<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
  header("Location: http://www.devmobilewash.com/admin-new/edit-order.php?id=".$_GET['id']);
die();
}
require_once('../api/protected/vendors/braintree/lib/Braintree.php');
include('header.php');

/*Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');*/

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('czckz7jkzcnny4jj');
Braintree_Configuration::publicKey('zwcjr8h49b5j5s96');
Braintree_Configuration::privateKey('1d9f980b86df0a4d0e0ce3253970a8ee');

$clientToken = Braintree_ClientToken::generate();

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

$rootpath = '/home/devmobilewash/public_html/admin-new/all-orders-img/';
if(!is_dir($rootpath.$_GET['id'])){
mkdir($rootpath.$_GET['id'], 0777, true);
}

if($_GET['car-remove']){
unlink($rootpath.$_GET['id']."/".$_GET['car-remove']);

 echo "<script type='text/javascript'>window.location = 'edit-order.php?id=".$_GET['id']."';</script>";
exit;
}

$handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('wash_request_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
           $getorder_response = $jsondata->response;
$getorder_result_code = $jsondata->result;
$getorder = $jsondata;


$handle = curl_init(ROOT_URL."/api/index.php?r=agents/profiledetails");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('agent_id' => $getorder->agent_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $profiledetail = json_decode($result);


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

   //$handle = curl_init(ROOT_URL."/api/index.php?r=agents/allagents");
   $handle = curl_init(ROOT_URL."/api/index.php?r=agents/allagents_formatted");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

$allagents = $jsondata->agents;

$first_wash_check = 0;
$first_time_wash = 0;
$org_wash_points = 0;
$wash_points = 0;
$del_wash_count = 0;
$prem_wash_count = 0;
$ordererror = '';
$lat = '';
$long = '';
$full_address = '';
$address_type = '';
$city = '';
$state = '';
$zipcode = '';

if(isset($_POST['payment_method_nonce'])){
    
    if(!empty($_POST['loc_id'])){

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getlocationbyid");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("customer_id" => $getorder->customer_id, "location_id" => $_POST['loc_id'], 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

  $lat = $jsondata->location_details->lat;
$long = $jsondata->location_details->lng;

$full_address = $jsondata->location_details->address;
$address_type = $jsondata->location_details->title;
$city = $jsondata->location_details->city;
$state = $jsondata->location_details->state;
$zipcode = $jsondata->location_details->zipcode;

}
else{

$address = $_POST['caddress'];

$address_temp = $_POST['caddress'].", ".$_POST['ccity'].", ".$_POST['cstate']." ".$_POST['czip'];



 $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address_temp)."&sensor=true&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
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
  $ordererror = "Error in adding location.";
//header('location: https://www.mobilewash.com/admin-new/add-schedule-order.php?step=2');
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
        $ordererror = "Sorry, Mobile Wash is currently not available in your area. Please register to find out when we're available in your area!";
//header('location: https://www.mobilewash.com/admin-new/add-schedule-order.php?step=2');
//die();

}

else{


$full_address = $address_temp;
$city = $_POST['ccity'];
$state = $_POST['cstate'];
$zipcode = $_POST['czip'];

    $full_address = trim($full_address);
      //echo $fulladdress;
    $encode_address = urlencode($full_address);

    /* --- Geocode lat long --- */

    $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".$encode_address."&sensor=true&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
    $ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//var_dump($geojsondata);
if($geojsondata->status == 'ZERO_RESULTS'){
          $ordererror = "Error in adding location.";
//header('location: https://www.mobilewash.com/admin-new/add-schedule-order.php?step=2');
//die();
}
else{
 $lat = $geojsondata->results[0]->geometry->location->lat;
$long = $geojsondata->results[0]->geometry->location->lng;

}

$address_type = $_POST['address_type'];

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/addlocation");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("customer_id" => $getorder->customer_id, "wash_request_id" => $getorder->id, "location_title" => $_POST['address_type'], "location_address" => $full_address, 'actual_latitude'=> $lat, 'actual_longitude' => $long, 'city' => $_POST['ccity'], 'state' => $_POST['cstate'], 'zipcode' => $_POST['czip'], 'admin_username' => $jsondata_permission->user_name, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

}

}

$old_car_list = explode(",", $getorder->car_list);
if(count($_POST['car_makes'])){
$car_ids = '';
$car_packs = '';
$pet_hair_vehicles = '';
$lifted_vehicles = '';
$exthandwax_vehicles = '';
$extplasticdressing_vehicles = '';
$extclaybar_vehicles = '';
$waterspotremove_vehicles = '';
$upholstery_vehicles = '';
$floormat_vehicles = '';
$fifthwash_vehicles = '';

foreach($_POST['car_makes'] as $ind=>$make){
    $car_id = 0;
    $addi_detail = '';
    $addon_detail = '';
if($_POST['car_ids'][$ind] == 0){
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/addvehicle");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('customer_id' => $getorder->customer_id, 'brand_name' => $make, 'model_name' => $_POST['car_models'][$ind], 'car_pack' => $_POST['car_packs'][$ind], 'vehicle_image' => ROOT_URL.'/api/images/veh_img/defimage.png', 'vehicle_build' => $_POST['car_types'][$ind], 'add_log' => 'true', 'admin_username' => $jsondata_permission->user_name, 'wash_request_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
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

$car_ids .= $car_id.",";
$car_packs .=  $_POST['car_packs'][$ind].",";
$pet_hair_vehicles_custom = 0.00;
if($_POST['pet_fees'][$ind] != 0) {
    $pet_hair_vehicles .=  $car_id.",";
    $addon_detail .= 'Extra Cleaning, ';
    $pet_hair_vehicles_custom = $_POST['pet_fees'][0];
}
if($_POST['truck_fees'][$ind] != 0) {
    $lifted_vehicles .=  $car_id.",";
    $addon_detail .= 'Lifted, ';
}
if($_POST['exthandwaxes'][$ind] != 0) {
    $exthandwax_vehicles .=  $car_id.",";
    $addon_detail .= 'Wax, ';
}
if($_POST['extplasticdressings'][$ind] != 0) {
    $extplasticdressing_vehicles .=  $car_id.",";
    $addon_detail .= 'Dressing, ';
}
if($_POST['extclaybars'][$ind] != 0) {
    $extclaybar_vehicles .=  $car_id.",";
    $addon_detail .= 'Clay, ';
}
if($_POST['waterspotremoves'][$ind] != 0) {
    $waterspotremove_vehicles .=  $car_id.",";
    $addon_detail .= 'Water Spot, ';
}
if($_POST['upholstery'][$ind] != 0) {
    $upholstery_vehicles .=  $car_id.",";
    $addon_detail .= 'Upholstery, ';
}
if($_POST['floormat'][$ind] != 0) {
    $floormat_vehicles .=  $car_id.",";
    $addon_detail .= 'Floormat, ';
}
if($_POST['fifth_discs'][$ind] != 0) {
    $fifthwash_vehicles .=  $car_id.",";
}

$addon_detail = rtrim($addon_detail, ", ");

if(isset($_POST['is_package_changed'][$ind]) && ($_POST['is_package_changed'][$ind] == 1)){
    $addi_detail = $make." ".$_POST['car_models'][$ind]." ".$_POST['car_packs'][$ind];
   $handle = curl_init(ROOT_URL."/api/index.php?r=site/addcustomlog");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('wash_request_id' => $getorder->id, 'admin_username' => $jsondata_permission->user_name, 'action' => 'adminchangepack', 'addi_detail' => $addi_detail, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle); 
}

if(isset($_POST['is_addons_changed'][$ind]) && ($_POST['is_addons_changed'][$ind] == 1)){
    
    $addi_detail = $make." ".$_POST['car_models'][$ind]." Addons: ".$addon_detail;
   $handle = curl_init(ROOT_URL."/api/index.php?r=site/addcustomlog");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('wash_request_id' => $getorder->id, 'admin_username' => $jsondata_permission->user_name, 'action' => 'adminchangeaddons', 'addi_detail' => $addi_detail, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle); 
}

}

  $car_ids = rtrim($car_ids, ',');
  $car_packs = rtrim($car_packs, ',');
 $pet_hair_vehicles = rtrim($pet_hair_vehicles, ',');
 $lifted_vehicles = rtrim($lifted_vehicles, ',');
 $exthandwax_vehicles = rtrim($exthandwax_vehicles, ',');
 $extplasticdressing_vehicles = rtrim($extplasticdressing_vehicles, ',');
 $extclaybar_vehicles = rtrim($extclaybar_vehicles, ',');
 $waterspotremove_vehicles = rtrim($waterspotremove_vehicles, ',');
 $upholstery_vehicles = rtrim($upholstery_vehicles, ',');
 $floormat_vehicles = rtrim($floormat_vehicles, ',');
 $fifthwash_vehicles = rtrim($fifthwash_vehicles, ',');
 
 $new_car_list = explode(",", $car_ids);
 
 foreach($getorder->vehicles as $vehicle){
    if(($key = array_search($vehicle->id, $new_car_list)) === false) {
    $addi_detail = $vehicle->brand_name." ".$vehicle->model_name;
   $handle = curl_init(ROOT_URL."/api/index.php?r=site/addcustomlog");
curl_setopt($handle, CURLOPT_POST, true);
$data = array('wash_request_id' => $getorder->id, 'admin_username' => $jsondata_permission->user_name, 'action' => 'adminremovecar', 'addi_detail' => $addi_detail, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle); 
    
    }
 }

}


$data = array("wash_request_id" => $_GET['id'], "car_ids_org" => $getorder->car_list, "car_ids" => $car_ids, "car_packs" => $car_packs, "pet_hair_vehicles" => $pet_hair_vehicles, "pet_hair_vehicles_custom"=> $pet_hair_vehicles_custom, "lifted_vehicles" => $lifted_vehicles, "exthandwax_vehicles" => $exthandwax_vehicles, "extplasticdressing_vehicles" => $extplasticdressing_vehicles, "extclaybar_vehicles" => $extclaybar_vehicles, "waterspotremove_vehicles" => $waterspotremove_vehicles, "upholstery_vehicles" => $upholstery_vehicles, "floormat_vehicles" => $floormat_vehicles, "fifthwash_vehicles" => $fifthwash_vehicles, "tip_amount" => $_POST['ctip'], "full_address" => $full_address, "address_type" => $address_type, "city" => $city, "state" => $state, "zipcode" => $zipcode, "lat" => $lat, "lng" => $long, "admin_command" => "update-order", 'promo_code' => $_POST['promo_code'], "admin_username" => $jsondata_permission->user_name, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

//print_r($data);

  $handle_data = curl_init(ROOT_URL."/api/index.php?r=site/updatewashadmin");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$add_jsondata = json_decode($result);
  if($add_jsondata->result == 'false'){
      $ordererror = $add_jsondata->response;
  }
//print_r($add_jsondata);
//exit;

if(!empty($_POST['pay_method_token'])){
    $paymentdata = array('customer_id' => $getorder->customer_id, 'nonce'=> $_POST['payment_method_nonce'], 'payment_method_token'=> $_POST['pay_method_token'], 'amount' => $getorder->net_price, 'company_amount' => $getorder->company_total, "admin_username" => $jsondata_permission->user_name, "wash_request_id" => $_GET['id'], 'is_token_changed' => $_POST['is_token_changed'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
 
//echo "working";
//echo $total;
//echo $company_total;

 }
 else{
  $paymentdata = array('customer_id' => $getorder->customer_id, 'nonce'=> $_POST['payment_method_nonce'], 'amount' => $getorder->net_price, 'company_amount' => $getorder->company_total, 'cardno' => $_POST['ccno'], 'cardname' => $_POST['ccname'], 'cvv' => $_POST['cccvc'], 'mo_exp' => $_POST['ccexpmo'], 'yr_exp' => $_POST['ccexpyr'], 'bill_straddress' => $_POST['bill_address'], 'bill_apt' => $_POST['bill_apt'], 'bill_city' => $_POST['bill_city'], 'bill_state' => $_POST['bill_state'], 'bill_zip' => $_POST['bill_zipcode'], "admin_username" => $jsondata_permission->user_name, "wash_request_id" => $_GET['id'], 'is_token_changed' => $_POST['is_token_changed'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

 }

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

  $ordererror = $afterpay_response;
//header('location: https://www.mobilewash.com/admin-new/add-schedule-order.php?step=2');
//die();
}


}


$handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('wash_request_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
           $getorder_response = $jsondata->response;
$getorder_result_code = $jsondata->result;
$getorder = $jsondata;

$per_car_wash_points_arr = explode(",", $getorder->per_car_wash_points);


$handle_data = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('customerid' => $getorder->customer_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$custdetails = json_decode($result);
$first_wash_check = $custdetails->is_first_wash;
//if($first_wash_check == 0) $first_time_wash = 1;
$org_wash_points = $custdetails->wash_points;
$wash_points = $custdetails->wash_points;

if($org_wash_points >=5 && $wash_points>=5){
$org_wash_points = 0;
$wash_points = 0;
}

$handle_data = curl_init(ROOT_URL."/api/index.php?r=agents/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('agent_id' => $getorder->agent_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$agentdetails = json_decode($result);
  $transaction_details = '';

 if($getorder->transaction_id){
   $handle_data = curl_init(ROOT_URL."/api/index.php?r=users/gettransactionbyid");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('customer_id' => $getorder->customer_id, 'wash_request_id' => $_GET['id'], 'transaction_id' => $getorder->transaction_id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$transaction_details = json_decode($result);
//print_r($transaction_details);
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
 <link rel="stylesheet" href="assets/global/plugins/jquery-ui/jquery-ui.min.css">

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
bottom: -25px;
}

.bx-wrapper .bx-pager.bx-default-pager a:hover, .bx-wrapper .bx-pager.bx-default-pager a.active {
    background: #111;
}

.bxslider{
    margin-top: 20px;
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

.addon-checked{
margin-top: 20px;
    background: #ffdbdb;
    padding: 10px;
    border: 1px solid red;
}


.sec-heading {
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


.admin-edit-alert{
background: red;
color: #fff;
padding: 10px;
display: none;
    margin-bottom: 15px;
    font-size: 16px;
}

.label-busy {
    background-color: #FF8C00 !important;
}
.label-online {
    background-color: #16CE0C !important;
}
.label-offline {
    background-color: #FF0202 !important;
}

.label-cancel {
    background-color: #999 !important;
}

.orange-btn{
    font-size: 18px;
    margin-top: 3px;
    cursor: pointer;
    background: #e47e00;
    color: #fff;
    padding: 8px 20px;
    margin-bottom: 15px;
    text-align: center;
}

.points-holder{
    margin-top: 15px !important;
    margin-bottom: 0;
}


  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 200px;
  }

  .error{
          background: #d40000;
          color: #fff;
          padding: 6px;
          display: block !important;
  }

   .success{
          background: green;
          color: #fff;
          padding: 6px;
          display: block !important;
  }

  .status-text{
      display: none;
  }


.red-border{
    border: 2px solid red;
}
.ui-dialog-titlebar-close{
	padding: 0;
	color: #000;
	height: 25px !important;
    width:25px !important;
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

<div class="admin-edit-alert"></div>
<div style="float: right; font-size: 18px; background: #fff; padding: 10px; max-width: 300px;" <?php if(($getorder->transaction_id) && ($transaction_details->transaction_details->amount != $getorder->net_price) && (($transaction_details->transaction_details->status == 'submitted_for_settlement') || ($transaction_details->transaction_details->status == 'settling') || ($transaction_details->transaction_details->status == 'settled'))) echo "class='red-border'"; ?>>
<div style="font-size: 18px; background: #006fcf; color: #fff; padding: 5px 10px;">

<span style="font-weight: 500;font-size: 22px; display: block; float: right;"><?php if($getorder->status == 5 || $getorder->status == 6) {echo "$".number_format($getorder->cancel_fee, 2); }else{if($getorder->net_price > 0) {$net_total = $getorder->net_price - $getorder->company_discount; echo "$".$net_total;} else {echo "N/A";}} ?></span>
<span style="display: block; float: right; margin-top: 4px; margin-right: 5px;">TOTAL PRICE:</span>
<?php if(($getorder->wash_now_fee > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(Wash Now Fee: <?php echo "+$".number_format($getorder->wash_now_fee, 2); ?>)</span><?php endif; ?><?php if(($getorder->wash_later_fee > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(Surge Fee: <?php echo "+$".number_format($getorder->wash_later_fee, 2); ?>)</span><?php endif; ?><?php if(($getorder->transaction_fee > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(Transaction Fee: <?php echo "$".number_format($getorder->transaction_fee, 2); ?>)</span><?php endif; ?><?php if(($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(Service Fee: +$1.00)</span><?php endif; ?><?php if(($getorder->tip_amount > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(Tip: <?php echo "+$".number_format($getorder->tip_amount, 2); ?>)</span><?php endif; ?><?php if(($getorder->coupon_discount > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(<?php echo $getorder->coupon_code; ?>: <?php echo "-$".number_format($getorder->coupon_discount, 2); ?>)</span><?php endif; ?><?php if(($getorder->company_discount > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">Company Discount: <?php echo "-$".number_format($getorder->company_discount, 2); ?></span><?php endif; ?><?php if(($getorder->fifth_wash_discount > 0) && ($getorder->status != 5) && ($getorder->status != 6)): ?> <span style="font-size: 18px; display: block; text-align: right; clear: both;">(5th Wash Discount: <?php echo "-$".number_format($getorder->fifth_wash_discount, 2); ?>)</span><?php endif; ?>
<span style="font-weight: 500;font-size: 16px; display: block; clear: both; text-align: right; margin-top: 10px;">Agent Total: <?php if($getorder->status == 5 || $getorder->status == 6) {echo "$".number_format($getorder->washer_cancel_fee, 2); } else{if($getorder->agent_total > 0) {echo "$".$getorder->agent_total;} else {echo "N/A";}} ?></span>
<span style="font-weight: 500;font-size: 16px; display: block; clear: both; text-align: right;">Company Total: <?php if($getorder->status == 5 || $getorder->status == 6) {if($getorder->washer_cancel_fee > 0) {echo "$".number_format($getorder->washer_cancel_fee / 2, 2);} else {echo "$".number_format($getorder->cancel_fee, 2);}} else{if($getorder->company_total > 0) {$net_company_total = $getorder->company_total - $getorder->company_discount; echo "$".number_format($net_company_total, 2);} else {echo "N/A";}} ?></span>
<div style="clear: both;"></div>
<?php if($getorder->payment_type == 'free'): ?>
<p style="margin: 5px 0; text-align: center;">(Free wash applied)</p>
<?php endif; ?>
</div>
<?php if(($getorder->transaction_id) && ($transaction_details->transaction_details->amount != $getorder->net_price) && (($transaction_details->transaction_details->status == 'submitted_for_settlement') || ($transaction_details->transaction_details->status == 'settling') || ($transaction_details->transaction_details->status == 'settled'))): ?>
<p style="margin: 0; font-size: 16px; text-align: center; font-weight: bold; margin-top: 8px; color: red;">Braintree Custom Payment</p>
<?php endif; ?>
</div>
<?php if($getorder->status == 4): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; background: #05b500; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;">Order Complete</div>
<?php elseif($getorder->status == 5 || $getorder->status == 6): ?>
<div class="process-payment-trigger" style="float: right; font-size: 18px; margin-top: 3px; background: #999; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;">Cancelled <?php if($getorder->cancel_fee) echo "($".number_format($getorder->cancel_fee, 2).")"; ?></div>
<?php elseif($getorder->status == 0): ?>
<div class="process-payment-trigger" style="float: right; font-size: 18px; margin-top: 3px; background: red; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;">Pending</div>
<?php else: ?>
<div class="process-payment-trigger" style="float: right; font-size: 18px; margin-top: 3px; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;">In Process</div>
<?php endif; ?>
<?php if(!$getorder->admin_submit_for_settle): ?>
<?php if(($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin')): ?>
<?php if(($getorder->status == 5) || ($getorder->status == 6)): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="wash-uncancel">Un-Cancel</div>
<?php endif; ?>
<?php if(($getorder->status == 4)): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="process-free-wash">Free Wash</div>
<?php endif; ?>
<?php /*
<?php if(!$getorder->washer_payment_status): ?>
														<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="stop-washer-pay">Stop Washer Payment</div>
															<?php endif; ?>
																<?php if($getorder->washer_payment_status == 2): ?>
														<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="enable-washer-pay">Enable Washer Payment</div>
															<?php endif; ?> */ ?>
<?php endif; ?>
<?php endif; ?>

<?php if((!$getorder->transaction_id) || (($transaction_details->transaction_details->status != 'authorized') && ($transaction_details->transaction_details->status != 'submitted_for_settlement') && ($transaction_details->transaction_details->status != 'settling') && ($transaction_details->transaction_details->status != 'settled') && (!$getorder->admin_submit_for_settle))): ?>

<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e42400; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="process-payment">Process Payment</div>
<?php else: ?>

<?php if(($transaction_details->transaction_details->escrow_status == 'hold_pending' || $transaction_details->transaction_details->escrow_status == 'held') && $transaction_details->transaction_details->status == 'settled' && (!$getorder->admin_submit_for_settle)): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="release-payment">Release Payment</div>
<?php endif; ?>

 <?php if($transaction_details->transaction_details->escrow_status == 'released' && $transaction_details->transaction_details->status == 'settled'): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; background: #05b500; color: #fff; padding: 8px 35px; margin-right: 20px;">Payment Complete</div>
<?php endif; ?>
<?php if(!$transaction_details->transaction_details->escrow_status && $transaction_details->transaction_details->status == 'settled'): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; background: #05b500; color: #fff; padding: 8px 35px; margin-right: 20px;">Payment Complete</div>
<?php endif; ?>
<?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php if(($transaction_details->transaction_details->status == 'submitted_for_settlement' || $transaction_details->transaction_details->status == 'authorized') && (!$getorder->admin_submit_for_settle)): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="void-payment">Void Payment</div>
<?php endif; ?>
<?php if(($transaction_details->transaction_details->status == 'settling' || $transaction_details->transaction_details->status == 'settled') && (!$getorder->admin_submit_for_settle)): ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #e47e00; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="refund-payment">Refund Payment</div>
 <?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if($getorder->status != 5 && $getorder->status != 6 && (!$getorder->admin_submit_for_settle)): ?>
 <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>

<div style="float: right; font-size: 18px; margin-top: 3px; background: #e47e00; cursor: pointer; color: #fff; padding: 8px 35px; margin-right: 20px;" class="<?php if($getorder->status ==0) {echo "cancel-order";} else {echo "cancel-order-ondemand";}; ?>">Cancel Order</div>

<?php endif; ?>
<?php endif; ?>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #006fcf; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="client-receipt-send">Client Receipt</div>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #006fcf; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="agent-receipt-send">Detailer Receipt</div>
<div style="float: right; font-size: 18px; margin-top: 3px; cursor: pointer; background: #006fcf; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="company-receipt-send">Company Receipt</div>
<div style="clear: both;"></div>

<div style="clear: both;"></div>
<div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">ORDER #0000<?php echo $_GET['id']; ?></span>

                                                </div>
<p class="err-text"></p>
<?php if($_GET['action'] == 'payment-error'): ?>
<p style="text-align: left; clear: both; margin-top: 0; background: #d40000; color: #fff; padding: 10px;">Error in processing payment. Please try again.</p>
<?php endif; ?>
<?php if($ordererror): ?>
<p style="text-align: left; clear: both; margin-top: 0; background: #d40000; color: #fff; padding: 10px;"><?php echo $ordererror; ?></p>
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
                                                            <input type="text" name="cname" id="cname" style="width: 300px;" class="form-control" value="<?php echo $custdetails->customername; ?>" readonly required />
                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label" style="margin-top: 0;">Schedule Date<span style="color: red;">*</span></label>
                                                        <?php if($getorder->is_scheduled): ?>
                                                        <input type="text" name="sdate" id="sdate" style="width: 300px;" class="form-control" value="<?php if($getorder->reschedule_time) {echo $getorder->reschedule_date;} else{echo $getorder->schedule_date;} ?>" required readonly />
                                                        <?php else: ?>
                                                        <input type="text" name="sdate" id="sdate" style="width: 300px;" class="form-control " value="N/A" readonly />
                                                        <?php endif; ?>

                                                        </div>
                                                     </div>


                                                            <div style="clear: both;"></div>
<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Phone Number<span style="color: red;">*</span></label>
                                                            <input type="text" name="cphone" id="cphone" style="width: 300px;" class="form-control" value="<?php echo $custdetails->contact_number; ?>" readonly required />
                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                             <label class="control-label">Schedule Time<span style="color: red;">*</span></label>
                                                             <?php if($getorder->is_scheduled): ?>
                                                            <input type="text" name="stime" id="stime" style="width: 300px;" class="form-control" value="<?php if($getorder->reschedule_time) {echo $getorder->reschedule_time;} else{echo $getorder->schedule_time;} ?>" required readonly />
                                                             <?php else: ?>
                                                        <input type="text" name="stime" id="stime" style="width: 300px;" class="form-control " value="N/A" readonly />
                                                        <?php endif; ?>
                                                       </div>
                                                     </div>

                                                            <div style="clear: both;"></div>
<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address<span style="color: red;">*</span></label>
                                                            <input type="text" name="caddress2" id="caddress2" style="width: 300px;" class="form-control" value="<?php echo $getorder->address." (".$getorder->address_type.")"; ?>" readonly required />
                                                        </div>
                                                     </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Email Address</label>
                                                     <input type="text" name="cemail" id="cemail" style="width: 300px;" class="form-control" value="<?php echo $custdetails->email; ?>" readonly />

                                                        </div>
                                                     </div>

                                                                            <div style="clear: both;"></div>
 <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Tip Amount</label>
                                                     <input type="text" name="ctip" id="ctip" style="width: 300px;" class="form-control custom_tip_amount" value="<?php if($getorder->tip_amount > 0) {echo $getorder->tip_amount;} else{echo 0;} ?>" onkeypress="return isNumberKey(event)" min="0" />

                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Washer Penalty Fee</label>
                                                     <input type="text" name="washer_penalty_fee" id="washer_penalty_fee" style="width: 300px;" class="form-control" value="<?php echo $getorder->washer_penalty_fee; ?>" readonly />

                                                        </div>
                                                     </div>
<div style="clear: both;"></div>
 <div class="col-md-6">
                                                        <div class="form-group">
 <label class="control-label">Promo Code</label>
                                                     <input type="text" name="promo_code" id="promo_code" style="width: 300px;" class="form-control" value="<?php echo $getorder->coupon_code; ?>" />

                                                        </div>
                                                     </div>
<div style="clear: both;"></div>
 <?php if($getorder->is_scheduled): ?>
 <h3 class="sec-heading" style="margin-top: 25px;">Re-Schedule Date & Time</h3>

  <div class="col-md-12">
                                                        <div class="form-group">
                                                             <label class="control-label" style="margin-top: 0;">Re-Schedule Date</label>

                                                        <input type="text" name="reschedule_date" id="reschedule_date" style="width: 300px;" class="form-control date-picker" value="" autocomplete="off" />

                                                        </div>
                                                     </div>

                                                      <div class="col-md-12">
                                                        <div class="form-group">
                                                             <label class="control-label">Re-Schedule Time</label>

                                                            <input type="text" name="reschedule_time" id="reschedule_time" style="width: 300px;" class="form-control timepicker timepicker-default" value="" autocomplete="off" />

                                                       </div>
                                                     </div>

                                                     <div class="col-md-12">
                                                        <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" class="reschedule_update" value="Save" /></p>
                                                       </div>
                                                     </div>
 <?php endif; ?>

  <h3 class="sec-heading" style="margin-top: 25px;">Device Info</h3>
  
  <p><strong>Customer:</strong> <?php if(count($custdetails->last_used_device)) {echo $custdetails->last_used_device[0]->device_name." / ".$custdetails->last_used_device[0]->device_type." ".$custdetails->last_used_device[0]->os_details;} else{echo "N/A";} ?></p>
  <p><strong>Washer:</strong> <?php if(count($agentdetails->last_used_device)) {echo $agentdetails->last_used_device[0]->device_name." / ".$agentdetails->last_used_device[0]->device_type." ".$agentdetails->last_used_device[0]->os_details;} else{echo "N/A";} ?></p>
  
 <h3 class="sec-heading" style="margin-top: 25px;">Location Info</h3>

                                                     <?php if(count($custdetails->customer_locations)): ?>

                                                     <ul class="cust_locations">
<?php
$first_loc_id = 0;
foreach($custdetails->customer_locations as $ind=> $loc): ?>
<li><input type="radio" <?php if($loc->location_address == $getorder->address) echo "checked"; ?> value="<?php echo $loc->location_id; ?>" name="saved_loc" class="cust_saved_loc" /> <strong><?php echo $loc->location_title; ?>:</strong> <?php echo $loc->location_address; ?> <a class="addr-remove" data-locid="<?php echo $loc->location_id; ?>" href="#" style="margin-left: 30px;">Remove</a></li>
<?php
if($loc->location_address == $getorder->address) $first_loc_id = $loc->location_id;
endforeach; ?>
</ul>

<input type="hidden" name="loc_id" id="loc_id" value="<?php if($first_loc_id) echo $first_loc_id; ?>" />
 <?php endif; ?>
 
 <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="loc-add-trigger" href="#">+ ADD NEW LOCATION</a></p>

  <div class="add-address-wrap">
  <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Address<span style="color: red;">*</span></label>
                                                            <input type="text" name="caddress" id="caddress" style="width: 300px;" class="form-control" required="" <?php if($first_loc_id) echo "disabled"; ?>>
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
  

                                                     <?php

                                                     $regular_vehicles = [];
                                                     $classic_vehicles = [];
$point_index = 0;
                                                        foreach($getorder->vehicles as $vehc){

                                                            if($vehc->vehicle_cat == 'regular') $regular_vehicles [] = $vehc;
                                                            if($vehc->vehicle_cat == 'classic') $classic_vehicles [] = $vehc;
                                                        }

                                                     ?>
                                                             <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="regular-add-trigger" href="#">+ ADD NEW VEHICLE</a></p>
                                                             <div class="regular-vehicles-wrap">
<?php if(count($regular_vehicles)): ?>
<?php
foreach($regular_vehicles as $ind => $veh): ?>
<?php
if($getorder->status != 4) $wash_points++;

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getvehiclebyid");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("vehicle_id" => $veh->id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$vehdata = json_decode($result);
      $clay_bar_price = 40;
      $floormat_price = 10;

if($vehdata->vehicle->vehicle_type == 'S') {
 $clay_bar_price = 40;
 $floormat_price = 10;
}
if($vehdata->vehicle->vehicle_type == 'M') {
  $clay_bar_price = 42.50;
  $floormat_price = 12.50;
}
if($vehdata->vehicle->vehicle_type == 'L') {
  $clay_bar_price = 45;
  $floormat_price = 15;
}
if($vehdata->vehicle->vehicle_type == 'E') {
    $clay_bar_price = 45;
    $floormat_price = 15;
}
?>
<div class='regular-car-box' id='regular-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>
<img style="width: 170px; clear: both; display: block; margin-top: 20px;" src="<?php if(strpos($veh->vehicle_image, ROOT_URL) !== false) {echo $veh->vehicle_image; } else{echo ROOT_URL."/api/images/veh_img/".$veh->vehicle_image; } ?>" alt="" />
<p style="margin-top: 20px;">Eco friendly waterless wash: <?php if($veh->eco_friendly == 1) {echo "Yes";} else {echo "No";} ?></p>
<label class='control-label'>Make</label>
<input type="text" name='car_makes[]' class='form-control regular-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh->brand_name; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='car_models[]' class='form-control regular-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh->model_name; ?>" />

<label class='control-label'>Type</label>
<input type="text" class='form-control classic-model' style='width: 300px; border: 0;' readonly value="<?php echo ucfirst($veh->vehicle_cat); ?>" />
<input type="hidden" name="car_types[]" value="<?php echo $veh->vehicle_cat; ?>" />
<label class='control-label'>Package</label>
<select name="car_packs[]" class="form-control regular-pack" style="width: 300px;">
<option value="Express" <?php if($veh->vehicle_washing_package == 'Express') echo "selected"; ?>>EXPRESS WASH</option>
<option value="Deluxe" <?php if($veh->vehicle_washing_package == 'Deluxe') echo "selected"; ?>>DELUXE WASH</option>
<option value="Premium" <?php if($veh->vehicle_washing_package == 'Premium') echo "selected"; ?>>PREMIUM DETAIL</option>
</select>

<?php if(($per_car_wash_points_arr[$point_index]) == 1): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 2): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 3): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 4): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 5): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img class="last" src="../images/blue-bubble2.png" /></p>
<?php endif; ?>

<label class='control-label'>Price</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo $veh->vehicle_washing_price; ?>" />
<!--<input type="hidden" name="car_prices[]" value="<?php echo $veh_detail[4]; ?>" />-->

<!--<label class='control-label'>Handling Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[7], 2); ?>" />-->
<input type="hidden" name="handle_fees[]" value="1" />

<?php if($veh->bundle_discount > 0): ?>
<label class='control-label'>Bundle Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$<?php echo number_format($veh->bundle_discount, 2); ?>" />
<?php endif; ?>
  <input type="hidden" name="bundle_discs[]" value="<?php echo $veh->bundle_discount; ?>" />

<p style="margin-top: 20px; <?php if($veh->vehicle_washing_package == 'Premium') echo 'display: none;'; ?>" class="exthandwax <?php if($veh->exthandwax_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="exthandwax" value="12" <?php if($veh->exthandwax_vehicle_fee > 0) echo "checked"; ?>> $12 Liquid Hand Wax </p>

<input type="hidden" name="exthandwaxes[]" id="exthandwaxes" value="<?php echo $veh->exthandwax_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if($veh->vehicle_washing_package == 'Premium') echo 'display: none;'; ?>" class="extplasticdressing <?php if($veh->extplasticdressing_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="extplasticdressing" value="8" <?php if($veh->extplasticdressing_vehicle_fee > 0) echo "checked"; ?>> $8 Exterior Plastic Dressing</p>
<input type="hidden" name="extplasticdressings[]" id="extplasticdressings" value="<?php echo $veh->extplasticdressing_vehicle_fee; ?>" />

<p style="margin-top: 20px;" class="extclaybar <?php if($veh->extclaybar_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="extclaybar" value="<?php if($veh->extclaybar_vehicle_fee > 0) {echo $veh->extclaybar_vehicle_fee;} else{ echo $clay_bar_price;} ?>" <?php if($veh->extclaybar_vehicle_fee > 0) echo "checked"; ?>> $<?php if($veh->extclaybar_vehicle_fee > 0) {echo $veh->extclaybar_vehicle_fee;} else{ echo $clay_bar_price;} ?> Clay Bar & Paste Wax<!--Clay Bar & Paste Wax--></p>
<input type="hidden" name="extclaybars[]" id="extclaybars" value="<?php echo $veh->extclaybar_vehicle_fee; ?>" />

<p style="margin-top: 20px;" class="waterspotremove <?php if($veh->waterspotremove_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="waterspotremove" value="30" <?php if($veh->waterspotremove_vehicle_fee > 0) echo "checked"; ?>> $30 Water Spot Removal</p>
<input type="hidden" name="waterspotremoves[]" id="waterspotremoves" value="<?php echo $veh->waterspotremove_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if(($veh->vehicle_washing_package == 'Premium') || ($veh->vehicle_washing_package == 'Express')) echo 'display: none;'; ?>" class="upholstery_el <?php if($veh->upholstery_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="upholstery_el" value="20" <?php if($veh->upholstery_vehicle_fee > 0) echo "checked"; ?>> $20 Upholstery Conditioning</p>

<input type="hidden" name="upholstery[]" id="upholstery" value="<?php echo $veh->upholstery_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if(($veh->vehicle_washing_package == 'Express')) echo 'display: none;'; ?>" class="floormat_el <?php if($veh->floormat_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="floormat_el" value="<?php if($veh->floormat_vehicle_fee > 0) {echo $veh->floormat_vehicle_fee;} else{ echo $floormat_price;} ?>" <?php if($veh->floormat_vehicle_fee > 0) echo "checked"; ?>> $<?php if($veh->floormat_vehicle_fee > 0) {echo $veh->floormat_vehicle_fee;} else{ echo $floormat_price;} ?> Floor Mat Cleaning</p>
<input type="hidden" name="floormat[]" id="floormat" value="<?php echo $veh->floormat_vehicle_fee; ?>" />

<p style="<?php if($veh->vehicle_washing_package == 'Express') echo 'display: none;'; ?>" class="pet_fee_el <?php if($veh->pet_hair_fee > 0) echo "addon-checked"; ?>" style="margin-top: 20px;"><input type="checkbox" id="pet_fee" value="10" <?php if($veh->pet_hair_fee > 0) echo "checked"; ?>> $<input type="text" id="pet_fees" name="pet_fees[]" class="custom_pet_fees" value="<?php echo $veh->pet_hair_fee; ?>" onkeypress="return isNumberKey(event)" min="0" /> Extra Cleaning Fee</p>


<p class="lifted_truck_el <?php if($veh->lifted_vehicle_fee > 0) echo "addon-checked"; ?>" style="margin-top: 20px;"><input type="checkbox" id="lifted_truck_fee" value="10" <?php if($veh->lifted_vehicle_fee > 0) echo "checked"; ?>> $10 Lifted Truck Fee</p>
<input type="hidden" name="truck_fees[]" id="truck_fees" value="<?php echo $veh->lifted_vehicle_fee; ?>" />



<?php if($getorder->first_wash_discount > 0 && $ind == 0): ?>
<div class='first-disc-wrap'><p>$<?php echo $getorder->first_wash_discount; ?> First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='<?php echo $getorder->first_wash_discount; ?>' /></div>
<?php $first_time_wash = 0; ?>
<?php else: ?>
  <div class='first-disc-wrap'></div>
<?php endif; ?>
<?php if($veh->fifth_wash_discount > 0){
    echo "<div class='fifth-disc-wrap'><p>$".$veh->fifth_wash_discount." Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='".$veh->fifth_wash_discount."' /></div>";
      $wash_points = 0;
}
else{
      echo "<div class='fifth-disc-wrap'><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='0' /></div>";
//echo "<input type='hidden' name='fifth_discs[]' value='".$veh_detail[10]."' />";
    } ?>

<input type="hidden" name="car_ids[]" value="<?php echo $veh->id; ?>" />
<input type="hidden" name="is_package_changed[]" class="ispackagechanged" value="0" />
<input type="hidden" name="is_addons_changed[]" class="isaddonschanged" value="0" />
<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>
<?php $point_index++; endforeach; ?>
<?php endif; ?>

                                                             </div>

                                                             </div>
                                                              <div class="col-md-6">
                                                             <p style="margin: 0; font-weight: bold; margin-top: 30px;"><a class="classic-add-trigger" href="#">+ ADD NEW CLASSIC</a></p>
                                                             <div class="classic-vehicles-wrap">

<?php if($classic_vehicles): ?>
<?php
foreach($classic_vehicles as $ind=>$veh): ?>
<?php
if($getorder->status != 4)  $wash_points++;
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getvehiclebyid");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array("vehicle_id" => $veh->id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$vehdata = json_decode($result);
      $clay_bar_price = 40;
       $floormat_price = 10;

if($vehdata->vehicle->vehicle_type == 'S') {
 $clay_bar_price = 40;
 $floormat_price = 10;
}
if($vehdata->vehicle->vehicle_type == 'M') {
  $clay_bar_price = 42.50;
  $floormat_price = 12.50;
}
if($vehdata->vehicle->vehicle_type == 'L') {
  $clay_bar_price = 45;
  $floormat_price = 15;
}
if($vehdata->vehicle->vehicle_type == 'E') {
    $clay_bar_price = 45;
    $floormat_price = 15;
}
?>
<div class='classic-car-box' id='classic-car-box-<?php echo $ind+1; ?>' style='border-top: 1px solid #ccc; margin-top: 20px;'>
<img style="width: 170px; clear: both; display: block; margin-top: 20px;" src="<?php if(strpos($veh->vehicle_image, ROOT_URL) !== false) {echo $veh->vehicle_image; } else{echo ROOT_URL."/api/images/veh_img/".$veh->vehicle_image; } ?>" alt="" />
<p style="margin-top: 20px;">Eco friendly waterless wash: <?php if($veh->eco_friendly == 1) {echo "Yes";} else {echo "No";} ?></p>
<label class='control-label'>Make</label>
<input type="text" name='car_makes[]' class='form-control classic-make' style='width: 300px; border: 0;' readonly value="<?php echo $veh->brand_name; ?>" />

<label class='control-label'>Model</label>
<input type="text" name='car_models[]' class='form-control classic-model' style='width: 300px; border: 0;' readonly value="<?php echo $veh->model_name; ?>" />

<label class='control-label'>Type</label>
<input type="text" class='form-control classic-model' style='width: 300px; border: 0;' readonly value="<?php echo ucfirst($veh->vehicle_cat); ?>" />
<input type="hidden" name="car_types[]" value="<?php echo $veh->vehicle_cat; ?>" />

<label class='control-label'>Package</label>
<select name="car_packs[]" class="form-control regular-pack" style="width: 300px;">
<option value="Express" <?php if($veh->vehicle_washing_package == 'Express') echo "selected"; ?>>EXPRESS WASH</option>
<option value="Deluxe" <?php if($veh->vehicle_washing_package == 'Deluxe') echo "selected"; ?>>DELUXE WASH</option>
<option value="Premium" <?php if($veh->vehicle_washing_package == 'Premium') echo "selected"; ?>>PREMIUM DETAIL</option>
</select>

<?php if(($per_car_wash_points_arr[$point_index]) == 1): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 2): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 3): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/gray-bubble.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 4): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img class="last" src="../images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$point_index] == 5): ?>
<p class="points-holder"><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img src="../images/blue-bubble2.png" /><img class="last" src="../images/blue-bubble2.png" /></p>
<?php endif; ?>


<label class='control-label'>Price</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo $veh->vehicle_washing_price; ?>" />
<!--<input type="hidden" name="car_prices[]" value="<?php echo $veh_detail[4]; ?>" />-->

<!--<label class='control-label'>Handling Fee</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="$<?php echo number_format($veh_detail[7], 2); ?>" />-->
<input type="hidden" name="handle_fees[]" value="1" />

<?php if($veh->bundle_discount > 0): ?>
<label class='control-label'>Bundle Discount</label>
<input type="text" class='form-control' style='width: 300px; border: 0;' readonly value="-$<?php echo number_format($veh->bundle_discount, 2); ?>" />

<?php endif; ?>
 <input type="hidden" name="bundle_discs[]" value="<?php echo $veh->bundle_discount; ?>" />

<p style="margin-top: 20px; <?php if($veh->vehicle_washing_package == 'Premium') echo 'display: none;'; ?>" class="exthandwax <?php if($veh->exthandwax_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="exthandwax" value="12" <?php if($veh->exthandwax_vehicle_fee > 0) echo "checked"; ?>> $12 Liquid Hand Wax<!--Liquid Hand Wax--> (Liquid form)</p>

<input type="hidden" id="exthandwaxes" name="exthandwaxes[]" value="<?php echo $veh->exthandwax_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if($veh->vehicle_washing_package == 'Premium') echo 'display: none;'; ?>" class="extplasticdressing <?php if($veh->extplasticdressing_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="extplasticdressing" value="8" <?php if($veh->extplasticdressing_vehicle_fee > 0) echo "checked"; ?>> $8 Exterior Plastic Dressing<!--Exterior Plastic Dressing--></p>
<input type="hidden" id="extplasticdressings" name="extplasticdressings[]" value="<?php echo $veh->extplasticdressing_vehicle_fee; ?>" />

<p style="margin-top: 20px;" class="extclaybar <?php if($veh->extclaybar_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="extclaybar" value="<?php if($veh->extclaybar_vehicle_fee > 0) {echo $veh->extclaybar_vehicle_fee;} else {echo $clay_bar_price;} ?>" <?php if($veh->extclaybar_vehicle_fee > 0) echo "checked"; ?>> $<?php if($veh->extclaybar_vehicle_fee > 0) {echo $veh->extclaybar_vehicle_fee;} else {echo $clay_bar_price;} ?> Clay Bar & Paste Wax</p>
<input type="hidden" id="extclaybars" name="extclaybars[]" value="<?php echo $veh->extclaybar_vehicle_fee; ?>" />

<p style="margin-top: 20px;" class="waterspotremove <?php if($veh->waterspotremove_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="waterspotremove" value="30" <?php if($veh->waterspotremove_vehicle_fee > 0) echo "checked"; ?>> $30 Water Spot Removal</p>
<input type="hidden" id="waterspotremoves" name="waterspotremoves[]" value="<?php echo $veh->waterspotremove_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if(($veh->vehicle_washing_package == 'Premium') || ($veh->vehicle_washing_package == 'Express')) echo 'display: none;'; ?>" class="upholstery_el <?php if($veh->upholstery_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="upholstery_el" value="20" <?php if($veh->upholstery_vehicle_fee > 0) echo "checked"; ?>> $20 Upholstery Conditioning</p>

<input type="hidden" name="upholstery[]" id="upholstery" value="<?php echo $veh->upholstery_vehicle_fee; ?>" />

<p style="margin-top: 20px; <?php if($veh->vehicle_washing_package == 'Express') echo 'display: none;'; ?>" class="floormat_el <?php if($veh->floormat_vehicle_fee > 0) echo "addon-checked"; ?>"><input type="checkbox" id="floormat_el" value="<?php if($veh->floormat_vehicle_fee > 0) {echo $veh->floormat_vehicle_fee;} else{ echo $floormat_price;} ?>" <?php if($veh->floormat_vehicle_fee > 0) echo "checked"; ?>> $<?php if($veh->floormat_vehicle_fee > 0) {echo $veh->floormat_vehicle_fee;} else{ echo $floormat_price;} ?> Floor Mat Cleaning</p>
<input type="hidden" name="floormat[]" id="floormat" value="<?php echo $veh->floormat_vehicle_fee; ?>" />

<p style="<?php if($veh->vehicle_washing_package == 'Express') echo 'display: none;'; ?>" class="pet_fee_el <?php if($veh->pet_hair_fee > 0) echo "addon-checked"; ?>" style="margin-top: 20px;"><input type="checkbox" id="pet_fee" value="10" <?php if($veh->pet_hair_fee > 0) echo "checked"; ?>> $<input type="text" id="pet_fees" class="custom_pet_fees" min="0" name="pet_fees[]" value="<?php echo $veh->pet_hair_fee; ?>" onkeypress="return isNumberKey(event)"/> Extra Cleaning Fee<!--Extra Cleaning Fee--></p>


<p class="lifted_truck_el <?php if($veh->lifted_vehicle_fee > 0) echo "addon-checked"; ?>" style="margin-top: 20px;"><input type="checkbox" id="lifted_truck_fee" value="10" <?php if($veh->lifted_vehicle_fee > 0) echo "checked"; ?>> $10 Lifted Truck Fee</p>
<input type="hidden" id="truck_fees" name="truck_fees[]" value="<?php echo $veh->lifted_vehicle_fee; ?>" />


<?php if($getorder->first_wash_discount > 0 && $ind == 0): ?>
<div class='first-disc-wrap'><p>$<?php echo $getorder->first_wash_discount; ?> First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='<?php echo $getorder->first_wash_discount; ?>' /></div>
<?php $first_time_wash = 0; ?>
<?php else: ?>
  <div class='first-disc-wrap'></div>
<?php endif; ?>
<?php if($veh->fifth_wash_discount > 0){
    echo "<div class='fifth-disc-wrap'><p>$".$veh->fifth_wash_discount." Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='".$veh->fifth_wash_discount."' /></div>";
      $wash_points = 0;
}
else{
      echo "<div class='fifth-disc-wrap'><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='0' /></div>";
//echo "<input type='hidden' name='fifth_discs[]' value='".$veh_detail[10]."' />";
    } ?>

<input type="hidden" name="car_ids[]" value="<?php echo $veh->id; ?>" />
<input type="hidden" name="is_package_changed[]" class="ispackagechanged" value="0" />
<input type="hidden" name="is_addons_changed[]" class="isaddonschanged" value="0" />

<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p>

</div>
<?php $point_index++; endforeach; ?>
<?php endif; ?>

                                                             </div>

                                                             </div>
                                                             <div style="clear: both;"></div>

    <h3 class="sec-heading" style="margin-top: 25px;">Payment Methods</h3>
    <?php if($getorder->is_flagged == 1): ?>
<div style="float: left;font-size: 18px; margin-top: 3px; cursor: pointer; background: #9C27B0; color: #fff; padding: 8px 35px; margin-right: 20px; margin-bottom: 15px;" class="pass-fraud">Pass Fraud</div>
<div style="clear: both;"></div>
<?php endif; ?>
     <?php
 if($getorder->customer_id) {

$handle = curl_init(ROOT_URL."/api/index.php?r=customers/getcustomerpaymentmethods");
$data = array('customer_id' => $getorder->customer_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
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
<div style="float: left;"><input type="radio" <?php if($paymethod->payment_method_details->isDefault) echo "checked"; ?> value="<?php echo $paymethod->payment_method_details->token; ?>" name="saved_paymethod" class="cust_saved_paymethod" /> <img class="card-img" src="<?php echo $paymethod->payment_method_details->cardimg; ?>" style="width: 44px; margin-right: 6px; margin-left: 6px;" /> <?php echo $paymethod->payment_method_details->maskedNumber; ?> <span class="card-exp-det" style="margin-left: 10px;"> (exp. <?php echo $paymethod->payment_method_details->expirationMonth."/".$paymethod->payment_method_details->expirationYear; ?>)</span></div><div style="float: left; margin-left: 60px; margin-top: 7px;"><?php echo $paymethod->payment_method_details->cardname; ?><a href="#" class="card-remove" data-token="<?php echo $paymethod->payment_method_details->token; ?>" <?php if($paymethod->payment_method_details->cardname) echo "style='margin-left: 25px;'";?>>Remove</a></div><div style="clear: both;"></div></li>
<?php if($paymethod->payment_method_details->isDefault) {
  $first_pay_token = $paymethod->payment_method_details->token;
   $first_card_ending_no = $paymethod->payment_method_details->last4;
   $first_card_type = $paymethod->payment_method_details->cardType;
} ?>
<?php endforeach; ?>
</ul>
<input type="hidden" name="pay_method_token" id="pay_method_token" value="<?php if($first_pay_token) echo $first_pay_token; ?>" />
<input type="hidden" name="card_ending_no" id="card_ending_no" value="<?php if($first_card_ending_no) echo $first_card_ending_no; ?>" />
<input type="hidden" name="card_type" id="card_type" value="<?php if($first_card_type) echo $first_card_type; ?>" />
<input type="hidden" name="is_token_changed" id="is_token_changed" value="0" />

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
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Billing Address<span style="color: red;">*</span></label>
                                                            <input type="text" name="bill_address" id="bill_address" placeholder="Street Address" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Apt</label>
                                                            <input type="text" name="bill_apt" id="bill_apt" placeholder="Apt" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">City<span style="color: red;">*</span></label>
                                                            <input type="text" name="bill_city" id="bill_city" placeholder="City" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>
                                                       <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">State<span style="color: red;">*</span></label>
                                                            <input type="text" name="bill_state" id="bill_state" placeholder="State" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Zipcode<span style="color: red;">*</span></label>
                                                            <input type="text" name="bill_zipcode" id="bill_zipcode" placeholder="Zipcode" style="width: 300px;" class="form-control">
                                                        </div>
                                                     </div>
                                                       <div style="clear: both;"></div>

</div>

                                                             <div style="clear: both;"></div>
                                                             <input type="hidden" id="bt_number" data-braintree-name="number">
<input type="hidden" id="bt_exp" data-braintree-name="expiration_date">

                                                             <div style="clear: both;"></div>

                                                            </div>
                                                            <div class="col-md-4" style="padding-right: 0; border-left: 1px solid #ccc; padding-left: 28px;">

                                                                <div class="form-group">
                                                                     <?php if($getorder->status == 0): ?>
                                                                    <div class="order-status-update orange-btn" data-status="1">Start Job</div>
                                                                    <?php endif; ?>
                                                                    <?php if($getorder->status == 1): ?>
                                                                    <div class="order-status-update orange-btn" data-status="2">Arrive</div>
                                                                    <?php endif; ?>
                                                                    <?php if($getorder->status == 2): ?>
                                                                    <div class="order-status-update orange-btn" data-status="3">Start Processing</div>
                                                                    <?php endif; ?>
                                                                    <?php if($getorder->status == 3): ?>
                                                                    <div class="order-status-update orange-btn" data-status="4">Complete Order</div>
                                                                    <?php endif; ?>
                                                                </div>

                                                             <div class="form-group">
                                                             <label class="control-label" style="margin-top: 0;">Order Status</label>
                                                             <?php if($getorder->status == 0) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-offline'>0 - Pending</span></p>"; ?>
                                                             <?php if($getorder->status == 1) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-busy'>1 - Washer Start Job</span></p>"; ?>
                                                             <?php if($getorder->status == 2) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-busy'>2 - Washer Arrived</span></p>"; ?>
                                                             <?php if($getorder->status == 3) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-busy'>3 - Processing</span></p>"; ?>
                                                             <?php if($getorder->status == 4) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-online'>4 - Completed</span></p>"; ?>
                                                             <?php if($getorder->status == 5) {
                                                              if($getorder->company_cancel == 1) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-cancel'>MobileWash Canceled</span></p>";
                                                              else echo "<p style='margin-bottom: 0;'><span class='label label-sm label-cancel'>5 - Customer Canceled</span></p>";
                                                             }
                                                              ?>
                                                             <?php if($getorder->status == 6) {
                                                              if($getorder->company_cancel == 1) echo "<p style='margin-bottom: 0;'><span class='label label-sm label-cancel'>MobileWash Canceled</span></p>";
                                                              else echo "<p style='margin-bottom: 0;'><span class='label label-sm label-cancel'>6 - Washer Canceled</span></p>";
                                                              }
                                                               ?>

                                                        </div>

                                                        <div class="form-group">
                                                             <label class="control-label">Assigned Detailer</label>
                                                             <input type="text" id="agentname" name="agentname" class="form-control" value="<?php if($agentdetails->id) echo $agentdetails->real_washer_id." - ".$agentdetails->first_name." ".$agentdetails->last_name; ?>" />
                                                             <input type="hidden" name="detailer" id="detailer" value="<?php if($agentdetails->id) {echo $agentdetails->id;}else{echo "0";} ?>" />

                                                        </div>
                                                         <?php if(!$getorder->admin_submit_for_settle): ?>
                                                         <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" class="washer_update" value="Save Washer" /></p>
                                                       </div>
                                                         <?php endif; ?>
                                                        <div class="form-group">
                                                          <label class="control-label">Washer Payment Status: </label>
                                                               <?php if($getorder->washer_payment_status == 1){

														   $image2 =  "<p style='margin-bottom: 0;'><span class='label label-sm label-online'>Released</span></p>";
														}
														elseif($getorder->washer_payment_status == 3){

														   $image2 =  '<p style="margin-bottom: 0;"><span class="label label-sm label-online">Admin Released</span></p>';
														}
														elseif(!$getorder->washer_payment_status){

															   $image2 = '<p style="margin-bottom: 0;"><span class="label label-sm label-offline">Pending</span></p>';
														}
														elseif($getorder->washer_payment_status == 2){

																$image2 = '<p style="margin-bottom: 0;"><span class="label label-sm label-busy">On Hold</span></p>';
														}
														else{
														   $image2 = '';
														} ?>
														<?php echo $image2; ?>
</div>
                                                 <?php if($getorder->transaction_id): ?>
                                                 <div class="form-group" style="display: block; margin-top: 20px;">
                                                          <label class="control-label">Washer Braintree ID: </label>
                                                          <p style="margin-bottom: 0;"><?php echo $transaction_details->transaction_details->merchant_id; ?></p>
                                                              
</div>
                                                 <?php endif; ?>
<?php
$handle = curl_init(ROOT_URL."/api/index.php?r=site/getwashersavedroplog");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('wash_request_id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $savedroplogdata = json_decode($result);
if($savedroplogdata->result == 'true'):?>
 <div class="form-group" style="display: block; margin-top: 25px;">
     <div class="activity-logs">
         
                                                          <?php foreach($savedroplogdata->logs as $log): ?>
                                                          <?php if($log->action == 'savejob'): ?>
                                                          <?php if($log->admin_username): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> saved #<?php echo $log->agent_company_id; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php else: ?>
                                                          <p style="margin-bottom: 10px;">                            #<?php echo $log->agent_company_id; ?> saved order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php endif; ?>
                                                          <?php if((($log->action == 'dropjob_ratingunchange')) && ($getorder->is_scheduled)): ?>
                                                          <!--<p style="margin-bottom: 10px; color: red;">#<?php //echo $log->agent_company_id; ?> dropped order at <?php //echo date('F j, Y - h:i A', strtotime($log->action_date)); ?>. Reason: <?php //echo $log->addi_detail; ?></p>-->
                                                          <p style="margin-bottom: 10px; color: red;">Washer #<?php echo $log->agent_company_id; ?> feedback at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?>: <?php echo $log->addi_detail; ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'dropjob' && ($getorder->is_scheduled) && (!$log->admin_username)): ?>
                                                          <p style="margin-bottom: 10px; color: red;">Washer #<?php echo $log->agent_company_id; ?> feedback at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?>: <?php echo $log->addi_detail; ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'Dropschedule' && ($getorder->is_scheduled)): ?>
                                                          <p style="margin-bottom: 10px; color: red;">#<?php echo $log->agent_company_id; ?> dropped order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'admindropjob'): ?>
                                                          <p style="margin-bottom: 10px; color: red;"><?php echo $log->admin_username; ?> dropped Washer #<?php echo $log->agent_company_id; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'reschedule'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> re-scheduled order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                            <?php if($log->action == 'savenote'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added order note at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'savecustomernote'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added Customers note at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'savewashnote'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added washer note at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'editorder'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> edited order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'refundpayment'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> refunded payment at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'processpayment'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> processed payment at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'voidpayment'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> voided payment at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'cancelorder'): ?>
                                                          <p style="margin-bottom: 10px; color:red;"><?php echo $log->admin_username; ?> canceled order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'agentreceiptsend'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> sent washer receipt at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'clientreceiptsend'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> sent client receipt at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'addlocation'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added location at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'updatelocation'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> updated location at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'deletelocation'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> deleted location at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'deletepaymentmethod'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> deleted payment method at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'addpaymentmethod'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added payment method at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'updatepaymentmethod'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> updated payment method at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerpush'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> sent washer push notification at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'passfraud'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> released order from fraud at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'startjob'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> accepted request at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'arrivejob'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> arrived at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'processjob'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> processed order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'completejob'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> completed order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerstartjob'): ?>
                                                          <?php if($getorder->is_scheduled == 1): ?>
                                                          <p style="margin-bottom: 10px;">#<?php echo $log->agent_company_id; ?> tapped Drive Now at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          
                                                          <?php else: ?>
                                                          <p style="margin-bottom: 10px;">#<?php echo $log->agent_company_id; ?> accepted request at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          
                                                          <?php endif; ?>
                                                          
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerarrivejob'): ?>
                                                          <p style="margin-bottom: 10px;">#<?php echo $log->agent_company_id; ?> arrived at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerprocessjob'): ?>
                                                          <p style="margin-bottom: 10px;">#<?php echo $log->agent_company_id; ?> started processing at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'appcompletejob'): ?>
                                                          <p style="margin-bottom: 10px;">Order completed at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'freewash'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> gives free wash at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'uncancel'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> un-canceled wash at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'cancelorderclient'): ?>
                                                          <p style="margin-bottom: 10px;  color:red;">Customer canceled order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'cancelorderwasher'): ?>
                                                          <p style="margin-bottom: 10px;  color:red;">Washer #<?php echo $log->agent_company_id; ?> canceled order at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerenroutecancel'): ?>
                                                          <p style="margin-bottom: 10px; color:red;">Washer #<?php echo $log->agent_company_id; ?> canceled order enroute at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'adminstopwasherpayment'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> stopped washer payment at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                           <?php if($log->action == 'adminenablewasherpayment'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> enabled washer payment at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washeraddcar'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> added <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerchangepack'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> changed package/addons <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'adminsubmitforsettlement'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> submitted payment for settlement at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'meetwasherbeforeinspect'): ?>
                                                          <p style="margin-bottom: 10px;">Customer hit <?php if($log->addi_detail == 'yes') {echo "'Meet Washer Outside'";} if($log->addi_detail == 'no') {echo "'No Thanks'";}  ?> before inspection at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'meetwasherwashend'): ?>
                                                          <p style="margin-bottom: 10px;">Customer hit <?php if($log->addi_detail == 'yes') {echo "'Meet Washer Outside'";} if($log->addi_detail == 'no') {echo "'No Thanks'";}  ?> after wash at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washereditcar'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> edited vehicle to <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'adminaddcar'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'adminchangepack'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> changed package <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'adminchangeaddons'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> changed addons <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'washerremovecar'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> removed <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'adminremovecar'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> removed <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customerfeedback'): ?>
                                                          <p style="margin-bottom: 10px; color:red;">Customer feedback at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?> : <?php echo $log->addi_detail; ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'agentfeedback'): ?>
                                                          <p style="margin-bottom: 10px; color: red;">Washer #<?php echo $log->agent_company_id; ?> finishes adding the feedback for that dropped order <?php //echo date('F j, Y - h:i A', strtotime($log->action_date)); ?> : <?php echo $log->addi_detail; ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customercall'): ?>
                                                          <p style="margin-bottom: 10px;">Customer called washer #<?php echo $log->agent_company_id; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'agentcall'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> called customer at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'agentbuzz'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_company_id; ?> buzzed customer at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customeracceptupgrade'): ?>
                                                          <p style="margin-bottom: 10px;">Customer accepted package/addons <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customerrejectupgrade'): ?>
                                                          <p style="margin-bottom: 10px;">Customer declined package/addons <?php echo $log->addi_detail; ?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'tipamount'): ?>
                                                          <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> edited tip amount from <?php echo $log->addi_detail;?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customertipamount'): ?>
                                                          <p style="margin-bottom: 10px;">Customer edited tip amount from <?php echo $log->addi_detail;?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'customeraddtipamount'): ?>
                                                          <p style="margin-bottom: 10px;">Customer added <?php echo $log->addi_detail;?> tip amount at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'agentreinspectopt'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_id; ?> choose reinspect for <?php echo $log->addi_detail;?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'agentnodamageopt'): ?>
                                                          <p style="margin-bottom: 10px;">Washer #<?php echo $log->agent_id; ?> choose no damage for <?php echo $log->addi_detail;?> at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'clientwashnowschedule'): ?>
                                                          <p style="margin-bottom: 10px;">Customer changed Wash Now to Scheduled at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'ondemandautocancel'): ?>
                                                          <p style="margin-bottom: 10px; color:red;">Customer's On Demand auto-canceled at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php if($log->action == 'scheduleauto-canceled'): ?>
                                                          <p style="margin-bottom: 10px; color:red;">"Scheduled Order auto-canceled at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                          <?php endif; ?>
                                                          <?php endforeach; ?>
                                                          </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        <div class="form-group">
                                                             <label class="control-label">Order Notes</label>
                                                           <textarea name="notes" id="notes" style="width: 313px; height: 277px;" class="form-control"><?php echo $getorder->notes; ?></textarea>
                                                        </div>


                                                        <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" class="note_update" value="Save" /></p>
                                                       </div>
                                                                
                                                        <div class="form-group">
                                                             <label class="control-label">Customer Notes</label>
                                                           <textarea name="notes" id="cst_notes" style="width: 313px; height: 100px;" class="form-control"><?php echo $custdetails->customer_notes;?></textarea>
                                                        </div>


                                                        <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" value="Save" id="customer_note"/></p>
                                                       </div>

                                                        <div class="form-group">
                                                             <label class="control-label">Washer Notes</label>
                                                           <textarea name="notes" id="was_notes" style="width: 313px; height: 100px;" class="form-control"><?php echo $profiledetail->notes;?></textarea>
                                                        </div>


                                                        <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" value="Save" id="washer_note"/></p>
                                                       </div>
                                                                
                                                        <div class="form-group">
                                                             <label class="control-label">Washer Push Notification</label>
                                                           <textarea name="washer_push_msg" id="washer_push_msg" style="width: 313px; height: 100px;" class="form-control"></textarea>
                                                        </div>


                                                        <div class="form-group">
                                                             <p style="margin-top: 20px;"><input type="button" class="send_washer_push" value="Send" /></p>
                                                             <p class="status-text">Push sent</p>
                                                       </div>

                                                       

                                                        <?php if(count($getorder->vehicles) > 0 && ($getorder->status > 0)): ?>
                                                       <div class="form-group" style="display: block; clear: both; margin-bottom: 60px;">
                                                      
                                                       <ul class="bxslider">
                                                       <?php foreach($getorder->vehicles as $veh): ?>
                                                       <?php if($veh->vehicle_inspect_image) echo "<li><img title='".$veh->brand_name." ".$veh->model_name."' src='".$veh->vehicle_inspect_image."' /></li>"; ?>
                                                        <?php endforeach; ?>
                                                       </ul>
                                                    </div>
                                                       <?php endif; ?>
                                                       <?php if(($getorder->transaction_id) && ($transaction_details->transaction_details->status == 'authorized') && (!$getorder->washer_payment_status)) :?>
                                                       <div class="form-group">
                                                        <label class="control-label" style="display: block; padding-top: 15px; margin-top: 0; font-weight: bold; margin-bottom: 15px;">Submit for Settlement</label>
                                                        <input type="text" style="width: 85px;" value="<?php echo $getorder->net_price; ?>" id="submit_for_settle_amount" /> USD
                                                        <p style="margin-top: 20px;"><input type="button" class="submit_settle_btn" value="Submit for Settlement" /></p>
                                                       </div>
                                                       <?php endif; ?>
                                                            </div>
                                                             <div style="clear: both;"></div>
                                                               <?php if(!$getorder->admin_submit_for_settle): ?>
                                                               <input type="submit" id="edit-order-submit" value="Submit" name="edit-order-submit" style="color: rgb(255, 255, 255); margin-top: 20px; background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            <?php endif; ?>    
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
   <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
   <input type="text" name="spdisc" id="spdisc" placeholder="Enter special discount amount (if have any)" style="display: block; padding: 10px; width: 80%;" />
<?php endif; ?>
  <div id="payment-form"></div>
  <input type="submit" value="Process" style="margin-top: 20px; display: block; border: 0; background: #076ee1; color: #fff; padding: 8px; cursor: pointer;" name="pay-process-submit" />
</form>
<a href="#" class="pop-close">X</a>
</div>
                </div>
            </div>
<?php include('footer.php') ?>

<div id="cancel-order-pop" title="Cancel Order">
  <p>Please select an option</p>
</div>

<div id="cancel-order-ondemand-pop" title="Cancel Order">
  <p>Please select an option</p>
</div>

<div id="washer-arrive-dialog" title="Washer Arrived">
  <p>Washer arrived. Please meet washer outside.</p>
</div>

<div id="car-inspect-dialog" title="Vehicle Inspection">

</div>

<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://209.95.41.9:3000/socket.io/socket.io.js"></script>
<script>
var current_vehicle_id;
  $( function() {

   $('#washer_note').on('click', function(){
          var notes = $('#was_notes').val();
          
          $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=agents/NotesUpdate", { customer_id: "<?php echo $getorder->agent_id; ?>",admin_command: 'save-wash-note', admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>",notes : notes, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
      });
      });
      
    $('#customer_note').on('click', function(){
          var notes = $('#cst_notes').val();
          
          $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=customers/NotesUpdate", { customer_id: "<?php echo $getorder->customer_id; ?>",admin_command: 'save-customer-note', admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>",notes : notes, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
      });
      });

    $( "#cancel-order-pop" ).dialog({
      resizable: false,
 autoOpen: false,
      height: "auto",
      width: 465,
      modal: true,
      buttons: {
        "Customer Cancel (fee)": function() {
      $( this ).dialog( "close" );
$(".cancel-order").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/cancelscheduleorder", { customer_id: "<?php echo $getorder->customer_id; ?>", id: "<?php echo $getorder->id; ?>", fee: 15, admin_username: "<?php echo $jsondata_permission->user_name; ?>", api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order").html('Cancel Order');

}

});
        },
        "Washer Cancel (rating penalty, no fee)": function() {

          $( this ).dialog( "close" );
$(".cancel-order").html('Cancelling. Please wait...');
$(".err-text").hide();
var socket = io.connect("209.95.41.9:3000", { query: "action=commandcenter" });
    socket.on('connect', function() {
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/updatewashrequeststatus", { wash_request_id: "<?php echo $getorder->id; ?>", agent_id: 0, washer_drop_job: 1, is_scheduled: 1, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4',admin_username: "<?php echo $jsondata_permission->user_name; ?>"}, function(data){
//console.log(data);
if(data.result == 'true'){
  var wash_id = "<?php echo $getorder->specid; ?>";
  socketId = socket.io.engine.id;
  //console.log(socketId);
  socket.emit('currentwashschedulealert',{wash_request_id: wash_id, socketId:socketId});
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order").html('Cancel Order');

}

});
});
        },
        "Company Cancel (no fee, no rating penalty)": function() {

          $( this ).dialog( "close" );
$(".cancel-order").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/cancelscheduleorder", { customer_id: "<?php echo $getorder->customer_id; ?>", id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", fee: 15, free_cancel: true, company_cancel: 1, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order").html('Cancel Order');

}

});
        },
        "Customer No Response (fee)": function() {
      $( this ).dialog( "close" );
$(".cancel-order").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/customernoresponse", { customer_id: "<?php echo $getorder->customer_id; ?>", id: "<?php echo $getorder->id; ?>", fee: 30, admin_username: "<?php echo $jsondata_permission->user_name; ?>", api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order").html('Cancel Order');

}

});

        }
      }
    });

        $( "#cancel-order-ondemand-pop" ).dialog({
      resizable: false,
 autoOpen: false,
      height: "auto",
      width: 465,
      modal: true,
      buttons: {
        "Customer Cancel (fee)": function() {
      $( this ).dialog( "close" );
$(".cancel-order-ondemand").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminondemandcancelorder", { id: "<?php echo $getorder->id; ?>", status: 5, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order-ondemand").html('Cancel Order');

}

});
        },
                "Washer Cancel (rating penalty, no fee)": function() {

          $( this ).dialog( "close" );
$(".cancel-order-ondemand").html('Cancelling. Please wait...');
$(".err-text").hide();
<?php if($getorder->status == 1): ?>
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/washerenroutecancel", { wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", status: 6, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order-ondemand").html('Cancel Order');

}

});
<?php else: ?>
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminondemandcancelorder", { id: "<?php echo $getorder->id; ?>", free_cancel: 'yes', status: 6, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){

