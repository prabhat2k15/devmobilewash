<?php
include('header.php');
    if(!empty($_POST['hidden']))
    {

$client_reminder = array();
foreach($_POST['section-title'] as $ind => $title){
//echo $title."<br>";
$client_reminder[$ind]['title'] = $title;
}

//echo "<br><br><br>";
foreach($_POST['section-content'] as $ind => $content){
//echo $content."<br>";
$client_reminder[$ind]['content'] = $content;
}

// print_r($client_reminder);
$client_reminder_json_arr = json_encode($client_reminder);
//echo $client_reminder_json_arr;


            // COLLECT POST VALUE //

            $data = array('client_reminder'=> $client_reminder_json_arr, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

            // END COLLECT POST VALUE //

            $handle = curl_init(ROOT_URL."/api/index.php?r=site/addreminder");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $post_response = $jsondata->response;
            $post_result_code = $jsondata->result;

    }
?>

<?php include('right-sidebar.php') ?>
<?php
            $url = ROOT_URL.'/api/index.php?r=site/getremindersadmin';
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $client_reminder = $jsondata->client_reminder;

//echo count($client_reminder);



?>
<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',
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

                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Reminder Client</span>

                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light form-fit bordered">
                            <?php if($post_response == "Updated Successfully" && $post_result_code == "true"){ ?>
<p style="background: #8BC34A; color: #fff; padding: 10px; margin-left: 15px; margin-right: 15px;">Update Successful</p>
<?php } ?>
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    <form action="" method="post" class="form-horizontal form-bordered">
                                        <div class="form-body">
                                        <?php if($client_reminder): ?>
                                        <?php foreach($client_reminder as $ind => $creminder): ?>
                                            <div class="form-group" id="sec-<?php echo $ind+1; ?>">
                                                <div class="col-md-7">
                                                <p>Section Title</p>
                                                <input style="margin-bottom: 20px; width: 100%;" type="text" name="section-title[]" id="section-title-<?php echo $ind+1; ?>" value="<?php echo $creminder->title; ?>" />
                                                 <p>Section Content (one point per line)</p>
                                                 <textarea style="margin-bottom: 20px; width: 100%;" name="section-content[]" id="section-content-<?php echo $ind+1; ?>" cols="30" rows="10"><?php echo $creminder->content; ?></textarea>
                                                 <?php if($ind >= 1): ?>
                                                 <p style='text-align: right; margin-top: 15px;'><a href='#' class='remove-sec' data-id="<?php echo $ind+1; ?>">Remove Section</a></p>
                                                 <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                             <div class="form-group" id="sec-1">

                                                <div class="col-md-7">
                                                <p>Section Title</p>
                                                <input style="margin-bottom: 20px; width: 100%;" type="text" name="section-title[]" id="section-title-1" value="" />
                                                 <p>Section Content (one point per line)</p>
                                                 <textarea style="margin-bottom: 20px; width: 100%;" name="section-content[]" id="section-content-1" cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                             <div class="addi-sections">

                                            </div>

                                            <p style="margin-left: 20px; margin-top: 15px;"><a href="#" class="add-new-sec">+ Add New Section</a></p>



                                            <div class="form-group">
                                            <div class="col-md-12">
                                            <input type="hidden" name="hidden" value="hidden">
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
            <script>
            $(function(){
                counter = 1;
                counter = "<?php echo count($client_reminder); ?>";
               $(".add-new-sec").click(function(){
                  counter++;
                  $( ".addi-sections" ).append( "<div class='form-group' id='sec-"+counter+"'><div class='col-md-7'><p>Section Title</p><input style='margin-bottom: 20px; width: 100%;' type='text' name='section-title[]' id='section-title-"+counter+"' value='' /><p>Section Content (one point per line)</p><textarea style='margin-bottom: 20px; width: 100%;' name='section-content[]' id='section-content-"+counter+"' cols='30' rows='10'></textarea><p style='text-align: right; margin-top: 15px;'><a href='#' class='remove-sec' data-id='"+counter+"'>Remove Section</a></p></div></div>" );
                 tinymce.remove();
tinymce.init({
  selector: 'textarea',
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
                  return false;
               });

               $( ".form-body" ).on( "click", ".remove-sec", function() {
                   sec_id = $(this).data('id');
                   $('#sec-'+sec_id).remove();
                   return false;
               });
            });
            </script>