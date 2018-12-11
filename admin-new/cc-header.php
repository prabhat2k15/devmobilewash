<?php
/*if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
  header("Location: ".ROOT_URL."/admin-new/command-center.php");
die();
}*/

require_once('../api/protected/config/constant.php');

/* -------- logged in auth --------- */

$device_token = '';
$finalusertoken = '';
$jsondata_permission = '';
$mw_admin_auth_arr = array();
if (isset($_COOKIE['mw_admin_auth'])) {
$mw_admin_auth = base64_decode($_COOKIE["mw_admin_auth"]);
$mw_admin_auth_arr = explode("@@009654A!*csT=",$mw_admin_auth);

$device_token = $mw_admin_auth_arr[0];
$keydecode = base64_decode($mw_admin_auth_arr[2]);
	$ivdecode = base64_decode($mw_admin_auth_arr[3]);
	$key_pt1 = substr($keydecode,12,8);
	$key_pt2 = substr($keydecode,-22,8);
	
	$fullkey = $key_pt1.$key_pt2;
	
	$iv_pt1 = substr($ivdecode,12,8);
	$iv_pt2 = substr($ivdecode,-22,8);
	
	$fulliv = $iv_pt1.$iv_pt2;
	
	$string_decode = base64_decode($mw_admin_auth_arr[1]);
	
	$string_plain = openssl_decrypt($string_decode, "AES-128-CBC", $fullkey, $options=OPENSSL_RAW_DATA, $fulliv);
	
	$decodestrarr = explode("tmn!!==*",$string_plain);
$timestamp_fct = $decodestrarr[1];
$decodedstr2 = substr($decodestrarr[0],25);
$user_token_str = substr($decodedstr2,0,-25);

$rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

$first_25 = substr($rand_bytes,0,25);
$last_25 = substr($rand_bytes,-25,25);

$ciphertext_raw = openssl_encrypt($first_25.$user_token_str.$last_25."tmn!!==*".time(), "AES-128-CBC", $fullkey, $options=OPENSSL_RAW_DATA, $fulliv);
$finalusertoken = base64_encode($ciphertext_raw);
}
else{
 header("Location: ".ROOT_URL."/admin-new/login.php");
die();   
}

$data = array("device_token"=>$device_token, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle = curl_init(ROOT_URL."/api/index.php?r=users/authenticate");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($result_code == "false"){
setcookie("mw_admin_auth", "", time() - 3600, "/", COOKIE_DOMAIN);
unset($_COOKIE['mw_admin_auth']);
header("Location: ".ROOT_URL."/admin-new/login.php");
die();
}

$userdata = array("user_token"=>$device_token, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);

if($jsondata_permission->result == "false"){
setcookie("mw_admin_auth", "", time() - 3600, "/", COOKIE_DOMAIN);
unset($_COOKIE['mw_admin_auth']);
header("Location: ".ROOT_URL."/admin-new/login.php");
die();
}

if($jsondata_permission->users_type == 'recruiter'){

	   header("Location: ".ROOT_URL."/admin-new/");
	die(); 


}

$userdata = array("user_token"=>$device_token, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/updateadminuserlastactivetime");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);


/* -------- logged in auth end --------- */

parse_str($_SERVER['QUERY_STRING']);
if($action=="logout"){
//$device_token = $_COOKIE["mw_admin_auth"];
$data = array("device_token"=>$device_token, "key" => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle = curl_init(ROOT_URL."/api/index.php?r=users/logout");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($result_code == "true"){
setcookie("mw_admin_auth", "", time() - 3600, "/", COOKIE_DOMAIN);
unset($_COOKIE['mw_admin_auth']);
header("Location: ".ROOT_URL."/admin-new/login.php");
die();
}
else{
header("Location: ".ROOT_URL."/admin/login.php");
die();
}
}
?>
<html>
<head>
<title>Admin Dashboard - MobileWash.com</title>

   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
  <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="admin-style.css" />
</head>