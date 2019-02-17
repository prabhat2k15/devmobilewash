<?php include('header.php') ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#example1').dataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "aaSorting": [],
                    "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'csvHtml5'
        ]
        });
        

    $('.csv-link').on('click',function(){
        $('.buttons-csv').trigger('click');
    });

    });
</script>
<?php include('right-sidebar.php') ?>
<?php
$url = ROOT_URL . '/api/index.php?r=customers/get3hourfeedbacks';
//echo $url;
$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
?>
<!--<style>
    table.dataTable thead .sorting_asc {
        background: rgba(0, 0, 0, 0) url("<?php echo ROOT_URL; ?>/admin-new/assets/global/css/../plugins/datatables/images/sort_both.png") no-repeat scroll right center !important;
        padding-right: 50px !important;
    }
    table.dataTable thead .sorting_desc {
        background: rgba(0, 0, 0, 0) url("<?php echo ROOT_URL; ?>/admin-new/assets/global/css/../plugins/datatables/images/sort_both.png") no-repeat scroll right center !important;
        padding-right: 50px !important;
    }
    
</style>-->
<style>
.dt-button.buttons-csv.buttons-html5 { opacity: 0;}
.table thead tr th{
    white-space: nowrap;
}
.table-scrollable {
    width: 100%;
    overflow-x: auto;
    overflow-y: auto;
    border: 1px solid #e7ecf1;
    margin: 10px 0 !important;
    max-height: 800px;
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
                            <span class="caption-subject bold uppercase"> Customer 3 hour email feedbacks</span><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                            <thead>
                                <tr>
                                    
                                    <th class="row1" style="min-width: 120px !important;"> Order Number </th>
                                    <th class="row2"> Customer name </th>
                                    <th class="row2"> Customer Email </th>
                                    <th class="row2"> Customer Phone </th>
                                    <th class="row2"> Customer Address </th>
                                     <th class="row2"> Customer Feedback </th>
                                    <th class="row2"> Washer Name & Badge</th>
                                    <th class="row2"> Create Date</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                /*function is_iterable($var) {
                                    return $var !== null && (is_array($var) || $var instanceof Traversable || $var instanceof Iterator || $var instanceof IteratorAggregate
                                            );
                                }*/

                                foreach ($jsondata as $responseage) {
                                        foreach ($responseage as $responseagents) {

                                            //$totalrecord = $responseagents->totalrecorc;  
                                            ?>
                                            <tr class="odd gradeX">

                                                    
                                                <td><a target="_blank" href="edit-order.php?id=<?php echo $responseagents->wash_request_id; ?>"><?php echo $responseagents->wash_request_id; ?></a></td>  
                                                <td><?php echo $responseagents->customername; ?></td>
                                                <td><?php echo $responseagents->email; ?></td>
                                                <td><?php echo $responseagents->contact_number; ?></td>
                                                <td><?php echo $responseagents->address; ?></td>
                                                
                                             
                                                <td><?php
                                                    if (!empty($responseagents->comments)) {
                                                        echo $responseagents->comments;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                    </td>
                                                   <td><?php echo $responseagents->agentname." (#".$responseagents->real_washer_id.")"; ?></td>
                                                   <td><?php echo date('Y-m-d g:i A', strtotime($responseagents->created_date)); ?></td>
                                            </tr>
                                            <?php
                                        }
                                  
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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