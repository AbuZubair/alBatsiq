<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AlBatsiq</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
		 <!-- css default for blank page -->
		 <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
		<script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
		<!-- highchat modules -->
		<script src="<?php echo base_url()?>assets/chart/highcharts.js"></script>
		<script src="<?php echo base_url()?>assets/chart/modules/exporting.js"></script>
		<script src="<?php echo base_url()?>assets/chart/modules/script.js"></script>
		<!-- end highchat modules -->	

		
    </head>
    <body>
    <div id="container">
		<div id="wrapper">
			<div id="header1">
				<h1>alBatsiq</h1>
			</div>
			
			<div id="header">
                <div id="keterangan">Welcome, <?php echo $_SESSION['nama'] ?><br><br>
                    <a href="<?php echo base_url(); ?>user/ganti_pwd">GANTI PASSWORD</a> | <a href="<?php echo base_url(); ?>login/logout" >LOGOUT</a> 
                </div>
			</div>
		</div>
		
		<div class="menu">
			<div class="dropdown_menu">
					<a href="<?php echo base_url(); ?>home">Home</a>
			</div>

					<?php  foreach($modul as $key_row=>$rows_m) : ?>
						
						<div class="dropdown_menu">

							<button class="dropbtn_menu"><?php echo $rows_m['program_name']?></button>

							<div class="dropdown-content_menu">
								
									<?php foreach($rows_m['menu'] as $key_row=>$row_modul) : $mods=(str_replace(' ', '_', strtolower($row_modul['program_name'])));
										if(isset($row_modul['submenu'])){?><div class='dropdown-submenu_menu'><?php } ?>
											<a href="<?php echo ($row_modul['link'] != '#')?base_url("dashboard?mod=$mods"):'#'?>" class="<?php echo ($row_modul['link'] == '#')?'dropbtn2_menu':''?>" tabindex='-1'><?php echo $row_modul['program_name']?></a> 
											<div class='dropdown-content-sub_menu'>
												<?php foreach($row_modul['submenu'] as $row_submodul) : $mods=(str_replace(' ', '_', strtolower($row_submodul->program_name)));?>
													<a href="<?php echo ($row_submodul->link != '#')?base_url("dashboard?mod=$mods"):'#'?>"><?php echo $row_submodul->program_name?></a> 
												<?php endforeach; ?>
											</div>
									<?php	if(isset($row_modul['submenu'])){ ?></div><?php } ?>
									<?php endforeach; ?>
								
							</div>
						</div>
					<?php endforeach; ?>
				 
		</div>

		
		<div id="content_graph">
		
		</div>
	
