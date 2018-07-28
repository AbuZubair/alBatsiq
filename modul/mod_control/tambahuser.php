<script>
	function confirmPass() {
    var pass1 = document.getElementById("pass1").value;
    var pass2 = document.getElementById("pass2").value;
    var ok = false;
    if (pass1 == pass2) {
        //alert("Passwords Do not match");
        document.getElementById("pass1").style.borderColor = "#E34234";
        document.getElementById("pass2").style.borderColor = "#E34234";
        ok = true;
    } 
    else {
        alert("Passwords Tidak Sama!!!");
    }
    return ok;
}

 
function confirmUname() {
	alert("Username sudah digunakan");
	document.getElementById("uname").value  = "<?php echo $_POST['username'] ?>";
	document.getElementById("namalkp").value  = "<?php echo $_POST['namalengkap'] ?>";
	var level = "<?php echo $_POST['lvl'] ?>";
	if (level == "user") {
	  document.getElementById('lvl1').checked = true;
	} else if (level == "admin") {
	   document.getElementById("lvl2").checked = true;
	}
	document.getElementById("pass1").value = "<?php echo $_POST['password'] ?>";
    document.getElementById("pass2").value = "<?php echo $_POST['password1'] ?>";
}



</script>
<?php

echo "
<div id='titlepage'>
	<div class = 'title-container'>
		<div class = 'txttitle'>
			<h1>Tambah User</h1>
		</div>
	</div>
</div>


			
	<form method=POST action='albatsiq.php?module=control&act=tambahuser&id=add' onsubmit='return confirmPass()' id='form1'>
		<table width='40%'>
			<tr>
				<td class='titlechild'>Username</td>
				<td><input type='text' name='username' id='uname' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Nama Lengkap</td>
				<td><input type='text' name='namalengkap' size='30' id='namalkp' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Level</td>
				<td><input type='radio' name='lvl' id='lvl1' value='user'> User
					<input type='radio' name='lvl' id='lvl2' value='admin'> Admin<br>
			</tr>
			<tr>
				<td class='titlechild'>Password</td>
				<td><input type='password' name='password' id='pass1' pattern='.{5,}' title='Must contain at least 5 or more characters' required></td>
			</tr>
			<tr>
				<td class='titlechild'>Konfirmasi Password</td>
				<td><input type='password' name='password1' id='pass2' pattern='.{5,}' title='Must contain at least 5 or more characters' required></td>
			</tr>
			
		</table>
		</form>
		<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>
	";
		
	
		
	
	$id = $_GET['id'];
		if ($id == 'add'){
			$uname = strtolower ($_POST['username']);
			$password = strtolower($_POST['password']);
			$pass=md5($password);
			$cekdulu = mysql_query("select * from user where USERNAME = '$uname'");
			$rowcek = mysql_num_rows($cekdulu);
			
			if($rowcek == 0){
			mysql_query("insert into user(USERNAME,PASSWORD,NAMA_LENGKAP,LEVEL) values ('$uname','$pass', '$_POST[namalengkap]','$_POST[lvl]') ");
			echo "
			<script type='text/javascript'>
				alert('User berhasil ditambahkan');
				setTimeout(function () { window.location.href = 'albatsiq.php?module=control&act=tambahuser'; }, 1000);
			  </script>
			  ";
			} else {
				echo '<script type="text/javascript"> confirmUname(); </script>';
			}
		} 
?>

