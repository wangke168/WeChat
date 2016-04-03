<?php
include("mysql.php");



/*

有奖活动函数集中营

*/



/*

 * 生成兑奖号
 * @param    string       $classid     活动父ID
 * @return   string       $result      到数据库查（WX_Request_Keyword）询输出关键字

*/

//function Create_Serial($fromUsername,$toUsername,$classid,$number,$startdate,$enddate)
function Create_Serial($ParentID,$startdate,$enddate)
{
  include ("mysql.php");
  //计算出活动的天数
  $Startdate=strtotime($startdate);
  $Enddate=strtotime($enddate);
  $day=($Enddate-$Startdate)/3600/24; 
  
  $active_date=$startdate;

	for ($i=0; $i<=$day; $i++)
	{
	$result = mysql_query("SELECT * from wx_activity_list where ParentID='".$ParentID."' order by id desc",$link);
	 while($row = mysql_fetch_array($result))
	{
		
			//每次生成兑奖码的张数
			for ($j=1; $j<=$row['Activity_Count']; $j++)
			{

				$Serial=mt_rand(10000000,99999999);
				mysql_query("INSERT INTO WX_Activity_number (Serial,classid,ParentID,SendDate) VALUES ('".$Serial."','".$row['ID']."','".$row['ParentID']."','".$active_date."')") or die(mysql_error());  

			}
	 }
	 $active_date=date("Y-m-d",strtotime($active_date."+1 day"));
	}

    echo "已经生成";
}
//	Create_Serial("1","2014-6-24","2014-6-26");

/*
抢楼
 * @param   string       $classid         活动id
 * @param   string       $winid        	  几楼能中奖
 * @param   string       $type        	  能否重复中奖，1为能重复中奖，2不能
*/
function floor_active($fromUsername,$toUsername,$classid,$winid,$type)
{
  include("mysql.php");
  //先检查是否存在用户，如果已经存在，则提示
  
  if ($type=="2")
  {
    $row=mysql_fetch_array(mysql_query("select * from WX_Activity where classid='".$classid."' and wx_openid='".$fromUsername."'"));
  	if ($row)
    {
      $contentStr1="您已经参加过本次活动。\n";
      if ($row['win_if']==1)
      {
      	$contentStr1=$contentStr1."\n您的对奖号是".$row['Serial_number'];
         if ($row['used']==1)
        {
          $contentStr1=$contentStr1."已使用";
        }
        else
        {
          $contentStr1=$contentStr1."未使用";
		}
      }
      else
      {
      	$contentStr2="本次活动一个帐号只能参加一次的哦。";
      }
     }
  	else
    {
	  $contentStr2=Activity_Active($classid,$fromUsername,$winid);
    }
   	$contentStr=$contentStr1.$contentStr2;
	$msgType = "text";
  	echo responseText($fromUsername,$toUsername,$contentStr);
  }
  else
  {
	//由于可以重复参加，因此查询最后一条记录，看是否中奖。
    $row=mysql_fetch_array(mysql_query("SELECT * from WX_Activity where classid='".$classid."' and wx_openID = '".$fromUsername."' order by id desc LIMIT 0,1"));
  	if ($row)
    {
   
      if ($row['win_if']==1)
      {
		$contentStr1="您已经参加过本次活动。\n";
      	$contentStr1=$contentStr1."\n您的对奖号是".$row['Serial_number'];
        if ($row['used']==1)
        {
          $contentStr1=$contentStr1."\n使用情况：已使用";
        }
        else
        {
          $contentStr1=$contentStr1."\n使用情况：未使用";
		}
      }
      else
      {
		$contentStr2=Activity_Active($classid,$fromUsername,$winid);
      }
	}
	else
	{
	  $contentStr2=Activity_Active($classid,$fromUsername,$winid);
	}
   	  $contentStr=$contentStr1.$contentStr2;
	  $msgType = "text";
  	  echo responseText($fromUsername,$toUsername,$contentStr);
   }
}

