<?php include('header.php') ?>
<?php

$userdata = array("user_token"=>$device_token, 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=vehicles/allcustomervehicles");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$allcarsdata = json_decode($result);

//print_r($allcarsdata);

if(isset($_POST['pricing_submit'])){
for($i = 1, $j=0; $i <= count($_POST['price']); $i++, $j++){
 
$vehdata = array('id'=> $i, 'duration' => $_POST['duration'][$j], 'wash_time' => $_POST['wash_time'][$j], 'price' => $_POST['price'][$j], 'price2' => $_POST['price2'][$j], 'price3' => $_POST['price3'][$j], 'price4' => $_POST['price4'][$j], 'description' => $_POST['description'][$j], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4], "admin_username" => $jsondata_permission->user_name);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/updatevehicleplan");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
//$pricingdata = json_decode($result);
}
}

$vehdata = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=washing/getvehicleplans");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $vehdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$pricingdata = json_decode($result);

?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME LAYOUT STYLES -->

<?php include('right-sidebar.php') ?>


<style>
#main{
    background-color: #EEF1F5;
}
.form-group {
    display: inline;
}
.imgbtn{
    text-align: center;
}
.green{
	background-color: green !important;
    border-color: green;
}
.reg-loading{
    display: none;
}
.classic-loading{
    display: none;
}
#regular-packlist{
    display: none;
}
#classic-packlist{
    display: none;
}