if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order-ondemand").html('Cancel Order');

}

});
<?php endif; ?>
        },
              "Company Cancel (no fee, no rating penalty)": function() {
                var cancel_status = 5;
                
      $( this ).dialog( "close" );
$(".cancel-order-ondemand").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminondemandcancelorder", { id: "<?php echo $getorder->id; ?>", status: cancel_status, free_cancel: 'yes', admin_username: "<?php echo $jsondata_permission->user_name; ?>", company_cancel: 1, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order-ondemand").html('Cancel Order');

}

});
        },
        "Customer No Response (fee)": function() {
      $( this ).dialog( "close" );
$(".cancel-order").html('Cancelling. Please wait...');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/customernoresponse", { customer_id: "<?php echo $getorder->customer_id; ?>", id: "<?php echo $getorder->id; ?>", fee: 30, admin_username: "<?php echo $jsondata_permission->user_name; ?>", api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(".cancel-order").html('Cancel Order');

}

});

        }

      }
    });



     $( "#washer-arrive-dialog" ).dialog({
      resizable: false,
 autoOpen: false,
      height: "auto",
      width: 465,
      modal: true,
      buttons: {
        "Ok": function() {
      $( this ).dialog( "close" );
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/updatewashrequeststatus", { agent_id: "<?php echo $getorder->agent_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", status: 3, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'false'){

$(".err-text").html(data.response);
$(".err-text").show();

}

});
        },
        "Close": function() {

          $( this ).dialog( "close" );

        }
      }
    });


     $( "#car-inspect-dialog" ).dialog({
      resizable: false,
 autoOpen: false,
      height: "auto",
      width: 465,
      modal: true,
      buttons: {
        "Accept": function() {
      $( this ).dialog( "close" );
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=customers/setvehiclestatus", { vehicle_id: current_vehicle_id, wash_request_id: "<?php echo $getorder->id; ?>", status: 5, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'false'){

$(".err-text").html(data.response);
$(".err-text").show();


}

});
        },
        "Reject": function() {

               $( this ).dialog( "close" );
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=customers/setvehiclestatus", { vehicle_id: current_vehicle_id, wash_request_id: "<?php echo $getorder->id; ?>", status: 3, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'false'){

$(".err-text").html(data.response);
$(".err-text").show();


}

});

        }
      }
    });
