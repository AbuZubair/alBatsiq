<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '005' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
    $num = sprintf("%04d", $tambah);
    $cekunit = mysql_query("select * from unit");
	$cekitem = mysql_query("select * from item");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
		echo"	
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Stock Adjustment</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=stockAdjustList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Adjustment No.</td>
							<td><input type='text' name='sanumber' id='sanumber' value='SA/$tahun/$bulan/$num' disabled></td>
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
                            <td class='titlechild'>From Unit</td>
                            <td>
                                <select name='locunit' width='20' id='insertlocunit' onchange='getrec(this)' required>";
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
                            </td>
                        </tr>
						<tr>
							<td class='titlechild'>Adjust Type</td>
							<td><select name='adjustType' id='adjustType'>
									  <option style='display:none;' disabled selected value>  </option>
									  <option value='salahHitung'>Salah Hitung</option>
									  <option value='hilang'>Hilang</option>
									  <option value='kecelakaan'>Kecelakaan</option>
									  <option value='lainnya'>Lainnya</option>
									</select>
							</td>
							
						</tr>
					</table>
				</div>
		
				<div style='clear:both;'>	
					<table id='insertTable' style='display:none;' width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='5'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='20%'>Item ID*</td>
							<td width='20%' colspan='2'>Quantity*</td>
							<td width='20%'>Satuan*</td>
							<td width='20%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' id='insertItemID' onchange='getvalAdj(this)'>";
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
								<select name='type' id='insertType'>
									<option style='display:none;' selected></option>
									<option value='+'>(+)<br>
									<option value='-'>(-)<br>
								</select>
							</td>
							<td>
								<input type='number' size='25' placeholder='0' name='insertqty' id='insertqty'>
								<p id='balanceskg' style='display:inline;font-size:15px;'></p>
							</td>
							<td>
								<select name='satuan' id='insertSatuanAdj'>
									  <option id='satuanbl'></option>
									  <option id='satuanjl'></option>
								</select>
								 
							</td>
							<td>
                                <a class='button3' onclick='return addRecords()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
						</tr>
						<tr>
							<td colspan='5'>
								<a class='button3' onclick='removeRowDist()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addStockAdj' id='form1' onsubmit='return setValues()'>
						<table width='100%' id='record'>
							<input type='hidden' name='sanumber' id='sanumber' value='SA/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
                            <input type='hidden' name='notetype' id='notetype'>
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

var a = 0;
var x = 0;

function addRecords() {
	var cek1 =  document.getElementById("insertItemID").value;
	var cek2 =  document.getElementById("insertqty").value;
	var cek3 =  document.getElementById("insertType").value;
	var bal =  document.getElementById("balanceskg").innerHTML;
	var res = bal.split(" ");
	var cek4 = parseInt(res[1]);
    var cek5 = parseInt(cek2);

//	alert(res[1]+""+cek2);
	var ok = false;
	if (cek1 == '' || cek2 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		return ok;
	} else if((cek3 == '-') && (cek4<cek5)){
		alert("Balance tidak cukup!");
		return ok;
	} else if (cek2 == 0){
		alert("Tidak boleh 0");
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
		
		var width = document.createAttribute("width"); 
		width.value = "30%";
		cell1.setAttributeNode(width);
		var width2 = document.createAttribute("width"); 
		width2.value = "30%";
		cell2.setAttributeNode(width2);
		var width3 = document.createAttribute("width"); 
		width3.value = "20%";
		cell3.setAttributeNode(width3);
		var width4 = document.createAttribute("width"); 
		width4.value = "20%";
		cell4.setAttributeNode(width4);
					
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
		
		
		var input2 = document.createElement("input");
		cell2.appendChild(input2);
		var size = document.createAttribute("size");
		size.value = "1";
		var val21 = document.createAttribute("value");
		val21.value = document.getElementById("insertType").value;
		var name2 = document.createAttribute("name");
		name2.value = "qtytype"+x;
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
		cell2.appendChild(input2).setAttributeNode(size);
		cell2.appendChild(input2).setAttributeNode(val21);
		cell2.appendChild(input2).setAttributeNode(name2);
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
		val3.value = document.getElementById("insertSatuanAdj").value;
		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell3.appendChild(input).setAttributeNode(type);
		cell3.appendChild(input).setAttributeNode(name);
		cell3.appendChild(input).setAttributeNode(val3);
		cell3.appendChild(input).setAttributeNode(read);
					
		var btn = document.createElement("input");
		cell4.appendChild(btn);
		var type = document.createAttribute("type"); 
		type.value = "checkbox";
		var name = document.createAttribute("name");
		name.value = "chk"+x;
		var id = document.createAttribute("id");
		id.value = "chk";
		var val4 = document.createAttribute("value");
		val4.value = "Y";
		cell4.appendChild(btn).setAttributeNode(type);
		cell4.appendChild(btn).setAttributeNode(name);
		cell4.appendChild(btn).setAttributeNode(id);
		cell4.appendChild(btn).setAttributeNode(val4);
		
		a++;
		document.getElementById("jmlcell").value = x;
		x++;
	
	}

	document.getElementById("insertItemID").value = "";
	document.getElementById("insertqty").value = "";
	document.getElementById('insertType').value = "";
	document.getElementById("alert").innerHTML = " ";
	document.getElementById("balanceskg").innerHTML = " ";
	//document.getElementById("alert").innerHTML = " ";
	
	
}

function setValues(){
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
    	
	var ok = false;
	if(rowCount <= 0){
		alert("Mohon Lengkapi data inputan!!");
		return ok;
	} else {
		var notetype = document.getElementById("adjustType").value ;
		document.getElementById("notetype").value = notetype;
	} 

}

function removeRowDist(){
	try {
	var table = document.getElementById("record");
	var rowCount = table.rows.length;

	
	for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[3].childNodes[0];
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

function getvalAdj(sel){
	
	//var js_var = "<br />Hello world from JavaScript"; 
	var vals = sel.value;
	var unit = document.getElementById("insertlocunit").value;
	 var xhr;
	
	 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
	 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
	 
	 var url = 'modul/mod_inventory/get_valueAdj.php?q=' + vals +'&unit=' + unit;
	 xhr.open('GET', url, false);
	 xhr.onreadystatechange = function () {
	 if (xhr.readyState===4 && xhr.status===200) {
	 var x =  eval("(" + xhr.responseText + ")");
	 var k = x[0];
	 var b = x[2];
	 var c = x[1];
	 var d = x[3];
	 document.getElementById("satuanbl").value = k;
	 document.getElementById("satuanbl").text = k;
	 document.getElementById("satuanjl").value = c;
	 document.getElementById("satuanjl").text = c;
	 document.getElementById("insertSatuanAdj").value = k;
	 document.getElementById("balanceskg").innerHTML = "(Balance: "+d+" )";
	 }
	 }
	 xhr.send();
	// ajax stop
	 return false; 
}

function getrec(sel){
		var table = document.getElementById("insertTable");
		table.style.display = "";
		table.style.border = "none";
		table.style.width = "100%";
		table.style.padding = "0";
		var b = document.getElementById("insertlocunit").value;
		document.getElementById("locunit").value = b;
	}



</script>