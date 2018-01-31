<?php
include('header.php');
    if(!empty($_GET['action'])){
        $id = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=users/deleteadminuser&id='.$id;
        $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;
        if($response == "successfully delete" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-user.php?delete=true"</script>
            <?php
            die();
            }
    }
?>
<?php  ?>
<?php
    if($company_module_permission == 'no' || $checked_manage_user == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
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


</style>
<?php
    
            $url = ROOT_URL.'/api/index.php?r=users/manageuser'; 
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $manageuser = json_decode($result);
            
    
?>
<style>
.current_tab{
background-color: #5407e2 !important;
border-top: 5px solid #5407e2 !important;
height: 90px !important;
padding: 13px 0 0 10px !important;
cursor: pointer !important;
}
</style>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <div class="clear">&nbsp;</div>
                    <?php if(!empty($_GET['cnf'])){ ?>
                    <p style="text-align: center; color: green;">Record added successfully</p>
                    <?php } ?>
                    <?php if(!empty($_GET['update'])){ ?>
                    <p style="text-align: center; color: green;">Record Update successfully</p>
                    <?php } ?>
                    <?php if(!empty($_GET['delete'])){ ?>
					<p style="text-align: center; color: green;">Record deleted successfully</p>
					<?php } ?>
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Manage Users</span>
                                    </div>
                                    <div style="padding: 10px 0px 0px 20px; display: " class="caption font-dark">
                                        <span class="caption-subject bold uppercase"><a href="add-user.php"> Add New User</a></span>
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
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                    <th>Email</th>
                    <th>User Name</th>
                    <th>Users Type</th>
                    <th>Client Module Permission</th>
                    <th>Washer Module Permission</th>
                    <th>Company Module Permission</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        foreach($manageuser->all_users as $users){
                                            if(empty($users->client_action)){
                                                $clientactiondata = '';
                                            }elseif($users->client_action == 'no'){
                                                $clientactiondata = 'No';
                                            }else{
                                                $clientaction = unserialize($users->client_action);
                                                $clientactiondata = implode($clientaction,',');
                                            }
                                            if(empty($users->washer_action)){
                                                $washeractiondata = '';
                                            }elseif($users->washer_action == 'no'){
                                                $washeractiondata = 'No';
                                            }else{
                                                $washeraction = unserialize($users->washer_action);
                                                $washeractiondata = implode($washeraction,',');
                                            }
                                            if(empty($users->company_action)){
                                                $companyactiondata = '';
                                            }elseif($users->company_action == 'no'){
                                                $companyactiondata = 'No';
                                            }else{
                                                $companyaction = unserialize($users->company_action);
                                                $companyactiondata = implode($companyaction,',');
                                            }
                                        ?>
                                            <tr class="odd gradeX">
                                                <td> <a href="edit-user.php?id=<?php echo $users->id; ?>">Edit</a> </td>
												<td> <a onclick="return confirm('Are you sure ?')" href="manage-user.php?id=<?php echo $users->id; ?>&action=delete">Delete</a> </td>
                                                <td> <?php echo $users->email; ?> </td>
                                                <td> <?php echo $users->username; ?> </td>
                                                <td> <?php echo $users->users_type; ?> </td>
                                                <td> <?php echo $clientactiondata; ?> </td>
                                                <td> <?php echo $washeractiondata; ?> </td>
                                                <td> <?php echo $companyactiondata; ?> </td>
												
                                                
                                            </tr>
                                            
                                        <?php 
                                        
                                            
                                        }
                                        
                                        ?>
                                        </tbody>
                                    </table>
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
        <style>
        
.page-content-wrapper .page-content{
    padding: 0 20px 10px !important;
}
        </style>