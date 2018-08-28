<?php
include "config/koneksi.php";
function anti_injection($data){
  $filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$username 	= anti_injection($_POST['username']);
$password = strtolower($_POST['password']);
$pass   = anti_injection(md5($password));

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
  include "gagal_login.php";
}
else{
$login=mysql_query("SELECT * FROM user WHERE USERNAME='$username' AND PASSWORD='$pass' AND BLOKIR='N'");
$ketemu=mysql_num_rows($login);
$r=mysql_fetch_array($login);


// Apabila username dan password ditemukan
if ($ketemu > 0){
  session_start();
  include "timeout.php";

  $_SESSION[username]     = $r[USERNAME];
  $_SESSION[namalengkap]  = $r[NAMA_LENGKAP];
  $_SESSION[passuser]     = $r[PASSWORD];
  $_SESSION[leveluser]    = $r[LEVEL];
  $_SESSION[unit]         = $r[UNIT_ID];
  
  // session timeout
  $_SESSION[login] = 1;
  timer();

	$sid_lama = session_id();
	
	session_regenerate_id();

	$sid_baru = session_id();

  mysql_query("UPDATE user SET ID_SESSION='$sid_baru' WHERE USERNAME='$username'");
  header('location:albatsiq.php?module=home');
}
else{
   include "gagal_login.php";
}
}
?>
