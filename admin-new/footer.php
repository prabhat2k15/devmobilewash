</div>

        <div class="page-footer">
            <div class="page-footer-inner"> Copyright MobileWash <?php echo date('Y'); ?> - All Rights Reserved </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>


        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->

        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        <script>

$(function(){



/*$.getJSON("http://www.devmobilewash.com/api/index.php?r=customers/customermontwise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
    console.log(data);
    $.each(data, function(k, v){


    });
});*/
$.getJSON("http://www.devmobilewash.com/api/index.php?r=users/Appstat", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
  $(".clientonline").html(data.Online_Customers);
  $(".clientoffline").html(data.Offline_Customers);
  $(".pendingorder").html(data.Pending_Orders);
   $(".orderprogress").html(data.Processing_Orders);
   $(".agentonline").html(data.Online_Agent);
    $(".busyagents").html(data.busy_Agents);
    $(".offlineagents").html(data.Offline_Agent);
	$(".total-order").html(data.Completed_Orders);
    $(".today-order").html(data.Completed_Orders_today);
    $(".cancel_orders").html(data.Cancel_Orders);
    $(".cancel_orders_client").html(data.Cancel_Orders_Client);
    $(".cancel_orders_agent").html(data.Cancel_Orders_Agent);
    $(".total_agetns").html(data.total_agetns);
    $(".total_customers").html(data.total_customers);
    $(".bad_rating_agents").html(data.bad_rating_agents);
    $(".bad_rating_customers").html(data.bad_rating_customers);
    $(".insurance_license_expiration_count").html(data.insurance_license_expiration_count);
    $(".idle_wash").html(data.idle_wash);
    $(".late_drivers").html(data.late_drivers);
    $(".idle_wash_client").html(data.idle_wash_client);
    $(".total_order").html(data.totalorder_real);
 $(".total_completed_order").html(data.Completed_Orders);
$(".today_order").html(data.todayorder);
    $(".today_completed_order").html(data.Completed_Orders_today);
   // $(".today_revenue").html(data.profit);
   // $(".total_revenue").html(data.total_profit);
    $(".pre_register_client").html(data.pre_registered_clients);
	console.log(data.pre_registered_clients);

});


//onchnge New Customer map change scriptt
$('.deadline_customer').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_activities').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_activities_loading').hide();
                $('#site_activities_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                     $('#new_customer_section').text('Yearly New Customer');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customeryearwise';
                 }else if(selectedvalue == 'week'){
                     $('#new_customer_section').text('Weekly New Customer');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerweekwise';

                 }else if(selectedvalue == 'month'){
                     $('#new_customer_section').text('Monthly New Customer');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwise';
                 }else if(selectedvalue == 'monthdays'){
					$('#new_customer_section').text('Daily Customer');
					var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerDaywise';
                 }else{
                     $('#new_customer_section').text('Monthly New Customer');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_activities"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_activities").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' New Customer');
                        }
                    }
                });

                $('#site_activities').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end script for map order

//onchnge revenue map change scriptt
$('.revenue_section').on('change', function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_new').size() != 0)
    {
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                     $('#revenue').text('Client Revenue Yearly');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/TotalRevenueYearWise';
                 }else if(selectedvalue == 'week'){
                     $('#revenue').text('Client Revenue Weekly');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/TotalRevenueWeekWise';

                 }else if(selectedvalue == 'month'){
                     $('#revenue').text('Client Revenue Monthly');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/TotalRevenueMonthWise';
                 }else{
                     $('#revenue').text('Client Revenue Monthly');
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/TotalRevenueMonthWise';
                 }
                //var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwiseorder';
                $('#site_statistics_new_loading').hide();
                $('#site_statistics_new_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
                    if(selectedvalue == 'year'){
                         var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });

                 }else if(selectedvalue == 'week'){
                 var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });
                 }else{
                var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });
                  }


                var plot_statistics = $.plot($("#site_statistics_new"), [{
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
                $("#site_statistics_new").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], '$'+item.datapoint[1]);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            }
});
//end script for map revenue


//onchnge New Agents map change scriptt
$('.agents_new').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_4').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_4_loading').hide();
                $('#site_statistics_4_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                     $('#new_washers').text('Yearly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/washeryearwise';
                 }else if(selectedvalue == 'week'){
                     $('#new_washers').text('Weekly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/washerweekwise';

                 }else if(selectedvalue == 'month'){
                     $('#new_washers').text('Monthly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/washermonthwise';
                 }else{
                     $('#new_washers').text('Monthly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/washermonthwise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_statistics_4"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_4").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' New Wasers');
                        }
                    }
                });

                $('#site_statistics_4').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end script for new agents



