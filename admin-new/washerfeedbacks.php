<?php include('header.php') ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#example1').dataTable( {
                "order": [[ 3, "desc" ]],
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
} );
            
        });
        </script>
<?php include('right-sidebar.php') ?>
<?php
    $url = ROOT_URL.'/api/index.php?r=washing/washerfeedbacksapp';
        //echo $url;
        $handle = curl_init($url);
        $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4], 'type'=>$_GET['type']);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

?>
<style>
table.dataTable thead .sorting_asc {
    background: rgba(0, 0, 0, 0) url("<?php echo ROOT_URL; ?>/admin-new/assets/global/css/../plugins/datatables/images/sort_both.png") no-repeat scroll right center !important;
    padding-right: 50px !important;
}
table.dataTable thead .sorting_desc {
    background: rgba(0, 0, 0, 0) url("<?php echo ROOT_URL; ?>/admin-new/assets/global/css/../plugins/datatables/images/sort_both.png") no-repeat scroll right center !important;
    padding-right: 50px !important;
}
</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    
                    
                    
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">WASHER APP FEEDBACK</span>
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-5 col-md-offset-3">
                                            <a href="<?php echo ROOT_URL;?>/admin-new/washerfeedbacks.php?type=Praise" class="btn btn-primary">PRAISE</a>                                        
                                            <a href="<?php echo ROOT_URL;?>/admin-new/washerfeedbacks.php?type=Questions" class="btn btn-primary">QUESTIONS</a>                                        
                                            <a href="<?php echo ROOT_URL;?>/admin-new/washerfeedbacks.php?type=Suggestion" class="btn btn-primary">SUGGESTION</a>
                                            <a href="<?php echo ROOT_URL;?>/admin-new/washerfeedbacks.php?type=Problem" class="btn btn-primary">PROBLEM</a>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th class="row1"> Badge # </th>
                                                <th class="row2"> Washer Name </th>
                                                <th class="row2"> Wsher Number </th>
                                                <th> Washer Feedback </th>
                                                <th> Creat Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                   
                   function is_iterable($var)
{
    return $var !== null 
        && (is_array($var) 
            || $var instanceof Traversable 
            || $var instanceof Iterator 
            || $var instanceof IteratorAggregate
            );
}

    foreach($jsondata as $responseage)
{             
    if (is_iterable($responseage))
{
              foreach($responseage as $responseagents)
              {
                 
                 //$totalrecord = $responseagents->totalrecorc;  
               
               
?>
                <tr class="odd gradeX">
                    
                    <td><?php echo $responseagents->id;?></td>
                    <td><?php if(!empty($responseagents->customer)){ echo $responseagents->customer; } else { echo 'N/A'; } ?></td> 
                    <td><?php if(!empty($responseagents->contact_number)){ echo $responseagents->contact_number; } else { echo 'N/A'; } ?></td> 
                    <td><?php if(!empty($responseagents->comments)){ echo $responseagents->comments; } else { echo 'N/A'; } ?></td>
                    <td><?php if(!empty($responseagents->create_time)){ echo $responseagents->create_time; } else { echo 'N/A'; } ?></td>   
                </tr>
                <?php   }
}
} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>                  
                    <div class="clearfix"></div>
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <style>
            .row1{width: 10% !important;}
            .row2{width: 19% !important;}
            </style>
<?php include('footer.php') ?>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->