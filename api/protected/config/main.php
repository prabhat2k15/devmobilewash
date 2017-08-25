<?php
include(dirname(__FILE__) . '/constant.php');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'MobileWash',
	'theme'=> 'varg',
	'preload'=>array('log'),
 'timeZone' => 'America/Los_Angeles',

	// auto loading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.YiiMailer.YiiMailer',
          'ext.braintree.CustomBraintree',

	),

	'modules'=>array(
		'admin',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		//	Database configuration
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=devmobil_mwmain',
			'emulatePrepare' => true,
			'username' => 'devmobil_mwuser',
			'password' => 'XUS9Qf9bwJ%&',
			'charset' => 'utf8',
		),
		'braintree' => array(
            'class'=>'application.extensions.braintree.CustomBraintree',
            'ENV' => ENV,//sandbox or production
            'MERCHANT_ID' => MERCHANT_ID,
            'MERCHANT_ACCOUNT_ID'=>MERCHANT_ACCOUNT_ID,
            'PUBLIC_KEY' => PUBLIC_KEY,
            'PRIVATE_KEY'=>PRIVATE_KEY
        ),
'twilio' => array(
            'class'=>'application.extensions.twilio.CustomTwilio',
            'SID' => SID,
            'CLIENT_TOKEN'=>CLIENT_TOKEN,
            'VERSION'=>VERSION
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, waiting',
				),
			),
		),
	),
	'params'=>array(
		//	Global Admin Email
		'adminEmail'=>'mobilewashapp@gmail.com',
		'adminToEmail'=>'mobilewashapp@gmail.com',
		'adminFromEmail'=>'info@devmobilewash.com'
	),
);
