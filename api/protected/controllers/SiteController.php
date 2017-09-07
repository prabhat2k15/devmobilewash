<?php

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

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

     public function actionGetCMS()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $id = Yii::app()->request->getParam('id');
         $getcms =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = '$id' ")->queryAll();

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
         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = '$id' ")->queryAll();

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
         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM cms WHERE id = '$id' ")->queryAll();

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


				   $resUpdate = Yii::app()->db->createCommand()->update('newsletters', $data,"id='".$id."'");

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

             $msg_check = Yii::app()->db->createCommand()->select('*')->from('push_messages')->where('id='.$id)->queryAll();

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

 $msg_check = Yii::app()->db->createCommand()->select('*')->from('push_messages')->where('id='.$id)->queryAll();

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


				   $resUpdate = Yii::app()->db->createCommand()->update('push_messages', $data,"id='".$id."'");

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

$promo_check = Yii::app()->db->createCommand()->select('*')->from('promo_popups')->where('id='.$id)->queryAll();

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


				   $resUpdate = Yii::app()->db->createCommand()->update('promo_popups', $data,"id='".$id."'");

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

             $pop_check = Yii::app()->db->createCommand()->select('*')->from('promo_popups')->where('id='.$id)->queryAll();

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

            $setting_check = Yii::app()->db->createCommand()->select('*')->from('discount_settings')->where('id='.$id)->queryAll();

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

