
<?php
include "config/koneksi.php";
date_default_timezone_set('Asia/Jakarta');
// Bagian Home
if ($_GET['module']=='home'){
  if ($_SESSION['leveluser']=='user'){
    echo "
    <div class='welcomepage'>
      <h1> كُلُّ نَفْسٍ ذَائِقَةُ الْمَوْتِ ۖ ثُمَّ إِلَيْنَا تُرْجَعُونَ </h1>
      <h2>“Setiap yang bernyawa akan merasakan mati. <br>
      Kemudian hanya kepada Kami kamu dikembalikan” [al-‘Ankabût/29:57]</h2>
    </div>
      
    ";
  }
  elseif ($_SESSION['leveluser']=='admin'){
  echo "
  <div class='welcomepage'>
    <h1>…يَا ابْنَ آدَمَ إِنَّكَ لَوْ أَتَيْتَنِي بِقُرَابِ اْلأَرْضِ خَطَايَا ثُمَّ لَقِيْتَنِي لاَ تُشْرِكُ بِي شَيْئاً لأَتَيْتُكَ بِقُرَابِهَا مَغْفِرَةً.</h1>
    <h2>‘…Wahai bani Adam, seandainya engkau datang kepada-Ku dengan dosa sepenuh bumi,<br>
     sedangkan engkau ketika mati tidak menyekutukan Aku sedikit pun juga,<br>
      pasti Aku akan berikan kepadamu ampunan sepenuh bumi pula.’<br>
      HR. At-Tirmidzi (no. 3540), ia berkata, “Hadits hasan gharib.”   
    </h2>
  </div>";
 	}
} 

// Bagian Sales
elseif ($_GET['module']=='sales'){
  include "modul/mod_sales/sales.php";
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

// Bagian Report
elseif ($_GET['module']=='report'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_report/report.php";
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

// Apabila modul tidak ditemukan
else{
  echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
}
?>
