<?php include('header.php') ?>
<?php

if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);

    if($client_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
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
        <link href="css/croppie.css" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME LAYOUT STYLES -->
        <script>

            $(document).ready(function() {
                $('#submit').click(function(event){
                    data = $('#password').val();
                    var len = data.length;
                    if($('#password').val() != $('#cpassword').val()) {
                        alert("Password and Confirm Password don't match");
                        event.preventDefault();
                    }
                    return true;
                });
            });
            </script>
            <?php
            if(!empty($_GET['cnt'])){
                ?>
                <script>
            $(document).ready(function() {
                $('#tab13').trigger('click');
            });
            </script>
                <?php
            }
            ?>
 <?php
 if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php
$handle_data = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('customerid' => $_GET['customerID'], 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$custdetails = json_decode($result);
$err = '';

    if(isset($_POST['hidden'])){
        
        if(!empty($_POST['custnewpic'])){

$data = $_POST['custnewpic'];

$data = str_replace('data:image/jpeg;base64,', '', $data);
$data = str_replace(' ', '+', $data);
$data = base64_decode($data);
$md5 = md5(uniqid(rand(), true));
$picname = $_POST['id']."_".$md5.".jpg";
file_put_contents(ROOT_WEBFOLDER.'/public_html/api/images/cust_img/'.$picname, $data);
$profileimg = ROOT_URL.'/api/images/cust_img/'.$picname;
}
else{
  $profileimg = $custdetails->image;  
}

   
            $id = $_POST['id'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $customername = $_POST['customername'];
            $email = $_POST['email'];
            $password = '';
            if($_POST['password']) $password = md5($_POST['password']);
            $current_password = $_POST['current_password'];
            $contact_number = $_POST['contact_number'];
            $email_alerts = $_POST['email_alerts'];
            $push_notifications = $_POST['push_notifications'];
            $total_wash = $_POST['total_wash'];
            $time_zone = $_POST['time_zone'];
            $time_zone = $_POST['time_zone'];
            $account_status = $_POST['account_status'];
            $created_date = $_POST['created_date'].' '.date('H:i:s');
            $updated_date = $_POST['updated_date'].' '.date('H:i:s');
            $online_status = $_POST['online_status'];
            $rating = $_POST['rating'];
             $hours_opt_check = $_POST['hours_opt_check'];
             $block_client = $_POST['block_client'];
             $notes = $_POST['notes'];

            $id = $_POST['id'];

            // COLLECT POST VALUE //

            $data = array('id'=> strip_tags($_GET['customerID']),'firstname' => $firstname, 'lastname' => $lastname, 'customername'=> strip_tags($customername),'email'=> strip_tags($email),'contact_number'=> strip_tags($contact_number),'email_alerts'=> strip_tags($email_alerts),'push_notifications'=> strip_tags($push_notifications),'total_wash'=> strip_tags($total_wash),'time_zone'=> strip_tags($time_zone),'account_status'=> strip_tags($account_status),'created_date'=> strip_tags($created_date),'updated_date'=> strip_tags($updated_date),'online_status'=> strip_tags($online_status),'rating'=> strip_tags($rating),'image'=> strip_tags($profileimg), 'password'=> strip_tags($password), 'how_hear_mw'=> strip_tags($_POST['how_hear_mw']), 'hours_opt_check' => strip_tags($hours_opt_check), 'block_client' => strip_tags($block_client), 'notes' => strip_tags($notes), 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

            // END COLLECT POST VALUE //

            $handle = curl_init(ROOT_URL."/api/index.php?r=customers/UpdateCustomersRecord");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;

            if($response == "updated successfully" && $result_code == "true"){
                ?>
           
            <?php
            }
            if($result_code == 'false'){
                $err .= $response."<br>";
            }
    }

            $customerID = $_GET['customerID'];
            $url = ROOT_URL.'/api/index.php?r=customers/EditCustomers&customerID='.$customerID;
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);

            $id = $jsondata->id;
            $customername = $jsondata->customername;
            $firstname = $jsondata->first_name;
            $lastname = $jsondata->last_name;
            $email = $jsondata->email;
            $contact_number = $jsondata->contact_number;
            $email_alerts = $jsondata->email_alerts;
            $push_notifications = $jsondata->push_notifications;
$how_hear_mw = $jsondata->how_hear_mw;
            $total_wash = $jsondata->total_wash;
            $time_zone = $jsondata->time_zone;
            $account_status = $jsondata->account_status;
            $created_date = $jsondata->created_date;
            $updated_date = $jsondata->updated_date;
            $online_status = $jsondata->online_status;
            $rating = $jsondata->rating;
            $image = $jsondata->image;
             $hours_opt_check = $jsondata->hours_opt_check;
             $block_client = $jsondata->block_client;
             $notes = $jsondata->notes;


			$url = ROOT_URL.'/api/index.php?r=customers/getcustomerlogindetail&customerID='.$customerID;
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
			$lastlogin = $jsondata->last_login;
			$login_time = $jsondata->login_time;
			$lastorder = $jsondata->lastorder;
			$complte_order = $jsondata->complte_order;
			$totalprice = $jsondata->totalprice;

?>
<style>
#main{
    background-color: #EEF1F5;
}
.green{
	background-color: green !important;
    border-color: green;
}

#cust-image-crop{
    display: none;
     width: 300px; 
      height: 300px;
      margin-bottom: 55px;
     
}

.profile-userpic img {
    float: none;
    margin: 0 auto;
    width: 200px;
    height: 200px;
    -webkit-border-radius: 50%!important;
    -moz-border-radius: 50%!important;
    border-radius: 50%!important;
    box-shadow: 0 0 5px #ccc;
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
</style>
$handle = curl_init(ROOT_URL."/api/index.php?r=site/getwashersavedroplog");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('wash_request_id' => $_GET['customerID'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $savedroplogdata = json_decode($result);
?>
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
                        <?php if((!empty($_POST)) && (empty($err))){ ?>
                        <div class="col-md-12">
                            <p style="background-color: rgb(255, 255, 255); height: 34px; padding: 6px 0px 0px 10px; color: green;">SAVE SUCCESSFULLY</p>
                        </div>
                        <?php } ?>
                         <?php if(!empty($err)){ ?>
                        <div class="col-md-12">
                            <p style="background-color: rgb(255, 255, 255); height: 34px; padding: 6px 0px 0px 10px; color: red;"><?php echo $err; ?></p>
                        </div>
                        <?php }
                        
                        ?>
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet ">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic">
                                        <img src="<?php echo $image; ?>" class="img-responsive" alt=""> </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name"> <?php echo $customername; ?> </div>
                                        <div class="profile-usertitle-job"> Customer </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->
                                    <!-- SIDEBAR BUTTONS -->
                                    <div class="profile-userbuttons">
									<?php if($online_status == 'online') { $color = 'green'; } else { $color = 'red'; } ?>
                                        <button type="button" class="btn btn-circle <?php echo $color; ?> btn-sm"><?php echo $online_status; ?></button>
                                    </div>
                                    <!-- END SIDEBAR BUTTONS -->
                                    <!-- SIDEBAR MENU -->
                                    <div class="profile-usermenu">
                                        &nbsp;
                                    </div>
                                    <!-- END MENU -->
                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light ">
                                    <!-- STAT -->

									<div class="row list-separated profile-stat">
                                        <?php if(!empty($totalprice)) { $totalprice = $totalprice; } else { $totalprice = 0; } ?>
                                        <div style="text-align: center;" class="uppercase profile-stat-title"> <?php echo '$'.$totalprice; ?> </div>
										<div class="uppercase profile-stat-text" style="text-align: center; font-size: 12px;"> Total Spent </div>
                                    </div>
									<div class="row list-separated profile-stat">
                                        <div class="uppercase profile-stat-title" style="text-align: center; font-size: 20px; text-transform: capitalize !important;"> <?php echo $complte_order; ?> </div>
										<div style="text-align: center; font-size: 12px;" class="uppercase profile-stat-text"> Last Completed Order </div>
                                    </div>

                                    <div class="row list-separated profile-stat">
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> <?php echo $total_wash; ?> </div>
                                            <div class="uppercase profile-stat-text"> Total Wash </div>
                                        </div>
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title"> <?php echo $rating; ?> </div>
                                            <div class="uppercase profile-stat-text"> Rating </div>
                                        </div>
                                    </div>
                                    <!-- END STAT -->
                                    <div class="row list-separated profile-stat">


										<div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title" style="font-size: 18px; font-weight: bold; text-transform: capitalize !important;">
											<?php if($lastlogin == '2 day(s) ago' || $lastlogin == '1 day(s) ago' || $lastlogin == 'Today' || $lastlogin == 'N/A'){ ?>
											<?php echo $lastlogin; ?>
											<?php }else{ ?>
                                            <?php echo $lastlogin; ?>
											<?php } ?>

											</div>
                                            <div class="uppercase profile-stat-text"> Last Login </div>
                                        </div>
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <div class="uppercase profile-stat-title" style="font-size: 18px; font-weight: bold;"> <?php echo $login_time; ?> </div>
                                            <div class="uppercase profile-stat-text"> Last Login At </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Edit Customer Account</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_3" id="tab13" data-toggle="tab">Change Password</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_4" data-toggle="tab">Privacy Settings</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                            <form role="form" method="post" action="" enctype="multipart/form-data">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">

                                                            <div class="form-group">
                                                                <label class="control-label">First Name<span style="color: red;">*</span></label>
                                                                <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Last Name<span style="color: red;">*</span></label>
                                                                <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Full Name</label>
                                                                <input type="text" name="customername" class="form-control" value="<?php echo $customername; ?>" /> </div>

                                                            <div class="form-group">
                                                                <label class="control-label">Email<span style="color: red;">*</span></label>
                                                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required/> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Phone Number<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="contact_number" type="text" title="Phone number with 7-9 and remaing 9 digit with 0-9" value="<?php echo $contact_number; ?>" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Email Alerts<span style="color: red;">*</span></label>
                                                                 <select name="email_alerts" class="form-control">
                                                                    <option value="1" <?php if($email_alerts == 1) echo "selected"; ?>>ON</option>
                                                                    <option value="0" <?php if($email_alerts == 0) echo "selected"; ?>>OFF</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Push Notifications<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="push_notifications" value="<?php echo $push_notifications; ?>" required />
                                                            </div>
<div class="form-group">
                                                                <label class="control-label">How did you hear about MobileWash?<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="how_hear_mw" value="<?php echo $how_hear_mw; ?>" required />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Time Zone<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="time_zone" value="<?php echo $time_zone; ?>" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Hours of Operation Check</label>
                                                                <select name="hours_opt_check" class="form-control">
                                                                    <option value="1" <?php if($hours_opt_check == 1) echo "selected"; ?>>ON</option>
                                                                    <option value="0" <?php if($hours_opt_check == 0) echo "selected"; ?>>OFF</option>
                                                                </select>

                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Block Client</label>
                                                                <select name="block_client" class="form-control">
                                                                    <option value="0" <?php if($block_client == 0) echo "selected"; ?>>No</option>
                                                                    <option value="1" <?php if($block_client == 1) echo "selected"; ?>>Yes</option>
                                                                </select>

                                                            </div>
                                                        <?php 
                                                            if($savedroplogdata->result == 'true'){?>
                                                                <div class="form-group" style="display: block; margin-top: 25px;">
                                                                    <div class="activity-logs">
                                                                        <?php foreach($savedroplogdata->logs as $log){ ?>
                                                                            <?php if($log->action == 'edit_customer'): ?>
                                                                                <p style="margin-bottom: 10px;"><?php echo $log->admin_username; ?> added customer note at <?php echo date('F j, Y - h:i A', strtotime($log->action_date)); ?></p>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                           <?php } ?>
                                                            <div class="form-group">
                                                                <label class="control-label">Customer Note</label>
                                                                <textarea name="notes" placeholder="Write Notes" id="notes" class="form-control"><?php echo $notes;?></textarea>

                                                            </div>


                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- CHANGE AVATAR TAB -->
                                                    <div class="tab-pane" id="tab_1_2">

                                                            <div class="form-group">
                                                                <div id="cust-image-crop"></div>
                                                                <input type="file" id="upload-custpic" value="Choose a file" accept="image/*" />
                                                                <a href="javascript:void(0)" class="crop-result">Crop</a>
                                                                <input type="hidden" class="custnewpic" name="custnewpic" />
                                                            </div>

                                                    </div>
                                                    <!-- END CHANGE AVATAR TAB -->
                                                    <!-- CHANGE PASSWORD TAB -->
                                                    <div class="tab-pane" id="tab_1_3">
                                                    <?php if(!empty($_GET['cnt'])){ ?>
                                                        <div class="form-group" style="text-align: center; color: red;">Current Password Wrong</div>
                                                    <?php } ?>


                                                            <div class="form-group">
                                                                <label class="control-label">New Password</label>
                                                                <input type="password" id="password" name="password" class="form-control" /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Re-type New Password</label>
                                                                <input type="password" id="cpassword" name="cpassword" class="form-control" /> </div>


                                                    </div>
                                                    <!-- END CHANGE PASSWORD TAB -->
                                                    <!-- PRIVACY SETTINGS TAB -->
                                                    <div class="tab-pane" id="tab_1_4">

                                                            <table class="table table-light table-hover">
                                                                <tr>
                                                                    <td> Account Status </td>
                                                                    <td  style="text-align: right;">
                                                                        <label class="uniform-inline">
                                                                            <input type="radio" name="account_status" value="1" <?php if($account_status == 1) { echo 'checked="checked"'; } ?> /> Active </label>
                                                                        <label class="uniform-inline">
                                                                            <input type="radio" name="account_status" value="0" <?php if($account_status == 0) { echo 'checked="checked"'; } ?> /> Pending </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td> Online Status </td>
                                                                    <td  style="text-align: right;">
                                                                        <label class="uniform-inline">
                                                                            <input type="radio" name="online_status" value="online" <?php if($online_status == 'online') { echo 'checked="checked"'; } ?> /> Online </label>
                                                                        <label class="uniform-inline">
                                                                            <input type="radio" name="online_status" value="offline" <?php if($online_status == 'offline') { echo 'checked="checked"'; } ?> /> Offline </label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <!--end profile-settings-->


                                                    </div>
                                                    <!-- END PRIVACY SETTINGS TAB -->
                                                </div>
                                                 <div class="margiv-top-10">
                                                                <input type="hidden" name="hidden" value="hidden">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="submit" value="Save Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                            </form>
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
        <script src="js/croppie.js" type="text/javascript"></script>
        <script>
            
           	function custprofilepicupload() {
		var $uploadCrop;

		function readFile(input) {
 			if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
					//$('.upload-demo').addClass('ready');
					$('#cust-image-crop').show();
                                        $('.crop-result').css('display', 'block');
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	}).then(function(){
	            		//console.log('jQuery bind complete');
	            	});
	            	
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        alert("Sorry - you're browser doesn't support the FileReader API");
		    }
		}

		$uploadCrop = $('#cust-image-crop').croppie({
			viewport: {
				width: 200,
				height: 200,
				type: 'circle'
			},
			enableExif: false
		});

		$('#upload-custpic').on('change', function () { readFile(this); });
		$('.crop-result').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport',
				circle: false,
				quality: .9,
				format: 'jpeg'
			}).then(function (resp) {
				$('.profile-sidebar .profile-userpic img').attr('src', resp);
				$('.custnewpic').val(resp);
			});
		});
	}
	
	custprofilepicupload();
        </script>