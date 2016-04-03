		
		<div class="page-sidebar nav-collapse collapse">
			<!-- 左侧菜单-->        
			<ul class="page-sidebar-menu">

				<li>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

					<div class="sidebar-toggler hidden-phone"></div>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

				</li>

				<li>

					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->

					<form class="sidebar-search">

						<div class="input-box">

							<a href="javascript:;" class="remove"></a>

							<input type="text" placeholder="搜索文字..." />

							<input type="button" class="submit" value=" " />

						</div>

					</form>

					<!-- END RESPONSIVE QUICK SEARCH FORM -->

				</li>

				<li class="start ">

					<a href="index.php">

					<i class="icon-home"></i> 

					<span class="title">首页</span>

					<span class="selected"></span>

					</a>

				</li>

				<?php
				if(in_array("1", $array_Permission))
				{
				?>

				<li>

					<a href="javascript:;">

					<i class="icon-file"></i> 

					<span class="title">资料管理</span>

					<span class="arrow "></span>

					</a>

					<ul class="sub-menu">

						<li class="">

							<a href="articlelist.php">
                                信息列表
							</a>

						</li>

                        <li >

							<a href="ArticleAdd.php">
                                添加信息
							</a>

						</li>
                        <li>

                            <a href="keyword_request.php">
                                关键字回复
                            </a>

                        </li>
				<!--
                        <li >

							<a href="dataguide.html">
                                添加指南
							</a>

						</li>
				-->
					</ul>

				</li>

<!------------------------------------------------>


				<?php

				}	

				elseif (in_array("2", $array_Permission))
				
				{

				?>

				<li class="">

					<a href="articlelist.php?classid=98">

					<i class="icon-lock"></i> 

					<span class="title">信息管理</span>

					</a>

				</li>
                <?php

                }

                if (in_array("6", $array_Permission))

                {

                    ?>

				<li class="">

					<a href="javascript:;">

					<i class="icon-cogs"></i> 

					<span class="title">市场信息</span>

					<span class="arrow "></span>

					</a>

					<ul class="sub-menu">

						<li >

							<a href="qr_market.php?Qrscene_id=<?php echo $eventkey; ?>">
                                市场信息
							</a>

						</li>
						<li >

							<a href="qr_market_count.php?Qrscene_id=<?php echo $eventkey; ?>">
                                月关注清单
							</a>

						</li>

					</ul>

				</li>


				<?php
				
				}

				if (in_array("3", $array_Permission))

				{

				?>

				<li class="">

					<a href="javascript:;">

					<i class="icon-cogs"></i> 

					<span class="title">市场信息</span>

					<span class="arrow "></span>

					</a>

					<ul class="sub-menu">

						<li >

							<a href="qr_list.php">
                                市场信息
							</a>

						</li>
						<li >

							<a href="qr_esc_list.php">
                                市场取消信息
							</a>

						</li>

					</ul>

				</li>

				<?php
				
				}

				if (in_array("4", $array_Permission))

				{

				?>

				<li class="">

					<a href="javascript:;">

					<i class="icon-cogs"></i> 

					<span class="title">二维码管理</span>

					<span class="arrow "></span>

					</a>

					<ul class="sub-menu">

						<li >

							<a href="qr_list.php">
                                市场信息
							</a>

						</li>
                        <li >

                            <a href="qr_daili.php">
                                代理商
                            </a>

                        </li>
                        <li >

                            <a href="qr_tglm.php">
                                推广联盟
                            </a>

                        </li>
                        <li >

                            <a href="qr_other.php">
                                其他
                            </a>

                        </li>
						<li >

							<a href="CreateMarket.php?action=add">
                                登记二维码
							</a>

						</li>

					</ul>

				</li>

                    <li class="">

                        <a href="javascript:;">

                            <i class="icon-cogs"></i>

                            <span class="title">订单信息</span>

                            <span class="arrow "></span>

                        </a>

                        <ul class="sub-menu">

                            <li >

                                <a href="order_info.php">
                                    未支付订单
                                </a>

                            </li>

                        </ul>

                    </li>


				<li class="">

					<a href="userlist.php">

					<i class="icon-user-md"></i> 

					<span class="title">人员管理</span>

					</a>

				</li>
				<?php
				
				}

				if (in_array("5", $array_Permission))

				{

				?>
				<li class="">

					<a href="activelist.php">

					<i class="icon-user-md"></i> 

					<span class="title">奖券管理</span>

					</a>

				</li>
				<?php

				}

                if (in_array("7", $array_Permission))

                {

                    ?>
                    <li class="">

                        <a href="articlelist_market.php">

                            <i class="icon-user-md"></i>

                            <span class="title">信息列表</span>

                        </a>

                    </li>
                <?php

                }

                ?>


                <li class="">

					<a href="ChangePWD.php">

					<i class="icon-lock"></i> 

					<span class="title">密码修改</span>

					</a>

				</li>

                <li class="last">

					<a href="javascript:window.location='logout.php';">

					<i class="icon-key"></i> 

					<span class="title">退出系统</span>

					<span class=""></span>

					</a>

				</li>
		

			</ul>
						<!-- 左侧菜单 -->

		</div>