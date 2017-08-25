<?php


class WashingController extends Controller{


	protected $pccountSid = 'ACa9a7569fc80a0bd3a709fb6979b19423';
    protected $authToken = '149336e1b81b2165e953aaec187971e6';
    protected $from = '+13102941020';
    protected $callbackurl = 'https://www.mobilewash.com/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex(){
        $this->render('index');
    }


    /*
    ** Returns Washing Palns.
    ** Post Required: vehicle_model
    ** Url:- http://www.demo.com/index.php?r=washing/plans
    ** Purpose:- Return Plan details
    */



    public function actionplans(){

		$vehicle_make  = Yii::app()->request->getParam('vehicle_make');
        $vehicle_model = Yii::app()->request->getParam('vehicle_model');
		$vehicle_build = '';
		$vehicle_build = Yii::app()->request->getParam('vehicle_build');

        $json = array();
        $plans = array();
        $deluxe_plan = array();
        $premium_plan = array();
        $vehicle_type = '';
        $result= 'false';
        $response= 'Pass the required parameters';

        if(isset($vehicle_make) && !empty($vehicle_make) && isset($vehicle_model) && !empty($vehicle_model)){

if($vehicle_build == 'classic'){
$vehicle_exists = Yii::app()->db->createCommand()
                ->select('*')
                ->from('all_classic_vehicles')
                ->where("make='".$vehicle_make."' AND model='".$vehicle_model."'", array())
                ->queryAll();
}
else{
$vehicle_exists = Yii::app()->db->createCommand()
                ->select('*')
                ->from('all_vehicles')
                ->where("make='".$vehicle_make."' AND model='".$vehicle_model."'", array())
                ->queryAll();
}


            if(count($vehicle_exists)>0){
$vehicle_type = $vehicle_exists[0]['type'];

            }

            if($vehicle_type){

                $result = 'true';
                $response = 'Plans';

                $allplans = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('washing_plans')
                    ->where("vehicle_type='".$vehicle_type."'", array())
                    ->queryAll();

                foreach($allplans as $planDetails){
                    $planDetails['description'] = preg_split('/\r\n|[\r\n]/', $planDetails['description']);
                    unset($planDetails['id']);
                    if($planDetails['title'] == "Deluxe") $deluxe_plan[] = $planDetails;
                    if($planDetails['title'] == "Premium") $premium_plan[] = $planDetails;
                    //$plans[] = $planDetails;
                }

                $plans['deluxe'] = $deluxe_plan;
                $plans['premium'] = $premium_plan;

            }else{
                $response = 'No plans exists';
            }
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'plans'=> $plans
        );

        echo json_encode($json); die();
    }


/*

public function actionplans(){

        $vehicle_model = Yii::app()->request->getParam('vehicle_model');
$vehicle_build = '';
$vehicle_build = Yii::app()->request->getParam('vehicle_build');

        $json = array();
        $plans = array();
        $deluxe_plan = array();
        $premium_plan = array();
        $vehicle_type = '';
        $result= 'false';
        $response= 'Pass the required parameters';

        if(isset($vehicle_model) && !empty($vehicle_model)){

if($vehicle_build == 'classic'){
$vehicle_exists = Yii::app()->db->createCommand()
                ->select('*')
                ->from('all_classic_vehicles')
                ->where("model='".$vehicle_model."'", array())
                ->queryAll();
}
else{
$vehicle_exists = Yii::app()->db->createCommand()
                ->select('*')
                ->from('all_vehicles')
                ->where("model='".$vehicle_model."'", array())
                ->queryAll();
}


            if(count($vehicle_exists)>0){
$vehicle_type = $vehicle_exists[0]['type'];

            }

            if($vehicle_type){

                $result = 'true';
                $response = 'Plans';

                $allplans = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('washing_plans')
                    ->where("vehicle_type='".$vehicle_type."'", array())
                    ->queryAll();

                foreach($allplans as $planDetails){
                    $planDetails['description'] = preg_split('/\r\n|[\r\n]/', $planDetails['description']);
                    unset($planDetails['id']);
                    if($planDetails['title'] == "Deluxe") $deluxe_plan[] = $planDetails;
                    if($planDetails['title'] == "Premium") $premium_plan[] = $planDetails;
                    //$plans[] = $planDetails;
                }

                $plans['deluxe'] = $deluxe_plan;
                $plans['premium'] = $premium_plan;

            }else{
                $response = 'No plans exists';
            }
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'plans'=> $plans
        );

        echo json_encode($json); die();
    }

*/



 public function actionClearwash() {
        $result= 'false';
        $response= 'Pass the required parameters';
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if(isset($wash_request_id) && !empty($wash_request_id)) {
            $wash = Washingrequests::model()->findByPk($wash_request_id);
            $wash->status = 0;
            $wash->save(false);
            $result= 'true';
            $response= 'status cleared';
        }

        $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
    }

    /*
    ** Returns Washing Requests.
    ** Post Required: customer id,CarList,PackageList,address,address_type,PaymentType,Nonce
    ** Url:- http://www.demo.com/index.php?r=washing/createwashrequest
    ** Purpose:- Create wash request
    */
    public function actioncreatewashrequest(){

        $customer_id = Yii::app()->request->getParam('customer_id');
        $car_ids = Yii::app()->request->getParam('car_ids');
        $package_ids = Yii::app()->request->getParam('package_names');
        $address = Yii::app()->request->getParam('address');
        $address_type = Yii::app()->request->getParam('address_type');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $payment_type = Yii::app()->request->getParam('payment_type');
        $nonce = Yii::app()->request->getParam('nonce');
        $estimate_time = Yii::app()->request->getParam('estimate_time');
        //$transaction_id = Yii::app()->request->getParam('transaction_id');
$is_scheduled = 0;
if(Yii::app()->request->getParam('is_scheduled')) $is_scheduled = Yii::app()->request->getParam('is_scheduled');
		$schedule_date = Yii::app()->request->getParam('schedule_date');
		$schedule_time = Yii::app()->request->getParam('schedule_time');
		$schedule_cars_info = Yii::app()->request->getParam('schedule_cars_info');
		$schedule_total = Yii::app()->request->getParam('schedule_total');
$schedule_total_vip = Yii::app()->request->getParam('schedule_total_vip');
		$schedule_company_total = Yii::app()->request->getParam('schedule_company_total');
$schedule_company_total_vip = Yii::app()->request->getParam('schedule_company_total_vip');
		$schedule_agent_total = Yii::app()->request->getParam('schedule_agent_total');
$coupon_amount = '';
if(Yii::app()->request->getParam('coupon_amount')) $coupon_amount = Yii::app()->request->getParam('coupon_amount');
$coupon_code = '';
if(Yii::app()->request->getParam('coupon_code')) $coupon_code = Yii::app()->request->getParam('coupon_code');
$coupon_code_vip = Yii::app()->request->getParam('coupon_code_vip');
$tip_amount = Yii::app()->request->getParam('tip_amount');
$wash_request_position = Yii::app()->request->getParam('wash_request_position');
$pet_hair_vehicles = '';
if(Yii::app()->request->getParam('pet_hair_vehicles')) $pet_hair_vehicles = Yii::app()->request->getParam('pet_hair_vehicles');
$lifted_vehicles = '';
if(Yii::app()->request->getParam('lifted_vehicles')) $lifted_vehicles = Yii::app()->request->getParam('lifted_vehicles');
$exthandwax_vehicles = '';
if(Yii::app()->request->getParam('exthandwax_vehicles')) $exthandwax_vehicles = Yii::app()->request->getParam('exthandwax_vehicles');
$extplasticdressing_vehicles = '';
if(Yii::app()->request->getParam('extplasticdressing_vehicles')) $extplasticdressing_vehicles = Yii::app()->request->getParam('extplasticdressing_vehicles');
$extclaybar_vehicles = '';
if(Yii::app()->request->getParam('extclaybar_vehicles')) $extclaybar_vehicles = Yii::app()->request->getParam('extclaybar_vehicles');
$waterspotremove_vehicles = '';
if(Yii::app()->request->getParam('waterspotremove_vehicles')) $waterspotremove_vehicles = Yii::app()->request->getParam('waterspotremove_vehicles');

        $json = array();
        $car_id_check = true;
        $washplan_id_check = true;
        $result= 'false';
        $response= 'Pass the required parameters';

        if((isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($package_ids) && !empty($package_ids)) && (isset($address) && !empty($address)) && (isset($address_type) && !empty($address_type)) && (isset($latitude) && !empty($latitude)) && (isset($longitude) && !empty($longitude)) && (isset($estimate_time) && !empty($estimate_time))) {
            $customers_id_check = Customers::model()->findByAttributes(array("id"=>$customer_id));
            $pendingwashcheck =  Washingrequests::model()->findAll(array("condition"=>"wash_request_position != 'real' AND status <= 3 AND customer_id=".$customer_id), array('order' => 'created_date desc'));

            $car_ids_array = explode(",", $car_ids);
            foreach($car_ids_array as $cid) {
                $car_id_exists = Vehicle::model()->findByAttributes(array("id"=>$cid));
                if(!count( $car_id_exists)){
                    $car_id_check = false;
                    break;
                }
            }

            $washplan_ids_array = explode(",", $package_ids);
            foreach($washplan_ids_array as $wid) {
                $washplan_id_exists = Washingplans::model()->findByAttributes(array("title"=>$wid));
                if(!count( $washplan_id_exists)){
                    $washplan_id_check = false;
                    break;
                }
            }

            if(!count( $customers_id_check)){
                $response= 'Invalid customer';
            }

            else if(!$car_id_check){
                $response= 'Invalid vehicle id '.$cid ;
            }

            else if(!$washplan_id_check){
                $response= 'Invalid washing plan '.$wid ;
            }

             else if(count($pendingwashcheck)){
if($pendingwashcheck[0]->is_scheduled == 1) $response= "Sorry you may not order at this time. You have a pending scheduled order in progress." ;
else $response= "Sorry you may not order at this time. You have a pending order in progress." ;
            }

            else{

             foreach($car_ids_array as $car){

                 $carresetdata= array('status' => 0, 'eco_friendly' => 0, 'damage_points'=> '','damage_pic'=>'', 'upgrade_pack'=> 0, 'edit_vehicle'=> 0, 'remove_vehicle_from_kart'=> 0, 'new_vehicle_confirm'=> 0, 'new_pack_name'=> '');
                 $vehiclemodel = new Vehicle;
                 $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id'=>$car));
             }



                $date = date("Y-m-d H:i:s");

                $washrequestdata= array(
                    'customer_id'=> $customer_id,
                    'car_list'=> $car_ids,
                    'package_list'=> $package_ids,
                    'address'=> $address,
                    'address_type'=> $address_type,
                    'latitude'=> $latitude,
                    'longitude'=> $longitude,
'is_scheduled' => $is_scheduled,
                    'estimate_time'=> $estimate_time,
                    'created_date'=> $date,

                );
                //$washrequestdata= array_filter($washrequestdata);
                $model=new Washingrequests;
                $model->attributes= $washrequestdata;
                  if($model->save(false))
                {
                    $washrequestid = Yii::app()->db->getLastInsertID();
                    $result= 'true';
                    $response= $washrequestid;

                    /* ---------- insert transaction id -------------- */

                    if((isset($transaction_id) && !empty($transaction_id))){

                        $update_request = Washingrequests::model()->findByPk($washrequestid);
                        $update_request->transaction_id = $transaction_id;
                        $update_request->save(false);
                    }

                      /* ---------- insert transaction id end -------------- */

/* ---------- insert addons / others -------------- */



                        Washingrequests::model()->updateByPk($washrequestid, array('pet_hair_vehicles' => $pet_hair_vehicles, 'lifted_vehicles' => $lifted_vehicles, 'exthandwax_vehicles' => $exthandwax_vehicles, 'extplasticdressing_vehicles' => $extplasticdressing_vehicles, 'extclaybar_vehicles' => $extclaybar_vehicles, 'waterspotremove_vehicles' => $waterspotremove_vehicles, 'coupon_discount' => $coupon_amount, 'coupon_code' => $coupon_code));

$car_arr = explode(",",$car_ids);

   foreach($car_arr as $ind=>$carid){
$pet_fee = 0;
$lift_fee = 0;
$exthandwax_fee = 0;
$extplasticdressing_fee = 0;
$extclaybar_fee = 0;
$waterspotremove_fee = 0;

$pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles);
if (in_array($carid, $pet_hair_vehicles_arr)) $pet_fee = 5;

$lifted_vehicles_arr = explode(",", $lifted_vehicles);
if (in_array($carid, $lifted_vehicles_arr)) $lift_fee = 5;

$exthandwax_addon_arr = explode(",", $exthandwax_vehicles);
if (in_array($carid, $exthandwax_addon_arr)) $exthandwax_fee = 12;

$extplasticdressing_addon_arr = explode(",", $extplasticdressing_vehicles);
if (in_array($carid, $extplasticdressing_addon_arr)) $extplasticdressing_fee = 8;

$extclaybar_addon_arr = explode(",", $extclaybar_vehicles);
if (in_array($carid, $extclaybar_addon_arr)) $extclaybar_fee = 35;

$waterspotremove_addon_arr = explode(",", $waterspotremove_vehicles);
if (in_array($carid, $waterspotremove_addon_arr)) $waterspotremove_fee = 30;

Vehicle::model()->updateByPk($carid, array('pet_hair' => $pet_fee, 'lifted_vehicle' => $lift_fee, 'exthandwax_addon' => $exthandwax_fee, 'extplasticdressing_addon' => $extplasticdressing_fee, 'extclaybar_addon' => $extclaybar_fee, 'waterspotremove_addon' => $waterspotremove_fee));
}




                      /* ---------- insert addons end -------------- */

/* ----------- make customer status busy ----------- */

                        $customers_id_check->online_status = 'busy';
                        $customers_id_check->save(false);

/* ---------- make customer status busy end -------------- */

 /* ---------- add schedule info -------------- */

				if((isset($is_scheduled) && !empty($is_scheduled))){

					Washingrequests::model()->updateByPk($washrequestid, array('schedule_date' => $schedule_date, 'schedule_time' => $schedule_time, 'scheduled_cars_info' => $schedule_cars_info, 'schedule_total' => $schedule_total, 'schedule_total_vip' => $schedule_total_vip, 'schedule_company_total_vip' => $schedule_company_total_vip, 'schedule_company_total' => $schedule_company_total, 'schedule_agent_total' => $schedule_agent_total, 'coupon_discount' => $coupon_amount, 'coupon_code' => $coupon_code, 'vip_coupon_code' => $coupon_code_vip, 'tip_amount' => $tip_amount, 'wash_request_position' => $wash_request_position));

					$wash_details = Washingrequests::model()->findByPk($washrequestid);

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
$mobile_receipt = '';
					$subject = 'Order Receipt - #0000'.$washrequestid;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
					$message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is scheduled for ".$sched_date." @ ".$wash_details->schedule_time."</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$address."</p>";
					$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>".$customers_id_check->customername."</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000".$washrequestid."</td></tr>
					</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
					$all_cars = explode("|", $wash_details->scheduled_cars_info);
					foreach($all_cars as $ind=>$vehicle){
						$car_details = explode(",", $vehicle);
$mobile_receipt .= $car_details[0]." ".$car_details[1]."\r\n".$car_details[2]." $".$car_details[4]."\r\nHandling $1.00\r\n";

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

$mobile_receipt .= "Wax $".number_format($car_details[12], 2)."\r\n";
						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[13], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Dressing $".number_format($car_details[13], 2)."\r\n";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[14], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Clay $".number_format($car_details[14], 2)."\r\n";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[15], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Spot $".number_format($car_details[15], 2)."\r\n";
						}
						if($car_details[5]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
							</tr>";
$mobile_receipt .= "Hair $5.00\r\n";
						}
						if($car_details[6]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
							</tr>";
$mobile_receipt .= "Lifted $5.00\r\n";
						}

						if($car_details[8]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : '1.00')."</p></td>
							</tr>";
$mobile_receipt .= "Bundle -$1.00\r\n";
						}

						if($car_details[9]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($car_details[9], 2))."</p></td>
							</tr>";
$mobile_receipt .= "1st -$".number_format($car_details[9], 2)."\r\n";
						}

						if($car_details[10]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($car_details[10], 2))."</p></td>
							</tr>";
$mobile_receipt .= "5th -$".number_format($car_details[10], 2)."\r\n";
						}

						$message .= "</table></td></tr>";
                        $mobile_receipt .= "------\r\n";
					}

if($tip_amount){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Tip</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$".number_format($tip_amount, 2)."</p></td>
							</tr></table>";
$mobile_receipt .= "Tip $".number_format($tip_amount, 2)."\r\n";
						}

if($coupon_amount){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Coupon Discount (".$coupon_code.")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-$".($wash_details->vip_coupon_code != '' ? '0' : number_format($coupon_amount, 2))."</p></td>
							</tr></table>";
$mobile_receipt .= "Coupon -$".number_format($coupon_amount, 2)."\r\n";
						}


if($wash_details->vip_coupon_code){

 $vip_coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$wash_details->vip_coupon_code));

							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>".$vip_coupon_check->package_name." (".$wash_details->vip_coupon_code.")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'></p></td>
							</tr></table>";
$mobile_receipt .= $vip_coupon_check->package_name."\r\n";

						}


					$message .= "</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".($wash_details->vip_coupon_code != '' ? $wash_details->schedule_total_vip : $wash_details->schedule_total)."</span></p></td></tr></table>";

$mobile_receipt .= "Total: $".$wash_details->schedule_total."\r\n";

					$message .= "<p style='text-align: center; font-size: 18px;'>To re-schedule or cancel visit your account history by<br>logging in to <a href='https://www.mobilewash.com'>Mobilewash.com</a></p>";
					$message .= "<p style='text-align: center; font-size: 20px; margin-bottom: 0;'>*$10 cancelation fee will apply for cancelling<br>within 30 minutes of your scheduled wash time</p>";

					Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
					Vargas::Obj()->SendMail("admin@mobilewash.com","info@mobilewash.com",$message,$subject, 'mail-receipt');



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


            $message = "NEW Web Order #000".$washrequestid."- ".date('M d', strtotime($wash_details->schedule_date))." @ ".$wash_details->schedule_time."\r\n".$customers_id_check->customername."\r\n".$customers_id_check->contact_number."\r\n".$address."\r\n------\r\n".$mobile_receipt;


  $sendmessage = $client->account->messages->create(array(
                'To' =>  '9098023158',
                'From' => '+13103128070',
                'Body' => $message,
            ));



$sendmessage = $client->account->messages->create(array(
                'To' =>  '8183313631',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '3109999334',
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));


      if($coupon_code){
$coupondata= array(
                    'customer_id'=> $customer_id,
                    'wash_request_id'=> $washrequestid,
                    'promo_code'=> $coupon_code
                );

                $model=new CustomerDiscounts;
                $model->attributes= $coupondata;
                $model->save(false);
}

					//$allagents = Agents::model()->findAll();



                    /*
					foreach($allagents as $agent){
						$get_notify = 1;
						$allschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$agent->id, 'is_scheduled' => 1, 'status'=> 0));

						foreach($allschedwashes as $wash){

							$datediff = round((strtotime($wash['schedule_date']) - strtotime($schedule_date))/(60*60));

							if($datediff < 2){
							$get_notify = 0;
							break;
							}

						}

						if($get_notify){

							$agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent->id."' ")->queryAll();



							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
							$message = $pushmsg[0]['message'];

							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "schedule";
								$notify_msg = urlencode($message);

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}

						}
					}

                    */
                }

				/* ---------- add schedule info -------------- */

                $result= 'true';
                $response= $washrequestid;
            }
        }


    }

     $json = array(
            'result'=> $result,
            'response'=> $response,

        );

        echo json_encode($json); die();
}


    /*
	** Returns Pending Washing Requests.
	** Post Required: none
	** Url:- http://www.demo.com/index.php?r=washing/pendingwashrequests
	** Purpose:- Pending Washing Requests
	*/
    public function actionpendingwashrequests(){

        $json = array();

        $result= 'true';
        $response= 'Pending wash requests';
        $pendingwashrequests = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $qrRequests = Yii::app()->db->createCommand()
            ->select('*')->from('washing_requests')
            ->where("status='0'",array())
            ->queryAll();

        if(count($qrRequests)>0){

            foreach($qrRequests as $wrequest)
            {
                $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $wrequest['customer_id']));
                $total_rate = count($cust_feedbacks);
                if($total_rate){
                    $rate = 0;
                    foreach($cust_feedbacks as $cust_feedback){
                        $rate += $cust_feedback->customer_ratings;
                    }

                    $cust_rate =  round($rate/$total_rate);
                }
                else{
                    $cust_rate = 0;
                }

                $cust_details = Customers::model()->findByAttributes(array("id"=>$wrequest['customer_id']));

$customername = '';
$cust_name = explode(" ", trim($cust_details->customername));
if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
else $customername = $cust_name[0];

                $pendingwashrequests[] = array('id'=>$wrequest['id'],
                    'customer_id'=>$wrequest['customer_id'],
                    'customer_name'=>$customername,
                    'customer_email'=>$cust_details->email,
                    'customer_phoneno'=>$cust_details->contact_number,
                    'customer_photo'=>$cust_details->image,
                    'customer_rating' =>$cust_details->rating,
                    'car_list'=>$wrequest['car_list'],
                    'package_list'=>$wrequest['package_list'],
                    'address'=>$wrequest['address'],
                    'address_type'=>$wrequest['address_type'],
                    'latitude'=>$wrequest['latitude'],
                    'longitude'=>$wrequest['longitude'],
                    'payment_type'=>$wrequest['payment_type'],
                    'nonce'=>$wrequest['nonce'],
                    'estimate_time'=>$wrequest['estimate_time'],
                    'status'=>$wrequest['status'],

                );

                $last_cust_id = $wrequest['customer_id'];
                $last_cust_lat = $wrequest['latitude'];
                $last_cust_lng = $wrequest['longitude'];



            }

            /* ---- nearest agent call ----

            $handle = curl_init("https://www.mobilewash.com/api/index.php?r=customers/estimatetime");
            $data = array('customer_id' => $last_cust_id, 'latitude' => $last_cust_lat, 'longitude' => $last_cust_lng);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $agent_result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($agent_result);
//var_dump($jsondata);
//$nearest_agent_details = $jsondata->result;
*/
            /* ---- nearest agent call end ---- */
   /*
            if($jsondata->result == 'true'){

              --- notification call ---


                $agent_details = Agents::model()->findByAttributes(array('id'=>$jsondata->agent_id));
                $notify_token = '';
                $notify_msg = '';
                $notify_token = $agent_details->device_token;
                $device_type = 'ios';
                $device_type = strtolower($agent_details->mobile_type);

$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '9' ")->queryAll();
$notify_msg = $pushmsg[0]['message'];

                //$notify_msg = "You have a wash request.";

                $notify_msg = urlencode($notify_msg);

                $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg;
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$notifyurl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                if($notify_msg) $notifyresult = curl_exec($ch);
                curl_close($ch);

                //var_dump($notifyresult);


                /* --- notification call end ---
            }
 */
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'pending_wash_requests' => $pendingwashrequests
        );

        echo json_encode($json); die();
    }

    public function actionlatestwashrequestbyclientid(){
        $customer_id = Yii::app()->request->getParam('customer_id');
        $result= 'false';
        $response= 'Pass the required parameters';
        $json= array();
        $wash_details = new stdClass();
        if((isset($customer_id) && !empty($customer_id))){


            $cust_id_check = Customers::model()->findByAttributes(array('id'=>$customer_id));

            if(!count($cust_id_check)){
                $result= 'false';
                $response= 'Invalid customer';
            }

            else{
                $result= 'true';
                $response= 'Latest wash request details';
                $wrequest_id_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$customer_id), array('order'=>'created_date DESC'));

                $customer_id_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $customer_id));

                    $total_rate = count($customer_id_feedbacks);
                    if($total_rate){
                    $rate = 0;
                    foreach($customer_id_feedbacks as $customer_feedback){
                       $rate += $customer_feedback->agent_ratings;
                    }

                    $customer_rate =  round($rate/$total_rate);
                    }
                    else{
                    $customer_rate = 0;
                    }

                $wash_details->id = $wrequest_id_check->id;
                $wash_details->customer_id = $wrequest_id_check->customer_id;
                $wash_details->agent_id = $wrequest_id_check->agent_id;
                $wash_details->car_list = $wrequest_id_check->car_list;
                $wash_details->package_list = $wrequest_id_check->package_list;
                $wash_details->address = $wrequest_id_check->address;
                $wash_details->address_type = $wrequest_id_check->address_type;
                $wash_details->latitude = $wrequest_id_check->latitude;
                $wash_details->longitude = $wrequest_id_check->longitude;
                $wash_details->payment_type = $wrequest_id_check->payment_type;
                $wash_details->estimate_time = $wrequest_id_check->estimate_time;
                $wash_details->status = $wrequest_id_check->status;
                $wash_details->created_date = $wrequest_id_check->created_date;
                $wash_details->total_price = $wrequest_id_check->total_price;
                $wash_details->net_price = $wrequest_id_check->net_price;
                $wash_details->company_total = $wrequest_id_check->company_total;
                $wash_details->agent_total = $wrequest_id_check->agent_total;
                $wash_details->bundle_discount = $wrequest_id_check->bundle_discount;
                $wash_details->fifth_wash_discount = $wrequest_id_check->fifth_wash_discount;
                $wash_details->first_wash_discount = $wrequest_id_check->first_wash_discount;
                $wash_details->coupon_discount = $wrequest_id_check->coupon_discount;
                $wash_details->cancel_fee = $wrequest_id_check->cancel_fee;
            }


        }
        else{
            $result= 'false';
            $response= 'Pass the required parameters';

        }

