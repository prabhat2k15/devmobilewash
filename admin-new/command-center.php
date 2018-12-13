<?php include_once('cc-header.php'); ?>
<style>
body {
	/* cursor: url('images/cursor.ico'), default !important; */
 cursor: url('images/cursor.cur'), default !important;
}

body a:hover{
	/* cursor: url('images/cursor-blue.ico'), default !important; */
}

#map_wrapper {
    height: 100%;
}

#map_canvas {
    width: 100%;
    height: 100%;
}

.mapicon-hide{
   opacity: .4;
}

.single-stat img{
/* cursor: url('images/cursor-blue.ico'), default !important; */
    cursor: pointer;
width: 35px;
}

.admin-home .stat-row .single-stat .count{
font-size: 36px;
}

.admin-home .stat-row .single-stat .status{
font-size: 16px;
}

.topbar{
    position: absolute;
    z-index: 20;
    width: 100%;
    background: rgba(0, 0, 0, .8);
    color: #fff;
    padding: 20px 0;
}

.bottom-bar{
    position: absolute;
    z-index: 20;
    width: 100%;
    background: rgba(0, 0, 0, .8);
    color: #fff;
    bottom: 0;
}

.admin-home .stat-row{
    background: rgba(0, 0, 0, .5);
/* text-align: center; */
}

.admin-home .stat-row.alt {
    background: rgba(30, 30, 30, .5);
text-align: center;
}

.admin-home .stat-row .single-stat{
float: none;
display: inline-block;
text-align: left;
margin-right: 25px;
/* cursor: url('images/cursor-blue.ico'), default !important; */
cursor: pointer;
}

.admin-home .stat-row .client-online .count{
    color: #76ea73 !important;
}

.admin-home .stat-row .client-online .status{
    color: #38dc34 !important;
}

.admin-home .stat-row .pending-order .count{
    color: #ff4b47 !important;
}

.admin-home .stat-row .pending-order .status{
    color: #ff3c37 !important;
}

.admin-home .stat-row .processing-order .count, .admin-home .stat-row .sched-orders .count{
    color: #f69944 !important;
}

.admin-home .stat-row .processing-order .status, .admin-home .stat-row .sched-orders .status{
    color: #ff973b !important;
}

.admin-home .stat-row .agent-online .count{
    color: #4a9efb !important;
}

.admin-home .stat-row .agent-online .status{
    color: #4a9efb !important;
}

.admin-home .stat-row .agent-busy .count{
    color: #f69944 !important;
}

.admin-home .stat-row .agent-busy .status{
     color: #ff973b !important;
}

.admin-home .stat-row .agent-offline .count{
    color: #8e8e8e !important;
}

.admin-home .stat-row .agent-offline .status{
     color: #8e8e8e !important;
}

.admin-home .stat-row .single-stat .count{
    color: #fff;
}

.admin-home .stat-row .total-order .count, .admin-home .stat-row .today-order .count{
    color: #fff !important;
}

.admin-home .stat-row .section{
    width: 1076px;
    float: left;
}

.menu-container ::-webkit-scrollbar {
    width: 12px;
}

.menu-container ::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
}

.menu-container ::-webkit-scrollbar-thumb {
    border-radius: 10px;

    background: rgba(255, 255, 255, .3);
}


.menu-container{
   width: auto;
height: 300px;
   background: #000;
   background: rgba(0, 0, 0, .8);
   position: absolute;
   top: 70px; right: 10px;
   z-index: 22;
    color: #fff;
    display: none;
min-width: 300px;
max-width: 800px;
 -webkit-transition: right 1s; /* Safari */
    transition: right 1s;
/* overflow: auto; */
}

.menu-container .tabs h2{
  margin-top: 0;
    font-size: 18px;
    /* border-bottom: 1px solid #ff4b47; */
    padding-bottom: 10px;
    margin-bottom: 0;
    display: inline-block;
    background: rgb(218, 0, 0);
    padding: 10px;
    cursor: pointer;
/* cursor: url('images/cursor-blue.ico'), default !important; */
}

.menu-container .tab-content ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

.menu-container .tab-content ul li{
    display: block;
    padding: 10px;
position: relative;
padding-right: 30px;
}

.menu-container .tab-content ul li .subarrow{
width: 21px;
height: 21px;
position: absolute;
background: url('images/arrow-right.png') no-repeat top left;
right: 5px;
top: 10px;
/* cursor: url('images/cursor-blue.ico'), default !important; */
cursor: pointer;
}


.menu-container .tab-content ul li:nth-child(odd){
background: rgba(150, 150, 150, .8);
}

.menu-container .tab-content ul li a{
    color: #fff;
    text-decoration: none;
}

.menu-container .tab-content ul li .submenu{
width: 300px;
background: rgb(218, 0, 0);
background: #000;
   background: rgba(0, 0, 0, .8);
position: absolute;
top: 0;
right: -310px;
display: none;
min-height: 300px;
}

.menu-container .tab-content ul li .submenu ul li{
padding-right: 70px;
}

.menu-container .tab-content ul li .submenu .assign-agent{
background: #fff;
    color: #000;
    padding: 6px;
    position: absolute;
    right: 6px;
    top: 4px;
 /* cursor: url('images/cursor-blue.ico'), default !important; */
    cursor: pointer;
    font-size: 14px;
}

.note-message{
width: 500px;
background: green;
    background: rgba(76,175,80,0.9);
    color: #fff;
    position: absolute;
    top: 0;
    z-index: 22;
    left: 50%;
    margin-left: -250px;
    padding: 10px;
    text-align: center;
display: none;
word-break: break-all;
}

.search-cc{

}

.search-cc .search-autocomplete-box{
 position: absolute;
    background: rgba(0, 0, 0, .9);
    z-index: 40;
    width: 100%;
    min-height: 300px;
max-height: 800px;
overflow: auto;
display: none;
}

.search-cc .search-autocomplete-box ul{
list-style: none;
margin: 0;
padding: 0;
}

.search-cc .search-autocomplete-box ul li{
display: block;
    margin-bottom: 1px;

    color: #fff;
    padding: 10px;
}

.search-cc .search-autocomplete-box ul li.client{
background: rgba(74, 251, 88, 0.8);
}

.search-cc .search-autocomplete-box ul li.agent{
background: rgba(74, 145, 251, 0.8);
}

.search-cc .search-autocomplete-box ul li.agent-offline{
background: rgba(138, 138, 138, 0.8);
}

.search-cc .search-autocomplete-box ul li.agent-busy, .search-cc .search-autocomplete-box ul li.agent-progress, .search-cc .search-autocomplete-box ul li.client-progress{
background: #f69944;
}

.search-cc .search-autocomplete-box ul li.agent-pending{
background: #ff3c37;   
}
    
.search-cc .search-autocomplete-box ul li a{
display: block;
color: #fff;
text-decoration: none;
}

.search-cc input[type="text"]{
width: 100%;
background: none;
border: 0;
padding: 10px;
background: rgba(80, 80, 80, 0.7) url('images/search-icon.png') no-repeat 97% center;
color: #fff;
font-size: 16px;
padding-right: 35px;
}

.color-block{
     width: 10px;
    height: 10px;
    background: #ccc;
    display: inline-block;
    vertical-align: middle;
}

.color-block.yellow{
 background: #ffeb3b;
}

.color-block.red{
 background: #ff5722;
}

</style>
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

        document.getElementById("servertime").innerHTML=time + ' PST / ' + today+'<br/>Los Angeles, CA'
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>
<body class="admin-panel admin-dashboard admin-home">

