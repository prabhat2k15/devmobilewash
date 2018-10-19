<?php
include('header.php');
 $url = ROOT_URL.'/api/index.php?r=site/getallnewslettersubscribers';

    $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$subscriber_response = $jsondata->response;
$subscriber_result_code = $jsondata->result;
$mw_all_subscribers = $jsondata->subscribers;
$mw_all_subscribers_arr =  array();

foreach($mw_all_subscribers as $ss){
array_push($mw_all_subscribers_arr,$ss->email);
}

//print_r($mw_all_subscribers_arr);

$handle = curl_init(ROOT_URL."/api/index.php?r=site/getnewsletterbyid");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('id' => $_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
           $getletter_response = $jsondata->response;
$getletter_result_code = $jsondata->result;
$getletter = $jsondata->newsletter_details;
$receivers =  explode(",", $getletter->receivers);

?>
<style>
.scan-report{
display: none;
}
.scan-details{
width: 600px;
height: 300px;
overflow: auto;
background: #000;
color: #fff;
margin-bottom: 20px;
}

.scan-details a{
color: #fff;
text-decoration: underline;
}

pre{
    padding: 0;
    background: 0;
    border-radius: 0;
    color: #fff;
    margin: 0;
display: block;
border: 0;
}

.meter {
	height: 18px;  /* Can be anything */
	position: relative;
	background: #999;
	box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
}

.meter > span {
  display: block;
  height: 100%;

  background-color: rgb(43,194,83);
  background-image: linear-gradient(
    center bottom,
    rgb(43,194,83) 37%,
    rgb(84,240,84) 69%
  );
  box-shadow:
    inset 0 2px 9px  rgba(255,255,255,0.3),
    inset 0 -2px 6px rgba(0,0,0,0.4);
  position: relative;
  overflow: hidden;
}

.status-bar{
display: none;
padding: 10px;
color: #fff;
}

.waiting{
background: #ccb700;
}

.done{
background: green;
}
</style>

<?php include('right-sidebar.php') ?>
<?php


?>

<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                     <div class="caption">
                        <i class="icon-settings"></i>
						<?php if(!empty($msg)) { ?> <span class="caption-subject font-dark bold uppercase" style="color: green !important; font-size: 15px !important;"><?php echo $msg; ?></span> <?php } else{ ?>
                        <span class="caption-subject font-dark bold uppercase" style="font-size: 15px !important;">Send Newsletter</span><?php } ?>


                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light form-fit">

                                  <p><a href="#" class="send-start" style="background: #337ab7; color: #fff; border-radius: 5px; padding: 15px; text-decoration: none;">Start Newsletter Delivery</a></p>
<p class="status-bar"></p>

<div class="scan-report">
<div class="scan-details">
</div>
<p>Delivery Successful: <span class="t-pub">0</span></p>
<p>Delivery Failed: <span class="t-unpub">0</span></p>
<div class="meter">
  <span style="width: 0%"></span>
</div>
<p style="text-align: center;"><span class="c-item">0</span>/<span class="t-num">0</span></p>
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
var nid;
$(function(){
nid = "<?php echo $_GET['id']; ?>";
$(".send-start").click(function(){
$(".send-start").hide();
$(".status-bar").show();
$(".status-bar").addClass('waiting');
$(".status-bar").html("Newsletter delivery in progress...");
$(".scan-report").show();

//$(".status-bar").val("Newsletter delivery in progress...");
//$("#scan-abusive-form .scan-trigger").attr('disabled', 'disabled');


all_pets = <?php echo json_encode($receivers); ?>;
 
if($.inArray( "all", all_pets ) != -1){
 $(".scan-report .t-num").html("<?php echo count($mw_all_subscribers_arr); ?>");
all_pets = <?php echo json_encode($mw_all_subscribers_arr); ?>;
}
else{
 $(".scan-report .t-num").html("<?php echo count($receivers); ?>");
all_pets = <?php echo json_encode($receivers); ?>;
}

$( all_pets ).each(function( index, val ) {
      console.log(val);
send_newsletter(val, nid);

});






return false;
});
});

function send_newsletter(email, id){


$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/sendnewsletter", {email: email, newsletter_id: id, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

if(data.result == 'true') {
$(".scan-details").append("<pre>"+email+" - <span style='background: green; color: #fff;'>Send Successful</span></pre>");
var tpub = $(".scan-report .t-pub").html();
if(tpub == 0) tpub = 1;
else tpub++;
$(".scan-report .t-pub").html(tpub);
}

if(data.result == 'false') {
$(".scan-details").append("<pre>"+ email +" - <span style='background: red; color: #fff;'>Send Error</span></pre>");
var tunpub = $(".scan-report .t-unpub").html();
if(tunpub == 0) tunpub = 1;
else tunpub++;
$(".scan-report .t-unpub").html(tunpub);
}


$(".scan-details")[0].scrollTop = $(".scan-details")[0].scrollHeight;

var citem = $(".scan-report .c-item").html();
var total_items = $(".scan-report .t-num").html();
if(citem == 0) citem = 1;
else citem++;
percent = Math.ceil((citem/total_items) * 100);
if(percent > 100) percent = 100;
//console.log(data.abusive_words);
$(".scan-report .c-item").html(citem);
$(".scan-report .meter span").css('width', percent+'%');
if(citem == total_items){
$(".status-bar").addClass('done');
$(".status-bar").html("Newsletter delivery completed");
}
 });


}


</script>