$('.ui-dialog-titlebar-close').html('X');
  } );
  </script>

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
var initial_pay_token = '';
//if(first_wash_check == 0) first_time_wash = 1;
var org_wash_points = "<?php echo $org_wash_points; ?>";
var wash_points = "<?php echo $wash_points; ?>";

	if(org_wash_points >= 5 && wash_points >= 5) {
org_wash_points = 0;
wash_points = 0;
}

console.log('wash point '+ wash_points);

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
    
$(function(){
initial_pay_token = $("#pay_method_token").val();
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
               regular_models += "<option value='"+mod[0]+"' data-cat='"+mod[1]+"' data-type='"+mod[2]+"'>" + mod[0] + "</option>";
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
               classic_models += "<option value='"+mod[0]+"' data-cat='"+mod[1]+"' data-type='"+mod[2]+"'>" + mod[0] + "</option>";
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
if(index == 0) f_model = mod[0];

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"' data-type='"+mod[2]+"'>" + mod[0] + "</option>");

  var car_cat = $("#"+parentid).find('select.regular-model option:selected').data('cat');
var car_type = $("#"+parentid).find('select.regular-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}
  $("#"+parentid).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#"+parentid).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");
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
if(index == 0) f_model = mod[0];

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"' data-type='"+mod[2]+"'>" + mod[0] + "</option>");

  var car_cat = $("#"+parentid).find('select.classic-model option:selected').data('cat');
var car_type = $("#"+parentid).find('select.classic-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}

  $("#"+parentid).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#"+parentid).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");
		});

	});
});

