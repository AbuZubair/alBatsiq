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
		
		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderList');	 
		
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
		$loc = $_POST['locunit'];
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
				
				if (isset($loc)){
					$cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					$numrowcek = mysql_num_rows($cekbalance);
					if($numrowcek == 0){
						mysql_query("insert into stock_item (LOC_ID,ITEM_ID,BALANCE,DATE) values ('$loc','$itemIDs[$i]','$qty[$i]','$tglskg')");
					}else{
						$fetchbalance = mysql_fetch_array($cekbalance);
						$balance = $fetchbalance['BALANCE'];
						$setbalance = $qty[$i] + $balance;
						mysql_query("update stock_item set BALANCE = '$setbalance', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					}	
				}

				$cekhargabeli = mysql_query("select item.ITEM_ID,
											item.SATUAN_BELI,
											item.SALES_AVAILABLE,
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
				if($hargabeli['SALES_AVAILABLE'] == 'N'){
					$cekharga = $hargabeli['HARGA'];
					$cekqty = $hargabeli['QUANTITY'];
					$pembagi = $cekharga / $cekqty;
					$hrgjual = $pembagi / $hargabeli['KONVERSI'];
					mysql_query("update item set HARGA_JUAL = '$hrgjual' where ITEM_ID = '$itemIDs[$i]'");
				}
			}		
		}
		
		$cekpocomplete = mysql_query("SELECT DISTINCT ITEM_ID FROM item_transaction_detail WHERE TRANSACTION_NO = '$ref' AND ITEM_ID NOT IN ( SELECT DISTINCT ITEM_ID FROM item_transaction_detail where REFERENCE_NO = '$ref' )");
		$numrowcek = mysql_num_rows($cekpocomplete);
				
		if($numrowcek == 0){
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID)  values ('$pornumber','002','$tglskg','$sum','$note','$ref',1,'$loc')");
		} else{
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID)  values ('$pornumber','002','$tglskg','$sum','$note','$ref',0,'$loc')");
		}

		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderReceiveList');	
		
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
		$loc = $_POST['locunit'];
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
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA,REFERENCE_NO)  values ('$retnumber','$itemIDs[$i]','-$qty[$i]','$satuan[$i]','$konversi[$i]','-$harga[$i]','$ref')");
				
				if (isset($loc)){
					$cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					$fetchbalance = mysql_fetch_array($cekbalance);
					$balance = $fetchbalance['BALANCE'];
					$setbalance = $balance-$qty[$i];
					mysql_query("update stock_item set BALANCE = '$setbalance', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
				}

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
		
		//echo $numrowcek;
		
		if($numrowcek == 0){
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID,FROM_LOC_ID)  values ('$retnumber','003','$tglskg','-$sum','$note','$ref',1,NULL,'$loc')");
		} else{
			mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID,FROM_LOC_ID)  values ('$retnumber','003','$tglskg','-$sum','$note','$ref',0,NULL,'$loc')");
		}
		header('location:../../albatsiq.php?module=inventory&act=purchaseOrderReturnList&page=1');	 
		
	} else if ($_GET['act'] == 'addPROD'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$prodnumber = $_POST['prodnumber'];
		$note = $_POST['note'];
		$jmlcell=$_POST['jmlcell'];
		$bahandasar = $_POST['bahandasar'];
		$balancebahanskg = $_POST['balancebahanskg'];
		$satuanskg = $_POST['satuanskg'];
		$bhndsr = $_POST['bahandasar'];
		$loc = $_POST['locunit'];
		$cekkonversi = mysql_query("select KONVERSI from item where ITEM_ID = '$bhndsr'");
		$knv = mysql_fetch_array($cekkonversi);	
		$sum = 0;
		
			
		//$countpost = (count($_POST) - 5) / 5;
				
			for($i=0;$i<=$jmlcell;$i++){
			
			
			$index1="itemID".$i;
			$itemID = explode(" ",$_POST[$index1]);	
			$itemIDs[$i] = $itemID[0];		
			$check[$i]=!empty($itemIDs[$i]);
					
			$index2="qty".$i;
			$qty[$i]=$_POST[$index2];
			
			$index3="qtybahan".$i;
			$qtybahan[$i]=$_POST[$index3];
			
		}	
		
		mysql_query ("Insert into production (PROD_ID,FT_LOC_ID,PROD_DATE,NOTE)  values ('$prodnumber','$loc','$tglskg','$note')");

		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				mysql_query ("Insert into production_detail (PROD_ID,BAHAN_ID,ITEM_RESULT,QUANTITY,QTY_BAHAN,SATUAN_BAHAN,PROD_DATE) values ('$prodnumber','$bhndsr','$itemIDs[$i]','$qty[$i]','$qtybahan[$i]','$satuanskg','$tglskg')");
				
				if (isset($loc)){
					$cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					$numrowcek = mysql_num_rows($cekbalance);
					if($numrowcek == 0){
						mysql_query("insert into stock_item (LOC_ID,ITEM_ID,BALANCE,DATE) values ('$loc','$itemIDs[$i]','$qty[$i]','$tglskg')");
					}else{
						$fetchbalance = mysql_fetch_array($cekbalance);
						$balance = $fetchbalance['BALANCE'];
						$setbalance = $qty[$i] + $balance;
						mysql_query("update stock_item set BALANCE = '$setbalance', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					}	
				}
			}		
		}
		
		$stockskg = $balancebahanskg/$knv['KONVERSI']*1;
		mysql_query("update stock_item set BALANCE = '$stockskg', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$bhndsr'");

		header('location:../../albatsiq.php?module=inventory&act=productionList');	
		
	} else if ($_GET['act'] == 'addDIST'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$distnumber = $_POST['distnumber'];
		$note = $_POST['note'];
		$jmlcell=$_POST['jmlcell'];
		$loc = $_POST['locunit'];
		$toloc = $_POST['tolocunit'];
			
		
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
			
		}	
		
		mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID,FROM_LOC_ID)  values ('$distnumber','004','$tglskg','','$note','','','$toloc','$loc')");

		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA)  values ('$distnumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','$konversi[$i]','')");
				
				if (isset($loc)){
					$cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$toloc' and ITEM_ID='$itemIDs[$i]'");					
					$fetchbalance = mysql_fetch_array($cekbalance);
					$cekfrombalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");					
					$fetchfrombalance = mysql_fetch_array($cekfrombalance);
					$numrowcek = mysql_num_rows($cekbalance);
					$balance = $fetchbalance['BALANCE'];
					$frombalance = $fetchfrombalance['BALANCE'];
					$setbalance = $qty[$i] + $balance;
					$setfrombalance = $frombalance - $qty[$i];
					mysql_query("update stock_item set BALANCE = '$setfrombalance',DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");	
					if($numrowcek == 0){
						mysql_query("insert into stock_item (LOC_ID,ITEM_ID,BALANCE,DATE) values ('$toloc','$itemIDs[$i]','$qty[$i]','$tglskg')");
					} else {
						mysql_query("update stock_item set BALANCE = '$setbalance',DATE = '$tglskg' where LOC_ID='$toloc' and ITEM_ID='$itemIDs[$i]'");
					}
				}
			}		
		} 
	
		header('location:../../albatsiq.php?module=inventory&act=distribusiList');	
	
	}
	else if ($_GET['act'] == 'addStockAdj'){
		$tgl = $_POST['tgl'];
		$date = explode("-",$tgl);
		$tglskg = date("$date[2]-$date[1]-$date[0]");
		$sanumber = $_POST['sanumber'];
		$notetype = $_POST['notetype'];
		$jmlcell=$_POST['jmlcell'];
		$loc = $_POST['locunit'];
					
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
			
			$index4="qtytype".$i;
			$qtytype[$i]=$_POST[$index4];
			
		}	
		
		mysql_query("insert into item_transaction (TRANSACTION_NO,TRANSACTION_CODE,TRANSACTION_DATE,CHARGE_AMOUNT,NOTE,REFERENCE_NO,IS_COMPLETE,TO_LOC_ID,FROM_LOC_ID)  values ('$sanumber','005','$tglskg','','$notetype','','',NULL,'$loc')");

		for($i=0;$i<=$jmlcell;$i++)
		{
			if($check[$i] == 1){
				mysql_query ("Insert into item_transaction_detail (TRANSACTION_NO,ITEM_ID,QUANTITY,SATUAN,KONVERSI,HARGA,REFERENCE_NO,QTY_TYPE)  values ('$sanumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','','','','$qtytype[$i]')");
				
				if (isset($loc)){
					$cekfrombalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");					
					$fetchfrombalance = mysql_fetch_array($cekfrombalance);
					$frombalance = $fetchfrombalance['BALANCE'];
					if($qtytype[$i] == '+'){
						$setfrombalance = $frombalance + $qty[$i];
					} else{
						$setfrombalance = $frombalance - $qty[$i];
					}
					
					mysql_query("update stock_item set BALANCE = '$setfrombalance',DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
					
				}
			}		
		} 
	
		header('location:../../albatsiq.php?module=inventory&act=stockAdjustList'); 
	}
	
?>