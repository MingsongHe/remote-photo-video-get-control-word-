<?php
// 接收文件
var_dump($_FILES); // 区别于$_POST、$_GET
print_r($_FILES);
$file = $_FILES["img"];
// 先判断有没有错
if ($file["error"] == 0) {
 // 成功 
 // 判断传输的文件是否是图片，类型是否合适
 // 获取传输的文件类型
 $typeArr = explode("/", $file["type"]);
 if($typeArr[0]== "image"){
  // 如果是图片类型
  $imgType = array("png","jpg","jpeg");
  if(in_array($typeArr[1], $imgType)){ // 图片格式是数组中的一个
   // 类型检查无误，保存到文件夹内
   // 给图片定一个新名字 (或者使用时间戳，防止重复)
   date_default_timezone_set('PRC'); //设置时区
   $imgname = "wp-content/uploads/office_live_photos/".$file["name"];
   // 大写H，是24小时制，小写h则12小时制
   // 将上传的文件写入到文件夹中 (使用函数 move_uploaded_file()）
   // 参数1: 图片在服务器缓存的地址
   // 参数2: 图片的目的地址（最终保存的位置）
   // 最终会有一个布尔返回值
//   $bol = move_uploaded_file($file["tmp_name"], $imgname);
   $bol = move_uploaded_file($file["tmp_name"], $imgname);
   if($bol){
    echo "这是在bilingualplan网站上的office_get_upload_image.php完成的。";
    echo "\r\n";
    echo "图片成功上传到 wp-content/uploads/office_live_photos/";
    echo "\r\n";
    echo "正常，OK!";
   } else {
    echo "上传失败！";
    echo $imgname;
    echo $bol;
    echo $file["tmp_name"];
   };
  };
 } else {
  // 不是图片类型
  echo "没有图片，再检查一下吧！";
 };
} else {
 // 失败
 echo $file["error"];
};
$path = "./wp-content/uploads/office_live_photos/";
$p = scandir($path);
unlink($path.$p[2]);
?>