<?php
//session_start();
@$eventkey = $_SESSION['eventkey'];
//@$user_name = $_SESSION['UserName'];
include ("mysql.php");
include ("inc/function.php");
@$action=$_GET['action'];

if ($action=='save')
{
	$Qrscene_Name			= $_POST['Qrscene_Name'];
	$Qrscene_Person_Name	= $_POST['Qrscene_Person_Name'];
	$Qrscene_Person_Phone	= $_POST['Qrscene_Person_Phone'];
	@$Qrscene_UID			= $_POST['Qrscene_UID'];
    $ClassID	            = $_POST['ClassID'];
	Create_Qrscene_id($eventkey,$Qrscene_Name,$Qrscene_Person_Name,$Qrscene_Person_Phone,$ClassID,$Qrscene_UID);
	echo "<script Language=Javascript>alert('添加成功，可生成二维码！^_^');</script>";
	echo "<script>window.location =\"qr_list.php?Qrscene_id=".$eventkey."\";</script>";
}

if ($action=='savemodify')
{
	$Qrscene_Name			= $_POST['Qrscene_Name'];
	$Qrscene_Person_Name	= $_POST['Qrscene_Person_Name'];
	$Qrscene_Person_Phone	= $_POST['Qrscene_Person_Phone'];
	@$Qrscene_UID			= $_POST['Qrscene_UID'];
	$Qrscene_id				= $_GET['Qrscene_id'];
    $ClassID	            = $_POST['ClassID'];
//	Create_Qrscene_id($eventkey,$Qrscene_Name,$Qrscene_Person_Name,$Qrscene_Person_Phone,$Qrscene_UID);
	
	mysql_query("update wx_qrscene_info set Qrscene_Name='".$Qrscene_Name."',Qrscene_Person_Name='".$Qrscene_Person_Name."',Qrscene_Person_Phone='".$Qrscene_Person_Phone."',uid='".$Qrscene_UID."',ClassID='".$ClassID."' where Qrscene_id='".$Qrscene_id."'")or die(mysql_error());


	echo "<script Language=Javascript>alert('修改成功，可生成二维码！^_^');</script>";
	echo "<script>window.location =\"qr_list.php?Qrscene_id=".$eventkey."\";</script>";
}

?>

<!DOCTYPE html>

<!-- 

Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 2.3.1

Version: 1.3

Author: KeenThemes

Website: http://www.keenthemes.com/preview/?theme=metronic

Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469

