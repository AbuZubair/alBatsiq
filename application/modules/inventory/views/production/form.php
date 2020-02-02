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

counterfile = <?php $j=1;echo $j.";";?>
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
          $('#page-area-content').load('inventory/production?_=' + (new Date()).getTime());
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
			$('#page-area-content').load('inventory/production?_=' + (new Date()).getTime());


		}else{          

			$.achtung({message: jsonResponse.message, timeout:5});          

		}        

		//achtungHideLoader();        

	}      

});     

$('select[name="insertlocunit"]').change(function () {      

  getoptionbahan($(this).val(),0);
  $("#input_file1").html('');
  $("#bahan_dasar").show('fast');

  counterfile = <?php $j=1;echo $j.";";?>

}); 

/*
$('select[class="insertBahan"]').change(function () {      

  $.getJSON("<?php //echo site_url('templates/References/getItemProductionDetail') ?>/" + $(this).val() + "/" + $("#insertlocunit").val(), '', function (data) {              
            
    $.each(data, function (i, o) {                  
   
        bal = o.konversi*o.balance;
        
        $("#qty_bahan").val(bal);

        $("#satuan_bahan").val(o.satuan_name);

        $("#satuan_bahan_dipakai").val(o.satuan_name);

    });                

  }); 
  
  $("#table_input").show('fast');

});
*/

var bal=0;

