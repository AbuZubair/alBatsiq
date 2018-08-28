
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Stock Adjustment</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=stockAdjust&id=nomorprod' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='SA No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=stockAdjust&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=stockAdjustNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Adjustment No. </th>
                    <th width='20%'> Unit </th>
                    <th width='30%'> Note</th>
					<th width='10%'> Date</th>
					<th width='10%'></th>
				</tr>
			";
		
       $cekAdjust =  mysql_query("select 
                                it.TRANSACTION_NO,it.TRANSACTION_DATE,unit.UNIT_NAME,
                                CASE
                                    when it.NOTE = 'salahHitung' THEN 'Salah Hitung'
                                    when it.NOTE = 'hilang' THEN 'Hilang'
                                    when it.NOTE = 'kecelakaan' THEN 'Kecelakaan'
                                    when it.NOTE = 'lainnya' THEN 'Lainnya'
                                END AS NOTETIPE
                                from item_transaction it left join unit on unit.LOC_ID = it.FROM_LOC_ID 
                                where it.TRANSACTION_CODE = '005' ORDER BY it.TRANSACTION_NO DESC LIMIT $start_from, $limit");
		while($adj = mysql_fetch_array($cekAdjust)){
			$tgl = explode("-",$adj['TRANSACTION_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$adj[TRANSACTION_NO]</td>
                    <td>$adj[UNIT_NAME]</td>
                    <td>$adj[NOTETIPE]</td>
					<td>$tanggal</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=stockAdjust&id=$adj[TRANSACTION_NO]><b>View</b></a></td>
				</tr>";
			}
			echo"
			</table>
			
			";
		


	echo"	<ul class='pagination'>";
           
            
            if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV
			  echo"
				<li class='disabled'><a href='#'>First</a></li>
                <li class='disabled'><a href='#'>&laquo;</a></li> ";
            
            } else { // Jika buka page ke 1
                $link_prev = ($page > 1) ? $page - 1 : 1;
            echo"
                <li><a href='albatsiq.php?module=inventory&act=stockAdjustList&page=1'>First</a></li>
                <li><a href='albatsiq.php?module=inventory&act=stockAdjustList&page=$link_prev'>&laquo;</a></li>
            ";
            }
           

           
			// Buat query untuk menghitung semua jumlah data
			$resut = mysql_query("select * from item_transaction where TRANSACTION_CODE = '005' ORDER BY TRANSACTION_NO DESC");
			$row = mysql_num_rows($resut);    
			$jumlah_page = ceil($row / $limit);
            $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
            $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
            $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
            for ($i = $start_number; $i <= $end_number; $i++) {
                $link_active = ($page == $i) ? 'class="active"' : '';
            echo" <li $link_active><a href=albatsiq.php?module=inventory&act=stockAdjustList&page=".$i.">".$i."</a></li>";
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
                <li><a href='albatsiq.php?module=inventory&act=stockAdjustList&page=$link_next'>&raquo;</a></li>
                <li><a href='albatsiq.php?module=inventory&act=stockAdjustList&page=$jumlah_page'>Last</a></li>
            ";
            }
   echo"         
</ul>";
?>