
<?php
session_start();
error_reporting(0);
include "timeout.php";
include "config/koneksi.php";
//include "modul/mod_cuti/hitungcuti.php";
//$peg = mysql_query("SELECT * FROM tbl_pegawai WHERE NIK ='".$_SESSION['nik']."'");
//	$r = mysql_fetch_array($peg);

if($_SESSION[login]==1){
	if(!cek_login()){
		$_SESSION[login] = 0;
	}
}
if($_SESSION[login]==0){
  header('location:logout.php?act=timeout');
}
else{
if (empty($_SESSION['username']) AND empty($_SESSION['passuser']) AND $_SESSION['login']==0){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{
?>
<script>

</script>

<html>
<head>
<title>AlBatsiq</title>
<link href='style.css' rel='stylesheet' type='text/css'>
<link href='' rel='shortcut icon'>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" type="text/css" href="css/css/all.css">
  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );

	$(document).ready(function() {
    $('.searchSelect').select2();
});
  
  </script>


</head>
<body>
	
	<div id="container">
		<div id="wrapper">
			<div id="header1">
				<h1>alBatsiq</h1>
			</div>
			
			<div id="header">
			<?php
					date_default_timezone_set('Asia/Jakarta');
				
					//ECHO $orgr[JABATAN];
					echo "<div id='keterangan'>Welcome, $nbsp".$_SESSION['namalengkap']."<br><br>
							<a href=albatsiq.php?module=user&act=gantipassword>GANTI PASSWORD</a> | <a href=logout.php?act=logout >LOGOUT</a> 
							&nbsp
						  </div>
			
	
			</div>
		</div>
		
		
			<div class='menu'>
				<div class='dropdown'>
					<a href=albatsiq.php?module=home>Home</a>
				</div>";
			if ($_SESSION['leveluser'] == 'admin' || $_SESSION['unit'] == 'B01'){
				echo"
				<div class='dropdown'>
				  <button class='dropbtn'>Sales</button>
					<div class='dropdown-content'>
						<a href=albatsiq.php?module=sales&act=salesNew>Sales</a>
						<a href=albatsiq.php?module=sales&act=salesRetList>Sales Return</a>
					</div>
				</div>
						";	
			}	
				echo"
				<div class='dropdown'>
				  <button class='dropbtn'>Inventory</button>
					<div class='dropdown-content'>";
					if ($_SESSION['leveluser'] == 'admin' ||  $_SESSION['leveluser'] == 'kepala gudang' ||  $_SESSION['leveluser'] == 'kepala toko') {
						echo"
						<div class='dropdown-submenu'>
							<a class='dropbtn2' tabindex='-1' href='#'>Transaksi Item</a>
								<div class='dropdown-content-sub'>
									<a href=albatsiq.php?module=inventory&act=purchaseOrderList>Purchase Order</a>
									<a href=albatsiq.php?module=inventory&act=purchaseOrderReceiveList>Purchase Order Receive</a>
									<a href=albatsiq.php?module=inventory&act=purchaseOrderReturnList>Purchase Order Return</a>
								</div>
						</div>
						<a href=albatsiq.php?module=inventory&act=distribusiList>Distribusi</a>";
					}
					if ($_SESSION['leveluser'] == 'admin' ||  $_SESSION['leveluser'] == 'kepala gudang') {
						echo"
						<a href=albatsiq.php?module=inventory&act=productionList>Production</a>";
				  	}
						echo"
				  		<div class='dropdown-submenu'>
							<a class='dropbtn2' tabindex='-1' href='#'>Stock</a>
								<div class='dropdown-content-sub2'>
									<a href=albatsiq.php?module=inventory&act=stockInformation>Stock Information</a>";
									if ($_SESSION['leveluser'] == 'admin' ||  $_SESSION['leveluser'] == 'kepala gudang') {
									echo"
										<a href=albatsiq.php?module=inventory&act=stockAdjustList>Stock Adjustment</a>";
									}
									echo"
								</div>
						</div>		
					</div>
				</div>";
				
				if ($_SESSION['leveluser'] == 'admin' ||  $_SESSION['leveluser'] == 'kepala gudang') {
							echo"
							<div class='dropdown'>
							  <button class='dropbtn'>Master</button>
								<div class='dropdown-content'>
									<a href=albatsiq.php?module=master&act=masterunitList>Unit</a>
									<a href=albatsiq.php?module=master&act=masteritemList>Item Product</a>
									<a href=albatsiq.php?module=master&act=masteritemTariffList>Item Tariff</a>
								</div>
							</div>";
				}

				if ($_SESSION['leveluser'] == 'admin') {
							echo"
							<div class='dropdown'>
							  <button class='dropbtn'>Control User</button>
								<div class='dropdown-content'>
									<a href=albatsiq.php?module=control&act=tambahuser>Tambah User</a>
									<a href=albatsiq.php?module=control&act=edituser>Edit User</a>
									<a href=albatsiq.php?module=control&act=resetpassword>Reset Password</a>
								</div>
							</div>

							<div class='dropdown'>
								<a href=albatsiq.php?module=report&act=report>Report</a>
							</div>
							
	
							";
				}
			echo"	
			</div>";
				
				
	?>	
		<div id="content">
			
				<?php include "content.php"; ?>
				<br>
			
			
		</div>
		
	</div>
				  
	
	

</body>
</html>
<?php
}
}
?>
