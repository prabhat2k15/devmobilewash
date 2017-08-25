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
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

     public function actionGetCMS()
    {
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

public function actiongetreminderstwo()
    {


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


 // visitors Year wise

    public function ActionVisitorsYearWise (){
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
         $sitesettings =  Yii::app()->db->createCommand("SELECT * FROM site_settings ")->queryAll();

         $value = $sitesettings[0]['value'];
         $fromdate = $sitesettings[0]['fromdate'];
         $enddate = $sitesettings[0]['enddate'];
         $message = $sitesettings[0]['message'];
         $json = array(
                'site_service'=> $value,
                'fromdate'=> $fromdate,
                'enddate'=> $enddate,
                'message'=> $message
            );
             echo json_encode($json);
             exit;


    }

 public function actionGetBackupFile()
    {


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


 $from = Vargas::Obj()->getAdminEmail();
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

		$result= 'false';
		$response= 'Fill up required fields';

 $mon = Yii::app()->request->getParam('mon');
         $tue = Yii::app()->request->getParam('tue');
 $wed = Yii::app()->request->getParam('wed');
 $thurs = Yii::app()->request->getParam('thurs');
 $fri = Yii::app()->request->getParam('fri');
 $sat = Yii::app()->request->getParam('sat');
 $sun = Yii::app()->request->getParam('sun');


$schedule_times = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=1')->queryAll();

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




                   $data= array(
					'mon'=> $mon,
					'tue'=> $tue,
                    'wed'=> $wed,
                    'thurs'=> $thurs,
					'fri'=> $fri,
                    'sat'=> $sat,
                    'sun'=>  $sun
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('schedule_times', $data,"id=1");

                    	$result= 'true';
		$response= 'schedule times updated successfully';



		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetscheduletimes(){

		$result= 'false';
		$response= 'Fill up required fields';

$times = array();

$schedule_times = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=1')->queryAll();

 
$times['mon'] = $schedule_times[0]['mon'];

$times['tue'] = $schedule_times[0]['tue'];

$times['wed'] = $schedule_times[0]['wed'];

$times['thurs'] = $schedule_times[0]['thurs'];

$times['fri'] = $schedule_times[0]['fri'];

$times['sat'] = $schedule_times[0]['sat'];

$times['sun'] = $schedule_times[0]['sun'];



                    	$result= 'true';
		$response= 'schedule times';



		$json= array(
			'result'=> $result,
			'response'=> $response,
'schedule_times' => $times
		);
		echo json_encode($json);
	}


}
