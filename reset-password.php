<?php
require_once('api/protected/config/constant.php');
/* --------- Client password reset handling ---------- */
$pass_reset_success = '';
if(isset($_POST['forget-pass-cl-submit'])){
$email = $_POST['email'];
$error = '';
$data = array("emailid"=>$email, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/forgetpassword");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

}

if($_GET['action'] == 'clrp'){
$id = '';
$token = '';
$id = $_GET['id'];
$token = $_GET['token'];
}

if(isset($_POST['pass-reset-cl-submit'])){
$newpassword = $_POST['newpassword'];
$cnfpassword = $_POST['newpassword'];

$data = array("token"=> $token, "id"=> $id, "newpassword"=> $newpassword, "cnfpassword"=> $cnfpassword, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=customers/resetpassword");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$pass_reset_response = $jsondata->response;
$pass_reset_result_code = $jsondata->result;

if($pass_reset_result_code == 'true'){
    $pass_reset_success = 'true';
    //if($_GET['action'] == 'schedule') header("Location: https://www.mobilewash.com/client-login.php?action=".$_GET['action']);
    //else header("Location: https://www.mobilewash.com/client-login.php");
//die();
} 
}

/* --------- Client password reset handling end ---------- */

/* --------- Agent password reset handling ---------- */

if(isset($_POST['forget-pass-ag-submit'])){
$email = $_POST['email'];
$error = '';
$data = array("emailid"=>$email, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=agents/forgetpassword");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

}

if($_GET['action'] == 'agrp'){
$id = '';
$token = '';
$id = $_GET['id'];
$token = $_GET['token'];
}

if(isset($_POST['pass-reset-ag-submit'])){
$newpassword = $_POST['newpassword'];
$cnfpassword = $_POST['cnfpassword'];

$data = array("token"=> $token, "id"=> $id, "newpassword"=> $newpassword, "cnfpassword"=> $cnfpassword, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init(ROOT_URL."/api/index.php?r=agents/resetpassword");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$pass_reset_response = $jsondata->response;
$pass_reset_result_code = $jsondata->result;
}

if(isset($_GET['customer_id'])){
  $handle = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
$data = array('customerid' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
//var_dump($profiledetail);

//echo count($all_wash_requests);
}

if(isset($_GET['washer_id'])){
  $handle = curl_init(ROOT_URL."/api/index.php?r=agents/profiledetails");
$data = array('agent_id' => $_GET['washer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
//var_dump($profiledetail);

//echo count($all_wash_requests);
}



/* --------- Agent password reset handling end ---------- */
?>
<!DOCTYPE HTML>

<html>

<head>
  <title>Reset Password - MobileWash.com</title>
  <meta name="description" content="Forgot your password? Please click on this button to send a password reset email." />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta charset="UTF-8" />
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://www.mobilewash.com/style.css" />
  <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="https://www.mobilewash.com/css/responsive.css" />
  <link rel="alternate" href="https://www.mobilewash.com/reset-password.php" hreflang="en-us">
</head>

<body class="login client-login">
<!--Container-->
<div id="container" class="on-canvas">
    <!--Header-->
    <div id="header">
        <div class="wrapper">
            
            <div class="login-wrap">
                <p style="margin: 0;"><a href="index.php"><img src="https://www.mobilewash.com/images/logo-new-white.png" style="width: 100%; max-width: 400px;" alt="" /></a></p>
                <h1 class="bordered-text"><span>RESET PASSWORD</span></h1>
                 <?php if($result_code == "false"): ?>
             <p class="error" style="background: #DC0000; color: #fff; padding: 12px;">
             <?php if($response == "Pass the required parameters") echo "Please provide email address";
             else echo $response; ?>
             </p>
             <?php endif; ?>
<?php if($result_code == "true"): ?>
 <p class="success" style="background: green; color: #fff; padding: 12px;">
 <?php echo $response; ?>
             </p>
             <?php endif; ?>
                <form action="" id="client-login-form" class="forget-pass-form" method="post" style="margin-top: 40px;">
                    <p style="margin-top: 20px;">ENTER YOUR EMAIL ADDRESS</p>
                    <p><input type="email" name="email" placeholder="EMAIL ADDRESS" id="email" value="<?php echo $profiledetail->email; ?>" required /></p>
                   <?php if($_GET['action'] == 'ag' || ($_GET['washer_id'])): ?>
                    <p><input type="submit" value="SUBMIT" name="forget-pass-ag-submit" /></p>
                    <?php else: ?>
                     <p><input type="submit" value="SUBMIT" name="forget-pass-cl-submit" /></p>
                     <?php endif; ?>
                </form>
                <?php if(($_GET['action'] == 'clrp') || ($_GET['action'] == 'agrp')): ?>
                 <?php if($pass_reset_result_code == "false"): ?>
             <p class="error" style="background: #DC0000; color: #fff; padding: 12px;">
             <?php if($pass_reset_response == "Pass the required parameters") echo "All fields are required";
             else echo $pass_reset_response; ?>
             </p>
             <?php endif; ?>
<?php if($pass_reset_result_code == "true"): ?>
 <p class="success" style="background: green; color: #fff; padding: 12px;">
 <?php echo $pass_reset_response; ?>
             </p>
             <?php endif; ?>
                 <?php if($pass_reset_result_code != "true"): ?>
                 <form action="" id="client-login-form" class="pass-reset-form" method="post" style="margin-top: 40px;">
                    <p style="margin-top: 20px;">NEW PASSWORD</p>
                    <p><input type="password" name="newpassword" placeholder="NEW PASSWORD" id="newpassword" required /></p>
                    <?php if($_GET['action'] == 'agrp'): ?>
                    <p><input type="submit" value="RESET PASSWORD" name="pass-reset-ag-submit" /></p>
                    <?php else: ?>
                    <p><input type="submit" value="RESET PASSWORD" name="pass-reset-cl-submit" /></p>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
                <?php endif; ?>
                <?php if($_GET['action'] != 'ag' && $_GET['action'] != 'agrp' && (!$_GET['washer_id'])): ?>
               
<?php endif; ?>
            </div>
        </div>
    </div><!--Header End-->
    <style>
    .bordered-text{
    color: #fff;
    font-size: 20px;
    position: relative;
    }
    
    .bordered-text:before{
    content: '';
    background: #fff;
    height: 1px;
    position: absolute;
    top: 50%;
    left: 0;
    width: 25%;
    }
    
    .bordered-text:after{
    content: '';
    background: #fff;
    height: 1px;
    position: absolute;
    top: 50%;
    right: 0;
    width: 25%;
    }
    </style>
     <?php if(($_GET['action'] == 'clrp') || ($_GET['action'] == 'agrp')): ?>
    <style>
    .forget-pass-form{
    display: none;
    }
    </style>
    <?php endif; ?>

   <?php include_once('footer.php'); ?>