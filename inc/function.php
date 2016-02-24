<?php
include("mysql.php");
//include("Activity.php");
//include("bind.php");


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


/*
 *根据图片是上传还是网络图片，输出正确图片路径
 * @param    string       $picurl      读取图片地址
 * @return   string               输出结果
 */
function check_picurl($picurl)
{
    if (strstr($picurl, 'http') != '') {
        return $picurl;
    } else {
        return "http://weix.hengdianworld.com" . $picurl;
    }
}

/*
 * 检查关键字是不是电话号码

 * @param    string       $keyword     输入字符
 * @param    string       $text        客人输入关键字
 * @return   string       $flag        输出结果
*/
function check_word($keyword)
{
    include "mysql.php";
    //  $abc='12345790';//调试语句
    //  if ($abc == '') {$abc='13829840';}
    $flag = 0;
    if (preg_match('|^\d{8}$|', $keyword)) {
        $flag = 1;
    } elseif (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $keyword)) {
        $flag = 2;
    }
    return $flag;
}


/*******
 * @param $eventkey
 * @return $flag
 * 确认订单的是否被固定数整除
 */
function check_order_number($eventkey)
{
    if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
        include "mysql.php";
        $result = mysql_query("SELECT * from wx_order_send  order by id desc LIMIT 0,1", $link);
        $row = mysql_fetch_array($result);
        @$orderid = $row['ID'];

        if ($orderid % 1 == "0") {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }

}

/***************************************
 *
 * 查询二维码的uid
 ******************************************/
function query_qr_uid($eventkey)
{
    include "mysql.php";
    $row = mysql_fetch_array(mysql_query("SELECT * from wx_qrscene_info where Qrscene_id='" . $eventkey . "'", $link));
    return $row['uid'];
}