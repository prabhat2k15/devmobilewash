<?php include('header.php') ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
<?php include('right-sidebar.php') ?>

<style>
.dashboard-stat.blue {
  background-color: #fff !important;
}
.dashboard-stat.red {
  background-color: #fff !important;
}
.dashboard-stat.green {
  background-color: #fff !important;
}
.dashboard-stat.purple {
  background-color: #fff !important;
}
.dashboard-stat .visual > i {
    font-size: 110px;
    line-height: 110px;
    margin-left: -7px !important;
    margin-top: -24px;
}
.visual i {
    opacity: 1 !important;
}
.table-bordered, .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
  border: 0px solid #fff;
}
.table-scrollable > .table {
  background-color: #000;
  border-color: #ff0059;
  border-style: solid;
  border-width: 5px;
  height: 140px;
}
.table.table-striped.table-bordered.table-hover.table-checkable.order-column th {
  vertical-align: inherit;
}

.page-content-wrapper .page-content {
  padding: 0 20px 10px !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
$('#totalagetns').click(function(){
    if($('.total_agetns').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php';
});
$('#offlineagents').click(function(){
    if($('.offlineagents').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=offlineagents';
});
$('#insurance_license_expiration_count').click(function(){
    if($('.insurance_license_expiration_count').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=insurance_license_expiration_count';
});
$('#cancel_orders_agent').click(function(){
    if($('.cancel_orders_agent').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=cancel_orders_agent';
});
$('#idle_wash').click(function(){
    if($('.idle_wash').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=idle_wash';
});
$('#bad_rating_agents').click(function(){
    if($('.bad_rating_agents').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=bad_rating_agents';
});
$('#late_drivers').click(function(){
    if($('.late_drivers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-agents.php?type=late_drivers';
});
$('#total_customers').click(function(){
    if($('.total_customers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php';
});
$('#clientoffline').click(function(){
    if($('.clientoffline').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php?type=clientoffline';
});
$('#cancel_orders_client').click(function(){
    if($('.cancel_orders_client').html() == 0){
        alert('There is no data found!');
        return false;
    }
    
   window.location.href='manage-customers.php?type=cancel_orders_client';
});
$('#idle_wash_client').click(function(){
    if($('.idle_wash_client').html() == 0){
        alert('There is no data found!');
        return false;
    }
    
   window.location.href='manage-customers.php?type=idle_wash_client';
});
$('#bad_rating_customers').click(function(){
    if($('.bad_rating_customers').html() == 0){
        alert('There is no data found!');
        return false;
    }
   window.location.href='manage-customers.php?type=bad_rating_customers';
});
});
</script>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    
                    
                    
                    <div class="row" style="background-color: #000; color: #fff; margin-left: -20px ! important; margin-right: -20px;">
                        <div class="col-md-1 col-sm-1" id="totalagetns" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #5407E2; height: 90px; background-color: #5407e2;">
                            <div style="font-size: 20px;" class="total_agetns">0</div>
                            <div>Total Washers</div>
                        </div>
                        <div class="col-md-1 col-sm-1" id="offlineagents" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #0771e2;">
                            <div style="font-size: 20px;" class="offlineagents">0</div>
                            <div>Offline Washers</div>
                        </div>
                        <div class="col-md-2 col-sm-2" id="insurance_license_expiration_count" style="cursor: pointer; padding: 13px 0px 0px 10px; border-top: 5px solid #e28307;">
                            <div style="font-size: 20px;" class="insurance_license_expiration_count">0</div>
                            <div>Insurance Expiring</div>
                        </div>
                        <div class="col-md-2 col-sm-2" id="cancel_orders_agent" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e20724;">
                            <div style="font-size: 20px;" class="cancel_orders_agent">0</div>
                            <div>Washers Cancels</div>
                        </div>
                        <div class="col-md-1 col-sm-1" id="idle_wash" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #07c1e2;">
                            <div style="font-size: 20px;" class="idle_wash">0</div>
                            <div>Idle Washers</div>
                        </div>
                        <div class="col-md-2 col-sm-2" id="bad_rating_agents" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #e900e7;">
                            <div style="font-size: 20px;" class="bad_rating_agents">0</div>
                            <div>Flagged Bad Washers</div>
                        </div>
                        <div class="col-md-1 col-sm-1" id="late_drivers" style="padding: 13px 0px 0px 10px; cursor: pointer; border-top: 5px solid #25e900;">
                            <div style="font-size: 20px;" class="late_drivers">0</div>
                            <div>Late Drivers</div>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-bar-chart font-green"></i>
                                        <span class="caption-subject font-green bold uppercase" style="font-size: 13px;" id="new_washers_null">Washers</span>
                                    </div>
                                    <div class="actions">
                                        <form method="get" action="">
                                            <select name="typecustomer" class="form-control input-sm input-xsmall input-inline agents_new">
                                            <option value="month" <?php if($_GET['typeorder'] == 'month') { echo 'selected="selected"'; } ?>>Month</option>
                                            <option value="year" <?php if($_GET['typeorder'] == 'year') { echo 'selected="selected"'; } ?>>Year</option>
                                            <option value="week" <?php if($_GET['typeorder'] == 'week') { echo 'selected="selected"'; } ?>>Week</option>
                                            </select>
                                         </form>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="site_statistics_4_loading">
                                        <img src="assets/global/img/loading.gif" alt="loading" /> </div>
                                    <div id="site_statistics_4_content" class="display-none">
                                        <div id="site_statistics_4" class="chart"> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-bar-chart font-green"></i>
                                        <span class="caption-subject font-green bold uppercase" style="font-size: 13px;">Pre Registered Washers</span>
                                    </div>
                                    <div class="actions">
                                        <form method="get" action="">
                                            <select name="typecustomer" class="form-control input-sm input-xsmall input-inline prewashers">
                                            <option value="month" <?php if($_GET['typeorder'] == 'month') { echo 'selected="selected"'; } ?>>Month</option>
                                            <option value="year" <?php if($_GET['typeorder'] == 'year') { echo 'selected="selected"'; } ?>>Year</option>
                                            <option value="week" <?php if($_GET['typeorder'] == 'week') { echo 'selected="selected"'; } ?>>Week</option>
                                            </select>
                                    </form>                                    
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="site_statistics_6_loading">
                                        <img src="assets/global/img/loading.gif" alt="loading" /> </div>
                                    <div id="site_statistics_6_content" class="display-none">
                                        <div id="site_statistics_6" class="chart"> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light bordered pre_wshers">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-bar-chart font-green"></i>
                                        <span class="caption-subject font-green bold uppercase" style="font-size: 13px;">Washers</span>
                                    </div>
                                    
                                </div>
                                <div class="portlet-body">
                                    <div id="site_statistics_9_loading">
                                        <img src="assets/global/img/loading.gif" alt="loading" /> </div>
                                    <div id="site_statistics_9_content" class="display-none">
                                        <div id="site_statistics_9" class="chart"> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>                   
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php include('footer.php') ?>
            <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->