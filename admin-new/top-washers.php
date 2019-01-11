<?php include('header.php') ?>
<?php
$from = "";
$to = "";
$voice_print = '';

if($_GET['from']){
 $from = $_GET['from'];   
}
else{
  $from = date('Y-m-d',strtotime("-1 month"));  
}

if($_GET['to']){
 $to = $_GET['to'];   
}
else{
  $to = date('Y-m-d');  
}

$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/gettopmostwashers");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array("key" => API_KEY, "from" => $from, "to" => $to, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$washers_data = json_decode($result);
?>
<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->


<?php include('right-sidebar.php') ?>


<style>
.dt-button.buttons-csv.buttons-html5 { opacity: 0;}
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
                                        <span class="caption-subject bold uppercase"> Top Washers (<?php echo date("M j, Y",strtotime($from)); ?>  - <?php echo date("M j, Y",strtotime($to)); ?>) </span><a style="margin-left: 20px;" target="_blank" href="<?php echo ROOT_URL; ?>/api/index.php?r=site/gettopmostwashers&key=<?php echo API_KEY; ?>&from=<?php echo $from; ?>&to=<?php echo $to; ?>&csv_export=1&api_token=<?php echo urlencode($finalusertoken); ?>&t1=<?php echo urlencode($mw_admin_auth_arr[2]); ?>&t2=<?php echo urlencode($mw_admin_auth_arr[3]); ?>&user_type=admin&user_id=<?php echo urlencode($mw_admin_auth_arr[4]); ?>">Download CSV</a>
                                    </div>
                                    <div class="caption font-dark">

                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>

                                   <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
				<div style="clear: both;"></div>
                                                    <form action="" method="get">
							 <p>From: <input class="form-control form-control-inline input-medium date-picker" style="display: inline; width: 180px !important;" name="from" size="16" type="date" value="<?php echo $from; ?>" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required> To: <input class="form-control form-control-inline input-medium date-picker" name="to" size="16" style="display: inline; width: 180px !important;" type="date" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $to; ?>" required><input style="margin-left: 10px;" type="submit" value="Search" /></p>
                                            
						    </form>
						   

                                </div>
                                <div class="portlet-body">
                                  <?php if(count($washers_data->top_washers)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
						<!--<th> ID </th>-->
                                                <th> Badge ID </th>
                                                <th>  Washer Photo </th>
                                               <th> Name </th>
					       <th> Phone Number </th>
                                                <th> Email </th>
						<th style="display: none;"> Address </th>
                                                <th style="display: none;"> Street </th>
                                                <th style="display: none;"> Home Number </th>
                                                <th style="display: none;"> City </th>
                                                <th style="display: none;"> State </th>
                                                <th style="display: none;"> Zip Code </th>
                                                <th> Total Scheduled </th>
                                                <th> Total On Demand </th>
                                                <th> Total Washes </th>
                                                <th> Total cancel </th>
                                                <th> Total Earned </th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach($washers_data->top_washers as $washer): ?>
<?php if($washer->block_washer) continue; ?>
<tr>
    <!--<td><?php //echo $washer->id; ?></td>-->
    <td><?php echo $washer->company_id; ?></td>
    <td><img class="washer_default_background" src="<?php if($washer->image) {echo $washer->image;} else{echo "images/image_icon.png";} ?>" style="width: 100px; height: 100px;"></td>
    <td><a href="<?php echo ROOT_URL;?>/admin-new/washer_history.php?washer_id=<?php echo $washer->washer_id; ?>&from=<?php echo $from; ?>&to=<?php echo $to; ?>&event=washer_history"><?php echo $washer->name; ?></a></td>
    <td><?php echo $washer->phonenumber; ?></td>
    <td><?php echo $washer->email; ?></td>
    <td style="display: none;"><?php echo $washer->address; ?></td>
    <td style="display: none;"><?php echo $washer->street; ?></td>
    <?php $home = explode(',', $washer->street); ?>
    <td style="display: none;"><?php echo (int) filter_var($home[0], FILTER_SANITIZE_NUMBER_INT);?></td>
    <td style="display: none;"><?php echo $washer->city; ?></td>
    <td style="display: none;"><?php echo $washer->state; ?></td>
    <td style="display: none;"><?php echo $washer->zip; ?></td>
    <td><?php echo $washer->total_scheduled; ?></td>
    <td><?php echo $washer->total_demand; ?></td>
    <td><?php echo $washer->total_washes; ?></td>
    <?php if($washer->total_cancel > 0){?>
    <td><a href="<?php echo ROOT_URL;?>/admin-new/washer_history.php?washer_id=<?php echo $washer->washer_id; ?>&from=<?php echo $from; ?>&to=<?php echo $to; ?>&event=washer_history_cancel"><?php echo $washer->total_cancel; ?></a></td>
    <?php }else{
        echo "<td>".$washer->total_cancel."</td>";
    }?>
    <td><?php echo ($washer->total_sum > 0)? '$'.number_format($washer->total_sum,2):'$00.00'; ?></td>
</tr>
<?php endforeach; ?>
             
                                      </tbody>
                                    </table>
				    <?php else: ?>
				    <h2>Nothing Found</h2>
                                    <?php endif; ?> 
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
<script type="text/javascript">
                $('#example1').DataTable({
        pageLength: 25,
        stateSave: true,
        //lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

    });
		
            </script>
             <!--<script src='js/jquery.voicerss-tts.min.js'></script>-->
      
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
            