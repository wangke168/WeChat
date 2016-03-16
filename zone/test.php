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
 * SELECT * FROM  `wx_user_add` WHERE HOUR( adddate ) =  hour(now())  AND adddate ＝ DATE( NOW( ) )  //查询现在时间段内的数据
 *
 */

require_once("../classes/db.class.php");
require_once("../classes/tour.class.php");

$wx_openid=$_GET["wx_openid"];

function get_q()
{
    $db=new DB();
//    $row=$db->query("select count(*) as tempcount from wx_user_add where ");
}