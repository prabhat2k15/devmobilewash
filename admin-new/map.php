<?php include('header.php') ?>
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
</style>
<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="index.html">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Dashboard</span>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <div class="pull-right tooltips btn btn-sm">
                                <i class="icon-calendar"></i>&nbsp;
                                <span id="servertime" style="font-weight: 300 !important;"></span>&nbsp;
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Dashboard
                        <small>dashboard & statistics</small>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa"><img src="images/client-online-pin.png"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span class="clientonline" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Clients Online </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa"><img src="images/pending-order-pin.png"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span class="pendingorder" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Pending Orders </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat green">
                                <div class="visual">
                                    <i class="fa"><img src="images/processing-order-pin.png"></i>
                                </div>
                                <div class="details">
                                <div class="number">
                                        <span class="orderprogress" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Orders in Progress </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat purple">
                                <div class="visual">
                                    <i class="fa"><img src="images/online-agent-pin.png"></i>
                                </div>
                                <div class="details">
                                <div class="number">
                                        <span class="agentonline" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Agents Online </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa"><img src="images/busy-agent-pin.png"></i>
                                </div>
                                <div class="details">
                                <div class="number">
                                        <span class="busyagents" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Busy Agents </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat green">
                                <div class="visual">
                                    <i class="fa"><img src="images/offline-agent-pin.png"></i>
                                </div>
                                <div class="details">
                                <div class="number">
                                        <span class="offlineagents" style="color: gray !important;">0</span>
                                    </div>
                                    <div class="desc" style="color: gray ! important; font-weight: bold;"> Offline Agents </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>                    
                    <div class="clearfix"></div>
                    <!-- END DASHBOARD STATS 1-->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN BASIC PORTLET-->
                            <div class="portlet light portlet-fit bordered">
                                                                <div class="portlet-body">
                                    <div id="gmap_basic" class="gmaps" style="height: 590px;"> </div>
                                </div>
                            </div>
                            <!-- END BASIC PORTLET-->
                        </div>
                        
                       
                    </div>                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php include('footer.php') ?>
<script src="https://maps.googleapis.com/maps/api/js" type="text/javascript"></script>
 <script type="text/javascript">
 //Sample code written by August Li
 var icon = new google.maps.MarkerImage("images/client-online-pin.png",
 new google.maps.Size(44, 68), new google.maps.Point(0, 0),
 new google.maps.Point(16, 44));
 var center = null;
 var map = null;
 var currentPopup;
 var bounds = new google.maps.LatLngBounds();
 function addMarker(lat, lng, info, icon) {
 var pt = new google.maps.LatLng(lat, lng);
 bounds.extend(pt);
 var marker = new google.maps.Marker({
 position: pt,
 icon: icon,
 map: map
 });
 var popup = new google.maps.InfoWindow({
 content: info,
 maxWidth: 500
 });
 google.maps.event.addListener(marker, "click", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 }
 popup.open(map, marker);
 currentPopup = popup;
 });
 google.maps.event.addListener(popup, "closeclick", function() {
 map.panTo(center);
 currentPopup = null;
 });
 }
 
</script>
<script>

var interval    =   0;
function start(){
     map = new google.maps.Map(document.getElementById("gmap_basic"), {
 center: {lat: 33.7669444, lng: -118.1883333},
 zoom: 8,
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 mapTypeControl: false,
 mapTypeControlOptions: {
 style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
 },
 navigationControl: true,
 navigationControlOptions: {
 style: google.maps.NavigationControlStyle.SMALL
 }
 });
    setTimeout( function(){
        ajax_function();
        interval    =   30;
        setInterval( function(){
            ajax_function();
        }, interval * 1000);
    }, interval * 1000);    
}

function ajax_function(){
   
  
   /*Onlin customer*/
    $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=customers/clientsbystatus", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
        
        
        $.each(data, function(k, v){
    $.each(v, function(key, value){
        //alert(key+'ssssssss'+value.customername);
        var latitude = value.latitude;
        var longitude = value.longitude;
        var customername = value.customername;
        var contact_number = 'Phone Number : '+value.contact_number;
        var rating = 'Rating : '+value.rating;
        var total_wash = 'Total Wash : '+value.total_wash;
        var image = value.image;
        var icon = new google.maps.MarkerImage("images/client-online-pin.png",
 new google.maps.Size(44, 68), new google.maps.Point(0, 0),
 new google.maps.Point(16, 44));
        addMarker(latitude, longitude,'<b>'+customername+'<br/> '+ contact_number +'<br/>'+rating+'<br/>'+total_wash, icon);
        
    });
        });
    center = bounds.getCenter();
       // map.fitBounds(bounds);
        
        });
        
    /*Online agents*/  
    
    $.getJSON("<?php echo ROOT_URL; ?>/api/index.php?r=agents/onlineagents", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
        
        
        $.each(data.agents, function(k, v){
            
             var latitude = v.latitude;
        var longitude = v.longitude;
        var firstname = v.first_name;
        var lastname = v.last_name;
        var email = v.email;
        var total_washes = 'Total Wash : '+v.total_washes;
        
        var icon = new google.maps.MarkerImage("images/online-agent-pin.png",
 new google.maps.Size(44, 68), new google.maps.Point(0, 0),
 new google.maps.Point(16, 44));
        addMarker(latitude, longitude,'<br/>'+email+'<br/>'+total_washes, icon);
    
        });
    center = bounds.getCenter();
       // map.fitBounds(bounds);
        
        });   
        
        
        
        /*$.getJSON("http://www.mobilewash.com/dev/api/index.php?r=washing/pendingwashrequests", {key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
        
        
        $.each(data.pending_wash_requests, function(k, v){
            
             var latitude = v.latitude;
        var longitude = v.longitude;
        var customer_rating = v.customer_rating;
        var customer_name = v.customer_name;
        var icon = new google.maps.MarkerImage("images/pending-order-pin.png",
 new google.maps.Size(44, 68), new google.maps.Point(0, 0),
 new google.maps.Point(16, 44));
        addMarker(latitude, longitude,'<br/>'+customer_name+'<br/>'+customer_name, icon);
    
        });
    center = bounds.getCenter();
       // map.fitBounds(bounds);
        
        });   */
        
     

}



$( window ).load(function(){
    var time    =   new Date();
    interval    =   30 - time.getSeconds();
    if(interval==30)
        interval    =   0;
    start();
});
</script>