
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Purchase Order</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=nomorpo' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='PO No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=purchaseOrder&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=purchaseOrderNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Purchase Order No. </th>
					<th width='20%'> Date </th>
					<th width='40%'> Note </th>
					<th width='10%'></th>
				</tr>
			";
		
		$cekpo = mysql_query("select * from item_transaction where TRANSACTION_CODE = '001' ORDER BY item_transaction.TRANSACTION_NO DESC LIMIT $start_from, $limit");
		while($po = mysql_fetch_array($cekpo)){
			$tgl = explode("-",$po['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$po[0]</td>
					<td>$tanggal</td>
					<td>$po[4]</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=purchaseOrder&id=$po[0]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		/*	$resut = mysql_query("select * from item_transaction where TRANSACTION_CODE = '001' ORDER BY item_transaction.TRANSACTION_NO DESC");
			$row = mysql_num_rows($resut);    
			$total_pages = ceil($row / $limit);  
			$pagLink = "<div class='pagination'>";  
			for($i = max(1, $page - 5); $i <= min($page + 4, $total_pages); $i++) {  
						 $pagLink .= "<a href='albatsiq.php?module=inventory&act=purchaseOrderList&page=".$i."'>".$i."</a>";  
			};  
			echo $pagLink . "</div>";*/


	echo"	<ul class='pagination'>";
           
            
            if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV
			  echo"
				<li class='disabled'><a href='#'>First</a></li>
                <li class='disabled'><a href='#'>&laquo;</a></li> ";
            
            } else { // Jika buka page ke 1
                $link_prev = ($page > 1) ? $page - 1 : 1;
            echo"
                <li><a href='albatsiq.php?module=inventory&act=purchaseOrderList&page=1'>First</a></li>
                <li><a href='albatsiq.php?module=inventory&act=purchaseOrderList&page=$link_prev'>&laquo;</a></li>
            ";
            }
           

           
			// Buat query untuk menghitung semua jumlah data
			$resut = mysql_query("select * from item_transaction where TRANSACTION_CODE = '001' ORDER BY item_transaction.TRANSACTION_NO DESC");
			$row = mysql_num_rows($resut);    
			$jumlah_page = ceil($row / $limit);
            $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
            $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
            $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
            for ($i = $start_number; $i <= $end_number; $i++) {
                $link_active = ($page == $i) ? 'class="active"' : '';
            echo" <li $link_active><a href=albatsiq.php?module=inventory&act=purchaseOrderList&page=".$i.">".$i."</a></li>";
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
                <li><a href='albatsiq.php?module=inventory&act=purchaseOrderList&page=$link_next'>&raquo;</a></li>
                <li><a href='albatsiq.php?module=inventory&act=purchaseOrderList&page=$jumlah_page'>Last</a></li>
            ";
            }
   echo"         
</ul>";
?>