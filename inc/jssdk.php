<?php
class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
	$mem = new Memcache;
	$mem->connect("127.0.0.1",11211);
	$ticket=$mem->get("access_ticket");
	if (!$ticket)
	{
		$accessToken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
		$json = $this->httpGet($url);
		$res = json_decode($json,true);  
		$ticket = $res['ticket'];
		if($ticket)
		{
			$mem->set("access_ticket",$ticket,0,5000); 
			 return $res['ticket'];
		}else{
			return "Get JsApiTicket Error";  
		}
		
	}
	else{
		return $ticket;
	}
	
  }



/*
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }
    return $ticket;
*/


  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	$mem = new Memcache;
	$mem->connect("127.0.0.1",11211);
    $access_token=$mem->get("access_token1");
    if (!$access_token)
    {
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81";  
        $json=$this->httpGet($url);//这个地方不能用file_get_contents  
        $data=json_decode($json,true);  
        if($data['access_token']){  
          //将access_token写入缓存
          //            require_once 'BaeMemcache.class.php';
          //         	$mem = new BaeMemcache();
            $mem->set("access_token1",$data['access_token'],0,5000); 	//设置cache，为下一步提供依据
          
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

  private function httpGet($url) {
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($ch);  
        curl_close($ch);  
        return $result;    
  }
}