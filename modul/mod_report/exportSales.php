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
    
    $result = mysql_query("SELECT * FROM sales s INNER JOIN sales_detail sd on s.SALES_ID=sd.SALES_ID INNER JOIN item i on i.ITEM_ID=sd.ITEM_ID WHERE s.SALES_DATE BETWEEN '$tahun-$bulan-$tgl' AND '$tahun1-$bulan1-$tgl1'");

    echo "
        <table width='100%'>
            <tr>
                <th>No.</th>
                <th>Nomor Sales </th>
                <th>Date</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total Amount</th>
            </tr>";
        while($sale = mysql_fetch_array($result)){
            $amt = number_format($sale['TOTAL_AMOUNT'], 2, '.', ',');
            $tgl = explode("-",$sale['SALES_DATE']);
            $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
            echo"
            <tr>
                <td>$no</td>
                <td>$sale[SALES_ID]</td>
                <td>$tanggal</td>
                <td>$sale[ITEM_NAME]</td>
                <td>$sale[QUANTITY]</td>
                <td>Rp. $amt</td>
            </tr>
            ";
            $no++;
            $sumqtys += $sale['QUANTITY'];
            $sumamts += $sale['TOTAL_AMOUNT'];
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