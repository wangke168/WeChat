<?php

/*
 * 检查关键字中是否包含可回复字符
 * @param    string       $text        客人输入关键字
 * @return   string       $result      到数据库查（WX_Request_Keyword）询输出关键字
*/
function check_in($text)
{
    $db = new DB();
    $flag = "不包含";
    $row = $db->query("select keyword from WX_Request_Keyword order by id asc", PDO::FETCH_NUM);

    foreach ($row as $result) {
        if (strstr($text, $result['keyword']) != '') {
            $flag = $result['keyword'];
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
 *
 *
 * function CheckGroup($fromUsername)
 * {
 * include("mysql.php");
 * $result = mysql_query("select * from WX_User_Group where WX_OpenID='" . $fromUsername . "'") or die(mysql_error());
 * $row = mysql_fetch_array($result);
 * if (!$row) {
 * return "0";
 * } else {
 * return $row['Group'];
 * }
 * }
 */

/*
* 获取access_token
* 写到缓存
*/
function get_access_token()
{
    $mem = new Memcache;
    $mem->connect("127.0.0.1", 11211);
    @$mark_time = $mem->get("mark_time");
    $access_token = $mem->get("access_token");
    if (!$mark_time || (time() - $mark_time > 3600) || !$access_token) {

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81";
        $json = http_request_json($url);//这个地方不能用file_get_contents
        $data = json_decode($json, true);
        if ($data['access_token']) {
            $mem->set("access_token", $data['access_token'], 0, 7200);    //设置cache，为下一步提供依据
            $mem->set("mark_time", time(), 0, 7200);
            $access_token = $mem->get("access_token");
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
    $db = new DB();
    $row = $db->row("select * from WX_User_Info where wx_openid=:fromUsername order by id desc LIMIT 0,1", array("fromUsername" => $fromUsername));
    if($row)
    {
        return $row[$type];
    }
    else{
        return "";
    }
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
    $db = new DB();
    $row = $db->row("select * from WX_qrscene_Info where Qrscene_id=:Qrscene_id order by id desc LIMIT 0,1", array("Qrscene_id" => $eventkey));
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
        $db = new DB();
        $row = $db->row("SELECT * from wx_order_send  order by id desc LIMIT 0,1");
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
    $db = new DB();
    $row = $db->row("SELECT * from wx_qrscene_info where Qrscene_id=:Qrscene_id", array("Qrscene_id" => $eventkey));
    return $row['uid'];
}


/*
 * 加密解密
 *
 *
 */

function authcode($string, $operation = 'DECODE', $expiry = 0)
{
    if ($operation == 'DECODE') {
        $string = str_replace('[a]', '+', $string);
        $string = str_replace('[b]', '&', $string);
        $string = str_replace('[c]', '/', $string);
    }

    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;
    $key = "hdtravel";
    // 密匙
    $key = md5($key ? $key : 'hengdianworld');

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
        substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
//解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
        sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
        ) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        $ustr = $keyc . str_replace('=', '', base64_encode($result));
        $ustr = str_replace('+', '[a]', $ustr);
        $ustr = str_replace('&', '[b]', $ustr);
        $ustr = str_replace('/', '[c]', $ustr);
        return $ustr;


    }
}
