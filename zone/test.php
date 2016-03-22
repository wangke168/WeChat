<?php
/**
 * Created by PhpStorm.
 * User: wangke
 * Date: 16/3/16
 * Time: 下午1:36
 *
 * 排队编号格式：小时＋号码；例如下午1点－2点时间段取的号为13-1，13-2，13-3等
 *
 * 1.获取编号时，确认当天该微信号没有取号
 *              先在数据库搜索本时间段的号码总数，如果大于wait_amount参数，则无法取号，如果不到的，则从上面一个号开始顺延，以此类推
 *
 *
 * 2.回推信息时，有预约的时间区域，龙帝惊临是取号后一个小时之内，以小时为单位。例如，客人是13点14分取号，则游玩时间是14点14分－－15点14分
 * 3.核销：回推信息是图文，点进去可以按钮核销，类似摇一摇确认领奖
 *
 * SELECT * FROM  `tour_project_wait_detail` WHERE HOUR( adddate ) =  hour(now())  AND adddate ＝ DATE( NOW( ) )  //查询现在时间段内的数据
 *
 */

require_once("../classes/db.class.php");
require_once("../classes/tour.class.php");
require_once("../classes/response.class.php");
require_once("../inc/function.php");


$fromUsername = $_GET["fn"];
$fromUsername=authcode($fromUsername,'DECODE',0);
$project_id = "1";  //龙帝惊临
//$type = "1";        //按天计算
//$wx_openid=$_GET["wx_openid"];
$db = new DB();
$tour=new tour();
$type = $tour->get_wait_info($project_id, "3");


/*先判断当天是否已经约满，然后判断该微信号是否已经有约*/

if ($tour->check_amount($project_id, $type))     //确定当天或当小时预约是否已满
{
    if ($type == 1) {
        $str = "<font color='red'>今天预约已满</font>";
    } elseif ($type == 2) {
        $str = "<font color='red'>该小时预约已满</font>";
    }
} else {
    if ($tour->check_wxid($fromUsername, "1"))     //确定该微信号是否当下已经预约
    {
        $str = "<font color='red'>不能重复取号</font>";
    } else {
        $str = insert_wait_info($fromUsername, $project_id);
        $project_loction = $tour->get_project_location($project_id);
//        $project = new tour();
        $project_name = $tour->get_project_name($project_id);
        $zone_name=$tour->get_zone_name($project_id,"2");
        $str1 = "您已经成功预约".$zone_name."景区" . $project_name . "项目，\n".$str . "。\n如果您不清楚具体演出地点，<a href='" . $project_loction . "'>点我</a>";

        $str="<font color='green'>预约成功<br>".$str."</font>";
        $response = new responseMsg();
        $response->responseV_Text($fromUsername, $str1);
    }
}

echo $str;

function insert_wait_info($fromUsername, $project_id)
{
    $db = new DB();
    $row = $db->query("select * from tour_project_wait_detail WHERE project_id=:project_id AND date(addtime)=:addtime ORDER BY id DESC  limit 0,1", array("project_id" => $project_id, "addtime" => date('Y-m-d')));
    if (!$row) {
        $user_id = "1";
    } else {
        $user_id = $row[0]["user_id"] + 1;
    }

//    $addtime = date('Y-m-d H:i:s');

//    echo $addtime;
    $db->query("insert into tour_project_wait_detail (user_id,project_id, wx_openid) VALUES (:user_id,:project_id,:wx_openid)",
        array("user_id" => $user_id, "project_id" => $project_id, "wx_openid" => $fromUsername));



    return "您的游玩时间段为" . date("Y-m-d H:i", time() + 3600) . "---" . date("H:i", time() + 7200);
//    return "您已经成功预约".$zone_name."景区" . $project_name . "项目，您的游玩时间段为" . date("Y-m-d H:i", time() + 3600) . "---" . date("H:i", time() + 7200);
}



