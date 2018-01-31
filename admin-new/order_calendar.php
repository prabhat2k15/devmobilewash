<?php
include('header.php');
    if($_GET['action'] == 'trash'){
        $clientsid = $_GET['id'];
        $url = ROOT_URL.'/api/index.php?r=customers/trashpreclients&id='.$clientsid;
        $handle = curl_init($url);
        $data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($handle);
        curl_close($handle);
        $jsondata = json_decode($result);
        $response = $jsondata->response;
        $result_code = $jsondata->result;
        if($response == "clients trashed" && $result_code == "true"){
            ?>
            <script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/manage-pre-clients.php?trash=true"</script>
            <?php
            die();
            }
    }

 ?>
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

    if($company_module_permission == 'no' || $checked_opening_hours == ''){
        ?><script type="text/javascript">window.location = "<?php echo ROOT_URL; ?>/admin-new/index.php"</script><?php
    }
?>

<?php if($jsondata_permission->users_type == 'admin' || $jsondata_permission->users_type == 'superadmin'): ?>
<?php include('right-sidebar.php') ?>
<?php else: ?>
<?php include('navigation-employee.php') ?>
<style>
#calendar .fc-right{
display: none;
}

.ordertypetext{
text-transform: capitalize;
}
</style>
<?php endif; ?>
<?php
	$url = ROOT_URL.'/api/index.php?r=customers/getallpreclients';
	$handle = curl_init($url);
	$data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($handle);
	curl_close($handle);
	$preclients = json_decode($result);


	$url_trash = ROOT_URL.'/api/index.php?r=customers/getpreclientstrashdata';
	$handle_trash = curl_init($url_trash);
	$data_trash = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
	curl_setopt($handle_trash, CURLOPT_POST, true);
	curl_setopt($handle_trash, CURLOPT_POSTFIELDS, $data_trash);
	curl_setopt($handle_trash,CURLOPT_RETURNTRANSFER,1);
	$result_trash = curl_exec($handle_trash);
	curl_close($handle_trash);
	$preclients_trash = json_decode($result_trash);
	$count = $preclients_trash->count;


