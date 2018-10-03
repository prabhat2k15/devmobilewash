<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
require ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio-php-master/Twilio/autoload.php';
	// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
		echo "Direct access is Not Allowed.";
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


     public function actionGetCMS()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $id = Yii::app()->request->getParam('id');
         $getcms =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = :id ")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();

         $title = $getcms[0]['title'];
         $content = $getcms[0]['content'];
         $json = array(
                'title'=> $title,
                'content'=> $content
            );

             echo json_encode($json);
             exit;


    }
    public function actionGetAllCms()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $getcms =  Yii::app()->db->createCommand("SELECT * FROM cms ORDER BY id DESC ")->queryAll();
         $cms = array();
         foreach($getcms as $cmsdata){
         $key = 'cms_'.$cmsdata['id'];
         $json = array();
         $json['id'] = $cmsdata['id'];
         $json['title'] = $cmsdata['title'];
         $cms[$key] = $json;
         }
         $cmscontent['cms'] = $cms;

             echo json_encode($cmscontent);
             exit;


    }

  public function actionGetCmsDataadmin()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $id = Yii::app()->request->getParam('id');
         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = :id ")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();

         $title = $sitesettings[0]['title'];
         $content = stripslashes($sitesettings[0]['content']);


         $json = array(
                'title'=> $title,
                'content'=> $content
            );

             echo json_encode($json);
             exit;


    }

     public function actionDeleteCms()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $model=new Cms;
        $id = Yii::app()->request->getParam('id');
         $delcms = Cms::model()->deleteAll('id=:id', array(':id'=>$id));


             $json = array(
                'result'=> 'true',
                'response'=> 'delete'
            );
             echo json_encode($json);
             exit;


    }
     public function actioncms()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $model=new Cms;
         $title = Yii::app()->request->getParam('title');
         $content = Yii::app()->request->getParam('content');
         $id = Yii::app()->request->getParam('id');
         $array = array('title'=>$title,'content'=>$content);



         $update_cms = Cms::model()->updateAll($array,'id=:id',array(':id'=>$id));




         $json = array(
                'result'=> 'true',
                'response'=> 'Update Successfully'
            );
             echo json_encode($json);
             exit;


    }
    public function actionGetCmsData()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $id = Yii::app()->request->getParam('id');
         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = :id ")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();

         $title = $sitesettings[0]['title'];
         $content = stripslashes($sitesettings[0]['content']);

         $json = array(
                'title'=> $title,
                'content'=> $content
            );

             echo json_encode($json);
             exit;


    }

 public function actionaddreminder()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $client_reminder_titles = '';
 $client_reminder_contents = '';
 $washer_reminder_titles = '';
 $washer_reminder_contents = '';
$client_reminder = '';
          if(Yii::app()->request->getParam('client_reminder')) $client_reminder = Yii::app()->request->getParam('client_reminder');

  if(Yii::app()->request->getParam('agent_reminder')) $agent_reminder = Yii::app()->request->getParam('agent_reminder');

  $reminder_data = Reminders::model()->findByAttributes(array("id"=>1));

if(!$client_reminder){
$client_reminder = $reminder_data->client_reminder;
}

if(!$agent_reminder){
$agent_reminder = $reminder_data->washer_reminder;
}


$data = array("client_reminder" => $client_reminder, "washer_reminder" => $agent_reminder);


         $update_check = Reminders::model()->updateAll($data,'id=:id',array(':id'=>1));




         $json = array(
                'result'=> 'true',
                'response'=> 'Updated Successfully'
            );
             echo json_encode($json);
             exit;


    }

public function actiongetreminders()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


$reminders =  Yii::app()->db->createCommand("SELECT * FROM reminders WHERE id = 1 ")->queryAll();

         $client_reminder_titles = $reminders[0]['client_reminder_titles'];
$client_reminder_contents = $reminders[0]['client_reminder_contents'];
             $washer_reminder_titles = $reminders[0]['washer_reminder_titles'];
$washer_reminder_contents = $reminders[0]['washer_reminder_contents'];

         $json = array(
'result' => 'true',
                'client_reminder_titles'=> $client_reminder_titles,
'client_reminder_contents'=> $client_reminder_contents,
                'washer_reminder_titles'=> $washer_reminder_titles,
 'washer_reminder_contents'=> $washer_reminder_contents
            );

             echo json_encode($json);
             exit;


    }


public function actiongetremindersadmin()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


$reminders =  Yii::app()->db->createCommand("SELECT * FROM reminders WHERE id = 1 ")->queryAll();

         $client_reminder = json_decode($reminders[0]['client_reminder']);
 $washer_reminder = json_decode($reminders[0]['washer_reminder']);


         $json = array(
'result' => 'true',
                'client_reminder'=> $client_reminder,
'washer_reminder' => $washer_reminder
            );

             echo json_encode($json);
             exit;



    }

public function actiongetreminderstwo()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


$reminders =  Yii::app()->db->createCommand("SELECT * FROM reminders WHERE id = 1 ")->queryAll();

         $client_reminder = json_decode($reminders[0]['client_reminder']);
 $washer_reminder = json_decode($reminders[0]['washer_reminder']);
$cr_array = array();
$wr_array = array();

foreach($client_reminder as $i=> $cr){
$content = $cr->content;
$cr_array[$i]['title'] = $cr->title;

$dom = new DOMDocument;
$dom->loadHTML($content);
foreach($dom->getElementsByTagName('li') as $ind=> $node)
{
    $cr_array[$i]['content'][$ind] = strip_tags($dom->saveHTML($node));
}

}


foreach($washer_reminder as $i=> $wr){
$content = $wr->content;
$wr_array[$i]['title'] = $wr->title;

$dom = new DOMDocument;
$dom->loadHTML($content);
foreach($dom->getElementsByTagName('li') as $ind=> $node)
{
    $wr_array[$i]['content'][$ind] = strip_tags($dom->saveHTML($node));
}

}


         $json = array(
'result' => 'true',
                'client_reminder'=> $cr_array,
'washer_reminder' => $wr_array
            );

             echo json_encode($json);
             exit;


    }


 // visitors Year wise

    public function ActionVisitorsYearWise (){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $array = array();
        for($i = 6; $i >=0; $i--){
            $year = date("Y",strtotime('-'.$i." year"));
            $start_year =  $year.'-01-01'.' '.'00:00:00';
            $end_year =  $year.'-12-31'.' '.'23:59:59';

        $visitors = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `visitors` WHERE visitor_date BETWEEN '$start_year' AND '$end_year'  ")->queryAll();
        if(!empty($visitors)){

           $array[$year] += $visitors[0]['cnt'];

        }else{
            $array[$year] += 0;
        }

    }

        //exit;
        $json= $array;
        echo json_encode($json);
        die();
    }
    // end

// visitors Week wise

    public function ActionVisitorsWeekWise (){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $array = array();
         for($i = 6; $i >=0; $i--){
    $start_date =  date("Y-m-d", strtotime($i." days ago")).' '.'00:00:00';
    $end_date =  date("Y-m-d", strtotime($i." days ago")).' '.'23:59:59';

$day = date("D", strtotime($i." days ago"));

        $visitors = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `visitors` WHERE visitor_date BETWEEN '$start_date' AND '$end_date'  ")->queryAll();
        if(!empty($visitors)){
           $array[$day] += $visitors[0]['cnt'];

        }else{
            $array[$day] += 0;
        }
    }

        //exit;
        $json= $array;
        echo json_encode($json);
        die();
    }
    // end

// visitors month wise

    public function ActionVisitorsMonthWise (){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $start = mktime(0,0,0,date('m'), date('d'), date('Y'));;

// loop through the current and last four month
$array = array();
for($i = 6; $i >=0; $i--){
    // calculate the first day of the month
    $first = mktime(0,0,0,date('m',$start) - $i,1,date('Y',$start));

    // calculate the last day of the month
    $last = mktime(0, 0, 0, date('m') -$i + 1, 0, date('Y',$start));

    // now some output...
    $month =  date('M',$first);
    $irstdate =  date('Y-m-d',$first).' '.'12:00:00';
    $lastdate = date('Y-m-d',$last).' '.'3:53:04';
        $visitors = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `visitors` WHERE visitor_date BETWEEN '$irstdate' AND '$lastdate'  ")->queryAll();
        if(!empty($visitors)){
        //echo "SELECT * FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$irstdate' AND '$lastdate' ".'<br />';

           $array[$month] += $visitors[0]['cnt'];

        }else{
            $array[$month] += 0;
        }

    }

        //exit;
        $json= $array;
        echo json_encode($json);
        die();
    }
    // end

public function actionshedule()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $model=new Shedule;
         $from_monday = Yii::app()->request->getParam('from_monday');
         $to_monday = Yii::app()->request->getParam('to_monday');
         $from_tuesday = Yii::app()->request->getParam('from_tuesday');
         $to_tuesday = Yii::app()->request->getParam('to_tuesday');
         $from_wednesday = Yii::app()->request->getParam('from_wednesday');
         $to_wednesday = Yii::app()->request->getParam('to_wednesday');
         $from_thursday = Yii::app()->request->getParam('from_thursday');
         $to_thursday = Yii::app()->request->getParam('to_thursday');
         $from_friday = Yii::app()->request->getParam('from_friday');
         $to_friday = Yii::app()->request->getParam('to_friday');
         $from_saturday = Yii::app()->request->getParam('from_saturday');
         $to_saturday = Yii::app()->request->getParam('to_saturday');
         $from_sunday = Yii::app()->request->getParam('from_sunday');
         $to_sunday = Yii::app()->request->getParam('to_sunday');
		 /*if off*/
         $monday_off = Yii::app()->request->getParam('monday_off');
$monday_all_day = Yii::app()->request->getParam('monday_all_day');
         $tuesday_off = Yii::app()->request->getParam('tuesday_off');
 $tuesday_all_day = Yii::app()->request->getParam('tuesday_all_day');
         $wednesday_off = Yii::app()->request->getParam('wednesday_off');
$wednesday_all_day = Yii::app()->request->getParam('wednesday_all_day');
         $thursday_off = Yii::app()->request->getParam('thursday_off');
$thursday_all_day = Yii::app()->request->getParam('thursday_all_day');
         $friday_off = Yii::app()->request->getParam('friday_off');
$friday_all_day = Yii::app()->request->getParam('friday_all_day');
         $saturday_off = Yii::app()->request->getParam('saturday_off');
$saturday_all_day = Yii::app()->request->getParam('saturday_all_day');
         $sunday_off = Yii::app()->request->getParam('sunday_off');
$sunday_all_day = Yii::app()->request->getParam('sunday_all_day');

$business_unavail_notice = '';

$business_unavail_notice = Yii::app()->request->getParam('business_unavail_notice');
		 /*end*/
		 //$delete =  Shedule::model()->deleteAll("id!='0'");
         if(!empty($from_monday)){
		 $monday = array('day'=>'monday','from'=>$from_monday,'to'=>$to_monday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_tuesday)){
         $tuesday = array('day'=>'tuesday','from'=>$from_tuesday,'to'=>$to_tuesday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_wednesday)){
         $wednesday = array('day'=>'wednesday','from'=>$from_wednesday,'to'=>$to_wednesday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_thursday)){
         $thursday = array('day'=>'thursday','from'=>$from_thursday,'to'=>$to_thursday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_friday)){
         $friday = array('day'=>'friday','from'=>$from_friday,'to'=>$to_friday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_saturday)){
         $saturday = array('day'=>'saturday','from'=>$from_saturday,'to'=>$to_saturday,'status'=>'on', 'open_all_day' => '');
		 }
		 if(!empty($from_sunday)){
         $sunday = array('day'=>'sunday','from'=>$from_sunday,'to'=>$to_sunday,'status'=>'on', 'open_all_day' => '');
		 }
		 /*if status*/
		 if(!empty($monday_off)){
		 $monday = array('day'=>'monday','from'=>'','to'=>'','status'=>$monday_off, 'open_all_day' => '');
		 }
