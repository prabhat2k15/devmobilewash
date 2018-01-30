<?php
include('header.php');
    if(!empty($_POST['getphone'])){
        $phone = $_POST['getphone'];
        $url = ROOT_URL.'/api/index.php?r=twilio/getreplysms&number='.$phone;    
        $handle = curl_init($url);
        //$data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
         curl_close($handle);
        $jsondata = json_decode($result);
        $result_code = $jsondata->status;
        $message = $jsondata->messages;
    }
?>
<?php
    if($company_module_permission == 'no' || $checked_messages == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#getphone').on('change', function(event) {
  
  if(this.value == 'null'){
      
      event.preventDefault();
  }else {
      
      return true;
  }
});
        });
        $(document).ready(function(){
            $('#example1').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
} );
            
        });
        </script>
<?php include('right-sidebar.php') ?>
<?php
$url = ROOT_URL.'/api/index.php?r=twilio/getreplynumber';
$handle = curl_init($url);
$data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
$number = $jsondata->phone;
/*echo "<pre>";
print_r($number);
echo "<pre>";
exit;*/
?>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    
                    
                    
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
					<?php if(!empty($_GET['dell'])){ ?>
					<p style="text-align: center; color: green;">Successfully Deleted</p>
					<?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Manage Reply Messages</span>
                                    </div>
									
									<div class="caption font-dark" style="font-size: 13px ! important;">
                                        <form method="post" action="">
                                        <span class="caption-subject uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phone Number</span>
                                        <select name="getphone" id="getphone" onchange="this.form.submit()">
                                        <option value="null">Select Number</option>
                                        <?php foreach($number as $phone){ ?>
                                        <option value="<?php echo $phone ?>" <?php if($phone == $_POST['getphone']){ echo 'selected'; } ?>><?php echo $phone; ?></option>
                                        <?php } ?>
                                        </select>
                                        </form>
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <?php if(!empty($_POST)){ ?>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                
                                                <th>&nbsp;</th>
                                                <th> From </th>
                                                <th> TO </th>
                                                <th> Message </th>
                                                <th> Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                  $i = 0;
    foreach($message as $response)
{ $i++;
    $date = explode(' +0000',$response->date);
      
          ?>
                <tr class="odd gradeX">
                    
                    <td><?php echo $i; ?></td>
                    <td><?php echo $response->from; ?></td>
                    <td><?php echo $response->to; ?></td>
                    <td><?php echo $response->message; ?></td>
                    <td><?php echo $date[0]; ?></td>
                    
                </tr>
           <?php
      
}
?>    
                                        </tbody>
                                    </table>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="portlet-body">
                                    <div id="example1_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="example1_length"><label> <select name="example1_length" aria-controls="example1" class="form-control input-sm input-xsmall input-inline"><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="-1">All</option></select> records </label></div></div><div class="col-md-6 col-sm-6"><div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm input-small input-inline" placeholder="" aria-controls="example1"></label></div></div></div><div class="table-scrollable"><table id="example1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 159px;" aria-sort="ascending" aria-label="&amp;nbsp;: activate to sort column descending">&nbsp;</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 195px;" aria-label=" From : activate to sort column ascending"> From </th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 127px;" aria-label=" TO : activate to sort column ascending"> TO </th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 306px;" aria-label=" Message : activate to sort column ascending"> Message </th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 176px;" aria-label=" Date : activate to sort column ascending"> Date </th></tr>
                                        </thead>
                                        <tbody>
                                            
                                        <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">Please select phone number to see the data</td></tr></tbody>
                                    </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_bootstrap_number" id="example1_paginate"><ul class="pagination" style="visibility: hidden;"><li class="prev disabled"><a title="Prev" href="#"><i class="fa fa-angle-left"></i></a></li><li class="next disabled"><a title="Next" href="#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                    </div>
                                    <?php } ?>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>                  
                    <div class="clearfix"></div>
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php include('footer.php') ?>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
			<style>
			#setwidth{
				width: 13% !important;
			}
			</style>
