<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/2/24
 * Time: 14:16
 */
class responseMsg
{
    public function responseV_News($fromUsername, $keyword, $type)
    {
        include "mysql.php";
        switch ($type) {
            case "1":
                if (!$keyword || $keyword == "") {
                    $eventkey = "all";
                } else {
                    $eventkey = $keyword;
                }
                break;
            case "2":
                $uid = query_qr_uid(return_user_info($fromUsername, "eventkey"));  //获取客人uid
                $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
                break;
        }

        if (!$uid) {
            $uid = "";
        }

        switch ($type) {
            case "1":
                /* 根据用户的openid所属eventkey下有没有关注显示的文章 */
                if ($this->Query_Market_Article($eventkey, 1)) {
                    $result = mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and   eventkey='" . $eventkey . "'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,8", $link);
                } else {
                    $result = mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and  eventkey='all'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,8", $link);
                }
                break;
            case "2":
                $result = mysql_query("SELECT * from wx_article where msgtype='news' and (classid = '" . $keyword . "' or keyword like '%" . $keyword . "%') and audit=1 and online=1 and  (eventkey='all' or eventkey='" . $eventkey . "')  and startdate<=CURDATE() and enddate>=CURDATE()   order by priority asc,id desc  LIMIT 0,8", $link);
                break;
        }
        //   $result = mysql_query("SELECT * from wx_article where msgtype='news' and (classid = '" . $keyword . "' or keyword like '%" . $keyword . "%') and audit=1 and online=1 and  (eventkey='all' or eventkey='".$eventkey."')  and startdate<=CURDATE() and enddate>=CURDATE()   order by priority asc,id desc  LIMIT 0,8",$link);
        $i = 0;
        $content = array();
        while ($row = mysql_fetch_array($result)) {

            $url = $row['url'];

            /*如果只直接跳转链接页面时，判断是否已经带参数*/
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    $url = $url . "&wxnumber=" . $fromUsername . "&uid=" . $uid . "&wpay=1";
                } else {
                    $url = $url . "?wxnumber=" . $fromUsername . "&uid=" . $uid . "&wpay=1";
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $row['id'] . "&wxnumber=" . $fromUsername;
            }

            @$PicUrl = check_picurl($row['picurl']);
            @$PicUrl_Small = $row['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }
            }
            $i = $i + 1;

            $content[] = array("Title" => "" . $row['title'] . "", "Description" => "" . $row['title'] . "", "Url" => "" . $url . "", "PicUrl" => "" . $PicUrl . "");
        }

