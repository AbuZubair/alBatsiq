<?php
	echo"
				<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Item Tariff</h1>
						</div>
						
						<div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=itemname' id='formSearch1' onsubmit='return searchfunc()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Nama Item..' name='search' id='srch'>
							</form>
						 </div>
						 <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=itemTariffList&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
					</div>
					<h3><a href=albatsiq.php?module=master&act=masteritemTariffNew class='link'>New</a></h3>
				</div>
							
				
				<table width='100%'>
					<tr>
						<th width='10%'> ID Item </th>
						<th width='40%'> Nama Item </th>
						<th width='20%'> Harga Jual </th>
						<th width='20%'> Date </th>
						<th width='10%'> </th>
					</tr>
					";
					
				$cekitemtariff = mysql_query("select 
										item_tariff.ITEM_ID,
										item.ITEM_NAME,
										item_tariff.HARGA_JUAL,
										item_tariff.DATE
										from item_tariff 
										inner join item on item.ITEM_ID = item_tariff.ITEM_ID order by DATE DESC, TARIFF_ID DESC");
				
							
				while($itemtariff = mysql_fetch_array($cekitemtariff)){
					$hrg = number_format($itemtariff[2], 2, '.', ',');
					$tgl = explode("-",$itemtariff['DATE']);
					$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
				echo"
					<tr>
						<td>$itemtariff[0]</td>
						<td>$itemtariff[1]</td>
						<td>Rp. $hrg</td>
						<td>$tanggal</td>
						<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=masteritemTariff&id=$itemtariff[0]&hj=$itemtariff[2]><b>View</b></a></td>
					</tr>";
				}
				echo"
				</table>
				
				";
?>