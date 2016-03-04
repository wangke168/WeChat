<?php


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
            $endtime=date("Y-m-d H:i:s");
            mysql_query("Update WX_User_Info set eventkey='" . $eventkey . "',subscribe='1',scandate='" . date("Y-m-d") . "',endtime='".$endtime."' where wx_openid='" . $fromUsername . "'") or die(mysql_error());
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