$(".regular-add-trigger").click(function(){
    var content = '';
regularindex++;
wash_points++;
console.log(wash_points);
first_time_wash = 0;
//if(first_wash_check == 0 && $( ".first-disc-wrap" ).children().length <= 0) first_time_wash = 1;

    content += "<div class='regular-car-box' id='regular-car-box-"+regularindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='car_makes[]' class='form-control regular-make' style='width: 300px;'>";
    content += regular_makes;
    content += "</select><label class='control-label'>Model</label><select name='car_models[]' class='form-control regular-model' style='width: 300px;'>";
    content += regular_models;
    content += "</select><label class='control-label'>Package</label><select name='car_packs[]' class='form-control regular-pack' style='width: 300px;'><option value='Express'>EXPRESS WASH</option><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><input type='hidden' id='car_type' name='car_types[]' value='regular'><input type='hidden' id='handle_fee' name='handle_fees[]' value='1'><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Liquid Hand Wax </p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='8' /> $8 Exterior Plastic Dressing</p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='40' /> $40 Clay Bar & Paste Wax</p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal</p><p style='margin-top: 20px; display: none;' class='upholstery_el'><input type='checkbox' id='upholstery_el' value='20' /> $20 Upholstery Conditioning</p><p style='margin-top: 20px; display: none;' class='floormat_el'><input type='checkbox' id='floormat_el' value='10' /> $10 Floor Mat Cleaning</p><p class='pet_fee_el' style='margin-top: 20px;'><input type='checkbox' id='pet_fee' value='10' /> $10 Extra Cleaning Fee</p><p class='lifted_truck_el' style='margin-top: 20px;'><input type='checkbox' id='lifted_truck_fee' value='10' /> $10 Lifted Truck Fee</p><input type='hidden' name='pet_fees[]' id='pet_fees' value='0' /><input type='hidden' name='truck_fees[]' id='truck_fees' value='0' /><input type='hidden' id='exthandwaxes' name='exthandwaxes[]' value='0'><input type='hidden' id='extplasticdressings' name='extplasticdressings[]' value='0'><input type='hidden' id='extclaybars' name='extclaybars[]' value='0'><input type='hidden' id='waterspotremoves' name='waterspotremoves[]' value='0'><input type='hidden' id='upholstery' name='upholstery[]' value='0'><input type='hidden' id='floormat' name='floormat[]' value='0'><input type='hidden' name='car_ids[]' id='car_id' value='0' />";
 if(first_time_wash == 1){
        content += "<div class='first-disc-wrap'><p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' /></div>";
    }
    else{
      content += "<div class='first-disc-wrap'></div>";
    }
if(wash_points >= 5){
    content += "<div class='fifth-disc-wrap'><p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' /></div>";
      wash_points = 0;
}
else{
      content += "<div class='fifth-disc-wrap'><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='0' /></div>";
    }

content +="<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='regular-car-remove'>Remove</a></p></div>";

    $(".regular-vehicles-wrap").append(content);

 var car_cat = $("#regular-car-box-"+regularindex).find('select.regular-model option:selected').data('cat');
var car_type = $("#regular-car-box-"+regularindex).find('select.regular-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}

  $("#regular-car-box-"+regularindex).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#regular-car-box-"+regularindex).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");

    return false;

});

$(".classic-add-trigger").click(function(){
    var content = '';
classicindex++;
wash_points++;
//if(first_wash_check == 0 && $( ".first-disc-wrap" ).children().length <= 0) first_time_wash = 1;
    content += "<div class='classic-car-box' id='classic-car-box-"+classicindex+"' style='border-top: 1px solid #ccc; margin-top: 20px;'><label class='control-label'>Make</label><select name='car_makes[]' class='form-control classic-make' style='width: 300px;'>";
    content += classic_makes;
    content += "</select><label class='control-label'>Model</label><select name='car_models[]' class='form-control classic-model' style='width: 300px;'>";
    content += classic_models;
    content += "</select><label class='control-label'>Package</label><select name='car_packs[]' class='form-control classic-pack' style='width: 300px;'><option value='Express'>EXPRESS WASH</option><option value='Deluxe'>DELUXE WASH</option><option value='Premium'>PREMIUM DETAIL</option></select><input type='hidden' id='car_type' name='car_types[]' value='classic'><input type='hidden' id='handle_fee' name='handle_fees[]' value='1'><p style='margin-top: 20px;' class='exthandwax'><input type='checkbox' id='exthandwax' value='12' /> $12 Liquid Hand Wax </p><p style='margin-top: 20px;' class='extplasticdressing'><input type='checkbox' id='extplasticdressing' value='8' /> $8 Exterior Plastic Dressing</p><p style='margin-top: 20px;' class='extclaybar'><input type='checkbox' id='extclaybar' value='40' /> $40 Clay Bar & Paste Wax</p><p style='margin-top: 20px;' class='waterspotremove'><input type='checkbox' id='waterspotremove' value='30' /> $30 Water Spot Removal</p><p style='margin-top: 20px; display: none;' class='upholstery_el'><input type='checkbox' id='upholstery_el' value='20' /> $20 Upholstery Conditioning</p><p style='margin-top: 20px; display: none;' class='floormat_el'><input type='checkbox' id='floormat_el' value='10' /> $10 Floor Mat Cleaning</p><p style='margin-top: 20px;' class='pet_fee_el'><input type='checkbox' id='pet_fee' value='10' /> $10 Extra Cleaning Fee</p><p class='lifted_truck_el' style='margin-top: 20px;'><input type='checkbox' id='lifted_truck_fee' value='10' /> $10 Lifted Truck Fee</p><input type='hidden' name='pet_fees[]' id='pet_fees' value='0' /><input type='hidden' name='truck_fees[]' id='truck_fees' value='0' /><input type='hidden' id='exthandwaxes' name='exthandwaxes[]' value='0'><input type='hidden' id='extplasticdressings' name='extplasticdressings[]' value='0'><input type='hidden' id='extclaybars' name='extclaybars[]' value='0'><input type='hidden' id='waterspotremoves' name='waterspotremoves[]' value='0'><input type='hidden' id='upholstery' name='upholstery[]' value='0'><input type='hidden' id='floormat' name='floormat[]' value='0'><input type='hidden' name='car_ids[]' id='car_id' value='0' />";
 if(first_time_wash == 1){
     content += "<div class='first-disc-wrap'><p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' /></div>";
    }
    else{
      content += "<div class='first-disc-wrap'></div>";
    }
    if(wash_points >= 5){
    content += "<div class='fifth-disc-wrap'><p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' /></div>";
      wash_points = 0;
}
else{
      content += "<div class='fifth-disc-wrap'><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='0' /></div>";
    }
content += "<p style='margin-top: 20px; text-align: right; margin-right: 15px; margin-bottom: 15px;'><a href='#' class='classic-car-remove'>Remove</a></p></div>";

    $(".classic-vehicles-wrap").append(content);

 var car_cat = $("#classic-car-box-"+classicindex).find('select.classic-model option:selected').data('cat');
var car_type = $("#classic-car-box-"+classicindex).find('select.classic-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}

  $("#classic-car-box-"+classicindex).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#classic-car-box-"+classicindex).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");

    return false;

});

$( "#phone-order-form" ).on( "click", ".regular-car-remove", function() {

wash_points = org_wash_points;

$( ".regular-vehicles-wrap .regular-car-box .fifth-disc-wrap" ).html('');
$( ".classic-vehicles-wrap .classic-car-box .fifth-disc-wrap" ).html('');

   $(this).parent().parent().remove();

 first_time_wash = 0;
//console.log('first disc wrap '+$( ".first-disc-wrap" ).children().length);
//if(first_wash_check == 0 && $( ".first-disc-wrap" ).children().length <= 0) first_time_wash = 1;

  $( ".regular-vehicles-wrap .regular-car-box" ).each(function() {
wash_points++;
console.log('regular '+wash_points);
 if ( first_time_wash == 1 ) {
          //console.log($('.classic-vehicles-wrap').children('.classic-car-box').length);
            if($(this).find('.regular-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express') $(this).find('.first-disc-wrap').html("<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />");
            if($(this).find('.regular-pack').val() == 'Premium') $(this).find('.first-disc-wrap').html("<p>$10 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='10' />");
           first_time_wash = 0;
       }

           if(wash_points >= 5){
             if($(this).find('.regular-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express' || $(this).find('.regular-pack').val() == 'Premium') $(this).find('.fifth-disc-wrap').html("<p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' />");
             wash_points = 0;
           }


});

  $( ".classic-vehicles-wrap .classic-car-box" ).each(function() {
wash_points++;
console.log('classic '+wash_points);
  if ( first_time_wash == 1 ) {
          //console.log($('.classic-vehicles-wrap').children('.classic-car-box').length);
            if($(this).find('.classic-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express') $(this).find('.first-disc-wrap').html("<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />");
            if($(this).find('.classic-pack').val() == 'Premium') $(this).find('.first-disc-wrap').html("<p>$10 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='10' />");
           first_time_wash = 0;
       }
           if(wash_points >= 5){
             if($(this).find('.classic-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express' || $(this).find('.regular-pack').val() == 'Premium') $(this).find('.fifth-disc-wrap').html("<p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' />");
             wash_points = 0;
           }


});

   return false;
});

$( "#phone-order-form" ).on( "click", ".classic-car-remove", function() {

wash_points = org_wash_points;

$( ".regular-vehicles-wrap .regular-car-box .fifth-disc-wrap" ).html('');
$( ".classic-vehicles-wrap .classic-car-box .fifth-disc-wrap" ).html('');

 $(this).parent().parent().remove();

  //console.log('first disc wrap '+$( ".first-disc-wrap" ).children().length);
first_time_wash = 0;
//if(first_wash_check == 0 && $( ".first-disc-wrap" ).children().length <= 0) first_time_wash = 1;

$( ".regular-vehicles-wrap .regular-car-box" ).each(function() {
wash_points++;
console.log('regular '+wash_points);
 if ( first_time_wash == 1 ) {
          //console.log($('.classic-vehicles-wrap').children('.classic-car-box').length);
            if($(this).find('.regular-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express') $(this).find('.first-disc-wrap').html("<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />");
            if($(this).find('.regular-pack').val() == 'Premium') $(this).find('.first-disc-wrap').html("<p>$10 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='10' />");
           first_time_wash = 0;
       }
           if(wash_points >= 5){
             if($(this).find('.regular-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express' || $(this).find('.regular-pack').val() == 'Premium') $(this).find('.fifth-disc-wrap').html("<p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' />");
             wash_points = 0;
           }


});


  $( ".classic-vehicles-wrap .classic-car-box" ).each(function() {
wash_points++;
//console.log('classic '+wash_points);
  if ( first_time_wash == 1 ) {
          //console.log($('.classic-vehicles-wrap').children('.classic-car-box').length);
            if($(this).find('.classic-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express') $(this).find('.first-disc-wrap').html("<p>$5 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='5' />");
            if($(this).find('.classic-pack').val() == 'Premium') $(this).find('.first-disc-wrap').html("<p>$10 First Wash Discount</p><input type='hidden' name='first_discs[]' id='first_discs' value='10' />");
           first_time_wash = 0;
       }
           if(wash_points >= 5){
             if($(this).find('.classic-pack').val() == 'Deluxe' || $(this).find('.regular-pack').val() == 'Express' || $(this).find('.regular-pack').val() == 'Premium') $(this).find('.fifth-disc-wrap').html("<p>$5 Fifth Wash Discount</p><input type='hidden' name='fifth_discs[]' id='fifth_discs' value='5' />");
             wash_points = 0;
           }


});


   return false;
});

$("body").on('focusout', '.custom_pet_fees', function(){
    var val = $(this).val();
    if(val > 10 || (val > 0 && val < 10)){
      if(confirm('Are you sure you want to change extra cleaning fee amount to ( '+val+' )? ')){
          return true;
      }else{
        $(this).val(10);

      }
    }
    if(val < 0){
      val = val.replace("-", "");
      $(this).val(val);
    }
});

$('.custom_pet_fees, .custom_tip_amount').keypress(function (e) {
  if (e.which == 13) {
    return false;    //<---- Add this line
  }else{
    return true;
  }
});
    
$( "#phone-order-form" ).on( "change", ".regular-pack, .classic-pack", function() {
  $(this).parent().find('.ispackagechanged').val(1);  
 if($(this).val() == 'Express'){
   $(this).parent().find('.pet_fee_el input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.pet_fee_el span').removeClass( "checked");
$(this).parent().find('#pet_fees').val(0);
$(this).parent().find('.lifted_truck_el input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.lifted_truck_el span').removeClass( "checked");
$(this).parent().find('#truck_fees').val(0);
$(this).parent().find('.upholstery_el input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.upholstery_el span').removeClass( "checked");
$(this).parent().find('#upholstery').val(0);
$(this).parent().find('.floormat_el input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.floormat_el span').removeClass( "checked");
$(this).parent().find('#floormat').val(0);

$(this).parent().find('.pet_fee_el, .lifted_truck_el, .upholstery_el, .floormat_el').removeClass('addon-checked');
$(this).parent().find('.pet_fee_el, .lifted_truck_el, .upholstery_el, .floormat_el').hide();

$(this).parent().find('.exthandwax, .extplasticdressing').show();
first_dis = $(this).parent().find('#first_discs').val();
fifth_dis = $(this).parent().find('#fifth_discs').val();

//console.log(first_dis);
if(first_dis != '0') {
$(this).parent().find('#first_discs').val('5');
$(this).parent().find('#first_discs').prev().html('$5 First Wash Discount');
}

if(fifth_dis != '0') {
$(this).parent().find('#fifth_discs').val('5');
$(this).parent().find('#fifth_discs').prev().html('$5 Fifth Wash Discount');
}

 }

 if($(this).val() == 'Deluxe'){

$(this).parent().find('.exthandwax, .extplasticdressing, .upholstery_el, .floormat_el').show();
first_dis = $(this).parent().find('#first_discs').val();
fifth_dis = $(this).parent().find('#fifth_discs').val();

//console.log(first_dis);
if(first_dis != '0') {
$(this).parent().find('#first_discs').val('5');
$(this).parent().find('#first_discs').prev().html('$5 First Wash Discount');
}

if(fifth_dis != '0') {
$(this).parent().find('#fifth_discs').val('5');
$(this).parent().find('#fifth_discs').prev().html('$5 Fifth Wash Discount');
}

$(this).parent().find('.pet_fee_el, .lifted_truck_el').show();

}

   if($(this).val() == 'Premium'){
$(this).parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
$(this).parent().find('.exthandwax span').removeClass( "checked");
$(this).parent().find('#exthandwaxes').val(0);
$(this).parent().find('.extplasticdressing input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.extplasticdressing span').removeClass( "checked");
$(this).parent().find('#extplasticdressings').val(0);
$(this).parent().find('.upholstery_el input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.upholstery_el span').removeClass( "checked");
$(this).parent().find('#upholstery').val(0);
/*$(this).parent().find('.floormat_el input[type=checkbox]').prop( "checked", false );
	$(this).parent().find('.floormat_el span').removeClass( "checked");
$(this).parent().find('#floormat').val(0);*/

$(this).parent().find('.exthandwax, .extplasticdressing, .upholstery_el').removeClass('addon-checked');
$(this).parent().find('.exthandwax, .extplasticdressing, .upholstery_el').hide();

first_dis = $(this).parent().find('#first_discs').val();
fifth_dis = $(this).parent().find('#fifth_discs').val();

//console.log(first_dis);
if(first_dis != '0') {
$(this).parent().find('#first_discs').val('10');
$(this).parent().find('#first_discs').prev().html('$10 First Wash Discount');
}

if(fifth_dis != '0') {
$(this).parent().find('#fifth_discs').val('5');
$(this).parent().find('#fifth_discs').prev().html('$5 Fifth Wash Discount');
}

$(this).parent().find('.pet_fee_el, .lifted_truck_el').show();

}
   return false;
});



$( "#phone-order-form" ).on( "click", ".pet_fee_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#pet_fees').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#pet_fees').val(0);
$(this).parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".pet_fee_el #uniform-pet_fee input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#pet_fees').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {

$(this).parent().parent().parent().parent().find('#pet_fees').val(0);

$(this).parent().parent().parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".lifted_truck_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#truck_fees').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#truck_fees').val(0);
$(this).parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".lifted_truck_el #uniform-lifted_truck_fee input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#truck_fees').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {
$(this).parent().parent().parent().parent().find('#truck_fees').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".exthandwax input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#exthandwaxes').val($(this).val());
$(this).parent().addClass('addon-checked');
$(this).parent().parent().find('#extclaybars').val(0);
$(this).parent().parent().find('.extclaybar').removeClass('addon-checked');
$(this).parent().parent().find('.extclaybar input[type=checkbox]').prop( "checked", false );
}
else {
$(this).parent().parent().find('#exthandwaxes').val(0);
$(this).parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".exthandwax #uniform-exthandwax input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#exthandwaxes').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
$(this).parent().parent().parent().parent().find('#extclaybars').val(0);
$(this).parent().parent().parent().parent().find('.extclaybar').removeClass('addon-checked');
$(this).parent().parent().parent().parent().find('.extclaybar span').removeClass('checked');
$(this).parent().parent().parent().parent().find('.extclaybar input[type=checkbox]').prop( "checked", false );
}
else {
$(this).parent().parent().parent().parent().find('#exthandwaxes').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".extplasticdressing input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#extplasticdressings').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#extplasticdressings').val(0);
$(this).parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".extplasticdressing #uniform-extplasticdressing input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#extplasticdressings').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {
$(this).parent().parent().parent().parent().find('#extplasticdressings').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".extclaybar input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#extclaybars').val($(this).val());
$(this).parent().addClass('addon-checked');
$(this).parent().parent().find('#exthandwaxes').val(0);
$(this).parent().parent().find('.exthandwax').removeClass('addon-checked');
$(this).parent().parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
}
else {
$(this).parent().parent().find('#extclaybars').val(0);
$(this).parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".extclaybar #uniform-extclaybar input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#extclaybars').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
$(this).parent().parent().parent().parent().find('#exthandwaxes').val(0);
$(this).parent().parent().parent().parent().find('.exthandwax').removeClass('addon-checked');
$(this).parent().parent().parent().parent().find('.exthandwax span').removeClass('checked');
$(this).parent().parent().parent().parent().find('.exthandwax input[type=checkbox]').prop( "checked", false );
}
else {
$(this).parent().parent().parent().parent().find('#extclaybars').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".waterspotremove input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#waterspotremoves').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#waterspotremoves').val(0);
$(this).parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".waterspotremove #uniform-waterspotremove input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#waterspotremoves').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {
$(this).parent().parent().parent().parent().find('#waterspotremoves').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".upholstery_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#upholstery').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#upholstery').val(0);
$(this).parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".upholstery_el #uniform-upholstery_el input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#upholstery').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {
$(this).parent().parent().parent().parent().find('#upholstery').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "click", ".floormat_el input[type=checkbox]", function() {
if($(this).is(":checked")) {
$(this).parent().parent().find('#floormat').val($(this).val());
$(this).parent().addClass('addon-checked');
}
else {
$(this).parent().parent().find('#floormat').val(0);
$(this).parent().removeClass('addon-checked');
}

});

$( "#phone-order-form" ).on( "click", ".floormat_el #uniform-floormat_el input[type=checkbox]", function() {
    $(this).parent().parent().parent().parent().find('.isaddonschanged').val(1);
if($(this).is(":checked")) {
$(this).parent().parent().parent().parent().find('#floormat').val($(this).val());
$(this).parent().removeClass('addon-checked');
$(this).parent().parent().parent().addClass('addon-checked');
}
else {
$(this).parent().parent().parent().parent().find('#floormat').val(0);
$(this).parent().parent().parent().removeClass('addon-checked');
}

});


$( "#phone-order-form" ).on( "change", ".regular-model", function() {
	parent_el = $(this).parent();
	make = $(parent_el).find('.regular-make').val();
	model = $(this).val();
    //alert($(this).find(':selected').data('cat'));
    var parentid = $(this).parent().attr('id');


          var car_cat = $("#"+parentid).find('select.regular-model option:selected').data('cat');
var car_type = $("#"+parentid).find('select.regular-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}

  $("#"+parentid).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#"+parentid).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");

});


$( "#phone-order-form" ).on( "change", ".classic-model", function() {
parent_el = $(this).parent();
make = $(parent_el).find('.classic-make').val();
model = $(this).val();
var parentid = $(this).parent().attr('id');

  var car_cat = $("#"+parentid).find('select.classic-model option:selected').data('cat');
var car_type = $("#"+parentid).find('select.classic-model option:selected').data('type');
//console.log(car_cat);

var clay_bar_price = 40;
var floormat_price = 10;

if(car_type == 'S') {
 clay_bar_price = 40;
 floormat_price = 10;
}
if(car_type == 'M') {
  clay_bar_price = 42.50;
  floormat_price = 12.50;
}
if(car_type == 'L') {
  clay_bar_price = 45;
  floormat_price = 15;
}
if(car_type == 'E') {
  clay_bar_price = 45;
  floormat_price = 15;
}

  $("#"+parentid).find('.extclaybar').html("<input type='checkbox' id='extclaybar' value='"+clay_bar_price+"'> $"+clay_bar_price+" Clay Bar & Paste Wax");
  $("#"+parentid).find('.floormat_el').html("<input type='checkbox' id='floormat_el' value='"+floormat_price+"'> $"+floormat_price+" Floor Mat Cleaning");


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


$(".pay-methods li input").click(function(){
$(".add-card-wrap").hide();
$(".add-card-wrap input").removeAttr('required');
$(".add-card-wrap input").val('');
$("#pay_method_token").val($(this).val());
$("#card_ending_no").val($(this).parent().parent().parent().parent().data('ending'));
$("#card_type").val($(this).parent().parent().parent().parent().data('type'));
if($(this).val() != initial_pay_token) $("#is_token_changed").val(1);
if($(this).val() == initial_pay_token) $("#is_token_changed").val(0); 
});

$(".paymethod-add-trigger").click(function(){
$(".add-card-wrap").slideDown();
$(".add-card-wrap input").attr('required', 'required');
$(".add-card-wrap input#bill_apt").removeAttr('required');
$(".pay-methods li input").removeAttr('checked');
$(".pay-methods li span").removeClass('checked');
$("#pay_method_token").val('');
$("#card_ending_no").val('');
$("#card_type").val('');
$("#is_token_changed").val(0);
return false;
});

});


</script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        
   <script src="js/jquery.bxslider.min.js"></script>
   
   <script>
$(function(){
  $('.bxslider').bxSlider();
});
</script>

<script>
var order_status = "<?php echo $getorder->status; ?>";
$(function(){
$("#checkout").submit(function(){
var th = $(this);
$("#checkout input[type='submit']").val('Processing...');
$(".err-text").hide();
var spdisc = $("#checkout #spdisc").val();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminschedulewashprocesspayment", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", agent_id: "<?php echo $getorder->agent_id; ?>", spdisc: spdisc, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$("#checkout input[type='submit']").val('Process');
 $(".popup-overlay").hide();

}

});

return false;

});


$(".cancel-order").click(function(){
var c = confirm('Are you sure you want to cancel this order?');
if(c){
$('#cancel-order-pop').dialog('open');
}

return false;

});


$(".cancel-order-ondemand").click(function(){
var c = confirm('Are you sure you want to cancel this order?');
if(c){
    $('#cancel-order-ondemand-pop').dialog('open');
}

return false;

});


$(".process-free-wash").click(function(){
var c = confirm('Are you sure you want to process this order as free?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('process-free-wash');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminschedulewashprocesspaymentfree", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Free Wash');
$(th).addClass('process-free-wash');

}

});
}

return false;

});


$(".release-payment").click(function(){
var c = confirm('Are you sure you want to release payment?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('release-payment');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/releaseescrow", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", transaction_id: "<?php echo $getorder->transaction_id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Release Payment');
$(th).addClass('release-payment');

}

});
}

return false;

});


$(".void-payment").click(function(){
var c = confirm('Are you sure you want to void payment?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('void-payment');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/voidpayment", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", transaction_id: "<?php echo $getorder->transaction_id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Void Payment');
$(th).addClass('void-payment');

}

});
}

