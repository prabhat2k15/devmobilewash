<?php
session_start();
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';
include('header.php');

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//echo print_r($_SESSION['access_token']);
  $client->setAccessToken($_SESSION['access_token']);
 
        
} else {
  $redirect_uri = ROOT_URL. '/admin-new/oauth2callback.php?redirectpage=zipcode-pricing';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

?>
<?php
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

if(isset($_POST['pricing_submit'])){
        $userdata = array("id"=>1, 'zip' => $_POST['zipcodes'], 'express_price' => $_POST['exp_price'], 'deluxe_price' => $_POST['del_price'], 'premium_price' => $_POST['prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/updatezipprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
if($_POST['zipcodes']) $all_zips = explode(",", $_POST['zipcodes']);
$all_zips_formatted = array();
foreach($all_zips as $zip) array_push($all_zips_formatted, trim($zip));
$row_ids = array();
$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->addScope('https://www.googleapis.com/auth/fusiontables');
  $client->setAccessToken($_SESSION['access_token']);
$tableId = '1iuPfrdpW4w8IT-v47IY3TMuKfAE25w6OCe0-6Jsc';
        $ft = new Google_Service_Fusiontables($client);

 $result = $ft->query->sql("SELECT ROWID, ZIPCODE FROM $tableId");
//print_r($result->rows);
if(count($all_zips_formatted)){
foreach($result->rows as $rr){
if (in_array($rr[1], $all_zips_formatted)) {
array_push($row_ids, $rr[0]); 
}

}
}

$ft->query->sql("UPDATE $tableId SET SPECIAL_PRICE_APPLIED = '' WHERE SPECIAL_PRICE_APPLIED = 'true'");

if(count($row_ids)) foreach($row_ids as $rid) $ft->query->sql("UPDATE $tableId SET SPECIAL_PRICE_APPLIED = 'true' WHERE ROWID = '$rid'");

      
}

    }
    

 $userdata = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/getzipprices");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$pricedata = json_decode($result);


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

#pricing_form table td{
    vertical-align: middle;
}

#pricing_form select{
    padding: 5px;
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Zipcode Pricing</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">


<form action="" method="post" id="pricing_form">
<table class="table table-striped table-bordered order-column no-footer" style="width: 450px;">
<tr>
    <th>Zipcodes</th>
    <th>Express</th>
    <th>Deluxe</th>
    <th>Premium</th>
</tr>
<tr>
    <td><input name="zipcodes" style="width: 400px;" type="text" value="<?php echo $pricedata->zipcode_prices[0]->zipcodes; ?>" /></td>
    <td style="min-width: 105px;">
        <input name="exp_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->express; ?>" style="width: 70px;" />%
       
    </td>
    <td style="min-width: 105px;">
        <input name="del_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->deluxe; ?>" style="width: 70px;" />%
       
    </td>
    <td style="min-width: 105px;">
        <input name="prem_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->premium; ?>" style="width: 70px;" />%
    </td>
</tr>

</table>
<input type="submit" value="Save" name="pricing_submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px; width: 135px;" />
</form>


                                                            
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
