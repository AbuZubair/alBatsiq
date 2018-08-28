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
									<td><input type='text' name='itemid' size='5' value='$item[ITEM_ID]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'>Nama Item</td>
									<td><input type='text' name='itemname' size='50' value='$item[ITEM_NAME]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'>Item Group</td>
									<td><select name='itemgroup'>
											<option value='$item[ITEM_GROUP]'>$item[ITEM_GROUP]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Beli</td>
									<td><select name='satuanbeli'>
										  <option value='$item[SATUAN_BELI]'>$item[SATUAN_BELI]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Satuan Jual</td>
									<td><select name='satuanjual'>
										  <option value='$item[SATUAN_JUAL]'>$item[SATUAN_JUAL]</option>
										</select></td>
								</tr>
								<tr>
									<td class='titlechild'>Konversi</td>
									<td><input type='number' name='konversi' value='$item[KONVERSI]' readonly='readonly'></td>
								</tr>
								<tr>
									<td class='titlechild'></td>";
									if($item['SALES_AVAILABLE'] == 'Y'){
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
							<tr>
								<td class='titlechild'>Harga Beli</td>
								<td><input type='text' name='hrgbeli' value='$item[HARGA_BELI]' readonly='readonly'></td>
							</tr>
							<tr>
								<td class='titlechild'>HPP</td>
								<td><input type='text' name='hpp' value='0.00' readonly='readonly' ></td>
							</tr>
							<tr>
								<td class='titlechild'>Harga Jual</td>
								<td><input type='text' name='hrgjual' value='$item[HARGA_JUAL]' readonly='readonly'></td>
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
		$cekpor = mysql_query("select * from item_transaction inner join unit on unit.LOC_ID=item_transaction.TO_LOC_ID where TRANSACTION_NO = '$id'");
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
							<td class='titlechild'>Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' required>
									<option value='".$purchaseOrderReceive1['LOC_ID']."'>
										".$purchaseOrderReceive1['UNIT_NAME']."
									</option>	
								</select>
								<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
							</td>
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
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote' disabled>$purchaseOrderReceive1[NOTE]</textarea></td>
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

	case "purchaseOrderReturn";
	$id = $_GET['id'];
	$cekret = mysql_query("select * from item_transaction inner join unit on unit.LOC_ID=item_transaction.FROM_LOC_ID where TRANSACTION_NO = '$id'");
	$cekpurchaseOrderReturn = mysql_query("Select item_transaction_detail.ITEM_ID, 
											item.ITEM_NAME,
											item_transaction_detail.QUANTITY, 
											item_transaction_detail.SATUAN, 
											item_transaction_detail.KONVERSI, 
											item_transaction_detail.HARGA
											from item_transaction_detail 
											inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
											where item_transaction_detail.TRANSACTION_NO = '$id'");
	$purchaseOrderReturn1 = mysql_fetch_array($cekret);
	$tgl = explode("-",$purchaseOrderReturn1['TRANSACTION_DATE']);
	$tgls = date("$tgl[2]-$tgl[1]-$tgl[0]");
	echo"
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Return</h1>
					</div>
				</div>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnList class='link'>Back</a></h3>";
				if($purchaseOrderReturn1['REFERENCE_NO'] == ""){
				echo"<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnEdit&id=$id class='link'>Edit</a></h3>";
				}
		echo"
		</div>
		
		<div id='wrapper-master'>
			<div class='content-left'>
			
				<table width='100%'>
					<tr>
						<td class='titlechild'>Return No.</td>
						<td><input type='text' name='pornumber' id='pornumber' value='$purchaseOrderReturn1[TRANSACTION_NO]' disabled></td>
					</tr>
					<tr>
						<td class='titlechild'>Return Date</td>
						<td><input type='text' value='$tgls' name='tgl' readonly='readonly'></td>
					</tr>
					<tr>
						<td class='titlechild'>Receive Order No.</td>
						<td><input type='text' value='$purchaseOrderReturn1[REFERENCE_NO]' readonly='readonly'></td>
					</tr>
					<tr>
						<td class='titlechild'>Unit</td>
						<td>
							<select name='locunit' width='20' id='insertlocunit' required>
								<option value='".$purchaseOrderReturn1['LOC_ID']."'>
									".$purchaseOrderReturn1['UNIT_NAME']."
								</option>	
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
						<td><textarea rows='2' cols='50' name='insertNote' id='insertNote' disabled>$purchaseOrderReturn1[NOTE]</textarea></td>
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
					
					while($purchaseOrderReturn = mysql_fetch_array($cekpurchaseOrderReturn)){
						echo"
							<tr>
								<td width='30%'><input type='text' size='50' value='$purchaseOrderReturn[ITEM_ID] - $purchaseOrderReturn[ITEM_NAME]' disabled></td>
								<td width='10%'><input type='number' value='$purchaseOrderReturn[QUANTITY]' disabled></td>
								<td width='20%'><input type='text' value='$purchaseOrderReturn[SATUAN]' disabled></td>
								<td width='10%'><input type='number' value='$purchaseOrderReturn[KONVERSI]' disabled></td>
								<td width='20%'><input type='text' value='$purchaseOrderReturn[HARGA]' disabled></td>
								<td width='10%'></td>
							</tr>
						";
					}
					
		echo"	</table>
				
			</div>
		</div>
			
				
			
		";
	break;

	case"production";
	$id = $_GET['id'];
	$cekprod = mysql_query("select production.PROD_ID, production.PROD_DATE, item.ITEM_NAME, unit.UNIT_NAME,
							production.NOTE from production 
							inner join production_detail on production_detail.PROD_ID = production.PROD_ID
							left join item on item.ITEM_ID = production_detail.BAHAN_ID 
							left join unit on unit.LOC_ID = production.FT_LOC_ID 
							where production.PROD_ID = '$id'");
	$prod = mysql_fetch_array($cekprod);
	$cekprodlist = mysql_query("select * from production_detail left join item on item.ITEM_ID = production_detail.ITEM_RESULT where PROD_ID='$id'");

	echo"	
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
							<td><input type='text' name='prodnumber' id='prodnumber' value='$prod[PROD_ID]' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$prod[PROD_DATE]' name='tgl' readonly='readonly'></td>
                        </tr>
                        <tr>
							<td class='titlechild'>From Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' required>
									<option value='$prod[UNIT_NAME]'>$prod[UNIT_NAME]</option>		
								</select>
							</td>
						</tr>
                        <tr>
                            <td class='titlechild'>Bahan Dasar</td>
                            <td>
                                <select name='item' id='insertItemID' onchange='getbahan(this)'>
									<option value='$prod[ITEM_NAME]'>$prod[ITEM_NAME]</option>					
                                </select>
                            </td>
                        </tr>
					</table>
				</div>
				
				<div class='content-right'>
                    <table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote'>$prod[NOTE]</textarea></td>
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
						</tr>";
						while($prodlis = mysql_fetch_array($cekprodlist)){
							echo"
								<tr>
									<td width='40%'><input type='text' size='50' value='$prodlis[ITEM_RESULT] - $prodlis[ITEM_NAME]' disabled></td>
									<td width='20%'><input type='number' value='$prodlis[QUANTITY]' disabled></td>
									<td width='20%'><input type='text' value='$prodlis[QTY_BAHAN]' disabled><input type='text' size='3' value='$prodlis[SATUAN_BAHAN]' disabled> </td>
									<td width='20%'></td>
								</tr>
							";
						}
					echo"
					</table>";
	
	break;

	case"distribusi";
		$id = $_GET['id'];
		$cekdist = mysql_query("select it.TRANSACTION_NO,it.TRANSACTION_DATE,it.NOTE,unit.UNIT_NAME,u2.UNIT_NAME AS FROM_UNIT
								from item_transaction it
								left join unit on unit.LOC_ID = it.TO_LOC_ID 
								left join unit u2 on u2.LOC_ID = it.FROM_LOC_ID 
								where TRANSACTION_NO = '$id' ");
		
		$cekdistlist = mysql_query("Select item_transaction_detail.ITEM_ID, 
									item.ITEM_NAME,
									item_transaction_detail.QUANTITY, 
									item_transaction_detail.SATUAN, 
									item_transaction_detail.KONVERSI
									from item_transaction_detail 
									inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
									where item_transaction_detail.TRANSACTION_NO = '$id'");
		$dist = mysql_fetch_array($cekdist);
		
		echo"	
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
							<td><input type='text' name='distnumber' id='distnumber' value='$dist[TRANSACTION_NO]' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$dist[TRANSACTION_DATE]' name='tgl' readonly='readonly'></td>
                        </tr>
                        <tr>
							<td class='titlechild'>Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' onchange='getrec(this)' required>							
									<option value='$dist[FROM_LOC_ID]' selected>$dist[FROM_UNIT]</option>													
								</select>
							</td>
                        </tr>
                        <tr>
							<td class='titlechild'>To Unit</td>
							<td>
							<select name='locunit' width='20' id='insertlocunit' onchange='getrec(this)' required>							
								<option value='$dist[TO_LOC_ID]' selected>$dist[UNIT_NAME]</option>													
							</select>
							</td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote'>$dist[NOTE]</textarea></td>
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
							<td width='10%'></td>
						</tr>";
						
						while($distlist = mysql_fetch_array($cekdistlist)){
							echo"
								<tr>
									<td width='30%'><input type='text' size='50' value='$distlist[ITEM_ID] - $distlist[ITEM_NAME]' disabled></td>
									<td width='10%'><input type='number' value='$distlist[QUANTITY]' disabled></td>
									<td width='20%'><input type='text' value='$distlist[SATUAN]' disabled></td>
									<td width='10%'><input type='number' value='$distlist[KONVERSI]' disabled></td>
									<td width='10%'></td>
								</tr>
							";
						}
						
			echo"	</table>
					
				</div>
			</div>";
	break;

	case"stockAdjust";
		$id = $_GET['id'];
		$cekstockAdj = mysql_query("select 
									it.TRANSACTION_NO,it.TRANSACTION_DATE,unit.UNIT_NAME,
									CASE
										when it.NOTE = 'salahHitung' THEN 'Salah Hitung'
										when it.NOTE = 'hilang' THEN 'Hilang'
										when it.NOTE = 'kecelakaan' THEN 'Kecelakaan'
										when it.NOTE = 'lainnya' THEN 'Lainnya'
									END AS NOTETIPE 
									from item_transaction it left join unit on unit.LOC_ID = it.FROM_LOC_ID  
									where it.TRANSACTION_NO = '$id'");
		$cekstockAdjList = mysql_query("Select item_transaction_detail.ITEM_ID, 
												item.ITEM_NAME,
												item_transaction_detail.QUANTITY, 
												item_transaction_detail.SATUAN,
												item_transaction_detail.QTY_TYPE
												from item_transaction_detail 
												inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
												where item_transaction_detail.TRANSACTION_NO = '$id'");
		$sa = mysql_fetch_array($cekstockAdj);
		$tgl = explode("-",$sa['TRANSACTION_DATE']);
		$tgls = date("$tgl[2]-$tgl[1]-$tgl[0]");
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
							<td><input type='text' name='sanumber' id='sanumber' value='$sa[TRANSACTION_NO]' disabled></td>
						</tr>
						<tr>
							<td class='titlechild'>Date</td>
							<td><input type='text' value='$tgls' name='tgl' readonly='readonly'></td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>From Unit</td>
							<td>
								<select name='locunit' width='20' id='insertlocunit' required>							
									<option value='$sa[FROM_LOC_ID]'>$sa[UNIT_NAME]</option>		
								</select>
							</td>
						</tr>
						<tr>
							<td class='titlechild'>Adjust Type</td>
							<td><select name='adjustType' id='adjustType'>
									<option value='$sa[NOTETIPE]'>$sa[NOTETIPE]</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			
			<div style='clear:both;'>	
				<table width='100%'>
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
					</tr>";
					
					while($saList = mysql_fetch_array($cekstockAdjList)){
						echo"
							<tr>
								<td width='20%'><input type='text' size='50' value='$saList[ITEM_ID] - $saList[ITEM_NAME]' disabled></td>
								<td>
									<select name='type' id='insertType' disabled>
										<option value='$saList[QTY_TYPE]'>$saList[QTY_TYPE]<br>							
									</select>
								</td>
								<td width='20%'><input type='number' value='$saList[QUANTITY]' disabled></td>
								<td width='20%'><input type='text' value='$saList[SATUAN]' disabled></td>
								<td width='20%'></td>
							</tr>
						";
					}
					
		echo"	</table>
				
			</div>
		</div>
			
				
			
		";				
	break;
	case "salesReturn";
		$id = $_GET['id'];
		$cekret = mysql_query("select * from sales inner join unit on unit.LOC_ID=sales.FROM_LOC_ID where SALES_ID = '$id'");
		$ceksalesReturn = mysql_query("Select sales_detail.ITEM_ID, 
												item.ITEM_NAME,
												sales_detail.QUANTITY, 
												sales_detail.SATUAN, 
												sales_detail.HARGA_JUAL,
												sales_detail.DISCOUNT_AMOUNT,
												sales_detail.TOTAL_AMOUNT, 
												sales_detail.REFERENCE_ID
												from sales_detail 
												inner join item on sales_detail.ITEM_ID = item.ITEM_ID 
												where sales_detail.SALES_ID = '$id'");
		$saleRet = mysql_fetch_array($cekret);
		$tgl = explode("-",$saleRet['SALES_DATE']);
		$tgls = date("$tgl[2]-$tgl[1]-$tgl[0]");

		echo"
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Sales Return</h1>
					</div>
				</div>
					<h3><a href=albatsiq.php?module=sales&act=salesRetList class='link'>Back</a></h3>";
				if($saleRet['REFERENCE_ID'] == ""){
				echo"<h3><a href=albatsiq.php?module=sales&act=salesRetList&id=$id class='link'>Edit</a></h3>";
				}
		echo"
		</div>
		
		<div id='wrapper-master'>
			<div class='content-left'>
			
				<table width='100%'>
					<tr>
						<td class='titlechild'>Return No.</td>
						<td><input type='text' name='slretnumber' id='slretnumber' value='$saleRet[SALES_ID]' disabled></td>
					</tr>
					<tr>
						<td class='titlechild'>Date</td>
						<td><input type='text' value='$tgls' name='tgl' readonly='readonly'></td>
					</tr>
					<tr>
						<td class='titlechild'>Sales No.</td>
						<td><input type='text' value='$saleRet[REFERENCE_ID]' readonly='readonly'></td>
					</tr>
				</table>
			</div>
			
			<div class='content-right'>
				<table width='100%'>
					<tr>
						<td class='titlechild'>Unit</td>
						<td>
							<select name='locunit' width='20' id='insertlocunit' required>
								<option value='".$saleRet['LOC_ID']."'>
									".$saleRet['UNIT_NAME']."
								</option>	
							</select>
							<p id='alertlocunit' style='display:inline;color:red;font-size:12px;'></p>
						</td>
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
						<td width='15%'>Total Amount</td>
						<td width='10%'></td>
					</tr>";
					
					while($saleReturn = mysql_fetch_array($ceksalesReturn)){
						$hrg = number_format($saleReturn['HARGA_JUAL'], 2, '.', ',');
						$dsc = number_format($saleReturn['DISCOUNT_AMOUNT'], 2, '.', ',');
						$tot = $saleReturn['TOTAL_AMOUNT'] * -1;
						$total = number_format($tot, 2, '.', ',');
						echo"
							<tr>
								<td width='20%'><input type='text' size='50' value='$saleReturn[ITEM_ID] - $saleReturn[ITEM_NAME]' disabled></td>
								<td width='15%'><input type='number' value='$saleReturn[QUANTITY]' disabled></td>
								<td width='10%'><input type='text' value='$saleReturn[SATUAN]' disabled></td>
								<td width='15%'><input type='text' value='$hrg' disabled></td>
								<td width='15%'><input type='text' value='$dsc' disabled></td>
								<td width='15%'><input type='text' value='$total' disabled></td>
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