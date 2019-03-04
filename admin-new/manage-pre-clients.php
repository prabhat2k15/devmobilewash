<?php
include('header.php');
if ($_GET['action'] == 'trash') {
    $clientsid = $_GET['id'];
    $url = ROOT_URL . '/api/index.php?r=customers/trashpreclients&id=' . $clientsid;
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
    if ($response == "clients trashed" && $result_code == "true") {
        ?>
        <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php?trash=true"</script>
        <?php
        die();
    }
}
?>

<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#example1').dataTable({
            "order": [[6, "desc"]],
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
        });

    });
</script>

<?php include('right-sidebar.php') ?>

<?php
$url = ROOT_URL . '/api/index.php?r=customers/getallpreclients';
$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$preclients = json_decode($result);
//print_r($preclients);


$url_trash = ROOT_URL . '/api/index.php?r=customers/getpreclientstrashdata';
$handle_trash = curl_init($url_trash);
$data_trash = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle_trash, CURLOPT_POST, true);
curl_setopt($handle_trash, CURLOPT_POSTFIELDS, $data_trash);
curl_setopt($handle_trash, CURLOPT_RETURNTRANSFER, 1);
$result_trash = curl_exec($handle_trash);
curl_close($handle_trash);
$preclients_trash = json_decode($result_trash);
$count = $preclients_trash->count;
if (!empty($_GET['type']) && empty($_POST['submit'])) {
    $type = $_GET['type'];
    if ($type == 'pre_register_client') {
        $pre_register_client = 'current_tab';
    }
}
?>
<style>
    .label-online {
        background-color: #16CE0C !important;
    }

    .label-offline {
        background-color: #969696 !important;
    }
</style>
<style>
    .label-busy {
        background-color: #FF8C00 !important;
    }
    .label-online {
        background-color: #16CE0C !important;
    }
    .label-offline {
        background-color: #FF0202 !important;
    }
    .pagination ul {
        display: inline-block;
        margin-bottom: 0;
        margin-left: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .pagination li {
        display: inline;
    }
    li {
        line-height: 20px;
    }
    user agent stylesheetli {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .pagination li:first-child a, .pagination li:first-child span {
        border-left-width: 1px;
        -webkit-border-radius: 3px 0 0 3px;
        -moz-border-radius: 3px 0 0 3px;
        border-radius: 3px 0 0 3px;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    a {
        color: #08c;
        text-decoration: none;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    .pagination{
        width: 100%;
    }
    .page-content-wrapper .page-content {
        padding: 0 20px 10px !important;
    }

</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#total_customers').click(function () {
            if ($('.total_customers').html() == 0) {
                alert('There is no data found!');
                return false;
            }
            window.location.href = 'manage-customers.php';
        });
        $('#clientoffline').click(function () {
            if ($('.clientoffline').html() == 0) {
                alert('There is no data found!');
                return false;
            }
            window.location.href = 'manage-customers.php?type=clientoffline';
        });
        $('#cancel_orders_client').click(function () {
            if ($('.cancel_orders_client').html() == 0) {
                alert('There is no data found!');
                return false;
            }

            window.location.href = 'manage-customers.php?type=cancel_orders_client';
        });
        $('#idle_wash_client').click(function () {
            if ($('.idle_wash_client').html() == 0) {
                alert('There is no data found!');
                return false;
            }

            window.location.href = 'manage-customers.php?type=idle_wash_client';
        });
        $('#bad_rating_customers').click(function () {
            if ($('.bad_rating_customers').html() == 0) {
                alert('There is no data found!');
                return false;
            }
            window.location.href = 'manage-customers.php?type=bad_rating_customers';
        });
        $('#pre_register_client').click(function () {
            if ($('.pre_register_client').html() == 0) {
                alert('There is no data found!');
                return false;
            }
            window.location.href = 'manage-pre-clients.php?type=pre_register_client';
        });
    });
</script>
<style>
    .current_tab{
        background-color: #5407e2 !important;
        border-top: 5px solid #5407e2 !important;
        height: 90px !important;
        padding: 13px 0 0 10px !important;
        cursor: pointer !important;
    }
