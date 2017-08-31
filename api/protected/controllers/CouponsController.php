<?php
class CouponsController extends Controller{
	public function actionIndex(){
		$this->render('index');
	}


public function actionaddcoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';

        $coupon_name = Yii::app()->request->getParam('coupon_name');
		$coupon_code = Yii::app()->request->getParam('coupon_code');
		$deluxe_amount = Yii::app()->request->getParam('deluxe_amount');
$premium_amount = Yii::app()->request->getParam('premium_amount');
		$discount_unit = Yii::app()->request->getParam('discount_unit');
		$coupon_status = Yii::app()->request->getParam('coupon_status');
$usage_limit = Yii::app()->request->getParam('usage_limit');
        $expire_date = '';
        $expire_date = Yii::app()->request->getParam('expire_date');

		if((isset($coupon_name) && !empty($coupon_name)) &&
			(isset($coupon_code) && !empty($coupon_code)) &&
			(isset($deluxe_amount) && !empty($deluxe_amount)) &&
(isset($premium_amount) && !empty($premium_amount)) &&
			(isset($discount_unit) && !empty($discount_unit)) &&
			(isset($coupon_status) && !empty($coupon_status)))
			 {

             $coupon_check = CouponCodes::model()->findAllByAttributes(array("coupon_code"=>$coupon_code));

             	if(count($coupon_check) > 0){
                   	$result= 'false';
		$response= 'Promo already exists';
                }

                else{
                   $coupondata= array(
					'coupon_name'=> $coupon_name,
					'coupon_code'=> $coupon_code,
					'deluxe_amount'=> $deluxe_amount,
'premium_amount'=> $premium_amount,
					'discount_unit'=> $discount_unit,
					'coupon_status'=> $coupon_status,
'usage_limit'=> $usage_limit,
                    'expire_date'=> $expire_date,
				);

				    $model=new CouponCodes;
				    $model->attributes= $coupondata;
				    if($model->save(false)){
                       $coupon_id = Yii::app()->db->getLastInsertID();
                    }

                    	$result= 'true';
		$response= 'Promo added successfully';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}

    public function actioneditcoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $coupon_id = Yii::app()->request->getParam('id');
        $coupon_name = Yii::app()->request->getParam('coupon_name');
		$coupon_code = Yii::app()->request->getParam('coupon_code');
		$deluxe_amount = Yii::app()->request->getParam('deluxe_amount');
$premium_amount = Yii::app()->request->getParam('premium_amount');
		$discount_unit = Yii::app()->request->getParam('discount_unit');
		$coupon_status = Yii::app()->request->getParam('coupon_status');
$usage_limit = Yii::app()->request->getParam('usage_limit');
        $expire_date = '';
        $expire_date = Yii::app()->request->getParam('expire_date');

		if((isset($coupon_id) && !empty($coupon_id)))
			 {

             $coupon_check = CouponCodes::model()->findByAttributes(array("id"=>$coupon_id));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo doesn't exist";
                }

                else{

                if(!$coupon_name){
                  $coupon_name = $coupon_check->coupon_name;
                }

                if(!$coupon_code){
                  $coupon_code = $coupon_check->coupon_code;
                }

                if(!$deluxe_amount){
                  $deluxe_amount = $coupon_check->deluxe_amount;
                }

if(!$premium_amount){
                  $premium_amount = $coupon_check->premium_amount;
                }

                 if(!$discount_unit){
                  $discount_unit = $coupon_check->discount_unit;
                }

                  if(!$coupon_status){
                  $coupon_status = $coupon_check->coupon_status;
                }


  if(!$usage_limit){
                  $usage_limit = $coupon_check->usage_limit;
                }

                   if(!$expire_date){
                  $expire_date = $coupon_check->expire_date;
                }

                   $coupondata= array(
					'coupon_name'=> $coupon_name,
					'coupon_code'=> $coupon_code,
					'deluxe_amount'=> $deluxe_amount,
'premium_amount'=> $premium_amount,
					'discount_unit'=> $discount_unit,
					'coupon_status'=> $coupon_status,
'usage_limit'=> $usage_limit,
                    'expire_date'=> $expire_date,
				);


				  $resUpdate = Yii::app()->db->createCommand()->update('coupon_codes', $coupondata,"id='".$coupon_id."'");

                    	$result= 'true';
		$response= 'Promo updated successfully';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}

      public function actiongetcouponbyid(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $coupon_id = Yii::app()->request->getParam('id');


		if((isset($coupon_id) && !empty($coupon_id)))
			 {

             $coupon_check = CouponCodes::model()->findByAttributes(array("id"=>$coupon_id));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo doesn't exist";
                }

                else{


                   $coupondata= array(
					'coupon_name'=> $coupon_check->coupon_name,
					'coupon_code'=> $coupon_check->coupon_code,
					'deluxe_amount'=> $coupon_check->deluxe_amount,
'premium_amount'=> $coupon_check->premium_amount,
					'discount_unit'=> $coupon_check->discount_unit,
					'coupon_status'=> $coupon_check->coupon_status,
'usage_limit'=> $coupon_check->usage_limit,
                    'expire_date'=> $coupon_check->expire_date,
				);


                    	$result= 'true';
		$response= 'promo details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'coupon_details'=> $coupondata
		);
		echo json_encode($json);
	}


public function actiongetcouponbycode(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('code');


		if((isset($coupon_code) && !empty($coupon_code)))
			 {

             $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code"=>$coupon_code));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo doesn't exist";
                }

                else{


                   $coupondata= array(
'coupon_id'=> $coupon_check->id,
					'coupon_name'=> $coupon_check->coupon_name,
					'coupon_code'=> $coupon_check->coupon_code,
					'deluxe_amount'=> $coupon_check->deluxe_amount,
'premium_amount'=> $coupon_check->premium_amount,
					'discount_unit'=> $coupon_check->discount_unit,
					'coupon_status'=> $coupon_check->coupon_status,
'usage_limit'=> $coupon_check->usage_limit,
                    'expire_date'=> $coupon_check->expire_date,
				);


                    	$result= 'true';
		$response= 'promo details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'coupon_details'=> $coupondata
		);
		echo json_encode($json);
	}

    public function actiongetallcoupons(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

        $all_coupons = array();

        $result= 'false';
		$response= 'none';

        $coupons_exists = Yii::app()->db->createCommand()->select('*')->from('coupon_codes')->queryAll();

        if(count($coupons_exists)>0){
           $result= 'true';
		    $response= 'all coupons';

            foreach($coupons_exists as $ind=>$coupon){
                 $coupon_usage = CustomerDiscounts::model()->findAllByAttributes(array("promo_code"=>$coupon['coupon_code']));
                $all_coupons[$ind]['id'] = $coupon['id'];
               $all_coupons[$ind]['coupon_name'] = $coupon['coupon_name'];
               $all_coupons[$ind]['coupon_code'] = $coupon['coupon_code'];
               $all_coupons[$ind]['deluxe_amount'] = $coupon['deluxe_amount'];
$all_coupons[$ind]['premium_amount'] = $coupon['premium_amount'];
               $all_coupons[$ind]['discount_unit'] = $coupon['discount_unit'];
               $all_coupons[$ind]['coupon_status'] = $coupon['coupon_status'];
 $all_coupons[$ind]['usage_limit'] = $coupon['usage_limit'];
               $all_coupons[$ind]['expire_date'] = $coupon['expire_date'];
               $all_coupons[$ind]['coupon_usage'] = count($coupon_usage);
            }

        }

        	$json= array(
			'result'=> $result,
			'response'=> $response,
            'coupons'=> $all_coupons
		);
		echo json_encode($json);

    }

    public function actionapplycoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('coupon_code');
         $customer_id = Yii::app()->request->getParam('customer_id');
         $car_ids = Yii::app()->request->getParam('car_ids');
         $pack_names = Yii::app()->request->getParam('pack_names');
         $total = 0;
         $discounted_total = 0;
         $discount = 0;

		if((isset($coupon_code) && !empty($coupon_code)) && (isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($pack_names) && !empty($pack_names)))
			 {

             $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code"=>$coupon_code));
$coupon_usage = CustomerDiscounts::model()->findByAttributes(array("promo_code"=>$coupon_code, "customer_id" => $customer_id));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo code doesn't exist";
                }

 else if($coupon_check->coupon_status != 'enabled'){
                   	$result= 'false';
		           $response= "Sorry, this promo is not available this time.";
                }

  else if(strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d")))){
                   	$result= 'false';
		            $response= "Promo code expired";
                }

 else if(($coupon_check->usage_limit == 'single') && (count($coupon_usage) >= 1)){
                   	$result= 'false';
		            $response= "Sorry, you already used this promo once.";
                }


                else{

                 /* --------- Get total price ------------- */

                 $total_cars = explode(",",$car_ids);
                 $total_packs = explode(",",$pack_names);

$deluxe_found = 0;
$prem_found = 0;
$fee_check = 0;

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
$deluxe_found = 1;

                   }
                   if($total_packs[$carindex] == 'Premium') {
                       $total += $prem_price;
                       $veh_price = $prem_price;
                       $safe_handle_fee = $washing_plan_prem->handling_fee;
$prem_found = 1;
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

               

if($fee_check){
$result= 'false';
		            $response= "Discounts cannot be combined with promotions, coupons and other offers.";
}

else{
$result= 'true';

if($prem_found) $response = number_format($coupon_check->premium_amount, 2);
else $response = number_format($coupon_check->deluxe_amount, 2);

}

                  
         


                    	//$result= 'true';
		//$response= 'coupon applied';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
           
		);
		echo json_encode($json);
	}


public function actionpreapplycoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Please enter promo code';
        $coupon_code = Yii::app()->request->getParam('code');
 $cust_id = Yii::app()->request->getParam('customer_id');
$order_cars = Yii::app()->request->getParam('order_cars');


