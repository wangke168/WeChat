<?php
$xjson = "{
      \"button\":[
      {
           \"name\":\"活动专区\",
           \"sub_button\":[
			{
               \"type\":\"click\",
               \"name\":\"最新活动\",
               \"key\":\"2\"
            },{
               \"type\":\"click\",
               \"name\":\"玩在横店\",
               \"key\":\"3\"
            },
			{
               \"type\":\"click\",
               \"name\":\"秀在横店\",
               \"key\":\"4\"
            },
			{
               \"type\":\"click\",
               \"name\":\"住在横店\",
               \"key\":\"5\"
            },
			{
               \"type\":\"view\",
               \"name\":\"百礼挑一预约\",
               \"url\":\"http://e.hengdianworld.com/freeticket/default.aspx\"
            }]
       },
       {
           \"name\":\"我要预订\",
           \"sub_button\":[
            {
               \"type\":\"click\",
               \"name\":\"门票预订\",
               \"key\":\"7\"
            },
           {
               \"type\":\"click\",
               \"name\":\"特惠（门票+住宿）\",
               \"key\":\"8\"
            },
		  {
               \"type\":\"click\",
               \"name\":\"酒店预订\",
               \"key\":\"9\"
            },
			{
               \"type\":\"view\",
               \"name\":\"订单查询\",
               \"url\":\"http://e.hengdianworld.com/yd_search.aspx\"
            }]
       },
       {
           \"name\":\"更多服务\",
           \"sub_button\":[
            {
               \"type\":\"click\",
               \"name\":\"客服电话\",
               \"key\":\"13\"
            },
			{
               \"type\":\"click\",
               \"name\":\"景区节目时间表\",
               \"key\":\"14\"
            },
			{
               \"type\":\"click\",
               \"name\":\"剧组拍摄动态\",
               \"key\":\"15\"
            },
			{
               \"type\":\"click\",
			   \"name\":\"交通速查/叫出租/导航\",
                \"key\":\"16\"
            },
			{
               \"type\":\"view\",
               \"name\":\"横店微社区\",
               \"url\":\"http://wx.wsq.qq.com/191658063\"
            }]
       }]
 }";
          //https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3e632d57ac5dcc68&secret=5bc0ddd4d88d904c9b24131fa9227f81

$token_url="http://weix2.hengdianworld.com/server/wechat/inc/gettoken.php";
$accessToken=file_get_contents($token_url);
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
$result = vpost($url,$xjson);
var_dump($result);
 
function vpost($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包x
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}

?>