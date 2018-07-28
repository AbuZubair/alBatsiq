<?php
	echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitNew class='link'>New</a></h3>
			</div>
			
			<table width='100%'>
				<tr>
					<th width='10%'> ID Unit </th>
					<th width='50%'> Nama Unit </th>
					<th width='30%'> Lokasi Inventori </th>
					<th width='10%'> </th>
				</tr>
				";
				
			$cekunit = mysql_query("select * from unit");
						
			while($unit = mysql_fetch_array($cekunit)){
			echo"
				<tr>
					<td>$unit[0]</td>
					<td>$unit[1]</td>
					<td>$unit[3]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=master&act=masterunitEdit&id=$unit[0]><b>Edit</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
?>