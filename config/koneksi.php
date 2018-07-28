<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "bismillah";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");
date_default_timezone_set('Asia/Jakarta');

?>
