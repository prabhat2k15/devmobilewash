<?php
    if(isset($_POST['hidden'])){
        
        $data = array("id"=> $_GET['id'], "user_id" => $_POST['user_id'], "user_email" => $_POST['user_email'], "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "phone" => $_POST['phone'], "address" => $_POST['address'], "city" => $_POST['city'], "state" => $_POST['state'], "zip" => $_POST['zip'], "ID_number" => $_POST['ID_number'], "DL_ID_exp" => $_POST['DL_ID_exp'], "insurance_exp" => $_POST['insurance_exp'], "payment_due_d_ins" => $_POST['payment_due_d_ins'], "account_name" => $_POST['account_name'], "SSN_ITIN_TAX_ID" => $_POST['SSN_ITIN_TAX_ID'], "routing_number" => $_POST['routing_number'], "account_number" => $_POST['account_number'], "trash_status" => $_POST['trash_status'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

        $handle = curl_init("http://www.devmobilewash.com/api/index.php?r=customers/preclientsupdate2");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        //print_r($result);
        $updatedata = json_decode($result);
        $url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsdetails2';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
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
    <script type="text/javascript">window.location = "active-washers.php?update=done"</script>
    <?php
}
    }
    if(isset($_POST['hidden2'])){
            
            if(!empty($_FILES['image']['tmp_name']))
            {
                $profile_pic = $_FILES['image']['tmp_name'];
                $profile_pic_type = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $md5 = md5(uniqid(rand(), true));
                $picname = $_POST['id']."_".$md5.".".$profile_pic_type;
                move_uploaded_file($profile_pic, '/home/mobilewa/public_html/api/images/washer_img/'.$picname);
                $profileimg = 'http://www.devmobilewash.com/api/images/washer_img/'.$picname;
            }
            else
            {
                $profileimg = $_POST['emptyimage'];
            }
            
            // END PROFILE IMAGE //
            
            
            
            $id = $_POST['id'];
            $data = array('id'=> $id,'image'=> $profileimg, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            
            // END COLLECT POST VALUE //
            
            $handle = curl_init("http://www.devmobilewash.com/api/index.php?r=customers/updatewasherimage");
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
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/act-washer-details.php?id=<?php echo $id; ?>&cnf=done"</script>
            <?php
            }
            
    }