$customername = '';
$cust_name = explode(" ", trim($cust_id_check->customername));
if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
else $customername = $cust_name[0];

        $json= array(
            'result'=> $result,
            'response'=> $response,
            'wash_details' => $wash_details,
            'name' => $customername,
            'image' => $cust_id_check->image,
            'email' => $cust_id_check->email,
            'rating' => $customer_rate,
        );
        echo json_encode($json);
    }

    /*
   ** Returns Changed wash request status.
   ** Post Required: agent id,wash request id,status
   ** Url:- http://www.demo.com/index.php?r=washing/updatewashrequeststatus
   ** Purpose:- Change wash request status
   */

	public function actionupdatewashrequeststatus(){
		$agent_id = Yii::app()->request->getParam('agent_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');
		$buzz_status = Yii::app()->request->getParam('buzz_status');
		$car_ids = Yii::app()->request->getParam('car_ids');
		$package_list = Yii::app()->request->getParam('package_list');
		$is_scheduled = Yii::app()->request->getParam('is_scheduled');
		$schedule_date = Yii::app()->request->getParam('schedule_date');
		$schedule_time = Yii::app()->request->getParam('schedule_time');
		$address = Yii::app()->request->getParam('schedule_address_new');
$address_type = Yii::app()->request->getParam('address_type');
$eta = Yii::app()->request->getParam('estimate_time');
		$lat = Yii::app()->request->getParam('schedule_address_lat');
		$long = Yii::app()->request->getParam('schedule_address_lng');
		$checklist = Yii::app()->request->getParam('checklist');
		$notes = Yii::app()->request->getParam('notes');
$scheduled_cars_info = Yii::app()->request->getParam('scheduled_cars_info');
$schedule_total = Yii::app()->request->getParam('schedule_total');
$schedule_agent_total = Yii::app()->request->getParam('schedule_agent_total');
$schedule_company_total = Yii::app()->request->getParam('schedule_company_total');
$reschedule_date = Yii::app()->request->getParam('reschedule_date');
		$reschedule_time = Yii::app()->request->getParam('reschedule_time');
$is_rescheduled = Yii::app()->request->getParam('is_rescheduled');
$tip_amount = Yii::app()->request->getParam('tip_amount');
$admin_permit = Yii::app()->request->getParam('admin_permit');

        $result= 'false';
        $response= 'Pass the required parameters';
        $json= array();

		if((isset($wash_request_id) && !empty($buzz_status)))
        {

			$washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
			$buzzstatus = $washrequestmodel->buzz_status;
			if($buzzstatus==0)
			{
			   $washrequestmodel->buzz_status = $buzz_status;
			}
			else
			{

				$buzzstatus = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));
				$buzz = $buzzstatus->buzz_status;
			   $washrequestmodel->buzz_status = $buzz+1;
			}



			$resUpdate = $washrequestmodel->save(false);
			/* Notifictaion Message Sent*/
			$cust_id = $washrequestmodel->customer_id;
			$cust_details = Customers::model()->findByAttributes(array('id'=>$cust_id));
			$notify_token = '';
			$notify_msg = '';
			$notify_token = $cust_details->device_token;
			$device_type = strtolower($cust_details->mobile_type);
			$alert_type = "default";

			$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '10' ")->queryAll();
			$notify_msg = $pushmsg[0]['message'];

			//$notify_msg = "Please meet your washer outside";
			$alert_type = "buzz";

			$notify_msg = urlencode($notify_msg);

			$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
		   // echo $notifyurl;die;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$notifyurl);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

			if($notify_msg) $notifyresult = curl_exec($ch);
			curl_close($ch);
			/*End Notification*/
			if($resUpdate){
				$result= 'true';
				$sound= 'buzzsound.mp3';
				$response= 'Wash buzz status changed';
			}
        }

		elseif($wash_request_id && is_numeric($is_scheduled))
        {
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));

			if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }
            else{

                $result= 'true';
                $response= 'wash request updated';

                if(!$schedule_date){
                    $schedule_date = $wrequest_id_check->schedule_date;
                }

				if(!$schedule_time){
                    $schedule_time = $wrequest_id_check->schedule_time;
                }

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

				if(!$checklist){
                   $checklist = $wrequest_id_check->checklist;
                }
				if(!$notes){
                   $notes = $wrequest_id_check->notes;
                }

if(!isset($car_ids)){
                   $car_ids = $wrequest_id_check->car_list;
                }

if(!$package_list){
                   $package_list = $wrequest_id_check->package_list;
                }

if(!$address_type){
                   $address_type = $wrequest_id_check->address_type;
                }

if(!$eta){
                   $eta = $wrequest_id_check->estimate_time;
                }

if(!$scheduled_cars_info){
                   $scheduled_cars_info = $wrequest_id_check->scheduled_cars_info;
                }

if(!$schedule_total){
                   $schedule_total = $wrequest_id_check->schedule_total;
                }

if(!$schedule_company_total){
                   $schedule_company_total = $wrequest_id_check->schedule_company_total;
                }

if(!$schedule_agent_total){
                   $schedule_agent_total = $wrequest_id_check->schedule_agent_total;
                }

if(!$tip_amount){
                   $tip_amount = $wrequest_id_check->tip_amount;
                }


                /* IF ADDRESS NOT FOUND IN DEFAULT PARAMETERES THEN EXSITING ADDRESS WILL BE USED FROM DB */
				if(!isset($address) && empty($address)){
					$address = $wrequest_id_check->address;
				}
				/* IF LAT NOT FOUND IN DEFAULT PARAMETERES THEN EXSITING LAT VALUE WILL BE USED FROM DB */
				if(empty($lat)){
					$lat = $wrequest_id_check->latitude;
				}
				/* IF LONG NOT FOUND IN DEFAULT PARAMETERS THEN EXSITING LONG VALUE WILL BE USED FROM DB */
				if(empty($long)){
					$long = $wrequest_id_check->longitude;
				}


                /* ------- overlapping wash check for agent ----- */

				$model_NewRqst =  Washingrequests::model()->findByPk($wash_request_id);


				$new_schedule_date = $model_NewRqst->schedule_date;
				$new_schedule_time = $model_NewRqst->schedule_time;

                $washtime = 0;

					$cars = explode(",",$model_NewRqst->car_list);
					$plans = explode(",",$model_NewRqst->package_list);
					foreach($cars as $ind=>$car){
						$car_detail =  Vehicle::model()->findByPk($car);
						//echo $car_detail->brand_name." ".$car_detail->model_name."<br>";

						$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/plans");
						$data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build);
						curl_setopt($handle, CURLOPT_POST, true);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
						$plan_result = curl_exec($handle);
						curl_close($handle);
						$jsondata = json_decode($plan_result);
						if($plans[$ind] == 'Deluxe'){
							//echo $jsondata->plans->deluxe[0]->wash_time."<br>";
							$delprice = intval($jsondata->plans->deluxe[0]->wash_time);
							$washtime += $delprice;
						}

						if($plans[$ind] == 'Premium'){
							//echo $jsondata->plans->premium[0]->wash_time."<br>";
							$premprice = intval($jsondata->plans->premium[0]->wash_time);
							$washtime += $premprice;
						}

                         /* --- addons time ----- */



$pet_hair_vehicles_arr = explode(",", $model_NewRqst->pet_hair_vehicles);
if (in_array($car, $pet_hair_vehicles_arr)) $washtime += 5;

$lifted_vehicles_arr = explode(",", $model_NewRqst->lifted_vehicles);
if (in_array($car, $lifted_vehicles_arr)) $washtime += 5;

$exthandwax_vehicles_arr = explode(",", $model_NewRqst->exthandwax_vehicles);
if (in_array($car, $exthandwax_vehicles_arr)) $washtime += 10;

$extplasticdressing_vehicles_arr = explode(",", $model_NewRqst->extplasticdressing_vehicles);
if (in_array($car, $extplasticdressing_vehicles_arr)) $washtime += 5;

$extclaybar_vehicles_arr = explode(",", $model_NewRqst->extclaybar_vehicles);
if (in_array($car, $extclaybar_vehicles_arr)) $washtime += 15;

$waterspotremove_vehicles_arr = explode(",", $model_NewRqst->waterspotremove_vehicles);
if (in_array($car, $waterspotremove_vehicles_arr)) $washtime += 10;

                   /* --- addons time end ----- */

					}

					$washtime += 30;

//echo $washtime."<br>";

if($model_NewRqst->reschedule_time) {
  $currentwashtotalscheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->reschedule_date.' '.$model_NewRqst->reschedule_time." +".$washtime." minutes"));
  $currentwashbasescheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->reschedule_date.' '.$model_NewRqst->reschedule_time));
}
else {
    $currentwashtotalscheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->schedule_date.' '.$model_NewRqst->schedule_time." +".$washtime." minutes"));
     $currentwashbasescheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->schedule_date.' '.$model_NewRqst->schedule_time));
}

//echo "currentwashtotalscheduletime ".$currentwashtotalscheduletime."<br>";
//echo "currentwashbasescheduletime ".$currentwashbasescheduletime."<br>";
 $agenttakenwashes = Washingrequests::model()->findAll(array("condition"=>"agent_id =" . $agent_id." AND status = 0 AND is_scheduled = 1"));

 if(count($agenttakenwashes)){
 foreach($agenttakenwashes as $agtwash){

  $washtime = 0;

					$cars = explode(",",$agtwash->car_list);
					$plans = explode(",",$agtwash->package_list);
					foreach($cars as $ind=>$car){
						$car_detail =  Vehicle::model()->findByPk($car);
						//echo $car_detail->brand_name." ".$car_detail->model_name."<br>";

						$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/plans");
						$data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build);
						curl_setopt($handle, CURLOPT_POST, true);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
						$plan_result = curl_exec($handle);
						curl_close($handle);
						$jsondata = json_decode($plan_result);
						if($plans[$ind] == 'Deluxe'){
							//echo $jsondata->plans->deluxe[0]->wash_time."<br>";
							$delprice = intval($jsondata->plans->deluxe[0]->wash_time);
							$washtime += $delprice;
						}

						if($plans[$ind] == 'Premium'){
							//echo $jsondata->plans->premium[0]->wash_time."<br>";
							$premprice = intval($jsondata->plans->premium[0]->wash_time);
							$washtime += $premprice;
						}

                         /* --- addons time ----- */

$pet_hair_vehicles_arr = explode(",", $agtwash->pet_hair_vehicles);
if (in_array($car, $pet_hair_vehicles_arr)) $washtime += 5;

$lifted_vehicles_arr = explode(",", $agtwash->lifted_vehicles);
if (in_array($car, $lifted_vehicles_arr)) $washtime += 5;

$exthandwax_vehicles_arr = explode(",", $agtwash->exthandwax_vehicles);
if (in_array($car, $exthandwax_vehicles_arr)) $washtime += 10;

$extplasticdressing_vehicles_arr = explode(",", $agtwash->extplasticdressing_vehicles);
if (in_array($car, $extplasticdressing_vehicles_arr)) $washtime += 5;

$extclaybar_vehicles_arr = explode(",", $agtwash->extclaybar_vehicles);
if (in_array($car, $extclaybar_vehicles_arr)) $washtime += 15;

$waterspotremove_vehicles_arr = explode(",", $agtwash->waterspotremove_vehicles);
if (in_array($car, $waterspotremove_vehicles_arr)) $washtime += 10;

                   /* --- addons time end ----- */

					}

					$washtime += 30;

//echo $washtime."<br>";

if($agtwash->reschedule_time) {
  $agtwashtotalscheduletime = date('Y-m-d h:i A', strtotime($agtwash->reschedule_date.' '.$agtwash->reschedule_time." +".$washtime." minutes"));
  $agtwashbasescheduletime = date('Y-m-d h:i A', strtotime($agtwash->reschedule_date.' '.$agtwash->reschedule_time));
}
else {
   $agtwashtotalscheduletime = date('Y-m-d h:i A', strtotime($agtwash->schedule_date.' '.$agtwash->schedule_time." +".$washtime." minutes"));
   $agtwashbasescheduletime = date('Y-m-d h:i A', strtotime($agtwash->schedule_date.' '.$agtwash->schedule_time));
}

//echo "agtwashtotalscheduletime ".$agtwashtotalscheduletime."<br>";
//echo "agtwashbasescheduletime ".$agtwashbasescheduletime."<br>";


if ((!$status) && $agent_id && !$admin_permit) {

    if(strtotime($currentwashbasescheduletime) >= strtotime($agtwashbasescheduletime)){

//echo "currentwashtotalscheduletime ".strtotime($currentwashtotalscheduletime)."<br>";
//echo "agtwashtotalscheduletime ".strtotime($agtwashtotalscheduletime)."<br>";

         if(strtotime($agtwashtotalscheduletime) > strtotime($currentwashbasescheduletime)){
      		$result= 'false';
					$response= 'Sorry, you have an overlapping appointment with this schedule.';

 $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
die();
    }
    }

      if(strtotime($currentwashbasescheduletime) <= strtotime($agtwashbasescheduletime)){
         if(strtotime($currentwashtotalscheduletime) > strtotime($agtwashbasescheduletime)){
      		$result= 'false';
					$response= 'Sorry, you have an overlapping appointment with this schedule.';

 $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
die();
    }
    }



				}
 }
 }

 /* ------- overlapping wash check for agent end ------- */


 if(Yii::app()->request->getParam('savejob') == 1){

     if($wrequest_id_check->agent_id != 0){
         	$result= 'false';
			$response= 'Sorry, this order is already taken by another washer';
         $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
        die();
     }
 }


     Washingrequests::model()->updateByPk($wash_request_id, array("address" => $address, "latitude" => $lat, "longitude" => $long, "address_type" => $address_type, "estimate_time" => $eta, "car_list" => $car_ids, "package_list" => $package_list, "is_scheduled" => $is_scheduled, "schedule_date" => $schedule_date, "schedule_time" => $schedule_time, "reschedule_date" => $reschedule_date, "reschedule_time" => $reschedule_time, "status" => $status, "agent_id" => $agent_id, "checklist" => $checklist, "notes" => $notes, "scheduled_cars_info" => $scheduled_cars_info, "schedule_total" => $schedule_total, "schedule_company_total" => $schedule_company_total, "schedule_agent_total" => $schedule_agent_total, "tip_amount" => $tip_amount));

					if($is_rescheduled == 1){
$mobile_receipt = '';

					 $customers_id_check = Customers::model()->findByAttributes(array("id"=>$wrequest_id_check->customer_id));

					$from = Vargas::Obj()->getAdminEmail();
										//echo $from;
										$sched_date = '';
										if(strtotime($reschedule_date) == strtotime(date('Y-m-d'))){
											$sched_date = 'Today';
										}
										else{
											$sched_date = date('M d', strtotime($reschedule_date));
										}
										$message = '';
										$subject = 'Re-Scheduled Order Receipt - #0000'.$wash_request_id;
										//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
										$message = "<div class='block-content' style='background: #fff; text-align: left;'>
										<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
										<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order has been re-scheduled for ".$sched_date." @ ".$reschedule_time."</p>
										<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$wrequest_id_check->address."</p>";
										$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
										<tr><td><strong>".$customers_id_check->customername."</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000".$wash_request_id."</td></tr>
										</table>";

										$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
										$all_cars = explode("|", $wrequest_id_check->scheduled_cars_info);
										foreach($all_cars as $ind=>$vehicle){
											$car_details = explode(",", $vehicle);
$mobile_receipt .= $car_details[0]." ".$car_details[1]."\r\n".$car_details[2]." $".$car_details[4]."\r\nHandling $1.00\r\n";
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
if($car_details[12]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[12], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Wax $".number_format($car_details[12], 2)."\r\n";
						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[13], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Dressing $".number_format($car_details[13], 2)."\r\n";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[14], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Clay $".number_format($car_details[14], 2)."\r\n";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($car_details[15], 2)."</p></td>
							</tr>";
$mobile_receipt .= "Spot $".number_format($car_details[15], 2)."\r\n";
						}
											if($car_details[5]){
												$message .= "<tr>
												<td>
												<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
												</td>
												<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
												</tr>";
$mobile_receipt .= "Hair $5.00\r\n";
											}
											if($car_details[6]){
												$message .= "<tr>
												<td>
												<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
												</td>
												<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$5.00</p></td>
												</tr>";
$mobile_receipt .= "Lifted $5.00\r\n";
											}

											if($car_details[8]){
												$message .= "<tr>
												<td>
												<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
												</td>
												<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
												</tr>";
$mobile_receipt .= "Bundle -$1.00\r\n";
											}

											if($car_details[9]){
												$message .= "<tr>
												<td>
												<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
												</td>
												<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".$car_details[9].".00</p></td>
												</tr>";
$mobile_receipt .= "1st -$".number_format($car_details[9], 2)."\r\n";
											}

											if($car_details[10]){
												$message .= "<tr>
												<td>
												<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
												</td>
												<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$5.00</p></td>
												</tr>";
$mobile_receipt .= "5th -$".number_format($car_details[10], 2)."\r\n";
											}

											$message .= "</table></td></tr>";
  $mobile_receipt .= "------\r\n";
										}

if($wrequest_id_check->tip_amount){
												$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
												<td style='padding-bottom: 15px;'>
												<p style='font-size: 18px; margin: 0;'>Tip</p>
												</td>
												<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$".number_format($wrequest_id_check->tip_amount, 2)."</p></td>
												</tr></table>";
$mobile_receipt .= "Tip $".number_format($tip_amount, 2)."\r\n";
											}

					if($wrequest_id_check->coupon_discount){
												$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
												<td style='padding-bottom: 15px;'>
												<p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
												</td>
												<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-$".number_format($wrequest_id_check->coupon_discount, 2)."</p></td>
												</tr></table>";
$mobile_receipt .= "Coupon -$".number_format($coupon_amount, 2)."\r\n";
											}



										$message .= "</table>";

										$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
										<tr>
										<td></td>
										<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$wrequest_id_check->schedule_total."</span></p></td></tr></table>";
$mobile_receipt .= "Total: $".$wrequest_id_check->schedule_total."\r\n";

										$message .= "<p style='text-align: center; font-size: 18px;'>To cancel visit your account history by<br>logging in to <a href='https://www.mobilewash.com'>Mobilewash.com</a></p>";
										$message .= "<p style='text-align: center; font-size: 20px; margin-bottom: 0;'>*$10 cancellation fee will apply for cancelling <br>within 30 minutes of your scheduled wash time</p>";

										Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
										Vargas::Obj()->SendMail("admin@mobilewash.com","info@mobilewash.com",$message,$subject, 'mail-receipt');


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


            $message = "Order #".$wash_request_id." has been re-scheduled at ".$sched_date." @ ".$reschedule_time."\r\n".$customers_id_check->customername."\r\n".$customers_id_check->contact_number."\r\n".$wrequest_id_check->address."\r\n------\r\n".$mobile_receipt;

            $sendmessage = $client->account->messages->create(array(
                'To' =>  '9098023158',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '8183313631',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '3109999334',
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));

					}


					if(($is_scheduled) && ($agent_id) && ($status == 1)){
						$cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);

						$clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '".$wrequest_id_check->customer_id."' ")->queryAll();

						/* --- notification call --- */

						$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '17' ")->queryAll();
						$message = $pushmsg[0]['message'];

						foreach( $clientdevices as $ctdevice){
							//$message =  "You have a new scheduled wash request.";
							//echo $agentdetails['mobile_type'];
							$device_type = strtolower($ctdevice['device_type']);
							$notify_token = $ctdevice['device_token'];
								$alert_type = "schedule";
							$notify_msg = urlencode($message);

							$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
							file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$notifyurl);
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

							if($notify_msg) $notifyresult = curl_exec($ch);
							curl_close($ch);
						}
												/* --- notification call end --- */
					}

            }
        }

        elseif((isset($agent_id) && !empty($agent_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))){

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));
            $agent_id_check = Agents::model()->findByAttributes(array('id'=>$agent_id));

            if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }

            else if(!count($agent_id_check)){
                $result= 'false';
                $response= 'Invalid agent id';
            }

            else{

                $washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
                if($washrequestmodel->status == WASHREQUEST_STATUS_ACCEPTED && $washrequestmodel->agent_id != $agent_id){
					$result= 'false';
					$response= 'Wash request already accepted by other agent';
                }else if($status == WASHREQUEST_STATUS_CANCELWASH_BYCLIENT){
                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);

                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);

                    if($resUpdate){
                        $result= 'true';
                        $response= 'Wash request cancelled';
                    }
                    else{
                        $result= 'false';
                        $response= 'Wash request not cancelled';
                    }
                }else if($status == WASHREQUEST_STATUS_CANCELWASH_BYAGENT){
                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);

                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);

                    if($resUpdate){
                        $result= 'true';
                        $response= 'Wash request cancelled By Agent';
                    }
                    else{
                        $result= 'false';
                        $response= 'Wash request not cancelled';
                    }
                } else {
                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 0;
                    $agentmodel->save(false);

                    $washrequestmodel->agent_id = $agent_id;
                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);
                    if($resUpdate){
                        $result= 'true';
                        $response= 'Wash request status changed';
                    }
                    else{
                        $result= 'false';
                        $response= 'Wash request status not changed';
                    }
                }
                if($status == WASHREQUEST_STATUS_COMPLETEWASH){

					/* ----------- update pricing details -------------- */

					$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
					$data = array('wash_request_id' => $wash_request_id);
					curl_setopt($handle, CURLOPT_POST, true);
					curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
					curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
					$kartresult = curl_exec($handle);
					curl_close($handle);
					$kartdetails = json_decode($kartresult);

					$washrequestmodel->total_price = $kartdetails->total_price;
					$washrequestmodel->net_price = $kartdetails->net_price;
					$washrequestmodel->company_total = $kartdetails->company_total;
					$washrequestmodel->agent_total = $kartdetails->agent_total;
					$washrequestmodel->bundle_discount = $kartdetails->bundle_discount;
					//$washrequestmodel->fifth_wash_discount = $kartdetails->fifth_wash_discount;
					$washrequestmodel->first_wash_discount = $kartdetails->first_wash_discount;
					$washrequestmodel->coupon_discount = $kartdetails->coupon_discount;
					$cust_details = Customers::model()->findByAttributes(array('id'=>$washrequestmodel->customer_id));
					$washrequestmodel->customer_wash_points = $cust_details->fifth_wash_points;

					$resUpdate = $washrequestmodel->save(false);


					/* ----------- update pricing details end -------------- */

                     $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    //$agentmodel->save(false);

                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",",$car_ids);

 Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 1));

                    /* ----------- 5th wash check ----------- */
                    /*
                    $get_fifth_wash = 0;
                    $cust_details = Customers::model()->findByAttributes(array('id'=>$wrequest_id_check->customer_id));
                    $current_points = $cust_details->fifth_wash_points;
                    //echo $current_points;
                    $new_points = $current_points + count($car_ids_arr);
                     //echo $new_points;
                    if($new_points >= 5){
                       $get_fifth_wash = 1;
                       $new_points -= 5;
                    }
                    */
                    /* ----------- 5th wash check end ----------- */

                    foreach($car_ids_arr as $car){

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
                    }
                    $cust_id = $wrequest_id_check->customer_id;
                    $completed_washes_agent = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$agent_id, 'status'=> WASHREQUEST_STATUS_COMPLETEWASH));
                    $completed_washes_cust = Washingrequests::model()->findAllByAttributes(array('customer_id'=>$cust_id, 'status'=> WASHREQUEST_STATUS_COMPLETEWASH));
                    $total_washes_agent = count($completed_washes_agent);
                    $total_washes_cust = count($completed_washes_cust);
                    $total_wash_data_agent= array('total_wash' => $total_washes_agent);
                    $total_wash_data_cust= array('total_wash' => $total_washes_cust);
                    $Customers = new Customers;
                    $Agents = new Agents;
                    $Customers->updateAll($total_wash_data_cust, 'id=:id', array(':id'=>$cust_id));
                    $Agents->updateAll($total_wash_data_agent, 'id=:id', array(':id'=>$agent_id));

