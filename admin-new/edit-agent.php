<?php include('header.php') ?>

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

<link rel="stylesheet" href="css/bootstrap-multiselect.css?v=<?= time(); ?>">
<!--<link rel="stylesheet" href="css/bootstrap-3.3.2.min.css">-->
<link href="css/croppie.css" rel="stylesheet" type="text/css" />
<!-- BEGIN THEME LAYOUT STYLES -->
<style>
    .multiselect-container>li>a>label {
        padding: 4px 20px 3px 20px;
    }
    .flex {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<script>

    $(document).ready(function () {
        $('#submit').click(function (event) {
            data = $('#password').val();
            var len = data.length;
            if ($('#password').val() != $('#cpassword').val()) {
                alert("Password and Confirm Password don't match");
                event.preventDefault();
            }
            return true;
        });
    });
</script>
<?php
if (!empty($_GET['cnt'])) {
    ?>
    <script>
        $(document).ready(function () {
            $('#tab13').trigger('click');
        });
    </script>
    <?php
}
?>
<?php include('right-sidebar.php') ?>

<?php
$err = '';
$handle = curl_init(ROOT_URL . "/api/index.php?r=agents/profiledetails");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('agent_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);


if (isset($_POST['edit-agent-submit'])) {

    if (!empty($_POST['agentnewpic'])) {

        $data = $_POST['agentnewpic'];

        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
        $md5 = md5(uniqid(rand(), true));
        $picname = $_GET['id'] . "_" . $md5 . ".jpg";
        file_put_contents(ROOT_WEBFOLDER . '/public_html/api/images/agent_img/' . $picname, $data);
        $profileimg = ROOT_URL . '/api/images/agent_img/' . $picname;
    } else {
        $profileimg = $profiledetail->image;
    }

// END PRFILE IMAGE //
// START DRIVER LICENCE IMAGE UPLOAD //

    if (!empty($_FILES['driver_license']['tmp_name'])) {
        $profile_pic = $_FILES['driver_license']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['driver_license']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $driver_license = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $driver_license = '';
    }

    if (!empty($_FILES['business_license']['tmp_name'])) {
        $profile_pic = $_FILES['business_license']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['business_license']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $business_license = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $business_license = '';
    }

// END DRIVER LICENCE IMAGE //
// START PROOF INSURANCE IMAGE //

    if (!empty($_FILES['proof_insurance']['tmp_name'])) {
        $profile_pic = $_FILES['proof_insurance']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['proof_insurance']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $proof_insurance = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $proof_insurance = '';
    }

// END PROOF INSURANCE //
// END PRFILE IMAGE //
/// washer vehical Image start
    if (!empty($_FILES['agent_vehicle_pic']['tmp_name'])) {
        $profile_pic = $_FILES['agent_vehicle_pic']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['agent_vehicle_pic']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_vehical_images/' . $picname);
        $agent_vehicle_pic = ROOT_URL . '/api/images/agent_vehical_images/' . $picname;
    } else {
        $agent_vehicle_pic = "";
    }

// START PROOF INSURANCE IMAGE //

    if (!empty($_FILES['agreement_prof']['tmp_name'])) {
        $profile_pic = $_FILES['agreement_prof']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['agreement_prof']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $agreement_prof = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $agreement_prof = '';
    }

// END PROOF INSURANCE //
// START PROOF INSURANCE IMAGE //

    if (!empty($_FILES['privacy_policy']['tmp_name'])) {
        $profile_pic = $_FILES['privacy_policy']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['privacy_policy']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $privacy_policy = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $privacy_policy = '';
    }

// END PROOF INSURANCE //
// START PROOF INSURANCE IMAGE //

    if (!empty($_FILES['notice_standard']['tmp_name'])) {
        $profile_pic = $_FILES['notice_standard']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['notice_standard']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $notice_standard = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $notice_standard = '';
    }

// END PROOF INSURANCE //
// START PROOF INSURANCE IMAGE //

    if (!empty($_FILES['notice_card_security']['tmp_name'])) {
        $profile_pic = $_FILES['notice_card_security']['tmp_name'];
        $profile_pic_type = pathinfo($_FILES['notice_card_security']['name'], PATHINFO_EXTENSION);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . "." . $profile_pic_type;
        move_uploaded_file($profile_pic, ROOT_WEBFOLDER . '/public_html/api/images/agent_img/agent_docs/' . $picname);
        $notice_card_security = ROOT_URL . '/api/images/agent_img/agent_docs/' . $picname;
    } else {
        $notice_card_security = '';
    }

// END PROOF INSURANCE //

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $password = $_POST['password'];
    $street_address = $_POST['street_address'];
    $suite_apt = $_POST['suite_apt'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $wash_experience = $_POST['wash_experience'];
    $rating = $_POST['rating'];
    $notes = $_POST['washer_note'];

    $mobile_type = $_POST['mobile_type'];
    $bank_account_number = $_POST['bank_account_number'];
    $routing_number = $_POST['routing_number'];
    $legally_eligible = $_POST['legally_eligible'];
    $own_vehicle = $_POST['own_vehicle'];
    $waterless_wash_product = $_POST['waterless_wash_product'];
    $operate_area = $_POST['operate_area'];
    $work_schedule = $_POST['work_schedule'];
    $operating_as = $_POST['operating_as'];
    $company_name = $_POST['company_name'];
    $email_alerts = $_POST['email_alerts'];
    $push_notifications = $_POST['push_notifications'];
    $agent_location = $_POST['agent_location'];
    $bt_submerchant_id = $_POST['bt_submerchant_id'];
    $status = $_POST['status'];
    $account_status = $_POST['account_status'];
    $available_for_new_order = $_POST['available_for_new_order'];
    $driver_license_expiration = $_POST['driver_license_expiration'];
    $insurance_expiration = $_POST['insurance_expiration'];
    $total_wash = $_POST['total_wash'];
    $block_washer = (int) $_POST['block_washer'];

    $driverlicense_expiration = $driver_license_expiration;
    $hours_opt_check = $_POST['hours_opt_check'];
    $rating_control = $_POST['rating_control'];
    $sms_control = $_POST['sms_control'];
    $unlimited_schedule_range = $_POST['unlimited_schedule_range'];
    $decals_installed = $_POST['decals_installed'];
    $updateBy = "WEB";
    $certificates = $_POST['certificates'];
    $service_area = $_POST['service_area'];
    $helper = $_POST['helper'];
    $certificates_str = '';

    if (!empty($certificates)) {
        $N = count($certificates);

        for ($i = 0; $i < $N; $i++) {
            $certificates_str .= $certificates[$i] . "|";
        }

        $certificates_str = rtrim($certificates_str, '|');
    }

    $data = array('helper' => $helper, 'vehicle_pic' => $agent_vehicle_pic, 'update_by' => $updateBy, 'unlimited_schedule_range' => $unlimited_schedule_range, 'agent_id' => strip_tags($_GET['id']), 'first_name' => strip_tags($first_name), 'last_name' => strip_tags($last_name), 'email' => strip_tags($email), 'phone_number' => strip_tags($phone_number), 'date_of_birth' => strip_tags($date_of_birth), 'new_password' => strip_tags($password), 'confirm_password' => strip_tags($password), 'street_address' => strip_tags($street_address), 'suite_apt' => strip_tags($suite_apt), 'city' => strip_tags($city), 'state' => strip_tags($state), 'zipcode' => strip_tags($zipcode), 'wash_experience' => strip_tags($wash_experience), 'rating' => strip_tags($rating), 'driver_license' => strip_tags($driver_license), 'business_license' => strip_tags($business_license), 'proof_insurance' => strip_tags($proof_insurance), 'agent_profile_img' => strip_tags($profileimg), 'agreement_prof' => strip_tags($agreement_prof), 'privacy_policy' => strip_tags($privacy_policy), 'notice_standard' => strip_tags($notice_standard), 'notice_card_security' => strip_tags($notice_card_security), 'mobile_type' => strip_tags($mobile_type), 'bank_account_number' => strip_tags($bank_account_number), 'routing_number' => strip_tags($routing_number), 'legally_eligible' => strip_tags($legally_eligible), 'own_vehicle' => strip_tags($own_vehicle), 'waterless_wash_product' => strip_tags($waterless_wash_product), 'operate_area' => strip_tags($operate_area), 'work_schedule' => strip_tags($work_schedule), 'operating_as' => strip_tags($operating_as), 'company_name' => strip_tags($company_name), 'email_alerts' => strip_tags($email_alerts), 'push_notifications' => strip_tags($push_notifications), 'agent_location' => strip_tags($agent_location), 'bt_submerchant_id' => strip_tags($bt_submerchant_id), 'status' => strip_tags($status), 'total_wash' => strip_tags($total_wash), 'account_status' => strip_tags($account_status), 'available_for_new_order' => strip_tags($available_for_new_order), 'driver_license_expiration' => strip_tags($driverlicense_expiration), 'insurance_expiration' => strip_tags($insurance_expiration), 'washer_position' => strip_tags($_POST['washer_position']), 'real_washer_id' => strip_tags($_POST['real_washer_id']), 'block_washer' => $block_washer, 'admin_edit' => 'true', 'notes' => strip_tags($notes), 'hours_opt_check' => strip_tags($hours_opt_check), 'rating_control' => strip_tags($rating_control), 'sms_control' => strip_tags($sms_control), 'admin_username' => $jsondata_permission->user_name, 'api_password' => AES256CBC_API_PASS, 'decals_installed' => strip_tags($decals_installed), 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'certificates' => $certificates_str, 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);


    $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/profileupdate");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle);
    curl_close($handle);
    $jsondata = json_decode($result);

    $response = $jsondata->response;
    $result_code = $jsondata->result;

    if ($result_code == 'false') {
        $err .= $response . "<br>";
    }

    $dir_name = ROOT_WEBFOLDER . '/public_html/admin-new/edit-washer-logs/' . $_GET['id'];

    if (!is_dir($dir_name)) {
        mkdir($dir_name);
    }

    $orig_filename = ROOT_WEBFOLDER . '/public_html/admin-new/edit-washer-logs/' . $_GET['id'] . '/log.txt';
    $context = stream_context_create();
    $orig_file = fopen($orig_filename, 'a+', 1, $context);

    $temp_filename = tempnam(sys_get_temp_dir(), 'php_prepend_' . time());
    file_put_contents($temp_filename, $jsondata_permission->user_name . " on " . date('M d, Y h:i A') . PHP_EOL);
    file_put_contents($temp_filename, $orig_file, FILE_APPEND);

    fclose($orig_file);
    unlink($orig_filename);
    rename($temp_filename, $orig_filename);
}

$handle = curl_init(ROOT_URL . "/api/index.php?r=agents/profiledetails");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('agent_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$profiledetail = json_decode($result);
?>
<style>
    .col-md-9 {
        width: 90%;
    }
    .col-md-3 {
        width: 65%;
    }
    .form-horizontal .control-label {
        margin-bottom: 0;
        padding-top: 7px;
        text-align: left;
    }
    .image-upload-btn {
        background: rgb(48, 159, 254) none repeat scroll 0 0;
        border-radius: 5px;
        color: rgb(255, 255, 255);
        display: block;
        padding: 11px 0;
        text-align: center;
        margin-top: 10px;
        text-decoration: none;
        width: 150px;
    }
    #washer_vehicle{
        border-radius: 16px;
    }
    a:hover{
        color: #fff !important;
        text-decoration: none !important;
    }

    #agent-image-crop{
        display: none;
        width: 300px; 
        height: 300px;
        margin-bottom: 55px;

    }

    #image_pic {

        width: 200px;
        height: 200px;
        -webkit-border-radius: 50%!important;
        -moz-border-radius: 50%!important;
        border-radius: 50%!important;
        box-shadow: 0 0 5px #ccc;
        margin-bottom: 10px;
    }

    .crop-result{
        color: rgb(255, 255, 255);
        background-color: rgb(50, 197, 210);
        border: 1px solid rgb(50, 197, 210);
        padding: 6px 7px 7px 6px;
        border-radius: 3px;
        margin-top: 20px;
        display: block;
        width: 75px;
        text-align: center;
        text-decoration: none !important;
        color: #fff;
        display: none;
    }
    textarea.washerNotesArea{
        height:36px;
    }
    .form-control.error{
        border:1px solid #c2cad8;
    }
    .fileinput{
        margin:10px;
    }
    .edit-agent .form-group{
        display:flex;
        justify-content:center;
    }
    .cstmMultiselect .multiselect-container li a{
        padding-left:30px;

    }
    .cstmMultiselect .multiselect-container li a:hover{
        color:#333!important;
    }
    .cstmMultiselect .btn-group{
        width:100%;
    }

    .cstmMultiselect .btn-group .multiselect{
        width:100%;
        text-align:left;
    }
    .cstmMultiselect .btn-group .multiselect .caret{
        float: right;
        margin-top: 0;
        right: 4px;
        position: absolute;
        top: 15px;
    }
</style>
<?php if ($jsondata_permission->users_type != 'admin'): ?>
    <style>
        #edit_agent_form input, #edit_agent_form select, #edit_agent_form textarea{
            pointer-events: none; 
        }

        #edit_agent_form .image-upload-btn{
            display: none;
        }

        #edit_agent_form select[name='block_washer'], #edit_agent_form textarea[name='washer_note'], #edit_agent_form select[name='decals_installed']{
            pointer-events: auto; 
        }
        .cstmMultiselect .btn-group{
            width:100%;
        }

        .cstmMultiselect .btn-group .multiselect{
            width:100%;
            text-align:left;
        }
        .cstmMultiselect .btn-group .multiselect .caret{
            float:right;
            margin-top:10px;
        }
    </style>
