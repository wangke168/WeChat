<?php
require("../classes/DB.class.php");
require("../classes/response.class.php");
require("function.php");
/*
*  预订成功后从官网接受信息，反馈给客人微信
*/




$sellid = $_GET['sellid'];
//	$sellid="V1409140222";
//	$fromUsername = "o2e-YuBgnbLLgJGMQykhSg_V3VRI";
$fromUsername = $_GET['openid'];

$db = new DB();
//查询是否已经发送过信息，避免二次发送

if (check_order($sellid)) {
    $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
    $row = $db->query("INSERT INTO WX_Order_Send (WX_OpenID,SellID,eventkey) VALUES (:fromUsername,:sellid,:eventkey)", array("fromUsername" => $fromUsername,"sellid" => $sellid,"eventkey" => $eventkey));
    if (!check_order($sellid)) {
        $ACCESS_TOKEN = get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $ACCESS_TOKEN;
        vpost($url, Repost_order($sellid, $fromUsername));
    }
}


function check_order($sellid)
{
    $db = new DB();
    $row = $db->query("SELECT count(*) as allcount from WX_Order_Send where SellID = :SellID", array("SellID" => $sellid));

    if ($row[0]["allcount"] == 0) {
        $flag = true;
    } else {
        $flag = false;
    }
    return $flag;
}

