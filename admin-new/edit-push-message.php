<?php
include('header.php');
    if(!empty($_POST['hidden']))
    {
       
       $message = $_POST['content'];
       $id = $_POST['id'];
          
            // COLLECT POST VALUE //
            
            $data = array('id'=> $id, 'message'=> $message, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=site/updatepushmessage");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($result_code == "true"){
               header('location: '.ROOT_URL.'/admin-new/push-messages.php?action=update-success');
die();
           
            }
            




    }
?>
<?php
    if($company_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>
<?php include('right-sidebar.php') ?>
<?php
    
        $id = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=site/getpushmessagebyid'; 
            $handle = curl_init($url);
            $data = array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            
            
            
            $content = $jsondata->message->message;
    
?>
<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Edit Push Message</span><?php } ?>
                        
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
                                                <label class="control-label col-md-3">Message</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="content" style="height: 135px;"><?php echo $content; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <div class="col-md-3">
                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
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