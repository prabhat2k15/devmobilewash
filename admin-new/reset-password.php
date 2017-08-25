<?php
if($_GET['action'] == 'adrp'){
$id = '';
$token = '';
$id = $_GET['id'];
$token = $_GET['token'];
}

if(isset($_POST['pass-reset-ad-submit'])){
$newpassword = $_POST['pass'];
$cnfpassword = $_POST['cpass'];
$data = array("token"=> $token, "id"=> $id, "newpassword"=> $newpassword, "cnfpassword"=> $cnfpassword, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=users/resetpassword");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
}
?>

<!DOCTYPE HTML>

<html>

<head>
  <title>Admin Password Reset - MobileWash.com</title>
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
<?php if($result_code == "true"): ?>
 <p class="success" style="background: green; color: #fff; padding: 12px;"><?php echo $response; ?>. Please <a style="color: #fff; text-decoration: underline;" href="login.php">login here</a>.</p>
<?php endif; ?>
             <?php if($result_code == "false"): ?>
             <p class="error" style="background: #DC0000; color: #fff; padding: 12px;"><?php echo $response; ?></p>
             <?php endif; ?>

                <form action="" method="post">
                    <p style="margin-top: 20px;">NEW PASSWORD</p>
                    <p><input type="password" name="pass" placeholder="NEW PASSWORD" id="pass" required /></p>
<p style="margin-top: 20px;">CONFIRM PASSWORD</p>
                    <p><input type="password" name="cpass" placeholder="CONFIRM PASSWORD" id="cpass" required /></p>
                    
                    <p><input type="submit" value="UPDATE PASSWORD" name="pass-reset-ad-submit" /></p>
                </form>

              
            </div>
        </div>
    </div><!--Content End-->
    </body>
    </html>