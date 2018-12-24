<?php include('header.php') ?>
<style>
    .input-group-sm .input-group-btn select.btn, 
    .input-group-sm select.form-control,
    .input-group-sm select.input-group-addon,
    select.input-sm{
        line-height:inherit;
    }
</style>

<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#example1').dataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "order": [[0, 'dsc']],
            "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'csv',
                    /*exportOptions: {
                     columns: [1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21] // indexes of the columns that should be printed,
                     } */
                }
            ]
        });

    });
    $(document).ready(function () {

        $('.csv-link').on('click', function () {
            $('.buttons-csv').trigger('click');
        });
    });
</script>
<?php include('right-sidebar.php') ?>
<?php
$url = ROOT_URL . '/api/index.php?r=customers/CustomerExpansionRequestList';

$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$data = $jsondata->data;
?>
<style>
    .label-online {
        background-color: #16CE0C !important;
    }

    .label-offline {
        background-color: #FF0202 !important;
    }
    .dt-button.buttons-csv.buttons-html5 { opacity: 0; }
    .scrollable-table tr th{
        white-space:nowrap;
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
                            <span class="caption-subject bold uppercase"> Expansion Requests</span><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                        <div class="caption font-dark">

                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>
                        <div class="caption font-dark" style="display: <?php echo $add_company; ?>">

                        </div>
                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                        <?php if ($_GET['action'] == 'delete-success'): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Vehicle #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/modern-vehicles.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if ($_GET['action'] == 'delete-error'): ?>
                            <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting vehicle. Please try again.</span><a href="/admin-new/modern-vehicles.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive scrollable-table">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                <thead>
                                    <tr>
                                        <!-- <th style="display:none"> S.No </th> -->
                                        <th> ID </th>
                                        <th> Customer Name </th>
                                        <th> Email </th>
                                        <th> Address </th>
                                        <th> City </th>
                                        <th> State </th>
                                        <th> Zipcode </th>
                                        <th> Create Date </th>
                                        <th>Source</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $key => $val) { ?> 
                                        <tr>
                                            <!-- <td style="display:none">$val->id ?></td> -->
                                            <td><?= $val->id ?></td>
                                            <td><?= $val->first_name . " " . $val->last_name ?></td>
                                            <td><?= $val->email ?></td>
                                            <td><?= $val->address ?></td>
                                            <td><?= $val->city ?></td>
                                            <td><?= $val->state ?></td>
                                            <td><?= $val->zipcode ?></td>
                                            <td><?= date("Y-m-d h:i A", strtotime($val->register_date)) ?></td>
                                            <td><?= $val->source ?></td>
                                        </tr>
                                    <?php } ?>
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
    .fullwidth{
        width: 14% !important;
    }
</style>
<?php include('footer.php') ?>
<script>
    $(function () {
        $(document).on('click', '.delete-car', function () {
            var th = $(this);
            id = $(this).data('id');
            carname = $(this).data('carname');

            var r = confirm('Are you sure you want to delete ' + carname + '?');
            if (r == true) {
                $(th).html('Deleting...');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=vehicles/deletevehicle", {id: id, build: 'regular', key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    if (data.result == 'true') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/modern-vehicles.php?action=delete-success&nid=" + id;
                    }
                    if (data.result == 'false') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/modern-vehicles.php?action=delete-error";
                    }

                });

            }
            return false;
        });
    });
</script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->