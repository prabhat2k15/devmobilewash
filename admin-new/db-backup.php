<?php include('header.php') ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
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


</style>
<?php
    
       $url = ROOT_URL.'/api/index.php?r=site/getbackupfile'; 
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $backup = json_decode($result);
       
?>
<style>
.current_tab{
background-color: #5407e2 !important;
border-top: 5px solid #5407e2 !important;
height: 90px !important;
padding: 13px 0 0 10px !important;
cursor: pointer !important;
}
</style>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <div class="clear">&nbsp;</div>

					<?php if(!empty($_GET['backup'])){ ?>
					<p style="text-align: center; color: green;">Backup Generated Successfully</p>
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
                                        <span class="caption-subject bold uppercase"> Manage Database Backup Files</span>
                                    </div>
                                    <div style="margin: -20px 0px 0px 100px; display: none;" class="caption font-dark" id="backup">
                                        <span class="caption-subject bold uppercase"> <img width="84" src="images/loader.gif" class="copy_clients"></span>
                                    </div>
                                    <div style="margin: 0px 0px 0px 20px; color: #55799a; display: <?php echo $edit_washer; ?>" class="actions">
                                        <span class="caption-subject bold uppercase backup" style="cursor: pointer;">Backup</span>
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
                    <th>ID</th>
                    <th>File Name ( Download Link)</th>
                    
                    <th>Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        foreach($backup->data as $file){
                                       
                                       
                                        ?>
                                            <tr class="odd gradeX">
                                                
                                                <td> <?php echo $file->id; ?> </td>
                                                <td> <a href="backup_db/<?php echo $file->filename; ?>" download><?php echo $file->filename; ?></a> </td>
                                                <td> <?php echo $file->date; ?> </td>
												
                                                
                                            </tr>
                                            
                                        <?php 
                                        
                                            
                                        }
                                        
                                        ?>
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
        <style>
        
.page-content-wrapper .page-content{
    padding: 0 20px 10px !important;
}
        </style>
        
<script type="text/javascript">
$('.backup').click(function(){
    $('#backup').show();
    $.getJSON("<?php echo ROOT_URL; ?>/admin-new/copy.php", function( data ) {
if(data.response == 'backup'){
    $('#backup').hide();
    window.location.href = "db-backup.php?backup=true";
}


});
});
</script>    
   