<?php
 include("mysql.php");

/*检查该用户是否已经绑定

fromUsername   微信号

*/
function check_user($fromUsername)
{
  include("mysql.php");
  $url="SELECT count(*) from WX_Info where Wx_OpenID = '" . $fromUsername . "'";
  $itemsCount=mysql_query($url,$link);
  $row1 = mysql_fetch_array($itemsCount);
  $itemsCount=$row1['count(*)'];
  //如果数据库中没有该关键字的记录，输出帮助提示
  if ($itemsCount<>0)
  {
     	return true;
  }
  else
  {
      	return false;
  }
}


/*********************根据微信号查询客人绑定的姓名和联系电话

fromUsername   微信号
type		name 或者phone

**************************/
function user_info($fromUsername,$type)
{
   include("mysql.php");
  $result=mysql_query("SELECT * from WX_Info where Wx_OpenID = '" . $fromUsername . "'",$link);
  $row=mysql_fetch_array($result);
  if ($type=="name")
  {
   return $row['D_Name'];
  }
  elseif ($type=="phone")
  {
  	return $row['D_Phone'];
  }
  //    return "SELECT * from WX_Info where Wx_OpenID = '" . $fromUsername . "'";
    mysql_close($link);
}
    

/***
当客人输bd时，在cache中flag写入bd_0
$memcache->add($key,$value);//第一次向cache中增加一条key:value,并且永久有效
*/
function bd0_cache($textTpl, $fromUsername, $toUsername, $time)
{
  //检查在openid表中是否已经有用户数据，如果没有插入
  		include "mysql.php" ;
  /*      	$row=mysql_fetch_array(mysql_query("select count(*) from WX_OpenID where openid='".$fromUsername."'"));
        if ($row[0]=="0")
        {
			mysql_query("INSERT INTO WX_OpenID (OpenID) VALUES ('".$fromUsername."')") or die(mysql_error());
			mysql_close($link);
        }
*/ 
  //检查是否已经绑定，如果已经绑定则提示已经绑定
  		$row=mysql_fetch_array(mysql_query("select count(*) from WX_Info where wx_openid='".$fromUsername."'"));
        if ($row[0]>"0")
        {
			$contentStr="您的帐号已经绑定,无需重新绑定";
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
  			echo $resultStr;
        }
		else
        {
    	    require_once 'BaeMemcache.class.php';
			$memcache = new BaeMemcache();
       		$memcache->set($fromUsername."_do","bd_0",0,60); 	//设置cache，为下一步提供依据
 		 	$contentStr="请输入您的姓名";
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		  	echo $resultStr;
        }
  mysql_close($link);
}

/***
当客人输姓名时，在cache中的flag写入bd_1，同时在$fromUsername."user"输入姓名
$memcache->add($key,$value);//第一次向cache中增加一条key:value,并且永久有效
*/
function bd_cache($keyword,$textTpl, $fromUsername, $toUsername, $time)
{
	require_once 'BaeMemcache.class.php';
	$memcache = new BaeMemcache();
 	$_do=$memcache->get($fromUsername."_do");
  //检测上一步的操作是否是输入“bd”
  	if ($_do=="bd_0")
    {
      	require_once 'BaeMemcache.class.php';
		$memcache = new BaeMemcache();
	  	$memcache->set($fromUsername."user","{$keyword}",0,60);  //在fromUsername中写入缓存
	  	$memcache->set($fromUsername."_do","bd_1",0,60); 
	  //	$contentStr= $memcache->get($fromUsername."user");
 	 	$contentStr="请输入您预订时的手机号";
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
 	 	echo $resultStr;
    }
  	//检测上一步的操作输入“bd”后，是否已经输入姓名
  	elseif($_do=="bd_1")
    {
    $memcache->set($fromUsername."phone","{$keyword}",0,60);  //在fromUsername中写入缓存
  	$memcache->set($fromUsername."_do","bd_2",0,60); 
  	$contentStr1 = $memcache->get($fromUsername."user");
  	$contentStr2 = $memcache->get($fromUsername."phone");
  	$contentStr="您好，您绑定的姓名为".$contentStr1."手机号为".$contentStr2."，确认请回复1，重新输入请回复0。";
	$msgType = "text";
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
  	echo $resultStr;
    }
  	elseif (($_do=="bd_2")&&($keyword=="0"))
    {
    	$memcache->set($fromUsername."_do","bd_0",0,60); 
  //	$contentStr= $memcache->get($fromUsername."user");
  		$contentStr="请输入您的姓名";
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
  		echo $resultStr;
    }
  	elseif (($_do=="bd_2")&&($keyword=="1"))
    {
      	$UserName = $memcache->get($fromUsername."user");
  		$UserPhone = $memcache->get($fromUsername."phone");
     
      //先查询openid的ID，然后把姓名和电话插入标wx_test
  		include "mysql.php" ;
      //      	$result=mysql_fetch_array(mysql_query("select id from WX_OpenID where openid='".$fromUsername."'"));
		mysql_query("INSERT INTO WX_Info (wx_openid,d_name,d_phone) VALUES ('".$fromUsername."','".$UserName."','".$UserPhone."')") or die(mysql_error());
		mysql_close($link);
      	$contentStr="绑定成功,姓名：".$UserName."手机:".$UserPhone."，您可以使用订单查询功能，查询您在官网预订的订单。";
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
  		echo $resultStr;
    }
  
}

?>