<audio id="audio1">
	<source src="sounds/welcome-sound.mp3"></source>
</audio>
<audio id="audio2">
	<source src="sounds/cc-welcome.mp3"></source>
</audio>
<audio id="order-assign-audio">
	<source src="sounds/order-assigned.mp3"></source>
</audio>
<audio id="order-pending-audio" onended="finishpendingaudio(this);">
	<source src="sounds/order-pending-alert.mp3"></source>
</audio>
	<div id="container" style="padding-bottom: 0; position: relative;">
<div class="note-message"></div>
        <a href="#" class="mw-menu" title="Menu"><img src="images/icon-menu.png" alt="" style="width: 40px; position: absolute; top: 10px; right: 10px; z-index: 20;" /></a>
        <div class="menu-container">
<div class="search-cc">
<input type="text" id="cc-search-text" placeholder="Search">
<div class="search-autocomplete-box">
<ul>
<li class="client"><a href="#">John Doe</a></li>
<li><a href="#">John Doe</a></li>
<li class="agent"><a href="#">John Doe</a></li>
</ul>
</div>
</div>
            <div class="tabs">
                <h2 id="pending-tab">Pending Orders</h2>
            </div>
            <div data-id="pending-tab" class="tab-content pending-tab-content">

            </div>
        </div>
        <div id="main-col" style="padding: 0; width: 100%;">
         <div id="map_wrapper">
            <div id="map_canvas" class="mapping"></div>
         </div>

		<div class="clear"></div>
		</div><!--main col end-->
		<div class="clear"></div>
        <div class="bottom-bar">
        	<div class="stat-row">
            <div class="section">
			<div class="single-stat client-online">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Clients Online</p>
				</div>
				<div class="clear"></div>
			</div>
			<div class="single-stat pending-order">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Pending Orders</p>
				</div>
				<div class="clear"></div>
			</div>
			<div class="single-stat processing-order">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Orders in Progress</p>
				</div>
				<div class="clear"></div>
			</div>
            <div class="single-stat cancel-order-client">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Client Cancels</p>
				</div>
				<div class="clear"></div>
			</div>
            </div>

            <div class="section">
            <div class="single-stat agent-online">

				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Washers Online</p>
				</div>
				<div class="clear"></div>
			</div>
			<div class="single-stat agent-busy">

				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Busy Washers</p>
				</div>
				<div class="clear"></div>
			</div>
			<div class="single-stat agent-offline mapicon-hide">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Offline Washers</p>
				</div>
				<div class="clear"></div>
			</div>
            <div class="single-stat agent-late">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Late Washers</p>
				</div>
				<div class="clear"></div>
			</div>
             <div class="single-stat agent-cancel">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Washer Cancels</p>
				</div>
				<div class="clear"></div>
			</div>

            </div>

           <div class="section" style="width: 670px;">
            <!--<div class="single-stat total-order">

				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Total Orders</p>
				</div>
				<div class="clear"></div>
			</div>-->
            <div class="single-stat today-order">

				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Orders Today</p>
				</div>
				<div class="clear"></div>
			</div>
              <div class="single-stat agent-idle">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Idle Washers</p>
				</div>
				<div class="clear"></div>
			</div>
<div class="single-stat home-orders">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Home Orders</p>
				</div>
				<div class="clear"></div>
			</div>
<div class="single-stat office-orders">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Work Orders</p>
				</div>
				<div class="clear"></div>
			</div>
            <div class="single-stat sched-orders">
				<div class="single-stat-content">
				<p class="count">0</p>
				<p class="status">Schedule Orders</p>
				</div>
				<div class="clear"></div>
			</div>
             </div>
			<div class="clear"></div>
<a href="#" style="position: absolute; bottom: 90px; right: 110px; font-size: 18px; text-align: left; color: #fff; text-decoration: none;" class="ziparea-toggle">Zipcode Area: On</a>
            <span style="display: block; position: absolute; bottom: 25px; right: 34px; font-size: 18px; text-align: left;" id="servertime"></span>
		</div>
        </div>
	</div><!--container end-->
<?php include_once('cc-footer.php'); ?>
<script src="https://devmobilewash.com:3000/socket.io/socket.io.js"></script>
<script>
var markers = [];
var closest_markers = [];
var infoWindowContent;
var map;
var bounds;
var markerClusterer;
var infoWindow;
var layer;
var socketId;
var socketintvaltimer;
var location_arr = [];
var total_stop_count = 0;
var socket = io.connect("https://devmobilewash.com:3000", { query: "action=commandcenter", secure: true});
var is_shiftpressed = 0;
var selectedzips = '';
 var shiftPressed = false;
     var mouseDownPos, gribBoundingBox = null,
        mouseIsDown = 0;
    var themap;
    var ziparea_polys = [];

socket.on('connect', function() {
socketId = socket.io.engine.id;
  //console.log(socketId);
  socketintvaltimer = setInterval(function(){
    
    socket.emit('get_appstat',{socketId:socketId, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"});
    socket.emit('get_pendingwashesdetails',{socketId:socketId, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"});
    socket.emit('get_agentsbystatus',{socketId:socketId, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"});
    socket.emit('get_clientsbystatus',{socketId:socketId, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"});
    
    }, 60000);
  
});
$(window).load(function(){
    $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=washing/wash30secondrunning", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
            $.each(markers, function(i, marker) {

                if(marker.category == 'onlineagents'){
                    //console.log(marker);
                    //alert(data.washer_id);
                    if(marker.id == data.washer_id) {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    }
                }

            });
    });
    $.getJSON("<?php echo ROOT_URL;?>/api/index.php?r=agents/washernotifycc", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
        if(data.result){
            var data_show = data.response;
                location_arr[0] = data_show[0].agent_id;
                location_arr[1] = data_show[0].latitude;
                location_arr[2] = data_show[0].longitude;
                total_stop_count =1;
                console.log(location_arr);
        }
    });
});
setInterval(function(){
    $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=washing/wash30secondrunning", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
            $.each(markers, function(i, marker) {

                if(marker.category == 'onlineagents'){
                    //console.log(marker);
                    //alert(data.washer_id);
                    if(marker.id == data.washer_id) {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    }else{
                        marker.setAnimation(null);
                    }
                }

            });
    });
}, 60000);
    
setInterval(function(){
    $.getJSON("<?php echo ROOT_URL;?>/api/index.php?r=agents/washernotifycc", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
        if(data.result){
            var data_show = data.response;
            if(location_arr.length > 0){
                if(location_arr[0] == data_show[0].agent_id && location_arr[1] == data_show[0].latitude && location_arr[2] == data_show[0].longitude){
                    total_stop_count = parseInt(total_stop_count)+1;
                }
            }else{
                total_stop_count = 1;
            }   
                var data_show = data.response;
                location_arr[0] = data_show[0].agent_id;
                location_arr[1] = data_show[0].latitude;
                location_arr[2] = data_show[0].longitude;
                //alert(total_stop_count);
        }else{
            total_stop_count = 0;
        }
    });
    if(total_stop_count == 5){
        //alert("You must drive to your order");
    }
}, 60000);
</script>

<script>
      var script = '<script type="text/javascript" src="js/markerclusterer';
      if (document.location.search.indexOf('compiled') !== -1) {
        script += '_compiled';
      }
      script += '.js"><' + '/script>';
      document.write(script);
    </script>
<script>

$(function(){

   $(".stat-row .agent-online").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'onlineagents') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });

  $(".stat-row .agent-busy").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'busyagents') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });


   $(".stat-row .agent-offline").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'offlineagents') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });


    $(".stat-row .client-online").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'onlineclients') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });


      $(".stat-row .pending-order").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'pendingorders') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });

     $(".stat-row .sched-orders").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'schedorders') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });


   $(".stat-row .processing-order").click(function(){
        $(this).toggleClass('mapicon-hide');
        var selc = this;
    $.each(markers, function(i, marker) {

        if(marker.category == 'processorders') {
            if($(selc).hasClass('mapicon-hide')){
              marker.setVisible(false);
            }
            else{
             marker.setVisible(true);
            }

        }

    });
  });
});



