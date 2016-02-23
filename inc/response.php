<?php
/*********
 * 利用客服接口返回图文消息
 *
 * string $type  图文返回类型  1:关注；2：关键字；
 *string $keyword 关键字，如果是关注回复，则为空
 *
 */
function responseV_News($fromUsername, $keyword, $type)
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
            if (Query_Market_Article($eventkey, 1)) {
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

    $result_xjson = item_news($fromUsername, $content);
    $ACCESS_TOKEN = get_access_token();
    $url_post = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $ACCESS_TOKEN;
    vpost($url_post, $result_xjson);
}

/*
 * 客服接口的json模版
 */
function item_news($fromUsername, $newsArray)
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
function responseV_Text($FromUserName, $content)
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

function responseText($FromUserName, $ToUserName, $content)
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

/***************************
 * 返回图文信息
 ***************************/

function responseNews($FromUserName, $ToUserName, $Title, $Description, $PicUrl, $Url)
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


/***************************************
 *
 * 关键字回复
 ******************************************/
function request_keyword($fromUsername, $keyword)
{
    include "mysql.php";
    $eventkey = return_user_info($fromUsername, "eventkey");  //获取客人所属市场
    /*
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
    */
    //查询是否有符合的记录
    $itemsCount1 = mysql_query("SELECT id from wx_article where audit=1 and online='1'  and (eventkey='all' or eventkey='" . $eventkey . "') and (classid = '" . $keyword . "' or keyword like '%" . $keyword . "%') and startdate<=CURDATE() and enddate>=CURDATE() order by id desc LIMIT 0,1", $link);
    $row1 = mysql_fetch_array($itemsCount1);
    if ($row1) {
        responseV_News($fromUsername, $keyword, "2");

    } else {
        $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
        responseV_Text($fromUsername, $contentStr);
    }

}


/***************************************
 *
 * 目录回复
 ******************************************/
function request_menu($fromUsername, $toUsername, $menu)
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
            if (strtotime($row["AddDate"]) > strtotime("2016-01-10 21:07:00")) {
                $uid = "627A7778313233";
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
function request_subscribe($fromUsername, $toUsername, $eventkey)
{
    include "mysql.php";
//    $eventkey =  return_eventkey_info(return_user_info($fromUsername,"eventkey"));  //获取客人所属市场
    //$uid = query_qr_uid(return_user_info($fromUsername,"eventkey"));
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
    }
    else
    {
        if (Query_Market_Article($eventkey, 1))
        {
            $result = mysql_query("SELECT * from wx_article where msgtype='news' and focus = 1  and audit=1 and online=1 and   eventkey='" . $eventkey . "'  and startdate<=CURDATE() and enddate>=CURDATE()  order by priority asc,id desc  LIMIT 0,10", $link);
        }
        else
        {
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
function Query_Market_Article($keyword, $type)
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

/***************************************
 *
 * 查询代理商二维码的uid
 ******************************************/
function query_qr_uid($eventkey)
{
    include "mysql.php";
    $row = mysql_fetch_array(mysql_query("SELECT * from wx_qrscene_info where Qrscene_id='" . $eventkey . "'", $link));
    return $row['uid'];
}

/*******
 * @param $eventkey
 * @return $flag
 * 确认订单的是否被10整除
 */
function check_order_number($eventkey)
{
    if (($eventkey == "87") || ($eventkey == "88") || ($eventkey == "90") || ($eventkey == "91")) {
        include "mysql.php";
        $result = mysql_query("SELECT * from wx_order_send  order by id desc LIMIT 0,1", $link);
        $row = mysql_fetch_array($result);
        @$orderid = $row['ID'];

        if ($orderid % 1 == "0") {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }

}

/*
 * 检查关键字是否符合要求

 * @param    string       $keyword     输入字符
 * @param    string       $text        客人输入关键字
 * @return   string       $flag        输出结果
*/
function check_word($keyword)
{
    include "mysql.php";
    //  $abc='12345790';//调试语句
    //  if ($abc == '') {$abc='13829840';}
    $flag = 0;
    if (preg_match('|^\d{8}$|', $keyword)) {
        $flag = 1;
    } elseif (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $keyword)) {
        $flag = 2;
    }
    return $flag;
}

/*
 *根据图片是上传还是网络图片，输出正确图片路径
 * @param    string       $picurl      读取图片地址
 * @return   string               输出结果
 */
function check_picurl($picurl)
{
    if (strstr($picurl, 'http') != '') {
        return $picurl;
    } else {
        return "http://weix.hengdianworld.com" . $picurl;
    }
}

/*
 *识别用户语音
 * @param    string       $picurl      读取图片地址
 * @return   string               输出结果
 */

function handleVoice($postObj)
{
    $fromUsername = $postObj->FromUserName;
//    $toUsername = $postObj->ToUserName;
    /*
     $Recognition = $postObj->Recognition;

     $msgId = $postObj->MsgI

     //判断客人是不是扫年会二维码关注的
     $eventkey = return_eventkey_info(return_user_info($fromUsername,"eventkey"));
     if ($eventkey=="1142") {


 //    $CreateTime = $postObj->CreateTime;
 //    $MediaId = $postObj->MediaId;
 //    $Format = $postObj->Format;
         $msgId = (string)$msgId;
         $mem = new Memcache;
         $mem->connect("127.0.0.1", 11211);
         $access_msgId = $mem->get("access_msgId1");
         if ($msgId != $access_msgId) {
             $mem->set("access_msgId1", $msgId, 0, 20);
             //       $access_msgId = $mem->get("access_msgId");

             //     $Contentstr = "识别出您的语音：" . $Recognition . "。";
             //       responseV_Text($fromUsername, $Contentstr);
             //  responseText($fromUsername,$toUsername, $Contentstr);

             //如果有语音活动
             if (strstr($Recognition, '红包') != '') {
                 Take_Money("61", $fromUsername, "6", "1");
                 //  $Contentstr="您好，您是要喊出红包吗，请点击<a href=\"http://4.eastsun.duapp.com/active/post.php?openid=".$fromUsername."\">输入有效订单号。</a>";
             }
             elseif(strstr($Recognition, '庙会') != '')
             {
                 Take_Money("71", $fromUsername, "6", "10");
             }
             elseif(strstr($Recognition, '新年') != '')
             {
                 Take_Money("71", $fromUsername, "6", "10");
             }
             elseif(strstr($Recognition, '横店') != '')
             {
                 Take_Money("66", $fromUsername, "6", "1");
             }
             else {
                 responseV_Text($fromUsername, "您是想喊出红包吗，发音要标准哦。");
             }
         }
     }
 */
    //   else
    //   {
    $contentStr = "嘟......您的留言已经进入自动留声机，小横横回来后会努力回复你的~\n您也可以拨打400-9999141立刻接通小横横。";
    responseV_Text($fromUsername, $contentStr);
    //   }

}

/*
 * 正常文字回复
 */
function handleText_normal($fromUsername,$keyword)
{
    switch ($keyword) {
        case "天气":
            weather($fromUsername);
            break;
        case "y":
            responseV_News($fromUsername, "", 1);
            break;
        case "wxh":
            responseV_Text($fromUsername, $fromUsername);
            break;
        case "a":
            responseV_Text($fromUsername, return_user_info($fromUsername, 'eventkey'));
            break;
        case "刮刮乐":
            Query_ggl($fromUsername);
            break;
        default:
            request_keyword($fromUsername, $keyword);
            break;
    }
}