		if((isset($coupon_code) && !empty($coupon_code)))
			 {

             $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code"=>$coupon_code));
$coupon_usage = CustomerDiscounts::model()->findAllByAttributes(array("promo_code"=>$coupon_code, "customer_id"=>$cust_id));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "promo code doesn't exist";
                }

 else if($coupon_check->coupon_status != 'enabled'){
                   	$result= 'false';
		            $response= "Sorry, this promo is not available this time.";
                }

                 else if(strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d")))){
                   	$result= 'false';
		            $response= "Promo code expired";
                }

 else if(($coupon_check->usage_limit == 'single') && (count($coupon_usage) >= 1)){
                   	$result= 'false';
		            $response= "Sorry, you already used this promo once.";
                }

                else{

$deluxe_found = 0;
$prem_found = 0;
$fee_check = 0;

if($order_cars){
$all_cars = explode("|", $order_cars);
foreach($all_cars as $car){
$car_detail = explode(",", $car);

if($car_detail[2] == 'Deluxe'){
$deluxe_found = 1;
}

if($car_detail[2] == 'Premium'){
$prem_found = 1;
}

if($car_detail[9] || $car_detail[10]){
$fee_check = 1;
}

}

}

if($fee_check){
$result= 'false';
		            $response= "Discounts cannot be combined with promotions, coupons and other offers.";
}

else{
$result= 'true';

if($prem_found) $response= number_format($coupon_check->premium_amount, 2);
else $response= number_format($coupon_check->deluxe_amount, 2);

}



                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response
		);
		echo json_encode($json);
	}


    public function actionpreapplyvipcoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Please enter promo code';
        $coupon_code = Yii::app()->request->getParam('code');
 $cust_id = Yii::app()->request->getParam('customer_id');
 $deluxe_wash_count = Yii::app()->request->getParam('deluxe_wash_count');
 $premium_wash_count = Yii::app()->request->getParam('premium_wash_count');

		if((isset($coupon_code) && !empty($coupon_code)))
			 {

             $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$coupon_code));
             $deluxe_wash_used = $coupon_check->deluxe_wash_used + $deluxe_wash_count;
             $premium_wash_used = $coupon_check->premium_wash_used + $premium_wash_count;
