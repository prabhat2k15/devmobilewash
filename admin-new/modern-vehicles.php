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
    $url = ROOT_URL.'/api/index.php?r=vehicles/getallmodernvehicles';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

$all_vehicles = $jsondata->all_vehicles;

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

                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Modern Vehicles</span>
                                    </div>
                                    <div class="caption font-dark">
                                        
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                    <div class="caption font-dark" style="display: <?php echo $add_company; ?>">
                                        
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                    <?php if($_GET['action'] == 'delete-success'): ?>
<p style="text-align: left; clear: both; background: green; color: #fff; padding: 10px;"><span style="display: block; float: left;">Vehicle #<?php echo $_GET['nid']; ?> deleted successfully</span><a href="/admin-new/modern-vehicles.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
<?php if($_GET['action'] == 'delete-error'): ?>
<p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;"><span style="display: block; float: left;">Error in deleting vehicle. Please try again.</span><a href="/admin-new/modern-vehicles.php" style="color: #fff; text-align: right; display: block; float: right;">X</a><span style="display: block; clear: both;"></span></p>
<?php endif; ?>
                                </div>
                                <div class="portlet-body">
                                 <?php if(count($all_vehicles) > 0){ ?>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th> &nbsp; </th>
                                                <th> ID </th>
                                                <th> Make </th>
                                                <th> Model </th>
                                                <th> Type </th>
                                                <th> Category </th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($all_vehicles as $vehicle){ ?>
                <tr class="odd gradeX">
                    <td><a href="edit-vehicle.php?id=<?php echo $vehicle->id; ?>&build=regular" style="margin-right: 10px;">Edit</a> <a class='delete-car' href="#" data-id="<?php echo $vehicle->id; ?>" data-carname="<?php echo $vehicle->make." ".$vehicle->model; ?>">Delete</a></td>
                   
                    <td><?php echo $vehicle->id; ?></td>
                   <td><?php echo $vehicle->make; ?></td>
                   <td><?php echo $vehicle->model; ?></td>
                   <td><?php echo $vehicle->type; ?></td>
                   <td><?php echo $vehicle->category; ?></td>
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
            <script>
$(function(){
$(document).on( 'click', '.delete-car', function(){
var th = $(this);
id = $(this).data('id');
carname = $(this).data('carname');

var r = confirm('Are you sure you want to delete '+carname+'?');
if (r == true) {
$(th).html('Deleting...');
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=vehicles/deletevehicle", {id: id, build: 'regular', key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
if(data.result == 'true'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/modern-vehicles.php?action=delete-success&nid="+id;
}
if(data.result == 'false'){
window.location.href="<?php echo ROOT_URL; ?>/admin-new/modern-vehicles.php?action=delete-error";
}

});
   
}
return false;
});
});
</script>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->