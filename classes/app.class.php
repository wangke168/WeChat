<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/2/23
 * Time: 14:16
 */
class wechatCallbackapi
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $tmpArr = array(TOKEN, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        include("inc/query.php");
        include("inc/function.php");
        include("inc/Activity.php");
        include("inc/active.php");
        include("inc/event.php");
        include("inc/response.php");
        @$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {

            // 先返回一个信号
            ignore_user_abort(true);
            ob_start();
            // do initial processing here
            echo 'success'; // send the response
            header('Connection: close');
            header('Content-Length: ' . ob_get_length());
            ob_end_flush();
            ob_flush();
            flush();

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $time = time();
            $RX_TYPE = trim($postObj->MsgType);

            //检测客人的操作，文本，事件或其他
            switch ($RX_TYPE) {
                case "text":
                    $this->handleText($postObj);
                    break;
                case "event":
                    handleEvent($postObj, $fromUsername, $toUsername);
                    break;
                case "image";
                    handleImage($postObj);
                    break;
                case "voice";
                    handleVoice($postObj);
                    break;
                /*
                case "location":
                  $label=$loction_x = $postObj->Label;
                  $loction_x = $postObj->Location_X;
                  $loction_y = $postObj->Location_Y;
                  daohan($loction_x,$loction_y,$fromUsername, $toUsername);
                      //dh($fromUsername, $toUsername);
                 break;
                 */
                default:
                    $resultStr = "Unknow msg type: " . $RX_TYPE;
                    break;
            }
//                echo $resultStr;
        } else {
            echo "";
            exit;
        }
    }

    public function handleText($postObj)
    {

        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        //  $msgId = $postObj->MsgId;
        //   $time = time();


        /*     require_once 'BaeMemcache.class.php';
             $mem = new BaeMemcache();
                  //查看cache，客人是否输入了姓名
             $aaa=$mem->get($fromUsername."_do");
             if(!empty($aaa))
             {
               bd_cache($keyword,$textTpl, $fromUsername, $toUsername, $time);
             }
             else
             {
                 */
        if (!empty($keyword)) {
            if (return_user_info($fromUsername, "eventkey") == '8888') {
                $temp_array = array();
                $temp_array = str_split($keyword, 27);
                if ($temp_array[0] == '去横店穿越去横店飞') {
                    responseV_Text($fromUsername, "cg");
                } else {
                    handleText_normal($fromUsername, $keyword);
                }
            } else {
                handleText_normal($fromUsername, $keyword);

            }
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
