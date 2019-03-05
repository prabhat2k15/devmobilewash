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

$handle_data = curl_init(ROOT_URL . "/api/index.php?r=site/heatmaplistdata");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array("key" => API_KEY, "from" => $from, "to" => $to, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$heatmaplist_data = json_decode($result);
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
                    <div class="portlet-title tabbable-line">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Heatmap List (<?php echo date("M j, Y", strtotime($from)); ?>  - <?php echo date("M j, Y", strtotime($to)); ?>) </span>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab">City</a>
                            </li>
                            <li>
                                <a href="#tab_1_5" data-toggle="tab">Zipcode</a>
                            </li>
                        </ul>

                        <div style="clear: both;"></div>



                    </div>
                    <form action="" method="get">
                        <p>From: <input class="form-control form-control-inline input-medium date-picker" style="display: inline; width: 180px !important;" name="from" size="16" type="date" value="<?php echo $from; ?>" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required> To: <input class="form-control form-control-inline input-medium date-picker" name="to" size="16" style="display: inline; width: 180px !important;" type="date" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $to; ?>" required><input style="margin-left: 10px;" type="submit" value="Search" /></p>

                    </form>
                    <div class="portlet-body">


                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                            <thead>
                                <tr>
                                    <th> Zone </th>
                                    <th> Total # of Washes </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Blue</td>
                                    <td><?php echo $heatmaplist_data->blue_orders; ?></td>
                                </tr>
                                <tr>
                                    <td>Yellow</td>
                                    <td><?php echo $heatmaplist_data->yellow_orders; ?></td>
                                </tr>
                                <tr>
                                    <td>Red</td>
                                    <td><?php echo $heatmaplist_data->red_orders; ?></td>
                                </tr>
                                <tr>
                                    <td>Purple</td>
                                    <td><?php echo $heatmaplist_data->purple_orders; ?></td>
                                </tr>

                            </tbody>
                        </table>


                        <div class="tab-content">
                            <!-- PERSONAL INFO TAB -->
                            <div class="tab-pane active" id="tab_1_1">
                                <?php if (count($heatmaplist_data->all_washes_city)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th> City Name </th>
                                                <th> Total # of Washes </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($heatmaplist_data->all_washes_city as $washbycity): ?>
                                                <tr>
                                                    <td><?php echo $washbycity->city; ?></td>
                                                    <td><a href="<?= ROOT_URL ?>/admin-new/all-orders.php?city=<?= $washbycity->city ?>&event=city&filter=&limit=400"><?php echo $washbycity->total; ?></a></td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <h2>Nothing Found</h2>
                                <?php endif; ?> 
                            </div>

                            <div class="tab-pane" id="tab_1_5">
                                <?php if (count($heatmaplist_data->all_washes_zipcode)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                        <thead>
                                            <tr>
                                                <th> Zipcode </th>
                                                <th> Total # of Washes </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($heatmaplist_data->all_washes_zipcode as $washbyzip): ?>
                                                <tr>
                                                    <td><?php echo $washbyzip->zipcode; ?> - <?php echo $washbyzip->city; ?></td>
                                                    <td><a href="<?= ROOT_URL ?>/admin-new/all-orders.php?city=<?= $washbyzip->city ?>&zipcode=<?= $washbyzip->zipcode ?>&event=zipcode_and_city&filter=&limit=400"><?php echo $washbyzip->total; ?></a></td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <h2>Nothing Found</h2>
                                <?php endif; ?> 
                            </div>
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

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    $('#example1, #example2').DataTable({
        pageLength: 25,

        "aaSorting": [],
    });
    $(document).ready(function () {

        $('.csv-link').on('click', function () {
            $('.buttons-csv').trigger('click');
        });
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