</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="row" style="background-color: #000; color: #fff; margin-left: -20px ! important; margin-right: -20px; height: 90px;">
            <div class="col-md-1 col-sm-1 <?php echo $total_customers; ?>" id="total_customers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #5407E2; height: 90px;">
                <div style="font-size: 20px;" class="total_customers">0</div>
                <div>Total Clients</div>
            </div>
            <div class="col-md-1 col-sm-1 <?php echo $clientoffline; ?>" id="clientoffline" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #0771e2;">
                <div style="font-size: 20px;" class="clientoffline">0</div>
                <div>Offline Clients</div>
            </div>
            <div class="col-md-2 col-sm-2 <?php echo $cancel_orders_client; ?>" id="cancel_orders_client" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e20724;">
                <div style="font-size: 20px; <?php echo $total_customers; ?>" class="cancel_orders_client">0</div>
                <div>Clients Cancels</div>
            </div>
            <div class="col-md-1 col-sm-1 <?php echo $idle_wash_client; ?>" id="idle_wash_client" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #07c1e2;">
                <div style="font-size: 20px;" class="idle_wash_client">0</div>
                <div>Idle Clients</div>
            </div>
            <div class="col-md-2 col-sm-2 <?php echo $bad_rating_customers; ?>" id="bad_rating_customers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e900e7;">
                <div style="font-size: 20px;" class="bad_rating_customers">0</div>
                <div>Flagged Bad Clients</div>
            </div>
            <div class="col-md-2 col-sm-2 <?php echo $pre_register_client; ?>" id="pre_register_client" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #0771e2;">
                <div style="font-size: 20px;" class="pre_register_client">0</div>
                <div>Pre Registered Clients</div>
            </div>
        </div>	
        <div class="clear">&nbsp;</div>

        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> MANAGE Pre-Registered Customers</span>
                        </div>
                        <div style="margin: -20px 0px 0px 100px; display: none;" class="caption font-dark" id="copy_clients">
                            <span class="caption-subject bold uppercase"> <img width="84" src="images/loader.gif" class="copy_clients"></span>
                        </div>

                        <div class="actions hide" style="padding: 1px 0px 0px 20px;">
                            <span class="caption-subject bold uppercase"><a href="trash-pre-clients.php"><img src="images/trash.png" width="30">(<?php echo $count; ?>)</a></span>
                        </div>


                        <div class="caption font-dark">

                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>

                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                        <?php if (!empty($_GET['update'])): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record updated successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if (!empty($_GET['restore'])): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record restored successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if ($_GET['action'] == 'move-success'): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Client moved to real client section successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if ($_GET['action'] == 'move-error'): ?>
                            <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in moving pre-client. Please try again.</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if (!empty($_GET['trash'])): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record moved to trash successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                    </div>
                    <div class="portlet-body">
                        <?php if ($preclients->result == 'true') { ?>
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                <thead>
                                    <tr>

                                        <th> ID </th>
                                        <th> Email </th>
                                        <th>Name </th>
                                        <th> Phone </th>
                                        <th> City </th>
                                        <th> State </th>
                                        <th>Register Date</th>
                                        <th style="min-width: 140px;"> Actions </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($preclients->all_clients as $clients) { ?>
                                        <tr class="odd gradeX">
                                            <td> <?php echo $clients->id; ?> </td>
                                            <td> <?php echo $clients->email; ?> </td>
                                            <td> <?php echo $clients->first_name . '' . $clients->last_name; ?> </td>
                                            <td> <?php echo $clients->phone; ?> </td>
                                            <td> <?php echo $clients->city; ?> </td>
                                            <td> <?php echo $clients->state; ?> </td>
                                            <td> <?= date("Y-m-d h:i A", strtotime($clients->register_date)) ?> </td>
                                            <td>
                                                <a href="pre-clients-details.php?id=<?php echo $clients->id; ?>" style="margin-right: 7px;">Edit</a><a href="#" data-id="<?php echo $clients->id; ?>" class="move-client" style="margin-right: 7px;">Move</a>  <a class="hide" onclick="return confirm('Are you sure ?')" href="manage-pre-clients.php?id=<?php echo $clients->id; ?>&action=trash">Trash</a>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
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
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>


<!-- END PAGE LEVEL SCRIPTS -->
<script>
                                            $(function () {
                                                $(document).on('click', '.move-client', function () {
                                                    var th = $(this);
                                                    id = $(this).data('id');
                                                    var r = confirm('Do you want to make pre-client #' + id + ' as real client?');
                                                    if (r == true) {
                                                        $(th).html('Moving...');
                                                        $.getJSON('<?php echo ROOT_URL; ?>/api/index.php?r=customers/MovePreToRealclient&clientid=' + id + '&movewasher=yes', {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                                                            if (data.result == 'true') {
                                                                window.location.href = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php?action=move-success";
                                                            }
                                                            if (data.result == 'false') {
                                                                window.location.href = "<?php echo ROOT_URL; ?>/admin-new/newsletters.php?action=move-error";
                                                            }

                                                        });

                                                    }
                                                    return false;
                                                });
                                            });
</script>