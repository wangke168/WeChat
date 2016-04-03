<?php
	 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

	@$ManageLevel		= $_SESSION['ManageLevel'];

	@$UserID			= $_SESSION['UserID'];

	@$eventkey			= $_SESSION['eventkey'];

	@$user_eventket		= $_SESSION['eventkey'];

	@$Permission		= $_SESSION['Permission'];

	@$user_name			= $_SESSION['UserName'];

	if ($UserID=='')
	{
		echo "<script>window.location =\"login.php\";</script>";
	}

	$array_Permission = explode(",",$Permission);

/*	if(in_array("10", $array_Permission)){

	echo "12345";

	}
*/

//		ManageLevel		  = Session("ManageLevel")


?>


