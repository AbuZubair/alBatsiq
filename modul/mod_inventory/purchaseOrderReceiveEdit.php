<?php
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
		$numrow = mysql_num_rows($cekpurchaseOrderReceive);
		$cekitem = mysql_query("select * from item");
		$purchaseOrderReceive1 = mysql_fetch_array($cekpor);
		$i = 1;
		echo"
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Receive</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=view&act=purchaseOrderReceive&id=$id class='link'>Cancel</a></h3>
						<button  type='submit' value='Simpan' form='form1' class='link2'><h3>Save</h3></button>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
				<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=editPOR&id=$numrow' id='form1'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Receive No.</td>
							<td><input type='text' name='pornumber' id='pornumber' value='$purchaseOrderReceive1[TRANSACTION_NO]' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>Receive Date</td>
							<td><input type='text' value='$purchaseOrderReceive1[TRANSACTION_DATE]' name='tgl' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' readonly='readonly'></td>
						</tr>
					</table>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Note</td>
							<td><textarea rows='2' cols='50' name='insertNote' id='insertNote'>$purchaseOrderReceive1[NOTE]</textarea></td>
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
						
						while($purchaseReceiveOrder = mysql_fetch_array($cekpurchaseOrderReceive)){
							echo"
								<tr>
									<td width='30%'><input type='text' size='50' value='$purchaseReceiveOrder[ITEM_ID] - $purchaseReceiveOrder[ITEM_NAME]' name='itemID$i' readonly='readonly'></td>
									<td width='10%'><input type='number' value='$purchaseReceiveOrder[QUANTITY]' name='qty$i'></td>
									<td width='20%'>	
										<select name='satuan$i' id='satuan'>
										  <option style='display:none;' value='$purchaseReceiveOrder[SATUAN]'>$purchaseReceiveOrder[SATUAN]</option>
										  <option value='Meter' id='satuanbl1'>Meter</option>
										  <option value='Gulung' id='satuanbl2'>Gulung</option>
										  <option value='Buah' id='satuanbl3'>Buah</option>
										  <option value='Pcs' id='satuanbl4'>Pcs</option>
										</select>
									</td>
									<td width='10%'><input type='number' value='$purchaseReceiveOrder[KONVERSI]' name='konversi$i'></td>
									<td width='20%'><input type='text' value='$purchaseReceiveOrder[HARGA]' name='harga$i'></td>
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
?>