-->

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>市场管理-添加市场</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />

	<!-- BEGIN GLOBAL MANDATORY STYLES -->

	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-metro.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/style-responsive.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

	<link href="media/css/uniform.default.css" rel="stylesheet" type="text/css"/>

	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->

	<link rel="stylesheet" type="text/css" href="media/css/bootstrap-fileupload.css" />

	<link rel="stylesheet" type="text/css" href="media/css/jquery.gritter.css" />

	<link rel="stylesheet" type="text/css" href="media/css/chosen.css" />

	<link rel="stylesheet" type="text/css" href="media/css/select2_metro.css" />

	<link rel="stylesheet" type="text/css" href="media/css/jquery.tagsinput.css" />

	<link rel="stylesheet" type="text/css" href="media/css/clockface.css" />

	<link rel="stylesheet" type="text/css" href="media/css/bootstrap-wysihtml5.css" />

	<link rel="stylesheet" type="text/css" href="media/css/datepicker.css" />

	<link rel="stylesheet" type="text/css" href="media/css/timepicker.css" />

	<link rel="stylesheet" type="text/css" href="media/css/colorpicker.css" />

	<link rel="stylesheet" type="text/css" href="media/css/bootstrap-toggle-buttons.css" />

	<link rel="stylesheet" type="text/css" href="media/css/daterangepicker.css" />

	<link rel="stylesheet" type="text/css" href="media/css/datetimepicker.css" />

	<link rel="stylesheet" type="text/css" href="media/css/multi-select-metro.css" />

	<link href="media/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

	<!-- END PAGE LEVEL STYLES -->

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<?php include ("inc/head.php"); ?>

	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->

	<div class="page-container">

		<!-- BEGIN SIDEBAR 菜单-->

		<?php	include ("inc/menu.php"); ?>

		<!-- END SIDEBAR -->

		<!-- BEGIN PAGE -->

		<div class="page-content">

			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

			<div id="portlet-config" class="modal hide">

				<div class="modal-header">

					<button data-dismiss="modal" class="close" type="button"></button>

					<h3>Widget Settings</h3>

				</div>

				<div class="modal-body">

					Widget settings form goes here

				</div>

			</div>

			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

			<!--页面内容start -->

            <div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->

						<h3 class="page-title">

							添加市场

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.html">首页</a> 

								<i class="icon-angle-right"></i>

							</li>

							<li>

								<a href="#">市场管理</a>

								<i class="icon-angle-right"></i>

							</li>

							<li><a href="Marketadd.html">添加市场</a></li>

						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<?php
					if ($action=='modify')
					{
						$Qrscene_id=$_GET['Qrscene_id'];
						$row=mysql_fetch_array(mysql_query("select * from wx_qrscene_info where Qrscene_id='".$Qrscene_id."'",$link));
				?>

				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">

                    <div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-comments"></i>修改信息</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="form-horizontal portlet-body form">

							<form METHOD="post" Action="CreateMarket.php?action=savemodify&Qrscene_id=<?php echo $Qrscene_id;?>" name="Form1" onSubmit="return CheckValue(this);"  style="margin:0px;">  


								<!--BEGIN TABS-->

                                <div class="control-group">

									<label class="control-label">名称</label>

									<div class="controls">

										<input type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Name" value=<?php echo $row['Qrscene_Name'];?> />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">联系人</label>

									<div class="controls">

										<input id="Modal1in" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Person_Name" value=<?php echo $row['Qrscene_Person_Name'];?> >

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">联系电话</label>

									<div class="controls">

										<input id="Text1" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Person_Phone" value=<?php echo $row['Qrscene_Person_Phone'];?> >

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">UID</label>

									<div class="controls">

										<input id="Text2" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_UID" value=<?php echo $row['uid'];?> >

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

                                    <label class="control-label">类型</label>

                                    <div class="controls">

                                        <select class="chosen span3" tabindex="-1" id="selS0V"  name='ClassID'>

                                            <option value="1" <?php if ($row['ClassID']=="1") {echo "selected";}?> >市场</option>

                                            <option value="2" <?php if ($row['ClassID']=="2") {echo "selected";}?> >代理商</option>

                                            <option value="3" <?php if ($row['ClassID']=="3") {echo "selected";}?> >推广联盟</option>

                                            <option value="4" <?php if ($row['ClassID']=="4") {echo "selected";}?> >公司自用</option>

                                        </select>

                                        <span class="help-inline">信息提示</span>

                                    </div>

                                </div>

                                <div class="form-actions">

									<button type="submit" class="btn blue"><i class="icon-ok"></i> 确认</button>

									<button type="button" class="btn" onclick="javascript:window.history.go(-1)">返回</button>

								</div>

								<!--END TABS-->

								</form>

							</div>

						</div>

						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>

				<?php

					}

					if ($action=='add')
					{

				?>

				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">

                    <div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-comments"></i>添加信息</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="form-horizontal portlet-body form">

							<form METHOD="post" Action="CreateMarket.php?action=save" name="Form1" onSubmit="return CheckValue(this);"  style="margin:0px;">  


								<!--BEGIN TABS-->

                                <div class="control-group">

									<label class="control-label">名称</label>

									<div class="controls">

										<input type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Name" />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">联系人</label>

									<div class="controls">

										<input id="Modal1in" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Person_Name" />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">联系电话</label>

									<div class="controls">

										<input id="Text1" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_Person_Phone" />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">UID</label>

									<div class="controls">

										<input id="Text2" type="text" placeholder="请输入" class="m-wrap large" name="Qrscene_UID" />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

                                    <label class="control-label">类型</label>

                                    <div class="controls">

                                        <select class="chosen span3" tabindex="-1" id="selS0V"  name='ClassID'>

													<option value="1">市场</option>

													<option value="2">代理商</option>

													<option value="3">推广联盟</option>

													<option value="4">公司自用</option>

                                        </select>

                                        <span class="help-inline">信息提示</span>

                                    </div>

                                </div>

                                <div class="form-actions">

									<button type="submit" class="btn blue"><i class="icon-ok"></i> 确认</button>

									<button type="button" class="btn" onclick="javascript:window.history.go(-1)">返回</button>

								</div>

								<!--END TABS-->

								</form>

							</div>

						</div>

						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>
				<?php
					}	
				?>

				<!-- END PAGE CONTENT-->

			</div>

			<!--页面内容end-->    

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-tools">

			<span class="go-top">

			<i class="icon-angle-up"></i>

			</span>

		</div>

	</div>

	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

	<script src="media/js/jquery-1.10.1.min.js" type="text/javascript"></script>

	<script src="media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      

	<script src="media/js/bootstrap.min.js" type="text/javascript"></script>

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>  

	<![endif]-->   

	<script src="media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.blockui.min.js" type="text/javascript"></script>  

	<script src="media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script type="text/javascript" src="media/js/ckeditor.js"></script>  

	<script type="text/javascript" src="media/js/bootstrap-fileupload.js"></script>

	<script type="text/javascript" src="media/js/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="media/js/select2.min.js"></script>

	<script type="text/javascript" src="media/js/wysihtml5-0.3.0.js"></script> 

	<script type="text/javascript" src="media/js/bootstrap-wysihtml5.js"></script>

	<script type="text/javascript" src="media/js/jquery.tagsinput.min.js"></script>

	<script type="text/javascript" src="media/js/jquery.toggle.buttons.js"></script>

	<script type="text/javascript" src="media/js/bootstrap-datepicker.js"></script>

	<script type="text/javascript" src="media/js/bootstrap-datetimepicker.js"></script>

	<script type="text/javascript" src="media/js/clockface.js"></script>

	<script type="text/javascript" src="media/js/date.js"></script>

	<script type="text/javascript" src="media/js/daterangepicker.js"></script> 

	<script type="text/javascript" src="media/js/bootstrap-colorpicker.js"></script>  

	<script type="text/javascript" src="media/js/bootstrap-timepicker.js"></script>

	<script type="text/javascript" src="media/js/jquery.inputmask.bundle.min.js"></script>   

	<script type="text/javascript" src="media/js/jquery.input-ip-address-control-1.0.min.js"></script>

	<script type="text/javascript" src="media/js/jquery.multi-select.js"></script>   

	<script src="media/js/bootstrap-modal.js" type="text/javascript" ></script>

	<script src="media/js/bootstrap-modalmanager.js" type="text/javascript" ></script> 

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js"></script>

	<script src="media/js/form-components.js"></script>     

	<!-- END PAGE LEVEL SCRIPTS -->
    <!--isme-->
    <script src="media/js/Ccreate.js"></script>
    <!--isme-->
	<script>

	    jQuery(document).ready(function () {

	        App.init(); // initlayout and core plugins

	        menuchushi();

	        FormComponents.init();

	    });

	</script>

	<!-- END JAVASCRIPTS -->

<script type="text/javascript">  var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-37564768-1']); _gaq.push(['_setDomainName', 'keenthemes.com']); _gaq.push(['_setAllowLinker', true]); _gaq.push(['_trackPageview']); (function () { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();</script></body>

<!-- END BODY -->

</html>