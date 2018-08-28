<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM sales where SALES_CODE = '001' AND SALES_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
    $num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from stock_item si inner join item i on i.ITEM_ID=si.ITEM_ID where si.LOC_ID='003' AND si.BALANCE<>0 AND i.SALES_AVAILABLE = 'Y'");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
	$cekunit = mysql_query("select * from unit");
	$getunit =  mysql_query("select * from unit where UNIT_ID = '$_SESSION[unit]'");
	$gets = mysql_fetch_array($getunit);
$sum = 0;
		echo"	
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Sales</h1>
						</div>
					</div>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Sales No.</td>
							<td><input type='text' name='slnumber' id='slnumber' value='SALE/$tahun/$bulan/$num' disabled></td>
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
							<td class='titlechild'>Unit</td>";
							if($_SESSION['leveluser'] == 'admin'){
								echo"
								<td>
									<select name='locunit' width='20' id='insertlocunit' required>";
									while ($rows = mysql_fetch_array($cekunit))
										{
											echo" 
											<option style='display:none;' selected></option>
											<option id='".$rows['LOC_ID']."' value='".$rows['LOC_ID']."'>
												".$rows['UNIT_NAME']."
											</option>";
										}   
									echo"					
									</select>
									<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
								</td>
								";
							} else {
								echo"
								<td>
									<select name='locunit' width='20' id='insertlocunit' required>
										<option value='$gets[LOC_ID]'>$gets[UNIT_NAME]</option>			
									</select>
									<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
								</td>";
							}
						echo"	
						</tr>
					</table>
				</div>
						
				<div style='clear:both;'>	
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='7'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='20%'>Item ID*</td>
							<td width='15%'>Quantity*</td>
							<td width='10%'>Satuan*</td>
							<td width='15%'>Harga*</td>
							<td width='15%'>Discount</td>
							<td width='15%'>Discount Amount</td>
							<td width='10%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' class='searchSelect' id='insertItemID' onchange='getvals(this)'>
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
								<input type='number' style='width: 3em' placeholder='0' name='insertqty' id='insertqty' >
								<p id='balanceskg' style='display:inline;font-size:14px;'></p> 
							</td>
							<td>
								<select name='satuan' id='insertSatuan'>
									  <option id='satuanjl'></option>
								</select>
							</td>
							<td>
								Rp. <input type='text' placeholder='0.00' name='inserthrg' id='inserthrg'>
							</td>
							<td>
								<input type='number' style='width: 3em' placeholder='0' name='insertdsc' id='insertdsc'>%
							</td>
							<td>
								Rp. <input type='number' step='0.01' placeholder='0.00' name='insertdscAmt' id='insertdscAmt'>
							</td>
							<td>
								<a class='button3' onclick='return addRecordSale()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='7'>
								<a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
						<tr style='display:none'></tr>
						<tr id='titleshw' style='display:none'>
							<td width='20%'>Item ID</td>
							<td width='15%'>Quantity</td>
							<td width='10%'>Satuan</td>
							<td width='15%'>Harga</td>
							<td width='15%'>Discount</td>
							<td width='15%'>Total Amount</td>
							<td width='10%'></td>
						</tr>	
					
					</table>
					<form method=POST action='modul/mod_sales/aksi_sale.php?act=addSales' id='form1' onsubmit='return setValues()'>
						<table width='100%' id='record'>
							<input type='hidden' name='slnumber' id='slnumber' value='SALE/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
							<input type='hidden' name='created' id='created'>	
							<input type='hidden' name='locunit' id='locunit'>
							<input type='hidden' name='grtotal' id='grtotal'>
						</table>
					
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>

						<table width='100%' id='titleshw2' style='display:none'>
							<td style='color:white;background-color:#001f3f;text-align:right;' colspan='5'>
								Grand Total 
							</td>	
							<td style='color:white;background-color:#001f3f;text-align:left;' width='25%' colspan='2'>
								<p id='grandtotal'></p>
							</td>				
						</table>
					</form>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Payment</button>	
				</div>
			</div>	
					
			";
?>

<script>

var a = 0,
	x = 0,
	sum = 0;

