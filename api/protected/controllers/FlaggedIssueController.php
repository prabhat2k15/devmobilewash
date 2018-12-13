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
        $flaggedVal = Yii::app()->request->getParam('flaggedVal');
        $orderId = Yii::app()->request->getParam('orderId');
        $result = Yii::app()->db->createCommand("UPDATE washing_requests SET flagged_issue_status='" . $flaggedVal . "'     WHERE id=" . $orderId)->query();
    }

    public function actionTest() {
        //$result = Yii::app()->db->createCommand("mysqldump -u devmobil_mwuser -p9F;WPnZwCEscQ$*[K4 devmobil_mwmain > db_backup.sql")->query();
        //$x = exec("mysqldump --opt -u devmobil_mwuser -p'9F;WPnZwCEscQ$*[K4' devmobil_mwmain customers > db_backup1.csv");
        //$x = exec("SELECT * FROM customers  INTO OUTFILE '/var/www/public_html/orders1.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '' LINES TERMINATED BY '\n';");
        // $x = exec("mysqldump --opt -u devmobil_mwuser -p'9F;WPnZwCEscQ$*[K4' devmobil_mwmain customers > db_backup1.csv");
        // print_r($x);
        //$file = '/var/www/public_html/file.csv';
        // $file = 'file.csv';
        //  $sql = 'SELECT * FROM customers';
        //$cmd = 'mysql --host=localhost -u devmobil_mwuser -p9F;WPnZwCEscQ$*[K4 --quick  -e \''.$sql.'\' > '.$file.' 2>&1';
//  print_r($cmd);
        //$x=exec($cmd);
        //$cmd="SELECT * FROM customers INTO OUTFILE '/var/www/public_html/orders1.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '#' LINES TERMINATED BY '/n' ";
        //$y="SELECT * FROM customers  INTO OUTFILE 'orders.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '#' LINES TERMINATED BY '\n'  2>&1";
        //$x= exec($y);
        //print_r($x);
        $result = Yii::app()->db->createCommand("SELECT * FROM customers  INTO OUTFILE 'orders.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '#' LINES TERMINATED BY '\n'")->query();
        //$cmd="mysql --host=localhost -u devmobil_mwuser -p9F;WPnZwCEscQ$*[K4 customers OUTFILE 'orders1.csv' --fields-enclosed-by=\" --fields-terminated-by=,> test123.csv  2>&1";
        //$x= exec("mysqldump --opt -u devmobil_mwuser -p'9F;WPnZwCEscQ$*[K4' devmobil_mwmain customers --fields-enclosed-by='\' --fields-terminated-by=','> db_backup1.csv");
        print_r($result);
        die;
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