//onchnge Comp Revenue change scriptt
$('.comp_revenue').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_activities_2').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_activities_2_loading').hide();
                $('#site_activities_2_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                     $('#new_washers').text('Yearly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/CompRevenueYearWise';
                 }else if(selectedvalue == 'week'){
                     $('#new_washers').text('Weekly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/CompRevenueWeekWise';

                 }else if(selectedvalue == 'month'){
                     $('#new_washers').text('Monthly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/CompRevenueMonthWise';
                 }else{
                     $('#new_washers').text('Monthly New Washers');
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/CompRevenueMonthWise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_activities_2"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_activities_2").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], '$'+item.datapoint[1]);
                        }
                    }
                });

                $('#site_activities_2').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end



//onchnge visitors change scriptt
$('.visitors').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_5').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_5_loading').hide();
                $('#site_statistics_5_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=site/visitorsyearwise';
                 }else if(selectedvalue == 'week'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=site/visitorsweekwise';

                 }else if(selectedvalue == 'month'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=site/visitorsmonthwise';
                 }else{
                var url = 'http://www.devmobilewash.com/api/index.php?r=site/visitorsmonthwise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_statistics_5"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_5").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' visitors');
                        }
                    }
                });

                $('#site_statistics_5').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end



//onchnge pre registered washers
$('.prewashers').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_6').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_6_loading').hide();
                $('#site_statistics_6_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewasheryearwise';
                 }else if(selectedvalue == 'week'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewasherweekwise';

                 }else if(selectedvalue == 'month'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewashermonthwise';
                 }else{
                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewashermonthwise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_statistics_6"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_6").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' washers');
                        }
                    }
                });

                $('#site_statistics_6').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end


//onchnge pre registered washers
$('.preclients').on('change', function(){

function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_7').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_7_loading').hide();
                $('#site_statistics_7_content').show();
                var selectedvalue = this.value;

                 if(selectedvalue == 'year'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsyearwise';
                 }else if(selectedvalue == 'week'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsweekwise';

                 }else if(selectedvalue == 'month'){
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsmonthwise';
                 }else{
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsmonthwise';
                 }
               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                if(selectedvalue == 'year'){
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else if(selectedvalue == 'week'){
                 var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                 }else{
                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                  }
                var plot_statistics = $.plot($("#site_statistics_7"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_7").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' clients');
                        }
                    }
                });

                $('#site_statistics_7').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
