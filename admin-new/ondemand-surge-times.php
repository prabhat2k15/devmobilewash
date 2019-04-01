<?php
include('header.php');

if(isset($_POST['schedule_times_submit'])){
 
 $wash_now_fees_json['yellow'] = $_POST['custom_surge_yellow'];
$wash_now_fees_json['red'] = $_POST['custom_surge_red'];
$wash_now_fees_json['orange'] = $_POST['custom_surge_orange'];
$wash_now_fees_json['purple'] = $_POST['custom_surge_purple'];

 $url = ROOT_URL.'/api/index.php?r=site/updateondemandsurgetimes';

    $handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('mon' => $_POST['mon_time'], 'tue' => $_POST['tue_time'], 'wed' => $_POST['wed_time'], 'thurs' => $_POST['thurs_time'], 'fri' => $_POST['fri_time'], 'sat' => $_POST['sat_time'], 'sun' => $_POST['sun_time'], 'message' => $_POST['business_unavail_notice'], 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4], "admin_username" => $jsondata_permission->user_name));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

 $data = array('ios_wash_now_fee'=> json_encode($wash_now_fees_json), 'android_wash_now_fee'=> json_encode($wash_now_fees_json), 'key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]);

            // END COLLECT POST VALUE //

            $handle = curl_init(ROOT_URL."/api/index.php?r=users/updateappsettingsadmin");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result2 = curl_exec($handle);
            curl_close($handle);
            $jsondata2 = json_decode($result2);


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

<?php include('right-sidebar.php');

 $url = ROOT_URL.'/api/index.php?r=site/getondemandsurgetimes';

    $handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$sched_times = $jsondata->schedule_times;

   $url = ROOT_URL.'/api/index.php?r=users/getappsettings';
            $handle = curl_init($url);
            $data = '';
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => API_KEY, 'api_token' => $finalusertoken, 't1' => $mw_admin_auth_arr[2], 't2' => $mw_admin_auth_arr[3], 'user_type' => 'admin', 'user_id' => $mw_admin_auth_arr[4]));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $appsettings = json_decode($result);
            //echo $appsettings->ios_wash_now_fee->yellow."<br>".$appsettings->ios_wash_now_fee->red;
?>
 <style>
 .portlet-body .col{
     float: left;
     text-align: center;
     margin-right: 50px;
 }

  .portlet-body .col h3{
       text-transform: uppercase;
       font-weight: 500;
       font-size: 22px;
       margin-bottom: 20px;
       margin-top: 10px;
  }

  .portlet-body .col ul{
      list-style: none;
      margin: 0;
      padding: 0;
  }

  .portlet-body .col ul li{
         display: block;
    padding: 8px 10px;
    background: #ccc;
    text-align: left;
    margin-bottom: 8px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
  }

  .portlet-body .col ul li.active{
      background: #63dffb;
  }
  
  .portlet-body .col ul li.surgeactive-yellow{
      background: #ffeb3b;
  }
  
   .portlet-body .col ul li.surgeactive-red{
      background: #FF0000;
  }
  
   .portlet-body .col ul li.surgeactive-orange{
      background: #FF8000;
  }
  
    .portlet-body .col ul li.surgeactive-purple{
      background: #800080;
      color: #fff;
  }

.portlet-body .col ul li.spec-time{
border: 3px solid #FF9800;
}


.portlet-body .price-row{
 
}

.portlet-body .price-row .color-block{
     width: 20px;
    height: 20px;
    background: #ccc;
    display: inline-block;
    vertical-align: middle;
}

.portlet-body .price-row .gray{
 background: #ccc;
}

.portlet-body .price-row .blue{
 background: #63dffb;
}

.portlet-body .price-row .yellow{
 background: #ffeb3b;
}

.portlet-body .price-row .red{
 background: #FF0000;
}

.portlet-body .price-row .orange{
 background: #FF8000;
}

.portlet-body .price-row .purple{
 background: #800080;
}

.portlet-body .price-row .green{
 background: #4ed845;
}

 </style>
<div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content" id="main">

                    <div class="row ">
                        <div class="col-md-12">
