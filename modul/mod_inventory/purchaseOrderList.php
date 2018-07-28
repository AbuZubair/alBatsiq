<?php
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=nomorpo' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='PO No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Purchase Order No. </th>
					<th width='20%'> Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekpo = mysql_query("select * from item_transaction where TRANSACTION_CODE = '001' ORDER BY item_transaction.TRANSACTION_NO DESC");
		while($po = mysql_fetch_array($cekpo)){
			$tgl = explode("-",$po['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$po[0]</td>
					<td>$tanggal</td>
					<td>$po[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$po[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
?>