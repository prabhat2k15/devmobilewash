<?php
include('header.php');
  $handle = curl_init(ROOT_URL."/api/index.php?r=site/getallpromopopups");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $allpops = $jsondata->promo_popups;
            //$result_code = $jsondata->result;

    if(!empty($_POST['promo_submit']))
    {
$homepromoimg = $jsondata->promo_popups[0]->promo_img_url;

if($_POST['home_promo_status']){
  if(!empty($_FILES['home_promo']['tmp_name']))
            {
                $home_promo_pic = $_FILES['home_promo']['tmp_name'];
                $home_promo_pic_type = pathinfo($_FILES['home_promo']['name'], PATHINFO_EXTENSION);
                $md5 = md5(uniqid(rand(), true));
                $picname = $md5.".".$home_promo_pic_type;
                move_uploaded_file($home_promo_pic, '/home/mobilewa/public_html/admin-new/images/promo-img/'.$picname);
                $homepromoimg = ROOT_URL.'/admin-new/images/promo-img/'.$picname;
}

            $data = array('id'=> 1,'promo_img_url'=> $homepromoimg,'promo_status'=> $_POST['home_promo_status'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
          
            // END COLLECT POST VALUE //
            
            $handle = curl_init(ROOT_URL."/api/index.php?r=site/updatepromopopup");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            //$response = $jsondata->response;
            //$result_code = $jsondata->result;


}
           
header('location: '.ROOT_URL.'/admin-new/promo-popups.php');
die();

    }



?>

<?php include('right-sidebar.php') ?>
<style>
.image-upload-btn {
 background: rgb(48, 159, 254);
    border-radius: 5px;
    color: rgb(255, 255, 255);
    display: block;
    padding: 12px;
    text-align: center;
    text-decoration: none;
 
    margin-bottom: 10px;
    width: 200px;
}

.image-upload-btn:hover{
text-decoration: none;
color: #fff;
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
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Promo Popups</span><?php } ?>
                        
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
<?php if($allpops): foreach($allpops as $pop): ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php if($pop->id == 1) echo 'Homepage Promo'; ?></label>
                                                 <div class="col-md-9">
                                                    <select class="form-control input-medium" name="<?php if($pop->id == 1) echo 'home_promo_status'; ?>">
                                                        <option value="enabled" <?php if($pop->promo_status == 'enabled') echo "selected"; ?>>Enabled</option>
                                                        <option value="disabled" <?php if($pop->promo_status == 'disabled') echo "selected"; ?>>Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"></label>
<?php if($pop->id == 1): ?>                                                
<div class="col-md-9">
  <a href="#" class="image-upload-btn" onclick="chooseFile('#home_promo'); return false;">Upload Image</a>
   
                 <div style="height:0px;overflow:hidden">
<?php if($pop->promo_img_url): ?>  
<input type="file" id="home_promo" name="home_promo" onchange="readURL(this, 'home_promo_preview')" />
<?php else: ?>
<input type="file" id="home_promo" name="home_promo" onchange="readURL(this, 'home_promo_preview')" required />
<?php endif; ?>
</div>
<?php if($pop->promo_img_url): ?> 
<a href="<?php echo $pop->promo_img_url; ?>" target="_blank"><img id="home_promo_preview" src="<?php echo $pop->promo_img_url; ?>" class="file_img_preview" style="max-width: 300px;" /></a>
<?php else: ?>
<img id="home_promo_preview" class="file_img_preview" style="max-width: 300px;" />
<?php endif; ?>
                                                                </div>
<?php endif; ?>
                                                </div>
                                            </div>
              <?php endforeach; endif; ?>                             
                                          
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <input type="hidden" name="hidden" value="hidden">
                                            <div class="col-md-3" style="display: <?php echo $add_company; ?>">
                                                <input type="submit" name="promo_submit" class="btn blue" value="Save" />
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
<script>
function chooseFile(fileid) {
      $(fileid).click();
   }

   function readURL(input, imagename) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
         $('#'+imagename).show();
            $('#'+imagename).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


</script>
            
            <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/profile.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>