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
  
    $('#form_user').ajaxForm({
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
          $('#page-area-content').load('control/User?_=' + (new Date()).getTime());
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
        <h1 style="margin-top:10px;margin-left:10px;">User</h1>
    </div>
</div>


 <form class="form-horizontal" method="post" id="form_user" action="<?php if(!isset($value)){echo site_url('control/user/process/create');}else{echo site_url('control/user/process/update');}?>" enctype="multipart/form-data">
     
    <div class="row">
        <div class="col-lg-6"> 

            <div class="form-group">
                <label class="control-label col-md-4">User ID</label>
                <div class="col-md-2">
                <input name="id" id="id" value="<?php echo isset($value)?$value->user_id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Username</label>
                <div class="col-md-6">
                <input name="username" id="username" value="<?php echo isset($value)?$value->username:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                </div>
                
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Nama Lengkap</label>
                <div class="col-md-6">
                <input name="nama_lengkap" id="nama_lengkap" value="<?php echo isset($value)?$value->nama_lengkap:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                </div>
                
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">User Role</label>
                <div class="col-md-6">
                        <select name='level_id' id='level_id' value="<?php echo isset($value)?$value->level_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                            <?php foreach ($level as $key): 
                                if(!isset($value)){
                                ?>
                                <option style="display:none;" selected></option>
                                <?php } ?>
                                <option value="<?php echo isset($key)?$key->level_id:''?>" <?php echo isset($value)?($value->level_id==$key->level_id)? 'selected="selected"':'':''; ?>><?php echo $key->level_name ?></option>
                            <?php endforeach ?>
                        </select>
                
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Unit</label>
                <div class="col-md-6">
                        <select name='unit_id' id='unit_id' value="<?php echo isset($value)?$value->unit_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                            <?php foreach ($unit as $key): 
                                if(!isset($value)){
                                ?>
                                <option style="display:none;" selected></option>
                                <?php } ?>
                                <option value="<?php echo isset($key)?$key->unit_id:''?>" <?php echo isset($value)?($value->unit_id==$key->unit_id)? 'selected="selected"':'':''; ?>><?php echo $key->unit_name ?></option>
                            <?php endforeach ?>
                        </select>
                
                </div>
            </div>
        </div>

        <div class="col-lg-6">                     
            <div class="form-group">
                <label class="control-label col-md-4">Password</label>
                <div class="col-md-6">
                <input name="password" id="password" value="" placeholder="" class="form-control" type="password" <?php echo ($flag!='create')?'readonly':''?> >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Password Confirmation</label>
                <div class="col-md-6">
                <input name="confirm" id="confirm" value="" placeholder="" class="form-control" type="password" <?php echo ($flag!='create')?'readonly':''?> >
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
            <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->username:0?>"> -->

            <a onclick="getMenu('control/user')" href="#" class="btn btn-sm btn-success">
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

