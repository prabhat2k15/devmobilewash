<?php include('header.php') ?>
<?php
    if($company_module_permission == 'no' || $checked_opening_hours == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
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
<?php
    $url = ROOT_URL.'/api/index.php?r=coupons/userdpromocode';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4', 'promocode' => $_GET['promocode']);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$coupons_response = $jsondata->response;
$coupons_result_code = $jsondata->result;
$all_coupons = $jsondata->coupons;
/*echo "<pre>";
print_r($all_coupons);
echo "</pre>";
die;*/
?>
<style>
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


                    <?php if(!empty($_GET['cnf'])){ ?>
                    <p style="background: #50BB50; color: #fff; padding: 15px; box-sizing: border-box;">Promo code added successfully</p>
                    <?php } ?>
                    <?php if(!empty($_GET['update'])){ ?>
                    <p style="text-align: center; color: green;">Successfully Update Promotion</p>
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
                                        <span class="caption-subject bold uppercase"> Manage Promotions Users</span>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                    <div class="caption font-dark" style="display: <?php echo $add_company; ?>">
                                        
                                        <!-- <span class="caption-subject bold uppercase"> <a href="add-coupon.php"><img src="images/add-coupon.png"></a></span> -->
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                 <?php if($coupons_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th> ID </th>
                                                 <th> Order Type </th>
                                                <th> Status </th>
                                                <th> Payment </th>
                                                <th> Transaction ID </th>
                                                <th> Declined Transaction ID </th>
                                                <!--th> Customer ID </th-->
                                                <th> Customer Name </th>
                                                <th> Customer Phone </th>
                                                <!--th> Agent ID </th-->
                                                <th> Badge </th>
                                                <th> Agent Name </th>
                                                <!--th> Agent Email </th-->
                                                <th> Agent Phone </th>
                                                <th style='min-width: 115px;'> Address </th>
                                                <th> Schedule Datetime </th>
                                                <th> Starts </th>
                                                <th> Vehicles </th>
                                                <th> Total Price </th>
                                                <th> Created Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($all_coupons as $order){ ?>
                <tr class="odd gradeX <?php if($ind == 0) echo "flashro";?>" id="order-<?php echo $order->id; ?>">
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
                    <td><?php if(($order->payment_status == 'Declined') || ($order->payment_status == 'Check Fraud')) echo"<span class='label label-sm label-fraud'>".$order->payment_status."</span><br><br>"; else echo $order->payment_status; ?><?php if($order->payment_type == 'free'): ?><span class="label label-sm label-complete">Free Wash</span><?php endif; ?></td>
                    <td><?php echo $order->transaction_id; ?></td>
                    <td><?php echo $order->failed_transaction_id; ?></td>
                    <td><a target="_blank" href="/admin-new/all-orders.php?customer_id=<?php echo $order->customer_id; ?>"><?php echo $order->customer_name; ?></a></td>
                    <td><?php echo $order->customer_phoneno; ?></td>
                    <td><?php if(count($order->agent_details)) echo $order->agent_details->real_washer_id; else echo "N/A";?></td>
                    <td><?php if(count($order->agent_details)) echo "<a target='_blank' href='/admin-new/all-orders.php?agent_id=".$order->agent_details->agent_id."'>".$order->agent_details->agent_name."</a>"; else echo "N/A";?></td>
                    <td><?php if(count($order->agent_details)) echo $order->agent_details->agent_phoneno; else echo "N/A"; ?></td>
                    <td><?php echo $order->address." (".$order->address_type.")"; ?></td>
                    <td><?php if($order->is_scheduled): ?><?php if(strtotime($order->reschedule_date) > 0): ?><span style="color: red; font-weight: bold; font-size: 13px;"><?php echo $order->reschedule_date." ".$order->reschedule_time; ?></span><p style="text-align: center; font-weight: bold; color: red; margin: 5px 0;">Re-Scheduled</p><?php endif; ?><?php if(strtotime($order->schedule_date) > 0) echo $order->schedule_date." ".$order->schedule_time; ?><?php else: ?>N/A<?php endif; ?></td>
                    <td><?php if($order->min_diff > 0) echo $order->min_diff; else echo "-";?></td>
                    <td><?php
                    if(count($order->vehicles)){
                    echo "<ol style='padding-left: 15px;'>";
                    foreach($order->vehicles as $car){
                    echo "<li style='margin-bottom: 10px;'>".$car->make." ".$car->model." (".$car->pack.")";
                    if($car->addons) echo " - Addons: ".$car->addons;
                    echo "</li>";
                    }
                    echo "</ol>";
                    }
                    ?></td>
                    <td>$<?php echo $order->net_price; ?></td>
                    <td><?php echo $order->created_date; ?></td>
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