$setting_check = Yii::app()->db->createCommand()->select('*')->from('discount_settings')->where('id='.$id)->queryAll();

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


				   $resUpdate = Yii::app()->db->createCommand()->update('discount_settings', $data,"id='".$id."'");

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


    public function actionclearpendingsandlogins(){
    if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

Agents::model()->updateAll(array('device_token' => '', 'status'=> 'offline', 'available_for_new_order'=> 0));
Customers::model()->updateAll(array('online_status' => 'offline'));
Washingrequests::model()->updateAll(array('status' => 5),'status < 4 AND is_scheduled != 1');

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

            $account_sid = 'ACa9a7569fc80a0bd3a709fb6979b19423';
            $auth_token = '149336e1b81b2165e953aaec187971e6';
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
$car_packs = Yii::app()->request->getParam('car_packs');
$pet_hair_vehicles = Yii::app()->request->getParam('pet_hair_vehicles');
$lifted_vehicles = Yii::app()->request->getParam('lifted_vehicles');
$exthandwax_vehicles = Yii::app()->request->getParam('exthandwax_vehicles');
$extplasticdressing_vehicles = Yii::app()->request->getParam('extplasticdressing_vehicles');
$extclaybar_vehicles = Yii::app()->request->getParam('extclaybar_vehicles');
$waterspotremove_vehicles  = Yii::app()->request->getParam('waterspotremove_vehicles');
$fifthwash_vehicles  = Yii::app()->request->getParam('fifthwash_vehicles');
$tip_amount = 0;
$tip_amount  = Yii::app()->request->getParam('tip_amount');
$admin_username = '';
$admin_username  = Yii::app()->request->getParam('admin_username');
$full_address  = Yii::app()->request->getParam('full_address');
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
$coupon_usage = CustomerDiscounts::model()->findByAttributes(array("promo_code"=>$promo_code, "customer_id" => $wrequest_id_check->customer_id));
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

  else if(($promo_code) && (strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d"))))){
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

	if($admin_command == 'save-washer'){
			 Washingrequests::model()->updateByPk($wash_request_id, array("agent_id" => $agent_id));
			 $agent_detail = Agents::model()->findByAttributes(array("id"=>$agent_id));
			  $washeractionlogdata = array(
                        'agent_id'=> $agent_id,
                        'wash_request_id'=> $wash_request_id,
                        'agent_company_id'=> $agent_detail->real_washer_id,
                        'admin_username' => $admin_username,
                        'action'=> 'savejob',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
}

if($admin_command == 'save-note'){
 $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'savenote',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
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

  $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$wrequest_id_check->agent_id."' ")->queryAll();

						/* --- notification call --- */

						$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
						$message = $pushmsg[0]['message'];
$message = str_replace("[ORDER_ID]","#".$wash_request_id, $message);
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
												/* --- notification call end --- */
}

if($admin_command == 'update-order'){
    if($promo_code){
       if (strpos($car_packs, 'Premium') !== false) {
        $coupon_amount = number_format($coupon_check->premium_amount, 2);
       }
       else{
        $coupon_amount = number_format($coupon_check->deluxe_amount, 2);
       }

       $fifthwash_vehicles = '';
    }

    Washingrequests::model()->updateByPk($wash_request_id, array('car_list' => $car_ids, 'package_list' => $car_packs, 'pet_hair_vehicles' => $pet_hair_vehicles, 'lifted_vehicles' => $lifted_vehicles, 'exthandwax_vehicles' => $exthandwax_vehicles, 'extplasticdressing_vehicles' => $extplasticdressing_vehicles, 'extclaybar_vehicles' => $extclaybar_vehicles, 'waterspotremove_vehicles' => $waterspotremove_vehicles, 'fifth_wash_vehicles' => $fifthwash_vehicles, 'tip_amount' => $tip_amount, 'address' => $full_address, 'address_type' => $address_type, 'latitude' => $lat, 'longitude' => $lng, 'coupon_code' => $promo_code, 'coupon_discount' => $coupon_amount));

     $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'editorder',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    if($wrequest_id_check->address != $full_address){
                        $washeractionlogdata = array(

                        'wash_request_id'=> $wash_request_id,

                        'admin_username' => $admin_username,
                        'action'=> 'updatelocation',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }

}

if($status == WASHREQUEST_STATUS_COMPLETEWASH){

$washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);

      $washrequestmodel->complete_order = date("Y-m-d H:i:s");
                    $resUpdate = $washrequestmodel->save(false);

					/* ----------- update pricing details -------------- */

					$handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
					$data = array('wash_request_id' => $wash_request_id, "key" => API_KEY);
					curl_setopt($handle, CURLOPT_POST, true);
					curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
					curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
					$kartresult = curl_exec($handle);
					curl_close($handle);
					$kartdetails = json_decode($kartresult);

					$cust_details = Customers::model()->findByAttributes(array('id'=>$wrequest_id_check->customer_id));

					Washingrequests::model()->updateByPk($wash_request_id, array('total_price' => $kartdetails->total_price, 'net_price' => $kartdetails->net_price, 'company_total' => $kartdetails->company_total, 'agent_total' => $kartdetails->agent_total, 'bundle_discount' => $kartdetails->bundle_discount, 'first_wash_discount' => $kartdetails->first_wash_discount, 'coupon_discount' => $kartdetails->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points, 'fifth_wash_discount' => 0, 'fifth_wash_vehicles' => '', 'per_car_wash_points' => ''));


					/* ----------- update pricing details end -------------- */

					$all_washes = Yii::app()->db->createCommand()->select('*')->from('washing_requests')->where("customer_id = ".$wrequest_id_check->customer_id." AND status = 4 AND id != ".$wash_request_id, array())->queryAll();

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

                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",",$car_ids);


 Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 1));




                    foreach($car_ids_arr as $car){
 $cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);
 $wash_detail = Washingrequests::model()->findByPk($wash_request_id);

                    /* --------- Inspection details save --------- */

                     $cardetail = Vehicle::model()->findByPk($car);

                    $washinginspectmodel = new Washinginspections;
                    $washinginspectmodel->wash_request_id = $wash_request_id;
                    $washinginspectmodel->vehicle_id = $car;
                    $washinginspectmodel->damage_pic = $cardetail->damage_pic;
                    $washinginspectmodel->save(false);

                   /* --------- Inspection details save end --------- */

                        $carresetdata= array('status' => 0, 'eco_friendly' => 0, 'damage_points'=> '','damage_pic'=>'', 'upgrade_pack'=> 0, 'edit_vehicle'=> 0, 'remove_vehicle_from_kart'=> 0, 'new_vehicle_confirm'=> 0, 'new_pack_name'=> '', 'pet_hair' => 0, 'lifted_vehicle' => 0, 'exthandwax_addon' => 0, 'extplasticdressing_addon' => 0, 'extclaybar_addon' => 0, 'waterspotremove_addon' => 0);
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id'=>$car));

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
if (!in_array($car, $fifth_vehicles_arr)) array_push($fifth_vehicles_arr, $car);
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

$alllogs =  Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE wash_request_id=".$wash_request_id)->queryAll();
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
        $deluxe_price = Yii::app()->request->getParam('deluxe_price');
		$premium_price = Yii::app()->request->getParam('premium_price');



		if((isset($id) && !empty($id)))

			 {

$item_check = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where('id='.$id)->queryAll();

             	if(!count($item_check)){
                   	$result= 'false';
		$response= "Invalid id";
                }
else{

 if(!is_numeric($deluxe_price)){
$deluxe_price = $item_check[0]['deluxe'];
}

if(!is_numeric($premium_price)){
$premium_price = $item_check[0]['premium'];
}


                   $data= array(
					'deluxe'=> $deluxe_price,
					'premium'=> $premium_price
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('surge_pricing', $data,"id='".$id."'");

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
			}elseif($event == 'completed'){
				$status = 4;
				$status_qr = ' AND w.status="'.$status.'"';
			}elseif($event == 'processing'){
				$status = 2;
				$status_qr = ' AND (w.status >=1 && w.status <=3)';
			}
		elseif($event == 'canceled'){

				$status_qr = ' AND (w.status=5 || w.status=6)';
			}
				elseif($event == 'declined'){

				$status_qr = " AND (w.failed_transaction_id != '')";
			}
			else{
				$status_qr = '';
			}

			$order_day = " AND DATE_FORMAT(w.order_for,'%Y-%m-%d')= '$day'$status_qr";
		}
		/* END */



        $json = array();

        $result= 'true';
        $response= 'all wash requests';
        $pendingwashrequests = array();
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

if($customer_id > 0) $cust_query = "w.customer_id=".$customer_id." AND ";
if($agent_id > 0) $agent_query = "w.agent_id=".$agent_id." AND ";

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

				$customername = '';
				$cust_name = explode(" ", trim($cust_details->customername));
				if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
				else $customername = $cust_name[0];
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

if($wrequest['is_flagged'] == 1) $payment_status = 'Check Fraud';

 if($min_diff >= 0){
    $resched_date = '';
    $resched_time = '';
    if(strtotime($wrequest['reschedule_date']) > 0){
       $resched_date = date('Y-m-d',strtotime($wrequest['reschedule_date']));
    $resched_time = date('h:i A',strtotime($wrequest['reschedule_time']));
    }
   $pendingwashrequests_upcoming[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$cust_details->customername,
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
                    'customer_name'=>$cust_details->customername,
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
                    'customer_name'=>$cust_details->customername,
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
            'pending_wash_count' => $pendingorderscount
            //'upcoming' => $pendingwashrequests_upcoming,
            //'nonupcoming' => $pendingwashrequests_nonupcoming,
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

$query = Yii::app()->request->getParam('query');
		$limit = Yii::app()->request->getParam('limit');

		$limit_str = '';
      $total_count = 0;
      if($limit && ($limit != 'none')){
          $limit_str = " LIMIT ".$limit;
      }

 $cust_query = "(c.customername LIKE '%$query%' OR c.email LIKE '%$query%' OR c.contact_number LIKE '%$query%') OR ";
$agent_query = "(a.first_name LIKE '%$query%' OR a.last_name LIKE '%$query%' OR a.email LIKE '%$query%' OR a.phone_number LIKE '%$query%') ";

		//if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
//else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();


if($query){
    $qrRequests =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id WHERE ".$cust_query.$agent_query."ORDER BY w.id DESC".$limit_str)->queryAll();
 $total_rows = Yii::app()->db->createCommand("SELECT COUNT(w.id) as countid FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id WHERE ".$cust_query.$agent_query."ORDER BY w.id DESC")->queryAll();
 $total_count = $total_rows[0]['countid'];

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

				$customername = '';
				$cust_name = explode(" ", trim($cust_details->customername));
				if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
				else $customername = $cust_name[0];
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
                    'customer_name'=>$cust_details->customername,
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
                    'customer_name'=>$cust_details->customername,
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
                    'customer_name'=>$cust_details->customername,
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
            'total_wash_requests' => $total_count
        );

        echo json_encode($json); die();
    }


public function actionnewcustomerwelcomepush(){
  if(Yii::app()->request->getParam('key') != API_KEY){
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

	        	$clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = ".$client['id'])->queryAll();
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
  if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$clientlist = Yii::app()->db->createCommand("SELECT * FROM customers WHERE is_first_wash = 1 AND is_nextwash_reminder_push_sent = 0")->queryAll();

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
if($min_diff >= 14400){

	        	$clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = ".$client['id'])->queryAll();
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
			          $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent_id."' ")->queryAll();

            if(count($agentdevices))
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
  if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

	$clientlist = Customers::model()->findAll();

	if(count($clientlist)){
	    foreach($clientlist as $client){
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

						 Customers::model()->updateByPk($client->id, array("is_non_returning" => 1));


}
else{
    Customers::model()->updateByPk($client->id, array("is_non_returning" => 0));
}
	         }



	    }
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
$all_customers = Customers::model()->findAllByAttributes(array('is_non_returning' => 1),array('order'=>'id ASC'));

			if(count($all_customers)){

				$response = "nonreturning customers";
				$result = "true";
                foreach($all_customers as $ind=>$customer){
                     $last_wash = Washingrequests::model()->findByAttributes(array('customer_id'=>$customer->id, 'status' => 4),array('order'=>'id DESC'));
                    $nonreturncust_arr[$ind]['id'] = $customer->id;
                    $nonreturncust_arr[$ind]['name'] = $customer->customername;
                    $nonreturncust_arr[$ind]['email'] = $customer->email;
                    $nonreturncust_arr[$ind]['phone'] = $customer->contact_number;
                    $nonreturncust_arr[$ind]['total_wash'] = $customer->total_wash;
                    if(count($last_wash)) $nonreturncust_arr[$ind]['last_order'] = "#".$last_wash->id." at ".date('m-d-Y h:i A', strtotime($last_wash->order_for));
                    else $nonreturncust_arr[$ind]['last_order'] = "N/A";
                }

			}



       $json = array(
			'result'=> $result,
			'response'=> $response,
			'allcustomers' => $nonreturncust_arr
		);

		echo json_encode($json);
		die();

}


public function actionwashfraudcheck() {

if(Yii::app()->request->getParam('key') != API_KEY){
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
                   $kartapiresult = $this->washingkart($wash['id'], API_KEY);
$kartdata = json_decode($kartapiresult);

$cust_detail = Customers::model()->findByPk($wash['customer_id']);

/* ------- higher price check --------- */

 if($kartdata->net_price > 120){
   $is_flagged = 1;
 }

 /* ------- higher tip check --------- */

 if($kartdata->tip_amount >= 20){
   $is_flagged = 1;
 }

  /* ------- strange email check --------- */

$cust_name_arr = explode(" ", $cust_detail->customername);
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
                     $cust_name_arr = explode(" ", $cust_detail->customername);

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
        $schedule_date = Yii::app()->request->getParam('schedule_date');
        $schedule_time = Yii::app()->request->getParam('schedule_time');

		if((isset($msg) && !empty($msg)) && (isset($receiver_type) && !empty($receiver_type)) && (isset($schedule_date) && !empty($schedule_date)) && (isset($schedule_time) && !empty($schedule_time))){

            $data = array(
                        'notification_type'=> $receiver_type,
                        'receiver_ids' => $receiver_ids,
                        'notification_msg' => $msg,
                        'schedule_date' => date('Y-m-d H:i:s', strtotime($schedule_date." ".$schedule_time)),
                        'created_date' => date('Y-m-d H:i:s'),
                        'status'=> 0);

                    Yii::app()->db->createCommand()->insert('scheduled_notifications', $data);

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


}