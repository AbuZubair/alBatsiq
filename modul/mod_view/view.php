<?php

switch($_GET[act]){
	case "masteritem";
			$id = $_GET[id];
			$cekitem = mysql_query("select * from item where ITEM_ID = '$id'");
			$item = mysql_fetch_array ($cekitem);
			
			echo "
						
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Item Product</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masteritemList class='link' >Back</a></h3>
				<h3><a href=albatsiq.php?module=master&act=masteritemEdit&id=$id class='link' >Edit</a></h3>
			</div>
			
				<div id='wrapper-master'>
					<div class='content-left'>
							<table width='100%'>
								<tr>
									<td class='titlechild'>ID Item</td>
									<td><input type='text' name='itemid' size='5' value='$item[0]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'>Nama Item</td>
									<td><input type='text' name='itemname' size='50' value='$item[1]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'>Item Group</td>
									<td><select name='itemgroup'>
											<option value='$item[2]'>$item[2]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Beli</td>
									<td><select name='satuanbeli'>
										  <option value='$item[3]'>$item[3]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Jual</td>
									<td><select name='satuanjual'>
										  <option value='$item[4]'>$item[4]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Konversi</td>
									<td><input type='number' name='konversi' value='$item[7]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'></td>";
									if($item[8] == 'Y'){
										echo"
											<td><input type='checkbox' name='sales_available' value='Y' checked disabled> Untuk Dijual</td>";
									} else{
										echo"
											<td><input type='checkbox' name='sales_available' value='Y' disabled> Untuk Dijual</td>";
									}
									echo"
								</tr>
							</table>
					</div>
					
					<div class='content-right'>
						<table width='100%'>
							<tr>";
								$cekhargabeliskg = mysql_query("select * from item where ITEM_ID = '$id'");
								$hrgbeliskg = mysql_fetch_array($cekhargabeliskg);
							echo"
								<td class='titlechild'>Harga Beli</td>
								<td><input type='text' name='hrgbeli' value='$hrgbeliskg[HARGA_BELI]' readonly='readonly'></td>
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
							</tr>
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
	
	case "masteritemTariff";
		$id = $_GET['id'];
		$hj = $_GET['hj'];
		$cekitemtariff = mysql_query("select 
										item_tariff.ITEM_ID,
										item.ITEM_NAME,
										item_tariff.HARGA_JUAL,
										item_tariff.DATE
										from item_tariff 
										inner join item on item.ITEM_ID = item_tariff.ITEM_ID where item_tariff.ITEM_ID = '$id' and item_tariff.HARGA_JUAL = '$hj'");
		$row = mysql_fetch_array($cekitemtariff);
		$tgl = explode("-",$row['DATE']);
		$tgls = date("$tgl[2]-$tgl[1]-$tgl[0]");
		
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
						</tr>
						
							<tr>
								<td width='50%'>Item</td>
								<td>Harga Jual</td>
								<td>Tanggal</td>
							<tr>
						
							
							<tr>
									<td width='50%'>
										<select name='item' width='20' disabled>
											<option value='$row[ITEM_ID]'> $row[ITEM_ID] - $row[ITEM_NAME]</option>			
										</select>
									</td>
									<td>Rp.<input type='text' step='0.01' placeholder='0.00' name='hargajual' id='hargajual' value='$row[HARGA_JUAL]' readonly='readonly'></td>
									<td><input type='text' name='tgl' value='$tgls' readonly='readonly'></td>
							<tr>	 
					</table>
				</div>
				"; 
	break;
	
	case"purchaseOrder";
		$id = $_GET['id'];
		$cekpo = mysql_query("select * from item_transaction where TRANSACTION_NO = '$id'");
		$cekpoBeforepor = mysql_query("SELECT DISTINCT * FROM item_transaction WHERE TRANSACTION_NO = '$id' AND TRANSACTION_NO NOT IN ( SELECT DISTINCT REFERENCE_NO FROM item_transaction )");
		$cekpurchaseOrder = mysql_query("Select item_transaction_detail.ITEM_ID, 
												item.ITEM_NAME,
												item_transaction_detail.QUANTITY, 
												item_transaction_detail.SATUAN, 
												item_transaction_detail.KONVERSI, 
												item_transaction_detail.HARGA
												from item_transaction_detail 
												inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
												where item_transaction_detail.TRANSACTION_NO = '$id'");
		$purchaseOrder1 = mysql_fetch_array($cekpo);
		$purchaseOrderRow = mysql_num_rows($cekpoBeforepor);
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderList class='link'>Back</a></h3>";
					if($purchaseOrderRow != 0){	
					echo"
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderEdit&id=$id class='link'>Edit</a></h3>";
					}
				echo"
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
				
					<table width='100%'>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' name='ponumber' id='ponumber' value='$purchaseOrder1[TRANSACTION_NO]' disabled></td>
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
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote' disabled>$purchaseOrder1[NOTE]</textarea></td>
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
									<td width='30%'><input type='text' size='50' value='$purchaseOrder[ITEM_ID] - $purchaseOrder[ITEM_NAME]' disabled></td>
									<td width='10%'><input type='number' value='$purchaseOrder[QUANTITY]' disabled></td>
									<td width='20%'><input type='text' value='$purchaseOrder[SATUAN]' disabled></td>
									<td width='10%'><input type='number' value='$purchaseOrder[KONVERSI]' disabled></td>
									<td width='20%'><input type='text' value='$purchaseOrder[HARGA]' disabled></td>
									<td width='10%'></td>
								</tr>
							";
						}
						
			echo"	</table>
					
				</div>
			</div>
				
					
				
			";
	break;
	
	case"purchaseOrderReceive";
		$id = $_GET['id'];
		$cekpor = mysql_query("select * from item_transaction where TRANSACTION_NO = '$id'");
		$cekpurchaseOrderReceive = mysql_query("Select item_transaction_detail.ITEM_ID, 
												item.ITEM_NAME,
												item_transaction_detail.QUANTITY, 
												item_transaction_detail.SATUAN, 
												item_transaction_detail.KONVERSI, 
												item_transaction_detail.HARGA
												from item_transaction_detail 
												inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
												where item_transaction_detail.TRANSACTION_NO = '$id'");
		$purchaseOrderReceive1 = mysql_fetch_array($cekpor);
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Receive</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveList class='link'>Back</a></h3>";
					if($purchaseOrderReceive1['REFERENCE_NO'] == ""){
					echo"<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveEdit&id=$id class='link'>Edit</a></h3>";
					}
			echo"
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
				
					<table width='100%'>
						<tr>
							<td class='titlechild'>Receive No.</td>
							<td><input type='text' name='pornumber' id='pornumber' value='$purchaseOrderReceive1[TRANSACTION_NO]' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Receive Date</td>
							<td><input type='text' value='$purchaseOrderReceive1[TRANSACTION_DATE]' name='tgl' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' value='$purchaseOrderReceive1[REFERENCE_NO]' readonly='readonly'></td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote' disabled>$purchaseOrder1[NOTE]</textarea></td>
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
						
						while($purchaseOrderReceive = mysql_fetch_array($cekpurchaseOrderReceive)){
							echo"
								<tr>
									<td width='30%'><input type='text' size='50' value='$purchaseOrderReceive[ITEM_ID] - $purchaseOrderReceive[ITEM_NAME]' disabled></td>
									<td width='10%'><input type='number' value='$purchaseOrderReceive[QUANTITY]' disabled></td>
									<td width='20%'><input type='text' value='$purchaseOrderReceive[SATUAN]' disabled></td>
									<td width='10%'><input type='number' value='$purchaseOrderReceive[KONVERSI]' disabled></td>
									<td width='20%'><input type='text' value='$purchaseOrderReceive[HARGA]' disabled></td>
									<td width='10%'></td>
								</tr>
							";
						}
						
			echo"	</table>
					
				</div>
			</div>
				
					
				
			";
	break;
}

?>