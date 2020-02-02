<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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
  
    $('#form_item_tariff').ajaxForm({
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
          $('#page-area-content').load('control/master/tariff_item?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

})

</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;">Item Tarif</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_item_tariff" action="control/master/tariff_item/process/create" enctype="multipart/form-data">
              <table width='100%'>
                <tr>
                  <td style='color:white;background-color:#001f3f;text-align:left;' colspan='3'>Tariff Item</td>
                </tr>

                <tr>
                  <td width='50%'>Item*</td>
                  <td>Harga Jual*</td>
                  <td>Tanggal</td>
                <tr>
                
                <tr>
                  <td>
                    <?php echo $this->master->custom_selection(array('table' => 'item', 'id' => 'item_id', 'name' => 'item_name', 'where' => array('sales_available' => 'Y', 'is_deleted' => 'N')),isset($value)?$value->item_id:'',($flag=='read')?'readonly':'','item','item','col-lg-10 form-control','','') ?>     
                  </td>
                  <td>
                    <div class="col-md-6">
                      <input name="harga_jual" id="harga_jual" value="<?php echo isset($value)?$value->harga_jual:''?>" placeholder="0.00" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>>
                    </div>
                  </td>
                  <td>
                    <div class="col-md-6" style="text-align:center;">
                      <input name="tgl_item_tarif" id="harga_tgl_item_tarifjual" value="<?php echo date("d-m-Y")?>" class="form-control" type="text" readonly="readonly" style="text-align:center">
                    </div>
                  </td>
                </tr>
              </table>

                <div class="form-actions" style="text-align:center;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('control/master/tariff_item')" href="#" class="btn btn-sm btn-success">
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