if(!empty($monday_all_day)){
		 $monday = array('day'=>'monday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $monday_all_day);
		 }

		 if(!empty($tuesday_off)){
		 $tuesday = array('day'=>'tuesday','from'=>'','to'=>'','status'=>$tuesday_off, 'open_all_day' => '');
		 }
 if(!empty($tuesday_all_day)){
		 $tuesday = array('day'=>'tuesday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $tuesday_all_day);
		 }

		 if(!empty($wednesday_off)){
		 $wednesday = array('day'=>'wednesday','from'=>'','to'=>'','status'=>$wednesday_off, 'open_all_day' => '');
		 }

 if(!empty($wednesday_all_day)){
		 $wednesday = array('day'=>'wednesday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $wednesday_all_day);
		 }

		 if(!empty($thursday_off)){
		 $thursday = array('day'=>'thursday','from'=>'','to'=>'','status'=>$thursday_off, 'open_all_day' => '');
		 }

 if(!empty($thursday_all_day)){
		 $thursday = array('day'=>'thursday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $thursday_all_day);
		 }
		 if(!empty($friday_off)){
		 $friday = array('day'=>'friday','from'=>'','to'=>'','status'=>$friday_off, 'open_all_day' => '');
		 }
 if(!empty($friday_all_day)){
		 $friday = array('day'=>'friday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $friday_all_day);
		 }
		 if(!empty($saturday_off)){
		 $saturday = array('day'=>'saturday','from'=>'','to'=>'','status'=>$saturday_off, 'open_all_day' => '');
		 }
 if(!empty($saturday_all_day)){
		 $saturday = array('day'=>'saturday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $saturday_all_day);
		 }
		 if(!empty($sunday_off)){
		 $sunday = array('day'=>'sunday','from'=>'','to'=>'','status'=>$sunday_off, 'open_all_day' => '');
		 }

 if(!empty($sunday_all_day)){
		 $sunday = array('day'=>'sunday','from'=>'','to'=>'','status'=>'', 'open_all_day' => $sunday_all_day);
		 }

if(empty($business_unavail_notice)){
$business_unavail_notice = 'Sorry, MobileWash is currently closed. Please check back later.';
}
		 /*end*/
         $array = array('monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday,'saturday'=>$saturday,'sunday'=>$sunday);

		 $array = array_filter($array);
         $i = 1;
		 foreach($array as $k=>$v ){
			 $data_a['day'] = $v[''];
shedule::model()->updateByPk($i,$v);
			// Yii::app()->db->createCommand()->insert('shedule',$v);
$i++;
		 }


	shedule::model()->updateByPk(8,array('status'=> $business_unavail_notice));


         $json = array(
                'result'=> 'true',
                'response'=> 'done'
            );
             echo json_encode($json);
             exit;


    }

	public function actionsheduleresult()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$sitesettingsm =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'monday' ")->queryAll();
		$sitesettingst =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'tuesday' ")->queryAll();
		$sitesettingsw =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'wednesday' ")->queryAll();
		$sitesettingsth =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'thursday' ")->queryAll();
		$sitesettingsf =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'friday' ")->queryAll();
		$sitesettingsst =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'saturday' ")->queryAll();
		$sitesettingssu =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = 'sunday' ")->queryAll();
$sitesettingstext =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE id = 8")->queryAll();

         $value = $sitesettings[0]['value'];
         $fromdate = $sitesettings[0]['fromdate'];
         $enddate = $sitesettings[0]['enddate'];
         $message = $sitesettings[0]['message'];
         $json = array(
                'monday'=> $sitesettingsm,
                'tuesday'=> $sitesettingst,
                'wednesday'=> $sitesettingsw,
                'thursday'=> $sitesettingsth,
                'friday'=> $sitesettingsf,
                'saturday'=> $sitesettingsst,
                'sunday'=> $sitesettingssu,
'status_text'=> $sitesettingstext
            );
             echo json_encode($json);
             exit;
	}

	public function actionscheduletime()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$date = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
		$day = $date->format('l');
		$currentday = strtolower($day);
		$currenttime = strtotime($date->format('h:i:s A'));

		$settings =  Yii::app()->db->createCommand("SELECT * FROM shedule WHERE day = '$currentday' ")->queryAll();

		/*check if particular day off */
		if(!empty($settings[0]['status'])){
			$result = true;
			$response = 'closed';
			$json = array(
                'result'=> $result,
                'response'=> $response
            );
             echo json_encode($json);
             exit;
		}
		/* end */



		/*check if particular day open and available or not */
		$from = $settings[0]['from'];
		$to = $settings[0]['to'];

		if($currenttime > strtotime($from) && $currenttime < strtotime($to)){
			$result = true;
			$response = 'open';
			$opentime = $from;
			$closedtime = $to;
			$json = array(
                'result'=> $result,
                'response'=> $response,
                'opentime'=> $opentime,
                'closedtime'=> $closedtime
            );
             echo json_encode($json);
             exit;
		}else{
			$result = true;
			$response = 'closed';
			$opentime = $from;
			$closedtime = $to;
			$json = array(
                'result'=> $result,
                'response'=> $response,
                'opentime'=> $opentime,
                'closedtime'=> $closedtime
            );
             echo json_encode($json);
             exit;
		}
		/* end */

	}


/**
     * save site settings.
     */
    public function actionSaveSiteSettings()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $from_date = Yii::app()->request->getParam('from_date');
        $to_date = Yii::app()->request->getParam('to_date');
        $site_settings = Yii::app()->request->getParam('site_settings');
        $message = Yii::app()->request->getParam('message');

        if(!empty($from_date) && !empty($to_date) && !empty($message))
        {

            $sitesettings_data =  Yii::app()->db->createCommand("SELECT * FROM site_settings ")->queryAll();

            if(!empty($sitesettings_data))
            {

                $id = $sitesettings_data[0]['id'];
                $model=new Sitesettings;
                $data = array('key'=> 'site sttings','value'=> $site_settings,'fromdate'=> $from_date,'enddate'=> $to_date,'message'=> $message);

                $update_orders = Sitesettings::model()->updateAll($data,'id=:id',array(':id'=>$id));

                $result = 'true';
                $response = 'save successfully';
                $fromdate = $from_date;
                $enddate = $to_date;
                $status = $site_settings;
                $json = array(
                'result'=> $result,
                'response'=> $response,
                'fromdate'=> $fromdate,
                'enddate'=> $enddate,
                'site_service'=> $status
            );echo json_encode($json);
            die();
            }
            else{
            $sitesettings = Yii::app()->db->createCommand("INSERT INTO `site_settings` (`key`, `value`, `fromdate`, `enddate`, `message`)
VALUES ('site sttings', '$site_settings', '$from_date', '$to_date', '$message'); ")->queryAll();
        $result = 'true';
                $response = 'save successfully';
                $result = 'true';
                $fromdate = $from_date;
                $enddate = $to_date;
                $status = $site_settings;
               $json = array(
                'result'=> $result,
                'response'=> $response,
                'fromdate'=> $fromdate,
                'enddate'=> $enddate,
                'site_service'=> $status
            );echo json_encode($json);
            die();
            }
        }
        else
        {

            $result = 'false';
            $response = 'Something Wrong';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
        }

    }

    /**
     * Get site settings.
     */
    public function actionGetSiteConfiguration()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$go_online = 0;
 $go_online = Yii::app()->request->getParam('go_online');
 $agent_id = 0;
 $agent_id = Yii::app()->request->getParam('agent_id');
 $agent_detail = Agents::model()->findByAttributes(array("id"=>$agent_id));
   if($go_online == 1){

date_default_timezone_set('America/Los_Angeles');

   /* -------- hours of operation -------- */


        $current_day = strtolower(date('l'));

        $hours_op_check =  Shedule::model()->findByAttributes(array('day'=>$current_day));
        $hours_op_response =  Shedule::model()->findByAttributes(array('id'=>8));

         /* -------- hours of operation end -------- */

        if(($agent_detail->hours_opt_check) && ($hours_op_check->status == 'off')){
            	$json = array(
					'result'=> 'false',
					'response'=> $hours_op_response->status

					);

				echo json_encode($json); die();
        }


if( ($agent_detail->hours_opt_check) && ($hours_op_check->open_all_day != 'yes') && ((time() < strtotime($hours_op_check->from)) || (time() > strtotime($hours_op_check->to)))){
  	$json = array(
					'result'=> 'false',
					'response'=> $hours_op_response->status

					);

				echo json_encode($json); die();

        }

       }

         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM site_settings ")->queryAll();

         $value = $sitesettings[0]['value'];
         $fromdate = $sitesettings[0]['fromdate'];
         $enddate = $sitesettings[0]['enddate'];
         $message = $sitesettings[0]['message'];
         $json = array(
                'site_service'=> $value,
                'result' => 'true',
                'fromdate'=> $fromdate,
                'enddate'=> $enddate,
                'message'=> $message
            );
             echo json_encode($json);
             exit;


    }

 public function actionGetBackupFile()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$data =  Yii::app()->db->createCommand("SELECT * FROM db_backup ")->queryAll();


         $file = array();
         foreach($data as $ind=> $backup){
                    $file[$ind]['id'] = $backup['id'];
                    $file[$ind]['date'] = $backup['date'];
                    $file[$ind]['filename'] = $backup['filename'];
                }
         $json = array(
'result' => 'true',
                'data'=> $file
            );




             echo json_encode($json);
             exit;


    }

   public function actiongetallnewslettersubscribers(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_subscribers = array();

        $result= 'false';
		$response= 'none';

        $user_exists = Yii::app()->db->createCommand()->select('*')->from('newsletter_subscribers')->order('id ASC')->queryAll();

        if(count($user_exists)>0){
           $result= 'true';
		    $response= 'all subscribers';

            foreach($user_exists as $ind=>$user){

                $all_subscribers[$ind]['id'] = $user['id'];
				$all_subscribers[$ind]['name'] = $user['name'];
				$all_subscribers[$ind]['email'] = $user['email'];
				$all_subscribers[$ind]['subscription_status'] = $user['subscription_status'];
				$all_subscribers[$ind]['subscription_date'] = $user['subscription_date'];

            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'subscribers'=> $all_subscribers
		);
		echo json_encode($json);

    }


  public function actionDeleteSubscriber(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $result= 'false';
		$response= 'Please provide subscriber id';

		$subscriber_id = Yii::app()->request->getParam('subscriber_id');



		if((isset($subscriber_id) && !empty($subscriber_id)))
		{

            $subscriber_exists = NewsletterSubscribers::model()->findByAttributes(array("id"=>$subscriber_id));
              if(!count($subscriber_exists)){
                 $response = "Invalid subscriber id";
              }


           else{
				$response = "Subscriber deleted";
                $result = 'true';

                  NewsletterSubscribers::model()->deleteByPk(array('id'=>$subscriber_id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }

public function actionaddnewslettersubscriber(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

		$name = '';
        $name = Yii::app()->request->getParam('name');
		$email = Yii::app()->request->getParam('email');



		if(isset($email) && !empty($email))

		{

			$subscriber_exists = NewsletterSubscribers::model()->findByAttributes(array("email"=>$email));
			if(count($subscriber_exists)){
				$response = "Subscriber exists";

			}
			else{

				$data= array(
					'name'=> $name,
					'email'=> $email,
					'subscription_status'=> 1,
					'subscription_date'=> date("Y-m-d H:i:s")
				);

				$model=new NewsletterSubscribers;
				$model->attributes= $data;
				if($model->save(false)){
					$result= 'true';
					$response= 'subscriber added successfully';
				}
			}
		}


		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}


public function actionaddnewsletter(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

        $title = Yii::app()->request->getParam('title');
		$content = Yii::app()->request->getParam('content');
		$receivers = Yii::app()->request->getParam('receivers');


		if((isset($title) && !empty($title)) &&
			(isset($content) && !empty($content)) &&
			(isset($receivers) && !empty($receivers)))

			 {

                   $data= array(
					'title'=> $title,
					'content'=> $content,
					'receivers'=> $receivers,

				);

				    $model=new Newsletters;
				    $model->attributes= $data;
				    if($model->save(false)){



                    	$result= 'true';
		$response= 'newsletter added successfully';
                }
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actioneditnewsletter(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
        $title = Yii::app()->request->getParam('title');
		$content = Yii::app()->request->getParam('content');
		$receivers = Yii::app()->request->getParam('receivers');


		if((isset($id) && !empty($id)))

			 {

$letter_check = Newsletters::model()->findByAttributes(array("id"=>$id));

             	if(!count($letter_check)){
                   	$result= 'false';
		$response= "Newsletter doesn't exists";
                }
else{

 if(!$title){
$title = $letter_check->title;
}

if(!$content){
$content = $letter_check->content;
}

if(!$receivers){
$receivers = $letter_check->receivers;
}


                   $data= array(
					'title'=> $title,
					'content'=> $content,
					'receivers'=> $receivers,

				);


				   $resUpdate = Yii::app()->db->createCommand()->update('newsletters', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'Newsletter updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetallnewsletters(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_newsletters = array();

        $result= 'false';
		$response= 'none';

        $letter_exists = Yii::app()->db->createCommand()->select('*')->from('newsletters')->order('id ASC')->queryAll();

        if(count($letter_exists)>0){
           $result= 'true';
		    $response= 'all newsletters';

            foreach($letter_exists as $ind=>$letter){

                $all_newsletters[$ind]['id'] = $letter['id'];
 $all_newsletters[$ind]['title'] = $letter['title'];
 $all_newsletters[$ind]['content'] = $letter['content'];
 $all_newsletters[$ind]['receivers'] = $letter['receivers'];


            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'newsletters'=> $all_newsletters
		);
		echo json_encode($json);

    }

 public function actiongetnewsletterbyid(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');


		if((isset($id) && !empty($id)))
		{
			$letter_check = Newsletters::model()->findByAttributes(array("id"=>$id));

             	if(!count($letter_check)){
                   	$result= 'false';
		$response= "Newsletter doesn't exists";
                }

                else{


                   $data= array(
					'title'=> $letter_check->title,
					'content'=> $letter_check->content,
					'receivers'=> $letter_check->receivers,
				);


					$result= 'true';
					$response= 'newsletter details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'newsletter_details'=> $data
		);
		echo json_encode($json);
	}

public function actionDeleteNewsletter(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $result= 'false';
		$response= 'Please provide newsletter id';

		$id = Yii::app()->request->getParam('id');



		if((isset($id) && !empty($id)))
		{

            $letter_exists = Newsletters::model()->findByAttributes(array("id"=>$id));
              if(!count($letter_exists)){
                 $response = "Invalid newsletter id";
              }


           else{
				$response = "Newsletter deleted";
                $result = 'true';

                  Newsletters::model()->deleteByPk(array('id'=>$id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }


public function actionsendnewsletter(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$result= 'false';
		$response= 'please provide email and newsletter id';

		$email = Yii::app()->request->getParam('email');
$newsletter_id = Yii::app()->request->getParam('newsletter_id');

if((isset($email) && !empty($email)) && (isset($newsletter_id) && !empty($newsletter_id))){

$letter_exists = Newsletters::model()->findByAttributes(array("id"=>$newsletter_id));

              if(!count($letter_exists)){
                 $response = "Invalid newsletter id";
              }

else{


$user_exists = NewsletterSubscribers::model()->findByAttributes(array("email"=>$email));
if(trim($user_exists->name)){
$user_fullname = trim($user_exists->name);
$user_name_arr = explode(" ",$user_exists->name);
$user_fname = $user_name_arr[0];
$user_lname = $user_name_arr[1];
}
else{
$user_fname = 'Subscriber';
$user_lname = 'Subscriber';
$user_fullname = 'Subscriber';
}


 $from = Vargas::Obj()->getAdminFromEmail();
					//echo $from;
					$subject = $letter_exists->title;
					$message = $letter_exists->content;

$message = str_replace("[USER_FIRSTNAME]",$user_fname,$message);
$message = str_replace("[USER_LASTNAME]",$user_lname,$message);
$message = str_replace("[USER_FULLNAME]",$user_fullname,$message);

   if(Vargas::Obj()->SendMail($email,$from,$message,$subject, 'mail-newsletter')){

$result= 'true';
		$response= 'newsletter sent';
}
else{

$result= 'false';
		$response= 'newsletter not sent';
}
}

}

$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

}


public function actiongetallpushmessages(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_msgs = array();

        $result= 'false';
		$response= 'none';

        $msg_exists = Yii::app()->db->createCommand()->select('*')->from('push_messages')->order('id ASC')->queryAll();

        if(count($msg_exists)>0){
           $result= 'true';
		    $response= 'all push messages';

            foreach($msg_exists as $ind=>$msg){

                $all_msgs[$ind]['id'] = $msg['id'];
 $all_msgs[$ind]['message'] = $msg['message'];



            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'push_messages'=> $all_msgs
		);
		echo json_encode($json);

    }


 public function actiongetpushmessagebyid(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');


		if((isset($id) && !empty($id)))
			 {

             $msg_check = Yii::app()->db->createCommand()->select('*')->from('push_messages')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($msg_check)){
                   	$result= 'false';
		$response= "message doesn't exists";
                }

                else{


                   $data= array(

					'message'=> $msg_check[0]['message'],

				);


                    	$result= 'true';
		$response= 'message details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'message'=> $data
		);
		echo json_encode($json);
	}



public function actionupdatepushmessage(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
        $title = Yii::app()->request->getParam('title');
		$message = Yii::app()->request->getParam('message');



		if((isset($id) && !empty($id)))

			 {

 $msg_check = Yii::app()->db->createCommand()->select('*')->from('push_messages')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($msg_check)){
                   	$result= 'false';
		$response= "push message doesn't exists";
                }
else{



if(!$message){
$message = $msg_check->message;
}



                   $data= array(

					'message'=> $message


				);


				   $resUpdate = Yii::app()->db->createCommand()->update('push_messages', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'push message updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}

public function actionupdatepromopopup(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
        $promo_img_url = Yii::app()->request->getParam('promo_img_url');
		$promo_status = Yii::app()->request->getParam('promo_status');



		if((isset($id) && !empty($id)))

			 {

$promo_check = Yii::app()->db->createCommand()->select('*')->from('promo_popups')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($promo_check)){
                   	$result= 'false';
		$response= "Promo doesn't exists";
                }
else{

 if(!$promo_img_url){
$promo_img_url = $promo_check[0]['promo_img_url'];
}

if(!$promo_status){
$promo_status = $promo_check[0]['promo_status'];
}


                   $data= array(
					'promo_img_url'=> $promo_img_url,
					'promo_status'=> $promo_status

				);


				   $resUpdate = Yii::app()->db->createCommand()->update('promo_popups', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'promo updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetallpromopopups(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_pops = array();

        $result= 'false';
		$response= 'none';

        $pop_exists = Yii::app()->db->createCommand()->select('*')->from('promo_popups')->order('id ASC')->queryAll();

        if(count($pop_exists)>0){
           $result= 'true';
		    $response= 'all promo popups';

            foreach($pop_exists as $ind=>$pop){

                $all_pops[$ind]['id'] = $pop['id'];
$all_pops[$ind]['promo_img_url'] = $pop['promo_img_url'];
$all_pops[$ind]['promo_status'] = $pop['promo_status'];


            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'promo_popups'=> $all_pops
		);
		echo json_encode($json);

    }


public function actiongetpromopopupbyid(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');


		if((isset($id) && !empty($id)))
			 {

             $pop_check = Yii::app()->db->createCommand()->select('*')->from('promo_popups')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($pop_check)){
                   	$result= 'false';
		$response= "promo popup doesn't exists";
                }

                else{


                   $data= array(
					'promo_img_url'=> $pop_check[0]['promo_img_url'],
					'promo_status'=> $pop_check[0]['promo_status'],

				);


                    	$result= 'true';
		$response= 'promo details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'promo'=> $data
		);
		echo json_encode($json);
	}


 public function actiongetdiscountsettings(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');


		if((isset($id) && !empty($id)))
			 {

            $setting_check = Yii::app()->db->createCommand()->select('*')->from('discount_settings')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($setting_check)){
                   	$result= 'false';
		$response= "No settings found";
                }

                else{


                   $data= array(
					'first_wash_discount'=> $setting_check[0]['first_wash_discount'],

				);


                    	$result= 'true';
		$response= 'discount settings';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'discount_settings'=> $data
		);
		echo json_encode($json);
	}


public function actionupdatediscountsettings(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
        $first_wash_discount = Yii::app()->request->getParam('first_wash_discount');


		if((isset($id) && !empty($id)))

			 {

$setting_check = Yii::app()->db->createCommand()->select('*')->from('discount_settings')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($setting_check)){
                   	$result= 'false';
		$response= "settings doesn't exists";
                }
else{

 if(!$first_wash_discount){
$first_wash_discount = $setting_check[0]['first_wash_discount'];
}


                   $data= array(
					'first_wash_discount'=> $first_wash_discount,


				);


				   $resUpdate = Yii::app()->db->createCommand()->update('discount_settings', $data,"id=:id", array(":id" =>$id));

                    	$result= 'true';
		$response= 'settings updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}



public function actionaddwebhooklog()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $webhook_kind = Yii::app()->request->getParam('webhook_kind');
$webhook_kind_id = Yii::app()->request->getParam('webhook_kind_id');
$webhook_details = Yii::app()->request->getParam('webhook_details');


Yii::app()->db->createCommand("INSERT INTO `webhook_logs` (`webhook_kind`, `webhook_kind_id`, `details`) VALUES ('$webhook_kind', '$webhook_kind_id', '$webhook_details') ")->execute();


         $json = array(
                'result'=> 'true',
                'response'=> 'log added successfully'
            );
             echo json_encode($json);
             exit;


    }


public function actionupdatescheduletimes(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $mon = Yii::app()->request->getParam('mon');
         $tue = Yii::app()->request->getParam('tue');
 $wed = Yii::app()->request->getParam('wed');
 $thurs = Yii::app()->request->getParam('thurs');
 $fri = Yii::app()->request->getParam('fri');
 $sat = Yii::app()->request->getParam('sat');
 $sun = Yii::app()->request->getParam('sun');

 $mon_spec = Yii::app()->request->getParam('mon_spec');
         $tue_spec = Yii::app()->request->getParam('tue_spec');
 $wed_spec = Yii::app()->request->getParam('wed_spec');
 $thurs_spec = Yii::app()->request->getParam('thurs_spec');
 $fri_spec = Yii::app()->request->getParam('fri_spec');
 $sat_spec = Yii::app()->request->getParam('sat_spec');
 $sun_spec = Yii::app()->request->getParam('sun_spec');


$schedule_times = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=1')->queryAll();
$schedule_times_spec = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=2')->queryAll();

 if(!$mon){
$mon = $schedule_times[0]['mon'];
}

 if(!$tue){
$tue = $schedule_times[0]['tue'];
}

 if(!$wed){
$wed = $schedule_times[0]['wed'];
}

 if(!$thurs){
$thurs = $schedule_times[0]['thurs'];
}

 if(!$fri){
$fri = $schedule_times[0]['fri'];
}

 if(!$sat){
 $sat = $schedule_times[0]['sat'];
}

 if(!$sun){
 $sun = $schedule_times[0]['sun'];
}

 if(!$mon_spec){
$mon_spec = $schedule_times_spec[0]['mon'];
}

 if(!$tue_spec){
$tue_spec = $schedule_times_spec[0]['tue'];
}

 if(!$wed_spec){
$wed_spec = $schedule_times_spec[0]['wed'];
}

 if(!$thurs_spec){
$thurs_spec = $schedule_times_spec[0]['thurs'];
}

 if(!$fri_spec){
$fri_spec = $schedule_times_spec[0]['fri'];
}

 if(!$sat_spec){
 $sat_spec = $schedule_times_spec[0]['sat'];
}

 if(!$sun_spec){
 $sun_spec = $schedule_times_spec[0]['sun'];
}





                   $data= array(
					'mon'=> $mon,
					'tue'=> $tue,
                    'wed'=> $wed,
                    'thurs'=> $thurs,
					'fri'=> $fri,
                    'sat'=> $sat,
                    'sun'=>  $sun
				);

 $data2= array('mon'=> $mon_spec,
					'tue'=> $tue_spec,
                    'wed'=> $wed_spec,
                    'thurs'=> $thurs_spec,
					'fri'=> $fri_spec,
                    'sat'=> $sat_spec,
                    'sun'=>  $sun_spec
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('schedule_times', $data,"id=1");
$resUpdate2 = Yii::app()->db->createCommand()->update('schedule_times', $data2,"id=2");

                    	$result= 'true';
		$response= 'schedule times updated successfully';



		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetscheduletimes(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

$times = array();

$schedule_times = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=1')->queryAll();
$schedule_times_spec = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=2')->queryAll();


$times['mon'] = $schedule_times[0]['mon'];

$times['tue'] = $schedule_times[0]['tue'];

$times['wed'] = $schedule_times[0]['wed'];

$times['thurs'] = $schedule_times[0]['thurs'];

$times['fri'] = $schedule_times[0]['fri'];

$times['sat'] = $schedule_times[0]['sat'];

$times['sun'] = $schedule_times[0]['sun'];

$times['mon_spec'] = $schedule_times_spec[0]['mon'];

$times['tue_spec'] = $schedule_times_spec[0]['tue'];

$times['wed_spec'] = $schedule_times_spec[0]['wed'];

$times['thurs_spec'] = $schedule_times_spec[0]['thurs'];

$times['fri_spec'] = $schedule_times_spec[0]['fri'];

$times['sat_spec'] = $schedule_times_spec[0]['sat'];

$times['sun_spec'] = $schedule_times_spec[0]['sun'];



                    	$result= 'true';
		$response= 'schedule times';



		$json= array(
			'result'=> $result,
			'response'=> $response,
'schedule_times' => $times
		);
		echo json_encode($json);
	}
	
	
	public function actionupdateondemandsurgetimes(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $mon = Yii::app()->request->getParam('mon');
         $tue = Yii::app()->request->getParam('tue');
 $wed = Yii::app()->request->getParam('wed');
 $thurs = Yii::app()->request->getParam('thurs');
 $fri = Yii::app()->request->getParam('fri');
 $sat = Yii::app()->request->getParam('sat');
 $sun = Yii::app()->request->getParam('sun');
  $message = Yii::app()->request->getParam('message');

$schedule_times = Yii::app()->db->createCommand()->select('*')->from('ondemand_surge_times')->where('id=1')->queryAll();

 if(!$mon){
$mon = $schedule_times[0]['mon'];
}

 if(!$tue){
$tue = $schedule_times[0]['tue'];
}

 if(!$wed){
$wed = $schedule_times[0]['wed'];
}

 if(!$thurs){
$thurs = $schedule_times[0]['thurs'];
}

 if(!$fri){
$fri = $schedule_times[0]['fri'];
}

 if(!$sat){
 $sat = $schedule_times[0]['sat'];
}

 if(!$sun){
 $sun = $schedule_times[0]['sun'];
}

 if(!$message){
 $message = $schedule_times[0]['message'];
}

 

                   $data= array(
					'mon'=> $mon,
					'tue'=> $tue,
                    'wed'=> $wed,
                    'thurs'=> $thurs,
					'fri'=> $fri,
                    'sat'=> $sat,
                    'sun'=>  $sun,
		    'message'=>  $message
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('ondemand_surge_times', $data,"id=1");

                    	$result= 'true';
		$response= 'ondemand surge times updated successfully';



		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetondemandsurgetimes(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

$times = array();

$schedule_times = Yii::app()->db->createCommand()->select('*')->from('ondemand_surge_times')->where('id=1')->queryAll();


$times['mon'] = $schedule_times[0]['mon'];

$times['tue'] = $schedule_times[0]['tue'];

$times['wed'] = $schedule_times[0]['wed'];

$times['thurs'] = $schedule_times[0]['thurs'];

$times['fri'] = $schedule_times[0]['fri'];

$times['sat'] = $schedule_times[0]['sat'];

$times['sun'] = $schedule_times[0]['sun'];

$times['message'] = $schedule_times[0]['message'];




                    	$result= 'true';
		$response= 'ondemand surge times';



		$json= array(
			'result'=> $result,
			'response'=> $response,
'schedule_times' => $times
		);
		echo json_encode($json);
	}
	
	
	public function actiongetorderwashnowfee(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

if(Yii::app()->request->getParam('timezone')){
date_default_timezone_set(Yii::app()->request->getParam('timezone'));
}
else{
date_default_timezone_set('America/Los_Angeles');
}
 			  
		$result= 'false';
		$response= 'Fill up required fields';
		$wash_now_fee = 0;
		$wash_unavailable = 1;

$times = '';
$current_date_time = date('Y-m-d H:i:s');
$current_date = date('Y-m-d');
$current_day = date('l');
$current_time = date('g:i A');
//echo $current_date."<br>".$current_day."<br>".$current_time;
$schedule_times = Yii::app()->db->createCommand()->select('*')->from('ondemand_surge_times')->where('id=1')->queryAll();


if($current_day == 'Monday') $times = $schedule_times[0]['mon'];
if($current_day == 'Tuesday') $times = $schedule_times[0]['tue'];
if($current_day == 'Wednesday') $times = $schedule_times[0]['wed'];
if($current_day == 'Thursday') $times = $schedule_times[0]['thurs'];
if($current_day == 'Friday') $times = $schedule_times[0]['fri'];
if($current_day == 'Saturday') $times = $schedule_times[0]['sat'];
if($current_day == 'Sunday') $times = $schedule_times[0]['sun'];

//echo "<br>".$times;

$times_arr = explode("|",$times);

foreach($times_arr as $time){
$time_detail = explode(",",$time);
//if($time_detail[1] == 'inactive') continue;
$start = strtotime($current_date." ".$time_detail[0]);
$end = strtotime($current_date." ".$time_detail[0]." +14 minutes 59 seconds");
//echo $start." ".$end." ".date('h:i:s A', $start)." ".date('h:i:s A', $end)."<br>";

if(time() >= $start && time() <= $end) {
  $wash_now_fee = $time_detail[2];
  $wash_unavailable = 0;
  if($time_detail[1] == 'inactive') $wash_unavailable = 1;
  break;
} 
}


if($wash_unavailable){
$result= 'false';
		$response= $schedule_times[0]['message'];	
}
else{
$result= 'true';
		$response= 'wash now fee and timing';	
}

                    	



		$json= array(
			'result'=> $result,
			'response'=> $response,
'current_date_time' => $current_date_time,
'current_day' => $current_day,
'current_time' => $current_time,
'wash_now_fee' => $wash_now_fee

		);
		echo json_encode($json);
	}


    public function actionclearpendingsandlogins(){
    if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

Agents::model()->updateAll(array('device_token' => '', 'status'=> 'offline', 'available_for_new_order'=> 0));
Customers::model()->updateAll(array('online_status' => 'offline'));
//Washingrequests::model()->updateAll(array('status' => 5),'status < 4 AND is_scheduled != 1');

}


    public function actionadminpendingschedwashesalert(){
    if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
$result = 'false';
$response = 'no washes found';
$flaggedwashids = array();
$pendingwashes =  Washingrequests::model()->findAll(array("condition"=>"wash_request_position = '".APP_ENV."' AND status = 0"), array('order' => 'created_date desc'));

if(count($pendingwashes)){
   foreach($pendingwashes as $schedwash){
       if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = 0;
if($from_time >= $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}

if($min_diff > 0 && $min_diff <= 15) $flaggedwashids[] = $schedwash->id;
   }

   if(count($flaggedwashids)){
       $result = 'true';
$response = 'pending delayed washes found';
     $json= array(
			'result'=> $result,
			'response'=> $response,
			'wash_ids' => $flaggedwashids
		);
   }
   else{

       $result = 'false';
$response = 'no washes found';

      	$json= array(
			'result'=> $result,
			'response'=> $response
		);
   }
}
else{
 	$json= array(
			'result'=> $result,
			'response'=> $response
		);

}


		echo json_encode($json);
}


public function actionadminupdatewasherpaystatus(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
$status = Yii::app()->request->getParam('status');
$admin_username = '';
$admin_username  = Yii::app()->request->getParam('admin_username');

      $response = "Pass the required parameters";
      $result = "false";

      if((isset($status) && !empty($status)) && (isset($wash_request_id) && !empty($wash_request_id))){

           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

           else{

                        //print_r($result);die;
                        $response = "status updated";
                        $result = "true";

                        if($status == 'ZERO') $status = 0;

                        Washingrequests::model()->updateByPk($wash_request_id, array('washer_payment_status' => $status));

                         if($status == 2){
                           $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'adminstopwasherpayment',
                        'action_date'=> date('Y-m-d H:i:s'));
                         }

                          if(!$status){
                           $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'adminenablewasherpayment',
                        'action_date'=> date('Y-m-d H:i:s'));
                         }

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

           }

      }

        $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

 }

 public function actionsendapplinksms(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


        $num = Yii::app()->request->getParam('phone');
 $device_type = Yii::app()->request->getParam('device_type');

           $result  = 'false';
$response = 'please enter phone number';

            $json    = array();

if((isset($num) && !empty($num))){

  $app_settings =  Yii::app()->db->createCommand("SELECT * FROM `app_settings`")->queryAll();

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


            if($device_type == 'WEB') $message = "iPhone App\r\n".$app_settings[0]['app_link']."\r\n\r\nAndroid App\r\n".$app_settings[1]['app_link'];

if($device_type == 'IOS') $message = "iPhone App\r\n".$app_settings[0]['app_link'];
if($device_type == 'ANDROID') $message = "Android App\r\n".$app_settings[1]['app_link'];

            $sendmessage = $client->account->messages->create(array(
                'To' =>  $num,
                'From' => '+13103128070',
                'Body' => $message,
            ));


            spl_autoload_register(array('YiiBase','autoload'));

$result = 'true';
$response = 'sms sent';

}


$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


    }



    public function actionupdatewashadmin(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
         $reschedule_date = Yii::app()->request->getParam('reschedule_date');
         $reschedule_time = Yii::app()->request->getParam('reschedule_time');
         $agent_id = Yii::app()->request->getParam('agent_id');
         $status = Yii::app()->request->getParam('status');
         $notes = Yii::app()->request->getParam('notes');
         $admin_command = Yii::app()->request->getParam('admin_command');
           $result  = 'false';
$response = 'Enter wash request id';
$order_for_date = '';
$car_ids = Yii::app()->request->getParam('car_ids');
$car_ids_new = $car_ids;
$car_ids_org = Yii::app()->request->getParam('car_ids_org');
$car_packs = Yii::app()->request->getParam('car_packs');
$pet_hair_vehicles = Yii::app()->request->getParam('pet_hair_vehicles');
$pet_hair_vehicles_custom = Yii::app()->request->getParam('pet_hair_vehicles_custom');
$lifted_vehicles = Yii::app()->request->getParam('lifted_vehicles');
$exthandwax_vehicles = Yii::app()->request->getParam('exthandwax_vehicles');
$extplasticdressing_vehicles = Yii::app()->request->getParam('extplasticdressing_vehicles');
$extclaybar_vehicles = Yii::app()->request->getParam('extclaybar_vehicles');
$waterspotremove_vehicles  = Yii::app()->request->getParam('waterspotremove_vehicles');
$upholstery_vehicles  = Yii::app()->request->getParam('upholstery_vehicles');
$floormat_vehicles  = Yii::app()->request->getParam('floormat_vehicles');
$fifthwash_vehicles  = Yii::app()->request->getParam('fifthwash_vehicles');
$tip_amount = 0;
$tip_amount  = Yii::app()->request->getParam('tip_amount');
$admin_username = '';
$admin_username  = Yii::app()->request->getParam('admin_username');
$full_address  = Yii::app()->request->getParam('full_address');
$city  = Yii::app()->request->getParam('city');
$state  = Yii::app()->request->getParam('state');
$zipcode  = Yii::app()->request->getParam('zipcode');
$lat  = Yii::app()->request->getParam('lat');
$lng  = Yii::app()->request->getParam('lng');
$address_type = Yii::app()->request->getParam('address_type');
$promo_code = '';
$promo_code  = Yii::app()->request->getParam('promo_code');
$coupon_amount = 0;
            $json    = array();

if((isset($wash_request_id) && !empty($wash_request_id))){

    $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));
    if($promo_code) {
      $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code"=>$promo_code));
$coupon_usage = CustomerDiscounts::model()->findByAttributes(array("promo_code"=>$promo_code, "customer_id" => $wrequest_id_check->customer_id), array('condition'=>"wash_request_id != ".$wash_request_id));

    }



			if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }
               	else if(($promo_code) && (!count($coupon_check))){
                   	$result= 'false';
		$response= "Promo code doesn't exist";
                }

 else if(($promo_code) && ($coupon_check->coupon_status != 'enabled')){
                   	$result= 'false';
		           $response= "Sorry, this promo is not available this time.";
                }

  else if(($wrequest_id_check->coupon_code != $promo_code) && ($promo_code) && (strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d"))))){
                   	$result= 'false';
		            $response= "Promo code expired";
                }

 else if(($promo_code) && (($coupon_check->usage_limit == 'single') && (count($coupon_usage) >= 1))){
                   	$result= 'false';
		        $response= "Sorry, you already used this promo once.";
                }
            else{
                $result = 'true';
$response = 'wash request updated';

 if(!$reschedule_date){
                    $reschedule_date = $wrequest_id_check->reschedule_date;
                }

				if(!$reschedule_time){
                    $reschedule_time = $wrequest_id_check->reschedule_time;
                }

                 if(!is_numeric($status)){
                   $status = $wrequest_id_check->status;
                }

				if(!is_numeric($agent_id)){
                   $agent_id = $wrequest_id_check->agent_id;
                }


				if(!$notes){
                   $notes = $wrequest_id_check->notes;
                }

                	if(!$full_address){
                   $full_address = $wrequest_id_check->address;
                }
		
			if(!$city){
                   $city = $wrequest_id_check->city;
                }
		
			if(!$state){
                   $state = $wrequest_id_check->state;
                }
		
			if(!$zipcode){
                   $zipcode = $wrequest_id_check->zipcode;
                }

	if(!$address_type){
                   $address_type = $wrequest_id_check->address_type;
                }

                if(!$lat){
                   $lat = $wrequest_id_check->latitude;
                }

                if(!$lng){
                   $lng = $wrequest_id_check->longitude;
                }

                if($reschedule_time){
                    $reschedule_time = date('h:i A', strtotime($reschedule_time));
                }

                if(!$order_for_date){
                   $order_for_date = $wrequest_id_check->order_for;
                }

                	if($admin_command == 'save-reschedule'){
				$order_for_date = date("Y-m-d H:i:s", strtotime($reschedule_date." ".$reschedule_time));

				 $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'reschedule',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
}


if($admin_command == 'save-note'){
$date = date('Y-m-d H:i:s');
 $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'savenote',
                        'action_date'=> $date);

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    $notes = $notes." (".$admin_username." added at ".$date.")";
                    Washingrequests::model()->updateByPk($wash_request_id, array("notes" => $notes));
}

	if($admin_command == 'save-status'){
	    $actionname = '';
        if($status == 1) $actionname = 'startjob';
        if($status == 2) $actionname = 'arrivejob';
        if($status == 3) $actionname = 'processjob';
        if($status == 4) $actionname = 'completejob';
       $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> $actionname,
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
    }

                Washingrequests::model()->updateByPk($wash_request_id, array("reschedule_date" => $reschedule_date, "reschedule_time" => $reschedule_time, "status" => $status, "agent_id" => $agent_id, "notes" => $notes, 'order_for' => $order_for_date));


if(($admin_command == 'save-reschedule') && ($wrequest_id_check->is_scheduled == 1) && ($wrequest_id_check->agent_id) && ($reschedule_time) && ((strtotime($reschedule_date) != strtotime($wrequest_id_check->schedule_date)) || (strtotime($reschedule_time) != strtotime($wrequest_id_check->schedule_time)))){

$agent_detail = Agents::model()->findByPk($wrequest_id_check->agent_id);
  $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$wrequest_id_check->agent_id."' ORDER BY last_used DESC LIMIT 1")->queryAll();
						/* --- notification call --- */

						$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
						$message = $pushmsg[0]['message'];
$message = str_replace("[ORDER_ID]","#".$wash_request_id, $message);
						if(!$agent_detail->block_washer){
						foreach( $agentdevices as $ctdevice){
							//$message =  "You have a new scheduled wash request.";
							//echo $agentdetails['mobile_type'];
							$device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
								$alert_type = "schedule";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
							file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}
}
				/* --- notification call end --- */
				
				Washingrequests::model()->updateByPk($wrequest_id_check->id, array("is_create_schedulewash_push_sent" => 1));
												
}

if($admin_command == 'update-order'){
    
    $old_amount = 0.00;
    if($tip_amount > 0){
        $old_tip_amount = Yii::app()->db->createCommand("SELECT tip_amount FROM washing_requests WHERE id = :id LIMIT 1" )
        ->bindValue(':id', $wash_request_id, PDO::PARAM_STR)
        ->queryAll();
        
        if($old_tip_amount[0]['tip_amount']){
            $old_amount = $old_tip_amount[0]['tip_amount'];
        }
    }
    
    if($promo_code){
       if (strpos($car_packs, 'Premium') !== false) {
        $coupon_amount = number_format($coupon_check->premium_amount, 2, '.', '');
       }elseif(strpos($car_packs, 'Deluxe') !== false){
        $coupon_amount = number_format($coupon_check->deluxe_amount, 2, '.', '');
       }elseif(strpos($car_packs, 'Express') !== false){
        $coupon_amount = number_format($coupon_check->express_amount, 2, '.', '');
       }

       $fifthwash_vehicles = '';
    }

    Washingrequests::model()->updateByPk($wash_request_id, array('car_list' => $car_ids, 'package_list' => $car_packs, 'pet_hair_vehicles' => $pet_hair_vehicles, 'pet_hair_vehicles_custom_amount' => $pet_hair_vehicles_custom, 'lifted_vehicles' => $lifted_vehicles, 'exthandwax_vehicles' => $exthandwax_vehicles, 'extplasticdressing_vehicles' => $extplasticdressing_vehicles, 'extclaybar_vehicles' => $extclaybar_vehicles, 'waterspotremove_vehicles' => $waterspotremove_vehicles, 'upholstery_vehicles' => $upholstery_vehicles, 'floormat_vehicles' => $floormat_vehicles, 'fifth_wash_vehicles' => $fifthwash_vehicles, 'tip_amount' => $tip_amount, 'address' => $full_address, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'address_type' => $address_type, 'latitude' => $lat, 'longitude' => $lng, 'coupon_code' => $promo_code, 'coupon_discount' => $coupon_amount));

     

                    if($wrequest_id_check->address != $full_address){
                        $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'updatelocation',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }

                    if(number_format($tip_amount,2) != number_format($old_amount,2)){
                        $washeractionlogdata = array(
                        'wash_request_id'=> $wash_request_id,
                        'admin_username' => $admin_username,
                        'addi_detail' => '$'.number_format($old_amount,2).' to $'.number_format($tip_amount,2),
                        'action'=> 'tipamount',
                        'action_date'=> date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }else{
                        $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'editorder',
                        'action_date'=> date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
    
                    WashPricingHistory::model()->updateAll(array('status'=>1),'wash_request_id=:wash_request_id', array(":wash_request_id" => $wash_request_id));

                    $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS);
                    $kartdetails = json_decode($kartapiresult);

                    if($wrequest_id_check->net_price != $kartdetails->net_price) {
                        WashPricingHistory::model()->deleteAll("wash_request_id=:wash_request_id", array(":wash_request_id" => $wash_request_id));
                        /* ----------- update pricing details -------------- */

                        $cust_details = Customers::model()->findByAttributes(array('id'=>$wrequest_id_check->customer_id));
                        Washingrequests::model()->updateByPk($wash_request_id, array('total_price' => $kartdetails->total_price, 'net_price' => $kartdetails->net_price, 'company_total' => $kartdetails->company_total, 'agent_total' => $kartdetails->agent_total, 'bundle_discount' => $kartdetails->bundle_discount, 'first_wash_discount' => $kartdetails->first_wash_discount, 'coupon_discount' => $kartdetails->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points));

                        /* ----------- update pricing details end -------------- */
                    }
                    else WashPricingHistory::model()->updateAll(array('status'=>0),'wash_request_id=:wash_request_id', array(":wash_request_id" => $wash_request_id));

                     foreach($kartdetails->vehicles as $car){
                        if($wrequest_id_check->net_price != $kartdetails->net_price){
                            /* --------- car pricing save --------- */

                            $washpricehistorymodel = new WashPricingHistory;
                            $washpricehistorymodel->wash_request_id = $wash_request_id;
                            $washpricehistorymodel->vehicle_id = $car->id;
                            $washpricehistorymodel->package = $car->vehicle_washing_package;
                            $washpricehistorymodel->vehicle_price = $car->vehicle_washing_price;
                            $washpricehistorymodel->pet_hair = $car->pet_hair_fee;
                            $washpricehistorymodel->lifted_vehicle = $car->lifted_vehicle_fee;
                            $washpricehistorymodel->exthandwax_addon = $car->exthandwax_vehicle_fee;
                            $washpricehistorymodel->extplasticdressing_addon = $car->extplasticdressing_vehicle_fee;
                            $washpricehistorymodel->extclaybar_addon = $car->extclaybar_vehicle_fee;
                            $washpricehistorymodel->waterspotremove_addon = $car->waterspotremove_vehicle_fee;
                            $washpricehistorymodel->upholstery_addon = $car->upholstery_vehicle_fee;
                            $washpricehistorymodel->floormat_addon = $car->floormat_vehicle_fee;
                            $washpricehistorymodel->safe_handling = $car->safe_handling_fee;
                            $washpricehistorymodel->bundle_disc = $car->bundle_discount;
                            $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                            $washpricehistorymodel->save(false);

                            /* --------- car pricing save end --------- */
                        }
                    }

}

if($status == WASHREQUEST_STATUS_COMPLETEWASH){
	
$car_ids_org_arr = explode(",",$car_ids_org);
$car_ids_new_arr = explode(",",$car_ids_new);

$washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);

      $washrequestmodel->complete_order = date("Y-m-d H:i:s");
                    $resUpdate = $washrequestmodel->save(false);

                    WashPricingHistory::model()->updateAll(array('status'=>1),'wash_request_id=:wash_request_id', array(":wash_request_id" => $wash_request_id));



					 $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS);
                    $kartdetails = json_decode($kartapiresult);

                    if($wrequest_id_check->net_price != $kartdetails->net_price) WashPricingHistory::model()->deleteAll("wash_request_id=:wash_request_id", array(":wash_request_id" => $wash_request_id));
                    else WashPricingHistory::model()->updateAll(array('status'=>0),'wash_request_id=:wash_request_id', array(":wash_request_id" => $wash_request_id));

                    /* ----------- update pricing details -------------- */

      			    $cust_details = Customers::model()->findByAttributes(array('id'=>$wrequest_id_check->customer_id));
                     if(count($car_ids_org_arr) != count($car_ids_new_arr)) Washingrequests::model()->updateByPk($wash_request_id, array('total_price' => $kartdetails->total_price, 'net_price' => $kartdetails->net_price, 'company_total' => $kartdetails->company_total, 'agent_total' => $kartdetails->agent_total, 'bundle_discount' => $kartdetails->bundle_discount, 'first_wash_discount' => $kartdetails->first_wash_discount, 'coupon_discount' => $kartdetails->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points, 'fifth_wash_discount' => 0, 'fifth_wash_vehicles' => '', 'per_car_wash_points' => '', "washer_late_cancel" => 0, "no_washer_cancel" => 0));
		else Washingrequests::model()->updateByPk($wash_request_id, array('total_price' => $kartdetails->total_price, 'net_price' => $kartdetails->net_price, 'company_total' => $kartdetails->company_total, 'agent_total' => $kartdetails->agent_total, 'bundle_discount' => $kartdetails->bundle_discount, 'first_wash_discount' => $kartdetails->first_wash_discount, 'coupon_discount' => $kartdetails->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points, "washer_late_cancel" => 0, "no_washer_cancel" => 0));

		    
					/* ----------- update pricing details end -------------- */

					$all_washes = Yii::app()->db->createCommand()->select('*')->from('washing_requests')->where("customer_id = ".$wrequest_id_check->customer_id." AND status = 4 AND id != :wash_request_id", array(":wash_request_id" => $wash_request_id))->queryAll();

if(count($car_ids_org_arr) != count($car_ids_new_arr)){
if(count($all_washes)){
    $totalpoints = 0;
    $current_fifth_points = 0;
    foreach($all_washes as $wash){
       $car_arr = explode(",", $wash['car_list']);
       $totalpoints += count($car_arr);
    }

    $current_fifth_points = $totalpoints % 5;
    if($current_fifth_points == 0) $current_fifth_points = 5;
  Customers::model()->updateByPk($wrequest_id_check->customer_id, array("fifth_wash_points" => $current_fifth_points));
}
}

                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",",$car_ids);


 Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 1));




                    foreach($kartdetails->vehicles as $car){
 $cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);
 $wash_detail = Washingrequests::model()->findByPk($wash_request_id);

   if($wrequest_id_check->net_price != $kartdetails->net_price){
                     /* --------- car pricing save --------- */

                     $washpricehistorymodel = new WashPricingHistory;
                        $washpricehistorymodel->wash_request_id = $wash_request_id;
                        $washpricehistorymodel->vehicle_id = $car->id;
                        $washpricehistorymodel->package = $car->vehicle_washing_package;
                        $washpricehistorymodel->vehicle_price = $car->vehicle_washing_price;
                        $washpricehistorymodel->pet_hair = $car->pet_hair_fee;
                        $washpricehistorymodel->lifted_vehicle = $car->lifted_vehicle_fee;
                        $washpricehistorymodel->exthandwax_addon = $car->exthandwax_vehicle_fee;
                        $washpricehistorymodel->extplasticdressing_addon = $car->extplasticdressing_vehicle_fee;
                        $washpricehistorymodel->extclaybar_addon = $car->extclaybar_vehicle_fee;
                        $washpricehistorymodel->waterspotremove_addon = $car->waterspotremove_vehicle_fee;
                        $washpricehistorymodel->upholstery_addon = $car->upholstery_vehicle_fee;
                        $washpricehistorymodel->floormat_addon = $car->floormat_vehicle_fee;
                        $washpricehistorymodel->safe_handling = $car->safe_handling_fee;
                        $washpricehistorymodel->bundle_disc = $car->bundle_discount;
                        $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                        $washpricehistorymodel->save(false);

                      /* --------- car pricing save end --------- */
                      }

                    /* --------- Inspection details save --------- */

                     $cardetail = Vehicle::model()->findByPk($car->id);

                    $washinginspectmodel = new Washinginspections;
                    $washinginspectmodel->wash_request_id = $wash_request_id;
                    $washinginspectmodel->vehicle_id = $car->id;
                    $washinginspectmodel->damage_pic = $cardetail->damage_pic;
                    $washinginspectmodel->save(false);

                   /* --------- Inspection details save end --------- */

                        $carresetdata= array('status' => 0, 'eco_friendly' => 0, 'damage_points'=> '','damage_pic'=>'', 'upgrade_pack'=> 0, 'edit_vehicle'=> 0, 'remove_vehicle_from_kart'=> 0, 'new_vehicle_confirm'=> 0, 'new_pack_name'=> '', 'pet_hair' => 0, 'lifted_vehicle' => 0, 'exthandwax_addon' => 0, 'extplasticdressing_addon' => 0, 'extclaybar_addon' => 0, 'waterspotremove_addon' => 0, 'upholstery_addon' => 0, 'floormat_addon' => 0);
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id'=>$car->id));

                if(count($car_ids_org_arr) != count($car_ids_new_arr)){
		
		/* ------ 5th wash check ------- */
		
                    $current_points = $cust_detail->fifth_wash_points;
if($current_points == 5){
$new_points = 1;
}
else{
                    $new_points = $current_points + 1;
}


//$custmodel = new Customers;
//$custmodel->updateAll(array('fifth_wash_points'=> $new_points, 'id=:id', array(':id'=>$wash_request_exists->customer_id)));

Customers::model()->updateByPk($wash_detail->customer_id, array('fifth_wash_points' => $new_points, 'is_first_wash' => 1));


                    if($new_points == 5){

$fifth_vehicles_old = '';
$fifth_vehicles_old = $wash_detail->fifth_wash_vehicles;
$fifth_vehicles_arr = explode(",", $fifth_vehicles_old);
if (!in_array($car->id, $fifth_vehicles_arr)) array_push($fifth_vehicles_arr, $car->id);
$fifth_vehicles_new = implode(",", $fifth_vehicles_arr);
$fifth_vehicles_new = trim($fifth_vehicles_new,",");

Washingrequests::model()->updateByPk($wash_request_id, array('fifth_wash_discount' => 5, 'fifth_wash_vehicles' => $fifth_vehicles_new));

                    }

/* ------ 5th wash check end ------- */

/* ---- per car wash points ------ */

$per_car_points_old = '';
$per_car_points_old = $wash_detail->per_car_wash_points;
$per_car_points_arr = explode(",", $per_car_points_old);
array_push($per_car_points_arr, $new_points);
$per_car_points_new = implode(",", $per_car_points_arr);
$per_car_points_new = trim($per_car_points_new,",");

Washingrequests::model()->updateByPk($wash_request_id, array('customer_wash_points' => $new_points, 'per_car_wash_points' => $per_car_points_new));

/* ---- per car wash points end ------ */

               $curr_wash_points++;
               if($curr_wash_points >= 5) $curr_wash_points = 0;
		    }
                    }

                   $cust_id = $wrequest_id_check->customer_id;
                    $completed_washes_agent = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$wrequest_id_check->agent_id, 'status'=> WASHREQUEST_STATUS_COMPLETEWASH));
                    $completed_washes_cust = Washingrequests::model()->findAllByAttributes(array('customer_id'=>$cust_id, 'status'=> WASHREQUEST_STATUS_COMPLETEWASH));
                    $total_washes_agent = count($completed_washes_agent);
                    $total_washes_cust = count($completed_washes_cust);
                    $total_wash_data_agent= array('total_wash' => $total_washes_agent);
                    $total_wash_data_cust= array('total_wash' => $total_washes_cust);
                    $Customers = new Customers;
                    $Agents = new Agents;
                    $Customers->updateAll($total_wash_data_cust, 'id=:id', array(':id'=>$cust_id));
                    $Agents->updateAll($total_wash_data_agent, 'id=:id', array(':id'=>$wrequest_id_check->agent_id));

                }


            }



}


$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


    }


    public function actiongetwashersavedroplog() {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$response = "no logs found";
		$result = "false";
		$allwashes = array();
$wash_request_id = Yii::app()->request->getParam('wash_request_id');

$alllogs =  Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE wash_request_id=:wash_request_id ORDER BY id ASC")
->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
->queryAll();
$alllogs_new = [];

			if(count($alllogs)){
			    foreach($alllogs as $ind=> $log){
			      $alllogs_new[$ind] = $log;
			      $alllogs_new[$ind]['formatted_action_date'] = date('F j, Y - h:i A', strtotime($log['action_date']));
			    }
				$response = "logs found";
				$result = "true";

			}



       $json = array(
			'result'=> $result,
			'response'=> $response,
			'logs' => $alllogs_new
		);

		echo json_encode($json);
		die();

}


public function actionupdatesurgeprice(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
 $express_price = Yii::app()->request->getParam('express_price');
        $deluxe_price = Yii::app()->request->getParam('deluxe_price');
		$premium_price = Yii::app()->request->getParam('premium_price');



		if((isset($id) && !empty($id)))

			 {

$item_check = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($item_check)){
                   	$result= 'false';
		$response= "Invalid id";
                }
else{

 if(!is_numeric($express_price)){
$express_price = $item_check[0]['express'];
}

 if(!is_numeric($deluxe_price)){
$deluxe_price = $item_check[0]['deluxe'];
}

if(!is_numeric($premium_price)){
$premium_price = $item_check[0]['premium'];
}


                   $data= array(
					'express'=> $express_price,
					'deluxe'=> $deluxe_price,
					'premium'=> $premium_price
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('surge_pricing', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'update successful';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}


	public function actiongetsurgeprices(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'nothing found';


$all_prices = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->queryAll();

 if(count($all_prices)){
     	$result= 'true';
		$response= 'surge prices';
 }



		$json= array(
			'result'=> $result,
			'response'=> $response,
			'surge_prices' => $all_prices
		);
		echo json_encode($json);
	}
	
	
	public function actionupdatezipprice(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
  $price_unit = Yii::app()->request->getParam('price_unit');
 $blue_price = Yii::app()->request->getParam('blue_price');
        $yellow_price = Yii::app()->request->getParam('yellow_price');
		$red_price = Yii::app()->request->getParam('red_price');



		if((isset($id) && !empty($id)))

			 {

$item_check = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where('id=:id', array(":id" => $id))->queryAll();

             	if(!count($item_check)){
                   	$result= 'false';
		$response= "Invalid id";
                }
else{

 if(!is_numeric($blue_price)){
$blue_price = $item_check[0]['blue'];
}

 if(!is_numeric($yellow_price)){
$yellow_price = $item_check[0]['yellow'];
}

if(!is_numeric($red_price)){
$red_price = $item_check[0]['red'];
}

 if(!$price_unit){
$price_unit = $item_check[0]['price_unit'];
}


                   $data= array(
					'price_unit'=> $price_unit,
					'blue'=> $blue_price,
					'yellow'=> $yellow_price,
					'red'=> $red_price
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('zipcode_pricing', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'update successful';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}
	
	
		public function actionupdatezippricenew(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
  $zip = Yii::app()->request->getParam('zip');
  $action = Yii::app()->request->getParam('action');
 $express_price = Yii::app()->request->getParam('express_price');
        $deluxe_price = Yii::app()->request->getParam('deluxe_price');
		$premium_price = Yii::app()->request->getParam('premium_price');



		if((isset($id) && !empty($id)))

			 {

$item_check = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where('id=:id', array(":id" =>$id))->queryAll();

             	if(!count($item_check)){
                   	$result= 'false';
		$response= "Invalid id";
                }
else{

if($action == 'disablesurge'){
	$all_zips = explode(",", $item_check[0]['zipcodes']);
$all_zips = array_filter($all_zips, function($v) use ($zip) {
    if ($v != $zip) {
        return true;
   }
});

$new_zips = implode(",", $all_zips);

}
else{
if($item_check[0]['zipcodes']) $new_zips = $item_check[0]['zipcodes'].",".$zip;
else $new_zips = $zip;
$new_zips = trim($new_zips);	
}

                   $data= array(
					'zipcodes'=> $new_zips
					
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('zipcode_pricing', $data,"id=:id", array(":id" => $id));

                    	$result= 'true';
		$response= 'update successful';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}
	
	
	public function actiongetzipprices(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'nothing found';


$all_prices = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->queryAll();

 if(count($all_prices)){
     	$result= 'true';
		$response= 'zipcode prices';
 }

		$json= array(
			'result'=> $result,
			'response'=> $response,
			'zipcode_prices' => $all_prices
		);
		echo json_encode($json);
	}


	public function actionupdateorderfordates(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'nothing found';


$all_washes = Yii::app()->db->createCommand()->select('*')->from('washing_requests')->order('id DESC')->limit(20)->offset(0)->queryAll();

 if(count($all_washes)){
     	$result= 'true';
		$response= 'all washes';
		foreach($all_washes as $wash){
		    echo $wash['id']." ".$wash['created_date']." ".strtolower(date('D', strtotime($wash['created_date'])))."<br>";
		    if(!$wash['is_scheduled']){
		       // Washingrequests::model()->updateByPk($wash['id'], array('order_for' => $wash['created_date']));
		    }

		    if($wash['is_scheduled']){
		        //if($wash['reschedule_time']) Washingrequests::model()->updateByPk($wash['id'], array('order_for' => date("Y-m-d H:i:s", strtotime($wash['reschedule_date']." ".$wash['reschedule_time']))));
		        //else Washingrequests::model()->updateByPk($wash['id'], array('order_for' => date("Y-m-d H:i:s", strtotime($wash['schedule_date']." ".$wash['schedule_time']))));
		    }
		}
 }



		$json= array(
			'result'=> $result,
			'response'=> $response,

		);
		echo json_encode($json);
	}


	 private static function sortById($a, $b) {
    $a = abs($a['min_diff']);
	$b = abs($b['min_diff']);

	if ($a == $b)
	{
		return 0;
	}

	return ($a<$b) ? -1 : (($a > $b) ? 1 : 0);
}


  public function actiongetallwashrequestsnew(){

        if(Yii::app()->request->getParam('key') != API_KEY){
            echo "Invalid api key";
            die();
        }
		/* Checking for post(day) parameters */
		$order_day='';
		if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
			$day = Yii::app()->request->getParam('day');
			$event = Yii::app()->request->getParam('event');
			
			$status_qr = '';
			if($event == 'pending'){
				$status = 0;
				$status_qr = ' AND w.status="'.$status.'"';
			} elseif($event == 'total_orders'){
                $status_qr = " AND w.status IN('0','4','3','2','1')";
            } elseif($event == 'completed'){
				$status = 4;
				$status_qr = ' AND w.status="'.$status.'"';
			} elseif($event == 'processing'){
				$status = 3;
				$status_qr = ' AND (w.status >=1 && w.status <=3)';
                //$status_qr = ' AND (w.status = 3)';
			} elseif($event == 'canceled'){
				$status_qr = ' AND (w.status=5 || w.status=6)';
			} elseif($event == 'declined'){
                $status_qr = " AND (w.failed_transaction_id != '')";
            } elseif($event == 'express' || $event == 'deluxe' || $event == 'premium'){
                $status_qr=" AND (FIND_IN_SET('".$event."', w.package_list)>0 AND w.status IN('0','4'))";
            } elseif($event == 'coupon_code'){
                $status_qr = " AND w.coupon_code <> ''";
            } elseif($event == 'tip_amount'){
				$status_qr=" AND (w.tip_amount <> '' && w.tip_amount <> '0.00' && w.tip_amount <> '0')";
			}
			elseif($event == 'addoncompleted'){
				$status_qr=" AND (w.pet_hair_vehicles != '' OR  w.lifted_vehicles != '' OR  w.exthandwax_vehicles != '' OR  w.extplasticdressing_vehicles != '' OR  w.extclaybar_vehicles != '' OR  w.waterspotremove_vehicles != '' OR  w.upholstery_vehicles != '' OR  w.floormat_vehicles != '') AND w.status = 4";
			}
			elseif($event == 'ondemandcompleted'){
				$status_qr=" AND w.is_scheduled = 0 AND w.status = 4";
			}
			elseif($event == 'schedulecompleted'){
				$status_qr=" AND w.is_scheduled = 1 AND w.status = 4";
			}
			elseif($event == 'schedulecanceled'){
				$status_qr=" AND w.is_scheduled = 1 AND (w.status=5 || w.status=6)";
			}
			elseif($event == 'ondemandcanceled'){
				$status_qr=" AND w.is_scheduled = 0 AND (w.status=5 || w.status=6)";
			}
			elseif($event == 'newcustomer'){
				$status_qr=" AND c.total_wash = 0";
			}elseif(in_array($event, array('yelloworders', 'blueorders', 'redorders', 'purpleorders'))){
                $status_qr = " AND w.status IN('0','4','3','2','1')";
            }
			else {
				$status_qr = '';
			}

			$order_day = " AND DATE_FORMAT(w.order_for,'%Y-%m-%d')= '".$day."'".$status_qr;
		}
		/* END */



        $json = array();

        $result= 'true';
        $response= 'all wash requests';
        $pendingwashrequests = array();
        $pendingwashrequests_flashing = array();
        $pendingwashrequests_upcoming = array();
        $pendingwashrequests_nonupcoming = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $filter = '';
        $limit = 0;
        $filter = Yii::app()->request->getParam('filter');
        $limit = Yii::app()->request->getParam('limit');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
$pendingorderscount = 0;
$cust_query = '';
$agent_query = '';
 $avg_order_frequency = 0;
 $total_days_diff = 0;
 $completed_orders = 0;

if($customer_id > 0) $cust_query = "w.customer_id=".$customer_id." AND ";
if($agent_id > 0) $agent_query = "w.agent_id=".$agent_id." AND ";

if($customer_id > 0){
    $cust_check = Customers::model()->findByPk($customer_id);
}

		//if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
//else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();

  if($filter == 'testorders'){

    if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 0 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC LIMIT ".$limit)->queryAll();
else $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 0 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC")->queryAll();
  }
  else{
    if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 1 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC LIMIT ".$limit)->queryAll();
else $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 1 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC")->queryAll();
  }

   //print_r($qrRequests);

        if(count($qrRequests)>0){

            foreach($qrRequests as $ind=> $wrequest)
            {

	if($event == 'blueorders'){
		$coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode'=>$wrequest['zipcode']));
	
		if(count($coveragezipcheck)){
			$zipcolor = $coveragezipcheck->zip_color;
			if(($zipcolor != '') && ($zipcolor != 'blue')) continue;
		}
		
	}
	
	if($event == 'yelloworders'){
		$coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode'=>$wrequest['zipcode']));
	
		if(count($coveragezipcheck)){
			$zipcolor = $coveragezipcheck->zip_color;
			if(($zipcolor != 'yellow')) continue;
		}
		else{
			continue;
		}
	}
	
	if($event == 'redorders'){
		$coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode'=>$wrequest['zipcode']));
	
		if(count($coveragezipcheck)){
			$zipcolor = $coveragezipcheck->zip_color;
			if(($zipcolor != 'red')) continue;
		}
		else{
			continue;
		}
	}
                
    if($event == 'purpleorders'){
        $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode'=>$wrequest['zipcode']));
    
        if(count($coveragezipcheck)){
            $zipcolor = $coveragezipcheck->zip_color;
            if(($zipcolor != 'purple')) continue;
        }
        else{
            continue;
        }
    }
                
    if($event == 'scheduleauto'){
        $check_auto_canceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'scheduleauto-canceled' AND wash_request_id = :order_id")->bindValue(':order_id', $wrequest['id'], PDO::PARAM_STR)->queryAll();
        //print_r($check_auto_canceled);
        if(count($check_auto_canceled) == 0){
             continue;
        }
        
    }
                
    if($event == 'ondemandauto'){
        $ondemandautocanceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'ondemandautocancel' AND wash_request_id = :order_id")->bindValue(':order_id', $wrequest['id'], PDO::PARAM_STR)->queryAll();
        //print_r($check_auto_canceled);
        if(count($ondemandautocanceled) == 0){
             continue;
        }
        
    }

if($wrequest['is_scheduled']){
                 if($wrequest['reschedule_time']) $scheduledatetime = $wrequest['reschedule_date']." ".$wrequest['reschedule_time'];
else $scheduledatetime = $wrequest['schedule_date']." ".$wrequest['schedule_time'];

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);

//$min_diff = abs($min_diff);
}
else{
  if($wrequest['status'] >= 0 && $wrequest['status'] < 4) $min_diff = 0;
  else{

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($wrequest['order_for']);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);
  }
}

if($wrequest['status'] == 0) $pendingorderscount++;

if(($customer_id > 0) && ($wrequest['status'] == 4)){
//echo $wrequest['id']." ".$wrequest['order_for']." "."<br>";
    //echo $qrRequests[$ind+1]['id']." ".$qrRequests[$ind+1]['order_for']." "."<br>";
    if(isset($qrRequests[$ind+1])){
        $order1_date = date("Y-m-d", strtotime($wrequest['order_for']));
      $order2_date = date("Y-m-d", strtotime($qrRequests[$ind+1]['order_for']));
      //echo $order1_date." ".$order2_date."<br>";
  $day_diff = date_diff(new DateTime($order1_date), new DateTime($order2_date));
     //echo $day_diff->format("%a")."<br>";
     //echo "working<br>";
$total_days_diff += $day_diff->format("%a");
}

 $completed_orders++;
}

                $cust_details = Customers::model()->findByAttributes(array("id"=>$wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id"=>$wrequest['agent_id']));
                $cars =  explode(",",$wrequest['car_list']);
				$packs =  explode(",",$wrequest['package_list']);
				$vehicles = array();
				foreach($cars as $ind=>$car){
                    $car_details = Vehicle::model()->findByAttributes(array("id"=>$car));
		    
		    $veh_addons = '';
		    
		    $pet_hair_vehicles_arr = explode(",", $wrequest['pet_hair_vehicles']);
if (in_array($car, $pet_hair_vehicles_arr)) $veh_addons .= 'Extra Cleaning, ';

$lifted_vehicles_arr = explode(",", $wrequest['lifted_vehicles']);
if (in_array($car, $lifted_vehicles_arr)) $veh_addons .= 'Lifted Truck, ';

$exthandwax_addon_arr = explode(",", $wrequest['exthandwax_vehicles']);
if (in_array($car, $exthandwax_addon_arr)) $veh_addons .= 'Liquid Hand Wax, ';

$extplasticdressing_addon_arr = explode(",", $wrequest['extplasticdressing_vehicles']);
if (in_array($car, $extplasticdressing_addon_arr)) $veh_addons .= 'Exterior Plastic Dressing, ';

$extclaybar_addon_arr = explode(",", $wrequest['extclaybar_vehicles']);
if (in_array($car, $extclaybar_addon_arr)) $veh_addons .= 'Clay Bar & Paste Wax, ';

$waterspotremove_addon_arr = explode(",", $wrequest['waterspotremove_vehicles']);
if (in_array($car, $waterspotremove_addon_arr)) $veh_addons .= 'Water Spot Removal, ';

$upholstery_addon_arr = explode(",", $wrequest['upholstery_vehicles']);
if (in_array($car, $upholstery_addon_arr)) $veh_addons .= 'Upholstery Conditioning, ';

$floormat_addon_arr = explode(",", $wrequest['floormat_vehicles']);
if (in_array($car, $floormat_addon_arr)) $veh_addons .= 'Floor Mat Cleaning, ';

$veh_addons = rtrim($veh_addons, ", ");

                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind], 'addons' => $veh_addons);
				}

				
				if(($cust_details->first_name != '') && ($cust_details->last_name != '')){
						$customername = '';
						$cust_name = explode(" ", trim($cust_details->last_name));
						$customername = $cust_details->first_name." ".strtoupper(substr($cust_name[0], 0, 1)).".";
						
					}
					else{
						$customername = '';
				$cust_name = explode(" ", trim($cust_details->customername));
				if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
				else $customername = $cust_name[0];
					}
					
					$customername = strtolower($customername);
$customername = ucwords($customername);

								$agent_info = array();
				if(count($agent_details)){
					   $agent_info = array('agent_id'=>$wrequest['agent_id'], 'real_washer_id'=>$agent_details->real_washer_id, 'agent_name'=>$agent_details->first_name." ".$agent_details->last_name, 'agent_phoneno'=>$agent_details->phone_number, 'agent_email'=>$agent_details->email);
				}
$payment_status = '';
$submerchant_id = '';
$transaction_status = '';
$washer_payment_status = '';
$washer_change_pack = 0;
$washercustnomeet = 0;
$washer_wash_activity = 1;
$washer_30_min_noarrive = 0;

if(($wrequest['status'] == 4) && (!$wrequest['washer_payment_status'])){
$end = time();
$start = strtotime($wrequest['order_for']);

$days_between = ceil(abs($end - $start) / 86400);
//echo $wrequest['id']." ".$days_between."<br>"; 
if($days_between > 1 ) $washer_payment_status = 'pending';
}
if($wrequest['failed_transaction_id']){
  $payment_status = 'Declined';
}
else{
if($wrequest['transaction_id']){

if($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held'){
$payment_status = 'Processed';
}

else if($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released'){
$payment_status = 'Released';
}


/*if($cust_details->client_position == 'real') $payresult = Yii::app()->braintree->getTransactionById_real($wrequest['transaction_id']);
else $payresult = Yii::app()->braintree->getTransactionById($wrequest['transaction_id']);
if($payresult['success'] == 1) {
//$submerchant_id = $payresult['merchant_id'];
$transaction_status = $payresult['status'];
}*/


 }
}

if(($wrequest['status'] >=1) && ($wrequest['status'] <= 3)){
	$total_rows =  Yii::app()->db->createCommand("SELECT COUNT(*) as total FROM activity_logs WHERE action = 'customeracceptupgrade' AND admin_username = '' AND wash_request_id = ".$wrequest['id'])->queryAll();
	$washer_change_pack = $total_rows[0]['total'];
}

if(($wrequest['status'] == 1) && (!$wrequest['is_scheduled'])){
	$min_diff2 = 0;
	$current_time = strtotime(date('Y-m-d H:i:s'));

	if($current_time > strtotime($wrequest['wash_begin'])){
		$min_diff2 = round(($current_time - strtotime($wrequest['wash_begin'])) / 60,2);
	}
	
	if($min_diff2 >= 30) $washer_30_min_noarrive = 1;
}

if(($wrequest['status'] == 2) && ($wrequest['meet_washer_outside'] != 'yes')){
	$min_diff2 = 0;
	$current_time = strtotime(date('Y-m-d H:i:s'));

	if($current_time > strtotime($wrequest['washer_arrived_at'])){
		$min_diff2 = round(($current_time - strtotime($wrequest['washer_arrived_at'])) / 60,2);
	}
	
	if($min_diff2 >= 10) $washercustnomeet = 1;
}

if(($wrequest['status'] == 3) && (!$wrequest['washer_wash_activity'])){
	$min_diff2 = 0;
	$current_time = strtotime(date('Y-m-d H:i:s'));

	if($current_time > strtotime($wrequest['meet_or_nomeet_washer_outside_at'])){
		$min_diff2 = round(($current_time - strtotime($wrequest['meet_or_nomeet_washer_outside_at'])) / 60,2);
	}
	
	if($min_diff2 >= 10) $washer_wash_activity = 0;
}


if($wrequest['is_flagged'] == 1) $payment_status = 'Check Fraud';

 if(($min_diff < 0) && ($wrequest['status'] == 0)){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_flashing[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
		    'city'=>$wrequest['city'],
			'state'=>$wrequest['state'],
			'zipcode'=>$wrequest['zipcode'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>date('Y-m-d',strtotime($wrequest['schedule_date'])),
                    'schedule_time'=>date('h:i A', strtotime($wrequest['schedule_time'])),
					'reschedule_date'=>$resched_date,
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$resched_time,
					'created_date'=>date('Y-m-d',strtotime($wrequest['created_date']))." ".date('h:i A', strtotime($wrequest['created_date'])),
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
					'submerchant_id' => $submerchant_id,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$wrequest['net_price'],
'payment_status' => $payment_status,
'min_diff' => $min_diff,
'washer_pay_status' => $washer_payment_status,
'admin_submit_for_settle'=>$wrequest['admin_submit_for_settle'],
'washer_change_pack' => $washer_change_pack,
'washercustnomeet' => $washercustnomeet,
'washer_wash_activity' => $washer_wash_activity,
'washer_30_min_noarrive' => $washer_30_min_noarrive
                );

}

if($min_diff >= 0){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_upcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
		    'city'=>$wrequest['city'],
			'state'=>$wrequest['state'],
			'zipcode'=>$wrequest['zipcode'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>date('Y-m-d',strtotime($wrequest['schedule_date'])),
                    'schedule_time'=>date('h:i A', strtotime($wrequest['schedule_time'])),
					'reschedule_date'=>$resched_date,
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$resched_time,
					'created_date'=>date('Y-m-d',strtotime($wrequest['created_date']))." ".date('h:i A', strtotime($wrequest['created_date'])),
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
					'submerchant_id' => $submerchant_id,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$wrequest['net_price'],
'payment_status' => $payment_status,
'min_diff' => $min_diff,
'washer_pay_status' => $washer_payment_status,
'admin_submit_for_settle'=>$wrequest['admin_submit_for_settle'],
'washer_change_pack' => $washer_change_pack,
'washercustnomeet' => $washercustnomeet,
'washer_wash_activity' => $washer_wash_activity,
'washer_30_min_noarrive' => $washer_30_min_noarrive
                );

}
if(($min_diff < 0) && ($wrequest['status'] > 0)){
     $pendingwashrequests_nonupcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
		    'city'=>$wrequest['city'],
			'state'=>$wrequest['state'],
			'zipcode'=>$wrequest['zipcode'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'submerchant_id' => $submerchant_id,
					'transaction_status'=>$transaction_status,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$wrequest['net_price'],
'payment_status' => $payment_status,
'min_diff' => $min_diff,
'washer_pay_status' => $washer_payment_status,
'admin_submit_for_settle'=>$wrequest['admin_submit_for_settle'],
'washer_change_pack' => $washer_change_pack,
'washercustnomeet' => $washercustnomeet,
'washer_wash_activity' => $washer_wash_activity,
'washer_30_min_noarrive' => $washer_30_min_noarrive
                );

}

				$pendingwashrequests[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
		    'city'=>$wrequest['city'],
			'state'=>$wrequest['state'],
			'zipcode'=>$wrequest['zipcode'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$wrequest['net_price'],
'payment_status' => $payment_status,
'min_diff' => $min_diff,
'washer_pay_status' => $washer_payment_status,
'admin_submit_for_settle'=>$wrequest['admin_submit_for_settle'],
'washer_change_pack' => $washer_change_pack,
'washercustnomeet' => $washercustnomeet,
'washer_wash_activity' => $washer_wash_activity,
'washer_30_min_noarrive' => $washer_30_min_noarrive
                );


            }

            //echo "total: ".$total_days_diff."<br>";
            //echo "total orders done: ".$completed_orders."<br>";
            //echo "average order frequency: ".round($total_days_diff/($completed_orders-1))."<br>";
             if($completed_orders > 1) $avg_order_frequency = round($total_days_diff/($completed_orders-1));

usort($pendingwashrequests_flashing, array('SiteController','sortById'));
usort($pendingwashrequests_upcoming, array('SiteController','sortById'));
        usort($pendingwashrequests_nonupcoming, array('SiteController','sortById'));

       $pendingwashrequests = array_merge($pendingwashrequests_flashing,$pendingwashrequests_upcoming,$pendingwashrequests_nonupcoming);


        }
        else{
           $result= 'false';
			$response= 'no wash requests found';
        }



        $json = array(
            'result'=> $result,
            'response'=> $response,
            'wash_requests' => $pendingwashrequests,
            'pending_wash_count' => $pendingorderscount,
            'cust_avg_order_frequency' => $avg_order_frequency
            //'upcoming' => $pendingwashrequests_upcoming,
            //'nonupcoming' => $pendingwashrequests_nonupcoming,
        );

        echo json_encode($json); die();
    }


	  public function actiongetallwashrequestsnew2(){

        if(Yii::app()->request->getParam('key') != API_KEY){
            echo "Invalid api key";
            die();
        }
		/* Checking for post(day) parameters */
		$order_day='';
		if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
			$day = Yii::app()->request->getParam('day');
			$event = Yii::app()->request->getParam('event');
			$status_qr = '';
			if($event == 'pending'){
				$status = 0;
				$status_qr = ' AND w.status="'.$status.'"';
			} elseif($event == 'total_orders'){
                $status_qr = " AND w.status IN('0','4')";
            } elseif($event == 'completed'){
				$status = 4;
				$status_qr = ' AND w.status="'.$status.'"';
			} elseif($event == 'processing'){
				$status = 2;
				$status_qr = ' AND (w.status >=1 && w.status <=3)';
			} elseif($event == 'canceled'){
				$status_qr = ' AND (w.status=5 || w.status=6)';
			} elseif($event == 'declined'){
                $status_qr = " AND (w.failed_transaction_id != '')";
            } elseif($event == 'express' || $event == 'deluxe' || $event == 'premium'){
                $status_qr=" AND (FIND_IN_SET('".$event."', w.package_list)>0 AND w.status IN('0','4'))";
            } elseif($event == 'coupon_code'){
                $status_qr = " AND w.coupon_code <> ''";
            } elseif($event == 'tip_amount'){
				$status_qr=" AND (w.tip_amount <> '' && w.tip_amount <> '0.00' && w.tip_amount <> '0')";
			}
			elseif($event == 'addoncompleted'){
				$status_qr=" AND (w.pet_hair_vehicles != '' OR  w.lifted_vehicles != '' OR  w.exthandwax_vehicles != '' OR  w.extplasticdressing_vehicles != '' OR  w.extclaybar_vehicles != '' OR  w.waterspotremove_vehicles != '' OR  w.upholstery_vehicles != '' OR  w.floormat_vehicles != '') AND w.status = 4";
			}
			elseif($event == 'ondemandcompleted'){
				$status_qr=" AND w.is_scheduled = 0 AND w.status = 4";
			}
			elseif($event == 'schedulecompleted'){
				$status_qr=" AND w.is_scheduled = 1 AND w.status = 4";
			}
			elseif($event == 'schedulecanceled'){
				$status_qr=" AND w.is_scheduled = 1 AND (w.status=5 || w.status=6)";
			}
			elseif($event == 'ondemandcanceled'){
				$status_qr=" AND w.is_scheduled = 0 AND (w.status=5 || w.status=6)";
			}
			else {
				$status_qr = '';
			}

			$order_day = " AND DATE_FORMAT(w.order_for,'%Y-%m-%d')='".$day."'".$status_qr;
			
		}
		/* END */



        $json = array();

        $result= 'true';
        $response= 'all wash requests';
        $pendingwashrequests = array();
        $pendingwashrequests_flashing = array();
        $pendingwashrequests_upcoming = array();
        $pendingwashrequests_nonupcoming = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $filter = '';
        $limit = 0;
        $filter = Yii::app()->request->getParam('filter');
        $limit = Yii::app()->request->getParam('limit');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
$pendingorderscount = 0;
$cust_query = '';
$agent_query = '';
 $avg_order_frequency = 0;
 $total_days_diff = 0;
 $completed_orders = 0;
 
 $total_entries = 0;
	$total_pages = 0;
	$limit = 0;
	$offset = 0;
	$page_number = 1;
	$limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
	$limit = 20;
	$offset = ($page_number -1) * $limit;

if($customer_id > 0) $cust_query = "w.customer_id=:customer_id AND ";
if($agent_id > 0) $agent_query = "w.agent_id=:agent_id AND ";

if($customer_id > 0){
    $cust_check = Customers::model()->findByPk($customer_id);
}

		//if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
//else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();

  if($filter == 'testorders'){

  $total_rows =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as countid FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 0 AND w.wash_request_position = '".APP_ENV."' ".$order_day)
  ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
  ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
  ->queryAll();

    if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 0 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC LIMIT ".$limit." OFFSET ".$offset)

    ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
    ->queryAll();

  }
  else{

    $total_rows =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as countid FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 1 AND w.wash_request_position = '".APP_ENV."' ".$order_day)

    ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
    ->queryAll();
$total_rows[0]['countid'] = 10;
    if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE ".$cust_query.$agent_query."c.hours_opt_check = 1 AND w.wash_request_position = '".APP_ENV."' ".$order_day." ORDER BY w.id DESC LIMIT ".$limit." OFFSET ".$offset)

        ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
    ->queryAll();

  }
  
   $total_entries = $total_rows[0]['countid'];
 if($total_entries > 0) $total_pages = ceil($total_entries / $limit);

   //print_r($qrRequests);

        if(count($qrRequests)>0){

            foreach($qrRequests as $ind=> $wrequest)
            {


if($wrequest['is_scheduled']){
                 if($wrequest['reschedule_time']) $scheduledatetime = $wrequest['reschedule_date']." ".$wrequest['reschedule_time'];
else $scheduledatetime = $wrequest['schedule_date']." ".$wrequest['schedule_time'];

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);

//$min_diff = abs($min_diff);
}
else{
  if($wrequest['status'] >= 0 && $wrequest['status'] < 4) $min_diff = 0;
  else{

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($wrequest['order_for']);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);
  }
}

if($wrequest['status'] == 0) $pendingorderscount++;

if(($customer_id > 0) && ($wrequest['status'] == 4)){
//echo $wrequest['id']." ".$wrequest['order_for']." "."<br>";
    //echo $qrRequests[$ind+1]['id']." ".$qrRequests[$ind+1]['order_for']." "."<br>";
    if(isset($qrRequests[$ind+1])){
        $order1_date = date("Y-m-d", strtotime($wrequest['order_for']));
      $order2_date = date("Y-m-d", strtotime($qrRequests[$ind+1]['order_for']));
      //echo $order1_date." ".$order2_date."<br>";
  $day_diff = date_diff(new DateTime($order1_date), new DateTime($order2_date));
     //echo $day_diff->format("%a")."<br>";
     //echo "working<br>";
$total_days_diff += $day_diff->format("%a");
}

 $completed_orders++;
}

                $cust_details = Customers::model()->findByAttributes(array("id"=>$wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id"=>$wrequest['agent_id']));
                $cars =  explode(",",$wrequest['car_list']);
				$packs =  explode(",",$wrequest['package_list']);
				$vehicles = array();
				foreach($cars as $ind=>$car){
                    $car_details = Vehicle::model()->findByAttributes(array("id"=>$car));
		    
		    $veh_addons = '';
		    
		    $pet_hair_vehicles_arr = explode(",", $wrequest['pet_hair_vehicles']);
if (in_array($car, $pet_hair_vehicles_arr)) $veh_addons .= 'Extra Cleaning, ';

$lifted_vehicles_arr = explode(",", $wrequest['lifted_vehicles']);
if (in_array($car, $lifted_vehicles_arr)) $veh_addons .= 'Lifted Truck, ';

$exthandwax_addon_arr = explode(",", $wrequest['exthandwax_vehicles']);
if (in_array($car, $exthandwax_addon_arr)) $veh_addons .= 'Liquid Hand Wax, ';

$extplasticdressing_addon_arr = explode(",", $wrequest['extplasticdressing_vehicles']);
if (in_array($car, $extplasticdressing_addon_arr)) $veh_addons .= 'Exterior Plastic Dressing, ';

$extclaybar_addon_arr = explode(",", $wrequest['extclaybar_vehicles']);
if (in_array($car, $extclaybar_addon_arr)) $veh_addons .= 'Clay Bar & Paste Wax, ';

$waterspotremove_addon_arr = explode(",", $wrequest['waterspotremove_vehicles']);
if (in_array($car, $waterspotremove_addon_arr)) $veh_addons .= 'Water Spot Removal, ';

$upholstery_addon_arr = explode(",", $wrequest['upholstery_vehicles']);
if (in_array($car, $upholstery_addon_arr)) $veh_addons .= 'Upholstery Conditioning, ';

$floormat_addon_arr = explode(",", $wrequest['floormat_vehicles']);
if (in_array($car, $floormat_addon_arr)) $veh_addons .= 'Floor Mat Cleaning, ';

$veh_addons = rtrim($veh_addons, ", ");

                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind], 'addons' => $veh_addons);
				}

				
				if(($cust_details->first_name != '') && ($cust_details->last_name != '')){
						$customername = '';
						$cust_name = explode(" ", trim($cust_details->last_name));
						$customername = $cust_details->first_name." ".strtoupper(substr($cust_name[0], 0, 1)).".";
						
					}
					else{
						$customername = '';
				$cust_name = explode(" ", trim($cust_details->customername));
				if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
				else $customername = $cust_name[0];
					}
					
					$customername = strtolower($customername);
$customername = ucwords($customername);

								$agent_info = array();
				if(count($agent_details)){
					   $agent_info = array('agent_id'=>$wrequest['agent_id'], 'real_washer_id'=>$agent_details->real_washer_id, 'agent_name'=>$agent_details->first_name." ".$agent_details->last_name, 'agent_phoneno'=>$agent_details->phone_number, 'agent_email'=>$agent_details->email);
				}
$payment_status = '';
$submerchant_id = '';
$transaction_status = '';

if($wrequest['failed_transaction_id']){
  $payment_status = 'Declined';
}
else{
if($wrequest['transaction_id']){

if($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held'){
$payment_status = 'Processed';
}

else if($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released'){
$payment_status = 'Released';
}


/*if($cust_details->client_position == 'real') $payresult = Yii::app()->braintree->getTransactionById_real($wrequest['transaction_id']);
else $payresult = Yii::app()->braintree->getTransactionById($wrequest['transaction_id']);
if($payresult['success'] == 1) {
//$submerchant_id = $payresult['merchant_id'];
$transaction_status = $payresult['status'];
}*/

 }
}


$kartapiresult = $this->washingkart($wrequest['id'], API_KEY, 0, AES256CBC_API_PASS);
$kartdata = json_decode($kartapiresult);


if($wrequest['is_flagged'] == 1) $payment_status = 'Check Fraud';

 if(($min_diff < 0) && ($wrequest['status'] == 0)){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_flashing[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>date('Y-m-d',strtotime($wrequest['schedule_date'])),
                    'schedule_time'=>date('h:i A', strtotime($wrequest['schedule_time'])),
					'reschedule_date'=>$resched_date,
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$resched_time,
					'created_date'=>date('Y-m-d',strtotime($wrequest['created_date']))." ".date('h:i A', strtotime($wrequest['created_date'])),
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
					'submerchant_id' => $submerchant_id,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$kartdata->net_price,
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );

}

if($min_diff >= 0){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_upcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>date('Y-m-d',strtotime($wrequest['schedule_date'])),
                    'schedule_time'=>date('h:i A', strtotime($wrequest['schedule_time'])),
					'reschedule_date'=>$resched_date,
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$resched_time,
					'created_date'=>date('Y-m-d',strtotime($wrequest['created_date']))." ".date('h:i A', strtotime($wrequest['created_date'])),
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
					'submerchant_id' => $submerchant_id,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$kartdata->net_price,
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );

}
if(($min_diff < 0) && ($wrequest['status'] > 0)){
     $pendingwashrequests_nonupcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
                    'order_for'=>date('Y-m-d h:i A',strtotime($wrequest['order_for'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'submerchant_id' => $submerchant_id,
					'transaction_status'=>$transaction_status,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$kartdata->net_price,
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );

}

				$pendingwashrequests[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
					'transaction_status'=>$transaction_status,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
					'net_price'=>$kartdata->net_price,
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );


            }

            //echo "total: ".$total_days_diff."<br>";
            //echo "total orders done: ".$completed_orders."<br>";
            //echo "average order frequency: ".round($total_days_diff/($completed_orders-1))."<br>";
             if($completed_orders > 1) $avg_order_frequency = round($total_days_diff/($completed_orders-1));

usort($pendingwashrequests_flashing, array('SiteController','sortById'));
usort($pendingwashrequests_upcoming, array('SiteController','sortById'));
        usort($pendingwashrequests_nonupcoming, array('SiteController','sortById'));

       $pendingwashrequests = array_merge($pendingwashrequests_flashing,$pendingwashrequests_upcoming,$pendingwashrequests_nonupcoming);


        }
        else{
           $result= 'false';
			$response= 'no wash requests found';
        }



        $json = array(
            'result'=> $result,
            'response'=> $response,
            'wash_requests' => $pendingwashrequests,
            'pending_wash_count' => $pendingorderscount,
            'cust_avg_order_frequency' => $avg_order_frequency,
	    'total_entries' => $total_entries,
	    'total_pages' => $total_pages
            //'upcoming' => $pendingwashrequests_upcoming,
            //'nonupcoming' => $pendingwashrequests_nonupcoming,
        );

        echo json_encode($json); die();
    }

    public function actiongetpaymentreports(){

        if(Yii::app()->request->getParam('key') != API_KEY){
            echo "Invalid api key";
            die();
        }
        /* Checking for post(day) parameters */
        $order_day='';
        if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
            $day = Yii::app()->request->getParam('day');
            $event = Yii::app()->request->getParam('event');
            $status_qr = '';
            if($event == 'pending'){
                $status = 0;
                $status_qr = ' AND w.status="'.$status.'"';
            } elseif($event == 'completed'){
                $status = 4;
                $status_qr = ' AND w.status="'.$status.'"';
            } elseif($event == 'processing'){
                $status = 2;
                $status_qr = ' AND (w.status >=1 && w.status <=3)';
            } elseif($event == 'canceled'){
                $status_qr = ' AND (w.status=5 || w.status=6)';
            } elseif($event == 'declined'){
                $status_qr = " AND (w.failed_transaction_id != '')";
            } else {
                $status_qr = '';
            }
            $order_day = " AND DATE_FORMAT(w.order_for,'%Y-%m-%d')= :day".$status_qr;
        }
        /* END */

        $json = array();

        $result= 'true';
        $response= 'all wash requests';
        $pendingwashrequests = array();
        $pendingwashrequests_flashing = array();
        $pendingwashrequests_upcoming = array();
        $pendingwashrequests_nonupcoming = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $filter = '';
        $limit = 0;
	$offset = 0;
	$page_number = 1;
        $filter = Yii::app()->request->getParam('filter');
        $limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
        $pendingorderscount = 0;
        $cust_query = '';
        $agent_query = '';
        $avg_order_frequency = 0;
        $total_days_diff = 0;
        $completed_orders = 0;
	$total_entries = 0;
	$total_pages = 0;

        if($customer_id > 0) $cust_query = "w.customer_id=".$customer_id." AND ";
        if($agent_id > 0) $agent_query = "w.agent_id=".$agent_id." AND ";

        if($customer_id > 0){
            $cust_check = Customers::model()->findByPk($customer_id);
        }
	
	$limit = 10;
	$offset = ($page_number -1) * $limit;
	
	$total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM washing_requests WHERE wash_request_position = '".APP_ENV."'")->queryAll();
 $total_entries = $total_rows[0]['countid'];
 if($total_entries > 0) $total_pages = ceil($total_entries / $limit);

        //if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
        //else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();

       
            if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = '".APP_ENV."' ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset)->queryAll();
            

        //print_r($qrRequests);

        if(count($qrRequests)>0){
            foreach($qrRequests as $ind=> $wrequest) {
                if($wrequest['is_scheduled']){
                    if($wrequest['reschedule_time']) $scheduledatetime = $wrequest['reschedule_date']." ".$wrequest['reschedule_time'];
                    else $scheduledatetime = $wrequest['schedule_date']." ".$wrequest['schedule_time'];

                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;

                    $min_diff = round(($from_time - $to_time) / 60,2);

                    //$min_diff = abs($min_diff);
                } else {
                    if($wrequest['status'] >= 0 && $wrequest['status'] < 4) $min_diff = 0;
                    else {
                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($wrequest['order_for']);
                        $min_diff = 0;
                        $min_diff = round(($from_time - $to_time) / 60,2);
                    }
                }

                if($wrequest['status'] == 0) $pendingorderscount++;

                if(($customer_id > 0) && ($wrequest['status'] == 4)){
                //echo $wrequest['id']." ".$wrequest['order_for']." "."<br>";
                    //echo $qrRequests[$ind+1]['id']." ".$qrRequests[$ind+1]['order_for']." "."<br>";
                    if(isset($qrRequests[$ind+1])){
                        $order1_date = date("Y-m-d", strtotime($wrequest['order_for']));
                        $order2_date = date("Y-m-d", strtotime($qrRequests[$ind+1]['order_for']));
                        //echo $order1_date." ".$order2_date."<br>";
                        $day_diff = date_diff(new DateTime($order1_date), new DateTime($order2_date));
                        //echo $day_diff->format("%a")."<br>";
                        //echo "working<br>";
                        $total_days_diff += $day_diff->format("%a");
                    }
                    $completed_orders++;
                }

                $cust_details = Customers::model()->findByAttributes(array("id"=>$wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id"=>$wrequest['agent_id']));
                $cars =  explode(",",$wrequest['car_list']);
                $packs =  explode(",",$wrequest['package_list']);
                $vehicles = array();
                foreach($cars as $ind=>$car){
                    $car_details = Vehicle::model()->findByAttributes(array("id"=>$car));           
                    $veh_addons = '';           
                    $pet_hair_vehicles_arr = explode(",", $wrequest['pet_hair_vehicles']);
                    if (in_array($car, $pet_hair_vehicles_arr)) $veh_addons .= 'Extra Cleaning, ';
                    $lifted_vehicles_arr = explode(",", $wrequest['lifted_vehicles']);
                    if (in_array($car, $lifted_vehicles_arr)) $veh_addons .= 'Lifted Truck, ';
                    $exthandwax_addon_arr = explode(",", $wrequest['exthandwax_vehicles']);
                    if (in_array($car, $exthandwax_addon_arr)) $veh_addons .= 'Liquid Hand Wax, ';

                    $extplasticdressing_addon_arr=explode(",", $wrequest['extplasticdressing_vehicles']);
                    if (in_array($car, $extplasticdressing_addon_arr)) $veh_addons .= 'Exterior Plastic Dressing, ';

                    $extclaybar_addon_arr = explode(",", $wrequest['extclaybar_vehicles']);
                    if (in_array($car, $extclaybar_addon_arr)) $veh_addons .= 'Clay Bar & Paste Wax, ';

                    $waterspotremove_addon_arr = explode(",", $wrequest['waterspotremove_vehicles']);
                    if (in_array($car, $waterspotremove_addon_arr)) $veh_addons .= 'Water Spot Removal, ';

                    $upholstery_addon_arr = explode(",", $wrequest['upholstery_vehicles']);
                    if (in_array($car, $upholstery_addon_arr)) $veh_addons .= 'Upholstery Conditioning, ';

                    $floormat_addon_arr = explode(",", $wrequest['floormat_vehicles']);
                    if (in_array($car, $floormat_addon_arr)) $veh_addons .= 'Floor Mat Cleaning, ';

                    $veh_addons = rtrim($veh_addons, ", ");

                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind], 'addons' => $veh_addons);
                }

                
                if(($cust_details->first_name != '') && ($cust_details->last_name != '')){
                    $customername = '';
                    $cust_name = explode(" ", trim($cust_details->last_name));
                    $customername = $cust_details->first_name." ".strtoupper(substr($cust_name[0], 0, 1)).".";
                        
                } else {
                    $customername = '';
                    $cust_name = explode(" ", trim($cust_details->customername));
                    if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
                    else $customername = $cust_name[0];
                }
                    
                $customername = strtolower($customername);
                $customername = ucwords($customername);

                $agent_info = array();
                if(count($agent_details)){
                    $agent_info = array('agent_id'=>$wrequest['agent_id'], 'agent_name'=>$agent_details->first_name." ".$agent_details->last_name, 'agent_phoneno'=>$agent_details->phone_number, 'agent_email'=>$agent_details->email);
                }

                $payment_status = '';
                $submerchant_id = '';
		$transaction_status = '';

                if($wrequest['failed_transaction_id']){
                    $payment_status = 'Declined';
                } else {
                    if($wrequest['transaction_id']){
                        if($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held'){
                            $payment_status = 'Processed';
                        } else if($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released'){
                            $payment_status = 'Released';
                        }

                        if($cust_details->client_position == 'real') $payresult = Yii::app()->braintree->getTransactionById_real($wrequest['transaction_id']);
                        else $payresult = Yii::app()->braintree->getTransactionById($wrequest['transaction_id']);
                        if($payresult['success'] == 1) {
                        //$submerchant_id = $payresult['merchant_id'];
			$transaction_status = $payresult['status'];
                        }

                    }
                }

                if($wrequest['is_flagged'] == 1) $payment_status = 'Check Fraud';

             

               
                    $resched_date = '';
                    $resched_time = '';
                    if(strtotime($wrequest['reschedule_date']) > 0){
                       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
                        $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
                    }
                   

                $pendingwashrequests[] = array(
                    'id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                    'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
                    'reschedule_date'=>$wrequest['reschedule_date'],
                    'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
                    'created_date'=>$wrequest['created_date'],
                    'transaction_id'=>$wrequest['transaction_id'],
                    'failed_transaction_id'=>$wrequest['failed_transaction_id'],
		    'transaction_status'=>$transaction_status,
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
                    'wash_request_position'=>$wrequest['wash_request_position'],
                    'total_price'=>$wrequest['total_price'],
                    'net_price'=>$wrequest['net_price'],
                    'company_total'=>$wrequest['company_total'],
                    'agent_total'=>$wrequest['agent_total'],
                    'bundle_discount'=>$wrequest['bundle_discount'],
                    'fifth_wash_discount'=>$wrequest['fifth_wash_discount'],
                    'first_wash_discount'=>$wrequest['first_wash_discount'],
                    'coupon_discount'=>$wrequest['coupon_discount'],
                    'coupon_code'=>$wrequest['coupon_code'],
                    'vip_coupon_code'=>$wrequest['vip_coupon_code'],
                    'company_discount'=>$wrequest['company_discount'],
                    'tip_amount'=>$wrequest['tip_amount'],
                    'payment_status' => $payment_status,
                    'min_diff' => $min_diff
                );
            }

            //echo "total: ".$total_days_diff."<br>";
            //echo "total orders done: ".$completed_orders."<br>";
            //echo "average order frequency: ".round($total_days_diff/($completed_orders-1))."<br>";
            if($completed_orders > 1) $avg_order_frequency=round($total_days_diff/($completed_orders-1));

            

        } else {
            $result= 'false';
            $response= 'no wash requests found';
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'wash_requests' => $pendingwashrequests,
            'pending_wash_count' => $pendingorderscount,
            'cust_avg_order_frequency' => $avg_order_frequency,
	    'total_entries' => $total_entries,
	    'total_pages' => $total_pages
        );

        echo json_encode($json); die();
    }


    	  public function actionsearchorders(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $json = array();

        $result= 'true';
        $response= 'all wash requests';
        $pendingwashrequests = array();
        $pendingwashrequests_upcoming = array();
        $pendingwashrequests_nonupcoming = array();
        $total_pages = 0;
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $filter = '';
        $limit = 0;
        $filter = Yii::app()->request->getParam('filter');
        $limit = Yii::app()->request->getParam('limit');
        $search_area = Yii::app()->request->getParam('search_area');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
$pendingorderscount = 0;
$cust_query = '';
$agent_query = '';
$cust_veh_query = '';
$order_query = '';

$query = Yii::app()->request->getParam('query');
	$page_number = 1;
	if(Yii::app()->request->getParam('page_number')) $page_number = Yii::app()->request->getParam('page_number');
	$offset = ($page_number -1) * $limit;

		$limit_str = '';
      $total_count = 0;
      if($limit && ($limit != 'none')){
          $limit_str = " LIMIT ".$limit." OFFSET ".$offset;
      }

if(!$search_area) $search_area = "Order Number";

if($search_area == "Order Number") $order_query = "(id LIKE :query) ";
if($search_area == "Created Date") $order_query = "(created_date LIKE :query) ";
if($search_area == "Scheduled Date") $order_query = "(order_for LIKE :query AND is_scheduled = 1) ";
if($search_area == "On-Demand") $order_query = "(is_scheduled = 0) ";
if($search_area == "Scheduled") $order_query = "(is_scheduled = 1) ";

		//if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
//else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();


if($query){
    $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE ".$order_query."ORDER BY id DESC".$limit_str)->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();
 $total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM washing_requests WHERE ".$order_query."ORDER BY id DESC")->bindValue(':query', "%$query%", PDO::PARAM_STR)->queryAll();
 $total_count = $total_rows[0]['countid'];
 if($total_count > 0) $total_pages = ceil($total_count / $limit);

}

   //print_r($qrRequests);
        if(count($qrRequests)>0){

            foreach($qrRequests as $wrequest)
            {


if($wrequest['is_scheduled']){
                 if($wrequest['reschedule_time']) $scheduledatetime = $wrequest['reschedule_date']." ".$wrequest['reschedule_time'];
else $scheduledatetime = $wrequest['schedule_date']." ".$wrequest['schedule_time'];

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);

//$min_diff = abs($min_diff);
}
else{
  if($wrequest['status'] >= 0 && $wrequest['status'] < 4) $min_diff = 0;
  else{

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($wrequest['order_for']);
$min_diff = 0;

$min_diff = round(($from_time - $to_time) / 60,2);
  }
}

if($wrequest['status'] == 0) $pendingorderscount++;

                $cust_details = Customers::model()->findByAttributes(array("id"=>$wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id"=>$wrequest['agent_id']));
                $cars =  explode(",",$wrequest['car_list']);
				$packs =  explode(",",$wrequest['package_list']);
				$vehicles = array();
				foreach($cars as $ind=>$car){
                    $car_details = Vehicle::model()->findByAttributes(array("id"=>$car));
                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind]);
				}

				
				if(($cust_details->first_name != '') && ($cust_details->last_name != '')){
						$customername = '';
						$cust_name = explode(" ", trim($cust_details->last_name));
						$customername = $cust_details->first_name." ".strtoupper(substr($cust_name[0], 0, 1)).".";
						
					}
					else{
						$customername = '';
				$cust_name = explode(" ", trim($cust_details->customername));
				if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
				else $customername = $cust_name[0];
					}
					
					$customername = strtolower($customername);
$customername = ucwords($customername);
					
								$agent_info = array();
				if(count($agent_details)){
					   $agent_info = array('agent_id'=>$wrequest['agent_id'], 'agent_name'=>$agent_details->first_name." ".$agent_details->last_name, 'agent_phoneno'=>$agent_details->phone_number, 'agent_email'=>$agent_details->email);
				}
$payment_status = '';

if($wrequest['failed_transaction_id']){
  $payment_status = 'Declined';
}
else{
if($wrequest['transaction_id']){

if($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held'){
$payment_status = 'Processed';
}

else if($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released'){
$payment_status = 'Released';
}

 }
}

 if($min_diff >= 0){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_upcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>date('Y-m-d',strtotime($wrequest['schedule_date'])),
                    'schedule_time'=>date('h:i A', strtotime($wrequest['schedule_time'])),
					'reschedule_date'=>$resched_date,
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$resched_time,
					'created_date'=>date('Y-m-d',strtotime($wrequest['created_date']))." ".date('h:i A', strtotime($wrequest['created_date'])),
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );

}
if($min_diff < 0){
     $pendingwashrequests_nonupcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );

}

				$pendingwashrequests[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->first_name." ".$cust_details->last_name,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                     'agent_details'=> $agent_info,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],
                    'is_scheduled'=>$wrequest['is_scheduled'],
                    'schedule_date'=>$wrequest['schedule_date'],
                    'schedule_time'=>$wrequest['schedule_time'],
					'reschedule_date'=>$wrequest['reschedule_date'],
					'checklist'=>$wrequest['checklist'],
                    'reschedule_time'=>$wrequest['reschedule_time'],
					'created_date'=>$wrequest['created_date'],
					'transaction_id'=>$wrequest['transaction_id'],
					'failed_transaction_id'=>$wrequest['failed_transaction_id'],
                    'scheduled_cars_info'=>$wrequest['scheduled_cars_info'],
                    'schedule_total'=>$wrequest['schedule_total'],
                    'schedule_company_total'=>$wrequest['schedule_company_total'],
                    'schedule_agent_total'=>$wrequest['schedule_agent_total'],
					'wash_request_position'=>$wrequest['wash_request_position'],
'payment_status' => $payment_status,
'min_diff' => $min_diff
                );


            }

   usort($pendingwashrequests_upcoming, array('SiteController','sortById'));
        usort($pendingwashrequests_nonupcoming, array('SiteController','sortById'));

       $pendingwashrequests = array_merge($pendingwashrequests_upcoming,$pendingwashrequests_nonupcoming);


        }
        else{
           $result= 'false';
			$response= 'no wash requests found';
        }



        $json = array(
            'result'=> $result,
            'response'=> $response,
            'wash_requests' => $pendingwashrequests,
            'pending_wash_count' => $pendingorderscount,
            'total_wash_requests' => $total_count,
            'total_pages' => $total_pages
        );

        echo json_encode($json); die();
    }


public function actionnewcustomerwelcomepush(){
  if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

	$newclients = Yii::app()->db->createCommand("SELECT * FROM customers WHERE is_first_wash = 0 AND is_firstwash_reminder_push_sent = 0")->queryAll();

	if(count($newclients)){
	    foreach($newclients as $client){
	       // echo $client['id']."<br>";

	         $current_time = strtotime(date('Y-m-d H:i:s'));
$create_time = strtotime($client['created_date']);
$min_diff = 0;
if($current_time > $create_time){
$min_diff = round(($current_time - $create_time) / 60,2);
}

//echo $min_diff;
//echo "<br>";
if($min_diff >= 30){

                 $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '".$client['id']."' ORDER BY last_used DESC LIMIT 1")->queryAll();

$cust_details = Customers::model()->findByAttributes(array("id"=>$client['id']));
if(($cust_details->customername) && ($cust_details->customername != 'N/A')){
  $custname_arr = explode(" ",$cust_details->customername);
  $custname = " ".$custname_arr[0];
}
else{
  $custname = '';
}


						 $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '25' ")->queryAll();
						$message = $pushmsg[0]['message'];
$message = str_replace("[CUSTNAME]",$custname, $message);
						foreach( $clientdevices as $ctdevice){

							//echo $agentdetails['mobile_type'];
							$device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
								$alert_type = "default";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
							//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}

						 Customers::model()->updateByPk($client['id'], array("is_firstwash_reminder_push_sent" => 1));


}

	    }
	}



}


public function actioncustomernextwashremindpush(){
  if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

	$clientlist = Yii::app()->db->createCommand("SELECT * FROM customers WHERE is_first_wash = 1 AND (is_nextwash_reminder_push_sent = 0 OR is_30days_reminder_push_sent = 0)")->queryAll();

	if(count($clientlist)){
	    foreach($clientlist as $client){
	       // echo $client['id']."<br>";
	        $wash_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$client['id'], 'status' => 4),array('order'=>'id DESC'));
	         if(count($wash_check)){
	             //echo $client['id']." ".$wash_check->id." ";
	              $current_time = strtotime(date('Y-m-d H:i:s'));

	              $create_time = strtotime($wash_check->order_for);
$min_diff = 0;
if($current_time > $create_time){
$min_diff = round(($current_time - $create_time) / 60,2);
}

//echo $min_diff;
//echo "<br>";
if(($min_diff >= 14400) && ($min_diff < 43200) && (!$client['is_nextwash_reminder_push_sent'])){

                 $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '".$client['id']."' ORDER BY last_used DESC LIMIT 1")->queryAll();

$cust_details = Customers::model()->findByAttributes(array("id"=>$client['id']));
if(($cust_details->customername) && ($cust_details->customername != 'N/A')){
  $custname_arr = explode(" ",$cust_details->customername);
  $custname = " ".$custname_arr[0];
}
else{
  $custname = '';
}


						 $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '26' ")->queryAll();
						$message = $pushmsg[0]['message'];
$message = str_replace("[CUSTNAME]",$custname, $message);
						foreach( $clientdevices as $ctdevice){

							//echo $agentdetails['mobile_type'];
							$device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
								$alert_type = "default";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
							//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}

						 Customers::model()->updateByPk($client['id'], array("is_nextwash_reminder_push_sent" => 1));


}

if(($min_diff >= 43200) && (!$client['is_30days_reminder_push_sent'])){

                 $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '".$client['id']."' ORDER BY last_used DESC LIMIT 1")->queryAll();

$cust_details = Customers::model()->findByAttributes(array("id"=>$client['id']));
if(($cust_details->customername) && ($cust_details->customername != 'N/A')){
  $custname_arr = explode(" ",$cust_details->customername);
  $custname = " ".$custname_arr[0];
}
else{
  $custname = '';
}


						 $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '38' ")->queryAll();
						$message = $pushmsg[0]['message'];
$message = str_replace("[CUSTNAME]",$custname, $message);
						foreach( $clientdevices as $ctdevice){

							//echo $agentdetails['mobile_type'];
							$device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
								$alert_type = "default";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
							//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}

						 Customers::model()->updateByPk($client['id'], array("is_nextwash_reminder_push_sent" => 1, "is_30days_reminder_push_sent" => 1));


}
	         }



	    }
	}



}


public function actionaddbugentry(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

        $platform = Yii::app()->request->getParam('platform');
		$status = Yii::app()->request->getParam('status');
		$description = Yii::app()->request->getParam('description');


		if((isset($platform) && !empty($platform)) &&
			(isset($status) && !empty($status)) &&
			(isset($description) && !empty($description)))

			 {

                   $data= array(
					'platform'=> $platform,
					'status'=> $status,
					'description'=> $description,
					'created_date' => date('Y-m-d H:i:s'));

				  Yii::app()->db->createCommand()->insert('bug_report', $data);

                    	$result= 'true';
		$response= 'Bug entry added';

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


	    public function actiongetbugreport() {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$response = "nothing found";
		$result = "false";

$alllogs =  Yii::app()->db->createCommand("SELECT * FROM bug_report ORDER BY id asc")->queryAll();
$alllogs_new = [];

			if(count($alllogs)){

				$response = "bug reports";
				$result = "true";

			}



       $json = array(
			'result'=> $result,
			'response'=> $response,
			'bugreport' => $alllogs
		);

		echo json_encode($json);
		die();

}


public function actionsendwasherpush(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

        $agent_id = Yii::app()->request->getParam('agent_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $admin_username = Yii::app()->request->getParam('admin_username');
		$message = Yii::app()->request->getParam('message');

		if((isset($agent_id) && !empty($agent_id)) &&
			(isset($message) && !empty($message)))

			 {
			     
			     $agent_check = Agents::model()->findByPk($agent_id);
			     
			     if(!count($agent_check)){
			       	$result= 'false';
		$response= 'No washer found';
			     }
			     else{
                      $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1")->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)->queryAll();
         if((count($agentdevices)) && (!$agent_check->block_washer))
            {
                foreach($agentdevices as $agdevice)
                {
                   
                    $device_type = strtolower($agdevice['device_type']);
                    $notify_token = $agdevice['device_token'];
                    $alert_type = "default";
                    $notify_msg = urlencode($message);

                    $notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$notifyurl);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                    if($notify_msg) $notifyresult = curl_exec($ch);
                    curl_close($ch);
                }
            }
            
            $washeractionlogdata = array(
                        'agent_id'=> $agent_id,
                        'wash_request_id'=> $wash_request_id,
                        'agent_company_id'=> $agent_check->real_washer_id,
                        'admin_username'=> $admin_username,
                        'action'=> 'washerpush',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
            
            $result= 'true';
		$response= 'Push notification sent';
			         
			     }
			     
			     

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


    public function actionnonreturncustomercheck(){
  if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

	$clientlist = Customers::model()->findAllByAttributes(array('non_return_check' => 1), array('limit'=> 1000));

	if(count($clientlist)){
	    foreach($clientlist as $client){
		//echo $client->id."<br>";
	       // echo $client['id']."<br>";
	        $wash_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$client->id, 'status' => 4),array('order'=>'id DESC'));
	         if(count($wash_check)){
	             //echo $client['id']." ".$wash_check->id." ";
	              $current_time = strtotime(date('Y-m-d H:i:s'));

	              $create_time = strtotime($wash_check->order_for);
$min_diff = 0;
if($current_time > $create_time){
$min_diff = round(($current_time - $create_time) / 60,2);
}

//echo $min_diff;
//echo "<br>";
//more than 30 days
if($min_diff >= 43200){

	if(($min_diff >= 43200) && ($min_diff < 86400)) Customers::model()->updateByPk($client->id, array("is_non_returning" => 1, "nonreturn_cat" => 30));
	if(($min_diff >= 86400) && ($min_diff < 129600)) Customers::model()->updateByPk($client->id, array("is_non_returning" => 1, "nonreturn_cat" => 60));
	if($min_diff >= 129600) Customers::model()->updateByPk($client->id, array("is_non_returning" => 1, "nonreturn_cat" => 90));


}
else{
    Customers::model()->updateByPk($client->id, array("is_non_returning" => 0, "nonreturn_cat" => 0));
}
	         }

Customers::model()->updateByPk($client->id, array("non_return_check" => 0));

	    }
	}
	else{
		Customers::model()->updateAll(array('non_return_check' => 1));

	}



}


	    public function actiongetnonreturncustomers() {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$response = "nothing found";
		$result = "false";
         $nonreturncust_arr = [];
	 $nonreturncust_arr_30 = [];
	 $nonreturncust_arr_60 = [];
	 $nonreturncust_arr_90 = [];
	 $ind30 = 0;
	 $ind60 = 0;
	 $ind90 = 0;
	 $total_entries_30 = 0;
	$total_pages_30 = 0;
	 $total_entries_60 = 0;
	$total_pages_60 = 0;
	$total_entries_90 = 0;
	$total_pages_90 = 0;
	$limit = 0;
	$offset_30 = 0;
	$offset_60 = 0;
	$offset_90 = 0;
	$page_number = 1;
	$limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
	$range = Yii::app()->request->getParam('range');
	$limit = 100;
	if($range == 30) $offset_30 = ($page_number -1) * $limit;
	if($range == 60) $offset_60 = ($page_number -1) * $limit;
	if($range == 90) $offset_90 = ($page_number -1) * $limit;
	
	$total_rows_30 = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 30")->queryAll();
if($limit > 0) $nonreturncust_arr_30 =  Yii::app()->db->createCommand("SELECT id, first_name, last_name, email, contact_number, total_wash FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 30 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset_30)->queryAll();

 $total_entries_30 = $total_rows_30[0]['countid'];
 if($total_entries_30 > 0) $total_pages_30 = ceil($total_entries_30 / $limit);
 
 $total_rows_60 = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 60")->queryAll();
if($limit > 0) $nonreturncust_arr_60 =  Yii::app()->db->createCommand("SELECT id, first_name, last_name, email, contact_number, total_wash FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 60 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset_60)->queryAll();

 $total_entries_60 = $total_rows_60[0]['countid'];
 if($total_entries_60 > 0) $total_pages_60 = ceil($total_entries_60 / $limit);
 
  $total_rows_90 = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 90")->queryAll();
if($limit > 0) $nonreturncust_arr_90 =  Yii::app()->db->createCommand("SELECT id, first_name, last_name, email, contact_number, total_wash FROM customers WHERE is_non_returning = 1 AND nonreturn_cat = 90 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset_90)->queryAll();

 $total_entries_90 = $total_rows_90[0]['countid'];
 if($total_entries_90 > 0) $total_pages_90 = ceil($total_entries_90 / $limit);
 
			if(count($nonreturncust_arr_30) || count($nonreturncust_arr_60) || count($nonreturncust_arr_90)){

				$response = "nonreturning customers";
				$result = "true";
                /*foreach($all_customers as $ind=>$customer){
                     $last_wash = Washingrequests::model()->findByAttributes(array('customer_id'=>$customer['id'], 'status' => 4),array('order'=>'id DESC'));
		     if(count($last_wash)){
			$current_time = strtotime(date('Y-m-d H:i:s'));

			$create_time = strtotime($last_wash->order_for);
			$min_diff = 0;
			if($current_time > $create_time){
				$min_diff = round(($current_time - $create_time) / 60,2);
			}

			// 30 days or more inactive
			if(($min_diff >= 43200) && ($min_diff < 86400)){

			$nonreturncust_arr_30[$ind30]['id'] = $customer['id'];
			$nonreturncust_arr_30[$ind30]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_30[$ind30]['email'] = $customer['email'];
			$nonreturncust_arr_30[$ind30]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_30[$ind30]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_30[$ind30]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$ind30++;

			}
			
			// 60 days or more inactive
			if(($min_diff >= 86400) && ($min_diff < 129600)){

			$nonreturncust_arr_60[$ind60]['id'] = $customer['id'];
			$nonreturncust_arr_60[$ind60]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_60[$ind60]['email'] = $customer['email'];
			$nonreturncust_arr_60[$ind60]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_60[$ind60]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_60[$ind60]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$ind60++;

			}
			
			// 90 days or more inactive
			if($min_diff >= 129600){

			$nonreturncust_arr_90[$ind90]['id'] = $customer['id'];
			$nonreturncust_arr_90[$ind90]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_90[$ind90]['email'] = $customer['email'];
			$nonreturncust_arr_90[$ind90]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_90[$ind90]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_90[$ind90]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$ind90++;

			}
		     }
            
                }*/

			}



       $json = array(
			'result'=> $result,
			'response'=> $response,
			'nonreturncusts_30' => $nonreturncust_arr_30,
			'nonreturncusts_60' => $nonreturncust_arr_60,
			'nonreturncusts_90' => $nonreturncust_arr_90,
			'total_entries_30' => $total_entries_30,
			'total_pages_30' => $total_pages_30,
			'total_entries_60' => $total_entries_60,
			'total_pages_60' => $total_pages_60,
			'total_entries_90' => $total_entries_90,
			'total_pages_90' => $total_pages_90,
		);

		echo json_encode($json);
		die();

}


	    public function actiongetinactivecustomers() {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$response = "nothing found";
		$result = "false";
	 $inactivecust_arr_5 = [];
	 $inactivecust_arr_10 = [];
	 $inactivecust_arr_15 = [];
	 $ind5 = 0;
	 $ind10 = 0;
	 $ind15 = 0;
	 $total_entries = 0;
	$total_pages = 0;
	$limit = 0;
	$offset = 0;
	$page_number = 1;
	$limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
	$limit = 100;
	$offset = ($page_number -1) * $limit;
  
$total_rows = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM customers WHERE total_wash = 0 AND is_first_wash = 0")->queryAll();
if($limit > 0) $all_customers =  Yii::app()->db->createCommand("SELECT * FROM customers WHERE total_wash = 0 AND is_first_wash = 0 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset)->queryAll();

 $total_entries = $total_rows[0]['countid'];
 if($total_entries > 0) $total_pages = ceil($total_entries / $limit);
 

			if(count($all_customers)){

				$response = "inactive customers";
				$result = "true";
                foreach($all_customers as $ind=>$customer){
                     
			$current_time = strtotime(date('Y-m-d H:i:s'));

			$create_time = strtotime($customer['created_date']);
			$min_diff = 0;
			if($current_time > $create_time){
				$min_diff = round(($current_time - $create_time) / 60,2);
			}

			// 5 days or more inactive
			if(($min_diff >= 7200) && ($min_diff < 14400)){

			$nonreturncust_arr_5[$ind5]['id'] = $customer['id'];
			$nonreturncust_arr_5[$ind5]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_5[$ind5]['email'] = $customer['email'];
			$nonreturncust_arr_5[$ind5]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_5[$ind5]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_5[$ind5]['updated_date'] = date('Y-m-d h:i A', strtotime($customer['updated_date']));
			
			$ind5++;

			}
			
			// 10 days or more inactive
			if(($min_diff >= 14400) && ($min_diff < 21600)){

			$nonreturncust_arr_10[$ind10]['id'] = $customer['id'];
			$nonreturncust_arr_10[$ind10]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_10[$ind10]['email'] = $customer['email'];
			$nonreturncust_arr_10[$ind10]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_10[$ind10]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_10[$ind10]['updated_date'] = date('Y-m-d h:i A', strtotime($customer['updated_date']));
			
			$ind10++;

			}
			
			// 15 days or more inactive
			if($min_diff >= 21600){

			$nonreturncust_arr_15[$ind15]['id'] = $customer['id'];
			$nonreturncust_arr_15[$ind15]['name'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr_15[$ind15]['email'] = $customer['email'];
			$nonreturncust_arr_15[$ind15]['phone'] = $customer['contact_number'];
			$nonreturncust_arr_15[$ind15]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr_15[$ind15]['updated_date'] = date('Y-m-d h:i A', strtotime($customer['updated_date']));
			
			$ind15++;

			}
		    
            
                }

			}



       $json = array(
			'result'=> $result,
			'response'=> $response,
			'nonreturncusts_5' => $nonreturncust_arr_5,
			'nonreturncusts_10' => $nonreturncust_arr_10,
			'nonreturncusts_15' => $nonreturncust_arr_15,
			'total_entries' => $total_entries,
	    'total_pages' => $total_pages
		);

		echo json_encode($json);
		die();

}


public function actionwashfraudcheck() {

if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

$all_washes = Yii::app()->db->createCommand()
                ->select('*')
                ->from('washing_requests')
                ->where("status >= 0 AND status <=3 AND is_flagged = 0", array())
                ->queryAll();

			if(count($all_washes)){

                foreach($all_washes as $ind=>$wash){
                 $is_flagged = 0;
                   $kartapiresult = $this->washingkart($wash['id'], API_KEY, 0, AES256CBC_API_PASS);
$kartdata = json_decode($kartapiresult);

$cust_detail = Customers::model()->findByPk($wash['customer_id']);

/* ------- first time cust check --------- */

 if(!$cust_detail->total_wash){
   $is_flagged = 1;
 }

/* ------- higher price check --------- */

 if($kartdata->net_price >= 60){
   $is_flagged = 1;
 }

 /* ------- higher tip check --------- */

 if($kartdata->tip_amount >= 20){
   $is_flagged = 1;
 }

  /* ------- strange email check --------- */

$cust_name_arr = explode(" ", $cust_detail->first_name." ".$cust_detail->last_name);
$good_email = 0;
foreach($cust_name_arr as $custnamechunk){
    $strpos = stripos($cust_detail->email, $custnamechunk);

    if ($strpos !== false) {
       $good_email = 1;
       break;
    }
}

if(!$good_email) $is_flagged = 1;

/* ------- credit card name check --------- */

    $good_card = 0;
  if($cust_detail->client_position == 'real') $Bresult = Yii::app()->braintree->getCustomerById_real($cust_detail->braintree_id);
else $Bresult = Yii::app()->braintree->getCustomerById($cust_detail->braintree_id);

if(count($Bresult->paymentMethods)){

                  foreach($Bresult->paymentMethods as $index=>$paymethod){
                     $payment_methods[$index]['title'] = get_class($paymethod);
                     if($paymethod->isDefault()){
                     $cust_name_arr = explode(" ", $cust_detail->first_name." ".$cust_detail->last_name);

                     foreach($cust_name_arr as $custnamechunk){
                        $strpos = stripos($paymethod->cardholderName, $custnamechunk);

                        if ($strpos !== false) {
                            $good_card = 1;
                            break;
                        }
                    }

                    }

                  }
                }
                else{
                  $good_card = 1;
                }


if(!$good_card) $is_flagged = 1;

/* ------- last order date and address check --------- */

  $last_wash = Washingrequests::model()->findByAttributes(array('customer_id'=>$wash['customer_id'], 'status' => 4),array('order'=>'id DESC'));
  if(count($last_wash)) {
    $current_time = strtotime(date('Y-m-d H:i:s'));
	$create_time = strtotime($last_wash->order_for);
    $min_diff = 0;
    if($current_time > $create_time){
        $min_diff = round(($current_time - $create_time) / 60,2);
    }

    if($min_diff < 10080) $is_flagged = 1;

    if($wash['address'] != $last_wash->address) $is_flagged = 1;
  }


 if($is_flagged){
     //echo $wash['id']." ".$is_flagged."<br>";
     Washingrequests::model()->updateByPk($wash['id'], array("is_flagged" => 1));
 }
                }

			}


}


public function actionpassfraud(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');

           $result  = 'false';
$response = 'Enter wash request id';

$admin_username = '';
$admin_username  = Yii::app()->request->getParam('admin_username');

            $json    = array();

if((isset($wash_request_id) && !empty($wash_request_id))){

    $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));

			if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }
            else{
                $result = 'true';
$response = 'wash request unflagged';

                  Washingrequests::model()->updateByPk($wash_request_id, array("is_flagged" => 2));
				 $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'passfraud',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

            }



}


$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


    }


    public function actionadminaddschedulenotify(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$msg = Yii::app()->request->getParam('msg');
		$receiver_type = Yii::app()->request->getParam('receiver_type');
        $receiver_ids = Yii::app()->request->getParam('receiver_ids');
        $receiver_str = '';
	if($receiver_type == 'all-clients') $receiver_str = 'clients';
	if($receiver_type == 'all-agents') $receiver_str = 'agents';

		if((isset($msg) && !empty($msg)) && (isset($receiver_type) && !empty($receiver_type))){

		$pending_job_check =  Yii::app()->db->createCommand("SELECT * FROM `scheduled_notifications` WHERE status = 0 AND notification_type = '".$receiver_str."'")->queryAll();
		
		if(count($pending_job_check)){
		$json = array(
				'result'=> 'false',
				'response'=> 'There is already a notification delivery pending'
			);
		echo json_encode($json);
		die();
		}
		
            $data = array(
                        'notification_type'=> $receiver_str,
                        'receiver_ids' => '',
                        'notification_msg' => $msg,
                        'schedule_date' => date('Y-m-d H:i:s'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'status'=> 0);

                    Yii::app()->db->createCommand()->insert('scheduled_notifications', $data);
		    
		    if($receiver_type == 'all-clients') Customers::model()->updateAll(array('is_pushmsg_pending'=>1));
		    if($receiver_type == 'all-agents') Agents::model()->updateAll(array('is_pushmsg_pending'=>1));

        	$json = array(
				'result'=> 'true',
				'response'=> 'schedule notification added'
			);

		}else{
			$json = array(
				'result'=> 'false',
				'response'=> 'Pass the required parameters'
			);
		}
		echo json_encode($json);
		die();
	}

    public function actioncreateallorderswashpricinghistory(){

        if(Yii::app()->request->getParam('key') != API_KEY){
            echo "Invalid api key";
            die();
        }
        $result= 'false';
        $response = 'no washes found';
        $all_washes = Washingrequests::model()->findAll();

        if(count($all_washes)){
            foreach($all_washes as $wash){
                 $washingpricecheck = WashPricingHistory::model()->findByAttributes(array('wash_request_id'=>$wash->id));

                 if(!count($washingpricecheck)){
                    $kartapiresult = $this->washingkart($wash->id, API_KEY, 0, AES256CBC_API_PASS);
                    $kartdata = json_decode($kartapiresult);

                    foreach($kartdata->vehicles as $ind=>$car)
                    {
                        /* --------- car pricing save --------- */

                        $washpricehistorymodel = new WashPricingHistory;
                        $washpricehistorymodel->wash_request_id = $wash->id;
                        $washpricehistorymodel->vehicle_id = $car->id;
                        $washpricehistorymodel->package = $car->vehicle_washing_package;
                        $washpricehistorymodel->vehicle_price = $car->vehicle_washing_price;
                        $washpricehistorymodel->pet_hair = $car->pet_hair_fee;
                        $washpricehistorymodel->lifted_vehicle = $car->lifted_vehicle_fee;
                        $washpricehistorymodel->exthandwax_addon = $car->exthandwax_vehicle_fee;
                        $washpricehistorymodel->extplasticdressing_addon = $car->extplasticdressing_vehicle_fee;
                        $washpricehistorymodel->extclaybar_addon = $car->extclaybar_vehicle_fee;
                        $washpricehistorymodel->waterspotremove_addon = $car->waterspotremove_vehicle_fee;
                        $washpricehistorymodel->upholstery_addon = $car->upholstery_vehicle_fee;
                        $washpricehistorymodel->floormat_addon = $car->floormat_vehicle_fee;
                        $washpricehistorymodel->safe_handling = $car->safe_handling_fee;
                        $washpricehistorymodel->bundle_disc = $car->bundle_discount;
                        $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                        $washpricehistorymodel->save(false);

                        /* --------- car pricing save end --------- */


                }
            }
            }

            $result= 'true';
        $response = 'washes found';
        }
       $json = array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);
		die();

    }

     public function actioncreatesingleorderpricinghistory(){

        if(Yii::app()->request->getParam('key') != API_KEY){
            echo "Invalid api key";
            die();
        }

        $result= 'false';
        $response = 'pass wash id';
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $wash_check = Washingrequests::model()->findByPk($wash_request_id);

        if(count($wash_check)){
                 $washingpricecheck = WashPricingHistory::model()->findByAttributes(array('wash_request_id'=>$wash_request_id));

                 if(!count($washingpricecheck)){
                    $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS);
                    $kartdata = json_decode($kartapiresult);

                    foreach($kartdata->vehicles as $ind=>$car)
                    {
                        /* --------- car pricing save --------- */

                        $washpricehistorymodel = new WashPricingHistory;
                        $washpricehistorymodel->wash_request_id = $wash_request_id;
                        $washpricehistorymodel->vehicle_id = $car->id;
                        $washpricehistorymodel->package = $car->vehicle_washing_package;
                        $washpricehistorymodel->vehicle_price = $car->vehicle_washing_price;
                        $washpricehistorymodel->pet_hair = $car->pet_hair_fee;
                        $washpricehistorymodel->lifted_vehicle = $car->lifted_vehicle_fee;
                        $washpricehistorymodel->exthandwax_addon = $car->exthandwax_vehicle_fee;
                        $washpricehistorymodel->extplasticdressing_addon = $car->extplasticdressing_vehicle_fee;
                        $washpricehistorymodel->extclaybar_addon = $car->extclaybar_vehicle_fee;
                        $washpricehistorymodel->waterspotremove_addon = $car->waterspotremove_vehicle_fee;
                        $washpricehistorymodel->upholstery_addon = $car->upholstery_vehicle_fee;
                        $washpricehistorymodel->floormat_addon = $car->floormat_vehicle_fee;
                        $washpricehistorymodel->safe_handling = $car->safe_handling_fee;
                        $washpricehistorymodel->bundle_disc = $car->bundle_discount;
                        $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                        $washpricehistorymodel->save(false);

                        /* --------- car pricing save end --------- */


                }

                 $result= 'true';
        $response = '#'.$wash_request_id.' wash price created';
            }
            else{
              $response = '#'.$wash_request_id.' wash price already exists';
            }

        }
        else{
          $response = '#'.$wash_request_id.' wash not exists';
        }
       $json = array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);
		die();

    }

    		public function actionallwashes()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'pass required parameters';

			  $all_washes =  Yii::app()->db->createCommand()
						->select('id')
						->from('washing_requests')
						->queryAll();

if(count($all_washes) > 0){
    $result= 'true';
			$response= 'all washes';

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_washes' => $all_washes
		);
		echo json_encode($json);
	}

    		public function actionadminsmscustpass()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$result= 'false';
	$response= 'pass required parameters';

$customer_id = Yii::app()->request->getParam('customer_id');
$customer_email = Yii::app()->request->getParam('customer_email');
$customer_password = Yii::app()->request->getParam('customer_password');
 $cust_check = Customers::model()->findByPk($customer_id);

        if(!count($cust_check)){
          $result= 'false';
			$response= "Customer not found";
        }

        else if(!$cust_check->contact_number){
          $result= 'false';
			$response= "Customer phone doesn't exist";
        }

        else{

   Customers::model()->updateByPk($customer_id, array("password" => md5($customer_password)));

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


            $message = "Greetings from MobileWash\r\nYour login is: ".$customer_email."\r\nYour new password is: ".$customer_password;


  $sendmessage = $client->account->messages->create(array(
                'To' =>  $cust_check->contact_number,
                'From' => '+13103128070',
                'Body' => $message,
            ));
spl_autoload_register(array('YiiBase','autoload'));
  $result= 'true';
			$response= "SMS sent";

        }



		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}


    		public function actionadminpreviewcustpasssms()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$result= 'false';
	$response= 'pass required parameters';

$customer_id = Yii::app()->request->getParam('customer_id');
        $cust_check = Customers::model()->findByPk($customer_id);

        if(!count($cust_check)){
          $result= 'false';
			$response= "Customer not found";
        }

        else{


           			  $word_list =  Yii::app()->db->createCommand()
						->select('*')
						->from('word_list')
						->queryAll();

if(count($word_list) > 0){
   $all_words = explode(" ", $word_list[0]['words']);
$rand_key = array_rand($all_words);
$pass_phrase =  trim($all_words[$rand_key]);
$digits = 2;
$pass_digits = rand(pow(10, $digits-1), pow(10, $digits)-1);
$final_pass = $pass_phrase.$pass_digits;


            $response = "Greetings from MobileWash<br>Your login is: ".$cust_check->email."<br>Your new password is: ".$final_pass;
  $result= 'true';
}
else{
    $result= 'false';
			$response= "Error";
}
        }



		$json= array(
			'result'=> $result,
			'response'=> $response,
            'customer_email' => $cust_check->email,
            'customer_password' => $final_pass
		);
		echo json_encode($json);
	}

    public function actionadminuncancel(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');

           $result  = 'false';
$response = 'Enter wash request id';

$admin_username = '';
$admin_username  = Yii::app()->request->getParam('admin_username');

            $json    = array();

if((isset($wash_request_id) && !empty($wash_request_id))){

    $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));

			if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }
            else{
                $result = 'true';
$response = 'wash request uncanceled';

                  Washingrequests::model()->updateByPk($wash_request_id, array("status" => 0, "washer_late_cancel" => 0, "no_washer_cancel" => 0, "company_cancel" => 0, "cancel_fee" => 0, "washer_cancel_fee" => 0));
				 $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'uncancel',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

            }



}


$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


    }

     public function actioncccustomerpushnotify() {

 if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$customer_id  = Yii::app()->request->getParam('customer_id');
    $message  = Yii::app()->request->getParam('message');
    $cust_id_check = Customers::model()->findByPk($customer_id);
      $result = 'false';
      $response = 'Error in sending notification';
        if(count($cust_id_check)){

                         $customerdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1")->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)->queryAll();

						foreach($customerdevices as $ctdevice){

						    $device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
							$alert_type = "default";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}


                    $result = 'true';
                    $response = 'Notification sent';

       }

       $json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


   }


        public function actioncccagentpushnotify() {

 if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$agent_id  = Yii::app()->request->getParam('agent_id');
    $message  = Yii::app()->request->getParam('message');
    $agent_id_check = Agents::model()->findByPk($agent_id);
      $result = 'false';
      $response = 'Error in sending notification';
        if((count($agent_id_check)) && (!$agent_id_check->block_washer)){

                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1")->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)->queryAll();
                        foreach($agentdevices as $agdevice){

						    $device_type = strtolower($agdevice['device_type']);
							$notify_token = $agdevice['device_token'];
							$alert_type = "default";
							$notify_msg = urlencode($message);

							$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}


                    $result = 'true';
                    $response = 'Notification sent';

       }

       $json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


   }

        public function actioncccustomersendsms() {

 if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$customer_id  = Yii::app()->request->getParam('customer_id');
    $message  = Yii::app()->request->getParam('message');
    $cust_id_check = Customers::model()->findByPk($customer_id);
      $result = 'false';
      $response = 'Error in sending SMS';
        if(count($cust_id_check)){

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


  $sendmessage = $client->account->messages->create(array(
                'To' =>  $cust_id_check->contact_number,
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));


                    $result = 'true';
                    $response = 'SMS sent';

       }

       $json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


   }

   public function actionccagentsendsms() {

 if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$agent_id  = Yii::app()->request->getParam('agent_id');
    $message  = Yii::app()->request->getParam('message');
    $agent_id_check = Agents::model()->findByPk($agent_id);
      $result = 'false';
      $response = 'Error in sending SMS';
        if((count($agent_id_check)) && (!$agent_id_check->block_washer) && ($agent_id_check->sms_control)){

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


  $sendmessage = $client->account->messages->create(array(
                'To' =>  $agent_id_check->phone_number,
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));


                    $result = 'true';
                    $response = 'SMS sent';

       }

       $json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


   }

   public function actionadminchangewasher(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
        $result  = 'true';
        $response = 'Washer updated';
        $admin_username = '';
        $admin_username  = Yii::app()->request->getParam('admin_username');

        $json = array();

        if((isset($wash_request_id) && !empty($wash_request_id))){

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));
            $customer_check = Customers::model()->findByPk($wrequest_id_check->customer_id);
            $agent_check = Agents::model()->findByPk($agent_id);

			if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';

            }

            else if(!$agent_id){
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array('agent_id' => $agent_id, 'washer_payment_status' => 0, 'status' => 0, 'is_create_schedulewash_push_sent' => 0, 'washer_on_way_push_sent' => 0, 'order_temp_assigned' => 0));
                     
		     if(($wrequest_id_check->agent_id != $agent_id)) {
			$old_agent_id = $wrequest_id_check->agent_id;
			if($old_agent_id){
				$old_agent_detail = Agents::model()->findByPk($old_agent_id);
				 $washeractionlogdata = array(
				'agent_id'=> $old_agent_id,
				'wash_request_id'=> $wash_request_id,
				'agent_company_id'=> $old_agent_detail->real_washer_id,
				'admin_username' => $admin_username,
				'action'=> 'admindropjob',
				'action_date'=> date('Y-m-d H:i:s'));

				Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
		       }
		       
		       if(($wrequest_id_check->is_scheduled) && (!$wrequest_id_check->status)){
		      $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$old_agent_id."' ORDER BY last_used DESC LIMIT 1")->queryAll();

							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '46' ")->queryAll();
							//$message = $pushmsg[0]['message'];
							$message = str_replace("[ORDER_ID]","#".$wrequest_id_check->id, $pushmsg[0]['message']);
							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "strong";
								$notify_msg = urlencode($message);

								$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}
		       }
		     }
		       
		    $json= array(
				        'result'=> 'true',
				        'response'=> 'Washer updated'
		    	    );
		            echo json_encode($json);
                    die();
            }

            else if(!count($agent_check)){
                $result= 'false';
                $response= 'Invalid agent id';

            }

            else if(!$agent_check->bt_submerchant_id){
                $result= 'false';
                $response= 'Agent braintree ID not found';

            }

            else if(!count($customer_check)){
                $result = 'false';
                $response= 'Invalid customer id';

            }

            else if(!$customer_check->braintree_id){
                $result= 'false';
                $response= 'Customer braintree ID not found';

            }

            else{

                $token = '';
                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS);
                $kartdetails = json_decode($kartapiresult);

                if($customer_check->client_position == 'real') $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);
                else $Bresult = Yii::app()->braintree->getCustomerById($customer_check->braintree_id);

                if(count($Bresult->paymentMethods)){
                    foreach($Bresult->paymentMethods as $index=>$paymethod){
                        $payment_methods[$index]['title'] = get_class($paymethod);
                        if($payment_methods[$index]['title'] == 'Braintree\\CreditCard'){
                            if($paymethod->isDefault()){
                                $token = $paymethod->token;
                                break;
                            }
                        }


                    }
                }

                if(!$token) {
                    $result = 'false';
                    $response = 'Customer payment method not found';

                }
                else{

                    if($wrequest_id_check->transaction_id && ($wrequest_id_check->agent_id != $agent_id) && ($wrequest_id_check->status == 4)){
                        if($customer_check->client_position == 'real') $voidresult = Yii::app()->braintree->void_real($wrequest_id_check->transaction_id);
                        else $voidresult = Yii::app()->braintree->void($wrequest_id_check->transaction_id);

                        if($voidresult['success'] == 1) {
                            if($customer_check->client_position == 'real'){
                                $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wrequest_id_check->id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price,'paymentMethodToken' => $token];
                                $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);

                                if($payresult['success'] == 1) {
                                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array('transaction_id' => $payresult['transaction_id'], 'failed_transaction_id'=>'', 'washer_payment_status' => 0));

                                }
                                else{
                                    $result = 'false';
                                    $response = $payresult['message'];

                                }
                            }
                            else{
                                $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wrequest_id_check->id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price,'paymentMethodToken' => $token];
                                $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);

                                if($payresult['success'] == 1) {
                                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array('transaction_id' => $payresult['transaction_id'], 'failed_transaction_id'=>'', 'washer_payment_status' => 0));

                                }
                                else{
                                    $result = 'false';
                                    $response = $payresult['message'];

                                }
                            }

                        }
                        else{
                            $result = 'false';
                            $response = $voidresult['message'];

                        }
                    }

                    if((!$wrequest_id_check->transaction_id) && ($wrequest_id_check->agent_id != $agent_id) && ($wrequest_id_check->status == 4)){
                        if($customer_check->client_position == 'real'){
                            $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wrequest_id_check->id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price,'paymentMethodToken' => $token];
                            $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);

                            if($payresult['success'] == 1) {
                                Washingrequests::model()->updateByPk($wrequest_id_check->id, array('transaction_id' => $payresult['transaction_id'], 'failed_transaction_id'=>'', 'washer_payment_status' => 0));

                            }
                            else{
                                $result = 'false';
                                $response = $payresult['message'];

                            }
                        }
                        else{
                            $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'orderId' => $wrequest_id_check->id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price,'paymentMethodToken' => $token];
                            $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);

                            if($payresult['success'] == 1) {
                                Washingrequests::model()->updateByPk($wrequest_id_check->id, array('transaction_id' => $payresult['transaction_id'], 'failed_transaction_id'=>'', 'washer_payment_status' => 0));

                            }
                            else{
                                $result = 'false';
                                $response = $payresult['message'];

                            }
                        }



                }

                if(($wrequest_id_check->agent_id != $agent_id) && ($result != 'false')) {
                    $old_agent_id = $wrequest_id_check->agent_id;
		    if((!$wrequest_id_check->is_scheduled)) {
			if(($agent_id)) Washingrequests::model()->updateByPk($wash_request_id, array("agent_id" => $agent_id, 'washer_payment_status' => 0, 'status' => 1));
			else Washingrequests::model()->updateByPk($wash_request_id, array("agent_id" => $agent_id, 'washer_payment_status' => 0, 'status' => 0, 'order_temp_assigned' => 0));
		    }
		else Washingrequests::model()->updateByPk($wash_request_id, array("agent_id" => $agent_id, 'washer_payment_status' => 0));

                    $result = 'true';
                    $response = "Washer updated successfully";
		    
		       if($old_agent_id){
			$old_agent_detail = Agents::model()->findByPk($old_agent_id);
			 $washeractionlogdata = array(
                        'agent_id'=> $old_agent_id,
                        'wash_request_id'=> $wash_request_id,
                        'agent_company_id'=> $old_agent_detail->real_washer_id,
                        'admin_username' => $admin_username,
                        'action'=> 'admindropjob',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
		    
		    if(($wrequest_id_check->is_scheduled) && (!$wrequest_id_check->status)){
		      $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$old_agent_id."' ORDER BY last_used DESC LIMIT 1")->queryAll();

							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '46' ")->queryAll();
							//$message = $pushmsg[0]['message'];
							$message = str_replace("[ORDER_ID]","#".$wrequest_id_check->id, $pushmsg[0]['message']);
							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "strong";
								$notify_msg = urlencode($message);

								$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}
		       }
		       }
		      

			if($agent_id){        
				$washeractionlogdata = array(
				'agent_id'=> $agent_id,
				'wash_request_id'=> $wash_request_id,
				'agent_company_id'=> $agent_check->real_washer_id,
				'admin_username' => $admin_username,
				'action'=> 'savejob',
				'action_date'=> date('Y-m-d H:i:s'));

				Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
				
				if(($wrequest_id_check->is_scheduled) && (!$wrequest_id_check->status)){
		      $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent_id."' ORDER BY last_used DESC LIMIT 1")->queryAll();

							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '47' ")->queryAll();
							//$message = $pushmsg[0]['message'];
							$message = str_replace("[ORDER_ID]","#".$wrequest_id_check->id, $pushmsg[0]['message']);
							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "strong";
								$notify_msg = urlencode($message);

								$notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								//file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}
		       }
		       
$mobile_receipt = '';
$kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS);
$kartdata = json_decode($kartapiresult);

foreach($kartdata->vehicles as $ind=>$vehicle){
$mobile_receipt .= $vehicle->brand_name." ".$vehicle->model_name."\r\n".$vehicle->vehicle_washing_package." $".$vehicle->vehicle_washing_price."\r\nHandling $1.00\r\n";

     if($vehicle->surge_vehicle_fee > 0){
$mobile_receipt .= "Surge $".$vehicle->surge_vehicle_fee."\r\n";
}
if($vehicle->extclaybar_vehicle_fee > 0){

$mobile_receipt .= "Clay $".$vehicle->extclaybar_vehicle_fee."\r\n";
}
if($vehicle->waterspotremove_vehicle_fee > 0){

$mobile_receipt .= "Spot $".$vehicle->waterspotremove_vehicle_fee."\r\n";
}
if($vehicle->exthandwax_vehicle_fee > 0){

$mobile_receipt .= "Wax $".$vehicle->exthandwax_vehicle_fee."\r\n";
}

if($vehicle->pet_hair_fee > 0){

$mobile_receipt .= "Extra Cleaning $".$vehicle->pet_hair_fee."\r\n";
}
if($vehicle->lifted_vehicle_fee > 0){

$mobile_receipt .= "Lifted $".$vehicle->lifted_vehicle_fee."\r\n";
}

if($vehicle->extplasticdressing_vehicle_fee > 0){

$mobile_receipt .= "Dressing $".$vehicle->extplasticdressing_vehicle_fee."\r\n";
}

if($vehicle->upholstery_vehicle_fee > 0){

$mobile_receipt .= "Upholstery $".$vehicle->upholstery_vehicle_fee."\r\n";
}

if($vehicle->floormat_vehicle_fee > 0){

$mobile_receipt .= "Floormat $".$vehicle->floormat_vehicle_fee."\r\n";
}

if(($ind == 0) && ($kartdata->coupon_discount > 0)){

$mobile_receipt .= "Promo: ".$kartdata->coupon_code." -$".number_format($kartdata->coupon_discount, 2)."\r\n";
}


if($vehicle->fifth_wash_discount > 0){

$mobile_receipt .= "5th -$".number_format($vehicle->fifth_wash_discount, 2)."\r\n";
}

if(($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)){

$mobile_receipt .= "Bundle -$1.00\r\n";
}

if(($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)){

$mobile_receipt .= "Bundle -$1.00\r\n";
}
 $mobile_receipt .= "------\r\n";	
}

if($kartdata->tip_amount > 0){
	$mobile_receipt .= "Tip $".number_format($kartdata->tip_amount, 2)."\r\n";
}

if($kartdata->wash_now_fee > 0){
	$mobile_receipt .= "Wash Now $".number_format($kartdata->wash_now_fee, 2)."\r\n";
}

if($kartdata->wash_later_fee > 0){
	$mobile_receipt .= "Surge Fee $".number_format($kartdata->wash_later_fee, 2)."\r\n";
}


                     $mobile_receipt .= "Total: $".$kartdata->net_price."\r\n";
		       
		       //if(APP_ENV == 'real'){
                    $this->layout = "xmlLayout";
                    
		require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

            $account_sid = TWILIO_SID;
            $auth_token = TWILIO_AUTH_TOKEN;
            $client = new Services_Twilio($account_sid, $auth_token);
	    
	     if($wrequest_id_check->is_scheduled) $message = "SCHEDULED WASH TAKEN ";
	     else $message = "WASH NOW TAKEN ";
	     
	     $message .= "#000".$wrequest_id_check->id."- ".date('M d', strtotime($wrequest_id_check->created_date))." @ ".date('h:i A', strtotime($wrequest_id_check->created_date))."\r\n".$customer_check->first_name." ".$customer_check->last_name."\r\n".$customer_check->contact_number."\r\n".$wrequest_id_check->address." (".$wrequest_id_check->address_type.")\r\nWasher Name: ".$agent_check->first_name." ".$agent_check->last_name." (".$admin_username.")\r\nWasher Badge #".$agent_check->real_washer_id."\r\n------\r\n".$mobile_receipt;


try {
$sendmessage = $client->account->messages->create(array(
                'To' =>  '8183313631',
                'From' => '+13103128070',
                'Body' => $message,
            ));
 }catch (Services_Twilio_RestException $e) {
            //echo  $e;
}

try {
$sendmessage = $client->account->messages->create(array(
                'To' =>  '3109999334',
                'From' => '+13103128070',
                'Body' => $message,
            ));

	     }catch (Services_Twilio_RestException $e) {
            //echo  $e;
}

//}
		       
			}
                }

            }

        }



}
else{
    $result = 'false';
    $response = "Enter wash request ID";
}


