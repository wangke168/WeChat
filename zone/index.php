<?php

$fn=$_GET["wxnumber"];
$project_id=$_GET["p_id"];

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
        /*假的定位*/
        function gpsdw() {
            if ($(".info").text() == "您未在秦王宫范围内") {
                $(".info").html("您所在位置:秦王宫");
            } else {
                $(".info").html("您未在秦王宫范围内");
            }
        }
        /*取号*/
        function getqh() {
            if ($(".info").text() == "您未在秦王宫范围内") {
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
                您所在位置:秦王宫
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
                预约完成,请根据微信提示排队等候
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