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
  
    $("#form-horizontal").validate({focusInvalid:true});
    $( "#inputItem" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          
          if($('#inputItem').val() != '' && $('#insertItemID').val()=='' ){
            data = [];
            data[0] = $('#inputItem').val();
            data[1] = $('#insertlocunit').val();
            $.ajax({
                url: "templates/References/getItemSalesAvailable",
                data: {data:data},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  //console.log(response)
                  if(response.length === 0){
                    achtungCreate("Item belum ditambahkan di unit",false)
                  }else{
                    $('#insertItemID').val($('#inputItem').val());
                    $.getJSON("<?php echo site_url('templates/References/getDataItem') ?>/" + $('#inputItem').val(), '', function (data) {              
                    
                      $('#insertSatuan option').remove();                

                      $('<option value="">-Pilih Satuan-</option>').appendTo($('#insertSatuan'));                

                      $.each(data, function (i, o) {   
                          item = $('#inputItem').val()+' : '+o.item_name;
                          $('#inputItem').val(item)               

                          $('<option value="' + o.satuan_beli + '" >' + o.satuan_beli + '</option>').appendTo($('#insertSatuan')); 
                          $('<option value="' + o.satuan_jual + '" selected>' + o.satuan_jual + '</option>').appendTo($('#insertSatuan'));                     
                          $('#insertknv').val(o.konversi);
                          //hrg = parseInt(o.harga_jual).toFixed(2); 
                          harga = o.harga_jual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
                          $('#inserthrg').val(harga);

                      });                

                    }); 
                  }
                }
            });
                       
            // addRecords()
          }else{
            if($(this).valid()){
              addRecords()
            }
            return false;  
          }
               
        }
    });

    // $('#form_item_sales').ajaxForm({
    //   beforeSend: function() {
    //     achtungShowLoader();  
    //   },
    //   uploadProgress: function(event, position, total, percentComplete) {
    //   },
    //   complete: function(xhr) {     
    //     var data=xhr.responseText;
    //     var jsonResponse = JSON.parse(data);

    //     if(jsonResponse.status === 200){
    //       $.achtung({message: jsonResponse.message, timeout:5});
    //       $('#page-area-content').load('inventory/transaksi_item/purchase_order_receive?_=' + (new Date()).getTime());
    //     }else{
    //       $.achtung({message: jsonResponse.message, timeout:5});
    //     }
    //     achtungHideLoader();
    //   }
    // }); 

    $('#btnSave').click(function (e) {      

      e.preventDefault();  

      total = $('#grand_total').val();
      if (total == '') {
        alert('Silahkan pilih barang terlebih dahulu !'); return false;    
      }else{
        $('#form_modal').load('sales/sales_new/form_payment?tot='+total);
        $("#GlobalModal").modal();
      }

    });   

    $('#GlobalModal').on('shown.bs.modal', function () {
        $('#insert_cash').focus();
    })

    $('#ModalKembalian').on('hidden.bs.modal', function () {
        $('#page-area-content').load('sales/sales_new/form');
    });

})
 
$('#form_item_sales').ajaxForm({ 
  

	beforeSend: function() {        

    achtungShowFadeIn();        

	},      

	uploadProgress: function(event, position, total, percentComplete) {        

	},      

	complete: function(xhr) {             

		var data=xhr.responseText;        

		var jsonResponse = JSON.parse(data);        

		if(jsonResponse.status === 200){          

      $.achtung({message: jsonResponse.message, timeout:5});        
      
      $("#GlobalModal").modal('hide');

      kembalian = jsonResponse.kembalian.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
      $('#form_kembalian').html('<h2>Kembalian Anda IDR '+kembalian+'</h2>')
      if(jsonResponse.kembalian!=0){
        $("#ModalKembalian").modal();
      }else{
        $('#page-area-content').load('sales/sales_new/form');
      }
      
			/*show action after success submit form*/
			//$('#page-area-content').load('sales/sales_new/form');


		}else{          

			$.achtung({message: jsonResponse.message, timeout:5});          

		}        

		//achtungHideLoader();        

	}      

});     

var bal=0;

