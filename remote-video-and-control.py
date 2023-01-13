# EMS SUNLIGHT SERVICE IOT TEAM 22-11-2022

import RPi.GPIO as GPIO
import pymysql                #操作数据库
import time
from time import sleep

import ctypes                  #文字转语音,包括import sys
from ctypes import *
import sys

import cv2       #Camera 拍照和视频需要

import schedule  #比APScheduler要简单，但是没有APScheduler强大，无法持久化任务，也无法动态增加删减 通过 pip install schedule 即可安装。

#抓出网页原始码(要安装套件requests: pip install requests 和 pip install Beautifulsoup4 即bs4)
import requests
import bs4
import urllib.request as req

def pulse_width(num):  # 用于处理角度值转换到脉宽
    fm = 10.0 / 180.0
    num = num * fm + 2.5
    num = int(num * 10) / 10.0
    return num

global no_of_crawled, record_count , index , videoname, index_a, order_int
no_of_crawled = 1
index_no1 = 1
index_no2 = 1
record_count = 0
index = 0
index_a = 9
order_int = 1
videoname ="ABC.mp4"
fourcc_type = 'avc1'
headers={
        "User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36"
    }

url_control_word = "https://xxxx.com/zh/iot-photo-and-controler/"  #有控制字的页面
url_upload_1 = "https://xxxx.com/meeting_room_get_upload_video.php"
url_upload_2 = "https://xxxx.com/office_get_upload_video.php"
url_bilin="https://xxxx.com"                                       #发事件的方式，改变控制位，测试使用
url_ems="https://xxxx.com"

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

servopin1 = 23              #GPIO 23: 舵机完成垂直角度
button1 = 18
GPIO.setup(button1,GPIO.IN, pull_up_down = GPIO.PUD_DOWN)
GPIO.setup(servopin1, GPIO.OUT)
GPIO.output(servopin1, GPIO.LOW)

p1 = GPIO.PWM(servopin1,50) #50HZ

p1.start(pulse_width(90)) #初始化角度
sleep(0.5)
p1.ChangeDutyCycle(0) #清除当前占空比，使舵机停止抖动
sleep(1)

GPIO.setup(5, GPIO.OUT)         #A线
GPIO.output(5, GPIO.LOW)
GPIO.setup(6, GPIO.OUT)         #电机
GPIO.output(6, GPIO.LOW)
GPIO.setup(13, GPIO.OUT)        #风机
GPIO.output(13, GPIO.LOW)
GPIO.setup(19, GPIO.OUT)        #提示屏
GPIO.output(19, GPIO.LOW)
GPIO.setup(26, GPIO.OUT)        #工作灯
GPIO.output(26, GPIO.LOW)

GPIO.setup(10, GPIO.OUT)        #GPIO 10,9,11,24 视频头水平转角步进电机
GPIO.output(10, GPIO.LOW)
GPIO.setup(9, GPIO.OUT)
GPIO.output(9, GPIO.LOW)
GPIO.setup(11, GPIO.OUT)
GPIO.output(11, GPIO.LOW)
GPIO.setup(24, GPIO.OUT)
GPIO.output(24, GPIO.LOW)

GPIO.setup(4, GPIO.OUT)  #GPIO 04 做程序工作指示 闪烁频率：0.5
GPIO.output(4, GPIO.LOW)
p = GPIO.PWM(4, 0.5)     #频率 0.5
p.start(50)              #占宽比 50%

def get_conn():
    return pymysql.connect(
        host='127.0.0.1',
        user='root',
        password='30189',
        database='iot_v50',
        charset= 'utf8'
    )

def query_data(sql):
    conn = get_conn()
    try:
       cursor = conn.cursor(pymysql.cursors.DictCursor)
       cursor.execute(sql)
       return cursor.fetchall()
    finally:
        conn.close()

def insert_or_updata_data(sql):
    conn = get_conn()
    try:
       cursor = conn.cursor()
       cursor.execute(sql)
       conn.commit()
    finally:
        conn.close()

def Motor_Type_A_Drive(aa,bb,cc,dd,step_time,number_of_steps):
#aa bb cc dd 是GPIO引脚号，step_time 是脉宽（影响频率，定速度）
#number_of_steps 是步数，4096步转一周. 正值 方向顺时针  负值方向 逆时针
   global CamPitch
   CamPitch=1                 # 1: Run, 0:Stop
   import sys
   import time
   StepPins = [aa,bb,cc,dd]

   for pin in StepPins:

     GPIO.setup(pin,GPIO.OUT)
     GPIO.output(pin, False)

   Seq = [[1,0,0,1],
          [1,0,0,0],
          [1,1,0,0],
          [0,1,0,0],
          [0,1,1,0],
          [0,0,1,0],
          [0,0,1,1],
          [0,0,0,1]]
   StepCount = len(Seq)
   if number_of_steps >= 0:
     StepDir = -1
   if number_of_steps < 0:
     StepDir = 1
   if len(sys.argv)>1:
     WaitTime = int(sys.argv[1])/float(1000)
   else:
     WaitTime = step_time/float(1000)

   StepCounter = 0

   Step = abs(number_of_steps)
   while Step >= 0:
     if CamPitch == 0:
        Step=-1
     else:
        Step=Step-1

     for pin in range(0, 4):
       xpin = StepPins[pin]
       if Seq[StepCounter][pin]!=0:
         GPIO.output(xpin, True)
       else:
         GPIO.output(xpin, False)

     StepCounter += StepDir

     if (StepCounter>=StepCount):
       StepCounter = 0
     if (StepCounter<0):
       StepCounter = StepCount+StepDir

     time.sleep(WaitTime)
   GPIO.output(aa, GPIO.LOW)
   GPIO.output(bb, GPIO.LOW)
   GPIO.output(cc, GPIO.LOW)
   GPIO.output(dd, GPIO.LOW)
   return None

