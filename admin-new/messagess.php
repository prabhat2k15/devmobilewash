<?php include('header.php') ?>
<?php
    if($company_module_permission == 'no' || $checked_messages == ''){
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

	if(!empty($_GET['action'])){
		$id = $_GET['id'];
		$url = 'http://www.devmobilewash.com/api/index.php?r=twilio/deletemessage&id='.$id;
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
		if($response == "agents deleted" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/messagess.php?dell=cnf"</script>
            <?php
            die();
            }
	}

	if(!empty($_POST['submit'])){
		
		$id = $_POST['id'];
		$phone = $_POST['phone'];
		$message = $_POST['message'];
		//exit;
		$media = $_POST['media'];
		$message_get = explode(',', $phone);
		   $phone = array();
		   foreach($message_get as $message_list){
			   $phone[] = $message_list;
		   }
		   $count = count($phone);
		   
		   $message_data = array();
		   $message_data['message'] = $message;
		    $message_data['media'] = $media;
$message_data['key'] = 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4';

			//$message_data['media'] = $media;
			$message_data = http_build_query($message_data);
		   foreach($phone as $phonenumber){
			   
		if(!empty($media)){
		$url = 'http://www.devmobilewash.com/api/index.php?r=twilio/sendsms&tonumber='.$phonenumber;
		}else{
		$url = 'http://www.devmobilewash.com/api/index.php?r=twilio/sendsms&tonumber='.$phonenumber;	
		}

		$handle = curl_init($url);
        //$data = '';
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $message_data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
         curl_close($handle);
        $jsondata = json_decode($result);
		$result_code = $jsondata->status;

		if($result_code == "queued"){
				$url = 'http://www.devmobilewash.com/api/index.php?r=twilio/reportchange&id='.$id;
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
                if($response == "updated successfully" && $result == "true"){
               ?>
			   <script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/messagess.php"</script>
			   <?php
				}
            
            }
		
		   }

		   
	}
    $url = 'http://www.devmobilewash.com/api/index.php?r=twilio/getmessges';
       
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
/*echo "<pre>";
print_r($jsondata);
echo "<pre>";
exit;*/
?>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    
                    
                    
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
					<?php if(!empty($_GET['dell'])){ ?>
					<p style="text-align: center; color: green;">Successfully Deleted</p>
					<?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase"> Manage Messages</span>
                                    </div>
									
									<div class="caption font-dark" style="display: <?php echo $add_company; ?>">
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="add-message.php">Add New</a></span>
                                    </div>
                                    <div class="caption font-dark" style="display: <?php echo $edit_company; ?>">
                                        <span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="importdata.php">Import Data</a></span>
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
                                                
                                                <th id="setwidth"> Type </th>
                                                <th> Phone </th>
                                                <th> Message </th>
                                                <th> Media </th>
                                                <th> Send SMS </th>
                                                <th> &nbsp; </th>
                                                <th style="display: <?php echo $delete_company; ?>"> &nbsp; </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                  
    foreach($jsondata as $response)
{
$i = 0; 
      foreach($response as $responsemesage)
      {
          $i++;
          ?>
                <tr class="odd gradeX">
                    
                    <td><?php echo $responsemesage->to; ?></td>
                    <td><?php  echo str_replace(',', ', ', $responsemesage->phone ); ?></td>
                    <td><?php echo $responsemesage->message; ?></td>
                    <td><?php echo $responsemesage->media; ?></td>
					<?php if($responsemesage->report == 'sent') {  ?>
					<td><span id="" style="color: #00AFF0; cursor: pointer;">Already sent</span></td>
					<td>&nbsp;</td>
					<?php }else{ ?>
                    <td><span id="form_<?php echo $i; ?>" onclick="myFunction(<?php echo $i; ?>)" style="color: #00AFF0; cursor: pointer;">Send Now</span></td>
					<td><a href="edit-message.php?id=<?php echo $responsemesage->id; ?>">Edit</a></td>
					<?php } ?>
					
					<td><a onclick="return confirm('Are you sure ?')" href="messagess.php?id=<?php echo $responsemesage->id; ?>&action=dell">Delete</a></td>
                    
                </tr>
           <?php
      }
}
?>    
                                        </tbody>
                                    </table>
									 <?php
                 
    foreach($jsondata as $response)
{
$i = 0;  
      foreach($response as $responsemesage)
      {
		  $i++;
          
          ?>
									<form method="post" action="">
									<table style="display: none;">
									<tr><td>
									<input type="hidden" name="id" value="<?php echo $responsemesage->id; ?>"></td></tr>
									
									<tr><td>
									<input type="hidden" name="phone" value="<?php echo $responsemesage->phone; ?>"></td></tr>
									<tr><td>
									<input type="hidden" name="message" value="<?php echo $responsemesage->message; ?>"></td></tr>
									<tr><td>
									<input type="hidden" name="media" value="<?php echo $responsemesage->media; ?>"></td></tr>
									<tr><td>
									<input type="submit" id="sumit_<?php echo $i; ?>" name="submit" value="submit">
									</td></tr>
									</table>
									</form>
									<?php 
	  }
}
									?>
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
			<?php
                 
    foreach($jsondata as $response)
{
$i = 0;  
      foreach($response as $responsemesage)
      {
		  $i++;
          
          ?>
		  
		  <script>
        function myFunction(el) {
                
					if(confirm("Are you sure?")){
         $('#sumit_'+el).trigger('click');
                    return true;
    }
    else{
        return false;
    }
                   
                
            }
            </script>
		  
		  <?php
		  
	  }
}
		  ?>
			<style>
			#setwidth{
				width: 13% !important;
			}
			</style>