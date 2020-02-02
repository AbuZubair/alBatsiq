
<!-- page specific plugin styles -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-editable.css" />
		<link href="<?php echo base_url('assets/datatables/css/jquery.dataTables.min.css')?>" rel="stylesheet">
        <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
		<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>


	<div id="onload" >
		<div id="page-area-content">
				
		</div>
	</div>
</div>


    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script type="text/javascript">
      window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <script src="<?php echo base_url()?>assets/js/bootstrap-multiselect.js"></script>

    <!-- page specific plugin scripts -->


    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
    
    <!-- ace scripts -->
    <script src="<?php echo base_url()?>assets/js/ace/elements.scroller.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.colorpicker.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.fileinput.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.typeahead.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wysiwyg.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.spinner.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.treeview.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wizard.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.aside.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.ajax-content.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.touch-drag.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.submenu-hover.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-box.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-rtl.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-skin.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-on-reload.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.searchbox-autocomplete.js"></script>

    <!-- achtung loader -->
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>
    

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script>

    <!-- form date-time-->
    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
    <!-- end form date-time-->
    
    
    
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>


	<?php  foreach($modul as $key_row=>$rows_m) : ?>
		<script type="text/javascript">
            $('#content_graph').hide('fast');
			getMenu('<?php echo $rows_m['link']?>');
		</script>
	<?php endforeach; ?>

      </body>
	  

</html>