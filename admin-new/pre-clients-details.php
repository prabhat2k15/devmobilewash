<?php
include('header.php');
    if(isset($_POST['hidden'])){
        
        $data = array("id"=> $_GET['id'], "first_name" => $_POST['fname'], "last_name" => $_POST['lname'], "email" => $_POST['email'], "phone" => $_POST['phoneno'], "city" => $_POST['city'], "state" => $_POST['state'], "how_hear_mw" => $_POST['how_hear_mw'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);

        $handle = curl_init(ROOT_URL."/api/index.php?r=customers/preclientsupdate");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        //print_r($result);
        $updatedata = json_decode($result);
        $url = ROOT_URL.'/api/index.php?r=customers/preclientsdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $client_response = $jsondata->response;
            $client_result = $jsondata->result;
            if($client_result == 'true' && $client_response == 'clients details'){
    ?>
    <script type="text/javascript">window.location = "manage-pre-clients.php?update=done"</script>
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

<?php
            $id = $_GET['id'];
            $url = ROOT_URL.'/api/index.php?r=customers/preclientsdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
			
    
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">MobileWash Client Registration</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Pre Client Info</a>
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
                                                                <label class="control-label">Phone Number</label>
                                                                <input class="form-control" name="phoneno" type="text" title="Phone number with 7-9 and remaing 9 digit with 0-9" value="<?php echo $jsondata->washer_details->phone; ?>" required /> 
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
                                                                <label class="control-label">How did you hear about Mobile Wash?<span style="color: red;">*</span></label>
                                                                <input class="form-control" name="how_hear_mw" type="text" value="<?php echo $jsondata->washer_details->how_hear_mw; ?>" required /> 
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
        