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
    .border-box{
        float: left;
        width: 100%;
        border: 1px solid #ccc;
        padding: 0px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .border-box h4,
    .fontBold{
        font-weight:600;
    }
    .border-box table tr th:last-child,
    .border-box table tr td:last-child{
        text-align:right;
        padding-right: 20px;
    }
    input[type="date"].form-control, input[type="time"].form-control, input[type="datetime-local"].form-control, input[type="month"].form-control {
        line-height: inherit;
    }
    .input-group-sm > .input-group-btn > select.btn, .input-group-sm > select.form-control, .input-group-sm > select.input-group-addon, select.input-sm{
        line-height: inherit;
    }
    .dataTables_length{
        text-transform: capitalize;
    }

    .dataTables_info {
        float:left;
        line-height: 30px;
    }
    .dataTables_paginate {
        float: right;

    }
    .dataTables_paginate ul.pagination{
        margin-top:0px;
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
                    <div class="portlet-title tabbable-line">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase">Downloads  </span><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                        </div>
                    </div>
                    <form action="" method="get">
                        <p>From: <input class="form-control form-control-inline input-medium date-picker" style="display: inline; width: 180px !important;" name="from" size="16" type="date" value="<?php echo $from; ?>" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required> To: <input class="form-control form-control-inline input-medium date-picker" name="to" size="16" style="display: inline; width: 180px !important;" type="date" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $to; ?>" required><input style="margin-left: 10px;" type="submit" value="Search" /></p>

                    </form>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top Zone</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;"> #</th>
                                                <th> Zone </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width:50px">1</td>
                                                <td>Blue</td>
                                                <td><?php echo $download_data->blue; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="width:50px">2</td>
                                                <td>Yellow</td>
                                                <td><?php echo $download_data->yellow; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="width:50px">3</td>
                                                <td>Red</td>
                                                <td><?php echo $download_data->red; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="width:50px">4</td>
                                                <td>Purple</td>
                                                <td><?php echo $download_data->purple; ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top City</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;"> #</th>
                                                <th> City Name </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($download_data->all_city as $city):
                                                ?>
                                                <tr>
                                                    <td style="width:50px;"><?= $i ?></td>
                                                    <td><?php echo $city->city; ?></td>
                                                    <td><?php echo $city->total; ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top State</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;">#</th>
                                                <th> State </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($download_data->all_state as $state):
                                                ?>
                                                <tr>
                                                    <td style="width:50px;"><?= $i; ?></td>
                                                    <td><?php echo $state->state; ?></td>
                                                    <td><?php echo $state->total; ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top Country</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example4">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Country </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($download_data->all_country as $country):
                                                ?>
                                                <tr>
                                                    <td style="width:50px"><?= $i; ?></td>
                                                    <td><?php echo $country->country; ?></td>
                                                    <td><?php echo $country->total; ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top Zipcode</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example5">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Zipcode </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($download_data->all_zipcode as $zipcode):
                                                ?>
                                                <tr>
                                                    <td style="width:50px;"> <?= $i ?></td>
                                                    <td><?php echo $zipcode->zipcode; ?></td>
                                                    <td><?php echo $zipcode->total; ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="border-box">
                                    <h4 class="text-center">Top Platform</h4>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example6">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Platform </th>
                                                <th> Total# of Downloads </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($download_data->all_source as $source):
                                                ?>
                                                <tr>
                                                    <td style="width:50px"><?= $i ?></td>
                                                    <td><?php echo $source->source; ?></td>
                                                    <td><?php echo $source->total; ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="fontBold">Latest Downloads</h4>
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example7">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Download Time </th>
                                            <th>Zipcode</th>
                                            <th>Zone</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Source</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($download_data->all_data as $val):
                                            ?>

                                            <tr>
                                                <td><?= $i + 1; ?></td>
                                                <td><?= date("Y-m-d h:i A", strtotime($val->created_at)) ?></td>
                                                <td><?= $val->zipcode ?></td>
                                                <td><?= $val->ZipColour; ?></td>
                                                <td><?= $val->city ?></td>
                                                <td><?= $val->state ?></td>
                                                <td><?= $val->country ?></td>

                                                <td><?= $val->source ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
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
    $('#example1, #example2, #example3, #example4, #example5, #example6').DataTable({
        pageLength: 5,
        "aaSorting": [],
//        buttons: [
//            'csvHtml5'
//        ]

    });
    $('#example7').DataTable({
        pageLength: 5,
        dom: 'Bfrtip',
        "aaSorting": [],
//        buttons: [
//            'csvHtml5'
//        ]
        buttons: [
            'csv',
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
