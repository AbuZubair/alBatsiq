<?php

switch($_GET[act]){
	//Master Unit
	case "masterunitList":	
		include "masterUnitList.php";	
	break;
	
	//Master Unit
	case "masterunitNew":
		include "masterUnitNew.php";
	break;
	
	//Master Unit
	case "masterunitEdit":
		include "masterUnitEdit.php";
	break;
	
	//Master Item
	case "masteritemList":
		include "masterItemList.php";
	break;
	
	//Master Item
	case "masteritemNew";
		include "masterItemNew.php";
	break;
	
	//Master Item
	case"masteritemEdit";
		include "masterItemEdit.php";
	break;
	
	case"masteritemTariffList";
		include "masterItemTariffList.php";
	break;
	
	case "masteritemTariffNew";
		include "masterItemTariffNew.php";
	break;
			
}
?>

<script>
function myFunction() {
    document.getElementById("itemgroup").value = "<?php echo $itemEdit["ITEM_GROUP"] ?>";
	document.getElementById("satuanbl").value = "<?php echo $itemEdit["SATUAN_BELI"] ?>";
	document.getElementById("satuanjl").value = "<?php echo $itemEdit["SATUAN_JUAL"] ?>";
}

function searchfunc(){
	var cek =  document.getElementById("srch").value;
	var ok = false;
	if(cek == ''){
		return ok;
	} 
}

function searchfunc2(){
	var cek2 =  document.getElementById("srch2").value;
	var ok = false;
	if(cek2 == ''){
		return ok;
	} 
}

function searchfuncDate(){
	var cek2 =  document.getElementById("datepicker").value;
	var ok = false;
	if(cek2 == ''){
		return ok;
	} 
}


</script>