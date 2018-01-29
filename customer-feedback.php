<?php
require_once('api/protected/config/constant.php');

/* ------- recent wash request id ----------- */

$handle = curl_init(ROOT_URL."/api/index.php?r=washing/getwashrequestbyid");
$data = array('id' => $_GET['order_id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$washdet = json_decode($result);
//var_dump($jsondata);
$customer_id = $washdet->order_details->customer_id;
$agent_id = $washdet->order_details->agent_id;
//echo $wash_request_id;

/* ------- recent wash request id end ----------- */

if(isset($_POST['cust_feedback_submit'])){
$rating = $_POST['star'];
//echo $rating;
$comment = '';
$comment = $_POST['feedback'];
//echo $comment;

/* --------- feedback api ------------- */
//echo $wash_request_id;
$handle2 = curl_init(ROOT_URL."/api/index.php?r=customers/customer3hrfeedback");
$fb_id = '';
if(isset($_POST['fb_id'])) $fb_id = $_POST['fb_id']; 
$data2 = array('wash_request_id' => $_GET['order_id'], 'comments' => $comment, 'ratings' => $rating, 'fb_id' => $fb_id, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle2, CURLOPT_POST, true);
curl_setopt($handle2, CURLOPT_POSTFIELDS, $data2);
curl_setopt($handle2,CURLOPT_RETURNTRANSFER,1);
$result2 = curl_exec($handle2);
curl_close($handle2);
$jsondata2 = json_decode($result2);
//var_dump($jsondata2);

/* --------- feedback api ------------- */


}

?>
<?php include_once('header.php'); ?>
<style>
.customer-feedback #content {
    text-align: center;
    background: url('../images/login-header-bg.jpg') no-repeat center top;
    background-size: cover;
    min-height: 500px;
    padding-bottom: 75px;
    padding-top: 25px;
}

.customer-feedback .global-status-text {
    margin-top: 0;
    color: #fff;
    text-align: left;
    font-size: 26px;
}

.customer-feedback .global-status-text span {
    font-weight: normal;
    color: #97d2ff;
    font-size: 24px;
}

.customer-feedback .btn{
display: block;
    padding: 15px;
    color: #fff;
    text-align: center;
    text-decoration: none;
    font-weight: 700;
    background: #076ee1;
    width: 320px;
    margin: 0 auto;
    border: 0;
    cursor: pointer;
    border-radius: 5px;
    font-size: 24px;
    margin-bottom: 30px;
    -webkit-transition: background-color .7s;
    transition: background-color .7s;
    margin-top: 40px;
}

.customer-feedback .btn-red{
 background: #e60f02;   
}


.customer-feedback .fb_id{
display: block; 
float: right; 
padding: 12px;
    text-align: center;
    width: 35%;
    border-radius: 4px;
    border: 0;
    background: #eee;
    box-sizing: border-box;
}

.customer-feedback .block-content input[type="submit"]{
margin-top: 30px;
}

.customer-feedback .stars{
    float: left;
}

@media screen and (max-width: 550px) {
.customer-feedback .fb_id,.customer-feedback .block-content input[type="submit"]{
width: 100%;
} 
}
</style>
    
    <!--Content-->
    <div id="content">
        <div class="wrapper">
        <!--Block-->
              <div class="block">
<?php if($_GET['feedback_type'] == 'cancel'): ?>
                    <h2 class="global-status-text">Order Cancelled / <span>Please leave feedback</span></h2>
<?php else: ?>
<h2 class="global-status-text">MobileWash Complete / <span>Please leave feedback</span></h2>
<?php endif; ?>
                    <div class="block-content">
<?php if($jsondata2->result == 'true'): ?>
 <h2 style='text-align: center; font-weight: 400; font-size: 34px;'>Your feedback has been submitted. Thank you.</h2>
<a href="https://www.yelp.com/biz/mobile-wash-los-angeles-3" class="btn btn-red">Leave a Review on Yelp.com</a>
<a href="<?php echo ROOT_URL; ?>" class="btn">Return to MobileWash.com</a>
<?php endif; ?>
<?php if($jsondata2->result == 'false'): ?>
<p class="err" style="font-size: 22px; margin-top: 0px; background: red; color: #fff; padding: 10px;">Error in submitting your feedback. Please try again.</p>
<?php endif; ?>
<?php if($jsondata2->result != 'true'): ?>
<?php if($_GET['feedback_type'] == 'cancel'): ?>
                    <p style="font-size: 25px; margin-top: 0px;">Please let us know the reason for cancelling this order.</p>
<?php else: ?>
<p style="font-size: 25px; margin-top: 0px;">Let us know what you think about our service.</p>
<?php endif; ?>

                    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
                        <form action="" class="feedback-form" method="post">
                        <p style="margin-top: 0; font-size: 20px; font-weight: bold;">Enter comments:</p>
                        	<textarea name="feedback" id="feedback"></textarea>
<?php if($washdet->order_details->agent_id): ?>                        	
<p style="font-size: 20px; font-weight: bold; margin-bottom: 0;">Rate Washer:</p>
<?php else: ?>
<p style="font-size: 20px; font-weight: bold; margin-bottom: 0;">Rate Our Service:</p>
<?php endif; ?>

                        	<div class="stars">
                        	 <input class="star star-5" id="star-5" type="radio" name="star" value="5" checked />
    <label class="star star-5" for="star-5"></label>
    <input class="star star-4" id="star-4" type="radio" name="star" value="4" />
    <label class="star star-4" for="star-4"></label>
    <input class="star star-3" id="star-3" type="radio" name="star" value="3" />
    <label class="star star-3" for="star-3"></label>
    <input class="star star-2" id="star-2" type="radio" name="star" value="2" />
    <label class="star star-2" for="star-2"></label>
    <input class="star star-1" id="star-1" type="radio" name="star" value="1" />
    <label class="star star-1" for="star-1"></label>
    </div>
    <p style="width: 335px; float: right;">Enter your Facebook or Instagram handle for a chance to win a Free Deluxe Wash every week.</p>
    <div class="clear"></div>
<input type="text" class="fb_id" placeholder="Facebook or Instagram handle" name="fb_id">
<div class="clear"></div>
                        	<input type="submit" value="Submit" name="cust_feedback_submit" class="btn" />
                        	<div class="clear"></div>
                        </form>
<?php endif; ?>
                    </div>
              </div><!--Block End-->

        </div>
    </div>
    <!--Content End-->
    <?php include_once('footer.php'); ?>
    <script>
    $(function(){
 $( ".customer-feedback .block-content .rating > span" ).hover(
  function() {
    $( this ).addClass("active");
  }, function() {
    $( this ).removeClass( "active" );
  }
);

$(".customer-feedback textarea").focus(function(){
$(".customer-feedback .err").hide();
});
    });
    </script>