<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM production where PROD_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
	$num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from item where ITEM_GROUP='Bahan'");
    $cekitemresult = mysql_query("select * from item where ITEM_GROUP<>'Bahan'");
	$cekunit = mysql_query("select * from unit");
	$getunit =  mysql_query("select * from unit where UNIT_ID = '$_SESSION[unit]'");
	$gets = mysql_fetch_array($getunit);
		echo"	
			<body onload='unitProdFunction()'>
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Production</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=productionList class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Production No.</td>
							<td><input type='text' name='prodnumber' id='prodnumber' value='PROD/$tahun/$bulan/$num' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$tglskg' name='tgl' readonly='readonly'></td>
                        </tr>
                        <tr>
							<td class='titlechild'>From Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit'  onchange='stopAlert(this)' required>";
								while ($row = mysql_fetch_array($cekunit))
									{
										echo" 
										<option id='".$row['LOC_ID']."' value='".$row['LOC_ID']."'>
											".$row['UNIT_NAME']."
										</option>";
									}   
								echo"					
								</select>
								<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
							</td>
						</tr>
                        <tr>
                            <td class='titlechild'>Bahan Dasar</td>
                            <td id='showbahandasar'>
                                <select name='item' class='searchSelect' id='insertItemID' onchange='getbahan(this)'>
									<option disabled value=' ' selected>--Select Item--</option>";
									while ($row = mysql_fetch_array($cekitem))
                                        {
                                            echo" 
                                            <option value='".$row['ITEM_ID']."'>
                                                ".$row['ITEM_ID']." - ".$row['ITEM_NAME']." 
                                            </option>";
                                        }   
                                    echo"					
                                </select>
                                Tersedia <input type='number' style='width: 4em' id='balancebahan' readonly='readonly'>
                                <input type='text' size='3' id='satuans' > 
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
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='6'>
								Item 
							</td>
						</tr>
						<tr>
							<td width='40%'>Item ID*</td>
							<td width='20%'>Quantity*</td>
							<td width='20%'>Bahan yang dipakai*</td>
							<td width='20%'></td>
						</tr>
						<tr>
							<td>
								<select name='insertitemresult' class='searchSelect' id='insertitemresult' >
									<option disabled value=' ' selected>--Select Item--</option>";
									while ($rowresult = mysql_fetch_array($cekitemresult))
										{
											echo" 
											<option value='".$rowresult['ITEM_ID']." - ".$rowresult['ITEM_NAME']."'>
												".$rowresult['ITEM_ID']." - ".$rowresult['ITEM_NAME']." 
											</option>";
										}   
									echo"					
								</select>
							</td>
							<td>
								<input type='number' size='5' placeholder='0' name='insertqty' id='insertqty' >
							</td>
							<td>
                                <input type='number' size='5' placeholder='0' name='insertqtybahan' id='insertqtybahan' >
                                <input type='text' size='3' id='satuan' > 
							</td>
							
							<td>
								<a class='button3' onclick='return addRecordProd()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRowProd()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addPROD' id='form1' onsubmit='return setValues()'>
						<table width='100%' id='record'>
							<input type='hidden' name='prodnumber' id='prodnumber' value='PROD/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
                            <input type='hidden' name='note' id='note'>
							<input type='hidden' name='bahandasar' id='bahandasar'>
							<input type='hidden' name='balancebahanskg' id='balancebahanskg'>
							<input type='hidden' name='satuanskg' id='satuanskg' > 
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

function unitProdFunction(){
	var unit = "<?php echo $gets['LOC_ID'] ?>";
	document.getElementById(unit).selected = true;
}

var a = 0;
var x = 0;

