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
?>
<?php
if(isset($_POST['add-car-regular-submit'])){
$vehdata = array("make"=>$_POST['regular-make'], "model"=>$_POST['regular-model'], "type"=>$_POST['regular-type'], "category"=>$_POST['regular-cat'], "vehicle_build"=>'regular', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=vehicles/addvehicle");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$vehadddata = json_decode($result);
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
<link rel="stylesheet" href="css/fontawesome-stars.css">
        <!-- BEGIN THEME LAYOUT STYLES -->

<?php include('right-sidebar.php') ?>

<?php

            $url = ROOT_URL.'/api/index.php?r=agents/prewasherdetails';
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
.reg-loading{
    display: none;
}
.classic-loading{
    display: none;
}
#regular-packlist{
    display: none;
}
#classic-packlist{
    display: none;
}

form .control-label{
   margin-top: 15px;
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Add Review</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                   
                                                    <form action="" id="add-review-form" method="post" enctype="multipart/form-data" style="width: 50%;">
                                                     <div class="form-group">
                                                                <label class="control-label" style="margin-top: 0;">Customer Name</label>
                                                              <input type="text" name="custname" id="custname" class="form-control" required />
                                                            </div>
                                                             <div class="form-group">
                                                                <label class="control-label">Customer Location</label>
                                                              <input type="text" name="custloc" id="custloc" class="form-control" required />
                                                            </div>
							      <div class="form-group">
                                                                <label class="control-label">Customer Car</label>
                                                              <input type="text" name="custcar" id="custcar" class="form-control" required />
                                                            </div>
							      <div class="form-group">
                                                                <label class="control-label">Wash Package</label>
                                                              <select name="custpack" id="custpack" class="form-control" required>
                                                                    <option value="Express">Express</option>
                                                                    <option value="Deluxe">Deluxe</option>
                                                                    <option value="Premium">Premium</option>
                                                                </select>
                                                            </div>
							      <div class="form-group">
																<label class="control-label">Customer Photo</label>
																 <input type="file" class="form-control" name="custphoto" id="custphoto" required>
																 	
																
															</div>
                                                        <div class="form-group">
                                                                <label class="control-label">Customer Rating</label>
                                                              <select name="custrating" id="custrating" class="form-control" required>
                                                                   <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5" selected>5</option>
                                                                </select>
                                                            </div>
						       <div class="form-group">
                                                                <label class="control-label">Customer Review</label>
                                                              <textarea cols="10" class="form-control" rows="5" name="custreview" id="custreview" required></textarea>
                                                            </div>
                                                           
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" id="regular-submit" value="Submit" name="add-review-submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                    </form>

                                                            <div class="clear" style="height: 10px;">&nbsp;</div>

                                                
                                                

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
<script src="js/jquery.barrating.min.js"></script>
<script type="text/javascript">
   $(function() {
      $('#custrating').barrating({
        theme: 'fontawesome-stars'
      });
   });
</script>
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


