<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 11/26/2015
 * Time: 4:24 AM
 */

/* --- API key --- */

define("API_KEY","m4b5WB0h0HXUK8fTvPw5O1nIDDmEAt4c");
define("API_KEY_CRON","zCWJmjHOlZDbsyiLwhwM0bDdB9sUo2yo");
define("ROOT_URL","https://www.devmobilewash.com");
define("ROOT_WEBFOLDER","/home/devmobilewash");
define("COOKIE_DOMAIN",".devmobilewash.com");
define("WEBSITE_URL","https://www.mobilewash.com");
define("APP_ENV",""); // real or blank
define("MW_SERVER_IP","209.95.41.9");

define("ENV","sandbox");//sandbox or production
define("MERCHANT_ID","czckz7jkzcnny4jj");
define("MERCHANT_ID_REAL","74zsnfqy5svgpvjv");
define("PUBLIC_KEY","zwcjr8h49b5j5s96");
define("PRIVATE_KEY","1d9f980b86df0a4d0e0ce3253970a8ee");
define("MERCHANT_ACCOUNT_ID","");
define("BT_TRANSACTION_URL","https://sandbox.braintreegateway.com/merchants/czckz7jkzcnny4jj/transactions/");


//AES-256-CBC keys

define("AES256CBC_PASSPHRASE","buckshotherniadiwangatflamcephalad");
define("AES256CBC_KEY","C100C17EE98435861499D347578F93970B462B8ECA86744B");
define("AES256CBC_IV","6877393754561BEA7C8FC5693A6D05BA");
define("AES256CBC_SALT","928FC69A87CFFF2F");
define("AES128CBC_KEY","RfUjWnZr4u7x!A%D");
define("AES128CBC_IV","u7x!A%D*G-KaPdSg");
define("AES256CBC_STATUS", 1); // 0 => encrypt/decrypt off, 1 => encrypt/decrypt on
define("AES256CBC_API_PASS","33CkqOl7C*4iO5Q.W8aO2eCYQ749=%158Jt8mIbM");
define("AES256CBC_API_PASS_V2_2","AE22K5O6j15xY3Qg5Oh989n6v63bS8eJ");

/*
define("ENV","production");//sandbox or production
define("MERCHANT_ID","74zsnfqy5svgpvjv");
define("PUBLIC_KEY","7gg5kfvkx8w5fcx8");
define("PRIVATE_KEY","579e6af0c752079c2f9596c838191327");
define("MERCHANT_ACCOUNT_ID","");
*/
//TWILIO

//

define("TWILIO_SID",'ACa9a7569fc80a0bd3a709fb6979b19423'); 
define("TWILIO_AUTH_TOKEN","c2644f802b56217189ae58736e246e69"); 
define("VERSION",'2010-04-01');

define("AWS_ACCESS_KEY",'AKIAJ6HPIJ2U66GFEPSA'); 
define("AWS_SECRET_KEY","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");
define("AWS_CUST_IOS_PLATFORM_ARN","arn:aws:sns:us-west-2:461900685840:app/GCM/custiosdevices_dev");
define("AWS_CUST_ANDROID_PLATFORM_ARN","arn:aws:sns:us-west-2:461900685840:app/GCM/custandroiddevices_dev");
define("AWS_WASHER_IOS_PLATFORM_ARN","arn:aws:sns:us-west-2:461900685840:app/GCM/washeriosdevices_dev");
define("AWS_WASHER_ANDROID_PLATFORM_ARN","arn:aws:sns:us-west-2:461900685840:app/GCM/washerandroiddevices_dev");

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

defined('DATE_TIME') OR define('DATE_TIME', date('Y-m-d H:i:s'));

// washer push api key

define('API_ACCESS_KEY_ANDRIOD', 'AAAAKHWvBtc:APA91bH7eWGNgvoZQxe56zzxeE2cxW4qVG_5dc9iwpF73R0ph0govruyXQ-1QK-pE_VxLeBewkXsnKWecuVp42IZKJSB0Z6yo5x44w6ytelM7HXWHSItSViPO4TmzscYddTEmcqNi3ae');