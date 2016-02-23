

<?php
//活动函数专区

/*
 * 2015年vip年会喊红包活动
 *参加活动两个限制。1、扫描会场二维码 2、语音中含有关键字
 * 利用$eventkey = return_eventkey_info(return_user_info($fromUsername,"eventkey"))获取用户的eventkey，年会的eventkey为1142
 */


/*
 * 文字抢红包

 * @param    string       $keyword     输入字符
 * @param    string       $text        客人输入关键字
 * @return   string       $flag        输出结果
*/
function Text_Wallet($Parentid,$fromUsername,$msgId)
{
    $eventkey = return_eventkey_info(return_user_info($fromUsername,"eventkey"));
    if ($eventkey=="1142")
    {
        $msgId = (string)$msgId;
        $mem = new Memcache;
        $mem->connect("127.0.0.1", 11211);
        $access_msgId = $mem->get("access_msgId_text1");
        if ($msgId != $access_msgId) {
            $mem->set("access_msgId_text1", $msgId, 0, 20);

            Take_Money($Parentid, $fromUsername, "6", "10");
        }
    }
    else
    {
        $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
        responseV_Text($fromUsername,$contentStr);
    }
}


/*
 * 拿红包活动

 * @param    string       $keyword     输入字符
 * @param    string       $text        客人输入关键字
 * @return   string       $flag        输出结果
*/
function Take_Money($Parentid,$fromUsername,$winid,$repeat)
{
    include ("mysql.php");
    if  (Check_Activity_Number($Parentid)==False)
    {
        $str="您好，感谢您参加本次活动，本次活动的奖已经全部中出。";
 //       responseV_Text($fromUsername,$str);
    }
    else
    {
        $result=mysql_query("SELECT count(*) from WX_Activity where Parentid='".$Parentid."' and wx_openID = '".$fromUsername."' and to_days(AddDate) = to_days(now()) ",$link);
        $row=mysql_fetch_array($result);
        $todaycount=$row['count(*)'];
        if ($todaycount<$repeat)  //如果当天的记录数少于可重复抽奖次数
        {

            mysql_query("INSERT INTO WX_Activity (wx_OpenID,Parentid) VALUES ('".$fromUsername."','".$Parentid."')") or die(mysql_error());
            //查找对应ID，根据规则查出是否中奖
            $result=mysql_query("SELECT * from WX_Activity where Parentid='".$Parentid."' and wx_openID = '".$fromUsername."' order by id desc LIMIT 0,1",$link);
            $row=mysql_fetch_array($result);
            $activeid = $row['ID'];

            if ($activeid%$winid=="0")
            {

                //在数据库中找到中奖优惠码
                $result=mysql_query("SELECT  * from WX_Activity_number where ParentID='".$Parentid."' and send = '0' and to_days(SendDate) = to_days(now()) order by rand() LIMIT 0,1",$link);
                $row=mysql_fetch_array($result);
                $Serial=$row['Serial'];
                $Serialid=$row['ID'];
                $classid=$row['classid'];
                //往兑奖券表中做标记
                mysql_query("update WX_Activity_number set send='1' where id='".$Serialid."'") or die(mysql_error());
                mysql_query("update WX_Activity set Serial_number='".$Serial."',win_if='1',Classid='".$classid."' where id='".$activeid."'" ) or die(mysql_error());
                //  $str="感谢你参加本次活动，\n恭喜您中奖\n您的兑奖号为".$Serial."，请拨打400-1-9999141转1兑奖。";
             //   $str="恭喜您，您中了".Query_ActivierName($classid);
         //       $str=Query_ActivierName($classid);

                $amt=Query_ActivierName($classid);




                $json=file_get_contents("http://test.hengdianworld.com/WeixinRedPacket.aspx?hdkey=hdwrold9982779WWWdyhzzjie&openid=o2e-YuEDmWsGD51eu8ccUb9mZf1g&redtype=0&amt=88&num=1");
                $data = json_decode($json,true);

                $str = "祝您猴年有财，财源滚滚";

 /*
                $hdkey = "hdwrold9982779WWWdyhzzjie";
                $openid = "o2e-YuBgnbLLgJGMQykhSg_V3VRI";
                $redtype = "redtype";
                $amt = "2";
                $num = "1";
                $curlPost = 'hdkey='.$hdkey .'&openid=' .$openid. '&redtype='.$redtype.'&amt='.$amt.'&num='.$num;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://test.hengdianworld.com/WeixinRedPacket.aspx');
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
                $data = curl_exec();
                curl_close($ch);
                */


//                responseV_Text($fromUsername,$str);
            }
            else
            {
                $str="感谢你参加本次活动，\n很遗憾，您未中奖。\n祝您猴年有财，财源滚滚。";
 //               responseV_Text($fromUsername,$str);

            }
        }
        else
        {
            $str="您已经参与过这次活动。";
//            responseV_Text($fromUsername,$str);
        }
    }
    responseV_Text($fromUsername,$str);
//  return $str;
//echo $classid;
}

/*
 * 台州市场文字回复活动
 * 客人回复“去横店穿越去横店飞+手机号”，留下客人信息，抽奖
 */
function text_active($eventkey,$keyworld)
{

}
?>
