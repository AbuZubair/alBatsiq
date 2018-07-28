
<?php
include "config/koneksi.php";
date_default_timezone_set('Asia/Jakarta');
// Bagian Home
if ($_GET['module']=='home'){
  if ($_SESSION['leveluser']=='user'){
		echo "<h1>VISI</h1> 
			Menjadi Rumah Sakit yang memenuhi kebutuhan keluarga dalam masyarakat secara holistik, dengan layanan tepat dan profesional
			<h1>MISI</h1> 
			Meningkatkan mutu pelayanan kesehatan yang memenuhi kaidah  keselamatan pasien
			<h1>&nbsp</h1>";
  }
  elseif ($_SESSION['leveluser']=='admin'){
  echo "
          <p>Hai selamat datang di halaman Administrator .<br> 
          Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola website. </p>";
 	}
} 

// Bagian User
elseif ($_GET['module']=='user'){
    include "modul/mod_user/useres.php";
}

// Bagian View
elseif ($_GET['module']=='view'){
    include "modul/mod_view/view.php";
}

// Bagian Search
elseif ($_GET['module']=='search'){
    include "modul/mod_view/search.php";
}

// Bagian Control
elseif ($_GET['module']=='control'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_control/usercontrol.php";
  }
}

// Bagian Master
elseif ($_GET['module']=='master'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_master/master.php";
  }
}

// Bagian Inventory
elseif ($_GET['module']=='inventory'){
    include "modul/mod_inventory/inventory.php";
}

// Bagian Cuti
elseif ($_GET['module']=='cuti'){
  if ($_SESSION['leveluser']=='user'){
    include "modul/mod_cuti/cuti.php";
  }
}

// Bagian Shift
elseif ($_GET['module']=='shift'){
  if ($_SESSION['leveluser']=='user'){
    include "modul/mod_shift/shift.php";
  }
}

// Bagian Absen
elseif ($_GET['module']=='absen'){
  if ($_SESSION['leveluser']=='user'){
    include "modul/mod_absen/absen.php";
  }
}

// Bagian Lembur
elseif ($_GET['module']=='lembur'){
  if ($_SESSION['leveluser']=='user'){
    include "modul/mod_lembur/lembur.php";
  }
}

// Apabila modul tidak ditemukan
else{
  echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
}
?>