        $result_xjson = $this->item_news($fromUsername, $content);
        $ACCESS_TOKEN = get_access_token();
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $ACCESS_TOKEN;
        vpost($url_post, $result_xjson);
    }

    /*
     * 客服接口的json模版
     */
    private function item_news($fromUsername, $newsArray)
    {

        $itemTpl = "{
             \"title\":\"%s\",
             \"description\":\"%s\",
             \"url\":\"%s\",
             \"picurl\":\"%s\"
         },";
        $item_str = "";
        foreach ($newsArray as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['Url'], $item['PicUrl']);
        }

        $xjson = "{
    \"touser\":\"" . $fromUsername . "\",
    \"msgtype\":\"news\",
    \"news\":{
        \"articles\": [
            $item_str
         ]
         }
    }";
        return $xjson;
    }

    /******
     * 被动返回文本信息
     */
    public function responseV_Text($FromUserName, $content)
    {
        $ACCESS_TOKEN = get_access_token();

        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $ACCESS_TOKEN;
        $xjson = "{
    \"touser\":\"" . $FromUserName . "\",
    \"msgtype\":\"text\",
    \"text\":
    {
         \"content\":\"" . $content . "\"
    },
     \"customservice\":
    {
         \"kf_account\":\"1001@u_hengdian\"
    }
  }";
        vpost($url, $xjson);
    }


    /*******************
     * 返回文本信息
     *******************/

    public function responseText($FromUserName, $ToUserName, $content)
    {
        $textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
        $resultStr = sprintf($textTpl, $FromUserName, $ToUserName, time(), $content);
        return $resultStr;
    }


    /***************************************
     *
     * 关键字回复
     ******************************************/
    public function request_keyword($fromUsername, $keyword)
    {
        include "mysql.php";
        $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
        $keyword = check_in($keyword);
        //查询是否有符合的记录
        $itemsCount1 = mysql_query("SELECT id from wx_article where audit=1 and online='1'  and (eventkey='all' or eventkey='" . $eventkey . "') and (classid = '" . $keyword . "' or keyword like '%" . $keyword . "%') and startdate<=CURDATE() and enddate>=CURDATE() order by id desc LIMIT 0,1", $link);
        $row1 = mysql_fetch_array($itemsCount1);
        if ($row1) {
            $this->responseV_News($fromUsername, $keyword, "2");

        } else {
            $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
            $this->responseV_Text($fromUsername, $contentStr);
        }

    }


    /***************************************
     *
     * 目录回复
     ******************************************/
    public function request_menu($fromUsername, $toUsername, $menu)
    {
        include "mysql.php";
        $eventkey = return_eventkey_info(return_user_info($fromUsername, "eventkey"));  //获取客人所属市场
        if (check_order_number($eventkey) == true) {
            $uid = query_qr_uid(return_user_info($fromUsername, "eventkey"));
        } else {
            $uid = "";
        }


//	查询优惠码，在套餐预订时嵌入
        @$coupon_code = '';
        if ($menu == "8") {
            $result = mysql_query("select * from wx_coupon_code where wx_openid='" . $fromUsername . "'");
            $row = mysql_fetch_array($result);
            if ($row) {
                $coupon_code = $row['coupon_code'];
            }
            if (!$eventkey) {
                $row = mysql_fetch_array(mysql_query("SELECT * from wx_user_info where wx_openID='" . $fromUsername . "' order by id desc  LIMIT 0,1", $link));
                if (strtotime($row["AddDate"]) > strtotime("2016-02-10 21:07:00")) {
                    // $uid = "627A7778313233";
                }
            }

        }
        if ($eventkey) {
            if (!$uid) {
                //		$uid="627A7778313233";
                $uid = "";
            }
        }

        if (!$uid) {
            //	$uid="627A7778313233";
            $uid = "";
        }
        $CreateTime = time();
        $FuncFlag = 1;
        $newTplHeader = "<xml>
           <ToUserName><![CDATA[%s]]></ToUserName>
           <FromUserName><![CDATA[%s]]></FromUserName>
           <CreateTime>%s</CreateTime>
           <MsgType><![CDATA[%s]]></MsgType>
           <ArticleCount>%s</ArticleCount>
           <Articles>";
        $newTplItem = "<item>
                 <Title><![CDATA[%s]]></Title>
                 <Description><![CDATA[%s]]></Description>
                 <PicUrl><![CDATA[%s]]></PicUrl>
                 <Url><![CDATA[%s]]></Url>
                 </item>";
        $newTplFoot = "</Articles>
                 <FuncFlag>%s</FuncFlag>
                 </xml>";

        //如果有图文资料时，输出图文
        $Content = '';
        $result = mysql_query("SELECT * from wx_article where msgtype='news' and (classid = '" . $menu . "' or keyword like '%" . $menu . "%') and audit=1 and online=1 and  (eventkey='all' or eventkey='" . $eventkey . "')  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link);
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            $url = $row['url'];
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    //	$url=$url."&shop_eventkey=".return_user_info($fromUsername,"eventkey")."&wxnumber=".$fromUsername."&coupon=".$coupon_code."&market_eventkey=".$eventkey."&uid=".$uid;
                    $url = $url . "&shop_eventkey=" . return_user_info($fromUsername, "eventkey") . "&wxnumber=" . $fromUsername . "&market_eventkey=" . $eventkey . "&uid=" . $uid . "&wpay=1";
                } else {
                    //	  $url=$url."?wxnumber=".$fromUsername."&coupon=".$coupon_code."&shop_eventkey=".return_user_info($fromUsername,"eventkey")."&market_eventkey=".$eventkey."&uid=".$uid;
                    $url = $url . "?wxnumber=" . $fromUsername . "&shop_eventkey=" . return_user_info($fromUsername, "eventkey") . "&market_eventkey=" . $eventkey . "&uid=" . $uid . "&wpay=1";
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $row['id'] . "&wxnumber=" . $fromUsername;
            }
            @$PicUrl = check_picurl($row['picurl']);
            @$PicUrl_Small = $row['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }

            }
            $Content = $Content . "<item>
				 <Title><![CDATA[{$row['title']}]]></Title>
				 <Description><![CDATA[{$row['description']}]]></Description>
				 <PicUrl><![CDATA[{$PicUrl}]]></PicUrl>
				 <Url><![CDATA[{$url}]]></Url>
				 </item>";
            $i = $i + 1;
        }
        $header = sprintf($newTplHeader, $fromUsername, $toUsername, $CreateTime, "news", $i);
        $footer = sprintf($newTplFoot, $FuncFlag);
        $resultStr = $header . $Content . $footer;
        echo $resultStr;
    }

    /***************************************
     *
     * 关注回复
     ******************************************/
    public function request_subscribe($fromUsername, $toUsername, $eventkey)
    {
        include "mysql.php";
        if (!$eventkey or $eventkey == "") {
            $eventkey = "all";
        } else {

            if (check_order_number($eventkey) == true) {
                $uid = query_qr_uid(return_user_info($fromUsername, "eventkey"));
            } else {
                $uid = "";
            }
        }
        if (!$uid) {
            $uid = "";
        }
        $CreateTime = time();
        $FuncFlag = 1;
        $newTplHeader = "<xml>
           <ToUserName><![CDATA[%s]]></ToUserName>
           <FromUserName><![CDATA[%s]]></FromUserName>
           <CreateTime>%s</CreateTime>
           <MsgType><![CDATA[%s]]></MsgType>
           <ArticleCount>%s</ArticleCount>
           <Articles>";
        $newTplItem = "<item>
                 <Title><![CDATA[%s]]></Title>
                 <Description><![CDATA[%s]]></Description>
                 <PicUrl><![CDATA[%s]]></PicUrl>
                 <Url><![CDATA[%s]]></Url>
                 </item>";
        $newTplFoot = "</Articles>
                 <FuncFlag>%s</FuncFlag>
                 </xml>";

        //如果有图文资料时，输出图文
        $Content = '';
        //黄金周专属
        if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
            $result = mysql_query("SELECT * from wx_article where id='170'", $link);
        } else {
            if ($this->Query_Market_Article($eventkey, 1)) {
                $result = mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and   eventkey='" . $eventkey . "'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link);
            } else {
                $result = mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and  eventkey='all'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link);
            }
        }
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            $url = $row['url'];
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    $url = $url . "&wxnumber=" . $fromUsername . "&uid=" . $uid . "&wpay=1";
                    //	$url=$url."&wxnumber=".$fromUsername;
                } else {
                    $url = $url . "?wxnumber=" . $fromUsername . "&uid=" . $uid . "&wpay=1";
                    //	$url=$url."?wxnumber=".$fromUsername;
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $row['id'] . "&wxnumber=" . $fromUsername;
            }
            @$PicUrl = check_picurl($row['picurl']);
            @$PicUrl_Small = $row['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }

            }
            $Content = $Content . "<item>
				 <Title><![CDATA[{$row['title']}]]></Title>
				 <Description><![CDATA[{$row['description']}]]></Description>
				 <PicUrl><![CDATA[{$PicUrl}]]></PicUrl>
				 <Url><![CDATA[{$url}]]></Url>
				 </item>";
            $i = $i + 1;
        }
        $header = sprintf($newTplHeader, $fromUsername, $toUsername, $CreateTime, "news", $i);
        $footer = sprintf($newTplFoot, $FuncFlag);
        $resultStr = $header . $Content . $footer;
        echo $resultStr;
    }

