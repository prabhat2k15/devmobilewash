<?php include('header.php') ?>

<!--begin-right-side-bar-->
<?php include('right-sidebar.php') ?>
<!--End-right-side-bar-->
<?php
if (isset($_POST['up_washer_feed'])) {

    if (isset($_POST['to_date']) && isset($_POST['from_date'])) {
        $from_date = $_POST['from_date'] . " " . $_POST['from_time'];
        $to_date = $_POST['to_date'] . " " . $_POST['to_time'];

        function convertString($date) {
            // convert date and time to seconds 
            $sec = strtotime($date);

            // convert seconds into a specific format 
            $date = date("Y-m-d H:i", $sec);

            // append seconds to the date and time 
            return $date = $date . ":00";
        }

        $from_date = convertString($from_date);
        $to_date = convertString($to_date);
    }
    if (isset($_FILES['image'])) {
        $filename = $_FILES['image']['tmp_name'];
        $handle = fopen($filename, "r");
        $data = fread($handle, filesize($filename));
        $image = base64_encode($data);
    }
    if (isset($_POST['removeImage'])) {
        $removeImage = $_POST['removeImage'];
    }

    $washerData = array("id" => $_POST['id'], 'from_date' => $from_date, 'to_date' => $to_date, 'image_link' => $_POST['image_link'], 'image' => $image, 'removeImage' => $removeImage, "title" => $_POST['title'], "message" => $_POST['message'], "feed" => $_POST['feed'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
    $handle_data = curl_init(ROOT_URL . "/api/index.php?r=washerFeed/UpdateFeedById");
    curl_setopt($handle_data, CURLOPT_POST, true);
    curl_setopt($handle_data, CURLOPT_POSTFIELDS, $washerData);
    curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle_data);
    curl_close($result);
    curl_close($handle_data);
    $updateResponse = json_decode($result);
}



$getFeedData = curl_init(ROOT_URL . "/api/index.php?r=washerFeed/GetFeedListAdmin");
curl_setopt($getFeedData, CURLOPT_POST, true);
curl_setopt($getFeedData, CURLOPT_POSTFIELDS, array('agent_id' => $_GET['id'], 'api_password' => AES256CBC_API_PASS, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($getFeedData, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($getFeedData);
$finalData = json_decode($result);
?>
<style>
    .after-upload-img-show-box{
        display:flex;
        justify-content:center;
        flex-direction:column;
        position:relative;
        padding:10px;
        max-width:200px;
        height:200px;
        width:100%;
        margin:0 auto;
        overflow:hidden;
        background: #f3f3f3;
        box-shadow:0px 0px 3px #ccc;
    }
    .after-upload-img-show-box a{
        position: absolute;
        right: 0;
        top: 0;
        width: 20px;
        height: 20px;
        background: #ff0000;
        text-align: center;
        border-radius: 50%;
        color: #fff;
    }
    .after-upload-img-show-box img{
        margin:0 auto;
    }
    .savebox{
        margin-top:77px;
        padding-left: 1em;
    }
    .no-notification-found{
        display:flex;
        width:100%;
        height:200px;
        flex-direction: column;
        justify-content:center;
        text-align:center;
        background: #f7f7f7;
    }
    .no-notification-found h4{
        font-weight:700;
    }
    .img-url-box{
        float:left;
        padding:10px;
        background:#ccc;
    }
    .emojionearea-editor{
        word-break: break-all;
    }
    .emojionearea-editor div:empty{
        display:none;
    }
    .emoji-picker-icon{
    right :20px;
}
</style>
<?php

function convertStringDate($date) {
    // convert date and time to seconds 
    $sec = strtotime($date);

    // convert seconds into a specific format 
    $date = date("Y-m-d", $sec);

    // append seconds to the date and time 
    return $date = $date;
}

function convertStringTime($date) {
    // convert date and time to seconds 
    $sec = strtotime($date);

    // convert seconds into a specific format 
    $date = date("h:i:s A", $sec);

    // append seconds to the date and time 
    return $date = $date;
}
?>
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
                            <span class="caption-subject bold" style="color: #000"> Washer Feed NOTIFICATIONS</span> 
                        </div>

                    </div>
                    <?php if ($updateResponse->status == 1 && isset($updateResponse->status)) { ?>
                        <div class="alert alert-success">
                            <?= $updateResponse->message ?>
                        </div>
                    <?php } ?>
                    <?php if ($updateResponse->status == 0 && isset($updateResponse->status)) { ?>
                        <div class="alert alert-success">
                            <?= $updateResponse->message ?>
                        </div>
                    <?php } ?>

                    <?php
                    if (count($finalData->data) > 0) {
                        foreach ($finalData->data as $key => $val) {
                            ?>

                            <div class="spacing"> 

                                <div class="portlet-body">

                                    <form id="washfeed_form_<?= $key + 1 ?>" method="post" enctype="multipart/form-data" >
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4">
                                                <label><strong>Notification Message # <?= $key + 1 ?></strong></label>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="time-box">
                                                            <span class="text_select_time">From </span>
                                                            <span class="time-input-wrap">
                                                                <div style="margin: 6px 0px;">
                                                                    <div class="cstm-arrw">
                                                                        <input   type="text" required="" value="<?= convertStringDate($val->from_date) ?>" name="from_date" class="time FromDate fromDate" id="fromDate" placeholder="Select Date">            
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="time-box">
                                                            <span class="text_select_time">To </span>
                                                            <span class="time-input-wrap">
                                                                <div style="margin: 6px 0px;">
                                                                    <div class="cstm-arrw">
                                                                        <input   type="text" required="" value="<?= convertStringDate($val->to_date) ?>" name="to_date" class="time ToDate toDate" id="ToDate" placeholder="Select Date">            
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="time-box">
                                                            <span class="text_select_time">Start Time </span>
                                                            <span class="time-input-wrap">
                                                                <div style="margin-top: 6px;">
                                                                    <div class="cstm-arrw">
                                                                        <input   type="text" name="from_time" required="" value="<?= convertStringTime($val->from_date); ?>" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="time-box">
                                                            <span class="text_select_time">End Time </span>
                                                            <span class="time-input-wrap">
                                                                <div style="margin-top: 6px;">
                                                                    <div class="cstm-arrw">
                                                                        <input   type="text" name="to_time" required="" value="<?= convertStringTime($val->to_date); ?>" class="time ui-timepicker-input SelectTime" id="setTimeExample" placeholder="Select Time">            
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>              
                                            </div>  

                                            <div class="col-xs-12 col-md-8">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-7">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-12">
                                                                <input type="text" name="title" value="<?= $val->title; ?>"  class="borderm form-control" placeholder="Write Title" data-emojiable="true">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:20px;margin-bottom:20px;">
                                                            <div class="col-xs-6 col-md-6 pad_left">
                                                                <div class="form-group upload-image-wrap">
                                                                    <label class="btn btn-secondary labelUpload">Upload Image</label> <input type="file" id="<?= $val->id ?>"  name="image" class="changeDefaultName photo_validation" onchange="readURL(this,<?= $val->id ?>)">
                                                                    <span class="disply-img-name img-name-box-<?= $val->id ?>" title=""></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($val->image) {
                                                            $dispaly = "block";
                                                        } else {
                                                            $dispaly = "none";
                                                        }
                                                        ?>
                                                        <div class="row">
                                                            <input type="hidden" value="<?= $val->image ?>" class="borderm form-control" />
                                                            <div class="col-xs-12 col-md-6 form-group" id="display-img-<?= $val->id ?>" style="display: <?= $dispaly ?>;" >
                                                                <div class="after-upload-img-show-box" id="hideImage-<?= $val->id; ?>">
                                                                    <a href="javascript:void(0);" class="removeImage" data-imageId="<?= $val->id; ?>" data-imageVal="<?= $val->image; ?>">&times;</a>

                                                                    <image id="big_image-<?= $val->id ?>"  class="img-responsive" src="<?= $val->image; ?>" />

                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-md-6 form-group">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <label>Image Link</label>
                                                                    </div>
                                                                    <div class="col-xs-12">
                                                                        <!-- <span class="img-url-box"><?= $val->image ?></span> -->
                                                                        <input type="text" class="form-control" name="image_link" value="<?= $val->image_link ?>">
                                                                    </div>
                                                                </div>
                                                                <!--                                                                <div class="row">
                                                                                                                                    <div class="col-xs-12">
                                                                                                                                        <label>Direct Link</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-xs-12">
                                                                                                                                     
                                                                                                                                        <input type="text" class="form-control" value="<?= $val->image ?>">
                                                                                                                                    </div>
                                                                                                                                </div>-->
                                                            </div>
                                                            <input type="hidden" name="removeImage" id="removeImageVal-<?= $val->id; ?>" value="" />
                                                            <input type="hidden" name="feed" value="<?= $val->id; ?>" />
                                                            <input type="hidden" name="id" value="<?= $val->id; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-7 washer-feed-box">
                                                        <textarea name="message" class="borderm form-control emoji" data-emojiable="true" rows="4" cols="50" placeholder="Write Message" required=""><?= $val->message; ?></textarea>
                                                    </div>
                                                    <div class="col-xs-12 col-md-2 savebox"> <button type="submit" name="up_washer_feed" class="btn btn-primary bold-txt"> SAVE</button> </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>


                                </div>
                            </div>
                            <hr>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="no-notification-found"><h4>No Notification  Found</h4></div>
                    <?php } ?>

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
    $(document).ready(function () {
        $('.SelectTime').timepicker({
            step: '15',
            timeFormat: 'h:i:s A',
        });
    });
    $(document).ready(function () {
        // $('.FromDate').datepicker({
        // date: new Date,
        /// format: 'yyyy-mm-dd',
        // autoHide: true,
        // onClose: function (e) {
        // $(".ToDate").datepicker('setStartDate', e.date);
        //startDate:Date,
        //}
        // });

        ///$('.ToDate').datepicker({
        // date: new Date,
        // format: 'yyyy-mm-dd',
        // autoHide: true,
        //endDate:Date,
        //});
        $('.FromDate').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            startDate: new Date()

        }).on('changeDate', function (e) {
            $('.ToDate').datepicker('setStartDate', e.date)
        });

        $('.ToDate').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (e) {
            $('.FromDate').datepicker('setEndDate', e.date)
        });
    });

    $(".photo_validation").change(function () {

        var val = $(this).val();
        var id = $(this).attr('id');
        $('#hideImage-' + id).show();
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
                $('#display-img-' + id).hide();
        }
    });

    function readURL(input, id) {
        $('#hideImage-' + id).show();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            $('#display-img-' + id).show();
            reader.onload = function (e) {
                $('#big_image-' + id).attr('src', e.target.result)
            };
            $('#big_image-' + id).next('span').css('display', 'none');
            // $('#big_image-' + id).prev('span').css('display', 'block');
            $('#big_image-' + id).nextAll('.remove-img').eq(0).css('display', 'block');

            reader.readAsDataURL(input.files[0]);
        }
    }


    $('.changeDefaultName').on('change', function (e) {
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
    });


</script>



<!-- END PAGE LEVEL SCRIPTS -->
<style>

    .page-content-wrapper .page-content{
        padding: 0 20px 10px !important;
    }
</style>

<?php include('footer.php') ?>