<?php
include ("../mysql.php");

$result = mysql_query("SELECT * from wx_qrscene_info where parent_id ='' order by id asc",$link);
while($row = mysql_fetch_array($result))
{
	echo "<input name=\"Qrscene_id\" type=\"radio\" id=\"Qrscene_id\" onClick=\"getRadioValue();\" value=\"".$row["Qrscene_Name"]."\">".$row["Qrscene_Name"]."<br>";
}
?>
<head>
<script language = "JavaScript">
function getRadioValue(){//得到radio的值     
        var obj=document.getElementsByName("Qrscene_id");     
        for(var i=0;i<obj.length;i++){     
        if(obj[i].checked){     
            document.getElementById("marketid").value = obj[i].value;  
//            return obj[i].value;     
            }     
        }     
    }  
	function getRadioValue1(){//得到radio的值     

            alert ("fsfsd");  
//            return obj[i].value;       
    }  
</script>
</head>
<input name="marketid" type="hidden" id="marketid" type="hidden" value="">