<?php endif; ?>
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" id="main">

        <form action="" class="form-horizontal" id="edit_agent_form" method="post" enctype="multipart/form-data">
            <div class="form-body" style="padding: 10px 0px 0px 20px;">
                <?php
                if (!empty($err)) {
                    echo "<p style='padding: 10px; background: red; color: #fff;'>" . $err . "</p>";
                }
                ?>
                <?php
                if ((!empty($_POST)) && (empty($err))) {
                    echo "<p style='padding: 10px; background: green; color: #fff;'>Update successful</p>";
                }
                ?>
                <h3 class="form-section" style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Personal Info</h3>
                <div class="row">
                    <div class="col-md-4">
                        <p>Created On: <?php echo date('M j, Y h:i A', strtotime($profiledetail->created_date)); ?></p>
                    </div>
                    <!--/span-->
                    <div class="col-md-4" style="margin-top: 20px;">
                        <?php if (strtotime($profiledetail->last_admin_edit_at) > 0): ?>
                            <span>Last Edited by: <?php echo $profiledetail->last_edited_admin; ?></span>
                            <span>on <?php echo date('M j, Y h:i A', strtotime($profiledetail->last_admin_edit_at)); ?></span>
                        <?php else: ?>
                            <span>Last Edited by: N/A</span>
                        <?php endif; ?>
                    </div>
                    <!--/span-->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">First Name</label>
                            <div class="col-md-9">
                                <input type="text" name="first_name" class="form-control" value="<?php echo $profiledetail->first_name; ?>" />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name</label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $profiledetail->last_name; ?>" name="last_name" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input type="email" value="<?php echo $profiledetail->email; ?>" name="email" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Phone Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo $profiledetail->phone_number; ?>" name="phone_number" title="Phone number with 7-9 and remaing 9 digit with 0-9" />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control date-picker" value="<?php echo $profiledetail->date_of_birth; ?>" name="date_of_birth" placeholder="yyyy-mm-dd" required /> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Service area</label>
                            <div class="col-md-9">
                                <select class="form-control" name="operate_area">

                                    <option value="CA" <?php if ($profiledetail->operate_area == 'CA') echo 'selected'; ?>>CA</option>
                                    <option value="NV" <?php if ($profiledetail->operate_area == 'NV') echo 'selected'; ?>>NV</option>
                                    <option value="AZ" <?php if ($profiledetail->operate_area == 'AZ') echo 'selected'; ?>>AZ</option>
                                    <option value="FL" <?php if ($profiledetail->operate_area == 'FL') echo 'selected'; ?>>FL</option>
                                    <option value="TX" <?php if ($profiledetail->operate_area == 'TX') echo 'selected'; ?>>TX</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Bank Account Number</label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $profiledetail->bank_account_number; ?>" name="bank_account_number" class="form-control" /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Routing Number</label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $profiledetail->routing_number; ?>" name="routing_number" class="form-control" /> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Company Name</label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $profiledetail->company_name; ?>" name="company_name" class="form-control"  /> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Washer Position</label>
                            <div class="col-md-9">
                                <select class="form-control" name="washer_position">
                                    <option value="demo">Demo</option>
                                    <option value="real" <?php if ($profiledetail->washer_position == 'real') echo 'selected'; ?>>Real</option>

                                </select> </div>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Badge ID</label>
                            <div class="col-md-9">
                                <input type="text" name="real_washer_id" class="form-control" value="<?php echo $profiledetail->real_washer_id; ?>"> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Block Washer</label>
                            <div class="col-md-9">
                                <select class="form-control" name="block_washer">
                                    <option value="0">No</option>
                                    <option value="1" <?php if ($profiledetail->block_washer == 1) echo 'selected'; ?>>Yes</option>

                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Washer Notes</label>
                            <div class="col-md-9">
                                <textarea name="washer_note"  class="form-control washerNotesArea"><?php echo $profiledetail->notes; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Decals Installed</label>
                            <div class="col-md-9">
                                <select class="form-control" name="decals_installed">

                                    <option value="1" <?php if ($profiledetail->decals_installed == 1) echo 'selected'; ?>>Yes</option>
                                    <option value="0" <?php if (!$profiledetail->decals_installed) echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
               
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unlimited Schedule Range</label>
                            <div class="col-md-9">
                                <select class="form-control" name="unlimited_schedule_range">

                                    <option value="1" <?php if ($profiledetail->unlimited_schedule_range == 1) echo 'selected'; ?>>Yes</option>
                                    <option value="0" <?php if ($profiledetail->unlimited_schedule_range == 0) echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">SMS</label>
                            <div class="col-md-9">
                                <select class="form-control" name="sms_control">

                                    <option value="1" <?php if ($profiledetail->sms_control == 1) echo 'selected'; ?>>ON</option>
                                    <option value="0" <?php if (!$profiledetail->sms_control) echo 'selected'; ?>>OFF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Helper</label>
                            <div class="col-md-9">
                                <select class="form-control" name="helper">
                                    <option value="0" <?php if ($profiledetail->helper == 0) echo 'selected'; ?>>No</option>
                                    <option value="1" <?php if ($profiledetail->helper == 1) echo 'selected'; ?>>Yes</option>

                                </select> </div>
                        </div>
                    </div>

                    <div class="col-md-6 cstmMultiselect">
                        <div class="form-group">
                            <label class="control-label col-md-3">Certificates</label>
                            <div class="col-md-11">
                                <select classs="form-control" id="chkveg" name="certificates[]" multiple="multiple">
                                    <?php
                                    $all_certs = array();
                                    if ($profiledetail->certificates) {
                                        $all_certs = explode("|", $profiledetail->certificates);
                                    }
                                    ?>
                                    <option <?php if (in_array("Head Light Restoration", $all_certs)) echo "selected"; ?> value="Head Light Restoration">Head Light Restoration</option>
                                    <option <?php if (in_array("Clay Bar & Paste Wax", $all_certs)) echo "selected"; ?> value="Clay Bar & Paste Wax">Clay Bar & Paste Wax</option>
                                    <option <?php if (in_array("Interior Shampooing", $all_certs)) echo "selected"; ?> value="Interior Shampooing">Interior Shampooing</option>
                                    <option <?php if (in_array("Ceramic Coating", $all_certs)) echo "selected"; ?> value="Ceramic Coating">Ceramic Coating</option>
                                    <option <?php if (in_array("Tree Sap Removal", $all_certs)) echo "selected"; ?> value="Tree Sap Removal">Tree Sap Removal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>


                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1; margin-top: 3em;">Address</h3>
                <!--/row-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Street Address<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo $profiledetail->street_address; ?>" name="street_address"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Suite Apt</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo $profiledetail->suite_apt; ?>" name="suite_apt"  />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">City<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo $profiledetail->city; ?>" name="city" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">State<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="<?php echo $profiledetail->state; ?>" name="state" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">ZipCode<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" value="<?php echo $profiledetail->zipcode; ?>" name="zipcode" class="form-control" /> </div>
                        </div>
                    </div>

                </div>


                <!--/row-->

                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Work</h3>
                <!--/row-->
                <div class="row">
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Rating <?php if (($jsondata_permission->users_type == 'admin')): ?><a href="#" class="reset-rating" style="margin-left: 10px; color: #337ab7 !important;">Reset</a><?php endif; ?></label>
                            <div class="col-md-9">
                                <input type="text" name="rating" value="<?php echo $profiledetail->rating; ?>" class="form-control"  /> </div>
                        </div>
                    </div>
                
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">BT Submerchant ID</label>
                            <div class="col-md-9">
                                <input type="text" name="bt_submerchant_id" value="<?php echo $profiledetail->bt_submerchant_id; ?>" class="form-control"  /> </div>
                        </div>
                    </div>
                    
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Driver License Expiration</label>
                            <div class="col-md-9">
                                <input type="text" name="driver_license_expiration" value="" class="form-control date-picker" placeholder="yyyy-mm-dd" /> </div>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Insurance Expiration</label>
                            <div class="col-md-9">
                                <input type="text" name="insurance_expiration" value="<?php if (strtotime($profiledetail->insurance_expiration) > 0) echo $profiledetail->insurance_expiration; ?>" class="form-control date-picker" placeholder="yyyy-mm-dd" /> </div>
                        </div>
                    </div>
                    <!--/span-->


                </div>
                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Upload</h3>
                <!--/row-->
                <div class="row    edit-agent" style="padding: 0px 0px 0px 15px;">
                    <div id="agent-image-crop"></div>
                    <a href="javascript:void(0)" class="crop-result">Crop</a>
                    <div class="form-group">
                        <div class="fileinput fileinput-new col-2" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="image_pic" class="image_img" src="<?php
                            if ($profiledetail->image) {
                                echo $profiledetail->image;
                            } else {
                                echo "images/image_icon.png";
                            }
                            ?>" style='display: block; width: 150px; height: auto; cursor: pointer;' /> <a class="image_pic_link image-upload-btn" href="#" onclick="chooseFile('#image'); return false;">Profile Pic</a> 
                            <input type="file" name="image" id="image" value="" style="padding: 6px 0px 0px; display: none;" onchange="loadimage(event)" />
                            <input type="hidden" class="agentnewpic" name="agentnewpic" />
                        </div>
                    </div>
                   
                    <!--/row-->
                </div>

                <div class="form-actions">
                    <div class="row" style="text-align: center;">
                        <div class="clear">&nbsp;</div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="edit-agent-submit" value="hidden">
                                    <button type="submit" id="submit" class="btn green">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"> </div>
                    </div>
                </div>

        </form>


    </div>
    <!-- END CONTENT BODY -->
