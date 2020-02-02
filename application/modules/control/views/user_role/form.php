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
  
    $('#form_Tmp_role_has_menu').ajaxForm({
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
          $('#page-area-content').load('control/user_role?_=' + (new Date()).getTime());
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
        <h1 style="margin-top:10px;margin-left:10px;">Role Privilege</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_Tmp_role_has_menu" action="<?php echo site_url('control/user_role/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">Level ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->level_id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                  
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Level Name</label>
                  <div class="col-md-2">
                    <input name="level_name" id="level_name" value="<?php echo isset($value)?$value->level_name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Desciption</label>
                  <div class="col-md-4">
                  <textarea name="description" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:50px !important"><?php echo isset($value)?$value->description:''?></textarea>
                  </div>
                </div>

                 <div class="form-group">
                  <label class="control-label col-md-2">Aktif?</label>
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
              
                <div class="form-group">
                  <label class="control-label col-md-2">&nbsp;</label>
                  <div class="col-md-10">
                    <table class="table table-striped table-bordered">
                          <thead>
                            <tr style="color:black">
                              <th class="center">Nama Menu</th>
                              <?php 
                                foreach ($function as $key => $row_function) {
                              ?>
                              <th class="center"><?php echo strtoupper($row_function->name). '<br>[ '.$row_function->code.' ]'?></th>
                              <?php }?>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                              foreach ($menus as $key2 => $row_menus) {
                            ?>
                            <tr>
                              <td>
                                <?php echo ucfirst($row_menus['program_name']).''?>
                                <input type="hidden" name="program_id[]" value="<?php echo $row_menus['program_id']?>">
                              </td>

                               <?php 
                                foreach ($function as $key3 => $func_row) {
                              ?>

                              <td class="center">
                                <label class="pos-rel">
                                  <?php if($row_menus['link'] != '#'){?>
                                    <input type="checkbox" name="<?php echo $row_menus['program_id']; ?>[]" value="<?php echo $func_row->code?>" class="ace" <?php echo isset($value)?$this->m_user_role->get_checked_form($row_menus['program_id'], $value->level_id, $func_row->code):''?>/>
                                    <span class="lbl"></span>
                                  <?php }?>
                                </label>
                              </td>

                              <?php }?>

                            </tr>
                            <?php foreach ($row_menus['menu'] as $rowsubmenu) {?>
                                <tr>
                                <td style="text-align:left;">&nbsp;&nbsp;&nbsp;<i class="fa fa-circle-o"></i> <?php echo ucfirst($rowsubmenu['program_name'])?>
                                <input type="hidden" name="program_id[]" value="<?php echo $rowsubmenu['program_id']?>">
                                </td>

                                 <?php 
                                  foreach ($function as $key4 => $func_row) {?>
                                <td>
                                  <label class="pos-rel">
                                    <input type="checkbox" name="<?php echo $rowsubmenu['program_id']; ?>[]" value="<?php echo $func_row->code?>" class="ace" <?php echo isset($value)?$this->m_user_role->get_checked_form($rowsubmenu['program_id'], $value->level_id, $func_row->code):''?>/>
                                    <span class="lbl"></span>
                                  </label>
                                </td>

                                <?php }?>

                              </tr>

                                   <?php if(isset($rowsubmenu['submenu'])){ foreach ($rowsubmenu['submenu'] as $rowsubmenu_2) { ?>
                                    <tr>
                                    <td style="padding-left:20px;text-align:left;">&nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> <?php echo ucfirst($rowsubmenu_2->{'program_name'})?>
                                    <input type="hidden" name="program_id[]" value="<?php echo $rowsubmenu_2->{'program_id'}?>">
                                    </td>

                                    <?php 
                                      foreach ($function as $key5 => $func_row) {
                                    ?>

                                    <td>
                                      <label class="pos-rel">
                                        <input type="checkbox" name="<?php echo $rowsubmenu_2->{'program_id'}; ?>[]" value="<?php echo $func_row->code?>" class="ace" <?php echo isset($value)?$this->m_user_role->get_checked_form($rowsubmenu_2->{'program_id'}, $value->level_id, $func_row->code):''?>/>
                                        <span class="lbl"></span>
                                      </label>
                                    </td>

                                    <?php }?>

                                  </tr>

                                  <?php } }?>

                              <?php }?>
                            
                            <?php }?>
                        </tbody>
                    </table>
                  </div>
                </div>
               


                <div class="form-actions center">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('control/User_role')" href="#" class="btn btn-sm btn-success">
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