$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);


    }
    
    
    /*public function actiongetallcustphones() {

 if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}


	$allcusts = Customers::model()->findAll();

						foreach($allcusts as $cust){
							$newphone = '';
							$newphone = preg_replace('/\D/', '', $cust->contact_number);

							echo $cust->id." ".$cust->contact_number." ".$newphone."<br>";
							
							Customers::model()->updateByPk($cust->id, array("contact_number" => $newphone));
						}



   }*/
    
    		public function actionmanagepayments()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'nothing found';
			$all_washes_arr = array();

			  $all_washes =  Yii::app()->db->createCommand()
						->select('*')
						->from('washing_requests')
						->where('order_for BETWEEN NOW() - INTERVAL 30 DAY AND NOW()')
						->queryAll();

if(count($all_washes) > 0){
    $result= 'true';
			$response= 'all washes';
			
			foreach($all_washes as $ind => $wash){
				$kartapiresult = $this->washingkart($wash['id'], API_KEY, 0, AES256CBC_API_PASS);
				$kartdata = json_decode($kartapiresult);
				$all_washes_arr[$ind]['id'] = $wash['id'];
				$all_washes_arr[$ind]['transaction_id'] = $wash['transaction_id'];
				$all_washes_arr[$ind]['order_total'] = $kartdata->net_price;
				$all_washes_arr[$ind]['agent_total'] = $kartdata->agent_total;
				$all_washes_arr[$ind]['company_total'] = $kartdata->company_total;
				
				if($wash['agent_id']){
					$agent_detail = Agents::model()->findByPk($wash['agent_id']);
					$all_washes_arr[$ind]['real_washer_id'] = $agent_detail->real_washer_id;
					$all_washes_arr[$ind]['washer_merchant_id'] = $agent_detail->bt_submerchant_id;
				}
				else{
					$all_washes_arr[$ind]['real_washer_id'] = "";
					$all_washes_arr[$ind]['washer_merchant_id'] = "";	
				}
			}

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_washes' => $all_washes_arr
		);
		echo json_encode($json);
	}
	
	
	    		public function actiongettopmostwashers()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$from  = Yii::app()->request->getParam('from');
