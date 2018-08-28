<?php
	include "../../config/koneksi.php";

	if (isset($_GET['q'])) $php_var = $_GET['q'];
	//$unit = $_GET['unit'];
	$ceksl = mysql_query("Select sales_detail.ITEM_ID, 
												item.ITEM_NAME,
												sales_detail.QUANTITY, 
												sales_detail.SATUAN, 
												sales_detail.HARGA_JUAL,
                                                sales_detail.DISCOUNT_AMOUNT,
                                                sales_detail.TOTAL_AMOUNT,
												sales.FROM_LOC_ID,
												sales.CHARGE_AMOUNT
												from sales_detail 
												left join item on sales_detail.ITEM_ID = item.ITEM_ID
												left join sales on  sales_detail.SALES_ID = sales.SALES_ID
												where sales_detail.SALES_ID='$php_var'
												AND sales_detail.ITEM_ID NOT IN ( select sales_detail.ITEM_ID from sales_detail inner join sales on sales.SALES_ID=sales_detail.SALES_ID where sales.REFERENCE_ID = '$php_var' )
												");		
	$memberdata = array();
	
	while($row = mysql_fetch_array($ceksl)) {
    $cekstock = mysql_query("select BALANCE from stock_item where ITEM_ID ='$row[ITEM_ID]' AND LOC_ID = '$row[FROM_LOC_ID]'");
	$rowstock = mysql_fetch_array($cekstock);
    $memberdata[] = array(
        'item_ID'  => $row['ITEM_ID'], // we access the firstname at the current index
        'item_name' => $row['ITEM_NAME'],
        'quantity' => $row['QUANTITY'],
        'satuan' => $row['SATUAN'],
        'harga' => $row['HARGA_JUAL'],
        'diskon' => $row['DISCOUNT_AMOUNT'],
        'total' => $row['TOTAL_AMOUNT'],
		'locid' => $row['FROM_LOC_ID'],
		'amt' => $row['CHARGE_AMOUNT'],
		'balance' => $rowstock['BALANCE']
		
    );
}

	/*$result = array (
				while($row = mysql_fetch_array($cekpo)){
					array("".$row['ITEM_ID']." - ".$row['ITEM_NAME']."","".$row['QUANTITY']."","".$row['SATUAN']."","".$row['KONVERSI']."","".$row['HARGA'].""),
				}
			);*/
				
	
	echo json_encode($memberdata);

?>