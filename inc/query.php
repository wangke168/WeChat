<?php
include("mysql.php");


/****订单查询

http://e.hengdianworld.com/searchorder_json.aspx?name=%E9%87%91%E5%86%9B%E8%88%AA&phone=13706794299

http://e.hengdianworld.com/searchorder_json.aspx?wxnumber=daasd
************************/
/*
function serOrder1($fromUsername, $toUsername)
{
  //先通过微信号查询是否有订单
	$url="http://e.hengdianworld.com/searchorder_json.aspx?wxnumber=".$fromUsername;
  	$json=file_get_contents($url);
  	$data = json_decode($json,true);
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
  
		$contentStr1 = micromessage_order($fromUsername);

        if (check_user($fromUsername)==true)
 		  {
			$name=user_info($fromUsername,"name");
			$tel=user_info($fromUsername,"phone");
		  	$url="http://e.hengdianworld.com/searchorder_json.aspx?name=".$name."&phone=".$tel;
			$json=file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?name=".$name."&phone=".$tel);
			$data = json_decode($json,true);
			//查询数组情况
			$ticketcount = count($data['ticketorder']);
			$inclusivecount = count($data['inclusiveorder']);
			$hotelcount = count($data['hotelorder']);
			if ($ticketcount==0&&$inclusivecount==0&&$hotelcount==0)
			{
				$contentStr2="您好，没有查到您在官网的订单，您绑定的姓名:".$name.",电话:".$tel."，请再核对。";
			}
			else
			{
				$contentStr2 = web_order($fromUsername, $toUsername,$name,$tel,$data);
				$url="http://1.hengdianwp.duapp.com/?p=9";
              //			echo responseNews($fromUsername, $toUsername,"订单查询",$contentStr,"",$url);
			}
		   }
		else
		{
			require_once 'BaeMemcache.class.php';
			$memcache = new BaeMemcache();
			$memcache->set($fromUsername."_do","bd_0",0,60); 	//设置cache，为下一步提供依据
			$contentStr2="您好，如果您有在官网预订，请先输入您预订时留得姓名。";
          //			echo responseText($fromUsername,$toUsername,$str);
		}

		$contentStr = $contentStr1."\n\n".$contentStr2;
  		$contentStr =$contentStr."\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
      	$url="http://1.hengdianwp.duapp.com/ArticleDetail.php?id=9";
  //$url="http://1.hengdianwp.duapp.com/?p=9";
      echo responseNews($fromUsername, $toUsername,"订单查询",$contentStr,"",$url);
  //    	responseV_News($fromUsername,$toUsername,"订单查询",$contentStr,"",$url);
   	
}

/*  


没有微信订单时用和微信绑定的姓名和手机号查询，如未绑定，则提示客人绑定姓名和手机号


function serOrder2($fromUsername, $toUsername)
 
{
  //   include("wx_tpl.php");
  //先查询微信号对应绑定的姓名和手机号
  if (check_user($fromUsername)==true)
   {
     $name=user_info($fromUsername,"name");
     $tel=user_info($fromUsername,"phone");
     //    $name=urlencode("王科");
     //     $tel="13605725464";
  	 $url="http://e.hengdianworld.com/searchorder_json.aspx?name=".$name."&phone=".$tel;
	 $json=file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?name=".$name."&phone=".$tel);
     $data = json_decode($json,true);

  //查询数组情况
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
   	if ($ticketcount==0&&$inclusivecount==0&&$hotelcount==0)
    {
      $str="您好，没有查到您在官网的订单，您绑定的姓名:".$name.",电话:".$tel."，请再核对。";
      echo responseText($fromUsername,$toUsername,$str);
    
    }
	else
	{
		$contentStr = web_order($fromUsername, $toUsername,$name,$tel,$data);
      //    	$msgType = "news";
      	$url="http://1.hengdianwp.duapp.com/?p=9";
	    echo responseNews($fromUsername, $toUsername,"订单查询",$contentStr,"",$url);
  }
}
   else
	{
  	    require_once 'BaeMemcache.class.php';
		$memcache = new BaeMemcache();
        $memcache->set($fromUsername."_do","bd_0",0,30); 	//设置cache，为下一步提供依据
 		$str="您好，如果您有在官网预订，请先输入您预订时留得姓名。";
        echo responseText($fromUsername,$toUsername,$str);
	}
}
*/
/*********************

	查询微信订单
**************/
function micromessage_order($fromUsername)
{
	$url="http://e.hengdianworld.com/searchorder_json.aspx?wxnumber=".$fromUsername;
  	$json=file_get_contents($url);
  	$data = json_decode($json,true);
	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
	$i=0;
  $str="您好，您通过微信在横店影视城的订单信息如下:";
	if ($ticketcount<>0)
	    {
	      	for ($j=0; $j<$ticketcount; $j++)
	        {
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
              	$str=$str."\n姓名:".$data['ticketorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['ticketorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
				$str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
				$str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
              	$str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
				$str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
        	}
	    }
		if ($inclusivecount<>0)
	    {
	    	for ($j=0; $j<$inclusivecount; $j++)
            {
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['inclusiveorder'][$j]['sellid'];
              	$str=$str."\n姓名:".$data['inclusiveorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['inclusiveorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['inclusiveorder'][$j]['date2'];
				$str=$str."\n预购景点:".$data['inclusiveorder'][$j]['ticket'];
				$str=$str."\n入住酒店:".$data['inclusiveorder'][$j]['hotel'];
				$str=$str."\n人数:".$data['inclusiveorder'][$j]['numbers'];
				$str=$str."\n订单状态:".$data['inclusiveorder'][$j]['flag']."\n";
			}
		}
		if ($hotelcount<>0)
    	{
    		for ($j=0; $j<$hotelcount; $j++)
        	{
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['hotelorder'][$j]['sellid'];
            	$str=$str."\n姓名:".$data['hotelorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['hotelorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['hotelorder'][$j]['date2'];
				$str=$str."\n预订酒店:".$data['hotelorder'][$j]['hotel'];
				$str=$str."\n数量:".$data['hotelorder'][$j]['numbers'];
				$str=$str."\n天数:".$data['hotelorder'][$j]['days'];
				$str=$str."\n订单状态:".$data['hotelorder'][$j]['flag']."\n";
			}
		}
  
  //		   	$contentStr =$str."\n\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
  			return $str;
}

/*********************

	查询官网订单
**************/
function web_order($fromUsername, $toUsername,$name,$tel,$data)
{
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
  
  	$i=0;
  	$str="您好，您在官网的预订信息如下\n";
    $str=$str."姓名：".$name."   电话：".$tel."\n";
	if ($ticketcount<>0)
    {
      	for ($j=0; $j<$ticketcount; $j++)
        {
          $i=$i+1;
          $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
          $str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
          $str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
          $str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
          $str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
        }
    }
	if ($inclusivecount<>0)
    {
    	for ($j=0; $j<$inclusivecount; $j++)
        {
          $i=$i+1;
          $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['inclusiveorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['inclusiveorder'][$j]['date2'];
          $str=$str."\n预购景点:".$data['inclusiveorder'][$j]['ticket'];
          $str=$str."\n入住酒店:".$data['inclusiveorder'][$j]['hotel'];
          $str=$str."\n人数:".$data['inclusiveorder'][$j]['numbers'];
          $str=$str."\n订单状态:".$data['inclusiveorder'][$j]['flag']."\n";
        }
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
  // 		   	$contentStr =$str."\n\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
  			return $str;
}


/*

用订单号查询  V1311050069

http://e.hengdianworld.com/searchorder_json.aspx?sellid=V1311050069
*/
function Search_order($sellid)
{
  	$url="http://e.hengdianworld.com/searchorder_json.aspx?sellid=".$sellid;
	 $json=file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?sellid=".$sellid);
     $data = json_decode($json,true);

  
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
  
  	$i=0;
  //  	$str="您好，您在官网的预订信息如下\n";
  //    $str=$str."姓名：".$name."   电话：".$tel."\n";
	if ($ticketcount<>0)
    {
      	for ($j=0; $j<$ticketcount; $j++)
        {
          $i=$i+1;
          //                   $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
          $str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
          $str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
          $str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
          $str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
  /*        
          $name=$data['ticketorder'][$j]['name'];
          $tel=$data['ticketorder'][$j]['tel'];
*/          
          
        }
    }
	if ($inclusivecount<>0)
    {
    	for ($j=0; $j<$inclusivecount; $j++)
        {
          $i=$i+1;
          //                   $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['inclusiveorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['inclusiveorder'][$j]['date2'];
          $str=$str."\n预购景点:".$data['inclusiveorder'][$j]['ticket'];
          $str=$str."\n入住酒店:".$data['inclusiveorder'][$j]['hotel'];
          $str=$str."\n人数:".$data['inclusiveorder'][$j]['numbers'];
          $str=$str."\n订单状态:".$data['inclusiveorder'][$j]['flag']."\n";
 /*       
          $name=$data['inclusiveorder'][$j]['name'];
          $tel=$data['inclusiveorder'][$j]['tel'];
*/  
        }
    }
	if ($hotelcount<>0)
    {
    	for ($j=0; $j<$hotelcount; $j++)
        {
          $i=$i+1;
          //          $str=$str."\n订单".$i;
          $str=$str."\n订单号:".$data['hotelorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['hotelorder'][$j]['date2'];
          $str=$str."\n预订酒店:".$data['hotelorder'][$j]['hotel'];
          $str=$str."\n数量:".$data['hotelorder'][$j]['numbers'];
          $str=$str."\n天数:".$data['hotelorder'][$j]['days'];
          $str=$str."\n订单状态:".$data['hotelorder'][$j]['flag']."\n";
 /*       
          $name=$data['hotelorder'][$j]['name'];
          $tel=$data['hotelorder'][$j]['tel'];
*/            
        }
    }
  // 		   	$contentStr =$str."\n\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
 return $str;
  			
}

/*

查询订单号相关信息  V1311050069

http://e.hengdianworld.com/searchorder_json.aspx?sellid=V1311050069
*/
function Search_order_key($sellid,$keyword)
{
  	$url="http://e.hengdianworld.com/searchorder_json.aspx?sellid=".$sellid;
	 $json=http_request_json("http://e.hengdianworld.com/searchorder_json.aspx?sellid=".$sellid);
     $data = json_decode($json,true);

  
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
  
  	$i=0;
  //  	$str="您好，您在官网的预订信息如下\n";
  //    $str=$str."姓名：".$name."   电话：".$tel."\n";
	if ($ticketcount<>0)
    {
      
      if($keyword=='arrivedate')
      {$result = $data['ticketorder'][0]['date2'];}
          $str=$str."\n订单号:".$data['ticketorder'][0]['sellid'];
          $str=$str."\n预达日期:".$data['ticketorder'][0]['date2'];
          $str=$str."\n预购景点:".$data['ticketorder'][0]['ticket'];
          $str=$str."\n人数:".$data['ticketorder'][0]['numbers'];
          $str=$str."\n订单识别码:".$data['ticketorder'][0]['code']."（在检票口出示此识别码可直接进入景区。）";
          $str=$str."\n订单状态:".$data['ticketorder'][0]['flag']."\n";
        
    }
	if ($inclusivecount<>0)
    {
       if($keyword=='arrivedate')
      {$result = $data['inclusiveorder'][0]['date2'];}
          $str=$str."\n订单号:".$data['inclusiveorder'][0]['sellid'];
          $str=$str."\n预达日期:".$data['inclusiveorder'][0]['date2'];
          $str=$str."\n预购景点:".$data['inclusiveorder'][0]['ticket'];
          $str=$str."\n入住酒店:".$data['inclusiveorder'][0]['hotel'];
          $str=$str."\n人数:".$data['inclusiveorder'][0]['numbers'];
          $str=$str."\n订单状态:".$data['inclusiveorder'][0]['flag']."\n";
    
    }
	if ($hotelcount<>0)
    {
      if($keyword=='arrivedate')
      {$result = $data['hotelorder'][0]['date2'];}
          $str=$str."\n订单号:".$data['hotelorder'][0]['sellid'];
          $str=$str."\n预达日期:".$data['hotelorder'][0]['date2'];
          $str=$str."\n预订酒店:".$data['hotelorder'][0]['hotel'];
          $str=$str."\n数量:".$data['hotelorder'][0]['numbers'];
          $str=$str."\n天数:".$data['hotelorder'][0]['days'];
          $str=$str."\n订单状态:".$data['hotelorder'][0]['flag']."\n";
     
    }
  // 		   	$contentStr =$str."\n\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
 return $result;
  			
}
/*********************************************
测试
**********************************************/
function micromessage_order1($fromUsername, $toUsername)
{
  //先通过微信号查询是否有订单
	$url="http://e.hengdianworld.com/searchorder_json.aspx?wxnumber=".$fromUsername;
  	$json=file_get_contents($url);
  	$data = json_decode($json,true);
	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
	$i=0;
  $str="您好，您通过微信在横店影视城的订单信息如下:";
	if ($ticketcount<>0)
	    {
	      	for ($j=0; $j<$ticketcount; $j++)
	        {
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
              	$str=$str."\n姓名:".$data['ticketorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['ticketorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
				$str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
				$str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
              	$str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
				$str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
        	}
	    }
		if ($inclusivecount<>0)
	    {
	    	for ($j=0; $j<$inclusivecount; $j++)
            {
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['inclusiveorder'][$j]['sellid'];
              	$str=$str."\n姓名:".$data['inclusiveorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['inclusiveorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['inclusiveorder'][$j]['date2'];
				$str=$str."\n预购景点:".$data['inclusiveorder'][$j]['ticket'];
				$str=$str."\n入住酒店:".$data['inclusiveorder'][$j]['hotel'];
				$str=$str."\n人数:".$data['inclusiveorder'][$j]['numbers'];
				$str=$str."\n订单状态:".$data['inclusiveorder'][$j]['flag']."\n";
			}
		}
		if ($hotelcount<>0)
    	{
    		for ($j=0; $j<$hotelcount; $j++)
        	{
				$i=$i+1;
				$str=$str."\n订单".$i;
				$str=$str."\n订单号:".$data['hotelorder'][$j]['sellid'];
            	$str=$str."\n姓名:".$data['hotelorder'][$j]['name'];
              	$str=$str."\n手机号:".$data['hotelorder'][$j]['phone'];
				$str=$str."\n预达日期:".$data['hotelorder'][$j]['date2'];
				$str=$str."\n预订酒店:".$data['hotelorder'][$j]['hotel'];
				$str=$str."\n数量:".$data['hotelorder'][$j]['numbers'];
				$str=$str."\n天数:".$data['hotelorder'][$j]['days'];
				$str=$str."\n订单状态:".$data['hotelorder'][$j]['flag']."\n";
			}
		}
  $url1="http://1.hengdianwp.duapp.com/ArticleDetail.php?id=9";
  //		   	$contentStr =$str."\n\n\n点击\"阅读全文\"了解各产品的使用方式。\n\n如有疑问，请电询客服中心0579-86547211。";
  //		return $str;
   $Contentstr="您好，您是要报名本周探班游吗，请点击<a href=http://4.eastsun.duapp.com/active/post.php?openid=".$fromUsername.">输入有效订单号。</a>";
  //echo responseText($fromUsername,$toUsername, $Contentstr);
     responseV_Text($fromUsername,$toUsername,$Contentstr);	
  
    echo responseNews($fromUsername, $toUsername,"微信预订查询",$str,"",$url1);
  
}

/**
 * 检票口工作人员查询订单
 * @access  public
 * @param   string       $tel         客人手机号
 * @echo  string         $str         输出门票订单详情
 */
function Check_Order_Group1($fromUsername,$toUsername,$tel)
{
  if (CheckGroup($fromUsername)=="1")
  {
    $contentStr=Check_tecket($tel);
    $url="http://1.hengdianwp.duapp.com/ArticleDetail.php?id=9";
	$resultStr = responseNews($fromUsername, $toUsername,"订单查询",$contentStr,"",$url);
	echo $resultStr;
  }
  else
  {
    request_normal($fromUsername,$toUsername,$KeyWord);
  }
}
/**
 * 根据客人手机号确认订单信息
 * @access  public
 * @param   string       $tel      客人手机号
 * @return  string       $str      订单详情
 */

function Check_tecket($tel)
{
  //    $url="http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel;
//	$json=file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel);

/*
	$json=http_request_json("http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel);
    $data = json_decode($json,true);
  	$ticketcount = count($data['ticketorder']);
	$inclusivecount = count($data['inclusiveorder']);
	$hotelcount = count($data['hotelorder']);
*/

$url = "http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel;
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
$json = curl_exec($ch);
$data = json_decode($json,true);
$ticketcount = count($data['ticketorder']);
$inclusivecount = count($data['inclusiveorder']);
$hotelcount = count($data['hotelorder']);


  	$i=0;
  	
  //    $str=$str."姓名：".$name."   电话：".$tel."\n";
	if ($ticketcount<>0)
    {
      $str="您好，该客人的预订信息如下\n注意，若是联票+梦幻谷的门票仍然需要身份证检票\n";
      	for ($j=0; $j<$ticketcount; $j++)
        {
          $i=$i+1;
          $str=$str."\n订单".$i;
          $str=$str."\n姓名：".$data['ticketorder'][$j]['name'];
          $str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
          $str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
          $str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
          $str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
          $str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
          $str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
        }
    }
  else
  {
    $str="该手机号下无门票订单";
  }
  return $str;
}


/********************************

查询客人微信号
*********************************/
function wx_openid($fromUsername, $toUsername){
	return responseText($fromUsername,$toUsername, $fromUsername);
}

?>