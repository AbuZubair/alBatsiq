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

function confirmOldPass() {
	alert("Password lama salah!!!");
	document.getElementById("pass0").value = "<?php echo $_POST['oldpassword'] ?>";
	document.getElementById("pass1").value = "<?php echo $_POST['password'] ?>";
    document.getElementById("pass2").value = "<?php echo $_POST['password1'] ?>";
}

</script>

<div id="titlepage">
	<h1>Ganti Password</h1>
</div>

<?php
	echo "<form method=POST action='albatsiq.php?module=user&act=gantipassword&id=gantipwd' onsubmit='return confirmPass()' id='form2'>
		<table>
			<tr>
				<td width='40%' style='text-align:right;'>Password lama <input type='hidden' value='".$_SESSION['username']."' name='username'></td>
				<td width='60%'><input type='password' name='oldpassword' id='pass0' required>
			</tr>
			<tr>
				<td width='40%' style='text-align:right;'>Password baru <input type='hidden' value='".$_SESSION['username']."' name='username'></td>
				<td width='60%'><input type='password' name='password' id='pass1' required>
			</tr>
				<td width='40%' style='text-align:right;'>Konfirmasi Password</td>
				<td width='60%'><input type='password' name='password1' id='pass2' required>
			</tr>
		</table>
		</form>
		
		<button  type='submit' value='Simpan' form='form2' class='button' style='background-color: #008CBA;'>Submit</button>
	";
	
	$id = $_GET['id'];
		if ($id == 'gantipwd'){
			$uname = strtolower ($_POST['username']);
			$oldpassword = strtolower($_POST['oldpassword']);
			$password = strtolower($_POST['password']);
			$oldpass = md5($oldpassword);
			$pass=md5($password);
			$cekdulu = mysql_query("select * from user where USERNAME = '$uname'");
			$rowcek =  mysql_fetch_array($cekdulu);
			
			if($rowcek['PASSWORD'] == $oldpass) {
				mysql_query("UPDATE user SET PASSWORD = '$pass' WHERE USERNAME = '$_POST[username]'");
				echo "
				<script type='text/javascript'>
					alert('Password berhasil dirubah');
					setTimeout(function () { window.location.href = 'index.php'; }, 100);
				  </script>
				  ";
			} else {
				echo '<script type="text/javascript"> confirmOldPass(); </script>';
			}
		} 
?>