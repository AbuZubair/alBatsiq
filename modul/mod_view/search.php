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
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpo' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='PO No..' name='search' id='srch5'>
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

					echo"
						<tr>
							<td>$por[0]</td>
							<td>$por[2]</td>
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
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpo' id='formSearch1' onsubmit='return searchfunc5()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='PO No..' name='search' id='srch5'>
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
	
} 
?>