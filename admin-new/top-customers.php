<?php include('header.php') ?>
<?php
$from = "";
$to = "";
$voice_print = '';

if ($_GET['from']) {
    $from = $_GET['from'];
} else {
    $from = "2017-01-01";
}

if ($_GET['to']) {
    $to = $_GET['to'];
} else {
    $to = date('Y-m-d');
}
if ($_GET['limit']) {
    $limit = $_GET['limit'];
} else {
    $limit = 400;
}
$handle_data = curl_init(ROOT_URL . "/api/index.php?r=site/gettopmostcustomers");

curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array("limit" => $limit, "key" => API_KEY, "from" => $from, "to" => $to, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$customers_data = json_decode($result);
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<?php include('right-sidebar.php') ?>


<style>
    .dt-button.buttons-csv.buttons-html5 { opacity: 0;}
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
                            <span class="caption-subject bold uppercase"> Top Customers (<?php echo date("M j, Y", strtotime($from)); ?>  - <?php echo date("M j, Y", strtotime($to)); ?>) </span><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                        <div class="caption font-dark">

                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>

                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                        <div style="clear: both;"></div>
                        <form action="" method="get">
                            <p>From: <input type="hidden" name="limit" value="400"><input class="form-control form-control-inline input-medium date-picker" style="display: inline; width: 180px !important;" name="from" size="16" type="date" value="<?php echo $from; ?>" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required> To: <input class="form-control form-control-inline input-medium date-picker" name="to" size="16" style="display: inline; width: 180px !important;" type="date" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $to; ?>" required><input style="margin-left: 10px;" type="submit" value="Search" /></p>
                        </form>
                    </div>
                    <div class="portlet-body">
                        <p style="margin-bottom: 20px; font-size: 16px;">Limit 
                            <select class='order-limit'>
                                <option value="200" <?php if ($_GET['limit'] == 200) echo "selected"; ?>>200</option>
                                <option value="400" <?php if ($_GET['limit'] == 400) echo "selected"; ?>>400</option>
                                <option value="600" <?php if ($_GET['limit'] == 600) echo "selected"; ?>>600</option>
                                <option value="800" <?php if ($_GET['limit'] == 800) echo "selected"; ?>>800</option>
                                <option value="1000" <?php if ($_GET['limit'] == 1000) echo "selected"; ?>>1000</option>
                                <option value="" <?php if (!$_GET['limit']) echo "selected"; ?>>All</option>
                            </select>

                            <?php if (count($customers_data->top_customers)): ?>
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Customer Photo </th>
                                        <th> Name </th>
                                        <th> Email </th>
                                        <th style="display: none;"> Address </th>
                                        <th style="display: none;"> city </th>
                                        <th style="display: none;"> state </th>
                                        <th> Total Scheduled </th>
                                        <th> Total On Demand </th>
                                        <th> Total Washes </th>
                                        <th> Total Spent </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers_data->top_customers as $cust): ?>
                                        <tr>
                                            <td><?php echo $cust->customer_id; ?></td>
                                            <td><img class="washer_default_background" src="<?php
                                                if ($cust->image) {
                                                    echo $cust->image;
                                                } else {
                                                    echo "images/image_icon.png";
                                                }
                                                ?>" style="width: 100px; height: 100px;"></td>
                                            <td><a href="<?php echo ROOT_URL; ?>/admin-new/all-orders.php?event=topCustomerOrder&customer_id=<?php echo $cust->customer_id; ?>&from=<?php echo $from; ?>&to=<?php echo $to; ?>"><?php echo $cust->name; ?></a></td>
                                           <!--td><?php //echo $cust->contact_number;                     ?></td-->
                                            <td><?php echo $cust->email; ?></td>
                                            <td style="display: none;"><?php echo $cust->address; ?></td>

                                            <td style="display: none;"><?php echo $cust->city; ?></td>
                                            <td style="display: none;"><?php echo $cust->state; ?></td>
                                            <td><?php echo $cust->total_scheduled; ?></td>
                                            <td><?php echo $cust->total_demand; ?></td>
                                            <td><?php echo $cust->total_washes; ?></td>
                                            <td><?php echo ($cust->total_sum > 0) ? '$' . number_format($cust->total_sum, 2) : '$00.00'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        <?php else: ?>
                            <h2>Nothing Found</h2>
                        <?php endif; ?> 
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
<script type="text/javascript">
    $('#example1').DataTable({
        pageLength: 25,
        stateSave: true,
        //lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'csvHtml5',
        ]
    });
    $(document).ready(function () {
        $('.csv-link').on('click', function () {
            $('.buttons-csv').trigger('click');
        });
    });

    var curr_url = "<?php echo ROOT_URL; ?>/admin-new/top-customers.php?from=<?php echo $_GET['from']; ?>&to=<?php echo $_GET['to']; ?>";
        var limit = "<?php echo $_GET['limit']; ?>";
        $(".order-limit").change(function () {
            window.location.href = curr_url + '&limit=' + $(this).val();
        });
</script>
 <!--<script src='js/jquery.voicerss-tts.min.js'></script>-->

<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
