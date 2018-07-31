<?php
	include "../../config/koneksi.php";
	
	if ($_GET['act'] == 'addPO'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$ponumber = $_POST['ponumber'];
		$note = $_POST['note'];
		$jmlcell=$_POST['jmlcell'];
		$sum = 0;
		
			for($i=0;$i<=$jmlcell;$i++){
			
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];		
			$check[$i]=!empty($itemIDs[$i]);
						
			$index2="qty".$i;
			$qty[$i]=$_POST[$index2];
			
			$index3="satuan".$i;
			$satuan[$i]=$_POST[$index3];
			
			$index4="knv".$i;
			$konversi[$i]=$_POST[$index4];
			
			$index5="harga".$i;
			$harga[$i]=$_POST[$index5];
			
			$sum += $harga[$i];
			
		}	
		
		mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE)  values ('$ponumber','001','$tglskg','$sum','$note')");
		
		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA)  values ('$ponumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','$konversi[$i]','$harga[$i]')");

			}
		}
		
		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderList&page=1');	 
		
	} else if ($_GET['act'] == 'editPO'){
		$numrow = $_GET['id'];
		$ponumber = $_POST['ponumber'];
		$sum = 0;
		
		for($i=1;$i<=$numrow;$i++){
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];
			
			$index1="qty".$i;
			$qty[$i]=$_POST[$index1];
			
			$index2="satuan".$i;
			$satuan[$i]=$_POST[$index2];
			
			$index3="konversi".$i;
			$konversi[$i]=$_POST[$index3];
			
			$index4="harga".$i;
			$harga[$i]=$_POST[$index4];
			
			$sum += $harga[$i];
		}
		
		for($i=1;$i<=$numrow;$i++){
			mysql_query("update item_transaction_detail set QUANTITY = '$qty[$i]',SATUAN = '$satuan[$i]',KONVERSI = '$konversi[$i]',HARGA = '$harga[$i]' where TRANSACTION_NO='$ponumber' and ITEM_ID='$itemIDs[$i]'");
	/*		$cekhargabeli = mysql_query("select item.ITEM_ID,
										item.SATUAN_BELI,
										item_transaction.TRANSACTION_CODE,
										item_transaction_detail.TRANSACTION_NO,
										item_transaction_detail.QUANTITY,
										item_transaction_detail.SATUAN,
										item_transaction_detail.KONVERSI,
										item_transaction_detail.HARGA
										from item_transaction_detail 
										inner join item on item.ITEM_ID = item_transaction_detail.ITEM_ID 
										inner join item_transaction on item_transaction.TRANSACTION_NO = item_transaction_detail.TRANSACTION_NO
										where item_transaction_detail.ITEM_ID = '$itemIDs[$i]' and item_transaction.TRANSACTION_CODE = '001' ORDER BY item_transaction_detail.TRANSACTION_NO DESC limit 1
								");
			$hargabeli = mysql_fetch_array($cekhargabeli);
	
			if ($hargabeli['TRANSACTION_NO'] == $ponumber){
				if($hargabeli['SATUAN_BELI'] == 'Meter' && $hargabeli['SATUAN'] == 'Gulung'){
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$konversi = $hargabeli['KONVERSI'];
					$pembagi = $cekharga / $cekqty;
					$hrgbeliakhir = $pembagi / $konversi;
					mysql_query("update item set HARGA_BELI = '$hrgbeliakhir' where ITEM_ID = '$itemIDs[$i]'");
					
				} else if($hargabeli['SATUAN_BELI'] == 'Gulung' && $hargabeli['SATUAN'] == 'Meter'){
					//notyet
				
				}else {
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$pembagi = $cekharga / $cekqty;
					$hrgbeliakhir = $pembagi * 1;
					mysql_query("update item set HARGA_BELI = '$hrgbeliakhir' where ITEM_ID = '$itemIDs[$i]'");
					
				} */
			}		
		mysql_query("update item_transaction set CHARGE_AMOUNT = '$sum' where TRANSACTION_NO='$ponumber'");
				
		header("location:../../albatsiq.php?module=view&act=purchaseOrder&id=$ponumber");	
		
	} else if ($_GET['act'] == 'addPOR'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$pornumber = $_POST['pornumber'];
		$note = $_POST['note'];
		$jmlcell=$_POST['jmlcell'];
		$ref = $_POST['referenceNo'];
		$sum = 0;
		
		
		//$countpost = (count($_POST) - 5) / 5;
				
			for($i=0;$i<=$jmlcell;$i++){
			
			
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];		
			$check[$i]=!empty($itemIDs[$i]);
					
			$index2="qty".$i;
			$qty[$i]=$_POST[$index2];
			
			$index3="satuan".$i;
			$satuan[$i]=$_POST[$index3];
			
			$index4="knv".$i;
			$konversi[$i]=$_POST[$index4];
			
			$index5="harga".$i;
			$harga[$i]=$_POST[$index5];
			
			$sum += $harga[$i];
			
		}	
						
		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA,REFERENCE_NO)  values ('$pornumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','$konversi[$i]','$harga[$i]','$ref')");
				
				$cekhargabeli = mysql_query("select item.ITEM_ID,
											item.SATUAN_BELI,
											item_transaction_detail.TRANSACTION_NO,
											item_transaction_detail.QUANTITY,
											item_transaction_detail.SATUAN,
											item_transaction_detail.KONVERSI,
											item_transaction_detail.HARGA
											from item_transaction_detail left join item on item.ITEM_ID = item_transaction_detail.ITEM_ID 
											where item_transaction_detail.ITEM_ID = '$itemIDs[$i]' and item_transaction_detail.TRANSACTION_NO = '$pornumber'
									");
				$hargabeli = mysql_fetch_array($cekhargabeli);
				
				
				if($hargabeli['SATUAN_BELI'] == $hargabeli['SATUAN']){
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$pembagi = $cekharga / $cekqty;
					$hrgbeliakhir = $pembagi * 1;
					mysql_query("update item set HARGA_BELI = '$hrgbeliakhir' where ITEM_ID = '$itemIDs[$i]'");
				}
			}		
		}
		
		$cekpocomplete = mysql_query("SELECT DISTINCT ITEM_ID FROM item_transaction_detail WHERE TRANSACTION_NO = '$ref' AND ITEM_ID NOT IN ( SELECT DISTINCT ITEM_ID FROM item_transaction_detail where REFERENCE_NO = '$ref' )");
		$numrowcek = mysql_num_rows($cekpocomplete);
		
		echo $numrowcek;
		
		if($numrowcek == 0){
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE)  values ('$pornumber','002','$tglskg','$sum','$note','$ref',1)");
		} else{
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE)  values ('$pornumber','002','$tglskg','$sum','$note','$ref',0)");
		}
		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderReceiveList&page=1');	
		
	} else if ($_GET['act'] == 'editPOR'){
		$numrow = $_GET['id'];
		$pornumber = $_POST['pornumber'];
		$sum = 0;
		
		for($i=1;$i<=$numrow;$i++){
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];
			
			$index1="qty".$i;
			$qty[$i]=$_POST[$index1];
			
			$index2="satuan".$i;
			$satuan[$i]=$_POST[$index2];
			
			$index3="konversi".$i;
			$konversi[$i]=$_POST[$index3];
			
			$index4="harga".$i;
			$harga[$i]=$_POST[$index4];
			
			$sum += $harga[$i];
		}
		
		for($i=1;$i<=$numrow;$i++){
			mysql_query("update item_transaction_detail set QUANTITY = '$qty[$i]',SATUAN = '$satuan[$i]',KONVERSI = '$konversi[$i]',HARGA = '$harga[$i]' where TRANSACTION_NO='$pornumber' and ITEM_ID='$itemIDs[$i]'");
	/*		$cekhargabeli = mysql_query("select item.ITEM_ID,
										item.SATUAN_BELI,
										item_transaction.TRANSACTION_CODE,
										item_transaction_detail.TRANSACTION_NO,
										item_transaction_detail.QUANTITY,
										item_transaction_detail.SATUAN,
										item_transaction_detail.KONVERSI,
										item_transaction_detail.HARGA
										from item_transaction_detail 
										inner join item on item.ITEM_ID = item_transaction_detail.ITEM_ID 
										inner join item_transaction on item_transaction.TRANSACTION_NO = item_transaction_detail.TRANSACTION_NO
										where item_transaction_detail.ITEM_ID = '$itemIDs[$i]' and item_transaction.TRANSACTION_CODE = '001' ORDER BY item_transaction_detail.TRANSACTION_NO DESC limit 1
								");
			$hargabeli = mysql_fetch_array($cekhargabeli);
	
			if ($hargabeli['TRANSACTION_NO'] == $ponumber){
				if($hargabeli['SATUAN_BELI'] == 'Meter' && $hargabeli['SATUAN'] == 'Gulung'){
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$konversi = $hargabeli['KONVERSI'];
					$pembagi = $cekharga / $cekqty;
					$hrgbeliakhir = $pembagi / $konversi;
					mysql_query("update item set HARGA_BELI = '$hrgbeliakhir' where ITEM_ID = '$itemIDs[$i]'");
					
				} else if($hargabeli['SATUAN_BELI'] == 'Gulung' && $hargabeli['SATUAN'] == 'Meter'){
					//notyet
				
				}else {
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$pembagi = $cekharga / $cekqty;
					$hrgbeliakhir = $pembagi * 1;
					mysql_query("update item set HARGA_BELI = '$hrgbeliakhir' where ITEM_ID = '$itemIDs[$i]'");
					
				} */
			}		
		mysql_query("update item_transaction set CHARGE_AMOUNT = '$sum' where TRANSACTION_NO='$pornumber'");
				
		header("location:../../albatsiq.php?module=view&act=purchaseOrderReceive&id=$pornumber");	
		
	} else if ($_GET['act'] == 'addRET'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$retnumber = $_POST['retnumber'];
		$note = $_POST['note'];
		$jmlcell=$_POST['jmlcell'];
		$ref = $_POST['referenceNo'];
		$sum = 0;
		
			for($i=0;$i<=$jmlcell;$i++){
			
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];		
			$check[$i]=!empty($itemIDs[$i]);
						
			$index2="qty".$i;
			$qty[$i]=$_POST[$index2];
			
			$index3="satuan".$i;
			$satuan[$i]=$_POST[$index3];
			
			$index4="knv".$i;
			$konversi[$i]=$_POST[$index4];
			
			$index5="harga".$i;
			$harga[$i]=$_POST[$index5];
			
			$sum += $harga[$i];
			
		}	
		
		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA,REFERENCE_NO)  values ('$retnumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','$konversi[$i]','$harga[$i]','$ref')");
				
				$cekhargabeli = mysql_query("select item.ITEM_ID,
											item.SATUAN_BELI,
											item_transaction_detail.TRANSACTION_NO,
											item_transaction_detail.QUANTITY,
											item_transaction_detail.SATUAN,
											item_transaction_detail.KONVERSI,
											item_transaction_detail.HARGA
											from item_transaction_detail left join item on item.ITEM_ID = item_transaction_detail.ITEM_ID 
											where item_transaction_detail.ITEM_ID = '$itemIDs[$i]' and item_transaction_detail.TRANSACTION_NO = '$retnumber'
									");
				$hargabeli = mysql_fetch_array($cekhargabeli);	
			}		
		}
		
		$cekpocomplete = mysql_query("SELECT DISTINCT ITEM_ID FROM item_transaction_detail WHERE TRANSACTION_NO = '$ref' AND ITEM_ID NOT IN ( SELECT DISTINCT ITEM_ID FROM item_transaction_detail where REFERENCE_NO = '$ref' )");
		$numrowcek = mysql_num_rows($cekpocomplete);
		
		echo $numrowcek;
		
		if($numrowcek == 0){
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE)  values ('$retnumber','003','$tglskg','$sum','$note','$ref',1)");
		} else{
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE)  values ('$retnumber','003','$tglskg','$sum','$note','$ref',0)");
		}
		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderReturnList&page=1');	 
		
	}
	
	
?>