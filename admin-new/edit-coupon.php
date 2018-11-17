<?php
include('header.php');
$response = '';
$result_code = '';
$couponid = $_GET['couponid'];
if(isset($_POST['updatecoupon-form-submit'])){

$handle = curl_init(ROOT_URL."/api/index.php?r=coupons/editcoupon");
        $data = array("id"=> $_GET['couponid'], "coupon_name"=>$_POST['coupon_name'], "coupon_code"=>$_POST['coupon_code'], "express_amount"=>$_POST['discount_express'], "deluxe_amount"=>$_POST['discount_deluxe'], "premium_amount"=>$_POST['discount_premium'], "discount_unit"=>$_POST['discount_unit'], "coupon_status"=>$_POST['coupon_status'], "usage_limit"=>$_POST['coupon_use_limit'], "expire_date"=>$_POST['expire_date'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);

curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$coupon_response = $jsondata->response;
$coupon_result_code = $jsondata->result;

}

$handle = curl_init(ROOT_URL."/api/index.php?r=coupons/getcouponbyid");
		$data = array("id"=>$_GET['couponid'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$coupon_details = $jsondata->coupon_details;

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
        <?php include('right-sidebar.php') ?>

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
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Edit Coupon</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Coupon</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
  <?php if(isset($_POST['updatecoupon-form-submit']) && $coupon_result_code == 'true'): ?>
        <p class="success" style="background: #50BB50; color: #fff; padding: 15px; box-sizing: border-box;"><?php echo $coupon_response; ?></p>
        <?php endif; ?>
        <?php if(isset($_POST['updatecoupon-form-submit']) && $coupon_result_code == 'false'): ?>
        <p class="error" style="background: #BB5050; color: #fff; padding: 15px; box-sizing: border-box;"><?php echo $coupon_response; ?></p>
        <?php endif; ?>
                                                        <form method="post" action="">
                                                           <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Coupon Name<span style="color: red;">*</span></label>
                                                                <input type="text" name="coupon_name" class="form-control" value="<?php echo $coupon_details->coupon_name; ?>" required /> </div>
                                                                </div>
                                                                </div>
                                                                <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Coupon Code<span style="color: red;">*</span></label>
                                                                <input type="text" name="coupon_code" class="form-control" value="<?php echo $coupon_details->coupon_code; ?>" required /> </div>
                                                                 </div>
                                                                </div>
                                                                <div class="row">
								    <div class="col-md-2 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Discount Express<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="discount_express" value="<?php echo $coupon_details->express_amount; ?>" required />
                                                            </div>
                                                            </div>
                                                        <div class="col-md-2 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Discount Deluxe<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="discount_deluxe" value="<?php echo $coupon_details->deluxe_amount; ?>" required />
                                                            </div>
                                                            </div>
                                                            <div class="col-md-2 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Discount Premium<span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="discount_premium" value="<?php echo $coupon_details->premium_amount; ?>" required />
                                                            </div>
                                                            </div>
                                                               </div>
                                                               <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Discount Unit</label>
                                                                <select name="discount_unit" class="form-control" id="discount_unit">
                                                                <option value="usd" <?php if($coupon_details->discount_unit == 'usd') echo 'selected'; ?>>USD</option>
                                                               
                                                                </select>
                                                            </div>
                                                            </div>
                                                             </div>
                                                             <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Coupon Status</label>
                                                                <select name="coupon_status" id="coupon_status" class="form-control">
                                                                <option value="enabled" <?php if($coupon_details->coupon_status == 'enabled') echo 'selected'; ?>>Enabled</option>
            <option value="paused" <?php if($coupon_details->coupon_status == 'paused') echo 'selected'; ?>>Paused</option>
            <option value="disabled" <?php if($coupon_details->coupon_status == 'disabled') echo 'selected'; ?>>Disabled</option>
                                                                </select>
                                                            </div>
                                                             </div>
                                                             </div>

                                                            <div class="row">
                                                        <div class="col-md-6 col-sm-12">
<div class="form-group">
                                                                <label class="control-label">How many times</label>
                                                                <select name="coupon_use_limit" id="coupon_use_limit" class="form-control">
                                                                <option value="single" <?php if($coupon_details->usage_limit == 'single') echo 'selected'; ?>>1 time use only</option>
                                                                <option value="multiple" <?php if($coupon_details->usage_limit == 'multiple') echo 'selected'; ?>>Multiple uses</option>

                                                                </select>
                                                            </div>
                                                            </div></div>
                                                            <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Expire Date<span style="color: red;">*</span></label>
                                                                <input class="form-control form-control-inline date-picker" type="text" required="" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" name="expire_date" value="<?php echo $coupon_details->expire_date; ?>" />
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="margiv-top-10">
                                                                <input type="submit" value="Update Coupon" name="updatecoupon-form-submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
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