?>
<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
?>
<?php
    if($client_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/index.php"</script><?php
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
            $url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsdetails2';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
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
                    <?php if(!empty($_GET['cnf'])){ ?>
                        <div class="col-md-12">
                            <p style="background-color: rgb(255, 255, 255); height: 34px; padding: 6px 0px 0px 10px; color: green;">SAVE SUCCESSFULLY</p>
                        </div>
                        <?php } ?>
                        <div class="col-md-12">
                        <!-- BEGIN PROFILE SIDEBAR -->
                        <div class="profile-sidebar">
    <!-- PORTLET MAIN -->
    <div class="portlet light profile-sidebar-portlet ">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
        <?php 
        $image = $jsondata->washer_details->washer_img;
        if(!empty($image)){ ?>
        <img src="<?php echo $image; ?>" class="img-responsive" alt="">
        <?php }else{ ?>
            <img src="http://www.devmobilewash.com/api/images/cust_img/no_profile.jpg" class="img-responsive" alt=""> 
            <?php } ?>
            </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name"> 
            <?php echo $jsondata->washer_details->first_name.'&nbsp;'.$jsondata->washer_details->last_name; ?> 
            </div>
            <div class="profile-usertitle-job"> Washer </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <!--<div class="profile-userbuttons">
        <button type="button" class="btn btn-circle green btn-sm">online</button>
        </div>-->
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
            &nbsp;
        </div>
        <!-- END MENU -->
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
                                <span class="caption-subject font-blue-madison bold uppercase">MobileWash Washer Registration</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab">Active Washer Info</a>
                                </li>
                                <li class="">
                            <a data-toggle="tab" href="#tab_1_2" aria-expanded="true">Change Avatar</a>
                                </li>
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <!-- PERSONAL INFO TAB -->
                <div class="tab-pane active" id="tab_1_1">
                <form action="" method="post" role="form">

                <div class="form-group">
                <label class="control-label">User Email<span style="color: red;">*</span></label>
                <input type="email" class="form-control" name="user_email" value="<?php echo $jsondata->washer_details->user_email; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">First Name<span style="color: red;">*</span></label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $jsondata->washer_details->first_name; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Last Name<span style="color: red;">*</span></label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $jsondata->washer_details->last_name; ?>" required /> 
                </div>


                <div class="form-group">
                <label class="control-label">Phone Number</label>
                <input class="form-control" name="phone" type="text" title="Phone number with 7-9 and remaing 9 digit with 0-9" value="<?php echo $jsondata->washer_details->phone; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Address</label>
                <input class="form-control" name="address" type="text" title="Phone number with 7-9 and remaing 9 digit with 0-9" value="<?php echo $jsondata->washer_details->address; ?>" required /> 
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
                <label class="control-label">Zip<span style="color: red;">*</span></label>
                <input class="form-control" name="zip" type="text" value="<?php echo $jsondata->washer_details->zip; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">ID Number<span style="color: red;">*</span></label>
                <input class="form-control" name="ID_number" type="text" value="<?php echo $jsondata->washer_details->ID_number; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">DL/ID Exp<span style="color: red;">*</span></label>
                <input class="form-control" name="DL_ID_exp" type="text" value="<?php echo $jsondata->washer_details->DL_ID_exp; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Insurance Exp<span style="color: red;">*</span></label>
                <input class="form-control" name="insurance_exp" type="text" value="<?php echo $jsondata->washer_details->insurance_exp; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Payment DUE (Ins.)<span style="color: red;">*</span></label>
                <input class="form-control" name="payment_due_d_ins" type="text" value="<?php echo $jsondata->washer_details->payment_due_d_ins; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Account Name<span style="color: red;">*</span></label>
                <input class="form-control" name="account_name" type="text" value="<?php echo $jsondata->washer_details->account_name; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Account Number<span style="color: red;">*</span></label>
                <input class="form-control" name="account_number" type="text" value="<?php echo $jsondata->washer_details->account_number; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">Routing Number<span style="color: red;">*</span></label>
                <input class="form-control" name="routing_number" type="text" value="<?php echo $jsondata->washer_details->routing_number; ?>" required /> 
                </div>
                <div class="form-group">
                <label class="control-label">SSN or ITIN or TAX ID<span style="color: red;">*</span></label>
                <input class="form-control" name="SSN_ITIN_TAX_ID" type="text" value="<?php echo $jsondata->washer_details->SSN_ITIN_TAX_ID; ?>" required /> 
                </div>
    <!--<div class="form-group">
    <label class="control-label">Trash Status<span style="color: red;">*</span></label>
    <input class="form-control" name="state" type="text" value="<?php echo $jsondata->washer_details->trash_status; ?>" required /> 
    </div>-->


    <!-- <div class="form-group">
    <label class="control-label">How did you hear about Mobile Wash?<span style="color: red;">*</span></label>
    <input class="form-control" name="how_hear_mw" type="text" value="<?php echo $jsondata->washer_details->how_hear_mw; ?>" required /> 
    </div>-->

               <div class="clear" style="height: 10px;">&nbsp;</div>
               <div class="margiv-top-10">
            <input type="hidden" name="hidden" value="hidden">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $jsondata->washer_details->user_id; ?>" />
            <input name="trash_status" type="hidden" value="<?php echo $jsondata->washer_details->trash_status; ?>" />
            <input type="submit" value="Update Changes" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />

            </div>
            </form>
                </div>
                                
                <div id="tab_1_2" class="tab-pane">
                <form role="form" enctype="multipart/form-data" method="post" action="">
                <div class="form-group">
                <div data-provides="fileinput" class="fileinput fileinput-new">
                <div style="width: 200px; height: 150px;" class="fileinput-new thumbnail">
                <img alt="" src="http://www.devmobilewash.com/api/images/cust_img/no_profile.jpg"> </div>
                <div style="max-width: 200px; max-height: 150px;" class="fileinput-preview fileinput-exists thumbnail"> </div>
                <div>
                <span class="btn default btn-file">
                <span class="fileinput-new"> Select image </span>
                <span class="fileinput-exists"> Change </span>
                <input type="file" value="http://www.devmobilewash.com/api/images/cust_img/no_profile.jpg" name="image"> </span>
                <a data-dismiss="fileinput" class="btn default fileinput-exists" href="javascript:;"> Remove </a>
                </div>
                </div>
                </div>
                <div class="margin-top-10">
                <input type="hidden" value="hidden" name="hidden2">
                <input type="hidden" value="<?php echo $image; ?>" name="emptyimage">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <input type="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" name="submit" value="Submit">
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
        