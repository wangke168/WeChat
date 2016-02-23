<?php
/*
用户事件操作反馈，
subscribe：      关注
unsubscribe：    取消关注
CLICK：			点击
*/
function handleEvent($object, $fromUsername, $toUsername)
{
    $contentStr = "";
    switch ($object->Event) {
        case "subscribe":
            $eventkey = $object->EventKey;
            if (substr($eventkey, 0, 7) == 'qrscene') {
                $eventkey = substr($eventkey, 8);
            }
            request_subscribe($fromUsername,$toUsername,$eventkey);
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
            responseV_News($fromUsername, $eventkey, "1");
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
                    request_menu($fromUsername, $toUsername, "2");
                    break;
                case "3":
//          handleClick($fromUsername,"V1002");
                    request_menu($fromUsername, $toUsername, "3");
                    break;
                case "4":
//          handleClick($fromUsername,"V1003");
                    request_menu($fromUsername, $toUsername, "4");
                    break;
                case "5":
//          handleClick($fromUsername,"V1004");
                    request_menu($fromUsername, $toUsername, "5");
                    break;
                case "6":
//          handleClick($fromUsername,"V1005");
                    request_menu($fromUsername, $toUsername, "6");
                    break;
                case "7":
//          handleClick($fromUsername,"V2001");
                    request_menu($fromUsername, $toUsername, "7");
                    break;
                case "8":
//         handleClick($fromUsername,"V2002");
                    request_menu($fromUsername, $toUsername, "8");
                    break;
                case "9":
//          handleClick($fromUsername,"V2003");
                    request_menu($fromUsername, $toUsername, "9");
                    break;
                case "V2005":
//          handleClick($fromUsername,"V2005");
                    request_menu($fromUsername, $toUsername, "礼品激活");
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
                    request_menu($fromUsername, $toUsername, "15");
                    break;
                case "16":
//          handleClick($fromUsername,"V3004");
                    request_menu($fromUsername, $toUsername, "16");
                    break;
                case "18":
                    request_menu($fromUsername, $toUsername, "18");
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

//客人发送图片的识别
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

//统计客人的点击情况
function handleClick($fromUsername, $click)
{
    include "mysql.php";
    mysql_query("INSERT INTO WX_Click_Hits (WX_OpenID,Click) VALUES ('" . $fromUsername . "','" . $click . "')") or die(mysql_error());
    mysql_close($link);
}

/*
* 获取客人信息，并插入wx_user_info表中；
 * @param  string  $type            参数subscribe,新关注；参数SCAN，重复关注；
 * scandate为扫最后二维码的日期，方便景区推送时间表
*/
function insert_user_info($fromUsername, $eventkey, $type)
{
    include "mysql.php";
    //获取客人的微信信息
    $ACCESS_TOKEN = get_access_token();
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $ACCESS_TOKEN . "&openid=" . $fromUsername;
    $json = http_request_json($url);//这个地方不能用file_get_contents
    $data = json_decode($json, true);
//  $nickname=$data['nickname'];
    $sex = $data['sex'];
    $city = $data['city'];
    $province = $data['province'];
    $country = $data['country'];
    $subscribe_time = $data['subscribe_time'];
    if (substr($eventkey, 0, 7) == 'qrscene') {
        $eventkey = substr($eventkey, 8);
    }

    //////////////////////////////////

    if ($type == "subscribe")//新关注
    {
        $row = mysql_fetch_array(mysql_query("select count(*) from WX_User_Info where wx_openid='" . $fromUsername . "'"));

        mysql_query("INSERT INTO WX_User_Add (wx_openid,eventkey) VALUES ('" . $fromUsername . "','" . $eventkey . "')") or die(mysql_error());

        if ($row[0] == "0") {
            mysql_query("INSERT INTO WX_User_Info (wx_openid,sex,city,province,country,subscribe_time,eventkey,subscribe,scandate) VALUES ('" . $fromUsername . "','" . $sex . "','" . $city . "','" . $province . "','" . $country . "','" . $subscribe_time . "','" . $eventkey . "','1','" . date("Y-m-d") . "')") or die(mysql_error());

            //mysql_close($link);
        } else {
            mysql_query("Update WX_User_Info set eventkey='" . $eventkey . "',subscribe='1' where wx_openid='" . $fromUsername . "'") or die(mysql_error());
            //mysql_close($link);
        }
    } elseif ($type == "SCAN")//如果是重复关注的；
    {
        $eventkey = $eventkey;
        $row = mysql_fetch_array(mysql_query("select count(*) from WX_User_Info where wx_openid='" . $fromUsername . "'"));
        if ($row[0] == "0") {

            mysql_query("INSERT INTO WX_User_Info (wx_openid,sex,city,province,country,subscribe_time,eventkey,subscribe,scandate) VALUES ('" . $fromUsername . "','" . $sex . "','" . $city . "','" . $province . "','" . $country . "','" . $subscribe_time . "','" . $eventkey . "','1','" . date("Y-m-d") . "')") or die(mysql_error());
            //mysql_close($link);
        } else {
            mysql_query("Update WX_User_Info set eventkey='" . $eventkey . "',subscribe='1',scandate='" . date("Y-m-d") . "' where wx_openid='" . $fromUsername . "'") or die(mysql_error());
            //mysql_close($link);
        }

    }
}

/*
* 获取取消关注的客人信息
*/
function insert_unsubscribe_info($fromUsername)
{
    include "mysql.php";

    //获取该微信号的关注时间

    $row = mysql_fetch_array(mysql_query("select * from WX_User_Info where WX_OpenID='" . $fromUsername . "' order by id desc  LIMIT 0,1"));
    $Adddate = $row['AddDate'];

    @$Eventkey = $row['eventkey'];

    mysql_query("delete from WX_User_Info where wx_openid='" . $fromUsername . "'") or die(mysql_error());

    mysql_query("INSERT INTO WX_User_Esc (wx_openid,EventKey,AddDate) VALUES ('" . $fromUsername . "','" . $Eventkey . "','" . $Adddate . "')") or die(mysql_error());

    mysql_close($link);

}


?>