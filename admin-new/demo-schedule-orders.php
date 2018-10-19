<?php include('header.php') ?>
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
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#example1, #example2').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
 "aaSorting": []
} );

        });
        </script>

<?php include('right-sidebar.php') ?>

<?php


	/* $day = $_event = '';
	if( isset($_GET['day']) && !empty( $_GET['event'] ) ){
		$day = $_GET['day'];
		//$_event = $_GET['event'];
	} */



/* SCHEDULE ORDER */

$day = $_event = '';
if( isset($_GET['sday']) && !empty( $_GET['sday'] ) ){
	$day = $_GET['sday'];
	$_event = $_GET['event'];
}

$url = ROOT_URL.'/api/index.php?r=washing/getschedulewashrequests';

$handle = curl_init($url);
$data = array('day'=>$day,'event'=>$_event, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$s_orders_response = $jsondata->response;
$s_orders_result_code = $jsondata->result;
$s_mw_all_orders = $jsondata->schedule_wash_requests;
/* echo"<pre>";print_r($s_mw_all_orders);echo"</pre>";die; */
?>
<style>
.label-complete {
    background-color: #16CE0C !important;
}

.label-pending {
    background-color: red !important;
}

.label-process {
    background-color: #FF8C00 !important;
}

.label-cancel {
    background-color: #999 !important;
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
                                        <span class="caption-subject bold uppercase"> Schedule Orders </span>
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                   <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>

                                </div>
                                <div class="portlet-body">
                                 <?php if($s_orders_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
												<th style="min-width: 140px;"> Actions </th>
                                                <th> ID </th>
                                                <th> Status </th>
<th> Customer ID </th>
                                                <th> Customer Name </th>
 <th> Customer Email </th>
                                                <th> Customer Phone </th>
<th> Agent ID </th>
                                                <th> Agent Name </th>
 <th> Agent Email </th>
                                                <th> Agent Phone </th>
                                                <th style='min-width: 210px;'> Address </th>


                                                <th> Schedule Datetime </th>

                                                <th>Vehicles </th>
        <th>Total Price </th>
<th>Transaction ID </th>
 <th> Created Date </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($s_mw_all_orders as $order){ 
							if($order->wash_request_position != 'real'){
				   ?>
                <tr class="odd gradeX">
					<td><a href="edit-schedule-order.php?id=<?php echo $order->id; ?>" class="appt-edit-order" data-id="<?php echo $order->id; ?>" style="margin-right: 7px;">Edit</a></td>
                    <td><?php echo $order->id; ?></td>
                    <td>
                    <?php if($order->status == 0): ?>
<span class="label label-sm label-pending">Pending</span>
<?php endif; ?>
<?php if($order->status == 4): ?>
<span class="label label-sm label-complete">Completed</span>
<?php endif; ?>
<?php if($order->status >= 1 && $order->status < 4): ?>
<span class="label label-sm label-process">In Process</span>
<?php endif; ?>
<?php if($order->status == 5 || $order->status == 6): ?>
<span class="label label-sm label-cancel">Cancelled</span>
<?php endif; ?>
                    </td>
<td><?php echo $order->customer_id; ?></td>
                    <td><?php echo $order->customer_name; ?></td>
 <td><?php echo $order->customer_email; ?></td>
                    <td><?php echo $order->customer_phoneno; ?></td>
<td><?php
if(count($order->agent_details)) echo $order->agent_details->agent_id;
else echo "N/A";
?>
</td>
<td><?php
if(count($order->agent_details)) echo $order->agent_details->agent_name;
else echo "N/A";
?>
</td>
<td><?php
if(count($order->agent_details)) echo $order->agent_details->agent_email;
else echo "N/A";
?>
</td>
<td><?php
if(count($order->agent_details)) echo $order->agent_details->agent_phoneno;
else echo "N/A";
?>
</td>
                    <td><?php echo $order->address." (".$order->address_type.")"; ?></td>

<td><?php echo date('H:i A', strtotime($order->schedule_time)).' '.date('d-m-Y',strtotime($order->schedule_date)); ?></td>
  <td><?php
  if(count($order->vehicles)){
echo "<ol style='padding-left: 15px;'>";
foreach($order->vehicles as $car){
echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.")</li>";
}
echo "</ol>";
}

?></td>
 <td><?php
if($order->schedule_total) echo "$".$order->schedule_total;
else echo "N/A";
?></td>
<td><?php echo $order->transaction_id; ?></td>
<td><?php echo date('H:i A',strtotime($order->created_date)).' '.date('d-m-Y',strtotime($order->created_date)); ?></td>



                </tr>
							<?php } } ?>
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
$(document).on( 'click', '.delete-order', function(){
var th = $(this);
id = $(this).data('id');
var r = confirm('Are you sure you want to delete order #'+id+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=PhoneOrders/deleteorder", {id: id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-error";
}

});

}
return false;
});

$(document).on( 'click', '.appt-delete-order', function(){
var th = $(this);
id = $(this).data('id');
var r = confirm('Are you sure you want to delete order #'+id+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=ScheduleOrders/deleteorder", {id: id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?action=delete-error";
}

});

}
return false;
});

});
</script>