<?php

require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
require ROOT_WEBFOLDER . '/public_html/api/protected/extensions/amazon-sdk/aws-autoloader.php';

use Twilio\Rest\Client;
use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;

class washerFeedController extends Controller {

    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13102941020';
    protected $callbackurl = ROOT_URL . '/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex() {
        $this->render('index');
    }

    // get feed list Api
    public function actionGetFeedListAdmin() {
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
        $washerFeeds = washerFeeds::model()->findAll();

        if (count($washerFeeds) > 0) {
            foreach ($washerFeeds as $key => $val) {
                //print_r($val);
                $data[$key]['id'] = $val['id'] ? $val['id'] : ' ';
                $data[$key]['title'] = $val['title'] ? $val['title'] : ' ';
                $data[$key]['message'] = $val['message'] ? $val['message'] : ' ';
                $data[$key]['image'] = $val['image'] ? $val['image'] : ' ';
                $data[$key]['image_link'] = $val['image_link'] ? $val['image_link'] : ' ';
                $data[$key]['from_date'] = $val['from_date'] ? $val['from_date'] : ' ';
                $data[$key]['to_date'] = $val['to_date'] ? $val['to_date'] : ' ';
            }

            if (count($data) > 0) {
                echo json_encode(['status' => 1, 'data' => $data]);
            } else {
                echo json_encode(['status' => 0, 'data' => 'not found']);
            }
        }
    }

    public function actionGetFeedList() {
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
        $washerFeeds = washerFeeds::model()->findAll();
        if (count($washerFeeds) > 0) {
            $i = 0;
            foreach ($washerFeeds as $key => $val) {
                $currentDate = date('Y-m-d H:i:s');
                $currentDate = strtotime($currentDate);
                $fromDate = strtotime($val['from_date']);
                $toDate = strtotime($val['to_date']);
                if (($currentDate > $fromDate) && ($currentDate < $toDate)) {
                    $data[$i]['id'] = $val['id'] ? $val['id'] : ' ';
                    $data[$i]['title'] = $val['title'] ? $val['title'] : ' ';
                    $data[$i]['message'] = $val['message'] ? $val['message'] : ' ';
                    $data[$i]['image'] = $val['image'] ? $val['image'] : ' ';
                    $data[$i]['image_link'] = $val['image_link'] ? $val['image_link'] : ' ';
                    $data[$i]['from_date'] = $val['from_date'] ? $val['from_date'] : ' ';
                    $data[$i]['to_date'] = $val['to_date'] ? $val['to_date'] : ' ';
                    $i++;
                }
            }
            if (count($data) > 0) {
                echo json_encode(['status' => 1, 'data' => $data]);
            } else {
                echo json_encode(['status' => 0, 'data' => 'not found']);
            }
        }
    }

//update feed form by id 
    public function actionUpdateFeedById() {

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
        $stringfrom_date = strtotime($_POST['from_date']);
        $stringTo_date = strtotime($_POST['to_date']);
        if ($stringfrom_date > $stringTo_date) {
            echo json_encode(['status' => 0, 'message' => 'To date should be Greater than From date']);
            die;
        }
        $img = Yii::app()->request->getParam('image');
        $removeImage = Yii::app()->request->getParam('removeImage');
        $id = Yii::app()->request->getParam('id');
        $washer = washerFeeds::model()->findByPk($id);
        if ($removeImage) {
            $washer->image = "";
        }
        if ($img) {
            $data = base64_decode($img);
            $file = uniqid() . '.jpg';
            file_put_contents(ROOT_WEBFOLDER . '/public_html/api/images/washer_feed_images/' . $file, $data);
            $feedimg = ROOT_URL . '/api/images/washer_feed_images/' . $file;
            $washer->image = $feedimg;
        }

        $washer->title = Yii::app()->request->getParam('title');
        $washer->message = Yii::app()->request->getParam('message');
        $washer->image_link = Yii::app()->request->getParam('image_link');
        $washer->from_date = Yii::app()->request->getParam('from_date');
        $washer->to_date = Yii::app()->request->getParam('to_date');
        if ($washer->save()) {
            echo json_encode(['status' => 1, 'message' => 'Notification update sucessfully']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

}
