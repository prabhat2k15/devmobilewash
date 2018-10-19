<?php
include('header.php');
session_start();

 require_once('../api/protected/vendors/braintree/lib/Braintree.php');

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

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');


$clientToken = Braintree_ClientToken::generate();
$first_wash_check = 0;
if($_GET['customer_id']){

/* --- client my account call --- */

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
$data = array('customerid' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
//var_dump($profiledetail);
$profiledetail_response = $profiledetail->response;
$profiledetail_result_code = $profiledetail->result;
//echo count($all_wash_requests);
$bt_id = $profiledetail->braintree_id;
$first_wash_check = $profiledetail->is_first_wash;

/* --- client my account call end --- */

}

if(isset($_POST['payment_method_nonce'])){
$adderror = '';
$address = '';
$address_type = '';
$full_address = '';
$lat = '';
$long = '';

 $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }

    $pass_new = implode($pass);

$data = array('customername' => $_POST['cname'], 'emailid'=> $_POST['cemail'], 'contact_number'=>$_POST['cphone'], 'password'=>$pass_new, 'time_zone'=> 'America/Los_Angeles', 'client_position' => 'real', 'how_hear_mw' => $_POST['how-hear'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/register");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
//var_dump($jsondata);
$reg_response = $jsondata->response;
$reg_result_code = $jsondata->result;
$cust_id = $jsondata->customerid;

//echo $reg_response." ".$cust_id;

if($_GET['customer_id']){
$cust_id = $_GET['customer_id'];
$reg_result_code = 'true';
}

if($reg_result_code == 'true'){

$total = 0;
$agent_total = 0;
$company_total = 0;
 $all_cars = '';
if(count($_POST['car_makes'])){
    $car_ids = '';
$car_packs = '';
foreach($_POST['car_makes'] as $ind=>$make){
 $car_price = 0;
$bundle_fee = 0;
$first_disc = 0;
$fifth_disc = 0;
$car_id = 0;

if(!isset($_POST['car_prices'][$ind])){
    $handle = curl_init(ROOT_URL."/api/index.php?r=washing/plans");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("vehicle_make" => $make, "vehicle_model" => $_POST['car_models'][$ind], "vehicle_build" => $_POST['car_types'][$ind], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$pricedata = json_decode($result);

if($_POST['car_packs'][$ind] == 'Deluxe') $car_price = $pricedata->plans->deluxe[0]->price;
if($_POST['car_packs'][$ind] == 'Premium') $car_price = $pricedata->plans->premium[0]->price;

}
else{
    $car_price = $_POST['car_prices'][$ind];
}

if(!isset($_POST['bundle_discs'][$ind])){

if(count($_POST['car_makes']) > 1) $bundle_fee = 1;
}
else{
if(count($_POST['car_makes']) > 1) $bundle_fee = 1;
else $bundle_fee = $_POST['bundle_discs'][$ind];
}

if(!isset($_POST['first_discs'][$ind])){

}
else{
$first_disc = $_POST['first_discs'][$ind];
}

if(!isset($_POST['fifth_discs'][$ind])){

}
else{
$fifth_disc = $_POST['fifth_discs'][$ind];
}

if($_POST['car_ids'][$ind] == 0){
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/addvehicle");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('customer_id' => $cust_id, 'brand_name' => $make, 'model_name' => $_POST['car_models'][$ind], 'vehicle_image' => ROOT_URL.'/api/images/veh_img/no_pic.jpg', 'vehicle_build' => $_POST['car_types'][$ind], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$car_id = $jsondata->vehicle->id;
}
else{
$car_id = $_POST['car_ids'][$ind];
}

$all_cars .= $make.",".$_POST['car_models'][$ind].",".$_POST['car_packs'][$ind].",".$_POST['car_types'][$ind].",".$car_price.",".$_POST['pet_fees'][$ind].",".$_POST['truck_fees'][$ind].",".$_POST['handle_fees'][$ind].",".$bundle_fee.",".$first_disc.",".$fifth_disc.",".$car_id.",".$_POST['exthandwaxes'][$ind].",".$_POST['extplasticdressings'][$ind].",".$_POST['extclaybars'][$ind].",".$_POST['waterspotremoves'][$ind]."|";
$car_ids .= $car_id.",";
$car_packs .=  $_POST['car_packs'][$ind].",";

$total += $car_price;
$total += $_POST['pet_fees'][$ind];
$total += $_POST['truck_fees'][$ind];
$total += $_POST['exthandwaxes'][$ind];
$total += $_POST['extplasticdressings'][$ind];
$total += $_POST['extclaybars'][$ind];
$total += $_POST['waterspotremoves'][$ind];
$total += $_POST['handle_fees'][$ind];
$total -= $bundle_fee;
$total -= $first_disc;
$total -= $fifth_disc;

if($_POST['car_packs'][$ind] == 'Premium') {
 $agent_total += $car_price * .75;
}
else $agent_total += $car_price * .80;

 $agent_total += $_POST['extclaybars'][$ind] * .80;
 $agent_total += $_POST['waterspotremoves'][$ind] * .80;
$agent_total += $_POST['exthandwaxes'][$ind] * .80;
 $agent_total += $_POST['extplasticdressings'][$ind] * .80;
$agent_total += $_POST['pet_fees'][$ind] * .80;
$agent_total += $_POST['truck_fees'][$ind] * .80;
$agent_total -= $bundle_fee * .80;

if($_POST['car_packs'][$ind] == 'Premium') {
 $company_total += $car_price * .25;

}
else $company_total += $car_price * .20;

 $company_total += $_POST['extclaybars'][$ind] * .20;
 $company_total += $_POST['waterspotremoves'][$ind] * .20;
$company_total += $_POST['pet_fees'][$ind] * .20;
$company_total += $_POST['truck_fees'][$ind] * .20;
$company_total += $_POST['exthandwaxes'][$ind] * .20;
$company_total += $_POST['extplasticdressings'][$ind] * .20;
$company_total += $_POST['handle_fees'][$ind];
$company_total -= $bundle_fee * .20;
$company_total -= $first_disc;
$company_total -= $fifth_disc;

}
}

 //$total += $getorder->tip_amount;
 //if($getorder->tip_amount) $agent_total += $getorder->tip_amount * .80;
 //if($getorder->tip_amount) $company_total += $getorder->tip_amount * .20;

//$total -= $getorder->coupon_discount;
//if($getorder->coupon_discount) $company_total -= $getorder->coupon_discount;

$all_cars = trim($all_cars,"|");

//echo $all_cars;
//echo"<br>".$total."<br>";

$car_ids = trim($car_ids,",");
$car_packs = trim($car_packs,",");


if(!empty($_POST['loc_id'])){


$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getlocationbyid");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("customer_id" => $cust_id, "location_id" => $_POST['loc_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

  $lat = $jsondata->location_details->lat;
$long = $jsondata->location_details->lng;

$full_address = $jsondata->location_details->address;
$address_type = $jsondata->location_details->title;

$address_frag = explode(" ", $full_address);

$on_demand_area = '';
$schedule_area = '';

 $geourl = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($full_address)."&sensor=true&key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA";
    $ch = curl_init();
     $zip = '';
	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//var_dump($geojsondata);
if($geojsondata->status == 'ZERO_RESULTS'){
$adderror = "Error in adding location.";
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();
}
else{
 $addressComponents = $geojsondata->results[0]->address_components;
            foreach($addressComponents as $addrComp){
                if($addrComp->types[0] == 'postal_code'){
                    //Return the zipcode
                    $zip = $addrComp->long_name;
                }
            }
}



$url = ROOT_URL.'/api/index.php?r=washing/checkcoveragezipcode';
            $handle = curl_init($url);
            $data = array("zipcode" => $zip, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            if($jsondata->result == 'true') $on_demand_area = 'yes';




   if(($on_demand_area == '')){
       $adderror = "Sorry, Mobile Wash is currently not available in your area. Please register to find out when we're available in your area!";
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();
}


}
else{

$address = $_POST['caddress'];
$address_type = $_POST['address_type'];
$address_temp = $_POST['caddress'].", ".$_POST['ccity'].", ".$_POST['cstate']." ".$_POST['czip'];



 $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address_temp)."&sensor=true&key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA";
    $ch = curl_init();
     $zip = '';
	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//var_dump($geojsondata);
if($geojsondata->status == 'ZERO_RESULTS'){
  $adderror = "Error in adding location.";
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();
}
else{
   $addressComponents = $geojsondata->results[0]->address_components;
            foreach($addressComponents as $addrComp){
                if($addrComp->types[0] == 'postal_code'){
                    //Return the zipcode
                    $zip = $addrComp->long_name;
                }
            }
}


$on_demand_area = '';
$schedule_area = '';

if(!$zip) $zip = $_POST['czip'];

$url = ROOT_URL.'/api/index.php?r=washing/checkcoveragezipcode';

            $handle = curl_init($url);
            $data = array("zipcode" => $zip, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            if($jsondata->result == 'true') $on_demand_area = 'yes';


   if(($on_demand_area == '')){
        $adderror = "Sorry, Mobile Wash is currently not available in your area. Please register to find out when we're available in your area!";
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();

}

else{


$full_address = $address_temp;


    $full_address = trim($full_address);
      //echo $fulladdress;
    $encode_address = urlencode($full_address);

    /* --- Geocode lat long --- */

    $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".$encode_address."&sensor=true&key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA";
    $ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//var_dump($geojsondata);
if($geojsondata->status == 'ZERO_RESULTS'){
          $adderror = "Error in adding location.";
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();
}
else{
 $lat = $geojsondata->results[0]->geometry->location->lat;
$long = $geojsondata->results[0]->geometry->location->lng;

}


$handle = curl_init(ROOT_URL."/api/index.php?r=customers/addlocation");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("customer_id" => $cust_id, "location_title" => $_POST['address_type'], "location_address" => $full_address, 'actual_latitude'=> $lat, 'actual_longitude' => $long, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

}

}


/* -------- estimate time ---------- */
$eta = '60';

 $handle = curl_init(ROOT_URL."/api/index.php?r=customers/estimatetime");
$data = array('customer_id' => $cust_id, 'latitude'=> $lat, 'longitude' => $long, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
//var_dump($jsondata);
if($jsondata->estimate_time) $eta = $jsondata->estimate_time;


/* -------- estimate time end ---------- */

if(isset($_POST['payment_method_nonce'])){

 $handle = curl_init(ROOT_URL."/api/index.php?r=customers/getclienttoken");
$data = array('customer_id' => $cust_id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
//var_dump($jsondata);
//$clientoken_response = $jsondata->response;
//$clientoken_result_code = $jsondata->result;
//$clientToken = $jsondata->client_token;


$handle = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
$data = array('customerid' => $cust_id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);

$bt_id = $profiledetail->braintree_id;

$paymentdata = '';
  $payment_pass = 0;



if(!empty($_POST['pay_method_token'])){
    $paymentdata = array('customer_id' => $cust_id, 'nonce'=> $_POST['payment_method_nonce'], 'payment_method_token'=> $_POST['pay_method_token'], 'amount' => $total, 'company_amount' => $company_total, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
//echo "working";
//echo $total;
//echo $company_total;

 }
 else{
  $paymentdata = array('customer_id' => $cust_id, 'nonce'=> $_POST['payment_method_nonce'], 'amount' => $total, 'company_amount' => $company_total, 'cardno' => $_POST['ccno'], 'cardname' => $_POST['ccname'], 'cvv' => $_POST['cccvc'], 'mo_exp' => $_POST['ccexpmo'], 'yr_exp' => $_POST['ccexpyr'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

 }



 /* --- payment process api --- */

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/CustomerPaymentWebsite");

curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $paymentdata);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);

$jsondata = json_decode($result);
//var_dump($jsondata);

$afterpay_response = $jsondata->response;
$afterpay_result_code = $jsondata->result;

//echo $result->paymentInstrumentType;
if($afterpay_response == "Payment method saved"){
$payment_pass = 1;

}
else{

  $adderror = $afterpay_response;
//header('location: '.ROOT_URL.'/admin-new/add-schedule-order.php?step=2');
//die();
}

}


 if($payment_pass){
 $handle = curl_init(ROOT_URL."/api/index.php?r=washing/createwashrequest");
  //echo $car_ids."<br>".$car_packs."<br>".$address."<br>".$address_type."<br>".$lat."<br>".$long."<br>".$eta."<br>".$_POST['sdate']."<br>".$_POST['stime']."<br>".$all_cars."<br>".$total."<br>".$company_total."<br>".$agent_total;
$data = array('customer_id' => $cust_id, 'car_ids'=> $car_ids, 'package_names'=>$car_packs, 'address'=> $full_address, 'address_type'=> $address_type, 'latitude'=> $lat, 'longitude' => $long, 'estimate_time' => $eta, 'is_scheduled' => 1, 'schedule_date' =>$_POST['sdate'], 'schedule_time' =>date('h:i A', strtotime($_POST['stime'])), 'schedule_cars_info' =>$all_cars, 'schedule_total' =>number_format($total, 2), 'schedule_company_total' =>number_format($company_total, 2), 'schedule_agent_total' =>number_format($agent_total, 2), 'coupon_amount' => $_POST['coupon_amount'], 'coupon_code' => $_POST['coupon_code'], 'tip_amount' => $_POST['tip_amount'], 'wash_request_position' => 'real', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
//var_dump($jsondata);
//die();
$createwash_response = $jsondata->response;
$createwash_result_code = $jsondata->result;

//echo "<br>".$createwash_response;

if($createwash_result_code == 'false'){
    $adderror = $createwash_response;
//header('location: http://www.devmobilewash.com/admin-new/add-schedule-order.php?step=2');
//die();

}

if($createwash_result_code == 'true'){
    session_destroy();
     echo "<script type='text/javascript'>window.location = 'edit-schedule-order.php?id=".$createwash_response."';</script>";
exit;
}
}


}

else{

   $adderror = $reg_response;
//header('location: http://www.devmobilewash.com/admin-new/add-schedule-order.php?step=2');
//die();
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
    height: 300px;
    background: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -250px;
    margin-top: -150px;
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

.err-text{
text-align: left;
clear: both;
margin-top: 0;
background: #d40000;
color: #fff;
padding: 10px;
display: none;
}


.extclaybar, .waterspotremove{
    display: none;
}

.sec-heading{
margin-top: 0;
margin-bottom: 25px;
font-weight: bold;
font-size: 18px;
text-decoration: underline;
}

.cust_locations, .pay-methods{
    list-style: none;
    margin: 0;
    padding: 0;
    margin-left: 10px;
}

.cust_locations li, .pay-methods li{
    margin-bottom: 10px;
}

.radio input[type=radio]{
    margin-left: -9px;
}

.add-address-wrap, .add-card-wrap{
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">ADD SCHEDULE ORDER</span>

                                                </div>
       <?php if( $adderror): ?>
<p style="text-align: left; clear: both; margin-top: 0; background: #d40000; color: #fff; padding: 10px;"><?php echo $adderror; ?></p>
<?php endif; ?>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">

                                                    <form action="" id="phone-order-form" method="post" enctype="multipart/form-data">
                                                    <div class="col-md-8" style="padding-left: 0; padding-right: 0;">

<h3 class="sec-heading">General Info</h3>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" style="margin-top: 0;">Customer Name<span style="color: red;">*</span></label>
                                                            <input type="text" name="cname" id="cname" style="width: 300px;" class="form-control" value="<?php if($_GET['customer_id']) {echo $profiledetail->customername;} ?>" <?php if($_GET['customer_id']) {echo "readonly";} ?> required />
                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" style="margin-top: 0;">Phone Number<span style="color: red;">*</span></label>
                                                            <input type="text" name="cphone" id="cphone" style="width: 300px;" class="form-control" value="<?php if($_GET['customer_id']) {echo $profiledetail->contact_number;} ?>" <?php if($_GET['customer_id']) {echo "readonly";}  ?> required />
                                                        </div>
                                                     </div>
                                                     <div style="clear: both;"></div>
                                                        <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Email Address<span style="color: red;">*</span></label>
                                                     <input type="text" name="cemail" id="cemail" style="width: 300px;" class="form-control" value="<?php if($_GET['customer_id']) echo $profiledetail->email; ?>" <?php if($_GET['customer_id']) echo "readonly"; ?> required />

                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">How did you hear about us? (Optional)</label>
                                                            <input type="text" name="how-hear" id="how-hear" style="width: 300px;" value="<?php if($_GET['customer_id']) echo $profiledetail->how_hear_mw; ?>" class="form-control">
                                                        </div>
                                                     </div>

                                                                            <div style="clear: both;"></div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label">Schedule Date<span style="color: red;">*</span></label>
                                                            <input type="text" name="sdate" id="sdate" style="width: 300px;" class="form-control date-picker" value="" required />
                                                        </div>
                                                     </div>

                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label">Schedule Time<span style="color: red;">*</span></label>
                                                            <input type="text" name="stime" id="stime" style="width: 300px;" class="form-control timepicker timepicker-default" value="" required />
                                                        </div>
                                                     </div>

                                                            <div style="clear: both;"></div>


                                                     <h3 class="sec-heading" style="margin-top: 25px;">Location Info</h3>

                                                     <?php if(count($profiledetail->customer_locations)): ?>

                                                     <ul class="cust_locations">
<?php
$first_loc_id = 0;
foreach($profiledetail->customer_locations as $ind=> $loc): ?>
<li><input type="radio" <?php if($ind == 0) echo "checked"; ?> value="<?php echo $loc->location_id; ?>" name="saved_loc" class="cust_saved_loc" /> <strong><?php echo $loc->location_title; ?>:</strong> <?php echo $loc->location_address; ?></li>
<?php
if($ind == 0) $first_loc_id = $loc->location_id;
endforeach; ?>
</ul>

<input type="hidden" name="loc_id" id="loc_id" value="<?php if($first_loc_id) echo $first_loc_id; ?>" />
 <?php endif; ?>

<p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="loc-add-trigger" href="#">+ ADD NEW LOCATION</a></p>

  <div class="add-address-wrap">
  <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address<span style="color: red;">*</span></label>
                                                            <input type="text" name="caddress" id="caddress" style="width: 300px;" class="form-control" value="" required <?php if($first_loc_id) echo "disabled"; ?> />
                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address Type<span style="color: red;">*</span></label>
<select name="address_type" id="address_type" style="width: 300px;" class="form-control" required="" <?php if($first_loc_id) echo "disabled"; ?>><option value="Home">Home</option><option value="Work">Work</option></select>
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>

                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">City<span style="color: red;">*</span></label>
                                                            <input type="text" name="ccity" id="ccity" style="width: 300px;" class="form-control" required="" <?php if($first_loc_id) echo "disabled"; ?>>
                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">State<span style="color: red;">*</span></label>
                                                            <input type="text" name="cstate" id="cstate" style="width: 300px;" class="form-control" required="" <?php if($first_loc_id) echo "disabled"; ?>>
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>
                                                          <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Zipcode<span style="color: red;">*</span></label>
                                                            <input type="text" name="czip" id="czip" style="width: 300px;" class="form-control" required="" <?php if($first_loc_id) echo "disabled"; ?>>
                                                        </div>
                                                     </div>
                                                      <div style="clear: both;"></div>
  </div>

     <h3 class="sec-heading" style="margin-top: 25px; margin-bottom: 0;">Vehicles Info</h3>

      <?php
                       $all_cars = '';
      if(isset($_GET['customer_id'])) {
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getvehicals");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('customer_id' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$savedcars = json_decode($result);

if($savedcars->result == 'true'){


foreach($savedcars->vehicles as $ind=> $veh){
if($ind >=2) break;
$all_cars .= $veh->brand_name.",".$veh->model_name.",Deluxe,".$veh->vehicle_build.",0,0,0,1,0,0,0,".$veh->id.",0,0,0,0|";
}

$all_cars = trim($all_cars,"|");

}

}
                                                     $all_vehicles =   explode("|", $all_cars);
                                                     $regular_vehicles = '';
                                                     $classic_vehicles = '';

                                                        foreach($all_vehicles as $vehc){
                                                            $vech_details = explode(',', $vehc);
                                                            if($vech_details[3] == 'regular') $regular_vehicles .= $vehc."|";
                                                            if($vech_details[3] == 'classic') $classic_vehicles .= $vehc."|";
                                                        }
                                                        $regular_vehicles = trim($regular_vehicles,"|");
                                                        $classic_vehicles = trim($classic_vehicles,"|");
                                                     ?>

                                                             <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="regular-add-trigger" href="#">+ ADD NEW VEHICLE</a></p>
                                                             <div class="regular-vehicles-wrap">


<?php if($regular_vehicles): ?>
<?php $reg_vehicles = explode("|",$regular_vehicles);
foreach($reg_vehicles as $ind=>$veh): ?>
<?php $veh_detail = explode(",",$veh); ?>
<div class='regular-car-box' id='regular-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>

<label class='control-label'>Make</label>
<input type="text" name='car_makes[]' class='form-control regular-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[0]; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='car_models[]' class='form-control regular-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[1]; ?>" />

<input type="hidden" name="car_types[]" value="<?php echo $veh_detail[3]; ?>" />

<label class='control-label'>Package</label>
<select name="car_packs[]" class="form-control regular-pack" style="width: 300px;">
<option value="Deluxe">DELUXE WASH</option>
<option value="Premium">PREMIUM DETAIL</option>
</select>


<input type="hidden" name="handle_fees[]" value="1" />

<p style="margin-top: 20px;" class="exthandwax">
<input type="checkbox" id="exthandwax" value="12"> $12 Full Exterior Hand Wax (Liquid form)
</p>

<p style="margin-top: 20px;" class="extplasticdressing">
<input type="checkbox" id="extplasticdressing" value="10"> $10 Dressing of all Exterior Plastics
</p>

<p style="margin-top: 20px;" class="extclaybar">
<input type="checkbox" id="extclaybar" value="35"> $35 Full Exterior Clay Bar
</p>

<p style="margin-top: 20px;" class="waterspotremove">
<input type="checkbox" id="waterspotremove" value="30"> $30 Water Spot Removal
</p>

<p class="pet_fee_el" style="margin-top: 20px;">
<input type="checkbox" id="pet_fee" value="5"> $5 Pet Hair Fee
</p>

<p class="lifted_truck_el" style="margin-top: 20px;">
<input type="checkbox" id="lifted_truck_fee" value="5"> $5 Lifted Truck Fee
</p>
<input type="hidden" name="pet_fees[]" id="pet_fees" value="0">
<input type="hidden" name="truck_fees[]" id="truck_fees" value="0">
<input type="hidden" id="exthandwaxes" name="exthandwaxes[]" value="0">
<input type="hidden" id="extplasticdressings" name="extplasticdressings[]" value="0">
<input type="hidden" id="extclaybars" name="extclaybars[]" value="0">
<input type="hidden" id="waterspotremoves" name="waterspotremoves[]" value="0">

  <input type="hidden" name="first_discs[]" value="<?php echo $veh_detail[9]; ?>" />

<input type="hidden" name="fifth_discs[]" value="<?php echo $veh_detail[10]; ?>" />
<input type="hidden" name="car_ids[]" value="<?php echo $veh_detail[11]; ?>" />
<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>
<?php endforeach; ?>
<?php endif; ?>



                                                             </div>




                                                             </div>
                                                              <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="classic-add-trigger" href="#">+ ADD NEW CLASSIC</a></p>
                                                             <div class="classic-vehicles-wrap">

                                                             <?php if($classic_vehicles): ?>
<?php $cla_vehicles = explode("|",$classic_vehicles);
foreach($cla_vehicles as $ind=>$veh): ?>
<?php $veh_detail = explode(",",$veh); ?>
<div class='classic-car-box' id='classic-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>

<label class='control-label'>Make</label>
<input type="text" name='car_makes[]' class='form-control regular-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[0]; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='car_models[]' class='form-control regular-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh_detail[1]; ?>" />

<input type="hidden" name="car_types[]" value="<?php echo $veh_detail[3]; ?>" />

<label class='control-label'>Package</label>
<select name="car_packs[]" class="form-control regular-pack" style="width: 300px;">
<option value="Deluxe">DELUXE WASH</option>
<option value="Premium">PREMIUM DETAIL</option>
</select>

<input type="hidden" name="handle_fees[]" value="1" />

<p style="margin-top: 20px;" class="exthandwax">
<input type="checkbox" id="exthandwax" value="12"> $12 Full Exterior Hand Wax (Liquid form)
</p>

<p style="margin-top: 20px;" class="extplasticdressing">
<input type="checkbox" id="extplasticdressing" value="10"> $10 Dressing of all Exterior Plastics
</p>

<p style="margin-top: 20px;" class="extclaybar">
<input type="checkbox" id="extclaybar" value="35"> $35 Full Exterior Clay Bar
</p>

<p style="margin-top: 20px;" class="waterspotremove">
<input type="checkbox" id="waterspotremove" value="30"> $30 Water Spot Removal
</p>

<p class="pet_fee_el" style="margin-top: 20px;">
<input type="checkbox" id="pet_fee" value="5"> $5 Pet Hair Fee
</p>

<p class="lifted_truck_el" style="margin-top: 20px;">
<input type="checkbox" id="lifted_truck_fee" value="5"> $5 Lifted Truck Fee
</p>
<input type="hidden" name="pet_fees[]" id="pet_fees" value="0">
<input type="hidden" name="truck_fees[]" id="truck_fees" value="0">
<input type="hidden" id="exthandwaxes" name="exthandwaxes[]" value="0">
<input type="hidden" id="extplasticdressings" name="extplasticdressings[]" value="0">
<input type="hidden" id="extclaybars" name="extclaybars[]" value="0">
<input type="hidden" id="waterspotremoves" name="waterspotremoves[]" value="0">

  <input type="hidden" name="first_discs[]" value="<?php echo $veh_detail[9]; ?>" />

<input type="hidden" name="fifth_discs[]" value="<?php echo $veh_detail[10]; ?>" />
<input type="hidden" name="car_ids[]" value="<?php echo $veh_detail[11]; ?>" />

<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p>

</div>
<?php endforeach; ?>
<?php endif; ?>

                                                             </div>

                                                             </div>
                                                             <div style="clear: both;"></div>

     <h3 class="sec-heading" style="margin-top: 25px;">Payment Methods</h3>
     <?php
 if(isset($_GET['customer_id'])) {

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getcustomerpaymentmethods");
$data = array('customer_id' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$paymentmethods = json_decode($result);
 ?>
<?php if($paymentmethods->result == 'true'): ?>
<?php $first_pay_token = '';
$first_card_ending_no = '';
$first_card_type = ''; ?>
<ul class="pay-methods">
<?php foreach($paymentmethods->payment_methods as $ind => $paymethod): ?>
<li data-token="<?php echo $paymethod->payment_method_details->token; ?>" data-ending="<?php echo $paymethod->payment_method_details->last4; ?>" data-type="<?php echo $paymethod->payment_method_details->cardType; ?>">
<div style="float: left;"><input type="radio" <?php if($ind == 0) echo "checked"; ?> value="<?php echo $paymethod->payment_method_details->token; ?>" name="saved_paymethod" class="cust_saved_paymethod" /> <img class="card-img" src="<?php echo $paymethod->payment_method_details->cardimg; ?>" style="width: 44px; margin-right: 6px; margin-left: 6px;" /> <?php echo $paymethod->payment_method_details->maskedNumber; ?></div><div style="float: left; margin-left: 60px; margin-top: 7px;"><?php echo $paymethod->payment_method_details->cardname; ?></div><div style="clear: both;"></div></li>
<?php if($ind == 0) {
  $first_pay_token = $paymethod->payment_method_details->token;
   $first_card_ending_no = $paymethod->payment_method_details->last4;
   $first_card_type = $paymethod->payment_method_details->cardType;
} ?>
<?php endforeach; ?>
</ul>
<input type="hidden" name="pay_method_token" id="pay_method_token" value="<?php if($first_pay_token) echo $first_pay_token; ?>" />
<input type="hidden" name="card_ending_no" id="card_ending_no" value="<?php if($first_card_ending_no) echo $first_card_ending_no; ?>" />
<input type="hidden" name="card_type" id="card_type" value="<?php if($first_card_type) echo $first_card_type; ?>" />

<?php endif; }?>

<p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="paymethod-add-trigger" href="#">+ ADD PAYMENT METHOD</a></p>

<div class="add-card-wrap">
         <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Credit Card Number<span style="color: red;">*</span></label>
                                                     <input type="text" name="ccno" id="ccno" style="width: 300px;" class="form-control" value="" />

                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Credit Card Name<span style="color: red;">*</span></label>
                                                            <input type="text" name="ccname" id="ccname" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>

                                                                            <div style="clear: both;"></div>

                                                                               <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Credit Card Exp Month<span style="color: red;">*</span></label>
                                                     <input type="text" name="ccexpmo" id="ccexpmo" placeholder="MM" style="width: 300px;" class="form-control" value="" />

                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Credit Card Exp Year<span style="color: red;">*</span></label>
                                                            <input type="text" name="ccexpyr" id="ccexpyr" placeholder="YYYY" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>

                                                                            <div style="clear: both;"></div>
                                                                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Credit Card CVV<span style="color: red;">*</span></label>
                                                            <input type="text" name="cccvc" id="cccvc" placeholder="CVV" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>

</div>

                                                            </div>

                                                             <div style="clear: both;"></div>
                                                             <input type="hidden" id="bt_number" data-braintree-name="number">
<input type="hidden" id="bt_exp" data-braintree-name="expiration_date">
                                                               <input type="submit" id="edit-order-submit" value="Submit" name="add-order-submit" style="color: rgb(255, 255, 255); margin-top: 20px; background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px; margin-left: 20px;" />
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

<script>
var regular_makes;
var regular_models;
var regular_packs = [];
var classic_makes;
var classic_models;
var regularindex = 0;
var classicindex = 0;
var first_reg_model;
var first_reg_make;
var first_cl_model;
var first_cl_make;
var total_price = "<?php echo $getorder->total_price; ?>";
var first_wash_check = "<?php echo $first_wash_check; ?>";
var first_time_wash = 0;
if(first_wash_check == 0) first_time_wash = 1;
$(function(){

$.getJSON("../api/index.php?r=vehicles/vehiclemakes", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {

		var vals = [];


				makes = data.vehicle_makes.join(",");
firstmake = '';
				vals = makes.split(",");
firstmake = vals[0];
  first_reg_make = vals[0];

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
if(index == 0) first_reg_model = mod[0];
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
    content += "<div class='regular-car-box' id='regular-car-box-"+regularindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='car_makes[]' class='form-control regular-make' style='width: 300px;'>";
    content += regular_makes;
    content += "</select><label class='control-label'>Model</label><select name='car_models[]' class='form-control regular-model' style='width: 300px;'>";
    content += regular_models;
    content += "</select><label class='control-label'>Package</label><select name='car_packs[]' class='form-control regular-pack' style='width: 300px;'><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><input type='hidden' id='car_type' name='car_types[]' value='regular'><input type='hidden' id='handle_fee' name='handle_fees[]' value='1'><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Full Exterior Hand Wax (Liquid form)</p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='10' /> $10 Dressing of all Exterior Plastics</p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='35' /> $35 Full Exterior Clay Bar</p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal</p><p class='pet_fee_el' style='margin-top: 20px;'><input type='checkbox' id='pet_fee' value='5' /> $5 Pet Hair Fee</p><p class='lifted_truck_el' style='margin-top: 20px;'><input type='checkbox' id='lifted_truck_fee' value='5' /> $5 Lifted Truck Fee</p><input type='hidden' name='pet_fees[]' id='pet_fees' value='0' /><input type='hidden' name='truck_fees[]' id='truck_fees' value='0' /><input type='hidden' id='exthandwaxes' name='exthandwaxes[]' value='0'><input type='hidden' id='extplasticdressings' name='extplasticdressings[]' value='0'><input type='hidden' id='extclaybars' name='extclaybars[]' value='0'><input type='hidden' id='waterspotremoves' name='waterspotremoves[]' value='0'><input type='hidden' name='car_ids[]' id='car_id' value='0' />";
    if(first_time_wash == 1){
      content += "<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />";
       first_time_wash = 0;
    }
    content += "<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>";

    $(".regular-vehicles-wrap").append(content);

    return false;

});

$(".classic-add-trigger").click(function(){
    var content = '';
classicindex++;
    content += "<div class='classic-car-box' id='classic-car-box-"+classicindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='car_makes[]' class='form-control classic-make' style='width: 300px;'>";
    content += classic_makes;
    content += "</select><label class='control-label'>Model</label><select name='car_models[]' class='form-control classic-model' style='width: 300px;'>";
    content += classic_models;
    content += "</select><label class='control-label'>Package</label><select name='car_packs[]' class='form-control classic-pack' style='width: 300px;'><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><input type='hidden' id='car_type' name='car_types[]' value='classic'><input type='hidden' id='handle_fee' name='handle_fees[]' value='1'><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Full Exterior Hand Wax (Liquid form)</p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='10' /> $10 Dressing of all Exterior Plastics</p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='35' /> $35 Full Exterior Clay Bar</p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal</p><p style='margin-top: 20px;' class='pet_fee_el'><input type='checkbox' id='pet_fee' value='5' /> $5 Pet Hair Fee</p><p class='lifted_truck_el' style='margin-top: 20px;'><input type='checkbox' id='lifted_truck_fee' value='5' /> $5 Lifted Truck Fee</p><input type='hidden' name='pet_fees[]' id='pet_fees' value='0' /><input type='hidden' name='truck_fees[]' id='truck_fees' value='0' /><input type='hidden' id='exthandwaxes' name='exthandwaxes[]' value='0'><input type='hidden' id='extplasticdressings' name='extplasticdressings[]' value='0'><input type='hidden' id='extclaybars' name='extclaybars[]' value='0'><input type='hidden' id='waterspotremoves' name='waterspotremoves[]' value='0'><input type='hidden' name='car_ids[]' id='car_id' value='0' />";
    if(first_time_wash == 1){
      content += "<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />";
       first_time_wash = 0;
    }
    content += "<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p></div>";

    $(".classic-vehicles-wrap").append(content);

    return false;

});

$( "#phone-order-form" ).on( "click", ".regular-car-remove", function() {

   $(this).parent().parent().remove();

   if (($('.regular-vehicles-wrap').children().length < 1) && ($('.classic-vehicles-wrap').children().length < 1)) {
        if(first_wash_check == 0) first_time_wash = 1;
   }
   return false;
});

$( "#phone-order-form" ).on( "click", ".classic-car-remove", function() {
   $(this).parent().parent().remove();
   if (($('.regular-vehicles-wrap').children().length < 1) && ($('.classic-vehicles-wrap').children().length < 1)) {
        if(first_wash_check == 0) first_time_wash = 1;
   }
   return false;
});

$( "#phone-order-form" ).on( "change", ".regular-pack, .classic-pack", function() {
   if($(this).val() == 'Deluxe'){
$(this).parent().find('.extclaybar input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extclaybar span').removeClass( "checked");
$(this).parent().find('#extclaybars').val(0);
$(this).parent().find('.waterspotremove input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.waterspotremove span').removeClass( "checked");
$(this).parent().find('#waterspotremoves').val(0);

$(this).parent().find('.extclaybar, .waterspotremove').hide();
$(this).parent().find('.exthandwax, .extplasticdressing').show();
first_dis = $(this).parent().find('#first_discs').val();

//console.log(first_dis);
if(first_dis != '0') $(this).parent().find('#first_discs').val('5');


}

   if($(this).val() == 'Premium'){
$(this).parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.exthandwax span').removeClass( "checked");
$(this).parent().find('#exthandwaxes').val(0);
$(this).parent().find('.extplasticdressing input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.extplasticdressing span').removeClass( "checked");
$(this).parent().find('#extplasticdressings').val(0);

$(this).parent().find('.exthandwax, .extplasticdressing').hide();
$(this).parent().find('.extclaybar, .waterspotremove').show();

first_dis = $(this).parent().find('#first_discs').val();

//console.log(first_dis);
if(first_dis != '0') $(this).parent().find('#first_discs').val('10');

}
   return false;
});



$( "#phone-order-form" ).on( "click", ".pet_fee_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#pet_fees').val($(this).val());
}
else {
$(this).parent().parent().find('#pet_fees').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".pet_fee_el #uniform-pet_fee input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#pet_fees').val($(this).val());
}
else {

$(this).parent().parent().parent().parent().find('#pet_fees').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".lifted_truck_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#truck_fees').val($(this).val());
}
else {
$(this).parent().parent().find('#truck_fees').val(0);
}

});


$( "#phone-order-form" ).on( "click", ".lifted_truck_el #uniform-lifted_truck_fee input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#truck_fees').val($(this).val());
}
else {
$(this).parent().parent().parent().parent().find('#truck_fees').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".exthandwax input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#exthandwaxes').val($(this).val());
}
else {
$(this).parent().parent().find('#exthandwaxes').val(0);
}

});


$( "#phone-order-form" ).on( "click", ".exthandwax #uniform-exthandwax input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#exthandwaxes').val($(this).val());
}
else {
$(this).parent().parent().parent().parent().find('#exthandwaxes').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".extplasticdressing input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#extplasticdressings').val($(this).val());
}
else {
$(this).parent().parent().find('#extplasticdressings').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".extplasticdressing #uniform-extplasticdressing input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#extplasticdressings').val($(this).val());
}
else {
$(this).parent().parent().parent().parent().find('#extplasticdressings').val(0);
}

});


$( "#phone-order-form" ).on( "click", ".extclaybar input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#extclaybars').val($(this).val());
}
else {
$(this).parent().parent().find('#extclaybars').val(0);
}

});


$( "#phone-order-form" ).on( "click", ".extclaybar #uniform-extclaybar input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#extclaybars').val($(this).val());
}
else {
$(this).parent().parent().parent().parent().find('#extclaybars').val(0);
}

});


$( "#phone-order-form" ).on( "click", ".waterspotremove input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#waterspotremoves').val($(this).val());
}
else {
$(this).parent().parent().find('#waterspotremoves').val(0);
}

});

$( "#phone-order-form" ).on( "click", ".waterspotremove #uniform-waterspotremove input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#waterspotremoves').val($(this).val());
}
else {
$(this).parent().parent().parent().parent().find('#waterspotremoves').val(0);
}

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

});


</script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="../js/jquery.maskedinput.js"></script>
<script>
$(function(){

  $("#phone-order-form #cphone").mask("(999) 999-9999");

  $(".cust_locations li input").click(function(){
$(".add-address-wrap").hide();
$(".add-address-wrap input, .add-address-wrap select").attr('disabled', 'disabled');
$(".add-address-wrap #caddress, .add-address-wrap #ccity, .add-address-wrap #cstate, .add-address-wrap #czip").val('');

$("#loc_id").val($(this).val());
});

$(".loc-add-trigger").click(function(){
$(".add-address-wrap").slideDown();
$(".add-address-wrap input, .add-address-wrap select").removeAttr('disabled');
$(".cust_locations li input").removeAttr('checked');
$(".cust_locations li span").removeClass('checked');
$("#loc_id").val('');
return false;
});

$(".pay-methods li input").click(function(){
$(".add-card-wrap").hide();
$(".add-card-wrap input").removeAttr('required');
$(".add-card-wrap input").val('');
$("#pay_method_token").val($(this).val());
$("#card_ending_no").val($(this).parent().parent().parent().parent().data('ending'));
$("#card_type").val($(this).parent().parent().parent().parent().data('type'));
});

$(".paymethod-add-trigger").click(function(){
$(".add-card-wrap").slideDown();
$(".add-card-wrap input").attr('required', 'required');
$(".pay-methods li input").removeAttr('checked');
$(".pay-methods li span").removeClass('checked');
$("#pay_method_token").val('');
$("#card_ending_no").val('');
$("#card_type").val('');
return false;
});

});
</script>
<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>

var token = "<?php echo $clientToken; ?>";

braintree.setup(token, 'custom', {id: 'phone-order-form', onPaymentMethodReceived: function(payload) {
 var nonce = payload.nonce;


 if (nonce)
                {

$("#phone-order-form #bt_number").val($("#phone-order-form #ccno").val());
exp_date = $("#phone-order-form #ccexpmo").val()+"/"+ $("#phone-order-form #ccexpyr").val();
$("#phone-order-form #bt_exp").val(exp_date);

                  var form = document.getElementById('phone-order-form');
                    var payment_method_nonce = document.createElement('input');
                    payment_method_nonce.name = 'payment_method_nonce';
                    payment_method_nonce.type = 'hidden';
                    payment_method_nonce.value = nonce;
                    form.appendChild(payment_method_nonce);
                    form.submit();
                }
}});


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