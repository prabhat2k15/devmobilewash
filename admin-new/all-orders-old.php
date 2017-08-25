<?php include('header.php') ?>
<?php
    if($company_module_permission == 'no' || $checked_manage_order == ''){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/index.php"</script><?php
    }
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#app_order').dataTable( {
				"pageLength": 20,
				  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
			} );
			$('#phone_order').dataTable( {
				"pageLength": 20,
				  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
			} );
			$('#schedule_order').dataTable( {
				"pageLength": 20,
				  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
			} );
            
        });
        </script>
<?php include('right-sidebar.php') ?>
<?php 
	
	$day = $_event = '';
	if( isset($_GET['alordday']) && !empty( $_GET['event'] ) ){
		$day = $_GET['alordday'];
		$_event = $_GET['event'];
	}
	
	$url = 'http://www.devmobilewash.com/api/index.php?r=washing/vieworder/';
        
	$handle = curl_init($url);
	$data = array('day'=>$day,'event'=>$_event, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($handle);
	curl_close($handle);
	$jsondata = json_decode($result);
	$response = $jsondata->response;
	$result_code = $jsondata->result;
	
	
	/* PHONE ORDERS */
	$url_phone = 'http://www.devmobilewash.com/api/index.php?r=PhoneOrders/getallorders';

    $handle_phone = curl_init($url_phone);
	$data_phone = array('day'=>$day,'event'=>$_event, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	/* $data = array('day'=>$day); */
	curl_setopt($handle_phone, CURLOPT_POST, true);
	curl_setopt($handle_phone, CURLOPT_POSTFIELDS,  http_build_query($data_phone));
	curl_setopt($handle_phone,CURLOPT_RETURNTRANSFER,1);
	$result_phone = curl_exec($handle_phone);
	curl_close($handle_phone);
	$jsondata_phone = json_decode($result_phone);
	$orders_response = $jsondata_phone->response;
	$orders_result_code = $jsondata_phone->result;
	$phone_orders_status = $jsondata_phone->status;
	$mw_all_orders = $jsondata_phone->orders;
	
	/* SCHEDULE ORDER */

	$url_sch = 'http://www.devmobilewash.com/api/index.php?r=washing/getschedulewashrequests';

	$handle_sch = curl_init($url_sch);
	$data_sch = array('day'=>$day,'event'=>$_event, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	curl_setopt($handle_sch, CURLOPT_POST, true);
	curl_setopt($handle_sch, CURLOPT_POSTFIELDS, $data_sch);
	curl_setopt($handle_sch,CURLOPT_RETURNTRANSFER,1);
	$result_sch = curl_exec($handle_sch);
	curl_close($handle_sch);
	$jsondata_sch = json_decode($result_sch);
	$s_orders_response = $jsondata_sch->response;
	$s_orders_result_code = $jsondata_sch->result;
	$s_mw_all_orders = $jsondata_sch->schedule_wash_requests;
?>
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
                                        <span class="caption-subject bold uppercase"> Managed Orders</span>
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime_app" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="app_order">
                                        <thead>
                                            <tr>
                                                <th style="display: <?php echo $edit_company; ?>"> &nbsp; </th>
                                                <th> Status </th>
                                                <th> OrderID </th>
                                                
                                                <th> Agent </th>
                                                <th> Client </th>
                                                <th> Address </th>
                                                <th> Address Type </th>
                             
                                                <th> Total </th>
                                                <th> Net Total </th>
                                                <th> Total Discount </th>
                                             
                                                <th> Created Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        function is_iterable($var){
                                            return $var !== null 
                                                && (is_array($var) 
                                                    || $var instanceof Traversable 
                                                    || $var instanceof Iterator 
                                                    || $var instanceof IteratorAggregate
                                                    );
                                        }
										
                                        foreach($jsondata as $response){
                                            
											if (is_iterable($response)){
                                            foreach($response as $responseagents){
                                                if($responseagents->status == 'Complete'){
                                                   $time = 'N/A';
                                                   $image =  '<span class="label label-sm label-online">Complete</span>';
                                                   }
                                               elseif($responseagents->status == 'Pending'){
                                                       $time = (int)$responseagents->time;
                                                       $h = floor (($time - $d * 1440) / 60);
                                                       $m = $time - ($d * 1440) - ($h * 60);

                                                       $newtime = $h.'h' .$m.'m';
                                                       $image = '<span class="label label-sm label-offline">Pending</span>';
                                               }
                                               elseif($responseagents->status == 'Processing'){
                                                        $time = 'N/A';
                                                        $image = '<span class="label label-sm label-busy">Processing</span>';  
                                               }
                                               else{
                                                   $image = '';
                                               }
                                               $time = $responseagents->created_date;
                                               $createtime = explode(' ', $time);
                                               $created_date = $createtime[0];
                                               $date_created = explode('-', $created_date);
                                               $created_year = $date_created[0];
                                               $created_month = $date_created[1];
                                               $created_day = $date_created[2];
                                               $craeteddate = $created_month.'-'.$created_day.'-'.$created_year;
                                               $created_time = $createtime[1];
                                               $time_created = explode(':', $created_time);
                                               $created_h = $time_created[0];
                                               $created_m = $time_created[1];
                                               if (!function_exists('time24to12')){
                                   
                                               function time24to12($h24, $min) {
                                                    if ($h24 === 0) { $newhour = 12; }
                                                        elseif ($h24 <= 12) { $newhour = $h24; }
                                                        elseif ($h24 > 12) { $newhour = $h24 - 12; }
                                                        return ($h24 < 12) ? $newhour . ":$min am" : $newhour . ":$min pm";
                                                    }
                                                }
                                                $createdtime = time24to12($created_h, $created_m);
                                                $created_date_time = $craeteddate.' '.$createdtime;
                                        
                                        
                                        ?>
                                            <tr class="odd gradeX <?php echo $responseagents->status; if($responseagents->status == 'Pending'){ echo ' flash'; }?>" id='tr_<?php echo $responseagents->orderid; ?>' data-id='<?php echo $responseagents->orderid; ?>'>
                                                <td style="display: <?php echo $edit_company; ?>"><a href="edit-order.php?orderID=<?php echo $responseagents->orderid; ?>"> Edit </a></td>
                                                <td class="<?php if($responseagents->status == 'Pending'){ echo 'Pending'; } ?>  status_<?php echo $responseagents->orderid; ?>" data="<?php if($responseagents->status == 'Pending'){ echo $responseagents->orderid; } ?>"> <?php echo $image; ?> </td>
                                                <td> <?php echo $responseagents->orderid; ?> </td>
                                                
                                                <td> <?php echo $responseagents->agent; ?> </td>
                                                <td> <?php echo $responseagents->client; ?> </td>
                                                <td> <?php echo $responseagents->address; ?> </td>
                                                <td> <?php echo $responseagents->address_type; ?> </td>
                                              
                                                <td> <?php echo $responseagents->total_price; ?> </td>
                                                <td> <?php echo $responseagents->net_price; ?> </td>
                                                <td> <?php echo $responseagents->discount; ?> </td>
                                                
                                                <td> <?php echo $created_date_time; ?> </td>
                                                
                                            </tr>
                                            
                                        <?php 
                                     
                                            }
                                            } 
                                        }
                                        
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
							<div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Call-In Orders</span> 
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime_phone" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
									<?php if($_GET['action'] == 'add-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order added successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'update-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order updated successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'delete-success'): ?>
									<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Order #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
									<?php if($_GET['action'] == 'delete-error'): ?>
									<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting order. Please try again.</span><a href="/admin-new/phone-orders.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
									<?php endif; ?>
                                </div>
                                <div class="portlet-body"><?php 
									if($orders_result_code == 'true'){ ?>
										<table class="table table-striped table-bordered table-hover table-checkable order-column" id="phone_order">
											<thead>
												<tr>

													<th> ID </th>
													<th> Status </th>
													<th> Assigned Detailer </th>
													<th> Customer Name </th>
													<th> Phone </th>
													<th> Address </th>
													<th> City </th>
													<th> Schedule Datetime </th>
													<th> Regular Vehicles </th>
													<th> Classic Vehicles </th>
													<th> Created Date </th>
													<th style="min-width: 140px;"> Actions </th>
												</tr>
											</thead>
											<tbody><?php 
												$count = 0;
												$number=0;
												$number1=0;
												$show=false;
												$flag =false;
												$flag1 =false;

												if($phone_orders_status =='completed') {
													$number =7;	
													$flag =true;
												}
												if($phone_orders_status =='pending') {
													 $number =0;
													 $flag1 =true;												 
													
												}
												if($phone_orders_status =='processing') {
													$number =1;	
													$number1=7;
												}
												if($phone_orders_status =='all') {
													$show=true;
												}

												foreach($mw_all_orders as $order){  
												
													
													$output = '';

													if($order->number >=$number   && $order->number <$number1){
													 
													$output.='<tr class="odd gradeX"><td>';
													$output.=$order->id;
													$output.='</td><td>';
													
													$output.='<span class="label label-sm label-process">In Process</span>';	
													
													
													$output.='</td><td>';
													$output.=$order->detailer;
													$output.='</td><td>';
													$output.=$order->customername; 
													$output.='</td><td>';
													$output.=$order->phoneno;
													$output.='</td><td>';
													$output.=$order->address;
													$output.='</td><td>';
													$output.= $order->city;
													$output.='</td><td>';
													$output.=$order->schedule_date." ".$order->schedule_time; 
													$output.='</td><td>';
													if(!$order->regular_vehicles) 
													$output.='N/A';
													else{
													$all_reg = explode("|",$order->regular_vehicles);
													foreach($all_reg as $reg){
													$reg_detail = explode(",",$reg);
													$output.=$reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													if(!$order->classic_vehicles) 
													$output.='N/A';
													else{
													$all_cla = explode("|",$order->classic_vehicles);
													foreach($all_cla as $cla){
															$cla_detail = explode(",",$cla);
													$output.= $cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													$output.=$order->created_date; 
													$output.='</td><td><a href="edit-phone-order.php?id='.$order->id.'" style="margin-right: 7px;">Edit</a> <a href="#" class="delete-order" data-id="'.$order->id.'">Delete</a></td>
													</tr>';
													}
													else if($order->number ==$number && $flag==true){
													
													 
													  $output.='<tr class="odd gradeX"><td>';
													$output.=$order->id;
													$output.='</td><td>';
													
													
													$output.='<span class="label label-sm label-complete">Completed</span>';	

													
													
													$output.='</td><td>';
													$output.=$order->detailer;
													$output.='</td><td>';
													$output.=$order->customername; 
													$output.='</td><td>';
													$output.=$order->phoneno;
													$output.='</td><td>';
													$output.=$order->address;
													$output.='</td><td>';
													$output.= $order->city;
													$output.='</td><td>';
													$output.=$order->schedule_date." ".$order->schedule_time; 
													$output.='</td><td>';
													if(!$order->regular_vehicles) 
													$output.='N/A';
													else{
													$all_reg = explode("|",$order->regular_vehicles);
													foreach($all_reg as $reg){
													$reg_detail = explode(",",$reg);
													$output.=$reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													if(!$order->classic_vehicles) 
													$output.='N/A';
													else{
													$all_cla = explode("|",$order->classic_vehicles);
													foreach($all_cla as $cla){
															$cla_detail = explode(",",$cla);
													$output.= $cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													$output.=$order->created_date; 
													$output.='</td><td><a href="edit-phone-order.php?id='.$order->id.'" style="margin-right: 7px;">Edit</a> <a href="#" class="delete-order" data-id="'.$order->id.'">Delete</a></td>
													</tr>';
													}
													else if($order->number ==$number && $flag1==true){
													  
													$output.='<tr class="odd gradeX"><td>';
													$output.=$order->id;
													$output.='</td><td>';
													
													$output.='<span class="label label-sm label-pending">Pending</span>';
													
													$output.='</td><td>';
													$output.=$order->detailer;
													$output.='</td><td>';
													$output.=$order->customername; 
													$output.='</td><td>';
													$output.=$order->phoneno;
													$output.='</td><td>';
													$output.=$order->address;
													$output.='</td><td>';
													$output.= $order->city;
													$output.='</td><td>';
													$output.=$order->schedule_date." ".$order->schedule_time; 
													$output.='</td><td>';
													if(!$order->regular_vehicles) 
													$output.='N/A';
													else{
													$all_reg = explode("|",$order->regular_vehicles);
													foreach($all_reg as $reg){
													$reg_detail = explode(",",$reg);
													$output.=$reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													if(!$order->classic_vehicles) 
													$output.='N/A';
													else{
													$all_cla = explode("|",$order->classic_vehicles);
													foreach($all_cla as $cla){
															$cla_detail = explode(",",$cla);
													$output.= $cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													$output.=$order->created_date; 
													$output.='</td><td><a href="edit-phone-order.php?id='.$order->id.'" style="margin-right: 7px;">Edit</a> <a href="#" class="delete-order" data-id="'.$order->id.'">Delete</a></td>
													</tr>';
													}
													
													elseif($show){
													 //echo $output;
													$output.='<tr class="odd gradeX"><td>';
													$output.=$order->id;
													$output.='</td><td>';
													if($order->number < 1){ 
													$output.='<span class="label label-sm label-pending">Pending</span>';
													}else{
													if($order->number == 7){
													$output.='<span class="label label-sm label-complete">Completed</span>';	

													}
													else{ 
													$output.='<span class="label label-sm label-process">In Process</span>';	
													} 
													}
													$output.='</td><td>';
													$output.=$order->detailer;
													$output.='</td><td>';
													$output.=$order->customername; 
													$output.='</td><td>';
													$output.=$order->phoneno;
													$output.='</td><td>';
													$output.=$order->address;
													$output.='</td><td>';
													$output.= $order->city;
													$output.='</td><td>';
													$output.=$order->schedule_date." ".$order->schedule_time; 
													$output.='</td><td>';
													if(!$order->regular_vehicles) 
													$output.='N/A';
													else{
													$all_reg = explode("|",$order->regular_vehicles);
													foreach($all_reg as $reg){
													$reg_detail = explode(",",$reg);
													$output.=$reg_detail[0]." ".$reg_detail[1]." (".$reg_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													if(!$order->classic_vehicles) 
													$output.='N/A';
													else{
													$all_cla = explode("|",$order->classic_vehicles);
													foreach($all_cla as $cla){
															$cla_detail = explode(",",$cla);
													$output.= $cla_detail[0]." ".$cla_detail[1]." (".$cla_detail[2].")<br>";
													}
													}
													$output.='</td><td>';
													$output.=$order->created_date; 
													$output.='</td><td><a href="edit-phone-order.php?id='.$order->id.'" style="margin-right: 7px;">Edit</a> <a href="#" class="delete-order" data-id="'.$order->id.'">Delete</a></td>
													</tr>';
													}
													
													echo $output;
												} ?>
											</tbody>
										</table><?php  
									} ?>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
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
                                         <span id="servertime_schedule" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>

                                </div>
                                <div class="portlet-body"><?php 
									if($s_orders_result_code == 'true'){ ?>
										<table class="table table-striped table-bordered table-hover table-checkable order-column" id="schedule_order">
											<thead>
												<tr>
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
													<th style="min-width: 140px;"> Actions </th>
												</tr>
											</thead>
											<tbody><?php 
												foreach($s_mw_all_orders as $order_sch){ ?>
													<tr class="odd gradeX">
														<td><?php echo $order_sch->id; ?></td>
														<td><?php if($order_sch->status == 0): ?>
															<span class="label label-sm label-pending">Pending</span>
															<?php endif; ?>
															<?php if($order_sch->status == 4): ?>
															<span class="label label-sm label-complete">Completed</span>
															<?php endif; ?>
															<?php if($order_sch->status >= 1 && $order_sch->status < 4): ?>
															<span class="label label-sm label-process">In Process</span>
															<?php endif; ?>
															<?php /* if($order_sch->status == 5 || $order_sch->status == 6): ?>
															<span class="label label-sm label-cancel">Cancelled</span>
															<?php endif; */ ?>
														</td>
														<td><?php echo $order_sch->customer_id; ?></td>
														<td><?php echo $order_sch->customer_name; ?></td>
														<td><?php echo $order_sch->customer_email; ?></td>
														<td><?php echo $order_sch->customer_phoneno; ?></td>
														<td><?php
															if(count($order_sch->agent_details)) echo $order_sch->agent_details->agent_id;
															else echo "N/A";
															?>
														</td>
														<td><?php
															if(count($order_sch->agent_details)) echo $order_sch->agent_details->agent_name;
															else echo "N/A";?>
														</td>
														<td><?php
															if(count($order_sch->agent_details)) echo $order_sch->agent_details->agent_email;
															else echo "N/A";?>
														</td>
														<td><?php
															if(count($order_sch->agent_details)) echo $order_sch->agent_details->agent_phoneno;
															else echo "N/A";?>
														</td>
														<td><?php echo $order_sch->address." (".$order_sch->address_type.")"; ?></td>

														<td><?php echo $order_sch->schedule_date." ".$order_sch->schedule_time; ?></td>
														<td><?php
															if(count($order_sch->vehicles)){
																echo "<ol style='padding-left: 15px;'>";
																foreach($order_sch->vehicles as $car){
																	echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.")</li>";
																}
																echo "</ol>";
															}?>
														</td>
														<td><?php
															if($order_sch->schedule_total) echo "$".$order_sch->schedule_total;
															else echo "N/A";?>
														</td>
														<td><?php echo $order_sch->transaction_id; ?></td>
														<td><?php echo $order_sch->created_date; ?></td>
														<td><a href="edit-schedule-order.php?id=<?php echo $order_sch->id; ?>" class="appt-edit-order" data-id="<?php echo $order_sch->id; ?>" style="margin-right: 7px;">Edit</a></td>
													</tr><?php 
												} ?>
											</tbody>
										</table><?php  
									} ?>
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
