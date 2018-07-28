<?php

switch($_GET[act]){
	//Master Unit
	case "masterunitList":	
		echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitNew class='link'>New</a></h3>
			</div>
			
			<table width='100%'>
				<tr>
					<th width='10%'> ID Unit </th>
					<th width='50%'> Nama Unit </th>
					<th width='30%'> Lokasi Inventori </th>
					<th width='10%'> </th>
				</tr>
				";
				
			$cekunit = mysql_query("select * from unit");
						
			while($unit = mysql_fetch_array($cekunit)){
			echo"
				<tr>
					<td>$unit[0]</td>
					<td>$unit[1]</td>
					<td>$unit[3]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=master&act=masterunitEdit&id=$unit[0]><b>Edit</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		
	break;
	
	//Master Unit
	case "masterunitNew":
		echo "		
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Tambah Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitList class='link'>Back</a></h3>
			</div>
		
		<form method=POST action='modul/mod_master/aksi_master.php?act=addUnit' id='form1'>
		<table width='50%'>
			<tr>
				<td class='titlechild'>ID Unit</td>
				<td><input type='text' name='unitid' size='5' id='unitid' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Nama Unit</td>
				<td><input type='text' name='unitname' size='50' id='unitname' required></td>
			</tr>
			<tr>
				<td class='titlechild'>ID Location</td>
				<td><input type='text' name='locid' size='5' id='locid' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Nama Location</td>
				<td><input type='text' name='locname' id='locname'required></td>
			</tr>			
		</table>
		</form>
		<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>
	";
	break;
	
	//Master Unit
	case "masterunitEdit":
	
		echo "	
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Edit Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitList class='link'>Back</a></h3>
			</div>
		";
		
		
		$id = $_GET['id'];
		$queryunit = mysql_query("select * from unit where UNIT_ID = '$id'");
		$rowunit = mysql_fetch_array($queryunit);
		echo"
			<form method=POST action='modul/mod_master/aksi_master.php?act=editUnit' id='form2'>
					<table width='50%'>
						<tr>
							<td class='titlechild'>ID Unit</td>
							<td><b>$rowunit[0]</b></td>
						</tr>
						<tr>
							<td class='titlechild'> Nama Unit <input type='hidden' value='$rowunit[0]' name='unitid'></td>
							<td><input type='text' value='$rowunit[1]' name='unitname' id='unitname' required></td>
						</tr>
						<tr>
							<td class='titlechild'>ID Location</td>
							<td><b>$rowunit[2]</b></td>
						</tr>	
						<tr>
							<td class='titlechild'> Nama Location <input type='hidden' value='$rowunit[2]' name='locids'></td>
							<td><input type='text' value='$rowunit[3]' name='locname' id='locname' required></td>
						</tr>
					</table>
			</form>
			<button  type='submit' value='Simpan' form='form2' class='button' style='background-color: #008CBA;'>Edit</button>
			";
	break;
	
	//Master Item
	case "masteritemList":
		echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Item Product</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemname' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Search Nama Item..' name='search' id='srch'>
						</form>
					 </div>
					 <div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemid' id='formSearch2' onsubmit='return searchfunc2()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Search ID Item..' name='search' id='srch2'>
						</form>
					 </div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masteritemNew class='link'>New</a></h3>
			</div>
						
			
			<table width='100%'>
				<tr>
					<th width='10%'> ID Item </th>
					<th width='40%'> Nama Item </th>
					<th width='20%'> Item Group </th>
					<th width='20%'> Satuan </th>
					<th width='10%'> </th>
				</tr>
				";
				
			$cekitem = mysql_query("select * from item");
						
			while($item = mysql_fetch_array($cekitem)){
			echo"
				<tr>
					<td>$item[0]</td>
					<td>$item[1]</td>
					<td>$item[2]</td>
					<td>$item[3]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=masteritem&id=$item[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		
	break;
	
	//Master Item
	case "masteritemNew";
		echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Tambah Item Product</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masteritemList class='link'>Back</a></h3>
			</div>
		
		
			<div id='wrapper-master'>
				<div class='content-left'>
					<form method=POST action='modul/mod_master/aksi_master.php?act=addItem' id='form3'>
						<table width='100%'>
							<tr>
								<td class='titlechild'>ID Item</td>
								<td><input type='text' name='itemid' size='5' id='itemid' required></td>
							</tr>
							<tr>
								<td class='titlechild'>Nama Item</td>
								<td><input type='text' name='itemname' size='50' id='itemname' required></td>
							</tr>
							<tr>
								<td class='titlechild'>Item Group</td>
								<td><select name='itemgroup' id='itemgroup'>
									  <option style='display:none;' disabled selected value>  </option>
									  <option value='Bahan'>Bahan</option>
									  <option value='Gamis'>Gamis</option>
									  <option value='Hijab'>Hijab</option>
									  <option value='Pasmina'>Pasmina</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Satuan Beli</td>
								<td><select name='satuanbeli' id='satuanbl'>
									  <option style='display:none;' disabled selected value> </option>
									  <option value='Meter'>Meter</option>
									  <option value='Gulung'>Gulung</option>
									  <option value='Buah'>Buah</option>
									  <option value='Pcs'>Pcs</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Satuan Jual</td>
								<td><select name='satuanjual'  id='satuanjl'>
									  <option style='display:none;' disabled selected value> </option>
									  <option value='Meter'>Meter</option>
									  <option value='Gulung'>Gulung</option>
									  <option value='Buah'>Buah</option>
									  <option value='Pcs'>Pcs</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Konversi</td>
								<td><input type='number' name='konversi' id='konversi' value='1'></td>
							</tr>
							<tr>
								<td class='titlechild'></td>
								<td><input type='checkbox' name='sales_available' id='sales_available' value='Y'> Untuk Dijual</td>
							</tr>
						</table>
					</form>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Harga Beli</td>
							<td><input type='text' name='hrgbeli' value='0.00' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>HPP</td>
							<td><input type='text' name='hpp' value='0.00' readonly='readonly' ></td>
						</tr>
						<tr>
							<td class='titlechild'>Harga Jual</td>
							<td><input type='text' name='hrgjual' value='0.00' readonly='readonly'></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div style='clear:both;'>
				<button  type='submit' value='Simpan' form='form3' class='button' style='background-color: #008CBA;'>Submit</button>
			</div>
		";
		
		
		$id = $_GET['id'];
		if ($id == 'confirmId'){
			$itemid = $_GET['itemid'];
			$itemname = $_GET['itemname'];
			$itemgroup = $_GET['itemgroup'];
			$satuanbeli = $_GET['satuanbeli'];
			$satuanjual = $_GET['satuanjual'];
			$konversi = $_GET['konversi'];
			$sales_available = $_GET['sales_available'];

			echo "<script type='text/javascript'>
				alert('ID sudah digunakan');
					document.getElementById('itemid').value  = '$itemid';
					document.getElementById('itemname').value  = '$itemname';
					document.getElementById('itemgroup').value = '$itemgroup';
					document.getElementById('satuanbl').value = '$satuanbeli';
					document.getElementById('satuanjl').value = '$satuanjual';
					document.getElementById('konversi').value = '$konversi';
					var x = '$sales_available';
					if(x == 'Y'){
						document.getElementById('sales_available').checked = true;
					} else {
						document.getElementById('sales_available').checked = false;
					}
			</script>";
		}
	break;
	
	//Master Item
	case"masteritemEdit";
		$id = $_GET[id];
		$cekitem = mysql_query("select * from item where ITEM_ID = '$id'");
		$itemEdit = mysql_fetch_array ($cekitem);
			
		echo"
			<body onload='myFunction()'>	
					
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Item Product</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=view&act=masteritem&id=$id class='link'>Cancel</a></h3>
				<button  type='submit' value='Simpan' form='form4' class='link2'><h3>Save</h3></button>
			</div>
			
				<div id='wrapper-master'>
					<div class='content-left'>
						<form method=POST action='modul/mod_master/aksi_master.php?act=editItem' id='form4'>
							<table width='100%'>
								<tr>
									<td class='titlechild'>ID Item</td>
									<td><input type='text' name='itemid' size='5' value='$itemEdit[0]'></td>
								</tr>
								<tr>
									<td class='titlechild'>Nama Item</td>
									<td><input type='text' name='itemname' size='50' value='$itemEdit[1]'></td>
								</tr>
								<tr>
									<td class='titlechild'>Item Group</td>
									<td><select name='itemgroup' id='itemgroup'>
											<option value='Bahan' id='itemgroup1'>Bahan</option>
											<option value='Gamis' id='itemgroup2'>Gamis</option>
											<option value='Hijab' id='itemgroup3'>Hijab</option>
											<option value='Pasmina' id='itemgroup4'>Pasmina</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Beli</td>
									<td><select name='satuanbeli' id='satuanbl'>
										  <option value='Meter' id='satuanbl1'>Meter</option>
										  <option value='Gulung' id='satuanbl2'>Gulung</option>
										  <option value='Buah' id='satuanbl3'>Buah</option>
										  <option value='Pcs' id='satuanbl4'>Pcs</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Jual</td>
									<td><select name='satuanjual' id='satuanjl'>
										  <option value='Meter' id='satuanjl1'>Meter</option>
										  <option value='Gulung' id='satuanjl2'>Gulung</option>
										  <option value='Buah' id='satuanjl3'>Buah</option>
										  <option value='Pcs' id='satuanjl4'>Pcs</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Konversi</td>
									<td><input type='number' name='konversi' value='$itemEdit[7]'></td>
								</tr>
								<tr>
									<td class='titlechild'></td>";
									if($itemEdit[8] == 'Y'){
										echo"
											<td><input type='checkbox' name='sales_available' value='Y' checked> Untuk Dijual</td>";
									} else{
										echo"
											<td><input type='checkbox' name='sales_available' value='Y'> Untuk Dijual</td>";
									}
									echo"
								</tr>
							</table>
						</form>
					</div>
					
					<div class='content-right'>
						<table width='100%'>
							<tr>
								<td class='titlechild'>Harga Beli</td>
								<td><input type='text' name='hrgbeli' value='0.00' readonly='readonly'></td>
							</tr>
							<tr>
								<td class='titlechild'>HPP</td>
								<td><input type='text' name='hpp' value='0.00' readonly='readonly' ></td>
							</tr>
							<tr>
								<td class='titlechild'>Harga Jual</td>";
								
								$cekhargajualskg = mysql_query("select * from item_tariff where ITEM_ID = '$id' order by DATE DESC, TARIFF_ID DESC LIMIT 1");
								$hrgjualskg = mysql_fetch_array($cekhargajualskg);
								
								echo"
								<td><input type='text' name='hrgjual' value='$hrgjualskg[HARGA_JUAL]' readonly='readonly'></td>
						</table>
					</div>
				</div>
				
				<div style='clear:both; padding-top:2%;'>
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='3'>History Harga</td>
						</tr>";
						
						$cekhargajual = mysql_query("select * from item_tariff where ITEM_ID = '$id' order by DATE DESC, TARIFF_ID DESC LIMIT 2");
						
						while ($hrgjual = mysql_fetch_array($cekhargajual))
						{
							$hrg = number_format($hrgjual['HARGA_JUAL'], 2, '.', ',');
							$tgl = explode("-",$hrgjual['DATE']);
							$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
							echo"
							<tr>
								<td>Harga Jual</td>
								<td>Rp $hrg</td>
								<td>$tanggal</td>
							<tr>";
						}
					echo"	
					</table>
				</div>
			
			";
	break;
	
	case"masteritemTariffList";
		echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Tariff</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemname' id='formSearch1' onsubmit='return searchfunc()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch'>
							</form>
						 </div>
						 <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
					</div>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffNew class='link'>New</a></h3>
				</div>
							
				
				<table width='100%'>
					<tr>
						<th width='10%'> ID Item </th>
						<th width='40%'> Nama Item </th>
						<th width='20%'> Harga Jual </th>
						<th width='20%'> Date </th>
						<th width='10%'> </th>
					</tr>
					";
					
				$cekitemtariff = mysql_query("select 
										item_tariff.ITEM_ID,
										item.ITEM_NAME,
										item_tariff.HARGA_JUAL,
										item_tariff.DATE
										from item_tariff 
										inner join item on item.ITEM_ID = item_tariff.ITEM_ID order by DATE DESC, TARIFF_ID DESC");
				
							
				while($itemtariff = mysql_fetch_array($cekitemtariff)){
					$hrg = number_format($itemtariff[2], 2, '.', ',');
					$tgl = explode("-",$itemtariff['DATE']);
					$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
				echo"
					<tr>
						<td>$itemtariff[0]</td>
						<td>$itemtariff[1]</td>
						<td>Rp. $hrg</td>
						<td>$tanggal</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=masteritemTariff&id=$itemtariff[0]&hj=$itemtariff[2]><b>View</b></a></td>
					</tr>";
				}
				echo"
				</table>
				
				";
	break;
	
	case "masteritemTariffNew";
	$date = date("Y-m-d");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Tariff</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=master&act=masteritemTariffList class='link'>Back</a></h3>
			</div>
			
			
			<div style='clear:both; padding-top:2%;'>
					<table width='100%'>
						<tr>
							<td style='color:white;background-color:#001f3f;' colspan='3'>Tariff Item</td>
						</tr>";
						
			$cekitemtariff = mysql_query("select * from item where sales_available='Y'");			
			
						echo"
							<tr>
								<td width='50%'>Item</td>
								<td>Harga Jual</td>
								<td>Tanggal</td>
							<tr>
							
							
							<tr>
								<form method=POST action='modul/mod_master/aksi_master.php?act=addItemTariff' id='form5'>
									<td width='50%'>
										<select name='item' width='20'>";
											while ($row = mysql_fetch_array($cekitemtariff))
												{
													echo" <option value='".$row['ITEM_ID']."'>".$row['ITEM_ID']." - ".$row['ITEM_NAME']."</option>";
												}   
											echo"					
											</select>
									</td>
									<td>Rp.<input type='number' step='0.01' placeholder='0.00' name='hargajual' id='hargajual'></td>
									<td><input type='hidden' value='$date' name='tgl'>$tglskg</td>
								</form>
							<tr>
							 
							";
						
					echo"	
					</table>
					<button  type='submit' value='Simpan' form='form5' class='button' style='background-color: #008CBA;'>Submit</button>
				</div>
				";
	break;
			
}
?>

<script>
function myFunction() {
    document.getElementById("itemgroup").value = "<?php echo $itemEdit[2] ?>";
	document.getElementById("satuanbl").value = "<?php echo $itemEdit[3] ?>";
	document.getElementById("satuanjl").value = "<?php echo $itemEdit[4] ?>";
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