<?php


/*
* 获取客人信息，并插入wx_user_info表中；
 * @param  string  $type            参数subscribe,新关注；参数SCAN，重复关注；
 * scandate为扫最后二维码的日期，方便景区推送时间表
*/
function insert_user_info($fromUsername, $eventkey, $type)
{
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

    $db = new DB();
    if ($type == "subscribe")//新关注
    {
        $row = $db->query("SELECT * from WX_User_Info  where wx_openid=:wx_openid", array("wx_openid" => $fromUsername));

        $db->query("INSERT INTO WX_User_Add (wx_openid,eventkey) VALUES (:wx_openid,:eventkey)", array("wx_openid" => $fromUsername, "eventkey" => $eventkey));


        if (!$row) {

            $db->query("INSERT INTO WX_User_Info (wx_openid,sex,city,province,country,subscribe_time,eventkey,subscribe,scandate)
                          VALUES(:wx_openid,:sex,:city,:province,:country,:subscribe_time,:eventkey,:subscribe,:scandate)",
                array("wx_openid" => $fromUsername, "sex" => $sex, "city" => $city, "province" => $province,
                    "country" => $country, "subscribe_time" => $subscribe_time, "eventkey" => $eventkey, "subscribe" => "1",
                    "scandate" => date("Y-m-d")));

        } else {
            $db->query("Update WX_User_Info set eventkey=:eventkey,subscribe=:subscribe where wx_openid=:wx_openid",
                array("eventkey" => $eventkey, "subscribe" => "1", "wx_openid" => $fromUsername));

        }
    } elseif ($type == "SCAN")//如果是重复关注的；
    {

        $row = $db->query("SELECT count(*) from WX_User_Info  where wx_openid=:wx_openid", array("wx_openid" => $fromUsername));

        if (!$row) {

            $db->query("INSERT INTO WX_User_Info (wx_openid,sex,city,province,country,subscribe_time,eventkey,subscribe,scandate)
                          VALUES(:wx_openid,:sex,:city,:province,:country,:subscribe_time,:eventkey,:subscribe,:scandate)",
                array("wx_openid" => $fromUsername, "sex" => $sex, "city" => $city, "province" => $province,
                    "country" => $country, "subscribe_time" => $subscribe_time, "eventkey" => $eventkey, "subscribe" => "1",
                    "scandate" => date("Y-m-d")));

        } else {
            $endtime = date("Y-m-d H:i:s");

            $db->query("Update WX_User_Info set eventkey=:eventkey,subscribe=:subscribe,scandate=:scandate,endtime=:endtime where wx_openid=:wx_openid",
                array("eventkey" => $eventkey, "subscribe" => "1", "scandate" => date("Y-m-d"), "endtime" => $endtime, "wx_openid" => $fromUsername));

        }

    }
}

/*
* 获取取消关注的客人信息
*/
function insert_unsubscribe_info($fromUsername)
{

    $db = new DB();
    //获取该微信号的关注时间

    $row = $db->query("select * from WX_User_Info where WX_OpenID=:WX_OpenID order by id desc  LIMIT 0,1", array("WX_OpenID" => $fromUsername));

    $Adddate = $row[0]['AddDate'];

    @$Eventkey = $row[0]['eventkey'];

    $db->query("delete from WX_User_Info where wx_openid=:wx_openid", array("wx_openid" => $fromUsername));

    $db->query("INSERT INTO WX_User_Esc (wx_openid,EventKey,AddDate) VALUES(:wx_openid,:EventKey,:AddDate)", array("wx_openid" => $fromUsername, "EventKey" => $Eventkey, "AddDate" => $Adddate));
}
