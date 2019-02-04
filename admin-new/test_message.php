<?php
include('header.php');
if (!empty($_POST['hidden'])) {
    $to = $_POST['to'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $media = $_POST['media'];
    $status = $_POST['status'];

    $to = "Test Message";
    $phone = $phone;
    $message = $message;
    $media = $media;
    // COLLECT POST VALUE //

    $data = array('status' => $status, 'to' => $to, 'phone' => $phone, 'message' => $message, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);


    // END COLLECT POST VALUE //

    $handle = curl_init(ROOT_URL . "/api/index.php?r=twilio/messges");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle);
//    print_r($result);
//    die;
    curl_close($handle);
    $jsondata = json_decode($result);
    $response = $jsondata->response;
    $result_code = $jsondata->result;

    //exit;
    if ($response == "Message successfully store" && $result_code == "true") {

        $msg = 'Successfully Saved';
        ?>
        <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/messagess.php"</script>
        <?php
        //die();
    } else {
        $msg = 'Something Wrong';
    }
}


if (!empty($_POST['updateTestNumber'])) {
    $phone = $_POST['phone'];
    $id = $_POST['id'];
    $phone = $phone;
    // COLLECT POST VALUE //

    $data = array('id' => $id, 'phone' => $phone, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);


    // END COLLECT POST VALUE //

    $handle = curl_init(ROOT_URL . "/api/index.php?r=twilio/UpdateTestMessges");
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle);

    curl_close($handle);
    $jsondata = json_decode($result);
    $response = $jsondata->response;
    $result_code = $jsondata->result;

    //exit;
    if ($response == "update successfully" && $result_code == "true") {

        $msg = 'update successfully';
        ?>
        <!--        <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/messagess.php"</script>-->
        <?php
        //die();
    } else {
        $msg = 'Something Wrong';
    }
}
?>

<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#example1').dataTable({
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
        });

    });
</script>
<?php include('right-sidebar.php') ?>
<?php
$url = ROOT_URL . '/api/index.php?r=twilio/GetTestNubmers';
$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondataTestNumber = json_decode($result);


$url = ROOT_URL . '/api/index.php?r=twilio/GetLatestTestMessage';
$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondataTestMessage = json_decode($result);
if ($jsondataTestMessage->TestMessage->message) {
    $latestMessage = $jsondataTestMessage->TestMessage->message;
} else {
    $latestMessage = " ";
}
?>
<style>
    #phone{
        display: none;
    }
    #message{
        display: none;
    }
    #media{
        display: none;
    }
    #save{
        display: none;
    }
    .emoji-wysiwyg-editor {
        min-height: 130px !important;
    }
    .emoji-picker-icon {
        right: 23px;
        top: 21px;
    }
    .emoji-menu{
        top: 40px;
        right: 16px;
    }
    .icon-bell:before,
    .icon-grid:before{
        opacity:0;
    }

    .edit-btn{
        top: 17px;
        position: relative;
    }
</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="caption">
            <i class="icon-settings"></i>
            <?php if (!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else { ?>
                <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Test Message</span><?php } ?>

        </div>
        <div class="clear">&nbsp;</div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet light form-fit bordered">
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="" method="post"  class="form-horizontal form-bordered" >
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Test Number<span style="color: red;">*</span></label>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" id="phoneNumber" name="phone" required="" minlength="12" maxlength="12" placeholder="Add number">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Phone<span style="color: red;">*</span></label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="testNumbers">
                                            <option >Select Phone</option>
                                            <?php foreach ($jsondataTestNumber->testNumbers as $val) { ?>
                                                <option data-id="<?= $val->id ?>" value="<?= $val->phone ?>"><?= $val->phone ?></option>
                                            <?php } ?>
                                        </select>
<!--                                        <input type="text" required name="phone" title="Phone number with 7-9 and remaing 9 digit with 0-9" class="form-control form-control-inline input-medium">-->
                                    </div>
                                    <!--                                    <button type="button" id="Modal" class="btn btn-info btn-sm edit-btn" data-toggle="modal" data-target="#myModal">Edit</button>-->
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Message<span style="color: red;">*</span></label>
                                    <div class="col-md-5">
                                        <textarea class="form-control emoji" data-emojiable="true" name="message" rows="3" required><?= $latestMessage ?></textarea>
                                        <input type="hidden" name="status" value="2">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-3">&nbsp;</div>
                                    <div class="col-md-3">
                                        <button type="submit" name="submit" class="btn blue">Save</button>
                                    </div>
                                    <input type="hidden" name="hidden" value="hidden">
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

<div class="container">
    <!-- Trigger the modal with a button -->


    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post"  class="form-horizontal form-bordered" >
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Update Number<span style="color: red;">*</span></label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="phoneUpdate" name="phone" required="" minlength="10" maxlength="10" placeholder="Add number">

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-3">
                                    <button type="submit" name="submit" class="btn blue">Save</button>
                                </div>
                                <input type="hidden" name="id" id="TestNumberId">
                                <input type="hidden" name="updateTestNumber" value="updateTestNumber">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- END CONTENT -->

<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    function getval(sel) {
        var val = sel.value;
        if ((val == 'custom')) {
            $('#phone').show();
            $('#phone input[name=phone]').attr('required', 'required');
            $('#message').show();
            $('#media').show();
            $('#save').show();
        }

        if ((val == 'all_washers')) {
            $('#phone').hide();
            $('#phone input[name=phone]').removeAttr('required');
            $('#message').show();
            $('#media').show();
            $('#save').show();
        }
    }

    $('#testNumbers').on('change', function () {
        console.log($(this).val());
        if ($(this).val() == "Select Phone") {
            $('#data-target').addClass("disabled");
            $('#data-target').attr('data-target', ' ');
        } else {
            $('#Modal').removeClass("disabled");
            $('#data-target').attr('data-target', '#myModal');
        }
        $('#phoneNumber').val($(this).val());
        $('#TestNumberId').val($(this).find(':selected').attr('data-id'));
    });

    $('#Modal').on('click', function () {
        //$('#testNumbers').val();
        $('#phoneUpdate').val($('#testNumbers').val());
    });


</script>