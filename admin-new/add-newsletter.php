<?php
include('header.php');
 $handle = curl_init(ROOT_URL."/api/index.php?r=site/getallnewslettersubscribers");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
           $subscriber_response = $jsondata->response;
$subscriber_result_code = $jsondata->result;
$mw_all_subscribers = $jsondata->subscribers;

    if(!empty($_POST['newsletter-submit']))
    {

       $title = $_POST['title'];
       $content = $_POST['content'];
       $receivers = $_POST['receiver-list'];
       $receivers_string = '';
       if(count($receivers) > 1){
          $receivers_string = implode(',', $receivers);
           trim($receivers_string, ",");
       }
       else{
            $receivers_string = $receivers[0];
       }

            // COLLECT POST VALUE //

            $data = array('title'=> $title,'content'=> $content,'receivers'=> $receivers_string, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

            // END COLLECT POST VALUE //

            $handle = curl_init(ROOT_URL."/api/index.php?r=site/addnewsletter");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $add_response = $jsondata->response;
            $add_result_code = $jsondata->result;

            //exit;
            if($add_result_code == "true"){

            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/newsletters.php?action=add-newsletter-success"</script>
            <?php
            die();
            //die();
            }

    }
?>

<?php include('right-sidebar.php') ?>
<?php


?>
<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',
relative_urls: false,
convert_urls: false,
remove_script_host : false,
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
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Add Newsletter</span><?php } ?>
                                           
                       <?php if(!empty($_POST['newsletter-submit']) && $add_result_code == 'false'): ?>
                       <p style="text-align: left; clear: both; background: #d40000; color: #fff; padding: 10px;">Error in adding newsletter. Please try again.</p>
                       <?php endif; ?>
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
                                                    <input type="text" class="form-control form-control-inline" name="title" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Content<span style="color: red;">*</span></label>
                                                <div class="col-md-9">
<p>Shortcodes: [USER_FIRSTNAME], [USER_LASTNAME], [USER_FULLNAME]</p>
                                                    <textarea class="form-control" name="content"></textarea>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="control-label col-md-3">Receivers<span style="color: red;">*</span></label>
                                                <div class="col-md-9" style="max-height: 250px; overflow: auto;">
                                                 <input type="checkbox" name="receiver-list[]" id="all-receiver" class="receiver-item" value="all" checked /> All Subscribers<br>
                                                 <?php foreach($mw_all_subscribers as $subscriber): ?>
<input type="checkbox" name="receiver-list[]" class="receiver-item" value="<?php echo trim($subscriber->email); ?>" /> #<?php echo $subscriber->id; ?> - <?php echo $subscriber->email; ?><br>
<?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="col-md-3">&nbsp;</div>
                                            <div class="col-md-3">
                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                <input type="submit" name="newsletter-submit" class="btn blue" value="Save" />
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
$(function(){
$('input[name="receiver-list[]').click(function() {
if($('input[name="receiver-list[]"]:checked').length > 1){
$( "#all-receiver" ).prop( "checked", false );
$( "#all-receiver" ).removeAttr( "checked");
$( "#all-receiver" ).parent().removeClass( "checked");
}
});
});
</script>