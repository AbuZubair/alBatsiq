<?php
	$date = date("Y-m-d");
	$bulan = date("m");
	$tahun = date("Y");
	$sekarang = date_create($date);
	$tgl = explode("-",$date);
    $tglskg = date("$tgl[2]-$tgl[1]-$tgl[0]");
    $cekunit = mysql_query("select * from unit");
    $cekitem = mysql_query("select * from item");
	
		echo"	
			<div id='titlepage'>
					<div class = 'title-container'>
						<div class = 'txttitle'>
							<h1>Stock Information</h1>
						</div>
					</div>
			</div>
			
			<div id='wrapper-master'>
                <form method=POST action='albatsiq.php?module=search&act=stockInformation' id='form1' onsubmit='return setValues()'>
					<table width='100%'>
						<tr>
							<td width='20%' style='background-color:#001f3f;color:white;'>Unit</td>
							<td>
								<select name='locunit' width='20' id='unit' required>";
								while ($row = mysql_fetch_array($cekunit))
									{
										echo" 
										<option style='display:none;' selected></option>
										<option value='".$row['LOC_ID']."'>
											".$row['UNIT_NAME']."
										</option>";
									}   
								echo"					
								</select>
                            </td>
                            <td rowspan=2>
                            <button  type='submit' value='Simpan' form='form1' class='button' style='background-color: #008CBA;'>Search</button>
                            </td>
						</tr>
						<tr>
                            <td width='20%' style='background-color:#001f3f;color:white;'>Item</td>
                            <td>
							    <select name='item' width='20' id='ItemID'>";
									while ($row = mysql_fetch_array($cekitem))
										{
											echo" 
											<option style='display:none;' selected></option>
											<option value='".$row['ITEM_ID']." - ".$row['ITEM_NAME']."'>
												".$row['ITEM_ID']." - ".$row['ITEM_NAME']." 
											</option>";
										}   
									echo"					
                                </select>
                            </td>
						</tr>
                    </table>
                </form>            
            </div>
                   
            ";
?>