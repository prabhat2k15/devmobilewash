<?php

require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/amazon-sdk/aws-autoloader.php';

use Twilio\Rest\Client;
use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;

class AgentsController extends Controller {

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
     * * Post Required: email, password, device_token, mobile_type
     * * Url:- http://www.demo.com/projects/index.php?r=agents/register
     * * Purpose:- New Agents can register
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

        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $emailid = Yii::app()->request->getParam('email');

        $contact_number = Yii::app()->request->getParam('phone_number');
        $date_of_birth = Yii::app()->request->getParam('date_of_birth');
        $street_address = Yii::app()->request->getParam('street_address');
        $suite_apt = '';
        $suite_apt = Yii::app()->request->getParam('suite_apt');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $driver_license = Yii::app()->request->getParam('driver_license');
        $proof_insurance = Yii::app()->request->getParam('proof_insurance');
        $business_license = Yii::app()->request->getParam('business_license');
        //$bank_account_number = Yii::app()->request->getParam('bank_account_number');
        //$routing_number = Yii::app()->request->getParam('routing_number');
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $operating_as = Yii::app()->request->getParam('operating_as');
        $company_name = Yii::app()->request->getParam('company_name');
        $wash_experience = Yii::app()->request->getParam('wash_experience');
        $date = date('Y-m-d H:i:s');
        $directorypath1 = realpath(Yii::app()->basePath . '/../images/agent_img');
        $SiteUrl = Yii::app()->getBaseUrl(true);
        $json = array();
        $agentid = '';
        if ((isset($first_name) && !empty($first_name)) &&
                (isset($last_name) && !empty($last_name)) &&
                (isset($emailid) && !empty($emailid)) &&
                (isset($contact_number) && !empty($contact_number)) &&
                (isset($date_of_birth) && !empty($date_of_birth)) &&
                (isset($street_address) && !empty($street_address)) &&
                (isset($city) && !empty($city)) &&
                (isset($state) && !empty($state)) &&
                (isset($zipcode) && !empty($zipcode)) &&
                (isset($driver_license) && !empty($driver_license)) &&
                (isset($proof_insurance) && !empty($proof_insurance)) &&
                (isset($business_license) && !empty($business_license)) &&
                (isset($legally_eligible) && !empty($legally_eligible)) &&
                (isset($own_vehicle) && !empty($own_vehicle)) &&
                (isset($waterless_wash_product) && !empty($waterless_wash_product)) &&
                (isset($operate_area) && !empty($operate_area)) &&
                (isset($work_schedule) && !empty($work_schedule)) &&
                (isset($operating_as) && !empty($operating_as)) &&
                (isset($company_name) && !empty($company_name)) &&
                (isset($wash_experience) && !empty($wash_experience))) {
            $agents_email_exists = Agents::model()->findByAttributes(array("email" => $emailid));
            if (count($agents_email_exists) > 0) {
                $result = 'false';
                $response = 'Email already exists.';
                $agentid = $agents_email_exists->id;
                $json = array(
                    'result' => $result,
                    'response' => $response,
                    'agentid' => $agentid
                );
            } else {

                $agent_img = $SiteUrl . '/images/agent_img/no_profile.jpg';
                /*
                  $dl_img = str_replace('data:image/png;base64,', '', $driver_license);
                  $dl_img = str_replace(' ', '+', $dl_img);
                  $dl_data = base64_decode($dl_img);
                  $md5 = md5(uniqid(rand(), true));
                  $name = 'agent_driver_license_'.$md5.".jpg";
                  $path = $directorypath1.'/agent_docs/'.$name;
                 */
                $dl_imagename = $SiteUrl . '/images/agent_img/agent_docs/' . $driver_license;
                $bl_imagename = $SiteUrl . '/images/agent_img/agent_docs/' . $business_license;

                //file_put_contents($path, $dl_data);
                /*
                  $pi_img = str_replace('data:image/png;base64,', '', $proof_insurance);
                  $pi_img = str_replace(' ', '+', $pi_img);
                  $pi_data = base64_decode($pi_img);
                  $md5 = md5(uniqid(rand(), true));
                  $name = 'agent_proof_insurance_'.$md5.".jpg";
                  $path = $directorypath1.'/agent_docs/'.$name;
                 */
                $pi_imagename = $SiteUrl . '/images/agent_img/agent_docs/' . $proof_insurance;

                //file_put_contents($path, $pi_data);

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $emailid,
                    'phone_number' => $contact_number,
                    'date_of_birth' => $date_of_birth,
                    'street_address' => $street_address,
                    'suite_apt' => $suite_apt,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'driver_license' => $dl_imagename,
                    'proof_insurance' => $pi_imagename,
                    'business_license' => $bl_imagename,
                    'legally_eligible' => $legally_eligible,
                    'own_vehicle' => $own_vehicle,
                    'waterless_wash_product' => $waterless_wash_product,
                    'operate_area' => $operate_area,
                    'work_schedule' => $work_schedule,
                    'operating_as' => $operating_as,
                    'company_name' => $company_name,
                    'wash_experience' => $wash_experience,
                    'image' => $agent_img,
                    'account_status' => 0,
                    'created_date' => $date,
                );

                $agentdata = array_filter($agentdata);
                $model = new Agents;
                $model->attributes = $agentdata;
                if ($model->save(false)) {
                    $agentid = Yii::app()->db->getLastInsertID();

                    $result = 'true';
                    $response = 'Agent successfully registered';


                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $emailid,
                        'phone_number' => $contact_number,
                        'date_of_birth' => $date_of_birth,
                        'street_address' => $street_address,
                        'suite_apt' => $suite_apt,
                        'city' => $city,
                        'state' => $state,
                        'zipcode' => $zipcode,
                        'driver_license' => $dl_imagename,
                        'proof_insurance' => $pi_imagename,
                        'business_license' => $bl_imagename,
                        'legally_eligible' => $legally_eligible,
                        'own_vehicle' => $own_vehicle,
                        'waterless_wash_product' => $waterless_wash_product,
                        'operate_area' => $operate_area,
                        'work_schedule' => $work_schedule,
                        'operating_as' => $operating_as,
                        'company_name' => $company_name,
                        'wash_experience' => $wash_experience,
                        'image' => $agent_img,
                        'account_status' => 0,
                        'created_date' => $date,
                    );

                    /* ----- braintree submerchant account creation ----------- */
                    /*
                      $fullname = $first_name." ".$last_name;
                      $merchantAccountParams = [
                      'individual' => [
                      'firstName' => $first_name,
                      'lastName' => $last_name,
                      'email' => $emailid,
                      'phone' => $contact_number,
                      'dateOfBirth' => $date_of_birth,
                      'address' => [
                      'streetAddress' => $street_address,
                      'locality' => $city,
                      'region' => $state,
                      'postalCode' => $zipcode
                      ]
                      ],
                      'funding' => [
                      'descriptor' => $fullname,
                      'destination' => 'bank',
                      'email' => $emailid,
                      'mobilePhone' => $contact_number,
                      'accountNumber' => $bank_account_number,
                      'routingNumber' => $routing_number
                      ],
                      'tosAccepted' => true,
                      'masterMerchantAccountId' => "mobilewashinc"
                      ];

                      $bt_result = Yii::app()->braintree->createSubMerchant($merchantAccountParams);
                      //print_r($bt_result);
                      //exit;
                      if($bt_result['success'] == 1) {
                      $update_status = Agents::model()->updateAll(array('bt_submerchant_id' => $bt_result['sub_merchant_id']),'id=:id',array(':id'=>$agentid));
                      //$result['sub_merchant_id'];
                      }
                     */
                    /* ----- braintree submerchant account creation end ----------- */
                } else {
                    $result = 'false';
                    $response = 'Internal error';
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'agentid' => $agentid
                    );
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response,
                'agentid' => $agentid
            );
        }
        echo json_encode($json);
        die();
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

        if ((isset($emailid) && !empty($emailid)) && (isset($password) && !empty($password))) {
            $agents_id = Agents::model()->findByAttributes(array("email" => $emailid));
            if (count($agents_id) > 0) {
                if ($agents_id->password == $password) {
                    if (!empty($device_token)) {
                        $model = Agents::model()->findByAttributes(array('id' => $agents_id->id));
                        $data = array('device_token' => $device_token, 'mobile_type' => $mobile_type);
                        $data = array_filter($data);
                        $model->attributes = $data;
                        $model->save(false);
                    }
                    $result = 'true';
                    $response = 'Successfully logged in';

                    $online_status = array('status' => 'online');

                    $update_status = Agents::model()->updateAll($online_status, 'id=:id', array(':id' => $agents_id->id));

                    /* ------------- check if agent available for new order ------------- */

                    $isagentbusy = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id='" . $agents_id->id . "' AND (status >= 1 AND status <= 3)")->queryAll();
                    ;
                    if (!count($isagentbusy)) {
                        Agents::model()->updateAll(array('available_for_new_order' => 1), 'id=:id', array(':id' => $agents_id->id));
                    }

                    /* ------------- check if agent available for new order end ------------- */

                    // $wash_count_check = Washingrequests::model()->findAllByAttributes(array("agent_id" => $agents_id->id, "status" => 5));
                    $wash_count = $agents_id->total_wash;
                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agents_id->id));

                    $total_rate = count($agent_feedbacks);
                    if ($total_rate) {
                        $rate = 0;
                        foreach ($agent_feedbacks as $agent_feedback) {
                            $rate += $agent_feedback->agent_ratings;
                        }

                        $agent_rate = round($rate / $total_rate);
                    } else {
                        $agent_rate = 0;
                    }

                    $agent_rate = $agents_id->rating;

                    $agentname = $agents_id->first_name . " " . $agents_id->last_name;
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'agentid' => $agents_id->id,
                        'email' => $agents_id->email,
                        'first_name' => $agents_id->first_name,
                        'last_name' => $agents_id->last_name,
                        'image' => $agents_id->image,
                        'contact_number' => $agents_id->phone_number,
                        'street_address' => $agents_id->street_address,
                        'suite_apt' => $agents_id->suite_apt,
                        'city' => $agents_id->city,
                        'state' => $agents_id->state,
                        'zipcode' => $agents_id->zipcode,
                        'driver_license' => $agents_id->driver_license,
                        'proof_insurance' => $agents_id->proof_insurance,
                        'legally_eligible' => $agents_id->legally_eligible,
                        'own_vehicle' => $agents_id->own_vehicle,
                        'waterless_wash_product' => $agents_id->waterless_wash_product,
                        'operate_area' => $agents_id->operate_area,
                        'work_schedule' => $agents_id->work_schedule,
                        'operating_as' => $agents_id->operating_as,
                        'company_name' => $agents_id->company_name,
                        'wash_experience' => $agents_id->wash_experience,
                        'account_status' => $agents_id->account_status,
                        'created_date' => $agents_id->created_date,
                        'total_washes' => $wash_count,
                        'rating' => $agents_id->rating,
                    );
                } else {
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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $device_token = Yii::app()->request->getParam('device_token');
        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }
        $model = Agents::model()->findByAttributes(array('id' => $agent_id));

        $json = array();
        if (count($model) > 0) {
            $data = array('device_token' => '', 'status' => 'offline', 'available_for_new_order' => 0);
            $model->attributes = $data;
            if ($model->save(false)) {
                $result = 'true';
                $response = 'Successfully logged out';
                $json = array(
                    'result' => $result,
                    'response' => $response
                );

                if (!Yii::app()->request->getParam('go_offline'))
                    Agents::model()->updateByPk($agent_id, array('forced_logout' => 0, 'access_token' => '', 'access_key' => '', 'access_vector' => ''));

                Yii::app()->db->createCommand("UPDATE agent_devices SET device_status='offline' WHERE agent_id = :agent_id AND device_token = :device_token")
                        ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                        ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
                        ->execute();

                Washingrequests::model()->updateAll(array('order_temp_assigned' => 0), 'order_temp_assigned=:agent_id AND status = 0', array(':agent_id' => $agent_id));
            }
        }else {
            $result = 'false';
            $response = 'Not a authorized agent';
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
        $agent_id = Yii::app()->request->getParam('agent_id');
        if (isset($agent_id) && ($agent_id != '')) {

            $model = Agents::model()->findByAttributes(array('id' => $agent_id));
            $json = array();
            if ((count($model) > 0) && ($model->device_token != '')) {
                $result = 'true';
                $response = 'success';
                $agent_name = $model->first_name . " " . $model->last_name;
            } else {
                $result = 'false';
                $response = 'error';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'agent_name' => $agent_name
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
            $agents_email_exists = Agents::model()->findByAttributes(array("email" => $email));
            if (count($agents_email_exists) > 0) {
                $token = md5(time());
                $agents_email_exists->token = $token;
                if ($agents_email_exists->save(false)) {
                    $uniqueMail = $email;
                    $from = Vargas::Obj()->getAdminFromEmail();
                    $subject = 'MobileWash.com - Reset Your Password';
                    $reporttxt = ROOT_URL . '/reset-password.php?action=agrp&token=' . $token . '&id=' . $agents_email_exists->id;
                    $message = "";

                    $message .= "<p style='font-size: 20px;'>Dear " . $agents_email_exists->first_name . ",</p>";
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
            $agents_email_exists = Agents::model()->findByAttributes(array("token" => $token, "id" => $id));
            if (!count($agents_email_exists)) {
                $result = 'false';
                $response = "Sorry can't reset your password. Please check password reset link.";
            } else if (empty($new_password)) {
                $result = 'false';
                $response = "Password can not be empty.";
            } else if ($new_password != $cnfpassword) {
                $result = 'false';
                $response = "New Password and Confirm Password does not match.";
            } else {
                $update_password = Agents::model()->updateAll(array('password' => md5($new_password), 'token' => ''), 'id=:id', array(':id' => $id));
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
        $json = array();
        if (isset($email) && !empty($email)) {
            $agent_email_exists = Agents::model()->findByAttributes(array("email" => $email));
            if (count($agent_email_exists) > 0) {

                if ($agent_email_exists->account_status) {
                    $json = array(
                        'result' => 'true',
                        'response' => 'Your account is already verified',
                    );
                } else {

                    $encriptpassword = md5($agent_email_exists->id);

                    $customername2 = '';
                    $cust_name = explode(" ", trim($agent_email_exists->first_name . " " . $agent_email_exists->last_name));
                    if (count($cust_name > 1))
                        $customername2 = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                    else
                        $customername2 = $cust_name[0];

                    $from = Vargas::Obj()->getAdminFromEmail();
                    //echo $from;
                    $subject = 'Welcome to Mobile Wash! ' . $customername2;
                    //$message = "Hello ".$customername2.",<br/><br/>Welcome to Mobile wash!";

                    $message = "<h1>Hello " . $customername2 . "!</h1>";

                    $message .= "<p style='color: #333;'>Thank you for signing up with <b style='color: #000;'>Mobile Wash.</b></p>
					<p style='color: #333;'>Please click the link below to confirm your email address and activate your account.</p>";



                    $message .= "<a style='background: #076ee1 none repeat scroll 0 0; border: 0 none; border-radius: 5px; color: #fff; cursor: pointer; display: block; font-size: 18px; font-weight: 700;  padding: 12px 0; text-align: center; text-decoration: none; text-transform: uppercase;  width: 283px;' href='" . ROOT_URL . "/email_confirm.php?code=" . $encriptpassword . "&user_type=ag'> ACTIVATE MY ACCOUNT </a>";

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
                    'response' => 'Email does not exist',
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

        $agentID = Yii::app()->request->getParam('code');
        $agentDetail = Yii::app()->db->createCommand("SELECT * FROM agents WHERE md5(id) = :id ")
                ->bindValue(':id', $agentID, PDO::PARAM_STR)
                ->queryAll();

        if (!empty($agentDetail)) {
            $account_status = array('account_status' => 1);
            $update_status = Agents::model()->updateAll($account_status, 'md5(id)=:id', array(':id' => $agentID));
            $result = 'true';
            $response = 'Confirm Your Email address.';
            $json = array(
                'result' => $result,
                'response' => $response,
                'agent_id' => $agentDetail[0]['id']
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

    /*
     * * Agent location update
     * * Post Required: agent_id, latitude, longitude
     * * Url:- http://www.demo.com/projects/index.php?r=agents/updateagentlocations
     * * Purpose:- Agent location update
     */

    public function actionupdateagentlocations() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if ((isset($agent_id) && !empty($agent_id)) && (isset($latitude) && !empty($latitude)) && (isset($longitude) && !empty($longitude))) {
            $agent_id = Yii::app()->request->getParam('agent_id');
            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }
            $model = Agents::model()->findByAttributes(array('id' => $agent_id));

            if (!count($model)) {
                $result = 'false';
                $response = 'Invalid agent id';
            } else {
                $current_date = date("Y-m-d H:i:s");
                $data = array('agent_id' => $agent_id, 'latitude' => $latitude, 'longitude' => $longitude);
                $agentloc = new AgentLocations;
                $agentloc->attributes = $data;
                $checkagent = $agentloc->findByAttributes(array('agent_id' => $agent_id));
                if (!count($checkagent))
                    $resUpdate = $agentloc->save(false);
                else
                    $resUpdate = $agentloc->updateAll($data, 'agent_id=:agent_id', array(':agent_id' => $agent_id));
                Agents::model()->updateAll(array('last_activity' => $current_date), 'id=:id', array(':id' => $agent_id));
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
     * * Get agent location
     * * Post Required: agent_id
     * * Url:- http://www.demo.com/projects/index.php?r=agents/getagentlocations
     * * Purpose:- Agent location retrieve
     */

    public function actiongetagentlocations() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if (isset($agent_id) && !empty($agent_id)) {
            $agent_id = Yii::app()->request->getParam('agent_id');
            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }
            $model = Agents::model()->findByAttributes(array('id' => $agent_id));

            if (!count($model)) {
                $result = 'false';
                $response = 'Invalid agent id';
            } else {
                $agentlocmodel = AgentLocations::model()->findByAttributes(array('agent_id' => $agent_id));
                if (count($agentlocmodel)) {
                    $latitude = $agentlocmodel->latitude;
                    $longitude = $agentlocmodel->longitude;
                    $result = 'true';
                    $response = 'Agent Location';
                } else {
                    $result = 'false';
                    $response = 'No agent location found';
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }
        if (count($agentlocmodel)) {
            $json = array(
                'result' => $result,
                'response' => $response,
                'latitude' => $latitude,
                'langitude' => $longitude
            );
        } else {
            $json = array(
                'result' => $result,
                'response' => $response,
            );
        }

        echo json_encode($json);
    }

    /*
     * * Returns feedback added confirmation.
     * * Post Required: agent id, feedback
     * * Url:- http://www.demo.com/index.php?r=agents/appfeedback
     * * Purpose:- adding agent application feedback
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

        $agent_id = Yii::app()->request->getParam('agent_id');

        $comments = '';
        $title = '';
        $comments = Yii::app()->request->getParam('comments');
        $title = Yii::app()->request->getParam('title');

        $json = array();

        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($agent_id) && !empty($agent_id)) && (isset($comments) && !empty($comments))) {
            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }

            $agent_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));
            if (!count($agent_id_check)) {
                $json = array(
                    'result' => "false",
                    'response' => "agent not exist",
                );

                echo json_encode($json);
                die();
            }
            $agent_feedback_check = Appfeedbacks::model()->findByAttributes(array("agent_id" => $agent_id));

            if ($agent_id_check) {

                $washfeedbackdata = array(
                    'agent_id' => $agent_id,
                    'comments' => $comments,
                    'title' => $title,
                );

                Yii::app()->db->createCommand()->insert('app_feedbacks', $washfeedbackdata);
//                } else {
//                    $washfeedbackdata = array(
//                        'agent_id' => $agent_id,
//                        'comments' => $comments
//                    );
//                    $washfeedbackmodel = new Appfeedbacks;
//
//                    $washfeedbackmodel->attributes = $washfeedbackdata;
//                    $washfeedbackmodel->updateAll($washfeedbackdata, 'agent_id=:agent_id', array(':agent_id' => $agent_id));
//                }

                $result = 'true';
                $response = "Feeback added";



                $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Washer App Feedback</h2>
<p><b>Washer Name:</b> " . $agent_id_check->first_name . " " . $agent_id_check->last_name . "</p>
<p><b>Washer Email:</b> " . $agent_id_check->email . "</p>
<p><b>Title:</b> " . $title . "</p>
<p><b>Comments:</b> " . $comments . "</p>";

                //$to = Vargas::Obj()->getAdminToEmail();
                $to = Vargas::Obj()->getAdminToEmailFeedBack();
                $from = Vargas::Obj()->getAdminFromEmail();

                Vargas::Obj()->SendMail($to, $from, $message, "Washer App Feedback", 'mail-receipt');
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Returns all agents
     * * Post Required: none
     * * Url:- http://www.demo.com/index.php?r=agents/allagents
     * * Purpose:- getting all agents info
     */

    public function actionallagents() {

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
        $result = 'false';
        $response = 'Pass the required parameters';
        $all_agents = array();
        $agent_details = new stdClass();
        $agents = Yii::app()->db->createCommand()
                ->select('*')
                ->from('agents')
                ->queryAll();

        if (!count($agents)) {
            $response = 'No agents found';
            $result = 'false';
        } else {
            $result = 'true';
            $response = 'All agents';
            foreach ($agents as $agent) {
                $total_wash = 0;
                $sql = "SELECT COUNT(*) as washes FROM washing_requests WHERE agent_id='" . $agent['id'] . "' AND status='5'";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryAll();
                $total_wash = (int) $results[0]["washes"];
                $agent_details = array("id" => $agent['id'], "name" => $agent['first_name'] . " " . $agent['last_name'], "email" => $agent['email'], "total_washes" => $agent['total_wash'], "washer_position" => $agent['washer_position'], "bt_submerchant_id" => $agent['bt_submerchant_id'], "real_washer_id" => $agent['real_washer_id']);
                $all_agents[] = $agent_details;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'agents' => $all_agents
        );

        echo json_encode($json);
        die();
    }

    public function actionallagents_formatted() {

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
        $result = 'false';
        $response = 'Pass the required parameters';
        $all_agents = array();
        $agent_details = new stdClass();
        $agents = Yii::app()->db->createCommand()
                ->select('*')
                ->from("agents")
                ->where("washer_position='" . APP_ENV . "'")
                ->order("id asc")
                ->queryAll();

        if (!count($agents)) {
            $response = 'No agents found';
            $result = 'false';
        } else {
            $result = 'true';
            $response = 'All agents';
            foreach ($agents as $agent) {
                $total_wash = 0;

                $agent_details = array("label" => $agent['real_washer_id'] . " - " . $agent['first_name'] . " " . $agent['last_name'], "value" => $agent['id']);
                $all_agents[] = $agent_details;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'agents' => $all_agents
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Returns top agents
     * * Post Required: none
     * * Url:- http://www.demo.com/index.php?r=agents/topagents
     * * Purpose:- getting top agents info
     */

    public function actiononlineagents() {

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
        $result = 'false';
        $response = 'Pass the required parameters';
        $all_agents = array();
        $agent_details = new stdClass();
        $sql = "SELECT * FROM agents WHERE status= 'online'";
        $agents = Yii::app()->db->createCommand($sql)->queryAll();

        if (!count($agents)) {
            $response = 'No agents found';
            $result = 'false';
        } else {
            $result = 'true';
            $response = 'agents';
            foreach ($agents as $agent) {
                $sql = "SELECT * FROM agent_locations WHERE agent_id='" . $agent['id'] . "'";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryAll();
                $agent_details = array("id" => $agent['id'], "first_name" => $agent['first_name'], "last_name" => $agent['last_name'], "email" => $agent['email'], "latitude" => $results[0]['latitude'], "longitude" => $results[0]['longitude'], "total_washes" => $agent['total_wash']);
                $all_agents[] = $agent_details;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'agents' => $all_agents
        );

        echo json_encode($json);
        die();
    }

    /**
     * * Returns agent details.
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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((isset($agent_id) && !empty($agent_id))) {
            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }
            $agent_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));
            if (count($agent_id_check) > 0) {

                $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1")->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)->queryAll();


                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_id_check->id));

                $total_rate = count($agent_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($agent_feedbacks as $agent_feedback) {
                        $rate += $agent_feedback->agent_ratings;
                    }

                    $agent_rate = round($rate / $total_rate);
                } else {
                    $agent_rate = 0;
                }

                $agent_rate = $agent_id_check->rating;

                if ($agent_id_check->bt_submerchant_id) {
                    if ($agent_id_check->washer_position == 'real')
                        $bt_result = Yii::app()->braintree->getsubmerchantbyid_real($agent_id_check->bt_submerchant_id);
                    else
                        $bt_result = Yii::app()->braintree->getsubmerchantbyid($agent_id_check->bt_submerchant_id);

//print_r($bt_result);

                    $bank_no = '';
                    $routing_no = '';

                    if ($bt_result && ($bt_result->status == 'active')) {
                        if ($bt_result->fundingDetails->accountNumberLast4)
                            $bank_no = '********' . $bt_result->fundingDetails->accountNumberLast4;
                        if ($bt_result->fundingDetails->routingNumber)
                            $routing_no = $bt_result->fundingDetails->routingNumber;
                    }
                }

                if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                    $agent_id = $this->aes256cbc_crypt($agent_id_check->id, 'e', AES256CBC_API_PASS);
                } else {
                    $agent_id = $agent_id_check->id;
                }

                $json = array(
                    'result' => 'true',
                    'response' => 'Agent details',
                    'id' => $agent_id,
                    'first_name' => $agent_id_check->first_name,
                    'last_name' => $agent_id_check->last_name,
                    'email' => $agent_id_check->email,
                    'date_of_birth' => $agent_id_check->date_of_birth,
                    'phone_number' => $agent_id_check->phone_number,
                    'street_address' => $agent_id_check->street_address,
                    'suite_apt' => $agent_id_check->suite_apt,
                    'city' => $agent_id_check->city,
                    'state' => $agent_id_check->state,
                    'zipcode' => $agent_id_check->zipcode,
                    'driver_license' => $agent_id_check->driver_license,
                    'proof_insurance' => $agent_id_check->proof_insurance,
                    'insurance_expiration' => $agent_id_check->insurance_license_expiration,
                    'business_license' => $agent_id_check->business_license,
                    'bank_account_number' => $bank_no,
                    'routing_number' => $routing_no,
                    'legally_eligible' => $agent_id_check->legally_eligible,
                    'own_vehicle' => $agent_id_check->own_vehicle,
                    'waterless_wash_product' => $agent_id_check->waterless_wash_product,
                    'operate_area' => $agent_id_check->operate_area,
                    'work_schedule' => $agent_id_check->work_schedule,
                    'operating_as' => $agent_id_check->operating_as,
                    'company_name' => $agent_id_check->company_name,
                    'wash_experience' => $agent_id_check->wash_experience,
                    'email_alerts' => $agent_id_check->email_alerts,
                    'push_notifications' => $agent_id_check->push_notifications,
                    'image' => $agent_id_check->image,
                    'status' => $agent_id_check->status,
                    'account_status' => $agent_id_check->account_status,
                    'total_washes' => $agent_id_check->total_wash,
                    'rating' => $agent_id_check->rating,
                    'created_date' => $agent_id_check->created_date,
                    'updated_date' => $agent_id_check->updated_date,
                    'phone_verified' => $agent_id_check->phone_verified,
                    'mobile_type' => $agent_id_check->mobile_type,
                    'bt_submerchant_id' => $agent_id_check->bt_submerchant_id,
                    'washer_position' => $agent_id_check->washer_position,
                    'real_washer_id' => $agent_id_check->real_washer_id,
                    'notes' => $agent_id_check->notes,
                    'available_for_new_order' => $agent_id_check->available_for_new_order,
                    'block_washer' => $agent_id_check->block_washer,
                    'hours_opt_check' => $agent_id_check->hours_opt_check,
                    'rating_control' => $agent_id_check->rating_control,
                    'sms_control' => $agent_id_check->sms_control,
                    'last_edited_admin' => $agent_id_check->last_edited_admin,
                    'last_used_device' => $agentdevices,
                    'decals_installed' => $agent_id_check->decals_installed,
                    'unlimited_schedule_range' => $agent_id_check->unlimited_schedule_range,
                );
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid agent'
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
     * * Performs the agent Profile update.
     * * Post Required: agent_id, first_name, last_name, email, phone_number, new_password, confirm_password, street_address, suite_apt, city, state, zipcode,
      operating_as, company_name, wash_experience, account_status, status, image, email_alerts, push_notify
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
        $agent_id = Yii::app()->request->getParam('agent_id');
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $agentname = $first_name . " " . $last_name;
        $email = Yii::app()->request->getParam('email');
        $phone_number = Yii::app()->request->getParam('phone_number');
        $phone_number = preg_replace('/\D/', '', $phone_number);
        $date_of_birth = Yii::app()->request->getParam('date_of_birth');
        $new_password = Yii::app()->request->getParam('new_password');
        $confirm_password = Yii::app()->request->getParam('confirm_password');
        $street_address = Yii::app()->request->getParam('street_address');
        $suite_apt = Yii::app()->request->getParam('suite_apt');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $operating_as = Yii::app()->request->getParam('operating_as');
        $company_name = Yii::app()->request->getParam('company_name');
        $wash_experience = Yii::app()->request->getParam('wash_experience');
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $routing_number = Yii::app()->request->getParam('routing_number');
        $account_status = '';
        $account_status = Yii::app()->request->getParam('account_status');
        $status = Yii::app()->request->getParam('status');
        $image = Yii::app()->request->getParam('image');
        $email_alerts = '';
        $email_alerts = Yii::app()->request->getParam('email_alerts');
        $push_notify = '';
        $push_notify = Yii::app()->request->getParam('push_notify');
        $agent_profile_img = '';
        $agent_profile_img = Yii::app()->request->getParam('agent_profile_img');
        $driver_license = '';
        $driver_license = Yii::app()->request->getParam('driver_license');
        $proof_insurance = '';
        $proof_insurance = Yii::app()->request->getParam('proof_insurance');
        $business_license = '';
        $business_license = Yii::app()->request->getParam('business_license');
        $bt_submerchant_id = '';
        $bt_submerchant_id = Yii::app()->request->getParam('bt_submerchant_id');
        $washer_position = Yii::app()->request->getParam('washer_position');
        $real_washer_id = Yii::app()->request->getParam('real_washer_id');
        $rating = Yii::app()->request->getParam('rating');
        $notes = Yii::app()->request->getParam('notes');
        $phone_dup_check = Yii::app()->request->getParam('phone_dup_check');
        $block_washer = Yii::app()->request->getParam('block_washer');
        $admin_edit = '';
        $admin_edit = Yii::app()->request->getParam('admin_edit');
        $hours_opt_check = '';
        $hours_opt_check = Yii::app()->request->getParam('hours_opt_check');
        $decals_installed = 1;
        $decals_installed = Yii::app()->request->getParam('decals_installed');
        $rating_control = 0;
        $rating_control = Yii::app()->request->getParam('rating_control');
        $rating_control = 0;
        $sms_control = Yii::app()->request->getParam('sms_control');
        $insurance_expiration = '';
        $insurance_expiration = Yii::app()->request->getParam('insurance_expiration');
        $is_voip_number = Yii::app()->request->getParam('is_voip_number');
        $api_password = '';
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $force_logout = 0;
        if ($block_washer == 1)
            $force_logout = 1;

        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }
        //if($phone_dup_check == 'true'){
        if ($phone_number) {
            $agent_phone_exists = Agents::model()->findByAttributes(array("phone_number" => $phone_number));
            $agent_detail = Agents::model()->findByAttributes(array("id" => $agent_id));

            if ((count($agent_phone_exists) > 0) && ($agent_phone_exists->id != $agent_id)) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Phone number already exists.',
                    'contact_number' => $agent_detail->phone_number
                );

                echo json_encode($json);
                die();
            }

            $sid = TWILIO_SID;
            $token = TWILIO_AUTH_TOKEN;
            $twilio = new Client($sid, $token);
            try {
                $phone_number_check = $twilio->lookups->v1->phoneNumbers($phone_number)->fetch(array("type" => "carrier"));

                if (($phone_number_check->carrier['type'] == 'voip') && (!$block_washer)) {
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

            if (!$admin_edit) {
                $digits = 4;
                $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $update_response = Yii::app()->db->createCommand("UPDATE agents SET phone_verify_code='$randum_number' WHERE id = '$agent_id' ")->execute();
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
                        'To' => $phone_number,
                        'From' => '+13106834902',
                        'Body' => $message,
                    ));
                } catch (Services_Twilio_RestException $e) {
                    //echo  $e;
                }
            }
        }


        if (isset($agent_id) && !empty($agent_id)) {
            $model = Agents::model()->findByAttributes(array('id' => $agent_id));

            if (($model->block_washer) && ($admin_edit != 'true')) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Account error. Please contact MobileWash.'
                );

                echo json_encode($json);
                die();
            }
            if (count($model) > 0) {
                if (!empty($image)) {
                    $directorypath1 = realpath(Yii::app()->basePath . '/../images/agent_img');
                    $img = str_replace('data:image/PNG;base64,', '', $image);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $md5 = md5(uniqid(rand(), true));
                    $name = $agent_id . '_' . $md5 . ".jpg";
                    $path = $directorypath1 . '/' . $name;
                    $SiteUrl = Yii::app()->getBaseUrl(true);
                    $image = $SiteUrl . '/images/agent_img/' . $agent_id . '_' . $md5 . ".jpg";
                    file_put_contents($path, $data);
                } else {
                    $image = $model->image;
                }


                /* if(isset($email) && !empty($email) ){
                  $email_Exist= Agents::model()->findByAttributes(array('email'=>$email));
                  if(isset($email_Exist->id) &&($email_Exist->id == $agent_id)){
                  $email_Exist = array();
                  }
                  if(count($email_Exist)>0){
                  $email_details= Agents::model()->findByAttributes(array('id'=> $id));
                  if(count($email_details)>0){
                  $email = $email_details->email;
                  }
                  //$result= 'false';
                  //$response= 'Email already exists';
                  $email = $model->email;
                  }


                  }else{
                  $email = $model->email;
                  } */

                if (empty($email)) {
                    $email = $model->email;
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

                        $password = md5($new_password);
                    }
                } else {

                    $password = $model->password;
                }

                if (empty($phone_number)) {
                    $phone_number = $model->phone_number;
                }

                if (empty($date_of_birth)) {
                    $date_of_birth = $model->date_of_birth;
                }

                if (empty($street_address)) {
                    $street_address = $model->street_address;
                }

                if (empty($suite_apt)) {
                    $suite_apt = $model->suite_apt;
                }

                if (empty($city)) {
                    $city = $model->city;
                }

                if (empty($state)) {
                    $state = $model->state;
                }

                if (empty($zipcode)) {
                    $zipcode = $model->zipcode;
                }

                /* if(empty($legally_eligible)){
                  $legally_eligible = $model->legally_eligible;
                  } */

                if (empty($own_vehicle)) {
                    $own_vehicle = $model->own_vehicle;
                }

                /* if(empty($waterless_wash_product)){
                  $waterless_wash_product = $model->waterless_wash_product;
                  } */

                if (empty($operate_area)) {
                    $operate_area = $model->operate_area;
                }

                /* if(empty($work_schedule)){
                  $work_schedule = $model->work_schedule;
                  } */

                if (empty($operating_as)) {
                    $operating_as = $model->operating_as;
                }

                /* if(empty($company_name)){
                  $company_name = $model->company_name;
                  } */

                if (empty($wash_experience)) {
                    $wash_experience = $model->wash_experience;
                }

                /* if(empty($bank_account_number)){
                  $bank_account_number = $model->bank_account_number;
                  } */

                /* if(empty($routing_number)){
                  $routing_number = $model->routing_number;
                  } */

                if (empty($insurance_expiration)) {
                    $insurance_expiration = $model->insurance_license_expiration;
                }

                if (empty($bt_submerchant_id)) {
                    $bt_submerchant_id = $model->bt_submerchant_id;
                }

                if (!is_numeric($block_washer)) {
                    $block_washer = $model->block_washer;
                }

                if (!is_numeric($rating_control)) {
                    $rating_control = $model->rating_control;
                }

                if (!is_numeric($sms_control)) {
                    $sms_control = $model->sms_control;
                }

                if (!is_numeric($is_voip_number)) {
                    $is_voip_number = $model->is_voip_number;
                }

                if ($account_status == '') {
                    $account_status = $model->account_status;
                }

                if (empty($status)) {
                    $status = $model->status;
                }

                if ($email_alerts == '') {
                    $email_alerts = $model->email_alerts;
                }


                if ($push_notify == '') {
                    $push_notify = $model->push_notifications;
                }

                if ($driver_license == '') {
                    $driver_license = $model->driver_license;
                }

                if ($agent_profile_img == '') {
                    $agent_profile_img = $image;
                }

                if ($proof_insurance == '') {
                    $proof_insurance = $model->proof_insurance;
                }

                if ($business_license == '') {
                    $business_license = $model->business_license;
                }

                if ($washer_position == '') {
                    $washer_position = $model->washer_position;
                }

                if ($washer_position == 'demo')
                    $washer_position = '';

                if ($real_washer_id == '') {
                    $real_washer_id = $model->real_washer_id;
                }

                if ($admin_username == '') {
                    $admin_username = $model->last_edited_admin;
                }

                if ($rating == '') {
                    $rating = $model->rating;
                }

                if ($notes == '') {
                    $notes = $model->notes;
                }

                if (!is_numeric($hours_opt_check)) {
                    $hours_opt_check = $model->hours_opt_check;
                }

                if ($agent_profile_img) {
                    $image = $agent_profile_img;
                }

                $data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    //'agentname' => $agentname,
                    'email' => $email,
                    'image' => $image,
                    //'phone_number'=> $phone_number,
                    'date_of_birth' => $date_of_birth,
                    'street_address' => $street_address,
                    'suite_apt' => $suite_apt,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'legally_eligible' => $legally_eligible,
                    'own_vehicle' => $own_vehicle,
                    'waterless_wash_product' => $waterless_wash_product,
                    'operate_area' => $operate_area,
                    'work_schedule' => $work_schedule,
                    'operating_as' => $operating_as,
                    'company_name' => $company_name,
                    'wash_experience' => $wash_experience,
                    'account_status' => $account_status,
                    'status' => $status,
                    'email_alerts' => $email_alerts,
                    'push_notifications' => $push_notify,
                    'password' => $password,
                    'driver_license' => $driver_license,
                    'proof_insurance' => $proof_insurance,
                    'insurance_license_expiration' => $insurance_expiration,
                    'business_license' => $business_license,
                    'washer_position' => $washer_position,
                    'block_washer' => $block_washer,
                    'real_washer_id' => $real_washer_id,
                    'rating' => $rating,
                    'notes' => $notes,
                    'hours_opt_check' => $hours_opt_check,
                    'rating_control' => $rating_control,
                    'sms_control' => $sms_control,
                    'bt_submerchant_id' => $bt_submerchant_id,
                    'forced_logout' => $force_logout,
                    'is_voip_number' => $is_voip_number,
                    'bank_account_number' => $bank_account_number,
                    'routing_number' => $routing_number,
                    'last_edited_admin' => $admin_username,
                    //'decals_installed' => $decals_installed,
                    'updated_date' => date('Y-m-d H:i:s')
                );
                if (Yii::app()->request->getParam('update_by') == "WEB") {
                    $data['unlimited_schedule_range'] = Yii::app()->request->getParam('unlimited_schedule_range');
                    $data['agentname'] = $agentname;
                    $data['decals_installed'] = $decals_installed;
                }

                if ($admin_edit == 'true')
                    $data['phone_number'] = $phone_number;

                Agents::model()->updateByPk($agent_id, $data);

                $response = 'Profile updated';
                $result = 'true';
                $agentcheck = Agents::model()->findByAttributes(array('id' => $agent_id));

                if ($agentcheck->status == 'online') {

                    /* ------------- check if agent available for new order ------------- */

                    $isagentbusy = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id='" . $agentcheck->id . "' AND (status >= 1 AND status <= 3)")->queryAll();
                    ;
                    if (!count($isagentbusy)) {
                        Agents::model()->updateAll(array('available_for_new_order' => 1), 'id=:id', array(':id' => $agentcheck->id));
                    }

                    /* ------------- check if agent available for new order end ------------- */
                }

                /* ----- braintree submerchant account update ----------- */

                $merchant_id = 'mobilewashinc';
                if ($washer_position == 'real')
                    $merchant_id = 'MobileWashINC_marketplace';

                $fullname = $first_name . " " . $last_name;
                $merchantAccountParams = [
                    'individual' => [
                        'firstName' => $first_name,
                        'lastName' => $last_name,
                        'email' => $email,
                        'phone' => $phone_number,
                        'dateOfBirth' => $date_of_birth,
                        'address' => [
                            'streetAddress' => $street_address,
                            'locality' => $city,
                            'region' => $state,
                            'postalCode' => $zipcode
                        ]
                    ],
                    'funding' => [
                        'descriptor' => $fullname,
                        'destination' => 'bank',
                        'accountNumber' => $bank_account_number,
                        'routingNumber' => $routing_number
                    ],
                    'tosAccepted' => true,
                    'masterMerchantAccountId' => $merchant_id
                ];

                if ($agentcheck->bt_submerchant_id) {
                    if ($washer_position == 'real')
                        $bt_result = Yii::app()->braintree->updateSubMerchant_real($agentcheck->bt_submerchant_id, $merchantAccountParams);
                    else
                        $bt_result = Yii::app()->braintree->updateSubMerchant($agentcheck->bt_submerchant_id, $merchantAccountParams);
                }
                else {
                    if ($washer_position == 'real')
                        $bt_result = Yii::app()->braintree->createSubMerchant_real($merchantAccountParams);
                    else
                        $bt_result = Yii::app()->braintree->createSubMerchant($merchantAccountParams);

                    Agents::model()->updateByPk($agentcheck->id, array('bt_submerchant_id' => $bt_result['sub_merchant_id']));
                }

                //print_r($bt_result);
                //exit;


                /* ----- braintree submerchant account update end ----------- */

                echo json_encode(array('result' => $result, 'response' => $response));
                die();
            }else {
                $result = 'false';
                $response = 'Invalid agent';
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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $limit = 5;
        if (Yii::app()->request->getParam('limit'))
            $limit = Yii::app()->request->getParam('limit');
        $page = 1;
        if (Yii::app()->request->getParam('page'))
            $page = Yii::app()->request->getParam('page');
        $total_entries = 0;
        $total_entries2 = 0;
        $total_pages = 0;

        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        if ((isset($agent_id) && !empty($agent_id))) {

            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }
            $agent_id_check = Agents::model()->findByAttributes(array('id' => $agent_id));
            if (!count($agent_id_check)) {
                $result = 'false';
                $response = 'Invalid agent id';
            } else {
                $all_wash_requests_count = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM washing_requests WHERE (agent_id=:agent_id OR canceled_washer_id = :agent_id) AND ((status='4' OR status='5' OR status='6' OR status='7')) order by order_for desc")
                        ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                        ->queryAll();
                $total_entries = $all_wash_requests_count[0]['count'];

                $all_wash_requests_count2 = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id=:agent_id AND action='dropjob' order by action_date desc")
                        ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                        ->queryAll();
                $total_entries2 = $all_wash_requests_count2[0]['count'];

                $total_entries += $total_entries2;

                if ($total_entries) {
                    $total_pages = ceil($total_entries / $limit);
//$frag_pages = $total_entries % $limit;
//if($frag_pages > 0)$total_pages++;
                }

                $all_wash_requests = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('washing_requests')
                        ->where("(agent_id=:agent_id OR canceled_washer_id = :agent_id) AND ((status='4' OR status='5' OR status='6' OR status='7'))", array(":agent_id" => $agent_id))
                        ->limit($limit)
                        ->offset(($page - 1) * $limit)
                        ->order(array('order_for desc'))
                        ->queryAll();

                /* $all_wash_requests =  Yii::app()->db->createCommand("SELECT DISTINCT w.id, w.agent_id FROM washing_requests w LEFT JOIN activity_logs a ON w.id = a.wash_request_id WHERE (w.agent_id='".$agent_id."' AND (w.status='4' OR w.status='5' OR w.status='6')) OR (a.agent_id='".$agent_id."' AND a.action='dropjob') order by created_date desc")->queryAll();
                  echo count($all_wash_requests);
                  print_r($all_wash_requests);
                  exit; */
                //print_r($all_wash_requests); die;
                if (count($all_wash_requests)) {
                    foreach ($all_wash_requests as $index => $wrequest) {

                        /* ----- total and discounts ------- */

                        /*  $handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
                          $data = array("wash_request_id"=>$wrequest['id'], "key" => API_KEY);
                          curl_setopt($handle, CURLOPT_POST, true);
                          curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                          curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                          $kartapiresult = curl_exec($handle);
                          curl_close($handle);
                          $jsondata = json_decode($kartapiresult);
                          //var_dump($jsondata); */

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
                        //$total = $wrequest['agent_total'] + ($wrequest['tip_amount'] * .80);
                        $total = $kartdata->agent_total;

                        $inspectsobject = Washinginspections::model()->findAllByAttributes(array("wash_request_id" => $wrequest['id']));
                        //echo count($inspectsobject);

                        if (count($inspectsobject)) {
                            foreach ($inspectsobject as $inspect) {

                                $inspectcarobject = Vehicle::model()->findByAttributes(array("id" => $inspect->vehicle_id));

                                $inspect_details[] = array('vehicle_id' => $inspectcarobject->id, 'vehicle_make' => $inspectcarobject->brand_name, 'vehicle_model' => $inspectcarobject->model_name, 'damage_pic' => $inspect->damage_pic);
                            }
                        }


                        $wash_requests[$index]['vehicle_details'] = $vehicles;
                        $wash_requests[$index]['inspection_details'] = $inspect_details;
                        $wash_requests[$index]['total'] = $total;
                        $customerdata = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));
                        $customername = $customerdata->first_name . " " . $customerdata->last_name;

                        if (($customerdata->first_name != '') && ($customerdata->last_name != '')) {
                            $customershortname = '';
                            $cust_name = explode(" ", trim($customerdata->last_name));
                            $customershortname = $customerdata->first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
                        } else {
                            $customershortname = '';
                            $cust_name = explode(" ", trim($customerdata->customername));
                            if (count($cust_name > 1))
                                $customershortname = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                            else
                                $customershortname = $cust_name[0];
                        }

                        $customershortname = strtolower($customershortname);
                        $customershortname = ucwords($customershortname);

                        $wash_requests[$index]['customer_name'] = $customername;
                        $wash_requests[$index]['customer_short_name'] = $customershortname;
                        if (AES256CBC_STATUS == 1) {
                            $wash_requests[$index]['customer_id'] = $this->aes256cbc_crypt($customerdata->id, 'e', AES256CBC_API_PASS);
                        } else {
                            $wash_requests[$index]['customer_id'] = $customerdata->id;
                        }

                        $washfeedbacks = Washingfeedbacks::model()->findByAttributes(array("agent_id" => $agent_id, "wash_request_id" => $wrequest['id']));
                        if ($washfeedbacks->customer_ratings)
                            $wash_requests[$index]['rating'] = number_format($washfeedbacks->customer_ratings, 2);
                        else
                            $wash_requests[$index]['rating'] = $washfeedbacks->customer_ratings;
                        if (!count($washfeedbacks))
                            $wash_requests[$index]['rating'] = 5.00;
                        $wash_requests[$index]['status'] = $wrequest['status'];
                        $wash_requests[$index]['cancel_fee'] = $wrequest['cancel_fee'];
                        $wash_requests[$index]['washer_cancel_fee'] = $wrequest['washer_cancel_fee'];
                    }
                }

                $result = 'true';
                $response = 'Acccount history';
            }
        }
        else {
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

    public function actionisagentnearest() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $result = 'false';
        $response = 'Pass the required parameters';
        $distance_array = array();
        $distance_closest_array = array();
        $distance = 0;
        $near_agent = '';

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }

        $check_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id, "customer_id" => $customer_id));
        $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings` WHERE `app_type` = 'IOS'")->queryAll();

        if (!count($check_data)) {
            $result = 'false';
            $response = 'Invalid data';
        } else {

            $customer_lat = $check_data->latitude;
            $customer_lng = $check_data->longitude;
            //echo "customer latlng: ".$customer_lat.", ".$customer_lng;



            $sql = "SELECT * FROM agent_locations";
            $command = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($command as $loc) {
                /* --------- distance calculation ------------ */

                $theta = $customer_lng - $loc['longitude'];
                $dist = sin(deg2rad($customer_lat)) * sin(deg2rad($loc['latitude'])) + cos(deg2rad($customer_lat)) * cos(deg2rad($loc['latitude'])) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
//echo "#".$loc['agent_id']." ".$miles."<br>";

                /*
                  if ($unit == "K") {
                  return ($miles * 1.609344);
                  } else if ($unit == "N") {
                  return ($miles * 0.8684);
                  } else {
                  return $miles;
                  }
                 */

                /*
                  $latFrom = deg2rad($agent_lat);
                  $lonFrom = deg2rad($agent_lng);
                  $latTo = deg2rad($customer_lat);
                  $lonTo = deg2rad($customer_lng);

                  $latDelta = $latTo - $latFrom;
                  $lonDelta = $lonTo - $lonFrom;

                  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                  $miles =  $angle * 6371000;
                 */
                /* --------- distance calculation end ------------ */

                if (($miles > 0) && ($miles <= $app_settings[0]['washer_search_radius'])) {
                    $distance_array[$loc['agent_id']] = $miles;
                }
            }

            if (count($distance_array)) {
                asort($distance_array);
                //print_r($distance_array);
                foreach ($distance_array as $aid => $dist) {
                    //echo $aid;

                    /* ------- check if agent is available for new order -------- */

                    $isavailable = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $aid . "' AND available_for_new_order = 1 AND block_washer=0")->queryAll();

                    /* ------- check if agent is available for new order end -------- */

                    /* --- agent assigned to another order --- */

                    //$agent_has_order = Washingrequests::model()->findByAttributes(array("order_temp_assigned"=>$aid, "status"=>0, "is_scheduled"=>0), array('condition'=>'id'!=$wash_request_id));

                    /* --- agent assigned to another order end --- */

                    /* ------- check if agent already reject current order -------- */

                    $order_rejects = false;
                    $a_array = explode(',', $check_data->agent_reject_ids);
                    foreach ($a_array as $aaid) {
                        //echo $id;
                        if ($aaid == '-' . $aid) {
                            $order_rejects = true;
                            break;
                        }
                    }


                    /* ------- check if agent already reject current order end -------- */


                    //if(count($isavailable) && (!$order_rejects) && (!count($agent_has_order))){
                    if (count($isavailable) && (!$order_rejects)) {
//echo "working";
                        $near_agent = $aid;
                        if ($near_agent == $agent_id)
                            break;
                    }
                    //echo $aid."<br>";
                }
                //echo $near_agent;
                //$distance_closest_array = array_keys($distance_array, min($distance_array));
                //print_r($distance_array);
                //print_r($distance_closest_array);
                //$is_closest = array_search($agent_id, $distance_array);
                if ($near_agent == $agent_id) {
                    $result = 'true';
                    $response = 'agent is nearest';
                } else {
                    $result = 'false';
                    $response = 'agent is not nearest';
                }
            } else {
                $result = 'false';
                $response = 'agent is not nearest';
            }

            //$agent_lat = $agentdata->latitude;
            //$agent_lng = $agentdata->longitude;
            //echo "<br>agent lat long: ".$agent_lat.", ".$agent_lng;
            //echo "<br>";
        }



        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function actiongetnearestagents() {

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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $cust_lat = Yii::app()->request->getParam('cust_lat');
        $cust_lng = Yii::app()->request->getParam('cust_lng');
        $ignore_offline = 0;
        $ignore_offline = Yii::app()->request->getParam('ignore_offline');
        $api_password = '';
        $api_password = Yii::app()->request->getParam('api_password');
        $result = 'false';
        $response = 'Pass the required parameters';
        $distance_array = array();
        $distance_closest_array = array();
        $distance = 0;
        $near_agent = '';

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }
        $check_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
        $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings` WHERE `app_type` = 'IOS'")->queryAll();

        if (!count($check_data) && (!$cust_lat)) {
            $result = 'false';
            $response = 'Invalid data';
        } else {

            if ($cust_lat && $cust_lng) {
                $customer_lat = $cust_lat;
                $customer_lng = $cust_lng;
            } else {
                $customer_lat = $check_data->latitude;
                $customer_lng = $check_data->longitude;
            }

            //echo "customer latlng: ".$customer_lat.", ".$customer_lng;



            $sql = "SELECT * FROM agent_locations";
            $command = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($command as $loc) {
                /* --------- distance calculation ------------ */

                $theta = $customer_lng - $loc['longitude'];
                $dist = sin(deg2rad($customer_lat)) * sin(deg2rad($loc['latitude'])) + cos(deg2rad($customer_lat)) * cos(deg2rad($loc['latitude'])) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);

                /*
                  if ($unit == "K") {
                  return ($miles * 1.609344);
                  } else if ($unit == "N") {
                  return ($miles * 0.8684);
                  } else {
                  return $miles;
                  }
                 */

                /*
                  $latFrom = deg2rad($agent_lat);
                  $lonFrom = deg2rad($agent_lng);
                  $latTo = deg2rad($customer_lat);
                  $lonTo = deg2rad($customer_lng);

                  $latDelta = $latTo - $latFrom;
                  $lonDelta = $lonTo - $lonFrom;

                  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                  $miles =  $angle * 6371000;
                 */
                /* --------- distance calculation end ------------ */

                if (($miles > 0) && ($miles <= $app_settings[0]['washer_search_radius'])) {

                    /* ------- check if agent is available for new order -------- */

                    if ($ignore_offline == 1)
                        $isavailable = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $loc['agent_id'] . "' AND block_washer=0")->queryAll();
                    elseif ($ignore_offline == 2)
                        $isavailable = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $loc['agent_id'] . "' AND available_for_new_order = 0 AND status = 'offline' AND block_washer=0")->queryAll();
                    else
                        $isavailable = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $loc['agent_id'] . "' AND available_for_new_order = 1 AND block_washer=0")->queryAll();
                    /* ------- check if agent is available for new order end -------- */



                    if (count($isavailable) > 0) {
                        $distance_array[$loc['agent_id']] = $miles;
                    }
                }
            }

            if (count($distance_array)) {
                asort($distance_array);
                $result = 'true';
                $response = 'nearest agents of order';
            } else {
                $result = 'false';
                $response = 'no nearest agents found';
            }

            //$agent_lat = $agentdata->latitude;
            //$agent_lng = $agentdata->longitude;
            //echo "<br>agent lat long: ".$agent_lat.", ".$agent_lng;
            //echo "<br>";
        }



        $json = array(
            'result' => $result,
            'response' => $response,
            'nearest_agents' => $distance_array
        );

        echo json_encode($json);
        die();
    }

    public function actionagentsbystatus() {

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

        $agent_online = Agents::model()->findAllByAttributes(array("status" => 'online', "available_for_new_order" => 1));
        $agent_offline = Agents::model()->findAllByAttributes(array("status" => 'offline', 'block_washer' => 0));
        $logs = Yii::app()->db->createCommand()
                ->select('*')
                ->from('washing_requests')  //Your Table name
                ->group('agent_id')
                ->where('status>=1 AND status<=3') // Write your where condition here
                ->queryAll();
        $busyagents = count($logs);


        $agentloc = new AgentLocations;

        //Online agents

        $agent = array();
        foreach ($agent_online as $onlineagents) {

            $checkagent_loc = $agentloc->findByAttributes(array('agent_id' => $onlineagents->id));
            $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $onlineagents->id));

            $total_rate = count($agent_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($agent_feedbacks as $agent_feedback) {
                    $rate += $agent_feedback->agent_ratings;
                }

                $agent_rate = round($rate / $total_rate);
            } else {
                $agent_rate = 0;
            }



            $key = 'agent_' . $onlineagents->id;
            $json = array();
            $json['id'] = $onlineagents->id;
            $json['first_name'] = $onlineagents->first_name;
            $json['last_name'] = $onlineagents->last_name;
            $json['phone_number'] = $onlineagents->phone_number;
            $json['image'] = $onlineagents->image;
            $json['latitude'] = $checkagent_loc->latitude;
            $json['longitude'] = $checkagent_loc->longitude;
            $json['total_wash'] = $onlineagents->total_wash;
            $json['rating'] = $onlineagents->rating;
            $json['badge_number'] = $onlineagents->real_washer_id;
            $agent[$key] = $json;
        }


        //Offline agents
        $agentoff = array();
        foreach ($agent_offline as $offlineagents) {

            $checkagent_loc = $agentloc->findByAttributes(array('agent_id' => $offlineagents->id));
            $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $offlineagents->id));

            $total_rate = count($agent_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($agent_feedbacks as $agent_feedback) {
                    $rate += $agent_feedback->agent_ratings;
                }

                $agent_rate = round($rate / $total_rate);
            } else {
                $agent_rate = 0;
            }

            $key = 'agent_' . $offlineagents->id;
            $jsonoff = array();
            $jsonoff['id'] = $offlineagents->id;
            $jsonoff['first_name'] = $offlineagents->first_name;
            $jsonoff['last_name'] = $offlineagents->last_name;
            $jsonoff['phone_number'] = $offlineagents->phone_number;
            $jsonoff['image'] = $offlineagents->image;
            $jsonoff['latitude'] = $checkagent_loc->latitude;
            $jsonoff['longitude'] = $checkagent_loc->longitude;
            $jsonoff['total_wash'] = $offlineagents->total_wash;
            $jsonoff['rating'] = $offlineagents->rating;
            $jsonoff['badge_number'] = $offlineagents->real_washer_id;
            $agentoff[$key] = $jsonoff;
        }

        // Busy agents

        $agentsbusy = array();
        foreach ($logs as $log) {

            $busyagent_id = $log['agent_id'];
            //echo "++";
            $agent_detail = Agents::model()->findByAttributes(array("id" => $busyagent_id));
            $checkagent_loc = $agentloc->findByAttributes(array('agent_id' => $agent_detail->id));
            $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_detail->id));

            $total_rate = count($agent_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($agent_feedbacks as $agent_feedback) {
                    $rate += $agent_feedback->agent_ratings;
                }

                $agent_rate = round($rate / $total_rate);
            } else {
                $agent_rate = 0;
            }

            $key = 'agent_' . $agent_detail->id;
            $jsonobusy = array();
            $jsonobusy['id'] = $agent_detail->id;
            $jsonobusy['first_name'] = $agent_detail->first_name;
            $jsonobusy['last_name'] = $agent_detail->last_name;
            $jsonobusy['phone_number'] = $agent_detail->phone_number;
            $jsonobusy['image'] = $agent_detail->image;
            $jsonobusy['latitude'] = $checkagent_loc->latitude;
            $jsonobusy['longitude'] = $checkagent_loc->longitude;
            $jsonobusy['total_wash'] = $agent_detail->total_wash;
            $jsonobusy['rating'] = $agent_detail->rating;
            $jsonobusy['badge_number'] = $agent_detail->real_washer_id;
            $agentsbusy[$key] = $jsonobusy;
        }

        $agentstatus['online'] = $agent;
        $agentstatus['offline'] = $agentoff;
        $agentstatus['busyAgents'] = $agentsbusy;

        echo json_encode($agentstatus);
        die();
    }

    public function actionagentsadmin() {

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


        $sort = Yii::app()->request->getParam('orderby');
        $sortorder = explode("_", $sort);
        $table = $sortorder[0];
        if ($table == 'u') {
            $set = 'email';
        } elseif ($table == 'i') {
            $set = 'id';
        } elseif ($table == 'f') {
            $set = 'first_name';
        } elseif ($table == 'l') {
            $set = 'last_name';
        } elseif ($table == 's') {
            $set = 'status';
        }

        $des = $sortorder[1];


        $agents = Yii::app()->db->createCommand("SELECT * FROM `agents` ORDER BY " . ($set) . " " . ($des) . " ")->queryAll();
        /* echo "SELECT * FROM `agents` ODER BY ". ($set) ." ". ($des) ." ";
          exit; */

        $agentloc = new AgentLocations;

        //Online agents

        $agent = array();
        foreach ($agents as $onlineagents) {
            $agetsid = $onlineagents['id'];
            $totalwash = 0;
            $logs = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('washing_requests')  //Your Table name
                    ->group('agent_id')
                    ->where('status>=1 AND status<=3 AND agent_id="' . $agetsid . '"') // Write your where condition here
                    ->queryAll();
            $cancelwash = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM `washing_requests` WHERE status IN ('5', '6') AND `agent_id` = '$agetsid' GROUP BY agent_id")->queryAll();

            $totalwash_arr = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status=4 AND `agent_id` = '$agetsid'")->queryAll();
            $totalwash = count($totalwash_arr);
            /* echo "<pre>";
              print_r($cancelwash);
              echo "<pre>"; */
            //exit;
            if (!empty($logs)) {
                $status = 'Busy';
            } else {
                $status = $onlineagents['status'];
            }
            $checkagent_loc = $agentloc->findByAttributes(array('agent_id' => $onlineagents['id']));
            $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $onlineagents['id']));

            $total_rate = count($agent_feedbacks);
            if ($total_rate) {
                $rate = 0;
                foreach ($agent_feedbacks as $agent_feedback) {
                    $rate += $agent_feedback->agent_ratings;
                }

                $agent_rate = round($rate / $total_rate);
            } else {
                $agent_rate = 0;
            }

            $agent_rate = $onlineagents['rating'];

            if (!empty($cancelwash)) {
                $cancelcarwash = $cancelwash[0]['count'];
            } else {
                $cancelcarwash = 0;
            }
            $key = 'agent_' . $onlineagents['id'];
            $json = array();
            $json['id'] = $onlineagents['id'];
            $json['email'] = $onlineagents['email'];
            $json['status'] = $status;
            $json['first_name'] = $onlineagents['first_name'];
            $json['last_name'] = $onlineagents['last_name'];
            $json['account_status'] = $onlineagents['account_status'];
            $json['total_wash'] = $totalwash;
            $json['cancels'] = $cancelcarwash;
            $json['lastactive'] = 'N/A';
            $json['rating'] = $agent_rate;
            $json['licenseexp'] = 'N/A';
            //$json['insurance_exp_date'] = $onlineagents['insurance_license_expiration'];
            $json['dailyavg'] = 'N/A';
            $agent[$key] = $json;
        }
        $agentstatus['agent'] = $agent;


        echo json_encode($agentstatus);

        exit;
    }

    public function actionsubmerchanttest() {

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

        $merchantAccountParams = [
            'individual' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'cika@gmail.com',
                'phone' => '5553334444',
                'dateOfBirth' => '1981-11-19',
                'address' => [
                    'streetAddress' => '111 Main St',
                    'locality' => 'Chicago',
                    'region' => 'IL',
                    'postalCode' => '60622'
                ]
            ],
            'funding' => [
                'descriptor' => 'John Doe',
                'destination' => 'bank',
                'email' => 'cika@gmail.com',
                'mobilePhone' => '5555555555',
                'accountNumber' => '1123581321',
                'routingNumber' => '071101307'
            ],
            'tosAccepted' => true,
            'masterMerchantAccountId' => "mobilewashinc"
        ];

        $result = Yii::app()->braintree->createSubMerchant($merchantAccountParams);
        print_r($result);
        if ($result['success'] == 1)
            echo $result['sub_merchant_id'];
    }

    public function actionsubmerchantpaytest() {

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

        $merchantAccountParams = [
            'merchantAccountId' => 'blue_ladders_store',
            'amount' => '10.00',
            'paymentMethodNonce' => nonceFromTheClient,
            'serviceFeeAmount' => "1.00"
        ];
        $result = Yii::app()->braintree->transactToSubMerchant($merchantAccountParams);
        if ($result['success'] == 1)
            echo $result['sub_merchant_id'];
    }

    public function actionEditAgentsAdmin() {

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

        $agents_id = Yii::app()->request->getParam('agentsid');
        $email = Yii::app()->request->getParam('email');
        $firstname = Yii::app()->request->getParam('fname');
        $lastname = Yii::app()->request->getParam('lname');
        $accountstatus = Yii::app()->request->getParam('accountstatus');
        $statususer = Yii::app()->request->getParam('status');
        if (!empty($email)) {
            $update_password = Agents::model()->updateAll(array('email' => $email), 'id=:id', array(':id' => $agents_id));
            $value = $email;
        } elseif (!empty($firstname)) {
            $update_password = Agents::model()->updateAll(array('first_name' => $firstname), 'id=:id', array(':id' => $agents_id));
            $value = $firstname;
        } elseif (!empty($lastname)) {
            $update_password = Agents::model()->updateAll(array('last_name' => $lastname), 'id=:id', array(':id' => $agents_id));
            $value = $lastname;
        } elseif ($statususer == 'online' || $statususer == 'offline') {


            $update_password = Agents::model()->updateAll(array('status' => $statususer), 'id=:id', array(':id' => $agents_id));
            $value = $statususer;
        } elseif ($accountstatus == 0 || $accountstatus == 1) {

            $update_password = Agents::model()->updateAll(array('account_status' => $accountstatus), 'id=:id', array(':id' => $agents_id));
            $value = $accountstatus;
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

    public function actionEditAgents() {

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

        $agents_id = Yii::app()->request->getParam('agentID');
        $agentsdetail = Agents::model()->findByAttributes(array("id" => $agents_id));
        $id = $agentsdetail['id'];
        $first_name = $agentsdetail['first_name'];
        $last_name = $agentsdetail['last_name'];
        $email = $agentsdetail['email'];
        $phone_number = $agentsdetail['phone_number'];
        $date_of_birth = $agentsdetail['date_of_birth'];
        $password = $agentsdetail['password'];
        $street_address = $agentsdetail['street_address'];
        $suite_apt = $agentsdetail['suite_apt'];
        $city = $agentsdetail['city'];
        $state = $agentsdetail['state'];
        $zipcode = $agentsdetail['zipcode'];
        $driver_license = $agentsdetail['driver_license'];
        $proof_insurance = $agentsdetail['proof_insurance'];
        $bank_account_number = $agentsdetail['bank_account_number'];
        $business_license = $agentsdetail['business_license'];
        $legally_eligible = $agentsdetail['legally_eligible'];
        $routing_number = $agentsdetail['routing_number'];
        $waterless_wash_product = $agentsdetail['waterless_wash_product'];
        $own_vehicle = $agentsdetail['own_vehicle'];
        $operate_area = $agentsdetail['operate_area'];
        $work_schedule = $agentsdetail['work_schedule'];
        $company_name = $agentsdetail['company_name'];
        $operating_as = $agentsdetail['operating_as'];
        $wash_experience = $agentsdetail['wash_experience'];
        $image = $agentsdetail['image'];
        $push_notifications = $agentsdetail['push_notifications'];
        $email_alerts = $agentsdetail['email_alerts'];
        $status = $agentsdetail['status'];
        $account_status = $agentsdetail['account_status'];
        $rating = $agentsdetail['rating'];
        $total_wash = $agentsdetail['total_wash'];
        $available_for_new_order = $agentsdetail['available_for_new_order'];
        $driver_license_expiration = $agentsdetail['driver_license_expiration'];
        $json = array(
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'date_of_birth' => $date_of_birth,
            'password' => $password,
            'street_address' => $street_address,
            'suite_apt' => $suite_apt,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'driver_license' => $driver_license,
            'proof_insurance' => $proof_insurance,
            'bank_account_number' => $bank_account_number,
            'business_license' => $business_license,
            'legally_eligible' => $legally_eligible,
            'routing_number' => $routing_number,
            'waterless_wash_product' => $waterless_wash_product,
            'own_vehicle' => $own_vehicle,
            'operate_area' => $operate_area,
            'work_schedule' => $work_schedule,
            'company_name' => $company_name,
            'operating_as' => $operating_as,
            'wash_experience' => $wash_experience,
            'image' => $image,
            'push_notifications' => $push_notifications,
            'email_alerts' => $email_alerts,
            'status' => $status,
            'account_status' => $account_status,
            'rating' => $rating,
            'total_wash' => $total_wash,
            'available_for_new_order' => $available_for_new_order,
            'driver_license_expiration' => $driver_license_expiration
        );
        echo json_encode($json);
        exit;
    }

    public function actionUpdateAgentsRecord() {

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
        $email = Yii::app()->request->getParam('email');
        $phone_number = Yii::app()->request->getParam('phone_number');
        $date_of_birth = Yii::app()->request->getParam('date_of_birth');
        $password = Yii::app()->request->getParam('password');
        $street_address = Yii::app()->request->getParam('street_address');
        $suite_apt = Yii::app()->request->getParam('suite_apt');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $routing_number = Yii::app()->request->getParam('routing_number');
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $company_name = Yii::app()->request->getParam('company_name');
        $operating_as = Yii::app()->request->getParam('operating_as');
        $wash_experience = Yii::app()->request->getParam('wash_experience');
        $push_notifications = Yii::app()->request->getParam('push_notifications');
        $email_alerts = Yii::app()->request->getParam('email_alerts');
        $status = Yii::app()->request->getParam('status');
        $account_status = Yii::app()->request->getParam('account_status');
        $rating = Yii::app()->request->getParam('rating');
        $total_wash = Yii::app()->request->getParam('total_wash');
        $available_for_new_order = Yii::app()->request->getParam('available_for_new_order');
        $driver_license_expiration = Yii::app()->request->getParam('driver_license_expiration');
        $driverlic = Yii::app()->request->getParam('driver_license');
        $proofins = Yii::app()->request->getParam('proof_insurance');
        $profilepic = Yii::app()->request->getParam('image');

        $data = array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone_number' => $phone_number, 'date_of_birth' => $date_of_birth, 'password' => $password, 'street_address' => $street_address, 'suite_apt' => $suite_apt, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'bank_account_number' => $bank_account_number, 'legally_eligible' => $legally_eligible, 'routing_number' => $routing_number, 'waterless_wash_product' => $waterless_wash_product, 'own_vehicle' => $own_vehicle, 'operate_area' => $operate_area, 'work_schedule' => $work_schedule, 'company_name' => $company_name, 'operating_as' => $operating_as, 'wash_experience' => $wash_experience, 'push_notifications' => $push_notifications, 'email_alerts' => $email_alerts, 'status' => $status, 'account_status' => $account_status, 'rating' => $rating, 'total_wash' => $total_wash, 'available_for_new_order' => $available_for_new_order, 'driver_license_expiration' => $driver_license_expiration);

        $arraypic = array('driver_license' => $driverlic, 'proof_insurance' => $proofins, 'image' => $profilepic);
        $data = array_filter($data);
        $updatesArrayPic = array_filter($arraypic);
        $upadtearray = array_merge($data, $updatesArrayPic);
        $id = Yii::app()->request->getParam('id');

        $update_agents = Agents::model()->updateAll($upadtearray, 'id=:id', array(':id' => $id));

        $result = 'true';
        $response = 'updated successfully';
        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionpreregisterold() {

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
        $email = Yii::app()->request->getParam('email');
        $phone = Yii::app()->request->getParam('phone');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $how_you_hear_mw = Yii::app()->request->getParam('how_you_hear_mw');
        $van_lease = '';
        $van_lease = Yii::app()->request->getParam('van_lease');

        $register_date = date("Y-m-d H:i:s");
        $result = 'false';
        $response = 'All fields are required';

        if ((isset($first_name) && !empty($first_name)) && (isset($last_name) && !empty($last_name)) && (isset($email) && !empty($email)) && (isset($phone) && !empty($phone)) && (isset($city) && !empty($city)) && (isset($state) && !empty($state)) && (isset($zipcode) && !empty($zipcode)) && (isset($how_you_hear_mw) && !empty($how_you_hear_mw))) {

            $agents_email_exists = PreRegWashers::model()->findByAttributes(array("email" => $email));
            if (count($agents_email_exists) > 0) {
                $result = 'false';
                $response = 'Email already exists';
            } else {
                $result = 'true';
                $response = 'Registration successful';

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'hear_mw_how' => $how_you_hear_mw,
                    'van_lease' => $van_lease,
                    'register_date' => $register_date
                );

                $model = new PreRegWashers;
                $model->attributes = $agentdata;
                $model->save(false);

                $md5 = md5(uniqid(rand(), true));
                $insert_id = Yii::app()->db->getLastInsertID();
                $token = $insert_id . "_" . $md5;
                PreRegWashers::model()->updateByPk($insert_id, array('register_token' => $token));
                $from = Vargas::Obj()->getAdminFromEmail();

                $message = "<h3>Dear " . $first_name . " " . $last_name . ",</h3>";
                $message .= "<p>Thanks for signing up. MobileWash will contact you to complete your registration.</p>";

//$message .= "<p><b><a href='https://www.mobilewash.com/register?uid=".$token."'>Complete Registration</a></b></p>";


                $message .= "<p style='height: 0px;'>&nbsp;</p>
               <p><b>Kind Regards,</b></p>
               <p style='margin-bottom: 0;'><b>The MobileWash Team</b></p>
               <p style='margin: 0; margin-top: 5px;'>www.mobilewash.com</p>
               <p style='margin-top: 5px;'>support@mobilewash.com</p>";


                Vargas::Obj()->SendMail($email, $from, $message, "Thank you for your registering with MobileWash");
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'token' => $token
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
        $email = Yii::app()->request->getParam('email');
        $phone = Yii::app()->request->getParam('phone');
        $password = Yii::app()->request->getParam('password');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $register_token = Yii::app()->request->getParam('register_token');
        $van_lease = '';
        $van_lease = Yii::app()->request->getParam('van_lease');
        $phone_verified = Yii::app()->request->getParam('phone_verified');
        $how_you_hear_mw = Yii::app()->request->getParam('how_you_hear_mw');

        $register_date = date("Y-m-d H:i:s");
        $result = 'false';
        $response = 'All fields are required';

        if ((isset($first_name) && !empty($first_name)) && (isset($last_name) && !empty($last_name)) && (isset($email) && !empty($email)) && (isset($phone) && !empty($phone)) && (isset($city) && !empty($city)) && (isset($state) && !empty($state))) {

            $agents_email_exists = PreRegWashers::model()->findByAttributes(array("email" => $email));
            $agents_phone_exists = PreRegWashers::model()->findByAttributes(array("phone" => $phone));

            if (count($agents_email_exists) > 0) {
                $result = 'false';
                $response = 'Email already exists';
            } else {
                $result = 'true';
                $response = 'Registration successful';

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'state' => $state,
                    'password' => '',
                    'van_lease' => $van_lease,
                    'register_token' => $register_token,
                    'phone_verified' => $phone_verified,
                    'hear_mw_how' => $how_you_hear_mw,
                    'register_date' => $register_date
                );

                $model = new PreRegWashers;
                $model->attributes = $agentdata;

                if ($model->save(false)) {


                    $response = $model->id;

                    $from = Vargas::Obj()->getAdminFromEmail();

                    $message = "<h3>Dear " . $first_name . " " . $last_name . ",</h3>";
                    $message .= "<p>Thanks for signing up. MobileWash will contact you to complete your registration.</p>";

//$message .= "<p><b><a href='https://www.mobilewash.com/register?uid=".$token."'>Complete Registration</a></b></p>";


                    $message .= "<p style='height: 0px;'>&nbsp;</p>
               <p><b>Kind Regards,</b></p>
               <p style='margin-bottom: 0;'><b>The MobileWash Team</b></p>
               <p style='margin: 0; margin-top: 5px;'>www.mobilewash.com</p>
               <p style='margin-top: 5px;'>support@mobilewash.com</p>";

                    $message2 = "<p>Name: " . $first_name . " " . $last_name . "</p>";
                    $message2 .= "<p>Email: " . $email . "</p>";
                    $message2 .= "<p>Phone: " . $phone . "</p>";
                    $message2 .= "<p>City: " . $city . "</p>";
                    $message2 .= "<p>State: " . $state . "</p>";
                    $message2 .= "<p>How heard about us: " . $how_you_hear_mw . "</p>";
                    $message2 .= "<p>Interested in leasing MobileWash van: " . $van_lease . "</p>";

                    $to = Vargas::Obj()->getAdminToEmail();

                    Vargas::Obj()->SendMail($email, $from, $message, "Thank you for your registering with MobileWash");
                    Vargas::Obj()->SendMail($to, $from, $message2, "New Washer Registration");
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

    public function actionresendprewasherverifyemail() {

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

        $result = 'false';
        $response = 'All fields are required';

        if ((isset($id) && !empty($id))) {

            $agents_exists = PreRegWashers::model()->findByAttributes(array("id" => $id));
            if (!count($agents_exists)) {
                $result = 'false';
                $response = 'Invalid washer id';
            } else {
                $result = 'true';
                $response = 'email sent';
                $from = Vargas::Obj()->getAdminFromEmail();

                $message = "<h3>Dear " . $agents_exists->first_name . " " . $agents_exists->last_name . ",</h3>";
                $message .= "<p>Thank you for pre-registering with MobileWash. Please use the link below to complete your registration.</p>";

                $message .= "<p><b><a href='" . ROOT_URL . "/register?uid=" . $agents_exists->register_token . "'>Complete Registration</a></b></p>";


                $message .= "<p style='height: 0px;'>&nbsp;</p>
               <p><b>Kind Regards,</b></p>
               <p style='margin-bottom: 0;'><b>The Mobilewash Team</b></p>
               <p style='margin: 0; margin-top: 5px;'>www.mobilewash.com</p>
               <p style='margin-top: 5px;'>support@mobilewash.com</p>";


                Vargas::Obj()->SendMail($agents_exists->email, $from, $message, "Thank you for your pre-registration with MobileWash");
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actiongetallprewashers() {

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
        $response = 'no washers found';
        $all_washers = array();
        $orderby = Yii::app()->request->getParam('order_by');
        if ($orderby == 'd_ASC')
            $washers_exists = PreRegWashers::model()->findAll(array("condition" => "trash_status = 0"), array('order' => 'register_date ASC'));
        else
            $washers_exists = PreRegWashers::model()->findAll(array("condition" => "trash_status = 0"), array('order' => 'register_date DESC'));
        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'all pre washers';

            foreach ($washers_exists as $ind => $washer) {
                $all_washers[$ind]['id'] = $washer->id;
                $all_washers[$ind]['email'] = $washer->email;
                $all_washers[$ind]['first_name'] = $washer->first_name;
                $all_washers[$ind]['last_name'] = $washer->last_name;
                $all_washers[$ind]['phone'] = $washer->phone;
                $all_washers[$ind]['city'] = $washer->city;
                $all_washers[$ind]['state'] = $washer->state;
                $all_washers[$ind]['zipcode'] = $washer->zipcode;
                $all_washers[$ind]['hear_mw_how'] = $washer->hear_mw_how;
                $all_washers[$ind]['register_date'] = $washer->register_date;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washers' => $all_washers
        );

        echo json_encode($json);
        die();
    }

    public function actiongetalltrashprewashers() {

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
        $response = 'no washers found';
        $all_washers = array();
        $orderby = Yii::app()->request->getParam('order_by');
        if ($orderby == 'd_ASC')
            $washers_exists = PreRegWashers::model()->findAll(array("condition" => "trash_status = 1"), array('order' => 'register_date ASC'));
        else
            $washers_exists = PreRegWashers::model()->findAll(array("condition" => "trash_status = 1"), array('order' => 'register_date DESC'));
        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'all pre washers';

            foreach ($washers_exists as $ind => $washer) {
                $all_washers[$ind]['id'] = $washer->id;
                $all_washers[$ind]['email'] = $washer->email;
                $all_washers[$ind]['first_name'] = $washer->first_name;
                $all_washers[$ind]['last_name'] = $washer->last_name;
                $all_washers[$ind]['phone'] = $washer->phone;
                $all_washers[$ind]['city'] = $washer->city;
                $all_washers[$ind]['state'] = $washer->state;
                $all_washers[$ind]['zipcode'] = $washer->zipcode;
                $all_washers[$ind]['hear_mw_how'] = $washer->hear_mw_how;
                $all_washers[$ind]['register_date'] = $washer->register_date;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washers' => $all_washers
        );

        echo json_encode($json);
        die();
    }

    public function actionrestoreprewasher() {

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
        $response = 'no washer found';
        $id = Yii::app()->request->getParam('id');
        $washers_exists = PreRegWashers::model()->findByPk($id);
        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'agents restore';

            PreRegWashers::model()->updateByPk($id, array('trash_status' => 0));
        }


        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actiongetallagents() {

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
        $response = 'no washers found';
        $all_washers = array();
        $total_entries = 0;
        $total_pages = 0;
        $limit = 0;
        $offset = 0;
        $page_number = 1;
        $limit = Yii::app()->request->getParam('limit');
        $page_number = Yii::app()->request->getParam('page_number');
        $limit = 20;
        $offset = ($page_number - 1) * $limit;

        /*          if(Yii::app()->request->getParam('type') == 'demo'){
          $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'demo' OR washer_position = ''")->queryAll();
          if($limit > 0) $washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'demo' OR washer_position = '' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
          }
          else{
          $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'real'")->queryAll();
          if($limit > 0) $washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'real' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
          } */

        if (Yii::app()->request->getParam('type') == 'demo') {
            $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'demo' OR washer_position = ''")->queryAll();
            if ($limit > 0)
                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'demo' OR washer_position = '' ORDER BY id DESC")->queryAll();
            //$washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'demo' OR washer_position = '' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
        }
        else {
            $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'real'")->queryAll();
            if ($limit > 0)
                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'real' ORDER BY id DESC")->queryAll();
//$washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'real' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
        }



        //$total_entries = $total_rows[0]['countid'];
        //if($total_entries > 0) $total_pages = ceil($total_entries / $limit);


        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'all agents';

            foreach ($washers_exists as $ind => $washer) {


                $cust_served_ids = array();
                $care_rating = 0;
                $total_returning_customers = 0;
                $totalwash = 0;
                $washer_registered_since = 0;
                $current_time = time(); // or your date as well
                $washer_created = strtotime($washer['created_date']);
                $datediff = $current_time - $washer_created;

                $washer_registered_since = round($datediff / (60 * 60 * 24));
                /* if($washer_registered_since > 30){

                  $totalwash_arr = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status=4 AND `agent_id` = '".$washer['id']."'")->queryAll();
                  $totalwash = count($totalwash_arr);

                  if(count($totalwash_arr)){
                  foreach($totalwash_arr as $agentwash){
                  $cust_served_ids[] = $agentwash['customer_id'];
                  }


                  $cust_served_ids = array_unique($cust_served_ids);

                  if(count($cust_served_ids) > 0){
                  foreach($cust_served_ids as $cid){
                  $cust_check = Customers::model()->findByAttributes(array("id"=>$cid));
                  $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$cid, 'status' => 4),array('order'=>'id DESC'));
                  if((count($cust_check)) && ($cust_check->is_first_wash == 1) && (!$cust_check->is_non_returning) && ($cust_last_wash_check->agent_id == $washer['id'])){
                  $total_returning_customers++;
                  }
                  }
                  }

                  if(count($cust_served_ids) > 0) {
                  $care_rating = ($total_returning_customers/$totalwash) * 100;
                  $care_rating = round($care_rating, 2);
                  }
                  }
                  else $care_rating = "N/A";
                  }
                  else{
                  $care_rating = "NEW";
                  } */
                $care_rating = $washer['care_rating'];
                $insurance_date = '';
                if (strtotime($washer['insurance_license_expiration']) > 0)
                    $insurance_date = date('m-d-Y', strtotime($washer['insurance_license_expiration']));
                else
                    $insurance_date = '';

                $all_washers[$ind]['id'] = $washer['id'];
                $all_washers[$ind]['real_washer_id'] = $washer['real_washer_id'];
                $all_washers[$ind]['email'] = $washer['email'];
                $all_washers[$ind]['first_name'] = $washer['first_name'];
                $all_washers[$ind]['last_name'] = $washer['last_name'];
                $all_washers[$ind]['phone_number'] = $washer['phone_number'];
                $all_washers[$ind]['city'] = $washer['city'];
                $all_washers[$ind]['state'] = $washer['state'];
                $all_washers[$ind]['zipcode'] = $washer['zipcode'];
                $all_washers[$ind]['phone_verify_code'] = $washer['phone_verify_code'];
                $all_washers[$ind]['rating'] = $washer['rating'];
                $all_washers[$ind]['care_rating'] = $care_rating;
                $all_washers[$ind]['block_washer'] = ($washer['block_washer'] == 1) ? 'Yes' : 'No';
                $all_washers[$ind]['total_wash'] = $washer['total_wash'];
                $all_washers[$ind]['bt_submerchant_id'] = $washer['bt_submerchant_id'];
                $all_washers[$ind]['status'] = $washer['status'];
                $all_washers[$ind]['insurance_exp_date'] = $insurance_date;
                $all_washers[$ind]['created_date'] = $washer['created_date'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washers' => $all_washers,
                //'total_entries' => $total_entries,
                //'total_pages' => $total_pages
        );

        echo json_encode($json);
        die();
    }

    public function actionUnlimited_schedule_range() {

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
        $response = 'no washers found';
        $all_washers = array();
        $total_entries = 0;
        $total_pages = 0;
        $limit = 0;
        $offset = 0;
        $page_number = 1;
        $limit = Yii::app()->request->getParam('limit');
        $page_number = Yii::app()->request->getParam('page_number');
        $limit = 20;
        $offset = ($page_number - 1) * $limit;

        /*          if(Yii::app()->request->getParam('type') == 'demo'){
          $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'demo' OR washer_position = ''")->queryAll();
          if($limit > 0) $washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'demo' OR washer_position = '' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
          }
          else{
          $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE washer_position = 'real'")->queryAll();
          if($limit > 0) $washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'real' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
          } */
        $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE (unlimited_schedule_range = '1') ORDER BY id DESC")->queryAll();
//        if (Yii::app()->request->getParam('type') == 'demo') {
//            $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE (washer_position = 'demo' OR washer_position = '') AND (unlimited_schedule_range = '1') ")->queryAll();
//            if ($limit > 0)
//                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE (washer_position = 'demo' OR washer_position = '') AND (unlimited_schedule_range = '1') ORDER BY id DESC")->queryAll();
//            //$washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'demo' OR washer_position = '' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
//        }
//        else {
//            $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE (washer_position = 'real') AND (unlimited_schedule_range = '1') ")->queryAll();
//            if ($limit > 0)
//                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE (washer_position = 'real') AND (unlimited_schedule_range = '1') ORDER BY id DESC")->queryAll();
////$washers_exists =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE washer_position = 'real' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
//        }
        //$total_entries = $total_rows[0]['countid'];
        //if($total_entries > 0) $total_pages = ceil($total_entries / $limit);


        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'all agents';

            foreach ($washers_exists as $ind => $washer) {


                $cust_served_ids = array();
                $care_rating = 0;
                $total_returning_customers = 0;
                $totalwash = 0;
                $washer_registered_since = 0;
                $current_time = time(); // or your date as well
                $washer_created = strtotime($washer['created_date']);
                $datediff = $current_time - $washer_created;

                $washer_registered_since = round($datediff / (60 * 60 * 24));
                /* if($washer_registered_since > 30){

                  $totalwash_arr = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status=4 AND `agent_id` = '".$washer['id']."'")->queryAll();
                  $totalwash = count($totalwash_arr);

                  if(count($totalwash_arr)){
                  foreach($totalwash_arr as $agentwash){
                  $cust_served_ids[] = $agentwash['customer_id'];
                  }


                  $cust_served_ids = array_unique($cust_served_ids);

                  if(count($cust_served_ids) > 0){
                  foreach($cust_served_ids as $cid){
                  $cust_check = Customers::model()->findByAttributes(array("id"=>$cid));
                  $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$cid, 'status' => 4),array('order'=>'id DESC'));
                  if((count($cust_check)) && ($cust_check->is_first_wash == 1) && (!$cust_check->is_non_returning) && ($cust_last_wash_check->agent_id == $washer['id'])){
                  $total_returning_customers++;
                  }
                  }
                  }

                  if(count($cust_served_ids) > 0) {
                  $care_rating = ($total_returning_customers/$totalwash) * 100;
                  $care_rating = round($care_rating, 2);
                  }
                  }
                  else $care_rating = "N/A";
                  }
                  else{
                  $care_rating = "NEW";
                  } */
                $care_rating = $washer['care_rating'];
                $insurance_date = '';
                if (strtotime($washer['insurance_license_expiration']) > 0)
                    $insurance_date = date('m-d-Y', strtotime($washer['insurance_license_expiration']));
                else
                    $insurance_date = '';

                $all_washers[$ind]['id'] = $washer['id'];
                $all_washers[$ind]['real_washer_id'] = $washer['real_washer_id'];
                $all_washers[$ind]['email'] = $washer['email'];
                $all_washers[$ind]['first_name'] = $washer['first_name'];
                $all_washers[$ind]['last_name'] = $washer['last_name'];
                $all_washers[$ind]['phone_number'] = $washer['phone_number'];
                $all_washers[$ind]['city'] = $washer['city'];
                $all_washers[$ind]['state'] = $washer['state'];
                $all_washers[$ind]['zipcode'] = $washer['zipcode'];
                $all_washers[$ind]['phone_verify_code'] = $washer['phone_verify_code'];
                $all_washers[$ind]['rating'] = $washer['rating'];
                $all_washers[$ind]['care_rating'] = $care_rating;
                $all_washers[$ind]['block_washer'] = ($washer['block_washer'] == 1) ? 'Yes' : 'No';
                $all_washers[$ind]['total_wash'] = $washer['total_wash'];
                $all_washers[$ind]['bt_submerchant_id'] = $washer['bt_submerchant_id'];
                $all_washers[$ind]['status'] = $washer['status'];
                $all_washers[$ind]['insurance_exp_date'] = $insurance_date;
                $all_washers[$ind]['created_date'] = $washer['created_date'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washers' => $all_washers,
                //'total_entries' => $total_entries,
                //'total_pages' => $total_pages
        );

        echo json_encode($json);
        die();
    }

    public function actionsearchagents() {

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
        $response = 'no washers found';
        $all_washers = array();
        $query = Yii::app()->request->getParam('query');
        $query_str = '';
        $limit = 0;
        $limit = Yii::app()->request->getParam('limit');
        $total_pages = 0;
        $search_area = Yii::app()->request->getParam('search_area');
        $agent_query = '';

        $page_number = 1;
        if (Yii::app()->request->getParam('page_number'))
            $page_number = Yii::app()->request->getParam('page_number');
        $offset = ($page_number - 1) * $limit;

        $limit_str = '';
        $total_count = 0;
        if ($limit && ($limit != 'none')) {
            $limit_str = " LIMIT " . $limit . " OFFSET " . $offset;
        }

        if ($search_area == "Washer Name") {
//            $query_arr = explode(" ", $query);
//            $query = join("', '", $query_arr);
            $agent_query = "agentname LIKE '%$query%' OR first_name LIKE '%$query%' OR last_name LIKE '%$query%' ";
//            if (count($query_arr) > 1)
//                $agent_query = "(first_name IN ('$query') OR last_name IN ('$query')) ";
//            else
//                $agent_query = "(first_name LIKE :query OR last_name LIKE :query) ";
        }
        // && (count($query_arr) > 1
        if ($search_area == "Washer Phone")
            $agent_query = "(phone_number LIKE :query) ";
        if ($search_area == "Washer Badge")
            $agent_query = "(real_washer_id LIKE :query) ";
        if (($query) || is_numeric($query)) {

            if (($search_area == "Washer Name"))
                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE " . $agent_query . "ORDER BY id DESC" . $limit_str)->queryAll();
            else
                $washers_exists = Yii::app()->db->createCommand("SELECT * FROM agents WHERE " . $agent_query . "ORDER BY id DESC" . $limit_str)->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();

            if (($search_area == "Washer Name"))
                $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE " . $agent_query . "ORDER BY id DESC")->queryAll();
            else
                $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM agents WHERE " . $agent_query . "ORDER BY id DESC")->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();

            $total_count = $total_rows[0]['countid'];
            if ($total_count > 0)
                $total_pages = ceil($total_count / $limit);
        }

        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'all agents';

            foreach ($washers_exists as $ind => $washer) {

                $all_washers[$ind]['id'] = $washer['id'];
                $all_washers[$ind]['real_washer_id'] = $washer['real_washer_id'];
                $all_washers[$ind]['email'] = $washer['email'];
                $all_washers[$ind]['first_name'] = $washer['first_name'];
                $all_washers[$ind]['last_name'] = $washer['last_name'];
                $all_washers[$ind]['phone_number'] = $washer['phone_number'];
                $all_washers[$ind]['phone_verify_code'] = $washer['phone_verify_code'];
                $all_washers[$ind]['city'] = $washer['city'];
                $all_washers[$ind]['state'] = $washer['state'];
                $all_washers[$ind]['zipcode'] = $washer['zipcode'];
                $all_washers[$ind]['rating'] = $washer['rating'];
                $all_washers[$ind]['care_rating'] = $washer['care_rating'];
                $all_washers[$ind]['total_wash'] = $washer['total_wash'];
                $all_washers[$ind]['bt_submerchant_id'] = $washer['bt_submerchant_id'];
                $all_washers[$ind]['status'] = $washer['status'];
                $all_washers[$ind]['insurance_exp_date'] = $washer['insurance_license_expiration'];
                $all_washers[$ind]['created_date'] = $washer['created_date'];
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washers' => $all_washers,
            'total_washers' => $total_count,
            'total_pages' => $total_pages
        );

        echo json_encode($json);
        die();
    }

    public function actionprewasherdetails() {

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
        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $agents_email_exists = PreRegWashers::model()->findByAttributes(array("id" => $id));
            if (!count($agents_email_exists)) {
                $result = 'false';
                $response = 'Invalid washer id';
            } else {
                $result = 'true';
                $response = 'washer details';

                $agentdata = array(
                    'id' => $agents_email_exists->id,
                    'first_name' => $agents_email_exists->first_name,
                    'last_name' => $agents_email_exists->last_name,
                    'email' => $agents_email_exists->email,
                    'phone' => $agents_email_exists->phone,
                    'city' => $agents_email_exists->city,
                    'state' => $agents_email_exists->state,
                    'zipcode' => $agents_email_exists->zipcode,
                    'register_date' => $agents_email_exists->register_date,
                    'register_status' => $agents_email_exists->register_status,
                    'register_token' => $agents_email_exists->register_token,
                    'phone_verify_code' => $agents_email_exists->phone_verify_code,
                    'phone_verified' => $agents_email_exists->phone_verified,
                    'date_of_birth' => $agents_email_exists->date_of_birth,
                    'street_address' => $agents_email_exists->street_address,
                    'suite_apt' => $agents_email_exists->suite_apt,
                    'legally_eligible' => $agents_email_exists->legally_eligible,
                    'own_vehicle' => $agents_email_exists->own_vehicle,
                    'waterless_wash_product' => $agents_email_exists->waterless_wash_product,
                    'operate_area' => $agents_email_exists->operate_area,
                    'work_schedule' => $agents_email_exists->work_schedule,
                    'operating_as' => $agents_email_exists->operating_as,
                    'company_name' => $agents_email_exists->company_name,
                    'wash_experience' => $agents_email_exists->wash_experience,
                    'driver_license' => $agents_email_exists->driver_license,
                    'liable_insurance' => $agents_email_exists->liable_insurance,
                    'insurance_expire_date' => $agents_email_exists->insurance_expire_date,
                    'ssn_image' => $agents_email_exists->ssn_image,
                    'ssn_expire_date' => $agents_email_exists->ssn_expire_date,
                    'routing_number' => $agents_email_exists->routing_number,
                    'bank_account_number' => $agents_email_exists->bank_account_number,
                    'bank_name' => $agents_email_exists->bank_name,
                    'bank_account_name' => $agents_email_exists->bank_account_name,
                    'hear_mw_how' => $agents_email_exists->hear_mw_how,
                    'vehicle_front_img' => $agents_email_exists->vehicle_front_img,
                    'vehicle_back_img' => $agents_email_exists->vehicle_back_img,
                    'vehicle_left_img' => $agents_email_exists->vehicle_left_img,
                    'vehicle_right_img' => $agents_email_exists->vehicle_right_img,
                    'equipment_img' => $agents_email_exists->equipment_img,
                    'vehicle_insurance' => $agents_email_exists->vehicle_insurance,
                    'vehicle_register' => $agents_email_exists->vehicle_register,
                    'cl_insurance' => $agents_email_exists->cl_insurance,
                    'w9' => $agents_email_exists->w9,
                    'pro_service_agree_sign' => $agents_email_exists->pro_service_agree_sign,
                    'pro_service_agree' => $agents_email_exists->pro_service_agree,
                    'security_notice_agree' => $agents_email_exists->security_notice_agree,
                    'rating_system_agree' => $agents_email_exists->rating_system_agree,
                    'terms_of_use_agree' => $agents_email_exists->terms_of_use_agree,
                    'privacy_policy_agree' => $agents_email_exists->privacy_policy_agree,
                );
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'washer_details' => $agentdata
        );

        echo json_encode($json);
        die();
    }

    public function actionprewasheraccount() {

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
        $register_token = Yii::app()->request->getParam('register_token');
        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id)) && (isset($register_token) && !empty($register_token))) {

            $agents_email_exists = PreRegWashers::model()->findByAttributes(array("id" => $id, "register_token" => $register_token));
            if (!count($agents_email_exists)) {
                $result = 'false';
                $response = 'Invalid washer';
            } else {
                $result = 'true';
                $response = 'washer details';

                $agentdata = array(
                    'id' => $agents_email_exists->id,
                    'first_name' => $agents_email_exists->first_name,
                    'last_name' => $agents_email_exists->last_name,
                    'email' => $agents_email_exists->email,
                    'phone' => $agents_email_exists->phone,
                    'city' => $agents_email_exists->city,
                    'state' => $agents_email_exists->state,
                    'zipcode' => $agents_email_exists->zipcode,
                    'register_date' => $agents_email_exists->register_date,
                    'register_status' => $agents_email_exists->register_status,
                    'register_token' => $agents_email_exists->register_token,
                    'phone_verify_code' => $agents_email_exists->phone_verify_code,
                    'phone_verified' => $agents_email_exists->phone_verified,
                    'date_of_birth' => $agents_email_exists->date_of_birth,
                    'street_address' => $agents_email_exists->street_address,
                    'suite_apt' => $agents_email_exists->suite_apt,
                    'legally_eligible' => $agents_email_exists->legally_eligible,
                    'own_vehicle' => $agents_email_exists->own_vehicle,
                    'waterless_wash_product' => $agents_email_exists->waterless_wash_product,
                    'operate_area' => $agents_email_exists->operate_area,
                    'work_schedule' => $agents_email_exists->work_schedule,
                    'operating_as' => $agents_email_exists->operating_as,
                    'company_name' => $agents_email_exists->company_name,
                    'wash_experience' => $agents_email_exists->wash_experience,
                    'driver_license' => $agents_email_exists->driver_license,
                    'liable_insurance' => $agents_email_exists->liable_insurance,
                    'insurance_expire_date' => $agents_email_exists->insurance_expire_date,
                    'ssn_image' => $agents_email_exists->ssn_image,
                    'ssn_expire_date' => $agents_email_exists->ssn_expire_date,
                    'routing_number' => $agents_email_exists->routing_number,
                    'bank_account_number' => $agents_email_exists->bank_account_number,
                    'bank_name' => $agents_email_exists->bank_name,
                    'bank_account_name' => $agents_email_exists->bank_account_name,
                    'hear_mw_how' => $agents_email_exists->hear_mw_how,
                    'vehicle_front_img' => $agents_email_exists->vehicle_front_img,
                    'vehicle_back_img' => $agents_email_exists->vehicle_back_img,
                    'vehicle_left_img' => $agents_email_exists->vehicle_left_img,
                    'vehicle_right_img' => $agents_email_exists->vehicle_right_img,
                    'equipment_img' => $agents_email_exists->equipment_img,
                    'equipment_images' => $agents_email_exists->equipment_images,
                    'vehicle_insurance' => $agents_email_exists->vehicle_insurance,
                    'vehicle_register' => $agents_email_exists->vehicle_register,
                    'cl_insurance' => $agents_email_exists->cl_insurance,
                    'w9' => $agents_email_exists->w9,
                    'taxform_expire_date' => $agents_email_exists->taxform_expire_date,
                    'pro_service_agree_sign' => $agents_email_exists->pro_service_agree_sign,
                    'pro_service_agree' => $agents_email_exists->pro_service_agree,
                    'security_notice_agree' => $agents_email_exists->security_notice_agree,
                    'rating_system_agree' => $agents_email_exists->rating_system_agree,
                    'terms_of_use_agree' => $agents_email_exists->terms_of_use_agree,
                    'privacy_policy_agree' => $agents_email_exists->privacy_policy_agree,
                );
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'washer_details' => $agentdata
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

        $agentid = Yii::app()->request->getParam('id');
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

        $digits = 4;
        $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $update_response = Yii::app()->db->createCommand("UPDATE pre_registered_washers SET phone_verify_code='$randum_number' WHERE id = :id ")
                ->bindValue(':id', $agentid, PDO::PARAM_STR)
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
                'response' => 'send 4 digit code.'
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

        $agentid = Yii::app()->request->getParam('id');
        $sortcode = Yii::app()->request->getParam('verify_code');
        $model = new PreRegWashers;
        $matchcode = PreRegWashers::model()->findByAttributes(array("phone_verify_code" => $sortcode, "id" => $agentid));
        if (!empty($matchcode)) {
            if ($matchcode->phone_verified != 1) {
                $update_response = Yii::app()->db->createCommand("UPDATE pre_registered_washers SET phone_verified='1' WHERE id = :id AND phone_verify_code = :phone_verify_code ")
                        ->bindValue(':id', $agentid, PDO::PARAM_STR)
                        ->bindValue(':phone_verify_code', $sortcode, PDO::PARAM_STR)
                        ->execute();
                $data = array(
                    'result' => 'true',
                    'response' => 'Congratulation, Your phone is verified.'
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

    public function actionVerifyPhoneWasher() {

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

        $washerid = Yii::app()->request->getParam('id');
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

        $digits = 4;
        $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $update_response = Yii::app()->db->createCommand("UPDATE agents SET phone_verify_code='$randum_number' WHERE id = :id ")->bindValue(':id', $washerid, PDO::PARAM_STR)->execute();
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
                'response' => 'send 4 digit code.'
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

    public function actionConfirmPhoneWasher() {

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

        $washerid = Yii::app()->request->getParam('id');
        $sortcode = Yii::app()->request->getParam('verify_code');
        $model = new Agents;
        $matchcode = Agents::model()->findByAttributes(array("phone_verify_code" => $sortcode, "id" => $washerid));
        if (!empty($matchcode)) {
            if ($matchcode->phone_verified != 1) {
                $update_response = Yii::app()->db->createCommand("UPDATE agents SET phone_verified='1' WHERE id = :id AND phone_verify_code = :phone_verify_code ")
                        ->bindValue(':id', $washerid, PDO::PARAM_STR)
                        ->bindValue(':phone_verify_code', $sortcode, PDO::PARAM_STR)
                        ->execute();
                $data = array(
                    'result' => 'true',
                    'response' => 'Congratulation, Your phone is verified.'
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

    public function actionWasherupdate() {
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
        $email = Yii::app()->request->getParam('email');
        $phone_number = Yii::app()->request->getParam('phone_number');
        $agent_id = Yii::app()->request->getParam('washer_id');

        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }

        $upadtearray = array();
        if ($first_name != '') {
            $upadtearray['first_name'] = $firstname;
        }
        if ($last_name != '') {
            $upadtearray['last_name'] = $last_name;
        }
        if ($email != '') {
            $upadtearray['email'] = $email;
        }
        if ($phone_number != '') {
            $upadtearray['phone_number'] = $phone_number;
        }
        if (count($upadtearray) > 0) {
            $update_agents = Agents::model()->updateAll($upadtearray, 'id=:id', array(':id' => $agent_id));
        }
        //if($update_agents){
        $result = 'true';
        $response = $upadtearray;
        /* }
          else{
          $result = 'false';
          $response = 'Some thing worng';

          } */
        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionprewasherupdate() {

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
        $zipcode = '';
        $zipcode = Yii::app()->request->getParam('zipcode');
        $hear_mw_how = '';
        $hear_mw_how = Yii::app()->request->getParam('hear_mw_how');
        $dob = '';
        $dob = Yii::app()->request->getParam('date_of_birth');
        $staddr = '';
        $staddr = Yii::app()->request->getParam('street_address');
        $suiteno = '';
        $suiteno = Yii::app()->request->getParam('suite_apt');
        $legally_eligible = '';
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $own_vehicle = '';
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $waterless_wash_product = '';
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $operate_area = '';
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = '';
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $operationmethod = '';
        $operationmethod = Yii::app()->request->getParam('operating_as');
        $companyname = '';
        $companyname = Yii::app()->request->getParam('company_name');
        $wash_exp = '';
        $wash_exp = Yii::app()->request->getParam('wash_experience');
        $driver_license = '';
        $driver_license = Yii::app()->request->getParam('driver_license');
        $liable_insurance = '';
        $liable_insurance = Yii::app()->request->getParam('liable_insurance');
        $insurance_expire_date = '';
        $insurance_expire_date = Yii::app()->request->getParam('insurance_expire_date');
        $ssn_image = '';
        $ssn_image = Yii::app()->request->getParam('ssn_image');
        $ssn_expire_date = '';
        $ssn_expire_date = Yii::app()->request->getParam('ssn_expire_date');
        $routing_number = '';
        $routing_number = Yii::app()->request->getParam('routing_number');
        $bank_account_number = '';
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $bank_name = '';
        $bank_name = Yii::app()->request->getParam('bank_name');
        $bank_account_name = '';
        $bank_account_name = Yii::app()->request->getParam('bank_account_name');
        $register_status = '';
        $register_status = Yii::app()->request->getParam('register_status');
        $vehicle_front_img = '';
        $vehicle_front_img = Yii::app()->request->getParam('vehicle_front_img');
        $vehicle_left_img = '';
        $vehicle_left_img = Yii::app()->request->getParam('vehicle_left_img');
        $vehicle_right_img = '';
        $vehicle_right_img = Yii::app()->request->getParam('vehicle_right_img');
        $vehicle_back_img = '';
        $vehicle_back_img = Yii::app()->request->getParam('vehicle_back_img');
        $equipment_img = '';
        $equipment_img = Yii::app()->request->getParam('equipment_img');
        $equipment_imgs = '';
        $equipment_imgs = Yii::app()->request->getParam('equipment_images');
        $equipment_images_noadd = '';
        $equipment_images_noadd = Yii::app()->request->getParam('equipment_images_noadd');
        $vehicle_insurance = '';
        $vehicle_insurance = Yii::app()->request->getParam('vehicle_insurance');
        $vehicle_register = '';
        $vehicle_register = Yii::app()->request->getParam('vehicle_register');
        $cl_insurance = '';
        $cl_insurance = Yii::app()->request->getParam('cl_insurance');
        $w9 = '';
        $w9 = Yii::app()->request->getParam('w9');
        $taxform_expire_date = '';
        $taxform_expire_date = Yii::app()->request->getParam('taxform_expire_date');
        $pro_service_agree_sign = '';
        $pro_service_agree_sign = Yii::app()->request->getParam('pro_service_agree_sign');
        $pro_service_agree = '';
        $pro_service_agree = Yii::app()->request->getParam('pro_service_agree');
        $security_notice_agree = '';
        $security_notice_agree = Yii::app()->request->getParam('security_notice_agree');
        $rating_system_agree = '';
        $rating_system_agree = Yii::app()->request->getParam('rating_system_agree');
        $privacy_policy_agree = '';
        $privacy_policy_agree = Yii::app()->request->getParam('privacy_policy_agree');
        $terms_of_use_agree = '';
        $terms_of_use_agree = Yii::app()->request->getParam('terms_of_use_agree');


        $result = 'false';
        $response = 'Please fillup all fields';

        if ((isset($id) && !empty($id))) {

            $user_exists = PreRegWashers::model()->findByPk($id);
            if (!$user_exists) {
                $result = 'false';
                $response = "Sorry, you are not a registered washer. Please register first.";
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

                if (!$zipcode) {
                    $zipcode = $user_exists->zipcode;
                }

                if (!$hear_mw_how) {
                    $hear_mw_how = $user_exists->hear_mw_how;
                }

                if (!$dob) {
                    $dob = $user_exists->date_of_birth;
                }

                if (!$staddr) {
                    $staddr = $user_exists->street_address;
                }

                if (!$suiteno) {
                    $suiteno = $user_exists->suite_apt;
                }

                if (!$legally_eligible) {
                    $legally_eligible = $user_exists->legally_eligible;
                }

                if (!$own_vehicle) {
                    $own_vehicle = $user_exists->own_vehicle;
                }

                if (!$waterless_wash_product) {
                    $waterless_wash_product = $user_exists->waterless_wash_product;
                }

                if (!$operate_area) {
                    $operate_area = $user_exists->operate_area;
                }

                if (!$work_schedule) {
                    $work_schedule = $user_exists->work_schedule;
                }

                if (!$operationmethod) {
                    $operationmethod = $user_exists->operating_as;
                }

                if (!$companyname) {
                    $companyname = $user_exists->company_name;
                }

                if (!$wash_exp) {
                    $wash_exp = $user_exists->wash_experience;
                }

                if (!$driver_license) {
                    $driver_license = $user_exists->driver_license;
                }

                if (!$liable_insurance) {
                    $liable_insurance = $user_exists->liable_insurance;
                }

                if (!$insurance_expire_date) {
                    $insurance_expire_date = $user_exists->insurance_expire_date;
                }

                if (!$ssn_image) {
                    $ssn_image = $user_exists->ssn_image;
                }

                if (!$ssn_expire_date) {
                    $ssn_expire_date = $user_exists->ssn_expire_date;
                }

                if (!$routing_number) {
                    $routing_number = $user_exists->routing_number;
                }

                if (!$bank_account_number) {
                    $bank_account_number = $user_exists->bank_account_number;
                }

                if (!$bank_name) {
                    $bank_name = $user_exists->bank_name;
                }

                if (!$bank_account_name) {
                    $bank_account_name = $user_exists->bank_account_name;
                }

                if (!$register_status) {
                    $register_status = $user_exists->register_status;
                }

                if (!$vehicle_front_img) {
                    $vehicle_front_img = $user_exists->vehicle_front_img;
                }

                if (!$vehicle_back_img) {
                    $vehicle_back_img = $user_exists->vehicle_back_img;
                }

                if (!$vehicle_left_img) {
                    $vehicle_left_img = $user_exists->vehicle_left_img;
                }

                if (!$vehicle_right_img) {
                    $vehicle_right_img = $user_exists->vehicle_right_img;
                }

                if (!$equipment_img) {
                    $equipment_img = $user_exists->equipment_img;
                }

                if (!$equipment_imgs) {
                    $equipment_imgs = $user_exists->equipment_images;
                } else {
                    if ($user_exists->equipment_images && (!$equipment_images_noadd))
                        $equipment_imgs = $user_exists->equipment_images . "|" . $equipment_imgs;
                }

                if (!$vehicle_insurance) {
                    $vehicle_insurance = $user_exists->vehicle_insurance;
                }

                if (!$vehicle_register) {
                    $vehicle_register = $user_exists->vehicle_register;
                }

                if (!$cl_insurance) {
                    $cl_insurance = $user_exists->cl_insurance;
                }

                if (!$w9) {
                    $w9 = $user_exists->w9;
                }

                if (!$taxform_expire_date) {
                    $taxform_expire_date = $user_exists->taxform_expire_date;
                }


                if (!$pro_service_agree_sign) {
                    $pro_service_agree_sign = $user_exists->pro_service_agree_sign;
                }

                if (!$pro_service_agree) {
                    $pro_service_agree = $user_exists->pro_service_agree;
                }

                if (!$security_notice_agree) {
                    $security_notice_agree = $user_exists->security_notice_agree;
                }

                if (!$rating_system_agree) {
                    $rating_system_agree = $user_exists->rating_system_agree;
                }

                if (!$privacy_policy_agree) {
                    $privacy_policy_agree = $user_exists->privacy_policy_agree;
                }

                if (!$terms_of_use_agree) {
                    $terms_of_use_agree = $user_exists->terms_of_use_agree;
                }

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'hear_mw_how' => $hear_mw_how,
                    'date_of_birth' => $dob,
                    'street_address' => $staddr,
                    'suite_apt' => $suiteno,
                    'legally_eligible' => $legally_eligible,
                    'own_vehicle' => $own_vehicle,
                    'waterless_wash_product' => $waterless_wash_product,
                    'operate_area' => $operate_area,
                    'work_schedule' => $work_schedule,
                    'operating_as' => $operationmethod,
                    'company_name' => $companyname,
                    'wash_experience' => $wash_exp,
                    'driver_license' => $driver_license,
                    'liable_insurance' => $liable_insurance,
                    'insurance_expire_date' => $insurance_expire_date,
                    'ssn_image' => $ssn_image,
                    'ssn_expire_date' => $ssn_expire_date,
                    'register_status' => $register_status,
                    'vehicle_front_img' => $vehicle_front_img,
                    'vehicle_back_img' => $vehicle_back_img,
                    'vehicle_left_img' => $vehicle_left_img,
                    'vehicle_right_img' => $vehicle_right_img,
                    'equipment_img' => $equipment_img,
                    'equipment_images' => $equipment_imgs,
                    'vehicle_insurance' => $vehicle_insurance,
                    'vehicle_register' => $vehicle_register,
                    'cl_insurance' => $cl_insurance,
                    'w9' => $w9,
                    'taxform_expire_date' => $taxform_expire_date,
                    'pro_service_agree_sign' => $pro_service_agree_sign,
                    'pro_service_agree' => $pro_service_agree,
                    'security_notice_agree' => $security_notice_agree,
                    'rating_system_agree' => $rating_system_agree,
                    'privacy_policy_agree' => $privacy_policy_agree,
                    'terms_of_use_agree' => $terms_of_use_agree,
                );

                PreRegWashers::model()->updateByPk($id, $agentdata);
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionGetWasherName() {

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

        $user_id = Yii::app()->request->getParam('user_id');
        $model = new PreRegWashers;
        $washer = PreRegWashers::model()->findByAttributes(array("id" => $user_id));
        $name = ucfirst($washer->first_name) . ' ' . ucfirst($washer->last_name);
        $company_name = $washer->company_name;
        if (!empty($company_name)) {
            $company = $company_name;
        } else {
            $company = $name;
        }
        $data = array(
            'result' => 'false',
            'response' => 'Washer name',
            'name' => $name,
            'company_name' => $company
        );
        echo json_encode($data);
        die();
    }

// new agents in a Year
    public function Actionwasheryearwise() {

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
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `agents` WHERE created_date BETWEEN '$start_year' AND '$end_year' ")->queryAll();
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
// new agents in a week
    public function Actionwasherweekwise() {

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
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `agents` WHERE created_date BETWEEN '$start_date' AND '$end_date' ")->queryAll();
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
    // new agents in a month
    public function Actionwashermonthwise() {

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

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `agents` WHERE created_date BETWEEN '$irstdate' AND '$lastdate' ")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$irstdate' AND '$lastdate' ".$month.'<br />';


            foreach ($request as $details) {
                $array[$month] = $details['cnt'];
            }
        }

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
    // new pre washers in a month
    public function Actionprewashermonthwise() {

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

            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_washers` WHERE register_date BETWEEN '$irstdate' AND '$lastdate' ")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `customers` WHERE created_date BETWEEN '$irstdate' AND '$lastdate' ".$month.'<br />';


            foreach ($request as $details) {
                $array[$month] = $details['cnt'];
            }
        }

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// revenue Year wise

    public function ActionCompRevenueYearWise() {

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

            $wash_request = Yii::app()->db->createCommand("SELECT company_total FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$start_year' AND '$end_year'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$year] += $wash_details['company_total'];
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

    public function ActionCompRevenueWeekWise() {

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

            $wash_request = Yii::app()->db->createCommand("SELECT company_total FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$start_date' AND '$end_date'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$day] += $wash_details['company_total'];
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

    public function ActionCompRevenueMonthWise() {

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
            $wash_request = Yii::app()->db->createCommand("SELECT company_total FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate'  ")->queryAll();
            if (!empty($wash_request)) {
                //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';
                foreach ($wash_request as $wash_details) {
                    $array[$month] += $wash_details['company_total'];
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
    // new pre washers in a Year
    public function Actionprewasheryearwise() {

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
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_washers` WHERE register_date BETWEEN '$start_year' AND '$end_year' ")->queryAll();
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
// new pre washers in a week
    public function Actionprewasherweekwise() {

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
            $diff = date("d", strtotime($i . " days ago"));
//echo $day.'<br />';
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_washers` WHERE register_date BETWEEN '$start_date' AND '$end_date' ")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE created_date BETWEEN '$start_year' AND '$end_year' " .'<br />';
            foreach ($request as $details) {
                $array[$day] = $details['cnt'];
            }
        }


        $json = $array;
        echo json_encode($json);
        die();
    }

    // end
// new pre washers per day
    public function Actionprewasherperday() {

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
            //echo $i.'<br />';
            $start_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '00:00:00';
            $end_date = date("Y-m-d", strtotime($i . " days ago")) . ' ' . '23:59:59';

            $day = date("D", strtotime($i . " days ago"));
            $day_date = date("d", strtotime($i . " days ago"));
            $request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `pre_registered_washers` WHERE register_date BETWEEN '$start_date' AND '$end_date' ")->queryAll();
            //echo "SELECT COUNT(*) as cnt FROM `washing_requests` WHERE created_date BETWEEN '$start_year' AND '$end_year' " .'<br />';
            foreach ($request as $details) {
                $array[$day] = $details['cnt'];
            }
            //echo $day.'xxxxx'.$day_date.'<br />';
        }

//exit;

        $json = $array;
        echo json_encode($json);
        die();
    }

    // end


    public function actionGetAgentLoginOrderDetail() {

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

        $agentsid = Yii::app()->request->getParam('agentID');
        $washingid = Yii::app()->request->getParam('washingid');
        $agentsdetail = Agents::model()->findByAttributes(array("id" => $agentsid));
        $lastorder = Yii::app()->db->createCommand("SELECT created_date, complete_order FROM washing_requests WHERE agent_id = :agent_id AND status = '4' ORDER BY id DESC LIMIT 0,1")->bindValue(':agent_id', $agentsid, PDO::PARAM_STR)->queryAll();
        $image = $agentsdetail->image;
        $lastactive = $agentsdetail->last_active;
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
            'image' => $image,
            'last_login' => $login,
            'login_time' => $lastlogintime,
            'complte_order' => $complte_order,
            'lastorder' => $order
        );
        echo json_encode($json);
        die();
    }

    public function actionagentmapview() {

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


            $customer_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $client->id));

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
            $onlineclients[$key] = $jsononlineclient;
        }


        //penidng orders
        $pending_orders = Washingrequests::model()->findAllByAttributes(array("status" => '0'));



        $pendingorders = array();
        foreach ($pending_orders as $pendings_orders) {

            $customers_detail = Customers::model()->findByAttributes(array("id" => $pendings_orders->customer_id));


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
            $jsonpending['rating'] = $customers_detail->rating;
            $jsonpending['created_date'] = $pendings_orders->created_date;
            $pendingorders[$key] = $jsonpending;
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
        $clientstatus['processing_orders'] = $processingorders;
        echo json_encode($clientstatus);
        die();
    }

    public function actiontrashPreWasher() {

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

        $model = new PreRegWashers;
        $id = Yii::app()->request->getParam('id');
        //$delagents = PreRegWashers::model()->deleteAll('id=:id', array(':id'=>$id));
        $washersdata = array(
            'trash_status' => 1
        );
        PreRegWashers::model()->updateByPk($id, $washersdata);

        $result = 'true';
        $response = 'agents trash';

        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
        die();
    }

    public function actionGetPreWasherTrashData() {

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

        $washer_count = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM pre_registered_washers WHERE trash_status = '1' ")->queryAll();

        $count = $washer_count[0]['cnt'];
        $json = array(
            'result' => 'true',
            'response' => 'trash data',
            'count' => $count,
        );
        echo json_encode($json);
        die();
    }

    public function actioninactiveagentslogout() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
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

        $online_agents = Yii::app()->db->createCommand("SELECT * FROM agents WHERE status = 'online' AND available_for_new_order = 1")->queryAll();

        if (count($online_agents) > 0) {
            foreach ($online_agents as $agent) {
                $last_active = $agent['last_activity'];
                $current_time = date("Y-m-d H:i:s");

                $inactive_time = 0;
                $inactive_time = round(abs(strtotime($current_time) - strtotime($last_active)) / 60, 2);
                if ($inactive_time > 2) {

                    $inactive_logout = Agents::model()->updateAll(array('status' => 'offline', 'available_for_new_order' => 0), 'id=:id', array(':id' => $agent['id']));


                    $clear_wash_hold = Washingrequests::model()->updateAll(array('order_temp_assigned' => 0), "order_temp_assigned = " . $agent['id'] . " AND status = 0");

                    echo "agent #" . $agent['id'] . " is inactive for " . $inactive_time . " minutes. Now logged out.<br>";
                }
            }
        }
        $json = array(
            'result' => 'true',
            'response' => 'done',
        );
        //echo json_encode($json);
        die();
    }

    public function actionactivewasherdetails() {

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

        $washer_id = Yii::app()->request->getParam('washer_id');

        if ((isset($washer_id) && !empty($washer_id))) {

            $agent_id_check = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('active_washers')
                    ->where("id=:id", array(":id" => $washer_id))
                    ->queryAll();

            if (count($agent_id_check) > 0) {



                $json = array(
                    'result' => 'true',
                    'response' => 'washer details',
                    'id' => $agent_id_check[0]['id'],
                    'user_id' => $agent_id_check[0]['user_id'],
                    'first_name' => $agent_id_check[0]['first_name'],
                    'last_name' => $agent_id_check[0]['last_name'],
                    'email' => $agent_id_check[0]['user_email'],
                    'phone' => $agent_id_check[0]['phone'],
                    'active_status' => $agent_id_check[0]['active_status']
                );
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid washer'
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

    public function actionaddagent() {
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
        $agentname = $first_name . " " . $last_name;
        $emailid = Yii::app()->request->getParam('email');

        $contact_number = Yii::app()->request->getParam('phone_number');
        $date_of_birth = Yii::app()->request->getParam('date_of_birth');
        $street_address = Yii::app()->request->getParam('street_address');
        $password = Yii::app()->request->getParam('password');
        $suite_apt = '';
        $suite_apt = Yii::app()->request->getParam('suite_apt');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $driver_license = Yii::app()->request->getParam('driver_license');
        $proof_insurance = Yii::app()->request->getParam('proof_insurance');
        $business_license = Yii::app()->request->getParam('business_license');
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $routing_number = Yii::app()->request->getParam('routing_number');
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $operating_as = Yii::app()->request->getParam('operating_as');
        $company_name = Yii::app()->request->getParam('company_name');
        $wash_experience = Yii::app()->request->getParam('wash_experience');
        $washer_position = Yii::app()->request->getParam('washer_position');
        $real_washer_id = Yii::app()->request->getParam('real_washer_id');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $date = date('Y-m-d H:i:s');
        $directorypath1 = realpath(Yii::app()->basePath . '/../images/agent_img');
        $SiteUrl = Yii::app()->getBaseUrl(true);
        $json = array();
        $agentid = '';
        if ((isset($first_name) && !empty($first_name)) &&
                (isset($last_name) && !empty($last_name)) &&
                (isset($emailid) && !empty($emailid)) &&
                (isset($password) && !empty($password)) &&
                (isset($contact_number) && !empty($contact_number)) &&
                (isset($street_address) && !empty($street_address)) &&
                (isset($state) && !empty($state)) &&
                (isset($zipcode) && !empty($zipcode)) &&
                (isset($date_of_birth) && !empty($date_of_birth)) &&
                (isset($city) && !empty($city)) &&
                (isset($bank_account_number) && !empty($bank_account_number)) &&
                (isset($routing_number) && !empty($routing_number))) {
            $agents_email_exists = Agents::model()->findByAttributes(array("email" => $emailid));
            $agents_phone_exists = Agents::model()->findByAttributes(array("phone_number" => $contact_number));
            $customers_email_exists = Customers::model()->findByAttributes(array("email" => $emailid));
            $customers_phone_exists = Customers::model()->findByAttributes(array("contact_number" => $contact_number));
            /* if(count($customers_email_exists)>0){
              $result = 'false';
              $response = 'You are already registered as Customer.';
              $json= array(
              'result'=> $result,
              'response'=> $response
              );
              } */

            if (count($customers_phone_exists) > 0) {
                $result = 'false';
                $response = 'Phone number already exists.';

                $json = array(
                    'result' => $result,
                    'response' => $response
                );
            }

            /* else if(count($agents_email_exists)>0){
              $result = 'false';
              $response = 'Email already exists.';
              $agentid = $agents_email_exists->id;
              $json= array(
              'result'=> $result,
              'response'=> $response,
              'agentid'=> $agentid
              );
              } */ else if (count($agents_phone_exists) > 0) {
                $result = 'false';
                $response = 'Phone number already exists.';
                $agentid = $agents_email_exists->id;
                $json = array(
                    'result' => $result,
                    'response' => $response,
                    'agentid' => $agentid
                );
            } else {



                //file_put_contents($path, $pi_data);

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'agentname' => $agentname,
                    'email' => $emailid,
                    'password' => md5($password),
                    'phone_number' => $contact_number,
                    'date_of_birth' => $date_of_birth,
                    'street_address' => $street_address,
                    'suite_apt' => $suite_apt,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'driver_license' => $dl_imagename,
                    'proof_insurance' => $pi_imagename,
                    'business_license' => $bl_imagename,
                    'legally_eligible' => $legally_eligible,
                    'own_vehicle' => $own_vehicle,
                    'waterless_wash_product' => $waterless_wash_product,
                    'operate_area' => $operate_area,
                    'work_schedule' => $work_schedule,
                    'operating_as' => $operating_as,
                    'company_name' => $company_name,
                    'status' => 'offline',
                    'wash_experience' => $wash_experience,
                    'washer_position' => $washer_position,
                    'real_washer_id' => $real_washer_id,
                    'image' => $agent_img,
                    'account_status' => 0,
                    'created_date' => $date,
                );
                // print_r($agentdata); 
//                $data=Agents::model()->insert($agentdata);
//                print_r($data);die;
                $agentdata = array_filter($agentdata);
                $model = new Agents;
                $model->attributes = $agentdata;
                if ($model->save(false)) {
                    $agentid = Yii::app()->db->getLastInsertID();

                    if ($admin_username)
                        Agents::model()->updateByPk($agentid, array('last_edited_admin' => $admin_username, 'agentname' => $agentname));

                    $result = 'true';
                    $response = 'Agent successfully registered';


                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'id' => $agentid,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $emailid,
                        'phone_number' => $contact_number,
                        'date_of_birth' => $date_of_birth,
                        'street_address' => $street_address,
                        'suite_apt' => $suite_apt,
                        'city' => $city,
                        'state' => $state,
                        'zipcode' => $zipcode,
                        'driver_license' => $dl_imagename,
                        'proof_insurance' => $pi_imagename,
                        'business_license' => $bl_imagename,
                        'legally_eligible' => $legally_eligible,
                        'own_vehicle' => $own_vehicle,
                        'waterless_wash_product' => $waterless_wash_product,
                        'operate_area' => $operate_area,
                        'work_schedule' => $work_schedule,
                        'operating_as' => $operating_as,
                        'company_name' => $company_name,
                        'wash_experience' => $wash_experience,
                        'washer_position' => $washer_position,
                        'real_washer_id' => $real_washer_id,
                        'image' => $agent_img,
                        'account_status' => 0,
                        'created_date' => $date,
                    );

                    /* ----- braintree submerchant account creation ----------- */

                    $merchant_id = 'mobilewashinc';
                    if ($washer_position == 'real')
                        $merchant_id = 'MobileWashINC_marketplace';

                    $fullname = $first_name . " " . $last_name;
                    $merchantAccountParams = [
                        'individual' => [
                            'firstName' => $first_name,
                            'lastName' => $last_name,
                            'email' => $emailid,
                            'phone' => $contact_number,
                            'dateOfBirth' => $date_of_birth,
                            'address' => [
                                'streetAddress' => $street_address,
                                'locality' => $city,
                                'region' => $state,
                                'postalCode' => $zipcode
                            ]
                        ],
                        'funding' => [
                            'descriptor' => $fullname,
                            'destination' => 'bank',
                            'accountNumber' => $bank_account_number,
                            'routingNumber' => $routing_number
                        ],
                        'tosAccepted' => true,
                        'masterMerchantAccountId' => $merchant_id
                    ];

                    if ($washer_position == 'real')
                        $bt_result = Yii::app()->braintree->createSubMerchant_real($merchantAccountParams);
                    else
                        $bt_result = Yii::app()->braintree->createSubMerchant($merchantAccountParams);
                    //print_r($bt_result);
                    //exit;
                    if ($bt_result['success'] == 1) {
                        $update_status = Agents::model()->updateAll(array('bt_submerchant_id' => $bt_result['sub_merchant_id']), 'id=:id', array(':id' => $agentid));
                        //$result['sub_merchant_id'];
                    }

                    /* ----- braintree submerchant account creation end ----------- */
                } else {
                    $result = 'false';
                    $response = 'Internal error';
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'agentid' => $agentid
                    );
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response,
                'agentid' => $agentid
            );
        }
        echo json_encode($json);
        die();
    }

    public function actioneditagent() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $first_name = Yii::app()->request->getParam('first_name');
        $last_name = Yii::app()->request->getParam('last_name');
        $emailid = Yii::app()->request->getParam('email');

        $contact_number = Yii::app()->request->getParam('phone_number');
        $date_of_birth = Yii::app()->request->getParam('date_of_birth');
        $street_address = Yii::app()->request->getParam('street_address');
        $password = Yii::app()->request->getParam('password');
        $suite_apt = '';
        $suite_apt = Yii::app()->request->getParam('suite_apt');
        $city = Yii::app()->request->getParam('city');
        $state = Yii::app()->request->getParam('state');
        $zipcode = Yii::app()->request->getParam('zipcode');
        $driver_license = Yii::app()->request->getParam('driver_license');
        $proof_insurance = Yii::app()->request->getParam('proof_insurance');
        $business_license = Yii::app()->request->getParam('business_license');
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $routing_number = Yii::app()->request->getParam('routing_number');
        $legally_eligible = Yii::app()->request->getParam('legally_eligible');
        $own_vehicle = Yii::app()->request->getParam('own_vehicle');
        $waterless_wash_product = Yii::app()->request->getParam('waterless_wash_product');
        $operate_area = Yii::app()->request->getParam('operate_area');
        $work_schedule = Yii::app()->request->getParam('work_schedule');
        $operating_as = Yii::app()->request->getParam('operating_as');
        $company_name = Yii::app()->request->getParam('company_name');
        $wash_experience = Yii::app()->request->getParam('wash_experience');
        $washer_position = Yii::app()->request->getParam('washer_position');
        $real_washer_id = Yii::app()->request->getParam('real_washer_id');
        $date = date('Y-m-d H:i:s');
        $directorypath1 = realpath(Yii::app()->basePath . '/../images/agent_img');
        $SiteUrl = Yii::app()->getBaseUrl(true);
        $json = array();
        $agentid = '';
        if ((isset($agent_id) && !empty($agent_id))) {
            $agents_email_exists = Agents::model()->findByAttributes(array("email" => $emailid));
            if ($emailid && count($agents_email_exists) > 0) {
                $result = 'false';
                $response = 'Email already exists.';
                $agentid = $agents_email_exists->id;
                $json = array(
                    'result' => $result,
                    'response' => $response,
                    'agentid' => $agent_id
                );
            } else {


                //file_put_contents($path, $pi_data);

                $agentdata = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $emailid,
                    'password' => md5($password),
                    'phone_number' => $contact_number,
                    'date_of_birth' => $date_of_birth,
                    'street_address' => $street_address,
                    'suite_apt' => $suite_apt,
                    'city' => $city,
                    'state' => $state,
                    'zipcode' => $zipcode,
                    'driver_license' => $dl_imagename,
                    'proof_insurance' => $pi_imagename,
                    'business_license' => $bl_imagename,
                    'legally_eligible' => $legally_eligible,
                    'own_vehicle' => $own_vehicle,
                    'waterless_wash_product' => $waterless_wash_product,
                    'operate_area' => $operate_area,
                    'work_schedule' => $work_schedule,
                    'operating_as' => $operating_as,
                    'company_name' => $company_name,
                    'status' => 'offline',
                    'wash_experience' => $wash_experience,
                    'washer_position' => $washer_position,
                    'real_washer_id' => $real_washer_id,
                    'image' => $agent_img,
                    'account_status' => 0,
                    'created_date' => $date,
                );

                $agentdata = array_filter($agentdata);
                $model = new Agents;
                $model->attributes = $agentdata;
                if ($model->save(false)) {
                    $agentid = Yii::app()->db->getLastInsertID();

                    $result = 'true';
                    $response = 'Agent successfully registered';


                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $emailid,
                        'phone_number' => $contact_number,
                        'date_of_birth' => $date_of_birth,
                        'street_address' => $street_address,
                        'suite_apt' => $suite_apt,
                        'city' => $city,
                        'state' => $state,
                        'zipcode' => $zipcode,
                        'driver_license' => $dl_imagename,
                        'proof_insurance' => $pi_imagename,
                        'business_license' => $bl_imagename,
                        'legally_eligible' => $legally_eligible,
                        'own_vehicle' => $own_vehicle,
                        'waterless_wash_product' => $waterless_wash_product,
                        'operate_area' => $operate_area,
                        'work_schedule' => $work_schedule,
                        'operating_as' => $operating_as,
                        'company_name' => $company_name,
                        'wash_experience' => $wash_experience,
                        'washer_position' => $washer_position,
                        'real_washer_id' => $real_washer_id,
                        'image' => $agent_img,
                        'account_status' => 0,
                        'created_date' => $date,
                    );

                    /* ----- braintree submerchant account creation ----------- */

                    $merchant_id = 'mobilewashinc';
                    if ($washer_position == 'real')
                        $merchant_id = 'MobileWashINC_marketplace';

                    $fullname = $first_name . " " . $last_name;
                    $merchantAccountParams = [
                        'individual' => [
                            'firstName' => $first_name,
                            'lastName' => $last_name,
                            'email' => $emailid,
                            'phone' => $contact_number,
                            'dateOfBirth' => $date_of_birth,
                            'address' => [
                                'streetAddress' => $street_address,
                                'locality' => $city,
                                'region' => $state,
                                'postalCode' => $zipcode
                            ]
                        ],
                        'funding' => [
                            'descriptor' => $fullname,
                            'destination' => 'bank',
                            'accountNumber' => $bank_account_number,
                            'routingNumber' => $routing_number
                        ],
                        'tosAccepted' => true,
                        'masterMerchantAccountId' => $merchant_id
                    ];

                    if ($washer_position == 'real')
                        $bt_result = Yii::app()->braintree->createSubMerchant_real($merchantAccountParams);
                    else
                        $bt_result = Yii::app()->braintree->createSubMerchant($merchantAccountParams);
                    //print_r($bt_result);
                    //exit;
                    if ($bt_result['success'] == 1) {
                        $update_status = Agents::model()->updateAll(array('bt_submerchant_id' => $bt_result['sub_merchant_id']), 'id=:id', array(':id' => $agentid));
                        //$result['sub_merchant_id'];
                    }

                    /* ----- braintree submerchant account creation end ----------- */
                } else {
                    $result = 'false';
                    $response = 'Internal error';
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'agentid' => $agentid
                    );
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
            $json = array(
                'result' => $result,
                'response' => $response,
                'agentid' => $agentid
            );
        }
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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $response = "Pass the required parameters";
        $result = "false";
        $allwashes = array();
        $washstarttimes = array();

        if ((isset($agent_id) && !empty($agent_id))) {

            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }
            $agents = Agents::model()->findByPk($agent_id);


            if (!$agents) {
                $response = "Invalid agent id";
                $result = "false";
            } else {
                $allschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent_id, 'is_scheduled' => 1, 'status' => 0), array('order' => 'id desc'));
                $has_workinprogress_wash = Washingrequests::model()->findAll(array("condition" => "status > 0 AND status <= 3 AND agent_id=:agent_id", 'params' => array(':agent_id' => $agent_id), 'order' => 'created_date desc'));
                if (count($allschedwashes)) {

                    foreach ($allschedwashes as $key => $schedwash) {
                        $sched_date = '';
                        $sched_time = '';
                        if ($schedwash->reschedule_time) {
                            $sched_date = $schedwash->reschedule_date;
                            $sched_time = $schedwash->reschedule_time;
                        } else {
                            $sched_date = $schedwash->schedule_date;
                            $sched_time = $schedwash->schedule_time;
                        }

                        $scheduledatetime = $sched_date . " " . $sched_time;
                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($scheduledatetime);
                        $min_diff = 0;

                        $min_diff = round(($from_time - $to_time) / 60, 2);

                        if (($min_diff < 0) && ($min_diff <= -1440)) {
                            continue;
                        }


                        $cust_detail = Customers::model()->findByPk($schedwash->customer_id);

                        if (($cust_detail->first_name != '') && ($cust_detail->last_name != '')) {
                            $cust_shortname = '';
                            $cust_name = explode(" ", trim($cust_detail->last_name));
                            $cust_shortname = $cust_detail->first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
                        } else {
                            $cust_shortname = '';
                            $cust_name = explode(" ", trim($cust_detail->customername));
                            if (count($cust_name > 1))
                                $cust_shortname = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                            else
                                $cust_shortname = $cust_name[0];
                        }

                        $cust_shortname = strtolower($cust_shortname);
                        $cust_shortname = ucwords($cust_shortname);

                        $washtime = 0;
                        $washtime_str = '';
                        $cars = explode(",", $schedwash->car_list);
                        $plans = explode(",", $schedwash->package_list);
                        foreach ($cars as $ind => $car) {
                            $car_detail = Vehicle::model()->findByPk($car);
                            //echo $car_detail->brand_name." ".$car_detail->model_name."<br>";

                            $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/plans");
                            $data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                            curl_setopt($handle, CURLOPT_POST, true);
                            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                            $plan_result = curl_exec($handle);
                            curl_close($handle);
                            $jsondata = json_decode($plan_result);
                            if ($plans[$ind] == 'Express') {
                                //echo $jsondata->plans->deluxe[0]->wash_time."<br>";
                                $expprice = intval($jsondata->plans->express[0]->wash_time);
                                $washtime += $expprice;
                            }

                            if ($plans[$ind] == 'Deluxe') {
                                //echo $jsondata->plans->deluxe[0]->wash_time."<br>";
                                $delprice = intval($jsondata->plans->deluxe[0]->wash_time);
                                $washtime += $delprice;
                            }

                            if ($plans[$ind] == 'Premium') {
                                //echo $jsondata->plans->premium[0]->wash_time."<br>";
                                $premprice = intval($jsondata->plans->premium[0]->wash_time);
                                $washtime += $premprice;
                            }

                            /* --- addons time ----- */



                            $pet_hair_vehicles_arr = explode(",", $schedwash['pet_hair_vehicles']);
                            if (in_array($car, $pet_hair_vehicles_arr))
                                $washtime += 5;

                            $lifted_vehicles_arr = explode(",", $schedwash['lifted_vehicles']);
                            if (in_array($car, $lifted_vehicles_arr))
                                $washtime += 5;

                            $exthandwax_vehicles_arr = explode(",", $schedwash['exthandwax_vehicles']);
                            if (in_array($car, $exthandwax_vehicles_arr))
                                $washtime += 10;

                            $extplasticdressing_vehicles_arr = explode(",", $schedwash['extplasticdressing_vehicles']);
                            if (in_array($car, $extplasticdressing_vehicles_arr))
                                $washtime += 5;

                            $extclaybar_vehicles_arr = explode(",", $schedwash['extclaybar_vehicles']);
                            if (in_array($car, $extclaybar_vehicles_arr))
                                $washtime += 15;

                            $waterspotremove_vehicles_arr = explode(",", $schedwash['waterspotremove_vehicles']);
                            if (in_array($car, $waterspotremove_vehicles_arr))
                                $washtime += 10;

                            $upholstery_vehicles_arr = explode(",", $schedwash['upholstery_vehicles']);
                            if (in_array($car, $upholstery_vehicles_arr))
                                $washtime += 10;

                            $floormat_vehicles_arr = explode(",", $schedwash['floormat_vehicles']);
                            if (in_array($car, $floormat_vehicles_arr))
                                $washtime += 10;

                            /* --- addons time end ----- */
                        }

                        //$washtime += 30;

                        $hours = floor($washtime / 60);
                        $minutes = ($washtime % 60);
                        $washtime_str = sprintf('%d:%02d mins', $hours, $minutes);

                        $is_startable = 0;
                        $scheduledatetime = $sched_date . " " . $sched_time;
                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($scheduledatetime);
                        $min_diff = 0;
                        if ($from_time >= $to_time) {
                            $min_diff = round(($from_time - $to_time) / 60, 2);
                        }
//echo "#".$schedwash->id." ".$min_diff."<br>";
                        if (($min_diff <= 60) && (!count($has_workinprogress_wash))) {
                            $is_startable = 1;
                        }
                        if ($min_diff < 0) {
                            $min_diff = 0;
                        }

                        $washstarttimes[$key] = $min_diff;

                        if (AES256CBC_STATUS == 1) {
                            $allwashes[] = array('id' => $this->aes256cbc_crypt($schedwash->id, 'e', AES256CBC_API_PASS),
                                'customer_id' => $this->aes256cbc_crypt($schedwash->customer_id, 'e', AES256CBC_API_PASS),
                                'customer_name' => $cust_detail->first_name . " " . $cust_detail->last_name,
                                'customer_shortname' => $cust_shortname,
                                'customer_phoneno' => $cust_detail->contact_number,
                                'customer_rating' => $cust_detail->rating,
                                'car_list' => $schedwash->car_list,
                                'package_list' => $schedwash->package_list,
                                'address' => $schedwash->address,
                                'address_type' => $schedwash->address_type,
                                'latitude' => $schedwash->latitude,
                                'longitude' => $schedwash->longitude,
                                'status' => $schedwash->status,
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time,
                                'is_startable' => $is_startable,
                                'time_left_to_schedule' => $min_diff . " mins",
                                'estimate_time' => $washtime,
                                'estimate_time_str' => $washtime_str
                            );
                        } else {
                            $allwashes[] = array('id' => $schedwash->id,
                                'customer_id' => $schedwash->customer_id,
                                'customer_name' => $cust_detail->first_name . " " . $cust_detail->last_name,
                                'customer_shortname' => $cust_shortname,
                                'customer_phoneno' => $cust_detail->contact_number,
                                'customer_rating' => $cust_detail->rating,
                                'car_list' => $schedwash->car_list,
                                'package_list' => $schedwash->package_list,
                                'address' => $schedwash->address,
                                'address_type' => $schedwash->address_type,
                                'latitude' => $schedwash->latitude,
                                'longitude' => $schedwash->longitude,
                                'status' => $schedwash->status,
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time,
                                'is_startable' => $is_startable,
                                'time_left_to_schedule' => $min_diff . " mins",
                                'estimate_time' => $washtime,
                                'estimate_time_str' => $washtime_str
                            );
                        }
                    }
                    if (count($allwashes) > 0) {
                        $response = "all scheduled washes";
                        $result = "true";
                        array_multisort($washstarttimes, SORT_ASC, $allwashes);
                    } else {
                        $response = "no scheduled washes found";
                        $result = "false";
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

    public function actionallagentslogout() {

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


        $update_status = Agents::model()->updateAll(array('status' => 'offline', 'device_token' => '', 'available_for_new_order' => 0));

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

    public function actionbuggedagentlogout() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        /* $api_token = Yii::app()->request->getParam('api_token');
          $t1 = Yii::app()->request->getParam('t1');
          $t2 = Yii::app()->request->getParam('t2');
          $user_type = Yii::app()->request->getParam('user_type');
          $user_id = Yii::app()->request->getParam('user_id');

          $token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

          if(!$token_check){
          $json = array(
          'result'=> 'false',
          'response'=> 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'error in logout operation';


        $update_status = Agents::model()->updateAll(array('device_token' => '', 'available_for_new_order' => 0), 'status=:status', array(':status' => 'offline'));

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

    public function actionaddagentdevice() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $device_name = Yii::app()->request->getParam('device_name');
        $device_id = Yii::app()->request->getParam('device_id');
        $device_token = Yii::app()->request->getParam('device_token');
        $os_details = Yii::app()->request->getParam('os_details');
        $device_type = Yii::app()->request->getParam('device_type');
        $aws_platformarn = '';
        $endpoint_arn = '';

        if ((isset($agent_id) && !empty($agent_id)) && (isset($device_id) && !empty($device_id)) && (isset($device_token) && !empty($device_token))) {

            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }

            $device_exists = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE device_id = :device_id")->bindValue(':device_id', $device_id, PDO::PARAM_STR)->queryAll();

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
                            'Attributes' => array("Token" => $device_token, 'Enabled' => 'true'),
                            'EndpointArn' => $device_exists[0]['endpoint_arn'],
                        ]);

                        if($device_exists[0]['device_type'] == 'IOS'){
                          $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $device_exists[0]['endpoint_arn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_ios_dev',
                        ]);  
                        }
                        else{
                            $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $device_exists[0]['endpoint_arn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_android_dev',
                        ]);   
                        }
                        
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
                            $aws_platformarn = AWS_WASHER_IOS_PLATFORM_ARN;
                        else
                            $aws_platformarn = AWS_WASHER_ANDROID_PLATFORM_ARN;

                        $aws_result = $aws_client->createPlatformEndpoint([
                            'CustomUserData' => base64_encode($this->aes256cbc_crypt($agent_id, 'e', AES256CBC_API_PASS)),
                            'PlatformApplicationArn' => $aws_platformarn,
                            'Token' => $device_token,
                        ]);

 if($device_exists[0]['device_type'] == 'IOS'){
                        $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $aws_result['EndpointArn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_ios_dev',
                        ]);
 }
 else{
  $aws_subscribe_result = $aws_client->subscribe([
                            'Endpoint' => $aws_result['EndpointArn'],
                            'Protocol' => 'application',
                            'ReturnSubscriptionArn' => true,
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_android_dev',
                        ]);   
 }
                        $endpoint_arn = $aws_result['EndpointArn'];
                    } catch (exception $e) {
                        
                    }
                }

                if (!$device_exists[0]['endpoint_arn']) {
                    Yii::app()->db->createCommand("UPDATE agent_devices SET agent_id=:agent_id, device_token=:device_token, device_name=:device_name, os_details=:os_details, device_type=:device_type, endpoint_arn=:endpoint_arn, last_used='" . date("Y-m-d H:i:s") . "' WHERE device_id = :device_id")
                            ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                            ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
                            ->bindValue(':device_name', $device_name, PDO::PARAM_STR)
                            ->bindValue(':os_details', $os_details, PDO::PARAM_STR)
                            ->bindValue(':device_type', $device_type, PDO::PARAM_STR)
                            ->bindValue(':device_id', $device_id, PDO::PARAM_STR)
                            ->bindValue(':endpoint_arn', $endpoint_arn, PDO::PARAM_STR)
                            ->execute();
                } else {
                    Yii::app()->db->createCommand("UPDATE agent_devices SET agent_id=:agent_id, device_token=:device_token, device_name=:device_name, os_details=:os_details, device_type=:device_type, last_used='" . date("Y-m-d H:i:s") . "' WHERE device_id = :device_id")
                            ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
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
                        $aws_platformarn = AWS_WASHER_IOS_PLATFORM_ARN;
                    else
                        $aws_platformarn = AWS_WASHER_ANDROID_PLATFORM_ARN;

                    $aws_result = $aws_client->createPlatformEndpoint([
                        'CustomUserData' => base64_encode($this->aes256cbc_crypt($agent_id, 'e', AES256CBC_API_PASS)),
                        'PlatformApplicationArn' => $aws_platformarn,
                        'Token' => $device_token,
                    ]);

if ($device_type == 'IOS'){
                      $aws_subscribe_result = $aws_client->subscribe([
                        'Endpoint' => $aws_result['EndpointArn'],
                        'Protocol' => 'application',
                        'ReturnSubscriptionArn' => true,
                        'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_ios_dev',
                    ]);  
}
else{
                      $aws_subscribe_result = $aws_client->subscribe([
                        'Endpoint' => $aws_result['EndpointArn'],
                        'Protocol' => 'application',
                        'ReturnSubscriptionArn' => true,
                        'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush_android_dev',
                    ]);  
}

                    $endpoint_arn = $aws_result['EndpointArn'];
                } catch (exception $e) {
                    
                }

                $data = array('agent_id' => $agent_id, 'device_name' => $device_name, 'device_id' => $device_id, 'device_token' => $device_token, 'os_details' => $os_details, 'device_type' => $device_type, 'device_add_date' => date("Y-m-d H:i:s"), 'last_used' => date("Y-m-d H:i:s"), 'endpoint_arn' => $endpoint_arn);

                Yii::app()->db->createCommand()->insert('agent_devices', $data);
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
    
    public function actionsubmerchant_find() {

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

        /* ----- braintree submerchant account creation ----------- */

        $id = Yii::app()->request->getParam('id');

        if (APP_ENV == 'real')
            $bt_result = Yii::app()->braintree->getsubmerchantbyid_real($id);
        else
            $bt_result = Yii::app()->braintree->getsubmerchantbyid($id);
        print_r($bt_result);



        /* ----- braintree submerchant account creation end ----------- */
    }

    public function actionsubmerchant_update() {

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
        $bank_account_number = Yii::app()->request->getParam('bank_account_number');
        $routing_number = Yii::app()->request->getParam('routing_number');

        /* ----- braintree submerchant account creation ----------- */

        /* $data = [
          'funding' => [
          'descriptor' => 'Juan Ramon Lopez',
          'destination' => 'bank',
          'email' => 'franciscocanchola55@gmail.com',
          'mobilePhone' => '5628411881',
          'accountNumber' => '6693982016',
          'routingNumber' => '122000247'
          ]
          ]; */

        $data = [
            'funding' => [
                'destination' => 'bank',
                'accountNumber' => $bank_account_number,
                'routingNumber' => $routing_number
            ]
        ];

        if (APP_ENV == 'real')
            $bt_result = Yii::app()->braintree->updateSubMerchant_real($id, $data);
        else
            $bt_result = Yii::app()->braintree->updateSubMerchant($id, $data);
        print_r($bt_result);



        /* ----- braintree submerchant account creation end ----------- */
    }

    public function actionprewasherregsms() {

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


        $num = Yii::app()->request->getParam('phoneno');


        $result = 'false';
        $response = 'please enter phone number';

        $json = array();

        if ((isset($num) && !empty($num))) {

            $sid = TWILIO_SID;
            $token = TWILIO_AUTH_TOKEN;
            $twilio = new Client($sid, $token);

            $phone_number_check = $twilio->lookups->v1->phoneNumbers($num)->fetch(array("type" => "carrier"));

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


            $agents_phone_exists = PreRegWashers::model()->findByAttributes(array("phone" => $num));



            $this->layout = "xmlLayout";
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

            require('Services/Twilio.php');
            require('Services/Twilio/Capability.php');

            $account_sid = TWILIO_SID;
            $auth_token = TWILIO_AUTH_TOKEN;
            $client = new Services_Twilio($account_sid, $auth_token);




            $message = "Here is your MobileWash Washer registration link https://www.mobilewash.com/register/complete-registration.php?wephn=" . rtrim(strtr(base64_encode($num), '+/', '-_'), '=');

            $sendmessage = $client->account->messages->create(array(
                'To' => $num,
                'From' => '+13108959160',
                'Body' => $message,
            ));


            spl_autoload_register(array('YiiBase', 'autoload'));

            $result = 'true';
            $response = 'sms sent';
        }


        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionsinglewashercarerating() {

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
        $response = 'no washers found';
        $all_washers = array();


        $washers_exists = Agents::model()->findByPk(Yii::app()->request->getParam('id'));



        if (count($washers_exists) > 0) {
            $result = 'true';
            $response = 'agent';

            $avg_care_rating = 0;
            $final_avg_care_rating = 100;
            $washer_first_wash_check = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status = 4 AND `agent_id` = '$washers_exists->id' ORDER BY id ASC LIMIT 0, 1")->queryAll();

            if (count($washer_first_wash_check)) {
                echo $washers_exists->id . " " . $washer_first_wash_check[0]['order_for'];
                $now = time(); // or your date as well
                $datediff = $now - strtotime($washer_first_wash_check[0]['order_for']);
                $washer_working_since = round($datediff / (60 * 60 * 24));
                $num_of_30_days_segment = floor($washer_working_since / 30);
                echo " washer working since " . $washer_working_since;
                echo " number of 30 days segment " . floor($washer_working_since / 30);
                echo "<br>";
                for ($i = 1; $i <= $num_of_30_days_segment; $i++) {
                    $cust_served_ids = array();
                    $care_rating = 0;
                    $total_returning_customers = 0;
                    $totalwash = 0;
                    if ($i == 1) {
                        $fromdate = $washer_first_wash_check[0]['order_for'];
                        $todate = date('Y-m-d', strtotime($washer_first_wash_check[0]['order_for'] . " + 30 days"));
                    } else {
                        $fromdate = date('Y-m-d', strtotime($todate . " + 1 days"));
                        $todate = date('Y-m-d', strtotime($fromdate . " + 30 days"));
                    }

                    echo "from date " . $fromdate . " to date " . $todate . "<br>";
                    $totalwash_arr = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status=4 AND `agent_id` = '$washers_exists->id' AND order_for BETWEEN '" . $fromdate . "' AND '" . $todate . "'")->queryAll();
                    $totalwash = count($totalwash_arr);

                    if (count($totalwash_arr)) {
                        foreach ($totalwash_arr as $agentwash) {
                            $cust_served_ids[] = $agentwash['customer_id'];
                        }
                    }

                    $cust_served_ids = array_unique($cust_served_ids);

                    if (count($cust_served_ids) > 0) {
                        foreach ($cust_served_ids as $cid) {
                            $cust_check = Customers::model()->findByAttributes(array("id" => $cid));
                            if ((count($cust_check)) && ($cust_check->is_first_wash == 1) && (!$cust_check->is_non_returning)) {
                                $total_returning_customers++;
                            }
                        }
                    }
                    echo "total return " . $total_returning_customers . " total served " . count($cust_served_ids) . "<br>";
                    if (count($cust_served_ids) > 0)
                        $care_rating = ($total_returning_customers / count($cust_served_ids)) * 100;
                    $avg_care_rating += $care_rating;
                    echo "rating " . $care_rating . "segment " . $i . "<br>";
                }

                if ($num_of_30_days_segment)
                    $final_avg_care_rating = $avg_care_rating / $num_of_30_days_segment;
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'care_rating' => round($final_avg_care_rating, 2)
        );

        echo json_encode($json);
        die();
    }

    public function actioncheckinactivewashers() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        /* $api_token = Yii::app()->request->getParam('api_token');
          $t1 = Yii::app()->request->getParam('t1');
          $t2 = Yii::app()->request->getParam('t2');
          $user_type = Yii::app()->request->getParam('user_type');
          $user_id = Yii::app()->request->getParam('user_id');

          $token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

          if(!$token_check){
          $json = array(
          'result'=> 'false',
          'response'=> 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $washers_exists = Yii::app()->db->createCommand("SELECT * FROM `agents` WHERE (DATEDIFF(CURDATE(), last_activity) >= 30) AND block_washer = 0")->queryAll();


        if (count($washers_exists)) {
            foreach ($washers_exists as $washer) {
                Agents::model()->updateByPk($washer['id'], array("block_washer" => 1));
            }
        }
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
            Agents::model()->updateByPk($id, $data);
            $result = 'true';
            $response = 'updated successfully';
            if ($admin_command == 'save-wash-note') {
                $washeractionlogdata = array(
                    'wash_request_id' => $wash_request_id,
                    'admin_username' => $admin_username,
                    'action' => 'savewashnote',
                    'action_date' => $date);
                Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                /* $washeractionlogdata = array(
                  'wash_request_id'=> $id,
                  'admin_username' => $admin_username,
                  'action'=> 'edit_Agent',
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

    public function actionexcessratedwasherscheck() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        /* $api_token = Yii::app()->request->getParam('api_token');
          $t1 = Yii::app()->request->getParam('t1');
          $t2 = Yii::app()->request->getParam('t2');
          $user_type = Yii::app()->request->getParam('user_type');
          $user_id = Yii::app()->request->getParam('user_id');

          $token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

          if(!$token_check){
          $json = array(
          'result'=> 'false',
          'response'=> 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $all_washers = Agents::model()->findAll(array("condition" => "rating > 5"));

        if (count($all_washers)) {
            foreach ($all_washers as $washer) {

                /* ------------ calculate agent average feedback ---------------- */

                $washer_total_dropjobs = 0;
                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $washer->id));
                $total_rate = count($agent_feedbacks);

                $washerdropjobs = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id = :agent_id AND action = 'dropjob' AND status = 0")->bindValue(':agent_id', $washer->id, PDO::PARAM_STR)->queryAll();

                if (!empty($washerdropjobs))
                    $washer_total_dropjobs = $washerdropjobs[0]['count'];
                if ($total_rate) {
                    $rate = 50;
                    foreach ($agent_feedbacks as $ind => $agent_feedback) {

                        if (!is_numeric($agent_feedback->customer_ratings))
                            $rate += 5;
                        else
                            $rate += $agent_feedback->customer_ratings;
                    }

                    if ($washer_total_dropjobs) {
                        //echo "rate: ".$rate."<br>total drops: ".$washer_total_dropjobs."<br>total rate: ".$total_rate."<br>";
                        $agent_rate = ($rate + $washer_total_dropjobs) / ($total_rate + 10 + $washer_total_dropjobs);
                    } else
                        $agent_rate = $rate / ($total_rate + 10);

                    $agent_rate = number_format($agent_rate, 2, '.', '');
                }
                else {
                    $agent_rate = 5.00;
                }


                $agentmodel = new Agents;
                //if($agent_rate < 3.5) $agentmodel->updateAll(array("rating"=> $agent_rate, "block_washer" => 1), 'id=:id', array(':id'=>$washrequest_id_check->agent_id));
                $agentmodel->updateAll(array("rating" => $agent_rate), 'id=:id', array(':id' => $washer->id));

                /* ------------ calculate agent average feedback end ---------------- */
            }
        }
    }

    public function actionwasherstoplocation() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');

        if (!empty($agent_id) && isset($agent_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }

            //$wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$washer_request_id));

            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();


            /* --- notification call --- */

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '44' ")->queryAll();
            $message = $pushmsg[0]['message'];

            foreach ($agentdevices as $atdevice) {

                //echo $agentdetails['mobile_type'];
                $device_type = strtolower($atdevice['device_type']);
                $notify_token = $atdevice['device_token'];
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

    public function actionwashernotifycc() {

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

        $check_washer = Yii::app()->db->createCommand("SELECT al.*, wr.id as order_id FROM washing_requests wr INNER JOIN agent_locations al ON wr.agent_id = al.agent_id WHERE wr.status = 1")->queryAll();
        $result = false;
        $response = array();
        if (count($check_washer) > 0) {
            $result = true;
            $response = $check_washer;
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionupdatetotalwash() {

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

        $offset = 0;
        $offset = Yii::app()->request->getParam('offset');


        $all_agents = Yii::app()->db->createCommand("SELECT * FROM agents ORDER BY id ASC LIMIT 100 OFFSET " . $offset)->queryAll();

        if (count($all_agents)) {
            foreach ($all_agents as $agent) {
                $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as total FROM washing_requests WHERE agent_id = " . $agent['id'] . " AND status = 4")->queryAll();
                $total_wash = $total_rows[0]['total'];
                echo "agent id: " . $agent['id'] . " | wash: " . $total_wash;
                echo "<br>";

                Agents::model()->updateByPk($agent['id'], array('total_wash' => $total_wash));
            }
        } else {
            echo "nothing found";
        }
    }

    public function actionsubscribeagentdevicetosns() {

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

        /* if(!$token_check){
          $json = array(
          'result'=> 'false',
          'response'=> 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        $limit = 500;
        if (Yii::app()->request->getParam('limit'))
            $limit = Yii::app()->request->getParam('limit');
        $page = 1;
        if (Yii::app()->request->getParam('page'))
            $page = Yii::app()->request->getParam('page');
        $total_entries = 0;
        $total_pages = 0;

        $json = array();

        $all_wash_requests_count = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM agent_devices order by id asc")
                ->queryAll();
        $total_entries = $all_wash_requests_count[0]['count'];

        if ($total_entries) {
            $total_pages = ceil($total_entries / $limit);
        }
        echo "total page: " . $total_pages . "<br>";

        $all_devices = Yii::app()->db->createCommand()
                ->select('*')
                ->from('agent_devices')
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
                    $aws_platformarn = AWS_WASHER_IOS_PLATFORM_ARN;
                else
                    $aws_platformarn = AWS_WASHER_ANDROID_PLATFORM_ARN;
                if (trim($device['device_token'])) {
                    try {
                        $aws_result = $aws_client->createPlatformEndpoint([
                            'CustomUserData' => base64_encode($this->aes256cbc_crypt($device['agent_id'], 'e', AES256CBC_API_PASS)),
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
                            'TopicArn' => 'arn:aws:sns:us-west-2:461900685840:washerschedpush',
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



                    Yii::app()->db->createCommand("UPDATE agent_devices SET endpoint_arn=:endpoint_arn WHERE id = :id")
                            ->bindValue(':id', $device['id'], PDO::PARAM_STR)
                            ->bindValue(':endpoint_arn', $aws_result['EndpointArn'], PDO::PARAM_STR)
                            ->execute();
                }


                echo "id: " . $device['id'] . " token: " . $device['device_token'] . " agent id: " . $device['agent_id'] . " end arn: " . $endpoint_arn . " subsc arn: " . $subscribe_arn;
                echo "<br>";
            }
        }
    }

    public function actionresetwasherrating() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $agent_id = Yii::app()->request->getParam('agent_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $agent_check = Agents::model()->findByPk($agent_id);

        if (!count($agent_check)) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid washer id'
            );
            echo json_encode($json);
            die();
        }

        Yii::app()->db->createCommand("UPDATE washing_feedbacks SET customer_ratings= '5.00' WHERE agent_id = " . $agent_id)->execute();
        Yii::app()->db->createCommand("UPDATE activity_logs SET status = 1 WHERE agent_id = '" . $agent_id . "' AND action='dropjob'")->execute();
        Agents::model()->updateByPk($agent_id, array('rating' => 5.00));

        $json = array(
            'result' => 'true',
            'response' => 'update successful'
        );
        echo json_encode($json);
        die();
    }

    public function actionCronJobUninstallApp() {
        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }
        $user_devices = Yii::app()->db->createCommand("SELECT  agent_devices.agent_id FROM agent_devices WHERE  device_type='ANDROID' group by agent_id ORDER BY last_used DESC ")->queryAll();
        $wash_id = "";
        if (count($user_devices)) {

            foreach ($user_devices as $device) {
                /* --- notification call --- */
                $user_devices_detail = Yii::app()->db->createCommand("SELECT agent_devices.* FROM  agent_devices   WHERE agent_id=" . $device['agent_id'] . " AND  device_type='ANDROID'  ORDER BY last_used DESC LIMIT 1")->queryRow();
                //define('API_ACCESS_KEY', 'AAAAKHWvBtc:APA91bH7eWGNgvoZQxe56zzxeE2cxW4qVG_5dc9iwpF73R0ph0govruyXQ-1QK-pE_VxLeBewkXsnKWecuVp42IZKJSB0Z6yo5x44w6ytelM7HXWHSItSViPO4TmzscYddTEmcqNi3ae');
                $registrationIds = array($user_devices_detail['device_token']);

                // prep the bundle
                $msg = array
                    (
                    'message' => "test blank notify",
                    'title' => '',
                    'subtitle' => '',
                    'tickerText' => '',
                    'vibrate' => 1,
                    'sound' => "strong",
                    'largeIcon' => 'large_icon',
                    'wash_id' => $wash_id,
                    'smallIcon' => 'small_icon'
                );
                $fields = array
                    (
                    'registration_ids' => $registrationIds,
                    'data' => $msg
                );

                $headers = array
                    (
                    'Authorization: key=' . API_ACCESS_KEY_ANDRIOD,
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                $result = json_decode($result);
                if ($result->success == 0) {
                    if ($result->results[0]->error == "NotRegistered") {
                        echo "SMS disabled FOR Agent " . $user_devices_detail['agent_id'];
                        $result = Yii::app()->db->createCommand("UPDATE agents SET sms_control=0  WHERE id=" . $user_devices_detail['agent_id'])->query();
                    }
                }
                curl_close($ch);
                echo "SMS enabled FOR Agent";
                /* --- notification call end --- */


                //echo $result;
                //die;
            }
        }
    }

    public function actiongetagentdevice() {

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
        $device_token = Yii::app()->request->getParam('device_token');

        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }

        $agentdevices = Yii::app()->db->createCommand("SELECT id, forced_logout FROM agent_devices WHERE agent_id = '" . $agent_id . "' AND device_token = '" . $device_token . "' ORDER BY id DESC LIMIT 1")->queryAll();

        if (count($agentdevices)) {
            $json = array(
                'result' => 'true',
                'response' => 'device found',
                'forced_logout' => $agentdevices[0]['forced_logout']
            );
            echo json_encode($json);
            die();
        } else {
            $json = array(
                'result' => 'false',
                'response' => 'device not found',
            );
            echo json_encode($json);
            die();
        }
    }

}