if($tip_amount){

Washingrequests::model()->updateAll(array('tip_amount' => $tip_amount), 'id=:id', array(':id'=>$wash_request_id));
                }

                    /* ------- send receipt ----------- */
					if($wrequest_id_check->status != 4){
						$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/sendorderreceipts");
						$data = array('wash_request_id' => $wash_request_id, 'customer_id' =>$cust_id, 'agent_id'=> $agent_id);
						curl_setopt($handle, CURLOPT_POST, true);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
						$receiptresult = curl_exec($handle);
						curl_close($handle);
						$receiptdetails = json_decode($receiptresult);
						//var_dump($jsondata);
					}

					/* ------- send receipt end ----------- */
                }

                /* --- notification call --- */

                $cust_id = $wrequest_id_check->customer_id;
                $cust_details = Customers::model()->findByAttributes(array('id'=>$cust_id));
                $notify_token = '';
                $notify_msg = '';
                $notify_token = $cust_details->device_token;
                $device_type = strtolower($cust_details->mobile_type);
                $alert_type = "default";
                if($status == WASHREQUEST_STATUS_ACCEPTED){

					$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '11' ")->queryAll();
					$notify_msg = $pushmsg[0]['message'];

                    //$notify_msg = "An agent has been found and is on the way.";
                    $alert_type = "strong";
                       $washrequestmodel->wash_begin = date("Y-m-d H:i:s");
                    $resUpdate = $washrequestmodel->save(false);
                }

                if($status == WASHREQUEST_STATUS_AGENTARRIVED){

					$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '10' ")->queryAll();
					$notify_msg = $pushmsg[0]['message'];

                    //$notify_msg = "Please meet your washer outside";
                    $alert_type = "soft";
                }

                if($status == WASHREQUEST_STATUS_AGENTARRIVED_CONFIRMED_BYCLIENT){

					$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '13' ")->queryAll();
					$notify_msg = $pushmsg[0]['message'];

                    //$notify_msg = "Agent begins car inspection process.";
                    $alert_type = "strong";

                }

                if($status == WASHREQUEST_STATUS_COMPLETEWASH){

					$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '14' ")->queryAll();
					$notify_msg = $pushmsg[0]['message'];

                    //$notify_msg = "All car washes complete. Thank you.";
                    $alert_type = "soft";
                }

                $notify_msg = urlencode($notify_msg);

                $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
               // echo $notifyurl;die;
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$notifyurl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                if($notify_msg) $notifyresult = curl_exec($ch);
                curl_close($ch);

                if($status == WASHREQUEST_STATUS_COMPLETEWASH){

					$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '15' ")->queryAll();
					$notify_msg2 = $pushmsg[0]['message'];

                    //$notify_msg2 = "Wash Complete!";
                    $notify_msg2 = urlencode($notify_msg2);

                    $agent_id = $wrequest_id_check->agent_id;
                    $agent_details = Agents::model()->findByAttributes(array('id'=>$agent_id));
                    $notify_token2 = '';

                    $notify_token2 = $agent_details->device_token;
                    $device_type2 = 'ios';
                    $device_type2 = strtolower($agent_details->mobile_type);
                    $notifyurl2 = "https://www.mobilewash.com/push-notifications/".$device_type2."/?device_token=".$notify_token2."&msg=".$notify_msg2;
                    $ch2 = curl_init();
                    curl_setopt($ch2,CURLOPT_URL,$notifyurl2);
                    curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);

                    if($notify_msg2)
                    {
                        $notifyresult2 = curl_exec($ch2);
                    }
                    curl_close($ch2);
                }
                //var_dump($notifyresult);
                /* --- notification call end --- */
            }


        }
        else{
            $result= 'false';
            $response= 'Pass the required parameters';

        }
        $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
    }

    /*
   ** Returns check wash request status.
   ** Post Required: customer id, wash request id
   ** Url:- http://www.demo.com/index.php?r=washing/checkwashrequeststatus
   ** Purpose:- check wash request status
   */
    public function actioncheckwashrequeststatus(){
        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $result= 'false';
        $response= 'Pass the required parameters';
        $json= array();
        $agent_details = new stdClass();
        $customer_details = new stdClass();
        $car_types = '';
        if((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id))){

            $customer_id_check = Customers::model()->findByAttributes(array('id'=>$customer_id));
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id, 'customer_id'=> $customer_id));


                      /* ------- get nearest agents --------- */

$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/getnearestagents");
$data = array('wash_request_id' => $wash_request_id);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$output = curl_exec($handle);
curl_close($handle);
$nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */


            if(!count($customer_id_check)){
                $result= 'false';
                $response= 'Invalid customer id';
            }

            else if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }

 else if(($wrequest_id_check->status == 0) && ($nearagentsdetails->result == 'false')){


/* ---- time check --- */

$wash_time = strtotime($wrequest_id_check->created_date);
$now_time = time();
$time_diff = round(abs($now_time - $wash_time) / 60,2);

/* ---- time check end ---- */


if($time_diff > 1){
 $result= 'false';
                $response= 'no washers available';
 Washingrequests::model()->updateByPk($wash_request_id, array( 'status' => 5, 'no_washer_cancel' => 1));
}
else{
 $result= 'false';
                $response= 'searching washer';
}
               


            }

             else if(($wrequest_id_check->status == 0) && ($wrequest_id_check->is_two_loops_reject)){
                $result= 'false';
                $response= 'no washers available';
 Washingrequests::model()->updateByPk($wash_request_id, array( 'status' => 5));
            }


            else{
                $wrequest_obj = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id, 'customer_id'=> $customer_id));

                $response = $wrequest_obj->status;
                $buzz_status = $wrequest_obj->buzz_status;
                $result= 'true';
                if($response){



                    $agent_id = $wrequest_obj->agent_id;
                    $agent_obj = Agents::model()->findByAttributes(array('id'=>$agent_id));
                    $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id'=>$agent_id));
                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_id));

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
if(trim($agent_obj->last_name)) $agentlname = strtoupper(substr($agent_obj->last_name, 0, 1)).".";
else $agentlname = $agent_obj->last_name;

                    $agentname = $agent_obj->first_name." ".$agentlname;
                    $agent_details->id = $agent_id;
                    $agent_details->name = $agentname;
                    $agent_details->phone = $agent_obj->phone_number;
                    $agent_details->total_washes = $agent_obj->total_wash;
                    $agent_details->rating = $agent_obj->rating;
                    $agent_details->photo = $agent_obj->image;
                    $agent_details->latitude = $agent_loc_obj->latitude;
                    $agent_details->longitude = $agent_loc_obj->longitude;

                    $cust_loc_obj = CustomerLocation::model()->findByAttributes(array('customer_id'=>$customer_id));

                    $customer_details->latitude = $wrequest_obj->latitude;
                    $customer_details->longitude = $wrequest_obj->longitude;

                    $car_ids = $wrequest_obj->car_list;
                    $car_ids = explode(",", $car_ids);
                    foreach ($car_ids as $cid){
                        $cars_obj = Vehicle::model()->findByAttributes(array('id'=>$cid));
                        $car_types.=  $cars_obj->vehicle_type.",";
                    }
                    $car_types = rtrim($car_types, ',');
                    $customer_details->cartypes = $car_types;

                    if(!$wrequest_obj->washer_arrival_notify){

                    /* ------------- Checek agent arrival distance ---------- */

                       /* --- Google Distance call --- */

      //echo "customer locations: ".$latitude.",".$longitude."<br>";
    $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$agent_loc_obj->latitude.",".$agent_loc_obj->longitude."&destinations=".$wrequest_obj->latitude.",".$wrequest_obj->longitude."&mode=driving&language=en-EN&sensor=false&key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA";

    $ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$geourl);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

$georesult = curl_exec($ch);
curl_close($ch);
$geojsondata = json_decode($georesult);
//print_r($geojsondata);
$eta = $geojsondata->rows[0]->elements[0]->duration->value;

if($eta <= 60){
    /* --- notification call --- */

$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '16' ")->queryAll();
$message = $pushmsg[0]['message'];

                            //$message =  "Washer arriving within 1 minute";
                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($customer_id_check->mobile_type);
                            $notify_token = $customer_id_check->device_token;
                            $alert_type = "strong";
                            $notify_msg = urlencode($message);

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;

                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);

                            /* --- notification call end --- */
                              Washingrequests::model()->updateByPk($wash_request_id, array( 'washer_arrival_notify' => 1 ));

}


               /* ------------- Checek agent arrival distance end ---------- */
                 }
                }
            }


        }
        else{
            $result= 'false';
            $response= 'Pass the required parameters';

        }
        if($response){
            $json= array(
                'result'=> $result,
                'response'=> $response,
                'buzz_status'=> $buzz_status,
                'agent_details' => $agent_details,
                'customer_details' => $customer_details
            );
        }
        else{
            $json= array(
                'result'=> $result,
                'response'=> $response
            );
        }
        echo json_encode($json);
    }



    /*
    ** Returns totals and washed car lists.
    ** Post Required: customer id,wash_request_id,comments,ratings
    ** Url:- http://www.demo.com/index.php?r=washing/customerfeedback
    ** Purpose:- Getting totals and washed car lists
    */
    public function actioncustomerfeedback(){

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');
        $ratings = 5.00;
        $ratings = Yii::app()->request->getParam('ratings');

        $json = array();
        $car_id_check = true;
        $washrequest_id_check = true;
        $result= 'false';
        $response= 'Pass the required parameters';

        if((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id))) {
            $customers_id_check = Customers::model()->findByAttributes(array("id"=>$customer_id));
            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id, "customer_id"=> $customer_id));
            $cust_feedback_check = Washingfeedbacks::model()->findByAttributes(array("wash_request_id"=>$wash_request_id));

            if(!count( $customers_id_check)){
                $response= 'Invalid customer';
            }

            else if(!count($washrequest_id_check)){
                $response= 'Invalid wash request id';
            }


            else{
                if(!count($cust_feedback_check)){
                    $washfeedbackdata= array(
                        'customer_id'=> $customer_id,
'agent_id'=> $washrequest_id_check->agent_id,
                        'wash_request_id'=> $wash_request_id,
                        'customer_comments'=> $comments,
                        'customer_ratings'=> $ratings,
                    );

                    Yii::app()->db->createCommand()->insert('washing_feedbacks', $washfeedbackdata);

                 /* ------------ calculate agent average feedback ---------------- */


                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $washrequest_id_check->agent_id));
                $total_rate = count($agent_feedbacks);
                if($total_rate){
                    $rate = 0;
                    foreach($agent_feedbacks as $agent_feedback){
                        $rate += $agent_feedback->customer_ratings;
                    }

                    $agent_rate =  $rate/$total_rate;
                    $agent_rate = number_format($agent_rate, 2);

                }
                else{
                    $agent_rate = 5.00;

                }

                $agentmodel = new Agents;
                $agentmodel->updateAll(array("rating"=> $agent_rate), 'id=:id', array(':id'=>$washrequest_id_check->agent_id));

                /* ------------ calculate agent average feedback end ---------------- */

                }
                else{
                    $washfeedbackdata= array(
                        'customer_id'=> $customer_id,
                        'customer_comments'=> $comments,
                        'customer_ratings'=> $ratings,
                    );
                    $washfeedbackmodel = new Washingfeedbacks;

                    $washfeedbackmodel->attributes= $washfeedbackdata;
                    $washfeedbackmodel->updateAll($washfeedbackdata, 'wash_request_id=:wash_request_id', array(':wash_request_id'=>$wash_request_id));



                /* ------------ calculate agent average feedback ---------------- */

                $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $washrequest_id_check->agent_id));
                $total_rate = count($agent_feedbacks);
                if($total_rate){
                    $rate = 0;
                    foreach($agent_feedbacks as $agent_feedback){
                        $rate += $agent_feedback->customer_ratings;
                    }

                    $agent_rate =  $rate/$total_rate;
                    $agent_rate = number_format($agent_rate, 2);
                }
                else{
                    $agent_rate = 5.00;
                }



                $agentmodel = new Agents;
                $agentmodel->updateAll(array("rating"=> $agent_rate), 'id=:id', array(':id'=>$washrequest_id_check->agent_id));

                /* ------------ calculate agent average feedback end ---------------- */
                }

                $result= 'true';
                $response= "Feeback added";

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id, "customer_id"=> $customer_id));

                $plan_ids = '';
                $car_ids = '';

                $plan_ids = $washrequests_data->package_list;
                $car_ids = $washrequests_data->car_list;

                $plan_ids = explode(",", $plan_ids);
                $car_ids = explode(",", $car_ids);
                //var_dump($plan_ids);
                //var_dump($car_ids);

                $total = 0;

                $vehicles = array();
                foreach($car_ids as $index=>$car_id){
                    $cardata =  Vehicle::model()->findByAttributes(array("id"=>$car_id));
                    $vehicle_no =  $cardata->vehicle_no;
                    $brand_name =  $cardata->brand_name;
                    $model_name =  $cardata->model_name;
                    $vehicle_type =  $cardata->vehicle_type;
                    $vehicle_image =  $cardata->vehicle_image;
                    $vehicles[] = array('vehicle_no'=> $vehicle_no, 'brand_name'=> $brand_name, 'model_name'=> $model_name, 'vehicle_type'=> $vehicle_type, 'vehicle_image'=> $vehicle_image);
                    $plandata =  Washingplans::model()->findByAttributes(array("title"=>$plan_ids[$index], "vehicle_type"=>$vehicle_type));
                    $price =  $plandata->price;
                    $fee = $plandata->handling_fee;
                    $total+= $price + $fee;
                }

                //echo $total;
                $total = number_format($total, 2, '.', '');



$message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer Feedback</h2>
<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000".$wash_request_id."</p>
<p><b>Customer Name:</b> ".$customers_id_check->customername."</p>
<p><b>Customer Email:</b> ".$customers_id_check->email."</p>
<p><b>Rating by Customer:</b> ".number_format($ratings, 2)."</p>
<p><b>Comments:</b> ".$comments."</p>";


Vargas::Obj()->SendMail("admin@mobilewash.com","info@mobilewash.com",$message,"Customer Feedback - Order #0000".$wash_request_id, 'mail-receipt');


            }
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'total'=> $total,
            'vehicles'=>$vehicles

        );

        echo json_encode($json); die();
    }

    /*
	** Returns totals and washed car lists.
	** Post Required: agent id,wash_request_id,comments,ratings
	** Url:- http://www.demo.com/index.php?r=washing/agentfeedback
	** Purpose:- Getting totals and washed car lists
	*/
    public function actionagentfeedback(){

        $agent_id = Yii::app()->request->getParam('agent_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');
        $ratings = 5.00;
        $ratings = Yii::app()->request->getParam('ratings');

        $json = array();
        $car_id_check = true;
        $washrequest_id_check = true;
        $result= 'false';
        $response= 'Pass the required parameters';

        if((isset($agent_id) && !empty($agent_id)) && (isset($wash_request_id) && !empty($wash_request_id))) {
            $agents_id_check = Agents::model()->findByAttributes(array("id"=>$agent_id));
            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));
            $agent_feedback_check = Washingfeedbacks::model()->findByAttributes(array("wash_request_id"=>$wash_request_id));

            if(!count( $agents_id_check)){
                $response= 'Invalid agent';
            }

            else if(!count($washrequest_id_check)){
                $response= 'Invalid wash request id';
            }


            else{
                if(!count($agent_feedback_check)){
                    $washfeedbackdata= array(
                        'agent_id'=> $agent_id,
'customer_id'=> $washrequest_id_check->customer_id,
                        'wash_request_id'=> $wash_request_id,
                        'agent_comments'=> $comments,
                        'agent_ratings'=> $ratings,
                    );

                    Yii::app()->db->createCommand()->insert('washing_feedbacks', $washfeedbackdata);

                     /* ------------ calculate customer average feedback ---------------- */

                $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $washrequest_id_check->customer_id));
                $total_rate = count($cust_feedbacks);
                if($total_rate){
                    $rate = 0;
                    foreach($cust_feedbacks as $cust_feedback){
                        $rate += $cust_feedback->agent_ratings;
                    }

                    $cust_rate =  $rate/$total_rate;
                    $cust_rate = number_format($cust_rate, 2);
                }
                else{
                    $cust_rate = 5.00;
                }

                $custmodel = new Customers;
                $custmodel->updateAll(array("rating"=> $cust_rate), 'id=:id', array(':id'=>$washrequest_id_check->customer_id));

                /* ------------ calculate customer average feedback end ---------------- */

                }
                else{
                    $washfeedbackdata= array(
                        'agent_id'=> $agent_id,
                        'agent_comments'=> $comments,
                        'agent_ratings'=> $ratings,
                    );
                    $washfeedbackmodel = new Washingfeedbacks;

                    $washfeedbackmodel->attributes= $washfeedbackdata;
                    $washfeedbackmodel->updateAll($washfeedbackdata, 'wash_request_id=:wash_request_id', array(':wash_request_id'=>$wash_request_id));

                       /* ------------ calculate customer average feedback ---------------- */

                $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $washrequest_id_check->customer_id));
                $total_rate = count($cust_feedbacks);
                if($total_rate){
                    $rate = 0;
                    foreach($cust_feedbacks as $cust_feedback){
                        $rate += $cust_feedback->agent_ratings;
                    }

                    $cust_rate =  $rate/$total_rate;
                    $cust_rate = number_format($cust_rate, 2);
                }
                else{
                    $cust_rate = 5.00;
                }

                $custmodel = new Customers;
                $custmodel->updateAll(array("rating"=> $cust_rate), 'id=:id', array(':id'=>$washrequest_id_check->customer_id));

                /* ------------ calculate customer average feedback end ---------------- */

                }

                $result= 'true';
                $response= "Feeback added";

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id, "agent_id"=> $agent_id));

                $plan_ids = '';
                $car_ids = '';

                $plan_ids = $washrequests_data->package_list;
                $car_ids = $washrequests_data->car_list;

                $plan_ids = explode(",", $plan_ids);
                $car_ids = explode(",", $car_ids);
                //var_dump($plan_ids);
                //var_dump($car_ids);

                $total = 0;

                $vehicles = array();
                foreach($car_ids as $index=>$car_id){
                    $cardata =  Vehicle::model()->findByAttributes(array("id"=>$car_id));
                    $vehicle_no =  $cardata->vehicle_no;
                    $brand_name =  $cardata->brand_name;
                    $model_name =  $cardata->model_name;
                    $vehicle_type =  $cardata->vehicle_type;
                    $vehicle_image =  $cardata->vehicle_image;
                    $vehicles[] = array('vehicle_no'=> $vehicle_no, 'brand_name'=> $brand_name, 'model_name'=> $model_name, 'vehicle_type'=> $vehicle_type, 'vehicle_image'=> $vehicle_image);
                    $plandata =  Washingplans::model()->findByAttributes(array("title"=>$plan_ids[$index], "vehicle_type"=>$vehicle_type));
                    $price =  $plandata->price;
                    $fee = $plandata->handling_fee;
                    $total+= $price + $fee;
                }

                //echo $total;
                $total = number_format($total, 2, '.', '');


            }
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'total'=> $total,
            'vehicles'=>$vehicles

        );

        echo json_encode($json); die();
    }



     public function actionresetrejectedwashrequests(){

$pendingrequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE status = 0 AND is_scheduled = 0")->queryAll();

if(count($pendingrequests)){
foreach($pendingrequests as $wrequest){
//echo $wrequest['id']."<br>";

   /* ------- get nearest agents --------- */

$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/getnearestagents");
$data = array('wash_request_id' => $wrequest['id']);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$output = curl_exec($handle);
curl_close($handle);
$nearagentsdetails = json_decode($output);
//print_r($nearagentsdetails->nearest_agents);


            /* ------- get nearest agents end --------- */

            if($nearagentsdetails->result == 'true'){

                   $all_reject_ids = $wrequest['agent_reject_ids'];
                   $all_reject_ids_arr = explode(",", $all_reject_ids);
$all_reject_ids_arr_new = array();
foreach($all_reject_ids_arr as $val) $all_reject_ids_arr_new[] = abs($val);
                   $everyone_rejects = true;
//print_r($all_reject_ids_arr_new);
foreach($nearagentsdetails->nearest_agents as $agid=>$nearagentdis){
//echo $agid."<br>";
if (!in_array($agid, $all_reject_ids_arr_new)) {
                          $everyone_rejects = false;
                          break;
}

}


                   if($everyone_rejects){
echo "clearing rejects of wash #".$wrequest['id']."<br>";
                       Washingrequests::model()->updateByPk($wrequest['id'], array( 'agent_reject_ids' => '', 'order_temp_assigned' => 0 ));
                   }

            }

}
}


     }



    public function actionrejectwashrequest(){

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');
        $is_scheduled = Yii::app()->request->getParam('is_scheduled');
        $json = array();
        $result= 'false';
        $response= 'Pass the required parameters';

        if((isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))){
        $wash_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));

        if(!count($wash_id_check)){
          $result= 'false';
        $response= 'Invalid wash request id';
        }

        else if($status >= 0){
            $result = 'false';
            $response = 'Invalid status code. use negative value';
        }

        else{
            $result = 'true';
            $response = 'wash request rejected';
            $status_text = '';
            $saved_reject_ids = '';
            $status_text = $wash_id_check->agent_reject_ids;
            $saved_reject_ids = $wash_id_check->all_reject_ids;
            if($status_text == '') {
                $status_text = $status;
                }
            else $status_text .= ",".$status;

            if($saved_reject_ids == '') {
                $saved_reject_ids = abs($status);
                }
            else $saved_reject_ids .= ",".abs($status);
            //$status_text = rtrim($status_text, ',');
            //echo $status_text;

            if($is_scheduled){
                Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => $status_text, 'all_reject_ids' => $saved_reject_ids));
            }
            else{

            /* ------- get nearest agents --------- */

			$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/getnearestagents");
			$data = array('wash_request_id' => $wash_request_id);
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
			curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
			$output = curl_exec($handle);
			curl_close($handle);
			$nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */

            if($nearagentsdetails->result == 'true'){
                end($nearagentsdetails->nearest_agents);
                $last_agent_id = key($nearagentsdetails->nearest_agents);
                reset($nearagentsdetails->nearest_agents);

                if(abs($status) == $last_agent_id){
                   Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => '', 'order_temp_assigned' => 0, 'all_reject_ids' => $saved_reject_ids ));

                 /* ------- check if last agent rejects order two times -------- */

					  $total_rejects_array = explode(',',$saved_reject_ids);
					  $num_rejects_per_agents = array_count_values($total_rejects_array);

                      if($num_rejects_per_agents[$last_agent_id] >= 2){
                        Washingrequests::model()->updateByPk($wash_request_id, array('is_two_loops_reject' => 1)); //make wash available for schedule
                      }
                }
                else {
                   Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => $status_text, 'all_reject_ids' => $saved_reject_ids, 'order_temp_assigned' => 0 ));

                   $all_reject_ids = $wash_id_check->agent_reject_ids;
                   $all_reject_ids_arr = explode(",", $all_reject_ids);
                   $everyone_rejects = true;
/*
                   foreach($all_reject_ids_arr as $reject_id){
                       if (!array_key_exists($reject_id, $nearagentsdetails->nearest_agents)){
                          $everyone_rejects = false;
                          break;
                       }

                   }
*/

$all_reject_ids_arr_new = array();
foreach($all_reject_ids_arr as $val) $all_reject_ids_arr_new[] = abs($val);
                   $everyone_rejects = true;
