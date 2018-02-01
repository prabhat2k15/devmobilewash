<?php
require_once('../api/protected/vendors/braintree/lib/Braintree.php');
/*
Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('czckz7jkzcnny4jj');
Braintree_Configuration::publicKey('zwcjr8h49b5j5s96');
Braintree_Configuration::privateKey('1d9f980b86df0a4d0e0ce3253970a8ee');
*/

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

$clientToken = Braintree_ClientToken::generate();

include('header.php');

if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}

$phone_order_err = '';
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

$rootpath = '/home/mobilewa/public_html/admin-new/phone-orders-img/';
if(!is_dir($rootpath.$_GET['id'])){
mkdir($rootpath.$_GET['id'], 0777, true);
}

if($_GET['car-remove']){
unlink($rootpath.$_GET['id']."/".$_GET['car-remove']);

 echo "<script type='text/javascript'>window.location = 'edit-phone-order.php?id=".$_GET['id']."';</script>";
exit;
}



$handle = curl_init(ROOT_URL."/api/index.php?r=vehicles/vehiclemakes");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

$vehicle_makes = $jsondata->vehicle_makes;

$handle = curl_init(ROOT_URL."/api/index.php?r=vehicles/vehiclemakesclassic");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

$classic_makes = $jsondata->vehicle_makes;


 $handle = curl_init(ROOT_URL."/api/index.php?r=agents/allagents");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

$allagents = $jsondata->agents;


