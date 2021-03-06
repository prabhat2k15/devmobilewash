<?php include('header.php') ?>
<?php
$voice_print = '';
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<?php include('right-sidebar.php') ?>

<?php
/* $day = $_event = '';
  if( isset($_GET['day']) && !empty( $_GET['event'] ) ){
  $day = $_GET['day'];
  //$_event = $_GET['event'];
  } */

$zipcode = "";

/* SCHEDULE ORDER */
$day = $_event = $month = '';
if (isset($_GET['event']) && !empty($_GET['event'])) {
    $_event = $_GET['event'];
}

if (isset($_GET['day']) && !empty($_GET['day'])) {
    $day = $_GET['day'];
    $_event = $_GET['event'];
}
if (isset($_GET['month']) && !empty($_GET['month'])) {
    $month = $_GET['month'];
    $_event = $_GET['event'];
}
if (isset($_GET['zipcode']) && !empty($_GET['zipcode'])) {
    $zipcode = $_GET['zipcode'];
}

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = $_GET['city'];
}

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

$url = ROOT_URL . '/api/index.php?r=site/getallwashrequestsnew';
$cust_id = 0;
$agent_id = 0;
if (isset($_GET['customer_id']))
    $cust_id = $_GET['customer_id'];
if (isset($_GET['agent_id']))
    $agent_id = $_GET['agent_id'];
$handle = curl_init($url);
$data = array('to' => $to, 'from' => $from, 'city' => $city, 'zipcode' => $zipcode, 'day' => $day, 'event' => $_event, 'filter' => $_GET['filter'], 'limit' => $_GET['limit'], 'customer_id' => $cust_id, 'agent_id' => $agent_id, 'admin_username' => $jsondata_permission->user_name, 'key' => API_KEY, 'month' => $month, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);

curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
//print_r($result);die;
curl_close($handle);
$jsondata = json_decode($result);
//print_r($jsondata); die;
$s_orders_response = $jsondata->response;
$s_orders_result_code = $jsondata->result;
$s_mw_all_orders = $jsondata->wash_requests;
//print_r($s_mw_all_orders); die;
/* \\ echo"<pre>";print_r($s_mw_all_orders);echo"</pre>";die; */
$pending_order_count = '';
if (!$jsondata->pending_wash_count)
    $pending_order_count = "no orders";
if ($jsondata->pending_wash_count == 1)
    $pending_order_count = "1 order";
if ($jsondata->pending_wash_count > 1)
    $pending_order_count = $jsondata->pending_wash_count . " orders";
$voice_print = "Hello " . $jsondata_permission->user_name . "! You have " . $pending_order_count . " pending.";
$cust_avg_order_frequency = $jsondata->cust_avg_order_frequency;
$android_count = $jsondata->android_count;
$ios_count = $jsondata->ios_count;
/* echo "<pre>";
  print_r($result);
  echo "</pre>"; */
