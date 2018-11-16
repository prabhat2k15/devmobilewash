<?php include('header.php') ?>

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
 "aaSorting": []
} );

        });
        </script>

<?php include('right-sidebar.php') ?>

<?php


	/* $day = $_event = '';
	if( isset($_GET['day']) && !empty( $_GET['event'] ) ){
		$day = $_GET['day'];
		//$_event = $_GET['event'];
	} */



/* SCHEDULE ORDER */

$day = $_event = '';
if( isset($_GET['sday']) && !empty( $_GET['sday'] ) ){
	$day = $_GET['sday'];
	$_event = $_GET['event'];
}

$url = ROOT_URL.'/api/index.php?r=customers/review';

$handle = curl_init($url);
$data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$review_response = $jsondata->response;
$review_code = $jsondata->result;
$all_reviews = $jsondata->reviews;
/* echo"<pre>";print_r($all_reviews);echo"</pre>";
echo $all_reviews[0]->cust_review;
die; */
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
                                        <span class="caption-subject bold uppercase"> Reviews </span>
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
                                 <?php if($review_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
												<th style="min-width: 140px;"> Actions </th>
                                                <th> ID </th>
												<th> Customer Name </th>
                                                <th style='min-width: 210px;'> Address </th>
                                                <th>Reviews </th>
												<th> Created Date </th>
                                            </tr>
                                        </thead>
										<tbody><?php  
											foreach($all_reviews as $index=>$review){ //echo"<pre>";print_r($review);echo"</pre>";?>
												<tr class="odd gradeX">
													<td><a href="add-edit-review.php?id=<?php echo $review->id; ?>&action=edit" class="appt-edit-order" data-id="<?php echo $review->id; ?>" style="margin-right: 7px;">Edit</a></td>
													<td><?php echo $review->id; ?></td>
													<td><?php echo "N/A"; ?></td>
													<td><?php echo "N/A"; ?></td>
													<td><?php echo substr($review->cust_review,0,150).'..'; ?></td>
													<td><?php echo date('m-d-Y', strtotime($review->created_date))." ".date('h:i A', strtotime($review->created_date)); ?></td>
												</tr><?php 
												 
											} ?>
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