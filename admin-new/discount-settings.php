<?php
include('header.php');
    if(!empty($_POST['hidden']))
    {
     
            
            $data = array('id'=> 1,'first_wash_discount'=> $_POST['first_wash_status'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=site/updatediscountsettings");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($result_code == "true"){
               
            $msg = 'Settings updated successfully';
            //die();
            }
            else
            {
                $msg = 'Error in updating settings. Please try again.';   
            }




    }
        
        $url = ROOT_URL.'/api/index.php?r=site/getdiscountsettings'; 
            $handle = curl_init($url);
            $data = '';
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id'=>1, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $discsettings = json_decode($result);
            
            
    
?>

<?php include('right-sidebar.php') ?>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Discount Settings</span>
                      <?php if(!empty($msg)) { if($msg == 'Settings updated successfully'){?> <p style="padding: 10px; background: green; color: #fff;"><?php echo $msg; ?></p> <?php } else{ ?>
                        <p style="padding: 10px; background: red; color: #fff;"><?php echo $msg; ?></p><?php }} ?>  
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
                                                <label class="control-label col-md-3">First Wash Discount</label>
                                                <div class="col-md-3">
                                                    <select class="form-control input-medium" name="first_wash_status">
                                                        <option value="enabled" <?php if($discsettings->discount_settings->first_wash_discount == 'enabled') echo 'selected'; ?>>Enabled</option>
                                                        <option value="disabled" <?php if($discsettings->discount_settings->first_wash_discount == 'disabled') echo 'selected'; ?>>Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3" style="display: <?php echo $add_company; ?>">
                                                <button type="submit" name="submit" class="btn blue">Save</button>
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

