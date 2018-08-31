<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link href="css/style.css" rel="stylesheet">
        <style>

            table{
            color: #000;
            border-width: 1px;
            border-style: solid;
            border-collapse: collapse;
            padding: 0.5em;
            max-width: 100%;
            }
            table th, table td{
                border: 1px solid #ddd;
                padding: 8px;
            }

            table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #4CAF50;
                color: white;
            }

            table tr:nth-child(even){background-color: #f2f2f2;}

            table tr:hover {background-color: #ddd;}

            #btnok{
                background-color: #008CBA;
                position:absolute;
            
                margin-top:10px;
                padding:5px 15px;
            }

            a button{
                margin:1% 0;
            }

        </style>

        <script>
            function closewdw() {
                window.close();
            } 
        </script>
    </head>
    <body>
        <?php
            include "../../config/koneksi.php";

            $limit = 20;  
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $start_from = ($page-1) * $limit;

            $from = $_GET['from'];
            $todate = $_GET['to'];
            $tem = explode("/",$from);
            $temto = explode("/",$todate);
            $sumqty=$sumamt=$sumqtys=$sumamts=0;
            $totamt='';
            $totamts='';
            // alert(tem[1]+"-"+tem[0]+"-"+tem[2]);
            $por = mysql_query("SELECT * FROM item_transaction it INNER JOIN item_transaction_detail itd on it.TRANSACTION_NO=itd.TRANSACTION_NO INNER JOIN item i on i.ITEM_ID=itd.ITEM_ID WHERE it.TRANSACTION_CODE IN ('002','003') AND it.TRANSACTION_DATE BETWEEN '$tem[2]-$tem[0]-$tem[1]' AND '$temto[2]-$temto[0]-$temto[1]' LIMIT $start_from, $limit");
            $result = mysql_query("SELECT * FROM item_transaction it INNER JOIN item_transaction_detail itd on it.TRANSACTION_NO=itd.TRANSACTION_NO INNER JOIN item i on i.ITEM_ID=itd.ITEM_ID WHERE it.TRANSACTION_CODE IN ('002','003') AND it.TRANSACTION_DATE BETWEEN '$tem[2]-$tem[0]-$tem[1]' AND '$temto[2]-$temto[0]-$temto[1]'");
           
            echo" 
            <a href='export2.php?tgl=$tem[1]&bulan=$tem[0]&tahun=$tem[2]&tgl1=$temto[1]&bulan1=$temto[0]&tahun1=$temto[2]'><button>Export Data ke Excel</button></a>
            <button onclick='closewdw()'>Close</button>    
                <table width='100%'>

                    <tr>
                        <th>Nomor POR </th>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>";
                while($pemb = mysql_fetch_array($por)){
                    $amt = number_format($pemb['HARGA'], 2, '.', ',');
                    $tgl = explode("-",$pemb['TRANSACTION_DATE']);
                    $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
                    echo"
                    <tr>
                        <td>$pemb[TRANSACTION_NO]</td>
                        <td>$tanggal</td>
                        <td>$pemb[ITEM_NAME]</td>
                        <td>$pemb[QUANTITY]</td>
                        <td>Rp. $amt</td>
                    </tr>
                    ";
                    $sumqtys += $pemb['QUANTITY'];
                    $sumamts += $pemb['HARGA'];
                    $totamts = number_format($sumamts, 2, '.', ',');
                }

                while($pembres = mysql_fetch_array($result)){
                    $sumqty += $pembres['QUANTITY'];
                    $sumamt += $pembres['HARGA'];
                    $totamt = number_format($sumamt, 2, '.', ',');
                }
                echo"
                <tr>
                    <td colspan=3 style='text-align:center;'>Total</td>
                    <td>$sumqtys</td>
                    <td>Rp. $totamts </td>
                </tr>
                <tr>
                    <th colspan=3 style='text-align:center;'>Grand Total</th>
                    <th>$sumqty</th>
                    <th>Rp. $totamt </th>
                </tr>
                </table>
            ";

                echo"	<center><ul class='pagination'>";
            
                        
                        if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV
                        echo"
                            <li class='disabled'><a href='#'>First</a></li>
                            <li class='disabled'><a href='#'>&laquo;</a></li> ";
                        
                        } else { // Jika buka page ke 1
                            $link_prev = ($page > 1) ? $page - 1 : 1;
                        echo"
                            <li><a href='showPOR.php?from=$from&to=$todate&page=1'>First</a></li>
                            <li><a href='showPOR.php?from=$from&to=$todate&page=$link_prev'>&laquo;</a></li>
                        ";
                        }
                    

                    
                        // Buat query untuk menghitung semua jumlah data
                        
                        $row = mysql_num_rows($result);    
                        $jumlah_page = ceil($row / $limit);
                        $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
                        $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
                        $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
                        for ($i = $start_number; $i <= $end_number; $i++) {
                            $link_active = ($page == $i) ? 'class="active"' : '';
                        echo" <li $link_active><a href='showPOR.php?from=$from&to=$todate&page=".$i."'>".$i."</a></li>";
                        }
                    

                    
                        // Jika page sama dengan jumlah page, maka disable link NEXT nya
                        // Artinya page tersebut adalah page terakhir
                        if ($page == $jumlah_page) { // Jika page terakhir
                        echo"
                            <li class='disabled'><a href='#'>&raquo;</a></li>
                            <li class='disabled'><a href='#'>Last</a></li>
                        ";
                        } else { // Jika bukan page terakhir
                            $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                        echo"
                            <li><a href='showPOR.php?from=$from&to=$todate&page=$link_next'>&raquo;</a></li>
                            <li><a href='showPOR.php?from=$from&to=$todate&page=$jumlah_page'>Last</a></li>
                        ";
                        }
            echo"         
            </ul></center>";
        ?>

    </body>
</html>