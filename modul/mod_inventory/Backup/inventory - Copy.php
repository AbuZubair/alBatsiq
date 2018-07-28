<?php
switch($_GET[act]){
	case "purchaseOrderList";
		echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=nomorpo' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='PO No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Purchase Order No. </th>
					<th width='20%'> Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekpo = mysql_query("select * from item_transaction where TRANSACTION_CODE = '001' ORDER BY item_transaction.TRANSACTION_NO DESC");
		while($po = mysql_fetch_array($cekpo)){
			$tgl = explode("-",$po['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$po[0]</td>
					<td>$tanggal</td>
					<td>$po[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$po[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
	break;
	
	case"purchaseOrderNew";
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '001' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
	$num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from item");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
		echo"	
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' name='ponumber' id='ponumber' value='PO/$tahun/$bulan/$num' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$tglskg' name='tgl' readonly='readonly'></td>
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
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='6'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='30%'>Item ID</td>
							<td width='10%'>Quantity</td>
							<td width='20%'>Satuan</td>
							<td width='10%'>Konversi</td>
							<td width='20%'>Harga</td>
							<td width='10%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' width='20' id='insertItemID' onchange='getval(this);'>";
									while ($row = mysql_fetch_array($cekitem))
										{
											echo" 
											<option style='display:none;' selected></option>
											<option value='".$row['ITEM_ID']." - ".$row['ITEM_NAME']."'>
												".$row['ITEM_ID']." - ".$row['ITEM_NAME']." 
											</option>";
										}   
									echo"					
								</select>
							</td>
							<td>
								<input type='number' size='5' placeholder='0' name='insertqty' id='insertqty' >
							</td>
							<td>
								<div id='auto4' name='satuan'></div>
							</td>
							<td>
								<input type='number' size='5' value='1' name='insertknv' id='insertknv' >
							</td>
							<td>
								Rp. <input type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg'>
							</td>
							<td>
								<a class='button3' onclick='return addRecord()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addPO' id='form1' onsubmit='return setValue()'>
						<table width='100%' id='record'>
							<input type='hidden' name='ponumber' id='ponumber' value='PO/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
							<input type='hidden' name='note' id='note'>
						</table>
					</form>
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>	
				</div>
			</div>	
					
			<p id='testing'></p>
			";
			
	break;
	
	case "purchaseOrderEdit";
		$id = $_GET['id'];
		$cekpo = mysql_query("select * from item_transaction where TRANSACTION_NO = '$id'");
		$cekpurchaseOrder = mysql_query("Select item_transaction_detail.ITEM_ID, 
												item.ITEM_NAME,
												item_transaction_detail.QUANTITY, 
												item_transaction_detail.SATUAN, 
												item_transaction_detail.KONVERSI, 
												item_transaction_detail.HARGA
												from item_transaction_detail 
												inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
												where item_transaction_detail.TRANSACTION_NO = '$id'");
		$numrow = mysql_num_rows($cekpurchaseOrder);
		$cekitem = mysql_query("select * from item");
		$purchaseOrder1 = mysql_fetch_array($cekpo);
		$i = 1;
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=view&act=purchaseOrder&id=$id class='link'>Cancel</a></h3>
						<button  type='submit' value='Simpan' form='form1' class='link2'><h3>Save</h3></button>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
				<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=editPO&id=$numrow' id='form1'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' name='ponumber' id='ponumber' value='$purchaseOrder1[TRANSACTION_NO]' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$purchaseOrder1[TRANSACTION_DATE]' name='tgl' readonly='readonly'></td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote'>$purchaseOrder1[NOTE]</textarea></td>
						</tr>
					</table>
				</div>
				
				<div style='clear:both;'>	
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='6'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='30%'>Item ID</td>
							<td width='10%'>Quantity</td>
							<td width='20%'>Satuan</td>
							<td width='10%'>Konversi</td>
							<td width='20%'>Harga</td>
							<td width='10%'></td>
						</tr>";
						
						while($purchaseOrder = mysql_fetch_array($cekpurchaseOrder)){
							echo"
								<tr>
									<td width='30%'><input type='text' size='50' value='$purchaseOrder[ITEM_ID] - $purchaseOrder[ITEM_NAME]' name='itemID$i' readonly='readonly'></td>
									<td width='10%'><input type='number' value='$purchaseOrder[QUANTITY]' name='qty$i'></td>
									<td width='20%'>	
										<select name='satuan$i' id='satuan'>
										  <option style='display:none;' value='$purchaseOrder[SATUAN]'>$purchaseOrder[SATUAN]</option>
										  <option value='Meter' id='satuanbl1'>Meter</option>
										  <option value='Gulung' id='satuanbl2'>Gulung</option>
										  <option value='Buah' id='satuanbl3'>Buah</option>
										  <option value='Pcs' id='satuanbl4'>Pcs</option>
										</select>
									</td>
									<td width='10%'><input type='number' value='$purchaseOrder[KONVERSI]' name='konversi$i'></td>
									<td width='20%'><input type='text' value='$purchaseOrder[HARGA]' name='harga$i'></td>
									<td width='10%'></td>
								</tr>
							
							";
							$i++;
						}
						
			echo"	</table>
					</form>
				</div>
			</div>
			";
	break;
	
	case "purchaseOrderReceiveList";
		echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Receive</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpor' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Received No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Receive No. </th>
					<th width='20%'> Received Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekpor = mysql_query("select * from item_transaction where TRANSACTION_CODE = '002' ORDER BY item_transaction.TRANSACTION_NO DESC");
		while($por = mysql_fetch_array($cekpor)){
			$tgl = explode("-",$por['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$por[0]</td>
					<td>$tanggal</td>
					<td>$por[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReceive&id=$por[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
	break;
	case"purchaseOrderReceiveNew";
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '002' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
	$num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from item");	
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Receive</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Receive No.</td>
							<td><input type='text' name='pornumber' id='pornumber' value='POR/$tahun/$bulan/$num' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Receive Date</td>
							<td><input type='text' value='$tglskg' name='tgl' readonly='readonly'></td>
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
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='6'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='30%'>Item ID*</td>
							<td width='10%'>Quantity*</td>
							<td width='20%'>Satuan*</td>
							<td width='10%'>Konversi</td>
							<td width='20%'>Harga*</td>
							<td width='10%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' width='20' id='insertItemID' >";
									while ($row = mysql_fetch_array($cekitem))
										{
											echo" 
											<option style='display:none;' disabled selected value> </option>
											<option value='".$row['ITEM_ID']." - ".$row['ITEM_NAME']."' id='".$row['ITEM_NAME']."'>
												".$row['ITEM_ID']." - ".$row['ITEM_NAME']." (Satuan : ".$row['SATUAN_BELI'].")
											</option>";
										}   
									echo"					
								</select>
							</td>
							<td>
								<input type='number' size='5' placeholder='0' name='insertqty' id='insertqty' >
							</td>
							<td>
								<select name='insertsatuan' id='insertsatuan' width='20' >
									<option style='display:none;' disabled selected value> </option>
									<option value='Meter' id='satuanbl1'>Meter</option>
									<option value='Gulung' id='satuanbl2'>Gulung</option>
									<option value='Buah' id='satuanbl3'>Buah</option>
									<option value='Pcs' id='satuanbl4'>Pcs</option>									
								</select>
							</td>
							<td>
								<input type='number' size='5' value='1' name='insertknv' id='insertknv' >
							</td>
							<td>
								Rp. <input type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg' >
							</td>
							<td>
								<a class='button3' onclick='return addRecord()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addPOR' id='form1' onsubmit='return setValue()'>
						<table width='100%' id='record'>
							<input type='hidden' name='pornumber' id='pornumber' value='POR/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
							<input type='hidden' name='note' id='note'>
						</table>
					</form>
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>	
				</div>
			</div>	
					
				
			";
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
	document.getElementById("insertknv").value = "1";
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
	}
}

function getval(sel){
	//var js_var = "<br />Hello world from JavaScript"; 
	var vals = sel.value;
	 var xhr;
	 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
	 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
	 
	 var url = 'modul/mod_inventory/get_satuan.php?q=' + vals;
	 xhr.open('GET', url, false);
	 xhr.onreadystatechange = function () {
	 if (xhr.readyState===4 && xhr.status===200) {
	 var div = document.getElementById('auto4');
	 div.innerHTML = xhr.responseText;
	 }
	 }
	 xhr.send();
	 // ajax stop
	 return false;
}


</script>