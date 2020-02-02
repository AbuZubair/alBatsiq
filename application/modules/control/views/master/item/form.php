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
  
    $('#form_item').ajaxForm({
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
          $('#page-area-content').load('control/master/item?_=' + (new Date()).getTime());
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
        <h1 style="margin-top:10px;margin-left:10px;">Item</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_item" action="<?php if(!isset($value)){echo site_url('control/master/item/process/create');}else{echo site_url('control/master/item/process/update');}?>" enctype="multipart/form-data">
                <br>
              <div class="row">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Item ID</label>
                    <div class="col-md-4">
                      <input name="id" id="id" value="<?php echo isset($value)?$value->item_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Item Name</label>
                    <div class="col-md-4">
                      <input name="item_name" id="item_name" value="<?php echo isset($value)?$value->item_name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Item Group</label>
                    <div class="col-md-4">
                      <select name='group_item_id' id='group_item_id' value="<?php echo isset($value)?$value->group_item_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                            <?php foreach ($group as $key): 
                                if(!isset($value)){
                                ?>
                                <option style="display:none;" selected></option>
                                <?php } ?>
                                <option value="<?php echo isset($key)?$key->group_item_id:''?>" <?php echo isset($value)?($value->group_item_id==$key->group_item_id)?'selected="selected"':'':''; ?>><?php echo $key->group_name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Satuan Beli</label>
                    <div class="col-md-4">
                      <select name='satuan_beli_id' id='satuan_beli_id' value="<?php echo isset($value)?$value->satuan_beli_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                            <?php foreach ($satuan as $key): 
                                if(!isset($value)){
                                ?>
                                <option style="display:none;" selected></option>
                                <?php } ?>
                                <option value="<?php echo isset($key)?$key->satuan_id:''?>" <?php echo isset($value)?($value->satuan_beli_id==$key->satuan_id)?'selected="selected"':'':''; ?>><?php echo $key->satuan_name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Satuan Jual</label>
                    <div class="col-md-4">
                      <select name='satuan_jual_id' id='satuan_jual_id' value="<?php echo isset($value)?$value->satuan_jual_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                            <?php foreach ($satuan as $key): 
                                if(!isset($value)){
                                ?>
                                <option style="display:none;" selected></option>
                                <?php } ?>
                                <option value="<?php echo isset($key)?$key->satuan_id:''?>" <?php echo isset($value)?($value->satuan_jual_id==$key->satuan_id)?'selected="selected"':'':''; ?>><?php echo $key->satuan_name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Konversi</label>
                    <div class="col-md-3">
                      <input name="konversi" id="konversi" value="<?php echo isset($value)?$value->konversi:''?>" placeholder="" class="form-control" type="number" <?php echo ($flag=='read')?'readonly':''?> >
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Untuk Dijual</label>
                    <div class="col-md-3" style="text-align:left;">
                      <input type="checkbox" name="sales_available" value="Y" <?php echo isset($value) ? ($value->sales_available == 'Y') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6"> 
                  
                  <div class="form-group">
                    <label class="control-label col-md-4">Harga Beli</label>
                    <div class="col-md-3">
                      <input name="harga_beli" id="harga_beli" value="<?php echo isset($value)?$value->harga_beli:''?>" placeholder="0.00" class="form-control" type="text" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Harga Jual</label>
                    <div class="col-md-3">
                      <input name="harga_jual" id="harga_jual" value="<?php echo isset($value)?$value->harga_jual:''?>" placeholder="0.00" class="form-control" type="text" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">HPP</label>
                    <div class="col-md-3">
                      <input name="hpp" id="hpp" value="<?php echo isset($value)?$value->hpp:''?>" placeholder="0.00" class="form-control" type="text" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-4">Aktif?</label>
                      <div class="col-md-2">
                      <div class="radio" style="text-align:left;">
                              <label >
                              <input name="is_active" type="radio" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                              <span class="lbl"> Ya</span>
                              </label>
                              <label>
                              <input name="is_active" type="radio" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                              <span class="lbl">Tidak</span>
                              </label>
                      </div>
                      </div>
                  </div>
                </div>
              </div>

                <div class="form-actions center">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('control/master/item')" href="#" class="btn btn-sm btn-success">
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


