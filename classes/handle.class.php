<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/2/24
 * Time: 16:00
 */
class handle
{
    /*
       * 正常文字回复
       */
    public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $keyword = trim($postObj->Content);
        $responseMsg = new responseMsg();
        switch ($keyword) {
            case "天气":
                $responseMsg->weather($fromUsername);
                break;
            case "y":
                $responseMsg->responseV_News($fromUsername, "", 1);
                break;
            case "wxh":
                $responseMsg->responseV_Text($fromUsername, $fromUsername);
                break;
            case "a":
                $responseMsg->responseV_Text($fromUsername, return_user_info($fromUsername, 'eventkey'));
                break;
            case "刮刮乐":
                Query_ggl($fromUsername);
                break;
            default:
                $responseMsg->request_keyword($fromUsername, $keyword);
                break;
        }
    }

    /*
    *用户事件操作反馈，
    *subscribe：      关注
    *unsubscribe：    取消关注
    *CLICK：			点击
    */
    public function handleEvent($object)
    {
        $fromUsername = $object->FromUserName;
        $toUsername = $object->ToUserName;
        $contentStr = "";
        $responseMsg = new responseMsg();
        switch ($object->Event) {
            case "subscribe":
                $eventkey = $object->EventKey;
                if (substr($eventkey, 0, 7) == 'qrscene') {
                    $eventkey = substr($eventkey, 8);
                }

                $responseMsg->request_subscribe($fromUsername, $toUsername, $eventkey);
                //      responseV_News($fromUsername, $eventkey, "1");

                insert_user_info($fromUsername, $eventkey, 'subscribe');
                /*
                            if ($fromUsername=='o2e-YuBgnbLLgJGMQykhSg_V3VRI')
                            {
                                echo responseText($fromUsername,$toUsername, $eventkey);
                            }
                            else{
                                responseV_News($fromUsername, $eventkey, "1");
                            }
                */
//      makeNews($fromUsername,$toUsername,"关注");
                //    request_subscribe($fromUsername,$toUsername);

                break;
            case "SCAN":
                $eventkey = $object->EventKey;
                if (substr($eventkey, 0, 7) == 'qrscene') {
                    $eventkey = substr($eventkey, 8);
                }
                $responseMsg->responseV_News($fromUsername, $eventkey, "1");
                //   request_subscribe($fromUsername,$toUsername,$eventkey);
                insert_user_info($fromUsername, $eventkey, "SCAN");


                break;
            case "unsubscribe":
                insert_unsubscribe_info($fromUsername);
                break;
            case "CLICK":
                switch ($object->EventKey) {

                    case "2":
//          handleClick($fromUsername,"V1001");
                        $responseMsg->request_menu($fromUsername, $toUsername, "2");
                        break;
                    case "3":
//          handleClick($fromUsername,"V1002");
                        $responseMsg->request_menu($fromUsername, $toUsername, "3");
                        break;
                    case "4":
//          handleClick($fromUsername,"V1003");
                        $responseMsg->request_menu($fromUsername, $toUsername, "4");
                        break;
                    case "5":
//          handleClick($fromUsername,"V1004");
                        $responseMsg->request_menu($fromUsername, $toUsername, "5");
                        break;
                    case "6":
//          handleClick($fromUsername,"V1005");
                        $responseMsg->request_menu($fromUsername, $toUsername, "6");
                        break;
                    case "7":
//          handleClick($fromUsername,"V2001");
                        $responseMsg->request_menu($fromUsername, $toUsername, "7");
                        break;
                    case "8":
//         handleClick($fromUsername,"V2002");
                        $responseMsg->request_menu($fromUsername, $toUsername, "8");
                        break;
                    case "9":
//          handleClick($fromUsername,"V2003");
                        $responseMsg->request_menu($fromUsername, $toUsername, "9");
                        break;
                    case "V2005":
//          handleClick($fromUsername,"V2005");
                        $responseMsg->request_menu($fromUsername, $toUsername, "礼品激活");
                        break;
                    case "13":
//          handleClick($fromUsername,"V3001");
                        responseV_Text($fromUsername, "横店影视城官方客服电话" . "\n" . "400-9999141");
                        break;
                    case "14":
//          handleClick($fromUsername,"V3002");
                        request_menu($fromUsername, $toUsername, "14");
                        break;
                    case "15":
//          handleClick($fromUsername,"V3003");
                        $responseMsg->request_menu($fromUsername, $toUsername, "15");
                        break;
                    case "16":
//          handleClick($fromUsername,"V3004");
                        $responseMsg->request_menu($fromUsername, $toUsername, "16");
                        break;
                    case "18":
                        $responseMsg->request_menu($fromUsername, $toUsername, "18");
                        break;
                    default:
                        $contentStr[] = array("Title" => "默认菜单回复", "Description" => "欢迎关注横店影城城", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "weixin://addfriend/beancube");
                        break;
                }
                break;
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                break;
        }
    }


    /*
    *识别用户语音回复
    * @param    string       $picurl      读取图片地址
    * @return   string               输出结果
    */

    function handleVoice($postObj)
    {
        $fromUsername = $postObj->FromUserName;
//    $toUsername = $postObj->ToUserName;
        /*
         $Recognition = $postObj->Recognition;

         $msgId = $postObj->MsgI

         //判断客人是不是扫年会二维码关注的
         $eventkey = return_eventkey_info(return_user_info($fromUsername,"eventkey"));
         if ($eventkey=="1142") {


     //    $CreateTime = $postObj->CreateTime;
     //    $MediaId = $postObj->MediaId;
     //    $Format = $postObj->Format;
             $msgId = (string)$msgId;
             $mem = new Memcache;
             $mem->connect("127.0.0.1", 11211);
             $access_msgId = $mem->get("access_msgId1");
             if ($msgId != $access_msgId) {
                 $mem->set("access_msgId1", $msgId, 0, 20);
                 //       $access_msgId = $mem->get("access_msgId");

                 //     $Contentstr = "识别出您的语音：" . $Recognition . "。";
                 //       responseV_Text($fromUsername, $Contentstr);
                 //  responseText($fromUsername,$toUsername, $Contentstr);

                 //如果有语音活动
                 if (strstr($Recognition, '红包') != '') {
                     Take_Money("61", $fromUsername, "6", "1");
                     //  $Contentstr="您好，您是要喊出红包吗，请点击<a href=\"http://4.eastsun.duapp.com/active/post.php?openid=".$fromUsername."\">输入有效订单号。</a>";
                 }
                 elseif(strstr($Recognition, '庙会') != '')
                 {
                     Take_Money("71", $fromUsername, "6", "10");
                 }
                 elseif(strstr($Recognition, '新年') != '')
                 {
                     Take_Money("71", $fromUsername, "6", "10");
                 }
                 elseif(strstr($Recognition, '横店') != '')
                 {
                     Take_Money("66", $fromUsername, "6", "1");
                 }
                 else {
                     responseV_Text($fromUsername, "您是想喊出红包吗，发音要标准哦。");
                 }
             }
         }
     */
        //   else
        //   {
        $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
        responseV_Text($fromUsername, $contentStr);
        //   }

    }

    //客人发送图片的保存回复
    function handleImage($postObj)
    {
        include "mysql.php";
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $PicUrl = $postObj->PicUrl;
        $MediaId = $postObj->MediaId;
        $MsgId = $postObj->MsgId;

        mysql_query("INSERT INTO WX_Image_Receive (WX_OpenID,PicUrl,MediaId,MsgId) VALUES ('" . $fromUsername . "','" . $PicUrl . "','" . $MediaId . "','" . $MsgId . "')") or die(mysql_error());
        mysql_close($link);

        $Contentstr = "您好，图片接收成功";

        echo responseText($fromUsername, $toUsername, $Contentstr);
    }

} 