<?php
include('header.php');

if(isset($_POST['inactive-6th-day_submit'])){
    $picname = '';
     if(!empty($_FILES['inactive-6th-day_email_image']['tmp_name']))
            {
                $pic_type = pathinfo($_FILES['inactive-6th-day_email_image']['name'], PATHINFO_EXTENSION);
                $picname = "inactive-6th-day_email_image.".$pic_type;
                move_uploaded_file($_FILES['inactive-6th-day_email_image']['tmp_name'], ROOT_WEBFOLDER.'/public_html/admin-new/images/cust-spec-notify-img/'.$picname);
            }
            
    $data = array('notify_cat'=> 'inactive-6th-day', 'notify_trigger_time'=> $_POST['inactive-6th-day_time']." ".$_POST['inactive-6th-day_timeformat'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4],
                  'sms_text' => $_POST['inactive-6th-day_sms_text'], 'notify_text' => $_POST['inactive-6th-day_notify_text'], 'email_img_url' => $picname);
                      
            $handle = curl_init(ROOT_URL."/api/index.php?r=customers/updatecustomerspecnotifiations");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $notify_api_response = $jsondata->response;
            $notify_api_result_code = $jsondata->result; 
}

if(isset($_POST['inactive-11th-day_submit'])){
    $picname = '';
     if(!empty($_FILES['inactive-11th-day_email_image']['tmp_name']))
            {
                $pic_type = pathinfo($_FILES['inactive-11th-day_email_image']['name'], PATHINFO_EXTENSION);
                $picname = "inactive-11th-day_email_image.".$pic_type;
                move_uploaded_file($_FILES['inactive-11th-day_email_image']['tmp_name'], ROOT_WEBFOLDER.'/public_html/admin-new/images/cust-spec-notify-img/'.$picname);
            }
            
    $data = array('notify_cat'=> 'inactive-11th-day', 'notify_trigger_time'=> $_POST['inactive-11th-day_time']." ".$_POST['inactive-11th-day_timeformat'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4],
                  'sms_text' => $_POST['inactive-11th-day_sms_text'], 'notify_text' => $_POST['inactive-11th-day_notify_text'], 'email_img_url' => $picname);
                      
            $handle = curl_init(ROOT_URL."/api/index.php?r=customers/updatecustomerspecnotifiations");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $notify_api_response = $jsondata->response;
            $notify_api_result_code = $jsondata->result; 
}

if(isset($_POST['inactive-31st-day_submit'])){
    $picname = '';
     if(!empty($_FILES['inactive-31st-day_email_image']['tmp_name']))
            {
                $pic_type = pathinfo($_FILES['inactive-31st-day_email_image']['name'], PATHINFO_EXTENSION);
                $picname = "inactive-31st-day_email_image.".$pic_type;
                move_uploaded_file($_FILES['inactive-31st-day_email_image']['tmp_name'], ROOT_WEBFOLDER.'/public_html/admin-new/images/cust-spec-notify-img/'.$picname);
            }
            
    $data = array('notify_cat'=> 'inactive-31st-day', 'notify_trigger_time'=> $_POST['inactive-31st-day_time']." ".$_POST['inactive-31st-day_timeformat'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4],
                  'sms_text' => $_POST['inactive-31st-day_sms_text'], 'notify_text' => $_POST['inactive-31st-day_notify_text'], 'email_img_url' => $picname);
                      
            $handle = curl_init(ROOT_URL."/api/index.php?r=customers/updatecustomerspecnotifiations");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $notify_api_response = $jsondata->response;
            $notify_api_result_code = $jsondata->result; 
}

 $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
                      
            $handle = curl_init(ROOT_URL."/api/index.php?r=customers/getcustomerspecnotifiations");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $notifyobj_jsondata = json_decode($result, true);
            
 ?>

<style>
    .customcsv{
        opacity: 0;
    }

.emoji-wysiwyg-editor {
        min-height: 130px !important;
    }
    .emoji-picker-icon {
    right: 7px;
    top: 5px;
}
    .emoji-menu{
        top: 30px;
        right: 12px;
    }
</style>




<?php include('right-sidebar.php') ?>

