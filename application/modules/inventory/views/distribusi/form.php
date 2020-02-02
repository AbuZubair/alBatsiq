<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url().'assets/js/chosen/chosen.css'?>"></script>

<script src="<?php echo base_url().'assets/js/chosen/chosen.jquery.js'?>"></script>

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
          $('#page-area-content').load('inventory/distribusi?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
    

})
 
$('#form_item_tariff').ajaxForm({      

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
			$('#page-area-content').load('inventory/distribusi?_=' + (new Date()).getTime());


		}else{          

			$.achtung({message: jsonResponse.message, timeout:5});          

		}        

		//achtungHideLoader();        

	}      

});     

$('select[name="insertlocunit"]').change(function () {      

  $.getJSON("<?php echo site_url('templates/References/getLocUnit') ?>/" + $(this).val(), '', function (data) {              

    $('#insertTolocunit option').remove();        

    //console.log(data)        

    $('<option value="">-Pilih Satuan-</option>').appendTo($('#insertTolocunit'));                

    $.each(data, function (i, o) {                  
 
        
        $('<option value="' + o.loc_id + '">' + o.unit_name + '</option>').appendTo($('#insertTolocunit'));            

    });                

  }); 

  $("#to_loc_id").show('fast');

}); 

$('select[name="insertTolocunit"]').change(function () {      

  $("#table_input").show('fast');

}); 

var bal=0;

