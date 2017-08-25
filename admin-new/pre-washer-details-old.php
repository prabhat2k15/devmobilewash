<?php
        if(isset($_POST['hidden'])){
        if($_FILES['driver_license']['tmp_name']){
        $driver_license = $_FILES['driver_license']['tmp_name'];
        $driver_license_type = pathinfo($_FILES['driver_license']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $dlname = 'agent_driver_license_'.$_GET['id']."_".$md5.".".$driver_license_type;
        move_uploaded_file($driver_license, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$dlname);
        $driver_license_image = 'agent_driver_license_'.$_GET['id']."_".$md5.".".$driver_license_type;
        }
        if($_FILES['proof_insurance']['tmp_name']){
        $proof_insurance = $_FILES['proof_insurance']['tmp_name'];
        $proof_insurance_type = pathinfo($_FILES['proof_insurance']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picame = 'proof_insurance_'.$_GET['id']."_".$md5.".".$proof_insurance_type;
        move_uploaded_file($proof_insurance, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$picame);
        $proof_insurance_image = 'proof_insurance_'.$_GET['id']."_".$md5.".".$proof_insurance_type;
        }

        if($_FILES['commercial_liability_insurance']['tmp_name']){
        $commercial_liability_insurance = $_FILES['commercial_liability_insurance']['tmp_name'];
        $commercial_liability_insurance_type = pathinfo($_FILES['commercial_liability_insurance']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $image_commercial_liability_insurance = 'commercial_liability_insurance_'.$_GET['id']."_".$md5.".".$commercial_liability_insurance_type;
        move_uploaded_file($commercial_liability_insurance, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$image_commercial_liability_insurance);
        $cl_insurance = 'commercial_liability_insurance_'.$_GET['id']."_".$md5.".".$commercial_liability_insurance_type;
        }
        if($_FILES['registration']['tmp_name']){
        $registration = $_FILES['registration']['tmp_name'];
        $registration_type = pathinfo($_FILES['registration']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $registrationimage = 'vehicle_register_'.$_GET['id']."_".$md5.".".$registration_type;
        move_uploaded_file($registration, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$registrationimage);
        $vehicle_register = 'vehicle_register_'.$_GET['id']."_".$md5.".".$registration_type;
        }

        if($_FILES['w9']['tmp_name']){
        $w9 = $_FILES['w9']['tmp_name'];
        $w9_type = pathinfo($_FILES['w9']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $w9_images = 'w9_'.$_GET['id']."_".$md5.".".$w9_type;
        move_uploaded_file($w9, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$w9_images);
        $w9_image = 'w9_'.$_GET['id']."_".$md5.".".$w9_type;
        }

        if($_FILES['vehcile_front_side']['tmp_name']){
        $vehcile_front_side = $_FILES['vehcile_front_side']['tmp_name'];
        $vehcile_front_side_type = pathinfo($_FILES['vehcile_front_side']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $vehcile_front_side_images = 'vehicle_front_img_'.$_GET['id']."_".$md5.".".$vehcile_front_side_type;
        move_uploaded_file($vehcile_front_side, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$vehcile_front_side_images);
        $vehicle_front_img = 'vehicle_front_img_'.$_GET['id']."_".$md5.".".$vehcile_front_side_type;
        }
        if($_FILES['vehcile_back_side']['tmp_name']){
        $vehcile_back_side = $_FILES['vehcile_back_side']['tmp_name'];
        $vehcile_back_side_type = pathinfo($_FILES['vehcile_back_side']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $vehcile_back_side_images = 'vehicle_back_img_'.$_GET['id']."_".$md5.".".$vehcile_back_side_type;
        move_uploaded_file($vehcile_back_side, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$vehcile_back_side_images);
        $vehicle_back_img = 'vehicle_back_img_'.$_GET['id']."_".$md5.".".$vehcile_back_side_type;
        }
        if($_FILES['vehcile_left_side']['tmp_name']){
        $vehcile_left_side = $_FILES['vehcile_left_side']['tmp_name'];
        $vehcile_left_side_type = pathinfo($_FILES['vehcile_left_side']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $vehcile_left_side_images = 'vehicle_left_img_'.$_GET['id']."_".$md5.".".$vehcile_left_side_type;
        move_uploaded_file($vehcile_left_side, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$vehcile_left_side_images);
        $vehicle_left_img = 'vehicle_left_img_'.$_GET['id']."_".$md5.".".$vehcile_left_side_type;
        }
        if($_FILES['vehcile_right_side']['tmp_name']){
        $vehcile_right_side = $_FILES['vehcile_right_side']['tmp_name'];
        $vehcile_right_side_type = pathinfo($_FILES['vehcile_right_side']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $vehcile_right_side_images = 'vehicle_right_img_'.$_GET['id']."_".$md5.".".$vehcile_right_side_type;
        move_uploaded_file($vehcile_right_side, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$vehcile_right_side_images);
        $vehicle_right_img = 'vehicle_right_img_'.$_GET['id']."_".$md5.".".$vehcile_right_side_type;
        }
        if($_FILES['equipment']['tmp_name']){
        $equipment = $_FILES['equipment']['tmp_name'];
        $equipment_type = pathinfo($_FILES['equipment']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $equipment_images = 'equipment_img_'.$_GET['id']."_".$md5.".".$equipment_type;
        move_uploaded_file($equipment, '/home/mobilewa/public_html/api/images/agent_img/agent_docs/'.$equipment_images);
        $equipment_img = 'equipment_img_'.$_GET['id']."_".$md5.".".$equipment_type;
        }


        $data = array("id"=> $_GET['id'], "first_name" => $_POST['fname'], "last_name" => $_POST['lname'], "email" => $_POST['email'], "phone" => $_POST['phoneno'], "city" => $_POST['city'], "state" => $_POST['state'], "zipcode" => $_POST['zipcode'], "date_of_birth" => $_POST['dob'], "street_address" => $_POST['staddr'], "suite_apt" => $_POST['suiteno'], "legally_eligible" => $_POST['legally_eligible'], "own_vehicle" => $_POST['own_vehicle'], "waterless_wash_product" => $_POST['waterless_wash_product'], "operate_area" => $_POST['operate_area'], "work_schedule" => $_POST['work_schedule'], "operating_as" => $_POST['operationmethod'], "company_name" => $_POST['companyname'], "wash_experience" => $_POST['wash_exp'], "driver_license" => $driver_license_image, "liable_insurance" => $proof_insurance_image,



        "cl_insurance" => $cl_insurance, "vehicle_register" => $vehicle_register, "w9" => $w9_image, "vehicle_front_img" => $vehicle_front_img, "vehicle_back_img" => $vehicle_back_img, "vehicle_left_img" => $vehicle_left_img, "vehicle_right_img" => $vehicle_right_img, "equipment_img" => $equipment_img, "insurance_expire_date"=> $_POST['insure_exp_date'], "routing_number" => $_POST['routing_number'], "bank_account_number" => $_POST['bank_account_number']);

        $handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/prewasherupdate");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        //print_r($result);
        $updatedata = json_decode($result);
        $url = 'https://www.mobilewash.com/api/index.php?r=agents/prewasherdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id']);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $washer_response = $jsondata->response;
            $washer_result = $jsondata->result;
            if($washer_result == 'true' && $washer_response == 'washer details'){
    ?>
    <script type="text/javascript">window.location = "manage-pre-washers.php?update=done"</script>
    <?php
}
    }
?>
<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token);
$handle_data = curl_init("https://www.mobilewash.com/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
?>
<?php
    if($washer_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "https://www.mobilewash.com/admin-new/index.php"</script><?php
    }
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME LAYOUT STYLES -->
       <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php
            $id = $_GET['id'];
            $url = 'https://www.mobilewash.com/api/index.php?r=agents/prewasherdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id']);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);


?>
<?php

$data = array("id"=> 16);
$handle = curl_init("https://www.mobilewash.com/dev/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$contracttext = curl_exec($handle);
curl_close($handle);

$data = array("id"=> 15);
$handle = curl_init("https://www.mobilewash.com/dev/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$contracttext_sec = curl_exec($handle);
curl_close($handle);

$data = array("id"=> 13);
$handle = curl_init("https://www.mobilewash.com/dev/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$contracttext_rating = curl_exec($handle);
curl_close($handle);

$data = array("id"=> 11);
$handle = curl_init("https://www.mobilewash.com/dev/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$contracttext_privacy = curl_exec($handle);
curl_close($handle);

$data = array("id"=> 10);
$handle = curl_init("https://www.mobilewash.com/dev/api/index.php?r=site/getcmsdata");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$contracttext_terms = curl_exec($handle);
curl_close($handle);

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
?>
<style>
#main{
    background-color: #EEF1F5;
}
.form-group {
    display: inline;
}
.imgbtn{
    text-align: center;
}
.green{
	background-color: green !important;
    border-color: green;
}

</style>
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content" id="main">
                    <!-- BEGIN PAGE HEADER-->


                    <!-- BEGIN PAGE TITLE-->
                   <!-- <h3 class="page-title"> New User Profile | Account
                        <small>user account page</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Registration</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_5" data-toggle="tab">Professional Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_6" data-toggle="tab">Driver Documents</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_8" data-toggle="tab">Vehicle Documents</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_7" data-toggle="tab">Bank Deposit Information</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_9" data-toggle="tab">Legal Documents</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                    <form action="" method="post" role="form">
                                                            <div class="form-group">
                                                                <label class="control-label">First Name<span style="color: red;">*</span></label>
                                                                <input type="text" name="fname" class="form-control" value="<?php echo $jsondata->washer_details->first_name; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Last Name<span style="color: red;">*</span></label>
                                                                <input type="text" name="lname" class="form-control" value="<?php echo $jsondata->washer_details->last_name; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Email<span style="color: red;">*</span></label>
                                                                <input type="email" class="form-control" name="email" value="<?php echo $jsondata->washer_details->email; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Date of Birth<span style="color: red;">*</span></label>
                                                                <input class="form-control form-control-inline date-picker" type="text" required="" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" name="dob" value="<?php echo $jsondata->washer_details->date_of_birth; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Phone Number<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="phoneno" type="text" title="Phone number with 7-9 and remaing 9 digit with 0-9" value="<?php echo $jsondata->washer_details->phone; ?>" required />
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label">Street Address<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="staddr" type="text" value="<?php echo $jsondata->washer_details->street_address; ?>" required />
															</div>
                                                            <div class="form-group">
                                                                <label class="control-label">Suite/Apt # (optional)</label>
                                                                <input class="form-control" name="suiteno" type="text" value="<?php echo $jsondata->washer_details->suite_apt; ?>" required />
															</div>
                                                            <div class="form-group">
                                                                <label class="control-label">City<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="city" type="text" value="<?php echo $jsondata->washer_details->city; ?>" required />
															</div>
                                                            <div class="form-group">
                                                                <label class="control-label">State<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="state" type="text" value="<?php echo $jsondata->washer_details->state; ?>" required />
															</div>
                                                            <div class="form-group">
                                                                <label class="control-label">Zipcode<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="zipcode" type="text" value="<?php echo $jsondata->washer_details->zipcode; ?>" required />
															</div>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

                                                            </div>
                                                            </form>
                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- Professional INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_5">
                                                    <form action="" method="post" role="form">
                                                            <div class="form-group">
                                                                <label class="control-label">ARE YOU LEGALLY ELIGIBLE TO WORK IN THE U.S.?<span style="color: red;">*</span></label>
                                                                <input type="text" name="legally_eligible" class="form-control" value="<?php echo $jsondata->washer_details->legally_eligible; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">DO YOU OWN A VEHICLE AND MOBILE AUTO DETAIL EQUIPMENT?<span style="color: red;">*</span></label>
                                                                <input type="text" name="own_vehicle" class="form-control" value="<?php echo $jsondata->washer_details->own_vehicle; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">DO YOU HAVE WATERLESS CAR WASH PRODUCTS?<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="waterless_wash_product" value="<?php echo $jsondata->washer_details->waterless_wash_product; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">WHAT AREAS WOULD YOU PREFER TO OPERATE IN?<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="operate_area" type="text" value="<?php echo $jsondata->washer_details->operate_area; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">WHAT IS YOUR PREFERRED WORK SCHEDULE?<span style="color: red;">*</span></label>
                                                                <input class="form-control" type="text" name="work_schedule" value="<?php echo $jsondata->washer_details->work_schedule; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">ARE YOU OPERATING AS A BUSINESS OR INDIVIDUAL?</label>
                                                                <select name="operationmethod" class="form-control" id="operationmethod">
<option value="Business" <?php if($jsondata->washer_details->operating_as == 'Business') echo "selected"; ?>>Business</option>
<option value="Individual" <?php if($jsondata->washer_details->operating_as == 'Individual') echo "selected"; ?>>Individual</option>
</select>

                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">NAME OF COMPANY<span style="color: red;">*</span></label>
                                                                <input class="form-control" type="text" name="companyname" value="<?php echo $jsondata->washer_details->company_name; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">HOW MANY YEARS OF CAR WASH EXPERIENCE?<span style="color: red;">*</span></label>
                                                                <input class="form-control" type="text" name="wash_exp" value="<?php echo $jsondata->washer_details->wash_experience; ?>" required />
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

                                                            </div>
                                                            </form>
                                                    </div>
                                                    <!-- END professional INFO TAB -->

                                                    <!-- CHANGE Document TAB -->
                                                    <div class="tab-pane" id="tab_1_6">
                                                        <form action="" method="post" enctype="multipart/form-data" role="form">
                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->driver_license; ?>" data-fancybox-group="gallery" title="Driver License"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->driver_license; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Select Driver License </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="driver_license" value="<?php echo $jsondata->washer_details->driver_license; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->liable_insurance; ?>" data-fancybox-group="gallery" title="Proof Insurance"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->liable_insurance; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Select Proof Insurance </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="proof_insurance" value="<?php echo $jsondata->washer_details->liable_insurance; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 220px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->cl_insurance; ?>" data-fancybox-group="gallery" title="Commercial Liability Insurance"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->cl_insurance; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Commercial Liability Insurance </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="commercial_liability_insurance" value="<?php echo $jsondata->washer_details->cl_insurance; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_register; ?>" data-fancybox-group="gallery" title="Registration"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_register; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Registration </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="registration" value="<?php echo $jsondata->washer_details->vehicle_register; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->w9; ?>" data-fancybox-group="gallery" title="W-9"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->w9; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> W-9 </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="w9" value="<?php echo $jsondata->washer_details->w9; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="clear" style="height: 13px;">&nbsp;</div>
                                                            <div class="form-group">
                                                                <label class="control-label">Insurance Expiration Date<span style="color: red;">*</span></label>
                                                                <input class="form-control form-control-inline date-picker" type="text" required="" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" name="insure_exp_date" value="<?php echo $jsondata->washer_details->insurance_expire_date; ?>" required />
                                                            </div>


                                                            <div class="margin-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END CHANGE Document TAB -->
                                                    <!-- CHANGE Document TAB -->
                                                    <div class="tab-pane" id="tab_1_8">
                                                        <form action="" method="post" enctype="multipart/form-data" role="form">
                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_front_img; ?>" data-fancybox-group="gallery" title="Vehcile Front Side"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_front_img; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Vehicle Front Side </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="vehcile_front_side" value="<?php echo $jsondata->washer_details->vehicle_front_img; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_back_img; ?>" data-fancybox-group="gallery" title="Vehcile Back Side"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_back_img; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Vehicle Back Side </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="vehcile_back_side" value="<?php echo $jsondata->washer_details->vehicle_back_img; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_left_img; ?>" data-fancybox-group="gallery" title="Vehcile Left Side"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_left_img; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Vehicle Left Side </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="vehcile_left_side" value="<?php echo $jsondata->washer_details->vehicle_left_img; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_right_img; ?>" data-fancybox-group="gallery" title="Vehcile Right Side"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->vehicle_right_img; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Vehicle Right Side </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="vehcile_right_side" value="<?php echo $jsondata->washer_details->vehicle_right_img; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="height: 130px; width: 165px;">
                                                                        <a class="fancybox" target="_blank" href="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->equipment_img; ?>" data-fancybox-group="gallery" title="Equipment"><img src="https://www.mobilewash.com/api/images/agent_img/agent_docs/<?php echo $jsondata->washer_details->equipment_img; ?>" alt="" /></a> </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                                    <div class="imgbtn">
                                                                        <span class="btn default btn-file">
                                                                            <span class="fileinput-new"> Equipment </span>
                                                                            <span class="fileinput-exists"> Change </span>
                                                                            <input type="file" name="equipment" value="<?php echo $jsondata->washer_details->equipment_img; ?>" /> </span>
                                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    </div>
                                                                </div>
                                                            </div>




                                                            <div class="margin-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END CHANGE Document TAB -->
                                                    <!-- BANK INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_7">
                                                        <form role="form" method="post" action="">
                                                            <div class="form-group">
                                                                <label class="control-label">Routing Number<span style="color: red;">*</span></label>
                                                                <input type="text" name="routing_number" class="form-control" value="<?php echo $jsondata->washer_details->routing_number; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Bank Account Number<span style="color: red;">*</span></label>
                                                                <input type="text" name="bank_account_number" class="form-control" value="<?php echo $jsondata->washer_details->bank_account_number; ?>" required /> </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- END BANK INFO TAB -->
                                                    <!-- BANK INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_9">
                                                            <div class="form-group">
                                                                <label class="control-label"><a href="pdfs/<?php echo $_GET['id']; ?>-pro-service-agreement.pdf" target="_blank">PRO SERVICE AGREEMENT</a></label>
                                                            </div>
                                                            <div class="clear" style="clear: both;">&nbsp;</div>
                                                            <div class="form-group">
                                                                <label class="control-label"><a href="pdfs/<?php echo $_GET['id']; ?>-payment-card-security-notice.pdf" target="_blank">PAYMENT CARD SECURITY NOTICE</a></label>
                                                            </div>
                                                            <div class="clear" style="clear: both;">&nbsp;</div>
                                                            <div class="form-group">
                                                                <label class="control-label"><a href="pdfs/<?php echo $_GET['id']; ?>-notice-of-rating-system.pdf" target="_blank">NOTICE OF RATING SYSTEM</a></label>
                                                            </div>
                                                            <div class="clear" style="clear: both;">&nbsp;</div>
                                                            <div class="form-group">
                                                                <label class="control-label"><a href="pdfs/<?php echo $_GET['id']; ?>-service-provider-privacy-policy.pdf" target="_blank">SERVICE PROVIDER PRIVACY POLICY</a></label>
                                                            </div>
                                                            <div class="clear" style="clear: both;">&nbsp;</div>
                                                            <div class="form-group">
                                                                <label class="control-label"><a href="pdfs/<?php echo $_GET['id']; ?>-terms-of-use.pdf" target="_blank">TERMS OF USE</a></label>
                                                            </div>
                                                    </div>
                                                    <!-- END BANK INFO TAB -->


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/profile.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>
