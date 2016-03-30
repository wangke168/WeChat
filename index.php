<?php

require_once("classes/app.class.php");
require_once("classes/response.class.php");
require_once("classes/handle.class.php");
require_once("classes/Db.class.php");
require_once("classes/active.class.php");
require_once("classes/tour.class.php");
require_once("classes/query.class.php");

define("TOKEN", "hdtravel");
define("AppID", "wx3e632d57ac5dcc68");
define("EncodingAESKey", "Z1NfjF6r1huTCNxWKPYy3Ac3bPa2lfIiWo9sNJ4r3E0");

//require_once('inc/wxBizMsgCrypt.php');

$wechatObj = new wechatCallbackapi();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
} else {
    $wechatObj->valid();
}


?>