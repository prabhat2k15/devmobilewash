<?php include('header.php') ?>
<?php
    if($company_module_permission == 'no' || $checked_notifications == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
<style type="text/css">
.user_id_div span, .user_id_div input{
    display: inline-block;
    /*vertical-align: top;*/
    padding: 0px !important;
}
.user_id_div{
    display: none;
}
</style>
<?php include('right-sidebar.php') ?>
<?php
$response = '';
$result_code = '';
if(isset($_POST['notify-form-submit'])){

     if(($_POST['receiver_type'] == 'single-client') || ($_POST['receiver_type'] == 'single-agent')){

     $handle = curl_init(ROOT_URL."/api/index.php?r=users/adminnotify");
    $data = array("msg"=>$_POST['notify_msg'], "receiver_type"=>$_POST['receiver_type'], 'user_id' => $_POST['user_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
     curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($handle);
    curl_close($handle);
    $jsondata = json_decode($result);
    $response = $jsondata->response;
    $result_code = $jsondata->result;
         
     }

    
    if(($_POST['receiver_type'] == 'all-clients') || ($_POST['receiver_type'] == 'all-agents'))
    {
        $handle = curl_init(ROOT_URL."/api/index.php?r=site/adminaddschedulenotify");
        $data = array("msg"=>$_POST['notify_msg'], "receiver_type"=>$_POST['receiver_type'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
   
    
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
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 18px !important;">Mobile Wash Notifications</span>
                        <p style="margin-top: 10px;">Push Notification Dashboard</p>
                        <?php if(isset($_POST['notify-form-submit']) && $result_code == 'true'): ?>
        <p class="success" style="background: #33A232; color: #fff; padding: 10px; box-sizing: border-box;"><i class="icon-check" style="margin-right: 10px; vertical-align: middle;"></i><?php if($_POST['receiver_type'] == 'clients') {echo "Notification delivery scheduled successfully";} else {echo "Notification sent successfully";} ?></p>
        <?php endif; ?>
        <?php if(isset($_POST['notify-form-submit']) && $result_code == 'false'): ?>
<p class="error" style="background: rgb(228, 58, 58);color: #fff;padding: 10px;box-sizing: border-box;"><i class="icon-close" style="margin-right: 10px; vertical-align: middle;"></i><?php echo $response; ?></p>
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
                                                <label class="control-label col-md-3">Receiver Type</label>
                                                <div class="col-md-3">
                                                    
                                                    <select class="form-control input-medium" name="receiver_type" id="receiver_type">
                                                       <option value="all-agents">All Washers</option>
                                                       <option value="all-clients">All Customers</option>
                                                       <option value="single-agent">Washer</option>
                                                       <option value="single-client">Customer</option>
                                                       <!--<option value="all" selected="">All</option>-->
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-6 user_id_div">
                                                <span class="control-label" id='selecterlabel'>ID #:</span>
                                                    <input type="text" class="form-control input-medium" name="user_id" >
                                                </div>
                                            </div>

                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3" style="display: <?php echo $add_company; ?>">
                                                <input type="submit" name="notify-form-submit" value="Send Notification" class="btn blue" />
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
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>
        <script src="./assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
$('#receiver_type').on('change', function(){
    var val = $(this).val();
    if((val == 'single-agent') || (val == 'single-client')){
        $('.user_id_div').show();
	$('.user_id_div input[name=user_id]').attr('required', 'required');
    }else{
        $('.user_id_div').hide();
	$('.user_id_div input[name=user_id]').removeAttr('required');
    }
});

</script>