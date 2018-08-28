<?php
	include "../../config/koneksi.php";

    if (isset($_GET['q'])) $php_var = $_GET['q'];
     
	$itemID = explode(" ",$php_var);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL,KONVERSI from item where ITEM_ID='$itemIDs'");
	$row = mysql_fetch_array($ceksatuan);
			
	$result = array("".$row['SATUAN_BELI']."","".$row['SATUAN_JUAL']."","".$row['KONVERSI']."");
	echo json_encode($result);
?>