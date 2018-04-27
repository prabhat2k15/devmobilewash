<?php
include('header.php');
session_start();
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//echo print_r($_SESSION['access_token']);
  //$client->setAccessToken($_SESSION['access_token']);
 
        
} else {
  $redirect_uri = ROOT_URL . '/admin-new/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}



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
if(isset($_POST['zipcode-submit'])){
$vehdata = array("zipcode"=>$_POST['zipcode'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/addcoveragezipcode");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$zipdata = json_decode($result);

if($zipdata->result == 'true'){

$row_ids = array();

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->addScope('http://www.googleapis.com/auth/fusiontables');
//print_r($client);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//echo print_r($_SESSION['access_token']);
  $client->setAccessToken($_SESSION['access_token']);
  $tableId = '1Ck5Bulp_3881RFZqDRNb5yl-HgHnwA-p9Vv2JB-k';
        $ft = new Google_Service_Fusiontables($client);

 $result = $ft->query->sql("SELECT ROWID, ZIPCODE FROM $tableId");
//print_r($result->rows);
foreach($result->rows as $rr){
if($rr[1] == $_POST['zipcode']){
array_push($row_ids, $rr[0]); 
}

}

    foreach($row_ids as $rid) $result = $ft->query->sql("UPDATE $tableId SET MW_COVERAGE_AREA = 'true' WHERE ROWID = '$rid'");
            //print_r($result);
}

 echo "<script type='text/javascript'>window.location = '".ROOT_URL."/admin-new/coverage-area-zipcodes.php?action=add-zipcode-success'</script>";
}
}
?>

<?php
    if($company_module_permission == 'no' || $checked_vehicles_packages == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
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
       <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>

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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Add Coverage Area Zipcode</span>
                                                </div>

                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                   <?php if(isset($_POST['zipcode-submit']) && $zipdata->result == 'false'): ?>
                                                       <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><?php echo $zipdata->response; ?></p>
                                                     <?php endif; ?>
                                                    <form action="" id="add-zipcode" method="post">
                                                     <div class="form-group">
                                                                <label class="control-label">Zipcode</label>
                                                              <input type="text" name="zipcode" id="zipcode" style="width: 250px;" class="form-control" required />
                                                            </div>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" id="regular-submit" value="Submit" name="zipcode-submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
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


