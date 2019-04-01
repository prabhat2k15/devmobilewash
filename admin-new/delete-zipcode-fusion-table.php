<?php

require_once('../api/protected/config/constant.php');
session_start();
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->setAccessType('offline');
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $zip = $_GET['zipcode'];
    $row_ids = array();
    $client->setAccessToken($_SESSION['access_token']);
    $tableId = '1ECb-guhoNwEE3leCYpBbdRcpXVhTHcbraYX9yt54';
    $ft = new Google_Service_Fusiontables($client);
    $ft->query->sql("UPDATE $tableId SET MW_COVERAGE_AREA = '', ZIP_COLOR = '' WHERE ZIPCODE = '$zip'");

    $json = array("result" => 'true', "zip" => $zip);
    echo json_encode($json);
} else {
    $redirect_uri = ROOT_URL . '/admin-new/oauth2callback.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>