function addRecordSale() {
	var cek1 =  document.getElementById("insertItemID").value;
	var cek2 =  document.getElementById("insertqty").value;
	var cek5 =  document.getElementById("balanceskg").innerHTML;
	var res = cek5.split(" ");
	var cek6 = parseInt(res[1]);
	var cek7 = parseInt(cek2);
	var cek4 =  document.getElementById("inserthrg").value;
	var ok = false;
	if (cek1 == '' || cek2 == '' || cek4 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		return ok;
	} else if (cek2 == 0 || cek4 == 0){
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

	if(cek7>cek6){
			alert("Balance Tidak Cukup!!");
			return;
	} else {
		document.getElementById("titleshw").style.display = "";	
		document.getElementById("titleshw2").style.display = "";	

		var table = document.getElementById("record");
		var row = table.insertRow(a);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		var cell4 = row.insertCell(3);
		var cell5 = row.insertCell(4);
		var cell6 = row.insertCell(5);
		var cell7 = row.insertCell(6);
		var textnode = document.createTextNode("Rp.");
		cell4.appendChild(textnode);
		cell6.appendChild(textnode);
		
		var width = document.createAttribute("width"); 
		width.value = "20%";
		cell1.setAttributeNode(width);
		var width2 = document.createAttribute("width"); 
		width2.value = "15%";
		cell2.setAttributeNode(width2);
		var width3 = document.createAttribute("width"); 
		width3.value = "10%";
		cell3.setAttributeNode(width3);
		var width4 = document.createAttribute("width"); 
		width4.value = "15%";
		cell4.setAttributeNode(width4);
		var width5 = document.createAttribute("width"); 
		width5.value = "15%";
		cell5.setAttributeNode(width5);
		var width6 = document.createAttribute("width"); 
		width6.value = "15%";
		cell6.setAttributeNode(width6);
		var width7 = document.createAttribute("width"); 
		width7.value = "10%";
		cell7.setAttributeNode(width7);
		
			
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
		var style = document.createAttribute("style");
		style.value = "width: 3em";
		var val2 = document.createAttribute("value");
		val2.value = document.getElementById("insertqty").value;
		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell2.appendChild(input).setAttributeNode(type);
		cell2.appendChild(input).setAttributeNode(name);
		cell2.appendChild(input).setAttributeNode(style);
		cell2.appendChild(input).setAttributeNode(val2);
		cell2.appendChild(input).setAttributeNode(read);
		
		var input = document.createElement("input");
		cell3.appendChild(input);
		var type = document.createAttribute("type"); 
		type.value = "text";
		var name = document.createAttribute("name");
		name.value = "satuan"+x;
		var size = document.createAttribute("size");
		size.value = "2";
		var val3 = document.createAttribute("value");
		val3.value = document.getElementById("insertSatuan").value;
		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell3.appendChild(input).setAttributeNode(type);
		cell3.appendChild(input).setAttributeNode(name);
		cell3.appendChild(input).setAttributeNode(size);
		cell3.appendChild(input).setAttributeNode(val3);
		cell3.appendChild(input).setAttributeNode(read);
			
		var input = document.createElement("input");
		cell4.appendChild(input);
		var type = document.createAttribute("type"); 
		type.value = "text";
		var name = document.createAttribute("name");
		name.value = "harga"+x;
		var val4 = document.createAttribute("value");
		val4.value = document.getElementById("inserthrg").value;
		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell4.appendChild(input).setAttributeNode(type);
		cell4.appendChild(input).setAttributeNode(name);
		cell4.appendChild(input).setAttributeNode(val4);
		cell4.appendChild(input).setAttributeNode(read);

		var input = document.createElement("input");
		cell5.appendChild(input);
		var type = document.createAttribute("type"); 
		type.value = "text";
		var name = document.createAttribute("name");
		name.value = "dsc"+x;
		var val5 = document.createAttribute("value");
		var dc = document.getElementById("insertdsc").value,
			dcamt = document.getElementById("insertdscAmt").value;
		if(dc != ""){
			var hrg = document.getElementById("inserthrg").value,
				harga = parseFloat(hrg.replace(/,/g, ''));
			var disc = (dc / 100) * harga,
				discfix = disc.toFixed(2),
				disccur = discfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				val5.value = disccur;  
		}else if(dcamt != ""){
			var hrg = document.getElementById("inserthrg").value,
				harga = parseFloat(hrg.replace(/,/g, ''));
			var disc = parseInt(dcamt),
				discfix = disc.toFixed(2);
				disccur = discfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				val5.value = disccur;  
		} else {
			var hrg = document.getElementById("inserthrg").value,
				harga = parseFloat(hrg.replace(/,/g, '')),
				disc = 0;
			val5.value = 0;
		}
		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell5.appendChild(input).setAttributeNode(type);
		cell5.appendChild(input).setAttributeNode(name);
		cell5.appendChild(input).setAttributeNode(val5);
		cell5.appendChild(input).setAttributeNode(read);

		var input = document.createElement("input");
		cell6.appendChild(input);
		var type = document.createAttribute("type"); 
		type.value = "text";
		var name = document.createAttribute("name");
		name.value = "total"+x;
		var id = document.createAttribute("id");
		id.value =  "total"+x;
		var val6 = document.createAttribute("value");
		
		var total = harga - disc,
			totalamt = total * val2.value
			totalfix = totalamt.toFixed(2), 
			totalcur = totalfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		val6.value = totalcur; 
		sum += totalamt;
		var sumfix = sum.toFixed(2),
			sumcur = sumfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		var read = document.createAttribute("readonly");
		read.value = "readonly";
		cell6.appendChild(input).setAttributeNode(type);
		cell6.appendChild(input).setAttributeNode(name);
		cell6.appendChild(input).setAttributeNode(id);
		cell6.appendChild(input).setAttributeNode(val6);
		cell6.appendChild(input).setAttributeNode(read);
		
		var btn = document.createElement("input");
		cell7.appendChild(btn);
		var type = document.createAttribute("type"); 
		type.value = "checkbox";
		var name = document.createAttribute("name");
		name.value = "chk"+x;
		var id = document.createAttribute("id");
		id.value = "chk";
		var val7 = document.createAttribute("value");
		val7.value = "Y";
		cell7.appendChild(btn).setAttributeNode(type);
		cell7.appendChild(btn).setAttributeNode(name);
		cell7.appendChild(btn).setAttributeNode(id);
		cell7.appendChild(btn).setAttributeNode(val7);
		
		a++;
		document.getElementById("jmlcell").value = x;
		x++;
		}
	}
	
	document.getElementById("grandtotal").innerHTML = "Rp "+sumcur;
	document.getElementById("insertItemID").value = " ";
	document.getElementById("select2-insertItemID-container").innerHTML = " ";
	document.getElementById("select2-insertItemID-container").title = " ";
	document.getElementById("insertqty").value = " ";
	document.getElementById("insertSatuan").value = " ";
	document.getElementById("inserthrg").value = " ";
	document.getElementById("insertdsc").value = " ";
	document.getElementById("insertdscAmt").value = " ";
	document.getElementById("alert").innerHTML = " ";
	document.getElementById("balanceskg").innerHTML = " ";
	
}