//print_r($all_reject_ids_arr_new);
foreach($nearagentsdetails->nearest_agents as $agid=>$nearagentdis){
//echo $agid."<br>";
if (!in_array($agid, $all_reject_ids_arr_new)) {
                          $everyone_rejects = false;
                          break;
}

}

                   if($everyone_rejects){

                       Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => '', 'order_temp_assigned' => 0, 'all_reject_ids' => $saved_reject_ids ));
                   }

                     /* ------- check if all available agents rejects order two times -------- */

                      $two_loops_rejects = 1;
					  $total_rejects_array = explode(',',$saved_reject_ids);
					  $num_rejects_per_agents = array_count_values($total_rejects_array);

                      foreach($nearagentsdetails->nearest_agents as $agid=>$nearagentdis){

                        if (!in_array($agid, $total_rejects_array)) {
                          $two_loops_rejects = 0;
                          break;
                        }

                        if (in_array($agid, $total_rejects_array)) {
                        if($num_rejects_per_agents[$agid] < 2){
                           $two_loops_rejects = 0;
                          break;
                        }

                        }

                    }

                      if($two_loops_rejects){
                        Washingrequests::model()->updateByPk($wash_request_id, array('is_two_loops_reject' => 1)); //make wash available for schedule
                      }


                }
            }

            //else Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => $status_text, 'order_temp_assigned' => 0 ));
           }

        }

        }

         $json = array(
            'result'=> $result,
            'response'=> $response,
        );

        echo json_encode($json); die();

    }


    public function actionWashingTotalPrice ()
    {

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if(!empty($wash_request_id))
        {
       // $washid =  Yii::app()->db->createCommand("SELECT customer_id FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
	   $wash_request_details = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
       $customer_id = $wash_request_details[0]['customer_id'];
       $washing_status = $wash_request_details[0]['status'];
       if($washing_status == 5 || $washing_status == 6)
        {
            $data = array(
                'Error' => 'This Order already Cancelled'

            );
        echo json_encode($data);
        die();
        }
        else
        {
        $customers_request =  Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status IN ('4', '5', '6') AND `customer_id` = '$customer_id'")->queryAll();
        if(!empty($customers_request))
        {

            // OLD CUSTOMER
            //$old_customer =  Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
            $car_details = array();
            $package_name = array();
            foreach($wash_request_details as $request_details)
            {
                $car_list = explode(',', $request_details['car_list']);
                $package_title = explode(',', $request_details['package_list']);

                foreach($car_list as $cars)
                {
                    $car_details[] = $cars;
                }
                foreach($package_title as $packages)
                {
                    $package_name[] = $packages;
                }




            }

            $customer_vehicals = array();
            $cardetails = array();
            $k = -1;
             $counter = 0;
            foreach($car_details as $list)
            {
                $counter++;

                $k++;
            $listcar =  Yii::app()->db->createCommand("SELECT * FROM `customer_vehicals` WHERE `id` = '$list'")->queryAll();

            foreach($listcar as $carlist)
            {


                $title_plan = $package_name[$k];

                $vehicle_type = $carlist['vehicle_type'];
                $washing_plans =  Yii::app()->db->createCommand("SELECT title, price FROM `washing_plans` WHERE `vehicle_type` = '$vehicle_type' AND title = '$title_plan'")->queryAll();


                unset($carlist['customer_id']);
                //$cardetails[] = $carlist;
                        // echo $k.'dad'. $counter;
                 $cardetails[] =  array_merge($carlist,$washing_plans[0]);

                array_push($customer_vehicals, $carlist['vehicle_type']);
            }
            }

            $i = -1;
            $totalpice = 0;
            $j = 0;
            foreach($customer_vehicals as $vehicals)
            {
                 $j++;
                $i++;
                $title_plan = $package_name[$i];

            $price =  Yii::app()->db->createCommand("SELECT SUM(price) AS TotalPrice FROM `washing_plans` WHERE `vehicle_type` = '$vehicals' AND title = '$title_plan'")->queryAll();
          //echo "SELECT SUM(price) AS TotalPrice FROM `washing_plans` WHERE `vehicle_type` = '$vehicals' AND title = '$title_plan'";

            foreach($price as $pice)
            {

                $totalpice+= $pice['TotalPrice'];
            }

            }

            if($j == 2)
            {
              $newprice = $totalpice-3;
              $discount = 3;
            }
            elseif($j == 3)
            {
                $newprice = $totalpice-6;
                $discount = 6;
            }
            elseif($j == 4)
            {
                $newprice = $totalpice-9;
                $discount = 9;
            }
            else
            {
                $newprice = $totalpice;
                $discount = 0;
            }




            $countprice =  Yii::app()->db->createCommand("SELECT car_list FROM `washing_requests` WHERE status IN ('4') AND `customer_id` = '$customer_id'")->queryAll();
            $carcount = array();
            foreach($countprice as $z)
            {
                $count = explode(',', $z['car_list']);
                foreach($count as $c)
                {
                    $cnt = array_push($carcount,$c);
                }



            }
            $totalcarwash = count($carcount);
            if($totalcarwash == 2)
            {
                  $iteration_number = 1;
            }
            elseif($totalcarwash == 3)
            {
                  $iteration_number = 2;
            }
            elseif($totalcarwash == 4)
            {
                  $iteration_number = 3;
            }
            elseif($totalcarwash >= 5)
            {
                  $iteration_number = 0;
            }
            if($totalcarwash%5==0)
            {
                $finalprice = $newprice-5;
                    $data = array();
                $data = array(
                 'result'=> true,
                 'response'=> 'price',
                 'total_price'=> $finalprice,
                 'bundle_discount'=> $discount,
                 'iteration_discount'=> 5,
                 'iteration_number'=> $iteration_number,
                 'new_customer_discount'=> 0,
                 'vehicles'=>$cardetails
                );

            }
            else
            {
                $finalprice = $newprice;
                $finaldiscount = $discount;
                $data = array();
                $data = array(
                 'result'=> true,
                 'response'=> 'price',
                 'total_price'=> $finalprice,
                 'bundle_discount'=> $discount,
                 'iteration_discount'=> 0,
                 'iteration_number'=> $iteration_number,
                 'new_customer_discount'=> 0 ,
                 'vehicles'=>$cardetails
                );


            }


            echo json_encode($data);
            die();

        }
        else
        {

            // NEW CUSTOMER
            $new_customer =  Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
            $car_details = array();
            $package_name = array();
            foreach($wash_request_details as $request_details)
            {
                $car_list = explode(',', $request_details['car_list']);
                $package_title = explode(',', $request_details['package_list']);

                foreach($car_list as $cars)
                {
                    $car_details[] = $cars;
                }
                foreach($package_title as $packages)
                {
                    $package_name[] = $packages;
                }
            }
            $customer_vehicals = array();
            $cardetails = array();
            $k = -1;
            foreach($car_details as $list)
            {
                $k++;
            $listcar =  Yii::app()->db->createCommand("SELECT * FROM `customer_vehicals` WHERE `id` = '$list'")->queryAll();
            foreach($listcar as $carlist)
            {
            $title_plan = $package_name[$k];

                $vehicle_type = $carlist['vehicle_type'];
                $washing_plans =  Yii::app()->db->createCommand("SELECT title, price FROM `washing_plans` WHERE `vehicle_type` = '$vehicle_type' AND title = '$title_plan'")->queryAll();


                unset($carlist['customer_id']);
                //$cardetails[] = $carlist;

                 $cardetails[] =  array_merge($carlist,$washing_plans[0]);

                array_push($customer_vehicals, $carlist['vehicle_type']);
            }
            }
            $i = -1;
            $totalpice = 0;
            $j = 0;
            foreach($customer_vehicals as $vehicals)
            {
                $j++;
                $i++;
                $tit = $titl[$i];

            $price =  Yii::app()->db->createCommand("SELECT SUM(price) AS TotalPrice FROM `washing_plans` WHERE `vehicle_type` = '$vehicals' AND title = '$tit'")->queryAll();

            foreach($price as $pice)
            {

                $totalpice+= $pice['TotalPrice'];
            }

            }
            if($j == 2)
            {
              $newprice = $totalpice-8;
              $discount = 3;

            }
            elseif($j == 3)
            {
                $newprice = $totalpice-11;
                $discount = 6;
            }
            elseif($j == 4)
            {
                $newprice = $totalpice-14;
                $discount = 9;
            }
            else
            {
                $newprice = $totalpice-5;
                $discount = 5;
            }
            $new_customer_discount = 5;

            $data = array(
                'result'=> true,
                'response'=> 'price',
                'total_price' => $newprice,
                'bundle_discount' => $discount,
                'iteration_discount' => 0,
                'iteration_number' => 0,
                'new_customer_discount' => $new_customer_discount  ,
                'vehicles'=>$cardetails

            );

            echo json_encode($data);
            die();

        }
        }
        }
        else
        {
            $data = array(
                'Error' => 'Wash Request ID is missing'

            );
        echo json_encode($data);
        die();
        }

    }

    public function actiongetnewwashrequest(){

        $agent_id = Yii::app()->request->getParam('agent_id');
        $status = Yii::app()->request->getParam('washer_status');
        $json = array();
        $result= 'false';
        $response= 'Pass the required parameters';
        $is_scheduled_wash_120 = 0;
$pendingwashcount = 0;
		$agentdetails = Agents::model()->findByAttributes(array("id"=>$agent_id));
        if((isset($agent_id) && !empty($agent_id)) ){
			$agents_id_check = Agents::model()->findByAttributes(array("id"=>$agent_id));
			$agent_has_order = Washingrequests::model()->findByAttributes(array("order_temp_assigned"=>$agent_id, "status"=>0, "is_scheduled"=>0));

$pendingschedrequests =  Washingrequests::model()->findAll(array("condition"=>"wash_request_position != 'real' AND agent_id = 0 AND is_scheduled = 1 AND status = 0"));

foreach($pendingschedrequests as $pdrequest){
$declinedids = explode(",",$pdrequest->agent_reject_ids);

if($agent_id){
if (!in_array(-$agent_id, $declinedids)) {
$pendingwashcount++;
}
}
}


			if(count($agents_id_check)){
				$current_date = date("Y-m-d H:i:s");
				Agents::model()->updateByPk($agent_id, array('last_activity' => $current_date));

                 $agenttakenwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent_id, 'is_scheduled' => 1), array('condition'=>'status = 0 OR status = 1 OR status = 2'));

                     if(count($agenttakenwashes)){
                         foreach($agenttakenwashes as $schedwash){

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
       break;
    }
}
			}
            }
            }

			if(!count($agents_id_check)){
				$result= 'false';
				$response= 'Invalid agent id';
			}

			else if($agents_id_check->available_for_new_order == 0){
			  $result= 'false';
			$response= 'Agent is not available for new order';
			}

			else if(count($agent_has_order)){
				  $result= 'true';
			 $response= 'wash request found';

			  /* --- notification call --- */

								$message =  "You have a new wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agentdetails['mobile_type']);
								$notify_token = $agentdetails['device_token'];
								$alert_type = "strong";
								$notify_msg = urlencode($message);

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);

								/* --- notification call end --- */

								  $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" =>$agent_has_order->customer_id));
					$total_rate = count($cust_feedbacks);
					if($total_rate){
						$rate = 0;
						foreach($cust_feedbacks as $cust_feedback){
							$rate += $cust_feedback->customer_ratings;
						}

						$cust_rate =  round($rate/$total_rate);
					}
					else{
						$cust_rate = 0;
					}

					$cust_details = Customers::model()->findByAttributes(array("id"=>$agent_has_order->customer_id));

					$customername = '';
					$cust_name = explode(" ", trim($cust_details->customername));
					if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
					else $customername = $cust_name[0];

					$pendingwashrequests[] = array('id'=>$agent_has_order->id,
						'customer_id'=>$agent_has_order->customer_id,
						'customer_name'=>$customername,
						'customer_email'=>$cust_details->email,
						'customer_phoneno'=>$cust_details->contact_number,
						'customer_photo'=>$cust_details->image,
						'customer_rating' =>$cust_details->rating,
						'car_list'=>$agent_has_order->car_list,
						'package_list'=>$agent_has_order->package_list,
						'address'=>$agent_has_order->address,
						'address_type'=>$agent_has_order->address_type,
						'latitude'=>$agent_has_order->latitude,
						'longitude'=>$agent_has_order->longitude,
						'payment_type'=>$agent_has_order->payment_type,
						'nonce'=>$agent_has_order->nonce,
						'estimate_time'=>$agent_has_order->estimate_time,
						'status'=>$agent_has_order->status

					);
			}

			else{

			     $pendingrequests =  Washingrequests::model()->findAllByAttributes(array("status"=>0, "is_scheduled"=>0));



				if(count($pendingrequests)){
				   foreach($pendingrequests as $prequest){
					   $result= 'false';
						$response= 'No wash requests found for you';

					  /* ------- check if order is already taken by other agent or agent reject-------- */

					  $order_rejects = false;
					  $a_array = explode(',',$prequest['agent_reject_ids']);
					  foreach ($a_array as $aid){
						//echo $id;
						if($aid == '-'.$agent_id){
							$order_rejects = true;
							break;
						}
					  }

                      /* ------- check if agent rejects order two times -------- */

					  $total_rejects_array = explode(',',$prequest['all_reject_ids']);
					  $num_rejects_per_agents = array_count_values($total_rejects_array);

                      if($num_rejects_per_agents[$agent_id] >= 2){
                        $order_rejects = true;
                      }


					  if($prequest['order_temp_assigned'] == 0 AND (!$order_rejects)){

						  /* ------- check if agent is nearest -------- */

							$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/isagentnearest");
							$data = array("customer_id"=>$prequest['customer_id'], "wash_request_id"=>$prequest['id'], "agent_id"=>$agent_id);
							curl_setopt($handle, CURLOPT_POST, true);
							curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
							curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
							$nearapiresult = curl_exec($handle);
							curl_close($handle);
							$jsondata = json_decode($nearapiresult);
							//var_dump($jsondata);
							$nearest_response = $jsondata->response;

							$nearest_check = $jsondata->result;


							if($nearest_check == 'true' && $nearest_response == 'agent is nearest'){
							   $id_assign_check = Washingrequests::model()->updateByPk($prequest['id'], array( 'order_temp_assigned' => $agent_id ));
								  $result= 'true';
				  $response= 'wash request found';

				  /* --- notification call --- */

								$message =  "You have a new wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agentdetails['mobile_type']);
								$notify_token = $agentdetails['device_token'];
								$alert_type = "strong";
								$notify_msg = urlencode($message);

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);

								/* --- notification call end --- */

								  $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" =>$prequest['customer_id']));
					$total_rate = count($cust_feedbacks);
					if($total_rate){
						$rate = 0;
						foreach($cust_feedbacks as $cust_feedback){
							$rate += $cust_feedback->customer_ratings;
						}

						$cust_rate =  round($rate/$total_rate);
					}
					else{
						$cust_rate = 0;
					}

				  $cust_details = Customers::model()->findByAttributes(array("id"=>$prequest['customer_id']));

					$customername = '';
					$cust_name = explode(" ", trim($cust_details->customername));
					if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
					else $customername = $cust_name[0];

					$pendingwashrequests[] = array('id'=>$prequest['id'],
						'customer_id'=>$prequest['customer_id'],
						'customer_name'=>$customername,
						'customer_email'=>$cust_details->email,
						'customer_phoneno'=>$cust_details->contact_number,
						'customer_photo'=>$cust_details->image,
						'customer_rating' =>$cust_details->rating,
						'car_list'=>$prequest['car_list'],
						'package_list'=>$prequest['package_list'],
						'address'=>$prequest['address'],
						'address_type'=>$prequest['address_type'],
						'latitude'=>$prequest['latitude'],
						'longitude'=>$prequest['longitude'],
						'payment_type'=>$prequest['payment_type'],
						'nonce'=>$prequest['nonce'],
						'estimate_time'=>$prequest['estimate_time'],
						'status'=>$prequest['status']


					);


							   break;
							}

					  }

				   }

				}
				else{
					 $result= 'false';
					$response= 'No pending requests found';
				}
			}
        }

         $json = array(
            'result'=> $result,
            'response'=> $response,
            'new_request_details' => $pendingwashrequests,
			'washer_status'=>$agentdetails['status'],
'total_pending_schedule_washes'=>$pendingwashcount,
'is_scheduled_wash_120' => $is_scheduled_wash_120
        );

        echo json_encode($json); die();

    }


    public function actionunstuckorder(){
        $washmodel = new Washingrequests;
        $washmodel->updateAll(array('agent_id'=>0), 'status=:status', array(':status'=>0));
        echo "done";
    }

	/* view particular customer request */

    public function actionwashingkart(){
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $coupon_discount = 0;
        if(Yii::app()->request->getParam('coupon_discount')) $coupon_discount = Yii::app()->request->getParam('coupon_discount');
$coupon_code = '';
        $json = array();
        $result= 'false';
        $response= 'Pass the required parameters';
        $total = 0;
        $net_total = 0;
        $bundle_discount = 0;
        $fifth_wash_discount = 0;
        $first_wash_discount = 0;
        $vehicles = array();
        $veh_price = 0;
        $safe_handle_fee = 1;
        $agent_total = 0;
        $company_total = 0;
        $promo_wash_count = 0;
		$total_pet_lift_fee = 0;
$tip_amount = 0;

		if((isset($wash_request_id) && !empty($wash_request_id))){

            $wash_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));

			if(!count($wash_id_check)){
				$result= 'false';
				$response= 'Invalid wash request id';
			}
			else{

                $result= 'true';
				$response= 'kart details';

                 /* --------- Get total price ------------- */

				$total_cars = explode(",",$wash_id_check->car_list);
				$total_packs = explode(",",$wash_id_check->package_list);
				$pet_hair_arr = explode(",",$wash_id_check->pet_hair_vehicles);
				$lifted_vehicles_arr = explode(",",$wash_id_check->lifted_vehicles);
$exthandwax_vehicles_arr = explode(",",$wash_id_check->exthandwax_vehicles);
$extplasticdressing_vehicles_arr = explode(",",$wash_id_check->extplasticdressing_vehicles);
$extclaybar_vehicles_arr = explode(",",$wash_id_check->extclaybar_vehicles);
$waterspotremove_vehicles_arr = explode(",",$wash_id_check->waterspotremove_vehicles);
				$fifth_vehicles_arr = explode(",",$wash_id_check->fifth_wash_vehicles);

if($wash_id_check->coupon_discount) $coupon_discount = $wash_id_check->coupon_discount;
if($wash_id_check->coupon_code) $coupon_code = $wash_id_check->coupon_code;

				foreach($total_cars as $carindex=>$car){

					$vehicle_details = Vehicle::model()->findByAttributes(array("id"=>$car));

					$vehicle_inspect_details = Washinginspections::model()->findByAttributes(array("wash_request_id"=>$wash_request_id, "vehicle_id"=>$car));
					$inspect_img = '';
					if(count($vehicle_inspect_details) > 0){
						$inspect_img = $vehicle_inspect_details->damage_pic;
					}
					$washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Deluxe"));
                    if(count($washing_plan_deluxe)) $delx_price = $washing_plan_deluxe->price;
                    else $delx_price = "24.99";

                    $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Premium"));
                    if(count($washing_plan_prem)) $prem_price = $washing_plan_prem->price;
                    else $prem_price = "59.99";

					if($total_packs[$carindex] == 'Deluxe') {
                       $total += $delx_price;
                       $veh_price = $delx_price;
                       $agent_total += $veh_price * .80;
                       $company_total += $veh_price * .20;
                       $safe_handle_fee = $washing_plan_deluxe->handling_fee;
                       $company_total += $washing_plan_deluxe->handling_fee;
					}
					if($total_packs[$carindex] == 'Premium') {
                       $total += $prem_price;
                       $veh_price = $prem_price;
                       $agent_total += number_format($veh_price * .75, 2);
                       $company_total += number_format($veh_price * .25, 2);
                       $safe_handle_fee = $washing_plan_prem->handling_fee;
						$company_total += $washing_plan_prem->handling_fee;
					}

					//safe handling fee
					$total++;

					/* ----- pet hair / lift / fifth /addons check ------- */

					$pet_hair = 0;
					$lift_vehicle = 0;
$exthandwax_vehicle = 0;
$extplasticdressing_vehicle = 0;
$extclaybar_vehicle = 0;
$waterspotremove_vehicle = 0;
					$fifth_wash_disc = 0;

					if (in_array($car, $pet_hair_arr)){
						$total += 5;
						$total_pet_lift_fee += 5;


							$agent_total += 5 * .80;
							$company_total += 5 * .20;


						$pet_hair = 5;
					}

					if (in_array($car, $lifted_vehicles_arr)){
						$total += 5;
						$total_pet_lift_fee += 5;

						$agent_total += 5 * .80;
						$company_total += 5 * .20;

						$lift_vehicle = 5;
					}

if (in_array($car, $exthandwax_vehicles_arr)){
						$total += 12;
						//total_pet_lift_fee += 5;


						$agent_total += 12 * .80;
						$company_total += 12 * .20;

						$exthandwax_vehicle = 12;
					}

if (in_array($car, $extplasticdressing_vehicles_arr)){
						$total += 8;
						//total_pet_lift_fee += 5;


						$agent_total += 8 * .80;
						$company_total += 8 * .20;

						$extplasticdressing_vehicle = 8;
					}


if (in_array($car, $extclaybar_vehicles_arr)){
						$total += 35;
						//total_pet_lift_fee += 5;



						$agent_total += 35 * .80;
						$company_total += 35 * .20;


						$extclaybar_vehicle = 35;
					}


if (in_array($car, $waterspotremove_vehicles_arr)){
						$total += 30;
						//total_pet_lift_fee += 5;


						$agent_total += 30 * .80;
						$company_total += 30 * .20;


						$waterspotremove_vehicle = 30;
					}


					if (in_array($car, $fifth_vehicles_arr)){
						//$total += 5;
						//$total_pet_lift_fee += 5;
						//$agent_total += round(5 * .8, 2);
						$fifth_wash_disc = 5;
					}

					/* ----- pet hair / lift / fifth / addons check end ------- */

					$veh_price_agent = 0;
					if($total_packs[$carindex] == 'Premium') {
						$veh_price_agent = number_format($veh_price * .75, 2);
					}
					else{
						$veh_price_agent = number_format($veh_price * .8, 2);
					}

					$vehicles[] = array(
						'id'=>$vehicle_details->id,
						'vehicle_no'=>$vehicle_details->vehicle_no,
						'brand_name'=>$vehicle_details->brand_name,
						'model_name'=>$vehicle_details->model_name,
						'vehicle_image'=>$vehicle_details->vehicle_image,
						'vehicle_inspect_image'=>$inspect_img,
						'vehicle_type'=>$vehicle_details->vehicle_type,
						'vehicle_washing_package' => $total_packs[$carindex],
						'vehicle_washing_price'=> number_format($veh_price, 2),
						'vehicle_washing_price_agent'=> $veh_price_agent,
						'safe_handling_fee' => number_format($safe_handle_fee, 2),
						'pet_hair_fee' => number_format($pet_hair, 2),
'pet_hair_fee_agent' => number_format($pet_hair * .8, 2),
						'lifted_vehicle_fee' => number_format($lift_vehicle, 2),
'lifted_vehicle_fee_agent' => number_format($lift_vehicle * .8, 2),
'exthandwax_vehicle_fee' => number_format($exthandwax_vehicle, 2),
'exthandwax_vehicle_fee_agent' => number_format($exthandwax_vehicle * .8, 2),
'extplasticdressing_vehicle_fee' => number_format($extplasticdressing_vehicle, 2),
'extplasticdressing_vehicle_fee_agent' => number_format($extplasticdressing_vehicle * .8, 2),
'extclaybar_vehicle_fee' => number_format($extclaybar_vehicle, 2),
'extclaybar_vehicle_fee_agent' => number_format($extclaybar_vehicle * .8, 2),
'waterspotremove_vehicle_fee' => number_format($waterspotremove_vehicle, 2),
'waterspotremove_vehicle_fee_agent' => number_format($waterspotremove_vehicle * .8, 2),
						'fifth_wash_discount' => number_format($fifth_wash_disc, 2)
					);

				}

				/* --------- Get total price end ------------- */

/* ---- tip ---- */

if($wash_id_check->tip_amount) {
$tip_amount = $wash_id_check->tip_amount;
$total =  $total + $tip_amount;
 $company_total =  $company_total + ($tip_amount * .20);
 $agent_total =  $agent_total + ($tip_amount * .80);
}

/* ----- tip end ---- */

				/* ------------ bundle discount ------- */

				if(count($total_cars) >= 2) {
					$bundle_discount = count($total_cars) * 1;

				}

                /* ------------ bundle discount end ------- */


				/* ------------ fifth wash discount ------- */


				$cust_details = Customers::model()->findByAttributes(array("id"=>$wash_id_check->customer_id));

				$fifth_wash_discount = $wash_id_check->fifth_wash_discount;
				$promo_wash_count = $cust_details->fifth_wash_points;

				if($fifth_wash_discount && $bundle_discount) $bundle_discount -= 1;

				/* ------------ fifth wash discount end ------- */


				/* ------------ first wash discount ------- */

				$new_customer =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=".$wash_id_check->customer_id)->execute();

				if($new_customer == 1){

if($total_packs[0] == 'Premium') $first_wash_discount = 10;
else $first_wash_discount = 5;
				}

				/* ------------ first wash discount end ------- */

				/* ---- net price ------ */

				$net_total = $total - $bundle_discount -  $fifth_wash_discount - $first_wash_discount - $coupon_discount;

				/* ---- net price end ------ */

				/* ----------- calculate agent and company total after discounts ----------- */


				$agent_total -= $bundle_discount * .8;
				$company_total -= $bundle_discount * .2;

				if($wash_id_check->fifth_wash_discount){

					if(($wash_id_check->fifth_wash_discount) && (count($total_cars) >1)){
						$agent_total -= .8;
						$company_total -= 5 - .8;
					}
					else{
						$company_total -= 5;
					}
				}

				if($new_customer == 1){

if($total_packs[0] == 'Premium') $company_total -= 10;
else $company_total -= 5;

				}

				if($coupon_discount){
					//$agent_total -= count($total_cars) * .8;
					$company_total -= $coupon_discount;
				}

				$agent_total = round($agent_total, 2);
				$company_total = round($company_total, 2);

				//$company_total = round(($net_total - count($total_cars)) * .2, 2);
				//$company_total += count($total_cars);
				//echo $company_total;
				//echo "<br>".$total_pet_lift_fee;


				/* ----------- calculate agent and company total after discounts ----------- */

			}
		}

		$json = array(
            'result'=> $result,
            'response'=> $response,
            'order_date'=> $wash_id_check->created_date,
            'address'=> $wash_id_check->address,
            'address_type'=> $wash_id_check->address_type,
'latitude'=> $wash_id_check->latitude,
'longitude'=> $wash_id_check->longitude,
			'is_scheduled'=> $wash_id_check->is_scheduled,
			'schedule_date'=> $wash_id_check->schedule_date,
			'schedule_time'=> $wash_id_check->schedule_time,
            'total_price'=> number_format($total, 2),
            'net_price'=> number_format($net_total, 2),
            'company_total' => number_format($company_total, 2),
            'agent_total' => number_format($agent_total, 2),
'tip_amount' => number_format($tip_amount, 2),
            'bundle_discount' => number_format($bundle_discount, 2),
            'fifth_wash_discount' => number_format($fifth_wash_discount, 2),
            'first_wash_discount' => number_format($first_wash_discount, 2),
            'coupon_discount' => number_format($coupon_discount, 2),
'coupon_code' => $coupon_code,
            'promo_wash_count' => $promo_wash_count,

			'customer_wash_points' => $wash_id_check->customer_wash_points,
			'per_car_wash_points' => $wash_id_check->per_car_wash_points,
			'cancel_fee' => number_format($wash_id_check->cancel_fee, 2),
			'status' => $wash_id_check->status,
			'transaction_id' => $wash_id_check->transaction_id,
'washer_late_cancel' => $wash_id_check->washer_late_cancel,
            'vehicles' => $vehicles
        );

        echo json_encode($json); die();

    }

    public function actionwashingkartbeforewashcreate(){
        $customer_id = Yii::app()->request->getParam('customer_id');
        $car_ids = Yii::app()->request->getParam('car_ids');
        $pack_names = Yii::app()->request->getParam('pack_names');
        $coupon_discount = 0;
        if(Yii::app()->request->getParam('coupon_discount')) $coupon_discount = Yii::app()->request->getParam('coupon_discount');
        $json = array();
        $result= 'false';
        $response= 'Pass the required parameters';
        $total = 0;
        $net_total = 0;
        $bundle_discount = 0;
        $fifth_wash_discount = 0;
        $first_wash_discount = 0;
        $vehicles = array();
        $veh_price = 0;
        $safe_handle_fee = 1;
        $agent_total = 0;
        $company_total = 0;
        $promo_wash_count = 0;
        $total_discount = 0;

         if((isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($pack_names) && !empty($pack_names))){


                $result= 'true';
        $response= 'kart details';

                 /* --------- Get total price ------------- */

                 $total_cars = explode(",",$car_ids);
                 $total_packs = explode(",",$pack_names);

                  foreach($total_cars as $carindex=>$car){

                     $vehicle_details = Vehicle::model()->findByAttributes(array("id"=>$car));

                      $washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Deluxe"));
                    if(count($washing_plan_deluxe)) $delx_price = $washing_plan_deluxe->price;
                    else $delx_price = "24.99";

                    $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Premium"));
                    if(count($washing_plan_prem)) $prem_price = $washing_plan_prem->price;
                    else $prem_price = "59.99";

                   if($total_packs[$carindex] == 'Deluxe') {
                       $total += $delx_price;
                       $veh_price = $delx_price;
                       $safe_handle_fee = $washing_plan_deluxe->handling_fee;

                   }
                   if($total_packs[$carindex] == 'Premium') {
                       $total += $prem_price;
                       $veh_price = $prem_price;
                       $safe_handle_fee = $washing_plan_prem->handling_fee;
                   }

                   //safe handling fee
                   $total++;

                   $vehicles[] = array('id'=>$vehicle_details->id,
											'vehicle_no'=>$vehicle_details->vehicle_no,
											'brand_name'=>$vehicle_details->brand_name,
											'model_name'=>$vehicle_details->model_name,
											'vehicle_image'=>$vehicle_details->vehicle_image,
											'vehicle_type'=>$vehicle_details->vehicle_type,
                                            'vehicle_washing_package' => $total_packs[$carindex],
                                            'vehicle_washing_price'=> $veh_price,
                                            'safe_handling_fee' => $safe_handle_fee
                                            );

                  }

                 /* --------- Get total price end ------------- */




                 /* ------------ bundle discount ------- */

                 if(count($total_cars) >= 2) $bundle_discount = count($total_cars) * 1;

                /* ------------ bundle discount end ------- */


                 /* ------------ fifth wash discount ------- */

                    $cust_details = Customers::model()->findByAttributes(array("id"=>$customer_id));

                    $promo_wash_count = $cust_details->fifth_wash_points;

                    if($promo_wash_count == 5) {
                        $fifth_wash_discount = 5;

                    }

                 /* ------------ fifth wash discount end ------- */


                 /* ------------ first wash discount ------- */

                  $new_customer =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=".$customer_id)->execute();

                 if($new_customer == 0){

                     $first_wash_discount = 5;
                 }

                  /* ------------ first wash discount end ------- */

                  /* -------- total discount ---------- */

                  $total_discount = $bundle_discount + $fifth_wash_discount + $first_wash_discount + $coupon_discount;

                  /* -------- total discount end ---------- */

                  /* ---- net price ------ */

                    $net_total = $total - $bundle_discount -  $fifth_wash_discount - $first_wash_discount - $coupon_discount;

                 /* ---- net price end ------ */

                   /* ----------- calculate agent and company total ----------- */

                       $agent_total = round(($net_total - count($total_cars)) * .8, 2);
                       $company_total = round(($net_total - count($total_cars)) * .2, 2);
                       $company_total += count($total_cars);

                 /* ----------- calculate agent and company total end ----------- */


         }

           $json = array(
            'result'=> $result,
            'response'=> $response,
            'total_price'=> $total,
            'net_price'=> $net_total,
            'company_total' => $company_total,
            'agent_total' => $agent_total,
            'total_discount' => $total_discount,
            'bundle_discount' => $bundle_discount,
            'fifth_wash_discount' => $fifth_wash_discount,
            'first_wash_discount' => $first_wash_discount,
            'coupon_discount' => $coupon_discount,
            'promo_wash_count' => $promo_wash_count,
            'vehicles' => $vehicles
        );

        echo json_encode($json); die();

    }

  public function actionvieworder(){
		/* Checking for post(day) parameters */
		$order_day='';
$customer_id = Yii::app()->request->getParam('customer_id');
		if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
			$day = Yii::app()->request->getParam('day');
			$event = Yii::app()->request->getParam('event');

			$status_qr = '';
			if($event == 'pending'){
				$status = 0;
				$status_qr = ' AND a.status="'.$status.'"';
			}elseif($event == 'completed'){
				$status = 4;
				$status_qr = ' AND a.status="'.$status.'"';
			}elseif($event == 'processing'){
				$status = 2;
				$status_qr = ' AND a.status="'.$status.'"';
			}else{
				$status_qr = '';
			}

			$order_day = " AND DATE_FORMAT(a.created_date,'%Y-%m-%d')= '$day'$status_qr";
		}
		/* END */
        $total_order =  Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];


