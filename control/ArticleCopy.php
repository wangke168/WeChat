<?php
include ("mysql.php");
include ("inc/function.php");
session_start();
$id=$_GET["id"];

$row=mysql_fetch_array(mysql_query("select * from WX_article where id='".$id."'"));
if ($row)
{
	@$classid		=$row['classid'];	//菜单目录
	@$title			=$row['title'];		//标题
	@$pyq_title		=$row['pyq_title'];  //转发朋友圈标题
	@$keyword		=$row['keyword'];	//关键字回复
	@$picurl		=$row['picurl'];		//图片链接
	@$pyq_pic		=$row['pyq_pic'];		//朋友圈图片
	@$description	=$row['description'];		//副标题
	@$content		=$row['content'];	//正文内容
	@$url			=$row['url'];	//正文内容
	@$startdate		=$row['startdate'];//开始显示日期
	@$enddate		=$row['enddate'];	//下线日期
	@$Priority		=$row['Priority'];	//排序
	@$eventkey_market		=$row['eventkey'];	//是否属于市场部
	@$focus			=$row['focus'];		//是否关注显示
	@$allow_copy	=$row['allow_copy'];		//是否允许复制
	@$audit			=$row['audit'];     //是否需要审核
	@$Show_Qr		=$row['Show_Qr'];  //是否在底部显示二维码
}

?>

<?php
	$htmlData = '';
	if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
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

	<title>资料管理-修改信息</title>

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
	<script charset="utf-8" src="editor/kindeditor.js"></script>
	<script charset="utf-8" src="editor/lang/zh_CN.js"></script>

	<script type="text/javascript" src="js/zDrag.js"></script>
	<script type="text/javascript" src="js/zDialog.js"></script>

	<script language = "JavaScript">
		function Checkmarket()
		{
		  if (document.getElementById("marketid").checked ==true)
		  {
			var diag = new Dialog();
			diag.Title = "选择所属市场";
			diag.URL = "inc/markqr.php";
			diag.OKEvent = function(){
				
				$id('getval').value = diag.innerFrame.contentWindow.document.getElementById('marketid').value;
				diag.close();
				};
			diag.show();
			var doc=diag.innerFrame.contentWindow.document;
		  }
		}
		function addkeyword()
		{
			var diag = new Dialog();
			diag.Title = "选择所属市场";
			diag.URL = "inc/keywordlist.php";
			diag.OKEvent = function(){
				
				$id('keyword').value = diag.innerFrame.contentWindow.document.getElementById('keyid').value;
				diag.close();
				};
			diag.show();
			var doc=diag.innerFrame.contentWindow.document;
		}
	</script>

	<script>
	//日历
		$(function() {
			 
			$( "#startdate" ).datepicker({
				inline: true,
				showOtherMonths: true,
				minDate:0//提前15天预定；
				//maxDate:30
			});
			$( "#enddate" ).datepicker({
				inline: true,
				showOtherMonths: true,
				minDate:0//提前15天预定；
				//maxDate:30
			});
	 
		});
		</script>
		<script>
			KindEditor.ready(function(K) {
				var editor = K.editor({
						allowFileManager : true
					});
					K('#image1').click(function() {
						editor.loadPlugin('image', function() {
							editor.plugin.imageDialog({
								showRemote : false,
								imageUrl : K('#url1').val(),
								clickFn : function(url, title, width, height, border, align) {
									K('#url1').val(url);
									editor.hideDialog();
								}
							});
						});
					});
					K('#image3').click(function() {
						editor.loadPlugin('image', function() {
							editor.plugin.imageDialog({
								showRemote : false,
								imageUrl : K('#url3').val(),
								clickFn : function(url, title, width, height, border, align) {
									K('#url3').val(url);
									editor.hideDialog();
								}
							});
						});
					});
	//                window.editor = K.create('#editor_id');
				var editor1 = K.create('textarea[name="content1"]', { 
			resizeType : 1,
			allowPreviewEmoticons : false,
			allowImageUpload : true,
				allowFileManager : true,
			items : [
			'source', '|', 'undo', 'redo', '|', 'preview','cut', 'copy', 'paste',
			'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			'justifyfull', 'insertorderedlist', 'insertunorderedlist','clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
			'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
			'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|' ,'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
			'link', 'unlink', '|', 'image']
		});
				prettyPrint();
			});
		</script>

	<!--start editor-->
		<script type="text/javascript">
