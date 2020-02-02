<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=stock_information.xls");  //File name extension was wrong
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
          <th>Balance</th>
          <th>Satuan</th>
          <th>Last Update</th>        
        </tr>
      </thead>
      <tbody id='result'>
      <?php
          $no=0;
          foreach ($result as $row_list) {
              # code...
              $no++;
              echo '
              <tr>
                  <td>'.$no.'</td>
                  <td>'.$row_list->item_name.'</td>
                  <td>'.$row_list->unit_name.'</td>
                  <td>'.$row_list->balance.'</td>
                  <td>'.$row_list->satuan.'</td>
                  <td>'.$row_list->updated_date.'</td>
              </tr>';
              
          }
          

      ?>
      </tbody>
    </table>