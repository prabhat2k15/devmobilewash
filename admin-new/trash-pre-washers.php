<?php
include('header.php');
    if(!empty($_GET['action'])){
        $agentID = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=agents/restoreprewasher&id='.$agentID;
        $handle = curl_init($url);
        $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;
        if($response == "agents restore" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-washers.php?restore=true"</script>
            <?php
            die();
            }
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
    
       $url = ROOT_URL.'/api/index.php?r=agents/getalltrashprewashers'; 
            $handle = curl_init($url);
            $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $prewashers = json_decode($result);
    
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
                                        <span class="caption-subject bold uppercase"> Manage Pre-Registered Trash Washers</span>
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
                                            <th style="display: <?php echo $edit_washer; ?>">Action</th>
                                            <th>ID</th>
                    <th>Email</th>
                    
                    
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zipcode</th>
                    <th>Register Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        foreach($prewashers->all_washers as $washer){
                                               
                                        
                                        
                                        ?>
                                            <tr class="odd gradeX">
                                            <td style="display: <?php echo $edit_washer; ?>"><a onclick="return confirm('Are you sure ?')" href="trash-pre-washers.php?id=<?php echo $washer->id; ?>&action=restore">restore </a></td>
                                            <td> <?php echo $washer->id; ?> </td>
                                                <td> <?php echo $washer->email; ?> </td>
                                                
                                                <td> <?php echo $washer->first_name; ?> </td>
                                                <td> <?php echo $washer->last_name; ?> </td>
                                                <td> <?php echo $washer->phone; ?> </td>
                                                <td> <?php echo $washer->city; ?> </td>
                                                <td> <?php echo $washer->state; ?> </td>
                                                <td> <?php echo $washer->zipcode; ?> </td>
                                                <td> <?php echo $washer->register_date; ?> </td>
												
                                                
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
        
    
   