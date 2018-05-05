<?php
session_start();
if($_GET['action'] == 'savezip'){
  require_once('../api/protected/config/constant.php');

require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->setAccessType('offline'); 
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
$zip = $_GET['zipcode'];
$row_ids = array();
  $client->setAccessToken($_SESSION['access_token']);
$tableId = '1Ck5Bulp_3881RFZqDRNb5yl-HgHnwA-p9Vv2JB-k';
        $ft = new Google_Service_Fusiontables($client);

	
        $userdata = array('zipcode' => $zip, 'zipcolor' => $_GET['zipcolor'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/updatecoveragezipcode");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$jsondata = json_decode($result);
if($jsondata->result == 'true'){
    $ft->query->sql("UPDATE $tableId SET ZIP_COLOR = '".$_GET['zipcolor']."' WHERE ZIPCODE = '$zip'");
   $json = array("result" => 'true', "response" => 'Zipcode '.$zip.' updated successfully');
echo json_encode($json);  
}
else{
  $json = array("result" => 'false', "response" => $jsondata->response);
echo json_encode($json);

}


} else {
  //$redirect_uri =  ROOT_URL.'/admin-new/oauth2callback.php';
  //header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  $json = array("result" => 'false', "response" => "Please authenticate first from <a href='".ROOT_URL."/admin-new/coverage-area-zipcodes.php'>here</a>");
echo json_encode($json); 
}

}



?>