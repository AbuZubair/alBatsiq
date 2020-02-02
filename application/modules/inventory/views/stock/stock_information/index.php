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

    $('#inputItem').typeahead({
        source: function (query, result) {
          console.log(query)
            $.ajax({
                url: "templates/References/getItem",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          //console.log(val_item);
          $('#insertItem').val(val_item);

          
        }
    });

})

$('#btn_search_data').click(function (e) {  

  $.getJSON("<?php echo site_url('inventory/stock/stock_information/getData') ?>?item=" + $('#insertItem').val() + "&loc=" + $('#insertlocunit').val(), '', function (data) {              

    $('#result tr').remove();                  

    $.each(data, function (i, o) {                  
        
        no=i+1
        $('<tr><td>'+no+'</td><td>'+o.item_name+'</td><td>'+o.unit_name+'</td><td>'+o.balance+'</td><td>'+o.satuan+'</td><td>'+o.updated_date+'</td></tr>').appendTo($('#result'));                    

    });                

  }); 

$("#dynamic-table").show('fast');

});


$('#btn_export_excel').click(function (e) {  

  e.preventDefault();      

  window.open("<?php echo site_url('inventory/stock/stock_information/export') ?>?item=" + $('#insertItem').val() + "&loc=" + $('#insertlocunit').val()+"");
  

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
        <div class="col-md-4">
          <?php // $this->master->custom_selection($params = array('table' => 'item', 'id' => 'item_id', 'name' => 'item_name', 'where' => array('is_active' => 'Y')), '' , '','insertItem', 'insertItem', 'form-control', '', '') ?>
          <input id="inputItem" class="form-control" name="inputItem" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
          <input type="hidden" name="insertItem" value="" id="insertItem">
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
          <th>Balance</th>
          <th>Satuan</th>
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



