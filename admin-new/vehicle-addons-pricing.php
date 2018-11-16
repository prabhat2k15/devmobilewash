<?php include('header.php') ?>
<?php

if(isset($_POST['addons_pricing_submit'])){
   $fulltitles = '';
   $descs = '';
   $wash_times = '';
   $prices = '';
foreach($_POST['price'] as $ind=> $price){
    $fulltitles .= $_POST['fulltitle'][$ind]."|";
    $descs .= $_POST['description'][$ind]."|";
    $wash_times .= $_POST['wash_time'][$ind]."|";
    $prices .= $_POST['price'][$ind]."|";
}

$fulltitles = rtrim($fulltitles, '|');
$descs = rtrim($descs, '|');
$wash_times = rtrim($wash_times, '|');
$prices = rtrim($prices, '|');

$vehdata = array('fulltitles' => $fulltitles, 'descs' => $descs, 'wash_times' => $wash_times, 'prices' => $prices, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/updatevehicleaddonsplan");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
}

$vehdata = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/getvehicleaddons");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$pricingdata = json_decode($result);

?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME LAYOUT STYLES -->

<?php include('right-sidebar.php') ?>


<style>
#main{
    background-color: #EEF1F5;
}
.form-group {
    display: inline;
}
.imgbtn{
    text-align: center;
}
.green{
	background-color: green !important;
    border-color: green;
}
.reg-loading{
    display: none;
}
.classic-loading{
    display: none;
}
#regular-packlist{
    display: none;
}
#classic-packlist{
    display: none;
}
</style>
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content" id="main">
                    <!-- BEGIN PAGE HEADER-->


                    <!-- BEGIN PAGE TITLE-->
                   <!-- <h3 class="page-title"> New User Profile | Account
                        <small>user account page</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Vehicle Addons Pricing</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">

 <?php if($pricingdata->result == 'true'): ?>
<form action="" method="post" id="pricing_form">
<table class="table table-striped table-bordered order-column no-footer">
<tr>
    <th>Title</th>
<th>Fulltitle</th>
<th>Description</th>
<th>Package</th>
<th>Wash Time</th>
<th>Price</th>
 <th>Vehicle Type</th>
</tr>
<?php foreach($pricingdata->plans as $plan): ?>
<tr>
<td><?php echo $plan->title; ?></td>
<td><input type="text" value="<?php echo $plan->fulltitle; ?>" name="fulltitle[]" required /></td>
<td><textarea name="description[]" style="width: 100%; height: 100px;"><?php echo $plan->description; ?></textarea></td>
<td><?php echo $plan->package; ?></td>
<td><input type="text" value="<?php echo $plan->wash_time; ?>" name="wash_time[]" required /></td>
<td><input type="text" value="<?php echo $plan->price; ?>" name="price[]" required /></td>
<td><?php echo $plan->vehicle_type; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php if($jsondata_permission->users_type == 'admin'): ?>
<input type="submit" value="Save" name="addons_pricing_submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px; width: 135px;" />
<?php endif; ?>
</form>
<?php endif; ?>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>

                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- Address INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_5">


                                                            <div class="clear" style="height: 10px;">&nbsp;</div>


                                                    </div>
                                                    <!-- END Address INFO TAB -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
<?php include('footer.php') ?>
<?php if($jsondata_permission->users_type == 'scheduler'): ?>
<script>
   $(function(){
      $("#pricing_form input, #pricing_form textarea").attr('disabled', 'disabled');
   })
</script>
<?php endif; ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/profile.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>

