
<?php

include "config/koneksi.php";
$id = $_GET['id'];
	$querynames = mysql_query("select * from user where USERNAME = '$id'");
	$rownames = mysql_fetch_array($querynames);
	$cekunit = mysql_query("select * from unit");
	
echo"

<body onload='levelFunction()'>

<div id='titlepage'>
	<div class = 'title-container'>
		<div class = 'txttitle'>
			<h1>Edit User</h1>
		</div>
	</div>
</div>

		<form method=POST action='modul/mod_control/aksi_edit.php' id='form'>
				<table width='50%'>
					<tr>
						<td class='titlechild'> Username</td>
						<td><b>$rownames[USERNAME]</b></td>
					</tr>
					<tr>
						<td class='titlechild'> Nama Lengkap <input type='hidden' value='$rownames[USERNAME]' name='username'></td>
						<td><input type='text' value='$rownames[NAMA_LENGKAP]' name='namalengkap' id='namalengkap' required></td>
					</tr>
					<tr>
						<td class='titlechild'> User Role </td>
						<td><select name='userrule' id='userrule'>
							<option id='user' value='user'>User</option>
							<option id='kepala toko' value='kepala toko'>Kepala Toko</option>
							<option id='kepala gudang' value='kepala gudang'>Kepala Gudang</option>
							<option id='admin' value='admin'>Administrator</option>
						</select></td>
					</tr>	
					<tr>
						<td class='titlechild'>Unit</td>
						<td>
							<select name='locunit' width='20' id='locunit' required>";
							while ($row = mysql_fetch_array($cekunit))
								{
									echo" 
									<option style='display:none;' selected></option>
									<option id='".$row['UNIT_ID']."' value='".$row['UNIT_ID']."'>
										".$row['UNIT_NAME']."
									</option>";
								}   
							echo"					
							</select>
						</td>
					</tr>	
				</table>
		</form>
					
	<a href='albatsiq.php?module=control&act=edituser'><button type='button' class='button' style='background-color: #008CBA;'>Back</button></a>
	<button  type='submit' value='Simpan' form='form' class='button' style='background-color: #008CBA;'>Edit</button>";
?>

<script>
function levelFunction() {
	var level = "<?php echo $rownames['LEVEL'] ?>";
	document.getElementById(level).selected = true;	

	var unit = "<?php echo $rownames['UNIT_ID'] ?>";
	document.getElementById(unit).selected = true;		
}
</script>	
