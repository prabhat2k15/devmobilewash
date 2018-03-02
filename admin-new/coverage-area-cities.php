<?php
include('header.php');

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
    $url = ROOT_URL.'/api/index.php?r=site/getallcoverageareacities';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$cities_response = $jsondata->response;
$cities_result_code = $jsondata->result;
$all_cities = $jsondata->all_cities;
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
                                        <span class="caption-subject bold uppercase"> Coverage Area Cities</span> <a href="add-coverage-area-city.php" style="margin-left: 15px; font-size: 16px; font-weight: bold;">+ Add New</a>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                   
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
<?php if($_GET['action'] == 'add-city-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">City added successfully</span><a href="/admin-new/coverage-area-cities.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">City #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/coverage-area-cities.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-error'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting city. Please try again.</span><a href="/admin-new/coverage-area-cities.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if($cities_result_code == 'true'){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                               
                                                <th style="text-align: left; padding: 10px 5px;"> City </th>
						<th style="text-align: left; padding: 10px 5px;"> State </th>
						<th style="text-align: left; padding: 10px 5px;"> Actions </th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($all_cities as $city){ ?>
                <tr class="odd gradeX">
                    
                    <td style="text-align: left; padding: 10px 5px;"><a href="<?php if($city->citypage_url) {echo $city->citypage_url;} else{echo "#";} ?>" target="_blank"><?php echo $city->city; ?></a></td>
		                        <td style="text-align: left; padding: 10px 5px;"><?php echo $city->state; ?></td>
					<td style="text-align: left; padding: 10px 5px;"><a href="edit-coverage-area-city.php?id=<?php echo $city->id; ?>">Edit</a> <a style="margin-left: 10px;" href="#" class="delete-city" data-id="<?php echo $city->id; ?>" data-city="<?php echo $city->city; ?>">Delete</a></td>
                   
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
$(document).on( 'click', '.delete-city', function(){
var th = $(this);
id = $(this).data('id');
city = $(this).data('city');
//console.log(zip);
var r = confirm('Are you sure you want to delete '+city+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/deletecoverageareacity", {id: id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
//console.log(data2);
window.location.href="<?php echo ROOT_URL; ?>/admin-new/coverage-area-cities.php?action=delete-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/coverage-area-cities.php?action=delete-error";
}

});
   
} 
return false;
});
});
</script>