<?php
include('header.php');
if(isset($_POST['schedule_times_submit'])){

 $url = ROOT_URL.'/api/index.php?r=site/updatescheduletimes';

    $handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('mon' => $_POST['mon_time'], 'tue' => $_POST['tue_time'], 'wed' => $_POST['wed_time'], 'thurs' => $_POST['thurs_time'], 'fri' => $_POST['fri_time'], 'sat' => $_POST['sat_time'], 'sun' => $_POST['sun_time'], 'mon_spec' => $_POST['mon_spec_time'], 'tue_spec' => $_POST['tue_spec_time'], 'wed_spec' => $_POST['wed_spec_time'], 'thurs_spec' => $_POST['thurs_spec_time'], 'fri_spec' => $_POST['fri_spec_time'], 'sat_spec' => $_POST['sat_spec_time'], 'sun_spec' => $_POST['sun_spec_time'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

 $data = array('ios_wash_later_fee'=> $_POST['custom_surge'], 'android_wash_later_fee'=> $_POST['custom_surge'], 'ios_wash_now_fee'=> 'dontpass', 'android_wash_now_fee'=> 'dontpass', 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

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
<?php
    if($company_module_permission == 'no' || $checked_opening_hours == ''){
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

<?php include('right-sidebar.php');

 $url = ROOT_URL.'/api/index.php?r=site/getscheduletimes';

    $handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
$sched_times = $jsondata->schedule_times;

   $url = ROOT_URL.'/api/index.php?r=users/getappsettings';
            $handle = curl_init($url);
            $data = '';
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $appsettings = json_decode($result);
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
  
  .portlet-body .col ul li.surgeactive{
      background: #ffeb3b;
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
                                    <div class="caption">
                                        <i class="icon-clock font-dark"></i>

										<span class="caption-subject font-dark bold uppercase">Schedule Times</span>

                                    </div>
                                </div>
                                <div class="portlet-body">

                                    <form class="form-inline" method="post" action="" role="form">
                                     <div class="price-row">
                                      <p><span class='color-block gray'></span> - Surge Price N/A</p>
                                      <p><span class='color-block blue'></span> - Standard Surge Price ($0)</p>
                                      <p><span class='color-block yellow'></span> - Custom Surge Price <input type="text" name="custom_surge" id="custom_surge" value = "<?php echo $appsettings->ios_wash_later_fee; ?>"/></p>
                                     </div>

                                     <div class="col">
                                        <h3>MON</h3>
<?php
$montimes = $sched_times->mon;
$montimes_arr = explode("|", $montimes);
?>
                                        <ul class="times montime">
                                           <li <?php $time_det = explode(",",$montimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$montimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>TUE</h3>
                                        <?php
$tuetimes = $sched_times->tue;
$tuetimes_arr = explode("|", $tuetimes);
?>
                                        <ul class="times tuetime">
                                           <li <?php $time_det = explode(",",$tuetimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$tuetimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>WED</h3>
                                         <?php
$wedtimes = $sched_times->wed;
$wedtimes_arr = explode("|", $wedtimes);
?>
                                        <ul class="times wedtime">
                                            <li <?php $time_det = explode(",",$wedtimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$wedtimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>THUR</h3>
<?php
$thurstimes = $sched_times->thurs;
$thurstimes_arr = explode("|", $thurstimes);
?>
                                        <ul class="times thurstime">
                                            <li <?php $time_det = explode(",",$thurstimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[20]); if($time_det[1] == 'active') echo "class='active'";  if($time_det[1] == 'surgeactive') echo "class='surgeactive'";?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[24]); if($time_det[1] == 'active') echo "class='active'";  if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$thurstimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>FRI</h3>
 <?php
$fritimes = $sched_times->fri;
$fritimes_arr = explode("|", $fritimes);
?>
                                        <ul class="times fritime">
                                           <li <?php $time_det = explode(",",$fritimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$fritimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col">
                                        <h3>SAT</h3>
<?php
$sattimes = $sched_times->sat;
$sattimes_arr = explode("|", $sattimes);
?>
                                        <ul class="times sattime">
                                          <li <?php $time_det = explode(",",$sattimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$sattimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div class="col" style="margin-right: 0;">
                                        <h3>SUN</h3>
<?php
$suntimes = $sched_times->sun;
$suntimes_arr = explode("|", $suntimes);
?>
                                        <ul class="times suntime">
                                           <li <?php $time_det = explode(",",$suntimes_arr[0]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[1]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[2]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[3]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[4]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[5]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[6]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[7]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>8:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[8]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[9]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[10]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[11]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>9:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[12]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[13]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[14]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[15]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>10:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[16]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:00 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[17]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:15 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[18]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:30 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[19]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>11:45 AM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[20]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[21]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[22]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[23]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>12:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[24]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[25]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[26]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[27]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>1:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[28]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[29]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[30]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[31]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>2:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[32]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[33]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[34]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[35]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>3:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[36]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[37]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[38]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[39]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>4:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[40]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[41]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[42]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[43]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>5:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[44]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[45]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[46]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[47]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>6:45 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[48]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:00 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[49]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:15 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[50]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:30 PM</li>
                                           <li <?php $time_det = explode(",",$suntimes_arr[51]); if($time_det[1] == 'active') echo "class='active'"; if($time_det[1] == 'surgeactive') echo "class='surgeactive'"; ?>>7:45 PM</li>
                                        </ul>
                                     </div>
                                     <div style="clear: both"></div>
                                     <input type="hidden" name="mon_time" id="mon_time" />
<input type="hidden" name="tue_time" id="tue_time" />
<input type="hidden" name="wed_time" id="wed_time" />
<input type="hidden" name="thurs_time" id="thurs_time" />
<input type="hidden" name="fri_time" id="fri_time" />
<input type="hidden" name="sat_time" id="sat_time" />
<input type="hidden" name="sun_time" id="sun_time" />

<input type="hidden" name="mon_spec_time" id="mon_spec_time" value="<?php echo $sched_times->mon_spec; ?>" />
<input type="hidden" name="tue_spec_time" id="tue_spec_time" value="<?php echo $sched_times->tue_spec; ?>" />
<input type="hidden" name="wed_spec_time" id="wed_spec_time" value="<?php echo $sched_times->wed_spec; ?>" />
<input type="hidden" name="thurs_spec_time" id="thurs_spec_time" value="<?php echo $sched_times->thurs_spec; ?>" />
<input type="hidden" name="fri_spec_time" id="fri_spec_time" value="<?php echo $sched_times->fri_spec; ?>" />
<input type="hidden" name="sat_spec_time" id="sat_spec_time" value="<?php echo $sched_times->sat_spec; ?>" />
<input type="hidden" name="sun_spec_time" id="sun_spec_time" value="<?php echo $sched_times->sun_spec; ?>" />
                                     <p>
                                     <input type="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 7px 20px 7px 20px; border-radius: 3px; margin-top: 30px;" name="schedule_times_submit" value="Update">

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
var mon_spec = "<?php echo $sched_times->mon_spec; ?>";
var tue_spec = "<?php echo $sched_times->tue_spec; ?>";
var wed_spec = "<?php echo $sched_times->wed_spec; ?>";
var thurs_spec = "<?php echo $sched_times->thurs_spec; ?>";
var fri_spec = "<?php echo $sched_times->fri_spec; ?>";
var sat_spec = "<?php echo $sched_times->sat_spec; ?>";
var sun_spec = "<?php echo $sched_times->sun_spec; ?>";

function refreshtimesandprice(){
  timearr = [];
    timestr = '';
    if($('.portlet-body #custom_surge').val()) surge_price = $('.portlet-body #custom_surge').val();

    $(".montime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == mon_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#mon_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".tuetime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
         if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == tue_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#tue_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".wedtime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == wed_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#wed_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".thurstime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == thurs_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#thurs_time').val(timestr);

 timearr = [];
    timestr = '';

$(".fritime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == fri_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#fri_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".sattime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == sat_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#sat_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".suntime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == sun_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#sun_time').val(timestr);
}

$(function(){

 timearr = [];
    timestr = '';
    if($('.portlet-body #custom_surge').val()) surge_price = $('.portlet-body #custom_surge').val();

    $(".montime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == mon_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#mon_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".tuetime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
         if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == tue_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#tue_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".wedtime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == wed_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#wed_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".thurstime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == thurs_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#thurs_time').val(timestr);

 timearr = [];
    timestr = '';

$(".fritime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == fri_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#fri_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".sattime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == sat_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#sat_time').val(timestr);

 timearr = [];
    timestr = '';

 $(".suntime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
        timearr.push(s);
if($(this).text() == sun_spec) $(this).addClass('spec-time');
    });
    timestr = timearr.join('|');
    $('#sun_time').val(timestr);



$('.portlet-body .col ul li').click(function(){
 var changeclass = 0;
 if($(this).hasClass('active') && (!changeclass)) {
  $(this).removeClass('active');
  $(this).addClass('surgeactive');
  if($('.portlet-body #custom_surge').val()) surge_price = $('.portlet-body #custom_surge').val();
  else surge_price = 0;
  changeclass = 1;
 }
 
  if($(this).hasClass('surgeactive') && (!changeclass)) {
  $(this).removeClass('surgeactive');
  surge_price = 0;
  changeclass = 1;
 }
 
   if((!changeclass)) {
  $(this).addClass('active');
  surge_price = 0;
  changeclass = 1;
 }

if($(this).parent().hasClass('montime')){
    timearr = [];
    timestr = '';
    $(".montime li").each(function(idx, li) {
        var s = '';
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
         if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
        s += $(this).html()+",";
        if($(this).hasClass('active')) s += "active";
        else if($(this).hasClass('surgeactive')) s += "surgeactive";
        else s += "inactive";
        if($(this).hasClass('surgeactive')) s += ","+surge_price;
        else s += ",0";
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
</script>
<script>
$(function(){
$(".times li").mousedown(function(event) {
 if(event.button == 2) { 
$(this).parent().find('li').removeClass('spec-time');
        $(this).addClass("spec-time");
if($(this).parent().hasClass('montime')){
$('#mon_spec_time').val($(this).text());
}
if($(this).parent().hasClass('tuetime')){
$('#tue_spec_time').val($(this).text());
}
if($(this).parent().hasClass('wedtime')){
$('#wed_spec_time').val($(this).text());
}

if($(this).parent().hasClass('thurstime')){
$('#thurs_spec_time').val($(this).text());
}

if($(this).parent().hasClass('fritime')){
$('#fri_spec_time').val($(this).text());
}

if($(this).parent().hasClass('sattime')){
$('#sat_spec_time').val($(this).text());
}

if($(this).parent().hasClass('suntime')){
$('#sun_spec_time').val($(this).text());
}
    }

});

$('.times li').contextmenu(function() {
    return false;
});
});
</script>