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
    if(!empty($_POST['hidden']))
    {
       $to = $_POST['to'];
       $phone = $_POST['phone'];
       $message = $_POST['message'];
       $media = $_POST['media'];  
       $id = $_POST['id'];  
	   
		   $to = $to;
		   $phone = $phone;
		   $message = $message;
		   $media = $media;     
            // COLLECT POST VALUE //
            
            $data = array('to'=> $to,'phone'=> $phone,'message'=> $message,'media'=> $media, 'id'=>$id, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
          

            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=twilio/editmessges");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($response == "update successfully" && $result_code == "true"){
               ?>
			   <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/messagess.php"</script>
			   <?php
            
            }
            else
            {
                $msg = 'Something Wrong';   
            }




    }else{
        $id = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=twilio/getsinglemessage&id='.$id; 
            $handle = curl_init($url);
            $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            
            
            $to = $jsondata->to;
            $phone = $jsondata->phone;
            $message = $jsondata->message;
            $media = $jsondata->media;
    }
?>

<?php if($to != 'custom') { 
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
<?php

} ?>
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
                                                <label class="control-label col-md-3">To</label>
                                                <div class="col-md-3">
                                                    <select class="form-control input-medium" onchange="getval(this);" name="to" required>
                                                        <option value="">Select</option>
                                                        <option value="customer" <?php if($to == 'customer') { echo 'selected="selected"'; } ?>>Customer</option>
                                                        <option value="washer" <?php if($to == 'washer') { echo 'selected="selected"'; } ?>>Washer</option>
                                                        <option value="custom" <?php if($to == 'custom') { echo 'selected="selected"'; } ?>>Custom</option>
                                                    </select>
                                                </div>
                                            </div>
											<div class="form-group" id="phone">
                                                <label class="control-label col-md-3">Phone</label>
                                                <div class="col-md-3">
                                                    <input type="text" required="" name="phone" value="<?php echo $phone; ?>" class="form-control form-control-inline input-medium">
                                                </div>
                                            </div>
                                            <div class="form-group" id="message">
                                                <label class="control-label col-md-3">Message</label>
                                                <div class="col-md-3">
                                                    <textarea class="form-control" name="message" rows="3" required><?php echo $message; ?></textarea>
                                                </div>
                                            </div>
											<div class="form-group" id="media">
                                                <label class="control-label col-md-3">Media</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="media" value="<?php echo $media; ?>" class="form-control form-control-inline input-medium">
                                                </div>
                                            </div>
                                            <div class="form-group" id="save">
												<div class="col-md-3">&nbsp;</div>
												<div class="col-md-3">
													<button type="submit" name="submit" class="btn blue">Save</button>
												</div>
												<input type="hidden" name="id" value="<?php echo $id; ?>">
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