<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/3/2
 * Time: 8:56
 */
class smart
{

    /*
     * 自动推送演艺秀信息
     * m1：紫金大典  M2：八旗马战  m3：清宫秘戏  m4：康熙巡游
     *
     *
     */

    public function send_showtime()
    {
        $db = new DB();
        $response = new responseMsg();
        $row = $db->query("Select * from wx_location_list where id <5");
        foreach ($row as $result) {
            $aaa = explode(',', $result["show_time"]);
            $prevtime = "";
            foreach ($aaa as $bbb) {
//        if (strtotime($bbb)-(strtotime("now"))/60)
                $temptime = (strtotime($bbb) - strtotime("now")) / 60;
                if ($temptime < 30 && $temptime>0) {
                    $row1 = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days and UNIX_TIMESTAMP(endtime)>=:endtime order by id desc", array("eventkey" => $result['eventkey'], "days" => date('Y-m-d'),"endtime"=>strtotime($prevtime)));
                    foreach ($row1 as $ccc) {
                        $response->responseV_Text($ccc["wx_openID"], "您好，" .$result["zone_id"]."景区". $result["show_name"]."的演出时间是".$bbb."。还没到剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='".$result["location_url"]."'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                        $response->responseV_News($ccc['wx_openID'], $result["show_name"], "2");
                    }
                }
                $prevtime=$bbb;
//                    echo    $bbb;
//        echo strtotime($bbb)."...".strtotime("now")."<br>";
//        echo $bbb."<br>";
            }

//    echo $result["show_time"];
        }
    }

    public function send_showtime1($showid)
    {
        $db = new DB();
        $response = new responseMsg();
        switch ($showid) {
            case "m1":
                $row = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days   order by id desc", array("eventkey" => "8888", "days" => date('Y-m-d')));
                foreach ($row as $result) {
                    $response->responseV_Text($result['wx_openID'], "您好，明清宫苑“紫禁大典”节目还有20分钟就要开始了，还没到剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='http://l.map.qq.com/11654882773?m'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                    $response->responseV_News($result['wx_openID'], "紫禁大典", "2");
                }
                break;
            case "m2":
                $row = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days   order by id desc", array("eventkey" => "8888", "days" => date('Y-m-d')));
                foreach ($row as $result) {
                    $response->responseV_Text($result['wx_openID'], "您好，明清宫苑“八旗马战”节目还有20分钟就要开始了，还没到演武场的话要抓紧了哦。\n如果您不知道演武场位置，<a href='http://l.map.qq.com/11681206512?m'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                    $response->responseV_News($result['wx_openID'], "八旗马战", "2");
                }
                break;
            case "m3":
                $row = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days   order by id desc", array("eventkey" => "8888", "days" => date('Y-m-d')));
                foreach ($row as $result) {
                    $response->responseV_Text($result['wx_openID'], "您好，明清宫苑“清宫秘戏”节目还有20分钟就要开始了，还没到湖广会馆的话要抓紧了哦。\n如果您不知道湖广会馆位置，<a href='http://l.map.qq.com/11681208964?m'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                    $response->responseV_News($result['wx_openID'], "清宫秘戏", "2");
                }
                break;
            case "m4":
                $row = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days   order by id desc", array("eventkey" => "8888", "days" => date('Y-m-d')));
                foreach ($row as $result) {
                    $response->responseV_Text($result['wx_openID'], "您好，明清宫苑“康熙巡游”节目还有20分钟就要开始了，还没到指定地点的话要抓紧了哦。\n微信演出时间有时无法及时更新，以景区公示为准。");
                }
                break;
        }
    }
} 