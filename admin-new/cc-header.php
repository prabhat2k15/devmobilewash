<?php
require_once('../api/protected/config/constant.php');
/* -------- logged in auth --------- */

$device_token = '';
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$data = array("device_token"=>$device_token, "key" => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=users/authenticate");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($response == "error" && $result_code == "false"){
header("Location: ".ROOT_URL."/admin-new/login.php");
die();
}


/* -------- logged in auth end --------- */

parse_str($_SERVER['QUERY_STRING']);
if($action=="logout"){
$device_token = $_COOKIE["mw_admin_auth"];
$data = array("device_token"=>$device_token, "key" => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=users/logout");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($response == "Successfully logout" && $result_code == "true"){
unset($_COOKIE['mw_admin_auth']);
setcookie("mw_admin_auth", "", time() - 3600);
header("Location: ".ROOT_URL."/admin/login.php");
die();
}
else{
header("Location: ".ROOT_URL."/admin/login.php");
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