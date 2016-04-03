<?php

include ("inc/config.php");

/*
 * 市场关注数和订单数统计
 */
function qr_info_count($qr_id,$counttype,$type)
{
    include("mysql.php");

    if ($type=="1")
    {
        if ($counttype=="count")
        {
            $row=mysql_fetch_array(mysql_query("SELECT count(*) as a from WX_User_Info where eventkey in (SELECT qrscene_id from wx_qrscene_info where qrscene_id='".$qr_id."' or parent_id='".$qr_id."')"));
            return $row['a'];
        }
        elseif ($counttype=="countorder")
        {
            $row=mysql_fetch_array(mysql_query("SELECT count(*) from WX_Order_Send where eventkey in (SELECT qrscene_id from wx_qrscene_info where qrscene_id='".$qr_id."' or parent_id='".$qr_id."')"));
            return $row[0];
        }
    }

    if ($type=="2")
    {
        if ($counttype=="count")
        {
            $row=mysql_fetch_array(mysql_query("SELECT count(*) from WX_User_Info where eventkey='".$qr_id."'"));
            return $row[0];
        }
        elseif ($counttype=="countorder")
        {
            $row=mysql_fetch_array(mysql_query("SELECT count(*) from WX_Order_Send where eventkey='".$qr_id."'"));
            return $row[0];
        }
    }

}

/*
 * 根据查询市场对应信息
 */
function Market_info($qr_id)
{
    if ($qr_id=="all")
    {
        return "全部";
    }
    else
    {
        include("../mysql.php");
        $row=mysql_fetch_array(mysql_query("SELECT Qrscene_Name from WX_Qrscene_Info where Qrscene_id='".$qr_id."'"));
        return $row[0];
    }
}

/*
 * 根据市场查对应二维码信息
 */
function Qrscene_info($Qrscene_Name)
{
    if ($Qrscene_Name=="all")
    {
        return "全部";
    }
    else
    {
        include("mysql.php");
        $row=mysql_fetch_array(mysql_query("SELECT Qrscene_id from WX_Qrscene_Info where Qrscene_Name='".$Qrscene_Name."'"));
        return $row[0];
    }
}

/*
*	市场自身生成下一级二维码
*/
function Create_Qrscene_id($Qrscene_id,$Qrscene_Name,$Qrscene_Person_Name,$Qrscene_Person_Phone,$ClassID,$Qrscene_UID)
{
    include ("mysql.php");
	$i=$Qrscene_id;
	$j=1000;
	$k=100000;
	for ($j;$j<$k;$j++)
	{
        $query=mysql_query("SELECT count(*)  from wx_qrscene_info where qrscene_id=".$j);
		$row=mysql_fetch_array($query);
		if ($row[0]==0)
		{
			break;
		}
	}
	mysql_query("insert into wx_qrscene_info (ClassID,Qrscene_id,parent_ID,Qrscene_Name,Qrscene_Person_Name,Qrscene_Person_Phone,uid) VALUES ('".$ClassID."','".$j."','".$Qrscene_id."','".$Qrscene_Name."','".$Qrscene_Person_Name."','".$Qrscene_Person_Phone."','".$Qrscene_UID."')  ") or die(mysql_error());
}

/*
*	新建用户
*/
function Create_User($name,$username,$Market)
{
    mysql_query("INSERT INTO Userlist (Name,UserName,Eventkey) VALUES ('".$name."','".$username."','".$Market."')") or die(mysql_error());

	echo "<script Language=Javascript>alert('添加用户成功！^_^');</script>";

	echo "<script>window.location =\"UserList.php\";</script>";

}

