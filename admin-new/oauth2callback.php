<?php
require_once('../api/protected/config/constant.php');
// include your composer dependencies
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->setRedirectUri(ROOT_URL.'/admin-new/oauth2callback.php');
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();

  if((isset($_GET['redirectpage'])) && ($_GET['redirectpage'] == 'zipcode-pricing')) $redirect_uri = ROOT_URL. '/admin-new/zipcode-pricing.php';
  else $redirect_uri = ROOT_URL. '/admin-new/coverage-area-zipcodes.php'; 
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>