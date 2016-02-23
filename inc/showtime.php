<?php
//include "../mysql.php";
include "response.php";
include "function.php";

@$showid=$_GET["showid"];


send_showtime($showid);

function send_showtime($showid)
{
    include "mysql.php";

    switch ($showid)
    {
        case "m1":
            $result=mysql_query("SELECT * from wx_user_info where eventkey='8888'  and (to_days(scandate) = to_days(now()))  order by id desc",$link);
            while($row = mysql_fetch_array($result))
            {
                responseV_Text($row['wx_openID'],"您好，明清宫苑“紫金大典”节目还有20分钟就要开始了，还没到剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='http://l.map.qq.com/11654882773?m'>点我</a>");

                //echo $row['wx_openID'];
            }
            break;
        case "m2":
            $result=mysql_query("SELECT * from wx_user_info where eventkey='8888'  and (to_days(scandate) = to_days(now()))  order by id desc",$link);
            while($row = mysql_fetch_array($result))
            {
                responseV_Text($row['wx_openID'],"您好，明清宫苑“八旗马战”节目还有20分钟就要开始了，还没到演武场的话要抓紧了哦。\n如果您不知道演武场位置，<a href='http://l.map.qq.com/11681206512?m'>点我</a>");

                //echo $row['wx_openID'];
            }
            break;
        case "m3":
            $result=mysql_query("SELECT * from wx_user_info where eventkey='8888'  and (to_days(scandate) = to_days(now()))  order by id desc",$link);
            while($row = mysql_fetch_array($result))
            {
                responseV_Text($row['wx_openID'],"您好，明清宫苑“清宫秘戏”节目还有20分钟就要开始了，还没到湖广会馆的话要抓紧了哦。\n如果您不知道湖广会馆位置，<a href='http://l.map.qq.com/11681208964?m'>点我</a>");

                //echo $row['wx_openID'];
            }
            break;
        case "m4":
            $result=mysql_query("SELECT * from wx_user_info where eventkey='8888'  and (to_days(scandate) = to_days(now()))  order by id desc",$link);
            while($row = mysql_fetch_array($result))
            {
                responseV_Text($row['wx_openID'],"您好，明清宫苑“康熙巡游”节目还有20分钟就要开始了，还没到东望楼剧场的话要抓紧了哦。>点我</a>");

                //echo $row['wx_openID'];
            }
            break;
        case "q1":
            $result=mysql_query("SELECT * from wx_user_info where eventkey='8888'  and (to_days(scandate) = to_days(now()))  order by id desc",$link);
            while($row = mysql_fetch_array($result))
            {
                responseV_Text($row['wx_openID'],"您好，秦王宫“英雄比剑”节目还有20分钟就要开始了，还没到东望楼剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='http://l.map.qq.com/11710487912?m'>点我</a>");

                //echo $row['wx_openID'];
            }
            break;
    }
}