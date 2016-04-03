<?php
	session_start();
	session_unset();  
	session_destroy();  
	echo "<script language=jscript>window.location.href ='Login.php'; </script>";
?>