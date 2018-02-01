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

$url = ROOT_URL.'/api/index.php?r=site/getbugreport';

$handle = curl_init($url);
$data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$s_orders_response = $jsondata->response;
$s_orders_result_code = $jsondata->result;
$all_bugs = $jsondata->bugreport;
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


  .flashrow{
   -webkit-animation: glowing 1500ms infinite;
        -moz-animation: glowing 1500ms infinite;
        -o-animation: glowing 1500ms infinite;
        animation: glowing 1500ms infinite;
}

.flashrow td{
    border: 0 !important;

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
                                        <span class="caption-subject bold uppercase"> Bug Report </span>
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
												<th style="min-width: 300px;"> Actions </th>
                                                <th> ID </th>
                                                 <th> Platform </th>
                                                <th> Status </th>
<th> Description </th>
<th> Created Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($all_bugs as $ind=>$bug){
						
				   ?>
                <tr class="odd gradeX">
					<td><a href="#" style="margin-right: 7px;">Edit</a></td>
                    <td><?php echo $bug->id; ?></td>
                    <td><?php echo $bug->platform; ?></td>
                    <td><?php echo $bug->status; ?></td>
                    <td><?php echo $bug->description; ?></td>
 <td><?php echo $bug->created_date; ?></td>



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
                