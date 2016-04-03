<?php
//session_start();
//@$user_eventket = $_SESSION['eventkey'];
//echo $user_eventket;
include ("mysql.php");
include ("inc/function.php");
@$classid=$_GET['classid'];
@$keyword=$_GET['keyword'];
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

	<title>资料管理</title>

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

	<?php include ("inc/head.php"); ?>

	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->

	<div class="page-container">

		<!-- BEGIN SIDEBAR 菜单-->
		<?php
			include ("inc/menu.php");
		?>
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

							信息列表

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

							<li><a href="datalist.html">信息列表</a></li>

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

										<?php
			
											if ($_SESSION['ManageLevel']==1 or $_SESSION['ManageLevel']==3)
											{
										?>	

											<ul class="nav nav-tabs">

										<!--	<li><a href="articlelist.php">全部</a></li> -->
												
												<li><a href="articlelist.php?classid=2">最新活动</a></li>

												<li><a href="articlelist.php?classid=3">玩在横店</a></li>

                                                <li><a href="articlelist.php?classid=4">秀在横店</a></li>

                                                <li><a href="articlelist.php?classid=5">住在横店</a></li>

												 <li><a href="articlelist.php?classid=7">门票预订</a></li>

												  <li><a href="articlelist.php?classid=8">门票酒店组合预订</a></li>

												   <li><a href="articlelist.php?classid=9">酒店预订</a></li>

												    <li><a href="articlelist.php?classid=14">景区节目时间表</a></li>

													 <li><a href="articlelist.php?classid=15">剧组拍摄动态</a></li>

													 <li><a href="articlelist.php?classid=16">交通速查/出租/导航</a></li>

													 <li><a href="articlelist.php?classid=97">其他</a></li>

												<li><a href="articlelist.php?classid=audit">待审核</a></li>

												<li><a href="articlelist.php?classid=1">市场部</a></li>
												
											</ul>
										<?php
											 }
											else
											{
										?>
											<ul class="nav nav-tabs">

													<li><a href="articlelist.php?classid=98">全部</a></li>
													
												<!--	<li><a href="articlelist.php?classid=99">可复制</a></li>	-->
												
												</ul>
										<?php
											}				
										?>
											<div class="tab-content">

												<div class="tab-pane active" id="tab_1_1">
                                                    <div class="btn-group">
                                                        <a href="ArticleAdd.php" class="btn blue">
                                                            添加
                                                            <i class="icon-plus-sign"></i>
                                                        </a>
                                                    </div>
													<!--
                                                    <div class="btn-group">
                                                        <a href="#" class="btn red">
                                                            删除
                                                            <i class="icon-remove-sign"></i>
                                                        </a> 
                                                    </div>

													-->
													
                                                    <!--搜索框-->

                                                    <div class="input-append">
														<form method="GET" name="myform"  action="ArticleSearch.php">
															<input class="m-wrap" type="text" name="keyword" id="keyword" value="<?php echo $keyword?>" /><button class="btn green" type="submit">搜索</button>
														</form>	
												    </div>

                                                    <!--搜索框-->
                                                    <!--表-->

            <?php

			if ($classid!="")
				{
					if($classid=="1")
					{
						$query="SELECT count(*) FROM wx_article where eventkey like '%".$user_eventket."%' and eventkey<>'all' and title like '%".$keyword."%'";

					//	echo $query1;
					}
					elseif($classid=="2")
					{
						$query="SELECT count(*) FROM wx_article where classid like '%".$classid."%'  and   title like '%".$keyword."%' and eventkey='all'";
					}
					elseif($classid=="97")
					{
						$query="SELECT count(*) FROM wx_article where classid='' and eventkey like '%".$user_eventket."%' and   title like '%".$keyword."%' and eventkey='all'";
					}
					elseif($classid=="audit")
					{
						$query="SELECT count(*) FROM wx_article where eventkey like '%".$user_eventket."%' and title like '%".$keyword."%'  and audit='0'";
					}
					elseif($classid=="98")
					{
						$query="SELECT count(*) FROM wx_article where classid ='2' and eventkey = '".$user_eventket."' and title like '%".$keyword."%'";
					}
					else
					{
						$query="SELECT count(*) FROM wx_article where classid like '%".$classid."%' and eventkey like '%".$user_eventket."%' and title like '%".$keyword."%'";
					}
				}
				else
				{
                 //   echo $user_eventket;
					$query="SELECT count(*) FROM wx_article where eventkey = '".$user_eventket."'  and title like '%".$keyword."%'";
				}










 //           $query="SELECT count(*) FROM wx_article where eventkey like '%".$user_eventket."%' and title like '%".$keyword."%' and  classid like '%".$classid."%'";



            $rs = mysql_query($query);
            $myrows=mysql_fetch_array($rs);
            $numrows=$myrows[0];
            //设定每一页显示的记录数
            $pagesize = 15;
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
													<table class="table table-bordered table-hover">

									                    <thead>

										                    <tr>

											                    <th style="width:20px">
                                                                    <input type="checkbox" id="zcheck" value="" />
											                    </th>

											                    <th>编号</th>

											                    <th>标题</th>

                                                                <th>排序</th>

                                                                <th>展示对象</th>

                                                                <th>是否过期</th>
																<th>复制</th>

                                                                <th>点击</th>

                                                                <th>不重复点击</th>

                                                                <th>转发</th>

                                                                <th>状态</th>

                                                                <th>操作</th>

                                                                <th>上线时间</th>

										                    </tr>

									                    </thead>

									                    <tbody>

               <?php
				if ($classid!="")
				{
					if($classid=="1")
					{
						$query1="SELECT * FROM wx_article where eventkey like '%".$user_eventket."%' and eventkey<>'all' and title like '%".$keyword."%'  order by  id desc limit ".$offset.",".$pagesize;

					//	echo $query1;
					}
					elseif($classid=="2")
					{
						$query1="SELECT * FROM wx_article where classid like '%".$classid."%' and eventkey like '%".$user_eventket."%' and   title like '%".$keyword."%' and eventkey='all'  order by  id desc limit ".$offset.",".$pagesize;
					}
					elseif($classid=="97")
					{
						$query1="SELECT * FROM wx_article where classid='' and eventkey like '%".$user_eventket."%' and   title like '%".$keyword."%' and eventkey='all'  order by  id desc limit ".$offset.",".$pagesize;
					}
					elseif($classid=="audit")
					{
						$query1="SELECT * FROM wx_article where eventkey like '%".$user_eventket."%' and title like '%".$keyword."%'  and audit='0'  order by  id desc limit ".$offset.",".$pagesize;
					}
					elseif($classid=="98")
					{
						$query1="SELECT * FROM wx_article where classid ='2' and eventkey = '".$user_eventket."' and title like '%".$keyword."%' order by  id desc limit ".$offset.",".$pagesize;
					}
					else
					{
						$query1="SELECT * FROM wx_article where classid like '%".$classid."%' and eventkey like '%".$user_eventket."%' and title like '%".$keyword."%' order by  id desc limit ".$offset.",".$pagesize;
					}
				}
				else
				{
					$query1="SELECT * FROM wx_article where eventkey = '".$user_eventket."'  and title like '%".$keyword."%'   order by  id desc limit ".$offset.",".$pagesize;
				}

	//			echo $query1;
                $result = mysql_query($query1,$link);
                //   {
                $i = 0;
                while($row=mysql_fetch_array($result))
                {
                    $i++;
                    echo "<tr>";
					echo "<td><input class='fetablecheckbox' type='checkbox' value='' /></td>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['title']."</td>";
                    echo "<td>".$row['Priority']."</td>";
                    echo "<td>".Market_info($row['eventkey'])."</td>";
	//				echo "<td>".$row['online']."</td>";
					if ($row['online']==1)
					{
						echo "<td>显示</td>";
					}
					else
					{
						echo "<td><font color=red>不显示</font></td>";
					}
					if ($row['allow_copy']==1)
					{
						echo "<td><span class='label label-success'>允许</span></td>";
					}
					else
					{
						echo "<td><span class='label label-warning'>不允许</span></td>";
					}
					echo "<td>".$row['hits']."</td>";
					echo "<td>".article_ip_hits($row['id'])."</td>";
					echo "<td>".$row['resp']."</td>";
					if($row['audit']==0)
					{
						echo "<td><a href='articlesave.php?action=audit&id=".$row['id']."'><span class='label label-warning'>待审核</span></a></td>";	
					}
					else
					{
						echo "<td><span class='label label-success'>已审核</span></td>";	
					}
					echo "<td>";
	//				echo "<a href='ArticleCopy.php?id=".$row['id']."'>复制</a>&nbsp;&nbsp;";
                    echo "<a href='ArticleModify.php?id=".$row['id']."'>修改</a>&nbsp;&nbsp;";
					echo "<a OnClick=\"javascript:if (!confirm('是否真的要删除'))return false;\"  href='articlesave.php?action=del&id=".$row['id']."'\">删除</a>&nbsp;&nbsp;";
					if ($row['online']==1)
					{
						echo "<a OnClick=\"javascript:if (!confirm('是否真的要下线'))return false;\"  href='articlesave.php?action=offline&id=".$row['id']."'\"><font color='red'>下线</font></a>";
					}
					elseif ($row['online']==0)
					{
						echo "<a OnClick=\"javascript:if (!confirm('是否真的要上线'))return false;\"  href='articlesave.php?action=online&id=".$row['id']."'\">上线</a>";

					}
					echo "</td>";
                    echo "<td>".$row['adddate']."</td>";
                    echo "</tr>";

                }
