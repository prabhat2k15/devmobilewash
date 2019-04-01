<?php include('header.php') ?>
<?php
$errmsg = $msg = '';
if (isset($_POST['add-review-submit'])) {
    $action = $_POST['action'];
    $img_error_ext = $img_error_check = $imag_error_empty = '';
    if ($action == 'add') {
        $filename = $_FILES['customer-img']['name'];
        $filedata = $_FILES['customer-img']['tmp_name'];
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/upload_reviews/tmp/';
        // file check
        $target_file = $target_dir . basename($filename);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($filedata);
        // Allow certain file formats
        if (empty($filename)) {
            $imag_error_empty = "Please choose image to upload.";
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $img_error_ext = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($check == false) {
            $img_error_check = "File is not an image.";
        }

        if (empty($img_error_ext) && empty($img_error_check) && !empty($filename)) {

            $up = move_uploaded_file($filedata, $target_dir . $filename);
            if ($up == 1) {

                $revdata = array("target" => $target_dir, "filename" => $filename, "cust_review" => $_POST['customer-review'], "action" => $action, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);


                $handle_data = curl_init(ROOT_URL . "/api/index.php?r=customers/AddUpdateReview");

                curl_setopt($handle_data, CURLOPT_POST, true);
                curl_setopt($handle_data, CURLOPT_POSTFIELDS, $revdata);
                curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($handle_data);
                curl_close($handle_data);
                $jsondata = json_decode($result);
                $review_response = $jsondata->response;
                $review_code = $jsondata->result;
            } else {
                $img_error_check = "Sorry! something went wrong with image please try again.";
            }
        }
    } else {
        $review_id = $_GET['id'];
        $old_img = $_POST['old_img'];
        $filename = $_FILES['customer-img']['name'];
        $filedata = $_FILES['customer-img']['tmp_name'];
        $cust_review = $_POST['customer-review'];

        $revdata = array();
        if (!empty($filename) && ($old_img != $filename)) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/upload_reviews/tmp/';
            $source_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/upload_reviews/';
            // file check
            $target_file = $target_dir . basename($filename);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($filedata);
            // unlink old_img
            unlink($source_dir . $old_img);

            // Allow certain file formats
            if (empty($filename)) {
                $imag_error_empty = "Please choose image to upload.";
            } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $img_error_ext = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } elseif ($check == false) {
                $img_error_check = "File is not an image.";
            }

            if (empty($img_error_ext) && empty($img_error_check) && !empty($filename)) {

                $up = move_uploaded_file($filedata, $target_dir . $filename);
                if ($up == 1) {
                    $revdata = array("review_id" => $review_id, "target" => $target_dir, "filename" => $filename, "old_img" => $old_img, "cust_review" => $cust_review, "action" => $action, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
                }
            }
        } else {
            $revdata = array("review_id" => $review_id, "cust_review" => $cust_review, "old_img" => $old_img, "action" => $action, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
        }

        $handle_data = curl_init(ROOT_URL . "/api/index.php?r=customers/AddUpdateReview");

        curl_setopt($handle_data, CURLOPT_POST, true);
        curl_setopt($handle_data, CURLOPT_POSTFIELDS, $revdata);
        curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle_data);
        curl_close($handle_data);
        $jsondata = json_decode($result);
        $review_response = $jsondata->response;
        $review_code = $jsondata->result;
        $all_reviews = $jsondata->reviews;
    }
}

if ($_GET['action'] != 'add' && !isset($_POST['add-review-submit'])) {
    $review_id = $_GET['id'];
    $handle_data = curl_init(ROOT_URL . "/api/index.php?r=customers/AddUpdateReview");

    // Assign POST data
    $data = array('id' => $review_id, 'action' => 'view', 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
    curl_setopt($handle_data, CURLOPT_POST, true);
    curl_setopt($handle_data, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle_data, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle_data);
    curl_close($handle_data);
    $jsondata = json_decode($result);
    $review_response = $jsondata->response;
    $review_code = $jsondata->result;
    $all_reviews = $jsondata->reviews;
}



$baseURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/';
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

<?php
$url = ROOT_URL . '/api/index.php?r=agents/prewasherdetails';
$handle = curl_init($url);
$data = array('id' => $_GET['id'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
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
    #image {
        padding-bottom: 10px;
        padding-top: 10px;
    }
</style>
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" id="main">
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
                                        <span class="caption-subject font-blue-madison bold uppercase"><?php echo ucfirst($_GET['action']); ?> Review</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB --> 
                                        <div class="tab-pane active" id="tab_1_1">
                                            <?php if (isset($_POST['add-review-submit']) && ($_POST['action'] == 'add') && ($review_code == 'true')): ?>
                                                <p style="color: #fff; background: green; padding: 10px;"><?php echo $review_response; ?></p>
                                            <?php endif; ?> 
                                            <?php if (isset($_POST['add-review-submit']) && ($_POST['action'] == 'edit') && ($review_code == 'true')): ?>
                                                <p style="color: #fff; background: green; padding: 10px;"><?php echo $review_response; ?></p>
                                            <?php endif; ?> 
                                            <?php if (isset($_POST['add-review-submit']) && $review_code == 'false'): ?>
                                                <p style="color: #fff; background: red; padding: 10px;"><?php echo $review_response; ?></p>
                                            <?php endif; ?> 
                                            <?php if (isset($_POST['add-review-submit']) && !empty($img_error_check)): ?>
                                                <p style="color: #fff; background: red; padding: 10px;"><?php echo $img_error_check; ?></p>
                                            <?php endif; ?> 
                                            <?php if (isset($_POST['add-review-submit']) && !empty($img_error_ext)): ?>
                                                <p style="color: #fff; background: red; padding: 10px;"><?php echo $img_error_ext; ?></p>
                                            <?php endif; ?> 
                                            <?php if (isset($_POST['add-review-submit']) && !empty($imag_error_empty)): ?>
                                                <p style="color: #fff; background: red; padding: 10px;"><?php echo $imag_error_empty; ?></p>
                                            <?php endif; ?> 

                                            <form action="" id="add-review" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="control-label">Image</label>
                                                    <input type="file" class="form-control" name="customer-img" id="customer-img" style="width: 250px;height: auto;">
                                                    <?php if (!empty($all_reviews[0]->cust_img)) { ?>
                                                        <div id="image"><img src="<?php echo $baseURL . 'images/upload_reviews/' . $all_reviews[0]->cust_img; ?>" height="80" alt="No Image"></div><?php }
                                                    ?>	
                                                    <input type="hidden" name="old_img" value="<?php
                                                    if (!empty($all_reviews[0]->cust_img)) {
                                                        echo $all_reviews[0]->cust_img;
                                                    }
                                                    ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Review</label>
                                                    <textarea cols="10" class="form-control" rows="5" name="customer-review" id="customer-review"><?php
                                                        if (isset($all_reviews[0]->cust_review)) {
                                                            echo $all_reviews[0]->cust_review;
                                                        }
                                                        ?></textarea>
                                                </div>
                                                <div class="clear" style="height: 10px;">&nbsp;</div>
                                                <div class="margiv-top-10">
                                                    <input type="submit" id="regular-submit" value="Submit" name="add-review-submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                    <input type="hidden" name="action" value="<?php
                                                    if (isset($all_reviews[0]->id)) {
                                                        echo"edit";
                                                    } else {
                                                        echo"add";
                                                    }
                                                    ?>">
                                                </div>
                                            </form>
                                            <div class="clear" style="height: 10px;">&nbsp;</div>
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