return false;

});


$(".refund-payment").click(function(){
var c = confirm('Are you sure you want to refund payment?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('refund-payment');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/refundpayment", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", transaction_id: "<?php echo $getorder->transaction_id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Refund Payment');
$(th).addClass('refund-payment');

}

});
}

return false;

});


$(".submit_settle_btn").click(function(){
var c = confirm('Are you sure you want to submit payment for settlement?');
if(c){
var th = $(this);
var amount = $("#submit_for_settle_amount").val();
$(this).val('Processing, please wait...');
$(this).removeClass('submit_settle_btn');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/submitforsettlement", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", transaction_id: "<?php echo $getorder->transaction_id; ?>", amount: amount, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).val('Submit for Settlement');
$(th).addClass('submit_settle_btn');

}

});
}

return false;

});


$(".pass-fraud").click(function(){
var c = confirm('Are you sure you want to release the order from fraud?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('pass-fraud');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/passfraud", { wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Pass Fraud');
$(th).addClass('pass-fraud');

}

});
}

return false;

});

$(".wash-uncancel").click(function(){
var c = confirm('Are you sure you want to un-cancel this wash?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('wash-uncancel');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminuncancel", { wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Un-Cancel');
$(th).addClass('wash-uncancel');

}

});
}

return false;

});

