<?php include('header.php') ?>
<?php
$voice_print = '';
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->





 <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>
<?php


	/* $day = $_event = '';
	if( isset($_GET['day']) && !empty( $_GET['event'] ) ){
		$day = $_GET['day'];
		//$_event = $_GET['event'];
	} */



/* SCHEDULE ORDER */

$day = $_event = '';
if( isset($_GET['day']) && !empty( $_GET['day'] ) ){
	$day = $_GET['day'];
	$_event = $_GET['event'];
}

$url = ROOT_URL.'/api/index.php?r=site/getpaymentreports';
$cust_id = 0;
$agent_id = 0;
$page_number = 1;
if(isset($_GET['customer_id'])) $cust_id = $_GET['customer_id'];
if(isset($_GET['agent_id'])) $agent_id = $_GET['agent_id'];
if(isset($_GET['page_number'])) $page_number = $_GET['page_number'];
$handle = curl_init($url);
$data = array('customer_id' => $cust_id, 'agent_id' => $agent_id, 'page_number' => $page_number, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$s_orders_response = $jsondata->response;
$s_orders_result_code = $jsondata->result;
$s_mw_all_orders = $jsondata->wash_requests;
//echo"<pre>";print_r($s_mw_all_orders);echo"</pre>";die; 
$pending_order_count = '';

 $cust_avg_order_frequency = $jsondata->cust_avg_order_frequency;
 $total_pages = $jsondata->total_pages;
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

.label-enroute {
    background-color: #00BCD4 !important;
}

.label-cancel {
    background-color: #999 !important;
}

.preloader{
    background: rgba(255, 255, 255, .9);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999999;
}

.loader {
  border: 10px solid #ccc;
  border-radius: 50%;
  border-top: 10px solid #0880e6;
  width: 80px;
  height: 80px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  position: absolute;
  top: 50%;
  margin-top: -40px;
  left: 50%;
  margin-left: -40px;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

 @-webkit-keyframes glowing {
  0% { background-color: #fff; }
  50% { background-color: red;  }
  100% { background-color: #fff; }
}

@-moz-keyframes glowing {
 0% { background-color: #fff; }
  50% { background-color: red;  }
  100% { background-color: #fff; }
}

@-o-keyframes glowing {
 0% { background-color: #fff; }
  50% { background-color: red;  }
  100% { background-color: #fff; }
}

@keyframes glowing {
  0% { background-color: #fff; }
  50% { background-color: red;  }
  100% { background-color: #fff; }
}


 @-webkit-keyframes glowing2 {
  0% { background-color: #fff; }
  50% { background-color: #FFA500;  }
  100% { background-color: #fff; }
}

@-moz-keyframes glowing2 {
 0% { background-color: #fff; }
  50% { background-color: #FFA500;  }
  100% { background-color: #fff; }
}

@-o-keyframes glowing2 {
 0% { background-color: #fff; }
  50% { background-color: #FFA500;  }
  100% { background-color: #fff; }
}

@keyframes glowing2 {
  0% { background-color: #fff; }
  50% { background-color: #FFA500;  }
  100% { background-color: #fff; }
}


 @-webkit-keyframes glowing3 {
  0% { background-color: #fff; }
  50% { background-color: #FF1493;  }
  100% { background-color: #fff; }
}

@-moz-keyframes glowing3 {
 0% { background-color: #fff; }
  50% { background-color: #FF1493;  }
  100% { background-color: #fff; }
}

@-o-keyframes glowing3 {
 0% { background-color: #fff; }
  50% { background-color: #FF1493;  }
  100% { background-color: #fff; }
}

@keyframes glowing3 {
  0% { background-color: #fff; }
  50% { background-color: #FF1493;  }
  100% { background-color: #fff; }
}


 @-webkit-keyframes glowing4 {
  0% { background-color: #fff; }
  20% { background-color: red;  }
  40% { background-color: #fff; }
  60% { background-color: #FF1493;  }
  80% { background-color: #fff; }
  100% { background-color: red; }
}

@-moz-keyframes glowing4 {
  0% { background-color: #fff; }
  20% { background-color: red;  }
  40% { background-color: #fff; }
  60% { background-color: #FF1493;  }
  80% { background-color: #fff; }
  100% { background-color: red; }
}

@-o-keyframes glowing4 {
 0% { background-color: #fff; }
  20% { background-color: red;  }
  40% { background-color: #fff; }
  60% { background-color: #FF1493;  }
  80% { background-color: #fff; }
  100% { background-color: red; }
}

@keyframes glowing4 {
  0% { background-color: #fff; }
  20% { background-color: red;  }
  40% { background-color: #fff; }
  60% { background-color: #FF1493;  }
  80% { background-color: #fff; }
  100% { background-color: red; }
}


  .flashrow{
   -webkit-animation: glowing 1500ms infinite;
        -moz-animation: glowing 1500ms infinite;
        -o-animation: glowing 1500ms infinite;
        animation: glowing 1500ms infinite;
}

.flashrow td{
    border: 0 !important;

}

  .flashrownotarrive{
   -webkit-animation: glowing2 1500ms infinite;
        -moz-animation: glowing2 1500ms infinite;
        -o-animation: glowing2 1500ms infinite;
        animation: glowing2 1500ms infinite;
}

.flashrownotarrive td{
    border: 0 !important;

}

  .flashrowdeclined{
   -webkit-animation: glowing3 1500ms infinite;
        -moz-animation: glowing3 1500ms infinite;
        -o-animation: glowing3 1500ms infinite;
        animation: glowing3 1500ms infinite;
}

.flashrowdeclined td{
    border: 0 !important;

}

  .flashrowdeclinednotarrive{
   -webkit-animation: glowing4 1500ms infinite;
        -moz-animation: glowing4 1500ms infinite;
        -o-animation: glowing4 1500ms infinite;
        animation: glowing4 1500ms infinite;
}

.flashrowdeclinednotarrive td{
    border: 0 !important;

}


.spec-orders{
  width: 600px;
    height: auto;
    max-height: 100px;
    position: fixed;
    background: rgba(234, 0, 85, 0.84);
    z-index: 999;
    top: 65px;
    left: 50%;
    margin-left: -300px;
    color: #fff;
    padding: 20px;
    padding-top: 0;
    box-sizing: border-box;
    overflow: auto;
    display: none;
}

.spec-orders p{
    margin-top: 20px;
    margin-bottom: 0;
}

.spec-orders p a{
   margin-left: 10px;
    color: #fff;
    text-decoration: underline;
}

.dataTables_info{
	display: none !important;
}

.load-more{
	display: block;
    text-align: center;
    background: #337ab7;
    padding: 15px;
    color: #fff;
    font-size: 20px;
    text-decoration: none !important;
    width: 300px;
    margin: 10px auto;
    color: #fff !important;
}

</style>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="spec-orders"></div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Payment Reports </span>
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
                        <!-- <div class="table-scrollable">  -->                          
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                <thead>
                                    <tr>
										<th> Actions </th>
                                        <th> ID </th>
                                        <th> Order Type </th>
                                        <th> Status </th>
                                        <th> Payment </th>
                                        <th> Transaction ID </th>
					<th> Braintree Status </th>
					<th> Payment Processed </th>
				                        <th> Customer Name </th>
				                        <th> Customer Phone </th>
                                        
										<th> Agent Name </th>
                                        <th> Agent Phone </th>
					<th> Agent Submerchant ID </th>
                                        <th style='min-width: 115px;'> Address </th>
                                        <th> Schedule Datetime </th>
                                        
                                        <th> Vehicles </th>
    						            <th> Total Price </th>
                                        <th> Net Price </th>
										<th> Company Total </th>
                                        <th> Agent Total </th>
                                        <th> Bundle Discount </th>
                                        <th> Fifth Wash Discount </th>
                                        <!-- <th> First Wash Discount </th> -->
                                        <th> Coupon Discount </th>
                                        <th> Coupon Code </th>
                                        
                                        <th> Company Discount </th>
										<th> Tip Amount </th>
										<th> Created Date </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($s_mw_all_orders as $ind=>$order){ ?>
                                    <tr class="odd gradeX <?php if($ind == 0) echo "flashro";?>" id="order-<?php echo $order->id; ?>">
  					                    <td>
  					                        <a href="edit-order.php?id=<?php echo $order->id; ?>" class="appt-edit-order" data-id="<?php echo $order->id; ?>" style="margin-right: 7px;">Edit</a>
                      					    <!--&nbsp;
                      					    <a href="view_order.php?id=<?php echo $order->id; ?>" class="appt-edit-order" data-id="<?php echo $order->id; ?>" style="margin-right: 7px;">View</a> -->
                      					</td>
                                        <td><?php echo $order->id; ?></td>
                                        <td><?php if($order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #0046ff !important;">Scheduled</span></p><?php endif; ?><?php if(!$order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #009688 !important;">On-Demand</span></p><?php endif; ?></td>
                                        <td>                   
                                            <?php if($order->status == 5 || $order->status == 6): ?>
                                            <span class="label label-sm label-cancel">Cancelled</span>
                                            <?php elseif(!$order->status): ?>
                                            <span class="label label-sm label-pending">Pending</span>
                                            <?php elseif($order->status == 1): ?>
                                            <span class="label label-sm label-enroute">En Route</span>
                                            <?php elseif($order->status == 2): ?>
                                            <span class="label label-sm label-process">Arrived</span>
                                            <?php elseif($order->status == 3): ?>
                                            <span class="label label-sm label-process">In Process</span>
                                            <?php elseif($order->status == 4): ?>
                                            <span class="label label-sm label-complete">Completed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(($order->payment_status == 'Declined') || ($order->payment_status == 'Check Fraud')) echo"<span class='label label-sm label-pending'>".$order->payment_status."</span><br><br>";
                                            else echo $order->payment_status; ?>
                                            <?php if($order->payment_type == 'free'): ?>
                                            <span class="label label-sm label-complete">Free Wash</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $order->transaction_id; ?></td>
					<td><?php echo $order->transaction_status; ?></td>
					 <td>                   
                                            <?php if(($order->transaction_status == 'authorized') || ($order->transaction_status == 'submitted_for_settlement') || ($order->transaction_status == 'settling') || ($order->transaction_status == 'settled')): ?>
                                            <span class="label label-sm label-complete">Yes</span>
					    <?php else: ?>
					    <span class="label label-sm label-pending">No</span>
					    <?php endif; ?>
                                            
                                        </td>
                                        <td><a target="_blank" href="/admin-new/all-orders.php?customer_id=<?php echo $order->customer_id; ?>"><?php echo $order->customer_name; ?></a></td>
                                        <td><?php echo $order->customer_phoneno; ?></td>
                                        
                                        <!--td><?php /*
                                        if(count($order->agent_details)) echo $order->agent_details->agent_id;
                                        else echo "N/A"; */
                                        ?>
                                        </td-->
                                        <td><?php
                                        if(count($order->agent_details)) echo "<a target='_blank' href='/admin-new/all-orders.php?agent_id=".$order->agent_details->agent_id."'>".$order->agent_details->agent_name."</a>";
                                        else echo "N/A";
                                        ?>
                                        </td>
                                        <!--td><?php
                                        /* if(count($order->agent_details)) echo $order->agent_details->agent_email;
                                        else echo "N/A"; */
                                        ?>
                                        </td-->
                                        <td><?php
                                        if(count($order->agent_details)) echo $order->agent_details->agent_phoneno;
                                        else echo "N/A";
                                        ?>
                                        </td>
					<td><?php
					 if($order->agent_details->agent_submerchant_id) echo $order->agent_details->agent_submerchant_id;
                                        else echo "N/A";
                                        ?>
                                        </td>
                                        <td><?php echo $order->address." (".$order->address_type.")"; ?></td>
                                        <td>
                                            <?php if($order->is_scheduled): ?>
                                        <?php if(strtotime($order->reschedule_date) > 0): ?>

                                        <span style="color: red; font-weight: bold; font-size: 13px;"><?php echo $order->reschedule_date." ".$order->reschedule_time; ?></span><p style="text-align: center; font-weight: bold; color: red; margin: 5px 0;">Re-Scheduled</p>
                                        <?php endif; ?>
                                        <?php echo $order->schedule_date." ".$order->schedule_time; ?>
                                        <?php else: ?>
                                        N/A
                                        <?php endif; ?>
                                        </td>
                                       
                                        <td><?php if(count($order->vehicles)){
                                        echo "<ol style='padding-left: 15px;'>";
                                        foreach($order->vehicles as $car){
                                        echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.")";
                                        if($car->addons) echo " - Addons: ".$car->addons;
                                        echo "</li>";
                                        }
                                        echo "</ol>";
                                        }

                                        ?></td>
                                         <!--td><?php
                                        /* if($order->schedule_total) echo "$".$order->schedule_total;
                                        else echo "N/A"; */
                                        ?></td-->
                                        <!--td><?php //echo $order->transaction_id; ?></td-->
                                        <td>$<?php echo number_format($order->total_price, 2); ?></td>
                                        <td>$<?php echo number_format($order->net_price, 2); ?></td>
                                        <td>$<?php echo number_format($order->company_total, 2); ?></td>
                                        <td>$<?php echo number_format($order->agent_total, 2); ?></td>
                                        <td>$<?php echo number_format($order->bundle_discount, 2); ?></td>
                                        <td>$<?php echo number_format($order->fifth_wash_discount, 2); ?></td>
                                        
                                        <td>$<?php if(!$order->coupon_discount) {echo "0.00";} else {echo number_format($order->coupon_discount, 2);} ?></td>
                                        <td><?php echo $order->coupon_code; ?></td>
                                        
                                        <td>$<?php echo number_format($order->company_discount, 2); ?></td>
                                        <td>$<?php echo number_format($order->tip_amount, 2); ?></td>
                                        <td><?php echo $order->created_date; ?></td>
                                    </tr>
					            <?php } ?>
                                </tbody>
                            </table>
                        <!-- </div> -->
                        <a href="#" class="load-more">Load More</a>
                        <?php } ?>
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
<!--<script src='js/jquery.voicerss-tts.min.js'></script>-->
<script>
    /*
        $.speech({
        key: '571c40dafcda4d3fabdca18e960c9198',
        src: "<?php echo $voice_print; ?>",
        hl: 'en-us',
        r: -2, 
        c: 'mp3',
        f: '44khz_16bit_stereo',
        ssml: false
    });
    */
</script>
<script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    var dt_table;
    var page_number = 2;
    var total_pages = "<?php echo $total_pages; ?>";
    console.log(total_pages);
    $(document).ready(function(){
        $.fn.dataTable.moment( 'YYYY-MM-DD hh:mm A' );
        $.fn.dataTableExt.oSort['nullable-asc'] = function(a,b) {
            if (a == '-')
                return 1;
            else if (b == '-')
                return -1;
            else
            {
                var ia = parseInt(a);
                var ib = parseInt(b);
                return (ia<ib) ? -1 : ((ia > ib) ? 1 : 0);
            }
        }

        $.fn.dataTableExt.oSort['nullable-desc'] = function(a,b) {
            if (a == '-')
                return 1;
            else if (b == '-')
                return -1;
            else
            {
                var ia = parseInt(a);
                var ib = parseInt(b);
                return (ia>ib) ? -1 : ((ia < ib) ? 1 : 0);
            }
        }

        dt_table = $('#example1, #example2').dataTable( {
            "pageLength": 20,
            "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
             order: [[ 1, "desc" ]],
	    "bPaginate": false,
        } );
    });
</script>
<script>
  

    $(function(){
 
$(".preloader").remove();


$(".load-more").click(function(){
	var th = $(this);
	$(this).removeClass('.load-more');
	$(this).html('Loading...');
  $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/getpaymentreports&page_number="+page_number+"&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4", function( data ) {
    
if(data.result == 'true'){
	page_number++;
	if (page_number > total_pages) {
		$('.load-more').hide();
	}
//console.log(data);
$(".portlet-body table tr").removeClass('flashrow');
$.each(data.wash_requests, function( index, value ) {
  
    dt_table.fnDeleteRow( $(".portlet-body table tr#order-"+value.id));

});

alldata = dt_table.fnGetData();
//console.log(alldata);
dt_table.fnClearTable();

$.each(data.wash_requests, function( index, value ) {
    var upcomingwashes = [];

    upcomingwashes["DT_RowId"] = "order-"+value.id;


      upcomingwashes.push("<a href='edit-order.php?id="+value.id+"' class='appt-edit-order' data-id='"+value.id+"' style='margin-right: 7px;'>Edit</a>");
      upcomingwashes.push(value.id);
       if(value.is_scheduled == 1){
         upcomingwashes.push("<p><span class='label label-sm label-pending' style='background-color: #0046ff !important;'>Scheduled</span></p>"); 
      }
      else{
        upcomingwashes.push("<p><span class='label label-sm label-pending' style='background-color: #009688 !important;'>On-Demand</span></p>");   
      }
      //var checklist_arr = value.checklist.split('|');
      
      if(value.status == 5 || value.status == 6){
         upcomingwashes.push("<span class='label label-sm label-cancel'>Cancelled</span>"); 
      }
      
      else if(value.status == 0){
         
        upcomingwashes.push("<span class='label label-sm label-pending'>Pending</span>");
      }
      
      else if(value.status == 1){
         upcomingwashes.push("<span class='label label-sm label-process'>En Route</span>"); 
      }
      
      else if(value.status == 2){
         upcomingwashes.push("<span class='label label-sm label-process'>Arrived</span>"); 
      }
      
       else if(value.status == 3){
         upcomingwashes.push("<span class='label label-sm label-process'>In Process</span>"); 
      }
      
      else if(value.status == 4){
         upcomingwashes.push("<span class='label label-sm label-complete'>Completed</span>");
      }
      
       var payment_status_str = '';
if((value.payment_status == 'Declined') || (value.payment_status == 'Check Fraud')){
payment_status_str += "<span class='label label-sm label-pending'>"+value.payment_status+"</span><br><br>";

      }
      else payment_status_str += value.payment_status;

      if(value.payment_type == 'free') payment_status_str += "<span class='label label-sm label-complete'>Free Wash</span>";
     upcomingwashes.push(payment_status_str);

upcomingwashes.push(value.transaction_id);
upcomingwashes.push(value.transaction_status);

					if ((value.transaction_status == 'authorized') || (value.transaction_status == 'submitted_for_settlement') || (value.transaction_status == 'settling') || (value.transaction_status == 'settled')) {
						upcomingwashes.push("<span class='label label-sm label-complete'>Yes</span>");
					}
					else{
					upcomingwashes.push("<span class='label label-sm label-pending'>No</span>");	
					}
upcomingwashes.push("<a target='_blank' href='/admin-new/all-orders.php?customer_id="+value.customer_id+"'>"+value.customer_name+"</a>");
upcomingwashes.push(value.customer_phoneno);
if(value.agent_details.agent_name) upcomingwashes.push("<a target='_blank' href='/admin-new/all-orders.php?agent_id="+value.agent_details.agent_id+"'>"+value.agent_details.agent_name+"</a>");
else upcomingwashes.push("N/A");
if(value.agent_details.agent_phoneno) upcomingwashes.push(value.agent_details.agent_phoneno);
else upcomingwashes.push("N/A");
if(value.agent_details.agent_submerchant_id) upcomingwashes.push(value.agent_details.agent_submerchant_id);
else upcomingwashes.push("N/A");
upcomingwashes.push(value.address+" ("+value.address_type+")");

if(value.is_scheduled == 1){
 if (value.reschedule_time) {
  upcomingwashes.push("<span style='color: red; font-weight: bold; font-size: 13px;'>"+value.reschedule_date+" "+value.reschedule_time+"</span><p style='text-align: center; font-weight: bold; color: red; margin: 5px 0;'>Re-Scheduled</p>"+value.schedule_date+" "+value.schedule_time);  
}
else{
 upcomingwashes.push(value.schedule_date+" "+value.schedule_time);   
}
}
else{
    upcomingwashes.push("N/A");  
}


 var veh_string = '';
if(value.vehicles.length){
   
veh_string += "<ol style='padding-left: 15px;'>";
$.each(value.vehicles, function( ind, val ) {
veh_string += "<li style='margin-bottom: 10px;'>"+val.make+" "+val.model+" ("+val.pack+")";
if (val.addons) {
veh_string += " - Addons: "+val.addons;
}
veh_string += "</li>";
});
veh_string += "</ol>";
}
upcomingwashes.push(veh_string);
upcomingwashes.push("$"+value.total_price);
upcomingwashes.push("$"+value.net_price);
upcomingwashes.push("$"+value.company_total);
upcomingwashes.push("$"+value.agent_total);
upcomingwashes.push("$"+value.bundle_discount);
upcomingwashes.push("$"+value.fifth_wash_discount);

if(!value.coupon_discount) upcomingwashes.push("$0.00");
else upcomingwashes.push("$"+value.coupon_discount)

upcomingwashes.push(value.coupon_code);
upcomingwashes.push("$"+value.company_discount);
upcomingwashes.push("$"+value.tip_amount);
upcomingwashes.push(value.created_date);
dt_table.fnAddData(upcomingwashes);
 //console.log(upcomingwashes);
});
 
 if(alldata.length > 0) dt_table.fnAddData(alldata);
 //dt_table.fnDraw();
}

$(th).addClass('load-more');
$(th).html('Load More');
});
  return false;
});

});



</script>