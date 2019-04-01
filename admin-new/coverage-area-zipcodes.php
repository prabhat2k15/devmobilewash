<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
include('header.php');
session_start();
require_once 'google-api-php-client-2.0.1/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_947329153849.json');
$client->setAccessType('offline');
$client->addScope('https://www.googleapis.com/auth/fusiontables');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//echo print_r($_SESSION['access_token']);
    $client->setAccessToken($_SESSION['access_token']);
} else {

    $redirect_uri = ROOT_URL . '/admin-new/oauth2callback.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function getExcerpt($str, $startPos = 0, $maxLength = 100) {
    if (strlen($str) > $maxLength) {
        $excerpt = substr($str, $startPos, $maxLength - 3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt = substr($excerpt, 0, $lastSpace);
        $excerpt .= '...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
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
$url = ROOT_URL . '/api/index.php?r=washing/getallcoveragezipcodes';

$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$zipcode_response = $jsondata->response;
$zipcode_result_code = $jsondata->result;
$all_zipcodes = $jsondata->zipcodes;
?>
<style>
    .label-online {
        background-color: #16CE0C !important;
    }

    .label-offline {
        background-color: #969696 !important;
    }
</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->



        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Coverage Area Zipcodes</span> <a href="add-coverage-area-zipcode.php" style="margin-left: 15px; font-size: 16px; font-weight: bold;">+ Add New</a>
                        </div>
                        <div class="caption font-dark">

                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>

                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                        <?php if ($_GET['action'] == 'add-zipcode-success'): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Zipcode added successfully</span><a href="/admin-new/coverage-area-zipcodes.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if ($_GET['action'] == 'delete-success'): ?>
                            <p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Zipcode #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/coverage-area-zipcodes.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                        <?php if ($_GET['action'] == 'delete-error'): ?>
                            <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting zipcode. Please try again.</span><a href="/admin-new/coverage-area-zipcodes.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
                        <?php endif; ?>
                    </div>
                    <div class="portlet-body">
                        <?php if ($zipcode_result_code == 'true') { ?>
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Zipcode </th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($all_zipcodes as $zipcode) { ?>
                                        <tr class="odd gradeX">

                                            <td><?php echo $zipcode->id; ?></td>
                                            <td><?php echo $zipcode->zipcode; ?> <a style="float: right;" href="#" class="delete-zipcode" data-id="<?php echo $zipcode->id; ?>" data-zip="<?php echo $zipcode->zipcode; ?>">X</a>
                                                <div style="clear: both;"></div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<style>
    .fullwidth{
        width: 14% !important;
    }
</style>
<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    $(function () {
        $(document).on('click', '.delete-zipcode', function () {
            var th = $(this);
            id = $(this).data('id');
            zip = $(this).data('zip');
//console.log(zip);
            var r = confirm('Are you sure you want to delete zipcode #' + id + '?');
            if (r == true) {
                $(th).html('Deleting...');
                $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=washing/deletecoveragezipcode", {id: id, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function (data) {
                    if (data.result == 'true') {
                        $.getJSON("<?php echo ROOT_URL; ?>/admin-new/delete-zipcode-fusion-table.php", {zipcode: zip}, function (data2) {
//console.log(data2);
                            if (data2.result == 'true') {
                                window.location.href = "<?php echo ROOT_URL; ?>/admin-new/coverage-area-zipcodes.php?action=delete-success&nid=" + id;
                            }
                        });
                    }
                    if (data.result == 'false') {
                        window.location.href = "<?php echo ROOT_URL; ?>/admin-new/coverage-area-zipcodes.php?action=delete-error";
                    }

                });

            }
            return false;
        });
    });
</script>