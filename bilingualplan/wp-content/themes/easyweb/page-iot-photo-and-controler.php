<?php
get_header();

//wc_get_template( 'iot_templates/login.php' ); 

global $wpdb;
$wpdb -> show_errors();
$login_name = $wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2" , 1);
$datacollectionstate = $wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2" , 4);
if ($datacollectionstate == "OFF"){
  $displaybtnon = "block";
  $displaybtnoff = "none";
}
if ($datacollectionstate == "ON"){
  $displaybtnon = "none";
  $displaybtnoff = "block";
}

$no1_position_x = $_POST['no1_position_x'];
$no1_position_y = $_POST['no1_position_y'];
$no2_position_x = $_POST['no2_position_x'];
$no2_position_y = $_POST['no2_position_y'];
$working_lighter = $_POST['working_lighter'];
$screen = $_POST['screen'];
$fan_on_off = $_POST['fan_on_off'];
$motor_on_off = $_POST['motor_on_off'];
$meeting_room_video_on_off = $_POST['meeting_room_video_on_off'];
$office_video_on_off = $_POST['office_video_on_off'];
$work_route = $_POST['work_route'];
$Leave_message = $_POST['Leave_message'];
if($no1_position_x!=""){
    $wpdb -> update('ufq_iotdata', array('no1_position_x' => "$no1_position_x"), array('id' => 2));
    $wpdb -> update('ufq_iotdata', array('no1_position_y' => "$no1_position_y"), array('id' => 2));
    $wpdb -> update('ufq_iotdata', array('no2_position_x' => "$no2_position_x"), array('id' => 2));
    $wpdb -> update('ufq_iotdata', array('no2_position_y' => "$no2_position_y"), array('id' => 2));
    if($working_lighter == "checked"){
                $wpdb -> update('ufq_iotdata', array('working_lighter' => "$working_lighter"), array('id' => 2));
    }
    else{
                $wpdb -> update('ufq_iotdata', array('working_lighter' => "xxxxxxx"), array('id' => 2));
    }
    if($screen == "checked"){
        $wpdb -> update('ufq_iotdata', array('screen' => "$screen"), array('id' => 2));
    }
    else{
        $wpdb -> update('ufq_iotdata', array('screen' => "xxxxxxx"), array('id' => 2));
    }
    if($meeting_room_video_on_off == "checked"){
        $wpdb -> update('ufq_iotdata', array('meeting_room_video' => "$meeting_room_video_on_off"), array('id' => 2));
    }
    else{
        $wpdb -> update('ufq_iotdata', array('meeting_room_video' => "xxxxxxx"), array('id' => 2));
    }
    if($office_video_on_off == "checked"){
        $wpdb -> update('ufq_iotdata', array('office_video' => "$office_video_on_off"), array('id' => 2));
    }
    else{
        $wpdb -> update('ufq_iotdata', array('office_video' => "xxxxxxx"), array('id' => 2));
    }
  if($fan_on_off == "checked"){
      $wpdb -> update('ufq_iotdata', array('fan_on_off' => "$fan_on_off"), array('id' => 2));
  }
  else{
      $wpdb -> update('ufq_iotdata', array('fan_on_off' => "xxxxxxx"), array('id' => 2));
  }
  if($motor_on_off == "checked"){
      $wpdb -> update('ufq_iotdata', array('motor_on_off' => "$motor_on_off"), array('id' => 2));
  }
  else{
      $wpdb -> update('ufq_iotdata', array('motor_on_off' => "xxxxxxx"), array('id' => 2));
  }
    if($work_route == "check_a"){
        $wpdb -> update('ufq_iotdata', array('A_line' => "checked"), array('id' => 2));
        $wpdb -> update('ufq_iotdata', array('B_line' => "xxxxxxx"), array('id' => 2));
    }
    if($work_route == "check_b"){
        $wpdb -> update('ufq_iotdata', array('A_line' => "xxxxxxx"), array('id' => 2));
        $wpdb -> update('ufq_iotdata', array('B_line' => "checked"), array('id' => 2));
    }
    $wpdb -> update('ufq_iotdata', array('message' => "$Leave_message"), array('id' => 2));
}
$video_position=$_GET['video_position'];
if($video_position!=""){
    $wpdb -> update('ufq_iotdata', array('video_position' => $video_position), array('id' => 2));
}
// -> Start Define variables

