<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=users/getusertypebytoken");
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
$.fn.dataTable.moment( 'MM-DD-YYYY hh:mm A' );
            $('#example1, #example2').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
	//order: [[ 8, 'desc' ]],
	"aaSorting": []
} );

        });
        </script>
 <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php
    $url = 'http://www.devmobilewash.com/api/index.php?r=PhoneOrders/merge_all_orders';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$orders_response = $jsondata->response;
$orders_result_code = $jsondata->result;
$mw_all_orders = $jsondata->orders;
/* echo"<pre>";print_r($mw_all_orders);echo"</pre>"; */
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
                                        <span class="caption-subject bold uppercase"> All Orders</span> <!--a href="add-phone-order.php" style="margin-left: 15px; font-size: 16px; font-weight: bold;">+ Add New Order</a-->
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
									<?php if($_GET['action'] == 'add-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order added successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'update-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order updated successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'cancel-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order #<?php echo $_GET['nid']; ?> cancelled successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'cancel-error'): ?>
									<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in cancelling order. Please try again.</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if($orders_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
												 <th style="min-width: 140px;"> Actions </th>
                                                <th> ID </th>
												<th> Status </th>
												<!--th> Assigned Detailer </th-->
                                                <th> Customer Name </th>
                                                <th> Customer Email </th>
                                                <th> Phone </th>
                                                <th> Address </th>
                                                <!--th> City </th-->
                                                <th> Schedule Datetime </th>
                                                <th> Regular Vehicles </th>
                                                <th> Classic Vehicles </th>
												<th> Created Date </th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($mw_all_orders as $order){ 
						
						//if($order->id == 1285){echo"<pre>";print_r($order);echo"</pre>";} 
						
						?>
                <tr class="odd gradeX">
					<?php 
						$editurl ='';
						if($order->type == 'phone-order') $editurl ='edit-phone-order.php?';
						if($order->type == 'schedule-order') $editurl ='edit-schedule-order.php?';
					
					?>
					<td><a href="<?php echo $editurl; ?>id=<?php echo $order->id; ?>&type=<?php echo $order->type;?>" style="margin-right: 7px;">Edit</a> </td>
                    <td><?php echo $order->id; ?></td>
					<td>
						<?php if($order->type == 'phone-order'){ ?>
						
							<?php if($order->is_cancel): ?>
							<span class="label label-sm label-cancel">Cancelled</span>	
							<?php else: ?>
							<?php if(!$order->checklist): ?>
							<span class="label label-sm label-pending">Pending</span>
							<?php else: ?>
							<?php 
							$checklist_arr = explode("|", $order->checklist);
							if(count($checklist_arr) == 8): ?>
							<span class="label label-sm label-complete">Complete</span>
							<?php else: ?>
							<span class="label label-sm label-process">In Process</span>
							<?php endif; ?>
							<?php endif; ?>
							<?php endif;
						}elseif($order->type == 'schedule-order'){ 
							$checklist_arr = explode("|", $order->checklist); ?>
							<?php if($order->status == 5 || $order->status == 6): ?>
							<span class="label label-sm label-cancel">Cancelled</span>
							<?php elseif(!$order->checklist): ?>
							<span class="label label-sm label-pending">Pending</span>
							<?php elseif(count($checklist_arr) == 10): ?>
							<span class="label label-sm label-complete">Completed</span>
							<?php elseif((count($checklist_arr) < 10 && count($checklist_arr) > 0)): ?>
							<span class="label label-sm label-process">In Process</span>
							<?php endif; 
						}?>
						
					</td>
					<!--td><?php //echo $order->detailer; ?></td-->
                    <td><?php echo $order->customer_name; ?></td>
                    <td><?php echo $order->customer_email; ?></td>
                    <td><?php echo $order->customer_phoneno; ?></td>
                    <td><?php echo $order->address; ?></td>
                    <!--td><?php //echo $order->city; ?></td-->
					<td><?php echo date('m-d-Y',strtotime($order->schedule_date))." ".date('h:i A', strtotime($order->schedule_time)); ?></td>
                     <td><?php
						if($order->type == 'phone-order'){
							if( empty($order->vehicles->regular_vehicles)){ echo "N/A";}
							else{
								//if($order->vehicles->regular_vehicles){
									$all_reg = explode("|",$order->vehicles->regular_vehicles);
									echo "<ol style='padding-left: 15px;'>";
									foreach($all_reg as $reg){
										$reg_detail = explode(",",$reg);
										//echo $reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].")<br>";
										echo "<li style='margin-bottom: 10px;'>".$reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].	")</li>";
									}
									echo "</ol>";
								//}
							}
						}elseif($order->type == 'schedule-order'){ 
							if(!empty($order->vehicles)){
								$na = "N/A";
								$info = array();
								$carInfo_exp = explode('|',$order->scheduled_cars_info);
								foreach($carInfo_exp as $info){
									$info = explode(",",$info);
								}
								if (in_array("regular", $info)){
									echo "<ol style='padding-left: 15px;'>";
									foreach($order->vehicles as $car){
										echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.	")</li>";
									}
									echo "</ol>";
									$na='';
								}
								
								echo $na;
							}
						}
						?>
					</td>
					<td><?php

						if($order->type == 'phone-order'){
							if( empty($order->vehicles->classic_vehicles)){ echo "N/A";}
							else{
								//if($order->vehicles->classic_vehicles){
									$all_cla = explode("|",$order->vehicles->classic_vehicles);
									echo "<ol style='padding-left: 15px;'>";
									foreach($all_cla as $cla){
										$cla_detail = explode(",",$cla);
										//echo $cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].")<br>";
										echo "<li style='margin-bottom: 10px;'>".$cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].	")</li>";
									}
									echo "</ol>";
								//}
							}
						}
						elseif($order->type == 'schedule-order'){ 
							if(!empty($order->vehicles)){
								$na = "N/A";
								$info = array();
								$carInfo_exp = explode('|',$order->scheduled_cars_info);
								
								foreach($carInfo_exp as $info){
									$info = explode(",",$info);
								}
									
								if (in_array("classic", $info)){
									echo "<ol style='padding-left: 15px;'>";	
									foreach($order->vehicles as $car){
										$li[]= "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.	")</li>";
									}
									
									echo "</ol>";
									$na = '';
								} 
									
								
								echo $na;
							}
						}?>	
					</td>
					
					<td><?php echo date('m-d-Y',strtotime($order->created_date))." ".date('h:i A', strtotime($order->created_date)); ?></td>
					


                </tr>
                <?php } ?>
                                      </tbody>
                                    </table>
                                    <?php  } ?>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->

                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Appointments </span>
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                    <div class="actions">
                                         
                                    </div>

                                </div>
                                <div class="portlet-body">
                                 <?php if($s_orders_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>

                                                <th> ID </th>
<th> Customer ID </th>
                                                <th> Name </th>
 <th> Email </th>
                                                <th> Phone </th>
                                                <th> Address </th>
                                              
 <th> Zipcode </th>
                                                <th> Schedule Datetime </th>
                                            
                                                <th>Vehicles </th>
        <th>Total Price </th>
<th>Transaction ID </th>
 <th> Created Date </th>
                                                <th style="min-width: 140px;"> Actions </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($s_mw_all_orders as $order){ ?>
                <tr class="odd gradeX">

                    <td><?php echo $order->id; ?></td>
<td><?php echo $order->customer_id; ?></td>
                    <td><?php echo $order->name; ?></td>
 <td><?php echo $order->email; ?></td>
                    <td><?php echo $order->phone; ?></td>
                    <td><?php echo $order->address." (".$order->address_type.")"; ?></td>
                   
<td><?php echo $order->zipcode; ?></td>
<td><?php $order->schedule_date." ".$order->schedule_time; ?></td>
  <td><?php 
echo "<ol style='padding-left: 15px;'>";
$all_cars = explode("|", $order->vehicles);
foreach($all_cars as $car){
$car_detail = explode(",", $car);
$car_total = $car_detail[4]+$car_detail[5]+$car_detail[6];
echo "<li style='margin-bottom: 10px;'>".$car_detail[0]." ".$car_detail[1]." (".$car_detail[2].",".$car_detail[3];
if($car_detail[5] == 5) echo ", Pet Hair";
if($car_detail[6] == 5) echo ", Lifted Truck";
echo ") $".$car_total."</li>";

}
echo "</ol>";

?></td> 
 <td><?php echo "$".$order->total_price; ?></td> 
<td><?php echo $order->transaction_id; ?></td>     
 <td><?php echo $order->created_date; ?></td>
<td><a href="#" class="appt-delete-order" data-id="<?php echo $order->id; ?>">Delete</a></td>


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
$(document).on( 'click', '.delete-order', function(){
var th = $(this);
id = $(this).data('id');
var r = confirm('Are you sure you want to cancel order #'+id+'?');
if (r == true) {
$(th).html('Cancelling...');
$.getJSON( "http://www.devmobilewash.com/api/index.php?r=PhoneOrders/editorder", {id: id, is_cancel: 1, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="http://www.devmobilewash.com/admin-new/phone-orders.php?action=cancel-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="http://www.devmobilewash.com/admin-new/phone-orders.php?action=cancel-error";
}

});

}
return false;
});


});
</script>