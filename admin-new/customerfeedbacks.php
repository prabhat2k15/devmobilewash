<?php include('header.php') ?>
<style>
    .strike td:not(:first-child){ 
        text-decoration: line-through;
    }
    .resolved{
        background-color: #ed6b75;
        text-decoration:none;
        color:#fff
    }
    .resolved:hover ,  .resolved:active,  .resolved.active , .resolved:focus{
        background-color: #f15c68;
        transition: all .3s;
        text-decoration:none;
        color:#fff
    }
    .reopen{
        background-color:#16CE0C;
        text-decoration:none;
        color:#fff
    }
    .reopen:hover , .reopen:active , .reopen.active , .reopen:focus{
        transition: all .3s;
        background-color:#15b30c;
        text-decoration:none;
        color:#fff
    }
</style>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#example1').dataTable({
            //"order": [-1, "asc"],
            "order": [6, 'asc'],
            "pageLength": 20,
            "bLengthChange": false,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
        });
    });
</script>




<?php include('right-sidebar.php') ?>
<?php
$url = ROOT_URL . '/api/index.php?r=Customers/customersfeedbacksapp';
//echo $url;
$handle = curl_init($url);
$data = array('feedback_type' => 'customer', 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4], 'type' => $_GET['type']);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
//print_r($result); die;
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
//print_r($jsondata);
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
    .btn-primary.btn-resolved-grn{
        background:#16CE0C;
        border-color:#16CE0C;
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
                            <span class="caption-subject bold uppercase"> CUSTOMER APP FEEDBACK</span>
                        </div>
                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-5 col-md-offset-3">
                                <?php $praise_color = ($_GET['type'] == 'Praise') ? 'primary btn-resolved-grn' : 'primary'; ?>
                                <a href="<?php echo ROOT_URL; ?>/admin-new/customerfeedbacks.php?type=Praise" class="btn btn-<?php echo $praise_color; ?>">PRAISE</a>
                                <?php $questions_color = ($_GET['type'] == 'Questions') ? 'primary btn-resolved-grn' : 'primary'; ?>
                                <a href="<?php echo ROOT_URL; ?>/admin-new/customerfeedbacks.php?type=Questions" class="btn btn-<?php echo $questions_color; ?>">QUESTIONS</a>
                                <?php $suggestion_color = ($_GET['type'] == 'Suggestion') ? 'primary btn-resolved-grn' : 'primary'; ?>
                                <a href="<?php echo ROOT_URL; ?>/admin-new/customerfeedbacks.php?type=Suggestion" class="btn btn-<?php echo $suggestion_color; ?>">SUGGESTION</a>
                                <?php $problem_color = ($_GET['type'] == 'Problem') ? 'primary btn-resolved-grn' : 'primary'; ?>
                                <a href="<?php echo ROOT_URL; ?>/admin-new/customerfeedbacks.php?type=Problem" class="btn btn-<?php echo $problem_color; ?>">PROBLEM</a>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                            <thead>
                                <tr>

                                    <?php if ($_GET['type'] == 'Problem' || $_GET['type'] == 'Suggestion' || $_GET['type'] == 'Questions') { ?>
                                        <th class="row1"> Action </th> 
                                    <?php } else { ?>
                                        <th class = "row1 hide"> Action </th>
                                    <?php } ?>
                                    <th class="row1"> Customer ID </th>                                 
                                    <th class="row2"> Customer Name </th>
                                    <th class="row2"> Customer Number </th>
                                    <th> Customer Feedback </th>
                                    <th>Orders</th>
                                    <th> Create Date </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                function is_iterable($var) {
                                    return $var !== null && (is_array($var) || $var instanceof Traversable || $var instanceof Iterator || $var instanceof IteratorAggregate
                                            );
                                }

                                foreach ($jsondata as $responseage) {
                                    if (is_iterable($responseage)) {
                                        foreach ($responseage as $responseagents) {
                                            $strike = ($responseagents->status == 1) ? 'strike' : ' ';
                                            //$totalrecord = $responseagents->totalrecorc;  
                                            ?>
                                            <tr class="odd gradeX <?= $strike ?>">
                                                <?php if ($_GET['type'] == 'Problem' || $_GET['type'] == 'Suggestion' || $_GET['type'] == 'Questions') { ?>
                                                    <td> <?php if ($responseagents->status == 0) { ?> 
                                                            <a  id="<?= $responseagents->feedBackId ?>" data-val="1" class="btn  updateStatus resolved"> Resolved </a> <?php } else {
                                                        ?>
                                                            <a  id="<?= $responseagents->feedBackId ?>" data-val="0" class="btn  updateStatus reopen"> Reopen </a>
                                                        <?php } ?></td>
                                                <?php } else { ?>
                                                    <td class="hide"><?php echo $responseagents->feedBackId; ?></td>  
                                                <?php } ?>
                                                <td><?php echo $responseagents->id; ?></td>
                                                <td><?php
                                                    if (!empty($responseagents->customer)) {
                                                        echo $responseagents->customer;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></td> 
                                                <td><?php
                                                    if (!empty($responseagents->contact_number)) {
                                                        echo $responseagents->contact_number;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></td> 
                                                <td><?php
                                                    if (!empty($responseagents->comments)) {
                                                        echo $responseagents->comments;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    if (!empty($responseagents->orders)) {
                                                        echo $responseagents->orders;
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    if (!empty($responseagents->create_time)) {
                                                        echo date("Y-m-d h:i A", strtotime($responseagents->create_time));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></td>   
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
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

<script>
    $('.updateStatus').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var status = $(this).attr('data-val');
        $.ajax({
            type: 'GET',
            url: '<?= ROOT_URL ?>/api/index.php?r=Customers/CustomersfeedbacksappUpdatStatus',
            data: {status: status, id: id, key: '<?= API_KEY ?>', api_token: '<?= $finalusertoken ?>', t1: '<?= $mw_admin_auth_arr[2] ?>', t2: '<?= $mw_admin_auth_arr[3] ?>', user_type: 'admin', user_id: '<?= $mw_admin_auth_arr[4] ?>', type: '<?= $_GET['type'] ?>'},
            success: function (data) {
                location.reload();
            }});
    });

</script>
</script