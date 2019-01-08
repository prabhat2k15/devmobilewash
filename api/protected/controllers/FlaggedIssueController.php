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

    public function actionTest() {
        
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
                    //echo "SMS disabled FOR Agent";
                    
                    $result = Yii::app()->db->createCommand("UPDATE agents SET sms_control=0  WHERE id=" . $user_devices_detail['agent_id'])->query();
                }
                curl_close($ch);
                echo "SMS enabled FOR Agent";
                /* --- notification call end --- */


                //echo $result;
                //die;
            }
        }
    }

    public function actionGetAllCustomersInCsv() {
        //$result = Customers::model()->findAll();
        $result = Yii::app()->db->createCommand("SELECT * FROM customers")->queryAll();
        //print_r($result); die;
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('ID'));
        //$result = mysqli_query($con, $query);
        while ($row = $result) {
            fputcsv($output, array($row['id']));
        }
        fclose($output);
        ob_end_clean();
    }

    public function actionTestcsv() {
        $result = Yii::app()->db->createCommand("SELECT * FROM customers")->queryAll();
        $directorypath = realpath(Yii::app()->basePath . '/../images/cust_img');
        $file = fopen($directorypath . '/file.csv', 'w');

        $columns = array('id', 'Customer Name', 'Email', 'Zip code');
        fputcsv($file, $columns);

        foreach ($result as $val) {
            fputcsv($file, array($val['id'], $val['first_name'] . " " . $val['last_name'], $val['email'], $val['zip']));
        }
        fclose($file);
        ob_end_clean();
        echo "done;";
    }

}
