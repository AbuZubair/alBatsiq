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
	var ok = false;
	if (cek1 == '' || cek2 == '' || cek4 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		return ok;
	} else {
		
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
	width.value = "30%";
	cell1.setAttributeNode(width);
	var width2 = document.createAttribute("width"); 
	width2.value = "10%";
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
	document.getElementById("insertqty").value = " ";
	document.getElementById("insertSatuan").value = " ";
	document.getElementById("inserthrg").value = " ";
	document.getElementById("insertknv").value = " ";
	document.getElementById("alert").innerHTML = " ";
	
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

function setValue(){
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	var ok = false;
	if(rowCount <= 0){
		alert("No Record");
		return ok;
	} else {
		var note = document.getElementById("insertNote").value ;
		document.getElementById("note").value = note;
		var ref = document.getElementById("insertReferenceNo").value ;
		document.getElementById("referenceNo").value = ref;
	} 
}


function getval(sel){
	//var js_var = "<br />Hello world from JavaScript"; 
	var vals = sel.value;
	 var xhr;
	 
	 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
	 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
	 
	 var url = 'modul/mod_inventory/get_value.php?q=' + vals;
	 xhr.open('GET', url, false);
	 xhr.onreadystatechange = function () {
	 if (xhr.readyState===4 && xhr.status===200) {
	 var x =  eval("(" + xhr.responseText + ")");
	 var a = x[0];
	 var b = x[2];
	 var c = x[1];
	 document.getElementById("satuanbl").value = a;
	 document.getElementById("satuanbl").text = a;
	 document.getElementById("satuanjl").value = c;
	 document.getElementById("satuanjl").text = c;
	 document.getElementById("insertSatuan").value = a;
	 document.getElementById("insertknv").value = b;
	 }
	 }
	 xhr.send();
	// ajax stop
	 return false;
}

	function outstanding(){
		var date = "<?php echo $date ?>";
		var res = date.split("-");
		var newdate = res[1]+"/"+res[1]+"/"+res[0];
		window.open("modul/mod_inventory/selectPO.php?id=default", "popuppage", "width=500,height=500,toolbar=no,scrollbars=yes,location=no,statusbar=no,menubar=no,resizable=yes,fullscreen=no");
	}
	
	function updateValue(nilai){
    // this gets called from the popup window and updates the field with a new value		
			var table = document.getElementById("record");
			var rowCount = table.rows.length;
		if(rowCount == 0){
				document.getElementById('insertReferenceNo').value = nilai;
							
				var vals = nilai;
				 var xhr;
				 
				 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
				 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
				 
				 var url = 'modul/mod_inventory/get_potopor.php?q=' + vals;
				 xhr.open('GET', url, false);
				 xhr.onreadystatechange = function () {
				 if (xhr.readyState===4 && xhr.status===200) {
				 var x =  JSON.parse(xhr.responseText);
				 var count = x.length;
					 for(var i=0; i<count; i++) {
						var table = document.getElementById("record");
						var row = table.insertRow(i);
						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						var cell3 = row.insertCell(2);
						var cell4 = row.insertCell(3);
						var cell5 = row.insertCell(4);
						var textnode = document.createTextNode("Rp.");
						cell5.appendChild(textnode);
						var cell6 = row.insertCell(5);
						
						var width = document.createAttribute("width"); 
						width.value = "30%";
						cell1.setAttributeNode(width);
						var width2 = document.createAttribute("width"); 
						width2.value = "10%";
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
						name.value = "itemID"+i;
						var val = document.createAttribute("value");
						val.value = x[i].item_ID + " " + "-" + " " + x[i].item_name; 
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
						
						var input = document.createElement("input");
						cell2.appendChild(input);
						var type = document.createAttribute("type"); 
						type.value = "number";
						var name = document.createAttribute("name");
						name.value = "qty"+i;
						var val2 = document.createAttribute("value");
						val2.value = x[i].quantity;
						var read = document.createAttribute("readonly");
						read.value = "readonly";
						cell2.appendChild(input).setAttributeNode(type);
						cell2.appendChild(input).setAttributeNode(name);
						cell2.appendChild(input).setAttributeNode(val2);
						cell2.appendChild(input).setAttributeNode(read);
						
						var input = document.createElement("input");
						cell3.appendChild(input);
						var type = document.createAttribute("type"); 
						type.value = "text";
						var name = document.createAttribute("name");
						name.value = "satuan"+i;
						var val3 = document.createAttribute("value");
						val3.value = x[i].satuan;
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
						name.value = "knv"+i;
						var val4 = document.createAttribute("value");
						val4.value = x[i].konversi;
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
						name.value = "harga"+i;
						var val5 = document.createAttribute("value");
						val5.value = x[i].harga;
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
						name.value = "chk"+i;
						var id = document.createAttribute("id");
						id.value = "chk";
						var val6 = document.createAttribute("value");
						val6.value = "Y";
						cell6.appendChild(btn).setAttributeNode(type);
						cell6.appendChild(btn).setAttributeNode(name);
						cell6.appendChild(btn).setAttributeNode(id);
						cell6.appendChild(btn).setAttributeNode(val6);
						
						document.getElementById("jmlcell").value = i+1;
					 }
				 
					}
				 }
				 xhr.send();
				// ajax stop				
				document.getElementById('buttonInsert').style.display = "none";
				return false;
		}else{
			alert("Harus remove semua inputan yang ada!!");
		}
			 
	}
	
	function ResetOutstanding(){
		location.reload();
	}

</script>