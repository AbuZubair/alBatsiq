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

var otable;
var base_url = $('#dynamic-table').attr('base-url'); 

$(document).ready(function(){
  
    $('#form_so_detail').ajaxForm({
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
          $('#page-area-content').load('inventory/stock/stock_opname?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

     //datatables
     otable = $('#dynamic-table').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "ordering": false, 

        "ajax": {
            "url": base_url+"/get_data_detail/<?php echo $value->so_no; ?>",
            "type": "POST"
        },

        });

})

</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;">Stock Opname</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
            <form class="form-horizontal" method="post" id="form_so_detail" action="inventory/stock/stock_opname/process_so" enctype="multipart/form-data">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Stock Opname No.</label>
                    <div class="col-md-4">
                      <input name="id" id="id" value="<?php echo isset($value)?$value->so_id:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
                      <input name='so_number' id='so_number' value='<?php echo isset($value)?$value->so_no:''?>' placeholder="" class="form-control" type="text" readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">
                      <input name="tgl" id="tgl" value="<?php echo ($flag!='create')?$value->so_date:date("Y-m-d")?>" placeholder="" class="form-control" type="text" readonly >
                    </div>
                  </div>

									<div class="form-group">
										<label class="control-label col-md-4">Unit</label>
                    <div class="col-md-4">
                      <input name='unit' id='unit' value="<?php echo isset($value)?$value->unit_name:''?>"" placeholder="" class="form-control" type="text" readonly>
                    </div>	
									</div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="control-label col-md-4">Note</label>
                    <div class="col-md-4">
                      <textarea rows='2' cols='50' name='insertNote' id='insertNote' placeholder='Enter note here..'><?php echo isset($value)?$value->note:''?></textarea>
                    </div>
                    
                  </div>
                </div>
              
              <div class="col-lg-12" style="height:30px">
                <a href="<?php echo base_url().'inventory/stock/stock_opname/exportPdf?a='.$value->loc_id.'&b='.$value->unit_name.''?>" target="blank"  id="" class="btn btn-m btn-success">
                  <i class="fa fa-file-word-o bigger-110"></i>
                  Print Blank Form
                </a>

                <a href="<?php echo base_url().'inventory/stock/stock_opname/exportPdf_result?a='.$value->loc_id.'&b='.$value->unit_name.''?>" target="blank"  id="" class="btn btn-m btn-primary" style="<?php echo ($value->so_status==1)?'':'display:none'?>">
                  <i class="fa fa-file-word-o bigger-110"></i>
                  Print Result Form
                </a>
              </div>

              <table id="dynamic-table" base-url="inventory/stock/stock_opname" class="display table table-striped table-bordered table-hover" width='100%' >
                <thead>
                    <tr>
                        <th width='5%'> No </th>
                        <th width='20%'> Item ID </th>
                        <th width='40%'> Item Name </th>
                        <th width='10%'> Quantity  </th>
                        <th width='10%'> Result </th>
                        <th width='15%'> Satuan </th>
                    </tr>
                </thead> 
                <tbody>
                    
                </tbody>
            </table>

                <div style="text-align:center;margin-bottom:5%;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('inventory/stock/stock_opname')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->

