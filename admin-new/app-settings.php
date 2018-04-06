<?php
include('header.php');
    if(isset($_POST['app_settings_submit']))
    {


            $data = array('ios_app_version_check'=> $_POST['ios_app_version_check'], 'ios_app_version'=> $_POST['ios_app_version'],'ios_app_link'=> $_POST['ios_app_link'],'ios_order_wait_time'=> $_POST['ios_order_wait_time'],'ios_order_rotate_time'=> $_POST['ios_order_rotate_time'],'ios_washer_search_radius'=> $_POST['ios_washer_search_radius'], 'ios_wash_now_fee'=> $_POST['ios_wash_now_fee'], 'ios_wash_later_fee'=> $_POST['ios_wash_later_fee'],'android_app_version_check'=> $_POST['android_app_version_check'], 'android_app_version'=> $_POST['android_app_version'], 'android_app_link'=> $_POST['android_app_link'], 'android_order_wait_time'=> $_POST['android_order_wait_time'],'android_order_rotate_time'=> $_POST['android_order_rotate_time'], 'android_washer_search_radius'=> $_POST['android_washer_search_radius'], 'android_wash_now_fee'=> $_POST['android_wash_now_fee'], 'android_wash_later_fee'=> $_POST['android_wash_later_fee'],'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

            // END COLLECT POST VALUE //

            $handle = curl_init(ROOT_URL."/api/index.php?r=users/updateappsettingsadmin");
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

        $url = ROOT_URL.'/api/index.php?r=users/getappsettings';
            $handle = curl_init($url);
            $data = '';
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $appsettings = json_decode($result);



?>
<?php
    if($company_module_permission == 'no' || $checked_site_settings == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<?php include('right-sidebar.php') ?>
<style>
.portlet table{
    border: 1px solid #efefef;
    border-collapse: collapse;
}

.portlet table th, .portlet table td{
   border: 1px solid #efefef;
   padding: 10px;
}

.portlet table .form-control{
    display: inline-block;
    width: auto;
}

.portlet table .form-control.small{
    width: 68px;
}

</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>

                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">App Settings</span>
                      <?php if(!empty($msg)) { if($msg == 'Settings updated successfully'){?> <p style="padding: 10px; background: green; color: #fff;"><?php echo $msg; ?></p> <?php } else{ ?>
                        <p style="padding: 10px; background: red; color: #fff;"><?php echo $msg; ?></p><?php }} ?>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light form-fit bordered" style="padding: 20px; overflow: auto; box-sizing: border-box;">
                                 <form action="" method="post" class="form-horizontal">
                            <table>
                            <tr>
                            <th>App Type</th>
                            <th>Version Check</th>
                            <th>Latest App Version</th>
                            <th>Latest App Link</th>
                            <th>Customer On-Demand Wait Time</th>
                            <th>Max Order Rotate Time</th>
                            <th>Washer Search Radius</th>
                            <th>Wash Now Fee</th>
                            <th>Wash Later Fee</th>
                            </tr>
                            <tr>
                            <td>
                               iOS
                            </td>
                            <td>
                                <select id="ios_app_version_check" name="ios_app_version_check" class="form-control"><option value="on" <?php if($appsettings->ios_app_version_check == 'on') echo "selected"; ?>>ON</option><option value="off" <?php if($appsettings->ios_app_version_check == 'off') echo "selected"; ?>>OFF</option></select>
                            </td>
                            <td>
                               <input type="text" id="ios_app_version" name="ios_app_version" class="form-control small" value="<?php echo $appsettings->ios_app_version; ?>" />
                            </td>
                            <td>
                               <input type="text" id="ios_app_link" name="ios_app_link" class="form-control" value="<?php echo $appsettings->ios_app_link; ?>" />
                            </td>
                            <td>
                                <input type="text" id="ios_order_wait_time" name="ios_order_wait_time" class="form-control small" value="<?php echo $appsettings->ios_cust_ondemand_wait_time; ?>" /> mins
                                
                            </td>
                            <td>
                                <input type="text" id="ios_order_rotate_time" name="ios_order_rotate_time" class="form-control small" value="<?php echo $appsettings->ios_max_order_rotate_time; ?>" /> mins
                                
                            </td>
                             <td>
                                <input type="text" id="ios_washer_search_radius" name="ios_washer_search_radius" class="form-control small" value="<?php echo $appsettings->ios_washer_search_radius; ?>" /> miles
                                
                            </td>
                             <td>
                                <span>$</span><input type="text" id="ios_wash_now_fee" name="ios_wash_now_fee" class="form-control small" value="<?php echo $appsettings->ios_wash_now_fee; ?>" />
                                
                            </td>
                             <td>
                                <span>$</span><input type="text" id="ios_wash_later_fee" name="ios_wash_later_fee" class="form-control small" value="<?php echo $appsettings->ios_wash_later_fee; ?>" />
                                
                            </td>
                            </tr>
                            <tr>
                            <td>
                               Android
                            </td>
                            <td><select id="android_app_version_check" name="android_app_version_check" class="form-control"><option value="on" <?php if($appsettings->android_app_version_check == 'on') echo "selected"; ?>>ON</option><option value="off" <?php if($appsettings->android_app_version_check == 'off') echo "selected"; ?>>OFF</option></select></td>
                            <td>
                               <input type="text" id="android_app_version" name="android_app_version" class="form-control small" value="<?php echo $appsettings->android_app_version; ?>" />
                            </td>
                            <td>
                               <input type="text" id="android_app_link" name="android_app_link" class="form-control" value="<?php echo $appsettings->android_app_link; ?>" />
                            </td>
                            <td>
                                <input type="text" id="android_order_wait_time" name="android_order_wait_time" class="form-control small" value="<?php echo $appsettings->android_cust_ondemand_wait_time; ?>" /> mins
                                
                            </td>
                            <td>
                                <input type="text" id="android_order_rotate_time" name="android_order_rotate_time" class="form-control small" value="<?php echo $appsettings->android_max_order_rotate_time; ?>" /> mins
                                
                            </td>
                             <td>
                                <input type="text" id="android_washer_search_radius" name="android_washer_search_radius" class="form-control small" value="<?php echo $appsettings->android_washer_search_radius; ?>" /> miles
                                
                            </td>
                             <td>
                                <span>$</span><input type="text" id="android_wash_now_fee" name="android_wash_now_fee" class="form-control small" value="<?php echo $appsettings->android_wash_now_fee; ?>" />
                                
                            </td>
                             <td>
                                <span>$</span><input type="text" id="android_wash_later_fee" name="android_wash_later_fee" class="form-control small" value="<?php echo $appsettings->android_wash_later_fee; ?>" />
                                
                            </td>
                            </tr>
                            
                            
                            </table>
                            <div style="display: none;"><p><strong>SMS Content</strong></p>
                            <textarea name="sms_content" id="sms_content" style="width: 400px; height: 150px;"></textarea></div>
                             <p><button type="submit" name="app_settings_submit" class="btn blue">Save</button></p>
                            </form>
                             
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
