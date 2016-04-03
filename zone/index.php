<?php
require_once("../classes/jssdk.class.php");
//require_once("../inc/function.php");
$fn=$_GET["wxnumber"];
if ($fn=="")
{
    $showinfo="false";
}
else{
    $showinfo="true";
}
$project_id=$_GET["p_id"];
$jssdk=new JSSDK("wx3e632d57ac5dcc68", "5bc0ddd4d88d904c9b24131fa9227f81");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

    <title>龙帝惊临取号测试</title>
    <link href="css/index.css" rel="stylesheet" />

    <script src="js/jquery-2.0.3.min.js"></script>

    <script>
        var qhterm = <?php echo $showinfo;?>;//是否满足取号条件 false不满足,true满足

        //页面加载后即开始第一次定位
        $(function () {
            if (qhterm) {   //满足取号条件,开始定位
                gpsdw();
            }
            else {    //不满足取号条件
                $(".overdiv").show(1)
                    .find(".closebtn").hide(1)
                    .nextAll("span").html("请扫描龙帝惊临二维码后重新取号").css({ "margin-top": "30px" });
            }
        })
	
	//定位
        function gpsdw() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showposition, showerror, {
                    // 指示浏览器获取高精度的位置，默认为false
                    enableHighAccuracy: true,
                    // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
                    timeout: 5000,
                    // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
                    maximumAge: 3000
                });
            } else {
                alert("非常抱歉,您的浏览器不支持定位功能");
            }
        }

	//输出位置坐标
        function showposition(position) {
            var weidu = position.coords.latitude;//维度
            var jingdu = position.coords.longitude;//经度
            if (weidu > 29.1347 && weidu < 29.1400 && jingdu > 120.3055 && jingdu < 120.3119) {
                $(".info").html("您所在位置:秦王宫");
            }
            /*影视城位置以下可注释*/
            /*else if (weidu > 29.154 && weidu < 29.1549 && jingdu > 120.312 && jingdu < 120.313) {
                $(".info").html("您所在位置:横店影视城有限公司");
            }*/
            /*影视城位置以上可注释*/
            else {
                $(".info").html("您不在秦王宫范围");
            }
        }
        //位置读取错误时
        function showerror(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("您拒绝了定位申请,请重试");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("无法获取到地理位置");
                    break;
                case error.TIMEOUT:
                    alert("请求超时,请重试");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("出现未知原因");
                    break;
            }
            $(".info").html("出现错误,请按提示解决");
        }
	
	
        /*取号*/
        function getqh() {
            if ($(".info").text() != "您所在位置:秦王宫") {
                $(".overdiv").show(1)
                    .find(".closebtn").show(1)
                    .nextAll("span").html("您好，只有在秦王宫才能预约,如果您确认在景区请点击点位按钮重新获取您的位置。");
            } else {
                $.get('test.php?p_id=<?php echo $project_id?>&fn=<?php echo $fn?>', function (data) {
                    var content=data;
                    $(".overdiv").show(1)
                            .find(".closebtn").hide(1)
                            .nextAll("span").html(content).css({ "margin-top": "30px" });
                });
            }
        }
        /*关闭按钮*/
        function closeoverdiv() {
            $(".overdiv").hide(1);
        }
    </script>

</head>
<body>
    <div id="page">
        <a class="quhaobtn" href="javascript:getqh()">
            点击取号
        </a>
        <div class="dwlabel">
            <div class="info">
                定位中...
            </div>
            <a class="btn" href="javascript:gpsdw()">
                <i class="gpsico"></i>
                定位
            </a>
        </div>
    </div> 
    <div class="overdiv" style="display:none;">
        <div class="tootip">
            <a class="closebtn" href="javascript:closeoverdiv()">
                +
            </a>
            <span>
                提示区文字
            </span>
        </div>
    </div>
</body>
</html>
<script language="JavaScript" >
    function get_wait() {
        $.get("test.php?p_id=<?php echo $project_id?>&fn=<?php echo $fn?>", function (data) {
        });
    }
</script>


<?php
$pyq_title="秦王宫龙帝惊临智能排队系统";
$imgUrl="http://weix.hengdianworld.com/control/editor/attached/image/20160324/20160324130222_32090.jpg";
$url="http://weix2.hengdianworld.com/server/wechat/zone/index.php?p_id=1";
?>

<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">

    wx.config({
//        debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage']
        // 所有要调用的 API 都要加到这个列表中

    });
    wx.ready(function () {
        // 在这里调用 API
        wx.onMenuShareTimeline({
            title: '<?php echo $pyq_title;?>', // 分享标题
            link: '<?php echo $url;?>', // 分享链接
            imgUrl: '<?php echo $imgUrl?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                $.get("../inc/datasync.php?active=addresp&id=11", function(data){

                });

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $pyq_title;?>', // 分享标题
            desc: '<?php echo $description;?>', // 分享描述
            link: '<?php echo $url;?>', // 分享链接
            imgUrl: '<?php echo $imgUrl?>', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
                $.get("../inc/datasync.php?active=addrespf&id=11", function(data){

                });
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

    });

</script>
