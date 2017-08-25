<?php
if(isset($_POST['admin-login-submit'])){

	$email = $_POST['email'];
	$password = $_POST['password'];
	$error = '';
	$device_token = md5(uniqid(rand(), true));
	$data = array("email"=>$email, "password"=>$password, "device_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=users/login");
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

	if($response == "Successfully login" && $result_code == "true"){
		if($_POST['rememberme']){
		setcookie( "mw_admin_auth", $device_token, time() + (86400 * 30), "/" ) ;
		setcookie( "mw_username", $username, time() + (86400 * 30), "/" ) ;
		setcookie( "mw_uid", $uid, time() + (86400 * 30), "/" ) ;
		}
		else{
		setcookie( "mw_admin_auth", $device_token, time()+3600, "/" ) ;
		setcookie( "mw_username", $username, time()+3600, "/" ) ;
		setcookie( "mw_uid", $uid, time()+3600, "/" ) ;
		}

		if($jsondata->user_type == 'employee'){
			header("Location: http://www.devmobilewash.com/admin-new/all-orders.php?filter=&limit=400");
			die();
		}
		else{
			header("Location: http://www.devmobilewash.com/admin-new/");
			die();
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
             </p>
             <?php endif; ?>
                <form action="" method="post">
                    <p style="margin-top: 20px;">EMAIL</p>
                    <p><input type="email" name="email" placeholder="EMAIL ADDRESS" id="email" required /></p>
                    <p>PASSWORD</p>
                    <p><input type="password" name="password" placeholder="PASSWORD" id="password" required /></p>
                    <p><input type="checkbox" name="rememberme" id="rememberme" value="1" />&nbsp;&nbsp;REMEMBER ME</p>
                    <p><input type="submit" value="LOG IN" name="admin-login-submit" /></p>
                </form>
                <p style="margin-bottom: 0; color: #fff;"><a href="forgot-password.php" class="forget-pass">Password Reset</a></p>

            </div>
        </div>
    </div><!--Content End-->
    </body>
    </html>