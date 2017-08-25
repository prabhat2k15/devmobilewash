<?php
class PhoneOrdersController extends Controller{
	public function actionIndex(){
		$this->render('index');
	}


public function actionaddorder(){

		$result= 'false';
		$response= 'Fill up required fields';

        $customername = Yii::app()->request->getParam('customername');
		$phoneno = Yii::app()->request->getParam('phoneno');
		$address = Yii::app()->request->getParam('address');
$address_type = Yii::app()->request->getParam('address_type');
		$city = Yii::app()->request->getParam('city');
		$schedule_date = Yii::app()->request->getParam('schedule_date');
$schedule_time = Yii::app()->request->getParam('schedule_time');
$email = '';
if(Yii::app()->request->getParam('email')) $email = Yii::app()->request->getParam('email');
$how_hear_mw = '';
if(Yii::app()->request->getParam('how_hear_mw')) $how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
$regular_vehicles = '';
if(Yii::app()->request->getParam('regular_vehicles')) $regular_vehicles = Yii::app()->request->getParam('regular_vehicles');
$classic_vehicles = '';
if(Yii::app()->request->getParam('classic_vehicles')) $classic_vehicles = Yii::app()->request->getParam('classic_vehicles');
$total_price = Yii::app()->request->getParam('total_price');
$agent_total = Yii::app()->request->getParam('agent_total');
$company_total = Yii::app()->request->getParam('company_total');

		if((isset($customername) && !empty($customername)) &&
			(isset($phoneno) && !empty($phoneno)) &&
			(isset($address) && !empty($address)) &&
(isset($address_type) && !empty($address_type)) &&
			(isset($city) && !empty($city)) &&
(isset($schedule_time) && !empty($schedule_time)) &&
			(isset($schedule_date) && !empty($schedule_date)))
			 {

$order_exists = PhoneOrders::model()->findByAttributes(array("phoneno"=>$phoneno));


                   $orderdata= array(
					'customername'=> $customername,
					'phoneno'=> $phoneno,
					'address'=> $address,
'address_type'=> $address_type,
					'city'=> $city,
					'schedule_date'=> $schedule_date,
                    'schedule_time'=> $schedule_time,
                    'email'=> $email,
                    'how_hear_mw'=> $how_hear_mw,
                    'regular_vehicles'=> $regular_vehicles,
                    'classic_vehicles'=> $classic_vehicles,
'total_price' => $total_price,
'agent_total' => $agent_total,
'company_total' => $company_total,
'created_date'=> date('Y-m-d H:i:s')
				);

				    $model=new PhoneOrders;
				    $model->attributes= $orderdata;
				    if($model->save(false)){
                       $order_id = Yii::app()->db->getLastInsertID();
}


                    	$result= 'true';
		$response= 'Order added successfully';


		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
'id' => $order_id
		);
		echo json_encode($json);
	}


public function actiongetallorders(){
		/* Checking for post(day) parameters */
		$order_day = $ord_status = $before_from = '';

		if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
			$day = Yii::app()->request->getParam('day');
			$event = Yii::app()->request->getParam('event');
			$status_qr = '';
			if($event == 'pending'){
				$ord_status = 'pending';
			}elseif($event == 'completed'){
				$ord_status = 'completed';
			}elseif($event == 'processing'){
				$ord_status = 'processing';
			}else{
				$ord_status = 'all';
			}
			$before_from = ",LENGTH(checklist) - LENGTH(REPLACE(checklist, '|', '')) as number";
			$order_day = " WHERE DATE_FORMAT(schedule_date,'%Y-%m-%d')= '$day'";
		}else{
			$ord_status = 'all';
		}
		/* END */

        $all_orders = array();

        $result= 'false';
		$response= 'none';

		/* $qry = "SELECT id,detailer,customername,phoneno,address,city,schedule_date,schedule_time,email,how_hear_mw,regular_vehicles,classic_vehicles,checklist,created_date$before_from FROM phone_orders$order_day ORDER BY schedule_date DESC".'--'.$ord_status;
		$path = '/home/mobilewa/public_html/api/protected/controllers/test.php';
		file_put_contents($path,serialize($qry));
		die; */

		$orders_exists =  Yii::app()->db->createCommand("SELECT id,agent_id, detailer,customername,phoneno,address,city,schedule_date,schedule_time,email,how_hear_mw,regular_vehicles,classic_vehicles,is_cancel,checklist,created_date$before_from FROM phone_orders$order_day ORDER BY schedule_date DESC")->queryAll();

