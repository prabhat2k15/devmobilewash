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

$page_number = 1;
if(isset($_GET['page_number'])) $page_number = $_GET['page_number'];
if(($_GET['search_area'] == 'Order Number') || ($_GET['search_area'] == 'Created Date') || ($_GET['search_area'] == 'Scheduled Date') || ($_GET['search_area'] == 'On-Demand') || ($_GET['search_area'] == 'Scheduled')) $url = ROOT_URL.'/api/index.php?r=site/searchorders';
if(($_GET['search_area'] == 'Customer Name') || ($_GET['search_area'] == 'Customer Email') || ($_GET['search_area'] == 'Customer Phone')) $url = ROOT_URL.'/api/index.php?r=customers/searchcustomers';
if(($_GET['search_area'] == 'Washer Name') || ($_GET['search_area'] == 'Washer Phone')) $url = ROOT_URL.'/api/index.php?r=agents/searchagents';
        $handle = curl_init($url);
        $data = array('query' => $_GET['q'], 'limit' => 10, 'search_area' => $_GET['search_area'], 'page_number' => $page_number, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $searchresults = json_decode($result);
        $searchresult_response = $searchresults->response;
        $searchresult_result_code = $searchresults->result;
      

?>

<?php
    if($washer_module_permission == 'no'){
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
            $('#example1, #example2, #example3').dataTable( {
  "pageLength": 20,
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],
"aaSorting": [],
"bPaginate": false,
"columnDefs": [ {
"targets": 0,
"orderable": false
} ]
} );

        });
        </script>
 <?php
 if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>

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

.action-col{
    min-width: 105px !important;
    max-width: 100% !important;
}

