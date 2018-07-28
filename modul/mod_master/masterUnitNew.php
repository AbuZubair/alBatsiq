<?php
	echo "		
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Tambah Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitList class='link'>Back</a></h3>
			</div>
		
		<form method=POST action='modul/mod_master/aksi_master.php?act=addUnit' id='form1'>
		<table width='50%'>
			<tr>
				<td class='titlechild'>ID Unit</td>
				<td><input type='text' name='unitid' size='5' id='unitid' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Nama Unit</td>
				<td><input type='text' name='unitname' size='50' id='unitname' required></td>
			</tr>
			<tr>
				<td class='titlechild'>ID Location</td>
				<td><input type='text' name='locid' size='5' id='locid' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Nama Location</td>
				<td><input type='text' name='locname' id='locname'required></td>
			</tr>			
		</table>
		</form>
		<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>
	";
?>