def post_order(button1): #按钮按下后，向网站发命令字
    time.sleep(0.5)     #等外部GPIO18状态完成和稳定
    if GPIO.input(button1) == 0: #联合该条件执行，不然会执行多2，3次
        global order_int
        print('\n')
        print('回调函数')
        res=requests.get(url_bilin,headers=headers,params={"video_position":order_int})
        print(res)
        res=requests.get(url_ems,headers=headers,params={"video_position":order_int})
        print(res)
        order_int = order_int + 1
        if (order_int >= 6):
           order_int = 1

def no1_camera_do():
    global record_count
    global index_no1
    global videoname
    if index_no1 < 10 :
       videoname ="image/No1 "+time.strftime('%Y-%m-%d-%H-%M-%S')+" v0"+str(index_no1)+".mp4"
    else:
       videoname ="image/No1 "+time.strftime('%Y-%m-%d-%H-%M-%S')+" v"+str(index_no1)+".mp4"

    cap = cv2.VideoCapture(2)
    codec = cv2.VideoWriter_fourcc(*fourcc_type)   # XVID is .mp4, MJPG is .avi  # MJPG没有报错, XVID有
    fps = 25                    # fps = 20.0 also can but 经实际测试，单线程下最高帧率为10帧/秒，且会变动，因此选择9.5帧/秒
                                  #若设置帧率与实际帧率不一致，会导致视频时间与音频时间不一致
    frameSize = (640, 480)
    out = cv2.VideoWriter(videoname, codec, fps, frameSize)  #('D:\Python\Meeting_room ' + imgname) + ".jpg"
    index_no1 = index_no1 + 1
    if index_no1 >= 11:
        index_no1 = 1
    while(cap.isOpened()):
      ret, frame = cap.read()
      if ret==True:
         out.write(frame)
         cv2.imshow('frame',frame)
         record_count += 1
         if(record_count > 500):     #如果这里是500，那么是500/pfs=20是25秒, pfs=9.5 so 190 is 20 seconds           
           record_count = 0
           print(videoname)
           break
      else:
         break
    cap.release()
    out.release()
    cv2.destroyAllWindows()

    print("过了no1_camera_do")
    time.sleep(10)
    files = {'mp4file': (videoname,open(videoname,'rb'),'mp4file',{})}
    res = requests.request("POST",url_upload_1, data = {"type":"1"}, files = files,headers={
        "User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36"
    })

def no2_camera_do():
    global record_count
    global index_no2
    global videoname
    if index_no2 < 10 :
       videoname ="image/No2 "+time.strftime('%Y-%m-%d-%H-%M-%S')+" v0"+str(index_no2)+".mp4"
    else:
       videoname ="image/No2 "+time.strftime('%Y-%m-%d-%H-%M-%S')+" v"+str(index_no2)+".mp4"

    cap = cv2.VideoCapture(0)
    codec = cv2.VideoWriter_fourcc(*fourcc_type)   # XVID is .mp4, MJPG is .avi  # MJPG没有报错, XVID有
    fps = 25                    # fps = 20.0 also can but 经实际测试，单线程下最高帧率为10帧/秒，且会变动，因此选择9.5帧/秒
                                  #若设置帧率与实际帧率不一致，会导致视频时间与音频时间不一致
    frameSize = (640, 480)
    out = cv2.VideoWriter(videoname, codec, fps, frameSize)  #('D:\Python\Meeting_room ' + imgname) + ".jpg"
    index_no2 = index_no2 + 1
    if index_no2 >= 11:
        index_no2 = 1
    while(cap.isOpened()):
      ret, frame = cap.read()
      if ret==True:
         out.write(frame)
         cv2.imshow('frame',frame)
         record_count += 1
         if(record_count > 500):     #如果这里是500，那么是500/pfs=20是25秒, pfs=9.5 so 190 is 20 seconds           
           record_count = 0
           print(videoname)
           break
      else:
         break
    cap.release()
    out.release()
    cv2.destroyAllWindows()

    print("过了no2_camera_do")
    time.sleep(10)
    files = {'mp4file': (videoname,open(videoname,'rb'),'mp4file',{})}
    res = requests.request("POST",url_upload_2, data = {"type":"1"}, files = files,headers={
        "User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36"
    })

