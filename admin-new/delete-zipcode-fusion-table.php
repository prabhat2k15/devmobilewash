<?php
require_once('../api/protected/config/constant.php');
session_start();
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
$zip = $_GET['zipcode'];
$row_ids = array();
  $client->setAccessToken($_SESSION['access_token']);
$tableId = '1Ck5Bulp_3881RFZqDRNb5yl-HgHnwA-p9Vv2JB-k';
        $ft = new Google_Service_Fusiontables($client);

 $result = $ft->query->sql("SELECT ROWID, ZIPCODE FROM $tableId");
//print_r($result->rows);
foreach($result->rows as $rr){
if($rr[1] == $zip){
array_push($row_ids, $rr[0]); 
}

}


foreach($row_ids as $rid) $ft->query->sql("UPDATE $tableId SET MW_COVERAGE_AREA = '', ZIP_COLOR = '' WHERE ROWID = '$rid'");

 $json = array("result" => 'true', "zip" => $zip);
echo json_encode($json);       
} else {
  $redirect_uri =  ROOT_URL.'/admin-new/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>