.cust-sms-pop{
    display: none;
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

.label-enroute {
    background-color: #00BCD4 !important;
}

.label-cancel {
    background-color: #999 !important;
}

.custom-pagination{
    text-align: center;
    margin: 10px;
}

.custom-pagination a{
   padding: 5px 10px;
    background: #337ab7;
    color: #fff;
    margin-right: 2px; 
}

.custom-pagination a:hover{
    text-decoration: none;
}
</style>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                  
                    <div class="clear">&nbsp;</div>
                   
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold" style="font-size: 22px;"> Search Results For: <?php if(isset($_GET['q'])) echo $_GET['q']; ?><?php if(isset($_GET['search_area'])) echo " (".$_GET['search_area'].")"; ?></span>
                                    </div>
      
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <?php if((($_GET['search_area'] == 'Customer Name') || ($_GET['search_area'] == 'Customer Email') || ($_GET['search_area'] == 'Customer Phone')) && (count($searchresults->allcustomers) > 0)): ?>
                                <div class="portlet-body">
                                    <h3 style="font-weight: bold; margin-top: 0; margin-bottom: 20px;">Customers</h3>
                                  
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                             <tr>
                                                <th class="action-col"> Actions </th>
<th> ID </th>
<th> User Type </th>
   <th> Customer Name </th>
<th> Email </th>
<th> Rating </th>
<th> Orders </th>
<th> Points </th>
<th> Phone </th>
<th> Phone Verify Code </th>
<th> Device Type </th>
<th> Address </th>
<th> City </th>
<th> How Hear MW </th>
<th> Created Date </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                            foreach( $searchresults->allcustomers as $customer){
                                           
                                        ?>
                                            <?php $city = 'N/A';
$address = 'N/A';

                                        ?>
                                            <tr class="odd gradeX">
                                                <td class="action-col"> <a href="edit-customer.php?customerID=<?php echo $customer->id; ?>">Edit</a> - <a href="#" data-id="<?php echo $customer->id; ?>" class='cust-pass-sms-trig'>Send Login</a></td>
 <td> <?php echo $customer->id; ?> </td>
<td> <?php echo $customer->user_type; ?> </td>
 <td> <a target="_blank" href="/admin-new/all-orders.php?customer_id=<?php echo $customer->id; ?>"><?php echo $customer->name; ?></a> </td>
<td> <?php echo $customer->email; ?> </td>
<td> <?php echo $customer->rating; ?> </td>
<td> <?php echo $customer->care_rating; ?> </td>
 <td> <?php 
if($customer->total_wash > 0) echo "<a target='_blank' href='".ROOT_URL."/admin-new/all-orders.php?customer_id=".$customer->id."'>".$customer->total_wash."</a>";
else echo $customer->total_wash; 

?> </td>
 <td> <?php echo $customer->wash_points; ?>/5 </td>
<td> <?php echo $customer->phone; ?> </td>
<td> <?php echo $customer->phone_verify_code; ?> </td>
<td> <?php echo $customer->device_type; ?> </td>
<td> <?php echo $customer->address; ?> </td>
  <td> <?php echo $customer->city; ?> </td>
 <td> <?php echo $customer->how_hear_mw; ?> </td>
 <td> <?php echo date('m-d-Y h:i A', strtotime($customer->client_science)); ?> </td>


                                            </tr>


                                        <?php

                                            }


                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class='custom-pagination'>
                                    <?php 
                                    //echo $searchresults->total_pages."<br>";
                                    if($page_number != 1) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=1'>&laquo;</a> ";
                                    for($i=$page_number+1, $j=1; $i<=$searchresults->total_pages; $i++, $j++){
                                      echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$i."'>".$i."</a> ";  
                                      if($j==5) break;
                                    }
                                    if($page_number != $searchresults->total_pages) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$searchresults->total_pages."'>&raquo;</a> ";
                                    ?>
                                    </div>
                                <?php endif; ?>
                                
<?php if((($_GET['search_area'] == 'Washer Name') || ($_GET['search_area'] == 'Washer Phone')) && (count($searchresults->all_washers) > 0)): ?>
                                <div class="portlet-body">
                                    <h3 style="font-weight: bold; margin-top: 0; margin-bottom: 20px;">Washers</h3>
                                    
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example2">
                                        <thead>
                                            <tr>
												<th> Actions </th>
                                                <th> ID </th>
<th> Badge No </th>
                                                   <th> Name </th>
                                                
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


                                            foreach( $searchresults->all_washers as $washer){
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
												
												</td>
                                              <td> <?php echo $washer->id; ?> </td>
 <td> <?php echo $washer->real_washer_id; ?> </td>
                                                <td> <a target="_blank" href="/admin-new/all-orders.php?agent_id=<?php echo $washer->id; ?>"><?php echo $washer->first_name." ".$washer->last_name; ?></a> </td>
                                               
                                                 <td> <?php echo $washer->email; ?> </td>
                                                 <td> <?php echo $washer->phone_number; ?> </td>
                                                 <td> <?php echo $washer->phone_verify_code; ?> </td>
                                                   <td> <?php echo $washer->city; ?> </td>
 <td> <?php if(strtotime($washer->insurance_exp_date) > 0) echo date('m-d-Y', strtotime($washer->insurance_exp_date)); ?> </td>

<td> <?php echo $washer->rating; ?> </td>                                                
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
                                <div class='custom-pagination'>
                                    <?php 
                                    //echo $searchresults->total_pages."<br>";
                                    if($page_number != 1) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=1'>&laquo;</a> ";
                                    for($i=$page_number+1, $j=1; $i<=$searchresults->total_pages; $i++, $j++){
                                      echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$i."'>".$i."</a> ";  
                                      if($j==5) break;
                                    }
                                    if($page_number != $searchresults->total_pages) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$searchresults->total_pages."'>&raquo;</a> ";
                                    ?>
                                    </div>
                                <?php endif; ?>
                               
                                <?php if((($_GET['search_area'] == 'Order Number') || ($_GET['search_area'] == 'Created Date') || ($_GET['search_area'] == 'Scheduled Date') || ($_GET['search_area'] == 'On-Demand') || ($_GET['search_area'] == 'Scheduled')) && (count($searchresults->wash_requests))): ?>
                                 <div class="portlet-body">
                                    <h3 style="font-weight: bold; margin-top: 0; margin-bottom: 20px;">Orders</h3>
                                    
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example3">
                                        <thead>
                                            <tr>
												<th> Actions </th>
                                                <th> ID </th>
                                                 <th> Order Type </th>
                                                <th> Status </th>
<th> Payment </th>
<th> Transaction ID </th>
												<!--th> Customer ID </th-->
												<th> Customer Name </th>

												<th> Customer Phone </th>
												<!--th> Agent ID </th-->
												<th> Agent Name </th>
												<!--th> Agent Email </th-->
                                                <th> Agent Phone </th>
                                                <th style='min-width: 115px;'> Address </th>
                                                <th> Schedule Datetime </th>
<th> Starts </th>
                                                <th>Vehicles </th>
												<!--th>Total Price </th-->
												<!--th>Transaction ID </th-->
												<th> Created Date </th>

                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($searchresults->wash_requests as $ind=>$order){
						
				   ?>
                <tr class="odd gradeX <?php if($ind == 0) echo "flashro";?>" id="order-<?php echo $order->id; ?>">
					<td><a href="edit-order.php?id=<?php echo $order->id; ?>" class="appt-edit-order" data-id="<?php echo $order->id; ?>" style="margin-right: 7px;">Edit</a></td>
                    <td><?php echo $order->id; ?></td>
                    <td><?php if($order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #0046ff !important;">Scheduled</span></p><?php endif; ?><?php if(!$order->is_scheduled): ?><p><span class="label label-sm label-pending" style="background-color: #009688 !important;">On-Demand</span></p><?php endif; ?></td>
                    <td>
                   
<?php if($order->status == 5 || $order->status == 6): ?>
<span class="label label-sm label-cancel">Cancelled</span>
<?php elseif(!$order->status): ?>
<span class="label label-sm label-pending">Pending</span>
<?php elseif($order->status == 1): ?>
<span class="label label-sm label-process">En Route</span>
<?php elseif($order->status == 2): ?>
<span class="label label-sm label-process">Arrived</span>
<?php elseif($order->status == 3): ?>
<span class="label label-sm label-process">In Process</span>
<?php elseif($order->status == 4): ?>
<span class="label label-sm label-complete">Completed</span>
<?php endif; ?>
                    </td>
<td><?php 
if($order->payment_status == 'Declined') echo"<span class='label label-sm label-pending'>".$order->payment_status."</span>";
else echo $order->payment_status; ?></td>
 <td><?php echo $order->transaction_id; ?></td>
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
<td>
<?php
if($order->min_diff > 0) echo $order->min_diff;
else echo "-";

?>
</td>
  <td><?php
  if(count($order->vehicles)){
echo "<ol style='padding-left: 15px;'>";
foreach($order->vehicles as $car){
echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.")</li>";
}
echo "</ol>";
}

?></td>

 <td><?php echo $order->created_date; ?></td>



                </tr>
							<?php } ?>
                                      </tbody>
                                    </table>
                                    </div>
                                    <div class='custom-pagination'>
                                    <?php 
                                    //echo $searchresults->total_pages."<br>";
                                    if($page_number != 1) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=1'>&laquo;</a> ";
                                    for($i=$page_number+1, $j=1; $i<=$searchresults->total_pages; $i++, $j++){
                                      echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$i."'>".$i."</a> ";  
                                      if($j==5) break;
                                    }
                                    if($page_number != $searchresults->total_pages) echo "<a href='".ROOT_URL."/admin-new/search.php?q=".$_GET['q']."&search_area=".$_GET['search_area']."&page_number=".$searchresults->total_pages."'>&raquo;</a> ";
                                    ?>
                                    </div>
                                   <?php endif; ?>
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
            <div class="cust-sms-pop" style="width: 100%; height: 100%; background: rgba(0,0,0,.8); position: fixed; top: 0; left: 0; z-index: 99999;">
            <div class="pop-content" style="position: absolute; width: 400px; margin-left: -200px; left: 50%; top: 200px; background: #fff; color: #000; padding: 20px; box-sizing: border-box;">
                    <h2 style="font-weight: bold; font-size: 22px; margin-top: 0;">SMS Preview</h2>
                    <div class="sms-content"><p>Greetings from MobileWash<br>Your login is: nazmur.r@gmail.com<br>Your new password is: oddwood12</p></div>
                    <p style="font-weight: bold; text-align: right;" class="action-btns"><a href="#" style="margin-right: 10px;" class="send-cust-pass-sms">Send</a> <a href="#" class="pop-close">Cancel</a></p>
                </div>
            </div>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <script>
        $(function(){
            var cust_email = '';
            var cust_pass = '';
            var cust_id = '';
           $(".cust-pass-sms-trig").click(function(){
              $(".cust-sms-pop").fadeIn();
              $(".cust-sms-pop .action-btns").show();
              $(".cust-sms-pop .sms-content").html('Loading...');
               cust_id = $(this).data('id');
              $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminpreviewcustpasssms", { customer_id: cust_id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
    cust_email = data.customer_email;
    cust_pass = data.customer_password;
$(".cust-sms-pop .sms-content").html(data.response);
}
else{
    $(".cust-sms-pop .sms-content").html(data.response);
cust_email = '';
            cust_pass = '';
            cust_id = '';
}

});
              return false;
           });

           $(".cust-sms-pop .pop-close").click(function(){
              $(".cust-sms-pop").fadeOut();
              return false;
           });

           $(".send-cust-pass-sms").click(function(){
             var th = $(this);
              $(this).html('Sending...');

              $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/adminsmscustpass", { customer_id: cust_id, customer_email: cust_email, customer_password: cust_pass, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data){
//console.log(data);
if(data.result == 'true'){
     $(".cust-sms-pop .sms-content").html(data.response);
   $(th).html('Send');
   cust_email = '';
            cust_pass = '';
            cust_id = '';
            $(".cust-sms-pop .action-btns").hide();
            setTimeout(function(){
 $(".cust-sms-pop").fadeOut();
}, 2000);
}
else{
    $(".cust-sms-pop .sms-content").html(data.response);
  $(th).html('Send');
}

});
              return false;
           });
        });
        </script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <style>

.page-content-wrapper .page-content{
    padding: 0 20px 10px !important;
}
        </style>