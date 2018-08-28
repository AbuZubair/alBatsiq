<script type="text/javascript">
	function my(){
		return confirm('Yakin ingin blokir?');
	}

	function searchfunc(){
	var cek =  document.getElementById("srch").value;
	var ok = false;
	if(cek == ''){
			return ok;	
		} 
	} 
	
	function searchfunc2(){
	var cek =  document.getElementById("srch2").value;
	var ok = false;
	if(cek == ''){
			return ok;	
		} 
	} 
	
	function searchfunc3(){
	var cek =  document.getElementById("srch3").value;
	var ok = false;
	if(cek == ''){
			return ok;			
		} 
	} 
	
	function searchfunc4(){
	var cek =  document.getElementById("datepicker").value;
	var ok = false;
	if(cek == ''){
			return ok;
		} 
	} 
	
	function searchfunc5(){
	var cek =  document.getElementById("srch5").value;
	var ok = false;
	if(cek == ''){
			return ok;
		} 
	} 
	
				
</script>


<?php

switch($_GET[act]){
	
	//Edit User
	case "editusers";
		$search = $_POST['search'];
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Edit User</h1>
						</div>
					
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=editusers' id='form' onsubmit='return searchfunc()'>
								<input type='text' placeholder='Search Nama..' name='search' id='srch'>
								<button type='submit'><i class='fa fa-search'></i></button>
							</form>
						</div>
					</div>
				</div>
				";

			
			$cekquery = mysql_query("select * from user where NAMA_LENGKAP like '%$search%'");
			echo"
				<table width='100%'>
					<tr>
						<th width='20%'> Username </th>
						<th width='50%'> Nama Lengkap </th>
						<th width='10%'> Level </th>
						<th width='20%' colspan='2'>  </th>
					</tr>";
			while($rowcek = mysql_fetch_array($cekquery)){
					echo"
					<tr>
						<td>$rowcek[0]</a></td>
						<td>$rowcek[2]</td>
						<td>$rowcek[3]</td>";
						if ($rowcek[4] == 'N'){
							echo"
							<td><a class='link' style='text-decoration: none;'href=albatsiq.php?module=control&act=edited&id=$rowcek[0]><b>Edit</b></a></td>
							<td><a class='link' style='text-decoration: none;'href=albatsiq.php?module=control&act=edituser&id=blokir&no=$rowcek[0] onclick='return my();'><b>Blokir</b></a></td>
							";
						} else {
							echo"
							<td style='opacity:0.2;'><b>Edit</b></td>
							<td><a class='link' style='text-decoration: none;'href=albatsiq.php?module=control&act=edituser&id=aktif&no=$rowcek[0]><b>Aktivasi</b></a></button></td>
							";
						}
						echo"
					</tr>";
				}
				echo"
			</table>";
			 
	break;
	
	//Item
	case "itemList";
		if ($_GET['id'] == 'itemname'){
			$srch = $_POST['search'];
			$cekitem = mysql_query("select * from item where ITEM_NAME LIKE '%$srch%'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Product</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemname' onsubmit='return searchfunc2()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch2'>
							</form>
						 </div>
						 <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemid' onsubmit='return searchfunc2()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search ID Item..' name='search' id='srch2'>
							</form>
						 </div>
					</div>
					<h3><a href=albatsiq.php?module=master&act=masteritemNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=master&act=masteritemList class='link'>Back</a></h3>
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
		} else if ($_GET['id'] == 'itemid'){
			$srch = $_POST['search'];
			$cekitem = mysql_query("select * from item where ITEM_ID LIKE '%$srch%'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Product</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemname' id='formSearch1' onsubmit='return searchfunc2()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch2'>
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
					<h3><a href=albatsiq.php?module=master&act=masteritemList class='link'>Back</a></h3>
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
		}
	break;
	
	case "itemTariffList";
		if ($_GET['id'] == 'itemname'){
			$srch = $_POST['search'];
			$cekitem = mysql_query("select item_tariff.ITEM_ID,
										item.ITEM_NAME,
										item_tariff.HARGA_JUAL,
										item_tariff.DATE  from item_tariff inner join item on item.ITEM_ID = item_tariff.ITEM_ID where item.ITEM_NAME LIKE '%$srch%' order by DATE DESC, TARIFF_ID DESC");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Tariff</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemname' id='formSearch1' onsubmit='return searchfunc3()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch3'>
							</form>
						 </div>
						 <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemid' id='formSearch2' onsubmit='return searchfunc4()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
					</div>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffList class='link'>Back</a></h3>
				</div>
				
				<table width='100%'>
					<tr>
						<th width='10%'> ID Item </th>
						<th width='40%'> Nama Item </th>
						<th width='20%'> Harga Jual </th>
						<th width='20%'> Date </th>
						<th width='10%'> </th>
					</tr>";
				
				while($itemtariff = mysql_fetch_array($cekitem)){
					$hrg = number_format($itemtariff[2], 2, '.', ',');
					$tgl = explode("-",$itemtariff['DATE']);
					$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
				echo"
					<tr>
						<td>$itemtariff[0]</td>
						<td>$itemtariff[1]</td>
						<td>Rp. $hrg</td>
						<td>$tanggal</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=masteritemTariff&id=$itemtariff[0]><b>View</b></a></td>
					</tr>";
				}
				echo"
				</table>
				";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			$cekitem = mysql_query("select item_tariff.ITEM_ID,
										item.ITEM_NAME,
										item_tariff.HARGA_JUAL,
										item_tariff.DATE  from item_tariff inner join item on item.ITEM_ID = item_tariff.ITEM_ID where item_tariff.DATE = '$tanggal' order by DATE DESC, TARIFF_ID DESC");
												
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Tariff</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemname' id='formSearch1' onsubmit='return searchfunc3()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch3'>
							</form>
						 </div>
						 <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemid' id='formSearch2' onsubmit='return searchfunc4()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
					</div>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffList class='link'>Back</a></h3>
				</div>
				
				<table width='100%'>
					<tr>
						<th width='10%'> ID Item </th>
						<th width='40%'> Nama Item </th>
						<th width='20%'> Harga Jual </th>
						<th width='20%'> Date </th>
						<th width='10%'> </th>
					</tr>";
				
				while($itemtariff = mysql_fetch_array($cekitem)){
					$hrg = number_format($itemtariff[2], 2, '.', ',');
					$tgls = explode("-",$itemtariff['DATE']);
					$tanggals = date("$tgl[0]-$tgl[1]-$tgl[2]");
				echo"
					<tr>
						<td>$itemtariff[0]</td>
						<td>$itemtariff[1]</td>
						<td>Rp. $hrg</td>
						<td>$tanggals</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=masteritemTariff&id=$itemtariff[0]><b>View</b></a></td>
					</tr>";
				}
				echo"
				</table>
				";
		}
	
	break;
	
	case "purchaseOrder";
		if ($_GET['id'] == 'nomorpo'){
			$srch = $_POST['search'];
					
			$cekpo = mysql_query("select * from item_transaction where TRANSACTION_NO LIKE '%$srch%' and TRANSACTION_CODE = '001'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=nomorpo' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='PO No..' name='search' id='srch5'>
							</form>
						 </div>
						  <div class='search-container'>
								<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
								  <button type='submit'><i class='fa fa-search'></i></button>
								  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
								</form>
							 </div>
					</div>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderList&page=1 class='link'>Back</a></h3>
				</div>
				
				
				<table width='100%'>
					<tr>
						<th width='30%'> Purchase Order No. </th>
						<th width='20%'> Date </th>
						<th width='40%'> Note </th>
						<th width='10%'></th>
					</tr>";
			
				while($po = mysql_fetch_array($cekpo)){
					echo"
						<tr>
							<td>$po[0]</td>
							<td>$po[2]</td>
							<td>$po[4]</td>
							<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$po[0]><b>View</b></a></td>
						</tr>";
				}
				
				echo"
				</table>
			";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			
			$cekpo = mysql_query("select * from item_transaction where TRANSACTION_DATE = '$tanggal' and TRANSACTION_CODE = '001'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=nomorpo' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='PO No..' name='search' id='srch5'>
							</form>
						 </div>
						  <div class='search-container'>
								<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
								  <button type='submit'><i class='fa fa-search'></i></button>
								  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
								</form>
							 </div>
					</div>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderList class='link'>Back</a></h3>
				</div>
				
				
				<table width='100%'>
					<tr>
						<th width='30%'> Purchase Order No. </th>
						<th width='20%'> Date </th>
						<th width='40%'> Note </th>
						<th width='10%'></th>
					</tr>";
			
				while($po = mysql_fetch_array($cekpo)){
					$newtgl = explode("-",$po[2]);
					$newtanggal = date("$newtgl[2]-$newtgl[1]-$newtgl[0]");
					echo"
						<tr>
							<td>$po[0]</td>
							<td>$newtanggal</td>
							<td>$po[4]</td>
							<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$po[0]><b>View</b></a></td>
						</tr>";
				}
				
				echo"
				</table>
			";
		}
			
	break;
	
	case "purchaseOrderReceive";
		if ($_GET['id'] == 'nomorpor'){
			$srch = $_POST['search'];
					
			$cekpor = mysql_query("select * from item_transaction where TRANSACTION_NO LIKE '%$srch%' and TRANSACTION_CODE = '002'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Receive</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpor' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='POR No..' name='search' id='srch5'>
							</form>
						 </div>
						  <div class='search-container'>
								<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
								  <button type='submit'><i class='fa fa-search'></i></button>
								  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
								</form>
							 </div>
					</div>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveList class='link'>Back</a></h3>
				</div>
				
				
				<table width='100%'>
					<tr>
						<th width='30%'> Receive No. </th>
						<th width='20%'> Receive Date </th>
						<th width='40%'> Note </th>
						<th width='10%'></th>
					</tr>";
			
				while($por = mysql_fetch_array($cekpor)){
					$tgl = explode("-",$por[2]);
					$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
					echo"
						<tr>
							<td>$por[0]</td>
							<td>$tanggal</td>
							<td>$por[4]</td>
							<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$por[0]><b>View</b></a></td>
						</tr>";
				}
				
				echo"
				</table>
			";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			
			$cekpor = mysql_query("select * from item_transaction where TRANSACTION_DATE = '$tanggal' and TRANSACTION_CODE = '002'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Purchase Order Receive</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpor' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='POR No..' name='search' id='srch5'>
							</form>
						 </div>
						  <div class='search-container'>
								<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
								  <button type='submit'><i class='fa fa-search'></i></button>
								  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
								</form>
							 </div>
					</div>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveNew class='link'>New</a></h3>
					<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveList class='link'>Back</a></h3>
				</div>
				
				
				<table width='100%'>
					<tr>
						<th width='30%'> Receive Order No. </th>
						<th width='20%'> Receive Date </th>
						<th width='40%'> Note </th>
						<th width='10%'></th>
					</tr>";
			
				while($por = mysql_fetch_array($cekpor)){
					$newtgl = explode("-",$por[2]);
					$newtanggal = date("$newtgl[2]-$newtgl[1]-$newtgl[0]");
					echo"
						<tr>
							<td>$por[0]</td>
							<td>$newtanggal</td>
							<td>$por[4]</td>
							<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReceive&id=$por[0]><b>View</b></a></td>
						</tr>";
				}
				
				echo"
				</table>
			";
		}
			
	break;

	case "purchaseOrderReturn";
	if ($_GET['id'] == 'nomorret'){
		$srch = $_POST['search'];
				
		$cekret = mysql_query("select * from item_transaction where TRANSACTION_NO LIKE '%$srch%' and TRANSACTION_CODE = '003'");
		
		echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Return</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=nomorret' id='formSearch1' onsubmit='return searchfunc5()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='RET No..' name='search' id='srch5'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnNew class='link'>New</a></h3>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnList class='link'>Back</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Return No. </th>
					<th width='20%'> Return Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>";
		
			while($ret = mysql_fetch_array($cekret)){
				$tgl = explode("-",$ret[2]);
				$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
				echo"
					<tr>
						<td>$ret[TRANSACTION_NO]</td>
						<td>$tanggal</td>
						<td>$ret[NOTE]</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReturn&id=$ret[TRANSACTION_NO]><b>View</b></a></td>
					</tr>";
			}
			
			echo"
			</table>
		";
	} else if ($_GET['id'] == 'dates'){
		$srch = $_POST['tgl'];
		$tgl = explode("-",$srch);
		$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
		
		$cekret = mysql_query("select * from item_transaction where TRANSACTION_DATE = '$tanggal' and TRANSACTION_CODE = '003'");
		
		echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Return</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=nomorret' id='formSearch1' onsubmit='return searchfunc5()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='RET No..' name='search' id='srch5'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=dates' id='formSearch2' onsubmit='return searchfunc4()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnNew class='link'>New</a></h3>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReturnList class='link'>Back</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Return Order No. </th>
					<th width='20%'> Return Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>";
		
			while($ret = mysql_fetch_array($cekret)){
				$newtgl = explode("-",$ret['TRANSACTION_DATE']);
				$newtanggal = date("$newtgl[2]-$newtgl[1]-$newtgl[0]");
				echo"
					<tr>
						<td>$ret[TRANSACTION_NO]</td>
						<td>$newtanggal</td>
						<td>$ret[NOTE]</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReturn&id=$ret[TRANSACTION_NO]><b>View</b></a></td>
					</tr>";
			}
			
			echo"
			</table>
		";
	}		
	break;

	case "stockInformation";
	$cekunit = mysql_query("select * from unit");
	$cekitem = mysql_query("select * from item");
	$unit = $_POST['locunit']; 
	$item = explode(" ",$_POST['item']);
	$itemID = $item[0];
		echo"	
		<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Stock Information</h1>
						</div>
					</div>
			</div>
			
			<div id='wrapper-master'>
                <form method=POST action='albatsiq.php?module=search&act=stockInformation' id='form1' onsubmit='return setValues()'>
					<table width='100%'>
						<tr>
							<td width='20%' style='background-color:#001f3f;color:white;'>Unit</td>
							<td>
								<select name='locunit' width='20' id='unit' required>";
								while ($row = mysql_fetch_array($cekunit))
									{
										echo" 
										<option style='display:none;' selected></option>
										<option value='".$row['LOC_ID']."'>
											".$row['UNIT_NAME']."
										</option>";
									}   
								echo"					
								</select>
                            </td>
                            <td rowspan=2>
                            <button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Search</button>
                            </td>
						</tr>
						<tr>
                            <td width='20%' style='background-color:#001f3f;color:white;'>Item</td>
                            <td>
							    <select name='item' width='20' id='ItemID'>";
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
						</tr>
                    </table>
                </form>            
            </div>	
		
		<table width='100%'>
				<tr>
					<th width='30%'> Unit </th>
					<th width='10%'> Item ID</th>
					<th width='30%'> Item Name</th>
					<th width='10%'> Balance </th>
					<th width='20%'> Date </th>
				</tr>
			";

		if($itemID == ''){
			$cekstock = mysql_query("select unit.UNIT_NAME,
							item.ITEM_NAME,
							stock_item.ITEM_ID,
							stock_item.BALANCE,
							stock_item.DATE
								from stock_item 
								left join unit on stock_item.LOC_ID = unit.LOC_ID
								left join item on stock_item.ITEM_ID = item.ITEM_ID 
								where stock_item.LOC_ID = '$unit'");
		} else if($unit == ''){
			$cekstock = mysql_query("select unit.UNIT_NAME,
								item.ITEM_NAME,
								stock_item.ITEM_ID,
								stock_item.BALANCE,
								stock_item.DATE
									from stock_item 
									left join unit on stock_item.LOC_ID = unit.LOC_ID
									left join item on stock_item.ITEM_ID = item.ITEM_ID 
									where stock_item.ITEM_ID = '$itemID'");
		} else {
			$cekstock = mysql_query("select unit.UNIT_NAME,
								item.ITEM_NAME,
								stock_item.ITEM_ID,
								stock_item.BALANCE,
								stock_item.DATE
									from stock_item 
									left join unit on stock_item.LOC_ID = unit.LOC_ID
									left join item on stock_item.ITEM_ID = item.ITEM_ID 
									where stock_item.LOC_ID = '$unit' AND stock_item.ITEM_ID = '$itemID'");
		}
		while($stock = mysql_fetch_array($cekstock)){
			$tgl = explode("-",$stock['DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$stock[UNIT_NAME]</td>
					<td>$stock[ITEM_ID]</td>
					<td>$stock[ITEM_NAME]</td>
					<td>$stock[BALANCE]</td>
					<td>$tanggal</td>
				</tr>";
				$sum += $stock['BALANCE'];
			}
		echo"
				<tr>
					<td colspan=3>Jumlah</td>
					<td>$sum</td>
					<td></td>
				</tr>
		</table>
		";
	break;

	case "production";
		if ($_GET['id'] == 'nomorprod'){
			$srch = $_POST['search'];
					
			$cekprod = mysql_query("select production.PROD_ID, production.PROD_DATE, item.ITEM_NAME from production 
			inner join production_detail on production_detail.PROD_ID = production.PROD_ID
			left join item on item.ITEM_ID = production_detail.BAHAN_ID  where production.PROD_ID LIKE '%$srch%'");
			
			echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Production</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=production&id=nomorprod' id='formSearch1' onsubmit='return searchfunc()'>
							<button type='submit'><i class='fa fa-search'></i></button>
							<input type='text' placeholder='PROD No..' name='search' id='srch'>
							</form>
						</div>
						<div class='search-container'>
								<form method=POST action='albatsiq.php?module=search&act=production&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
								<button type='submit'><i class='fa fa-search'></i></button>
								<input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
								</form>
						</div>
					</div>
					<h3><a href=albatsiq.php?module=inventory&act=productionNew class='link'>New</a></h3>	
					<h3><a href=albatsiq.php?module=inventory&act=productionList class='link'>Back</a></h3>
				</div>
				
				
				<table width='100%'>
				<tr>
					<th width='30%'> Production No. </th>
					<th width='20%'> Bahan Dasar </th>
					<th width='40%'> Date</th>
					<th width='10%'></th>
				</tr>";
			
				while($prod = mysql_fetch_array($cekprod)){
					$tgl = explode("-",$prod['PROD_DATE']);
					$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
					echo"
					<tr>
					<td>$prod[PROD_ID]</td>
					<td>$prod[ITEM_NAME]</td>
					<td>$tanggal</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=production&id=$prod[PROD_ID]><b>View</b></a></td>
				</tr>";
				}
				
				echo"
				</table>
			";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			
			$cekprod = mysql_query("select production.PROD_ID, production.PROD_DATE, item.ITEM_NAME from production 
			inner join production_detail on production_detail.PROD_ID = production.PROD_ID
			   left join item on item.ITEM_ID = production_detail.BAHAN_ID  where production.PROD_DATE = '$tanggal'");
			
			echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Production</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=production&id=nomorprod' id='formSearch1' onsubmit='return searchfunc()'>
						<button type='submit'><i class='fa fa-search'></i></button>
						<input type='text' placeholder='PROD No..' name='search' id='srch'>
						</form>
					</div>
					<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=production&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							<button type='submit'><i class='fa fa-search'></i></button>
							<input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=productionNew class='link'>New</a></h3>	
				<h3><a href=albatsiq.php?module=inventory&act=productionList class='link'>Back</a></h3>
				
			</div>
				
				
			<table width='100%'>
			<tr>
				<th width='30%'> Production No. </th>
				<th width='20%'> Bahan Dasar </th>
				<th width='40%'> Date</th>
				<th width='10%'></th>
			</tr>";
			
				while($prod = mysql_fetch_array($cekprod)){
					$newtgl = explode("-",$prod['PROD_DATE']);
					$newtanggal = date("$newtgl[2]-$newtgl[1]-$newtgl[0]");
					echo"
					<tr>
					<td>$prod[PROD_ID]</td>
					<td>$prod[ITEM_NAME]</td>
					<td>$newtanggal</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=production&id=$prod[PROD_ID]><b>View</b></a></td>
				</tr>";
				}
				
				echo"
				</table>
			";
		}
			
	break;

	case"distribusi";
		if ($_GET['id'] == 'nomordist'){
			$srch = $_POST['search'];
			echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Distribusi</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=distribusi&id=nomordist' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='DIST No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=distribusi&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=distribusiNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='20%'> Distribusi No. </th>
					<th width='10%'> Date </th>
                    <th width='20%'> Note </th>
                    <th width='20%'> From Unit </th>
                    <th width='20%'> To Unit </th>
					<th width='10%'></th>
				</tr>";
       $cekdist = mysql_query("select it.TRANSACTION_NO,it.TRANSACTION_DATE,it.NOTE,unit.UNIT_NAME,u2.UNIT_NAME AS FROM_UNIT
                                from item_transaction it
                                left join unit on unit.LOC_ID = it.TO_LOC_ID 
                                left join unit u2 on u2.LOC_ID = it.FROM_LOC_ID 
                                where it.TRANSACTION_CODE = '004' AND it.TRANSACTION_NO LIKE '%$srch%'");
		while($dist = mysql_fetch_array($cekdist)){
			$tgl = explode("-",$dist['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$dist[TRANSACTION_NO]</td>
					<td>$tanggal</td>
                    <td>$dist[NOTE]</td>
                    <td>$dist[FROM_UNIT]</td>
                    <td>$dist[UNIT_NAME]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=distribusi&id=$dist[TRANSACTION_NO]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Distribusi</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=distribusi&id=nomordist' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='DIST No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=distribusi&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=distribusiNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='20%'> Distribusi No. </th>
					<th width='10%'> Date </th>
                    <th width='20%'> Note </th>
                    <th width='20%'> From Unit </th>
                    <th width='20%'> To Unit </th>
					<th width='10%'></th>
				</tr>";
       $cekdist = mysql_query("select it.TRANSACTION_NO,it.TRANSACTION_DATE,it.NOTE,unit.UNIT_NAME,u2.UNIT_NAME AS FROM_UNIT
                                from item_transaction it
                                left join unit on unit.LOC_ID = it.TO_LOC_ID 
                                left join unit u2 on u2.LOC_ID = it.FROM_LOC_ID 
                                where it.TRANSACTION_CODE = '004' AND it.TRANSACTION_DATE = '$tanggal'");
		while($dist = mysql_fetch_array($cekdist)){
			$tgl = explode("-",$dist['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$dist[TRANSACTION_NO]</td>
					<td>$tanggal</td>
                    <td>$dist[NOTE]</td>
                    <td>$dist[FROM_UNIT]</td>
                    <td>$dist[UNIT_NAME]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=distribusi&id=$dist[TRANSACTION_NO]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>";
		}
	break;
	
	case"salesReturn";
		if ($_GET['id'] == 'nomorret'){
			$srch = $_POST['search'];

			echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Sales Return</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=salesReturn&id=nomorret' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Return No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=salesReturn&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=sales&act=salesRetNew class='link'>New</a></h3>
				<h3><a href=albatsiq.php?module=sales&act=salesRetList class='link'>Back</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Return No. </th>
					<th width='20%'> Return Date </th>
					<th width='40%'> Total Amount </th>
					<th width='10%'></th>
				</tr>
			";
			
		$cekret = mysql_query("select * from sales where SALES_ID LIKE '%$srch%' AND SALES_CODE = '002'");
		while($ret = mysql_fetch_array($cekret)){
			$tgl = explode("-",$ret['SALES_DATE']);
            $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
            $chg = $ret['CHARGE_AMOUNT']*-1;
            $amt = number_format($chg, 2, '.', ',');
			echo"
				<tr>
					<td>$ret[SALES_ID]</td>
					<td>$tanggal</td>
					<td>Rp. $amt</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=salesReturn&id=$ret[SALES_ID]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		} else if ($_GET['id'] == 'dates'){
			$srch = $_POST['tgl'];
			$tgl = explode("-",$srch);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");

			echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Sales Return</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=salesReturn&id=nomorret' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Return No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=salesReturn&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=sales&act=salesRetNew class='link'>New</a></h3>
				<h3><a href=albatsiq.php?module=sales&act=salesRetList class='link'>Back</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Return No. </th>
					<th width='20%'> Return Date </th>
					<th width='40%'> Total Amount </th>
					<th width='10%'></th>
				</tr>
			";
			
			
		$cekret = mysql_query("select * from sales where SALES_DATE = '$tanggal' AND SALES_CODE = '002'");
		while($ret = mysql_fetch_array($cekret)){
			$tgl = explode("-",$ret['SALES_DATE']);
            $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
            $chg = $ret['CHARGE_AMOUNT']*-1;
            $amt = number_format($chg, 2, '.', ',');
			echo"
				<tr>
					<td>$ret[SALES_ID]</td>
					<td>$tanggal</td>
					<td>Rp. $amt</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=salesReturn&id=$ret[SALES_ID]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		}
	break;
} 
?>