$to  = Yii::app()->request->getParam('to');

			$result= 'false';
			$response= 'nothing found';
			$washer_ids = array();
			$topwashers_arr = array();
			$topwashers_det_arr = array();
			$all_washes =  Yii::app()->db->createCommand()
						->select('agent_id')
						->from('washing_requests')
						->where("(order_for >= :from AND order_for <= :to) AND status = 4 AND agent_id != 0", array(":from" => $from, ":to" => $to))
						->queryAll();

if(count($all_washes) > 0){
    $result= 'true';
			$response= 'topmost washers';
			

foreach($all_washes as $wash){
$washer_ids[] = $wash['agent_id'];	
}

$topwashers_arr = array_count_values($washer_ids);
arsort($topwashers_arr);
$i = 0;
foreach($topwashers_arr as $key=>$washer){
	$agent_det = Agents::model()->findByPk($key);
	$topwashers_det_arr[$i]['id'] = $key;
	$topwashers_det_arr[$i]['company_id'] = $agent_det->real_washer_id;
	$topwashers_det_arr[$i]['name'] = $agent_det->first_name." ".$agent_det->last_name;
	$topwashers_det_arr[$i]['total_washes'] = $washer;
    $topwashers_det_arr[$i]['street'] = $agent_det->street_address;
    $topwashers_det_arr[$i]['city'] = $agent_det->city;
    $topwashers_det_arr[$i]['state'] = $agent_det->state;
    $topwashers_det_arr[$i]['zip'] = $agent_det->zipcode;
$i++;	
}

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'top_washers' => $topwashers_det_arr
		);
		echo json_encode($json);
	}
	
	
		    		public function actiongettopmostcustomers()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$from  = Yii::app()->request->getParam('from');
