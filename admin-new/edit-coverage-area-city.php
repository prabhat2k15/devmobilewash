<?php
include('header.php');

if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);

?>
<?php
if(isset($_POST['city-submit'])){
$vehdata = array("id" => $_GET['id'], "city"=>$_POST['city'], "state"=>$_POST['state'], "citypage_url"=>$_POST['citypage_url'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/editcoverageareacity");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$cityupdatedata = json_decode($result);

}

$vehdata = array("id" => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/getcoverageareacitybyid");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$citydata = json_decode($result);
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Add Coverage Area City</span>
                                                </div>

                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                   <?php if(isset($_POST['city-submit']) && ($cityupdatedata->result == 'false')): ?>
                                                       <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><?php echo $cityupdatedata->response; ?></p>
                                                     <?php endif; ?>
						     <?php if(isset($_POST['city-submit']) && ($cityupdatedata->result == 'true')): ?>
                                                       <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;">Update successful</p>
                                                     <?php endif; ?>
                                                    <form action="" id="add-city" method="post">
                                                     <div class="form-group">
                                                                <label class="control-label">City</label>
                                                              <input type="text" name="city" id="city" value="<?php echo $citydata->coverage_city[0]->city; ?>" style="width: 400px;" class="form-control" required />
                                                            </div>
                                                     <div class="form-group">
                                                                <label class="control-label">State</label>
                                                              <input type="text" name="state" id="state" style="width: 400px;" value="<?php echo $citydata->coverage_city[0]->state; ?>" class="form-control" />
                                                            </div>
                                                     <div class="form-group">
                                                                <label class="control-label">City Page Url</label>
                                                              <input type="text" name="citypage_url" id="citypage_url" style="width: 400px;" value="<?php echo $citydata->coverage_city[0]->citypage_url; ?>" class="form-control" />
                                                            </div>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" id="regular-submit" value="Submit" name="city-submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                    </form>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>

                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->


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


