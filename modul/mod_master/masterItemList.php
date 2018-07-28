<?php
	echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Item Product</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=itemList&id=itemname' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='Search Nama Item..' name='search' id='srch'>
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
				
			$cekitem = mysql_query("select * from item");
						
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
?>