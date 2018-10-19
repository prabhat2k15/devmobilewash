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
    $url = ROOT_URL.'/api/index.php?r=site/getallnewslettersubscribers';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$subscriber_response = $jsondata->response;
$subscriber_result_code = $jsondata->result;
$mw_all_subscribers = $jsondata->subscribers;
?>
<style>
.label-online {
    background-color: #16CE0C !important;
}

.label-offline {
    background-color: #969696 !important;
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
                                        <span class="caption-subject bold uppercase"> Newsletter Subscribers</span>
<a href="#" class="csv-upload" style="margin-left: 15px; font-size: 16px; font-weight: bold;">Upload CSV</a>
<form action="newsletter-subscriber-csv-upload.php" id="csv-upload-form" style="visibility: hidden; height: 0px;" method="post" enctype="multipart/form-data">
<input type="file" name="csv" id="csv-file" value="" />
<input type="hidden" name="csv-submit" value="yes" />
</form>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                   
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
<?php if($_GET['action'] == 'delete-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Subscriber #<?php echo $_GET['sid']; ?> deleted successfully</span><a href="/admin-new/newsletter-subscribers.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-error'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting subscriber. Please try again.</span><a href="/admin-new/newsletter-subscribers.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'csv-error1'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Please upload CSV file</span><a href="/admin-new/newsletter-subscribers.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'csv-error2'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">CSV file needs at least 1 column</span><a href="/admin-new/newsletter-subscribers.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'csv-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Subscribers added successfully</span><a href="/admin-new/newsletter-subscribers.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if($subscriber_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th> &nbsp; </th>
                                                <th> ID </th>
                                                <th> Name </th>
                                                <th> Email </th>
                                                <th> Subscription Status </th>
                                                <th> Subscription Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($mw_all_subscribers as $subscriber){ ?>
                <tr class="odd gradeX">
                    <td><a href="#" class="delete-subscriber" data-id="<?php echo $subscriber->id; ?>">Delete</a></td>
                    <td><?php echo $subscriber->id; ?></td>
                    <td><?php echo $subscriber->name; ?></td>
                    <td><?php echo $subscriber->email; ?></td>
                   <td>
<?php if($subscriber->subscription_status == 1): ?>
<span class="label label-sm label-online">Active</span>
<?php else: ?>
<span class="label label-sm label-offline">Inactive</span>
<?php endif; ?>
</td>
 <td><?php echo date( 'M j, Y h:i:s a', strtotime($subscriber->subscription_date )); ?></td>

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
<script>
$(function(){
$(document).on( 'click', '.delete-subscriber', function(){
var th = $(this);
user_id = $(this).data('id');
var r = confirm('Are you sure you want to delete subscriber #'+user_id+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/DeleteSubscriber", {subscriber_id: user_id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/newsletter-subscribers.php?action=delete-success&sid="+user_id;
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/newsletter-subscribers.php?action=delete-error";
}

});
   
} 
return false;
});

$('.csv-upload').click(function(){ 
$('#csv-file').click(); 
return false;

});

$('#csv-file').change(function() { 
    // select the form and submit
    $('#csv-upload-form').submit(); 
});
});
</script>