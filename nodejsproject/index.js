var app = require('express')();
//var https = require('https').Server(app);
var https  = require('https');
//var io = require('socket.io')(https);
var request = require('request');
var fs = require( 'fs' );
//var braintree = require("braintree");

/*app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});*/

var getappstattimer;
var getpendingwashesdetailstimer;
var getagentsbystatustimer;
var getclientsbystatustimer;
var getnewwashrequesttimer;
var connectedusers = [];

function getAppstat(socket_id='', key = '', api_token='', t1='', t2='', user_type='', user_id='') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=users/Appstat',
  body:    "key="+key+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 // console.log(JSON.parse(body));
 //console.log('stat func');
 try{
    if(socket_id) io.sockets.connected[socket_id].emit('get appstat', JSON.parse(body));
    else io.emit('get appstat', JSON.parse(body));
    
 }
 catch(err){
     
 }
            
});
//getappstattimer = setTimeout(getAppstat, 5000);
}

function getpendingwashesdetails(socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/pendingwashesdetails',
  body:    "key="+key+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 // console.log(JSON.parse(body));
 //console.log('pendingwash func');
 try{
    if(socket_id) io.sockets.connected[socket_id].emit('get pendingwashesdetails', JSON.parse(body));
    else io.emit('get pendingwashesdetails', JSON.parse(body));
    
 }
 catch(err){
     
 }
 
});
//getpendingwashesdetailstimer = setTimeout(getpendingwashesdetails, 5000);
}

function washing_currentwashondemandalert(wash_request_id='', key = '', socket_id = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/currentwashondemandalert',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
  /*console.log('ondemand alert'+" "+wash_request_id+" apikey "+key);
   console.log("body "+body);
  console.log("res "+response);
  console.log("err "+error);*/
 //console.log('pendingwash func');
//io.emit('get pendingwashesdetails', JSON.parse(body));
});
}

function washing_currentwashschedulealert(wash_request_id='', key = '', socket_id = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/currentwashschedulealert',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
/*console.log('schedule alert'+" "+wash_request_id+" apikey "+key);
  console.log("body "+body);
  console.log("res "+response);
  console.log("err "+error);*/
 //console.log(JSON.parse(body));

 //console.log('pendingwash func');
//io.emit('get pendingwashesdetails', JSON.parse(body));
});
}

function customers_nonreturn20daynotify(agent_id='', agent_lat = '', agent_lng = '', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/nonreturn20daynotify',
  body:    "key="+key+"&agent_id="+agent_id+"&agent_lat="+agent_lat+"&agent_lng="+agent_lng+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('pendingwash func');
//io.emit('get pendingwashesdetails', JSON.parse(body));
});
}

function washing_washingkart(wash_request_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/washingkart',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('pendingwash func');
 try{
  if(socket_id) io.sockets.connected[socket_id].emit('washing_washingkart_'+wash_request_id, JSON.parse(body));
  else io.emit('washing_washingkart_'+wash_request_id, JSON.parse(body));
 }
 catch(err){
    
 }

});
}

function washing_checkwashrequeststatus(wash_request_id='', customer_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/checkwashrequeststatus',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&customer_id="+customer_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('pendingwash func');
 try{
   if(socket_id) io.sockets.connected[socket_id].emit('washing_checkwashrequeststatus_'+wash_request_id, JSON.parse(body));
  else io.emit('washing_checkwashrequeststatus_'+wash_request_id, JSON.parse(body));
 }
 catch(err){
     
 }

});
}

function getagentsbystatus(socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=agents/agentsbystatus',
  body:    "key="+key+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 // console.log(JSON.parse(body));
 //console.log('agentsbystatus func');
 try{
    if(socket_id) io.sockets.connected[socket_id].emit('get agentsbystatus', JSON.parse(body));
  else io.emit('get agentsbystatus', JSON.parse(body));
 }
 catch(err){
     
 }
            
});
//getagentsbystatustimer = setTimeout(getagentsbystatus, 5000);
}

function getclientsbystatus(socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/clientsbystatus',
  body:    "key="+key+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 // console.log(JSON.parse(body));
 //console.log('clientsbystatus func');
 try{
   if(socket_id) io.sockets.connected[socket_id].emit('get clientsbystatus', JSON.parse(body));
  else io.emit('get clientsbystatus', JSON.parse(body));
 }
 catch(err){
     
 }
            
});
//getclientsbystatustimer = setTimeout(getclientsbystatus, 5000);
}