</div>

</div>
</div>
<!-- END CONTENT BODY -->
</div>

<script>
    function chooseFile(fileid) {
        $(fileid).click();
    }


    var loaddriver_license = function (event) {
        var output1 = document.getElementById('driver_license_pic');
        output1.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadbusiness_license = function (event) {
        var output2 = document.getElementById('business_license_pic');
        output2.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadproof_insurance = function (event) {
        var output3 = document.getElementById('proof_insurance_pic');
        output3.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadimage = function (event) {
        var output4 = document.getElementById('image_pic');
        output4.src = URL.createObjectURL(event.target.files[0]);
    };
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#washer_vehicle').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#washer_vehicle_pic").change(function () {
        readURL(this);
    });

</script>
<script>
    $(document).ready(function () {
        $('#submit').click(function (event) {
            data = $('#password').val();
            var len = data.length;
            if (data) {
                if ($('#password').val() != $('#cpassword').val()) {
                    alert("Password and Confirm Password don't match");
                    event.preventDefault();
                }
            }
            return true;
        });
    });
</script>
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
<script src="js/croppie.js" type="text/javascript"></script>
<script src="js/bootstrap-3.3.2.min.js?v=<?= time(); ?>" type="text/javascript"></script>
<script src="js/bootstrap-multiselect.js?v=<?= time(); ?>" type="text/javascript"></script>
<script>

    function custprofilepicupload() {
        var $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    //$('.upload-demo').addClass('ready');
                    $('#agent-image-crop').show();
                    $('.crop-result').css('display', 'block');
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        //console.log('jQuery bind complete');
                    });

                }

                reader.readAsDataURL(input.files[0]);
            } else {
                alert("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#agent-image-crop').croppie({
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            },
            enforceBoundary: false,
            enableExif: false
        });

        $('#image').on('change', function () {
            readFile(this);
        });
        $('.crop-result').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport',
                circle: false,

                quality: .9,
                format: 'png'
            }).then(function (resp) {

                $('#image_pic').attr('src', resp);
                $('.agentnewpic').val(resp);
            });
        });
    }

    custprofilepicupload();