        if(count($orders_exists)>0){
           $result= 'true';
		    $response= 'all orders';

            foreach($orders_exists as $ind=>$order){
				$all_orders[$ind]['number'] = '';
$agent_name = '';
if($order['detailer']) $agent_name = $order['detailer'];
else{
if($order['agent_id']){
$agent_detail = Agents::model()->findByPk($order['agent_id']);
$agent_name = $agent_detail->first_name." ".$agent_detail->last_name;
if($agent_detail->real_washer_id) $agent_name .= " #".$agent_detail->real_washer_id;
}
}

                $all_orders[$ind]['id'] = $order['id'];
				$all_orders[$ind]['detailer'] = $agent_name;
				$all_orders[$ind]['customername'] = $order['customername'];
				$all_orders[$ind]['phoneno'] = $order['phoneno'];
				$all_orders[$ind]['address'] = $order['address'];
				$all_orders[$ind]['city'] = $order['city'];
				$all_orders[$ind]['schedule_date'] = $order['schedule_date'];
				$all_orders[$ind]['schedule_time'] = $order['schedule_time'];
				$all_orders[$ind]['email'] = $order['email'];
				$all_orders[$ind]['how_hear_mw'] = $order['how_hear_mw'];
				$all_orders[$ind]['regular_vehicles'] = $order['regular_vehicles'];
				$all_orders[$ind]['classic_vehicles'] = $order['classic_vehicles'];
				$all_orders[$ind]['checklist'] = $order['checklist'];
				$all_orders[$ind]['created_date'] = $order['created_date'];
				$all_orders[$ind]['number'] = $order['number'];
$all_orders[$ind]['is_cancel'] = $order['is_cancel'];
            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'orders'=> $all_orders,
			'status'=> $ord_status
		);
		echo json_encode($json);

    }


public function actionDeleteOrder(){

         $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');



		if((isset($id) && !empty($id)))
		{

            $order_exists = PhoneOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{
				$response = "Order deleted";
                $result = 'true';

                  PhoneOrders::model()->deleteByPk(array('id'=>$id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }


public function actiongetorderbyid(){

		$result= 'false';
		$response= 'Fill up required fields';
        $id = Yii::app()->request->getParam('id');
$total_price = 0;
$regular_prices = array();
$classic_prices = array();
		if((isset($id) && !empty($id)))
			 {

             $order_check = PhoneOrders::model()->findByAttributes(array("id"=>$id));

             	if(!count($order_check)){
                   	$result= 'false';
		$response= "Order doesn't exists";
                }

                else{

$total_vehicles = 0;
$reg = 0;
$cl = 0;
if($order_check->regular_vehicles) $reg = count(explode("|", $order_check->regular_vehicles));
if($order_check->classic_vehicles) $cl = count(explode("|", $order_check->classic_vehicles));

$total_vehicles = $reg + $cl;

if($order_check->regular_vehicles){
$all_vehs = explode("|", $order_check->regular_vehicles);
foreach($all_vehs as $veh){
$veh_detail = explode(",",$veh);
$vehicle_exists = Yii::app()->db->createCommand()->select('*')->from('all_vehicles')->where("make='".$veh_detail[0]."' AND model ='".$veh_detail[1]."'")->queryAll();
//print_r($vehicle_exists);
 $car_plan = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_exists[0]['type'], "title"=>$veh_detail[2]));
                  $total_price += $car_plan->price;
 $total_price += 1;
if($total_vehicles > 1) $total_price -= 1;
$total_price -= $veh_detail[3];

array_push($regular_prices, $car_plan->price);

}
}

if($order_check->classic_vehicles){
$all_vehs = explode("|", $order_check->classic_vehicles);
foreach($all_vehs as $veh){
$veh_detail = explode(",",$veh);
$vehicle_exists = Yii::app()->db->createCommand()->select('*')->from('all_classic_vehicles')->where("make='".$veh_detail[0]."' AND model ='".$veh_detail[1]."'")->queryAll();
//print_r($vehicle_exists);
 $car_plan = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_exists[0]['type'], "title"=>$veh_detail[2]));
                  $total_price += $car_plan->price;
 $total_price += 1;
if($total_vehicles > 1) $total_price -= 1;
$total_price -= $veh_detail[3];
array_push($classic_prices, $car_plan->price);

}
}

if($order_check->total_price) $total_price = $order_check->total_price;

if($order_check->order_discount) $net_price = number_format($total_price - $order_check->order_discount, 2);
else $net_price = $total_price;



                   $data= array(
					'customername'=> $order_check->customername,
					'phoneno'=> $order_check->phoneno,
'address'=> $order_check->address,
'address_type'=> $order_check->address_type,
'city'=> $order_check->city,
'schedule_date'=> $order_check->schedule_date,
'schedule_time'=> $order_check->schedule_time,
'email'=> $order_check->email,
'how_hear_mw'=> $order_check->how_hear_mw,
'total_vehicles' => $total_vehicles,
'regular_vehicles'=> $order_check->regular_vehicles,
'classic_vehicles'=> $order_check->classic_vehicles,
'detailer'=> $order_check->detailer,
'notes'=> $order_check->notes,
'agent_id'=> $order_check->agent_id,
'checklist'=> $order_check->checklist,
'total_price' => $total_price,
'net_price' => $net_price,
'agent_total' => $order_check->agent_total,
'company_total' => $order_check->company_total,
'regular_prices' => $regular_prices,
'classic_prices' => $classic_prices,
'payment_status' => $order_check->payment_status,
'order_discount' => $order_check->order_discount,
'tip_amount' => $order_check->tip_amount,
'transaction_id' => $order_check->transaction_id,
'is_property_allowed' => $order_check->is_property_allowed,
'is_payment_processed' => $order_check->is_payment_processed,
'is_cancel' => $order_check->is_cancel
				);


                    	$result= 'true';
		$response= 'Order details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'order_details'=> $data
		);
		echo json_encode($json);
	}


public function actioneditorder(){

		$result= 'false';
		$response= 'Fill up required fields';

$id = Yii::app()->request->getParam('id');

$customername = Yii::app()->request->getParam('customername');
		$phoneno = Yii::app()->request->getParam('phoneno');
		$address = Yii::app()->request->getParam('address');
$address_type = Yii::app()->request->getParam('address_type');
		$city = Yii::app()->request->getParam('city');
		$schedule_date = Yii::app()->request->getParam('schedule_date');
$schedule_time = Yii::app()->request->getParam('schedule_time');

$email = Yii::app()->request->getParam('email');

$how_hear_mw = Yii::app()->request->getParam('how_hear_mw');

$regular_vehicles = Yii::app()->request->getParam('regular_vehicles');

$classic_vehicles = Yii::app()->request->getParam('classic_vehicles');
$agent_id = Yii::app()->request->getParam('agent_id');
$detailer = Yii::app()->request->getParam('detailer');
$notes = Yii::app()->request->getParam('notes');
$checklist = Yii::app()->request->getParam('checklist');
$payment_status = Yii::app()->request->getParam('payment_status');
$transaction_id = Yii::app()->request->getParam('transaction_id');
$order_discount = Yii::app()->request->getParam('order_discount');
$tip_amount = Yii::app()->request->getParam('tip_amount');
$is_cancel = Yii::app()->request->getParam('is_cancel');
$total_price = Yii::app()->request->getParam('total_price');
$agent_total = Yii::app()->request->getParam('agent_total');
$company_total = Yii::app()->request->getParam('company_total');
$is_property_allowed = Yii::app()->request->getParam('is_property_allowed');
$is_payment_processed = Yii::app()->request->getParam('is_payment_processed');

		if((isset($id) && !empty($id)))

			 {

$order_check = PhoneOrders::model()->findByAttributes(array("id"=>$id));

             	if(!count($order_check)){
                   	$result= 'false';
		$response= "Order doesn't exists";
                }
else{

 if(!$customername){
$customername = $order_check->customername;
}

 if(!$phoneno){
$phoneno = $order_check->phoneno;
}


 if(!$address){
$address = $order_check->address;
}

 if(!$address_type){
$address_type = $order_check->address_type;
}

 if(!$city){
$city = $order_check->city;
}

 if(!$schedule_date){
$schedule_date = $order_check->schedule_date;
}

 if(!$schedule_time){
$schedule_time = $order_check->schedule_time;
}

if(!$email){
$email = $order_check->email;
}

if(!$how_hear_mw){
$how_hear_mw = $order_check->how_hear_mw;
}

if(!$regular_vehicles){
$regular_vehicles = $order_check->regular_vehicles;
}

if(!$classic_vehicles){
$classic_vehicles = $order_check->classic_vehicles;
}


if(!$agent_id){
$agent_id = $order_check->agent_id;
}

if(!$detailer){
$detailer = $order_check->detailer;
}

if(!$notes){
$notes = $order_check->notes;
}

if(!$checklist){
$checklist = $order_check->checklist;
}

if(!$payment_status){
$payment_status = $order_check->payment_status;
}

if(!$transaction_id){
$transaction_id = $order_check->transaction_id;
}

if(!$order_discount){
$order_discount = $order_check->order_discount;
}

if(!$tip_amount){
$tip_amount = $order_check->tip_amount;
}

if(!$is_cancel){
$is_cancel = $order_check->is_cancel;
}

if(!$total_price){
$total_price = $order_check->total_price;
}

if(!$agent_total){
$agent_total = $order_check->agent_total;
}

if(!$company_total){
$company_total = $order_check->company_total;
}

if(!$is_property_allowed){
$is_property_allowed = $order_check->is_property_allowed;
}

if(!$is_payment_processed){
$is_payment_processed = $order_check->is_payment_processed;
}

if($agent_id == 'none') $agent_id = 0;


                   $data= array(
					'customername'=> $customername,
					'phoneno'=> $phoneno,
					'address'=> $address,
'address_type'=> $address_type,
'city'=> $city,
'schedule_date'=> $schedule_date,
'schedule_time'=> $schedule_time,
'email'=> $email,
'how_hear_mw'=> $how_hear_mw,
'regular_vehicles'=> $regular_vehicles,
'classic_vehicles'=> $classic_vehicles,
'agent_id'=> $agent_id,
'detailer'=> $detailer,
'notes'=> $notes,
'checklist'=> $checklist,
'payment_status'=> $payment_status,
'transaction_id'=> $transaction_id,
'order_discount'=> $order_discount,
'is_cancel'=> $is_cancel,
'total_price' => $total_price,
'agent_total' => $agent_total,
'company_total' => $company_total,
'is_property_allowed' => $is_property_allowed,
'is_payment_processed' => $is_payment_processed,
'tip_amount' => $tip_amount
				);


				   $resUpdate = Yii::app()->db->createCommand()->update('phone_orders', $data,"id='".$id."'");

                    	$result= 'true';
		$response= 'Order updated successfully';
}
}


		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}

	/*
	* FUNCTION FOR ALL ORDER COUNT WHICH ARE SHOWING ON CALENDAR
	* ONCE CLICK ON ALL ORDER BUTTON.
	*/
	public function Actionmerge_all_orders(){
		/* Checking for post(month) parameters */
		$order_month='';
		if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
			$last_month = Yii::app()->request->getParam('start');
			$curr_month = Yii::app()->request->getParam('end');
			$order_month = " AND ( DATE_FORMAT(a.created_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(a.created_date,'%Y-%m')<= '$curr_month')";
			$order_month_phone = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
			$order_month_sch = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
		}
		/* Post END */

		/* $path = '/home/mobilewa/public_html/api/protected/controllers/test.php';
		file_put_contents($path,serialize($phone_orders)); */
		
		$all_orders = $finalArray = $all_orders_phone = $all_orders_schd = array();

        $result= 'false';
		$response= 'none';
		
		/* web orderds */
		$total_order =  Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];
	
		
        $schedule_order =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND wash_request_position = 'real' ORDER BY created_date DESC")->queryAll();