function washing_getnewwashrequest(agent_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/getnewwashrequest',
  body:    "key="+key+"&agent_id="+agent_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('washing_getnewwashrequest func');
            try
       {
         
         if(socket_id) io.sockets.connected[socket_id].emit('washing_getnewwashrequest_'+agent_id, JSON.parse(body));
  else io.emit('washing_getnewwashrequest_'+agent_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function washing_getallschedulewashes(agent_id='', washer_position = '', agent_latitude = '', agent_longitude = '', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/getallschedulewashes',
  body:    "key="+key+"&agent_id="+agent_id+"&washer_position="+washer_position+"&agent_latitude="+agent_latitude+"&agent_longitude="+agent_longitude+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('washing_getnewwashrequest func');
            try
       {
         
         if(socket_id) io.sockets.connected[socket_id].emit('washing_getallschedulewashes_'+agent_id, JSON.parse(body));
  else io.emit('washing_getallschedulewashes_'+agent_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function site_updatedevicestatus(user_type='', user_id='', device_token='', socket_id = '', key = '', api_token='', t1='', t2='', user_type_security='', user_id_security='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=site/updatedevicestatus',
  body:    "key="+key+"&user_type="+user_type+"&user_id="+user_id+"&device_token="+device_token+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type_security="+user_type_security+"&user_id_security="+user_id_security
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
 try
 {
         if(socket_id) io.sockets.connected[socket_id].emit('site_updatedevicestatus_'+user_type+'_'+user_id, JSON.parse(body));
         else io.emit('site_updatedevicestatus_'+user_type+'_'+user_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
           
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function users_checkuserforcelogout(user_type='', user_id='', socket_id = '', key = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=users/checkuserforcelogout',
  body:    "key="+key+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
 try
 {
         if(socket_id) io.sockets.connected[socket_id].emit('users_checkuserforcelogout_'+user_type+'_'+user_id, JSON.parse(body));
         else io.emit('users_checkuserforcelogout_'+user_type+'_'+user_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
           
});

}

function agents_updateagentlocations(agent_id='', latitude=0, longitude=0, socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=agents/updateagentlocations',
  body:    "key="+key+"&agent_id="+agent_id+"&latitude="+latitude+"&longitude="+longitude+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
           
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function customers_updatecustomerlocations(customer_id='', latitude=0, longitude=0, socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/updatecustomerlocations',
  body:    "key="+key+"&customer_id="+customer_id+"&latitude="+latitude+"&longitude="+longitude+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
           
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function customers_getclienttoken(customer_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/getClientToken',
  body:    "key="+key+"&customer_id="+customer_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
            try
       {
         if(socket_id) io.sockets.connected[socket_id].emit('customers_getclienttoken_'+customer_id, JSON.parse(body));
         else io.emit('customers_getclienttoken_'+customer_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});

}

function customers_addcustomerpaymentmethod(customer_id='', nonce = '', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/addcustomerpaymentmethod',
  body:    "key="+key+"&customer_id="+customer_id+"&nonce="+nonce+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
            try
       {
         if(socket_id) io.sockets.connected[socket_id].emit('customers_addcustomerpaymentmethod_'+customer_id, JSON.parse(body));
         else io.emit('customers_addcustomerpaymentmethod_'+customer_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});

}

function customers_getcustomerpaymentmethods(customer_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/getcustomerpaymentmethods',
  body:    "key="+key+"&customer_id="+customer_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
            try
       {
         if(socket_id) io.sockets.connected[socket_id].emit('customers_getcustomerpaymentmethods_'+customer_id, JSON.parse(body));
         else io.emit('customers_getcustomerpaymentmethods_'+customer_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});

}

function washing_getwash30secondtimer(wash_request_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/wash30secondtimer',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
  //console.log("inside 30 timer"+JSON.parse(body));
            try
       {
         
         if(socket_id) io.sockets.connected[socket_id].emit('washing_wash30secondtimer_'+wash_request_id, JSON.parse(body));
  else io.emit('washing_wash30secondtimer_'+wash_request_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function customers_getcustomerdevice(customer_id='', device_token = '', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=customers/getcustomerdevice',
  body:    "key="+key+"&customer_id="+customer_id+"&device_token="+device_token+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
  //console.log("inside 30 timer"+JSON.parse(body));
            try
       {
         
         if(socket_id) io.sockets.connected[socket_id].emit('customers_getcustomerdevice_'+customer_id, JSON.parse(body));
  else io.emit('customers_getcustomerdevice_'+customer_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});

}

function agents_getagentdevice(agent_id='', device_token = '', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=agents/getagentdevice',
  body:    "key="+key+"&agent_id="+agent_id+"&device_token="+device_token+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
  //console.log("inside 30 timer"+JSON.parse(body));
            try
       {
         
         if(socket_id) io.sockets.connected[socket_id].emit('agents_getagentdevice_'+agent_id, JSON.parse(body));
  else io.emit('agents_getagentdevice_'+agent_id, JSON.parse(body));
       }
       catch(err)
       {

       }     
            
});

}


function washing_removeduplicatewashnowstartlog(wash_request_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/removeduplicatewashnowstartlog',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
  //console.log("inside 30 timer"+JSON.parse(body));
     
            
});

}

function washing_sendorderreceipts(wash_request_id='', customer_id='', agent_id='', socket_id = '', key = '', api_token='', t1='', t2='', user_type='', user_id='') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'https://www.devmobilewash.com/api/index.php?r=washing/sendorderreceipts',
  body:    "key="+key+"&wash_request_id="+wash_request_id+"&customer_id="+customer_id+"&agent_id="+agent_id+"&api_token="+api_token+"&t1="+t1+"&t2="+t2+"&user_type="+user_type+"&user_id="+user_id
}, function(error, response, body){
     //console.log("body "+body);
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
           
});

}

var server = https.createServer({
    key: fs.readFileSync('/home/devmobilewash/ssl/keys/e2d28_c9e8d_89b88bb84590416dfdcf0e774e73b971.key'),
    cert: fs.readFileSync('/home/devmobilewash/ssl/certs/devmobilewash_com_e2d28_c9e8d_1548719999_dd90cb2446ad5a1e069143697a227a06.crt'),
    //ca: fs.readFileSync('./test_ca.crt'),
    requestCert: false,
    rejectUnauthorized: false
},app);
server.listen(3000);

var io = require('socket.io').listen(server);

io.sockets.on('connection',function (socket) {

//io.on('connection', function(socket){
console.log(socket.handshake.query.auth_token);
//connectedusers.push(socket.id);
//console.log("user socket id connected: "+socket.id);
if(socket.handshake.query.action == 'commandcenter') {
     console.log('admin user connected');
    //getAppstat();
    //getpendingwashesdetails();
    //getagentsbystatus();
    //getclientsbystatus();
}
else{
  console.log('app user connected');  
}

  socket.on('get_appstat', function(data){
      //console.log(data);
     getAppstat(data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
  socket.on('get_pendingwashesdetails', function(data){
      //console.log(data);
     getpendingwashesdetails(data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
   socket.on('get_agentsbystatus', function(data){
      //console.log(data);
     getagentsbystatus(data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
   
      socket.on('get_clientsbystatus', function(data){
      //console.log(data);
     getclientsbystatus(data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
  socket.on('getnewwashrequest', function(data){
      //console.log(data);
     washing_getnewwashrequest(data.agent_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
    socket.on('currentwashondemandalert', function(data){
      //console.log(data);
    washing_currentwashondemandalert(data.wash_request_id, data.key, data.socketId, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
     socket.on('currentwashschedulealert', function(data){
      //console.log(data);
    washing_currentwashschedulealert(data.wash_request_id, data.key, data.socketId, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
     
   socket.on('nonreturn20daynotify', function(data){
      //console.log(data);
    customers_nonreturn20daynotify(data.agent_id, data.agent_lat, data.agent_lng, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
  socket.on('washingkart', function(data){
      //console.log(data);
    washing_washingkart(data.wash_request_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
  socket.on('checkwashrequeststatus', function(data){
      //console.log(data);
    washing_checkwashrequeststatus(data.wash_request_id, data.customer_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
   socket.on('getallschedulewashes', function(data){
      //console.log(data);
    washing_getallschedulewashes(data.agent_id, data.washer_position, data.agent_latitude, data.agent_longitude, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
   socket.on('updateuserdevice', function(data){
      //console.log(data);
    site_updatedevicestatus(data.user_type, data.user_id, data.device_token, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type_security, data.user_id_security);
  });
  
     socket.on('updateagentlocations', function(data){
      //console.log(data);
    agents_updateagentlocations(data.agent_id, data.latitude, data.longitude, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
     
          socket.on('updatecustomerlocations', function(data){
      //console.log(data);
    customers_updatecustomerlocations(data.customer_id, data.latitude, data.longitude, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
   socket.on('getbtclienttoken', function(data){
      //console.log(data);
    customers_getclienttoken(data.customer_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
    socket.on('addcustomerpaymentmethod', function(data){
      //console.log(data);
    customers_addcustomerpaymentmethod(data.customer_id, data.nonce, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
   socket.on('getcustomerpaymentmethods', function(data){
      //console.log(data);
    customers_getcustomerpaymentmethods(data.customer_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
   
     socket.on('getwash30secondtimer', function(data){
      //console.log(data);
    washing_getwash30secondtimer(data.wash_request_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
     
  socket.on('removeduplicatewashnowstartlog', function(data){
      //console.log(data);
    washing_removeduplicatewashnowstartlog(data.wash_request_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
  
    socket.on('getcustomerdevice', function(data){
      //console.log(data);
    customers_getcustomerdevice(data.customer_id, data.device_token, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
    
        socket.on('getagentdevice', function(data){
      //console.log(data);
    agents_getagentdevice(data.agent_id, data.device_token, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
        
           socket.on('sendorderreceipts', function(data){
      //console.log(data);
    washing_sendorderreceipts(data.wash_request_id, data.customer_id, data.agent_id, data.socketId, data.key, data.api_token, data.t1, data.t2, data.user_type, data.user_id);
  });
           
   socket.on('checkuserforcelogout', function(data){
      //console.log(data);
    users_checkuserforcelogout(data.user_type, data.user_id, data.socketId, data.key);
  });
   

  socket.on('disconnect', function(){
    if(socket.handshake.query.action == 'commandcenter') {
        console.log('admin user disconnected');
        //clearTimeout(getappstattimer);
        //clearTimeout(getpendingwashesdetailstimer);
        //clearTimeout(getagentsbystatustimer);
        //clearTimeout(getclientsbystatustimer);
    }
    else{
      console.log('app user disconnected');
      //clearTimeout(getnewwashrequesttimer);
    }

  });
});

/*https.listen(3000, function(){
  console.log('listening on *:3000');
});*/