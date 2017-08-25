<?php
class UsersController extends Controller{
	public function actionIndex(){
		$this->render('index');
	}

	/*
	** Performs the User Registration.
	** Post Required: emailid, username, password, image, device_token, login_type, mobile_type
	** Url:- http://www.vishalbhosale.com/projects/trans/index.php?r=users/usersregistration
	** Purpose:- New users can register in app
	*/


	/*
	** Performs the User facebook login Registration.
	** Post Required: emailid, username, image, device_token, login_type, mobile_type
	*/


	/**
	** Performs the login.
	**/
	public function actionlogin(){

		$username = Yii::app()->request->getParam('email');
		$password = md5(Yii::app()->request->getParam('password'));
		$device_token = Yii::app()->request->getParam('device_token');

		if((isset($username) && !empty($username)) && (isset($password) && !empty($password))){
			$user_id = Users::model()->findByAttributes(array("email"=>$username));
			if(count($user_id)>0){
				if($user_id->password== $password){


					if(!empty($device_token)){
							$model= Users::model()->findByAttributes(array('id'=>$user_id->id));
							$data= array('device_token'=>$device_token, 'password_reset_token' => '');
							$data= array_filter($data);
							$model->attributes= $data;
							$model->save(false);
					}

$model= Users::model()->findByAttributes(array('id'=>$user_id->id));
							$data= array('password_reset_token' => '');
							$model->attributes= $data;
							$model->save(false);
					$result= 'true';
					$response= 'Successfully login';
					$json= array(
						'result'=> $result,
						'response'=> $response,
'user_type' => $user_id->users_type

					);
				}else{
					$result= 'false';
					$response= 'Wrong password';
					$json= array(
						'result'=> $result,
						'response'=> $response,
					);
				}
			}else{
				$result= "false";
				$response = 'Wrong email';
				$json = array(
					'result'=> $result,
					'response'=> $response
				);
			}
		}else{
			$json = array(
				'result'=> 'false',
				'response'=> 'Pass the required parameters'
			);
		}
		echo json_encode($json);
		die();
	}

	public function actionAppstat(){
        // Get Customer Online/Offline Status
        $customers_online = Customers::model()->countByAttributes(array("online_status"=>'online'));
        $customers_offline = Customers::model()->countByAttributes(array("online_status"=>'offline'));

// Get total agents
        $agent_total =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM agents ")->queryAll();
        $total_agetns = $agent_total[0]['count'];
        // Get total clients
        $customers_total =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM customers ")->queryAll();
        $total_customers = $customers_total[0]['count'];
        //Get total order
        $toatlorder =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM washing_requests ")->queryAll();
        $ordertotal = $toatlorder[0]['count'];
$toatlorder_real =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM washing_requests WHERE wash_request_position='real'")->queryAll();
        $ordertotal_real = $toatlorder_real[0]['count'];
        //Get today order
        $curntdate1 = date('Y-m-d').' '.'00:00:00';
        $curntdate2 = date('Y-m-d').' '.'23:59:59';
        $ordertoday =  Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE created_date BETWEEN '$curntdate1' AND '$curntdate2' ")->queryAll();
        $todayorder = $ordertoday[0]['count'];
        if(!empty($todayorder)){
            $today_order = $ordertoday[0]['count'];
        }else{
            $today_order = 0;
        }
        //Get Idel agents
        $format = 'Y-m-d h:i:s';
        $date = date ( $format, strtotime ( '-60 days' ) );
        $idle_count =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM agents WHERE total_wash = 0 ")->queryAll();
        $total_idle = $idle_count[0]['count'];
        $idle =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE total_wash != 0 ")->queryAll();
        $cnt = 0;
        foreach($idle as $washers){


            $id = $washers['id'];
            $totalwash = $washers['total_wash'];
            if($totalwash != 0){
                $agentwash_date =  Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM washing_requests WHERE agent_id = '$id' AND created_date <= '$date' GROUP BY agent_id ")->queryAll();

                foreach($agentwash_date as $wash){

                if(!empty($agentwash_date)){

                     $cnt++;
                }
                }
            }
        }
        $idle_wash = $cnt+$total_idle;

        //Get Idel Clients
        $format = 'Y-m-d h:i:s';
        $date = date ( $format, strtotime ( '-60 days' ) );
        $idle_count_client =  Yii::app()->db->createCommand("SELECT id FROM customers WHERE total_wash = 0 ")->queryAll();
        $client_one = array();
        foreach($idle_count_client as $customerid){
            $client_one[] = $customerid['id'];
        }
        $idle_client =  Yii::app()->db->createCommand("SELECT * FROM customers WHERE total_wash != 0 ")->queryAll();
        $client_two = array();
        foreach($idle_client as $clients){


            $id = $clients['id'];
            $totalwash_client = $clients['total_wash'];
            if($totalwash != 0){
                $clientwash_date =  Yii::app()->db->createCommand("SELECT customer_id FROM washing_requests WHERE customer_id = '$id' AND created_date <= '$date' GROUP BY customer_id ")->queryAll();
                //echo "SELECT * FROM washing_requests WHERE customer_id = '$id' AND created_date <= '$date' ".'<br />';

                foreach($clientwash_date as $wash){

                if(!empty($clientwash_date)){

                     $client_two[] = $wash['customer_id'];
                }
                }
            }
        }
        //exit;
        $idle_wash_client = count($client_one+$client_two);
        //Get Expiring Licence agents
        $timestamp = time()-86400;
        $time_stamp = strtotime("+7 day", $timestamp);
        $next_date = date('Y-m-d', $time_stamp);
        $current_date = date('Y-m-d');
        $insurance_license_expiration =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM agents WHERE insurance_license_expiration between '$current_date' AND '$next_date' ")->queryAll();
        $insurance_license_expiration_count = $insurance_license_expiration[0]['count'];
        //Get Late agents
        $late_agents =  Yii::app()->db->createCommand("SELECT COUNT(*) as cnt, agent_id FROM washing_requests WHERE TIMESTAMPDIFF(MINUTE, wash_begin, complete_order) > estimate_time GROUP BY agent_id HAVING cnt>2 ")->queryAll();
        $late_drivers = count($late_agents);
        //Get bad rating agents
        $rating =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM agents WHERE rating <= 3.50 ")->queryAll();
        $bad_rating_agents = $rating[0]['count'];
        //Get bad rating clients
        $rating_customer =  Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM customers WHERE rating <= 3.50 ")->queryAll();
        $bad_rating_customers = $rating_customer[0]['count'];


        // Get Agent Online/Offline Status
        $agent_online = Agents::model()->countByAttributes(array("status"=>'online', "available_for_new_order"=>1));
$agent_busy = Agents::model()->countByAttributes(array("status"=>'online', "available_for_new_order"=>0));
        $agent_offline = Agents::model()->countByAttributes(array("status"=>'offline'));
        // Get Pending Orders
        $pending_orders = Washingrequests::model()->countByAttributes(array("status"=>'0', "is_scheduled"=>'0'));
$sched_orders = Washingrequests::model()->countByAttributes(array("status"=>'0', "is_scheduled"=>'1', 'wash_request_position' => 'real'));
 $cancel_orders_client = Washingrequests::model()->countByAttributes(array("status"=>'5'));
$cancel_orders_agent = Washingrequests::model()->countByAttributes(array("status"=>'6'));
        // Get Processing Orders

        $processing_orders = Yii::app()->db->createCommand()
            ->select('*')
            ->from('washing_requests')  //Your Table name
            ->where('status>=1 AND status<=3') // Write your where condition here
            ->queryAll();

            $processing_orders = count($processing_orders);
        // Get Completed Orders
        $completed_orders = Washingrequests::model()->countByAttributes(array("status"=>'4'));
$completed_orders = number_format($completed_orders);

           // Get Completed Orders today
        $today = date("Y-m-d");

        $completed_orders_check = Yii::app()->db->createCommand()
            ->select('*')
            ->from('washing_requests')  //Your Table name
            ->where("status = 4 AND date(created_date) = '".$today."'") // Write your where condition here
            ->queryAll();

         $completed_orders_today = count($completed_orders_check);

$home_orders_check = Yii::app()->db->createCommand()
            ->select('*')
            ->from('washing_requests')  //Your Table name
            ->where("status = 4 AND (address_type='Home' OR address_type='home' OR  address_type='HOME')")
            ->queryAll();

 $home_orders = count($home_orders_check);
$home_orders = number_format($home_orders);


$office_orders_check = Yii::app()->db->createCommand()
            ->select('*')
            ->from('washing_requests')  //Your Table name
            ->where("status = 4 AND (address_type='Office' OR address_type='office' OR  address_type='OFFICE' || address_type='Work' OR address_type='work' OR  address_type='WORK')")
            ->queryAll();

 $office_orders = count($office_orders_check);
$office_orders = number_format($office_orders);

             // Get Cancel Orders

        $cancel_orders = Yii::app()->db->createCommand()
            ->select('*')
            ->from('washing_requests')  //Your Table name
            ->where('status > 4 AND status<=6') // Write your where condition here
            ->queryAll();
            $cancel_orders = count($cancel_orders);
        // Get Number of busy Agents
        $logs = Yii::app()->db->createCommand()
            ->select('COUNT(*) as busyAgent')
            ->from('washing_requests')  //Your Table name
            ->group('agent_id')
            ->where('status>=1 AND status<=3') // Write your where condition here
            ->queryAll();
        $busyagents = count($logs);

 //today revnue
        $curntdate1 = date('Y-m-d').' '.'00:00:00';
        $curntdate2 = date('Y-m-d').' '.'23:59:59';
        $revnue = Yii::app()->db->createCommand("SELECT SUM(company_total) as total FROM `washing_requests` WHERE `status` = 4 AND created_date BETWEEN '$curntdate1' AND '$curntdate2'  ")->queryAll();
        $profit = $revnue[0]['total'];
        if(!empty($profit)){
            $profit = '$'.number_format($revnue[0]['total'], 2);
        }else{
            $profit = 0;
        }
        // end
        //total revnue
        $revnue_total = Yii::app()->db->createCommand("SELECT SUM(company_total) as total FROM `washing_requests` WHERE `status` = 4 ")->queryAll();
        $total_profit = '$'.number_format($revnue_total[0]['total'], 2);
        // end


		/* PRE-REGISETER CLIENT TOTAL COUNT */
		$pre_registered_clients = '';
		$request = Yii::app()->db->createCommand("SELECT COUNT(*) as cnt FROM `customers`")->queryAll();
		$pre_registered_clients = $request[0]['cnt'];

		/* END */

        $data = array(
                'Online_Customers' => $customers_online,
                'Offline_Customers' => $customers_offline,
 'total_agetns' => $total_agetns,
                'total_customers' => $total_customers,
                'bad_rating_agents' => $bad_rating_agents,
                'bad_rating_customers' => $bad_rating_customers,
                'insurance_license_expiration_count' => $insurance_license_expiration_count,
                'idle_wash_client' => $idle_wash_client,
                'late_drivers' => $late_drivers,
                'idle_wash' => $idle_wash,
                'idle_wash_client' => $idle_wash_client,
                'Online_Agent' => $agent_online,
                'Offline_Agent' => $agent_offline,
                'Pending_Orders' => $pending_orders,
'Schedule_Orders' => $sched_orders,
                'Processing_Orders' => $processing_orders,
                'Completed_Orders' => $completed_orders,
                'Completed_Orders_today' => $completed_orders_today,
                'Cancel_Orders' => $cancel_orders,
				'Home_Orders' => $home_orders,
				'Office_Orders' => $office_orders,
				'Cancel_Orders_Client' => $cancel_orders_client,
				'Cancel_Orders_Agent' => $cancel_orders_agent,
				'totalorder' => $ordertotal,
'totalorder_real' => $ordertotal_real,
				'todayorder' => $today_order,
				'total_profit' => $total_profit,
				'profit' => $profit,
                'busy_Agents' => $busyagents,
				'pre_registered_clients' => $pre_registered_clients

            );
        echo json_encode($data);
        die();
    }

