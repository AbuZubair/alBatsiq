<script>

    $(document).ready(function(){
  
        $('#form_so').ajaxForm({
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
                $('#page-area-content').load('inventory/stock/stock_opname?_=' + (new Date()).getTime());
            }else{
                $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
            }
        }); 

    })

    function stock_opname() {

        $("#modalSO").modal();  

    }

    function showModal_so()

    {  

        $("#modalSO").modal();  

    }
</script>


<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;"><?php if($level==2){ echo $title; } else { echo $parent; ?> <span style="font-size:20px;"> >> </span><span style="font-size:25px;"><?php echo $title; ?></span> <?php } ?></h1>
    </div>
</div>

<div>    

    <div class="clearfix" style="margin-bottom:10px;text-align:left;">
        <a href="#" class="btn btn-m btn-primary" onclick="stock_opname()">
        <i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>
            Add
        </a>
    </div>

        <table id="dynamic-table" base-url="inventory/stock/stock_opname" class="display table table-striped table-bordered table-hover" width='100%' >
            <thead>
                <tr>
                    <th width='5%'></th>
                    <th width='15%'> Stock Opname No. </th>
                    <th width='15%'> Unit </th>
                    <th width='10%'> Date  </th>
                    <th width='10%'> Note </th>
                    <th width='10%'> Created Date </th>
                    <th width='15%'> Created By </th>
                    <th width='15%'>  </th>
                </tr>
            </thead> 
            <tbody>
                
            </tbody>
        </table>
</div>

<!-- MODAL SO -->

<div id="modalSO" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

  <div class="modal-content">
  
    <div class="modal-header">

        <div class="table-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

            </button>

            <span style="color:white;text-align:left;"><h1>Stock Opname</h1></span>

        </div>

    </div>
    <form class="form-horizontal" method="post" id="form_so" action="inventory/stock/stock_opname/process" enctype="multipart/form-data">
        <div class="modal-body no-padding">
        
            <div class="col-lg-5">
                
                <label class="control-label col-md-4">Unit</label>
                <div class="col-md-6">
                    <?php echo $this->master->custom_selection($params = array('table' => 'unit', 'id' => 'loc_id', 'name' => 'unit_name', 'where' => array('is_active' => 'Y')), isset($value)?$value->to_loc_id:'' , '','insertlocunit', 'insertlocunit', 'form-control', '', '') ?>
                </div>
                
            </div>

            <div class="col-lg-6">
                
                <label class="control-label col-md-4">Note</label>
                <div class="col-md-4">
                    <textarea rows='2' cols='50' name='insertNote' id='insertNote' placeholder='Enter note here..'><?php echo isset($value)?$value->note:''?></textarea>
                </div>              
            
            </div>			  
        </div>

        <div class="modal-footer">

            <div class="col-md-4">
                <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>

                    Close
                </button>

                <button style="float:left" type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                </button>
            
            </div>

        </div>
    </form>

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->
     
<script src="<?php echo base_url().'assets/js/custom/datatable.js'?>"></script>

 	