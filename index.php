<?php

include("classes/app.class.php");
include("classes/response.class.php");
include("classes/handle.class.php");

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