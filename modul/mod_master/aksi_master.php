 <?php
	include "../../config/koneksi.php";
	$unitid = $_POST['unitid'];
	$unitname =  $_POST['unitname'];
	$locid = $_POST['locid'];
	$locname = $_POST['locname'];
	$itemid = $_POST['itemid'];
	$itemname = $_POST['itemname'];
	$itemgroup = $_POST['itemgroup'];
	$satuanbeli = $_POST['satuanbeli'];
	$satuanjual = $_POST['satuanjual'];
	$konversi = $_POST['konversi'];
	$sales_available = $_POST['sales_available'];
	$item = $_POST['item'];
	$hargajual = $_POST['hargajual'];
	$tgl = $_POST['tgl'];
	
			
	if ($_GET['act'] == 'addUnit'){
		mysql_query("insert into unit (UNIT_ID,UNIT_NAME,LOC_ID,LOC_NAME) values ('$unitid','$unitname','$locid','$locname')");
		header('location:../../albatsiq.php?module=master&act=masterunitList');			
	} 
	else if ($_GET['act'] == 'editUnit'){
		mysql_query("update unit set UNIT_NAME = '$unitname', LOC_NAME = '$locname' where UNIT_ID = '$unitid'");
		header('location:../../albatsiq.php?module=master&act=masterunitList');
	}
	else if ($_GET['act'] == 'addItem'){
		$cekitem = mysql_query("select * from item where ITEM_ID = '$itemid'");
		$rowitem = mysql_num_rows($cekitem);
		
		if($rowitem == 0){
			if($sales_available == 'Y'){
				mysql_query("insert into item (ITEM_ID,ITEM_NAME,ITEM_GROUP,SATUAN_BELI,SATUAN_JUAL,HARGA_BELI,HPP,KONVERSI,SALES_AVAILABLE) values ('$itemid','$itemname','$itemgroup','$satuanbeli','$satuanjual','','','$konversi','$sales_available')");
			} else {
				mysql_query("insert into item (ITEM_ID,ITEM_NAME,ITEM_GROUP,SATUAN_BELI,SATUAN_JUAL,HARGA_BELI,HPP,KONVERSI,SALES_AVAILABLE) values ('$itemid','$itemname','$itemgroup','$satuanbeli','$satuanjual','','','$konversi','N')");
			}
			header('location:../../albatsiq.php?module=master&act=masteritemList');
		} else {
			$data = array(
					  'itemid' => $itemid,
					  'itemname' => $itemname,
					  'itemgroup'=>$itemgroup,
					  'satuanbeli'=>$satuanbeli,
					  'satuanjual'=>$satuanjual,
					  'konversi'=>$konversi,
					  'sales_available'=>$sales_available
					  );
			header("location:../../albatsiq.php?module=master&act=masteritemNew&id=confirmId&". http_build_query($data) . "");
		}
	} 
	else if ($_GET['act'] == 'editItem'){
		mysql_query("update item set ITEM_NAME = '$itemname', ITEM_GROUP = '$itemgroup', SATUAN_BELI = '$satuanbeli', SATUAN_JUAL = '$satuanjual', KONVERSI = '$konversi', SALES_AVAILABLE = '$sales_available' where ITEM_ID = '$itemid'");
		header("location:../../albatsiq.php?module=view&act=masteritem&id=$itemid");
	}
	else if ($_GET['act'] == 'addItemTariff'){
		mysql_query("insert into item_tariff (ITEM_ID,HARGA_JUAL,DATE) values ('$item','$hargajual','$tgl')");
		mysql_query("update item set HARGA_JUAL = '$hargajual' where ITEM_ID = '$item'");
		header('location:../../albatsiq.php?module=master&act=masteritemTariffList');
	}
		
?>