$(".client-receipt-send").click(function(){
var c = confirm('Are you sure you want to resend client receipt?');
if(c){
var th = $(this);
$(this).html('Sending...');
$(this).removeClass('client-receipt-send');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/sendclientappreceipt", { customer_id: "<?php echo $getorder->customer_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
$(th).html('Receipt Sent');
setTimeout(function(){
$(th).html('Client Receipt');
$(th).addClass('client-receipt-send');
}, 3000);
updateactivitylogs();
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Client Receipt');
$(th).addClass('client-receipt-send');

}

});
}

return false;

});


$(".agent-receipt-send").click(function(){
var c = confirm('Are you sure you want to resend detailer receipt?');
if(c){
var th = $(this);
$(this).html('Sending...');
$(this).removeClass('agent-receipt-send');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/sendagentappreceipt", { agent_id: "<?php echo $getorder->agent_id; ?>", wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
$(th).html('Receipt Sent');
setTimeout(function(){
$(th).html('Detailer Receipt');
$(th).addClass('agent-receipt-send');
}, 3000);
updateactivitylogs();
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Detailer Receipt');
$(th).addClass('agent-receipt-send');

}

});
}

return false;

});

$(".company-receipt-send").click(function(){
 window.open("<?php echo ROOT_URL; ?>/company-full-receipt.php?orderid=<?php echo $getorder->id; ?>",'_blank');

});