<style>
    .label-busy {
        background-color: #FF8C00 !important;
    }
    .label-online {
        background-color: #16CE0C !important;
    }
    .label-offline {
        background-color: #FF0202 !important;
    }
    .pagination ul {
        display: inline-block;
        margin-bottom: 0;
        margin-left: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .pagination li {
        display: inline;
    }
    li {
        line-height: 20px;
    }
    user agent stylesheetli {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .pagination li:first-child a, .pagination li:first-child span {
        border-left-width: 1px;
        -webkit-border-radius: 3px 0 0 3px;
        -moz-border-radius: 3px 0 0 3px;
        border-radius: 3px 0 0 3px;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    a {
        color: #08c;
        text-decoration: none;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    .pagination a, .pagination span {
        float: left;
        padding: 0 14px;
        line-height: 38px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-left-width: 0;
    }
    .pagination{
        width: 100%;
    }
    .portlet-body form {
        padding-bottom: 10px;
    }

    .cust-search-box{
        margin-bottom: 20px;
        display: none;
    }

    .cust-search-box h2{
        font-size: 26px;
        font-weight: 400;
    }

    .custom-pagination{
        text-align: center;
        margin: 10px;
    }

    .custom-pagination a{
        padding: 5px 10px;
        background: #337ab7;
        color: #fff;
        margin-right: 2px; 
    }

    .custom-pagination a:hover{
        text-decoration: none;
    }
</style>


<style>
    .current_tab{
        background-color: #5407e2 !important;
        border-top: 5px solid #5407e2 !important;
        height: 90px !important;
        padding: 13px 0 0 10px !important;
        cursor: pointer !important;
    }
    .Not_msg{
        float:left;
    }
    .time-box{
        float:left; 
    }
    .textBox{
        float: left;padding:1em;
    }
    .textSave{
        float: left;padding-top: 3em;padding-left:3em;
    }
    .text_select_time{
        font-size: 14px;
        font-weight: 600;
    }
    .not_msg{
        float: left;padding-left: 1em;   font-size: 14px;
        font-weight: 600;
         padding-left: 0;
    }
    .textSave{
        font-weight: 600;
    }
    .text_msg{
        float: left;
        margin-top: 8px;
        /*        margin-left: -10em;*/
        margin-bottom: 30px;
    }
    .spacing{
        margin-top:1em;
        margin-bottom:1em;
    }
    .ui-timepicker-wrapper{
        max-width: 192px;
        width: 100%;
    }
    .bold-txt{
        font-weight:600;

    }
    .SelectTime{
        border-radius:3px;
    }
    .savebox{
        margin-top: 2em;
        padding-left: 4em;
    }
    .my-text-align{
        margin-left: 5em;
    }
    .time-input-wrap{
        position:relative;
    }
    .time-input-wrap .time{
        position:relative;
        width:100%;
        padding:6px;
        border:1px solid #ccc;
    }
    .time-input-wrap .cstm-arrw{
        position:relative;
        max-width:192px;
        width:100%;
    }
    .time-input-wrap .cstm-arrw:after{
        content: '';
        position: absolute;
        top:13px;
        right:7px;
        border-top: 8px solid #000;
        border-right: 5px solid transparent;
        border-left: 5px solid transparent;
    }
    
     .portlet-body .select-box-cstm, .portlet-body .select-box-cstm select{
            width: 80px;
           }
           
           .portlet-body .upload-image-wrap{
            max-width: 100%;
           }
           
             .portlet-body .preview-img{
            width: 100%;
    max-width: 300px;
    margin: 0 auto;
    display: block;
           }
           
           .portlet-body .upload-image-wrap .disply-img-name{
            margin-bottom: 20px;
           }
</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->



        <div class="clear">&nbsp;</div>

        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">                    
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> INACTIVE CUSTOMER NOTIFICATIONS</span> 
                        </div>

                    </div>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 6<sup>th</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">
                            <form id="inactive_form_customer_1" action="" method="post" enctype="multipart/form-data">
                                <div class="row my-text-align">
                                    <div class="col-xs-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                         <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input name="inactive-6th-day_time"  type="text" required="" value="<?php echo $notifyobj_jsondata['spec_notifications']['inactive-6th-day']['notify_trigger_time']; ?>" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control" name="inactive-6th-day_timeformat">
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-6th-day']['notify_trigger_time'], 'AM') !== false) echo "selected"; ?>>AM</option>
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-6th-day']['notify_trigger_time'], 'PM') !== false) echo "selected"; ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-5">
                                        <div> <span class="not_msg">SMS </span> <span class="text_msg emoji_div"> <textarea name="inactive-6th-day_sms_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-6th-day']['sms_text']; ?></textarea> </span>
                                        </div>
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="inactive-6th-day_notify_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-6th-day']['notify_text']; ?></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="inactive-6th-day_email_image" name="inactive-6th-day_email_image" class="photo_validation">
                                                    <span id="inactive-6th-day_email_image_filename" class="disply-img-name" title=""></span>
                                                    <?php if($notifyobj_jsondata['spec_notifications']['inactive-6th-day']['email_image_url']): ?>
                                                    <img src="<?php echo ROOT_URL.'/admin-new/images/cust-spec-notify-img/'.$notifyobj_jsondata['spec_notifications']['inactive-6th-day']['email_image_url']; ?>" id="inactive-6th-day_email_image_preview" class='preview-img' />
                                                    
                                                    <?php else: ?>
                                                    <img src="" id="inactive-6th-day_email_image_preview" class='preview-img' />
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 savebox"> <button type="submit" name="inactive-6th-day_submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>
                           

                        </div>
                    </div>
                    <hr>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 11<sup>th</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">

                            <form id="inactive_form_customer_2" action="" method="post" enctype="multipart/form-data">
                                <div class="row my-text-align">
                                    <div class="col-xs-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                         <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input name="inactive-11th-day_time"  type="text" required="" value="<?php echo $notifyobj_jsondata['spec_notifications']['inactive-11th-day']['notify_trigger_time']; ?>" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control" name="inactive-11th-day_timeformat">
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-11th-day']['notify_trigger_time'], 'AM') !== false) echo "selected"; ?>>AM</option>
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-11th-day']['notify_trigger_time'], 'PM') !== false) echo "selected"; ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-5">
                                        <div> <span class="not_msg">SMS </span> <span class="text_msg emoji_div"> <textarea name="inactive-11th-day_sms_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-11th-day']['sms_text']; ?></textarea> </span>
                                        </div>
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="inactive-11th-day_notify_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-11th-day']['notify_text']; ?></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="inactive-11th-day_email_image" name="inactive-11th-day_email_image" class="photo_validation">
                                                    <span id="inactive-11th-day_email_image_filename" class="disply-img-name" title=""></span>
                                                    <?php if($notifyobj_jsondata['spec_notifications']['inactive-11th-day']['email_image_url']): ?>
                                                    <img src="<?php echo ROOT_URL.'/admin-new/images/cust-spec-notify-img/'.$notifyobj_jsondata['spec_notifications']['inactive-11th-day']['email_image_url']; ?>" id="inactive-11th-day_email_image_preview" class='preview-img' />
                                                    
                                                    <?php else: ?>
                                                    <img src="" id="inactive-11th-day_email_image_preview" class='preview-img' />
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 savebox"> <button type="submit" name="inactive-11th-day_submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>


                        </div>
                    </div>         
                    <hr>
                    <div class="spacing"> 
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject bold" style="color: #000"> 31<sup>st</sup> Day Notifications</span> 
                        </div>
                        <div class="portlet-body">
                            <form id="inactive_form_customer_3" action="" method="post" enctype="multipart/form-data">
                                <div class="row my-text-align">
                                    <div class="col-xs-12 col-md-4">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="time-box">
                                                    <span class="text_select_time">Select Time </span>
                                                    <span class="time-input-wrap">
                                                         <div style="margin-top: 6px;">
                                                            <div class="cstm-arrw">
                                                                <input name="inactive-31st-day_time"  type="text" required="" value="<?php echo $notifyobj_jsondata['spec_notifications']['inactive-31st-day']['notify_trigger_time']; ?>" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Select Time Format</label>
                                                    <div class="select-box-cstm">
                                                        <select class="form-control" name="inactive-31st-day_timeformat">
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-31st-day']['notify_trigger_time'], 'AM') !== false) echo "selected"; ?>>AM</option>
                                                            <option <?php if (strpos($notifyobj_jsondata['spec_notifications']['inactive-31st-day']['notify_trigger_time'], 'PM') !== false) echo "selected"; ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-5">
                                        <div> <span class="not_msg">SMS </span> <span class="text_msg emoji_div"> <textarea name="inactive-31st-day_sms_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-31st-day']['sms_text']; ?></textarea> </span>
                                        </div>
                                        <div> <span class="not_msg">Notification Message </span> <span class="text_msg emoji_div"> <textarea name="inactive-31st-day_notify_text" class="borderm form-control emoji" rows="4" cols="50" placeholder="Write Message" data-emojiable="true"><?php echo $notifyobj_jsondata['spec_notifications']['inactive-31st-day']['notify_text']; ?></textarea> </span>
                                        </div>
                                        <div>
                                                <div class="form-group upload-image-wrap">
                                                    <label class="btn btn-secondary labelUpload">Upload Email Image</label> <input type="file" id="inactive-31st-day_email_image" name="inactive-31st-day_email_image" class="photo_validation">
                                                    <span id="inactive-31st-day_email_image_filename" class="disply-img-name" title=""></span>
                                                    <?php if($notifyobj_jsondata['spec_notifications']['inactive-31st-day']['email_image_url']): ?>
                                                    <img src="<?php echo ROOT_URL.'/admin-new/images/cust-spec-notify-img/'.$notifyobj_jsondata['spec_notifications']['inactive-31st-day']['email_image_url']; ?>" id="inactive-31st-day_email_image_preview" class='preview-img' />
                                                    
                                                    <?php else: ?>
                                                    <img src="" id="inactive-31st-day_email_image_preview" class='preview-img' />
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 savebox"> <button type="submit" name="inactive-31st-day_submit" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                </div>
                            </form>


                        </div>
                    </div>

                    <!-- body end-->
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
                <div class="clear"></div>

                <div class="clear"></div>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<script> 
    $(document).ready(function(){
    $('.SelectTime').timepicker({
        step: '60',
        timeFormat: 'h:i',
        'minTime': '01:00',
        'maxTime': '12:00',
    });
    
     $(".photo_validation").change(function () {

        var val = $(this).val();
        var id = $(this).attr('id');
        $('#' + id+"_preview").show();
readURL(this);
        switch (val.substring(val.lastIndexOf('.') + 1).toLowerCase()) {
            case 'gif':
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'PNG':
            case 'JPEG 2000':
            case 'BMP':

                break;
            default:
                $(this).val('');
                // error message here
                alert("Image upload format valid format");
                break;
                $('#' + id+"_preview").hide();
        }
    });

function readURL(input) {
    var id = $(input).attr('id');
    
    if (input.files && input.files[0]) {
         var fileName = input.files[0].name;
        var reader = new FileReader();

        reader.onload = function (e) {
            
            $('#' + id+"_preview").attr('src', e.target.result);
     
        $('#' + id+"_filename").html(fileName);
        
        }

        reader.readAsDataURL(input.files[0]);
    }
}

    /*$('.changeDefaultName').on('change', function (e) {
        var id = $(this).attr('id');
        $('#hideImage-' + id).show();
        var fileName = e.target.files[0].name;
        $('.img-name-box-' + id).html(fileName);
        $('.img-name-box-' + id).attr('title', fileName);
    });
    
    $('.removeImage').on('click', function (e) {
        var removeImageVal = $(this).attr('data-imageVal');
        var removeImageValId = $(this).attr('data-imageId');
        $('.img-name-box-' + removeImageValId).attr('title', '');
        $('.img-name-box-' + removeImageValId).html('');
        $('#' + removeImageValId).val('');
        $('#hideImage-' + removeImageValId).hide();
        $('#removeImageVal-' + removeImageValId).val(removeImageVal);
    });*/

     
    });
</script>



<!-- END PAGE LEVEL SCRIPTS -->
<style>

    .page-content-wrapper .page-content{
        padding: 0 20px 10px !important;
    }
</style>

<?php include('footer.php') ?>