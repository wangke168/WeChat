<?php
/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/3/16
 * Time: 9:06
 *
 * 客人如果在确定订单10分钟后未付款就推送消息给用户
 *
 *
 *
 */

require_once("../classes/db.class.php");
require_once("../classes/response.class.php");
require_once("../inc/function.php");

$db = new DB();
$response=new responseMsg();
//$temptime = (strtotime($bbb) - strtotime("now")) / 60;
$row = $db->query("select * from wx_order_confirm where(ROUND( (UNIX_TIMESTAMP( NOW( ) ) - UNIX_TIMESTAMP( adddate ) ) /60) <:temptime)",
                    array("temptime" => "60"));
foreach ($row as $result) {
//    echo $result["WX_OpenID"];
    $response->responseV_Text("o2e-YuBgnbLLgJGMQykhSg_V3VRI","<a href='http://e.hengdianworld.com/yd_mp_s3.aspx?sellid=".$result[SellID]."&wxnumber=".$result["WX_OpenID"]."'>点我支付</a>");

}

/*
 * 继续付款连接
 *
 * http://e.hengdianworld.com/yd_mp_s3.aspx?sellid=V1603160051
 *
 */