$('#inputItem').typeahead({
    source: function (query, result) {
        //console.log($('select[name^="insertBahan"]').val());return false;
        var bhn = [];
		$('select[name^="insertBahan"]').each(function() {
            bhn.push($(this).val())
        });

        data = [];
        data[0] = query;
        data[1] = JSON.stringify( bhn );
        $.ajax({
            url: "templates/References/getItemSales",
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

function insert_bahan(val,idx){
    console.log(val)
    
    $.getJSON("<?php echo site_url('templates/References/getItemProductionDetail') ?>/" + val.value + "/" + $("#insertlocunit").val(), '', function (data) {              
            
        $.each(data, function (i, o) {                  
       
            bal = o.konversi*o.balance;
            
            $("#qty_bahan_"+idx+"").val(bal);
    
            $("#satuan_bahan_"+idx+"").val(o.satuan_name);
    
            $("#satuan_bahan_dipakai_"+idx+"").val(o.satuan_name);
    
        });      
        
    }); 
     $("#table_input").show('fast');
}

function getoptionbahan(val,idx){
    $.getJSON("<?php echo site_url('templates/References/getItemProduction') ?>/" + val, '', function (data) {              

        $('#insertBahan_'+idx+' option').remove();        
    
        console.log(data)        
    
        $('<option value="">-Pilih Item-</option>').appendTo($('#insertBahan_'+idx+''));                
    
        $.each(data, function (i, o) {                  
     
            
            $('<option value="' + o.item_id + '">' + o.item_name + '</option>').appendTo($('#insertBahan_'+idx+''));            
    
        });                

    }); 
}

if($("#jmlcell").val()==''){
  var i=0;
}else{
  var i = $("#jmlcell").val()
}

function addRecords() {
  var ok = true;
  qty_bahan =  parseInt($('#qty_bahan').val());
  bhn_dipakai = parseInt($('#insertbahan_dipakai').val());
	if ($('#inputItem').val() == '' || $('#insertqty').val() == '' || $('#insertSatuan').val() == '' || $('#inserthrg').val() == ''){
		//document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		achtungCreate("*Please Fill All Required Field",false)
		return false;
	} else if ($('#insertqty').val() == 0){
		achtungCreate("Quantity Tidak boleh 0",false)
		return $("#insertqty").focus();
    }
    
    $("#record").find('input[class="InputItemID"]').each(function(){
			if($(this).val() == $('#inputItem').val()){
				achtungCreate("Item Sudah ada dalam list",false);
				ok = false;
				return ok;
			}
	});
    
    $('input[name^="insertbahan_dipakai"]').each(function() {
        var id = $(this).attr('id').substr($(this).attr('id').length - 1)
        bhn_dipakai = parseInt($(this).val());
        qty_bahan = parseInt($('#qty_bahan_'+id+'').val());

        if( qty_bahan < bhn_dipakai ){
            achtungCreate("Quantity "+$("#insertBahan_"+id+" option:selected").text()+" Tidak Cukup",false);
            ok = false;
		    return false;
        }
    });


    if(ok == true){
     
        
        
        html_res = '';
        
        html_res += '<tr>';
        html_res += '<td width="30%">';
        html_res += '    <input name="itemID'+i+'" class="form-control InputItemID" value="'+$('#inputItem').val()+'" size="30" readonly="readonly">';
        html_res += '</td>';
        html_res += '<td width="10%"">';
        html_res += '    <input type="number" name="qty'+i+'" class="form-control" value="'+$('#insertqty').val()+'" readonly="readonly">';
        html_res += '</td>';
        html_res += '<td width="10%">';
        html_res += '    <input type="text" name="satuan'+i+'" class="form-control" value="'+$('#insertSatuan').val()+'" readonly="readonly">';
        html_res += '</td>';
        html_res += '<td width="20%">';
            $('input[name^="insertbahan_dipakai"]').each(function() {
                var id = $(this).attr('id').substr($(this).attr('id').length - 1)
                bhn_dipakai = parseInt($(this).val());
                qty_bahan = parseInt($('#qty_bahan_'+id+'').val());
        
              
                bal_now=qty_bahan-bhn_dipakai;
                $('#qty_bahan_'+id+'').val(bal_now);
                
                html_res += '<input type="text" name="bahan_dipakai'+i+'[]" class="form-control InputBahan_dipakai" value="'+$(this).val()+'" readonly="readonly">';
                $(this).val('');
                
            });
            
        html_res += '</td>'
        html_res += '<td width="20%">'
        html_res += '    <input type="number" name="harga'+i+'" class="form-control" value="'+ $('#inserthrg').val()+'" readonly="readonly">';
        html_res += '</td>';
        html_res += '<td align="center" width="10%">';
        html_res += '    <input type="checkbox" name="chk'+i+'" class="chk" value="Y">'
        html_res += '</td>';
        html_res += '</tr>';
        
        //$('<tr><td width="30%"><input name="itemID'+i+'" class="form-control" value="'+$('#inputItem').val()+'" id="InputItemID" size="30" readonly="readonly"></td><td width="10%""><input type="number" name="qty'+i+'" class="form-control" value="'+$('#insertqty').val()+'" readonly="readonly"></td><td width="10%"><input type="text" name="satuan'+i+'" class="form-control" value="'+$('#insertSatuan').val()+'" readonly="readonly"></td><td width="20%"><input type="text" name="bahan_dipakai'+i+'" id="InputBahan_dipakai" class="form-control" value="'+$('#insertbahan_dipakai').val()+'" readonly="readonly"></td><td width="20%"><input type="number" name="harga'+i+'" class="form-control" value="'+ $('#inserthrg').val()+'" readonly="readonly"></td><td align="center" width="10%"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result_dist'));                    
        $(html_res).appendTo($('#result_dist'))
    
        $("#jmlcell").val(i);
        i++;
      
       $('#inputItem').val('');
        $('#insertqty').val('');
        $('#inserthrg').val('');
    	$('#table_input').find('select').val('');
    }

    //bal_now=qty_bahan-bhn_dipakai;
    //$('#qty_bahan').val(bal_now);

}

function removeRow(){
	$("#record").find('input[class="chk"]').each(function(){
      if($(this).is(":checked")){
          $(this).parents("tr").each(function(){
            //console.log($(this).find('input[id="InputBahan_dipakai"]').val())
            //console.log($('#qty_bahan').val())
            bhn_dipakai = parseInt($(this).find('input[class="InputBahan_dipakai"]').val());
            qty_bahan =  parseInt($('#qty_bahan').val());
            bal_now=qty_bahan+bhn_dipakai;
            $('#qty_bahan').val(bal_now);
          });

          $(this).parents("tr").remove();
      }
  });
}

function hapus_file(a, b)

{

  if(b != 0){
    /*$.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });*/
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
    document.getElementById("file_pakai"+a).innerHTML = "";
  }

}

function tambah_file()

{

  counternextfile = counterfile + 1;

  counterIdfile = counterfile + 1;
  
  html = '';
  
  html += "<div id=\"file"+counternextfile+"\" class='clonning_form'><div class='form-group'>";
  html += '<label class="control-label col-md-4">'+counternextfile+'</label><div class="col-md-4">';
  html += '<select class="form-control insertBahan" name="insertBahan[]" id="insertBahan_'+counterfile+'"  onchange="insert_bahan(this,'+ counterfile +')" >';
 html += '<option value="0" selected> - Silahkan pilih - </option>';
 html += '</select>'
 html += '</div>';            
 
 html += '<label class="control-label col-md-1">Tersedia</label>';
 html += '<div class="col-md-3">';
 html += '<input style="width:30%;display:inline;" type="number" name="qty_bahan[]" id="qty_bahan_'+counterfile+'" value="" class="form-control">';
 html += '<input style="width:40%;display:inline;" type="text" name="satuan_bahan[]" id="satuan_bahan_'+counterfile+'" value="" class="form-control"><input type="button" onclick="hapus_file('+counternextfile+',0)" value=" x " class="btn btn-xs btn-danger"/>';
 html += '</div>';
 html += '</div>';
 html += "</div><div id=\"input_file"+counternextfile+"\"></div>";

  document.getElementById("input_file"+counterfile).innerHTML = html;
  
  getoptionbahan($('select[name="insertlocunit"]').val(),counterfile);
  
  
  html_='';
  
  html_ += "<div id=\"file_pakai"+counternextfile+"\" class='clonning_form_pakai'>";
  html_ +=''+counternextfile+' :<input style="width:50%;display:inline;" name="insertbahan_dipakai[]" id="insertbahan_dipakai_'+counterfile+'" value="" placeholder="0" class="form-control" type="number" step="0.01" <?php echo ($flag=="read")?"readonly":""?>> ';
  html_ +='<input style="width:30%;display:inline;" name="satuan_bahan_dipakai[]" id="satuan_bahan_dipakai_'+counterfile+'" value="" class="form-control" type="text" readonly>';
  html_ += "</div><div id=\"input_file_pakai"+counternextfile+"\"></div>";
  
  document.getElementById("input_file_pakai"+counterfile).innerHTML = html_;

  counterfile++;
  

}


</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;">Production</h1>
    </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
            <div class="row">
						<form class="form-horizontal" method="post" id="form_item_tariff" action="inventory/production/process" enctype="multipart/form-data">
                <div class="col-lg-7"> 

                  <div class="form-group">
                    <label class="control-label col-md-4">Production No.</label>
                    <div class="col-md-4">
											<input class="form-control" type="text" name='prodnumber' id='prodnumber' value='<?php echo isset($value)?$value->production_no:$trans_no?>' readonly>
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-4">Date</label>
                    <div class="col-md-4">   
                      <input class="form-control date-picker" name="prodtgl" id="prodtgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo ($flag!='create')?$value->production_date:date('Y-m-d')?>"/>
                    </div>
                  </div>

                  <div class="form-group">
										<label class="control-label col-md-4">From Unit</label>
                    <div class="col-md-4">
											<?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($value)?$value->loc_id:'' , '','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>
                    </div>	
									</div>

                  <div class="form-group" id="bahan_dasar" style='display:none'>
										<label class="control-label col-md-4">Bahan Dasar</label>
                    <div class="col-md-4">            

                      <select class="form-control insertBahan" name="insertBahan[]" id="insertBahan_0" onchange="insert_bahan(this,0)" >
                        <option value="0" selected> - Silahkan pilih - </option>
                        </select>
                        
                    </div>
    
                      <label class="control-label col-md-1">Tersedia</label>

                      <div class="col-md-3">  

                        <input style="width:30%;display:inline;" type="number" name="qty_bahan[]" id="qty_bahan_0" value="<?php echo  isset($value)?$balance_bahan:'' ?>" class="form-control">
                        <input style="width:40%;display:inline;" type="text" name="satuan_bahan[]" id="satuan_bahan_0" value="<?php echo  isset($value)?$value_detail[0]->satuan_bahan:'' ?>" class="form-control">
                        <input onClick="tambah_file()" value="+" type="button" class="btn btn-xs btn-info" />
                      </div>
                      
                       <div id="clone_form">
                  <div id="input_file<?php echo $j;?>"></div>
                </div>
                    

									</div>

                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="control-label col-md-4">Note</label>
                    <div class="col-md-4">
                      <textarea rows='2' cols='50' name='insertNote' id='insertNote' placeholder='Enter note here..'><?php echo isset($value)?$value->note:''?></textarea>
                    </div>
                    
                  </div>
                </div>
              
              <br>


              <table id="table_input" width='100%' style='<?php echo ($flag=='create')?'display:none;()':''?>'>
                <tr>
                  <td style='color:white;background-color:#001f3f;text-align:left;' colspan='6'>Item</td>
                </tr>

                <tr>
                  <td width='30%'>Item ID*</td>
                  <td width='10%'>Quantity*</td>
                  <td width='10%'>Satuan</td>
                  <td width='20%'>Bahan yang dipakai*</td>
                  <td width='20%'>Harga*</td>
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
                    <input style="width:50%;display:inline;" name="insertbahan_dipakai[]" id="insertbahan_dipakai_0" value="" placeholder="0" class="form-control" type="number" step='0.01' <?php echo ($flag=='read')?'readonly':''?>> 
                    <input style="width:30%;display:inline;" name="satuan_bahan_dipakai[]" id="satuan_bahan_dipakai_0" value="" class="form-control" type="text" readonly>
                    
                    <div id="clone_form_pakai">
                        <div id="input_file_pakai<?php echo $j;?>"></div>
                    </div>
                  </td>
                  <td>      
                    <input class="form-control" type='number' step='0.01' placeholder='0.00' name='inserthrg' id='inserthrg' <?php echo ($flag=='read')?'readonly':''?>>  
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
								<input name="id" id="id" value="<?php echo isset($value)?$value->production_id:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
							</div>

              <table width='100%' id='record'>
                <input type='hidden' name='jmlcell' id='jmlcell' value='<?php echo ($value_numrow!=0)?$value_numrow:''?>'>
                
								<tbody id="result_dist">
									<?php if(isset($value_detail)){$i=0;$result_id=''; foreach($value_detail as $key_row=>$rows_m) : if($rows_m->item_id_result!=$result_id){?>
										<tr>
											<td width="30%"><input name="itemID<?php echo $i?>" class="form-control InputItemID" value="<?php echo ''.$rows_m->item_id_result.' : '.$rows_m->item_name.'' ?>"  size="30" readonly="readonly"></td>
											<td width="10%"><input type="number" name="qty<?php echo $i?>" class="form-control" value="<?php echo $rows_m->result_quantity ?>" readonly="readonly"></td>
											<td width="10%"><input type="text" name="satuan<?php echo $i?>" class="form-control" value="<?php echo $rows_m->satuan_result ?>" readonly="readonly"></td>
                      <td width="20%">
                        <?php foreach($value_detail as $k=>$v) : if($rows_m->item_id_result==$v->item_id_result){?>
                          <div><span><?php echo $v->bahan ?>&nbsp;&nbsp; :</span><span><input type="text" name="bahan_dipakai<?php echo $i?>" class="form-control InputBahan_dipakai" value="<?php echo $v->bahan_quantity ?>" readonly="readonly" style="width: 30%;display: inline;"></span><span><?php echo $v->satuan_bahan ?></span></div>
                        <?php } endforeach; ?>
                      </td>
                      <td width="20%"><input type="number" name="harga<?php echo $i?>" class="form-control" value="<?php echo $rows_m->harga ?>" readonly="readonly"></td>
											<td width="10%"><input type="checkbox" name="chk<?php echo $i?>" class="chk" value="Y" readonly="readonly"></td>
										</tr>
									<?php $i++; $result_id=$rows_m->item_id_result;} endforeach; }?>
								</tbody>
              </table>

              

                <div class="form-actions" style="text-align:center;">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->role_id:0?>"> -->

                  <a onclick="getMenu('inventory/production')" href="#" class="btn btn-sm btn-success">
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