	public function actionlogout(){

		$device_token = Yii::app()->request->getParam('device_token');
		$model= Users::model()->findByAttributes(array('device_token'=>$device_token));
		$json= array();
		if(count($model)>0){
			$data= array('device_token' => '');
			$model->attributes= $data;
			if($model->save(false)){
				$result= 'true';
				$response= 'Successfully logout';
				$json= array(
					'result'=> $result,
					'response'=> $response
				);
			}
		}else{
			$result= 'false';
			$response= 'Invalid request';
			$json= array(
				'result'=> $result,
				'response'=> $response
			);
		}
		echo json_encode($json);
	}

	public function actionauthenticate(){
	$result= 'false';
	$response= 'error';
	$device_token = Yii::app()->request->getParam('device_token');
	if(isset($device_token) && ($device_token != '')) {

		$model= Users::model()->findByAttributes(array('device_token'=>$device_token));
		$json= array();
		if(count($model)>0){
		$result= 'true';
				$response= 'success';


		}else{
			$result= 'false';
			$response= 'error';

		}
		}
			$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);
	}

public function actionAppLogin() {
        $username = Yii::app()->request->getParam('email');
        $password = md5(Yii::app()->request->getParam('password'));
        $device_token = Yii::app()->request->getParam('device_token');
        $device_type =Yii::app()->request->getParam('mobile_type');
        $user_type = "";
        $model = false;

        /* -------- check for hours of operation -------- */

        date_default_timezone_set('America/Los_Angeles');
        $current_day = strtolower(date('l'));
        //$current_time = date('g:i:s A');
        //echo $current_day;
        //echo "<br>".$current_time;

        $hours_op_check =  Shedule::model()->findByAttributes(array('day'=>$current_day));
        $hours_op_response =  Shedule::model()->findByAttributes(array('id'=>8));

if($hours_op_check->status == 'off'){
             $json = array(
                'result'=> 'false',
                'response'=> $hours_op_response->status
            );
        }

else if( $hours_op_check->open_all_day != 'yes' && ((time() < strtotime($hours_op_check->from)) || (time() > strtotime($hours_op_check->to)))){
            $json = array(
                'result'=> 'false',
                'response'=> $hours_op_response->status
            );

        }

        else{

        if((isset($username) && !empty($username)) && (isset($password) && !empty($password)&& (isset($device_token) && !empty($device_token)))&& (isset($device_type) && !empty($device_type))){
                $customer =  Customers::model()->findByAttributes(array('email'=>$username));


                $customer_login_status =  Yii::app()->db->createCommand("SELECT * FROM `customers` WHERE `email` = '$username'")->queryAll();
                $agent_login_status =  Yii::app()->db->createCommand("SELECT * FROM `agents` WHERE `email` = '$username'")->queryAll();

                $agent   =   Agents::model()->findByAttributes(array('email'=>$username));

            if($customer){ $model = $customer; $user_type ="customer"; }
            else if($agent){ $model = $agent;$user_type ="agent"; }
             if($customer_login_status[0]['device_token']!=$device_token && $customer_login_status[0]['online_status']=='online')
             {
                 $result= "false";
                $response = "There is no permission for log in with same account on 2 devices";
                $json = array(
                    'result'=> $result,
                    'response'=> $response
                );
             }
             else if($agent_login_status[0]['device_token']!=$device_token && $agent_login_status[0]['status']=='online')
             {
                 $result= "false";
                $response = "There is no permission for log in with same account on 2 devices";
                $json = array(
                    'result'=> $result,
                    'response'=> $response
                );
             }
             else
             {
            if($model){

                //print_r($user_type);die;
                if($model->password === $password){

                    $user_data = array('usertype'=>$user_type,'user_id'=>$model->id);
                    if(!empty($device_token)){
                        $model->device_token = $device_token;
                        $model->mobile_type = $device_type;
                        $model->save(false);

                    }
                    $result= 'true';
                    $response= 'Successfully login';
                    $json= array(
                        'result'=> $result,
                        'response'=> $response,
                    );
                    if($user_type == 'customer'){
                    $online_status= array('online_status' => 'online');

                    /* Comment for online status change */

						$update_status = Customers::model()->updateAll($online_status,'id=:id',array(':id'=>$model->id));



                    $latestwash = Washingrequests::model()->findByAttributes(array('customer_id'=>$model->id), array('order'=>'created_date DESC'));
                     $allschedwashes = Washingrequests::model()->findAllByAttributes(array('customer_id' => $model->id, 'is_scheduled' => 1), array('condition'=>'status = 0 OR status = 1 OR status = 2'));

                      $upcoming_schedule_wash_details = array();
                     if(count($allschedwashes)){

                         foreach($allschedwashes as $schedwash){
$sched_date = '';
$sched_time = '';
if($schedwash->reschedule_time){
$sched_date = $schedwash->reschedule_date;
$sched_time = $schedwash->reschedule_time;
}
else{
$sched_date = $schedwash->schedule_date;
$sched_time = $schedwash->schedule_time;
}

if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;
               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = -1;
if($from_time >= $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}

if($min_diff <= 60 && $min_diff >= 0){
     $ag_det = Agents::model()->findByPk($schedwash->agent_id);
   $upcoming_schedule_wash_details['id'] = $schedwash->id;
   $upcoming_schedule_wash_details['schedule_date'] = $sched_date;
   $upcoming_schedule_wash_details['schedule_time'] = $sched_time;
   $upcoming_schedule_wash_details['status'] = $schedwash->status;
   $upcoming_schedule_wash_details['customer_id'] = $schedwash->customer_id;
   $upcoming_schedule_wash_details['agent_id'] = $schedwash->agent_id;
   if(count($ag_det)){
    $upcoming_schedule_wash_details['agent_name'] = $ag_det->first_name." ".$ag_det->last_name;
    $upcoming_schedule_wash_details['agent_rating'] = $ag_det->rating;
    $upcoming_schedule_wash_details['agent_phone'] = $ag_det->phone_number;
   }
   break;
}
                         }
                     }
                        $location_details = Yii::app()->db->createCommand()
                            ->select('*')
                            ->from('customer_locations')
                            ->where("customer_id='".$model->id."'", array())
                            ->queryAll();

                        $locations = array();

                        if(count($location_details)>0){
                            foreach($location_details as $sloc){
                                $locations[]= array(
                                    'location_title'=> $sloc['location_title'],
                                    'location_address'=> $sloc['location_address'],
                                    'actual_longitude'=> $sloc['actual_longitude'],
                                    'actual_latitude'=> $sloc['actual_latitude'],
                                    'is_editable'=> $sloc['is_editable']
                                );
                            }
                        }

$customername = '';
$cust_name = explode(" ", trim($model->customername));
if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
else $customername = $cust_name[0];

if($latestwash->id){
$json= array(
                            'result'=> $result,
                            'response'=> $response,
                            'user_type'=>$user_type,
                            'customerid' => $model->id,
                            'email' => $model->email,
                            'customername' => $customername,
                            'image' => $model->image,
                            'contact_number' => $model->contact_number,
                            'locations' => $locations,
                            'total_washes' => $model->total_wash,
'fifth_wash_points' => $model->fifth_wash_points,
                            'email_alerts'=> $model->email_alerts,
                            'push_notifications'=> $model->push_notifications,
'phone_verified'=> $model->phone_verified,
                            'wash_id'=>$latestwash->id,
                            'upcoming_schedule_wash_details' => $upcoming_schedule_wash_details
                             );
}
else{
  $json= array(
                            'result'=> $result,
                            'response'=> $response,
                            'user_type'=>$user_type,
                            'customerid' => $model->id,
                            'email' => $model->email,
                            'customername' => $customername,
                            'image' => $model->image,
                            'contact_number' => $model->contact_number,
                            'locations' => $locations,
'fifth_wash_points' => $model->fifth_wash_points,
                            'total_washes' => $model->total_wash,
                            'email_alerts'=> $model->email_alerts,
                            'push_notifications'=> $model->push_notifications,
'phone_verified'=> $model->phone_verified,
'upcoming_schedule_wash_details' => $upcoming_schedule_wash_details

                        );
}

                    }else{

                      $online_status = array('status' => 'online');
                      /*Comment for online status change */
		              $update_status = Agents::model()->updateAll($online_status,'id=:id',array(':id'=>$model->id));
$update_active = Agents::model()->updateAll(array('last_activity' => date("Y-m-d H:i:s")),'id=:id',array(':id'=>$model->id));

if($model->status == 'online'){
                    /* ------------- check if agent available for new order -------------*/

                     $isagentbusy = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id='".$model->id."' AND (status >= 1 AND status <= 3)")->queryAll();
                    ;
                    if(!count($isagentbusy)){
                       Agents::model()->updateAll(array('available_for_new_order' => 1),'id=:id',array(':id'=>$model->id));
                    }

                    /* ------------- check if agent available for new order end -------------*/
}

                    $latestwash = Washingrequests::model()->findByAttributes(array('agent_id'=>$model->id), array('order'=>'created_date DESC'));
                       $allschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $model->id, 'is_scheduled' => 1), array('condition'=>'status = 0 OR status = 1 OR status = 2'));

                      $upcoming_schedule_wash_details = array();
                      $is_scheduled_wash_120 = 0;
$min_diff = -1;
                     if(count($allschedwashes)){

                         foreach($allschedwashes as $schedwash){
$sched_date = '';
$sched_time = '';
if($schedwash->reschedule_time){
$sched_date = $schedwash->reschedule_date;
$sched_time = $schedwash->reschedule_time;
}
else{
$sched_date = $schedwash->schedule_date;
$sched_time = $schedwash->schedule_time;
}

if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = -1;
if($from_time >= $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}

if(!$is_scheduled_wash_120){
    if($min_diff <= 120 && $min_diff >= 0){
       $is_scheduled_wash_120 = 1;
    }
}

if($min_diff <= 60 && $min_diff >= 0){
     $ct_det = Customers::model()->findByPk($schedwash->customer_id);
   $upcoming_schedule_wash_details['id'] = $schedwash->id;
   $upcoming_schedule_wash_details['schedule_date'] = $sched_date;
   $upcoming_schedule_wash_details['schedule_time'] = $sched_time;
   $upcoming_schedule_wash_details['status'] = $schedwash->status;
   $upcoming_schedule_wash_details['customer_id'] = $schedwash->customer_id;
   $upcoming_schedule_wash_details['agent_id'] = $schedwash->agent_id;
   if(count($ct_det)){
    $upcoming_schedule_wash_details['customer_name'] = $ct_det->customername;
    $upcoming_schedule_wash_details['customer_rating'] = $ct_det->rating;
    $upcoming_schedule_wash_details['customer_phone'] = $ct_det->contact_number;
   }
   break;
}
                         }
                     }
                        $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" =>$model->id));

                        $total_rate = count($agent_feedbacks);
                        if($total_rate){
                            $rate = 0;
                            foreach($agent_feedbacks as $agent_feedback){
                                $rate += $agent_feedback->agent_ratings;
                            }

                            $agent_rate =  round($rate/$total_rate);
                        }
                        else{
                            $agent_rate = 0;
                        }

$agentlname = '';
if(trim($model->last_name)) $agentlname = strtoupper(substr($model->last_name, 0, 1)).".";
else $agentlname = $model->last_name;

                        if($latestwash->id){
                        $json= array(
                            'result'=> $result,
                            'response'=> $response,
                            'user_type'=>$user_type,
                            'agentid' => $model->id,
                            'email' => $model->email,
                            'first_name' => $model->first_name,
                            'last_name' => $agentlname,
                            'image' => $model->image,
                            'contact_number' => $model->phone_number,
                            'street_address' => $model->street_address,
                            'suite_apt' => $model->suite_apt,
                            'city' => $model->city,
                            'state' => $model->state,
                            'zipcode' => $model->zipcode,
                            'driver_license' => $model->driver_license,
                            'proof_insurance' => $model->proof_insurance,
                            'legally_eligible' => $model->legally_eligible,
                            'own_vehicle' => $model->own_vehicle,
                            'waterless_wash_product' => $model->waterless_wash_product,
                            'operate_area' => $model->operate_area,
                            'work_schedule' => $model->work_schedule,
                            'operating_as' => $model->operating_as,
                            'company_name' => $model->company_name,
                            'wash_experience' => $model->wash_experience,
                            'account_status' => $model->account_status,
                            'created_date' => $model->created_date,
                            'total_washes' => $model->total_wash,
                            'rating' => $model->rating,
                            'wash_id'=>$latestwash->id,
                            'upcoming_schedule_wash_details' => $upcoming_schedule_wash_details,
                            'is_scheduled_wash_120' => $is_scheduled_wash_120,
'time_left_to_start' => $min_diff
                        );
                        }
                        else{
                        $json= array(
                            'result'=> $result,
                            'response'=> $response,
                            'user_type'=>$user_type,
                            'agentid' => $model->id,
                            'email' => $model->email,
                            'first_name' => $model->first_name,
                            'last_name' => $model->last_name,
                            'image' => $model->image,
                            'contact_number' => $model->phone_number,
                            'street_address' => $model->street_address,
                            'suite_apt' => $model->suite_apt,
                            'city' => $model->city,
                            'state' => $model->state,
                            'zipcode' => $model->zipcode,
                            'driver_license' => $model->driver_license,
                            'proof_insurance' => $model->proof_insurance,
                            'legally_eligible' => $model->legally_eligible,
                            'own_vehicle' => $model->own_vehicle,
                            'waterless_wash_product' => $model->waterless_wash_product,
                            'operate_area' => $model->operate_area,
                            'work_schedule' => $model->work_schedule,
                            'operating_as' => $model->operating_as,
                            'company_name' => $model->company_name,
                            'wash_experience' => $model->wash_experience,
                            'account_status' => $model->account_status,
                            'created_date' => $model->created_date,
                            'total_washes' => $model->total_wash,
                            'rating' => $model->rating,
                            'upcoming_schedule_wash_details' => $upcoming_schedule_wash_details,
                            'is_scheduled_wash_120' => $is_scheduled_wash_120,
'time_left_to_start' => $min_diff

                        );
                        }

                    }

                }
                else
                {
                    $result= 'false';
                    $response= 'Wrong password';
                    $json= array(
                        'result'=> $result,
                        'response'=> $response,
                    );
                }
            } else {
                $result= "false";
                $response = 'Wrong email';
                $json = array(
                    'result'=> $result,
                    'response'=> $response
                );
            }
        }
        }
        else{
            $json = array(
                'result'=> 'false',
                'response'=> 'Pass the required parameters'
            );
        }
        }
        echo json_encode($json);
        die();

    }


    	/**
	** send notifications from admin
	**/
	public function actionadminnotify(){

		$msg = Yii::app()->request->getParam('msg');
		$receiver_type = Yii::app()->request->getParam('receiver_type');

		if((isset($msg) && !empty($msg)) && (isset($receiver_type) && !empty($receiver_type))){
          $allagents =  Yii::app()->db->createCommand()->select('*')->from('agent_devices')->queryAll();
          $allclients = Yii::app()->db->createCommand()->select('*')->from('customer_devices')->queryAll();

          if($receiver_type == 'all' ||  $receiver_type == 'agents'){

        foreach($allagents as $agent){

                        /* --- notification call --- */

                            //echo $agentdetails['device_type'];
                            $device_type = strtolower($agent['device_type']);
                            $notify_token = $agent['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($msg);

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);

                            /* --- notification call end --- */
        }
        }

         if($receiver_type == 'all' ||  $receiver_type == 'clients'){

        foreach($allclients as $client){

                        /* --- notification call --- */

                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($client['device_type']);
                            $notify_token = $client['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($msg);

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);

                            /* --- notification call end --- */
        }
        }

        	$json = array(
				'result'=> 'true',
				'response'=> 'notification sent'
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

    	public function actionnewslettersubscribe(){
	$result= 'false';
	$response= 'Please enter email address';
    $name = '';
	$name = Yii::app()->request->getParam('name');
	$email = Yii::app()->request->getParam('email');

	if((isset($email) && !empty($email))) {

        $check_already_subscribe = NewsletterSubscribers::model()->findByAttributes(array('email'=>$email));

        if(count($check_already_subscribe) > 0){
          $result= 'false';
	$response= 'You are already subscribed to our mailing list';
        }

        else{

        	$result= 'true';
	$response= 'You have successfully subscribed to our mailing list';

            $check_already_customer = Customers::model()->findByAttributes(array('email'=>$email));

            if(count($check_already_customer) > 0) $name = $check_already_customer->customername;

            $data = array(
					'name'=> $name,
					'email'=> $email,
					'subscription_status'=> 1,
					'subscription_date'=> date("Y-m-d H:i:s")
				);

				$model = new NewsletterSubscribers;
				$model->attributes= $data;
				$model->save(false);

                $from = Vargas::Obj()->getAdminEmail();
					//echo $from;
                    if(!$name) $name = 'Subscriber';
					$subject = 'Thank you for subscribing with Mobile Wash!';
                    $message = "<h2>Dear ".$name."!</h2>
               <p style='color: #333;'>Thank you for subscribing with <b style='color: #000;'>MobileWash.</b></p>

               <p style='height: 0px;'>&nbsp;</p>
               <p style='color: #333;'><b>kind Regards,</b></p>
               <p style='color: #333; margin: 0; margin-bottom: 5px;'><b>The Mobilewash Team</b></p>
               <p style='color: blue; margin: 0; margin-bottom: 5px;'>www.mobilewash.com</p>
               <p style='color: blue; margin: 0; margin-bottom: 5px;'>support@mobilewash.com</p>";

					Vargas::Obj()->SendMail($email,$from,$message,$subject);

        }

		}
			$json= array(
				'result'=> $result,
				'response'=> $response
			);
		echo json_encode($json);
	}

public function actionadminorderassign(){

        $agent_id = Yii::app()->request->getParam('agent_id');
        $wash_id = Yii::app()->request->getParam('wash_request_id');
        $json = array();
        $result= 'false';
        $response= 'Pass the required parameters';

        if((isset($agent_id) && !empty($agent_id)) && (isset($wash_id) && !empty($wash_id))){
        $agents_id_check = Agents::model()->findByAttributes(array("id"=>$agent_id));
        $wash_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_id));


        if(!count($agents_id_check)){
          $result= 'false';
        $response= 'Invalid agent id';
        }

        else if(!count($wash_id_check)){
          $result= 'false';
        $response= 'Invalid wash id';
        }

        else{
             $result= 'true';
        $response= 'order assigned';

        $id_assign_check = Washingrequests::model()->updateByPk($wash_id, array( 'order_temp_assigned' => $agent_id, 'agent_reject_ids'=>'' ));

        }
        }

         $json = array(
            'result'=> $result,
            'response'=> $response,
        );

        echo json_encode($json); die();

    }

public function actionsearchagentsclients() {
$search_query = '';
$search_query = Yii::app()->request->getParam('search_query');
$result= 'false';
$response = 'nothing found';
$agents_arr = array();
$clients_arr = array();

$q = new CDbCriteria();
$q->addcondition("(CONCAT(first_name, ' ', last_name) LIKE '%".$search_query."%')");
$q2 = new CDbCriteria();
$q2->addcondition("(customername LIKE '%".$search_query."%') AND online_status = 'online'");

$findagents = Agents::model()->findAll( $q );
$findclients = Customers::model()->findAll( $q2 );

if(count($findagents) || count($findclients)){

$result= 'true';
$response = 'search results';

if(count($findagents)){
foreach($findagents as $key=>$agent){
$agents_arr[$key]['id'] = $agent['id'];
$agents_arr[$key]['name'] = $agent['first_name']." ".$agent['last_name'];
$agents_arr[$key]['status'] = $agent['status'];
}
}

if(count($findclients)){
foreach($findclients as $key=>$client){
$clients_arr[$key]['id'] = $client['id'];
$clients_arr[$key]['name'] = $client['customername'];
}
}

}


$json= array(
				'result'=> $result,
				'response'=> $response,
'agents' => $agents_arr,
'clients' => $clients_arr
			);
		echo json_encode($json);
}

 public function actionDeleteAdminUser(){
        $id = Yii::app()->request->getParam('id');
        $userdetail = Users::model()->deleteAll('id=:id', array(':id'=>$id));


                $json= array(
                    'result'=> 'true',
                    'response'=> 'successfully delete'
                );
        echo json_encode($json);
        die();
    }

 public function actionManageUser(){
        $userdetail =  Yii::app()->db->createCommand("SELECT * FROM users ORDER BY id DESC ")->queryAll();
        $all_users = array();
        foreach($userdetail as $usr=> $user){
                    $all_users[$usr]['id'] = $user['id'];
                    $all_users[$usr]['email'] = $user['email'];
                    $all_users[$usr]['username'] = $user['username'];
                    $all_users[$usr]['users_type'] = $user['users_type'];
                    $all_users[$usr]['client_action'] = $user['client_action'];
                    $all_users[$usr]['washer_action'] = $user['washer_action'];
                    $all_users[$usr]['company_action'] = $user['company_action'];
                }
                $json= array(
                    'result'=> 'true',
                    'response'=> 'successfully insert',
                    'all_users'=> $all_users
                );
        echo json_encode($json);
        die();
    }


public function actionAddAdminUser(){

        $username = Yii::app()->request->getParam('username');
        $email = Yii::app()->request->getParam('email');
        $password = Yii::app()->request->getParam('password');

        $account_type = Yii::app()->request->getParam('account_status');
        $client_pemission = Yii::app()->request->getParam('client_pemission');
        $washer_permission = Yii::app()->request->getParam('washer_permission');
        $company_permission = Yii::app()->request->getParam('company_permission');

        $insertuser = Yii::app()->db->createCommand("INSERT INTO `users` (`email`, `username`, `password`, `users_type`,  `device_token`, `client_action`, `washer_action`, `company_action`)
VALUES ('$email', '$username', '$password', '$account_type', '', '$client_pemission', '$washer_permission', '$company_permission') ")->execute();

$json= array(
                'result'=> 'true',
                'response'=> 'successfully insert'
            );
        echo json_encode($json);
        die();
    }

  public function actionEditAdminUser(){

        $username = Yii::app()->request->getParam('username');
        $email = Yii::app()->request->getParam('email');
        $password = Yii::app()->request->getParam('password');
        $id = Yii::app()->request->getParam('id');

        $account_type = Yii::app()->request->getParam('account_status');
        $client_pemission = Yii::app()->request->getParam('client_pemission');
        $washer_permission = Yii::app()->request->getParam('washer_permission');
        $company_permission = Yii::app()->request->getParam('company_permission');
        if(!empty($password)){
            $password = md5($password);
        $update_user = Yii::app()->db->createCommand("UPDATE users SET email='$email', username = '$username', password = '$password', users_type = '$account_type', client_action = '$client_pemission', washer_action = '$washer_permission', company_action = '$company_permission' WHERE id = '$id' ")->execute();
        }else{
            $update_user = Yii::app()->db->createCommand("UPDATE users SET email='$email', username = '$username', users_type = '$account_type', client_action = '$client_pemission', washer_action = '$washer_permission', company_action = '$company_permission' WHERE id = '$id' ")->execute();
        }

$json= array(
                'result'=> 'true',
                'response'=> 'successfully update'
            );
        echo json_encode($json);
        die();
    }

 public function actionEditUseraa(){
        $id = Yii::app()->request->getParam('id');
        $userdetail =  Yii::app()->db->createCommand("SELECT * FROM users WHERE id = '$id'")->queryAll();


                $json= array(
                    'result'=> 'true',
                    'response'=> 'successfully insert',
                    'email' => $userdetail[0]['email'],
                    'username' => $userdetail[0]['username'],
                    'users_type' => $userdetail[0]['users_type'],
                    'client_action' => $userdetail[0]['client_action'],
                    'washer_action' => $userdetail[0]['washer_action'],
                    'command_action' => $userdetail[0]['command_action'],
                    'company_action' => $userdetail[0]['company_action']
                );
        echo json_encode($json);
        die();
    }

public function actiongetusertypebytoken(){
        $user_token = Yii::app()->request->getParam('user_token');
        $userdetail =  Yii::app()->db->createCommand("SELECT * FROM users WHERE device_token = '$user_token'")->queryAll();

if(count($userdetail)){


                $json= array(
                    'result'=> 'true',
                    'response'=> 'user type',
                    'users_type' => $userdetail[0]['users_type'],
   );
}
else{
$json= array(
                    'result'=> 'false',
                    'response'=> 'user not found',

   );
}

        echo json_encode($json);
        die();
    }


/*
	** Performs the forgot password.
	** Post Requirement: emailid
	*/
	public function actionforgetpassword(){

		$email= Yii::app()->request->getParam('emailid');
		$json = array();
		if(isset($email) && !empty($email)){
			$user_email_exists = Users::model()->findByAttributes(array("email"=>$email));
			if(count($user_email_exists)>0){
				$token = md5(time());
				$user_email_exists->password_reset_token= $token;
				if($user_email_exists->save(false)){
					$uniqueMail= $email;
					$from = Vargas::Obj()->getAdminEmail();
					$subject = 'MobileWash.com - Reset Your Password';
					$reporttxt = 'https://www.mobilewash.com/admin-new/reset-password.php?action=adrp&token='.$token.'&id='.$user_email_exists->id;
                    $message = "";

                    $message .= "<p style='font-size: 20px;'>Dear ".$user_email_exists->username.",</p>";
                    $message .= "<p>You requested to reset your MobileWash password information. To complete the request, please click the link below.</p>";
                    $message .= "<p><a href='".$reporttxt."' style='color: #016fd0;'>Reset Password Now</a></p>";
                    $message .= "<p>If this was a mistake or you did not authorize this request you may disregard this email.</p>";
                    $message .= "<p style='font-size: 20px;line-height: 30px;margin-top: 30px;'>Kind Regards,<br>";
                    $message .= "The MobileWash Team<br><a href='https://www.mobilewash.com' style='font-size: 16px; color: #016fd0;'>www.mobilewash.com</a><br><a href='mailto:support@mobilewash.com' style='font-size: 16px; color: #016fd0;'>support@mobilewash.com</a></p>";

					Vargas::Obj()->SendMail($uniqueMail,$from,$message,$subject);
					$json= array(
						'result'=> 'true',
						'response'=> 'Reset password email has been sent to your email account',
					);
				}else{
					$json= array(
						'result'=> 'false',
						'response'=> 'Internal Error',
					);
				}
			}else{
				$json= array(
					'result'=> 'false',
					'response'=> 'Email does not exists',
				);
			}
		}else{
			$json = array(
				'result'=> 'false',
				'response'=> 'Fillup required fields'
			);
		}
		echo json_encode($json);die();
	}

    public function actionresetpassword() {
       $token = Yii::app()->request->getParam('token');
	   $id = Yii::app()->request->getParam('id');
       $new_password = Yii::app()->request->getParam('newpassword');
       $cnfpassword = Yii::app()->request->getParam('cnfpassword');
       	$json= array();
        $result = 'false';
        $response = 'Fillup required fields';
       	if((isset($token) && !empty($token)) && (isset($id) && !empty($id)) && (isset($new_password) && !empty($new_password)) && (isset($cnfpassword) && !empty($cnfpassword))){
       	$user_email_exists = Users::model()->findByAttributes(array("password_reset_token"=>$token,"id"=>$id));
			if(!count($user_email_exists)) {
				$result= 'false';
				$response= "Sorry can't reset your password. Please check password reset link.";
			}

            else if(empty($new_password)){
                $result= 'false';
					$response = "Password can not be empty.";
			}

            else if($new_password!=$cnfpassword){
                $result= 'false';
              $response = "New Password and Confirm Password does not match.";
            }

            else{
               	$update_password = Users::model()->updateAll(array('password'=>md5($new_password)),'id=:id',array(':id'=>$id));
				$result = 'true';
				$response = 'Password updated successfully';

            }
            }

            $json = array(
				'result'=> $result,
				'response'=> $response
			);

            echo json_encode($json);die();

    }


public function actiongetallusers(){
        $all_users = array();

        $result= 'false';
		$response= 'none';

        $users_exists = Yii::app()->db->createCommand()->select('*')->from('users')->order('id ASC')->queryAll();

        if(count($users_exists)>0){
           $result= 'true';
		    $response= 'all users';

            foreach($users_exists as $ind=>$user){

                $all_users[$ind]['id'] = $user['id'];
 $all_users[$ind]['email'] = $user['email'];
 $all_users[$ind]['username'] = $user['username'];
$all_users[$ind]['usertype'] = $user['users_type'];

            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'users'=> $all_users
		);
		echo json_encode($json);

    }


public function actionadduser(){

		$result= 'false';
		$response= 'Fill up required fields';

        $email = Yii::app()->request->getParam('email');
		$username = Yii::app()->request->getParam('username');
		$password = Yii::app()->request->getParam('password');
$usertype = Yii::app()->request->getParam('usertype');

		if((isset($email) && !empty($email)) &&
			(isset($username) && !empty($username)) &&
			(isset($password) && !empty($password)) &&
(isset($usertype) && !empty($usertype)))
			 {

 $email_exists = Users::model()->findByAttributes(array("email"=>$email));
              if(count($email_exists)){
                 $response = "User already exists";
              }
else{


                   $data= array(
					'email'=> $email,
					'username'=> $username,
					'password'=> md5($password),
'users_type'=> $usertype
);

				    $model=new Users;
				    $model->attributes= $data;
				    if($model->save(false)){



                    	$result= 'true';
		$response= 'User added successfully';
                }
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiondeleteuser(){

         $result= 'false';
		$response= 'Please provide user id';

		$id = Yii::app()->request->getParam('id');



		if((isset($id) && !empty($id)))
		{

            $user_exists = Users::model()->findByAttributes(array("id"=>$id));
              if(!count($user_exists)){
                 $response = "Invalid user id";
              }


           else{
				$response = "User deleted";
                $result = 'true';

                  Users::model()->deleteByPk(array('id'=>$id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }


 public function actiongetuserbyid(){

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');


		if((isset($id) && !empty($id)))
			 {

             $user_check = Users::model()->findByAttributes(array("id"=>$id));

             	if(!count($user_check)){
                   	$result= 'false';
		$response= "User doesn't exists";
                }

                else{


                   $data= array(
					'email'=> $user_check->email,
					'username'=> $user_check->username,
'usertype'=> $user_check->users_type,

				);


                    	$result= 'true';
		$response= 'user details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'user_details'=> $data
		);
		echo json_encode($json);
	}


public function actionedituser(){

		$result= 'false';
		$response= 'Fill up required fields';

 $id = Yii::app()->request->getParam('id');
        $username = Yii::app()->request->getParam('username');
$usertype = Yii::app()->request->getParam('usertype');
		if(Yii::app()->request->getParam('password')) $password = md5(Yii::app()->request->getParam('password'));

		if((isset($id) && !empty($id)))

			 {

$user_check = Users::model()->findByAttributes(array("id"=>$id));

             	if(!count($user_check)){
                   	$result= 'false';
		$response= "User doesn't exists";
                }
else{

 if(!Yii::app()->request->getParam('password')){
$password = $user_check->password;
}



                   $data= array(

					'username'=> $username,
					'password'=> $password,
'users_type'=> $usertype,

				);


				   $resUpdate = Yii::app()->db->createCommand()->update('users', $data,"id='".$id."'");

                    	$result= 'true';
		$response= 'User updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


 public function actionadminschedulewashprocesspayment_old(){
      $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $agent_id = Yii::app()->request->getParam('agent_id');
$tip = Yii::app()->request->getParam('tip');
$spdisc = Yii::app()->request->getParam('spdisc');

      $response = "Pass the required parameters";
      $result = "false";

      if((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($agent_id) && !empty($agent_id))){
           $customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);
           $agent_check = Agents::model()->findByPk($agent_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

           else if(!count($agent_check)){
                    $response = "Invalid agent id";
                    $result = "false";
           }
           else{
               if(!$customer_check->braintree_id){
                  $json = array(
                'result'=> 'false',
                'response'=> 'customer braintree id not found',
            );

            echo json_encode($json);
            die();

               }
                if($customer_check->client_position == 'real') $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);
else $Bresult = Yii::app()->braintree->getCustomerById($customer_check->braintree_id);
                $token = '';
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
                else{
                       $json = array(
                'result'=> 'false',
                'response'=> 'No payment methods found'
            );

            echo json_encode($json);
            die();

                }

                if(!$token){
                    $json = array(
                    'result'=> 'false',
                    'response'=> 'No default payment method found'
                    );

                    echo json_encode($json);
                    die();
                }

                  if(!$agent_check->bt_submerchant_id){
                    $json = array(
                    'result'=> 'false',
                    'response'=> 'agent braintree id not found'
                    );

                    echo json_encode($json);
                    die();
                }


               $handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
$data = array('wash_request_id' => $wash_request_id);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$kartresult = curl_exec($handle);
curl_close($handle);
$kartdetails = json_decode($kartresult);


if($wash_check->schedule_total) {

$company_fee = $wash_check->schedule_company_total;
$netprice = $wash_check->schedule_total;
if($spdisc) {
$company_fee -= $spdisc;
$netprice -= $spdisc;
}

if($tip){
$company_fee += $tip * .20;
$netprice += $tip;
}

$request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True]];
}
else  {

$company_fee = $kartdetails->company_total;
$netprice = $kartdetails->net_price;
if($spdisc) {
$company_fee -= $spdisc;
$netprice -= $spdisc;
}

if($tip){
$company_fee += $tip * .20;
$netprice += $tip;
}

$request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True]];
}
                     if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
else $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                    //print_r($payresult);
                    //die();
                    if($payresult['success'] == 1) {

                        //print_r($result);die;
                        $response = "Payment successful";
                        $result = "true";


                        Washingrequests::model()->updateByPk($wash_request_id, array('transaction_id' => $payresult['transaction_id'], 'payment_type' => 'Credit Card', 'status' => 4));

                        $curr_wash_points =  $customer_check->fifth_wash_points;
            $order_cars = explode("|", $wash_check->scheduled_cars_info);
            $total_cars = count($order_cars);

            for($i = 1; $i <= $total_cars; $i++){
               $curr_wash_points++;
               if($curr_wash_points >= 5) $curr_wash_points = 0;
            }

if($curr_wash_points == 0) $curr_wash_points = 'zero';

            $handle = curl_init("https://www.mobilewash.com/api/index.php?r=customers/profileupdate");
curl_setopt($handle, CURLOPT_POST, true);
if($customer_check->is_first_wash == 0) $data = array('customerid' => $customer_id, 'fifth_wash_points' => $curr_wash_points, 'is_first_wash' => 1);
else $data = array('customerid' => $customer_id, 'fifth_wash_points' => $curr_wash_points);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
curl_exec($handle);
curl_close($handle);
//$jsondata = json_decode($result);

                    } else {
                        $result = "false";
                        $response = $payresult['message'];
                    }


           }

      }

        $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

 }



  public function actionadminschedulewashprocesspayment(){
      $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $agent_id = Yii::app()->request->getParam('agent_id');
$tip = Yii::app()->request->getParam('tip');
$spdisc = Yii::app()->request->getParam('spdisc');

      $response = "Pass the required parameters";
      $result = "false";

      if((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($agent_id) && !empty($agent_id))){
           $customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);
           $agent_check = Agents::model()->findByPk($agent_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

           else if(!count($agent_check)){
                    $response = "Invalid agent id";
                    $result = "false";
           }
           else{
               if(!$customer_check->braintree_id){
                  $json = array(
                'result'=> 'false',
                'response'=> 'customer braintree id not found',
            );

            echo json_encode($json);
            die();

               }
                if($customer_check->client_position == 'real') $Bresult = Yii::app()->braintree->getCustomerById_real($customer_check->braintree_id);
else $Bresult = Yii::app()->braintree->getCustomerById($customer_check->braintree_id);
                $token = '';
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
                else{
                       $json = array(
                'result'=> 'false',
                'response'=> 'No payment methods found'
            );

            echo json_encode($json);
            die();

                }

                if(!$token){
                    $json = array(
                    'result'=> 'false',
                    'response'=> 'No default payment method found'
                    );

                    echo json_encode($json);
                    die();
                }

                  if(!$agent_check->bt_submerchant_id){
                    $json = array(
                    'result'=> 'false',
                    'response'=> 'agent braintree id not found'
                    );

                    echo json_encode($json);
                    die();
                }


               $handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
$data = array('wash_request_id' => $wash_request_id);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$kartresult = curl_exec($handle);
curl_close($handle);
$kartdetails = json_decode($kartresult);


if($wash_check->schedule_total) {

$company_fee = $wash_check->schedule_company_total;
$netprice = $wash_check->schedule_total;
if($spdisc) {
$company_fee -= $spdisc;
$netprice -= $spdisc;
}

if($tip){
$company_fee += $tip * .20;
$netprice += $tip;
}

if($wash_check->vip_coupon_code) $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => 'fg2rrvr', 'options' => ['submitForSettlement' => True, 'holdInEscrow' => true]];
else $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True, 'holdInEscrow' => true]];
}
else  {

$company_fee = $kartdetails->company_total;
$netprice = $kartdetails->net_price;
if($spdisc) {
$company_fee -= $spdisc;
$netprice -= $spdisc;
}

if($tip){
$company_fee += $tip * .20;
$netprice += $tip;
}

if($wash_check->vip_coupon_code) $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => 'fg2rrvr', 'options' => ['submitForSettlement' => True, 'holdInEscrow' => true]];
else $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $company_fee, 'amount' => $netprice,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True, 'holdInEscrow' => true]];
}
                     if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
else $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                    //print_r($payresult);
                    //die();
   if($wash_check->vip_coupon_code && $wash_check->schedule_total_vip > 0){
     if($customer_check->client_position == 'real') $payresult2 = Yii::app()->braintree->sale_real(['amount' => $wash_check->schedule_total_vip,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True]]);
else $payresult2 = Yii::app()->braintree->sale(['amount' => $wash_check->schedule_total_vip,'paymentMethodToken' => $token, 'options' => ['submitForSettlement' => True]]);

if(!$payresult2['success']) {
      $json = array(
      'result'=> 'false',
                'response'=> $payresult2['message']
            );

        echo json_encode($json);
        die();
}
else{
      Washingrequests::model()->updateByPk($wash_request_id, array('vip_transaction_id' => $payresult2['transaction_id']));
}
}


                    if($payresult['success'] == 1) {

                        //print_r($result);die;
                        $response = "Payment successful";
                        $result = "true";


                        Washingrequests::model()->updateByPk($wash_request_id, array('transaction_id' => $payresult['transaction_id'], 'payment_type' => 'Credit Card', 'status' => 4, 'escrow_status' => $payresult['escrow_status']));

                        $curr_wash_points =  $customer_check->fifth_wash_points;
            $order_cars = explode("|", $wash_check->scheduled_cars_info);
            $total_cars = count($order_cars);

            for($i = 1; $i <= $total_cars; $i++){
               $curr_wash_points++;
               if($curr_wash_points >= 5) $curr_wash_points = 0;
            }

if($curr_wash_points == 0) $curr_wash_points = 'zero';

            $handle = curl_init("https://www.mobilewash.com/api/index.php?r=customers/profileupdate");
curl_setopt($handle, CURLOPT_POST, true);
if($customer_check->is_first_wash == 0) $data = array('customerid' => $customer_id, 'fifth_wash_points' => $curr_wash_points, 'is_first_wash' => 1);
else $data = array('customerid' => $customer_id, 'fifth_wash_points' => $curr_wash_points);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
curl_exec($handle);
curl_close($handle);
//$jsondata = json_decode($result);

                    } else {
                        $result = "false";
                        $response = $payresult['message'];
                    }


           }

      }

        $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

 }


  public function actionreleaseescrow(){
 $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $transaction_id = Yii::app()->request->getParam('transaction_id');
       $response = "Pass the required parameters";
      $result = "false";

       if((isset($transaction_id) && !empty($transaction_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))){

$customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{
          if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->releaseFromEscrow_real($transaction_id);
else $payresult = Yii::app()->braintree->releaseFromEscrow($transaction_id);

          if($payresult['success'] == 1) {
                        //print_r($result);die;
                        $response = "escrow release successful";
                        $result = "true";
 Washingrequests::model()->updateByPk($wash_request_id, array('escrow_status' => $payresult['escrow_status']));

}
else {
                        $result = "false";
                        $response = $payresult['message'];
                    }
}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

}


public function actionvoidpayment(){
 $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $transaction_id = Yii::app()->request->getParam('transaction_id');
       $response = "Pass the required parameters";
      $result = "false";

       if((isset($transaction_id) && !empty($transaction_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))){

$customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{
          if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->void_real($transaction_id);
else $payresult = Yii::app()->braintree->void($transaction_id);

          if($payresult['success'] == 1) {
                        //print_r($result);die;
                        $response = "payment void successful";
                        $result = "true";
 Washingrequests::model()->updateByPk($wash_request_id, array('transaction_id' => ''));

}
else {
                        $result = "false";
                        $response = $payresult['message'];
                    }
}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

}


public function actionrefundpayment(){
 $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $transaction_id = Yii::app()->request->getParam('transaction_id');
       $response = "Pass the required parameters";
      $result = "false";

       if((isset($transaction_id) && !empty($transaction_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))){

$customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{
          if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->refund_real($transaction_id);
else $payresult = Yii::app()->braintree->refund($transaction_id);

          if($payresult['success'] == 1) {
                        //print_r($result);die;
                        $response = "payment refund successful";
                        $result = "true";
 Washingrequests::model()->updateByPk($wash_request_id, array('transaction_id' => ''));

}
else {
                        $result = "false";
                        $response = $payresult['message'];
                    }
}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

}


public function actiongettransactionbyid(){
 $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
      $transaction_id = Yii::app()->request->getParam('transaction_id');
       $response = "Pass the required parameters";
      $result = "false";
$transaction_details = array();

       if((isset($transaction_id) && !empty($transaction_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))){

$customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{
          if($customer_check->client_position == 'real') $payresult = Yii::app()->braintree->getTransactionById_real($transaction_id);
else $payresult = Yii::app()->braintree->getTransactionById($transaction_id);

          if($payresult['success'] == 1) {
                        //print_r($result);die;
                        $response = "transaction_details";
                        $result = "true";
$transaction_details['status'] = $payresult['status'];
$transaction_details['escrow_status'] = $payresult['escrow_status'];

}
else {
                        $result = "false";
                        $response = $payresult['message'];
                    }
}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response,
'transaction_details' => $transaction_details
            );

        echo json_encode($json);
        die();

}


public function actionsendclientwebreceipt(){
 $customer_id = Yii::app()->request->getParam('customer_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
       $response = "Pass the required parameters";
      $result = "false";

       if((isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))){

$customer_check = Customers::model()->findByPk($customer_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($customer_check)){
                    $response = "Invalid customer id";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{

            $wash_details = Washingrequests::model()->findByPk($wash_request_id);

					$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$sched_date = '';
					if(strtotime($wash_details->schedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';
					}
					else{
						$sched_date = date('M d', strtotime($wash_details->schedule_date));
					}
					$message = '';
					$subject = 'Order Receipt - #0000'.$wash_request_id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
					$message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is scheduled for ".$sched_date." @ ".$wash_details->schedule_time."</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$wash_details->address."</p>";
					$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>".$customer_check->customername."</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000".$wash_request_id."</td></tr>
					</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
					$all_cars = explode("|", $wash_details->scheduled_cars_info);
					foreach($all_cars as $ind=>$vehicle){
						$car_details = explode(",", $vehicle);

						$message .="<tr>
						<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
						<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
						<tr>
						<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$car_details[0]." ".$car_details[1]."</p></td>
						<td style='text-align: right;'>
						<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".($wash_details->vip_coupon_code != '' ? '0' : $car_details[4])."</p>
						</td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>".$car_details[2]." Package</p></td>
						<td style='text-align: right;'></td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>Handling Fee</p></td>
						<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".($wash_details->vip_coupon_code != '' ? '0' : '1.00')."</p></td>
						</tr>
						";
if($car_details[12]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[12], 2)."</p></td>
							</tr>";

						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[13], 2)."</p></td>
							</tr>";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[14], 2)."</p></td>
							</tr>";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[15], 2)."</p></td>
							</tr>";
						}
						if($car_details[5]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
							</tr>";
						}
						if($car_details[6]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
							</tr>";
						}

						if($car_details[8]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : '1.00')."</p></td>
							</tr>";
						}

						if($car_details[9]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($car_details[9], 2))."</p></td>
							</tr>";
						}

						if($car_details[10]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($car_details[10], 2))."</p></td>
							</tr>";
						}

						$message .= "</table></td></tr>";

					}

if($wash_details->tip_amount > 0){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Tip</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$".number_format($wash_details->tip_amount, 2)."</p></td>
							</tr></table>";
						}

if($wash_details->coupon_discount > 0){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Coupon Discount (".$wash_details->coupon_code.")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($wash_details->coupon_discount, 2))."</p></td>
							</tr></table>";
						}


if($wash_details->vip_coupon_code){

 $vip_coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$wash_details->vip_coupon_code));

							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>".$vip_coupon_check->package_name." (".$wash_details->vip_coupon_code.")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'></p></td>
							</tr></table>";

						}


					$message .= "</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".($wash_details->vip_coupon_code != '' ? $wash_details->schedule_total_vip : $wash_details->schedule_total)."</span></p></td></tr></table>";


					$message .= "<p style='text-align: center; font-size: 18px;'>To re-schedule or cancel visit your account history by<br>logging in to <a href='https://www.mobilewash.com'>Mobilewash.com</a></p>";
					$message .= "<p style='text-align: center; font-size: 20px; margin-bottom: 0;'>*$10 cancelation fee will apply for cancelling<br>within 30 minutes of your scheduled wash time</p>";

					Vargas::Obj()->SendMail($customer_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
					Vargas::Obj()->SendMail("admin@mobilewash.com","info@mobilewash.com",$message,$subject, 'mail-receipt');
                    $result = 'true';
                    $response = 'email sent';

}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

}


public function actionsendagentwebreceipt(){
 $agent_id = Yii::app()->request->getParam('agent_id');
      $wash_request_id = Yii::app()->request->getParam('wash_request_id');
       $response = "Pass the required parameters";
      $result = "false";

       if((isset($wash_request_id) && !empty($wash_request_id)) && (isset($agent_id) && !empty($agent_id))){

$agent_check = Agents::model()->findByPk($agent_id);
           $wash_check = Washingrequests::model()->findByPk($wash_request_id);

           if(!count($agent_check)){
                    $response = "Invalid agent id";
                    $result = "false";
           }

           else if(!$agent_check->email){
                    $response = "Email address not provided";
                    $result = "false";
           }

           else if(!count($wash_check)){
                    $response = "Invalid wash request id";
                    $result = "false";
           }

          else{

            $wash_details = Washingrequests::model()->findByPk($wash_request_id);

					$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$sched_date = '';
					if(strtotime($wash_details->schedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';
					}
					else{
						$sched_date = date('M d', strtotime($wash_details->schedule_date));
					}
					$message = '';
					$subject = 'Order Receipt - #0000'.$wash_request_id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
					$message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is scheduled for ".$sched_date." @ ".$wash_details->schedule_time."</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$wash_details->address."</p>";
					$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>".$agent_check->first_name." ".$agent_check->last_name."</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000".$wash_request_id."</td></tr>
					</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
					$all_cars = explode("|", $wash_details->scheduled_cars_info);
					foreach($all_cars as $ind=>$vehicle){
						$car_details = explode(",", $vehicle);

						$message .="<tr>
						<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
						<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
						<tr>
						<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$car_details[0]." ".$car_details[1]."</p></td>
						<td style='text-align: right;'>";
                        if($car_details[2] == 'Premium') $message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".number_format($car_details[4]*.75, 2)."</p>";
                        else $message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".number_format($car_details[4]*.8, 2)."</p>";
						$message .= "</td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>".$car_details[2]." Package</p></td>
						<td style='text-align: right;'></td>
						</tr>
						";
if($car_details[12]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[12]*.8, 2)."</p></td>
							</tr>";

						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[13]*.8, 2)."</p></td>
							</tr>";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[14]*.8, 2)."</p></td>
							</tr>";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[15]*.8, 2)."</p></td>
							</tr>";
						}
						if($car_details[5]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[5]*.8, 2)."</p></td>
							</tr>";
						}
						if($car_details[6]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[6]*.8, 2)."</p></td>
							</tr>";
						}

						if($car_details[8]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format($car_details[8]*.8, 2)."</p></td>
							</tr>";
						}

						$message .= "</table></td></tr>";

					}

if($wash_details->tip_amount > 0){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Tip</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$".number_format($wash_details->tip_amount*.8, 2)."</p></td>
							</tr></table>";
						}


					$message .= "</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$wash_details->schedule_agent_total."</span></p></td></tr></table>";


					Vargas::Obj()->SendMail($agent_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
					Vargas::Obj()->SendMail("admin@mobilewash.com","info@mobilewash.com",$message,$subject, 'mail-receipt');
                    $result = 'true';
                    $response = 'email sent';

}

       }

      $json = array(
                'result'=> $result,
                'response'=> $response
            );

        echo json_encode($json);
        die();

}


}