<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
	$tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
	$cek = mysql_query("SELECT * FROM item_transaction where TRANSACTION_CODE = '001' and TRANSACTION_DATE LIKE '$tahun-$bulan%'");
	$numrow = mysql_num_rows($cek);
	$tambah = $numrow + 1; 
	$num = sprintf("%04d", $tambah);
	$cekitem = mysql_query("select * from item");
	$getItemID = $_GET["id"];
	$itemID = explode(" ",$getItemID);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL from item where ITEM_ID='$itemIDs'");
		echo"	
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
					</div>
						<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderList&page=1 class='link'>Back</a></h3>
			</div>
			
			<div id='wrapper-master'>
				<div class='content-left'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Purchase Order No.</td>
							<td><input type='text' name='ponumber' id='ponumber' value='PO/$tahun/$bulan/$num' disabled></td>
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
							<td width='30%'>Item ID*</td>
							<td width='10%'>Quantity*</td>
							<td width='20%'>Satuan*</td>
							<td width='10%'>Konversi</td>
							<td width='20%'>Harga*</td>
							<td width='10%'></td>
						</tr>
						<tr>
							<td>
								<select name='item' id='insertItemID' onchange='getval(this);getvalknv(this)'>";
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
								<input type='number' size='5' placeholder='0' name='insertqty' id='insertqty' >
							</td>
							<td>
								<select name='satuan' id='insertSatuan'>
									  <option id='satuanbl'></option>
									  <option id='satuanjl'></option>
								</select>
							</td>
							<td>
								<input type='number' size='5' value='1' name='insertknv' id='insertknv' >
							</td>
							<td>
								Rp. <input type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg'>
							</td>
							<td>
								<a class='button3' onclick='return addRecord()'><i class='fa fa-plus-circle'></i> Insert</a>
							</td>
							
						</tr>
						<tr>
							<td colspan='6'>
								<a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
							</td>
						</tr>
					
					</table>
					<form method=POST action='modul/mod_inventory/aksi_inventory.php?act=addPO' id='form1' onsubmit='return setValue()'>
						<table width='100%' id='record'>
							<input type='hidden' name='ponumber' id='ponumber' value='PO/$tahun/$bulan/$num'>
							<input type='hidden' value='$tglskg' name='tgl' readonly='readonly'>
							<input type='hidden' name='jmlcell' id='jmlcell'>
							<input type='hidden' name='note' id='note'>
						</table>
					</form>
					<p id='alert' style='color:red;font-size:12px;text-align:left;'></p>
					<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>	
				</div>
			</div>	
					
			";
?>