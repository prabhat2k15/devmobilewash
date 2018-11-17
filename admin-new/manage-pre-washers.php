<?php
include('header.php');
    if(!empty($_GET['action'])){
        $agentID = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=agents/trashprewasher&id='.$agentID;
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
        if($response == "agents trash" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-washers.php?trash=true"</script>
            <?php
            die();
            }
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
    
       $url = ROOT_URL.'/api/index.php?r=agents/getallprewashers'; 
            $handle = curl_init($url);
            $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $prewashers = json_decode($result);
            
       $url_trash = ROOT_URL.'/api/index.php?r=agents/getprewashertrashdata'; 
            $handle_trash = curl_init($url_trash);
            $data_trash = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle_trash, CURLOPT_POST, true);
            curl_setopt($handle_trash, CURLOPT_POSTFIELDS, $data_trash);
            curl_setopt($handle_trash,CURLOPT_RETURNTRANSFER,1);
            $result_trash = curl_exec($handle_trash);
            curl_close($handle_trash);
            $preclients_trash = json_decode($result_trash);
            $count = $preclients_trash->count;
    
?>
<style>
.current_tab{
background-color: #5407e2 !important;
border-top: 5px solid #5407e2 !important;
height: 90px !important;
padding: 13px 0 0 10px !important;
cursor: pointer !important;
}
.control-group.form-group {
  width: 100%;
}
.form-control.input-sm {
  width: 100%;
}
.editable-buttons {
  text-align: center;
}
</style>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <div class="clear">&nbsp;</div>
                    <?php if(!empty($_GET['trash'])){ ?>
                    <p style="text-align: center; color: green;">Record moved to trash</p>
                    <?php } ?>
                    <?php if(!empty($_GET['act'])){ ?>
					<p style="text-align: center; color: green;">Wahser moved to real washer section successfully</p>
					<?php } ?>
                    <?php if(!empty($_GET['restore'])){ ?>
                    <p style="text-align: center; color: green;">Record restored successfully</p>
                    <?php } ?>
					<?php if(!empty($_GET['datacopy'])){ ?>
                    <p style="text-align: center; color: green;">Record updated successfully</p>
                    <?php } ?>
                    <?php if(!empty($_GET['update'])){ ?>
					<p style="text-align: center; color: green;">Record updated successfully</p>
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
                                        <span class="caption-subject bold uppercase"> Manage Pre-Registered Washers</span> <a style="margin-left: 20px;" target="_blank" href="<?php echo ROOT_URL; ?>/api/index.php?r=site/prewasherexport&key=<?php echo API_KEY; ?>&api_token=<?php echo urlencode($finalusertoken); ?>&t1=<?php echo ($mw_admin_auth_arr[2]); ?>&t2=<?php echo urlencode($mw_admin_auth_arr[3]); ?>&user_type=admin&user_id=<?php echo urlencode($mw_admin_auth_arr[4]); ?>">Download CSV</a>
                                    </div>
                                    <div style="margin: -20px 0px 0px 100px; display: none;" class="caption font-dark" id="copy_washers">
                                        <span class="caption-subject bold uppercase"> <img width="84" src="images/loader.gif" class="copy_clients"></span>
                                    </div>
                                    <div class="actions" style="padding: 1px 0px 0px 20px;">
                                        <span class="caption-subject bold uppercase"><a href="trash-pre-washers.php"><img src="images/trash.png" width="30">(<?php echo $count; ?>)</a></span>
                                    </div>
                                    <div style="margin: 0px 0px 0px 20px; color: #55799a; display: <?php echo $edit_washer; ?>" class="actions">
                                        <span class="caption-subject bold uppercase copy_washers" style="cursor: pointer;">Update</span>
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
                                                <th style="display: <?php echo $edit_washer; ?>">&nbsp;</th>
                                                <th style="display: <?php echo $delete_washer; ?>">&nbsp;</th>
                                                <th>&nbsp;</th>
                    <th>Email</th>
                    
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zipcode</th>
                    <th>How you heard about us</th>
                    <th>Register Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        foreach($prewashers->all_washers as $washer){
                                               
                                        $date = explode(' ',$washer->register_date);
                                        $newdate = explode('-',$date[0]);
                                        $usdate = $newdate[1].'-'.$newdate[2].'-'.$newdate[0].' '.$date[1];
                                        
                                        ?>
                                            <tr class="odd gradeX">
                                                <td><span id="<?php echo $washer->id; ?>" class="xedit select editable editable-click" data-title="Do you want this washer move to real washer?" data-type="select" data="movewasher" data-original-title="" title="" style="border-bottom: dashed 1px #0088cc; color: #337ab7; cursor: pointer;">Move</span> </td>
                                                <td style="display: <?php echo $edit_washer; ?>"> <a href="pre-washer-details.php?id=<?php echo $washer->id; ?>">Edit</a> </td>
												<td style="display: <?php echo $delete_washer; ?>"> <a onclick="return confirm('Are you sure ?')" href="manage-pre-washers.php?id=<?php echo $washer->id; ?>&action=trash">Trash</a> </td>
                                                <td> <?php echo $washer->email; ?> </td>
                                                <td> <?php echo $washer->id; ?> </td>
                                                <td> <?php echo $washer->first_name; ?> </td>
                                                <td> <?php echo $washer->last_name; ?> </td>
                                                <td> <?php echo $washer->phone; ?> </td>
                                                <td> <?php echo $washer->city; ?> </td>
                                                <td> <?php echo $washer->state; ?> </td>
                                                <td> <?php echo $washer->zipcode; ?> </td>
                                                <td> <?php echo $washer->hear_mw_how; ?> </td>
                                                <td> <?php echo $usdate; ?> </td>
												
                                                
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
        
<script type="text/javascript">
$('.copy_washers').click(function(){
    $('#copy_washers').show();
    $.getJSON("<?php echo ROOT_URL; ?>/admin-new/data_update_washer.php", function( data ) {
if(data.response == 'copy'){
    $('#copy_washers').hide();
    window.location.href = "manage-pre-washers.php?datacopy=true";
}


});
});
</script> 
 
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-editable.js" type="text/javascript"></script> 
        <script>
    $(function(){
        $('.select').editable({
            value: 2,    
            source: [
                  {value: 'no', text: 'NO'},
                  {value: 'yes', text: 'YES'}
               ]
        });
    });
    
    </script>
<script type="text/javascript">
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}
jQuery(document).ready(function() {  
        $.fn.editable.defaults.mode = 'popup';
        $('.xedit').editable();        
        $(document).on('click','.editable-submit',function(){
            var x = $(this).closest('td').children('span').attr('id');
            var y = $('.input-sm').val();
            var z = $(this).closest('td').children('span').attr('data');
            
           
           if(z == 'movewasher' && y==null)
           {
               alert('Please Select Move Status');
               return false;
           }
           if(z == 'movewasher' && y=='no')
           {
               $('.editable-cancel').trigger('click');
               return false;
           }
           
          
           
            $.getJSON('<?php echo ROOT_URL; ?>/api/index.php?r=agents/MovePreToRealWasher&agentsid='+x+'&'+z+'='+y, {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
                if(data.response == 'move successfully'){
                    window.location.href = "manage-pre-washers.php?act=move";
                }
            });
        });
});
</script>   
   
