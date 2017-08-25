<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);

if(isset($_POST['pricing_submit'])){
    if(isset($_POST['mon_del_price']) && isset($_POST['mon_prem_price'])){
        $userdata = array("id"=>1, 'deluxe_price' => $_POST['mon_del_price'], 'premium_price' => $_POST['mon_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
      if(isset($_POST['tue_del_price']) && isset($_POST['tue_prem_price'])){
        $userdata = array("id"=>2, 'deluxe_price' => $_POST['tue_del_price'], 'premium_price' => $_POST['tue_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
     if(isset($_POST['wed_del_price']) && isset($_POST['wed_prem_price'])){
        $userdata = array("id"=>3, 'deluxe_price' => $_POST['wed_del_price'], 'premium_price' => $_POST['wed_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
    if(isset($_POST['thu_del_price']) && isset($_POST['thu_prem_price'])){
        $userdata = array("id"=>4, 'deluxe_price' => $_POST['thu_del_price'], 'premium_price' => $_POST['thu_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
    if(isset($_POST['fri_del_price']) && isset($_POST['fri_prem_price'])){
        $userdata = array("id"=>5, 'deluxe_price' => $_POST['fri_del_price'], 'premium_price' => $_POST['fri_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
    if(isset($_POST['sat_del_price']) && isset($_POST['sat_prem_price'])){
        $userdata = array("id"=>6, 'deluxe_price' => $_POST['sat_del_price'], 'premium_price' => $_POST['sat_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
    
     if(isset($_POST['sun_del_price']) && isset($_POST['sun_prem_price'])){
        $userdata = array("id"=>7, 'deluxe_price' => $_POST['sun_del_price'], 'premium_price' => $_POST['sun_prem_price'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/updatesurgeprice");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
    }
}

 $userdata = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/api/index.php?r=site/getsurgeprices");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$pricedata = json_decode($result);

?>
<?php
    if($company_module_permission == 'no' || $checked_vehicles_packages == ''){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/index.php"</script><?php
    }
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
       <?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<?php endif; ?>

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
                                                <div class="caption caption-md">
                                                    <i class="icon-globe theme-font hide"></i>
                                                    <span class="caption-subject font-blue-madison bold uppercase">Surge Pricing</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">


<form action="" method="post" id="pricing_form">
<table class="table table-striped table-bordered order-column no-footer" style="width: 450px;">
<tr>
    <th></th>
    <th>Deluxe</th>
    <th>Premium</th>
</tr>
<tr>
    <td>MON</td>
    <td>
        <select name="mon_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[0]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[0]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[0]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="mon_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[0]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[0]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[0]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>TUE</td>
    <td>
        <select name="tue_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[1]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[1]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[1]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="tue_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[1]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[1]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[1]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>WED</td>
    <td>
        <select name="wed_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[2]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[2]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[2]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="wed_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[2]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[2]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[2]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>THU</td>
    <td>
        <select name="thu_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[3]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[3]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[3]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="thu_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[3]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[3]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[3]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>FRI</td>
    <td>
        <select name="fri_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[4]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[4]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[4]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="fri_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[4]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[4]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[4]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>SAT</td>
    <td>
        <select name="sat_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[5]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[5]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[5]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="sat_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[5]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[5]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[5]->premium == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
</tr>
<tr>
    <td>SUN</td>
    <td>
        <select name="sun_del_price">
            <option value="0" <?php if(!$pricedata->surge_prices[6]->deluxe) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[6]->deluxe == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[6]->deluxe == 10) echo "selected"; ?>>$10</option>
        </select>
    </td>
    <td>
        <select name="sun_prem_price">
            <option value="0" <?php if(!$pricedata->surge_prices[6]->premium) echo "selected"; ?>>$0</option>
            <option value="5" <?php if($pricedata->surge_prices[6]->premium == 5) echo "selected"; ?>>$5</option>
            <option value="10" <?php if($pricedata->surge_prices[6]->premium == 10) echo "selected"; ?>>$10</option>
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
         $.post( "http://www.devmobilewash.com/api/index.php?r=vehicles/updatecustomervehsmle", {vehicle_id: item, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
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
