<?php
include "../../config/koneksi.php";

switch($_GET['act']){
	case "addSales";
        $tgl = $_POST['tgl'];
        $date = explode("-",$tgl);
        $tglskg = date("$date[2]-$date[1]-$date[0]");
        $slnumber = $_POST['slnumber'];
        $jmlcell=$_POST['jmlcell'];
        $loc = $_POST['locunit'];
        $grtl = $_POST['grtotal'];
        $created = $_POST['created'];
       
   mysql_query("insert into sales (SALES_ID,SALES_CODE,SALES_DATE,CHARGE_AMOUNT,CREATED_BY,FROM_LOC_ID,REFERENCE_ID,IS_COMPLETE) values ('$slnumber','001','$tglskg','$grtl','$created','$loc','','')");
                
           for($i=0;$i<=$jmlcell;$i++){
            
            
            $index1="itemID".$i;
            $itemID = explode(" ",$_POST[$index1]);	
            $itemIDs[$i] = $itemID[0];		
            $check[$i]=!empty($itemIDs[$i]);
                    
            $index2="qty".$i;
            $qty[$i]=$_POST[$index2];
            
            $index3="satuan".$i;
            $satuan[$i]=$_POST[$index3];
            
            $index4="harga".$i;
            $harga[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index4]));
          
            $index5="dsc".$i;
            $dsc[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index5]));

            $index6="total".$i;
            $total[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index6]));
                        
        }	
                            
        for($i=0;$i<=$jmlcell;$i++)
        {
            if($check[$i] == 1){
                mysql_query ("Insert sales_detail (SALES_ID,ITEM_ID,QUANTITY,SATUAN,HARGA_JUAL,DISCOUNT_AMOUNT,TOTAL_AMOUNT)  values ('$slnumber','$itemIDs[$i]','$qty[$i]','$satuan[$i]','$harga[$i]','$dsc[$i]','$total[$i]')");
                
                if (isset($loc)){
                    $cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
                    $fetchbalance = mysql_fetch_array($cekbalance);
                    $balance = $fetchbalance['BALANCE'];
                    $setbalance = $balance - $qty[$i];
                    mysql_query("update stock_item set BALANCE = '$setbalance', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
                }
            }		
        }
        
        header('location:../../albatsiq.php?module=sales&act=salesNew'); 
    break;

    case "addSalesRet";
        $tgl = $_POST['tgl'];
        $date = explode("-",$tgl);
        $tglskg = date("$date[2]-$date[1]-$date[0]");
        $slretnumber = $_POST['slretnumber'];
        $jmlcell=$_POST['jmlcell'];
        $loc = $_POST['locunit'];
        $grtl = $_POST['grtotal'];
        $created = $_POST['created'];
        $ref = $_POST['referenceNo'];
       
   
                
           for($i=0;$i<=$jmlcell;$i++){
            
            
            $index1="itemID".$i;
            $itemID = explode(" ",$_POST[$index1]);	
            $itemIDs[$i] = $itemID[0];		
            $check[$i]=!empty($itemIDs[$i]);
                    
            $index2="qty".$i;
            $qty[$i]=$_POST[$index2];
            
            $index3="satuan".$i;
            $satuan[$i]=$_POST[$index3];
            
            $index4="harga".$i;
            $harga[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index4]));
          
            $index5="dsc".$i;
            $dsc[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index5]));

            $index6="total".$i;
            $total[$i]=floatval(preg_replace('/[^\d.]/', '', $_POST[$index6]));
                        
        }	
                            
        for($i=0;$i<=$jmlcell;$i++)
        {
            if($check[$i] == 1){
               mysql_query ("Insert sales_detail (SALES_ID,ITEM_ID,QUANTITY,SATUAN,HARGA_JUAL,DISCOUNT_AMOUNT,TOTAL_AMOUNT,REFERENCE_ID)  values ('$slretnumber','$itemIDs[$i]','-$qty[$i]','$satuan[$i]','$harga[$i]','$dsc[$i]','-$total[$i]','$ref')");
                
                if (isset($loc)){
                    $cekbalance = mysql_query("SELECT * FROM stock_item WHERE LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
                    $fetchbalance = mysql_fetch_array($cekbalance);
                    $balance = $fetchbalance['BALANCE'];
                    $setbalance = $balance + $qty[$i];
                    mysql_query("update stock_item set BALANCE = '$setbalance', DATE = '$tglskg' where LOC_ID='$loc' and ITEM_ID='$itemIDs[$i]'");
                }
            }		
        }
        
        $cekcomplete = mysql_query("SELECT DISTINCT ITEM_ID FROM sales_detail WHERE SALES_ID = '$ref' AND ITEM_ID NOT IN ( SELECT DISTINCT ITEM_ID FROM sales_detail where REFERENCE_ID = '$ref' )");
        $numrowcek = mysql_num_rows($cekcomplete);

              
		if($numrowcek == 0){
            mysql_query("insert into sales (SALES_ID,SALES_CODE,SALES_DATE,CHARGE_AMOUNT,CREATED_BY,FROM_LOC_ID,REFERENCE_ID,IS_COMPLETE) values ('$slretnumber','002','$tglskg','-$grtl','$created','$loc','$ref','1')");
          
		} else{
           mysql_query("insert into sales (SALES_ID,SALES_CODE,SALES_DATE,CHARGE_AMOUNT,CREATED_BY,FROM_LOC_ID,REFERENCE_ID,IS_COMPLETE) values ('$slretnumber','002','$tglskg','-$grtl','$created','$loc','$ref','0')");
           
		}

       

        header('location:../../albatsiq.php?module=sales&act=salesRetList'); 
    break;
}
?>