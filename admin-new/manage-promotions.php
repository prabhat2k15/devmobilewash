<?php include('header.php') ?>

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
    $url = ROOT_URL.'/api/index.php?r=coupons/getallcoupons';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$coupons_response = $jsondata->response;
$coupons_result_code = $jsondata->result;
$all_coupons = $jsondata->coupons;

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
                                        <span class="caption-subject bold uppercase"> Manage Promotions</span>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                    <div class="caption font-dark" style="display: <?php echo $add_company; ?>">
                                        
                                        <span class="caption-subject bold uppercase"> <a href="add-coupon.php"><img src="images/add-coupon.png"></a></span>
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
                                                <th> &nbsp; </th>
                                                <th> Coupon Name </th>
                                                <th> Promo ID </th>
                                                <th> Status </th>
                                                <th> Code </th>
                                                <th> Express Discount </th>
                                                <th> Deluxe Discount </th>
<th> Premium Discount </th>
                                                <th> Uses </th>
                                                <th> Expires </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($all_coupons as $coupon){ ?>
                <tr class="odd gradeX">
                    <td><a href="edit-coupon.php?couponid=<?php echo $coupon->id; ?>">Edit</a></td>
                    <td class="fullwidth"><?php echo $coupon->coupon_name; ?></td>
                    <td><?php echo $coupon->id; ?></td>
                    <td><?php if($coupon->coupon_status == 'enabled'){ ?>
                    <span class="label label-sm label-online">Enabled</span>
                    <?php } ?>
                    <?php if($coupon->coupon_status == 'paused'){ ?>
                    <span class="label label-sm label-warning">Paused</span>
                    <?php } ?>
                    <?php if($coupon->coupon_status == 'disabled') { ?>
                    <span class="label label-sm label-offline">Disabled</span>
                    <?php } ?></td>
                    <td><?php echo $coupon->coupon_code; ?></td>
                    <td><?php if($coupon->discount_unit == 'usd'){ echo "$"; }
                    echo $coupon->express_amount;
                    if($coupon->discount_unit == 'percent') { echo "%"; }
                    ?></td>
                    <td><?php if($coupon->discount_unit == 'usd'){ echo "$"; }
                    echo $coupon->deluxe_amount;
                    if($coupon->discount_unit == 'percent') { echo "%"; }
                    ?></td>
 <td><?php if($coupon->discount_unit == 'usd'){ echo "$"; }
                    echo $coupon->premium_amount;
                    if($coupon->discount_unit == 'percent') { echo "%"; }
                    ?></td>
                    <td><a href="/admin-new/promocode_use.php?promocode=<?php echo $coupon->coupon_code; ?>"><?php echo $coupon->coupon_usage; ?></a></td>
                    <td><?php if(strtotime($coupon->expire_date) < 0 ) { echo "N/A"; }
                    else { echo $coupon->expire_date; } ?></td>

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