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
  
    $('#form_item_trans').ajaxForm({
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
 
$('#form_item_trans').ajaxForm({      

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
			$('#page-area-content').load('inventory/transaksi_item/purchase_order_receive?_=' + (new Date()).getTime());


		}else{          

			$.achtung({message: jsonResponse.message, timeout:5});          

		}        

		//achtungHideLoader();        

	}      

});     

$('#inputItem').typeahead({
    source: function (query, result) {
			console.log(query)
        $.ajax({
            url: "templates/References/getItem",
            data: 'keyword=' + query,            
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
              $('#insertknv').val(o.konversi)  

          });                

        }); 
      }else{
        $('#insertSatuan option').remove()  
      }
    }
});


$('#btn_search_data').click(function (e) {      

	e.preventDefault();      

	if( $("#form_cari_pasien").val() == "" ){

		return $("#from_tgl").focus();

	}else{

		achtungShowLoader();

		$.getJSON("<?php echo site_url('Templates/References/getPObyTgl') ?>/" + $('#from_tgl').val() +"/" +  $('#to_tgl').val(), '', function (data) {              
			
			achtungHideLoader();

			if( data.count == 0){

				alert('Data tidak ditemukan'); return $("#from_tgl").focus();

			}else{              

				$("#result_PO tr").remove();

				$.each(data, function (i, o) {                  


						$('<tr><td>'+o.transaction_no+'</td><td>'+o.transaction_date+'</td><td>'+o.note+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_po_from_modal('+"'"+o.transaction_no+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_PO'));                    

				}); 

				showModal();  

			}             

		});             
		
	}    

})

$('#btnDirect').click(function (e) {      

  e.preventDefault();      

  $("#insert_table").show('fast');      
     

})

$('#btnReset').click(function (e) {      

  e.preventDefault();      

  $('#insertReferenceNo').val('');

  $('#insertReferenceNo').text('');  

  $("#result_PO_detail tr").remove();  

  $("#insert_table").hide('fast');
   

})

function outstanding() {

	$.getJSON("<?php echo site_url('Templates/References/getPO') ?>", '', function (data) {              
			
	//    achtungHideLoader();

			$("#result_PO tr").remove();

			
			$.each(data, function (i, o) {  

					$('<tr><td>'+o.transaction_no+'</td><td>'+o.transaction_date+'</td><td>'+o.note+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_po_from_modal('+"'"+o.transaction_no+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_PO'));                    
			
			//    status = (o.status==null)?'-':o.status;               

			//    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_bed_from_modal_bed('+"'"+o.kode_ruangan+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_PO'));                    
				
			}); 

			showModal_po();  

	});       

}

function showModal_po()

{  

		$("#modalPO").modal();  

}

function select_po_from_modal(po_no){	

		$("#modalPO").modal('hide');


		$('#insertReferenceNo').val(po_no);

		$('#insertReferenceNo').text(po_no);  


		$.getJSON("<?php echo site_url('Templates/References/getPODetail') ?>/" + po_no, '', function (data) {              
		
		//    achtungHideLoader();
	
				$("#result_PO_detail tr").remove();
	
				
				$.each(data, function (i, o) {  
	
						$('<tr><td width="30%"><input name="itemID'+i+'" class="form-control" value="'+o.item_id+' : '+o.item_name+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%""><input type="number" name="qty'+i+'" class="form-control" value="'+o.quantity+'" readonly="readonly"></td><td width="20%"><input type="text" name="satuan'+i+'" class="form-control" value="'+o.satuan+'" readonly="readonly"></td><td width="10%"><input type="number" name="knv'+i+'" class="form-control" value="'+o.konversi+'" readonly="readonly"></td><td width="20%"><input type="number" name="harga'+i+'" class="form-control" value="'+o.harga+'" readonly="readonly"></td><td align="center"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_PO_detail'));                    
				
				//    status = (o.status==null)?'-':o.status;               
	
				//    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_bed_from_modal_bed('+"'"+o.kode_ruangan+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_PO_detail'));                    
					
          $('#jmlcell').val(i);
				}); 
	
				$("#insert_table").show('fast'); 

				$("#btn_insert").hide('fast'); 
	
		});  
     

}

if($("#jmlcell").val()==''){
  var i=0;
}else{
  var i = $("#jmlcell").val()
}

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
      $('<tr><td width="30%"><input name="itemID'+i+'" class="form-control" value="'+$('#inputItem').val()+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%""><input type="number" name="qty'+i+'" class="form-control" value="'+$('#insertqty').val()+'" readonly="readonly"></td><td width="20%"><input type="text" name="satuan'+i+'" class="form-control" value="'+$('#insertSatuan').val()+'" readonly="readonly"></td><td width="10%"><input type="number" name="knv'+i+'" class="form-control" value="'+ $('#insertknv').val()+'" readonly="readonly"></td><td width="20%"><input type="number" name="harga'+i+'" class="form-control" value="'+ $('#inserthrg').val()+'" readonly="readonly"></td><td align="center"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_PO_detail'));                    

      $("#jmlcell").val(i);
      i++;
  }

	}

	$('#insert_table').find('input').val('');
	$('#insert_table').find('select').val('');

}


function removeRow(){
	$("#record").find('input[id="chk"]').each(function(){
					if($(this).is(":checked")){
							$(this).parents("tr").remove();
					}
			});
}
				

</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;">Purchase Order Receive</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
            <form class="form-horizontal" method="post" id="form_item_trans" action="inventory/transaksi_item/purchase_order_receive/process" enctype="multipart/form-data">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Purchase Order Receive No.</label>
                    <div class="col-md-4">
                      <input name='pornumber' id='pornumber' value='<?php echo isset($value)?$value->transaction_no:$trans_no?>' placeholder="" class="form-control" type="text" readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">
                      <input name="tgl" id="tgl" value="<?php echo ($flag!='create')?$value->transaction_date:date("Y-m-d")?>" placeholder="" class="form-control" type="text" readonly >
                    </div>
                  </div>

									<div class="form-group">
										<label class="control-label col-md-4">To Unit</label>
                    <div class="col-md-4">
											<?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($value)?$value->to_loc_id:'' , '','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>
                    </div>	
									</div>

									<div class="form-group">
										<label class="control-label col-md-4">Purchase Order No</label>
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

                          <button type="button" id="btnDirect" class="btn btn-sm btn-secondary">
														Direct POR
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
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
                  <td style='color:white;background-color:#001f3f;text-align:left;' colspan='6'>Item</td>
                </tr>

                <tr>
                  <td width='30%'>Item ID*</td>
                  <td width='10%'>Quantity*</td>
                  <td width='20%'>Satuan*</td>
                  <td width='10%'>Konversi</td>
                  <td width='20%'>Harga*</td>
                  <td width='10%'></td>
                <tr>
                
                <tr>
                  <td>
                    <input id="inputItem" class="form-control" name="inputItem" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" <?php echo ($flag=='read')?'readonly':''?>/>
                    <input type="hidden" name="insertItemID" value="" id="insertItemID">
                  </td>
                  <td>
                    <input name="insertqty" id="insertqty" value="" placeholder="0" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>> 
                  </td>
                  <td>     
                    <?php echo $this->master->get_change($params = array('table' => 'tmp_mst_satuan', 'id' => 'satuan_id', 'name' => 'satuan_name', 'where' => array()), '' , 'insertSatuan', 'insertSatuan', 'form-control', '', '',($flag=='read')?'readonly':'') ?>
                  </td>
                  <td>   
                    <input class="form-control" type='number' size='5' value='1' name='insertknv' id='insertknv' <?php echo ($flag=='read')?'readonly':''?>>
                  </td>
                  <td>      
                    <input class="form-control" type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg' <?php echo ($flag=='read')?'readonly':''?>>  
                  </td>
                  <td>
                    <a id='btn_insert' class='button3' onclick="<?php echo ($flag!='read')?'addRecords()':''?>"><i class='fa fa-plus-circle'></i> Insert</a>
                  </td>
                </tr>
                <tr style="<?php echo ($flag=='read')?'display:none':''?>">
                  <td colspan='6'  style="text-align:left">
                    <a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
                  </td>
                </tr>
              </table>
							<div class="col-md-1" style="display:none">
								<input name="id" id="id" value="<?php echo isset($value)?$value->item_transaction_id:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
							</div>

              <table width='100%' id='record'>
                
                <input type='hidden' name='jmlcell' id='jmlcell' value='<?php echo ($value_numrow!=0)?$value_numrow:''?>'>
                                
								<tbody id="result_PO_detail">

										<?php if(isset($value_detail)){$i=0; foreach($value_detail as $key_row=>$rows_m) :?>
											<tr>
												<td width="30%"><input name="itemID<?php echo $i?>" class="form-control" value="<?php echo ''.$rows_m->item_id.' : '.$rows_m->item_name.'' ?>"  id="InputItemID" size="30" readonly="readonly"></td>
												<td width="10%"><input type="number" name="qty<?php echo $i?>" class="form-control" value="<?php echo $rows_m->quantity ?>" readonly="readonly"></td>
												<td width="20%"><input type="text" name="satuan<?php echo $i?>" class="form-control" value="<?php echo $rows_m->satuan ?>" readonly="readonly"></td>
												<td width="10%"><input type="number" name="knv<?php echo $i?>" class="form-control" value="<?php echo $rows_m->konversi ?>" readonly="readonly"></td>
												<td width="20%"><input type="number" name="harga<?php echo $i?>" class="form-control" value="<?php echo $rows_m->harga ?>" readonly="readonly"></td>
												<td width="10%"><input type="checkbox" name="chk<?php echo $i?>" id="chk" value="Y" readonly="readonly"></td>
											</tr>
										<?php $i++; endforeach; }?>
								</tbody>
							
              </table>

              

                <div class="form-actions" style="text-align:center;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('inventory/transaksi_item/purchase_order_receive')" href="#" class="btn btn-sm btn-success">
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

<div id="modalPO" class="modal fade" tabindex="-1">

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

            <th>Purchase Order No</th>

            <th>Date</th>

            <th>Note</th>

            <th></th>

          </tr>

        </thead>

        <tbody id="result_PO">


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