?>
<link href='assets/global/css/full-cal/fullcalendar.css' rel='stylesheet' />
<link href='assets/global/css/full-cal/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='assets/global/scripts/full-cal/moment.min.js'></script>
<script src='assets/global/scripts/full-cal/fullcalendar.min.js'></script>
<script>
var event_Day;
var timetracker = 0;
$(document).ready(function() {
	var color_back= title = "";
	var __bool = 0;
	__checkBool(__bool);
	$('.ord_center a').click(function(){
		order_for  = $(this).attr('id');
		if($('.ord_center a').hasClass('active-ord')){
			$('.ord_center a').removeClass('active-ord');
			$(this).addClass('active-ord');
		}else{
			$(this).addClass('active-ord');
		}
		__checkBool(order_for);
	});

	function __checkBool(__bool){
		show_calendar(__bool);
	}

	function show_calendar(type){
		$('#calendar').fullCalendar( 'destroy' );
		/* $url = '/api/index.php?r=washing/order_schedule_app'; */
		$url = '/api/index.php?r=washing/order_schedule_app';

		/* if(type == 'app_orders'){
			$url = '/api/index.php?r=washing/order_schedule_app';
		} */
		if(type == 'phone_orders'){
			$url = "/api/index.php?r=washing/order_schedule";
$(".ordertypetext").html("(Phone Orders)");
		}if(type == 'schedule_orders'){
			$url = '/api/index.php?r=washing/order_schedule_ordSchedule';
$(".ordertypetext").html("(Schedule Orders)");
		}

		$('#calendar').fullCalendar({
			defaultDate: moment().toDate(),
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			aspectRatio: 1,
			events: function(start, end, timezone, callback) {
				//$('#calendar').fullCalendar('removeEvents');

				month = end.month();
				year = end.year();
 if(month == 0){
                     month = 12;
                     year--;
                 }
				start = startMonthYear(month,year)
				end = endMonthYear(month,year)
//console.log(start);
//console.log(end);
				//$('.cal-overlay').css('display','block');
				$('.fc-left .center').remove();
				$.ajax({
					url: $url,
					dataType: 'json',
					data: {
						// our hypothetical feed requires UNIX timestamps
						start: start,
						end: end,
key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'
					},
					success: function(doc) {
					    //console.log(doc);
						var events = [];
						if(doc.order.empty == 'yes'){
							$('.cal-overlay').css('display','none');
						}
						else{
							var color,title,start,start1,color1,title1,start2,color2,title2,titledec,colordec;
							$.each( doc.order, function(index, value ) {
								var complete = Object.keys(value.complete).length;
								var pending = Object.keys(value.pending).length;
								var processing = Object.keys(value.processing).length;
								var canceled = Object.keys(value.canceled).length;
								var declined = Object.keys(value.declined).length;
								var _viewAll = ['1'];
								var view_all = Object.keys(_viewAll).length
								var home_ord = Object.keys(value.home).length;
								var work_ord = Object.keys(value.work).length;

								if(complete > 1 ){
									title = value.complete.count+' Completed';
									color = value.complete.color;
									events.push({
										title:title,
										start:index,
										color  : color
									});
								}
								if(pending > 1 ){

									title1 = value.pending.count+' Pending';
									color1 = value.pending.color;
									events.push({
										title:title1,
										start:index,
										color  : color1
									});
								}
								if(processing > 1  ){
									title2 = value.processing.count+' Processing';
									color2 = value.processing.color;
										events.push({
										title:title2,
										start:index,
										color  : color2
									});
								}
								if(canceled > 1  ){
									title2 = value.canceled.count+' Canceled';
									color2 = value.canceled.color;
										events.push({
										title:title2,
										start:index,
										color  : color2
									});
								}
								if(declined > 1  ){
									titledec = value.declined.count+' Declined';
									colordec = value.declined.color;
										events.push({
										title:titledec,
										start:index,
										color  : colordec
									});
								}
								if(view_all >= 1){
									title3 = 'View All';
									color3 = '#035954';
									events.push({
										title:title3,
										start:index,
										color: color3
									});
								}
								if(home_ord >= 1){
									home = value.home.count;
									$('.hidd-home-work').append('<input class="home" data-date="'+index+'" type="hidden" value="'+value.home.count+'">')
								}
								if(work_ord >= 1){
									work = value.work.count;
									$('.hidd-home-work').append('<input class="work" data-date="'+index+'" type="hidden" value="'+value.work.count+'">')
								}
							});
							$('.cal-overlay').css('display','none');

						}
						callback(events);
					},
					color: color_back
				});
			},
			eventRender: function(event, element) {
				$(".fc-next-button").addClass('fc-state-disabled');
				$(".fc-prev-button").addClass('fc-state-disabled');


				event_Day = event.start.date();
				var event_current_day = moment().format('D');
				var event_previous_day = moment().add(-1, 'days').format('D');
				var event_next_day = moment().add(+1, 'days').format('D');
				if( (event_Day == event_current_day) || (event_Day == event_previous_day) || (event_Day ==event_next_day)) return true;
				else return false;
				e.preventDefault();
			},
			eventClick: function(calEvent, jsEvent, view) {
				var _day = calEvent.start._i;

				var _event = '';
				if(calEvent.color == '#FF3B30'){
					_event = 'pending';
				}else if(calEvent.color == '#30A0FF'){
					_event = 'completed';
				}else if(calEvent.color == '#EF9047'){
					_event = 'processing';
				}else if(calEvent.color == '#AAAAAA'){
					_event = 'canceled';
				}
				else if(calEvent.color == '#cc0066'){
					_event = 'declined';
				}else{
					_event = 'all'
				}


				if(type == 'phone_orders'){
					window.location = "/admin-new/phone-orders.php?day="+_day+"&event="+_event;
				}else if(type == 'schedule_orders'){
					window.location = "/admin-new/schedule-orders.php?sday="+_day+"&event="+_event;
				}else if(type == 'all'){
					window.location = "/admin-new/schedule-orders.php?ajax=true&day="+_day+"&event="+_event;
				}else{
				    window.open("/admin-new/all-orders.php?ajax=true&day="+_day+"&event="+_event);
					//window.location  = "/admin-new/all-orders.php?day="+_day+"&event="+_event;
				}

				//window.location = _url;
			},
			eventAfterAllRender:  function(view){
				//var _html = '<div class="center" style="width: 45%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span>Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #aaa; background: #aaa;"><span>Canceled</span></div>	</div>';

				//$('.fc-left').append(_html);
				var event_current_date = moment().format('YYYY-MM-DD');
				var event_previous_date = moment().add(-1, 'days').format('YYYY-MM-DD');
				var event_next_date = moment().add(+1, 'days').format('YYYY-MM-DD');
//console.log(event_current_date);

				$('.hidd-home-work input.home').each(function(){
					home_count = $(this).val();
					home_day = $(this).data('date');
					if(home_count.length < 1) home_count = 0;
//console.log(home_count.length);
if( (event_current_date == home_day) || (event_previous_date == home_day) || (event_next_date ==home_day)){
					$('.fc-day-grid .fc-bg tbody tr td[data-date="'+home_day+'"]').append('<div class="home_work"><div class="left">Home: 0</div></div>');
					$('td[data-date="'+home_day+'"] .home_work .left').text('Home: '+home_count);
}

				});
				$('.hidd-home-work input.work').each(function(){
					work_count = $(this).val();
					work_day = $(this).data('date');
					if(work_count.length < 1) work_count = 0;
//console.log(work_count.length);
if( (event_current_date == work_day) || (event_previous_date == work_day) || (event_next_date ==work_day)){
					$('.fc-day-grid .fc-bg tbody tr td[data-date="'+work_day+'"] .home_work').append('<div class="right">Work: 0</div>');
					$('td[data-date="'+work_day+'"] .home_work .right').text('Work: '+work_count);
}
				});

				if($('.hidd-home-work input').length > 0){
					$('.hidd-home-work').find('input').remove();
				}
                
			}
		});
		
	var _html = '<div class="center" style="width: 50%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span>Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #aaa; background: #aaa;"><span>Canceled</span></div><div id="declined" style="border: 1px solid #cc0066; background: #cc0066;"><span>Declined</span></div>	</div>';

				$('.fc-left').append(_html);
	}

});
function startMonthYear(month,year){
	last_year = '';
	last_month = parseInt(month);
	current_year = year;
	current_month = parseInt(month);
	if(current_month == 1){
		last_month =  12;
		last_year =  (current_year)-1;
	}else{
		last_year = current_year;
	}
	if(last_month.toString().length == 1) last_month = '0'+last_month;
	var startMonthYear = last_year+'-'+last_month;

	return startMonthYear;
}
function endMonthYear(month,year){
	current_year = year;
	current_month = parseInt(month);
	if(current_month.toString().length == 1) current_month = '0'+current_month;
	var endMonthYear = current_year+'-'+current_month;

	return endMonthYear;
}