if(isset($_POST['edit-order-submit'])){

$total = 0;
$company_total = 0;
$agent_total = 0;
$total_vehicles = 0;
$bundle_fee = 1;

$total_vehicles = count($_POST['regular-make']) + count($_POST['classic-make']);



if(isset($_FILES['car-photos']['tmp_name']))
            {
//print_r($_FILES);
 $num_files = count($_FILES['car-photos']['tmp_name']);
 for($i=0; $i < $num_files;$i++){
//print_r($file);
//echo $_FILES['car-photos']['tmp_name'][$i];

$profile_pic = $_FILES['car-photos']['tmp_name'][$i];
                $profile_pic_type = pathinfo($_FILES['car-photos']['name'][$i], PATHINFO_EXTENSION);
                 $md5 = md5(uniqid(rand(), true));
                $picname = $_GET['id']."_".$md5.".".$profile_pic_type;
                move_uploaded_file($profile_pic, $rootpath.$_GET['id']."/".$picname);
                //$profileimg = $rootpath.$_GET['id']."/".$picname;
}

            }


$regular_vehicles = '';
$classic_vehicles = '';


if(count($_POST['regular-make'])){
foreach($_POST['regular-make'] as $ind=>$rmake){

$regular_vehicles .= $rmake.",".$_POST['regular-model'][$ind].",".$_POST['regular-pack'][$ind].",".$_POST['regular-disc'][$ind].",".$_POST['regular_pet_fees'][$ind].",".$_POST['regular_truck_fees'][$ind].",".$_POST['regular_exthandwaxes'][$ind].",".$_POST['regular_extplasticdressings'][$ind].",".$_POST['regular_extclaybars'][$ind].",".$_POST['regular_waterspotremoves'][$ind]."|";
//print_r($_FILES['regular-car-img']['tmp_name'][$ind]);

$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/plans");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('vehicle_make' => $rmake, 'vehicle_model' => $_POST['regular-model'][$ind], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_plan = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_plan = json_decode($result_plan);

if($_POST['regular-pack'][$ind] == 'Deluxe') {
$total += $jsondata_plan->plans->deluxe[0]->price;
$total += $jsondata_plan->plans->deluxe[0]->handling_fee;
$agent_total += $jsondata_plan->plans->deluxe[0]->price * .80;
$company_total += $jsondata_plan->plans->deluxe[0]->price * .20;
$company_total += $jsondata_plan->plans->premium[0]->handling_fee;
}

if($_POST['regular-pack'][$ind] == 'Premium') {
$total += $jsondata_plan->plans->premium[0]->price;
$total += $jsondata_plan->plans->premium[0]->handling_fee;
$agent_total += $jsondata_plan->plans->premium[0]->price * .75;
$company_total += $jsondata_plan->plans->premium[0]->price * .25;
$company_total += $jsondata_plan->plans->premium[0]->handling_fee;
}

if($_POST['regular-disc'][$ind]) {
$total -= $_POST['regular-disc'][$ind];
$company_total -= $_POST['regular-disc'][$ind];
}

$total += $_POST['regular_pet_fees'][$ind];
$total += $_POST['regular_truck_fees'][$ind];
$total += $_POST['regular_exthandwaxes'][$ind];
$total += $_POST['regular_extplasticdressings'][$ind];
$total += $_POST['regular_extclaybars'][$ind];
$total += $_POST['regular_waterspotremoves'][$ind];
if($total_vehicles > 1) $total -= $bundle_fee;



   $agent_total += $_POST['regular_extclaybars'][$ind] * .80;
$agent_total += $_POST['regular_waterspotremoves'][$ind] * .80;


$agent_total += $_POST['regular_pet_fees'][$ind] * .80;
$agent_total += $_POST['regular_truck_fees'][$ind] * .80;
$agent_total += $_POST['regular_exthandwaxes'][$ind] * .80;
$agent_total += $_POST['regular_extplasticdressings'][$ind] * .80;

if($total_vehicles > 1) $agent_total -= $bundle_fee * .80;


   $company_total += $_POST['regular_extclaybars'][$ind] * .20;
$company_total += $_POST['regular_waterspotremoves'][$ind] * .20;


$company_total += $_POST['regular_exthandwaxes'][$ind] * .20;
$company_total += $_POST['regular_extplasticdressings'][$ind] * .20;
$company_total += $_POST['regular_pet_fees'][$ind] * .20;
$company_total += $_POST['regular_truck_fees'][$ind] * .20;

if($total_vehicles > 1) $company_total -= $bundle_fee * .20;


 if(!empty($_FILES['regular-car-img']['tmp_name'][$ind]))
            {

                $profile_pic = $_FILES['regular-car-img']['tmp_name'][$ind];
                $profile_pic_type = pathinfo($_FILES['regular-car-img']['name'][$ind], PATHINFO_EXTENSION);
                 $md5 = md5(uniqid(rand(), true));
                $picname = $rmake."_".$_POST['regular-model'][$ind]."_".$md5.".".$profile_pic_type;
                move_uploaded_file($profile_pic, $rootpath.$_GET['id']."/".$picname);
                //$profileimg = $rootpath.$_GET['id']."/".$picname;
            }
}
}

if(count($_POST['classic-make'])){
foreach($_POST['classic-make'] as $ind=>$cmake){

$classic_vehicles .= $cmake.",".$_POST['classic-model'][$ind].",".$_POST['classic-pack'][$ind].",".$_POST['classic-disc'][$ind].",".$_POST['classic_pet_fees'][$ind].",".$_POST['classic_truck_fees'][$ind].",".$_POST['classic_exthandwaxes'][$ind].",".$_POST['classic_extplasticdressings'][$ind].",".$_POST['classic_extclaybars'][$ind].",".$_POST['classic_waterspotremoves'][$ind]."|";

$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/plans");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('vehicle_make' => $cmake, 'vehicle_model' => $_POST['classic-model'][$ind], 'vehicle_build' => 'classic', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_plan = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_plan = json_decode($result_plan);

if($_POST['classic-pack'][$ind] == 'Deluxe') {
$total += $jsondata_plan->plans->deluxe[0]->price;
$total += $jsondata_plan->plans->deluxe[0]->handling_fee;
$agent_total += $jsondata_plan->plans->deluxe[0]->price * .80;
$company_total += $jsondata_plan->plans->deluxe[0]->price * .20;
$company_total += $jsondata_plan->plans->premium[0]->handling_fee;
}

if($_POST['classic-pack'][$ind] == 'Premium') {
$total += $jsondata_plan->plans->premium[0]->price;
$total += $jsondata_plan->plans->premium[0]->handling_fee;
$agent_total += $jsondata_plan->plans->premium[0]->price * .75;
$company_total += $jsondata_plan->plans->premium[0]->price * .25;
$company_total += $jsondata_plan->plans->premium[0]->handling_fee;
}

if($_POST['classic-disc'][$ind]) {
$total -= $_POST['classic-disc'][$ind];
$company_total -= $_POST['classic-disc'][$ind];
}

$total += $_POST['classic_exthandwaxes'][$ind];
$total += $_POST['classic_extplasticdressings'][$ind];
$total += $_POST['classic_extclaybars'][$ind];
$total += $_POST['classic_waterspotremoves'][$ind];
$total += $_POST['classic_pet_fees'][$ind];
$total += $_POST['classic_truck_fees'][$ind];

if($total_vehicles > 1) $total -= $bundle_fee;


   $agent_total += $_POST['classic_extclaybars'][$ind] * .80;
$agent_total += $_POST['classic_waterspotremoves'][$ind] * .80;


$agent_total += $_POST['classic_pet_fees'][$ind] * .80;
$agent_total += $_POST['classic_truck_fees'][$ind] * .80;
$agent_total += $_POST['classic_exthandwaxes'][$ind] * .80;
$agent_total += $_POST['classic_extplasticdressings'][$ind] * .80;

if($total_vehicles > 1) $agent_total -= $bundle_fee * .80;


   $company_total += $_POST['classic_extclaybars'][$ind] * .20;
$company_total += $_POST['classic_waterspotremoves'][$ind] * .20;


$company_total += $_POST['classic_pet_fees'][$ind] * .20;
$company_total += $_POST['classic_truck_fees'][$ind] * .20;
$company_total += $_POST['classic_exthandwaxes'][$ind] * .20;
$company_total += $_POST['classic_extplasticdressings'][$ind] * .20;

if($total_vehicles > 1) $company_total -= $bundle_fee * .20;


 if(!empty($_FILES['classic-car-img']['tmp_name'][$ind]))
            {

                $profile_pic = $_FILES['classic-car-img']['tmp_name'][$ind];
                $profile_pic_type = pathinfo($_FILES['classic-car-img']['name'][$ind], PATHINFO_EXTENSION);
                 $md5 = md5(uniqid(rand(), true));
                $picname = $cmake."_".$_POST['classic-model'][$ind]."_".$md5.".".$profile_pic_type;
                move_uploaded_file($profile_pic, $rootpath.$_GET['id']."/".$picname);
                //$profileimg = $rootpath.$_GET['id']."/".$picname;
            }

}
}

$regular_vehicles = trim($regular_vehicles,"|");
$classic_vehicles = trim($classic_vehicles,"|");

$checklist = '';

if(isset($_POST['checklist'])){
foreach($_POST['checklist'] as $ck)$checklist .= $ck."|";
}

$checklist = trim($checklist,"|");

$property_allowed = 'no';
$payment_processed = 'no';

if($_POST['property_allowed']) $property_allowed = $_POST['property_allowed'];
if($_POST['process_payment']) $payment_processed = $_POST['process_payment'];

$data = array("id" => $_GET['id'], "customername"=>$_POST['cname'], "phoneno"=>$_POST['cphone'], "address"=>$_POST['caddress'], "address_type" => $_POST['address_type'], "city"=>$_POST['ccity'], "schedule_date"=>$_POST['sdate'], "schedule_time"=>$_POST['stime'], "email"=>$_POST['cemail'], "how_hear_mw"=>$_POST['how-hear'], "regular_vehicles"=>$regular_vehicles, "classic_vehicles"=>$classic_vehicles, "notes"=>$_POST['notes'], "checklist" => $checklist, "total_price" => number_format($total, 2), "agent_total" => number_format($agent_total, 2), "company_total" => number_format($company_total, 2), 'agent_id' => $_POST['detailer'], 'is_property_allowed' => $property_allowed, 'is_payment_processed' => $payment_processed, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=PhoneOrders/editorder");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$add_jsondata = json_decode($result);
if($add_jsondata->result == 'true'){
// echo "<script type='text/javascript'>window.location = 'phone-orders.php?action=edit-success'</script>";
//exit;
}
}

$handle = curl_init(ROOT_URL."/api/index.php?r=PhoneOrders/getorderbyid");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
           $getorder_response = $jsondata->response;
$getorder_result_code = $jsondata->result;
$getorder = $jsondata->order_details;

$handle_data = curl_init(ROOT_URL."/api/index.php?r=agents/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('agent_id' => $getorder->agent_id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$agentdetails = json_decode($result);

if(isset($_POST['payment_method_nonce'])){

   //$amount = 0.01;
//$getorder->total_price;
$company_total = $getorder->company_total;
$agent_total = $getorder->agent_total;

if($_POST['order_discount']) {
$net_total_price = $getorder->total_price - $_POST['order_discount'];
$company_total -= $_POST['order_discount'];
}
else $net_total_price = $getorder->total_price;
if($_POST['tip_amount']) {
$net_total_price += $_POST['tip_amount'];
$agent_total += $_POST['tip_amount'] * .8;
$company_total += $_POST['tip_amount'] * .2;
}
//echo $_POST['payment_method_nonce'];

$request_data = ['merchantAccountId' => $agentdetails->bt_submerchant_id, 'serviceFeeAmount' => $company_total, 'amount' => $net_total_price, 'paymentMethodNonce' => $_POST['payment_method_nonce'], 'options' => ['submitForSettlement' => True], 'customer' => ['firstName' => $getorder->customername],'billing' => ['firstName' => $getorder->customername]];
$result = Braintree_Transaction::sale($request_data);
//print_r($result->transaction);
//echo $result->paymentInstrumentType;
if ($result->success) {

$data = array("id" => $_GET['id'], "payment_status" => 'complete', "transaction_id" => $result->transaction->id, 'order_discount' => $_POST['order_discount'], 'tip_amount' => $_POST['tip_amount'], 'total_price' => $net_total_price, 'company_total' => number_format($company_total, 2), 'agent_total' => number_format($agent_total,2), 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=PhoneOrders/editorder");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);

echo "<script type='text/javascript'>window.location = 'edit-phone-order.php?id=".$_GET['id']."';</script>";
}
else{

foreach($result->errors->deepAll() AS $error) {
  $phone_order_err .= $error->message . "<br>";
}

}
}
?>


<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.bxslider.css" rel="stylesheet" />
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

#phone-order-form label{
margin-top: 15px;
}

.car-gallery{
list-style: none;
margin: 0;
padding: 0;
}

.car-gallery li{
display: block;
float: left;
margin-right: 20px;
margin-bottom: 20px;
}

.car-gallery li img{
border: 1px solid #ccc;
max-width: 200px;
padding: 5px;
}

.checklist{
list-style: none;
margin: 0;
padding: 0;
border-bottom: 1px solid red;
}

.checklist > li{
color: red;
padding: 10px;
border: 1px solid red;
background: #ffdbdb;
font-weight: 500;
border-bottom: 0;
}

.checklist > li.checked{
color: green;
border: 1px solid green;
background: #dcf5db;
border-bottom: 0;
}

.checklist > li:last-child.checked{
border: 1px solid green;
}

.checklist ul{
list-style: none;
}

.checklist ul li{

}

.bxslider li img{
width: 100%;
}

.bx-controls-direction a {
    opacity: 0;
    transition: opacity .25s ease-in-out;
    -moz-transition: opacity .25s ease-in-out;
    -webkit-transition: opacity .25s ease-in-out;
}

.bx-wrapper:hover .bx-controls-direction a {
    opacity: 1;
}

.bx-wrapper .bx-pager{
padding-top: 0;
bottom: -5px;
}

.popup-overlay{
    background: rgba(0, 0, 0, .9);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 22222;
display: none;
}


.popup-overlay .popup-wrap{
    width: 500px;
    height: 356px;
    background: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -250px;
    margin-top: -178px;
padding: 20px;
box-sizing: border-box;
}

.popup-overlay .popup-wrap .pop-close{
display: block;
    position: absolute;
    top: 5px;
    right: 10px;
    font-size: 20px;
    text-decoration: none;
    font-family: arial;
}

.prem_disc_el{
display: none;
}

.extclaybar, .waterspotremove{
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">CALL-IN ORDER #0000<?php echo $_GET['id']; ?></span>

                                                </div>
<div style="float: right; font-size: 18px; margin-top: 3px; background: #006fcf; color: #fff; padding: 5px 10px; margin-bottom: 10px;"><span style="display: block; float: left; margin-top: 4px; margin-right: 5px;">TOTAL PRICE:</span><span style="font-weight: 500;font-size: 22px; display: block; float: left; margin-bottom: 5px;" class="order_total">$<?php echo $getorder->total_price; ?><?php if($getorder->order_discount): ?> <span style="font-size: 18px;">(Discount: <?php echo "$".number_format($getorder->order_discount, 2); ?>) </span><?php endif; ?><?php if($getorder->tip_amount): ?> <span style="font-size: 18px;">(Tip: <?php echo "$".number_format($getorder->tip_amount, 2); ?>)</span><?php endif; ?></span><div style="clear: both;"></div>
<span style="font-weight: 500;font-size: 16px; display: block; clear: both; text-align: right;">Agent Total: <?php if($getorder->agent_total) {echo "$".$getorder->agent_total;} else {echo "N/A";} ?></span>
<span style="font-weight: 500;font-size: 16px; display: block; clear: both; text-align: right;">Company Total: <?php if($getorder->company_total) {echo "$".$getorder->company_total;} else {echo "N/A";} ?></span>

</div>

<?php if($getorder->payment_status == 'complete'): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; background: #05b500; color: #fff; padding: 8px 35px; margin-right: 20px;">Payment Complete</div>
<?php else: ?>
<div class="process-payment-trigger" style="float: right; font-size: 18px; margin-top: 3px; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; cursor: pointer;">Process Payment</div>
<?php endif; ?>
<div style="clear: both;"></div>
<?php if($phone_order_err): ?>
<p style="text-align: left; clear: both; margin-top: 0; background: #d40000; color: #fff; padding: 10px;"><?php echo $phone_order_err; ?></p>
<?php endif; ?>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                   <?php if(isset($_POST['zipcode-submit']) && $zipdata->result == 'false'): ?>
                                                       <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><?php echo $zipdata->response; ?></p>
                                                     <?php endif; ?>
                                                    <form action="" id="phone-order-form" method="post" enctype="multipart/form-data">
                                                    <div class="col-md-8" style="padding-left: 0; padding-right: 0;">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" style="margin-top: 0;">Customer Name<span style="color: red;">*</span></label>
                                                            <input type="text" name="cname" id="cname" style="width: 300px;" class="form-control" value="<?php echo $getorder->customername; ?>" required />
                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label" style="margin-top: 0;">Schedule Date<span style="color: red;">*</span></label>
                                                            <input type="text" name="sdate" id="sdate" style="width: 300px;" class="form-control date-picker" value="<?php echo $getorder->schedule_date; ?>" required />
                                                        </div>
                                                     </div>


                                                            <div style="clear: both;"></div>
<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Phone Number<span style="color: red;">*</span></label>
                                                            <input type="text" name="cphone" id="cphone" style="width: 300px;" class="form-control" value="<?php echo $getorder->phoneno; ?>" required />
                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label">Schedule Time<span style="color: red;">*</span></label>
                                                            <input type="text" name="stime" id="stime" style="width: 300px;" class="form-control timepicker timepicker-default" value="<?php echo $getorder->schedule_time; ?>" required />
                                                        </div>
                                                     </div>

                                                            <div style="clear: both;"></div>
<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address<span style="color: red;">*</span></label>
                                                            <input type="text" name="caddress" id="caddress" style="width: 300px;" class="form-control" value="<?php echo $getorder->address; ?>" required />
                                                        </div>
                                                     </div>

<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address Type<span style="color: red;">*</span></label>
<select name="address_type" id="address_type" style="width: 300px;" class="form-control">
<option value="Home" <?php if($getorder->address_type == 'Home') echo "selected"; ?>>Home</option>
<option value="Work" <?php if($getorder->address_type == 'Work') echo "selected"; ?>>Work</option>
</select>
                                                        </div>
                                                     </div>
 <div style="clear: both;"></div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Email Address (Optional)</label>
                                                     <input type="text" name="cemail" id="cemail" style="width: 300px;" class="form-control" value="<?php echo $getorder->email; ?>" />

                                                        </div>
                                                     </div>


<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">City<span style="color: red;">*</span></label>
                                                            <input type="text" name="ccity" id="ccity" style="width: 300px;" class="form-control" value="<?php echo $getorder->city; ?>" required/>
                                                        </div>
                                                     </div>
 <div style="clear: both;"></div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label">How did you hear about us? (Optional)</label>

<input type="text" name="how-hear" id="how-hear" value="<?php echo $getorder->how_hear_mw; ?>" style="width: 300px;" class="form-control" />
                                                        </div>
                                                     </div>

                                                            <div style="clear: both;"></div>
                                                             <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="regular-add-trigger" href="#">+ ADD NEW VEHICLE</a></p>
                                                             <div class="regular-vehicles-wrap">
<?php if($getorder->regular_vehicles): ?>
<?php $reg_vehicles = explode("|",$getorder->regular_vehicles);
foreach($reg_vehicles as $ind=>$veh): ?>
<?php $veh_detail = explode(",",$veh); ?>
<div class='regular-car-box' id='regular-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>

<label class='control-label'>Make</label>
<input type="text" name='regular-make[]' class='form-control regular-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[0]; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='regular-model[]' class='form-control regular-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[1]; ?>" />

<label class='control-label'>Package</label>
<input type="text" name='regular-pack[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[2]; ?>" />

<label class='control-label'>Price</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo $getorder->regular_prices[$ind]; ?>" />

<label class='control-label'>Handling Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$1.00" />

<?php if($veh_detail[4]): ?>
  <label class='control-label'>Pet Hair Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[4], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_pet_fees[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[4]; ?>" />

<?php if($veh_detail[5]): ?>
  <label class='control-label'>Lifted Truck Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[5], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_truck_fees[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[5]; ?>" />

<?php if($veh_detail[6]): ?>
  <label class='control-label'>Full Exterior Hand Wax (Liquid form)</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[6], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_exthandwaxes[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[6]; ?>" />


<?php if($veh_detail[7]): ?>
  <label class='control-label'>Dressing of all Exterior Plastics</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[7], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_extplasticdressings[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[7]; ?>" />

<?php if($veh_detail[8]): ?>
  <label class='control-label'>Full Exterior Clay Bar</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[8], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_extclaybars[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[8]; ?>" />


<?php if($veh_detail[9]): ?>
  <label class='control-label'>Water Spot Removal</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[9], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='regular_waterspotremoves[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[9]; ?>" />

<?php if($getorder->total_vehicles > 1): ?>
<label class='control-label'>Bundle Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$1.00" />
<?php endif; ?>

<?php if($veh_detail[3] == 5): ?>
<label class='control-label'>Deluxe Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$5.00" />
<?php endif; ?>

<?php if($veh_detail[3] == 10): ?>
<label class='control-label'>Premium Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$10.00" />
<?php endif; ?>

<input type="hidden" name="regular-disc[]" style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[3]; ?>" />


<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>
<?php endforeach; ?>
<?php endif; ?>

                                                             </div>

                                                             </div>
                                                              <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="classic-add-trigger" href="#">+ ADD NEW CLASSIC</a></p>
                                                             <div class="classic-vehicles-wrap">

<?php if($getorder->classic_vehicles): ?>
<?php $cla_vehicles = explode("|",$getorder->classic_vehicles);
foreach($cla_vehicles as $ind=>$veh): ?>
<?php $veh_detail = explode(",",$veh); ?>
<div class='classic-car-box' id='classic-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>
<label class='control-label'>Make</label>
<input type="text" name='classic-make[]' class='form-control classic-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[0]; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='classic-model[]' class='form-control classic-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[1]; ?>" />

<label class='control-label'>Package</label>
<input type="text" name='classic-pack[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[2]; ?>" />

<label class='control-label'>Price</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo $getorder->classic_prices[$ind]; ?>" />

<label class='control-label'>Handling Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$1.00" />

<?php if($veh_detail[4]): ?>
  <label class='control-label'>Pet Hair Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[4], 2); ?>" />

<?php endif; ?>
<input type="hidden" name='classic_pet_fees[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[4]; ?>" />

<?php if($veh_detail[5]): ?>
  <label class='control-label'>Lifted Truck Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[5], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='classic_truck_fees[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[5]; ?>" />

<?php if($veh_detail[6]): ?>
  <label class='control-label'>Full Exterior Hand Wax (Liquid form)</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[6], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='classic_exthandwaxes[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[6]; ?>" />


<?php if($veh_detail[7]): ?>
  <label class='control-label'>Dressing of all Exterior Plastics</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[7], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='classic_extplasticdressings[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[7]; ?>" />

<?php if($veh_detail[8]): ?>
  <label class='control-label'>Full Exterior Clay Bar</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[8], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='classic_extclaybars[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[8]; ?>" />


<?php if($veh_detail[9]): ?>
  <label class='control-label'>Water Spot Removal</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[9], 2); ?>" />
<?php endif; ?>
<input type="hidden" name='classic_waterspotremoves[]' class='form-control' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[9]; ?>" />

<?php if($getorder->total_vehicles > 1): ?>
<label class='control-label'>Bundle Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$1.00" />
<?php endif; ?>

<?php if($veh_detail[3] == 5): ?>
<label class='control-label'>Deluxe Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$5.00" />
<?php endif; ?>

<?php if($veh_detail[3] == 10): ?>
<label class='control-label'>Premium Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$10.00" />
<?php endif; ?>

<input type="hidden" name="classic-disc[]" style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[3]; ?>" />

<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p>

</div>
<?php endforeach; ?>
<?php endif; ?>

                                                             </div>

                                                             </div>
                                                             <div style="clear: both;"></div>

                                                            </div>
                                                            <div class="col-md-4" style="padding-right: 0; border-left: 1px solid #ccc; padding-left: 28px;">

                                                        <div class="form-group">
                                                             <label class="control-label" style="margin-top: 0;">Assigned Detailer</label>
                                                             <select name="detailer" id="detailer" style="width: 300px; font-size: 18px; font-weight: 500; padding: 5px;">
                                                             <option value="none">-- Select Detailer --</option>
                                                             <?php if(count($allagents)): foreach($allagents as $agent): ?>
<?php if($agent->washer_position == 'real'): ?>
                                                             <option value="<?php echo $agent->id; ?>" <?php if($getorder->agent_id == $agent->id) echo 'selected'; ?>><?php echo "#".$agent->real_washer_id." - ".$agent->name; ?><?php if(!$agent->bt_submerchant_id) echo "(no bt id)"; ?></option>
<?php endif; ?>
                                                             <?php endforeach; endif; ?>
                                                             </select>
                                                        </div>

                                                        <div class="form-group">
                                                             <label class="control-label">Notes</label>
                                                           <textarea name="notes" id="notes" style="width: 313px; height: 277px;" class="form-control"><?php echo $getorder->notes; ?></textarea>
                                                        </div>

<h3 style="font-weight: 500; font-size: 18px; color: #006fcf; border-bottom: 1px solid #ccc; padding-bottom: 15px; margin-top: 30px; margin-bottom: 20px;">Checklist</h3>
<?php
$checklist_arr = array();
if($getorder->checklist){
$checklist_arr = explode("|", $getorder->checklist);
}
?>
<ul class="checklist">
<li <?php if ($getorder->is_property_allowed == 'yes') echo "class='checked'";?>><input type="checkbox" name="property_allowed" class="checklist-checker" value="yes" <?php if ($getorder->is_property_allowed == 'yes') echo "checked"; ?> /> Are we allowed to wash on the property?
</li>
<li <?php if ($getorder->is_payment_processed == 'yes') echo "class='checked'";?>><input type="checkbox" name="process_payment" class="checklist-checker" value="yes" <?php if ($getorder->is_payment_processed == 'yes') echo "checked"; ?> /> Process Client Payment
</li>
<li <?php if (in_array("detailer_assign", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="detailer_assign" <?php if (in_array("detailer_assign", $checklist_arr)) echo "checked"; ?> /> Assign Detailer</li>
<li <?php if (in_array("confirm_schedule", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="confirm_schedule" <?php if (in_array("confirm_schedule", $checklist_arr)) echo "checked"; ?> /> Confirm Schedule with Client</li>
<li <?php if (in_array("text_detailer", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="text_detailer" <?php if (in_array("text_detailer", $checklist_arr)) echo "checked"; ?> /> Text Detailer Client Info:
     <ul>
<li>- Client Name & Address (No Number)</li>
     <li>- Package Details & Price</li>
     <li>- Washer Do's & Don'ts</li>
</ul>
</li>
<li <?php if (in_array("text_client", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="text_client" <?php if (in_array("text_client", $checklist_arr)) echo "checked"; ?> /> Text Client Info:
     <ul>
<li>- Package Details & Price</li>
     <li>- Client Terms</li>
</ul>
</li>
<li <?php if (in_array("confirm_arrival", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="confirm_arrival" <?php if (in_array("confirm_arrival", $checklist_arr)) echo "checked"; ?> /> Confirm Arrival w/ Detailer<br><span style="margin-left: 30px;">(15 minutes before arrival)</span></li>
<li <?php if (in_array("confirm_wash_complete", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="confirm_wash_complete" <?php if (in_array("confirm_wash_complete", $checklist_arr)) echo "checked"; ?> /> Confirm Wash Completed w/ Detailer and ask for Photos</li>
<li <?php if (in_array("client_feedback", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="client_feedback" <?php if (in_array("client_feedback", $checklist_arr)) echo "checked"; ?> /> Client Feedback:
    <ul><li>- Were you satisfied with the wash?</li>
    <li>- if YES ask for Email & YELP Review</li>
    <li>- if NO get full issue details & ask how we can improve our service</li>
</ul>
</li>
<li <?php if (in_array("detailer_feedback", $checklist_arr)) echo "class='checked'";?>><input type="checkbox" name="checklist[]" class="checklist-checker" value="detailer_feedback" <?php if (in_array("detailer_feedback", $checklist_arr)) echo "checked"; ?> /> Detailer Feedback:
    <ul><li>- Take Note of any feedback</li></ul>
</li>
</ul>
<h3 style="font-weight: 500; font-size: 18px; color: #006fcf; border-bottom: 1px solid #ccc; padding-bottom: 15px; margin-top: 30px; margin-bottom: 8px;">Photos</h3>
<label class='control-label' style="margin-top: 8px;">Upload Image(s)</label>
<input type="file" name='car-photos[]' class='car-photos' multiple />

<?php $files = array();
foreach (glob($rootpath.$_GET['id']."/*") as $file) {
  $files[] = basename($file);

}
//print_r($files);
?>
<?php if(count($files)): ?>

<ul class="bxslider">
<?php foreach($files as $img): ?>
  <li>
<p style="text-align: right; margin: 0;"><a href="/admin-new/edit-phone-order.php?id=<?php echo $_GET['id']; ?>&car-remove=<?php echo $img; ?>">X</a></p>
<a href="<?php echo ROOT_URL; ?>/admin-new/phone-orders-img/<?php echo $_GET['id']; ?>/<?php echo $img; ?>" target="_blank"><img src="<?php echo ROOT_URL; ?>/admin-new/phone-orders-img/<?php echo $_GET['id']; ?>/<?php echo $img; ?>" /></a>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


                                                            </div>
                                                             <div style="clear: both;"></div>
                                                               <input type="submit" id="edit-order-submit" value="Submit" name="edit-order-submit" style="color: rgb(255, 255, 255); margin-top: 20px; background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
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
                <div class="popup-overlay">
                <div class="popup-wrap">
   <form id="checkout" method="post" action="" style="margin-top: 25px;">
<input type="hidden" name="first_name" value="<?php echo $getorder->customername; ?>" />
  <div id="payment-form"></div>
<input type="text" name="order_discount" id="order_discount" onkeypress="return isNumberKey(event)" class="form-control" placeholder="Order Discount (if any)" style="margin-top: 12px;" />
<input type="text" name="tip_amount" id="tip_amount" onkeypress="return isNumberKey(event)" class="form-control" placeholder="Tip Amount (if any)" style="margin-top: 12px;" />
  <input type="submit" value="Process" style="margin-top: 20px; display: block; border: 0; background: #076ee1; color: #fff; padding: 8px; cursor: pointer;" name="pay-process-submit" />
</form>
<a href="#" class="pop-close">X</a>
</div>
                </div>
            </div>
<?php include('footer.php') ?>

<script>
var regular_makes;
var regular_models;
var classic_makes;
var classic_models;
var regularindex = 0;
var classicindex = 0;
var total_price = "<?php echo $getorder->total_price; ?>";
$(function(){
$.getJSON("../api/index.php?r=vehicles/vehiclemakes", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {


		var vals = [];


				makes = data.vehicle_makes.join(",");
firstmake = '';
				vals = makes.split(",");
firstmake = vals[0];


		var $secondChoice = $(".regular-make");
		//$secondChoice.empty();
		$.each(vals, function(index, value) {
		    regular_makes += "<option value='"+value+"'>" + value + "</option>";
			//$secondChoice.append("<option value='"+value+"'>" + value + "</option>");
		});


$.getJSON("../api/index.php?r=vehicles/vehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {




			models = data.vehicles.makes[firstmake];





		var $secondChoice = $(".regular-model");
		//$secondChoice.empty();
		$.each(models, function(index, value) {
mod = value.split("|");
               regular_models += "<option value='"+mod[0]+"'>" + mod[0] + "</option>";
			//$secondChoice.append("<option value='"+mod[0]+"'>" + mod[0] + "</option>");
		});

	});



	});


    $.getJSON("../api/index.php?r=vehicles/vehiclemakesclassic", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {


		var vals = [];


				makes = data.vehicle_makes.join(",");
firstmake2 = '';
				vals = makes.split(",");
firstmake2 = vals[0];


		var $secondChoice2 = $(".classic-make");
		//$secondChoice2.empty();
		$.each(vals, function(index, value) {
		     classic_makes += "<option value='"+value+"'>" + value + "</option>";
			//$secondChoice2.append("<option value='"+value+"'>" + value + "</option>");
		});


$.getJSON("../api/index.php?r=vehicles/classicvehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {




			models = data.vehicles.makes[firstmake2];





		var $secondChoice2 = $(".classic-model");
		//$secondChoice2.empty();
		$.each(models, function(index, value) {
mod = value.split("|");
               classic_models += "<option value='"+mod[0]+"'>" + mod[0] + "</option>";
			//$secondChoice2.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});



	});



$( "#phone-order-form" ).on( "change", ".regular-make", function() {

var th = $(this);
	var $dropdown = $(this);

	$.getJSON("../api/index.php?r=vehicles/vehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {

		var key = $dropdown.val();
		var vals = [];


			models = data.vehicles.makes[key].join(",");
				vals = models.split(",");



       var parentid = $(th).parent().attr('id');
       //console.log(parentid);
		var $secondChoice = $("#"+parentid+" .regular-model");

		$secondChoice.empty();
		$.each(vals, function(index, value) {
mod = value.split("|");

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});
});


$( "#phone-order-form" ).on( "change", ".classic-make", function() {

var th = $(this);
	var $dropdown = $(this);

	$.getJSON("../api/index.php?r=vehicles/classicvehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {

		var key = $dropdown.val();
		var vals = [];


			models = data.vehicles.makes[key].join(",");
				vals = models.split(",");



       var parentid = $(th).parent().attr('id');
       //console.log(parentid);
		var $secondChoice = $("#"+parentid+" .classic-model");

		$secondChoice.empty();
		$.each(vals, function(index, value) {
mod = value.split("|");

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});
});

$(".regular-add-trigger").click(function(){
    var content = '';
regularindex++;
    content += "<div class='regular-car-box' id='regular-car-box-"+regularindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='regular-make[]' class='form-control regular-make' style='width: 300px;'>";
    content += regular_makes;
    content += "</select><label class='control-label'>Model</label><select name='regular-model[]' class='form-control regular-model' style='width: 300px;'>";
    content += regular_models;
    content += "</select><label class='control-label'>Package</label><select name='regular-pack[]' class='form-control regular-pack' style='width: 300px;'><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><p style='margin-top: 20px;'><input type='checkbox' id='regular_pet_fee' /> Pet Hair Fee ($5)<input type='hidden' name='regular_pet_fees[]' value='0'/></p><p><input type='checkbox' id='regular_truck_fee' /> Lifted Truck Fee ($5)<input type='hidden' name='regular_truck_fees[]' value='0'/></p><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Full Exterior Hand Wax (Liquid form)<input type='hidden' id='exthandwaxes' name='regular_exthandwaxes[]' value='0'></p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='8' /> $8 Dressing of all Exterior Plastics<input type='hidden' id='extplasticdressings' name='regular_extplasticdressings[]' value='0'></p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='35' /> $35 Full Exterior Clay Bar<input type='hidden' id='extclaybars' name='regular_extclaybars[]' value='0'></p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal<input type='hidden' id='waterspotremoves' name='regular_waterspotremoves[]' value='0'></p><p style='margin-top: 20px;' class='deluxe_disc_el'><input type='checkbox' id='deluxe_disc' value='5' /> $5 off on Deluxe</p><p class='prem_disc_el' style='margin-top: 20px;'><input type='checkbox' id='prem_disc' value='10' /> $10 off on Premium</p><input type='hidden' name='regular-disc[]' id='regular-disc' value='0' /><p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>";

    $(".regular-vehicles-wrap").append(content);

    return false;

});

$(".classic-add-trigger").click(function(){
    var content = '';
classicindex++;
    content += "<div class='classic-car-box' id='classic-car-box-"+classicindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='classic-make[]' class='form-control classic-make' style='width: 300px;'>";
    content += classic_makes;
    content += "</select><label class='control-label'>Model</label><select name='classic-model[]' class='form-control classic-model' style='width: 300px;'>";
    content += classic_models;
    content += "</select><label class='control-label'>Package</label><select name='classic-pack[]' class='form-control classic-pack' style='width: 300px;'><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><p style='margin-top: 20px;'><input type='checkbox' id='classic_pet_fee' /> Pet Hair Fee ($5)<input type='hidden' name='classic_pet_fees[]' value='0'/></p><p><input type='checkbox' id='classic_truck_fee' /> Lifted Truck Fee ($5)<input type='hidden' name='classic_truck_fees[]' value='0'/></p><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Full Exterior Hand Wax (Liquid form)<input type='hidden' id='exthandwaxes' name='classic_exthandwaxes[]' value='0'></p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='8' /> $8 Dressing of all Exterior Plastics<input type='hidden' id='extplasticdressings' name='classic_extplasticdressings[]' value='0'></p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='35' /> $35 Full Exterior Clay Bar<input type='hidden' id='extclaybars' name='classic_extclaybars[]' value='0'></p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal<input type='hidden' id='waterspotremoves' name='classic_waterspotremoves[]' value='0'></p><p style='margin-top: 20px;' class='deluxe_disc_el'><input type='checkbox' id='deluxe_disc' value='5' /> $5 off on Deluxe</p><p class='prem_disc_el' style='margin-top: 20px;'><input type='checkbox' id='prem_disc' value='10' /> $10 off on Premium</p><input type='hidden' name='classic-disc[]' id='classic-disc' value='0' /><p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p></div>";

    $(".classic-vehicles-wrap").append(content);

    return false;

});

$( "#phone-order-form" ).on( "click", ".regular-car-remove", function() {
   $(this).parent().parent().remove();
   return false;
});

$( "#phone-order-form" ).on( "click", ".classic-car-remove", function() {
   $(this).parent().parent().remove();
   return false;
});

$( "#phone-order-form" ).on( "change", ".regular-pack", function() {
   if($(this).val() == 'Deluxe'){
$(this).parent().find('.prem_disc_el input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extclaybar input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.waterspotremove input[type=checkbox]').prop( "checked", false );
$(this).parent().find('#regular-disc').val(0);
$(this).parent().find('#extclaybars').val(0);
$(this).parent().find('#waterspotremoves').val(0);
$(this).parent().find('.prem_disc_el').hide();
$(this).parent().find('.extclaybar, .waterspotremove').hide();
$(this).parent().find('.deluxe_disc_el').show();
$(this).parent().find('.exthandwax, .extplasticdressing').show();
}

   if($(this).val() == 'Premium'){
$(this).parent().find('.deluxe_disc_el input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extplasticdressing input[type=checkbox]').prop( "checked", false );
$(this).parent().find('#regular-disc').val(0);
$(this).parent().find('#exthandwaxes').val(0);
$(this).parent().find('#extplasticdressings').val(0);
$(this).parent().find('.exthandwax, .extplasticdressing').hide();
$(this).parent().find('.deluxe_disc_el').hide();
$(this).parent().find('.prem_disc_el').show();
$(this).parent().find('.extclaybar, .waterspotremove').show();

}
   return false;
});

$( "#phone-order-form" ).on( "change", ".classic-pack", function() {
   if($(this).val() == 'Deluxe'){
$(this).parent().find('.prem_disc_el input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extclaybar input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.waterspotremove input[type=checkbox]').prop( "checked", false );
$(this).parent().find('#classic-disc').val(0);
$(this).parent().find('#extclaybars').val(0);
$(this).parent().find('#waterspotremoves').val(0);
$(this).parent().find('.prem_disc_el').hide();
$(this).parent().find('.extclaybar, .waterspotremove').hide();
$(this).parent().find('.deluxe_disc_el').show();
$(this).parent().find('.exthandwax, .extplasticdressing').show();
}

   if($(this).val() == 'Premium'){
$(this).parent().find('.deluxe_disc_el input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extplasticdressing input[type=checkbox]').prop( "checked", false );
$(this).parent().find('#classic-disc').val(0);
$(this).parent().find('#exthandwaxes').val(0);
$(this).parent().find('#extplasticdressings').val(0);
$(this).parent().find('.deluxe_disc_el').hide();
$(this).parent().find('.exthandwax, .extplasticdressing').hide();
$(this).parent().find('.prem_disc_el').show();
$(this).parent().find('.extclaybar, .waterspotremove').show();

}
   return false;
});

$( "#phone-order-form" ).on( "click", ".deluxe_disc_el input[type=checkbox], .prem_disc_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#regular-disc').val($(this).val());
$(this).parent().parent().find('#classic-disc').val($(this).val());
}
else {
$(this).parent().parent().find('#regular-disc').val(0);
$(this).parent().parent().find('#classic-disc').val(0);
}

});

$( "#phone-order-form" ).on( "click", "#regular_pet_fee, #regular_truck_fee, #classic_pet_fee, #classic_truck_fee", function() {
if($(this).is(":checked")) {
$(this).next().val(5);
}
else {
$(this).next().val(0);
}

});


$( "#phone-order-form" ).on( "click", ".exthandwax input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).next().val($(this).val());
}
else {
$(this).next().val(0);
}

});

$( "#phone-order-form" ).on( "click", ".extplasticdressing input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).next().val($(this).val());
}
else {
$(this).next().val(0);
}

});


$( "#phone-order-form" ).on( "click", ".extclaybar input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).next().val($(this).val());
}
else {
$(this).next().val(0);
}

});


$( "#phone-order-form" ).on( "click", ".waterspotremove input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).next().val($(this).val());
}
else {
$(this).next().val(0);
}

});

$( ".pop-close" ).click(function(){
   $(".popup-overlay").fadeOut();
   return false;
});

$( ".process-payment-trigger" ).click(function(){
   $(".popup-overlay").fadeIn();
   return false;
});


$('#phone-order-form .checklist-checker').click(function() {
    var $this = $(this);

    if ($this.is(':checked')) {
      $this.closest('li').addClass('checked');
if($this.closest('li').is(':last-child')){
$(".checklist").css('borderBottom', 0);
}
    } else {
       $this.closest('li').removeClass('checked');
if($this.closest('li').is(':last-child')){
$(".checklist").css('borderBottom', '1px solid red');
}
$this.removeAttr('checked');
    }
});

 if ($("#phone-order-form .checklist li:last-child .checklist-checker").is(':checked')) {
$(".checklist").css('borderBottom', 0);
}

 /*
$("#order_discount").keyup(function(){
   if($("#tip_amount").val()) new_total = parseFloat(total_price) + parseFloat($("#tip_amount").val()) - parseFloat($(this).val());
else new_total = parseFloat(total_price) - parseFloat($(this).val());
new_total = new_total.toFixed(2);

if(!isNaN(new_total)) {
$(".order_total").html("$"+new_total);
$("input[name=pay-process-submit]").val("Process $"+new_total);
}
if(!$(this).val()) {
if($("#tip_amount").val()) {
$(".order_total").html("$"+parseFloat(total_price)+parseFloat($("#tip_amount").val()));
$("input[name=pay-process-submit]").val("Process $"+parseFloat(total_price)+parseFloat($("#tip_amount").val()));
}
else{
$(".order_total").html("$"+total_price);
$("input[name=pay-process-submit]").val("Process $"+total_price);
}


}

});

$("#tip_amount").keyup(function(){
   new_total = parseFloat(total_price) + parseFloat($(this).val());
new_total = new_total.toFixed(2);

if(!isNaN(new_total)) {
$(".order_total").html("$"+new_total);
$("input[name=pay-process-submit]").val("Process $"+new_total);
}
if(!$(this).val()) {
$(".order_total").html("$"+total_price);
$("input[name=pay-process-submit]").val("Process $"+total_price);

}

//console.log(new_total);

});
*/
});

function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
             return false;

//new_total = parseFloat(total_price) - parseFloat($("#order_discount").val());

//if(!new_total) $(".order_total").html("$"+new_total);

//console.log($("#order_discount").val());

          return true;
       }


</script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="../js/jquery.bxslider.min.js"></script>
<script>
$(function(){
  $('.bxslider').bxSlider();
});
</script>

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
<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).
var clientToken = "eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiIxNjBiZjQ2OGE0YjJmNjQ1ZTFiZmQ5NjIxYWQyZTVlMWZiZmRjNjg0OGJjZGI2ZGExYmNjZjI0YmEyNTViMjQyfGNyZWF0ZWRfYXQ9MjAxNS0xMi0wM1QxODo0NTo0Ni45MDI1MzQwMzErMDAwMFx1MDAyNm1lcmNoYW50X2lkPWQ1NXozOG56dnQ1enpxbnNcdTAwMjZwdWJsaWNfa2V5PWRwNG5kcTRkaDhrNHdydnkiLCJjb25maWdVcmwiOiJodHRwczovL2FwaS5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tOjQ0My9tZXJjaGFudHMvZDU1ejM4bnp2dDV6enFucy9jbGllbnRfYXBpL3YxL2NvbmZpZ3VyYXRpb24iLCJjaGFsbGVuZ2VzIjpbXSwiZW52aXJvbm1lbnQiOiJzYW5kYm94IiwiY2xpZW50QXBpVXJsIjoiaHR0cHM6Ly9hcGkuc2FuZGJveC5icmFpbnRyZWVnYXRld2F5LmNvbTo0NDMvbWVyY2hhbnRzL2Q1NXozOG56dnQ1enpxbnMvY2xpZW50X2FwaSIsImFzc2V0c1VybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXV0aFVybCI6Imh0dHBzOi8vYXV0aC52ZW5tby5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tIiwiYW5hbHl0aWNzIjp7InVybCI6Imh0dHBzOi8vY2xpZW50LWFuYWx5dGljcy5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tIn0sInRocmVlRFNlY3VyZUVuYWJsZWQiOmZhbHNlLCJwYXlwYWxFbmFibGVkIjp0cnVlLCJwYXlwYWwiOnsiZGlzcGxheU5hbWUiOiJpdmFjdWx1cyIsImNsaWVudElkIjpudWxsLCJwcml2YWN5VXJsIjoiaHR0cDovL2V4YW1wbGUuY29tL3BwIiwidXNlckFncmVlbWVudFVybCI6Imh0dHA6Ly9leGFtcGxlLmNvbS90b3MiLCJiYXNlVXJsIjoiaHR0cHM6Ly9hc3NldHMuYnJhaW50cmVlZ2F0ZXdheS5jb20iLCJhc3NldHNVcmwiOiJodHRwczovL2NoZWNrb3V0LnBheXBhbC5jb20iLCJkaXJlY3RCYXNlVXJsIjpudWxsLCJhbGxvd0h0dHAiOnRydWUsImVudmlyb25tZW50Tm9OZXR3b3JrIjp0cnVlLCJlbnZpcm9ubWVudCI6Im9mZmxpbmUiLCJ1bnZldHRlZE1lcmNoYW50IjpmYWxzZSwiYnJhaW50cmVlQ2xpZW50SWQiOiJtYXN0ZXJjbGllbnQzIiwiYmlsbGluZ0FncmVlbWVudHNFbmFibGVkIjpudWxsLCJtZXJjaGFudEFjY291bnRJZCI6Iml2YWN1bHVzIiwiY3VycmVuY3lJc29Db2RlIjoiVVNEIn0sImNvaW5iYXNlRW5hYmxlZCI6ZmFsc2UsIm1lcmNoYW50SWQiOiJkNTV6MzhuenZ0NXp6cW5zIiwidmVubW8iOiJvZmZsaW5lIiwiYXBwbGVQYXkiOnsic3RhdHVzIjoibW9jayIsImNvdW50cnlDb2RlIjoiVVMiLCJjdXJyZW5jeUNvZGUiOiJVU0QiLCJtZXJjaGFudElkZW50aWZpZXIiOiJtZXJjaGFudC5Nb2JpbGVXYXNoLmFwcGxlcGF5Iiwic3VwcG9ydGVkTmV0d29ya3MiOlsidmlzYSIsIm1hc3RlcmNhcmQiLCJhbWV4Il19fQ==";

var token = "<?php echo $clientToken; ?>";

braintree.setup(token, "dropin", {
  container: "payment-form"
});
</script>