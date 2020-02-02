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
          if($(this).valid()){
            addRecords()
          }
          return false;       
        }
    });

    $('#form_item_sales').ajaxForm({
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
          $('#page-area-content').load('inventory/transaksi_item/purchase_order_receive?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
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

			/*show action after success submit form*/
			$('#page-area-content').load('sales/sales_return?_=' + (new Date()).getTime());


		}else{          

			$.achtung({message: jsonResponse.message, timeout:5});          

		}        

		//achtungHideLoader();        

	}      

});     

function outstanding() {

  $.getJSON("<?php echo site_url('Templates/References/getSales') ?>/" + $('#insertlocunit').val(), '', function (data) {              
      
  //    achtungHideLoader();

      $("#result_Sales tr").remove();

      
      $.each(data, function (i, o) {  

          $('<tr><td>'+o.sales_no+'</td><td>'+o.sales_date+'</td><td>'+o.note+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_sales_from_modal('+"'"+o.sales_no+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_Sales'));                    
      
      //    status = (o.status==null)?'-':o.status;               

      //    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_bed_from_modal_bed('+"'"+o.kode_ruangan+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_PO'));                    

          gt = o.charge_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
          $('#grand_total').val(gt);
        
      }); 

      showModal_por();  

  });       

}

function showModal_por()

{  

  $("#modalSales").modal();  

}

function select_sales_from_modal(sales_no){	

  $("#modalSales").modal('hide');

  $('#insertReferenceNo').val(sales_no);

  $('#insertReferenceNo').text(sales_no); 

  $.getJSON("<?php echo site_url('Templates/References/getSalesDetail') ?>/" + sales_no, '', function (data) {              
  
  //    achtungHideLoader();

      //$("#result_sales tr").remove();
      
      $.each(data, function (i, o) {  

          harga = o.harga_jual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 

          dsc = o.discount_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  

          total = o.total_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
   
          $('<tr><td width="15%""><input type="number" name="qty'+i+'" class="form-control" value="'+o.quantity+'" readonly="readonly"></td><td width="20%"><input name="itemID'+i+'" class="form-control" value="'+o.item_id+' : '+o.item_name+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%"><input type="text" name="satuan'+i+'" class="form-control" value="'+o.satuan+'" readonly="readonly"></td><td width="15%"><input type="text" name="harga'+i+'" class="form-control" value="'+harga+'" readonly="readonly"></td><td width="15%"><input type="number" name="dsc'+i+'" class="form-control" value="'+dsc+'" readonly="readonly"></td><td width="15%"><input type="text" id="total" name="total'+i+'" class="form-control" value="'+ total +'" readonly="readonly"></td><td align="center"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_sales'));                    
        
          
          $('#jmlcell').val(i);
          
      }); 

      $("#insert_table").show('fast'); 

      $("#show_grand_total").show('fast'); 

      $("#btn_insert").hide('fast'); 

      $("#res_head").show('fast'); 

  });  
   

}

$('#btn_search_data').click(function (e) {      

  e.preventDefault();      

  if( $("#form_cari_pasien").val() == "" ){

    return $("#from_tgl").focus();

  }else{

    achtungShowLoader();

    $.getJSON("<?php echo site_url('Templates/References/getSalesbyTgl') ?>/" + $('#from_tgl').val() +"/" +  $('#to_tgl').val(), '', function (data) {              
      
      achtungHideLoader();

      if( data.count == 0){

        alert('Data tidak ditemukan'); return $("#from_tgl").focus();

      }else{              

        $("#result_Sales tr").remove();

        $.each(data, function (i, o) {                  


            $('<tr><td>'+o.sales_no+'</td><td>'+o.sales_date+'</td><td>'+o.note+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_sales_from_modal('+"'"+o.sales_no+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_Sales'));                    

        }); 

        showModal();  

      }             

    });             
    
  }    

})


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
      console.log(val_item);
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

$('#btnReset').click(function (e) {      

  e.preventDefault();   

  $("#result_sales tr").remove();   

  $("#show_grand_total tr").remove();   

  $('#insertReferenceNo').val('');

  $('#insertReferenceNo').text('');  

  $("#insert_table").hide('fast');
 

})


if($("#jmlcell").val()==''){
  var i=0;
}else{
  var i = $("#jmlcell").val()
}
var sum = 0;

function addRecords() {
  var ok = true;
  
	if ($('#inputItem').val() == '' || $('#insertqty').val() == '' || $('#insertSatuan').val() == '' || $('#inserthrg').val() == ''){
		//document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		achtungCreate("*Please Fill All Required Field",false)
		return ok;
	} else if ($('#insertqty').val() == 0 || $('#inserthrg').val() == 0){
		achtungCreate("Quantity / Harga Tidak boleh 0",false)
		return ok;
	} else {

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
    }

	}

  $("#show_grand_total").show('fast');
  $("#grand_total").val(sumcur);

	$('#inputItem').val('');
  $('#insertqty').val(1);
  $('#inserthrg').val('');
  $('#insertdisc').val('');
  $('#insertdisc_amt').val('');
	$('#table_input').find('select').val('');

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
        <h1 style="margin-top:10px;margin-left:10px;">Sales Return</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
            <form class="form-horizontal" method="post" id="form_item_sales" action="sales/sales_return/process" enctype="multipart/form-data">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Sales Return No.</label>
                    <div class="col-md-4">
                      <input name='salesret_number' id='salesret_number' value='<?php echo isset($value)?$value->sales_no:$trans_no?>' placeholder="" class="form-control" type="text" readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">
                      <input name="tgl" id="tgl" value="<?php echo date("Y-m-d")?>" placeholder="" class="form-control" type="text" readonly >
                    </div>
                  </div>

                  <div class="form-group">
										<label class="control-label col-md-4">Unit</label>
                    <div class="col-md-4">
											<?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($_SESSION['loc'])?$_SESSION['loc']:'' , ($_SESSION['leveluser']!=1)?'readonly':'','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>
                    </div>	
									</div>

                  <div class="form-group" id="outstanding">
										<label class="control-label col-md-4">Sales No</label>
                    <div class="col-md-8">
											<div class="input-group" style="z-index:0 ">
												<input type="text" name="insertReferenceNo" id="insertReferenceNo" value="<?php echo isset($value)?$value->reference_no:''?>" class="form-control" readonly>

												<span class="input-group-btn" <?php echo ($flag!='create')?'style="display:none"':''?>>

													<button type="button" class="btn btn-primary btn-sm" onclick="outstanding()">

														<span class="ace-icon fa fa-bed icon-on-right bigger-110"></span>

														Outstanding

													</button>

													<button type="reset" id="btnReset" class="btn btn-sm btn-danger">
														<i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
														Reset
													</button>                        

												</span>
											</div>
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
              
              <br>

              <table width='100%' id='insert_table' style='<?php echo ($flag!='read')?'display:none;()':''?>'>
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
                    
                  </td>
                </tr>
                <tr style="<?php echo ($flag=='read')?'display:none':''?>">
                  <td colspan='7'  style="text-align:left">
                    <a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
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

                  <a onclick="getMenu('sales/sales_return')" href="#" class="btn btn-sm btn-success">
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

<!-- MODAL PO -->

<div id="modalSales" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

  <div class="modal-content">
  
    <div class="modal-header">

        <div class="table-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

        </button>

          <span style="color:white;text-align:left;"><h1>Purchase Order</h1></span>

        </div>

    </div>

    <div class="modal-body no-padding">

			<div class="form-group">
        <label class="control-label col-md-2">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group" id="datepicker">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
					</div>
					
					<div class="col-md-2">
          <a href="#" id="btn_search_data" class="btn btn-m btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
        </div>
      </div>

      <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">

        <thead>

          <tr>

            <th>Sales No</th>

            <th>Date</th>

            <th>Note</th>

            <th></th>

          </tr>

        </thead>

        <tbody id="result_Sales">


        </tbody>

      </table>

    </div>

    <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div>

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