$deluxe_wash_avail = $coupon_check->deluxe_wash_limit - $coupon_check->deluxe_wash_used;
             $premium_wash_avail = $coupon_check->premium_wash_limit - $coupon_check->premium_wash_used;

if($deluxe_wash_avail < 0) $deluxe_wash_avail = 0;
if($premium_wash_avail < 0) $premium_wash_avail = 0;

$total_wash_avail = $deluxe_wash_avail + $premium_wash_avail;

             	if(!count($coupon_check)){
                   	$result= 'false';
		            $response= "Promo code doesn't exists";
                }

                else if($coupon_check->customer_id && ($coupon_check->customer_id != $cust_id)){
                   	$result= 'false';
		            $response= "Sorry, this promo is not available";
                }

                else if(($deluxe_wash_used > $coupon_check->deluxe_wash_limit) && ($premium_wash_used > $coupon_check->premium_wash_limit)){
                   	$result= 'false';
		            $response= "Sorry, you don't have anymore complimentary washes available";
                }

                else if(($deluxe_wash_used > $coupon_check->deluxe_wash_limit)){
                   	$result= 'false';
		            $response= "Sorry, you don't have anymore complimentary Deluxe washes available. If you want to continue using this promo, you have to remove Deluxe cars from your order.";
                }

                else if(($premium_wash_used > $coupon_check->premium_wash_limit)){
                   	$result= 'false';
		            $response= "Sorry, you don't have anymore complimentary Premium washes available. If you want to continue using this promo, you have to remove Premium cars from your order.";
                }

                else{

                 $result = 'true';
                 $response = 'promo applied successfully';

                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
'coupon_package' => $coupon_check->package_name,
'del_wash_avail' => $deluxe_wash_avail,
'prem_wash_avail' => $premium_wash_avail,
'total_wash_avail' => $total_wash_avail
		);
		echo json_encode($json);
	}


   public function actionupdatevipcoupon(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Enter promo code';

		$coupon_code = Yii::app()->request->getParam('coupon_code');
		$customer_id = Yii::app()->request->getParam('customer_id');
$deluxe_wash_count = Yii::app()->request->getParam('deluxe_wash_count');
		$premium_wash_count = Yii::app()->request->getParam('premium_wash_count');


		if((isset($coupon_code) && !empty($coupon_code)))
			 {

             $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$coupon_code));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo doesn't exist";
                }

                else{

                $deluxe_wash_used = 0;
                $premium_wash_used = 0;
                $deluxe_wash_used = $coupon_check->deluxe_wash_used + $deluxe_wash_count;
             $premium_wash_used = $coupon_check->premium_wash_used + $premium_wash_count;

                if(!$customer_id){
                  $customer_id = $coupon_check->customer_id;
                }

if(!$deluxe_wash_count){
                  $deluxe_wash_used = $coupon_check->deluxe_wash_used;
                }

if(!$premium_wash_count){
                  $premium_wash_used = $coupon_check->premium_wash_used;
                }



                   $coupondata= array(

					'customer_id'=> $customer_id,
					'deluxe_wash_used'=> $deluxe_wash_used,
'premium_wash_used'=> $premium_wash_used
				);


				  $resUpdate = Yii::app()->db->createCommand()->update('vip_coupon_codes', $coupondata,"fullcode='".$coupon_code."'");

                    	$result= 'true';
		$response= 'Promo updated successfully';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}


