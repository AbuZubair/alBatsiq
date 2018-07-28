<?php
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order Receive</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=nomorpor' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Received No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrderReceive&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Receive No. </th>
					<th width='20%'> Received Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekpor = mysql_query("select * from item_transaction where TRANSACTION_CODE = '002' ORDER BY item_transaction.TRANSACTION_NO DESC");
		while($por = mysql_fetch_array($cekpor)){
			$tgl = explode("-",$por['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$por[0]</td>
					<td>$tanggal</td>
					<td>$por[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrderReceive&id=$por[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
?>