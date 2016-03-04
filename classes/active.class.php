<?php
/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/2/25
 * Time: 10:03
 */


class active {

    public function text_taizhou($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $keyword = trim($postObj->Content);
        $responseMsg = new responseMsg();
        $handle = new handle();
        @$eventkey = return_user_info($fromUsername, "eventkey");
        if (!empty($keyword)) {
            if ($eventkey == '105') {
                $temp_array = array();
                $temp_array = str_split($keyword, 30);
                if ($temp_array[0] == '中国好闺蜜免费游横店') {
                    if (check_word($temp_array[1]) == 2) {
//                        $responseMsg->responseV_Text($fromUsername, $temp_array[1]);
                        $this->insert_active_tel($fromUsername, $eventkey, $temp_array[1], '82');

                    } else {
                        $responseMsg->responseV_Text($fromUsername, "您的手机号或格式有误，请检查后重新报名，正确格式如下，\n中国好闺蜜免费游横店136XXXXXXXX");
                    }
                } else {
                    $handle->handleText($postObj);
                }
            } else {
                $handle->handleText($postObj);
            }
        }
    }

    private function insert_active_tel($fromUsername, $eventkey, $tel, $classid)
    {
//        include (mysql.php);
        $db = new DB();
        $responseMsg = new responseMsg();
        $row = $db->query("select * from wx_activity_market where classid=:classid and (wx_openid=:fromUsername or tel=:tel )", array("classid" => $classid, "fromUsername" => $fromUsername, "tel" => $tel));
        /*
        $row = mysql_fetch_array(mysql_query("select * from wx_activity_market where classid='" . $classid . "' and (wx_openid='" . $fromUsername . "' or tel='" . $tel . "'"));
        $responseMsg->responseV_Text($fromUsername, "select * from wx_activity_market where classid='" . $classid . "' and (wx_openid='" . $fromUsername . "' or tel='" . $tel . "'");
//        if ($row) {
//            $responseMsg->responseV_Text($fromUsername, "同一个微信或手机号只能报名一次，请检查后重新报名");
//        } else {
//            mysql_query("INSERT INTO wx_activity_market (classid,tel,eventkey,wx_openid) VALUES ('" . $classid . "','" . $tel . "','" . $eventkey . "','" . $fromUsername . "')") or die(mysql_error());
//            $responseMsg->responseV_Text($fromUsername, "报名成功");
//        }


    }
    */
        if ($row) {
            $responseMsg->responseV_Text($fromUsername, "同一个微信或手机号只能报名一次，请检查后重新报名");
        } else {
            $db->query("INSERT INTO wx_activity_market (classid,tel,eventkey,wx_openid) VALUES (:classid,:tel,:eventkey,:fromUsername)",array("classid" => $classid,"tel" => $tel,"eventkey"=>$eventkey,"fromUsername"=>$fromUsername));
//            mysql_query("INSERT INTO wx_activity_market (classid,tel,eventkey,wx_openid) VALUES ('" . $classid . "','" . $tel . "','" . $eventkey . "','" . $fromUsername . "')") or die(mysql_error());
            $responseMsg->responseV_Text($fromUsername, "报名成功");
        }
    }
} 