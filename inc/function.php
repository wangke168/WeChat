<?php
include("mysql.php");
//include("Activity.php");
//include("bind.php");


/***************************************
 *
 * 查天气
 * 来源：百度车联网
 * 地址：http://developer.baidu.com/map/carapi-7.htm
 ******************************************/
function weather($fromUsername)
{
    $json = file_get_contents("http://api.map.baidu.com/telematics/v3/weather?location=%E4%B8%9C%E9%98%B3&output=json&ak=2c87d6d0443ab161753291258ac8ab7a");
    $data = json_decode($json, true);
    $contentStr = "【横店天气预报】：\n\n";
    $contentStr = $contentStr . $data['results'][0]['weather_data'][0]['date'] . "\n";
    $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][0]['weather'] . "\n";
    $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][0]['temperature'] . "\n\n";
    $contentStr = $contentStr . "明天：" . $data['results'][0]['weather_data'][1]['date'] . "\n";
    $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][1]['weather'] . "\n";
    $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][1]['temperature'] . "\n\n";
    $contentStr = $contentStr . "后天：" . $data['results'][0]['weather_data'][2]['date'] . "\n";
    $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][2]['weather'] . "\n";
    $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][2]['temperature'] . "\n";

    responseV_Text($fromUsername, $contentStr);
}


/*
 * 检查关键字中是否包含可回复字符
 * @param    string       $text        客人输入关键字
 * @return   string       $result      到数据库查（WX_Request_Keyword）询输出关键字
*/
function check_in($text)
{
    include("mysql.php");
    $flag = "不包含";
    $result = mysql_query("select * from WX_Request_Keyword order by id asc");
    while ($row = mysql_fetch_array($result)) {

        //      	$arr="报名,探班,交通,公交,出租,门票,套餐,剧组,预订,节目,测试,景区,景点,玩,秦王宫,明清宫苑,清明上河图,广州街香港街,梦幻谷,电影";
        //		$keys = explode(',',$arr);
        //		$result = '不包含';
        //		if($keys){
        //			foreach($keys as $key){
        if (strstr($text, $row['Keyword']) != '') {
            $flag = $row['Keyword'];
            //              $flag = "bbb";
            break;
        }
    }

    return $flag;
}

/**
 * 查询客人微信号的分组
 * @access  public
 * @param   string $fromUsername 客人微信号
 * @return  int          $str                  群组
 */

function CheckGroup($fromUsername)
{
    include("mysql.php");
    $result = mysql_query("select * from WX_User_Group where WX_OpenID='" . $fromUsername . "'") or die(mysql_error());
    $row = mysql_fetch_array($result);
    if (!$row) {
        return "0";
    } else {
        return $row['Group'];
    }
}


/*
* 获取access_token
* 写到缓存
*/
function get_access_token()
{
//    require_once ('Memcache.php');
//    $mem = new hdMemcache();

    $mem = new Memcache;
    $mem->connect("127.0.0.1", 11211);
    @$mark_time = $mem->get("mark_time");
    $access_token = $mem->get("access_token1");
    if (!$mark_time || (time() - $mark_time > 3600) || !$access_token) {

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81";
        $json = http_request_json($url);//这个地方不能用file_get_contents
        $data = json_decode($json, true);
        if ($data['access_token']) {
            //将access_token写入缓存
            //            require_once 'BaeMemcache.class.php';
            //         	$mem = new BaeMemcache();
            $mem->set("access_token1", $data['access_token'], 0, 7200);    //设置cache，为下一步提供依据
            $mem->set("mark_time", time(), 0, 7200);
            $access_token = $mem->get("access_token1");
            return $access_token;
        } else {
            return "获取access_token错误";
        }
    } else {
        return $access_token;
    }
}

//因为url是https 所有请求不能用file_get_contents,用curl请求json 数据
function http_request_json($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/*
* 提交post
*/
function vpost($url, $data)
{ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在

//    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包x
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
//    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}


/*
 * 获得客人信息
 * @param  string  $fromUsername    用户微信号
 * @param  string  $type            参数
 * @return string  $str             对应信息
*/
function return_user_info($fromUsername, $type)
{
    include "mysql.php";
    $result = mysql_query("select * from WX_User_Info where wx_openid='" . $fromUsername . "' order by id desc LIMIT 0,1");
    $row = mysql_fetch_array($result);
    return $row[$type];
    //    responseV_Text($fromUsername,$row[$type]);
    //  responseV_Text($fromUsername,"fdsgdfs");
}


/*
 * 获得eventkey相关信息
 * @param  string  $eventkey    用户的eventkey
 * @return string  $str             对应信息
*/
/**
 * @param $eventkey
 * @return mixed
 */
function return_eventkey_info($eventkey)
{
    include "mysql.php";
    $result = mysql_query("select * from WX_qrscene_Info where Qrscene_id='" . $eventkey . "' order by id desc LIMIT 0,1");
    $row = mysql_fetch_array($result);
    if (!$row['Root_ID']) {
        return $eventkey;
    } else {
        return $row['Root_ID'];
    }
}

/**
 * 查询活动信息
 * @access  public
 * @param   string $classid 活动编号
 * @param   string $type 信息类型
 * @return  string       $str             活动具体信息
 */
function Activity_Name($classid, $type)
{
    include "mysql.php";
    $row = mysql_fetch_array(mysql_query("select * from WX_Activity_List where id='" . $classid . "'"));
    //	return $row[{$type}];

}


/**
 * 返回探班游报名情况
 * @access  public
 * @param   string $classid 活动编号
 * @return  string       $str             报名情况
 */
function Response_Sign($fromUsername, $toUsername, $classid)
{
    /*
	$ContentStr=
	$result = mysql_query("select * from WX_Activity_tb where id='".$classid."' order by id asc");
	while($row = mysql_fetch_array($result))
	{
//		$ContentStr=
	 }
  
  if ($hotelcount<>0)
    {
    	for ($j=0; $j<$hotelcount; $j++)
        {
          $i=$i+1;
          $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['hotelorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['hotelorder'][$j]['date2'];
          $str=$str."\n预订酒店:".$data['hotelorder'][$j]['hotel'];
          $str=$str."\n数量:".$data['hotelorder'][$j]['numbers'];
          $str=$str."\n天数:".$data['hotelorder'][$j]['days'];
          $str=$str."\n订单状态:".$data['hotelorder'][$j]['flag']."\n";
        }
    }
    */
}
