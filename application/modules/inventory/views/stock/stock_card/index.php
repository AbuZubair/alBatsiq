<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#form_Tmp_mst_function').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('registration/Reg_on_dashboard?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
})

$('#btn_search_data').click(function (e) {  

  $.getJSON("<?php echo site_url('inventory/stock/stock_card/getData') ?>?item=" + $('#insertItem').val() + "&loc=" + $('#insertlocunit').val(), '', function (data) {              

    $('#result tr').remove();                  

    $.each(data, function (i, o) {                  
        
        no=i+1
        st_in= (o.stock_in==null)?'-':o.stock_in;
        st_out= (o.stock_out==null)?'-':o.stock_out;
        ref= (o.loc_ref!=null)?o.unit_ref:o.unit;
        switch (o.transaction_code) {
          case '002':
              trans = 'Purchase Order Receive'
            break;
          case '003':
            trans = 'Purchase Order Return'
          break;
          case '100':
            trans = 'Distribusi'
          break;
          case '500':
            trans = 'Production'
          break;
          case '700':
            trans = 'Sales'
          break;
          case '710':
            trans = 'Sales Return'
          break;
        }

        $('<tr><td>'+no+'</td><td>'+o.item_name+'</td><td>'+o.unit+'</td><td>'+st_in+'</td><td>'+st_out+'</td><td>'+o.stock_before+'</td><td>'+o.stock_balance+'</td><td>'+o.satuan+'</td><td>'+trans+'</td><td>'+ref+'</td><td>'+o.created_date+'</td></tr>').appendTo($('#result'));                    
        
    });                

  }); 

$("#dynamic-table").show('fast');

});

$('#btn_export_excel').click(function (e) {  

  e.preventDefault();      

  window.open("<?php echo site_url('inventory/stock/stock_card/export') ?>?item=" + $('#insertItem').val() + "&loc=" + $('#insertlocunit').val()+"");


});

</script>
<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;"><?php if($level==2){ echo $title; } else { echo $parent; ?> <span style="font-size:20px;"> >> </span><span style="font-size:25px;"><?php echo $title; ?></span> <?php } ?></h1>
    </div>
</div>

<form class="form-horizontal" action="#">      
    <div class="col-md-12">

      <div class="form-group">
        <label class="control-label col-md-2">Item</label>
        <div class="col-md-2">
          <?php echo $this->master->custom_selection($params = array('table' => 'item', 'id' => 'item_id', 'name' => 'item_name', 'where' => array('is_active' => 'Y')), '' , '','insertItem', 'insertItem', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">

        <label class="control-label col-md-2">Unit</label>

        <div class="col-md-2">

          <?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), '' , '','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>

        </div>

      </div><br>

      <div class="form-group">
        <label class="control-label col-md-2" style="background:transparent"></label>
        <div class="col-md-4" style="text-align:left">
          <a href="#" id="btn_search_data" class="btn btn-m btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-m btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
      </div><br>

    </div>
</form>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" class="table table-bordered table-hover" style="display:none;">
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

      </tbody>
    </table>
    </div>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



