<?php include('header.php') ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <style>
        .schedule_field{
            display: none;
        }
        </style>
<?php include('right-sidebar.php') ?>
<?php
$response = '';
$result_code = '';
if(isset($_POST['notify-form-submit'])){

if((isset($_POST['schedule_notify'])) && ($_POST['schedule_notify'] == 1)){

 $handle = curl_init(ROOT_URL."/api/index.php?r=site/adminaddschedulenotify");
		$data = array("msg"=>$_POST['notify_msg'], "receiver_type"=>'customer', 'schedule_date' => $_POST['notify_schedule_date'], 'schedule_time' => $_POST['notify_schedule_time'], 'receiver_ids' => '', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
}
else{
 $handle = curl_init(ROOT_URL."/api/index.php?r=users/adminnotify");
		$data = array("msg"=>$_POST['notify_msg'], "receiver_type"=>'clients', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
}


}
?>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 18px !important;">Customer Push Notifications</span>
                        <?php if(isset($_POST['notify-form-submit']) && $result_code == 'true'): ?>
        <?php if($response == 'schedule notification added'): ?>
        <p class="success" style="background: #33A232; color: #fff; padding: 10px; box-sizing: border-box;"><i class="icon-check" style="margin-right: 10px; vertical-align: middle;"></i>Notification scheduled successfully</p>
        <?php else: ?>
        <p class="success" style="background: #33A232; color: #fff; padding: 10px; box-sizing: border-box;"><i class="icon-check" style="margin-right: 10px; vertical-align: middle;"></i>Notification sent successfully</p>
        <?php endif; ?>
        <?php endif; ?>
        <?php if(isset($_POST['notify-form-submit']) && $result_code == 'false'): ?>
<p class="error" style="background: rgb(228, 58, 58);color: #fff;padding: 10px;box-sizing: border-box;"><i class="icon-close" style="margin-right: 10px; vertical-align: middle;"></i>Error in sending notification. Please try again</p>
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
                                                <label class="control-label col-md-3">Notification Message</label>
                                                <div class="col-md-3">
                                                    <textarea class="form-control" name="notify_msg" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Schedule Notification</label>
                                                <div class="col-md-3">
                                                    <input type="checkbox" id="schedule_notify" name="schedule_notify" value="1" />
                                                </div>
                                            </div>
                                            <div class="form-group schedule_field">
                                                <label class="control-label col-md-3">Schedule Date</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="notify_schedule_date" id="notify_schedule_date" class="form-control date-picker" value="<?php echo date('Y-m-d'); ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group schedule_field">
                                                <label class="control-label col-md-3">Schedule Time</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="notify_schedule_time" id="notify_schedule_time" class="form-control timepicker timepicker-default" value="" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3" style="display: <?php echo $add_company; ?>">
                                                <input type="submit" id="notify-form-submit" name="notify-form-submit" value="Send Notification" class="btn blue" />
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
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script>
            $(function(){
               $("#schedule_notify").change(function() {
                    if(this.checked) {
                       $(".schedule_field").show();
                       $("#notify-form-submit").val('Schedule Notification');
                    }
                    else{
                      $(".schedule_field").hide();
                      $("#notify-form-submit").val('Send Notification');
                    }
                });
            });
            </script>
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
        <script src="./assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->