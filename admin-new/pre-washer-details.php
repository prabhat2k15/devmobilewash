<?php include_once('header.php'); ?>
<style>
.clear{
clear: both;
}

.page-header.navbar .menu-toggler.sidebar-toggler{
display: none;
}

#main-col{
margin-top: 50px !important;
}
</style>
<?php if(!empty($_GET['id'])){

 $url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewasherdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

}

$data = array("id"=> 16, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$contracttext = json_decode($result);
$contracttext = $contracttext->content;



$data = array("id"=> 15, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$contracttext_sec = json_decode($result);
$contracttext_sec = $contracttext_sec->content;


$data = array("id"=> 13, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$contracttext_rating = json_decode($result);
$contracttext_rating = $contracttext_rating->content;

$data = array("id"=> 11, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$contracttext_privacy = json_decode($result);
$contracttext_privacy = $contracttext_privacy->content;

$data = array("id"=> 10, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$contracttext_terms = json_decode($result);
$contracttext_terms = $contracttext_terms->content;

$contracttext = str_replace('[CURRENT_DAY]',date('d'), $contracttext);
$contracttext = str_replace('[CURRENT_MONTH]',date('F'), $contracttext);
$contracttext = str_replace('[CURRENT_YEAR]',date('y'), $contracttext);
$contracttext = str_replace('[WASHER_NAME]',$jsondata->washer_details->first_name." ".$jsondata->washer_details->last_name, $contracttext);
$contracttext = str_replace('[WASHER_ADDRESS]',$jsondata->washer_details->street_address.", ".$jsondata->washer_details->city.", ".$jsondata->washer_details->state." ".$jsondata->washer_details->zipcode, $contracttext);

$contracttext = str_replace('[SIGN_IMAGE]',"<img src='/home/mobilewa/public_html/api/images/agent_img/agent_docs/".$jsondata->washer_details->pro_service_agree_sign."' />", $contracttext);

$contracttext = str_replace('[CURRENT_DATE]',date("Y-m-d"), $contracttext);


$contracttext_sec = str_replace('[WASHER_NAME]',$jsondata->washer_details->first_name." ".$jsondata->washer_details->last_name, $contracttext_sec);
$contracttext_sec = str_replace('[WASHER_ADDRESS]',$jsondata->washer_details->street_address.", ".$jsondata->washer_details->city.", ".$jsondata->washer_details->state." ".$jsondata->washer_details->zipcode, $contracttext_sec);

$contracttext_sec = str_replace('[SIGN_IMAGE]',"<img src='/home/mobilewa/public_html/api/images/agent_img/agent_docs/".$jsondata->washer_details->pro_service_agree_sign."' />", $contracttext_sec);

$contracttext_sec = str_replace('[CURRENT_DATE]',date("Y-m-d"), $contracttext_sec);

$contracttext_rating = str_replace('[WASHER_NAME]',$jsondata->washer_details->first_name." ".$jsondata->washer_details->last_name, $contracttext_rating);
$contracttext_rating = str_replace('[WASHER_ADDRESS]',$jsondata->washer_details->street_address.", ".$jsondata->washer_details->city.", ".$jsondata->washer_details->state." ".$jsondata->washer_details->zipcode, $contracttext_rating);

$contracttext_rating = str_replace('[SIGN_IMAGE]',"<img src='/home/mobilewa/public_html/api/images/agent_img/agent_docs/".$jsondata->washer_details->pro_service_agree_sign."' />", $contracttext_rating);

$contracttext_rating = str_replace('[CURRENT_DATE]',date("Y-m-d"), $contracttext_rating);

$contracttext_privacy = str_replace('[SIGN_IMAGE]',"<img src='/home/mobilewa/public_html/api/images/agent_img/agent_docs/".$jsondata->washer_details->pro_service_agree_sign."' />", $contracttext_privacy);

$contracttext_terms = str_replace('[SIGN_IMAGE]',"<img src='/home/mobilewa/public_html/api/images/agent_img/agent_docs/".$jsondata->washer_details->pro_service_agree_sign."' />", $contracttext_terms);



// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;


// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($contracttext);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$filename = 'pdfs/'.$_GET['id'].'-pro-service-agreement.pdf';
  $output = $dompdf->output();
    file_put_contents($filename, $output);


$dompdf = new Dompdf();
$dompdf->loadHtml($contracttext_sec);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$filename = 'pdfs/'.$_GET['id'].'-payment-card-security-notice.pdf';
  $output = $dompdf->output();
    file_put_contents($filename, $output);


$dompdf = new Dompdf();
$dompdf->loadHtml($contracttext_rating);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$filename = 'pdfs/'.$_GET['id'].'-notice-of-rating-system.pdf';
  $output = $dompdf->output();
    file_put_contents($filename, $output);



$dompdf = new Dompdf();
$dompdf->loadHtml($contracttext_privacy);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$filename = 'pdfs/'.$_GET['id'].'-service-provider-privacy-policy.pdf';
  $output = $dompdf->output();
    file_put_contents($filename, $output);


$dompdf = new Dompdf();
$dompdf->loadHtml($contracttext_terms);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$filename = 'pdfs/'.$_GET['id'].'-terms-of-use.pdf';
  $output = $dompdf->output();
    file_put_contents($filename, $output);


$piname = $jsondata->washer_details->liable_insurance;
$vehicle_front_name = $jsondata->washer_details->vehicle_front_img;
$vehicle_back_name = $jsondata->washer_details->vehicle_back_img;
$vehicle_left_name = $jsondata->washer_details->vehicle_left_img;
$vehicle_right_name = $jsondata->washer_details->vehicle_right_img;
$equipment_name = $jsondata->washer_details->equipment_img;
$driver_license_name = $jsondata->washer_details->driver_license;
$vehicle_register_name = $jsondata->washer_details->vehicle_register;
$vehicle_insurance_name = $jsondata->washer_details->vehicle_insurance;
$cl_insurance_name = $jsondata->washer_details->cl_insurance;
$w9_name = $jsondata->washer_details->w9;

if(isset($_POST['pre-washer-info-submit'])){

if($_FILES['proof_insurance']['tmp_name']){
$proof_insurance = $_FILES['proof_insurance']['tmp_name'];
$proof_insurance_type = pathinfo($_FILES['proof_insurance']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$piname = 'agent_liability_insurance_'.$_GET['id']."_".$md5.".".$proof_insurance_type;
move_uploaded_file($proof_insurance, '../api/images/agent_img/agent_docs/'.$piname);
}

if($_FILES['ssn_image']['tmp_name']){
$ssn_image = $_FILES['ssn_image']['tmp_name'];
$ssn_image_type = pathinfo($_FILES['ssn_image']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$ssnname = 'ssn_'.$_GET['id']."_".$md5.".".$ssn_image_type;
move_uploaded_file($ssn_image, '../api/images/agent_img/agent_docs/'.$ssnname);
}

if($_FILES['vehicle_front']['tmp_name']){
$vehicle_front = $_FILES['vehicle_front']['tmp_name'];
$vehicle_front_type = pathinfo($_FILES['vehicle_front']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$vehicle_front_name = 'vehicle_front_img_'.$_GET['id']."_".$md5.".".$vehicle_front_type;
move_uploaded_file($vehicle_front, '../api/images/agent_img/agent_docs/'.$vehicle_front_name);
}


if($_FILES['vehicle_back']['tmp_name']){
$vehicle_back = $_FILES['vehicle_back']['tmp_name'];
$vehicle_back_type = pathinfo($_FILES['vehicle_back']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$vehicle_back_name = 'vehicle_back_img_'.$_GET['id']."_".$md5.".".$vehicle_back_type;
move_uploaded_file($vehicle_back, '../api/images/agent_img/agent_docs/'.$vehicle_back_name);
}

if($_FILES['vehicle_left']['tmp_name']){
$vehicle_left = $_FILES['vehicle_left']['tmp_name'];
$vehicle_left_type = pathinfo($_FILES['vehicle_left']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$vehicle_left_name = 'vehicle_left_img_'.$_GET['id']."_".$md5.".".$vehicle_left_type;
move_uploaded_file($vehicle_left, '../api/images/agent_img/agent_docs/'.$vehicle_left_name);
}

if($_FILES['vehicle_right']['tmp_name']){
$vehicle_right = $_FILES['vehicle_right']['tmp_name'];
$vehicle_right_type = pathinfo($_FILES['vehicle_right']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$vehicle_right_name = 'vehicle_right_img_'.$_GET['id']."_".$md5.".".$vehicle_right_type;
move_uploaded_file($vehicle_right, '../api/images/agent_img/agent_docs/'.$vehicle_right_name);
}

if($_FILES['equipment']['tmp_name']){
$equipment = $_FILES['equipment']['tmp_name'];
$equipment_type = pathinfo($_FILES['equipment']['name'], PATHINFO_EXTENSION);
$md5 = md5(uniqid(rand(), true));
$equipment_name = 'equipment_img_'.$_GET['id']."_".$md5.".".$equipment_type;
move_uploaded_file($equipment, '../api/images/agent_img/agent_docs/'.$equipment_name);
}

if($_FILES['drivers_license']['tmp_name']){
$driver_license = $_FILES['drivers_license']['tmp_name'];
$driver_license_type = pathinfo($_FILES['drivers_license']['name'], PATHINFO_EXTENSION);

$md5 = md5(uniqid(rand(), true));
$driver_license_name = 'driver_license_'.$_GET['id']."_".$md5.".".$driver_license_type;
move_uploaded_file($driver_license, '../api/images/agent_img/agent_docs/'.$driver_license_name);
}

if($_FILES['vehicle_register']['tmp_name']){
$vehicle_register = $_FILES['vehicle_register']['tmp_name'];
$vehicle_register_type = pathinfo($_FILES['vehicle_register']['name'], PATHINFO_EXTENSION);

$md5 = md5(uniqid(rand(), true));
$vehicle_register_name = 'vehicle_register_'.$_GET['id']."_".$md5.".".$vehicle_register_type;
move_uploaded_file($vehicle_register, '../api/images/agent_img/agent_docs/'.$vehicle_register_name);
}

if($_FILES['vehicle_insurance']['tmp_name']){
$vehicle_insurance = $_FILES['vehicle_insurance']['tmp_name'];
$vehicle_insurance_type = pathinfo($_FILES['vehicle_insurance']['name'], PATHINFO_EXTENSION);

$md5 = md5(uniqid(rand(), true));
$vehicle_insurance_name = 'vehicle_insurance_'.$_GET['id']."_".$md5.".".$vehicle_insurance_type;
move_uploaded_file($vehicle_insurance, '../api/images/agent_img/agent_docs/'.$vehicle_insurance_name);
}

if($_FILES['cl_insurance']['tmp_name']){
$cl_insurance = $_FILES['cl_insurance']['tmp_name'];
$cl_insurance_type = pathinfo($_FILES['cl_insurance']['name'], PATHINFO_EXTENSION);

$md5 = md5(uniqid(rand(), true));
$cl_insurance_name = 'cl_insurance_'.$_GET['id']."_".$md5.".".$cl_insurance_type;
move_uploaded_file($cl_insurance, '../api/images/agent_img/agent_docs/'.$cl_insurance_name);
}

if($_FILES['w9']['tmp_name']){
$w9 = $_FILES['w9']['tmp_name'];
$w9_type = pathinfo($_FILES['w9']['name'], PATHINFO_EXTENSION);

$md5 = md5(uniqid(rand(), true));
$w9_name = 'w9_'.$_GET['id']."_".$md5.".".$w9_type;
move_uploaded_file($w9, '../api/images/agent_img/agent_docs/'.$w9_name);
}

$data = array("id"=> $_GET['id'], "first_name" => $_POST['fname'], "last_name" => $_POST['lname'], "email" => $_POST['email'], "phone" => $_POST['phoneno'], "city" => $_POST['city'], "state" => $_POST['state'], "zipcode" => $_POST['zipcode'], "hear_mw_how" => $_POST['hear_mw_how'], "date_of_birth" => $_POST['dob'], "street_address" => $_POST['staddr'], "suite_apt" => $_POST['suiteno'], "legally_eligible" => $_POST['legally_eligible'], "own_vehicle" => $_POST['own_vehicle'], "waterless_wash_product" => $_POST['waterless_wash_product'], "operate_area" => $_POST['operate_area'], "work_schedule" => $_POST['work_schedule'], "operating_as" => $_POST['operationmethod'], "company_name" => $_POST['companyname'], "wash_experience" => $_POST['wash_exp'], "liable_insurance" => $piname, "ssn_image" => $ssnname,"vehicle_front_img" => $vehicle_front_name, "vehicle_back_img" => $vehicle_back_name, "vehicle_left_img" => $vehicle_left_name, "vehicle_right_img" => $vehicle_right_name, "equipment_img" => $equipment_name, "driver_license" => $driver_license_name, "vehicle_insurance" => $vehicle_insurance_name, "vehicle_register" => $vehicle_register_name, "cl_insurance" => $cl_insurance_name, "w9" => $w9_name, "insurance_expire_date"=> $_POST['insure_exp_date'], "ssn_expire_date"=> $_POST['ssn_exp_date'], "bank_name" => $_POST['bank_name'], "bank_account_name" => $_POST['bank_account_name'], "routing_number" => $_POST['routing_no'], "bank_account_number" => $_POST['bank_account_no'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=agents/prewasherupdate");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
//print_r($result);
$updatedata = json_decode($result);

 $url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewasherdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);



}

?>

<link href="https://fonts.googleapis.com/css?family=Lato:400,700,300" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<style>

body{

font-family: 'Lato', sans-serif;
}

.form-wrap {
    width: 512px;
    margin: 0 auto;
    margin-bottom: 50px;
    float: left;
    margin-right: 50px;
}

.form-wrap .section-no {
    display: block;
    width: 50px;
    height: 50px;
    background: #016fd0;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    line-height: 48px;
    font-size: 26px;
    font-weight: 400;
    float: left;
    margin-right: 25px;
    margin-top: 22px;
}

.form-wrap h2 {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 0;
}

.form-wrap .form-content {
    margin-top: 30px;
    border-left: 1px solid #ccc;
    padding-left: 45px;
    margin-left: 25px;
    padding-top: 10px;
}

.form-wrap .form-content .fname-col {
    float: left;
}

.form-wrap .form-content .lname-col {
    float: right;
}

.form-wrap .form-content p {
    font-size: 14px;
    font-weight: 400;
}

.form-wrap input[type="text"], .form-wrap input[type="email"], .form-wrap input[type="tel"], .form-wrap input[type="password"], .form-wrap select {
    display: block;
    padding: 13px;
    border-radius: 5px;
    border: 1px solid #b2b9bf;
    width: 440px;
    margin-bottom: 10px;
    font-size: 14px;
}

.form-wrap .form-content .state-col {
    float: left;
}

.form-wrap .form-content #state {
    width: 118px;
    margin-bottom: 0;
}

.form-wrap .form-content .zip-col {
    float: left;
    margin-left: 15px;
}

.form-wrap .form-content #zipcode {
    width: 188px;
    margin-bottom: 0;
}

.form-wrap .form-content #fname {
    width: 213px;
}

.form-wrap .form-content #lname {
    width: 213px;
}

form input[type="submit"]{
display: block;
    padding: 13px;
    border-radius: 5px;
    width: 440px;
text-transform: uppercase;
background: #016fd0;
color: #fff;
border: 0;
font-family: lato;
margin-left: auto;
margin-right: auto;
width: 300px;
box-sizing: border-box;
font-size: 16px;
cursor: pointer;
margin-bottom: 25px;
}

.form-wrap .form-content .image-upload-btn {
    color: #fff;
    display: block;
    background: #309ffe url(../images/camera-icon.png) no-repeat 116px 9px;
    border-radius: 5px;
    padding: 12px;
    text-align: center;
    text-decoration: none;
}

.file_img_preview {
    width: auto;
    max-width: 100%;
    display: none;
}

.meter {
	height: 10px;  /* Can be anything */
	position: relative;
	background: #CACACA;
	-moz-border-radius: 25px;
	-webkit-border-radius: 25px;
	border-radius: 25px;

	box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
}

.meter > span {
  display: block;
  height: 100%;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  background-color: rgb(43,194,83);
  background-image: linear-gradient(
    center bottom,
    rgb(43,194,83) 37%,
    rgb(84,240,84) 69%
  );
  box-shadow:
    inset 0 2px 9px  rgba(255,255,255,0.3),
    inset 0 -2px 6px rgba(0,0,0,0.4);
  position: relative;
  overflow: hidden;
}

.meter.red > span{
 background-color: #f0a3a3;
  background-image: linear-gradient(to bottom, #f0a3a3, #f42323);
border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
}
</style>


<body class="admin-panel admin-dashboard" style="background: #e8e9e9;">
	<div id="container">
		<div id="main-col" style="width: 1280px; margin: 0 auto; padding: 0;">

        <p style="text-align: center;"><img style="width: 500px; margin-top: 20px;" src="../images/logo-new.png" alt="Mobile Wash" /></p>
         <?php if($jsondata->result == 'true'): ?>
<?php if($updatedata->result == 'true'): ?>
<p style="color: #fff; text-align: center; background: green; padding: 10px;">Update Successful</p>
<?php endif; ?>
        <form style="width: 1074px; margin: 0 auto; display: block;" method="post" enctype="multipart/form-data">
            <div class="form-wrap">
<?php
$basic_info_progress = 0;
$basic_point_base = 10;
if(trim($jsondata->washer_details->first_name)) $basic_info_progress += $basic_point_base;

if(trim($jsondata->washer_details->last_name)) $basic_info_progress += $basic_point_base;

if(trim($jsondata->washer_details->email)) $basic_info_progress += $basic_point_base;

if(trim($jsondata->washer_details->date_of_birth) && strtotime($jsondata->washer_details->date_of_birth) > 0) $basic_info_progress += $basic_point_base;

if(trim($jsondata->washer_details->phone) && trim($jsondata->washer_details->phone_verified)) $basic_info_progress += $basic_point_base;
if(trim($jsondata->washer_details->street_address)) $basic_info_progress += $basic_point_base;
if(trim($jsondata->washer_details->city)) $basic_info_progress += $basic_point_base;
if(trim($jsondata->washer_details->state)) $basic_info_progress += $basic_point_base;
if(trim($jsondata->washer_details->zipcode)) $basic_info_progress += $basic_point_base;
if(trim($jsondata->washer_details->hear_mw_how)) $basic_info_progress += $basic_point_base;
?>
<?php if($basic_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $basic_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">1</span>
                <h2>Washer Profile Information</h2>
                <p style="font-size: 20px; margin-top: 10px; margin-left: 76px;" class="wash-subtitle">Basic name and contact information</p>
                    <div class="form-content">
                    <div class="fname-col">
                        <p style="margin-top: 0;">FIRST NAME</p>
                        <input type="text" name="fname" id="fname" value="<?php echo $jsondata->washer_details->first_name; ?>" style="margin-bottom: 0;">
                    </div>
                    <div class="lname-col">
                        <p style="margin-top: 0;">LAST NAME</p>
                        <input type="text" name="lname" id="lname" value="<?php echo $jsondata->washer_details->last_name; ?>" style="margin-bottom: 0;">
                    </div>
                    <div class="clear"></div>
                    <p>EMAIL ADDRESS</p>
                    <input type="email" name="email" id="email" value="<?php echo $jsondata->washer_details->email; ?>">
                    <p>DATE OF BIRTH</p>
                    <input type="text" name="dob" id="dob" value="<?php echo $jsondata->washer_details->date_of_birth; ?>">
                    <p>PHONE NUMBER <?php if(!$jsondata->washer_details->phone_verified) echo "<span style='color: red;'>(Not verified)</span>"; ?></p>
                    <input type="tel" name="phoneno" id="phoneno" value="<?php echo $jsondata->washer_details->phone; ?>">
                    <p>STREET ADDRESS</p>
                    <input type="text" name="staddr" id="staddr" value="<?php echo $jsondata->washer_details->street_address; ?>">
                    <p>SUITE/APT # (OPTIONAL)</p>
                    <input type="text" name="suiteno" id="suiteno" value="<?php echo $jsondata->washer_details->suite_apt; ?>">
                    <p>CITY</p>
                    <input type="text" name="city" id="city" style="margin-bottom: 0;" value="<?php echo $jsondata->washer_details->city; ?>">
                    <div class="state-col">
                        <p>STATE</p>
                        <input type="text" name="state" id="state" value="<?php echo $jsondata->washer_details->state; ?>">
                    </div>
                    <div class="zip-col">
                        <p>ZIP CODE</p>
                        <input type="text" name="zipcode" id="zipcode" value="<?php echo $jsondata->washer_details->zipcode; ?>">
                    </div>

                    <div class="clear"></div>
 <p>How did you hear about us?</p>
                    <input type="text" name="hear_mw_how" id="hear_mw_how" style="margin-bottom: 0;" value="<?php echo $jsondata->washer_details->hear_mw_how; ?>">
                   </div>
            </div>

            <div class="form-wrap" style="margin-right: 0;">
<?php
$member_info_progress = 0;
$member_point_base = 14.29;
if(trim($jsondata->washer_details->legally_eligible)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->own_vehicle)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->waterless_wash_product)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->operate_area)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->work_schedule)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->operating_as)) $member_info_progress += $member_point_base;
if(trim($jsondata->washer_details->wash_experience)) $member_info_progress += $member_point_base;

?>
<?php if($member_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $member_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">2</span>
                <h2>Membership Eligibility</h2>
                <p class="wash-subtitle" style="font-size: 20px; margin-top: 10px; margin-left: 76px;">Please complete the following questionnaire</p>
                <div class="form-content">

 <p style="margin-top: 0;">DO YOU HAVE A GENERAL LIABILITY INSURANCE POLICY?</p>
                    <input type="text" name="legally_eligible" id="legally_eligible" value="<?php echo $jsondata->washer_details->legally_eligible; ?>">
                    <p>DO YOU OWN A VEHICLE AND MOBILE AUTO DETAIL EQUIPMENT?</p>
                      <input type="text" name="own_vehicle" id="own_vehicle" value="<?php echo $jsondata->washer_details->own_vehicle; ?>">
                     <p>DO YOU HAVE WATERLESS CAR WASH PRODUCTS?</p>
                   <input type="text" name="waterless_wash_product" id="waterless_wash_product" value="<?php echo $jsondata->washer_details->waterless_wash_product; ?>">

                    <p>WHAT AREAS WOULD YOU PREFER TO OPERATE IN?</p>
                    <input type="text" name="operate_area" id="operate_area" value="<?php echo $jsondata->washer_details->operate_area; ?>">
                    <p>HOW MANY WASHES CAN YOU DO ON AVERAGE DAILY?</p>
                   <input type="text" name="work_schedule" id="work_schedule" value="<?php echo $jsondata->washer_details->work_schedule; ?>">
                    <p>ARE YOU OPERATING AS A BUSINESS OR INDIVIDUAL?</p>
                    <select name="operationmethod" id="operationmethod">
<option value="Business" <?php if($jsondata->washer_details->operating_as == 'Business') echo "selected"; ?>>Business</option>
<option value="Individual" <?php if($jsondata->washer_details->operating_as == 'Individual') echo "selected"; ?>>Individual</option>
</select>
                    <p>BUSINESS OR DBA NAME</p>
                      <input type="text" name="companyname" id="companyname" value="<?php echo $jsondata->washer_details->company_name; ?>">
                    <p>HOW MANY YEARS OF CAR WASH EXPERIENCE?</p>
                     <input type="text" name="wash_exp" id="wash_exp" value="<?php echo $jsondata->washer_details->wash_experience; ?>">

                </div>

            </div>
            <div class="clear"></div>

<div class="form-wrap">

<?php
$insurance_info_progress = 0;
$insurance_point_base = 25;
if(trim($jsondata->washer_details->liable_insurance)) $insurance_info_progress += $insurance_point_base;
if(trim($jsondata->washer_details->insurance_expire_date) && strtotime($jsondata->washer_details->insurance_expire_date) >0) $insurance_info_progress += $insurance_point_base;
if(trim($jsondata->washer_details->ssn_image)) $insurance_info_progress += $insurance_point_base;
if(trim($jsondata->washer_details->ssn_expire_date) && strtotime($jsondata->washer_details->ssn_expire_date) >0) $insurance_info_progress += $insurance_point_base;
?>
<?php if($insurance_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $insurance_info_progress; ?>%"></span>
</div>
<?php endif; ?>

            <span class="section-no">3</span>
                <h2>Insurance & SSN</h2>
                <p style="font-size: 20px; margin-top: 10px; margin-left: 76px;" class="wash-subtitle">Please upload the following documents</p>
                    <div class="form-content">

                    <p style="margin-top: 0;">PERSONAL LIABILITY / GARAGE KEEPER'S INSURANCE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#proof_insurance'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->liable_insurance): ?>
<input type="file" id="proof_insurance" name="proof_insurance" onchange="readURL(this, 'proof_insurance_preview')" />
<?php else: ?>
<input type="file" id="proof_insurance" name="proof_insurance" onchange="readURL(this, 'proof_insurance_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->liable_insurance): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->liable_insurance; ?>" target="_blank"><img id="proof_insurance_preview" class="file_img_preview" style="display: block; max-width: 300px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->liable_insurance; ?>" /></a>
<?php else: ?>
<img id="proof_insurance_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                    <p>INSURANCE EXPIRATION DATE</p>
<?php if(strtotime($jsondata->washer_details->insurance_expire_date) < 0): ?>
                    <input type="text" name="insure_exp_date" id="insure_exp_date">
<?php else: ?>
 <input type="text" name="insure_exp_date" value="<?php echo $jsondata->washer_details->insurance_expire_date; ?>" id="insure_exp_date">
<?php endif; ?>

<p>UPLOAD A VALID COPY OF SSN OR ITIN</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#ssn_image'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->ssn_image): ?>
<input type="file" id="ssn_image" name="ssn_image" onchange="readURL(this, 'ssn_image_preview')" />
<?php else: ?>
<input type="file" id="ssn_image" name="ssn_image" onchange="readURL(this, 'ssn_image_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->ssn_image): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->ssn_image; ?>" target="_blank"><img id="ssn_image_preview" class="file_img_preview" style="display: block; max-width: 300px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->ssn_image; ?>" /></a>
<?php else: ?>
<img id="ssn_image_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                    <p>SSN ISSUE DATE</p>
<?php if(strtotime($jsondata->washer_details->ssn_expire_date) < 0): ?>
                    <input type="text" name="ssn_exp_date" id="ssn_exp_date">
<?php else: ?>
 <input type="text" name="ssn_exp_date" value="<?php echo $jsondata->washer_details->ssn_expire_date; ?>" id="ssn_exp_date">
<?php endif; ?>

                   </div>
            </div>

<div class="form-wrap" style="margin-right: 0;">
<?php
$bank_info_progress = 0;
$bank_point_base = 25;
if(trim($jsondata->washer_details->bank_name)) $bank_info_progress += $bank_point_base;
if(trim($jsondata->washer_details->bank_account_name)) $bank_info_progress += $bank_point_base;
if(trim($jsondata->washer_details->routing_number)) $bank_info_progress += $bank_point_base;
if(trim($jsondata->washer_details->bank_account_number)) $bank_info_progress += $bank_point_base;
?>
<?php if($bank_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $bank_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">4</span>
                <h2>Bank Deposit Information</h2>
                <p class="wash-subtitle" style="font-size: 20px; margin-top: 10px; margin-left: 76px;">Please enter your direct deposit information</p>
                <div class="form-content">
<p style="margin-top: 0;">BANK NAME</p>
                    <input type="text" name="bank_name" value="<?php echo $jsondata->washer_details->bank_name; ?>" id="bank_name">

<p>BANK ACCOUNT NAME</p>
                    <input type="text" name="bank_account_name" value="<?php echo $jsondata->washer_details->bank_account_name; ?>" id="bank_account_name">


 <p>ROUTING NUMBER</p>
<div class="number-field-holder">
                    <input type="text" name="routing_no" value="<?php echo $jsondata->washer_details->routing_number; ?>" id="routing_no">
<div class="showhide" title="Show Number"></div>
</div>
                    <p>BANK ACCOUNT NUMBER</p>
<div class="number-field-holder">
                    <input type="text" name="bank_account_no" value="<?php echo $jsondata->washer_details->bank_account_number; ?>" id="bank_account_no">
<div class="showhide" title="Show Number"></div>
  </div>

                </div>

            </div>
 <div class="clear"></div>

 <div class="form-wrap">
<?php
$vehicle_info_progress = 0;
$vehicle_point_base = 20;
if(trim($jsondata->washer_details->vehicle_front_img)) $vehicle_info_progress += $vehicle_point_base;
if(trim($jsondata->washer_details->vehicle_left_img)) $vehicle_info_progress += $vehicle_point_base;
if(trim($jsondata->washer_details->equipment_img)) $vehicle_info_progress += $vehicle_point_base;
if(trim($jsondata->washer_details->vehicle_back_img)) $vehicle_info_progress += $vehicle_point_base;
if(trim($jsondata->washer_details->vehicle_right_img)) $vehicle_info_progress += $vehicle_point_base;
?>
<?php if($vehicle_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $vehicle_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">5</span>
                <h2>Vehicle & Equipment Documents</h2>
                <p style="font-size: 20px; margin-top: 10px; margin-left: 76px;" class="wash-subtitle">Please upload the following documents</p>
                    <div class="form-content">

                    <p style="margin-top: 0;">VEHICLE FRONT SIDE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_front'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_front_img): ?>
<input type="file" id="vehicle_front" name="vehicle_front" onchange="readURL(this, 'vehicle_front_preview')" />
<?php else: ?>
<input type="file" id="vehicle_front" name="vehicle_front" onchange="readURL(this, 'vehicle_front_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_front_img): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_front_img; ?>" target="_blank"><img id="vehicle_front_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_front_img; ?>" /></a>
<?php else: ?>
<img id="vehicle_front_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

   <p>VEHICLE LEFT SIDE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_left'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_left_img): ?>
<input type="file" id="vehicle_left" name="vehicle_left" onchange="readURL(this, 'vehicle_left_preview')" />
<?php else: ?>
<input type="file" id="vehicle_left" name="vehicle_left" onchange="readURL(this, 'vehicle_left_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_left_img): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_left_img; ?>" target="_blank"><img id="vehicle_left_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_left_img; ?>" /></a>
<?php else: ?>
<img id="vehicle_left_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

 <p>EQUIPMENT</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#equipment'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->equipment_img): ?>
<input type="file" id="equipment" name="equipment" onchange="readURL(this, 'equipment_preview')" />
<?php else: ?>
<input type="file" id="equipment" name="equipment" onchange="readURL(this, 'equipment_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->equipment_img): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->equipment_img; ?>" target="_blank"><img id="equipment_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->equipment_img; ?>" /></a>
<?php else: ?>
<img id="equipment_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                   </div>
            </div>

<div class="form-wrap" style="margin-right: 0;">

             <div class="form-content" style="margin-top: 122px;">
   <p style="margin-top: 0;">VEHICLE BACK SIDE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_back'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_back_img): ?>
<input type="file" id="vehicle_back" name="vehicle_back" onchange="readURL(this, 'vehicle_back_preview')" />
<?php else: ?>
<input type="file" id="vehicle_back" name="vehicle_back" onchange="readURL(this, 'vehicle_back_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_back_img): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_back_img; ?>" target="_blank"><img id="vehicle_back_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_back_img; ?>" /></a>
<?php else: ?>
<img id="vehicle_back_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

 <p>VEHICLE RIGHT SIDE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_right'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_right_img): ?>
<input type="file" id="vehicle_right" name="vehicle_right" onchange="readURL(this, 'vehicle_right_preview')" />
<?php else: ?>
<input type="file" id="vehicle_right" name="vehicle_right" onchange="readURL(this, 'vehicle_right_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_right_img): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_right_img; ?>" target="_blank"><img id="vehicle_right_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_right_img; ?>" /></a>
<?php else: ?>
<img id="vehicle_right_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                </div>

            </div>
 <div class="clear"></div>


 <div class="form-wrap">
<?php
$license_info_progress = 0;
$license_point_base = 20;
if(trim($jsondata->washer_details->driver_license)) $license_info_progress += $license_point_base;
if(trim($jsondata->washer_details->vehicle_insurance)) $license_info_progress += $license_point_base;
if(trim($jsondata->washer_details->w9)) $license_info_progress += $license_point_base;
if(trim($jsondata->washer_details->vehicle_register)) $license_info_progress += $license_point_base;
if(trim($jsondata->washer_details->cl_insurance)) $license_info_progress += $license_point_base;
?>
<?php if($license_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $license_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">6</span>
                <h2>License & Insurance Documents</h2>
                <p style="font-size: 20px; margin-top: 10px; margin-left: 76px;" class="wash-subtitle">Please upload the following documents</p>
                    <div class="form-content">

                    <p style="margin-top: 0;">DRIVER LICENSE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#drivers_license'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->driver_license): ?>
<input type="file" id="drivers_license" name="drivers_license" onchange="readURL(this, 'drivers_license_preview')" />
<?php else: ?>
<input type="file" id="drivers_license" name="drivers_license" onchange="readURL(this, 'drivers_license_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->driver_license): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->driver_license; ?>" target="_blank"><img id="drivers_license_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->driver_license; ?>" /></a>
<?php else: ?>
<img id="drivers_license_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

 <p>VEHICLE INSURANCE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_insurance'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_insurance): ?>
<input type="file" id="vehicle_insurance" name="vehicle_insurance" onchange="readURL(this, 'vehicle_insurance_preview')" />
<?php else: ?>
<input type="file" id="vehicle_insurance" name="vehicle_insurance" onchange="readURL(this, 'vehicle_insurance_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_insurance): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_insurance; ?>" target="_blank"><img id="vehicle_insurance_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_insurance; ?>" /></a>
<?php else: ?>
<img id="vehicle_insurance_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

 <p>W-9</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#w9'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->w9): ?>
<input type="file" id="w9" name="w9" onchange="readURL(this, 'w9_preview')" />
<?php else: ?>
<input type="file" id="w9" name="w9" onchange="readURL(this, 'w9_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->w9): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->w9; ?>" target="_blank"><img id="w9_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/register/washer/steps/images/w9-done.jpg" /></a>
<?php else: ?>
<img id="w9_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                   </div>
            </div>

<div class="form-wrap" style="margin-right: 0;">
             <div class="form-content" style="margin-top: 122px;">
              <p style="margin-top: 0;">REGISTRATION</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#vehicle_register'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->vehicle_register): ?>
<input type="file" id="vehicle_register" name="vehicle_register" onchange="readURL(this, 'vehicle_register_preview')" />
<?php else: ?>
<input type="file" id="vehicle_register" name="vehicle_register" onchange="readURL(this, 'vehicle_register_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->vehicle_register): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_register; ?>" target="_blank"><img id="vehicle_register_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_register; ?>" /></a>
<?php else: ?>
<img id="vehicle_register_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

 <p>COMMERCIAL LIABILITY INSURANCE</p>
                    <a href="#" class="image-upload-btn" onclick="chooseFile('#cl_insurance'); return false;">UPLOAD IMAGE</a>
                    <div style="height:0px;overflow:hidden">
<?php if($jsondata->washer_details->cl_insurance): ?>
<input type="file" id="cl_insurance" name="cl_insurance" onchange="readURL(this, 'cl_insurance_preview')" />
<?php else: ?>
<input type="file" id="cl_insurance" name="cl_insurance" onchange="readURL(this, 'cl_insurance_preview')" />
<?php endif; ?>
</div>
<?php if($jsondata->washer_details->cl_insurance): ?>
<a href="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->cl_insurance; ?>" target="_blank"><img id="cl_insurance_preview" class="file_img_preview" style="display: block; max-width: 300px; height: 200px;" src="http://www.devmobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->cl_insurance; ?>" /></a>
<?php else: ?>
<img id="cl_insurance_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>

                </div>

            </div>
 <div class="clear"></div>

 <div class="form-wrap">
<?php
$legal_info_progress = 0;
$legal_point_base = 20;
if(trim($jsondata->washer_details->pro_service_agree_sign) && trim($jsondata->washer_details->pro_service_agree)) $legal_info_progress += $legal_point_base;
if(trim($jsondata->washer_details->security_notice_agree)) $legal_info_progress += $legal_point_base;
if(trim($jsondata->washer_details->rating_system_agree)) $legal_info_progress += $legal_point_base;
if(trim($jsondata->washer_details->terms_of_use_agree)) $legal_info_progress += $legal_point_base;
if(trim($jsondata->washer_details->privacy_policy_agree)) $legal_info_progress += $legal_point_base;
?>
<?php if($legal_info_progress >= 100): ?>
<div class="meter">
	<span style="width: 100%"></span>
</div>
<?php else: ?>
<div class="meter red">
	<span style="width: <?php echo $legal_info_progress; ?>%"></span>
</div>
<?php endif; ?>
            <span class="section-no">7</span>
                <h2>Legal Documents</h2>
                <p style="font-size: 20px; margin-top: 10px; margin-left: 76px;" class="wash-subtitle">Please check the following legal documents</p>
                    <div class="form-content">

                    <p style="margin-top: 0;"><a href="pdfs/<?php echo $_GET['id']; ?>-pro-service-agreement.pdf" target="_blank">PRO SERVICE AGREEMENT</a> <?php if(trim($jsondata->washer_details->pro_service_agree_sign) && trim($jsondata->washer_details->pro_service_agree)) echo "<span style='color: green;'><i class='icon-check'></i></span>"; ?></p>

 <p><a href="pdfs/<?php echo $_GET['id']; ?>-payment-card-security-notice.pdf" target="_blank">PAYMENT CARD SECURITY NOTICE</a> <?php if(trim($jsondata->washer_details->security_notice_agree)) echo "<span style='color: green; margin-left: 5px;'><i class='icon-check'></i></span>"; ?></p>
<p><a href="pdfs/<?php echo $_GET['id']; ?>-notice-of-rating-system.pdf" target="_blank">NOTICE OF RATING SYSTEM</a> <?php if(trim($jsondata->washer_details->rating_system_agree)) echo "<span style='color: green; margin-left: 5px;'><i class='icon-check'></i></span>"; ?></p>
<p><a href="pdfs/<?php echo $_GET['id']; ?>-service-provider-privacy-policy.pdf" target="_blank">SERVICE PROVIDER PRIVACY POLICY</a> <?php if(trim($jsondata->washer_details->privacy_policy_agree)) echo "<span style='color: green; margin-left: 5px;'><i class='icon-check'></i></span>"; ?></p>
<p><a href="pdfs/<?php echo $_GET['id']; ?>-terms-of-use.pdf" target="_blank">TERMS OF USE</a> <?php if(trim($jsondata->washer_details->terms_of_use_agree)) echo "<span style='color: green; margin-left: 5px;'><i class='icon-check'></i></span>"; ?></p>

                   </div>
            </div>

            <div class="clear"></div>
<input type="submit" name="pre-washer-info-submit" value="Update">
        </form>
        <?php else: ?>
        <h2>No washer found</h2>
        <?php endif; ?>

		<div class="clear"></div>
<p style="text-align: center;"><a class="resend-email" href="#" style="color: #016fd0;">Resend Verification Email</a></p>
	</div>



<?php include_once('footer.php'); ?>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
  $(function() {
    $( "#dob" ).datepicker({
     changeMonth: true,
      changeYear: true,
    dateFormat: 'yy-mm-dd',
     yearRange: "1920:2016"
    });

$( "#insure_exp_date, #ssn_exp_date" ).datepicker({
     changeMonth: true,
      changeYear: true,
    dateFormat: 'yy-mm-dd',
     yearRange: "2016:2050"
    });

$(".resend-email").click(function(){

th = $(this);
$(this).text('Sending...');

$.getJSON( "/api/index.php?r=agents/resendprewasherverifyemail", {id: <?php echo $_GET['id']; ?>, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( json ) {
  if(json.result == 'true'){
$(th).text('Email sent successfully');
}
else{
$(th).text(json.response);
}
 });

return false;
});


  });
  </script>
<script>
function chooseFile(fileid) {
      $(fileid).click();
   }

   function readURL(input, imagename) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
         $('#'+imagename).show();
            $('#'+imagename).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


</script>
	<script>
		$(function() {
			$(".meter > span").each(function() {
				$(this).data("origWidth", $(this).width()).width(0).animate({
						width: $(this).data("origWidth")
					}, 1200);
			});
		});
	</script>