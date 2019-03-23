<?php

class CouponsController extends Controller {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionaddcoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';

        $coupon_name = Yii::app()->request->getParam('coupon_name');
        $coupon_code = Yii::app()->request->getParam('coupon_code');
        $express_amount = Yii::app()->request->getParam('express_amount');
        $deluxe_amount = Yii::app()->request->getParam('deluxe_amount');
        $premium_amount = Yii::app()->request->getParam('premium_amount');
        $discount_unit = Yii::app()->request->getParam('discount_unit');
        $coupon_status = Yii::app()->request->getParam('coupon_status');
        $usage_limit = Yii::app()->request->getParam('usage_limit');
        $expire_date = '';
        $expire_date = Yii::app()->request->getParam('expire_date');

        if ((isset($coupon_name) && !empty($coupon_name)) &&
                (isset($coupon_code) && !empty($coupon_code)) &&
                (is_numeric($express_amount)) &&
                (is_numeric($deluxe_amount)) &&
                (is_numeric($premium_amount)) &&
                (isset($discount_unit) && !empty($discount_unit)) &&
                (isset($coupon_status) && !empty($coupon_status))) {

            $coupon_check = CouponCodes::model()->findAllByAttributes(array("coupon_code" => $coupon_code));

            if (count($coupon_check) > 0) {
                $result = 'false';
                $response = 'Promo already exists';
            } else {
                $coupondata = array(
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'express_amount' => $express_amount,
                    'deluxe_amount' => $deluxe_amount,
                    'premium_amount' => $premium_amount,
                    'discount_unit' => $discount_unit,
                    'coupon_status' => $coupon_status,
                    'usage_limit' => $usage_limit,
                    'expire_date' => $expire_date,
                );

                $model = new CouponCodes;
                $model->attributes = $coupondata;
                if ($model->save(false)) {
                    $coupon_id = Yii::app()->db->getLastInsertID();
                }

                $result = 'true';
                $response = 'Promo added successfully';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actioneditcoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';
        $coupon_id = Yii::app()->request->getParam('id');
        $coupon_name = Yii::app()->request->getParam('coupon_name');
        $coupon_code = Yii::app()->request->getParam('coupon_code');
        $express_amount = Yii::app()->request->getParam('express_amount');
        $deluxe_amount = Yii::app()->request->getParam('deluxe_amount');
        $premium_amount = Yii::app()->request->getParam('premium_amount');
        $discount_unit = Yii::app()->request->getParam('discount_unit');
        $coupon_status = Yii::app()->request->getParam('coupon_status');
        $usage_limit = Yii::app()->request->getParam('usage_limit');
        $expire_date = '';
        $expire_date = Yii::app()->request->getParam('expire_date');

        if ((isset($coupon_id) && !empty($coupon_id))) {

            $coupon_check = CouponCodes::model()->findByAttributes(array("id" => $coupon_id));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo doesn't exist";
            } else {

                if (!$coupon_name) {
                    $coupon_name = $coupon_check->coupon_name;
                }

                if (!$coupon_code) {
                    $coupon_code = $coupon_check->coupon_code;
                }

                if (!is_numeric($express_amount)) {
                    $express_amount = $coupon_check->express_amount;
                }

                if (!is_numeric($deluxe_amount)) {
                    $deluxe_amount = $coupon_check->deluxe_amount;
                }

                if (!is_numeric($premium_amount)) {
                    $premium_amount = $coupon_check->premium_amount;
                }

                if (!$discount_unit) {
                    $discount_unit = $coupon_check->discount_unit;
                }

                if (!$coupon_status) {
                    $coupon_status = $coupon_check->coupon_status;
                }


                if (!$usage_limit) {
                    $usage_limit = $coupon_check->usage_limit;
                }

                if (!$expire_date) {
                    $expire_date = $coupon_check->expire_date;
                }

                $coupondata = array(
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'express_amount' => $express_amount,
                    'deluxe_amount' => $deluxe_amount,
                    'premium_amount' => $premium_amount,
                    'discount_unit' => $discount_unit,
                    'coupon_status' => $coupon_status,
                    'usage_limit' => $usage_limit,
                    'expire_date' => $expire_date,
                );


                $resUpdate = Yii::app()->db->createCommand()->update('coupon_codes', $coupondata, 'id=:id', array(':id' => $coupon_id));

                $result = 'true';
                $response = 'Promo updated successfully';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actiongetcouponbyid() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';
        $coupon_id = Yii::app()->request->getParam('id');


        if ((isset($coupon_id) && !empty($coupon_id))) {

            $coupon_check = CouponCodes::model()->findByAttributes(array("id" => $coupon_id));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo doesn't exist";
            } else {


                $coupondata = array(
                    'coupon_name' => $coupon_check->coupon_name,
                    'coupon_code' => $coupon_check->coupon_code,
                    'express_amount' => $coupon_check->express_amount,
                    'deluxe_amount' => $coupon_check->deluxe_amount,
                    'premium_amount' => $coupon_check->premium_amount,
                    'discount_unit' => $coupon_check->discount_unit,
                    'coupon_status' => $coupon_check->coupon_status,
                    'usage_limit' => $coupon_check->usage_limit,
                    'expire_date' => $coupon_check->expire_date,
                );


                $result = 'true';
                $response = 'promo details';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'coupon_details' => $coupondata
        );
        echo json_encode($json);
    }

    public function actiongetcouponbycode() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('code');


        if ((isset($coupon_code) && !empty($coupon_code))) {

            $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $coupon_code));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo doesn't exist";
            } else {


                $coupondata = array(
                    'coupon_id' => $coupon_check->id,
                    'coupon_name' => $coupon_check->coupon_name,
                    'coupon_code' => $coupon_check->coupon_code,
                    'deluxe_amount' => $coupon_check->deluxe_amount,
                    'premium_amount' => $coupon_check->premium_amount,
                    'discount_unit' => $coupon_check->discount_unit,
                    'coupon_status' => $coupon_check->coupon_status,
                    'usage_limit' => $coupon_check->usage_limit,
                    'expire_date' => $coupon_check->expire_date,
                );


                $result = 'true';
                $response = 'promo details';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'coupon_details' => $coupondata
        );
        echo json_encode($json);
    }

    public function actiongetallcoupons() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $all_coupons = array();

        $result = 'false';
        $response = 'none';

        $coupons_exists = Yii::app()->db->createCommand()->select('*')->from('coupon_codes')->order('id DESC')->queryAll();

        if (count($coupons_exists) > 0) {
            $result = 'true';
            $response = 'all coupons';

            foreach ($coupons_exists as $ind => $coupon) {
                $coupon_usage = CustomerDiscounts::model()->findAllByAttributes(array("promo_code" => $coupon['coupon_code']));
                $all_coupons[$ind]['id'] = $coupon['id'];
                $all_coupons[$ind]['coupon_name'] = $coupon['coupon_name'];
                $all_coupons[$ind]['coupon_code'] = $coupon['coupon_code'];
                $all_coupons[$ind]['express_amount'] = $coupon['express_amount'];
                $all_coupons[$ind]['deluxe_amount'] = $coupon['deluxe_amount'];
                $all_coupons[$ind]['premium_amount'] = $coupon['premium_amount'];
                $all_coupons[$ind]['discount_unit'] = $coupon['discount_unit'];
                $all_coupons[$ind]['coupon_status'] = $coupon['coupon_status'];
                $all_coupons[$ind]['usage_limit'] = $coupon['usage_limit'];
                $all_coupons[$ind]['expire_date'] = $coupon['expire_date'];
                $all_coupons[$ind]['coupon_usage'] = count($coupon_usage);
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'coupons' => $all_coupons
        );
        echo json_encode($json);
    }

    public function actionuserdpromocode() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $coupon_code = Yii::app()->request->getParam('promocode');

        $result = 'false';
        $response = 'none';

        $coupon_usage = $coupons_user = array();
        $coupon_usage = Yii::app()->db->createCommand("SELECT wr.* FROM customer_discounts cd INNER JOIN washing_requests wr ON cd.wash_request_id = wr.id WHERE cd.promo_code = :promocode")->bindValue(':promocode', $coupon_code, PDO::PARAM_STR)->queryAll();

        if (count($coupon_usage) > 0) {
            $result = 'true';
            $response = 'all coupons';
            foreach ($coupon_usage as $key => $wrequest) {
                if ($wrequest['is_scheduled']) {
                    if ($wrequest['reschedule_time'])
                        $scheduledatetime = $wrequest['reschedule_date'] . " " . $wrequest['reschedule_time'];
                    else
                        $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];

                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;

                    $min_diff = round(($from_time - $to_time) / 60, 2);

                    //$min_diff = abs($min_diff);
                }
                else {
                    if ($wrequest['status'] >= 0 && $wrequest['status'] < 4)
                        $min_diff = 0;
                    else {

                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($wrequest['order_for']);
                        $min_diff = 0;

                        $min_diff = round(($from_time - $to_time) / 60, 2);
                    }
                }

                $cust_details = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id" => $wrequest['agent_id']));
                $cars = explode(",", $wrequest['car_list']);
                $packs = explode(",", $wrequest['package_list']);
                $vehicles = array();
                foreach ($cars as $ind => $car) {
                    $car_details = Vehicle::model()->findByAttributes(array("id" => $car));

                    $veh_addons = '';

                    $pet_hair_vehicles_arr = explode(",", $wrequest['pet_hair_vehicles']);
                    if (in_array($car, $pet_hair_vehicles_arr))
                        $veh_addons .= 'Extra Cleaning, ';

                    $lifted_vehicles_arr = explode(",", $wrequest['lifted_vehicles']);
                    if (in_array($car, $lifted_vehicles_arr))
                        $veh_addons .= 'Lifted Truck, ';

                    $exthandwax_addon_arr = explode(",", $wrequest['exthandwax_vehicles']);
                    if (in_array($car, $exthandwax_addon_arr))
                        $veh_addons .= 'Liquid Hand Wax, ';

                    $extplasticdressing_addon_arr = explode(",", $wrequest['extplasticdressing_vehicles']);
                    if (in_array($car, $extplasticdressing_addon_arr))
                        $veh_addons .= 'Exterior Plastic Dressing, ';

                    $extclaybar_addon_arr = explode(",", $wrequest['extclaybar_vehicles']);
                    if (in_array($car, $extclaybar_addon_arr))
                        $veh_addons .= 'Clay Bar & Paste Wax, ';

                    $waterspotremove_addon_arr = explode(",", $wrequest['waterspotremove_vehicles']);
                    if (in_array($car, $waterspotremove_addon_arr))
                        $veh_addons .= 'Water Spot Removal, ';

                    $upholstery_addon_arr = explode(",", $wrequest['upholstery_vehicles']);
                    if (in_array($car, $upholstery_addon_arr))
                        $veh_addons .= 'Upholstery Conditioning, ';

                    $floormat_addon_arr = explode(",", $wrequest['floormat_vehicles']);
                    if (in_array($car, $floormat_addon_arr))
                        $veh_addons .= 'Floor Mat Cleaning, ';

                    $veh_addons = rtrim($veh_addons, ", ");

                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind], 'addons' => $veh_addons);
                }

                if (($cust_details->first_name != '') && ($cust_details->last_name != '')) {
                    $customername = '';
                    $cust_name = explode(" ", trim($cust_details->last_name));
                    $customername = $cust_details->first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
                } else {
                    $customername = '';
                    $cust_name = explode(" ", trim($cust_details->customername));
                    if (count($cust_name > 1))
                        $customername = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
                    else
                        $customername = $cust_name[0];
                }

                $customername = strtolower($customername);
                $customername = ucwords($customername);

                $agent_info = array();
                if (count($agent_details)) {
                    $agent_info = array('agent_id' => $wrequest['agent_id'], 'real_washer_id' => $agent_details->real_washer_id, 'agent_name' => $agent_details->first_name . " " . $agent_details->last_name, 'agent_phoneno' => $agent_details->phone_number, 'agent_email' => $agent_details->email);
                }
                $payment_status = '';
                $submerchant_id = '';
                $transaction_status = '';

                if ($wrequest['failed_transaction_id']) {
                    $payment_status = 'Declined';
                } else {
                    if ($wrequest['transaction_id']) {

                        if ($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held') {
                            $payment_status = 'Processed';
                        } else if ($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released') {
                            $payment_status = 'Released';
                        }


                        /* if($cust_details->client_position == 'real') $payresult = Yii::app()->braintree->getTransactionById_real($wrequest['transaction_id']);
                          else $payresult = Yii::app()->braintree->getTransactionById($wrequest['transaction_id']);
                          if($payresult['success'] == 1) {
                          //$submerchant_id = $payresult['merchant_id'];
                          $transaction_status = $payresult['status'];
                          } */
                    }
                }

