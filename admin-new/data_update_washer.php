<?php
  $connection_1 = mysql_connect("localhost", "devmobil_mwuser", "XUS9Qf9bwJ%&") or die(mysql_error());
 mysql_select_db("devmobil_mwmain", $connection_1) or die(mysql_error());

 $connection_2 = mysql_pconnect("localhost", "devmobil_mwuser", "XUS9Qf9bwJ%&") or die(mysql_error());
 mysql_select_db("devmobil_mwmain", $connection_2) or die(mysql_error());
 
$quer1 =  mysql_query("SELECT * FROM `pre_registered_washers`", $connection_1); // run query for first connection
//$quer2 =  mysql_query("SELECT * FROM `pre_registered_clients`", $connection_2);
$del = mysql_query('TRUNCATE TABLE pre_registered_washers',$connection_2);
while($row1 = mysql_fetch_array($quer1)){
    $id = $row1['id'];
    $first_name = $row1['first_name'];
    $last_name = $row1['last_name'];
    $email = $row1['email'];
    $phone = $row1['phone'];
    $explode = explode(' free', $phone);
    $number = $explode[0];
    $city = $row1['city'];
    $state = $row1['state'];
    $zipcode = $row1['zipcode'];
    $hear_mw_how = $row1['hear_mw_how'];
    $register_date = $row1['register_date'];
    $register_status = $row1['register_status'];
    $register_token = $row1['register_token'];
    $phone_verify_code = $row1['phone_verify_code'];
    $phone_verified = $row1['phone_verified'];
    $date_of_birth = $row1['date_of_birth'];
    $street_address = $row1['street_address'];
    $suite_apt = $row1['suite_apt'];
    $legally_eligible = $row1['legally_eligible'];
    $own_vehicle = $row1['own_vehicle'];
    $waterless_wash_product = $row1['waterless_wash_product'];
    $operate_area = $row1['operate_area'];
    $work_schedule = $row1['work_schedule'];
    $operating_as = $row1['operating_as'];
    $company_name = $row1['company_name'];
    $wash_experience = $row1['wash_experience'];
    $driver_license = $row1['driver_license'];
    $liable_insurance = $row1['liable_insurance'];
    $insurance_expire_date = $row1['insurance_expire_date'];
    $routing_number = $row1['routing_number'];
    $bank_account_number = $row1['bank_account_number'];
    
    mysql_query("INSERT INTO `pre_registered_washers` (`id`,`first_name`, `last_name`, `email`, `phone`, `city`, `state`, `zipcode`, `hear_mw_how`, `register_date`, `register_status`, `register_token`, `phone_verify_code`, `phone_verified`, `date_of_birth`, `street_address`, `suite_apt`, `legally_eligible`, `own_vehicle`, `waterless_wash_product`, `operate_area`, `work_schedule`, `operating_as`, `company_name`, `wash_experience`, `driver_license`, `liable_insurance`, `insurance_expire_date`, `routing_number`, `bank_account_number`, `trash_status`) VALUES ('$id', '$first_name', '$last_name', '$email', '$number', '$city', '$state', '$zipcode', '$hear_mw_how', '$register_date',  '$register_status', '$register_token', '$phone_verify_code', '$phone_verified', '$date_of_birth', '$street_address', '$suite_apt', '$legally_eligible', '$own_vehicle', '$waterless_wash_product', '$operate_area', '$work_schedule', '$operating_as', '$company_name', '$wash_experience', '$driver_license', '$liable_insurance', '$insurance_expire_date', '$routing_number', '$bank_account_number', '0')", $connection_2);
    //exit;
  
}
$json = array(
                'result'=> 'true',
                'response'=> 'copy'
            );

         echo json_encode($json);die();

?>
