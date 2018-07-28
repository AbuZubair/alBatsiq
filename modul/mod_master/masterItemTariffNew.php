<?php
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
?>