//$kartapiresult = $this->washingkart($wrequest['id'], API_KEY, 0, AES256CBC_API_PASS);
//$kartdata = json_decode($kartapiresult);

                if ($wrequest['is_flagged'] == 1)
                    $payment_status = 'Check Fraud';

                $coupons_user[] = array('id' => $wrequest['id'],
                    'customer_id' => $wrequest['customer_id'],
                    'customer_name' => $cust_details->first_name . " " . $cust_details->last_name,
                    'customer_email' => $cust_details->email,
                    'customer_phoneno' => $cust_details->contact_number,
                    'agent_details' => $agent_info,
                    'car_list' => $wrequest['car_list'],
                    'package_list' => $wrequest['package_list'],
                    'vehicles' => $vehicles,
                    'address' => $wrequest['address'],
                    'address_type' => $wrequest['address_type'],
                    'latitude' => $wrequest['latitude'],
                    'longitude' => $wrequest['longitude'],
                    'payment_type' => $wrequest['payment_type'],
                    'nonce' => $wrequest['nonce'],
                    'estimate_time' => $wrequest['estimate_time'],
                    'status' => $wrequest['status'],
                    'is_scheduled' => $wrequest['is_scheduled'],
                    'schedule_date' => date('Y-m-d', strtotime($wrequest['schedule_date'])),
                    'schedule_time' => date('h:i A', strtotime($wrequest['schedule_time'])),
                    'reschedule_date' => $resched_date,
                    'checklist' => $wrequest['checklist'],
                    'reschedule_time' => $resched_time,
                    'created_date' => date('Y-m-d', strtotime($wrequest['created_date'])) . " " . date('h:i A', strtotime($wrequest['created_date'])),
                    'order_for' => date('Y-m-d h:i A', strtotime($wrequest['order_for'])),
                    'transaction_id' => $wrequest['transaction_id'],
                    'failed_transaction_id' => $wrequest['failed_transaction_id'],
                    'transaction_status' => $transaction_status,
                    'submerchant_id' => $submerchant_id,
                    'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
                    'schedule_total' => $wrequest['schedule_total'],
                    'schedule_company_total' => $wrequest['schedule_company_total'],
                    'schedule_agent_total' => $wrequest['schedule_agent_total'],
                    'wash_request_position' => $wrequest['wash_request_position'],
                    'net_price' => $wrequest['net_price'],
                    'payment_status' => $payment_status,
                    'min_diff' => $min_diff
                );
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'coupons' => $coupons_user
        );
        echo json_encode($json);
    }

    public function actionapplycoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('coupon_code');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $car_ids = Yii::app()->request->getParam('car_ids');
        $pack_names = Yii::app()->request->getParam('pack_names');
        $total = 0;
        $discounted_total = 0;
        $discount = 0;

        if ((isset($coupon_code) && !empty($coupon_code)) && (isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($pack_names) && !empty($pack_names))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $coupon_code));
            $coupon_usage = CustomerDiscounts::model()->findByAttributes(array("promo_code" => $coupon_code, "customer_id" => $customer_id));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo code doesn't exist";
            } else if ($coupon_check->coupon_status != 'enabled') {
                $result = 'false';
                $response = "Sorry, this promo code is not available at this time.";
            } else if (strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d")))) {
                $result = 'false';
                $response = "Promo code expired";
            } else if (($coupon_check->usage_limit == 'single') && (count($coupon_usage) >= 1)) {
                $result = 'false';
                $response = "Sorry, you already used this promo once.";
            } else {

                /* --------- Get total price ------------- */

                $total_cars = explode(",", $car_ids);
                $total_packs = explode(",", $pack_names);

                $express_found = 0;
                $deluxe_found = 0;
                $prem_found = 0;
                $fee_check = 0;

                foreach ($total_cars as $carindex => $car) {

                    $vehicle_details = Vehicle::model()->findByAttributes(array("id" => $car));

                    $washing_plan_express = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_details->vehicle_type, "title" => "Express"));
                    if (count($washing_plan_express))
                        $expr_price = $washing_plan_express->price;
                    else
                        $expr_price = "19.99";

                    $washing_plan_deluxe = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_details->vehicle_type, "title" => "Deluxe"));
                    if (count($washing_plan_deluxe))
                        $delx_price = $washing_plan_deluxe->price;
                    else
                        $delx_price = "24.99";

                    $washing_plan_prem = Washingplans::model()->findByAttributes(array("vehicle_type" => $vehicle_details->vehicle_type, "title" => "Premium"));
                    if (count($washing_plan_prem))
                        $prem_price = $washing_plan_prem->price;
                    else
                        $prem_price = "59.99";

                    if ($total_packs[$carindex] == 'Express') {
                        $total += $expr_price;
                        $veh_price = $expr_price;
                        $safe_handle_fee = $washing_plan_express->handling_fee;
                        $express_found = 1;
                    }

                    if ($total_packs[$carindex] == 'Deluxe') {
                        $total += $delx_price;
                        $veh_price = $delx_price;
                        $safe_handle_fee = $washing_plan_deluxe->handling_fee;
                        $deluxe_found = 1;
                    }
                    if ($total_packs[$carindex] == 'Premium') {
                        $total += $prem_price;
                        $veh_price = $prem_price;
                        $safe_handle_fee = $washing_plan_prem->handling_fee;
                        $prem_found = 1;
                    }



                    //safe handling fee
                    $total++;

                    $vehicles[] = array('id' => $vehicle_details->id,
                        'vehicle_no' => $vehicle_details->vehicle_no,
                        'brand_name' => $vehicle_details->brand_name,
                        'model_name' => $vehicle_details->model_name,
                        'vehicle_image' => $vehicle_details->vehicle_image,
                        'vehicle_type' => $vehicle_details->vehicle_type,
                        'vehicle_washing_package' => $total_packs[$carindex],
                        'vehicle_washing_price' => $veh_price,
                        'safe_handling_fee' => $safe_handle_fee
                    );
                }

                /* --------- Get total price end ------------- */



                if ($fee_check) {
                    $result = 'false';
                    $response = "Discounts cannot be combined with promotions, coupons and other offers.";
                } else {
                    $result = 'true';

                    if ($prem_found)
                        $response = number_format($coupon_check->premium_amount, 2);
                    elseif ($deluxe_found)
                        $response = number_format($coupon_check->deluxe_amount, 2);
                    else
                        $response = number_format($coupon_check->express_amount, 2);
                }





                //$result= 'true';
                //$response= 'coupon applied';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actionpreapplycoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Please enter promo code';
        $coupon_code = Yii::app()->request->getParam('code');
        $cust_id = Yii::app()->request->getParam('customer_id');
        $order_cars = Yii::app()->request->getParam('order_cars');

        if (AES256CBC_STATUS == 1) {
            $cust_id = $this->aes256cbc_crypt($cust_id, 'd', AES256CBC_API_PASS);
        }
        if ((isset($coupon_code) && !empty($coupon_code))) {

            $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $coupon_code));
            $coupon_usage = CustomerDiscounts::model()->findAllByAttributes(array("promo_code" => $coupon_code, "customer_id" => $cust_id));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "promo code doesn't exist";
            } else if ($coupon_check->coupon_status != 'enabled') {
                $result = 'false';
                $response = "Sorry, this promo code is not available at this time.";
            } else if (strtotime($coupon_check->expire_date) > 0 && (strtotime($coupon_check->expire_date) < strtotime(date("Y-m-d")))) {
                $result = 'false';
                $response = "Promo code expired";
            } else if (($coupon_check->usage_limit == 'single') && (count($coupon_usage) >= 1)) {
                $result = 'false';
                $response = "Sorry, you already used this promo once.";
            } else {

                $deluxe_found = 0;
                $prem_found = 0;
                $fee_check = 0;

                if ($order_cars) {
                    $all_cars = explode("|", $order_cars);
                    foreach ($all_cars as $car) {
                        $car_detail = explode(",", $car);

                        if ($car_detail[2] == 'Deluxe') {
                            $deluxe_found = 1;
                        }

                        if ($car_detail[2] == 'Premium') {
                            $prem_found = 1;
                        }

                        if ($car_detail[9] || $car_detail[10]) {
                            $fee_check = 1;
                        }
                    }
                }

                if ($fee_check) {
                    $result = 'false';
                    $response = "Discounts cannot be combined with promotions, coupons and other offers.";
                } else {
                    $result = 'true';

                    if ($prem_found)
                        $response = number_format($coupon_check->premium_amount, 2);
                    else
                        $response = number_format($coupon_check->deluxe_amount, 2);
                }
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionpreapplyvipcoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Please enter promo code';
        $coupon_code = Yii::app()->request->getParam('code');
        $cust_id = Yii::app()->request->getParam('customer_id');
        $deluxe_wash_count = Yii::app()->request->getParam('deluxe_wash_count');
        $premium_wash_count = Yii::app()->request->getParam('premium_wash_count');

        if ((isset($coupon_code) && !empty($coupon_code))) {

            $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $coupon_code));
            $deluxe_wash_used = $coupon_check->deluxe_wash_used + $deluxe_wash_count;
            $premium_wash_used = $coupon_check->premium_wash_used + $premium_wash_count;
            $deluxe_wash_avail = $coupon_check->deluxe_wash_limit - $coupon_check->deluxe_wash_used;
            $premium_wash_avail = $coupon_check->premium_wash_limit - $coupon_check->premium_wash_used;

            if ($deluxe_wash_avail < 0)
                $deluxe_wash_avail = 0;
            if ($premium_wash_avail < 0)
                $premium_wash_avail = 0;

            $total_wash_avail = $deluxe_wash_avail + $premium_wash_avail;

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo code doesn't exists";
            } else if ($coupon_check->customer_id && ($coupon_check->customer_id != $cust_id)) {
                $result = 'false';
                $response = "Sorry, this promo code is not available at this time.";
            } else if (($deluxe_wash_used > $coupon_check->deluxe_wash_limit) && ($premium_wash_used > $coupon_check->premium_wash_limit)) {
                $result = 'false';
                $response = "Sorry, you don't have anymore complimentary washes available";
            } else if (($deluxe_wash_used > $coupon_check->deluxe_wash_limit)) {
                $result = 'false';
                $response = "Sorry, you don't have anymore complimentary Deluxe washes available. If you want to continue using this promo, you have to remove Deluxe cars from your order.";
            } else if (($premium_wash_used > $coupon_check->premium_wash_limit)) {
                $result = 'false';
                $response = "Sorry, you don't have anymore complimentary Premium washes available. If you want to continue using this promo, you have to remove Premium cars from your order.";
            } else {

                $result = 'true';
                $response = 'promo applied successfully';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'coupon_package' => $coupon_check->package_name,
            'del_wash_avail' => $deluxe_wash_avail,
            'prem_wash_avail' => $premium_wash_avail,
            'total_wash_avail' => $total_wash_avail
        );
        echo json_encode($json);
    }

    public function actionupdatevipcoupon() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Enter promo code';

        $coupon_code = Yii::app()->request->getParam('coupon_code');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $deluxe_wash_count = Yii::app()->request->getParam('deluxe_wash_count');
        $premium_wash_count = Yii::app()->request->getParam('premium_wash_count');


        if ((isset($coupon_code) && !empty($coupon_code))) {

            $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $coupon_code));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo doesn't exist";
            } else {

                $deluxe_wash_used = 0;
                $premium_wash_used = 0;
                $deluxe_wash_used = $coupon_check->deluxe_wash_used + $deluxe_wash_count;
                $premium_wash_used = $coupon_check->premium_wash_used + $premium_wash_count;

                if (!$customer_id) {
                    $customer_id = $coupon_check->customer_id;
                }

                if (!$deluxe_wash_count) {
                    $deluxe_wash_used = $coupon_check->deluxe_wash_used;
                }

                if (!$premium_wash_count) {
                    $premium_wash_used = $coupon_check->premium_wash_used;
                }



                $coupondata = array(
                    'customer_id' => $customer_id,
                    'deluxe_wash_used' => $deluxe_wash_used,
                    'premium_wash_used' => $premium_wash_used
                );


                $resUpdate = Yii::app()->db->createCommand()->update('vip_coupon_codes', $coupondata, 'fullcode=:fullcode', array(':fullcode' => $coupon_code));

                $result = 'true';
                $response = 'Promo updated successfully';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actiongetvipcoupondetails() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $result = 'false';
        $response = 'Fill up required fields';
        $coupon_code = Yii::app()->request->getParam('code');


        if ((isset($coupon_code) && !empty($coupon_code))) {

            $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $coupon_code));

            if (!count($coupon_check)) {
                $result = 'false';
                $response = "Promo doesn't exists";
            } else {

                $dealer_details = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('vip_coupon_dealers')
                        ->where("dealer_code='" . $coupon_check->dealer_code . "'", array())
                        ->queryAll();


                $coupondata = array(
                    'coupon_id' => $coupon_check->id,
                    'package_name' => $coupon_check->package_name,
                    'deluxe_wash_limit' => $coupon_check->deluxe_wash_limit,
                    'premium_wash_limit' => $coupon_check->premium_wash_limit,
                    'dealer_name' => $coupon_check->dealer_name,
                    'dealer_logo' => $dealer_details[0]['dealer_logo'],
                    'package_code' => $coupon_check->package_code,
                    'dealer_code' => $coupon_check->dealer_code,
                    'unique_code' => $coupon_check->unique_code,
                    'fullcode' => $coupon_check->fullcode,
                    'customer_id' => $coupon_check->customer_id,
                    'deluxe_wash_used' => $coupon_check->deluxe_wash_used,
                    'premium_wash_used' => $coupon_check->premium_wash_used
                );


                $result = 'true';
                $response = 'promo details';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
            'coupon_details' => $coupondata
        );
        echo json_encode($json);
    }

}