// theme options variables
$easyweb_webnus_options	= easyweb_webnus_options();

// page options variables
$show_titlebar	= rwmb_meta( 'easyweb_page_title_bar_meta' );
$titlebar_fg	= rwmb_meta( 'easyweb_page_title_text_color_meta' );
$titlebar_bg	= rwmb_meta( 'easyweb_page_title_bg_color_meta' );
$titlebar_fs	= rwmb_meta( 'easyweb_page_title_font_size_meta' );
$sidebar_pos	= rwmb_meta( 'easyweb_sidebar_position_meta' );

// others variables
$have_sidebar	= $sidebar_pos ? true : false;

// -> End Define variables

// headline and breadcrubs
if ( $show_titlebar ) : ?>
	<section id="headline" style="<?php if ( ! empty( $titlebar_bg ) ) echo 'background-color: ' . $titlebar_bg . ';'; ?>">
		<div class="container">
			<h1 style="<?php if ( ! empty( $titlebar_fg ) ) echo 'color: ' . $titlebar_fg . ';'; if ( ! empty( $titlebar_fs ) ) echo ' font-size: ' . $titlebar_fs . ';'; ?>"><?php the_title(); ?></h1>
		</div>
	</section>
<?php endif; ?>

<!-- Start Page Content -->
<section id="main-content" class="container">
<?php
  //get current directory
	$working_dir = getcwd();
	//get image directory
	$img_dir_meeting_room = $working_dir . "/wp-content/uploads/meeting_room_live_photos/";
	//change current directory to image directory
	chdir($img_dir_meeting_room);
	//using glob() function get images 
	$files_meeting_room = glob("*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE );
	//again change the directory to working directory
	chdir($working_dir);
//	$imgurl = $img_dir.$files[0];
  $imgurl_meeting_room = "https://bilingualplan.com/wp-content/uploads/meeting_room_live_photos/".$files_meeting_room[0];

  //get image directory
	$img_dir_office = $working_dir . "/wp-content/uploads/office_live_photos/";
	//change current directory to image directory
	chdir($img_dir_office);
	//using glob() function get images 
	$files_office = glob("*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE );
	//again change the directory to working directory
	chdir($working_dir);
//	$imgurl = $img_dir.$files[0];
  $imgurl_office = "https://bilingualplan.com/wp-content/uploads/office_live_photos/".$files_office[0];
?>    
<!doctype html>
<html>
<head>
   <title>EMS IOT</title>
   <link rel="stylesheet" type="text/css" href='https://bilingualplan.com/wp-content/themes/easyweb/iot_static/style.css'/>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
   <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ????????????????????? ????????????????????????????????? -->
   <script src="https://code.jquery.com/jquery-3.5.0.js"></script> <!--?????????????????????JavaScript ??????????????????-->
</head>

<body>
   <div id= "iot-container" class="iot-container" style="position:relative;top:-30px;">
     <div id='side_cam' class="img-video" style="position:relative;top:5px;text-align: center;">
            	  <div id="box" class="box">                	
                    <s class="prev" title="?????????????????????"></s>
                    <s class="next" title="?????????"></s>
                    <img src="<?php echo $imgurl_meeting_room; ?>"/><i class="vline"></i>
                  </div> 
		            <div style="text-align: center;">
				            <a id = "imgfilename_meeting_room" class ="imgfilename"><?php echo substr($files_meeting_room[0],0,36); ?></a><br>
                    </div> 
   </div>

<!--
       <div id='mobile_cam' class="img-video" align="center" style="position:relative;top:5px;">
          <iframe   name="frameMobilphone"   src="http://192.168.1.124:8081" width="800" height="574" style="border:2px solid #deb887"></iframe>
       </div>

       <div id='mobile_cam_mute' class="img-video" align="center" style="position:relative;top:5px;">
          <iframe   name="frameMobilphone"   src="http://192.168.1.124:8081/video" width="800" height="574" style="border:2px solid #deb887"></iframe>
       </div>
-->
        <div style="text-align: center; position:relative;top:10px">
          <div class="btn-group">
            <button id = "meeting_room" type="button" class="btn btn-default" onclick="meeting_room_Function()">#1 ??????</button>
            <button id = "office" type="button" class="btn btn-default" onclick="office_Function()">#2 ??????</button>
          </div>
          <div class="btn-group">
            <button id = "next_pic" type="button" class="btn btn-default" onclick="next_pic_Function()"><</button>
            <button id = "previous_pic" type="button" class="btn btn-default" onclick="previous_pic_Function()">></button>
          </div>
          <div class="btn-group">
            <button id = "language_english" type="button" class="btn btn-default">ENGLISH</button>
            <button id = "language_chinese" type="button" class="btn btn-default">??????</button>
          </div>
        </div>
    </div>
    <div style="display:none">
        <div id = "Dada_collection_on_btn" style="text-align: center;">
           <button id = "Dada_collection_on" class = "btn btn-block btn-md btn-primary" style = "color: rgb(255,66,66);width:80px;font-weight: 700;" onclick="Dada_collection_off_Function()">????????????</button>
	         <p id = "mesgarea"></p>
        </div>
	      <div id = "Dada_collection_off_btn" style="text-align: center;">
           <button id = "Data_collection_off" class = "btn btn-block btn-md btn-primary" style = "color: rgb(164,164,164);width:80px;font-weight: 700;" onclick="Dada_collection_on_Function()">????????????</button>
           <p id = "mesgarea"></p>
        </div>
    </div>   
      <div class="stat-item " data-width-tablet="180" data-width-desktop="210" style="text-align: center;display:none">
      <p id = "Data_collection_state" class="number"><?php echo ($datacollectionstate); ?></p>
      </div>

        <div style="background:#f0f3f9; position:relative; top:-2px; height: 110px; text-align: center; margin-left:-30px; margin-right:-20px; ">
          <!--            form ???????????????????????????<lable>??? for ??????????????????????????????????????? id ???????????????????????????
                                            radio ????????????? ??????name??????????????????name????????????????????????????????????????????????
          -->
    <form action="" method="post" >
                    <div id="setting-01" style="position:relative;top:10px; left:10px">
                    <label for="no1_position_x"id = "no1_camera" style="float:left;">#1 ?????? H/V </label>
                    <input class="position" id="no1_position_x" type="range" min="10" style="width:90%"
                                 value="<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",5)); ?>"
                                 max="80" step="1" name="no1_position_x" onchange="document.getElementById('show_no1_x').innerHTML=value*2-90"><span id = "show_no1_x"><?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",5)*2-90); ?></span><span>&deg;</span>
                    <input class="position" id="no1_position_y" type="range" min="10" style="width:90%; position:relative;top:1px"
                                 value="<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",6)); ?>" 
                                 max="80" step="1" name="no1_position_y" onchange="document.getElementById('show_no1_y').innerHTML=value*2-90"><span id = "show_no1_y"><?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",6)*2-90); ?></span><span>&deg;</span>   
                    </div>
                    <div id="setting-01" style="position:relative; top:10px">
                    <label for="no2_camera" id = "no2_camera" style="float:left;">#2 ?????? H/V </label>  
                    <input class="position" id="no2_position_x" type="range" min="10" style="width:90%"
                                 value="<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",7)); ?>"
                                 max="80" step="1" name="no2_position_x" onchange="document.getElementById('show_no2_x').innerHTML=value*2-90"><span id = "show_no2_x"><?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",7)*2-90); ?></span><span>&deg;</span>
                    <input class="position" id="no2_position_y" type="range" min="10" style="width:90%; position:relative;top:1px"
                                 value="<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",8)); ?>" 
                                 max="80" step="1" name="no2_position_y" onchange="document.getElementById('show_no2_y').innerHTML=value*2-90"><span id = "show_no2_y"><?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",8)*2-90); ?></span><span>&deg;</span>
                    </div>
                    <div id="setting-02" style="position:relative;top:10px;">
                    <label for="video_on_off" id = "video_on_off" >????????????: </label> <br>
                    <input type="checkbox" id = "meeting_room_video_on_off" name="meeting_room_video_on_off" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",11)); ?>>
                    <label for="meeting_room_video_on_off" id = "meeting_room_video" style = "font-weight: 400;">&nbsp;#1 ??????</label><br>
                    <input type="checkbox" id = "office_video_on_off" name="office_video_on_off" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",12)); ?>>
                    <label for="office_video_on_off" id = "office_video" style = "font-weight: 400;">&nbsp;#2 ??????</label>
                    </div>
                    <div id="setting-02" style="position:relative;top:10px;">
                    <label for="seetting_contral" id = "seetting_contral">????????????: </label> <br>
                    <input type="checkbox" id = "working_lighter_contral" name="working_lighter" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",9)); ?>>
                    <label for="working_lighter_contral" id = "working_lighter" style = "font-weight: 400;">&nbsp;?????????</label><br>
                    <input type="checkbox" id = "screen_contral" name="screen" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",10)); ?>>
                    <label for="creen_contral" id = "screen" style = "font-weight: 400;">&nbsp;?????????</label>
                    </div>
                    <div id="setting-02" style="position:relative;top:10px;">
                    <label for="on_off" id = "video_on_off" ></label>&nbsp;<br>
                    <input type="checkbox" id = "fan_on_off" name="fan_on_off" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",13)); ?>>
                    <label for="fan_on_off" id = "fan_on_off" style = "font-weight: 400;">&nbsp;??????</label><br>
                    <input type="checkbox" id = "motor_on_off" name="motor_on_off" value="checked" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",14)); ?>>
                    <label for="motor_on_off" id = "motor_on_off" style = "font-weight: 400;">&nbsp;??????</label>
                    </div>
                    <div id="setting-03" style="position:relative;top:10px;">
                    <label for="select_product_line" id = "select_product_line">?????????: </label> <br> 
                    <input type="radio" id="a_line" name="work_route" value="check_a" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",15)); ?>>
                    <label for="a_line" id = "a_product_line" style = "font-weight: 400;">&nbsp;A ???</label><br>
                    <input type="radio" id="b_line" name="work_route" value="check_b" <?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",16)); ?>>
                    <label for="b_line" id = "b_product_line" style = "font-weight: 400;">&nbsp;B ???</label>
                    </div>
          </div>
          <div style="background:#f0f3f9; position:relative; top:-2px; height: 60px; text-align: center; margin-left:-30px; margin-right:-20px; ">
                    <div id="setting-04" style="position:relative; margin-left:px;10">
                    <label for="leave_message" id = "leave_message" style="float:left;position:relative;left:10px;">??????: </label><br>
                    <input type = textarea id="leave_message_text" name="Leave_message" rows="2" cols="3" style="width:95%" value="<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",18)); ?>"></textarea>
                    </div>
                    <div id="setting-05" style="position:relative;top:10px;">
 <!--                  <button type="button" id = "Save_Settings" class = "btn btn-block btn-md btn-primary" style = "position:relative;top:50px;color: white; rgb(255,66,66);width:100px;font-weight: 700;" onclick="Save_setting_Function()">????????????</button> -->
                    <input type="submit" id = "Save_Settings" value="???  ???"  class = "submitbtn"/>
 <!--                  <input type="reset" value="??????" class = "btn btn-block btn-md btn-primary" style = "color: white; rgb(255,66,66);width:60px; height:20;font-weight: 700;"/> -->
                    </div>          
          </div>
    </form>      