socket.on('get appstat', function (data) {
    $(".client-online .count").html(data.Online_Customers);
  $(".pending-order .count").html(data.Pending_Orders);
  $(".sched-orders .count").html(data.Schedule_Orders);
   $(".processing-order .count").html(data.Processing_Orders);
    $(".total-order .count").html(data.Completed_Orders);
    $(".today-order .count").html(data.Completed_Orders_today);
   $(".agent-online .count").html(data.Online_Agent);
    $(".agent-busy .count").html(data.busy_Agents);
    $(".agent-offline .count").html(data.Offline_Agent);
     $(".cancel-order-client .count").html(data.Cancel_Orders_Client);
     $(".agent-cancel .count").html(data.Cancel_Orders_Agent);
});

socket.on('get pendingwashesdetails', function (data) {
    console.log(data);
       var no_avail_agent = 0;
if(data.pending_washes.length){
    pending_data = '';
     pending_data += '<ul>';

    $.each(data.pending_washes, function( index, pending_wash ) {
        pending_data += "<li id="+pending_wash.customer_id+"><a href=''>"+pending_wash.customer_name+" - "+pending_wash.address+"</a>";

        if(pending_wash.available_agents.length){
            no_avail_agent = 1;
            if($(".pending-tab-content #"+pending_wash.customer_id+" .submenu").is(':visible')) pending_data += "<div class='subarrow'></div><div class='submenu' style='display: block;'><ul>";
            else pending_data += "<div class='subarrow'></div><div class='submenu'><ul>";

            $.each(pending_wash.available_agents, function( ind, a_agent ) {
               pending_data += "<li id="+a_agent.id+"><a href='#'>"+a_agent.name+" ("+a_agent.distance+" miles)</a> <span class='assign-agent' data-agent-id="+a_agent.id+" data-wash-id="+pending_wash.id+">Assign</span></li>";
            });
           pending_data += "</ul></div>";
        }

});
  pending_data += '</ul>';
  $(".menu-container .pending-tab-content").html(pending_data);

}
else{
   $(".menu-container .pending-tab-content").html("<p style='padding: 0 10px;'>No Pending Orders Found</p>");
}


if(no_avail_agent == 0){
 $('.menu-container').css('right', '10px');
}
});

socket.on('get agentsbystatus', UpdateAgents);
socket.on('get clientsbystatus', UpdateClients);


function initialize_map(){

$.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=agents/agentsbystatus", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, PlotAgents);

$.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=customers/clientsbystatus", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, PlotClients);

$.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=users/Appstat", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
  $(".client-online .count").html(data.Online_Customers);
  $(".pending-order .count").html(data.Pending_Orders);
  $(".sched-orders .count").html(data.Schedule_Orders);
   $(".processing-order .count").html(data.Processing_Orders);
    $(".total-order .count").html(data.Completed_Orders);
    $(".today-order .count").html(data.Completed_Orders_today);
   $(".agent-online .count").html(data.Online_Agent);
    $(".agent-busy .count").html(data.busy_Agents);
    $(".agent-offline .count").html(data.Offline_Agent);
     $(".cancel-order-client .count").html(data.Cancel_Orders_Client);
     $(".agent-cancel .count").html(data.Cancel_Orders_Agent);
 $(".home-orders .count").html(data.Home_Orders);
 $(".office-orders .count").html(data.Office_Orders);

});

$.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=washing/pendingwashesdetails", {key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function( data ) {
    var no_avail_agent = 0;
if(data.pending_washes.length){
    pending_data = '';
     pending_data += '<ul>';

    $.each(data.pending_washes, function( index, pending_wash ) {
        pending_data += "<li id="+pending_wash.customer_id+"><a href=''>"+pending_wash.customer_name+" - "+pending_wash.address+"</a>";

        if(pending_wash.available_agents.length){
            no_avail_agent = 1;
            if($(".pending-tab-content #"+pending_wash.customer_id+" .submenu").is(':visible')) pending_data += "<div class='subarrow'></div><div class='submenu' style='display: block;'><ul>";
            else pending_data += "<div class='subarrow'></div><div class='submenu'><ul>";

            $.each(pending_wash.available_agents, function( ind, a_agent ) {
               pending_data += "<li id="+a_agent.id+"><a href='#'>"+a_agent.name+" ("+a_agent.distance+" miles)</a> <span class='assign-agent'>Assign</span></li>";
            });
           pending_data += "</ul></div>";
        }

});
  pending_data += '</ul>';
  $(".menu-container .pending-tab-content").html(pending_data);

}
else{
   $(".menu-container .pending-tab-content").html("<p style='padding: 0 10px;'>No Pending Orders Found</p>");
}


if(no_avail_agent == 0){
 $('.menu-container').css('right', '10px');
}
});

  }

  initialize_map();


function PlotAgents(agentdata){

$.each( agentdata.online, function( index, agent ){
//console.log(index);


    agentname = agent['first_name']+" "+agent['last_name'];
    content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Badge Number: "+agent['badge_number']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/online-agent-pin.png', 'onlineagents', content);

});

$.each( agentdata.offline, function( index, agent ){
//console.log(index);


    agentname = agent['first_name']+" "+agent['last_name'];
   content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Badge Number: "+agent['badge_number']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/offline-agent-pin.png', 'offlineagents', content);

});

$.each( agentdata.busyAgents, function( index, agent ){
//console.log(index);


    agentname = agent['first_name']+" "+agent['last_name'];
  content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Badge Number: "+agent['badge_number']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/busy-agent-pin.png', 'busyagents', content);

});

 //markerClusterer.addMarkers(markers);

}


function PlotClients(clientdata){

$.each( clientdata.online_clients, function( index, client ){
//console.log(index);


    clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p>Last Wash: "+client['last_wash']+"</p>";
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(client['id'], clientname, client['latitude'], client['longitude'], 'images/online-client-pin.png', 'onlineclients', content);

});


$.each( clientdata.pending_orders, function( index, client ){
//console.log(index);
var pin_image = 'images/order-pin-animated.gif';
if (client['agent_id'] != 0) {
    pin_image = 'images/order-pin-yellow.png';
}
//console.log(client['agent_id']+" "+client['wash_request_id']+" "+pin_image);
 var order_date = new Date(client['created_date']);
 //console.log(order_date);
 today = new Date();
 //console.log(today);
 var diffMs = (today - order_date);
var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes

    clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
     content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
    content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    if (client['agent_id'] != 0) {
	content += "<p>Washer Badge: #"+client['agent_badge_id']+"</p>";
	content += "<p>Washer Name: "+client['agent_name']+"</p>";
	content += "<p>Washer Phone: "+client['agent_phone']+"</p>";
    }
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    if(diffMins >= 3 ){
       addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'pendingorders', content, client['wash_request_id']);

 }
    else addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'pendingorders', content, client['wash_request_id']);

});

