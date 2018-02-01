 <?php
 require_once('../api/protected/config/constant.php');
 ?>
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
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
                                
                                   
                                    <div class="col-md-4 col-sm-4">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-bar-chart font-green"></i>
                                        <span class="caption-subject font-green bold uppercase">Monthly Order</span>
                                    </div>
                                    <div class="actions">
                                         <form method="get" action="">
                                            <select name="typeorder" id="dataorder" onchange="mydatachart()">
                                            <option value="month" <?php if($_GET['typeorder'] == 'month') { echo 'selected="selected"'; } ?>>Month</option>
                                            <option value="year" <?php if($_GET['typeorder'] == 'year') { echo 'selected="selected"'; } ?>>Year</option>
                                            <option value="week" <?php if($_GET['typeorder'] == 'week') { echo 'selected="selected"'; } ?>>Week</option>
                                            </select>
                                            </form>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="site_statistics_loading">
                                        <img src="assets/global/img/loading.gif" alt="loading" /> </div>
                                 <div id="site_statistics_content" class="display-none">
                                        <div id="site_statistics" class="chart"> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
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
                                
                        
                                <script>

$(function(){
if ($('#site_statistics').size() != 0) 
            {
                <?php if($_GET['typeorder'] == 'year'){ ?>
                var url = '<?php echo ROOT_URL; ?>/api/index.php?r=customers/customeryearwiseorder';
                <?php }elseif($_GET['typeorder'] == 'week'){ ?>
                var url = '<?php echo ROOT_URL; ?>/api/index.php?r=customers/customerweekwiseorder';
                
                <?php }elseif($_GET['typeorder'] == 'month'){ ?>
                var url = '<?php echo ROOT_URL; ?>/api/index.php?r=customers/customermontwiseorder';
                <?php }else{ ?>
                var url = '<?php echo ROOT_URL; ?>/api/index.php?r=customers/customermontwiseorder';
                <?php  } ?>
                //var url = '<?php echo ROOT_URL; ?>/api/index.php?r=customers/customermontwiseorder';
                $('#site_statistics_loading').hide();
                $('#site_statistics_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
                    <?php if($typeorder == 'year'){ ?>
                var visitors_new = [
                    ['2016', data.year_0],
                    ['2015', data.year_1],
                    ['2014', data.year_2],
                    ['2013', data.year_3],
                    ['2012', data.year_4],
                    ['2011', data.year_5],
                    ['2010', data.year_6]
                ];
                <?php }elseif($typeorder == 'week'){ ?>
                 var visitors_new = [
                    ['Fri', data.Fri],
                    ['Thu', data.Thu],
                    ['Wed', data.Wed],
                    ['Tue', data.Tue],
                    ['Mon', data.Mon],
                    ['Sun', data.Sun],
                    ['Sat', data.Sat]
                ];
                <?php }else{ ?>
                var visitors_new = [
                    ['Mar', data.Mar],
                    ['Feb', data.Feb],
                    ['Jan', data.Jan],
                    ['Dec', data.Dec],
                    ['APR', data.Nov],
                    ['Oct', data.Oct],
                    ['Sep', data.Sep]
                ];
                <?php  } ?>
               
                
                var plot_statistics = $.plot($("#site_statistics"), [{
                        data: visitors_new,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#f89f9f']
                    }, {
                        data: visitors_new,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#f89f9f",
                            lineWidth: 3
                        },
                        color: '#fff',
                        shadowSize: 0
                    }],

                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });
                    
            });

                var previousPoint = null;
                $("#site_statistics").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' Order');
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            }

});



</script>