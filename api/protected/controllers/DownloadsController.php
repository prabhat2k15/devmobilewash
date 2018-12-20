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
        $source = Yii::app()->request->getParam('source');
        $CustomerExpansionRequestExist = Download::model()->findByAttributes(array('customer_id' => $customerid));
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
        $Download = new Download;
        $Download->customer_id = $customerid;
        $Download->country = $country;
        $Download->city = $city;
        $Download->zipcode = $zipcode;
        $Download->state = $state;
        $Download->source = $source;
        $Download->created_at = date('Y-m-d H:i:s');
        if ($Download->save()) {
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
        $from = Yii::app()->request->getParam('from');
        $to = Yii::app()->request->getParam('to');
        $from = $from . " 00:00:00";
        $to = $to . " 23:59:59";
        $purple = Yii::app()->db->createCommand("SELECT COUNT(d.id) as total FROM downloads d RIGHT JOIN coverage_area_zipcodes z ON d.zipcode = z.zipcode WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') AND z.zip_color='purple'")
                ->queryRow();
        $red = Yii::app()->db->createCommand("SELECT COUNT(d.id) as total FROM downloads d RIGHT JOIN coverage_area_zipcodes z ON d.zipcode = z.zipcode WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') AND z.zip_color='red'")
                ->queryRow();
        $yellow = Yii::app()->db->createCommand("SELECT COUNT(d.id) as total FROM downloads d RIGHT JOIN coverage_area_zipcodes z ON d.zipcode = z.zipcode WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') AND z.zip_color='yellow'")
                ->queryRow();
        $blue = Yii::app()->db->createCommand("SELECT COUNT(d.id) as total FROM downloads d RIGHT JOIN coverage_area_zipcodes z ON d.zipcode = z.zipcode WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') AND z.zip_color='blue'")
                ->queryRow();
        $all_city = Yii::app()->db->createCommand("SELECT city, COUNT(id) as total FROM downloads  WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') GROUP BY city ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_zipcode = Yii::app()->db->createCommand("SELECT zipcode, COUNT(id) as total FROM downloads  WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') GROUP BY zipcode ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_country = Yii::app()->db->createCommand("SELECT country, COUNT(id) as total FROM downloads WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') GROUP BY country ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_state = Yii::app()->db->createCommand("SELECT state, COUNT(id) as total FROM downloads WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "')  GROUP BY state ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_source = Yii::app()->db->createCommand("SELECT source, COUNT(id) as total FROM downloads WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "') GROUP BY source ORDER BY COUNT(id) DESC")
                ->queryAll();
        $all_data = Yii::app()->db->createCommand("SELECT * FROM downloads WHERE (created_at >= '" . $from . "' AND created_at <= '" . $to . "')  ORDER BY id DESC")
                ->queryAll();
        $json = array(
            'all_data' => $all_data,
            'all_city' => $all_city,
            'all_state' => $all_state,
            'all_source' => $all_source,
            'all_country' => $all_country,
            'all_zipcode' => $all_zipcode,
            'blue' => $blue['total'],
            'yellow' => $yellow['total'],
            'red' => $red['total'],
            'purple' => $purple['total'],
        );
        echo json_encode($json);
    }

}
