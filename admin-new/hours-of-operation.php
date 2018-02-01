<?php
include('header.php');
    if(!empty($_POST['submit']))
    {

            $data = $_POST;

            $handle = curl_init(ROOT_URL."/api/index.php?r=site/shedule");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $post_response = $jsondata->response;
            $post_result_code = $jsondata->result;

    }

            $url = ROOT_URL.'/api/index.php?r=site/sheduleresult';
            $handle = curl_init($url);
            $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            $monday_from = $jsondata->monday[0]->from;
            $monday_to = $jsondata->monday[0]->to;
            $monday_off = $jsondata->monday[0]->status;
            $monday_all_day = $jsondata->monday[0]->open_all_day;

            $tuesday_from = $jsondata->tuesday[0]->from;
            $tuesday_to = $jsondata->tuesday[0]->to;
            $tuesday_off = $jsondata->tuesday[0]->status;
            $tuesday_all_day = $jsondata->tuesday[0]->open_all_day;

            $wednesday_from = $jsondata->wednesday[0]->from;
            $wednesday_to = $jsondata->wednesday[0]->to;
            $wednesday_off = $jsondata->wednesday[0]->status;
             $wednesday_all_day = $jsondata->wednesday[0]->open_all_day;

            $thursday_from = $jsondata->thursday[0]->from;
            $thursday_to = $jsondata->thursday[0]->to;
            $thursday_off = $jsondata->thursday[0]->status;
            $thursday_all_day = $jsondata->thursday[0]->open_all_day;

            $friday_from = $jsondata->friday[0]->from;
            $friday_to = $jsondata->friday[0]->to;
            $friday_off = $jsondata->friday[0]->status;
            $friday_all_day = $jsondata->friday[0]->open_all_day;

            $saturday_from = $jsondata->saturday[0]->from;
            $saturday_to = $jsondata->saturday[0]->to;
            $saturday_off = $jsondata->saturday[0]->status;
            $saturday_all_day = $jsondata->saturday[0]->open_all_day;

            $sunday_from = $jsondata->sunday[0]->from;
            $sunday_to = $jsondata->sunday[0]->to;
            $sunday_off = $jsondata->sunday[0]->status;
            $sunday_all_day = $jsondata->sunday[0]->open_all_day;

$status_text = $jsondata->status_text[0]->status;

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

