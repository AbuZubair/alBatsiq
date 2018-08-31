<html>
<title></title>
<head>
    <style>
        table{
            color: #000;
            padding: 0.5em;
            max-width: 100%;
        }
        table th, table td{
            border: 1px solid #ddd;
            padding: 8px;
            text-align:center;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}
    </style>
</head>
<?php
	ini_set('max_execution_time', 300);
	include "../../config/koneksi.php";
	$tgl = $_GET['tgl'];
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	
	$tgl1 = $_GET['tgl1'];
	$bulan1 = $_GET['bulan1'];
    $tahun1 = $_GET['tahun1'];

    $sumqtys=$sumamts=0;
    $no=1;
    
    $result = mysql_query("SELECT * FROM item_transaction it INNER JOIN item_transaction_detail itd on it.TRANSACTION_NO=itd.TRANSACTION_NO INNER JOIN item i on i.ITEM_ID=itd.ITEM_ID WHERE it.TRANSACTION_CODE IN ('002','003') AND it.TRANSACTION_DATE BETWEEN '$tahun-$bulan-$tgl' AND '$tahun1-$bulan1-$tgl1'");

    echo "
        <table width='100%'>
            <tr>
                <th>No.</th>
                <th>Nomor POR</th>
                <th>Date</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total Amount</th>
            </tr>";
        while($por = mysql_fetch_array($result)){
            $amt = number_format($por['HARGA'], 2, '.', ',');
            $tgl = explode("-",$por['TRANSACTION_DATE']);
            $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
            echo"
            <tr>
                <td>$no</td>
                <td>$por[TRANSACTION_NO]</td>
                <td>$tanggal</td>
                <td>$por[ITEM_NAME]</td>
                <td>$por[QUANTITY]</td>
                <td>Rp. $amt</td>
            </tr>
            ";
            $no++;
            $sumqtys += $por['QUANTITY'];
            $sumamts += $por['HARGA'];
            $totamts = number_format($sumamts, 2, '.', ',');
        }
        echo"
        <tr>
            <th colspan=4 style='text-align:center;'>Grand Total</th>
            <th>$sumqtys</th>
            <th>Rp. $totamts </th>
        </tr>
        </table>
    ";
?>
</html>