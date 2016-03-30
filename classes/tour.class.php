<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/3/15
 * Time: 13:21
 *
 *
 * 和景区有关的类
 */
class tour
{

    /*
     *
     * 获取景区名称
     * int $classid  ID
     * int $type     type=1:景区id  type=2:演艺秀id
     * return 景区名称
     *
     */
    public function get_zone_name($classid, $type)
    {
        $db = new DB();
        switch ($type) {
            case "1":
                $row = $db->query("select zone_name from tour_zone_class where id=:id", array("id" => $classid));
                if ($row) {
                    return $row[0]["zone_name"];
                } else {
                    return "该景区不存在";
                }
                break;
            case "2":
                $row = $db->query("select zone_name from tour_zone_class where id=(select zone_classid from tour_project_class where id=:id)", array("id" => $classid));
                if ($row) {
                    return $row[0]["zone_name"];
                } else {
                    return "该景区不存在";
                }
                break;
            default:
                return "错误类型";
                break;
        }
    }

    /*
     *
     * 获取演艺秀名称
     *
     */
    public function get_project_name($classid)
    {
        $db = new DB();
        $row = $db->query("select project_name from tour_project_class where id=:id", array("id" => $classid));
        if ($row) {
            return $row[0]["project_name"];
        } else {
            return "该演艺秀不存在";
        }
    }




  /*
   * 检查当天该微信号是否已经取号
   *
   * int $type  1.检查当天是否取号； 2.检查该小时是否取号
   *
   * return flag 如果用户没有取号，则返回false,如果已经取号，则返回true
   *
   */

    public function check_wxid($fromUsername, $type)
    {
        $db = new DB();
        switch ($type) {
            case "1":
                $row = $db->query("select count(*) as tempcount from tour_project_wait_detail where wx_openid=:wx_openid and   date(addtime)=:addtime",
                    array("wx_openid" => $fromUsername, "addtime" => date('Y-m-d')));
                break;
            case "2":
                $row = $db->query("select count(*) as tempcount from tour_project_wait_detail where wx_openid=:wx_openid AND  date(addtime)=:addtime and hour(addtime)=:hourtime",
                    array("wx_openid" => $fromUsername, "addtime" => date('Y-m-d'), "hourtime" => date('G')));
                break;
            default:
                $row = "该类型不存在";
                break;
        }

        if ($row[0]["tempcount"] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * 检查该小时取号数是否已满
     *
     * int $project_id  取号项目
     *
     * int $type 1.查看每天号是否取满； 2.查看每小时号是否取满
     *
     * return flag 如果取号已满，则返回true,如果取号未满，则返回false
     *
     */
    public function check_amount($project_id, $type)
    {
        $db = new DB();

        switch ($type) {
            case "1";
                $row = $db->query("SELECT count(*)  as tempcount from tour_project_wait_detail where date(addtime)=:addtime",
                    array("addtime" => date('Y-m-d')));
                break;
            case "2":
                $row = $db->query("SELECT count(*)  as tempcount from tour_project_wait_detail where date(addtime)=:addtime and hour(addtime)=:hourtime",
                    array("addtime" => date('Y-m-d'), "hourtime" => date('G')));
                break;
            default:
                echo "错误类型";
                break;
        }

        $project_amount = $this->get_wait_info($project_id, $type);

        if ($row[0]["tempcount"] >= $project_amount) {
            return true;    //如果该小时取号数大于设定值，则返回true
        } else {
            return false;
        }
    }


    /*
     * 获取相关项目的排队数据
     * int $project_id  项目id
     * int $type        1.查看每天可取号人数； 2.查看每小时可取号人数；3.取号类型（按天还是按小时）
     *
     * return int  返回对应的数字
     */
    public  function get_wait_info($project_id, $type)
    {
        $db = new DB();
        $row = $db->query("select * from tour_project_wait where project_id=:project_id", array("project_id" => $project_id));
        switch ($type) {
            case "1":
                return $row[0]["wait_all_amount"];
                break;
            case "2":
                return $row[0]["wait_amount"];
                break;
            case "3":
                return $row[0]["wait_type"];
                break;
            default:
                return "无此类型数据";
                break;
        }
    }

    /*
     *获取该项目的地理位置
     *
     * int $project_id  项目id
     *
     * return string    项目在腾讯地图的位置
     */
    public function get_project_location($project_id)
    {
        $db = new DB();
        $row = $db->query("select project_location from tour_project_location where project_id=:project_id", array("project_id" => $project_id));
        if ($row) {
            return $row[0]["project_location"];
        } else {
            return "没有该项目id";
        }
    }


    /*
     *排队号码核销
     *
     *
     */

    public function  track_info($fromUsername,$project_id)
    {
        $db = new DB();

        $row = $db->query("select * from tour_project_wait_detail WHERE wx_openid=:wx_openid AND project_id=:project_id ORDER BY id desc limit 0,1", array("wx_openid" => $fromUsername, "project_id" => $project_id));

        if (!$row) {
            return "您的号码有误，请联系工作人员。";
        } elseif ($row[0]["used"] == "1") {
            return "不能重复游玩。";
        } else {
            /*查询是否符合核销条件（当天，一小时前）*/
            $row1 = $db->query("select * from tour_project_wait_detail WHERE wx_openid=:wx_openid AND project_id=:project_id AND  used=:used AND date(addtime)=:tempdate  AND UNIX_TIMESTAMP(addtime)<=:endtime",
                array("wx_openid" => $fromUsername, "project_id" => $project_id, "used" => "0","tempdate"=>date('Y-m-d'),"endtime" => strtotime(date("Y-m-d H:i", time() - 3600))));
            if (!$row1) {
                return "您好，现在未到您的预约时间";
            } else {
                $row2 = $db->query("update tour_project_wait_detail set used=:used WHERE wx_openid=:wx_openid AND project_id=:project_id AND date(addtime)=:tempdate  and  UNIX_TIMESTAMP(addtime)<=:endtime",
                    array("used" => "1", "wx_openid" => $fromUsername, "project_id" => $project_id,"tempdate"=>date('Y-m-d'),"endtime" => strtotime(date("Y-m-d H:i", time() - 3600))));
                if ($row2 > 0) {
                    return "您好，您现在可以入场。";
                }
                else
                {
                    return "核销有误，请联系工作人员。";
                }
            }
        }
    }

}