<?php
$device_token = '';
if (isset($_COOKIE['mw_admin_auth'])) {
$device_token = $_COOKIE["mw_admin_auth"];
$username = $_COOKIE['mw_username'];
$uid = $_COOKIE['mw_uid'];
}
$userdata = array("mw_uid"=>$uid, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle_data = curl_init("http://www.devmobilewash.com/dev/api/index.php?r=users/checkpermission");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, $userdata);
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result_permission = curl_exec($handle_data);
curl_close($handle_data);
$jsondata_permission = json_decode($result_permission);

if($jsondata_permission->users_type == 'admin'){
    $client_module_permission = $jsondata_permission->client_action;
    $washer_module_permission = $jsondata_permission->washer_action;
    $company_module_permission = $jsondata_permission->company_action;
    $reminder_show = 'none';
     $add_text = 'add';
        $delete_text = 'delete';
        $edit_text = 'edit';
        
    if($client_module_permission == 'no'){
        $client_module_show = 'none';
    }
    else{
        $client_module_show = '';
        $client_action = unserialize($jsondata_permission->client_action);
       
        if (is_array($client_action) &&  in_array($edit_text, $client_action, $strict = FALSE)){
            $edit = '';
        }else{
            $edit = 'none';
        }
        if (is_array($client_action) &&  in_array($add_text, $client_action, $strict = FALSE)){
            $add = '';
        }else{
            $add = 'none';
        }
        if (is_array($client_action) &&  in_array($delete_text, $client_action, $strict = FALSE)){
            $delete = '';
        }else{
            $delete = 'none';
        }
    }
    if($washer_module_permission == 'no'){
        $washer_module_show = 'none';
    }
    else{
        $washer_module_show = '';
        $washer_action = unserialize($jsondata_permission->washer_action);
        
        if (is_array($washer_action) &&  in_array($edit_text, $washer_action, $strict = FALSE)){
            $edit_washer = '';
        }else{
            $edit_washer = 'none';
        }
        if (is_array($washer_action) &&  in_array($add_text, $washer_action, $strict = FALSE)){
            $add_washer = '';
        }else{
            $add_washer = 'none';
        }
        if (is_array($washer_action) &&  in_array($delete_text, $washer_action, $strict = FALSE)){
            $delete_washer = '';
        }else{
            $delete_washer = 'none';
        }
    }
    if($company_module_permission == 'no'){
        $company_module_show = 'none';
    }
    else{
        $company_module_show = '';
        $company_action = unserialize($jsondata_permission->company_action);
            $manage_order = 'manage_order';
            $vehicles_packages = 'vehicles_packages';
            $manage_promotions = 'manage_promotions';
            $opening_hours = 'opening_hours';
            $site_settings = 'site_settings';
            $messages = 'messages';
            $notifications = 'notifications';
            $cms = 'cms';
            $manage_user = 'manage_user';
            $reminder_washer = 'reminder_washer';
            $reminder_client = 'reminder_client';
            $backup_db = 'backup_db';
            $command_center = 'command_center';
            $show_calendar = 'show_calendar';
            $order_calendar = 'order_calendar';
            $review = 'review';
			if(is_array($show_calendar) &&  in_array($show_calendar, $company_action, $strict = FALSE)){
				$checked_show_calendar = 'checked';
				$checked_show_calendar_display = '';
            }else{
				$checked_show_calendar = '';
				$checked_show_calendar_display = 'none';
            }
			if(is_array($review) &&  in_array($review, $company_action, $strict = FALSE)){
				$checked_review_display = 'checked';
				$checked_show_review_display = '';
				
            }else{
				$checked_review_display = '';
				$checked_show_review_display = 'none';
            }
			if(is_array($order_calendar) &&  in_array($order_calendar, $company_action, $strict = FALSE)){
				$checked_order_calendar = 'checked';
				$checked_order_calendar_display = '';
            }else{
				$checked_order_calendar = '';
				$checked_order_calendar_display = 'none';
            }
            if(is_array($company_action) &&  in_array($manage_order, $company_action, $strict = FALSE)){
				$checked_manage_order = 'checked';
				$checked_manage_display = '';
            }else{
				$checked_manage_order = '';
				$checked_manage_display = 'none';
            }
            if(is_array($company_action) &&  in_array($vehicles_packages, $company_action, $strict = FALSE)){
                $checked_vehicles_packages = 'checked';
                $checked_vehicles_display = '';
            }else{
                $checked_vehicles_packages = '';
                $checked_vehicles_display = 'none';
            }
            if(is_array($company_action) &&  in_array($manage_promotions, $company_action, $strict = FALSE)){
                $checked_manage_promotions = 'checked';
                $checked_promotions_display = '';
            }else{
                $checked_manage_promotions = '';
                $checked_promotions_display = 'none';
            }
            if(is_array($company_action) &&  in_array($opening_hours, $company_action, $strict = FALSE)){
                $checked_opening_hours = 'checked';
                $checked_opening_display = '';
            }else{
                $checked_opening_hours = '';
                $checked_opening_display = 'none';
            }
            if(is_array($company_action) &&  in_array($site_settings, $company_action, $strict = FALSE)){
                $checked_site_settings = 'checked';
                $checked_site_display = '';
            }else{
                $checked_site_settings = '';
                $checked_site_display = 'none';
            }
            if(is_array($company_action) &&  in_array($messages, $company_action, $strict = FALSE)){
                $checked_messages = 'checked';
                $checked_messages_display = '';
            }else{
                $checked_messages = '';
                $checked_messages_display = 'none';
            }
            if(is_array($company_action) &&  in_array($notifications, $company_action, $strict = FALSE)){
                $checked_notifications = 'checked';
                $checked_notifications_display = '';
            }else{
                $checked_notifications = '';
                $checked_notifications_display = 'none';
            }
            if(is_array($company_action) &&  in_array($cms, $company_action, $strict = FALSE)){
                $checked_cms = 'checked';
                $checked_cms_display = '';
            }else{
                $checked_cms = '';
                $checked_cms_display = 'none';
            }
            if(is_array($company_action) &&  in_array($manage_user, $company_action, $strict = FALSE)){
                $checked_manage_user = 'checked';
                $checked_user_display = '';
            }else{
                $checked_manage_user = '';
                $checked_user_display = 'none';
            }
            if(is_array($company_action) &&  in_array($reminder_washer, $company_action, $strict = FALSE)){
                $checked_reminder_washer = 'checked';
                $checked_reminderwasher_display = '';
            }else{
                $checked_reminder_washer = '';
                $checked_reminderwasher_display = 'none';
            }
            if(is_array($company_action) &&  in_array($reminder_client, $company_action, $strict = FALSE)){
                $checked_reminder_client = 'checked';
                $checked_reminderclient_display = '';
            }else{
                $checked_reminder_client = '';
                $checked_reminderclient_display = 'none';
            }
            if(is_array($company_action) &&  in_array($backup_db, $company_action, $strict = FALSE)){
                $checked_backup_db = 'checked';
                $checked_backup_db_display = '';
            }else{
                $checked_backup_db = '';
                $checked_backup_db_display = 'none';
            }
            if(is_array($company_action) &&  in_array($command_center, $company_action, $strict = FALSE)){
                $checked_command_center = 'checked';
                $checked_command_center_display = '';
            }else{
                $checked_command_center = '';
                $checked_command_center_display = 'none';
            }
    }
}else{
    
            $checked_manage_order = ' ';
           
                $checked_vehicles_packages = ' ';
            
                $checked_manage_promotions = ' ';
             
                $checked_opening_hours = ' ';
                $checked_site_settings = ' ';
            
                $checked_messages = ' ';
           
                $checked_notifications = ' ';
            
                $checked_cms = ' ';
           
                $checked_manage_user = ' ';
            
                $checked_reminder_washer = ' ';
            
                $checked_reminder_client = ' ';
            
                $checked_backup_db = ' ';
            
                $checked_command_center = ' ';
				
				$checked_show_calendar = ' ';
				
				$checked_order_calendar = ' ';
}
$data = array("device_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=users/authenticate");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($response == "error" && $result_code == "false"){
header("Location: http://www.devmobilewash.com/admin-new/login.php");
die();
}


