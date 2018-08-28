<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script type="text/javascript">
$( function() {
    $( "#datepicker" ).datepicker();
	$( "#datepicker2" ).datepicker();
  } );
  
function sendValue(value)
{
	var table = document.getElementById("table");
	for(var i=1; i<=value; i++) {
		var row = table.rows[i];
		var chkbox = row.cells[3].childNodes[0];
		if(null != chkbox && true == chkbox.checked) {
			window.opener.updateValuePOR(chkbox.value);
			window.close();
		}
	}
}

function searchfuncDate(){
	var cek =  document.getElementById("datepicker").value;
	var cek2 =  document.getElementById("datepicker2").value;
	var ok = false;
	if(cek == '' || cek2 == ''){
		return ok;
	} else {
		document.getElementById("boom").style.display = "none"; 
	}
}


</script>

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
	left:50%;
	bottom:5%;
	padding:5px 15px;
}

</style>

</head>

<?php
	include "../../config/koneksi.php";

	$limit = 6;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;
	
	$date = date("m/d/Y");
	echo "
	<form method=POST action='selectSales.php?id=search' id='formSearch' onsubmit='return searchfuncDate()'>
		<table>
			<tr>
				<td rowspan=2><button type='submit'><i class='fa fa-search'></i></button></td>
				<td><input type='text' placeholder='Dari Tanggal..' id='datepicker' name='tgl1' value=''></td>
			</tr>
			<tr>
				<td><input type='text' placeholder='Sampai Tanggal..' id='datepicker2' name='tgl2' value=''></td>
			</tr>
		</table>
	</form>
	
		<table width='100%' id='table'>
				<tr>
					<th width='30%'> Sales No. </th>
					<th width='20%'> Sales Date </th>
					<th width='50%' colspan=2> Total Amount </th>
				</tr>
	
	";
	if($_GET['id'] == 'default'){
	$cekslfirst = mysql_query("SELECT DISTINCT * FROM sales WHERE SALES_CODE = '001' AND SALES_ID NOT IN ( SELECT DISTINCT REFERENCE_ID FROM sales where IS_COMPLETE = 1 ) ORDER BY SALES_ID DESC LIMIT $start_from, $limit");
	$numrowfirst = mysql_num_rows($cekslfirst);
	if($numrowfirst != 0){
		while($sl = mysql_fetch_array($cekslfirst)){
				$tgl = explode("-",$sl['SALES_DATE']);
                $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
                $amt = number_format($sl['CHARGE_AMOUNT'], 2, '.', ',');
				echo"
						<tr>
							<td>$sl[SALES_ID]</td>
							<td>$tanggal</td>";

							$cekcomplete = mysql_query("SELECT DISTINCT SALES_ID FROM sales WHERE SALES_ID = '$sl[SALES_ID]' AND SALES_ID NOT IN ( SELECT DISTINCT REFERENCE_ID FROM sales where REFERENCE_ID = '$sl[SALES_ID]' )");
							$numrowcek = mysql_num_rows($cekcomplete);
							$cekrefsl = mysql_query("SELECT * FROM sales WHERE REFERENCE_ID='$sl[SALES_ID]'");
							$refsl = mysql_fetch_array($cekrefsl);
							$amtref = $sl['CHARGE_AMOUNT'] + $refsl['CHARGE_AMOUNT'];
							$amtreffix = number_format($amtref, 2, '.', ','); 
							if($numrowcek == 0){
								echo"
							<td width='90%'>Rp. $amtreffix</td>";
							} else {
								echo "
							<td width='90%'>Rp. $amt</td>";
							}
							echo"
							<td><input type='radio' name='cek' value='$sl[SALES_ID]' id='radio'></td>
						</tr>
						";
					}
					echo"
					</table>
					<button id='btnok' onclick='sendValue($numrowfirst)'>OK</button>
			";
		}
		echo"	<ul class='pagination'>";
			
				
				if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV
				echo"
					<li class='disabled'><a href='#'>First</a></li>
					<li class='disabled'><a href='#'>&laquo;</a></li> ";
				
				} else { // Jika buka page ke 1
					$link_prev = ($page > 1) ? $page - 1 : 1;
				echo"
					<li><a href='selectSales.php?id=default&page=1'>First</a></li>
					<li><a href='selectSales.php?id=default&page=$link_prev'>&laquo;</a></li>
				";
				}
			

			
				// Buat query untuk menghitung semua jumlah data
				$resut =  mysql_query("SELECT DISTINCT * FROM sales WHERE SALES_CODE = '001' AND SALES_ID NOT IN ( SELECT DISTINCT REFERENCE_ID FROM sales where IS_COMPLETE = 1 ) ORDER BY SALES_ID DESC");
				$row = mysql_num_rows($resut);    
				$jumlah_page = ceil($row / $limit);
				$jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
				$start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
				$end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
				for ($i = $start_number; $i <= $end_number; $i++) {
					$link_active = ($page == $i) ? 'class="active"' : '';
				echo" <li $link_active><a href=selectSales.php?id=default&page=".$i.">".$i."</a></li>";
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
					<li><a href='selectSales.php?id=default&page=$link_next'>&raquo;</a></li>
					<li><a href='selectSales.php?id=default&page=$jumlah_page'>Last</a></li>
				";
				}
	echo"         
	</ul>";
	}
	
	if($_GET['id'] == 'search'){
		$search = $_POST['tgl1'];
		$sampaisearch = $_POST['tgl2'];
		
		$tgl = explode("/",$search);
		$tglskg = date("$tgl[2]-$tgl[0]-$tgl[1]");
		
		$tgl2 = explode("/",$sampaisearch);
		$tglskg2 = date("$tgl2[2]-$tgl2[0]-$tgl2[1]");
		$ceksl = mysql_query("select * from sales where SALES_DATE between '$tglskg'and '$tglskg2' and SALES_CODE='001' AND SALES_ID NOT IN ( SELECT DISTINCT REFERENCE_ID FROM sales where IS_COMPLETE = 1 ) ORDER BY SALES_ID DESC");
		$numrow = mysql_num_rows($ceksl);
				
		while($sl = mysql_fetch_array($ceksl)){
			$tgl = explode("-",$sl['SALES_DATE']);
            $tanggal = date("$tgl[2]-$tgl[1]-$tgl[0]");
            $amt = number_format($sl['CHARGE_AMOUNT'], 2, '.', ',');
			echo"
			<body onload='hiding()'>
				<tr>
					<td>$sl[SALES_ID]</td>
					<td>$tanggal</td>
					<td width='90%'>Rp. $amt</td>
					<td><input type='radio' name='cek' value='$sl[SALES_ID]' id='radio'></td>
				</tr>
				";
			}
			echo"
			</table>
			<button id='btnok' onclick='sendValue($numrow)' style='background-color: #008CBA;'>OK</button>
		";
	}
	
?>
	
</html>