$to  = Yii::app()->request->getParam('to');

			$result= 'false';
			$response= 'nothing found';
			$cust_ids = array();
			$topcusts_arr = array();
			$topcusts_det_arr = array();
			$all_washes =  Yii::app()->db->createCommand()
						->select('customer_id')
						->from('washing_requests')
						->where("(order_for >= :from AND order_for <= :to) AND status = 4 AND agent_id != 0", array(":from" => $from, ":to" => $to))
						->queryAll();

if(count($all_washes) > 0){
    $result= 'true';
			$response= 'topmost customers';
			

foreach($all_washes as $wash){
$cust_ids[] = $wash['customer_id'];	
}

$topcusts_arr = array_count_values($cust_ids);
arsort($topcusts_arr);
$i = 0;
foreach($topcusts_arr as $key=>$cust){
	$cust_det = Customers::model()->findByPk($key);
	if(((!$cust_det->first_name) && (!$cust_det->last_name)) && (!$cust_det->customername)) continue;
    $location = CustomerLocation::model()->findByPk($key);
	$topcusts_det_arr[$i]['id'] = $key;
	$topcusts_det_arr[$i]['name'] = $cust_det->first_name." ".$cust_det->last_name;
	$topcusts_det_arr[$i]['email'] = $cust_det->email;
	$topcusts_det_arr[$i]['phone'] = $cust_det->contact_number;
    $topcusts_det_arr[$i]['address'] = $location->location_address;
    //$topcusts_det_arr[$i]['street'] = $location->street_name;
    $topcusts_det_arr[$i]['city'] = $location->city;
    $topcusts_det_arr[$i]['state'] = $location->state;
    $topcusts_det_arr[$i]['zip'] = $location->zipcode;
	$topcusts_det_arr[$i]['total_washes'] = $cust;
$i++;	
}

}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'top_customers' => $topcusts_det_arr
		);
		echo json_encode($json);
	}
	
	
	
    public function actionupdatedevicestatus(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $user_type = Yii::app()->request->getParam('user_type');
	$user_id = Yii::app()->request->getParam('user_id');
	$device_token = Yii::app()->request->getParam('device_token');
	$pending_wash_id = '';
	$is_scheduled = '';
	$wash_status = '';
	$agent_wash_details = array();
	
$user_photo = '';
$user_name = '';
$user_rating = '';
           $result  = 'false';
$response = 'pass the required fields';


if((isset($user_type) && !empty($user_type)) && (isset($user_id) && !empty($user_id)) && (isset($device_token) && !empty($device_token))){
  if(AES256CBC_STATUS == 1){
$user_id = $this->aes256cbc_crypt( $user_id, 'd', AES256CBC_API_PASS );
}

if($user_type == 'customer') $user_type = 'customer';
elseif ($user_type == 'agent') $user_type = 'agent';
else $user_type = 'agent';

    $device_check = Yii::app()->db->createCommand("SELECT * FROM ".$user_type."_devices WHERE device_token = :device_token AND ".$user_type."_id = :user_id")
    ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
    ->bindValue(':user_id', $user_id, PDO::PARAM_STR)
    ->queryAll();
    
    if($user_type == 'customer') $user_check = Customers::model()->findByPk($user_id);
	if($user_type == 'agent') $user_check = Agents::model()->findByPk($user_id);

   
	if(!count($user_check)){
                $result= 'false';
                $response= 'No user found';
        }

	else if(!count($device_check)){
                $result= 'false';
                $response= 'No device found';
            }
            else{
		
		
                $result = 'true';
$response = 'device updated';

                 Yii::app()->db->createCommand("UPDATE ".$user_type."_devices SET device_status='online', last_used='".date("Y-m-d H:i:s")."' WHERE device_token = :device_token AND ".$user_type."_id = :user_id")
    ->bindValue(':device_token', $device_token, PDO::PARAM_STR)
    ->bindValue(':user_id', $user_id, PDO::PARAM_STR)
		 ->execute();

            
		if($user_type == 'customer'){
			if($user_check->forced_logout == 1) $response = 'offline';
			else $response = 'online';
			
			$user_photo = $user_check->image;
			$user_name = $user_check->first_name." ".$user_check->last_name;
			$user_rating = $user_check->rating;
			$pending_wash_id_check =  Washingrequests::model()->findAll(array("condition"=>"status <= 3 AND customer_id=:customer_id", 'params'  => array(':customer_id' => $user_id), 'order' => 'created_date desc'));
			if(count($pending_wash_id_check)){
				$pending_wash_id = $pending_wash_id_check[0]->id;
				$is_scheduled = $pending_wash_id_check[0]->is_scheduled;
				$wash_status = $pending_wash_id_check[0]->status;
			}
		}
		
		if($user_type == 'agent'){
			if($user_check->forced_logout == 1) $response = 'offline';
			else $response = 'online';
			
			$user_photo = $user_check->image;
			$user_name = $user_check->first_name." ".$user_check->last_name;
			$user_rating = $user_check->rating;
			$is_agent_has_wash = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id='".$user_check->id."' AND (status >= 1 AND status <= 3)")->queryAll();
			
			if(count($is_agent_has_wash)){
				$cust_detail = Customers::model()->findByPk($is_agent_has_wash[0]['customer_id']);
			if(AES256CBC_STATUS == 1){
			$agent_wash_details['wash_id'] = $this->aes256cbc_crypt( $is_agent_has_wash[0]['id'], 'e', AES256CBC_API_PASS );
			$agent_wash_details['customer_id'] = $this->aes256cbc_crypt( $is_agent_has_wash[0]['customer_id'], 'e', AES256CBC_API_PASS );	
			}
			else{
			$agent_wash_details['wash_id'] = $is_agent_has_wash[0]['id'];
			$agent_wash_details['customer_id'] = $is_agent_has_wash[0]['customer_id'];	
			}
			
			$agent_wash_details['customer_name'] = $cust_detail->first_name." ".$cust_detail->last_name;
			$agent_wash_details['customer_phoneno'] = $cust_detail->contact_number;
			$agent_wash_details['customer_rating'] = $cust_detail->rating;
			$agent_wash_details['address'] = $is_agent_has_wash[0]['address'];
			$agent_wash_details['latitude'] = $is_agent_has_wash[0]['latitude'];
			$agent_wash_details['longitude'] = $is_agent_has_wash[0]['longitude'];
			$agent_wash_details['wash_status'] = $is_agent_has_wash[0]['status'];
			$agent_wash_details['is_scheduled'] = $is_agent_has_wash[0]['is_scheduled'];
			}
			
		/*if($user_check->status != 'online'){
			$isagentbusy = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id='".$user_check->id."' AND (status >= 1 AND status <= 3)")->queryAll();
			if(!count($isagentbusy)){
				Agents::model()->updateAll(array('available_for_new_order' => 1, 'status' => 'online'),'id=:id',array(':id'=>$user_check->id));
			}	
		}*/
		
		}
	    
	    }



}

  if(AES256CBC_STATUS == 1){
$user_id = $this->aes256cbc_crypt( $user_id, 'e', AES256CBC_API_PASS );
$pending_wash_id = $this->aes256cbc_crypt( $pending_wash_id, 'e', AES256CBC_API_PASS );
}

$json= array(
				'result'=> $result,
				'response'=> $response,
				'user_id'=> $user_id,
				'user_type'=> $user_type,
				'user_photo' => $user_photo,
				'user_name' => $user_name,
				'user_rating' => $user_rating,
				'customer_pending_wash_id' => $pending_wash_id,
				'customer_pending_wash_is_scheduled' => $is_scheduled,
				'customer_pending_wash_status' => $wash_status,
				'agent_current_wash_details' => $agent_wash_details
				
			);
		echo json_encode($json);


    }
    
    
    public function actioncheckuseronlinedevices(){

if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

    $customer_online_devices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE device_status = 'online'")->queryAll();
    $agent_online_devices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE device_status = 'online'")->queryAll();

	if(count($customer_online_devices)){
		foreach($customer_online_devices as $custdevice){
			$current_time = strtotime(date('Y-m-d H:i:s'));
			$last_used_time = strtotime($custdevice['last_used']);
			$min_diff = 0;
			if($current_time > $last_used_time){
				$min_diff = round(($current_time - $last_used_time) / 60,2);
			}

			if($min_diff >= 1){
				Yii::app()->db->createCommand("UPDATE customer_devices SET device_status='offline' WHERE id = '".$custdevice['id']."'")->execute();
	
			}
		}
        
	}
	
	if(count($agent_online_devices)){
		
		foreach($agent_online_devices as $agdevice){
			$agent_detail = Agents::model()->findByPk($agdevice['agent_id']);
			$current_time = strtotime(date('Y-m-d H:i:s'));
			$last_used_time = strtotime($agdevice['last_used']);
			$min_diff = 0;
			if($current_time > $last_used_time){
				$min_diff = round(($current_time - $last_used_time) / 60,2);
			}
			
			if($min_diff >= .2){
				Yii::app()->db->createCommand("UPDATE agent_devices SET device_status='offline' WHERE id = '".$agdevice['id']."'")->execute();	
				if((count($agent_detail) > 0) && ($agent_detail->status == 'online') && ($agent_detail->available_for_new_order == 1)){
					Agents::model()->updateByPk($agent_detail->id, array('status' => 'offline', 'available_for_new_order' => 0));
					Washingrequests::model()->updateAll(array('order_temp_assigned' => 0), "order_temp_assigned = ".$agent_detail->id." AND status = 0");	
				}
				
			}
		}
		
        
	}
	            

	$json= array(
		'result'=> 'true',
		'response'=> 'done'
		);
	echo json_encode($json);


    }
    
    
        public function actionaddcustomlog(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$wash_request_id = Yii::app()->request->getParam('wash_request_id');
$agent_id = Yii::app()->request->getParam('agent_id');
$agent_company_id = Yii::app()->request->getParam('agent_company_id');
$admin_username = Yii::app()->request->getParam('admin_username');
$action = Yii::app()->request->getParam('action');
$addi_detail = Yii::app()->request->getParam('addi_detail');

  $logdata = array(

                        'wash_request_id'=> $wash_request_id,
                        'admin_username' => $admin_username,
                        'action'=> $action,
			'addi_detail'=> $addi_detail,
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
            

	$json= array(
		'result'=> 'true',
		'response'=> 'done'
		);
	echo json_encode($json);


    }
    
           public function actionupdatelog(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$wash_request_id = Yii::app()->request->getParam('wash_request_id');

$admin_username = Yii::app()->request->getParam('admin_username');


Yii::app()->db->createCommand("UPDATE `activity_logs` SET `admin_username`=:admin_username WHERE `wash_request_id` = :wash_request_id")
	    ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
	    ->bindValue(':admin_username', $admin_username, PDO::PARAM_STR)
	    ->execute();

	$json= array(
		'result'=> 'true',
		'response'=> 'done'
		);
	echo json_encode($json);


    }
    
    
        public function actionaddcoverageareacity()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $city = Yii::app()->request->getParam('city');
	$state = Yii::app()->request->getParam('state');
        $citypage_url = Yii::app()->request->getParam('citypage_url');
       
        if(!empty($city))
        {

            Yii::app()->db->createCommand("INSERT INTO `coverage_area_cities` (`city`, `state`, `citypage_url`) VALUES ('$city', '$state', '$citypage_url'); ")->execute();
        $result = 'true';
                $response = 'city added';
                $result = 'true';
               
               $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
            
        }
        else
        {

            $result = 'false';
            $response = 'Please enter city';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
        }

    }
    
    
         public function actioneditcoverageareacity()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $id = Yii::app()->request->getParam('id');
	$city = Yii::app()->request->getParam('city');
	$state = Yii::app()->request->getParam('state');
        $citypage_url = Yii::app()->request->getParam('citypage_url');
       
        if((!empty($city)) && (!empty($id)))
        {

            Yii::app()->db->createCommand("UPDATE `coverage_area_cities` SET `city`=:city,`state`=:state,`citypage_url`=:citypage_url WHERE `id` = :id")
	    ->bindValue(':id', $id, PDO::PARAM_STR)
	    ->bindValue(':city', $city, PDO::PARAM_STR)
	    ->bindValue(':state', $state, PDO::PARAM_STR)
	    ->bindValue(':citypage_url', $citypage_url, PDO::PARAM_STR)
	    ->execute();
        $result = 'true';
                $response = 'city updated';
                $result = 'true';
               
               $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
            
        }
        else
        {

            $result = 'false';
            $response = 'Please enter city and id';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
        }

    }
    
    
                public function actiongetcoverageareacitybyid()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $id = Yii::app()->request->getParam('id');
        
        if(!empty($id))
        {
		
		$city_exists = Yii::app()->db->createCommand("SELECT * FROM `coverage_area_cities` WHERE id = :id")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();
		if(!count($city_exists)){
			$result = 'false';
			$response = 'Invalid city id';
			 $json = array(
                'result'=> $result,
                'response'=> $response
            );
			 echo json_encode($json);
            die();
		}
		
			$result = 'true';
			$response = 'coverage city';
			$result = 'true';
               
			$json = array(
			'result'=> $result,
			'response'=> $response,
			'coverage_city' => $city_exists
			);
			
			echo json_encode($json);
			die();	
		
    
        }
        else
        {

            $result = 'false';
            $response = 'Please enter city id';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
        }

    }
    
    
            public function actiondeletecoverageareacity()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $id = Yii::app()->request->getParam('id');
        
        if(!empty($id))
        {
		
		$city_exists = Yii::app()->db->createCommand("SELECT * FROM `coverage_area_cities` WHERE id = :id")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();
		if(!count($city_exists)){
			$result = 'false';
			$response = 'Invalid city id';
			 $json = array(
                'result'=> $result,
                'response'=> $response
            );
			 echo json_encode($json);
            die();
		}
		
			Yii::app()->db->createCommand("DELETE FROM `coverage_area_cities` WHERE id = :id")->bindValue(':id', $id, PDO::PARAM_STR)->execute();
			$result = 'true';
			$response = 'city deleted';
			$result = 'true';
               
			$json = array(
			'result'=> $result,
			'response'=> $response
			);
			
			echo json_encode($json);
			die();	
		
    
        }
        else
        {

            $result = 'false';
            $response = 'Please enter city id';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );echo json_encode($json);
            die();
        }

    }
    
    
    public function actiongetallcoverageareacities()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
		$where = '';
		if(Yii::app()->request->getParam('county') != ""){
		    $countyName = Yii::app()->request->getParam('county');
            $where = "where county=:county";
        }
		$all_cities = Yii::app()->db->createCommand("SELECT * FROM `coverage_area_cities` $where ORDER BY city ASC")->bindValue(':county', $countyName, PDO::PARAM_STR)->queryAll();
		
			$result = 'true';
			$response = 'all cities';
			 $json = array(
                'result'=> $result,
                'response'=> $response,
		'all_cities' => $all_cities
            );
			 echo json_encode($json);
            die();
		
   
    }
    
     public function actionfixcusotmerphoneformat()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$offset = 0;
