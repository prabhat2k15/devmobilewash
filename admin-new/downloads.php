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

$handle_data = curl_init(ROOT_URL . "/api/index.php?r=Downloads/DownloadData");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array("key" => API_KEY, "from" => $from, "to" => $to, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$download_data = json_decode($result);
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
                            <span class="caption-subject bold uppercase">Manage Download List  </span>
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

                    <div class="portlet-body">


                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                            <thead>
                                <tr>
                                    <th> Zone </th>
                                    <th> Total# of Downloads </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Blue</td>
                                    <td><?php echo $download_data->blue; ?></td>
                                </tr>
                                <tr>
                                    <td>Yellow</td>
                                    <td><?php echo $download_data->yellow; ?></td>
                                </tr>
                                <tr>
                                    <td>Red</td>
                                    <td><?php echo $download_data->red; ?></td>
                                </tr>
                                <tr>
                                    <td>Purple</td>
                                    <td><?php echo $download_data->purple; ?></td>
                                </tr>

                            </tbody>
                        </table>


                        <div class="tab-content">
                            <!-- PERSONAL INFO TAB -->
                            <div class="tab-pane active" id="tab_1_1">

                                <?php if (count($download_data->all_washes_city)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th> City Name </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($download_data->all_washes_city as $washbycity): ?>
                                                <tr>
                                                    <td><?php echo $washbycity->city; ?></td>
                                                    <td><?php echo $washbycity->total; ?></td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <h2>Nothing Found</h2>
                                <?php endif; ?> 
                            </div>

                            <div class="tab-pane" id="tab_1_5">
                                <?php if (count($download_data->all_washes_zipcode)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                        <thead>
                                            <tr>
                                                <th> Zipcode </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($download_data->all_washes_zipcode as $washbyzip): ?>
                                                <tr>
                                                    <td><?php echo $washbyzip->zipcode; ?></td>
                                                    <td><?php echo $washbyzip->total; ?></td>
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
        buttons: [
            'csvHtml5'
        ]
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
