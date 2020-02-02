<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script type="text/javascript">

function rollback(id) {
    $.confirm({
        title: 'Rollback',
        content: 'Rollback stock??',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: "inventory/production/rollback",
                    data: {id:id,type:1},             
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    }
                });
            },
            cancel: function () {
                $.alert('Canceled!');
            },
        }
    });
}

</script>

<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;"><?php if($level==2){ echo $title; } else { echo $parent; ?> <span style="font-size:20px;"> >> </span><span style="font-size:25px;"><?php echo $title; ?></span> <?php } ?></h1>
    </div>
</div>

<div>    

    <div class="clearfix" style="margin-bottom:10px;text-align:left;">
      <?php echo $this->authuser->show_button('inventory/production','C','',1)?>
      <div class="pull-right tableTools-container"></div>
    </div>

        <table id="dynamic-table" base-url="inventory/production" class="display table table-striped table-bordered table-hover" width='100%' >
            <thead>
                <tr>
                    <th width='5%'></th>
                    <th width='10%'> Production No. </th>
                    <th width='10%'> Date  </th>
                    <th width='10%'> Note </th>
                    <th width='10%'> Created Date </th>
                    <th width='15%'> Created By </th>
                    <th width='20%'>  </th>
                </tr>
            </thead> 
            <tbody>
                
            </tbody>
        </table>
</div>
     
<script src="<?php echo base_url().'assets/js/custom/datatable.js'?>"></script>

 	