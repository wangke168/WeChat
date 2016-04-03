<?php
include ("mysql.php");
include ("inc/function.php");
//session_start();
//echo $_SESSION['ManageLevel'];
//$_SESSION['UserLevel']=1;
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

	<title>人员管理</title>

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

	<link href="media/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/daterangepicker.css" rel="stylesheet" type="text/css" />

	<link href="media/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

	<link href="media/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>

	<link href="media/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

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

		<?php	include ("inc/menu.php");?>

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

							人员列表

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.html">首页</a> 

								<i class="icon-angle-right"></i>

							</li>

							<li>

								<a href="userlist.html">人员管理</a>

							</li>

						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">

                    <div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-comments"></i>信息列表</div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="portlet-body">

                                <div class="btn-group">
                                    <a class="btn blue" href="#useradd" data-toggle="modal">
                                        添加
                                        <i class="icon-plus-sign"></i>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="#" class="btn red">
                                        删除
                                        <i class="icon-remove-sign"></i>
                                    </a> 
                                </div>
                                <!--搜索框-->
                                <div class="input-append">

									<input class="m-wrap" type="text" /><button class="btn green" type="button">搜索</button>

								</div>
                                <!--搜索框-->
                                                    <!--表-->
                                                        
													<table class="table table-bordered table-hover">

									                    <thead>

										                    <tr>

											                    <th style="width:20px">
                                                                    <input type="checkbox" id="zcheck" value="" />
											                    </th>

											                    <th>姓名</th>

											                    <th>用户名</th>

											                    <th>所属市场</th>

                                                                <th style="width:190px;">
                                                                    操作
                                                                </th>

										                    </tr>

									                    </thead>

									                    <tbody>

														<?php 
															$result = mysql_query("SELECT * from userlist  order by id desc",$link);
															while($row = mysql_fetch_array($result))
													{
											//			echo "<option value=".$row["qrscene_id"].">".$row["qrscene_name"]."</option>";
														echo "<tr> ";
														echo "<td><input class=\"fetablecheckbox\" type=\"checkbox\" value=\"\" ></td>";
														echo  "<td >".$row["UserName"]."</td>";
														echo  "<td >".$row["UserName"]."</td>";
														echo  "<td >".Market_info($row["eventkey"])."</td>";
														echo  "<td ><a href=\"UserList.php?action=Modify&id=".$row["id"]."\">修改</a>&nbsp;&nbsp;<a OnClick=\"javascript:if (!confirm('是否真的要删除？'))return false;\" href=\"UserList.php?action=Del&id=".$row["id"]."\">删除</a>&nbsp;&nbsp;<a OnClick=\"javascript:if (!confirm('是否真的要锁定'))return false;\"  href=\"UserList.php?action=Lock&id".$row["id"]."\">锁定</a></td>";
														echo "<tr>";
													//	echo "<input name=\"Qrscene_id\" type=\"radio\" id=\"Qrscene_id\" onClick=\"getRadioValue();\" value=\"".$row["Qrscene_Name"]."\">".$row["Qrscene_Name"]."<br>";
													}
											?>

											<!--

										                    <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>
                                                                    蒋晓祥
											                    </td>

											                    <td>
                                                                    蒋晓祥
											                    </td>

											                    <td>
                                                                    宣城市场
											                    </td>

                                                                <td style="width:190px;">
                                                                    <a class="btn mini blue" href="#useredit" data-toggle="modal">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-lock"></i>
                                                                        锁定
                                                                    </a> 
                                                                </td>

										                    </tr>

															-->

									                    </tbody>

								                    </table>

                                                    <!--表-->

                                                    <!--页码-->
                                                    <div class="pagination pull-right" style="margin:0;">

									                    <ul>

										                    <li class="active"><a href="#">«</a></li>

										                    <li class="active"><a href="#">1</a></li>

										                    <li><a href="#">2</a></li>

										                    <li><a href="#">3</a></li>

										                    <li><a href="#">4</a></li>

										                    <li><a href="#">»</a></li>

									                    </ul>

								                    </div>
                                                    <!--页码-->

                                <div class="clearfix"></div>

							</div>

						</div>

						<!-- END SAMPLE TABLE PORTLET-->

					</div>

				</div>

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

    <!--begin弹窗-->
    <!--添加-->
    <div id="useradd" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

			<h3>添加人员</h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<label class="control-label">联系人</label>

				<div class="controls">

					<input type="text" placeholder="请输入" class="m-wrap large" />

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="control-group">

				<label class="control-label">用户名</label>

				<div class="controls">

					<input type="text" placeholder="请输入" class="m-wrap large" />

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="control-group">

				<label class="control-label">所属市场</label>

				<div class="controls">

					<select class="chosen span4" tabindex="2">
                        <option>请选择</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                    </select>

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="clearfix"></div>

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

			<button class="btn blue" >确认</button>

		</div>

	</div>

    <!--人员修改-->
    <div id="useredit" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

			<h3>人员修改</h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<label class="control-label">联系人</label>

				<div class="controls">

					<input type="text" placeholder="请输入" class="m-wrap large" />

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="control-group">

				<label class="control-label">用户名</label>

				<div class="controls">

					<input type="text" placeholder="请输入" class="m-wrap large" />

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="control-group">

				<label class="control-label">所属市场</label>

				<div class="controls">

					<select class="chosen span4" tabindex="2">
                        <option>请选择</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                    </select>

					<span class="help-inline">信息提示</span>

				</div>

			</div>

            <div class="clearfix"></div>

		</div>

		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

			<button class="btn blue" >确认</button>

		</div>

	</div>
    <!--end弹窗-->

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

	<script src="media/js/jquery.vmap.js" type="text/javascript"></script>   

	<script src="media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  

	<script src="media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="media/js/date.js" type="text/javascript"></script>

	<script src="media/js/daterangepicker.js" type="text/javascript"></script>     

	<script src="media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="media/js/jquery.sparkline.min.js" type="text/javascript"></script>   

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="media/js/app.js" type="text/javascript"></script>

	<script src="media/js/index.js" type="text/javascript"></script>    

	<!-- END PAGE LEVEL SCRIPTS -->  
    <!--isme-->
    <script src="media/js/Ccreate.js"></script>
    <!--isme-->
	<script>

	    jQuery(document).ready(function () {

	        App.init(); // initlayout and core plugins

	        tabelqx();

	        menuchushi();

	        $(document).ready(function () {
	            var urlstr = window.location.toString();
	            var nava = $(".nav-tabs a");
	            var b = false;
	            for (var i = 0; i < nava.length; i++) {
	                if (urlstr.indexOf(nava.eq(i).attr("href").toString()) >= 0) {
	                    nava.eq(i).closest("li").addClass("active");
	                    b = true;
	                    break;
	                }
	            }
	            if (!b) {
	                nava.eq(0).closest("li").addClass("active");
	            }
	        })
	    });

	</script>

	<!-- END JAVASCRIPTS -->

