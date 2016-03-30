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
        $row = $db->query("Select * from wx_location_list");
        foreach ($row as $result) {
            $aaa = explode(',', $result["show_time"]);
            $prevtime = "";
            foreach ($aaa as $bbb) {
//        if (strtotime($bbb)-(strtotime("now"))/60)
                $temptime = (strtotime($bbb) - strtotime("now")) / 60;
                if ($temptime < 30 && $temptime>0) {
                    $row1 = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days and UNIX_TIMESTAMP(endtime)>=:endtime order by id desc",
                        array("eventkey" => $result['eventkey'], "days" => date('Y-m-d'),"endtime"=>strtotime($prevtime)));
                    foreach ($row1 as $ccc) {
                        $response->responseV_Text($ccc["wx_openID"], "您好，" .$result["zone_id"]."景区". $result["show_name"]."的演出时间是".$bbb."。还没到剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='".$result["location_url"]."'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                        $response->responseV_News($ccc['wx_openID'], $result["show_name"], "2");
                    }


                    /*检查景区eventkey下有没有其他二维码，例：龙帝惊临项目在秦王宫里，因此龙帝惊临和秦王宫的二维码是从属关系，扫龙帝惊临的二维码也能收到秦王宫的节目提醒*/
                    $qrscene_id=$this->get_eventkey_info($result['eventkey']);
                    if($qrscene_id)
                    {
                        foreach($qrscene_id as $key=>$eventkey)
                        {
                            $row2 = $db->query("SELECT * from wx_user_info where eventkey=:eventkey  and scandate = :days and UNIX_TIMESTAMP(endtime)>=:endtime order by id desc",
                                array("eventkey" => $eventkey, "days" => date('Y-m-d'),"endtime"=>strtotime($prevtime)));
                            foreach ($row2 as $ddd) {
                                $response->responseV_Text($ddd["wx_openID"], "您好，" .$result["zone_id"]."景区". $result["show_name"]."的演出时间是".$bbb."。还没到剧场的话要抓紧了哦。\n如果您不知道剧场位置，<a href='".$result["location_url"]."'>点我</a>\n微信演出时间有时无法及时更新，以景区公示为准。");
                                $response->responseV_News($ddd['wx_openID'], $result["show_name"], "2");
                            }
                        }
                    }

                }
                $prevtime=$bbb;
            }
        }
    }

    private function get_eventkey_info($parentid)
    {
        $a=array();
        $db = new DB();
        $row = $db->query("select Qrscene_id from wx_qrscene_info where parent_ID=:parent_ID", array("parent_ID" => $parentid));
        if ($row) {
            foreach ($row as $result) {
                $a[] = $result["Qrscene_id"];
            }
            return $a;
        }
        else{
            return false;
        }
    }

} 