<?php

/* --------- Client password reset handling ---------- */

if(isset($_POST['email-verify-client-submit'])){
$email = $_POST['email'];
$error = '';
$data = array("emailid"=>$email, 'customer_id' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://devmobilewash.com/api/index.php?r=customers/resendverifyemail");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

}

if(isset($_POST['email-verify-washer-submit'])){
$email = $_POST['email'];
$error = '';
$data = array("emailid"=>$email, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://devmobilewash.com/api/index.php?r=agents/resendverifyemail");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

}

if(isset($_GET['customer_id'])){
  $handle = curl_init("http://devmobilewash.com/api/index.php?r=customers/profiledetails");
$data = array('customerid' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
//var_dump($profiledetail);
$profiledetail_response = $profiledetail->response;
$profiledetail_result_code = $profiledetail->result;
//echo count($all_wash_requests);
}

if(isset($_GET['washer_id'])){
  $handle = curl_init("http://devmobilewash.com/api/index.php?r=agents/profiledetails");
$data = array('agent_id' => $_GET['washer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
//var_dump($profiledetail);
$profiledetail_response = $profiledetail->response;
$profiledetail_result_code = $profiledetail->result;
//echo count($all_wash_requests);
}

if(isset($_GET['washer_email_confirm']) && $_GET['washer_email_confirm'] == 'success'){
    $result_code = 'true';
    $response = 'Your account is verified successfully';
    
}

if(isset($_GET['washer_email_confirm']) && $_GET['washer_email_confirm'] == 'error'){
    $result_code = 'false';
    $response = 'Sorry, Your Email address does not match.';
    
}

?>
<!DOCTYPE HTML>

<html>

<head>
  <title>Email Verification - MobileWash.com</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://www.mobilewash.com/style.css" />
  <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="https://www.mobilewash.com/css/responsive.css" />
</head>

<body class="login client-login">
<!--Container-->
<div id="container" class="on-canvas">
    <!--Header-->
    <div id="header">
        <div class="wrapper">
            <div class="login-wrap">
                <p style="margin: 0;"><a href="index.php"><img src="https://www.mobilewash.com/images/logo-new-white.png" style="width: 100%; max-width: 400px;" alt="" /></a></p>
                <p class="bordered-text"><span>EMAIL VERIFICATION</span></p>
                 <?php if($result_code == "false"): ?>
             <p class="error" style="background: #DC0000; color: #fff; padding: 12px;">
             <?php if($response == "Pass the required parameters") echo "Please provide email address";
             else echo $response; ?>
             </p>
             <?php endif; ?>
<?php if($result_code == "true"): ?>
             <?php if($response == 'Please check your email for verification link'): ?>
             <div class="success" style="text-align: left; background: rgba(255, 255, 255, .8); padding: 10px 20px;"><h3>Email Sent</h3>
<p>An email with instructions on how to verify your account has been sent to <b>"<?php echo $_POST['email']; ?>"</b>. Please check your spam or junk folder if you do not see the email in your inbox.</p>
<p>If you no longer have access to this email account, please <a href="mailto:support@mobilewash.com">contact us</a>.</p>
</div>
<?php else: ?>
 <p class="success" style="background: green; color: #fff; padding: 12px;"><?php echo $response; ?></p>
 <?php endif; ?>
             <?php endif; ?>
                <?php if($result_code != "true"): ?>
                <form action="" id="client-login-form" class="forget-pass-form" method="post" style="margin-top: 40px;">
                    <p style="margin-top: 20px;">ENTER YOUR EMAIL ADDRESS</p>
                    <p><input type="email" name="email" placeholder="EMAIL ADDRESS" id="email" value="<?php echo $profiledetail->email; ?>" required /></p>


                     <p><input type="submit" value="SUBMIT" name="<?php if(isset($_GET['washer_id'])) {echo "email-verify-washer-submit";} else{echo "email-verify-client-submit";} ?>" /></p>

                </form>
                 <?php if(isset($_GET['customer_id'])): ?>
                <p style="margin-bottom: 0; color: #fff;"><a href="<?php if($_GET['action']) {echo 'client-login.php?action='.$_GET['action'];} else {echo 'client-login.php';} ?>" class="forget-pass">Login</a> | <a href="<?php if($_GET['action']) {echo 'register.php?action='.$_GET['action'];} else {echo 'register.php';} ?>" class="forget-pass">Create an Account</a></p>
                <?php endif; ?>
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
    width: 20%;
    }

    .bordered-text:after{
    content: '';
    background: #fff;
    height: 1px;
    position: absolute;
    top: 50%;
    right: 0;
    width: 20%;
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