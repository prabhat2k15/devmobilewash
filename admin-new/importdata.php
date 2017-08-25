<?php
if ($_FILES['csv']['size'] > 0) { 

    //get the csv file 
    $file = $_FILES['csv']['tmp_name']; 
    $handle = fopen($file,"r"); 
     
    //loop through the csv file and insert into database 
    $array = array();
    do { 
        if ($data[3]) { 
            /*mysql_query("INSERT INTO contacts (contact_first, contact_last, contact_email) VALUES 
                ( 
                    '".addslashes($data[0])."', 
                    '".addslashes($data[1])."', 
                    '".addslashes($data[2])."' 
                ) 
            ");*/ 
            $array[] = '+1'.$data[3];
        } 
    } while ($data = fgetcsv($handle,1000,",","'")); 
    // 
    
    unset($array[0]) ;
    
    $val = implode(',', $array);
    $dataval = array('to'=> 'custom','phone'=> $val,'message'=> 'Sign-up to use the MobileWash App! http://www.devmobilewash.com/register/','media'=> 'http://www.devmobilewash.com/documents/mw-icon.png', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=twilio/messges");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $dataval);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($response == "Message successfully store" && $result_code == "true"){
               
            $msg = 'Successfully Saved';
            ?>
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/messagess.php"</script>
            <?php
            //die();
            }
            else
            {
                $msg = 'Something Wrong';   
            }

} 
?>
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
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Messages</span><?php } ?>
                        
                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light form-fit bordered">
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                                        <div class="form-body">
											<div class="form-group" id="phone">
                                                <label class="control-label col-md-3">Phone</label>
                                                <div class="col-md-3">
                                                    <input type="file" name="csv">
                                                </div>
                                            </div>
                                            <div class="form-group" id="save">
												<div class="col-md-3">&nbsp;</div>
												<div class="col-md-3">
													<button type="submit" name="submit" class="btn blue">Save</button>
												</div>
												<input type="hidden" name="hidden" value="hidden">
											</div>
                                        </div>
                                    </form>
                                  <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
			
            <?php include('footer.php') ?>
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
			