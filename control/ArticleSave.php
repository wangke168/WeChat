<?php
session_start();
include ("mysql.php");
include ("inc/function.php");
@$action=$_GET["action"];


	@$classid=$_POST['ClassID'];	//菜单目录
	@$title=$_POST['title'];		//标题
	@$keyword=$_POST['keyword'];	//关键字回复

	
	@$picurl=$_POST['url1'];		//图片链接
	@$pyq_pic=$_POST['url3'];		//转发朋友圈图片


	@$pyq_title=$_POST['pyq_title'];		//转发朋友圈标题
	@$description=$_POST['description'];		//副标题
	@$content=$_POST['content1'];	//正文内容
	$content = str_replace('\'','\'\'',$content);
	@$url=$_POST['url'];	//正文内容
	@$startdate=$_POST['startdate'];//开始显示日期
	if ($startdate=='')
	{
		$startdate=date('Y-m-d');
	}
	@$enddate=$_POST['enddate'];	//下线日期

//	echo $enddate;

	if ($enddate=='')
	{
		$enddate="2099-12-12";
	}
	@$Priority=$_POST['Priority'];	//排序

	@$marketid=$_POST['marketid'];
	if ($marketid=="全部")
	{
		@$eventkey = "all";
	}
	else
	{
		@$eventkey=Qrscene_info($marketid);
	}



	@$focus=$_POST['focus'];		//是否关注显示
	if ($focus=="yes")
	{
		$focus = "1";
	}
	else
	{
		$focus = "0";
	}


	@$allow_copy=$_POST['allow_copy'];		//是否关注显示

	if ($allow_copy=="yes")
	{
		$allow_copy = "1";
	}
	else
	{
		$allow_copy = "0";
	}

	@$Show_Qr=$_POST['Show_Qr'];		//是否在尾部显示二维码
	if ($Show_Qr=="yes")
	{
		$Show_Qr = "1";
	}
	else
	{
		$Show_Qr = "0";
	}
	

	if ($_SESSION['ManageLevel']=="1" or $_SESSION['ManageLevel']=="3" )
	{
		$audit="1";     //是否需要审核
	}
	elseif ($_SESSION['ManageLevel']=="2")
	{
		@$audit=$_POST['audit'];	//关键字回复
		if ($audit=="yes")
		{
			$audit = "0";
		}
		else
		{
			$audit = "1";
		}

	}

	@$adddate=date('Y-m-d');
	/*
	echo $title."<br>";
	echo $keyword."<br>";
	echo $picurl."<br>";
	echo $content."<br>";
	echo $startdate."<br>";
	echo $enddate."<br>";
	echo $Priority."<br>";
	echo $eventkey."<br>";
	echo $audit;
	*/
	//


if ($action=="add")
{
	mysql_query("INSERT INTO wx_article (classid,title,pyq_title,description,content,url,picurl,pyq_pic,focus,allow_copy,audit,keyword,Priority,eventkey,startdate,enddate,adddate,Show_Qr) VALUES ('".$classid."','".$title."','".$pyq_title."','".$description."','".$content."','".$url."','".$picurl."','".$pyq_pic."','".$focus."','".$allow_copy."','".$audit."','".$keyword."','".$Priority."','".$eventkey."','".$startdate."','".$enddate."','".$adddate."','".$Show_Qr."')") or die(mysql_error());

	echo "<script>window.location =\"ArticleList.php\";</script>";

}
elseif ($action=="modify")
{
	
	$id=$_POST["id"];
	mysql_query("update wx_article set classid='".$classid."',title='".$title."',pyq_title='".$pyq_title."',description='".$description."',content='".$content."',url='".$url."',picurl='".$picurl."',pyq_pic='".$pyq_pic."',focus='".$focus."',audit='".$audit."',keyword='".$keyword."',Priority='".$Priority."',eventkey='".$eventkey."',startdate='".$startdate."',enddate='".$enddate."',adddate='".$adddate."',allow_copy='".$allow_copy."',Show_Qr='".$Show_Qr."' where id='".$id."'") or die(mysql_error());  
	
//	echo "update wx_article set classid='".$classid."',title='".$title."',pyq_title='".$pyq_title."',description='".$description."',content='".$content."',url='".$url."',picurl='".$picurl."',pyq_pic='".$pyq_pic."',focus='".$focus."',audit='".$audit."',keyword='".$keyword."',Priority='".$Priority."',eventkey='".$eventkey."',startdate='".$startdate."',enddate='".$enddate."',adddate='".$adddate."',allow_copy='".$allow_copy."',Show_Qr='".$Show_Qr."' where id='".$id."'";

	echo "<script>window.location =\"ArticleList.php\";</script>";
}
elseif ($action=="audit")
{
	$id=$_GET["id"];
	mysql_query("update wx_article set audit='".$audit."' where id='".$id."'") or die(mysql_error());  
	echo "<script>window.location =\"ArticleList.php\";</script>";
}
elseif ($action=="offline")
{
	$id=$_GET["id"];
	mysql_query("update wx_article set online=0 where id='".$id."'") or die(mysql_error());
	echo "<script>window.location =\"ArticleList.php\";</script>";
}
elseif ($action=="online")
{
	$id=$_GET["id"];
	mysql_query("update wx_article set online=1 where id='".$id."'") or die(mysql_error());  
	echo "<script>window.location =\"ArticleList.php\";</script>";
}
elseif ($action=="del")
{
	$id=$_GET["id"];
	mysql_query("delete from wx_article where id='".$id."'") or die(mysql_error());  
	echo "<script>window.location =\"ArticleList.php\";</script>";
}
?>