function Repost_order($sellid, $fromUsername)
{
//    $url = "http://e.hengdianworld.com/searchorder_json.aspx?sellid=" . $sellid;
    $json = file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?sellid=" . $sellid);
    $data = json_decode($json, true);


    $ticketcount = count($data['ticketorder']);
    $inclusivecount = count($data['inclusiveorder']);
    $hotelcount = count($data['hotelorder']);

    $i = 0;
    if ($ticketcount <> 0) {
        for ($j = 0; $j < $ticketcount; $j++) {
            $i = $i + 1;

            $first = $data['ticketorder'][$j]['name'] . "，您好，您已经成功预订门票。\\n";
            $sellid = $data['ticketorder'][$j]['sellid'];
            $date = $data['ticketorder'][$j]['date2'];
            $ticket = $data['ticketorder'][$j]['ticket'];
            $numbers = $data['ticketorder'][$j]['numbers'];
//          $ticketorder=$data['ticketorder'][$j]['code'];

            if ($data['ticketorder'][$j]['ticket'] == '三大点+梦幻谷' || $data['ticketorder'][$j]['ticket'] == '网络联票+梦幻谷') {
                $ticketorder = "注意：该票种需要身份证检票";
            } else {
                $ticketorder = $data['ticketorder'][$j]['code'];
            }


            $remark = "\\n在检票口出示此识别码可直接进入景区。\\n如有疑问，请致电4009999141。";


            $xjson = "{
	\"touser\":\"" . $fromUsername . "\",
	\"template_id\":\"GO-MX6boWkOW_0RPdX_p1L5roVshgyHxttG4Ruw_GqM\",
	\"url\":\"http://weix.hengdianworld.com/article/articledetail.php?id=44\",
	\"topcolor\":\"#FF0000\",
	\"data\":{
	\"first\": {
	\"value\":\"" . $first . "\",
	\"color\":\"#000000\"
	},
	\"keyword1\": {
	\"value\":\"" . $sellid . "\",
	\"color\":\"#173177\"
	},
	\"keyword2\":{
	\"value\":\"" . $date . "\",
	\"color\":\"#173177\"
	},
	\"keyword3\":{
	\"value\":\"" . $ticket . "\",
	\"color\":\"#173177\"
	},
	\"keyword4\":{
	\"value\":\"" . $numbers . "\",
	\"color\":\"#173177\"
	},
	\"keyword5\":{
	\"value\":\"" . $ticketorder . "\",
	\"color\":\"#173177\"
	},
	\"remark\":{
	\"value\":\"" . $remark . "\",
	\"color\":\"#000000\"
	}
	}
}";
        }
    }
    if ($inclusivecount <> 0) {
        for ($j = 0; $j < $inclusivecount; $j++) {
            $i = $i + 1;
            $first = $data['inclusiveorder'][$j]['name'] . "，您好，您已经成功预订组合套餐。\\n";
            $sellid = $data['inclusiveorder'][$j]['sellid'];
            $name = $data['inclusiveorder'][$j]['name'];
            $date = $data['inclusiveorder'][$j]['date2'];
            $ticket = $data['inclusiveorder'][$j]['ticket'];
            $hotel = $data['inclusiveorder'][$j]['hotel'];

            $remark = "人数：" . $data['inclusiveorder'][$j]['numbers'] . "\\n\\n预达日凭身份证到酒店前台取票。如有疑问，请致电4009999141。";


            $xjson = "{
		\"touser\":\"" . $fromUsername . "\",
		\"template_id\":\"6_xcQ3_C7ypfMkuU2YPZo_gxx7XyQC99Sn9gkBomFpI\",
		\"url\":\"http://weix.hengdianworld.com/article/articledetail.php?id=44\",
		\"topcolor\":\"#FF0000\",
		\"data\":{
		\"first\": {
		\"value\":\"" . $first . "\",
		\"color\":\"#000000\"
		},
		\"keyword1\": {
		\"value\":\"" . $sellid . "\",
		\"color\":\"#173177\"
		},
		\"keyword2\":{
		\"value\":\"" . $name . "\",
		\"color\":\"#173177\"
		},
		\"keyword3\":{
		\"value\":\"" . $date . "\",
		\"color\":\"#173177\"
		},
		\"keyword4\":{
		\"value\":\"" . $ticket . "\",
		\"color\":\"#173177\"
		},
		\"keyword5\":{
		\"value\":\"" . $hotel . "\",
		\"color\":\"#173177\"
		},
		\"remark\":{
		\"value\":\"" . $remark . "\",
		\"color\":\"#000000\"
		}
		}
	}";
        }
    }
    if ($hotelcount <> 0) {
        for ($j = 0; $j < $hotelcount; $j++) {
            $i = $i + 1;
            $first = "        " . $data['hotelorder'][$j]['name'] . "，您好，您已经成功预订" . $data['hotelorder'][$j]['hotel'] . "，酒店所有工作人员静候您的光临。\\n";
            $sellid = $data['hotelorder'][$j]['sellid'];
            $date = $data['hotelorder'][$j]['date2'];
            $days = $data['hotelorder'][$j]['days'];
            $hotel = $data['hotelorder'][$j]['hotel'];
            $numbers = $data['hotelorder'][$j]['numbers'];
            $roomtype = $data['hotelorder'][$j]['roomtype'];
            $remark = "\\n        预达日凭身份证到酒店前台办理入住办手续。\\n如有疑问，请致电4009999141。";
            $xjson = "{
		\"touser\":\"" . $fromUsername . "\",
		\"template_id\":\"KEoAPCC2TM5A7D7Va8-LbwJCZ6qrTPuxYcge0If5sMI\",
		\"url\":\"http://weix.hengdianworld.com/article/articledetail.php?id=44\",
		\"topcolor\":\"#FF0000\",
		\"data\":{
		\"first\": {
		\"value\":\"" . $first . "\",
		\"color\":\"#000000\"
		},
		\"keyword1\": {
		\"value\":\"" . $sellid . "\",
		\"color\":\"#173177\"
		},
		\"keyword2\":{
		\"value\":\"" . $date . "\",
		\"color\":\"#173177\"
		},
		\"keyword3\":{
		\"value\":\"" . $days . "\",
		\"color\":\"#173177\"
		},
		\"keyword4\":{
		\"value\":\"" . $roomtype . "\",
		\"color\":\"#173177\"
		},
		\"keyword5\":{
		\"value\":\"" . $numbers . "\",
		\"color\":\"#173177\"
		},
		\"remark\":{
		\"value\":\"" . $remark . "\",
		\"color\":\"#000000\"
		}
		}
	}";

        }
    }
    return $xjson;

}