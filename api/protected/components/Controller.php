<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	/* view particular customer request */

    public function washingkart($wash_request_id, $api_key, $coupon_discount = 0){

       if($api_key != API_KEY){
echo "Invalid api key";
return;
}

        //$wash_request_id = Yii::app()->request->getParam('wash_request_id');
        //$coupon_discount = 0;
        //if(Yii::app()->request->getParam('coupon_discount')) $coupon_discount = Yii::app()->request->getParam('coupon_discount');
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
        $card_no = '';
        $card_exp_mo = '';
        $card_exp_yr = '';
        $cardholder_name = '';
        $card_img = '';
	$total_cars = array();

		if((isset($wash_request_id) && !empty($wash_request_id))){

            $wash_id_check = Washingrequests::model()->findByAttributes(array("id"=>$wash_request_id));


			if(!count($wash_id_check)){
				$result= 'false';
				$response= 'Invalid wash request id';
			}
			else{

                $result= 'true';
				$response= 'kart details';

				$cust_details = Customers::model()->findByAttributes(array("id"=>$wash_id_check->customer_id));



                 /* --------- Get total price ------------- */

				if($wash_id_check->car_list) $total_cars = explode(",",$wash_id_check->car_list);
				$total_packs = explode(",",$wash_id_check->package_list);
				$pet_hair_arr = explode(",",$wash_id_check->pet_hair_vehicles);
				$lifted_vehicles_arr = explode(",",$wash_id_check->lifted_vehicles);
                $exthandwax_vehicles_arr = explode(",",$wash_id_check->exthandwax_vehicles);
                $extplasticdressing_vehicles_arr = explode(",",$wash_id_check->extplasticdressing_vehicles);
                $extclaybar_vehicles_arr = explode(",",$wash_id_check->extclaybar_vehicles);
                $waterspotremove_vehicles_arr = explode(",",$wash_id_check->waterspotremove_vehicles);
                $upholstery_vehicles_arr = explode(",",$wash_id_check->upholstery_vehicles);
                $floormat_vehicles_arr = explode(",",$wash_id_check->floormat_vehicles);
                $surge_vehicles_arr = explode(",",$wash_id_check->surge_price_vehicles);
				$fifth_vehicles_arr = explode(",",$wash_id_check->fifth_wash_vehicles);

                if($wash_id_check->coupon_discount) $coupon_discount = $wash_id_check->coupon_discount;
                if($wash_id_check->coupon_code) $coupon_code = $wash_id_check->coupon_code;

		if(count($total_cars)){
				foreach($total_cars as $carindex=>$car){

					$vehicle_details = Vehicle::model()->findByAttributes(array("id"=>$car));
                    $vehicle_wash_pricing = WashPricingHistory::model()->findByAttributes(array("vehicle_id"=>$car, "wash_request_id" => $wash_request_id, "status" => 0));

					$vehicle_inspect_details = Washinginspections::model()->findByAttributes(array("wash_request_id"=>$wash_request_id, "vehicle_id"=>$car));
					$inspect_img = '';
					if(count($vehicle_inspect_details) > 0){
						$inspect_img = $vehicle_inspect_details->damage_pic;
					}

                    if(count($vehicle_wash_pricing)){
                        $expr_price = $vehicle_wash_pricing->vehicle_price;
                        $delx_price = $vehicle_wash_pricing->vehicle_price;
                        $prem_price = $vehicle_wash_pricing->vehicle_price;
                    }
                    else{
			              $encode_address = urlencode($wash_id_check->address);
				      $cust_zipcode = '';
				      $exp_surge_factor = 0;
				      $del_surge_factor = 0;
				      $prem_surge_factor = 0;

    /* --- Geocode lat long --- */

    $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=".$encode_address."&sensor=true&key=AIzaSyCuokwB88pjRfuNHVc9ktCUqDuuquOMLwA";
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
                if($addrComp->types[0] == 'postal_code'){
                    //Return the zipcode
                    $cust_zipcode = $addrComp->long_name;
		    break;
                }
            }

}


			$surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='".strtolower(date('D', strtotime($wash_id_check->order_for)))."'", array())->queryAll();
			$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1' AND zipcodes != ''", array())->queryAll();
		   
			if(($cust_zipcode) && count($zipcodeprice)){
			   $all_zips = explode(",", $zipcodeprice[0]['zipcodes']);
			   foreach($all_zips as $zip){
			      $zip = trim($zip);
			      if($zip == $cust_zipcode){
				 $exp_surge_factor = $zipcodeprice[0]['express'];
				 $del_surge_factor = $zipcodeprice[0]['deluxe'];
				 $prem_surge_factor = $zipcodeprice[0]['premium'];
			      }
			   }
			}
                        
			$exp_surge_factor += $surgeprice[0]['express'];
			$del_surge_factor += $surgeprice[0]['deluxe'];
			$prem_surge_factor += $surgeprice[0]['premium'];
			
			$washing_plan_express = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Express"));
                        if(count($washing_plan_express)) {
                        $expr_price = $washing_plan_express->price;
			$expr_price = $expr_price + ($expr_price * ($exp_surge_factor / 100));
                         }
                        else {
                        $expr_price = "19.99";
                        }

                        $washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Deluxe"));
                        if(count($washing_plan_deluxe)) {
                        $delx_price = $washing_plan_deluxe->price;
			$delx_price = $delx_price + ($delx_price * ($del_surge_factor / 100));
                         }
                        else {
                        $delx_price = "24.99";
                        }

                        $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type"=>$vehicle_details->vehicle_type, "title"=>"Premium"));
                        if(count($washing_plan_prem)) {
                        $prem_price = $washing_plan_prem->price;
			$prem_price = $prem_price + ($prem_price * ($prem_surge_factor / 100));

                        }
                        else {
                        $prem_price = "59.99";
                        }
                    }

                    if($total_packs[$carindex] == 'Express') {
                       $total += $expr_price;
                       $veh_price = $expr_price;
                       $agent_total += $veh_price * .80;
                       $company_total += $veh_price * .20;
                       if(count($vehicle_wash_pricing)){
                        $safe_handle_fee = $vehicle_wash_pricing->safe_handling;
                        $company_total += $vehicle_wash_pricing->safe_handling;
                       }
                       else{
                        $safe_handle_fee = $washing_plan_express->handling_fee;
                       $company_total += $washing_plan_express->handling_fee;
                       }

					}

                    if($total_packs[$carindex] == 'Deluxe') {
                       $total += $delx_price;
                       $veh_price = $delx_price;
                       $agent_total += $veh_price * .80;
                       $company_total += $veh_price * .20;
                       if(count($vehicle_wash_pricing)){
                        $safe_handle_fee = $vehicle_wash_pricing->safe_handling;
                        $company_total += $vehicle_wash_pricing->safe_handling;
                       }
                       else{
                        $safe_handle_fee = $washing_plan_deluxe->handling_fee;
                       $company_total += $washing_plan_deluxe->handling_fee;
                       }

					}
					if($total_packs[$carindex] == 'Premium') {
                       $total += $prem_price;
                       $veh_price = $prem_price;
                       $agent_total += number_format($veh_price * .75, 2, '.', '');
                       $company_total += number_format($veh_price * .25, 2, '.', '');
                       if(count($vehicle_wash_pricing)){
                          $safe_handle_fee = $vehicle_wash_pricing->safe_handling;
                        $company_total += $vehicle_wash_pricing->safe_handling;
                       }
                       else{
                         $safe_handle_fee = $washing_plan_prem->handling_fee;
						$company_total += $washing_plan_prem->handling_fee;
                       }

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
                    $surge_vehicle = 0;
					$fifth_wash_disc = 0;
                    $bundle_disc = 0;
                    $agent_bundle_disc = 0;
                    $upholstery_vehicle = 0;
                    $floormat_vehicle = 0;
					if (in_array($car, $pet_hair_arr)){
					    if(count($vehicle_wash_pricing)){
                         	$total += $vehicle_wash_pricing->pet_hair;
						$total_pet_lift_fee += $vehicle_wash_pricing->pet_hair;


							$agent_total += $vehicle_wash_pricing->pet_hair * .80;
							$company_total += $vehicle_wash_pricing->pet_hair * .20;


						$pet_hair = $vehicle_wash_pricing->pet_hair;
					    }
                        else{
                          	$total += 10;
						$total_pet_lift_fee += 10;


							$agent_total += 10 * .80;
							$company_total += 10 * .20;


						$pet_hair = 10;
                        }

					}

					if (in_array($car, $lifted_vehicles_arr)){
					    if(count($vehicle_wash_pricing)){
					        $total += $vehicle_wash_pricing->lifted_vehicle;
						$total_pet_lift_fee += $vehicle_wash_pricing->lifted_vehicle;

						$agent_total += $vehicle_wash_pricing->lifted_vehicle * .80;
						$company_total += $vehicle_wash_pricing->lifted_vehicle * .20;

						$lift_vehicle = $vehicle_wash_pricing->lifted_vehicle;
					    }
                        else{
                          $total += 10;
						$total_pet_lift_fee += 10;

						$agent_total += 10 * .80;
						$company_total += 10 * .20;

						$lift_vehicle = 10;
                        }

					}

                    if (in_array($car, $exthandwax_vehicles_arr)){
                    if(count($vehicle_wash_pricing)){
                       $total += $vehicle_wash_pricing->exthandwax_addon;

						$agent_total += $vehicle_wash_pricing->exthandwax_addon * .80;
						$company_total += $vehicle_wash_pricing->exthandwax_addon * .20;

						$exthandwax_vehicle = $vehicle_wash_pricing->exthandwax_addon;
                    }
                    else{
                     $total += 12;

						$agent_total += 12 * .80;
						$company_total += 12 * .20;

						$exthandwax_vehicle = 12;
                    }

					}

                    if (in_array($car, $extplasticdressing_vehicles_arr)){
                    if(count($vehicle_wash_pricing)){
                      $total += $vehicle_wash_pricing->extplasticdressing_addon;

						$agent_total += $vehicle_wash_pricing->extplasticdressing_addon * .80;
						$company_total += $vehicle_wash_pricing->extplasticdressing_addon * .20;

						$extplasticdressing_vehicle = $vehicle_wash_pricing->extplasticdressing_addon;
                    }
                    else{
                      $total += 8;

						$agent_total += 8 * .80;
						$company_total += 8 * .20;

						$extplasticdressing_vehicle = 8;
                    }

					}


if (in_array($car, $extclaybar_vehicles_arr)){

if(count($vehicle_wash_pricing)){
    $clay_price = $vehicle_wash_pricing->extclaybar_addon;

						$total += $clay_price;

						$agent_total += $clay_price * .80;
						$company_total += $clay_price * .20;

						$extclaybar_vehicle = $clay_price;
}
else{
  $clay_price = 40;
    if($vehicle_details->vehicle_type == 'S') $clay_price = 40;
if($vehicle_details->vehicle_type == 'M') $clay_price = 42.50;
if($vehicle_details->vehicle_type == 'L') $clay_price = 45;
if($vehicle_details->vehicle_type == 'E') $clay_price = 45;

						$total += $clay_price;

						$agent_total += $clay_price * .80;
						$company_total += $clay_price * .20;

						$extclaybar_vehicle = $clay_price;
}

					}


if (in_array($car, $waterspotremove_vehicles_arr)){
    if(count($vehicle_wash_pricing)){
     $total += $vehicle_wash_pricing->waterspotremove_addon;

						$agent_total += $vehicle_wash_pricing->waterspotremove_addon * .80;
						$company_total += $vehicle_wash_pricing->waterspotremove_addon * .20;


						$waterspotremove_vehicle = $vehicle_wash_pricing->waterspotremove_addon;
    }
    else{
       $total += 30;

						$agent_total += 30 * .80;
						$company_total += 30 * .20;


						$waterspotremove_vehicle = 30;
    }

					}


                    if (in_array($car, $upholstery_vehicles_arr)){
    if(count($vehicle_wash_pricing)){
     $total += $vehicle_wash_pricing->upholstery_addon;

						$agent_total += $vehicle_wash_pricing->upholstery_addon * .80;
						$company_total += $vehicle_wash_pricing->upholstery_addon * .20;


						$upholstery_vehicle = $vehicle_wash_pricing->upholstery_addon;
    }
    else{
       $total += 20;

						$agent_total += 20 * .80;
						$company_total += 20 * .20;


						$upholstery_vehicle = 20;
    }

					}

                    if (in_array($car, $floormat_vehicles_arr)){

if(count($vehicle_wash_pricing)){
    $floormat_price = $vehicle_wash_pricing->floormat_addon;

						$total += $floormat_price;

						$agent_total += $floormat_price * .80;
						$company_total += $floormat_price * .20;

						$floormat_vehicle = $floormat_price;
}
else{
  $floormat_price = 10;
    if($vehicle_details->vehicle_type == 'S') $floormat_price = 10;
if($vehicle_details->vehicle_type == 'M') $floormat_price = 12.50;
if($vehicle_details->vehicle_type == 'L') $floormat_price = 15;
if($vehicle_details->vehicle_type == 'E') $floormat_price = 15;

						$total += $floormat_price;

						$agent_total += $floormat_price * .80;
						$company_total += $floormat_price * .20;

						$floormat_vehicle = $floormat_price;
}

					}

					if (in_array($car, $surge_vehicles_arr)){
						$total += 5;
						//total_pet_lift_fee += 5;


						$agent_total += 5 * .80;
						$company_total += 5 * .20;


						$surge_vehicle = 5;
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
						$veh_price_agent = number_format($veh_price * .75, 2, '.', '');
					}
					else{
						$veh_price_agent = number_format($veh_price * .8, 2, '.', '');
					}

						/* ------------ first wash discount ------- */
$first_wash_discount = 0;
/*if($carindex == 0){
				if($wash_id_check->first_wash_discount){
				   $first_wash_discount = $wash_id_check->first_wash_discount;


				}
				else{
				   if((!$cust_details->is_first_wash) && (!$cust_details->fifth_wash_points)) {
				       $car_packs_arra = explode(",", $wash_id_check->package_list);
                      if($car_packs_arra[0] == 'Premium') $first_disc = 10;
else $first_disc = 5;
$first_wash_discount = $first_disc;

				   }
				}
}*/


				/* ------------ first wash discount end ------- */

if((!$fifth_wash_disc) && (!$first_wash_discount) && (count($total_cars) > 1)) {
  if(count($vehicle_wash_pricing)) $bundle_disc = $vehicle_wash_pricing->bundle_disc;
  else $bundle_disc = 1;
}
if(count($total_cars) > 1) {
    //if(count($vehicle_wash_pricing)) $agent_bundle_disc = $vehicle_wash_pricing->bundle_disc*.8;
    $agent_bundle_disc = 1*.8;
    $agent_total -= $agent_bundle_disc;
}

if((count($total_cars) > 1) && ($carindex==0) && ($wash_id_check->coupon_discount > 0)) $bundle_disc = 0;


					$vehicles[] = array(
						'id'=>$vehicle_details->id,
						'status'=>$vehicle_details->status,
						'vehicle_no'=>$vehicle_details->vehicle_no,
						'brand_name'=>$vehicle_details->brand_name,
						'model_name'=>$vehicle_details->model_name,
						'vehicle_cat'=>$vehicle_details->vehicle_build,
						'vehicle_image'=>$vehicle_details->vehicle_image,
						'vehicle_inspect_image'=>$inspect_img,
                        'eco_friendly'=>$vehicle_inspect_details->eco_friendly,
						'vehicle_inspect_image_temp'=>$vehicle_details->damage_pic,
						'vehicle_type'=>$vehicle_details->vehicle_type,
						'vehicle_washing_package' => $total_packs[$carindex],
						'vehicle_washing_price'=> number_format($veh_price, 2, '.', ''),
						'vehicle_washing_price_agent'=> $veh_price_agent,
						'safe_handling_fee' => number_format($safe_handle_fee, 2, '.', ''),
						'pet_hair_fee' => number_format($pet_hair, 2, '.', ''),
'pet_hair_fee_agent' => number_format($pet_hair * .8, 2, '.', ''),
						'lifted_vehicle_fee' => number_format($lift_vehicle, 2, '.', ''),
'lifted_vehicle_fee_agent' => number_format($lift_vehicle * .8, 2, '.', ''),
'exthandwax_vehicle_fee' => number_format($exthandwax_vehicle, 2, '.', ''),
'exthandwax_vehicle_fee_agent' => number_format($exthandwax_vehicle * .8, 2, '.', ''),
'extplasticdressing_vehicle_fee' => number_format($extplasticdressing_vehicle, 2, '.', ''),
'extplasticdressing_vehicle_fee_agent' => number_format($extplasticdressing_vehicle * .8, 2, '.', ''),
'extclaybar_vehicle_fee' => number_format($extclaybar_vehicle, 2, '.', ''),
'extclaybar_vehicle_fee_agent' => number_format($extclaybar_vehicle * .8, 2, '.', ''),
'waterspotremove_vehicle_fee' => number_format($waterspotremove_vehicle, 2, '.', ''),
'waterspotremove_vehicle_fee_agent' => number_format($waterspotremove_vehicle * .8, 2, '.', ''),
'upholstery_vehicle_fee' => number_format($upholstery_vehicle, 2, '.', ''),
'upholstery_vehicle_fee_agent' => number_format($upholstery_vehicle * .8, 2, '.', ''),
'floormat_vehicle_fee' => number_format($floormat_vehicle, 2, '.', ''),
'floormat_vehicle_fee_agent' => number_format($floormat_vehicle * .8, 2, '.', ''),
'surge_vehicle_fee' => number_format($surge_vehicle, 2, '.', ''),
'surge_vehicle_fee_agent' => number_format($surge_vehicle * .8, 2, '.', ''),
'bundle_discount' => number_format($bundle_disc, 2, '.', ''),
'bundle_discount_agent' => number_format($agent_bundle_disc, 2, '.', ''),
						'fifth_wash_discount' => number_format($fifth_wash_disc, 2, '.', '')
					);



				}
			}

				/* --------- Get total price end ------------- */

/* ---- tip ---- */

if($wash_id_check->tip_amount > 0) {
$tip_amount = $wash_id_check->tip_amount;
$total =  $total + $tip_amount;
 $company_total =  $company_total + ($tip_amount * .20);
 $agent_total =  $agent_total + ($tip_amount * .80);
}

/* ----- tip end ---- */

/* ---- wash now fee ---- */

if($wash_id_check->wash_now_fee > 0) {
$wash_now_fee = $wash_id_check->wash_now_fee;
$total =  $total + $wash_now_fee;
 $company_total =  $company_total + ($wash_now_fee * .20);
 $agent_total =  $agent_total + ($wash_now_fee * .80);
}

/* ----- wash now fee end ---- */

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

			/*	if($wash_id_check->first_wash_discount){
				   $first_wash_discount = $wash_id_check->first_wash_discount;
				   if($first_wash_discount && $bundle_discount) $bundle_discount -= 1;

				if(count($total_cars) > 1) $company_total -= $first_wash_discount - .8;
				else $company_total -= $first_wash_discount;

				}
				else{
				   if((!$cust_details->is_first_wash) && (!$cust_details->fifth_wash_points)) {
				       $car_packs_arra = explode(",", $wash_id_check->package_list);
                      if($car_packs_arra[0] == 'Premium') $first_disc = 10;
else $first_disc = 5;
$first_wash_discount = $first_disc;

if($first_wash_discount && $bundle_discount) $bundle_discount -= 1;

				if(count($total_cars) > 1) $company_total -= $first_disc - .8;
				else $company_total -= $first_disc;
				   }
				} */


				/* ------------ first wash discount end ------- */

				if(($wash_id_check->coupon_discount > 0) && $bundle_discount) $bundle_discount -= 1;

				/* ---- net price ------ */

				$net_total = $total - $bundle_discount -  $fifth_wash_discount - $first_wash_discount - $coupon_discount;

				/* ---- net price end ------ */

				/* ----------- calculate agent and company total after discounts ----------- */

				$company_total -= $bundle_discount * .2;


				if($wash_id_check->fifth_wash_discount){

						if(count($total_cars) > 1) $company_total -= 5-.8;
						else $company_total -= 5;
				}


				if($coupon_discount > 0){
					//$agent_total -= count($total_cars) * .8;
					if(count($total_cars) > 1) $company_total -= $coupon_discount-.8;
					else $company_total -= $coupon_discount;
				}


				$agent_total = round($agent_total, 2);
				$company_total = round($company_total, 2);

if(!count($total_cars)){
   $total = 0;
   $net_total = 0;
   $agent_total = 0;
   $company_total = 0;  
}

				//$company_total = round(($net_total - count($total_cars)) * .2, 2);
				//$company_total += count($total_cars);
				//echo $company_total;
				//echo "<br>".$total_pet_lift_fee;


				/* ----------- calculate agent and company total after discounts ----------- */

                if($wash_id_check->transaction_id){
                    if($wash_id_check->wash_request_position == 'real') $Bresult = Yii::app()->braintree->getTransactionById_real($wash_id_check->transaction_id);
else $Bresult = Yii::app()->braintree->getTransactionById($wash_id_check->transaction_id);

                    if($Bresult['transaction_id']){
                       $card_no = $Bresult['card_no'];
                       $card_exp_mo = $Bresult['exp_mo'];
                        $card_exp_yr = $Bresult['exp_yr'];
                         $cardholder_name = $Bresult['cardholder_name'];
                         $card_img = $Bresult['cardtype_img'];
                    }
                }

			}
		}

		$json = array(
            'result'=> $result,
            'response'=> $response,
            'id'=> $wash_id_check->id,
            'order_date'=> $wash_id_check->created_date,
            'address'=> $wash_id_check->address,
	    'city'=> $wash_id_check->city,
            'address_type'=> $wash_id_check->address_type,
            'latitude'=> $wash_id_check->latitude,
            'longitude'=> $wash_id_check->longitude,
	    'car_list'=> $wash_id_check->car_list,
            'customer_id'=> $wash_id_check->customer_id,
            'agent_id'=> $wash_id_check->agent_id,
            'washer_penalty_fee'=> $wash_id_check->washer_penalty_fee,
            'company_discount'=> $wash_id_check->company_discount,
			'is_scheduled'=> $wash_id_check->is_scheduled,
			'schedule_date'=> $wash_id_check->schedule_date,
			'schedule_time'=> $wash_id_check->schedule_time,
			'reschedule_date'=> $wash_id_check->reschedule_date,
			'reschedule_time'=> $wash_id_check->reschedule_time,
            'total_price'=> number_format($total, 2, '.', ''),
            'net_price'=> number_format($net_total, 2, '.', ''),
            'company_total' => number_format($company_total, 2, '.', ''),
            'agent_total' => number_format($agent_total, 2, '.', ''),
            'tip_amount' => number_format($tip_amount, 2, '.', ''),
            'bundle_discount' => number_format($bundle_discount, 2, '.', ''),
            'fifth_wash_discount' => number_format($fifth_wash_discount, 2, '.', ''),
            'first_wash_discount' => number_format($first_wash_discount, 2, '.', ''),
            'coupon_discount' => number_format($coupon_discount, 2, '.', ''),
            'coupon_code' => $coupon_code,
            'promo_wash_count' => $promo_wash_count,
	       'notes' => $wash_id_check->notes,
			'customer_wash_points' => $wash_id_check->customer_wash_points,
			'per_car_wash_points' => $wash_id_check->per_car_wash_points,
			'cancel_fee' => number_format($wash_id_check->cancel_fee, 2, '.', ''),
			'washer_cancel_fee' => number_format($wash_id_check->washer_cancel_fee, 2, '.', ''),
			'status' => $wash_id_check->status,
			'transaction_id' => $wash_id_check->transaction_id,
            'is_flagged' => $wash_id_check->is_flagged,
            'meet_washer_outside' => $wash_id_check->meet_washer_outside,
            'card_no' => $card_no,
            'card_exp_mo' => $card_exp_mo,
            'card_exp_yr' => $card_exp_yr,
            'cardholder_name' => $cardholder_name,
            'card_img' => $card_img,
            'washer_late_cancel' => $wash_id_check->washer_late_cancel,
            'washer_payment_status' => $wash_id_check->washer_payment_status,
            'total_schedule_rejected' => $wash_id_check->total_schedule_rejected,
            'wash_now_fee' => $wash_id_check->wash_now_fee,
	    'payment_type' => $wash_id_check->payment_type,
	    'admin_submit_for_settle' => $wash_id_check->admin_submit_for_settle,
            'vehicles' => $vehicles
        );

        return json_encode($json);

    }

}