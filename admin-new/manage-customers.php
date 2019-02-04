<?php include('header.php') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<!--<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
     <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
     <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>-->
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">

</script>

<?php include('right-sidebar.php') ?>

<?php
$url = ROOT_URL . '/api/index.php?r=customers/clientsadmin';


//echo $url;
$handle = curl_init($url);
$data = array('limit' => $_GET['limit'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
//print_r($result);
//die;
curl_close($handle);
$allcustomers = json_decode($result);
//$response = $jsondata->response;
//$result_code = $jsondata->result;
/*  echo "<pre>";
  print_r($result);
  print_r($jsondata);
  echo "<pre>";
  exit; */

$url = ROOT_URL . '/api/index.php?r=customers/AverageCustomer';


//echo $url;
$handle = curl_init($url);
$data = array('limit' => $_GET['limit'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);

//die;
curl_close($handle);
$allcustomersFrequency = json_decode($result);
//number_format($allcustomersFrequency->spent_frequency, 2);
//number_format($allcustomersFrequency->order_frequency, 2);
?>
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
    .portlet-body form {
        padding-bottom: 10px;
    }

    .cust-search-box{
        margin-bottom: 20px;
        display: none;
    }

    .cust-search-box h2{
        font-size: 26px;
        font-weight: 400;
    }
    .dt-button.buttons-csv.buttons-html5 {
        opacity: 0;
    }
    .portlet-body{
        width: 100%;
        overflow-x: scroll;
    }
    .average-total-list-detail {
        padding:0px;
        margin-top: 10px;
        list-style:none;
    }
    .average-total-list-detail li{
        display: inline-block;
        margin-right:20px;
        font-size:14px;
    }
    .average-total-list-detail li span{
        font-weight:600;
    }
</style>


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
                            <span class="caption-subject bold uppercase"> Managed Customers</span><a style="margin-left: 20px;" class="csv-link" href="javascript:void(0)">Download CSV</a>
                            <ul class="average-total-list-detail">
                                <li>
                                    <label>Average Total Spent (min. 1 order):</label><span> $<?= number_format($allcustomersFrequency->spent_frequency, 2); ?></span>
                                </li>
                                <li>
                                    <label>Average Total Orders (min. 1 order):</label><span>$<?= number_format($allcustomersFrequency->order_frequency, 2); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="actions">
                        <i class="icon-calendar"></i>&nbsp;
                        <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                    </div>
                </div>
                <div class="portlet-body">
                    <form method="post" action="">
                        <div class="row">

                            <div class="col-md-12 col-sm-12">
                                <?php if ($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>

                                    <?php /* <button class="btn blue all-clients-logout" style="float: left; display: none;">Logout All Clients</button> */ ?>
                                    <div style="clear: both;"></div>
                                <?php endif; ?>

                                <div class="cust-search-box">
                                    <h2>SEARCH CUSTOMER</h2>
                                    <input type="text" id="cust_search" class="form-control" name="search" style="max-width: 400px;" />
                                </div>

                                <p style="margin-bottom: 20px; font-size: 16px;">Load Customers <select class='order-limit'><option value="400" <?php if ($_GET['limit'] == 400) echo "selected"; ?>>400</option><option value="600" <?php if ($_GET['limit'] == 600) echo "selected"; ?>>600</option><option value="800" <?php if ($_GET['limit'] == 800) echo "selected"; ?>>800</option><option value="1000" <?php if ($_GET['limit'] == 1000) echo "selected"; ?>>1000</option><option value="" <?php if (!$_GET['limit']) echo "selected"; ?>>All</option></select></p>

                            </div>
                        </div>

                    </form>
                    <?php if ($_GET['action'] == 'allclogout-success'): ?>
                        <p style="background: green; color: #fff; padding: 10px; margin: 10px 0;">All clients successfully logout</p>
                    <?php endif; ?>
                    <?php if ($_GET['action'] == 'allclogout-error'): ?>
                        <p style="background: red; color: #fff; padding: 10px; margin: 10px 0;">Error in logout operation</p>
                    <?php endif; ?>

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                        <!--table class="table table-striped table-bordered table-hover table-checkable order-column"-->
                        <thead>
                            <tr>
                                <th style="display: <?php echo $edit; ?>"> Actions </th>
                                <th> ID </th>
                                <th> User Type </th>
                                <th> Customer Name </th>
                                <th> Email </th>
                                <th> Rating </th>
                                <th> Orders </th>
                                <th> Points </th>
                                <th> Phone </th>
                                <th> Phone Verify Code </th>
                                <th> Device Type </th>
                                <th> Address </th>
                                <th> City </th>
                                <th> How Hear MW </th>
                                <th> Total Spent </th>
                                <th>Average Order Frequency (min. 1 order)</th>
                                <th>Average Total Spent (min. 1 order)</th>
                                <th>Average Total Orders (min. 1 order)</th>
                                <th> Created Date </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($allcustomers)) {

                                foreach ($allcustomers as $responseagents) {

                                    $city = 'N/A';
                                    $address = 'N/A';
                                    /*
                                      $url = ROOT_URL.'/api/index.php?r=customers/getcustomerAddress&customer_id='.$responseagents->id;
                                      $handle = curl_init($url);
                                      $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
                                      curl_setopt($handle, CURLOPT_POST, true);
                                      curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                                      curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                                      $result = curl_exec($handle);
                                      curl_close($handle);
                                      $jsondata = json_decode($result);
                                      $response = $jsondata->response;
                                      $result_code = $jsondata->result;


                                      if(!empty($jsondata->address)){
                                      $addrs_exp = explode(',',$jsondata->address);
                                      $address = $addrs_exp[0];
                                      if(!empty(trim($addrs_exp[1])) && trim($addrs_exp[1]) !='CA'){
                                      $city = $addrs_exp[1];
                                      }elseif(trim($addrs_exp[1]) =='CA'){
                                      $city = $jsondata->address;
                                      }

                                      }
                                     */
                                    ?>
                                    <tr class="odd gradeX">
                                        <td style="display: <?php echo $edit; ?>"> <a href="edit-customer.php?customerID=<?php echo $responseagents->id; ?>">Edit</a></td>
                                        <td> <?php echo $responseagents->id; ?> </td>
                                        <td> <?php echo $responseagents->user_type; ?> </td>
                                        <td> <a target="_blank" href="<?php echo ROOT_URL; ?>/admin-new/all-orders.php?customer_id=<?php echo $responseagents->id; ?>"><?php echo $responseagents->name; ?></a> </td>
                                        <td> <?php echo $responseagents->email; ?> </td>
                                        <td> <?php echo $responseagents->rating; ?> </td>
                                        <td> <?php
                                            if ($responseagents->total_wash > 0)
                                                echo "<a target='_blank' href='" . ROOT_URL . "/admin-new/all-orders.php?customer_id=" . $responseagents->id . "'>" . $responseagents->total_wash . "</a>";
                                            else
                                                echo $responseagents->total_wash;
                                            ?> </td>
                                        <td> <?php echo $responseagents->wash_points; ?>/5 </td>
                                        <td> <?php echo $responseagents->phone; ?> </td>
                                        <td> <?php echo $responseagents->phone_verify_code; ?> </td>
                                        <td> <?php echo $responseagents->device_type; ?> </td>
                                        <td> <?php echo $responseagents->address; ?> </td>
                                        <td> <?php echo $responseagents->city; ?> </td>
                                        <td> <?php echo $responseagents->how_hear_mw; ?> </td>
                                        <td> $<?php echo $responseagents->total_spent; ?> </td>
                                        <td> <?php echo number_format($responseagents->order_frequency, 2) . " days"; ?></td>
                                        <td> $<?php echo $responseagents->totalSpentAverage; ?> </td>
                                        <td> $<?php echo $responseagents->totalOrderAverage; ?> </td>
                                        <td> <?php echo $responseagents->client_science; ?> </td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
            <div class="clear"></div>

            <div class="clear"></div>
        </div>
    </div>
    <div class="clearfix"></div>

</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>-->
<!-- END PAGE LEVEL SCRIPTS -->
<style>

    .page-content-wrapper .page-content{
        padding: 0 20px 10px !important;
    }
</style>

<script>
    var ajaxrunning = 0;
    var table;
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
        params.limit = 40;
    $(document).ready(function () {

        table = $('#example1').dataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
            "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csvHtml5'
            ]
        });

        $(document).ready(function () {
            $('.csv-link').on('click', function () {
                $('.buttons-csv').trigger('click');
            });
        });

        $('.cust-search-box').show();

        $('#cust_search').keyup(function () {

            //$('#example1').dataTable().fnFilter(this.value);
            table.fnFilter(this.value);
        });

    });
    $(function () {
        /* $(document).on( 'click', '.all-clients-logout', function(){ */
        $('.all-clients-logout').click(function (e) {
            var th = $(this);
            console.log(th);
            var r = confirm('Are you sure you want to logout all clients?');
            if (r == true) {
                $(th).html('Logging out...');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=customers/allclientslogout", {key: API_KEY, api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    if (data.result == 'true') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/manage-customers.php?action=allclogout-success";
                    }
                    if (data.result == 'false') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/manage-customers.php?action=allclogout-error";
                    }

                });

            }
            return false;
        });

        setInterval(function () {
            if (!ajaxrunning) {
                ajaxrunning = 1;
                loadmorecustomers();
            }
            /*if($('.pagination li.next').prev().hasClass('active')) {
             
             if (!ajaxrunning) {
             ajaxrunning = 1;
             loadmorecustomers();
             }
             
             }*/
        }, 10000);

        /*$('.pagination li').click(function(e){
         var timer;
         clearTimeout(timer); 
         timer = setTimeout(function(){
         if($('.pagination li.next').prev().hasClass('active')) console.log('last one');
         }, 1000); 
         
         
         });*/

        var curr_url = "<?php echo ROOT_URL; ?>/admin-new/manage-customers.php";

        $(".order-limit").change(function () {
            if ($(this).val())
                window.location.href = curr_url + '?limit=' + $(this).val();
            else
                window.location.href = curr_url;
        });

        function loadmorecustomers() {
            console.log('loading...');
            console.log(params);
            $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=customers/clientsadmin", params, function (data) {
                console.log(data);
                if (data) {
                    //console.log(data);

                    //alldata = dt_table.fnGetData();
                    //console.log(alldata);
                    //dt_table.fnClearTable();

                    $.each(data, function (index, value) {
                        var morecustomers = [];

                        /*morecustomers["DT_RowId"] = "order-" + value.id;
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
                         //if(value.washer_wash_activity == 0) upcomingwashes["DT_RowClass"] = "washernowashactivity";*/

                        morecustomers.push(" <a href='edit-customer.php?customerID=" + value.id + "'>Edit</a>");
                        morecustomers.push(value.id);
                        morecustomers.push(value.user_type);
                        morecustomers.push("<a target='_blank' href='<?php echo ROOT_URL; ?>/admin-new/all-orders.php?customer_id=" + value.id + "'>" + value.name + "</a>");
                        morecustomers.push(value.email);
                        morecustomers.push(value.rating);
                        if (value.total_wash > 0)
                            morecustomers.push("<a target='_blank' href='<?php echo ROOT_URL; ?>/admin-new/all-orders.php?customer_id=" + value.id + "'>" + value.total_wash + "</a>");
                        else
                            morecustomers.push(value.total_wash);
                        morecustomers.push(value.wash_points + "/5");
                        morecustomers.push(value.phone);
                        morecustomers.push(value.phone_verify_code);
                        morecustomers.push(value.device_type);
                        morecustomers.push(value.address);
                        morecustomers.push(value.city);
                        morecustomers.push(value.how_hear_mw);
                        morecustomers.push("$" + value.total_spent);
                        morecustomers.push(value.order_frequency);
                        morecustomers.push(value.totalSpentAverage);
                        morecustomers.push(value.totalOrderAverage);
                        morecustomers.push(value.client_science);
                        table.add(morecustomers).draw(false);
                        //table.fnAddData(morecustomers, false);
                        //console.log(upcomingwashes);
                    });
//table.fnDraw();
                    ajaxrunning = 0;
                }



            });
        }
    });
</script>