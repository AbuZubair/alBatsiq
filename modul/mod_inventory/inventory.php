<?php
switch($_GET[act]){
	case "purchaseOrderList";
		include "purchaseOrderList.php";
	break;
	
	case"purchaseOrderNew";
		include "purchaseOrderNew.php";		
	break;
	
	case "purchaseOrderEdit";
		include "purchaseOrderEdit.php";
	break;
	
	case "purchaseOrderReceiveList";
		include "purchaseOrderReceiveList.php";
	break;
	
	case"purchaseOrderReceiveNew";
		include "purchaseOrderReceiveNew.php";
	break;
	
	case "purchaseOrderReceiveEdit";
		include "purchaseOrderReceiveEdit.php";
	break;
	
	case "purchaseOrderReturnList";
		include "purchaseOrderReturnList.php";
	break;

	case "purchaseOrderReturnNew";
		include "purchaseOrderReturnNew.php";
	break;

	case "purchaseOrderReturnEdit";
		include "purchaseOrderReturnEdit.php";
	break;

	case "stockInformation";
		include "stockInformation.php";
	break;

	case "productionList";
		include "productionList.php";
	break;

	case "productionNew";
		include "productionNew.php";
	break;

	case "distribusiList";
		include "distribusiList.php";
	break;

	case "distribusiNew";
		include "distribusiNew.php";
	break;

	case"stockAdjustNew";
		include "stockAdjustNew.php";
	break;

	case"stockAdjustList";
		include "stockAdjustList.php";
	break;
}
?>
<script>
function searchfunc(){
	var cek =  document.getElementById("srch").value;
	var ok = false;
	if(cek == ''){
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

var a = 0;
var x = 0;

function addRecord() {
	var cek1 =  document.getElementById("insertItemID").value;
	var cek2 =  document.getElementById("insertqty").value;
	//var cek3 =  document.getElementById("auto4").value;
	var cek4 =  document.getElementById("inserthrg").value;
	var bal =  document.getElementById("balanceskg").innerHTML;
	var ok = false;
	if (cek1 == '' || cek2 == '' || cek4 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		return ok;
	} else if(cek2 == 0){
		alert("Nilai tidak boleh 0!!");
	}else {
		
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	
	for(var i=0; i<rowCount; i++) {
		var row = table.rows[i];
		var idcek = row.cells[0].childNodes[0];
		if(null != idcek && cek1 == idcek.value) {
			alert("Item Sudah ada dalam list");
			return;
		}
	}
		
    var table = document.getElementById("record");
    var row = table.insertRow(a);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	var textnode = document.createTextNode("Rp.");
	cell5.appendChild(textnode);
	var cell6 = row.insertCell(5);
	
	var width = document.createAttribute("width"); 
	width.value = "20%";
	cell1.setAttributeNode(width);
	var width2 = document.createAttribute("width"); 
	width2.value = "20%";
	cell2.setAttributeNode(width2);
	var width3 = document.createAttribute("width"); 
	width3.value = "20%";
	cell3.setAttributeNode(width3);
	var width4 = document.createAttribute("width"); 
	width4.value = "10%";
	cell4.setAttributeNode(width4);
	var width5 = document.createAttribute("width"); 
	width5.value = "20%";
	cell5.setAttributeNode(width5);
	var width6 = document.createAttribute("width"); 
	width6.value = "10%";
	cell6.setAttributeNode(width6);
	
	
	
	var input = document.createElement("input");
	cell1.appendChild(input);
	var name = document.createAttribute("name");
	name.value = "itemID"+x;
	var val = document.createAttribute("value");
	val.value = document.getElementById("insertItemID").value;
	var id = document.createAttribute("id");
	id.value = "InputItemID";
	var size = document.createAttribute("size");
	size.value = "30";
	var read = document.createAttribute("readonly");
	read.value = "readonly";
	cell1.appendChild(input).setAttributeNode(name);
	cell1.appendChild(input).setAttributeNode(val);
	cell1.appendChild(input).setAttributeNode(id);
	cell1.appendChild(input).setAttributeNode(size);
	cell1.appendChild(input).setAttributeNode(read);
	
	var p = document.createElement("p");
	cell2.appendChild(p);
	var input = document.createElement("input");
	cell2.appendChild(input);
	var type = document.createAttribute("type"); 
	type.value = "number";
	var name = document.createAttribute("name");
	name.value = "qty"+x;
	var val2 = document.createAttribute("value");
	val2.value = document.getElementById("insertqty").value;
	var read = document.createAttribute("readonly");
	read.value = "readonly";
	var style = document.createAttribute("style");
	style.value = "display:inline;font-family:Arial;font-size:15px;";
	var text = document.createTextNode(bal+" " );
	//cell2.appendChild(p).setAttributeNode(id);
	cell2.appendChild(p).setAttributeNode(style);
	cell2.appendChild(p).appendChild(text);
	cell2.appendChild(input).setAttributeNode(type);
	cell2.appendChild(input).setAttributeNode(name);
	cell2.appendChild(input).setAttributeNode(val2);
	cell2.appendChild(input).setAttributeNode(read);
	
	var input = document.createElement("input");
	cell3.appendChild(input);
	var type = document.createAttribute("type"); 
	type.value = "text";
	var name = document.createAttribute("name");
	name.value = "satuan"+x;
	var val3 = document.createAttribute("value");
	val3.value = document.getElementById("insertSatuan").value;
	var read = document.createAttribute("readonly");
	read.value = "readonly";
	cell3.appendChild(input).setAttributeNode(type);
	cell3.appendChild(input).setAttributeNode(name);
	cell3.appendChild(input).setAttributeNode(val3);
	cell3.appendChild(input).setAttributeNode(read);
	
	var input = document.createElement("input");
	cell4.appendChild(input);
	var type = document.createAttribute("type"); 
	type.value = "number";
	var name = document.createAttribute("name");
	name.value = "knv"+x;
	var val4 = document.createAttribute("value");
	val4.value = document.getElementById("insertknv").value;
	var read = document.createAttribute("readonly");
	read.value = "readonly";
	cell4.appendChild(input).setAttributeNode(type);
	cell4.appendChild(input).setAttributeNode(name);
	cell4.appendChild(input).setAttributeNode(val4);
	cell4.appendChild(input).setAttributeNode(read);
	
	var input = document.createElement("input");
	cell5.appendChild(input);
	var type = document.createAttribute("type"); 
	type.value = "number";
	var name = document.createAttribute("name");
	name.value = "harga"+x;
	var val5 = document.createAttribute("value");
	val5.value = document.getElementById("inserthrg").value;
	var read = document.createAttribute("readonly");
	read.value = "readonly";
	cell5.appendChild(input).setAttributeNode(type);
	cell5.appendChild(input).setAttributeNode(name);
	cell5.appendChild(input).setAttributeNode(val5);
	cell5.appendChild(input).setAttributeNode(read);
	
	var btn = document.createElement("input");
	cell6.appendChild(btn);
	var type = document.createAttribute("type"); 
	type.value = "checkbox";
	var name = document.createAttribute("name");
	name.value = "chk"+x;
	var id = document.createAttribute("id");
	id.value = "chk";
	var val6 = document.createAttribute("value");
	val6.value = "Y";
	cell6.appendChild(btn).setAttributeNode(type);
	cell6.appendChild(btn).setAttributeNode(name);
	cell6.appendChild(btn).setAttributeNode(id);
	cell6.appendChild(btn).setAttributeNode(val6);
	
	a++;
	document.getElementById("jmlcell").value = x;
	x++;
	}
	
	document.getElementById("insertItemID").value = " ";
	document.getElementById("select2-insertItemID-container").innerHTML = " ";
	document.getElementById("select2-insertItemID-container").title = " ";
	document.getElementById("insertqty").value = " ";
	document.getElementById("insertSatuan").value = " ";
	document.getElementById("inserthrg").value = " ";
	document.getElementById("insertknv").value = " ";
	document.getElementById("alert").innerHTML = " ";
	document.getElementById("balanceskg").innerHTML = " ";
	
}

function removeRow(){
	try {
	var table = document.getElementById("record");
	var rowCount = table.rows.length;

	
	for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[5].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
					table.deleteRow(i);
					rowCount--;
					i--;
				}
			}
	}catch(e) {
		alert(e);
	}
	
	a = rowCount;
	
}


function getval(sel){
	
	//var js_var = "<br />Hello world from JavaScript"; 
	var vals = sel.value;
	var unit = document.getElementById("insertlocunit").value;
	 var xhr;
	
	 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
	 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
	 
	 var url = 'modul/mod_inventory/get_value.php?q=' + vals +'&unit=' + unit;
	 xhr.open('GET', url, false);
	 xhr.onreadystatechange = function () {
	 if (xhr.readyState===4 && xhr.status===200) {
	 var x =  eval("(" + xhr.responseText + ")");
	 var a = x[0];
	 var b = x[2];
	 var c = x[1];
	 var d = x[3];
	 document.getElementById("satuanbl").value = a;
	 document.getElementById("satuanbl").text = a;
	 document.getElementById("satuanjl").value = c;
	 document.getElementById("satuanjl").text = c;
	 document.getElementById("insertknv").value = b;
	 document.getElementById("insertSatuan").value = a;
	 document.getElementById("balanceskg").innerHTML = "(Balance: "+d+" )";
	 }
	 }
	 xhr.send();
	// ajax stop
	 return false; 
}

	
	function ResetOutstanding(){
		location.reload();
	}
	

</script>