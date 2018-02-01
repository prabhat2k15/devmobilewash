<?php
include('header.php');
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

if(isset($_POST['submit'])){
     $data = array('platform'=> $_POST['platform'], 'status'=> $_POST['status'], 'description'=> $_POST['description'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=site/addbugentry");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $bugentry_response = $jsondata->response;
            $bugentry_result_code = $jsondata->result;
}
?>
<?php
    if($company_module_permission == 'no' || $checked_site_settings == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<style>
    #description{
    width: 350px; 
    height: 130px;    
    }
</style>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Report Bug</span><?php } ?>
                        
                        <?php if(isset($_POST['submit']) && $bugentry_result_code == 'false'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><?php echo $bugentry_response; ?></p>
<?php endif; ?>

              <?php if(isset($_POST['submit']) && $bugentry_result_code == 'true'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><?php echo $bugentry_response; ?></p>
<?php endif; ?>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light form-fit bordered">
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    <form action="" method="post" class="form-horizontal form-bordered">
                                        <div class="form-body">
                                           
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Platform<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control input-medium" name="platform" required>
                                                        <option value="">--Please Select--</option>
                                                        <option value="IOS">iOS</option>
                                                        <option value="ANDROID">Android</option>
                                                        <option value="WEB">Web</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Status<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control input-medium" name="status" required>
                                                        <option value="pending">Pending</option>
                                                        <option value="inreview">In-Review</option>
                                                        <option value="resolved">Resolved</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Description<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <textarea class="form-control" name="description" rows="3" col="20" id="description" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3" style="display: block">
                                                <button type="submit" name="submit" class="btn blue">Submit</button>
                                            </div>
                                            
                                        </div>
                                        </div>
                                    </form>
                                  <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php include('footer.php') ?>
            
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