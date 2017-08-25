<?php
    if(!empty($_POST['hidden']))
    {
       $to = $_POST['to'];
       $phone = $_POST['phone'];
       $message = $_POST['message'];
       $media = $_POST['media'];  
       
           $to = $to;
           $phone = $phone;
           $message = $message;
           $media = $media;     
            // COLLECT POST VALUE //
            
            $data = array('to'=> $to,'phone'=> $phone,'message'=> $message,'media'=> $media, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
          

            // END COLLECT POST VALUE //
            
            $handle = curl_init("http://www.devmobilewash.com/api/index.php?r=twilio/messges");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
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
<?php
    if($company_module_permission == 'no'){
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
            $url = 'http://www.devmobilewash.com/api/index.php?r=site/getsiteconfiguration'; 
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            
            
            $value = $jsondata->value;
            $fromdate = $jsondata->fromdate;
            $enddate = $jsondata->enddate;
            $message = $jsondata->message;
    
?>
<style>
#phone{
	display: none;
}
#message{
	display: none;
}
#media{
	display: none;
}
#save{
	display: none;
}
</style>
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
                                    <form action="" method="post" class="form-horizontal form-bordered">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">To<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control input-medium" onchange="getval(this);" name="to" required>
                                                        <option value="">Select</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="washer">Washer</option>
                                                        <option value="custom">Custom</option>
                                                    </select>
                                                </div>
                                            </div>
											<div class="form-group" id="phone">
                                                <label class="control-label col-md-3">Phone<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" required name="phone" title="Phone number with 7-9 and remaing 9 digit with 0-9" class="form-control form-control-inline input-medium">
                                                </div>
                                            </div>
                                            <div class="form-group" id="message">
                                                <label class="control-label col-md-3">Message<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <textarea class="form-control" name="message" rows="3" required></textarea>
                                                </div>
                                            </div>
											<div class="form-group" id="media">
                                                <label class="control-label col-md-3">Media</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="media" class="form-control form-control-inline input-medium">
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
			<script type="text/javascript">
				function getval(sel) {
				   var val = sel.value;
				   if(val == 'custom'){
					   $('#phone').show();
					   $('#message').show();
					   $('#media').show();
					   $('#save').show();
				   }else{
					   $('#phone').hide();
					   $('#message').hide();
					   $('#media').hide();
					   $('#save').hide();
				   }
				}
			</script>