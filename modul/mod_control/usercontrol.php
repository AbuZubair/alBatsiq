<?php
$aksi="modul/mod_control/aksi_user.php";

switch($_GET[act]){
	case "tambahuser":
		include "tambahuser.php";
	break;
	
	case "edituser":
		include "edituser.php";
	break;
	
	case "edited":
		include "edited.php";
	break;
	
	case "resetpassword":
		include "resetpassword.php";
	break;
	
}
?>