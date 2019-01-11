<?php include('header.php') ?>

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
if (!empty($_GET['action'])) {
    $id = $_GET['id'];
    $url = ROOT_URL . '/api/index.php?r=twilio/deletemessage&id=' . $id;
    $handle = curl_init($url);
    $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handle);
    curl_close($handle);
    $jsondata = json_decode($result);
    $response = $jsondata->response;
    $result_code = $jsondata->result;
    if ($response == "agents deleted" && $result_code == "true") {
        ?>
        <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/messagess.php?dell=cnf"</script>
        <?php
        die();
    }
}

if (!empty($_POST['submit'])) {

    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    //exit;
    $media = $_POST['media'];
    $message_get = explode(',', $phone);
    $phone = array();
    $mw_from_mumbers = array("+18335674415", "+18338107607", "+18335674490", "+18335673979", "+18339568060", "+18335673609", "+18335674492", "+18335673706", "+18337896894", "+18335674230");
    $fromindex = 0;
    foreach ($message_get as $message_list) {
        $phone[] = $message_list;
    }
    $count = count($phone);

    $message_data = array();
    $message_data['message'] = $message;
    $message_data['media'] = $media;
    $message_data['key'] = API_KEY;
    $message_data['api_token'] = $finalusertoken;
    $message_data['t1'] = $mw_admin_auth_arr[2];
    $message_data['t2'] = $mw_admin_auth_arr[3];
    $message_data['user_type'] = 'admin';
    $message_data['user_id'] = $mw_admin_auth_arr[4];

    //$message_data['media'] = $media;
    $message_data = http_build_query($message_data);

    foreach ($phone as $phonenumber) {
        $phonenumber = trim($phonenumber);
        //echo $phonenumber;
        //echo "<br>";
        //continue;
        if (!empty($media)) {
            $url = ROOT_URL . '/api/index.php?r=twilio/sendsms&tonumber=' . $phonenumber . '&fromnumber=' . $mw_from_mumbers[$fromindex];
        } else {
            $url = ROOT_URL . '/api/index.php?r=twilio/sendsms&tonumber=' . $phonenumber . '&fromnumber=' . $mw_from_mumbers[$fromindex];
        }

        $handle = curl_init($url);
        //$data = '';
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $message_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $result_code = $jsondata->status;

        if ($result_code == "queued") {
            $url = ROOT_URL . '/api/index.php?r=twilio/reportchange&id=' . $id;
            $handle = curl_init($url);
            $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            if ($response == "updated successfully" && $result == "true") {
                ?>

                <?php
            }
        }

        $fromindex++;
        if ($fromindex > 9)
            $fromindex = 0;
    }

    //die();
}
$url = ROOT_URL . '/api/index.php?r=twilio/getmessges';

$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
/* echo "<pre>";
  print_r($jsondata);
  echo "<pre>";
  exit; */
?>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->



        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <?php if (!empty($_GET['dell'])) { ?>
            <p style="text-align: center; color: green;">Successfully Deleted</p>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Manage Messages</span>
                        </div>

                        <div class="caption font-dark" style="display: <?php echo $add_company; ?>">
                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="add-message.php">Add New</a></span>
                        </div>
                        <div class="caption font-dark" style="display: <?php echo $edit_company; ?>">
                            <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="importdata.php">Import Data</a></span>
                        </div>
                        <div class="actions">
                            <i class="icon-calendar"></i>&nbsp;
                            <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                            <thead>
                                <tr>

                                    <th id="setwidth"> Type </th>
                                    <th> Phone </th>
                                    <th style="width:300px !important;"> Message </th>
                                    <th class="hidden"> Media </th>
                                    <th> Send SMS </th>
                                    <th> &nbsp; </th>
                                    <th style="display: <?php echo $delete_company; ?>"> &nbsp; </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($jsondata as $response) {
                                    $i = 0;
                                    foreach ($response as $responsemesage) {
                                        $i++;
                                        ?>
                                        <tr class="odd gradeX">

                                            <td><?php
                                                if ($responsemesage->to == 'all_washers') {
                                                    echo 'All Active Washers';
                                                } else {
                                                    echo $responsemesage->to;
                                                }
                                                ?></td>
                                            <td><?php echo str_replace(',', ', ', $responsemesage->phone); ?></td>
                                            <td>
                                                <p class="emoji"><?php echo $responsemesage->message; ?></p>
                                            </td>

                                            <td  class="hidden"><?php echo $responsemesage->media; ?></td>
                                            <?php if ($responsemesage->report == 'sent') { ?>
                                                <td><span id="" style="color: #00AFF0; cursor: pointer;">Already sent</span></td>
                                                <td>&nbsp;</td>
                                            <?php } else { ?>
                                                <td><span id="form_<?php echo $i; ?>" onclick="myFunction(<?php echo $i; ?>)" style="color: #00AFF0; cursor: pointer;">Send Now</span></td>
                                                <td><a href="edit-message.php?id=<?php echo $responsemesage->id; ?>">Edit</a></td>
                                            <?php } ?>

                                            <td><a onclick="return confirm('Are you sure ?')" href="messagess.php?id=<?php echo $responsemesage->id; ?>&action=dell">Delete</a></td>

                                        </tr>
                                        <?php
                                    }
                                }
                                ?>    
                            </tbody>
                        </table>
                        <?php
                        foreach ($jsondata as $response) {
                            $i = 0;
                            foreach ($response as $responsemesage) {
                                $i++;
                                ?>
                                <form method="post" action="" style="display: none;">
                                    <table style="display: none;">
                                        <tr><td>
                                                <input type="hidden" name="id" value="<?php echo $responsemesage->id; ?>"></td></tr>

                                        <tr><td>
                                                <input type="hidden" name="phone" value="<?php echo $responsemesage->phone; ?>"></td></tr>
                                        <tr><td>
                                                <input type="hidden" name="message" value="<?php echo str_replace('"', '”', $responsemesage->message); ?>"></td></tr>
                                        <tr><td>
                                                <input type="hidden" name="media" value="<?php echo $responsemesage->media; ?>"></td></tr>
                                        <tr><td>
                                                <input type="submit" id="sumit_<?php echo $i; ?>" name="submit" value="submit">
                                            </td></tr>
                                    </table>
                                </form>
                                <?php
                            }
                        }
                        ?>
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
<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<?php
foreach ($jsondata as $response) {
    $i = 0;
    foreach ($response as $responsemesage) {
        $i++;
        ?>

        <script>
                                                function myFunction(el) {

                                                    if (confirm("Are you sure?")) {
                                                        $('#sumit_' + el).trigger('click');
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }


                                                }
        </script>

        <?php
    }
}
?>
<style>
    #setwidth{
        width: 13% !important;
    }
    .emojionearea, .emojionearea.form-control{
        border:none;
        box-shadow: none;
    }
    .emojionearea-button{
        display:none;
    }
</style>