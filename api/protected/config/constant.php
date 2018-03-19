<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 11/26/2015
 * Time: 4:24 AM
 */

/* --- API key --- */

define("API_KEY","Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4");
define("API_KEY_CRON","ZZAvmySKskGsEL4ecUuXyytCcTLXLuDAsGrzSq9T");
define("ROOT_URL","http://www.devmobilewash.com");
define("ROOT_WEBFOLDER","/home/devmobilewash");
define("APP_ENV",""); // real or blank

define("ENV","sandbox");//sandbox or production
define("MERCHANT_ID","czckz7jkzcnny4jj");
define("PUBLIC_KEY","zwcjr8h49b5j5s96");
define("PRIVATE_KEY","1d9f980b86df0a4d0e0ce3253970a8ee");
define("MERCHANT_ACCOUNT_ID","");

/*
define("ENV","production");//sandbox or production
define("MERCHANT_ID","74zsnfqy5svgpvjv");
define("PUBLIC_KEY","7gg5kfvkx8w5fcx8");
define("PRIVATE_KEY","579e6af0c752079c2f9596c838191327");
define("MERCHANT_ACCOUNT_ID","");
*/
//TWILIO

//

define("SID",'ACb25f4b4987698fcd9f5ca7512a704105'); // production  ACa9a7569fc80a0bd3a709fb6979b19423
define("CLIENT_TOKEN","38e0bce8600d30b2446c1b7e6aa725ed"); // production  149336e1b81b2165e953aaec187971e6
define("VERSION",'2010-04-01');

// wash request staus

define("WASHREQUEST_STATUS_PENDING",'0');
define("WASHREQUEST_STATUS_ACCEPTED",'1');
define("WASHREQUEST_STATUS_AGENTARRIVED",'2');
define("WASHREQUEST_STATUS_AGENTARRIVED_CONFIRMED_BYCLIENT",'3');
define("WASHREQUEST_STATUS_COMPLETEWASH",'4');
define("WASHREQUEST_STATUS_CANCELWASH_BYCLIENT",'5');
define("WASHREQUEST_STATUS_CANCELWASH_BYAGENT",'6');
// given by sanket.
#define WASHREQUEST_STATUS_PENDING 0
#define WASHREQUEST_STATUS_ACCEPTED 1
#define WASHREQUEST_STATUS_AGENTARRIVED 2
#define WASHREQUEST_STATUS_AGENTARRIVED_CONFIRMED_BYCLIENT 3
#define WASHREQUEST_STATUS_COMPLETEWASH 4
#define WASHREQUEST_STATUS_CANCELWASH_BYCLIENT 5
#define WASHREQUEST_STATUS_CANCELWASH_BYAGENT 6