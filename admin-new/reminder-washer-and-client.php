<?php
    if(!empty($_POST['hidden']))
    {
       $client_reminder = $_POST['client_reminder'];
       $washer_reminder = $_POST['washer_reminder'];
          
            // COLLECT POST VALUE //
            
            $data = array('client_reminder'=> $client_reminder,'washer_reminder'=> $washer_reminder, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init("http://www.devmobilewash.com/dev/api/index.php?r=site/addreminder");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($response == "Updated Successfully" && $result_code == "true"){
               
            ?>
            <script type="text/javascript">window.location = "http://www.devmobilewash.com/dev/admin-new/reminder-washer-and-client.php?cnf=done"</script>
            <?php
            die();
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
    if($reminder_show == 'none'){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/dev/admin-new/index.php"</script><?php
    }
?>
<?php include('right-sidebar.php') ?>
<?php
        $url = 'http://www.devmobilewash.com/api/index.php?r=site/getreminders'; 
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $washer_reminder = $jsondata->washer_reminder;
            $client_reminder = $jsondata->client_reminder;
    
?>
<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    'https://fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    'https://www.tinymce.com/css/codepen.min.css'
  ]
});
</script>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                <?php if(!empty($_GET['cnf'])){ ?>
                    <div class="caption" style="text-align: center;">
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px ! important; color: green ! important;">Updated Successfully</span>                        
                    </div>
                    <?php } ?>
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Reminder Washer and Client</span><?php } ?>
                        
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
                                                <label class="control-label col-md-3">Remind Washer</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="washer_reminder"><?php echo $washer_reminder; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Remind Client</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="client_reminder"><?php echo $client_reminder ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3">
                                                <button type="submit" name="submit" class="btn blue">Update</button>
                                            </div>
                                            
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