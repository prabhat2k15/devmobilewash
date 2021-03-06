<?php
include('header.php');

if($jsondata_permission->users_type != 'admin'){
header("Location: ".ROOT_URL."/admin-new/index.php");
die();
}

if(isset($_POST['adduser-form-submit'])){

$handle = curl_init(ROOT_URL."/api/index.php?r=users/adduser");
        $data = array("email"=>$_POST['email'], "username"=>$_POST['username'], "password"=>$_POST['pass'], "usertype"=>$_POST['usertype'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
        
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$user_response = $jsondata->response;
$user_result_code = $jsondata->result;

if($user_result_code == 'true'){
    ?>
    <script type="text/javascript">window.location = "users.php?action=add-success"</script>
    <?php
}
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
        <?php include('right-sidebar.php') ?>

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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Add User</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    
                                                </ul>
<?php if(isset($_POST['adduser-form-submit']) && $user_result_code == 'false'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><?php echo $user_response; ?></p>
<?php endif; ?>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                        <form method="post" action="" id="add-user-form">
                                                            <div class="form-group">
                                                                <label class="control-label">Email<span style="color: red;">*</span></label>
                                                                <input type="email" name="email" class="form-control" style="width: 40%;" value="" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label" style="margin-top: 15px;">Username<span style="color: red;">*</span></label>
                                                                <input type="text" name="username" class="form-control" style="width: 40%;" value="" required /> </div>
                                                            <div class="form-group">
                                                                <label class="control-label" style="margin-top: 15px;">Password<span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="pass" style="width: 40%;" value="" required /> 
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="control-label" style="margin-top: 15px;">Confirm Password<span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="cpass" style="width: 40%;" value="" required /> 
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label" style="margin-top: 15px;">User Type<span style="color: red;">*</span></label>
                                                                <select name="usertype" class="form-control" style="width: 40%;" required>
<option value="scheduler">Scheduler</option>
<option value="recruiter">Recruiter</option>
<option value="admin">Admin</option>
</select>
                                                            </div>
                                                           
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" value="Submit" name="adduser-form-submit" style="color: rgb(255, 255, 255); margin-top: 10px; background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                        </form>
                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    
                                                    
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
<script>
$(function(){
$("#add-user-form").submit(function(e){
pass = $("#add-user-form input[name='pass']").val();
cpass = $("#add-user-form input[name='cpass']").val();
if(pass != cpass){
alert('Password and confirm password do not match');
e.preventDefault();
}
});
});
</script>

    