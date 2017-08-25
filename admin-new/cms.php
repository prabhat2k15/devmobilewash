<?php
if(!empty($_GET['action'])){
        $id = $_GET['id'];
        $url = 'http://www.devmobilewash.com/api/index.php?r=site/deletecms&id='.$id;
        $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;
        
        if($response == "delete" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/cms.php?dell=cnf"</script>
            <?php
            die();
            }
    }
    
?>
<?php include('header.php') ?>
<?php
    if($company_module_permission == 'no' || $checked_cms == ''){
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
    $url = 'http://www.devmobilewash.com/api/index.php?r=site/getallcms';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;
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

                    <?php if(!empty($_GET['dell'])){ ?>
                    <p style="text-align: center; color: green;">Successfully Deleted</p>
                    <?php } ?>
                    <?php if(!empty($_GET['cnf'])){ ?>
                    <p style="text-align: center; color: green;">Successfully Save</p>
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
                                        <span class="caption-subject bold uppercase"> CMS</span>
                                    </div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
                                 
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="example1">
                                        <thead>
                                            <tr>
                                                <th style="display: none;"> &nbsp; </th>
                                                <th> Tilte </th>
                                                <th> Edit </th>
                                                <th> Delete </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                   <?php foreach($jsondata as $response){
                                            foreach($response as $responsecms){ ?>
                <tr class="odd gradeX">
                    <td style="display: none;"><a href="#">&nbsp;</a></td>
                    <td><?php echo $responsecms->title; ?></td>
                    <td><a href="edit-cms.php?id=<?php echo $responsecms->id; ?>">Edit</a></td>
                    <td><a onclick="return confirm('Are you sure ?')" href="cms.php?action=del&id=<?php echo $responsecms->id; ?>">Delete</a></td>

                </tr>
                <?php } }?>
                                      </tbody>
                                    </table>
                                    
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
            <?php include('footer.php') ?>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->