/* -------- logged in auth end --------- */

parse_str($_SERVER['QUERY_STRING']);
if($_GET['set']=="logout"){
$device_token = $_COOKIE["mw_admin_auth"];
$data = array("device_token"=>$device_token, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
$handle = curl_init("http://www.devmobilewash.com/api/index.php?r=users/logout");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$response = $jsondata->response;
$result_code = $jsondata->result;

if($response == "Successfully logout" && $result_code == "true"){
unset($_COOKIE['mw_username']);
setcookie("mw_admin_auth", "", time() - 3600);
setcookie("mw_username", "", time() - 3600);
header("Location: http://www.devmobilewash.com/admin-new/login.php");
die();
}
else{
header("Location: http://www.devmobilewash.com/admin-new/login.php");
}
}
$pageURL = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$newurl = explode('admin-new/', $pageURL);
$newurl_page = explode('?', $newurl[1]);
$url = $newurl[1];
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Admin Dashboard - MobileWash.com</title>
       
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
		<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        
        <script>

$(function(){




$.getJSON("http://www.devmobilewash.com/api/index.php?r=users/Appstat", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
  $(".clientonline").html(data.Online_Customers);
  $(".clientoffline").html(data.Offline_Customers);
  $(".pendingorder").html(data.Pending_Orders);
   $(".orderprogress").html(data.Processing_Orders);
   $(".agentonline").html(data.Online_Agent);
    $(".busyagents").html(data.busy_Agents);
    $(".offlineagents").html(data.Offline_Agent);
	$(".totalorder").html(data.Completed_Orders);
    $(".todayorder").html(data.Completed_Orders_today);
    $(".cancelorders").html(data.Cancel_Orders);
    $(".cancelordersclient").html(data.Cancel_Orders_Client);
    $(".cancelordersagent").html(data.Cancel_Orders_Agent);


});




});
</script>
        <script type="text/javascript">
