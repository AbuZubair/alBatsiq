
<?php

include "config/koneksi.php";
$id = $_GET['id'];
	$querynames = mysql_query("select * from user where USERNAME = '$id'");
	$rownames = mysql_fetch_array($querynames);
	
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
						<td><b>$rownames[0]</b></td>
					</tr>
					<tr>
						<td class='titlechild'> Nama Lengkap <input type='hidden' value='$rownames[0]' name='username'></td>
						<td><input type='text' value='$rownames[2]' name='namalengkap' id='namalengkap' required></td>
					</tr>
					<tr>
						<td class='titlechild'> Level </td>
						<td><input type='radio' name='lvl' id='lvl1' value='user'> User
							<input type='radio' name='lvl' id='lvl2' value='admin'> Admin<br>
					</tr>		
				</table>
		</form>
					
	<a href='albatsiq.php?module=control&act=edituser'><button type='button' class='button' style='background-color: #008CBA;'>Back</button></a>
	<button  type='submit' value='Simpan' form='form' class='button' style='background-color: #008CBA;'>Edit</button>";
?>

<script>
function levelFunction() {
	var level = "<?php echo $rownames[3] ?>";
	if (level == "user") {
	  document.getElementById('lvl1').checked = true;
	} else if (level == "admin") {
	   document.getElementById("lvl2").checked = true;
	}
}
</script>	