?>
<!--															
															<tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-success">已审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-down"></i>
                                                                        下线
                                                                    </a> 
                                                                </td>

										                    </tr>

                                                            <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-warning">未审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-up"></i>
                                                                        上线
                                                                    </a> 
                                                                </td>

										                    </tr>

                                                            <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-success">已审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-down"></i>
                                                                        下线
                                                                    </a> 
                                                                </td>

										                    </tr>

                                                            <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-warning">未审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-up"></i>
                                                                        上线
                                                                    </a> 
                                                                </td>

										                    </tr>

                                                            <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-success">已审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-down"></i>
                                                                        下线
                                                                    </a> 
                                                                </td>

										                    </tr>

                                                            <tr>

											                    <td>
                                                                    <input class="fetablecheckbox" type="checkbox" value="" />
											                    </td>

											                    <td>1</td>

											                    <td>
                                                                    明清民居博览城七夕特惠门票
											                    </td>

											                    <td></td>

                                                                <td></td>
                                                                <td>门票</td>
                                                                <td>1</td>
                                                                <td>全部</td>
                                                                <td>1</td>
                                                                <td>2500</td>
                                                                <td>3000</td>
                                                                <td>200</td>

											                    <td><span class="label label-warning">未审核</span></td>

                                                                <td style="width:190px;">
                                                                    <a href="#" class="btn mini blue">
                                                                        <i class="icon-edit"></i>
                                                                        编辑
                                                                    </a>
                                                                    <a href="#" class="btn mini red">
                                                                        <i class="icon-remove"></i>
                                                                        删除
                                                                    </a> 
                                                                    <a href="#" class="btn mini green">
                                                                        <i class="icon-arrow-up"></i>
                                                                        上线
                                                                    </a> 
                                                                </td>

										                    </tr>