.update-price-btn{
   margin-top: 20px;
    display: block;
    background: #004179;
    color: #fff;
    padding: 15px;
    text-decoration: none !important;
    width: 240px;
    color: #fff !important;
}

	.meter {
			height: 20px;  /* Can be anything */
			position: relative;
			margin: 25px 0 20px 0; /* Just for demo spacing */
			background: #555;
		
		
			-webkit-box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
			-moz-box-shadow   : inset 0 -1px 1px rgba(255,255,255,0.3);
			box-shadow        : inset 0 -1px 1px rgba(255,255,255,0.3);
			display: none;
		}
		.meter > span {
			display: block;
			height: 100%;
			  
			background-color: rgb(43,194,83);
			background-image: -webkit-gradient(
			  linear,
			  left bottom,
			  left top,
			  color-stop(0, rgb(43,194,83)),
			  color-stop(1, rgb(84,240,84))
			 );
			background-image: -moz-linear-gradient(
			  center bottom,
			  rgb(43,194,83) 37%,
			  rgb(84,240,84) 69%
			 );
			-webkit-box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			-moz-box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			position: relative;
			overflow: hidden;
		}
		.meter > span:after, .animate > span > span {
			content: "";
			position: absolute;
			top: 0; left: 0; bottom: 0; right: 0;
			background-image: 
			   -webkit-gradient(linear, 0 0, 100% 100%,
			      color-stop(.25, rgba(255, 255, 255, .2)), 
			      color-stop(.25, transparent), color-stop(.5, transparent), 
			      color-stop(.5, rgba(255, 255, 255, .2)), 
			      color-stop(.75, rgba(255, 255, 255, .2)), 
			      color-stop(.75, transparent), to(transparent)
			   );
			background-image: 
				-moz-linear-gradient(
				  -45deg, 
			      rgba(255, 255, 255, .2) 25%, 
			      transparent 25%, 
			      transparent 50%,
			      rgba(255, 255, 255, .2) 50%, 
			      rgba(255, 255, 255, .2) 75%, 
			      transparent 75%, 
			      transparent
			   );
			z-index: 1;
			-webkit-background-size: 50px 50px;
			-moz-background-size: 50px 50px;
			-webkit-animation: move 2s linear infinite;
			   -webkit-border-top-right-radius: 8px;
			-webkit-border-bottom-right-radius: 8px;
			       -moz-border-radius-topright: 8px;
			    -moz-border-radius-bottomright: 8px;
			           border-top-right-radius: 8px;
			        border-bottom-right-radius: 8px;
			    -webkit-border-top-left-radius: 20px;
			 -webkit-border-bottom-left-radius: 20px;
			        -moz-border-radius-topleft: 20px;
			     -moz-border-radius-bottomleft: 20px;
			            border-top-left-radius: 20px;
			         border-bottom-left-radius: 20px;
			overflow: hidden;
		}
		
		.animate > span:after {
			display: none;
		}
		
		@-webkit-keyframes move {
		    0% {
		       background-position: 0 0;
		    }
		    100% {
		       background-position: 50px 50px;
		    }
		}

		.orange > span {
			background-color: #f1a165;
			background-image: -moz-linear-gradient(top, #f1a165, #f36d0a);
			background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f1a165),color-stop(1, #f36d0a));
			background-image: -webkit-linear-gradient(#f1a165, #f36d0a);
		}

		.red > span {
			background-color: #f0a3a3;
			background-image: -moz-linear-gradient(top, #f0a3a3, #f42323);
			background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f0a3a3),color-stop(1, #f42323));
			background-image: -webkit-linear-gradient(#f0a3a3, #f42323);
		}
		
		.nostripes > span > span, .nostripes > span:after {
			-webkit-animation: none;
			background-image: none;
		}
   
</style>
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content" id="main">
                    <!-- BEGIN PAGE HEADER-->


                    <!-- BEGIN PAGE TITLE-->
                   <!-- <h3 class="page-title"> New User Profile | Account
                        <small>user account page</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PROFILE CONTENT -->
                            <div class="profile-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title tabbable-line">
                                                <div class="caption caption-md col-md-7">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Vehicle Pricing</span>
                                                </div>
                                                <div class="caption caption-md col-md-5">
                                                <span class="last_edit"> Last edited by : (<?php echo $pricingdata->plans[0]->created_by;?> , <?php echo $pricingdata->plans[0]->Updated_date;?>) </span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content" style="overflow: auto;">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">

 <?php if($pricingdata->result == 'true'): ?>
<form action="" method="post" id="pricing_form">
<table class="table table-striped table-bordered order-column no-footer">
<tr>
    <th>Title</th>
    <th>Duration</th>
    <th>Wash Time</th>
    <th>Tier 1 Price <span style="color: #076ee1;">(Blue)</span></th>
    <th>Tier 2 Price <span style="color: #f4d942;">(Yellow)</span></th>
    <th>Tier 3 Price <span style="color: #ff5722;">(Red)</span></th>
    <th>Tier 4 Price <span style="color: #800080;">(Purple)</span></th>
    <th>Handling Fee</th>
    <th style="min-width: 200px;">Description</th>
    <th>Vehicle Type</th>
</tr>
<?php foreach($pricingdata->plans as $plan): ?>
<tr>
<td><?php echo $plan->title; ?></td>
<td><input type="text" value="<?php echo $plan->duration; ?>" name="duration[]" required /></td>
<td><input type="text" value="<?php echo $plan->wash_time; ?>" name="wash_time[]" required /></td>
<td><input type="text" value="<?php echo $plan->price; ?>" name="price[]" required /></td>
<td><input type="text" value="<?php echo $plan->tier2_price; ?>" name="price2[]" required /></td>
<td><input type="text" value="<?php echo $plan->tier3_price; ?>" name="price3[]" required /></td>
<td><input type="text" value="<?php echo $plan->tier4_price; ?>" name="price4[]" required /></td>
<td><?php echo $plan->handling_fee; ?></td>
<td><textarea name="description[]" style="width: 100%; height: 100px;"><?php echo $plan->description; ?></textarea></td>
<td><?php echo $plan->vehicle_type; ?></td>
</tr>
<?php endforeach; ?>
</table>
<input type="submit" value="Save" name="pricing_submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px; width: 135px;" />
</form>
<?php endif; ?>

                                                            
                                                        <div class="clear" style="height: 10px;">&nbsp;</div>
                                                         <a href="#" class="update-ct-vehicle-price update-price-btn">Update Customer Vehicles Price</a>   
                                                         <div class="meter">
	<span style="width: 0%;"></span>
</div>

                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- Address INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_5">


                                                            <div class="clear" style="height: 10px;">&nbsp;</div>


                                                    </div>
                                                    <!-- END Address INFO TAB -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
<?php include('footer.php') ?>

<script>


    $(function(){
       
        $(".update-ct-vehicle-price").click(function(){
            var th = $(this);
            $(this).removeClass('update-ct-vehicle-price');
            $(this).html('Update in Progress...');
            $(".meter").show();
            $(".meter span").css('width', '0%');
               var allcars = new Array();
    <?php foreach($allcarsdata->all_vehicles as $key => $val){ 
       
        ?>
        allcars.push('<?php echo $val->id; ?>');
    <?php } ?>
    //console.log(allcars.length);
    total_cars = allcars.length;
    count = 0;
    percent_completed = 0;
     $.each(allcars, function( index, item ) {
         $.post( "<?php echo ROOT_URL; ?>/api/index.php?r=vehicles/updatecustomervehsmle", {vehicle_id: item, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
 //console.log(data);
 count++;
  //console.log(count);
  percent_completed = Math.round((count/total_cars) * 100);
  $(".meter span").css('width', percent_completed+'%');
  if(percent_completed >= 100){
         $(th).addClass('update-ct-vehicle-price');
            $(th).html('Update Customer Vehicles Price');
            $(".meter").hide();
     } 
});
        //console.log(item); 
         
     });
     //console.log(count); 
     
     return false;
        });
        
     
   
    });
</script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
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

