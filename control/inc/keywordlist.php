<?php
include ("../mysql.php");

$result = mysql_query("SELECT * from wx_request_keyword order by id asc",$link);
while($row = mysql_fetch_array($result))
{
	echo "<input name=\"keyword\" type=\"checkbox\" id=\"keyword\" onClick=\"chk();\" value=\"".$row["Keyword"]."\">".$row["Keyword"]."<br>";
}
?>
<head>
<script language = "JavaScript">
function chk(){  
  var obj=document.getElementsByName('keyword');  //选择所有name="'test'"的对象，返回数组  
  //取到对象数组后，我们来循环检测它是不是被选中  
  var s='';  
  for(var i=0; i<obj.length; i++){  
    if(obj[i].checked) s+=obj[i].value+',';  //如果选中，将value添加到变量s中  
  }  
  //那么现在来检测s的值就知道选中的复选框的值了  
//  s==''?'你还没有选择任何内容！':s;  
  document.getElementById("keyid").value = s;  
}  
</script>
</head>
<input name="keyid" type="hidden" id="keyid" type="hidden" value="">