//查询用户市场有没有关注回复的信息，如有只推送市场信息
    /*
     * $type 1:关注 2:关键字
     */
    private function Query_Market_Article($keyword, $type)
    {
        include("mysql.php");

        switch ($type) {
            case "1":
                $row = mysql_fetch_array(mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and   eventkey='" . $keyword . "'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link));
                break;
            case "2":
                $eventkey = return_eventkey_info(return_user_info($keyword, "eventkey"));  //获取客人所属市场
                $row = mysql_fetch_array(mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and   eventkey='" . $eventkey . "'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link));
        }
        if (!$row) {
            return false;
        } else {
            return true;
        }
    }


    public function postMsg_News($fromUsername, $content)
    {

        $result_xjson = $this->item_news($fromUsername, $content);
        $ACCESS_TOKEN = get_access_token();
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $ACCESS_TOKEN;
        vpost($url_post, $result_xjson);
    }

    /***************************************
     *
     * 查天气
     * 来源：百度车联网
     * 地址：http://developer.baidu.com/map/carapi-7.htm
     ******************************************/
    public function weather($fromUsername)
    {
        $json = file_get_contents("http://api.map.baidu.com/telematics/v3/weather?location=%E4%B8%9C%E9%98%B3&output=json&ak=2c87d6d0443ab161753291258ac8ab7a");
        $data = json_decode($json, true);
        $contentStr = "【横店天气预报】：\n\n";
        $contentStr = $contentStr . $data['results'][0]['weather_data'][0]['date'] . "\n";
        $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][0]['weather'] . "\n";
        $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][0]['temperature'] . "\n\n";
        $contentStr = $contentStr . "明天：" . $data['results'][0]['weather_data'][1]['date'] . "\n";
        $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][1]['weather'] . "\n";
        $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][1]['temperature'] . "\n\n";
        $contentStr = $contentStr . "后天：" . $data['results'][0]['weather_data'][2]['date'] . "\n";
        $contentStr = $contentStr . "天气情况：" . $data['results'][0]['weather_data'][2]['weather'] . "\n";
        $contentStr = $contentStr . "气温：" . $data['results'][0]['weather_data'][2]['temperature'] . "\n";


        $this->responseV_Text($fromUsername, $contentStr);
    }

}

