<script type="text/javascript">
	function my(){
		return confirm('Yakin ingin blokir?');
	}
	
	function searchfunc(){
	var cek =  document.getElementById("srch").value;
	var ok = false;
	if(cek == ''){
			return ok;
		} 
	} 
			
</script>

<?php

include "config/koneksi.php";


echo"
<div id='titlepage'>
	<div class = 'title-container'>
		<div class = 'txttitle'>
			<h1>Edit User</h1>
		</div>
	
		<div class='search-container'>
			<form method=POST action='albatsiq.php?module=search&act=editusers' id='form' onsubmit='return searchfunc()'>
				<input type='text' placeholder='Search Nama..' name='search' id='srch'>
				<button type='submit'><i class='fa fa-search'></i></button>
			</form>
		</div>
	</div>
</div>
";

$query = mysql_query("select * from user");
	echo"
				<table width='100%'>
					<tr>
						<th width='20%'> Username </th>
						<th width='50%'> Nama Lengkap </th>
						<th width='10%'> Level </th>
						<th width='20%' colspan='2'>  </th>
					</tr>";
	while($row = mysql_fetch_array($query)){
		echo"
			<tr>
				<td>$row[0]</td>
				<td>$row[2]</td>
				<td>$row[3]</td>";
				if ($row[4] == 'N'){
					echo"
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=control&act=edited&id=$row[0]><b>Edit</b></a></td>
					<td><a href='albatsiq.php?module=control&act=edituser&id=blokir&no=$row[0]' class='link' style='text-decoration: none;' onclick='return my()'><b>Blokir</b></a></td>
					";
				} else {
					echo"
					<td style='opacity:0.2;'><b>Edit</b></td>
					<td><a class='link' style='text-decoration: none;'href=albatsiq.php?module=control&act=edituser&id=aktif&no=$row[0]><b>Aktivasi</b></a></td>
					";
				}
				echo"
			</tr>";
		}					

			echo"
		</table>
		";



	$id = $_GET['id'];
		if ($id == 'success'){
			echo"
			<script type='text/javascript'>
				alert('User berhasil diupdate');
			</script>";
		}
		else if ($id == 'blokir'){
				$no = $_GET['no'];
				mysql_query("UPDATE user SET BLOKIR = 'Y' WHERE USERNAME = '$no'");
				echo "<body onload='myFunction()'></body>
				<script type='text/javascript'>
					setTimeout(function () { window.location.href = 'albatsiq.php?module=control&act=edituser'; }, 100);
				  </script>
				  ";
			}	
		else if ($id == 'aktif'){
				$no = $_GET['no'];
				mysql_query("UPDATE user SET BLOKIR = 'N' WHERE USERNAME = '$no'");
				echo "<body onload='myFunction()'></body>
				<script type='text/javascript'>
					setTimeout(function () { window.location.href = 'albatsiq.php?module=control&act=edituser'; }, 100);
				  </script>
				  ";
			}
?>

