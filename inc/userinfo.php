<?php
//error_reporting(e_all);


/*替换为你自己的数据库名（可从管理中心查看到）*/
$dbname = 'weixin';
 
/*从环境变量里取出数据库连接需要的参数*/
$host = "localhost";
$port = "3306";
$user = "weixin";
$pwd = "Wk*471000";
 
 
/*接着调用mysql_connect()连接服务器*/
$link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
if(!$link) {
    die("Connect Server Failed: " . mysql_error());
}
/*连接成功后立即调用mysql_select_db()选中需要连接的数据库*/
if(!mysql_select_db($dbname,$link)) {
    die("Select Database Failed: " . mysql_error($link));
}


 function CheckGroup($fromUsername)
 {
   include ("mysql.php");
   $result=mysql_query("select * from WX_User_Group where WX_OpenID='".$fromUsername."'") or die(mysql_error());
   $row=mysql_fetch_array($result);
   if (!$row)
   {
     return "0";
   }
   else
   {
     return $row['Group'];
   }
 }
//include ("response.php");
	


 echo CheckGroup("o2e-YuBgnbLLgJGMQykhSg_V3VRI");


//echo web_order11("13605725464");


?>