var currenttime = '<?php echo date("F d, Y H:i:s", time())?>' 

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
var split = timestring.split(':');
var hours = split[0];
var minutes = split[1];
        var suffix = '';
        if (hours > 11) {
            suffix += "pm";
        } else {
            suffix += "am";
        }
        //var minutes = currentTime.getMinutes()
        if (minutes < 10) {
            minutes = minutes
        }
        if (hours > 12) {
            hours -= 12;
        } else if (hours === 0) {
            hours = 12;
        }
        var time = hours + ":" + minutes+ "" +suffix;
        
        var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd
} 

if(mm<10) {
    mm='0'+mm
} 

today = mm+'-'+dd+'-'+yyyy;
        if(document.getElementById('servertime') != null){
			document.getElementById("servertime").innerHTML=time + ' PST / ' + today+' Los Angeles, CA';
		} 
		if(document.getElementById('servertime_app') != null){
			document.getElementById("servertime_app").innerHTML=time + ' PST / ' + today+' Los Angeles, CA';
		}
		if(document.getElementById('servertime_phone') != null){
			document.getElementById("servertime_phone").innerHTML=time + ' PST / ' + today+' Los Angeles, CA';
		}
		if(document.getElementById('servertime_schedule') != null){
			document.getElementById("servertime_schedule").innerHTML=time + ' PST / ' + today+' Los Angeles, CA';
		}
		
        //document.getElementById("showtime").innerHTML=time + ' PST / ' + today+'<br/> Los Angeles, CA'
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>
        <style>
		.dashboard-stat .details {
  padding-right: 0 !important;
  position: absolute;
  right: 15px;
}
.dashboard-stat .details .number {
  font-size: 34px;
  font-weight: 300;
  letter-spacing: -1px;
  line-height: 36px;
  margin-bottom: 0;
  padding-top: 11px !important;
  text-align: right;
}
.dashboard-stat .details .desc {
  font-size: 12px !important;
  font-weight: 300;
  letter-spacing: 0;
  text-align: right;
}
.dataTables_filter {
  text-align: end !important;
}

.page-sidebar .sidebar-search{
    margin-top: 40px;
}

.page-sidebar .sidebar-search .input-group .form-control{
    color: #fff;
}

.page-sidebar-closed .page-sidebar .sidebar-search{
    margin-top: 22px;
}

.page-header.navbar{
    height: 48px;
}

.page-header.navbar .page-logo{
    width: 235px;
    height: 45px;
}

.page-header.navbar .page-logo img{
      width: 200px;
    margin-top: 4px !important;
}

.page-sidebar-closed .add-bug-btn{
    display: none !important;
}

        </style>
         </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white <?php if(basename($_SERVER['PHP_SELF']) == 'schedule-orders.php') echo "page-sidebar-closed" ?>">
         <?php if(basename($_SERVER['PHP_SELF']) == 'schedule-orders.php' || basename($_SERVER['PHP_SELF']) == 'schedule-orders-new.php'): ?>
         <div class="preloader">
     <div class="loader"></div>
 </div>
 <?php endif; ?>
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="http://www.devmobilewash.com/admin-new/">
                        <img src="images/logo-white2.png" alt="logo" class="logo-default" /> </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="assets/layouts/layout/img/avatar3_small.jpg" />
                                <span class="username username-hide-on-mobile">  <?php echo  $username; ?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="?set=logout">
                                                <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
