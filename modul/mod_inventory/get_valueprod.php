<?php
	include "../../config/koneksi.php";

   $php_var = $_GET['q'];
   $loc = $_GET['p'];
     
	$itemID = explode(" ",$php_var);	
	$itemIDs = $itemID[0];	
	$ceksatuan = mysql_query("select item.SATUAN_BELI,item.SATUAN_JUAL,item.KONVERSI,stock_item.BALANCE from item left join stock_item on stock_item.ITEM_ID = item.ITEM_ID where item.ITEM_ID='$itemIDs' and stock_item.LOC_ID='$loc'");
    $row = mysql_fetch_array($ceksatuan);
    $balanceafter = $row['BALANCE'] * $row['KONVERSI'];
			
	$result = array("".$row['SATUAN_BELI']."","".$row['SATUAN_JUAL']."","".$row['KONVERSI']."","".$balanceafter."");
	echo json_encode($result);
?>