<?php
// Change the line below to your timezone!
date_default_timezone_set('America/Los_Angeles');
$date = date('m/d/Y h:i:s a', time());
//echo $date;
?>
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
										<?php if($post_response == "done" && $post_result_code == "true"){  ?>
<p style="background: #8BC34A; color: #fff; padding: 10px;">Update Successful</p>
<?php } ?>

                                <div class="portlet-title">
                                    <div class="caption col-md-7">
                                        <i class="icon-clock font-dark"></i>

										<span class="caption-subject font-dark bold uppercase">On-Demand Surge Times</span>

                                    </div>
                                    <div class="caption caption-md col-md-5">
                                                <span class="last_edit"> Last edited by : (<?php echo $jsondata->edit_by;?> , <?php echo $jsondata->updated_by;?>) </span>
                                                </div>
                                </div>
                                <div class="portlet-body">

                                    <form class="form-inline" method="post" action="" role="form">
                                     <div class="price-row">
                                      <p><span class='color-block gray'></span> - Disabled</p>
                                      <p><span class='color-block blue'></span> - Enabled</p>
                                      <?php if($jsondata_permission->users_type == 'scheduler'): ?>
                                      <p><span class='color-block green'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_purple" id="custom_surgeactive-purple" value = "<?php echo ''; ?>"/></p>
                                      <p><span class='color-block yellow'></span> - Dynamic Wash Now Fee: <?php echo $appsettings->ios_wash_now_fee->yellow; ?></p>
                                      <p><span class='color-block orange'></span> - Dynamic Wash Now Fee: <?php echo $appsettings->ios_wash_now_fee->orange; ?></p>
                                      <p><span class='color-block red'></span> - Dynamic Wash Now Fee: <?php echo $appsettings->ios_wash_now_fee->red; ?></p>
                                      
                                      <p><span class='color-block purple'></span> - Dynamic Wash Now Fee: <?php echo $appsettings->ios_wash_now_fee->purple; ?></p>

                                      <?php else: ?>
                                      <p><span class='color-block green'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_purple" id="custom_surgeactive-purple" value = "<?php echo ''; ?>"/></p>
                                      <p><span class='color-block yellow'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_yellow" id="custom_surgeactive-yellow" value = "<?php echo $appsettings->ios_wash_now_fee->yellow; ?>"/></p>
                                      <p><span class='color-block orange'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_orange" id="custom_surgeactive-orange" value = "<?php echo $appsettings->ios_wash_now_fee->orange; ?>"/></p>
                                      <p><span class='color-block red'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_red" id="custom_surgeactive-red" value = "<?php echo $appsettings->ios_wash_now_fee->red; ?>"/></p>
                                      
                                      <p><span class='color-block purple'></span> - Dynamic Wash Now Fee <input type="text" name="custom_surge_purple" id="custom_surgeactive-purple" value = "<?php echo $appsettings->ios_wash_now_fee->purple; ?>"/></p>

                                      <?php endif; ?>
                                      
                                      <h5>Message (when unavailable)</h5>
                                        <?php if($jsondata_permission->users_type == 'scheduler'): ?>
                                         <p><?php echo $sched_times->message; ?></p>

                                        <?php else: ?>
                                         <textarea style="margin-bottom: 40px; display: block; width: 60%; height: 130px; padding: 5px;" name="business_unavail_notice" id="business_unavail_notice"><?php echo $sched_times->message; ?></textarea>

                                        <?php endif; ?>
                                               
                                     </div>

                                     <div class="col">
                                        <h3>MON</h3>