//上传组件
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "/control/swfupload/swfupload/swfupload.swf",
				upload_url: "/control/swfupload/upload.php",	
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 10,  
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "images/TestImageNoText_65x29.png",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">浏览</span>',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	
			};

			swfu = new SWFUpload(settings);
	     };
	</script>
	<!-- end editor-->
</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<?php	include ("inc/head.php"); ?>

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

							修改信息

						</h3>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home"></i>

								<a href="index.html">首页</a> 

								<i class="icon-angle-right"></i>

							</li>

							<li>

								<a href="#">资料管理</a>

								<i class="icon-angle-right"></i>

							</li>

							<li><a href="datalist.html">添加信息</a></li>

						</ul>

						<!-- END PAGE TITLE & BREADCRUMB-->

					</div>

				</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->          

				<div class="row-fluid">

                    <div class="span12">

						<!-- BEGIN SAMPLE TABLE PORTLET-->

						<form method="POST" name="myform" onSubmit="return CheckForm();" action="ArticleSave.php?action=add" target="_self">

						<div class="portlet box purple">

							<div class="portlet-title">

								<div class="caption"><i class="icon-comments"></i>添加信息<input name="id" type="hidden" id="id" value="<?php echo $id;?>" ></div>

								<div class="tools">

									<a href="javascript:;" class="collapse"></a>

								</div>

							</div>

							<div class="form-horizontal portlet-body form">

								<!--BEGIN TABS-->

								<div class="control-group">

									<label class="control-label">所属栏目</label>

									<div class="controls">

										

												<?php
													 if ($_SESSION['ManageLevel']==1 or $_SESSION['ManageLevel']==3)
													 {
												 ?>
												<select class="chosen span3" tabindex="-1" id="selS0V"  name='ClassID'>
												<option value=""></option>
												  <optgroup label="横店攻略">
												  <option value="2" <?php if ($classid=="2") {echo "selected";}?>>最新活动</option>
												  <option value="3" <?php if ($classid=="3") {echo "selected";}?>>玩在横店</option>
												  <option value="4" <?php if ($classid=="4") {echo "selected";}?>>秀在横店</option>
												  <option value="5" <?php if ($classid=="5") {echo "selected";}?>>住在横店</option>
												</optgroup>

												<optgroup label="我要预订">
												  <option value="7" <?php if ($classid=="7") {echo "selected";}?>>门票预订</option>
												  <option value="8" <?php if ($classid=="8") {echo "selected";}?>>门票酒店组合预订</option>
												  <option value="9" <?php if ($classid=="9") {echo "selected";}?>>酒店预订</option>
												  </optgroup>

												  <optgroup label="更多服务">
												  <option value="14" <?php if ($classid=="14") {echo "selected";}?>>景区节目时间表</option>
												  <option value="15" <?php if ($classid=="15") {echo "selected";}?>>剧组拍摄动态</option>
												  <option value="16" <?php if ($classid=="16") {echo "selected";}?>>交通速查/出租/导航</option>
												</select>
												 <?php
													 }
													 elseif ($_SESSION['ManageLevel']==2)
													 {
												?>
													<span class="help-inline">最新活动</span><input name="ClassID" type="hidden" id="ClassID" value="2">
												<?php
															 
													}
												?>

												<!--

												<optgroup label="横店攻略">

													<option>最新活动</option>

													<option>玩在横店</option>

													<option>秀在横店</option>

													<option>住在横店</option>

                                                    <option>梦幻谷夏季攻略</option>

												</optgroup>

												<optgroup label="我要预订">

													<option>门票预订</option>

													<option>门票酒店组合预订</option>

													<option>酒店预订</option>

													<option>礼品卡激活</option>

												</optgroup>

												<optgroup label="更多服务">

													<option>景区节目时间表</option>

													<option>剧组拍摄动态</option>

													<option>交通速查/出租/导航</option>

												</optgroup>

												-->

											

										

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">文章标题</label>

									<div class="controls">

										<input type="text"  class="m-wrap large" name="title"  value="<?php echo $title; ?>" />

										<span class="help-inline">信息提示</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">关键字</label>

									<div class="controls">

										<input id="keyword" name="keyword" type="text"  class="m-wrap large"  value="<?php echo $keyword;?>" />

                                        <a id="Modal1" href="#myModal1" data-toggle="modal">
                                        </a>

										<span class="help-inline">用户发送的信息中如含有以上关键字，则会自动回复本条咨询</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">索引图</label>

									<div class="controls">

										<input type="text" class="m-wrap large"  id="url1" name="url1"  value="<?php echo $picurl;?>"  />

										<input type="button" class="btn btn-success blue" id="image1" value="选择图片" /><span class="help-inline">微信推送的图，如果排序为1的建议900*500，之后的建议200*200，点此<a href="#myModal3"  data-toggle="modal">查看例子</a></span>

									</div>

								</div>

								<div class="control-group">

									<label class="control-label">朋友圈图</label>

									<div class="controls">
										<input type="text" class="m-wrap large"  id="url3" name="url3" value="<?php echo $pyq_pic;?>"/>
										<input type="button" class="btn btn-success blue" id="image3" value="选择图片" /><span class="help-inline">转发朋友圈时的小图，建议尺寸200*200，点此<a href="#myModal4"  data-toggle="modal">查看例子</a></span>

									</div>

								</div>
								<div class="control-group">

									<label class="control-label">转发朋友圈标题：</label>

									<div class="controls">

										<input type="text" class="m-wrap large" name="pyq_title" value="<?php echo $pyq_title; ?>" />

										<span class="help-inline">如果不填，则默认转发文章标题，点此<a href="#myModal4"  data-toggle="modal">查看例子</a></span>

									</div>

								</div>

								<!--  开始转发好友描述 description 

                                <div class="control-group">

									<label class="control-label">转发好友标题</label>

									<div class="controls">

										<input type="text" placeholder="请输入" class="m-wrap large" name="description"/>

										<span class="help-inline">只会出现在转发微信好友时</span>

									</div>

								</div>

								 结束转发好友描述 -->

                                <div class="control-group">

									<label class="control-label">文章内容</label>

									<div class="controls">
										<textarea name="Content" style="display:none" class="border"></textarea> 
										<textarea name="content1" style="width:400px;height:200px;visibility:hidden;"><?php echo $content;?></textarea> 
										</textarea>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">链接</label>

									<div class="controls">

										<input type="text" name="url"  class="m-wrap large" value="<?php echo $url;?>"  />

										<span class="help-inline">除非该内容是直接链接到其他网页，否则不需要填写，文章内容和链接只需要在一处填写</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">开始日期</label>

									<div class="controls">

									    <input class="m-wrap m-ctrl-medium date-picker large" name="startdate" id="startdate"  readonly size="16" type="text" data-date-format="yyyy-mm-dd"  value="<?php echo $startdate;?>"  />

										<span class="help-inline">如果不选默认马上生效</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">结束日期</label>

									<div class="controls">

										<input class="m-wrap m-ctrl-medium date-picker large" name="enddate"  id="enddate"  readonly size="16" type="text" data-date-format="yyyy-mm-dd"  value="<?php echo $enddate;?>"  />

										<span class="help-inline">如果不选默认一直有效</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">排序</label>

									<div class="controls">

                                        <select class="chosen span3" tabindex="-1" name="Priority" id="Priority">
											  <option value="0" <?php if ($Priority=="0") { echo "selected"; }?>>请选择</option>

											  <option value="1" <?php if ($Priority=="1") { echo "selected"; }?>>1</option>

											  <option value="2" <?php if ($Priority=="2") { echo "selected"; }?>>2</option>

											  <option value="3" <?php if ($Priority=="3") { echo "selected"; }?>>3</option>

											  <option value="4" <?php if ($Priority=="4") { echo "selected"; }?>>4</option>

											  <option value="5" <?php if ($Priority=="5") { echo "selected"; }?>>5</option>

											  <option value="6" <?php if ($Priority=="6") { echo "selected"; }?>>6</option>

											  <option value="7" <?php if ($Priority=="7") { echo "selected"; }?>>7</option>

											  <option value="8" <?php if ($Priority=="8") { echo "selected"; }?>>8</option>

											  <option value="9" <?php if ($Priority=="9") { echo "selected"; }?>>9</option>

                                        </select>

									</div>

								</div>

								<?php
								if ($_SESSION['ManageLevel']==2)
								{
								?>

								<div class="control-group">
                                    
                                    <label class="control-label">是否需要审核</label>

									<div class="controls">

                                        <label class="checkbox">

                                        <input type="checkbox"  name="audit" id="audit" value="yes"  <?php if ($audit=="0") {echo "checked";}?>/>

									    </label>

										<span class="help-inline">如果勾选，则需审核后发布。</span>

									</div>

								</div>

								<?php
								}	
								?>

                                <div class="control-group">
                                    
                                    <label class="control-label">是否关注显示</label>

									<div class="controls">

                                        <label class="checkbox">

                                        <input type="checkbox"  name="focus" id="focus" value="yes"  <?php if ($focus=="1") {echo "checked";}?>/>

									    </label>

										<span class="help-inline">如果勾选，则客人关注时接收到此条信息。</span>

									</div>

								</div>

								 <div class="control-group">
                                    
                                    <label class="control-label">是否显示二维码</label>

									<div class="controls">

                                        <label class="checkbox">

                                        <input type="checkbox"  name="Show_Qr" id="Show_Qr" value="yes" <?php if ($Show_Qr=="1") {echo "checked";}?> />

									    </label>

										<span class="help-inline">如果勾选，则在文章最下方显示市场的二维码。</span>

									</div>

								</div>

								 <?php
									 if  ($_SESSION['ManageLevel']==1 or $_SESSION['ManageLevel']==3)
									 {
								 ?>


								<div class="control-group">
                                    
                                    <label class="control-label">是否允许复制</label>

									<div class="controls">

                                        <label class="checkbox">

                                        <input type="checkbox"  name="allow_copy" id="allow_copy" value="yes" <?php if ($allow_copy=="1") {echo "checked";}?> />

									    </label>

										<span class="help-inline">如果勾选，则允许所有市场人员复制修改。</span>

									</div>

								</div>

                                <div class="control-group">

									<label class="control-label">是否属于市场</label>

									<div class="controls">

                                        <input id="Modal2in" name="marketid" type="text" class="m-wrap large"  value="<?php echo Market_info($eventkey_market);?>"  />

                                        <a id="Modal2" href="#myModal2" data-toggle="modal"></a>

										<span class="help-inline">如果不选，默认全部显示</span>

									</div>
								
								</div>

								  <?php
									 }
									  elseif ($_SESSION['ManageLevel']==2)
									  {
								  ?>

							

								<div class="control-group">

									<label class="control-label">是否属于市场</label>

									<div class="controls">

                                        <input type="text" name="marketid" id="marketid" value="<?php echo Market_info($_SESSION['eventkey']);?>" readonly="readonly"/>

									</div>

								</div>
								<?php
									  }	
								?>

                                <div class="form-actions">

									<button type="submit" class="btn blue"><i class="icon-ok"></i> 确认</button>

									<button type="button" class="btn" onclick="javascript:window.history.go(-1)">返回</button>

								</div>

								</form>
                                                
                                <!--弹窗start-->
                                <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

									<div class="modal-header">

										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

										<h3>关键字</h3>

									</div>

									<div class="modal-body">

										<div class="controls">

										<?php

											$result = mysql_query("SELECT * from wx_request_keyword order by id asc",$link);

											while($row = mysql_fetch_array($result))
											{
												echo "<label class='checkbox line'>";

												echo "<input name=\"keyword1\" type=\"checkbox\" value=\"".$row["Keyword"]."\">".$row["Keyword"];

												echo "</label>";

											}

										?>

											<!--例子

											<label class="checkbox line">

											<input type="checkbox" value="" /> 报名

											</label>

											-->

										</div>

									</div>

									<div class="modal-footer">

										<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

										<button class="btn blue" data-dismiss="modal" onclick="javascript:modal1()" >确认</button>

									</div>

								</div>

                                <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

									<div class="modal-header">

										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

										<h3>所属市场</h3>

									</div>

									<div class="modal-body">

										<div class="controls">

											<label class="radio line">

											<input type="radio" name="radiome" value="" checked /> 全部

											</label>

										<?php
											$result = mysql_query("SELECT * from wx_qrscene_info where parent_id ='' order by id asc",$link);

											while($row = mysql_fetch_array($result))
											{
												echo "<label class=\"radio line\">";

											//	echo "<input name=\"Qrscene_id\" type=\"radio\" id=\"Qrscene_id\" onClick=\"getRadioValue();\" value=\"".$row["Qrscene_Name"]."\">".$row["Qrscene_Name"]."<br>";

												echo "<input type=\"radio\" name=\"radiome\" value=\"\" />".$row["Qrscene_Name"];

												echo "</label>";

											}
										
										?>
			
											
											<!-- 例子

											<label class="radio line">

											<input type="radio" name="radiome" value="" /> 上海市场部

											</label>

											 -->

										</div>

									</div>

									<div class="modal-footer">

										<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

										<button class="btn blue" data-dismiss="modal" onclick="javascript:modal2()" >确认</button>

									</div>

								</div>

								 <div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

									<div class="modal-header">

										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

										<h3>索引图例子</h3>

									</div>

									<div class="modal-body">

										<div class="controls">

                                            <label class="radio line">

											<img src="images/eg1.jpg">

											</label>
										</div>

									</div>
									<div class="modal-footer">

										<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

									</div>
									</div>

									 <div id="myModal4" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

									<div class="modal-header">

										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

										<h3>朋友圈例子</h3>

									</div>

									<div class="modal-body">

										<div class="controls">

                                            <label class="radio line">

											<img src="images/eg2.jpg">

											</label>
										</div>

									</div>

									<div class="modal-footer">

										<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>

									</div>

								</div>
                                <!--弹窗end-->

								<!--END TABS-->

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

	        $("#keyword").click(function () {
	            $("#Modal1").click();
	        })
	        $("#Modal2in").click(function () {
	            $("#Modal2").click();
	        })
			$("#Modal3in").click(function () {
	            $("#Modal3").click();
	        })
			$("#Modal4in").click(function () {
	            $("#Modal4").click();
	        })

	    });

	    function modal1() {
	        $("#keyword").val("");
	        for (var i = 0; i < $("#myModal1 .modal-body").find(":checkbox").length;i++) {
	            var icheckbox = $("#myModal1 .modal-body").find(":checkbox").eq(i);
	            if (icheckbox.is(":checked")) {
	                $("#keyword").val($("#keyword").val() + icheckbox.closest("label").text().trim() + ",");
	            }  
	        }
	    }
	    function modal2() {
	        $("#Modal2in").val($("#myModal2 .modal-body").find(":radio:checked").closest("label").text().trim());
	    }
		function modal3() {
	        $("#Modal3in").val($("#myModal3 .modal-body").find(":radio:checked").closest("label").text().trim());
	    }
		function modal4() {
	        $("#Modal4in").val($("#myModal4 .modal-body").find(":radio:checked").closest("label").text().trim());
	    }
	</script>

	<!-- END JAVASCRIPTS -->

<script type="text/javascript">  var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-37564768-1']); _gaq.push(['_setDomainName', 'keenthemes.com']); _gaq.push(['_setAllowLinker', true]); _gaq.push(['_trackPageview']); (function () { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();</script></body>

<!-- END BODY -->

</html>