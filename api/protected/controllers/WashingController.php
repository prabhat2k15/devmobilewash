<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

class WashingController extends Controller {

    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13102941020';
    protected $callbackurl = 'http://www.devmobilewash.com/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'PNfd832d59f14c19b1527208ea314c1b87';

    public function actionIndex() {
        $this->render('index');
    }

    /*
     * * Returns Washing Palns.
     * * Post Required: vehicle_model
     * * Url:- http://www.demo.com/index.php?r=washing/plans
     * * Purpose:- Return Plan details
     */

    public function actionplans() {

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

        $vehicle_make = Yii::app()->request->getParam('vehicle_make');
        $vehicle_model = Yii::app()->request->getParam('vehicle_model');
        $vehicle_build = '';
        $vehicle_build = Yii::app()->request->getParam('vehicle_build');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $location_id = '';
        if (Yii::app()->request->getParam('location_id'))
            $location_id = Yii::app()->request->getParam('location_id');
        $zipcode = '';
        if (Yii::app()->request->getParam('zipcode'))
            $zipcode = Yii::app()->request->getParam('zipcode');
        $json = array();
        $plans = array();
        $express_plan = array();
        $deluxe_plan = array();
        $premium_plan = array();
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        $wash_now_fee = 0;
        $wash_later_fee = 0;
        $vehicle_type = '';
        $result = 'false';
        $response = 'Pass the required parameters';
        $coveragezipcheck = 0;
        $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings`")->queryAll();
        if (count($app_settings)) {
            //if(is_numeric($app_settings[0]['wash_now_fee'])) $wash_now_fee = $app_settings[0]['wash_now_fee'];
            //if(is_numeric($app_settings[0]['wash_later_fee'])) $wash_later_fee = $app_settings[0]['wash_later_fee'];
        }

        if (isset($vehicle_make) && !empty($vehicle_make) && isset($vehicle_model) && !empty($vehicle_model)) {

            if ($vehicle_build == 'classic') {

                $vehicle_exists = Yii::app()->db->createCommand('SELECT * FROM all_classic_vehicles WHERE make = :make AND model= :model')
                        ->bindValue(':make', $vehicle_make, PDO::PARAM_STR)
                        ->bindValue(':model', $vehicle_model, PDO::PARAM_STR)
                        ->queryAll();
            } else {

                $vehicle_exists = Yii::app()->db->createCommand('SELECT * FROM all_vehicles WHERE make = :make AND model= :model')
                        ->bindValue(':make', $vehicle_make, PDO::PARAM_STR)
                        ->bindValue(':model', $vehicle_model, PDO::PARAM_STR)
                        ->queryAll();
            }


            if (count($vehicle_exists) > 0) {
                $vehicle_type = $vehicle_exists[0]['type'];
            }

            if ($vehicle_type) {

                $result = 'true';
                $response = 'Plans';

                $allplans = Yii::app()->db->createCommand('SELECT * FROM washing_plans WHERE vehicle_type = :vehicle_type')
                        ->bindValue(':vehicle_type', $vehicle_type, PDO::PARAM_STR)
                        ->queryAll();




                $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
                //$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();


                if ($zipcode) {

                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));
                    /* if(count($coveragezipcheck)){

                      if($coveragezipcheck->zip_color == 'yellow'){
                      $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                      }

                      if($coveragezipcheck->zip_color == 'red'){
                      $zipcode_price_factor = $zipcodeprice[0]['red'];
                      }

                      if($coveragezipcheck->zip_color == ''){
                      $zipcode_price_factor = $zipcodeprice[0]['blue'];
                      }

                      if($zipcodeprice[0]['price_unit'] == 'percent'){
                      $exp_surge_factor += $zipcode_price_factor;
                      $del_surge_factor += $zipcode_price_factor;
                      $prem_surge_factor += $zipcode_price_factor;
                      }

                      } */
                } else {
                    if ($location_id)
                        $loc_check = CustomerLocation::model()->findByPk($location_id);
                    if (count($loc_check)) {
                        $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $loc_check->zipcode));
                        /* if(count($coveragezipcheck)){

                          if($coveragezipcheck->zip_color == 'yellow'){
                          $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                          }

                          if($coveragezipcheck->zip_color == 'red'){
                          $zipcode_price_factor = $zipcodeprice[0]['red'];
                          }

                          if($coveragezipcheck->zip_color == ''){
                          $zipcode_price_factor = $zipcodeprice[0]['blue'];
                          }

                          if($zipcodeprice[0]['price_unit'] == 'percent'){
                          $exp_surge_factor += $zipcode_price_factor;
                          $del_surge_factor += $zipcode_price_factor;
                          $prem_surge_factor += $zipcode_price_factor;
                          }
                          } */
                    }
                }

                $exp_surge_factor += $surgeprice[0]['express'];
                $del_surge_factor += $surgeprice[0]['deluxe'];
                $prem_surge_factor += $surgeprice[0]['premium'];


                foreach ($allplans as $planDetails) {
                    $planDetails['description'] = preg_split('/\r\n|[\r\n]/', $planDetails['description']);
                    unset($planDetails['id']);

                    /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){

                      $planDetails['price'] += $zipcode_price_factor;
                      $planDetails['price'] = (string) $planDetails['price'];
                      } */

                    if (count($coveragezipcheck)) {
                        if ($coveragezipcheck->zip_color == 'yellow') {
                            $planDetails['price'] = $planDetails['tier2_price'];
                        }

                        if ($coveragezipcheck->zip_color == 'red') {
                            $planDetails['price'] = $planDetails['tier3_price'];
                        }

                        if ($coveragezipcheck->zip_color == 'purple') {
                            $planDetails['price'] = $planDetails['tier4_price'];
                        }
                    }

                    if ($planDetails['title'] == "Express") {

                        $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($exp_surge_factor / 100));
                        $planDetails['price'] = number_format($planDetails['price'], 2);
                        $express_plan[] = $planDetails;
                    }
                    if ($planDetails['title'] == "Deluxe") {
                        //$planDetails['price'] += $surgeprice[0]['deluxe'];
                        //$planDetails['price'] = (string) $planDetails['price'];
                        $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($del_surge_factor / 100));
                        $planDetails['price'] = number_format($planDetails['price'], 2);
                        $deluxe_plan[] = $planDetails;
                    }
                    if ($planDetails['title'] == "Premium") {
                        //$planDetails['price'] += $surgeprice[0]['premium'];
                        //$planDetails['price'] = (string) $planDetails['price'];
                        $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($prem_surge_factor / 100));
                        $planDetails['price'] = number_format($planDetails['price'], 2);
                        $premium_plan[] = $planDetails;
                    }
                    //$plans[] = $planDetails;
                }

                $plans['express'] = $express_plan;
                $plans['deluxe'] = $deluxe_plan;
                $plans['premium'] = $premium_plan;
            } else {
                $response = 'No plans exists';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'plans' => $plans,
            'wash_now_fee' => $wash_now_fee,
            'wash_later_fee' => $wash_later_fee
        );

        echo json_encode($json);
        die();
    }

    public function actionmultivehicleplans() {

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

        $vehicle_ids = Yii::app()->request->getParam('vehicle_ids');

        $customer_id = Yii::app()->request->getParam('customer_id');
        $location_id = '';
        if (Yii::app()->request->getParam('location_id'))
            $location_id = Yii::app()->request->getParam('location_id');
        $json = array();
        $plans = array();
        $express_plan = array();
        $deluxe_plan = array();
        $premium_plan = array();
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        $vehicle_type = '';
        $result = 'false';
        $response = 'Pass the required parameters';
        $coveragezipcheck = 0;

        if (isset($vehicle_ids) && !empty($vehicle_ids)) {

            if ($location_id)
                $loc_check = CustomerLocation::model()->findByPk($location_id);
            $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
            $zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();

            $allvehicles = explode(",", $vehicle_ids);

            if (count($allvehicles)) {
                foreach ($allvehicles as $vehicle) {
                    $exp_surge_factor = 0;
                    $del_surge_factor = 0;
                    $prem_surge_factor = 0;
                    $zipcode_price_factor = 0;
                    $express_plan = array();
                    $deluxe_plan = array();
                    $premium_plan = array();
                    $vehicle_exists = Vehicle::model()->findByAttributes(array("id" => trim($vehicle)));

                    if (count($vehicle_exists) && ($vehicle_exists->vehicle_type)) {

                        $vehplan = Yii::app()->db->createCommand('SELECT * FROM washing_plans WHERE vehicle_type = :vehicle_type')
                                ->bindValue(':vehicle_type', $vehicle_exists->vehicle_type, PDO::PARAM_STR)
                                ->queryAll();

                        if (count($loc_check)) {
                            $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $loc_check->zipcode));
                            /* if(count($coveragezipcheck)){

                              if($coveragezipcheck->zip_color == 'yellow'){
                              $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                              }

                              if($coveragezipcheck->zip_color == 'red'){
                              $zipcode_price_factor = $zipcodeprice[0]['red'];
                              }

                              if($coveragezipcheck->zip_color == ''){
                              $zipcode_price_factor = $zipcodeprice[0]['blue'];
                              }

                              if($zipcodeprice[0]['price_unit'] == 'percent'){
                              $exp_surge_factor += $zipcode_price_factor;
                              $del_surge_factor += $zipcode_price_factor;
                              $prem_surge_factor += $zipcode_price_factor;
                              }

                              } */
                        }

                        $exp_surge_factor += $surgeprice[0]['express'];
                        $del_surge_factor += $surgeprice[0]['deluxe'];
                        $prem_surge_factor += $surgeprice[0]['premium'];


                        foreach ($vehplan as $planDetails) {
                            $planDetails['description'] = preg_split('/\r\n|[\r\n]/', $planDetails['description']);
                            unset($planDetails['id']);

                            /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                              $planDetails['price'] += $zipcode_price_factor;
                              $planDetails['price'] = (string) $planDetails['price'];
                              } */

                            if (count($coveragezipcheck)) {
                                if ($coveragezipcheck->zip_color == 'yellow') {
                                    $planDetails['price'] = $planDetails['tier2_price'];
                                }

                                if ($coveragezipcheck->zip_color == 'red') {
                                    $planDetails['price'] = $planDetails['tier3_price'];
                                }

                                if ($coveragezipcheck->zip_color == 'purple') {
                                    $planDetails['price'] = $planDetails['tier4_price'];
                                }
                            }

                            if ($planDetails['title'] == "Express") {
                                //$planDetails['price'] += $surgeprice[0]['deluxe'];
                                //$planDetails['price'] = (string) $planDetails['price'];
                                $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($exp_surge_factor / 100));
                                $planDetails['price'] = number_format($planDetails['price'], 2);
                                $express_plan[] = $planDetails;
                            }
                            if ($planDetails['title'] == "Deluxe") {
                                //$planDetails['price'] += $surgeprice[0]['deluxe'];
                                //$planDetails['price'] = (string) $planDetails['price'];
                                $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($del_surge_factor / 100));
                                $planDetails['price'] = number_format($planDetails['price'], 2);
                                $deluxe_plan[] = $planDetails;
                            }
                            if ($planDetails['title'] == "Premium") {
                                //$planDetails['price'] += $surgeprice[0]['premium'];
                                //$planDetails['price'] = (string) $planDetails['price'];
                                $planDetails['price'] = $planDetails['price'] + ($planDetails['price'] * ($prem_surge_factor / 100));
                                $planDetails['price'] = number_format($planDetails['price'], 2);
                                $premium_plan[] = $planDetails;
                            }
                            //$plans[] = $planDetails;
                        }

                        $plans[$vehicle_exists->id]['express'] = $express_plan;
                        $plans[$vehicle_exists->id]['deluxe'] = $deluxe_plan;
                        $plans[$vehicle_exists->id]['premium'] = $premium_plan;
                    }
                }
            }


            if (count($plans)) {
                $result = 'true';
                $response = 'plans';
            } else {
                $response = 'No plans exists';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'plans' => $plans
        );

        echo json_encode($json);
        die();
    }

    public function actiongetvehicleplans() {

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
        $response = 'Pass the required parameters';

        $allplans = Yii::app()->db->createCommand()
                ->select('*')
                ->from('washing_plans')
                ->queryAll();

        if (!count($allplans)) {
            $response = 'No plans exists';
            $result = 'false';
        } else {
            $response = 'washing plans';
            $result = 'true';
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'plans' => $allplans
        );

        echo json_encode($json);
        die();
    }

    public function actionupdatevehicleplan() {

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

        $id = Yii::app()->request->getParam('id');
        $duration = Yii::app()->request->getParam('duration');
        $wash_time = Yii::app()->request->getParam('wash_time');
        $price = Yii::app()->request->getParam('price');
        $price2 = Yii::app()->request->getParam('price2');
        $price3 = Yii::app()->request->getParam('price3');
        $price4 = Yii::app()->request->getParam('price4');
        $description = Yii::app()->request->getParam('description');
        $admin_username = Yii::app()->request->getParam('admin_username');
        $updated_date = date('Y-m-d H:i:s');

        $result = 'false';
        $response = 'Pass the required parameters';

        $update_status = Yii::app()->db->createCommand("UPDATE washing_plans SET duration=:duration, wash_time=:wash_time, price=:price, tier2_price=:price2, tier3_price=:price3, tier4_price=:price4, description=:description, updated_date=:Updated_date, created_by=:created_by WHERE id = :id")
                ->bindValue(':duration', $duration, PDO::PARAM_STR)
                ->bindValue(':wash_time', $wash_time, PDO::PARAM_STR)
                ->bindValue(':price', $price, PDO::PARAM_STR)
                ->bindValue(':price2', $price2, PDO::PARAM_STR)
                ->bindValue(':price3', $price3, PDO::PARAM_STR)
                ->bindValue(':price4', $price4, PDO::PARAM_STR)
                ->bindValue(':description', $description, PDO::PARAM_STR)
                ->bindValue(':id', $id, PDO::PARAM_STR)
                ->bindValue(':Updated_date', $updated_date, PDO::PARAM_STR)
                ->bindValue(':created_by', $admin_username, PDO::PARAM_STR)
                ->execute();

        $response = 'update success';
        $result = 'true';


        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionClearwash() {

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
        $response = 'Pass the required parameters';
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if (isset($wash_request_id) && !empty($wash_request_id)) {
            $wash = Washingrequests::model()->findByPk($wash_request_id);
            $wash->status = 0;
            $wash->save(false);
            $result = 'true';
            $response = 'status cleared';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    /*
     * * Returns Washing Requests.
     * * Post Required: customer id,CarList,PackageList,address,address_type,PaymentType,Nonce
     * * Url:- http://www.demo.com/index.php?r=washing/createwashrequest
     * * Purpose:- Create wash request
     */

    public function actioncreatewashrequest() {

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
        $dateChack = Yii::app()->request->getParam('ScheduleDateVal');


        $customer_id = Yii::app()->request->getParam('customer_id');
        $car_ids = Yii::app()->request->getParam('car_ids');
        $package_ids = Yii::app()->request->getParam('package_names');
        $address = Yii::app()->request->getParam('address');
        $street_name = Yii::app()->request->getParam('street_name');
        $order_city = Yii::app()->request->getParam('order_city');
        $order_state = Yii::app()->request->getParam('order_state');
        $order_zipcode = Yii::app()->request->getParam('order_zipcode');
        $address_type = Yii::app()->request->getParam('address_type');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $payment_type = Yii::app()->request->getParam('payment_type');
        $nonce = Yii::app()->request->getParam('nonce');
        $estimate_time = Yii::app()->request->getParam('estimate_time');
        $transaction_id = Yii::app()->request->getParam('transaction_id');
        $is_cust_first_wash = 0;

        $is_scheduled = 0;
        if (Yii::app()->request->getParam('is_scheduled'))
            $is_scheduled = Yii::app()->request->getParam('is_scheduled');

        $schedule_date = Yii::app()->request->getParam('schedule_date');

        $schedule_time = Yii::app()->request->getParam('schedule_time');
        $schedule_cars_info = Yii::app()->request->getParam('schedule_cars_info');
        $schedule_total = Yii::app()->request->getParam('schedule_total');
        $schedule_total_ini = '';
        $schedule_total_ini = Yii::app()->request->getParam('schedule_total_ini');
        $schedule_total_vip = Yii::app()->request->getParam('schedule_total_vip');
        $schedule_company_total = Yii::app()->request->getParam('schedule_company_total');
        $schedule_company_total_vip = Yii::app()->request->getParam('schedule_company_total_vip');
        $schedule_agent_total = Yii::app()->request->getParam('schedule_agent_total');

        $coupon_amount = '';
        if (Yii::app()->request->getParam('coupon_amount'))
            $coupon_amount = Yii::app()->request->getParam('coupon_amount');

        $coupon_code = '';
        if (Yii::app()->request->getParam('coupon_code'))
            $coupon_code = Yii::app()->request->getParam('coupon_code');

        $coupon_code_vip = Yii::app()->request->getParam('coupon_code_vip');
        $tip_amount = Yii::app()->request->getParam('tip_amount');
        $wash_request_position = Yii::app()->request->getParam('wash_request_position');

        $pet_hair_vehicles = '';
        if (Yii::app()->request->getParam('pet_hair_vehicles'))
            $pet_hair_vehicles = Yii::app()->request->getParam('pet_hair_vehicles');

        $lifted_vehicles = '';
        if (Yii::app()->request->getParam('lifted_vehicles'))
            $lifted_vehicles = Yii::app()->request->getParam('lifted_vehicles');

        $exthandwax_vehicles = '';
        if (Yii::app()->request->getParam('exthandwax_vehicles'))
            $exthandwax_vehicles = Yii::app()->request->getParam('exthandwax_vehicles');

        $extplasticdressing_vehicles = '';
        if (Yii::app()->request->getParam('extplasticdressing_vehicles'))
            $extplasticdressing_vehicles = Yii::app()->request->getParam('extplasticdressing_vehicles');

        $extclaybar_vehicles = '';
        if (Yii::app()->request->getParam('extclaybar_vehicles'))
            $extclaybar_vehicles = Yii::app()->request->getParam('extclaybar_vehicles');

        $waterspotremove_vehicles = '';
        if (Yii::app()->request->getParam('waterspotremove_vehicles'))
            $waterspotremove_vehicles = Yii::app()->request->getParam('waterspotremove_vehicles');

        $upholstery_vehicles = '';
        if (Yii::app()->request->getParam('upholstery_vehicles'))
            $upholstery_vehicles = Yii::app()->request->getParam('upholstery_vehicles');

        $floormat_vehicles = '';
        if (Yii::app()->request->getParam('floormat_vehicles'))
            $floormat_vehicles = Yii::app()->request->getParam('floormat_vehicles');

        $fifth_wash_vehicles = '';
        if (Yii::app()->request->getParam('fifth_wash_vehicles'))
            $fifth_wash_vehicles = Yii::app()->request->getParam('fifth_wash_vehicles');

        $surge_price_vehicles = '';

        $customer_total_wash = 0;
        $wash_now_fee = 0;
        if (Yii::app()->request->getParam('wash_now_fee'))
            $wash_now_fee = Yii::app()->request->getParam('wash_now_fee');
        $wash_later_fee = 0;
        if (Yii::app()->request->getParam('wash_later_fee'))
            $wash_later_fee = Yii::app()->request->getParam('wash_later_fee');
        $last_order_days = "";

        $json = array();
        $car_id_check = true;
        $washplan_id_check = true;
        $result = 'false';
        $response = 'Pass the required parameters';

        if (AES256CBC_STATUS == 1) {
            $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
        }

        if ((isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($package_ids) && !empty($package_ids)) && (isset($address) && !empty($address)) && (isset($address_type) && !empty($address_type)) && (isset($latitude) && !empty($latitude)) && (isset($longitude) && !empty($longitude)) && (isset($estimate_time) && !empty($estimate_time))) {
            $customers_id_check = Customers::model()->findByAttributes(array("id" => $customer_id));

            if ($customers_id_check->client_position == 'real')
                $pendingwashcheck = Washingrequests::model()->findAll(array("condition" => "wash_request_position = 'real' AND status <= 3 AND customer_id=:customer_id", 'params' => array(':customer_id' => $customer_id), 'order' => 'created_date desc'));
            else
                $pendingwashcheck = Washingrequests::model()->findAll(array("condition" => "wash_request_position != 'real' AND status <= 3 AND customer_id=:customer_id", 'params' => array(':customer_id' => $customer_id), 'order' => 'created_date desc'));

            $car_ids_array = explode(",", $car_ids);
            foreach ($car_ids_array as $cid) {
                $car_id_exists = Vehicle::model()->findByAttributes(array("id" => $cid));
                if (!count($car_id_exists)) {
                    $car_id_check = false;
                    break;
                }
            }

            $washplan_ids_array = explode(",", $package_ids);
            foreach ($washplan_ids_array as $wid) {
                $washplan_id_exists = Washingplans::model()->findByAttributes(array("title" => $wid));
                if (!count($washplan_id_exists)) {
                    $washplan_id_check = false;
                    break;
                }
            }

            if (!count($customers_id_check)) {
                $response = 'Invalid customer';
            } else if (!$car_id_check) {
                $response = 'Invalid vehicle id ' . $cid;
            } else if (!$washplan_id_check) {
                $response = 'Invalid washing plan ' . $wid;
            } else if ($customers_id_check->block_client) {
                $response = "Account error. Please contact MobileWash.";
            } else if (count($pendingwashcheck)) {
                if ($pendingwashcheck[0]->is_scheduled == 1)
                    $response = "Sorry you may not order at this time. You have a pending scheduled order in progress.";
                else
                    $response = "Sorry you may not order at this time. You have a pending order in progress.";
            }

            else {

                $totalwash_arr = Washingrequests::model()->findAllByAttributes(array("status" => 4, "customer_id" => $customer_id));

                $customer_total_wash = count($totalwash_arr);

                foreach ($car_ids_array as $car) {

                    $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '');
                    $vehiclemodel = new Vehicle;
                    $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                }

                $encode_address = urlencode($address);

                /* --- Geocode lat long --- */

                $geourl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $encode_address . "&sensor=true&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $geourl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                $georesult = curl_exec($ch);
                curl_close($ch);
                $geojsondata = json_decode($georesult);

                if ($geojsondata->status == 'ZERO_RESULTS') {
                    $json = array(
                        'result' => 'false',
                        'response' => 'Invalid address',
                    );

                    echo json_encode($json);
                    die();
                } else {
                    $latitude = $geojsondata->results[0]->geometry->location->lat;
                    $longitude = $geojsondata->results[0]->geometry->location->lng;
                }



                if ((isset($is_scheduled) && !empty($is_scheduled))) {
                    $order_date = date("Y-m-d H:i:s", strtotime($schedule_date . " " . $schedule_time));
                } else {
                    $order_date = date("Y-m-d H:i:s");
                }

                $date = date("Y-m-d H:i:s");

                $washrequestdata = array(
                    'customer_id' => $customer_id,
                    'car_list' => $car_ids,
                    'package_list' => $package_ids,
                    'address' => $address,
                    'address_type' => $address_type,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'is_scheduled' => $is_scheduled,
                    'estimate_time' => $estimate_time,
                    'created_date' => $date,
                    'order_for' => $order_date,
                );

                //$washrequestdata= array_filter($washrequestdata);
                $model = new Washingrequests;
                $model->attributes = $washrequestdata;
                if ($model->save(false)) {
                    $washrequestid = Yii::app()->db->getLastInsertID();
                    $result = 'true';
                    $response = $washrequestid;

                    if ($is_scheduled) {
                        $logdata = array(
                            'wash_request_id' => $washrequestid,
                            //'admin_username' => $admin_username,
                            //'agent_id'=> $customer_id,
                            'addi_detail' => "",
                            'action' => 'customerschedulecreate',
                            'action_date' => date('Y-m-d H:i:s'));
                    } else {
                        $logdata = array(
                            'wash_request_id' => $washrequestid,
                            //'admin_username' => $admin_username,
                            //'agent_id'=> $customer_id,
                            'addi_detail' => "",
                            'action' => 'customerondemandcreate',
                            'action_date' => date('Y-m-d H:i:s'));
                    }


                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

                    /* ---------- insert transaction id -------------- */

                    if ((isset($transaction_id) && !empty($transaction_id))) {

                        Washingrequests::model()->updateByPk($washrequestid, array("transaction_id" => $transaction_id, "upfront_transaction_id" => $transaction_id));
                    }

                    /* ---------- insert transaction id end -------------- */

                    if ($coupon_code) {
                        $coupondata = array(
                            'customer_id' => $customer_id,
                            'wash_request_id' => $washrequestid,
                            'promo_code' => $coupon_code
                        );

                        $model = new CustomerDiscounts;
                        $model->attributes = $coupondata;
                        $model->save(false);
                    }
                    /* Check Old tip value */
                    $old_amount = 0.00;
                    $old_tip_amount = Yii::app()->db->createCommand("SELECT tip_amount FROM washing_requests WHERE id = :id LIMIT 1")->bindValue(':id', $washrequestid, PDO::PARAM_STR)->queryAll();

                    /* ---------- insert addons / others -------------- */

                    if ($order_state == '(null)' || $order_zipcode == '(null)') {
                        /* --- Geocode lat long --- */

                        $geourl = "https://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $encode_address . "&language=en&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $geourl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                        $georesult = curl_exec($ch);
                        curl_close($ch);
                        $geojsondata = json_decode($georesult);
//var_dump($geojsondata);

                        if ($geojsondata->status == 'ZERO_RESULTS') {
                            $json = array(
                                'result' => 'false',
                                'response' => 'Invalid address',
                            );

                            echo json_encode($json);
                            die();
                        } else {
                            $address_components = $geojsondata->results[0]->address_components;

                            foreach ($address_components as $key_geo => $geo_value) {

                                echo $geo_value->types[0];
                                if ($geo_value->types[0] == 'postal_code') {
                                    $order_zipcode = $geo_value->long_name;
                                } elseif ($geo_value->types[0] == 'administrative_area_level_1' && $geo_value->types[1] == 'political') {
                                    $order_zipcode = $geo_value->long_name;
                                }
                            }
                        }
                    }

                    $fifth_disc = 0;
                    if ($fifth_wash_vehicles)
                        $fifth_disc = 5;
                    $pet_hair_vehicles_custom = array();
                    $pet_hair_vehicles_array = array();
                    if ($pet_hair_vehicles)
                        $pet_hair_vehicles_array = explode(",", $pet_hair_vehicles);
                    $car_packs_arr = explode(",", $package_ids);
                    $all_cars_arr = explode(",", $car_ids);
                    if (count($pet_hair_vehicles_array)) {
                        foreach ($pet_hair_vehicles_array as $cid) {
                            $car_id_exists = Vehicle::model()->findByAttributes(array("id" => $cid));
                            if (count($car_id_exists)) {
                                $key = array_search($cid, $all_cars_arr);
                                $pethairprice = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE title = 'Pet Hair' AND package = '" . $car_packs_arr[$key] . "' AND vehicle_type = '" . $car_id_exists->vehicle_type . "'")
                                        ->queryAll();

                                $pet_hair_vehicles_custom[$cid] = $pethairprice[0]['price'];
                                //$pet_hair_vehicles_custom[$cid] = "10.00";
                            } else {
                                $pet_hair_vehicles_custom[$cid] = "10.00";
                            }
                        }
                    }

                    if (count($pet_hair_vehicles_custom))
                        $pet_hair_vehicles_custom = json_encode($pet_hair_vehicles_custom);
                    else
                        $pet_hair_vehicles_custom = '';

                    if (!$customers_id_check->total_wash)
                        $is_cust_first_wash = 1;
                    Washingrequests::model()->updateByPk($washrequestid, array('street_name' => $street_name, 'city' => $order_city, 'state' => $order_state, 'zipcode' => $order_zipcode, 'pet_hair_vehicles' => $pet_hair_vehicles, "pet_hair_vehicles_custom_amount" => $pet_hair_vehicles_custom, 'lifted_vehicles' => $lifted_vehicles, 'exthandwax_vehicles' => $exthandwax_vehicles, 'extplasticdressing_vehicles' => $extplasticdressing_vehicles, 'extclaybar_vehicles' => $extclaybar_vehicles, 'waterspotremove_vehicles' => $waterspotremove_vehicles, 'upholstery_vehicles' => $upholstery_vehicles, 'floormat_vehicles' => $floormat_vehicles, 'fifth_wash_vehicles' => $fifth_wash_vehicles, 'fifth_wash_discount' => $fifth_disc, 'coupon_discount' => $coupon_amount, 'coupon_code' => $coupon_code, 'tip_amount' => $tip_amount, 'wash_request_position' => $wash_request_position, 'wash_now_fee' => $wash_now_fee, 'wash_later_fee' => $wash_later_fee, 'inc_transaction_fee' => 1, 'is_cust_first_wash' => $is_cust_first_wash));

                    if ($old_tip_amount[0]['tip_amount']) {
                        $old_amount = $old_tip_amount[0]['tip_amount'];
                    }
                    if ($tip_amount != $old_amount) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $washrequestid,
                            //'admin_username' => $admin_username,
                            //'agent_id'=> $customer_id,
                            'addi_detail' => '$' . number_format($tip_amount, 2),
                            'action' => 'customeraddtipamount',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }

                    $car_arr = explode(",", $car_ids);
                    $car_packs = explode(",", $package_ids);
                    $wash_details = Washingrequests::model()->findByPk($washrequestid);
                    foreach ($car_arr as $ind => $carid) {
                        $pet_fee = 0;
                        $lift_fee = 0;
                        $exthandwax_fee = 0;
                        $extplasticdressing_fee = 0;
                        $extclaybar_fee = 0;
                        $waterspotremove_fee = 0;
                        $upholstery_fee = 0;
                        $floormat_fee = 0;
                        $surge_fee = 0;

                        $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles);
                        if (in_array($carid, $pet_hair_vehicles_arr))
                            $pet_fee = 10;

                        $lifted_vehicles_arr = explode(",", $lifted_vehicles);
                        if (in_array($carid, $lifted_vehicles_arr))
                            $lift_fee = 5;

                        $exthandwax_addon_arr = explode(",", $exthandwax_vehicles);
                        if (in_array($carid, $exthandwax_addon_arr))
                            $exthandwax_fee = 12;

                        $extplasticdressing_addon_arr = explode(",", $extplasticdressing_vehicles);
                        if (in_array($carid, $extplasticdressing_addon_arr))
                            $extplasticdressing_fee = 8;

                        $extclaybar_addon_arr = explode(",", $extclaybar_vehicles);
                        if (in_array($carid, $extclaybar_addon_arr))
                            $extclaybar_fee = 35;

                        $waterspotremove_addon_arr = explode(",", $waterspotremove_vehicles);
                        if (in_array($carid, $waterspotremove_addon_arr))
                            $waterspotremove_fee = 30;

                        $upholstery_addon_arr = explode(",", $upholstery_vehicles);
                        if (in_array($carid, $upholstery_addon_arr))
                            $upholstery_fee = 20;

                        $floormat_addon_arr = explode(",", $floormat_vehicles);
                        if (in_array($carid, $floormat_addon_arr))
                            $floormat_fee = 10;



                        Vehicle::model()->updateByPk($carid, array('pet_hair' => $pet_fee, 'lifted_vehicle' => $lift_fee, 'exthandwax_addon' => $exthandwax_fee, 'extplasticdressing_addon' => $extplasticdressing_fee, 'extclaybar_addon' => $extclaybar_fee, 'waterspotremove_addon' => $waterspotremove_fee, 'surge_addon' => $surge_fee, 'upholstery_addon' => $upholstery_fee, 'floormat_addon' => $floormat_fee));
                    }

                    $surge_price_vehicles = rtrim($surge_price_vehicles, ',');

                    Washingrequests::model()->updateByPk($washrequestid, array('surge_price_vehicles' => $surge_price_vehicles));

                    /* ---------- insert addons end -------------- */

                    /* ----------- make customer status busy ----------- */

                    $customers_id_check->online_status = 'busy';
                    $customers_id_check->save(false);

                    /* ---------- make customer status busy end -------------- */

                    Customers::model()->updateByPk($customer_id, array("is_first_wash" => 1));

                    /* ------- kart details ----------- */

                    $kartapiresult = $this->washingkart($washrequestid, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                    $kartdata = json_decode($kartapiresult);

                    /* ------- kart details end ----------- */

                    /* ----------- update pricing details -------------- */

                    $cust_details = Customers::model()->findByAttributes(array('id' => $customer_id));
                    Washingrequests::model()->updateByPk($washrequestid, array('total_price' => $kartdata->total_price, 'net_price' => $kartdata->net_price, 'company_total' => $kartdata->company_total, 'agent_total' => $kartdata->agent_total, 'bundle_discount' => $kartdata->bundle_discount, 'first_wash_discount' => $kartdata->first_wash_discount, 'coupon_discount' => $kartdata->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points));

                    /* ----------- update pricing details end -------------- */

                    $mobile_receipt = '';

                    /* --------- car pricing save --------- */
                    foreach ($kartdata->vehicles as $ind => $vehicle) {

                        $washpricehistorymodel = new WashPricingHistory;
                        $washpricehistorymodel->wash_request_id = $washrequestid;
                        $washpricehistorymodel->vehicle_id = $vehicle->id;
                        $washpricehistorymodel->package = $vehicle->vehicle_washing_package;
                        $washpricehistorymodel->vehicle_price = $vehicle->vehicle_washing_price;
                        $washpricehistorymodel->pet_hair = $vehicle->pet_hair_fee;
                        $washpricehistorymodel->lifted_vehicle = $vehicle->lifted_vehicle_fee;
                        $washpricehistorymodel->exthandwax_addon = $vehicle->exthandwax_vehicle_fee;
                        $washpricehistorymodel->extplasticdressing_addon = $vehicle->extplasticdressing_vehicle_fee;
                        $washpricehistorymodel->extclaybar_addon = $vehicle->extclaybar_vehicle_fee;
                        $washpricehistorymodel->waterspotremove_addon = $vehicle->waterspotremove_vehicle_fee;
                        $washpricehistorymodel->upholstery_addon = $vehicle->upholstery_vehicle_fee;
                        $washpricehistorymodel->floormat_addon = $vehicle->floormat_vehicle_fee;
                        $washpricehistorymodel->safe_handling = $vehicle->safe_handling_fee;
                        $washpricehistorymodel->bundle_disc = $vehicle->bundle_discount;
                        $washpricehistorymodel->last_updated = date("Y-m-d H:i:s");
                        $washpricehistorymodel->save(false);
                    }
                    /* --------- car pricing save end --------- */

                    if (!$is_scheduled) {
                        $wash_details = Washingrequests::model()->findByPk($washrequestid);

                        $from = Vargas::Obj()->getAdminFromEmail();
                        //echo $from;
                        $sched_date = '';
                        if (strtotime($wash_details->schedule_date) == strtotime(date('Y-m-d'))) {
                            $sched_date = 'Today';
                        } else {
                            $sched_date = date('M d', strtotime($wash_details->schedule_date));
                        }
                        $message = '';
                        $mobile_receipt = '';
                        $subject = 'Order Receipt - #0000' . $washrequestid;
                        //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                        $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is placed for " . date('M d', strtotime($wash_details->order_for)) . " @ " . date('h:i A', strtotime($wash_details->order_for)) . "</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $address . "</p>";
                        $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $washrequestid . "</td></tr>
					</table>";

                        $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";

                        foreach ($kartdata->vehicles as $ind => $vehicle) {
                            $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";
                            $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                            if ($vehicle->surge_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->extclaybar_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->waterspotremove_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->exthandwax_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->pet_hair_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                            }
                            if ($vehicle->lifted_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->upholstery_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->floormat_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                            }

                            $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->safe_handling_fee . "</p></td>
</tr>";

                            if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo Discount (" . $coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format($coupon_amount, 2) . "</p></td>
</tr>";
                                $mobile_receipt .= "Promo: " . $coupon_code . " -$" . number_format($coupon_amount, 2) . "\r\n";
                            }


                            if ($vehicle->fifth_wash_discount > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . $vehicle->fifth_wash_discount . "</p></td>
</tr>";
                                $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                            }

                            if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                                $mobile_receipt .= "Bundle -$1.00\r\n";
                            }

                            if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                                $mobile_receipt .= "Bundle -$1.00\r\n";
                            }



                            $mobile_receipt .= "------\r\n";
                            $message .= "</table>

</td>
</tr>";
                        }


                        if ($tip_amount) {
                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Tip</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($tip_amount, 2) . "</p></td>
							</tr></table>";
                            $mobile_receipt .= "Tip $" . number_format($tip_amount, 2) . "\r\n";
                        }

                        if ($wash_now_fee > 0) {

                            $mobile_receipt .= "Wash Now $" . number_format($wash_now_fee, 2) . "\r\n";

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Wash Now Fee</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($wash_now_fee, 2) . "</p></td>
							</tr></table>";
                        }

                        if ($wash_later_fee > 0) {

                            $mobile_receipt .= "Surge Fee $" . number_format($wash_later_fee, 2) . "\r\n";

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Surge Fee</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($wash_later_fee, 2) . "</p></td>
							</tr></table>";
                        }

                        if ($wash_details->vip_coupon_code) {

                            $vip_coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $wash_details->vip_coupon_code));

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>" . $vip_coupon_check->package_name . " (" . $wash_details->vip_coupon_code . ")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'></p></td>
							</tr></table>";
                            $mobile_receipt .= $vip_coupon_check->package_name . "\r\n";
                        }


                        $message .= "</table>";

                        if ($wash_details->schedule_total) {
                            $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . ($wash_details->vip_coupon_code != '' ? $wash_details->schedule_total_vip : $wash_details->schedule_total) . "</span></p></td></tr></table>";
                            $mobile_receipt .= "Total: $" . $wash_details->schedule_total . "\r\n";
                        } else {
                            $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $kartdata->net_price . "</span></p></td></tr></table>";
                            $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
                        }

                        $mobile_receipt .= "Washes: " . $customer_total_wash . "\r\n";

                        $message .= "<p style='text-align: center; font-size: 14px; line-height: 22px; font-size: 16px; margin-top: 25px;'>Once you request a service, a pre-authorization charge is in place to ensure the credit card on file is valid. Please note that this charge will immediately be voided following the service request. Only the actual total will be charged at the end of the service.<br><br>We may kindly ask for a 30 minute grace period due to unforeseen traffic delays.<br>Appointment times may be rescheduled due to overwhelming demand.</p><p style='text-align: center; font-size: 18px;'>Log in to <a href='" . WEBSITE_URL . "' style='color: #016fd0'>MobileWash.com</a> to view your on-demand order options</p>";
                        $message .= "<p style='text-align: center; font-size: 16px; margin-bottom: 0; line-height: 22px;'>$15 cancellation fee will apply for canceling wash.</p>";

                        //Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
                        $to = Vargas::Obj()->getAdminToEmail();
                        $from = Vargas::Obj()->getAdminFromEmail();
                        Vargas::Obj()->SendMail($to, $from, $message, $subject, 'mail-receipt');
                        if ($customers_id_check->email)
                            Vargas::Obj()->SendMail($customers_id_check->email, $from, $message, $subject, 'mail-receipt');

                        if ((APP_ENV == 'real')) {
                            $this->layout = "xmlLayout";

                            //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                            $account_sid = TWILIO_SID;
                            $auth_token = TWILIO_AUTH_TOKEN;
                            $client = new Services_Twilio($account_sid, $auth_token);

                            $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id' => $customers_id_check->id, 'status' => 4), array('order' => 'id DESC'));
                            $last_order_days = "N/A";
                            if (count($cust_last_wash_check)) {
                                $current_time = strtotime(date('Y-m-d H:i:s'));

                                $last_order_time = strtotime($cust_last_wash_check->order_for);
                                if ($current_time > $last_order_time) {
                                    $last_order_days = floor(($current_time - $last_order_time) / (60 * 60 * 24));
                                }
                            } else {
                                $last_order_days = "N/A";
                            }


                            $message = "WASH NOW ATTEMPT #000" . $washrequestid . "- " . date('M d', strtotime($wash_details->created_date)) . " @ " . date('h:i A', strtotime($wash_details->created_date)) . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $address . " (" . $address_type . ") \r\nDays Since Last Order: " . $last_order_days . "\r\n------\r\n" . $mobile_receipt;


                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '8183313631',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }

                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '3109999334',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }

                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '3103442534',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }
                        }
                    }

                    /* ---------- add schedule info -------------- */

                    if ((isset($is_scheduled) && !empty($is_scheduled))) {



                        if ($schedule_total_ini)
                            Washingrequests::model()->updateByPk($washrequestid, array('schedule_date' => $schedule_date, 'schedule_time' => $schedule_time, 'order_for' => date("Y-m-d H:i:s", strtotime($schedule_date . " " . $schedule_time)), 'scheduled_cars_info' => $schedule_cars_info, 'schedule_total' => $schedule_total, 'schedule_total_ini' => $schedule_total_ini, 'schedule_total_vip' => $schedule_total_vip, 'schedule_company_total_vip' => $schedule_company_total_vip, 'schedule_company_total' => $schedule_company_total, 'schedule_agent_total' => $schedule_agent_total, 'coupon_discount' => $coupon_amount, 'coupon_code' => $coupon_code, 'vip_coupon_code' => $coupon_code_vip, 'tip_amount' => $tip_amount, 'wash_request_position' => $wash_request_position));
                        else
                            Washingrequests::model()->updateByPk($washrequestid, array('schedule_date' => $schedule_date, 'schedule_time' => $schedule_time, 'order_for' => date("Y-m-d H:i:s", strtotime($schedule_date . " " . $schedule_time)), 'scheduled_cars_info' => $schedule_cars_info, 'schedule_total' => $schedule_total, 'schedule_total_vip' => $schedule_total_vip, 'schedule_company_total_vip' => $schedule_company_total_vip, 'schedule_company_total' => $schedule_company_total, 'schedule_agent_total' => $schedule_agent_total, 'coupon_discount' => $coupon_amount, 'coupon_code' => $coupon_code, 'vip_coupon_code' => $coupon_code_vip, 'tip_amount' => $tip_amount, 'wash_request_position' => $wash_request_position));
                        $wash_details = Washingrequests::model()->findByPk($washrequestid);

                        $from = Vargas::Obj()->getAdminFromEmail();
                        $sched_date = '';
                        if (strtotime($wash_details->schedule_date) == strtotime(date('Y-m-d'))) {
                            $sched_date = 'Today';
                        } else {
                            $sched_date = date('M d', strtotime($wash_details->schedule_date));
                        }
                        $message = '';
                        $mobile_receipt = '';
                        $subject = 'Order Receipt - #0000' . $washrequestid;
                        //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                        $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is scheduled for " . $sched_date . " @ " . $wash_details->schedule_time . "</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $address . "</p>";
                        $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $washrequestid . "</td></tr>
					</table>";

                        $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";


                        foreach ($kartdata->vehicles as $ind => $vehicle) {
                            $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";
                            $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                            if ($vehicle->surge_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->extclaybar_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->waterspotremove_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                            }
                            if ($vehicle->exthandwax_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->pet_hair_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                            }
                            if ($vehicle->lifted_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->upholstery_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                            }

                            if ($vehicle->floormat_vehicle_fee > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee . "</p></td>
</tr>";
                                $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                            }

                            $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->safe_handling_fee . "</p></td>
</tr>";

                            if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo Discount (" . $coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format($coupon_amount, 2) . "</p></td>
</tr>";
                                $mobile_receipt .= "Promo: " . $coupon_code . " -$" . number_format($coupon_amount, 2) . "\r\n";
                            }


                            if ($vehicle->fifth_wash_discount > 0) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . $vehicle->fifth_wash_discount . "</p></td>
</tr>";
                                $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                            }

                            if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                                $mobile_receipt .= "Bundle -$1.00\r\n";
                            }

                            if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                                $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                                $mobile_receipt .= "Bundle -$1.00\r\n";
                            }



                            $mobile_receipt .= "------\r\n";
                            $message .= "</table>

</td>
</tr>";
                        }


                        if ($tip_amount) {
                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Tip</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($tip_amount, 2) . "</p></td>
							</tr></table>";
                            $mobile_receipt .= "Tip $" . number_format($tip_amount, 2) . "\r\n";
                        }

                        if ($wash_now_fee > 0) {

                            $mobile_receipt .= "Wash Now $" . number_format($wash_now_fee, 2) . "\r\n";

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Wash Now Fee</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($wash_now_fee, 2) . "</p></td>
							</tr></table>";
                        }

                        if ($wash_later_fee > 0) {

                            $mobile_receipt .= "Surge Fee $" . number_format($wash_later_fee, 2) . "\r\n";

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>Surge Fee</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($wash_later_fee, 2) . "</p></td>
							</tr></table>";
                        }

                        if ($wash_details->vip_coupon_code) {

                            $vip_coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $wash_details->vip_coupon_code));

                            $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 18px; margin: 0;'>" . $vip_coupon_check->package_name . " (" . $wash_details->vip_coupon_code . ")</p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'></p></td>
							</tr></table>";
                            $mobile_receipt .= $vip_coupon_check->package_name . "\r\n";
                        }


                        $message .= "</table>";

                        if ($wash_details->schedule_total) {
                            $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . ($wash_details->vip_coupon_code != '' ? $wash_details->schedule_total_vip : $wash_details->schedule_total) . "</span></p></td></tr></table>";
                            $mobile_receipt .= "Total: $" . $wash_details->schedule_total . "\r\n";
                        } else {
                            $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $kartdata->net_price . "</span></p></td></tr></table>";
                            $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
                        }

                        $mobile_receipt .= "Washes: " . $customer_total_wash . "\r\n";

                        $message .= "<p style='text-align: center; font-size: 14px; line-height: 22px; font-size: 16px; margin-top: 25px;'>Once you request a service, a pre-authorization charge is in place to ensure the credit card on file is valid. Please note that this charge will immediately be voided following the service request. Only the actual total will be charged at the end of the service.<br><br>We may kindly ask for a 30 minute grace period due to unforeseen traffic delays.<br>Appointment times may be rescheduled due to overwhelming demand.</p><p style='text-align: center; font-size: 18px;'>Log in to <a href='" . WEBSITE_URL . "' style='color: #016fd0'>MobileWash.com</a> to view your scheduled order options</p>";
                        $message .= "<p style='text-align: center; font-size: 16px; margin-bottom: 0; line-height: 22px;'>$15 cancellation fee will apply for canceling within 1 hour of your <br>scheduled wash time</p>";

                        //Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
                        $to = Vargas::Obj()->getAdminToEmail();
                        $from = Vargas::Obj()->getAdminFromEmail();
                        Vargas::Obj()->SendMail($to, $from, $message, $subject, 'mail-receipt');
                        if ($customers_id_check->email)
                            Vargas::Obj()->SendMail($customers_id_check->email, $from, $message, $subject, 'mail-receipt');


                        if ((APP_ENV == 'real')) {

                            $this->layout = "xmlLayout";

                            //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                            require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                            $account_sid = TWILIO_SID;
                            $auth_token = TWILIO_AUTH_TOKEN;
                            $client = new Services_Twilio($account_sid, $auth_token);

                            $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id' => $customers_id_check->id, 'status' => 4), array('order' => 'id DESC'));
                            $last_order_days = "N/A";
                            if (count($cust_last_wash_check)) {
                                $current_time = strtotime(date('Y-m-d H:i:s'));

                                $last_order_time = strtotime($cust_last_wash_check->order_for);
                                if ($current_time > $last_order_time) {
                                    $last_order_days = floor(($current_time - $last_order_time) / (60 * 60 * 24));
                                }
                            } else {
                                $last_order_days = "N/A";
                            }


                            $message = "NEW Scheduled Order #000" . $washrequestid . "- " . date('M d', strtotime($wash_details->schedule_date)) . " @ " . $wash_details->schedule_time . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $address . " (" . $address_type . ")\r\nDays Since Last Order: " . $last_order_days . "\r\n------\r\n" . $mobile_receipt;


                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '8183313631',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }

                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '3109999334',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }

                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => '3103442534',
                                    'From' => '+13103128070',
                                    'Body' => $message,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }
                        }



                        //$allagents = Agents::model()->findAll();
                    }

                    /* ---------- add schedule info -------------- */

                    if (AES256CBC_STATUS == 1) {
                        $washrequestid = $this->aes256cbc_crypt($washrequestid, 'e', AES256CBC_API_PASS);
                    }
                    $result = 'true';
                    $response = $washrequestid;
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Returns Washing Request.
     * * Post Required: customer id,CarList,PackageList
     * * Url:- http://www.demo.com/index.php?r=washing/editwashrequest
     * * Purpose:- Edit wash request
     */

    public function actioneditwashrequest() {
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

        if (Yii::app()->request->getParam('order_id') == '') {
            echo "Invalid Order ID.";
            die();
        }

        $order_id = Yii::app()->request->getParam('order_id');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $car_ids = Yii::app()->request->getParam('car_ids');
        $package_ids = Yii::app()->request->getParam('package_names');
        $estimate_time = Yii::app()->request->getParam('estimate_time');

        $pet_hair_vehicles = '';
        if (Yii::app()->request->getParam('pet_hair_vehicles'))
            $pet_hair_vehicles = Yii::app()->request->getParam('pet_hair_vehicles');

        $lifted_vehicles = '';
        if (Yii::app()->request->getParam('lifted_vehicles'))
            $lifted_vehicles = Yii::app()->request->getParam('lifted_vehicles');

        $exthandwax_vehicles = '';
        if (Yii::app()->request->getParam('exthandwax_vehicles'))
            $exthandwax_vehicles = Yii::app()->request->getParam('exthandwax_vehicles');

        $extplasticdressing_vehicles = '';
        if (Yii::app()->request->getParam('extplasticdressing_vehicles'))
            $extplasticdressing_vehicles = Yii::app()->request->getParam('extplasticdressing_vehicles');

        $extclaybar_vehicles = '';
        if (Yii::app()->request->getParam('extclaybar_vehicles'))
            $extclaybar_vehicles = Yii::app()->request->getParam('extclaybar_vehicles');

        $waterspotremove_vehicles = '';
        if (Yii::app()->request->getParam('waterspotremove_vehicles'))
            $waterspotremove_vehicles = Yii::app()->request->getParam('waterspotremove_vehicles');

        $upholstery_vehicles = '';
        if (Yii::app()->request->getParam('upholstery_vehicles'))
            $upholstery_vehicles = Yii::app()->request->getParam('upholstery_vehicles');

        $floormat_vehicles = '';
        if (Yii::app()->request->getParam('floormat_vehicles'))
            $floormat_vehicles = Yii::app()->request->getParam('floormat_vehicles');

        $fifth_wash_vehicles = '';
        if (Yii::app()->request->getParam('fifth_wash_vehicles'))
            $fifth_wash_vehicles = Yii::app()->request->getParam('fifth_wash_vehicles');

        $surge_price_vehicles = '';

        $json = array();
        $car_id_check = true;
        $washplan_id_check = true;
        $result = 'false';
        $response = "Pass the required parameters";
        $coupon_amount = 0;

        if (!$package_ids) {
            $json = array('result' => $result, 'response' => 'no packages');
            echo json_encode($json);
            die();
        }

        if (!$car_ids) {
            $json = array('result' => $result, 'response' => 'no cars');
            echo json_encode($json);
            die();
        }

        if (!$customer_id) {
            $json = array('result' => $result, 'response' => 'no customer id');
            echo json_encode($json);
            die();
        }

        if (!$order_id) {
            $json = array('result' => $result, 'response' => 'no order id');
            echo json_encode($json);
            die();
        }

        if (!$estimate_time) {
            $json = array('result' => $result, 'response' => 'no eta');
            echo json_encode($json);
            die();
        }

        // && intval($order_id) != 0
        if ((isset($order_id)) && (isset($customer_id) && !empty($customer_id)) && (isset($car_ids) && !empty($car_ids)) && (isset($package_ids) && !empty($package_ids)) && (isset($estimate_time) && !empty($estimate_time))) {


            if (AES256CBC_STATUS == 1) {
                $order_id = $this->aes256cbc_crypt($order_id, 'd', AES256CBC_API_PASS);
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            $car_ids_array = explode(",", $car_ids);
            foreach ($car_ids_array as $cid) {
                $car_id_exists = Vehicle::model()->findByAttributes(array("id" => $cid));
                if (!count($car_id_exists)) {
                    $car_id_check = false;
                    break;
                }
            }

            $washplan_ids_array = explode(",", $package_ids);
            foreach ($washplan_ids_array as $wid) {
                $washplan_id_exists = Washingplans::model()->findByAttributes(array("title" => $wid));
                if (!count($washplan_id_exists)) {
                    $washplan_id_check = false;
                    break;
                }
            }
            $wash_id_check = Washingrequests::model()->findByAttributes(array("id" => $order_id));
            $customers_id_check = Customers::model()->findByAttributes(array("id" => $customer_id));

            if (!count($wash_id_check)) {
                $response = 'Invalid wash id';
            } else if (!count($customers_id_check)) {
                $response = 'Invalid customer';
            } else if (!$car_id_check) {
                $response = 'Invalid vehicle id ' . $cid;
            } else if (!$washplan_id_check) {
                $response = 'Invalid washing plan ' . $wid;
            } else if (!$washplan_id_check) {
                $response = 'Invalid washing plan ' . $wid;
            } else if ($customers_id_check->id != $customer_id) {
                $response = 'Invalid customer';
            } else {

                foreach ($car_ids_array as $car) {
                    $carresetdata = array('status' => 0,
                        'eco_friendly' => 0,
                        'damage_points' => '',
                        'damage_pic' => '',
                        'upgrade_pack' => 0,
                        'edit_vehicle' => 0,
                        'remove_vehicle_from_kart' => 0,
                        'new_vehicle_confirm' => 0,
                        'new_pack_name' => '');

                    $vehiclemodel = new Vehicle;
                    $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                }

                $washrequestdata = array_filter(array('car_list' => $car_ids,
                    'package_list' => $package_ids,
                    'estimate_time' => $estimate_time));


                $update_washing_request = Yii::app()->db->createCommand("UPDATE washing_requests SET car_list=:car_list, package_list=:package_list, estimate_time=:estimate_time, updated_date=now() WHERE id = :id")
                        ->bindValue(':car_list', $car_ids, PDO::PARAM_STR)
                        ->bindValue(':package_list', $package_ids, PDO::PARAM_STR)
                        ->bindValue(':estimate_time', $estimate_time, PDO::PARAM_STR)
                        ->bindValue(':id', $order_id, PDO::PARAM_STR)
                        ->execute();
                if ($update_washing_request) {


                    $washrequestid = $order_id;
                    $result = 'true';
                    $response = $washrequestid;

                    /* ---------- insert addons / others -------------- */
                    $fifth_disc = 0;
                    if ($fifth_wash_vehicles)
                        $fifth_disc = 5;

                    if ($wash_id_check->coupon_code) {
                        $coupon_check = CouponCodes::model()->findByAttributes(array("coupon_code" => $wash_id_check->coupon_code));
                        if (count($coupon_check)) {
                            if (strpos($package_ids, 'Premium') !== false) {
                                $coupon_amount = number_format($coupon_check->premium_amount, 2, '.', '');
                            } else {
                                $coupon_amount = number_format($coupon_check->deluxe_amount, 2, '.', '');
                            }
                        }

                        $fifth_wash_vehicles = '';
                        $fifth_disc = 0;
                    }

                    Washingrequests::model()->updateByPk($washrequestid, array('pet_hair_vehicles' => $pet_hair_vehicles,
                        'lifted_vehicles' => $lifted_vehicles,
                        'exthandwax_vehicles' => $exthandwax_vehicles,
                        'extplasticdressing_vehicles' => $extplasticdressing_vehicles,
                        'extclaybar_vehicles' => $extclaybar_vehicles,
                        'waterspotremove_vehicles' => $waterspotremove_vehicles,
                        'upholstery_vehicles' => $upholstery_vehicles,
                        'floormat_vehicles' => $floormat_vehicles,
                        'fifth_wash_vehicles' => $fifth_wash_vehicles,
                        'fifth_wash_discount' => $fifth_disc,
                        'coupon_discount' => $coupon_amount
                    ));
                    $car_arr = explode(",", $car_ids);
                    $car_packs = explode(",", $package_ids);
                    //WashPricingHistory::model()->updateAll(array('status'=>1),'wash_request_id="'.$washrequestid.'"');
                    WashPricingHistory::model()->updateAll(array('status' => 1), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $washrequestid));

                    $wash_details = Washingrequests::model()->findByPk($washrequestid);
                    $kartapiresult = $this->washingkart($washrequestid, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                    $kartdata = json_decode($kartapiresult);
                    if ($wash_details->net_price != $kartdata->net_price)
                        WashPricingHistory::model()->deleteAll("wash_request_id=" . $washrequestid);
                    else
                        WashPricingHistory::model()->updateAll(array('status' => 0), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $washrequestid));
                    foreach ($kartdata->vehicles as $ind => $car) {
                        $veh_detail = Vehicle::model()->findByPk($car->id);

                        $pet_fee = 0;
                        $lift_fee = 0;
                        $exthandwax_fee = 0;
                        $extplasticdressing_fee = 0;
                        $extclaybar_fee = 0;
                        $waterspotremove_fee = 0;
                        $upholstery_fee = 0;
                        $floormat_fee = 0;
                        $surge_fee = 0;


                        $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles);
                        if (in_array($car->id, $pet_hair_vehicles_arr))
                            $pet_fee = 10;

                        $lifted_vehicles_arr = explode(",", $lifted_vehicles);
                        if (in_array($car->id, $lifted_vehicles_arr))
                            $lift_fee = 10;

                        $exthandwax_addon_arr = explode(",", $exthandwax_vehicles);
                        if (in_array($car->id, $exthandwax_addon_arr))
                            $exthandwax_fee = 12;

                        $extplasticdressing_addon_arr = explode(",", $extplasticdressing_vehicles);
                        if (in_array($car->id, $extplasticdressing_addon_arr))
                            $extplasticdressing_fee = 8;

                        $extclaybar_addon_arr = explode(",", $extclaybar_vehicles);
                        if (in_array($car->id, $extclaybar_addon_arr))
                            $extclaybar_fee = 35;

                        $waterspotremove_addon_arr = explode(",", $waterspotremove_vehicles);
                        if (in_array($car->id, $waterspotremove_addon_arr))
                            $waterspotremove_fee = 30;

                        $upholstery_addon_arr = explode(",", $upholstery_vehicles);
                        if (in_array($car->id, $upholstery_addon_arr))
                            $upholstery_fee = 20;

                        $floormat_addon_arr = explode(",", $floormat_vehicles);
                        if (in_array($car->id, $floormat_addon_arr))
                            $floormat_fee = 10;



                        Vehicle::model()->updateByPk($car->id, array('pet_hair' => $pet_fee, 'lifted_vehicle' => $lift_fee, 'exthandwax_addon' => $exthandwax_fee, 'extplasticdressing_addon' => $extplasticdressing_fee, 'extclaybar_addon' => $extclaybar_fee, 'waterspotremove_addon' => $waterspotremove_fee, 'upholstery_addon' => $upholstery_fee, 'floormat_addon' => $floormat_fee, 'surge_addon' => $surge_fee));

                        if ($wash_details->net_price != $kartdata->net_price) {
                            /* --------- car pricing save --------- */

                            $washpricehistorymodel = new WashPricingHistory;
                            $washpricehistorymodel->wash_request_id = $washrequestid;
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

                    $surge_price_vehicles = rtrim($surge_price_vehicles, ',');
                    Washingrequests::model()->updateByPk($washrequestid, array('surge_price_vehicles' => $surge_price_vehicles));
                    /* ---------- insert addons end -------------- */

                    /* ----------- update pricing details -------------- */

                    $cust_details = Customers::model()->findByAttributes(array('id' => $wash_details->customer_id));
                    Washingrequests::model()->updateByPk($washrequestid, array('total_price' => $kartdata->total_price, 'net_price' => $kartdata->net_price, 'company_total' => $kartdata->company_total, 'agent_total' => $kartdata->agent_total, 'bundle_discount' => $kartdata->bundle_discount, 'first_wash_discount' => $kartdata->first_wash_discount, 'coupon_discount' => $kartdata->coupon_discount, 'customer_wash_points' => $cust_details->fifth_wash_points));

                    /* ----------- update pricing details end -------------- */

                    if (AES256CBC_STATUS == 1) {
                        $washrequestid = $this->aes256cbc_crypt($washrequestid, 'e', AES256CBC_API_PASS);
                    }
                    $result = 'true';
                    $response = $washrequestid;
                }
            }
        }

        $json = array('result' => $result, 'response' => $response);
        echo json_encode($json);
        die();
    }

    public function actioncustomereditscheduleorder() {
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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');
        $address = Yii::app()->request->getParam('address');
        $street_name = Yii::app()->request->getParam('street_name');
        $order_city = Yii::app()->request->getParam('order_city');
        $order_state = Yii::app()->request->getParam('order_state');
        $order_zipcode = Yii::app()->request->getParam('order_zipcode');
        $address_type = Yii::app()->request->getParam('address_type');
        $latitude = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $schedule_date = Yii::app()->request->getParam('schedule_date');
        $schedule_time = Yii::app()->request->getParam('schedule_time');
        $ondemand_10_min_cancel_schedule = Yii::app()->request->getParam('ondemand_10_min_cancel_schedule');
        $is_rescheduled = Yii::app()->request->getParam('is_rescheduled');
        $dateChack = Yii::app()->request->getParam('ScheduleDateVal');

        if ($dateChack == "tomorrow") {
            $datetime = new DateTime('tomorrow');
            $schedule_date = $datetime->format('Y-m-d');
        }
        if ($dateChack == "today") {
            $schedule_date = $currentDate = Date("Y-m-d");
        }

        $wash_now_reschedule = 0;
        if (Yii::app()->request->getParam('wash_now_reschedule'))
            $wash_now_reschedule = Yii::app()->request->getParam('wash_now_reschedule');

        $is_reschedule = 0;
        if (Yii::app()->request->getParam('is_reschedule'))
            $is_reschedule = Yii::app()->request->getParam('is_reschedule');
        $wash_later_fee = 0;
        if (Yii::app()->request->getParam('wash_later_fee'))
            $wash_later_fee = Yii::app()->request->getParam('wash_later_fee');
        $mobile_receipt = '';

        $json = array();

        $result = 'false';
        $response = "Pass the required parameters";

        if ((isset($wash_request_id) && !empty($wash_request_id))) {

            if (AES256CBC_STATUS == 1) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $wash_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));

            if (!count($wash_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {

                if (!is_numeric($status)) {
                    $status = $wash_id_check->status;
                }

                if (!$address) {
                    $address = $wash_id_check->address;
                }

                if (!$street_name) {
                    $street_name = $wash_id_check->street_name;
                }

                if (!$order_city) {
                    $order_city = $wash_id_check->city;
                }

                if (!$order_state) {
                    $order_state = $wash_id_check->state;
                }

                if (!$order_zipcode) {
                    $order_zipcode = $wash_id_check->zipcode;
                }

                if (!$address_type) {
                    $address_type = $wash_id_check->address_type;
                }

                if (!$latitude) {
                    $latitude = $wash_id_check->latitude;
                }

                if (!$longitude) {
                    $longitude = $wash_id_check->longitude;
                }

                if (!$schedule_date) {
                    $schedule_date = $wash_id_check->schedule_date;
                }

                if (!$schedule_time) {
                    $schedule_time = $wash_id_check->schedule_time;
                }

                $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings`")->queryAll();
                if (count($app_settings)) {

                    //if(is_numeric($app_settings[0]['wash_later_fee'])) $wash_later_fee = $app_settings[0]['wash_later_fee'];
                    $wash_now_fee = 0;
                    $current_day = date('l', strtotime($schedule_date));
                    $schedule_times = Yii::app()->db->createCommand()->select('*')->from('schedule_times')->where('id=1')->queryAll();
                    if ($current_day == 'Monday')
                        $times = $schedule_times[0]['mon'];
                    if ($current_day == 'Tuesday')
                        $times = $schedule_times[0]['tue'];
                    if ($current_day == 'Wednesday')
                        $times = $schedule_times[0]['wed'];
                    if ($current_day == 'Thursday')
                        $times = $schedule_times[0]['thurs'];
                    if ($current_day == 'Friday')
                        $times = $schedule_times[0]['fri'];
                    if ($current_day == 'Saturday')
                        $times = $schedule_times[0]['sat'];
                    if ($current_day == 'Sunday')
                        $times = $schedule_times[0]['sun'];

                    $times_arr = explode("|", $times);

                    foreach ($times_arr as $time) {
                        $time_detail = explode(",", $time);
                        //if($time_detail[1] == 'inactive') continue;
                        $start = strtotime($current_date . " " . $time_detail[0]);
                        $end = strtotime($current_date . " " . $time_detail[0] . " +14 minutes 59 seconds");


                        if (strtotime($schedule_time) >= $start && strtotime($schedule_time) <= $end) {
                            $wash_now_fee = $time_detail[2];
                            $wash_unavailable = 0;
                            if ($time_detail[1] == 'inactive')
                                $wash_unavailable = 1;
                            break;
                        }
                    }

                    //$wash_later_fee = $wash_now_fee;
                }

                $order_for_date = date("Y-m-d H:i:s", strtotime($schedule_date . " " . $schedule_time));

                if ($ondemand_10_min_cancel_schedule == 1) {
                    if (($is_rescheduled == 1) || ($is_reschedule == 1))
                        Washingrequests::model()->updateByPk($wash_request_id, array('reschedule_date' => $schedule_date, 'reschedule_time' => $schedule_time, 'status' => $status, 'address' => $address, 'street_name' => $street_name, 'city' => $order_city, 'state' => $order_state, 'zipcode' => $order_zipcode, 'address_type' => $address_type, 'latitude' => $latitude, 'longitude' => $longitude, 'is_scheduled' => 1, 'is_create_schedulewash_push_sent' => 0, 'wash_now_fee' => 0, 'wash_later_fee' => $wash_later_fee, 'order_for' => $order_for_date, 'order_temp_assigned' => 0, 'agent_reject_ids' => '', 'all_reject_ids' => '', 'is_two_loops_reject' => 0, 'no_washer_cancel' => 0));
                    else
                        Washingrequests::model()->updateByPk($wash_request_id, array('schedule_date' => $schedule_date, 'schedule_time' => $schedule_time, 'status' => $status, 'address' => $address, 'street_name' => $street_name, 'city' => $order_city, 'state' => $order_state, 'zipcode' => $order_zipcode, 'address_type' => $address_type, 'latitude' => $latitude, 'longitude' => $longitude, 'is_scheduled' => 1, 'is_create_schedulewash_push_sent' => 0, 'wash_now_fee' => 0, 'wash_later_fee' => $wash_later_fee, 'order_for' => $order_for_date, 'order_temp_assigned' => 0, 'agent_reject_ids' => '', 'all_reject_ids' => '', 'is_two_loops_reject' => 0, 'no_washer_cancel' => 0));
                }
                else {
                    if (($is_rescheduled == 1) || ($is_reschedule == 1))
                        Washingrequests::model()->updateByPk($wash_request_id, array('reschedule_date' => $schedule_date, 'reschedule_time' => $schedule_time, 'status' => $status, 'address' => $address, 'street_name' => $street_name, 'city' => $order_city, 'state' => $order_state, 'zipcode' => $order_zipcode, 'address_type' => $address_type, 'latitude' => $latitude, 'longitude' => $longitude, 'is_scheduled' => 1, 'is_create_schedulewash_push_sent' => 0, 'wash_now_fee' => 0, 'wash_later_fee' => $wash_later_fee, 'order_for' => $order_for_date));
                    else
                        Washingrequests::model()->updateByPk($wash_request_id, array('schedule_date' => $schedule_date, 'schedule_time' => $schedule_time, 'status' => $status, 'address' => $address, 'street_name' => $street_name, 'city' => $order_city, 'state' => $order_state, 'zipcode' => $order_zipcode, 'address_type' => $address_type, 'latitude' => $latitude, 'longitude' => $longitude, 'is_scheduled' => 1, 'is_create_schedulewash_push_sent' => 0, 'wash_now_fee' => 0, 'wash_later_fee' => $wash_later_fee, 'order_for' => $order_for_date));
                }

                WashPricingHistory::model()->updateAll(array('status' => 1), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));

                $wash_details = Washingrequests::model()->findByPk($wash_request_id);
                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);
                if ($wash_details->net_price != $kartdata->net_price)
                    WashPricingHistory::model()->deleteAll("wash_request_id = :wash_request_id", array(':wash_request_id' => $wash_request_id));
                else
                    WashPricingHistory::model()->updateAll(array('status' => 0), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));

                foreach ($kartdata->vehicles as $ind => $car) {
                    $veh_detail = Vehicle::model()->findByPk($car->id);

                    $pet_fee = 0;
                    $lift_fee = 0;
                    $exthandwax_fee = 0;
                    $extplasticdressing_fee = 0;
                    $extclaybar_fee = 0;
                    $waterspotremove_fee = 0;
                    $upholstery_fee = 0;
                    $floormat_fee = 0;
                    $surge_fee = 0;


                    $pet_hair_vehicles_arr = explode(",", $pet_hair_vehicles);
                    if (in_array($car->id, $pet_hair_vehicles_arr))
                        $pet_fee = 10;

                    $lifted_vehicles_arr = explode(",", $lifted_vehicles);
                    if (in_array($car->id, $lifted_vehicles_arr))
                        $lift_fee = 10;

                    $exthandwax_addon_arr = explode(",", $exthandwax_vehicles);
                    if (in_array($car->id, $exthandwax_addon_arr))
                        $exthandwax_fee = 12;

                    $extplasticdressing_addon_arr = explode(",", $extplasticdressing_vehicles);
                    if (in_array($car->id, $extplasticdressing_addon_arr))
                        $extplasticdressing_fee = 8;

                    $extclaybar_addon_arr = explode(",", $extclaybar_vehicles);
                    if (in_array($car->id, $extclaybar_addon_arr))
                        $extclaybar_fee = 35;

                    $waterspotremove_addon_arr = explode(",", $waterspotremove_vehicles);
                    if (in_array($car->id, $waterspotremove_addon_arr))
                        $waterspotremove_fee = 30;

                    $upholstery_addon_arr = explode(",", $upholstery_vehicles);
                    if (in_array($car->id, $upholstery_addon_arr))
                        $upholstery_fee = 20;

                    $floormat_addon_arr = explode(",", $floormat_vehicles);
                    if (in_array($car->id, $floormat_addon_arr))
                        $floormat_fee = 10;



                    Vehicle::model()->updateByPk($car->id, array('pet_hair' => $pet_fee, 'lifted_vehicle' => $lift_fee, 'exthandwax_addon' => $exthandwax_fee, 'extplasticdressing_addon' => $extplasticdressing_fee, 'extclaybar_addon' => $extclaybar_fee, 'waterspotremove_addon' => $waterspotremove_fee, 'upholstery_addon' => $upholstery_fee, 'floormat_addon' => $floormat_fee, 'surge_addon' => $surge_fee));

                    if ($wash_details->net_price != $kartdata->net_price) {
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

                    $mobile_receipt .= $car->brand_name . " " . $car->model_name . "\r\n" . $car->vehicle_washing_package . " $" . $car->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                    if ($car->surge_vehicle_fee > 0) {
                        $mobile_receipt .= "Surge $" . $car->surge_vehicle_fee . "\r\n";
                    }
                    if ($car->extclaybar_vehicle_fee > 0) {

                        $mobile_receipt .= "Clay $" . $car->extclaybar_vehicle_fee . "\r\n";
                    }
                    if ($car->waterspotremove_vehicle_fee > 0) {

                        $mobile_receipt .= "Spot $" . $car->waterspotremove_vehicle_fee . "\r\n";
                    }
                    if ($car->exthandwax_vehicle_fee > 0) {

                        $mobile_receipt .= "Wax $" . $car->exthandwax_vehicle_fee . "\r\n";
                    }

                    if ($car->pet_hair_fee > 0) {

                        $mobile_receipt .= "Extra Cleaning $" . $car->pet_hair_fee . "\r\n";
                    }
                    if ($car->lifted_vehicle_fee > 0) {

                        $mobile_receipt .= "Lifted $" . $car->lifted_vehicle_fee . "\r\n";
                    }

                    if ($car->extplasticdressing_vehicle_fee > 0) {

                        $mobile_receipt .= "Dressing $" . $car->extplasticdressing_vehicle_fee . "\r\n";
                    }

                    if ($car->upholstery_vehicle_fee > 0) {

                        $mobile_receipt .= "Upholstery $" . $car->upholstery_vehicle_fee . "\r\n";
                    }

                    if ($car->floormat_vehicle_fee > 0) {

                        $mobile_receipt .= "Floormat $" . $car->floormat_vehicle_fee . "\r\n";
                    }


                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {

                        $mobile_receipt .= "Promo: " . $kartdata->coupon_code . " -$" . number_format($kartdata->coupon_discount, 2) . "\r\n";
                    }


                    if ($car->fifth_wash_discount > 0) {

                        $mobile_receipt .= "5th -$" . number_format($car->fifth_wash_discount, 2) . "\r\n";
                    }

                    if (($car->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }

                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }

                    $mobile_receipt .= "------\r\n";
                }



                if ($kartdata->tip_amount > 0) {

                    $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
                }

                if ($kartdata->wash_now_fee > 0) {

                    $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
                }

                if ($kartdata->wash_later_fee > 0) {

                    $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
                }


                $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";

                Washingrequests::model()->updateByPk($wash_request_id, array('agent_total' => $kartdata->agent_total));




                if (($wash_now_reschedule == 1) && (APP_ENV == 'real')) {
                    //if(($wash_now_reschedule == 1)){
                    $this->layout = "xmlLayout";


                    //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                    $account_sid = TWILIO_SID;
                    $auth_token = TWILIO_AUTH_TOKEN;

                    $client = new Services_Twilio($account_sid, $auth_token);
                    $wash_details = Washingrequests::model()->findByPk($wash_request_id);
                    $customers_id_check = Customers::model()->findByAttributes(array("id" => $wash_details->customer_id));

                    $smscontent = "WASH NOW SCHEDULED #000" . $wash_request_id . "- " . date('M d', strtotime($wash_details->order_for)) . " @ " . date('h:i A', strtotime($wash_details->order_for)) . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $wash_details->address . "\r\n (" . $wash_details->address_type . ")\r\n------\r\n" . $mobile_receipt;


                    try {
                        $sendmessage = $client->account->messages->create(array(
                            'To' => '8183313631',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }

                    try {

                        $sendmessage = $client->account->messages->create(array(
                            'To' => '3109999334',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }

                    try {

                        $sendmessage = $client->account->messages->create(array(
                            'To' => '3103442534',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }
                }

                if (($wash_now_reschedule == 1)) {
                    $logdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'clientwashnowschedule',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                }

                if (($is_reschedule == 1)) {
                    $logdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'customerreschedule',
                        'addi_detail' => date('n/d h:i A', strtotime($wash_id_check->schedule_date . " " . $wash_id_check->schedule_time)) . " to " . date('n/d h:i A', strtotime($schedule_date . " " . $schedule_time)),
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
                }



                $result = 'true';
                $response = 'order updated';
            }
        }

        $json = array('result' => $result, 'response' => $response);
        echo json_encode($json);
        die();
    }

    /*
     * * Returns Pending Washing Requests.
     * * Post Required: none
     * * Url:- http://www.demo.com/index.php?r=washing/pendingwashrequests
     * * Purpose:- Pending Washing Requests
     */

    public function actionpendingwashrequests() {

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

        $json = array();

        $result = 'true';
        $response = 'Pending wash requests';
        $pendingwashrequests = array();
        $last_cust_id = '';
        $last_cust_lat = '';
        $last_cust_lng = '';
        $qrRequests = Yii::app()->db->createCommand()
                ->select('*')->from('washing_requests')
                ->where("status='0'", array())
                ->queryAll();

        if (count($qrRequests) > 0) {

            foreach ($qrRequests as $wrequest) {
                $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $wrequest['customer_id']));
                $total_rate = count($cust_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($cust_feedbacks as $cust_feedback) {
                        $rate += $cust_feedback->customer_ratings;
                    }

                    $cust_rate = round($rate / $total_rate);
                } else {
                    $cust_rate = 0;
                }

                $cust_details = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));

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

                $pendingwashrequests[] = array('id' => $wrequest['id'],
                    'customer_id' => $wrequest['customer_id'],
                    'customer_name' => $customername,
                    'customer_email' => $cust_details->email,
                    'customer_phoneno' => $cust_details->contact_number,
                    'customer_photo' => $cust_details->image,
                    'customer_rating' => $cust_details->rating,
                    'car_list' => $wrequest['car_list'],
                    'package_list' => $wrequest['package_list'],
                    'address' => $wrequest['address'],
                    'address_type' => $wrequest['address_type'],
                    'latitude' => $wrequest['latitude'],
                    'longitude' => $wrequest['longitude'],
                    'payment_type' => $wrequest['payment_type'],
                    'nonce' => $wrequest['nonce'],
                    'estimate_time' => $wrequest['estimate_time'],
                    'status' => $wrequest['status'],
                );

                $last_cust_id = $wrequest['customer_id'];
                $last_cust_lat = $wrequest['latitude'];
                $last_cust_lng = $wrequest['longitude'];
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'pending_wash_requests' => $pendingwashrequests
        );

        echo json_encode($json);
        die();
    }

    public function actionlatestwashrequestbyclientid() {

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

        $customer_id = Yii::app()->request->getParam('customer_id');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        $wash_details = new stdClass();
        if ((isset($customer_id) && !empty($customer_id))) {


            $cust_id_check = Customers::model()->findByAttributes(array('id' => $customer_id));

            if (!count($cust_id_check)) {
                $result = 'false';
                $response = 'Invalid customer';
            } else {
                $result = 'true';
                $response = 'Latest wash request details';
                /* $wrequest_id_check = Washingrequests::model()->findByAttributes(array('customer_id'=>$customer_id), array('order'=>'created_date DESC')); */

                $wrequest_id_check = Washingrequests::model()->findAll(array("condition" => "status < 4 AND customer_id=:customer_id", 'params' => array(':customer_id' => $customer_id), 'order' => 'created_date desc'));

                $customer_id_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $customer_id));

                $total_rate = count($customer_id_feedbacks);
                if ($total_rate) {
                    $rate = 0;
                    foreach ($customer_id_feedbacks as $customer_feedback) {
                        $rate += $customer_feedback->agent_ratings;
                    }

                    $customer_rate = round($rate / $total_rate);
                } else {
                    $customer_rate = 0;
                }

                if (count($wrequest_id_check)) {
                    $wash_details->id = $wrequest_id_check[0]->id;
                    $wash_details->customer_id = $wrequest_id_check[0]->customer_id;
                    $wash_details->agent_id = $wrequest_id_check[0]->agent_id;
                    $wash_details->car_list = $wrequest_id_check[0]->car_list;
                    $wash_details->package_list = $wrequest_id_check[0]->package_list;
                    $wash_details->address = $wrequest_id_check[0]->address;
                    $wash_details->address_type = $wrequest_id_check[0]->address_type;
                    $wash_details->latitude = $wrequest_id_check[0]->latitude;
                    $wash_details->longitude = $wrequest_id_check[0]->longitude;
                    $wash_details->payment_type = $wrequest_id_check[0]->payment_type;
                    $wash_details->estimate_time = $wrequest_id_check[0]->estimate_time;
                    $wash_details->status = $wrequest_id_check[0]->status;
                    $wash_details->created_date = $wrequest_id_check[0]->created_date;
                    $wash_details->total_price = $wrequest_id_check[0]->total_price;
                    $wash_details->net_price = $wrequest_id_check[0]->net_price;
                    $wash_details->company_total = $wrequest_id_check[0]->company_total;
                    $wash_details->agent_total = $wrequest_id_check[0]->agent_total;
                    $wash_details->bundle_discount = $wrequest_id_check[0]->bundle_discount;
                    $wash_details->fifth_wash_discount = $wrequest_id_check[0]->fifth_wash_discount;
                    $wash_details->first_wash_discount = $wrequest_id_check[0]->first_wash_discount;
                    $wash_details->coupon_discount = $wrequest_id_check[0]->coupon_discount;
                    $wash_details->cancel_fee = $wrequest_id_check[0]->cancel_fee;
                    $wash_details->scheduled_cars = $wrequest_id_check[0]->scheduled_cars_info;
                    $wash_details->is_scheduled = $wrequest_id_check[0]->is_scheduled;
                    $wash_details->schedule_total = $wrequest_id_check[0]->schedule_total;
                    $wash_details->tip_amount = $wrequest_id_check[0]->tip_amount;
                    $wash_details->schedule_date = $wrequest_id_check[0]->schedule_date;
                    $wash_details->schedule_time = $wrequest_id_check[0]->schedule_time;
                    $wash_details->reschedule_date = $wrequest_id_check[0]->reschedule_date;
                    $wash_details->reschedule_time = $wrequest_id_check[0]->reschedule_time;
                    $wash_details->coupon_code = $wrequest_id_check[0]->coupon_code;
                    $wash_details->coupon_discount = $wrequest_id_check[0]->coupon_discount;
                    $wash_details->cancel_fee = $wrequest_id_check[0]->cancel_fee;
                } else {
                    $result = 'false';
                    $response = 'no latest wash found';

                    $json = array(
                        'result' => $result,
                        'response' => $response
                    );
                    echo json_encode($json);
                    die();
                }
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }


        if (($cust_id_check->first_name != '') && ($cust_id_check->last_name != '')) {
            $customername = '';
            $cust_name = explode(" ", trim($cust_id_check->last_name));
            $customername = $cust_id_check->first_name . " " . strtoupper(substr($cust_name[0], 0, 1)) . ".";
        } else {
            $customername = '';
            $cust_name = explode(" ", trim($cust_id_check->customername));
            if (count($cust_name > 1))
                $customername = $cust_name[0] . " " . strtoupper(substr($cust_name[1], 0, 1)) . ".";
            else
                $customername = $cust_name[0];
        }

        $customername = strtolower($customername);
        $customername = ucwords($customername);

        $json = array(
            'result' => $result,
            'response' => $response,
            'wash_details' => $wash_details,
            'name' => $customername,
            'fullname' => $cust_id_check->first_name . " " . $cust_id_check->last_name,
            'image' => $cust_id_check->image,
            'email' => $cust_id_check->email,
            'rating' => $customer_rate,
        );
        echo json_encode($json);
    }

    /*
     * * Returns Changed wash request status.
     * * Post Required: agent id,wash request id,status
     * * Url:- http://www.demo.com/index.php?r=washing/updatewashrequeststatus
     * * Purpose:- Change wash request status
     */

    public function actionupdatewashrequeststatus() {
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
        $washer_penalty_fee = Yii::app()->request->getParam('washer_penalty_fee');
        $admin_permit = Yii::app()->request->getParam('admin_permit');
        $washer_ondemand_job_accept = 0;
        if (Yii::app()->request->getParam('washer_ondemand_job_accept'))
            $washer_ondemand_job_accept = Yii::app()->request->getParam('washer_ondemand_job_accept');
        $washer_arrive_hit = 0;
        if (Yii::app()->request->getParam('washer_arrive_hit'))
            $washer_arrive_hit = Yii::app()->request->getParam('washer_arrive_hit');
        $washer_arrive_hit = 0;
        if (Yii::app()->request->getParam('washer_arrive_hit'))
            $washer_arrive_hit = Yii::app()->request->getParam('washer_arrive_hit');
        $meet_washer_outside = '';
        if (Yii::app()->request->getParam('meet_washer_outside'))
            $meet_washer_outside = Yii::app()->request->getParam('meet_washer_outside');
        $meet_washer_outside_washend = '';
        if (Yii::app()->request->getParam('meet_washer_outside_washend'))
            $meet_washer_outside_washend = Yii::app()->request->getParam('meet_washer_outside_washend');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $admin_username = '';
        if (Yii::app()->request->getParam('admin_username'))
            $admin_username = Yii::app()->request->getParam('admin_username');
        $save_coupon = 0;
        if (Yii::app()->request->getParam('save_coupon'))
            $save_coupon = Yii::app()->request->getParam('save_coupon');
        $remove_coupon = 0;
        if (Yii::app()->request->getParam('remove_coupon'))
            $remove_coupon = Yii::app()->request->getParam('remove_coupon');
        $coupon_code = '';
        if (Yii::app()->request->getParam('coupon_code'))
            $coupon_code = Yii::app()->request->getParam('coupon_code');
        $coupon_amount = '';
        if (Yii::app()->request->getParam('coupon_amount'))
            $coupon_amount = Yii::app()->request->getParam('coupon_amount');
        $fifth_wash_discount = 0;
        if (Yii::app()->request->getParam('fifth_wash_discount'))
            $fifth_wash_discount = Yii::app()->request->getParam('fifth_wash_discount');
        $fifth_wash_vehicle = '';
        if (Yii::app()->request->getParam('fifth_wash_vehicle'))
            $fifth_wash_vehicle = Yii::app()->request->getParam('fifth_wash_vehicle');

        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();

        if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
            if ($agent_id)
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }

        $agent_detail = Agents::model()->findByAttributes(array("id" => $agent_id));
        $order_for_date = '';

        if ($save_coupon == 1) {
            Washingrequests::model()->updateByPk($wash_request_id, array("coupon_code" => $coupon_code, "coupon_discount" => $coupon_amount, "fifth_wash_discount" => 0, "fifth_wash_vehicles" => ''));

            $json = array('result' => 'true',
                'response' => 'coupon updated');

            echo json_encode($json);
            die();
        }

        if ($remove_coupon == 1) {
            Washingrequests::model()->updateByPk($wash_request_id, array("coupon_code" => $coupon_code, "coupon_discount" => $coupon_amount, "fifth_wash_discount" => $fifth_wash_discount, "fifth_wash_vehicles" => $fifth_wash_vehicle));

            $json = array('result' => 'true',
                'response' => 'coupon removed');

            echo json_encode($json);
            die();
        }

        if ($meet_washer_outside) {
            $old_meet_washer_outside = Yii::app()->db->createCommand("SELECT meet_washer_outside FROM washing_requests WHERE id = :id LIMIT 1")->bindValue(':id', $wash_request_id, PDO::PARAM_STR)->queryAll();
            if ($meet_washer_outside != $old_meet_washer_outside[0]['meet_washer_outside']) {
                Washingrequests::model()->updateByPk($wash_request_id, array("meet_washer_outside" => $meet_washer_outside, "meet_or_nomeet_washer_outside_at" => date('Y-m-d H:i:s')));

                $logdata = array(
                    'agent_id' => $agent_id,
                    'wash_request_id' => $wash_request_id,
                    'agent_company_id' => $agent_detail->real_washer_id,
                    'action' => 'meetwasherbeforeinspect',
                    'addi_detail' => $meet_washer_outside,
                    'action_date' => date('Y-m-d H:i:s'));

                Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
            }
        }

        if ($meet_washer_outside_washend) {
            $wash_detail = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            $agent_detail = Agents::model()->findByAttributes(array("id" => $wash_detail->agent_id));
            Washingrequests::model()->updateByPk($wash_request_id, array("meet_washer_outside_washend" => $meet_washer_outside_washend));
            if ($wash_detail->meet_washer_outside_washend != $meet_washer_outside_washend) {
                $logdata = array(
                    'agent_id' => $wash_detail->agent_id,
                    'wash_request_id' => $wash_request_id,
                    'agent_company_id' => $agent_detail->real_washer_id,
                    'action' => 'meetwasherwashend',
                    'addi_detail' => $meet_washer_outside_washend,
                    'action_date' => date('Y-m-d H:i:s'));

                Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
            }
            $json = array('result' => 'true',
                'response' => 'status updated');

            echo json_encode($json);
            die();
        }

        if (count($agent_detail) && $agent_detail->block_washer) {
            $json = array('result' => 'false',
                'response' => 'Account error. Please contact MobileWash.');

            echo json_encode($json);
            die();
        }

        if ($washer_ondemand_job_accept == 1) {
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            $cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);
            $mobile_receipt = '';
            if ($wrequest_id_check->status == 5) {
                $json = array('result' => 'false',
                    'response' => 'Request is already canceled by customer');

                echo json_encode($json);
                die();
            }

            if (($wrequest_id_check->agent_id != 0) && ($wrequest_id_check->agent_id != $agent_id)) {
                $result = 'false';
                $response = 'Sorry, this order is already assigned to another washer';

                //file_put_contents("ondemandtwowasher_log", $wash_request_id." ".$agent_id . "\r\n", FILE_APPEND);

                Yii::app()->db->createCommand("DELETE FROM activity_logs WHERE agent_id = :agent_id AND wash_request_id = :wash_request_id AND action = 'washerstartjob' ORDER BY action_date DESC LIMIT 1")
                        ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                        ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                        ->execute();

                $json = array('result' => $result, 'response' => $response);
                echo json_encode($json);
                die();
            }

            if (!$wrequest_id_check->is_scheduled) {
                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);

                foreach ($kartdata->vehicles as $ind => $vehicle) {
                    $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                    if ($vehicle->surge_vehicle_fee > 0) {
                        $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->extclaybar_vehicle_fee > 0) {

                        $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->waterspotremove_vehicle_fee > 0) {

                        $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->exthandwax_vehicle_fee > 0) {

                        $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->pet_hair_fee > 0) {

                        $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                    }
                    if ($vehicle->lifted_vehicle_fee > 0) {

                        $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->extplasticdressing_vehicle_fee > 0) {

                        $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->upholstery_vehicle_fee > 0) {

                        $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->floormat_vehicle_fee > 0) {

                        $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                    }

                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {

                        $mobile_receipt .= "Promo: " . $kartdata->coupon_code . " -$" . number_format($kartdata->coupon_discount, 2) . "\r\n";
                    }


                    if ($vehicle->fifth_wash_discount > 0) {

                        $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                    }

                    if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }

                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }
                    $mobile_receipt .= "------\r\n";
                }

                if ($kartdata->tip_amount > 0) {
                    $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
                }

                if ($kartdata->wash_now_fee > 0) {
                    $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
                }

                if ($kartdata->wash_later_fee > 0) {
                    $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
                }


                $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
            }

            Washingrequests::model()->updateByPk($wash_request_id, array("all_reject_ids" => '', "agent_reject_ids" => ''));
            Washingrequests::model()->updateAll(array("all_reject_ids" => '', "agent_reject_ids" => ''), 'status=:status', array(':status' => 0));
        }

        if ($washer_arrive_hit == 1) {
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            if ($wrequest_id_check->status == 5) {
                $json = array('result' => 'false',
                    'response' => 'Request is already canceled by customer');

                echo json_encode($json);
                die();
            }


            if (($wrequest_id_check->agent_id != 0) && ($wrequest_id_check->agent_id != $agent_id)) {
                $result = 'false';
                $response = 'Sorry this order is already taken by another washer';
                $json = array('result' => $result, 'response' => $response);
                echo json_encode($json);
                die();
            }
        }

        if ((isset($wash_request_id) && !empty($buzz_status))) {
            $washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
            $buzzstatus = $washrequestmodel->buzz_status;
            if ($buzzstatus == 0) {
                $washrequestmodel->buzz_status = $buzz_status;
            } else {
                $buzzstatus = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
                $buzz = $buzzstatus->buzz_status;
                $washrequestmodel->buzz_status = $buzz + 1;
            }

            $resUpdate = $washrequestmodel->save(false);

            /* Notifictaion Message Sent */
            $cust_id = $washrequestmodel->customer_id;
            $cust_details = Customers::model()->findByAttributes(array('id' => $cust_id));

            $notify_token = '';
            $notify_msg = '';
            $notify_token = $cust_details->device_token;
            $device_type = strtolower($cust_details->mobile_type);
            $alert_type = "default";

            if ($buzz_status == 1) {
                $clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $cust_id, PDO::PARAM_STR)->queryAll();
                $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '60' ")->queryAll();
                $message = $pushmsg[0]['message'];

                if (count($clientdevices)) {
                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        //echo $agentdetails['mobile_type'];
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "default";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }
            /* End Notification */

            if ($resUpdate) {
                $result = 'true';
                $sound = 'buzzsound.mp3';
                $response = 'Wash buzz status changed';
            }
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            $agent_detail = Agents::model()->findByPk($wrequest_id_check->agent_id);
            $logdata = array(
                'agent_id' => $wrequest_id_check->agent_id,
                'wash_request_id' => $wash_request_id,
                'agent_company_id' => $agent_detail->real_washer_id,
                'action' => 'agentbuzz',
                'action_date' => date('Y-m-d H:i:s'));

            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
        } elseif ($wash_request_id && is_numeric($is_scheduled)) {
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {

                if (Yii::app()->request->getParam('savejob') == 1) {

                    if (($wrequest_id_check->agent_id != 0) && ($wrequest_id_check->agent_id != $agent_id)) {
                        $result = 'false';
                        $response = 'Sorry, this order is already taken by another washer';

                        Yii::app()->db->createCommand("DELETE FROM activity_logs WHERE agent_id = :agent_id AND wash_request_id = :wash_request_id AND action = 'savejob' ORDER BY action_date DESC LIMIT 1")
                                ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                                ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                                ->execute();

                        $json = array('result' => $result, 'response' => $response);
                        echo json_encode($json);
                        die();
                    }
                }

                $result = 'true';
                $response = 'wash request updated';

                if (!$schedule_date) {
                    $schedule_date = $wrequest_id_check->schedule_date;
                }

                if (!$schedule_time) {
                    $schedule_time = $wrequest_id_check->schedule_time;
                }

                if (!$reschedule_date) {
                    $reschedule_date = $wrequest_id_check->reschedule_date;
                }

                if (!$reschedule_time) {
                    $reschedule_time = $wrequest_id_check->reschedule_time;
                }

                if (!is_numeric($status)) {
                    $status = $wrequest_id_check->status;
                }

                if (!is_numeric($agent_id)) {
                    $agent_id = $wrequest_id_check->agent_id;
                }

                if (!$checklist) {
                    $checklist = $wrequest_id_check->checklist;
                }

                if (!$notes) {
                    $notes = $wrequest_id_check->notes;
                }

                if (!$order_for_date) {
                    $order_for_date = $wrequest_id_check->order_for;
                }

                if (!isset($car_ids)) {
                    $car_ids = $wrequest_id_check->car_list;
                }

                if (!$package_list) {
                    $package_list = $wrequest_id_check->package_list;
                }

                if (!$address_type) {
                    $address_type = $wrequest_id_check->address_type;
                }

                if (!$eta) {
                    $eta = $wrequest_id_check->estimate_time;
                }

                if (!$scheduled_cars_info) {
                    $scheduled_cars_info = $wrequest_id_check->scheduled_cars_info;
                }

                if (!$schedule_total) {
                    $schedule_total = $wrequest_id_check->schedule_total;
                }

                if (!$schedule_company_total) {
                    $schedule_company_total = $wrequest_id_check->schedule_company_total;
                }

                if (!$schedule_agent_total) {
                    $schedule_agent_total = $wrequest_id_check->schedule_agent_total;
                }

                if (!is_numeric($tip_amount)) {
                    $tip_amount = $wrequest_id_check->tip_amount;
                }

                if (!is_numeric($washer_penalty_fee)) {
                    $washer_penalty_fee = $wrequest_id_check->washer_penalty_fee;
                }

                /* IF ADDRESS NOT FOUND IN DEFAULT PARAMETERES THEN EXSITING ADDRESS WILL BE USED FROM DB */
                if (!isset($address) && empty($address)) {
                    $address = $wrequest_id_check->address;
                }

                /* IF LAT NOT FOUND IN DEFAULT PARAMETERES THEN EXSITING LAT VALUE WILL BE USED FROM DB */
                if (empty($lat)) {
                    $lat = $wrequest_id_check->latitude;
                }
                /* IF LONG NOT FOUND IN DEFAULT PARAMETERS THEN EXSITING LONG VALUE WILL BE USED FROM DB */
                if (empty($long)) {
                    $long = $wrequest_id_check->longitude;
                }

                if ($is_rescheduled == 1) {
                    $order_for_date = date("Y-m-d H:i:s", strtotime($reschedule_date . " " . $reschedule_time));
                }

                /* ------- overlapping wash check for agent ----- */
                $model_NewRqst = Washingrequests::model()->findByPk($wash_request_id);
                $new_schedule_date = $model_NewRqst->schedule_date;
                $new_schedule_time = $model_NewRqst->schedule_time;

                $washtime = 0;
                $cars = explode(",", $model_NewRqst->car_list);
                $plans = explode(",", $model_NewRqst->package_list);
                foreach ($cars as $ind => $car) {
                    $car_detail = Vehicle::model()->findByPk($car);
                    //echo $car_detail->brand_name." ".$car_detail->model_name."<br>";

                    $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/plans");
                    $data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $plan_result = curl_exec($handle);
                    curl_close($handle);
                    $jsondata = json_decode($plan_result);

                    if ($plans[$ind] == 'Express') {
                        $expprice = intval($jsondata->plans->express[0]->wash_time);
                        $washtime += $expprice;
                    }

                    if ($plans[$ind] == 'Deluxe') {
                        $delprice = intval($jsondata->plans->deluxe[0]->wash_time);
                        $washtime += $delprice;
                    }

                    if ($plans[$ind] == 'Premium') {
                        $premprice = intval($jsondata->plans->premium[0]->wash_time);
                        $washtime += $premprice;
                    }

                    /* --- addons time ----- */
                    $pet_hair_vehicles_arr = explode(",", $model_NewRqst->pet_hair_vehicles);
                    if (in_array($car, $pet_hair_vehicles_arr))
                        $washtime += 5;

                    $lifted_vehicles_arr = explode(",", $model_NewRqst->lifted_vehicles);
                    if (in_array($car, $lifted_vehicles_arr))
                        $washtime += 5;

                    $exthandwax_vehicles_arr = explode(",", $model_NewRqst->exthandwax_vehicles);
                    if (in_array($car, $exthandwax_vehicles_arr))
                        $washtime += 10;

                    $extplasticdressing_vehicles_arr = explode(",", $model_NewRqst->extplasticdressing_vehicles);
                    if (in_array($car, $extplasticdressing_vehicles_arr))
                        $washtime += 5;

                    $extclaybar_vehicles_arr = explode(",", $model_NewRqst->extclaybar_vehicles);
                    if (in_array($car, $extclaybar_vehicles_arr))
                        $washtime += 15;

                    $waterspotremove_vehicles_arr = explode(",", $model_NewRqst->waterspotremove_vehicles);
                    if (in_array($car, $waterspotremove_vehicles_arr))
                        $washtime += 10;

                    $upholstery_vehicles_arr = explode(",", $model_NewRqst->upholstery_vehicles);
                    if (in_array($car, $upholstery_vehicles_arr))
                        $washtime += 10;

                    $floormat_vehicles_arr = explode(",", $model_NewRqst->floormat_vehicles);
                    if (in_array($car, $floormat_vehicles_arr))
                        $washtime += 10;
                    /* --- addons time end ----- */
                }

                //$washtime += 30;
                if ($model_NewRqst->reschedule_time) {
                    $currentwashtotalscheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->reschedule_date . ' ' . $model_NewRqst->reschedule_time . " +" . $washtime . " minutes"));
                    $currentwashbasescheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->reschedule_date . ' ' . $model_NewRqst->reschedule_time));
                } else {
                    $currentwashtotalscheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->schedule_date . ' ' . $model_NewRqst->schedule_time . " +" . $washtime . " minutes"));
                    $currentwashbasescheduletime = date('Y-m-d h:i A', strtotime($model_NewRqst->schedule_date . ' ' . $model_NewRqst->schedule_time));
                }

                $agenttakenwashes = Washingrequests::model()->findAll(array("condition" => "agent_id = :agent_id AND status = 0 AND is_scheduled = 1", 'params' => array(':agent_id' => $agent_id)));

                if (count($agenttakenwashes)) {
                    foreach ($agenttakenwashes as $agtwash) {
                        $washtime = 0;
                        $cars = explode(",", $agtwash->car_list);
                        $plans = explode(",", $agtwash->package_list);
                        foreach ($cars as $ind => $car) {
                            $car_detail = Vehicle::model()->findByPk($car);


                            $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/plans");
                            $data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                            curl_setopt($handle, CURLOPT_POST, true);
                            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                            $plan_result = curl_exec($handle);
                            curl_close($handle);
                            $jsondata = json_decode($plan_result);

                            if ($plans[$ind] == 'Express') {
                                $expprice = intval($jsondata->plans->express[0]->wash_time);
                                $washtime += $expprice;
                            }

                            if ($plans[$ind] == 'Deluxe') {
                                $delprice = intval($jsondata->plans->deluxe[0]->wash_time);
                                $washtime += $delprice;
                            }

                            if ($plans[$ind] == 'Premium') {
                                $premprice = intval($jsondata->plans->premium[0]->wash_time);
                                $washtime += $premprice;
                            }

                            /* --- addons time ----- */
                            $pet_hair_vehicles_arr = explode(",", $agtwash->pet_hair_vehicles);
                            if (in_array($car, $pet_hair_vehicles_arr))
                                $washtime += 5;

                            $lifted_vehicles_arr = explode(",", $agtwash->lifted_vehicles);
                            if (in_array($car, $lifted_vehicles_arr))
                                $washtime += 5;

                            $exthandwax_vehicles_arr = explode(",", $agtwash->exthandwax_vehicles);
                            if (in_array($car, $exthandwax_vehicles_arr))
                                $washtime += 10;

                            $extplasticdressing_vehicles_arr = explode(",", $agtwash->extplasticdressing_vehicles);
                            if (in_array($car, $extplasticdressing_vehicles_arr))
                                $washtime += 5;

                            $extclaybar_vehicles_arr = explode(",", $agtwash->extclaybar_vehicles);
                            if (in_array($car, $extclaybar_vehicles_arr))
                                $washtime += 15;

                            $waterspotremove_vehicles_arr = explode(",", $agtwash->waterspotremove_vehicles);
                            if (in_array($car, $waterspotremove_vehicles_arr))
                                $washtime += 10;

                            $upholstery_vehicles_arr = explode(",", $model_NewRqst->upholstery_vehicles);
                            if (in_array($car, $upholstery_vehicles_arr))
                                $washtime += 10;

                            $floormat_vehicles_arr = explode(",", $model_NewRqst->floormat_vehicles);
                            if (in_array($car, $floormat_vehicles_arr))
                                $washtime += 10;
                            /* --- addons time end ----- */
                        }

                        //$washtime += 30;
                        if ($agtwash->reschedule_time) {
                            $agtwashtotalscheduletime = date('Y-m-d h:i A', strtotime($agtwash->reschedule_date . ' ' . $agtwash->reschedule_time . " +" . $washtime . " minutes"));
                            $agtwashbasescheduletime = date('Y-m-d h:i A', strtotime($agtwash->reschedule_date . ' ' . $agtwash->reschedule_time));
                        } else {
                            $agtwashtotalscheduletime = date('Y-m-d h:i A', strtotime($agtwash->schedule_date . ' ' . $agtwash->schedule_time . " +" . $washtime . " minutes"));
                            $agtwashbasescheduletime = date('Y-m-d h:i A', strtotime($agtwash->schedule_date . ' ' . $agtwash->schedule_time));
                        }


                        if ((!$status) && ($agent_id) && (!$admin_permit) && (!Yii::app()->request->getParam('washer_drop_job')) && ($wrequest_id_check->agent_id != $agent_id)) {
                            if (strtotime($currentwashbasescheduletime) >= strtotime($agtwashbasescheduletime)) {

                                if (strtotime($agtwashtotalscheduletime) > strtotime($currentwashbasescheduletime)) {
                                    $result = 'false';
                                    $response = 'Sorry, you have an overlapping appointment with this schedule.';

                                    $json = array('result' => $result, 'response' => $response);
                                    echo json_encode($json);
                                    die();
                                }
                            }

                            if (strtotime($currentwashbasescheduletime) <= strtotime($agtwashbasescheduletime)) {
                                if (strtotime($currentwashtotalscheduletime) > strtotime($agtwashbasescheduletime)) {
                                    $result = 'false';
                                    $response = 'Sorry, you have an overlapping appointment with this schedule.';

                                    $json = array('result' => $result, 'response' => $response);
                                    echo json_encode($json);
                                    die();
                                }
                            }
                        }
                    }
                }
                /* ------- overlapping wash check for agent end ------- */

                if (Yii::app()->request->getParam('savejob') == 1) {
                    if (($wrequest_id_check->agent_id != 0) && ($wrequest_id_check->agent_id != $agent_id)) {
                        $result = 'false';
                        $response = 'Sorry, this order is already taken by another washer';
                        $json = array('result' => $result, 'response' => $response);
                        echo json_encode($json);
                        die();
                    }

                    if (($wrequest_id_check->status == 5) || ($wrequest_id_check->status == 6)) {
                        $result = 'false';
                        $response = 'Sorry, this order is canceled';
                        $json = array('result' => $result, 'response' => $response);
                        echo json_encode($json);
                        die();
                    }

                    $washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
                    $cust_details = Customers::model()->findByAttributes(array('id' => $washrequestmodel->customer_id));

                    if (!$wrequest_id_check->is_washer_assigned_push_sent) {
                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '23' ")->queryAll();
                        $message = $pushmsg[0]['message'];

                        $clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $washrequestmodel->customer_id, PDO::PARAM_STR)->queryAll();
                        if (count($clientdevices)) {
                            foreach ($clientdevices as $ctdevice) {
                                //$message =  "You have a new scheduled wash request.";
                                $device_type = strtolower($ctdevice['device_type']);
                                $notify_token = $ctdevice['device_token'];
                                //$alert_type = "strong";
                                $alert_type = "washerAssigned";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }

                        Washingrequests::model()->updateByPk($wash_request_id, array("is_washer_assigned_push_sent" => 1));
                    }

                    /* $get_data = Yii::app()->db->createCommand("SELECT count(*) as count FROM activity_logs WHERE agent_id = :agent_id AND agent_company_id = :agent_company_id AND action = 'savejob' AND wash_request_id = :wash_request_id")
                      ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                      ->bindValue(':agent_company_id', $agent_detail->real_washer_id, PDO::PARAM_STR)
                      ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                      ->queryAll(); */

                    $lastsavelogcheck = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE agent_id = :agent_id AND action = 'savejob' AND wash_request_id = :wash_request_id ORDER BY id DESC LIMIT 1")
                            ->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)
                            ->bindValue(':wash_request_id', $wash_request_id, PDO::PARAM_STR)
                            ->queryAll();
                    $lastsavesecond = 0;
                    if (count($lastsavelogcheck)) {
                        $lastsavesecond = time() - strtotime($lastsavelogcheck[0]['action_date']);
                    }

                    if ((!count($lastsavelogcheck)) || $lastsavesecond > 5) {
                        $washeractionlogdata = array(
                            'agent_id' => $agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'savejob',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
                }

                if (Yii::app()->request->getParam('washer_drop_job') == 1) {

                    $sched_time = '';
                    if ($wrequest_id_check->reschedule_time) {
                        $sched_date = $wrequest_id_check->reschedule_date;
                        $sched_time = $wrequest_id_check->reschedule_time;
                    } else {
                        $sched_date = $wrequest_id_check->schedule_date;
                        $sched_time = $wrequest_id_check->schedule_time;
                    }

                    $scheduledatetime = $sched_date . " " . $sched_time;
                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;

                    $min_diff = round(($from_time - $to_time) / 60, 2);


                    Washingrequests::model()->updateByPk($wash_request_id, array("is_create_schedulewash_push_sent" => 0, "is_washer_assigned_push_sent" => 0, "canceled_washer_id" => $wrequest_id_check->agent_id));

                    $alert_type = "strong";
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '24' ")->queryAll();
                    $notify_msg = urlencode($pushmsg[0]['message']);

                    $washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
                    $cust_details = Customers::model()->findByAttributes(array('id' => $washrequestmodel->customer_id));

                    $clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $washrequestmodel->customer_id, PDO::PARAM_STR)->queryAll();
                    $agent_detail = Agents::model()->findByPk($wrequest_id_check->agent_id);
                    if ($admin_username) {
                        $washeractionlogdata = array(
                            'agent_id' => $wrequest_id_check->agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'admin_username' => $admin_username,
                            'action' => 'admindropjob',
                            'action_date' => date('Y-m-d H:i:s'));
                    } else {
                        $washeractionlogdata = array(
                            'agent_id' => $wrequest_id_check->agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'action' => 'Dropschedule',
                            'action_date' => date('Y-m-d H:i:s'));
                    }



                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    if (($admin_username) && ($min_diff <= 60)) {
                        $washeractionlogdata2 = array(
                            'agent_id' => $wrequest_id_check->agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agent_detail->real_washer_id,
                            'admin_username' => $admin_username,
                            'action' => 'dropjob',
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata2);

                        /* ------------ calculate agent average feedback ---------------- */

                        $washer_total_dropjobs = 0;
                        $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $wrequest_id_check->agent_id));
                        $total_rate = count($agent_feedbacks);

                        $washerdropjobs = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id = :agent_id AND action = 'dropjob' AND status = 0")->bindValue(':agent_id', $wrequest_id_check->agent_id, PDO::PARAM_STR)->queryAll();

                        if (!empty($washerdropjobs))
                            $washer_total_dropjobs = $washerdropjobs[0]['count'];
                        if ($total_rate) {
                            $rate = 50;
                            foreach ($agent_feedbacks as $ind => $agent_feedback) {

                                if (!is_numeric($agent_feedback->customer_ratings))
                                    $rate += 5;
                                else
                                    $rate += $agent_feedback->customer_ratings;
                            }

                            if ($washer_total_dropjobs) {
                                //echo "rate: ".$rate."<br>total drops: ".$washer_total_dropjobs."<br>total rate: ".$total_rate."<br>";
                                $agent_rate = ($rate + $washer_total_dropjobs) / ($total_rate + 10 + $washer_total_dropjobs);
                            } else
                                $agent_rate = $rate / ($total_rate + 10);

                            $agent_rate = number_format($agent_rate, 2, '.', '');
                        }
                        else {
                            $agent_rate = 5.00;
                        }


                        $agentmodel = new Agents;
                        if ($agent_rate < 3)
                            $agentmodel->updateAll(array("rating" => $agent_rate, "block_washer" => 1, 'forced_logout' => 1), 'id=:id', array(':id' => $wrequest_id_check->agent_id));
                        $agentmodel->updateAll(array("rating" => $agent_rate), 'id=:id', array(':id' => $wrequest_id_check->agent_id));

                        /* ------------ calculate agent average feedback end ---------------- */
                    }

                    if (count($clientdevices)) {
                        foreach ($clientdevices as $ctdevice) {
                            //$message =  "You have a new scheduled wash request.";
                            //echo $agentdetails['mobile_type'];
                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];



                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                    }

                    //if(Yii::app()->request->getParam('admin_username') == ''){

                    $agent_details = Agents::model()->findByAttributes(array('id' => $wrequest_id_check->agent_id));
                    $notify_token2 = '';

                    $agentdevices = Yii::app()->db->createCommand('SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1')->bindValue(':agent_id', $wrequest_id_check->agent_id, PDO::PARAM_STR)->queryAll();

                    if ((count($agentdevices)) && (!$agent_details->block_washer)) {
                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '20' ")->queryAll();
                        //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '46' ")->queryAll();
                        $message = $pushmsg[0]['message'];
                        //$message = str_replace("[ORDER_ID]", "#" . $wash_request_id, $pushmsg[0]['message']);
                        foreach ($agentdevices as $agdevice) {
                            //$message =  "You have a new scheduled wash request.";
                            $device_type = strtolower($agdevice['device_type']);
                            $notify_token = $agdevice['device_token'];
                            $alert_type = "schedule";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                    // }
                    // INCREMENT 'total_schedule_rejected' counter on each rejection
                    $washrequestmodel->total_schedule_rejected = $washrequestmodel->total_schedule_rejected + 1;
                    $washrequestmodel->save(false);

                    if (APP_ENV == 'real') {
                        $this->layout = "xmlLayout";


                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;

                        $client = new Services_Twilio($account_sid, $auth_token);
                        $agent_det = Agents::model()->findByPk($wrequest_id_check->agent_id);
                        $smscontent = "Washer #" . $agent_det->real_washer_id . " - " . $agent_det->first_name . " " . $agent_det->last_name . " dropped the order #" . $wrequest_id_check->id;


                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '8183313631',
                                'From' => '+13103128070',
                                'Body' => $smscontent,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        try {

                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3109999334',
                                'From' => '+13103128070',
                                'Body' => $smscontent,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        try {

                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3103442534',
                                'From' => '+13103128070',
                                'Body' => $smscontent,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }
                }

                Washingrequests::model()->updateByPk($wash_request_id, array("address" => $address, "latitude" => $lat, "longitude" => $long, "address_type" => $address_type, "estimate_time" => $eta, "car_list" => $car_ids, "package_list" => $package_list, "is_scheduled" => $is_scheduled, "schedule_date" => $schedule_date, "schedule_time" => $schedule_time, "reschedule_date" => $reschedule_date, "reschedule_time" => $reschedule_time, 'order_for' => $order_for_date, "status" => $status, "agent_id" => $agent_id, "checklist" => $checklist, "notes" => $notes, "scheduled_cars_info" => $scheduled_cars_info, "schedule_total" => $schedule_total, "schedule_company_total" => $schedule_company_total, "schedule_agent_total" => $schedule_agent_total, "tip_amount" => $tip_amount, "washer_penalty_fee" => $washer_penalty_fee));

                if ($is_rescheduled == 1) {
                    $mobile_receipt = '';

                    $customers_id_check = Customers::model()->findByAttributes(array("id" => $wrequest_id_check->customer_id));
                    $from = Vargas::Obj()->getAdminFromEmail();
                    $sched_date = '';
                    if (strtotime($reschedule_date) == strtotime(date('Y-m-d'))) {
                        $sched_date = 'Today';
                    } else {
                        $sched_date = date('M d', strtotime($reschedule_date));
                    }

                    $message = '';
                    $subject = 'Re-Scheduled Order Receipt - #0000' . $wash_request_id;
                    //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                    $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order has been re-scheduled for " . $sched_date . " @ " . $reschedule_time . "</p>
					<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $wrequest_id_check->address . "</p>";
                    $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $wash_request_id . "</td></tr>
					</table>";

                    $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";


                    $message .= "</table>";

                    $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $wrequest_id_check->schedule_total . "</span></p></td></tr></table>";

                    $mobile_receipt .= "Total: $" . $wrequest_id_check->schedule_total . "\r\n";
                    $message .= "<p style='text-align: center; font-size: 14px; line-height: 22px; font-size: 16px; margin-top: 25px;'>Once you request a service, a pre-authorization charge is in place to ensure the credit card on file is valid. Please note that this charge will immediately be voided following the service request. Only the actual total will be charged at the end of the service.<br><br>We may kindly ask for a 30 minute grace period due to unforeseen traffic delays.<br>Appointment times may be rescheduled due to overwhelming demand.</p><p style='text-align: center; font-size: 18px;'>Log in to <a href='" . WEBSITE_URL . "' style='color: #016fd0'>MobileWash.com</a> to view your scheduled order options</p>";
                    $message .= "<p style='text-align: center; font-size: 16px; margin-bottom: 0; line-height: 22px;'>$15 cancellation fee will apply for canceling within 1 hour of your <br>scheduled wash time</p>";

                    //Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
                    $to = Vargas::Obj()->getAdminToEmail();
                    $from = Vargas::Obj()->getAdminFromEmail();
                    //Vargas::Obj()->SendMail($to,$from,$message,$subject, 'mail-receipt');

                    if ((APP_ENV == 'real')) {
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);

                        $message = "Order #" . $wash_request_id . " has been re-scheduled at " . $sched_date . " @ " . $reschedule_time . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $wrequest_id_check->address . " (" . $wrequest_id_check->address_type . ")\r\n------\r\n" . $mobile_receipt;


                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '8183313631',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }


                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3109999334',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3103442534',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }
                }

                if (($is_scheduled) && ($agent_id) && ($status == 1)) {
                    $cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);
                    $agent_det = Agents::model()->findByAttributes(array("id" => $agent_id));
                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    /* --- notification call --- */
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '17' ")->queryAll();
                    $message = $pushmsg[0]['message'];
                    $message = str_replace("[WASHERFIRSTNAME]", $agent_det->first_name, $message);

                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                    /* --- notification call end --- */

                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array("washer_on_way_push_sent" => 1, "wash_begin" => date("Y-m-d H:i:s")));


                    $washeractionlogdata = array(
                        'agent_id' => $agent_id,
                        'wash_request_id' => $wash_request_id,
                        'agent_company_id' => $agent_det->real_washer_id,
                        'action' => 'washerstartjob',
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            }
        }
        elseif ((isset($agent_id) && !empty($agent_id)) && (isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))) {
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
            $agent_id_check = Agents::model()->findByAttributes(array('id' => $agent_id));

            if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else if (!count($agent_id_check)) {
                $result = 'false';
                $response = 'Invalid agent id';
            } else {
                $washrequestmodel = Washingrequests::model()->findByPk($wash_request_id);
                if ($washrequestmodel->status == WASHREQUEST_STATUS_ACCEPTED && $washrequestmodel->agent_id != $agent_id) {
                    $result = 'false';
                    $response = 'Sorry this order is already taken by another washer';
                } else if ($status == WASHREQUEST_STATUS_CANCELWASH_BYCLIENT) {
                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);

                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);

                    if ($resUpdate) {
                        $result = 'true';
                        $response = 'Wash request canceled';
                    } else {
                        $result = 'false';
                        $response = 'Wash request not canceled';
                    }
                } else if ($status == WASHREQUEST_STATUS_CANCELWASH_BYAGENT) {
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '24' ")->queryAll();
                    $notify_msg = $pushmsg[0]['message'];
                    $alert_type = "strong";

                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);

                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);

                    if ($resUpdate) {
                        $result = 'true';
                        $response = 'Wash request canceled By Agent';
                    } else {
                        $result = 'false';
                        $response = 'Wash request not canceled';
                    }
                } else {
                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 0;
                    $agentmodel->save(false);

                    $washrequestmodel->agent_id = $agent_id;
                    $washrequestmodel->status = $status;
                    $resUpdate = $washrequestmodel->save(false);
                    if ($resUpdate) {
                        $result = 'true';
                        $response = 'Wash request status changed';
                    } else {
                        $result = 'false';
                        $response = 'Wash request status not changed';
                    }
                }

                if ($status == WASHREQUEST_STATUS_COMPLETEWASH) {
                    if ($wrequest_id_check->new_vehicle_confirm == 1) {
                        $result = 'false';
                        $response = 'a vehicle is waiting for customer confirmation';
                        $json = array('result' => $result, 'response' => $response);
                        echo json_encode($json);
                        die();
                    }

                    $washrequestmodel->complete_order = date("Y-m-d H:i:s");
                    $resUpdate = $washrequestmodel->save(false);

                    WashPricingHistory::model()->updateAll(array('status' => 1), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));

                    $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                    $kartdetails = json_decode($kartapiresult);

                    if ($wrequest_id_check->net_price != $kartdetails->net_price)
                        WashPricingHistory::model()->deleteAll("wash_request_id = :wash_request_id", array(':wash_request_id' => $wash_request_id));
                    else
                        WashPricingHistory::model()->updateAll(array('status' => 0), 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));

                    /* ----------- update pricing details -------------- */
                    $washrequestmodel->total_price = $kartdetails->total_price;
                    $washrequestmodel->net_price = $kartdetails->net_price;
                    $washrequestmodel->company_total = $kartdetails->company_total;
                    $washrequestmodel->agent_total = $kartdetails->agent_total;
                    $washrequestmodel->bundle_discount = $kartdetails->bundle_discount;
                    //$washrequestmodel->fifth_wash_discount = $kartdetails->fifth_wash_discount;
                    $washrequestmodel->first_wash_discount = $kartdetails->first_wash_discount;
                    $washrequestmodel->coupon_discount = $kartdetails->coupon_discount;
                    $cust_details = Customers::model()->findByAttributes(array('id' => $washrequestmodel->customer_id));
                    $washrequestmodel->customer_wash_points = $cust_details->fifth_wash_points;

                    $resUpdate = $washrequestmodel->save(false);
                    /* ----------- update pricing details end -------------- */

                    $agentmodel = Agents::model()->findByPk($agent_id);
                    $agentmodel->available_for_new_order = 1;
                    //$agentmodel->save(false);

                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",", $car_ids);

                    Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 1, "is_nextwash_reminder_push_sent" => 0, "is_non_returning" => 0, "nonreturn_20day_notify" => 0, 'nonreturn_email_sms_notify_sent' => 0, 'nonreturn_email_delivery_pending' => 0, "is_inactive" => 0, "inactive_cat" => 0, "inactive_email_delivery_pending" => 0, 'inactive_email_sms_notify_sent' => 0));

                    foreach ($kartdetails->vehicles as $car) {

                        $cust_details = Customers::model()->findByAttributes(array('id' => $washrequestmodel->customer_id));
                        $wash_detail = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));
                        if (!$wrequest_id_check->wash_complete_push_sent) {
                            /* ------ 5th wash check ------- */

                            $current_points = $cust_details->fifth_wash_points;
                            if ($current_points == 5) {
                                $new_points = 1;
                            } else {
                                $new_points = $current_points + 1;
                            }

                            Customers::model()->updateByPk($wrequest_id_check->customer_id, array('fifth_wash_points' => $new_points));

                            if ($new_points == 5) {

                                $fifth_vehicles_old = '';
                                $fifth_vehicles_old = $wash_detail->fifth_wash_vehicles;
                                $fifth_vehicles_arr = explode(",", $fifth_vehicles_old);
                                if (!in_array($car->id, $fifth_vehicles_arr))
                                    array_push($fifth_vehicles_arr, $car->id);
                                $fifth_vehicles_new = implode(",", $fifth_vehicles_arr);
                                $fifth_vehicles_new = trim($fifth_vehicles_new, ",");

                                if ($wrequest_id_check->coupon_discount <= 0)
                                    Washingrequests::model()->updateByPk($wash_request_id, array('fifth_wash_discount' => 5, 'fifth_wash_vehicles' => $fifth_vehicles_new));
                            }

                            /* ------ 5th wash check end ------- */

                            /* ---- per car wash points ------ */

                            $per_car_points_old = '';
                            $per_car_points_old = $wash_detail->per_car_wash_points;
                            $per_car_points_arr = explode(",", $per_car_points_old);
                            array_push($per_car_points_arr, $new_points);
                            $per_car_points_new = implode(",", $per_car_points_arr);
                            $per_car_points_new = trim($per_car_points_new, ",");

                            Washingrequests::model()->updateByPk($wash_request_id, array('per_car_wash_points' => $per_car_points_new, 'customer_wash_points' => $new_points));

                            /* ---- per car wash points end ------ */
                        }

                        if ($wrequest_id_check->net_price != $kartdetails->net_price) {
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
                        $washinginspectmodel->eco_friendly = $cardetail->eco_friendly;
                        $washinginspectmodel->save(false);
                        /* --------- Inspection details save end --------- */

                        $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '', 'pet_hair' => 0, 'lifted_vehicle' => 0, 'exthandwax_addon' => 0, 'extplasticdressing_addon' => 0, 'extclaybar_addon' => 0, 'waterspotremove_addon' => 0, 'upholstery_addon' => 0, 'floormat_addon' => 0, 'surge_addon' => 0);
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car->id));
                    }

                    $cust_id = $wrequest_id_check->customer_id;
                    $completed_washes_agent = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent_id, 'status' => WASHREQUEST_STATUS_COMPLETEWASH));
                    $completed_washes_cust = Washingrequests::model()->findAllByAttributes(array('customer_id' => $cust_id, 'status' => WASHREQUEST_STATUS_COMPLETEWASH));
                    $total_washes_agent = count($completed_washes_agent);
                    $total_washes_cust = count($completed_washes_cust);
                    $total_wash_data_agent = array('total_wash' => $total_washes_agent);

                    $total_wash_data_cust = array('total_wash' => $total_washes_cust);
                    $Customers = new Customers;
                    $Agents = new Agents;
                    $Customers->updateAll($total_wash_data_cust, 'id=:id', array(':id' => $cust_id));
                    $Agents->updateAll($total_wash_data_agent, 'id=:id', array(':id' => $agent_id));

                    if (is_numeric($tip_amount)) {
                        Washingrequests::model()->updateAll(array('tip_amount' => $tip_amount), 'id=:id', array(':id' => $wash_request_id));
                    }
                    Washingrequests::model()->updateAll(array("washer_late_cancel" => 0, "no_washer_cancel" => 0), 'id=:id', array(':id' => $wash_request_id));

                    $agent_det = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'agent_id' => $wrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'action' => 'appcompletejob',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }

                /* --- notification call --- */
                $cust_id = $wrequest_id_check->customer_id;
                $cust_details = Customers::model()->findByAttributes(array('id' => $cust_id));
                $notify_token = '';
                $notify_msg = '';
                $notify_token = $cust_details->device_token;
                $device_type = strtolower($cust_details->mobile_type);
                $alert_type = "default";

                $clientdevices = Yii::app()->db->createCommand('SELECT * FROM customer_devices WHERE customer_id = :customer_id ORDER BY last_used DESC LIMIT 1')->bindValue(':customer_id', $cust_id, PDO::PARAM_STR)->queryAll();

                if (count($clientdevices)) {
                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "default";
                    }
                }
                if (($status == WASHREQUEST_STATUS_ACCEPTED) && (!$wrequest_id_check->washer_on_way_push_sent)) {
                    $agent_det = Agents::model()->findByAttributes(array("id" => $agent_id));
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '11' ")->queryAll();
                    $notify_msg = $pushmsg[0]['message'];
                    $notify_msg = str_replace("[WASHERFIRSTNAME]", $agent_det->first_name, $notify_msg);

                    $alert_type = "washerAssigned";
                    //$washrequestmodel->wash_begin = date("Y-m-d H:i:s");
                    //$resUpdate = $washrequestmodel->save(false);
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array("washer_on_way_push_sent" => 1, "wash_begin" => date("Y-m-d H:i:s")));


                    $washeractionlogdata = array(
                        'agent_id' => $agent_id,
                        'wash_request_id' => $wash_request_id,
                        'agent_company_id' => $agent_det->real_washer_id,
                        'action' => 'washerstartjob',
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    if (((APP_ENV == 'real')) && ($cust_details->sms_control)) {
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $cust_details->contact_number,
                                'From' => '+13103128070',
                                'Body' => $notify_msg,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }
                }

                if (($status == WASHREQUEST_STATUS_AGENTARRIVED) && (!$wrequest_id_check->meet_washer_push_sent)) {
                    //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '10' ")->queryAll();
                    //$notify_msg = $pushmsg[0]['message'];
                    $notify_msg = '';
                    //$notify_msg = "Please meet your washer outside";
                    $alert_type = "soft";
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array("meet_washer_push_sent" => 1, "washer_arrived_at" => date('Y-m-d H:i:s')));
                    $agent_det = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'agent_id' => $wrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'agent_company_id' => $agent_det->real_washer_id,
                        'action' => 'washerarrivejob',
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    if (((APP_ENV == 'real')) && ($cust_details->sms_control)) {

                        $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '48' ")->queryAll();
                        $message = $pushmsg[0]['message'];
                        $message = str_replace("[FIRSTNAME]", $cust_details->first_name, $message);
                        $message = str_replace("[WASHERFIRSTNAME]", $agent_det->first_name, $message);
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $cust_details->contact_number,
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }
                }

                if (($status == WASHREQUEST_STATUS_AGENTARRIVED_CONFIRMED_BYCLIENT) && (!$wrequest_id_check->inspect_begin_push_sent)) {
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '13' ")->queryAll();
                    if (count($pushmsg) > 0) {
                        $notify_msg = $pushmsg[0]['message'];

                        //$notify_msg = "Agent begins car inspection process.";
                        $alert_type = "strong";
                        Washingrequests::model()->updateByPk($wrequest_id_check->id, array("inspect_begin_push_sent" => 1, 'fifth_wash_vehicles' => '', 'fifth_wash_discount' => 0));
                    }
                    $agent_det = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'agent_id' => $wrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'agent_company_id' => $agent_det->real_washer_id,
                        'action' => 'washerprocessjob',
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }

                if (($status == WASHREQUEST_STATUS_COMPLETEWASH) && (!$wrequest_id_check->wash_complete_push_sent)) {
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '14' ")->queryAll();
                    $notify_msg = $pushmsg[0]['message'];

                    //$notify_msg = "All car washes complete. Thank you.";
                    $alert_type = "soft";
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array("wash_complete_push_sent" => 1));

                    if (((APP_ENV == 'real')) && ($cust_details->sms_control)) {
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $cust_details->contact_number,
                                'From' => '+13103128070',
                                'Body' => "Wash complete. Please open your app and meet your washer outside to ensure satisfaction. Your washer will wait up to 10 minutes.",
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }
                }

                if ($notify_msg) {
                    $notify_msg = urlencode($notify_msg);

                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                    file_put_contents("android_notificaiton.log", $notifyurl . "\r\n", FILE_APPEND);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    if ($notify_msg)
                        $notifyresult = curl_exec($ch);
                    curl_close($ch);
                }

                if ($status == WASHREQUEST_STATUS_COMPLETEWASH) {
                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '15' ")->queryAll();
                    $notify_msg2 = $pushmsg[0]['message'];

                    //$notify_msg2 = "Wash Complete!";
                    $notify_msg2 = urlencode($notify_msg2);

                    $agent_id = $wrequest_id_check->agent_id;
                    $agent_details = Agents::model()->findByAttributes(array('id' => $agent_id));
                    $notify_token2 = '';

                    $agentdevices = Yii::app()->db->createCommand('SELECT * FROM agent_devices WHERE agent_id = :agent_id ORDER BY last_used DESC LIMIT 1')->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)->queryAll();

                    if ((count($agentdevices)) && (!$agent_details->block_washer)) {
                        foreach ($agentdevices as $agdevice) {
                            //$message =  "You have a new scheduled wash request.";
                            //echo $agentdetails['mobile_type'];
                            $device_type2 = strtolower($agdevice['device_type']);
                            $notify_token2 = $agdevice['device_token'];
                            $alert_type2 = "default";


                            $notifyurl2 = ROOT_URL . "/push-notifications/" . $device_type2 . "/?device_token=" . $notify_token2 . "&msg=" . $notify_msg2 . "&alert_type=" . $alert_type2;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch2 = curl_init();
                            curl_setopt($ch2, CURLOPT_URL, $notifyurl2);
                            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg2)
                                $notifyresult2 = curl_exec($ch2);
                            curl_close($ch2);
                        }
                    }
                }
                /* --- notification call end --- */
            }
        }
        else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
    }

    /*
     * * Returns check wash request status.
     * * Post Required: customer id, wash request id
     * * Url:- http://www.demo.com/index.php?r=washing/checkwashrequeststatus
     * * Purpose:- check wash request status
     */

    public function actioncheckwashrequeststatus() {

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

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $upgrade_pack = Yii::app()->request->getParam('upgrade_pack');
        $vehicle_id = Yii::app()->request->getParam('vehicle_id');
        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        $agent_details = new stdClass();
        $customer_details = new stdClass();
        $car_types = '';
        $feedback_5mins_passed = 0;
        $vehicles = array();

        $veh_type = '';
        $fifth_fee_check = 0;
        $first_fee_check = 0;
        $canceled_washer_id = 0;
        if (Yii::app()->request->getParam('zipcode'))
            $zipcode = Yii::app()->request->getParam('zipcode');
        $exp_surge_factor = 0;
        $del_surge_factor = 0;
        $prem_surge_factor = 0;
        $zipcode_price_factor = 0;
        if ((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id))) {

            if (AES256CBC_STATUS == 1) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $customer_id_check = Customers::model()->findByAttributes(array('id' => $customer_id));
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id, 'customer_id' => $customer_id));


            /* ------- get nearest agents --------- */

            /* $handle = curl_init(ROOT_URL."/api/index.php?r=agents/getnearestagents");
              $data = array('wash_request_id' => $wash_request_id, "key" => API_KEY);
              curl_setopt($handle, CURLOPT_POST, true);
              curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
              curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
              $output = curl_exec($handle);
              curl_close($handle);
              $nearagentsdetails = json_decode($output); */

            /* ------- get nearest agents end --------- */


            if (!count($customer_id_check)) {
                $result = 'false';
                $response = 'Invalid customer id';
            } else if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {

                // upgrade started
                if ($upgrade_pack == 11) {
                    Vehicle::model()->updateByPk($vehicle_id, array("upgrade_pack" => $upgrade_pack));
                }

                // upgrade canceled
                if ($upgrade_pack == 'zero') {
                    Vehicle::model()->updateByPk($vehicle_id, array("upgrade_pack" => 0));
                }

                $wrequest_obj = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id, 'customer_id' => $customer_id));

                $response = $wrequest_obj->status;
                $buzz_status = $wrequest_obj->buzz_status;
                $result = 'true';
                if ($response) {

                    $agent_id = $wrequest_obj->agent_id;
                    $agent_obj = Agents::model()->findByAttributes(array('id' => $agent_id));
                    $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id' => $agent_id));
                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_id));

                    $total_rate = count($agent_feedbacks);
                    if ($total_rate) {
                        $rate = 0;
                        foreach ($agent_feedbacks as $agent_feedback) {
                            $rate += $agent_feedback->agent_ratings;
                        }

                        $agent_rate = round($rate / $total_rate);
                    } else {
                        $agent_rate = 0;
                    }

                    $agentlname = '';
                    if (trim($agent_obj->last_name))
                        $agentlname = strtoupper(substr($agent_obj->last_name, 0, 1)) . ".";
                    else
                        $agentlname = $agent_obj->last_name;

                    $agentname = $agent_obj->first_name; //." ".$agentlname;
                    if (AES256CBC_STATUS == 1) {
                        $agent_details->id = $this->aes256cbc_crypt($agent_id, 'e', AES256CBC_API_PASS);
                    } else {
                        $agent_details->id = $agent_id;
                    }

                    $agent_details->name = $agentname;
                    $agent_details->phone = $agent_obj->phone_number;
                    $agent_details->total_washes = $agent_obj->total_wash;
                    $agent_details->rating = $agent_obj->rating;
                    $agent_details->photo = $agent_obj->image;
                    $agent_details->latitude = $agent_loc_obj->latitude;
                    $agent_details->longitude = $agent_loc_obj->longitude;

                    $cust_loc_obj = CustomerLocation::model()->findByAttributes(array('customer_id' => $customer_id));

                    $customer_details->name = $customer_id_check->first_name . " " . $customer_id_check->last_name;
                    $customer_details->photo = $customer_id_check->image;
                    $customer_details->latitude = $wrequest_obj->latitude;
                    $customer_details->longitude = $wrequest_obj->longitude;


                    $car_ids = $wrequest_obj->car_list;
                    $car_ids = explode(",", $car_ids);
                    foreach ($car_ids as $cid) {
                        $cars_obj = Vehicle::model()->findByAttributes(array('id' => $cid));
                        $car_types .= $cars_obj->vehicle_type . ",";
                    }
                    $car_types = rtrim($car_types, ',');
                    $customer_details->cartypes = $car_types;

                    if ((!$wrequest_obj->washer_arrival_notify) && $wrequest_id_check->agent_id) {

                        /* ------------- Checek agent arrival distance ---------- */

                        /* --- Google Distance call --- */

                        $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $agent_loc_obj->latitude . "," . $agent_loc_obj->longitude . "&destinations=" . $wrequest_obj->latitude . "," . $wrequest_obj->longitude . "&mode=driving&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $geourl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                        $georesult = curl_exec($ch);
                        curl_close($ch);
                        $geojsondata = json_decode($georesult);
                        $eta = $geojsondata->rows[0]->elements[0]->duration->value;

                        if ($eta <= 60) {
                            if (!$wrequest_id_check->washer_one_min_arrive_push_sent) {
                                /* --- notification call --- */

                                $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '16' ")->queryAll();
                                $message = $pushmsg[0]['message'];

                                $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                                foreach ($clientdevices as $ctdevice) {
                                    //$message =  "Washer arriving within 1 minute";
                                    $device_type = strtolower($ctdevice['device_type']);
                                    $notify_token = $ctdevice['device_token'];
                                    $alert_type = "strong";
                                    $notify_msg = urlencode($message);

                                    $notifyurl = "https://www.mobilewash.com/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    file_put_contents("washing_one_min_notificaiton.log", "order id " . $wrequest_id_check->id . " - " . "customer id " . $ctdevice['customer_id'] . " " . "device id " . $ctdevice['id'] . " " . $notifyurl . " source: checkwashrequeststatus\r\n", FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }

                                /* --- notification call end --- */

                                Washingrequests::model()->updateByPk($wash_request_id, array('washer_one_min_arrive_push_sent' => 1));
                            }
                            Washingrequests::model()->updateByPk($wash_request_id, array('washer_arrival_notify' => 1));
                        }


                        /* ------------- Checek agent arrival distance end ---------- */
                    }

                    $mins = 0;
                    $to_time = strtotime("now");
                    $from_time = strtotime($wrequest_id_check->wash_begin);
                    $mins = round(abs($to_time - $from_time) / 60, 2);

                    $to_time = strtotime("now");

                    if (($wrequest_id_check->status == 5) || ($wrequest_id_check->status == 6) || ($wrequest_id_check->status == 7))
                        $from_time = strtotime($wrequest_id_check->order_canceled_at);
                    else
                        $from_time = strtotime($wrequest_id_check->complete_order);

                    $feedback_time_check = round(abs($to_time - $from_time) / 60, 2);

                    if (($feedback_time_check >= 30) && ($wrequest_id_check->is_feedback_sent == 1)) {
                        $feedback_5mins_passed = 1;
                    }
                }

                /* --- getvehiclestatus --- */

                $wash_request_exists = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

                $surgeprice = Yii::app()->db->createCommand()->select('*')->from('surge_pricing')->where("day='" . strtolower(date('D')) . "'", array())->queryAll();
                $coveragezipcheck = 0;
//$zipcodeprice = Yii::app()->db->createCommand()->select('*')->from('zipcode_pricing')->where("id='1'", array())->queryAll();

                if ($zipcode) {

                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));
                    /* if(count($coveragezipcheck)){

                      if($coveragezipcheck->zip_color == 'yellow'){
                      $zipcode_price_factor = $zipcodeprice[0]['yellow'];
                      }

                      if($coveragezipcheck->zip_color == 'red'){
                      $zipcode_price_factor = $zipcodeprice[0]['red'];
                      }

                      if($coveragezipcheck->zip_color == ''){
                      $zipcode_price_factor = $zipcodeprice[0]['blue'];
                      }

                      if($zipcodeprice[0]['price_unit'] == 'percent'){
                      $exp_surge_factor += $zipcode_price_factor;
                      $del_surge_factor += $zipcode_price_factor;
                      $prem_surge_factor += $zipcode_price_factor;
                      }

                      } */
                }

                $exp_surge_factor += $surgeprice[0]['express'];
                $del_surge_factor += $surgeprice[0]['deluxe'];
                $prem_surge_factor += $surgeprice[0]['premium'];

                $cars = $wash_request_exists->car_list;
                $packs = $wash_request_exists->package_list;
                $car_arr = explode(",", $cars);
                $pack_arr = explode(",", $packs);
                $cust_exists = Customers::model()->findByAttributes(array("id" => $wash_request_exists->customer_id));


                foreach ($car_arr as $ind => $carid) {

                    $wash_time = 0;
                    $cardata = Vehicle::model()->findByAttributes(array("id" => $carid));

                    if ($cardata->vehicle_type != 'S' && $cardata->vehicle_type != 'M' && $cardata->vehicle_type != 'L' && $cardata->vehicle_type != 'E') {
                        $veh_type = 'S';
                    } else {
                        $veh_type = $cardata->vehicle_type;
                    }

                    if ($cardata->upgrade_pack == 1) {
                        $washing_plan_det = Washingplans::model()->findByAttributes(array("vehicle_type" => $veh_type, "title" => $cardata->new_pack_name));
                        if (count($washing_plan_det)) {
                            $wash_price = $washing_plan_det->price;

                            if (count($coveragezipcheck)) {

                                if ($coveragezipcheck->zip_color == 'yellow') {
                                    $wash_price = $washing_plan_det->tier2_price;
                                }

                                if ($coveragezipcheck->zip_color == 'red') {
                                    $wash_price = $washing_plan_det->tier3_price;
                                }

                                if ($coveragezipcheck->zip_color == 'purple') {
                                    $wash_price = $washing_plan_det->tier4_price;
                                }
                            }


                            if ($cardata->new_pack_name == 'Express')
                                $wash_price = $wash_price + ($wash_price * ($exp_surge_factor / 100));
                            if ($cardata->new_pack_name == 'Deluxe')
                                $wash_price = $wash_price + ($wash_price * ($del_surge_factor / 100));
                            if ($cardata->new_pack_name == 'Premium')
                                $wash_price = $wash_price + ($wash_price * ($prem_surge_factor / 100));

                            /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                              $wash_price += $zipcode_price_factor;
                              $wash_price = (string) $wash_price;
                              } */

                            $wash_price = number_format($wash_price, 2);
                        } else
                            $wash_price = '';
                    }
                    else {
                        $washing_plan_det = Washingplans::model()->findByAttributes(array("vehicle_type" => $veh_type, "title" => $pack_arr[$ind]));
                        if (count($washing_plan_det)) {
                            $wash_price = $washing_plan_det->price;

                            if (count($coveragezipcheck)) {

                                if ($coveragezipcheck->zip_color == 'yellow') {
                                    $wash_price = $washing_plan_det->tier2_price;
                                }

                                if ($coveragezipcheck->zip_color == 'red') {
                                    $wash_price = $washing_plan_det->tier3_price;
                                }

                                if ($coveragezipcheck->zip_color == 'purple') {
                                    $wash_price = $washing_plan_det->tier4_price;
                                }
                            }

                            if ($pack_arr[$ind] == 'Express')
                                $wash_price = $wash_price + ($wash_price * ($exp_surge_factor / 100));
                            if ($pack_arr[$ind] == 'Deluxe')
                                $wash_price = $wash_price + ($wash_price * ($del_surge_factor / 100));
                            if ($pack_arr[$ind] == 'Premium')
                                $wash_price = $wash_price + ($wash_price * ($prem_surge_factor / 100));

                            /* if((count($zipcodeprice)) && ($zipcodeprice[0]['price_unit'] == 'usd')){
                              $wash_price += $zipcode_price_factor;
                              $wash_price = (string) $wash_price;
                              } */

                            $wash_price = number_format($wash_price, 2);
                        } else
                            $wash_price = '';
                    }

                    $draft_vehicle_id = '';
                    $draft_vehicle_id = $wash_request_exists->draft_vehicle_id;
                    $new_vehicle_confirm = '';
                    $car_price_agent = 0;
                    $total_car_price = 0;
                    $total_car_price_agent = 0;
                    $bundle_fee = 0;
                    $bundle_fee_agent = 0;
                    $fifth_fee = 0;
                    $first_fee = 0;



                    if ($pack_arr[$ind] == 'Express')
                        $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                    if ($pack_arr[$ind] == 'Deluxe')
                        $car_price_agent = number_format($wash_price * .80, 2, '.', '');
                    if ($pack_arr[$ind] == 'Premium')
                        $car_price_agent = number_format($wash_price * .75, 2, '.', '');

                    $total_car_price += $wash_price;
                    $total_car_price += 1; //safe handling fee
                    $total_car_price += $cardata->pet_hair;
                    $total_car_price += $cardata->lifted_vehicle;
                    $total_car_price += $cardata->exthandwax_addon;
                    $total_car_price += $cardata->extplasticdressing_addon;
                    $total_car_price += $cardata->extclaybar_addon;
                    $total_car_price += $cardata->waterspotremove_addon;
                    $total_car_price += $cardata->upholstery_addon;
                    $total_car_price += $cardata->floormat_addon;


                    if (($cust_exists->fifth_wash_points == 4) && (!$fifth_fee_check) && ($wash_request_exists->coupon_discount <= 0)) {
                        $fifth_fee = 5;
                        $total_car_price -= $fifth_fee;
                        $fifth_fee_check = 1;
                    }

                    /* if(!$cust_exists->is_first_wash && (!$first_fee_check)) {
                      $first_pack = '';
                      if($cardata->upgrade_pack == 1) $first_pack = $cardata->new_pack_name;
                      else $first_pack = $pack_arr[0];

                      if($first_pack == 'Premium') $first_fee = 10;
                      else $first_fee = 5;
                      $total_car_price -= $first_fee;
                      $first_fee_check = 1;
                      } */

                    if ((count($car_arr) > 1) && (!$fifth_fee) && ($wash_request_exists->coupon_discount <= 0)) {
                        $bundle_fee = 1;
                        $total_car_price -= $bundle_fee;
                    }

                    $total_car_price_agent += $car_price_agent;
                    $total_car_price_agent += $cardata->pet_hair * .80;
                    $total_car_price_agent += $cardata->lifted_vehicle * .80;
                    $total_car_price_agent += $cardata->exthandwax_addon * .80;
                    $total_car_price_agent += $cardata->extplasticdressing_addon * .80;
                    $total_car_price_agent += $cardata->extclaybar_addon * .80;
                    $total_car_price_agent += $cardata->waterspotremove_addon * .80;
                    $total_car_price_agent += $cardata->upholstery_addon * .80;
                    $total_car_price_agent += $cardata->floormat_addon * .80;


                    if (count($car_arr) > 1) {
                        $bundle_fee_agent = number_format(.80, 2, '.', '');
                        $total_car_price_agent -= $bundle_fee_agent;
                    }

                    $wash_time = $washing_plan_det->wash_time;
                    if ($cardata->pet_hair > 0)
                        $wash_time += 5;
                    if ($cardata->lifted_vehicle > 0)
                        $wash_time += 5;
                    if ($cardata->exthandwax_addon > 0)
                        $wash_time += 10;
                    if ($cardata->extplasticdressing_addon > 0)
                        $wash_time += 5;
                    if ($cardata->extclaybar_addon > 0)
                        $wash_time += 15;
                    if ($cardata->waterspotremove_addon > 0)
                        $wash_time += 10;
                    if ($cardata->upholstery_addon > 0)
                        $wash_time += 10;
                    if ($cardata->floormat_addon > 0)
                        $wash_time += 10;

                    $hours = floor($wash_time / 60);
                    $minutes = ($wash_time % 60);

                    if ($hours < 1) {
                        $washtime_str = sprintf('%02d min', $minutes);
                    } else {
                        if ($hours == 1) {
                            if ($minutes > 0)
                                $washtime_str = sprintf('%d hour %02d min', $hours, $minutes);
                            else
                                $washtime_str = sprintf('%d hour', $hours);
                        }
                        else {
                            if ($minutes > 0)
                                $washtime_str = sprintf('%d hours %02d min', $hours, $minutes);
                            else
                                $washtime_str = sprintf('%d hours', $hours);
                        }
                    }


                    $new_vehicle_confirm = $wash_request_exists->new_vehicle_confirm;
                    $vehicles[] = array("id" => $carid, "make" => $cardata->brand_name, "model" => $cardata->model_name, "license_no" => $cardata->vehicle_no, "vehicle_type" => $cardata->vehicle_type, "vehicle_category" => $cardata->vehicle_category, "vehicle_build" => $cardata->vehicle_build, "vehicle_image" => $cardata->vehicle_image, "handling_fee" => '1.00', "status" => $cardata->status, "eco_friendly" => $cardata->eco_friendly, "damage_points" => $cardata->damage_points, "damage_pic" => $cardata->damage_pic, "pet_hair" => $cardata->pet_hair, "pet_hair_agent" => number_format(round($cardata->pet_hair * .80, 2), 2), "lifted_vehicle" => $cardata->lifted_vehicle, "lifted_vehicle_agent" => number_format(round($cardata->lifted_vehicle * .80, 2), 2), "exthandwax_addon" => $cardata->exthandwax_addon, "exthandwax_addon_agent" => number_format(round($cardata->exthandwax_addon * .80, 2), 2), "extplasticdressing_addon" => $cardata->extplasticdressing_addon, "extplasticdressing_addon_agent" => number_format(round($cardata->extplasticdressing_addon * .80, 2), 2), "extclaybar_addon" => $cardata->extclaybar_addon, "extclaybar_addon_agent" => number_format(round($cardata->extclaybar_addon * .80, 2), 2), "waterspotremove_addon" => $cardata->waterspotremove_addon, "waterspotremove_addon_agent" => number_format(round($cardata->waterspotremove_addon * .80, 2), 2), "upholstery_addon" => $cardata->upholstery_addon, "upholstery_addon_agent" => number_format(round($cardata->upholstery_addon * .80, 2), 2), "floormat_addon" => $cardata->floormat_addon, "floormat_addon_agent" => number_format(round($cardata->floormat_addon * .80, 2), 2), "bundle_discount" => number_format($bundle_fee, 2), "bundle_discount_agent" => number_format($bundle_fee_agent, 2), "fifth_wash_discount" => number_format($fifth_fee, 2), "first_wash_discount" => number_format($first_fee, 2), "upgrade_pack" => $cardata->upgrade_pack, "upgrade_requested_at" => $cardata->upgrade_requested_at, "new_pack_name" => $cardata->new_pack_name, "edit_vehicle" => $cardata->edit_vehicle, "remove_vehicle_from_kart" => $cardata->remove_vehicle_from_kart, "payment_type" => $pack_arr[$ind], "price" => $wash_price, "total_price" => number_format($total_car_price, 2), "price_agent" => $car_price_agent, "total_price_agent" => number_format($total_car_price_agent, 2), "wash_time" => $wash_time, "wash_time_str" => $washtime_str);
                }


                /* --- getvehiclestatus end --- */
            }
        }
        else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }

        if ((AES256CBC_STATUS == 1) && ($wrequest_id_check->canceled_washer_id)) {
            $canceled_washer_id = $this->aes256cbc_crypt($wrequest_id_check->canceled_washer_id, 'e', AES256CBC_API_PASS);
        }

        $sched_date = '';
        $sched_time = '';
        if ($wrequest_id_check->is_scheduled) {
            if ($wrequest_id_check->reschedule_time) {
                $sched_date = $wrequest_id_check->reschedule_date;
                $sched_time = $wrequest_id_check->reschedule_time;
            } else {
                $sched_date = $wrequest_id_check->schedule_date;
                $sched_time = $wrequest_id_check->schedule_time;
            }
        }

        $check_admin_cancel = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE wash_request_id = " . $wrequest_id_check->id . " AND action IN('completejob') AND admin_username != '' LIMIT 1")->queryAll();
        $admin_wash_complete = 0;
        if (count($check_admin_cancel) > 0) {
            $admin_wash_complete = 1;
        }

        if ($response) {
            $json = array(
                'result' => $result,
                'response' => $response,
                'buzz_status' => $buzz_status,
                'is_scheduled' => $wrequest_id_check->is_scheduled,
                'meet_washer_outside' => $wrequest_id_check->meet_washer_outside,
                'meet_washer_outside_washend' => $wrequest_id_check->meet_washer_outside_washend,
                'agent_id' => $wrequest_id_check->agent_id,
                'time_pass_since_washend' => $feedback_time_check,
                'wash_start_since' => $mins,
                'feedback_5mins_passed' => $feedback_5mins_passed,
                'agent_details' => $agent_details,
                'customer_details' => $customer_details,
                'draft_vehicle_id' => $draft_vehicle_id,
                'new_vehicle_confirm' => $new_vehicle_confirm,
                'upgrade_requested_at' => $wrequest_id_check->upgrade_requested_at,
                'vehicles' => $vehicles,
                'no_washer_cancel' => $wrequest_id_check->no_washer_cancel,
                'order_address' => $wrequest_id_check->address,
                'latitude' => $wrequest_id_check->latitude,
                'longitude' => $wrequest_id_check->longitude,
                'company_cancel' => $wrequest_id_check->company_cancel,
                'canceled_washer_id' => $canceled_washer_id,
                'schedule_date' => $sched_date,
                'schedule_time' => $sched_time,
                'order_for' => $wrequest_id_check->order_for,
                'total_price' => $wrequest_id_check->total_price,
                'net_price' => $wrequest_id_check->net_price,
                'agent_total' => $wrequest_id_check->agent_total,
                'company_total' => $wrequest_id_check->company_total,
                'admin_wash_complete' => $admin_wash_complete
            );
        } else {
            $json = array(
                'result' => $result,
                'response' => $response,
                'agent_id' => $wrequest_id_check->agent_id,
                'no_washer_cancel' => $wrequest_id_check->no_washer_cancel,
                'company_cancel' => $wrequest_id_check->company_cancel,
                'canceled_washer_id' => $canceled_washer_id,
                'schedule_date' => $sched_date,
                'schedule_time' => $sched_time,
                'order_for' => $wrequest_id_check->order_for,
                'total_price' => $wrequest_id_check->total_price,
                'net_price' => $wrequest_id_check->net_price,
                'agent_total' => $wrequest_id_check->agent_total,
                'company_total' => $wrequest_id_check->company_total,
            );
        }
        echo json_encode($json);
    }

    /*
     * * Returns totals and washed car lists.
     * * Post Required: customer id,wash_request_id,comments,ratings
     * * Url:- http://www.demo.com/index.php?r=washing/customerfeedback
     * * Purpose:- Getting totals and washed car lists
     */

    public function actioncustomerfeedback() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        if (Yii::app()->request->getParam('api_password') != AES256CBC_API_PASS) {
            $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

            if (!$token_check) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid request'
                );
                echo json_encode($json);
                die();
            }
        }

        $customer_id = Yii::app()->request->getParam('customer_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');
        $ratings = 5.00;
        $ratings = Yii::app()->request->getParam('ratings');
        $fb_id = '';
        $fb_id = Yii::app()->request->getParam('fb_id');
        $no_receipt = '';
        $no_receipt = Yii::app()->request->getParam('no_receipt');
        $tip_amount = 0;
        $tip_amount = Yii::app()->request->getParam('tip_amount');
        $feedback_source = '';
        $feedback_source = Yii::app()->request->getParam('feedback_source');
        $api_password = '';
        $api_password = Yii::app()->request->getParam('api_password');
        $cancel_feedback = Yii::app()->request->getParam('cancel_feedback');
        $old_tip_amount = '';
        if (Yii::app()->request->getParam('old_tip_amount'))
            $old_tip_amount = Yii::app()->request->getParam('old_tip_amount');
        $device_type = '';
        if (Yii::app()->request->getParam('device_type'))
            $device_type = Yii::app()->request->getParam('device_type');

        $json = array();
        $car_id_check = true;
        $washrequest_id_check = true;
        $result = 'false';
        $response = 'Pass the required parameters';


        if ((isset($customer_id) && !empty($customer_id)) && (isset($wash_request_id) && !empty($wash_request_id))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $customers_id_check = Customers::model()->findByAttributes(array("id" => $customer_id));
            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id, "customer_id" => $customer_id));

            $cust_feedback_check = Washingfeedbacks::model()->findByAttributes(array("wash_request_id" => $wash_request_id));


            if (!count($customers_id_check)) {
                $response = 'Invalid customer';
            } else if (!count($washrequest_id_check)) {
                $response = 'Invalid wash request id';
            } else {

                if ($tip_amount == 'zero')
                    $tip_amount = 0;

                if (!Yii::app()->request->getParam('tip_amount')) {
                    $tip_amount = $washrequest_id_check->tip_amount;
                }
                if (!count($cust_feedback_check)) {
                    $washfeedbackdata = array(
                        'customer_id' => $customer_id,
                        'agent_id' => $washrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'customer_comments' => $comments,
                        'customer_ratings' => $ratings,
                    );

                    if (!$cancel_feedback)
                        Yii::app()->db->createCommand()->insert('washing_feedbacks', $washfeedbackdata);

                    /* ------------ calculate agent average feedback ---------------- */

                    $washer_total_dropjobs = 0;
                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $washrequest_id_check->agent_id));
                    $total_rate = count($agent_feedbacks);

                    $washerdropjobs = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id = " . $washrequest_id_check->agent_id . " AND action = 'dropjob' AND status = 0")->queryAll();
                    if (!empty($washerdropjobs))
                        $washer_total_dropjobs = $washerdropjobs[0]['count'];
                    if ($total_rate) {
                        $rate = 50;
                        foreach ($agent_feedbacks as $ind => $agent_feedback) {

                            if (!is_numeric($agent_feedback->customer_ratings))
                                $rate += 5;
                            else
                                $rate += $agent_feedback->customer_ratings;
                        }

                        if ($washer_total_dropjobs)
                            $agent_rate = ($rate + $washer_total_dropjobs) / ($total_rate + 10 + $washer_total_dropjobs);
                        else
                            $agent_rate = $rate / ($total_rate + 10);
                        $agent_rate = number_format($agent_rate, 2, '.', '');
                    }
                    else {
                        $agent_rate = 5.00;
                    }


                    $agentmodel = new Agents;
                    if ($agent_rate < 3)
                        $agentmodel->updateAll(array("rating" => $agent_rate, "block_washer" => 1, "forced_logout" => 1), 'id=:id', array(':id' => $washrequest_id_check->agent_id));
                    $agentmodel->updateAll(array("rating" => $agent_rate), 'id=:id', array(':id' => $washrequest_id_check->agent_id));

                    /* ------------ calculate agent average feedback end ---------------- */
                }
                else {
                    if ($cust_feedback_check->customer_ratings == '') {
                        $washfeedbackdata = array(
                            'customer_id' => $customer_id,
                            'customer_comments' => $comments,
                            'customer_ratings' => $ratings,
                        );
                        $washfeedbackmodel = new Washingfeedbacks;

                        $washfeedbackmodel->attributes = $washfeedbackdata;
                        if (!$cancel_feedback)
                            $washfeedbackmodel->updateAll($washfeedbackdata, 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));



                        /* ------------ calculate agent average feedback ---------------- */

                        $washer_total_dropjobs = 0;
                        $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $washrequest_id_check->agent_id));
                        $total_rate = count($agent_feedbacks);

                        $washerdropjobs = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id = " . $washrequest_id_check->agent_id . " AND action = 'dropjob' AND status = 0")->queryAll();
                        if (!empty($washerdropjobs))
                            $washer_total_dropjobs = $washerdropjobs[0]['count'];
                        if ($total_rate) {
                            $rate = 50;
                            foreach ($agent_feedbacks as $ind => $agent_feedback) {

                                if (!is_numeric($agent_feedback->customer_ratings))
                                    $rate += 5;
                                else
                                    $rate += $agent_feedback->customer_ratings;
                            }
                            if ($washer_total_dropjobs)
                                $agent_rate = ($rate + $washer_total_dropjobs) / ($total_rate + 10 + $washer_total_dropjobs);
                            else
                                $agent_rate = $rate / $total_rate + 10;
                            $agent_rate = number_format($agent_rate, 2, '.', '');
                        }
                        else {
                            $agent_rate = 5.00;
                        }


                        $agentmodel = new Agents;
                        $agentmodel->updateAll(array("rating" => $agent_rate), 'id=:id', array(':id' => $washrequest_id_check->agent_id));

                        /* ------------ calculate agent average feedback end ---------------- */
                    }
                }

                $result = 'true';
                $response = "Feeback added";

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id, "customer_id" => $customer_id));

                $plan_ids = '';
                $car_ids = '';

                $plan_ids = $washrequests_data->package_list;
                $car_ids = $washrequests_data->car_list;

                $plan_ids = explode(",", $plan_ids);
                $car_ids = explode(",", $car_ids);

                $total = 0;

                $vehicles = array();
                foreach ($car_ids as $index => $car_id) {
                    $cardata = Vehicle::model()->findByAttributes(array("id" => $car_id));
                    $vehicle_no = $cardata->vehicle_no;
                    $brand_name = $cardata->brand_name;
                    $model_name = $cardata->model_name;
                    $vehicle_type = $cardata->vehicle_type;
                    $vehicle_image = $cardata->vehicle_image;
                    $vehicles[] = array('vehicle_no' => $vehicle_no, 'brand_name' => $brand_name, 'model_name' => $model_name, 'vehicle_type' => $vehicle_type, 'vehicle_image' => $vehicle_image);
                    $plandata = Washingplans::model()->findByAttributes(array("title" => $plan_ids[$index], "vehicle_type" => $vehicle_type));
                    $price = $plandata->price;
                    $fee = $plandata->handling_fee;
                    $total += $price + $fee;
                }

                $total = number_format($total, 2, '.', '');

                Washingrequests::model()->updateByPk($wash_request_id, array('tip_amount' => $tip_amount));

                /* ------- send order receipt ----------- */

                if ((!$washrequest_id_check->is_order_receipt_sent) && (!$no_receipt)) {
                    /* $handle = curl_init(ROOT_URL."/api/index.php?r=washing/sendorderreceipts");
                      $data = array('wash_request_id' => $wash_request_id, 'customer_id' =>$customer_id, 'agent_id'=> $washrequest_id_check->agent_id, "key" => API_KEY);
                      curl_setopt($handle, CURLOPT_POST, true);
                      curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                      curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                      $receiptresult = curl_exec($handle);
                      curl_close($handle);
                      $receiptdetails = json_decode($receiptresult);
                      //var_dump($jsondata); */

                    //$receiptresult = $this->actionsendorderreceipts($wash_request_id, $customer_id, $washrequest_id_check->agent_id, 'true', API_KEY, $api_token, $t1, $t2, $user_type, $user_id);
                    //$receiptdetails = json_decode($receiptresult);
                    Washingrequests::model()->updateByPk($wash_request_id, array('is_order_receipt_sent' => 1));
                }

                /* ------- send order receipt end ----------- */

                if ($washrequest_id_check->agent_id)
                    $agent_detail = Agents::model()->findByAttributes(array("id" => $washrequest_id_check->agent_id));

                $message = "<div class='block-content' style='background: #fff; text-align: left;'>";
                if ($washrequest_id_check->status == 4)
                    $message .= "<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer Wash Complete Feedback</h2>";
                if (($washrequest_id_check->status == 5) || ($washrequest_id_check->status == 6))
                    $message .= "<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer Reason for Cancellation</h2>";
                $message .= "<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000" . $wash_request_id . "</p>
<p><b>Customer Name:</b> " . $customers_id_check->first_name . " " . $customers_id_check->last_name . "</p>
<p><b>Customer Email:</b> " . $customers_id_check->email . "</p>
<p><b>Rating by Customer:</b> " . number_format($ratings, 2, '.', '') . "</p>
<p><b>Comments:</b> " . $comments . "</p>";
                if ($washrequest_id_check->agent_id)
                    $message .= "<p><b>Service Provider Name:</b> #" . $agent_detail->real_washer_id . " " . $agent_detail->first_name . " " . $agent_detail->last_name . "</p>";

                if ($fb_id)
                    $message .= "<p><b>Facebook/Instagram handle:</b> " . $fb_id . "</p>";

                $to = Vargas::Obj()->getAdminToEmail();
                $from = Vargas::Obj()->getAdminFromEmail();
                $subject = '';
                if ($washrequest_id_check->status == 4) {
                    $subject = 'Customer Wash Complete Feedback';

                    if ((!empty($washrequest_id_check->is_feedback_sent)) || (number_format($ratings, 1, '.', '') < 4.5) || (!empty($comments)))
                        Vargas::Obj()->SendMail($to, $from, $message, $subject . " - Order #0000" . $wash_request_id, 'mail-receipt');
                }
                if (($washrequest_id_check->status == 5) || ($washrequest_id_check->status == 6)) {
                    $subject = 'Customer Reason for Cancellation';
                    if ($comments)
                        Vargas::Obj()->SendMail($to, $from, $message, $subject . " - Order #0000" . $wash_request_id, 'mail-receipt');
                }

                Customers::model()->updateByPk($customer_id, array('fb_id' => $fb_id));

                if ($device_type == 'IOS') {

                    if (number_format($tip_amount, 2) != number_format($old_tip_amount, 2)) {

                        $washeractionlogdata = array(
                            'wash_request_id' => $wash_request_id,
                            //'admin_username' => $admin_username,
                            'agent_id' => $washrequest_id_check->agent_id,
                            'addi_detail' => '$' . number_format($old_tip_amount, 2) . ' to $' . number_format($tip_amount, 2),
                            'action' => 'customertipamount',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
                } else {
                    if (number_format($tip_amount, 2) != number_format($washrequest_id_check->tip_amount, 2)) {

                        $washeractionlogdata = array(
                            'wash_request_id' => $wash_request_id,
                            //'admin_username' => $admin_username,
                            'agent_id' => $washrequest_id_check->agent_id,
                            'addi_detail' => '$' . number_format($washrequest_id_check->tip_amount, 2) . ' to $' . number_format($tip_amount, 2),
                            'action' => 'customertipamount',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                    }
                }
                if (is_numeric($ratings) && (!$cancel_feedback))
                    $logcomment = $comments . " (Ratings: " . $ratings . ")";
                else
                    $logcomment = $comments;

                if (Yii::app()->request->getParam('comments') != '') {
                    $washeractionlogdata = array(
                        'agent_id' => $washrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'action' => 'customerfeedback',
                        'addi_detail' => $logcomment,
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }

                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdetails = json_decode($kartapiresult);

                /* ----------- update pricing details -------------- */

                Washingrequests::model()->updateByPk($wash_request_id, array('total_price' => $kartdetails->total_price, 'net_price' => $kartdetails->net_price, 'company_total' => $kartdetails->company_total, 'agent_total' => $kartdetails->agent_total, 'is_feedback_sent' => 1));

                /* ----------- update pricing details end -------------- */
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'total' => $total,
            'vehicles' => $vehicles
        );

        echo json_encode($json);
        die();
    }

    /*
     * * Returns totals and washed car lists.
     * * Post Required: agent id,wash_request_id,comments,ratings
     * * Url:- http://www.demo.com/index.php?r=washing/agentfeedback
     * * Purpose:- Getting totals and washed car lists
     */

    public function actionagentfeedback() {

        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        if (Yii::app()->request->getParam('api_password') != AES256CBC_API_PASS) {
            $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

            if (!$token_check) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Invalid request'
                );
                echo json_encode($json);
                die();
            }
        }

        $agent_id = Yii::app()->request->getParam('agent_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $comments = '';
        $comments = Yii::app()->request->getParam('comments');
        $ratings = 5.00;
        $ratings = Yii::app()->request->getParam('ratings');
        $feedback_source = '';
        $feedback_source = Yii::app()->request->getParam('feedback_source');
        $simulate_rating = Yii::app()->request->getParam('simulate_rating');
        $ratingdecrease = Yii::app()->request->getParam('ratingdecrease');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $cancel_feedback = Yii::app()->request->getParam('cancel_feedback');

        /* echo "agent id: ".$agent_id."<br>";
          echo "wash id: ".$wash_request_id."<br>";
          echo "comments: ".$comments."<br>";
          echo "rating: ".$ratings."<br>";
          echo "feedback source: ".$feedback_source."<br>"; */

        $json = array();
        $car_id_check = true;
        $washrequest_id_check = true;
        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($agent_id) && !empty($agent_id)) && (isset($wash_request_id) && !empty($wash_request_id))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $agents_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));
            $washrequest_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            $agent_feedback_check = Washingfeedbacks::model()->findByAttributes(array("wash_request_id" => $wash_request_id));
            $cust_check = Customers::model()->findByAttributes(array("id" => $washrequest_id_check->customer_id));

            if (!count($agents_id_check)) {
                $response = 'Invalid agent';
            } else if (!count($washrequest_id_check)) {
                $response = 'Invalid wash request id';
            } else {

                if (($feedback_source == 'dropjob') && ($agent_id)) {
                    if (!$washrequest_id_check->is_scheduled) {
                        $washeractionlogdata = array(
                            'agent_id' => $agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agents_id_check->real_washer_id,
                            'action' => 'agentfeedback',
                            'addi_detail' => $comments,
                            'action_date' => date('Y-m-d H:i:s'));
                    }

                    if ($ratingdecrease == 'no') {
                        $washeractionlogdata2 = array(
                            'agent_id' => $agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agents_id_check->real_washer_id,
                            'action' => 'dropjob_ratingunchange',
                            'addi_detail' => $comments,
                            'action_date' => date('Y-m-d H:i:s'));
                    } else {
                        $washeractionlogdata2 = array(
                            'agent_id' => $agent_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_company_id' => $agents_id_check->real_washer_id,
                            'action' => 'dropjob',
                            'addi_detail' => $comments,
                            'action_date' => date('Y-m-d H:i:s'));
                    }



                    if (!$simulate_rating) {
                        if (!$washrequest_id_check->is_scheduled)
                            Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata2);
                    }

                    /* ------------ calculate agent average feedback ---------------- */

                    $washer_total_dropjobs = 0;
                    $agent_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("agent_id" => $agent_id));
                    $total_rate = count($agent_feedbacks);

                    $washerdropjobs = Yii::app()->db->createCommand("SELECT COUNT(*) as count FROM activity_logs WHERE agent_id = :agent_id AND action = 'dropjob' AND status = 0")->bindValue(':agent_id', $agent_id, PDO::PARAM_STR)->queryAll();

                    if (!empty($washerdropjobs))
                        $washer_total_dropjobs = $washerdropjobs[0]['count'];
                    if ($simulate_rating)
                        $washer_total_dropjobs += 1;
                    if ($total_rate) {
                        $rate = 50;
                        foreach ($agent_feedbacks as $ind => $agent_feedback) {

                            if (!is_numeric($agent_feedback->customer_ratings))
                                $rate += 5;
                            else
                                $rate += $agent_feedback->customer_ratings;
                        }

                        if ($washer_total_dropjobs) {
                            $agent_rate = ($rate + $washer_total_dropjobs) / ($total_rate + 10 + $washer_total_dropjobs);
                        } else
                            $agent_rate = $rate / ($total_rate + 10);

                        $agent_rate = number_format($agent_rate, 2, '.', '');
                    }
                    else {
                        $agent_rate = 5.00;
                    }


                    $agentmodel = new Agents;
                    if ($agent_rate < 3)
                        $agentmodel->updateAll(array("rating" => $agent_rate, "block_washer" => 1, 'forced_logout' => 1), 'id=:id', array(':id' => $washrequest_id_check->agent_id));
                    if (!$simulate_rating)
                        $agentmodel->updateAll(array("rating" => $agent_rate), 'id=:id', array(':id' => $agent_id));

                    /* ------------ calculate agent average feedback end ---------------- */
                }

                if ($feedback_source != 'dropjob') {
                    if (!count($agent_feedback_check)) {

                        $washfeedbackdata = array(
                            'agent_id' => $agent_id,
                            'customer_id' => $washrequest_id_check->customer_id,
                            'wash_request_id' => $wash_request_id,
                            'agent_comments' => $comments,
                            'agent_ratings' => $ratings,
                        );

                        if (!$cancel_feedback)
                            Yii::app()->db->createCommand()->insert('washing_feedbacks', $washfeedbackdata);


                        /* ------------ calculate customer average feedback ---------------- */

                        $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $washrequest_id_check->customer_id));
                        $total_rate = count($cust_feedbacks);
                        if ($total_rate) {
                            $rate = 50;
                            foreach ($cust_feedbacks as $ind => $cust_feedback) {

                                if (!is_numeric($cust_feedback->agent_ratings))
                                    $rate += 5;
                                else
                                    $rate += $cust_feedback->agent_ratings;
                            }

                            $cust_rate = $rate / ($total_rate + 10);
                            $cust_rate = number_format($cust_rate, 2, '.', '');
                        }
                        else {
                            $cust_rate = 5.00;
                        }

                        $custmodel = new Customers;
                        $custmodel->updateAll(array("rating" => $cust_rate), 'id=:id', array(':id' => $washrequest_id_check->customer_id));

                        /* ------------ calculate customer average feedback end ---------------- */
                    } else {
                        if ($agent_feedback_check->agent_ratings == '') {
                            $washfeedbackdata = array(
                                'agent_id' => $agent_id,
                                'agent_comments' => $comments,
                                'agent_ratings' => $ratings,
                            );
                            $washfeedbackmodel = new Washingfeedbacks;

                            $washfeedbackmodel->attributes = $washfeedbackdata;
                            if (!$cancel_feedback)
                                $washfeedbackmodel->updateAll($washfeedbackdata, 'wash_request_id=:wash_request_id', array(':wash_request_id' => $wash_request_id));


                            /* ------------ calculate customer average feedback ---------------- */

                            $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $washrequest_id_check->customer_id));
                            $total_rate = count($cust_feedbacks);
                            if ($total_rate) {
                                $rate = 50;
                                foreach ($cust_feedbacks as $ind => $cust_feedback) {

                                    if (!is_numeric($cust_feedback->agent_ratings))
                                        $rate += 5;
                                    else
                                        $rate += $cust_feedback->agent_ratings;
                                }

                                $cust_rate = $rate / ($total_rate + 10);
                                $cust_rate = number_format($cust_rate, 2, '.', '');
                            }
                            else {
                                $cust_rate = 5.00;
                            }

                            $custmodel = new Customers;
                            $custmodel->updateAll(array("rating" => $cust_rate), 'id=:id', array(':id' => $washrequest_id_check->customer_id));

                            /* ------------ calculate customer average feedback end ---------------- */
                        }
                    }

                    if ($agent_id) {
                        // && (!$cancel_feedback)
                        if (is_numeric($ratings))
                            $logcomment = $comments . " (Rating: " . $ratings . ")";
                        else
                            $logcomment = $comments;
                        if (Yii::app()->request->getParam('comments') != '') {
                            $washeractionlogdata = array(
                                'agent_id' => $agent_id,
                                'wash_request_id' => $wash_request_id,
                                'agent_company_id' => $agents_id_check->real_washer_id,
                                'action' => 'agentfeedback',
                                'addi_detail' => $logcomment,
                                'action_date' => date('Y-m-d H:i:s'));

                            Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                        }
                    }
                }

                $result = 'true';
                $response = "Feeback added";

                $washrequests_data = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id, "agent_id" => $agent_id));

                $plan_ids = '';
                $car_ids = '';

                $plan_ids = $washrequests_data->package_list;
                $car_ids = $washrequests_data->car_list;

                $plan_ids = explode(",", $plan_ids);
                $car_ids = explode(",", $car_ids);

                $total = 0;

                $vehicles = array();
                foreach ($car_ids as $index => $car_id) {
                    $cardata = Vehicle::model()->findByAttributes(array("id" => $car_id));
                    $vehicle_no = $cardata->vehicle_no;
                    $brand_name = $cardata->brand_name;
                    $model_name = $cardata->model_name;
                    $vehicle_type = $cardata->vehicle_type;
                    $vehicle_image = $cardata->vehicle_image;
                    $vehicles[] = array('vehicle_no' => $vehicle_no, 'brand_name' => $brand_name, 'model_name' => $model_name, 'vehicle_type' => $vehicle_type, 'vehicle_image' => $vehicle_image);
                    $plandata = Washingplans::model()->findByAttributes(array("title" => $plan_ids[$index], "vehicle_type" => $vehicle_type));
                    $price = $plandata->price;
                    $fee = $plandata->handling_fee;
                    $total += $price + $fee;
                }

                $total = number_format($total, 2, '.', '');

                if (!$simulate_rating) {
                    Washingrequests::model()->updateByPk($wash_request_id, array('canceled_washer_id' => 0, 'is_washer_submit_feedback' => 1));

                    $message = "<div class='block-content' style='background: #fff; text-align: left;'>";
                    if ($washrequest_id_check->status == 4)
                        $message .= "<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Washer Wash Complete Feedback</h2>";
                    else
                        $message .= "<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Washer Reason for Cancellation</h2>";
                    $message .= "<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000" . $wash_request_id . "</p>
<p><b>Washer Name:</b> " . $agents_id_check->first_name . " " . $agents_id_check->last_name . "</p>
<p><b>Washer Email:</b> " . $agents_id_check->email . "</p>
<p><b>Rating by Washer:</b> " . number_format($ratings, 2, '.', '') . "</p>
<p><b>Comments:</b> " . $comments . "</p>";
                    if ($washrequest_id_check->agent_id)
                        $message .= "<p><b>Customer Name:</b> " . $cust_check->first_name . " " . $cust_check->last_name . "</p>";


                    $to = Vargas::Obj()->getAdminToEmail();
                    $from = Vargas::Obj()->getAdminFromEmail();
                    $subject = '';
                    if ($washrequest_id_check->status == 4)
                        $subject = 'Washer Wash Complete Feedback';
                    else
                        $subject = 'Washer Reason for Cancellation';
                    if ((!empty($comments)))
                        Vargas::Obj()->SendMail($to, $from, $message, $subject . " - Order #0000" . $wash_request_id, 'mail-receipt');
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'total' => $total,
            'vehicles' => $vehicles,
            'rating' => $agent_rate
        );

        echo json_encode($json);
        die();
    }

    public function actionresetrejectedwashrequests() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }


        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $pendingrequests = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE status = 0 AND is_scheduled = 0")->queryAll();

        if (count($pendingrequests)) {
            foreach ($pendingrequests as $wrequest) {

                /* ------- get nearest agents --------- */

                $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
                $data = array('wash_request_id' => $wrequest['id'], "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($handle);
                curl_close($handle);
                $nearagentsdetails = json_decode($output);


                /* ------- get nearest agents end --------- */

                if ($nearagentsdetails->result == 'true') {

                    $all_reject_ids = $wrequest['agent_reject_ids'];
                    $all_reject_ids_arr = explode(",", $all_reject_ids);
                    $all_reject_ids_arr_new = array();
                    foreach ($all_reject_ids_arr as $val)
                        $all_reject_ids_arr_new[] = abs($val);
                    $everyone_rejects = true;
//print_r($all_reject_ids_arr_new);
                    foreach ($nearagentsdetails->nearest_agents as $agid => $nearagentdis) {
//echo $agid."<br>";
                        if (!in_array($agid, $all_reject_ids_arr_new)) {
                            $everyone_rejects = false;
                            break;
                        }
                    }


                    if ($everyone_rejects) {
                        echo "clearing rejects of wash #" . $wrequest['id'] . "<br>";
                        Washingrequests::model()->updateByPk($wrequest['id'], array('agent_reject_ids' => '', 'order_temp_assigned' => 0));
                    }
                }


                /* -------- ondemand 10 min no washer available check -------- */

                if (!$wrequest['agent_id']) {
                    if (strtotime($wrequest['wash_begin']) > 0)
                        $wash_time = strtotime($wrequest['wash_begin']);
                    else
                        $wash_time = strtotime($wrequest['created_date']);
                    $now_time = time();
                    $time_diff = round(abs($now_time - $wash_time) / 60, 2);

                    if ($time_diff >= 10) {


                        if ($wrequest['transaction_id']) {
                            if ($wrequest['wash_request_position'] == 'real')
                                Yii::app()->braintree->void_real($wrequest['transaction_id']);
                            else
                                Yii::app()->braintree->void($wrequest['transaction_id']);
                        }


                        if ((!$wrequest['status']) && (!$wrequest['no_washer_cancel'])) {
                            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest['customer_id'] . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '22' ")->queryAll();
                            $message = $pushmsg[0]['message'];

                            foreach ($clientdevices as $ctdevice) {
                                $device_type = strtolower($ctdevice['device_type']);
                                $notify_token = $ctdevice['device_token'];
                                $alert_type = "schedule";
                                $notify_msg = urlencode($message);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                file_put_contents("washing_notificaiton.log", "order id " . $wrequest['id'] . " - " . "customer id " . $ctdevice['customer_id'] . " " . "device id " . $ctdevice['id'] . " " . $notifyurl . " source: resetrejectedwashrequests cron<br><br>", FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }

                        Washingrequests::model()->updateByPk($wrequest['id'], array('status' => 5, 'no_washer_cancel' => 1));

                        $logdata = array(
                            'agent_id' => $wrequest['agent_id'],
                            'wash_request_id' => $wrequest['id'],
                            'agent_company_id' => 0,
                            'action' => 'ondemandautocancel',
                            'action_date' => date('Y-m-d H:i:s'));

                        Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

                        if ((APP_ENV == 'real')) {
                            //if(($result == 'true') && ($wash_now_canceled == 1)){
                            $mobile_receipt = '';

                            $kartapiresult = $this->washingkart($wrequest['id'], API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                            $kartdata = json_decode($kartapiresult);

                            foreach ($kartdata->vehicles as $ind => $vehicle) {
                                $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                                if ($vehicle->surge_vehicle_fee > 0) {
                                    $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                                }
                                if ($vehicle->extclaybar_vehicle_fee > 0) {

                                    $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                                }
                                if ($vehicle->waterspotremove_vehicle_fee > 0) {

                                    $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                                }
                                if ($vehicle->exthandwax_vehicle_fee > 0) {

                                    $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                                }

                                if ($vehicle->pet_hair_fee > 0) {

                                    $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                                }
                                if ($vehicle->lifted_vehicle_fee > 0) {

                                    $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                                }

                                if ($vehicle->extplasticdressing_vehicle_fee > 0) {

                                    $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                                }

                                if ($vehicle->upholstery_vehicle_fee > 0) {

                                    $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                                }

                                if ($vehicle->floormat_vehicle_fee > 0) {

                                    $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                                }

                                if (($ind == 0) && ($kartdata->coupon_discount > 0)) {

                                    $mobile_receipt .= "Promo: " . $kartdata->coupon_code . " -$" . number_format($kartdata->coupon_discount, 2) . "\r\n";
                                }


                                if ($vehicle->fifth_wash_discount > 0) {

                                    $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                                }

                                if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {

                                    $mobile_receipt .= "Bundle -$1.00\r\n";
                                }

                                if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {

                                    $mobile_receipt .= "Bundle -$1.00\r\n";
                                }
                                $mobile_receipt .= "------\r\n";
                            }

                            if ($kartdata->tip_amount > 0) {
                                $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
                            }

                            if ($kartdata->wash_now_fee > 0) {
                                $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
                            }

                            if ($kartdata->wash_later_fee > 0) {
                                $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
                            }


                            $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
                            if (APP_ENV == 'real') {
                                $this->layout = "xmlLayout";


                                //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                                $account_sid = TWILIO_SID;
                                $auth_token = TWILIO_AUTH_TOKEN;

                                $client = new Services_Twilio($account_sid, $auth_token);

                                $customers_id_check = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));

                                $smscontent = "WASH NOW AUTO CANCELED #000" . $wrequest['id'] . "- " . date('M d', strtotime($wrequest['order_for'])) . " @ " . date('h:i A', strtotime($wrequest['order_for'])) . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $wrequest['address'] . "\r\n (" . $wrequest['address_type'] . ")\r\n------\r\n" . $mobile_receipt;


                                try {
                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '8183313631',
                                        'From' => '+13103128070',
                                        'Body' => $smscontent,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }

                                try {

                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '3109999334',
                                        'From' => '+13103128070',
                                        'Body' => $smscontent,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }

                                try {

                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '3103442534',
                                        'From' => '+13103128070',
                                        'Body' => $smscontent,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }
                            }
                        }
                    }
                }
                /* -------- ondemand 10 min no washer available check end -------- */
            }
        }

        $admin_edits = Washingrequests::model()->findAll(array("condition" => "is_admin_editing != 0"));

        if (count($admin_edits)) {
            foreach ($admin_edits as $adminedit) {
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $last_edit_time = strtotime($adminedit->admin_last_edit);
                $min_diff = 0;
                if ($current_time > $last_edit_time) {
                    $min_diff = round(($current_time - $last_edit_time) / 60, 2);
                }

                if ($min_diff >= 1) {
                    Washingrequests::model()->updateByPk($adminedit->id, array('is_admin_editing' => 0));
                }
            }
        }

        $clientwashernomeetwashes = Yii::app()->db->createCommand("SELECT id, customer_id, agent_id, washer_arrived_at, meet_washer_10min_alert, meet_washer_20min_alert FROM washing_requests WHERE status = 2 AND meet_washer_outside != 'yes' AND (meet_washer_20min_alert = 0 OR meet_washer_10min_alert = 0)")->queryAll();

        if (count($clientwashernomeetwashes)) {
            foreach ($clientwashernomeetwashes as $nomeetwash) {
                $min_diff = 0;
                $current_time = strtotime(date('Y-m-d H:i:s'));

                if ($current_time > strtotime($nomeetwash['washer_arrived_at']) && $nomeetwash['washer_arrived_at'] != '0000-00-00 00:00:00') {
                    $min_diff = round(($current_time - strtotime($nomeetwash['washer_arrived_at'])) / 60, 2);
                }
                if (($min_diff >= 10) && (!$nomeetwash['meet_washer_10min_alert'])) {
                    $cust_details = Customers::model()->findByAttributes(array('id' => $nomeetwash['customer_id']));
                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $nomeetwash['customer_id'] . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '50' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    if (((APP_ENV == 'real')) && ($cust_details->sms_control)) {
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);


                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $cust_details->contact_number,
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }


                    foreach ($clientdevices as $ctdevice) {

                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }

                    $agent_details = Agents::model()->findByAttributes(array('id' => $nomeetwash['agent_id']));

                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $nomeetwash['agent_id'] . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '49' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    foreach ($agentdevices as $agdevice) {

                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }

                    if ((APP_ENV == 'real')) {
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;

                        $client = new Services_Twilio($account_sid, $auth_token);

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $agent_details->phone_number,
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }


                    Washingrequests::model()->updateByPk($nomeetwash['id'], array('meet_washer_10min_alert' => 1));
                }
                if (($min_diff >= 15) && (!$nomeetwash['meet_washer_20min_alert'])) {

                    $agent_details = Agents::model()->findByAttributes(array('id' => $nomeetwash['agent_id']));

                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $nomeetwash['agent_id'] . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '51' ")->queryAll();

                    $message = $pushmsg[0]['message'];

                    foreach ($agentdevices as $agdevice) {
                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }

                    if ((APP_ENV == 'real')) {
                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;

                        $client = new Services_Twilio($account_sid, $auth_token);

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => $agent_details->phone_number,
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }
                    }

                    Washingrequests::model()->updateByPk($nomeetwash['id'], array('meet_washer_20min_alert' => 1));
                }
            }
        }
    }

    public function actionrejectwashrequest() {
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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');

        $is_scheduled = Yii::app()->request->getParam('is_scheduled');
        $json = array();
        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))) {

            if (AES256CBC_STATUS == 1) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
                $status = $this->aes256cbc_crypt($status, 'd', AES256CBC_API_PASS);
            }
            $status = -1 * abs($status);

            $wash_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            if (!count($wash_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else if ($status >= 0) {
                $result = 'false';
                $response = 'Invalid status code. use negative value';
            } else if (($wash_id_check->status == 5) || ($wash_id_check->status == 6)) {
                $result = 'false';
                $response = 'Sorry, this order is canceled';
            } else {
                $result = 'true';
                $response = 'wash request rejected';
                $status_text = '';
                $saved_reject_ids = '';
                $status_text = $wash_id_check->agent_reject_ids;
                $saved_reject_ids = $wash_id_check->all_reject_ids;
                if ($status_text == '') {
                    $status_text = $status;
                } else {
                    $status_text .= "," . $status;
                }

                if ($saved_reject_ids == '') {
                    $saved_reject_ids = abs($status);
                } else {
                    $saved_reject_ids .= "," . abs($status);
                }

                //$status_text = rtrim($status_text, ',');
                if ($is_scheduled) {
                    Washingrequests::model()->updateByPk($wash_request_id, array('agent_reject_ids' => $status_text, 'all_reject_ids' => $saved_reject_ids, 'create_wash_push_sent' => 0));
                } else {

                    $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings` WHERE `app_type` = 'IOS'")->queryAll();
                    $agentlocation = AgentLocations::model()->findByAttributes(array('agent_id' => abs($status)));

                    /* --------- distance calculation ------------ */

                    $theta = $wash_id_check->longitude - $agentlocation->longitude;
                    $dist = sin(deg2rad($wash_id_check->latitude)) * sin(deg2rad($agentlocation->latitude)) + cos(deg2rad($wash_id_check->latitude)) * cos(deg2rad($agentlocation->latitude)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                    $unit = strtoupper($unit);

                    if (($miles > 0) && ($miles <= $app_settings[0]['washer_search_radius'])) {

                        /* ------- get nearest agents --------- */
                        /* $handle = curl_init(ROOT_URL."/api/index.php?r=agents/getnearestagents");
                          $data = array('wash_request_id' => $wash_request_id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY);
                          curl_setopt($handle, CURLOPT_POST, true);
                          curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                          curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
                          $output = curl_exec($handle);
                          curl_close($handle);
                          $nearagentsdetails = json_decode($output); */
                        /* ------- get nearest agents end --------- */

                        /* if($nearagentsdetails->result == 'true')
                          {
                          end($nearagentsdetails->nearest_agents);
                          $last_agent_id = key($nearagentsdetails->nearest_agents);
                          reset($nearagentsdetails->nearest_agents); */

                        /* if(abs($status) == $last_agent_id)
                          {
                          Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => '', 'order_temp_assigned' => 0, 'all_reject_ids' => $saved_reject_ids, 'create_wash_push_sent' => 0 ));



                          $total_rejects_array = explode(',',$saved_reject_ids);
                          $num_rejects_per_agents = array_count_values($total_rejects_array);

                          if($num_rejects_per_agents[$last_agent_id] >= 2)
                          {
                          // Washingrequests::model()->updateByPk($wash_request_id, array('is_two_loops_reject' => 1, 'create_wash_push_sent' => 0)); //make wash available for schedule
                          }
                          } */
                        //else{
                        Washingrequests::model()->updateByPk($wash_request_id, array('agent_reject_ids' => $status_text, 'all_reject_ids' => $saved_reject_ids, 'order_temp_assigned' => 0, 'create_wash_push_sent' => 0));


                        $ondmeandpendingwashes = Washingrequests::model()->findAllByAttributes(array("status" => 0, "is_scheduled" => 0), array('order' => 'order_for ASC'));
                        $agent_has_order = 0;
                        foreach ($ondmeandpendingwashes as $pwash) {
                            $mile_check = 0;
                            $theta = $pwash->longitude - $agentlocation->longitude;
                            $dist = sin(deg2rad($pwash->latitude)) * sin(deg2rad($agentlocation->latitude)) + cos(deg2rad($pwash->latitude)) * cos(deg2rad($agentlocation->latitude)) * cos(deg2rad($theta));
                            $dist = acos($dist);
                            $dist = rad2deg($dist);
                            $mile_check = $dist * 60 * 1.1515;
                            $unit = strtoupper($unit);
                            if (($mile_check > 0) && ($mile_check <= $app_settings[0]['washer_search_radius'])) {
                                $rejected_ids = explode(",", $pwash->agent_reject_ids);

                                if (!in_array($status, $rejected_ids)) {

                                    $agent_has_order = 1;

                                    break;
                                }
                            }
                        }

                        if (!$agent_has_order) {

                            $ondmeandagentrejectwashes = Washingrequests::model()->findAllByAttributes(array("status" => 0, "is_scheduled" => 0), array('order' => 'order_for ASC'));
                            foreach ($ondmeandagentrejectwashes as $rwash) {
                                $rejected_ids = explode(",", $rwash->agent_reject_ids);

                                if (($key = array_search($status, $rejected_ids)) !== false) {
                                    unset($rejected_ids[$key]);
                                    $rejected_ids = array_values($rejected_ids);
                                }
                                $reject_ids_str = implode(',', $rejected_ids);
                                Washingrequests::model()->updateByPk($rwash->id, array('agent_reject_ids' => $reject_ids_str));
                            }
                        }
                        //}
                        // }
                    }

                    //else Washingrequests::model()->updateByPk($wash_request_id, array( 'agent_reject_ids' => $status_text, 'order_temp_assigned' => 0 ));
                }
                /* if($wash_id_check->agent_id){
                  $agent_detail = Agents::model()->findByAttributes(array("id"=>$wash_id_check->agent_id));
                  $washeractionlogdata = array(
                  'wash_request_id'=> $wash_request_id,
                  'action'=> 'cancelorderwasher',
                  'agent_id'=> $wash_id_check->agent_id,
                  'agent_company_id'=> $agent_detail->real_washer_id,
                  'action_date'=> date('Y-m-d H:i:s'));
                  Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                  } */
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
        );

        echo json_encode($json);
        die();
    }

    public function actionWashingTotalPrice() {
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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if (!empty($wash_request_id)) {
            // $washid =  Yii::app()->db->createCommand("SELECT customer_id FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
            $wash_request_details = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = :id")->bindValue(':id', $wash_request_id, PDO::PARAM_STR)->queryAll();
            $customer_id = $wash_request_details[0]['customer_id'];
            $washing_status = $wash_request_details[0]['status'];
            if ($washing_status == 5 || $washing_status == 6) {
                $data = array(
                    'Error' => 'This Order already Canceled'
                );
                echo json_encode($data);
                die();
            } else {
                $customers_request = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE status IN ('4', '5', '6') AND `customer_id` = :customer_id")->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)->queryAll();
                if (!empty($customers_request)) {

                    // OLD CUSTOMER
                    //$old_customer =  Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = '$wash_request_id'")->queryAll();
                    $car_details = array();
                    $package_name = array();
                    foreach ($wash_request_details as $request_details) {
                        $car_list = explode(',', $request_details['car_list']);
                        $package_title = explode(',', $request_details['package_list']);

                        foreach ($car_list as $cars) {
                            $car_details[] = $cars;
                        }
                        foreach ($package_title as $packages) {
                            $package_name[] = $packages;
                        }
                    }

                    $customer_vehicals = array();
                    $cardetails = array();
                    $k = -1;
                    $counter = 0;
                    foreach ($car_details as $list) {
                        $counter++;

                        $k++;
                        $listcar = Yii::app()->db->createCommand("SELECT * FROM `customer_vehicals` WHERE `id` = '$list'")->queryAll();

                        foreach ($listcar as $carlist) {


                            $title_plan = $package_name[$k];

                            $vehicle_type = $carlist['vehicle_type'];
                            $washing_plans = Yii::app()->db->createCommand("SELECT title, price FROM `washing_plans` WHERE `vehicle_type` = '$vehicle_type' AND title = '$title_plan'")->queryAll();


                            unset($carlist['customer_id']);
                            //$cardetails[] = $carlist;
                            $cardetails[] = array_merge($carlist, $washing_plans[0]);

                            array_push($customer_vehicals, $carlist['vehicle_type']);
                        }
                    }

                    $i = -1;
                    $totalpice = 0;
                    $j = 0;
                    foreach ($customer_vehicals as $vehicals) {
                        $j++;
                        $i++;
                        $title_plan = $package_name[$i];

                        $price = Yii::app()->db->createCommand("SELECT SUM(price) AS TotalPrice FROM `washing_plans` WHERE `vehicle_type` = '$vehicals' AND title = '$title_plan'")->queryAll();
                        foreach ($price as $pice) {

                            $totalpice += $pice['TotalPrice'];
                        }
                    }

                    if ($j == 2) {
                        $newprice = $totalpice - 3;
                        $discount = 3;
                    } elseif ($j == 3) {
                        $newprice = $totalpice - 6;
                        $discount = 6;
                    } elseif ($j == 4) {
                        $newprice = $totalpice - 9;
                        $discount = 9;
                    } else {
                        $newprice = $totalpice;
                        $discount = 0;
                    }




                    $countprice = Yii::app()->db->createCommand("SELECT car_list FROM `washing_requests` WHERE status IN ('4') AND `customer_id` = '$customer_id'")->queryAll();
                    $carcount = array();
                    foreach ($countprice as $z) {
                        $count = explode(',', $z['car_list']);
                        foreach ($count as $c) {
                            $cnt = array_push($carcount, $c);
                        }
                    }
                    $totalcarwash = count($carcount);
                    if ($totalcarwash == 2) {
                        $iteration_number = 1;
                    } elseif ($totalcarwash == 3) {
                        $iteration_number = 2;
                    } elseif ($totalcarwash == 4) {
                        $iteration_number = 3;
                    } elseif ($totalcarwash >= 5) {
                        $iteration_number = 0;
                    }
                    if ($totalcarwash % 5 == 0) {
                        $finalprice = $newprice - 5;
                        $data = array();
                        $data = array(
                            'result' => true,
                            'response' => 'price',
                            'total_price' => $finalprice,
                            'bundle_discount' => $discount,
                            'iteration_discount' => 5,
                            'iteration_number' => $iteration_number,
                            'new_customer_discount' => 0,
                            'vehicles' => $cardetails
                        );
                    } else {
                        $finalprice = $newprice;
                        $finaldiscount = $discount;
                        $data = array();
                        $data = array(
                            'result' => true,
                            'response' => 'price',
                            'total_price' => $finalprice,
                            'bundle_discount' => $discount,
                            'iteration_discount' => 0,
                            'iteration_number' => $iteration_number,
                            'new_customer_discount' => 0,
                            'vehicles' => $cardetails
                        );
                    }


                    echo json_encode($data);
                    die();
                } else {

                    // NEW CUSTOMER
                    $new_customer = Yii::app()->db->createCommand("SELECT * FROM `washing_requests` WHERE `id` = :id")->bindValue(':id', $wash_request_id, PDO::PARAM_STR)->queryAll();
                    $car_details = array();
                    $package_name = array();
                    foreach ($wash_request_details as $request_details) {
                        $car_list = explode(',', $request_details['car_list']);
                        $package_title = explode(',', $request_details['package_list']);

                        foreach ($car_list as $cars) {
                            $car_details[] = $cars;
                        }
                        foreach ($package_title as $packages) {
                            $package_name[] = $packages;
                        }
                    }
                    $customer_vehicals = array();
                    $cardetails = array();
                    $k = -1;
                    foreach ($car_details as $list) {
                        $k++;
                        $listcar = Yii::app()->db->createCommand("SELECT * FROM `customer_vehicals` WHERE `id` = '$list'")->queryAll();
                        foreach ($listcar as $carlist) {
                            $title_plan = $package_name[$k];

                            $vehicle_type = $carlist['vehicle_type'];
                            $washing_plans = Yii::app()->db->createCommand("SELECT title, price FROM `washing_plans` WHERE `vehicle_type` = '$vehicle_type' AND title = '$title_plan'")->queryAll();


                            unset($carlist['customer_id']);
                            //$cardetails[] = $carlist;

                            $cardetails[] = array_merge($carlist, $washing_plans[0]);

                            array_push($customer_vehicals, $carlist['vehicle_type']);
                        }
                    }
                    $i = -1;
                    $totalpice = 0;
                    $j = 0;
                    foreach ($customer_vehicals as $vehicals) {
                        $j++;
                        $i++;
                        $tit = $titl[$i];

                        $price = Yii::app()->db->createCommand("SELECT SUM(price) AS TotalPrice FROM `washing_plans` WHERE `vehicle_type` = '$vehicals' AND title = '$tit'")->queryAll();

                        foreach ($price as $pice) {

                            $totalpice += $pice['TotalPrice'];
                        }
                    }
                    if ($j == 2) {
                        $newprice = $totalpice - 8;
                        $discount = 3;
                    } elseif ($j == 3) {
                        $newprice = $totalpice - 11;
                        $discount = 6;
                    } elseif ($j == 4) {
                        $newprice = $totalpice - 14;
                        $discount = 9;
                    } else {
                        $newprice = $totalpice - 5;
                        $discount = 5;
                    }
                    $new_customer_discount = 5;

                    $data = array(
                        'result' => true,
                        'response' => 'price',
                        'total_price' => $newprice,
                        'bundle_discount' => $discount,
                        'iteration_discount' => 0,
                        'iteration_number' => 0,
                        'new_customer_discount' => $new_customer_discount,
                        'vehicles' => $cardetails
                    );

                    echo json_encode($data);
                    die();
                }
            }
        } else {
            $data = array(
                'Error' => 'Wash Request ID is missing'
            );
            echo json_encode($data);
            die();
        }
    }

    public function actiongetnewwashrequest() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $status = Yii::app()->request->getParam('washer_status');
        $json = array();
        $result = 'false';
        $response = 'Pass the required parameters';
        $is_scheduled_wash_120 = 0;
        $scheduled_wash_120_id = 0;
        $pendingwashcount = 0;

        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }
        $agentdetails = Agents::model()->findByAttributes(array("id" => $agent_id));

        if ($agentdetails->block_washer) {
            $result = 'false';
            $response = 'No wash requests found for you';

            $json = array(
                'result' => $result,
                'response' => $response
            );

            echo json_encode($json);
            die();
        }

        if ((isset($agent_id) && !empty($agent_id))) {
            $agents_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));

            if ($agents_id_check->washer_position == 'real')
                $pendingschedrequests = Washingrequests::model()->findAll(array("condition" => "wash_request_position = 'real' AND agent_id = 0 AND is_scheduled = 1 AND status = 0"));
            else
                $pendingschedrequests = Washingrequests::model()->findAll(array("condition" => "wash_request_position != 'real' AND agent_id = 0 AND is_scheduled = 1 AND status = 0"));
            foreach ($pendingschedrequests as $pdrequest) {
                $cust_id_check = Customers::model()->findByAttributes(array("id" => $pdrequest->customer_id));
                $sched_date = '';
                $sched_time = '';
                if ($pdrequest->reschedule_time) {
                    $sched_date = $pdrequest->reschedule_date;
                    $sched_time = $pdrequest->reschedule_time;
                } else {
                    $sched_date = $pdrequest->schedule_date;
                    $sched_time = $pdrequest->schedule_time;
                }

                $scheduledatetime = $sched_date . " " . $sched_time;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                $min_diff = 0;

                $min_diff = round(($from_time - $to_time) / 60, 2);

                $declinedids = explode(",", $pdrequest->agent_reject_ids);
                $miles = 0;
                $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id' => $agent_id));
                if (count($agent_loc_obj)) {
                    $theta = $pdrequest->longitude - $agent_loc_obj->longitude;
                    $dist = sin(deg2rad($pdrequest->latitude)) * sin(deg2rad($agent_loc_obj->latitude)) + cos(deg2rad($pdrequest->latitude)) * cos(deg2rad($agent_loc_obj->latitude)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                }

                if ($agent_id) {
                    if (!in_array(-$agent_id, $declinedids)) {
//if(($min_diff > 0) && ($agents_id_check->hours_opt_check == $cust_id_check->hours_opt_check)) $pendingwashcount++;
                        if (($min_diff > 0) && ($miles <= 50))
                            $pendingwashcount++;
                    }
                }
            }


            if (count($agents_id_check)) {
                $current_date = date("Y-m-d H:i:s");
                Agents::model()->updateByPk($agent_id, array('last_activity' => $current_date));

                $agenttakenwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent_id, 'is_scheduled' => 1), array('condition' => 'status = 0 OR status = 1 OR status = 2'));

                if (count($agenttakenwashes)) {
                    foreach ($agenttakenwashes as $schedwash) {

                        if ($schedwash->reschedule_time)
                            $scheduledatetime = $schedwash->reschedule_date . " " . $schedwash->reschedule_time;
                        else
                            $scheduledatetime = $schedwash->schedule_date . " " . $schedwash->schedule_time;

                        $to_time = strtotime(date('Y-m-d g:i A'));
                        $from_time = strtotime($scheduledatetime);
                        $min_diff = -1;
                        if ($from_time >= $to_time) {
                            $min_diff = round(($from_time - $to_time) / 60, 2);
                        }

                        if (!$is_scheduled_wash_120) {
                            if ($min_diff <= 120 && $min_diff >= 0) {
                                $is_scheduled_wash_120 = 1;
                                $scheduled_wash_120_id = $schedwash->id;
                                break;
                            }
                        }
                    }
                }
            }

            if (!count($agents_id_check)) {
                $result = 'false';
                $response = 'Invalid agent id';
            } else if ($agents_id_check->available_for_new_order == 0) {
                $result = 'false';
                $response = 'Agent is not available for new order';
            }

            /* else if(count($agent_has_order)){
              $result= 'true';
              $response= 'wash request found';

              if(!$agent_has_order->create_wash_push_sent){


              /*	  $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '9' ")->queryAll();
              $message = $pushmsg[0]['message'];

              $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agents_id_check->id."' ORDER BY last_used DESC LIMIT 1")->queryAll();

              if((count($agentdevices)) && (!$agentdetails->block_washer))
              {


              $device_type = strtolower($agentdevices[0]['device_type']);
              $notify_token = $agentdevices[0]['device_token'];
              $alert_type = "strong";
              $notify_msg = urlencode($message);

              $notifyurl = ROOT_URL."/push-notifications/".$device_type."/?device_token=".$notify_token."&msg=".$notify_msg."&alert_type=".$alert_type;

              $ch = curl_init();
              curl_setopt($ch,CURLOPT_URL,$notifyurl);
              curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

              if($notify_msg) $notifyresult = curl_exec($ch);
              curl_close($ch);

              }*--/



              Washingrequests::model()->updateByPk($agent_has_order->id, array("create_wash_push_sent" => 1));
              }

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

              if(AES256CBC_STATUS == 1){
              $pendingwashrequests[] = array('id'=>$this->aes256cbc_crypt( $agent_has_order->id, 'e', AES256CBC_API_PASS ),
              'customer_id'=>$this->aes256cbc_crypt( $agent_has_order->customer_id, 'e', AES256CBC_API_PASS ),
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

              } */ else {

                $pendingrequests = Washingrequests::model()->findAllByAttributes(array("status" => 0, "is_scheduled" => 0), array('order' => 'order_for ASC'));



                if (count($pendingrequests)) {
                    foreach ($pendingrequests as $prequest) {
                        $result = 'false';
                        $response = 'No wash requests found for you';

                        /* ------- check if order is already taken by other agent or agent reject-------- */

                        $order_rejects = false;
                        $a_array = explode(',', $prequest['agent_reject_ids']);
                        foreach ($a_array as $aid) {
                            //echo $id;
                            if ($aid == '-' . $agent_id) {
                                $order_rejects = true;
                                break;
                            }
                        }

                        /* ------- check if agent rejects order two times -------- */

                        $total_rejects_array = explode(',', $prequest['all_reject_ids']);
                        $num_rejects_per_agents = array_count_values($total_rejects_array);

                        if ($num_rejects_per_agents[$agent_id] >= 0) {
                            $order_rejects = true;
                        }


                        //if($prequest['order_temp_assigned'] == 0 AND (!$order_rejects)){
                        //if($prequest['order_temp_assigned'] == 0){
                        /* ------- check if agent is nearest -------- */

                        $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/isagentnearest");
                        $data = array("customer_id" => $prequest['customer_id'], "wash_request_id" => $prequest['id'], "agent_id" => $agent_id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                        curl_setopt($handle, CURLOPT_POST, true);
                        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                        $nearapiresult = curl_exec($handle);
                        curl_close($handle);
                        $jsondata = json_decode($nearapiresult);
                        //var_dump($jsondata);
                        $nearest_response = $jsondata->response;

                        $nearest_check = $jsondata->result;


                        if ($nearest_check == 'true' && $nearest_response == 'agent is nearest') {

                            $result = 'true';
                            $response = 'wash request found';

                            if (!$prequest['create_wash_push_sent']) {



                                Washingrequests::model()->updateByPk($prequest['id'], array("create_wash_push_sent" => 1));
                            }

                            $cust_feedbacks = Washingfeedbacks::model()->findAllByAttributes(array("customer_id" => $prequest['customer_id']));
                            $total_rate = count($cust_feedbacks);
                            if ($total_rate) {
                                $rate = 0;
                                foreach ($cust_feedbacks as $cust_feedback) {
                                    $rate += $cust_feedback->customer_ratings;
                                }

                                $cust_rate = round($rate / $total_rate);
                            } else {
                                $cust_rate = 0;
                            }

                            $cust_details = Customers::model()->findByAttributes(array("id" => $prequest['customer_id']));


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

                            if (AES256CBC_STATUS == 1) {
                                $pendingwashrequests[] = array('id' => $this->aes256cbc_crypt($prequest['id'], 'e', AES256CBC_API_PASS),
                                    'customer_id' => $this->aes256cbc_crypt($prequest['customer_id'], 'e', AES256CBC_API_PASS),
                                    'customer_name' => $customername,
                                    'customer_email' => $cust_details->email,
                                    'customer_phoneno' => $cust_details->contact_number,
                                    'customer_photo' => $cust_details->image,
                                    'customer_rating' => $cust_details->rating,
                                    'car_list' => $prequest['car_list'],
                                    'package_list' => $prequest['package_list'],
                                    'address' => $prequest['address'],
                                    'address_type' => $prequest['address_type'],
                                    'latitude' => $prequest['latitude'],
                                    'longitude' => $prequest['longitude'],
                                    'payment_type' => $prequest['payment_type'],
                                    'nonce' => $prequest['nonce'],
                                    'estimate_time' => $prequest['estimate_time'],
                                    'status' => $prequest['status']
                                );
                            } else {
                                $pendingwashrequests[] = array('id' => $prequest['id'],
                                    'customer_id' => $prequest['customer_id'],
                                    'customer_name' => $customername,
                                    'customer_email' => $cust_details->email,
                                    'customer_phoneno' => $cust_details->contact_number,
                                    'customer_photo' => $cust_details->image,
                                    'customer_rating' => $cust_details->rating,
                                    'car_list' => $prequest['car_list'],
                                    'package_list' => $prequest['package_list'],
                                    'address' => $prequest['address'],
                                    'address_type' => $prequest['address_type'],
                                    'latitude' => $prequest['latitude'],
                                    'longitude' => $prequest['longitude'],
                                    'payment_type' => $prequest['payment_type'],
                                    'nonce' => $prequest['nonce'],
                                    'estimate_time' => $prequest['estimate_time'],
                                    'status' => $prequest['status']
                                );
                            }



                            break;
                        }

                        //}
                    }
                } else {
                    $result = 'false';
                    $response = 'No pending requests found';
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'new_request_details' => $pendingwashrequests,
            'washer_status' => $agentdetails['status'],
            'total_pending_schedule_washes' => $pendingwashcount,
            'is_scheduled_wash_120' => $is_scheduled_wash_120,
            'scheduled_wash_120_id' => $scheduled_wash_120_id
        );

        echo json_encode($json);
        die();
    }

    public function actionunstuckorder() {

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

        $washmodel = new Washingrequests;
        $washmodel->updateAll(array('agent_id' => 0), 'status=:status', array(':status' => 0));
        echo "done";
    }

    /* view particular customer request */

    public function actionwashingkart() {

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');

        $kartapiresult = $this->washingkart(Yii::app()->request->getParam('wash_request_id'), Yii::app()->request->getParam('key'), Yii::app()->request->getParam('coupon_discount'), Yii::app()->request->getParam('api_password'), $api_token, $t1, $t2, $user_type, $user_id, Yii::app()->request->getParam('show_payment_method'));
        echo $kartapiresult;
        die();
    }

    public function actionvieworder() {

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

        /* Checking for post(day) parameters */
        $order_day = '';
        $customer_id = Yii::app()->request->getParam('customer_id');
        if (!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))) {
            $day = Yii::app()->request->getParam('day');
            $event = Yii::app()->request->getParam('event');

            $status_qr = '';
            if ($event == 'pending') {
                $status = 0;
                $status_qr = ' AND a.status="' . $status . '"';
            } elseif ($event == 'completed') {
                $status = 4;
                $status_qr = ' AND a.status="' . $status . '"';
            } elseif ($event == 'processing') {
                $status = 2;
                $status_qr = ' AND a.status="' . $status . '"';
            } else {
                $status_qr = '';
            }

            $order_day = " AND DATE_FORMAT(a.created_date,'%Y-%m-%d')= :day" . $status_qr;
        }
        /* END */
        $total_order = Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];


        if ($customer_id) {
            $customers_order = Yii::app()->db->createCommand("SELECT a.id, a.status, a.washer_payment_status, a.total_price, a.net_price, a.address_type, a.bundle_discount, a.fifth_wash_discount, a.first_wash_discount, a.address, a.coupon_discount, a.customer_id, a.agent_id, a.created_date, a.car_list, a.package_list, a.estimate_time, a.wash_request_position, b.customername, c.first_name, c.last_name, c.street_address, c.city, c.state FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.customer_id = :customer_id AND a.status NOT IN (5,6)$order_day ORDER BY a.id DESC")
                    ->bindValue(':day', $day, PDO::PARAM_STR)
                    ->bindValue(':customer_id', $customer_id, PDO::PARAM_STR)
                    ->queryAll();
        } else {
            $customers_order = Yii::app()->db->createCommand("SELECT a.id, a.status, a.washer_payment_status, a.total_price, a.net_price, a.address_type, a.bundle_discount, a.fifth_wash_discount, a.first_wash_discount, a.address, a.coupon_discount, a.customer_id, a.agent_id, a.created_date, a.car_list, a.package_list, a.estimate_time, a.wash_request_position, b.customername, c.first_name, c.last_name, c.street_address, c.city, c.state FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.status NOT IN (5,6)$order_day ORDER BY a.id DESC")->queryAll();
        }



        $package_name = array();
        $car_id = array();
        foreach ($customers_order as $orderbycustomer) {

            $subtotal = $orderbycustomer['net_price'];
            $discount = $orderbycustomer['bundle_discount'] + $orderbycustomer['fifth_wash_discount'] + $orderbycustomer['first_wash_discount'] + $orderbycustomer['coupon_discount'];


            //$counttype = array_count_values($package_list_explode);
            $orderstatus = $orderbycustomer['status'];
            $orderid = $orderbycustomer['id'];
            $client = $orderbycustomer['customername'];
            $created_date = $orderbycustomer['created_date'];
            $name_of_agent = $orderbycustomer['first_name'] . '&nbsp;' . $orderbycustomer['last_name'];
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
            if ($orderstatus == 4) {
                $order_of_status = 'Complete';
                $totalminutes = 'N/A';
                $near_agent = '';
            } elseif ($orderstatus == 0) {
                $order_of_status = 'Pending';
                $time = $orderbycustomer['created_date'];
                $currentdate = date("Y-m-d h:i:s");
                $to_time = strtotime($time);
                $from_time = strtotime($currentdate);
                $totalminutes = round(abs($to_time - $from_time) / 60, 2);
            } elseif ($orderstatus >= 1 && $orderstatus <= 3) {
                $order_of_status = 'Processing';
                $totalminutes = 'N/A';
                $near_agent = '';
            }
            $key = 'order_' . $count . '_' . $orderid;
            $json = array();
            $json['status'] = $order_of_status;
            $json['washer_payment_status'] = $orderbycustomer['washer_payment_status'];
            $json['countnear'] = $countnear;
            $json['orderid'] = $orderid;
            $json['time'] = $totalminutes;
            $json['created_date'] = $created_date;
            $json['client'] = $client;
            $json['agent'] = $name_of_agent;
            $json['address'] = $address;
            $json['address_type'] = $address_type;
            $json['city'] = $city;
            $json['state'] = $state;
            $json['DP'] = $dp;
            $json['total_price'] = $total_price;
            $json['net_price'] = $net_price;
            $json['discount'] = $discount;
            $json['duration'] = $duration;
            $json['wash_request_position'] = $wash_request_position;
            $orderview[] = $json;
        }
        $ordersdetails['order'] = $orderview;
        $ordersdetails['total_records'] = $count;

        echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

        exit;
    }

    public function actionEditOrderDetailAdmin() {

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

        $orderid = Yii::app()->request->getParam('orderid');
        $status = Yii::app()->request->getParam('status');
        $clientname = Yii::app()->request->getParam('clientname');
        $agentname = Yii::app()->request->getParam('agentname');
        if (!empty($status)) {
            $update_status = Yii::app()->db->createCommand("UPDATE washing_requests SET status=:status WHERE id = :id")->bindValue(':status', $status, PDO::PARAM_STR)->bindValue(':id', $orderid, PDO::PARAM_STR)->queryAll();
            $value = $status;
        } elseif (!empty($clientname)) {

            $customer = Yii::app()->db->createCommand("SELECT customer_id FROM washing_requests WHERE id = :id")->bindValue(':id', $orderid, PDO::PARAM_STR)->queryAll();
            $customerid = $customer[0]['customer_id'];
            $update_customer = Yii::app()->db->createCommand("UPDATE customers SET customername=:customername WHERE id = :id")->bindValue(':customername', $clientname, PDO::PARAM_STR)->bindValue(':id', $customerid, PDO::PARAM_STR)->queryAll();
            $value = $firstname;
        }
        $result = 'true';
        $response = $value . ' updated successfully';
        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actionadminpromotionscode() {

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

        $sort = Yii::app()->request->getParam('orderby');
        $sortorder = explode("_", $sort);
        $table = $sortorder[0];
        if ($table == 'promocode') {
            $set = 'promo_code';
        }


        $customer_discounts = Yii::app()->db->createCommand("SELECT * FROM customer_discounts GROUP BY promo_code ORDER BY " . ($set) . " " . ($des) . " ")->queryAll();
        $promotiondetails = array();
        foreach ($customer_discounts as $discount) {
            $promo_code = $discount['promo_code'];
            $count = Yii::app()->db->createCommand("SELECT COUNT(id) as count FROM customer_discounts WHERE promo_code = '$promo_code' GROUP BY promo_code")->queryAll();
            $uses = $count[0]['count'];
            $key = 'promotion_' . $promo_code;
            $json = array();
            $json['promo_code'] = $promo_code;
            $json['count'] = $uses;
            $json['status'] = 'N/A';
            $json['expires'] = 'N/A';
            $promotiondetails[] = $json;
        }
        $promotioncode['promotions'] = $promotiondetails;
        echo json_encode($promotioncode, JSON_PRETTY_PRINT);

        exit;
    }

    public function actionsendorderreceipts($wash_request_id = 0, $customer_id = 0, $agent_id = 0, $return_val = 'false', $key = '', $api_token = '', $t1 = '', $t2 = '', $user_type = '', $user_id = '') {

        if ((Yii::app()->request->getParam('key') != API_KEY) && ($key != API_KEY)) {
            echo "Invalid api key";
//die();
        }

        if (!$api_token)
            $api_token = Yii::app()->request->getParam('api_token');
        if (!$t1)
            $t1 = Yii::app()->request->getParam('t1');
        if (!$t2)
            $t2 = Yii::app()->request->getParam('t2');
        if (!$user_type)
            $user_type = Yii::app()->request->getParam('user_type');
        if (!$user_id)
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
        if (!$wash_request_id)
            $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if (!$customer_id)
            $customer_id = Yii::app()->request->getParam('customer_id');
        if (!$agent_id)
            $agent_id = Yii::app()->request->getParam('agent_id');
        $result = 'false';
        $response = 'Pass the required parameters';

        if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($customer_id) && !empty($customer_id))) {

            if ((AES256CBC_STATUS == 1) && (Yii::app()->request->getParam('wash_request_id'))) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            if ((AES256CBC_STATUS == 1) && (Yii::app()->request->getParam('customer_id'))) {
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }

            if ((AES256CBC_STATUS == 1) && (Yii::app()->request->getParam('agent_id'))) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
            }

            $wash_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));
            $customer_id_check = Customers::model()->findByAttributes(array("id" => $customer_id));
            if ($agent_id)
                $agent_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));

            if (!count($wash_id_check)) {
                $result = 'false';
                $response = 'Invalid wash id';
            } else if (!count($customer_id_check)) {
                $result = 'false';
                $response = 'Invalid customer id';
            }
            /* else if(!count($agent_id_check)){
              $result= 'false';
              $response= 'Invalid agent id';
              } */ else {
                $result = 'true';
                $response = 'order receipts sent';

                /* ------- kart details ----------- */

                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);

                /* ------- kart details end ----------- */

                $from = Vargas::Obj()->getAdminFromEmail();
                $subject = 'Order Receipt - #000' . $wash_id_check->id;
                //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                $message = "<div class='block-content' style='background: #fff; text-align: left;'>";
                $message .= "<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Thank you for choosing MobileWash</h2>
					<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Your order is placed at " . $wash_id_check->address . "</p>";

                $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>" . $customer_id_check->first_name . " " . $customer_id_check->last_name . "</strong></td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $wash_id_check->id . "</td></tr>
					</table>";

                if ($kartdata->status == 5) {
                    $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$" . number_format($kartdata->cancel_fee, 2) . "</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->cancel_fee, 2) . "</span></p></td>
</tr>
</table>";
                } else {
                    $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
                    foreach ($kartdata->vehicles as $ind => $vehicle) {
                        $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                        if ($vehicle->surge_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->extclaybar_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->waterspotremove_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->upholstery_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->exthandwax_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->pet_hair_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->lifted_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee . "</p></td>
</tr>";
                        }

                        $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->safe_handling_fee . "</p></td>
</tr>";

                        if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (" . $wash_id_check->coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format($kartdata->coupon_discount, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->fifth_wash_discount > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . $vehicle->fifth_wash_discount . "</p></td>
</tr>";
                        }

                        if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                        }

                        if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                        }



                        $message .= "</table>

</td>
</tr>";
                    }
                    $message .= "</table>";


                    if ($kartdata->wash_now_fee > 0) {
                        $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->wash_now_fee, 2) . "</p>
</td>
</tr>";

                        $message .= "</table>";
                    }

                    if ($kartdata->wash_later_fee > 0) {
                        $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Surge Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->wash_later_fee, 2) . "</p>
</td>
</tr>";

                        $message .= "</table>";
                    }

                    if ($kartdata->tip_amount > 0) {
                        $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->tip_amount, 2) . "</p>
</td>
</tr>";

                        $message .= "</table>";
                    }

                    $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $kartdata->net_price . "</span></p></td>
</tr>
</table>";
                }

                /* ------ agent msg ------ */

                $message_agent = "<div class='block-content' style='background: #fff; text-align: left;'>

                  <p style='text-align: center; font-family: arial; font-size: 20px; line-height: normal; margin: 0;'><strong>Order Number:</strong> #000" . $wash_id_check->id . "</p>";

                if ($kartdata->status == 5) {
                    $message_agent .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$" . number_format($kartdata->washer_cancel_fee, 2) . "</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->washer_cancel_fee, 2) . "</span></p></td>
</tr>
</table>";
                } else {
                    $message_agent .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";
                    foreach ($kartdata->vehicles as $ind => $vehicle) {
                        $message_agent .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price_agent . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";

                        if ($vehicle->surge_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->extclaybar_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee_agent . "</p></td>
</tr>";
                        }
                        if ($vehicle->waterspotremove_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->upholstery_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->exthandwax_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->pet_hair_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->lifted_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->extplasticdressing_vehicle_fee_agent > 0) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if (count($kartdata->vehicles) > 1) {
                            $message_agent .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format(round(1 * .80, 2), 2) . "</p></td>
</tr>";
                        }

                        $message_agent .= "</table>

</td>
</tr>";
                    }
                    $message_agent .= "</table>";

                    if ($kartdata->transaction_fee > 0) {
                        $message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message_agent .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Transaction Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$" . number_format($kartdata->transaction_fee, 2) . "</p>
</td>
</tr>";

                        $message_agent .= "</table>";
                    }

                    if ($kartdata->wash_now_fee > 0) {
                        $message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message_agent .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_now_fee * .75, 2), 2) . "</p>
</td>
</tr>";

                        $message_agent .= "</table>";
                    }

                    if ($kartdata->wash_later_fee > 0) {
                        $message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message_agent .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Surge</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_later_fee * .75, 2), 2) . "</p>
</td>
</tr>";

                        $message_agent .= "</table>";
                    }

                    if ($kartdata->tip_amount > 0) {
                        $message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $message_agent .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->tip_amount, 2) . "</p>
</td>
</tr>";

                        $message_agent .= "</table>";
                    }


                    $message_agent .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->agent_total, 2) . "</span></p></td>
</tr>
</table>";
                }


                /* ---- company msg -------- */

                $com_message = "<div class='block-content' style='background: #fff; text-align: left;'>

                  <p style='text-align: center; font-family: arial; font-size: 20px; line-height: normal; margin: 0;'><strong>Order Number:</strong> #000" . $wash_id_check->id . "</p>";

                if ($kartdata->status == 5) {
                    $company_cancel_fee = $kartdata->cancel_fee - $kartdata->washer_cancel_fee;
                    $com_message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$" . number_format($company_cancel_fee, 2) . "</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$" . number_format($company_cancel_fee, 2) . "</span></p></td>
</tr>
</table>";
                } else {
                    $com_message .= "<p style='margin: 0; margin-top: 15px; font-size: 18px; border-top: 1px solid #000; padding: 10px 0;'><strong>MobileWash Receipt</strong></p>";

                    $com_message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 0;'>";
                    foreach ($kartdata->vehicles as $ind => $vehicle) {
                        $com_message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>";
                        if ($vehicle->vehicle_washing_package == 'Premium')
                            $com_message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . number_format($vehicle->vehicle_washing_price * .25, 2) . "</p>";
                        else
                            $com_message .= "<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . number_format($vehicle->vehicle_washing_price * .20, 2) . "</p>";

                        $com_message .= "</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";

                        if ($vehicle->surge_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->surge_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->extclaybar_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->extclaybar_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->waterspotremove_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->waterspotremove_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->upholstery_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->upholstery_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->exthandwax_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->exthandwax_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->floormat_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->pet_hair_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->pet_hair_fee * .20, 2) . "</p></td>
</tr>";
                        }
                        if ($vehicle->lifted_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->lifted_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($vehicle->extplasticdressing_vehicle_fee * .20, 2) . "</p></td>
</tr>";
                        }

                        $com_message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$1.00</p></td>
</tr>";

                        if ($vehicle->fifth_wash_discount > 0) {
                            if ((count($kartdata->vehicles) > 1)) {
                                $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format($vehicle->fifth_wash_discount - .80, 2) . "</p></td>
</tr>";
                            } else {
                                $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format($vehicle->fifth_wash_discount, 2) . "</p></td>
</tr>";
                            }
                        }

                        if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$0.20</p></td>
</tr>";
                        }

                        if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$0.20</p></td>
</tr>";
                        }


                        $com_message .= "</table>

</td>
</tr>";
                    }
                    $com_message .= "</table>";
                    if ($kartdata->coupon_discount > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";
                        if ((count($kartdata->vehicles) > 1)) {
                            $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Promo Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$" . number_format($kartdata->coupon_discount - .80, 2) . "</p>
</td>
</tr>";
                        } else {
                            $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Promo Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$" . number_format($kartdata->coupon_discount, 2) . "</p>
</td>
</tr>";
                        }
                        $com_message .= "</table>";
                    }


                    if ($kartdata->transaction_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Transaction Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->transaction_fee, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->wash_now_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_now_fee * .25, 2), 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->wash_later_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Surge Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_later_fee * .25, 2), 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    /* if($kartdata->tip_amount > 0){
                      $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                      $com_message .= "<tr>
                      <td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
                      <td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
                      <p style='font-size: 18px; margin: 0;'>+$".number_format(round($kartdata->tip_amount*.20, 2), 2)."</p>
                      </td>
                      </tr>";

                      $com_message .= "</table>";
                      } */

                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $kartdata->company_total . "</span></p></td>
</tr>
</table>";
                }

                $cust_details = Customers::model()->findByAttributes(array("id" => $customer_id_check->id));


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

                $com_message .= "<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Client Receipt:</strong> " . $customername . "</p>";

                if ($kartdata->status == 5) {
                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$" . number_format($kartdata->cancel_fee, 2) . "</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->cancel_fee, 2) . "</span></p></td>
</tr>
</table>";
                } else {
                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
                    foreach ($kartdata->vehicles as $ind => $vehicle) {
                        $com_message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";

                        if ($vehicle->surge_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->extclaybar_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->waterspotremove_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->upholstery_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->exthandwax_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->pet_hair_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee . "</p></td>
</tr>";
                        }
                        if ($vehicle->lifted_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee . "</p></td>
</tr>";
                        }

                        if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee . "</p></td>
</tr>";
                        }

                        $com_message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->safe_handling_fee . "</p></td>
</tr>";

                        if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (" . $wash_id_check->coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . $kartdata->coupon_discount . "</p></td>
</tr>";
                        }


                        if ($vehicle->fifth_wash_discount > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . $vehicle->fifth_wash_discount . "</p></td>
</tr>";
                        }

                        if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                        }

                        if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$1.00</p></td>
</tr>";
                        }

                        $com_message .= "</table>

</td>
</tr>";
                    }
                    $com_message .= "</table>";


                    if ($kartdata->wash_now_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->wash_now_fee, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->wash_later_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Surge Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->wash_later_fee, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->tip_amount > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->tip_amount, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . $kartdata->net_price . "</span></p></td>
</tr>
</table>";
                }

                $wash_feedbacks = Washingfeedbacks::model()->findByAttributes(array("wash_request_id" => $wash_request_id));

                if (!$agent_id)
                    $agent_details = Agents::model()->findByAttributes(array("id" => $agent_id_check->id));

                $agentlname = '';
                if (trim($agent_details->last_name))
                    $agentlname = strtoupper(substr($agent_details->last_name, 0, 1)) . ".";
                else
                    $agentlname = $agent_details->last_name;

                $com_message .= "<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Agent Receipt:</strong> " . $agent_details->first_name . " " . $agentlname . "</p>";

                if ($kartdata->status == 5) {
                    $com_message .= "<table style='width: 100%; border-collapse: margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$" . number_format($kartdata->washer_cancel_fee, 2) . "</p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->washer_cancel_fee, 2) . "</span></p></td>
</tr>
</table>";
                } else {
                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
                    foreach ($kartdata->vehicles as $ind => $vehicle) {
                        $com_message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$" . $vehicle->vehicle_washing_price_agent . "</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";

                        if ($vehicle->surge_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->surge_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->extclaybar_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extclaybar_vehicle_fee_agent . "</p></td>
</tr>";
                        }
                        if ($vehicle->waterspotremove_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->waterspotremove_vehicle_fee_agent . "</p></td>
</tr>";
                        }
                        if ($vehicle->upholstery_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->upholstery_vehicle_fee_agent . "</p></td>
</tr>";
                        }
                        if ($vehicle->exthandwax_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->exthandwax_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->floormat_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->pet_hair_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->pet_hair_fee_agent . "</p></td>
</tr>";
                        }
                        if ($vehicle->lifted_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->lifted_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($vehicle->extplasticdressing_vehicle_fee_agent > 0) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . $vehicle->extplasticdressing_vehicle_fee_agent . "</p></td>
</tr>";
                        }

                        if ($wash_later_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$" . number_format($wash_later_fee, 2) . "</p></td>
</tr>";
                        }

                        if (count($kartdata->vehicles) > 1) {
                            $com_message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$" . number_format(round(1 * .80, 2), 2) . "</p></td>
</tr>";
                        }


                        $com_message .= "</table>

</td>
</tr>";
                    }
                    $com_message .= "</table>";

                    if ($kartdata->transaction_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Transaction Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->transaction_fee, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->wash_now_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_now_fee * .75, 2), 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->wash_later_fee > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Surge Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format(round($kartdata->wash_later_fee * .75, 2), 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }

                    if ($kartdata->tip_amount > 0) {
                        $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>";

                        $com_message .= "<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$" . number_format($kartdata->tip_amount, 2) . "</p>
</td>
</tr>";

                        $com_message .= "</table>";
                    }


                    $com_message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . number_format($kartdata->agent_total, 2) . "</span></p></td>
</tr>
</table>";
                }

                $to = Vargas::Obj()->getAdminToEmail();
                $from = Vargas::Obj()->getAdminFromEmail();

                Vargas::Obj()->SendMail($customer_id_check->email, $from, $message, $subject, 'mail-receipt');
                if ($agent_id)
                    Vargas::Obj()->SendMail($agent_id_check->email, $from, $message_agent, $subject, 'mail-receipt');
                Vargas::Obj()->SendMail($to, $from, $com_message, $subject, 'mail-receipt');
                /* if($is_scheduled == 1){
                  Vargas::Obj()->SendMail($customers_id_check->email,"billing@Mobilewash.com",$message,$subject, 'mail-receipt');
                  }else{
                  $receiptresult = $this->actionsendorderreceipts($wash_request_id, $customer_id, 0, 'true', API_KEY);
                  } */
                //Vargas::Obj()->SendMail($to,$from,$com_message,$subject, 'mail-receipt');
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        if ($return_val == 'true') {
            return json_encode($json);
        } else {
            echo json_encode($json);
            die();
        }
    }

    public function ActionWashingFeedbacks() {

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

        /*        get the total */
        $total_feed = Yii::app()->db->createCommand("SELECT COUNT(w.id) as countid FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id")->queryAll();
        $feeds = $total_feed[0]['countid'];
        $feedback = Yii::app()->db->createCommand("SELECT w.wash_request_id as id, wr.address, c.contact_number, wr.complete_order, a.real_washer_id, w.customer_comments, w.customer_ratings, w.agent_comments, w.agent_ratings, c.customername, a.first_name, a.last_name FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id LEFT JOIN washing_requests wr ON w.wash_request_id = wr.id")->queryAll();
        /* echo "SELECT w.id, w.customer_comments, w.customer_ratings, w.agent_comments, w.agent_ratings, c.customername, a.first_name, a.last_name FROM washing_feedbacks w LEFT JOIN customers c ON w.customer_id = c.id LEFT JOIN agents a ON w.agent_id = a.id ORDER BY ". ($set) ." ". ($des) ." LIMIT ". ($startpage)." ,  ". ($endpage). " "; */
        $i = 0;
        foreach ($feedback as $feedbacks) {
            $i++;
            $id = $feedbacks['id'];
            $customer_comments = $feedbacks['customer_comments'];
            $customer_ratings = $feedbacks['customer_ratings'];
            $agent_comments = $feedbacks['agent_comments'];
            $agent_ratings = $feedbacks['agent_ratings'];
            $customername = $feedbacks['customername'];
            $agentname = $feedbacks['first_name'] . '&nbsp;' . $feedbacks['last_name'];
            $agentfname = $feedbacks['first_name'];
            $agentlname = $feedbacks['last_name'];
            $address = $feedbacks['address'];
            $real_wash_id = $feedbacks['real_washer_id'];
            $complete_order = $feedbacks['complete_order'];
            $contact_number = $feedbacks['contact_number'];

            $key = 'feed_' . $id;
            $json = array();
            $json['washingid'] = $id;
            $json['customer_comments'] = $customer_comments;
            $json['customer_ratings'] = $customer_ratings;
            $json['agent_comments'] = $agent_comments;
            $json['agent_ratings'] = $agent_ratings;
            $json['customername'] = $customername;
            $json['agentname'] = $agentname;
            $json['agentfname'] = $agentfname;
            $json['agentlname'] = $agentlname;
            $json['address'] = $address;
            $json['real_washer_id'] = $real_washer_id;
            $json['complete_order'] = $complete_order;
            $json['phone'] = $contact_number;
            $feedview[] = $json;
        }

        $feedbackadmin['order'] = $feedview;
        $feedbackadmin['total_records'] = $feeds;

        echo json_encode($feedbackadmin, JSON_PRETTY_PRINT);
        exit;
    }

    public function ActionEditOrder() {

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

        $orderID = Yii::app()->request->getParam('orderID');
        $orderdetail = Washingrequests::model()->findByAttributes(array("id" => $orderID));
        $near_agents = array();
        $orderid = $orderdetail['id'];
        $customer_id = $orderdetail['customer_id'];
        $address = $orderdetail['address'];
        $status = $orderdetail['status'];
        $customer = Yii::app()->db->createCommand("SELECT * FROM customers WHERE id = " . $orderdetail['customer_id'])->queryAll();
        $customername = $customer[0]['customername'];
        $agent_detail = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id = " . $orderdetail['agent_id'])->queryAll();
        $agentname = $agent_detail[0]['first_name'] . " " . $agent_detail[0]['last_name'];
        $agentid = $agent_detail[0]['id'];
        $agentcompanyid = $agent_detail[0]['real_washer_id'];
        $inspectiondetails_arr = array();

        $inspectiondetails = Washinginspections::model()->findAllByAttributes(array("wash_request_id" => $orderID));

        if (count($inspectiondetails)) {
            foreach ($inspectiondetails as $ind => $inspectdet) {

                $vehicle_detail = Vehicle::model()->findByAttributes(array("id" => $inspectdet->vehicle_id));
                $inspectiondetails_arr[$ind]['vehicle_id'] = $inspectdet->vehicle_id;
                $inspectiondetails_arr[$ind]['vehicle_make'] = $vehicle_detail->brand_name;
                $inspectiondetails_arr[$ind]['vehicle_model'] = $vehicle_detail->model_name;
                $inspectiondetails_arr[$ind]['inspect_img'] = $inspectdet->damage_pic;
            }
        }

        if ($status == 0) {
            $order_of_status = 'Pending';



            // to find nearest agent

            $customer_id = $customer_id;
            $wash_request_id = $orderid;


            /* ------- get nearest agents --------- */

            $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
            $data = array('wash_request_id' => $wash_request_id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($handle);
            curl_close($handle);
            $nearagentsdetails = json_decode($output);

            /* ------- get nearest agents end --------- */

            if ($nearagentsdetails->result == 'true') {
                foreach ($nearagentsdetails->nearest_agents as $aid => $dist) {
                    $agent_details = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $aid . "' ")->queryAll();
                    $near_agents[$aid] = $agent_details[0]['first_name'] . " " . $agent_details[0]['last_name'] . " (" . round($dist, 4) . " miles)";
                }
            }
        } elseif ($status >= 1 && $status <= 3) {
            $order_of_status = 'Processing';
            $near_agent = '';
        } elseif ($status == 5 || $status == 6) {
            $order_of_status = 'Cancel';
            $near_agent = '';
        } else {
            $order_of_status = 'Complete';
        }

        $json = array(
            'id' => $orderid,
            'customername' => $customername,
            'agent_name' => $agentname,
            'agent_id' => $agentid,
            'agent_company_id' => $agentcompanyid,
            'address' => $address,
            'order_of_status' => $order_of_status,
            'washer_payment_status' => $orderdetail['washer_payment_status'],
            'inspection_details' => $inspectiondetails_arr,
            'nearagent' => $near_agents
        );
        echo json_encode($json);
        exit;
    }

    public function ActionPendigTimer() {

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
        $orderid = Yii::app()->request->getParam('orderid');
        $order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE id in (:id) ")->bindValue(':id', $orderid, PDO::PARAM_STR)->queryAll();

        $pending = array();
        $processing = array();
        foreach ($order as $k => $y) {
            //$id = $y['id'];
            if ($y['status'] == 0) {

                $id = $y['id'];
                $time = $y['created_date'];
                $currentdate = date("Y-m-d h:i:s");
                $to_time = strtotime($time);
                $from_time = strtotime($currentdate);
                $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                $time = (int) $totalminutes;
                $h = floor(($time - $d * 1440) / 60);
                $m = $time - ($d * 1440) - ($h * 60);

                $newtime = $h . 'h' . $m . 'm';

                $res[$id]['status'] = 'pending';
                $res[$id]['orderid'] = $id;
                $res[$id]['time'] = $newtime;
                $res[$id]['minutetime'] = $time;
                $res[$id]['name'] = 'N/A';
                $res[$id]['address'] = 'N/A';
                $res[$id]['city'] = 'N/A';
                $res[$id]['state'] = 'N/A';
            } elseif ($y['status'] >= 1 && $y['status'] <= 3) {
                $idd = $y['id'];

                $agentid = $y['agent_id'];
                $agent = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id = '$agentid' ")->queryAll();
                $var['name'] = $agent[0]['first_name'] . '&nbsp;' . $agent[0]['last_name'];
                $var['address'] = $agent[0]['street_address'];
                $var['city'] = $agent[0]['city'];
                $var['state'] = $agent[0]['state'];
                $processing[$idd] = $var;
                $res[$idd]['status'] = 'processing';
                $res[$idd]['orderid'] = $idd;
                $res[$idd]['time'] = 'N/A';
                $res[$idd]['name'] = $agent[0]['first_name'] . '&nbsp;' . $agent[0]['last_name'];
                $res[$idd]['address'] = $agent[0]['street_address'];
                $res[$idd]['city'] = $agent[0]['city'];
                $res[$idd]['state'] = $agent[0]['state'];
                //$processing[$idd] = $processing;
            }
        }

        $json = array($res);
        echo json_encode($json);
        die();
    }

    public function ActionUpdateOrder() {

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
        $wash_check = Washingrequests::model()->findByPk($id);
        $token = '';
        if ($wash_check->agent_id != $agent_id) {
            $cust_check = Customers::model()->findByPk($wash_check->customer_id);
            $agent_check = Agents::model()->findByPk($agent_id);

            if (!$cust_check->braintree_id) {

                $json = array(
                    'result' => 'false',
                    'response' => 'Customer braintree id not found'
                );

                echo json_encode($json);
                die();
            }

            if (!count($agent_check)) {
                $json = array(
                    'result' => 'false',
                    'response' => 'Washer not found'
                );

                echo json_encode($json);
                die();
            }

            if (!$agent_check->bt_submerchant_id) {

                $json = array(
                    'result' => 'false',
                    'response' => 'Washer submerchant id not found'
                );

                echo json_encode($json);
                die();
            }

            if ($cust_check->client_position == 'real')
                $Bresult = Yii::app()->braintree->getCustomerById_real($cust_check->braintree_id);
            else
                $Bresult = Yii::app()->braintree->getCustomerById($cust_check->braintree_id);

            if (count($Bresult->paymentMethods)) {
                foreach ($Bresult->paymentMethods as $index => $paymethod) {
                    $payment_methods[$index]['title'] = get_class($paymethod);
                    if ($payment_methods[$index]['title'] == 'Braintree\\CreditCard') {
                        if ($paymethod->isDefault()) {
                            $token = $paymethod->token;
                            break;
                        }
                    }
                }
            } else {
                $json = array(
                    'result' => 'false',
                    'response' => 'No customer payment methods found'
                );

                echo json_encode($json);
                die();
            }

            if (!$token) {
                $json = array(
                    'result' => 'false',
                    'response' => 'No customer payment methods found'
                );

                echo json_encode($json);
                die();
            }

            if ($wash_check->transaction_id) {
                if ($cust_check->client_position == 'real')
                    $voidresult = Yii::app()->braintree->void_real($wash_check->transaction_id);
                else
                    $voidresult = Yii::app()->braintree->void($wash_check->transaction_id);

                if ($voidresult['success'] == 1) {

                    $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/washingkart");
                    $data = array('wash_request_id' => $wash_check->id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $kartresult = curl_exec($handle);
                    curl_close($handle);
                    $kartdetails = json_decode($kartresult);
                    $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price, 'paymentMethodToken' => $token];
                    if ($cust_check->client_position == 'real')
                        $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                    else
                        $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);

                    if ($payresult['success'] == 1) {
                        Washingrequests::model()->updateByPk($wash_check->id, array('transaction_id' => $payresult['transaction_id'], 'washer_payment_status' => 0));
                    } else {
                        $json = array(
                            'result' => 'false',
                            'response' => $payresult['message']
                        );

                        echo json_encode($json);
                        die();
                    }
                } else {
                    $json = array(
                        'result' => 'false',
                        'response' => $voidresult['message']
                    );

                    echo json_encode($json);
                    die();
                }
            } else {

                $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/washingkart");
                $data = array('wash_request_id' => $wash_check->id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                $kartresult = curl_exec($handle);
                curl_close($handle);
                $kartdetails = json_decode($kartresult);
                $request_data = ['merchantAccountId' => $agent_check->bt_submerchant_id, 'serviceFeeAmount' => $kartdetails->company_total, 'amount' => $kartdetails->net_price, 'paymentMethodToken' => $token];
                if ($cust_check->client_position == 'real')
                    $payresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                else
                    $payresult = Yii::app()->braintree->transactToSubMerchant($request_data);

                if ($payresult['success'] == 1) {
                    Washingrequests::model()->updateByPk($wash_check->id, array('transaction_id' => $payresult['transaction_id'], 'washer_payment_status' => 0));
                } else {
                    $json = array(
                        'result' => 'false',
                        'response' => $payresult['message']
                    );

                    echo json_encode($json);
                    die();
                }
            }
        }
        $data = array('id' => $id, 'address' => $address, 'status' => $status, 'agent_id' => $agent_id);
        $image = array('damage_pic1' => $inspection_image1, 'damage_pic2' => $inspection_image2, 'damage_pic3' => $inspection_image3, 'damage_pic4' => $inspection_image4, 'damage_pic5' => $inspection_image5);
        $data = array_filter($data);
        $image = array_filter($image);
        $update_orders = Washingrequests::model()->updateAll($data, 'id=:id', array(':id' => $id));

        foreach ($image as $image) {

            $washinginspectmodel = new Washinginspections;
            $washinginspectmodel->wash_request_id = $id;
            $washinginspectmodel->vehicle_id = $carlist;
            $washinginspectmodel->damage_pic = $image;
            $washinginspectmodel->save(false);
        }
        $result = 'true';
        $response = 'updated successfully';
        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actioncancelwashrequest() {

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

        /* if (!$token_check) {
          $json = array(
          'result' => 'false',
          'response' => 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');
        $check_wash_status_before_cancel = 0;
        if (Yii::app()->request->getParam('check_wash_status_before_cancel'))
            $check_wash_status_before_cancel = Yii::app()->request->getParam('check_wash_status_before_cancel');
        $wash_now_canceled = 0;
        $action_log = 0;
        if (Yii::app()->request->getParam('wash_now_canceled'))
            $wash_now_canceled = Yii::app()->request->getParam('wash_now_canceled');
        $api_password = '';
        if (Yii::app()->request->getParam('api_password'))
            $api_password = Yii::app()->request->getParam('api_password');
        $result = 'false';
        $response = 'Pass the required parameters';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $ondemand_cancel_for_schedule = '';
        if (Yii::app()->request->getParam('ondemand_cancel_for_schedule'))
            $ondemand_cancel_for_schedule = Yii::app()->request->getParam('ondemand_cancel_for_schedule');
        $json = array();
        if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));

            if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else if (($status != 5) && ($status != 6)) {
                $result = 'false';
                $response = 'Invalid status code';
            } else if (($check_wash_status_before_cancel == 1) && ($wrequest_id_check->status == 1)) {
                $result = 'false';
                $response = 'wash already started';
            } else if (($wrequest_id_check->status == 1) && ($status == 5)) {
                $to_time = strtotime("now");
                $from_time = strtotime($wrequest_id_check->wash_begin);
                $mins = round(abs($to_time - $from_time) / 60, 2);

                if ($mins >= 0) {
                    $result = 'false';
                    $response = 'you cannot cancel wash until paying $10';
                } else {
                    $car_ids = $wrequest_id_check->car_list;
                    $car_ids_arr = explode(",", $car_ids);
                    foreach ($car_ids_arr as $car) {
                        $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '');
                        $vehiclemodel = new Vehicle;
                        $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                    }

                    $data = array('status' => $status, 'order_canceled_at' => date("Y-m-d H:i:s"));
                    $washrequestmodel = new Washingrequests;
                    $washrequestmodel->attributes = $data;

                    $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id' => $wash_request_id));

                    $result = 'true';
                    if ($status == 5)
                        $response = 'Wash request is canceled by client';
                    if ($status == 6)
                        $response = 'Wash request is canceled by agent';


                    if ($wrequest_id_check->agent_id && $wrequest_id_check->agent_id > 0) {
                        $agentmodel = Agents::model()->findByPk($wrequest_id_check->agent_id);
                        $agentmodel->available_for_new_order = 1;
                        $agentmodel->save(false);
                    }
                }

                if ($wrequest_id_check->transaction_id) {
                    if ($wrequest_id_check->wash_request_position == 'real')
                        $voidresult = Yii::app()->braintree->void_real($wrequest_id_check->transaction_id);
                    else
                        $voidresult = Yii::app()->braintree->void($wrequest_id_check->transaction_id);
                }

                $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $wrequest_id_check->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                if (!count($is_cust_has_order)) {
                    Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 0));
                }

                if ($wrequest_id_check->coupon_code) {
                    CustomerDiscounts::model()->deleteAll("wash_request_id=" . $wrequest_id_check->id . " AND customer_id=" . $wrequest_id_check->customer_id . " AND promo_code='" . $wrequest_id_check->coupon_code . "'");
                }

                if ($status == 5 && $wrequest_id_check->status != 5 && (!$admin_username)) {
                    if ($action_log == 0) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $wash_request_id,
                            'action' => 'cancelorderclient',
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                        $action_log = 1;
                    }
                }

                if ($status == 6) {
                    $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'cancelorderwasher',
                        'agent_id' => $wrequest_id_check->agent_id,
                        'agent_company_id' => $agent_detail->real_washer_id,
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            } else if (($wrequest_id_check->status > 1) && ($wrequest_id_check->status <= 4) && $status == 5) {
                $result = 'false';
                $response = 'you cannot cancel wash until paying $10';

                if ($wrequest_id_check->transaction_id) {
                    if ($wrequest_id_check->wash_request_position == 'real')
                        $voidresult = Yii::app()->braintree->void_real($wrequest_id_check->transaction_id);
                    else
                        $voidresult = Yii::app()->braintree->void($wrequest_id_check->transaction_id);
                }

                $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $wrequest_id_check->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                if (!count($is_cust_has_order)) {
                    Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 0));
                }

                if ($wrequest_id_check->coupon_code) {
                    CustomerDiscounts::model()->deleteAll("wash_request_id=" . $wrequest_id_check->id . " AND customer_id=" . $wrequest_id_check->customer_id . " AND promo_code='" . $wrequest_id_check->coupon_code . "'");
                }

                if ($status == 5 && $wrequest_id_check->status != 5 && (!$admin_username)) {
                    if ($action_log == 0) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $wash_request_id,
                            'action' => 'cancelorderclient',
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                        $action_log = 1;
                    }
                }

                if ($status == 6) {
                    $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'cancelorderwasher',
                        'agent_id' => $wrequest_id_check->agent_id,
                        'agent_company_id' => $agent_detail->real_washer_id,
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            } else {
                if (($wrequest_id_check->is_scheduled == 1) && ($status == 6)) {
                    $sched_time = '';
                    $sched_date = '';
                    if ($wrequest_id_check->reschedule_time) {
                        $sched_date = $wrequest_id_check->reschedule_date;
                        $sched_time = $wrequest_id_check->reschedule_time;
                    } else {
                        $sched_date = $wrequest_id_check->schedule_date;
                        $sched_time = $wrequest_id_check->schedule_time;
                    }

                    $scheduledatetime = $sched_date . " " . $sched_time;
                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;

                    $min_diff = round(($from_time - $to_time) / 60, 2);

                    if ($min_diff <= 60) {
                        $json = array(
                            'result' => 'false',
                            'response' => "You may not cancel a scheduled order within 1 hour of start time. If you must cancel due to emergency, please contact MobileWash at (888) 209-5585"
                        );

                        echo json_encode($json);
                        die();
                    }
                }
                if (($wrequest_id_check->status > 1) && ($wrequest_id_check->status <= 3)) {

                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '27' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    if (count($clientdevices)) {
                        foreach ($clientdevices as $ctdevice) {
                            //$message =  "You have a new scheduled wash request.";
                            $device_type = strtolower($ctdevice['device_type']);
                            $notify_token = $ctdevice['device_token'];
                            $alert_type = "default";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }


                $car_ids = $wrequest_id_check->car_list;
                $car_ids_arr = explode(",", $car_ids);
                foreach ($car_ids_arr as $car) {
                    $carresetdata = array('status' => 0, 'eco_friendly' => 0, 'damage_points' => '', 'damage_pic' => '', 'upgrade_pack' => 0, 'edit_vehicle' => 0, 'remove_vehicle_from_kart' => 0, 'new_vehicle_confirm' => 0, 'new_pack_name' => '');
                    $vehiclemodel = new Vehicle;
                    $vehiclemodel->updateAll($carresetdata, 'id=:id', array(':id' => $car));
                }


                $custmodel = Customers::model()->findByPk($wrequest_id_check->customer_id);
                $data = array('status' => $status, 'order_canceled_at' => date("Y-m-d H:i:s"), 'customer_wash_points' => $custmodel->fifth_wash_points);
                $washrequestmodel = new Washingrequests;
                $washrequestmodel->attributes = $data;

                $resUpdate = $washrequestmodel->updateAll($data, 'id=:id', array(':id' => $wash_request_id));

                $result = 'true';
                if ($status == 5)
                    $response = 'Wash request is canceled by client';
                if ($status == 6)
                    $response = 'Wash request is canceled by agent';


                if ($wrequest_id_check->agent_id && $wrequest_id_check->agent_id > 0) {
                    $agentmodel = Agents::model()->findByPk($wrequest_id_check->agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);
                }

                if ($wrequest_id_check->transaction_id) {
                    if ($wrequest_id_check->wash_request_position == 'real')
                        $voidresult = Yii::app()->braintree->void_real($wrequest_id_check->transaction_id);
                    else
                        $voidresult = Yii::app()->braintree->void($wrequest_id_check->transaction_id);
                }

                $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $wrequest_id_check->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                if (!count($is_cust_has_order)) {
                    Customers::model()->updateByPk($wrequest_id_check->customer_id, array("is_first_wash" => 0));
                }

                if ($wrequest_id_check->coupon_code) {
                    CustomerDiscounts::model()->deleteAll("wash_request_id=" . $wrequest_id_check->id . " AND customer_id=" . $wrequest_id_check->customer_id . " AND promo_code='" . $wrequest_id_check->coupon_code . "'");
                }

                if ($status == 5 && $wrequest_id_check->status != 5 && (!$admin_username) && (!$ondemand_cancel_for_schedule)) {
                    if ($action_log == 0) {
                        $washeractionlogdata = array(
                            'wash_request_id' => $wash_request_id,
                            'action' => 'cancelorderclient',
                            'action_date' => date('Y-m-d H:i:s'));
                        Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                        $action_log = 1;
                    }
                }

                if ($status == 6) {
                    $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'cancelorderwasher',
                        'agent_id' => $wrequest_id_check->agent_id,
                        'agent_company_id' => $agent_detail->real_washer_id,
                        'action_date' => date('Y-m-d H:i:s'));
                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }
            }

            if (($result == 'true') && ($wrequest_id_check->agent_id)) {

                $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                $cust_detail = Customers::model()->findByAttributes(array("id" => $wrequest_id_check->customer_id));
                if (((APP_ENV == 'real')) && (!$agent_detail->block_washer) && ($agent_detail->sms_control)) {
                    $this->layout = "xmlLayout";

                    //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                    $account_sid = TWILIO_SID;
                    $auth_token = TWILIO_AUTH_TOKEN;
                    $client = new Services_Twilio($account_sid, $auth_token);

                    $message = "Order #" . $wrequest_id_check->id . " has been canceled";

                    try {
                        $sendmessage = $client->account->messages->create(array(
                            'To' => $agent_detail->phone_number,
                            'From' => '+13103128070',
                            'Body' => $message,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }
                }

                $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wrequest_id_check->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                $message = "Order #" . $wrequest_id_check->id . " has been canceled";

                if ((count($agentdevices)) && (!$agent_detail->block_washer)) {
                    foreach ($agentdevices as $agdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        //echo $agentdetails['mobile_type'];
                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "default";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }

            if (($result == 'true') && ($wash_now_canceled == 1) && (APP_ENV == 'real')) {
                //if(($result == 'true') && ($wash_now_canceled == 1)){
                $mobile_receipt = '';

                $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);

                foreach ($kartdata->vehicles as $ind => $vehicle) {
                    $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                    if ($vehicle->surge_vehicle_fee > 0) {
                        $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->extclaybar_vehicle_fee > 0) {

                        $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->waterspotremove_vehicle_fee > 0) {

                        $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                    }
                    if ($vehicle->exthandwax_vehicle_fee > 0) {

                        $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->pet_hair_fee > 0) {

                        $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                    }
                    if ($vehicle->lifted_vehicle_fee > 0) {

                        $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->extplasticdressing_vehicle_fee > 0) {

                        $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->upholstery_vehicle_fee > 0) {

                        $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                    }

                    if ($vehicle->floormat_vehicle_fee > 0) {

                        $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                    }

                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {

                        $mobile_receipt .= "Promo: " . $kartdata->coupon_code . " -$" . number_format($kartdata->coupon_discount, 2) . "\r\n";
                    }


                    if ($vehicle->fifth_wash_discount > 0) {

                        $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                    }

                    if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }

                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {

                        $mobile_receipt .= "Bundle -$1.00\r\n";
                    }
                    $mobile_receipt .= "------\r\n";
                }

                if ($kartdata->tip_amount > 0) {
                    $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
                }

                if ($kartdata->wash_now_fee > 0) {
                    $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
                }

                if ($kartdata->wash_later_fee > 0) {
                    $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
                }


                $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";

                if (APP_ENV == 'real') {
                    $this->layout = "xmlLayout";


                    //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                    $account_sid = TWILIO_SID;
                    $auth_token = TWILIO_AUTH_TOKEN;

                    $client = new Services_Twilio($account_sid, $auth_token);
                    $wash_details = Washingrequests::model()->findByPk($wash_request_id);
                    $customers_id_check = Customers::model()->findByAttributes(array("id" => $wash_details->customer_id));

                    $smscontent = "WASH NOW CANCELED #000" . $wash_request_id . "- " . date('M d', strtotime($wash_details->order_for)) . " @ " . date('h:i A', strtotime($wash_details->order_for)) . "\r\n" . $customers_id_check->first_name . " " . $customers_id_check->last_name . "\r\n" . $customers_id_check->contact_number . "\r\n" . $wash_details->address . "\r\n (" . $wash_details->address_type . ")\r\n------\r\n" . $mobile_receipt;


                    try {
                        $sendmessage = $client->account->messages->create(array(
                            'To' => '8183313631',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }

                    try {

                        $sendmessage = $client->account->messages->create(array(
                            'To' => '3109999334',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }

                    try {

                        $sendmessage = $client->account->messages->create(array(
                            'To' => '3103442534',
                            'From' => '+13103128070',
                            'Body' => $smscontent,
                        ));
                    } catch (Services_Twilio_RestException $e) {
                        //echo  $e;
                    }
                }
            }

            if (($result == 'true') && ($wash_now_canceled == 1)) {
                Washingrequests::model()->updateByPk($wrequest_id_check->id, array("all_reject_ids" => '', "agent_reject_ids" => ''));
                Washingrequests::model()->updateAll(array("all_reject_ids" => '', "agent_reject_ids" => ''), 'status=:status', array(':status' => 0));
            }
        } else {
            $result = 'false';
            $response = 'Pass the required parameters';
        }
        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionwasherenroutecancel() {
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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $status = Yii::app()->request->getParam('status');

        $result = 'false';
        $response = 'Pass the required parameters';
        $json = array();
        $api_password = '';
        $api_password = Yii::app()->request->getParam('api_password');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        if ((isset($wash_request_id) && !empty($wash_request_id)) && (isset($status) && !empty($status))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }
            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));

            if (!count($wrequest_id_check)) {
                $result = 'false';
                $response = 'Invalid wash request id';
            } else {

                $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
                if ($admin_username) {

                    $washeractionlogdata = array(
                        'agent_id' => $wrequest_id_check->agent_id,
                        'wash_request_id' => $wash_request_id,
                        'agent_company_id' => $agent_detail->real_washer_id,
                        'admin_username' => $admin_username,
                        'action' => 'admindropjob',
                        'action_date' => date('Y-m-d H:i:s'));
                } else {
                    $washeractionlogdata = array(
                        'wash_request_id' => $wash_request_id,
                        'action' => 'washerenroutecancel',
                        'agent_id' => $wrequest_id_check->agent_id,
                        'agent_company_id' => $agent_detail->real_washer_id,
                        'action_date' => date('Y-m-d H:i:s'));
                }

                Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                Washingrequests::model()->updateByPk($wrequest_id_check->id, array('agent_id' => 0, 'canceled_washer_id' => $wrequest_id_check->agent_id, 'wash_begin' => date("Y-m-d H:i:s")));

                if ($wrequest_id_check->agent_id && $wrequest_id_check->agent_id > 0) {
                    $agentmodel = Agents::model()->findByPk($wrequest_id_check->agent_id);
                    $agentmodel->available_for_new_order = 1;
                    $agentmodel->save(false);
                }

                if ($wrequest_id_check->transaction_id) {
                    if ($wrequest_id_check->wash_request_position == 'real')
                        $voidresult = Yii::app()->braintree->void_real($wrequest_id_check->transaction_id);
                    else
                        $voidresult = Yii::app()->braintree->void($wrequest_id_check->transaction_id);
                }


                if ($wrequest_id_check->is_scheduled)
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array('status' => 0, 'agent_id' => 0, 'washer_on_way_push_sent' => 0, 'is_create_schedulewash_push_sent' => 0));
                else
                    Washingrequests::model()->updateByPk($wrequest_id_check->id, array('status' => 0, 'agent_id' => 0, 'washer_on_way_push_sent' => 0));

                $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '29' ")->queryAll();
                $message = $pushmsg[0]['message'];

                if (count($clientdevices)) {
                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "default";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (!$wrequest_id_check->is_scheduled) {
                    $app_settings = Yii::app()->db->createCommand("SELECT * FROM `app_settings` WHERE `app_type` = 'IOS'")->queryAll();
                    $sql = "SELECT * FROM agent_locations";
                    $command = Yii::app()->db->createCommand($sql)->queryAll();

                    foreach ($command as $loc) {
                        /* --------- distance calculation ------------ */
                        $theta = $wrequest_id_check->longitude - $loc['longitude'];
                        $dist = sin(deg2rad($wrequest_id_check->latitude)) * sin(deg2rad($loc['latitude'])) + cos(deg2rad($wrequest_id_check->latitude)) * cos(deg2rad($loc['latitude'])) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        $unit = strtoupper($unit);
                        if (($miles > 0) && ($miles <= 10)) {
                            $isavailable = Yii::app()->db->createCommand("SELECT * FROM agents WHERE id='" . $loc['agent_id'] . "' AND block_washer = 0")->queryAll();
                            if ((count($isavailable) > 0) && ($wrequest_id_check->agent_id != $loc['agent_id'])) {
                                $distance_array[$loc['agent_id']] = $miles;
                            }
                        }
                    }

                    if (count($distance_array)) {
                        asort($distance_array);
                        foreach ($distance_array as $aid => $dist) {

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '28' ")->queryAll();
                            $message = $pushmsg[0]['message'];
                            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $aid . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                            $message2 = str_replace(array("[CITY]", "[WASHER_TOTAL]"), array(strtoupper($wrequest_id_check->city), number_format($wrequest_id_check->agent_total, 2)), $message);
                            if (count($agentdevices) > 0) {
                                foreach ($agentdevices as $agdevice) {

                                    $device_type = strtolower($agdevice['device_type']);
                                    $notify_token = $agdevice['device_token'];
                                    $alert_type = "default";

                                    $notify_msg = urlencode($message2);

                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type . "&notification_type=wash_now_notify";
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }
                        }
                    }
                }

                $result = 'true';
                $response = 'wash reset';
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionpendingwashesdetails() {

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
        $response = 'No pending orders found';
        $json = array();
        $pendingwasharr = array();

        $pendingwashes = Washingrequests::model()->findAllByAttributes(array('status' => 0, 'is_scheduled' => 0));

        if (!count($pendingwashes)) {
            $result = 'false';
            $response = 'No pending orders found';
        } else {
            $result = 'true';
            $response = 'pending washes';
            foreach ($pendingwashes as $ind => $pwash) {
                $cust_details = Customers::model()->findByAttributes(array('id' => $pwash['customer_id']));
                $pendingwasharr[$ind]['id'] = $pwash['id'];
                $pendingwasharr[$ind]['customer_id'] = $pwash['customer_id'];
                $pendingwasharr[$ind]['customer_name'] = $cust_details->first_name . " " . $cust_details->last_name;
                $pendingwasharr[$ind]['address'] = $pwash['address'];
                $pendingwasharr[$ind]['latitude'] = $pwash['latitude'];
                $pendingwasharr[$ind]['longitude'] = $pwash['longitude'];

                /* ------- get nearest agents --------- */

                $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
                $data = array('wash_request_id' => $pwash['id'], "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($handle);
                curl_close($handle);
                $nearagentsdetails = json_decode($output);

                /* ------- get nearest agents end --------- */

                if (count($nearagentsdetails->nearest_agents)) {
                    $i = 0;
                    foreach ($nearagentsdetails->nearest_agents as $aid => $nagent) {
                        $agent_details = Agents::model()->findByAttributes(array('id' => $aid));
                        $agent_loc_details = AgentLocations::model()->findByAttributes(array('agent_id' => $aid));
                        $pendingwasharr[$ind]['available_agents'][$i]['id'] = $aid;
                        $pendingwasharr[$ind]['available_agents'][$i]['name'] = $agent_details->first_name . " " . $agent_details->last_name;
                        $pendingwasharr[$ind]['available_agents'][$i]['latitude'] = $agent_loc_details->latitude;
                        $pendingwasharr[$ind]['available_agents'][$i]['longitude'] = $agent_loc_details->longitude;
                        $pendingwasharr[$ind]['available_agents'][$i]['distance'] = round($nagent, 2);
                        $i++;
                    }
                } else {
                    $pendingwasharr[$ind]['available_agents'] = '';
                }

                //$pendingwasharr[$ind]['available_agents'] = $nearagentsdetails->nearest_agents;
            }
        }




        $json = array(
            'result' => $result,
            'response' => $response,
            'pending_washes' => $pendingwasharr
        );
        echo json_encode($json);
    }

    public function actioncheckcoveragezipcode() {

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

        $zipcode = Yii::app()->request->getParam('zipcode');

        $result = 'false';
        $response = 'enter zip code';

        if ((isset($zipcode) && !empty($zipcode))) {

            $check_zip = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $zipcode));

            if (count($check_zip)) {
                $result = 'true';
                $response = 'zipcode found';
            } else {
                $response = 'zipcode not found';
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actioncheckcoveragezipcodeweb() {

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

        $zipcode = Yii::app()->request->getParam('zipcode');

        $result = 'false';
        $response = 'enter zip code';

        if ((isset($zipcode) && !empty($zipcode))) {

            $check_zip = CoverageAreaCodesWeb::model()->findByAttributes(array('zipcode' => $zipcode));

            if (count($check_zip)) {
                $result = 'true';
                $response = 'zipcode found';
            } else {
                $response = 'zipcode not found';
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actiongetallcoveragezipcodes() {

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

        $all_zipcodes = array();

        $result = 'false';
        $response = 'none';

        $codes_exists = Yii::app()->db->createCommand()->select('*')->from('coverage_area_zipcodes')->queryAll();

        if (count($codes_exists) > 0) {
            $result = 'true';
            $response = 'all coverage area zipcodes';

            foreach ($codes_exists as $ind => $zipcode) {

                if ($zipcode['zip_color'] != 'gray') {
                    $all_zipcodes[]['id'] = $zipcode['id'];
                    $all_zipcodes[]['zipcode'] = $zipcode['zipcode'];
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'zipcodes' => $all_zipcodes
        );
        echo json_encode($json);
    }

    public function actionaddcoveragezipcode() {

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


        $zipcode = Yii::app()->request->getParam('zipcode');


        if ((isset($zipcode) && !empty($zipcode))) {

            $zip_check = CoverageAreaCodes::model()->findAllByAttributes(array("zipcode" => $zipcode));

            if (count($zip_check) > 0) {
                $result = 'false';
                $response = 'zipcode already exists';
            } else {
                $zipdata = array(
                    'zipcode' => $zipcode
                );

                $model = new CoverageAreaCodes;
                $model->attributes = $zipdata;
                if ($model->save(false)) {
                    $zip_id = Yii::app()->db->getLastInsertID();
                }

                $result = 'true';
                $response = 'zipcode added successfully';
            }
        }
        $json = array(
            'result' => $result,
            'response' => $response,
        );
        echo json_encode($json);
    }

    public function actionupdatecoveragezipcode() {

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
        $response = 'Please provide zipcode';

        $zipcode = Yii::app()->request->getParam('zipcode');
        $zipcolor = '';
        if (Yii::app()->request->getParam('zipcolor'))
            $zipcolor = Yii::app()->request->getParam('zipcolor');


        if ((isset($zipcode) && !empty($zipcode))) {

            $code_exists = CoverageAreaCodes::model()->findByAttributes(array("zipcode" => $zipcode));
            if (!count($code_exists)) {
                $response = "No zipcode found";
            } else {
                $response = "zipcode updated";
                $result = 'true';

                CoverageAreaCodes::model()->updateByPk($code_exists->id, array('zip_color' => $zipcolor));
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
    }

    public function actionupdategroupcoveragezipcodes() {

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
        $response = 'Please provide zipcode';

        $zipcode = Yii::app()->request->getParam('zipcode');
        $zipcolor = '';
        if (Yii::app()->request->getParam('zipcolor'))
            $zipcolor = Yii::app()->request->getParam('zipcolor');


        if ((isset($zipcode) && !empty($zipcode))) {
            $zip_arr = explode(",", $zipcode);
            $zip_str = join("','", $zip_arr);
            $response = "zipcode updated";
            $result = 'true';

            Yii::app()->db->createCommand("UPDATE coverage_area_zipcodes SET zip_color = :zip_color WHERE zipcode IN ('$zip_str') ")->bindValue(':zip_color', $zipcolor, PDO::PARAM_STR)->execute();
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
    }

    public function actiondeletecoveragezipcode() {

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
        $response = 'Please provide zipcode id';

        $id = Yii::app()->request->getParam('id');



        if ((isset($id) && !empty($id))) {

            $code_exists = CoverageAreaCodes::model()->findByAttributes(array("id" => $id));
            if (!count($code_exists)) {
                $response = "Invalid zipcode id";
            } else {
                $response = "zipcode deleted";
                $result = 'true';

                CoverageAreaCodes::model()->deleteByPk(array('id' => $id));
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
    }

    public function Actionorder_schedule() {

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

        /* Checking for post(month) parameters */
        $order_month = '';
        if (!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))) {
            $last_month = Yii::app()->request->getParam('start');
            $curr_month = Yii::app()->request->getParam('end');
            $order_month = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= :last_month AND DATE_FORMAT(schedule_date,'%Y-%m')<= :curr_month)";
        }
        /* Post END */
        //$path = '/home/devmobilewash/public_html/api/protected/controllers/test.php';
        /* Phone Orders */
        $phone_orders = array();

        $orders_exists = Yii::app()->db->createCommand("SELECT * FROM phone_orders$order_month ORDER BY schedule_date DESC")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();

        if (count($orders_exists) > 0) {
            $result = 'true';
            $response = 'all orders';
            foreach ($orders_exists as $ind => $order) {

                $ph_order_id = $order['id'];
                $ph_checklist = $order['checklist'];
                $ph_created = $order['schedule_date'];
                $checklist_arr = explode("|", $order['checklist']);
                if (!$order['checklist']) {
                    $order_of_status = 'Pending';
                    $color = '#FF3B30';
                } else if (count($checklist_arr) == 8) {
                    $order_of_status = 'Complete';
                    $color = '#30A0FF';
                } else {
                    $order_of_status = 'Processing';
                    $color = '#EF9047';
                }
                $phone_orders[] = array(
                    "start" => date('Y-m-d', strtotime($ph_created)),
                    "title" => $order_of_status,
                    "color" => $color
                );
            }
            $data = array();
            /* foreach($orderview as $key=>$value){ */
            foreach ($phone_orders as $key => $value) {
                if ($value['title'] == 'Complete') {
                    $data[$value['start']]['complete'][] = $value['title'];
                }
                if ($value['title'] == 'Pending') {
                    $data[$value['start']]['pending'][] = $value['title'];
                }
                if ($value['title'] == 'Processing') {
                    $data[$value['start']]['processing'][] = $value['title'];
                }
            }
            $dt = array();
            foreach ($data as $key => $val) {
                $dt[$key]['complete']['color'] = '';
                $dt[$key]['pending']['color'] = '';
                $dt[$key]['processing']['color'] = '';
                $dt[$key]['home']['count'] = '';
                $dt[$key]['work']['count'] = '';
                if (count($val['complete']) > 0) {
                    $dt[$key]['complete']['count'] = count($val['complete']);
                    $dt[$key]['complete']['color'] = '#30A0FF';
                }
                if (count($val['pending']) > 0) {
                    $dt[$key]['pending']['count'] = count($val['pending']);
                    $dt[$key]['pending']['color'] = '#FF3B30';
                }
                if (count($val['processing']) > 0) {
                    $dt[$key]['processing']['count'] = count($val['processing']);
                    $dt[$key]['processing']['color'] = '#EF9047';
                }
            }
            $ordersdetails['order'] = $dt;
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        } else {
            $ordersdetails['order'] = array('empty' => 'yes');
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        }
    }

    /* App Order */

    public function Actionorder_schedule_app() {

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

        /* if (!$token_check) {
          $json = array(
          'result' => 'false',
          'response' => 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */
        /* Checking for post(month) parameters */
        $order_month = '';
        if (!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))) {
            $last_month = Yii::app()->request->getParam('start');
            $curr_month = Yii::app()->request->getParam('end');
            $order_month = " AND ( DATE_FORMAT(a.order_for,'%Y-%m')>= :last_month AND DATE_FORMAT(a.order_for,'%Y-%m')<= :curr_month) ";
            //$order_month = " AND (( DATE_FORMAT(a.order_for,'%Y-%m')>= :last_month AND DATE_FORMAT(a.order_for,'%Y-%m')<= :curr_month) OR ( DATE_FORMAT(a.complete_order,'%Y-%m')>= :last_month AND DATE_FORMAT(a.complete_order,'%Y-%m')<= :curr_month)) ";
        }
        /* Post END */

        /* web orderds */
        $total_order = Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "'")->queryAll();

        $count = $total_order[0]['countid'];

        $customers_order = Yii::app()->db->createCommand("SELECT a.id, a.car_list, a.package_list, a.coupon_code, a.tip_amount, a.status, a.schedule_date, a.created_date, a.order_for, a.address_type, a.zipcode, a.failed_transaction_id, a.wash_request_position, a.pet_hair_vehicles, a.lifted_vehicles, a.exthandwax_vehicles, a.extplasticdressing_vehicles, a.extclaybar_vehicles, a.waterspotremove_vehicles, a.upholstery_vehicles, a.floormat_vehicles, a.is_scheduled,a.complete_order,a.is_cust_first_wash,b.total_wash, a.customer_id FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "' AND  a.status != 7 " . $order_month)
                //$customers_order = Yii::app()->db->createCommand("SELECT a.id, a.car_list, a.package_list, a.coupon_code, a.tip_amount, a.status, a.schedule_date, a.created_date, a.order_for, a.address_type, a.zipcode, a.failed_transaction_id, a.wash_request_position, a.pet_hair_vehicles, a.lifted_vehicles, a.exthandwax_vehicles, a.extplasticdressing_vehicles, a.extclaybar_vehicles, a.waterspotremove_vehicles, a.upholstery_vehicles, a.floormat_vehicles, a.is_scheduled,b.total_wash, a.customer_id FROM washing_requests a  JOIN customers b ON a.customer_id = b.id  JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "' AND a.status != 7 $order_month")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();

        /* END */
        if (!empty($customers_order)) {
            $check_auto_canceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'scheduleauto-canceled'")->queryAll();
            $scheduleautoArr = array();
            if (count($check_auto_canceled) > 0) {
                foreach ($check_auto_canceled as $key => $value) {
                    $scheduleautoArr[] = $value['wash_request_id'];
                }
            }
            $ondemandauto_canceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'ondemandautocancel'")->queryAll();
            $ondemandautocancelArr = array();
            if (count($ondemandauto_canceled) > 0) {
                foreach ($ondemandauto_canceled as $key => $value) {
                    $ondemandautocancelArr[] = $value['wash_request_id'];
                }
            }

            $get_befor_count = Yii::app()->db->createCommand("SELECT customer_id, order_for FROM washing_requests WHERE status = 4 GROUP BY customer_id ORDER BY id ASC")->queryAll();
            $ArrCustomers_date = array();
            if (count($get_befor_count) > 0) {
                foreach ($get_befor_count as $key => $value) {
                    $ArrCustomers_date[$value['customer_id']] = date('Y-m-d', strtotime($value['order_for']));
                }
            }

            foreach ($customers_order as $orderbycustomer) {

                //$counttype = array_count_values($package_list_explode);
                $orderstatus = $orderbycustomer['status'];
                $orderid = $orderbycustomer['id'];
                $address_type = $orderbycustomer['address_type'];
                $created_date = $orderbycustomer['order_for'];
                $wash_request_position = $orderbycustomer['wash_request_position'];
                $color = '';
                $check_auto_canceled = $ondemandautocanceled = 0;
                if (in_array($orderbycustomer['id'], $scheduleautoArr)) {
                    $check_auto_canceled = 1;
                }
                if (in_array($orderbycustomer['id'], $ondemandautocancelArr)) {
                    $ondemandautocanceled = 1;
                }
                $zipcolor = '';

                if ($orderbycustomer['zipcode']) {
                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $orderbycustomer['zipcode']));

                    if (count($coveragezipcheck)) {
                        $zipcolor = $coveragezipcheck->zip_color;
                    }
                }

                if ($orderstatus == 4) {
                    $order_of_status = 'Complete';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($check_auto_canceled == 1 && $orderbycustomer['is_scheduled'] == 1) {
                    $order_of_status = 'Scheduled-auto';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($ondemandautocanceled == 1 && $orderbycustomer['is_scheduled'] == 0) {
                    $order_of_status = 'ondemandautocanceled';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($orderstatus == 0) {
                    $order_of_status = 'Pending';
                    $time = $orderbycustomer['order_for'];
                    $currentdate = date("Y-m-d h:i:s");
                    $to_time = strtotime($time);
                    $from_time = strtotime($currentdate);
                    $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                    $color = '#FF3B30';
                } elseif ($orderstatus >= 1 && $orderstatus <= 3) {
                    $order_of_status = 'Processing';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#EF9047';
                } elseif (($orderstatus == 5) || ($orderstatus == 6)) {
                    $order_of_status = 'Canceled';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#AAAAAA';
                }

                if ($orderbycustomer['failed_transaction_id']) {
                    $order_of_status = 'Declined';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#cc0066';
                }
                ///$pakage_List = "";
                if (($orderstatus != 0) && ($orderstatus != 4)) {
                    $orderbycustomer['package_list'] = "N/A";
                }

                $key = 'order_' . $count . '_' . $orderid;
                $json = array();
                $json['title'] = $order_of_status;
                $json['orderid'] = $orderid;
                $json['time'] = $totalminutes;
                $json['address_type'] = $address_type;
                $json['start'] = date('Y-m-d', strtotime($created_date));
                //$orderbycustomer['total_wash'] = 1;
                //if ($ArrCustomers_date[$orderbycustomer['customer_id']] == date('Y-m-d', strtotime($created_date))) {
                //  $orderbycustomer['total_wash'] = 0;
                //}
                if ($wash_request_position == APP_ENV) {

                    $orderview[] = array(
                        "start" => date('Y-m-d', strtotime($created_date)),
                        "title" => $order_of_status,
                        "color" => $color,
                        "address_type" => $address_type,
                        "car_list" => $orderbycustomer['car_list'],
                        "package_list" => $orderbycustomer['package_list'],
                        "coupon_code" => $orderbycustomer['coupon_code'],
                        "tip_amount" => $orderbycustomer['tip_amount'],
                        "is_scheduled" => $orderbycustomer['is_scheduled'],
                        "pet_hair_vehicles" => $orderbycustomer['pet_hair_vehicles'],
                        "lifted_vehicles" => $orderbycustomer['lifted_vehicles'],
                        "exthandwax_vehicles" => $orderbycustomer['exthandwax_vehicles'],
                        "extplasticdressing_vehicles" => $orderbycustomer['extplasticdressing_vehicles'],
                        "extclaybar_vehicles" => $orderbycustomer['extclaybar_vehicles'],
                        "waterspotremove_vehicles" => $orderbycustomer['waterspotremove_vehicles'],
                        "upholstery_vehicles" => $orderbycustomer['upholstery_vehicles'],
                        "floormat_vehicles" => $orderbycustomer['floormat_vehicles'],
                        "total_wash" => $orderbycustomer['total_wash'],
                        "complete_order" => $orderbycustomer['complete_order'],
                        "is_cust_first_wash" => $orderbycustomer['is_cust_first_wash'],
                        "zip_color" => $zipcolor,
                        "orderstatus" => $orderstatus,
                    );
                }
            }
            $data = $ArrCustemer = array();
            foreach ($orderview as $key => $value) {

                /* $packages = explode(",", $value['package_list']);
                  if(count($packages)>0){
                  foreach ($packages as $package) {
                  $data[$value['start']][trim($package)][] = 1;
                  }
                  } */

                //$total_new_orders = Yii::app()->db->createCommand("SELECT COUNT(id) as countid FROM washing_requests WHERE is_cust_first_wash = 1 AND status = 4 AND DATE_FORMAT(order_for,'%Y-%m-%d') = '".$value['start']."'")->queryAll();
                //$total_new_count = $total_new_orders[0]['countid'];
                $cancledOrder = "N/A";
                $data[$value['start']]['OrderCancled'] = $cancledOrder;
                if ($value['orderstatus'] == 5 || $value['orderstatus'] == 6) {
                    $cancledOrder = "OrderCancled";
                }

                if (($value['is_cust_first_wash']) && ($value['orderstatus'] == 4)) {
                    //$data[$value['start']]['new_customer'][] = array_fill(0, $total_new_count, 1);
                    $data[$value['start']]['new_customer'][] = 1;
                }

//                if ((($value['zip_color'] == '') || ($value['zip_color'] == 'blue')) && ($value['title'] != 'Canceled')) {
//                    $data[$value['start']]['zip_blue'][] = 1;
//                }
                if ((($value['zip_color'] == '') || ($value['zip_color'] == 'blue')) && ($cancledOrder != 'OrderCancled')) {
                    $data[$value['start']]['zip_blue'][] = 1;
                }


                if (($value['zip_color'] == 'yellow') && ($cancledOrder != 'OrderCancled')) {
                    $data[$value['start']]['zip_yellow'][] = 1;
                }

                if (($value['zip_color'] == 'red') && ($cancledOrder != 'OrderCancled')) {
                    $data[$value['start']]['zip_red'][] = 1;
                }


                if (($value['zip_color'] == 'purple') && ($cancledOrder != 'OrderCancled')) {
                    $data[$value['start']]['zip_purple'][] = 1;
                }

                if ($value['address_type'] == 'Home') {
                    $data[$value['start']]['home'][] = $value['address_type'];
                }
                if ($value['address_type'] == 'Work') {
                    $data[$value['start']]['work'][] = $value['address_type'];
                }
                if ($value['title'] == 'Complete') {
                    $data[$value['start']]['complete'][] = $value['title'];
                    if ($value['is_scheduled'] == 1)
                        $data[$value['start']]['schedulecompleted'][] = 'schedulecompleted';
                    else
                        $data[$value['start']]['ondemandcompleted'][] = 'ondemandcompleted';

                    if (trim($value['pet_hair_vehicles']))
                        $data[$value['start']]['pet_hair_vehicles'][] = count(explode(",", $value['pet_hair_vehicles']));
                    else
                        $data[$value['start']]['pet_hair_vehicles'][] = 0;

                    if (trim($value['lifted_vehicles']))
                        $data[$value['start']]['lifted_vehicles'][] = count(explode(",", $value['lifted_vehicles']));
                    else
                        $data[$value['start']]['lifted_vehicles'][] = 0;

                    if (trim($value['exthandwax_vehicles']))
                        $data[$value['start']]['exthandwax_vehicles'][] = count(explode(",", $value['exthandwax_vehicles']));
                    else
                        $data[$value['start']]['exthandwax_vehicles'][] = 0;

                    if (trim($value['extplasticdressing_vehicles']))
                        $data[$value['start']]['extplasticdressing_vehicles'][] = count(explode(",", $value['extplasticdressing_vehicles']));
                    else
                        $data[$value['start']]['extplasticdressing_vehicles'][] = 0;

                    if (trim($value['extclaybar_vehicles']))
                        $data[$value['start']]['extclaybar_vehicles'][] = count(explode(",", $value['extclaybar_vehicles']));
                    else
                        $data[$value['start']]['extclaybar_vehicles'][] = 0;

                    if (trim($value['waterspotremove_vehicles']))
                        $data[$value['start']]['waterspotremove_vehicles'][] = count(explode(",", $value['waterspotremove_vehicles']));
                    else
                        $data[$value['start']]['waterspotremove_vehicles'][] = 0;

                    if (trim($value['upholstery_vehicles']))
                        $data[$value['start']]['upholstery_vehicles'][] = count(explode(",", $value['upholstery_vehicles']));
                    else
                        $data[$value['start']]['upholstery_vehicles'][] = 0;

                    if (trim($value['floormat_vehicles']))
                        $data[$value['start']]['floormat_vehicles'][] = count(explode(",", $value['floormat_vehicles']));
                    else
                        $data[$value['start']]['floormat_vehicles'][] = 0;
                }

                if ($value['title'] == 'Pending') {
                    $data[$value['start']]['pending'][] = $value['title'];
                }
                if ($value['title'] == 'Scheduled-auto') {
                    $data[$value['start']]['scheduled_auto'][] = $value['title'];
                }
                if ($value['title'] == 'ondemandautocanceled') {
                    $data[$value['start']]['ondemandautocanceled'][] = $value['title'];
                }
                if ($value['title'] == 'Processing') {
                    $data[$value['start']]['processing'][] = $value['title'];
                }
                if ($value['title'] == 'Canceled') {
                    $data[$value['start']]['canceled'][] = $value['title'];
                    if ($value['is_scheduled'] == 1)
                        $data[$value['start']]['schedulecanceled'][] = $value['title'];
                    else
                        $data[$value['start']]['ondemandcanceled'][] = $value['title'];
                }

                if ($value['title'] == 'Declined') {
                    $data[$value['start']]['declined'][] = $value['title'];
                }

                if (($value['title'] == 'Pending') || ($value['title'] == 'Processing') || ($value['title'] == 'Complete')) {
                    $data[$value['start']]['total_cars'][] = count(explode(",", $value['car_list']));


                    $packages = explode(",", $value['package_list']);
                    if (count($packages) > 0) {
                        foreach ($packages as $package) {
                            $data[$value['start']][trim($package)][] = 1;
                        }
                    }


                    if (trim($value['coupon_code']) != '' && !empty($value['coupon_code'])) {
                        $data[$value['start']]['coupon_code'][] = $value['coupon_code'];
                    }
                    if (intval($value['tip_amount']) != '' && intval($value['tip_amount']) != 0) {
                        $data[$value['start']]['tip_amount'][] = $value['tip_amount'];
                    }
                }
            }

            $dt = array();
            foreach ($data as $key => $val) {

                $dt[$key]['declined']['color'] = '';
                $dt[$key]['total_orders']['color'] = '';
                $dt[$key]['pending']['color'] = '';
                $dt[$key]['complete']['color'] = '';
                $dt[$key]['canceled']['color'] = '';
                $dt[$key]['schedulecanceled']['color'] = '';
                $dt[$key]['ondemandcanceled']['color'] = '';
                $dt[$key]['Express']['color'] = '';
                $dt[$key]['Deluxe']['color'] = '';
                $dt[$key]['Premium']['color'] = '';
                $dt[$key]['coupon_code']['color'] = '';
                $dt[$key]['tip_amount']['color'] = '';
                $dt[$key]['processing']['color'] = '';
                $dt[$key]['home']['count'] = '';
                $dt[$key]['work']['count'] = '';
                $dt[$key]['total_cars']['count'] = '';
                $dt[$key]['schedulecompleted']['color'] = '';
                $dt[$key]['ondemandcompleted']['color'] = '';
                $dt[$key]['addoncompleted']['color'] = '';
                $dt[$key]['scheduled_auto']['color'] = '#8b9d9e';
                $dt[$key]['scheduled_auto']['count'] = 0;
                $dt[$key]['ondemandautocanceled']['color'] = '#8b9d9e';
                $dt[$key]['ondemandautocanceled']['count'] = 0;
                $dt[$key]['schedulecanceled']['count'] = 0;
                $dt[$key]['schedulecanceled']['color'] = '#8b9d9e';
                $dt[$key]['zipblue']['color'] = '';
                $dt[$key]['zipblue']['count'] = 0;
                $dt[$key]['zipyellow']['color'] = '';
                $dt[$key]['zipyellow']['count'] = 0;
                $dt[$key]['zipred']['color'] = '';
                $dt[$key]['zipred']['count'] = 0;
                $dt[$key]['zippurple']['color'] = '';
                $dt[$key]['zippurple']['count'] = 0;
                $addonscompleted = 0;
                if (count($val['declined']) > 0) {
                    $dt[$key]['declined']['count'] = count($val['declined']);
                    $dt[$key]['declined']['color'] = '#eb1350';
                }
                if (count($val['total_cars']) > 0) {
                    $dt[$key]['total_cars']['count'] = 0;
                    foreach ($val['total_cars'] as $carcount) {
                        $dt[$key]['total_cars']['count'] += $carcount;
                    }
                }
                if (count($val['pending']) > 0 || count($val['processing']) > 0 || count($val['complete']) > 0 || count($val['canceled']) > 0 || count($val['scheduled_auto']) > 0 || count($val['ondemandautocanceled']) > 0) {
                    $total_orders = count($val['pending']) + count($val['processing']) + count($val['complete']); //+count($val['canceled'])+count($val['scheduled_auto'])+count($val['ondemandautocanceled'])+count($val['declined']);
                    $dt[$key]['total_orders']['count'] = $total_orders;
                    $dt[$key]['total_orders']['color'] = '#9c64b7';
                }
                if (count($val['pending']) > 0) {
                    $dt[$key]['pending']['count'] = count($val['pending']);
                    $dt[$key]['pending']['color'] = '#f6d235';
                }
                if (count($val['complete']) > 0) {
                    $dt[$key]['complete']['count'] = count($val['complete']);
                    $dt[$key]['complete']['color'] = '#14c266';
                }
                if (count($val['canceled']) > 0) {
                    $dt[$key]['canceled']['count'] = count($val['canceled']) + count($val['scheduled_auto']) + count($val['ondemandautocanceled']);
                    $dt[$key]['canceled']['color'] = '#8b9d9e';
                }
                if (count($val['scheduled_auto']) > 0) {
                    $dt[$key]['scheduled_auto']['count'] = count($val['scheduled_auto']);
                    $dt[$key]['scheduled_auto']['color'] = '#8b9d9e';
                }
                if (count($val['ondemandautocanceled']) > 0) {
                    $dt[$key]['ondemandautocanceled']['count'] = count($val['ondemandautocanceled']);
                    $dt[$key]['ondemandautocanceled']['color'] = '#8b9d9e';
                }
                if (count($val['schedulecanceled']) > 0) {
                    $dt[$key]['schedulecanceled']['count'] = count($val['schedulecanceled']);
                    $dt[$key]['schedulecanceled']['color'] = '#8b9d9e';
                }
                if (count($val['ondemandcanceled']) > 0) {
                    $dt[$key]['ondemandcanceled']['count'] = count($val['ondemandcanceled']);
                    $dt[$key]['ondemandcanceled']['color'] = '#8b9d9e';
                }
                if (count($val['processing']) > 0) {
                    $dt[$key]['processing']['count'] = count($val['processing']);
                    $dt[$key]['processing']['color'] = '#f6c235';
                }
                if (count($val['Express']) > 0) {
                    $dt[$key]['Express']['count'] = count($val['Express']);
                    $dt[$key]['Express']['color'] = '#2490d7';
                }
                if (count($val['Deluxe']) > 0) {
                    $dt[$key]['Deluxe']['count'] = count($val['Deluxe']);
                    $dt[$key]['Deluxe']['color'] = '#2490d7';
                }
                if (count($val['Premium']) > 0) {
                    $dt[$key]['Premium']['count'] = count($val['Premium']);
                    $dt[$key]['Premium']['color'] = '#2490d7';
                }
                if (count($val['coupon_code']) > 0) {
                    $dt[$key]['coupon_code']['count'] = count($val['coupon_code']);
                    $dt[$key]['coupon_code']['color'] = '#ec6858';
                }
                if (count($val['tip_amount']) > 0) {
                    $dt[$key]['tip_amount']['count'] = count($val['tip_amount']);
                    $dt[$key]['tip_amount']['color'] = '#f28fba';
                }

                if (count($val['schedulecompleted']) > 0) {
                    $dt[$key]['schedulecompleted']['count'] = count($val['schedulecompleted']);
                    $dt[$key]['schedulecompleted']['color'] = '#0000ff';
                }

                if (count($val['ondemandcompleted']) > 0) {
                    $dt[$key]['ondemandcompleted']['count'] = count($val['ondemandcompleted']);
                    $dt[$key]['ondemandcompleted']['color'] = '#008080';
                }

                if (count($val['zip_blue']) > 0) {
                    $dt[$key]['zipblue']['count'] = count($val['zip_blue']);
                    $dt[$key]['zipblue']['color'] = '#63dffb';
                }

                if (count($val['zip_yellow']) > 0) {
                    $dt[$key]['zipyellow']['count'] = count($val['zip_yellow']);
                    $dt[$key]['zipyellow']['color'] = '#d8c200';
                }

                if (count($val['zip_red']) > 0) {
                    $dt[$key]['zipred']['count'] = count($val['zip_red']);
                    $dt[$key]['zipred']['color'] = '#FF0000';
                }

                if (count($val['zip_purple']) > 0) {
                    $dt[$key]['zippurple']['count'] = count($val['zip_purple']);
                    $dt[$key]['zippurple']['color'] = '#800080';
                }


                if (count($val['pet_hair_vehicles']) > 0) {
                    foreach ($val['pet_hair_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['lifted_vehicles']) > 0) {
                    foreach ($val['lifted_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['exthandwax_vehicles']) > 0) {
                    foreach ($val['exthandwax_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['extplasticdressing_vehicles']) > 0) {
                    foreach ($val['extplasticdressing_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['extclaybar_vehicles']) > 0) {
                    foreach ($val['extclaybar_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['waterspotremove_vehicles']) > 0) {
                    foreach ($val['waterspotremove_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['upholstery_vehicles']) > 0) {
                    foreach ($val['upholstery_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['floormat_vehicles']) > 0) {
                    foreach ($val['floormat_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if ($addonscompleted > 0) {
                    $dt[$key]['addoncompleted']['count'] = $addonscompleted;
                    $dt[$key]['addoncompleted']['color'] = '#87CEFA';
                }

                if (count($val['home']) > 0) {
                    $dt[$key]['home']['count'] = count($val['home']);
                }
                if (count($val['work']) > 0) {
                    $dt[$key]['work']['count'] = count($val['work']);
                }

                if (count($val['new_customer']) > 0) {
                    $dt[$key]['new_customer']['color'] = '#3fcfb6';
                    $dt[$key]['new_customer']['count'] = count($val['new_customer']);
                } else {
                    $dt[$key]['new_customer']['color'] = '';
                    $dt[$key]['new_customer']['count'] = '';
                }

                /* if(count($val['tip_amount'])>0){
                  $dt[$key]['tip_amount']['color']= '#cc0066';
                  $dt[$key]['tip_amount']['count']= 0;
                  foreach($val['tip_amount'] as $amount){
                  $dt[$key]['tip_amount']['count'] += $amount;
                  }
                  } */
            }
            /* print_r($dt);die; */
            $ordersdetails['order'] = $dt;
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        } else {
            $ordersdetails['order'] = array('empty' => 'yes');
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        }
    }

    public function Actionorder_schedule_appOld() {

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
        /* Checking for post(month) parameters */
        $order_month = '';
        if (!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))) {
            $last_month = Yii::app()->request->getParam('start');
            $curr_month = Yii::app()->request->getParam('end');
            $order_month = " AND ( DATE_FORMAT(a.order_for,'%Y-%m')>= :last_month AND DATE_FORMAT(a.order_for,'%Y-%m')<= :curr_month) ";
        }
        /* Post END */

        /* web orderds */
        $total_order = Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "'")->queryAll();

        $count = $total_order[0]['countid'];

        $customers_order = Yii::app()->db->createCommand("SELECT a.id, a.car_list, a.package_list, a.coupon_code, a.tip_amount, a.status, a.schedule_date, a.created_date, a.order_for, a.address_type, a.zipcode, a.failed_transaction_id, a.wash_request_position, a.pet_hair_vehicles, a.lifted_vehicles, a.exthandwax_vehicles, a.extplasticdressing_vehicles, a.extclaybar_vehicles, a.waterspotremove_vehicles, a.upholstery_vehicles, a.floormat_vehicles, a.is_scheduled,b.total_wash, a.customer_id FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "' AND a.status != 7 $order_month")
                //$customers_order = Yii::app()->db->createCommand("SELECT a.id, a.car_list, a.package_list, a.coupon_code, a.tip_amount, a.status, a.schedule_date, a.created_date, a.order_for, a.address_type, a.zipcode, a.failed_transaction_id, a.wash_request_position, a.pet_hair_vehicles, a.lifted_vehicles, a.exthandwax_vehicles, a.extplasticdressing_vehicles, a.extclaybar_vehicles, a.waterspotremove_vehicles, a.upholstery_vehicles, a.floormat_vehicles, a.is_scheduled,b.total_wash, a.customer_id FROM washing_requests a  JOIN customers b ON a.customer_id = b.id  JOIN agents c ON a.agent_id = c.id WHERE b.hours_opt_check = 1 AND a.wash_request_position='" . APP_ENV . "' AND a.status != 7 $order_month")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();
        //print_r($customers_order);
        /* END */
        if (!empty($customers_order)) {
            $check_auto_canceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'scheduleauto-canceled'")->queryAll();
            $scheduleautoArr = array();
            if (count($check_auto_canceled) > 0) {
                foreach ($check_auto_canceled as $key => $value) {
                    $scheduleautoArr[] = $value['wash_request_id'];
                }
            }
            $ondemandauto_canceled = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE action = 'ondemandautocancel'")->queryAll();
            $ondemandautocancelArr = array();
            if (count($ondemandauto_canceled) > 0) {
                foreach ($ondemandauto_canceled as $key => $value) {
                    $ondemandautocancelArr[] = $value['wash_request_id'];
                }
            }

            $get_befor_count = Yii::app()->db->createCommand("SELECT customer_id, order_for FROM washing_requests WHERE status = 4 GROUP BY customer_id ORDER BY id ASC")->queryAll();
            $ArrCustomers_date = array();
            if (count($get_befor_count) > 0) {
                foreach ($get_befor_count as $key => $value) {
                    $ArrCustomers_date[$value['customer_id']] = date('Y-m-d', strtotime($value['order_for']));
                }
            }

            foreach ($customers_order as $orderbycustomer) {

                //$counttype = array_count_values($package_list_explode);
                $orderstatus = $orderbycustomer['status'];
                $orderid = $orderbycustomer['id'];
                $address_type = $orderbycustomer['address_type'];
                $created_date = $orderbycustomer['order_for'];
                $wash_request_position = $orderbycustomer['wash_request_position'];
                $color = '';
                $check_auto_canceled = $ondemandautocanceled = 0;
                if (in_array($orderbycustomer['id'], $scheduleautoArr)) {
                    $check_auto_canceled = 1;
                }
                if (in_array($orderbycustomer['id'], $ondemandautocancelArr)) {
                    $ondemandautocanceled = 1;
                }
                $zipcolor = '';

                if ($orderbycustomer['zipcode']) {
                    $coveragezipcheck = CoverageAreaCodes::model()->findByAttributes(array('zipcode' => $orderbycustomer['zipcode']));

                    if (count($coveragezipcheck)) {
                        $zipcolor = $coveragezipcheck->zip_color;
                    }
                }

                if ($orderstatus == 4) {
                    $order_of_status = 'Complete';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($check_auto_canceled == 1 && $orderbycustomer['is_scheduled'] == 1) {
                    $order_of_status = 'Scheduled-auto';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($ondemandautocanceled == 1 && $orderbycustomer['is_scheduled'] == 0) {
                    $order_of_status = 'ondemandautocanceled';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($orderstatus == 0) {
                    $order_of_status = 'Pending';
                    $time = $orderbycustomer['order_for'];
                    $currentdate = date("Y-m-d h:i:s");
                    $to_time = strtotime($time);
                    $from_time = strtotime($currentdate);
                    $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                    $color = '#FF3B30';
                } elseif ($orderstatus >= 1 && $orderstatus <= 3) {
                    $order_of_status = 'Processing';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#EF9047';
                } elseif (($orderstatus == 5) || ($orderstatus == 6)) {
                    $order_of_status = 'Canceled';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#AAAAAA';
                }

                if ($orderbycustomer['failed_transaction_id']) {
                    $order_of_status = 'Declined';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#cc0066';
                }


                $key = 'order_' . $count . '_' . $orderid;
                $json = array();
                $json['title'] = $order_of_status;
                $json['orderid'] = $orderid;
                $json['time'] = $totalminutes;
                $json['address_type'] = $address_type;
                $json['start'] = date('Y-m-d', strtotime($created_date));
                $orderbycustomer['total_wash'] = 1;
                if ($ArrCustomers_date[$orderbycustomer['customer_id']] == date('Y-m-d', strtotime($created_date))) {
                    $orderbycustomer['total_wash'] = 0;
                }
                if ($wash_request_position == APP_ENV) {
                    $orderview[] = array(
                        "start" => date('Y-m-d', strtotime($created_date)),
                        "title" => $order_of_status,
                        "color" => $color,
                        "address_type" => $address_type,
                        "car_list" => $orderbycustomer['car_list'],
                        "package_list" => $orderbycustomer['package_list'],
                        "coupon_code" => $orderbycustomer['coupon_code'],
                        "tip_amount" => $orderbycustomer['tip_amount'],
                        "is_scheduled" => $orderbycustomer['is_scheduled'],
                        "pet_hair_vehicles" => $orderbycustomer['pet_hair_vehicles'],
                        "lifted_vehicles" => $orderbycustomer['lifted_vehicles'],
                        "exthandwax_vehicles" => $orderbycustomer['exthandwax_vehicles'],
                        "extplasticdressing_vehicles" => $orderbycustomer['extplasticdressing_vehicles'],
                        "extclaybar_vehicles" => $orderbycustomer['extclaybar_vehicles'],
                        "waterspotremove_vehicles" => $orderbycustomer['waterspotremove_vehicles'],
                        "upholstery_vehicles" => $orderbycustomer['upholstery_vehicles'],
                        "floormat_vehicles" => $orderbycustomer['floormat_vehicles'],
                        "total_wash" => $orderbycustomer['total_wash'],
                        "zip_color" => $zipcolor
                    );
                }
            }
            $data = $ArrCustemer = array();
            foreach ($orderview as $key => $value) {

                /* $packages = explode(",", $value['package_list']);
                  if(count($packages)>0){
                  foreach ($packages as $package) {
                  $data[$value['start']][trim($package)][] = 1;
                  }
                  } */

                if (($value['total_wash'] == 0)) {
                    if (!in_array($value['customer_id'], $ArrCustemer)) {
                        $data[$value['start']]['new_customer'][] = 1;
                        $ArrCustemer[] = $value['customer_id'];
                    }
                }

                if ((($value['zip_color'] == '') || ($value['zip_color'] == 'blue')) && ($value['title'] != 'Canceled')) {
                    $data[$value['start']]['zip_blue'][] = 1;
                }

                if (($value['zip_color'] == 'yellow') && ($value['title'] != 'Canceled')) {
                    $data[$value['start']]['zip_yellow'][] = 1;
                }

                if (($value['zip_color'] == 'red') && ($value['title'] != 'Canceled')) {
                    $data[$value['start']]['zip_red'][] = 1;
                }

                if (($value['zip_color'] == 'purple') && ($value['title'] != 'Canceled')) {
                    $data[$value['start']]['zip_purple'][] = 1;
                }

                if ($value['address_type'] == 'Home') {
                    $data[$value['start']]['home'][] = $value['address_type'];
                }
                if ($value['address_type'] == 'Work') {
                    $data[$value['start']]['work'][] = $value['address_type'];
                }
                if ($value['title'] == 'Complete') {
                    $data[$value['start']]['complete'][] = $value['title'];
                    if ($value['is_scheduled'] == 1)
                        $data[$value['start']]['schedulecompleted'][] = 'schedulecompleted';
                    else
                        $data[$value['start']]['ondemandcompleted'][] = 'ondemandcompleted';

                    if (trim($value['pet_hair_vehicles']))
                        $data[$value['start']]['pet_hair_vehicles'][] = count(explode(",", $value['pet_hair_vehicles']));
                    else
                        $data[$value['start']]['pet_hair_vehicles'][] = 0;

                    if (trim($value['lifted_vehicles']))
                        $data[$value['start']]['lifted_vehicles'][] = count(explode(",", $value['lifted_vehicles']));
                    else
                        $data[$value['start']]['lifted_vehicles'][] = 0;

                    if (trim($value['exthandwax_vehicles']))
                        $data[$value['start']]['exthandwax_vehicles'][] = count(explode(",", $value['exthandwax_vehicles']));
                    else
                        $data[$value['start']]['exthandwax_vehicles'][] = 0;

                    if (trim($value['extplasticdressing_vehicles']))
                        $data[$value['start']]['extplasticdressing_vehicles'][] = count(explode(",", $value['extplasticdressing_vehicles']));
                    else
                        $data[$value['start']]['extplasticdressing_vehicles'][] = 0;

                    if (trim($value['extclaybar_vehicles']))
                        $data[$value['start']]['extclaybar_vehicles'][] = count(explode(",", $value['extclaybar_vehicles']));
                    else
                        $data[$value['start']]['extclaybar_vehicles'][] = 0;

                    if (trim($value['waterspotremove_vehicles']))
                        $data[$value['start']]['waterspotremove_vehicles'][] = count(explode(",", $value['waterspotremove_vehicles']));
                    else
                        $data[$value['start']]['waterspotremove_vehicles'][] = 0;

                    if (trim($value['upholstery_vehicles']))
                        $data[$value['start']]['upholstery_vehicles'][] = count(explode(",", $value['upholstery_vehicles']));
                    else
                        $data[$value['start']]['upholstery_vehicles'][] = 0;

                    if (trim($value['floormat_vehicles']))
                        $data[$value['start']]['floormat_vehicles'][] = count(explode(",", $value['floormat_vehicles']));
                    else
                        $data[$value['start']]['floormat_vehicles'][] = 0;
                }

                if ($value['title'] == 'Pending') {
                    $data[$value['start']]['pending'][] = $value['title'];
                }
                if ($value['title'] == 'Scheduled-auto') {
                    $data[$value['start']]['scheduled_auto'][] = $value['title'];
                }
                if ($value['title'] == 'ondemandautocanceled') {
                    $data[$value['start']]['ondemandautocanceled'][] = $value['title'];
                }
                if ($value['title'] == 'Processing') {
                    $data[$value['start']]['processing'][] = $value['title'];
                }
                if ($value['title'] == 'Canceled') {
                    $data[$value['start']]['canceled'][] = $value['title'];
                    if ($value['is_scheduled'] == 1)
                        $data[$value['start']]['schedulecanceled'][] = $value['title'];
                    else
                        $data[$value['start']]['ondemandcanceled'][] = $value['title'];
                }

                if ($value['title'] == 'Declined') {
                    $data[$value['start']]['declined'][] = $value['title'];
                }

                if (($value['title'] == 'Pending') || ($value['title'] == 'Processing') || ($value['title'] == 'Complete')) {
                    $data[$value['start']]['total_cars'][] = count(explode(",", $value['car_list']));

                    $packages = explode(",", $value['package_list']);
                    if (count($packages) > 0) {
                        foreach ($packages as $package) {
                            $data[$value['start']][trim($package)][] = 1;
                        }
                    }

                    if (trim($value['coupon_code']) != '' && !empty($value['coupon_code'])) {
                        $data[$value['start']]['coupon_code'][] = $value['coupon_code'];
                    }
                    if (intval($value['tip_amount']) != '' && intval($value['tip_amount']) != 0) {
                        $data[$value['start']]['tip_amount'][] = $value['tip_amount'];
                    }
                }
            }
            $dt = array();
            foreach ($data as $key => $val) {

                $dt[$key]['declined']['color'] = '';
                $dt[$key]['total_orders']['color'] = '';
                $dt[$key]['pending']['color'] = '';
                $dt[$key]['complete']['color'] = '';
                $dt[$key]['canceled']['color'] = '';
                $dt[$key]['schedulecanceled']['color'] = '';
                $dt[$key]['ondemandcanceled']['color'] = '';
                $dt[$key]['Express']['color'] = '';
                $dt[$key]['Deluxe']['color'] = '';
                $dt[$key]['Premium']['color'] = '';
                $dt[$key]['coupon_code']['color'] = '';
                $dt[$key]['tip_amount']['color'] = '';
                $dt[$key]['processing']['color'] = '';
                $dt[$key]['home']['count'] = '';
                $dt[$key]['work']['count'] = '';
                $dt[$key]['total_cars']['count'] = '';
                $dt[$key]['schedulecompleted']['color'] = '';
                $dt[$key]['ondemandcompleted']['color'] = '';
                $dt[$key]['addoncompleted']['color'] = '';
                $dt[$key]['scheduled_auto']['color'] = '#8b9d9e';
                $dt[$key]['scheduled_auto']['count'] = 0;
                $dt[$key]['ondemandautocanceled']['color'] = '#8b9d9e';
                $dt[$key]['ondemandautocanceled']['count'] = 0;
                $dt[$key]['schedulecanceled']['count'] = 0;
                $dt[$key]['schedulecanceled']['color'] = '#8b9d9e';
                $dt[$key]['zipblue']['color'] = '';
                $dt[$key]['zipblue']['count'] = 0;
                $dt[$key]['zipyellow']['color'] = '';
                $dt[$key]['zipyellow']['count'] = 0;
                $dt[$key]['zipred']['color'] = '';
                $dt[$key]['zipred']['count'] = 0;
                $dt[$key]['zippurple']['color'] = '';
                $dt[$key]['zippurple']['count'] = 0;
                $addonscompleted = 0;
                if (count($val['declined']) > 0) {
                    $dt[$key]['declined']['count'] = count($val['declined']);
                    $dt[$key]['declined']['color'] = '#eb1350';
                }
                if (count($val['total_cars']) > 0) {
                    $dt[$key]['total_cars']['count'] = 0;
                    foreach ($val['total_cars'] as $carcount) {
                        $dt[$key]['total_cars']['count'] += $carcount;
                    }
                }
                if (count($val['pending']) > 0 || count($val['processing']) > 0 || count($val['complete']) > 0 || count($val['canceled']) > 0 || count($val['scheduled_auto']) > 0 || count($val['ondemandautocanceled']) > 0) {
                    $total_orders = count($val['pending']) + count($val['processing']) + count($val['complete']); //+count($val['canceled'])+count($val['scheduled_auto'])+count($val['ondemandautocanceled'])+count($val['declined']);
                    $dt[$key]['total_orders']['count'] = $total_orders;
                    $dt[$key]['total_orders']['color'] = '#9c64b7';
                }
                if (count($val['pending']) > 0) {
                    $dt[$key]['pending']['count'] = count($val['pending']);
                    $dt[$key]['pending']['color'] = '#f6d235';
                }
                if (count($val['complete']) > 0) {
                    $dt[$key]['complete']['count'] = count($val['complete']);
                    $dt[$key]['complete']['color'] = '#14c266';
                }
                if (count($val['canceled']) > 0) {
                    $dt[$key]['canceled']['count'] = count($val['canceled']) + count($val['scheduled_auto']) + count($val['ondemandautocanceled']);
                    $dt[$key]['canceled']['color'] = '#8b9d9e';
                }
                if (count($val['scheduled_auto']) > 0) {
                    $dt[$key]['scheduled_auto']['count'] = count($val['scheduled_auto']);
                    $dt[$key]['scheduled_auto']['color'] = '#8b9d9e';
                }
                if (count($val['ondemandautocanceled']) > 0) {
                    $dt[$key]['ondemandautocanceled']['count'] = count($val['ondemandautocanceled']);
                    $dt[$key]['ondemandautocanceled']['color'] = '#8b9d9e';
                }
                if (count($val['schedulecanceled']) > 0) {
                    $dt[$key]['schedulecanceled']['count'] = count($val['schedulecanceled']);
                    $dt[$key]['schedulecanceled']['color'] = '#8b9d9e';
                }
                if (count($val['ondemandcanceled']) > 0) {
                    $dt[$key]['ondemandcanceled']['count'] = count($val['ondemandcanceled']);
                    $dt[$key]['ondemandcanceled']['color'] = '#8b9d9e';
                }
                if (count($val['processing']) > 0) {
                    $dt[$key]['processing']['count'] = count($val['processing']);
                    $dt[$key]['processing']['color'] = '#f6c235';
                }
                if (count($val['Express']) > 0) {
                    $dt[$key]['Express']['count'] = count($val['Express']);
                    $dt[$key]['Express']['color'] = '#2490d7';
                }
                if (count($val['Deluxe']) > 0) {
                    $dt[$key]['Deluxe']['count'] = count($val['Deluxe']);
                    $dt[$key]['Deluxe']['color'] = '#2490d7';
                }
                if (count($val['Premium']) > 0) {
                    $dt[$key]['Premium']['count'] = count($val['Premium']);
                    $dt[$key]['Premium']['color'] = '#2490d7';
                }
                if (count($val['coupon_code']) > 0) {
                    $dt[$key]['coupon_code']['count'] = count($val['coupon_code']);
                    $dt[$key]['coupon_code']['color'] = '#ec6858';
                }
                if (count($val['tip_amount']) > 0) {
                    $dt[$key]['tip_amount']['count'] = count($val['tip_amount']);
                    $dt[$key]['tip_amount']['color'] = '#f28fba';
                }

                if (count($val['schedulecompleted']) > 0) {
                    $dt[$key]['schedulecompleted']['count'] = count($val['schedulecompleted']);
                    $dt[$key]['schedulecompleted']['color'] = '#0000ff';
                }

                if (count($val['ondemandcompleted']) > 0) {
                    $dt[$key]['ondemandcompleted']['count'] = count($val['ondemandcompleted']);
                    $dt[$key]['ondemandcompleted']['color'] = '#008080';
                }

                if (count($val['zip_blue']) > 0) {
                    $dt[$key]['zipblue']['count'] = count($val['zip_blue']);
                    $dt[$key]['zipblue']['color'] = '#63dffb';
                }

                if (count($val['zip_yellow']) > 0) {
                    $dt[$key]['zipyellow']['count'] = count($val['zip_yellow']);
                    $dt[$key]['zipyellow']['color'] = '#d8c200';
                }

                if (count($val['zip_red']) > 0) {
                    $dt[$key]['zipred']['count'] = count($val['zip_red']);
                    $dt[$key]['zipred']['color'] = '#FF0000';
                }

                if (count($val['zip_purple']) > 0) {
                    $dt[$key]['zippurple']['count'] = count($val['zip_purple']);
                    $dt[$key]['zippurple']['color'] = '#800080';
                }


                if (count($val['pet_hair_vehicles']) > 0) {
                    foreach ($val['pet_hair_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['lifted_vehicles']) > 0) {
                    foreach ($val['lifted_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['exthandwax_vehicles']) > 0) {
                    foreach ($val['exthandwax_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['extplasticdressing_vehicles']) > 0) {
                    foreach ($val['extplasticdressing_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['extclaybar_vehicles']) > 0) {
                    foreach ($val['extclaybar_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['waterspotremove_vehicles']) > 0) {
                    foreach ($val['waterspotremove_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['upholstery_vehicles']) > 0) {
                    foreach ($val['upholstery_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if (count($val['floormat_vehicles']) > 0) {
                    foreach ($val['floormat_vehicles'] as $addoncount) {
                        $addonscompleted += $addoncount;
                    }
                }

                if ($addonscompleted > 0) {
                    $dt[$key]['addoncompleted']['count'] = $addonscompleted;
                    $dt[$key]['addoncompleted']['color'] = '#87CEFA';
                }

                if (count($val['home']) > 0) {
                    $dt[$key]['home']['count'] = count($val['home']);
                }
                if (count($val['work']) > 0) {
                    $dt[$key]['work']['count'] = count($val['work']);
                }

                if (count($val['new_customer']) > 0) {
                    $dt[$key]['new_customer']['color'] = '#3fcfb6';
                    $dt[$key]['new_customer']['count'] = count($val['new_customer']);
                } else {
                    $dt[$key]['new_customer']['color'] = '';
                    $dt[$key]['new_customer']['count'] = '';
                }

                /* if(count($val['tip_amount'])>0){
                  $dt[$key]['tip_amount']['color']= '#cc0066';
                  $dt[$key]['tip_amount']['count']= 0;
                  foreach($val['tip_amount'] as $amount){
                  $dt[$key]['tip_amount']['count'] += $amount;
                  }
                  } */
            }
            /* print_r($dt);die; */
            $ordersdetails['order'] = $dt;
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        } else {
            $ordersdetails['order'] = array('empty' => 'yes');
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        }
    }

    /* SCHEDULE ORDER CALENDAR */

    public function Actionorder_schedule_ordSchedule() {

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
        /* Checking for post(month) parameters */
        $order_month = '';
        if (!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))) {
            $last_month = Yii::app()->request->getParam('start');
            $curr_month = Yii::app()->request->getParam('end');
            $order_month = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= :last_month AND DATE_FORMAT(schedule_date,'%Y-%m')<= :curr_month)";
        }
        /* Post END */
        $customers_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND (status !=5 && status !=6)$order_month ORDER BY schedule_date DESC")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();

        /* END */

        if (!empty($customers_order)) {
            foreach ($customers_order as $orderbycustomer) {
                //$counttype = array_count_values($package_list_explode);
                $orderstatus = $orderbycustomer['status'];
                $orderid = $orderbycustomer['id'];
                $schedule_date = $orderbycustomer['schedule_date'];
                $address_type = $orderbycustomer['address_type'];
                $wash_request_position = $orderbycustomer['wash_request_position'];

                $color = '';
                $order_of_status = '';
                if ($orderstatus == 4) {
                    $order_of_status = 'Complete';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                }
                if ($orderstatus == 0) {
                    $order_of_status = 'Pending';
                    $time = $orderbycustomer['schedule_date'];
                    $currentdate = date("Y-m-d h:i:s");
                    $to_time = strtotime($time);
                    $from_time = strtotime($currentdate);
                    $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                    $color = '#FF3B30';
                }
                if ($orderstatus >= 1 && $orderstatus <= 3) {
                    $order_of_status = 'Processing';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#EF9047';
                }
                /* elseif($orderstatus == 5 || $orderstatus == 6){
                  $order_of_status = 'Canceled';
                  $totalminutes = 'N/A';
                  $near_agent = '';
                  $color = '#EF9047';
                  } */
                $key = 'order_' . $count . '_' . $orderid;
                $json = array();
                $json['title'] = $order_of_status;
                $json['orderid'] = $orderid;
                $json['time'] = $totalminutes;
                $json['start'] = date('Y-m-d', strtotime($schedule_date));

                if ($orderstatus != 5 && $orderstatus != 6 && $wash_request_position == APP_ENV) {
                    $orderview[] = array(
                        "start" => date('Y-m-d', strtotime($schedule_date)),
                        "title" => $order_of_status,
                        "color" => $color,
                        "address_type" => $address_type,
                        "orderstatus" => $orderstatus
                    );
                }
            }

            $data = array();
            foreach ($orderview as $key => $value) {
                if ($value['address_type'] == 'Home') {
                    $data[$value['start']]['home'][] = $value['address_type'];
                }
                if ($value['address_type'] == 'Work') {
                    $data[$value['start']]['work'][] = $value['address_type'];
                }
                if ($value['title'] == 'Complete') {
                    $data[$value['start']]['complete'][] = $value['title'];
                }
                if ($value['title'] == 'Pending') {
                    $data[$value['start']]['pending'][] = $value['title'];
                }
                if ($value['title'] == 'Processing') {
                    $data[$value['start']]['processing'][] = $value['title'];
                }
            }

            $dt = array();
            foreach ($data as $key => $val) {
                $dt[$key]['complete']['color'] = '';
                $dt[$key]['pending']['color'] = '';
                $dt[$key]['processing']['color'] = '';
                $dt[$key]['home']['count'] = '';
                $dt[$key]['work']['count'] = '';
                if (count($val['complete']) > 0) {
                    $dt[$key]['complete']['count'] = count($val['complete']);
                    $dt[$key]['complete']['color'] = '#30A0FF';
                }
                if (count($val['pending']) > 0) {
                    $dt[$key]['pending']['count'] = count($val['pending']);
                    $dt[$key]['pending']['color'] = '#FF3B30';
                }
                if (count($val['processing']) > 0) {
                    $dt[$key]['processing']['count'] = count($val['processing']);
                    $dt[$key]['processing']['color'] = '#EF9047';
                }
                if (count($val['home']) > 0) {
                    $dt[$key]['home']['count'] = count($val['home']);
                }
                if (count($val['work']) > 0) {
                    $dt[$key]['work']['count'] = count($val['work']);
                }
            }
            $ordersdetails['order'] = $dt;
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        } else {
            $ordersdetails['order'] = array('empty' => 'yes');
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        }
    }

    /*
     * FUNCTION FOR ALL ORDER COUNT WHICH ARE SHOWING ON CALENDAR
     * ONCE CLICK ON ALL ORDER BUTTON.
     */

    public function Actionorder_schedule_all_orders() {

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
        /* Checking for post(month) parameters */
        $order_month = '';
        if (!empty(Yii::app()->request->getParam('start')) && !empty(Yii::app()->request->getParam('end'))) {
            $last_month = Yii::app()->request->getParam('start');
            $curr_month = Yii::app()->request->getParam('end');
            $order_month = " AND ( DATE_FORMAT(a.schedule_date,'%Y-%m')>= :last_month AND DATE_FORMAT(a.schedule_date,'%Y-%m')<= :curr_month)";
            $order_month_phone = " WHERE ( DATE_FORMAT(schedule_date,'%Y-%m')>= :last_month AND DATE_FORMAT(schedule_date,'%Y-%m')<= :curr_month)";
            $order_month_sch = " AND ( DATE_FORMAT(schedule_date,'%Y-%m')>= :last_month AND DATE_FORMAT(schedule_date,'%Y-%m')<= :curr_month)";
        }
        /* Post END */



        /* web orderds */
        $total_order = Yii::app()->db->createCommand("SELECT COUNT(a.id) as countid FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id AND a.status NOT IN (5,6)")->queryAll();

        $count = $total_order[0]['countid'];

        $customers_order = Yii::app()->db->createCommand("SELECT a.id, a.status, a.schedule_date FROM washing_requests a LEFT JOIN customers b ON a.customer_id = b.id LEFT JOIN agents c ON a.agent_id = c.id WHERE a.status NOT IN (5,6)$order_month")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();

        /* END */


        /* Phone Orders */
        $phone_orders = array();
        $orders_exists = Yii::app()->db->createCommand("SELECT * FROM phone_orders$order_month_phone ORDER BY schedule_date DESC")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();
        if (count($orders_exists) > 0) {
            $result = 'true';
            $response = 'all orders';
            foreach ($orders_exists as $ind => $order) {

                $ph_order_id = $order['id'];
                $ph_checklist = $order['checklist'];
                /* $ph_created = $order['schedule_date']; */
                $ph_created = $order['schedule_date'];
                $checklist_arr = explode("|", $order['checklist']);
                if (!$order['checklist']) {
                    $order_of_status = 'Pending';
                    $color = '#FF3B30';
                } else if (count($checklist_arr) == 8) {
                    $order_of_status = 'Complete';
                    $color = '#30A0FF';
                } else {
                    $order_of_status = 'Processing';
                    $color = '#EF9047';
                }
                $phone_orders[] = array(
                    "start" => date('Y-m-d', strtotime($ph_created)),
                    "title" => $order_of_status,
                    "color" => $color
                );
            }
        }
        /* END */
        /* SCHEDULE ORDERS */
        $schedule_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND (status !=5 && status !=6) $order_month_sch ORDER BY schedule_date DESC")
                ->bindValue(':last_month', $last_month, PDO::PARAM_STR)
                ->bindValue(':curr_month', $curr_month, PDO::PARAM_STR)
                ->queryAll();

        if (!empty($schedule_order)) {
            foreach ($schedule_order as $orderbyschdule) {
                //$counttype = array_count_values($package_list_explode);
                $orderstatus = $orderbyschdule['status'];
                $orderid = $orderbyschdule['id'];
                $created_date = $orderbyschdule['schedule_date'];
                $color = '';
                $orderstatus = "";
                if ($orderstatus == 4) {
                    $order_of_status = 'Complete';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($orderstatus == 0) {
                    $order_of_status = 'Pending';
                    $time = $orderbyschdule['schedule_date'];
                    $currentdate = date("Y-m-d h:i:s");
                    $to_time = strtotime($time);
                    $from_time = strtotime($currentdate);
                    $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                    $color = '#FF3B30';
                } elseif ($orderstatus >= 1 && $orderstatus <= 3) {
                    $order_of_status = 'Processing';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#EF9047';
                }/* elseif($orderstatus == 5 || $orderstatus == 6){
                  $order_of_status = 'Canceled';
                  $totalminutes = 'N/A';
                  $near_agent = '';
                  $color = '#EF9047';
                  } */
                $key = 'order_' . $count . '_' . $orderid;
                $json = array();
                $json['title'] = $order_of_status;
                $json['orderid'] = $orderid;
                $json['time'] = $totalminutes;
                $json['start'] = date('Y-m-d', strtotime($created_date));

                if ($orderstatus != 5 && $orderstatus != 6) {
                    $sch_orderview[] = array(
                        "start" => date('Y-m-d', strtotime($created_date)),
                        "title" => $order_of_status,
                        "color" => $color
                    );
                }
            }
        }
        /* END */

        $package_name = array();
        $car_id = array();
        if (!empty($customers_order)) {
            foreach ($customers_order as $orderbycustomer) {
                //$counttype = array_count_values($package_list_explode);
                $orderstatus = $orderbycustomer['status'];
                $orderid = $orderbycustomer['id'];
                $created_date = $orderbycustomer['schedule_date'];
                $color = '';

                if ($orderstatus == 4) {
                    $order_of_status = 'Complete';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#30A0FF';
                } elseif ($orderstatus == 0) {
                    $order_of_status = 'Pending';
                    $time = $orderbycustomer['schedule_date'] . " " . $orderbycustomer['schedule_time'];
                    $currentdate = date("Y-m-d h:i:s");
                    $to_time = strtotime($time);
                    $from_time = strtotime($currentdate);
                    $totalminutes = round(abs($to_time - $from_time) / 60, 2);
                    $color = '#FF3B30';
                } elseif ($orderstatus >= 1 && $orderstatus <= 3) {
                    $order_of_status = 'Processing';
                    $totalminutes = 'N/A';
                    $near_agent = '';
                    $color = '#EF9047';
                }
                $key = 'order_' . $count . '_' . $orderid;
                $json = array();
                $json['title'] = $order_of_status;

                $json['orderid'] = $orderid;
                $json['time'] = $totalminutes;
                $json['start'] = date('Y-m-d', strtotime($created_date));


                $orderview[] = array(
                    "start" => date('Y-m-d', strtotime($created_date)),
                    "title" => $order_of_status,
                    "color" => $color
                );
            }
            /* Merge Both WEB & Phone Orders array */
            $orderview1 = array_merge($phone_orders, $orderview);



            /* Merging App, Phone, Schedule Orders array */
            $allorders = array_merge($sch_orderview, $orderview1);

            $data = array();
            /* foreach($orderview as $key=>$value){ */
            foreach ($allorders as $key => $value) {
                if ($value['title'] == 'Complete') {
                    $data[$value['start']]['complete'][] = $value['title'];
                }
                if ($value['title'] == 'Pending') {
                    $data[$value['start']]['pending'][] = $value['title'];
                }
                if ($value['title'] == 'Processing') {
                    $data[$value['start']]['processing'][] = $value['title'];
                }
            }
            $dt = array();
            foreach ($data as $key => $val) {
                $dt[$key]['complete']['color'] = '';
                $dt[$key]['pending']['color'] = '';
                $dt[$key]['processing']['color'] = '';
                $dt[$key]['home']['count'] = '';
                $dt[$key]['work']['count'] = '';
                if (count($val['complete']) > 0) {
                    $dt[$key]['complete']['count'] = count($val['complete']);
                    $dt[$key]['complete']['color'] = '#30A0FF';
                }
                if (count($val['pending']) > 0) {
                    $dt[$key]['pending']['count'] = count($val['pending']);
                    $dt[$key]['pending']['color'] = '#FF3B30';
                }
                if (count($val['processing']) > 0) {
                    $dt[$key]['processing']['count'] = count($val['processing']);
                    $dt[$key]['processing']['color'] = '#EF9047';
                }
            }
            $ordersdetails['order'] = $dt;
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        } else {
            $ordersdetails['order'] = array('empty' => 'yes');
            echo json_encode($ordersdetails, JSON_PRETTY_PRINT);

            exit;
        }
    }

    public function actiongetwashrequestbyid() {

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
        $response = 'Please provide wash request id';

        $id = Yii::app()->request->getParam('id');

        $order_details = array();

        if ((isset($id) && !empty($id))) {

            $order_exists = Washingrequests::model()->findByAttributes(array("id" => $id));
            if (!count($order_exists)) {
                $response = "Invalid order id";
            } else {
                $response = "Order details";
                $result = 'true';

                $order_det = Washingrequests::model()->findByPk($id);
                $vip_membership = '';

                $cust_exists = Customers::model()->findByAttributes(array("id" => $order_det->customer_id));
                if ($order_det->vip_coupon_code) {
                    $coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $order_det->vip_coupon_code));
                    $vip_membership = $coupon_check->package_name;
                }

                $order_details['id'] = $order_det->id;
                $order_details['agent_id'] = $order_det->agent_id;
                $order_details['customer_id'] = $order_det->customer_id;
                $order_details['name'] = $cust_exists->first_name . " " . $cust_exists->last_name;
                $order_details['car_list'] = $order_det->car_list;
                $order_details['package_list'] = $order_det->package_list;
                $order_details['address'] = $order_det->address;
                $order_details['address_type'] = $order_det->address_type;
                $order_details['latitude'] = $order_det->latitude;
                $order_details['longitude'] = $order_det->longitude;
                $order_details['payment_type'] = $order_det->payment_type;
                $order_details['nonce'] = $order_det->nonce;
                $order_details['transaction_id'] = $order_det->transaction_id;
                $order_details['second_transaction_id'] = $order_det->second_transaction_id;
                $order_details['washer_payment_transaction_id'] = $order_det->washer_payment_transaction_id;
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
                $order_details['company_discount'] = $order_det->company_discount;
                $order_details['vip_package'] = $vip_membership;
                $order_details['cancel_fee'] = $order_det->cancel_fee;
                $order_details['agent_cancel_fee'] = $order_det->washer_cancel_fee;
                $order_details['washer_penalty_fee'] = $order_det->washer_penalty_fee;
                $order_details['washer_arrival_notify'] = $order_det->washer_arrival_notify;
                $order_details['pet_hair_vehicles'] = $order_det->pet_hair_vehicles;
                $order_details['lifted_vehicles'] = $order_det->lifted_vehicles;
                $order_details['fifth_wash_vehicles'] = $order_det->fifth_wash_vehicles;
                $order_details['order_temp_assigned'] = $order_det->order_temp_assigned;
                $order_details['agent_reject_ids'] = $order_det->agent_reject_ids;
                $order_details['complete_order'] = $order_det->complete_order;
                $order_details['customer_wash_points'] = $order_det->customer_wash_points;
                $order_details['per_car_wash_points'] = $order_det->per_car_wash_points;
                if ($order_det->reschedule_time) {
                    $order_details['schedule_date'] = $order_det->reschedule_date;
                    $order_details['schedule_time'] = $order_det->reschedule_time;
                } else {
                    $order_details['schedule_date'] = $order_det->schedule_date;
                    $order_details['schedule_time'] = $order_det->schedule_time;
                }

                $order_details['is_scheduled'] = $order_det->is_scheduled;
                $order_details['scheduled_cars_info'] = $order_det->scheduled_cars_info;
                $order_details['schedule_total'] = $order_det->schedule_total;
                $order_details['schedule_total_ini'] = $order_det->schedule_total_ini;
                $order_details['schedule_total_vip'] = $order_det->schedule_total_vip;
                $order_details['schedule_company_total'] = $order_det->schedule_company_total;
                $order_details['schedule_company_total_vip'] = $order_det->schedule_company_total_vip;
                $order_details['schedule_agent_total'] = $order_det->schedule_agent_total;
                $order_details['checklist'] = $order_det->checklist;
                $order_details['tip_amount'] = $order_det->tip_amount;
                $order_details['notes'] = $order_det->notes;
                $order_details['escrow_status'] = $order_det->escrow_status;
                $order_details['is_admin_washpoint_processed'] = $order_det->is_admin_washpoint_processed;

                /* vehicals list */
                /* --------- Get total price ------------- */

                $total_cars = explode(",", $order_det->car_list);
                $total_packs = explode(",", $order_det->package_list);
                $pet_hair_arr = explode(",", $order_det->pet_hair_vehicles);
                $lifted_vehicles_arr = explode(",", $order_det->lifted_vehicles);
                $fifth_vehicles_arr = explode(",", $order_det->fifth_wash_vehicles);




                foreach ($total_cars as $carindex => $car) {

                    $vehicle_details = Vehicle::model()->findByAttributes(array("id" => $car));

                    $vehicle_inspect_details = Washinginspections::model()->findByAttributes(array("wash_request_id" => $id, "vehicle_id" => $car));
                    $inspect_img = '';
                    if (count($vehicle_inspect_details) > 0) {
                        $inspect_img = $vehicle_inspect_details->damage_pic;
                    }

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
                        $agent_total += $veh_price * .8;
                        $company_total += $veh_price * .2;
                        $safe_handle_fee = $washing_plan_express->handling_fee;
                        $company_total += $washing_plan_express->handling_fee;
                    }
                    if ($total_packs[$carindex] == 'Deluxe') {
                        $total += $delx_price;
                        $veh_price = $delx_price;
                        $agent_total += $veh_price * .8;
                        $company_total += $veh_price * .2;
                        $safe_handle_fee = $washing_plan_deluxe->handling_fee;
                        $company_total += $washing_plan_deluxe->handling_fee;
                    }
                    if ($total_packs[$carindex] == 'Premium') {
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

                    if (in_array($car, $pet_hair_arr)) {
                        $total += 5;
                        $total_pet_lift_fee += 5;



                        $agent_total += 5 * .8;
                        $company_total += 5 * .2;


                        $pet_hair = 5;
                    }

                    if (in_array($car, $lifted_vehicles_arr)) {
                        $total += 5;
                        $total_pet_lift_fee += 5;

                        $agent_total += 5 * .8;
                        $company_total += 5 * .2;

                        $lift_vehicle = 5;
                    }

                    if (in_array($car, $fifth_vehicles_arr)) {
                        //$total += 5;
                        //$total_pet_lift_fee += 5;
                        //$agent_total += round(5 * .8, 2);
                        $fifth_wash_disc = 5;
                    }

                    /* ----- pet hair / lift / fifth check end ------- */

                    $veh_price_agent = 0;
                    if ($total_packs[$carindex] == 'Premium') {
                        $veh_price_agent = round($veh_price * .75, 2);
                    } else {
                        $veh_price_agent = round($veh_price * .8, 2);
                    }

                    $vehicles[] = array(
                        'id' => $vehicle_details->id,
                        'vehicle_no' => $vehicle_details->vehicle_no,
                        'brand_name' => $vehicle_details->brand_name,
                        'model_name' => $vehicle_details->model_name,
                        'vehicle_image' => $vehicle_details->vehicle_image,
                        'vehicle_inspect_image' => $inspect_img,
                        'vehicle_type' => $vehicle_details->vehicle_type,
                        'vehicle_washing_package' => $total_packs[$carindex],
                        'vehicle_washing_price' => $veh_price,
                        'vehicle_washing_price_agent' => $veh_price_agent,
                        'safe_handling_fee' => $safe_handle_fee,
                        'pet_hair_fee' => $pet_hair,
                        'lifted_vehicle_fee' => $lift_vehicle,
                        'fifth_wash_discount' => $fifth_wash_disc
                    );
                }

                $order_details['vehicle'] = $vehicles;
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'order_details' => $order_details
        );

        echo json_encode($json);
    }

    public function actioncancelscheduleorder() {

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
        $response = 'please provide required parameters';

        $id = Yii::app()->request->getParam('id');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $name = Yii::app()->request->getParam('name');
        $email = Yii::app()->request->getParam('email');
        $address = Yii::app()->request->getParam('address');
        $address_type = Yii::app()->request->getParam('address_type');
        $fee = Yii::app()->request->getParam('fee');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $free_cancel = Yii::app()->request->getParam('free_cancel');
        $api_password = '';
        $api_password = Yii::app()->request->getParam('api_password');
        $company_cancel = 0;
        if (Yii::app()->request->getParam('company_cancel'))
            $company_cancel = Yii::app()->request->getParam('company_cancel');
        $cancel_price = 0;

        if ((isset($id) && !empty($id)) && (isset($customer_id) && !empty($customer_id)) && (isset($fee) && !empty($fee))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $id = $this->aes256cbc_crypt($id, 'd', AES256CBC_API_PASS);
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $result = 'true';
            $response = 'here';
            $order_exists = Washingrequests::model()->findByAttributes(array("id" => $id));
            $cust_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            $agent_det = Agents::model()->findByAttributes(array("id" => $order_exists->agent_id));
            if (!count($order_exists)) {
                $response = "Invalid order id";
            } else if (!count($cust_exists)) {
                $response = "Invalid customer id";
            } else {

                /* ------- kart details ----------- */

                $kartapiresult = $this->washingkart($id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);

                /* ------- kart details end ----------- */

                if ($order_exists->reschedule_time)
                    $scheduledatetime = $order_exists->reschedule_date . " " . $order_exists->reschedule_time;
                else
                    $scheduledatetime = $order_exists->schedule_date . " " . $order_exists->schedule_time;
                $min_diff = 0;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                if ($from_time > $to_time) {
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                }
                if (($min_diff <= 60) && (!Yii::app()->request->getParam('free_cancel')) && ($order_exists->agent_id)) {

                    $braintree_id = '';
                    $braintree_id = $cust_exists->braintree_id;

                    if ($cust_exists->client_position == 'real')
                        $Bresult = Yii::app()->braintree->getCustomerById_real($braintree_id);
                    else
                        $Bresult = Yii::app()->braintree->getCustomerById($braintree_id);
                    if (count($Bresult->paymentMethods)) {
                        $result = 'true';
                        $response = 'payment methods';
                        foreach ($Bresult->paymentMethods as $index => $paymethod) {

                            if (($order_exists->status > 1) && ($order_exists->status <= 3)) {
                                $request_data = ['merchantAccountId' => $agent_det->bt_submerchant_id, 'serviceFeeAmount' => "5.00", 'amount' => $fee, 'paymentMethodToken' => $paymethod->token];
                                if ($cust_exists->client_position == 'real')
                                    $cancelresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                                else
                                    $cancelresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                            }
                            else {
                                $request_data = ['amount' => $fee, 'paymentMethodToken' => $paymethod->token, 'customer' => ['firstName' => $cust_exists->first_name . " " . $cust_exists->last_name,], 'billing' => ['firstName' => $cust_exists->first_name . " " . $cust_exists->last_name]];

                                if ($cust_exists->client_position == 'real')
                                    $cancelresult = Yii::app()->braintree->sale_real($request_data);
                                else
                                    $cancelresult = Yii::app()->braintree->sale($request_data);
                            }



                            if (($cancelresult['success'] == 1)) {
                                /* if ($cust_exists->client_position == 'real')
                                  $cancelsettle = Yii::app()->braintree->submitforsettlement_real($cancelresult['transaction_id']);
                                  else
                                  $cancelsettle = Yii::app()->braintree->submitforsettlement($cancelresult['transaction_id']); */
                                $result = 'true';
                                $response = 'Order canceled';
                                $cancel_price = $fee;
                                if (($order_exists->status > 1) && ($order_exists->status <= 3))
                                    Washingrequests::model()->updateByPk($id, array('status' => 5, 'order_canceled_at' => date("Y-m-d H:i:s"), 'cancel_fee' => $fee, 'company_cancel' => $company_cancel, 'washer_cancel_fee' => $fee - 5, 'canceled_wash_transaction_id' => $cancelresult['transaction_id']));
                                else
                                    Washingrequests::model()->updateByPk($id, array('status' => 5, 'order_canceled_at' => date("Y-m-d H:i:s"), 'company_cancel' => $company_cancel, 'cancel_fee' => $fee, 'canceled_wash_transaction_id' => $cancelresult['transaction_id']));

                                if ($order_exists->transaction_id) {
                                    if ($order_exists->wash_request_position == 'real')
                                        $voidresult = Yii::app()->braintree->void_real($order_exists->transaction_id);
                                    else
                                        $voidresult = Yii::app()->braintree->void($order_exists->transaction_id);
                                }

                                $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $order_exists->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                                if (!count($is_cust_has_order)) {
                                    Customers::model()->updateByPk($order_exists->customer_id, array("is_first_wash" => 0));
                                }

                                $from = Vargas::Obj()->getAdminFromEmail();
                                $sched_date = '';
                                $sched_time = '';

                                if (!$order_exists->is_scheduled) {

                                    if (strtotime($order_exists->order_for) == strtotime(date('Y-m-d'))) {
                                        $sched_date = 'Today';
                                    } else {
                                        $sched_date = date('M d', strtotime($order_exists->order_for));
                                    }
                                    $sched_time = date('g:i A', strtotime($order_exists->order_for));
                                } else {

                                    if ($order_exists->reschedule_time) {
                                        if (strtotime($order_exists->reschedule_date) == strtotime(date('Y-m-d'))) {
                                            $sched_date = 'Today';
                                        } else {
                                            $sched_date = date('M d', strtotime($order_exists->reschedule_date));
                                        }
                                        $sched_time = $order_exists->reschedule_time;
                                    } else {
                                        if (strtotime($order_exists->schedule_date) == strtotime(date('Y-m-d'))) {
                                            $sched_date = 'Today';
                                        } else {
                                            $sched_date = date('M d', strtotime($order_exists->schedule_date));
                                        }
                                        $sched_time = $order_exists->schedule_time;
                                    }
                                }
                                $message = '';
                                $subject = 'Cancel Order Receipt - #0000' . $id;
                                //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                                $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>This order has been canceled</h2>";
                                if (!$order_exists->is_scheduled)
                                    $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>On-demand order for " . $sched_date . " @ " . $sched_time . "</p>";
                                else
                                    $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Scheduled order for " . $sched_date . " @ " . $sched_time . "</p>";
                                $message .= "<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $order_exists->address . "</p>";
                                $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>Client Name:</strong><br> " . $cust_exists->first_name . " " . $cust_exists->last_name . "</td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $id . "</td></tr>
					</table>";

                                $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";

                                foreach ($kartdata->vehicles as $ind => $vehicle) {

                                    $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$0.00</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                                    if ($vehicle->extclaybar_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->waterspotremove_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->upholstery_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->exthandwax_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->floormat_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";


                                    if ($vehicle->pet_hair_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->lifted_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (" . $order_exists->coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }


                                    if ($vehicle->fifth_wash_discount > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    $message .= "</table>

</td>
</tr>";
                                }


                                /* if($coupon_amount){
                                  $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
                                  <td style='padding-bottom: 15px;'>
                                  <p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
                                  </td>
                                  <td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-</p></td>
                                  </tr></table>";
                                  } */
                                $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 20px; margin: 0;'><strong>Cancellation Fee</strong></p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0; font-weight: bold;'>$" . number_format($fee, 2) . "</p></td>
							</tr></table>";

                                $message .= "</table>";

                                $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . number_format($fee, 2) . "</span></p></td></tr></table>";

                                $to = Vargas::Obj()->getAdminToEmail();

                                Vargas::Obj()->SendMail($cust_exists->email, "billing@devmobilewash.com", $message, $subject, 'mail-receipt');
                                Vargas::Obj()->SendMail($to, $cust_exists->email, $message, $subject, 'mail-receipt');

                                Washingrequests::model()->updateByPk($order_exists->id, array('is_order_receipt_sent' => 1));

                                if ((APP_ENV == 'real')) {

                                    $this->layout = "xmlLayout";

                                    //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                                    require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                                    $account_sid = TWILIO_SID;
                                    $auth_token = TWILIO_AUTH_TOKEN;
                                    $client = new Services_Twilio($account_sid, $auth_token);

                                    $message = "Order #" . $id . " has been canceled\r\nCustomer Name: " . $cust_exists->first_name . " " . $cust_exists->last_name . "\r\nPhone: " . $cust_exists->contact_number . "\r\nAddress: " . $order_exists->address . " (" . $order_exists->address_type . ")";
                                    $message2 = "Order #" . $id . " has been canceled";


                                    try {
                                        $sendmessage = $client->account->messages->create(array(
                                            'To' => '8183313631',
                                            'From' => '+13103128070',
                                            'Body' => $message,
                                        ));
                                    } catch (Services_Twilio_RestException $e) {
                                        //echo  $e;
                                    }

                                    try {
                                        $sendmessage = $client->account->messages->create(array(
                                            'To' => '3109999334',
                                            'From' => '+13103128070',
                                            'Body' => $message,
                                        ));
                                    } catch (Services_Twilio_RestException $e) {
                                        //echo  $e;
                                    }

                                    try {
                                        $sendmessage = $client->account->messages->create(array(
                                            'To' => '3103442534',
                                            'From' => '+13103128070',
                                            'Body' => $message,
                                        ));
                                    } catch (Services_Twilio_RestException $e) {
                                        //echo  $e;
                                    }



                                    if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->agent_id) && (!$agent_det->block_washer) && ($agent_det->sms_control) && ($agent_det->available_for_new_order > 0)) {
                                        try {
                                            $sendmessage = $client->account->messages->create(array(
                                                'To' => $agent_det->phone_number,
                                                'From' => '+13103128070',
                                                'Body' => $message2,
                                            ));
                                        } catch (Services_Twilio_RestException $e) {
                                            //echo  $e;
                                        }
                                    }
                                }


                                break;
                            } else {
                                if ($cancelresult['success'] == 1) {
                                    if ($cust_exists->client_position == 'real')
                                        $cancelvoid = Yii::app()->braintree->void_real($cancelresult['transaction_id']);
                                    else
                                        $cancelvoid = Yii::app()->braintree->void($cancelresult['transaction_id']);
                                }
                                $result = 'false';
                                $response = 'error in payment';
                            }
                        }
                    }
                    else {
                        $result = 'false';
                        $response = 'no payment method exists';
                    }
                } else {

                    $result = 'true';
                    $response = 'Order canceled';
                    $cancel_price = 0;
                    Washingrequests::model()->updateByPk($id, array('status' => 5, 'order_canceled_at' => date("Y-m-d H:i:s"), 'company_cancel' => $company_cancel, 'cancel_fee' => 0));

                    if ($order_exists->transaction_id) {
                        if ($order_exists->wash_request_position == 'real')
                            $voidresult = Yii::app()->braintree->void_real($order_exists->transaction_id);
                        else
                            $voidresult = Yii::app()->braintree->void($order_exists->transaction_id);
                    }

                    $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $order_exists->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                    if (!count($is_cust_has_order)) {
                        Customers::model()->updateByPk($order_exists->customer_id, array("is_first_wash" => 0));
                    }

                    $from = Vargas::Obj()->getAdminFromEmail();
                    $sched_date = '';
                    $sched_time = '';

                    if (!$order_exists->is_scheduled) {

                        if (strtotime($order_exists->order_for) == strtotime(date('Y-m-d'))) {
                            $sched_date = 'Today';
                        } else {
                            $sched_date = date('M d', strtotime($order_exists->order_for));
                        }
                        $sched_time = date('g:i A', strtotime($order_exists->order_for));
                    } else {

                        if ($order_exists->reschedule_time) {
                            if (strtotime($order_exists->reschedule_date) == strtotime(date('Y-m-d'))) {
                                $sched_date = 'Today';
                            } else {
                                $sched_date = date('M d', strtotime($order_exists->reschedule_date));
                            }
                            $sched_time = $order_exists->reschedule_time;
                        } else {
                            if (strtotime($order_exists->schedule_date) == strtotime(date('Y-m-d'))) {
                                $sched_date = 'Today';
                            } else {
                                $sched_date = date('M d', strtotime($order_exists->schedule_date));
                            }
                            $sched_time = $order_exists->schedule_time;
                        }
                    }
                    $message = '';
                    $subject = 'Cancel Order Receipt - #0000' . $id;
                    //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                    $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>This order has been canceled</h2>";
                    if (!$order_exists->is_scheduled)
                        $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>On-demand order for " . $sched_date . " @ " . $sched_time . "</p>";
                    else
                        $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Scheduled order for " . $sched_date . " @ " . $sched_time . "</p>";
                    $message .= "<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $order_exists->address . "</p>";
                    $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>Client Name:</strong> " . $cust_exists->first_name . " " . $cust_exists->last_name . "</td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $id . "</td></tr>
					</table>";

                    $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";

                    foreach ($kartdata->vehicles as $ind => $vehicle) {

                        $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$0.00</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                        if ($vehicle->extclaybar_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }
                        if ($vehicle->waterspotremove_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }
                        if ($vehicle->upholstery_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }
                        if ($vehicle->exthandwax_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }
                        if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        if ($vehicle->floormat_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        if ($vehicle->pet_hair_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }
                        if ($vehicle->lifted_vehicle_fee > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }


                        if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (" . $order_exists->coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        if ($vehicle->fifth_wash_discount > 0) {
                            $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                        }

                        $message .= "</table>

</td>
</tr>";
                    }



                    /* if($coupon_amount){
                      $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
                      <td style='padding-bottom: 15px;'>
                      <p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
                      </td>
                      <td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-</p></td>
                      </tr></table>";
                      } */
                    $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 20px; margin: 0;'><strong>Cancellation Fee</strong></p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0; font-weight: bold;'>$0.00</p></td>
							</tr></table>";

                    $message .= "</table>";

                    $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$0</span></p></td></tr></table>";


                    $to = Vargas::Obj()->getAdminToEmail();

                    Vargas::Obj()->SendMail($cust_exists->email, "billing@devmobilewash.com", $message, $subject, 'mail-receipt');
                    Vargas::Obj()->SendMail($to, $cust_exists->email, $message, $subject, 'mail-receipt');

                    Washingrequests::model()->updateByPk($order_exists->id, array('is_order_receipt_sent' => 1));

                    if ((APP_ENV == 'real')) {

                        $this->layout = "xmlLayout";

                        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                        require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                        $account_sid = TWILIO_SID;
                        $auth_token = TWILIO_AUTH_TOKEN;
                        $client = new Services_Twilio($account_sid, $auth_token);


                        $message = "Order #" . $id . " has been canceled\r\nCustomer Name: " . $cust_exists->first_name . " " . $cust_exists->last_name . "\r\nPhone: " . $cust_exists->contact_number . "\r\nAddress: " . $order_exists->address . " (" . $order_exists->address_type . ")";
                        $message2 = "Order #" . $id . " has been canceled";

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '8183313631',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3109999334',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        try {
                            $sendmessage = $client->account->messages->create(array(
                                'To' => '3103442534',
                                'From' => '+13103128070',
                                'Body' => $message,
                            ));
                        } catch (Services_Twilio_RestException $e) {
                            //echo  $e;
                        }

                        if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->agent_id) && (!$agent_det->block_washer) && ($agent_det->sms_control) && ($agent_det->available_for_new_order > 0)) {
                            try {
                                $sendmessage = $client->account->messages->create(array(
                                    'To' => $agent_det->phone_number,
                                    'From' => '+13103128070',
                                    'Body' => $message2,
                                ));
                            } catch (Services_Twilio_RestException $e) {
                                //echo  $e;
                            }
                        }
                    }
                }
                if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->agent_id) && (!$agent_det->block_washer)) {

                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $order_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '20' ")->queryAll();
                    $message = $pushmsg[0]['message'];
                    //$message = str_replace("[ORDER_ID]","#".$order_exists->id, $pushmsg[0]['message']);
                    foreach ($agentdevices as $agdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (($result == 'true') && ($admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $order_exists->id,
                        'admin_username' => $admin_username,
                        'action' => 'cancelorder',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $order_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '31' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (($result == 'true') && (!$admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $order_exists->id,
                        'action' => 'cancelorderclient',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }

                if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->coupon_code)) {
                    CustomerDiscounts::model()->deleteAll("wash_request_id=" . $order_exists->id . " AND customer_id=" . $order_exists->customer_id . " AND promo_code='" . $order_exists->coupon_code . "'");
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'cancel_price' => $cancel_price
        );

        echo json_encode($json);
    }

    private static function sortById($a, $b) {
        $a = abs($a['min_diff']);
        $b = abs($b['min_diff']);

        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
    }

    public function actiongetschedulewashrequests() {

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
        /* Checking for post(day) parameters */
        $order_day = '';
        if (!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))) {
            $day = Yii::app()->request->getParam('day');
            $event = Yii::app()->request->getParam('event');
            $status_qr = '';
            if ($event == 'pending') {
                $status = 0;
                $status_qr = ' AND status="' . $status . '"';
            } elseif ($event == 'completed') {
                $status = 4;
                $status_qr = ' AND status="' . $status . '"';
            } elseif ($event == 'processing') {
                $status = 2;
                $status_qr = ' AND status="' . $status . '"';
            } else {
                $status_qr = ' AND (status !=5 && status !=6)';
            }

            $order_day = " AND DATE_FORMAT(schedule_date,'%Y-%m-%d')= '$day'$status_qr";
        }
        /* END */



        $json = array();

        $result = 'true';
        $response = 'scheduled wash requests';
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


        if ($limit > 0)
            $qrRequests = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND wash_request_position = '" . APP_ENV . "' ORDER BY id DESC LIMIT " . $limit)->queryAll();
        else
            $qrRequests = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE is_scheduled = 1 AND wash_request_position = '" . APP_ENV . "' ORDER BY id DESC")->queryAll();
        if (count($qrRequests) > 0) {

            foreach ($qrRequests as $wrequest) {

                if ($filter == 'upcoming') {

                    if ($wrequest['reschedule_time'])
                        $scheduledatetime = $wrequest['reschedule_date'] . " " . $wrequest['reschedule_time'];
                    else
                        $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];

                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;
                    if ($from_time >= $to_time) {
                        $min_diff = round(($from_time - $to_time) / 60, 2);
                    }

                    if ($min_diff <= 0)
                        continue;
                }

                if ($filter == 'nonupcoming') {

                    if ($wrequest['reschedule_time'])
                        $scheduledatetime = $wrequest['reschedule_date'] . " " . $wrequest['reschedule_time'];
                    else
                        $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];

                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = 0;
                    if ($from_time >= $to_time) {
                        $min_diff = round(($from_time - $to_time) / 60, 2);
                    }

                    if ($min_diff > 0)
                        continue;
                }

                if ($wrequest['reschedule_time'])
                    $scheduledatetime = $wrequest['reschedule_date'] . " " . $wrequest['reschedule_time'];
                else
                    $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];

                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                $min_diff = 0;

                $min_diff = round(($from_time - $to_time) / 60, 2);

//$min_diff = abs($min_diff);


                $cust_details = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id" => $wrequest['agent_id']));
                $cars = explode(",", $wrequest['car_list']);
                $packs = explode(",", $wrequest['package_list']);
                $vehicles = array();
                foreach ($cars as $ind => $car) {
                    $car_details = Vehicle::model()->findByAttributes(array("id" => $car));
                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind]);
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
                    $agent_info = array('agent_id' => $wrequest['agent_id'], 'agent_name' => $agent_details->first_name . " " . $agent_details->last_name, 'agent_phoneno' => $agent_details->phone_number, 'agent_email' => $agent_details->email);
                }
                $payment_status = '';

                if ($wrequest['transaction_id']) {

                    if ($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held') {
                        $payment_status = 'Processed';
                    } else if ($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released') {
                        $payment_status = 'Released';
                    }
                }

                if ($min_diff >= 0) {
                    $resched_date = '';
                    $resched_time = '';
                    if (strtotime($wrequest['reschedule_date']) > 0) {
                        $resched_date = date('Y-m-d', strtotime($wrequest['reschedule_date']));
                        $resched_time = date('h:i A', strtotime($wrequest['reschedule_time']));
                    }
                    $pendingwashrequests_upcoming[] = array('id' => $wrequest['id'],
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
                        'transaction_id' => $wrequest['transaction_id'],
                        'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
                        'schedule_total' => $wrequest['schedule_total'],
                        'schedule_company_total' => $wrequest['schedule_company_total'],
                        'schedule_agent_total' => $wrequest['schedule_agent_total'],
                        'wash_request_position' => $wrequest['wash_request_position'],
                        'payment_status' => $payment_status,
                        'min_diff' => $min_diff
                    );
                }
                if ($min_diff < 0) {
                    $pendingwashrequests_nonupcoming[] = array('id' => $wrequest['id'],
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
                        'schedule_date' => $wrequest['schedule_date'],
                        'schedule_time' => $wrequest['schedule_time'],
                        'reschedule_date' => $wrequest['reschedule_date'],
                        'checklist' => $wrequest['checklist'],
                        'reschedule_time' => $wrequest['reschedule_time'],
                        'created_date' => $wrequest['created_date'],
                        'transaction_id' => $wrequest['transaction_id'],
                        'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
                        'schedule_total' => $wrequest['schedule_total'],
                        'schedule_company_total' => $wrequest['schedule_company_total'],
                        'schedule_agent_total' => $wrequest['schedule_agent_total'],
                        'wash_request_position' => $wrequest['wash_request_position'],
                        'payment_status' => $payment_status,
                        'min_diff' => $min_diff
                    );
                }
                $pendingwashrequests[] = array('id' => $wrequest['id'],
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
                    'schedule_date' => $wrequest['schedule_date'],
                    'schedule_time' => $wrequest['schedule_time'],
                    'reschedule_date' => $wrequest['reschedule_date'],
                    'checklist' => $wrequest['checklist'],
                    'reschedule_time' => $wrequest['reschedule_time'],
                    'created_date' => $wrequest['created_date'],
                    'transaction_id' => $wrequest['transaction_id'],
                    'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
                    'schedule_total' => $wrequest['schedule_total'],
                    'schedule_company_total' => $wrequest['schedule_company_total'],
                    'schedule_agent_total' => $wrequest['schedule_agent_total'],
                    'wash_request_position' => $wrequest['wash_request_position'],
                    'payment_status' => $payment_status,
                    'min_diff' => $min_diff
                );
            }

            usort($pendingwashrequests_upcoming, array('WashingController', 'sortById'));
            usort($pendingwashrequests_nonupcoming, array('WashingController', 'sortById'));

            $pendingwashrequests = array_merge($pendingwashrequests_upcoming, $pendingwashrequests_nonupcoming);
        } else {
            $result = 'false';
            $response = 'no scheduled wash requests found';
        }



        $json = array(
            'result' => $result,
            'response' => $response,
            'schedule_wash_requests' => $pendingwashrequests,
                //'upcoming' => $pendingwashrequests_upcoming,
                //'nonupcoming' => $pendingwashrequests_nonupcoming,
        );

        echo json_encode($json);
        die();
    }

    public function actiongetallwashrequests() {

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
        /* Checking for post(day) parameters */
        $order_day = '';
        if (!empty(Yii::app()->request->getParam('day')) && !empty(Yii::app()->request->getParam('event'))) {
            $day = Yii::app()->request->getParam('day');
            $event = Yii::app()->request->getParam('event');
            $status_qr = '';
            if ($event == 'pending') {
                $status = 0;
                $status_qr = ' AND w.status="' . $status . '"';
            } elseif ($event == 'completed') {
                $status = 4;
                $status_qr = ' AND w.status="' . $status . '"';
            } elseif ($event == 'processing') {
                $status = 2;
                $status_qr = ' AND (w.status >=1 && w.status <=3)';
            } elseif ($event == 'canceled') {

                $status_qr = ' AND (w.status=5 || w.status=6)';
            } else {
                $status_qr = '';
            }

            $order_day = " AND DATE_FORMAT(w.order_for,'%Y-%m-%d')= :day" . $status_qr;
        }
        /* END */



        $json = array();

        $result = 'true';
        $response = 'all wash requests';
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



        //if($limit > 0) $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC LIMIT ".$limit)->queryAll();
//else $qrRequests =  Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE wash_request_position = 'real' ".$order_day." ORDER BY id DESC")->queryAll();

        if ($filter == 'testorders') {
            if ($limit > 0)
                $qrRequests = Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 0 AND w.wash_request_position = '" . APP_ENV . "' " . $order_day . " ORDER BY w.id DESC LIMIT " . $limit)->bindValue(':day', $day, PDO::PARAM_STR)->queryAll();
            else
                $qrRequests = Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 0 AND w.wash_request_position = '" . APP_ENV . "' " . $order_day . " ORDER BY w.id DESC")->bindValue(':day', $day, PDO::PARAM_STR)->queryAll();
        }
        else {
            if ($limit > 0)
                $qrRequests = Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 1 AND w.wash_request_position = '" . APP_ENV . "' " . $order_day . " ORDER BY w.id DESC LIMIT " . $limit)->bindValue(':day', $day, PDO::PARAM_STR)->queryAll();
            else
                $qrRequests = Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 1 AND w.wash_request_position = '" . APP_ENV . "' " . $order_day . " ORDER BY w.id DESC")->bindValue(':day', $day, PDO::PARAM_STR)->queryAll();
        }

        if (count($qrRequests) > 0) {

            foreach ($qrRequests as $wrequest) {



                if ($wrequest['reschedule_time'])
                    $scheduledatetime = $wrequest['reschedule_date'] . " " . $wrequest['reschedule_time'];
                else
                    $scheduledatetime = $wrequest['schedule_date'] . " " . $wrequest['schedule_time'];

                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                $min_diff = 0;

                $min_diff = round(($from_time - $to_time) / 60, 2);

//$min_diff = abs($min_diff);


                $cust_details = Customers::model()->findByAttributes(array("id" => $wrequest['customer_id']));
                $agent_details = Agents::model()->findByAttributes(array("id" => $wrequest['agent_id']));
                $cars = explode(",", $wrequest['car_list']);
                $packs = explode(",", $wrequest['package_list']);
                $vehicles = array();
                foreach ($cars as $ind => $car) {
                    $car_details = Vehicle::model()->findByAttributes(array("id" => $car));
                    $vehicles[] = array('id' => $car, 'make' => $car_details->brand_name, 'model' => $car_details->model_name, 'pack' => $packs[$ind]);
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
                    $agent_info = array('agent_id' => $wrequest['agent_id'], 'agent_name' => $agent_details->first_name . " " . $agent_details->last_name, 'agent_phoneno' => $agent_details->phone_number, 'agent_email' => $agent_details->email);
                }
                $payment_status = '';

                if ($wrequest['transaction_id']) {

                    if ($wrequest['escrow_status'] == 'hold_pending' || $wrequest['escrow_status'] == 'held') {
                        $payment_status = 'Processed';
                    } else if ($wrequest['escrow_status'] == 'release_pending' || $wrequest['escrow_status'] == 'released') {
                        $payment_status = 'Released';
                    }
                }

                $pendingwashrequests[] = array('id' => $wrequest['id'],
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
                    'schedule_date' => $wrequest['schedule_date'],
                    'schedule_time' => $wrequest['schedule_time'],
                    'reschedule_date' => $wrequest['reschedule_date'],
                    'checklist' => $wrequest['checklist'],
                    'reschedule_time' => $wrequest['reschedule_time'],
                    'created_date' => $wrequest['created_date'],
                    'transaction_id' => $wrequest['transaction_id'],
                    'scheduled_cars_info' => $wrequest['scheduled_cars_info'],
                    'schedule_total' => $wrequest['schedule_total'],
                    'schedule_company_total' => $wrequest['schedule_company_total'],
                    'schedule_agent_total' => $wrequest['schedule_agent_total'],
                    'wash_request_position' => $wrequest['wash_request_position'],
                    'payment_status' => $payment_status,
                    'min_diff' => $min_diff
                );
            }
        } else {
            $result = 'false';
            $response = 'no wash requests found';
        }



        $json = array(
            'result' => $result,
            'response' => $response,
            'wash_requests' => $pendingwashrequests,
                //'upcoming' => $pendingwashrequests_upcoming,
                //'nonupcoming' => $pendingwashrequests_nonupcoming,
        );

        echo json_encode($json);
        die();
    }

    private function _getHoursDifference($date1, $date2) {

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

        $minuts = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);

        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));
        return $hours;
    }

    public function actiongetallschedulewashes() {

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

        $response = "no scheduled washes found";
        $result = "false";
        $allwashes = array();
        $agent_id = Yii::app()->request->getParam('agent_id');
        $washer_position = Yii::app()->request->getParam('washer_position');

        if (AES256CBC_STATUS == 1) {
            $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
        }

        $agent_detail = Agents::model()->findByAttributes(array("id" => $agent_id));
        $is_scheduled_wash_120 = 0;
        $scheduled_wash_120_id = 0;
        $agent_latitude = Yii::app()->request->getParam('agent_latitude');
        $agent_longitude = Yii::app()->request->getParam('agent_longitude');

        if ((count($agent_detail)) && ($agent_detail->block_washer)) {
            $json = array(
                'result' => $result,
                'response' => $response
            );

            echo json_encode($json);
            die();
        }


//$criteria=new CDbCriteria;
//$criteria->condition = "wash_request_position = '".$washer_position."' AND agent_id = 0 AND is_scheduled = 1 AND status = 0";
        //$allschedwashes = Washingrequests::model()->findAll($criteria, array('order' => 'created_date asc'));
//if(!$agent_detail->hours_opt_check) $allschedwashes =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 0 AND w.wash_request_position = '".$washer_position."' AND w.agent_id=0 AND w.is_scheduled = 1 AND w.status = 0 ORDER BY w.created_date ASC")->queryAll();
//else $allschedwashes =  Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE c.hours_opt_check = 1 AND w.wash_request_position = '".$washer_position."' AND w.agent_id=0 AND w.is_scheduled = 1 AND w.status = 0 ORDER BY w.created_date ASC")->queryAll();

        $allschedwashes = Yii::app()->db->createCommand("SELECT w.* FROM washing_requests w LEFT JOIN customers c ON w.customer_id = c.id WHERE w.wash_request_position = :washer_position AND w.agent_id=0 AND w.is_scheduled = 1 AND w.status = 0 ORDER BY w.created_date ASC")->bindValue(':washer_position', $washer_position, PDO::PARAM_STR)->queryAll();

        if (count($allschedwashes)) {
            $customerName = '';
            $currentDateTime = date('Y-m-d h:i:s', time());
            $currentDate = date('Y-m-d');
            foreach ($allschedwashes as $schedwash) {

                if ((is_numeric($agent_latitude)) && (is_numeric($agent_longitude))) {

                    $theta = $schedwash['longitude'] - $agent_longitude;
                    $dist = sin(deg2rad($schedwash['latitude'])) * sin(deg2rad($agent_latitude)) + cos(deg2rad($schedwash['latitude'])) * cos(deg2rad($agent_latitude)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                    //$unit = strtoupper($unit);
                    if ($agent_detail->unlimited_schedule_range == 0) {
                        if ($miles > 50)
                            continue;
                    }
                }

                $schdDateTime = date('Y-m-d h:i:s', strtotime($schedwash['schedule_date'] . ' ' . $schedwash['schedule_time']));
                $schdDate = date('Y-m-d', strtotime($schedwash['schedule_date']));

                $hoursDiff = $this->_getHoursDifference($currentDateTime, $schdDateTime);
                //if($schdDate >= $currentDate && $hoursDiff >= 1){
                $sched_date = '';
                $sched_time = '';
                if ($schedwash['reschedule_time']) {
                    $sched_date = $schedwash['reschedule_date'];
                    $sched_time = $schedwash['reschedule_time'];
                } else {
                    $sched_date = $schedwash['schedule_date'];
                    $sched_time = $schedwash['schedule_time'];
                }

                $scheduledatetime = $sched_date . " " . $sched_time;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                $min_diff = 0;

                $min_diff = round(($from_time - $to_time) / 60, 2);

                if (($min_diff < 0) && ($min_diff <= -1440)) {
                    continue;
                }

                if (!empty($schedwash['customer_id'])) {
                    $customer = Yii::app()->db->createCommand()->setFetchMode(PDO::FETCH_OBJ)
                            ->select('customername, first_name, last_name')
                            ->from('customers')
                            ->where("id =" . $schedwash['customer_id'])
                            ->queryAll();
                    $customerName = $customer[0]->first_name . " " . $customer[0]->last_name;
                }
                $washtime = 0;
                $washtime_str = '';
                $cars = explode(",", $schedwash['car_list']);
                $plans = explode(",", $schedwash['package_list']);
                foreach ($cars as $ind => $car) {
                    $car_detail = Vehicle::model()->findByPk($car);

                    $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/plans");
                    $data = array('vehicle_make' => $car_detail->brand_name, 'vehicle_model' => $car_detail->model_name, 'vehicle_build' => $car_detail->vehicle_build, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $plan_result = curl_exec($handle);
                    curl_close($handle);
                    $jsondata = json_decode($plan_result);

                    if ($plans[$ind] == 'Express') {
                        $expprice = intval($jsondata->plans->express[0]->wash_time);
                        $washtime += $expprice;
                    }

                    if ($plans[$ind] == 'Deluxe') {
                        $delprice = intval($jsondata->plans->deluxe[0]->wash_time);
                        $washtime += $delprice;
                    }

                    if ($plans[$ind] == 'Premium') {
                        $premprice = intval($jsondata->plans->premium[0]->wash_time);
                        $washtime += $premprice;
                    }

                    /* --- addons time ----- */



                    $pet_hair_vehicles_arr = explode(",", $schedwash['pet_hair_vehicles']);
                    if (in_array($car, $pet_hair_vehicles_arr))
                        $washtime += 5;

                    $lifted_vehicles_arr = explode(",", $schedwash['lifted_vehicles']);
                    if (in_array($car, $lifted_vehicles_arr))
                        $washtime += 5;

                    $exthandwax_vehicles_arr = explode(",", $schedwash['exthandwax_vehicles']);
                    if (in_array($car, $exthandwax_vehicles_arr))
                        $washtime += 10;

                    $extplasticdressing_vehicles_arr = explode(",", $schedwash['extplasticdressing_vehicles']);
                    if (in_array($car, $extplasticdressing_vehicles_arr))
                        $washtime += 5;

                    $extclaybar_vehicles_arr = explode(",", $schedwash['extclaybar_vehicles']);
                    if (in_array($car, $extclaybar_vehicles_arr))
                        $washtime += 15;

                    $waterspotremove_vehicles_arr = explode(",", $schedwash['waterspotremove_vehicles']);
                    if (in_array($car, $waterspotremove_vehicles_arr))
                        $washtime += 10;

                    $upholstery_vehicles_arr = explode(",", $schedwash['upholstery_vehicles']);
                    if (in_array($car, $upholstery_vehicles_arr))
                        $washtime += 10;

                    $floormat_vehicles_arr = explode(",", $schedwash['floormat_vehicles']);
                    if (in_array($car, $floormat_vehicles_arr))
                        $washtime += 10;

                    /* --- addons time end ----- */
                }

                //$washtime += 30;




                $hours = floor($washtime / 60);
                $minutes = ($washtime % 60);

                if ($hours < 1) {
                    $washtime_str = sprintf('%02d min', $minutes);
                } else {
                    if ($hours == 1) {
                        if ($minutes > 0)
                            $washtime_str = sprintf('%d hour %02d min', $hours, $minutes);
                        else
                            $washtime_str = sprintf('%d hour', $hours);
                    }
                    else {
                        if ($minutes > 0)
                            $washtime_str = sprintf('%d hours %02d min', $hours, $minutes);
                        else
                            $washtime_str = sprintf('%d hours', $hours);
                    }
                }




                $declinedids = explode(",", $schedwash['agent_reject_ids']);

                if ($agent_id) {

                    if (!in_array(-$agent_id, $declinedids)) {

                        if (AES256CBC_STATUS == 1) {
                            $allwashes[] = array('id' => $this->aes256cbc_crypt($schedwash['id'], 'e', AES256CBC_API_PASS),
                                'car_list' => $schedwash['car_list'],
                                'customer_id' => $this->aes256cbc_crypt($schedwash['customer_id'], 'e', AES256CBC_API_PASS),
                                'customer_name' => $customerName,
                                'package_list' => $schedwash['package_list'],
                                'address' => $schedwash['address'],
                                'address_type' => $schedwash['address_type'],
                                'street_name' => $schedwash['street_name'],
                                'city' => $schedwash['city'],
                                'state' => $schedwash['state'],
                                'zipcode' => $schedwash['zipcode'],
                                'latitude' => $schedwash['latitude'],
                                'longitude' => $schedwash['longitude'],
                                'status' => $schedwash['status'],
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time,
                                'estimate_time' => $washtime,
                                'estimate_time_str' => $washtime_str
                            );
                        } else {
                            $allwashes[] = array('id' => $schedwash['id'],
                                'car_list' => $schedwash['car_list'],
                                'customer_id' => $schedwash['customer_id'],
                                'customer_name' => $customerName,
                                'package_list' => $schedwash['package_list'],
                                'address' => $schedwash['address'],
                                'address_type' => $schedwash['address_type'],
                                'street_name' => $schedwash['street_name'],
                                'city' => $schedwash['city'],
                                'state' => $schedwash['state'],
                                'zipcode' => $schedwash['zipcode'],
                                'latitude' => $schedwash['latitude'],
                                'longitude' => $schedwash['longitude'],
                                'status' => $schedwash['status'],
                                'schedule_date' => $sched_date,
                                'schedule_time' => $sched_time,
                                'estimate_time' => $washtime,
                                'estimate_time_str' => $washtime_str
                            );
                        }
                    }
                } else {
                    if (AES256CBC_STATUS == 1) {
                        $allwashes[] = array('id' => $this->aes256cbc_crypt($schedwash['id'], 'e', AES256CBC_API_PASS),
                            'car_list' => $schedwash['car_list'],
                            'customer_id' => $this->aes256cbc_crypt($schedwash['customer_id'], 'e', AES256CBC_API_PASS),
                            'customer_name' => $customerName,
                            'package_list' => $schedwash['package_list'],
                            'address' => $schedwash['address'],
                            'address_type' => $schedwash['address_type'],
                            'latitude' => $schedwash['latitude'],
                            'longitude' => $schedwash['longitude'],
                            'street_name' => $schedwash['street_name'],
                            'city' => $schedwash['city'],
                            'state' => $schedwash['state'],
                            'zipcode' => $schedwash['zipcode'],
                            'status' => $schedwash['status'],
                            'schedule_date' => $sched_date,
                            'schedule_time' => $sched_time,
                            'estimate_time' => $washtime,
                            'estimate_time_str' => $washtime_str
                        );
                    } else {
                        $allwashes[] = array('id' => $schedwash['id'],
                            'car_list' => $schedwash['car_list'],
                            'customer_id' => $schedwash['customer_id'],
                            'customer_name' => $customerName,
                            'package_list' => $schedwash['package_list'],
                            'address' => $schedwash['address'],
                            'address_type' => $schedwash['address_type'],
                            'street_name' => $schedwash['street_name'],
                            'city' => $schedwash['city'],
                            'state' => $schedwash['state'],
                            'zipcode' => $schedwash['zipcode'],
                            'latitude' => $schedwash['latitude'],
                            'longitude' => $schedwash['longitude'],
                            'status' => $schedwash['status'],
                            'schedule_date' => $sched_date,
                            'schedule_time' => $sched_time,
                            'estimate_time' => $washtime,
                            'estimate_time_str' => $washtime_str
                        );
                    }
                }
                //}
            }

            if (count($allwashes)) {
                $response = "all schedule washes";
                $result = "true";
            }
        }

        if ($agent_id) {

            $agenttakenwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent_id, 'is_scheduled' => 1), array('condition' => 'status = 0 OR status = 1 OR status = 2'));

            if (count($agenttakenwashes)) {
                foreach ($agenttakenwashes as $schedwash) {

                    if ($schedwash->reschedule_time)
                        $scheduledatetime = $schedwash->reschedule_date . " " . $schedwash->reschedule_time;
                    else
                        $scheduledatetime = $schedwash->schedule_date . " " . $schedwash->schedule_time;

                    $to_time = strtotime(date('Y-m-d g:i A'));
                    $from_time = strtotime($scheduledatetime);
                    $min_diff = -1;
                    //if($from_time >= $to_time){
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                    //}

                    if (!$is_scheduled_wash_120) {
                        //if($min_diff <= 120 && $min_diff >= 0){
                        if ($min_diff <= 120) {
                            $is_scheduled_wash_120 = 1;
                            $scheduled_wash_120_id = $schedwash->id;
                            break;
                        }
                    }
                }
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'schedule_washes' => $allwashes,
            'is_scheduled_wash_120' => $is_scheduled_wash_120,
            'scheduled_wash_120_id' => $scheduled_wash_120_id
        );

        echo json_encode($json);
        die();
    }

    public function actionschedulewashalert() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }


        $allschedwashes = Washingrequests::model()->findAllByAttributes(array('is_scheduled' => 1, 'status' => 0));

        if (count($allschedwashes)) {
            foreach ($allschedwashes as $schedwash) {
                $wash_id_encoded = '';
                if (AES256CBC_STATUS == 1) {
                    $wash_id_encoded = $this->aes256cbc_crypt($schedwash->id, 'e', AES256CBC_API_PASS);
                }



                /* --- send schedule wash create alert ------- */

                if (!$schedwash->is_create_schedulewash_push_sent) {

                    $allagents = Agents::model()->findAll(array("condition" => "block_washer =  0"));

                    foreach ($allagents as $agent) {
                        $get_notify = 1;
                        $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id' => $agent->id));
                        if (count($agent_loc_obj)) {
                            $theta = $schedwash->longitude - $agent_loc_obj->longitude;
                            $dist = sin(deg2rad($schedwash->latitude)) * sin(deg2rad($agent_loc_obj->latitude)) + cos(deg2rad($schedwash->latitude)) * cos(deg2rad($agent_loc_obj->latitude)) * cos(deg2rad($theta));
                            $dist = acos($dist);
                            $dist = rad2deg($dist);
                            $miles = $dist * 60 * 1.1515;

                            if ($miles > 50)
                                $get_notify = 0;
                        }



                        //$agentallschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id'=>$agent->id, 'is_scheduled' => 1, 'status'=> 0));

                        /* foreach($agentallschedwashes as $wash){

                          $datediff = round((strtotime($wash['schedule_date']) -  strtotime($schedwash->schedule_date))/(60*60));

                          if($datediff < 2){
                          $get_notify = 0;
                          break;
                          }

                          } */

                        if ($get_notify) {

                            //$agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '".$agent->id."' AND device_token != '' ORDER BY last_used DESC LIMIT 1")->queryAll();
                            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agent->id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
                            $message = $pushmsg[0]['message'];
                            $message2 = str_replace(array("[CITY]", "[WASHER_TOTAL]"), array(strtoupper($schedwash->city), number_format($schedwash->agent_total, 2)), $message);

                            foreach ($agentdevices as $agdevice) {

                                $device_type = '';
                                $notify_token = '';
                                //$message =  "You have a new scheduled wash request.";
                                //echo $agentdetails['mobile_type'];
                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "schedule";
                                $notify_msg = urlencode($message2);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type . "&wash_id=" . $wash_id_encoded;
                                file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                    }

                    Washingrequests::model()->updateByPk($schedwash->id, array("is_create_schedulewash_push_sent" => 1));
                }

                /* --- send schedule wash create alert end ------- */


                /* --- send no washer before 30 mins alert ------- */

                if ($schedwash->reschedule_time)
                    $scheduledatetime = $schedwash->reschedule_date . " " . $schedwash->reschedule_time;
                else
                    $scheduledatetime = $schedwash->schedule_date . " " . $schedwash->schedule_time;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);

                $min_diff = -1;
                if ($from_time >= $to_time) {
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                }

                if ((!$schedwash->is_schedule_no_washer_befor1hour_alert_sent) && (!$schedwash->agent_id) && ($min_diff <= 30)) {


                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $schedwash->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '37' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    foreach ($clientdevices as $ctdevice) {
                        $device_type = '';
                        $notify_token = '';
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "default";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }




                    Washingrequests::model()->updateByPk($schedwash->id, array("is_schedule_no_washer_befor1hour_alert_sent" => 1));
                }

                /* --- send no washer before 30 mins alert end ------- */

                /* --- send reschedule wash alert ------- */
                /*
                  if ((!$schedwash->is_reschedulewash_push_sent) && $schedwash->reschedule_time) {

                  if ($schedwash->agent_id) {

                  $agentdet = Agents::model()->findByPk($schedwash->agent_id);

                  $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $schedwash->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                  $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
                  //$message = $pushmsg[0]['message'];
                  $message = str_replace("[ORDER_ID]", "#" . $schedwash->id, $pushmsg[0]['message']);

                  foreach ($agentdevices as $agdevice) {
                  //$message =  "You have a new scheduled wash request.";
                  //echo $agentdetails['mobile_type'];
                  $device_type = strtolower($agdevice['device_type']);
                  $notify_token = $agdevice['device_token'];
                  $alert_type = "schedule";
                  $notify_msg = urlencode($message);

                  $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                  file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $notifyurl);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                  if ($notify_msg)
                  $notifyresult = curl_exec($ch);
                  curl_close($ch);
                  }
                  }
                  else {
                  $allagents = Agents::model()->findAll(array("condition" => "block_washer =  0"));

                  foreach ($allagents as $agent) {
                  $get_notify = 1;

                  $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id' => $agent->id));
                  if (count($agent_loc_obj)) {
                  $theta = $schedwash->longitude - $agent_loc_obj->longitude;
                  $dist = sin(deg2rad($schedwash->latitude)) * sin(deg2rad($agent_loc_obj->latitude)) + cos(deg2rad($schedwash->latitude)) * cos(deg2rad($agent_loc_obj->latitude)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;

                  if ($miles > 50)
                  $get_notify = 0;
                  }

                  $agentallschedwashes = Washingrequests::model()->findAllByAttributes(array('agent_id' => $agent->id, 'is_scheduled' => 1, 'status' => 0));

                  foreach ($agentallschedwashes as $wash) {

                  $datediff = round((strtotime($wash['schedule_date']) - strtotime($schedwash->schedule_date)) / (60 * 60));

                  if ($datediff < 2) {
                  $get_notify = 0;
                  break;
                  }
                  }

                  if ($get_notify) {

                  $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agent->id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                  $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '18' ")->queryAll();
                  //$message = $pushmsg[0]['message'];
                  $message = str_replace("[ORDER_ID]", "#" . $schedwash->id, $pushmsg[0]['message']);
                  foreach ($agentdevices as $agdevice) {
                  //$message =  "You have a new scheduled wash request.";
                  //echo $agentdetails['mobile_type'];
                  $device_type = strtolower($agdevice['device_type']);
                  $notify_token = $agdevice['device_token'];
                  $alert_type = "schedule";
                  $notify_msg = urlencode($message);

                  $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                  file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $notifyurl);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                  if ($notify_msg)
                  $notifyresult = curl_exec($ch);
                  curl_close($ch);
                  }
                  }
                  }
                  }



                  Washingrequests::model()->updateByPk($schedwash->id, array("is_reschedulewash_push_sent" => 1));
                  }
                 */
                /* --- send reschedule wash alert end ------- */

                if ($schedwash->reschedule_time)
                    $scheduledatetime = $schedwash->reschedule_date . " " . $schedwash->reschedule_time;
                else
                    $scheduledatetime = $schedwash->schedule_date . " " . $schedwash->schedule_time;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);

                $min_diff = -1;
                if ($from_time >= $to_time) {
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                }
                echo "#" . $schedwash->id . " " . $min_diff . "<br>";
                if ($min_diff <= 60 && $min_diff >= 10) {

                    if ($schedwash->agent_id) {
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $schedwash->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                        //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
                        //$message = $pushmsg[0]['message'];

                        if ($min_diff == 60)
                            $message2 = "You have a scheduled car wash within 60 minutes";

                        else if ($min_diff < 60 && $min_diff >= 55)
                            $message2 = "You have a scheduled car wash within 55 minutes";

                        else if ($min_diff < 55 && $min_diff >= 50)
                            $message2 = "You have a scheduled car wash within 50 minutes";

                        else if ($min_diff < 50 && $min_diff >= 45)
                            $message2 = "You have a scheduled car wash within 45 minutes";

                        else if ($min_diff < 45 && $min_diff >= 40)
                            $message2 = "You have a scheduled car wash within 40 minutes";

                        else if ($min_diff < 40 && $min_diff >= 35)
                            $message2 = "You have a scheduled car wash within 35 minutes";

                        else if ($min_diff < 35 && $min_diff >= 30)
                            $message2 = "You have a scheduled car wash within 30 minutes";

                        else if ($min_diff < 30 && $min_diff >= 25)
                            $message2 = "You have a scheduled car wash within 25 minutes";

                        else if ($min_diff < 25 && $min_diff >= 20)
                            $message2 = "You have a scheduled car wash within 20 minutes";

                        else if ($min_diff < 20 && $min_diff >= 15)
                            $message2 = "You have a scheduled car wash within 15 minutes";

                        else if ($min_diff < 15 && $min_diff >= 10)
                            $message2 = "You have a scheduled car wash within 10 minutes";


                        foreach ($agentdevices as $agdevice) {
                            //$message =  "You have a new scheduled wash request.";
                            $device_type = strtolower($agdevice['device_type']);
                            $notify_token = $agdevice['device_token'];
                            $alert_type = "strong";
                            $notify_msg = urlencode($message2);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }
            }
        }
    }

    public function actionschedulewashtenminalert() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $allschedwashes = Washingrequests::model()->findAll(array("condition" => "is_scheduled = 1 AND status = 0"));

        if (count($allschedwashes)) {
            foreach ($allschedwashes as $schedwash) {

                if ($schedwash->reschedule_time)
                    $scheduledatetime = $schedwash->reschedule_date . " " . $schedwash->reschedule_time;
                else
                    $scheduledatetime = $schedwash->schedule_date . " " . $schedwash->schedule_time;

                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);
                $min_diff = -1;
                if ($from_time >= $to_time) {
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                }
                if ($min_diff < 10) {

                    if ($schedwash->agent_id) {
                        $agent_detail = Agents::model()->findByPk($schedwash->agent_id);
                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $schedwash->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                        //$pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
                        //$message = $pushmsg[0]['message'];

                        if ($min_diff == 9)
                            $message2 = "You have a scheduled car wash within 9 minutes";
                        else if ($min_diff == 8)
                            $message2 = "You have a scheduled car wash within 8 minutes";
                        else if ($min_diff == 7)
                            $message2 = "You have a scheduled car wash within 7 minutes";
                        else if ($min_diff == 6)
                            $message2 = "You have a scheduled car wash within 6 minutes";
                        else if ($min_diff == 5)
                            $message2 = "You have a scheduled car wash within 5 minutes";
                        else if ($min_diff == 4)
                            $message2 = "You have a scheduled car wash within 4 minutes";
                        else if ($min_diff == 3)
                            $message2 = "You have a scheduled car wash within 3 minutes";
                        else if ($min_diff == 2)
                            $message2 = "You have a scheduled car wash within 2 minutes";
                        else if ($min_diff == 1)
                            $message2 = "You have a scheduled car wash within 1 minute";
                        else if (($min_diff <= 0) && (!$schedwash->network_error_push_sent)) {

                            $message2 = "You missed your appointment. This may affect your ratings.";

                            //Washingrequests::model()->updateByPk($schedwash->id, array("status" => 6, "washer_late_cancel" => 1));
                            Washingrequests::model()->updateByPk($schedwash->id, array("network_error_push_sent" => 1));

                            /* if($schedwash->transaction_id) {
                              if($schedwash->wash_request_position == 'real') $voidresult = Yii::app()->braintree->void_real($schedwash->transaction_id);
                              else $voidresult = Yii::app()->braintree->void($schedwash->transaction_id);
                              }
                             */

                            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $schedwash->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '19' ")->queryAll();
                            $message = $pushmsg[0]['message'];

                            if (count($clientdevices)) {
                                foreach ($clientdevices as $ctdevice) {
                                    //$message =  "You have a new scheduled wash request.";
                                    $device_type = strtolower($ctdevice['device_type']);
                                    $notify_token = $ctdevice['device_token'];
                                    $alert_type = "strong";
                                    $notify_msg = urlencode($message);

                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }
                        }

                        if (!$agent_detail->block_washer) {
                            foreach ($agentdevices as $agdevice) {
                                //$message =  "You have a new scheduled wash request.";
                                $device_type = strtolower($agdevice['device_type']);
                                $notify_token = $agdevice['device_token'];
                                $alert_type = "strong";
                                $notify_msg = urlencode($message2);

                                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                file_put_contents("android_notificaiton.log", $notifyurl, FILE_APPEND);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                if ($notify_msg)
                                    $notifyresult = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                    }
                    else {

                        //if (($min_diff <= 0) && ($schedwash->schedule_time) && (!$schedwash->network_error_push_sent)) {
                        if (($min_diff <= 0) && ($schedwash->schedule_time)) {

                            Washingrequests::model()->updateByPk($schedwash->id, array("status" => 5, "no_washer_cancel" => 1, "network_error_push_sent" => 1));

                            if ($schedwash->transaction_id) {
                                if ($schedwash->wash_request_position == 'real')
                                    $voidresult = Yii::app()->braintree->void_real($schedwash->transaction_id);
                                else
                                    $voidresult = Yii::app()->braintree->void($schedwash->transaction_id);
                            }

                            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $schedwash->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '61' ")->queryAll();
                            $message = $pushmsg[0]['message'];

                            if (count($clientdevices)) {
                                foreach ($clientdevices as $ctdevice) {
                                    //$message =  "You have a new scheduled wash request.";
                                    $device_type = strtolower($ctdevice['device_type']);
                                    $notify_token = $ctdevice['device_token'];
                                    $alert_type = "strong";
                                    $notify_msg = urlencode($message);

                                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    if ($notify_msg)
                                        $notifyresult = curl_exec($ch);
                                    curl_close($ch);
                                }
                            }

                            $customers_details = Customers::model()->findByPk($schedwash->customer_id);
                            $cust_firstname = $customers_details->first_name;
                            /* $cust_name = explode(" ", trim($customers_details->customername));

                              $cust_firstname = '';
                              $cust_firstname = $cust_name[0]; */

                            $from = Vargas::Obj()->getAdminFromEmail();

                            $message = "<h2 style='margin-top: 10px;'>Greetings " . $cust_firstname . ",</h2>";

                            $message .= "<p style='color: #333;'>We apologize for not being able to ensure services were provided recently. We are a growing organization and are doing everything that we can to ensure that we meet the enormous demand that exists for the on-demand car wash industry. As a courtesy, we would like you to use the PROMO CODE: SAVE, which you can use to save on your next service. You will not be charged for services that were not provided.</p>";


                            $message .= "<p style='height: 0px;'>&nbsp;</p>
					<p style='color: #333;'>Thank you for your consideration.</p>
					<p style='color: #333;'>MobileWash</p>";

                            Vargas::Obj()->SendMail($customers_details->email, $from, $message, "MobileWash", 'mail-receipt');

                            $logdata = array(
                                'agent_id' => $schedwash->agent_id,
                                'wash_request_id' => $schedwash->id,
                                'agent_company_id' => 0,
                                'action' => 'scheduleauto-canceled',
                                'action_date' => date('Y-m-d H:i:s'));

                            Yii::app()->db->createCommand()->insert('activity_logs', $logdata);

                            if (APP_ENV == 'real') {

                                $kartapiresult = $this->washingkart($schedwash->id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                                $kartdata = json_decode($kartapiresult);

                                $this->layout = "xmlLayout";

                                //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                                $account_sid = TWILIO_SID;
                                $auth_token = TWILIO_AUTH_TOKEN;
                                $client = new Services_Twilio($account_sid, $auth_token);

                                $cust_last_wash_check = Washingrequests::model()->findByAttributes(array('customer_id' => $customers_details->id, 'status' => 4), array('order' => 'id DESC'));
                                $last_order_days = "N/A";
                                if (count($cust_last_wash_check)) {
                                    $current_time = strtotime(date('Y-m-d H:i:s'));

                                    $last_order_time = strtotime($cust_last_wash_check->order_for);
                                    if ($current_time > $last_order_time) {
                                        $last_order_days = floor(($current_time - $last_order_time) / (60 * 60 * 24));
                                    }
                                } else {
                                    $last_order_days = "N/A";
                                }

                                $mobile_receipt = '';

                                foreach ($kartdata->vehicles as $ind => $vehicle) {
                                    $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                                    if ($vehicle->surge_vehicle_fee > 0) {
                                        $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->extclaybar_vehicle_fee > 0) {
                                        $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->waterspotremove_vehicle_fee > 0) {
                                        $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->exthandwax_vehicle_fee > 0) {
                                        $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->pet_hair_fee > 0) {
                                        $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                                    }

                                    if ($vehicle->lifted_vehicle_fee > 0) {
                                        $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                                        $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->upholstery_vehicle_fee > 0) {
                                        $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                                    }

                                    if ($vehicle->floormat_vehicle_fee > 0) {
                                        $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                                    }

                                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                                        $mobile_receipt .= "Promo: " . $coupon_code . " -$" . number_format($coupon_amount, 2) . "\r\n";
                                    }


                                    if ($vehicle->fifth_wash_discount > 0) {
                                        $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                                    }

                                    if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                                        $mobile_receipt .= "Bundle -$1.00\r\n";
                                    }

                                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                                        $mobile_receipt .= "Bundle -$1.00\r\n";
                                    }



                                    $mobile_receipt .= "------\r\n";
                                }


                                if ($kartdata->tip_amount > 0) {

                                    $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
                                }

                                if ($kartdata->wash_now_fee > 0) {

                                    $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
                                }

                                if ($kartdata->wash_later_fee > 0) {

                                    $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
                                }

                                if ($schedwash->vip_coupon_code) {

                                    $vip_coupon_check = VipCouponCodes::model()->findByAttributes(array("fullcode" => $schedwash->vip_coupon_code));

                                    $mobile_receipt .= $vip_coupon_check->package_name . "\r\n";
                                }

                                if ($schedwash->schedule_total) {
                                    $mobile_receipt .= "Total: $" . $schedwash->schedule_total . "\r\n";
                                } else {
                                    $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
                                }

                                $mobile_receipt .= "Washes: " . $customers_details->total_wash . "\r\n";


                                $message = "Scheduled Wash Auto Cancelled #000" . $schedwash->id . "- " . date('M d', strtotime($schedwash->schedule_date)) . " @ " . $schedwash->schedule_time . "\r\n" . $customers_details->first_name . " " . $customers_details->last_name . "\r\n" . $customers_details->contact_number . "\r\n" . $schedwash->address . " (" . $schedwash->address_type . ")\r\nDays Since Last Order: " . $last_order_days . "\r\n------\r\n" . $mobile_receipt;


                                try {
                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '8183313631',
                                        'From' => '+13103128070',
                                        'Body' => $message,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }

                                try {
                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '3109999334',
                                        'From' => '+13103128070',
                                        'Body' => $message,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }

                                try {
                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => '3103442534',
                                        'From' => '+13103128070',
                                        'Body' => $message,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }
                            }

                            /* $logdata = array(
                              'wash_request_id' => $schedwash->id,
                              'action' => 'scheduleauto-canceled',
                              'action_date' => date('Y-m-d H:i:s'));

                              Yii::app()->db->createCommand()->insert('activity_logs', $logdata); */
                        }
                    }
                }
            }
        }
    }

    public function actioncustomerwashfeedbackemails() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }


        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }


        $allschedwashes = Washingrequests::model()->findAllByAttributes(array('status' => 4, 'is_feedback_email_sent' => 0));

        if (count($allschedwashes)) {
            foreach ($allschedwashes as $schedwash) {

                $checkfeedbacks = Washingfeedbacks::model()->findAllByAttributes(array('wash_request_id' => $schedwash->id, 'customer_id' => $schedwash->customer_id), array('condition' => "customer_comments != ''"));


                if (count($checkfeedbacks))
                    continue;
                /* if($schedwash->schedule_time){
                  if($schedwash->reschedule_time) $scheduledatetime = $schedwash->reschedule_date." ".$schedwash->reschedule_time;
                  else $scheduledatetime = $schedwash->schedule_date." ".$schedwash->schedule_time;
                  }
                  else{
                  $scheduledatetime = $schedwash->complete_order;
                  } */

                $scheduledatetime = $schedwash->order_for;
                $to_time = strtotime(date('Y-m-d g:i A'));
                $from_time = strtotime($scheduledatetime);

                $min_diff = 0;

                if ($to_time >= $from_time) {
                    $min_diff = round(($to_time - $from_time) / 60, 2);
                }

                echo $min_diff . " mins #" . $schedwash->id . "<br>";

                if ($min_diff >= 180) {

                    $custdetail = Customers::model()->findByPk($schedwash->customer_id);
//$cname = explode(" ",$custdetail->customername);
                    $cname = $custdetail->first_name;

                    $from = Vargas::Obj()->getAdminFromEmail();

                    $message = "<div class='block-content' style='background: #fff; text-align: left;'>
<h2 style='text-align:center;font-size: 28px;margin-top:0; margin-bottom: 0;text-transform: uppercase;'>Customer Feedback</h2>
<p style='text-align:center;font-size:18px;margin-bottom:0;margin-top: 10px;'><b>Order Number:</b> #0000" . $schedwash->id . "</p>
<p style='text-align:center;font-size: 24px;margin-top: 25px;'>Hello " . $cname . "</p>
<h2 style='text-align:center;font-size: 24px;line-height: normal;'>How was your experience with our<br>MobileWasher?</h2>
<p style='text-align:center;line-height: 24px;margin-top: 25px;'>We would like to make sure that you always have a great experience. Please help us make it the best service possible by letting us know how we did today.</p>";

                    if (APP_ENV == 'real')
                        $message .= "<p style='text-align:center; margin-top: 25px; margin-bottom: 3px;'><a style='background: #30a0ff; color: #fff; padding: 10px; display: block; width: 210px; margin: 0 auto; text-decoration: none; font-weight: bold; font-size: 20px; border-radius: 15px;' href='" . WEBSITE_URL . "/feedback/?order_id=" . $this->aes256cbc_crypt($schedwash->id, 'e', AES256CBC_API_PASS) . "'>LEAVE FEEDBACK</a></p>";
                    else
                        $message .= "<p style='text-align:center; margin-top: 25px; margin-bottom: 3px;'><a style='background: #30a0ff; color: #fff; padding: 10px; display: block; width: 210px; margin: 0 auto; text-decoration: none; font-weight: bold; font-size: 20px; border-radius: 15px;' href='" . WEBSITE_URL . "/feedback/?order_id=" . $this->aes256cbc_crypt($schedwash->id, 'e', AES256CBC_API_PASS) . "&pt=dev'>LEAVE FEEDBACK</a></p>";

                    $message .= "<div style='margin-top: 20px; text-align: center;'>
<a style='display: inline-block; width: 48px; height: 48px; margin-right: 6px;' href='https://www.facebook.com/getmobilewash'><div style='background: transparent url(" . ROOT_URL . "/images/facebook_circle.png) no-repeat top left; background-size: 48px !important; display: block; width: 48px; height: 48px;'></div></a>
<a style='display: inline-block; width: 48px; height: 48px;' href='https://www.instagram.com/getmobilewash/'><div style='background: transparent url(" . ROOT_URL . "/images/instagram_circle.png) no-repeat top left; background-size: 48px !important; display: block; width: 48px; height: 48px;'></div></a>
<p style='padding: 10px 10px;border:1px solid #0880e6;text-align:center;width: 100%;margin-left:auto;margin-right:auto;margin-bottom:0;border-radius:8px;line-height:22px;box-sizing: border-box;'><span style='font-size: 18px;margin-bottom: 10px;display: block;'><strong>By providing feedback and following us on Facebook or Instagram, you will be entered for our weekly Free Deluxe Wash drawing!</strong></span><span style='color: red;'>Please provide your Social Media handle(s) along with your order number for selection process.</span><span style='font-size: 16px;margin-top: 10px;display: block; color: red;'><strong>ALL WINNERS WILL BE ANNOUNCED ON FACEBOOK AND INSTAGRAM.</strong></span></p></div>";


                    Vargas::Obj()->SendMail($custdetail->email, $from, $message, "Customer Feedback - Order #0000" . $schedwash->id, 'mail-feedback');


                    Washingrequests::model()->updateByPk($schedwash->id, array('is_feedback_email_sent' => 1));
                }
            }
        }
    }

    public function actionwashingaddons() {

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
            'result' => $result,
            'response' => $response,
            'addons' => $addons
        );

        echo json_encode($json);
        die();
    }

    public function actionwashingaddonsnew() {

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

        $response = "addon details";
        $result = "true";
        $addons = array();
        $package = '';
        $package = Yii::app()->request->getParam('package');
        $vehicle_type = '';
        $vehicle_type = Yii::app()->request->getParam('vehicle_type');

        if ($package && $vehicle_type) {
            if ($package == 'Express')
                $expaddons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = :package AND vehicle_type = :vehicle_type")->bindValue(':package', $package, PDO::PARAM_STR)->bindValue(':vehicle_type', $vehicle_type, PDO::PARAM_STR)->queryAll();
            if ($package == 'Deluxe')
                $deladdons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = :package AND vehicle_type = :vehicle_type")->bindValue(':package', $package, PDO::PARAM_STR)->bindValue(':vehicle_type', $vehicle_type, PDO::PARAM_STR)->queryAll();
            if ($package == 'Premium')
                $premaddons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = :package AND vehicle_type = :vehicle_type")->bindValue(':package', $package, PDO::PARAM_STR)->bindValue(':vehicle_type', $vehicle_type, PDO::PARAM_STR)->queryAll();
        }
        else {
            $expaddons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = 'Express'")->queryAll();
            $deladdons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = 'Deluxe'")->queryAll();
            $premaddons = Yii::app()->db->createCommand("SELECT * FROM washing_plans_addons WHERE package = 'Premium'")->queryAll();
        }


        if (count($expaddons)) {

            foreach ($expaddons as $expaddon) {
                $par_send = '';
                if ($expaddon['title'] == 'Exterior Hand Wax')
                    $par_send = 'exthandwax_vehicles';
                if ($expaddon['title'] == 'Exterior Dressing')
                    $par_send = 'extplasticdressing_vehicles';
                if ($expaddon['title'] == 'Clay Bar')
                    $par_send = 'extclaybar_vehicles';
                if ($expaddon['title'] == 'Water Spot')
                    $par_send = 'waterspotremove_vehicles';
                if ($expaddon['title'] == 'Lifted Truck')
                    $par_send = 'lifted_vehicles';

                $addons['express'][] = array('id' => $expaddon['id'], 'title' => $expaddon['title'], 'fulltitle' => $expaddon['fulltitle'], 'desc' => $expaddon['description'], 'par_send' => $par_send, 'package' => $expaddon['package'], 'washtime' => $expaddon['wash_time'], 'price' => $expaddon['price'], 'vehicle_type' => $expaddon['vehicle_type']);
            }
        }

        if (count($deladdons)) {

            foreach ($deladdons as $deladdon) {
                $par_send = '';
                if ($deladdon['title'] == 'Exterior Hand Wax')
                    $par_send = 'exthandwax_vehicles';
                if ($deladdon['title'] == 'Exterior Dressing')
                    $par_send = 'extplasticdressing_vehicles';
                if ($deladdon['title'] == 'Clay Bar')
                    $par_send = 'extclaybar_vehicles';
                if ($deladdon['title'] == 'Water Spot')
                    $par_send = 'waterspotremove_vehicles';
                if ($deladdon['title'] == 'Pet Hair')
                    $par_send = 'pet_hair_vehicles';
                if ($deladdon['title'] == 'Lifted Truck')
                    $par_send = 'lifted_vehicles';
                if ($deladdon['title'] == 'Upholstery Conditioning')
                    $par_send = 'upholstery_vehicles';
                if ($deladdon['title'] == 'Floor Mat Cleaning')
                    $par_send = 'floormat_vehicles';

                $addons['deluxe'][] = array('id' => $deladdon['id'], 'title' => $deladdon['title'], 'fulltitle' => $deladdon['fulltitle'], 'desc' => $deladdon['description'], 'par_send' => $par_send, 'package' => $deladdon['package'], 'washtime' => $deladdon['wash_time'], 'price' => $deladdon['price'], 'vehicle_type' => $deladdon['vehicle_type']);
            }
        }

        if (count($premaddons)) {

            foreach ($premaddons as $premaddon) {
                $par_send = '';
                if ($premaddon['title'] == 'Exterior Hand Wax')
                    $par_send = 'exthandwax_vehicles';
                if ($premaddon['title'] == 'Exterior Dressing')
                    $par_send = 'extplasticdressing_vehicles';
                if ($premaddon['title'] == 'Clay Bar')
                    $par_send = 'extclaybar_vehicles';
                if ($premaddon['title'] == 'Water Spot')
                    $par_send = 'waterspotremove_vehicles';
                if ($premaddon['title'] == 'Pet Hair')
                    $par_send = 'pet_hair_vehicles';
                if ($premaddon['title'] == 'Lifted Truck')
                    $par_send = 'lifted_vehicles';
                if ($premaddon['title'] == 'Floor Mat Cleaning')
                    $par_send = 'floormat_vehicles';

                $addons['premium'][] = array('id' => $premaddon['id'], 'title' => $premaddon['title'], 'fulltitle' => $premaddon['fulltitle'], 'desc' => $premaddon['description'], 'par_send' => $par_send, 'package' => $premaddon['package'], 'washtime' => $premaddon['wash_time'], 'price' => $premaddon['price'], 'vehicle_type' => $premaddon['vehicle_type']);
            }
        }


        $json = array(
            'result' => $result,
            'response' => $response,
            'addons' => $addons
        );

        echo json_encode($json);
        die();
    }

    public function actionwasherarrivaleta() {

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

        $agent_id = Yii::app()->request->getParam('agent_id');
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $eta = '';
        $distance = '';
        $distance_mile = '';

        $json = array();
        $result = 'false';
        $response = 'Pass the required parameters';


        if (isset($agent_id) && !empty($agent_id) && isset($wash_request_id) && !empty($wash_request_id)) {

            if (AES256CBC_STATUS == 1) {
                $agent_id = $this->aes256cbc_crypt($agent_id, 'd', AES256CBC_API_PASS);
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $agent_id_check = Agents::model()->findByAttributes(array("id" => $agent_id));
            $wash_id_check = Washingrequests::model()->findByAttributes(array("id" => $wash_request_id));

            if (!$agent_id_check) {
                $response = 'Invalid agent';
            } else if (!$wash_id_check) {
                $response = 'Invalid wash request';
            } else {
                $result = 'true';
                $response = 'washer arrival eta and distance';

                $agent_loc = AgentLocations::model()->findByAttributes(array('agent_id' => $agent_id));

                $now = time();
                $dept_time = date(strtotime('+5 minutes', $now));

                $geourl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" .
                        $agent_loc->latitude . "," .
                        $agent_loc->longitude . "&destinations=" . $wash_id_check->latitude . "," . $wash_id_check->longitude . "&mode=driving&traffic_model=best_guess&departure_time=" . $dept_time . "&language=en-EN&sensor=false&key=AIzaSyBKtA-rMuYePlrl3O5Z52T-4LiEVl64Z9Y";

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $geourl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch,CURLOPT_HEADER, false);

                $georesult = curl_exec($ch);
                curl_close($ch);
                $geojsondata = json_decode($georesult);

                $distance = $geojsondata->rows[0]->elements[0]->distance->value;
                $distance_mile = round($distance * 0.000621371, 2);
                $eta = floor($geojsondata->rows[0]->elements[0]->duration_in_traffic->value / 60);
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'eta' => $eta,
            'distance' => $distance,
            'distance_mile' => $distance_mile
        );

        echo json_encode($json);
        die();
    }

    public function actiongetvehicleaddons() {

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
        $response = 'Pass the required parameters';

        $allplans = Yii::app()->db->createCommand()
                ->select('*')
                ->from('washing_plans_addons')
                ->queryAll();

        if (!count($allplans)) {
            $response = 'No plans exists';
            $result = 'false';
        } else {
            $response = 'washing plans addons';
            $result = 'true';
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'plans' => $allplans
        );

        echo json_encode($json);
        die();
    }

    public function actionupdatevehicleaddonsplan() {

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

        $fulltitles = Yii::app()->request->getParam('fulltitles');
        $descs = Yii::app()->request->getParam('descs');
        $wash_times = Yii::app()->request->getParam('wash_times');
        $prices = Yii::app()->request->getParam('prices');

        $result = 'false';
        $response = 'Pass the required parameters';

        $fulltitles_arr = explode("|", $fulltitles);
        $descs_arr = explode("|", $descs);
        $wash_times_arr = explode("|", $wash_times);
        $prices_arr = explode("|", $prices);


        for ($i = 1, $j = 0; $i <= count($prices_arr); $i++, $j++) {
            Yii::app()->db->createCommand("UPDATE washing_plans_addons SET fulltitle=:fulltitle, description=:description, wash_time=:wash_time, price=:price WHERE id = '$i'")
                    ->bindValue(':fulltitle', $fulltitles_arr[$j], PDO::PARAM_STR)
                    ->bindValue(':description', $descs_arr[$j], PDO::PARAM_STR)
                    ->bindValue(':wash_time', $wash_times_arr[$j], PDO::PARAM_STR)
                    ->bindValue(':price', $prices_arr[$j], PDO::PARAM_STR)
                    ->execute();
        }



        $response = 'update success';
        $result = 'true';


        $json = array(
            'result' => $result,
            'response' => $response
        );

        echo json_encode($json);
        die();
    }

    public function actioncompleted_wash_feedback_check() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $allwashes = Washingrequests::model()->findAll(array("condition" => "is_feedback_sent = 0 AND status = 4"));

        if (count($allwashes)) {
            foreach ($allwashes as $wash) {



                $washcompletetime = $wash->complete_order;

                $from_time = strtotime(date('Y-m-d g:i A'));
                $to_time = strtotime($washcompletetime);
                $min_diff = 0;
                if ($from_time > $to_time) {
                    $min_diff = round(($from_time - $to_time) / 60, 2);
                }

                if ($min_diff >= 30) {

                    $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/customerfeedback");
                    $data = array('customer_id' => $wash->customer_id, 'wash_request_id' => $wash->id, 'comments' => '', 'ratings' => '5.00', 'feedback_source' => 'cron', "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $cust_feedback_result = curl_exec($handle);
                    curl_close($handle);


                    $handle = curl_init(ROOT_URL . "/api/index.php?r=customers/customerpayment");
                    $data = array('customer_id' => $wash->customer_id, 'agent_id' => $wash->agent_id, 'wash_request_id' => $wash->id, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $cust_pay_result = curl_exec($handle);
                    curl_close($handle);
                    //$kartdetails = json_decode($kartresult);


                    $handle = curl_init(ROOT_URL . "/api/index.php?r=washing/agentfeedback");
                    $data = array('agent_id' => $wash->agent_id, 'wash_request_id' => $wash->id, 'comments' => '', 'ratings' => '5.00', 'feedback_source' => 'cron', "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                    curl_setopt($handle, CURLOPT_POST, true);
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                    $cust_feedback_result = curl_exec($handle);
                    curl_close($handle);

                    Washingrequests::model()->updateByPk($wash->id, array('is_feedback_sent' => 1, 'is_order_receipt_sent' => 1));

                    Customers::model()->updateAll(array('online_status' => 'offline', 'device_token' => ''), 'id=:id', array(':id' => $wash->customer_id));
                    Agents::model()->updateAll(array('device_token' => '', 'status' => 'offline', 'available_for_new_order' => 0), 'id=:id', array(':id' => $wash->agent_id));
                }
            }
        }
    }

    public function actionondemandwashalert() {

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

        $allwashes = Washingrequests::model()->findAllByAttributes(array('is_scheduled' => 0, 'status' => 0, 'ondemand_create_push_sent' => 0));


        if (count($allwashes)) {
            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '28' ")->queryAll();
            foreach ($allwashes as $wash) {

                /* ------- get nearest agents --------- */

                $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
                $data = array('wash_request_id' => $wash->id, "key" => API_KEY, "api_password" => AES256CBC_API_PASS, 'ignore_offline' => 1, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($handle);
                curl_close($handle);
                $nearagentsdetails = json_decode($output);

                /* ------- get nearest agents end --------- */

                if ($nearagentsdetails->result == 'true') {

                    $message = $pushmsg[0]['message'];
                    foreach ($nearagentsdetails->nearest_agents as $agid => $nearagentdis) {

                        $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agid . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                        foreach ($agentdevices as $agdevice) {

                            $device_type = strtolower($agdevice['device_type']);
                            $notify_token = $agdevice['device_token'];
                            $alert_type = "default";
                            $notify_msg = urlencode($message);

                            $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                            //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $notifyurl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            if ($notify_msg)
                                $notifyresult = curl_exec($ch);
                            curl_close($ch);
                        }
                    }

                    Washingrequests::model()->updateByPk($wash->id, array("ondemand_create_push_sent" => 1));
                }
            }
        }
    }

    public function actioncurrentwashondemandalert() {

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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        if (AES256CBC_STATUS == 1) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }
        $wash_id_check = Washingrequests::model()->findByPk($wash_request_id);
        if (count($wash_id_check) && (!$wash_id_check->ondemand_create_push_sent)) {
            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '28' ")->queryAll();
            //$app_settings =  Yii::app()->db->createCommand("SELECT * FROM `app_settings`")->queryAll();
            $wash_now_fee = number_format($wash_id_check->wash_now_fee * .75, 2);


            /* ------- get nearest agents --------- */

            $handle = curl_init(ROOT_URL . "/api/index.php?r=agents/getnearestagents");
            $data = array('wash_request_id' => $wash_request_id, 'ignore_offline' => 1, "api_password" => AES256CBC_API_PASS, "key" => API_KEY, "api_token" => $api_token, "t1" => $t1, "t2" => $t2, "user_type" => $user_type, "user_id" => $user_id);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($handle);
            curl_close($handle);
            $nearagentsdetails = json_decode($output);
            /* ------- get nearest agents end --------- */

            if ($nearagentsdetails->result == 'true') {
                $mw_from_mumbers = array("+18335674415", "+18338107607", "+18335674490", "+18335673979", "+18339568060", "+18335673609", "+18335674492", "+18335673706", "+18337896894", "+18335674230");
                $fromindex = 0;
                $message = $pushmsg[0]['message'];
                foreach ($nearagentsdetails->nearest_agents as $agid => $nearagentdis) {
                    $current_mile = 0;
                    $message2 = '';
                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agid . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                    $mins_since_last_use = 0;

                    $mins_since_last_use = round((time() - strtotime($agentdevices[0]['last_used'])) / 60, 2);

                    $current_mile = round($nearagentdis);

                    if ($current_mile < 1)
                        $current_mile = 1;

                    $message2 = str_replace(array("[CITY]", "[WASHER_TOTAL]"), array(strtoupper($wash_id_check->city), number_format($wash_id_check->agent_total, 2)), $message);
                    foreach ($agentdevices as $agdevice) {

                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "default";

                        $notify_msg = urlencode($message2);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type . "&notification_type=wash_now_notify";
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }

                    if ($current_mile <= 10) {
                        $agent_det = Agents::model()->findByPk($agid);
                        if ((count($agent_det)) && ($agent_det->phone_number) && (!$agent_det->block_washer) && ($agent_det->sms_control) && ($mins_since_last_use < 10080) && ($agent_det->available_for_new_order > 0)) {
                            if ((APP_ENV == 'real')) {
                                $this->layout = "xmlLayout";

                                //include($phpExcelPath . DIRECTORY_SEPARATOR . 'CList.php');

                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                                $account_sid = TWILIO_SID;
                                $auth_token = TWILIO_AUTH_TOKEN;

                                $client = new Services_Twilio($account_sid, $auth_token);

                                try {
                                    $sendmessage = $client->account->messages->create(array(
                                        'To' => $agent_det->phone_number,
                                        'From' => $mw_from_mumbers[$fromindex],
                                        'Body' => $message2,
                                    ));
                                } catch (Services_Twilio_RestException $e) {
                                    //echo  $e;
                                }
                            }
                        }
                    }

                    $fromindex++;
                    if ($fromindex > 9)
                        $fromindex = 0;
                }

                Washingrequests::model()->updateByPk($wash_request_id, array("ondemand_create_push_sent" => 1));
                //file_put_contents("ondemandnotify_log", $wash_request_id . "\r\n", FILE_APPEND);
            }
        }
    }

    public function actioncurrentwashschedulealert() {

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

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');
        $wash_request_id_org = Yii::app()->request->getParam('wash_request_id');


        if (AES256CBC_STATUS == 1) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }

        $wash_id_check = Washingrequests::model()->findByPk($wash_request_id);

        if (count($wash_id_check) && (!$wash_id_check->is_create_schedulewash_push_sent)) {
            $allagents = Agents::model()->findAll(array("condition" => "block_washer =  0"));
            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '8' ")->queryAll();
            $message = $pushmsg[0]['message'];
            $message2 = str_replace(array("[CITY]", "[WASHER_TOTAL]"), array(strtoupper($wash_id_check->city), number_format($wash_id_check->agent_total, 2)), $message);


            foreach ($allagents as $agent) {

                $get_notify = 1;
                $agent_loc_obj = AgentLocations::model()->findByAttributes(array('agent_id' => $agent->id));
                if (count($agent_loc_obj)) {
                    $theta = $wash_id_check->longitude - $agent_loc_obj->longitude;
                    $dist = sin(deg2rad($wash_id_check->latitude)) * sin(deg2rad($agent_loc_obj->latitude)) + cos(deg2rad($wash_id_check->latitude)) * cos(deg2rad($agent_loc_obj->latitude)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    if ($miles > 50)
                        $get_notify = 0;
                }

                if ($get_notify) {
                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $agent->id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();
                    foreach ($agentdevices as $agdevice) {

                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "schedule";

                        $notify_msg = urlencode($message2);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type . "&wash_id=" . $wash_request_id_org;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }


            Washingrequests::model()->updateByPk($wash_request_id, array("is_create_schedulewash_push_sent" => 1));
        }
    }

    public function actionwash30secondrunning() {

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
        $response = 'Right now no washer';
        $wash_id_check = Yii::app()->db->createCommand("SELECT order_temp_assigned FROM washing_requests WHERE status = 0 AND order_temp_assigned != 0 ORDER BY id DESC LIMIT 1")->queryAll();

        if (count($wash_id_check) > 0) {

            $result = 'true';
            $response = 'get washer 30 second request';

            $washer_id = $wash_id_check[0]['order_temp_assigned'];
        }

        $json = array(
            'result' => $result,
            'washer_id' => $washer_id,
            'response' => $response,
        );

        echo json_encode($json);
        die;
    }

    public function actionwash30secondtimer() {

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
        $response = 'enter wash id';
        $wash_request_id = Yii::app()->request->getParam('wash_request_id');

        if (AES256CBC_STATUS == 1) {
            $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
        }


        $wash_id_check = Washingrequests::model()->findByPk($wash_request_id);
        $time_left = 0;
        if (!count($wash_id_check)) {
            $result = 'false';
            $response = 'invalid wash id';
        } else {
            $result = 'true';
            $response = 'wash 30 second time';

            $timer_start_point = 33;
            $time_left = $timer_start_point - (time() - strtotime($wash_id_check->upgrade_requested_at));
            if ($time_left < 0)
                $time_left = 0;
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'time_left' => $time_left
        );

        echo json_encode($json);
        die();
    }

    public function actionpendingpaymentwashsettledtidcheck() {

        if (Yii::app()->request->getParam('key') != API_KEY_CRON) {
            echo "Invalid api key";
            die();
        }


        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip != MW_SERVER_IP) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        $pendingwashes = Washingrequests::model()->findAll(array("condition" => "status = 4 AND washer_payment_status != 1 AND washer_payment_status != 3"));

        if (count($pendingwashes)) {
            foreach ($pendingwashes as $wash) {
                $customer_check = Customers::model()->findByPk($wash['customer_id']);

                if ($customer_check->client_position == 'real')
                    $Bresult = Yii::app()->braintree->searchsettletransactionsbyorderid_real($wash['id']);
                else
                    $Bresult = Yii::app()->braintree->searchsettletransactionsbyorderid($wash['id']);

                if ($Bresult['success'] == 1) {

                    foreach ($Bresult['all_transactions'] as $transaction) {
                        if ($transaction['status'] == 'settled') {
                            Washingrequests::model()->updateByPk($wash['id'], array("transaction_id" => $transaction['id'], 'failed_transaction_id' => '', 'washer_payment_status' => 1));
                            break;
                        }
                    }
                }
            }
        }
    }

    public function actionarrivednotification() {

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
        $response = 'Pass the required parameters';

        $washer_request_id = Yii::app()->request->getParam('washer_request_id');

        if (!empty($washer_request_id) && isset($washer_request_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $washer_request_id = $this->aes256cbc_crypt($washer_request_id, 'd', AES256CBC_API_PASS);
            }

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $washer_request_id));

            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();


            /* --- notification call --- */

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '42' ")->queryAll();
            $message = $pushmsg[0]['message'];

            foreach ($clientdevices as $ctdevice) {

                //echo $agentdetails['mobile_type'];
                $device_type = strtolower($ctdevice['device_type']);
                $notify_token = $ctdevice['device_token'];
                //$alert_type = "schedule";
                $alert_type = "buzz";
                $notify_msg = urlencode($message);

                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if ($notify_msg)
                    $notifyresult = curl_exec($ch);
                curl_close($ch);
            }
            $result = 'true';
            $response = 'notification sent';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actioncompletnotification() {

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
        $response = 'Pass the required parameters';

        $washer_request_id = Yii::app()->request->getParam('washer_request_id');

        if (!empty($washer_request_id) && isset($washer_request_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $washer_request_id = $this->aes256cbc_crypt($washer_request_id, 'd', AES256CBC_API_PASS);
            }

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $washer_request_id));

            $check_admin_cancel = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE wash_request_id = " . $washer_request_id . " AND action IN('completejob') AND admin_username != '' LIMIT 1")->queryAll();
            $admin_wash_complete = 0;
            if (count($check_admin_cancel) > 0) {
                $admin_wash_complete = 1;
            }

            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();


            /* --- notification call --- */

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '43' ")->queryAll();
            $message = $pushmsg[0]['message'];
            if ($admin_wash_complete == 0) {
                foreach ($clientdevices as $ctdevice) {

                    //echo $agentdetails['mobile_type'];
                    $device_type = strtolower($ctdevice['device_type']);
                    $notify_token = $ctdevice['device_token'];
                    //$alert_type = "schedule";
                    $alert_type = "buzz";
                    $notify_msg = urlencode($message);

                    $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                    //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $notifyurl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    if ($notify_msg)
                        $notifyresult = curl_exec($ch);
                    curl_close($ch);
                }
            }
            $result = 'true';
            $response = 'notification sent';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionarrivelagent10mint() {

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
        $response = 'Pass the required parameters';

        $washer_request_id = Yii::app()->request->getParam('washer_request_id');

        if (!empty($washer_request_id) && isset($washer_request_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $washer_request_id = $this->aes256cbc_crypt($washer_request_id, 'd', AES256CBC_API_PASS);
            }

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $washer_request_id));
            $agent_det = Agents::model()->findByPk($wrequest_id_check->agent_id);
            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wrequest_id_check->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '49' ")->queryAll();

            $message = $pushmsg[0]['message'];

            foreach ($agentdevices as $agdevice) {

                $device_type = strtolower($agdevice['device_type']);
                $notify_token = $agdevice['device_token'];
                $alert_type = "schedule";

                $notify_msg = urlencode($message);

                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if ($notify_msg)
                    $notifyresult = curl_exec($ch);
                curl_close($ch);
            }



            $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $wrequest_id_check->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

            /* --- notification call --- */

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '50' ")->queryAll();
            $message = $pushmsg[0]['message'];

            foreach ($clientdevices as $ctdevice) {

                //echo $agentdetails['mobile_type'];
                $device_type = strtolower($ctdevice['device_type']);
                $notify_token = $ctdevice['device_token'];
                $alert_type = "schedule";
                $notify_msg = urlencode($message);

                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if ($notify_msg)
                    $notifyresult = curl_exec($ch);
                curl_close($ch);
            }
            $result = 'true';
            $response = 'notification sent';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actionarrivelagent20mint() {

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
        $response = 'Pass the required parameters';

        $washer_request_id = Yii::app()->request->getParam('washer_request_id');

        if (!empty($washer_request_id) && isset($washer_request_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $washer_request_id = $this->aes256cbc_crypt($washer_request_id, 'd', AES256CBC_API_PASS);
            }

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $washer_request_id));
            $agent_det = Agents::model()->findByPk($wrequest_id_check->agent_id);
            $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $wrequest_id_check->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

            $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '51' ")->queryAll();

            $message = $pushmsg[0]['message'];

            foreach ($agentdevices as $agdevice) {

                $device_type = strtolower($agdevice['device_type']);
                $notify_token = $agdevice['device_token'];
                $alert_type = "schedule";

                $notify_msg = urlencode($message);

                $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $notifyurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                if ($notify_msg)
                    $notifyresult = curl_exec($ch);
                curl_close($ch);
            }



            $result = 'true';
            $response = 'notification sent';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actioncustomernoresponse() {

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
        $response = 'please provide required parameters';

        $id = Yii::app()->request->getParam('id');
        $customer_id = Yii::app()->request->getParam('customer_id');
        $name = Yii::app()->request->getParam('name');
        $email = Yii::app()->request->getParam('email');
        $address = Yii::app()->request->getParam('address');
        $address_type = Yii::app()->request->getParam('address_type');
        $fee = Yii::app()->request->getParam('fee');
        $admin_username = '';
        $admin_username = Yii::app()->request->getParam('admin_username');
        $free_cancel = Yii::app()->request->getParam('free_cancel');
        $api_password = '';
        $api_password = Yii::app()->request->getParam('api_password');
        $company_cancel = 0;
        if (Yii::app()->request->getParam('company_cancel'))
            $company_cancel = Yii::app()->request->getParam('company_cancel');
        $cancel_price = 0;

        if ((isset($id) && !empty($id)) && (isset($customer_id) && !empty($customer_id)) && (isset($fee) && !empty($fee))) {

            if ((AES256CBC_STATUS == 1) && ($api_password != AES256CBC_API_PASS)) {
                $id = $this->aes256cbc_crypt($id, 'd', AES256CBC_API_PASS);
                $customer_id = $this->aes256cbc_crypt($customer_id, 'd', AES256CBC_API_PASS);
            }
            $result = 'true';
            $response = 'here';
            $order_exists = Washingrequests::model()->findByAttributes(array("id" => $id));
            $cust_exists = Customers::model()->findByAttributes(array("id" => $customer_id));
            $agent_det = Agents::model()->findByAttributes(array("id" => $order_exists->agent_id));
            if (!count($order_exists)) {
                $response = "Invalid order id";
            } else if (!count($cust_exists)) {
                $response = "Invalid customer id";
            } else if ($order_exists->agent_id == 0) {
                $response = "No Agent ID";
            } else if ($order_exists->status != 2) {
                $response = "Customers Not (Meet Washer/No Thanks) screen.";
            } else {

                /* ------- kart details ----------- */

                $kartapiresult = $this->washingkart($id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
                $kartdata = json_decode($kartapiresult);

                /* ------- kart details end ----------- */

                /*        if($order_exists->reschedule_time) $scheduledatetime = $order_exists->reschedule_date." ".$order_exists->reschedule_time;
                  else $scheduledatetime = $order_exists->schedule_date." ".$order_exists->schedule_time;
                  //echo date('Y-m-d h:i A')."<br>";
                  $min_diff = 0;
                  $to_time = strtotime(date('Y-m-d g:i A'));
                  $from_time = strtotime($scheduledatetime);
                  if($from_time > $to_time){
                  $min_diff = round(($from_time - $to_time) / 60,2);
                  } */
                if ((!Yii::app()->request->getParam('free_cancel')) && ($order_exists->agent_id)) {

                    $braintree_id = '';
                    $braintree_id = $cust_exists->braintree_id;

                    if ($cust_exists->client_position == 'real')
                        $Bresult = Yii::app()->braintree->getCustomerById_real($braintree_id);
                    else
                        $Bresult = Yii::app()->braintree->getCustomerById($braintree_id);
                    if (count($Bresult->paymentMethods)) {
                        $result = 'true';
                        $response = 'payment methods';
                        foreach ($Bresult->paymentMethods as $index => $paymethod) {

                            if (($order_exists->status > 1) && ($order_exists->status <= 3)) {
                                $request_data = ['merchantAccountId' => $agent_det->bt_submerchant_id, 'serviceFeeAmount' => "5.00", 'amount' => $fee, 'paymentMethodToken' => $paymethod->token];
                                if ($cust_exists->client_position == 'real')
                                    $cancelresult = Yii::app()->braintree->transactToSubMerchant_real($request_data);
                                else
                                    $cancelresult = Yii::app()->braintree->transactToSubMerchant($request_data);
                            }
                            else {
                                $request_data = ['amount' => $fee, 'paymentMethodToken' => $paymethod->token, 'customer' => ['firstName' => $cust_exists->first_name . " " . $cust_exists->last_name,], 'billing' => ['firstName' => $cust_exists->first_name . " " . $cust_exists->last_name]];

                                if ($cust_exists->client_position == 'real')
                                    $cancelresult = Yii::app()->braintree->sale_real($request_data);
                                else
                                    $cancelresult = Yii::app()->braintree->sale($request_data);
                            }



                            if (($cancelresult['success'] == 1)) {
                                /* if ($cust_exists->client_position == 'real')
                                  $cancelsettle = Yii::app()->braintree->submitforsettlement_real($cancelresult['transaction_id']);
                                  else
                                  $cancelsettle = Yii::app()->braintree->submitforsettlement($cancelresult['transaction_id']); */
                                $result = 'true';
                                $response = 'Order canceled';
                                $cancel_price = $fee;
                                $washer_cancel_fee = $fee - 5;
                                if (($order_exists->status > 1) && ($order_exists->status <= 3))
                                    Washingrequests::model()->updateByPk($id, array('status' => 7, 'order_canceled_at' => date("Y-m-d H:i:s"), 'cancel_fee' => $fee, 'company_cancel' => $company_cancel, 'washer_cancel_fee' => $washer_cancel_fee, 'canceled_washer_id' => $order_exists->agent_id, 'wash_begin' => date("Y-m-d H:i:s"), 'canceled_wash_transaction_id' => $cancelresult['transaction_id']));
                                else
                                    Washingrequests::model()->updateByPk($id, array('status' => 7, 'order_canceled_at' => date("Y-m-d H:i:s"), 'company_cancel' => $company_cancel, 'cancel_fee' => $fee, 'washer_cancel_fee' => $washer_cancel_fee, 'canceled_washer_id' => $order_exists->agent_id, 'wash_begin' => date("Y-m-d H:i:s"), 'canceled_wash_transaction_id' => $cancelresult['transaction_id']));

                                if ($order_exists->transaction_id) {
                                    if ($order_exists->wash_request_position == 'real')
                                        $voidresult = Yii::app()->braintree->void_real($order_exists->transaction_id);
                                    else
                                        $voidresult = Yii::app()->braintree->void($order_exists->transaction_id);
                                }

                                $is_cust_has_order = Yii::app()->db->createCommand("SELECT * FROM washing_requests WHERE customer_id=" . $order_exists->customer_id . " AND (status >= 0 AND status <= 4)")->queryAll();
                                if (!count($is_cust_has_order)) {
                                    Customers::model()->updateByPk($order_exists->customer_id, array("is_first_wash" => 0));
                                }

                                $from = Vargas::Obj()->getAdminFromEmail();
                                $sched_date = '';
                                $sched_time = '';

                                if (!$order_exists->is_scheduled) {

                                    if (strtotime($order_exists->order_for) == strtotime(date('Y-m-d'))) {
                                        $sched_date = 'Today';
                                    } else {
                                        $sched_date = date('M d', strtotime($order_exists->order_for));
                                    }
                                    $sched_time = date('g:i A', strtotime($order_exists->order_for));
                                } else {

                                    if ($order_exists->reschedule_time) {
                                        if (strtotime($order_exists->reschedule_date) == strtotime(date('Y-m-d'))) {
                                            $sched_date = 'Today';
                                        } else {
                                            $sched_date = date('M d', strtotime($order_exists->reschedule_date));
                                        }
                                        $sched_time = $order_exists->reschedule_time;
                                    } else {
                                        if (strtotime($order_exists->schedule_date) == strtotime(date('Y-m-d'))) {
                                            $sched_date = 'Today';
                                        } else {
                                            $sched_date = date('M d', strtotime($order_exists->schedule_date));
                                        }
                                        $sched_time = $order_exists->schedule_time;
                                    }
                                }
                                $message = '';
                                $subject = 'Order Receipt - #000' . $id;
                                //$message = "Hello ".$customername.",<br/><br/>Welcome to Mobile wash!";
                                $message = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Customer No Response</h2>";
                                if (!$order_exists->is_scheduled)
                                    $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>On-demand order for " . $sched_date . " @ " . $sched_time . "</p>";
                                else
                                    $message .= "<p style='text-align: center; font-size: 18px; margin-bottom: 0;'>Scheduled order for " . $sched_date . " @ " . $sched_time . "</p>";
                                $message .= "<p style='text-align: center; font-size: 18px; margin-top: 5px;'>at " . $order_exists->address . "</p>";
                                $message .= "<table style='width: 100%; border-collapse: collapse; text-align: left; font-size: 20px; margin-top: 30px;'>
					<tr><td><strong>Customer Name:</strong><br> " . $cust_exists->first_name . " " . $cust_exists->last_name . "</td><td style='text-align: right;'><strong>Order Number:</strong> #000" . $id . "</td></tr>
					</table>";

                                $message .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>";

                                foreach ($kartdata->vehicles as $ind => $vehicle) {

                                    $message .= "<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'>" . $vehicle->brand_name . " " . $vehicle->model_name . "</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$0.00</p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'>" . $vehicle->vehicle_washing_package . " Package</p></td>
<td style='text-align: right;'></td>
</tr>";
                                    if ($vehicle->extclaybar_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->waterspotremove_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->upholstery_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->exthandwax_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->extplasticdressing_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if ($vehicle->floormat_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    $message .= "<tr>
<td><p style='font-size: 18px; margin: 0;'>Service Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";


                                    if ($vehicle->pet_hair_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }
                                    if ($vehicle->lifted_vehicle_fee > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    if (($ind == 0) && ($kartdata->coupon_discount > 0)) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (" . $order_exists->coupon_code . ")</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }


                                    if ($vehicle->fifth_wash_discount > 0) {
                                        $message .= "<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-</p></td>
</tr>";
                                    }

                                    $message .= "</table>

</td>
</tr>";
                                }


                                /* if($coupon_amount){
                                  $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
                                  <td style='padding-bottom: 15px;'>
                                  <p style='font-size: 18px; margin: 0;'>Coupon Discount</p>
                                  </td>
                                  <td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 18px; margin: 0;'>-</p></td>
                                  </tr></table>";
                                  } */
                                $message .= "<table style='width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px;'>
							<p style='font-size: 20px; margin: 0;'><strong>Customer No Response Fee</strong></p>
							</td>
							<td style='text-align: right; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0; font-weight: bold;'>$" . number_format($fee, 2) . "</p></td>
							</tr></table>";

                                $message .= "</table>";

                                $message .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$" . number_format($fee, 2) . "</span></p></td></tr></table>";

                                $message .= "<p style='text-align: center; font-size: 14px; line-height: 20px; margin-top: 45px; margin-bottom: 0;'>Customer No Response Fee is applied 15 minutes after the washer arrives. We've made multiple attempts to reach you via texts, app notifications, and courtesy call(s). We charge this fee in order to compensate your washer.</p>";

                                $message_washer = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Customer No Response</h2><h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Order Number: <span style='font-weight: normal;'>#000" . $id . "</span></h2>";

                                $message_washer .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px; padding-top: 15px;'>
							<p style='font-size: 20px; margin: 0;'>CNR Fee</p>
							</td>
							<td style='text-align: right; padding-top: 15px; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0;'>+$25.00</p></td>
							</tr></table>";

                                $message_washer .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$25.00</span></p></td></tr></table>";

                                $message_company = "<div class='block-content' style='background: #fff; text-align: left;'>
					<h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Customer No Response</h2><h2 style='text-align: center; font-size: 26px; margin-top: 0;'>Order Number: <span style='font-weight: normal;'>#000" . $id . "</span></h2>";

                                $message_company .= "<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; border-bottom: 1px solid #000; margin-top: 15px;'><tr>
							<td style='padding-bottom: 15px; padding-top: 15px;'>
							<p style='font-size: 20px; margin: 0;'>CNR Fee</p>
							</td>
							<td style='text-align: right; padding-top: 15px; padding-bottom: 15px;'><p style='font-size: 20px; margin: 0;'>+$5.00</p></td>
							</tr></table>";

                                $message_company .= "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
					<tr>
					<td></td>
					<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$5.00</span></p></td></tr></table>";

                                $to = Vargas::Obj()->getAdminToEmail();

                                Vargas::Obj()->SendMail($cust_exists->email, "billing@devmobilewash.com", $message, $subject, 'mail-receipt');
                                Vargas::Obj()->SendMail($agent_det->email, "billing@devmobilewash.com", $message_washer, $subject, 'mail-receipt');
                                Vargas::Obj()->SendMail($to, "billing@devmobilewash.com", $message_company, $subject, 'mail-receipt');

                                Washingrequests::model()->updateByPk($order_exists->id, array('is_order_receipt_sent' => 1));
                            } else {
                                if ($cancelresult['success'] == 1) {
                                    if ($cust_exists->client_position == 'real')
                                        $cancelvoid = Yii::app()->braintree->void_real($cancelresult['transaction_id']);
                                    else
                                        $cancelvoid = Yii::app()->braintree->void($cancelresult['transaction_id']);
                                }
                                $result = 'false';
                                $response = 'error in payment';
                            }
                        }
                    }
                    else {
                        $result = 'false';
                        $response = 'no payment method exists';
                    }
                }

                if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->agent_id) && (!$agent_det->block_washer)) {

                    $agentdevices = Yii::app()->db->createCommand("SELECT * FROM agent_devices WHERE agent_id = '" . $order_exists->agent_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '20' ")->queryAll();
                    $message = $pushmsg[0]['message'];
                    foreach ($agentdevices as $agdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($agdevice['device_type']);
                        $notify_token = $agdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (($result == 'true') && ($admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $order_exists->id,
                        'admin_username' => $admin_username,
                        'action' => 'cnrcompanynoresponse',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);

                    $clientdevices = Yii::app()->db->createCommand("SELECT * FROM customer_devices WHERE customer_id = '" . $order_exists->customer_id . "' ORDER BY last_used DESC LIMIT 1")->queryAll();

                    $pushmsg = Yii::app()->db->createCommand("SELECT * FROM push_messages WHERE id = '31' ")->queryAll();
                    $message = $pushmsg[0]['message'];

                    foreach ($clientdevices as $ctdevice) {
                        //$message =  "You have a new scheduled wash request.";
                        $device_type = strtolower($ctdevice['device_type']);
                        $notify_token = $ctdevice['device_token'];
                        $alert_type = "schedule";
                        $notify_msg = urlencode($message);

                        $notifyurl = ROOT_URL . "/push-notifications/" . $device_type . "/?device_token=" . $notify_token . "&msg=" . $notify_msg . "&alert_type=" . $alert_type;
                        //file_put_contents("android_notificaiton.log",$notifyurl,FILE_APPEND);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $notifyurl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        if ($notify_msg)
                            $notifyresult = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (($result == 'true') && (!$admin_username)) {
                    $washeractionlogdata = array(
                        'wash_request_id' => $order_exists->id,
                        'action' => 'cnrcompanynoresponse',
                        'action_date' => date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $washeractionlogdata);
                }

                if (($result == 'true') && ($response == 'Order canceled') && ($order_exists->coupon_code)) {
                    CustomerDiscounts::model()->deleteAll("wash_request_id=" . $order_exists->id . " AND customer_id=" . $order_exists->customer_id . " AND promo_code='" . $order_exists->coupon_code . "'");
                }
            }
        }

        $json = array(
            'result' => $result,
            'response' => $response,
            'cancel_price' => $cancel_price
        );

        echo json_encode($json);
    }

    public function actionremoveduplicatewashnowstartlog() {

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

        /* if (!$token_check) {
          $json = array(
          'result' => 'false',
          'response' => 'Invalid request'
          );
          echo json_encode($json);
          die();
          } */

        $result = 'false';
        $response = 'Pass the required parameters';

        $wash_request_id = Yii::app()->request->getParam('wash_request_id');

        if (!empty($wash_request_id) && isset($wash_request_id)) {

            if ((AES256CBC_STATUS == 1)) {
                $wash_request_id = $this->aes256cbc_crypt($wash_request_id, 'd', AES256CBC_API_PASS);
            }

            $wrequest_id_check = Washingrequests::model()->findByAttributes(array('id' => $wash_request_id));

            $agent_detail = Agents::model()->findByAttributes(array("id" => $wrequest_id_check->agent_id));
            $cust_detail = Customers::model()->findByPk($wrequest_id_check->customer_id);

            $kartapiresult = $this->washingkart($wash_request_id, API_KEY, 0, AES256CBC_API_PASS, $api_token, $t1, $t2, $user_type, $user_id);
            $kartdata = json_decode($kartapiresult);

            foreach ($kartdata->vehicles as $ind => $vehicle) {
                $mobile_receipt .= $vehicle->brand_name . " " . $vehicle->model_name . "\r\n" . $vehicle->vehicle_washing_package . " $" . $vehicle->vehicle_washing_price . "\r\nHandling $1.00\r\n";

                if ($vehicle->surge_vehicle_fee > 0) {
                    $mobile_receipt .= "Surge $" . $vehicle->surge_vehicle_fee . "\r\n";
                }
                if ($vehicle->extclaybar_vehicle_fee > 0) {

                    $mobile_receipt .= "Clay $" . $vehicle->extclaybar_vehicle_fee . "\r\n";
                }
                if ($vehicle->waterspotremove_vehicle_fee > 0) {

                    $mobile_receipt .= "Spot $" . $vehicle->waterspotremove_vehicle_fee . "\r\n";
                }
                if ($vehicle->exthandwax_vehicle_fee > 0) {

                    $mobile_receipt .= "Wax $" . $vehicle->exthandwax_vehicle_fee . "\r\n";
                }

                if ($vehicle->pet_hair_fee > 0) {

                    $mobile_receipt .= "Extra Cleaning $" . $vehicle->pet_hair_fee . "\r\n";
                }
                if ($vehicle->lifted_vehicle_fee > 0) {

                    $mobile_receipt .= "Lifted $" . $vehicle->lifted_vehicle_fee . "\r\n";
                }

                if ($vehicle->extplasticdressing_vehicle_fee > 0) {

                    $mobile_receipt .= "Dressing $" . $vehicle->extplasticdressing_vehicle_fee . "\r\n";
                }

                if ($vehicle->upholstery_vehicle_fee > 0) {

                    $mobile_receipt .= "Upholstery $" . $vehicle->upholstery_vehicle_fee . "\r\n";
                }

                if ($vehicle->floormat_vehicle_fee > 0) {

                    $mobile_receipt .= "Floormat $" . $vehicle->floormat_vehicle_fee . "\r\n";
                }

                if (($ind == 0) && ($kartdata->coupon_discount > 0)) {

                    $mobile_receipt .= "Promo: " . $kartdata->coupon_code . " -$" . number_format($kartdata->coupon_discount, 2) . "\r\n";
                }


                if ($vehicle->fifth_wash_discount > 0) {

                    $mobile_receipt .= "5th -$" . number_format($vehicle->fifth_wash_discount, 2) . "\r\n";
                }

                if (($vehicle->fifth_wash_discount == 0) && ($kartdata->coupon_discount <= 0) && (count($kartdata->vehicles) > 1)) {

                    $mobile_receipt .= "Bundle -$1.00\r\n";
                }

                if (($kartdata->coupon_discount > 0) && ($ind != 0) && (count($kartdata->vehicles) > 1)) {

                    $mobile_receipt .= "Bundle -$1.00\r\n";
                }
                $mobile_receipt .= "------\r\n";
            }

            if ($kartdata->tip_amount > 0) {
                $mobile_receipt .= "Tip $" . number_format($kartdata->tip_amount, 2) . "\r\n";
            }

            if ($kartdata->wash_now_fee > 0) {
                $mobile_receipt .= "Wash Now $" . number_format($kartdata->wash_now_fee, 2) . "\r\n";
            }

            if ($kartdata->wash_later_fee > 0) {
                $mobile_receipt .= "Surge Fee $" . number_format($kartdata->wash_later_fee, 2) . "\r\n";
            }


            $mobile_receipt .= "Total: $" . $kartdata->net_price . "\r\n";
            if (APP_ENV == 'real') {
                $this->layout = "xmlLayout";

                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                require_once(ROOT_WEBFOLDER . '/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');

                $account_sid = TWILIO_SID;
                $auth_token = TWILIO_AUTH_TOKEN;
                $client = new Services_Twilio($account_sid, $auth_token);

                if (!$wrequest_id_check->is_scheduled)
                    $message = "WASH NOW TAKEN #000" . $wrequest_id_check->id . "- " . date('M d', strtotime($wrequest_id_check->created_date)) . " @ " . date('h:i A', strtotime($wrequest_id_check->created_date)) . "\r\n" . $cust_detail->first_name . " " . $cust_detail->last_name . "\r\n" . $cust_detail->contact_number . "\r\n" . $wrequest_id_check->address . " (" . $wrequest_id_check->address_type . ")\r\nWasher Name: " . $agent_detail->first_name . " " . $agent_detail->last_name . "\r\nWasher Badge #" . $agent_detail->real_washer_id . "\r\n------\r\n" . $mobile_receipt;
                if ($wrequest_id_check->is_scheduled)
                    $message = "SCHEDULED WASH TAKEN #000" . $wrequest_id_check->id . "- " . date('M d', strtotime($wrequest_id_check->schedule_date)) . " @ " . $wrequest_id_check->schedule_time . "\r\n" . $cust_detail->first_name . " " . $cust_detail->last_name . "\r\n" . $cust_detail->contact_number . "\r\n" . $wrequest_id_check->address . " (" . $wrequest_id_check->address_type . ")\r\nWasher Name: " . $agent_detail->first_name . " " . $agent_detail->last_name . "\r\nWasher Badge #" . $agent_detail->real_washer_id . "\r\n------\r\n" . $mobile_receipt;


                try {
                    $sendmessage = $client->account->messages->create(array(
                        'To' => '8183313631',
                        'From' => '+13108890719',
                        'Body' => $message,
                    ));
                } catch (Services_Twilio_RestException $e) {
                    //echo  $e;
                }

                try {
                    $sendmessage = $client->account->messages->create(array(
                        'To' => '3109999334',
                        'From' => '+13108890719',
                        'Body' => $message,
                    ));
                } catch (Services_Twilio_RestException $e) {
                    //echo  $e;
                }

                try {
                    $sendmessage = $client->account->messages->create(array(
                        'To' => '3103442534',
                        'From' => '+13108890719',
                        'Body' => $message,
                    ));
                } catch (Services_Twilio_RestException $e) {
                    //echo  $e;
                }
            }


            if (!$wrequest_id_check->is_scheduled) {
                $currentwasherwashnowstartlog = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE agent_id = '" . $wrequest_id_check->agent_id . "' AND wash_request_id = '" . $wash_request_id . "' AND action = 'washerstartjob' ORDER BY action_date DESC LIMIT 1")->queryAll();

                if (count($currentwasherwashnowstartlog)) {
                    $otherwasherswashnowstartlogs = Yii::app()->db->createCommand("SELECT * FROM activity_logs WHERE agent_id != '" . $wrequest_id_check->agent_id . "' AND wash_request_id = '" . $wash_request_id . "' AND action = 'washerstartjob' ORDER BY action_date DESC")->queryAll();

                    if (count($otherwasherswashnowstartlogs)) {
                        foreach ($otherwasherswashnowstartlogs as $log) {
                            $sec_diff = 0;
                            $sec_diff = abs(strtotime($currentwasherwashnowstartlog[0]['action_date']) - strtotime($log['action_date']));

                            if (($sec_diff <= 2) && ($sec_diff >= 0)) {
                                Yii::app()->db->createCommand("DELETE FROM activity_logs WHERE id = " . $log['id'])->execute();
                            }
                        }
                    }
                }
            }



            $result = 'true';
            $response = 'done';
        }

        $json = array(
            'result' => $result,
            'response' => $response
        );
        echo json_encode($json);
    }

    public function actioncustomersfeedbacksapp() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');
        $type = Yii::app()->request->getParam('type');
        $feedback_type = Yii::app()->request->getParam('feedback_type');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        /*        get the total */
        $whr = '';
        if ($type != '') {
            $whr = 'AND a.title = "' . $type . '"';
        }


        $feedback = Yii::app()->db->createCommand("SELECT c.id, c.customername, c.contact_number, a.comments, a.create_time FROM app_feedbacks a LEFT JOIN customers c ON a.customer_id = c.id WHERE a.agent_id = 0 " . $whr . " ORDER BY a.create_time DESC")->queryAll();

        $i = 0;
        foreach ($feedback as $feedbacks) {
            if ($feedbacks['id']) {
                $totalorders = Yii::app()->db->createCommand("SELECT COUNT(id) as orders FROM `washing_requests` WHERE `status`=4 AND customer_id =" . $feedbacks['id'])->queryRow();
            }
            $i++;
            $id = $feedbacks['id'];
            $customer = $feedbacks['customername'];
            $contact_number = $feedbacks['contact_number'];
            $comments = $feedbacks['comments'];
            $create_time = $feedbacks['create_time'];

            $json = array();
            $json['id'] = $id;
            $json['customer'] = $customer;
            $json['contact_number'] = $contact_number;
            $json['orders'] = $totalorders['orders'];
            $json['comments'] = $comments;
            $json['create_time'] = $create_time;
            $feedview[] = $json;
        }

        $feedbackadmin['order'] = $feedview;
        $feedbackadmin['result'] = 'true';

        echo json_encode($feedbackadmin, JSON_PRETTY_PRINT);
        exit;
    }

    public function actionwasherfeedbacksapp() {
        if (Yii::app()->request->getParam('key') != API_KEY) {
            echo "Invalid api key";
            die();
        }

        $api_token = Yii::app()->request->getParam('api_token');
        $t1 = Yii::app()->request->getParam('t1');
        $t2 = Yii::app()->request->getParam('t2');
        $user_type = Yii::app()->request->getParam('user_type');
        $user_id = Yii::app()->request->getParam('user_id');
        $type = Yii::app()->request->getParam('type');

        $token_check = $this->verifyapitoken($api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS);

        if (!$token_check) {
            $json = array(
                'result' => 'false',
                'response' => 'Invalid request'
            );
            echo json_encode($json);
            die();
        }

        /*        get the total */
        $whr = '';
        if ($type != '') {
            $whr = 'AND a.title = "' . $type . '"';
        }
        $feedback = Yii::app()->db->createCommand("SELECT c.real_washer_id, c.agentname, c.phone_number, a.comments, a.create_time FROM app_feedbacks a LEFT JOIN agents c ON a.agent_id = c.id WHERE a.agent_id != 0 AND a.customer_id = 0 " . $whr . " ORDER BY a.create_time DESC")->queryAll();

        $i = 0;
        foreach ($feedback as $feedbacks) {
            $i++;
            if ($feedbacks['real_washer_id']) {
                $totalorders = Yii::app()->db->createCommand("SELECT COUNT(id) as orders FROM `washing_requests` WHERE `status`=4 AND agent_id =" . $feedbacks['real_washer_id'])->queryRow();
            }
            $id = $feedbacks['real_washer_id'];
            $customer = $feedbacks['agentname'];
            $contact_number = $feedbacks['phone_number'];
            $comments = $feedbacks['comments'];
            $create_time = $feedbacks['create_time'];

            $json = array();
            $json['id'] = $id;
            $json['customer'] = $customer;
            $json['contact_number'] = $contact_number;
            $json['comments'] = $comments;
            $json['orders'] = $totalorders['orders'];
            $json['create_time'] = $create_time;
            $feedview[] = $json;
        }

        $feedbackadmin['order'] = $feedview;
        $feedbackadmin['result'] = 'true';

        echo json_encode($feedbackadmin, JSON_PRETTY_PRINT);
        exit;
    }

}