$(".reschedule_update").click(function(){
    if(!$("#phone-order-form #reschedule_date").val()){
        alert('Please select reschedule date');
        return false;
    }
var c = confirm('Are you sure you want to reschedule wash?');
if(c){
var th = $(this);
var resched_date = $("#phone-order-form #reschedule_date").val();
var resched_time = $("#phone-order-form #reschedule_time").val();
$(this).val('Saving...');
$(this).removeClass('reschedule_update');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/updatewashadmin", { reschedule_date: resched_date, reschedule_time: resched_time, admin_command: 'save-reschedule', wash_request_id: "<?php echo $getorder->id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
    $(th).addClass('reschedule_update');
//$(th).val('Save');
//updateactivitylogs();
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).val('Save');
$(th).addClass('reschedule_update');

}

});
}

return false;

});


$(".note_update").click(function(){

var th = $(this);
var notes = $("#phone-order-form #notes").val();
$(this).val('Saving...');
$(this).removeClass('note_update');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/updatewashadmin", { notes: notes, admin_command: 'save-note', admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
    $(th).addClass('note_update');
$(th).val('Save');
updateactivitylogs();
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).val('Save');
$(th).addClass('note_update');

}

});


return false;

});

$(".send_washer_push").click(function(){

var th = $(this);
var msg = $("#phone-order-form #washer_push_msg").val();
$(this).val('Sending...');
$(this).parent().next().html('');
$(this).parent().next().removeClass('error');
$(this).parent().next().removeClass('success');
$(this).removeClass('send_washer_push');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/sendwasherpush", { message: msg, agent_id: "<?php echo $getorder->agent_id; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){

if(data.result == 'true'){
    $(th).addClass('send_washer_push');
$(th).val('Send');
$(th).parent().next().html(data.response)
$(th).parent().next().addClass('success');
updateactivitylogs();
}
else{
$(th).parent().next().html(data.response)
$(th).parent().next().addClass('error');
$(th).val('Send');
$(th).addClass('send_washer_push');

}

});


return false;

});

$("#phone-order-form #washer_push_msg").click(function(){
   $(".status-text").html('');
$(".status-text").removeClass('error');
$(".status-text").removeClass('success'); 
});

$(".washer_update").click(function(){
    var c;
if((order_status != 0) && (order_status != 5) && (order_status != 6)){
    c = confirm('Order is currently being processed and changing washer is not recommended. Are you sure you want to change washer?')
}
else{
    c = true;
}
if(c == true){
var th = $(this);
var agent_id = $("#phone-order-form #detailer").val();
$(this).val('Saving...');
$(this).removeClass('washer_update');
$(".err-text").hide();
 var socket = io.connect("209.95.41.9:3000", { query: "action=commandcenter" });
    socket.on('connect', function() {
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminchangewasher", { agent_id: agent_id, admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
  
   var wash_id = "<?php echo $getorder->specid; ?>";
   
socketId = socket.io.engine.id;
  //console.log(socketId);

    if((agent_id == 0) && (order_status == 0)) {
      socket.emit('currentwashschedulealert',{wash_request_id: wash_id, socketId:socketId});
      }
   
   $(th).addClass('washer_update');
$(th).val('Save Washer');
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";

  
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).val('Save Washer');
$(th).addClass('washer_update');

}

});
 });
}

return false;

});

