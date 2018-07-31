<?php
	include "../../config/koneksi.php";

	if (isset($_GET['q'])) $php_var = $_GET['q'];
	$cekpor = mysql_query("Select item_transaction_detail.ITEM_ID, 
												item.ITEM_NAME,
												item_transaction_detail.QUANTITY, 
												item_transaction_detail.SATUAN, 
												item_transaction_detail.KONVERSI, 
												item_transaction_detail.HARGA
												from item_transaction_detail 
												inner join item on item_transaction_detail.ITEM_ID = item.ITEM_ID 
												where item_transaction_detail.TRANSACTION_NO='$php_var'
												AND item_transaction_detail.ITEM_ID NOT IN ( select ITEM_ID from item_transaction_detail where REFERENCE_NO = '$php_var' )
												");
			
	$memberdata = array();
	
	while($row = mysql_fetch_array($cekpor)) {
    $memberdata[] = array(
        'item_ID'  => $row['ITEM_ID'], // we access the firstname at the current index
        'item_name' => $row['ITEM_NAME'],
        'quantity' => $row['QUANTITY'],
        'satuan' => $row['SATUAN'],
		'konversi' => $row['KONVERSI'],
        'harga' => $row['HARGA']
    );
}

	/*$result = array (
				while($row = mysql_fetch_array($cekpo)){
					array("".$row['ITEM_ID']." - ".$row['ITEM_NAME']."","".$row['QUANTITY']."","".$row['SATUAN']."","".$row['KONVERSI']."","".$row['HARGA'].""),
				}
			);*/
				
	
	echo json_encode($memberdata);

?>