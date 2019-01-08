<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
//Yii::import('application.vendors.*');
//require_once('braintree/lib/Braintree.php');

require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/amazon-sdk/aws-autoloader.php';

use Twilio\Rest\Client;
use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

//use Aws\Sns\Exception;

class CustomersController extends Controller {

    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13102941020';
    protected $callbackurl = ROOT_URL . '/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex() {
        $this->render('index');
    }

    /*
     * * Performs the User Registration.
     * * Post Required: emailid, customername, password, image, device_token, login_type, mobile_type
     * * Url:- http://www.demo.com/projects/index.php?r=customers/customersregistration
     * * Purpose:- New customers can register in app
     */

    public function actionregister() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $how_hear_mw = '';
        $emailid = Yii::app()->request->getParam('emailid');
        $customername = Yii::app()->request->getParam('customername');
        $password = trim(Yii::app()->request->getParam('password'));

        if ($password != "")
            $password = md5($password);

        $image = Yii::app()->request->getParam('image');
        $device_token = Yii::app()->request->getParam('device_token');
        $login_type = Yii::app()->request->getParam('login_type');
        $mobile_type = Yii::app()->request->getParam('mobile_type');
        $contact_number = Yii::app()->request->getParam('contact_number');
        $time_zone = Yii::app()->request->getParam('time_zone');
        $client_position = Yii::app()->request->getParam('client_position');
        $action = '';
        $action = Yii::app()->request->getParam('action');
        if (!empty(Yii::app()->request->getParam('how_hear_mw'))) {
            $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
        }
        if (!empty(Yii::app()->request->getParam('form_tracker'))) {
            $form_tracker = Yii::app()->request->getParam('form_tracker');
        }


        $json = array();
        $customerid = '';
        if ((isset($emailid) && !empty($emailid)) && (isset($customername) && !empty($customername)) && (isset($password) && !empty($password)) && (isset($contact_number) && !empty($contact_number))) {
            $customers_email_exists = Customers::model()->findByAttributes(array("email" => $emailid));
            $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $contact_number));
            $agents_email_exists = Agents::model()->findByAttributes(array("email" => $emailid));

            if (!empty($agents_email_exists)) {
                $result = 'false';
                $response = 'You are already registered as Agent';
                $json = array(
                    'result' => $result,
                    'response' => $response
                );
            } else {

                if (count($customers_email_exists) > 0) {
                    $result = 'false';
                    $response = 'Email already exists.';
                    $customerid = $customers_email_exists->id;
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'customerid' => $customerid
                    );
                } else if (count($customers_phone_exists) > 0) {
                    $result = 'false';
                    $response = 'Phone number already exists.';
                    $customerid = $customers_email_exists->id;
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'customerid' => $customerid
                    );
                } else {

                    if ($image == NULL) {
                        $directorypath1 = realpath(Yii::app()->basePath . '/../images/cust_img');
                        $SiteUrl = Yii::app()->getBaseUrl(true);
                        $db_imagename = $SiteUrl . '/images/cust_img/no_profile.jpg';
                    } else {
                        if ($login_type == 'facebook') {
                            $db_imagename = $image;
                        } else {
                            $directorypath1 = realpath(Yii::app()->basePath . '/../images/cust_img');
                            $img = str_replace('data:image/PNG;base64,', '', $image);
                            $img = str_replace(' ', '+', $img);
                            $data = base64_decode($img);
                            $md5 = md5(uniqid(rand(), true));
                            $name = 'customerimg_' . $md5 . ".jpg";
                            $path = $directorypath1 . '/' . $name;
                            $SiteUrl = Yii::app()->getBaseUrl(true);
                            $db_imagename = $SiteUrl . '/images/cust_img/' . $name;

                            file_put_contents($path, $data);
                        }
                    }

                    $customerdata = array(
                        'email' => $emailid,
                        'customername' => $customername,
                        'password' => $password,
                        'image' => $db_imagename,
                        'device_token' => $device_token,
                        'mobile_type' => $mobile_type,
                        'contact_number' => $contact_number,
                        'time_zone' => $time_zone,
                        'client_position' => $client_position,
                        'account_status' => 0,
                        'how_hear_mw' => $how_hear_mw,
                        'form_tracker' => $form_tracker,
                        'created_date' => date("Y-m-d H:i:s"),
                        'updated_date' => date("Y-m-d H:i:s")
                    );

                    $customerdata = array_filter($customerdata);
                    $model = new Customers;
                    $model->attributes = $customerdata;

                    if ($model->save(false)) {
                        $customerid = Yii::app()->db->getLastInsertID();

                        $customers_details = Customers::model()->findByAttributes(array('id' => $customerid));
                        $customername = '';
                        $password = '';
                        $email = '';
                        $image = '';
                        if (count($customers_details) > 0) {
                            $customername = $customers_details->first_name . " " . $customers_details->last_name;
                            $email = $customers_details->email;
                            $image = $customers_details->image;
                        }
                        $encriptpassword = md5($customerid);
                        $result = 'true';
                        $response = 'Customer successfully registered';

                        //	Create default home location while customer register
                        //Yii::app()->db->createCommand()->insert('customer_locations',array('customer_id' =>$customerid,'location_title'=>'Home'));
                        //	Create default office location while customer register
                        //Yii::app()->db->createCommand()->insert('customer_locations',array('customer_id' =>$customerid,'location_title'=>'Office'));


                        /*
                          $location_details = Yii::app()->db->createCommand()
                          ->select('*')
                          ->from('customer_locations')
                          ->where("customer_id='".$customerid."'", array())
                          ->queryAll();

                          $locations = array();

                          if(count($location_details)>0){
                          foreach($location_details as $sloc){
                          $locations[]= array(
                          'location_title'=> $sloc['location_title'],
                          'location_address'=> $sloc['location_address'],
                          'actual_longitude'=> $sloc['actual_longitude'],
                          'actual_latitude'=> $sloc['actual_latitude'],
                          'is_editable'=> $sloc['is_editable']
                          );
                          }
                          }
                         */

                        $customername2 = '';
                        $cust_name = explode(" ", trim($customername));
                        if (count($cust_name > 1))
                            $customername2 = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                        else
                            $customername2 = $cust_name[0];
                        $cust_firstname = '';
                        $cust_firstname = $cust_name[0];
                        $json = array(
                            'result' => $result,
                            'response' => $response,
                            'user_type' => 'customer',
                            'customerid' => $customerid,
                            'customername' => $customername2,
                            'emailid' => $email,
                            'image' => $image,
                            'contact_number' => $contact_number,
                            'time_zone' => $time_zone,
                            'locations' => '',
                            'fifth_wash_points' => 0,
                            'total_washes' => 0,
                            'email_alerts' => 0,
                            'client_position' => $client_position,
                            'push_notifications' => 0
                        );

                        $from = Vargas::Obj()->getAdminFromEmail();
                        //echo $from;
                        if ($customers_details->customername && $customers_details->customername != 'N/A')
                            $subject = 'Welcome to Mobile Wash! ' . $customername2;
                        else
                            $subject = 'Welcome to Mobile Wash!';
                        //$message = "Hello ".$customername2.",<br/><br/>Welcome to Mobile wash!";

                        if ($customers_details->customername && $customers_details->customername != 'N/A')
                            $message = "<h1 style='margin-top: 10px;'>Hello " . $cust_firstname . ",</h1>";
                        else
                            $message = "<h1 style='margin-top: 10px;'>Hello!</h1>";

                        $message .= "<p style='color: #333;'>Thank you for downloading MobileWash. We realize that there are many choices when it comes to washing and detailing your vehicle. We hope that the convenience of our service exceeds your expectations and brings a smile to your face.</p><p style='color: #333;'>We look forward to serving you soon.</p>";

                        //if($action) $message .= "<a style='background: #076ee1 none repeat scroll 0 0; border: 0 none; border-radius: 5px; color: #fff; cursor: pointer; display: block; font-size: 18px; font-weight: 700;  padding: 12px 0; text-align: center; text-decoration: none; text-transform: uppercase;  width: 283px;' href='".ROOT_URL."/email_confirm.php?code=".$encriptpassword."&action=".$action."'> ACTIVATE MY ACCOUNT </a>";
                        //else $message .= "<a style='background: #076ee1 none repeat scroll 0 0; border: 0 none; border-radius: 5px; color: #fff; cursor: pointer; display: block; font-size: 18px; font-weight: 700;  padding: 12px 0; text-align: center; text-decoration: none; text-transform: uppercase;  width: 283px;' href='".ROOT_URL."/email_confirm.php?code=".$encriptpassword."'> ACTIVATE MY ACCOUNT </a>";


                        $message .= "<p style='height: 0px;'>&nbsp;</p>
					<p style='color: #333;'><b>Kind Regards,</b></p>
					<p style='color: #333;'><b>The MobileWash Team</b></p>
					<p style='color: blue;'>www.mobilewash.com</p>
					<p style='color: blue;'>support@mobilewash.com</p>";

                        Vargas::Obj()->SendMail($email, $from, $message, $subject, 'mail-custregister');
                    }else {
                        $result = 'false';
                        $response = 'Internal error';
                        $json = array(
                            'result' => $result,
                            'response' => $response,
                            'customerid' => $customerid
                        );
                    }
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response,
                'customerid' => $customerid
            );
        }
        echo json_encode($json);
        die();
    }

    public function actionphoneregister() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $phone_number = Yii::app()->request->getParam('phone_number');
        $client_position = Yii::app()->request->getParam('client_position');


        $json = array();
        $customerid = '';
        if ((isset($phone_number) && !empty($phone_number))) {
            $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $phone_number));


            if (count($customers_phone_exists) > 0) {
                $result = 'false';
                $response = 'Phone number already exists.';
                $customerid = $customers_phone_exists->id;
                $json = array(
                    'result' => $result,
                    'response' => $response,
                    'customerid' => $customerid
                );
            } else {


                $customerdata = array(
                    'contact_number' => $phone_number,
                    'client_position' => $client_position,
                    'account_status' => 0,
                    'created_date' => date("Y-m-d H:i:s"),
                    'updated_date' => date("Y-m-d H:i:s")
                );

                $customerdata = array_filter($customerdata);
                $model = new Customers;
                $model->attributes = $customerdata;

                if ($model->save(false)) {
                    $customerid = Yii::app()->db->getLastInsertID();



                    $result = 'true';
                    $response = 'Customer successfully registered';


                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'user_type' => 'customer',
                        'customerid' => $customerid,
                        'contact_number' => $phone_number,
                        'client_position' => $client_position,
                    );
                } else {
                    $result = 'false';
                    $response = 'Internal error';
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'customerid' => $customerid
                    );
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response,
                'customerid' => $customerid
            );
        }
        echo json_encode($json);
        die();
    }

    public function actioncustomeremailcheck() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $email = Yii::app()->request->getParam('email');
        $response = "Please provide email";
        $result = "false";

        if (isset($email) && !empty($email)) {

            $customers_email_exists = Customers::model()->findByAttributes(array("email" => $email));
            if (count($customers_email_exists)) {
                $response = "Email already exists";
            } else {
                $response = "email available";
                $result = "true";
            }
        }

        echo json_encode(array("result" => $result, "response" => $response));
        die();
    }

    public function actioncustomerphonecheck() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $phone = Yii::app()->request->getParam('phone');
        $response = "Please provide phone";
        $result = "false";

        if (isset($phone) && !empty($phone)) {

            $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $phone));
            if (count($customers_phone_exists)) {
                $response = $customers_phone_exists->id;
                $result = 'true';
            } else {
                $response = "phone not exists";
                $result = "false";
            }
        }

        echo json_encode(array("result" => $result, "response" => $response));
        die();
    }

    public function actionConfirmEmailAddress() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerID = Yii::app()->request->getParam('code');
        $email = Yii::app()->request->getParam('email');
        $customerDetail = Yii::app()->db->createCommand("SELECT * FROM customers WHERE md5(id) = :customer_id ")->bindValue(':customer_id', $customerID, PDO::PARAM_STR)->queryAll();
        $customers_email_exists = Customers::model()->findByAttributes(array("email" => $email));
        if (!empty($customerDetail)) {

            if (count($customers_email_exists) && ($customers_email_exists->id != $customerDetail[0]['id'])) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Email already exists'
                );

                echo json_encode($json);
                die();
            }

            $account_status = array('account_status' => 1);
            $update_status = Customers::model()->updateAll($account_status, 'md5(id)=:id', array(':id' => $customerID));
            $result = 'true';
            $response = 'Confirm Your Email address.';
            $json = array(
                'result' => $result,
                'response' => $response,
                'customer_id' => $customerDetail[0]['id']
            );

            echo json_encode($json);
            die();
        } else {
            $result = 'false';
            $response = 'Sorry, Your Email address does not match.';
            $json = array(
                'result' => $result,
                'response' => $response
            );

            echo json_encode($json);
            die();
        }
    }

    /**
     * * Performs the login.
     * */
    public function actionlogin() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $emailid = Yii::app()->request->getParam('emailid');
        $password = md5(Yii::app()->request->getParam('password'));
        $device_token = Yii::app()->request->getParam('device_token');
        $mobile_type = Yii::app()->request->getParam('mobile_type');
        file_put_contents("login_api.log", $emailid . " + " . $password . " + " . $device_token . " + " . $mobile_type . date("y-m-d h:i:s") . "\n", FILE_APPEND);
        if ((isset($emailid) && !empty($emailid)) && (isset($password) && !empty($password))) {
            $customers_id = Customers::model()->findByAttributes(array("email" => $emailid));
            $accountstatus = $customers_id['account_status'];
            if (count($customers_id) > 0) {
                if ($customers_id->password == $password) {
                    if (!empty($device_token)) {
                        if (!empty($device_token) && ($device_token != " " && $device_token != "Device Token")) {
                            $customers_id->device_token = $device_token;
                            $customers_id->mobile_type = $mobile_type;
                            $customers_id->save(false);
                        }
                    }
                    $result = 'true';
                    $response = 'Successfully logged in';
                    if ($mobile_type != 'WEB') {
                        $online_status = array('online_status' => 'online');

                        $update_status = Customers::model()->updateAll($online_status, 'id=:id', array(':id' => $customers_id->id));
                    }

                    $location_details = Yii::app()->db->createCommand()
                            ->select('*')
                            ->from('customer_locations')
                            ->where("customer_id='" . $customers_id->id . "'", array())
                            ->queryAll();

                    $locations = array();

                    if (count($location_details) > 0) {
                        foreach ($location_details as $sloc) {
                            $locations[] = array(
                                'location_title' => $sloc['location_title'],
                                'location_address' => $sloc['location_address'],
                                'actual_longitude' => $sloc['actual_longitude'],
                                'actual_latitude' => $sloc['actual_latitude'],
                                'is_editable' => $sloc['is_editable']
                            );
                        }
                    }

                    $customername = '';
                    $cust_name = explode(" ", trim($customers_id->customername));
                    if (count($cust_name > 1))
                        $customername = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                    else
                        $customername = $cust_name[0];

                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'customerid' => $customers_id->id,
                        'email' => $customers_id->email,
                        'customername' => $customername,
                        'image' => $customers_id->image,
                        'contact_number' => $customers_id->contact_number,
                        'locations' => $locations,
                        'total_washes' => $customers_id->total_wash,
                        'email_alerts' => $customers_id->email_alerts,
                        'push_notifications' => $customers_id->push_notifications,
                    );
                }

                else {
                    $result = 'false';
                    $response = 'Wrong password';
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                    );
                }
            } else {
                $result = "false";
                $response = 'Wrong email';
                $json = array(
                    'result' => $result,
                    'response' => $response
                );
            }
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }
        echo json_encode($json);
        die();
    }

    public function actionlogout() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customers_id = Yii::app()->request->getParam('customer_id');
        $device_token = Yii::app()->request->getParam('device_token');
        if (AES256CBC_STATUS == 1) {
            $customers_id = $this->aes256cbc_crypt($customers_id, 'd', AES256CBC_API_PASS);
        }
        $model = Customers::model()->findByAttributes(array('id' => $customers_id));
        $json = array();
        if (count($model) > 0) {
            $data = array('device_token' => '');
            $model->attributes = $data;
            if ($model->save(false)) {
                $result = 'true';
                $response = 'Successfully logged out';
                $json = array(
                    'result' => $result,
                    'response' => $response
                );

                $online_status = array('online_status' => 'offline', 'forced_logout' => 0, 'access_token' => '', 'access_key' => '', 'access_vector' => '');

                $update_status = Customers::model()->updateAll($online_status, 'id=:id', array(':id' => $customers_id));
                Yii::app()->db->createCommand("UPDATE customer_devices SET device_status='offline' WHERE customer_id = :customer_id AND device_token = :device_token")
                        ->bindValue(':customer_id', $customers_id, PDO::PARAM_STR)
                        ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
                        ->execute();
            }
        } else {
            $result = 'false';
            $response = 'Not a authorized customer';
            $json = array(
                'result' => $result,
                'response' => $response
            );
        }
        echo json_encode($json);
    }

    public function actionauthenticate() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $result = 'false';
        $response = 'error';
        $customer_name = '';
        $customer_id = Yii::app()->request->getParam('customer_id');
        if (isset($customer_id) && ($customer_id != '')) {

            $model = Customers::model()->findByAttributes(array('id' => $customer_id));
            $json = array();
            if ((count($model) > 0) && ($model->device_token != '')) {
                $result = 'true';
                $response = 'success';
                $customer_name = $model->first_name . " " . $model->last_name;
            } else {
                $result = 'false';
                $response = 'error';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'cust_name' => $customer_name
        );
        echo json_encode($json);
    }

    /*
     * * Performs the forgot password.
     * * Post Requirement: emailid
     */

    public function actionforgetpassword() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $email = Yii::app()->request->getParam('emailid');
        $json = array();
        if (isset($email) && !empty($email)) {
            $customers_email_exists = Customers::model()->findByAttributes(array("email" => $email));
            if (count($customers_email_exists) > 0) {
                $token = md5(time());
                $customers_email_exists->token = $token;
                if ($customers_email_exists->save(false)) {
                    $uniqueMail = $email;
                    $from = Vargas::Obj()->getAdminFromEmail();
                    $subject = 'MobileWash.com - Reset Your Password';
                    $reporttxt = ROOT_URL . '/reset-password.php?action=clrp&token=' . $token . '&id=' . $customers_email_exists->id;
                    $message = "";

                    $message .= "<p style='font-size: 20px;'>Dear " . $customers_email_exists->first_name . " " . $customers_email_exists->last_name . ",</p>";
                    $message .= "<p>You requested to reset your MobileWash password information. To complete the request, please click the link below.</p>";
                    $message .= "<p><a href='" . $reporttxt . "' style='color: #016fd0;'>Reset Password Now</a></p>";
                    $message .= "<p>If this was a mistake or you did not authorize this request you may disregard this email.</p>";
                    $message .= "<p style='font-size: 20px;line-height: 30px;margin-top: 30px;'>Kind Regards,<br>";
                    $message .= "The MobileWash Team<br><a href='" . ROOT_URL . "' style='font-size: 16px; color: #016fd0;'>www.mobilewash.com</a><br><a href='mailto:support@mobilewash.com' style='font-size: 16px; color: #016fd0;'>support@mobilewash.com</a></p>";

                    Vargas::Obj()->SendMail($uniqueMail, $from, $message, $subject);
                    $json = array(
                        'result' => 'true',
                        'response' => 'Reset password email has been sent to your email account',
                    );
                } else {
                    $json = array(
                        'result' => 'false',
                        'response' => 'Internal Error',
                    );
                }
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'Email does not exists',
                );
            }
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }
        echo json_encode($json);
        die();
    }

    public function actionresetpassword() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $token = Yii::app()->request->getParam('token');
        $id = Yii::app()->request->getParam('id');
        $new_password = Yii::app()->request->getParam('newpassword');
        $cnfpassword = Yii::app()->request->getParam('cnfpassword');
        $json = array();
        $result = 'false';
        $response = 'Pass the required parameters';
        if ((isset($token) && !empty($token)) && (isset($id) && !empty($id)) && (isset($new_password) && !empty($new_password)) && (isset($cnfpassword) && !empty($cnfpassword))) {
            $customers_email_exists = Customers::model()->findByAttributes(array("token" => $token, "id" => $id));
            if (!count($customers_email_exists)) {
                $result = 'false';
                $response = "Sorry can't reset your password. Please check password reset link.";
            } else if (empty($new_password)) {
                $result = 'false';
                $response = "Password can not be empty.";
            } else if ($new_password != $cnfpassword) {
                $result = 'false';
                $response = "New Password and Confirm Password does not match.";
            } else {
                $update_password = Customers::model()->updateAll(array('password' => md5($new_password), 'token' => ''), 'id=:id', array(':id' => $id));
                $result = 'true';
                $response = 'Password updated successfully';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionresendverifyemail() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $email = Yii::app()->request->getParam('emailid');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $json = array();
        if (isset($email) && !empty($email)) {
            $customers_email_exists = Customers::model()->findByAttributes(array("email" => $email));

            if (count($customers_email_exists) && ($customers_email_exists->id != $customer_id)) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Email already exists'
                );
            } else {

                $encriptpassword = md5($customer_id);


                if (($customers_email_exists->first_name != '') && ($customers_email_exists->last_name != '')) {
                    $customername2 = '';
                    $cust_name = explode(" ", trim($customers_email_exists->last_name));
                    $customername2 = $customers_email_exists->first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
                } else {
                    $customername2 = '';
                    $cust_name = explode(" ", trim($customers_email_exists->customername));
                    if (count($cust_name > 1))
                        $customername2 = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                    else
                        $customername2 = $cust_name[0];
                }

                $customername2 = strtolower($customername2);
                $customername2 = ucwords($customername2);

                $from = Vargas::Obj()->getAdminFromEmail();
                //echo $from;
                if ($customers_email_exists->customername && $customers_email_exists->customername != 'N/A')
                    $subject = 'Welcome to Mobile Wash! ' . $customername2;
                else
                    $subject = 'Welcome to Mobile Wash!';
                //$message = "Hello ".$customername2.",<br/><br/>Welcome to Mobile wash!";

                if ($customers_email_exists->customername && $customers_email_exists->customername != 'N/A')
                    $message = "<h1>Hello " . $customername2 . "!</h1>";
                else
                    $message = "<h1>Hello!</h1>";

                $message .= "<p style='color: #333;'>Thank you for signing up with <b style='color: #000;'>Mobile Wash.</b></p>
					<p style='color: #333;'>Please click the link below to confirm your email address and activate your account.</p>";


                if ($action)
                    $message .= "<a style='background: #076ee1 none repeat scroll 0 0; border: 0 none; border-radius: 5px; color: #fff; cursor: pointer; display: block; font-size: 18px; font-weight: 700;  padding: 12px 0; text-align: center; text-decoration: none; text-transform: uppercase;  width: 283px;' href='" . ROOT_URL . "/email_confirm.php?code=" . $encriptpassword . "&email=" . $email . "&action=" . $action . "'> ACTIVATE MY ACCOUNT </a>";
                else
                    $message .= "<a style='background: #076ee1 none repeat scroll 0 0; border: 0 none; border-radius: 5px; color: #fff; cursor: pointer; display: block; font-size: 18px; font-weight: 700;  padding: 12px 0; text-align: center; text-decoration: none; text-transform: uppercase;  width: 283px;' href='" . ROOT_URL . "/email_confirm.php?code=" . $encriptpassword . "&email=" . $email . "'> ACTIVATE MY ACCOUNT </a>";

                $message .= "<p style='margin-bottom: 0;'><a href='" . ROOT_URL . "/coming-soon/' style='display: inline-block; margin-right: 15px;'><img src='" . ROOT_URL . "/images/app-store-btn-large.png' alt='' width='135' /></a><a style='display: inline-block; margin-right: 15px;' href='" . ROOT_URL . "/coming-soon/'><img src='" . ROOT_URL . "/images/gplay-btn-large.png' alt='' width='135' /></a></p>";

                $message .= "<p style='color: #333;'>We hope you enjoy the experience.</p>
					<p style='height: 0px;'>&nbsp;</p>
					<p style='color: #333;'><b>Kind Regards,</b></p>
					<p style='color: #333;'><b>The Mobile Wash Team</b></p>
					<p style='color: blue;'>www.mobilewash.com</p>
					<p style='color: blue;'>support@mobilewash.com</p>";

                Vargas::Obj()->SendMail($email, $from, $message, $subject);

                $json = array(
                    'result' => 'true',
                    'response' => 'Please check your email for verification link',
                );
            }
        }else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }
        echo json_encode($json);
        die();
    }

    /*
      public function actionresetpassword() {

      if(Yii::app()->request->getParam('key') != API_KEY){
      echo "Invalid api key";
      die();
      }

      $this->layout = '//layouts/main2';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $message = "";
      $loadView = "index";
      $new_password = trim($_POST['newpassword']);
      $id = $_POST['id'];

      if(empty($new_password)){
      $message = "Password can not be empty.";
      }else{
      $cnfpassword = $_POST['cnfpassword'];
      if($new_password==$cnfpassword){
      $update_password = Customers::model()->updateAll(array('password'=>md5($new_password),'token'=>''),'id=:id',array(':id'=>$id));
      if($update_password ==1) {
      $loadView = 'message';
      $message = 'Password has been Updated';
      } else {
      $message = 'Password not Updated';
      }
      }else{
      $message = "New Password and Confirm Password does not match.";
      }

      }

      $this->render($loadView,array('id'=>$id,'message'=>$message));

      }else {
      $token = Yii::app()->request->getParam('token');
      $id = Yii::app()->request->getParam('id');
      $customers_email_exists = Customers::model()->findByAttributes(array("token"=>$token,"id"=>$id));
      if(count($customers_email_exists)>0) {
      $this->render('index',array('id'=>$id,'message'=>''));
      } else {
      $this->render('message',array('message'=>"Sorry can't reset your password. Please check reset link."));
      }
      }
      }
     */
    /*
     * * Performs the Customer Profile update.
     * * Post Required: emailid, customername, password, image, contact_number, email_alerts, push_notify
     */

    public function actionprofileupdate() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $json = array();
        $id = Yii::app()->request->getParam('customerid');
        $customername = Yii::app()->request->getParam('customername');
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $email = Yii::app()->request->getParam('emailid');
        $image = Yii::app()->request->getParam('image');
        $password = Yii::app()->request->getParam('password');
        $new_password = Yii::app()->request->getParam('new_password');
        $confirm_password = Yii::app()->request->getParam('confirm_password');

        $contact_number = Yii::app()->request->getParam('contact_number');
        $braintree_id = Yii::app()->request->getParam('braintree_id');
        if (Yii::app()->request->getParam('fifth_wash_points'))
            $fifth_wash_points = Yii::app()->request->getParam('fifth_wash_points');
        $is_first_wash = Yii::app()->request->getParam('is_first_wash');
        $device_token = Yii::app()->request->getParam('device_token');
        $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
        $email_alerts = '';
        $email_alerts = Yii::app()->request->getParam('email_alerts');
        $push_notify = '';
        $push_notify = Yii::app()->request->getParam('push_notify');
        $phone_dup_check = Yii::app()->request->getParam('phone_dup_check');
        $online_status = Yii::app()->request->getParam('online_status');
        $is_voip_number = Yii::app()->request->getParam('is_voip_number');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $id = $this->aes256cbc_crypt($id, 'd', AES256CBC_API_PASS);
        }

        //if($phone_dup_check == 'true'){
        if ($contact_number) {
            $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $contact_number));
            $cust_detail = Customers::model()->findByAttributes(array("id" => $id));

            if ((count($customers_phone_exists) > 0) && ($customers_phone_exists->id != $id)) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Phone number already exists.',
                    'contact_number' => $cust_detail->contact_number
                );

                echo json_encode($json);
                die();
            }

            $sid = TWILIO_SID;
            $token = TWILIO_AUTH_TOKEN;
            $twilio = new Client($sid, $token);
            try {
                $phone_number_check = $twilio->lookups->v1->phoneNumbers($contact_number)->fetch(array("type" => "carrier"));

                if ($phone_number_check->carrier['type'] == 'voip') {
                    $result = "false";
                    $response = "MobileWash no longer allows VOIP numbers, please enter a valid mobile number to continue";
                    $json = array(
                        'result' => $result,
                        'response' => $response
                    );
                    echo json_encode($json);
                    die();
                }
            } catch (Twilio\Exceptions\RestException $e) {
                //echo  $e;
            }


            $digits = 4;
            $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $update_response = Yii::app()->db->createCommand("UPDATE customers SET phone_verify_code='$randum_number' WHERE id = '$id' ")->execute();
            $json = array();

            $this->layout = "xmlLayout";

            //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');
            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

            /* Instantiate a new Twilio Rest Client */

            $account_sid = TWILIO_SID;
            $auth_token = TWILIO_AUTH_TOKEN;
            $client = new Services_Twilio($account_sid, $auth_token);


            $message = $randum_number . " is your MobileWash verification code";
            try {
                $sendmessage = $client->account->messages->create(array(
                    'To' => $contact_number,
                    'From' => '+13106834902',
                    'Body' => $message,
                ));
            } catch (Services_Twilio_RestException $e) {
                //echo  $e;
            }
        }


        if (isset($id) && !empty($id)) {
            $model = Customers::model()->findByAttributes(array('id' => $id));
            if (count($model) > 0) {
                if (!empty($image)) {
                    $directorypath1 = realpath(Yii::app()->basePath . '/../images/cust_img');
                    $img = str_replace('data:image/PNG;base64,', '', $image);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $md5 = md5(uniqid(rand(), true));
                    $name = $id . '_' . $md5 . ".jpg";
                    $path = $directorypath1 . '/' . $name;
                    $SiteUrl = Yii::app()->getBaseUrl(true);
                    $image = $SiteUrl . '/images/cust_img/' . $id . '_' . $md5 . ".jpg";
                    file_put_contents($path, $data);
                } else {
                    $image = $model->image;
                }

                if (empty($email)) {

                    $email = $model->email;
                }

                if (empty($customername)) {
                    $customername = $model->customername;
                }

                if (empty($first_name)) {
                    $first_name = $model->first_name;
                }

                if (empty($last_name)) {
                    $last_name = $model->last_name;
                }

                if (!empty($new_password)) {
                    if (empty($confirm_password)) {
                        $response = 'Please enter confirm password';
                        $password = $model->password;
                    }

                    if ($new_password != $confirm_password) {
                        $response = 'New password mismatch';
                        $password = $model->password;
                    } else {
                        $response = 'Profile updated';
                        $password = md5($new_password);
                    }
                } else {
                    $response = 'Profile updated';
                    $password = $model->password;
                }

                if (empty($contact_number)) {
                    $contact_number = $model->contact_number;
                }

                if (empty($braintree_id)) {
                    $braintree_id = $model->braintree_id;
                }

                if (empty($online_status)) {
                    $online_status = $model->online_status;
                }

                if (empty($fifth_wash_points)) {
                    $fifth_wash_points = $model->fifth_wash_points;
                }

                if (empty($is_first_wash)) {
                    $is_first_wash = $model->is_first_wash;
                }

                if (!is_numeric($is_voip_number)) {
                    $is_voip_number = $model->is_voip_number;
                }

                if (empty($device_token)) {
                    $device_token = $model->device_token;
                }

                if (empty($how_hear_mw)) {
                    $how_hear_mw = $model->how_hear_mw;
                }

                if ($email_alerts == '') {
                    $email_alerts = $model->email_alerts;
                }

                if ($push_notify == '') {
                    $push_notify = $model->push_notifications;
                }

                $result = 'true';

                if (Yii::app()->request->getParam('image_url')) {
                    $image = Yii::app()->request->getParam('image_url');
                }

                if (($first_name != '') && ($last_name != '')) {
                    $customershortname = '';
                    $cust_name = explode(" ", trim($last_name));
                    $customershortname = $first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
                } else {
                    $customershortname = '';
                    $cust_name = explode(" ", trim($customername));
                    if (count($cust_name > 1))
                        $customershortname = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                    else
                        $customershortname = $cust_name[0];
                }

                $customershortname = strtolower($customershortname);
                $customershortname = ucwords($customershortname);

                $data = array(
                    'result' => $result,
                    'response' => $response,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'customername' => $customername,
                    'email' => $email,
                    'image' => $image,
                    //'contact_number'=> $contact_number,
                    'how_hear_mw' => $how_hear_mw,
                    'email_alerts' => $email_alerts,
                    'push_notifications' => $push_notify,
                    'password' => $password,
                    'online_status' => $online_status,
                    'updated_date' => date("Y-m-d H:i:s")
                );
                if (empty($response)) {
                    $response = 'Profile updated';
                }
                $model->attributes = $data;
                if ($model->save(false)) {

                    if ($model->braintree_id && $customername) {

                        if ($model->client_position == 'real')
                            $result = Yii::app()->braintree->updateCustomer_real($model->braintree_id, array('firstName' => $customername));
                        else
                            $result = Yii::app()->braintree->updateCustomer($model->braintree_id, array('firstName' => $model->first_name . " " . $model->last_name));
                    }

                    if ($fifth_wash_points == 'zero')
                        $fifth_wash_points = 0;

                    /* --- campaign monitor subscribe -- */

                    if ((!$is_first_wash) && ($email) && (APP_ENV == 'real')) {
                        $curl = curl_init();
                        $url = "https://2f94ab1c39b710b2357f88158452c58a:x@api.createsend.com/api/v3.2/subscribers/616802d8a601cf1078b314335b206b7a.json";
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode(array('EmailAddress' => $email, 'Name' => $customername, "ConsentToTrack" => "Yes"))
                        ));

                        curl_exec($curl);

                        curl_close($curl);
                    }
                    /* --- campaign monitor subscribe end -- */

                    Customers::model()->updateByPk($id, array('braintree_id' => $braintree_id, 'fifth_wash_points' => $fifth_wash_points, 'is_first_wash' => $is_first_wash, 'device_token' => $device_token));

                    $location_details = Yii::app()->db->createCommand()
                            ->select('*')
                            ->from('customer_locations')
                            ->where("customer_id='" . $model->id . "'", array())
                            ->queryAll();

                    $locations = array();

                    if (count($location_details) > 0) {
                        foreach ($location_details as $sloc) {
                            $locations[] = array(
                                'location_title' => $sloc['location_title'],
                                'location_address' => $sloc['location_address'],
                                'actual_longitude' => $sloc['actual_longitude'],
                                'actual_latitude' => $sloc['actual_latitude'],
                                'is_editable' => $sloc['is_editable']
                            );
                        }
                    }

                    if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                        $id = $this->aes256cbc_crypt($id, 'e', AES256CBC_API_PASS);
                    }
                    $result = "true";
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'customerid' => $id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'customername' => $customername,
                        'customer_short_name' => $customershortname,
                        'email' => $email,
                        'image' => $image,
                        'password' => $password,
                        'contact_number' => $contact_number,
                        'how_hear_mw' => $how_hear_mw,
                        'locations' => $locations,
                        'total_washes' => $model->total_wash,
                        'email_alerts' => $email_alerts,
                        'push_notifications' => $push_notify,
                    );
                }
                echo json_encode($json);
                die();
            } else {
                $result = 'false';
                $response = 'User does not exists';
                $json = array(
                    'result' => $result,
                    'response' => $response
                );
                echo json_encode($json);
                die();
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response
            );
            echo json_encode($json);
            die();
        }
    }

    public function actionSaveRoute() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $washrequest_id = Yii::app()->request->getParam('wash_request_id');
        $route = Yii::app()->request->getParam('route');
        if (isset($washrequest_id) && !empty($washrequest_id) && isset($route) && !empty($route)) {
            $route_m = new WashReqRoute();
            $route_m->wash_request_id = $washrequest_id;
            $route_m->route = $route;
            if ($route_m->save(false)) {
                $result = 'TRUE';
                $response = 'Route Save Successfully';
            } else {
                $result = 'false';
                $response = 'Not Saved Successfully';
            }
        } else {
            $result = 'false';
            $response = 'Not Saved Successfully';
        }
        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionGetSavedRoute() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $route = "No Route Found";
        $washrequest_id = Yii::app()->request->getParam('wash_request_id');
        if (isset($washrequest_id) && !empty($washrequest_id)) {
            $route_m = WashReqRoute::model()->findByAttributes(array('wash_request_id' => $washrequest_id));

            if ($route_m) {
                $result = 'TRUE';
                $response = 'Route Found';
                $route = $route_m->route;
            } else {
                $result = 'false';
                $response = 'No Route Found';
                $route = "No Route Found";
            }
        } else {
            $result = 'false';
            $response = 'No Route Found';
            $route = "No Route Found";
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'route' => $route
        );
        echo json_encode($json);
    }

    /**
     * * Returns customer details.
     * */
    public function actionprofiledetails() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('customerid');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $customerid = $this->aes256cbc_crypt($customerid, 'd', AES256CBC_API_PASS);
        }

        if ($customerid) {
            $CustomerExpansionRequestExist = Download::model()->findByAttributes(['customer_id' => $customerid]);
            if ($CustomerExpansionRequestExist) {
                $Download = "1";
            } else {
                $Download = "0";
            }
        }

        if ((isset($customerid) && !empty($customerid))) {
            $customers_id = Customers::model()->findByAttributes(array("id" => $customerid));
            if (count($customers_id) > 0) {

                $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1")->bindValue(':customer_id', $customerid, PDO::PARAM_STR)->queryAll();

                $location_details = Yii::app()->db->createCommand("SELECT * FROM customer_locations WHERE customer_id = :customer_id")->bindValue(':customer_id', $customerid, PDO::PARAM_STR)->queryAll();


                $locations = array();

                if (count($location_details) > 0) {
                    foreach ($location_details as $sloc) {
                        $locations[] = array(
                            'location_id' => $sloc['id'],
                            'location_title' => $sloc['location_title'],
                            'location_address' => $sloc['location_address'],
                            'street_name' => $sloc['street_name'],
                            'city' => $sloc['city'],
                            'zipcode' => $sloc['zipcode'],
                            'state' => $sloc['state'],
                            'actual_longitude' => $sloc['actual_longitude'],
                            'actual_latitude' => $sloc['actual_latitude'],
                            'is_editable' => $sloc['is_editable']
                        );
                    }
                }


                $vehicle_details = Yii::app()->db->createCommand("SELECT * FROM customer_vehicals WHERE customer_id = :customer_id")->bindValue(':customer_id', $customerid, PDO::PARAM_STR)->queryAll();


                $vehicles = array();

                if (count($vehicle_details) > 0) {
                    foreach ($vehicle_details as $veh) {
                        $vehicles[] = array(
                            'vehicle_id' => $veh['id'],
                            'vehicle_make' => $veh['brand_name'],
                            'vehicle_model' => $veh['model_name'],
                            'vehicle_no' => $veh['vehicle_no'],
                            'vehicle_image' => $veh['vehicle_image']
                        );
                    }
                }

                $cust_id = $customers_id->id;
                if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                    $cust_id = $this->aes256cbc_crypt($cust_id, 'e', AES256CBC_API_PASS);
                }


                $json = array(
                    'result' => 'true',
                    'response' => 'Customer details',
                    'customerid' => $cust_id,
                    'email' => $customers_id->email,
                    'first_name' => $customers_id->first_name,
                    'last_name' => $customers_id->last_name,
                    'customername' => $customers_id->first_name . " " . $customers_id->last_name,
                    'image' => $customers_id->image,
                    'contact_number' => $customers_id->contact_number,
                    'how_hear_mw' => $customers_id->how_hear_mw,
                    'email_alerts' => $customers_id->email_alerts,
                    'push_notifications' => $customers_id->push_notifications,
                    'phone_verified' => $customers_id->phone_verified,
                    'wash_points' => $customers_id->fifth_wash_points,
                    'is_first_wash' => $customers_id->is_first_wash,
                    'braintree_id' => $customers_id->braintree_id,
                    'client_position' => $customers_id->client_position,
                    'rating' => $customers_id->rating,
                    'customer_locations' => $locations,
                    'customer_vehicles' => $vehicles,
                    'last_used_device' => $clientdevices,
                    'is_schedule_popup_shown' => $customers_id->is_schedule_popup_shown,
                    'customer_notes' => $customers_id->notes,
                    'Download' => $Download,
                );
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid Customer'
                );
            }
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }
        echo json_encode($json);
        die();
    }

    /* public function actiondeletecustomer(){

      if(Yii::app()->request->getParam('key') != API_KEY){
      echo "Invalid api key";
      die();
      }

      $customers_id = Yii::app()->request->getParam('customers_id');
      $customer = Customers::model()->findByAttributes(array("id"=>$customers_id));
      if(!empty($customer)){
      $customer->delete();
      //$rides = Rids::model()->findByAttributes(array('customers_id'=> $customers_id));
      //if(!empty($rides)) $likes->deleteAll('customers_id='. $customers_id);

      $response = 'User with all respected data is deleted successfully';
      $json = array(
      'result' => true,
      'response' => $response,
      'Deleted' => true
      );
      }else{
      $response = 'User doesnot exists, Please provide valid customer ID';
      $json = array(
      'result' => false,
      'response' => $response,
      'Deleted' => false
      );
      }
      echo json_encode($json);
      exit;
      } */

    public function actionAddLocation() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $customer_id = Yii::app()->request->getParam('customer_id');
        $location_title = Yii::app()->request->getParam('location_title');
        $location_address = Yii::app()->request->getParam('location_address');
        $actual_longitude = Yii::app()->request->getParam('actual_longitude');
        $actual_latitude = Yii::app()->request->getParam('actual_latitude');
        $street_name = Yii::app()->request->getParam('street_name');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        if ($location_title) {
            $location_title = strtolower($location_title);
            $location_title = ucfirst($location_title);
        }

        $location = array();

        if ((isset($customer_id) && !empty($customer_id)) &&
                (isset($location_title) && !empty($location_title)) &&
                (isset($location_address) && !empty($location_address))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $customer_check = Customers::model()->findAllByAttributes(array("id" => $customer_id));
            //$location_limit_check = CustomerLocation::model()->findAllByAttributes(array("customer_id"=>$customer_id));

            $cust_address_check = CustomerLocation::model()->findAllByAttributes(array("customer_id" => $customer_id, "location_title" => $location_title));

            if (!count($customer_check)) {
                $result = 'false';
                $response = 'Invalid customer';
            } else {
                if (!count($cust_address_check)) {
                    $locationdata = array(
                        'customer_id' => $customer_id,
                        'location_title' => $location_title,
                        'location_address' => $location_address,
                        'actual_longitude' => $actual_longitude,
                        'actual_latitude' => $actual_latitude,
                        'street_name' => $street_name,
                        'city' => $city,
                        'state' => $state,
                        'zipcode' => $zipcode
                    );

                    $locationdata = array_filter($locationdata);
                    $model = new CustomerLocation;
                    $model->attributes = $locationdata;
                    if ($model->save(false)) {
                        $location_id = Yii::app()->db->getLastInsertID();
                    }
                } else {

                    foreach ($cust_address_check as $cust_address) {
                        if ($cust_address->location_title == $location_title) {
                            $location_id = $cust_address->id;
                            break;
                        }
                    }

                    CustomerLocation::model()->updateByPk($location_id, array('location_address' => $location_address, 'street_name' => $street_name, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'actual_longitude' => $actual_longitude, 'actual_latitude' => $actual_latitude));
                }

                $result = 'true';
                $response = 'Location added';

                if (($result == 'true') && ($admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'admin_username' => $admin_username,
                        'action' => 'addlocation',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'location_id' => $location_id
        );
        echo json_encode($json);
    }

    public function actiongetlocations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $customer_id = Yii::app()->request->getParam('customer_id');
        $all_locations = array();
        if ((isset($customer_id) && !empty($customer_id))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $customer_check = Customers::model()->findAllByAttributes(array("id" => $customer_id));

            if (!count($customer_check)) {
                $result = 'false';
                $response = 'Invalid customer';
            } else {
                $locations = CustomerLocation::model()->findAllByAttributes(array("customer_id" => $customer_id));
                //var_dump($locations);
                if (count($locations)) {
                    $result = 'true';
                    $response = 'Customer Locations';
                    foreach ($locations as $loc) {
                        $all_locations[] = array("id" => $loc->id, "title" => $loc->location_title, "address" => $loc->location_address, "lat" => $loc->actual_latitude, "lng" => $loc->actual_longitude);
                    }
                } else {
                    $result = 'false';
                    $response = 'No Customer locations found';
                }
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'locations' => $all_locations
        );
        echo json_encode($json);
    }

    public function actiongetlocationbyid() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $customer_id = Yii::app()->request->getParam('customer_id');
        $location_id = Yii::app()->request->getParam('location_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $all_locations = new stdClass();
        if ((isset($customer_id) && !empty($customer_id)) && (isset($location_id) && !empty($location_id))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $customer_check = Customers::model()->findAllByAttributes(array("id" => $customer_id));
            $loc_check = CustomerLocation::model()->findAllByAttributes(array("id" => $location_id, "customer_id" => $customer_id));

            if (!count($customer_check)) {
                $result = 'false';
                $response = 'Invalid customer';
            } else if (!count($loc_check)) {
                $result = 'false';
                $response = 'Invalid location';
            } else {
                $locations = CustomerLocation::model()->findAllByAttributes(array("id" => $location_id, "customer_id" => $customer_id));
                //var_dump($locations);
                if (count($locations)) {
                    $result = 'true';
                    $response = 'Customer Location';
                    foreach ($locations as $loc) {

                        $all_locations->id = $loc->id;
                        $all_locations->title = $loc->location_title;
                        $all_locations->address = $loc->location_address;
                        $all_locations->street_name = $loc->street_name;
                        $all_locations->city = $loc->city;
                        $all_locations->state = $loc->state;
                        $all_locations->zipcode = $loc->zipcode;
                        $all_locations->lat = $loc->actual_latitude;
                        $all_locations->lng = $loc->actual_longitude;
                    }
                } else {
                    $result = 'false';
                    $response = 'No Customer location found';
                }
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'location_details' => $all_locations
        );
        echo json_encode($json);
    }

    public function actionUpdateLocations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $location_id = Yii::app()->request->getParam('location_id');
        $location_title = Yii::app()->request->getParam('location_title');
        $location_address = Yii::app()->request->getParam('location_address');
        $actual_longitude = Yii::app()->request->getParam('actual_longitude');
        $actual_latitude = Yii::app()->request->getParam('actual_latitude');

        $core_location_id = 0;
        $location = array();

        if ((isset($location_id) && !empty($location_id)) &&
                (isset($location_title) && !empty($location_title)) &&
                (isset($location_address) && !empty($location_address)) &&
                (isset($actual_longitude) && !empty($actual_longitude)) &&
                (isset($actual_latitude) && !empty($actual_latitude))) {
            $location_exist = CustomerLocation::model()->findByAttributes(array("id" => $location_id));

            if (count($location_exist) > 0) {

                $resUpdate = false;
                try {
                    $location = array('location_title' => $location_title,
                        'location_address' => $location_address,
                        'actual_longitude' => $actual_longitude,
                        'actual_latitude' => $actual_latitude,
                        'core_location_id' => $core_location_id);

                    $resUpdate = CustomerLocation::model()->updateAll($location, 'id=:id', array(':id' => $location_id));

                    $location['location_id'] = $location_id;

                    if ($resUpdate) {
                        $result = 'true';
                        $response = 'Location details updated';
                    } else {
                        $response = 'No changes to update';
                    }
                } catch (Exception $e) {
                    $response = 'Internal error';
                }
            } else {
                $response = "Invalid Location";
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'location' => $location
        );
        echo json_encode($json);
    }

    public function actionDeleteLocation() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $location_id = Yii::app()->request->getParam('location_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $admin_username = Yii::app()->request->getParam('admin_username');

        if (isset($location_id) && !empty($location_id)) {
            $location_exist = CustomerLocation::model()->findByAttributes(array("id" => $location_id));

            if (count($location_exist) > 0) {

                $resUpdate = false;

                $resUpdate = CustomerLocation::model()->deleteAll('id=:id', array(':id' => $location_id));

                if ($resUpdate) {
                    $result = 'true';
                    $response = 'Location deleted';
                } else {
                    $response = 'Location not deleted';
                }

                if (($result == 'true') && ($admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'admin_username' => $admin_username,
                        'action' => 'deletelocation',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            } else {
                $response = "Invalid Location";
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actionAddVehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $result = 'false';
        $response = 'Pass the required parameters';
        $vehicle_no = "N/A";
        $vehicle_type = 'S';
        $vehicle_build = '';
        $customer_id = Yii::app()->request->getParam('customer_id');
        if (Yii::app()->request->getParam('vehicle_no'))
            $vehicle_no = Yii::app()->request->getParam('vehicle_no');
        if (Yii::app()->request->getParam('vehicle_build'))
            $vehicle_build = Yii::app()->request->getParam('vehicle_build');

        $brand_name = Yii::app()->request->getParam('brand_name');
        $model_name = Yii::app()->request->getParam('model_name');
        $vehicle_category = '';
        $vehicle_source_id = 0;
        if (Yii::app()->request->getParam('vehicle_category'))
            $vehicle_category = Yii::app()->request->getParam('vehicle_category');
        $vehicle_image = Yii::app()->request->getParam('vehicle_image');
        $new_pack_name = '';
        if (Yii::app()->request->getParam('new_pack_name'))
            $new_pack_name = Yii::app()->request->getParam('new_pack_name');
        $car_pack = '';
        if (Yii::app()->request->getParam('car_pack'))
            $car_pack = Yii::app()->request->getParam('car_pack');
        $wash_request_id = '';
        if (Yii::app()->request->getParam('wash_request_id'))
            $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $admin_username = '';
        if (Yii::app()->request->getParam('admin_username'))
            $admin_username = Yii::app()->request->getParam('admin_username');
        $add_log = '';
        if (Yii::app()->request->getParam('add_log'))
            $add_log = Yii::app()->request->getParam('add_log');
        if (Yii::app()->request->getParam('vehicle_type'))
            $vehicle_type = Yii::app()->request->getParam('vehicle_type');

        $pet_hair = 0;
        if (Yii::app()->request->getParam('pet_hair'))
            $pet_hair = Yii::app()->request->getParam('pet_hair');
        $lifted_vehicle = 0;
        if (Yii::app()->request->getParam('lifted_vehicle'))
            $lifted_vehicle = Yii::app()->request->getParam('lifted_vehicle');
        $exthandwax_addon = 0;
        if (Yii::app()->request->getParam('exthandwax_addon'))
            $exthandwax_addon = Yii::app()->request->getParam('exthandwax_addon');
        $extplasticdressing_addon = 0;
        if (Yii::app()->request->getParam('extplasticdressing_addon'))
            $extplasticdressing_addon = Yii::app()->request->getParam('extplasticdressing_addon');
        $extclaybar_addon = 0;
        if (Yii::app()->request->getParam('extclaybar_addon'))
            $extclaybar_addon = Yii::app()->request->getParam('extclaybar_addon');
        $waterspotremove_addon = 0;
        if (Yii::app()->request->getParam('waterspotremove_addon'))
            $waterspotremove_addon = Yii::app()->request->getParam('waterspotremove_addon');
        $upholstery_addon = 0;
        if (Yii::app()->request->getParam('upholstery_addon'))
            $upholstery_addon = Yii::app()->request->getParam('upholstery_addon');
        $floormat_addon = 0;
        if (Yii::app()->request->getParam('floormat_addon'))
            $floormat_addon = Yii::app()->request->getParam('floormat_addon');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        $vehicle = array();
        if ((isset($customer_id) && !empty($customer_id)) &&
                (isset($brand_name) && !empty($brand_name)) &&
                (isset($model_name) && !empty($model_name)) &&
                (isset($vehicle_image) && !empty($vehicle_image))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $customer_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            if (count($customer_exists) > 0) {

                $qrVehicles = Yii::app()->db->createCommand('SELECT * FROM customer_vehicals WHERE customer_id = :customer_id')->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)->queryAll();
                /* if(count($qrVehicles) >= 5){
                  $response = 'Vehicle limit excceded. You can add upto 5 vehicles.';
                  }
                  else{ */
                $image = 'no_pic.jpg';
                $siteUrl = Yii::app()->getBaseUrl(true);
                if ((!empty($vehicle_image)) && (strpos($vehicle_image, 'api/images/veh_img') === false)) {

                    $directorypath1 = realpath(Yii::app()->basePath . '/../images/veh_img');
                    $img = str_replace('data:image/PNG;base64,', '', $vehicle_image);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $md5 = md5(uniqid(rand(), true));
                    $name = $customer_id . '_' . $md5 . ".jpg";
                    $path = $directorypath1 . '/' . $name;
                    $SiteUrl = Yii::app()->getBaseUrl(true);
                    $image = $SiteUrl . '/images/veh_img/' . $customer_id . '_' . $md5 . ".jpg";
                    file_put_contents($path, $data);
                }

                if (strpos($vehicle_image, 'api/images/veh_img') !== false) {
                    $image = $vehicle_image;
                }

                $vehicle_check = '';

                if ($vehicle_build == 'classic') {

                    $vehicle_check = Yii::app()->db->createCommand('SELECT * FROM all_classic_vehicles WHERE make = :make AND model= :model')
                            ->bindValue(':make', $brand_name, PDO::PARAM_STR)
                            ->bindValue(':model', $model_name, PDO::PARAM_STR)
                            ->queryAll();
                } else {

                    $vehicle_check = Yii::app()->db->createCommand('SELECT * FROM all_vehicles WHERE make = :make AND model= :model')
                            ->bindValue(':make', $brand_name, PDO::PARAM_STR)
                            ->bindValue(':model', $model_name, PDO::PARAM_STR)
                            ->queryAll();
                }

                if (count($vehicle_check) > 0) {
                    $vehicle_type = $vehicle_check[0]['type'];
                    $vehicle_category = $vehicle_check[0]['category'];
                    $vehicle_source_id = $vehicle_check[0]['id'];
                } else {
                    $vehicle_type = 'N/A';
                    $vehicle_category = 'N/A';
                    $vehicle_source_id = 0;
                }

                // $vehicle_type = $allvehicle_obj->type;
                //echo $vehicle_type;
                //exit;
                try {
                    $resIns = Yii::app()->db->createCommand()
                            ->insert('customer_vehicals', array('vehicle_source_id' => $vehicle_source_id, 'customer_id' => $customer_id,
                        'vehicle_no' => $vehicle_no,
                        'brand_name' => $brand_name,
                        'model_name' => $model_name,
                        'vehicle_image' => $image,
                        'vehicle_type' => $vehicle_type,
                        'vehicle_category' => $vehicle_category,
                        'vehicle_build' => $vehicle_build,
                        'new_pack_name' => $new_pack_name,
                        'pet_hair' => $pet_hair,
                        'lifted_vehicle' => $lifted_vehicle,
                        'exthandwax_addon' => $exthandwax_addon,
                        'extplasticdressing_addon' => $extplasticdressing_addon,
                        'extclaybar_addon' => $extclaybar_addon,
                        'waterspotremove_addon' => $waterspotremove_addon,
                        'upholstery_addon' => $upholstery_addon,
                        'floormat_addon' => $floormat_addon
                    ));
                } catch (Exception $e) {
                    //echo $e;
                }
                //var_dump($resIns);
                if ($resIns) {
                    $result = 'true';
                    $response = 'Vehicle added';
                    $qrVehicles = Yii::app()->db->createCommand()
                            ->select('*')->from('customer_vehicals')
                            ->where("id='" . Yii::app()->db->getLastInsertID() . "'", array())
                            ->queryRow();

                    $vehicle = array('id' => $qrVehicles['id'],
                        'vehicle_no' => $vehicle_no,
                        'brand_name' => $brand_name,
                        'model_name' => $model_name,
                        'vehicle_image' => $image,
                        'vehicle_type' => $vehicle_type, 'vehicle_category' => $vehicle_category, 'vehicle_build' => $vehicle_build);

                    if ($add_log == 'true') {
                        $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                        $agent_company_id = 0;
                        if (count($agent_detail))
                            $agent_company_id = $agent_detail->real_washer_id;
                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_company_id,
                            'action' => 'adminaddcar',
                            'addi_detail' => $brand_name . " " . $model_name . " " . $car_pack,
                            'admin_username' => $admin_username,
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                    }

                    /* --------- add vehicle by agent in current wash ------------ */

                    if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($new_pack_name) && !empty($new_pack_name))) {


                        $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

                        if (count($wash_request_exists)) {
                            $packs = $wash_request_exists->package_list;
                            $cars = $wash_request_exists->car_list;
                            $cars_arr = explode(",", $cars);
                            $packs_arr = explode(",", $packs);
                            array_push($cars_arr, $qrVehicles['id']);
                            array_push($packs_arr, $new_pack_name);
                            $new_packs = implode(",", $packs_arr);
                            $new_cars = implode(",", $cars_arr);

                            $coupon_amount = 0;
                            if ($wash_request_exists->coupon_code) {
                                $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $wash_request_exists->coupon_code));
                                if (count($coupon_check)) {
                                    if (strpos($new_packs, 'Premium') !== false) {
                                        $coupon_amount = number_format($coupon_check->premium_amount, 2, '.', '');
                                    } else {
                                        $coupon_amount = number_format($coupon_check->deluxe_amount, 2, '.', '');
                                    }
                                }
                            }

                            Washingrequests::model()->updateByPk($wash_request_id, array('package_list' => $new_packs, 'car_list' => $new_cars, 'coupon_discount' => $coupon_amount));

                            $cust_vehicle_data = Vehicle::model()->findByPk($qrVehicles['id']);

                            /* -------- pet hair / lift / addons check --------- */


                            if ($cust_vehicle_data->pet_hair) {
                                $pet_hair_vehicles_old = '';
                                $pet_hair_vehicles_old = $wash_request_exists->pet_hair_vehicles;
                                $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles_old);
                                if (!in_array($qrVehicles['id'], $pet_hair_vehicles_arr))
                                    array_push($pet_hair_vehicles_arr, $qrVehicles['id']);
                                $pet_hair_vehicles_new = implode(",", $pet_hair_vehicles_arr);
                                $pet_hair_vehicles_new = trim($pet_hair_vehicles_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('pet_hair_vehicles' => $pet_hair_vehicles_new));
                            }

                            if ($cust_vehicle_data->lifted_vehicle) {
                                $lifted_vehicles_old = '';
                                $lifted_vehicles_old = $wash_request_exists->lifted_vehicles;
                                $lifted_vehicles_arr = explode(",", $lifted_vehicles_old);
                                if (!in_array($qrVehicles['id'], $lifted_vehicles_arr))
                                    array_push($lifted_vehicles_arr, $qrVehicles['id']);
                                $lifted_vehicles_new = implode(",", $lifted_vehicles_arr);
                                $lifted_vehicles_new = trim($lifted_vehicles_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('lifted_vehicles' => $lifted_vehicles_new));
                            }

                            if ($cust_vehicle_data->exthandwax_addon) {
                                $exthandwax_addon_old = '';
                                $exthandwax_addon_old = $wash_request_exists->exthandwax_vehicles;
                                $exthandwax_addon_arr = explode(",", $exthandwax_addon_old);
                                if (!in_array($qrVehicles['id'], $exthandwax_addon_arr))
                                    array_push($exthandwax_addon_arr, $qrVehicles['id']);
                                $exthandwax_addon_new = implode(",", $exthandwax_addon_arr);
                                $exthandwax_addon_new = trim($exthandwax_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('exthandwax_vehicles' => $exthandwax_addon_new));
                            }


                            if ($cust_vehicle_data->extplasticdressing_addon) {
                                $extplasticdressing_addon_old = '';
                                $extplasticdressing_addon_old = $wash_request_exists->extplasticdressing_vehicles;
                                $extplasticdressing_addon_arr = explode(",", $extplasticdressing_addon_old);
                                if (!in_array($qrVehicles['id'], $extplasticdressing_addon_arr))
                                    array_push($extplasticdressing_addon_arr, $qrVehicles['id']);
                                $extplasticdressing_addon_new = implode(",", $extplasticdressing_addon_arr);
                                $extplasticdressing_addon_new = trim($extplasticdressing_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('extplasticdressing_vehicles' => $extplasticdressing_addon_new));
                            }


                            if ($cust_vehicle_data->extclaybar_addon) {
                                $extclaybar_addon_old = '';
                                $extclaybar_addon_old = $wash_request_exists->extclaybar_vehicles;
                                $extclaybar_addon_arr = explode(",", $extclaybar_addon_old);
                                if (!in_array($qrVehicles['id'], $extclaybar_addon_arr))
                                    array_push($extclaybar_addon_arr, $qrVehicles['id']);
                                $extclaybar_addon_new = implode(",", $extclaybar_addon_arr);
                                $extclaybar_addon_new = trim($extclaybar_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('extclaybar_vehicles' => $extclaybar_addon_new));
                            }

                            if ($cust_vehicle_data->waterspotremove_addon) {
                                $waterspotremove_addon_old = '';
                                $waterspotremove_addon_old = $wash_request_exists->waterspotremove_vehicles;
                                $waterspotremove_addon_arr = explode(",", $waterspotremove_addon_old);
                                if (!in_array($qrVehicles['id'], $waterspotremove_addon_arr))
                                    array_push($waterspotremove_addon_arr, $qrVehicles['id']);
                                $waterspotremove_addon_new = implode(",", $waterspotremove_addon_arr);
                                $waterspotremove_addon_new = trim($waterspotremove_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('waterspotremove_vehicles' => $waterspotremove_addon_new));
                            }

                            if ($cust_vehicle_data->upholstery_addon) {
                                $upholstery_addon_old = '';
                                $upholstery_addon_old = $wash_request_exists->upholstery_vehicles;
                                $upholstery_addon_arr = explode(",", $upholstery_addon_old);
                                if (!in_array($qrVehicles['id'], $upholstery_addon_arr))
                                    array_push($upholstery_addon_arr, $qrVehicles['id']);
                                $upholstery_addon_new = implode(",", $upholstery_addon_arr);
                                $upholstery_addon_new = trim($upholstery_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('upholstery_vehicles' => $upholstery_addon_new));
                            }

                            if ($cust_vehicle_data->floormat_addon) {
                                $floormat_addon_old = '';
                                $floormat_addon_old = $wash_request_exists->floormat_vehicles;
                                $floormat_addon_arr = explode(",", $floormat_addon_old);
                                if (!in_array($qrVehicles['id'], $floormat_addon_arr))
                                    array_push($floormat_addon_arr, $qrVehicles['id']);
                                $floormat_addon_new = implode(",", $floormat_addon_arr);
                                $floormat_addon_new = trim($floormat_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('floormat_vehicles' => $floormat_addon_new));
                            }

                            $newpriceobj = Washingplans::model()->findByAttributes(array("vehicle_type" => $cust_vehicle_data->vehicle_type, "title" => $new_pack_name));
                            $newprice = 0;
                            if (count($newpriceobj)) {
                                $newprice = $newpriceobj->price;
                            } else {
                                if ($new_pack_name == 'Express')
                                    $newprice = 19.99;
                                if ($new_pack_name == 'Deluxe')
                                    $newprice = 24.99;
                                if ($new_pack_name == 'Premium')
                                    $newprice = 59.99;
                            }

                            $washpricehistorymodel = new WashPricingHistory;
                            $washpricehistorymodel->wash_request_id = $wash_request_id;
                            $washpricehistorymodel->vehicle_id = $qrVehicles['id'];
                            $washpricehistorymodel->package = $new_pack_name;
                            $washpricehistorymodel->vehicle_price = $newprice;
                            $washpricehistorymodel->pet_hair = $cust_vehicle_data->pet_hair;
                            $washpricehistorymodel->lifted_vehicle = $cust_vehicle_data->lifted_vehicle;
                            $washpricehistorymodel->exthandwax_addon = $cust_vehicle_data->exthandwax_addon;
                            $washpricehistorymodel->extplasticdressing_addon = $cust_vehicle_data->extplasticdressing_addon;
                            $washpricehistorymodel->extclaybar_addon = $cust_vehicle_data->extclaybar_addon;
                            $washpricehistorymodel->waterspotremove_addon = $cust_vehicle_data->waterspotremove_addon;
                            $washpricehistorymodel->upholstery_addon = $cust_vehicle_data->upholstery_addon;
                            $washpricehistorymodel->floormat_addon = $cust_vehicle_data->floormat_addon;
                            $washpricehistorymodel->safe_handling = 1;
                            $washpricehistorymodel->bundle_disc = 0;
                            $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                            $washpricehistorymodel->save(false);

                            $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                            $logdata = array(
                                'wash_request_id' => $wash_request_id,
                                'agent_id' => $wash_request_exists->agent_id,
                                'agent_company_id' => $agent_detail->real_washer_id,
                                'action' => 'washeraddcar',
                                'addi_detail' => $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name . " (" . $new_pack_name . ")",
                                'action_date' => date('Y-m-d H:i:s'));
                            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                        }
                    }


                    /* --------- add vehicle by agent in current wash end ------------ */
                }
                else {
                    $response = 'Internal error';
                }
                //}
            } else {
                $response = 'Invalid customer';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicle' => $vehicle
        );
        echo json_encode($json);
    }

    public function actionUpdateVehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $vehicle_no = '';
        if (Yii::app()->request->getParam('vehicle_no'))
            $vehicle_no = Yii::app()->request->getParam('vehicle_no');
        $brand_name = Yii::app()->request->getParam('brand_name');
        $model_name = Yii::app()->request->getParam('model_name');
        $vehicle_image = Yii::app()->request->getParam('vehicle_image');
        $vehicle_type = '';
        if (Yii::app()->request->getParam('vehicle_type'))
            $vehicle_type = Yii::app()->request->getParam('vehicle_type');
        $vehicle_category = '';
        if (Yii::app()->request->getParam('vehicle_category'))
            $vehicle_category = Yii::app()->request->getParam('vehicle_category');
        $vehicle_build = '';
        if (Yii::app()->request->getParam('vehicle_build'))
            $vehicle_build = Yii::app()->request->getParam('vehicle_build');
        $vehicle = array();
        if ((isset($vehicle_id) && !empty($vehicle_id)) &&
                (isset($brand_name) && !empty($brand_name)) &&
                (isset($model_name) && !empty($model_name)) &&
                (isset($vehicle_image) && !empty($vehicle_image))) {
            $vehicle_exists = Vehicle::model()->findByAttributes(array("id" => $vehicle_id));
            if (count($vehicle_exists) > 0) {
                $image = 'no_pic.jpg';
                if (!empty($vehicle_image)) {

                    $directorypath1 = realpath(Yii::app()->basePath . '/../images/veh_img');

                    $img = str_replace('data:image/PNG;base64,', '', $vehicle_image);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $md5 = md5(uniqid(rand(), true));
                    $name = $customer_id . '_' . $md5 . ".jpg";
                    $path = $directorypath1 . '/' . $name;

                    $image = $customer_id . '_' . $md5 . ".jpg";

                    file_put_contents($path, $data);
                }
                $resUpdate = false;
                try {

                    if (!$vehicle_type) {
                        $vehicle_type = $vehicle_exists->vehicle_type;
                    }

                    if (!$vehicle_no) {
                        $vehicle_no = $vehicle_exists->vehicle_no;
                    }

                    if (!$vehicle_category) {
                        $vehicle_category = $vehicle_exists->vehicle_category;
                    }

                    if (!$vehicle_build) {
                        $vehicle_build = $vehicle_exists->vehicle_build;
                    }


                    $vehicle = array('vehicle_no' => $vehicle_no,
                        'brand_name' => $brand_name,
                        'model_name' => $model_name,
                        'vehicle_image' => $image,
                        'vehicle_type' => $vehicle_type,
                        'vehicle_category' => $vehicle_category,
                        'vehicle_build' => $vehicle_build
                    );

                    $resUpdate = Yii::app()->db->createCommand()->
                            update('customer_vehicals', $vehicle, "id=:id", array(":id" => $vehicle_id));
                    $vehicle['id'] = $vehicle_id;
                    if ($resUpdate) {
                        $result = 'true';
                        $response = 'Vehicle details updated';
                    } else {
                        $response = 'No changes to update';
                    }
                } catch (Exception $e) {
                    $response = 'Internal error';
                }
            } else {
                $response = "Invalid vehicle";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicle' => $vehicle
        );
        echo json_encode($json);
    }

    public function actionGetVehicals() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $result = 'false';
        $response = 'Pass the required parameters';

        $customer_id = Yii::app()->request->getParam('customer_id');
        $vehicles = array();

        if ((isset($customer_id) && !empty($customer_id))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customer_exists = Customers::model()->findByAttributes(array("id" => $customer_id));

            if (count($customer_exists) > 0) {
                $result = 'true';
                $response = "Vehicles";
                $siteUrl = Yii::app()->getBaseUrl(true);


//                $qrVehicles = Yii::app()->db->createCommand()
//                        ->select('*')->from('customer_vehicals')
//                        ->where("customer_id=:customer_id AND hide_vehicle = 0", array(":customer_id" => $customer_id))
//                        ->order("id DESC")
//                        ->queryAll();
                $qrVehicles = Yii::app()->db->createCommand()
                        ->select('customer_vehicals.*')->from('customer_vehicals')
                        ->where("customer_id=:customer_id AND hide_vehicle = 0", array(":customer_id" => $customer_id))
                        ->join('all_vehicles v', 'customer_vehicals.vehicle_source_id=v.id')
                        ->order("customer_vehicals.id DESC")
                        ->queryAll();

                if (count($qrVehicles) > 0) {

                    foreach ($qrVehicles as $vehicle) {
                        $json['result'] = 'true';

                        $vehicles[] = array('id' => $vehicle['id'],
                            'vehicle_no' => $vehicle['vehicle_no'],
                            'brand_name' => $vehicle['brand_name'],
                            'model_name' => $vehicle['model_name'],
                            'vehicle_image' => $vehicle['vehicle_image'],
                            'vehicle_type' => $vehicle['vehicle_type'],
                            'vehicle_category' => $vehicle['vehicle_category'], 'vehicle_build' => $vehicle['vehicle_build']);
                    }
                }
            } else {
                $response = "Invalid customer";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicles' => $vehicles
        );

        echo json_encode($json);
    }

    public function actiongetvehiclebyid() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $vehicle_id = Yii::app()->request->getParam('vehicle_id');

        $vehicle = array();
        if ((isset($vehicle_id) && !empty($vehicle_id))) {
            $vehicle_exists = Vehicle::model()->findByAttributes(array("id" => $vehicle_id));
            if (count($vehicle_exists) > 0) {
                $result = 'true';
                $response = 'Vehicle details';
                $washing_plan_express = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_exists->vehicle_type, "title" => "Express"));
                if (count($washing_plan_express))
                    $expr_price = $washing_plan_express->price;
                else
                    $expr_price = "19.99";

                $washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_exists->vehicle_type, "title" => "Deluxe"));
                if (count($washing_plan_deluxe))
                    $delx_price = $washing_plan_deluxe->price;
                else
                    $delx_price = "24.99";

                $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_exists->vehicle_type, "title" => "Premium"));
                if (count($washing_plan_prem))
                    $prem_price = $washing_plan_prem->price;
                else
                    $prem_price = "59.99";

                $vehicle = array(
                    'vehicle_no' => $vehicle_exists->vehicle_no,
                    'brand_name' => $vehicle_exists->brand_name,
                    'model_name' => $vehicle_exists->model_name,
                    'vehicle_image' => $vehicle_exists->vehicle_image,
                    'vehicle_type' => $vehicle_exists->vehicle_type,
                    'vehicle_category' => $vehicle_exists->vehicle_category,
                    'vehicle_build' => $vehicle_exists->vehicle_build,
                    'vehicle_status' => $vehicle_exists->status,
                    'eco_friendly' => $vehicle_exists->eco_friendly,
                    'damage_points' => $vehicle_exists->damage_points,
                    'express_price' => $expr_price,
                    'deluxe_price' => $delx_price,
                    'premium_price' => $prem_price
                );
            }else {
                $response = "Invalid vehicle";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicle' => $vehicle
        );
        echo json_encode($json);
    }

    public function actionDeleteVehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $customer_id = Yii::app()->request->getParam('customer_id');
        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $vehicles = array();

        if ((isset($customer_id) && !empty($customer_id)) && (isset($vehicle_id) && !empty($vehicle_id))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $customer_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            $vehicle_exists = Vehicle::model()->findByAttributes(array("id" => $vehicle_id, "customer_id" => $customer_id));
            if (!count($customer_exists)) {
                $response = "Invalid customer";
            } else if (!count($vehicle_exists)) {
                $response = "Invalid vehicle";
            } else {



                $response = "Vehicle deleted";
                $result = true;
                $siteUrl = Yii::app()->getBaseUrl(true);


                $qrVehicles = Yii::app()->db->createCommand()
                        ->select('*')->from('customer_vehicals')
                        ->where("customer_id=:customer_id AND id=:id", array(":customer_id" => $customer_id, ":id" => $vehicle_id))
                        ->queryAll();

                if (count($qrVehicles) > 0) {

                    foreach ($qrVehicles as $vehicle) {
                        $json['result'] = 'true';
                        if (AES256CBC_STATUS == 1) {
                            $vehicles[] = array('id' => $vehicle['id'],
                                'customer_id' => $this->aes256cbc_crypt($vehicle['customer_id'], 'e', AES256CBC_API_PASS),
                                'vehicle_no' => $vehicle['vehicle_no'],
                                'brand_name' => $vehicle['brand_name'],
                                'model_name' => $vehicle['model_name'],
                                'vehicle_image' => $siteUrl . '/veh_img/' . $vehicle['vehicle_image'],
                                'vehicle_type' => $vehicle['vehicle_type']);
                        } else {
                            $vehicles[] = array('id' => $vehicle['id'],
                                'customer_id' => $vehicle['customer_id'],
                                'vehicle_no' => $vehicle['vehicle_no'],
                                'brand_name' => $vehicle['brand_name'],
                                'model_name' => $vehicle['model_name'],
                                'vehicle_image' => $siteUrl . '/veh_img/' . $vehicle['vehicle_image'],
                                'vehicle_type' => $vehicle['vehicle_type']);
                        }
                    }
                }

                // Vehicle::model()->deleteAll("customer_id='".$customer_id."' AND id='".$vehicle_id."'");
                Vehicle::model()->updateByPk($vehicle_id, array('hide_vehicle' => 1));
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicles' => $vehicles
        );

        echo json_encode($json);
    }

    public function actionAcceptnotification() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        /* $brand_name = Yii::app()->request->getParam('brand_name');
          $model_name = Yii::app()->request->getParam('model_name'); */
        $new_vehicle_confirm = Yii::app()->request->getParam('new_vehicle_confirm');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $vehicle_id = Yii::app()->request->getParam('vehicle_id');

        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($wash_request_id)) && !empty($new_vehicle_confirm) && (isset($vehicle_id) && !empty($vehicle_id))) {
            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            /* $wash_request_exists = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));
              $vehicle_details = Vehicle::model()->findByAttributes(array('id'=>$vehicle_id, 'customer_id'=>$wash_request_exists->customer_id));
              if($new_vehicle_confirm > 0){
              $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '6' ")->queryAll();
              $notify_msg = $pushmsg[0]['message'];

              $notify_msg = str_replace("[BRAND_NAME]",$vehicle_details->brand_name, $notify_msg);
              $notify_msg = str_replace("[MODEL_NAME]",$vehicle_details->model_name, $notify_msg);
              }

              $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$wash_request_exists->agent_id."' ORDER BY last_used DESC LIMIT 1")->queryAll();
              $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

              if((count($agentdevices)) && (!$agent_detail->block_washer))
              {
              foreach($agentdevices as $agdevice)
              {

              $device_type = strtolower($agdevice['device_type']);
              $notify_token = $agdevice['device_token'];
              $alert_type = "strong";

              $notify_msg = urlencode($notify_msg);

              $notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
              //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
              $ch = curl_init();
              curl_setopt($ch,CURLOPT_URL,$notifyurl);
              curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

              if($notify_msg) $notifyresult = curl_exec($ch);
              curl_close($ch);

              }
              } */
            $result = 'true';
            $response = 'action is completed!';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actioninspectingvehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $vehicle_id = Yii::app()->request->getParam('vehicle_id');

        if (!empty($customer_id) && isset($customer_id)) {
            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }


            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            $agents_id_check = Agents::model()->findByAttributes(array("id" => $washrequest_id_check->agent_id));
            if ($vehicle_id)
                $vehicle_details = Vehicle::model()->findByPk($vehicle_id);

            if ($vehicle_id) {
                $logdata = array(
                    'agent_id' => $washrequest_id_check->agent_id,
                    'wash_request_id' => $wash_request_id,
                    'agent_company_id' => $agents_id_check->real_washer_id,
                    'action' => 'washerstartinspection',
                    'addi_detail' => $vehicle_details->brand_name . " " . $vehicle_details->model_name,
                    'action_date' => date('Y-m-d H:i:s'));
            } else {
                $logdata = array(
                    'agent_id' => $washrequest_id_check->agent_id,
                    'wash_request_id' => $wash_request_id,
                    'agent_company_id' => $agents_id_check->real_washer_id,
                    'action' => 'washerstartinspection',
                    'addi_detail' => 'current vehicle',
                    'action_date' => date('Y-m-d H:i:s'));
            }


            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

            /* --- notification call --- */

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '39' ")->queryAll();
            $message = $pushmsg[0]['message'];

            foreach ($clientdevices as $ctdevice) {

                //echo $agentdetails['mobile_type'];
                $device_type = strtolower($ctdevice['device_type']);
                $notify_token = $ctdevice['device_token'];
                $alert_type = "schedule";
                $notify_msg = urlencode($message);

                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if ($notify_msg)
                    $notifyresult = curl_exec($ch);
                curl_close($ch);
            }
            $result = 'true';
            $response = 'notification sent';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionsetvehiclestatus() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $status = Yii::app()->request->getParam('status');
        $eco_friendly = Yii::app()->request->getParam('eco_friendly');
        $pet_hair = Yii::app()->request->getParam('pet_hair');
        $lifted_vehicle = Yii::app()->request->getParam('lifted_vehicle');
        $exthandwax_addon = 0;
        if (Yii::app()->request->getParam('exthandwax_addon'))
            $exthandwax_addon = Yii::app()->request->getParam('exthandwax_addon');
        $extplasticdressing_addon = 0;
        if (Yii::app()->request->getParam('extplasticdressing_addon'))
            $extplasticdressing_addon = Yii::app()->request->getParam('extplasticdressing_addon');
        $extclaybar_addon = 0;
        if (Yii::app()->request->getParam('extclaybar_addon'))
            $extclaybar_addon = Yii::app()->request->getParam('extclaybar_addon');
        $waterspotremove_addon = 0;
        if (Yii::app()->request->getParam('waterspotremove_addon'))
            $waterspotremove_addon = Yii::app()->request->getParam('waterspotremove_addon');
        $upholstery_addon = 0;
        if (Yii::app()->request->getParam('upholstery_addon'))
            $upholstery_addon = Yii::app()->request->getParam('upholstery_addon');
        $floormat_addon = 0;
        if (Yii::app()->request->getParam('floormat_addon'))
            $floormat_addon = Yii::app()->request->getParam('floormat_addon');
        $damage_points = Yii::app()->request->getParam('damage_points');
        $upgrade_pack = Yii::app()->request->getParam('upgrade_pack');
        $new_pack_name = '';
        if (Yii::app()->request->getParam('new_pack_name'))
            $new_pack_name = Yii::app()->request->getParam('new_pack_name');
        $edit_vehicle = Yii::app()->request->getParam('edit_vehicle');
        $remove_vehicle_from_kart = Yii::app()->request->getParam('remove_vehicle_from_kart');
        $new_vehicle_confirm = Yii::app()->request->getParam('new_vehicle_confirm');
        $draft_vehicle_id = Yii::app()->request->getParam('draft_vehicle_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $damage_pic = "hi";
//file_put_contents("setvehiclestatus.log",$wash_request_id."+".$vehicle_id."+".$status."+".$eco_friendly."+".$damage_points."\n",FILE_APPEND);
        if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($vehicle_id) && !empty($vehicle_id)) && (isset($status))) {
            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            if (!count($wash_request_exists)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {
                $cars = $wash_request_exists->car_list;

                if (strpos($cars, $vehicle_id) === false) {
                    $result = 'false';
                    $response = 'Invalid vehicle id';
                } else {

                    if (Yii::app()->request->getParam('reinspect_opt') == 1) {

                        $cust_vehicle_data = Vehicle::model()->findByAttributes(array("id" => $vehicle_id));
                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                        $log_detail = $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name;

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'agentreinspectopt',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                    }

                    if (Yii::app()->request->getParam('no_damage_opt') == 1) {

                        $cust_vehicle_data = Vehicle::model()->findByAttributes(array("id" => $vehicle_id));
                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                        $log_detail = $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name;

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'agentnodamageopt',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                    }

                    /* ------------ upgrade pack ------------- */

                    if ($upgrade_pack == 1) {

//Vehicle::model()->updateByPk($vehicle_id, array('pet_hair' => $pet_hair, 'lifted_vehicle' => $lifted_vehicle, 'new_pack_name' => $new_pack_name, 'exthandwax_addon' => $exthandwax_addon, 'extplasticdressing_addon' => $extplasticdressing_addon, 'extclaybar_addon' => $extclaybar_addon, 'waterspotremove_addon' => $waterspotremove_addon, 'upholstery_addon' => $upholstery_addon, 'floormat_addon' => $floormat_addon));
//Vehicle::model()->updateByPk($vehicle_id, array('upgrade_requested_at' => date('Y-m-d H:i:s')));
                        Washingrequests::model()->updateByPk($wash_request_id, array('upgrade_requested_at' => date('Y-m-d H:i:s')));

                        $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wash_request_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '21' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        foreach ($clientdevices as $ctdevice) {

                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "schedule";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                        /* --- notification call end --- */
                    }

                    if ($upgrade_pack == 2) {

                        $old_vehicle_data = Vehicle::model()->findByPk($vehicle_id);

                        $cust_vehicle_data = CustomerDraftVehicle::model()->findByAttributes(array("id" => $draft_vehicle_id));

                        Vehicle::model()->updateByPk($vehicle_id, array('pet_hair' => $cust_vehicle_data->pet_hair, 'lifted_vehicle' => $cust_vehicle_data->lifted_vehicle, 'new_pack_name' => $cust_vehicle_data->wash_package, 'exthandwax_addon' => $cust_vehicle_data->exthandwax_addon, 'extplasticdressing_addon' => $cust_vehicle_data->extplasticdressing_addon, 'extclaybar_addon' => $cust_vehicle_data->extclaybar_addon, 'waterspotremove_addon' => $cust_vehicle_data->waterspotremove_addon, 'upholstery_addon' => $cust_vehicle_data->upholstery_addon, 'floormat_addon' => $cust_vehicle_data->floormat_addon));

                        //$cust_vehicle_data = Vehicle::model()->findByPk($vehicle_id);
                        $log_addon_detail = "";
                        $log_detail = "";
                        $cars_arr_up = explode(",", $wash_request_exists->car_list);
                        $packs_arr_up = explode(",", $wash_request_exists->package_list);
                        $carkey = array_search($vehicle_id, $cars_arr_up);
                        $log_detail_olddata = "(" . $packs_arr_up[$carkey] . " w/";
                        $log_addon_detail_olddata = "";

                        /* -------- pet hair / lift / addons check --------- */

                        if ($old_vehicle_data->pet_hair) {
                            $log_addon_detail_olddata .= "Extra cleaning, ";
                        }

                        if ($old_vehicle_data->lifted_vehicle) {
                            $log_addon_detail_olddata .= "Lifted, ";
                        }

                        if ($old_vehicle_data->exthandwax_addon) {
                            $log_addon_detail_olddata .= "Wax, ";
                        }

                        if ($old_vehicle_data->extplasticdressing_addon) {
                            $log_addon_detail_olddata .= "Dressing, ";
                        }

                        if ($old_vehicle_data->extclaybar_addon) {
                            $log_addon_detail_olddata .= "Clay bar, ";
                        }

                        if ($old_vehicle_data->waterspotremove_addon) {
                            $log_addon_detail_olddata .= "Water spot, ";
                        }

                        if ($old_vehicle_data->upholstery_addon) {
                            $log_addon_detail_olddata .= "Upholstery, ";
                        }

                        if ($old_vehicle_data->floormat_addon) {
                            $log_addon_detail_olddata .= "Floormat, ";
                        }

                        if (!$log_addon_detail_olddata)
                            $log_addon_detail_olddata = " no addons";
                        $log_addon_detail_olddata = rtrim($log_addon_detail_olddata, ', ');
                        $log_detail_olddata .= " " . $log_addon_detail_olddata;
                        $log_detail_olddata = rtrim($log_detail_olddata, ', ');
                        $log_detail_olddata = rtrim($log_detail_olddata, ' ');

                        $log_detail_olddata = $log_detail_olddata . ")";

                        if ($cust_vehicle_data->pet_hair) {
                            $pet_hair_vehicles_old = '';
                            $pet_hair_vehicles_old = $wash_request_exists->pet_hair_vehicles;
                            $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles_old);
                            if (!in_array($vehicle_id, $pet_hair_vehicles_arr))
                                array_push($pet_hair_vehicles_arr, $vehicle_id);
                            $pet_hair_vehicles_new = implode(",", $pet_hair_vehicles_arr);
                            $pet_hair_vehicles_new = trim($pet_hair_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('pet_hair_vehicles' => $pet_hair_vehicles_new));
                            $log_addon_detail .= "Extra cleaning, ";
                        }
                        else {
                            $pet_hair_vehicles_arr = explode(",", $wash_request_exists->pet_hair_vehicles);
                            if (($key = array_search($vehicle_id, $pet_hair_vehicles_arr)) !== false) {
                                unset($pet_hair_vehicles_arr[$key]);
                                array_values($pet_hair_vehicles_arr);
                            }
                            $pet_hair_vehicles_new = implode(",", $pet_hair_vehicles_arr);
                            $pet_hair_vehicles_new = trim($pet_hair_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('pet_hair_vehicles' => $pet_hair_vehicles_new));
                        }

                        if ($cust_vehicle_data->lifted_vehicle) {
                            $lifted_vehicles_old = '';
                            $lifted_vehicles_old = $wash_request_exists->lifted_vehicles;
                            $lifted_vehicles_arr = explode(",", $lifted_vehicles_old);
                            if (!in_array($vehicle_id, $lifted_vehicles_arr))
                                array_push($lifted_vehicles_arr, $vehicle_id);
                            $lifted_vehicles_new = implode(",", $lifted_vehicles_arr);
                            $lifted_vehicles_new = trim($lifted_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('lifted_vehicles' => $lifted_vehicles_new));
                            $log_addon_detail .= "Lifted, ";
                        }
                        else {
                            $lifted_vehicles_arr = explode(",", $wash_request_exists->lifted_vehicles);
                            if (($key = array_search($vehicle_id, $lifted_vehicles_arr)) !== false) {
                                unset($lifted_vehicles_arr[$key]);
                                array_values($lifted_vehicles_arr);
                            }
                            $lifted_vehicles_new = implode(",", $lifted_vehicles_arr);
                            $lifted_vehicles_new = trim($lifted_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('lifted_vehicles' => $lifted_vehicles_new));
                        }

                        if ($cust_vehicle_data->exthandwax_addon) {
                            $exthandwax_addon_old = '';
                            $exthandwax_addon_old = $wash_request_exists->exthandwax_vehicles;
                            $exthandwax_addon_arr = explode(",", $exthandwax_addon_old);
                            if (!in_array($vehicle_id, $exthandwax_addon_arr))
                                array_push($exthandwax_addon_arr, $vehicle_id);
                            $exthandwax_addon_new = implode(",", $exthandwax_addon_arr);
                            $exthandwax_addon_new = trim($exthandwax_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('exthandwax_vehicles' => $exthandwax_addon_new));
                            $log_addon_detail .= "Wax, ";
                        }
                        else {
                            $exthandwax_addon_arr = explode(",", $wash_request_exists->exthandwax_vehicles);
                            if (($key = array_search($vehicle_id, $exthandwax_addon_arr)) !== false) {
                                unset($exthandwax_addon_arr[$key]);
                                array_values($exthandwax_addon_arr);
                            }
                            $exthandwax_addon_new = implode(",", $exthandwax_addon_arr);
                            $exthandwax_addon_new = trim($exthandwax_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('exthandwax_vehicles' => $exthandwax_addon_new));
                        }


                        if ($cust_vehicle_data->extplasticdressing_addon) {
                            $extplasticdressing_addon_old = '';
                            $extplasticdressing_addon_old = $wash_request_exists->extplasticdressing_vehicles;
                            $extplasticdressing_addon_arr = explode(",", $extplasticdressing_addon_old);
                            if (!in_array($vehicle_id, $extplasticdressing_addon_arr))
                                array_push($extplasticdressing_addon_arr, $vehicle_id);
                            $extplasticdressing_addon_new = implode(",", $extplasticdressing_addon_arr);
                            $extplasticdressing_addon_new = trim($extplasticdressing_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extplasticdressing_vehicles' => $extplasticdressing_addon_new));
                            $log_addon_detail .= "Dressing, ";
                        }
                        else {
                            $extplasticdressing_addon_arr = explode(",", $wash_request_exists->extplasticdressing_vehicles);
                            if (($key = array_search($vehicle_id, $extplasticdressing_addon_arr)) !== false) {
                                unset($extplasticdressing_addon_arr[$key]);
                                array_values($extplasticdressing_addon_arr);
                            }
                            $extplasticdressing_addon_new = implode(",", $extplasticdressing_addon_arr);
                            $extplasticdressing_addon_new = trim($extplasticdressing_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extplasticdressing_vehicles' => $extplasticdressing_addon_new));
                        }


                        if ($cust_vehicle_data->extclaybar_addon) {
                            $extclaybar_addon_old = '';
                            $extclaybar_addon_old = $wash_request_exists->extclaybar_vehicles;
                            $extclaybar_addon_arr = explode(",", $extclaybar_addon_old);
                            if (!in_array($vehicle_id, $extclaybar_addon_arr))
                                array_push($extclaybar_addon_arr, $vehicle_id);
                            $extclaybar_addon_new = implode(",", $extclaybar_addon_arr);
                            $extclaybar_addon_new = trim($extclaybar_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extclaybar_vehicles' => $extclaybar_addon_new));
                            $log_addon_detail .= "Clay bar, ";
                        }
                        else {
                            $extclaybar_addon_arr = explode(",", $wash_request_exists->extclaybar_vehicles);
                            if (($key = array_search($vehicle_id, $extclaybar_addon_arr)) !== false) {
                                unset($extclaybar_addon_arr[$key]);
                                array_values($extclaybar_addon_arr);
                            }
                            $extclaybar_addon_new = implode(",", $extclaybar_addon_arr);
                            $extclaybar_addon_new = trim($extclaybar_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extclaybar_vehicles' => $extclaybar_addon_new));
                        }

                        if ($cust_vehicle_data->waterspotremove_addon) {
                            $waterspotremove_addon_old = '';
                            $waterspotremove_addon_old = $wash_request_exists->waterspotremove_vehicles;
                            $waterspotremove_addon_arr = explode(",", $waterspotremove_addon_old);
                            if (!in_array($vehicle_id, $waterspotremove_addon_arr))
                                array_push($waterspotremove_addon_arr, $vehicle_id);
                            $waterspotremove_addon_new = implode(",", $waterspotremove_addon_arr);
                            $waterspotremove_addon_new = trim($waterspotremove_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('waterspotremove_vehicles' => $waterspotremove_addon_new));
                            $log_addon_detail .= "Water spot, ";
                        }
                        else {
                            $waterspotremove_addon_arr = explode(",", $wash_request_exists->waterspotremove_vehicles);
                            if (($key = array_search($vehicle_id, $waterspotremove_addon_arr)) !== false) {
                                unset($waterspotremove_addon_arr[$key]);
                                array_values($waterspotremove_addon_arr);
                            }
                            $waterspotremove_addon_new = implode(",", $waterspotremove_addon_arr);
                            $waterspotremove_addon_new = trim($waterspotremove_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('waterspotremove_vehicles' => $waterspotremove_addon_new));
                        }

                        if ($cust_vehicle_data->upholstery_addon) {
                            $upholstery_addon_old = '';
                            $upholstery_addon_old = $wash_request_exists->upholstery_vehicles;
                            $upholstery_addon_arr = explode(",", $upholstery_addon_old);
                            if (!in_array($vehicle_id, $upholstery_addon_arr))
                                array_push($upholstery_addon_arr, $vehicle_id);
                            $upholstery_addon_new = implode(",", $upholstery_addon_arr);
                            $upholstery_addon_new = trim($upholstery_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('upholstery_vehicles' => $upholstery_addon_new));
                            $log_addon_detail .= "Upholstery, ";
                        }
                        else {
                            $upholstery_addon_arr = explode(",", $wash_request_exists->upholstery_vehicles);
                            if (($key = array_search($vehicle_id, $upholstery_addon_arr)) !== false) {
                                unset($upholstery_addon_arr[$key]);
                                array_values($upholstery_addon_arr);
                            }
                            $upholstery_addon_new = implode(",", $upholstery_addon_arr);
                            $upholstery_addon_new = trim($upholstery_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('upholstery_vehicles' => $upholstery_addon_new));
                        }

                        if ($cust_vehicle_data->floormat_addon) {
                            $floormat_addon_old = '';
                            $floormat_addon_old = $wash_request_exists->floormat_vehicles;
                            $floormat_addon_arr = explode(",", $floormat_addon_old);
                            if (!in_array($vehicle_id, $floormat_addon_arr))
                                array_push($floormat_addon_arr, $vehicle_id);
                            $floormat_addon_new = implode(",", $floormat_addon_arr);
                            $floormat_addon_new = trim($floormat_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('floormat_vehicles' => $floormat_addon_new));
                            $log_addon_detail .= "Floormat, ";
                        }
                        else {
                            $floormat_addon_arr = explode(",", $wash_request_exists->floormat_vehicles);
                            if (($key = array_search($vehicle_id, $floormat_addon_arr)) !== false) {
                                unset($floormat_addon_arr[$key]);
                                array_values($floormat_addon_arr);
                            }
                            $floormat_addon_new = implode(",", $floormat_addon_arr);
                            $floormat_addon_new = trim($floormat_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('floormat_vehicles' => $floormat_addon_new));
                        }

                        if (!$log_addon_detail)
                            $log_addon_detail = ' no add-ons';

                        $log_addon_detail = rtrim($log_addon_detail, ', ');

                        if ($log_addon_detail != ' no add-ons')
                            $log_addon_detail = " " . $log_addon_detail;

                        if ($cust_vehicle_data->wash_package == 'Premium') {
                            $surge_addon_arr = explode(",", $wash_request_exists->surge_price_vehicles);
                            if (($key = array_search($vehicle_id, $surge_addon_arr)) !== false) {
                                unset($surge_addon_arr[$key]);
                                array_values($surge_addon_arr);
                            }
                            $surge_addon_new = implode(",", $surge_addon_arr);
                            $surge_addon_new = trim($surge_addon_new, ",");

                            Vehicle::model()->updateByPk($vehicle_id, array('surge_addon' => 0));
                        }

                        $packs = $wash_request_exists->package_list;
                        $cars_arr = explode(",", $cars);
                        $old_packs_arr = explode(",", $packs);
                        $carkey = array_search($vehicle_id, $cars_arr);
                        $old_packs_arr[$carkey] = $cust_vehicle_data->wash_package;
                        $updated_packs = implode(",", $old_packs_arr);
                        $coupon_amount = 0;
                        if ($wash_request_exists->coupon_code) {
                            $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $wash_request_exists->coupon_code));
                            if (count($coupon_check)) {
                                if (strpos($updated_packs, 'Premium') !== false) {
                                    $coupon_amount = number_format($coupon_check->premium_amount, 2, '.', '');
                                } else {
                                    $coupon_amount = number_format($coupon_check->deluxe_amount, 2, '.', '');
                                }
                            }
                        }

                        Washingrequests::model()->updateByPk($wash_request_id, array('package_list' => $updated_packs, 'surge_price_vehicles' => $surge_addon_new, 'coupon_discount' => $coupon_amount));
                        $newpriceobj = Washingplans::model()->findByAttributes(array("vehicle_type" => $cust_vehicle_data->vehicle_type, "title" => $cust_vehicle_data->wash_package));
                        $newprice = 0;
                        $newprice = $cust_vehicle_data->package_price;
                        /* if(count($newpriceobj)) {
                          $newprice = $newpriceobj->price;
                          }
                          else{
                          if($cust_vehicle_data->wash_package == 'Express') $newprice = 19.99;
                          if($cust_vehicle_data->wash_package == 'Deluxe') $newprice = 24.99;
                          if($cust_vehicle_data->wash_package == 'Premium') $newprice = 59.99;
                          } */
                        WashPricingHistory::model()->updateAll(array('vehicle_price' => $newprice, 'package' => $cust_vehicle_data->wash_package, 'pet_hair' => $cust_vehicle_data->pet_hair, 'lifted_vehicle' => $cust_vehicle_data->lifted_vehicle, 'exthandwax_addon' => $cust_vehicle_data->exthandwax_addon, 'extplasticdressing_addon' => $cust_vehicle_data->extplasticdressing_addon, 'extclaybar_addon' => $cust_vehicle_data->extclaybar_addon, 'waterspotremove_addon' => $cust_vehicle_data->waterspotremove_addon, 'upholstery_addon' => $cust_vehicle_data->upholstery_addon, 'floormat_addon' => $cust_vehicle_data->floormat_addon, 'last_updated' => date("Y-m-d H:i:s")), 'wash_request_id=:wash_request_id AND vehicle_id=:vehicle_id', array(":wash_request_id" => $wash_request_id, ":vehicle_id" => $vehicle_id));

                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                        $log_detail = $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name . " from " . $log_detail_olddata . " to (" . $cust_vehicle_data->wash_package . " w/" . $log_addon_detail . ")";

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'customeracceptupgrade',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */
                        $notification_code = '';
                        //didn't start the wash
                        if ($status == 0) {
                            $notification_code = 35;
                        } elseif ($status == 5) {
                            //wash has start then
                            $notification_code = 40;
                        }
                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '$notification_code' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        if (!$agent_detail->block_washer) {
                            foreach ($agentdevices as $agdevice) {

                                //echo $agentdetails['mobile_type'];
                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "strong";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                        /* --- notification call end --- */
                    }

                    if ($upgrade_pack == 3) {

//Vehicle::model()->updateByPk($vehicle_id, array('pet_hair' => 0, 'lifted_vehicle' => 0, 'new_pack_name' => '', 'exthandwax_addon' => 0, 'extplasticdressing_addon' => 0, 'extclaybar_addon' => 0, 'waterspotremove_addon' => 0, 'upholstery_addon' => 0, 'floormat_addon' => 0));

                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                        $old_vehicle_data = Vehicle::model()->findByPk($vehicle_id);

                        $cust_vehicle_data = CustomerDraftVehicle::model()->findByAttributes(array("id" => $draft_vehicle_id));


                        //$cust_vehicle_data = Vehicle::model()->findByPk($vehicle_id);
                        $log_addon_detail = "";
                        $log_detail = "";
                        $cars_arr_up = explode(",", $wash_request_exists->car_list);
                        $packs_arr_up = explode(",", $wash_request_exists->package_list);
                        $carkey = array_search($vehicle_id, $cars_arr_up);
                        $log_detail_olddata = "(" . $packs_arr_up[$carkey] . " w/";
                        $log_addon_detail_olddata = "";

                        /* -------- pet hair / lift / addons check --------- */

                        if ($old_vehicle_data->pet_hair) {
                            $log_addon_detail_olddata .= "Extra cleaning, ";
                        }

                        if ($old_vehicle_data->lifted_vehicle) {
                            $log_addon_detail_olddata .= "Lifted, ";
                        }

                        if ($old_vehicle_data->exthandwax_addon) {
                            $log_addon_detail_olddata .= "Wax, ";
                        }

                        if ($old_vehicle_data->extplasticdressing_addon) {
                            $log_addon_detail_olddata .= "Dressing, ";
                        }

                        if ($old_vehicle_data->extclaybar_addon) {
                            $log_addon_detail_olddata .= "Clay bar, ";
                        }

                        if ($old_vehicle_data->waterspotremove_addon) {
                            $log_addon_detail_olddata .= "Water spot, ";
                        }

                        if ($old_vehicle_data->upholstery_addon) {
                            $log_addon_detail_olddata .= "Upholstery, ";
                        }

                        if ($old_vehicle_data->floormat_addon) {
                            $log_addon_detail_olddata .= "Floormat, ";
                        }

                        if (!$log_addon_detail_olddata)
                            $log_addon_detail_olddata = " no addons";
                        $log_addon_detail_olddata = rtrim($log_addon_detail_olddata, ', ');
                        $log_detail_olddata .= " " . $log_addon_detail_olddata;
                        $log_detail_olddata = rtrim($log_detail_olddata, ', ');
                        $log_detail_olddata = rtrim($log_detail_olddata, ' ');

                        $log_detail_olddata = $log_detail_olddata . ")";


                        /* -------- pet hair / lift / addons check --------- */


                        if ($cust_vehicle_data->pet_hair) {
                            $log_addon_detail .= "Extra cleaning, ";
                        }

                        if ($cust_vehicle_data->lifted_vehicle) {
                            $log_addon_detail .= "Lifted, ";
                        }

                        if ($cust_vehicle_data->exthandwax_addon) {

                            $log_addon_detail .= "Wax, ";
                        }


                        if ($cust_vehicle_data->extplasticdressing_addon) {

                            $log_addon_detail .= "Dressing, ";
                        }


                        if ($cust_vehicle_data->extclaybar_addon) {

                            $log_addon_detail .= "Clay bar, ";
                        }

                        if ($cust_vehicle_data->waterspotremove_addon) {

                            $log_addon_detail .= "Water spot, ";
                        }

                        if ($cust_vehicle_data->upholstery_addon) {

                            $log_addon_detail .= "Upholstery, ";
                        }

                        if ($cust_vehicle_data->floormat_addon) {

                            $log_addon_detail .= "Floormat, ";
                        }

                        if (!$log_addon_detail)
                            $log_addon_detail = ' no add-ons';

                        $log_addon_detail = rtrim($log_addon_detail, ', ');

                        if ($log_addon_detail != ' no add-ons')
                            $log_addon_detail = " " . $log_addon_detail;

                        $log_addon_detail = rtrim($log_addon_detail, ', ');

                        $log_detail = $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name . " from " . $log_detail_olddata . " to (" . $cust_vehicle_data->wash_package . " w/" . $log_addon_detail . ")";

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'customerrejectupgrade',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */
                        $notification_code = '';
                        //didn't start the wash
                        if ($status == 0) {
                            $notification_code = 36;
                        } elseif ($status == 5) {
                            //wash has start then
                            $notification_code = 41;
                        }
                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '$notification_code' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        if (!$agent_detail->block_washer) {
                            foreach ($agentdevices as $agdevice) {

                                //echo $agentdetails['mobile_type'];
                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "strong";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                        /* --- notification call end --- */
                    }

                    /* ------------ upgrade pack end ------------- */

                    /* ------ edit vehicle ---------- */

                    if ($edit_vehicle == 1) {

                        Washingrequests::model()->updateByPk($wash_request_id, array('upgrade_requested_at' => date('Y-m-d H:i:s')));

                        $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wash_request_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '32' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        foreach ($clientdevices as $ctdevice) {

                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                        /* --- notification call end --- */
                    }

                    if ($edit_vehicle == 2) {

                        $draft_vehicle_exists = CustomerDraftVehicle::model()->findByAttributes(array("id" => $draft_vehicle_id));
                        if ($draft_vehicle_exists->vehicle_build == 'classic') {
                            $vehicle_check = Yii::app()->db->createCommand()
                                    ->select('*')
                                    ->from('all_classic_vehicles')
                                    ->where("make='" . $draft_vehicle_exists->brand_name . "' AND model='" . $draft_vehicle_exists->model_name . "'", array())
                                    ->queryAll();
                        } else {
                            $vehicle_check = Yii::app()->db->createCommand()
                                    ->select('*')
                                    ->from('all_vehicles')
                                    ->where("make='" . $draft_vehicle_exists->brand_name . "' AND model='" . $draft_vehicle_exists->model_name . "'", array())
                                    ->queryAll();
                        }

                        WashPricingHistory::model()->updateAll(array('vehicle_price' => $draft_vehicle_exists->package_price, 'last_updated' => date("Y-m-d H:i:s")), 'wash_request_id=:wash_request_id AND vehicle_id=:vehicle_id', array(":wash_request_id" => $wash_request_id, ":vehicle_id" => $vehicle_id));
                        Vehicle::model()->updateByPk($vehicle_id, array("vehicle_source_id" => $vehicle_check[0]['id'], "brand_name" => $draft_vehicle_exists->brand_name, "model_name" => $draft_vehicle_exists->model_name, "vehicle_type" => $draft_vehicle_exists->vehicle_type, "vehicle_category" => $draft_vehicle_exists->vehicle_category, "vehicle_build" => $draft_vehicle_exists->vehicle_build, "vehicle_image" => $draft_vehicle_exists->vehicle_image));

                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '33' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        if (!$agent_detail->block_washer) {
                            foreach ($agentdevices as $agdevice) {

                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "strong";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                        /* --- notification call end --- */

                        $log_detail = $draft_vehicle_exists->brand_name . " " . $draft_vehicle_exists->model_name;
                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'washereditcar',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                    }

                    if ($edit_vehicle == 3) {
                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        /* --- notification call --- */

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '34' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        if (!$agent_detail->block_washer) {
                            foreach ($agentdevices as $agdevice) {

                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "strong";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                        /* --- notification call end --- */
                    }

                    /* ------ edit vehicle end ---------- */

                    /* ------------ remove car from kart ------------- */

                    if ($remove_vehicle_from_kart == 2) {

                        $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                        $cust_vehicle_data = Vehicle::model()->findByPk($vehicle_id);
                        $log_detail = $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name;

                        $logdata = array(
                            'wash_request_id' => $wash_request_id,
                            'agent_id' => $wash_request_exists->agent_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'washerremovecar',
                            'addi_detail' => $log_detail,
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);



                        $packs = $wash_request_exists->package_list;
                        $cars_arr = explode(",", $cars);
                        $packs_arr = explode(",", $packs);
                        $carkey = array_search($vehicle_id, $cars_arr);
                        unset($cars_arr[$carkey]);
                        unset($packs_arr[$carkey]);
                        $cars_arr = array_values($cars_arr);
                        $packs_arr = array_values($packs_arr);
                        $new_packs = implode(",", $packs_arr);
                        $new_cars = implode(",", $cars_arr);

                        $coupon_amount = 0;
                        if ($wash_request_exists->coupon_code) {
                            $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $wash_request_exists->coupon_code));
                            if (count($coupon_check)) {
                                if (strpos($new_packs, 'Premium') !== false) {
                                    $coupon_amount = number_format($coupon_check->premium_amount, 2, '.', '');
                                } else {
                                    $coupon_amount = number_format($coupon_check->deluxe_amount, 2, '.', '');
                                }
                            }
                        }

                        Washingrequests::model()->updateByPk($wash_request_id, array('package_list' => $new_packs, 'car_list' => $new_cars, 'coupon_discount' => $coupon_amount, 'fifth_wash_discount' => 0, 'fifth_wash_vehicles' => ''));

                        if ($cust_vehicle_data->pet_hair) {
                            $pet_hair_vehicles_arr = explode(",", $wash_request_exists->pet_hair_vehicles);
                            if (($key = array_search($vehicle_id, $pet_hair_vehicles_arr)) !== false) {
                                unset($pet_hair_vehicles_arr[$key]);
                                array_values($pet_hair_vehicles_arr);
                            }
                            $pet_hair_vehicles_new = implode(",", $pet_hair_vehicles_arr);
                            $pet_hair_vehicles_new = trim($pet_hair_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('pet_hair_vehicles' => $pet_hair_vehicles_new));
                        }


                        if ($cust_vehicle_data->lifted_vehicle) {
                            $lifted_vehicles_arr = explode(",", $wash_request_exists->lifted_vehicles);
                            if (($key = array_search($vehicle_id, $lifted_vehicles_arr)) !== false) {
                                unset($lifted_vehicles_arr[$key]);
                                array_values($lifted_vehicles_arr);
                            }
                            $lifted_vehicles_new = implode(",", $lifted_vehicles_arr);
                            $lifted_vehicles_new = trim($lifted_vehicles_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('lifted_vehicles' => $lifted_vehicles_new));
                        }

                        if ($cust_vehicle_data->exthandwax_addon) {
                            $exthandwax_addon_arr = explode(",", $wash_request_exists->exthandwax_vehicles);
                            if (($key = array_search($vehicle_id, $exthandwax_addon_arr)) !== false) {
                                unset($exthandwax_addon_arr[$key]);
                                array_values($exthandwax_addon_arr);
                            }
                            $exthandwax_addon_new = implode(",", $exthandwax_addon_arr);
                            $exthandwax_addon_new = trim($exthandwax_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('exthandwax_vehicles' => $exthandwax_addon_new));
                        }


                        if ($cust_vehicle_data->extplasticdressing_addon) {
                            $extplasticdressing_addon_arr = explode(",", $wash_request_exists->extplasticdressing_vehicles);
                            if (($key = array_search($vehicle_id, $extplasticdressing_addon_arr)) !== false) {
                                unset($extplasticdressing_addon_arr[$key]);
                                array_values($extplasticdressing_addon_arr);
                            }
                            $extplasticdressing_addon_new = implode(",", $extplasticdressing_addon_arr);
                            $extplasticdressing_addon_new = trim($extplasticdressing_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extplasticdressing_vehicles' => $extplasticdressing_addon_new));
                        }



                        if ($cust_vehicle_data->extclaybar_addon) {
                            $extclaybar_addon_arr = explode(",", $wash_request_exists->extclaybar_vehicles);
                            if (($key = array_search($vehicle_id, $extclaybar_addon_arr)) !== false) {
                                unset($extclaybar_addon_arr[$key]);
                                array_values($extclaybar_addon_arr);
                            }
                            $extclaybar_addon_new = implode(",", $extclaybar_addon_arr);
                            $extclaybar_addon_new = trim($extclaybar_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('extclaybar_vehicles' => $extclaybar_addon_new));
                        }


                        if ($cust_vehicle_data->waterspotremove_addon) {
                            $waterspotremove_addon_arr = explode(",", $wash_request_exists->waterspotremove_vehicles);
                            if (($key = array_search($vehicle_id, $waterspotremove_addon_arr)) !== false) {
                                unset($waterspotremove_addon_arr[$key]);
                                array_values($waterspotremove_addon_arr);
                            }
                            $waterspotremove_addon_new = implode(",", $waterspotremove_addon_arr);
                            $waterspotremove_addon_new = trim($waterspotremove_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('waterspotremove_vehicles' => $waterspotremove_addon_new));
                        }

                        if ($cust_vehicle_data->upholstery_addon) {
                            $upholstery_addon_arr = explode(",", $wash_request_exists->upholstery_vehicles);
                            if (($key = array_search($vehicle_id, $upholstery_addon_arr)) !== false) {
                                unset($upholstery_addon_arr[$key]);
                                array_values($upholstery_addon_arr);
                            }
                            $upholstery_addon_new = implode(",", $upholstery_addon_arr);
                            $upholstery_addon_new = trim($upholstery_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('upholstery_vehicles' => $upholstery_addon_new));
                        }

                        if ($cust_vehicle_data->floormat_addon) {
                            $floormat_addon_arr = explode(",", $wash_request_exists->floormat_vehicles);
                            if (($key = array_search($vehicle_id, $floormat_addon_arr)) !== false) {
                                unset($floormat_addon_arr[$key]);
                                array_values($floormat_addon_arr);
                            }
                            $floormat_addon_new = implode(",", $floormat_addon_arr);
                            $floormat_addon_new = trim($floormat_addon_new, ",");
                            Washingrequests::model()->updateByPk($wash_request_id, array('floormat_vehicles' => $floormat_addon_new));
                        }

                        WashPricingHistory::model()->deleteAll("wash_request_id=:wash_request_id AND vehicle_id=:vehicle_id", array(":wash_request_id" => $wash_request_id, ":vehicle_id" => $vehicle_id));

                        $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wash_request_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                        $vehicle_details = Vehicle::model()->findByAttributes(array('id' => $vehicle_id, 'customer_id' => $wash_request_exists->customer_id));

                        /* --- notification call --- */

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '30' ")->queryAll();
                        $message = $pushmsg[0]['message'];
                        $message = str_replace("[BRAND_NAME]", $vehicle_details->brand_name, $message);
                        $message = str_replace("[MODEL_NAME]", $vehicle_details->model_name, $message);

                        foreach ($clientdevices as $ctdevice) {

                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "schedule";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                        /* --- notification call end --- */
                    }

                    /* ------------ remove car from kart end ------------- */

                    /* --------- add draft vehicle id to current wash --------------- */

                    if ((isset($draft_vehicle_id) && !empty($draft_vehicle_id))) {
                        Washingrequests::model()->updateByPk($wash_request_id, array('draft_vehicle_id' => $draft_vehicle_id));
                    }

                    /* --------- add draft vehicle id to current wash end --------------- */

                    /* --------- add new vehicle command current wash --------------- */

                    if ((isset($new_vehicle_confirm) && !empty($new_vehicle_confirm))) {
                        Washingrequests::model()->updateByPk($wash_request_id, array('new_vehicle_confirm' => $new_vehicle_confirm));

                        if ($new_vehicle_confirm == 1) {

                            Washingrequests::model()->updateByPk($wash_request_id, array('upgrade_requested_at' => date('Y-m-d H:i:s')));

                            /* --- notification call --- */

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '1' ")->queryAll();

                            $notify_msg = $pushmsg[0]['message'];
                            $notify_msg = urlencode($notify_msg);

                            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wash_request_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            if (count($clientdevices)) {
                                foreach ($clientdevices as $ctdevice) {

                                    $device_type = strtolower($ctdevice['device_type']);
                                    $notify_token = $ctdevice['device_token'];
                                    $alert_type = "strong";


                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }


                            /* --- notification end --- */
                        }

                        if ($new_vehicle_confirm == 2) {

                            /* --- notification call --- */

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '2' ")->queryAll();

                            $notify_msg = $pushmsg[0]['message'];
                            $notify_msg = urlencode($notify_msg);

                            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                            $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                            if ((count($agentdevices)) && (!$agent_detail->block_washer)) {
                                foreach ($agentdevices as $agdevice) {

                                    $device_type = strtolower($agdevice['device_type']);
                                    $notify_token = $agdevice['device_token'];
                                    $alert_type = "strong";


                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }


                            /* --- notification end --- */
                        }

                        if ($new_vehicle_confirm == 3) {

                            /* --- notification call --- */

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '3' ")->queryAll();

                            $notify_msg = $pushmsg[0]['message'];
                            $notify_msg = urlencode($notify_msg);

                            $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);

                            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            if ((count($agentdevices)) && (!$agent_detail->block_washer)) {
                                foreach ($agentdevices as $agdevice) {

                                    $device_type = strtolower($agdevice['device_type']);
                                    $notify_token = $agdevice['device_token'];
                                    $alert_type = "strong";


                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }


                            /* --- notification end --- */

                            $cust_vehicle_data = CustomerDraftVehicle::model()->findByPk($wash_request_exists->draft_vehicle_id);

                            $logdata = array(
                                'wash_request_id' => $wash_request_id,
                                'agent_id' => $wash_request_exists->agent_id,
                                'agent_company_id' => $agent_detail->real_washer_id,
                                'action' => 'customerdeclinecar',
                                'addi_detail' => $cust_vehicle_data->brand_name . " " . $cust_vehicle_data->model_name . " (" . $cust_vehicle_data->wash_package . ")",
                                'action_date' => date('Y-m-d H:i:s'));
                            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                        }
                    }

                    /* --------- add new vehicle command current wash --------------- */


                    // $data= array('status' => $status, 'eco_friendly' => $eco_friendly, 'damage_points' => $damage_points);
                    $uploadOk = 0;
                    $cust_vehicle_model = Vehicle::model()->findByPk($vehicle_id);
                    $cust_vehicle_model->status = $status;
                    if ($status == 2)
                        $cust_vehicle_model->eco_friendly = $eco_friendly;
//if(!Yii::app()->request->getParam('pet_hair')) $pet_hair = $cust_vehicle_model->pet_hair;
//$cust_vehicle_model->pet_hair = $pet_hair;
//if(!Yii::app()->request->getParam('lifted_vehicle')) $lifted_vehicle = $cust_vehicle_model->lifted_vehicle;
//$cust_vehicle_model->lifted_vehicle = $lifted_vehicle;
                    $cust_vehicle_model->damage_points = $damage_points;
                    $cust_vehicle_model->upgrade_pack = $upgrade_pack;
                    $cust_vehicle_model->edit_vehicle = $edit_vehicle;
                    $cust_vehicle_model->remove_vehicle_from_kart = $remove_vehicle_from_kart;



                    if (isset($_FILES['damage_pic']['tmp_name'])) {

                        $check = getimagesize($_FILES["damage_pic"]["tmp_name"]);
                        if ($check !== false) {
                            $uploadOk = 1;
                        } else {
                            $uploadOk = 0;
                        }
                        $target_dir = realpath(Yii::app()->basePath . '/../images/veh_img/');
                        $ext = pathinfo($_FILES['damage_pic']['name'], PATHINFO_EXTENSION);
                        $md5 = md5(uniqid(rand(), true));
                        $new_image_name = $vehicle_id . '_dp_' . $md5 . "." . $ext;
                        $target_file = $target_dir . DIRECTORY_SEPARATOR . $new_image_name;
                        $SiteUrl = Yii::app()->getBaseUrl(true);
                        $damage_pic_url = $SiteUrl . '/images/veh_img/' . $new_image_name;
                        $uploadOk = 1;
                    }


                    if ($uploadOk != 0) {


                        if (move_uploaded_file($_FILES["damage_pic"]["tmp_name"], $target_file)) {

                            $cust_vehicle_model->damage_pic = $damage_pic_url;
                        } else {
                            echo json_encode(array("error" => "Sorry, there was an error uploading your file."));
                        }
                    }
                    $checkUpdate = $cust_vehicle_model->save(false);
                    if ($checkUpdate) {
                        $result = 'true';
                        $response = 'Status is changed';
                    } else {
                        $result = 'false';
                        $response = 'Status is not changed';
                    }


                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wash_request_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    if (count($clientdevices)) {
                        foreach ($clientdevices as $ctdevice) {

                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "strong";
                        }
                    }


                    $notify_msg = '';
                    $vehicle_details = Vehicle::model()->findByAttributes(array('id' => $vehicle_id, 'customer_id' => $wash_request_exists->customer_id));
                    //   echo "hi roahn".$device_type."_".$notify_token;die;
                    if (count($vehicle_details)) {
                        if (($status == 2) && (!$upgrade_pack) && (!$new_vehicle_confirm) && (!$edit_vehicle) && (!$remove_vehicle_from_kart)) {

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '4' ")->queryAll();
                            $notify_msg = $pushmsg[0]['message'];

                            $notify_msg = str_replace("[BRAND_NAME]", $vehicle_details->brand_name, $notify_msg);
                            $notify_msg = str_replace("[MODEL_NAME]", $vehicle_details->model_name, $notify_msg);

                            //$notify_msg = "Inspection complete for ".$vehicle_details->brand_name." ".$vehicle_details->model_name.", please confirm.";
                            $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                            $logdata = array(
                                'agent_id' => $wash_request_exists->agent_id,
                                'wash_request_id' => $wash_request_id,
                                'agent_company_id' => $agent_detail->real_washer_id,
                                'action' => 'washercompleteinspection',
                                'addi_detail' => $vehicle_details->brand_name . " " . $vehicle_details->model_name,
                                'action_date' => date('Y-m-d H:i:s'));
                            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                        }

                        if ($status == 2)
                            Washingrequests::model()->updateByPk($wash_request_id, array('washer_wash_activity' => 1));

                        if ($status == 3) {
//Vehicle::model()->updateByPk($vehicle_id, array('pet_hair' => 0, 'lifted_vehicle' => 0));
                        }

                        if ($status == 4) {

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '5' ")->queryAll();
                            $notify_msg = $pushmsg[0]['message'];

                            $notify_msg = str_replace("[BRAND_NAME]", $vehicle_details->brand_name, $notify_msg);
                            $notify_msg = str_replace("[MODEL_NAME]", $vehicle_details->model_name, $notify_msg);

                            //$notify_msg = "Begin ".$vehicle_details->brand_name." ".$vehicle_details->model_name." car wash.";
                            $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wash_request_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            if ((count($agentdevices)) && (!$agent_detail->block_washer)) {
                                foreach ($agentdevices as $agdevice) {

                                    $device_type = strtolower($agdevice['device_type']);
                                    $notify_token = $agdevice['device_token'];
                                    $alert_type = "strong";
                                }
                            }
                        }

                        if ($status == 5) {

                            $cust_vehicle_data = Vehicle::model()->findByPk($vehicle_id);

                            /* -------- pet hair / lift / addons check --------- */


                            if ($cust_vehicle_data->pet_hair) {
                                $pet_hair_vehicles_old = '';
                                $pet_hair_vehicles_old = $wash_request_exists->pet_hair_vehicles;
                                $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles_old);
                                if (!in_array($vehicle_id, $pet_hair_vehicles_arr))
                                    array_push($pet_hair_vehicles_arr, $vehicle_id);
                                $pet_hair_vehicles_new = implode(",", $pet_hair_vehicles_arr);
                                $pet_hair_vehicles_new = trim($pet_hair_vehicles_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('pet_hair_vehicles' => $pet_hair_vehicles_new));
                            }

                            if ($cust_vehicle_data->lifted_vehicle) {
                                $lifted_vehicles_old = '';
                                $lifted_vehicles_old = $wash_request_exists->lifted_vehicles;
                                $lifted_vehicles_arr = explode(",", $lifted_vehicles_old);
                                if (!in_array($vehicle_id, $lifted_vehicles_arr))
                                    array_push($lifted_vehicles_arr, $vehicle_id);
                                $lifted_vehicles_new = implode(",", $lifted_vehicles_arr);
                                $lifted_vehicles_new = trim($lifted_vehicles_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('lifted_vehicles' => $lifted_vehicles_new));
                            }

                            if ($cust_vehicle_data->exthandwax_addon) {
                                $exthandwax_addon_old = '';
                                $exthandwax_addon_old = $wash_request_exists->exthandwax_vehicles;
                                $exthandwax_addon_arr = explode(",", $exthandwax_addon_old);
                                if (!in_array($vehicle_id, $exthandwax_addon_arr))
                                    array_push($exthandwax_addon_arr, $vehicle_id);
                                $exthandwax_addon_new = implode(",", $exthandwax_addon_arr);
                                $exthandwax_addon_new = trim($exthandwax_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('exthandwax_vehicles' => $exthandwax_addon_new));
                            }


                            if ($cust_vehicle_data->extplasticdressing_addon) {
                                $extplasticdressing_addon_old = '';
                                $extplasticdressing_addon_old = $wash_request_exists->extplasticdressing_vehicles;
                                $extplasticdressing_addon_arr = explode(",", $extplasticdressing_addon_old);
                                if (!in_array($vehicle_id, $extplasticdressing_addon_arr))
                                    array_push($extplasticdressing_addon_arr, $vehicle_id);
                                $extplasticdressing_addon_new = implode(",", $extplasticdressing_addon_arr);
                                $extplasticdressing_addon_new = trim($extplasticdressing_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('extplasticdressing_vehicles' => $extplasticdressing_addon_new));
                            }


                            if ($cust_vehicle_data->extclaybar_addon) {
                                $extclaybar_addon_old = '';
                                $extclaybar_addon_old = $wash_request_exists->extclaybar_vehicles;
                                $extclaybar_addon_arr = explode(",", $extclaybar_addon_old);
                                if (!in_array($vehicle_id, $extclaybar_addon_arr))
                                    array_push($extclaybar_addon_arr, $vehicle_id);
                                $extclaybar_addon_new = implode(",", $extclaybar_addon_arr);
                                $extclaybar_addon_new = trim($extclaybar_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('extclaybar_vehicles' => $extclaybar_addon_new));
                            }

                            if ($cust_vehicle_data->waterspotremove_addon) {
                                $waterspotremove_addon_old = '';
                                $waterspotremove_addon_old = $wash_request_exists->waterspotremove_vehicles;
                                $waterspotremove_addon_arr = explode(",", $waterspotremove_addon_old);
                                if (!in_array($vehicle_id, $waterspotremove_addon_arr))
                                    array_push($waterspotremove_addon_arr, $vehicle_id);
                                $waterspotremove_addon_new = implode(",", $waterspotremove_addon_arr);
                                $waterspotremove_addon_new = trim($waterspotremove_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('waterspotremove_vehicles' => $waterspotremove_addon_new));
                            }

                            if ($cust_vehicle_data->upholstery_addon) {
                                $upholstery_addon_old = '';
                                $upholstery_addon_old = $wash_request_exists->upholstery_vehicles;
                                $upholstery_addon_arr = explode(",", $upholstery_addon_old);
                                if (!in_array($vehicle_id, $upholstery_addon_arr))
                                    array_push($upholstery_addon_arr, $vehicle_id);
                                $upholstery_addon_new = implode(",", $upholstery_addon_arr);
                                $upholstery_addon_new = trim($upholstery_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('upholstery_vehicles' => $upholstery_addon_new));
                            }

                            if ($cust_vehicle_data->floormat_addon) {
                                $floormat_addon_old = '';
                                $floormat_addon_old = $wash_request_exists->floormat_vehicles;
                                $floormat_addon_arr = explode(",", $floormat_addon_old);
                                if (!in_array($vehicle_id, $floormat_addon_arr))
                                    array_push($floormat_addon_arr, $vehicle_id);
                                $floormat_addon_new = implode(",", $floormat_addon_arr);
                                $floormat_addon_new = trim($floormat_addon_new, ",");
                                Washingrequests::model()->updateByPk($wash_request_id, array('floormat_vehicles' => $floormat_addon_new));
                            }


                            /* -------- pet hair / lift / addons check end --------- */

//if($upgrade_pack == 0){
                            if ((!$upgrade_pack) && (!$new_vehicle_confirm) && (!$remove_vehicle_from_kart) && (!$edit_vehicle)) {
                                $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '6' ")->queryAll();
                                $notify_msg = $pushmsg[0]['message'];

                                $notify_msg = str_replace("[BRAND_NAME]", $vehicle_details->brand_name, $notify_msg);
                                $notify_msg = str_replace("[MODEL_NAME]", $vehicle_details->model_name, $notify_msg);

                                $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                                $logdata = array(
                                    'agent_id' => $wash_request_exists->agent_id,
                                    'wash_request_id' => $wash_request_id,
                                    'agent_company_id' => $agent_detail->real_washer_id,
                                    'action' => 'washerstartwash',
                                    'addi_detail' => $vehicle_details->brand_name . " " . $vehicle_details->model_name,
                                    'action_date' => date('Y-m-d H:i:s'));
                                Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                            }

                            //$notify_msg = $vehicle_details->brand_name." ".$vehicle_details->model_name." car wash is in progress.";
                        }

                        if (($status == 6)) {
                            Vehicle::model()->updateByPk($vehicle_id, array('pet_hair' => 0, 'lifted_vehicle' => 0, 'new_pack_name' => '', 'exthandwax_addon' => 0, 'extplasticdressing_addon' => 0, 'extclaybar_addon' => 0, 'waterspotremove_addon' => 0, 'upholstery_addon' => 0, 'floormat_addon' => 0));

                            if ((!$upgrade_pack) && (!$new_vehicle_confirm) && (!$remove_vehicle_from_kart) && (!$edit_vehicle)) {
                                $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '7' ")->queryAll();
                                $notify_msg = $pushmsg[0]['message'];

                                $notify_msg = str_replace("[BRAND_NAME]", $vehicle_details->brand_name, $notify_msg);
                                $notify_msg = str_replace("[MODEL_NAME]", $vehicle_details->model_name, $notify_msg);

                                $agent_detail = Agents::model()->findByPk($wash_request_exists->agent_id);
                                $logdata = array(
                                    'agent_id' => $wash_request_exists->agent_id,
                                    'wash_request_id' => $wash_request_id,
                                    'agent_company_id' => $agent_detail->real_washer_id,
                                    'action' => 'washerfinishwash',
                                    'addi_detail' => $vehicle_details->brand_name . " " . $vehicle_details->model_name,
                                    'action_date' => date('Y-m-d H:i:s'));
                                Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                            }

                            //$notify_msg = $vehicle_details->brand_name." ".$vehicle_details->model_name." car wash complete.";

                            /* ----------- 1st wash check ----------- */

                            /* if((!$cust_details->is_first_wash) && (!$cust_details->fifth_wash_points)){
                              $car_packs_arra = explode(",", $wash_request_exists->package_list);
                              if($car_packs_arra[0] == 'Premium') $first_disc = 10;
                              else $first_disc = 5;

                              Washingrequests::model()->updateByPk($wash_request_id, array('first_wash_discount' => $first_disc));

                              } */

                            /* ----------- 1st wash check end ----------- */

                            /* ------ 5th wash check ------- */
                            /*
                              $current_points = $cust_details->fifth_wash_points;
                              if($current_points == 5){
                              $new_points = 1;
                              }
                              else{
                              $new_points = $current_points + 1;
                              }


                              Customers::model()->updateByPk($wash_request_exists->customer_id, array('fifth_wash_points' => $new_points));


                              if($new_points == 5){

                              $fifth_vehicles_old = '';
                              $fifth_vehicles_old = $wash_request_exists->fifth_wash_vehicles;
                              $fifth_vehicles_arr = explode(",", $fifth_vehicles_old);
                              if (!in_array($vehicle_id, $fifth_vehicles_arr)) array_push($fifth_vehicles_arr, $vehicle_id);
                              $fifth_vehicles_new = implode(",", $fifth_vehicles_arr);
                              $fifth_vehicles_new = trim($fifth_vehicles_new,",");

                              if($wash_request_exists->coupon_discount <= 0) Washingrequests::model()->updateByPk($wash_request_id, array('fifth_wash_discount' => 5, 'fifth_wash_vehicles' => $fifth_vehicles_new));

                              }
                             */
                            /* ------ 5th wash check end ------- */

                            /* ---- per car wash points ------ */
                            /*
                              $per_car_points_old = '';
                              $per_car_points_old = $wash_request_exists->per_car_wash_points;
                              $per_car_points_arr = explode(",", $per_car_points_old);
                              array_push($per_car_points_arr, $new_points);
                              $per_car_points_new = implode(",", $per_car_points_arr);
                              $per_car_points_new = trim($per_car_points_new,",");

                              Washingrequests::model()->updateByPk($wash_request_id, array('per_car_wash_points' => $per_car_points_new));
                             */
                            /* ---- per car wash points end ------ */
                        }
                    }

                    $notify_msg = urlencode($notify_msg);

                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                    // echo $notifyurl;die;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    if ($notify_msg) {
                        $notifyresult = curl_exec($ch);
                        //print_r($notifyresult);die;
                    }

                    curl_close($ch);

                    //var_dump($notifyresult);

                    /* --- notification call end --- */
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actiongetvehiclestatus() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';
        $vehicles = array();
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $zipcode = '';
        if (Yii::app()->request->getParam('zipcode'))
            $zipcode = Yii::app()->request->getParam('zipcode');
        $veh_type = '';
        $fifth_fee_check = 0;
        $first_fee_check = 0;
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        $coveragezipcheck = 0;

        if ((isset($wash_request_id) && !empty($wash_request_id))) {
            if (AES256CBC_STATUS == 1) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            if (!count($wash_request_exists)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {

                $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
                //$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();

                if ($zipcode) {

                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));
                    /* if(count($coveragezipcheck)){

                      if($coveragezipcheck->zip_color == 'yellow'){
                      $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                      }

                      if($coveragezipcheck->zip_color == 'red'){
                      $zipcode_price_factor = $zipcodeprice[0]['red'];
                      }

                      if($coveragezipcheck->zip_color == ''){
                      $zipcode_price_factor = $zipcodeprice[0]['blue'];
                      }

                      if($zipcodeprice[0]['price_unit'] == 'percent'){
                      $exp_surge_factor += $zipcode_price_factor;
                      $del_surge_factor += $zipcode_price_factor;
                      $prem_surge_factor += $zipcode_price_factor;
                      }

                      } */
                }

                $exp_surge_factor += $surgeprice[0]['express'];
                $del_surge_factor += $surgeprice[0]['deluxe'];
                $prem_surge_factor += $surgeprice[0]['premium'];

                $cars = $wash_request_exists->car_list;
                $packs = $wash_request_exists->package_list;
                $car_arr = explode(",", $cars);
                $pack_arr = explode(",", $packs);
                $cust_exists = Customers::model()->findByAttributes(array("id" => $wash_request_exists->customer_id));


                foreach ($car_arr as $ind => $carid) {


                    $cardata = Vehicle::model()->findByAttributes(array("id" => $carid));

                    if ($cardata->vehicle_type != 'S' && $cardata->vehicle_type != 'M' && $cardata->vehicle_type != 'L' && $cardata->vehicle_type != 'E') {
                        $veh_type = 'S';
                    } else {
                        $veh_type = $cardata->vehicle_type;
                    }

                    if ($cardata->upgrade_pack == 1) {
                        $washing_plan_det = Washingplans::model()->findByAttributes(array("vehicle_type" => $veh_type, "title" => $cardata->new_pack_name));
                        if (count($washing_plan_det)) {
                            $wash_price = $washing_plan_det->price;
                            if (count($coveragezipcheck)) {

                                if ($coveragezipcheck->zip_color == 'yellow') {
                                    $wash_price = $washing_plan_det->tier2_price;
                                }

                                if ($coveragezipcheck->zip_color == 'red') {
                                    $wash_price = $washing_plan_det->tier3_price;
                                }

                                if ($coveragezipcheck->zip_color == 'purple') {
                                    $wash_price = $washing_plan_det->tier4_price;
                                }
                            }

                            if ($cardata->new_pack_name == 'Express')
                                $wash_price = $wash_price + ($wash_price * ($exp_surge_factor / 100));
                            if ($cardata->new_pack_name == 'Deluxe')
                                $wash_price = $wash_price + ($wash_price * ($del_surge_factor / 100));
                            if ($cardata->new_pack_name == 'Premium')
                                $wash_price = $wash_price + ($wash_price * ($prem_surge_factor / 100));

                            /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                              $wash_price += $zipcode_price_factor;
                              $wash_price = (string) $wash_price;
                              } */

                            $wash_price = number_format($wash_price, 2);
                        } else
                            $wash_price = '';
                    }
                    else {
                        $washing_plan_det = Washingplans::model()->findByAttributes(array("vehicle_type" => $veh_type, "title" => $pack_arr[$ind]));
                        if (count($washing_plan_det)) {
                            $wash_price = $washing_plan_det->price;
                            if (count($coveragezipcheck)) {

                                if ($coveragezipcheck->zip_color == 'yellow') {
                                    $wash_price = $washing_plan_det->tier2_price;
                                }

                                if ($coveragezipcheck->zip_color == 'red') {
                                    $wash_price = $washing_plan_det->tier3_price;
                                }

                                if ($coveragezipcheck->zip_color == 'purple') {
                                    $wash_price = $washing_plan_det->tier4_price;
                                }
                            }

                            if ($pack_arr[$ind] == 'Express')
                                $wash_price = $wash_price + ($wash_price * ($exp_surge_factor / 100));
                            if ($pack_arr[$ind] == 'Deluxe')
                                $wash_price = $wash_price + ($wash_price * ($del_surge_factor / 100));
                            if ($pack_arr[$ind] == 'Premium')
                                $wash_price = $wash_price + ($wash_price * ($prem_surge_factor / 100));

                            /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                              $wash_price += $zipcode_price_factor;
                              $wash_price = (string) $wash_price;
                              } */

                            $wash_price = number_format($wash_price, 2);
                        } else
                            $wash_price = '';
                    }

                    $draft_vehicle_id = '';
                    $draft_vehicle_id = $wash_request_exists->draft_vehicle_id;
                    $new_vehicle_confirm = '';
                    $car_price_agent = 0;
                    $total_car_price = 0;
                    $total_car_price_agent = 0;
                    $bundle_fee = 0;
                    $bundle_fee_agent = 0;
                    $fifth_fee = 0;
                    $first_fee = 0;



                    if ($pack_arr[$ind] == 'Express')
                        $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                    if ($pack_arr[$ind] == 'Deluxe')
                        $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                    if ($pack_arr[$ind] == 'Premium')
                        $car_price_agent = number_format($wash_price * .75, 2, '.', '');

                    $total_car_price += $wash_price;
                    $total_car_price += 1; //safe handling fee
                    $total_car_price += $cardata->pet_hair;
                    $total_car_price += $cardata->lifted_vehicle;
                    $total_car_price += $cardata->exthandwax_addon;
                    $total_car_price += $cardata->extplasticdressing_addon;
                    $total_car_price += $cardata->extclaybar_addon;
                    $total_car_price += $cardata->waterspotremove_addon;
                    $total_car_price += $cardata->upholstery_addon;
                    $total_car_price += $cardata->floormat_addon;


                    if (($cust_exists->fifth_wash_points == 4) && (!$fifth_fee_check) && ($wash_request_exists->coupon_discount <= 0)) {
                        $fifth_fee = 5;
                        $total_car_price -= $fifth_fee;
                        $fifth_fee_check = 1;
                    }

                    /* if(!$cust_exists->is_first_wash && (!$first_fee_check)) {
                      $first_pack = '';
                      if($cardata->upgrade_pack == 1) $first_pack = $cardata->new_pack_name;
                      else $first_pack = $pack_arr[0];

                      if($first_pack == 'Premium') $first_fee = 10;
                      else $first_fee = 5;
                      $total_car_price -= $first_fee;
                      $first_fee_check = 1;
                      } */

                    if ((count($car_arr) > 1) && (!$fifth_fee) && ($wash_request_exists->coupon_discount <= 0)) {
                        $bundle_fee = 1;
                        $total_car_price -= $bundle_fee;
                    }

                    $total_car_price_agent += $car_price_agent;
                    $total_car_price_agent += $cardata->pet_hair * .80;
                    $total_car_price_agent += $cardata->lifted_vehicle * .80;
                    $total_car_price_agent += $cardata->exthandwax_addon * .80;
                    $total_car_price_agent += $cardata->extplasticdressing_addon * .80;
                    $total_car_price_agent += $cardata->extclaybar_addon * .80;
                    $total_car_price_agent += $cardata->waterspotremove_addon * .80;
                    $total_car_price_agent += $cardata->upholstery_addon * .80;
                    $total_car_price_agent += $cardata->floormat_addon * .80;


                    if (count($car_arr) > 1) {
                        $bundle_fee_agent = number_format(.80, 2, '.', '');
                        $total_car_price_agent -= $bundle_fee_agent;
                    }

                    $wash_time = $washing_plan_det->wash_time;
                    if ($cardata->pet_hair > 0)
                        $wash_time += 5;
                    if ($cardata->lifted_vehicle > 0)
                        $wash_time += 5;
                    if ($cardata->exthandwax_addon > 0)
                        $wash_time += 10;
                    if ($cardata->extplasticdressing_addon > 0)
                        $wash_time += 5;
                    if ($cardata->extclaybar_addon > 0)
                        $wash_time += 15;
                    if ($cardata->waterspotremove_addon > 0)
                        $wash_time += 10;
                    if ($cardata->upholstery_addon > 0)
                        $wash_time += 10;
                    if ($cardata->floormat_addon > 0)
                        $wash_time += 10;

                    $hours = floor($wash_time / 60);
                    $minutes = ($wash_time % 60);

                    if ($hours < 1) {
                        $washtime_str = sprintf('%02d min', $minutes);
                    } else {
                        if ($hours == 1) {
                            if ($minutes > 0)
                                $washtime_str = sprintf('%d hour %02d min', $hours, $minutes);
                            else
                                $washtime_str = sprintf('%d hour', $hours);
                        }
                        else {
                            if ($minutes > 0)
                                $washtime_str = sprintf('%d hours %02d min', $hours, $minutes);
                            else
                                $washtime_str = sprintf('%d hours', $hours);
                        }
                    }


                    $new_vehicle_confirm = $wash_request_exists->new_vehicle_confirm;
                    $vehicles[] = array("id" => $carid, "make" => $cardata->brand_name, "model" => $cardata->model_name, "license_no" => $cardata->vehicle_no, "vehicle_type" => $cardata->vehicle_type, "vehicle_category" => $cardata->vehicle_category, "vehicle_build" => $cardata->vehicle_build, "vehicle_image" => $cardata->vehicle_image, "handling_fee" => '1.00', "status" => $cardata->status, "eco_friendly" => $cardata->eco_friendly, "damage_points" => $cardata->damage_points, "damage_pic" => $cardata->damage_pic, "pet_hair" => $cardata->pet_hair, "pet_hair_agent" => number_format(round($cardata->pet_hair * .80, 2), 2), "lifted_vehicle" => $cardata->lifted_vehicle, "lifted_vehicle_agent" => number_format(round($cardata->lifted_vehicle * .80, 2), 2), "exthandwax_addon" => $cardata->exthandwax_addon, "exthandwax_addon_agent" => number_format(round($cardata->exthandwax_addon * .80, 2), 2), "extplasticdressing_addon" => $cardata->extplasticdressing_addon, "extplasticdressing_addon_agent" => number_format(round($cardata->extplasticdressing_addon * .80, 2), 2), "extclaybar_addon" => $cardata->extclaybar_addon, "extclaybar_addon_agent" => number_format(round($cardata->extclaybar_addon * .80, 2), 2), "waterspotremove_addon" => $cardata->waterspotremove_addon, "waterspotremove_addon_agent" => number_format(round($cardata->waterspotremove_addon * .80, 2), 2), "upholstery_addon" => $cardata->upholstery_addon, "upholstery_addon_agent" => number_format(round($cardata->upholstery_addon * .80, 2), 2), "floormat_addon" => $cardata->floormat_addon, "floormat_addon_agent" => number_format(round($cardata->floormat_addon * .80, 2), 2), "bundle_discount" => number_format($bundle_fee, 2), "bundle_discount_agent" => number_format($bundle_fee_agent, 2), "fifth_wash_discount" => number_format($fifth_fee, 2), "first_wash_discount" => number_format($first_fee, 2), "upgrade_pack" => $cardata->upgrade_pack, "new_pack_name" => $cardata->new_pack_name, "edit_vehicle" => $cardata->edit_vehicle, "remove_vehicle_from_kart" => $cardata->remove_vehicle_from_kart, "payment_type" => $pack_arr[$ind], "price" => $wash_price, "total_price" => number_format($total_car_price, 2), "price_agent" => $car_price_agent, "total_price_agent" => number_format($total_car_price_agent, 2), "wash_time" => $wash_time, "wash_time_str" => $washtime_str);
                }
                $result = true;
                $response = "Vehicles";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'draft_vehicle_id' => $draft_vehicle_id,
            'new_vehicle_confirm' => $new_vehicle_confirm,
            'vehicles' => $vehicles,
        );
        echo json_encode($json);
    }

    public function actionTestimageupload() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $username = Yii::app()->request->getParam('username');
        $check = getimagesize($_FILES["sample_image"]["tmp_name"]);
        if ($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
        }

        $target_dir = "uploads/";
        //if(file_exists())
        $ext = pathinfo($_FILES['sample_image']['name'], PATHINFO_EXTENSION);
        $new_image_name = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . $ext;
        $target_file = $target_dir . $new_image_name;
        $uploadOk = 1;

        if ($uploadOk == 0) {
            echo json_encode(array("error" => "Sorry, your file was not uploaded."));
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["sample_image"]["tmp_name"], $target_file)) {
                //array("image"=>"http://demo.thetatechnolabs.com/mobilewash/api/".$new_image_name);
                echo json_encode(array("image" => ROOT_URL . "/api/uploads/" . $new_image_name, "username" => $username));
            } else {
                echo json_encode(array("error" => "Sorry, there was an error uploading your file."));
            }
        }
    }

    public function actionEstimateSingle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $source_lat = Yii::app()->request->getParam('source_lat');
        $source_long = Yii::app()->request->getParam('source_long');
        $dest_lat = Yii::app()->request->getParam('dest_lat');
        $dest_long = Yii::app()->request->getParam('dest_long');

        $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $source_lat . "," . $source_long . "&destinations=" . $dest_lat . "," . $dest_long . "&mode=driving&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
        // $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=Ahmedabad&destinations=mumbai&mode=driving&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
        // echo $geourl;die;
        $ch = curl_init();

        /* curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); */
        curl_setopt($ch, CURLOPT_URL, $geourl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //	curl_setopt($ch,CURLOPT_HEADER, false);

        $georesult = curl_exec($ch);
        curl_close($ch);
        $geojsondata = json_decode($georesult);
        $status = $geojsondata->rows[0]->elements[0]->status;
        //$distance_in_meter = $geojsondata->rows[0]->elements[0]->distance->value;
        /* if($distance_in_meter <= 80000){
          $agent_lat = $loc['latitude'];
          $agent_long = $loc['longitude'];
          //echo "agent locations: ".$agent_lat." ".$agent_long."<br>";
          } else {
          $status = "ZERO_RESULTS";
          } */
        if ($status == "ZERO_RESULTS" || $status == "NOT FOUND") {
            $result = 'false';
            $response = 'No nearest agent found';
            $distance = 0;
            $eta = "Not Found";
        } else {
            $result = "TRUE";
            $response = 'distance found';
            $distance = $geojsondata->rows[0]->elements[0]->distance->text;
            $eta = $geojsondata->rows[0]->elements[0]->duration->text;
            //$agent_location = $agent_lat.",".$agent_long;
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'distance' => $distance,
            'estimate_time' => $eta
        );
        echo json_encode($json);
        //print_r([$source_lat,$dest_lat,$source_long,$dest_long]);die;
    }

    public function actionEstimateTime() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $customer_id = Yii::app()->request->getParam('customer_id');
        $longitude = Yii::app()->request->getParam('longitude');
        $latitude = Yii::app()->request->getParam('latitude');
        $distance_array = array();
        $distance_closest_array = array();
        $agent_location = array();
        if ((isset($customer_id) && !empty($customer_id)) &&
                (isset($longitude) && !empty($longitude)) &&
                (isset($latitude) && !empty($latitude))) {
            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customer_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            if (count($customer_exists) > 0) {

                $sql = "SELECT * FROM agent_locations";
                $command = Yii::app()->db->createCommand($sql)->queryAll();

                foreach ($command as $loc) {


                    /* --- Google Distance call --- */

                    $now = time();
                    $dept_time = date(strtotime('+5 minutes', $now));

                    //echo "customer locations: ".$latitude.",".$longitude."<br>";
                    $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $loc['latitude'] . "," . $loc['longitude'] . "&destinations=" . $latitude . "," . $longitude . "&mode=driving&traffic_model=best_guess&departure_time=" . $dept_time . "&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $geourl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                    $georesult = curl_exec($ch);
                    curl_close($ch);
                    $geojsondata = json_decode($georesult);
//print_r($geojsondata);

                    $status = $geojsondata->rows[0]->elements[0]->status;
                    $distance_in_meter = $geojsondata->rows[0]->elements[0]->distance->value;
//echo $distance_in_meter."<br>";
                    if (($distance_in_meter > 0) && ($distance_in_meter <= 32187)) {
                        $distance_array[$loc['agent_id']] = $distance_in_meter;
                    }
                }

//print_r($distance_array);
                if (count($distance_array)) {

                    $distance_closest_array = array_keys($distance_array, min($distance_array));
                    //print_r($distance_closest_array);
                    shuffle($distance_closest_array);
                    //print_r($distance_closest_array);
                    $closet_agent_id = $distance_closest_array[0];
                    //echo $closet_agent_id;
                }

                if ($closet_agent_id) {
                    $nearest_agent_details = AgentLocations::model()->findByAttributes(array("agent_id" => $closet_agent_id));

                    /* --- Google Distance call --- */

                    //echo "customer locations: ".$latitude.",".$longitude."<br>";
                    $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $nearest_agent_details['latitude'] . "," . $nearest_agent_details['longitude'] . "&destinations=" . $latitude . "," . $longitude . "&mode=driving&traffic_model=best_guess&departure_time=" . $dept_time . "&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $geourl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                    $georesult = curl_exec($ch);
                    curl_close($ch);
                    $geojsondata = json_decode($georesult);
//print_r($geojsondata);

                    $distance = $geojsondata->rows[0]->elements[0]->distance->text;
                    $eta = $geojsondata->rows[0]->elements[0]->duration_in_traffic->text;
                    $agent_location = $nearest_agent_details['latitude'] . "," . $nearest_agent_details['longitude'];
                    $json = array(
                        'result' => 'true',
                        'response' => 'Nearest Agent Location and ETA',
                        'agent_id' => $closet_agent_id,
                        'agent_location' => $agent_location,
                        'distance' => $distance,
                        'estimate_time' => $eta
                    );
                } else {
                    $json = array(
                        'result' => 'false',
                        'response' => 'No nearest agent found'
                    );
                }
            } else {

                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid customer'
                );
            }
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }


        echo json_encode($json);
    }

    // Generate braintree token
    public function actionGetClientToken() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('customer_id');

        if ((isset($customerid) && !empty($customerid))) {
            if (AES256CBC_STATUS == 1) {
                $customerid = $this->aes256cbc_crypt($customerid, 'd', AES256CBC_API_PASS);
            }
            $customers = Customers::model()->findByPk($customerid);
            if ($customers) {

                if ($customers->braintree_id) {
                    //if($customers->client_position == 'real') $result = Yii::app()->braintree->createClientToken_real($customers->braintree_id);
//else $result = Yii::app()->braintree->createClientToken($customers->braintree_id);
                    if ($customers->client_position == 'real')
                        $result = Yii::app()->braintree->createClientTokencustom_real();
                    else
                        $result = Yii::app()->braintree->createClientTokencustom();
                } else {
                    if ($customers->client_position == 'real') {
                        if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL))
                            $result = Yii::app()->braintree->createCustomer_real(array('firstName' => $customers->first_name . " " . $customers->last_name, 'company' => '-'));
                        else
                            $result = Yii::app()->braintree->createCustomer_real(array('firstName' => $customers->first_name . " " . $customers->last_name, 'company' => '-', 'email' => $customers->email));
                    }
                    else {
                        if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL))
                            $result = Yii::app()->braintree->createCustomer(array('firstName' => $customers->first_name . " " . $customers->last_name, 'company' => '-'));
                        else
                            $result = Yii::app()->braintree->createCustomer(array('firstName' => $customers->first_name . " " . $customers->last_name, 'company' => '-', 'email' => $customers->email));
                    }
                    if ($result['success'] != 0) {
                        $customer_id = $result['customer_id'];
                        $customers->braintree_id = $customer_id;
                        $customers->save(false);
                    }
                }

                $json = array(
                    'result' => 'true',
                    'response' => 'Client Token',
                    'client_token' => $result["token"],
                );
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid Customer'
                );
            }
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'Pass the required parameters'
            );
        }
        echo json_encode($json);
        die();
    }

    /*
      // Generate Payment with requested payment nonce
      public function actionCustomerPayment() {

      if(Yii::app()->request->getParam('key') != API_KEY){
      echo "Invalid api key";
      die();
      }

      $customer_id = Yii::app()->request->getParam('customer_id');
      $nonce = Yii::app()->request->getParam('nonce');
      $amount = Yii::app()->request->getParam('amount');
      $washing_request_id =Yii::app()->request->getParam('wash_request_id');
      $response = "Pass the required parameters";
      $result = "false";
      $payment_type = '';

      if((isset($customer_id) && !empty($customer_id)) && (isset($nonce) && !empty($nonce)) && (isset($amount) && !empty($amount))){

      $customers = Customers::model()->findByPk($customer_id);
      if(!$customers){
      $response = "Invalid customer id";
      $result = "false";
      } else {
      $request_data = ['amount' => $amount,'paymentMethodNonce' => $nonce,'options' => ['submitForSettlement' => True,'storeInVaultOnSuccess'=>true]];
      $Bresult = Yii::app()->braintree->sale($request_data);
      //print_r($Bresult);

      if($Bresult['success'] == 1) {
      //print_r($result);die;
      $response = "Payment successful";
      $result = "true";
      $payment_type = $Bresult['transaction_id'];
      $update_request = Washingrequests::model()->findByPk($washing_request_id);
      $update_request->transaction_id = $Bresult['transaction_id'];
      $update_request->save(false);

      } else {
      $result = "false";
      $response = $Bresult['message'];
      }
      }
      }

      $json = array(
      'result'=> $result,
      'response'=> $response,
      'transation_id'=> $payment_type
      );

      echo json_encode($json);
      die();

      }

     */

    // Generate Payment with requested payment nonce
    public function actionCustomerPaymentold() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $nonce = Yii::app()->request->getParam('nonce');
        $amount = Yii::app()->request->getParam('amount');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($nonce) && !empty($nonce)) && (isset($amount) && !empty($amount))) {
            $customers = Customers::model()->findByPk($customer_id);
            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else {

                $request_data = ['merchantAccountId' => 'blue_ladders_store', 'serviceFeeAmount' => "10.00", 'amount' => $amount, 'paymentMethodNonce' => $nonce];

                $Bresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                if ($Bresult['success'] == 1) {
                    $response = "Payment successful";
                    $result = "true";
                    $payment_type = $Bresult['transaction_id'];
                    //$update_request = Washingrequests::model()->findByPk($washing_request_id);
                    //$update_request->transaction_id = $Bresult['transaction_id'];
                    //$update_request->save(false);
                } else {
                    $result = "false";
                    $response = $Bresult['message'];
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'transation_id' => $payment_type
        );

        echo json_encode($json);
        die();
    }

    // Generate Payment with requested payment nonce
    public function actionCustomerPaymentWebsite() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $nonce = Yii::app()->request->getParam('nonce');
        $amount = Yii::app()->request->getParam('amount');
        $company_amount = Yii::app()->request->getParam('company_amount');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $payment_method_token = Yii::app()->request->getParam('payment_method_token');
        $cardno = Yii::app()->request->getParam('cardno');
        $cardname = Yii::app()->request->getParam('cardname');
        $cvv = Yii::app()->request->getParam('cvv');
        $mo_exp = Yii::app()->request->getParam('mo_exp');
        $yr_exp = Yii::app()->request->getParam('yr_exp');
        $bill_straddress = Yii::app()->request->getParam('bill_straddress');
        $bill_apt = '';
        $bill_apt = Yii::app()->request->getParam('bill_apt');
        $bill_city = Yii::app()->request->getParam('bill_city');
        $bill_state = Yii::app()->request->getParam('bill_state');
        $bill_zip = Yii::app()->request->getParam('bill_zip');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $is_token_changed = Yii::app()->request->getParam('is_token_changed');

        if ((isset($customer_id) && !empty($customer_id)) && (isset($nonce) && !empty($nonce)) && (isset($amount) && !empty($amount))) {

            $customers = Customers::model()->findByPk($customer_id);
            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else {


                if (!$payment_method_token) {

                    if ($customers->client_position == 'real') {
                        $createmethodresult = Yii::app()->braintree->addPaymentMethod_real([
                            'customerId' => $customers->braintree_id,
                            'cardholderName' => $cardname,
                            'number' => $cardno,
                            'expirationDate' => $mo_exp . "/" . $yr_exp,
                            'cvv' => $cvv,
                            'paymentMethodNonce' => $nonce,
                            'billingAddress' => [
                                'streetAddress' => $bill_straddress,
                                'extendedAddress' => $bill_apt,
                                'locality' => $bill_city,
                                'region' => $bill_state,
                                'postalCode' => $bill_zip
                            ],
                            'options' => [
                                'makeDefault' => true,
                                'verifyCard' => true
                            ]
                        ]);
                    } else {
                        $createmethodresult = Yii::app()->braintree->addPaymentMethod([
                            'customerId' => $customers->braintree_id,
                            'cardholderName' => $cardname,
                            'number' => $cardno,
                            'expirationDate' => $mo_exp . "/" . $yr_exp,
                            'cvv' => $cvv,
                            'paymentMethodNonce' => $nonce,
                            'billingAddress' => [
                                'streetAddress' => $bill_straddress,
                                'extendedAddress' => $bill_apt,
                                'locality' => $bill_city,
                                'region' => $bill_state,
                                'postalCode' => $bill_zip
                            ],
                            'options' => [
                                'makeDefault' => true,
                                'verifyCard' => true
                            ]
                        ]);
                    }


                    if (($createmethodresult['success'] == 1) && ($admin_username)) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $washing_request_id,
                            'admin_username' => $admin_username,
                            'action' => 'addpaymentmethod',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
                } else {

                    if ($customers->client_position == 'real') {
                        $createmethodresult = Yii::app()->braintree->updatePaymentMethod_real($payment_method_token, ['options' => [
                                'makeDefault' => true,
                                'verifyCard' => false
                            ]]
                        );
                    } else {
                        $createmethodresult = Yii::app()->braintree->updatePaymentMethod($payment_method_token, ['options' => [
                                'makeDefault' => true,
                                'verifyCard' => false
                            ]]
                        );
                    }

                    if (($createmethodresult['success'] == 1) && ($admin_username) && ($is_token_changed == 1)) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $washing_request_id,
                            'admin_username' => $admin_username,
                            'action' => 'updatepaymentmethod',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
                }

                if ($createmethodresult['success'] == 1) {

                    //print_r($result);die;
                    $response = "Payment method saved";
                    $result = "true";
                } else {
                    $result = "false";
                    $response = $createmethodresult['message'];
                }
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function actionCustomerPaymentUpfrontWebsite() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $nonce = Yii::app()->request->getParam('nonce');
        $amount = Yii::app()->request->getParam('amount');
        $company_amount = Yii::app()->request->getParam('company_amount');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $payment_method_token = Yii::app()->request->getParam('payment_method_token');
        $cardno = Yii::app()->request->getParam('cardno');
        $cardname = Yii::app()->request->getParam('cardname');
        $cvv = Yii::app()->request->getParam('cvv');
        $mo_exp = Yii::app()->request->getParam('mo_exp');
        $yr_exp = Yii::app()->request->getParam('yr_exp');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';
        $transaction_id = '';
        if ((isset($customer_id) && !empty($customer_id)) && (isset($nonce) && !empty($nonce)) && (isset($amount) && !empty($amount))) {

            $customers = Customers::model()->findByPk($customer_id);
            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else {

                if (!$payment_method_token) {

                    if ($customers->client_position == 'real') {
                        $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_amount, 'amount' => $amount, 'paymentMethodNonce' => $nonce, 'options' => ['storeInVaultOnSuccess' => true]];
                        $Bresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                    } else {
                        $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_amount, 'amount' => $amount, 'paymentMethodNonce' => $nonce, 'options' => ['storeInVaultOnSuccess' => true]];
                        $Bresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                    }
                } else {
                    if ($customers->client_position == 'real') {
                        $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_amount, 'amount' => $amount, 'paymentMethodToken' => $payment_method_token];
                        $Bresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                    } else {
                        $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_amount, 'amount' => $amount, 'paymentMethodToken' => $payment_method_token];
                        $Bresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                    }
                }





                if ($Bresult['success'] == 1) {

                    //print_r($result);die;
                    $response = "payment successful";
                    $result = "true";
                    $transaction_id = $Bresult['transaction_id'];
                } else {
                    $result = "false";
                    $response = $Bresult['message'];
                }
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'transaction_id' => $transaction_id
        );

        echo json_encode($json);
        die();
    }

    public function actionaddwashrequesttransactionid() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $transaction_id = Yii::app()->request->getParam('transaction_id');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $response = "Pass the required parameters";
        $result = "false";


        if ((isset($transaction_id) && !empty($transaction_id)) && (isset($washing_request_id) && !empty($washing_request_id))) {

            $wash_exists = Washingrequests::model()->findByPk($washing_request_id);
            if (!$wash_exists) {
                $response = "Invalid wash request id";
                $result = "false";
            } else {
                $result = 'true';
                $response = 'success';

                $update_request = Washingrequests::model()->findByPk($washing_request_id);
                $update_request->transaction_id = $transaction_id;
                $update_request->save(false);
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function actiongetcustomerpaymentmethods() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_methods = array();

        if ((isset($customer_id) && !empty($customer_id))) {
            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customer_exists = Customers::model()->findByPk($customer_id);
            if (!$customer_exists) {
                $response = "Invalid customer id";
                $result = "false";
            } else {

                $braintree_id = '';
                $braintree_id = $customer_exists->braintree_id;

                if ($customer_exists->client_position == 'real')
                    $Bresult = Yii::app()->braintree->getCustomerById_real($braintree_id);
                else
                    $Bresult = Yii::app()->braintree->getCustomerById($braintree_id);
                //var_dump($Bresult);
                if (count($Bresult->paymentMethods)) {
                    $result = 'true';
                    $response = 'payment methods';
                    foreach ($Bresult->paymentMethods as $index => $paymethod) {
                        $cardholder_name = '';
                        if ($paymethod->cardholderName)
                            $cardholder_name = $paymethod->cardholderName;
                        $explode_mask = explode('******', $paymethod->maskedNumber);
                        $countstr = strlen($explode_mask[0]) + 6;
                        $last4_show = str_repeat("*", $countstr) . $paymethod->last4;
                        $payment_methods[$index]['title'] = get_class($paymethod);
                        if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                            $payment_methods[$index]['title'] = 'Credit Card';
                            $payment_methods[$index]['payment_method_details'] = array("expirationMonth" => $paymethod->expirationMonth, "expirationYear" => $paymethod->expirationYear, "bin" => $paymethod->bin, "last4" => $paymethod->last4, "maskedNumber" => $paymethod->maskedNumber, "cardType" => $paymethod->cardType, "last4_show" => $last4_show, "token" => $paymethod->token, "cardname" => $cardholder_name, "cardimg" => $paymethod->imageUrl, "isDefault" => $paymethod->isDefault());
                        }

                        if ($payment_methods[$index]['title'] == 'Braintree\\PayPalAccount') {
                            $payment_methods[$index]['title'] = 'Paypal';
                            $payment_methods[$index]['payment_method_details'] = array("email" => $paymethod->email, "token" => $paymethod->token);
                        }
                    }
                } else {
                    $response = "No payment methods found";
                    $result = "false";
                }
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'payment_methods' => $payment_methods
        );

        echo json_encode($json);
        die();
    }

    public function actiondeletecustomerpaymentmethod() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $token = Yii::app()->request->getParam('token');
        $cust_type = Yii::app()->request->getParam('cust_type');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }

        $wash_exists = Washingrequests::model()->findByPk($wash_request_id);
        $customer_exists = Customers::model()->findByPk($wash_exists->customer_id);
        if (count($customer_exists)) {
            if ($customer_exists->client_position == 'real')
                $Bresult = Yii::app()->braintree->deletePaymentMethod_real($token);
            else
                $Bresult = Yii::app()->braintree->deletePaymentMethod($token);
        }
        else {
            if ($cust_type == 'real')
                $Bresult = Yii::app()->braintree->deletePaymentMethod_real($token);
            else
                $Bresult = Yii::app()->braintree->deletePaymentMethod($token);
        }


        if (($Bresult['success'] == 1) && ($admin_username)) {
            $washeractionlogdata = array(
                'wash_request_id' => $wash_request_id,
                'admin_username' => $admin_username,
                'action' => 'deletepaymentmethod',
                'action_date' => date('Y-m-d H:i:s'));

            Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
        }

        echo json_encode($Bresult);
        die();
    }

    public function actionapplypromocode() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $promo_code = Yii::app()->request->getParam('promo_code');

        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();

        if ((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($promo_code) && !empty($promo_code))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $cust_id_check = Customers::model()->findByAttributes(array('id' => $customer_id));
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id, 'customer_id' => $customer_id));
            $promo_dup_check = CustomerDiscounts::model()->findByAttributes(array('customer_id' => $customer_id, 'wash_request_id' => $wash_request_id, 'promo_code' => $promo_code));
            if (!count($cust_id_check)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else if ($promo_code != "FIRST5OFF") {
                $result = 'false';
                $response = 'Promo code not valid';
            } else if (count($promo_dup_check)) {
                $result = 'false';
                $response = 'Promo code expired. You already used this promo code.';
            } else {
                Yii::app()->db->createCommand()->insert('customer_discounts', array('customer_id' => $customer_id, 'wash_request_id' => $wash_request_id, 'promo_code' => $promo_code));

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id, "customer_id" => $customer_id));

                $plan_ids = '';
                $car_ids = '';

                $plan_ids = $washrequests_data->package_list;
                $car_ids = $washrequests_data->car_list;

                $plan_ids = explode(",", $plan_ids);
                $car_ids = explode(",", $car_ids);
                //var_dump($plan_ids);
                //var_dump($car_ids);

                $total = 0;

                $vehicles = array();
                foreach ($car_ids as $index => $car_id) {
                    $cardata = Vehicle::model()->findByAttributes(array("id" => $car_id));
                    $vehicle_no = $cardata->vehicle_no;
                    $brand_name = $cardata->brand_name;
                    $model_name = $cardata->model_name;
                    $vehicle_type = $cardata->vehicle_type;
                    $vehicle_image = $cardata->vehicle_image;
                    $vehicles[] = array('vehicle_no' => $vehicle_no, 'brand_name' => $brand_name, 'model_name' => $model_name, 'vehicle_type' => $vehicle_type, 'vehicle_image' => $vehicle_image);
                    $plandata = Washingplans::model()->findByAttributes(array("title" => $plan_ids[$index], "vehicle_type" => $vehicle_type));
                    $price = $plandata->price;
                    $fee = $plandata->handling_fee;
                    $total += $price + $fee;
                }

                //echo $total;
                $total = number_format($total, 2, '.', '');
                $total = $total - 5;
                $result = 'true';
                $response = 'Promo code applied';
                $wash_requests = array();
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'discounted_price' => $total
        );

        echo json_encode($json);
        die();
    }

    public function actionaccounthistory() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $limit = 10;
        if (Yii::app()->request->getParam('limit'))
            $limit = Yii::app()->request->getParam('limit');
        $page = 1;
        if (Yii::app()->request->getParam('page'))
            $page = Yii::app()->request->getParam('page');
        $total_entries = 0;
        $total_pages = 0;

        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();

        if ((isset($customer_id) && !empty($customer_id))) {
            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $cust_id_check = Customers::model()->findByAttributes(array('id' => $customer_id));
            if (!count($cust_id_check)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else {

                //$all_wash_requests = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id='".$customer_id."'")->queryAll();
                $all_wash_requests_count = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM washing_requests WHERE customer_id=:customer_id AND ((status='4' OR status='5' OR status='6' OR status='7') AND no_washer_cancel = 0) order by created_date desc")
                        ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
                        ->queryAll();
                $total_entries = $all_wash_requests_count[0]['count'];

                if ($total_entries) {
                    $total_pages = ceil($total_entries / $limit);
//$frag_pages = $total_entries % $limit;
//if($frag_pages > 0)$total_pages++;
                }

                $all_wash_requests = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')
                        ->where("customer_id=:customer_id AND ((status='4' OR status='5' OR status='6' OR status='7') AND no_washer_cancel = 0)", array(":customer_id" => $customer_id))
                        ->limit($limit)
                        ->offset(($page - 1) * $limit)
                        ->order(array('created_date desc'))
                        ->queryAll();

                if (count($all_wash_requests)) {

                    foreach ($all_wash_requests as $index => $wrequest) {

                        /* ----- total and discounts ------- */
                        /*
                          $handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
                          $data = array("wash_request_id"=>$wrequest['id'], "key" => API_KEY);
                          curl_setopt($handle, CURLOPT_POST, true);
                          curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                          curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                          $kartapiresult = curl_exec($handle);
                          curl_close($handle);
                          $jsondata = json_decode($kartapiresult);
                          //var_dump($jsondata);
                         */

                        $kartapiresult = $this->washingkart($wrequest['id'], API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                        $kartdata = json_decode($kartapiresult);

                        /* ----- total and discounts end ------- */

                        if (AES256CBC_STATUS == 1) {
                            $wash_requests[$index]['id'] = $this->aes256cbc_crypt($wrequest['id'], 'e', AES256CBC_API_PASS);
                        } else {
                            $wash_requests[$index]['id'] = $wrequest['id'];
                        }
                        $wash_requests[$index]['org_id'] = $wrequest['id'];
                        $wash_requests[$index]['date'] = $wrequest['order_for'];
                        $wash_requests[$index]['address'] = $wrequest['address'];

                        $plan_ids = $wrequest['package_list'];
                        $car_ids = $wrequest['car_list'];

                        $plan_ids = explode(",", $plan_ids);
                        $car_ids = explode(",", $car_ids);
                        //var_dump($plan_ids);
                        //var_dump($car_ids);

                        $total = 0;

                        $vehicles = array();
                        $inspect_details = array();
                        foreach ($car_ids as $index2 => $car_id) {
                            $cardata = Vehicle::model()->findByAttributes(array("id" => $car_id));
                            //$vehicle_no =  $cardata->vehicle_no;
                            $brand_name = $cardata->brand_name;
                            $model_name = $cardata->model_name;
                            $vehicle_type = $cardata->vehicle_type;
                            //$vehicle_image =  $cardata->vehicle_image;

                            $plandata = Washingplans::model()->findByAttributes(array("title" => $plan_ids[$index2], "vehicle_type" => $vehicle_type));
                            $price = $plandata->price;
                            $fee = $plandata->handling_fee;
                            $total += $price + $fee;
                            $vehicles[] = array('make' => $brand_name, 'model' => $model_name, 'wash_type' => $plandata->title);
                        }

                        //echo $total;
                        //$total = $wrequest['net_price']+$wrequest['tip_amount'];
                        $total = $kartdata->net_price;

                        $inspectsobject = Washinginspections::model()->findAllByAttributes(array("wash_request_id" => $wrequest['id']));
                        //echo count($inspectsobject);

                        if (count($inspectsobject)) {
                            foreach ($inspectsobject as $inspect) {

                                $inspectcarobject = Vehicle::model()->findByAttributes(array("id" => $inspect->vehicle_id));

                                $inspect_details[] = array('vehicle_id' => $inspectcarobject->id, 'vehicle_make' => $inspectcarobject->brand_name, 'vehicle_model' => $inspectcarobject->model_name, 'damage_pic' => $inspect->damage_pic);
                            }
                        }

                        $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];
                        $min_diff = 0;
                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($scheduledatetime);
                        if ($from_time > $to_time) {
                            $min_diff = round(($from_time - $to_time) / 60, 2);
                        }


                        $wash_requests[$index]['vehicle_details'] = $vehicles;
                        $wash_requests[$index]['inspection_details'] = $inspect_details;
                        $wash_requests[$index]['total'] = $total;
                        $wash_requests[$index]['bundle_discount'] = $wrequest['bundle_discount'];
                        $wash_requests[$index]['fifth_wash_discount'] = $wrequest['fifth_wash_discount'];
                        $wash_requests[$index]['first_wash_discount'] = $wrequest['first_wash_discount'];
                        if ($wrequest['coupon_discount'])
                            $wash_requests[$index]['coupon_discount'] = $wrequest['coupon_discount'];
                        else
                            $wash_requests[$index]['coupon_discount'] = 0;
                        $wash_requests[$index]['coupon_code'] = $wrequest['coupon_code'];
                        $wash_requests[$index]['tip_amount'] = $wrequest['tip_amount'];
                        $wash_requests[$index]['customer_wash_points'] = $wrequest['customer_wash_points'];
                        $wash_requests[$index]['cancel_fee'] = $wrequest['cancel_fee'];
                        $agentdata = Agents::model()->findByAttributes(array("id" => $wrequest['agent_id']));
                        $custdata = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));
                        $wash_requests[$index]['fifth_wash_points'] = $custdata->fifth_wash_points;

                        $agent_last_name = explode(" ", trim($agentdata->last_name));
                        $agentname = $agentdata->first_name . " " . strtoupper(substr($agent_last_name[0], 0, 1)) . ".";
                        $agentname = strtolower($agentname);
                        $agentname = ucwords($agentname);
                        $wash_requests[$index]['agent_name'] = $agentname;
                        if (AES256CBC_STATUS == 1) {
                            $wash_requests[$index]['agent_id'] = $this->aes256cbc_crypt($agentdata->id, 'e', AES256CBC_API_PASS);
                        } else {
                            $wash_requests[$index]['agent_id'] = $agentdata->id;
                        }

                        $wash_requests[$index]['real_washer_id'] = $agentdata->real_washer_id;
                        $washfeedbacks = Washingfeedbacks::model()->findByAttributes(array("customer_id" => $customer_id, "wash_request_id" => $wrequest['id']));
                        if ($washfeedbacks->agent_ratings)
                            $wash_requests[$index]['rating'] = number_format($washfeedbacks->agent_ratings, 2, '.', '');
                        else
                            $wash_requests[$index]['rating'] = $washfeedbacks->agent_ratings;

                        $wash_requests[$index]['status'] = $wrequest['status'];
                        $wash_requests[$index]['is_scheduled'] = $wrequest['is_scheduled'];

                        if ($min_diff <= 35) {
                            $wash_requests[$index]['is_reschedulable'] = 0;
                        } else {
                            $wash_requests[$index]['is_reschedulable'] = 1;
                        }

                        $wash_requests[$index]['schedule_date'] = $wrequest['schedule_date'];
                        $wash_requests[$index]['schedule_time'] = $wrequest['schedule_time'];
                        $wash_requests[$index]['reschedule_date'] = $wrequest['reschedule_date'];
                        $wash_requests[$index]['reschedule_time'] = $wrequest['reschedule_time'];
                        $wash_requests[$index]['scheduled_cars_info'] = $wrequest['scheduled_cars_info'];
                        $wash_requests[$index]['address_type'] = $wrequest['address_type'];
                        $wash_requests[$index]['schedule_total'] = $wrequest['schedule_total'];
                        $wash_requests[$index]['created_date'] = $wrequest['created_date'];
                    }
                }

                $result = 'true';
                $response = 'Acccount history';
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'total_entries' => $total_entries,
            'total_pages' => $total_pages,
            'wash_requests' => $wash_requests
        );

        echo json_encode($json);
        die();
    }

    public function actionschedulehistory() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');


        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();

        if ((isset($customer_id) && !empty($customer_id))) {

            $cust_id_check = Customers::model()->findByAttributes(array('id' => $customer_id));
            if (!count($cust_id_check)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else {

                //$all_wash_requests = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id='".$customer_id."'")->queryAll();



                $all_orders = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('schedule_orders')
                        ->where("customer_id=:customer_id", array(':customer_id' => $customer_id))
                        ->order(array('created_date desc'))
                        ->queryAll();

                if (count($all_orders)) {

                    foreach ($all_orders as $index => $order) {

                        $orders[$index]['id'] = $order['id'];
                        $orders[$index]['customer_id'] = $order['customer_id'];
                        $orders[$index]['name'] = $order['name'];
                        $orders[$index]['email'] = $order['email'];
                        $orders[$index]['phone'] = $order['phone'];
                        $orders[$index]['address'] = $order['address'];
                        $orders[$index]['address_type'] = $order['address_type'];
                        $orders[$index]['zipcode'] = $order['zipcode'];
                        $orders[$index]['schedule_date'] = $order['schedule_date'];
                        $orders[$index]['schedule_time'] = $order['schedule_time'];
                        $orders[$index]['vehicles'] = $order['vehicles'];
                        $orders[$index]['total_price'] = $order['total_price'];
                        $orders[$index]['transaction_id'] = $order['transaction_id'];
                        $orders[$index]['created_date'] = $order['created_date'];
                        $orders[$index]['status'] = $order['status'];
                    }
                    $result = 'true';
                    $response = 'Schedule orders history';
                } else {
                    $result = 'false';
                    $response = 'No history found';
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'schedule_orders' => $orders
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Returns feedback added confirmation.
     * * Post Required: customer id, feedback
     * * Url:- http://www.demo.com/index.php?r=customers/appfeedback
     * * Purpose:- adding customer application feedback
     */

    public function actionappfeedback() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $title = '';
        $title = Yii::app()->request->getParam('feedback_subject');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');


        $json = array();

        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($comments) && !empty($comments))) {
            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customers_id_check = Customers::model()->findByAttributes(array("id" => $customer_id));

            $cust_feedback_check = Appfeedbacks::model()->findByAttributes(array("customer_id" => $customer_id));
            if (!count($customers_id_check)) {
                $response = 'Invalid customer';
            } else {
                if (!count($cust_feedback_check)) {
                    $washfeedbackdata = array(
                        'customer_id' => $customer_id,
                        'comments' => $comments,
                        'title' => $title
                    );

                    Yii::app()->db->createCommand()->insert('app_feedbacks', $washfeedbackdata);
                } else {
                    $washfeedbackdata = array(
                        'customer_id' => $customer_id,
                        'comments' => $comments,
                        'title' => $title
                    );
                    $washfeedbackmodel = new Appfeedbacks;

                    $washfeedbackmodel->attributes = $washfeedbackdata;
                    $washfeedbackmodel->updateAll($washfeedbackdata, 'customer_id=:customer_id', array(':customer_id' => $customer_id));
                }

                $result = 'true';
                $response = "Feeback added";



                $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer App Feedback</h2>
<p><b>Customer Name:</b> " . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</p>
<p><b>Customer Email:</b> " . $customers_id_check->email . "</p>
    <p><b>Title:</b> " . $title . "</p>
<p><b>Comments:</b> " . $comments . "</p>";

                $from = Vargas::Obj()->getAdminFromEmail();
                $to = Vargas::Obj()->getAdminToEmailFeedBack();

                Vargas::Obj()->SendMail($to, $from, $message, "Customer App Feedback", 'mail-receipt');
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    //Client By status API

    public function actionclientsbystatus() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        //complete orders
        $online_clients = Customers::model()->findAllByAttributes(array("online_status" => 'online'));



        $onlineclients = array();
        foreach ($online_clients as $client) {

            $cust_last_wash_date = "";
            $customer_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $client->id));
            $cust_last_wash = Washingrequests::model()->findByAttributes(array("customer_id" => $client->id, "status" => 4), array('order' => 'id DESC'));
            if (count($cust_last_wash)) {
                $cust_last_wash_date = date('M d, Y h:i A', strtotime($cust_last_wash->order_for));
            } else
                $cust_last_wash_date = "N/A";

            $total_rate = count($customer_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($customer_feedbacks as $customers_feedbacks) {
                    $rate += $customers_feedbacks->customer_ratings;
                }

                $customer_rate = round($rate / $total_rate);
            } else {
                $customer_rate = 0;
            }

            $client_loc = CustomerLiveLocations::model()->findByAttributes(array("customer_id" => $client->id));
            //print_r($client_locs);

            $key = 'onlineclient_' . $client->id;
            $jsononlineclient = array();
            $jsononlineclient['id'] = $client->id;
            $jsononlineclient['customername'] = $client->first_name . " " . $client->last_name;
            $jsononlineclient['email'] = $client->email;
            $jsononlineclient['contact_number'] = $client->contact_number;
            $jsononlineclient['image'] = $client->image;
            $jsononlineclient['total_wash'] = $client->total_wash;
            $jsononlineclient['latitude'] = $client_loc->latitude;
            $jsononlineclient['longitude'] = $client_loc->longitude;
            $jsononlineclient['rating'] = $client->rating;
            $jsononlineclient['last_wash'] = $cust_last_wash_date;
            $onlineclients[$key] = $jsononlineclient;
        }


        //penidng orders
        $pending_orders = Washingrequests::model()->findAllByAttributes(array("status" => '0', "is_scheduled" => '0'));



        $pendingorders = array();
        foreach ($pending_orders as $pendings_orders) {
            $washer_detail = '';
            $customers_detail = Customers::model()->findByAttributes(array("id" => $pendings_orders->customer_id));
            if ($pendings_orders->agent_id)
                $washer_detail = Agents::model()->findByPk($pendings_orders->agent_id);

            $customer_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $pendings_orders->customer_id));

            $total_rate = count($customer_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($customer_feedbacks as $customers_feedbacks) {
                    $rate += $customers_feedbacks->customer_ratings;
                }

                $customer_rate = round($rate / $total_rate);
            } else {
                $customer_rate = 0;
            }


            $key = 'pending_order_' . $pendings_orders->id;
            $jsonpending = array();
            $jsonpending['id'] = $customers_detail->id;
            $jsonpending['wash_request_id'] = $pendings_orders->id;
            $jsonpending['customername'] = $customers_detail->first_name . " " . $customers_detail->last_name;
            $jsonpending['email'] = $customers_detail->email;
            $jsonpending['contact_number'] = $customers_detail->contact_number;
            $jsonpending['image'] = $customers_detail->image;
            $jsonpending['total_wash'] = $customers_detail->total_wash;
            $jsonpending['latitude'] = $pendings_orders->latitude;
            $jsonpending['longitude'] = $pendings_orders->longitude;
            $jsonpending['agent_id'] = $pendings_orders->agent_id;
            if (count($washer_detail)) {
                $jsonpending['agent_badge_id'] = $washer_detail->real_washer_id;
                $jsonpending['agent_name'] = $washer_detail->first_name . " " . $washer_detail->last_name;
                $jsonpending['agent_phone'] = $washer_detail->phone_number;
            }
            $jsonpending['rating'] = $customers_detail->rating;
            $jsonpending['created_date'] = $pendings_orders->created_date;
            $pendingorders[$key] = $jsonpending;
        }


        //schedule orders
        $schedule_orders = Washingrequests::model()->findAllByAttributes(array("status" => '0', "is_scheduled" => '1', 'wash_request_position' => APP_ENV));

        $scheduleorders = array();
        foreach ($schedule_orders as $schedorder) {
            $washer_detail = '';
            $customers_detail = Customers::model()->findByAttributes(array("id" => $schedorder->customer_id));

            if ($schedorder->agent_id)
                $washer_detail = Agents::model()->findByPk($schedorder->agent_id);

            $customer_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $schedorder->customer_id));

            $total_rate = count($customer_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($customer_feedbacks as $customers_feedbacks) {
                    $rate += $customers_feedbacks->customer_ratings;
                }

                $customer_rate = round($rate / $total_rate);
            } else {
                $customer_rate = 0;
            }


            $key = 'schedule_order_' . $schedorder->id;
            $jsonpending = array();
            $jsonpending['id'] = $customers_detail->id;
            $jsonpending['wash_request_id'] = $schedorder->id;
            $jsonpending['customername'] = $customers_detail->first_name . " " . $customers_detail->last_name;
            $jsonpending['email'] = $customers_detail->email;
            $jsonpending['contact_number'] = $customers_detail->contact_number;
            $jsonpending['image'] = $customers_detail->image;
            $jsonpending['total_wash'] = $customers_detail->total_wash;
            $jsonpending['latitude'] = $schedorder->latitude;
            $jsonpending['longitude'] = $schedorder->longitude;
            $jsonpending['rating'] = $customers_detail->rating;
            $jsonpending['created_date'] = $schedorder->created_date;
            $jsonpending['agent_id'] = $schedorder->agent_id;
            if (count($washer_detail)) {
                $jsonpending['agent_badge_id'] = $washer_detail->real_washer_id;
                $jsonpending['agent_name'] = $washer_detail->first_name . " " . $washer_detail->last_name;
                $jsonpending['agent_phone'] = $washer_detail->phone_number;
            }
            $jsonpending['schedule_date'] = date('m-d-Y', strtotime($schedorder->order_for));
            $jsonpending['schedule_time'] = date('h:i A', strtotime($schedorder->order_for));
            $datediff = (strtotime($schedorder->order_for)) - (strtotime(date("Y-m-d")));
            $difference = floor($datediff / (60 * 60 * 24));
            if ($difference > 0) {
                $jsonpending['order_for'] = 'tomorrow';
            } else {
                $jsonpending['order_for'] = 'today';
            }

            $scheduleorders[$key] = $jsonpending;
        }



        // Get Processing Orders
        /* $processing_orders = Yii::app()->db->createCommand()
          ->select('*')
          ->from('washing_requests')  //Your Table name
          ->where('status>=1 AND status<=3') // Write your where condition here
          ->queryAll(); */

        $processing_orders = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE status>=1 AND status<=3")->queryAll();

        $processingorders = array();

        foreach ($processing_orders as $process_orders) {

            $customers_detail = Customers::model()->findByAttributes(array("id" => $process_orders['customer_id']));
            $customer_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $process_orders['customer_id']));

            $total_rate = count($customer_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($customer_feedbacks as $customers_feedbacks) {
                    $rate += $customers_feedbacks->customer_ratings;
                }

                $customer_rate = round($rate / $total_rate);
            } else {
                $customer_rate = 0;
            }


            $key = 'processing_order_' . $process_orders['id'];
            $jsonprocessing = array();
            $jsonprocessing['id'] = $customers_detail->id;
            $jsonprocessing['wash_request_id'] = $process_orders['id'];
            $jsonprocessing['customername'] = $customers_detail->first_name . " " . $customers_detail->last_name;
            $jsonprocessing['email'] = $customers_detail->email;
            $jsonprocessing['contact_number'] = $customers_detail->contact_number;
            $jsonprocessing['image'] = $customers_detail->image;
            $jsonprocessing['total_wash'] = $customers_detail->total_wash;
            $jsonprocessing['latitude'] = $process_orders['latitude'];
            $jsonprocessing['longitude'] = $process_orders['longitude'];
            $jsonprocessing['rating'] = $customers_detail->rating;
            $processingorders[$key] = $jsonprocessing;
        }

        $clientstatus['online_clients'] = $onlineclients;
        $clientstatus['pending_orders'] = $pendingorders;
        $clientstatus['schedule_orders'] = $scheduleorders;
        $clientstatus['processing_orders'] = $processingorders;
        echo json_encode($clientstatus);
        die();
    }

    public function actionEditCustomerAdmin() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('customerid');
        $email = Yii::app()->request->getParam('email');
        if (!empty($email)) {
            $update_password = Customers::model()->updateAll(array('email' => $email), 'id=:id', array(':id' => $customerid));
            $value = $email;
        }



        $result = 'true';
        $response = $value . ' updated successfully';
        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    /* ----------- Add Draft Vehicle --------- */

    public function actionAddDraftVehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $result = 'false';
        $response = 'Pass the required parameters';
        $vehicle_no = "N/A";
        $vehicle_type = 'S';
        $vehicle_category = '';
        $vehicle_build = '';
        $package_price = 0;
        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_package = '';
        $zipcode = '';
        if (Yii::app()->request->getParam('zipcode'))
            $zipcode = Yii::app()->request->getParam('zipcode');
        if (Yii::app()->request->getParam('vehicle_no'))
            $vehicle_no = Yii::app()->request->getParam('vehicle_no');
        if (Yii::app()->request->getParam('wash_package'))
            $wash_package = Yii::app()->request->getParam('wash_package');
        if (Yii::app()->request->getParam('vehicle_category'))
            $vehicle_category = Yii::app()->request->getParam('vehicle_category');
        if (Yii::app()->request->getParam('vehicle_build'))
            $vehicle_build = Yii::app()->request->getParam('vehicle_build');
        $brand_name = Yii::app()->request->getParam('brand_name');
        $model_name = Yii::app()->request->getParam('model_name');
        $vehicle_image = Yii::app()->request->getParam('vehicle_image');
        $pet_hair = 0;
        if (Yii::app()->request->getParam('pet_hair'))
            $pet_hair = Yii::app()->request->getParam('pet_hair');
        $lifted_vehicle = 0;
        if (Yii::app()->request->getParam('lifted_vehicle'))
            $lifted_vehicle = Yii::app()->request->getParam('lifted_vehicle');
        $exthandwax_addon = 0;
        if (Yii::app()->request->getParam('exthandwax_addon'))
            $exthandwax_addon = Yii::app()->request->getParam('exthandwax_addon');
        $extplasticdressing_addon = 0;
        if (Yii::app()->request->getParam('extplasticdressing_addon'))
            $extplasticdressing_addon = Yii::app()->request->getParam('extplasticdressing_addon');
        $extclaybar_addon = 0;
        if (Yii::app()->request->getParam('extclaybar_addon'))
            $extclaybar_addon = Yii::app()->request->getParam('extclaybar_addon');
        $waterspotremove_addon = 0;
        if (Yii::app()->request->getParam('waterspotremove_addon'))
            $waterspotremove_addon = Yii::app()->request->getParam('waterspotremove_addon');
        $upholstery_addon = 0;
        if (Yii::app()->request->getParam('upholstery_addon'))
            $upholstery_addon = Yii::app()->request->getParam('upholstery_addon');
        $floormat_addon = 0;
        if (Yii::app()->request->getParam('floormat_addon'))
            $floormat_addon = Yii::app()->request->getParam('floormat_addon');
        $coveragezipcheck = 0;

        if (Yii::app()->request->getParam('vehicle_type'))
            $vehicle_type = Yii::app()->request->getParam('vehicle_type');
        $vehicle = array();
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        if ((isset($customer_id) && !empty($customer_id)) &&
                (isset($brand_name) && !empty($brand_name)) &&
                (isset($model_name) && !empty($model_name)) &&
                (isset($vehicle_image) && !empty($vehicle_image))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
            //$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();

            if ($zipcode) {

                $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));
                /* if(count($coveragezipcheck)){

                  if($coveragezipcheck->zip_color == 'yellow'){
                  $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                  }

                  if($coveragezipcheck->zip_color == 'red'){
                  $zipcode_price_factor = $zipcodeprice[0]['red'];
                  }

                  if($coveragezipcheck->zip_color == ''){
                  $zipcode_price_factor = $zipcodeprice[0]['blue'];
                  }

                  if($zipcodeprice[0]['price_unit'] == 'percent'){
                  $exp_surge_factor += $zipcode_price_factor;
                  $del_surge_factor += $zipcode_price_factor;
                  $prem_surge_factor += $zipcode_price_factor;
                  }

                  } */
            }

            $exp_surge_factor += $surgeprice[0]['express'];
            $del_surge_factor += $surgeprice[0]['deluxe'];
            $prem_surge_factor += $surgeprice[0]['premium'];

            $customer_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            if (count($customer_exists) > 0) {

                $image = 'no_pic.jpg';
                $siteUrl = Yii::app()->getBaseUrl(true);
                if ((!empty($vehicle_image)) && (strpos($vehicle_image, 'api/images/veh_img') === false)) {

                    $directorypath1 = realpath(Yii::app()->basePath . '/../images/veh_img');
                    $img = str_replace('data:image/PNG;base64,', '', $vehicle_image);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $md5 = md5(uniqid(rand(), true));
                    $name = $customer_id . '_' . $md5 . ".jpg";
                    $path = $directorypath1 . '/' . $name;
                    $SiteUrl = Yii::app()->getBaseUrl(true);
                    $image = $SiteUrl . '/images/veh_img/' . $customer_id . '_' . $md5 . ".jpg";
                    file_put_contents($path, $data);
                }

                if (strpos($vehicle_image, 'api/images/veh_img') !== false) {
                    $image = $vehicle_image;
                }
                /*
                  $allvehicle_obj = Allvehicles::model()->findByAttributes(array("make" => $brand_name, "model" => $model_name));
                  $vehicle_type = $allvehicle_obj->type;
                 */
                //echo $vehicle_type;

                $washplan_id_exists = Washingplans::model()->findByAttributes(array("title" => $wash_package, "vehicle_type" => $vehicle_type));
                if (count($washplan_id_exists)) {
                    $package_price = $washplan_id_exists->price;

                    if (count($coveragezipcheck)) {

                        if ($coveragezipcheck->zip_color == 'yellow') {
                            $package_price = $washplan_id_exists->tier2_price;
                        }

                        if ($coveragezipcheck->zip_color == 'red') {
                            $package_price = $washplan_id_exists->tier3_price;
                        }

                        if ($coveragezipcheck->zip_color == 'purple') {
                            $package_price = $washplan_id_exists->tier4_price;
                        }
                    }
                    if ($wash_package == 'Express')
                        $package_price = $package_price + ($package_price * ($exp_surge_factor / 100));
                    if ($wash_package == 'Deluxe')
                        $package_price = $package_price + ($package_price * ($del_surge_factor / 100));
                    if ($wash_package == 'Premium')
                        $package_price = $package_price + ($package_price * ($prem_surge_factor / 100));

                    /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                      $package_price += $zipcode_price_factor;
                      $package_price = (string) $package_price;
                      } */

                    $package_price = number_format($package_price, 2);
                }
                try {
                    $resIns = Yii::app()->db->createCommand()
                            ->insert('draft_customer_vehicals', array('customer_id' => $customer_id,
                        'vehicle_no' => $vehicle_no,
                        'brand_name' => $brand_name,
                        'model_name' => $model_name,
                        'vehicle_image' => $image,
                        'vehicle_type' => $vehicle_type,
                        'vehicle_category' => $vehicle_category,
                        'vehicle_build' => $vehicle_build,
                        'wash_package' => $wash_package,
                        'package_price' => $package_price,
                        'pet_hair' => $pet_hair,
                        'lifted_vehicle' => $lifted_vehicle,
                        'exthandwax_addon' => $exthandwax_addon,
                        'extplasticdressing_addon' => $extplasticdressing_addon,
                        'extclaybar_addon' => $extclaybar_addon,
                        'waterspotremove_addon' => $waterspotremove_addon,
                        'upholstery_addon' => $upholstery_addon,
                        'floormat_addon' => $floormat_addon
                    ));
                } catch (Exception $e) {
                    //echo $e;
                }
                //var_dump($resIns);
                if ($resIns) {
                    $result = 'true';
                    $response = 'Vehicle added';

                    $qrVehicles = Yii::app()->db->createCommand()
                            ->select('*')->from('draft_customer_vehicals')
                            ->where("id='" . Yii::app()->db->getLastInsertID() . "'", array())
                            ->queryRow();

                    $vehicle = array('id' => $qrVehicles['id'],
                        'vehicle_no' => $vehicle_no,
                        'brand_name' => $brand_name,
                        'model_name' => $model_name,
                        'vehicle_image' => $image,
                        'vehicle_type' => $vehicle_type,
                        'vehicle_category' => $vehicle_category,
                        'vehicle_build' => $vehicle_build,
                        'wash_package' => $wash_package,
                        'package_price' => $package_price,
                        'pet_hair' => $pet_hair,
                        'lifted_vehicle' => $lifted_vehicle,
                        'exthandwax_addon' => $exthandwax_addon,
                        'extplasticdressing_addon' => $extplasticdressing_addon,
                        'extclaybar_addon' => $extclaybar_addon,
                        'waterspotremove_addon' => $waterspotremove_addon,
                        'upholstery_addon' => $upholstery_addon,
                        'floormat_addon' => $floormat_addon
                    );
                } else {
                    $response = 'Internal error';
                }
            } else {
                $response = 'Invalid customer';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicle' => $vehicle
        );
        echo json_encode($json);
    }

    /* ----------- Get Draft Vehicle by ID --------- */

    public function actiongetdraftvehiclebyid() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $action = '';
        $action = Yii::app()->request->getParam('action');
        $zipcode = '';
        if (Yii::app()->request->getParam('zipcode'))
            $zipcode = Yii::app()->request->getParam('zipcode');
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        $coveragezipcheck = 0;
        $vehicle = array();
        if ((isset($vehicle_id) && !empty($vehicle_id))) {

            if (AES256CBC_STATUS == 1) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $vehicle_exists = CustomerDraftVehicle::model()->findByAttributes(array("id" => $vehicle_id));
            if (count($vehicle_exists) > 0) {
                $result = 'true';
                $response = 'Vehicle details';

                $cust_exists = Customers::model()->findByAttributes(array("id" => $vehicle_exists->customer_id));
                $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

                $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
//$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();

                if ($zipcode) {

                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));
                    /* if(count($coveragezipcheck)){

                      if($coveragezipcheck->zip_color == 'yellow'){
                      $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                      }

                      if($coveragezipcheck->zip_color == 'red'){
                      $zipcode_price_factor = $zipcodeprice[0]['red'];
                      }

                      if($coveragezipcheck->zip_color == ''){
                      $zipcode_price_factor = $zipcodeprice[0]['blue'];
                      }

                      if($zipcodeprice[0]['price_unit'] == 'percent'){
                      $exp_surge_factor += $zipcode_price_factor;
                      $del_surge_factor += $zipcode_price_factor;
                      $prem_surge_factor += $zipcode_price_factor;
                      }

                      } */
                }

                $exp_surge_factor += $surgeprice[0]['express'];
                $del_surge_factor += $surgeprice[0]['deluxe'];
                $prem_surge_factor += $surgeprice[0]['premium'];


                $wash_price = 0;
                $washing_plan_det = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_exists->vehicle_type, "title" => $vehicle_exists->wash_package));
                if ($vehicle_exists->package_price) {
                    $wash_price = $vehicle_exists->package_price;
                } else {
                    $wash_price = $washing_plan_det->price;
                }

                if (count($coveragezipcheck)) {

                    if ($coveragezipcheck->zip_color == 'yellow') {
                        $wash_price = $washing_plan_det->tier2_price;
                    }

                    if ($coveragezipcheck->zip_color == 'red') {
                        $wash_price = $washing_plan_det->tier3_price;
                    }

                    if ($coveragezipcheck->zip_color == 'purple') {
                        $wash_price = $washing_plan_det->tier4_price;
                    }
                }



                /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                  $wash_price += $zipcode_price_factor;
                  $wash_price = (string) $wash_price;
                  } */

                if ($vehicle_exists->wash_package == 'Express') {

                    $wash_price = $wash_price + ($wash_price * ($exp_surge_factor / 100));
                }

                if ($vehicle_exists->wash_package == 'Deluxe') {
                    //$wash_price += $surgeprice[0]['deluxe'];
                    //$wash_price = (string) $wash_price;
                    $wash_price = $wash_price + ($wash_price * ($del_surge_factor / 100));
                }

                if ($vehicle_exists->wash_package == 'Premium') {

                    $wash_price = $wash_price + ($wash_price * ($prem_surge_factor / 100));
                }

                $car_price_agent = 0;
                $total_car_price = 0;
                $total_car_price_agent = 0;
                $bundle_fee = 0;
                $bundle_fee_agent = 0;
                $fifth_fee = 0;
                $first_fee = 0;
                $total_cars = 0;
                $fifth_points = $cust_exists->fifth_wash_points;

                if ($wash_request_exists->id) {
                    $car_arr = explode(",", $wash_request_exists->car_list);
                    $total_cars = count($car_arr);
                }

                foreach ($car_arr as $carid) {
                    $veh_check = Vehicle::model()->findByAttributes(array("id" => $carid));
                    if ($veh_check->status != 6) {
                        $fifth_points ++;
                        if ($fifth_points > 5)
                            $fifth_points = 1;
                    }
                }



                if ($vehicle_exists->wash_package == 'Express')
                    $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                if ($vehicle_exists->wash_package == 'Deluxe')
                    $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                if ($vehicle_exists->wash_package == 'Premium')
                    $car_price_agent = number_format($wash_price * .75, 2, '.', '');

                $total_car_price += $wash_price;
                $total_car_price += 1; //handling fee
                $total_car_price += $vehicle_exists->pet_hair;
                $total_car_price += $vehicle_exists->lifted_vehicle;
                $total_car_price += $vehicle_exists->exthandwax_addon;
                $total_car_price += $vehicle_exists->extplasticdressing_addon;
                $total_car_price += $vehicle_exists->extclaybar_addon;
                $total_car_price += $vehicle_exists->waterspotremove_addon;
                $total_car_price += $vehicle_exists->upholstery_addon;
                $total_car_price += $vehicle_exists->floormat_addon;

                if ($action == 'edit_vehicle') {
                    if ($fifth_points == 5) {
                        $fifth_fee = 5;
                        $total_car_price -= $fifth_fee;
                    }
                } else {
                    if ($fifth_points == 4) {
                        $fifth_fee = 5;
                        $total_car_price -= $fifth_fee;
                    }
                }


                /* if(!$cust_exists->is_first_wash && (!$total_cars)) {
                  if($vehicle_exists->wash_package == 'Premium') $first_fee = 10;
                  else $first_fee = 5;
                  $total_car_price -= $first_fee;
                  } */

                if ($action == 'edit_vehicle') {
                    if ($total_cars > 1 && (!$fifth_fee) && ($wash_request_exists->coupon_discount <= 0)) {
                        $bundle_fee = 1;
                        $total_car_price -= $bundle_fee;
                    }
                } else {
                    if ($total_cars > 0 && (!$fifth_fee) && ($wash_request_exists->coupon_discount <= 0)) {
                        $bundle_fee = 1;
                        $total_car_price -= $bundle_fee;
                    }
                }


                $total_car_price_agent += $car_price_agent;
                $total_car_price_agent += $vehicle_exists->pet_hair * .80;
                $total_car_price_agent += $vehicle_exists->lifted_vehicle * .80;
                $total_car_price_agent += $vehicle_exists->exthandwax_addon * .80;
                $total_car_price_agent += $vehicle_exists->extplasticdressing_addon * .80;
                $total_car_price_agent += $vehicle_exists->extclaybar_addon * .80;
                $total_car_price_agent += $vehicle_exists->waterspotremove_addon * .80;
                $total_car_price_agent += $vehicle_exists->upholstery_addon * .80;
                $total_car_price_agent += $vehicle_exists->floormat_addon * .80;


                if ($action == 'edit_vehicle') {
                    if ($total_cars > 1) {
                        $bundle_fee_agent = number_format(.80, 2, '.', '');
                        $total_car_price_agent -= $bundle_fee_agent;
                    }
                } else {
                    if ($total_cars > 0) {
                        $bundle_fee_agent = number_format(.80, 2, '.', '');
                        $total_car_price_agent -= $bundle_fee_agent;
                    }
                }


                $wash_time = $washing_plan_det->wash_time;
                if ($vehicle_exists->pet_hair > 0)
                    $wash_time += 5;
                if ($vehicle_exists->lifted_vehicle > 0)
                    $wash_time += 5;
                if ($vehicle_exists->exthandwax_addon > 0)
                    $wash_time += 10;
                if ($vehicle_exists->extplasticdressing_addon > 0)
                    $wash_time += 5;
                if ($vehicle_exists->extclaybar_addon > 0)
                    $wash_time += 15;
                if ($vehicle_exists->waterspotremove_addon > 0)
                    $wash_time += 10;
                if ($vehicle_exists->upholstery_addon > 0)
                    $wash_time += 10;
                if ($vehicle_exists->floormat_addon > 0)
                    $wash_time += 10;

                $hours = floor($wash_time / 60);
                $minutes = ($wash_time % 60);

                if ($hours < 1) {
                    $washtime_str = sprintf('%02d min', $minutes);
                } else {
                    if ($hours == 1) {
                        if ($minutes > 0)
                            $washtime_str = sprintf('%d hour %02d min', $hours, $minutes);
                        else
                            $washtime_str = sprintf('%d hour', $hours);
                    }
                    else {
                        if ($minutes > 0)
                            $washtime_str = sprintf('%d hours %02d min', $hours, $minutes);
                        else
                            $washtime_str = sprintf('%d hours', $hours);
                    }
                }



                $vehicle = array(
                    'vehicle_no' => $vehicle_exists->vehicle_no,
                    'brand_name' => $vehicle_exists->brand_name,
                    'model_name' => $vehicle_exists->model_name,
                    'vehicle_image' => $vehicle_exists->vehicle_image,
                    'vehicle_type' => $vehicle_exists->vehicle_type,
                    'vehicle_category' => $vehicle_exists->vehicle_category,
                    'vehicle_build' => $vehicle_exists->vehicle_build,
                    'wash_package' => $vehicle_exists->wash_package,
                    'package_price' => number_format($wash_price, 2),
                    "total_price" => number_format($total_car_price, 2),
                    "price_agent" => $car_price_agent,
                    "total_price_agent" => number_format($total_car_price_agent, 2),
                    'pet_hair' => number_format($vehicle_exists->pet_hair, 2),
                    'lifted_vehicle' => number_format($vehicle_exists->lifted_vehicle, 2),
                    'exthandwax_addon' => number_format($vehicle_exists->exthandwax_addon, 2),
                    'extplasticdressing_addon' => number_format($vehicle_exists->extplasticdressing_addon, 2),
                    'extclaybar_addon' => number_format($vehicle_exists->extclaybar_addon, 2),
                    'waterspotremove_addon' => number_format($vehicle_exists->waterspotremove_addon, 2),
                    'upholstery_addon' => number_format($vehicle_exists->upholstery_addon, 2),
                    'floormat_addon' => number_format($vehicle_exists->floormat_addon, 2),
                    "handling_fee" => '1.00',
                    "bundle_discount" => number_format($bundle_fee, 2),
                    "bundle_discount_agent" => number_format($bundle_fee_agent, 2),
                    "fifth_wash_discount" => number_format($fifth_fee, 2),
                    "first_wash_discount" => number_format($first_fee, 2),
                    'wash_time' => $wash_time,
                    "wash_time_str" => $washtime_str
                );
            }else {
                $response = "Invalid vehicle";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'vehicle' => $vehicle
        );
        echo json_encode($json);
    }

    /* ----------- Delete Draft Vehicle --------- */

    public function actionDeleteDraftVehicle() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Pass the required parameters';

        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $vehicles = array();

        if ((isset($vehicle_id) && !empty($vehicle_id))) {
            $vehicle_exists = CustomerDraftVehicle::model()->findByAttributes(array("id" => $vehicle_id));

            if (!count($vehicle_exists)) {
                $response = "Invalid vehicle";
            } else {
                $response = "Vehicle deleted";
                $result = 'true';
                $siteUrl = Yii::app()->getBaseUrl(true);

                //unlink($vehicle_exists->vehicle_image);
                CustomerDraftVehicle::model()->deleteAll("id=:id", array(":id" => $vehicle_id));
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
    }

    public function actionclientsadmin() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $search = Yii::app()->request->getParam('search');
        $limit = Yii::app()->request->getParam('limit');

        if (!$limit)
            $customers = Customers::model()->findAll(array('order' => 'created_date desc'));
        else
            $customers = Customers::model()->findAll(array('order' => 'created_date desc', 'limit' => $limit));


        $customerdetail = array();
        foreach ($customers as $customername) {
            $totalwash = 0;
            $customersid = $customername->id;
            $totalwash_arr = Washingrequests::model()->findAllByAttributes(array("status" => 4, "customer_id" => $customername->id));
            $custfirstdevice = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $customername->id . "' ORDER BY device_add_date ASC LIMIT 1")->queryAll();

            $totalwash = count($totalwash_arr);

            $custlocation = CustomerLocation::model()->findByAttributes(array("customer_id" => $customername->id));

            $custspent = Yii::app()->db->createCommand("SELECT SUM(net_price) FROM washing_requests WHERE customer_id = :customer_id AND  status = 4 AND net_price > 0")
                    ->bindValue(':customer_id', $customername->id, PDO::PARAM_STR)
                    ->queryAll();
            $totalpaid = 0;
            $totalpaid = $custspent[0]['SUM(net_price)'];

            $city = 'N/A';
            $address = 'N/A';

            if (!empty($custlocation->location_address)) {
                $addrs_exp = explode(',', $custlocation->location_address);
                $address = $addrs_exp[0];
                if (!empty(trim($addrs_exp[1])) && trim($addrs_exp[1]) != 'CA') {
                    $city = $addrs_exp[1];
                } elseif (trim($addrs_exp[1]) == 'CA') {
                    $city = $custlocation->location_address;
                }
            }


            $json = array();
            $json['id'] = $customername->id;
            $json['name'] = $customername->first_name . " " . $customername->last_name;
            $json['user_type'] = $customername->login_type;
            $json['email'] = $customername->email;
            $json['phone'] = $customername->contact_number;
            $json['rating'] = $customername->rating;

            if (count($custfirstdevice))
                $json['device_type'] = $custfirstdevice[0]['device_type'];
            else {
                if ($customername->mobile_type)
                    $json['device_type'] = $customername->mobile_type;
                else
                    $json['device_type'] = "N/A";
            }
            $json['phone_verify_code'] = $customername->phone_verify_code;
            $json['wash_points'] = $customername->fifth_wash_points;

            $json['total_wash'] = $totalwash;
            $json['address'] = $address;
            $json['city'] = $city;
            $json['how_hear_mw'] = $customername->how_hear_mw;
            $json['total_spent'] = number_format($totalpaid, 2);

            $json['client_science'] = $customername->created_date;

            $customerdetail[] = $json;
        }
        echo json_encode($customerdetail);

        die();
    }

    public function actionsearchcustomers() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $query = Yii::app()->request->getParam('query');
        $limit = 0;
        $limit = Yii::app()->request->getParam('limit');
        $total_pages = 0;
        $search_area = Yii::app()->request->getParam('search_area');
        $cust_query = '';
        $total_count = 0;
        $page_number = 1;
        if (Yii::app()->request->getParam('page_number'))
            $page_number = Yii::app()->request->getParam('page_number');
        $offset = ($page_number - 1) * $limit;

        $limit_str = '';
        $total_count = 0;
        if ($limit && ($limit != 'none')) {
            $limit_str = " LIMIT " . $limit . " OFFSET " . $offset;
        }

        if ($search_area == "Customer Name")
            $cust_query = "(customername LIKE :query) ";
        if ($search_area == "Customer Email")
            $cust_query = "(email LIKE :query) ";
        if ($search_area == "Customer Phone")
            $cust_query = "(contact_number LIKE :query) ";

        if ($query) {
            $customers = Yii::app()->db->createCommand("SELECT * FROM customers WHERE " . $cust_query . "ORDER BY id DESC" . $limit_str)->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();
            $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM customers WHERE " . $cust_query . "ORDER BY id DESC")->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();
            $total_count = $total_rows[0]['countid'];
            if ($total_count > 0)
                $total_pages = ceil($total_count / $limit);
        }

        $customerdetail = array();
        foreach ($customers as $customername) {
            $totalwash = 0;
            $customersid = $customername['id'];
            $totalwash_arr = Washingrequests::model()->findAllByAttributes(array("status" => 4, "customer_id" => $customername['id']));

            $totalwash = count($totalwash_arr);

            $custlocation = CustomerLocation::model()->findByAttributes(array("customer_id" => $customername['id']));

            $city = 'N/A';
            $address = 'N/A';

            if (!empty($custlocation->location_address)) {
                $addrs_exp = explode(',', $custlocation->location_address);
                $address = $addrs_exp[0];
                if (!empty(trim($addrs_exp[1])) && trim($addrs_exp[1]) != 'CA') {
                    $city = $addrs_exp[1];
                } elseif (trim($addrs_exp[1]) == 'CA') {
                    $city = $custlocation->location_address;
                }
            }


            $json = array();
            $json['id'] = $customername['id'];
            $json['name'] = $customername['first_name'] . " " . $customername['last_name'];
            $json['user_type'] = $customername['login_type'];
            $json['email'] = $customername['email'];
            $json['rating'] = $customername['rating'];
            $json['phone'] = $customername['contact_number'];
            $json['device_type'] = $customername['mobile_type'];
            $json['phone_verify_code'] = $customername['phone_verify_code'];
            $json['wash_points'] = $customername['fifth_wash_points'];

            $json['total_wash'] = $totalwash;
            $json['address'] = $address;
            $json['city'] = $city;
            $json['how_hear_mw'] = $customername['how_hear_mw'];

            $json['client_science'] = $customername['created_date'];

            $customerdetail[] = $json;
        }


        echo json_encode(array("response" => "all clients", "result" => "true", "allcustomers" => $customerdetail, "total_customers" => $total_count, 'total_pages' => $total_pages));

        die();
    }

    public function actionEditCustomers() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerID = Yii::app()->request->getParam('customerID');
        $customerdetail = Customers::model()->findByAttributes(array("id" => $customerID));
        $id = $customerdetail['id'];
        $customername = $customerdetail['first_name'] . " " . $customerdetail['last_name'];
        $email = $customerdetail['email'];
        $contact_number = $customerdetail['contact_number'];
        $email_alerts = $customerdetail['email_alerts'];
        $push_notifications = $customerdetail['push_notifications'];
        $total_wash = $customerdetail['total_wash'];
        $time_zone = $customerdetail['time_zone'];
        $time_zone = $customerdetail['time_zone'];
        $account_status = $customerdetail['account_status'];
        $createddate = $customerdetail['created_date'];
        $created_date = explode(" ", $createddate);
        $updateddate = $customerdetail['updated_date'];
        $updated_date = explode(" ", $updateddate);
        $online_status = $customerdetail['online_status'];
        $rating = $customerdetail['rating'];
        $image = $customerdetail['image'];
        $sms_control = $customerdetail['sms_control'];
        $json = array(
            'id' => $id,
            'first_name' => $customerdetail['first_name'],
            'last_name' => $customerdetail['last_name'],
            'customername' => $customername,
            'email' => $email,
            'contact_number' => $contact_number,
            'email_alerts' => $email_alerts,
            'push_notifications' => $push_notifications,
            'total_wash' => $total_wash,
            'time_zone' => $time_zone,
            'account_status' => $account_status,
            'created_date' => $created_date[0],
            'updated_date' => $updated_date[0],
            'online_status' => $online_status,
            'rating' => $rating,
            'how_hear_mw' => $customerdetail['how_hear_mw'],
            'image' => $image,
            'hours_opt_check' => $customerdetail['hours_opt_check'],
            'sms_control' => $customerdetail['sms_control'],
            'block_client' => $customerdetail['block_client'],
            'notes' => $customerdetail['notes'],
            'created_date' => $customerdetail['created_date'],
            'updated_date' => $customerdetail['updated_date'],
            'admin_username' => $customerdetail['last_edited_admin']
        );
        echo json_encode($json);
        exit;
    }

    public function actionUpdateCustomersRecord() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $id = Yii::app()->request->getParam('id');
        $customername = Yii::app()->request->getParam('customername');
        $firstname = Yii::app()->request->getParam('firstname');
        $lastname = Yii::app()->request->getParam('lastname');
        $email = Yii::app()->request->getParam('email');
        $contact_number = Yii::app()->request->getParam('contact_number');
        $email_alerts = Yii::app()->request->getParam('email_alerts');
        $push_notifications = Yii::app()->request->getParam('push_notifications');
        $total_wash = Yii::app()->request->getParam('total_wash');
        $time_zone = Yii::app()->request->getParam('time_zone');

        $account_status = Yii::app()->request->getParam('account_status');
        $password = Yii::app()->request->getParam('password');
        $createddate = Yii::app()->request->getParam('created_date');
        $updateddate = Yii::app()->request->getParam('updated_date');
        $online_status = Yii::app()->request->getParam('online_status');
        $rating = Yii::app()->request->getParam('rating');
        $image = Yii::app()->request->getParam('image');
        $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
        $hours_opt_check = Yii::app()->request->getParam('hours_opt_check');
        $sms_control = Yii::app()->request->getParam('sms_control');
        $block_client = Yii::app()->request->getParam('block_client');
        $notes = Yii::app()->request->getParam('notes');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $force_logout = 0;

        $contact_number = preg_replace('/\D/', '', $contact_number);

        $customers_exists = Customers::model()->findByAttributes(array("id" => $id));
        $customers_email_exists = Customers::model()->findByAttributes(array("email" => $email));
        $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $contact_number));

        if (!count($customers_exists)) {
            $result = 'false';
            $response = 'Invalid customer';
        }
        /* else if(count($customers_email_exists) && $customers_email_exists->id != $id){
          $result = 'false';
          $response = 'Email already exists';
          } */ else if (count($customers_phone_exists) && $customers_phone_exists->id != $id) {
            $result = 'false';
            $response = 'Phone already exists';
        } else {

            if (!$password) {
                $password = $customers_exists->password;
            }

            if (!$image) {
                $image = $customers_exists->image;
            }

            if (!$online_status) {
                $online_status = $customers_exists->online_status;
            }


            if ($block_client == 1)
                $force_logout = 1;

            if (($block_client == 1) && (APP_ENV == 'real')) {
                $curl = curl_init();
                $url = "https://2f94ab1c39b710b2357f88158452c58a:x@api.createsend.com/api/v3.2/subscribers/616802d8a601cf1078b314335b206b7a.json?email=" . $email;
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                ));
                curl_exec($curl);

                curl_close($curl);
            }

            if ((!$block_client) && ($customers_exists->block_client == 1) && (APP_ENV == 'real')) {
                $curl = curl_init();
                $url = "https://2f94ab1c39b710b2357f88158452c58a:x@api.createsend.com/api/v3.2/subscribers/616802d8a601cf1078b314335b206b7a.json?email=" . $email;
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => json_encode(array("Resubscribe" => true, "ConsentToTrack" => "Yes"))
                ));
                curl_exec($curl);

                curl_close($curl);
            }

            $data = array('first_name' => $firstname, 'last_name' => $lastname, 'customername' => $firstname . " " . $lastname, 'email' => $email, 'contact_number' => $contact_number, 'email_alerts' => $email_alerts, 'push_notifications' => $push_notifications, 'online_status' => $online_status, 'image' => $image, 'password' => $password, 'how_hear_mw' => $how_hear_mw, 'hours_opt_check' => $hours_opt_check, 'block_client' => $block_client, 'notes' => $notes, 'sms_control' => $sms_control, 'last_edited_admin' => $admin_username, 'forced_logout' => $force_logout, 'updated_date' => date('Y-m-d h:i:s'));
            //$data = array_filter($data);

            if ($account_status == 0 || $account_status == 1) {
                $array = array('account_status' => $account_status);
                $data = array_merge($data, $array);
            }
            $update_agents = Customers::model()->updateAll($data, 'id=:id', array(':id' => $id));
            $result = 'true';
            $response = 'updated successfully';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionNotesUpdate() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $notes = Yii::app()->request->getParam('notes');
        $id = Yii::app()->request->getParam('customer_id');
        $admin_command = Yii::app()->request->getParam('admin_command');
        $admin_username = Yii::app()->request->getParam('admin_username');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $date = date('Y-m-d H:i:s');
        if (!empty($id) && !empty($notes)) {
            $data = array("notes" => $notes . " (" . $admin_username . " added at " . $date . ")");
            $update_agents = Customers::model()->updateAll($data, 'id=:id', array(':id' => $id));
            $result = 'true';
            $response = 'updated successfully';
            if ($admin_command == 'save-customer-note') {
                $washeractionlogdata = array(
                    'wash_request_id' => $wash_request_id,
                    'admin_username' => $admin_username,
                    'action' => 'savecustomernote',
                    'action_date' => $date);
                Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                /* $washeractionlogdata = array(
                  'wash_request_id'=> $id,
                  'admin_username' => $admin_username,
                  'action'=> 'edit_customer',
                  'action_date'=> date('Y-m-d H:i:s'));
                  Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata); */
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionCustomerCancelWashPaymentold() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $nonce = Yii::app()->request->getParam('nonce');
        $amount = Yii::app()->request->getParam('amount');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($washing_request_id) && !empty($washing_request_id)) && (isset($nonce) && !empty($nonce)) && (isset($amount) && !empty($amount))) {

            $customers = Customers::model()->findByPk($customer_id);
            $wash_id_check = Washingrequests::model()->findByPk($washing_request_id);
            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else if (!$wash_id_check) {
                $response = "Invalid wash request id";
                $result = "false";
            } else {
                if ($wash_id_check->status == 3) {
                    $request_data = ['merchantAccountId' => 'blue_ladders_store', 'serviceFeeAmount' => "5.00", 'amount' => $amount, 'paymentMethodNonce' => $nonce];
                    $Bresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                } else {
                    $request_data = ['amount' => $amount, 'paymentMethodNonce' => $nonce, 'options' => ['submitForSettlement' => True, 'storeInVaultOnSuccess' => true]];
                    $Bresult = Yii::app()->braintree->sale($request_data);
                }
                //print_r($Bresult);
                //die();
                if ($Bresult['success'] == 1) {

                    /* -------- cancel wash ------------ */

                    $car_ids = $wash_id_check->car_list;
                    $car_ids_arr = explode(",", $car_ids);
                    foreach ($car_ids_arr as $car) {
                        $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '');
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                    }

                    $data = array('status' => 5, 'cancel_fee' => $amount);
                    $washrequestmodel = new Washingrequests;
                    $washrequestmodel->attributes = $data;

                    $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id' => $washing_request_id));

                    /* -------- cancel wash end ------------ */

                    //print_r($result);die;
                    $response = "Payment successful and wash canceled";
                    $result = "true";
                    $payment_type = $Bresult['transaction_id'];
                    //$update_request = Washingrequests::model()->findByPk($washing_request_id);
                    //$update_request->transaction_id = $Bresult['transaction_id'];
                    //$update_request->save(false);
                } else {
                    $result = "false";
                    $response = $Bresult['message'];
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'transation_id' => $payment_type
        );

        echo json_encode($json);
        die();
    }

    public function actionpreregister() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $name = Yii::app()->request->getParam('name');
        $email = Yii::app()->request->getParam('email');
        $phone = Yii::app()->request->getParam('phone');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $source = Yii::app()->request->getParam('source');
        $address = Yii::app()->request->getParam('address');
        $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
        $register_date = date("Y-m-d H:i:s");
        $result = 'false';
        $response = 'All fields are required';


        if ((isset($name) && !empty($name)) && (isset($email) && !empty($email)) && (isset($phone) && !empty($phone)) && (isset($city) && !empty($city)) && (isset($state) && !empty($state))) {

            /* $clients_email_exists = PreRegClients::model()->findByAttributes(array("email"=>$email));
              if(count($clients_email_exists)>0){
              $result = 'false';
              $response = 'Email already exists';

              }
              else{ */
            $result = 'true';
            $response = 'Registration successful';

            $clientdata = array(
                'first_name' => $name,
                'email' => $email,
                'phone' => $phone,
                'city' => $city,
                'state' => $state,
                'zipcode' => $zipcode,
                'address' => $address,
                'source' => $source,
                'register_date' => $register_date,
                'how_hear_mw' => ''
            );

            $model = new PreRegClients;
            $model->attributes = $clientdata;
            $model->save(false);


            $from = Vargas::Obj()->getAdminFromEmail();

            $to = Vargas::Obj()->getAdminToEmail();


            /* $message = "<h3>Dear ".$first_name." ".$last_name.",</h3>";
              $message .= "<p>Thank you for registering with Mobile Wash! Our app is coming soon, but we'd love to service you today! Call <strong>(888)209-5585</strong> and we'll send you a mobile detailer.</p>"; */

            $message .= "<h3>Dear " . $name . ",</h3>";
            /* Our app is coming soon, but we'd love to service you today! */
            $message .= "<p>Thank you for registering with MobileWash! We'll be coming to your area soon, please follow us on Instagram, Facebook, and Twitter @getmobilewash for expansion announcements. Stay tuned!</p>";

            $message .= "<p style='height: 0px;'>&nbsp;</p>
               <p><b>Kind Regards,</b></p>
               <p style='margin-bottom: 0;'><b>The Mobilewash Team</b></p>
               <p style='margin: 0; margin-top: 5px;'>www.mobilewash.com</p>
               <p style='margin-top: 5px;'>support@mobilewash.com</p>";


            Vargas::Obj()->SendMail($email, $from, $message, "Thank you for your pre-registration with MobileWash");

            $msg = '';
            $msg = "Registration Date: " . date("Y-m-d H:i:s") . "<br>";
            $msg .= "Name: " . $name . "<br>";
            $msg .= "Email: " . $email . "<br>";
            $msg .= "Phone: " . $phone . "<br>";
            $msg .= "City: " . $city . "<br>";
            $msg .= "State: " . $state . "<br>";
            $msg .= "Zipcode: " . $zipcode . "<br>";
//$msg .= "How did you hear about Mobile Wash: ".$how_hear_mw."<br>";


            Vargas::Obj()->SendMail($to, $from, $msg, "New Customer Pre-Registration");

            //}
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Client location update
     * * Post Required: customer_id, latitude, longitude
     * * Url:- http://www.demo.com/projects/index.php?r=agents/updateagentlocations
     * * Purpose:- Client location update
     */

    public function actionupdatecustomerlocations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if ((isset($customer_id) && !empty($customer_id)) && (isset($latitude) && !empty($latitude)) && (isset($longitude) && !empty($longitude))) {
            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $model = Customers::model()->findByAttributes(array('id' => $customer_id));

            if (!count($model)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else {
                $data = array('customer_id' => $customer_id, 'latitude' => $latitude, 'longitude' => $longitude);
                $customerloc = new CustomerLiveLocations;
                $customerloc->attributes = $data;
                $checkcustomer = $customerloc->findByAttributes(array('customer_id' => $customer_id));
                if (!count($checkcustomer))
                    $resUpdate = $customerloc->save(false);
                else
                    $resUpdate = $customerloc->updateAll($data, 'customer_id=:customer_id', array(':customer_id' => $customer_id));
                if ($resUpdate) {
                    $result = 'true';
                    $response = 'Location updated';
                } else {
                    $result = 'false';
                    $response = 'Location not updated';
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }
        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    /*
     * * Get Client location
     * * Post Required: customer_id
     * * Url:- http://www.demo.com/projects/index.php?r=agents/getagentlocations
     * * Purpose:- Customer location retrieve
     */

    public function actiongetcustomerlocations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if (isset($customer_id) && !empty($customer_id)) {

            $customer_id = Yii::app()->request->getParam('customer_id');

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $model = Customers::model()->findByAttributes(array('id' => $customer_id));

            if (!count($model)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else {
                $customerlocmodel = CustomerLiveLocations::model()->findByAttributes(array('customer_id' => $customer_id));
                if (count($customerlocmodel)) {
                    $latitude = $customerlocmodel->latitude;
                    $longitude = $customerlocmodel->longitude;
                    $result = 'true';
                    $response = 'Customer Location';
                } else {
                    $result = 'false';
                    $response = 'No customer location found';
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }
        if (count($customerlocmodel)) {
            $json = array(
                'result' => $result,
                'response' => $response,
                'latitude' => $latitude,
                'longitude' => $longitude
            );
        } else {
            $json = array(
                'result' => $result,
                'response' => $response,
            );
        }

        echo json_encode($json);
    }

// order map for year
    public function Actioncustomeryearwiseorder() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $array = array();

        for ($i = 0; $i <= 6; $i++) {
            $year = date("Y", strtotime('-' . $i . " year"));
            $start_year = $year . '-01-01' . ' ' . '00:00:00';
            $end_year = $year . '-12-31' . ' ' . '23:59:59';
            if (Yii::app()->request->getParam('status') == 'completed') {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_year' AND '$end_year' AND wash_request_position='" . APP_ENV . "' AND status = 4")->queryAll();
            } else {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_year' AND '$end_year' AND wash_request_position='" . APP_ENV . "'")->queryAll();
            }
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_year' AND '$end_year' " .'<br />';
            foreach ($request as $details) {
                $array[$year] = $details['cnt'];
            }
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// order map for week
    public function Actioncustomerweekwiseorder() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $year = array();

        for ($i = 6; $i >= 0; $i--) {
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));
            $date = date("d", strtotime($i . " days ago"));
            if (Yii::app()->request->getParam('status') == 'completed') {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_date' AND '$end_date' AND wash_request_position = '" . APP_ENV . "' AND status=4")->queryAll();
            } else {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_date' AND '$end_date' AND wash_request_position = '" . APP_ENV . "'")->queryAll();
            }
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$start_year' AND '$end_year' " .'<br />';
            if (!empty($request)) {
                foreach ($request as $details) {
                    $array[$day] = $details['cnt'];
                }
            } else {
                $array[$day] = 0;
            }
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
//order map for moth
    public function Actioncustomermontwiseorder() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        ;

// loop through the current and last four month
        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            // calculate the first day of the month
            $first = mktime(0, 0, 0, date('m', $start) - $i, 1, date('Y', $start));

            // calculate the last day of the month
            $last = mktime(0, 0, 0, date('m') - $i + 1, 0, date('Y', $start));

            // now some output...
            $month = date('M', $first);
            $irstdate = date('Y-m-d', $first) . ' ' . '00:00:00';
            $lastdate = date('Y-m-d', $last) . ' ' . '23:59:59';

            if (Yii::app()->request->getParam('status') == 'completed') {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$irstdate' AND '$lastdate' AND wash_request_position= '" . APP_ENV . "' AND status=4")->queryAll();
            } else {
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `washing_requests` WHERE order_for BETWEEN '$irstdate' AND '$lastdate' AND wash_request_position= '" . APP_ENV . "'")->queryAll();
            }
            //echo "SELECT COUNT(*) as cnt FROM `customers` WHERE order_for BETWEEN '$irstdate' AND '$lastdate' ".$month.'<br />';


            foreach ($request as $details) {
                $array[$month] = $details['cnt'];
            }
        }

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
    // new customer for Year
    public function Actioncustomeryearwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $array = array();

        for ($i = 6; $i >= 0; $i--) {
            $year = date("Y", strtotime('-' . $i . " year"));
            $start_year = $year . '-01-01' . ' ' . '00:00:00';
            $end_year = $year . '-12-31' . ' ' . '23:59:59';
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$start_year' AND '$end_year' AND client_position='" . APP_ENV . "'")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE created_date BETWEEN '$start_year' AND '$end_year' " .'<br />';
            foreach ($request as $details) {
                $array[$year] = $details['cnt'];
            }
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
    // new customer for week
    public function Actioncustomerweekwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $year = array();

        for ($i = 6; $i >= 0; $i--) {
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$start_date' AND '$end_date' AND client_position='" . APP_ENV . "'")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE created_date BETWEEN '$start_year' AND '$end_year' " .'<br />';
            foreach ($request as $details) {
                $array[$day] = $details['cnt'];
            }
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new customer for month
    public function Actioncustomermontwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        ;

// loop through the current and last four month
        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            // calculate the first day of the month
            $first = mktime(0, 0, 0, date('m', $start) - $i, 1, date('Y', $start));

            // calculate the last day of the month
            $last = mktime(0, 0, 0, date('m') - $i + 1, 0, date('Y', $start));

            // now some output...
            $month = date('M', $first);
            $irstdate = date('Y-m-d', $first) . ' ' . '00:00:00';
            $lastdate = date('Y-m-d', $last) . ' ' . '23:59:59';

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$irstdate' AND '$lastdate'  AND client_position='" . APP_ENV . "'")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$irstdate' AND '$lastdate' ".$month.'<br />';


            foreach ($request as $details) {
                $array[$month] = $details['cnt'];
            }
        }
        //exit;
        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// revenue Year wise

    public function ActionTotalRevenueYearWise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            $year = date("Y", strtotime('-' . $i . " year"));
            $start_year = $year . '-01-01' . ' ' . '00:00:00';
            $end_year = $year . '-12-31' . ' ' . '23:59:59';

            $wash_request = Yii::app()->db->createCommand("SELECT total_price FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$start_year' AND '$end_year'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$year] += $wash_details['total_price'];
                }
            } else {
                $array[$year] += 0;
            }
        }

        //exit;
        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
    // revenue Week wise

    public function ActionTotalRevenueWeekWise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));

            $wash_request = Yii::app()->db->createCommand("SELECT total_price FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$start_date' AND '$end_date'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$day] += $wash_details['total_price'];
                }
            } else {
                $array[$day] += 0;
            }
        }

        //exit;
        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// revenue month wise

    public function ActionTotalRevenueMonthWise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        ;

// loop through the current and last four month
        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            // calculate the first day of the month
            $first = mktime(0, 0, 0, date('m', $start) - $i, 1, date('Y', $start));

            // calculate the last day of the month
            $last = mktime(0, 0, 0, date('m') - $i + 1, 0, date('Y', $start));

            // now some output...
            $month = date('M', $first);
            $irstdate = date('Y-m-d', $first) . ' ' . '12:00:00';
            $lastdate = date('Y-m-d', $last) . ' ' . '3:53:04';
            $array[$month] = $details['cnt'];
            $wash_request = Yii::app()->db->createCommand("SELECT total_price FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$month] += $wash_details['total_price'];
                }
            } else {
                $array[$month] += 0;
            }
        }

        //exit;
        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new pre clients in a Year
    public function Actionpreclientsyearwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $array = array();

        for ($i = 6; $i >= 0; $i--) {
            $year = date("Y", strtotime('-' . $i . " year"));
            $start_year = $year . '-01-01' . ' ' . '00:00:00';
            $end_year = $year . '-12-31' . ' ' . '23:59:59';
            /* $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_clients` WHERE register_date BETWEEN '$start_year' AND '$end_year' ")->queryAll(); */

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$start_year' AND '$end_year'")->queryAll();

            foreach ($request as $details) {
                $array[$year] = $details['cnt'];
            }
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new pre clients in a week
    public function Actionpreclientsweekwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $year = array();

        for ($i = 6; $i >= 0; $i--) {
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));
            /* $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_clients` WHERE register_date BETWEEN '$start_date' AND '$end_date' ")->queryAll(); */

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$start_date' AND '$end_date'")->queryAll();

            foreach ($request as $details) {
                $array[$day] = $details['cnt'];
            }
        }

        //exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new pre clients in a month
    public function Actionpreclientsmonthwise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        ;

        // loop through the current and last four month
        $array = array();
        for ($i = 6; $i >= 0; $i--) {
            // calculate the first day of the month
            $first = mktime(0, 0, 0, date('m', $start) - $i, 1, date('Y', $start));

            // calculate the last day of the month
            $last = mktime(0, 0, 0, date('m') - $i + 1, 0, date('Y', $start));

            // now some output...
            $month = date('M', $first);
            $irstdate = date('Y-m-d', $first) . ' ' . '00:00:00';
            $lastdate = date('Y-m-d', $last) . ' ' . '23:59:59';

            /* $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_clients` WHERE register_date BETWEEN '$irstdate' AND '$lastdate' ")->queryAll(); */


            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$irstdate' AND '$lastdate'")->queryAll();



            foreach ($request as $details) {
                $array[$month] = $details['cnt'];
            }
        }

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new pre clients per day
    public function Actionpreclientsperday() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $array = array();

        for ($i = 6; $i >= 0; $i--) {
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));
            /* $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_clients` WHERE register_date BETWEEN '$start_date' AND '$end_date' ")->queryAll(); */

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$start_date' AND '$end_date'")->queryAll();

            foreach ($request as $details) {
                $array[$day] = $details['cnt'];
            }
        }

        //exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end


    public function actiontrashpreclients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'trash_status' => 1
        );
        PreRegClients::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients trashed';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionGetAllPreClients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $result = 'false';
        $response = 'no clients found';
        $all_clients = array();
        $trash = 0;
        $clients_exists = Yii::app()->db->createCommand("SELECT * FROM pre_registered_clients WHERE trash_status = '0' ORDER BY id DESC ")->queryAll();

        if (count($clients_exists) > 0) {
            $result = 'true';
            $response = 'all pre clients';

            foreach ($clients_exists as $ind => $clients) {
                $all_clients[$ind]['id'] = $clients['id'];
                $all_clients[$ind]['email'] = $clients['email'];
                $all_clients[$ind]['first_name'] = $clients['first_name'];
                $all_clients[$ind]['last_name'] = $clients['last_name'];
                $all_clients[$ind]['phone'] = $clients['phone'];
                $all_clients[$ind]['city'] = $clients['city'];
                $all_clients[$ind]['state'] = $clients['state'];
                $all_clients[$ind]['trash_status'] = $clients['trash_status'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_clients' => $all_clients
        );

        echo json_encode($json);
        die();
    }

    public function actionGetPreClientsTrashData() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $clients_count = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM pre_registered_clients WHERE trash_status = '1' ")->queryAll();

        $count = $clients_count[0]['cnt'];
        $json = array(
            'result' => 'true',
            'response' => 'trash data',
            'count' => $count,
        );
        echo json_encode($json);
        die();
    }

    /*     * **************************************************************************  */
    /* controller for active washers and for these, modal is in PreRegClients2.php  */
    /*     * **************************************************************************  */

    public function actiontrashpreclients2() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'trash_status' => 1
        );
        PreRegClients2::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients trashed';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionupdatewasherimage() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        $image = Yii::app()->request->getParam('image');

        $clientsdata = array(
            'washer_img' => $image
        );
        PreRegClients2::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'updated successfully';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionapproveclients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'active_status' => 1
        );
        PreRegClients2::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients approved';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actiondisapproveclients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'active_status' => 0
        );
        PreRegClients2::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients disapproved';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionGetAllPreClients2() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $result = 'false';
        $response = 'no clients found';
        $all_clients = array();
        $trash = 0;
        $clients_exists = Yii::app()->db->createCommand("SELECT * FROM active_washers WHERE trash_status = '0' ORDER BY id DESC ")->queryAll();

        if (count($clients_exists) > 0) {
            $result = 'true';
            $response = 'all pre clients';

            foreach ($clients_exists as $ind => $clients) {
                $all_clients[$ind]['id'] = $clients['id'];
                $all_clients[$ind]['user_id'] = $clients['user_id'];
                $all_clients[$ind]['email'] = $clients['user_email'];
                $all_clients[$ind]['first_name'] = $clients['first_name'];
                $all_clients[$ind]['last_name'] = $clients['last_name'];
                $all_clients[$ind]['phone'] = $clients['phone'];
                $all_clients[$ind]['address'] = $clients['address'];
                $all_clients[$ind]['city'] = $clients['city'];
                $all_clients[$ind]['state'] = $clients['state'];
                $all_clients[$ind]['zip'] = $clients['zip'];
                $all_clients[$ind]['ID_number'] = $clients['ID_number'];
                $all_clients[$ind]['DL_ID_exp'] = $clients['DL_ID_exp'];
                $all_clients[$ind]['insurance_exp'] = $clients['insurance_exp'];
                $all_clients[$ind]['payment_due_d_ins'] = $clients['payment_due_d_ins'];
                $all_clients[$ind]['account_name'] = $clients['account_name'];
                $all_clients[$ind]['SSN_ITIN_TAX_ID'] = $clients['SSN_ITIN_TAX_ID'];
                $all_clients[$ind]['routing_number'] = $clients['routing_number'];
                $all_clients[$ind]['account_number'] = $clients['account_number'];
                $all_clients[$ind]['active_status'] = $clients['active_status'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_clients' => $all_clients
        );

        echo json_encode($json);
        die();
    }

    public function actionGetPreClientsTrashData2() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $clients_count = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM pre_registered_clients WHERE trash_status = '1' ")->queryAll();

        $count = $clients_count[0]['cnt'];
        $json = array(
            'result' => 'true',
            'response' => 'trash data',
            'count' => $count,
        );
        echo json_encode($json);
        die();
    }

    /*     * **************************************************************************  */
    /*     * **************************************************************************  */

    public function actiontrashactivewashers() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new ActWashClients;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'trash_status' => 1
        );
        ActWashClients::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients trashed';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function getAllActiveWashers() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new ActWashClients;
        $result = 'false';
        $response = 'no clients found';
        $all_clients = array();
        $trash = 0;
        $clients_exists = Yii::app()->db->createCommand("SELECT * FROM active_washers WHERE trash_status = '0' ORDER BY id DESC ")->queryAll();

        //echo "<pre>";
//            print_r( $clients_exists );
//            echo "<pre>";
//            die('<br>code die here');
        if (count($clients_exists) > 0) {
            $result = 'true';
            $response = 'all active washers';

            foreach ($clients_exists as $ind => $clients) {
                $all_clients[$ind]['id'] = $clients['id'];
                $all_clients[$ind]['user_id'] = $clients['user_id'];
                $all_clients[$ind]['user_email'] = $clients['user_email'];
                $all_clients[$ind]['first_name'] = $clients['first_name'];
                $all_clients[$ind]['last_name'] = $clients['last_name'];
                $all_clients[$ind]['phone'] = $clients['phone'];
                $all_clients[$ind]['address'] = $clients['address'];
                $all_clients[$ind]['city'] = $clients['city'];
                $all_clients[$ind]['state'] = $clients['state'];
                $all_clients[$ind]['zip'] = $clients['zip'];
                $all_clients[$ind]['ID_number'] = $clients['ID_number'];
                $all_clients[$ind]['DL_ID_exp'] = $clients['DL_ID_exp'];
                $all_clients[$ind]['insurance_exp'] = $clients['insurance_exp'];
                $all_clients[$ind]['payment_due_d_ins'] = $clients['payment_due_d_ins'];
                $all_clients[$ind]['account_name'] = $clients['account_name'];
                $all_clients[$ind]['SSN_ITIN_TAX_ID'] = $clients['SSN_ITIN_TAX_ID'];
                $all_clients[$ind]['routing_number'] = $clients['routing_number'];
                $all_clients[$ind]['account_number'] = $clients['account_number'];
                $all_clients[$ind]['trash_status'] = $clients['trash_status'];
                $all_clients[$ind]['active_status'] = $clients['active_status'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_clients' => $all_clients
        );

        echo json_encode($json);
        die();
    }

    public function actionGetAllActiveWashersTrashData() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $clients_count = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM active_washers WHERE trash_status = '1' ")->queryAll();

        $count = $clients_count[0]['cnt'];
        $json = array(
            'result' => 'true',
            'response' => 'trash data',
            'count' => $count,
        );
        echo json_encode($json);
        die();
    }

    public function ActionMovePreToRealClient() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $client_id = Yii::app()->request->getParam('clientid');
        $prewaher = Yii::app()->db->createCommand("SELECT * FROM `pre_registered_clients` WHERE id = :id ")->bindValue(':id', $client_id, PDO::PARAM_STR)->queryAll();
        $first_name = $prewaher[0]['first_name'];
        $last_name = $prewaher[0]['last_name'];
        $email = $prewaher[0]['email'];
        $phone_number = $prewaher[0]['phone'];
        $street_address = $prewaher[0]['city'];
        $register_date = $prewaher[0]['state'];
        $register_date = $prewaher[0]['register_date'];
        $name = $first_name . ' ' . $last_name;

        $insertuser = Yii::app()->db->createCommand("INSERT INTO `customers` (`customername`, `email`, `created_date`) VALUES ('$name', '$email', '$register_date') ")->execute();
        $insertid = Yii::app()->db->getLastInsertID();
        $update_user = Yii::app()->db->createCommand("UPDATE pre_registered_clients SET trash_status='1' WHERE id = :id ")->bindValue(':id', $client_id, PDO::PARAM_STR)->execute();
        $from = Vargas::Obj()->getAdminFromEmail();
        $subject = 'MobileWash.com - Set New Password';
        $reporttxt = ROOT_URL . '/set-password.php?action=clrp&id=' . $insertid;
        $message = "";

        $message .= "<p style='font-size: 20px;'>Dear " . $name . ",</p>";
        $message .= "<p>Please click <a href='" . $reporttxt . "' style='color: #016fd0;'>here</a> to set new password</p>";
        $message .= "<p>If this was a mistake or you did not authorize this request you may disregard this email.</p>";
        $message .= "<p style='font-size: 20px;line-height: 30px;margin-top: 30px;'>Kind Regards,<br>";
        $message .= "The MobileWash Team<br><a href='" . ROOT_URL . "' style='font-size: 16px; color: #016fd0;'>www.mobilewash.com</a><br><a href='mailto:support@mobilewash.com' style='font-size: 16px; color: #016fd0;'>support@mobilewash.com</a></p>";

        Vargas::Obj()->SendMail('bhatt.sudhakar06@gmail.com', $from, $message, $subject);
        //Vargas::Obj()->SendMail($email,$from,$message,$subject);

        $json = array(
            'result' => 'true',
            'response' => 'move successfully'
        );

        echo json_encode($json);
        die();
    }

    /*     * ***************** add new washer start here ***************************************** */

    public function actionAddNewWasher() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $user_idMax = Yii::app()->db->createCommand("SELECT MAX(user_id) AS MaxUserId FROM active_washers ")->queryAll();
        $userid = $user_idMax[0]['MaxUserId'] + 1;
        $user_email = Yii::app()->request->getParam('user_email');
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $phone = Yii::app()->request->getParam('phone');
        $address = Yii::app()->request->getParam('address');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zip = Yii::app()->request->getParam('zip');
        $ID_number = Yii::app()->request->getParam('ID_number');
        $DL_ID_exp = Yii::app()->request->getParam('DL_ID_exp');
        $insurance_exp = Yii::app()->request->getParam('insurance_exp');
        $payment_due_d_ins = Yii::app()->request->getParam('payment_due_d_ins');
        $account_name = Yii::app()->request->getParam('account_name');
        $SSN_ITIN_TAX_ID = Yii::app()->request->getParam('SSN_ITIN_TAX_ID');
        $routing_number = Yii::app()->request->getParam('routing_number');
        $account_number = Yii::app()->request->getParam('account_number');
        $trash_status = Yii::app()->request->getParam('trash_status');
        $active_status = '0';

        $insertuser = Yii::app()->db->createCommand("INSERT INTO `active_washers` (`user_id`, `user_email`, `first_name`, `last_name`, `phone`, `address`, `city`, `state`, `zip`, `ID_number`, `DL_ID_exp`, `insurance_exp`, `payment_due_d_ins`, `account_name`, `SSN_ITIN_TAX_ID`, `routing_number`, `account_number`, `trash_status`, `active_status`) VALUES ('$userid', '$user_email', '$first_name', '$last_name', '$phone', '$address', '$city', '$state', '$zip', '$ID_number', '$DL_ID_exp', '$insurance_exp', '$payment_due_d_ins', '$account_name', '$SSN_ITIN_TAX_ID', '$routing_number', '$account_number', '$trash_status', '$active_status') ")->execute();
        $insertid = Yii::app()->db->getLastInsertID();
        $json = array(
            'result' => 'true',
            'response' => 'insert successfully'
        );

        echo json_encode($json);
        die();
    }

    /*     * ***************** add new washer end here ***************************************** */

    public function actionrestorepreclients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegClients::model()->deleteAll('id=:id', array(':id'=>$id));
        $clientsdata = array(
            'trash_status' => 0
        );
        PreRegClients::model()->updateByPk($id, $clientsdata);

        $result = 'true';
        $response = 'clients restore';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionGetAllTrashPreClients() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $result = 'false';
        $response = 'no clients found';
        $all_clients = array();
        $trash = 0;
        $clients_exists = Yii::app()->db->createCommand("SELECT * FROM pre_registered_clients WHERE trash_status = '1' ")->queryAll();

        if (count($clients_exists) > 0) {
            $result = 'true';
            $response = 'all pre clients';

            foreach ($clients_exists as $ind => $clients) {
                $all_clients[$ind]['id'] = $clients['id'];
                $all_clients[$ind]['email'] = $clients['email'];
                $all_clients[$ind]['first_name'] = $clients['first_name'];
                $all_clients[$ind]['last_name'] = $clients['last_name'];
                $all_clients[$ind]['phone'] = $clients['phone'];
                $all_clients[$ind]['city'] = $clients['city'];
                $all_clients[$ind]['state'] = $clients['state'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_clients' => $all_clients
        );

        echo json_encode($json);
        die();
    }

    public function actionpreclientsupdate() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $id = Yii::app()->request->getParam('id');
        $first_name = '';
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = '';
        $last_name = Yii::app()->request->getParam('last_name');
        $email = '';
        $email = Yii::app()->request->getParam('email');
        $phone = '';
        $phone = Yii::app()->request->getParam('phone');
        $city = '';
        $city = Yii::app()->request->getParam('city');
        $state = '';
        $state = Yii::app()->request->getParam('state');
        $how_hear_mw = '';
        $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
        $zipcode = '';



        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $user_exists = PreRegClients::model()->findByPk($id);
            if (!$user_exists) {
                $result = 'false';
                $response = "Sorry, you are not a registered clients. Please register first.";
            } else {
                $result = 'true';
                $response = 'successfully updated';

                if (!$first_name) {
                    $first_name = $user_exists->first_name;
                }

                if (!$last_name) {
                    $last_name = $user_exists->last_name;
                }

                if (!$email) {
                    $email = $user_exists->email;
                }

                if (!$phone) {
                    $phone = $user_exists->phone;
                }

                if (!$city) {
                    $city = $user_exists->city;
                }

                if (!$state) {
                    $state = $user_exists->state;
                }

                if (!$how_hear_mw) {
                    $how_hear_mw = $user_exists->how_hear_mw;
                }




                $clientsdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'state' => $state,
                    'how_hear_mw' => $how_hear_mw
                );


                PreRegClients::model()->updateByPk($id, $clientsdata);
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionpreclientsdetails() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients;
        $id = Yii::app()->request->getParam('id');
        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $clients_email_exists = PreRegClients::model()->findByAttributes(array("id" => $id));
            if (!count($clients_email_exists)) {
                $result = 'false';
                $response = 'Invalid clients id';
            } else {
                $result = 'true';
                $response = 'clients details';

                $clientsdata = array(
                    'id' => $clients_email_exists->id,
                    'first_name' => $clients_email_exists->first_name,
                    'last_name' => $clients_email_exists->last_name,
                    'email' => $clients_email_exists->email,
                    'phone' => $clients_email_exists->phone,
                    'city' => $clients_email_exists->city,
                    'state' => $clients_email_exists->state,
                    'how_hear_mw' => $clients_email_exists->how_hear_mw,
                    'trash_status' => $clients_email_exists->trash_status,
                );
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'washer_details' => $clientsdata
        );

        echo json_encode($json);
        die();
    }

    /*     * *******************************************************************************  */
    /*     * *******************************************************************************  */
    /*     * *******************************************************************************  */

    /*  */

    public function actionpreclientsupdate2() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        $user_id = '';
        $user_id = Yii::app()->request->getParam('user_id');
        $user_email = '';
        $user_email = Yii::app()->request->getParam('user_email');
        $first_name = '';
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = '';
        $last_name = Yii::app()->request->getParam('last_name');
        $phone = '';
        $phone = Yii::app()->request->getParam('phone');
        $address = '';
        $address = Yii::app()->request->getParam('address');
        $city = '';
        $city = Yii::app()->request->getParam('city');
        $state = '';
        $state = Yii::app()->request->getParam('state');
        $zip = '';
        $zip = Yii::app()->request->getParam('zip');
        $ID_number = '';
        $ID_number = Yii::app()->request->getParam('ID_number');
        $DL_ID_exp = '';
        $DL_ID_exp = Yii::app()->request->getParam('DL_ID_exp');
        $insurance_exp = '';
        $insurance_exp = Yii::app()->request->getParam('insurance_exp');
        $payment_due_d_ins = '';
        $payment_due_d_ins = Yii::app()->request->getParam('payment_due_d_ins');
        $account_name = '';
        $account_name = Yii::app()->request->getParam('account_name');
        $account_number = '';
        $account_number = Yii::app()->request->getParam('account_number');
        $routing_number = '';
        $routing_number = Yii::app()->request->getParam('routing_number');
        $SSN_ITIN_TAX_ID = '';
        $SSN_ITIN_TAX_ID = Yii::app()->request->getParam('SSN_ITIN_TAX_ID');
        $trash_status = '';
        $trash_status = Yii::app()->request->getParam('trash_status');


        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $user_exists = PreRegClients2::model()->findByPk($id);
            if (!$user_exists) {
                $result = 'false';
                $response = "Sorry, you are not a registered clients. Please register first.";
            } else {
                $result = 'true';
                $response = 'successfully updated';

                if (!$user_id) {
                    $user_id = $user_exists->user_id;
                }
                if (!$user_email) {
                    $user_email = $user_exists->user_email;
                }
                if (!$first_name) {
                    $first_name = $user_exists->first_name;
                }
                if (!$last_name) {
                    $last_name = $user_exists->last_name;
                }
                if (!$user_email) {
                    $user_email = $user_exists->user_email;
                }
                if (!$phone) {
                    $phone = $user_exists->phone;
                }
                if (!$address) {
                    $address = $user_exists->address;
                }
                if (!$city) {
                    $city = $user_exists->city;
                }
                if (!$state) {
                    $state = $user_exists->state;
                }
                if (!$zip) {
                    $zip = $user_exists->zip;
                }
                if (!$ID_number) {
                    $ID_number = $user_exists->ID_number;
                }
                if (!$DL_ID_exp) {
                    $DL_ID_exp = $user_exists->DL_ID_exp;
                }
                if (!$insurance_exp) {
                    $insurance_exp = $user_exists->insurance_exp;
                }
                if (!$payment_due_d_ins) {
                    $payment_due_d_ins = $user_exists->payment_due_d_ins;
                }
                if (!$account_name) {
                    $account_name = $user_exists->account_name;
                }
                if (!$account_number) {
                    $account_number = $user_exists->account_number;
                }
                if (!$routing_number) {
                    $routing_number = $user_exists->routing_number;
                }
                if (!$SSN_ITIN_TAX_ID) {
                    $SSN_ITIN_TAX_ID = $user_exists->SSN_ITIN_TAX_ID;
                }
                if (!$trash_status) {
                    $trash_status = $user_exists->trash_status;
                }

                $clientsdata = array(
                    'user_id' => $user_id,
                    'user_email' => $user_email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'zip' => $zip,
                    'ID_number' => $ID_number,
                    'DL_ID_exp' => $DL_ID_exp,
                    'insurance_exp' => $insurance_exp,
                    'payment_due_d_ins' => $payment_due_d_ins,
                    'account_name' => $account_name,
                    'account_number' => $account_number,
                    'routing_number' => $routing_number,
                    'SSN_ITIN_TAX_ID' => $SSN_ITIN_TAX_ID,
                    'trash_status' => $trash_status
                );


                PreRegClients2::model()->updateByPk($id, $clientsdata);
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionpreclientsdetails2() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $model = new PreRegClients2;
        $id = Yii::app()->request->getParam('id');
        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $clients_email_exists = PreRegClients2::model()->findByAttributes(array("id" => $id));
            if (!count($clients_email_exists)) {
                $result = 'false';
                $response = 'Invalid clients id';
            } else {
                $result = 'true';
                $response = 'clients details';

                $clientsdata = array(
                    'id' => $clients_email_exists->id,
                    'user_id' => $clients_email_exists->user_id,
                    'user_email' => $clients_email_exists->user_email,
                    'first_name' => $clients_email_exists->first_name,
                    'last_name' => $clients_email_exists->last_name,
                    'phone' => $clients_email_exists->phone,
                    'address' => $clients_email_exists->address,
                    'city' => $clients_email_exists->city,
                    'state' => $clients_email_exists->state,
                    'zip' => $clients_email_exists->zip,
                    'ID_number' => $clients_email_exists->ID_number,
                    'DL_ID_exp' => $clients_email_exists->DL_ID_exp,
                    'insurance_exp' => $clients_email_exists->insurance_exp,
                    'payment_due_d_ins' => $clients_email_exists->payment_due_d_ins,
                    'account_name' => $clients_email_exists->account_name,
                    'SSN_ITIN_TAX_ID' => $clients_email_exists->SSN_ITIN_TAX_ID,
                    'routing_number' => $clients_email_exists->routing_number,
                    'account_number' => $clients_email_exists->account_number,
                    'washer_img' => $clients_email_exists->washer_img,
                    'trash_status' => $clients_email_exists->trash_status,
                );
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'washer_details' => $clientsdata
        );

        echo json_encode($json);
        die();
    }

    /*     * *******************************************************************************  */
    /*     * *******************************************************************************  */
    /*     * *******************************************************************************  */

    public function actionViewCustomers() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $type = Yii::app()->request->getParam('type');
        if ($type == 'clientoffline') {

            $sort = Yii::app()->request->getParam('orderby');
            $page = Yii::app()->request->getParam('page');
            $sortorder = explode("_", $sort);
            $table = $sortorder[0];
            if ($table == 'email') {
                $set = 'email';
            } elseif ($table == 'userid') {
                $set = 'id';
            } elseif ($table == 'since') {
                $set = 'created_date';
            } elseif ($table == 'status') {
                $set = 'online_status';
            }

            $des = $sortorder[1];

            $star = 0;
            $end = 15;

            if ($page == 1) {
                $startpage = $star;
                $endpage = $end;
            } else {
                $newrow = $page - 1;
                $pagaestart = $newrow * 15;
                $startpage = $pagaestart;
                $endpage = 15;
            }
            $customers = Yii::app()->db->createCommand("SELECT * FROM `customers` WHERE online_status = 'offline' ORDER BY " . ($set) . " " . ($des) . " LIMIT " . ($startpage) . " ,  " . ($endpage) . " ")->queryAll();
            //echo "SELECT * FROM `customers` WHERE online_status = 'offline' ORDER BY ". ($set) ." ". ($des) ." LIMIT ". ($startpage)." ,  ". ($endpage). " ".'<br />';
            $totalcustomers = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM `customers` WHERE online_status = 'offline' ")->queryAll();
            $countcustomers = $totalcustomers[0]['countid'];

            $customerdetail = array();
            foreach ($customers as $customername) {
                $customersid = $customername['id'];
                $logs = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')  //Your Table name
                        ->group('customer_id')
                        ->where('status>=1 AND status<=3 AND customer_id="' . $customersid . '"') // Write your where condition here
                        ->queryAll();
                $cancelwash = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `washing_requests` WHERE status IN ('5', '6') AND `customer_id` = '$customersid' GROUP BY customer_id")->queryAll();
                /* echo "<pre>";
                  print_r($cancelwash);
                  echo "<pre>"; */
                //exit;
                if (!empty($logs)) {
                    $status = 'Busy';
                } else {
                    $status = $customername['online_status'];
                }
                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $customername['id']));

                $total_rate = count($agent_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($agent_feedbacks as $agent_feedback) {
                        $rate += $agent_feedback->agent_ratings;
                    }

                    $customer_rate = round($rate / $total_rate);
                } else {
                    $customer_rate = 0;
                }

                if (!empty($cancelwash)) {
                    $cancelcarwash = $cancelwash[0]['count'];
                } else {
                    $cancelcarwash = 0;
                }
                $customer_ids = $customername['id'];
                $client_loc = CustomerLocation::model()->findByAttributes(array("customer_id" => $customer_ids));

                $long = $client_loc->actual_longitude;
                $lat = $client_loc->actual_latitude;
                $customer_name = $customername['first_name'] . " " . $customername['last_name'];
                $name = explode(" ", $customer_name);
                $key = 'customer_' . $customername['id'];
                $json = array();
                $json['id'] = $customername['id'];
                $json['email'] = $customername['email'];
                $json['status'] = $status;
                $json['first_name'] = $name[0];
                $json['last_name'] = $name[1];
                $json['total_wash'] = $customername['total_wash'];
                $json['cancels'] = $cancelcarwash;
                $json['lastactive'] = 'N/A';
                $json['rating'] = $customer_rate;
                $json['lat'] = $lat;
                $json['long'] = $long;
                $json['client_science'] = $customername['created_date'];
                $customerdetail[] = $json;
            }
            $customersstatus['customer'] = $customerdetail;
            $customersstatus['totalcustcustomer'] = $countcustomers;


            echo json_encode($customersstatus, JSON_PRETTY_PRINT);

            exit;
        } elseif ($type == 'bad_rating_customers') {


            $sort = Yii::app()->request->getParam('orderby');
            $page = Yii::app()->request->getParam('page');
            $sortorder = explode("_", $sort);
            $table = $sortorder[0];
            if ($table == 'email') {
                $set = 'email';
            } elseif ($table == 'userid') {
                $set = 'id';
            } elseif ($table == 'since') {
                $set = 'created_date';
            } elseif ($table == 'status') {
                $set = 'online_status';
            }

            $des = $sortorder[1];

            $star = 0;
            $end = 15;

            if ($page == 1) {
                $startpage = $star;
                $endpage = $end;
            } else {
                $newrow = $page - 1;
                $pagaestart = $newrow * 15;
                $startpage = $pagaestart;
                $endpage = 15;
            }

            $clientid = $clientdata['id'];
            $customers = Yii::app()->db->createCommand("SELECT * FROM `customers` WHERE rating <= 3.50 ORDER BY " . ($set) . " " . ($des) . " LIMIT " . ($startpage) . " ,  " . ($endpage) . " ")->queryAll();
            $totalcustomers = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM `customers` WHERE rating <= 3.50 ")->queryAll();
            $countcustomers = $totalcustomers[0]['countid'];

            $customerdetail = array();
            foreach ($customers as $customername) {
                $customersid = $customername['id'];
                $logs = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')  //Your Table name
                        ->group('customer_id')
                        ->where('status>=1 AND status<=3 AND customer_id="' . $customersid . '"') // Write your where condition here
                        ->queryAll();
                $cancelwash = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `washing_requests` WHERE status IN ('5', '6') AND `customer_id` = '$customersid' GROUP BY customer_id")->queryAll();
                /* echo "<pre>";
                  print_r($cancelwash);
                  echo "<pre>"; */
                //exit;
                if (!empty($logs)) {
                    $status = 'Busy';
                } else {
                    $status = $customername['online_status'];
                }
                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $customername['id']));

                $total_rate = count($agent_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($agent_feedbacks as $agent_feedback) {
                        $rate += $agent_feedback->agent_ratings;
                    }

                    $customer_rate = round($rate / $total_rate);
                } else {
                    $customer_rate = 0;
                }

                if (!empty($cancelwash)) {
                    $cancelcarwash = $cancelwash[0]['count'];
                } else {
                    $cancelcarwash = 0;
                }
                $customer_ids = $customername['id'];
                $client_loc = CustomerLocation::model()->findByAttributes(array("customer_id" => $customer_ids));

                $long = $client_loc->actual_longitude;
                $lat = $client_loc->actual_latitude;
                $customer_name = $customername['first_name'] . " " . $customername['last_name'];
                $name = explode(" ", $customer_name);
                $key = 'customer_' . $customername['id'];
                $json = array();
                $json['id'] = $customername['id'];
                $json['email'] = $customername['email'];
                $json['status'] = $status;
                $json['first_name'] = $name[0];
                $json['last_name'] = $name[1];
                $json['total_wash'] = $customername['total_wash'];
                $json['cancels'] = $cancelcarwash;
                $json['lastactive'] = 'N/A';
                $json['rating'] = $customer_rate;
                $json['lat'] = $lat;
                $json['long'] = $long;
                $json['client_science'] = $customername['created_date'];
                $customerdetail[] = $json;
            }

            $customersstatus['customer'] = $customerdetail;
            $customersstatus['totalcustcustomer'] = $countcustomers;


            echo json_encode($customersstatus, JSON_PRETTY_PRINT);

            exit;
        } elseif ($type == 'cancel_orders_client') {
            $cancel_orders_client = Yii::app()->db->createCommand("SELECT customer_id FROM washing_requests WHERE status = '5' GROUP BY customer_id ")->queryAll();
            $clientid = array();
            foreach ($cancel_orders_client as $washing_client_id) {
                $clientid[] = $washing_client_id['customer_id'];
            }
            $clientids = implode(',', $clientid);

            $sort = Yii::app()->request->getParam('orderby');
            $page = Yii::app()->request->getParam('page');
            $sortorder = explode("_", $sort);
            $table = $sortorder[0];
            if ($table == 'email') {
                $set = 'email';
            } elseif ($table == 'userid') {
                $set = 'id';
            } elseif ($table == 'since') {
                $set = 'created_date';
            } elseif ($table == 'status') {
                $set = 'online_status';
            }

            $des = $sortorder[1];

            $star = 0;
            $end = 15;

            if ($page == 1) {
                $startpage = $star;
                $endpage = $end;
            } else {
                $newrow = $page - 1;
                $pagaestart = $newrow * 15;
                $startpage = $pagaestart;
                $endpage = 15;
            }
            $customers = Yii::app()->db->createCommand("SELECT * FROM customers WHERE id IN ( $clientids ) ORDER BY " . ($set) . " " . ($des) . " LIMIT " . ($startpage) . " ,  " . ($endpage) . " ")->queryAll();
            //$totalcustomers =  Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM `customers` WHERE online_status = 'offline' ")->queryAll();
            $countcustomers = count($clientid);

            $customerdetail = array();
            foreach ($customers as $customername) {
                $customersid = $customername['id'];
                $logs = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')  //Your Table name
                        ->group('customer_id')
                        ->where('status>=1 AND status<=3 AND customer_id="' . $customersid . '"') // Write your where condition here
                        ->queryAll();
                $cancelwash = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `washing_requests` WHERE status IN ('5', '6') AND `customer_id` = '$customersid' GROUP BY customer_id")->queryAll();
                /* echo "<pre>";
                  print_r($cancelwash);
                  echo "<pre>"; */
                //exit;
                if (!empty($logs)) {
                    $status = 'Busy';
                } else {
                    $status = $customername['online_status'];
                }
                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $customername['id']));

                $total_rate = count($agent_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($agent_feedbacks as $agent_feedback) {
                        $rate += $agent_feedback->agent_ratings;
                    }

                    $customer_rate = round($rate / $total_rate);
                } else {
                    $customer_rate = 0;
                }

                if (!empty($cancelwash)) {
                    $cancelcarwash = $cancelwash[0]['count'];
                } else {
                    $cancelcarwash = 0;
                }
                $customer_ids = $customername['id'];
                $client_loc = CustomerLocation::model()->findByAttributes(array("customer_id" => $customer_ids));

                $long = $client_loc->actual_longitude;
                $lat = $client_loc->actual_latitude;
                $customer_name = $customername['first_name'] . " " . $customername['last_name'];
                $name = explode(" ", $customer_name);
                $key = 'customer_' . $customername['id'];
                $json = array();
                $json['id'] = $customername['id'];
                $json['email'] = $customername['email'];
                $json['status'] = $status;
                $json['first_name'] = $name[0];
                $json['last_name'] = $name[1];
                $json['total_wash'] = $customername['total_wash'];
                $json['cancels'] = $cancelcarwash;
                $json['lastactive'] = 'N/A';
                $json['rating'] = $customer_rate;
                $json['lat'] = $lat;
                $json['long'] = $long;
                $json['client_science'] = $customername['created_date'];
                $customerdetail[] = $json;
            }
            $customersstatus['customer'] = $customerdetail;
            $customersstatus['totalcustcustomer'] = $countcustomers;


            echo json_encode($customersstatus, JSON_PRETTY_PRINT);

            exit;
        } elseif ($type == 'idle_wash_client') {

            $format = 'Y-m-d h:i:s';
            $date = date($format, strtotime('-60 days'));
            $idle_count = Yii::app()->db->createCommand("SELECT id FROM customers WHERE total_wash = 0 ")->queryAll();
            $client_one = array();
            foreach ($idle_count as $customerid) {
                $client_one[] = $customerid['id'];
            }

            $idle = Yii::app()->db->createCommand("SELECT * FROM customers WHERE total_wash != 0 ")->queryAll();
            $client_two = array();
            foreach ($idle as $washers) {


                $id = $washers['id'];
                $totalwash = $washers['total_wash'];
                if ($totalwash != 0) {
                    $clientwash_date = Yii::app()->db->createCommand("SELECT customer_id FROM washing_requests WHERE agent_id = '$id' AND created_date <= '$date' GROUP BY customer_id ")->queryAll();

                    foreach ($clientwash_date as $wash) {

                        if (!empty($agentwash_date)) {

                            $client_two[] = $wash['customer_id'];
                        }
                    }
                }
            }
            $idle_clientid = array_merge($client_one, $client_two);
            $clientids = implode(',', $idle_clientid);

            $sort = Yii::app()->request->getParam('orderby');
            $page = Yii::app()->request->getParam('page');
            $sortorder = explode("_", $sort);
            $table = $sortorder[0];
            if ($table == 'email') {
                $set = 'email';
            } elseif ($table == 'userid') {
                $set = 'id';
            } elseif ($table == 'since') {
                $set = 'created_date';
            } elseif ($table == 'status') {
                $set = 'online_status';
            }

            $des = $sortorder[1];

            $star = 0;
            $end = 15;

            if ($page == 1) {
                $startpage = $star;
                $endpage = $end;
            } else {
                $newrow = $page - 1;
                $pagaestart = $newrow * 15;
                $startpage = $pagaestart;
                $endpage = 15;
            }
            $customers = Yii::app()->db->createCommand("SELECT * FROM customers WHERE id IN ( $clientids ) ORDER BY " . ($set) . " " . ($des) . " LIMIT " . ($startpage) . " ,  " . ($endpage) . " ")->queryAll();
            //$totalcustomers =  Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM `customers` WHERE online_status = 'offline' ")->queryAll();
            $countcustomers = count($idle_clientid);

            $customerdetail = array();
            foreach ($customers as $customername) {
                $customersid = $customername['id'];
                $logs = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')  //Your Table name
                        ->group('customer_id')
                        ->where('status>=1 AND status<=3 AND customer_id="' . $customersid . '"') // Write your where condition here
                        ->queryAll();
                $cancelwash = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `washing_requests` WHERE status IN ('5', '6') AND `customer_id` = '$customersid' GROUP BY customer_id")->queryAll();
                /* echo "<pre>";
                  print_r($cancelwash);
                  echo "<pre>"; */
                //exit;
                if (!empty($logs)) {
                    $status = 'Busy';
                } else {
                    $status = $customername['online_status'];
                }
                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $customername['id']));

                $total_rate = count($agent_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($agent_feedbacks as $agent_feedback) {
                        $rate += $agent_feedback->agent_ratings;
                    }

                    $customer_rate = round($rate / $total_rate);
                } else {
                    $customer_rate = 0;
                }

                if (!empty($cancelwash)) {
                    $cancelcarwash = $cancelwash[0]['count'];
                } else {
                    $cancelcarwash = 0;
                }
                $customer_ids = $customername['id'];
                $client_loc = CustomerLocation::model()->findByAttributes(array("customer_id" => $customer_ids));

                $long = $client_loc->actual_longitude;
                $lat = $client_loc->actual_latitude;
                $customer_name = $customername['first_name'] . " " . $customername['last_name'];
                $name = explode(" ", $customer_name);
                $key = 'customer_' . $customername['id'];
                $json = array();
                $json['id'] = $customername['id'];
                $json['email'] = $customername['email'];
                $json['status'] = $status;
                $json['first_name'] = $name[0];
                $json['last_name'] = $name[1];
                $json['total_wash'] = $customername['total_wash'];
                $json['cancels'] = $cancelcarwash;
                $json['lastactive'] = 'N/A';
                $json['rating'] = $customer_rate;
                $json['lat'] = $lat;
                $json['long'] = $long;
                $json['client_science'] = $customername['created_date'];
                $customerdetail[] = $json;
            }
            $customersstatus['customer'] = $customerdetail;
            $customersstatus['totalcustcustomer'] = $countcustomers;


            echo json_encode($customersstatus, JSON_PRETTY_PRINT);

            exit;
        }
    }

    public function actionCheckCurrentPassword() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $id = Yii::app()->request->getParam('id');
        $password = Yii::app()->request->getParam('password');
        $password = md5($password);
        $checkpassword = Yii::app()->db->createCommand("SELECT * FROM customers WHERE password = :password AND id = :id ")
                ->bindValue(':password', $password, PDO::PARAM_STR)
                ->bindValue(':id', $id, PDO::PARAM_STR)
                ->queryAll();

        if (!empty($checkpassword)) {
            $result = 'true';
            $response = 'confirm password';
            $json = array(
                'result' => $result,
                'response' => $response
            );
            echo json_encode($json);
            die();
        } else {
            $result = 'false';
            $response = "wrong password";
            $json = array(
                'result' => $result,
                'response' => $response
            );
            echo json_encode($json);
            die();
        }
    }

    public function actionGetCustomerLoginDetail() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerID = Yii::app()->request->getParam('customerID');
        $customerdetail = Customers::model()->findByAttributes(array("id" => $customerID));
        $lastorder = Yii::app()->db->createCommand("SELECT created_date, complete_order FROM washing_requests WHERE customer_id = '$customerID' AND status = '4' ORDER BY id DESC LIMIT 0,1 ")->queryAll();
        $image = $customerdetail->image;
        //$lastactive = $customerdetail->last_active;
        $lastlogin = explode(" ", $lastactive);
        $createddate = $lastorder[0]['created_date'];
        $lastprderdetail = explode(" ", $lastorder[0]['created_date']);
        $completeorder = $lastorder[0]['complete_order'];
        $date_current = date('y-m-d');
        $login_time = strtotime($lastlogin[0]);
        $currenttime = strtotime($date_current);
        $lastorder_day = strtotime($lastprderdetail[0]);
        $completeorder_day = strtotime($completeorder);
        $datediff = $currenttime - $login_time;
        $orderdiff = $currenttime - $lastorder_day;
        $ordercompletediff = $currenttime - $completeorder_day;
        $day = floor($datediff / (60 * 60 * 24));
        $orderday = floor($orderdiff / (60 * 60 * 24));
        $completeorderday = floor($ordercompletediff / (60 * 60 * 24));

        /* $customertotal =  Yii::app()->db->createCommand("SELECT net_price FROM washing_requests WHERE customer_id = :customer_id ")
          ->bindValue(':customer_id', $customerID, PDO::PARAM_STR)
          ->queryAll();
          $totalprice = '';
          foreach($customertotal as $toatl){
          $totalprice += $toatl['net_price'];
          } */

        $custspent = Yii::app()->db->createCommand("SELECT SUM(net_price) FROM washing_requests WHERE customer_id = :customer_id AND  status = 4 AND net_price > 0")
                ->bindValue(':customer_id', $customerID, PDO::PARAM_STR)
                ->queryAll();
        $totalpaid = 0;
        $totalpaid = $custspent[0]['SUM(net_price)'];

        if ($lastactive != '0000-00-00 00:00:00') {
            if ($day == 0) {
                $lastlogintime = $lastlogin[1];
                $login = 'Today';
            } else {
                $lastlogintime = $lastlogin[1];
                $login = $day . ' day(s) ago';
            }
        } else {
            $lastlogintime = 'N/A';
            $login = 'N/A';
        }
        if ($createddate != '0000-00-00 00:00:00' && !empty($createddate)) {
            if ($orderday == 0) {
                $order = 'Today';
            } else {
                $order = $orderday . ' day(s) ago';
            }
        } else {
            $order = 'N/A';
        }
        if ($completeorder != '0000-00-00' && !empty($completeorder)) {
            if ($completeorderday == 0) {
                $complte_order = 'Today';
            } else {
                $complte_order = $completeorderday . ' day(s) ago';
            }
        } else {
            $complte_order = 'N/A';
        }
        $result = 'true';
        $response = "last active";
        $json = array(
            'result' => $result,
            'response' => $response,
            'last_login' => $login,
            'image' => $image,
            'login_time' => $lastlogintime,
            'totalprice' => number_format($totalpaid, 2),
            'complte_order' => $complte_order,
            'lastorder' => $order
        );
        echo json_encode($json);
        die();
    }

    public function actionVerifyPhone() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('id');
        $num = Yii::app()->request->getParam('tonumber');

        //$getnumber = urlencode($num);
        //$doc = "(+918745) -042-716";
        //$number =  str_replace(')', '', str_replace('(','',$num));
        //echo $number.'<br />';
        //$number_formate =  str_replace('-','',$number);
        //$to_number =  str_replace('+','',$number_formate);
        //$to_num =  str_replace(' ','',$number_formate);
        //echo $to_num;
        //exit;
        // $message = urlencode($message);

        $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $num));

        if ((count($customers_phone_exists) > 0) && ($customers_phone_exists->id != $customerid)) {
            $result = 'false';
            $response = 'Phone number already exists.';

            $json = array(
                'result' => $result,
                'response' => $response
            );

            echo json_encode($json);
            die();
        }

        $digits = 4;
        $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $update_response = Yii::app()->db->createCommand("UPDATE customers SET phone_verify_code='$randum_number' WHERE id = :id ")
                ->bindValue(':id', $customerid, PDO::PARAM_STR)
                ->execute();
        $result = 'false';
        $json = array();

        $this->layout = "xmlLayout";
        spl_autoload_unregister(array(
            'YiiBase',
            'autoload'
        ));
        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');
        require('Services/Twilio.php');
        require('Services/Twilio/Capability.php');

        /* Instantiate a new Twilio Rest Client */

        $account_sid = TWILIO_SID;
        $auth_token = TWILIO_AUTH_TOKEN;
        $client = new Services_Twilio($account_sid, $auth_token);


        $message = "Here is your MobileWash verification code " . $randum_number;
        $sendmessage = $client->account->messages->create(array(
            'To' => $num,
            'From' => '+13103128070',
            'Body' => $message,
        ));





        if ($sendmessage != "") {

            $data = array(
                'result' => 'true',
                'response' => 'Send 4 digit code.'
            );
        } else {

            $data = array(
                'result' => 'false',
                'response' => 'phone number is invalid wrong, Please check.'
            );
        }


        echo json_encode($data);
        spl_autoload_register(array(
            'YiiBase',
            'autoload'
        ));
        exit;
    }

    public function actionConfirmPhone() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('id');
        $sortcode = Yii::app()->request->getParam('verify_code');
        $model = new Customers;
        $matchcode = Customers::model()->findByAttributes(array("phone_verify_code" => $sortcode, "id" => $customerid));
        if (!empty($matchcode)) {
            if ($matchcode->phone_verified != 1) {
                $update_response = Yii::app()->db->createCommand("UPDATE customers SET phone_verified='1' WHERE id = :id AND phone_verify_code = :phone_verify_code ")
                        ->bindValue(':id', $customerid, PDO::PARAM_STR)
                        ->bindValue(':phone_verify_code', $sortcode, PDO::PARAM_STR)
                        ->execute();
                $data = array(
                    'result' => 'true',
                    'response' => 'Congratulations, Your phone is verified.'
                );
                echo json_encode($data);
                exit;
            } else {
                $data = array(
                    'result' => 'false',
                    'response' => 'Your phone already verified.'
                );
                echo json_encode($data);
                exit;
            }
        } else {
            $data = array(
                'result' => 'false',
                'response' => 'Incorrect verification code'
            );
            echo json_encode($data);
            exit;
        }
    }

    public function actionaddcustomerpaymentmethod() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $nonce = Yii::app()->request->getParam('nonce');
        $deviceData = '';
        if (Yii::app()->request->getParam('deviceData'))
            $deviceData = Yii::app()->request->getParam('deviceData');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $payment_method_token = Yii::app()->request->getParam('payment_method_token');
        $payment_methods = array();
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($nonce) && !empty($nonce))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customers = Customers::model()->findByPk($customer_id);

            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else {

                $cust_bt_id = $customers->braintree_id;
                /* $customer_details = Yii::app()->braintree->find($cust_bt_id);
                  echo '<pre>';print_r($customer_details);die; */
                if ($cust_bt_id) {
                    if ($payment_method_token) {
                        if ($customers->client_position == 'real') {
                            $createmethodresult = Yii::app()->braintree->updatePaymentMethod_real($payment_method_token, ['options' => [
                                    'verifyCard' => false,
                                    'makeDefault' => true
                                ]]
                            );
                        } else {
                            $createmethodresult = Yii::app()->braintree->updatePaymentMethod($payment_method_token, ['options' => [
                                    'verifyCard' => false,
                                    'makeDefault' => true
                                ]]
                            );
                        }
                    } else {
                        if ($customers->client_position == 'real') {
                            $createmethodresult = Yii::app()->braintree->addPaymentMethod_real([
                                'customerId' => $cust_bt_id,
                                'paymentMethodNonce' => $nonce,
                                'deviceData' => $deviceData,
                                'options' => [
                                    'verifyCard' => true,
                                    'makeDefault' => true
                                ]
                            ]);
                        } else {
                            $createmethodresult = Yii::app()->braintree->addPaymentMethod([
                                'customerId' => $cust_bt_id,
                                'paymentMethodNonce' => $nonce,
                                'deviceData' => $deviceData,
                                'options' => [
                                    'verifyCard' => true,
                                    'makeDefault' => true
                                ]
                            ]);
                        }
                    }


                    if ($createmethodresult['success'] == 0) {
                        $json = array(
                            'result' => $result,
                            'response' => $createmethodresult['message_mob'],
                        );

                        echo json_encode($json);
                        die();
                    } else {
                        $result = 'true';
                        $response = $createmethodresult['token'];

                        if ($customers->client_position == 'real')
                            $Bresult = Yii::app()->braintree->getCustomerById_real($customers->braintree_id);
                        else
                            $Bresult = Yii::app()->braintree->getCustomerById($customers->braintree_id);
                        //var_dump($Bresult);
                        if (count($Bresult->paymentMethods)) {

                            foreach ($Bresult->paymentMethods as $index => $paymethod) {
                                $payment_methods[$index]['title'] = get_class($paymethod);
                                if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                                    $payment_methods[$index]['title'] = 'Credit Card';
                                    $payment_methods[$index]['payment_method_details'] = array("expirationMonth" => $paymethod->expirationMonth, "expirationYear" => $paymethod->expirationYear, "bin" => $paymethod->bin, "last4" => $paymethod->last4, "maskedNumber" => $paymethod->maskedNumber, "cardType" => $paymethod->cardType, "token" => $paymethod->token, "cardname" => $paymethod->cardholderName, "cardimg" => $paymethod->imageUrl, "isDefault" => $paymethod->isDefault());
                                }

                                if ($payment_methods[$index]['title'] == 'Braintree\\PayPalAccount') {
                                    $payment_methods[$index]['title'] = 'Paypal';
                                    $payment_methods[$index]['payment_method_details'] = array("email" => $paymethod->email, "token" => $paymethod->token);
                                }
                            }
                        }



                        $data = array(
                            'result' => $result,
                            'response' => $response,
                            'masked_number' => $createmethodresult['masked_number'],
                            'card_type' => $createmethodresult['card_type'],
                            'payment_methods' => $payment_methods
                        );

                        echo json_encode($data);
                        die();
                    }
                } else {
                    $result = 'false';
                    $response = 'customer braintree id not exists';
                }
            }
        }

        $data = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($data);
    }

    public function actioncustomerpayment() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
        $deviceData = '';
        if (Yii::app()->request->getParam('deviceData'))
            $deviceData = Yii::app()->request->getParam('deviceData');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        $amount = 0;
        $amount = Yii::app()->request->getParam('amount');

        $company_total = 0;
        $company_total = Yii::app()->request->getParam('company_total');
        $transaction_fee = 0;
        $response = "Pass the required parameters";
        $result = "false";

        if ((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($agent_id) && !empty($agent_id))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $customer_check = Customers::model()->findByPk($customer_id);
            $wash_check = Washingrequests::model()->findByPk($wash_request_id);
            $agent_check = Agents::model()->findByPk($agent_id);

            if (!count($customer_check)) {
                $response = "Invalid customer id";
                $result = "false";
            } else if (!count($wash_check)) {
                $response = "Invalid wash request id";
                $result = "false";
            } else if (!count($agent_check)) {
                $response = "Invalid agent id";
                $result = "false";
            } else {
                if (!$customer_check->braintree_id) {
                    $json = array('result' => 'false',
                        'response' => 'customer braintree id not found');

                    echo json_encode($json);
                    die();
                }

                if ($customer_check->client_position == 'real')
                    $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);
                else
                    $Bresult = Yii::app()->braintree->getCustomerById($customer_check->braintree_id);
                //print_r($Bresult);

                if (is_array($Bresult)) {
                    $json = array('result' => 'false', 'response' => 'Customer ID not found in Braintree');

                    echo json_encode($json);
                    die();
                }
                $token = '';
                if (count($Bresult->paymentMethods)) {
                    foreach ($Bresult->paymentMethods as $index => $paymethod) {
                        $payment_methods[$index]['title'] = get_class($paymethod);
                        if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                            if ($paymethod->isDefault()) {
                                $token = $paymethod->token;
                                break;
                            }
                        }
                    }
                } else {
                    $json = array('result' => 'false', 'response' => 'No payment methods found');

                    echo json_encode($json);
                    die();
                }

                if (!$token) {
                    $json = array('result' => 'false', 'response' => 'No default payment method found');

                    echo json_encode($json);
                    die();
                }

                if (!$agent_check->bt_submerchant_id) {
                    $json = array('result' => 'false', 'response' => 'agent braintree id not found');

                    echo json_encode($json);
                    die();
                }

                /* $handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
                  $data = array('wash_request_id' => $wash_request_id, "key" => API_KEY);
                  curl_setopt($handle, CURLOPT_POST, true);
                  curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                  curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                  $kartresult = curl_exec($handle);
                  curl_close($handle);
                  $kartdetails = json_decode($kartresult); */

                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdetails = json_decode($kartapiresult);

                if ($wash_check->transaction_id) {
                    if ($customer_check->client_position == 'real')
                        $voidresult = Yii::app()->braintree->void_real($wash_check->transaction_id);
                    else
                        $voidresult = Yii::app()->braintree->void($wash_check->transaction_id);
                }

                if ($amount > 0) {
                    $transaction_fee = ($amount * 0.029) + .30;
                    $company_total += $transaction_fee;
                    $company_total = number_format($company_total, 2);
                    $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wash_request_id, 'serviceFeeAmount' => $company_total, 'amount' => str_replace(",", "", $amount), 'paymentMethodToken' => $token, 'deviceData' => $deviceData];
                } else
                    $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wash_request_id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => str_replace(",", "", $kartdetails->net_price), 'paymentMethodToken' => $token, 'deviceData' => $deviceData];

                if ($customer_check->client_position == 'real')
                    $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                else
                    $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);

                if ($payresult['success'] == 1) {
                    $response = "Payment successful";
                    $result = "true";

                    Washingrequests::model()->updateByPk($wash_request_id, array('transaction_id' => $payresult['transaction_id'], 'failed_transaction_id' => '', 'payment_type' => 'Credit Card'));
                } else {
                    $result = "false";
                    $response = $payresult['message_mob'];
                    Washingrequests::model()->updateByPk($wash_request_id, array('failed_transaction_id' => $payresult['transaction_id']));
                }
            }
        }

        $json = array('result' => $result, 'response' => $response);

        echo json_encode($json);
        die();
    }

    public function actioncustomerupfrontpayment() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $total = Yii::app()->request->getParam('total');
        $company_total = Yii::app()->request->getParam('company_total');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $is_scheduled = Yii::app()->request->getParam('is_scheduled');
        $token = Yii::app()->request->getParam('payment_token');
        $deviceData = '';
        if (Yii::app()->request->getParam('deviceData'))
            $deviceData = Yii::app()->request->getParam('deviceData');
        $transaction_fee = 0;
        $response = "Pass the required parameters";
        $result = "false";
        $tid = '';
        date_default_timezone_set('America/Los_Angeles');

        if ((isset($customer_id) && !empty($customer_id)) && (isset($total) && !empty($total)) && (isset($company_total) && !empty($company_total))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customer_check = Customers::model()->findByPk($customer_id);

            /* $current_day = strtolower(date('l'));
              //$current_time = date('g:i:s A');
              //echo $current_day;
              //echo "<br>".$current_time;

              $hours_op_check =  Shedule::model()->findByAttributes(array('day'=>$current_day));
              $hours_op_response =  Shedule::model()->findByAttributes(array('id'=>8)); */

            if (!count($customer_check)) {
                $response = "Invalid customer id";
                $result = "false";
            }

            /* else if(($customer_check->hours_opt_check == 1) && ($hours_op_check->status == 'off') && (!$is_scheduled)){
              $result= 'false';
              $response= $hours_op_response->status;

              }

              else if( ($customer_check->hours_opt_check == 1) && ($hours_op_check->open_all_day != 'yes') && (!$is_scheduled) && ((time() < strtotime($hours_op_check->from)) || (time() > strtotime($hours_op_check->to)))){
              $result= 'false';
              $response= $hours_op_response->status;

              } */

            /* else if((!$is_scheduled) && ($nearagentsdetails->result == 'false')){

              $result= 'false';
              $response= 'no washers found';

              } */ else if (!$total) {
                $response = "Order total not specified";
                $result = "false";
            } else if (!$company_total) {
                $response = "Company total not specified";
                $result = "false";
            } else {
                if (!$customer_check->braintree_id) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'customer braintree id not found',
                    );

                    echo json_encode($json);
                    die();
                }
                if ($customer_check->client_position == 'real')
                    $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);
                else
                    $Bresult = Yii::app()->braintree->getCustomerById($customer_check->braintree_id);
                if (!$token) {
                    if (count($Bresult->paymentMethods)) {
                        foreach ($Bresult->paymentMethods as $index => $paymethod) {
                            $payment_methods[$index]['title'] = get_class($paymethod);
                            if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                                if ($paymethod->isDefault()) {
                                    $token = $paymethod->token;
                                    break;
                                }
                            }
                        }
                    } else {
                        $json = array(
                            'result' => 'false',
                            'response' => 'No payment methods found'
                        );

                        echo json_encode($json);
                        die();
                    }
                }

                if (!$token) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'No default payment method found'
                    );

                    echo json_encode($json);
                    die();
                }


                $current_time = strtotime(date('Y-m-d H:i:s'));
                $last_edit_time = strtotime($customer_check->last_upfront_payment_cut);

                $min_diff = round(($current_time - $last_edit_time) / 60, 2);


                if ((strtotime($customer_check->last_upfront_payment_cut) > 0) && ($min_diff <= 60)) {
                    $response = "Payment successful";
                    $result = "true";

                    $tid = '';
                } else {

                    $total = preg_replace("/[^0-9\.]/", "", $total);
                    $total = number_format($total, 2, '.', '');

                    $company_total = preg_replace("/[^0-9\.]/", "", $company_total);
                    $company_total = number_format($company_total, 2, '.', '');

                    $transaction_fee = ($total * 0.029) + .30;
                    $company_total += $transaction_fee;

                    $company_total = number_format($company_total, 2);

                    //$request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_total, 'amount' => $total,'paymentMethodToken' => $token];
                    if ($customer_check->client_position == 'real') {
                        $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_total, 'amount' => $total, 'paymentMethodToken' => $token, 'deviceData' => $deviceData];

                        $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                    } else {
                        $request_data = ['merchantAccountId' => 'mobilewash_payment_inst_m59bj2b6', 'serviceFeeAmount' => $company_total, 'amount' => $total, 'paymentMethodToken' => $token, 'deviceData' => $deviceData];

                        $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                    }

                    //print_r($Bresult);
                    //die();
                    if ($payresult['success'] == 1) {

                        if ($customer_check->client_position == 'real')
                            Yii::app()->braintree->updatePaymentMethod_real($token, ['options' => ['makeDefault' => true]]);
                        else
                            Yii::app()->braintree->updatePaymentMethod($token, ['options' => ['makeDefault' => true]]);

                        Customers::model()->updateByPk($customer_id, array('last_upfront_payment_cut' => date("Y-m-d H:i:s")));

                        //print_r($result);die;
                        $response = "Payment successful";
                        $result = "true";

                        $tid = $payresult['transaction_id'];
                    } else {
                        $result = "false";
                        $response = $payresult['message_mob'];
                    }
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'transaction_id' => $tid
        );

        echo json_encode($json);
        die();
    }

    //https://www.mobilewash.com/api/index.php?r=customers/customerupfrontpayment_new&key=Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4&customer_id=2201&total=45&company_total=55&latitude=76.707771&longitude=30.715261&is_scheduled=&token=
    public function actioncustomerupfrontpayment_new() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $total = Yii::app()->request->getParam('total');
        $company_total = Yii::app()->request->getParam('company_total');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $is_scheduled = Yii::app()->request->getParam('is_scheduled');
        $token = Yii::app()->request->getParam('payment_token');

        $response = "Pass the required parameters";
        $result = "false";
        $tid = '';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($total) && !empty($total)) && (isset($company_total) && !empty($company_total))) {
            $customer_check = Customers::model()->findByPk($customer_id);

            /* ------- get nearest agents --------- */
            $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
            $data = array('cust_lat' => $latitude, 'cust_lng' => $longitude, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($handle);
            curl_close($handle);
            $nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */
            if (!count($customer_check)) {
                $response = "Invalid customer id";
                $result = "false";
            }
            /* else if((!$is_scheduled) && ($nearagentsdetails->result == 'false')){
              $result= 'false';
              $response= 'no washers found';
              }
              else if(!$total){
              $response = "Order total not specified";
              $result = "false";
              }
              else if(!$company_total){
              $response = "Company total not specified";
              $result = "false";
              } */ else {

                if (!$customer_check->braintree_id) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'customer braintree id not found',
                    );
                    echo json_encode($json);
                    die();
                }
                $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);

                if (!$token) {
                    if (count($Bresult->paymentMethods)) {
                        foreach ($Bresult->paymentMethods as $index => $paymethod) {
                            $payment_methods[$index]['title'] = get_class($paymethod);
                            if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                                if ($paymethod->isDefault()) {
                                    $token = $paymethod->token;
                                    break;
                                }
                            }
                        }
                    } else {
                        $json = array(
                            'result' => 'false',
                            'response' => 'No payment methods found'
                        );
                        echo json_encode($json);
                        die();
                    }
                }

                if (!$token) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'No default payment method found'
                    );
                    echo json_encode($json);
                    die();
                }
                $creditCard = Yii::app()->braintree->findCard($token);
                echo '<pre>';
                print_r($creditCard);
                die;
                echo '$token=' . $token;
                die;
                $request_data = ['merchantAccountId' => 'al_davi_instant_4pjkk25r', 'serviceFeeAmount' => $company_total, 'amount' => $total, 'paymentMethodToken' => $token];
                $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                //print_r($Bresult);
                //die();
                if ($payresult['success'] == 1) {
                    Yii::app()->braintree->updatePaymentMethod_real($token, ['options' => ['makeDefault' => true]]);
                    //print_r($result);die;
                    $response = "Payment successful";
                    $result = "true";

                    $tid = $payresult['transaction_id'];
                } else {
                    $result = "false";
                    $response = $payresult['message_mob'];
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'transaction_id' => $tid
        );
        echo json_encode($json);
        die();
    }

    public function actionCustomerCancelWashPayment() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
        $amount = Yii::app()->request->getParam('amount');
        $washing_request_id = Yii::app()->request->getParam('wash_request_id');
        $deviceData = '';
        if (Yii::app()->request->getParam('deviceData'))
            $deviceData = Yii::app()->request->getParam('deviceData');
        $wash_position = Yii::app()->request->getParam('wash_position');
        $response = "Pass the required parameters";
        $result = "false";
        $payment_type = '';
        $company_cancel = 0;
        if ($company_cancel)
            $company_cancel = Yii::app()->request->getParam('company_cancel');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((isset($customer_id) && !empty($customer_id)) && (isset($agent_id) && !empty($agent_id)) && (isset($washing_request_id) && !empty($washing_request_id)) && (isset($amount) && !empty($amount))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $washing_request_id = $this->aes256cbc_crypt($washing_request_id, 'd', AES256CBC_API_PASS);
            }
            $customers = Customers::model()->findByPk($customer_id);
            $wash_id_check = Washingrequests::model()->findByPk($washing_request_id);
            $agent_check = Agents::model()->findByPk($agent_id);
            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else if (!$wash_id_check) {
                $response = "Invalid wash request id";
                $result = "false";
            } else if (!$agent_check) {
                $response = "Invalid agent id";
                $result = "false";
            } else {

                if (!$customers->braintree_id) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'customer braintree id not found',
                    );

                    echo json_encode($json);
                    die();
                }
                if (($wash_position == 'demo') || ($wash_position == ''))
                    $Bresult = Yii::app()->braintree->getCustomerById($customers->braintree_id);
                else
                    $Bresult = Yii::app()->braintree->getCustomerById_real($customers->braintree_id);
                $token = '';
                if (count($Bresult->paymentMethods)) {
                    foreach ($Bresult->paymentMethods as $index => $paymethod) {
                        $payment_methods[$index]['title'] = get_class($paymethod);
                        if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                            if ($paymethod->isDefault()) {
                                $token = $paymethod->token;
                                break;
                            }
                        }
                    }
                } else {
                    $json = array(
                        'result' => 'false',
                        'response' => 'No payment methods found'
                    );

                    echo json_encode($json);
                    die();
                }

                if (!$token) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'No default payment method found'
                    );

                    echo json_encode($json);
                    die();
                }

                if (!$agent_check->bt_submerchant_id) {
                    $json = array(
                        'result' => 'false',
                        'response' => 'agent braintree id not found'
                    );

                    echo json_encode($json);
                    die();
                }

                if (($wash_id_check->status >= 1) && ($wash_id_check->status <= 3)) {
                    $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => "5.00", 'amount' => $amount, 'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => true], 'deviceData' => $deviceData];
                    if (($wash_position == 'demo') || ($wash_position == ''))
                        $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                    else
                        $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                }
                else {
                    $request_data = ['amount' => $amount, 'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => true], 'deviceData' => $deviceData];
                    if (($wash_position == 'demo') || ($wash_position == ''))
                        $payresult = Yii::app()->braintree->sale($request_data);
                    else
                        $payresult = Yii::app()->braintree->sale_real($request_data);
                }
                //print_r($Bresult);
                //die();
                if ($payresult['success'] == 1) {

                    /* -------- cancel wash ------------ */

                    $car_ids = $wash_id_check->car_list;
                    $car_ids_arr = explode(",", $car_ids);
                    foreach ($car_ids_arr as $car) {
                        $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '');
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                    }

                    if (($wash_id_check->status >= 1) && ($wash_id_check->status <= 3))
                        $data = array('status' => 5, 'company_cancel' => $company_cancel, 'order_canceled_at' => date("Y-m-d H:i:s"), 'cancel_fee' => $amount, 'washer_cancel_fee' => $amount - 5);
                    else {
                        $data = array('status' => 5, 'company_cancel' => $company_cancel, 'order_canceled_at' => date("Y-m-d H:i:s"), 'cancel_fee' => $amount);
                    }
                    $washrequestmodel = new Washingrequests;
                    $washrequestmodel->attributes = $data;

                    $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id' => $washing_request_id));

                    /* -------- cancel wash end ------------ */

                    //print_r($result);die;
                    $response = "Payment successful and wash canceled";
                    $result = "true";
                    $payment_type = $payresult['transaction_id'];
                    //$update_request = Washingrequests::model()->findByPk($washing_request_id);
                    //$update_request->transaction_id = $Bresult['transaction_id'];
                    //$update_request->save(false);
                } else {
                    $result = "false";
                    $response = $payresult['message'];
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actiongetallschedulewashes() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $response = "Pass the required parameters";
        $result = "false";
        $allwashes = array();

        if ((isset($customer_id) && !empty($customer_id))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $customers = Customers::model()->findByPk($customer_id);


            if (!$customers) {
                $response = "Invalid customer id";
                $result = "false";
            } else {
                $allschedwashes = Washingrequests::model()->findAllByAttributes(array('customer_id' => $customer_id, 'is_scheduled' => 1), array('condition' => 'status = 0 OR status = 1 OR status = 2'), array('order' => 'id desc'));
                if (count($allschedwashes)) {
                    $response = "all scheduled washes";
                    $result = "true";
                    foreach ($allschedwashes as $schedwash) {
                        $sched_date = '';
                        $sched_time = '';
                        if ($schedwash->reschedule_time) {
                            $sched_date = $schedwash->reschedule_date;
                            $sched_time = $schedwash->reschedule_time;
                        } else {
                            $sched_date = $schedwash->schedule_date;
                            $sched_time = $schedwash->schedule_time;
                        }

                        if (AES256CBC_STATUS == 1) {
                            $allwashes[] = array('id' => $this->aes256cbc_crypt($schedwash->id, 'e', AES256CBC_API_PASS),
                                'car_list' => $schedwash->car_list,
                                'package_list' => $schedwash->package_list,
                                'address' => $schedwash->address,
                                'address_type' => $schedwash->address_type,
                                'latitude' => $schedwash->latitude,
                                'longitude' => $schedwash->longitude,
                                'status' => $schedwash->status,
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time
                            );
                        } else {
                            $allwashes[] = array('id' => $schedwash->id,
                                'car_list' => $schedwash->car_list,
                                'package_list' => $schedwash->package_list,
                                'address' => $schedwash->address,
                                'address_type' => $schedwash->address_type,
                                'latitude' => $schedwash->latitude,
                                'longitude' => $schedwash->longitude,
                                'status' => $schedwash->status,
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time
                            );
                        }
                    }
                } else {
                    $response = "no scheduled washes found";
                    $result = "false";
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'schedule_washes' => $allwashes
        );

        echo json_encode($json);
        die();
    }

    public function actionallclientslogout() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'error in logout operation';


        $update_status = Customers::model()->updateAll(array('online_status' => 'offline', 'device_token' => ''));

        if ($update_status) {
            $result = 'true';
            $response = 'Successfully logout';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionaddcustomerdevice() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'pass required parameters';

        $customer_id = Yii::app()->request->getParam('customer_id');
        $device_name = Yii::app()->request->getParam('device_name');
        $device_id = Yii::app()->request->getParam('device_id');
        $device_token = Yii::app()->request->getParam('device_token');
        $os_details = Yii::app()->request->getParam('os_details');
        $device_type = Yii::app()->request->getParam('device_type');
        $aws_platformarn = '';
        $endpoint_arn = '';

        if ((isset($customer_id) && !empty($customer_id)) && (isset($device_id) && !empty($device_id)) && (isset($device_token) && !empty($device_token))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $device_exists = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE device_id = :device_id")
                    ->bindValue(':device_id', $device_id, PDO::PARAM_STR)
                    ->queryAll();

            if (count($device_exists) > 0) {

                if ($device_exists[0]['endpoint_arn']) {
                    try {
                        $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);

                        $aws_client = SnsClient::factory(array(
                                    'credentials' => $aws_credentials,
                                    'region' => 'us-west-2',
                                    'version' => 'latest'
                        ));

                        $aws_result = $aws_client->setEndpointAttributes([
                            'Attributes' => array("Token" => $device_token, "Enabled" => 'true'),
                            'EndpointArn' => $device_exists[0]['endpoint_arn'],
                        ]);

                        $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $device_exists[0]['endpoint_arn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:custschedpush',
                        ]);

                        $endpoint_arn = $device_exists[0]['endpoint_arn'];
                    } catch (exception $e) {
                        
                    }
                } else {
                    try {
                        $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);

                        $aws_client = SnsClient::factory(array(
                                    'credentials' => $aws_credentials,
                                    'region' => 'us-west-2',
                                    'version' => 'latest'
                        ));

                        if ($device_type == 'IOS')
                            $aws_platformarn = AWS_CUST_IOS_PLATFORM_ARN;
                        else
                            $aws_platformarn = AWS_CUST_ANDROID_PLATFORM_ARN;

                        $aws_result = $aws_client->createPlatformEndpoint([
                            'CustomUserData' => base64_encode($this->aes256cbc_crypt($customer_id, 'e', AES256CBC_API_PASS)),
                            'PlatformApplicationArn' => $aws_platformarn,
                            'Token' => $device_token,
                        ]);

                        $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $aws_result['EndpointArn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:custschedpush',
                        ]);

                        $endpoint_arn = $aws_result['EndpointArn'];
                    } catch (exception $e) {
                        
                    }
                }

                if (!$device_exists[0]['endpoint_arn']) {
                    Yii::app()->db->createCommand("UPDATE customer_devices SET customer_id=:customer_id, device_token=:device_token, device_name=:device_name, os_details=:os_details, device_type=:device_type, endpoint_arn=:endpoint_arn, last_used='" . date("Y-m-d H:i:s") . "' WHERE device_id = :device_id")
                            ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
                            ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
                            ->bindValue(':device_name', $device_name, PDO::PARAM_STR)
                            ->bindValue(':os_details', $os_details, PDO::PARAM_STR)
                            ->bindValue(':device_type', $device_type, PDO::PARAM_STR)
                            ->bindValue(':device_id', $device_id, PDO::PARAM_STR)
                            ->bindValue(':endpoint_arn', $endpoint_arn, PDO::PARAM_STR)
                            ->execute();
                } else {
                    Yii::app()->db->createCommand("UPDATE customer_devices SET customer_id=:customer_id, device_token=:device_token, device_name=:device_name, os_details=:os_details, device_type=:device_type, last_used='" . date("Y-m-d H:i:s") . "' WHERE device_id = :device_id")
                            ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
                            ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
                            ->bindValue(':device_name', $device_name, PDO::PARAM_STR)
                            ->bindValue(':os_details', $os_details, PDO::PARAM_STR)
                            ->bindValue(':device_type', $device_type, PDO::PARAM_STR)
                            ->bindValue(':device_id', $device_id, PDO::PARAM_STR)
                            ->execute();
                }

                $result = 'true';
                $response = 'device updated';
            } else {
                try {
                    $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);

                    $aws_client = SnsClient::factory(array(
                                'credentials' => $aws_credentials,
                                'region' => 'us-west-2',
                                'version' => 'latest'
                    ));

                    if ($device_type == 'IOS')
                        $aws_platformarn = AWS_CUST_IOS_PLATFORM_ARN;
                    else
                        $aws_platformarn = AWS_CUST_ANDROID_PLATFORM_ARN;

                    $aws_result = $aws_client->createPlatformEndpoint([
                        'CustomUserData' => base64_encode($this->aes256cbc_crypt($customer_id, 'e', AES256CBC_API_PASS)),
                        'PlatformApplicationArn' => $aws_platformarn,
                        'Token' => $device_token,
                    ]);

                    $aws_subscribe_result = $aws_client->subscribe([
                        'Endpoint' => $aws_result['EndpointArn'],
                        'Protocol' => 'application',
                        'ReturnSubscriptionArn' => true,
                        'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:custschedpush',
                    ]);

                    $endpoint_arn = $aws_result['EndpointArn'];
                } catch (exception $e) {
                    
                }

                $data = array('customer_id' => $customer_id, 'device_name' => $device_name, 'device_id' => $device_id, 'device_token' => $device_token, 'os_details' => $os_details, 'device_type' => $device_type, 'device_add_date' => date("Y-m-d H:i:s"), 'last_used' => date("Y-m-d H:i:s"), 'endpoint_arn' => $endpoint_arn);

                Yii::app()->db->createCommand()->insert('customer_devices', $data);
                $result = 'true';
                $response = 'device added';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionrealsaletest() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $request_data = ['merchantAccountId' => 'luciano_olguin_instant_hb7gx3r6', 'serviceFeeAmount' => 0.03, 'amount' => 1.00, 'paymentMethodToken' => 'ff6dx9r', 'options' => ['submitForSettlement' => True]];

        $Bresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);


        //$request_data = ['amount' => 0.01,'paymentMethodToken' => 'ff6dx9r','options' => ['submitForSettlement' => True]];
        //$Bresult = Yii::app()->braintree->sale_real($request_data);
        print_r($Bresult);
    }

    public function actionAddUpdateReview() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $result = 'false';
        $response = 'All fields required';
        $action = Yii::app()->request->getParam('action');


        if ($action == 'add') {
            $review = array();
            $source = Yii::app()->request->getParam('target');
            $filename = Yii::app()->request->getParam('filename');
            $cust_review = Yii::app()->request->getParam('cust_review');
            if ((isset($cust_review) && !empty($cust_review)) && (!empty($filename)) && (!empty($source))) {
                $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/upload_reviews/';
                copy($source . $filename, $target_dir . $filename);
                unlink($source . $filename);
                try {

                    $resIns = Yii::app()->db->createCommand()
                            ->insert('customer_reviews', array(
                        'cust_review' => $cust_review,
                        'cust_img' => $filename
                    ));
                } catch (Exception $e) {
                    //echo $e;
                }
                //var_dump($resIns);
                if ($resIns) {
                    $result = 'true';
                    $response = 'Review added successfully';
                } else {
                    $response = 'Internal error. Please try again.';
                }
            } else {
                unlink($source . $filename);
            }
            $json = array(
                'result' => $result,
                'response' => $response,
            );
            echo json_encode($json);
        } else if ($action == 'edit') {
            $result = 'false';
            $response = 'All fields are required';
            $reviews = array();
            $review_id = Yii::app()->request->getParam('review_id');
            $cust_review = Yii::app()->request->getParam('cust_review');
            $cust_img = Yii::app()->request->getParam('old_img');

            if (!empty($review_id) && !empty($cust_review)) {
                $result = 'true';
                $response = 'Record updated successfully.';
                if (!empty(Yii::app()->request->getParam('target')) && !empty(Yii::app()->request->getParam('filename'))) {
                    $source = Yii::app()->request->getParam('target');
                    $filename = Yii::app()->request->getParam('filename');
                    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/upload_reviews/';
                    copy($source . $filename, $target_dir . $filename);
                    unlink($source . $filename);
                    $cust_img = $filename;
                }


                $update = CustomersReviews::model()->updateByPk($review_id, array('cust_review' => $cust_review, 'cust_img' => $cust_img));

                $review_obj = CustomersReviews::model()->findByAttributes(array('id' => $review_id));
                $reviews[] = array(
                    'id' => $review_obj->id,
                    'cust_id' => $review_obj->customer_id,
                    'cust_review' => $review_obj->cust_review,
                    'cust_img' => $review_obj->cust_img,
                    'created_date' => $review_obj->created_date,
                    'modified_date' => $review_obj->modified_date,
                );
            } else {
                $review_obj = CustomersReviews::model()->findByAttributes(array('id' => $review_id));
                $reviews[] = array(
                    'id' => $review_obj->id,
                    'cust_id' => $review_obj->customer_id,
                    'cust_review' => $review_obj->cust_review,
                    'created_date' => $review_obj->created_date,
                    'modified_date' => $review_obj->modified_date,
                );
            }
            $json = array(
                'result' => $result,
                'response' => $response,
                'reviews' => $reviews
            );
            echo json_encode($json);
        } else {
            $reviews = array();
            $review_id = Yii::app()->request->getParam('id');
            if ((isset($review_id) && !empty($review_id))) {
                $review_id_check = CustomersReviews::model()->findByAttributes(array('id' => $review_id));
                if (!count($review_id_check)) {
                    $result = 'false';
                    $response = 'Invalid review id';
                } else {
                    $result = 'true';
                    $response = 'valid review id';
                    $review_obj = CustomersReviews::model()->findByAttributes(array('id' => $review_id));
                    $reviews[] = array(
                        'id' => $review_obj->id,
                        'cust_id' => $review_obj->customer_id,
                        'cust_review' => $review_obj->cust_review,
                        'cust_img' => $review_obj->cust_img,
                        'created_date' => $review_obj->created_date,
                        'modified_date' => $review_obj->modified_date,
                    );
                }
            } else {
                $result = 'false';
                $response = 'Invalid review id';
            }
            $json = array(
                'result' => $result,
                'response' => $response,
                'reviews' => $reviews
            );
            echo json_encode($json);
        }
    }

    public function actionReview() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'empty';
        $all_review = array();
        $review_exists = Yii::app()->db->createCommand("SELECT id,customer_id,cust_review,created_date,modified_date FROM customer_reviews ORDER BY created_date DESC")->queryAll();
        if (count($review_exists) > 0) {
            $result = 'true';
            $response = 'all reviews';
            foreach ($review_exists as $ind => $review) {
                $all_review[] = array(
                    "id" => $review['id'],
                    "cust_review" => $review['cust_review'],
                    "created_date" => date('Y-m-d', strtotime($review['created_date'])),
                    "modified_date" => date('Y-m-d', strtotime($review['modified_date']))
                );
            }

            $json = array(
                'result' => $result,
                'response' => $response,
                'reviews' => $all_review
            );
            echo json_encode($json, JSON_PRETTY_PRINT);

            exit;
        } else {
            $json = array(
                'result' => $result,
                'response' => $response,
                'reviews' => $all_review
            );
            echo json_encode($json, JSON_PRETTY_PRINT);

            exit;
        }
    }

    public function Actionupdate_is_first_view() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'empty';
        $id = Yii::app()->request->getParam('customerid');
        $update = Customers::model()->updateByPk($id, array('is_schedule_popup_shown' => 1));
        if ($update) {
            $result = 'true';
            $response = 'updated';
        }
        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json, JSON_PRETTY_PRINT);

        exit;
    }

    /*
     * * Get Client Address
     * * Post Required: customer_id
     * * Url:- http://www.demo.com/projects/index.php?r=agents/getagentlocations
     * * Purpose:- Customer Address retrieve
     */

    public function actiongetcustomerAddress() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if (isset($customer_id) && !empty($customer_id)) {
            $customer_id = Yii::app()->request->getParam('customer_id');
            $model = Customers::model()->findByAttributes(array('id' => $customer_id));

            if (!count($model)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else {
                $CustomerLocation = CustomerLocation::model()->findByAttributes(array('customer_id' => $customer_id));
                if (count($CustomerLocation)) {
                    /* $latitude =  $CustomerLocation->latitude;
                      $longitude =  $CustomerLocation->longitude; */
                    $address = $CustomerLocation->location_address;
                    $result = 'true';
                    $response = 'Customer Address';
                } else {
                    $result = 'false';
                    $response = 'No customer address found';
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }
        if (count($CustomerLocation)) {
            $json = array(
                'result' => $result,
                'response' => $response,
                'address' => $address
            );
        } else {
            $json = array(
                'result' => $result,
                'response' => $response,
            );
        }

        echo json_encode($json);
    }

    // new customer for month
    public function ActioncustomerDaywise() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $type = Yii::app()->request->getParam('type');
        $from_date = Yii::app()->request->getParam('from_date');
        $to_date = Yii::app()->request->getParam('to_date');
        /* echo $type.':'.$from_date.":".$to_date; */

        $array = array();
        if (!empty($type) && !empty($from_date) && !empty($to_date)) {
            $start = $end = '';
            if ($type == 'monthly') {
                $startTime = mktime(0, 0, 0, date('m', strtotime($from_date)), date('d', strtotime($from_date)), date('Y', strtotime($from_date)));

                $endTime = mktime(0, 0, 0, date('m', strtotime($to_date)), date('d', strtotime($to_date)), date('Y', strtotime($to_date)));


                // now some output...

                $monthStart = date('Y-m-d', $startTime) . ' ' . '00:00:00';
                $monthEnd = date('Y-m-d', $endTime) . ' ' . '23:59:59';
                /* echo date('mm',$endTime); */
                $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt, created_date FROM `customers` WHERE DATE_FORMAT( created_date, '%Y-%m-%d/' ) BETWEEN '$monthStart' AND '$monthEnd' GROUP BY DATE_FORMAT( created_date, '%Y-%m-%d/' )")->queryAll();

                foreach ($request as $details) {
                    $array[] = array(
                        'x' => date('Y, m, d', strtotime($details['created_date'])),
                        'y' => $details['cnt']
                    );
                }
            }
        } else {

            $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            ;

            //$start_date =  date("Y-m-d", strtotime($i." days ago")).' '.'00:00:00';
            //	$end_date =  date("Y-m-d", strtotime($i." days ago")).' '.'23:59:59';
            // loop through the current and last four month
            //for($i = 6; $i >=0; $i--){
            // calculate the first day of the month
            $first = mktime(0, 0, 0, date('m', $start) - $i, 1, date('Y', $start));

            // calculate the last day of the month
            $last = mktime(0, 0, 0, date('m') - $i + 1, 0, date('Y', $start));

            // now some output...
            $Day = date('d', $first);
            $irstdate = date('Y-m-d', $first) . ' ' . '00:00:00';
            $lastdate = date('Y-m-d', $last) . ' ' . '23:59:59';

            /* echo $firstDay =  date('d',$first);
              echo '::'.$lastDay = date('d',$last);
              echo "SELECT COUNT(*) as cnt, created_date FROM `customers` WHERE DATE_FORMAT( created_date, '%Y-%m-%d/' ) BETWEEN '$irstdate' AND '$lastdate' GROUP BY DATE_FORMAT( created_date, '%Y-%m-%d/' )"; */

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt, created_date FROM `customers` WHERE DATE_FORMAT( created_date, '%Y-%m-%d/' ) BETWEEN '$irstdate' AND '$lastdate' GROUP BY DATE_FORMAT( created_date, '%Y-%m-%d/' )")->queryAll();

            foreach ($request as $details) {
                $array[] = array(
                    'date' => date('Y-m-d', strtotime($details['created_date'])),
                    'value' => $details['cnt']
                );
            }
        }


        $json = array('data' => $array);
        echo json_encode($json);
        die();
    }

    // end

    public function actioncustomer3hrfeedback() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        /* if (!$token_check) {
          $json = array(
          'result' => 'false',
          'response' => 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');
        $ratings = '';
        if (Yii::app()->request->getParam('ratings'))
            $ratings = Yii::app()->request->getParam('ratings');
        $fb_id = '';
        $fb_id = Yii::app()->request->getParam('fb_id');


        $json = array();

        $result = 'false';
        $response = 'Pass the required parameters';

        if (isset($wash_request_id) && !empty($wash_request_id)) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

            if (!count($washrequest_id_check)) {
                $response = 'Invalid wash request id';
            } else {

                $cust_feedback_check = Yii::app()->db->createCommand("SELECT * FROM `washing_feedbacks` WHERE `wash_request_id` = :wash_request_id")
                        ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                        ->queryAll();

                $customers_id_check = Customers::model()->findByAttributes(array("id" => $washrequest_id_check->customer_id));
                $agent_id_check = Agents::model()->findByAttributes(array("id" => $washrequest_id_check->agent_id));

                if (count($cust_feedback_check)) {
                    Yii::app()->db->createCommand("UPDATE washing_feedbacks SET customer_comments=:comments WHERE wash_request_id = :wash_request_id ")
                            ->bindValue(':comments', $comments, PDO::PARAM_STR)
                            ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                            ->execute();
                    if (is_numeric($cust_feedback_check[0]['customer_ratings']))
                        $logcomment = $comments . " (Ratings: " . $cust_feedback_check[0]['customer_ratings'] . ")";
                    else
                        $logcomment = $comments;
                    $washeractionlogdata = array(
                        'agent_id' => $washrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'action' => 'customerfeedback',
                        'addi_detail' => $logcomment,
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
		    
		    $feed_data = array(
                        'agent_id' => $washrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
			'customer_id' => $washrequest_id_check->customer_id,
                        'comments' => $comments,
			'ratings' => $cust_feedback_check[0]['customer_ratings'],
			'social_id' => '',
                        'created_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('mobilewasher_service_feedbacks', $feed_data);
                }

                $result = 'true';
                $response = "Feeback added";

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

                $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer 3 Hour Feedback</h2>
<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000" . $wash_request_id . "</p>
<p><b>Customer Name:</b> " . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</p>
<p><b>Customer Email:</b> " . $customers_id_check->email . "</p>
<p><b>Customer Phone:</b> " . $customers_id_check->contact_number . "</p>
<p><b>Washer Name & Badge:</b> " . $agent_id_check->first_name . " " . $agent_id_check->last_name . " (#" . $agent_id_check->real_washer_id . ")</p>
<p><b>Comments:</b> " . $comments . "</p>";

                $to = Vargas::Obj()->getAdminToEmail();
                $from = Vargas::Obj()->getAdminFromEmail();

                if (APP_ENV == 'real')
                    Vargas::Obj()->SendMail('feedback@mobilewash.com', $from, $message, "Customer 3 Hour Feedback - Order #0000" . $wash_request_id, 'mail-receipt');
                else
                    Vargas::Obj()->SendMail('mobilewash8@gmail.com', $from, $message, "Customer 3 Hour Feedback - Order #0000" . $wash_request_id, 'mail-receipt');

                if (((APP_ENV == 'real') || (APP_ENV == '')) && (!$customers_id_check->block_client) && ($customers_id_check->sms_control)) {
                    $this->layout = "xmlLayout";

                    //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                    $account_sid = TWILIO_SID;
                    $auth_token = TWILIO_AUTH_TOKEN;
                    $client = new Services_Twilio($account_sid, $auth_token);

                    $message = "Thank you for entering to win a FREE Deluxe Wash from MobileWash! We'll notify you if you've been selected as the winner! Message and data rates may apply.";

                    try {
                        $sendmessage = $client->account->messages->create(array(
                            'To' => $customers_id_check->contact_number,
                            'From' => '+13108890719',
                            'Body' => $message,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function Actionget3hourfeedbacks() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $feedbacks = Yii::app()->db->createCommand("SELECT mobilewasher_service_feedbacks.*,agents.agentname,customer_locations.*,customers.customername,customers.email,customers.contact_number FROM mobilewasher_service_feedbacks Left JOIN customers ON mobilewasher_service_feedbacks.customer_id=customers.id JOIN customer_locations ON customer_locations.customer_id=customers.id JOIN agents ON mobilewasher_service_feedbacks.agent_id=agents.id WHERE customer_locations.location_title='Home' ")->queryAll();

//        $i = 0;
//        foreach ($feedbacks as $feedback) {
//            $i++;
//
//            $json = array();
//            $json['id'] = $feedback['id'];
//            $json['washingid'] = $feedback['wash_request_id'];
//            $json['customer_comments'] = $feedback['comments'];
//            $json['customername'] = $feedback['customername'];
//            $json['location_address'] = $feedback['location_address'];
//            $json['city'] = $feedback['city'];
//            $json['state'] = $feedback['state'];
//            $json['zipcode'] = $feedback['zipcode'];
//            $json['email'] = $feedback['email'];
//            $json['contact_number'] = $feedback['contact_number'];
//            $json['customer_comments'] = $feedback['comments'];
//            $json['customer_ratings'] = $feedback['ratings'];
//            $json['customer_id'] = $feedback['customer_id'];
//            $json['agent_id'] = $feedback['agent_id'];
//            $json['customer_social_id'] = $feedback['social_id'];
//            $feedview[] = $json;
//        }

        $feedbackadmin['order'] = $feedbacks;

        echo json_encode($feedbackadmin, JSON_PRETTY_PRINT);
        exit;
    }

    public function actionaddcustomerw() {

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $washeractionlogdata = array(
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email22'],
            'phone' => $_POST['phone-number'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zipcode' => $_POST['pre_zipcode'],
            'register_date' => date('Y-m-d H:i:s'),
            'trash_status' => 0
        );

        Yii::app()->db->createCommand()->insert('pre_registered_clients', $washeractionlogdata);
        echo 'Inserted Data';
        $from = Vargas::Obj()->getAdminFromEmail();

        $message2 = "<p>First Name: " . $_POST['first_name'] . "</p>";
        $message2 .= "<p>Last Name: " . $_POST['last_name'] . "</p>";
        $message2 .= "<p>Email: " . $_POST['email22'] . "</p>";
        $message2 .= "<p>Phone: " . $_POST['phone-number'] . "</p>";
        $message2 .= "<p>City: " . $_POST['city'] . "</p>";
        $message2 .= "<p>State: " . $_POST['state'] . "</p>";
        $message2 .= "<p>Zipcode: " . $_POST['pre_zipcode'] . "</p>";

        $to = Vargas::Obj()->getAdminToEmail();

        Vargas::Obj()->SendMail($to, $from, $message2, "New Customer Pre-Registration");
    }

    public function actionsubscribecustdevicetosns() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $limit = 100;
        if (Yii::app()->request->getParam('limit'))
            $limit = Yii::app()->request->getParam('limit');
        $page = 1;
        if (Yii::app()->request->getParam('page'))
            $page = Yii::app()->request->getParam('page');
        $total_entries = 0;
        $total_pages = 0;

        $json = array();

        $all_wash_requests_count = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM customer_devices order by id asc")
                ->queryAll();
        $total_entries = $all_wash_requests_count[0]['count'];

        if ($total_entries) {
            $total_pages = ceil($total_entries / $limit);
        }
        echo "total page: " . $total_pages . "<br>";

        $all_devices = Yii::app()->db->createCommand()
                ->select('*')
                ->from('customer_devices')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->order(array('id asc'))
                ->queryAll();

        if (count($all_devices)) {

            $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);

            $aws_client = SnsClient::factory(array(
                        'credentials' => $aws_credentials,
                        'region' => 'us-west-2',
                        'version' => 'latest'
            ));

            foreach ($all_devices as $index => $device) {
                $endpoint_arn = '';
                $subscribe_arn = '';
                //echo $device['id'];
                if ($device['device_type'] == 'IOS')
                    $aws_platformarn = AWS_CUST_IOS_PLATFORM_ARN;
                else
                    $aws_platformarn = AWS_CUST_ANDROID_PLATFORM_ARN;
                if (trim($device['device_token'])) {
                    try {
                        $aws_result = $aws_client->createPlatformEndpoint([
                            'CustomUserData' => base64_encode($this->aes256cbc_crypt($device['customer_id'], 'e', AES256CBC_API_PASS)),
                            'PlatformApplicationArn' => $aws_platformarn,
                            'Token' => $device['device_token'],
                        ]);
                        if ($aws_result['EndpointArn'])
                            $endpoint_arn = $aws_result['EndpointArn'];
                    } catch (exception $e) {
                        //echo $e->getAwsErrorMessage();
                        /* switch ($e->getAwsErrorCode()) {
                          case 'EndpointDisabled':
                          case 'InvalidParameter':
                          echo "invalid parameter";
                          continue;
                          case 'NotFound':
                          /// do something
                          break;
                          } */
                    }
                }

                if ($aws_result['EndpointArn']) {
                    try {
                        $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $aws_result['EndpointArn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:custschedpush',
                        ]);
                        if ($aws_subscribe_result['SubscriptionArn'])
                            $subscribe_arn = $aws_subscribe_result['SubscriptionArn'];
                    } catch (exception $e) {
                        //echo $e->getAwsErrorMessage();
                        /* switch ($e->getAwsErrorCode()) {
                          case 'EndpointDisabled':
                          case 'InvalidParameter':
                          case 'NotFound':
                          /// do something
                          break;
                          } */
                    }



                    Yii::app()->db->createCommand("UPDATE customer_devices SET endpoint_arn=:endpoint_arn WHERE id = :id")
                            ->bindValue(':id', $device['id'], PDO::PARAM_STR)
                            ->bindValue(':endpoint_arn', $aws_result['EndpointArn'], PDO::PARAM_STR)
                            ->execute();
                }


                echo "id: " . $device['id'] . " token: " . $device['device_token'] . " cust id: " . $device['customer_id'] . " end arn: " . $endpoint_arn . " subsc arn: " . $subscribe_arn;
                echo "<br>";
            }
        }
    }

    public function actionupdatecustomerspecnotifiations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $notify_cat = Yii::app()->request->getParam('notify_cat');
        $sms_text = '';
        $sms_text = Yii::app()->request->getParam('sms_text');
        $notify_text = '';
        $notify_text = Yii::app()->request->getParam('notify_text');
        $email_img_url = '';
        $email_img_url = Yii::app()->request->getParam('email_img_url');
        $notify_trigger_time = '';
        $notify_trigger_time = Yii::app()->request->getParam('notify_trigger_time');

        $json = array();

        $result = 'false';
        $response = 'Pass the required parameters';

        if (isset($notify_cat) && !empty($notify_cat)) {
            $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                    ->bindValue(':notify_cat', $notify_cat, PDO::PARAM_STR)
                    ->queryAll();

            if (!count($notify_check)) {
                $response = "Notification doesn't exists";
            } else {

                if (!$email_img_url) {
                    $email_img_url = $notify_check[0]['email_image_url'];
                }

                Yii::app()->db->createCommand("UPDATE customer_spec_notifications SET notify_trigger_time=:notify_trigger_time, notify_text=:notify_text, sms_text = :sms_text, email_image_url = :email_img_url, last_updated_at = '" . date('Y-m-d H:i:s') . "' WHERE notify_cat = :notify_cat ")
                        ->bindValue(':notify_trigger_time', $notify_trigger_time, PDO::PARAM_STR)
                        ->bindValue(':notify_text', $notify_text, PDO::PARAM_STR)
                        ->bindValue(':sms_text', $sms_text, PDO::PARAM_STR)
                        ->bindValue(':email_img_url', $email_img_url, PDO::PARAM_STR)
                        ->bindValue(':notify_cat', $notify_cat, PDO::PARAM_STR)
                        ->execute();

                $result = 'true';
                $response = "Notification updated successfully";
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function Actiongetcustomerspecnotifiations() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $spec_notifications = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications")->queryAll();

        foreach ($spec_notifications as $notify) {
            $notify_array[$notify['notify_cat']] = array('id' => $notify['id'], 'notify_trigger_time' => $notify['notify_trigger_time'], 'sms_text' => $notify['sms_text'], 'notify_text' => $notify['notify_text'], 'email_image_url' => $notify['email_image_url'], 'last_updated_at' => $notify['last_updated_at']);
        }

        $json = array(
            'result' => 'true',
            'response' => 'all notifications',
            'spec_notifications' => $notify_array
        );

        echo json_encode($json);
        die();
    }

    public function actionCustomerExpansionRequestList() {
        $result = Yii::app()->db->createCommand('SELECT pre_registered_clients.* FROM pre_registered_clients ORDER BY id DESC')->queryAll();
        $json = array(
            'result' => 'true',
            'data' => $result
        );
        echo json_encode($json);
        die();
    }

    public function actionCustomerExpansionRequestSave() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $customerid = Yii::app()->request->getParam('customer_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $customerid = $this->aes256cbc_crypt($customerid, 'd', AES256CBC_API_PASS);
        }
        $country = Yii::app()->request->getParam('country');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $CustomerExpansionRequestExist = CustomerExpansionRequest::model()->findByAttributes(array('customer_id' => $customerid, 'state' => $state));
        if ($CustomerExpansionRequestExist) {
            $response['status'] = '0';
            $response['message'] = 'Customer already exist.';
            echo json_encode($response);
            die;
        }
        if ($customerid == "") {
            $response['status'] = '0';
            $response['message'] = 'Please enter customer id';
            echo json_encode($response);
            die;
        }
        if ($country == "") {
            $response['status'] = '0';
            $response['message'] = 'Please enter country ';
            echo json_encode($response);
            die;
        }
        if ($state == "") {
            $response['status'] = '0';
            $response['message'] = 'Please enter state ';
            echo json_encode($response);
            die;
        }
        if ($city == "") {
            $response['status'] = '0';
            $response['message'] = 'Please enter city';
            echo json_encode($response);
            die;
        }
        $customer = new CustomerExpansionRequest;
        $customer->customer_id = $customerid;
        $customer->country = $country;
        $customer->city = $city;
        $customer->zipcode = $zipcode;
        $customer->state = $state;
        if ($customer->save()) {
            $data['status'] = "1";
            $data['message'] = "Data saved Successfully.";
            echo json_encode($data);
            die;
        } else {
            $data['status'] = "0";
            $data['message'] = "something went wrong";
            echo json_encode($data);
            die;
        }
    }

    public function actionsendnonreturnemails() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        /* $json_str = file_get_contents('php://input');

          # Get as an object
          $json_obj = json_decode($json_str);
          $url = $json_obj->SubscribeURL;
          mail("nazmur_r@yahoo.com", "test", $url);

          exit; */

        $clientlist = Customers::model()->findAllByAttributes(array('is_non_returning' => 1, 'nonreturn_email_delivery_pending' => 1), array('order' => 'id DESC', 'limit' => 50));


        if (count($clientlist)) {
            $subject = '';
            $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);
            $sender_email = 'MobileWash <admin@mobilewash.com>';

            $SesClient = SesClient::factory(array(
                        'credentials' => $aws_credentials,
                        'region' => 'us-west-2',
                        'version' => 'latest'
            ));
            foreach ($clientlist as $client) {

                if ($client->nonreturn_cat == 30) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'non-return-31st-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "It's Time for a Shine! Treat Yourself!";
                }

                if ($client->nonreturn_cat == 60) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'non-return-61st-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "It's been a while!";
                }

                if ($client->nonreturn_cat == 90) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'non-return-90th-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "You've missed a lot in the last few months!";
                }


                $recipient_emails = array();
                array_push($recipient_emails, $client->email);

// Specify a configuration set. If you do not want to use a configuration
// set, comment the following variable, and the
// 'ConfigurationSetName' => $configuration_set argument below.
                $configuration_set = 'test';

                $plaintext_body = 'MobileWash';
                $html_body = "<html>
<head></head>
<body style='margin: 0; padding: 0;'>
<div style='/*background: #c6c6c6;*/ width: 100%; height: 100%; /*padding-top: 50px;*/'>
<div style='width: 650px; background: #fff; margin: 0 auto;'>
<div style='padding: 20px; text-align: center;'>
<p style='margin: 0;'><a href='https://www.mobilewash.com'><img src='https://www.mobilewash.com/images/drop_on_top_logo2.png' width='360' /></a></p>
<div style='margin-top: 20px;'>
                <a href='https://www.facebook.com/getmobilewash/'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/fb.png' alt=''></a>
               <a href='https://twitter.com/getmobilewash'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/tw.png' alt=''></a>
               <a href='https://plus.google.com/114985712775567009759/about'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/gp.png' alt=''></a>
                <a href='https://www.instagram.com/getmobilewash/'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/ins.png' alt=''></a>
                </div>
                <div style='clear: both;'></div>
                </div>
                <div style='background: #fff; padding: 20px; font-size: 16px; font-family: arial, sans-serif; line-height: 26px;'>";
                $html_body .= "<img src='" . ROOT_URL . "/admin-new/images/cust-spec-notify-img/" . $notify_check[0]['email_image_url'] . "' />";
                $html_body .= "</div>
</div>
<p style='text-align: center; font-size: 16px; font-family: arial, sans-serif; line-height: 20px; margin: 12px auto; padding-bottom: 25px; margin-top: 20px;'>Thank you for choosing MobileWash!</p>

<p style='text-align: center; font-size: 14px; font-family: arial, sans-serif; line-height: 20px; max-width: 480px; margin: 12px auto;'>&copy; " . date("Y") . " MobileWash, Inc. All rights reserved. All trademarks referenced herein are the property of their respective owners.</p>
</div>
</body>
</html>";
                $char_set = 'UTF-8';

                try {
                    $result = $SesClient->sendEmail([
                        'Destination' => [
                            'ToAddresses' => $recipient_emails,
                        ],
                        'ReplyToAddresses' => [$sender_email],
                        'Source' => $sender_email,
                        'Message' => [
                            'Body' => [
                                'Html' => [
                                    'Charset' => $char_set,
                                    'Data' => $html_body,
                                ],
                            /* 'Text' => [
                              'Charset' => $char_set,
                              'Data' => $plaintext_body,
                              ], */
                            ],
                            'Subject' => [
                                'Charset' => $char_set,
                                'Data' => $subject,
                            ],
                        ],
                        // If you aren't using a configuration set, comment or delete the
                        // following line
                        'ConfigurationSetName' => $configuration_set,
                    ]);
                    $messageId = $result['MessageId'];
                    //echo $client->id." msg id: ".$messageId."<br>";
                    Customers::model()->updateByPk($client->id, array('nonreturn_email_delivery_pending' => 0));
                } catch (AwsException $e) {
                    //echo $client->id."<br>";
                    //echo $e->getMessage();
                    //echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                    //echo "\n";
                }
            }
        }
    }

    public function actionsendinactivecustemails() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        /* $json_str = file_get_contents('php://input');

          # Get as an object
          $json_obj = json_decode($json_str);
          $url = $json_obj->SubscribeURL;
          mail("nazmur_r@yahoo.com", "test", $url);

          exit; */

        $clientlist = Customers::model()->findAllByAttributes(array('is_inactive' => 1, 'inactive_email_delivery_pending' => 1), array('order' => 'id DESC', 'limit' => 50));

        if (count($clientlist)) {
            $subject = '';
            $aws_credentials = new Credentials(AWS_ACCESS_KEY, AWS_SECRET_KEY);
            $sender_email = 'MobileWash <admin@mobilewash.com>';

            $SesClient = SesClient::factory(array(
                        'credentials' => $aws_credentials,
                        'region' => 'us-west-2',
                        'version' => 'latest'
            ));
            foreach ($clientlist as $client) {

                if (!$client->email)
                    continue;
                //echo $client->id."<br>";

                if ($client->inactive_cat == 5) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'inactive-6th-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "Forget Something?";
                }

                if ($client->inactive_cat == 10) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'inactive-11th-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "Go ahead. \"Tap\" that app.";
                }

                if ($client->inactive_cat == 30) {
                    $notify_check = Yii::app()->db->createCommand("SELECT * FROM customer_spec_notifications WHERE notify_cat = :notify_cat")
                            ->bindValue(':notify_cat', 'inactive-31st-day', PDO::PARAM_STR)
                            ->queryAll();
                    $subject = "There's Still Time!";
                }


                $recipient_emails = array();
                array_push($recipient_emails, $client->email);

// Specify a configuration set. If you do not want to use a configuration
// set, comment the following variable, and the
// 'ConfigurationSetName' => $configuration_set argument below.
                $configuration_set = 'inactivecustemail';

                $plaintext_body = 'MobileWash';
                $html_body = "<html>
<head></head>
<body style='margin: 0; padding: 0;'>
<div style='/*background: #c6c6c6;*/ width: 100%; height: 100%; /*padding-top: 50px;*/'>
<div style='width: 650px; background: #fff; margin: 0 auto;'>
<div style='padding: 20px; text-align: center;'>
<p style='margin: 0;'><a href='https://www.mobilewash.com'><img src='https://www.mobilewash.com/images/drop_on_top_logo2.png' width='360' /></a></p>
<div style='margin-top: 20px;'>
                <a href='https://www.facebook.com/getmobilewash/'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/fb.png' alt=''></a>
               <a href='https://twitter.com/getmobilewash'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/tw.png' alt=''></a>
               <a href='https://plus.google.com/114985712775567009759/about'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/gp.png' alt=''></a>
                <a href='https://www.instagram.com/getmobilewash/'><img style='margin-left: 2px;' src='https://www.mobilewash.com/images/ins.png' alt=''></a>
                </div>
                <div style='clear: both;'></div>
                </div>
                <div style='background: #fff; padding: 20px; font-size: 16px; font-family: arial, sans-serif; line-height: 26px;'>";
                $html_body .= "<img src='" . ROOT_URL . "/admin-new/images/cust-spec-notify-img/" . $notify_check[0]['email_image_url'] . "' />";
                $html_body .= "</div>
</div>
<p style='text-align: center; font-size: 16px; font-family: arial, sans-serif; line-height: 20px; margin: 12px auto; padding-bottom: 25px; margin-top: 20px;'>Thank you for choosing MobileWash!</p>

<p style='text-align: center; font-size: 14px; font-family: arial, sans-serif; line-height: 20px; max-width: 480px; margin: 12px auto;'>&copy; " . date("Y") . " MobileWash, Inc. All rights reserved. All trademarks referenced herein are the property of their respective owners.</p>
</div>
</body>
</html>";
                $char_set = 'UTF-8';

                try {
                    $result = $SesClient->sendEmail([
                        'Destination' => [
                            'ToAddresses' => $recipient_emails,
                        ],
                        'ReplyToAddresses' => [$sender_email],
                        'Source' => $sender_email,
                        'Message' => [
                            'Body' => [
                                'Html' => [
                                    'Charset' => $char_set,
                                    'Data' => $html_body,
                                ],
                            /* 'Text' => [
                              'Charset' => $char_set,
                              'Data' => $plaintext_body,
                              ], */
                            ],
                            'Subject' => [
                                'Charset' => $char_set,
                                'Data' => $subject,
                            ],
                        ],
                        // If you aren't using a configuration set, comment or delete the
                        // following line
                        'ConfigurationSetName' => $configuration_set,
                    ]);
                    $messageId = $result['MessageId'];
                    //echo $client->id." msg id: ".$messageId."<br>";
                    Customers::model()->updateByPk($client->id, array('inactive_email_delivery_pending' => 0));
                } catch (AwsException $e) {
                    //echo "clinet id: ".$client->id." ";
                    //echo $e->getMessage();
                    //echo "<br>";
                    //echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                    //echo "\n";
                }
            }
        }
    }

    public function actionnonreturn20daynotify() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        /* if (!$token_check) {
          $json = array(
          'result' => 'false',
          'response' => 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        $agent_id = Yii::app()->request->getParam('agent_id');
        $agent_lat = Yii::app()->request->getParam('agent_lat');
        $agent_lng = Yii::app()->request->getParam('agent_lng');

        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }

        $clientlist = Customers::model()->findAllByAttributes(array('is_non_returning' => 1, 'nonreturn_20day_notify' => 0), array('limit' => 100));
        // print_r($wash_id_check);
        if (count($clientlist)) {
            foreach ($clientlist as $client) {
                $miles = 0;
                $customer_lng = 0;
                $customer_lat = 0;
                $customerlocation = Yii::app()->db->createCommand("SELECT * FROM customer_live_locations WHERE customer_id = '" . $client->id . "'")->queryAll();

                if (count($customerlocation)) {
                    $customer_lng = $customerlocation[0]['longitude'];
                    $customer_lat = $customerlocation[0]['latitude'];
                }

                /* --------- distance calculation ------------ */

                $theta = $customer_lng - $agent_lng;
                $dist = sin(deg2rad($customer_lat)) * sin(deg2rad($agent_lat)) + cos(deg2rad($customer_lat)) * cos(deg2rad($agent_lat)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);

                /* --------- distance calculation end ------------ */

                if (($miles <= 10) && ($miles > 0)) {
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '53' ")->queryAll();
                    $message = $pushmsg[0]['message'];
                    $customerdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $client->id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                    foreach ($customerdevices as $ctdevice) {
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "default";

                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        //print_r($notifyurl);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                    Customers::model()->updateByPk($client->id, array("nonreturn_20day_notify" => 1));
                }
            }
        }
    }

}