$(".order-status-update").click(function(){
    var order_status = $(this).data('status');
    var text = $(this).html();
if(order_status == 1) var c = confirm('Are you sure you want to start job?');
if(order_status == 2) var c = confirm('Are you sure you want to arrive at wash location?');
if(order_status == 3) var c = confirm('Are you sure you want to start processing order?');
if(order_status == 4) var c = confirm('Are you sure you want to complete order?');
if(c){
var th = $(this);
$(this).html('Processing, please wait...');
$(this).removeClass('order-status-update');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/updatewashadmin", { status: order_status, admin_command: 'save-status', admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: "<?php echo $getorder->id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html(text);
$(th).addClass('order-status-update');

}

});
}

return false;

});

$( ".pop-close" ).click(function(){
   $(".popup-overlay").fadeOut();
   return false;
});

$( ".process-payment" ).click(function(){
$(".err-text").html('');
$(".err-text").hide();
   $(".popup-overlay").fadeIn();
   return false;
});


$(".card-remove").click(function(){
var c = confirm('Are you sure you want to remove this card?');
if(c){
var th = $(this);
var token = $(th).data('token');
$(this).html('Removing...');
$(this).removeClass('card-remove');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=customers/deletecustomerpaymentmethod", { token: token, cust_type: '', admin_username: "<?php echo $jsondata_permission->user_name; ?>", wash_request_id: <?php echo $getorder->id; ?>, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.success == 1){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Remove');
$(th).addClass('card-remove');

}

});
}

return false;

});

$(".addr-remove").click(function(){
var c = confirm('Are you sure you want to remove this address?');
if(c){
var th = $(this);
var locid = $(th).data('locid');
$(this).html('Removing...');
$(this).removeClass('addr-remove');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=customers/deletelocation", { location_id: locid, wash_request_id: <?php echo $getorder->id; ?>, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $getorder->id; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Remove');
$(th).addClass('addr-remove');

}

});
}

return false;

});


  $(".stop-washer-pay").click(function(){
var c = confirm('Are you sure you want to stop washer payment?');
if(c){
var th = $(this);
$(this).html('Processing...');
$(this).removeClass('stop-washer-pay');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminupdatewasherpaystatus", { status: 2, wash_request_id: "<?php echo $_GET['id']; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $_GET['id']; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Stop Washer Payment');
$(th).addClass('stop-washer-pay');

}

});
}

return false;

});


       $(".enable-washer-pay").click(function(){
var c = confirm('Are you sure you want to enable washer payment?');
if(c){
var th = $(this);
$(this).html('Processing...');
$(this).removeClass('enable-washer-pay');
$(".err-text").hide();
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminupdatewasherpaystatus", { status: 'ZERO', wash_request_id: "<?php echo $_GET['id']; ?>", admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $_GET['id']; ?>";
}
else{
$(".err-text").html(data.response);
$(".err-text").show();
$(th).html('Enable Washer Payment');
$(th).addClass('enable-washer-pay');

}

});
}

return false;

});

});
</script>

<script>
$(function(){

var wash_id = "<?php echo $getorder->id; ?>";
var admin_id = "<?php echo $jsondata_permission->user_id; ?>";
var customer_id = "<?php echo $getorder->customer_id; ?>";
//console.log(customer_id);

function checkadmineditstatus(){
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/updateadminscheduleeditstatus", { wash_request_id: wash_id, admin_id: admin_id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
$("form #edit-order-submit").show();
$(".admin-edit-alert").html("");
$(".admin-edit-alert").hide();
}
else if(data.result == 'edit disable'){
$("form #edit-order-submit").hide();
$(".admin-edit-alert").html(data.response);
$(".admin-edit-alert").show();
}


});
}

checkadmineditstatus();

 var ic = setInterval(checkadmineditstatus, 10000);


 $("#order_status").change(function(e){
     if($("#detailer").val() == 0){
       if(($(this).val() == 1) || ($(this).val() == 2) || ($(this).val() == 3) || ($(this).val() == 6)){
      alert('Please select a washer first');
     $(this).val(0);
     return false;
  }
     }


 });



});

</script>

<?php if($getorder->status != 1): ?>
<script>
  $(function(){
    var wash_id = "<?php echo $getorder->id; ?>";
var admin_id = "<?php echo $jsondata_permission->user_id; ?>";
var customer_id = "<?php echo $getorder->customer_id; ?>";

   function checkwashstatus(){
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=washing/washingkart", { wash_request_id: wash_id, api_password: "<?php echo AES256CBC_API_PASS; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if((data.result == 'true') && (data.status == 1) && (data.is_scheduled == 1)){
//$('#washer-arrive-dialog').dialog('open');
window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-order.php?id=<?php echo $_GET['id']; ?>";
}

if(data.result == 'true' && data.status == 3){
$(data.vehicles).each(function(e, veh){
if(veh.status == 2){
  current_vehicle_id = veh.id;
    $('#car-inspect-dialog').html("<p>"+veh.brand_name+" "+veh.model_name+"</p><p><img src='"+veh.vehicle_inspect_image_temp+"' style='width: 100%;' /></p>");

  //$('#car-inspect-dialog').dialog('open');
  return false;
}
});
}



});
}

checkwashstatus();

 var ic2 = setInterval(checkwashstatus, 5000);
});

</script>
 <?php endif; ?> 
       <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
  $( function() {

       var availableTags = new Array();
    <?php foreach($allagents as $agent){ ?>
    var obj = {};
obj['label'] = '<?php echo $agent->label; ?>';
obj['value'] = '<?php echo $agent->value; ?>';
        availableTags.push(obj);
    <?php } ?>
    $( ".portlet-body form #agentname" ).autocomplete({
      source: availableTags,
       select: function(event, ui) {
           event.preventDefault();
       //console.log(ui.item.label);
        //console.log(ui.item.value);
        $(".portlet-body form #detailer").val(ui.item.value);
        $(this).val(ui.item.label);
        //return false; // Prevent the widget from inserting the value.
    }
    });

     $( ".portlet-body form #agentname" ).keyup(function(){
        if(!$(this).val()){
            $(".portlet-body form #detailer").val(0);
        }
     });


  } );
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

function updateactivitylogs(){
  $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/getwashersavedroplog", { wash_request_id: "<?php echo $getorder->id; ?>", key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4' }, function(data){
//console.log(data);
if(data.result == 'true'){
    var contents = "";
   $.each( data.logs, function( i, log ) {
       
     if(log.action == 'savejob'){
        if(log.admin_username){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" assigned #"+log.agent_company_id+" at "+log.formatted_action_date+"</p>";  
        } 
        else{
            contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" assigned "+log.formatted_action_date+"</p>";
        }
     } 
      <?php if(($getorder->is_scheduled)): ?>
      if(log.action == 'dropjob_ratingunchange'){
          /*contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" dropped order at "+log.formatted_action_date+"</p>. Reason: "+log.addi_detail; */
          contents += "<p style='margin-bottom: 10px; color:red;'>Washer #"+log.agent_company_id+" feedback at "+log.formatted_action_date+" : "+log.addi_detail+'</p>';  
      }
      if((log.action == 'dropjob') && (!log.admin_username)){
          contents += "<p style='margin-bottom: 10px; color:red;'>Washer #"+log.agent_company_id+" feedback at "+log.formatted_action_date+" : "+log.addi_detail+'</p>';   
      }
      if(log.action == 'Dropschedule'){
          contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" dropped order at "+log.formatted_action_date+"</p>"; 
      }
      <?php endif; ?>
      if(log.action == 'admindropjob'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" dropped washer #"+log.agent_company_id+" from the order at "+log.formatted_action_date+"</p>";
      }
               
          if(log.action == 'reschedule'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" re-scheduled order at "+log.formatted_action_date+"</p>";  
      } 
      
        if(log.action == 'savenote'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added order note at "+log.formatted_action_date+"</p>";  
      } 
      
       if(log.action == 'savecustomernote'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added Customers note at "+log.formatted_action_date+"</p>";  
      } 
       
       if(log.action == 'savewashnote'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added washer note at "+log.formatted_action_date+"</p>";  
      } 
       
       if(log.action == 'editorder'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" edited order at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'refundpayment'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" refunded payment at "+log.formatted_action_date+"</p>";  
      }
      
      if(log.action == 'processpayment'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" processed payment at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'voidpayment'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" voided payment at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'cancelorder'){
          contents += "<p style='margin-bottom: 10px; color:red;'>"+log.admin_username+" canceled order at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'agentreceiptsend'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" sent washer receipt at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'clientreceiptsend'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" sent client receipt at "+log.formatted_action_date+"</p>";  
      } 
      
       if(log.action == 'addlocation'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added location at "+log.formatted_action_date+"</p>";  
      } 
      
      if(log.action == 'updatelocation'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" updated location at "+log.formatted_action_date+"</p>";  
      }
       
       if(log.action == 'deletelocation'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" deleted location at "+log.formatted_action_date+"</p>";  
      }
      
       if(log.action == 'deletepaymentmethod'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" deleted payment method at "+log.formatted_action_date+"</p>";  
      }
      
      if(log.action == 'addpaymentmethod'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added payment method at "+log.formatted_action_date+"</p>";  
      }
      
      if(log.action == 'updatepaymentmethod'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" updated payment method at "+log.formatted_action_date+"</p>";  
      }
      
      if(log.action == 'washerpush'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" sent washer push notification at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'passfraud'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" released order from fraud at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'startjob'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" accepted request at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'arrivejob'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" arrived at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'processjob'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" processed order at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'completejob'){
          contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" completed order at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'washerstartjob'){
            <?php if($getorder->is_scheduled == 1): ?>
            contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" tapped Drive Now at "+log.formatted_action_date+"</p>";
            <?php else: ?>
            contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" accepted request at "+log.formatted_action_date+"</p>";
            <?php endif; ?>
            
      }

      if(log.action == 'washerarrivejob'){
            contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" arrived at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'washerprocessjob'){
            contents += "<p style='margin-bottom: 10px;'>#"+log.agent_company_id+" started processing at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'appcompletejob'){
            contents += "<p style='margin-bottom: 10px;'>Order completed at "+log.formatted_action_date+"</p>";
      }

       if(log.action == 'freewash'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" gives free wash at "+log.formatted_action_date+"</p>";
      }

       if(log.action == 'uncancel'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" un-canceled order at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'cancelorderclient'){
            contents += "<p style='margin-bottom: 10px; color:red;'>Customer canceled order at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'cancelorderwasher'){
            contents += "<p style='margin-bottom: 10px; color:red;'>Washer #"+log.agent_company_id+" canceled order at "+log.formatted_action_date+"</p>";
      }

      if(log.action == 'washerenroutecancel'){
            contents += "<p style='margin-bottom: 10px; color:red;'>Washer #"+log.agent_company_id+" canceled order enroute at "+log.formatted_action_date+"</p>";
      }

       if(log.action == 'adminstopwasherpayment'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" stopped washer payment at "+log.formatted_action_date+"</p>";
      }

       if(log.action == 'adminenablewasherpayment'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" enabled washer payment at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'washeraddcar'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id+" added "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'washerchangepack'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id+" changed package/addons "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
      if(log.action == 'adminsubmitforsettlement'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" submitted payment for settlement at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'meetwasherbeforeinspect'){
        if (log.addi_detail == 'yes') {
         contents += "<p style='margin-bottom: 10px;'>Customer hit 'Meet Washer Outside' before inspection at "+log.formatted_action_date+"</p>";
        }
        
        if (log.addi_detail == 'no') {
         contents += "<p style='margin-bottom: 10px;'>Customer hit 'No Thanks' before inspection at "+log.formatted_action_date+"</p>";
        } 
            
      }
      
       if(log.action == 'meetwasherwashend'){
        if (log.addi_detail == 'yes') {
         contents += "<p style='margin-bottom: 10px;'>Customer hit 'Meet Washer Outside' after wash at "+log.formatted_action_date+"</p>";
        }
        
        if (log.addi_detail == 'no') {
         contents += "<p style='margin-bottom: 10px;'>Customer hit 'No Thanks' after wash at "+log.formatted_action_date+"</p>";
        } 
            
      }
      
        if(log.action == 'washereditcar'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id+" edited vehicle to "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'adminaddcar'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" added "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'adminchangepack'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" changed package "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
      if(log.action == 'adminchangeaddons'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" changed addons "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'washerremovecar'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id+" removed "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'adminremovecar'){
            contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" removed "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'customerfeedback'){
            contents += "<p style='margin-bottom: 10px; color:red;'>Customer feedback at "+log.formatted_action_date +" : "+ log.addi_detail+"</p>";
      }
      
       if(log.action == 'agentfeedback'){
            /*"+log.formatted_action_date +"*/
		    contents += "<p style='margin-bottom: 10px; color: red;'>Washer #"+log.agent_company_id+" finishes adding the feedback for that dropped order : "+ log.addi_detail+"</p>";
      }
      
       if(log.action == 'customercall'){
            contents += "<p style='margin-bottom: 10px;'>Customer called washer #"+log.agent_company_id +" at "+ log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'agentcall'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id +" called customer at "+ log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'agentbuzz'){
            contents += "<p style='margin-bottom: 10px;'>Washer #"+log.agent_company_id +" buzzed customer at "+ log.formatted_action_date+"</p>";
      }
      
       if(log.action == 'customeracceptupgrade'){
            contents += "<p style='margin-bottom: 10px;'>Customer accepted package/addons "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      
      if(log.action == 'customerrejectupgrade'){
            contents += "<p style='margin-bottom: 10px;'>Customer declined package/addons "+ log.addi_detail +" at "+log.formatted_action_date+"</p>";
      }
      if(log.action == 'tipamount'){
      contents += "<p style='margin-bottom: 10px;'>"+log.admin_username+" edited tip amount from "+ log.addi_detail+" at "+log.formatted_action_date+"</p>";
      }
      if(log.action == 'customertipamount'){
        contents += "<p style='margin-bottom: 10px;'>Customer edited tip amount from "+ log.addi_detail+" at "+log.formatted_action_date+"</p>";
       }
       if(log.action == 'customeraddtipamount'){
        contents += "<p style='margin-bottom: 10px;'>Customer added "+ log.addi_detail+" tip amount at "+log.formatted_action_date+"</p>";
       }
       
       if(log.action == 'agentreinspectopt'){
        contents += "<p style='margin-bottom: 10px;'>Washer # "+log.agent_id+" choose reinspect for "+ log.addi_detail+" at "+log.formatted_action_date+"</p>";
       }
       
       if(log.action == 'agentnodamageopt'){
        contents += "<p style='margin-bottom: 10px;'>Washer # "+log.agent_id+" choose no damage for "+ log.addi_detail+" at "+log.formatted_action_date+"</p>";
       }
       
       if(log.action == 'clientwashnowschedule'){
        contents += "<p style='margin-bottom: 10px;'>Customer changed Wash Now to Scheduled at "+log.formatted_action_date+"</p>";
       }
       
       if(log.action == 'ondemandautocancel'){
        contents += "<p style='margin-bottom: 10px;  color:red;'>Customer's On Demand auto-canceled at "+log.formatted_action_date+"</p>";
       }
       
       if(log.action == 'scheduleauto-canceled'){
        contents += "<p style='margin-bottom: 10px;  color:red;'>Scheduled Order auto-canceled at "+log.formatted_action_date+"</p>";
       }

   });
   
   $(".activity-logs").html(contents);

}


});  
}



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