//end

});
// default map order load
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#weekly_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerweekwiseorder';

                $('#weekly_order_loading').hide();
                $('#weekly_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#weekly_order_chart"), [{
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
                $("#weekly_order_chart").bind("plothover", function(event, pos, item) {
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

$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#monthly_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwiseorder';

                $('#monthly_order_loading').hide();
                $('#monthly_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#monthly_order_chart"), [{
                        data: visitors_new,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#2e69e9']
                    }, {
                        data: visitors_new,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#2e69e9",
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
                $("#monthly_order_chart").bind("plothover", function(event, pos, item) {
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

$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#yearly_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customeryearwiseorder';

                $('#yearly_order_loading').hide();
                $('#yearly_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#yearly_order_chart"), [{
                        data: visitors_new,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#8db930']
                    }, {
                        data: visitors_new,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#8db930",
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
                $("#yearly_order_chart").bind("plothover", function(event, pos, item) {
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


// default map order load
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#weekly_comp_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerweekwiseorder';

                $('#weekly_comp_order_loading').hide();
                $('#weekly_comp_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4', status: 'completed'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#weekly_comp_order_chart"), [{
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
                $("#weekly_comp_order_chart").bind("plothover", function(event, pos, item) {
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

$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#monthly_comp_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwiseorder';

                $('#monthly_comp_order_loading').hide();
                $('#monthly_comp_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4', status: 'completed'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#monthly_comp_order_chart"), [{
                        data: visitors_new,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#2e69e9']
                    }, {
                        data: visitors_new,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#2e69e9",
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
                $("#monthly_comp_order_chart").bind("plothover", function(event, pos, item) {
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

$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#yearly_comp_order_chart').size() != 0)
            {


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customeryearwiseorder';

                $('#yearly_comp_order_loading').hide();
                $('#yearly_comp_order_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4', status: 'completed'}, function( data ) {




                  var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });


                var plot_statistics = $.plot($("#yearly_comp_order_chart"), [{
                        data: visitors_new,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#8db930']
                    }, {
                        data: visitors_new,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#8db930",
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
                $("#yearly_comp_order_chart").bind("plothover", function(event, pos, item) {
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
// end script
// default map customer new load
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#site_activities').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_activities_loading').hide();
                $('#site_activities_content').show();

               $.getJSON("http://www.devmobilewash.com/api/index.php?r=customers/customermontwise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                var plot_statistics = $.plot($("#site_activities"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_activities").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' New Customer');
                        }
                    }
                });

                $('#site_activities').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
// end script

// Default map for client Revenue
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
    if ($('#site_statistics_new').size() != 0)
    {
                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/TotalRevenueMonthWise';

                //var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customermontwiseorder';
                $('#site_statistics_new_loading').hide();
                $('#site_statistics_new_content').show();
                $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {


                var visitors_new = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    visitors_new.push(d);
                });



                var plot_statistics = $.plot($("#site_statistics_new"), [{
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
                $("#site_statistics_new").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], '$'+item.datapoint[1]);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            }
})
// end

// default load new agents
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#site_statistics_4').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_4_loading').hide();
                $('#site_statistics_4_content').show();

               $.getJSON("http://www.devmobilewash.com/api/index.php?r=agents/washermonthwise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                var plot_statistics = $.plot($("#site_statistics_4"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_4").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' New Washers');
                        }
                    }
                });

                $('#site_statistics_4').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
// end script

// default load Comp Revenue
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#site_activities_2').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_activities_2_loading').hide();
                $('#site_activities_2_content').show();

               $.getJSON("http://www.devmobilewash.com/api/index.php?r=agents/CompRevenueMonthWise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                var plot_statistics = $.plot($("#site_activities_2"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_activities_2").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], '$'+item.datapoint[1]);
                        }
                    }
                });

                $('#site_activities_2').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
// end script


// default load Visitors
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#site_statistics_5').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_5_loading').hide();
                $('#site_statistics_5_content').show();

               $.getJSON("http://www.devmobilewash.com/api/index.php?r=site/visitorsmonthwise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                var plot_statistics = $.plot($("#site_statistics_5"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_5").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' visitors');
                        }
                    }
                });

                $('#site_statistics_5').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
// end script



// default load washers
$(document).ready(function(){
    function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
   if ($('#site_statistics_6').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_6_loading').hide();
                $('#site_statistics_6_content').show();

               $.getJSON("http://www.devmobilewash.com/api/index.php?r=agents/prewashermonthwise", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {

                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });
                var plot_statistics = $.plot($("#site_statistics_6"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_6").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' washers');
                        }
                    }
                });

                $('#site_statistics_6').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
});
// end script





// default load clients
$(document).ready(function(){
	function showChartTooltip(x, y, xValue, yValue) {
		$('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
			position: 'absolute',
			display: 'none',
			top: y - 40,
			left: x - 40,
			border: '0px solid #ccc',
			padding: '2px 6px',
			'background-color': '#fff'
		}).appendTo("body").fadeIn(200);
	}
	if ($('#site_statistics_7').size() != 0) {
		//site activities
		var previousPoint2 = null;
		$('#site_statistics_7_loading').hide();
		$('#site_statistics_7_content').show();
		var selectedvalue = this.value;


		var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsmonthwise';

	   $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {


		var data1 = new Array();
		$.each(data, function(k, v){
			var d = [k , v ];
			data1.push(d);
		});

		var plot_statistics = $.plot($("#site_statistics_7"),

			[{
				data: data1,
				lines: {
					fill: 0.2,
					lineWidth: 0,
				},
				color: ['#BAD9F5']
			}, {
				data: data1,
				points: {
					show: true,
					fill: true,
					radius: 4,
					fillColor: "#9ACAE6",
					lineWidth: 2
				},
				color: '#9ACAE6',
				shadowSize: 1
			}, {
				data: data1,
				lines: {
					show: true,
					fill: false,
					lineWidth: 3
				},
				color: '#9ACAE6',
				shadowSize: 0
			}],

			{

				xaxis: {
					tickLength: 0,
					tickDecimals: 0,
					mode: "categories",
					min: 0,
					font: {
						lineHeight: 18,
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
		$("#site_statistics_7").bind("plothover", function(event, pos, item) {
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));
			if (item) {
				if (previousPoint2 != item.dataIndex) {
					previousPoint2 = item.dataIndex;
					$("#tooltip").remove();
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);
					showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' clients');
				}
			}
		});

		$('#site_statistics_7').bind("mouseleave", function() {
			$("#tooltip").remove();
		});
	}

});
// end script




// default load clients
$(document).ready(function(){
   function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_8').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_8_loading').hide();
                $('#site_statistics_8_content').show();
                var selectedvalue = this.value;


                var url = 'http://www.devmobilewash.com/api/index.php?r=customers/preclientsperday';

               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {


                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });

                var plot_statistics = $.plot($("#site_statistics_8"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_8").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' clients');
                        }
                    }
                });

                $('#site_statistics_8').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }

});
// end script



// default load clients
$(document).ready(function(){
   function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }
if ($('#site_statistics_9').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_statistics_9_loading').hide();
                $('#site_statistics_9_content').show();
                var selectedvalue = this.value;


                var url = 'http://www.devmobilewash.com/api/index.php?r=agents/prewasherperday';

               $.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {


                var data1 = new Array();
                $.each(data, function(k, v){
                    var d = [k , v ];
                    data1.push(d);
                });

                var plot_statistics = $.plot($("#site_statistics_9"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                $("#site_statistics_9").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' washers');
                        }
                    }
                });

                $('#site_statistics_9').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }

});
// end script

</script>
    </body>
<style>
#site_activities_content > div {
    height: 300px !important;
}
#site_activities_2_content > div{
    height: 300px !important;
}
</style>
</html>