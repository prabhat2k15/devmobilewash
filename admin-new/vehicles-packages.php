<?php include('header.php') ?>
<?php
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
}
$userdata = array("user_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init(ROOT_URL."/api/index.php?r=users/getusertypebytoken");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);
?>
<?php
    if($company_module_permission == 'no' || $checked_vehicles_packages == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
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
<?php
    
            $url = ROOT_URL.'/api/index.php?r=agents/prewasherdetails';
            $handle = curl_init($url);
            $data = array('id'=>$_GET['id'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
			
    
?>
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
                                                    <span class="caption-subject font-blue-madison bold uppercase">Vehicles Packages</span>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a href="#tab_1_1" data-toggle="tab">Regular Vehicles</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_5" data-toggle="tab">Classic Vehicles</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="tab-content">
                                                    <!-- PERSONAL INFO TAB -->
                                                    <div class="tab-pane active" id="tab_1_1">
                                                            <div class="form-group">
                                                                <label class="control-label">Make</label>
                                                                <select name="regular-make" id="regular-make" style="width: 40%;" class="form-control">
                                                                <option value="">Car Make</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Model<img id="regular_data" style="display: none; width: 20px;" src="images/data_load.gif"></label>
                                                                <select name="regular-model" id="regular-model" style="width: 40%;" class="form-control"> 
                                                                <option value="">Car Model</option>
                                                                </select>   
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" id="regular-submit" value="Get Details" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                    <p class="reg-loading">Loading...</p>
                                                    <table id="regular-packlist" class="table table-striped table-bordered table-hover table-checkable order-column">
                                                    <tr>
                                                    <th style="width: 8%;"></th>
                                                    <th>Duration</th>
                                                    <th>Wash time</th>
                                                    <th>Price</th>
                                                    <th>Handling fee</th>
                                                    <th>Description</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Inspection Image</th>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Express</b></td>
                                                    <td class='e-duration'></td>
                                                    <td class='e-time'></td>
                                                    <td class='e-price'></td>
                                                    <td class='e-fee'></td>
                                                    <td class='e-desc'></td>
                                                    <td class='e-type'></td>
                                                    <td class='inspect-img' style="vertical-align: middle;" rowspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Deluxe</b></td>
                                                    <td class='d-duration'></td>
                                                    <td class='d-time'></td>
                                                    <td class='d-price'></td>
                                                    <td class='d-fee'></td>
                                                    <td class='d-desc'></td>
                                                    <td class='d-type'></td>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Premium</b></td>
                                                    <td class='p-duration'></td>
                                                    <td class='p-time'></td>
                                                    <td class='p-price'></td>
                                                    <td class='p-fee'></td>
                                                    <td class='p-desc'></td>
                                                    <td class='p-type'></td>
                                                    </tr>
                                                    </table>

                                                    </div>
                                                    <!-- END PERSONAL INFO TAB -->
                                                    <!-- Address INFO TAB -->
                                                    <div class="tab-pane" id="tab_1_5">
                                                    <div class="form-group">
                                                                <label class="control-label">Make</label>
                                                                <select name="classic-make" id="classic-make" style="width: 40%;" class="form-control">
                                                                <option value="">Car Make</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Model<img id="classic_data" style="display: none; width: 20px;" src="images/data_load.gif"></label>
                                                                <select name="classic-model" id="classic-model" style="width: 40%;" class="form-control"> 
                                                                <option value="">Car Model</option>
                                                                </select>   
                                                            </div>
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                            <div class="margiv-top-10">
                                                                <input type="submit" id="classic-submit" value="Get Details" name="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 6px 7px 7px 6px; border-radius: 3px;" />
                                                            </div>
                                                            
                                                            <div class="clear" style="height: 10px;">&nbsp;</div>
                                                    <p class="classic-loading">Loading...</p>
                                                    <table id="classic-packlist" class="table table-striped table-bordered table-hover table-checkable order-column">
                                                    <tr>
                                                    <th style="width: 8%;"></th>
                                                    <th>Duration</th>
                                                    <th>Wash time</th>
                                                    <th>Price</th>
                                                    <th>Handling fee</th>
                                                    <th>Description</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Inspection Image</th>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Express</b></td>
                                                    <td class='e-duration'></td>
                                                    <td class='e-time'></td>
                                                    <td class='e-price'></td>
                                                    <td class='e-fee'></td>
                                                    <td class='e-desc'></td>
                                                    <td class='e-type'></td>
                                                    <td class='inspect-img' style="vertical-align: middle;" rowspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Deluxe</b></td>
                                                    <td class='d-duration'></td>
                                                    <td class='d-time'></td>
                                                    <td class='d-price'></td>
                                                    <td class='d-fee'></td>
                                                    <td class='d-desc'></td>
                                                    <td class='d-type'></td>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Premium</b></td>
                                                    <td class='p-duration'></td>
                                                    <td class='p-time'></td>
                                                    <td class='p-price'></td>
                                                    <td class='p-fee'></td>
                                                    <td class='p-desc'></td>
                                                    <td class='p-type'></td>
                                                    </tr>
                                                    </table>
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
        
        
       <script>
$(function(){
$.getJSON("../api/index.php?r=vehicles/vehiclemakes", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {


		var vals = [];


				makes = data.vehicle_makes.join(",");
firstmake = '';
				vals = makes.split(",");
firstmake = vals[0];


		var $secondChoice = $("#regular-make");
		$secondChoice.empty();
		$.each(vals, function(index, value) {
			$secondChoice.append("<option value='"+value+"'>" + value + "</option>");
		});


$.getJSON("../api/index.php?r=vehicles/vehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {




			models = data.vehicles.makes[firstmake];





		var $secondChoice = $("#regular-model");
		$secondChoice.empty();
		$.each(models, function(index, value) {
mod = value.split("|");

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});



	});

    $("#regular-submit").click(function() {
$(".reg-loading").show();
make_name = $("#regular-make").val();
//console.log(make_name);
model_no = $("#regular-model").val();
veh_cat = $("#regular-model option:selected").attr('data-cat').toLowerCase();
$.getJSON("../api/index.php?r=washing/plans",  {vehicle_make: make_name, vehicle_model: model_no, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {
$(".reg-loading").hide();
    if(data.result == 'true'){
$("#regular-packlist").show();

$("#regular-packlist .e-duration").html('');
$("#regular-packlist .e-duration").html(data.plans.express[0]['duration']);
$("#regular-packlist .e-time").html('');
$("#regular-packlist .e-time").html(data.plans.express[0]['wash_time']);
$("#regular-packlist .e-price").html('');
$("#regular-packlist .e-price").html("$"+data.plans.express[0]['price']);
$("#regular-packlist .e-fee").html('');
$("#regular-packlist .e-fee").html("$"+data.plans.express[0]['handling_fee']);
$("#regular-packlist .e-type").html('');
$("#regular-packlist .e-type").html(data.plans.express[0]['vehicle_type']);
$("#regular-packlist .e-desc").html('');
desc = '';
$( data.plans.express[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#regular-packlist .e-desc").html(desc);

$("#regular-packlist .inspect-img").html('');
$("#regular-packlist .inspect-img").html("<img src='/admin-new/images/regular-inspect-img/"+veh_cat+".png' title='"+veh_cat+"' style='width: 250px;' />");

$("#regular-packlist .d-duration").html('');
$("#regular-packlist .d-duration").html(data.plans.deluxe[0]['duration']);
$("#regular-packlist .d-time").html('');
$("#regular-packlist .d-time").html(data.plans.deluxe[0]['wash_time']);
$("#regular-packlist .d-price").html('');
$("#regular-packlist .d-price").html("$"+data.plans.deluxe[0]['price']);
$("#regular-packlist .d-fee").html('');
$("#regular-packlist .d-fee").html("$"+data.plans.deluxe[0]['handling_fee']);
$("#regular-packlist .d-type").html('');
$("#regular-packlist .d-type").html(data.plans.deluxe[0]['vehicle_type']);
$("#regular-packlist .d-desc").html('');
desc = '';
$( data.plans.deluxe[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#regular-packlist .d-desc").html(desc);

$("#regular-packlist .inspect-img").html('');
$("#regular-packlist .inspect-img").html("<img src='/admin-new/images/regular-inspect-img/"+veh_cat+".png' title='"+veh_cat+"' style='width: 250px;' />");

$("#regular-packlist .p-duration").html('');
$("#regular-packlist .p-duration").html(data.plans.premium[0]['duration']);
$("#regular-packlist .p-time").html('');
$("#regular-packlist .p-time").html(data.plans.premium[0]['wash_time']);
$("#regular-packlist .p-price").html('');
$("#regular-packlist .p-price").html("$"+data.plans.premium[0]['price']);
$("#regular-packlist .p-fee").html('');
$("#regular-packlist .p-fee").html("$"+data.plans.premium[0]['handling_fee']);
$("#regular-packlist .p-type").html('');
$("#regular-packlist .p-type").html(data.plans.premium[0]['vehicle_type']);
$("#regular-packlist .p-desc").html('');
desc = '';
$( data.plans.premium[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#regular-packlist .p-desc").html(desc);
}
else{
alert('No plans found');
$("#regular-packlist").hide();
}

	});
return false;
});

});

$("#regular-make").change(function() {

	var $dropdown = $(this);

	$.getJSON("../api/index.php?r=vehicles/vehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {

		var key = $dropdown.val();
		var vals = [];


			models = data.vehicles.makes[key].join(",");
				vals = models.split(",");




		var $secondChoice = $("#regular-model");
		$secondChoice.empty();
		$.each(vals, function(index, value) {
mod = value.split("|");

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});
});
</script>

<script>
$(function(){
$.getJSON("../api/index.php?r=vehicles/vehiclemakesclassic", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {


		var vals = [];


				makes = data.vehicle_makes.join(",");
firstmake2 = '';
				vals = makes.split(",");
firstmake2 = vals[0];


		var $secondChoice2 = $("#classic-make");
		$secondChoice2.empty();
		$.each(vals, function(index, value) {
			$secondChoice2.append("<option value='"+value+"'>" + value + "</option>");
		});


$.getJSON("../api/index.php?r=vehicles/classicvehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {




			models = data.vehicles.makes[firstmake2];





		var $secondChoice2 = $("#classic-model");
		$secondChoice2.empty();
		$.each(models, function(index, value) {
mod = value.split("|");

			$secondChoice2.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});



	});

    $("#classic-submit").click(function() {
$(".classic-loading").show();
make_name = $("#classic-make").val();
//console.log(make_name);
model_no = $("#classic-model").val();
veh_cat = $("#classic-model option:selected").attr('data-cat').toLowerCase();
$.getJSON("../api/index.php?r=washing/plans",  {vehicle_make: make_name, vehicle_model: model_no, vehicle_build: 'classic', key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {
$(".classic-loading").hide();
    if(data.result == 'true'){

$("#classic-packlist").show();

$("#classic-packlist .e-duration").html('');
$("#classic-packlist .e-duration").html(data.plans.express[0]['duration']);
$("#classic-packlist .e-time").html('');
$("#classic-packlist .e-time").html(data.plans.express[0]['wash_time']);
$("#classic-packlist .e-price").html('');
$("#classic-packlist .e-price").html("$"+data.plans.express[0]['price']);
$("#classic-packlist .e-fee").html('');
$("#classic-packlist .e-fee").html("$"+data.plans.express[0]['handling_fee']);
$("#classic-packlist .e-type").html('');
$("#classic-packlist .e-type").html(data.plans.express[0]['vehicle_type']);
$("#classic-packlist .e-desc").html('');
desc = '';
$( data.plans.express[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#classic-packlist .e-desc").html(desc);

$("#classic-packlist .inspect-img").html('');
$("#classic-packlist .inspect-img").html("<img src='/admin-new/images/classic-inspect-img/"+veh_cat+".png' title='"+veh_cat+"' style='width: 250px;' />");

$("#classic-packlist .d-duration").html('');
$("#classic-packlist .d-duration").html(data.plans.deluxe[0]['duration']);
$("#classic-packlist .d-time").html('');
$("#classic-packlist .d-time").html(data.plans.deluxe[0]['wash_time']);
$("#classic-packlist .d-price").html('');
$("#classic-packlist .d-price").html("$"+data.plans.deluxe[0]['price']);
$("#classic-packlist .d-fee").html('');
$("#classic-packlist .d-fee").html("$"+data.plans.deluxe[0]['handling_fee']);
$("#classic-packlist .d-type").html('');
$("#classic-packlist .d-type").html(data.plans.deluxe[0]['vehicle_type']);
$("#classic-packlist .d-desc").html('');
desc = '';
$( data.plans.deluxe[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#classic-packlist .d-desc").html(desc);

$("#classic-packlist .inspect-img").html('');
$("#classic-packlist .inspect-img").html("<img src='/admin-new/images/classic-inspect-img/"+veh_cat+".png' title='"+veh_cat+"' style='width: 250px;' />");

$("#classic-packlist .p-duration").html('');
$("#classic-packlist .p-duration").html(data.plans.premium[0]['duration']);
$("#classic-packlist .p-time").html('');
$("#classic-packlist .p-time").html(data.plans.premium[0]['wash_time']);
$("#classic-packlist .p-price").html('');
$("#classic-packlist .p-price").html("$"+data.plans.premium[0]['price']);
$("#classic-packlist .p-fee").html('');
$("#classic-packlist .p-fee").html("$"+data.plans.premium[0]['handling_fee']);
$("#classic-packlist .p-type").html('');
$("#classic-packlist .p-type").html(data.plans.premium[0]['vehicle_type']);
$("#classic-packlist .p-desc").html('');
desc = '';
$( data.plans.premium[0]['description'] ).each(function(ind, val) {
desc += val+"; ";
});
$("#classic-packlist .p-desc").html(desc);
}
else{
alert('No plans found');
$("#classic-packlist").hide();
}

	});
return false;
});

});

$("#classic-make").change(function() {

	var $dropdown = $(this);

	$.getJSON("../api/index.php?r=vehicles/classicvehiclelist", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function(data) {

		var key = $dropdown.val();
		var vals = [];


			models = data.vehicles.makes[key].join(",");
				vals = models.split(",");




		var $secondChoice = $("#classic-model");
		$secondChoice.empty();
		$.each(vals, function(index, value) {
mod = value.split("|");

			$secondChoice.append("<option value='"+mod[0]+"' data-cat='"+mod[1]+"'>" + mod[0] + "</option>");
		});

	});
});
</script>
        