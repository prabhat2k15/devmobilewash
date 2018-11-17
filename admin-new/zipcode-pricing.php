<?php
session_start();
include('header.php');

if(isset($_POST['pricing_submit'])){
        $userdata = array("id"=>1, 'price_unit' => $_POST['price_unit'], 'blue_price' => $_POST['blue_price'], 'yellow_price' => $_POST['yellow_price'], 'red_price' => $_POST['red_price'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/updatezipprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);

    }
    

 $userdata = array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);
$handle_data = curl_init(ROOT_URL."/api/index.php?r=site/getzipprices");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$pricedata = json_decode($result);


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

#pricing_form table td{
    vertical-align: middle;
}

#pricing_form select{
    padding: 5px;
}

.usd-active{
    display: none;
}

</style>
<?php if($pricedata->zipcode_prices[0]->price_unit == 'usd'): ?>
<style>
  .percent-active{
    display: none;
}

.usd-active{
    display: inline;
}
</style>
<?php endif; ?>
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
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Zipcode Pricing</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">


<form action="" method="post" id="pricing_form">
<table class="table table-striped table-bordered order-column no-footer" style="width: 450px;">
<tr>
    
    <th>Blue</th>
    <th>Yellow</th>
    <th>Red</th>
    <th>Unit</th>
</tr>
<tr>
    <td style="min-width: 105px;">
        <span class='usd-active'>$</span><input name="blue_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->blue; ?>" style="width: 70px;" /><span class='percent-active'>%</span>
       
    </td>
    <td style="min-width: 105px;">
        <span class='usd-active'>$</span><input name="yellow_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->yellow; ?>" style="width: 70px;" /><span class='percent-active'>%</span>
       
    </td>
    <td style="min-width: 105px;">
        <span class='usd-active'>$</span><input name="red_price" type="text" value="<?php echo $pricedata->zipcode_prices[0]->red; ?>" style="width: 70px;" /><span class='percent-active'>%</span>
    </td>
    <td style="min-width: 105px;">
        <select id="price_unit" name="price_unit">
            <option value="percent" <?php if($pricedata->zipcode_prices[0]->price_unit == 'percent') echo "selected"; ?>>Percent</option>
            <option value="usd" <?php if($pricedata->zipcode_prices[0]->price_unit == 'usd') echo "selected"; ?>>USD</option>
        </select>
    </td>
</tr>

</table>
<input type="submit" value="Save" name="pricing_submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px; width: 135px;" />
</form>


                                                            
                                                        <div class="clear" style="height: 10px;">&nbsp;</div>
                                                       

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
    $( "#pricing_form #price_unit" ).change(function() {
  console.log($(this).val());
  if ($(this).val() == 'usd'){
    $("#pricing_form .percent-active").hide();
    $("#pricing_form .usd-active").show();
  }
  if ($(this).val() == 'percent'){
    $("#pricing_form .usd-active").hide();
    $("#pricing_form .percent-active").show();
  }
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