function refreshCal(){
    $('#calendar').fullCalendar('refetchEvents');
     var _html = '<div class="center" style="width: 50%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span>Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #aaa; background: #aaa;"><span>Canceled</span></div><div id="declined" style="border: 1px solid #cc0066; background: #cc0066;"><span>Declined</span></div>	</div>';

				$('.fc-left').append(_html); 
				//console.log('working');
				timetracker+= 10;
				if(timetracker >= 120) window.location.href="<?php echo ROOT_URL; ?>/admin-new/order_calendar.php";
				 setTimeout( refreshCal, 10000 );
}

setTimeout( refreshCal, 10000 );

/*setInterval(function(){
    //$('#calendar').fullCalendar('removeEvents');
              //$('#calendar').fullCalendar('addEventSource', events);         
              //$('#calendar').fullCalendar('rerenderEvents' );
    $('#calendar').fullCalendar('refetchEvents');
    
  	var _html = '<div class="center" style="width: 45%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span>Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #aaa; background: #aaa;"><span>Canceled</span></div>	</div>';

				$('.fc-left').append(_html); 
    
}, 10000);*/
</script>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">

		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption font-dark">
							<i class="icon-settings font-dark"></i>
							<span class="caption-subject bold uppercase">
								Order Calendar
							</span>
						</div>
						<div style="margin: -20px 0px 0px 100px; display: none;" class="caption font-dark" id="copy_clients">
							<span class="caption-subject bold uppercase"> <img width="84" src="images/loader.gif" class="copy_clients"></span>
						</div>

						<!--div class="actions" style="padding: 1px 0px 0px 20px;">
							<span class="caption-subject bold uppercase"><a href="trash-pre-clients.php"><img src="images/trash.png" width="30">(<?php //echo $count; ?>)</a></span>
						</div-->


						<div class="caption font-dark">

							<span class="caption-subject bold uppercase"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						</div>
						<!--div class="ord_center1">

							<div>
								<span>
									<a class="btn btn-info active-ord" href="javascript:;" id="phone_orders">Phone Orders</a>
								</span>
							</div>
							<div>
								<span>
									<a class="btn btn-info" href="javascript:;" id="schedule_orders">Schedule Orders</a>
								</span>
							</div>
						</div-->
						<div class="actions">
							 <i class="icon-calendar"></i>&nbsp;
							 <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
						</div>

					</div>
					<div class="order-btn ord_center">
						
					</div>
					<div class="portlet-body">
						<div id='calendar'><div class="cal-overlay" style="display:none"></div></div>
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="hidd-home-work"></div>
	</div>
	<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->

<?php include('footer.php') ?>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>