$offset = Yii::app()->request->getParam('offset');

		
		$all_cust = Yii::app()->db->createCommand("SELECT * FROM customers ORDER BY id ASC LIMIT 10 OFFSET ".$offset)->queryAll();
//echo count($all_cust);

if(count($all_cust)){
    foreach($all_cust as $cust){
        $new_phone = '';
        $new_phone = preg_replace('/\D/', '', $cust['contact_number']);
        echo "before: ".$cust['contact_number']." | after: ".$new_phone;
        echo "<br>";
        Customers::model()->updateByPk($cust['id'], array("contact_number" => $new_phone));
    }
}
else{
    echo "nothing found";
}

/*$all_phones = ["999-000-0999", "(787) 878 0999", "444 444 3333", " 999 - 990 -0999", "555 444 3333 ", "8989880099", "4444 44", "(999)898 8988", "(888)-999-9089", "(333) 898-9000"];

foreach($all_phones as $phone){
    $phone_new = preg_replace('/\D/', '', $phone);
    echo $phone_new."<br>";
}*/		
   
    }
    
    
         public function actionfixblankcities()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$offset = 0;
$offset = Yii::app()->request->getParam('offset');

		
		$all_orders = Yii::app()->db->createCommand("SELECT * FROM washing_requests ORDER BY id ASC LIMIT 500 OFFSET ".$offset)->queryAll();

