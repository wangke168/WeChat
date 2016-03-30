<?php

/*
 * 客人确认订单时，触发该链接，往数据库插入数据
 *
 * 10分钟后发现客人未成功预定的话，自动推送消息给客人
 *
 *
 */
require_once("../classes/DB.class.php");
require_once("function.php");
/*
*  预订成功后从官网接受信息，反馈给客人微信
*/


$sellid = $_GET['sellid'];

//$encrypt=new encrypt();
//	$sellid="V1409140222";
//	$fromUsername = "o2e-YuBgnbLLgJGMQykhSg_V3VRI";
$fromUsername = $_GET['openid'];

//$fromUsername=$encrypt->passport_decrypt($fromUsername);
$fromUsername=authcode($fromUsername,'DECODE',0);


$db = new DB();
//查询是否已经发送过信息，避免二次发送


$eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
$focusdate= return_user_info($fromUsername, "AddDate");  //获取客人关注时间
$row = $db->query("INSERT INTO wx_order_confirm (WX_OpenID,SellID,eventkey,focusdate) VALUES (:fromUsername,:sellid,:eventkey,:focusdate)", array("fromUsername" => $fromUsername, "sellid" => $sellid, "eventkey" => $eventkey,"focusdate"=>$focusdate));