/*
 * 参加活动
 * @param   string       $classid         活动id
 * @param   string       $fromUsername    参与者id
 * @param   string       $winid           中奖楼层
 * @param	int			 $repeat		  可重复抽奖次数
 * @return  string       $str        	  是否中奖信息
*/
function Activity_Active($Parentid,$fromUsername,$winid,$repeat)
{
  include ("mysql.php");
  if  (Check_Activity_Number($Parentid)==False)
  {
    $str="您好，感谢您参加本次活动，本次活动的奖已经全部中出。";
  }
  else
  {
	$result=mysql_query("SELECT count(*) from WX_Activity where Parentid='".$Parentid."' and wx_openID = '".$fromUsername."' and to_days(AddDate) = to_days(now()) ",$link);
	$row=mysql_fetch_array($result);
	$todaycount=$row['count(*)'];
	if ($todaycount<$repeat)  //如果当天的记录数少于可重复抽奖次数
	  {

		mysql_query("INSERT INTO WX_Activity (wx_OpenID,Parentid) VALUES ('".$fromUsername."','".$Parentid."')") or die(mysql_error());  
		//查找对应ID，根据规则查出是否中奖
		$result=mysql_query("SELECT * from WX_Activity where Parentid='".$Parentid."' and wx_openID = '".$fromUsername."' order by id desc LIMIT 0,1",$link);
		$row=mysql_fetch_array($result);
		$activeid = $row['ID'];
 
		if ($activeid%$winid=="0")
		{
		  //在数据库中找到中奖优惠码
		  $result=mysql_query("SELECT  * from WX_Activity_number where ParentID='".$Parentid."' and send = '0' and to_days(SendDate) = to_days(now()) order by rand() LIMIT 0,1",$link);
		  $row=mysql_fetch_array($result);
		  $Serial=$row['Serial'];
		  $Serialid=$row['ID'];
		  $classid=$row['classid'];
		  //往兑奖券表中做标记
		  mysql_query("update WX_Activity_number set send='1' where id='".$Serialid."'") or die(mysql_error());  
		  mysql_query("update WX_Activity set Serial_number='".$Serial."',win_if='1',Classid='".$classid."' where id='".$activeid."'" ) or die(mysql_error());  
		  $str="感谢你参加本次活动，\n恭喜您中奖\n您的兑奖号为".$Serial."，请拨打400-1-9999141转1兑奖。";
		}
		else
		{
		  $str="感谢你参加本次活动，\n很遗憾，您未中奖。";
		}
	  }
	  else
	  {
		$str="您今天已经参加过。";
	  }
  }
//  return $str;
echo $classid;
}



/*
 * 查询是否中奖
 * @param   string       $classid         活动id
 * @param   string       $fromUsername    参与者id
 * @return  boolean      $flag        	  是否中奖
*/
function Check_Activity_Win($fromUsername,$classid)
{
  include("mysql.php");
  $row=mysql_fetch_array(mysql_query("select * from WX_Activity where classid='".$classid."' and wx_openid='".$fromUsername."'"));
  if ($row)
  {
    $flag=True;
  }
  else
  {
    $flag=False;
  }
  return $flag;
}

/*
 * 查询是否已经使用兑奖号
 * @param   string       $classid         活动id
 * @param   string       $fromUsername    参与者id
 * @return  boolean      $flag        	  是否已经使用
*/
function Check_Activity_Used($fromUsername,$classid)
{
  include("mysql.php");
  $row=mysql_fetch_array(mysql_query("select * from WX_Activity where classid='".$classid."' and wx_openid='".$fromUsername."'"));
  if ($row)
  {
      $contentStr1="您已经参加过本次活动。\n";
      if ($row['win_if']==1)
      {
      	$contentStr1=$contentStr1."\n您的对奖号是".$row['Serial_number'];
         if ($row['used']==1)
        {
          $contentStr1=$contentStr1."已使用";
        }
        else
        {
          $contentStr1=$contentStr1."未使用";
		}
      }
  }
}


/*
 * 查询是否还有兑奖码
 * @param   string       $classid         活动id
 * @return  boolean      $flag        	  是否有奖券
*/
function Check_Activity_Number($classid)
{
  include("mysql.php");
  $result=mysql_query("SELECT  * from WX_Activity_number where ParentID='".$classid."' and send = '0' and to_days(SendDate) = to_days(now()) LIMIT 0,1",$link);
  $row=mysql_fetch_array($result);
  //查询当天的优惠码是否已经发完
  if (!$row)
  {
    $flag=False;
  }
  else
  {
    $flag=True;
  }
  return $flag;
}


/*
 * 根据id查询奖品种类
 * @param   int       $classid     奖品id
 * @return  string    $Activity_Name        	  是否有奖券
 */
 function Query_ActivierName($classid)
 {
	include("mysql.php");
	$result=mysql_query("select * from WX_Activity_list where ID='".$classid."'",$link);
    $row=mysql_fetch_array($result);
	if ($row)
	 {
		$Activity_Name = $row['Activity_Name'];
	}
	else
	 {
		$Activity_Name = "没有该类奖品，请检查后再查询";
	 }
	return	$Activity_Name; 
 }



