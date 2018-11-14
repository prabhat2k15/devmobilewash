<?php
require_once('../api/protected/config/constant.php');
session_start();
if(($_GET['step'] == 'mobile-verification') && ($_SESSION['step1_passed'] != 'true')){
  header("Location: ".ROOT_URL."/admin-new/login.php");
			die();  
}
if((isset($_POST['action'])) && ($_POST['action'] == 'admin_login')){
    
    // your secret key
$secret = "6LeOtnkUAAAAAPIk5nwLFQBPFgdzkbLJcU_K2YjF";

// empty response
$response = null;
$captcha_pass = 0;

if(isset($_POST['token']) && !empty($_POST['token'])){
    
    $handle = curl_init("https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('secret' => $secret, 'response' => $_POST['token']));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $verifyResponse = curl_exec($handle);
            curl_close($handle);
            $responseData = json_decode($verifyResponse);
 if ($responseData->success) {
$captcha_pass = 1;
}
else{
$captcha_pass = 2;
}
}

if($captcha_pass == 1){
    $ip = "";

if($_SERVER) {
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
$ip = $_SERVER['HTTP_CLIENT_IP'];
}else{
$ip = $_SERVER['REMOTE_ADDR'];
}
} else {
if(getenv('HTTP_X_FORWARDED_FOR')){
$ip = getenv('HTTP_X_FORWARDED_FOR');
}elseif(getenv('HTTP_CLIENT_IP')){
$ip = getenv('HTTP_CLIENT_IP');
}else{
$ip = getenv('REMOTE_ADDR');
}
}

$tempdevicedata = "device_id=web&device_name=web&device_token=".md5(uniqid(rand(), true))."&device_type=web&os_details=web";
$cryptokey = bin2hex(openssl_random_pseudo_bytes(8));
$iv = bin2hex(openssl_random_pseudo_bytes(8));
$tempdevicedata_raw = openssl_encrypt($tempdevicedata, "AES-128-CBC", $cryptokey, $options=OPENSSL_RAW_DATA, $iv);
$tempdevicedata_encrypted = base64_encode($tempdevicedata_raw);

$r1 = bin2hex(openssl_random_pseudo_bytes(6));
$r2 = bin2hex(openssl_random_pseudo_bytes(6));
$r3 = bin2hex(openssl_random_pseudo_bytes(7));
$r4 = bin2hex(openssl_random_pseudo_bytes(7));

$cryptokey_pt1 = substr($cryptokey,0,8);
$cryptokey_pt2 = substr($cryptokey,-8,8);

$cryptokeyencode = $r1.$cryptokey_pt1.$r2.$r3.$cryptokey_pt2.$r4; //[12][8][12][14][8][14]

$r1 = bin2hex(openssl_random_pseudo_bytes(6));
$r2 = bin2hex(openssl_random_pseudo_bytes(6));
$r3 = bin2hex(openssl_random_pseudo_bytes(7));
$r4 = bin2hex(openssl_random_pseudo_bytes(7));

$iv_pt1 = substr($iv,0,8);
$iv_pt2 = substr($iv,-8,8);

$ivencode = $r1.$iv_pt1.$r2.$r3.$iv_pt2.$r4; //[12][8][12][14][8][14]

$data = array("device_data"=>$tempdevicedata_encrypted, "t1" => base64_encode($cryptokeyencode),"t2" => base64_encode($ivencode),'key' => API_KEY);
	$handle = curl_init(ROOT_URL."/api/index.php?r=users/gettempaccesstoken");
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$apirawdata = curl_exec($handle);
	$gettempaccesstoken_result = json_decode($apirawdata);

	
	$finalusertoken = '';
	if($gettempaccesstoken_result->result == 'true'){
		$keydecode = base64_decode($gettempaccesstoken_result->t1);
	$ivdecode = base64_decode($gettempaccesstoken_result->t2);
	$key_pt1 = substr($keydecode,12,8);
	$key_pt2 = substr($keydecode,-22,8);
	
	$fullkey = $key_pt1.$key_pt2;
	
	$iv_pt1 = substr($ivdecode,12,8);
	$iv_pt2 = substr($ivdecode,-22,8);
	
	$fulliv = $iv_pt1.$iv_pt2;
	
	$string_decode = base64_decode($gettempaccesstoken_result->token);
	
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
	


if(($_GET['step'] == 'mobile-verification') && ($_SESSION['step1_passed'] == 'true')){
	$error = '';
	$device_token = md5(uniqid(rand(), true));
	$device_data = 'IP: '.$ip.' Device data: '.$_SERVER['HTTP_USER_AGENT'];
	$data = array("email"=>$_SESSION["user_email"], "verify_code"=>$_POST['verify-code'], "device_token"=>$device_token, 'device_data' => $device_data, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $gettempaccesstoken_result->t1, 't2' => $gettempaccesstoken_result->t2);
	$handle = curl_init(ROOT_URL."/api/index.php?r=users/adminlogincodeverify");
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($handle);

	curl_close($handle);
	$jsondata = json_decode($result);

	$response = $jsondata->response;
	$result_code = $jsondata->result;
	$username = $jsondata->username;
	$uid = $jsondata->uid;
	
	

	if($result_code == "true"){
$admin_auth_text = $device_token."@@009654A!*csT=".$jsondata->token."@@009654A!*csT=".$jsondata->t1."@@009654A!*csT=".$jsondata->t2."@@009654A!*csT=".$jsondata->user_id;
		if($_SESSION["rememberme"]){
		setcookie( "mw_admin_auth", base64_encode($admin_auth_text), time() + (86400 * 7), "/" ) ;
		//setcookie( "mw_username", $username, time() + (86400 * 7), "/" ) ;
		//setcookie( "mw_uid", $uid, time() + (86400 * 7), "/" ) ;
		
		}
		else{
		setcookie( "mw_admin_auth", base64_encode($admin_auth_text), time()+3600, "/" ) ;
		//setcookie( "mw_username", $username, time()+3600, "/" ) ;
		//setcookie( "mw_uid", $uid, time()+3600, "/" ) ;
		
		}
		
		session_unset(); 
session_destroy();

		if($jsondata->user_type == 'employee'){
			header("Location: ".ROOT_URL."/admin-new/all-orders.php?filter=&limit=400");
			die();
		}
		else{
			header("Location: ".ROOT_URL."/admin-new/");
			die();
		}
	}  
}
else{
  	$email = $_POST['email'];
	$password = $_POST['password'];
	$error = '';
	$data = array("email"=>$email, "password"=>$password, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $gettempaccesstoken_result->t1, 't2' => $gettempaccesstoken_result->t2);
	
	$handle = curl_init(ROOT_URL."/api/index.php?r=users/login");
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($handle);

	curl_close($handle);
	$jsondata = json_decode($result);
	

	$response = $jsondata->response;
	$result_code = $jsondata->result;
	$username = $jsondata->username;
	$uid = $jsondata->uid;

	if($result_code == "true"){
	    $_SESSION["step1_passed"] = "true";
	    $_SESSION["user_email"] = $email;
	    if($_POST['rememberme']) $_SESSION["rememberme"] = 1;
	    
	    	header("Location: ".ROOT_URL."/admin-new/login.php?step=mobile-verification");
			die();

	}  
}

}

}
?>

<!DOCTYPE HTML>

<html>

<head>
  <title>Admin Login - MobileWash.com</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="admin-style.css" />
  <script src='https://www.google.com/recaptcha/api.js?render=6LeOtnkUAAAAANP8yJjTTem9DIhT1_Ulp91kbKwG'></script>
</head>

<body class="admin-login">
<!--Container-->
<div id="container" class="on-canvas">
    <!--Content-->
    <div id="content">
        <div class="wrapper">
            <div class="login-wrap">
                <p style="margin: 0;"><a href="index.php"><img src="logo-new.png" alt="" /></a></p>
             <?php if($result_code == "false"): ?>
             <p class="error" style="background: #DC0000; color: #fff; padding: 12px;">
             <?php if($response == "Pass the required parameters") echo "Please provide email and password"; ?>
             <?php if($response == "Wrong email") echo "Email address incorrect"; ?>
             <?php if($response == "Wrong password") echo "Password incorrect"; ?>
             <?php if($response == "Wrong code") echo "Verification code incorrect"; ?>
             </p>
             <?php endif; ?>
                <?php if($_GET['step'] == 'mobile-verification'): ?>
                <form action="" method="post">
                    <p style="margin-top: 20px;">ENTER VERIFICATION CODE (sent to +18183313631)</p>
                    <p><input type="text" name="verify-code" placeholder="CODE" id="verify-code" autocomplete="off" style="font-size: 14px;" required /></p>
                    <p><input type="submit" value="VERIFY & LOG IN" name="admin-step2login-submit" /></p>
                </form>
                <?php else: ?>
                <form action="" method="post">
                    <p style="margin-top: 20px;">EMAIL</p>
                    <p><input type="email" name="email" placeholder="EMAIL ADDRESS" id="email" autocomplete="off" required /></p>
                    <p>PASSWORD</p>
                    <p><input type="password" name="password" placeholder="PASSWORD" id="password" required /></p>
                    <p><input type="checkbox" name="rememberme" id="rememberme" value="1" />&nbsp;&nbsp;REMEMBER ME</p>
                   
                    <p><input type="submit" value="LOG IN" name="admin-login-submit" /></p>
                </form>
                <p style="margin-bottom: 0; color: #fff;"><a href="forgot-password.php" class="forget-pass">Password Reset</a></p>
<?php endif; ?>
            </div>
        </div>
    </div><!--Content End-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    // when form is submit
    $('form').submit(function(event) { 
        // we stoped it
        event.preventDefault();
        // needs for recaptacha ready
        grecaptcha.ready(function() {
            // do request for recaptcha token
            // response is promise with passed token
            grecaptcha.execute('6LeOtnkUAAAAANP8yJjTTem9DIhT1_Ulp91kbKwG', {action: 'admin_login'}).then(function(token) {
                // add token to form
                $('form').prepend('<input type="hidden" name="token" value="' + token + '">');
                $('form').prepend('<input type="hidden" name="action" value="admin_login">');
                // submit form now
                $('form').unbind('submit').submit();
            });;
        });
    });

    </script>
    </body>
    </html>