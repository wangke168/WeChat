<?php

/**
 * Created by PhpStorm.
 * User: 吃不胖的猪
 * Date: 2016/2/24
 * Time: 14:16
 */
class responseMsg
{
    private $newTplHeader = "<xml>
           <ToUserName><![CDATA[%s]]></ToUserName>
           <FromUserName><![CDATA[%s]]></FromUserName>
           <CreateTime>%s</CreateTime>
           <MsgType><![CDATA[%s]]></MsgType>
           <ArticleCount>%s</ArticleCount>
           <Articles>";

    private $newTplItem = "<item>
              <Title><![CDATA[%s]]></Title>
              <Description><![CDATA[%s]]></Description>
              <PicUrl><![CDATA[%s]]></PicUrl>
              <Url><![CDATA[%s]]></Url>
              </item>";

    private $newTplFoot = "</Articles>
                 <FuncFlag>%s</FuncFlag>
                 </xml>";

    /*********
     * 关注或关键字时回复图文消息
     *
     * string $type  图文返回类型  1:重复关注；2：关键字；
     * string $keyword 关键字，如果是关注回复，则为空
     *
     */
    public function responseV_News($fromUsername, $keyword, $type)
    {
        $wxnumber = authcode($fromUsername,'ENCODE',0);
        $db = new DB();
        switch ($type) {
            case "1":
                if (!$keyword || $keyword == "") {
                    $eventkey = "all";
                } else {
                    $eventkey = $keyword;

                    $uid = query_qr_uid($eventkey);
                }
                break;
            case "2":
                $uid = query_qr_uid(return_user_info($fromUsername, "eventkey"));  //获取客人uid
                $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
                break;
        }

        switch ($type) {
            case "1":
                /* 根据用户的openid所属eventkey下有没有关注显示的文章 */
                if ($this->Query_Market_Article($eventkey, 1)) {
                    $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus = :focus  and audit=:audit and online=:online and   eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,8", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => "$eventkey", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                } else {
                    $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus = :focus  and audit=:audit and online=:online and   eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,8", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => "all", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                }
                break;
            case "2":
                $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and keyword like :keyword and audit=:audit and online=:online and  (eventkey=:allkey or eventkey=:eventkey)  and startdate<=:startdate and enddate>=:enddate   order by priority asc,id desc  LIMIT 0,8", array("msgtype" => "news", "keyword" => "%$keyword%", "audit" => "1", "online" => "1", "allkey" => "all", "eventkey" => $eventkey, "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                break;
        }
        $i = 0;
        $content = array();
        foreach ($row as $result) {
            $url = $result['url'];
            /*如果只直接跳转链接页面时，判断是否已经带参数*/
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    $url = $url . "&wxnumber=" . $wxnumber . "&uid=" . $uid . "&wpay=1";
                } else {
                    $url = $url . "?wxnumber=" . $wxnumber . "&uid=" . $uid . "&wpay=1";
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $result['id'] . "&wxnumber=" . $wxnumber;
            }

            @$PicUrl = check_picurl($result['picurl']);
            @$PicUrl_Small = $result['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }
            }
            $i = $i + 1;

            $content[] = array("Title" => "" . $result['title'] . "", "Description" => "" . $result['description'] . "", "Url" => "" . $url . "", "PicUrl" => "" . $PicUrl . "");
        }
        /*关键字有图文时显示图文*/
        $this->postMsg_News($fromUsername, $content);

        /*如果是扫描景区二维码，则提示导航功能*/
        switch ($type) {
            case "1":
                if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
                    $this->responseV_Text($fromUsername, "如果您想寻找周边的洗手间，您可以输入“厕所”或“卫生间”或“洗手间”。小横横会带你去哦。");
                }
                break;
        }
        /* if($type=="1") {
             if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
                 $this->responseV_Text($fromUsername, "如果您想寻找周边的洗手间，您可以输入“厕所”或“卫生间”或“洗手间”。小横横会带你去哦。");
             }
         }*/
    }


    /***************************************
     *
     * 关键字回复
     * string $fromUsername  微信号
     * string $keyword       关键字
     *
     ******************************************/
    public function request_keyword($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $keyword = trim($postObj->Content);
        $db = new DB();
        $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场

        /*先判断关键词，是否接入多客服*/
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗") || strstr($keyword, "有人吗")) {
            $result = $this->transmitKefu($postObj);
            echo $result;
        } else {
            $keyword = check_in($keyword);
            //查询是否有符合的记录
            $row = $db->query("SELECT id from wx_article where audit=:audit and online=:online  and (eventkey=:allkey or eventkey=:eventkey) and  keyword like :keyword and startdate<=:startdate and enddate>=:enddate", array("audit" => "1", "online" => "1", "allkey" => "all", "eventkey" => $eventkey, "keyword" => "%$keyword%", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
            if ($row) {
                $row_txt = $db->query("SELECT id from wx_article where msgtype=:msgtype and audit=:audit and online=:online  and (eventkey=:allkey or eventkey=:eventkey) and  keyword like :keyword and startdate<=:startdate and enddate>=:enddate", array("msgtype" => "txt", "audit" => "1", "online" => "1", "allkey" => "all", "eventkey" => $eventkey, "keyword" => "%$keyword%", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                if ($row_txt) {
                    $this->request_keyword_txt($fromUsername, $keyword);
                } else {
                    $this->responseV_News($fromUsername, $keyword, "2");
                }
            } else {
                $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
                $this->responseV_Text($fromUsername, $contentStr);
            }
        }
    }

    /*
    * 回复关键字的文本回复
    *
    */
    private function request_keyword_txt($fromUsername, $keyword)
    {
        $db = new DB();
        $row_txt = $db->query("SELECT * from wx_article where msgtype=:msgtype and audit=:audit and online=:online  and (eventkey=:allkey or eventkey=:eventkey) and  keyword like :keyword and startdate<=:startdate and enddate>=:enddate", array("msgtype" => "txt", "audit" => "1", "online" => "1", "allkey" => "all", "eventkey" => $eventkey, "keyword" => "%$keyword%", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
        foreach ($row_txt as $result) {
            $this->responseV_Text($fromUsername, $result["content"]);
        }
    }

    /*
     *
     * 菜单回复
     * string $fromUsername
     * string $toUsername
     * string int $menu
     *
     */
    public function request_menu($fromUsername, $toUsername, $menu)
    {
        $wxnumber = authcode($fromUsername,'ENCODE',0);
        $db = new DB();
        $uid = "";
        $eventkey = return_eventkey_info(return_user_info($fromUsername, "eventkey"));  //获取客人所属市场
        if (check_order_number($eventkey) == true) {
            $uid = query_qr_uid(return_user_info($fromUsername, "eventkey"));
        }
        $CreateTime = time();
        $FuncFlag = 1;

        if ($menu == "8") {
            if (!$eventkey) {
                $rowcheck = $db->query("SELECT * from wx_user_info where wx_openID=:wx_openID order by id desc  LIMIT 0,1", array("wx_openID" => $fromUsername));
                if (strtotime($rowcheck[0]['endtime']) > strtotime("2016-03-14 21:07:00")) {
                    $uid = "627A7778313233";
                }
            }
        }

        //如果有图文资料时，输出图文
        $Content = '';
        $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and classid = :classid and audit=:audit and online=:online and  (eventkey=:allkey or eventkey=:eventkey)  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "classid" => "$menu", "audit" => "1", "online" => "1", "allkey" => "all", "eventkey" => "$eventkey", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
        $i = 0;
        foreach ($row as $result) {
            $url = $result['url'];
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    //	$url=$url."&shop_eventkey=".return_user_info($fromUsername,"eventkey")."&wxnumber=".$fromUsername."&coupon=".$coupon_code."&market_eventkey=".$eventkey."&uid=".$uid;
                    $url = $url . "&shop_eventkey=" . return_user_info($fromUsername, "eventkey") . "&wxnumber=" . $wxnumber . "&market_eventkey=" . $eventkey . "&uid=" . $uid . "&wpay=1";
                } else {
                    //	  $url=$url."?wxnumber=".$fromUsername."&coupon=".$coupon_code."&shop_eventkey=".return_user_info($fromUsername,"eventkey")."&market_eventkey=".$eventkey."&uid=".$uid;
                    $url = $url . "?wxnumber=" . $wxnumber . "&shop_eventkey=" . return_user_info($fromUsername, "eventkey") . "&market_eventkey=" . $eventkey . "&uid=" . $uid . "&wpay=1";
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $result['id'] . "&wxnumber=" . $wxnumber;
            }
            @$PicUrl = check_picurl($result['picurl']);
            @$PicUrl_Small = $result['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }
            }
            $Content = $Content . sprintf($this->newTplItem, $result['title'], $result['description'], $PicUrl, $url);
            $i = $i + 1;
        }
        $header = sprintf($this->newTplHeader, $fromUsername, $toUsername, $CreateTime, "news", $i);
        $footer = sprintf($this->newTplFoot, $FuncFlag);
        $resultStr = $header . $Content . $footer;
        echo $resultStr;
    }

    /*
     *
     * 初次关注回复
     *
     */
    public function request_subscribe($fromUsername, $toUsername, $eventkey)
    {
        $wxnumber = authcode($fromUsername,'ENCODE',0);
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
        //如果有图文资料时，输出图文
        $Content = '';
        $db = new DB();

        /*        //黄金周专属
                if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
                    $row = $db->query("SELECT * from wx_article where id='170'");
                } else {
                    if ($this->Query_Market_Article($eventkey, 1)) {
                        $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus =:focus  and audit=:audit and online=:online and eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => $eventkey, "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                    } else {
                        $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus =:focus  and audit=:audit and online=:online and eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => "all", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                    }
                }*/

        if ($this->Query_Market_Article($eventkey, 1)) {
            $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus =:focus  and audit=:audit and online=:online and eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => $eventkey, "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
        } else {
            $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus =:focus  and audit=:audit and online=:online and eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => "all", "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
        }

        $i = 0;
        foreach ($row as $result) {
            $url = $result['url'];
            if ($url != '') {
                if (strstr($url, '?') != '') {
                    $url = $url . "&wxnumber=" . $wxnumber . "&uid=" . $uid . "&wpay=1";
                } else {
                    $url = $url . "?wxnumber=" . $wxnumber . "&uid=" . $uid . "&wpay=1";
                }
            } else {
                $url = "http://weix.hengdianworld.com/article/articledetail.php?id=" . $result['id'] . "&wxnumber=" . $wxnumber;
            }
            @$PicUrl = check_picurl($result['picurl']);
            @$PicUrl_Small = $result['picUrl_Small'];
            if ($i != 0) {
                if ($PicUrl_Small != '') {
                    $PicUrl = check_picurl($PicUrl_Small);
                }
            }
            $Content = $Content . sprintf($this->newTplItem, $result['title'], $result['description'], $PicUrl, $url);
            $i = $i + 1;
        }
        $header = sprintf($this->newTplHeader, $fromUsername, $toUsername, $CreateTime, "news", $i);
        $footer = sprintf($this->newTplFoot, $FuncFlag);
        $resultStr = $header . $Content . $footer;
        echo $resultStr;

        if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
            $this->responseV_Text($fromUsername, "如果您想寻找周边的洗手间，您可以输入“厕所”或“卫生间”或“洗手间”。小横横会带你去哦。");
        }
    }

//查询用户市场有没有关注回复的信息，如有只推送市场信息
    /*
     * $type 1:关注 2:关键字
     */
    private function Query_Market_Article($keyword, $type)
    {
        $db = new DB();
        switch ($type) {
            case "1":
                $eventkey = $keyword;
                $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus = :focus  and audit=:audit and online=:online and  eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => $eventkey, "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                break;
            case "2":
                $eventkey = return_eventkey_info(return_user_info($keyword, "eventkey"));  //获取客人所属市场
                $row = $db->query("SELECT * from wx_article where msgtype=:msgtype and focus =:focus  and audit=:audit and online=:online and  eventkey=:eventkey  and startdate<=:startdate and enddate>=:enddate  order by priority asc,id desc  LIMIT 0,10", array("msgtype" => "news", "focus" => "1", "audit" => "1", "online" => "1", "eventkey" => $eventkey, "startdate" => date('Y-m-d'), "enddate" => date('Y-m-d')));
                break;
        }
        if (!$row) {
            return false;
        } else {
            return true;
        }
    }

    /*
     *
     * 客服接口返回图文消息
     * string $fromUsername
     * string $content
     *
     */

    public function postMsg_News($fromUsername, $content)
    {

        $result_xjson = $this->item_news($fromUsername, $content);
        $ACCESS_TOKEN = get_access_token();
        $url_post = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $ACCESS_TOKEN;
        vpost($url_post, $result_xjson);
    }


    /*
     *
     * 转到客服接口
     *
     */
    private function transmitKefu($object)
    {
        $textTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /*
     *
     * 客服接口返回文字消息
     *
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


    /*
    *
    * 客服接口的json模版
    *
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

    /*
     *
     * 返回文本信息
     *
     */

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