/*
* 查询是否中奖（猜灯谜）
*
*
*/
function query_dm($fromUsername,$toUsername,$ParentID)
{
	include ("mysql.php");
	$result=mysql_query("select * from wx_activity where wx_openid='".$fromUsername."' and ParentID='".$ParentID."' and win_if='1'",$link);

	if(mysql_num_rows($result)==0)
	{
		responseV_Text($fromUsername,$toUsername,"不好意思，您未参加过猜灯谜活动或猜谜失败。如果您要参加梦幻谷猜灯谜活动，请点击菜单横店攻略中的最新活动。");
	}
	else
	{
	  while($row=mysql_fetch_array($result))
		{
			Send_SerialNumber_dm($fromUsername,$ParentID);
	  }
	}
//	while($row = mysql_fetch_array($result))
}
 function Send_SerialNumber_dm($fromUsername,$ParentID)
 {
	include("mysql.php");

//	include ("../../inc/response.php");
//	include ("../../inc/function.php");
	$result=mysql_query("select * from wx_activity where wx_openid='".$fromUsername."' and win_if='1' and ParentID='".$ParentID."'",$link);
    $row=mysql_fetch_array($result);
	if ($row['used']==1)
	 {
	  $Used="已使用";
	}
	else
	 {
	  $Used="未使用";
	}
	$str="领奖地点：梦幻剧场门口";
//	$str=$str."\n获奖验证码：".$SerialNumber;
	$str=$str."\n兑奖情况：".$Used;
	$str=$str."\n具体使用方法请点击下方阅读原文，有疑问请致电057986556600。";
//	$str=$str."\n如您刮中用餐抵价券或金牌菜，可至旅游大厦餐厅直接出示使用。";
	$Title = "恭喜您，您猜对了灯谜";
    $Description = $str;  
    $PicUrl="";
	$toUsername="";
    $Url = "http://weix.hengdianworld.com/article/dmcheck.php?id=".$row['ID']."&wxid=".$fromUsername;
	responseV_News($fromUsername,$toUsername,$Title,$Description,$PicUrl,$Url); 
 }






/*
* 查询是否中奖（刮刮乐）
*
*
*/
function Query_ggl($fromUsername,$toUsername)
{
	include ("mysql.php");
//	include ('../../inc/response.php');
//	include ('../../inc/function.php');
	$result=mysql_query("select * from wx_activity where wx_openid='".$fromUsername."' and parentid<>'39'  and win_if='1'",$link);

	if(mysql_num_rows($result)==0)
	{
		responseV_Text($fromUsername,$toUsername,"不好意思，您未刮到过奖品，再接再厉哦。如果您要参加刮刮乐活动，请点击菜单活动专区中的最新活动。");
	}
	else
	{
		while($row = mysql_fetch_array($result))
		{
/*			if ($row['Used']==1)
			{
				$Used="已使用";
			}
			else
			{
				$Used="未使用";
			}
*/
			$str="奖品内容：".Query_ActivierName($row['ClassID']);
			$str=$str."\n获奖验证码：".$row['Serial_number']."\n";
		//	$str=$str."\n使用情况：".$Used."\n";
			$str=$str."点击下方阅读全文，查看详细使用方式";
			$Title = "恭喜您，您曾刮到了奖品，以下是您的中奖记录";
			$Description = $str;  
			$PicUrl="";
			$toUsername="";

			$result1=mysql_query("select * from wx_activity_list where id='".$row['ParentID']."'",$link);
			$row1=mysql_fetch_array($result1);
			$Url = "http://weix.hengdianworld.com/article/articledetail.php?id=".$row1['Activity_Articleid'];
			responseV_News($fromUsername,$toUsername,$Title,$Description,$PicUrl,$Url); 
		}
	}
//	while($row = mysql_fetch_array($result))
}


/*
* 查询是否中奖（摇一摇）
*
*
*/
function Query_yyy($fromUsername,$toUsername)
{
	include ("mysql.php");
//	include ('../../inc/response.php');
//	include ('../../inc/function.php');
	$result=mysql_query("select * from wx_activity where wx_openid='".$fromUsername."' and parentid='54'  and win_if='1'",$link);

	if(mysql_num_rows($result)==0)
	{
		responseV_Text($fromUsername,$toUsername,"不好意思，您未摇到过奖品，再接再厉哦。");
	}
	else
	{
		while($row = mysql_fetch_array($result))
		{
			if ($row['used']=='1')
			{
				$Used="已领取礼品";
			}
			else
			{
				$Used="未领取礼品";
			}

			$str="奖品内容：".Query_ActivierName($row['ClassID']);
			$str=$str."\n\n请到服务台领取礼品\n";
//			$str=$str."\n获奖验证码：".$row['Serial_number']."\n";
			$str=$str."\n领取情况：".$Used."\n";
//			$str=$str."点击下方阅读全文，查看详细使用方式";
			$Title = "恭喜您，您曾摇到了奖品，以下是您的中奖记录";
			$Description = $str;  
			$PicUrl="";
			$toUsername="";

			$result1=mysql_query("select * from wx_activity_list where id='".$row['ParentID']."'",$link);
			$row1=mysql_fetch_array($result1);
			$Url = "http://weix.hengdianworld.com/active/wxshake/prizelist.php?id=".$row['ParentID']."&fromUsername=".$fromUsername;
			responseV_News($fromUsername,$toUsername,$Title,$Description,$PicUrl,$Url); 
		}
	}
//	while($row = mysql_fetch_array($result))
}
?>