if(count($all_orders)){
    foreach($all_orders as $order){
        if(!trim($order['city'])){
	$city = '';
	
	$geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($order['address'])."&sensor=true&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
    $ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//var_dump($geojsondata);
if($geojsondata->status == 'ZERO_RESULTS'){

}
else{
   $addressComponents = $geojsondata->results[0]->address_components;
            foreach($addressComponents as $addrComp){
                if($addrComp->types[0] == 'locality'){
                    //Return the zipcode
                    $city = $addrComp->short_name;
                }
            }
}

        Washingrequests::model()->updateByPk($order['id'], array("city" => $city));	
	}
	else $city = $order['city'];
	
	echo "order id: ".$order['id']." | city: ".$city;
        echo "<br>";
	
    }
}
else{
    echo "nothing found";
}
   
    }
    
    
        public function actiontotalorderspercity()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
				
		$from  = Yii::app()->request->getParam('from');
$to  = Yii::app()->request->getParam('to');

			$result= 'false';
			$response= 'nothing found';
			
			$all_washes =  Yii::app()->db->createCommand("SELECT city,COUNT(*) FROM washing_requests WHERE (order_for >= :from 00:00:00' AND order_for <= :to 23:59:00') AND status = 4 GROUP BY city ORDER BY count(*) DESC")
			->bindValue(':from', $from, PDO::PARAM_STR)
			->bindValue(':to', $to, PDO::PARAM_STR)
			->queryAll();
						
if(count($all_washes) > 0){
    $result= 'true';
			$response= 'all orders';
			
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_orders' => $all_washes
		);
		echo json_encode($json);
		
   
    }
    
    
            public function actiongetorderslatlong()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
				
		$status = 4;
		if(Yii::app()->request->getParam('status')) $status  = Yii::app()->request->getParam('status');


			$result= 'false';
			$response= 'nothing found';
			
			$all_washes =  Yii::app()->db->createCommand("SELECT id, latitude, longitude FROM washing_requests WHERE status = :status")
			->bindValue(':status', $status, PDO::PARAM_STR)
			->queryAll();
				
			//print_r($all_washes);
						
