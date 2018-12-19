<?php

require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/amazon-sdk/aws-autoloader.php';

use Twilio\Rest\Client;
use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;

class DownloadsController extends Controller {

    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13102941020';
    protected $callbackurl = ROOT_URL . '/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex() {
        $this->render('index');
    }

    public function actionCustomerDownloadRequestSave() {

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
        $CustomerExpansionRequestExist = Download::model()->findByAttributes(array('customer_id' => $customerid, 'state' => $state));
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
        $customer = new Download;
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

    public function actionDownloadData() {
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
        $all_city = Yii::app()->db->createCommand("SELECT city, COUNT(id) as total FROM downloads  GROUP BY city ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_zipcode = Yii::app()->db->createCommand("SELECT zipcode, COUNT(id) as total FROM downloads  GROUP BY zipcode ORDER BY COUNT(id) DESC")
                ->queryAll();
        $json = array(
            'result' => $result,
            'response' => $response,
            'all_washes_city' => $all_city,
            'all_washes_zipcode' => $all_zipcode,
            'blue' => $blue,
            'yellow' => $yellow,
            'red' => $red,
            'purple' => $purple,
        );
        echo json_encode($json);
    }

}
