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


}