<!-- 
       <div align="center" style="position:relative;top:10px;">
         <a id = "exit_page_1" href="/"><font color="#0000ff">Exit the page safely</font></a>
       </div>  --> 
       <div style="text-align: center;">
        <a id = "login_name">dmin</a>
        <a id = "login_link_text">, ??????????????????????????????:  </a>
        <a id = "login_time">01-01-2022</a>
       </div>
  <!--    <div align="center" style="display:none"> -->
      <div style="text-align: center;display:none;">
      <p id = "order_word" class="order_word"><?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",1)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",5)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",6)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",7)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",8)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",9)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",10)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",11)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",12)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",13)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",14));
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",15)); 
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",16));
                           echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",18));?>
          </p>
      </div>
      <br>

</body>
<script>
//   global $wpdb;  ??????????????????????????????????????????????????????????????????????????? $login_name ??????
     var mesg = "Waiting for...    ??????... ";
     var login_name = '<?php echo ($login_name); ?>';
     document.getElementById("login_name").innerHTML=login_name;
     var login_time = '<?php echo ($wpdb -> get_var("SELECT * FROM `ufq_iotdata` WHERE `id` = 2",3)); ?>';
     document.getElementById("login_time").innerHTML=login_time;

     var x = document.getElementById("Dada_collection_off_btn");
          {
            x.style.display = "<?php echo "$displaybtnon"; ?>";
          }
     var x = document.getElementById("Dada_collection_on_btn");
          {
            x.style.display = "<?php echo "$displaybtnoff"; ?>";
          }  

      function Dada_collection_on_Function()
       {  
        $.post("https://bilingualplan/?datacollection=ON",this.id,function(data,status){});
        document.getElementById("mesgarea").innerHTML=mesg;
        location.reload(); 
       }
       
     function Dada_collection_off_Function()
       {  
        $.post("https://bilingualplan/?datacollection=OFF",this.id,function(data,status){});
        document.getElementById("mesgarea").innerHTML=mesg;
        location.reload();
       }
    function Save_setting_Function()
       {  
        var object = document.getElementById("working_lighter_contral");
        var volume = object.checked;
        alert(volume);
        var no1_position_x = document.getElementById("no1_position_x").value;
        var no1_position_y = document.getElementById("no1_position_y").value;
        var no2_position_x = document.getElementById("no1_position_x").value;
        var no2_position_y = document.getElementById("no1_position_y").value;
        var working_lighter_contral = document.getElementById("working_lighter_contral").checked;
        var screen_contral = document.getElementById("screen_contral").checked;
        var meeting_room_video = document.getElementById("meeting_room_video_on_off").checked;
        var office_video = document.getElementById("office_video_on_off").checked;
        var a_line = document.getElementById("a_line").checked;
        var b_line = document.getElementById("b_line").checked;
        var leave_message = document.getElementById("leave_message_text").value;
        alert(a_line);
        alert(leave_message);
       }
      
     document.getElementById("language_chinese").onclick = function() 
     {
     document.getElementById("meeting_room").innerText ="#1 ??????";
     document.getElementById("office").innerText ="#2 ??????";
     document.getElementById("seetting_contral").innerText ="????????????:";
     document.getElementById("working_lighter").innerText ="?????????";
     document.getElementById("screen").innerText =" ?????????"; 
     document.getElementById("video_on_off").innerText ="????????????:"; 
     document.getElementById("meeting_room_video").innerText ="#1 ??????"; 
     document.getElementById("office_video").innerText ="#2 ??????";
     document.getElementById("select_product_line").innerText ="?????????:"; 
     document.getElementById("a_product_line").innerText ="A ???"; 
     document.getElementById("b_product_line").innerText ="B ???";
     document.getElementById("leave_message").innerText ="??????:"; 
     document.getElementById("no1_camera").innerText ="#1?????? H/V"; 
     document.getElementById("no2_camera").innerText ="#2?????? H/V";
     document.getElementById("Save_Settings").value ="??????";
     document.getElementById("fan_on_off").innerText ="??????"; 
     document.getElementById("motor_on_off").innerText ="??????";
     }
     
     document.getElementById("language_english").onclick = function()
     {
     document.getElementById("meeting_room").innerText ="#1 CAMERA";
     document.getElementById("office").innerText ="#2 CAMERA";
     document.getElementById("seetting_contral").innerText ="CONTRAL:";
     document.getElementById("working_lighter").innerText ="LIGHTER";
     document.getElementById("screen").innerText ="SCREEN"; 
     document.getElementById("video_on_off").innerText ="C.CONT:"; 
     document.getElementById("meeting_room_video").innerText ="#1 C."; 
     document.getElementById("office_video").innerText ="#2 C.";
     document.getElementById("select_product_line").innerText ="P.LINE:"; 
     document.getElementById("a_product_line").innerText ="A LINE";
     document.getElementById("b_product_line").innerText ="B LINE";
     document.getElementById("leave_message").innerText ="LEAVEL MESSAGE:"; 
     document.getElementById("no1_camera").innerText ="#1 CAMERA H/V"; 
     document.getElementById("no2_camera").innerText ="#2 CAMERA H/V";
     document.getElementById("Save_Settings").value ="SAVE";
     document.getElementById("fan_on_off").innerText ="FAN"; 
     document.getElementById("motor_on_off").innerText ="MOTOR";
     }
     
