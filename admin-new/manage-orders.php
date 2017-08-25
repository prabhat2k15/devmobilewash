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
            $('#example1').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
  "aaSorting": []
} );

        });
        </script>
<?php include('right-sidebar.php') ?>
<?php //echo $_SERVER['DOCUMENT_ROOT'];

	$day = $_event = '';
	if( isset($_GET['day']) && !empty( $_GET['event'] ) ){
		$day = $_GET['day'];
		$_event = $_GET['event'];
	}

	$url = 'http://www.devmobilewash.com/api/index.php?r=washing/vieworder/';

	$handle = curl_init($url);
	$data = array('day'=>$day,'event'=>$_event, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
if($_GET['customer_id']) $data = array('day'=>$day,'event'=>$_event, 'customer_id' => $_GET['customer_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($handle);
	curl_close($handle);
	$jsondata = json_decode($result);
	$response = $jsondata->response;
	$result_code = $jsondata->result;
	/* echo "<pre>";
	print_r($jsondata);echo "</pre>";
	exit; */
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
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th style="display: <?php echo $edit_company; ?>"> &nbsp; </th>
                                                <th> ID </th>
                                                <th> Order Status </th>
                                                <th> Washer Payment Status </th>
                                                
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
												   //	if($responseagents->wash_request_position =='real'){
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
														
														if($responseagents->washer_payment_status == 1){
														   
														   $image2 =  '<span class="label label-sm label-online">Released</span>';
														}
														elseif($responseagents->washer_payment_status == 3){
														   
														   $image2 =  '<span class="label label-sm label-online">Admin Released</span>';
														}
														elseif(!$responseagents->washer_payment_status){
															 
															   $image2 = '<span class="label label-sm label-offline">Pending</span>';
														}
														elseif($responseagents->washer_payment_status == 2){
															
																$image2 = '<span class="label label-sm label-busy">On Hold</span>';
														}
														else{
														   $image2 = '';
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
														$created_date_time = $craeteddate.' '.$createdtime;?>

														<tr class="odd gradeX <?php echo $responseagents->status; if($responseagents->status == 'Pending'){ echo ' flash'; }?>" id='tr_<?php echo $responseagents->orderid; ?>' data-id='<?php echo $responseagents->orderid; ?>'>
															<td style="display: <?php echo $edit_company; ?>"><a href="edit-order.php?orderID=<?php echo $responseagents->orderid; ?>"> Edit </a></td>
															<td> <?php echo $responseagents->orderid; ?> </td>
															<td class="<?php if($responseagents->status == 'Pending'){ echo 'Pending'; } ?>  status_<?php echo $responseagents->orderid; ?>" data="<?php if($responseagents->status == 'Pending'){ echo $responseagents->orderid; } ?>"> <?php echo $image; ?> </td>
															<td> <?php echo $image2; ?> </td>
															

															<td> <?php echo $responseagents->agent; ?> </td>
															<td> <?php echo $responseagents->client; ?> </td>
															<td> <?php echo $responseagents->address; ?> </td>
															<td> <?php echo $responseagents->address_type; ?> </td>

															<td> <?php echo $responseagents->total_price; ?> </td>
															<td> <?php echo $responseagents->net_price; ?> </td>
															<td> <?php echo $responseagents->discount; ?> </td>

															<td> <?php echo date('H:i A',strtotime($createdtime)).' '.$created_day.'-'.$created_month.'-'.$created_year; ?></td>
														</tr><?php

												   //	}
												}
                                            }
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