<script type="text/javascript">  var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-37564768-1']); _gaq.push(['_setDomainName', 'keenthemes.com']); _gaq.push(['_setAllowLinker', true]); _gaq.push(['_trackPageview']); (function () { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();</script></body>

<!-- END BODY -->

</html>

<?php
	@$action=$_GET["action"];
	if ($action=='adduser')
	{
		$LinkMan=$_POST["LinkMan"];
		$UserName=$_POST["UserName"];
		$market=$_POST["MarkID"];
		Create_User($LinkMan,$UserName,$market);
	}
	if ($action=='edit')
	{
		$id=$_POST["id"];
		$LinkMan=$_POST["LinkMan"];
		$UserName=$_POST["UserName"];
		$market=$_POST["MarkID"];
		Edit_User($id,$LinkMan,$UserName,$market);
	}
	if ($action=='Del')
	{
		$id=$_GET["id"];
		Del_User($id);
	}
/*
	if action="" Then
		If ManageLevel=1 Then
			Call Main1()
		Else 
			Call Main2()
		End If
	end if
	if action="Modify" Then
		If ManageLevel=1 Then
			Call Modify1()
		Else
			Call Modify2()
		End If
	end If
	If action="adduser" Then
		Call SaveAdd()
	End If
	If action="edit" Then
		Call SaveModify()
	End If
	If action="Del" Then
		Call Del()
	End If 
	If action="Lock" Then
		Call Lock()
	End If
	If action="Unlock" Then
		Call Unlock()
	End If
	*/
?>
