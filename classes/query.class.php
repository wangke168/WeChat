<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/3/16
 * Time: 9:45
 *
 *
 * 各类查询函数
 */
class query
{


    /*
     * 查询景区节目预约情况
     *
     *
     */
    public function query_wite_info($fromUsername)
    {
        $responseMsg = new responseMsg();


        $db = new DB();
        $row = $db->query("select * from tour_project_wait_detail WHERE wx_openid=:wx_openid AND date(addtime)=:temptime",
            array("wx_openid" => $fromUsername, "temptime" => date("Y-m-d")));
        if (!$row) {
            $responseMsg->responseV_Text($fromUsername, "您好，您今天没有预约。");
        } else {
            foreach ($row as $result) {
                $project_id = $result["project_id"];
                $tour = new tour();
                $project_name = $tour->get_project_name($project_id);
                $zone_name = $tour->get_zone_name($project_id, "2");
                $datetime = date($result["addtime"]);
                $starttime = date("H:i", strtotime($result["addtime"]) + 3600);
                $endtime = date("H:i", strtotime($result["addtime"]) + 7200);
                if ($result["used"] == 0) {
                    $used = "未使用";
                } else {
                    $used = "已使用";
                }
                $str = "您预约了" . $datetime . $zone_name . "景区" . $project_name . "项目;\n预约时间：" . $starttime . "---" . $endtime . "\n状态：" . $used;
                $responseMsg->responseV_Text($fromUsername, $str);
            }
        }

    }
} 