$.each( clientdata.schedule_orders, function( index, client ){
//console.log(index);

var pin_image = 'images/pending-order-pin.png';
if (client['agent_id'] != 0) {
    pin_image = 'images/order-pin-yellow.png';
}

if (client['order_for'] == 'tomorrow') {
     pin_image = 'images/pin-pink.png';
}

 var order_date = new Date(client['created_date']);


    clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
    content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p>Schedule DateTime: "+client['schedule_date']+" "+client['schedule_time']+"</p>";
    if (client['agent_id'] != 0) {
	content += "<p>Washer Badge: #"+client['agent_badge_id']+"</p>";
	content += "<p>Washer Name: "+client['agent_name']+"</p>";
	content += "<p>Washer Phone: "+client['agent_phone']+"</p>";
    }
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";

   addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'schedorders', content, client['wash_request_id']);

});

$.each( clientdata.processing_orders, function( index, client ){
//console.log(index);


    clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";

    addlocation(client['id'], clientname, client['latitude'], client['longitude'], 'images/processing-order-pin.png', 'processorders', content, client['wash_request_id']);

});


//markerClusterer.addMarkers(markers);

}

function UpdateAgents(agentdata){

var onlineagentids = new Array();
 var offlineagentids = new Array();
 var busyagentids = new Array();
 var id_exists = false;

$.each( agentdata.online, function( index, agent ){
//console.log(index);
 id_exists = false;
 for(var i=0;i<markers.length;i++){
        if(markers[i].id == agent['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'onlineagents'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

 if(!id_exists){
       agentname = agent['first_name']+" "+agent['last_name'];
    content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/online-agent-pin.png', 'onlineagents', content);

 }
 else{
      updatelocation(agent['id'], agent['latitude'], agent['longitude']);
 }

      onlineagentids.push(agent['id']);
});

id_exists = false;

$.each( agentdata.offline, function( index, agent ){
//console.log(index);
      id_exists = false;
     for(var i=0;i<markers.length;i++){
        if(markers[i].id == agent['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'offlineagents'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

 if(!id_exists){
     agentname = agent['first_name']+" "+agent['last_name'];
   content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/offline-agent-pin.png', 'offlineagents', content);
 }
 else{
     updatelocation(agent['id'], agent['latitude'], agent['longitude']);
 }

   offlineagentids.push(agent['id']);
});

id_exists = false;

$.each( agentdata.busyAgents, function( index, agent ){
//console.log(index);
     id_exists = false;
  for(var i=0;i<markers.length;i++){
        if(markers[i].id == agent['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'busyagents'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

  if(!id_exists){
       agentname = agent['first_name']+" "+agent['last_name'];
  content = "<p><img src='"+agent['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+agentname+"</b></p>";
    content += "<p>Phone Number: "+agent['phone_number']+"</p>";
     content += "<p>Rating: "+agent['rating']+"</p>";
    content += "<p>Total Washes: "+agent['total_wash']+"</p>";
    content += "<p><a href='#' class='send-agent-notify' data-id='"+agent['id']+"'>Send Notification</a><a href='#' class='send-agent-sms' data-id='"+agent['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(agent['id'], agentname, agent['latitude'], agent['longitude'], 'images/busy-agent-pin.png', 'busyagents', content);
  }
  else{
     updatelocation(agent['id'], agent['latitude'], agent['longitude']);
  }

    busyagentids.push(agent['id']);
});

  removeagents(onlineagentids, "onlineagents");
  removeagents(offlineagentids, "offlineagents");
  removeagents(busyagentids, "busyagents");
 //markerClusterer.addMarkers(markers);

}


function UpdateClients(clientdata){

 var onlineclientids = new Array();
 var pendingclientids = new Array();
 var processclientids = new Array();
 var id_exists = false;

$.each( clientdata.online_clients, function( index, client ){
//console.log(index);
  id_exists = false;
  for(var i=0;i<markers.length;i++){
        if(markers[i].id == client['id']){

            //console.log(markers[i].category);
            if(markers[i].category == 'onlineclients'){
                //console.log(markers[i].id);
                id_exists = true;
            }
        }
 }

 //console.log(client['id']+ " "+ id_exists);

  if(!id_exists){
     clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p>Last Wash: "+client['last_wash']+"</p>";
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(client['id'], clientname, client['latitude'], client['longitude'], 'images/online-client-pin.png', 'onlineclients', content);

   }
   else{
        //console.log("you just logged in: "+client['id']);
     updatelocation(client['id'], client['latitude'], client['longitude']);

   }

    onlineclientids.push(client['id']);
});

 id_exists = false;

$.each( clientdata.pending_orders, function( index, client ){
//console.log(index);
  id_exists = false;
 for(var i=0;i<markers.length;i++){
        if(markers[i].id == client['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'pendingorders'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

//console.log(client['created_date']);
var pin_image = 'images/order-pin-animated.gif';
if (client['agent_id'] != 0) {
    pin_image = 'images/order-pin-yellow.png';
}
 var order_date = new Date(client['created_date']);
 //console.log("order date "+order_date);
 today = new Date();
 //console.log(today);
 var diffMs = (today - order_date);
var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
 //console.log(diffMins);
if(diffMins == 3 ){

    //console.log('order waiting for 3 mins');
   removeclients(client['id'], "pendingorders");
   id_exists = false;
   var pending_audio = document.getElementById("order-pending-audio");
   if(!$(pending_audio).hasClass('inplay')) {
    //pending_audio.play();
    $(pending_audio).addClass('inplay');
   }

}

  if(!id_exists){
     clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    if (client['agent_id'] != 0) {
	content += "<p>Washer Badge: #"+client['agent_badge_id']+"</p>";
	content += "<p>Washer Name: "+client['agent_name']+"</p>";
	content += "<p>Washer Phone: "+client['agent_phone']+"</p>";
    }
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";

    if(diffMins == 3 ){
       addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'pendingorders', content, client['wash_request_id']);
    }
    else addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'pendingorders', content, client['wash_request_id']);
  }
  else{
    updatelocation(client['id'], client['latitude'], client['longitude']);
  }



       pendingclientids.push(client['id']);
});


 id_exists = false;

$.each( clientdata.schedule_orders, function( index, client ){
//console.log(index);
  id_exists = false;
 for(var i=0;i<markers.length;i++){
        if(markers[i].id == client['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'schedorders'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

//console.log(client['created_date']);
var pin_image = 'images/pending-order-pin.png';
if (client['agent_id'] != 0) {
    pin_image = 'images/order-pin-yellow.png';
}

if (client['order_for'] == 'tomorrow') {
     pin_image = 'images/pin-pink.png';
}

 var order_date = new Date(client['created_date']);
 //console.log("order date "+order_date);



  if(!id_exists){
     clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p>Schedule DateTime: "+client['schedule_date']+" "+client['schedule_time']+"</p>";
    if (client['agent_id'] != 0) {
	content += "<p>Washer Badge: #"+client['agent_badge_id']+"</p>";
	content += "<p>Washer Name: "+client['agent_name']+"</p>";
	content += "<p>Washer Phone: "+client['agent_phone']+"</p>";
    }
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";

    addlocation(client['id'], clientname, client['latitude'], client['longitude'], pin_image, 'schedorders', content, client['wash_request_id']);
  }
  else{
    updatelocation(client['id'], client['latitude'], client['longitude']);
  }

       pendingclientids.push(client['id']);
});

 id_exists = false;

$.each( clientdata.processing_orders, function( index, client ){
//console.log(index);
   id_exists = false;
 for(var i=0;i<markers.length;i++){
        if(markers[i].id == client['id']){
            //console.log(markers[i].category);
            if(markers[i].category == 'processorders'){
                //console.log(markers[i]);
                id_exists = true;
            }
        }
 }

  if(!id_exists){
      clientname = client['customername'];
    content = "<p><img src='"+client['image']+"' style='width: 70px; border-radius: 100px;' /></p>";
    content += "<p>Order ID: "+client['wash_request_id']+"</p>";
    content += "<p><b>"+clientname+"</b></p>";
    content += "<p>Phone Number: "+client['contact_number']+"</p>";
     content += "<p>Rating: "+client['rating']+"</p>";
    content += "<p>Total Washes: "+client['total_wash']+"</p>";
    content += "<p><a href='#' class='send-client-notify' data-id='"+client['id']+"'>Send Notification</a><a href='#' class='send-client-sms' data-id='"+client['id']+"' style='margin-left: 10px;'>Send SMS</a></p>";
    addlocation(client['id'], clientname, client['latitude'], client['longitude'], 'images/processing-order-pin.png', 'processorders', content, client['wash_request_id']);
  }
  else{
    updatelocation(client['id'], client['latitude'], client['longitude']);
  }

     processclientids.push(client['id']);
});

 removeclients(onlineclientids, "onlineclients");
 removeclients(pendingclientids, "pendingorders");
 removeclients(pendingclientids, "schedorders");
 removeclients(processclientids, "processorders");
//markerClusterer.addMarkers(markers);

}



jQuery(function($) {
    // Asynchronously Load the map API
    var script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?v=3.23&libraries=geometry&sensor=true&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y&callback=initialize";
    document.body.appendChild(script);
    //console.log(markers[0][0]);
});

function addlocation(markerid, name, lat, lng, icon, cat, content, extradata = ''){
var dragstatus = false;
if(cat == 'onlineagents') dragstatus = true;
 var position = new google.maps.LatLng(lat, lng);
  infoWindow = new google.maps.InfoWindow();
  bounds.extend(position);

    marker = new google.maps.Marker({
            position: position,
            map: map,
            title: name,
            icon: icon,
            category: cat,
            id: markerid,
extradata: extradata,
 optimized:false,
draggable: dragstatus
        });

        //marker.metadata = {id: markerid};
       if(cat == 'offlineagents') marker.setVisible(false);
        markers.push(marker);


          google.maps.event.addListener(marker, 'click', (function(marker) {
            return function() {
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
map.setZoom(18);
    map.setCenter(marker.getPosition());
            }
        })(marker));


        google.maps.event.addListener(marker, 'rightclick', function(event) {

     this.setMap(null);
        });
	

google.maps.event.addListener(marker, 'drag', function(event) {
//console.log('new position is '+event.latLng.lat()+' / '+event.latLng.lng());

});

google.maps.event.addListener(marker, 'dragend', function(event) {
//console.log(this);
closest_markers = [];
markercurrentpos = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
//console.log(markercurrentpos);
//console.log('final position is '+marker.latLng.lat()+' / '+marker.latLng.lng());

$.each(markers,function(index, mm){
    mm.distance=google.maps.geometry.spherical.computeDistanceBetween(mm.getPosition(), markercurrentpos);
if(mm.category == 'pendingorders') closest_markers.push(mm);
  });

closest_markers.sort(function(a, b) {
    return parseFloat(a.distance) - parseFloat(b.distance);
});

//console.log(closest_markers);
 infoWindow.setContent("<p style='font-size: 18px;'>Connect agent with order of <b>"+closest_markers[0].title+"</b>?</p><p style='text-align: center; background: #2196F3; color: #fff; display: block;'><a class='infowin-assign-order' data-agent-id='"+this.id+"' data-wash-id='"+closest_markers[0].extradata+"' style='color: #fff; text-decoration: none; font-size: 18px; font-weight: bold; display: block; padding: 10px; width: 100%; box-sizing: border-box;' href='#'>Assign Order</a></p>");
    infoWindow.open(map, closest_markers[0]);
});
}

function updatelocation(markerid, lat, lng){

 var position = new google.maps.LatLng(lat, lng);
  //bounds.extend(position);
    for(var i=0;i<markers.length;i++){
    if(markers[i].id === markerid){

        markers[i].setPosition(position);
        break;
    }
}


}

function removeclients(clientids, cat){
    //console.log(clientids);
    for(var i=0;i<markers.length;i++){
        if(($.inArray( markers[i].id, clientids )) ==-1){
            //console.log(markers[i].category);
            if(markers[i].category == cat){
                //console.log(markers[i]);
                markers[i].setMap(null);
                markers.splice(i, 1);
            }
        }
    }
}

function removeagents(agentids, cat){
    //console.log(agentids);
    for(var i=0;i<markers.length;i++){
        if(($.inArray( markers[i].id, agentids )) ==-1){
            //console.log(markers[i].category);
            if(markers[i].category == cat){
                //console.log(markers[i]);
                markers[i].setMap(null);
                markers.splice(i, 1);
            }
        }
    }
}

function initialize() {


     var myLatLng = {lat: 34.052234, lng: -118.243685};
    bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap',
         center: myLatLng,
zoomControl: true,
          zoomControlOptions: {
              position: google.maps.ControlPosition.LEFT_CENTER
          },
          scaleControl: true,
          streetViewControl: true,
          streetViewControlOptions: {
              position: google.maps.ControlPosition.LEFT_TOP
          },
 rotateControl: true,
          rotateControlOptions: {
              position: google.maps.ControlPosition.LEFT_TOP
          }
    };



    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);



    // Multiple Markers

     markerClusterer = new MarkerClusterer(map, markers, {
          maxZoom: 14,
          gridSize: 80
        });


    // Display multiple markers on a map


    // Loop through our array of markers & place each one on the map
    /*
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
            icon: icons[i]
        });

        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', (function(marker, i) {

            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);

            }
        })(marker, i));



        // Automatically center the map fitting all markers on the screen
       //map.fitBounds(bounds);
    }
    */
   map.fitBounds(bounds);

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(9);
        this.setCenter(myLatLng);
        google.maps.event.removeListener(boundsListener);
    });
    
    /*	google.maps.event.addDomListener(document, 'keydown', function (e) {

    var code = (e.keyCode ? e.keyCode : e.which);

   if (code == 16) {
    is_shiftpressed = 1;
   }
});

    	google.maps.event.addDomListener(document, 'keyup', function (e) {

    var code = (e.keyCode ? e.keyCode : e.which);

    is_shiftpressed = 0;

});
*/	
	   	google.maps.event.addDomListener(map, 'rightclick', function (e) {
   if (selectedzips) {
    var content = "<div class='zip-info'><p><b> SELECTED ZIPCODES: </b>"+selectedzips.replace(/,\s*$/, "")+"</p><p>Zip Color <select class='zip-color'><option value='gray'>Disabled</option><option value=''>Blue</option><option value='yellow'>Yellow</option><option value='red'>Red</option><option value='purple'>Purple</option></select></p><p><a href='#' class='save-groupzip-info' data-zips='"+selectedzips+"'>Save</a></p></div>";
  var infowindow = new google.maps.InfoWindow({
        content: content, position: e.latLng, maxWidth: 300

    });
    infowindow.open(this);
    
   }

});
		
google.maps.event.addDomListener(map, 'click', function (e) {
selectedzips = '';

 for (var i=0; i < ziparea_polys.length; i++)
{
if(ziparea_polys[i].zipcolor == 'yellow') ziparea_polys[i].setOptions({fillColor: "#f4d942", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'red') ziparea_polys[i].setOptions({fillColor: "#ff5722", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'purple') ziparea_polys[i].setOptions({fillColor: "#800080", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'gray') ziparea_polys[i].setOptions({fillColor: "#808080", fillOpacity: 0.6, strokeOpacity: 0.8});
else ziparea_polys[i].setOptions({fillColor: "#076ee1", fillOpacity: 0.6, strokeOpacity: 0.8});
}

});

  themap = map;  
     // Start drag rectangle to select markers !!!!!!!!!!!!!!!!
   

    $(window).keydown(function (evt) {
        if (evt.which === 16) { // shift
            shiftPressed = true;
           
        }
    }).keyup(function (evt) {
        if (evt.which === 16) { // shift
            shiftPressed = false;
           
        }
    });


<?php if($jsondata_permission->users_type == 'admin'): ?>
    google.maps.event.addListener(themap, 'mousemove', function (e) {      
        if (mouseIsDown && (shiftPressed|| gribBoundingBox != null) ) {
            if (gribBoundingBox !== null) // box exists
            {         
                var newbounds = new google.maps.LatLngBounds(mouseDownPos,null);
                newbounds.extend(e.latLng);    
                gribBoundingBox.setBounds(newbounds); // If this statement is enabled, I lose mouseUp events

            } else // create bounding box
            {
                 
                gribBoundingBox = new google.maps.Rectangle({
                    map: themap,
                    bounds: null,
                    fillOpacity: 0.15,
                    strokeWeight: 0.9,
                    clickable: false
                });
            }
        }
    });

    google.maps.event.addListener(themap, 'mousedown', function (e) {
        if (shiftPressed) {
            mouseIsDown = 1;
            mouseDownPos = e.latLng;
            themap.setOptions({
                draggable: false
            });
        }
    });

    google.maps.event.addListener(themap, 'mouseup', function (e) {
         var pointsInside = 0;
	 var contentString = '';
    var pointsOutside = 0;
	if (mouseIsDown && (shiftPressed|| gribBoundingBox != null)) {
            mouseIsDown = 0;
            if (gribBoundingBox !== null) // box exists
            {
                var boundsSelectionArea = new google.maps.LatLngBounds(gribBoundingBox.getBounds().getSouthWest(), gribBoundingBox.getBounds().getNorthEast());
		     for (var i=0; i < ziparea_polys.length; i++)
		    {
			
			var pointsInside = 0;
			var pointsOutside = 0;
			var vertices = ziparea_polys[i].getPath();
			for (var j =0; j < vertices.getLength(); j++) {
			    var xy = vertices.getAt(j);
			    polyLatLng = new google.maps.LatLng({lat: xy.lat(), lng: xy.lng()});
			    (gribBoundingBox.getBounds().contains(polyLatLng)) ? pointsInside++ : pointsOutside++;
			    
				if (pointsInside > pointsOutside) break;
			   
			}
			
			if (pointsInside > pointsOutside)
			{
			    ziparea_polys[i].setOptions({fillColor: '#008000', fillOpacity: 0.4});
			    selectedzips += ziparea_polys[i].zipcode+",";
			
			}
			
			
		    }

                gribBoundingBox.setMap(null); // remove the rectangle
            }
            gribBoundingBox = null;

        }

        themap.setOptions({
            draggable: true
        });
        //stopDraw(e);
    });
<?php endif; ?>	


/*
        layer = new google.maps.FusionTablesLayer({
          query: {
            select: 'geometry',
            from: '1KGA8BqTBlI6Rvv1sQACLdhDHa-7DmvoizGUCcbQr'

          },
styles: [{
            polygonOptions: {
              fillColor: '#FFFFFF',
              fillOpacity: 0.2,

          strokeOpacity: 0.8,
          strokeWeight: 0,
strokeColor: "#076ee1",
            }
          }
          ]
        });


  layer.setMap(map);
*/

 // Initialize JSONP request
        var script = document.createElement('script');
        var url = ['https://www.googleapis.com/fusiontables/v1/query?'];
        url.push('sql=');
        var query = "SELECT * FROM " + "1ECb-guhoNwEE3leCYpBbdRcpXVhTHcbraYX9yt54 WHERE MW_COVERAGE_AREA = 'true'";
        var encodedQuery = encodeURIComponent(query);
        url.push(encodedQuery);
        url.push('&callback=drawMap');
        url.push('&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y');
        script.src = url.join('');
        var body = document.getElementsByTagName('body')[0];
        body.appendChild(script);

}




</script>
<script>
var fusiondata = '';

function greeting(){
  var audio = document.getElementById("audio1");
var audio2 = document.getElementById("audio2");
//audio1.play();
//audio2.pause();
audio.addEventListener("ended", function () {
 //audio2.play();
});
}
//setTimeout(greeting, 5000);

function finishpendingaudio(elt){
    setTimeout(function(){$(elt).removeClass('inplay'); }, 180000);
}

  $(function(){
       $(".mw-menu").click(function(){
if($('.submenu').is(":visible")) {
$('.submenu').hide();
$('.menu-container').css('right', '10px');
}
        $(".menu-container").slideToggle();

         return false;
       });

 $(".menu-container .tab-content").on('click', '.subarrow', function(){
if($(this).hasClass('active')){
$(this).removeClass('active');

$(".menu-container .tab-content ul li .submenu").hide();
$('.menu-container').css('right', '10px');

}
else{
$(".menu-container .tab-content ul li .subarrow").removeClass('active');
$(this).addClass('active');
$(".menu-container .tab-content ul li .submenu").hide();
 $( this ).next().fadeIn();
$('.menu-container').css('right', '320px');
}

         return false;
       });

        $(".menu-container .tab-content, .menu-container .search-cc").on('click', 'ul li a', function(){
        mid = $(this).parent().attr('id');
        $.each(markers, function( index, mark ) {
            //console.log(mark.id);
  if(mark.id == mid) {
     map.setZoom(18);
    map.setCenter(mark.getPosition());
    infoWindow.setContent(mark.title);
    infoWindow.open(map, mark);
    return false;
  }
});
         return false;
       });


$("#container").on('click', '.infowin-assign-order', function(){
var wash_id = $(this).attr('data-wash-id');
var agent_id = $(this).attr('data-agent-id');
//console.log(agent_id);
var th = $(this);
//alert(wash_id+" "+agent_id);
$(this).html('Assigning...');

$.post( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminorderassign", { wash_request_id: wash_id, agent_id: agent_id, admin_username: "<?php echo $jsondata_permission->user_name; ?>", key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
  $(th).html('Assigned');
$("#container .note-message").fadeIn();
$("#container .note-message").html('Order assigned successfully');
//var assign_audio = document.getElementById("order-assign-audio");
//assign_audio.play();
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
});

return false;
  });


$(".menu-container .tab-content").on('click', 'ul li .assign-agent', function(){
var wash_id = $(this).attr('data-wash-id');
var agent_id = $(this).attr('data-agent-id');
var th = $(this);
//alert(wash_id+" "+agent_id);
$(this).html('Assigning...');

$.post( "<?php echo ROOT_URL; ?>/api/index.php?r=users/adminorderassign", { wash_request_id: wash_id, agent_id: agent_id, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
  $(th).html('Assigned');
$("#container .note-message").fadeIn();
$("#container .note-message").html('Order assigned successfully');
//var assign_audio = document.getElementById("order-assign-audio");
//assign_audio.play();
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
});
  });


$(".menu-container .search-cc").on('keyup', '#cc-search-text', function(){
var search_q = $("#cc-search-text").val();
if(search_q){
$.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/searchagentsclients", { search_query: search_q, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
if(data.result == 'true'){
$(".menu-container .search-cc .search-autocomplete-box").show();
search_data = '';
search_data += "<ul>";

if(data.agents.length){
$.each(data.agents, function( ind, agt ) {
if(agt['pending_orders'] > 1 && agt['status'] == 'online'){
    search_data += "<li class='agent-pending' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";   
}else if(agt['processing_orders'] > 1 && agt['status'] == 'online'){
    search_data += "<li class='agent-progress' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";   
}else if(agt['available_for_new_order'] == 0 && agt['status'] == 'online'){
    search_data += "<li class='agent-busy' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";
}else if(agt['available_for_new_order'] == 1 && agt['status'] == 'online'){
    search_data += "<li class='agent' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";
}else if(agt['status'] == 'offline' && agt['block_washer'] == 0){
    search_data += "<li class='agent-offline' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";    
}else{
    search_data += "<li class='agent' id='"+agt['id']+"'><a href='#'>"+agt['name']+"</a></li>";    
}

});
}

if(data.clients.length){
$.each(data.clients, function( ind2, clt ) {
    if(clt['status'] == 'offline'){
        search_data += "<li class='agent-offline' id='"+clt['id']+"'><a href='#'>"+clt['name']+"</a></li>";
    }else if(clt['status'] == 'online' && clt['processing_orders'] > 0){
        search_data += "<li class='client-progress' id='"+clt['id']+"'><a href='#'>"+clt['name']+"</a></li>";
    }else{
        search_data += "<li class='client' id='"+clt['id']+"'><a href='#'>"+clt['name']+"</a></li>";
    }

});
}

search_data += "</ul>";

$(".menu-container .search-cc .search-autocomplete-box").html(search_data);

}
else{
$(".menu-container .search-cc .search-autocomplete-box").html('');
$(".menu-container .search-cc .search-autocomplete-box").hide();
}

});
}
else{
 $(".menu-container .search-cc .search-autocomplete-box").html('');
$(".menu-container .search-cc .search-autocomplete-box").hide();
}
});

$(".ziparea-toggle").click(function(){

if($(this).html() == "Zipcode Area: On"){
$(this).html("Zipcode Area: Off");
hideziparea();
return false;
}

if($(this).html() == "Zipcode Area: Off"){
$(this).html("Zipcode Area: On");
showziparea(fusiondata);

return false;
}


return false;

});

$('body').on('click', '.send-client-notify', function(){
    var cust_id = $(this).data('id');
  var notifymsg = window.prompt("Enter notification message", "");

  if (notifymsg != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Sending...');

    $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/cccustomerpushnotify", { customer_id: cust_id, message: notifymsg, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
       $("#container .note-message").html(data.response);
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});

$('body').on('click', '.send-agent-notify', function(){
    var agent_id = $(this).data('id');
  var notifymsg = window.prompt("Enter notification message", "");

  if (notifymsg != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Sending...');

    $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/cccagentpushnotify", { agent_id: agent_id, message: notifymsg, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
       $("#container .note-message").html(data.response);
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});

$('body').on('click', '.send-client-sms', function(){
    var cust_id = $(this).data('id');
  var notifymsg = window.prompt("Enter SMS", "");

  if (notifymsg != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Sending...');

    $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/cccustomersendsms", { customer_id: cust_id, message: notifymsg, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
       $("#container .note-message").html(data.response);
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});

$('body').on('click', '.send-agent-sms', function(){
    var agent_id = $(this).data('id');
  var notifymsg = window.prompt("Enter SMS", "");

  if (notifymsg != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Sending...');

    $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=site/ccagentsendsms", { agent_id: agent_id, message: notifymsg, key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>" }, function( data ) {
       $("#container .note-message").html(data.response);
setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});

$('body').on('click', '.save-zip-info', function(){
    var zip = $(this).data('zip');
    var zipcolor = $(this).parent().parent().find('.zip-color').val();

  if (zip != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Saving...');

    $.getJSON( "ajax.php", { action: 'savezip', zipcode: zip, zipcolor: zipcolor  }, function( data ) {
       $("#container .note-message").html(data.response);
if(data.result == 'false') setTimeout(function(){$("#container .note-message").fadeOut();}, 30000);
else setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});

$('body').on('click', '.save-groupzip-info', function(){
    var zip = $(this).data('zips');
    var zipcolor = $(this).parent().parent().find('.zip-color').val();

  if (zip != null) {
      $("#container .note-message").fadeIn();
$("#container .note-message").html('Saving...');

    $.getJSON( "ajax.php", { action: 'savegroupzips', zipcode: zip, zipcolor: zipcolor  }, function( data ) {
       $("#container .note-message").html(data.response);
if(data.result == 'false') setTimeout(function(){$("#container .note-message").fadeOut();}, 30000);
else setTimeout(function(){$("#container .note-message").fadeOut();}, 3000);
    });
  }
return false;
});


});


function drawMap(data) {
    
    //var myParser = new geoXML3.parser({map: map});

         //console.log(data);
        var rows = data['rows'];
fusiondata = data['rows'];
        for (var i in rows) {

            var newCoordinates = [];

              newCoordinates = constructNewCoordinates(rows[i][10]['geometry']);
              
              	      var areacolor = "#076ee1";
		     
		      
if (rows[i][12] == 'yellow') {
    areacolor = "#f4d942";

}

if (rows[i][12] == 'red') {
    areacolor = "#ff5722";

}

if (rows[i][12] == 'purple') {
    areacolor = "#800080";

}

if (rows[i][12] == 'gray') {
    areacolor = "#808080";

}

            var randomnumber = Math.floor(Math.random() * 4);
            var country = new google.maps.Polygon({
              paths: newCoordinates,
              strokeColor: areacolor,
              strokeOpacity: 0.8,
              strokeWeight: 0,
              fillColor: areacolor,
              fillOpacity: 0.6,
              zipcode: rows[i][4],
	      zipcolor: rows[i][12]
            });

ziparea_polys.push(country);

<?php if($jsondata_permission->users_type == 'admin'): ?>
 google.maps.event.addListener(country, 'mousemove', function (e) {
              
        if (mouseIsDown && (shiftPressed|| gribBoundingBox != null) ) {
            if (gribBoundingBox !== null) // box exists
            {         
                var newbounds = new google.maps.LatLngBounds(mouseDownPos,null);
                newbounds.extend(e.latLng);    
                gribBoundingBox.setBounds(newbounds); // If this statement is enabled, I lose mouseUp events

            } else // create bounding box
            {
                 
                gribBoundingBox = new google.maps.Rectangle({
                    map: themap,
                    bounds: null,
                    fillOpacity: 0.15,
                    strokeWeight: 0.9,
                    clickable: false
                });
            }
	    
        }
    });

    google.maps.event.addListener(country, 'mousedown', function (e) {
        if (shiftPressed) {
            mouseIsDown = 1;
            mouseDownPos = e.latLng;
            themap.setOptions({
                draggable: false
            });
        }
    });

    google.maps.event.addListener(country, 'mouseup', function (e) {
         var pointsInside = 0;
	 var contentString = '';
    var pointsOutside = 0;
	if (mouseIsDown && (shiftPressed|| gribBoundingBox != null)) {
            mouseIsDown = 0;
            if (gribBoundingBox !== null) // box exists
            {
                var boundsSelectionArea = new google.maps.LatLngBounds(gribBoundingBox.getBounds().getSouthWest(), gribBoundingBox.getBounds().getNorthEast());
		     for (var i=0; i < ziparea_polys.length; i++)
		    {
			
			var pointsInside = 0;
			var pointsOutside = 0;
			var vertices = ziparea_polys[i].getPath();
			for (var j =0; j < vertices.getLength(); j++) {
			    var xy = vertices.getAt(j);
			    polyLatLng = new google.maps.LatLng({lat: xy.lat(), lng: xy.lng()});
			    (gribBoundingBox.getBounds().contains(polyLatLng)) ? pointsInside++ : pointsOutside++;
			    
				if (pointsInside > pointsOutside) break;
			   
			}
			
			if (pointsInside > pointsOutside)
			{
			    ziparea_polys[i].setOptions({fillColor: '#008000', fillOpacity: 0.4});
			    selectedzips += ziparea_polys[i].zipcode+",";
			
			}
			
			
		    }

                gribBoundingBox.setMap(null); // remove the rectangle
            }
            gribBoundingBox = null;

        }

        themap.setOptions({
            draggable: true
        });
        //stopDraw(e);
    });
<?php endif; ?>
            google.maps.event.addListener(country, 'mouseover', function() {
		//console.log(this);
		
               if((!shiftPressed) && (!selectedzips)) this.setOptions({fillColor: '#076ee1', fillOpacity: 0.4});
            });
            google.maps.event.addListener(country, 'mouseout', function() {
		if((!shiftPressed) && (!selectedzips)){
		    if(this.zipcolor == 'yellow') this.setOptions({fillColor: "#f4d942", fillOpacity: 0.6, strokeOpacity: 0.8});
		    else if(this.zipcolor == 'red') this.setOptions({fillColor: "#ff5722", fillOpacity: 0.6, strokeOpacity: 0.8});
		    else if(this.zipcolor == 'purple') this.setOptions({fillColor: "#800080", fillOpacity: 0.6, strokeOpacity: 0.8});
		    else if(this.zipcolor == 'gray') this.setOptions({fillColor: "#808080", fillOpacity: 0.6, strokeOpacity: 0.8});
		    else this.setOptions({fillColor: "#076ee1", fillOpacity: 0.6, strokeOpacity: 0.8});
		}
            });

	<?php if($jsondata_permission->users_type == 'admin'): ?>    
	  google.maps.event.addDomListener(country, 'rightclick', function (e) {
   if (selectedzips) {
    var content = "<div class='zip-info'><p><b> SELECTED ZIPCODES: </b>"+selectedzips.replace(/,\s*$/, "")+"</p><p>Zip Color <select class='zip-color'><option value='gray'>Disabled</option><option value=''>Blue</option><option value='yellow'>Yellow</option><option value='red'>Red</option><option value='purple'>Purple</option></select></p><p><a href='#' class='save-groupzip-info' data-zips='"+selectedzips+"'>Save</a></p></div>";
  var infowindow = new google.maps.InfoWindow({
        content: content, position: e.latLng, maxWidth: 300

    });
    infowindow.open(map, this);
    
   }

});
<?php endif; ?>	    


            google.maps.event.addListener(country, 'click', function(e)
{

	selectedzips = '';
	
	 for (var i=0; i < ziparea_polys.length; i++)
{
if(ziparea_polys[i].zipcolor == 'yellow') ziparea_polys[i].setOptions({fillColor: "#f4d942", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'red') ziparea_polys[i].setOptions({fillColor: "#ff5722", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'purple') ziparea_polys[i].setOptions({fillColor: "#800080", fillOpacity: 0.6, strokeOpacity: 0.8});
else if(ziparea_polys[i].zipcolor == 'gray') ziparea_polys[i].setOptions({fillColor: "#808080", fillOpacity: 0.6, strokeOpacity: 0.8});
else ziparea_polys[i].setOptions({fillColor: "#076ee1", fillOpacity: 0.6, strokeOpacity: 0.8});
}

   // console.log(country);
   //this.setOptions({fillColor: '#076ee1', fillOpacity: 0.4});
  		      var blue_selected = "selected='selected'";
		      var yellow_selected = '';
		      var red_selected = '';
		      var purple_selected = '';
		      var gray_selected = '';
		      
if (this.zipcolor == 'yellow') {
    yellow_selected = "selected='selected'";
    blue_selected = '';
    red_selected = '';
    purple_selected = '';
    gray_selected = '';
}

if (this.zipcolor == 'red') {
    yellow_selected = '';
    blue_selected = '';
    red_selected = "selected='selected'";
    purple_selected = '';
    gray_selected = '';
}

if (this.zipcolor == 'purple') {
    yellow_selected = '';
    blue_selected = '';
    red_selected = '';
    purple_selected = "selected='selected'";
    gray_selected = '';
}

if (this.zipcolor == 'gray') {
    yellow_selected = '';
    blue_selected = '';
    red_selected = '';
    purple_selected = '';
    gray_selected = "selected='selected'";
}
   
   <?php if($jsondata_permission->users_type == 'scheduler'): ?>
   var content = "<div class='zip-info'><p><b>ZIPCODE: </b>"+this.zipcode+"</p><p>Zip Color: "+this.zipcolor+"</p></div>";
  
   <?php else: ?>
   var content = "<div class='zip-info'><p><b>ZIPCODE: </b>"+this.zipcode+"</p><p>Zip Color <select class='zip-color'><option value='gray' "+gray_selected+">Disabled</option><option value='' "+blue_selected+">Blue</option><option value='yellow' "+yellow_selected+">Yellow</option><option value='red' "+red_selected+">Red</option><option value='purple' "+purple_selected+">Purple</option></select></p><p><a href='#' class='save-zip-info' data-zip='"+this.zipcode+"'>Save</a></p></div>";
  
   <?php endif; ?>
    
  var infowindow = new google.maps.InfoWindow({
        content: content, position: e.latLng

    });
    infowindow.open(map, this);
});


            country.setMap(map);

        }
      }

       function constructNewCoordinates(polygon) {
          
        var newCoordinates = [];
        var coordinates = polygon['coordinates'][0];
        for (var i in coordinates) {
          newCoordinates.push(
              new google.maps.LatLng(coordinates[i][1], coordinates[i][0]));
        }
        return newCoordinates;
      }


function showziparea(data) {

        for (var i in data) {

            var newCoordinates = [];

              newCoordinates = constructNewCoordinates(data[i][10]['geometry']);
              
              var areacolor = "#076ee1";
if (data[i][12] == 'yellow') {
    areacolor = "#f4d942";
}

if (data[i][12] == 'red') {
    areacolor = "#ff5722";
}

if (data[i][12] == 'purple') {
    areacolor = "#800080";
}

if (data[i][12] == 'gray') {
    areacolor = "#808080";
}

            var randomnumber = Math.floor(Math.random() * 4);
            var country = new google.maps.Polygon({
              paths: newCoordinates,
              strokeColor: areacolor,
              strokeOpacity: 0.8,
              strokeWeight: 0,
              fillColor: areacolor,
              fillOpacity: 0.6,
              zipcode: data[i][4],
	      zipcolor: data[i][12]
            });

ziparea_polys.push(country);

            country.setMap(map);

        }
      }


function hideziparea() {

      for (var i=0; i < ziparea_polys.length; i++)
  {
    ziparea_polys[i].setMap(null);
  }
  ziparea_polys = [];
      }


</script>