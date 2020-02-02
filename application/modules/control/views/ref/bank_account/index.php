<div class = 'title-container'>
    <div class = "txttitle" >
        <h1 style="margin-top:10px;margin-left:10px;"><?php if($level==2){ echo $title; } else { echo $parent; ?> <span style="font-size:20px;"> >> </span><span style="font-size:25px;"><?php echo $title; ?></span> <?php } ?></h1>
    </div>
</div>

<div>    

    <div class="clearfix" style="margin-bottom:10px;text-align:left;">
      <?php echo $this->authuser->show_button('control/ref/bank_account','C','',1)?>
      <?php echo $this->authuser->show_button('control/ref/bank_account','D','',5)?>
      <div class="pull-right tableTools-container"></div>
    </div>

        <table id="dynamic-table" base-url="control/ref/bank_account" class="display table table-striped table-bordered table-hover" width='100%' >
            <thead>
                <tr>
                    <th width='5%'></th>
                    <th width='25%'> Bank </th>
                    <th width='25%'> Bank Number  </th>
                    <th width='15%'> Status </th>
                    <th width='30%'>  </th>
                </tr>
            </thead> 
            <tbody>
                
            </tbody>
        </table>
</div>
     
<script src="<?php echo base_url().'assets/js/custom/datatable.js'?>"></script>

 	