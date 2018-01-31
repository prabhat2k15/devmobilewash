<?php
include('header.php');
    if($_GET['action'] == 'trash'){
        $clientsid = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=customers/trashpreclients2&id='.$clientsid;
        $handle = curl_init($url);
        $data = '';
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;
        if($response == "clients trashed" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients2.php?trash=true"</script>
            <?php
            die();
            }
    }

?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token);
$handle_data = curl_init("<?php echo ROOT_URL; ?>/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
?>
<?php

    if($company_module_permission == 'no' || $checked_opening_hours == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#example1').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
} );
            
        });
        </script>
<?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php
     $url = ROOT_URL.'/api/index.php?r=customers/getallpreclients2'; 
            $handle = curl_init($url);
            $data = '';
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $preclients = json_decode($result);
          
            
            $url_trash = ROOT_URL.'/api/index.php?r=customers/getpreclientstrashdata2'; 
            $handle_trash = curl_init($url_trash);
            $data_trash = '';
            curl_setopt($handle_trash, CURLOPT_POST, true);
            curl_setopt($handle_trash, CURLOPT_POSTFIELDS, $data_trash);
            curl_setopt($handle_trash,CURLOPT_RETURNTRANSFER,1);
            $result_trash = curl_exec($handle_trash);
            curl_close($handle_trash);
            $preclients_trash = json_decode($result_trash);
            $count = $preclients_trash->count;
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
                                        <span class="caption-subject bold uppercase"> MANAGE PRE-REGISTERED CLIENTS</span>
                                    </div>
<div style="margin: -20px 0px 0px 100px; display: none;" class="caption font-dark" id="copy_clients">
                                        <span class="caption-subject bold uppercase"> <img width="84" src="images/loader.gif" class="copy_clients"></span>
                                    </div>
                                   
                                    <div class="actions" style="padding: 1px 0px 0px 20px;">
                                        <span class="caption-subject bold uppercase"><a href="trash-pre-clients.php"><img src="images/trash.png" width="30">(<?php echo $count; ?>)</a></span>
                                    </div>
                                   
                                   
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                   
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
 <?php if(!empty($_GET['update'])): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record updated successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
 <?php if(!empty($_GET['restore'])): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record restored successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'move-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Client moved to real client section successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'move-error'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in moving pre-client. Please try again.</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
  <?php if(!empty($_GET['trash'])): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Record moved to trash successfully</span><a href="/admin-new/manage-pre-clients.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if($preclients->result == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                               
                                                <th> ID </th>
                                                <th> Email </th>
                                                <th> First Name </th>
                                                <th> Last Name </th>
<th> Phone </th>
<th> City </th>
<th> State </th>
                                                <th style="min-width: 140px;"> Actions </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php 
foreach($preclients->all_clients as $clients){ ?>
                <tr class="odd gradeX">
                       <td> <?php echo $clients->id; ?> </td>
                   <td> <?php echo $clients->email; ?> </td>
                                             
                                                <td> <?php echo $clients->first_name; ?> </td>
                                                <td> <?php echo $clients->last_name; ?> </td>
                                                <td> <?php echo $clients->phone; ?> </td>
                                                <td> <?php echo $clients->city; ?> </td>
                                                <td> <?php echo $clients->state; ?> </td>
<td>
   <a href="#" data-id="<?php echo $clients->id; ?>" class="move-client" style="margin-right: 7px;">Move</a> <a href="pre-clients-details.php?id=<?php echo $clients->id; ?>" style="margin-right: 7px;">Edit</a> <a onclick="return confirm('Are you sure ?')" href="manage-pre-clients.php?id=<?php echo $clients->id; ?>&action=trash">Trash</a>
                                              
</td>
                </tr>
                <?php } ?>
                                      </tbody>
                                    </table>
                                    <?php  } ?>
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
$(function(){
$(document).on( 'click', '.move-client', function(){
var th = $(this);
id = $(this).data('id');
var r = confirm('Do you want to make pre-client #'+ id +' as real client?');
if (r == true) {
$(th).html('Moving...');
 $.getJSON('<?php echo ROOT_URL; ?>/api/index.php?r=customers/MovePreToRealclient&clientid='+id+'&movewasher=yes', function( data ) {
if(data.result == 'true'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php?action=move-success";
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/newsletters.php?action=move-error";
}

});
   
} 
return false;
});
});
</script>