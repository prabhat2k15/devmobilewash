<?php include('header.php') ?>
<?php

function getExcerpt($str, $startPos=0, $maxLength=100) {
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

    if($company_module_permission == 'no' || $checked_opening_hours == ''){
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
  "lengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]]
} );
            
        });
        </script>
<?php include('right-sidebar.php') ?>
<?php
    $url = 'http://www.devmobilewash.com/api/index.php?r=site/getallnewsletters';

    $handle = curl_init($url);
        $data = '';
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$newsletters_response = $jsondata->response;
$newsletters_result_code = $jsondata->result;
$mw_all_newsletters = $jsondata->newsletters;
?>
<style>
.label-online {
    background-color: #16CE0C !important;
}

.label-offline {
    background-color: #969696 !important;
}
</style>
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
.pagination ul {
    display: inline-block;
    margin-bottom: 0;
    margin-left: 0;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      }
      .pagination li {
    display: inline;
}
li {
    line-height: 20px;
}
user agent stylesheetli {
    display: list-item;
    text-align: -webkit-match-parent;
}
.pagination li:first-child a, .pagination li:first-child span {
    border-left-width: 1px;
    -webkit-border-radius: 3px 0 0 3px;
    -moz-border-radius: 3px 0 0 3px;
    border-radius: 3px 0 0 3px;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
a {
    color: #08c;
    text-decoration: none;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
.pagination a, .pagination span {
    float: left;
    padding: 0 14px;
    line-height: 38px;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-left-width: 0;
}
.pagination{
    width: 100%;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
$('#total_customers').click(function(){
    if($('.total_customers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php';
});
$('#clientoffline').click(function(){
    if($('.clientoffline').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php?type=clientoffline';
});
$('#cancel_orders_client').click(function(){
    if($('.cancel_orders_client').html() == 0){
        alert('There is no data found!');
        return false;
    }
    
   window.location.href='manage-customers.php?type=cancel_orders_client';
});
$('#idle_wash_client').click(function(){
    if($('.idle_wash_client').html() == 0){
        alert('There is no data found!');
        return false;
    }
    
   window.location.href='manage-customers.php?type=idle_wash_client';
});
$('#bad_rating_customers').click(function(){
    if($('.bad_rating_customers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php?type=bad_rating_customers';
});
});
</script>
<style>
.current_tab{
background-color: #5407e2 !important;
border-top: 5px solid #5407e2 !important;
height: 90px !important;
padding: 13px 0 0 10px !important;
cursor: pointer !important;
}

.page-content-wrapper .page-content{
padding-top: 0 !important;
}
</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->

<div class="row" style="background-color: #000; color: #fff; margin-left: -20px ! important; margin-right: -20px; height: 90px;">
                        <div class="col-md-1 col-sm-1 <?php echo $total_customers; ?>" id="total_customers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #5407E2; height: 90px;">
                            <div style="font-size: 20px;" class="total_customers">0</div>
                            <div>Total Clients</div>
                        </div>
                        <div class="col-md-1 col-sm-1 <?php echo $clientoffline; ?>" id="clientoffline" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #0771e2;">
                            <div style="font-size: 20px;" class="clientoffline">0</div>
                            <div>Offline Clients</div>
                        </div>
                        <div class="col-md-2 col-sm-2 <?php echo $cancel_orders_client; ?>" id="cancel_orders_client" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e20724;">
                            <div style="font-size: 20px; <?php echo $total_customers; ?>" class="cancel_orders_client">0</div>
                            <div>Clients Cancels</div>
                        </div>
                        <div class="col-md-1 col-sm-1 <?php echo $idle_wash_client; ?>" id="idle_wash_client" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #07c1e2;">
                            <div style="font-size: 20px;" class="idle_wash_client">0</div>
                            <div>Idle Clients</div>
                        </div>
                        <div class="col-md-2 col-sm-2 <?php echo $bad_rating_customers; ?>" id="bad_rating_customers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e900e7;">
                            <div style="font-size: 20px;" class="bad_rating_customers">0</div>
                            <div>Flagged Bad Clients</div>
                        </div>
                    </div>
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
                                        <span class="caption-subject bold uppercase"> Newsletters</span> <a href="add-newsletter.php" style="margin-left: 15px; font-size: 16px; font-weight: bold;">+ Add New</a>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                   
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
<?php if($_GET['action'] == 'add-newsletter-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Newsletter added successfully</span><a href="/admin-new/newsletters.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'update-newsletter-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Newsletter updated successfully</span><a href="/admin-new/newsletters.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Newsletter #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/newsletters.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-error'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting newsletter. Please try again.</span><a href="/admin-new/newsletters.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if($newsletters_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                               
                                                <th> ID </th>
                                                <th> Title </th>
                                                <th> Content </th>
                                                <th> Receivers </th>
                                                <th style="min-width: 140px;"> Actions </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($mw_all_newsletters as $letter){ ?>
                <tr class="odd gradeX">
                    
                    <td><?php echo $letter->id; ?></td>
                    <td><?php echo $letter->title; ?></td>
                    <td><?php echo getExcerpt(strip_tags($letter->content), 0, 300); ?></td>
<td><?php 

$all_receivers = explode(",", $letter->receivers);
foreach($all_receivers as $re) echo $re."<br>"; ?></td>
<td><a href="send-newsletter.php?id=<?php echo $letter->id; ?>" style="margin-right: 7px;">Send</a> <a href="edit-newsletter.php?id=<?php echo $letter->id; ?>" style="margin-right: 7px;">Edit</a> <a href="#" class="delete-letter" data-id="<?php echo $letter->id; ?>">Delete</a></td>
             

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
$(document).on( 'click', '.delete-letter', function(){
var th = $(this);
id = $(this).data('id');
var r = confirm('Are you sure you want to delete newsletter #'+id+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "http://www.devmobilewash.com/api/index.php?r=site/deletenewsletter", {id: id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="http://www.devmobilewash.com/admin-new/newsletters.php?action=delete-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="http://www.devmobilewash.com/admin-new/newsletters.php?action=delete-error";
}

});
   
} 
return false;
});
});
</script>