/*
*	修改用户
*/
function Edit_User($id,$name,$username,$Market)
{
    mysql_query("update Userlist set Name='".$name."',UserName='".$username."',Eventkey='".$Market."' where id=".$id."") or die(mysql_error());

	echo "<script Language=Javascript>alert('修改用户成功！^_^');</script>";

	echo "<script>window.location =\"UserList.php\";</script>";
}
/*
*	删除用户
*/
function Del_User($id)
{
    mysql_query("delete from Userlist where id=".$id."") or die(mysql_error());

	echo "<script Language=Javascript>alert('删除用户成功！^_^');</script>";

	echo "<script>window.location =\"UserList.php\";</script>";

}

/*
*	检查文章的单独ip阅读数
*/
function article_ip_hits($article_id)
{
    include ("mysql.php");

	$row=mysql_fetch_array(mysql_query("select count(*) as counthits from wx_article_hits where article_id='".$article_id."'",$link));

	return $row["counthits"];
}
/*
 * 获取月份
*/

function GetMonth($n)
{
 //   $time = strtotime(date("Y/m/d"));

    //   $month = date('Y-m',strtotime(date('Y',$time).'-'.(date('m',$time)-$n)));

    $month=date('Y-m',mktime(0,0,0,date('m')-$n,date('d'),date('Y')));

    return $month;
}
/*
*	获取指定日期上几个月的第一天和最后一天
*/
function GetPurMonth($n)
{
    $firstday = date('Y-m-01',mktime(0,0,0,date('m')-$n,date('d'),date('Y')));

    $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));

    return array($firstday,$lastday);

 }

/*
*	获取指定日期所在月的第一天和最后一天
*/
 function GetTheMonth($date)
 {//
     $firstday = date("Y-m-01",strtotime($date));

     $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));

     return array($firstday,$lastday);

 }


/*
*	获取指定日期下个月的第一天和最后一天
*/
  function GetNextMonth($date)
  {//获取指定日期下个月的第一天和最后一天
      $arr = getdate();

      if($arr['mon'] == 12){
          $year = $arr['year'] +1;
          $month = $arr['mon'] -11;
          $day = $arr['mday'];

          if($day < 10){
              $mday = '0'.$day;
          }
          else {
              $mday = $day;
          }

          $firstday = $year.'-0'.$month.'-01';
          $lastday = $year.'-0'.$month.'-'.$mday;
      }
      else{
          $time=strtotime($date);
          $firstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)+1).'-01'));
          $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
      }

      return array($firstday,$lastday);
 }

 /*
 *	获取市场该月的关注数
 */
 function GetMarketFocus($eventkey,$date,$n)
 {
     include("../mysql.php");

	 $focusdate	=GetPurMonth($n);

	 $firstday	=$focusdate[0];

     $endday=date("Y-m-d",strtotime("$focusdate[1]+ 1 day" ));

     if ($eventkey == 'all')
     {
         $adduser_query="Select count(*) From wx_user_add where  adddate>'".$firstday."' and adddate<'".$endday."'";
     }
     else
     {
         $adduser_query="Select count(*) From wx_user_add where eventkey='".$eventkey."' and adddate>='".$firstday."' and adddate<='".$endday."'";
     }

	$adduser_result=mysql_query($adduser_query,$link);

	$row_result	   =mysql_fetch_array($adduser_result);

	return $row_result[0];
 }

  /*
 *	获取市场该月的取消关注数
 */
 function GetEscFocus($eventkey,$date,$n)
 {
     include("../mysql.php");

     $focusdate	=GetPurMonth($n);

     $firstday	=$focusdate[0];

     $endday=date("Y-m-d",strtotime("$focusdate[1]+ 1 day" ));

     if ($eventkey == 'all')
     {
         $adduser_query="Select count(*) From wx_user_esc where  esc_time>'".$firstday."' and esc_time<'".$endday."'";
     }
     else
     {
         $adduser_query="Select count(*) From wx_user_esc where eventkey='".$eventkey."' and esc_time>'".$firstday."' and esc_time<'".$endday."'";
     }

     $adduser_result=mysql_query($adduser_query,$link);

     $row_result	   =mysql_fetch_array($adduser_result);

     return $row_result[0];
 }
?>
