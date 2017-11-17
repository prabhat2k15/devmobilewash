<?php

if($_GET['type']) $url = 'http://www.devmobilewash.com/api/index.php?r=agents/getallagents&type='.$_GET['type'];
else $url = 'http://www.devmobilewash.com/api/index.php?r=agents/getallagents';
        $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $allagents = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;

    if(!empty($_GET['actionss'])){
        $agentID = $_GET['agentID'];
        $url = 'http://www.devmobilewash.com/api/index.php?r=agents/deleteagents&id='.$agentID;
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
        if($response == "agents deleted" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/manage-agents.php?dell=cnf"</script>
            <?php
            die();
            }
    }
?>
<?php include('header.php') ?>
<?php
    if($washer_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/index.php"</script><?php
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
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
"aaSorting": []
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
    if(empty($_GET['type'])){
       $url = 'http://www.devmobilewash.com/api/index.php?r=agents/agentsadmin';

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
        /*echo "<pre>";
        print_r($jsondata);
        echo "<pre>";
        exit;*/
        $totalagetns = 'current_tab';
    }elseif(!empty($_GET['type']))
    {


        $type = $_GET['type'];
        $url = 'http://www.devmobilewash.com/api/index.php?r=agents/viewagent&type='.$type;
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
        if($type == 'offlineagents'){
            $offlineagents = 'current_tab';
        }elseif($type == 'insurance_license_expiration_count'){
            $expiration = 'current_tab';
        }elseif($type == 'cancel_orders_agent'){
            $cancel_orders_agent = 'current_tab';
        }elseif($type == 'idle_wash'){
            $idle_wash = 'current_tab';
        }elseif($type == 'bad_rating_agents'){
            $bad_rating_agents = 'current_tab';
        }elseif($type == 'late_drivers'){
            $late_drivers = 'current_tab';
        }

    }
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
<script type="text/javascript">
$(document).ready(function(){
$('#totalagetns').click(function(){
    if($('.total_agetns').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php';
});
$('#offlineagents').click(function(){
    if($('.offlineagents').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=offlineagents';
});
$('#insurance_license_expiration_count').click(function(){
    if($('.insurance_license_expiration_count').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=insurance_license_expiration_count';
});
$('#cancel_orders_agent').click(function(){
    if($('.cancel_orders_agent').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=cancel_orders_agent';
});
$('#idle_wash').click(function(){
    if($('.idle_wash').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=idle_wash';
});
$('#bad_rating_agents').click(function(){
    if($('.bad_rating_agents').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=bad_rating_agents';
});
$('#late_drivers').click(function(){
    if($('.late_drivers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=late_drivers';
});
});
</script>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <div class="row" style="background-color: #000; color: #fff; margin-left: -20px ! important; margin-right: -20px;">
                        <div class="col-md-1 col-sm-1 <?php echo $totalagetns; ?>" id="totalagetns" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #5407e2;">
                            <div style="font-size: 20px;" class="total_agetns">0</div>
                            <div>Total Washers</div>
                        </div>
                        <div class="col-md-1 col-sm-1 <?php echo $offlineagents; ?>" id="offlineagents" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #0771e2;">
                            <div style="font-size: 20px;" class="offlineagents">0</div>
                            <div>Offline Washers</div>
                        </div>
                        <div class="col-md-2 col-sm-2 <?php echo $expiration; ?>" id="insurance_license_expiration_count" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e28307;">
                            <div style="font-size: 20px;" class="insurance_license_expiration_count">0</div>
                            <div>Insurance Expiring</div>
                        </div>
                        <div class="col-md-2 col-sm-2 <?php echo $cancel_orders_agent; ?>" id="cancel_orders_agent" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e20724;">
                            <div style="font-size: 20px;" class="cancel_orders_agent">0</div>
                            <div>Washers Cancels</div>
                        </div>
                        <div class="col-md-1 col-sm-1 <?php echo $idle_wash; ?>" id="idle_wash" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #07c1e2;">
                            <div style="font-size: 20px;" class="idle_wash">0</div>
                            <div>Idle Washers</div>
                        </div>
                        <div class="col-md-2 col-sm-2 <?php echo $bad_rating_agents; ?>" id="bad_rating_agents" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e900e7;">
                            <div style="font-size: 20px;" class="bad_rating_agents">0</div>
                            <div>Flagged Bad Washers</div>
                        </div>
                        <div class="col-md-1 col-sm-1 <?php echo $late_drivers; ?>" id="late_drivers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #25e900;">
                            <div style="font-size: 20px;" class="late_drivers">0</div>
                            <div>Late Drivers</div>
                        </div>
                    </div>
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
                                        <span class="caption-subject bold uppercase"> Managed Agents</span>
                                    </div>
                                    <div class="caption font-dark" style="padding: 10px 0px 0px 20px; padding-top: 3px; display: <?php echo $add_washer; ?>">
                                        <span class="caption-subject bold uppercase"><a href="add-agent.php"> Add New Agent</a></span>
<button class="btn blue all-agents-logout" style="margin-left: 15px;">Logout All Agents</button>
<select name="washer-type" class="washer-type" style="margin-left: 15px; font-size: 16px; padding: 5px;"><option value="http://www.devmobilewash.com/admin-new/manage-agents.php?type=real">Real Washer</option><option <?php if($_GET['type'] == 'demo') echo 'selected'; ?> value="http://www.devmobilewash.com/admin-new/manage-agents.php?type=demo">Demo Washer<option></select>                                    
</div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
<?php if($_GET['action'] == 'allaglogout-success'): ?>
<p style="background: green; color: #fff; padding: 10px; margin: 10px 0;">All agents successfully logout</p>
<?php endif; ?>
<?php if($_GET['action'] == 'allaglogout-error'): ?>
<p style="background: red; color: #fff; padding: 10px; margin: 10px 0;">Error in logout operation</p>
<?php endif; ?>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
												<th> Actions </th>
                                                <th> ID </th>
<th> Badge No </th>
                                                   <th> First Name </th>
                                                <th> Last Name </th>
                                                <th> Email </th>
                                                <th> Phone Number </th>
                                                <th> Phone Verify Code </th>
                                                <th> City </th>
<th> Insurance Exp. Date </th>
                                                <th> Rating </th>
                                                <th> CARE Rating </th>
<th> Washes </th>
 <th> BT Submerchant ID </th>
                                                <th> Status </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php


                                            foreach( $allagents->all_washers as $washer){
                                                if($washer->status == 'busy'){
                                                       $status = '<span class="label label-sm label-busy">Busy</span>';
                                                       }
                                                elseif($washer->status == 'online'){
                                                        $status =  '<span class="label label-sm label-online">Online</span>';
                                                        }
                                                elseif($washer->status == 'offline'){
                                                        $status = '<span class="label label-sm label-offline">Offline</span>';
                                                        }
                                                else{
                                                        $status = '';
                                                }
                                                if($washer->account_status == 1){
                                                        $account_status = '<span class="label label-sm label-success"> Active </span>';
                                                        }
                                                else{
                                                        $account_status = '<span class="label label-sm label-warning"> Pending </span>';
                                                        }


                                        ?>
                                            <tr class="odd gradeX">
												<td> 
													<a href="edit-agent.php?id=<?php echo $washer->id; ?>">Edit</a> 
													<!--a href="view-agent-wash.php?id=<?php //echo $washer->id; ?>">View</a-->
												</td>
                                              <td> <?php echo $washer->id; ?> </td>
 <td> <?php echo $washer->real_washer_id; ?> </td>
                                                <td> <a href="/admin-new/all-orders.php?agent_id=<?php echo $washer->id; ?>" target="_blank"><?php echo $washer->first_name; ?></a> </td>
                                                <td> <a href="/admin-new/all-orders.php?agent_id=<?php echo $washer->id; ?>" target="_blank"><?php echo $washer->last_name; ?></a> </td>
                                                 <td> <?php echo $washer->email; ?> </td>
                                                 <td> <?php echo $washer->phone_number; ?> </td>
                                                 <td> <?php echo $washer->phone_verify_code; ?> </td>
                                                   <td> <?php echo $washer->city; ?> </td>
 <td> <?php if(strtotime($washer->insurance_exp_date) > 0) echo date('m-d-Y', strtotime($washer->insurance_exp_date)); ?> </td>

<td> <?php echo $washer->rating; ?> </td>
<td> <?php echo $washer->care_rating; ?>% </td>
<td> <?php echo $washer->total_wash; ?> </td>
<td> <?php echo $washer->bt_submerchant_id; ?> </td>
                                              <td> <?php echo $washer->status; ?> </td>

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

<script>
$(function(){
$(document).on( 'click', '.all-agents-logout', function(){
var th = $(this);
var r = confirm('Are you sure you want to logout all agents?');
if (r == true) {
$(th).html('Logging out...');
$.getJSON( "http://www.devmobilewash.com/api/index.php?r=agents/allagentslogout", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
//console.log(data);
if(data.result == 'true'){
window.location.href="http://www.devmobilewash.com/admin-new/manage-agents.php?action=allaglogout-success";
}
if(data.result == 'false'){
window.location.href="http://www.devmobilewash.com/admin-new/manage-agents.php?action=allaglogout-error";
}

});

}
return false;
});

$(".page-content .washer-type option").filter(function() {
        return !this.value || $.trim(this.value).length == 0;
    }).remove();

$(".page-content .washer-type").change(function(){
window.location.href=$(this).val();
});


});
</script>