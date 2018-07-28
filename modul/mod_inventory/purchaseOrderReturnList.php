<?php
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Return</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=nomorpor' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Received No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReturn&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Return No. </th>
					<th width='20%'> Return Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekret = mysql_query("select * from item_transaction where TRANSACTION_CODE = '003' ORDER BY item_transaction.TRANSACTION_NO DESC");
		while($ret = mysql_fetch_array($cekret)){
			$tgl = explode("-",$ret['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$ret[0]</td>
					<td>$tanggal</td>
					<td>$ret[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReturn&id=$ret[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
?>