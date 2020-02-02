<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=stock_card.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>

<table id="dynamic-table" class="table table-bordered table-hover" >
       <thead>
        <tr>  
            <th>No </th>
            <th>Item</th>
            <th>Unit</th>
            <th>Stock In</th>
            <th>Stock Out</th>
            <th>Stock Sebelum</th>
            <th>Balance</th>
            <th>Satuan</th>
            <th>Transaksi</th>
            <th>Unit Referensi</th>
            <th>Last Update</th>        
        </tr>
      </thead>
      <tbody id='result'>
      <?php
          $no=0;
          foreach ($result as $row_list) {
              # code...
              $no++;
              $st_in= ($row_list->stock_in==null)?'-':$row_list->stock_in;
              $st_out= ($row_list->stock_out==null)?'-':$row_list->stock_out;
              $ref= ($row_list->loc_ref!=null)?$row_list->unit_ref:$row_list->unit;
              switch ($row_list->transaction_code) {
                case '002':
                    $trans = 'Purchase Order Receive';
                  break;
                case '003':
                  $trans = 'Purchase Order Return';
                break;
                case '100':
                  $trans = 'Distribusi';
                break;
                case '500':
                  $trans = 'Production';
                break;
                case '700':
                  $trans = 'Sales';
                break;
                case '710':
                  $trans = 'Sales Return';
                break;
              }
              echo '
              <tr>
                  <td>'.$no.'</td>
                  <td>'.$row_list->item_name.'</td>
                  <td>'.$row_list->unit.'</td>
                  <td>'.$st_in.'</td>
                  <td>'.$st_out.'</td>
                  <td>'.$row_list->stock_before.'</td>
                  <td>'.$row_list->stock_balance.'</td>
                  <td>'.$row_list->satuan.'</td>
                  <td>'.$trans.'</td>
                  <td>'.$ref.'</td>
                  <td>'.$row_list->created_date.'</td>
              </tr>';
              
          }
          

      ?>
      </tbody>
    </table>