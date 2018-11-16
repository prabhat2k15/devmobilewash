<?php
include('header.php');
    if(!empty($_POST['hidden']))
    {
       $title = $_POST['title'];
       $content = $_POST['content'];
       $id = $_POST['id'];
          
            // COLLECT POST VALUE //
            
            $data = array('title'=> $title,'id'=> $id,'content'=> $content, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=site/cms");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $response = $jsondata->response;
            $result_code = $jsondata->result;
            
            //exit;
            if($response == "Update Successfully" && $result_code == "true"){
               
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/cms.php?cnf=ok"</script>
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

<?php include('right-sidebar.php') ?>
<?php
    
        $id = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=site/getcmsdataadmin&id='.$id; 
            $handle = curl_init($url);
            $data = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            
            
            $title = $jsondata->title;
            $content = $jsondata->content;
    
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
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Edit CMS</span><?php } ?>
                        
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
                                                <label class="control-label col-md-3">Title<span style="color: red;">*</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control form-control-inline" value="<?php echo $title; ?>" name="title" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Content</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="content"><?php echo $content; ?></textarea>
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