<?php
switch($_GET[act]){
	case "salesNew";
		include "salesNew.php";
	break;
	case "salesRetList";
		include "salesReturnList.php";
	break;
	case "salesRetNew";
		include "salesReturn.php";
	break;
}

?>