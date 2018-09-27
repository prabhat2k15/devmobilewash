<?php
include('header.php') ?>
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
<style>
.total-cars-div{
        bottom: 26px;
    height: 28px;
    position: absolute;
    width: 100%;
    text-align: center;
    font-size: 14px;
}
.fc-basic-view .fc-body .fc-row{padding-bottom: 60px;}
.fc-left .center div{
	margin-bottom: 5px;
}

.fc-row.fc-rigid .fc-content-skeleton{
	position: relative;
}

.fc-day-grid-event .fc-content{
	white-space: normal;
}

</style>
<script src='assets/global/scripts/full-cal/moment.min.js'></script>
<script src='assets/global/scripts/full-cal/fullcalendar.min.js'></script>
<script>
var timetracker = 0;
$(document).ready(function() {
    	
	var color_back= title = "";
	var home = work = 0;
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
	    //console.log(type);
		$('#calendar').fullCalendar( 'destroy' );
		$url = '<?php echo ROOT_URL; ?>/api/index.php?r=washing/order_schedule_app';

		if(type == 'app_orders'){
			$url = '<?php echo ROOT_URL; ?>/api/index.php?r=washing/order_schedule_app';
		}if(type == 'phone_orders'){
			$url = "<?php echo ROOT_URL; ?>/api/index.php?r=washing/order_schedule";
		}if(type == 'schedule_orders'){
			$url = '<?php echo ROOT_URL; ?>/api/index.php?r=washing/order_schedule_ordSchedule';
		}if(type == 'all_orders'){
			$url = '<?php echo ROOT_URL; ?>/api/index.php?r=washing/order_schedule_all_orders';
		}
        //console.log($url);
		$('#calendar').fullCalendar({
			defaultDate: moment().toDate(),
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			droppable: false,
			aspectRatio: 1,
			events: function(start, end, timezone, callback) {
				//$('#calendar').fullCalendar('removeEvents');
                //console.log(start);
                //console.log(end);

				month = end.month();
				year = end.year();
                //console.log(month);
                 //console.log(year);
                 if(month == 0){
                     month = 12;
                     year--;
                 }
				start = startMonthYear(month,year)
				end = endMonthYear(month,year)
                 console.log("start "+start);
                 console.log("end "+end);
				//$('.cal-overlay').css('display','block');
			   $('.fc-left .center').remove();
				$.ajax({
					url: $url,
					 async: true,
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
                        //console.log(doc.order.empty);
						if(doc.order.empty == 'yes'){
							$('.cal-overlay').css('display','none');
						}
						else{
							var color,title,start,start1,color1,title1,start2,color2,title2,title3,color,title4,title5,title6,titledec,colordec,titletotalcars,colortotalcars,titleExp,titleDlx,titlePre,colorExp,colorDlx,colorPre,titleCC,colorCC,titleTa,titleondemandcompleted, titleschedulecompleted, titleaddoncompleted, colorTa,color6, colorschedulecompleted, colorondemandcompleted, coloraddoncompleted, schedulecanceledtitle, schedulecanceledcolor, ondemandcanceledtitle, ondemandcanceledcolor, new_customer;
							$.each( doc.order, function(index, value ) {
							    
								var _viewAll = ['1'];
								var view_all = Object.keys(_viewAll).length
								var declined = Object.keys(value.declined).length;
								var total_orders = Object.keys(value.total_orders).length;
								var pending = Object.keys(value.pending).length;
								var complete = Object.keys(value.complete).length;
								var canceled = Object.keys(value.canceled).length;
								var Express = Object.keys(value.Express).length;
								var Deluxe = Object.keys(value.Deluxe).length;
								var Premium = Object.keys(value.Premium).length;
								var coupon_code = Object.keys(value.coupon_code).length;
								var tip_amount = Object.keys(value.tip_amount).length;
								var processing = Object.keys(value.processing).length;
								var addoncompleted = Object.keys(value.addoncompleted).length;
								var ondemandcompleted = Object.keys(value.ondemandcompleted).length;
								var schedulecompleted = Object.keys(value.schedulecompleted).length;
								var schedulecanceled = Object.keys(value.schedulecanceled).length;
								var ondemandcanceled = Object.keys(value.ondemandcanceled).length;
                                var total_cars = Object.keys(value.total_cars).length;
								var home_ord = Object.keys(value.home).length;
								var work_ord = Object.keys(value.work).length;
								var new_customer = Object.keys(value.new_customer).length;
                                var scheduleauto = Object.keys(value.scheduled_auto).length;
                                var ondemandauto = Object.keys(value.ondemandautocanceled).length;
								var zipblue = value.zipblue.count;
								var zipyellow = value.zipyellow.count;
								var zipred = value.zipred.count;
                                var zippurple = value.zippurple.count;
								
                                if(value.new_customer.count > 0){
									titledec = value.new_customer.count+' New Customer';
									colordec = value.new_customer.color;
									events.push({
										eventtitle: 'newcustomer',
										title:titledec,
										description:'a',
										start:index,
										color:colordec
									});
								}
                                
								if(declined > 1  ){
									titledec = value.declined.count+' Declined Orders';
									colordec = value.declined.color;
									events.push({
										eventtitle: 'declined',
										title:titledec,
										description:'a',
										start:index,
										color:colordec
									});
								}
								
								if(total_orders > 1 ){
									title6 = value.total_orders.count+' Total Orders';
									color6 = value.total_orders.color;
									events.push({
										eventtitle: 'total_orders',
										title:title6,
										description:'b',
										start:index,
										color:color6,
										
									});
								}
								
								if(pending > 1 ){
									title1 = value.pending.count+' Pending Orders';
									color1 = value.pending.color;
									events.push({
										eventtitle: 'pending',
										title:title1,
										description:'c',
										start:index,
										color:color1,
										textColor: '#fff',
										
									});
								}
								if(complete > 1 ){
									title = value.complete.count+' Completed Orders';
									color = value.complete.color;
									events.push({
										eventtitle: 'completed',
										title:title,
										description:'d',
										start:index,
										color:color,
										
									});
								}
                                
                                if(scheduleauto > 0  ){
									title34 = value.scheduled_auto.count+' Scheduled Auto-Canceled';
									color34 = value.scheduled_auto.color;
									events.push({
										eventtitle: 'scheduleauto',
										title:title34,
										description:'n',
										start:index,
										color:color34,
										
									});
								}
                                
                                if(ondemandauto > 0  ){
									title36 = value.ondemandautocanceled.count+' Wash Now Auto-Canceled';
									color36 = value.ondemandautocanceled.color;
									events.push({
										eventtitle: 'ondemandauto',
										title:title36,
										description:'n',
										start:index,
										color:color36,
										
									});
								}
								
									if(processing > 1  ){
								    title2 = value.processing.count+' Processing Orders';
									color2 = value.processing.color;
									events.push({
										eventtitle: 'processing',
										title:title2,
										description:'e',
										start:index,
										color:color2,
										
									});
								}
								
									if(Express > 1  ){
									//titledec = 'Express Completed: '+value.Express.count;
									titleExp = value.Express.count+' Express Services';
									colorExp = value.Express.color;
									events.push({
										eventtitle: 'express',
										title:titleExp,
										description:'f',
										start:index,
										color:colorExp,
									});
								}
								
								if(Deluxe > 1  ){
									//titledec = 'Deluxe Completed: '+value.Deluxe.count;
									titleDlx = value.Deluxe.count+' Deluxe Services';
									colorDlx = value.Deluxe.color;
									events.push({
										eventtitle: 'deluxe',
										title:titleDlx,
										description:'g',
										start:index,
										color:colorDlx,
										
									});
								}
								if(Premium > 1  ){
									//titledec = 'Premium Completed: '+value.Premium.count;
									titlePre = value.Premium.count+' Premium Services';
									colorPre = value.Premium.color;
									events.push({
										eventtitle: 'premium',
										title:titlePre,
										description:'h',
										start:index,
										color:colorPre,
										
									});
								}
								
									if(coupon_code > 1  ){
									//titledec = 'Promo Codes: '+value.coupon_code.count;
									titleCC = value.coupon_code.count+' Promo Codes';
									colorCC = value.coupon_code.color;
									events.push({
										eventtitle: 'coupon_code',
										title:titleCC,
										description:'i',
										start:index,
										color:colorCC,
										
									});
								}
								
									if(tip_amount > 1  ){
									//titledec = 'Tips: '+value.tip_amount.count;
									titleTa = value.tip_amount.count+' Tips';
									colorTa = value.tip_amount.color;
									events.push({
										eventtitle: 'tip_amount',
										title:titleTa,
										description:'j',
										start:index,
										color:colorTa,
										textColor: '#fff',
										
									});
								}
								
								if(addoncompleted > 1  ){
								    titleaddoncompleted = value.addoncompleted.count+' Add-Ons Completed';
									coloraddoncompleted = value.addoncompleted.color;
									events.push({
										eventtitle: 'addoncompleted',
										title:titleaddoncompleted,
										description:'k',
										start:index,
										color:coloraddoncompleted,
										
									});
								}
								
								if(ondemandcompleted > 1  ){
								    titleondemandcompleted = value.ondemandcompleted.count+' On-Demand Completed';
									colorondemandcompleted = value.ondemandcompleted.color;
									events.push({
										eventtitle: 'ondemandcompleted',
										title:titleondemandcompleted,
										description:'l',
										start:index,
										color:colorondemandcompleted,
										
									});
								}
								
								if(schedulecompleted > 1  ){
								    titleschedulecompleted = value.schedulecompleted.count+' Scheduled Completed';
									colorschedulecompleted = value.schedulecompleted.color;
									events.push({
										eventtitle: 'schedulecompleted',
										title:titleschedulecompleted,
										description:'m',
										start:index,
										color:colorschedulecompleted,
										
									});
								}
								
								if(canceled > 1  ){
									title2 = value.canceled.count+' Total Canceled Orders';
									color2 = value.canceled.color;
									events.push({
										eventtitle: 'canceled',
										title:title2,
										description:'n',
										start:index,
										color:color2,
										
									});
								}
								
								if(ondemandcanceled > 1  ){
									ondemandcanceledtitle = value.ondemandcanceled.count+' On-Demand Canceled Orders';
									ondemandcanceledcolor = value.ondemandcanceled.color;
									events.push({
										eventtitle: 'ondemandcanceled',
										title:ondemandcanceledtitle,
										description:'o',
										start:index,
										color:ondemandcanceledcolor,
										
									});
								}
								
								if(schedulecanceled > 1  ){
									schedulecanceledtitle = value.schedulecanceled.count+' Scheduled Canceled Orders';
									schedulecanceledcolor = value.schedulecanceled.color;
									events.push({
										eventtitle: 'schedulecanceled',
										title:schedulecanceledtitle,
										description:'p',
										start:index,
										color:schedulecanceledcolor,
										
									});
								}
								
							
								//if(view_all >= 1){
									title3 = 'View All';
									color3 = '#2a3f53';
									events.push({
										title:title3,
										description:'v',
										start:index,
										color:color3,
										
									});
								//}
								
								if(zipblue > 0){
									
									titlezipblue = value.zipblue.count+' Blue Zone Orders';
									colorzipblue = value.zipblue.color;
									events.push({
										eventtitle: 'blueorders',
										title:titlezipblue,
										description:'r',
										start:index,
										color:colorzipblue,
									});
								}
								
								if(zipyellow > 0){
									
									titlezipyellow = value.zipyellow.count+' Yellow Zone Orders';
									colorzipyellow = value.zipyellow.color;
									events.push({
										eventtitle: 'yelloworders',
										title:titlezipyellow,
										description:'s',
										start:index,
										color:colorzipyellow,
									});
								}
								
								if(zipred > 0){
									
									titlezipred = value.zipred.count+' Red Zone Orders';
									colorzipred = value.zipred.color;
									events.push({
										eventtitle: 'redorders',
										title:titlezipred,
										description:'t',
										start:index,
										color:colorzipred,
									});
								}
                                
                                if(zippurple > 0){
									titlezippurple = value.zippurple.count+' Purple Zone Orders';
									colorzippurple = value.zippurple.color;
									events.push({
										eventtitle: 'purpleorders',
										title:titlezippurple,
										description:'t',
										start:index,
										color:colorzippurple,
									});
								}
                                 if(total_cars >= 1  ){
								   /*	titletotalcars = value.total_cars.count+' Vehicles';
									colortotalcars = "#000000";
										events.push({
										title:titletotalcars,
										start:index,
										color  : colortotalcars
									});*/
                                    //home = value.home.count;
									$('.hidd-total_cars').append('<input class="total_cars" data-date="'+index+'" type="hidden" value="'+value.total_cars.count+'">');
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
			eventClick: function(calEvent, jsEvent, view) {
				
				var _day = calEvent.start._i;
				var _event = calEvent.eventtitle;
				
				if ((calEvent.eventtitle == '') || (!calEvent.eventtitle)) {
					_event = 'all';
				}
				
				/*if(calEvent.color == '#f6a635'){
					_event = 'pending';
				}else if(calEvent.color == '#9c64b7'){
					_event = 'total_orders';
				}else if(calEvent.color == '#14c266'){
					_event = 'completed';
				}else if(calEvent.color == '#e67418'){
					_event = 'processing';
				}
				else if(calEvent.color == '#8b9d9e'){
					_event = 'canceled';
				}
				else if(calEvent.color == '#eb1350'){
					_event = 'declined';
				}
				else if(calEvent.color == '#2490d7'){
					_event = 'express';
				}
				else if(calEvent.color == '#2490d7'){
					_event = 'deluxe';
				}
				else if(calEvent.color == '#2490d7'){
					_event = 'premium';
				}
				else if(calEvent.color == '#800080'){
					_event = 'coupon_code';
				}
				else if(calEvent.color == '#f28fba'){
					_event = 'tip_amount';
				}
				else{
					_event = 'all'
				}*/
				
			
				if(type == 'app_orders'){
					window.location  = "<?php echo ROOT_URL; ?>/admin-new/manage-orders.php?day="+_day+"&event="+_event;
				}else if(type == 'phone_orders'){
					window.location = "<?php echo ROOT_URL; ?>/admin-new/phone-orders.php?day="+_day+"&event="+_event;
				}else if(type == 'schedule_orders'){
					window.location = "<?php echo ROOT_URL; ?>/admin-new/schedule-orders.php?sday="+_day+"&event="+_event;
				}else if(type == 'all_orders'){
					window.location = "<?php echo ROOT_URL; ?>/admin-new/all-orders.php?ajax=true&alordday="+_day+"&event="+_event;
				}else{
					window.open("<?php echo ROOT_URL; ?>/admin-new/all-orders.php?ajax=true&day="+_day+"&event="+_event);
					//window.location  = "<?php echo ROOT_URL; ?>/admin-new/all-orders.php?day="+_day+"&event="+_event;
				}
			},
			eventRender: function(event, element) {
				var moment_current_month = moment().format('M');
				var event_current_month =  parseInt(event.start.month()+1);

				var event_all_Day = event.start.date();
				var event_current_day = moment().format('D');
				var event_previous_day = moment().add(-1, 'days').format('D');
				var event_next_day = moment().add(+1, 'days').format('D');

				var event_title = $('fc-title').text();
			 /* if(event_current_month == moment_current_month){
					if( event_all_Day < event_previous_day  && event.title == 'View All'){
						return false;
					}
					else if(event_all_Day > event_next_day  && event.title == 'View All'){
						return false;
					}

				}else{
					if(event.title == "View All" ){
						return false;
					}
				}*/

			},
			eventAfterAllRender: function(view){

				//var _html = '<div class="center" style="width: 45%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span>Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #aaa; background: #aaa;"><span>Canceled</span></div>	</div>';

				//$('.fc-left').append(_html);

                $('.hidd-total_cars input.total_cars').each(function(index, val){
                    //console.log('working');
					total_cars_count = $(this).val();
					total_cars_day = $(this).data('date');
                    //console.log(total_cars_day);
					if(total_cars_count.length < 1) total_cars_count = 0;
					$('.fc-day-grid .fc-bg tbody tr td[data-date="'+total_cars_day+'"]').append('<div class="total-cars-div">0 Vehicles</div>');
					$('td[data-date="'+total_cars_day+'"] .total-cars-div').text(total_cars_count+" Vehicles");

				});
                $('.hidd-home-work input.home').each(function(){

					home_count = $(this).val();
					home_day = $(this).data('date');
					if(home_count.length < 1) home_count = 0;
					$('.fc-day-grid .fc-bg tbody tr td[data-date="'+home_day+'"]').append('<div class="home_work"><div class="left">Home: 0</div></div>');
					$('td[data-date="'+home_day+'"] .home_work .left').text('Home: '+home_count);

				});
				$('.hidd-home-work input.work').each(function(){
					work_count = $(this).val();
					work_day = $(this).data('date');
					if(work_count.length < 1) work_count = 0;
					$('.fc-day-grid .fc-bg tbody tr td[data-date="'+work_day+'"] .home_work').append('<div class="right">Work: 0</div>');
					$('td[data-date="'+work_day+'"] .home_work .right').text('Work: '+work_count);
				});
				if($('.hidd-home-work input').length > 0){
					$('.hidd-home-work').find('input').remove();
				}
			},
			dayClick: function(date, jsEvent, view) {
				if($('.fc-day').hasClass('day_highlight')){
					if($(this).hasClass('day_highlight')){
						$('.fc-day').removeClass('day_highlight');
					}else{
						$('.fc-day').removeClass('day_highlight');
						$(this).addClass('day_highlight');
					}
				}else{
					$(this).addClass('day_highlight');
				}
			},
			eventOrder: "description"
		});
		
		 var _html = '<div class="center" style="width: 70%;"><div id="view_all"><span>View All</span></div><div id="complete"><span>Complete</span></div><div id="pending"><span style="color: #fff;">Pending</span></div><div id="process"><span>Processing</span></div><div id="canceled" style="border: 1px solid #8b9d9e; background: #8b9d9e;"><span>Canceled</span></div><div id="declined" style="border: 1px solid #eb1350; background: #eb1350;"><span>Declined</span></div><div id="express" style="border: 1px solid #2490d7; background: #2490d7;"><span>Express</span></div><div id="deluxe" style="border: 1px solid #2490d7; background: #2490d7;"><span>Deluxe</span></div><div id="premium" style="border: 1px solid #2490d7; background: #2490d7;"><span>Premium</span></div><div id="tip" style="border: 1px solid #f28fba; background: #f28fba; color: #fff;"><span>Tip</span></div><div id="addoncompleted" style="border: 1px solid #87CEFA; background: #87CEFA; color: #fff;"><span>Add-Ons</span></div><div id="ondemandcompleted" style="border: 1px solid #008080; background: #008080; color: #fff;"><span>On-Demand</span></div><div id="schedulecompleted" style="border: 1px solid #0000ff; background: #0000ff; color: #fff;"><span>Scheduled</span></div><div id="new_customer" style="border: 1px solid #3fcfb6; background: #3fcfb6; color: #fff;"><span>New Customer</span></div>	</div>';

				$('.fc-left').append(_html);
				
				 //setTimeout( show_calendar(__bool), 10000 );
	}
	
	show_calendar(__bool);

});

function startMonthYear(month,year){
	last_year = '';
	last_month = parseInt(month) -1;
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
				if(timetracker >= 120) window.location.href="<?php echo ROOT_URL; ?>/admin-new/show-calendar.php";
				 setTimeout( refreshCal, 10000 );
}

//setTimeout( refreshCal, 10000 );

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

						<div class="actions">
							 <i class="icon-calendar"></i>&nbsp;
							 <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
						</div>

					</div>

					<div class="portlet-body">
						<div id='calendar'><div class="cal-overlay" style="display:none"></div></div>
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<div class="clearfix"></div>
        <div class="hidd-total_cars"></div>
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