if(count($all_washes) > 0){
    $result= 'true';
			$response= 'all orders';
			
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_orders' => $all_washes
		);
		echo json_encode($json);
		
   
    }
    
      public function actionprewasherexport(){
        if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
  CsvExport::export(
    PreRegWashers::model()->findAll(), // a CActiveRecord array OR any CModel array
    array('id'=>array('raw'),'first_name'=>array('text'), 'last_name'=>array('text'), 'email'=>array('text'), 'phone'=>array('text'), 'city'=>array('text'), 'state'=>array('text'), 'zipcode'=>array('text'), 'hear_mw_how'=>array('text'), 'van_lease'=>array('text'), 'register_date'=>array('date')),
    true, // boolPrintRows
    'prewashers--'.date('Y-m-d-H-i-s').".csv",
    ","
   );
}

      public function actionwasherscsvexport(){
        if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
 $real_washer = $all_washes =  Yii::app()->db->createCommand("SELECT *, CASE WHEN account_status = 1 THEN 'Active'
            ELSE 'Blocked' END AS washer_status, CASE WHEN decals_installed = 1 THEN 'Yes'
            ELSE 'No' END AS decals_installed FROM agents WHERE washer_position = :washer_position")
            ->bindValue(':washer_position', "real", PDO::PARAM_STR)
            ->queryAll();

  CsvExport::export(
    $real_washer, // a CActiveRecord array OR any CModel array
    array('id'=>array('raw'),'real_washer_id'=>array('raw'), 'first_name'=>array('text'), 'last_name'=>array('text'), 'email'=>array('text'), 'phone_number'=>array('text'), 'city'=>array('text'),  'rating'=>array('text'), 'care_rating' => array(text), 'bt_submerchant_id'=>array('text'), 'created_date'=>array('datetime'), 'decals_installed' => array('text'), 'total_wash'=>array('text'), 'washer_position'=>array('text'), 'washer_status'=> array('text')),
    true, // boolPrintRows
    'washers--'.date('Y-m-d-H-i-s').".csv",
    ","
   );
}

      public function actionnonreturncustscsvexport(){
        if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

 $nonreturncust_arr = [];

	 $index = 0;
	 
$range = Yii::app()->request->getParam('range');
$page_number = 1;
	$limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
	$limit = 100;
	$offset = ($page_number -1) * $limit;
	
	if($limit > 0) $all_customers =  Yii::app()->db->createCommand("SELECT * FROM customers WHERE is_non_returning = 1 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset)->queryAll();

if(count($all_customers)){

                foreach($all_customers as $customer){
                     $last_wash = Washingrequests::model()->findByAttributes(array('customer_id'=>$customer['id'], 'status' => 4),array('order'=>'id DESC'));
		     if(count($last_wash)){
			$current_time = strtotime(date('Y-m-d H:i:s'));

			$create_time = strtotime($last_wash->order_for);
			$min_diff = 0;
			if($current_time > $create_time){
				$min_diff = round(($current_time - $create_time) / 60,2);
			}

			
			if(($range == 30) && ($min_diff >= 43200) && ($min_diff < 86400)){

			$nonreturncust_arr[$index]['id'] = $customer['id'];
			$nonreturncust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr[$index]['email'] = $customer['email'];
			$nonreturncust_arr[$index]['phone'] = $customer['contact_number'];
			$nonreturncust_arr[$index]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr[$index]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$index++;

			}
			
			if(($range == 60) && ($min_diff >= 86400) && ($min_diff < 129600)){

			$nonreturncust_arr[$index]['id'] = $customer['id'];
			$nonreturncust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr[$index]['email'] = $customer['email'];
			$nonreturncust_arr[$index]['phone'] = $customer['contact_number'];
			$nonreturncust_arr[$index]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr[$index]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$index++;

			}
			
			if(($range == 90) && ($min_diff >= 129600)){

			$nonreturncust_arr[$index]['id'] = $customer['id'];
			$nonreturncust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$nonreturncust_arr[$index]['email'] = $customer['email'];
			$nonreturncust_arr[$index]['phone'] = $customer['contact_number'];
			$nonreturncust_arr[$index]['total_wash'] = $customer['total_wash'];
			$nonreturncust_arr[$index]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
			
			$index++;

			}
		}
		}
}

  CsvExport::export(
    $nonreturncust_arr, // a CActiveRecord array OR any CModel array
    array('id'=>array('raw'),'customername'=>array('text'), 'email'=>array('text'), 'phone'=>array('text'), 'total_wash'=>array('text'), 'last_order'=>array('text')),
    true, // boolPrintRows
    'nonreturncustomers-'.$range.'days-pageno-'.$page_number.'--'.date('Y-m-d-H-i-s').".csv",
    ","
   );
  

}


      public function actioninactivecustscsvexport(){
        if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

 $inactivecust_arr = [];

	 $index = 0;
	 
$range = Yii::app()->request->getParam('range');
$page_number = 1;
	$limit = Yii::app()->request->getParam('limit');
	$page_number = Yii::app()->request->getParam('page_number');
	$limit = 100;
	$offset = ($page_number -1) * $limit;

if($limit > 0) $all_customers =  Yii::app()->db->createCommand("SELECT * FROM customers WHERE total_wash = 0 AND is_first_wash = 0 ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset)->queryAll();


if(count($all_customers)){

              foreach($all_customers as $ind=>$customer){
                     
			$current_time = strtotime(date('Y-m-d H:i:s'));

			$create_time = strtotime($customer['created_date']);
			$min_diff = 0;
			if($current_time > $create_time){
				$min_diff = round(($current_time - $create_time) / 60,2);
			}

			// 5 days or more inactive
			if(($range == 5) && ($min_diff >= 7200) && ($min_diff < 14400)){

			$inactivecust_arr[$index]['id'] = $customer['id'];
			$inactivecust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$inactivecust_arr[$index]['email'] = $customer['email'];
			$inactivecust_arr[$index]['phone'] = $customer['contact_number'];
			$inactivecust_arr[$index]['total_wash'] = $customer['total_wash'];
			$inactivecust_arr[$index]['last_activity'] = date('Y-m-d h:s A', strtotime($customer['updated_date']));
			
			$index++;

			}
			
			// 10 days or more inactive
			if(($range == 10) && ($min_diff >= 14400) && ($min_diff < 21600)){

			$inactivecust_arr[$index]['id'] = $customer['id'];
			$inactivecust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$inactivecust_arr[$index]['email'] = $customer['email'];
			$inactivecust_arr[$index]['phone'] = $customer['contact_number'];
			$inactivecust_arr[$index]['total_wash'] = $customer['total_wash'];
			$inactivecust_arr[$index]['last_activity'] = date('Y-m-d h:s A', strtotime($customer['updated_date']));
			
			$index++;

			}
			
			// 15 days or more inactive
			if(($range == 15) && ($min_diff >= 21600)){

			$inactivecust_arr[$index]['id'] = $customer['id'];
			$inactivecust_arr[$index]['customername'] = $customer['first_name']." ".$customer['last_name'];
			$inactivecust_arr[$index]['email'] = $customer['email'];
			$inactivecust_arr[$index]['phone'] = $customer['contact_number'];
			$inactivecust_arr[$index]['total_wash'] = $customer['total_wash'];
			$inactivecust_arr[$index]['last_activity'] = date('Y-m-d h:s A', strtotime($customer['updated_date']));
			
			$index++;

			}
		}
		}

  CsvExport::export(
    $inactivecust_arr, // a CActiveRecord array OR any CModel array
    array('id'=>array('raw'),'customername'=>array('text'), 'email'=>array('text'), 'phone'=>array('text'), 'last_activity'=>array('text')),
    true, // boolPrintRows
    'inactivecustomers-'.$range.'days-pageno-'.$page_number.'--'.date('Y-m-d-H-i-s').".csv",
    ","
   );
  

}


public function actiondistancecheck(){
	$origin_lat = Yii::app()->request->getParam('origin_lat');
	$origin_long = Yii::app()->request->getParam('origin_long');
	$dest_lat = Yii::app()->request->getParam('dest_lat');
	$dest_long = Yii::app()->request->getParam('dest_long');
$theta = $origin_long - $dest_long;
            $dist = sin(deg2rad($origin_lat)) * sin(deg2rad($dest_lat)) +  cos(deg2rad($origin_lat)) * cos(deg2rad($dest_lat)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            //$unit = strtoupper($unit);
	    echo $miles;
}


      public function actionchecknewmobilewashreviews()
    {

if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}
$result = 'false';
$response = 'no reviews';

$YELP_API_KEY = "6wdTOt8MCVehCgOUlAKUIJmn2lz8Y49VTTMNRJOfthpe_puknst-lq1qLUbAChdHmKKwu46yHi5ha-jaHg5U6ROk-Vj3VddIsd3s8j-D65kfrrYBDUKtMLsY6IQXW3Yx";


 
        $curl = curl_init();

        $url = "https://api.yelp.com/v3/businesses/mobilewash-los-angeles/reviews";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $YELP_API_KEY,
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
      
        curl_close($curl);
    $yelp_reviews = json_decode($response);
    
if(count($yelp_reviews->reviews)){
    foreach($yelp_reviews->reviews as $yreview){
        if($yreview->rating == 5){
            $review_check = Yii::app()->db->createCommand("SELECT * FROM `mobilewash_reviews` WHERE review_org_id = '".$yreview->id."'")->queryAll();
            if(!count($review_check)) Yii::app()->db->createCommand("INSERT INTO `mobilewash_reviews` (`review_org_id`, `reviewer_name`, `reviewer_location`, `reviewer_photo`, `review`, `rating`, `review_date`, `review_url`, `review_source`) VALUES ('".$yreview->id."', '".$yreview->user->name."', '', '".$yreview->user->image_url."', '".$yreview->text."', '".$yreview->rating."', '".$yreview->time_created."', '".$yreview->url."', 'yelp'); ")->execute();
        }
        
       
    }
}

    $curl = curl_init();

        $url = "https://api.appfigures.com/v2/reviews?client_key=f2c8c8f3be574cff9e0936846ba30c66";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
             CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode("mobilewash8@gmail.com:86102931"),
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
      
        curl_close($curl);
    $app_reviews = json_decode($response);
    
    if(count($app_reviews->reviews)){
    foreach($app_reviews->reviews as $appreview){
        if(($appreview->stars == 5) && ($appreview->store == 'apple')){
            $app_review_escaped = str_replace("'","\'",$appreview->review);
            $review_check = Yii::app()->db->createCommand("SELECT * FROM `mobilewash_reviews` WHERE review_org_id = '".$appreview->id."'")->queryAll();
            if(!count($review_check)) Yii::app()->db->createCommand("INSERT INTO `mobilewash_reviews` (`review_org_id`, `reviewer_name`, `reviewer_location`, `reviewer_photo`, `review`, `rating`, `review_date`, `review_url`, `review_source`) VALUES ('".$appreview->id."', '".$appreview->author."', '', '', '".$app_review_escaped."', '".$appreview->stars."', '".date('Y-m-d H:i:s', strtotime($appreview->date))."', '', '".$appreview->store."'); ")->execute();
        }
        
       
    }
}
          
        $result = 'true';
                $response = 'reviews added';
               
               $json = array(
                'result'=> $result,
                'response'=> $response
            );
            echo json_encode($json);
            die();

       
    }
    
          public function actionaddyelpreviews()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
$result = 'false';
$response = 'no reviews';
$yelp_reviews = Yii::app()->request->getParam('reviews');
  $yelp_reviews = json_decode($yelp_reviews); 
if(count($yelp_reviews)){
    foreach($yelp_reviews as $yreview){
        if($yreview->rating == 5){
            $review_check = Yii::app()->db->createCommand("SELECT * FROM `mobilewash_reviews` WHERE review_org_id = '".$yreview->reviewid."'")->queryAll();
            if(!count($review_check)) Yii::app()->db->createCommand("INSERT INTO `mobilewash_reviews` (`review_org_id`, `reviewer_name`, `reviewer_location`, `reviewer_photo`, `review`, `rating`, `review_date`, `review_url`, `review_source`) VALUES ('".$yreview->reviewid."', '".$yreview->username."', '".$yreview->userlocation."', '".$yreview->userpic."', '".$yreview->review."', '".$yreview->rating."', '".$yreview->reviewdate."', '', 'yelp'); ")->execute();
        }
        
       
    }
}

        
        $result = 'true';
                $response = 'reviews added';
               
               $json = array(
                'result'=> $result,
                'response'=> $response
            );
            echo json_encode($json);
            die();

       
    }
    
        public function actiongetallmobilewashreviews()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
	
		$all_reviews = Yii::app()->db->createCommand("SELECT * FROM `mobilewash_reviews` ORDER BY review_date DESC LIMIT 5")->queryAll();
		
			$result = 'true';
			$response = 'all reviews';
			 $json = array(
                'result'=> $result,
                'response'=> $response,
		'all_reviews' => $all_reviews
            );
			 echo json_encode($json);
            die();
		
   
    }
    
    
            public function actionlookuptest()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$phone_num = Yii::app()->request->getParam('phone');
	


// Your Account SID and Auth Token from twilio.com/console
$sid = TWILIO_SID;
$token = TWILIO_AUTH_TOKEN;
$twilio = new Client($sid, $token);

$phone_number = $twilio->lookups->v1->phoneNumbers("9098023158")
                                    ->fetch(array("type" => "carrier"));

print_r($phone_number);
echo "test<br>";
//print_r($phone_number->carrier['type']);
echo "<br>".$phone_number->carrier['type'];


		
   
    }
    
    
          /* public function actioncodetest()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

 $cust_id = Yii::app()->request->getParam('customer_id');
	
		//$pendingwashcheck =  Washingrequests::model()->findAll(array("condition"=>"wash_request_position != 'real' AND status <= 3 AND customer_id=:customer_id", 'params'  => array(':customer_id' => $customer_id), 'order' => 'created_date desc'));
		//WashPricingHistory::model()->updateAll(array('status'=>5), 'wash_request_id=:wash_request_id', array(':wash_request_id'=>$wash_request_id));
		//WashPricingHistory::model()->deleteAll("wash_request_id = :wash_request_id", array(':wash_request_id' => $wash_request_id));
	//print_r($pendingwashcheck);
	
	$clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $cust_id, PDO::PARAM_STR)->queryAll();
	print_r($clientdevices);	
   
    }*/
	  
	           public function actioncheckcustomervoipnumbers()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$offset = 0;
$offset = Yii::app()->request->getParam('offset');

		
		$all_customers = Yii::app()->db->createCommand("SELECT * FROM customers ORDER BY id ASC LIMIT 10 OFFSET ".$offset)->queryAll();

if(count($all_customers)){
    foreach($all_customers as $customer){
     
$sid = TWILIO_SID;
$token = TWILIO_AUTH_TOKEN;
$twilio = new Client($sid, $token);
try { 
$phone_number = $twilio->lookups->v1->phoneNumbers($customer['contact_number'])
                                    ->fetch(array("type" => "carrier"));
				    if(count($phone_number)) {
		if($phone_number->carrier['type'] == 'voip') Customers::model()->updateByPk($customer['id'], array("is_voip_number" => 1));		
	}
				     }catch (Twilio\Exceptions\RestException $e) {
            //echo  $e;
} 

        



	
	echo "customer id: ".$customer['id']." | phone: ".$customer['contact_number']." | type: ".$phone_number->carrier['type'];
        echo "<br>";
	
    }
}
else{
    echo "nothing found";
}
   
    }
    
    
    	           public function actioncheckwashervoipnumbers()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$offset = 0;
$offset = Yii::app()->request->getParam('offset');

		
		$all_agents = Yii::app()->db->createCommand("SELECT * FROM agents ORDER BY id ASC LIMIT 10 OFFSET ".$offset)->queryAll();

if(count($all_agents)){
    foreach($all_agents as $agent){
     
$sid = TWILIO_SID;
$token = TWILIO_AUTH_TOKEN;
$twilio = new Client($sid, $token);
try { 
$phone_number = $twilio->lookups->v1->phoneNumbers($agent['phone_number'])
                                    ->fetch(array("type" => "carrier"));
				    if(count($phone_number)) {
		if($phone_number->carrier['type'] == 'voip') Agents::model()->updateByPk($agent['id'], array("is_voip_number" => 1));		
	}
				     }catch (Twilio\Exceptions\RestException $e) {
            //echo  $e;
} 

        



	
	echo "agent id: ".$agent['id']." | phone: ".$agent['phone_number']." | type: ".$phone_number->carrier['type'];
        echo "<br>";
	
    }
}
else{
    echo "nothing found";
}
   
    }
    
    
    	public function actionsendcustomerschedulepushnotify(){

if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

          
	  //$allclients = Customers::model()->findAllByAttributes(array('is_pushmsg_pending'=> 1));
	  $allclients = Yii::app()->db->createCommand('SELECT * FROM customers WHERE is_pushmsg_pending = 1 ORDER BY total_wash DESC LIMIT 2000')->queryAll();
	  $pendingjob = Yii::app()->db->createCommand("SELECT * FROM scheduled_notifications WHERE status = 0 AND notification_type = 'clients' ORDER BY id DESC LIMIT 1")->queryAll();
	

	 if(count($allclients) && count($pendingjob)){ 
		foreach($allclients as $client){

			$clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $client['id'], PDO::PARAM_STR)->queryAll();
	
			if(count($clientdevices)){
				
			/* --- notification call --- */

                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($clientdevices[0]['device_type']);
                            $notify_token = $clientdevices[0]['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($pendingjob[0]['notification_msg']);

                            $notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);

                            /* --- notification call end --- */
			}
			
			Customers::model()->updateByPk($client['id'], array("is_pushmsg_pending" => 0));
		}
	}
	else{
		if(count($pendingjob)) Yii::app()->db->createCommand("UPDATE scheduled_notifications SET status=1 WHERE id=".$pendingjob[0]['id'])->execute();
           
	}
      

 
	}
	
	    	public function actionsendwasherschedulepushnotify(){

if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

          
	  $allagents = Yii::app()->db->createCommand('SELECT * FROM agents WHERE is_pushmsg_pending = 1 ORDER BY total_wash DESC LIMIT 2000')->queryAll();
	  $pendingjob = Yii::app()->db->createCommand("SELECT * FROM scheduled_notifications WHERE status = 0 AND notification_type = 'agents' ORDER BY id DESC LIMIT 1")->queryAll();
	

	 if(count($allagents) && count($pendingjob)){ 
		foreach($allagents as $agent){

			$agentdevices = Yii::app()->db->createCommand('SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1')->bindValue(':agent_id', $agent['id'], PDO::PARAM_STR)->queryAll();
	
			if(count($agentdevices)){
				
			/* --- notification call --- */

                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($agentdevices[0]['device_type']);
                            $notify_token = $agentdevices[0]['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($pendingjob[0]['notification_msg']);

                            $notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);

                            /* --- notification call end --- */
			}
			
			Agents::model()->updateByPk($agent['id'], array("is_pushmsg_pending" => 0));
		}
	}
	else{
		if(count($pendingjob)) Yii::app()->db->createCommand("UPDATE scheduled_notifications SET status=1 WHERE id=".$pendingjob[0]['id'])->execute();
           
	}
      

 
	}
	
	
	   	           public function actionfixcustnameparts()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$offset = 0;
$offset = Yii::app()->request->getParam('offset');
$updateentry = 0;
$updateentry = Yii::app()->request->getParam('updateentry');

		
		$all_custs = Yii::app()->db->createCommand("SELECT * FROM customers WHERE (first_name = '' OR last_name = '') AND customername != '' ORDER BY id ASC LIMIT 100")->queryAll();

if(count($all_custs)){
    foreach($all_custs as $cust){

$custname_parts = explode(" ",$cust['customername'], 2);
   	   


	echo "cust id: ".$cust['id']." | fullname: ".$cust['customername']." | firstname: ".$cust['first_name']." | lastname: ".$cust['last_name'];
        echo "<br>";
	echo "cust id: ".$cust['id']." | fullname: ".$cust['customername']." | firstname: ".trim($custname_parts[0]," ")." | lastname: ".trim($custname_parts[1]," ");
	echo "<br><br>";
	if(($updateentry == 1) && (count($custname_parts) > 0)) Customers::model()->updateByPk($cust['id'], array("first_name" => trim($custname_parts[0]," "), "last_name" => trim($custname_parts[1]," ")));
	
    }
}
else{
    echo "nothing found";
}
   
    }
 
    public function actionwashercareratingupdate(){
    if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

//$all_agents = Agents::model()->findAll();
$all_agents = Yii::app()->db->createCommand("SELECT * FROM `agents` WHERE washer_position = '".APP_ENV."' AND is_carerating_update_pending = 1 ORDER BY id ASC LIMIT 50")->queryAll();

if(count($all_agents) > 0){
    foreach($all_agents as $ind=> $washer){
        
        $agent_id = $washer['id'];
        
        $washer_registered_since = 0;
        $current_time = time(); // or your date as well
        $washer_created = strtotime($washer['created_date']);
        $datediff = $current_time - $washer_created;

        $washer_registered_since = round($datediff / (60 * 60 * 24));
        
        if($washer_registered_since > 30){
            $totalwash_arr = Yii::app()->db->createCommand("SELECT DISTINCT customer_id FROM `washing_requests` WHERE status=4 AND `agent_id` = '".$agent_id."'")->queryAll();
            
	    $totalwash = count($totalwash_arr);

            if(count($totalwash_arr)){
            $cust_served_ids = array();
	    $attempted_cust_ids = array();
            foreach($totalwash_arr as $agentwash){
		 
                if(!in_array($agentwash['customer_id'], $cust_served_ids)){
                     $cust_served_ids[] = $agentwash['customer_id'];
		     
		     $cust_wash_attempt_60days = Yii::app()->db->createCommand("SELECT customer_id FROM `washing_requests` WHERE `customer_id` = '".$agentwash['customer_id']."' AND `order_for` >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)")->queryAll();
			 //$cust_wash_attempt_60days = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `customer_id` = '".$agentwash['customer_id']."' AND `order_for` >= DATE_SUB(CURDATE(), INTERVAL 60 DAY) ORDER BY order_for DESC")->queryAll();
			
			if(count($cust_wash_attempt_60days)) {
			    $attempted_cust_ids[] = $agentwash['customer_id'];
			   //echo "wash id: ".$cust_wash_attempt_60days[0]['id']." customer id: ".$cust_wash_attempt_60days[0]['customer_id']." ".$cust_wash_attempt_60days[0]['order_for']."<br>";
			   }
		}
            }


            //$cust_served_ids = array_unique($cust_served_ids);
            $total_returning_customers = count($cust_served_ids);
            /*if(count($cust_served_ids) > 0){
              foreach($cust_served_ids as $cid){
                 $cust_check = Customers::model()->findByAttributes(array("id"=>$cid));
             $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$cid, 'status' => 4),array('order'=>'id DESC'));
             if((count($cust_check)) && ($cust_check->is_first_wash == 1) && (!$cust_check->is_non_returning) && ($cust_last_wash_check->agent_id == $agent_id)){
                 $total_returning_customers++;
             }
              }
            }*/
            

            if(count($cust_served_ids) > 0) {
                //echo "# ".$agent_id." ".count($attempted_cust_ids)."/".count($cust_served_ids)."<br>";
                $care_rating = (count($attempted_cust_ids)/count($cust_served_ids)) * 100;
                $care_rating = round($care_rating, 2);
            }

        }else{
            $care_rating = "N/A";
        }
    }else{
        $care_rating = "NEW";
    }
    
Agents::model()->updateByPk($agent_id,array('care_rating' => $care_rating, 'is_carerating_update_pending' => 0));

}


}


}


public function actionmwcareratingupdate(){
    if(Yii::app()->request->getParam('key') != API_KEY_CRON){
echo "Invalid api key";
die();
}

Agents::model()->updateAll(array("is_carerating_update_pending" => 1), 'block_washer=0');
//Agents::model()->updateAll(array("is_carerating_update_pending" => 1));

$mw_care_rating = '';
$totalwash_arr = Yii::app()->db->createCommand("SELECT COUNT(DISTINCT customer_id) AS total FROM `washing_requests` WHERE status = 4")->queryAll();
    
$totalwash_arr_60 = Yii::app()->db->createCommand("SELECT COUNT(DISTINCT w.customer_id) AS total60 FROM `washing_requests` w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.total_wash > 0 AND w.order_for >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)")->queryAll();
                  
if($totalwash_arr[0]['total'] > 0){
	//echo $totalwash_arr_60[0]['total60']." ".$totalwash_arr[0]['total'];
$mw_care_rating = ($totalwash_arr_60[0]['total60'] / $totalwash_arr[0]['total']) * 100;
$mw_care_rating = round($mw_care_rating,2);
}
else{
$mw_care_rating = 'N/A';	
}

Yii::app()->db->createCommand("UPDATE app_settings SET mw_care_rating = :mw_care_rating WHERE id = '1' ")->bindValue(':mw_care_rating', $mw_care_rating, PDO::PARAM_STR)->execute();
      
Yii::app()->db->createCommand("UPDATE app_settings SET mw_care_rating = :mw_care_rating WHERE id = '2' ")->bindValue(':mw_care_rating', $mw_care_rating, PDO::PARAM_STR)->execute();

}


	    		public function actionheatmaplistdata()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$from  = Yii::app()->request->getParam('from');
$to  = Yii::app()->request->getParam('to');
$blue_orders = 0;
$red_orders = 0;
$yellow_orders = 0;
$purple_orders = 0;

			$result= 'false';
			$response= 'nothing found';
			
			$qrRequests =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as total FROM washing_requests w RIGHT JOIN coverage_area_zipcodes z ON w.zipcode = z.zipcode WHERE (order_for >= :from AND order_for <= :to) AND w.status = 4 AND z.zip_color='purple'")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();

			$purple_orders = $qrRequests[0]['total'];
			
			$qrRequests =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as total FROM washing_requests w RIGHT JOIN coverage_area_zipcodes z ON w.zipcode = z.zipcode WHERE (order_for >= :from AND order_for <= :to) AND w.status = 4 AND z.zip_color='yellow'")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();

			$yellow_orders = $qrRequests[0]['total'];
			
			$qrRequests =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as total FROM washing_requests w RIGHT JOIN coverage_area_zipcodes z ON w.zipcode = z.zipcode WHERE (order_for >= :from AND order_for <= :to) AND w.status = 4 AND z.zip_color='red'")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();;

			$red_orders = $qrRequests[0]['total'];
						
			$qrRequests =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as total FROM washing_requests w RIGHT JOIN coverage_area_zipcodes z ON w.zipcode = z.zipcode WHERE (order_for >= :from AND order_for <= :to) AND w.status = 4 AND (z.zip_color='' OR z.zip_color='blue')")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();

			$blue_orders = $qrRequests[0]['total'];
			
			$all_washes_city =  Yii::app()->db->createCommand("SELECT city, COUNT(id) as total FROM washing_requests WHERE (order_for >= :from AND order_for <= :to) AND status = 4 GROUP BY city ORDER BY COUNT(id) DESC")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();
			
			$all_washes_zipcode =  Yii::app()->db->createCommand("SELECT zipcode, COUNT(id) as total FROM washing_requests WHERE (order_for >= :from AND order_for <= :to) AND status = 4 GROUP BY zipcode ORDER BY COUNT(id) DESC")
			->bindValue(":from", $from)
			->bindValue(":to", $to)
			->queryAll();
			
			

if((count($all_washes_city)) || (count($all_washes_zipcode))){
    $result= 'true';
    $response= 'all washes';


}


		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_washes_city' => $all_washes_city,
			'all_washes_zipcode' => $all_washes_zipcode,
			'blue_orders' => $blue_orders,
			'yellow_orders' => $yellow_orders,
			'red_orders' => $red_orders,
			'purple_orders' => $purple_orders,
		);
		echo json_encode($json);
	}
    
}