$('#inputItem').typeahead({
    minLength: 3,
    source: function (query, result) {
			//console.log(query)
        data = [];
        data[0] = query;
        data[1] = $('#insertlocunit').val();
        $.ajax({
            url: "templates/References/getItemByLoc",
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
      bal = parseInt(bal_.split(' ')[1]);
      console.log(val_item);
      console.log(bal);
      $('#insertItemID').val(val_item);

      if (val_item) { 
        $.getJSON("<?php echo site_url('templates/References/getDataItem') ?>/" + val_item, '', function (data) {              

          $('#insertSatuan option').remove();                

          $('<option value="">-Pilih Satuan-</option>').appendTo($('#insertSatuan'));                

          $.each(data, function (i, o) {                  

              $('<option value="' + o.satuan_beli + '" selected>' + o.satuan_beli + '</option>').appendTo($('#insertSatuan')); 
              $('<option value="' + o.satuan_jual + '">' + o.satuan_jual + '</option>').appendTo($('#insertSatuan'));                     

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

function addRecords() {
  var ok = true;
  console.log(bal);
	if ($('#inputItem').val() == '' || $('#insertqty').val() == '' || $('#insertSatuan').val() == '' || $('#inserthrg').val() == ''){
		//document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		achtungCreate("*Please Fill All Required Field",false)
		return;
	} else if ($('#insertqty').val() == 0 || $('#inserthrg').val() == 0){
		achtungCreate("Quantity / Harga Tidak boleh 0",false)
		return $("#insertqty").focus();
	} else if($('#insertqty').val() > bal){
    achtungCreate("Balance tidak cukup!!",false)
		return $("#insertqty").focus();
  } else {

		$("#record").find('input[id="InputItemID"]').each(function(){
				if($(this).val() == $('#inputItem').val()){
					achtungCreate("Item Sudah ada dalam list",false);
					ok = false;
					return ok;
				}
		});

  if(ok == true){
      $('<tr><td width="30%"><input name="itemID'+i+'" class="form-control" value="'+$('#inputItem').val()+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%""><input type="number" name="qty'+i+'" class="form-control" value="'+$('#insertqty').val()+'" readonly="readonly"></td><td width="20%"><input type="text" name="satuan'+i+'" class="form-control" value="'+$('#insertSatuan').val()+'" readonly="readonly"></td><td align="center" width="10%"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_dist'));                    

      $("#jmlcell").val(i);
      i++;
  }

	}

	$('#table_input').find('input').val('');
	$('#table_input').find('select').val('');

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
        <h1 style="margin-top:10px;margin-left:10px;">Distribusi</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
						<form class="form-horizontal" method="post" id="form_item_tariff" action="inventory/distribusi/process" enctype="multipart/form-data">
                <div class="col-lg-6"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Distribusi No.</label>
                    <div class="col-md-4">
											<input class="form-control" type="text" name='distnumber' id='distnumber' value='<?php echo isset($value)?$value->transaction_no:$trans_no?>' readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">
                      <input name="tgl" id="tgl" value="<?php echo ($flag!='create')?$value->transaction_date:date("Y-m-d")?>" placeholder="" class="form-control" type="text" readonly >
                    </div>
                  </div>

                  <div class="form-group">
										<label class="control-label col-md-4">From Unit</label>
                    <div class="col-md-4">
											<?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($value)?$value->from_loc_id:'' , '','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>
                    </div>	
									</div>

                  <div class="form-group" id="to_loc_id" style='<?php echo ($flag!='read')?'display:none;()':''?>'>
										<label class="control-label col-md-4">To Unit</label>
                    <div class="col-md-4">
                      <?php echo $this->master->get_change($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array()), isset($value)?$value->to_loc_id:'' , 'insertTolocunit', 'insertTolocunit', 'form-control', '', '') ?>
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


              <table id="table_input" width='100%' style='<?php echo ($flag!='read')?'display:none;()':''?>'>
                <tr>
                  <td style='color:white;background-color:#001f3f;text-align:left;' colspan='6'>Item</td>
                </tr>

                <tr>
                  <td width='30%'>Item ID*</td>
                  <td width='10%'>Quantity*</td>
                  <td width='20%'>Satuan*</td>
                  <td width='10%'></td>
                <tr>
                
                <tr>
                  <td>
                    <input id="inputItem" class="form-control" name="inputItem" type="text" placeholder="Masukan keyword minimal 3 karakter" autocomplete="off" value="" <?php echo ($flag=='read')?'readonly':''?>/>
                    <input type="hidden" name="insertItemID" value="" id="insertItemID"> 
                  </td>
                  <td>
                    <input name="insertqty" id="insertqty" value="" placeholder="0" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>> 
                  </td>
                  <td>     
                    <?php echo $this->master->get_change($params = array('table' => 'tmp_mst_satuan', 'id' => 'satuan_id', 'name' => 'satuan_name', 'where' => array()), '' , 'insertSatuan', 'insertSatuan', 'form-control', '', '',($flag=='read')?'readonly':'') ?>
                  </td>
                  <td>
                    <a class='button3' onclick="<?php echo ($flag!='read')?'addRecords()':''?>"><i class='fa fa-plus-circle'></i> Insert</a>
                  </td>
                </tr>
                <tr style="<?php echo ($flag=='read')?'display:none':''?>">
                  <td colspan='6'  style="text-align:left">
                    <a class='button3' onclick='removeRow()'><i class='fa fa-minus-circle'></i> Remove</a>
                  </td>
                </tr>
              </table>
							<div class="col-md-1" style="display:none">
								<input name="id" id="id" value="<?php echo isset($value)?$value->warehouse_transaction_id:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
							</div>

              <table width='100%' id='record'>
                <input type='hidden' name='jmlcell' id='jmlcell' value='<?php echo ($value_numrow!=0)?$value_numrow:''?>'>
                
								<tbody id="result_dist">
									<?php if(isset($value_detail)){$i=0; foreach($value_detail as $key_row=>$rows_m) :?>
										<tr>
											<td width="30%"><input name="itemID<?php echo $i?>" class="form-control" value="<?php echo ''.$rows_m->item_id.' : '.$rows_m->item_name.'' ?>"  id="InputItemID" size="30" readonly="readonly"></td>
											<td width="10%"><input type="number" name="qty<?php echo $i?>" class="form-control" value="<?php echo $rows_m->quantity ?>" readonly="readonly"></td>
											<td width="20%"><input type="text" name="satuan<?php echo $i?>" class="form-control" value="<?php echo $rows_m->satuan ?>" readonly="readonly"></td>
											<!-- <td width="10%"><input type="number" name="knv<?php echo $i?>" class="form-control" value="<?php echo $rows_m->konversi ?>" readonly="readonly"></td> -->
											<td width="10%"><input type="checkbox" name="chk<?php echo $i?>" id="chk" value="Y" readonly="readonly"></td>
										</tr>
									<?php $i++; endforeach; }?>
								</tbody>
              </table>

              

                <div class="form-actions" style="text-align:center;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('inventory/distribusi')" href="#" class="btn btn-sm btn-success">
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