		/* END */
		
		/* Phone Orders */
		$phone_orders = array();
		/* echo "SELECT * FROM phone_orders$order_month_phone ORDER BY schedule_date DESC";die; */
		$orders_exists =  Yii::app()->db->createCommand("SELECT * FROM phone_orders$order_month_phone ORDER BY schedule_date DESC")->queryAll();
		
		if(count($orders_exists)>0){
			$result= 'true';
		    $response= 'all orders';

            foreach($orders_exists as $ind=>$order){
				//$all_orders_phone[$ind]['number'] = '';
				$agent_name = '';
				if($order['detailer']) $agent_name = $order['detailer'];
				else{
					if($order['agent_id']){
						$agent_detail = Agents::model()->findByPk($order['agent_id']);
						$agent_name = $agent_detail->first_name." ".$agent_detail->last_name;
						if($agent_detail->real_washer_id) $agent_name .= " #".$agent_detail->real_washer_id;
					}
				}

                /* $all_orders_phone[$ind]['id'] = $order['id'];
				$all_orders_phone[$ind]['detailer'] = $agent_name;
				$all_orders_phone[$ind]['customername'] = $order['customername'];
				$all_orders_phone[$ind]['phoneno'] = $order['phoneno'];
				$all_orders_phone[$ind]['address'] = $order['address'];
				$all_orders_phone[$ind]['city'] = $order['city'];
				$all_orders_phone[$ind]['schedule_date'] = $order['schedule_date'];
				$all_orders_phone[$ind]['schedule_time'] = $order['schedule_time'];
				$all_orders_phone[$ind]['email'] = $order['email'];
				$all_orders_phone[$ind]['how_hear_mw'] = $order['how_hear_mw'];
				$all_orders_phone[$ind]['regular_vehicles'] = $order['regular_vehicles'];
				$all_orders_phone[$ind]['classic_vehicles'] = $order['classic_vehicles'];
				$all_orders_phone[$ind]['checklist'] = $order['checklist'];
				$all_orders_phone[$ind]['created_date'] = $order['created_date'];
				$all_orders_phone[$ind]['number'] = $order['number'];
				$all_orders_phone[$ind]['is_cancel'] = $order['is_cancel'];
				$all_orders_phone[$ind]['type'] = 'phone-order'; */
				
				
				//customer_id, customer_name, customer_email, customer_phoneno, address, schedule_date, schedule_time, scheduled_cars_info, checklist, created_date
				$all_orders_phone[$ind]['id'] = $order['id'];
				$all_orders_phone[$ind]['customer_id'] = $order['id'];
				$all_orders_phone[$ind]['customer_name'] = $order['customername'];
				$all_orders_phone[$ind]['customer_email'] = $order['email'];
				$all_orders_phone[$ind]['customer_phoneno'] = $order['phoneno'];
				$all_orders_phone[$ind]['address'] = $order['address'];
				$all_orders_phone[$ind]['schedule_date'] = $order['schedule_date'];
				$all_orders_phone[$ind]['schedule_time'] = $order['schedule_time'];
				$all_orders_phone[$ind]['checklist'] = $order['checklist'];
				$all_orders_phone[$ind]['status'] = '';
				$all_orders_phone[$ind]['created_date'] = $order['created_date'];
				$all_orders_phone[$ind]['vehicles'] = array(
					'regular_vehicles' => $order['regular_vehicles'],
					'classic_vehicles' => $order['classic_vehicles']
				);
				$all_orders_phone[$ind]['is_cancel'] = $order['is_cancel'];
				$all_orders_phone[$ind]['type'] = 'phone-order';
				$all_orders_phone[$ind]['scheduled_cars_info'] = '';
            }

        }
		/* END */
        $package_name = array();
        $car_id = array();
		if(count($schedule_order)>0){

            foreach($schedule_order as $wrequest)
            {

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

				/* $pendingwashrequests[] = array('id'=>$wrequest['id'],
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
					'type' => 'schedule-order'
                ); */
				//customer_id, customer_name, customer_email, customer_phoneno, address, schedule_date, schedule_time, scheduled_cars_info, checklist, created_date
				
				$pendingwashrequests[] = array(
					'id'=>$wrequest['id'],
					'customer_id'=>$wrequest['customer_id'],
					'customer_name'=>$cust_details->customername,
					'customer_email'=>$cust_details->email,
					'customer_phoneno'=>$cust_details->contact_number,
					'address'=>$wrequest['address'],
					'schedule_date'=>$wrequest['schedule_date'],
					'schedule_time'=>$wrequest['schedule_time'],
					'checklist'=>$wrequest['checklist'],
					'created_date'=>$wrequest['created_date'],
					'status'=>$wrequest['status'],
					'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
					'vehicles'=> $vehicles,
					'type' => 'schedule-order',
					'is_cancel' => ''
				);

            }
        }
		if( count($orders_exists)>0 && count($schedule_order)>0){
			
			function _sortArray($a, $b){
				$ad = strtotime($a['schedule_date']);
				$bd = strtotime($b['schedule_date']);
				return ($bd-$ad);
			}
			$all_orders = array_merge($pendingwashrequests, $all_orders_phone);
			usort($all_orders, '_sortArray');
			$finalArray = $all_orders;
			
			//echo'<pre>';print_r($finalArray);
		}
		
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'orders'=> $finalArray
		);
		echo json_encode($json);
	}
	public function Actionview_agent_wash(){
		/* Checking for post(month) parameters */
		$agent_id = Yii::app()->request->getParam('agent_id');
		$agent_id_check = Agents::model()->findByAttributes(array('id'=>$agent_id));
		$all_orders = $finalArray = $all_orders_phone = $all_orders_schd = array();
		if(!count($agent_id_check)){
			$result= 'false';
			$response= 'Invalid agent id';
		}else{
		
			$order_month='';
			if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
				$last_month = Yii::app()->request->getParam('start');
				$curr_month = Yii::app()->request->getParam('end');
				$order_month = " AND ( DATE_FORMAT(a.created_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(a.created_date,'%Y-%m')<= '$curr_month')";
				$order_month_phone = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
				$order_month_sch = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
			}
			/* Post END */

			
			

			$result= 'true';
			$response= 'none';
			
			/* web orderds */
			/* $total_order =  Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

			$count = $total_order[0]['countid']; */
		
			//echo "SELECT * FROM washing_requests WHERE agent_id = $agent_id AND is_scheduled = 1 AND wash_request_position = 'real' ORDER BY created_date DESC";die;
			$schedule_order =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE agent_id = $agent_id AND is_scheduled = 1 AND wash_request_position = 'real' ORDER BY created_date DESC")->queryAll();

			/* END */
			
			/* Phone Orders */
			$phone_orders = array();
			
			$orders_exists =  Yii::app()->db->createCommand("SELECT * FROM phone_orders where agent_id=$agent_id ORDER BY schedule_date DESC")->queryAll();
			
			if(count($orders_exists)>0){
				$result= 'true';
				$response= 'all orders';

				foreach($orders_exists as $ind=>$order){
					//$all_orders_phone[$ind]['number'] = '';
					$agent_name = '';
					if($order['detailer']) $agent_name = $order['detailer'];
					else{
						if($order['agent_id']){
							$agent_detail = Agents::model()->findByPk($order['agent_id']);
							$agent_name = $agent_detail->first_name." ".$agent_detail->last_name;
							if($agent_detail->real_washer_id) $agent_name .= " #".$agent_detail->real_washer_id;
						}
					}

					//customer_id, customer_name, customer_email, customer_phoneno, address, schedule_date, schedule_time, scheduled_cars_info, checklist, created_date
					$all_orders_phone[$ind]['id'] = $order['id'];
					$all_orders_phone[$ind]['customer_id'] = $order['id'];
					$all_orders_phone[$ind]['customer_name'] = $order['customername'];
					$all_orders_phone[$ind]['customer_email'] = $order['email'];
					$all_orders_phone[$ind]['customer_phoneno'] = $order['phoneno'];
					$all_orders_phone[$ind]['address'] = $order['address'];
					$all_orders_phone[$ind]['schedule_date'] = $order['schedule_date'];
					$all_orders_phone[$ind]['schedule_time'] = $order['schedule_time'];
					$all_orders_phone[$ind]['checklist'] = $order['checklist'];
					$all_orders_phone[$ind]['status'] = '';
					$all_orders_phone[$ind]['created_date'] = $order['created_date'];
					$all_orders_phone[$ind]['vehicles'] = array(
						'regular_vehicles' => $order['regular_vehicles'],
						'classic_vehicles' => $order['classic_vehicles']
					);
					$all_orders_phone[$ind]['is_cancel'] = $order['is_cancel'];
					$all_orders_phone[$ind]['type'] = 'phone-order';
					$all_orders_phone[$ind]['scheduled_cars_info'] = '';
				}

			}
			/* END */
			$package_name = array();
			$car_id = array();
			if(count($schedule_order)>0){

				foreach($schedule_order as $wrequest)
				{

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
					
					$pendingwashrequests[] = array(
						'id'=>$wrequest['id'],
						'customer_id'=>$wrequest['customer_id'],
						'customer_name'=>$cust_details->customername,
						'customer_email'=>$cust_details->email,
						'customer_phoneno'=>$cust_details->contact_number,
						'address'=>$wrequest['address'],
						'schedule_date'=>$wrequest['schedule_date'],
						'schedule_time'=>$wrequest['schedule_time'],
						'checklist'=>$wrequest['checklist'],
						'created_date'=>$wrequest['created_date'],
						'status'=>$wrequest['status'],
						'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
						'vehicles'=> $vehicles,
						'type' => 'schedule-order',
						'is_cancel' => ''
					);

				}
			}
			if( count($orders_exists)>0 || count($schedule_order)>0){
				
				function _sortArray($a, $b){
					$ad = strtotime($a['schedule_date']);
					$bd = strtotime($b['schedule_date']);
					return ($bd-$ad);
				}
				$all_orders = array_merge($pendingwashrequests, $all_orders_phone);
				usort($all_orders, '_sortArray');
				$finalArray = $all_orders;
				
				//echo'<pre>';print_r($finalArray);
			}
		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'orders'=> $finalArray
		);
		echo json_encode($json);
	}

}