-->
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
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$first."&keyword=".$keyword."'>首页</a></li>";
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$prev."&keyword=".$keyword."'>上一页</a></li>";
                }

//                echo " 共有" .$pages. "页(" .$page. "/" .$pages.")";
                for($i=1; $i<$page; $i++)
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$i."&keyword=".$keyword."'>".$i."</a></li>";
                echo "<li class='active'><a href='#'>" .$page. "</a></li>";
                for($i=$page+1; $i<=$pages;$i++)
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$i."&keyword=".$keyword."'>".$i."</a></li>";
                if($page<$pages)
                {
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$next."&keyword=".$keyword."'>下一页</a></li>";
                    echo "<li><a href='ArticleList.php?classid=".$classid."&page=".$last."&keyword=".$keyword."'>最后一页</a></li>";
                }
				echo "</ul>";
                echo"</div>";
                ?>


                                                   

									              <!--   

										                    <li class="active"><a href="#">«</a></li>

										                    <li class="active"><a href="#">1</a></li>

										                    <li><a href="#">2</a></li>

										                    <li><a href="#">3</a></li>

										                    <li><a href="#">4</a></li>

										                    <li><a href="#">»</a></li>

									                    </ul>

								                    </div>
													-->
                                                    <!--页码-->

												</div>

											</div>

										</div>

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