if($customer_id){
$customers_order =  Yii::app()->db->createCommand("SELECT a.id, a.status, a.total_price, a.net_price, a.address_type, a.bundle_discount, a.fifth_wash_discount, a.first_wash_discount, a.address, a.coupon_discount, a.customer_id, a.agent_id, a.created_date, a.car_list, a.package_list, a.estimate_time, a.wash_request_position, b.customername, c.first_name, c.last_name, c.street_address, c.city, c.state FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.customer_id = ".$customer_id." AND a.status NOT IN (5,6)$order_day")->queryAll();
}
else{
$customers_order =  Yii::app()->db->createCommand("SELECT a.id, a.status, a.total_price, a.net_price, a.address_type, a.bundle_discount, a.fifth_wash_discount, a.first_wash_discount, a.address, a.coupon_discount, a.customer_id, a.agent_id, a.created_date, a.car_list, a.package_list, a.estimate_time, a.wash_request_position, b.customername, c.first_name, c.last_name, c.street_address, c.city, c.state FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.status NOT IN (5,6)$order_day")->queryAll();
}



        $package_name = array();
        $car_id = array();
        foreach($customers_order as $orderbycustomer){

            $subtotal = $orderbycustomer['net_price'];
            $discount = $orderbycustomer['bundle_discount'] + $orderbycustomer['fifth_wash_discount'] + $orderbycustomer['first_wash_discount'] + $orderbycustomer['coupon_discount'];


			//$counttype = array_count_values($package_list_explode);
			$orderstatus = $orderbycustomer['status'];
			$orderid = $orderbycustomer['id'];
			$client = $orderbycustomer['customername'];
			$created_date = $orderbycustomer['created_date'];
			$name_of_agent = $orderbycustomer['first_name'].'&nbsp;'.$orderbycustomer['last_name'];
			$address = $orderbycustomer['address'];
			$address_type = $orderbycustomer['address_type'];
			$total_price = $orderbycustomer['total_price'];
			$net_price = $orderbycustomer['net_price'];
			$city = $orderbycustomer['city'];
			$state = $orderbycustomer['state'];
			$agent_id = $orderbycustomer['agent_id'];
			$customer_id = $orderbycustomer['customer_id'];
			$wash_request_position = $orderbycustomer['wash_request_position'];



			$duration = $orderbycustomer['estimate_time'];
			if($orderstatus == 4)
			{
				$order_of_status = 'Complete';
				$totalminutes = 'N/A';
				$near_agent = '';

			}
			elseif($orderstatus == 0)
			{
				$order_of_status = 'Pending';
				$time = $orderbycustomer['created_date'];
                $currentdate = date("Y-m-d h:i:s");
                $to_time = strtotime($time);
                $from_time = strtotime($currentdate);
                $totalminutes = round(abs($to_time - $from_time) / 60,2);


			}
			elseif($orderstatus>=1 && $orderstatus<=3)
			{
				$order_of_status = 'Processing';
				$totalminutes = 'N/A';
				$near_agent = '';
			}
			$key = 'order_'.$count.'_'.$orderid;
			$json = array();
			$json['status'] =  $order_of_status;
			$json['countnear'] =  $countnear;
			$json['orderid'] =  $orderid;
			$json['time'] =  $totalminutes;
			$json['created_date'] =  $created_date;
			$json['client'] =  $client;
			$json['agent'] =  $name_of_agent;
			$json['address'] =  $address;
			$json['address_type'] =  $address_type;
			$json['city'] =  $city;
			$json['state'] =  $state;
			$json['DP'] =  $dp;
			$json['total_price'] = $total_price;
			$json['net_price'] = $net_price;
			$json['discount'] = $discount;
			$json['duration'] = $duration;
			$json['wash_request_position'] = $wash_request_position;
			$orderview[] = $json;
		}
        $ordersdetails['order'] = $orderview;
        $ordersdetails['total_records'] = $count;

		echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

		exit;
    }

public function actionEditOrderDetailAdmin(){

        $orderid = Yii::app()->request->getParam('orderid');
        $status = Yii::app()->request->getParam('status');
        $clientname = Yii::app()->request->getParam('clientname');
        $agentname = Yii::app()->request->getParam('agentname');
        if(!empty($status))
        {
            //$update_password = Customers::model()->updateAll(array('status'=>$status),'id=:id',array(':id'=>$orderid));
            $update_status = Yii::app()->db->createCommand("UPDATE washing_requests SET status='$status' WHERE id = '$orderid' ")->queryAll();
            $value = $status;
        }
        elseif(!empty($clientname))
        {

            $customer =  Yii::app()->db->createCommand("SELECT customer_id FROM washing_requests WHERE id = '$orderid' ")->queryAll();
            $customerid = $customer[0]['customer_id'];
            $update_customer = Yii::app()->db->createCommand("UPDATE customers SET customername='$clientname' WHERE id = '$customerid' ")->queryAll();
            $value = $firstname;
        }
       /* elseif(!empty($agentname))
        {
            $Agents = new Agents;
            $agentname = explode(" ",$agentname);

            $fname = $agentname[0];
            $lname = $agentname[1];
            $customer =  Yii::app()->db->createCommand("SELECT agent_id FROM washing_requests WHERE id = '$orderid' ")->queryAll();

            $agentid = $customer[0]['agent_id'];
            $update_agent = Agents::model()->updateByPk($agentid, array('first_name'=>$fname, 'last_name'=>$lname));
            //$update_agent = Yii::app()->db->createCommand("UPDATE agents SET first_name='$fname', last_name = '$lname' WHERE id = '$agentid' ")->queryAll();
            //echo "UPDATE agents SET first_name='$fname', last_name = '$lname' WHERE id = '$agentid'";
            $value = $agentname;
        }*/




                $result = 'true';
                $response = $value.' updated successfully';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );

         echo json_encode($json);die();
    }


public function actionadminpromotionscode()
    {

        $sort = Yii::app()->request->getParam('orderby');
        $sortorder = explode("_",$sort);
        $table = $sortorder[0];
        if($table == 'promocode')
        {
            $set = 'promo_code';
        }


        $customer_discounts =  Yii::app()->db->createCommand("SELECT * FROM customer_discounts GROUP BY promo_code ORDER BY ". ($set) ." ". ($des) ." ")->queryAll();
        $promotiondetails = array();
        foreach($customer_discounts as $discount)
        {
            $promo_code = $discount['promo_code'];
            $count =  Yii::app()->db->createCommand("SELECT COUNT(id) as count FROM customer_discounts WHERE promo_code = '$promo_code' GROUP BY promo_code")->queryAll();
            $uses = $count[0]['count'];
            $key = 'promotion_'.$promo_code;
             $json = array();
             $json['promo_code'] =  $promo_code;
             $json['count'] =  $uses;
             $json['status'] =  'N/A';
             $json['expires'] =  'N/A';
             $promotiondetails[] = $json;
        }
        $promotioncode['promotions'] = $promotiondetails;
        echo json_encode($promotioncode, JSON_PRETTY_PRINT);

         exit;
    }

    public function actionsendorderreceipts()
    {
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $agent_id = Yii::app()->request->getParam('agent_id');
        $result= 'false';
        $response= 'Pass the required parameters';

         if((isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id)) && (isset($agent_id) && !empty($agent_id))){

                $wash_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));
                $customer_id_check = Customers::model()->findByAttributes(array("id"=>$customer_id));
                $agent_id_check = Agents::model()->findByAttributes(array("id"=>$agent_id));

                if(!count($wash_id_check)){
                    $result= 'false';
                    $response= 'Invalid wash id';
                }
                else if(!count($customer_id_check)){
                    $result= 'false';
                    $response= 'Invalid customer id';
                }
                else if(!count($agent_id_check)){
                    $result= 'false';
                    $response= 'Invalid agent id';
                }
                else{
                   $result= 'true';
                    $response= 'order receipts sent';

                    /* ------- kart details ----------- */

$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/washingkart");
$data = array('wash_request_id' => $wash_request_id);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$kartresult = curl_exec($handle);
curl_close($handle);
$kartdata = json_decode($kartresult);
//var_dump($jsondata);


/* ------- kart details end ----------- */

                    $from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$subject = 'Order Receipt - #000'.$wash_id_check->id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                     $message = "<div class='block-content' style='background: #fff; text-align: left;'>";
 $message .= "<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is placed at ".$wash_id_check->address."</p>";

$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>".$customer_id_check->customername."</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000".$wash_id_check->id."</td></tr>
					</table>";

                  if($kartdata->status == 5){
if($kartdata->cancel_fee > 0){
$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$".number_format($kartdata->cancel_fee, 2)."</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$".number_format($kartdata->cancel_fee, 2)."</span></p></td>
</tr>
</table>";

}
}
else{
$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
 foreach($kartdata->vehicles as $ind=>$vehicle){
$message .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$vehicle->brand_name." ".$vehicle->model_name."</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".$vehicle->vehicle_washing_price."</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$vehicle->vehicle_washing_package." Package</p></td>
<td style='text-align: right;'></td>
</tr>";
if($vehicle->extclaybar_vehicle_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extclaybar_vehicle_fee."</p></td>
</tr>";
}
if($vehicle->waterspotremove_vehicle_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->waterspotremove_vehicle_fee."</p></td>
</tr>";
}
if($vehicle->exthandwax_vehicle_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->exthandwax_vehicle_fee."</p></td>
</tr>";
}
if($vehicle->extplasticdressing_vehicle_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extplasticdressing_vehicle_fee."</p></td>
</tr>";
}
$message .="<tr>
<td><p style='font-size: 18px; margin: 0;'>Safe Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->safe_handling_fee."</p></td>
</tr>";
if($vehicle->pet_hair_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Light Pet Hair Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->pet_hair_fee."</p></td>
</tr>";
}
if($vehicle->lifted_vehicle_fee > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->lifted_vehicle_fee."</p></td>
</tr>";
}

if(($vehicle->fifth_wash_discount == 0) && (count($kartdata->vehicles) > 1)){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
}

if(($kartdata->first_wash_discount > 0) && ($ind == 0)){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".$kartdata->first_wash_discount."</p></td>
</tr>";
}

if($vehicle->fifth_wash_discount > 0){
$message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".$vehicle->fifth_wash_discount."</p></td>
</tr>";
}

$message .= "</table>

</td>
</tr>";

}
$message .= "</table>";

if($kartdata->coupon_discount){
$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

if($kartdata->coupon_discount > 0){
$message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Coupon Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$".number_format($kartdata->coupon_discount, 2)."</p>
</td>
</tr>";
}
$message .= "</table>";
}


if($kartdata->tip_amount > 0){
$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

$message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$".number_format($kartdata->tip_amount, 2)."</p>
</td>
</tr>";

$message .= "</table>";
}

$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$kartdata->net_price."</span></p></td>
</tr>
</table>";
}

   /* ------ agent msg ------ */

 $message_agent = "<div class='block-content' style='background: #fff; text-align: left;'>

                  <p style='text-align: center; font-family: arial; font-size: 20px; line-height: normal; margin: 0;'><strong>Order Number:</strong> #000".$wash_id_check->id."</p>";

                  if($kartdata->status == 5){
if($kartdata->cancel_fee > 0){
$message_agent .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$5</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$5</span></p></td>
</tr>
</table>";

}
}
else{
$message_agent .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
 foreach($kartdata->vehicles as $ind=>$vehicle){
$message_agent .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$vehicle->brand_name." ".$vehicle->model_name."</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".$vehicle->vehicle_washing_price_agent."</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$vehicle->vehicle_washing_package." Package</p></td>
<td style='text-align: right;'></td>
</tr>";

if($vehicle->extclaybar_vehicle_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extclaybar_vehicle_fee_agent."</p></td>
</tr>";
}
if($vehicle->waterspotremove_vehicle_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->waterspotremove_vehicle_fee_agent."</p></td>
</tr>";
}

if($vehicle->exthandwax_vehicle_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->exthandwax_vehicle_fee_agent."</p></td>
</tr>";
}
if($vehicle->extplasticdressing_vehicle_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extplasticdressing_vehicle_fee_agent."</p></td>
</tr>";
}

if($vehicle->pet_hair_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Light Pet Hair Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->pet_hair_fee_agent."</p></td>
</tr>";
}
if($vehicle->lifted_vehicle_fee_agent > 0){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->lifted_vehicle_fee_agent."</p></td>
</tr>";
}


if(count($kartdata->vehicles) > 1){
$message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format(round(1*.80, 2), 2)."</p></td>
</tr>";
}

$message_agent .= "</table>

</td>
</tr>";

}
$message_agent .= "</table>";

if($kartdata->tip_amount > 0){
$message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

$message_agent .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$".number_format(round($kartdata->tip_amount*.80, 2), 2)."</p>
</td>
</tr>";

$message_agent .= "</table>";
}


$message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".number_format($kartdata->agent_total, 2)."</span></p></td>
</tr>
</table>";
}


/* ---- company msg -------- */

$com_message = "<div class='block-content' style='background: #fff; text-align: left;'>

                  <p style='text-align: center; font-family: arial; font-size: 20px; line-height: normal; margin: 0;'><strong>Order Number:</strong> #000".$wash_id_check->id."</p>";

                  if($kartdata->status == 5){
if($kartdata->cancel_fee > 0){
$com_message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$5.00</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$5.00</span></p></td>
</tr>
</table>";

}
}
else{
$com_message .= "<p style='margin: 0; margin-top: 15px; font-size: 18px; border-top: 1px solid #000; padding: 10px 0;'><strong>MobileWash Receipt</strong></p>";

$com_message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 0;'>";
 foreach($kartdata->vehicles as $ind=>$vehicle){
$com_message .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$vehicle->brand_name." ".$vehicle->model_name."</p></td>
<td style='text-align: right;'>";
if($vehicle->vehicle_washing_package == 'Premium') $com_message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".number_format($vehicle->vehicle_washing_price*.25, 2)."</p>";
else $com_message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".number_format($vehicle->vehicle_washing_price*.20, 2)."</p>";

$com_message .= "</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$vehicle->vehicle_washing_package." Package</p></td>
<td style='text-align: right;'></td>
</tr>";
if($vehicle->extclaybar_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->extclaybar_vehicle_fee*.20, 2)."</p></td>
</tr>";
}
if($vehicle->waterspotremove_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->waterspotremove_vehicle_fee*.20, 2)."</p></td>
</tr>";
}
if($vehicle->exthandwax_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->exthandwax_vehicle_fee*.20, 2)."</p></td>
</tr>";
}
if($vehicle->extplasticdressing_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->extplasticdressing_vehicle_fee*.20, 2)."</p></td>
</tr>";
}
$com_message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Safe Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$1.00</p></td>
</tr>";
if($vehicle->pet_hair_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->pet_hair_fee*.20, 2)."</p></td>
</tr>";
}
if($vehicle->lifted_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".number_format($vehicle->lifted_vehicle_fee*.20, 2)."</p></td>
</tr>";
}


if(($vehicle->fifth_wash_discount == 0) && (count($kartdata->vehicles) > 1)){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$0.20</p></td>
</tr>";
}

if(($kartdata->first_wash_discount > 0) && ($ind == 0)){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format($kartdata->first_wash_discount, 2)."</p></td>
</tr>";
}

if($vehicle->fifth_wash_discount > 0){
if((count($kartdata->vehicles) > 1)){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format($vehicle->fifth_wash_discount-.80, 2)."</p></td>
</tr>";
}
else{
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format($vehicle->fifth_wash_discount, 2)."</p></td>
</tr>";
}

}

$com_message .= "</table>

</td>
</tr>";

}
$com_message .= "</table>";
if($kartdata->coupon_discount){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

if($kartdata->coupon_discount > 0){
$com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Coupon Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$".number_format($kartdata->coupon_discount, 2)."</p>
</td>
</tr>";
}
$com_message .= "</table>";
}


if($kartdata->tip_amount > 0){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

$com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$".number_format(round($kartdata->tip_amount*.20, 2), 2)."</p>
</td>
</tr>";

$com_message .= "</table>";
}

$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$kartdata->company_total."</span></p></td>
</tr>
</table>";
}

 $cust_details = Customers::model()->findByAttributes(array("id"=>$customer_id_check->id));

$customername = '';
$cust_name = explode(" ", trim($cust_details->customername));
if(count($cust_name > 1)) $customername = $cust_name[0]." ".strtoupper(substr($cust_name[1], 0, 1)).".";
else $customername = $cust_name[0];

$com_message .= "<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Client Receipt:</strong> ".$customername."</p>";

 if($kartdata->status == 5){
if($kartdata->cancel_fee . 0){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$".number_format($kartdata->cancel_fee, 2)."</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$".number_format($kartdata->cancel_fee, 2)."</span></p></td>
</tr>
</table>";

}
}
else{
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
 foreach($kartdata->vehicles as $ind=>$vehicle){
$com_message .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$vehicle->brand_name." ".$vehicle->model_name."</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".$vehicle->vehicle_washing_price."</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$vehicle->vehicle_washing_package." Package</p></td>
<td style='text-align: right;'></td>
</tr>";

if($vehicle->extclaybar_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extclaybar_vehicle_fee."</p></td>
</tr>";
}
if($vehicle->waterspotremove_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->waterspotremove_vehicle_fee."</p></td>
</tr>";
}

if($vehicle->exthandwax_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->exthandwax_vehicle_fee."</p></td>
</tr>";
}
if($vehicle->extplasticdressing_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extplasticdressing_vehicle_fee."</p></td>
</tr>";
}

$com_message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Safe Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->safe_handling_fee."</p></td>
</tr>";
if($vehicle->pet_hair_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Light Pet Hair Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->pet_hair_fee."</p></td>
</tr>";
}
if($vehicle->lifted_vehicle_fee > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->lifted_vehicle_fee."</p></td>
</tr>";
}



if(($vehicle->fifth_wash_discount == 0) && (count($kartdata->vehicles) > 1)){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
}

if(($kartdata->first_wash_discount > 0) && ($ind == 0)){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format($kartdata->first_wash_discount, 2)."</p></td>
</tr>";
}

if($vehicle->fifth_wash_discount > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".$vehicle->fifth_wash_discount."</p></td>
</tr>";
}

$com_message .= "</table>

</td>
</tr>";

}
$com_message .= "</table>";
if($kartdata->coupon_discount){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

if($kartdata->coupon_discount > 0){
$com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Coupon Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$".number_format($kartdata->coupon_discount, 2)."</p>
</td>
</tr>";
}
$com_message .= "</table>";
}



if($kartdata->tip_amount > 0){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

$com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$".number_format($kartdata->tip_amount, 2)."</p>
</td>
</tr>";

$com_message .= "</table>";
}

$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".$kartdata->net_price."</span></p></td>
</tr>
</table>";
}

$wash_feedbacks = Washingfeedbacks::model()->findByAttributes(array("wash_request_id" => $wash_request_id));

/*
$com_message .= "<p style='font-size: 20px; margin-bottom: 0;'><strong>Client Rated Washer:</strong> ".$wash_feedbacks->customer_ratings." Stars</p>";
$com_message .= "<p style='font-size: 20px; margin-bottom: 0; margin-top: 0;'><strong>Client Feedback:</strong></p>";
$com_message .= "<p style='font-size: 20px; margin-top: 0;'>".$wash_feedbacks->customer_comments."</p>";
*/

 $agent_details = Agents::model()->findByAttributes(array("id"=>$agent_id_check->id));

$agentlname = '';
if(trim($agent_details->last_name)) $agentlname = strtoupper(substr($agent_details->last_name, 0, 1)).".";
else $agentlname = $agent_details->last_name;

$com_message .= "<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Agent Receipt:</strong> ".$agent_details->first_name." ".$agentlname."</p>";

 if($kartdata->status == 5){
if($kartdata->cancel_fee > 0){
$com_message .= "<table style='width: 100%; border-collapse: margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$5</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$5</span></p></td>
</tr>
</table>";

}
}
else{
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
 foreach($kartdata->vehicles as $ind=>$vehicle){
$com_message .="<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$vehicle->brand_name." ".$vehicle->model_name."</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$".$vehicle->vehicle_washing_price_agent."</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>".$vehicle->vehicle_washing_package." Package</p></td>
<td style='text-align: right;'></td>
</tr>";

if($vehicle->extclaybar_vehicle_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extclaybar_vehicle_fee_agent."</p></td>
</tr>";
}
if($vehicle->waterspotremove_vehicle_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->waterspotremove_vehicle_fee_agent."</p></td>
</tr>";
}
if($vehicle->exthandwax_vehicle_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->exthandwax_vehicle_fee_agent."</p></td>
</tr>";
}
if($vehicle->extplasticdressing_vehicle_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->extplasticdressing_vehicle_fee_agent."</p></td>
</tr>";
}

if($vehicle->pet_hair_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Light Pet Hair Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->pet_hair_fee_agent."</p></td>
</tr>";
}
if($vehicle->lifted_vehicle_fee_agent > 0){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$".$vehicle->lifted_vehicle_fee_agent."</p></td>
</tr>";
}


if(count($kartdata->vehicles) > 1){
$com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$".number_format(round(1*.80, 2), 2)."</p></td>
</tr>";
}


$com_message .= "</table>

</td>
</tr>";

}
$com_message .= "</table>";

if($kartdata->tip_amount > 0){
$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

$com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$".number_format(round($kartdata->tip_amount*.80, 2), 2)."</p>
</td>
</tr>";

$com_message .= "</table>";
}


$com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".number_format($kartdata->agent_total, 2)."</span></p></td>
</tr>
</table>";
}