public function actiongetvipcoupondetails(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$result= 'false';
		$response= 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('code');


		if((isset($coupon_code) && !empty($coupon_code)))
			 {

             $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode"=>$coupon_code));

             	if(!count($coupon_check)){
                   	$result= 'false';
		$response= "Promo doesn't exists";
                }

                else{

$dealer_details = Yii::app()->db->createCommand()
                ->select('*')
                ->from('vip_coupon_dealers')
                ->where("dealer_code='".$coupon_check->dealer_code."'", array())
                ->queryAll();


                   $coupondata= array(
'coupon_id'=> $coupon_check->id,
					'package_name'=> $coupon_check->package_name,
					'deluxe_wash_limit'=> $coupon_check->deluxe_wash_limit,
					'premium_wash_limit'=> $coupon_check->premium_wash_limit,
'dealer_name'=> $coupon_check->dealer_name,
'dealer_logo' => $dealer_details[0]['dealer_logo'],
					'package_code'=> $coupon_check->package_code,
					'dealer_code'=> $coupon_check->dealer_code,
'unique_code'=> $coupon_check->unique_code,
                    'fullcode'=> $coupon_check->fullcode,
'customer_id'=> $coupon_check->customer_id,
'deluxe_wash_used'=> $coupon_check->deluxe_wash_used,
'premium_wash_used'=> $coupon_check->premium_wash_used
				);


                    	$result= 'true';
		$response= 'promo details';
                }

		}
		$json= array(
			'result'=> $result,
			'response'=> $response,
            'coupon_details'=> $coupondata
		);
		echo json_encode($json);
	}


}