<?php
require("../classes/DB.class.php");

require("function.php");
/*
*  预订成功后从官网接受信息，反馈给客人微信
*/


$sellid = $_GET['sellid'];
//	$sellid="V1409140222";
//	$fromUsername = "o2e-YuBgnbLLgJGMQykhSg_V3VRI";
$fromUsername = $_GET['openid'];

$db = new DB();
//查询是否已经发送过信息，避免二次发送


$eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
$row = $db->query("INSERT INTO WX_Order_Confirm (WX_OpenID,SellID,eventkey) VALUES (:fromUsername,:sellid,:eventkey)", array("fromUsername" => $fromUsername, "sellid" => $sellid, "eventkey" => $eventkey));
