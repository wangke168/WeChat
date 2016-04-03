<?php
require dirname(__FILE__) . '/../inc/function.php';
@$qr_id = $_GET['Qrscene_id'];

$xjson = "{
    \"action_name\": \"QR_LIMIT_SCENE\", 
    \"action_info\": {
        \"scene\": {
            \"scene_id\": " . $qr_id . "
        }
    }
}
 }";
//https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81

$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . get_access_token();
printf($url);

$result = vpost1($url, $xjson);
//echo "QR101".$i."<br>";
//var_dump($result);
$jsoninfo = json_decode($result, true);

//printf($jsoninfo);
$ticket = $jsoninfo['ticket'];
//echo $ticket."<br><br><br>";
echo "<img src='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $ticket . "'>";


function vpost1($url, $data)
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
* 获取access_token
* 写到缓存
*/
/*
 function get_access_token(){  
//    require_once ('Memcache.php');
//    $mem = new hdMemcache();

	$mem = new Memcache;
	$mem->connect("127.0.0.1",11211);
    $access_token=$mem->get("access_token2");
    if (!$access_token)
    {
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81";  
        $json=http_request_json($url);//这个地方不能用file_get_contents  
        $data=json_decode($json,true);  
        if($data['access_token']){  
          //将access_token写入缓存
          //            require_once 'BaeMemcache.class.php';
          //         	$mem = new BaeMemcache();
            $mem->set("access_token2",$data['access_token'],0,3600); 	//设置cache，为下一步提供依据
          
            return $data['access_token']; 
        }else{  
            return "获取access_token错误";  
        }
 	}
   else
   {
   	return $access_token;
   }
} 
    //因为url是https 所有请求不能用file_get_contents,用curl请求json 数据  
function http_request_json($url){    
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($ch);  
        curl_close($ch);  
        return $result;    
} 
*/
?>