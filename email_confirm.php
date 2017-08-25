<?php
require_once "recaptchalib.php";
$contactformstatus = '';
if(isset($_POST['contact-form-submit'])){

// your secret key
$secret = "6LeKeB0TAAAAANsK-3FgEMZ_pPe_J8opOYyRgU-0";

// empty response
$response = null;
$captcha_pass = 0;

// check secret key
$reCaptcha = new ReCaptcha($secret);

if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );

 if ($response != null && $response->success) {
$captcha_pass = 1;
}
else{
$captcha_pass = 2;
}
}

if($captcha_pass == 1){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = '';
    $phone = $_POST['phone'];
    $comments = $_POST['comments'];

    $msg = '';
    $msg .= "Name: ".$fname." ".$lname."<br>";
    $msg .= "Email: ".$email."<br>";
    $msg .= "Phone: ".$phone."<br>";
    $msg .= "Comments: ".$comments."<br>";

    $headers = "From: ".$fname." ".$lname."<".$email.">\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

mail("mobilewash.office@gmail.com", "MobileWash Contact Form", $msg, $headers);
$contactformstatus = 'success';
}
}
?>
<?php include_once('header.php'); ?>
<style>
@media screen and (max-width: 380px){
#rc-imageselect, .g-recaptcha {transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;}
</style>

    <div id="content">
        <div class="wrapper">
          
          <h2 align="center" style="padding-left: 20px; padding-right: 20px; font-size: 38px; font-weight: 400; margin: 0;">Your account has been activated</h2>
         

 
            
          </form>
        </div>
    </div>
    <?php 
//include_once('newsletter.php'); 
?>
   <?php include_once('footer.php'); ?>
<script>
$(function(){
$('form').on('submit', function() {
var has_error = false;

if(grecaptcha.getResponse() == "") {
alert('Please complete Spam Verification');
has_error = true;
}

if(has_error) return false;
});
});
</script>