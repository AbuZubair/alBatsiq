<!DOCTYPE html>
<html >
<head>
  <title>LOGOUT </title>
<link href='style.css' rel='stylesheet' type='text/css'>
</head>

<body>
 <div class="body"></div>
		<div class="header">
			<div>alBatsiq</div>
		</div>
		<br>
		
		<div class="logout">
		
		<?php
		$act = $_GET['act'];
		
		if ($act == 'gantipassword'){
			session_start();
			session_destroy();
			echo "Password berhasil diganti. silakan login ulang sistem EHRD";
			echo "</br><a href=index.php><b>LOGIN</b></a><br>";
		} else if ($act == 'logout') {
			session_start();
			session_destroy();
			echo "Anda telah sukses keluar sistem";
			echo "</br><a href=index.php><b>LOGIN</b></a><br>";
		} else if ($act == 'timeout') {
			session_start();
			session_destroy();
			echo "sesi anda sudah habis<br>silakan login kembali";
			echo "</br><a href=index.php><b>LOGIN</b></a><br>";
		}
		?>
		
		</div>
	
</body>
</html>
