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
</script>

<?php

include "config/koneksi.php";
$query = "SELECT USERNAME, NAMA_LENGKAP FROM user where USERNAME != '".$_SESSION['username']."'";
$result = mysql_query($query) or die(mysql_error()."[".$query."]");

echo "
<div id='titlepage'>
	<div class = 'title-container'>
		<div class = 'txttitle'>
			<h1>Reset Password</h1>
		</div>
	</div>
</div>

<form method=POST action='albatsiq.php?module=control&act=resetpassword&id=reset' onsubmit='return confirmPass()' id='form1'>
	<table width='40%'>
		<tr>
			<td class='titlechild'>Username</td>
			<td>
				<select name='uname'>";
				while ($row = mysql_fetch_array($result))
					{
						echo" <option value='".$row['USERNAME']."'>".$row['NAMA_LENGKAP']."</option>";
					}   
				echo"					
				</select>
			</td>
		</tr>
		
		<tr>
			<td class='titlechild'>Password baru</td>
			<td><input type='password' name='password' id='pass1' required>
		</tr>
			<td class='titlechild'>Konfirmasi Password</td>
			<td><input type='password' name='password1' id='pass2' required>
		</tr>
	
	</table>
</form>
		<button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Submit</button>
	";
	
	$id = $_GET['id'];
		if ($id == 'reset'){
			$password = strtolower($_POST['password']);
			$pass=md5($password);
			
			mysql_query("UPDATE user SET PASSWORD = '$pass' WHERE USERNAME = '$_POST[uname]'");
			echo "
			<script type='text/javascript'>
				alert('Password berhasil dirubah');
			  </script>
			  ";
		} 

?>