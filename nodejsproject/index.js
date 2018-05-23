var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var request = require('request');
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

function getAppstat(socket_id='') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=users/Appstat',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4"
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

function getpendingwashesdetails(socket_id = '') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/pendingwashesdetails',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4"
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

function washing_currentwashondemandalert(wash_request_id=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/currentwashondemandalert',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&wash_request_id="+wash_request_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('pendingwash func');
//io.emit('get pendingwashesdetails', JSON.parse(body));
});
}

function washing_currentwashschedulealert(wash_request_id=0) {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/currentwashschedulealert',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&wash_request_id="+wash_request_id
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('pendingwash func');
//io.emit('get pendingwashesdetails', JSON.parse(body));
});
}

function washing_washingkart(wash_request_id=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/washingkart',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&wash_request_id="+wash_request_id
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

function washing_checkwashrequeststatus(wash_request_id=0, customer_id=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/checkwashrequeststatus',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&wash_request_id="+wash_request_id+"&customer_id="+customer_id
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

function getagentsbystatus(socket_id = '') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=agents/agentsbystatus',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4"
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

function getclientsbystatus(socket_id = '') {

request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=customers/clientsbystatus',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4"
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

function washing_getnewwashrequest(agent_id=0, socket_id = '') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/getnewwashrequest',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&agent_id="+agent_id
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

function washing_getallschedulewashes(agent_id=0, washer_position = '', agent_latitude = '', agent_longitude = '', socket_id = '') {
//console.log(agent_id);
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=washing/getallschedulewashes',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&agent_id="+agent_id+"&washer_position="+washer_position+"&agent_latitude="+agent_latitude+"&agent_longitude="+agent_longitude
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

function site_updatedevicestatus(user_type='', user_id=0, device_token='', socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=site/updatedevicestatus',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&user_type="+user_type+"&user_id="+user_id+"&device_token="+device_token
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

function agents_updateagentlocations(agent_id=0, latitude=0, longitude=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=agents/updateagentlocations',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&agent_id="+agent_id+"&latitude="+latitude+"&longitude="+longitude
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
           
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function customers_updatecustomerlocations(customer_id=0, latitude=0, longitude=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=customers/updatecustomerlocations',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&customer_id="+customer_id+"&latitude="+latitude+"&longitude="+longitude
}, function(error, response, body){
 //console.log(JSON.parse(body));
 //console.log('site_updatedevicestatus');
           
});
//getnewwashrequesttimer = setTimeout(washing_getnewwashrequest, 5000);
}

function customers_getclienttoken(customer_id=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=customers/getClientToken',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&customer_id="+customer_id
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

function customers_addcustomerpaymentmethod(customer_id=0, nonce = '', socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=customers/addcustomerpaymentmethod',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&customer_id="+customer_id+"&nonce="+nonce
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

function customers_getcustomerpaymentmethods(customer_id=0, socket_id = '') {
request.post({
  headers: {'content-type' : 'application/x-www-form-urlencoded'},
  url:     'http://www.devmobilewash.com/api/index.php?r=customers/getcustomerpaymentmethods',
  body:    "key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&customer_id="+customer_id
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

io.on('connection', function(socket){
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
     getAppstat(data.socketId);
  });
  
  socket.on('get_pendingwashesdetails', function(data){
      //console.log(data);
     getpendingwashesdetails(data.socketId);
  });
  
   socket.on('get_agentsbystatus', function(data){
      //console.log(data);
     getagentsbystatus(data.socketId);
  });
   
      socket.on('get_clientsbystatus', function(data){
      //console.log(data);
     getclientsbystatus(data.socketId);
  });
  
  socket.on('getnewwashrequest', function(data){
      //console.log(data);
     washing_getnewwashrequest(data.agent_id, data.socketId);
  });
  
    socket.on('currentwashondemandalert', function(data){
      //console.log(data);
    washing_currentwashondemandalert(data.wash_request_id, data.socketId);
  });
  
     socket.on('currentwashschedulealert', function(data){
      //console.log(data);
    washing_currentwashschedulealert(data.wash_request_id, data.socketId);
  });
  
  socket.on('washingkart', function(data){
      //console.log(data);
    washing_washingkart(data.wash_request_id, data.socketId);
  });
  
  socket.on('checkwashrequeststatus', function(data){
      //console.log(data);
    washing_checkwashrequeststatus(data.wash_request_id, data.customer_id, data.socketId);
  });
  
   socket.on('getallschedulewashes', function(data){
      //console.log(data);
    washing_getallschedulewashes(data.agent_id, data.washer_position, data.agent_latitude, data.agent_longitude, data.socketId);
  });
  
   socket.on('updateuserdevice', function(data){
      //console.log(data);
    site_updatedevicestatus(data.user_type, data.user_id, data.device_token, data.socketId);
  });
  
     socket.on('updateagentlocations', function(data){
      //console.log(data);
    agents_updateagentlocations(data.agent_id, data.latitude, data.longitude, data.socketId);
  });
     
          socket.on('updatecustomerlocations', function(data){
      //console.log(data);
    customers_updatecustomerlocations(data.customer_id, data.latitude, data.longitude, data.socketId);
  });
  
   socket.on('getbtclienttoken', function(data){
      //console.log(data);
    customers_getclienttoken(data.customer_id, data.socketId);
  });
  
    socket.on('addcustomerpaymentmethod', function(data){
      //console.log(data);
    customers_addcustomerpaymentmethod(data.customer_id, data.nonce, data.socketId);
  });
  
   socket.on('getcustomerpaymentmethods', function(data){
      //console.log(data);
    customers_getcustomerpaymentmethods(data.customer_id, data.socketId);
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

http.listen(3000, function(){
  console.log('listening on *:3000');
});