<?php
	echo"
			<div id='titlepage'>
				<div class = 'title-container'>
					<div class = 'txttitle'>
						<h1>Tambah Item Product</h1>
					</div>
				</div>
				<h3><a href=albatsiq.php?module=master&act=masteritemList class='link'>Back</a></h3>
			</div>
		
		
			<div id='wrapper-master'>
				<div class='content-left'>
					<form method=POST action='modul/mod_master/aksi_master.php?act=addItem' id='form3'>
						<table width='100%'>
							<tr>
								<td class='titlechild'>ID Item</td>
								<td><input type='text' name='itemid' size='5' id='itemid' required></td>
							</tr>
							<tr>
								<td class='titlechild'>Nama Item</td>
								<td><input type='text' name='itemname' size='50' id='itemname' required></td>
							</tr>
							<tr>
								<td class='titlechild'>Item Group</td>
								<td><select name='itemgroup' id='itemgroup'>
									  <option style='display:none;' disabled selected value>  </option>
									  <option value='Bahan'>Bahan</option>
									  <option value='Gamis'>Gamis</option>
									  <option value='Hijab'>Hijab</option>
									  <option value='Pasmina'>Pasmina</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Satuan Beli</td>
								<td><select name='satuanbeli' id='satuanbl'>
									  <option style='display:none;' disabled selected value> </option>
									  <option value='Meter'>Meter</option>
									  <option value='Gulung'>Gulung</option>
									  <option value='Buah'>Buah</option>
									  <option value='Pcs'>Pcs</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Satuan Jual</td>
								<td><select name='satuanjual'  id='satuanjl'>
									  <option style='display:none;' disabled selected value> </option>
									  <option value='Meter'>Meter</option>
									  <option value='Gulung'>Gulung</option>
									  <option value='Buah'>Buah</option>
									  <option value='Pcs'>Pcs</option>
									</select></td>
							</tr>
							<tr>
								<td class='titlechild'>Konversi</td>
								<td><input type='number' name='konversi' id='konversi' value='1'></td>
							</tr>
							<tr>
								<td class='titlechild'></td>
								<td><input type='checkbox' name='sales_available' id='sales_available' value='Y'> Untuk Dijual</td>
							</tr>
						</table>
					</form>
				</div>
				
				<div class='content-right'>
					<table width='100%'>
						<tr>
							<td class='titlechild'>Harga Beli</td>
							<td><input type='text' name='hrgbeli' value='0.00' readonly='readonly'></td>
						</tr>
						<tr>
							<td class='titlechild'>HPP</td>
							<td><input type='text' name='hpp' value='0.00' readonly='readonly' ></td>
						</tr>
						<tr>
							<td class='titlechild'>Harga Jual</td>
							<td><input type='text' name='hrgjual' value='0.00' readonly='readonly'></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div style='clear:both;'>
				<button  type='submit' value='Simpan' form='form3' class='button' style='background-color: #008CBA;'>Submit</button>
			</div>
		";
		
		
		$id = $_GET['id'];
		if ($id == 'confirmId'){
			$itemid = $_GET['itemid'];
			$itemname = $_GET['itemname'];
			$itemgroup = $_GET['itemgroup'];
			$satuanbeli = $_GET['satuanbeli'];
			$satuanjual = $_GET['satuanjual'];
			$konversi = $_GET['konversi'];
			$sales_available = $_GET['sales_available'];

			echo "<script type='text/javascript'>
				alert('ID sudah digunakan');
					document.getElementById('itemid').value  = '$itemid';
					document.getElementById('itemname').value  = '$itemname';
					document.getElementById('itemgroup').value = '$itemgroup';
					document.getElementById('satuanbl').value = '$satuanbeli';
					document.getElementById('satuanjl').value = '$satuanjual';
					document.getElementById('konversi').value = '$konversi';
					var x = '$sales_available';
					if(x == 'Y'){
						document.getElementById('sales_available').checked = true;
					} else {
						document.getElementById('sales_available').checked = false;
					}
			</script>";
		}
?>