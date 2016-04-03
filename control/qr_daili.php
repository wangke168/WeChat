<?php
//session_start();
include ("mysql.php");
include ("inc/function.php");
//$activeid=$_GET['activeid'];
@$qr_id=$_GET['Qrscene_id'];
@$keyword=$_GET['keyword'];

if ($qr_id=='')
{
    $Markinfo="各市场";
}
else
{
    $Markinfo=Market_info($qr_id);
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

	<title>市场管理</title>

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

	<!-- END PAGE LEVEL STYLES -->

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

							市场列表

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

							<li><a href="Marketlist.html">市场列表</a></li>

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

								
									<!--BEGIN TABS-->

										<div class="tabbable tabbable-custom">

											<div class="tab-content">

												<div class="tab-pane active" id="tab_1_1">


                                <div class="btn-group">
                                                        <a href="#" class="btn blue">
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

                                                        <form method="GET" name="myform"  action="qr_daili.php">

                                                            <input class="m-wrap" type="text" name="keyword" /><button class="btn green" type="button">搜索</button>

                                                        </form>


												    </div>
                                                    <!--搜索框-->
												   <?php

                                                       $query="SELECT count(*) FROM WX_Qrscene_Info WHERE classid='2' and qrscene_name like '%".$keyword."%'";

														
														$rs = mysql_query($query);
														$myrows=mysql_fetch_array($rs);
														$numrows=$myrows[0];
														//设定每一页显示的记录数
														$pagesize = 20;
														//计算总页数
														$pages=intval($numrows/$pagesize);

														if($numrows%$pagesize)
															$pages++;
														//设置页数

														if(isset($_GET["page"]))
														{
															$page=intval($_GET["page"]);

														}
														else{
															$page = 1; //没有页数则显示第一页；
														}
														//计算记录偏移量
														$offset = ($page-1)*$pagesize;
														//读取指定的记录数

													?> 

                                                    <!--表-->
                                                        
													<table class="table table-bordered table-hover">

									                    <thead>

										                    <tr>

											                    <th style="width:20px">
                                                                    <input type="checkbox" id="zcheck" value="" />
											                    </th>

											                    <th>序号</th>

											                    <th>市场部</th>

											                    <th>关注人数</th>

											                    <th>订单数</th>

                                                                <th>获取二维码</th>

                                                                <th style="width:135px">
                                                                    操作
                                                                </th>

										                    </tr>

									                    </thead>

									                    <tbody>

														<?php

															$url="SELECT * FROM WX_Qrscene_Info WHERE ClassID='2' and qrscene_name like '%".$keyword."%' order by id asc limit ".$offset.",".$pagesize;
															
															$result=mysql_query($url,$link);

															$i=0;

															while($row=mysql_fetch_array($result))

															{
																echo "<tr>";
																echo "<td>  <input class=\"fetablecheckbox\" type=\"checkbox\" value=\"\" /></td>";
																echo "<td>".$row['Qrscene_id']."</td>";
																echo "<td><a href='qr_market.php?Qrscene_id=".$row['Qrscene_id']."'>".$row['Qrscene_Name']."</a></td>";
																echo "<td>".qr_info_count($row['Qrscene_id'],"count","1")."</td>";
																echo "<td>".qr_info_count($row['Qrscene_id'],"countorder","2")."</td>";
																echo "<td><a href='http://weix2.hengdianworld.com/server/wechat/control/qr_create.php?Qrscene_id=".$row['Qrscene_id']."' target='_blank'>点击获取</a></td>";
																echo "<td><a href='CreateMarket.php?action=modify&Qrscene_id=".$row['Qrscene_id']."' target='_blank'>修改</a></td>";
																echo "</tr>";
															}
															
														?>


									                    </tbody>

								                    </table>

                                                    <!--表-->

                                                    <!--页码-->
													<?php
														$first = 1;
														$prev =$page-1;
														$next = $page+1;
														$last=$pages;
														echo " <div class='pagination pull-right' style='margin:0;'>";
														echo "<ul>";

														if($page>1)
														{
															echo "<li><a href='qr_daili.php?page=".$first."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>首页</a></li>";
															echo "<li><a href='qr_daili.php?page=".$prev."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>上一页</a></li>";
														}

										//                echo " 共有" .$pages. "页(" .$page. "/" .$pages.")";
														for($i=1; $i<$page; $i++)
															echo "<li><a href='qr_daili.php?page=".$i."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>".$i."</a></li>";
														echo "<li class='active'><a href='#'>" .$page. "</a></li>";
														for($i=$page+1; $i<=$pages;$i++)
															echo "<li><a href='qr_daili.php?page=".$i."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>".$i."</a></li>";
														if($page<$pages)
														{
															echo "<li><a href='qr_daili.php?page=".$next."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>下一页</a></li>";
															echo "<li><a href='qr_daili.php?page=".$last."&Qrscene_id=".$qr_id."&keyword=".$keyword."'>最后一页</a></li>";
														}
														echo "</ul>";
														echo"</div>";
													?>
                                                    <!--页码-->
													</div>

											</div>

										</div>

										<!--END TABS-->

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