function addRecordProd() {
    
	var cek1 =  document.getElementById("insertitemresult").value;
	var cek2 =  document.getElementById("insertqty").value;
	var cek3 =  document.getElementById("satuan").value;
	var cek4 =  document.getElementById("insertqtybahan").value;
    var cek5 =  document.getElementById("balancebahan").value;
    var cek6 = parseInt(cek5);
    var cek7 = parseInt(cek4);
	var ok = false;
	if (cek1 == '' || cek2 == '' || cek3 == '' || cek4 == ''){
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
    
        if(cek7<=cek6){
            var table = document.getElementById("record");
            var row = table.insertRow(a);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
                
            var width = document.createAttribute("width"); 
            width.value = "40%";
            cell1.setAttributeNode(width);
            var width2 = document.createAttribute("width"); 
            width2.value = "20%";
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
            val.value = document.getElementById("insertitemresult").value;
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
            type.value = "number";
            var name = document.createAttribute("name");
            name.value = "qtybahan"+x;
            var val3 = document.createAttribute("value");
            val3.value = document.getElementById("insertqtybahan").value;
            var read = document.createAttribute("readonly");
            read.value = "readonly";
            var input2 = document.createElement("input");
            cell3.appendChild(input2);
            var type2 = document.createAttribute("type"); 
            type2.value = "text";
            var ukrn = document.createAttribute("size"); 
            ukrn.value = "3";
            var val32 = document.createAttribute("value");
            val32.value = document.getElementById("satuan").value;
            var read2 = document.createAttribute("readonly");
            read2.value = "readonly";
            cell3.appendChild(input).setAttributeNode(type);
            cell3.appendChild(input).setAttributeNode(name);
            cell3.appendChild(input).setAttributeNode(val3);
            cell3.appendChild(input).setAttributeNode(read);
            cell3.appendChild(input2).setAttributeNode(type2);
            cell3.appendChild(input2).setAttributeNode(ukrn);
            cell3.appendChild(input2).setAttributeNode(val32);
            cell3.appendChild(input2).setAttributeNode(read2);
                    
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

            var qtybhn = parseInt(val3.value);
            qtynow = cek6 - qtybhn
            document.getElementById("balancebahan").value = qtynow;   
			document.getElementById("balancebahanskg").value = qtynow;    
        } else{
            alert("Balance Tidak Cukup!!");
        }
	}
	
	document.getElementById("insertitemresult").value = " ";
	document.getElementById("select2-insertitemresult-container").innerHTML = " ";
	document.getElementById("select2-insertitemresult-container").title = " ";
	document.getElementById("insertqty").value = " ";
	document.getElementById("insertqtybahan").value = " ";
	document.getElementById("alert").innerHTML = " "; 
}

function removeRowProd(){
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

function setValues(){
	var table = document.getElementById("record");
	var rowCount = table.rows.length;
	
	var ok = false;
	if(rowCount <= 0){
		alert("Mohon Lengkapi data inputan!!");
		return ok;
	} else {
		var note = document.getElementById("insertNote").value ;
		document.getElementById("note").value = note;
		var unit = document.getElementById("insertlocunit").value;
		document.getElementById("locunit").value = unit;
	} 
}


function getbahan(sel){
	document.getElementById("bahandasar").value = sel.value ;
	var locid = document.getElementById("insertlocunit").value ;
    var ok = false;
	if(locid == ''){
        document.getElementById("alertlocunit").innerHTML = "*Lengkapi data berikut";
    } 
        var vals = sel.value;
        
        var xhr;
        
        if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
        else xhr = new ActiveXObject("Microsoft.XMLHTTP"); // for IE
        
        var url = 'modul/mod_inventory/get_valueprod.php?q=' + vals +'&p=' + locid;
        xhr.open('GET', url, false);
        xhr.onreadystatechange = function () {
        if (xhr.readyState===4 && xhr.status===200) {
        var x =  eval("(" + xhr.responseText + ")");
        var c = x[1];
        var d = x[3];
        document.getElementById("satuan").value = c;
        document.getElementById("satuans").value = c;
		document.getElementById("satuanskg").value = c;
        document.getElementById("balancebahan").value = d;
        }
        }
        xhr.send();
        // ajax stop
        return false; 
}

function stopAlert(sel){
	document.getElementById("locunit").value = sel.value;
    //alert(document.getElementById("insertItemID").value);
  	document.getElementById("insertItemID").value = "";
}


</script>