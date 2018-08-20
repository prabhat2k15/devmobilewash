<?php include('header.php') ?>
<?php
$from = "";
$to = "";
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
if($_GET['from']){
 $from = $_GET['from'];   
}
else{
  $from = "2017-01-01";  
}

if($_GET['to']){
 $to = $_GET['to'];   
}
else{
  $to = date('Y-m-d');  
}

$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/gettopmostcustomers");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array("key" => "Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4", "from" => $from, "to" => $to));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$customers_data = json_decode($result);
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

<style>
.dt-button.buttons-csv.buttons-html5 {
    padding: 5px 10px;
    background-color: lightgray;
    color: black;
    border: 1px solid lightgray;
    border-radius: 5px;
    text-decoration: none;
}
.manik > .dt-buttons{margin : 0px !important; float: left !important;}
.dt-button.buttons-csv.buttons-html5:hover{ background-color: black; color: #fff; border-color: black; }
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
                                        <span class="caption-subject bold uppercase"> Top Customers (<?php echo date("M j, Y",strtotime($from)); ?>  - <?php echo date("M j, Y",strtotime($to)); ?>) </span>
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
                                  <?php if(count($customers_data->top_customers)): ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
						<th> ID </th>
                                                <th> Name </th>
						<th> Phone </th>
						<th> Email </th>
                                                <th> Total Washes </th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach($customers_data->top_customers as $cust): ?>
<tr>
    <td><?php echo $cust->id; ?></td>
    <td><?php echo $cust->name; ?></td>
    <td><?php echo $cust->phone; ?></td>
    <td><?php echo $cust->email; ?></td>
    <td><?php echo $cust->total_washes; ?></td>
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
        "sDom": "<'row'<'col-sm-5'l><'col-sm-3 text-center manik'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'csvHtml5'
        ]
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
            