function removeRow(){
	try {
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	var grandt =  document.getElementById("grandtotal").innerHTML,
		grndttl = grandt.split(" ");

	
	for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[6].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
				var	amount = row.cells[5].children[0].value,
					amtfloat = parseFloat(amount.replace(/,/g, '')),
					gt = parseFloat(grndttl[1].replace(/,/g, ''));
					sum = gt - amtfloat;
					var sumfix = sum.toFixed(2),
						sumcur = sumfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				document.getElementById("grandtotal").innerHTML = "Rp "+sumcur;
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

function setValues(){
	var units = document.getElementById("insertlocunit").value;
	document.getElementById("locunit").value = units;
	

	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	
	var ok = false;
	if(rowCount <= 0){
		alert("Mohon Lengkapi data inputan!!");
		return ok;
	} else if(units == ''){
		document.getElementById("alertlocunit").innerHTML = "*Isi Data Berikut!!"; 
		return ok;
	}else {
		var grandt =  document.getElementById("grandtotal").innerHTML,
			grndttl = grandt.split(" "),
			grndt = parseFloat(grndttl[1].replace(/,/g, ''));
			document.getElementById("grtotal").value = grndt;
		document.getElementById("created").value = "<?php echo $_SESSION['username'] ?>";

		
	} 

}


function getvals(sel){
	
	//var js_var = "<br />Hello world from JavaScript"; 
	var vals = sel.value;
	var unit = document.getElementById("insertlocunit").value;
	
	 var xhr;
	 
	 if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
	 else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
	 
	 var url = 'modul/mod_sales/get_values.php?q=' + vals+'&unit=' + unit;
	 xhr.open('GET', url, false);
	 xhr.onreadystatechange = function () {
	 if (xhr.readyState===4 && xhr.status===200) {
	 var x =  eval("(" + xhr.responseText + ")");
	 var b = x[2];
	 var c = x[1];
	 var a = x[3];
	 var d = x[4];
	 document.getElementById("satuanjl").value = c;
	 document.getElementById("satuanjl").text = c;
	 document.getElementById("insertSatuan").value = c;
	 var ceknum = parseInt(a),
		 numfix = ceknum.toFixed(2),
	 	 num = numfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	 document.getElementById("inserthrg").value = num;
	 document.getElementById("balanceskg").innerHTML = "(Balance: "+d+" )";
	 }
	 }
	 xhr.send();
	 
	// ajax stop
	 return false; 
}

</script>