$('#inputItem').typeahead({
    minLength: 3,
    source: function (query, result) {
			//console.log(query)
        data = [];
        data[0] = query;
        data[1] = $('#insertlocunit').val();
       // console.log(data);
        $.ajax({
            url: "templates/References/getItemSalesAvailable",
            data: {data:data},            
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
      var bal_ = item.split(':')[2];
      bal = bal_.split('')[1];
      // console.log(bal);
      $('#insertItemID').val(val_item);

      if (val_item) { 
        $.getJSON("<?php echo site_url('templates/References/getDataItem') ?>/" + val_item, '', function (data) {              

          $('#insertSatuan option').remove();                

          $('<option value="">-Pilih Satuan-</option>').appendTo($('#insertSatuan'));                

          $.each(data, function (i, o) {                  

              $('<option value="' + o.satuan_beli + '" selected>' + o.satuan_beli + '</option>').appendTo($('#insertSatuan')); 
              $('<option value="' + o.satuan_jual + '">' + o.satuan_jual + '</option>').appendTo($('#insertSatuan'));                     
              $('#insertknv').val(o.konversi);
              //hrg = parseInt(o.harga_jual).toFixed(2); 
              harga = o.harga_jual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
              $('#inserthrg').val(harga);

          });                

        }); 
      }else{
        $('#insertSatuan option').remove()  
      }
    }
});


if($("#jmlcell").val()==''){
  var i=0;
}else{
  var i = $("#jmlcell").val()
}
var sum = 0;

function addRecords() {
  var ok = true;

  console.log($('#insertqty').val());
  console.log(bal);
  
	if ($('#inputItem').val() == '' || $('#insertqty').val() == '' || $('#insertSatuan').val() == '' || $('#inserthrg').val() == ''){
		//document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		achtungCreate("*Please Fill All Required Field",false)
		return ok;
	} else if ($('#insertqty').val() == 0 || $('#inserthrg').val() == 0){
		achtungCreate("Quantity / Harga Tidak boleh 0",false)
		return ok;
	// } else if($('#insertqty').val() > bal){
  //   achtungCreate("Balance tidak cukup!!",false)
	// 	return $("#insertqty").focus();
  }else {

		$("#record").find('input[id="InputItemID"]').each(function(){
				if($(this).val() == $('#inputItem').val()){
					achtungCreate("Item Sudah ada dalam list",false);
          ok = false;
					return ok;
				}
		});

    if(ok == true){
      $("#res_head").show('fast');

      var dc = $("#insertdisc").val(),
			    dcamt = $("#insertdisc_amt").val();

      if(dc != ""){
        var hrg = $("#inserthrg").val(),
            harga = parseFloat(hrg.replace(/,/g, ''));
        var disc = (dc / 100) * harga,
            discfix = disc.toFixed(2),
            disccur = discfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            discount = disc;  
      }else if(dcamt != ""){
        var hrg = $("#inserthrg").val(),
            harga = parseFloat(hrg.replace(/,/g, ''));
        var disc = parseInt(dcamt),
            discfix = disc.toFixed(2);
            disccur = discfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            discount = disc;  
      } else {
        var hrg = $("#inserthrg").val(),
            harga = parseFloat(hrg.replace(/,/g, '')),
            disc = 0;
            discount = 0;
      }

      var total = harga - disc,
          totalamt = total * parseInt($("#insertqty").val()),
          totalfix = totalamt.toFixed(2), 
          totalcur = totalfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
          total_ = totalcur; 
          sum += totalamt;
      var sumfix = sum.toFixed(2),
          sumcur = sumfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      console.log(sum)
      $('<tr><td width="15%""><input type="number" name="qty'+i+'" class="form-control" value="'+$('#insertqty').val()+'" readonly="readonly"></td><td width="20%"><input name="itemID'+i+'" class="form-control" value="'+$('#inputItem').val()+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%"><input type="text" name="satuan'+i+'" class="form-control" value="'+$('#insertSatuan').val()+'" readonly="readonly"></td><td width="15%"><input type="text" name="harga'+i+'" class="form-control" value="'+ $('#inserthrg').val()+'" readonly="readonly"></td><td width="15%"><input type="number" name="dsc'+i+'" class="form-control" value="'+ discount +'" readonly="readonly"></td><td width="15%"><input type="text" id="total" name="total'+i+'" class="form-control" value="'+ total_ +'" readonly="readonly"></td><td align="center"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_sales'));
      
      $("#jmlcell").val(i);
      i++;

      $("#show_grand_total").show('fast');
      $("#grand_total").val(sumcur);

      $('#inputItem').val('');
      $('#insertItemID').val('');
      $('#insertqty').val(1);
      $('#inserthrg').val('');
      $('#insertdisc').val('');
      $('#insertdisc_amt').val('');
      $('#table_input').find('select').val('');
    }


	}

 
}


function removeRow(){
	$("#record").find('input[id="chk"]').each(function(){
      if($(this).is(":checked")){
        $(this).parents("tr").each(function(){
          //console.log($(this).find('input[id="InputBahan_dipakai"]').val())
          //console.log($('#qty_bahan').val())
          total = $(this).find('input[id="total"]').val();
          amtfloat = parseFloat(total.replace(/,/g, ''));
          gt = parseFloat($("#grand_total").val().replace(/,/g, ''));
          sum = gt - amtfloat;
          var sumfix = sum.toFixed(2),
              sumcur = sumfix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $("#grand_total").val(sumcur);
        });

          $(this).parents("tr").remove();
      }
  });
}
				

</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;">Sales</h1>
    </div>
</div>

<form class="form-horizontal" method="post" id="form_item_sales" action="sales/sales_new/process" enctype="multipart/form-data">
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Sales No.</label>
                    <div class="col-md-4">
                      <input name='salesnumber' id='salesnumber' value='<?php echo isset($value)?$value->sales_no:$trans_no?>' placeholder="" class="form-control" type="text" readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">
                      <input name="tgl" id="tgl" value="<?php echo date("Y-m-d")?>" placeholder="" class="form-control" type="text" readonly >
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

                  <div class="form-group">
										<label class="control-label col-md-4">Unit</label>
                    <div class="col-md-4">
											<?php if($flag=='create'){
                          echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($_SESSION['loc'])?$_SESSION['loc']:'' , ($_SESSION['leveluser']!=1)?'readonly':'','insertlocunit', 'insertlocunit', 'form-control', '', ''); } else{ 
                          echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), $value->loc_id , ($_SESSION['leveluser']!=1)?'readonly':'','insertlocunit', 'insertlocunit', 'form-control', '', ''); } ?>
                    </div>	
									</div>
                </div>
              
              <br>

              <table width='100%' id='insert_table'>
                <tr>
                  <td style='color:white;background-color:#001f3f;text-align:left;' colspan='7'>Item</td>
                </tr>

                <tr>
                  <td width='15%'>Quantity*</td>
                  <td width='20%'>Item ID*</td>                  
                  <td width='10%'>Satuan*</td>
                  <td width='15%'>Harga*</td>
                  <td width='15%'>Discount</td>
                  <td width='15%'>Discount Amount</td>
                  <td width='10%'></td>
                <tr>
                
                <tr>
                  <td>
                    <input name="insertqty" id="insertqty" value="1" placeholder="0" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>> 
                  </td>
                  <td>
                    <input id="inputItem" class="form-control" name="inputItem" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" <?php echo ($flag=='read')?'readonly':''?> autofocus/>
                    <input type="hidden" name="insertItemID" value="" id="insertItemID">
                  </td>
                  <td>     
                    <?php echo $this->master->get_change($params = array('table' => 'tmp_mst_satuan', 'id' => 'satuan_id', 'name' => 'satuan_name', 'where' => array()), '' , 'insertSatuan', 'insertSatuan', 'form-control', '', '',($flag=='read')?'readonly':'') ?>
                  </td>
                  <td>   
                  <input class="form-control" type='text' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg' <?php echo ($flag=='read')?'readonly':''?>>  
                  </td>
                  <td>      
                  <input style="display:inline;width:60px;" name="insertdisc" id="insertdisc" value="" placeholder="0" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>>&nbsp;%  
                  </td>
                  <td>      
                    <input class="form-control" type='number' step='0.01' placeholder='0.00' name='insertdisc_amt' id='insertdisc_amt' <?php echo ($flag=='read')?'readonly':''?>> 
                  </td>
                  <td>
                    <a id='btn_insert' class="btn btn-sm btn-primary" onclick="<?php echo ($flag!='read')?'addRecords()':''?>"><i class='fa fa-plus-circle'></i> Add</a>
                  </td>
                </tr>
                <tr style="<?php echo ($flag=='read')?'display:none':''?>">
                  <td colspan='7'  style="text-align:left">
                    <a class='btn btn-sm btn-danger' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
                  </td>
                </tr>
              </table>
							<div class="col-md-1" style="display:none">
								<input name="id" id="id" value="<?php echo isset($value)?$value->sales_id:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
							</div>

              <table width='100%' id='record'>
                
                <input type='hidden' name='jmlcell' id='jmlcell' value='<?php echo ($value_numrow!=0)?$value_numrow:''?>'>
                                
								<tbody id="result_sales">

										<tr id="res_head" style='<?php echo ($flag!='read')?'display:none;()':''?>'>
                      <td width='15%'>Quantity*</td>
                      <td width='20%'>Item ID*</td>
                      <td width='10%'>Satuan*</td>
                      <td width='15%'>Harga*</td>
                      <td width='15%'>Discount</td>
                      <td width='15%'>Total</td>
                      <td width='10%'></td>
                    <tr>
                    
                    <?php if(isset($value_detail)){$i=0; foreach($value_detail as $key_row=>$rows_m) :?>
											<tr>
                        <td width="15%"><input type="number" name="qty<?php echo $i?>" class="form-control" value="<?php echo $rows_m->quantity ?>" readonly="readonly"></td>
                        <td width="20%"><input name="itemID<?php echo $i?>" class="form-control" value="<?php echo ''.$rows_m->item_id.' : '.$rows_m->item_name.'' ?>"  id="InputItemID" size="30" readonly="readonly"></td>
												<td width="10%"><input type="text" name="satuan<?php echo $i?>" class="form-control" value="<?php echo $rows_m->satuan ?>" readonly="readonly"></td>
												<td width="15%"><input type="text" name="harga<?php echo $i?>" class="form-control" value="<?php echo number_format($rows_m->harga_jual,2) ?>" readonly="readonly"></td>
												<td width="15%"><input type="text" name="dsc<?php echo $i?>" class="form-control" value="<?php echo number_format($rows_m->discount_amount,2) ?>" readonly="readonly"></td>
                        <td width="15%"><input type="text" name="total<?php echo $i?>" class="form-control" value="<?php echo number_format($rows_m->total_amount,2) ?>" readonly="readonly"></td>
												<td width="10%"><input type="checkbox" name="chk<?php echo $i?>" id="chk" value="Y" readonly="readonly"></td>
											</tr>
										<?php $i++; endforeach; }?>
								</tbody>
							
              </table>

              <table width='100%' id='show_grand_total' style='<?php echo ($flag!='read')?'display:none;()':''?>'>
                <td style='color:white;background-color:#001f3f;text-align:right;' width='75%'>
                  Grand Total 
                </td>	
                <td style='color:white;background-color:#001f3f;text-align:left;' width='25%' colspan='2'>
                  <input type="text" id="grand_total" name="grand_total" class="form-control" value='<?php echo isset($value)?number_format($value->charge_amount, 2):''?>' readonly="readonly">
                </td>				
              </table>

                <div class="form-actions" style="text-align:center;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('sales/sales_new')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Payment
                  </button>
                  <?php endif; ?>
                </div>
              
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->

<div id="GlobalModal" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span style="color:white;text-align:left!important"><h3>PAYMENT</h3></span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_modal"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button type="submit" class="btn btn-sm btn-primary pull-left" name="submit" id="btnSubmit">

          <i class="ace-icon fa fa-save"></i>

          Submit

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->
                
</div>

</form>      

<div id="ModalKembalian" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span style="color:white;"><h3>Terima Kasih</h3></span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_kembalian"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button type="submit" class="btn btn-sm btn-primary pull-left" name="submit" id="btnSubmit">

          <i class="ace-icon fa fa-save"></i>

          Submit

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->
                
</div>