/*
$com_message .= "<p style='font-size: 20px; margin-bottom: 0;'><strong>Washer Rated Client:</strong> ".$wash_feedbacks->agent_ratings." Stars</p>";
$com_message .= "<p style='font-size: 20px; margin-bottom: 0; margin-top: 0;'><strong>Washer Feedback:</strong></p>";
$com_message .= "<p style='font-size: 20px; margin-top: 0;'>".$wash_feedbacks->agent_comments."</p>";
*/

                    Vargas::Obj()->SendMail($customer_id_check->email,$from,$message,$subject, 'mail-receipt');
                    Vargas::Obj()->SendMail($agent_id_check->email,$from,$message_agent,$subject, 'mail-receipt');
                    Vargas::Obj()->SendMail("billing@mobilewash.com",$from,$com_message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("admin@mobilewash.com",$from,$com_message,$subject, 'mail-receipt');

                }
         }

          $json = array(
            'result'=> $result,
            'response'=> $response
        );

        echo json_encode($json); die();

    }

  public function ActionWashingFeedbacks(){

/*        get the total */
        $total_feed =  Yii::app()->db->createCommand("SELECT COUNT(w.id) as countid FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id")->queryAll();
        $feeds = $total_feed[0]['countid'];
        $feedback =  Yii::app()->db->createCommand("SELECT w.id, w.customer_comments, w.customer_ratings, w.agent_comments, w.agent_ratings, c.customername, a.first_name, a.last_name FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id ")->queryAll();
        /*echo "SELECT w.id, w.customer_comments, w.customer_ratings, w.agent_comments, w.agent_ratings, c.customername, a.first_name, a.last_name FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id ORDER BY ". ($set) ." ". ($des) ." LIMIT ". ($startpage)." ,  ". ($endpage). " ";*/
        $i=0;
        foreach($feedback as $feedbacks){
            $i++;
            $id = $feedbacks['id'];
            $customer_comments = $feedbacks['customer_comments'];
            $customer_ratings = $feedbacks['customer_ratings'];
            $agent_comments = $feedbacks['agent_comments'];
            $agent_ratings = $feedbacks['agent_ratings'];
            $customername = $feedbacks['customername'];
            $agentname = $feedbacks['first_name'].'&nbsp;'.$feedbacks['last_name'];
            $agentfname = $feedbacks['first_name'];
            $agentlname = $feedbacks['last_name'];


            $key = 'feed_'.$id;
             $json = array();
             $json['washingid'] =  $id;
             $json['customer_comments'] =  $customer_comments;
             $json['customer_ratings'] =  $customer_ratings;
             $json['agent_comments'] =  $agent_comments;
             $json['agent_ratings'] =  $agent_ratings;
             $json['customername'] =  $customername;
             $json['agentname'] =  $agentname;
             $json['agentfname'] =  $agentfname;
             $json['agentlname'] =  $agentlname;
             $feedview[] = $json;
        }

        $feedbackadmin['order'] = $feedview;
        $feedbackadmin['total_records'] = $feeds;

        echo json_encode($feedbackadmin,JSON_PRETTY_PRINT);
        exit;
    }



public function ActionEditOrder(){

        $orderID = Yii::app()->request->getParam('orderID');
        $orderdetail = Washingrequests::model()->findByAttributes(array("id"=>$orderID));
        $near_agents = array();
        $orderid = $orderdetail['id'];
        $customer_id = $orderdetail['customer_id'];
        $address = $orderdetail['address'];
        $status = $orderdetail['status'];
        $customer =  Yii::app()->db->createCommand("SELECT * FROM customers WHERE id = ".$orderdetail['customer_id'])->queryAll();
        $customername = $customer[0]['customername'];
 $agent_detail =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE id = ".$orderdetail['agent_id'])->queryAll();
        $agentname = $agent_detail[0]['first_name']." ".$agent_detail[0]['last_name'];
$inspectiondetails_arr = array();

$inspectiondetails = Washinginspections::model()->findAllByAttributes(array("wash_request_id"=>$orderID));

if(count($inspectiondetails)){
foreach($inspectiondetails as $ind=> $inspectdet){

$vehicle_detail = Vehicle::model()->findByAttributes(array("id"=>$inspectdet->vehicle_id));
$inspectiondetails_arr[$ind]['vehicle_id'] = $inspectdet->vehicle_id;
$inspectiondetails_arr[$ind]['vehicle_make'] = $vehicle_detail->brand_name;
$inspectiondetails_arr[$ind]['vehicle_model'] = $vehicle_detail->model_name;
$inspectiondetails_arr[$ind]['inspect_img'] = $inspectdet->damage_pic;
}
}

         if($status == 0)
         {
             $order_of_status = 'Pending';



                // to find nearest agent

                $customer_id = $customer_id;
                $wash_request_id = $orderid;


              /* ------- get nearest agents --------- */

$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/getnearestagents");
$data = array('wash_request_id' => $wash_request_id);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$output = curl_exec($handle);
curl_close($handle);
$nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */

            if($nearagentsdetails->result == 'true'){
                foreach($nearagentsdetails->nearest_agents as $aid=>$dist){
                     $agent_details = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='".$aid."' ")->queryAll();
                     $near_agents[$aid] = $agent_details[0]['first_name']." ".$agent_details[0]['last_name']." (".round($dist,4)." miles)";
                }

            }



         }
         elseif($status>=1 && $status<=3)
         {
             $order_of_status = 'Processing';
             $near_agent = '';
         }

  elseif($status== 5 || $status== 6)
         {
             $order_of_status = 'Cancel';
             $near_agent = '';
         }
else{
$order_of_status = 'Complete';
}

         $json = array(
                'id'=> $orderid,
                'customername'=> $customername,
'agent_name' => $agentname,
                'address'=> $address,
                'order_of_status'=> $order_of_status,
'inspection_details' => $inspectiondetails_arr,
                'nearagent' =>  $near_agents
            );
             echo json_encode($json);
             exit;


    }


	 public function ActionPendigTimer()
    {
        $orderid = Yii::app()->request->getParam('orderid');
        $order =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE id in ".'('. ($orderid) .") ")->queryAll();

        $pending = array();
        $processing = array();
        foreach($order as $k=>$y)
        {
                //$id = $y['id'];
            if($y['status'] == 0)
            {

                $id = $y['id'];
                $time = $y['created_date'];
                $currentdate = date("Y-m-d h:i:s");
                $to_time = strtotime($time);
                $from_time = strtotime($currentdate);
                $totalminutes = round(abs($to_time - $from_time) / 60,2);
                $time = (int)$totalminutes;
                       $h = floor (($time - $d * 1440) / 60);
                       $m = $time - ($d * 1440) - ($h * 60);

                       $newtime = $h.'h' .$m.'m';

                    $res[$id]['status'] = 'pending';
                    $res[$id]['orderid'] = $id;
                    $res[$id]['time'] = $newtime;
                    $res[$id]['minutetime'] = $time;
                    $res[$id]['name'] = 'N/A';
                    $res[$id]['address'] = 'N/A';
                    $res[$id]['city'] = 'N/A';
                    $res[$id]['state'] = 'N/A';

            }
            elseif($y['status']>=1 && $y['status']<=3)
            {
                $idd = $y['id'];

                $agentid = $y['agent_id'];
                //echo "SELECT * FROM agents WHERE id = '$agentid'<br />";
                //echo "SELECT * FROM agents WHERE id = '$id' ";
               $agent =  Yii::app()->db->createCommand("SELECT * FROM agents WHERE id = '$agentid' ")->queryAll();
               $var['name'] = $agent[0]['first_name'].'&nbsp;'.$agent[0]['last_name'];
               $var['address'] = $agent[0]['street_address'];
               $var['city'] = $agent[0]['city'];
               $var['state'] = $agent[0]['state'];
               $processing[$idd] = $var;
               $res[$idd]['status'] = 'processing';
               $res[$idd]['orderid'] = $idd;
                $res[$idd]['time'] = 'N/A';
                $res[$idd]['name'] = $agent[0]['first_name'].'&nbsp;'.$agent[0]['last_name'];
                $res[$idd]['address'] = $agent[0]['street_address'];
                $res[$idd]['city'] = $agent[0]['city'];
                $res[$idd]['state'] = $agent[0]['state'];
               //$processing[$idd] = $processing;
            }
        }


        /*echo "<pre>";
        print_r($res);
        echo "<pre>";
        exit;*/

            //$json = array($array);
            $json = array($res);
        echo json_encode($json); die();

    }

	public function ActionUpdateOrder(){

          $id = Yii::app()->request->getParam('id');
          $address = Yii::app()->request->getParam('address');
          $status = Yii::app()->request->getParam('status');
          $agent_id = Yii::app()->request->getParam('agent_id');
          $carlist = Yii::app()->request->getParam('carlist');
          $inspection_image1 = Yii::app()->request->getParam('img1');
          $inspection_image2 = Yii::app()->request->getParam('img2');
          $inspection_image3 = Yii::app()->request->getParam('img3');
          $inspection_image4 = Yii::app()->request->getParam('img4');
          $inspection_image5 = Yii::app()->request->getParam('img5');
          $data = array('id'=> $id,'address'=> $address,'status'=> $status,'agent_id'=> $agent_id);
          $image = array('damage_pic1'=> $inspection_image1,'damage_pic2'=> $inspection_image2,'damage_pic3'=> $inspection_image3,'damage_pic4'=> $inspection_image4,'damage_pic5'=> $inspection_image5);
          $data = array_filter($data);
          $image = array_filter($image);
          $update_orders = Washingrequests::model()->updateAll($data,'id=:id',array(':id'=>$id));

          foreach($image as $image){

              $washinginspectmodel = new Washinginspections;
          $washinginspectmodel->wash_request_id = $id;
          $washinginspectmodel->vehicle_id = $carlist;
          $washinginspectmodel->damage_pic = $image;
          $washinginspectmodel->save(false);
          }
          $result = 'true';
                $response = 'updated successfully';
                $json = array(
                'result'=> $result,
                'response'=> $response
            );

         echo json_encode($json);
         die();
    }

	public function actionprofiledetails(){

		$agent_id = Yii::app()->request->getParam('agent_id');

		if((isset($agent_id) && !empty($agent_id))){
			$agent_id_check = Agents::model()->findByAttributes(array("id"=>$agent_id));
			if(count($agent_id_check)>0){


                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_id_check->id));

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
				$json= array(
					'result'=> 'true',
					'response'=> 'Agent details',
					'id' => $agent_id_check->id,
                    'first_name' => $agent_id_check->first_name,
                    'last_name' => $agent_id_check->last_name,
					'email' => $agent_id_check->email,
                    'phone_number' => $agent_id_check->phone_number,
                    'street_address' => $agent_id_check->street_address,
                    'suite_apt' => $agent_id_check->suite_apt,
                    'city' => $agent_id_check->city,
                    'state' => $agent_id_check->state,
                    'zipcode' => $agent_id_check->zipcode,
                    'driver_license' => $agent_id_check->driver_license,
                    'proof_insurance' => $agent_id_check->proof_insurance,
                    'legally_eligible' => $agent_id_check->legally_eligible,
                    'own_vehicle' => $agent_id_check->own_vehicle,
                    'waterless_wash_product' => $agent_id_check->waterless_wash_product,
                    'operate_area' => $agent_id_check->operate_area,
                    'work_schedule' => $agent_id_check->work_schedule,
                    'operating_as' => $agent_id_check->operating_as,
                    'company_name' => $agent_id_check->company_name,
                    'wash_experience' => $agent_id_check->wash_experience,
                    'image' => $agent_id_check->image,
                    'status' => $agent_id_check->status,
                    'account_status' => $agent_id_check->account_status,
                    'total_washes' => $agent_id_check->total_wash,
                    'rating' => $agent_rate,
                    'created_date' => $agent_id_check->created_date,

				);
			}else{
				$json = array(
					'result'=> 'false',
					'response'=> 'Invalid agent'
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


      public function actioncancelwashrequest(){

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');
        $result= 'false';
        $response= 'Pass the required parameters';
        $json= array();
        if((isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))){


            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id'=>$wash_request_id));

            if(!count($wrequest_id_check)){
                $result= 'false';
                $response= 'Invalid wash request id';
            }

            else if(($status != 5) && ($status != 6)){
                $result= 'false';
                $response= 'Invalid status code';
            }

             else if(($wrequest_id_check->status == 1) && ($status == 5)){
                 $to_time = strtotime("now");
                $from_time = strtotime($wrequest_id_check->wash_begin);
                $mins = round(abs($to_time - $from_time) / 60,2);
                if($mins > 5){
                $result= 'false';
                $response= 'you cannot cancel wash until paying $10';
                }
                else{
                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",",$car_ids);
                    foreach($car_ids_arr as $car){
                        $carresetdata= array('status' => 0, 'eco_friendly' => 0, 'damage_points'=> '','damage_pic'=>'', 'upgrade_pack'=> 0, 'edit_vehicle'=> 0, 'remove_vehicle_from_kart'=> 0, 'new_vehicle_confirm'=> 0, 'new_pack_name'=> '');
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id'=>$car));
                    }

                      $data= array('status' => $status);
                $washrequestmodel = new Washingrequests;
                $washrequestmodel->attributes= $data;

                $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id'=>$wash_request_id));
                if($resUpdate){
                    $result= 'true';
                    if($status == 5) $response= 'Wash request is cancelled by client';
                    if($status == 6) $response= 'Wash request is cancelled by agent';
                }
                else{
                    $result= 'false';
                    $response= 'Wash request is not cancelled';
                }
                   if($wrequest_id_check->agent_id && $wrequest_id_check->agent_id > 0){
                  $agentmodel = Agents::model()->findByPk($wrequest_id_check->agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);
                    }
                }
            }

             else if(($wrequest_id_check->status > 1) && ($wrequest_id_check->status <= 3) && $status == 5){
                $result= 'false';
                $response= 'you cannot cancel wash until paying $10';
            }

            else{

 $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",",$car_ids);
                    foreach($car_ids_arr as $car){
                        $carresetdata= array('status' => 0, 'eco_friendly' => 0, 'damage_points'=> '','damage_pic'=>'', 'upgrade_pack'=> 0, 'edit_vehicle'=> 0, 'remove_vehicle_from_kart'=> 0, 'new_vehicle_confirm'=> 0, 'new_pack_name'=> '');
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id'=>$car));
                    }


                   $custmodel = Customers::model()->findByPk($wrequest_id_check->customer_id);
                $data= array('status' => $status,'customer_wash_points' => $custmodel->fifth_wash_points);
                $washrequestmodel = new Washingrequests;
                $washrequestmodel->attributes= $data;

                $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id'=>$wash_request_id));
                if($resUpdate){
                    $result= 'true';
                    if($status == 5) $response= 'Wash request is cancelled by client';
                    if($status == 6) $response= 'Wash request is cancelled by agent';
                }
                else{
                    $result= 'false';
                    $response= 'Wash request is not cancelled';
                }
                   if($wrequest_id_check->agent_id && $wrequest_id_check->agent_id > 0){
                  $agentmodel = Agents::model()->findByPk($wrequest_id_check->agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);
                    }
            }


        }
        else{
            $result= 'false';
            $response= 'Pass the required parameters';

        }
        $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);
    }


     public function actionpendingwashesdetails(){

        $result= 'false';
        $response= 'No pending orders found';
        $json= array();
        $pendingwasharr =  array();

            $pendingwashes = Washingrequests::model()->findAllByAttributes(array('status'=>0, 'is_scheduled'=>0));

            if(!count($pendingwashes)){
                $result= 'false';
                $response= 'No pending orders found';
            }

            else{
                  $result= 'true';
                $response= 'pending washes';
                 foreach($pendingwashes as $ind=> $pwash){
$cust_details = Customers::model()->findByAttributes(array('id'=>$pwash['customer_id']));
                     $pendingwasharr[$ind]['id'] = $pwash['id'];
                     $pendingwasharr[$ind]['customer_id'] = $pwash['customer_id'];
$pendingwasharr[$ind]['customer_name'] = $cust_details->customername;
                     $pendingwasharr[$ind]['address'] = $pwash['address'];
                     $pendingwasharr[$ind]['latitude'] = $pwash['latitude'];
                     $pendingwasharr[$ind]['longitude'] = $pwash['longitude'];

                      /* ------- get nearest agents --------- */

$handle = curl_init("https://www.mobilewash.com/api/index.php?r=agents/getnearestagents");
$data = array('wash_request_id' => $pwash['id']);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$output = curl_exec($handle);
curl_close($handle);
$nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */

            if(count($nearagentsdetails->nearest_agents)){
                $i = 0;
                foreach($nearagentsdetails->nearest_agents as $aid=>$nagent){
                    $agent_details = Agents::model()->findByAttributes(array('id'=>$aid));
                    $agent_loc_details = AgentLocations::model()->findByAttributes(array('agent_id'=>$aid));
                     $pendingwasharr[$ind]['available_agents'][$i]['id'] = $aid;
                     $pendingwasharr[$ind]['available_agents'][$i]['name'] = $agent_details->first_name." ".$agent_details->last_name;
                     $pendingwasharr[$ind]['available_agents'][$i]['latitude'] = $agent_loc_details->latitude;
                     $pendingwasharr[$ind]['available_agents'][$i]['longitude'] = $agent_loc_details->longitude;
                     $pendingwasharr[$ind]['available_agents'][$i]['distance'] = round($nagent, 2);
                     $i++;
                }
            }

            else{
                $pendingwasharr[$ind]['available_agents'] = '';
            }

                     //$pendingwasharr[$ind]['available_agents'] = $nearagentsdetails->nearest_agents;
                 }

            }




        $json= array(
            'result'=> $result,
            'response'=> $response,
            'pending_washes' => $pendingwasharr
        );
        echo json_encode($json);
    }

 public function actioncheckcoveragezipcode(){

 $zipcode = Yii::app()->request->getParam('zipcode');

  $result= 'false';
  $response= 'enter zip code';

if((isset($zipcode) && !empty($zipcode))){

$check_zip = CoverageAreaCodes::model()->findByAttributes(array('zipcode'=>$zipcode));

            if(count($check_zip)){
                $result= 'true';
                $response= 'zipcode found';
            }
else{
 $response= 'zipcode not found';
}
}


 $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);


}


public function actioncheckcoveragezipcodeweb(){

 $zipcode = Yii::app()->request->getParam('zipcode');

  $result= 'false';
  $response= 'enter zip code';

if((isset($zipcode) && !empty($zipcode))){

$check_zip = CoverageAreaCodesWeb::model()->findByAttributes(array('zipcode'=>$zipcode));

            if(count($check_zip)){
                $result= 'true';
                $response= 'zipcode found';
            }
else{
 $response= 'zipcode not found';
}
}


 $json= array(
            'result'=> $result,
            'response'=> $response
        );
        echo json_encode($json);


}


