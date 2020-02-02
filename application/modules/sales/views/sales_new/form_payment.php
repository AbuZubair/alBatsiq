<script type="text/javascript">

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

      // $('#btnSubmit').focus();  


      $('select[name="payment_type"]').change(function () {      

          if ($(this).val() == 'debit/kredit') {        
              $('#bank_account').show('fast');
              $('#cash_amount').hide('fast');
          }else{
              $('#bank_account').hide('fast');
              $('#cash_amount').show('fast');
          }
      }); 

      $( "#insert_cash" )
        .keypress(function(event) {
          var keycode =(event.keyCode?event.keyCode:event.which); 
          if(keycode ==13){
            $('#form_item_sales').submit();
            return false;       
          }
      });

    });

</script>

<div class="row">

  <div class="col-lg-12"> 
    <div class="form-group">
      <label class="control-label col-md-4">Payment Type</label>
      <div class="col-md-4">
        <select  class="form-control" name="payment_type" id="payment_type" >
          <option value="cash">Cash</option>
          <option value="debit/kredit">Debit / Kredit</option>
        </select>
      </div>
      
    </div>
  </div>

  <div class="col-lg-12"> 
    <div class="form-group" id="bank_account" style="display:none">
      <label class="control-label col-md-4">Bank</label>
      <div class="col-md-4">
        <?php 
            echo $this->master->custom_selection($params = array('table' => 'tmp_bank_account', 'id' => 'ba_id', 'name' => 'bank_name', 'where' => array('is_active' => 'Y')), '','', 'bank_account', 'bank_account', 'form-control', ''); 
        ?>
      </div>	
    </div>
  </div>

  <div class="col-lg-12"> 
    <div class="form-group" id="bank_account">
      <label class="control-label col-md-4">Total</label>
      <div class="col-md-4">
        <input type="text" class="form-control" value='IDR <?php echo $total ?>' readonly="readonly">
      </div>	
    </div>
  </div>

  <div class="col-lg-12"  id='cash_amount'> 
    <div class="form-group" id="bank_account">
      <label class="control-label col-md-4">Cash</label>
      <div class="col-md-4">
        <input class="form-control" type='text' step='0.01' placeholder='0.00' name='insert_cash' id='insert_cash'>  
      </div>	
    </div>
  </div>

  <div class="col-lg-12" style="text-align:center !important;margin:20px 0 !important">

    <button type="submit" id="btnSubmit" class="btn btn-sm btn-primary" name="submit" >

      <i class="ace-icon fa fa-save"></i>

      Submit

    </button>
  </div>

</div>
