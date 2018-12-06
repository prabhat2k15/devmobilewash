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
//        $washRequestId = Yii::app()->request->getParam('washRequestId');
//        $flagged_val = Yii::app()->request->getParam('flagged_val');
        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flagged_val . "'     WHERE id=" . $washRequestId)->queryAll();
    }

    public function actionUpdateFlaggedIssueMultiple() {
        $flagVal = Yii::app()->request->getParam('flagVal');
        $resolvedValue = Yii::app()->request->getParam('resolvedValue');
        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flagVal . "'     WHERE id in( " . $resolvedValue . ")")->queryAll();
    }

}