var eleBox = document.getElementById("box"), eleImg = null, eleLink = null;
var indexImage = 0, indexLoop = 0,
	// arrImgUrl??????????????????????????????????????????????????????????????????????????????????????????????????????
arrImgUrl = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var imgfilename = new Array();

var files_meeting_room_str = "<?php echo implode($files_meeting_room) ?>";   //???????????????????????????
var files_office_str = "<?php echo implode($files_office) ?>";

for (var i=0;i<25;i++){ 
  imgfilename[i] = "meeting_room_live_photos/" + files_meeting_room_str.substr(i*40,40);  //???????????????????????????
}

function meeting_room_Function(){           
    for (var i=0;i<25;i++){ 
  imgfilename[i] = "meeting_room_live_photos/" + files_meeting_room_str.substr(i*40,40);
    }
}
       
function office_Function(){
    for (var i=0;i<25;i++){ 
  imgfilename[i] = "office_live_photos/" + files_office_str.substr(i*34,34);
    }  
}

if (eleBox && 
	(eleImg = eleBox.getElementsByTagName("img")[0]) &&
	(eleLink = eleBox.getElementsByTagName("s")).length === 2)
{
	for (; indexLoop < 2; indexLoop += 1) {
		(function(index) {
			eleLink[index].onclick = function() {
				var indexWill = indexImage + (index? 1: -1);
				if (arrImgUrl[indexWill]) {
				//	eleImg.src = eleImg.src.replace(/Study-2022-P\d/, "Study-2022-P" + arrImgUrl[indexWill]);
					eleImg.src = "https://bilingualplan.com/wp-content/uploads/".concat(imgfilename[indexWill]);
          document.getElementById("imgfilename_meeting_room").innerText =imgfilename[indexWill].substring(imgfilename[indexWill].indexOf("/") + 1,imgfilename[indexWill].indexOf("."));
					indexImage = indexWill;
				}
				if (indexWill <= 0) {
					eleLink[0].title = "This is the first picture";
				} else {
					eleLink[0].title = "Previous";
				}
				if (indexWill >= arrImgUrl.length - 1) {
					eleLink[1].title = "This is the last picture";	
				} else {
					eleLink[1].title = "Next";
				}
				return false;
			};
			// ???????????????
			eleLink[index].onmouseup = function(event) {
				event = event || window.event;
				var target = this;
				if (/^2|6|4|7$/.test(event.button)) {
					target.style.visibility = "hidden";
					setTimeout(function() { target.style.visibility = "visible";}, 16);			
				}		
			};
		})(indexLoop);
	}
}

var index_a = 0;
function next_pic_Function(){
     document.getElementById("box").getElementsByTagName("img")[0].src = "https://bilingualplan.com/wp-content/uploads/".concat(imgfilename[index_a]);
     document.getElementById("imgfilename_meeting_room").innerText =imgfilename[index_a].substring(imgfilename[index_a].indexOf("/") + 1,imgfilename[index_a].indexOf("."));
	 index_a = index_a - 1;
     if (index_a <= -1){
     index_a = 23;    
   }
}

function previous_pic_Function(){
     document.getElementById("box").getElementsByTagName("img")[0].src = "https://bilingualplan.com/wp-content/uploads/".concat(imgfilename[index_a]);
     document.getElementById("imgfilename_meeting_room").innerText =imgfilename[index_a].substring(imgfilename[index_a].indexOf("/") + 1,imgfilename[index_a].indexOf("."));
	 index_a = index_a + 1;
     if (index_a >= 24){
     index_a = 0;    
   }
}
</script>
</html>
</section>
<?php
get_footer();