?>
<style>

    .label-complete {
        background-color: #16CE0C !important;
    }

    .label-pending {
        background-color: #DCAD53 !important;
    }

    .label-fraud {
        background-color: #f44336 !important;
    }

    .label-process {
        background-color: #D0792B !important;
    }

    .label-enroute {
        background-color: #00BCD4 !important;
    }

    .label-cancel {
        background-color: #999 !important;
    }

    .preloader{
        background: rgba(255, 255, 255, .9);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999999;
    }

    .loader {
        border: 10px solid #ccc;
        border-radius: 50%;
        border-top: 10px solid #0880e6;
        width: 80px;
        height: 80px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        position: absolute;
        top: 50%;
        margin-top: -40px;
        left: 50%;
        margin-left: -40px;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @-webkit-keyframes glowing {
        0% { background-color: #fff; }
        50% { background-color: red;  }
        100% { background-color: #fff; }
    }

    @-moz-keyframes glowing {
        0% { background-color: #fff; }
        50% { background-color: red;  }
        100% { background-color: #fff; }
    }

    @-o-keyframes glowing {
        0% { background-color: #fff; }
        50% { background-color: red;  }
        100% { background-color: #fff; }
    }

    @keyframes glowing {
        0% { background-color: #fff; }
        50% { background-color: red;  }
        100% { background-color: #fff; }
    }


    @-webkit-keyframes glowing2 {
        0% { background-color: #fff; }
        50% { background-color: #FFA500;  }
        100% { background-color: #fff; }
    }

    @-moz-keyframes glowing2 {
        0% { background-color: #fff; }
        50% { background-color: #FFA500;  }
        100% { background-color: #fff; }
    }

    @-o-keyframes glowing2 {
        0% { background-color: #fff; }
        50% { background-color: #FFA500;  }
        100% { background-color: #fff; }
    }

    @keyframes glowing2 {
        0% { background-color: #fff; }
        50% { background-color: #FFA500;  }
        100% { background-color: #fff; }
    }


    @-webkit-keyframes glowing3 {
        0% { background-color: #fff; }
        50% { background-color: #FF1493;  }
        100% { background-color: #fff; }
    }

    @-moz-keyframes glowing3 {
        0% { background-color: #fff; }
        50% { background-color: #FF1493;  }
        100% { background-color: #fff; }
    }

    @-o-keyframes glowing3 {
        0% { background-color: #fff; }
        50% { background-color: #FF1493;  }
        100% { background-color: #fff; }
    }

    @keyframes glowing3 {
        0% { background-color: #fff; }
        50% { background-color: #FF1493;  }
        100% { background-color: #fff; }
    }


    @-webkit-keyframes glowing4 {
        0% { background-color: #fff; }
        20% { background-color: red;  }
        40% { background-color: #fff; }
        60% { background-color: #FF1493;  }
        80% { background-color: #fff; }
        100% { background-color: red; }
    }

    @-moz-keyframes glowing4 {
        0% { background-color: #fff; }
        20% { background-color: red;  }
        40% { background-color: #fff; }
        60% { background-color: #FF1493;  }
        80% { background-color: #fff; }
        100% { background-color: red; }
    }

    @-o-keyframes glowing4 {
        0% { background-color: #fff; }
        20% { background-color: red;  }
        40% { background-color: #fff; }
        60% { background-color: #FF1493;  }
        80% { background-color: #fff; }
        100% { background-color: red; }
    }

    @keyframes glowing4 {
        0% { background-color: #fff; }
        20% { background-color: red;  }
        40% { background-color: #fff; }
        60% { background-color: #FF1493;  }
        80% { background-color: #fff; }
        100% { background-color: red; }
    }


    @-webkit-keyframes glowing5 {
        0% { background-color: #fff; }
        20% { background-color: #2714ff;  }
        40% { background-color: #fff; }
        60% { background-color: #2714ff;  }
        80% { background-color: #fff; }
        100% { background-color: #fff; }
    }

    @-moz-keyframes glowing5 {
        0% { background-color: #fff; }
        20% { background-color: #2714ff;  }
        40% { background-color: #fff; }
        60% { background-color: #2714ff;  }
        80% { background-color: #fff; }
        100% { background-color: #fff; }
    }

    @-o-keyframes glowing5 {
        0% { background-color: #fff; }
        20% { background-color: #2714ff;  }
        40% { background-color: #fff; }
        60% { background-color: #2714ff;  }
        80% { background-color: #fff; }
        100% { background-color: #fff; }
    }

    @keyframes glowing5 {
        0% { background-color: #fff; }
        20% { background-color: #2714ff;  }
        40% { background-color: #fff; }
        60% { background-color: #2714ff;  }
        80% { background-color: #fff; }
        100% { background-color: #fff; }
    }

    @-webkit-keyframes glowing6 {
        0% { background-color: #ff5722; }
        50% { background-color: #ff5722;  }
        100% { background-color: #ff5722; }
    }

    @-moz-keyframes glowing6 {
        0% { background-color: #ff5722; }
        50% { background-color: #ff5722;  }
        100% { background-color: #ff5722; }
    }

    @-o-keyframes glowing6 {
        0% { background-color: #ff5722; }
        50% { background-color: #ff5722;  }
        100% { background-color: #ff5722; }
    }

    @keyframes glowing6 {
        0% { background-color: #ff5722; }
        50% { background-color: #ff5722;  }
        100% { background-color: #ff5722; }
    }


    .flashrow{
        -webkit-animation: glowing 1500ms infinite;
        -moz-animation: glowing 1500ms infinite;
        -o-animation: glowing 1500ms infinite;
        animation: glowing 1500ms infinite;
    }

    .flashrow td{
        border: 0 !important;

    }

    .flashrownotarrive{
        -webkit-animation: glowing2 1500ms infinite;
        -moz-animation: glowing2 1500ms infinite;
        -o-animation: glowing2 1500ms infinite;
        animation: glowing2 1500ms infinite;
    }

    .flashrownotarrive td{
        border: 0 !important;

    }

    .flashrowdeclined{
        -webkit-animation: glowing3 1500ms infinite;
        -moz-animation: glowing3 1500ms infinite;
        -o-animation: glowing3 1500ms infinite;
        animation: glowing3 1500ms infinite;
    }

    .flashrowdeclined td{
        border: 0 !important;

    }

    .flashrowdeclinednotarrive{
        -webkit-animation: glowing4 1500ms infinite;
        -moz-animation: glowing4 1500ms infinite;
        -o-animation: glowing4 1500ms infinite;
        animation: glowing4 1500ms infinite;
    }

    .flashrowdeclinednotarrive td{
        border: 0 !important;

    }

    .flashrowchangedpack{
        -webkit-animation: glowing5 1500ms infinite;
        -moz-animation: glowing5 1500ms infinite;
        -o-animation: glowing5 1500ms infinite;
        animation: glowing5 1500ms infinite;
    }

    .flashrowchangedpack td{
        border: 0 !important;

    }

    .washernowashactivity{
        -webkit-animation: glowing6 1500ms infinite;
        -moz-animation: glowing6 1500ms infinite;
        -o-animation: glowing6 1500ms infinite;
        animation: glowing6 1500ms infinite;
    }

    .washernowashactivity td{
        border: 0 !important;

    }


    .spec-orders{
        width: 100%;
        height: auto;
        max-height: 100px;
        background: rgba(234, 0, 85, 0.84);
        color: #fff;
        padding: 20px;
        padding-top: 0;
        box-sizing: border-box;
        overflow: auto;
        display: none;
        margin-bottom: 20px;
    }

    .spec-orders p{
        margin-top: 20px;
        margin-bottom: 0;
    }

    .spec-orders p a{
        margin-left: 10px;
        color: #fff;
        text-decoration: underline;
    }

    .spec-orders-2{
        width: 100%;
        height: auto;
        max-height: 100px;
        background: #ff5722e6;

        color: #fff;
        padding: 20px;
        padding-top: 0;
        box-sizing: border-box;
        overflow: auto;
        display: none;
        margin-bottom: 20px;
    }

    .spec-orders-2 p{
        margin-top: 20px;
        margin-bottom: 0;
    }

    .spec-orders-2 p a{
        margin-left: 10px;
        color: #fff;
        text-decoration: underline;
    }

    .spec-orders-3{
        width: 100%;
        height: auto;
        max-height: 100px;
        background: #92c300e0;

        color: #fff;
        padding: 20px;
        padding-top: 0;
        box-sizing: border-box;
        overflow: auto;
        display: none;
        margin-bottom: 20px;
    }

    .spec-orders-3 p{
        margin-top: 20px;
        margin-bottom: 0;
    }

    .spec-orders-3 p a{
        margin-left: 10px;
        color: #fff;
        text-decoration: underline;
    }

    .spec-orders-4{
        width: 100%;
        height: auto;
        max-height: 100px;
        background: rgba(0, 83, 234, 0.84);

        color: #fff;
        padding: 20px;
        padding-top: 0;
        box-sizing: border-box;
        overflow: auto;
        display: none;
        margin-bottom: 20px;
    }

    .spec-orders-4 p{
        margin-top: 20px;
        margin-bottom: 0;
    }

    .spec-orders-4 p a{
        margin-left: 10px;
        color: #fff;
        text-decoration: underline;
    }

    .table-scrollable {
        width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #e7ecf1;
        margin: 10px 0 !important;
        height: 800px;
    }
    .table-scrollable table {
        width: 2150px;
    }
    .large-table-fake-top-scroll-container-3 {
        width: 100%;
        overflow-x: scroll;
        overflow-y: auto;
    }
    .large-table-fake-top-scroll-container-3 div {
        font-size:1px;
        line-height:1px;
        width: 2150px;
        height: 1px;
    }
    .portlet.light{
        margin-bottom: 0px;
    }
    .dataTables_paginate{
        height: 28px;
    }
    .street_view span {
        position: absolute;
        background: #000;
        width: 150px;
        opacity: 0.7;
        color: #fff;
        border-radius: 6px;
        padding: 5px;
        top: 5px;
        left: 5px;
    }
    .street_view{
        position: relative;
    }
    .dt-button.buttons-csv.buttons-html5 { opacity: 0; }

    .alert-box-wrap{
        width: 600px;
        position: absolute;
        z-index: 999;
        top: 0px;
        left: 55%;
        margin-left: -300px;
        height: auto;
        display: none;
        max-height: 200px;
        box-sizing: border-box;
        overflow: auto;
        display: none;
    }

    .spec-order-list{
        list-style:none;
        margin: 0;
        padding: 0;
    }

    .spec-order-list li{
        display: block;
        color: #fff;
        padding: 10px;
        border-bottom: 1px solid #fff;

    }

    .spec-order-list li a{
        margin-left: 10px;
        color: #fff;
        text-decoration: underline;
    }

    .spec-order-list li.processordeclined, .spec-order-list li.nowashercancel{
        background: rgba(234, 0, 85, 0.84);
    }

    .spec-order-list li.addonupgrade{
        background: rgba(0, 83, 234, 0.84);
    }

    .spec-order-list li.meetwasher{
        background: #ff5722e6;
    }

    .spec-order-list li.washernoarrive30min{

        background: #92c300e0;
    }
</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="alert-box-wrap">

        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> <?php
                                if ($_GET['filter'] == 'upcoming')
                                    echo 'Upcoming';
                                if ($_GET['filter'] == 'nonupcoming')
                                    echo 'Non-Upcoming';
                                ?> All Orders </span><?php if ((isset($_GET['event'])) && ($_GET['event'] == 'total_orders' || $_GET['event'] == 'newcustomer' || $_GET['event'] == "topCustomerOrder" || $_GET['event'] == "all")) { ?><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                                <?php } ?>

                        </div>
                        <div class="caption font-dark">

                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>

                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>

                    </div>
                    <div class="portlet-body">
                        <p style="margin-bottom: 20px; font-size: 16px;">Limit Orders <select class='order-limit'><option value="200" <?php if ($_GET['limit'] == 200) echo "selected"; ?>>200</option><option value="400" <?php if ($_GET['limit'] == 400) echo "selected"; ?>>400</option><option value="600" <?php if ($_GET['limit'] == 600) echo "selected"; ?>>600</option><option value="800" <?php if ($_GET['limit'] == 800) echo "selected"; ?>>800</option><option value="1000" <?php if ($_GET['limit'] == 1000) echo "selected"; ?>>1000</option><option value="0" <?php if (!$_GET['limit']) echo "selected"; ?>>none</option></select></p>
                        <p style="margin-bottom: 20px; font-size: 16px;">Filter Orders <select class='order-filter'><option value="" <?php if (!$_GET['filter']) echo "selected"; ?>>Real Orders</option><option value="testorders" <?php if ($_GET['filter'] == 'testorders') echo "selected"; ?>>Test Orders</option></select></p>

                        <?php if ($s_orders_result_code == 'true') { ?>
                            <div class="large-table-fake-top-scroll-container-3">
                                <div>&nbsp;</div>
                            </div>
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                    <thead>
                                        <tr>
                                            <th> Actions </th>
                                            <th> ID </th>
                                            <th> Order Type </th>
                                            <th> Status </th>
                                            <th> Payment </th>
                                            <th> Transaction ID </th>
                                            <th> Declined Transaction ID </th>
                                            <!--th> Customer ID </th-->
                                            <th> Customer Name </th>

                                            <th> Customer Phone </th>
                                            <?php if ($_GET['customer_id']): ?>
                                                <th> Avg. Order<br>Frequency </th>
                                            <?php endif; ?>
                                            <!--th> Agent ID </th-->
                                            <th> Badge </th>
                                            <th> Agent Name </th>
                                            <!--th> Agent Email </th-->
                                            <th> Agent Phone </th>
                                            <th style='min-width: 115px;'> Address </th>
                                            <?php if ((isset($_GET['event'])) && ($_GET['event'] == 'total_orders' || $_GET['event'] == 'newcustomer')) { ?>
                                                <th style="display: none;"> House Number </th>
                                                <th style="display: none;"> Street </th>
                                                <th style="display: none;"> City </th>
                                                <th style="display: none;"> State </th>
                                                <th style="display: none;"> ZipCode </th>
                                            <?php } ?>
                                            <th> Schedule Datetime </th>
                                            <!--<th class="hide"> Starts </th>-->
                                            <th> Vehicles </th>
                                            <th> Total Price </th>
                                            <!--th>Total Price </th-->
                                            <!--th>Transaction ID </th-->
                                            <th> Created Date </th>
                                            <th> Completed Date </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($s_mw_all_orders as $ind => $order) {
                                            ?>
                                            <tr class="odd gradeX <?php if ($ind == 0) echo "flashro"; ?>" id="order-<?php echo $order->id; ?>">
                                                <td><a href="edit-order.php?id=<?php echo $order->id; ?>" class="appt-edit-order" data-id="<?php echo $order->id; ?>" style="margin-right: 7px;">Edit</a></td>
                                                <td><?php echo $order->id; ?></td>
                                                <td><?php if ($order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #0046ff !important;">Scheduled</span></p><?php endif; ?><?php if (!$order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #009688 !important;">On-Demand</span></p><?php endif; ?></td>
                                                <td>

                                                    <?php if ($order->status == 5 || $order->status == 6): ?>
                                                        <span class="label label-sm label-cancel">Cancelled</span>
                                                    <?php elseif (!$order->status): ?>
                                                        <span class="label label-sm label-pending">Pending</span>
                                                    <?php elseif ($order->status == 1): ?>
                                                        <span class="label label-sm label-enroute">En Route</span>
                                                    <?php elseif ($order->status == 2): ?>
                                                        <span class="label label-sm label-process">Arrived</span>
                                                    <?php elseif ($order->status == 3): ?>
                                                        <span class="label label-sm label-process">In Process</span>
                                                    <?php elseif ($order->status == 4): ?>
                                                        <span class="label label-sm label-complete">Completed</span>
                                                    <?php elseif ($order->status == 7): ?>
                                                        <span class="label label-sm label-cancel">CNR</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php
                                                    if (($order->payment_status == 'Declined') || ($order->payment_status == 'Check Fraud'))
                                                        echo"<span class='label label-sm label-fraud'>" . $order->payment_status . "</span><br><br>";
                                                    else
                                                        echo $order->payment_status;
                                                    ?>
                                                    <?php if ($order->payment_type == 'free'): ?>
                                                        <span class="label label-sm label-complete">Free Wash</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ((($order->status == 5) || ($order->status == 7) || ($order->status == 0)) && ($order->canceled_wash_transaction_id)): ?>
                                                        <a target="_blank" href="<?php echo BT_TRANSACTION_URL . $order->canceled_wash_transaction_id; ?>"><?php echo $order->canceled_wash_transaction_id; ?></a>
                                                    <?php else: ?>
                                                        <?php if ($order->transaction_id): ?>
                                                            <a target="_blank" href="<?php echo BT_TRANSACTION_URL . $order->transaction_id; ?>"><?php echo $order->transaction_id; ?></a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($order->failed_transaction_id): ?>
                                                        <a target="_blank" href="<?php echo BT_TRANSACTION_URL . $order->failed_transaction_id; ?>"><?php echo $order->failed_transaction_id; ?></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td><a target="_blank" href="<?php echo ROOT_URL; ?>/admin-new/all-orders.php?customer_id=<?php echo $order->customer_id; ?>"><?php echo $order->customer_name; ?></a></td>
                                                <td><?php echo $order->customer_phoneno; ?></td>
                                                <?php if ($_GET['customer_id']): ?>
                                                    <td><?php echo $cust_avg_order_frequency; ?> days</td>
                                                <?php endif; ?>
                                                <td><?php
                                                    if (count($order->agent_details))
                                                        echo $order->agent_details->real_washer_id;
                                                    else
                                                        echo "N/A";
                                                    ?>
                                                </td>
                                                <td><?php
                                                    if (count($order->agent_details))
                                                        echo "<a target='_blank' href='" . ROOT_URL . "/admin-new/all-orders.php?agent_id=" . $order->agent_details->agent_id . "'>" . $order->agent_details->agent_name . "</a>";
                                                    else
                                                        echo "N/A";
                                                    ?>
                                                </td>
                                                <!--td><?php
                                                /* if(count($order->agent_details)) echo $order->agent_details->agent_email;
                                                  else echo "N/A"; */
                                                ?>
                                                </td-->
                                                <td><?php
                                                    if (count($order->agent_details))
                                                        echo $order->agent_details->agent_phoneno;
                                                    else
                                                        echo "N/A";
                                                    ?>
                                                </td>
                                                <td><?php echo $order->address . " (" . $order->address_type . ")"; ?></td>
                                                <?php
                                                if ((isset($_GET['event'])) && ($_GET['event'] == 'total_orders' || $_GET['event'] == 'newcustomer')) {
                                                    $addressArr = explode(',', $order->address);
                                                    //$Arr_field['field_value']['address'] = $order->address; 
                                                    //print_r($addressArr);
                                                    $house_name = preg_replace('/[^0-9]/', '', $addressArr[0]);
                                                    $street = str_replace($house_name, '', $addressArr[0]);
                                                    $zipcode = preg_replace('/[^0-9]/', '', $addressArr[2]);
                                                    $state = str_replace($zipcode, '', $addressArr[2]);
                                                    ?>
                                                    <td style="display: none;"> <?php echo $house_name; ?> </td>
                                                    <td style="display: none;"> <?php echo (!empty($order->street_name)) ? $order->street_name : $street; ?> </td>
                                                    <td style="display: none;"> <?php echo (!empty($order->city)) ? $order->city : $addressArr[1]; ?> </td>
                                                    <td style="display: none;"> <?php echo (!empty($order->state)) ? $order->state : $state; ?> </td>
                                                    <td style="display: none;"> <?php echo (!empty($order->zipcode)) ? $order->zipcode : $zipcode; ?> </td>
                                                <?php } ?>

                                                <td>
                                                    <?php if ($order->is_scheduled): ?>
                                                        <?php if (strtotime($order->reschedule_date) > 0): ?>

                                                            <p style="color: red; font-weight: bold; font-size: 13px; margin: 0;">Rescheduled to <?php echo date('m/d/Y h:i A', strtotime($order->reschedule_date)); ?></p>
                                                        <?php endif; ?>
                                                        <?php if (strtotime($order->schedule_date) > 0) echo date('m/d/Y h:i A', strtotime($order->schedule_date)); ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <?php /* <td class="hide">
                                                  <?php
                                                  if ($order->min_diff > 0)
                                                  echo $order->min_diff;
                                                  else
                                                  echo "-";
                                                  ?>
                                                  </td> */ ?>
                                                <td><?php
                                                    if (count($order->vehicles)) {
                                                        echo "<ol style='padding-left: 15px;'>";
                                                        foreach ($order->vehicles as $car) {
                                                            echo "<li style='margin-bottom: 10px;'>" . $car->make . " " . $car->model . " (" . $car->pack . ")";
                                                            if ($car->addons)
                                                                echo " - Addons: " . $car->addons;
                                                            echo "</li>";
                                                        }
                                                        echo "</ol>";
                                                    }
                                                    ?></td>
                                                <!--td><?php
                                                /* if($order->schedule_total) echo "$".$order->schedule_total;
                                                  else echo "N/A"; */
                                                ?></td-->
                                               <!--td><?php //echo $order->transaction_id;                                            ?></td-->
                                                <?php
                                                if ($order->coupon_discount) {
                                                    $coupon_discount = $order->coupon_discount;
                                                } else {
                                                    $coupon_discount = 0.00;
                                                }
                                                $sum = ($order->agent_total + $order->company_total);
                                                ?>
                                                <td>$<?php echo number_format($sum, 2); ?>   </td>
                                                <td><?php echo date('m/d/Y h:i A', strtotime($order->created_date)); ?></td>
                                                <td>
                                                    <?php
                                                    if (strtotime($order->complete_order) > 0) {
                                                        echo date('m/d/Y h:i A', strtotime($order->complete_order));
                                                    } else {
                                                        echo " ";
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
 <!--<script src='js/jquery.voicerss-tts.min.js'></script>-->
<script>
    /*
     $.speech({
     key: '571c40dafcda4d3fabdca18e960c9198',
     src: "<?php echo $voice_print; ?>",
     hl: 'en-us',
     r: -2, 
     c: 'mp3',
     f: '44khz_16bit_stereo',
     ssml: false
     });
     */
</script>
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    var dt_table;

    $(document).ready(function () {
        $.fn.dataTable.moment('YYYY-MM-DD hh:mm A');

        $.fn.dataTableExt.oSort['nullable-asc'] = function (a, b) {
            if (a == '-')
                return 1;
            else if (b == '-')
                return -1;
            else
            {
                var ia = parseInt(a);
                var ib = parseInt(b);
                return (ia < ib) ? -1 : ((ia > ib) ? 1 : 0);
            }
        }

        $.fn.dataTableExt.oSort['nullable-desc'] = function (a, b) {
            if (a == '-')
                return 1;
            else if (b == '-')
                return -1;
            else
            {
                var ia = parseInt(a);
                var ib = parseInt(b);
                return (ia > ib) ? -1 : ((ia < ib) ? 1 : 0);
            }
        }

        /*         dt_table = $('#example1, #example2').dataTable( {
         "pageLength": 20,
         "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
         
         "aaSorting": []
         
         } );*/

    });
</script>
<?php if ((isset($_GET['event'])) && ($_GET['event'] == 'total_orders' || $_GET['event'] == 'newcustomer' || $_GET['topCustomerOrder'])) { ?>
    <script type="text/javascript">
        dt_table = $('#example1, #example2').DataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "aaSorting": [],
            "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'csv',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21] // indexes of the columns that should be printed,
                    }
                }
            ]
        });
        $(document).ready(function () {

            $('.csv-link').on('click', function () {
                $('.buttons-csv').trigger('click');
            });
        });
    </script>
<?php } else { ?>
    <script type="text/javascript">
        dt_table = $('#example1, #example2').dataTable({
            "pageLength": 20,
            "dom": 'Bfrtip',
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "aaSorting": [],
            "buttons": [
                'csvHtml5'
            ]

        });
        $('.csv-link').on('click', function () {
            $('.buttons-csv').trigger('click');
        });
    </script>
<?php } ?>
<script>
    var params = {};
<?php foreach ($_GET as $key => $value) {
    ?>
        params.<?php echo $key; ?> = "<?php echo $value; ?>";
<?php }; ?>
    params.key = "<?php echo API_KEY; ?>";
    params.admin_username = "<?php echo $jsondata_permission->user_name; ?>";
    params.api_token = "<?php echo $finalusertoken; ?>";
    params.t1 = "<?php echo $mw_admin_auth_arr[2]; ?>";
    params.t2 = "<?php echo $mw_admin_auth_arr[3]; ?>";
    params.user_type = 'admin';
    params.user_id = "<?php echo $mw_admin_auth_arr[4]; ?>";

    if ((!params.limit) || params.limit > 100)
        params.limit = 100;

    $(function () {
        $(document).on('click', '.delete-order', function () {
            var th = $(this);
            id = $(this).data('id');
            var r = confirm('Are you sure you want to delete order #' + id + '?');
            if (r == true) {
                $(th).html('Deleting...');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=PhoneOrders/deleteorder", {id: id, key: '<?php echo API_KEY; ?>', api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    if (data.result == 'true') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-success&nid=" + id;
                    }
                    if (data.result == 'false') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-error";
                    }

                });

            }
            return false;
        });

        $(document).on('click', '.appt-delete-order', function () {
            var th = $(this);
            id = $(this).data('id');
            var r = confirm('Are you sure you want to delete order #' + id + '?');
            if (r == true) {
                $(th).html('Deleting...');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=ScheduleOrders/deleteorder", {id: id, key: '<?php echo API_KEY; ?>', api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    if (data.result == 'true') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-success&nid=" + id;
                    }
                    if (data.result == 'false') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-error";
                    }

                });

            }
            return false;
        });

        $(".preloader").remove();

        var curr_url = "<?php echo ROOT_URL; ?>/admin-new/all-orders.php?filter=<?php echo $_GET['filter']; ?>&city=<?php echo $_GET['city']; ?>&event=<?php echo $_GET['event']; ?>&zipcode=<?php echo $_GET['zipcode']; ?>&to=<?php echo $to; ?>&from=<?php echo $from; ?>";
                var limit = "<?php echo $_GET['limit']; ?>";
                $(".order-limit").change(function () {
                    window.location.href = curr_url + '&limit=' + $(this).val();
                });

                $(".order-filter").change(function () {
                    if (limit)
                        window.location.href = '<?php echo ROOT_URL; ?>/admin-new/all-orders.php?filter=' + $(this).val() + '&limit=' + limit;
                    else
                        window.location.href = '<?php echo ROOT_URL; ?>/admin-new/all-orders.php?filter=' + $(this).val();
                });

                /*
                 dt_table.fnDeleteRow( $(".portlet-body table tr#order-8767"));
                 var alldata = dt_table.fnGetData();
                 dt_table.fnClearTable();
                 //console.log(alldata);
                 dt_table.fnAddData( [
                 "<a href='edit-schedule-order.php?id=8783' class='appt-edit-order' data-id='8783' style='margin-right: 7px;'>Edit</a>",
                 "8784",
                 "<span class='label label-sm label-pending'>Pending</span>",
                 "",
                 "",
                 "",
                 "",
                 "",
                 "",
                 "",
                 "30"
                 ]
                 );
                 
                 dt_table.fnAddData(alldata);
                 
                 // $(".portlet-body table tbody").prepend('<tr role="row" class="odd"><td><a href="edit-schedule-order.php?id=8783" class="appt-edit-order" data-id="8783" style="margin-right: 7px;">Edit</a></td><td>8784</td><td><span class="label label-sm label-pending">Pending</span></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="sorting_1">30</td><td></td><td></td></tr>');
                 dt_table.fnDraw();
                 */
            });

            function pendingflashingorder() {
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=site/adminpendingschedwashesalert", {key: '<?php echo API_KEY; ?>', api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    $(".portlet-body table tr").removeClass('flashrow');
                    if (data.result == 'true') {
//console.log(data.wash_ids);
                        $.each(data.wash_ids, function (index, value) {
                            $(".portlet-body table tr#order-" + value).addClass('flashrow');
                        });
                    }


                });
            }

</script>
<?php if ($_GET['ajax'] == 'true'): ?>
    <script>
        $(function () {
            $("body").on("click", ".admin-notify-view-click", function () {

                var wash_id = $(this).attr('data-id');
                var click_action = $(this).attr('data-action');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=site/updateadminnotifyview", {wash_request_id: wash_id, notify_name: click_action, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: '<?php echo API_KEY; ?>', api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {


                });
                $(this).parent().remove();
                if ($(".spec-order-list").children().length < 1) {
                    $(".spec-order-list").remove();
                }

                if ($(".alert-box-wrap").children().length < 1) {
                    $(".alert-box-wrap").hide();
                }

                if (click_action == 'washer_change_pack')
                    $("#example1 tr#order-" + wash_id).removeClass('flashrowchangedpack');
                window.open('edit-order.php?id=' + wash_id, '_blank');
                return false;

            });

            /*$( "body" ).on( "click", ".spec-order-list li a", function() {
             if ($(this).hasClass("addonupgrade-view")) {
             return false;
             }
             
             var wash_id = $(this).attr('data-id');
             
             $(this).parent().remove();
             if ($(".spec-order-list").children().length < 1){
             $(".spec-order-list").remove();
             }
             
             if ($(".alert-box-wrap").children().length < 1){
             $(".alert-box-wrap").hide();
             }
             window.open('edit-order.php?id='+wash_id, '_blank');
             return false;
             
             });*/

        });
        function ajaxorderlist() {
            var alldata;
            var upcomingwashes = [];
            var processordeclined_washes = "";
            var meetwasheralertbox = "";
            var washernoarrive30minbox = "";
            var addonupgradebox = "";
            var popuptextred = '';
            var popuptextgreen = '';
            var popuptextorange = '';
            var popuptextblue = '';
            var popuptextfull = '';
            //console.log(params);
            $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=site/getallwashrequestsnew", params, function (data) {

                if (data.result == 'true') {
                    //console.log(data);
                    $(".portlet-body table tr").removeClass('flashrow');
                    $.each(data.wash_requests, function (index, value) {

                        dt_table.fnDeleteRow($(".portlet-body table tr#order-" + value.id));

                    });

                    alldata = dt_table.fnGetData();
                    //console.log(alldata);
                    dt_table.fnClearTable();

                    $.each(data.wash_requests, function (index, value) {
                        var upcomingwashes = [];
                        if (value.payment_status == 'Declined') {
                            popuptextred += "<li class='processordeclined'>#" + value.id + " Processor declined order - " + value.customer_name + " <a data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if (value.washer_pay_status == 'pending') {
                            popuptextred += "<li class='processordeclined'>#" + value.id + " Payment non-settled order - " + value.customer_name + " <a data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if (value.washer_change_pack > 0) {
                            popuptextblue += "<li class='addonupgrade'>#" + value.id + " upgraded package/changed addons order - " + value.customer_name + " <a class='admin-notify-view-click' data-action='washer_change_pack' data-id='" + value.id + "' href='#'>View</a></li>";
                        }
                        if (value.admin_submit_for_settle == 1) {
                            popuptextred += "<li class='processordeclined'>#" + value.id + " BT custom payment order - " + value.customer_name + " <a data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if (value.washercustnomeet == 1) {
                            popuptextorange += "<li class='meetwasher'>#" + value.id + " - Customer has not selected meet Washer or No Thanks for 10 minutes - Call Customer <a class='admin-notify-view-click' data-action='washercustnomeet' data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if (value.washer_wash_activity == 0) {
                            popuptextorange += "<li class='meetwasher'>#" + value.id + " - Washer hasn't tapped Start Wash for 10 minutes - Please call <a class='admin-notify-view-click' data-action='washer_wash_activity' data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if (value.washer_30_min_noarrive == 1) {
                            popuptextgreen += "<li class='washernoarrive30min'>#" + value.id + " - En Route Washer " + value.agent_details.agent_name + " hasn't tapped \"arrive\" within 30 minutes - Please Call <a data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        if ((value.no_washer_cancel == 1) && (value.status == 5)) {
                            popuptextred += "<li class='nowashercancel'>#" + value.id + " Auto canceled - " + value.customer_name + " <a class='admin-notify-view-click' data-action='nowashercancel' data-id='" + value.id + "' href='edit-order.php?id=" + value.id + "' target='_blank'>View</a></li>";
                        }
                        upcomingwashes["DT_RowId"] = "order-" + value.id;
                        //if((value.min_diff > 0) && (value.min_diff <= 30) && (value.status == 0)) upcomingwashes["DT_RowClass"] = "flashrow";
                        if ((value.min_diff <= 30) && (value.status == 0))
                            upcomingwashes["DT_RowClass"] = "flashrow";
                        if ((value.min_diff < 0) && (value.status == 1))
                            upcomingwashes["DT_RowClass"] = "flashrownotarrive";
                        if (value.payment_status == 'Declined')
                            upcomingwashes["DT_RowClass"] = "flashrowdeclined";
                        if ((value.min_diff <= 30) && (value.status == 0) && (value.payment_status == 'Declined'))
                            upcomingwashes["DT_RowClass"] = "flashrowdeclinednotarrive";
                        if (value.washer_change_pack > 0)
                            upcomingwashes["DT_RowClass"] = "flashrowchangedpack";
                        if ((value.no_washer_cancel == 1) && (value.status == 5))
                            upcomingwashes["DT_RowClass"] = "flashrow";
                        //if(value.washer_wash_activity == 0) upcomingwashes["DT_RowClass"] = "washernowashactivity";

                        upcomingwashes.push("<a href='edit-order.php?id=" + value.id + "' class='appt-edit-order' data-id='" + value.id + "' style='margin-right: 7px;'>Edit</a>");
                        upcomingwashes.push(value.id);
                        if (value.is_scheduled == 1) {
                            upcomingwashes.push("<p><span class='label label-sm label-pending' style='background-color: #0046ff !important;'>Scheduled</span></p>");
                        } else {
                            upcomingwashes.push("<p><span class='label label-sm label-pending' style='background-color: #009688 !important;'>On-Demand</span></p>");
                        }
                        //var checklist_arr = value.checklist.split('|');

                        if (value.status == 5 || value.status == 6) {
                            upcomingwashes.push("<span class='label label-sm label-cancel'>Cancelled</span>");
                        } else if (value.status == 0) {

                            upcomingwashes.push("<span class='label label-sm label-pending'>Pending</span>");
                        } else if (value.status == 1) {
                            upcomingwashes.push("<span class='label label-sm label-process'>En Route</span>");
                        } else if (value.status == 2) {
                            upcomingwashes.push("<span class='label label-sm label-process'>Arrived</span>");
                        } else if (value.status == 3) {
                            upcomingwashes.push("<span class='label label-sm label-process'>In Process</span>");
                        } else if (value.status == 4) {
                            upcomingwashes.push("<span class='label label-sm label-complete'>Completed</span>");
                        } else if (value.status == 7) {
                            upcomingwashes.push("<span class='label label-sm label-cancel'>CNR</span>");
                        }

                        var payment_status_str = '';
                        if ((value.payment_status == 'Declined') || (value.payment_status == 'Check Fraud')) {
                            payment_status_str += "<span class='label label-sm label-fraud'>" + value.payment_status + "</span><br><br>";

                        } else
                            payment_status_str += value.payment_status;

                        if (value.payment_type == 'free')
                            payment_status_str += "<span class='label label-sm label-complete'>Free Wash</span>";
                        upcomingwashes.push(payment_status_str);

                        if (value.transaction_id)
                            upcomingwashes.push("<a target='_blank' href='<?php echo BT_TRANSACTION_URL; ?>" + value.transaction_id + "'>" + value.transaction_id + "</a>");
                        else
                            upcomingwashes.push('');
                        if (value.failed_transaction_id)
                            upcomingwashes.push("<a target='_blank' href='<?php echo BT_TRANSACTION_URL; ?>" + value.failed_transaction_id + "'>" + value.failed_transaction_id + "</a>");
                        else
                            upcomingwashes.push('');

                        upcomingwashes.push("<a target='_blank' href='<?php echo ROOT_URL; ?>/admin-new/all-orders.php?customer_id=" + value.customer_id + "'>" + value.customer_name + "</a>");
                        upcomingwashes.push(value.customer_phoneno);
                        if (value.agent_details.real_washer_id)
                            upcomingwashes.push(value.agent_details.real_washer_id);
                        else
                            upcomingwashes.push("N/A");
                        if (value.agent_details.agent_name)
                            upcomingwashes.push("<a target='_blank' href='<?php echo ROOT_URL; ?>/admin-new/all-orders.php?agent_id=" + value.agent_details.agent_id + "'>" + value.agent_details.agent_name + "</a>");
                        else
                            upcomingwashes.push("N/A");
                        if (value.agent_details.agent_phoneno)
                            upcomingwashes.push(value.agent_details.agent_phoneno);
                        else
                            upcomingwashes.push("N/A");
                        upcomingwashes.push(value.address + " (" + value.address_type + ")");

                        if (value.is_scheduled == 1) {
                            if (value.reschedule_time) {
                                var d = new Date(value.reschedule_date + " " + value.reschedule_time);
                                upcomingwashes.push("<p style='color: red; font-weight: bold; font-size: 13px; margin: 0;'>Rescheduled to " + d.toLocaleString('en-US', {day: '2-digit', month: '2-digit', year: 'numeric', hour12: true, hour: '2-digit', minute: '2-digit'}));
                                //upcomingwashes.push("<p style='color: red; font-weight: bold; font-size: 13px; margin: 0;'>Rescheduled to " + value.reschedule_date + " " + value.reschedule_time + "</p>");
                            } else {
                                if (value.schedule_time) {
                                    console.log("sch date" + value.schedule_date)
                                    var d = new Date(value.schedule_date + " " + value.schedule_time);
                                    upcomingwashes.push(d.toLocaleString('en-US', {day: '2-digit', month: '2-digit', year: 'numeric', hour12: true, hour: '2-digit', minute: '2-digit'}));
                                    //upcomingwashes.push(value.schedule_date + " " + value.schedule_time);
                                } else {
                                    upcomingwashes.push("N/A");
                                }
                            }
                        } else {
                            upcomingwashes.push("N/A");
                        }

                        /*if (value.min_diff > 0)
                         // upcomingwashes.push(value.min_diff);
                         else
                         // upcomingwashes.push("-");*/

                        var veh_string = '';
                        if (value.vehicles.length) {

                            veh_string += "<ol style='padding-left: 15px;'>";
                            $.each(value.vehicles, function (ind, val) {
                                veh_string += "<li style='margin-bottom: 10px;'>" + val.make + " " + val.model + " (" + val.pack + ")";
                                if (val.addons) {
                                    veh_string += " - Addons: " + val.addons;
                                }
                                veh_string += "</li>";
                            });
                            veh_string += "</ol>";
                        }
                        upcomingwashes.push(veh_string);
                        upcomingwashes.push("$" + value.net_price);
                        var d = new Date(value.created_date);
                        upcomingwashes.push(d.toLocaleString('en-US', {day: '2-digit', month: '2-digit', year: 'numeric', hour12: true, hour: '2-digit', minute: '2-digit'}));
                        // upcomingwashes.push(value.created_date);
                        if (value.status == 4) {
                            var d = new Date(value.complete_order);
                            upcomingwashes.push(d.toLocaleString('en-US', {day: '2-digit', month: '2-digit', year: 'numeric', hour12: true, hour: '2-digit', minute: '2-digit'}));
                            //upcomingwashes.push(value.complete_order);
                        } else {
                            upcomingwashes.push(' ');
                        }
                        dt_table.fnAddData(upcomingwashes);
                        //console.log(upcomingwashes);
                    });

                    if (alldata.length > 0)
                        dt_table.fnAddData(alldata);
                    //dt_table.fnDraw();
                }

                //console.log(processordeclined_washes);
                popuptextfull = popuptextred + popuptextgreen + popuptextblue + popuptextorange;
                if (popuptextfull != '') {
                    popuptextfull = "<ul class='spec-order-list'>" + popuptextfull + "</ul>";

                    $(".alert-box-wrap").html(popuptextfull);
                    $(".alert-box-wrap").show();
                } else {
                    $(".alert-box-wrap").html("");
                    $(".alert-box-wrap").hide();
                }


            });
            //console.log('working');

        }

        //pendingflashingorder();
        ajaxorderlist();
        var refreshId = setInterval(ajaxorderlist, 60000);

    </script>
<?php endif; ?>
<script type="text/javascript">
    $(function () {
        $(".table-scrollable").scroll(function () {
            $(".large-table-fake-top-scroll-container-3")
                    .scrollLeft($(".table-scrollable").scrollLeft());
        });
        $(".large-table-fake-top-scroll-container-3").scroll(function () {
            $(".table-scrollable")
                    .scrollLeft($(".large-table-fake-top-scroll-container-3").scrollLeft());
        });
    });
</script>