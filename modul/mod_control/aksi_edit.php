<?php
	include "../../config/koneksi.php";
	$uname = $_POST['username'];
	$nama =  $_POST['namalengkap'];
	$level = $_POST['lvl'];
	
	mysql_query("update user set NAMA_LENGKAP = '$nama', LEVEL = '$level' where USERNAME = '$uname'");
	
	header('location:../../albatsiq.php?module=control&act=edituser&id=success');
?>