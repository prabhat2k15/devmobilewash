<?php

session_start();
if ($_GET['action'] == 'savezip') {
    require_once('../api/protected/config/constant.php');

    $finalusertoken = '';
    $mw_admin_auth_arr = array();
    if (isset($_COOKIE['mw_admin_auth'])) {
        $mw_admin_auth = base64_decode($_COOKIE["mw_admin_auth"]);
        $mw_admin_auth_arr = explode("@@009654A!*csT=", $mw_admin_auth);

        $device_token = $mw_admin_auth_arr[0];
        $keydecode = base64_decode($mw_admin_auth_arr[2]);
        $ivdecode = base64_decode($mw_admin_auth_arr[3]);
        $key_pt1 = substr($keydecode, 12, 8);
        $key_pt2 = substr($keydecode, -22, 8);

        $fullkey = $key_pt1 . $key_pt2;

        $iv_pt1 = substr($ivdecode, 12, 8);
        $iv_pt2 = substr($ivdecode, -22, 8);

        $fulliv = $iv_pt1 . $iv_pt2;

        $string_decode = base64_decode($mw_admin_auth_arr[1]);

        $string_plain = openssl_decrypt($string_decode, "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);

        $decodestrarr = explode("tmn!!==*", $string_plain);
        $timestamp_fct = $decodestrarr[1];
        $decodedstr2 = substr($decodestrarr[0], 25);
        $user_token_str = substr($decodedstr2, 0, -25);

        $rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

        $first_25 = substr($rand_bytes, 0, 25);
        $last_25 = substr($rand_bytes, -25, 25);

        $ciphertext_raw = openssl_encrypt($first_25 . $user_token_str . $last_25 . "tmn!!==*" . time(), "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);
        $finalusertoken = base64_encode($ciphertext_raw);
    } else {
        $json = array("result" => 'false', "response" => "Invalid request");
        echo json_encode($json);
        die();
    }

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


        $userdata = array('zipcode' => $zip, 'zipcolor' => $_GET['zipcolor'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
        $handle_data = curl_init(ROOT_URL . "/api/index.php?r=washing/updatecoveragezipcode");
        curl_setopt($handle_data, CURLOPT_POST, true);
        curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
        curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle_data);
        curl_close($handle_data);
        $jsondata = json_decode($result);
        if ($jsondata->result == 'true') {
            $ft->query->sql("UPDATE $tableId SET ZIP_COLOR = '" . $_GET['zipcolor'] . "' WHERE ZIPCODE = '$zip'");
            $json = array("result" => 'true', "response" => 'Zipcode ' . $zip . ' updated successfully');
            echo json_encode($json);
        } else {
            $json = array("result" => 'false', "response" => $jsondata->response);
            echo json_encode($json);
        }
    } else {
        $json = array("result" => 'false', "response" => "Please authenticate first from <a href='" . ROOT_URL . "/admin-new/coverage-area-zipcodes.php'>here</a>");
        echo json_encode($json);
    }
}

if ($_GET['action'] == 'savegroupzips') {
    require_once('../api/protected/config/constant.php');

    $finalusertoken = '';
    $mw_admin_auth_arr = array();
    if (isset($_COOKIE['mw_admin_auth'])) {
        $mw_admin_auth = base64_decode($_COOKIE["mw_admin_auth"]);
        $mw_admin_auth_arr = explode("@@009654A!*csT=", $mw_admin_auth);

        $device_token = $mw_admin_auth_arr[0];
        $keydecode = base64_decode($mw_admin_auth_arr[2]);
        $ivdecode = base64_decode($mw_admin_auth_arr[3]);
        $key_pt1 = substr($keydecode, 12, 8);
        $key_pt2 = substr($keydecode, -22, 8);

        $fullkey = $key_pt1 . $key_pt2;

        $iv_pt1 = substr($ivdecode, 12, 8);
        $iv_pt2 = substr($ivdecode, -22, 8);

        $fulliv = $iv_pt1 . $iv_pt2;

        $string_decode = base64_decode($mw_admin_auth_arr[1]);

        $string_plain = openssl_decrypt($string_decode, "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);

        $decodestrarr = explode("tmn!!==*", $string_plain);
        $timestamp_fct = $decodestrarr[1];
        $decodedstr2 = substr($decodestrarr[0], 25);
        $user_token_str = substr($decodedstr2, 0, -25);

        $rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

        $first_25 = substr($rand_bytes, 0, 25);
        $last_25 = substr($rand_bytes, -25, 25);

        $ciphertext_raw = openssl_encrypt($first_25 . $user_token_str . $last_25 . "tmn!!==*" . time(), "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);
        $finalusertoken = base64_encode($ciphertext_raw);
    } else {
        $json = array("result" => 'false', "response" => "Invalid request");
        echo json_encode($json);
        die();
    }

    require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

    $client = new Google_Client();
    $client->setAuthConfigFile('client_secret_947329153849.json');
    $client->setAccessType('offline');
    $client->addScope('https://www.googleapis.com/auth/fusiontables');

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $zips = $_GET['zipcode'];
        $zips = rtrim($zips, ",");
        $zip_arr = explode(",", $zips);
        $zip_str = join("','", $zip_arr);
        $row_ids = array();
        $client->setAccessToken($_SESSION['access_token']);
        $tableId = '1ECb-guhoNwEE3leCYpBbdRcpXVhTHcbraYX9yt54';
        $ft = new Google_Service_Fusiontables($client);


        $userdata = array('zipcode' => $zips, 'zipcolor' => $_GET['zipcolor'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
        $handle_data = curl_init(ROOT_URL . "/api/index.php?r=washing/updategroupcoveragezipcodes");
        curl_setopt($handle_data, CURLOPT_POST, true);
        curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
        curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle_data);
        curl_close($handle_data);
        $jsondata = json_decode($result);
        if ($jsondata->result == 'true') {
            $ft->query->sql("UPDATE $tableId SET ZIP_COLOR = '" . $_GET['zipcolor'] . "' WHERE ZIPCODE IN ('$zip_str')");

            $json = array("result" => 'true', "response" => 'Zipcodes ' . $zips . ' updated successfully');
            echo json_encode($json);
        } else {
            $json = array("result" => 'false', "response" => $jsondata->response);
            echo json_encode($json);
        }
    } else {
        $json = array("result" => 'false', "response" => "Please authenticate first from <a href='" . ROOT_URL . "/admin-new/coverage-area-zipcodes.php'>here</a>");
        echo json_encode($json);
    }
}
?>