def control_word_crawler():
    global no_of_crawled
    classString_1="order_word"
    #建立一个Requst物件，附加 Request Headers 的信息
    request=req.Request(url_control_word,headers={
        "User-Agent":"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36"
    })
    with req.urlopen(request) as response:
        data=response.read().decode("utf-8")
        #print(data)
    root=bs4.BeautifulSoup(data,"html.parser")       # 需要Beautifulsoup4帮助解析html格式文件
    # 第一步：取字：
    order_word = root.find("p", class_= classString_1)  # 寻找class = "order_word"的 p 标签
    #print(order_word)                                  #打印取到的
    print(order_word.string)                           #打印有p标签的包含的字符串
    admin = order_word.string[0:5]
    no1_position_x = int(order_word.string[5:7])
    no1_position_y = int(order_word.string[7:9])
    no2_position_x = int(order_word.string[9:11])
    no2_position_y = int(order_word.string[11:13])
    working_lighter_contral = order_word.string[13:20]
    screen_contral = order_word.string[20:27]
    meeting_room_video_on_off = order_word.string[27:34]
    office_video_on_off = order_word.string[34:41]
    fan_on_off = order_word.string[41:48]
    motor_on_off = order_word.string[48:55]
    A_line = order_word.string[55:62]
    B_line = order_word.string[62:69]
    message = order_word.string[69:119] #最多截取前60个字符
    # 1）无条件调整舵机
      #水平   (步进电机)
    sql = "select * from user"
    datas = query_data(sql)
    last_no1_position_x = datas[0].get('no1_position_x')
    print (last_no1_position_x)
    print (no1_position_x)
    """
    SQL语句中有一个变量时，写法参考以下方法
    使用 %s 占位符表示当前位置被变量替代，SQL语句后面使用 %(变量名) 表示需要插入的变量
    sql = "insert into goods_detail(Url) values ('%s')" %(Url)
    cursor.execute(sql)
    方法2：
    SQL语句中有多个变量时，方法1不可用，需要使用方法2
    SQL语句中只用 %s 写需要替代的变量，将需要替代的变量名放在 cursor.execute 中执行
    sql = "UPDATE goods_detail SET productPrice = %s,productName = %s,stock = %s where  url = %s"
    cursor.execute(sql,(GoodsDetailPrice,NewGoodsName,Stock, NewGoodsUrl))
    """
    sql = "update user set no1_position_x = %s where id=1" %(no1_position_x)
    insert_or_updata_data(sql)
    action_step = int((last_no1_position_x - no1_position_x)*11.38)
    print(action_step)
    Motor_Type_A_Drive(10,9,11,24,5,action_step)
      #垂直   (舵机)
    p1.ChangeDutyCycle(pulse_width(180-(no1_position_y)*2))
    sleep(0.1)
    p1.ChangeDutyCycle(0) #清除当前占空比，使舵机停止抖动
    sleep(0.01)
    sleep(2)
    # 2）操作开关
    if working_lighter_contral == "checked":
       GPIO.output(26, GPIO.HIGH)
    else:
       GPIO.output(26, GPIO.LOW)
    if screen_contral == "checked":
       GPIO.output(19, GPIO.HIGH)
    else:
       GPIO.output(19, GPIO.LOW)
    if fan_on_off == "checked":
       GPIO.output(13, GPIO.HIGH)
    else:
       GPIO.output(13, GPIO.LOW)
    if motor_on_off == "checked":
       GPIO.output(6, GPIO.HIGH)
    else:
       GPIO.output(6, GPIO.LOW)
    if A_line == "checked":
       GPIO.output(5, GPIO.HIGH)
    else:
       GPIO.output(5, GPIO.LOW)
    # 3）Camera 录视频并上传
    if meeting_room_video_on_off == "checked":
       no1_camera_do()
       sleep(15)
    if office_video_on_off == "checked":
       no2_camera_do()
       sleep(15)

    # 4)文字转语音
    #下载文件 wget http://simcommander.cn/download/haisantts-py.gz 解压出文件到当前工作目录下
    if message != "":
       import sys

       txt_message = message
       lib = ctypes.cdll.LoadLibrary("./libhaisantts.so")
       lib.startHaisanTTS.argtypes=[POINTER(c_char)]
       TTS=(c_char * 100)(*bytes(txt_message,'utf-8'))
       cast(TTS, POINTER(c_char))
       lib.startHaisanTTS(TTS)

    no_of_crawled = no_of_crawled + 1

GPIO.add_event_detect(button1, GPIO.FALLING, bouncetime=200) #设置为中断方式
GPIO.add_event_callback(button1, post_order)

schedule.every(5).minutes.do(control_word_crawler)  #每xx秒/分钟执行一次函数

# 发布后的周期任务需要用 run_pending 函数来检测是否执行，因此需要一个 While 循环不断地轮询这个函数。
while True:
    #启动服务
    schedule.run_pending()
    time.sleep(1)
#    GPIO.wait_for_edge(button1, GPIO.FALLING)
#    post_order()
#    time.sleep(0.5)
    if no_of_crawled >= 11:        #实际上，这个条件不可能出现,调程序用.
        schedule.clear()
        #break
        exit(0)
