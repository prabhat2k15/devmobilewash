<?php
include('header.php');
    if(!empty($_GET['action'])){
        $clientsid = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=customers/restorepreclients&id='.$clientsid;
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
        if($response == "clients restore" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php?restore=true"</script>
            <?php
            die();
            }
    }
?>

<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
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
    
       $url = ROOT_URL.'/api/index.php?r=customers/getalltrashpreclients'; 
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $preclients = json_decode($result);
    
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
                    <?php if(!empty($_GET['dell'])){ ?>
					<p style="text-align: center; color: green;">Successfully Deleted</p>
					<?php } ?>
					<?php if(!empty($_GET['cnf'])){ ?>
					<p style="text-align: center; color: green;">Successfully Create Agent</p>
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
                                        <span class="caption-subject bold uppercase"> Manage Pre-Registered Trash Clients</span>
                                    </div>
                                    <!--<div class="caption font-dark" style="padding: 10px 0px 0px 20px;">
                                        <span class="caption-subject bold uppercase"><a href="add-agent.php"> Add New Agent</a></span>
                                    </div>-->
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                
                    <th style="display: <?php echo $edit; ?>">Action</th>
                    <th>ID</th>
                    <th>Email</th>
                    
                    
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        foreach($preclients->all_clients as $clients){
                                               
                                        
                                            
                                        
                                        
                                        ?>
                                            <tr class="odd gradeX">
                                               
                                               <td style="display: <?php echo $edit; ?>"> <a onclick="return confirm('Are you sure ?')" href="trash-pre-clients.php?id=<?php echo $clients->id; ?>&action=restore">Restore </a></td>
                                               <td> <?php echo $clients->id; ?> </td>
                                                <td> <?php echo $clients->email; ?> </td>
                                                
                                                <td> <?php echo $clients->first_name; ?> </td>
                                                <td> <?php echo $clients->last_name; ?> </td>
                                                <td> <?php echo $clients->phone; ?> </td>
                                                <td> <?php echo $clients->city; ?> </td>
                                                <td> <?php echo $clients->state; ?> </td>
												
                                                
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
        
    
   