/*
 * 开展检票功能时，先判断是不是电话号码，然后判断有没有检票权限
    function request($fromUsername, $toUsername, $keyword)
    {
        include "mysql.php";
        //
        if (check_word($keyword) == "2") {
            Check_Order_Group1($fromUsername, $toUsername, $keyword);
        } else {
            request_keyword($fromUsername, $keyword);
        }
    }



   ///////返回图文信息


public function responseNews($FromUserName, $ToUserName, $Title, $Description, $PicUrl, $Url)
{
    $newsTpl = "<xml>
           <ToUserName><![CDATA[%s]]></ToUserName>
           <FromUserName><![CDATA[%s]]></FromUserName>
           <CreateTime>%s</CreateTime>
           <MsgType><![CDATA[news]]></MsgType>
           <ArticleCount>1</ArticleCount>
           <Articles>
           <item>
           <Title><![CDATA[%s]]></Title>
           <Description><![CDATA[%s]]></Description>
           <PicUrl><![CDATA[%s]]></PicUrl>
           <Url><![CDATA[%s]]></Url>
           </item>
           </Articles>
           <FuncFlag>1</FuncFlag>
           </xml> ";
    $resultStr = sprintf($newsTpl, $FromUserName, $ToUserName, time(), $Title, $Description, $PicUrl, $Url);
    return $resultStr;
    //  	return $newTpl;
}

*/
