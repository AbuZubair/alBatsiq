<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
   		 <meta charset="utf-8" />
		<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">

		<!-- bootstrap & fontawesome -->
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

		
	</head>
	<body>
    <div class="container">
      <div class="row content">
        <div class="header col-lg-5 col-xs-12">
          <div style="text-decoration:underline;"><span>al</span>Batsiq</div>
        </div>
            
        <div class="login col-lg-7 col-xs-12">
          <form action="<?php echo base_url(); ?>login/cek_login" method="POST" id="form-login">
          
            <div class="form-row">
              <div class="col-md-12 ">
                <label>Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo set_value('username')?>">
              </div>
            </div>

            <div class="form-row">
              <div class="col-md-12 ">
                <label>Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo set_value('password')?>">
              </div>
            </div>

          

            <!--	<input type="text" placeholder="Username" name="username" id="username"><?php echo form_error('username'); ?><br>
              <input type="password" placeholder="Password" name="password" id="password"><br><br> -->
              <div class="form-row">
                <input type="button" id="button-login" value="Sign In" class="width-35 center-block btn btn-sm btn-primary" >
              </div>
          </form>
        </div>
      </div>
    </div>

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
			</script>

			<!-- <![endif]-->

			<!--[if IE]>
		<script type="text/javascript">
		window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery1x.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
			<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>

		<link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
     
      <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
      <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script> 

      
      <script>
      $('document').ready(function() {  

        $("#form-login").validate({focusInvalid:true});     
        $( "#username" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              event.preventDefault();
              if($(this).valid()){
                $('#password').focus();
              }
              return false;       
            }
        });
        
        $( "#password" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              if($("#form-login").valid()) {  
                $('#form-login').ajaxForm({
                  beforeSend: function() {
                    achtungCreate("Data sedang di Proses, Silahkan tunggu.",true);
                  },
                  uploadProgress: function(event, position, total, percentComplete) {
                  },
                  complete: function(xhr) {     
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status === 200){
                      window.location = '<?php echo base_url().'home'?>';
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:5});
                    }
                    achtungHideLoader();
                  }

                });
              }
              $("#form-login").submit();
            }
        });
        
        $( "#button-login" )
          .on("click",function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which);   
                $('#form-login').ajaxForm({
                  beforeSend: function() {
                    achtungCreate("Data sedang di Proses, Silahkan tunggu.",true);
                  },
                  complete: function(xhr) {  
                    //alert(xhr.responseText); return false;
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status === 200){
                      window.location = '<?php echo base_url().'home'?>';
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:5});
                    }
                    achtungHideLoader();
                  }
                });
              $("#form-login").submit();
            
        });
        
        $("#form-login input:text").first().focus();
        
      });
      </script>

      <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
      <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 
	</body>
</html>