</script>
<?php if (($jsondata_permission->users_type == 'admin')): ?>
    <script>
        $(function () {
            $(".reset-rating").click(function () {
                var c = confirm("Are you sure you want to reset this washer's rating? This cannot be undone");
                if (c) {
                    var th = $(this);
                    $(this).html('Please wait...');
                    $(this).removeClass('reset-rating');
                    $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=agents/resetwasherrating", {agent_id: "<?php echo $_GET['id']; ?>", key: '<?php echo API_KEY; ?>', api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                        if (data.result == 'true') {
                            window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-agent.php?id=<?php echo $_GET['id']; ?>";
                                                } else {
                                                    alert(data.response);
                                                    $(th).html('Reset');
                                                    $(th).addClass('reset-rating');
                                                }

                                            });
                                        }

                                        return false;
                                    });
                                });
    </script>
<?php endif; ?>

<script>
    $("#edit_agent_form").validate({
        errorElement: 'span',
        rules: {
            rating: {
                required: true,
                number: true,
                max: 5,
            },
        },
        messages: {
            rating: {
                required: "Please enter rating",
            },
        }


    });
</script>
<!--<script>
    $(function () {
        $('#chkveg').multiselect({
            includeSelectAllOption: true;
             $('#example-getting-started').multiselect();
        });

    });
</script>-->
<script type="text/javascript">
    $(document).ready(function () {
        $('#chkveg').multiselect({
            includeSelectAllOption: false
        });
        $(".multiselect").on("click", function () {
            $(this).parent().toggleClass("open");
        });
    });
</script>