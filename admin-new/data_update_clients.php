<?php
  $connection_1 = mysql_connect("localhost", "devmobil_mwuser", "XUS9Qf9bwJ%&") or die(mysql_error());
 mysql_select_db("devmobil_mwmain", $connection_1) or die(mysql_error());

 $connection_2 = mysql_pconnect("localhost", "devmobil_mwuser", "XUS9Qf9bwJ%&") or die(mysql_error());
 mysql_select_db("devmobil_mwmain", $connection_2) or die(mysql_error());
 
$quer1 =  mysql_query("SELECT * FROM `pre_registered_clients`", $connection_1); // run query for first connection
//$quer2 =  mysql_query("SELECT * FROM `pre_registered_clients`", $connection_2);
$del = mysql_query('TRUNCATE TABLE pre_registered_clients',$connection_2);
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
    $register_date = $row1['register_date'];
    
    mysql_query("INSERT INTO `pre_registered_clients` (`id`,`first_name`, `last_name`, `email`, `phone`, `city`, `state`, `register_date`, `trash_status`) VALUES ('$id', '$first_name', '$last_name', '$email', '$number', '$city', '$state', '$register_date', '0')", $connection_2);
    
}
$json = array(
                'result'=> 'true',
                'response'=> 'copy'
            );

         echo json_encode($json);die();

?>