<?php include('right-sidebar.php') ?>
<script>

            $(document).ready(function() {
                var dt = new Date();
                var suffix = '';
                if (dt.getHours() > 11) {
                    suffix += "PM";
                    } else {
                    suffix += "AM";
                    }
                var date = new Date();
                var hour = date.getHours() - (date.getHours() >= 12 ? 12 : 0);
                var period = date.getHours() >= 12 ? 'PM' : 'AM';
                var time = hour + ":" + dt.getMinutes() + ":" + dt.getSeconds() +' '+ period;

                $('#monday_off').click(function(event){
                    if ($('input#monday_off').prop('checked')) {
                        $(".monday_off").addClass("checked");
                         $(".monday_all_day").removeClass("checked");
                        $("#from-monday").prop('disabled', true);
                        $("#to-monday").prop('disabled', true);
                        $("#from-monday").val("");
                        $("#to-monday").val("");
                    }else{
                        $(".monday_off").removeClass("checked");
                        $("#from-monday").prop('disabled', false);
                        $("#to-monday").prop('disabled', false);
                        var from_monday = '<?php echo $monday_from; ?>';
                        var to_monday = '<?php echo $monday_to; ?>';
                        if(from_monday == ''){
                            $("#from-monday").val(time);
                        }else{
                            $("#from-monday").val(from_monday);
                        }
                        if(to_monday == ''){
                            $("#to-monday").val(time);
                        }else{
                            $("#to-monday").val(to_monday);
                        }
                    }
                });

                 $('#monday_all_day').click(function(event){
                    if ($('input#monday_all_day').prop('checked')) {
                        $(".monday_all_day").addClass("checked");
                         $(".monday_off").removeClass("checked");
                        $("#from-monday").prop('disabled', true);
                        $("#to-monday").prop('disabled', true);
                        $("#from-monday").val("");
                        $("#to-monday").val("");
                    }else{
                        $(".monday_all_day").removeClass("checked");
                        $("#from-monday").prop('disabled', false);
                        $("#to-monday").prop('disabled', false);
                        var from_monday = '<?php echo $monday_from; ?>';
                        var to_monday = '<?php echo $monday_to; ?>';
                        if(from_monday == ''){
                            $("#from-monday").val(time);
                        }else{
                            $("#from-monday").val(from_monday);
                        }
                        if(to_monday == ''){
                            $("#to-monday").val(time);
                        }else{
                            $("#to-monday").val(to_monday);
                        }
                    }
                });

                $('#tuesday_off').click(function(event){
                    if ($('input#tuesday_off').prop('checked')) {
                        $(".tuesday_off").addClass("checked");
                         $(".tuesday_all_day").removeClass("checked");
                        $("#from-tuesday").prop('disabled', true);
                        $("#to-tuesday").prop('disabled', true);
                        $("#from-tuesday").val("");
                        $("#to-tuesday").val("");
                    }else{
                        $(".tuesday_off").removeClass("checked");
                        $("#from-tuesday").prop('disabled', false);
                        $("#to-tuesday").prop('disabled', false);
                        var from_tuesday = '<?php echo $tuesday_from; ?>';
                        var to_tuesday = '<?php echo $tuesday_to; ?>';
                        if(from_tuesday == ''){
                            $("#from-tuesday").val(time);
                        }else{
                            $("#from-tuesday").val(from_tuesday);
                        }
                        if(to_tuesday == ''){
                            $("#to-tuesday").val(time);
                        }else{
                            $("#to-tuesday").val(to_tuesday);
                        }
                    }
                });

                $('#tuesday_all_day').click(function(event){
                    if ($('input#tuesday_all_day').prop('checked')) {
                        $(".tuesday_all_day").addClass("checked");
                         $(".tuesday_off").removeClass("checked");
                        $("#from-tuesday").prop('disabled', true);
                        $("#to-tuesday").prop('disabled', true);
                        $("#from-tuesday").val("");
                        $("#to-tuesday").val("");
                    }else{
                        $(".tuesday_all_day").removeClass("checked");
                        $("#from-tuesday").prop('disabled', false);
                        $("#to-tuesday").prop('disabled', false);
                        var from_tuesday = '<?php echo $tuesday_from; ?>';
                        var to_tuesday = '<?php echo $tuesday_to; ?>';
                        if(from_tuesday == ''){
                            $("#from-tuesday").val(time);
                        }else{
                            $("#from-tuesday").val(from_tuesday);
                        }
                        if(to_tuesday == ''){
                            $("#to-tuesday").val(time);
                        }else{
                            $("#to-tuesday").val(to_tuesday);
                        }
                    }
                });

                $('#wednesday_off').click(function(event){
                    if ($('input#wednesday_off').prop('checked')) {
                        $(".wednesday_off").addClass("checked");
                         $(".wednesday_all_day").removeClass("checked");
                        $("#from-wednesday").prop('disabled', true);
                        $("#to-wednesday").prop('disabled', true);
                        $("#from-wednesday").val("");
                        $("#to-wednesday").val("");
                    }else{
                        $(".wednesday_off").removeClass("checked");
                        $("#from-wednesday").prop('disabled', false);
                        $("#to-wednesday").prop('disabled', false);
                        var from_wednesday = '<?php echo $wednesday_from; ?>';
                        var to_wednesday = '<?php echo $wednesday_to; ?>';
                        if(from_wednesday == ''){
                            $("#from-wednesday").val(time);
                        }else{
                            $("#from-wednesday").val(from_wednesday);
                        }
                        if(to_wednesday == ''){
                            $("#to-wednesday").val(time);
                        }else{
                            $("#to-wednesday").val(to_wednesday);
                        }
                    }
                });

                 $('#wednesday_all_day').click(function(event){
                    if ($('input#wednesday_all_day').prop('checked')) {
                        $(".wednesday_all_day").addClass("checked");
                         $(".wednesday_off").removeClass("checked");
                        $("#from-wednesday").prop('disabled', true);
                        $("#to-wednesday").prop('disabled', true);
                        $("#from-wednesday").val("");
                        $("#to-wednesday").val("");
                    }else{
                        $(".wednesday_all_day").removeClass("checked");
                        $("#from-wednesday").prop('disabled', false);
                        $("#to-wednesday").prop('disabled', false);
                        var from_wednesday = '<?php echo $wednesday_from; ?>';
                        var to_wednesday = '<?php echo $wednesday_to; ?>';
                        if(from_wednesday == ''){
                            $("#from-wednesday").val(time);
                        }else{
                            $("#from-wednesday").val(from_wednesday);
                        }
                        if(to_wednesday == ''){
                            $("#to-wednesday").val(time);
                        }else{
                            $("#to-wednesday").val(to_wednesday);
                        }
                    }
                });

                $('#thursday_off').click(function(event){
                    if ($('input#thursday_off').prop('checked')) {
                        $(".thursday_off").addClass("checked");
                        $(".thursday_all_day").removeClass("checked");
                        $("#from-thursday").prop('disabled', true);
                        $("#to-thursday").prop('disabled', true);
                        $("#from-thursday").val("");
                        $("#to-thursday").val("");
                    }else{
                        $(".thursday_off").removeClass("checked");
                        $("#from-thursday").prop('disabled', false);
                        $("#to-thursday").prop('disabled', false);
                        var from_thursday = '<?php echo $thursday_from; ?>';
                        var to_thursday = '<?php echo $thursday_to; ?>';
                        if(from_thursday == ''){
                            $("#from-thursday").val(time);
                        }else{
                            $("#from-thursday").val(from_thursday);
                        }
                        if(to_thursday == ''){
                            $("#to-thursday").val(time);
                        }else{
                            $("#to-thursday").val(to_thursday);
                        }
                    }
                });

                 $('#thursday_all_day').click(function(event){
                    if ($('input#thursday_all_day').prop('checked')) {
                        $(".thursday_all_day").addClass("checked");
                         $(".thursday_off").removeClass("checked");
                        $("#from-thursday").prop('disabled', true);
                        $("#to-thursday").prop('disabled', true);
                        $("#from-thursday").val("");
                        $("#to-thursday").val("");
                    }else{
                        $(".thursday_all_day").removeClass("checked");
                        $("#from-thursday").prop('disabled', false);
                        $("#to-thursday").prop('disabled', false);
                        var from_thursday = '<?php echo $thursday_from; ?>';
                        var to_thursday = '<?php echo $thursday_to; ?>';
                        if(from_thursday == ''){
                            $("#from-thursday").val(time);
                        }else{
                            $("#from-thursday").val(from_thursday);
                        }
                        if(to_thursday == ''){
                            $("#to-thursday").val(time);
                        }else{
                            $("#to-thursday").val(to_thursday);
                        }
                    }
                });

                $('#friday_off').click(function(event){
                    if ($('input#friday_off').prop('checked')) {
                        $(".friday_off").addClass("checked");
                        $("#from-friday").prop('disabled', true);
                        $("#to-friday").prop('disabled', true);
                        $("#from-friday").val("");
                        $("#to-friday").val("");
                    }else{
                        $(".friday_off").removeClass("checked");
                        $("#from-friday").prop('disabled', false);
                        $("#to-friday").prop('disabled', false);
                        var from_friday = '<?php echo $friday_from; ?>';
                        var to_friday = '<?php echo $friday_to; ?>';
                        if(from_friday == ''){
                            $("#from-friday").val(time);
                        }else{
                            $("#from-friday").val(from_friday);
                        }
                        if(to_friday == ''){
                            $("#to-friday").val(time);
                        }else{
                            $("#to-friday").val(to_friday);
                        }
                    }
                });

                  $('#friday_all_day').click(function(event){
                    if ($('input#friday_all_day').prop('checked')) {
                        $(".friday_all_day").addClass("checked");
                         $(".friday_off").removeClass("checked");
                        $("#from-friday").prop('disabled', true);
                        $("#to-friday").prop('disabled', true);
                        $("#from-friday").val("");
                        $("#to-friday").val("");
                    }else{
                        $(".friday_all_day").removeClass("checked");
                        $("#from-friday").prop('disabled', false);
                        $("#to-friday").prop('disabled', false);
                        var from_friday = '<?php echo $friday_from; ?>';
                        var to_friday = '<?php echo $friday_to; ?>';
                        if(from_friday == ''){
                            $("#from-friday").val(time);
                        }else{
                            $("#from-friday").val(from_friday);
                        }
                        if(to_friday == ''){
                            $("#to-friday").val(time);
                        }else{
                            $("#to-friday").val(to_friday);
                        }
                    }
                });

                $('#saturday_off').click(function(event){
                    if ($('input#saturday_off').prop('checked')) {
                        $(".saturday_off").addClass("checked");
                         $(".saturday_all_day").removeClass("checked");
                        $("#from-saturday").prop('disabled', true);
                        $("#to-saturday").prop('disabled', true);
                        $("#from-saturday").val("");
                        $("#to-saturday").val("");
                    }else{
                        $(".saturday_off").removeClass("checked");
                        $("#from-saturday").prop('disabled', false);
                        $("#to-saturday").prop('disabled', false);
                        var from_saturday = '<?php echo $saturday_from; ?>';
                        var to_saturday = '<?php echo $saturday_to; ?>';
                        if(from_saturday == ''){
                            $("#from-saturday").val(time);
                        }else{
                            $("#from-saturday").val(from_saturday);
                        }
                        if(to_saturday == ''){
                            $("#to-saturday").val(time);
                        }else{
                            $("#to-saturday").val(to_saturday);
                        }
                    }
                });

                 $('#saturday_all_day').click(function(event){
                    if ($('input#saturday_all_day').prop('checked')) {
                        $(".saturday_all_day").addClass("checked");
                         $(".saturday_off").removeClass("checked");
                        $("#from-saturday").prop('disabled', true);
                        $("#to-saturday").prop('disabled', true);
                        $("#from-saturday").val("");
                        $("#to-saturday").val("");
                    }else{
                        $(".saturday_all_day").removeClass("checked");
                        $("#from-saturday").prop('disabled', false);
                        $("#to-saturday").prop('disabled', false);
                        var from_saturday = '<?php echo $saturday_from; ?>';
                        var to_saturday = '<?php echo $saturday_to; ?>';
                        if(from_saturday == ''){
                            $("#from-saturday").val(time);
                        }else{
                            $("#from-saturday").val(from_saturday);
                        }
                        if(to_saturday == ''){
                            $("#to-saturday").val(time);
                        }else{
                            $("#to-saturday").val(to_saturday);
                        }
                    }
                });

                $('#sunday_off').click(function(event){
                    if ($('input#sunday_off').prop('checked')) {
                        $(".sunday_off").addClass("checked");
                        $(".sunday_all_day").removeClass("checked");
                        $("#from-sunday").prop('disabled', true);
                        $("#to-sunday").prop('disabled', true);
                        $("#from-sunday").val("");
                        $("#to-sunday").val("");
                    }else{
                        $(".sunday_off").removeClass("checked");
                        $("#from-sunday").prop('disabled', false);
                        $("#to-sunday").prop('disabled', false);
                        var from_sunday = '<?php echo $sunday_from; ?>';
                        var to_sunday = '<?php echo $sunday_to; ?>';
                        if(from_sunday == ''){
                            $("#from-sunday").val(time);
                        }else{
                            $("#from-sunday").val(from_sunday);
                        }
                        if(to_sunday == ''){
                            $("#to-sunday").val(time);
                        }else{
                            $("#to-sunday").val(to_sunday);
                        }
                    }
                });

                $('#sunday_all_day').click(function(event){
                    if ($('input#sunday_all_day').prop('checked')) {
                        $(".sunday_all_day").addClass("checked");
                         $(".sunday_off").removeClass("checked");
                        $("#from-sunday").prop('disabled', true);
                        $("#to-sunday").prop('disabled', true);
                        $("#from-sunday").val("");
                        $("#to-sunday").val("");
                    }else{
                        $(".sunday_all_day").removeClass("checked");
                        $("#from-sunday").prop('disabled', false);
                        $("#to-sunday").prop('disabled', false);
                        var from_sunday = '<?php echo $sunday_from; ?>';
                        var to_sunday = '<?php echo $sunday_to; ?>';
                        if(from_sunday == ''){
                            $("#from-sunday").val(time);
                        }else{
                            $("#from-sunday").val(from_sunday);
                        }
                        if(to_sunday == ''){
                            $("#to-sunday").val(time);
                        }else{
                            $("#to-sunday").val(to_sunday);
                        }
                    }
                });

            });


            </script>
            <?php
          if($post_response == "done" && $post_result_code == "true"){
                ?>
                <script>
            $(document).ready(function() {
                $('#tab13').trigger('click');
            });
            </script>
                <?php
            }
            ?>
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

										<span class="caption-subject font-dark bold uppercase">Hours of Operation</span>

                                    </div>
                                </div>
                                <div class="portlet-body">

                                    <form class="form-inline" method="post" action="" role="form">
                                        <h5>Monday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="monday_off"><input type="checkbox" id="monday_off" name="monday_off" value="off"></span></div> Closed </label>
                                               <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="monday_all_day"><input type="checkbox" id="monday_all_day" name="monday_all_day" value="yes"></span></div> All day open </label>
                                            </div>
                                            <div class="col-md-3">
												<div class="input-icon">
												<i class="fa fa-clock-o"></i>
                                                <input type="text" placeholder="From" id="from-monday" value="<?php echo $monday_from; ?>" name="from_monday" class="form-control timepicker timepicker-default"></div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $monday_to ?>" name="to_monday" id="to-monday" placeholder="To"></div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Tuesday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="tuesday_off"><input type="checkbox" id="tuesday_off" name="tuesday_off" value="off"></span></div> Closed </label>
                                                <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="tuesday_all_day"><input type="checkbox" id="tuesday_all_day" name="tuesday_all_day" value="yes"></span></div> All day open </label>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $tuesday_from ?>" name="from_tuesday" id="from-tuesday" placeholder="From"> </div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $tuesday_to ?>" name="to_tuesday" id="to-tuesday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Wednesday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="wednesday_off"><input type="checkbox" id="wednesday_off" name="wednesday_off" value="off"></span></div> Closed </label>
                                              <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="wednesday_all_day"><input type="checkbox" id="wednesday_all_day" name="wednesday_all_day" value="yes"></span></div> All day open </label>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $wednesday_from ?>" name="from_wednesday" id="from-wednesday" placeholder="From"></div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $wednesday_to ?>" name="to_wednesday" id="to-wednesday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Thursday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="thursday_off"><input type="checkbox" id="thursday_off" name="thursday_off" value="off"></span></div> Closed </label>
                                                <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="thursday_all_day"><input type="checkbox" id="thursday_all_day" name="thursday_all_day" value="yes"></span></div> All day open </label>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $thursday_from ?>" name="from_thursday" id="from-thursday" placeholder="From"> </div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $thursday_to ?>" name="to_thursday" id="to-thursday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Friday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="friday_off"><input type="checkbox" id="friday_off" name="friday_off" value="off"></span></div> Closed </label>
                                              <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="friday_all_day"><input type="checkbox" id="friday_all_day" name="friday_all_day" value="yes"></span></div> All day open </label>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $friday_from ?>" name="from_friday" id="from-friday" placeholder="From"> </div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $friday_to ?>" name="to_friday" id="to-friday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Saturday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="saturday_off"><input type="checkbox" id="saturday_off" name="saturday_off" value="off"></span></div> Closed </label>
                                                 <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="saturday_all_day"><input type="checkbox" id="saturday_all_day" name="saturday_all_day" value="yes"></span></div> All day open </label>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $saturday_from ?>" name="from_saturday" id="from-saturday" placeholder="From"> </div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $saturday_to ?>" name="to_saturday" id="to-saturday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <hr>
										<h5>Sunday</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-3" style="padding: 8px 0px 0px;">
                                                <label>
                                                    <div class="checker"><span class="sunday_off"><input type="checkbox" id="sunday_off" name="sunday_off" value="off"></span></div> Closed </label>
                                                 <label style="margin-left: 10px;">
                                                    <div class="checker"><span class="sunday_all_day"><input type="checkbox" id="sunday_all_day" name="sunday_all_day" value="yes"></span></div> All day open </label>

                                         </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $sunday_from ?>" name="from_sunday" id="from-sunday" placeholder="From"></div>

                                            </div>
                                            <div class="col-md-3">
											<div class="input-icon">
											<i class="fa fa-clock-o"></i>
                                                <input type="text" class="form-control timepicker timepicker-default" value="<?php echo $sunday_to ?>" name="to_sunday" id="to-sunday" placeholder="To"> </div>

                                            </div>
                                        </div>
                                        <h5 style="margin-top: 40px;">Message (when unavailable)</h5>
                                        <div class="row" style="padding: 0px 0px 0px 34px;">
                                            <div class="col-md-12" style="padding: 8px 0px 0px;">
                                                <textarea style="display: block; width: 60%; height: 130px; padding: 5px;" name="business_unavail_notice" id="business_unavail_notice"><?php echo $status_text; ?></textarea>
                                            </div>

                                        </div>

										<div class="clear" style="height: 10px;"></div>
                                        <div class="row" style="padding: 0px 0px 0px 20px; margin-top: 25px; display: <?php echo $add_company; ?>">
                                            <input type="hidden" name="key" value="Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4" />
										<input type="submit" style="color: rgb(255, 255, 255); background-color: rgb(50, 197, 210); border: 1px solid rgb(50, 197, 210); padding: 7px 20px 7px 20px; border-radius: 3px;" name="submit" value="Update">
                                        </div>

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
        <?php if(!empty($monday_off) && $monday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#monday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($monday_all_day) && $monday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#monday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($tuesday_off) && $tuesday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#tuesday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($tuesday_all_day) && $tuesday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#tuesday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($wednesday_off) && $wednesday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#wednesday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($wednesday_all_day) && $wednesday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#wednesday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($thursday_off) && $thursday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#thursday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($thursday_all_day) && $thursday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#thursday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($friday_off) && $friday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#friday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($friday_all_day) && $friday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#friday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($saturday_off) && $saturday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#saturday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($saturday_all_day) && $saturday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#saturday_all_day").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($sunday_off) && $sunday_off == 'off'){ ?>
<script>

            $(document).ready(function() {
                $("#sunday_off").trigger("click");
            });
</script>
<?php } ?>
<?php if(!empty($sunday_all_day) && $sunday_all_day == 'yes'){ ?>
<script>

            $(document).ready(function() {
                $("#sunday_all_day").trigger("click");
            });
</script>
<?php } ?>