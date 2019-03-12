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
<link href="css/croppie.css" rel="stylesheet" type="text/css" />
<!-- BEGIN THEME LAYOUT STYLES -->
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
if (isset($_POST['hidden'])) {

    if (!empty($_POST['agentnewpic'])) {

        $data = $_POST['agentnewpic'];

        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
        $md5 = md5(uniqid(rand(), true));
        $picname = $md5 . ".jpg";
        file_put_contents(ROOT_WEBFOLDER . '/public_html/api/images/agent_img/' . $picname, $data);
        $profileimg = ROOT_URL . '/api/images/agent_img/' . $picname;
    } else {
        $profileimg = '';
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
    $total_wash = $_POST['total_wash'];
    $helper = $_POST['helper'];

    $driverlicense_expiration = $driver_license_expiration;

    $data = array('helper' => $helper, 'vehicle_pic' => $agent_vehicle_pic, 'first_name' => strip_tags($first_name), 'last_name' => strip_tags($last_name), 'email' => strip_tags($email), 'phone_number' => strip_tags($phone_number), 'date_of_birth' => strip_tags($date_of_birth), 'password' => strip_tags($password), 'street_address' => strip_tags($street_address), 'suite_apt' => strip_tags($suite_apt), 'city' => strip_tags($city), 'state' => strip_tags($state), 'zipcode' => strip_tags($zipcode), 'wash_experience' => strip_tags($wash_experience), 'rating' => strip_tags($rating), 'driver_license' => strip_tags($driver_license), 'business_license' => strip_tags($business_license), 'proof_insurance' => strip_tags($proof_insurance), 'image' => strip_tags($profileimg), 'agreement_prof' => strip_tags($agreement_prof), 'privacy_policy' => strip_tags($privacy_policy), 'notice_standard' => strip_tags($notice_standard), 'notice_card_security' => strip_tags($notice_card_security), 'mobile_type' => strip_tags($mobile_type), 'bank_account_number' => strip_tags($bank_account_number), 'routing_number' => strip_tags($routing_number), 'legally_eligible' => strip_tags($legally_eligible), 'own_vehicle' => strip_tags($own_vehicle), 'waterless_wash_product' => strip_tags($waterless_wash_product), 'operate_area' => strip_tags($operate_area), 'work_schedule' => strip_tags($work_schedule), 'operating_as' => strip_tags($operating_as), 'company_name' => strip_tags($company_name), 'email_alerts' => strip_tags($email_alerts), 'push_notifications' => strip_tags($push_notifications), 'agent_location' => strip_tags($agent_location), 'bt_submerchant_id' => strip_tags($bt_submerchant_id), 'status' => strip_tags($status), 'total_wash' => strip_tags($total_wash), 'account_status' => strip_tags($account_status), 'available_for_new_order' => strip_tags($available_for_new_order), 'driver_license_expiration' => strip_tags($driverlicense_expiration), 'washer_position' => strip_tags($_POST['washer_position']), 'real_washer_id' => strip_tags($_POST['real_washer_id']), 'admin_username' => $jsondata_permission->user_name, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
    //print_r($data);die;
    $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/addagent");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle);
    //print_r($result); die;
    curl_close($handle);
    $jsondata = json_decode($result);
    //print_r($jsondata); die;
    $response = $jsondata->response;
    $result_code = $jsondata->result;

    if ($result_code == "true") {
        ?>
        <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/edit-agent.php?id=<?php echo $jsondata->id; ?>"</script>
        <?php
        die();
    }
}
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

        width: 150px;
        height: 150px;
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
     .fileinput{
        margin:10px;
    }
    .add-agent .form-group{
        display:flex;
        justify-content:center;
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
        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-body" style="padding: 10px 0px 0px 20px;">
                <?php
                if ($result_code == "false") {
                    if ($response == 'Email already exists.')
                        echo "<p style='padding: 10px; background: red; color: #fff;'>Email already exists</p>";
                    else
                        echo "<p style='padding: 10px; background: red; color: #fff;'>" . $response . "</p>";
                }
                ?>
                <h3 class="form-section" style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Personal Info</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">First Name<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="first_name" class="form-control" value="" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" value="" name="last_name" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Email<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="email" value="" name="email" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Phone Number<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="" name="phone_number" title="Phone number with 7-9 and remaing 9 digit with 0-9" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Password<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="password" id="password" name="password" value="" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Confirm Password<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="password"  id="cpassword" class="form-control" value="" required />
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
                <!--/row-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control date-picker" value="" name="date_of_birth" placeholder="yyyy-mm-dd" required /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Mobile Type</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="mobile_type" class="form-control"  /> </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Bank Account Number<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" value="" name="bank_account_number" class="form-control" required /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Routing Number<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" value="" name="routing_number" class="form-control" required /> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Legally Eligible</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="legally_eligible" class="form-control"  /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Own Vehicle</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="own_vehicle" class="form-control"  /> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Waterless Wash Product</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="waterless_wash_product" class="form-control"  /> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Operate Area</label>
                            <div class="col-md-9">
                                <select class="form-control" name="operate_area">
                                    <option value="" ></option>
                                    <option value="CA" >CA</option>
                                    <option value="NV" >NV</option>
                                    <option value="AZ">AZ</option>
                                    <option value="FL" >FL</option>
                                    <option value="TX">TX</option>
                                </select> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Work Schedule</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="work_schedule" class="form-control"  /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Operating As</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="operating_as" class="form-control"  /> </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Company Name</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="company_name" class="form-control"  /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Washer Position<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="washer_position">
                                    <option value="demo">Demo</option>
                                    <option value="real">Real</option>

                                </select> </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Real Washer ID</label>
                            <div class="col-md-9">
                                <input type="text" value="" name="real_washer_id" class="form-control"  /> </div>
                        </div>
                    </div>

                </div>



                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Address</h3>
                <!--/row-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Street Address<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="" name="street_address"  required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Suite Apt</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="" name="suite_apt"  />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">City<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="" name="city" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">State<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="" name="state"  required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">ZipCode<span style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="text" value="" name="zipcode" class="form-control" required /> </div>
                        </div>
                    </div>

                </div>


                <!--/row-->

                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Work</h3>
                <!--/row-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Wash Experience</label>
                            <div class="col-md-9">
                                <input type="text" name="wash_experience" value="" class="form-control"  /> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Total Wash</label>
                            <div class="col-md-9">
                                <input type="text" name="total_wash" value="" class="form-control"  /> </div>
                        </div>
                    </div>


                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Rating</label>
                            <div class="col-md-9">
                                <input type="text" name="rating" value="" class="form-control"  /> </div>
                        </div>
                    </div>
                    <!--/span-->

                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Email Alerts</label>
                            <div class="col-md-9">
                                <input type="text" name="email_alerts" value="" class="form-control" /> </div>
                        </div>
                    </div>
                    <!--/span-->

                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Push Notifications</label>
                            <div class="col-md-9">
                                <input type="text" name="push_notifications" value="" class="form-control"  /> </div>
                        </div>
                    </div>
                    <!--/span-->

                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Agent Location</label>
                            <div class="col-md-9">
                                <input type="text" name="agent_location" value="" class="form-control"  /> </div>
                        </div>
                    </div>
                    <!--/span-->



                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">BT Submerchant ID</label>
                            <div class="col-md-9">
                                <input type="text" name="bt_submerchant_id" value="" class="form-control"  /> </div>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select class="form-control" name="status">
                                    <option value="offline">Offline</option>
                                    <option value="online">Online</option>

                                </select> </div>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Account Status</label>
                            <div class="col-md-9">
                                <select class="form-control" name="account_status">
                                    <option value="1">Active</option>
                                    <option value="0">Pending</option>
                                </select> </div>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Available For New Order</label>
                            <div class="col-md-9">
                                <select class="form-control" name="available_for_new_order">
                                    <option value="0">Not Available</option>
                                    <option value="1">Available</option>

                                </select> </div>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Driver License Expiration</label>
                            <div class="col-md-9">
                                <input type="text" name="driver_license_expiration" value="" class="form-control date-picker" placeholder="yyyy-mm-dd" /> </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Helper</label>
                            <div class="col-md-9">
                                <select class="form-control" name="helper">
                                    <option value="0" >No</option>
                                    <option value="1">Yes</option>

                                </select> </div>
                        </div>
                    </div>
                    <!--/span-->

                </div>
                <!--/row-->
                <h3 class="form-section"  style="margin: 30px 0; padding-bottom: 5px; border-bottom: 1px solid #e7ecf1;">Upload</h3>
                <!--/row-->
                <div class="row add-agent" style="padding: 0px 0px 0px 15px;">
                    <div id="agent-image-crop"></div>
                    <a href="javascript:void(0)" class="crop-result">Crop</a>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="driver_license_pic" class="driver_license_img" src="images/image_icon.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' />
                            <a class="driver_license_pic_link image-upload-btn" href="#" onclick="chooseFile('#driver_license'); return false;">Driver License</a>
                            <input type="file" name="driver_license" id="driver_license" value="" style="padding: 6px 0px 0px; display: none;" onchange="loaddriver_license(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="proof_insurance_pic" class="proof_insurance_img" src="images/image_icon.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' /> <a class="proof_insurance_pic_link image-upload-btn" href="#" onclick="chooseFile('#proof_insurance'); return false;">Proof Insurance</a> <input type="file" name="proof_insurance" id="proof_insurance" value="" style="padding: 6px 0px 0px; display: none;" onchange="loadproof_insurance(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="business_license_pic" class="business_license_img" src="images/image_icon.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' /> <a class="business_license_pic_link image-upload-btn" href="#" onclick="chooseFile('#business_license'); return false;">Business License</a> <input type="file" name="business_license" id="business_license" value="" style="padding: 6px 0px 0px; display: none;" onchange="loadbusiness_license(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="image_pic" class="image_img" src="images/image_icon.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' /> <a class="image_pic_link image-upload-btn" href="#" onclick="chooseFile('#image'); return false;">Profile Pic</a> <input type="file" name="image" id="image" value="" style="padding: 6px 0px 0px; display: none;" onchange="loadimage(event)" />
                            <input type="hidden" class="agentnewpic" name="agentnewpic" />
                        </div>

                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="washer_vehicle" class="driver_license_img" src="images/image_icon.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' />
                            <a class="driver_license_pic_link image-upload-btn" href="#" onclick="chooseFile('#washer_vehicle_pic'); return false;">Washer vehicle</a>
                            <input type="file" name="agent_vehicle_pic" id="washer_vehicle_pic" value="" style="padding: 6px 0px 0px; display: none;" accept="image/*"/>
                        </div>
                    </div>
                    <div class="form-group" >
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 0px 0px 0px;">
                            <img id="agreement_prof_pic" class="agreement_prof_img" src="images/pdf.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' />
                            <a class="agreement_prof_pic_link image-upload-btn" href="#" onclick="chooseFile('#agreement_prof'); return false;">Agreement Prof</a>
                            <input type="file" name="agreement_prof" id="agreement_prof" value="" accept="application/pdf" style="padding: 6px 0px 0px; display: none;" onchange="loadagreement_prof(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 30px 0px 0px;">
                            <img id="privacy_policy_pic" class="privacy_policy_img" src="images/pdf.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' />
                            <a class="privacy_policy_pic_link image-upload-btn" href="#" onclick="chooseFile('#privacy_policy'); return false;">Privacy Policy</a>
                            <input type="file" name="privacy_policy" id="privacy_policy" value="" accept="application/pdf" style="padding: 6px 0px 0px; display: none;" onchange="loadprivacy_policy(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 30px 0px 0px;">
                            <img id="notice_standard_pic" class="notice_standard_img" src="images/pdf.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' /> <a class="notice_standard_pic_link image-upload-btn" href="#" onclick="chooseFile('#notice_standard'); return false;">Notice Standard</a> <input type="file" name="notice_standard" id="notice_standard" value="" accept="application/pdf" style="padding: 6px 0px 0px; display: none;" onchange="loadnotice_standard(event)" />
                        </div>
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="padding: 0px 30px 0px 0px;">
                            <img id="notice_card_security_pic" class="notice_card_security_img" src="images/pdf.png" style='display: block; width: 150px; height: 150px; cursor: pointer;' /> <a class="notice_card_security_pic_link image-upload-btn" href="#" onclick="chooseFile('#notice_card_security'); return false;">Notice Card Security</a> <input type="file" name="notice_card_security" id="notice_card_security" accept="application/pdf" value="" style="padding: 6px 0px 0px; display: none;" onchange="loadnotice_card_security(event)" />
                        </div>


                    </div>
                    <!--/row-->
                </div>
                <div class="form-actions">
                    <div class="row" style="text-align: center;">
                        <div class="clear">&nbsp;</div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class=" col-md-12">
                                    <input type="hidden" name="hidden" value="hidden">
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

</script>
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
                format: 'jpeg'
            }).then(function (resp) {
                $('#image_pic').attr('src', resp);
                $('.agentnewpic').val(resp);
            });
        });
    }

    custprofilepicupload();
</script>
