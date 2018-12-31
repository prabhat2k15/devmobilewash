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
        $agent = Agents::model()->findAll();
        foreach ($agent as $val) {
            $name = $val->first_name . " " . $val->last_name;
            $result = Yii::app()->db->createCommand("UPDATE agents SET agentname='" . $name . "'     WHERE id=" . $val->id)->query();
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
