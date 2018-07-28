<?php
	echo "	
		<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Edit Unit</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masterunitList class='link'>Back</a></h3>
			</div>
		";
		
		
		$id = $_GET['id'];
		$queryunit = mysql_query("select * from unit where UNIT_ID = '$id'");
		$rowunit = mysql_fetch_array($queryunit);
		echo"
			<form method=POST action='modul/mod_master/aksi_master.php?act=editUnit' id='form2'>
					<table width='50%'>
						<tr>
							<td class='titlechild'>ID Unit</td>
							<td><b>$rowunit[0]</b></td>
						</tr>
						<tr>
							<td class='titlechild'> Nama Unit <input type='hidden' value='$rowunit[0]' name='unitid'></td>
							<td><input type='text' value='$rowunit[1]' name='unitname' id='unitname' required></td>
						</tr>
						<tr>
							<td class='titlechild'>ID Location</td>
							<td><b>$rowunit[2]</b></td>
						</tr>	
						<tr>
							<td class='titlechild'> Nama Location <input type='hidden' value='$rowunit[2]' name='locids'></td>
							<td><input type='text' value='$rowunit[3]' name='locname' id='locname' required></td>
						</tr>
					</table>
			</form>
			<button  type='submit' value='Simpan' form='form2' class='button' style='background-color: #008CBA;'>Edit</button>
			";
?>