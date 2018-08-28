<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '004' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
    $num = sprintf("%04d", $tambah);
    $cekunit = mysql_query("select * from unit");
	$cekunit2 = mysql_query("select * from unit");
	$getunit =  mysql_query("select * from unit where UNIT_ID = '$_SESSION[unit]'");
	$gets = mysql_fetch_array($getunit);
	$cekitem = mysql_query("select * from item");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
		echo"	
			<body onload='unitFunction()'>
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Distribusi</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=distribusiList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Distribusi No.</td>
							<td><input type='text' name='distnumber' id='distnumber' value='DST/$tahun/$bulan/$num' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$tglskg' name='tgl' readonly='readonly'></td>
                        </tr>
                        <tr>
							<td class='titlechild'>Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' onchange='getrec(this)' required>";
								while ($row = mysql_fetch_array($cekunit))
									{
										echo" 
										<option id='".$row['LOC_ID']."' value='".$row['LOC_ID']."'>
											".$row['UNIT_NAME']."
										</option>";
									}   
								echo"					
								</select>
							</td>
                        </tr>
                        <tr>
							<td class='titlechild'>To Unit</td>
							<td>
								<select name='tolocunit' width='20' id='insertTolocunit' onchange='return getrecdist(this)' required>";
								while ($rows = mysql_fetch_array($cekunit2))
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
							<td width='10%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' id='insertItemID' onchange='getval(this)'>";
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
							<td >
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
								<input type='number' size='5' value='1' name='insertknv' id='insertknv' readonly='readonly'>
							</td>
							<td>
								<a class='button3' onclick='return addRecords()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRowDist()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addDIST' id='form1' onsubmit='return setValues()'>
						<table width='100%' id='record'>
							<input type='hidden' name='distnumber' id='distnumber' value='DST/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
                            <input type='hidden' name='note' id='note'>
                            <input type='hidden' name='balancebahanskg' id='balancebahanskg'>
                            <input type='hidden' name='locunit' id='locunit'>
                            <input type='hidden' name='tolocunit' id='tolocunit'>
						</table>
					</form>
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>	
				</div>
			</div>	
					
			";
?>

<script>

function unitFunction(){
	var unit = "<?php echo $gets['LOC_ID'] ?>";
	document.getElementById(unit).selected = true;
	document.getElementById("locunit").value = unit;
}

var a = 0;
var x = 0;

function addRecords() {
	var cek1 =  document.getElementById("insertItemID").value;
	var cek2 =  document.getElementById("insertqty").value;
	var cek5 =  document.getElementById("balanceskg").innerHTML;
	var res = cek5.split(" ");
	var cek6 = parseInt(res[1]);
	var cek7 = parseInt(cek2);
	//var cek3 =  document.getElementById("auto4").value;
	//var cek4 =  document.getElementById("inserthrg").value;
	var ok = false;
	if (cek1 == '' || cek2 == ''){
		document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
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

		if(cek7>cek6){
			alert("Balance Tidak Cukup!!");
			return;
		}else{
			var table = document.getElementById("record");
			var row = table.insertRow(a);
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);
			
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
			width5.value = "10%";
			cell5.setAttributeNode(width5);
			var width6 = document.createAttribute("width"); 
			
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
				
			var btn = document.createElement("input");
			cell5.appendChild(btn);
			var type = document.createAttribute("type"); 
			type.value = "checkbox";
			var name = document.createAttribute("name");
			name.value = "chk"+x;
			var id = document.createAttribute("id");
			id.value = "chk";
			var val5 = document.createAttribute("value");
			val5.value = "Y";
			cell5.appendChild(btn).setAttributeNode(type);
			cell5.appendChild(btn).setAttributeNode(name);
			cell5.appendChild(btn).setAttributeNode(id);
			cell5.appendChild(btn).setAttributeNode(val5);
			
			a++;
			document.getElementById("jmlcell").value = x;
			x++;
		}
	}

	
	document.getElementById("insertItemID").value = " ";
	document.getElementById("insertqty").value = " ";
	document.getElementById("insertSatuan").value = " ";
	document.getElementById("balanceskg").innerHTML = " ";
	document.getElementById("insertknv").value = " ";
	document.getElementById("alert").innerHTML = " ";
	
	
}

function setValues(){
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
    var a = document.getElementById("insertTolocunit").value;
    var b = document.getElementById("insertlocunit").value;
	
	var ok = false;
	if(rowCount <= 0){
		alert("Mohon Lengkapi data inputan!!");
		return ok;
	} else if(a == b){
        alert("Unit tidak boleh sama!!!!");
		//alert(a+"+"+b);
        return ok;
    } else {
		var note = document.getElementById("insertNote").value ;
		document.getElementById("note").value = note;
		var ref = document.getElementById("insertReferenceNo").value ;
		document.getElementById("referenceNo").value = ref;
		//document.getElementById("locunit").value = b;
		//document.getElementById("tolocunit").value = a;
	} 

}

function removeRowDist(){
	try {
	var table = document.getElementById("record");
	var rowCount = table.rows.length;

	
	for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[4].childNodes[0];
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

function getrec(sel){
        var a = sel.value;
        var b = document.getElementById("insertTolocunit").value;
		document.getElementById("locunit").value = a;
        if(a == b){
            document.getElementById("alertlocunit").innerHTML = "Unit tidak boleh sama!!";
            return ok;
        } else {
            document.getElementById("alertlocunit").innerHTML = "";
        }
	}

function getrecdist(sel){
        var a = sel.value;
        var ok = false;
        var b = document.getElementById("insertlocunit").value;
		document.getElementById("tolocunit").value = a;
        if(a == b){
            document.getElementById("alertlocunit").innerHTML = "Unit tidak boleh sama!!";
            return ok;
        } else {
            document.getElementById("alertlocunit").innerHTML = "";
        }
	}


</script>