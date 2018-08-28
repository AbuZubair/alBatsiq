<?php
	include "../../config/koneksi.php";

    $php_var = $_GET['q'];
	$unit = $_GET['unit'];
     
	$itemID = explode(" ",$php_var);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select SATUAN_BELI,SATUAN_JUAL,KONVERSI,HARGA_JUAL from item where ITEM_ID='$itemIDs'");
	$row = mysql_fetch_array($ceksatuan);
	$cekstock = mysql_query("select BALANCE from stock_item where ITEM_ID ='$itemIDs' AND LOC_ID = '$unit'");
	$rowstock = mysql_fetch_array($cekstock);
			
	$result = array("".$row['SATUAN_BELI']."","".$row['SATUAN_JUAL']."","".$row['KONVERSI']."","".$row['HARGA_JUAL']."","".$rowstock['BALANCE']."");
	echo json_encode($result);
?>