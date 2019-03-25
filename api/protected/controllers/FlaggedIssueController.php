<?php

require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/amazon-sdk/aws-autoloader.php';

use Twilio\Rest\Client;
use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;

class FlaggedIssueController extends Controller {

    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13102941020';
    protected $callbackurl = ROOT_URL . '/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex() {
        $this->render('index');
    }

    public function actionGetAllFlaggedIssues() {
        $limit = Yii::app()->request->getParam('limit');
        $data['status'] = '1';
        $data['data'] = '0';
        $Query = "";
        if ($limit) {
            $Query .= " Limit " . $limit;
        }
        if ($is_flagged) {
            $is_flagged = 0;
            $Query .= " where  w.is_flagged = " . $is_flagged;
        }
        $getAllFlaggedIssue = Yii::app()->db->createCommand("SELECT w.id, c.first_name, c.last_name FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id  ORDER BY w.id DESC " . $Query)->queryAll();
        if ($getAllFlaggedIssue) {
            $data['status'] = '1';
            $data['data'] = $getAllFlaggedIssue;
        }
        echo json_encode($data);
        die;
    }

    public function actionUpdateFlaggedIssueByOrderId() {
        $washRequestId = Yii::app()->request->getParam('washRequestId');
        $flagged_val = Yii::app()->request->getParam('flagged_val');
        $admin_username = Yii::app()->request->getParam('admin_username');
        $washeractionlogdata = array(
            'wash_request_id' => $washRequestId,
            'admin_username' => $admin_username,
            'action' => 'flagged_issue',
            'action_date' => date('Y-m-d H:i:s'));
        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flagged_val . "'     WHERE id=" . $washRequestId)->query();
    }

    public function actionUpdateFlaggedIssueMultiple() {
        $flagVal = Yii::app()->request->getParam('flagVal');
        $resolvedValue = Yii::app()->request->getParam('resolvedValue');

        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flagVal . "'     WHERE id in( " . $resolvedValue . ")")->query();
    }

    public function actionUpdateFlagIssue() {

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
        $flaggedVal = Yii::app()->request->getParam('flaggedVal');
        $orderId = Yii::app()->request->getParam('orderId');
        $user_name = Yii::app()->request->getParam('user_name');
        if ($flaggedVal == 2) {
            $washeractionlogdata = array(
                'wash_request_id' => $orderId,
                'admin_username' => $user_name,
                'action' => 'resolved_flagged',
                'action_date' => date('Y-m-d H:i:s'));
            Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
        }
        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flaggedVal . "'     WHERE id=" . $orderId)->query();
    }

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
                $washfeedbackdata = array(
                    'customer_id' => $customer_id,
                    'comments' => $comments,
                    'title' => $title
                );

                Yii::app()->db->createCommand()->insert('app_feedbacks', $washfeedbackdata);
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

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function actionclientsadmin() {

        $search = Yii::app()->request->getParam('search');
        $limit = Yii::app()->request->getParam('limit');

        if (!$limit) {
            $customers = Yii::app()->db->createCommand("SELECT * FROM `customers`  ORDER BY `customers`.`id` DESC  limit 10")->queryAll();
            // print_r($customers); die;
        } else {
            $customers = Yii::app()->db->createCommand("SELECT * FROM `customers` ORDER BY id  dsc LIMIT " . $params['start'] . " ," . $params['length'])->queryAll();
        }
        $customerdetail = array();
        foreach ($customers as $customername) {
            //print_r($customername);
            $totalwash = 0;
            $customersid = $customername['id'];
            $CustomerTotalordersCount = Yii::app()->db->createCommand("SELECT COUNT(id) as orders FROM `washing_requests` WHERE  customer_id =" . $customername['id'])->queryRow();
            $CustomerTotalordersCount = 100;
            $customerCreatedDate = date('Y-m-d', strtotime($customername['created_date']));
            $current = date('Y-m-d');
            $current = date_create($current);
            $customerCreatedDate = date_create($customerCreatedDate);
            $diff = date_diff($current, $customerCreatedDate);
            $daysSinceCustomerCreate = $diff->format("%a");

            if ($daysSinceCustomerCreate != 0 && $CustomerTotalordersCount['orders']) {
                $order_frequency = ($daysSinceCustomerCreate / $CustomerTotalordersCount['orders']);
            } else {
                $order_frequency = "0";
            }


            $totalwash_arr = Washingrequests::model()->findAllByAttributes(array("status" => 4, "customer_id" => $customersid));
            $custfirstdevice = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $customersid . "' ORDER BY device_add_date ASC LIMIT 1")->queryAll();

            $totalwash = count($totalwash_arr);

            $custlocation = CustomerLocation::model()->findByAttributes(array("customer_id" => $customersid));

            $custspent = Yii::app()->db->createCommand("SELECT SUM(net_price) FROM washing_requests WHERE customer_id = :customer_id AND  status = 4 AND net_price > 0")
                    ->bindValue(':customer_id', $customersid, PDO::PARAM_STR)
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
            $json['id'] = $customersid;
            $json['name'] = $customername['first_name'] . " " . $customername['last_name'];
            $json['user_type'] = $customername['login_type'];
            $json['email'] = $customername['email'];
            $json['phone'] = $customername['contact_number'];
            $json['rating'] = $customername['rating'];

            if (count($custfirstdevice))
                $json['device_type'] = $custfirstdevice[0]['device_type'];
            else {
                if ($customername['mobile_type'])
                    $json['device_type'] = $customername['mobile_type'];
                else
                    $json['device_type'] = "N/A";
            }
            $json['phone_verify_code'] = $customername['phone_verify_code'];
            $json['wash_points'] = $customername['fifth_wash_points'];

            $json['total_wash'] = $totalwash;
            $json['address'] = $address;
            $json['city'] = $city;
            $json['how_hear_mw'] = $customername['how_hear_mw'];
            $json['total_spent'] = number_format($totalpaid, 2);
            $json['order_frequency'] = $order_frequency;
            $json['client_science'] = date('m-d-Y h:i A', strtotime($customername['created_date']));

            $customerdetail[] = $json;
        }
        //print_r($customerdetail); die;
        $data['draw'] = 5;
        $data['recordsTotal'] = 10;
        $data['recordsFiltered'] = $customerdetail;
        $data['data'] = $customerdetail;
        echo json_encode($data);

        die();
    }

    public function actionConfirmPhoneWeb() {

        $ip = "";

        if ($_SERVER) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }

        $tempdevicedata = "device_id=web&device_name=web&device_token=" . md5(uniqid(rand(), true)) . "&device_type=web&os_details=web";
        $cryptokey = bin2hex(openssl_random_pseudo_bytes(8));
        $iv = bin2hex(openssl_random_pseudo_bytes(8));
        $tempdevicedata_raw = openssl_encrypt($tempdevicedata, "AES-128-CBC", $cryptokey, $options = OPENSSL_RAW_DATA, $iv);
        $tempdevicedata_encrypted = base64_encode($tempdevicedata_raw);

        $r1 = bin2hex(openssl_random_pseudo_bytes(6));
        $r2 = bin2hex(openssl_random_pseudo_bytes(6));
        $r3 = bin2hex(openssl_random_pseudo_bytes(7));
        $r4 = bin2hex(openssl_random_pseudo_bytes(7));

        $cryptokey_pt1 = substr($cryptokey, 0, 8);
        $cryptokey_pt2 = substr($cryptokey, -8, 8);

        $cryptokeyencode = $r1 . $cryptokey_pt1 . $r2 . $r3 . $cryptokey_pt2 . $r4; //[12][8][12][14][8][14]

        $r1 = bin2hex(openssl_random_pseudo_bytes(6));
        $r2 = bin2hex(openssl_random_pseudo_bytes(6));
        $r3 = bin2hex(openssl_random_pseudo_bytes(7));
        $r4 = bin2hex(openssl_random_pseudo_bytes(7));

        $iv_pt1 = substr($iv, 0, 8);
        $iv_pt2 = substr($iv, -8, 8);

        $ivencode = $r1 . $iv_pt1 . $r2 . $r3 . $iv_pt2 . $r4; //[12][8][12][14][8][14]

        $data = array("device_data" => $tempdevicedata_encrypted, "t1" => base64_encode($cryptokeyencode), "t2" => base64_encode($ivencode), 'key' => API_KEY);
        $handle = curl_init(ROOT_URL . "/api/index.php?r=users/gettempaccesstoken");
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $apirawdata = curl_exec($handle);
        $gettempaccesstoken_result = json_decode($apirawdata);


        $finalusertoken = '';
        if ($gettempaccesstoken_result->result == 'true') {
            //print_r($gettempaccesstoken_result); die;
            $keydecode = base64_decode($gettempaccesstoken_result->t1);
            $ivdecode = base64_decode($gettempaccesstoken_result->t2);
            $key_pt1 = substr($keydecode, 12, 8);
            $key_pt2 = substr($keydecode, -22, 8);

            $fullkey = $key_pt1 . $key_pt2;

            $iv_pt1 = substr($ivdecode, 12, 8);
            $iv_pt2 = substr($ivdecode, -22, 8);

            $fulliv = $iv_pt1 . $iv_pt2;

            $string_decode = base64_decode($gettempaccesstoken_result->token);

            $string_plain = openssl_decrypt($string_decode, "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);

            $decodestrarr = explode("tmn!!==*", $string_plain);
            $timestamp_fct = $decodestrarr[1];
            $decodedstr2 = substr($decodestrarr[0], 25);
            $user_token_str = substr($decodedstr2, 0, -25);

            $rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

            $first_25 = substr($rand_bytes, 0, 25);
            $last_25 = substr($rand_bytes, -25, 25);

            $ciphertext_raw = openssl_encrypt($first_25 . $user_token_str . $last_25 . "tmn!!==*" . time(), "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);
            $finalusertoken = base64_encode($ciphertext_raw);
        }


        $api_token = $finalusertoken;
        $t1 = $gettempaccesstoken_result->t1;
        $t2 = $gettempaccesstoken_result->t2;

        $token_check = $this->verifyapitemptoken($api_token, $t1, $t2, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }
        $userid = Yii::app()->request->getParam('id');
        $sortcode = Yii::app()->request->getParam('verify_code');
        $user_type = Yii::app()->request->getParam('user_type');
        $device_token = Yii::app()->request->getParam('device_token');
        $app_version = Yii::app()->request->getParam('app_version');
        $phone = Yii::app()->request->getParam('phone');
        if (AES256CBC_STATUS == 1) {
            $userid = $this->aes256cbc_crypt($userid, 'd', AES256CBC_API_PASS);
        }


        if ($user_type == 'customer')
            $model = new Customers;
        else
            $model = new Agents;
        if ($user_type == 'customer')
            $matchcode = Customers::model()->findByAttributes(array("phone_verify_code" => $sortcode, "id" => $userid));
        else
            $matchcode = Agents::model()->findByAttributes(array("phone_verify_code" => $sortcode, "id" => $userid));



        if ($user_type == 'customer')
            $customer_login_status = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id=:user_id AND device_status = 'online'")->bindValue(':user_id', $userid, PDO::PARAM_STR)->queryAll();
        if ($user_type == 'agent')
            $agent_login_status = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id=:user_id AND device_status = 'online'")->bindValue(':user_id', $userid, PDO::PARAM_STR)->queryAll();



        if (count($customer_login_status)) {
            $result = "false";
            $response = "This account is already in use, please log out first and try again. If the problem persists, please contact MobileWash at (888) 209-5585";
            $json = array(
                'result' => $result,
                'response' => $response
            );
            echo json_encode($json);
            exit;
        }

        if (count($agent_login_status)) {
            $result = "false";
            $response = "This account is already in use, please log out first and try again. If the problem persists, please contact MobileWash at (888) 209-5585";
            $json = array(
                'result' => $result,
                'response' => $response
            );
            echo json_encode($json);
            exit;
        }


        if (!empty($matchcode)) {
            $usertoken = md5(uniqid() . time());
            $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
            $cryptokey = bin2hex(openssl_random_pseudo_bytes(8));
            $iv = bin2hex(openssl_random_pseudo_bytes(8));

            $rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

            $first_25 = substr($rand_bytes, 0, 25);
            $last_25 = substr($rand_bytes, -25, 25);

            $ciphertext_raw = openssl_encrypt($first_25 . $usertoken . $last_25 . "tmn!!==*" . time(), "AES-128-CBC", $cryptokey, $options = OPENSSL_RAW_DATA, $iv);
            $ciphertext = base64_encode($ciphertext_raw);

            $r1 = bin2hex(openssl_random_pseudo_bytes(6));
            $r2 = bin2hex(openssl_random_pseudo_bytes(6));
            $r3 = bin2hex(openssl_random_pseudo_bytes(7));
            $r4 = bin2hex(openssl_random_pseudo_bytes(7));

            $cryptokey_pt1 = substr($cryptokey, 0, 8);
            $cryptokey_pt2 = substr($cryptokey, -8, 8);

            $cryptokeyencode = $r1 . $cryptokey_pt1 . $r2 . $r3 . $cryptokey_pt2 . $r4; //[12][8][12][14][8][14]

            $r1 = bin2hex(openssl_random_pseudo_bytes(6));
            $r2 = bin2hex(openssl_random_pseudo_bytes(6));
            $r3 = bin2hex(openssl_random_pseudo_bytes(7));
            $r4 = bin2hex(openssl_random_pseudo_bytes(7));

            $iv_pt1 = substr($iv, 0, 8);
            $iv_pt2 = substr($iv, -8, 8);

            $ivencode = $r1 . $iv_pt1 . $r2 . $r3 . $iv_pt2 . $r4; //[12][8][12][14][8][14]

            $ciphertext_token = openssl_encrypt($usertoken, "AES-128-CBC", AES128CBC_KEY, $options = OPENSSL_RAW_DATA, AES128CBC_IV);
            $ciphertext_token_base64 = base64_encode($ciphertext_token);

            $ciphertext_key = openssl_encrypt($cryptokey, "AES-128-CBC", AES128CBC_KEY, $options = OPENSSL_RAW_DATA, AES128CBC_IV);
            $ciphertext_key_base64 = base64_encode($ciphertext_key);

            $ciphertext_iv = openssl_encrypt($iv, "AES-128-CBC", AES128CBC_KEY, $options = OPENSSL_RAW_DATA, AES128CBC_IV);
            $ciphertext_iv_base64 = base64_encode($ciphertext_iv);

            if ($user_type) {
                if ($userid) {

                    if (WebTokens::model()->exists('user_id = :user_id', array(":user_id" => $userid))) {
                        $update_response = Yii::app()->db->createCommand("UPDATE web_token SET    access_token = '" . $ciphertext_token_base64 . "', access_key='" . $ciphertext_key_base64 . "', access_vector='" . $ciphertext_iv_base64 . "', access_token_expire_at = '" . date("Y-m-d H:i:s", strtotime('+7 days')) . "' WHERE user_id = :user_id")->bindValue(':user_id', $userid, PDO::PARAM_STR)->execute();
                    } else {
                        $webToken = array(
                            'user_id' => $userid,
                            'access_token' => $ciphertext_token_base64,
                            'access_key' => $ciphertext_key_base64,
                            'access_vector' => $ciphertext_iv_base64,
                            'access_token_expire_at' => date("Y-m-d H:i:s", strtotime('+7 days')),
                        );
                        Yii::app()->db->createCommand()->insert('web_token', $webToken);
                    }
                }
            }
            Yii::app()->db->createCommand("DELETE FROM `temp_tokens` WHERE id = :id")->bindValue(':id', $token_check, PDO::PARAM_STR)->execute();
            $data = array(
                'result' => 'true',
                'response' => 'Congratulations, Your phone is verified.',
                'token' => $ciphertext,
                't1' => base64_encode($cryptokeyencode),
                't2' => base64_encode($ivencode),
            );
            if ($user_type == 'agent') {
                $update_response_Washer_SMS = Yii::app()->db->createCommand("UPDATE agents SET sms_control=1  WHERE id=" . $userid)->query();
            }

            echo json_encode($data);
            exit;
        } else {
            $data = array(
                'result' => 'false',
                'response' => 'Invalid Code.'
            );
            echo json_encode($data);
            exit;
        }
    }

    public function actionWebLogin() {
        $captcha_pass = 1;
        if ($captcha_pass == 1) {
            $ip = "";

            if ($_SERVER) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ip = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_CLIENT_IP')) {
                    $ip = getenv('HTTP_CLIENT_IP');
                } else {
                    $ip = getenv('REMOTE_ADDR');
                }
            }

            $tempdevicedata = "device_id=web&device_name=web&device_token=" . md5(uniqid(rand(), true)) . "&device_type=web&os_details=web";
            $cryptokey = bin2hex(openssl_random_pseudo_bytes(8));
            $iv = bin2hex(openssl_random_pseudo_bytes(8));
            $tempdevicedata_raw = openssl_encrypt($tempdevicedata, "AES-128-CBC", $cryptokey, $options = OPENSSL_RAW_DATA, $iv);
            $tempdevicedata_encrypted = base64_encode($tempdevicedata_raw);

            $r1 = bin2hex(openssl_random_pseudo_bytes(6));
            $r2 = bin2hex(openssl_random_pseudo_bytes(6));
            $r3 = bin2hex(openssl_random_pseudo_bytes(7));
            $r4 = bin2hex(openssl_random_pseudo_bytes(7));

            $cryptokey_pt1 = substr($cryptokey, 0, 8);
            $cryptokey_pt2 = substr($cryptokey, -8, 8);

            $cryptokeyencode = $r1 . $cryptokey_pt1 . $r2 . $r3 . $cryptokey_pt2 . $r4; //[12][8][12][14][8][14]

            $r1 = bin2hex(openssl_random_pseudo_bytes(6));
            $r2 = bin2hex(openssl_random_pseudo_bytes(6));
            $r3 = bin2hex(openssl_random_pseudo_bytes(7));
            $r4 = bin2hex(openssl_random_pseudo_bytes(7));

            $iv_pt1 = substr($iv, 0, 8);
            $iv_pt2 = substr($iv, -8, 8);

            $ivencode = $r1 . $iv_pt1 . $r2 . $r3 . $iv_pt2 . $r4; //[12][8][12][14][8][14]

            $data = array("device_data" => $tempdevicedata_encrypted, "t1" => base64_encode($cryptokeyencode), "t2" => base64_encode($ivencode), 'key' => API_KEY);
            $handle = curl_init(ROOT_URL . "/api/index.php?r=users/gettempaccesstoken");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $apirawdata = curl_exec($handle);
            $gettempaccesstoken_result = json_decode($apirawdata);


            $finalusertoken = '';
            if ($gettempaccesstoken_result->result == 'true') {
                //print_r($gettempaccesstoken_result); die;
                $keydecode = base64_decode($gettempaccesstoken_result->t1);
                $ivdecode = base64_decode($gettempaccesstoken_result->t2);
                $key_pt1 = substr($keydecode, 12, 8);
                $key_pt2 = substr($keydecode, -22, 8);

                $fullkey = $key_pt1 . $key_pt2;

                $iv_pt1 = substr($ivdecode, 12, 8);
                $iv_pt2 = substr($ivdecode, -22, 8);

                $fulliv = $iv_pt1 . $iv_pt2;

                $string_decode = base64_decode($gettempaccesstoken_result->token);

                $string_plain = openssl_decrypt($string_decode, "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);

                $decodestrarr = explode("tmn!!==*", $string_plain);
                $timestamp_fct = $decodestrarr[1];
                $decodedstr2 = substr($decodestrarr[0], 25);
                $user_token_str = substr($decodedstr2, 0, -25);

                $rand_bytes = bin2hex(openssl_random_pseudo_bytes(25));

                $first_25 = substr($rand_bytes, 0, 25);
                $last_25 = substr($rand_bytes, -25, 25);

                $ciphertext_raw = openssl_encrypt($first_25 . $user_token_str . $last_25 . "tmn!!==*" . time(), "AES-128-CBC", $fullkey, $options = OPENSSL_RAW_DATA, $fulliv);
                $finalusertoken = base64_encode($ciphertext_raw);
            }

            $api_token = $finalusertoken;
            $t1 = $gettempaccesstoken_result->t1;
            $t2 = $gettempaccesstoken_result->t2;

            $token_check = $this->verifyapitemptoken($api_token, $t1, $t2, AES256CBC_API_PASS);

            if (!$token_check) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid request'
                );
                echo json_encode($json);
                die();
            }

            $phone = Yii::app()->request->getParam('phone');

            $user_type = "";
            $model = false;

            if ((isset($phone) && !empty($phone))) {
                $phone = preg_replace('/\D/', '', $phone);
                $customer = Customers::model()->findByAttributes(array('contact_number' => $phone));
                $agent = Agents::model()->findByAttributes(array('phone_number' => $phone));
                if (count($customer) == 0 && count($agent) == 0) {
                    $json = array(
                        'result' => "false",
                        'response' => "Phone no. not found"
                    );
                    echo json_encode($json);
                    die();
                }
                if (count($customer))
                    $customer_login_status = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id='" . $customer->id . "' AND device_status = 'online'")->queryAll();
                if (count($agent))
                    $agent_login_status = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id='" . $agent->id . "' AND device_status = 'online'")->queryAll();

                if (count($customer)) {
                    $model = $customer;
                    $user_type = "customer";
                } else if (count($agent)) {
                    $model = $agent;
                    $user_type = "agent";
                }

                if (count($customer_login_status)) {
                    $result = "false";
                    $response = "There is no permission for log in with same account on 2 devices";
                    $json = array(
                        'result' => $result,
                        'response' => $response
                    );
                    echo json_encode($json);
                    die();
                } else if (count($agent_login_status)) {
                    $result = "false";
                    $response = "There is no permission for log in with same account on 2 devices";
                    $json = array(
                        'result' => $result,
                        'response' => $response
                    );
                    echo json_encode($json);
                    die();
                } else if (($agent->block_washer) || ($customer->block_client)) {
                    $result = "false";
                    $response = "Account error. Please contact MobileWash.";
                    $json = array(
                        'result' => $result,
                        'response' => $response
                    );
                    echo json_encode($json);
                    die();
                } else {
                    if ($model) {

                        $digits = 4;
                        $randum_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                        if ($user_type == 'customer')
                            $update_response = Yii::app()->db->createCommand("UPDATE customers SET phone_verify_code='$randum_number' WHERE id = '$model->id' ")->execute();
                        else
                            $update_response = Yii::app()->db->createCommand("UPDATE agents SET phone_verify_code='$randum_number' WHERE id = '$model->id' ")->execute();
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
                                'To' => $phone,
                                'From' => '+13106834902',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                        $result = 'true';
                        $response = 'Sent a 4 digit code.';
                    } else {
                        $json = array(
                            'result' => 'false',
                            'response' => 'Pass the required parameters'
                        );
                    }
                    if ($user_type == 'customer') {
                        if (AES256CBC_STATUS == 1) {
                            $customer_id = $this->aes256cbc_crypt($customer->id, 'e', AES256CBC_API_PASS);
                        }
                        $CustomerData['id'] = $customer_id;
                        $CustomerData['app_version'] = $customer->current_app_version;
                        $CustomerData['phone'] = $customer->contact_number;
                        $CustomerData['name'] = $customer->first_name;
                        $CustomerData['image_url'] = $customer->image;
                        $CustomerData['device_token'] = $customer->device_token;
                        $CustomerData['rating'] = $customer->rating;
                        $CustomerData['user_type'] = 'customer';
                        $userDetail = $CustomerData;
                    } else {
                        if (AES256CBC_STATUS == 1) {
                            $user_id = $this->aes256cbc_crypt($agent->id, 'e', AES256CBC_API_PASS);
                        }
                        $AgentData['id'] = $user_id;
                        $AgentData['app_version'] = $agent->current_app_version;
                        $AgentData['phone'] = $agent->phone_number;
                        $AgentData['device_token'] = $agent->device_token;
                        $AgentData['name'] = $agent->first_name;
                        $AgentData['image_url'] = $agent->image;
                        $AgentData['rating'] = $agent->rating;
                        $AgentData['user_type'] = 'agent';
                        $userDetail = $AgentData;
                    }
                    $json = array(
                        'result' => $result,
                        'response' => $response,
                        'user_detail' => $userDetail,
                    );

                    echo json_encode($json);
                    die();
                }
            }
        }
    }

}