public function actiongetallcoveragezipcodes(){
        $all_zipcodes = array();

        $result= 'false';
		$response= 'none';

        $codes_exists = Yii::app()->db->createCommand()->select('*')->from('coverage_area_zipcodes')->queryAll();

        if(count($codes_exists)>0){
           $result= 'true';
		    $response= 'all coverage area zipcodes';

            foreach($codes_exists as $ind=>$zipcode){

                $all_zipcodes[$ind]['id'] = $zipcode['id'];
               $all_zipcodes[$ind]['zipcode'] = $zipcode['zipcode'];

            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'zipcodes'=> $all_zipcodes
		);
		echo json_encode($json);

    }


public function actionaddcoveragezipcode(){

		$result= 'false';
		$response= 'Fill up required fields';


		$zipcode = Yii::app()->request->getParam('zipcode');


		if((isset($zipcode) && !empty($zipcode)))
			 {

             $zip_check = CoverageAreaCodes::model()->findAllByAttributes(array("zipcode"=>$zipcode));

             	if(count($zip_check) > 0){
                   	$result= 'false';
		$response= 'zipcode already exists';
                }

                else{
                   $zipdata= array(
					'zipcode'=> $zipcode
				);

				    $model=new CoverageAreaCodes;
				    $model->attributes= $zipdata;
				    if($model->save(false)){
                       $zip_id = Yii::app()->db->getLastInsertID();
                    }

                    	$result= 'true';
		$response= 'zipcode added successfully';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiondeletecoveragezipcode(){

         $result= 'false';
		$response= 'Please provide zipcode id';

		$id = Yii::app()->request->getParam('id');



		if((isset($id) && !empty($id)))
		{

            $code_exists = CoverageAreaCodes::model()->findByAttributes(array("id"=>$id));
              if(!count($code_exists)){
                 $response = "Invalid zipcode id";
              }


           else{
				$response = "zipcode deleted";
                $result = 'true';

                  CoverageAreaCodes::model()->deleteByPk(array('id'=>$id));
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);

    }
	public function Actionorder_schedule(){
		/* Checking for post(month) parameters */
		$order_month='';
		if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
			$last_month = Yii::app()->request->getParam('start');
			$curr_month = Yii::app()->request->getParam('end');
			$order_month = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
		}
		/* Post END */
		$path = '/home/mobilewa/public_html/api/protected/controllers/test.php';
		/* Phone Orders */
		$phone_orders = array();

		$orders_exists =  Yii::app()->db->createCommand("SELECT * FROM phone_orders$order_month ORDER BY schedule_date DESC")->queryAll();

		if(count($orders_exists)>0){
			$result= 'true';
		    $response= 'all orders';
            foreach($orders_exists as $ind=>$order){

                $ph_order_id = $order['id'];
				$ph_checklist = $order['checklist'];
				$ph_created = $order['schedule_date'];
				$checklist_arr = explode("|", $order['checklist']);
				if(!$order['checklist']){
					$order_of_status = 'Pending';
					$color = '#FF3B30';
				}
				else if(count($checklist_arr) == 8){
					$order_of_status = 'Complete';
					$color = '#30A0FF';
				}
				else{
					$order_of_status = 'Processing';
					$color = '#EF9047';
				}
				$phone_orders[] = array(
					"start"		=>	date('Y-m-d',strtotime($ph_created)),
					"title"		=>	$order_of_status,
					"color" 	=>	$color
				);
            }
			$data = array();
			/* foreach($orderview as $key=>$value){ */
			foreach($phone_orders as $key=>$value){
				if($value['title'] == 'Complete'){
					$data[$value['start']]['complete'][]  = $value['title'];
				}
				if($value['title'] == 'Pending'){
					$data[$value['start']]['pending'][]  = $value['title'];
				}
				if($value['title'] == 'Processing'){
					$data[$value['start']]['processing'][]  = $value['title'];
				}

			}
			$dt =array();
			foreach($data as $key=>$val){
				$dt[$key]['complete']['color']= '';
				$dt[$key]['pending']['color']='';
				$dt[$key]['processing']['color']='';
				$dt[$key]['home']['count']='';
				$dt[$key]['work']['count']='';
				if(count($val['complete'])>0){
					$dt[$key]['complete']['count']= count($val['complete']);
					$dt[$key]['complete']['color']= '#30A0FF';
				}
				if(count($val['pending'])>0){
					$dt[$key]['pending']['count']= count($val['pending']);
					$dt[$key]['pending']['color']= '#FF3B30';
				}
				if(count($val['processing'])>0){
					$dt[$key]['processing']['count']= count($val['processing']);
					$dt[$key]['processing']['color']= '#EF9047';
				}
			}
			$ordersdetails['order'] = $dt;
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}else{
			$ordersdetails['order'] = array('empty'=>'yes');
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}
	}
	/* App Order */
	public function Actionorder_schedule_app(){
		/* Checking for post(month) parameters */
		$order_month='';
		if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
			$last_month = Yii::app()->request->getParam('start');
			$curr_month = Yii::app()->request->getParam('end');
			$order_month = " AND ( DATE_FORMAT(a.schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(a.schedule_date,'%Y-%m')<= '$curr_month')";
		}
		/* Post END */

		/* web orderds */
		$total_order =  Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];

        $customers_order =  Yii::app()->db->createCommand("SELECT a.id, a.status, a.schedule_date, a.address_type, a.wash_request_position FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.status NOT IN (5,6)$order_month")->queryAll();

		/* END */
		if(!empty($customers_order)){
			foreach($customers_order as $orderbycustomer){

				//$counttype = array_count_values($package_list_explode);
				$orderstatus = $orderbycustomer['status'];
				$orderid = $orderbycustomer['id'];
				$address_type = $orderbycustomer['address_type'];
				$created_date = $orderbycustomer['schedule_date'];
				$wash_request_position = $orderbycustomer['wash_request_position'];
				$color='';

				if($orderstatus == 4)
				{
					$order_of_status = 'Complete';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#30A0FF';
				}
				elseif($orderstatus == 0)
				{
					$order_of_status = 'Pending';
					$time = $orderbycustomer['schedule_date']." ".$orderbycustomer['schedule_time'];
					$currentdate = date("Y-m-d h:i:s");
					$to_time = strtotime($time);
					$from_time = strtotime($currentdate);
					$totalminutes = round(abs($to_time - $from_time) / 60,2);
					$color = '#FF3B30';
				}
				elseif($orderstatus>=1 && $orderstatus<=3)
				{
					$order_of_status = 'Processing';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#EF9047';
				}


				$key = 'order_'.$count.'_'.$orderid;
				$json = array();
				$json['title'] =  $order_of_status;
				$json['orderid'] =  $orderid;
				$json['time'] =  $totalminutes;
				$json['address_type'] =  $address_type;
				$json['start'] = date('Y-m-d',strtotime($created_date));

				if($wash_request_position == 'real'){
					$orderview[] = array(
						"start"		=>	date('Y-m-d',strtotime($created_date)),
						"title"		=>	$order_of_status,
						"color" 	=>	$color,
						"address_type" 	=>	$address_type
					);
				}
			}
			 //print_r($orderview);
			$data = array();
			foreach($orderview as $key=>$value){
				if($value['address_type'] == 'Home'){
					$data[$value['start']]['home'][]  = $value['address_type'];
				}
				if($value['address_type'] == 'Work'){
					$data[$value['start']]['work'][]  = $value['address_type'];
				}
				if($value['title'] == 'Complete'){
					$data[$value['start']]['complete'][]  = $value['title'];
				}
				if($value['title'] == 'Pending'){
					$data[$value['start']]['pending'][]  = $value['title'];
				}
				if($value['title'] == 'Processing'){
					$data[$value['start']]['processing'][]  = $value['title'];
				}
			}
			$dt =array();
			foreach($data as $key=>$val){
				$dt[$key]['complete']['color']= '';
				$dt[$key]['pending']['color']='';
				$dt[$key]['processing']['color']='';
				$dt[$key]['home']['count']='';
				$dt[$key]['work']['count']='';
				//print_r($val);
				if(count($val['complete'])>0){
					$dt[$key]['complete']['count']= count($val['complete']);
					$dt[$key]['complete']['color']= '#30A0FF';
				}
				if(count($val['pending'])>0){
					$dt[$key]['pending']['count']= count($val['pending']);
					$dt[$key]['pending']['color']= '#FF3B30';
				}
				if(count($val['processing'])>0){
					$dt[$key]['processing']['count']= count($val['processing']);
					$dt[$key]['processing']['color']= '#EF9047';
				}
				if(count($val['home'])>0){
					$dt[$key]['home']['count']= count($val['home']);
				}
				if(count($val['work'])>0){
					$dt[$key]['work']['count']= count($val['work']);
				}
			}
			 /* print_r($dt);die; */
			$ordersdetails['order'] = $dt;
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}else{
			$ordersdetails['order'] = array('empty'=>'yes');
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}
	}
	/* SCHEDULE ORDER CALENDAR */
	public function Actionorder_schedule_ordSchedule(){
		/* Checking for post(month) parameters */
		$order_month='';
		if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
			$last_month = Yii::app()->request->getParam('start');
			$curr_month = Yii::app()->request->getParam('end');
			$order_month = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
		}
		/* Post END */
        $customers_order =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND (status !=5 && status !=6)$order_month ORDER BY schedule_date DESC")->queryAll();

		/* END */

		if(!empty($customers_order)){
			foreach($customers_order as $orderbycustomer){
				//$counttype = array_count_values($package_list_explode);
				$orderstatus = $orderbycustomer['status'];
				$orderid = $orderbycustomer['id'];
				$schedule_date = $orderbycustomer['schedule_date'];
				$address_type = $orderbycustomer['address_type'];
				$wash_request_position = $orderbycustomer['wash_request_position'];

				$color='';
				$order_of_status = '';
					if($orderstatus == 4)
					{
						$order_of_status = 'Complete';
						$totalminutes = 'N/A';
						$near_agent = '';
						$color = '#30A0FF';
					}
					if($orderstatus == 0)
					{
						$order_of_status = 'Pending';
						$time = $orderbycustomer['schedule_date'];
						$currentdate = date("Y-m-d h:i:s");
						$to_time = strtotime($time);
						$from_time = strtotime($currentdate);
						$totalminutes = round(abs($to_time - $from_time) / 60,2);
						$color = '#FF3B30';
					}
					if($orderstatus>=1 && $orderstatus<=3)
					{
						$order_of_status = 'Processing';
						$totalminutes = 'N/A';
						$near_agent = '';
						$color = '#EF9047';
					}
					/* elseif($orderstatus == 5 || $orderstatus == 6){
						$order_of_status = 'Cancelled';
						$totalminutes = 'N/A';
						$near_agent = '';
						$color = '#EF9047';
					} */
					$key = 'order_'.$count.'_'.$orderid;
					$json = array();
					$json['title'] =  $order_of_status;
					$json['orderid'] =  $orderid;
					$json['time'] =  $totalminutes;
					$json['start'] = date('Y-m-d',strtotime($schedule_date));

					if($orderstatus != 5 && $orderstatus != 6 && $wash_request_position == 'real'){
						$orderview[] = array(
							"start"		=>	date('Y-m-d',strtotime($schedule_date)),
							"title"		=>	$order_of_status,
							"color" 	=>	$color,
							"address_type" 	=>	$address_type,
							"orderstatus" 	=>	$orderstatus
						);
					}
			}

			$data = array();
			foreach($orderview as $key=>$value){
				if($value['address_type'] == 'Home'){
					$data[$value['start']]['home'][]  = $value['address_type'];
				}
				if($value['address_type'] == 'Work'){
					$data[$value['start']]['work'][]  = $value['address_type'];
				}
				if($value['title'] == 'Complete'){
					$data[$value['start']]['complete'][]  = $value['title'];
				}
				if($value['title'] == 'Pending'){
					$data[$value['start']]['pending'][]  = $value['title'];
				}
				if($value['title'] == 'Processing'){
					$data[$value['start']]['processing'][]  = $value['title'];
				}

			}

			$dt =array();
			foreach($data as $key=>$val){
				$dt[$key]['complete']['color']= '';
				$dt[$key]['pending']['color']='';
				$dt[$key]['processing']['color']='';
				$dt[$key]['home']['count']='';
				$dt[$key]['work']['count']='';
				if(count($val['complete'])>0){
					$dt[$key]['complete']['count']= count($val['complete']);
					$dt[$key]['complete']['color']= '#30A0FF';
				}
				if(count($val['pending'])>0){
					$dt[$key]['pending']['count']= count($val['pending']);
					$dt[$key]['pending']['color']= '#FF3B30';
				}
				if(count($val['processing'])>0){
					$dt[$key]['processing']['count']= count($val['processing']);
					$dt[$key]['processing']['color']= '#EF9047';
				}
				if(count($val['home'])>0){
					$dt[$key]['home']['count']= count($val['home']);
				}
				if(count($val['work'])>0){
					$dt[$key]['work']['count']= count($val['work']);
				}
			}
			//print_r($dt);
			$ordersdetails['order'] = $dt;
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}else{
			$ordersdetails['order'] = array('empty'=>'yes');
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}
	}
	/*
	* FUNCTION FOR ALL ORDER COUNT WHICH ARE SHOWING ON CALENDAR
	* ONCE CLICK ON ALL ORDER BUTTON.
	*/
	public function Actionorder_schedule_all_orders(){
		/* Checking for post(month) parameters */
		$order_month='';
		if(!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))){
			$last_month = Yii::app()->request->getParam('start');
			$curr_month = Yii::app()->request->getParam('end');
			$order_month = " AND ( DATE_FORMAT(a.schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(a.schedule_date,'%Y-%m')<= '$curr_month')";
			$order_month_phone = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
			$order_month_sch = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= '$last_month' AND DATE_FORMAT(schedule_date,'%Y-%m')<= '$curr_month')";
		}
		/* Post END */

		/* $path = '/home/mobilewa/public_html/api/protected/controllers/test.php';
		file_put_contents($path,serialize($phone_orders)); */

		/* web orderds */
		$total_order =  Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];

        $customers_order =  Yii::app()->db->createCommand("SELECT a.id, a.status, a.schedule_date FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.status NOT IN (5,6)$order_month")->queryAll();

		/* END */


		/* Phone Orders */
		$phone_orders = array();
		$orders_exists =  Yii::app()->db->createCommand("SELECT * FROM phone_orders$order_month_phone ORDER BY schedule_date DESC")->queryAll();
		if(count($orders_exists)>0){
			$result= 'true';
		    $response= 'all orders';
            foreach($orders_exists as $ind=>$order){

                $ph_order_id = $order['id'];
				$ph_checklist = $order['checklist'];
				/* $ph_created = $order['schedule_date']; */
				$ph_created = $order['schedule_date'];
				$checklist_arr = explode("|", $order['checklist']);
				if(!$order['checklist']){
					$order_of_status = 'Pending';
					$color = '#FF3B30';
				}
				else if(count($checklist_arr) == 8){
					$order_of_status = 'Complete';
					$color = '#30A0FF';
				}
				else{
					$order_of_status = 'Processing';
					$color = '#EF9047';
				}
				$phone_orders[] = array(
					"start"		=>	date('Y-m-d',strtotime($ph_created)),
					"title"		=>	$order_of_status,
					"color" 	=>	$color
				);
            }
        }
		/* END */
		/* SCHEDULE ORDERS */
		$schedule_order =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND (status !=5 && status !=6) $order_month_sch ORDER BY schedule_date DESC")->queryAll();

		if(!empty($schedule_order)){
			foreach($schedule_order as $orderbyschdule){
				//$counttype = array_count_values($package_list_explode);
				$orderstatus = $orderbyschdule['status'];
				$orderid = $orderbyschdule['id'];
				$created_date = $orderbyschdule['schedule_date'];
				$color='';
				$orderstatus ="";
				if($orderstatus == 4)
				{
					$order_of_status = 'Complete';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#30A0FF';
				}
				elseif($orderstatus == 0)
				{
					$order_of_status = 'Pending';
					$time = $orderbyschdule['schedule_date'];
					$currentdate = date("Y-m-d h:i:s");
					$to_time = strtotime($time);
					$from_time = strtotime($currentdate);
					$totalminutes = round(abs($to_time - $from_time) / 60,2);
					$color = '#FF3B30';
				}
				elseif($orderstatus>=1 && $orderstatus<=3)
				{
					$order_of_status = 'Processing';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#EF9047';
				}/* elseif($orderstatus == 5 || $orderstatus == 6){
					$order_of_status = 'Cancelled';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#EF9047';
				} */
				$key = 'order_'.$count.'_'.$orderid;
				$json = array();
				$json['title'] =  $order_of_status;
				$json['orderid'] =  $orderid;
				$json['time'] =  $totalminutes;
				$json['start'] = date('Y-m-d',strtotime($created_date));

				if($orderstatus != 5 && $orderstatus != 6){
					$sch_orderview[] = array(
						"start"		=>	date('Y-m-d',strtotime($created_date)),
						"title"		=>	$order_of_status,
						"color" 	=>	$color
					);
				}
			}
		}
		//print_r($sch_orderview);die;
		/* END */

        $package_name = array();
        $car_id = array();
		if(!empty($customers_order)){
			foreach($customers_order as $orderbycustomer){
				//$counttype = array_count_values($package_list_explode);
				$orderstatus = $orderbycustomer['status'];
				$orderid = $orderbycustomer['id'];
				$created_date = $orderbycustomer['schedule_date'];
				$color='';

				if($orderstatus == 4)
				{
					$order_of_status = 'Complete';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#30A0FF';
				}
				elseif($orderstatus == 0)
				{
					$order_of_status = 'Pending';
					$time = $orderbycustomer['schedule_date']." ".$orderbycustomer['schedule_time'];
					$currentdate = date("Y-m-d h:i:s");
					$to_time = strtotime($time);
					$from_time = strtotime($currentdate);
					$totalminutes = round(abs($to_time - $from_time) / 60,2);
					$color = '#FF3B30';
				}
				elseif($orderstatus>=1 && $orderstatus<=3)
				{
					$order_of_status = 'Processing';
					$totalminutes = 'N/A';
					$near_agent = '';
					$color = '#EF9047';
				}
				$key = 'order_'.$count.'_'.$orderid;
				$json = array();
				$json['title'] =  $order_of_status;

				$json['orderid'] =  $orderid;
				$json['time'] =  $totalminutes;
				$json['start'] = date('Y-m-d',strtotime($created_date));


				$orderview[] = array(
					"start"		=>	date('Y-m-d',strtotime($created_date)),
					"title"		=>	$order_of_status,
					"color" 	=>	$color
				);
			}
			/* Merge Both WEB & Phone Orders array */
			$orderview1 = array_merge($phone_orders,$orderview);



			/* Merging App, Phone, Schedule Orders array */
			$allorders = array_merge($sch_orderview,$orderview1);

			$data = array();
			/* foreach($orderview as $key=>$value){ */
			foreach($allorders as $key=>$value){
				if($value['title'] == 'Complete'){
					$data[$value['start']]['complete'][]  = $value['title'];
				}
				if($value['title'] == 'Pending'){
					$data[$value['start']]['pending'][]  = $value['title'];
				}
				if($value['title'] == 'Processing'){
					$data[$value['start']]['processing'][]  = $value['title'];
				}

			}
			$dt =array();
			foreach($data as $key=>$val){
				$dt[$key]['complete']['color']= '';
				$dt[$key]['pending']['color']='';
				$dt[$key]['processing']['color']='';
				$dt[$key]['home']['count']='';
				$dt[$key]['work']['count']='';
				if(count($val['complete'])>0){
					$dt[$key]['complete']['count']= count($val['complete']);
					$dt[$key]['complete']['color']= '#30A0FF';
				}
				if(count($val['pending'])>0){
					$dt[$key]['pending']['count']= count($val['pending']);
					$dt[$key]['pending']['color']= '#FF3B30';
				}
				if(count($val['processing'])>0){
					$dt[$key]['processing']['count']= count($val['processing']);
					$dt[$key]['processing']['color']= '#EF9047';
				}
			}
			$ordersdetails['order'] = $dt;
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}else{
			$ordersdetails['order'] = array('empty'=>'yes');
			echo json_encode($ordersdetails,JSON_PRETTY_PRINT);

			exit;
		}

	}

public function actiongetwashrequestbyid(){
 $result= 'false';
		$response= 'Please provide wash request id';

		$id = Yii::app()->request->getParam('id');

$order_details = array();

		if((isset($id) && !empty($id)))
		{

            $order_exists = Washingrequests::model()->findByAttributes(array("id"=>$id));
			if(!count($order_exists)){
				$response = "Invalid order id";
			}


			else{
				$response = "Order details";
                $result = 'true';

				$order_det = Washingrequests::model()->findByPk($id);
$vip_membership = '';

				$cust_exists = Customers::model()->findByAttributes(array("id"=>$order_det->customer_id));
if($order_det->vip_coupon_code) {
$coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$order_det->vip_coupon_code));
$vip_membership = $coupon_check->package_name;
}

				$order_details['id'] = $order_det->id;
				$order_details['agent_id'] = $order_det->agent_id;
				$order_details['customer_id'] = $order_det->customer_id;
				$order_details['name'] = $cust_exists->customername;
				$order_details['car_list'] = $order_det->car_list;
				$order_details['package_list'] = $order_det->package_list;
				$order_details['address'] = $order_det->address;
				$order_details['address_type'] = $order_det->address_type;
				$order_details['latitude'] = $order_det->latitude;
				$order_details['longitude'] = $order_det->longitude;
				$order_details['payment_type'] = $order_det->payment_type;
				$order_details['nonce'] = $order_det->nonce;
				$order_details['transaction_id'] = $order_det->transaction_id;
				$order_details['estimate_time'] = $order_det->estimate_time;
				$order_details['wash_begin'] = $order_det->wash_begin;
				$order_details['status'] = $order_det->status;
				$order_details['created_date'] = $order_det->created_date;
				$order_details['buzz_status'] = $order_det->buzz_status;
				$order_details['draft_vehicle_id'] = $order_det->draft_vehicle_id;
				$order_details['new_vehicle_confirm'] = $order_det->new_vehicle_confirm;
				$order_details['total_price'] = $order_det->total_price;
				$order_details['net_price'] = $order_det->net_price;
				$order_details['company_total'] = $order_det->company_total;
				$order_details['agent_total'] = $order_det->agent_total;
				$order_details['bundle_discount'] = $order_det->bundle_discount;
				$order_details['fifth_wash_discount'] = $order_det->fifth_wash_discount;
				$order_details['first_wash_discount'] = $order_det->first_wash_discount;
				$order_details['coupon_discount'] = $order_det->coupon_discount;
$order_details['coupon_code'] = $order_det->coupon_code;
$order_details['vip_coupon_code'] = $order_det->vip_coupon_code;
$order_details['vip_package'] = $vip_membership;
				$order_details['cancel_fee'] = $order_det->cancel_fee;
				$order_details['washer_arrival_notify'] = $order_det->washer_arrival_notify;
				$order_details['pet_hair_vehicles'] = $order_det->pet_hair_vehicles;
				$order_details['lifted_vehicles'] = $order_det->lifted_vehicles;
				$order_details['fifth_wash_vehicles'] = $order_det->fifth_wash_vehicles;
				$order_details['order_temp_assigned'] = $order_det->order_temp_assigned;
				$order_details['agent_reject_ids'] = $order_det->agent_reject_ids;
				$order_details['complete_order'] = $order_det->complete_order;
				$order_details['customer_wash_points'] = $order_det->customer_wash_points;
				$order_details['per_car_wash_points'] = $order_det->per_car_wash_points;
if($order_det->reschedule_time){
$order_details['schedule_date'] = $order_det->reschedule_date;
				$order_details['schedule_time'] = $order_det->reschedule_time;
}
else{
$order_details['schedule_date'] = $order_det->schedule_date;
				$order_details['schedule_time'] = $order_det->schedule_time;
}

				$order_details['is_scheduled'] = $order_det->is_scheduled;
				$order_details['scheduled_cars_info'] = $order_det->scheduled_cars_info;
				$order_details['schedule_total'] = $order_det->schedule_total;
$order_details['schedule_total_vip'] = $order_det->schedule_total_vip;
				$order_details['schedule_company_total'] = $order_det->schedule_company_total;
$order_details['schedule_company_total_vip'] = $order_det->schedule_company_total_vip;
				$order_details['schedule_agent_total'] = $order_det->schedule_agent_total;
				$order_details['checklist'] = $order_det->checklist;
$order_details['tip_amount'] = $order_det->tip_amount;
				$order_details['notes'] = $order_det->notes;
$order_details['escrow_status'] = $order_det->escrow_status;

				/* vehicals list */
				 /* --------- Get total price ------------- */

				$total_cars = explode(",",$order_det->car_list);
				$total_packs = explode(",",$order_det->package_list);
				$pet_hair_arr = explode(",",$order_det->pet_hair_vehicles);
				$lifted_vehicles_arr = explode(",",$order_det->lifted_vehicles);
				$fifth_vehicles_arr = explode(",",$order_det->fifth_wash_vehicles);




				foreach($total_cars as $carindex=>$car){

					$vehicle_details = Vehicle::model()->findByAttributes(array("id"=>$car));

					$vehicle_inspect_details = Washinginspections::model()->findByAttributes(array("wash_request_id"=>$id, "vehicle_id"=>$car));
					$inspect_img = '';
					if(count($vehicle_inspect_details) > 0){
						$inspect_img = $vehicle_inspect_details->damage_pic;
					}
					$washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Deluxe"));
                    if(count($washing_plan_deluxe)) $delx_price = $washing_plan_deluxe->price;
                    else $delx_price = "24.99";

                    $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Premium"));
                    if(count($washing_plan_prem)) $prem_price = $washing_plan_prem->price;
                    else $prem_price = "59.99";

					if($total_packs[$carindex] == 'Deluxe') {
                       $total += $delx_price;
                       $veh_price = $delx_price;
                       $agent_total += $veh_price * .8;
                       $company_total += $veh_price * .2;
                       $safe_handle_fee = $washing_plan_deluxe->handling_fee;
                       $company_total += $washing_plan_deluxe->handling_fee;
					}
					if($total_packs[$carindex] == 'Premium') {
                       $total += $prem_price;
                       $veh_price = $prem_price;
                       $agent_total += $veh_price * .75;
                       $company_total += $veh_price * .25;
                       $safe_handle_fee = $washing_plan_prem->handling_fee;
						$company_total += $washing_plan_prem->handling_fee;
					}

					//safe handling fee
					$total++;

					/* ----- pet hair / lift / fifth check ------- */

					$pet_hair = 0;
					$lift_vehicle = 0;
					$fifth_wash_disc = 0;

					if (in_array($car, $pet_hair_arr)){
						$total += 5;
						$total_pet_lift_fee += 5;



							$agent_total += 5 * .8;
							$company_total += 5 * .2;


						$pet_hair = 5;
					}

					if (in_array($car, $lifted_vehicles_arr)){
						$total += 5;
						$total_pet_lift_fee += 5;

						$agent_total += 5 * .8;
						$company_total += 5 * .2;

						$lift_vehicle = 5;
					}

					if (in_array($car, $fifth_vehicles_arr)){
						//$total += 5;
						//$total_pet_lift_fee += 5;
						//$agent_total += round(5 * .8, 2);
						$fifth_wash_disc = 5;
					}

					/* ----- pet hair / lift / fifth check end ------- */

					$veh_price_agent = 0;
					if($total_packs[$carindex] == 'Premium') {
						$veh_price_agent = round($veh_price * .75, 2);
					}
					else{
						$veh_price_agent = round($veh_price * .8, 2);
					}

					$vehicles[] = array(
						'id'=>$vehicle_details->id,
						'vehicle_no'=>$vehicle_details->vehicle_no,
						'brand_name'=>$vehicle_details->brand_name,
						'model_name'=>$vehicle_details->model_name,
						'vehicle_image'=>$vehicle_details->vehicle_image,
						'vehicle_inspect_image'=>$inspect_img,
						'vehicle_type'=>$vehicle_details->vehicle_type,
						'vehicle_washing_package' => $total_packs[$carindex],
						'vehicle_washing_price'=> $veh_price,
						'vehicle_washing_price_agent'=> $veh_price_agent,
						'safe_handling_fee' => $safe_handle_fee,
						'pet_hair_fee' => $pet_hair,
						'lifted_vehicle_fee' => $lift_vehicle,
						'fifth_wash_discount' => $fifth_wash_disc
					);

				}

				$order_details['vehicle'] = $vehicles;
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response,
			'order_details' => $order_details
		);

		echo json_encode($json);
}


