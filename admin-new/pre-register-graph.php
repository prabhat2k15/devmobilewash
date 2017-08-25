<?php include('header.php') ?>
<?php
    if($client_module_permission == 'no'){
        ?><script type="text/javascript">window.location = "http://www.devmobilewash.com/admin-new/index.php"</script><?php
    }
?>
<style>	
	#chartdiv {
		width	: 100%;
		height	: 400px;
	}	
	.amcharts-chart-div a {display:none !important;}	
	.cal-overlay {
		background: rgba(0, 0, 0, 0.7) none repeat scroll 0 0;
		bottom: 0;
		content: "";
		left: 0;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 1;
	}
	.cal-overlay::after {
		color: #fff;
		content: "Loading...";
		font-size:16px;
		font-style:italic;
		left: 0;
		position: absolute;
		right: 0;
		text-align: center;
		top: 50%;
		transform: translateY(-50%);
		-moz-transform: translateY(-50%);
		-webkit-transform: translateY(-50%);
		-o-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
	}
	.msg {
		float: left;
		margin-left: 20px;
		min-width: 380px;
		padding-top: 10px;
	}

</style>
<!-- Resources -->
<script src="assets/global/plugins/amcharts/js/amcharts.js"></script>
<script src="assets/global/plugins/amcharts/js/serial.js"></script>
<script src="assets/global/plugins/amcharts/js/light.js"></script>

<?php include('right-sidebar.php') ?>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                   
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Pre-Register Client Graph</span>
                                    </div>
									<div class="msg"></div>
                                    <div class="actions">
                                         <i class="icon-calendar"></i>&nbsp;
                                         <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                                    </div>
                                </div>
                                <div class="portlet-body">
									<form id="pre-graph" action="" method="post" class="form-inline">
											<div class="form-group">
												<label class="control-label">Type</label>
												<select class="form-control input-medium" id="graph_select" name="graph_select">
													<option value="daily" selected="">Daily</option>
													<option value="monthly">Monthly</option>
													<option value="yearly">Yearly</option>
												</select>												
											</div>
											<div class="form-group">
												<label class="control-label">From Date<span style="color: red;">*</span></label>
												<input id="from_date" class="form-control form-control-inline input-medium date-picker" name="from_date" size="16" value="" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" required="" type="date">
											</div>
											<div class="form-group">
												<label class="control-label">To Date<span style="color: red;">*</span></label>
												<input id="to_date" class="form-control form-control-inline input-medium date-picker" name="to_date" size="16" placeholder="format: YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="" required="" type="date">
											</div>
											
											<div class="form-group">
												<input name="hidden" value="hidden" type="hidden">
												<button type="button" name="submit" class="btn blue">Submit</button>
											</div>
									</form>
                                    <!-- HTML -->
									<div id="chartdiv">
										<div class="cal-overlay" style="display: none;"></div>
									</div>	
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>                  
                    <div class="clearfix"></div>
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
           
<?php include('footer.php') ?>
	<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script>
	var data1 = new Array();
	$(function(){
		$('.cal-overlay').css('display','block');
		var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerDaywise';
		 
		$.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
		
			if(data){
				
				data1 = JSON.parse(JSON.stringify(data.data));
				_drawChart(data1);
				
			}
		});
		/*  Chart type */
		$('#pre-graph button').click(function(){
			var type = $('#graph_select option:selected').val();
			var from_date =  $('#from_date').val();
			var to_date =  $('#to_date').val();
			console.log(type+"::"+from_date+"::"+to_date)
			$('.msg').hide('normal').html('');
			
			if(from_date !='' && to_date !=''){
				$('#from_date').css('border-color','#c2cad8');
				$('#to_date').css('border-color','#c2cad8');
				
				var url = 'http://www.devmobilewash.com/api/index.php?r=customers/customerDaywise&type='+type+'&from_date='+from_date+'&to_date='+to_date;
				//console.log(url);
				//return false;
				$.getJSON(url, {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
				
					if(data){
						
						data1 = JSON.parse(JSON.stringify(data.data));
						_drawChart(data1);
						
					}
				});
				
			}else{
				$('.msg').css({
				'color':'red',
				'font-size':'14px',
				'text-align':'center'				
				}).html('Please fill required(*) fields.').show('normal');
				$('#from_date').css('border-color','red');
				$('#to_date').css('border-color','red');
			}
		});
		function _drawChart(data){
			var chart = AmCharts.makeChart("chartdiv", {
				"type": "serial",
				"theme": "light",
				"marginRight":20,
				"autoMarginOffset":20,
				"dataDateFormat": "YYYY-MM-DD HH:NN",
				
				"dataProvider": data,
				"valueAxes": [{
					"labelsEnabled":false,
					"axisAlpha": 0,
					"guides": [{
						"fillAlpha": 0.1,
						"fillColor": "#888888",
						"lineAlpha": 0,
						"toValue": 16,
						"value": 10
					}],
					"position": "left",
					"tickLength": 0
				}],
				"graphs": [{
					"balloonText": "[[category]]<br><b><span style='font-size:14px;'>Client(s):[[value]]</span></b>",
					"bullet": "round",
					"dashLength": 3,
					"colorField":"color",
					"valueField": "value"
				}]/* ,
				"trendLines": [{
					"finalDate": "2012-01-11 12",
					"finalValue": 19,
					"initialDate": "2012-01-02 12",
					"initialValue": 10,
					"lineColor": "#CC0000"
				}, {
					"finalDate": "2012-01-22 12",
					"finalValue": 10,
					"initialDate": "2012-01-17 12",
					"initialValue": 16,
					"lineColor": "#CC0000"
				}] */,
				"chartScrollbar": {
					"scrollbarHeight":2,
					"offset":-1,
					"backgroundAlpha":0.1,
					"backgroundColor":"#888888",
					"selectedBackgroundColor":"#67b7dc",
					"selectedBackgroundAlpha":1
				},
				"chartCursor": {
					"fullWidth":true,
					"valueLineEabled":true,
					"valueLineBalloonEnabled":false,
					"valueLineAlpha":0.5,
					"cursorAlpha":0
				},
				"categoryField": "date",
				"categoryAxis": {
					"parseDates": true,
					"axisAlpha": 0,
					"gridAlpha": 0.1,
					"minorGridAlpha": 0.1,
					"minorGridEnabled": true
				},
				"export": {
					"enabled": false
				}
			});
			
			chart.addListener("dataUpdated", zoomChart);
			function zoomChart(){
				//chart.zoomToDates(new Date(2016, 0, 2), new Date(2016, 0, 13));
			}
			
		}
	})
</script>      