<?php
$montimes = $sched_times->mon;
$montimes_arr = explode("|", $montimes);
?>
                                        <ul class="times montime">
                                           <li <?php $time_det = explode(",",$montimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>TUE</h3>
                                        <?php
$tuetimes = $sched_times->tue;
$tuetimes_arr = explode("|", $tuetimes);
?>
                                        <ul class="times tuetime">
                                           <li <?php $time_det = explode(",",$tuetimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>WED</h3>
                                         <?php
$wedtimes = $sched_times->wed;
$wedtimes_arr = explode("|", $wedtimes);
?>
                                        <ul class="times wedtime">
                                            <li <?php $time_det = explode(",",$wedtimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>THUR</h3>
<?php
$thurstimes = $sched_times->thurs;
$thurstimes_arr = explode("|", $thurstimes);
?>
                                        <ul class="times thurstime">
                                            <li <?php $time_det = explode(",",$thurstimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>FRI</h3>
 <?php
$fritimes = $sched_times->fri;
$fritimes_arr = explode("|", $fritimes);
?>
                                        <ul class="times fritime">
                                           <li <?php $time_det = explode(",",$fritimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>SAT</h3>
<?php
$sattimes = $sched_times->sat;
$sattimes_arr = explode("|", $sattimes);
?>
                                        <ul class="times sattime">
                                          <li <?php $time_det = explode(",",$sattimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col" style="margin-right: 0;">
                                        <h3>SUN</h3>
<?php
$suntimes = $sched_times->sun;
$suntimes_arr = explode("|", $suntimes);
?>
                                        <ul class="times suntime">
                                           <li <?php $time_det = explode(",",$suntimes_arr[0]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[1]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[2]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[3]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[4]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[5]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[6]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[7]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[8]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[9]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[10]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[11]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[12]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[13]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[14]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[15]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[16]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[17]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[18]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[19]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[20]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[21]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[22]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[23]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[24]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[25]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[26]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[27]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[28]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[29]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[30]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[31]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[32]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[33]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[34]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[35]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[36]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[37]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[38]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[39]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[40]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[41]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[42]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[43]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[44]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[45]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[46]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[47]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[48]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[49]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[50]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[51]); echo "class='".$time_det[1]."'"; echo " data-price='".$time_det[2]."'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div style="clear: both"></div>
                                     <input type="hidden" name="mon_time" id="mon_time" value="<?php echo $sched_times->mon; ?>" />
<input type="hidden" name="tue_time" id="tue_time" value="<?php echo $sched_times->tue; ?>" />
<input type="hidden" name="wed_time" id="wed_time" value="<?php echo $sched_times->wed; ?>" />
<input type="hidden" name="thurs_time" id="thurs_time" value="<?php echo $sched_times->thurs; ?>" />
<input type="hidden" name="fri_time" id="fri_time" value="<?php echo $sched_times->fri; ?>" />
<input type="hidden" name="sat_time" id="sat_time" value="<?php echo $sched_times->sat; ?>" />
<input type="hidden" name="sun_time" id="sun_time" value="<?php echo $sched_times->sun; ?>" />

                                     <p>
                                     <?php if($jsondata_permission->users_type == 'admin'): ?>
                                     <input type="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 7px 20px 7px 20px; border-radius: 3px; margin-top: 30px;" name="schedule_times_submit" value="Update">
<?php endif; ?>
                                     </p>
                                    </form>

                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
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
var surge_price = 0;
 var timearr = [];
    var timestr = '';


function refreshtimesandprice(){
 
 $(".portlet-body .times li.surgeactive-yellow").attr('data-price', $('.portlet-body #custom_surgeactive-yellow').val());
 $(".portlet-body .times li.surgeactive-red").attr('data-price', $('.portlet-body #custom_surgeactive-red').val());
 $(".portlet-body .times li.surgeactive-orange").attr('data-price', $('.portlet-body #custom_surgeactive-orange').val());
 $(".portlet-body .times li.surgeactive-purple").attr('data-price', $('.portlet-body #custom_surgeactive-purple').val());

    timearr = [];
    timestr = '';
    $(".montime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#mon_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".tuetime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#tue_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".wedtime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#wed_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".thurstime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#thurs_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".fritime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#fri_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".sattime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#sat_time').val(timestr);


    timearr = [];
    timestr = '';
    $(".suntime li").each(function(idx, li) {
        var s = '';
        if($(this).attr('class')) s += $(this).html()+","+$(this).attr('class');
        else s += $(this).html()+",inactive";

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#sun_time').val(timestr);


}
<?php if($jsondata_permission->users_type == 'admin'): ?>
$(function(){

$('.portlet-body .col ul li').click(function(){
 var changeclass = 0;
 var surgeclasses = ['inactive', 'active', 'surgeactive-yellow', 'surgeactive-orange', 'surgeactive-red', 'surgeactive-purple'];
 var currentclass = $(this).attr('class');
 var currentclassindex = $.inArray( currentclass, surgeclasses );
 //console.log('current: '+currentclassindex);
  //console.log(currentclassindex + 1);
 if ((currentclassindex + 1) > (surgeclasses.length -1)) {
  currentclassindex = -1;
 }
  $(this).removeClass();
  
  $(this).addClass(surgeclasses[currentclassindex + 1]);
  if($('.portlet-body #custom_'+surgeclasses[currentclassindex + 1]).val()) surge_price = $('.portlet-body #custom_'+surgeclasses[currentclassindex + 1]).val();
  else surge_price = 0;
$(this).attr('data-price', surge_price);

if($(this).parent().hasClass('montime')){
    timearr = [];
    timestr = '';
    $(".montime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#mon_time').val(timestr);
}

if($(this).parent().hasClass('tuetime')){
    timearr = [];
    timestr = '';
    $(".tuetime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#tue_time').val(timestr);
}

if($(this).parent().hasClass('wedtime')){
    timearr = [];
    timestr = '';
    $(".wedtime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#wed_time').val(timestr);
}


if($(this).parent().hasClass('thurstime')){
    timearr = [];
    timestr = '';
    $(".thurstime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#thurs_time').val(timestr);
}

if($(this).parent().hasClass('fritime')){
    timearr = [];
    timestr = '';
    $(".fritime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#fri_time').val(timestr);
}


if($(this).parent().hasClass('sattime')){
    timearr = [];
    timestr = '';
    $(".sattime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#sat_time').val(timestr);
}


if($(this).parent().hasClass('suntime')){
    timearr = [];
    timestr = '';
    $(".suntime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+","+$(this).attr('class');
       

        s += ","+$(this).attr('data-price');
        
        timearr.push(s);
    });
    timestr = timearr.join('|');
    $('#sun_time').val(timestr);
}

});

$(".portlet-body form").submit(function(){
 refreshtimesandprice();
});
});
<?php endif; ?>
</script>