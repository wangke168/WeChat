<?php
//error_reporting(e_all);


/*�滻Ϊ���Լ������ݿ������ɴӹ������Ĳ鿴����*/
$dbname = 'weixin';
 
/*�ӻ���������ȡ�����ݿ�������Ҫ�Ĳ���*/
$host = "localhost";
$port = "3306";
$user = "weixin";
$pwd = "Wk*471000";
 
 
/*���ŵ���mysql_connect()���ӷ�����*/
$link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
if(!$link) {
    die("Connect Server Failed: " . mysql_error());
}
/*���ӳɹ�����������mysql_select_db()ѡ����Ҫ���ӵ����ݿ�*/
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