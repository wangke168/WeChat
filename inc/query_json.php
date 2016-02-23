<?php
@$eventkey				= $_GET['eventkey'];

@$shop_name				= Market_info($eventkey);
@$parents_shop_eventkey	= Eventkey_info($eventkey);
@$parents_shop_name		= Market_info(Eventkey_info($eventkey));
@$market_name			= Market_info(Eventkey_info(Eventkey_info($eventkey)));
@$market_eventkey		= Eventkey_info(Eventkey_info($eventkey));

$arr = array('market_name'=>urlencode($market_name),'market_eventkey'=>$market_eventkey,'parents_shop_name'=>urlencode($parents_shop_name),'parents_shop_eventkey'=>$parents_shop_eventkey,'shop_name'=>urlencode($shop_name),'shop_eventkey'=>$eventkey);

@$jsonpcallback=$_GET['jsonpcallback']; 

echo $jsonpcallback."(".urldecode(json_encode($arr)).")";
//echo $row['parent_ID'];
//echo $market_eventkey;



function Market_info($qr_id)
{
	include("../mysql.php");
	$row=mysql_fetch_array(mysql_query("SELECT Qrscene_Name from WX_Qrscene_Info where Qrscene_id='".$qr_id."'"));
	return $row[0];

}

function Eventkey_info($qr_id)
{
	include("../mysql.php");
	$row=mysql_fetch_array(mysql_query("SELECT parent_ID from WX_Qrscene_Info where Qrscene_id='".$qr_id."'"));
	return $row[0];

}
?> 
