<?php
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
?>