public function actioncancelscheduleorder(){
		$result= 'false';
		$response= 'please provide required parameters';

		$id = Yii::app()->request->getParam('id');
        $customer_id = Yii::app()->request->getParam('customer_id');
		$name = Yii::app()->request->getParam('name');
		$email = Yii::app()->request->getParam('email');
		$address = Yii::app()->request->getParam('address');
		$address_type = Yii::app()->request->getParam('address_type');
		$fee = Yii::app()->request->getParam('fee');

		if((isset($id) && !empty($id)) && (isset($customer_id) && !empty($customer_id)) && (isset($fee) && !empty($fee)))
		{
			$result= 'true';
			$response=  'here';
            $order_exists = Washingrequests::model()->findByAttributes(array("id"=>$id));
            $cust_exists = Customers::model()->findByAttributes(array("id"=>$customer_id));
              if(!count($order_exists)){
                 $response = "Invalid order id";
              }

               else if(!count($cust_exists)){
                 $response = "Invalid customer id";
              }


           else{

           if($order_exists->reschedule_time) $scheduledatetime = $order_exists->reschedule_date." ".$order_exists->reschedule_time;
else $scheduledatetime = $order_exists->schedule_date." ".$order_exists->schedule_time;
//echo date('Y-m-d h:i A')."<br>";
$min_diff = 0;
$to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
if($from_time > $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}
//echo $min_diff."<br>";
if($min_diff <= 30){

               $braintree_id = '';
                 $braintree_id =  $cust_exists->braintree_id;

                if($cust_exists->client_position == 'real') $Bresult = Yii::app()->braintree->getCustomerById_real($braintree_id);
else $Bresult = Yii::app()->braintree->getCustomerById($braintree_id);
                //var_dump($Bresult);
                if(count($Bresult->paymentMethods)){
                  $result = 'true';
                  $response = 'payment methods';
                  foreach($Bresult->paymentMethods as $index=>$paymethod){

                  $request_data = ['amount' => $fee,'paymentMethodToken' => $paymethod->token, 'customer' => ['firstName' =>$cust_exists->customername,],'billing' => ['firstName' => $cust_exists->customername]];

                     if($cust_exists->client_position == 'real') $cancelresult = Yii::app()->braintree->sale_real($request_data);
else $cancelresult = Yii::app()->braintree->sale($request_data);

                     if(($cancelresult['success'] == 1)) {
                         if($cust_exists->client_position == 'real') $cancelsettle = Yii::app()->braintree->submitforsettlement_real($cancelresult['transaction_id']);
else $cancelsettle = Yii::app()->braintree->submitforsettlement($cancelresult['transaction_id']);
                        $result = 'true';
                        $response = 'order cancelled';
                         Washingrequests::model()->updateByPk($id, array('status'=>5, 'cancel_fee' => $fee));

$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$sched_date = '';
$sched_time = '';
					if($order_exists->reschedule_time){
if(strtotime($order_exists->reschedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';

					}
					else{
						$sched_date = date('M d', strtotime($order_exists->reschedule_date));
					}
$sched_time = $order_exists->reschedule_time;
}
else{
if(strtotime($order_exists->schedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';
					}
					else{
						$sched_date = date('M d', strtotime($order_exists->schedule_date));
					}
$sched_time = $order_exists->schedule_time;
}
					$message = '';
					$subject = 'Cancel Order Receipt - #0000'.$id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
					$message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>This order has been cancelled</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Scheduled order for ".$sched_date." @ ".$sched_time."</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$order_exists->address."</p>";
					$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>Client Name:</strong> ".$cust_exists->customername."</td><td style='text-align: right;'><strong>Order Number:</strong> #000".$id."</td></tr>
					</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
					$all_cars = explode("|", $order_exists->scheduled_cars_info);
					foreach($all_cars as $ind=>$vehicle){
						$car_details = explode(",", $vehicle);
						$message .="<tr>
						<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
						<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
						<tr>
						<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$car_details[0]." ".$car_details[1]."</p></td>
						<td style='text-align: right;'>
						<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$0</p>
						</td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>".$car_details[2]." Package</p></td>
						<td style='text-align: right;'></td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>Handling Fee</p></td>
						<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
						</tr>
						";
if($car_details[12]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
						if($car_details[5]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
						if($car_details[6]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[8]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[9]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[10]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						$message .= "</table></td></tr>";
					}

if($coupon_amount){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr></table>";
						}
$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 20px; margin: 0;'><strong>Cancellation Fee</strong></p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$fee."</p></td>
							</tr></table>";

					$message .= "</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$".number_format($fee, 2)."</span></p></td></tr></table>";



	Vargas::Obj()->SendMail($cust_exists->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("scheduling@mobilewash.com",$cust_exists->email,$message,$subject, 'mail-receipt');


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

 $message = "Order #".$id." has been cancelled\r\nCustomer Name: ".$cust_exists->customername."\r\nPhone: ".$cust_exists->contact_number."\r\nAddress: ".$order_exists->address;

            $sendmessage = $client->account->messages->create(array(
                'To' =>  '9098023158',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '8183313631',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '3109999334',
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));



break;

                     }
                     else{
                          if($cancelresult['success'] == 1) {
                           if($cust_exists->client_position == 'real') $cancelvoid = Yii::app()->braintree->void_real($cancelresult['transaction_id']);
else $cancelvoid = Yii::app()->braintree->void($cancelresult['transaction_id']);
                          }
                    $result= 'false';
		$response= 'error in payment';
                }



                  }
                }
                else{
                    $result= 'false';
		$response= 'no payment method exists';
                }
               }
               else{

                  $result = 'true';
                        $response = 'order cancelled';
                         Washingrequests::model()->updateByPk($id, array('status'=>5, 'cancel_fee' => 0));

$from = Vargas::Obj()->getAdminEmail();
					//echo $from;
					$sched_date = '';
$sched_time = '';
					if($order_exists->reschedule_time){
if(strtotime($order_exists->reschedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';

					}
					else{
						$sched_date = date('M d', strtotime($order_exists->reschedule_date));
					}
$sched_time = $order_exists->reschedule_time;
}
else{
if(strtotime($order_exists->schedule_date) == strtotime(date('Y-m-d'))){
						$sched_date = 'Today';
					}
					else{
						$sched_date = date('M d', strtotime($order_exists->schedule_date));
					}
$sched_time = $order_exists->schedule_time;
}
					$message = '';
					$subject = 'Cancel Order Receipt - #0000'.$id;
					//$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
					$message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>This order has been cancelled</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Scheduled order for ".$sched_date." @ ".$sched_time."</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at ".$order_exists->address."</p>";
					$message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>Client Name:</strong> ".$cust_exists->customername."</td><td style='text-align: right;'><strong>Order Number:</strong> #000".$id."</td></tr>
					</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
					$all_cars = explode("|", $order_exists->scheduled_cars_info);
					foreach($all_cars as $ind=>$vehicle){
						$car_details = explode(",", $vehicle);
						$message .="<tr>
						<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
						<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
						<tr>
						<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>".$car_details[0]." ".$car_details[1]."</p></td>
						<td style='text-align: right;'>
						<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$0</p>
						</td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>".$car_details[2]." Package</p></td>
						<td style='text-align: right;'></td>
						</tr>
						<tr>
						<td><p style='font-size: 18px; margin: 0;'>Handling Fee</p></td>
						<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
						</tr>
						";
if($car_details[12]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[13]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[14]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
if($car_details[15]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
						if($car_details[5]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Pet Hair Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}
						if($car_details[6]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[8]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[9]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>First Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						if($car_details[10]){
							$message .= "<tr>
							<td>
							<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
							</td>
							<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr>";
						}

						$message .= "</table></td></tr>";
					}

if($coupon_amount){
							$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-</p></td>
							</tr></table>";
						}
$message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 20px; margin: 0;'><strong>Cancellation Fee</strong></p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0; font-weight: bold;'>$0</p></td>
							</tr></table>";

					$message .= "</table>";

					$message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$0</span></p></td></tr></table>";



	Vargas::Obj()->SendMail($cust_exists->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
Vargas::Obj()->SendMail("scheduling@mobilewash.com",$cust_exists->email,$message,$subject, 'mail-receipt');


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


            $message = "Order #".$id." has been cancelled\r\nCustomer Name: ".$cust_exists->customername."\r\nPhone: ".$cust_exists->contact_number."\r\nAddress: ".$order_exists->address;

            $sendmessage = $client->account->messages->create(array(
                'To' =>  '9098023158',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '8183313631',
                'From' => '+13103128070',
                'Body' => $message,
            ));

$sendmessage = $client->account->messages->create(array(
                'To' =>  '3109999334',
                'From' => '+13103128070',
                'Body' => $message,
            ));

            spl_autoload_register(array('YiiBase','autoload'));


}
			}

		}

		$json= array(
			'result'=> $result,
			'response'=> $response
		);

		echo json_encode($json);
}


 public function actiongetschedulewashrequests(){
		/* Checking for post(day) parameters */
		$order_day='';
		if(!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))){
			$day = Yii::app()->request->getParam('day');
			$event = Yii::app()->request->getParam('event');
			$status_qr = '';
			if($event == 'pending'){
				$status = 0;
				$status_qr = ' AND status="'.$status.'"';
			}elseif($event == 'completed'){
				$status = 4;
				$status_qr = ' AND status="'.$status.'"';
			}elseif($event == 'processing'){
				$status = 2;
				$status_qr = ' AND status="'.$status.'"';
			}else{
				$status_qr = ' AND (status !=5 && status !=6)';
			}

			$order_day = " AND DATE_FORMAT(schedule_date,'%Y-%m-%d')= '$day'$status_qr";
		}
		/* END */



        $json = array();

        $result= 'true';
        $response= 'scheduled wash requests';
        $pendingwashrequests = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';

		/* $qry = "SELECT * FROM washing_requests WHERE is_scheduled = 1$order_day ORDER BY id DESC";

		$path = '/home/mobilewa/public_html/api/protected/controllers/test.php';
		file_put_contents($path,serialize($qry));
		die; */

		$qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 ORDER BY id DESC")->queryAll();

        if(count($qrRequests)>0){

            foreach($qrRequests as $wrequest)
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
                );


            }

        }
        else{
           $result= 'false';
			$response= 'no scheduled wash requests found';
        }

        $json = array(
            'result'=> $result,
            'response'=> $response,
            'schedule_wash_requests' => $pendingwashrequests
        );

        echo json_encode($json); die();
    }

	private function _getHoursDifference($date1,$date2){
		$diff = abs(strtotime($date2) - strtotime($date1));

		$years = floor($diff / (365*60*60*24)); $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));

		$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

		$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
		return $hours;
	}
    public function actiongetallschedulewashes() {
		$response = "no scheduled washes found";
		$result = "false";
		$allwashes = array();
		$agent_id = Yii::app()->request->getParam('agent_id');

$criteria=new CDbCriteria;
$criteria->condition = "wash_request_position != 'real' AND agent_id = 0 AND is_scheduled = 1 AND status = 0";

		$allschedwashes = Washingrequests::model()->findAll($criteria, array('order' => 'created_date asc'));

//print_r($allschedwashes);
		if(count($allschedwashes)){
			$customerName='';
			$currentDateTime =  date('Y-m-d h:i:s', time());
			$currentDate =  date('Y-m-d');
			foreach($allschedwashes as $schedwash){

				$schdDateTime = date('Y-m-d h:i:s', strtotime($schedwash->schedule_date.' '.$schedwash->schedule_time));
				$schdDate = date('Y-m-d', strtotime($schedwash->schedule_date));

				$hoursDiff = $this->_getHoursDifference($currentDateTime,$schdDateTime);
                //echo $schedwash->id." ".$hoursDiff."<br>";
            	//if($schdDate >= $currentDate && $hoursDiff >= 1){
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
					if(!empty($schedwash->customer_id)){
						$customer = Yii::app()->db->createCommand()->setFetchMode(PDO::FETCH_OBJ)
							 ->select('customername')
							 ->from('customers')
							 ->where("id =$schedwash->customer_id")
							 ->queryAll();
						$customerName = $customer[0]->customername;
					}
					$washtime = 0;
					$washtime_str = '';
					$cars = explode(",",$schedwash->car_list);
					$plans = explode(",",$schedwash->package_list);
					foreach($cars as $ind=>$car){
						$car_detail =  Vehicle::model()->findByPk($car);
						//echo $car_detail->brand_name." ".$car_detail->model_name."<br>";

						$handle = curl_init("https://www.mobilewash.com/api/index.php?r=washing/plans");
						$data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build);
						curl_setopt($handle, CURLOPT_POST, true);
						curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
						curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
						$plan_result = curl_exec($handle);
						curl_close($handle);
						$jsondata = json_decode($plan_result);
						if($plans[$ind] == 'Deluxe'){
							//echo $jsondata->plans->deluxe[0]->wash_time."<br>";
							$delprice = intval($jsondata->plans->deluxe[0]->wash_time);
							$washtime += $delprice;
						}

						if($plans[$ind] == 'Premium'){
							//echo $jsondata->plans->premium[0]->wash_time."<br>";
							$premprice = intval($jsondata->plans->premium[0]->wash_time);
							$washtime += $premprice;
						}

                         /* --- addons time ----- */



$pet_hair_vehicles_arr = explode(",", $schedwash->pet_hair_vehicles);
if (in_array($car, $pet_hair_vehicles_arr)) $washtime += 5;

$lifted_vehicles_arr = explode(",", $schedwash->lifted_vehicles);
if (in_array($car, $lifted_vehicles_arr)) $washtime += 5;

$exthandwax_vehicles_arr = explode(",", $schedwash->exthandwax_vehicles);
if (in_array($car, $exthandwax_vehicles_arr)) $washtime += 10;

$extplasticdressing_vehicles_arr = explode(",", $schedwash->extplasticdressing_vehicles);
if (in_array($car, $extplasticdressing_vehicles_arr)) $washtime += 5;

$extclaybar_vehicles_arr = explode(",", $schedwash->extclaybar_vehicles);
if (in_array($car, $extclaybar_vehicles_arr)) $washtime += 15;

$waterspotremove_vehicles_arr = explode(",", $schedwash->waterspotremove_vehicles);
if (in_array($car, $waterspotremove_vehicles_arr)) $washtime += 10;

                   /* --- addons time end ----- */

					}

					//$washtime += 30;




					$hours = floor($washtime / 60);
					$minutes = ($washtime % 60);

                    if($hours < 1){
                      $washtime_str = sprintf('%02d min', $minutes);
                    }
                    else{
                      if($hours == 1) {
                        if($minutes > 0) $washtime_str = sprintf('%d hour %02d min', $hours, $minutes);
                        else $washtime_str = sprintf('%d hour', $hours);
                      }
					  else {
                         if($minutes > 0) $washtime_str = sprintf('%d hours %02d min', $hours, $minutes);
                         else $washtime_str = sprintf('%d hours', $hours);
					  }
                    }




					$declinedids = explode(",",$schedwash->agent_reject_ids);

					if($agent_id){

					   if (!in_array(-$agent_id, $declinedids)) {
							$allwashes[] = array('id'=>$schedwash->id,
								'car_list'=>$schedwash->car_list,
								'customer_id'=>$schedwash->customer_id,
								'customer_name'=>$customerName,
								'package_list'=>$schedwash->package_list,
								'address'=>$schedwash->address,
								'address_type'=>$schedwash->address_type,
								'latitude'=>$schedwash->latitude,
								'longitude'=>$schedwash->longitude,
								'status'=> $schedwash->status,
								'schedule_date'=>$sched_date,
								'schedule_time'=>$sched_time,
'estimate_time' => $washtime,
								'estimate_time_str' => $washtime_str

							);
						}
					}
					else{
						$allwashes[] = array('id'=>$schedwash->id,
							'car_list'=>$schedwash->car_list,
							'customer_id'=>$schedwash->customer_id,
							'customer_name'=>$customerName,
							'package_list'=>$schedwash->package_list,
							'address'=>$schedwash->address,
							'address_type'=>$schedwash->address_type,
							'latitude'=>$schedwash->latitude,
							'longitude'=>$schedwash->longitude,
							'status'=> $schedwash->status,
							'schedule_date'=>$sched_date,
								'schedule_time'=>$sched_time,
'estimate_time' => $washtime,
							 'estimate_time_str' => $washtime_str
						);
					}
                //}

			}

			if(count($allwashes)){
				$response = "all schedule washes";
				$result = "true";

			}
		}


       $json = array(
			'result'=> $result,
			'response'=> $response,
			'schedule_washes' => $allwashes
		);

		echo json_encode($json);
		die();

}



   public function actionschedulewashalert() {

        $allschedwashes = Washingrequests::model()->findAllByAttributes(array('is_scheduled' => 1, 'status' => 0));

         if(count($allschedwashes)){
               foreach($allschedwashes as $schedwash){

               /* --- send schedule wash create alert ------- */

               if(!$schedwash->is_create_schedulewash_push_sent){

                       $allagents = Agents::model()->findAll();

					foreach($allagents as $agent){
						$get_notify = 1;
						$agentallschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$agent->id, 'is_scheduled' => 1, 'status'=> 0));

						foreach($agentallschedwashes as $wash){

							$datediff = round((strtotime($wash['schedule_date']) -  strtotime($schedwash->schedule_date))/(60*60));

							if($datediff < 2){
							$get_notify = 0;
							break;
							}

						}

						if($get_notify){

							$agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent->id."' AND device_token != '' ")->queryAll();

//echo $schedwash->id." - ".$agent->id."<br>";

							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
							$message = $pushmsg[0]['message'];

							foreach($agentdevices as $agdevice){
$device_type = '';
$notify_token = '';
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "schedule";
								$notify_msg = urlencode($message);
//echo $device_type." ".$notify_token."<br><br>";

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}

						}
					}

                    Washingrequests::model()->updateByPk($schedwash->id, array("is_create_schedulewash_push_sent" => 1));
               }

               /* --- send schedule wash create alert end ------- */

                /* --- send reschedule wash alert ------- */

               if((!$schedwash->is_reschedulewash_push_sent) && $schedwash->reschedule_time){

               if($schedwash->agent_id){

                       $agentdet = Agents::model()->findByPk($schedwash->agent_id);


							$agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$schedwash->agent_id."' ")->queryAll();


							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
							$message = $pushmsg[0]['message'];
                            $message = str_replace("[ORDER_ID]","#".$schedwash->id, $message);

							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "schedule";
								$notify_msg = urlencode($message);

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}

               }
               else{
                       $allagents = Agents::model()->findAll();

					foreach($allagents as $agent){
						$get_notify = 1;
						$agentallschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$agent->id, 'is_scheduled' => 1, 'status'=> 0));

						foreach($agentallschedwashes as $wash){

							$datediff = round((strtotime($wash['schedule_date']) - strtotime($schedwash->schedule_date))/(60*60));

							if($datediff < 2){
							$get_notify = 0;
							break;
							}

						}

						if($get_notify){

							$agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent->id."' ")->queryAll();


							$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
							$message = $pushmsg[0]['message'];
                             $message = str_replace("[ORDER_ID]","#".$schedwash->id, $message);
							foreach($agentdevices as $agdevice){
								//$message =  "You have a new scheduled wash request.";
								//echo $agentdetails['mobile_type'];
								$device_type = strtolower($agdevice['device_type']);
								$notify_token = $agdevice['device_token'];
								$alert_type = "schedule";
								$notify_msg = urlencode($message);

								$notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
								file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
								$ch = curl_init();
								curl_setopt($ch,CURLOPT_URL,$notifyurl);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

								if($notify_msg) $notifyresult = curl_exec($ch);
								curl_close($ch);
							}

						}
					}

               }



                    Washingrequests::model()->updateByPk($schedwash->id, array("is_reschedulewash_push_sent" => 1));
               }

               /* --- send reschedule wash alert end ------- */

if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = -1;
if($from_time >= $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}
echo "#".$schedwash->id." ".$min_diff."<br>";
if($min_diff <= 60 && $min_diff >= 10){

               if($schedwash->agent_id){
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$schedwash->agent_id."' ")->queryAll();

                //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
                //$message = $pushmsg[0]['message'];

if($min_diff == 60) $message2 = "You have a scheduled car wash within 60 minutes";

else if($min_diff < 60 && $min_diff >= 55) $message2 = "You have a scheduled car wash within 55 minutes";

else if($min_diff < 55 && $min_diff >= 50) $message2 = "You have a scheduled car wash within 50 minutes";

else if($min_diff < 50 && $min_diff >= 45) $message2 = "You have a scheduled car wash within 45 minutes";

else if($min_diff < 45 && $min_diff >= 40) $message2 = "You have a scheduled car wash within 40 minutes";

else if($min_diff < 40 && $min_diff >= 35) $message2 = "You have a scheduled car wash within 35 minutes";

else if($min_diff < 35 && $min_diff >= 30) $message2 = "You have a scheduled car wash within 30 minutes";

else if($min_diff < 30 && $min_diff >= 25) $message2 = "You have a scheduled car wash within 25 minutes";

else if($min_diff < 25 && $min_diff >= 20) $message2 = "You have a scheduled car wash within 20 minutes";

else if($min_diff < 20 && $min_diff >= 15) $message2 = "You have a scheduled car wash within 15 minutes";

else if($min_diff < 15 && $min_diff >= 10) $message2 = "You have a scheduled car wash within 10 minutes";


                foreach($agentdevices as $agdevice){
                            //$message =  "You have a new scheduled wash request.";
                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($agdevice['device_type']);
                            $notify_token = $agdevice['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($message2);

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);
                }
         }
         }


            }

         }

   }


      public function actionschedulewashtenminalert() {

       $allschedwashes = Washingrequests::model()->findAll(array("condition"=>"agent_id != 0 AND wash_request_position != 'real' AND is_scheduled = 1 AND status = 0"));

         if(count($allschedwashes)){
               foreach($allschedwashes as $schedwash){

if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);
$min_diff = -1;
if($from_time >= $to_time){
$min_diff = round(($from_time - $to_time) / 60,2);
}
//echo "#".$schedwash->id." ".$min_diff."<br>";
if($min_diff < 10){

               if($schedwash->agent_id){
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$schedwash->agent_id."' ")->queryAll();

                //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
                //$message = $pushmsg[0]['message'];

if($min_diff == 9) $message2 = "You have a scheduled car wash within 9 minutes";
else if($min_diff == 8) $message2 = "You have a scheduled car wash within 8 minutes";
else if($min_diff == 7) $message2 = "You have a scheduled car wash within 7 minutes";
else if($min_diff == 6) $message2 = "You have a scheduled car wash within 6 minutes";
else if($min_diff == 5) $message2 = "You have a scheduled car wash within 5 minutes";
else if($min_diff == 4) $message2 = "You have a scheduled car wash within 4 minutes";
else if($min_diff == 3) $message2 = "You have a scheduled car wash within 3 minutes";
else if($min_diff == 2) $message2 = "You have a scheduled car wash within 2 minutes";
else if($min_diff == 1) $message2 = "You have a scheduled car wash within 1 minute";
else if($min_diff <= 0) {

 $message2 = "You missed your appointment. This may affect your ratings.";

  Washingrequests::model()->updateByPk($schedwash->id, array("status" => 6, "washer_late_cancel" => 1));

   $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '".$schedwash->customer_id."' ")->queryAll();

     if(count($clientdevices)){
    foreach($clientdevices as $ctdevice){
                            //$message =  "You have a new scheduled wash request.";
                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode("There was an error communicating with the network. Please contact support (888) 209-5585");

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);
                }
                }

}

                foreach($agentdevices as $agdevice){
                            //$message =  "You have a new scheduled wash request.";
                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($agdevice['device_type']);
                            $notify_token = $agdevice['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($message2);

                            $notifyurl = "https://www.mobilewash.com/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;
                            file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch,CURLOPT_URL,$notifyurl);
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

                            if($notify_msg) $notifyresult = curl_exec($ch);
                            curl_close($ch);
                }
         }
         }


            }

         }

   }


public function actioncustomerwashfeedbackemails() {

$allschedwashes = Washingrequests::model()->findAllByAttributes(array('is_scheduled' => 1, 'status' => 4, 'is_feedback_email_sent' => 0));

 if(count($allschedwashes)){
               foreach($allschedwashes as $schedwash){


$checkfeedbacks = Washingfeedbacks::model()->findAllByAttributes(array('wash_request_id' => $schedwash->id, 'customer_id' => $schedwash->customer_id));

if(count($checkfeedbacks)) continue;

 if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;

               $to_time = strtotime(date('Y-m-d g:i A'));
$from_time = strtotime($scheduledatetime);

$min_diff = 0;

if($to_time >= $from_time){
$min_diff = round(($to_time - $from_time) / 60,2);
}

echo $min_diff." mins #".$schedwash->id."<br>";

if($min_diff >= 180){

$custdetail = Customers::model()->findByPk($schedwash->customer_id);
$cname = explode(" ",$custdetail->customername);


$from = Vargas::Obj()->getAdminEmail();

$message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer Feedback</h2>
<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000".$schedwash->id."</p>
<p style='text-align:center;font-size: 24px;margin-top: 25px;'>Hello ".$cname[0]."</p>
<h2 style='text-align:center;font-size: 24px;line-height: normal;'>How was your experience with our<br>MobileWash Partner?</h2>
<p style='text-align:center;line-height: 24px;margin-top: 25px;'>We would like to make sure that you always have a great experience. Please help us make it the best service possible by letting us know how we did today.</p>
<p style='text-align:center; margin-top: 25px; margin-bottom: 3px;'><a style='background: #30a0ff; color: #fff; padding: 10px; display: block; width: 210px; margin: 0 auto; text-decoration: none; font-weight: bold; font-size: 20px; border-radius: 15px;' href='https://www.mobilewash.com/customer-feedback.php?order_id=".$schedwash->id."'>LEAVE FEEDBACK</a></p>
<p style='text-align:center;font-size: 14px;margin-top: 0;'>Only takes 1-2 minutes</p>";


Vargas::Obj()->SendMail($custdetail->email,$from,$message,"Customer Feedback - Order #0000".$schedwash->id, 'mail-receipt');


Washingrequests::model()->updateByPk($schedwash->id, array('is_feedback_email_sent' => 1));


}


}
}





}


public function actionwashingaddons() {
$response = "addon details";
		$result = "true";
		$addons = array();

$addons['deluxe'][] = array('title' => 'Full Exterior Hand Wax (Liquid form)', 'par_send' => 'exthandwax_vehicles', 'desc' => 'Recommended to protect and keep vehicle shining for weeks', 'price' => number_format('12', 2), 'washtime' => '10');
$addons['deluxe'][] = array('title' => 'Dressing of all Exterior Plastics', 'par_send' => 'extplasticdressing_vehicles', 'desc' => 'Add shine to all exterior plastic surfaces', 'price' => number_format('8', 2), 'washtime' => '5');
$addons['deluxe'][] = array('title' => 'Light Pet Hair Removal', 'par_send' => 'pet_hair_vehicles', 'desc' => 'Vacuum and remove pet hair from seats', 'price' => number_format('5', 2), 'washtime' => '5');
$addons['deluxe'][] = array('title' => 'Lifted Truck', 'par_send' => 'lifted_vehicles', 'desc' => 'If your truck is lifted please select', 'price' => number_format('5', 2), 'washtime' => '5');
$addons['premium'][] = array('title' => 'Full Exterior Clay Bar', 'par_send' => 'extclaybar_vehicles', 'desc' => 'Remove embedded contaminants from exterior surfaces', 'price' => number_format('35', 2), 'washtime' => '15');
$addons['premium'][] = array('title' => 'Water Spot Removal', 'par_send' => 'waterspotremove_vehicles', 'desc' => 'Remove and protect exterior surfaces from stubborn water spots', 'price' => number_format('30', 2), 'washtime' => '10');
$addons['premium'][] = array('title' => 'Light Pet Hair Removal', 'par_send' => 'pet_hair_vehicles', 'desc' => 'Vacuum and remove pet hair from seats', 'price' => number_format('5', 2), 'washtime' => '5');
$addons['premium'][] = array('title' => 'Lifted Truck', 'par_send' => 'lifted_vehicles', 'desc' => 'If your truck is lifted please select', 'price' => number_format('5', 2), 'washtime' => '5');

 $json = array(
			'result'=> $result,
			'response'=> $response,
			'addons' => $addons
		);

		echo json_encode($json);
		die();

}




}