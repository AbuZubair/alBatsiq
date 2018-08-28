<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '003' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
	$num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from item");
	$cekunit = mysql_query("select * from unit");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Return</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Return No.</td>
							<td><input type='text' name='retnumber' id='retnumber' value='RET/$tahun/$bulan/$num' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Return Date</td>
							<td><input type='text' value='$tglskg' name='tgl' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>Receive Order No.</td>
							<td><input id='insertReferenceNo' type='text' readonly='readonly'><button onclick='return outstandingReturn()' style='background-color: #008CBA;'>Outstanding</button>
							<button onclick='ResetOutstanding()' style='background-color: #008CBA;'>Reset</button></td>
						</tr>
						<tr>
							<td class='titlechild'>From Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' onchange='getrec()' required>";
								while ($row = mysql_fetch_array($cekunit))
									{
										echo" 
										<option style='display:none;' selected></option>
										<option value='".$row['LOC_ID']."'>
											".$row['UNIT_NAME']."
										</option>";
									}   
								echo"					
								</select>
								<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
							</td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote' placeholder='Enter note here..'></textarea></td>
						</tr>
					</table>
				</div>
				
				<div style='clear:both;'>	
					<table id='insertTable' width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='6'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='20%'>Item ID*</td>
							<td width='20%'>Quantity*</td>
							<td width='20%'>Satuan*</td>
							<td width='10%'>Konversi</td>
							<td width='20%'>Harga*</td>
							<td width='10%'></td>
						</tr>					
						<tr>
							<td>
								<select name='item' class='searchSelect' id='insertItemID' onchange='getval(this)'>
									<option disabled value=' ' selected>--Select Item--</option>";
									while ($row = mysql_fetch_array($cekitem))
										{
											echo" 
											<option value='".$row['ITEM_ID']." - ".$row['ITEM_NAME']."'>
												".$row['ITEM_ID']." - ".$row['ITEM_NAME']." 
											</option>";
										}   
									echo"					
								</select>
							</td>
							<td>
								<input type='number' size='25' placeholder='0' name='insertqty' id='insertqty' >
								<p id='balanceskg' style='display:inline;font-size:15px;'></p> 
							</td>
							<td>
								<select name='satuan' id='insertSatuan'>
									<option id='satuanbl'></option>
									<option id='satuanjl'></option>
								</select>
							</td>
							<td>
								<input type='number' size='5' value='1' name='insertknv' id='insertknv' >
							</td>
							<td>
								Rp. <input type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg' >
							</td>
			
							<td>
								<a class='button3' onclick='return addRecordret()' id='buttonInsert'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRow()'  id='buttonRemove'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addRET' id='form1' onsubmit='return setValueRet()'>
						<table width='100%' id='record'>
							<input type='hidden' name='retnumber' id='retnumber' value='RET/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='referenceNo' id='referenceNo'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
							<input type='hidden' name='note' id='note'>
							<input type='hidden' name='locunit' id='locunit'>
						</table>
					</form>
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>	
				</div>
			</div>				
				
			";
			
?>
<script>
	
    function outstandingReturn(){
		//var unit = document.getElementById("insertlocunit").value;
	//	if(unit == ''){
	//		document.getElementById("alertlocunit").innerHTML = "*Lengkapi data berikut";
	//		return;
	//	} else{
	//		document.getElementById("alertlocunit").innerHTML = "";
			var date = "<?php echo $date ?>";
			var res = date.split("-");
			var newdate = res[1]+"/"+res[1]+"/"+res[0];
			window.open("modul/mod_inventory/selectPOR.php?id=default&page=1", "popuppage", "width=500,height=600,toolbar=no,scrollbars=yes,location=no,statusbar=no,menubar=no,resizable=yes,fullscreen=no");
	//	}
	}

    function updateValuePOR(nilai){
    // this gets called from the popup window and updates the field with a new value		
			var table = document.getElementById("record");
			var rowCount = table.rows.length;
		if(rowCount == 0){
				document.getElementById('insertReferenceNo').value = nilai;
				var unit = document.getElementById("insertlocunit").value;			
				var vals = nilai;
				 var xhr;
				 
				 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
				 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
				 
				 var url = 'modul/mod_inventory/get_portoret.php?q=' + vals+'&unit=' + unit;
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
						
						
							var p = document.createElement("p");
							cell2.appendChild(p);
							var input = document.createElement("input");
							cell2.appendChild(input);
							var type = document.createAttribute("type"); 
							type.value = "number";
							var name = document.createAttribute("name");
							name.value = "qty"+i;
							var read = document.createAttribute("readonly");
							read.value = "readonly";
							var id = document.createAttribute("id");
							id.value = "balanceskg";
							var style = document.createAttribute("style");
							style.value = "display:inline;font-family:Arial;font-size:15px;";
							var text = document.createTextNode(" (Balance:"+x[i].balance+") ");
							cell2.appendChild(p).setAttributeNode(id);
							cell2.appendChild(p).setAttributeNode(style);
							cell2.appendChild(p).appendChild(text);
							cell2.appendChild(input).setAttributeNode(type);
							cell2.appendChild(input).setAttributeNode(name);
							cell2.appendChild(input).setAttributeNode(read);

							var bal = parseInt(x[i].balance);
							var qty = parseInt(x[i].quantity);
							if(bal >= qty){
								var val2 = document.createAttribute("value");
								val2.value = x[i].quantity;
								cell2.appendChild(input).setAttributeNode(val2);
							}else{
								var val2_1 = document.createAttribute("value");
								val2_1.value = x[i].balance;
								cell2.appendChild(input).setAttributeNode(val2_1);
								var p2_2 = document.createElement("p");
								cell2.appendChild(p2_2);
								var styles = document.createAttribute("style");
								styles.value = "display:inline;font-family:Arial;font-size:9px;color:red;";
								cell2.appendChild(p2_2).setAttributeNode(styles);
								var text2_2 = document.createTextNode("*Quantity disesuaikan balance ");
								cell2.appendChild(p2_2).appendChild(text2_2);
							}
											
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
						
						document.getElementById("insertlocunit").value = x[i].locid;
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

	function addRecordret() {
	var cek1 =  document.getElementById("insertItemID").value;
	var cek2 =  document.getElementById("insertqty").value;
	//var cek3 =  document.getElementById("auto4").value;
	var cek4 =  document.getElementById("inserthrg").value;
	var bal =  document.getElementById("balanceskg").innerHTML;
	var res = bal.split(" ");
	var cek5 = parseInt(res[1]);
    var cek6 = parseInt(cek2);

	var ok = false;
	if (cek1 == '' || cek2 == '' || cek4 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		return ok;
	}else if(cek5<cek6){
		alert("Balance tidak cukup!");
		return ok;
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

function setValueRet(){
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	var location = document.getElementById("insertlocunit").value ;
	var ok = false;
	if(rowCount <= 0){
		alert("Mohon Lengkapi data inputan!!");
		return ok;
	} else {
		var note = document.getElementById("insertNote").value ;
		document.getElementById("note").value = note;
		var ref = document.getElementById("insertReferenceNo").value ;
		document.getElementById("referenceNo").value = ref;
		var location = document.getElementById("insertlocunit").value ;
		document.getElementById("locunit").value = location;
	} 
	
	for(var i=0; i<rowCount; i++) {
		var row = table.rows[i];
		var qtycek = row.cells[1].childNodes[1];
		if(null != qtycek && qtycek.value == 0) {
			alert("Tidak boleh 0");
			return ok;
		}
	}

}


</script>