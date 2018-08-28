
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;
	echo"			
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Production</h1>
					</div>
					
					<div class='search-container'>
						<form method=POST action='albatsiq.php?module=search&act=production&id=nomorprod' id='formSearch1' onsubmit='return searchfunc()'>
						  <button type='submit'><i class='fa fa-search'></i></button>
						  <input type='text' placeholder='PROD No..' name='search' id='srch'>
						</form>
					 </div>
					  <div class='search-container'>
							<form method=POST action='albatsiq.php?module=search&act=production&id=dates' id='formSearch2' onsubmit='return searchfuncDate()'>
							  <button type='submit'><i class='fa fa-search'></i></button>
							  <input type='text' placeholder='Search Tanggal..' id='datepicker' name='tgl'>
							</form>
						 </div>
				</div>
				<h3><a href=albatsiq.php?module=inventory&act=productionNew class='link'>New</a></h3>
			</div>
			
			
			<table width='100%'>
				<tr>
					<th width='30%'> Production No. </th>
					<th width='20%'> Bahan Dasar </th>
					<th width='40%'> Date</th>
					<th width='10%'></th>
				</tr>
			";
		
	   $cekprod = mysql_query("select DISTINCT production.PROD_ID, production.PROD_DATE, item.ITEM_NAME from production 
								inner join production_detail on production_detail.PROD_ID = production.PROD_ID
	   							left join item on item.ITEM_ID = production_detail.BAHAN_ID 
                                ORDER BY production.PROD_ID DESC LIMIT $start_from, $limit");
		while($prod = mysql_fetch_array($cekprod)){
			$tgl = explode("-",$prod['PROD_DATE']);
			$tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
			echo"
				<tr>
					<td>$prod[PROD_ID]</td>
					<td>$prod[ITEM_NAME]</td>
					<td>$tanggal</td>
					<td><a class='link' style='text-decoration: none;' href=albatsiq.php?module=view&act=production&id=$prod[PROD_ID]><b>View</b></a></td>
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
                <li><a href='albatsiq.php?module=inventory&act=productionList&page=1'>First</a></li>
                <li><a href='albatsiq.php?module=inventory&act=productionList&page=$link_prev'>&laquo;</a></li>
            ";
            }
           

           
			// Buat query untuk menghitung semua jumlah data
			$resut = mysql_query("select * from production ORDER BY PROD_ID DESC");
			$row = mysql_num_rows($resut);    
			$jumlah_page = ceil($row / $limit);
            $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
            $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
            $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
            for ($i = $start_number; $i <= $end_number; $i++) {
                $link_active = ($page == $i) ? 'class="active"' : '';
            echo" <li $link_active><a href=albatsiq.php?module=inventory&act=productionList&page=".$i.">".$i."</a></li>";
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
                <li><a href='albatsiq.php?module=inventory&act=productionList&page=$link_next'>&raquo;</a></li>
                <li><a href='albatsiq.php?module=inventory&act=productionList&page=$jumlah_page'>Last</a></li>
            ";
            }
   echo"         
</ul>";
?>