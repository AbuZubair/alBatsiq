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
  
    $('#form_reset_pwd').ajaxForm({
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
        <h1 style="margin-top:10px;margin-left:10px;">Reset Password</h1>
    </div>
</div><br><br>

<form class="form-horizontal" method="post" id="form_reset_pwd" action="<?php echo site_url('control/user/proses_reset_pwd/'.$value->user_id.'')?>" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-md-6" style="text-align:right;">Password</label>
                <div class="col-md-4">
                <input name="password" id="password" value="" placeholder="" class="form-control" type="password" >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-6" style="text-align:right;">Password Confirmation</label>
                <div class="col-md-4">
                <input name="confirm" id="confirm" value="" placeholder="" class="form-control" type="password" >
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
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
    Submit
    </button>
</div>