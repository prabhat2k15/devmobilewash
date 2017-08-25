<?php
class ScheduleOrdersController extends Controller{

 protected $pccountSid = 'ACa9a7569fc80a0bd3a709fb6979b19423';
    protected $authToken = '149336e1b81b2165e953aaec187971e6';
    protected $from = '+13102941020';
    protected $callbackurl = ROOT_URL.'/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

	public function actionIndex(){
		$this->render('index');
	}


public function actionaddorder(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'All fields are required';

$customer_id = Yii::app()->request->getParam('customer_id');
        $name = Yii::app()->request->getParam('name');
$email = Yii::app()->request->getParam('email');
		$phone = Yii::app()->request->getParam('phone');
		$address = Yii::app()->request->getParam('address');
$address_type = Yii::app()->request->getParam('address_type');
		$city = Yii::app()->request->getParam('city');
		$zipcode = Yii::app()->request->getParam('zipcode');
		$schedule_date = Yii::app()->request->getParam('schedule_date');
$schedule_time = Yii::app()->request->getParam('schedule_time');
$how_hear_mw = Yii::app()->request->getParam('how_hear_mw');
$number_of_vehicles = Yii::app()->request->getParam('number_of_vehicles');
$vehicles = Yii::app()->request->getParam('vehicles');

$total_price = Yii::app()->request->getParam('total_price');
$transaction_id = Yii::app()->request->getParam('transaction_id');
$card_type = Yii::app()->request->getParam('card_type');
$card_ending_no = Yii::app()->request->getParam('card_ending_no');


		if((isset($customer_id) && !empty($customer_id)) &&
(isset($name) && !empty($name)) &&
(isset($email) && !empty($email)) &&
			(isset($phone) && !empty($phone)) &&
			(isset($address) && !empty($address)) &&
			(isset($address_type) && !empty($address_type)) &&
(isset($zipcode) && !empty($zipcode)) &&
(isset($schedule_time) && !empty($schedule_time)) &&
			(isset($schedule_date) && !empty($schedule_date)) &&
(isset($vehicles) && !empty($vehicles)) &&
(isset($total_price) && !empty($total_price)) &&
(isset($card_type) && !empty($card_type)) &&
(isset($card_ending_no) && !empty($card_ending_no)) &&
(isset($transaction_id) && !empty($transaction_id)))
			 {

                   $orderdata= array(
'customer_id' => $customer_id,
					'name'=> $name,
'email'=> $email,
					'phone'=> $phone,
					'address'=> $address,
					'address_type'=> $address_type,
					'zipcode'=> $zipcode,
					'schedule_date'=> $schedule_date,
                    'schedule_time'=> $schedule_time,
 'city'=> $city,

                    'vehicles'=> $vehicles,
 'total_price'=> $total_price,
'transaction_id'=> $transaction_id,
'card_type'=> $card_type,
'card_ending_no'=> $card_ending_no,
'created_date'=> date('Y-m-d H:i:s')
				);

				    $model=new ScheduleOrders;
				    $model->attributes= $orderdata;
				    if($model->save(false)){
                       $order_id = Yii::app()->db->getLastInsertID();
                    }

                    	$result= 'true';
		$response= 'Order added successfully';

$cust_id_check = CustomerScheduleInfo::model()->findByAttributes(array("customer_id"=>$customer_id));



 $from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$subject = 'Order Receipt - #0000'.$order_id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                     $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 18px;'>
<tr><td style='width: 48%; padding-right: 2%;'><strong>Billed To:</strong></td><td style='width: 48%; padding-left: 2%;'></td></tr>
<tr><td>".$email."</td><td><strong>Order Number:</strong> #000".$order_id."</td></tr>
<tr><td>".$name."</td><td><strong>Receipt Date:</strong> ".date('m/d/Y')."</p></td></tr>
<tr><td>".$address." (".$address_type.")</td><td><strong>Order Total:</strong> $".$total_price."</td></tr>
<tr><td></td><td><strong>Billed To:</strong> ".$card_type."....".$card_ending_no."</td></tr>
<tr><td></td><td><strong>Schedule Date Time:</strong> ".date('m/d/Y', strtotime($schedule_date))." ".$schedule_time."</td></tr>
</table>";


$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
$all_cars = explode("|", $vehicles);
foreach($all_cars as $ind=>$vehicle){
$car_details = explode(",", $vehicle);
$message .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$car_details[0]." ".$car_details[1]."</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".$car_details[4]."</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$car_details[2]." Package</p></td>
<td style='text-align: right;'></td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$1.00</p></td>
</tr>
";
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
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
}

if($car_details[9]){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$5.00</p></td>
</tr>";
}

if($car_details[10]){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$5.00</p></td>
</tr>";
}


$message .= "</table></td></tr>";
}

$message .= "</table>";

$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$total_price."</span></p></td></tr></table>";


Vargas::Obj()->SendMail($email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("scheduling@mobilewash.com",$email,$message,$subject, 'mail-receipt');

$all_washers = Yii::app()->db->createCommand()
						->select('*')
						->from('active_washers')
						->where("active_status='1'", array())
						->queryAll();

if(count($all_washers) > 0) {

foreach($all_washers as $washer){
 $this->layout = "xmlLayout";
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');
            require('Services/Twilio.php');
            require('Services/Twilio/Capability.php');

            /* Instantiate a new Twilio Rest Client */

            $account_sid = 'ACa9a7569fc80a0bd3a709fb6979b19423';
            $auth_token = '149336e1b81b2165e953aaec187971e6';
            $client = new Services_Twilio($account_sid, $auth_token);
$orderdate = '';
if(strtotime($schedule_date) == strtotime(date('Y-m-d'))){
$orderdate = 'Today';
}
else{
$orderdate = $schedule_date;
}

            $message = "Pending Order Request\r\n\r\n".$orderdate." @ ".$schedule_time."\r\n\r\n".$address." ".$city."\r\n\r\nClick Link to Accept Order\r\n\r\n".ROOT_URL."/washer/job-details.php?orderid=".$order_id."&wid=".$washer['id'];
            $sendmessage = $client->account->messages->create(array(
                'To' =>  $washer['phone'],
                'From' => '+13103128070',
                'Body' => $message,
 'mediaUrl' => ROOT_URL.'/washer/images/hm11k25d.png'
            ));
}

}


		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
'id' => $order_id
		);
		echo json_encode($json);
	}


public function actiongetallorders(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_orders = array();

        $result= 'false';
		$response= 'none';

        $orders_exists = Yii::app()->db->createCommand()->select('*')->from('schedule_orders')->order('id DESC')->queryAll();

        if(count($orders_exists)>0){
           $result= 'true';
		    $response= 'all orders';

            foreach($orders_exists as $ind=>$order){

                $all_orders[$ind]['id'] = $order['id'];
$all_orders[$ind]['customer_id'] = $order['customer_id'];
 $all_orders[$ind]['name'] = $order['name'];
$all_orders[$ind]['email'] = $order['email'];
$all_orders[$ind]['phone'] = $order['phone'];
$all_orders[$ind]['address'] = $order['address'];
$all_orders[$ind]['address_type'] = $order['address_type'];
$all_orders[$ind]['city'] = $order['city'];
$all_orders[$ind]['zipcode'] = $order['zipcode'];
$all_orders[$ind]['schedule_date'] = $order['schedule_date'];
$all_orders[$ind]['schedule_time'] = $order['schedule_time'];
$all_orders[$ind]['how_hear_mw'] = $order['how_hear_mw'];
$all_orders[$ind]['number_of_vehicles'] = $order['number_of_vehicles'];
$all_orders[$ind]['vehicles'] = $order['vehicles'];
$all_orders[$ind]['total_price'] = $order['total_price'];
$all_orders[$ind]['transaction_id'] = $order['transaction_id'];
$all_orders[$ind]['created_date'] = $order['created_date'];
            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'orders'=> $all_orders
		);
		echo json_encode($json);

    }


public function actiongetorderbyid(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

 $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');

$order_details = array();

		if((isset($id) && !empty($id)))
		{

            $order_exists = ScheduleOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{
				$response = "Order details";
                $result = 'true';

                  $order_det = ScheduleOrders::model()->findByPk($id);

   $order_details['id'] = $order_det->id;
$order_details['washer_id'] = $order_det->washer_id;
$order_details['customer_id'] = $order_det->customer_id;
 $order_details['name'] = $order_det->name;
$order_details['email'] = $order_det->email;
$order_details['phone'] = $order_det->phone;
$order_details['address'] = $order_det->address;
$order_details['address_type'] = $order_det->address_type;
$order_details['city'] = $order_det->city;
$order_details['zipcode'] = $order_det->zipcode;
$order_details['schedule_date'] = $order_det->schedule_date;
$order_details['schedule_time'] = $order_det->schedule_time;
$order_details['how_hear_mw'] = $order_det->how_hear_mw;
$order_details['number_of_vehicles'] = $order_det->number_of_vehicles;
$order_details['vehicles'] = $order_det->vehicles;
$order_details['total_price'] = $order_det->total_price;
$order_details['transaction_id'] = $order_det->transaction_id;
$order_details['created_date'] = $order_det->created_date;
$order_details['status'] = $order_det->status;
$order_details['card_type'] = $order_det->card_type;
$order_details['card_ending_no'] = $order_det->card_ending_no;

			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response,
'order_details' => $order_details
		);

		echo json_encode($json);
}

public function actioncancelOrder(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

 $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');
$name = Yii::app()->request->getParam('name');
$email = Yii::app()->request->getParam('email');
$address = Yii::app()->request->getParam('address');
$address_type = Yii::app()->request->getParam('address_type');
$fee = 0;
if(Yii::app()->request->getParam('fee')) $fee = Yii::app()->request->getParam('fee');
$card_ending_no = '';
if(Yii::app()->request->getParam('card_ending_no')) $card_ending_no = Yii::app()->request->getParam('card_ending_no');
$card_type = '';
if(Yii::app()->request->getParam('card_type')) $card_type = Yii::app()->request->getParam('card_type');

$r_fee = Yii::app()->request->getParam('r_fee');
$r_card_ending_no = Yii::app()->request->getParam('r_card_ending_no');
$r_card_type = Yii::app()->request->getParam('r_card_type');

		if((isset($id) && !empty($id)) && (isset($name) && !empty($name)) && (isset($email) && !empty($email)) && (isset($address) && !empty($address)) && (isset($address_type) && !empty($address_type)) && (isset($r_fee) && !empty($r_fee)) && (isset($r_card_ending_no) && !empty($r_card_ending_no)) && (isset($r_card_type) && !empty($r_card_type)))
		{

            $order_exists = ScheduleOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{
				$response = "Order cancelled";
                $result = 'true';

                  ScheduleOrders::model()->updateByPk($id, array('status'=>3));

$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$subject = 'Cancel Order Receipt - #0000'.$id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                     $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 18px;'>
<tr><td style='width: 48%; padding-right: 2%;'><strong>Billed To:</strong></td><td style='width: 48%; padding-left: 2%;'></td></tr>
<tr><td>".$email."</td><td><strong>Order Number:</strong> #000".$id."</td></tr>
<tr><td>".$name."</td><td><strong>Receipt Date:</strong> ".date('m/d/Y')."</p></td></tr>
<tr><td>".$address." (".$address_type.")</td><td><strong>Cancel Fee:</strong> $".$fee."</td></tr>";
if($card_type && $card_ending_no) $message .= "<tr><td></td><td><strong>Billed To:</strong> ".$card_type."....".$card_ending_no."</td></tr>";
$message .= "<tr><td></td><td><strong>Amount Refunded:</strong> $".$r_fee."</td></tr>";
if($r_card_type && $r_card_ending_no) $message .= "<tr><td></td><td><strong>Refunded To:</strong> ".$r_card_type."....".$r_card_ending_no."</td></tr>";

$message .= "</table></div>";

Vargas::Obj()->SendMail($email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("scheduling@mobilewash.com",$email,$message,$subject, 'mail-receipt');

			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);
}


public function actionDeleteOrder(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');



		if((isset($id) && !empty($id)))
		{

            $order_exists = ScheduleOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{
				$response = "Order deleted";
                $result = 'true';

                  ScheduleOrders::model()->deleteByPk(array('id'=>$id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }

public function actionrescheduleorder(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');
$schedule_date = Yii::app()->request->getParam('schedule_date');
$schedule_time = Yii::app()->request->getParam('schedule_time');

$email = Yii::app()->request->getParam('email');

		if((isset($id) && !empty($id)) && (isset($email) && !empty($email)) && (isset($schedule_date) && !empty($schedule_date)) && (isset($schedule_time) && !empty($schedule_time)))
		{

            $order_exists = ScheduleOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{
				$response = "Order rescheduled";
                $result = 'true';

                   ScheduleOrders::model()->updateByPk($id, array('schedule_date'=>$schedule_date, 'schedule_time'=>$schedule_time));

		$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$subject = 'Order #0000'.$id.' Re-Scheduled';
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                     $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 18px;'>
<tr><td></td><td><strong>Order Number:</strong> #000".$id."</td></tr>
<tr><td></td><td><strong>Re-Schedule Date:</strong> ".$schedule_date." ".$schedule_time."</p></td></tr>";

$message .= "</table></div>";

Vargas::Obj()->SendMail($email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("scheduling@mobilewash.com",$email,$message,$subject, 'mail-receipt');	

}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }

public function actionupdateorder(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

         $result= 'false';
		$response= 'Please provide order id';

		$id = Yii::app()->request->getParam('id');
$washer_id = Yii::app()->request->getParam('washer_id');
$status = Yii::app()->request->getParam('status');

		if((isset($id) && !empty($id)))
		{

            $order_exists = ScheduleOrders::model()->findByAttributes(array("id"=>$id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }


           else{

if(!isset($washer_id)){
$washer_id = $order_exists->washer_id;
}

if(!isset($status)){
$status = $order_exists->status;
}
				$response = "Order updated";
                $result = 'true';

                   ScheduleOrders::model()->updateByPk($id, array('washer_id'=>$washer_id, 'status'=>$status));

		
}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }


public function actioncustomerscheduleinfo(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$customer_id = Yii::app()->request->getParam('customer_id');
$api_pass = Yii::app()->request->getParam('api_pass');

		if((isset($customer_id) && !empty($customer_id)) && (isset($api_pass) && !empty($api_pass))){
			$customers_id = Customers::model()->findByAttributes(array("id"=>$customer_id));
            $s_info = CustomerScheduleInfo::model()->findByAttributes(array("customer_id"=>$customer_id));
if($api_pass != 'MW123@!!mw'){
$json = array(
					'result'=> 'false',
					'response'=> 'Access denied'
				);
}
            	else if(!count($customers_id)){
                   	$json = array(
					'result'=> 'false',
					'response'=> 'Invalid Customer'
				);
                }
                	else if(!count($s_info)){
                    	$json = array(
					'result'=> 'false',
					'response'=> 'No info found'
				);
                }
			else{
				$json= array(
					'result'=> 'true',
					'response'=> 'Customer schedule info',
					'customer_id' => $s_info->customer_id,
					'zipcode' => $s_info->zipcode,
					'address' => $s_info->address,
                    'address_type' => $s_info->address_type,
                    'cardno' => $s_info->cardno,
                    'cvc' => $s_info->cvc,
                    'mo_exp' => $s_info->mo_exp,
                    'yr_exp' => $s_info->yr_exp
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


}