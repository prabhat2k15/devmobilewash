<?php
require_once('api/protected/config/constant.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
ini_set("date.timezone", "America/Los_Angeles");

$order_id = $_GET['orderid'];

/* --- washing kart call --- */

$handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
$data = array('wash_request_id' => $order_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$kartdata = json_decode($result);
$per_car_wash_points_arr = explode(",", $kartdata->per_car_wash_points);
/* --- washing kart call end --- */

$handle_data = curl_init(ROOT_URL."/api/index.php?r=customers/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('customerid' => $kartdata->customer_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$custdetails = json_decode($result);

$handle_data = curl_init(ROOT_URL."/api/index.php?r=agents/profiledetails");
curl_setopt($handle_data, CURLOPT_POST, true);
curl_setopt($handle_data, CURLOPT_POSTFIELDS, array('agent_id' => $kartdata->agent_id, 'api_password' => AES256CBC_API_PASS, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'));
curl_setopt($handle_data,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle_data);
curl_close($handle_data);
$agentdetails = json_decode($result);


?>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<style>
body{
font-family: 'Lato', sans-serif;
margin: 0;
padding: 0;
background: #ccc;
}

.clear{
    clear: both;
}

</style>
</head>
<body>
<div class='block-content' style='background: #fff; text-align: left; width: 90%; padding: 20px; margin: 0 auto; box-sizing: border-box;'>

                  <p style='text-align: center; font-family: arial; font-size: 20px; line-height: normal; margin: 0;'><strong>Order Number:</strong> #000<?php echo $_GET['orderid']; ?></p>

                  <?php if($kartdata->status == 5){?>
<?php if($kartdata->cancel_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 15px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$<?php echo number_format($kartdata->cancel_fee - $kartdata->washer_cancel_fee, 2); ?></p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$<?php echo number_format($kartdata->cancel_fee - $kartdata->washer_cancel_fee, 2); ?></span></p></td>
</tr>
</table>

<?php }
}
else{ ?>
<p style='margin: 0; margin-top: 15px; font-size: 18px; border-top: 1px solid #000; padding: 10px 0;'><strong>MobileWash Receipt</strong></p>

<table style='width: 100%; border-collapse: collapse; border-top: 1px solid #000; margin-top: 0;'>
<?php foreach($kartdata->vehicles as $ind=>$vehicle){ ?>
<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p></td>
<td style='text-align: right;'>
<?php if($vehicle->vehicle_washing_package == 'Premium') : ?>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$<?php echo number_format($vehicle->vehicle_washing_price*.25, 2); ?></p>
<?php else : ?><p style='font-size: 20px; margin: 0; font-weight: bold;'>+$<?php echo number_format($vehicle->vehicle_washing_price*.20, 2); ?></p>
<?php endif; ?>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td style='text-align: right;'></td>
</tr>

<?php if($vehicle->surge_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->surge_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->extclaybar_vehicle_fee > 0){  ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->extclaybar_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->waterspotremove_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->waterspotremove_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->upholstery_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->upholstery_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->exthandwax_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->exthandwax_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->floormat_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->floormat_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->pet_hair_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->pet_hair_fee*.20, 2); ?></p></td>
</tr>
<?php }
if($vehicle->lifted_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Vehicle Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->lifted_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php }

if($vehicle->extplasticdressing_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($vehicle->extplasticdressing_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php } ?>

<tr>
<td><p style='font-size: 18px; margin: 0;'>Safe Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$1.00</p></td>
</tr>

<?php if($vehicle->fifth_wash_discount > 0){
if((count($kartdata->vehicles) > 1)){  ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($vehicle->fifth_wash_discount-.80, 2); ?></p></td>
</tr>
<?php }
else{ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($vehicle->fifth_wash_discount, 2); ?></p></td>
</tr>
<?php }

}

if($vehicle->bundle_discount > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($vehicle->bundle_discount*.20, 2); ?></p></td>
</tr>
<?php }

?>

</table>

</td>
</tr>

<?php } ?>
</table>
<?php if($kartdata->transaction_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Transaction Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo $kartdata->transaction_fee; ?></p>
</td>
</tr>

</table>
<?php }

if($kartdata->wash_now_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>
<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format(round($kartdata->wash_now_fee*.25, 2), 2); ?></p>
</td>
</tr>
</table>
<?php }

if($kartdata->wash_later_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>
<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Later Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format(round($kartdata->wash_later_fee*.25, 2), 2); ?></p>
</td>
</tr>
</table>
<?php }

if($kartdata->coupon_discount > 0){  ?>
    <table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>
   <?php if((count($kartdata->vehicles) > 1)){ ?>
    <tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Promo Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($kartdata->coupon_discount - .80, 2); ?></p>
</td>
</tr>
  <?php }
   else{ ?>
     <tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Promo Discount</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($kartdata->coupon_discount, 2); ?></p>
</td>
</tr>
  <?php } ?>
  </table>
<?php } ?>

<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$<?php echo $kartdata->company_total; ?></span></p></td>
</tr>
</table>
<?php } ?>

<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Client Receipt:</strong> <?php echo $custdetails->customername; ?></p>

<?php if($kartdata->status == 5){ ?>
<?php if($kartdata->cancel_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$<?php echo number_format($kartdata->cancel_fee, 2); ?></p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$<?php echo number_format($kartdata->cancel_fee, 2); ?></span></p></td>
</tr>
</table>

<?php }
}
else{ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
 <?php foreach($kartdata->vehicles as $ind=>$vehicle){ ?>
<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$<?php echo $vehicle->vehicle_washing_price; ?></p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td style='text-align: right;'></td>
</tr>

<?php if($vehicle->surge_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->surge_vehicle_fee; ?></p></td>
</tr>
<?php }

if($vehicle->extclaybar_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->extclaybar_vehicle_fee; ?></p></td>
</tr>
<?php }
if($vehicle->waterspotremove_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->waterspotremove_vehicle_fee; ?></p></td>
</tr>
<?php } ?>

<?php if($vehicle->upholstery_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->upholstery_vehicle_fee; ?></p></td>
</tr>
<?php } ?>

<?php if($vehicle->exthandwax_vehicle_fee > 0){  ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->exthandwax_vehicle_fee; ?></p></td>
</tr>
<?php }

if($vehicle->floormat_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->floormat_vehicle_fee; ?></p></td>
</tr>
<?php }

if($vehicle->pet_hair_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->pet_hair_fee; ?></p></td>
</tr>
<?php }
if($vehicle->lifted_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->lifted_vehicle_fee; ?></p></td>
</tr>
<?php }

if($vehicle->extplasticdressing_vehicle_fee > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->extplasticdressing_vehicle_fee; ?></p></td>
</tr>
<?php } ?>

<tr>
<td><p style='font-size: 18px; margin: 0;'>Safe Handling Fee</p></td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->safe_handling_fee; ?></p></td>
</tr>

<?php if(($ind == 0) && ($kartdata->coupon_discount > 0)){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Promo (<?php echo $kartdata->coupon_code; ?>)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo $kartdata->coupon_discount; ?></p></td>
</tr>
<?php }

if($vehicle->fifth_wash_discount > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Fifth Wash Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo $vehicle->fifth_wash_discount; ?></p></td>
</tr>
<?php }

if($vehicle->bundle_discount > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($vehicle->bundle_discount, 2); ?></p></td>
</tr>
<?php }
 ?>

</table>

</td>
</tr>

<?php } ?>
</table>

<?php if($kartdata->wash_now_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($kartdata->wash_now_fee, 2); ?></p>
</td>
</tr>
</table>
<?php }
if($kartdata->wash_later_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Later Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($kartdata->wash_later_fee, 2); ?></p>
</td>
</tr>
</table>
<?php }
if($kartdata->tip_amount > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($kartdata->tip_amount, 2); ?></p>
</td>
</tr>
</table>
<?php } ?>

<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$<?php echo $kartdata->net_price; ?></span></p></td>
</tr>
</table>
<?php } ?>

<p style='margin: 0; margin-top: 10px; font-size: 18px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0;'><strong>Agent Receipt:</strong> <?php echo $agentdetails->first_name." ".$agentdetails->last_name; ?></p>

 <?php if($kartdata->status == 5){
if($kartdata->cancel_fee > 0){  ?>
<table style='width: 100%; border-collapse: margin-top: 10px;'>
<tr><td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0;'>Cancel Fee</p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0;'>+$<?php if($kartdata->washer_cancel_fee) {echo number_format($kartdata->washer_cancel_fee, 2);} else {echo "0.00";} ?></p>
</td>
</tr>
</table>
</td></tr>
</table>
<table class='total' style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0;'>Order Total: <span style='font-weight: bold;'>$<?php if($kartdata->washer_cancel_fee) {echo number_format($kartdata->washer_cancel_fee, 2);} else {echo "0.00";} ?></span></p></td>
</tr>
</table>

<?php }
}
else{ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
 <?php foreach($kartdata->vehicles as $ind=>$vehicle){ ?>
<tr>
<td style='border-bottom: 1px solid #000; padding-bottom: 10px;'>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td><p style='font-size: 20px; margin: 0; font-weight: bold;'><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p></td>
<td style='text-align: right;'>
<p style='font-size: 20px; margin: 0; font-weight: bold;'>+$<?php echo $vehicle->vehicle_washing_price_agent; ?></p>
</td>
</tr>
<tr>
<td><p style='font-size: 18px; margin: 0;'><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td style='text-align: right;'></td>
</tr>

<?php if($vehicle->surge_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Surge Charge</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->surge_vehicle_fee_agent; ?></p></td>
</tr>
<?php }

if($vehicle->extclaybar_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Clay Bar & Paste Wax</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->extclaybar_vehicle_fee_agent; ?></p></td>
</tr>
<?php }
if($vehicle->waterspotremove_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Water Spot Removal</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->waterspotremove_vehicle_fee_agent; ?></p></td>
</tr>
<?php }
if($vehicle->upholstery_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Upholstery Conditioning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->upholstery_vehicle_fee_agent; ?></p></td>
</tr>
<?php }
if($vehicle->exthandwax_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Full Exterior Hand Wax (Liquid form)</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->exthandwax_vehicle_fee_agent; ?></p></td>
</tr>
<?php }

if($vehicle->floormat_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Floor Mat Cleaning</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->floormat_vehicle_fee_agent; ?></p></td>
</tr>
<?php }

if($vehicle->pet_hair_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Extra Cleaning Fee</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->pet_hair_fee_agent; ?></p></td>
</tr>
<?php }
if($vehicle->lifted_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Lifted Truck</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->lifted_vehicle_fee_agent; ?></p></td>
</tr>
<?php }

if($vehicle->extplasticdressing_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Dressing of all Exterior Plastics</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>+$<?php echo $vehicle->extplasticdressing_vehicle_fee_agent; ?></p></td>
</tr>
<?php } ?>


<?php if($vehicle->extplasticdressing_vehicle_fee_agent > 0){ ?>
<tr>
<td>
<p style='font-size: 18px; margin: 0;'>Bundle Discount</p>
</td>
<td style='text-align: right;'><p style='font-size: 18px; margin: 0;'>-$<?php echo number_format($vehicle->bundle_discount_agent, 2); ?></p></td>
</tr>
<?php } ?>

</table>

</td>
</tr>

<?php } ?>
</table>
<?php if($kartdata->transaction_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Transaction Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>-$<?php echo $kartdata->transaction_fee; ?></p>
</td>
</tr>

</table>
<?php }
if($kartdata->wash_now_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Now Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format(round($kartdata->wash_now_fee*.75, 2), 2); ?></p>
</td>
</tr>

</table>
<?php }
if($kartdata->wash_later_fee > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Wash Later Fee</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format(round($kartdata->wash_later_fee*.75, 2), 2); ?></p>
</td>
</tr>

</table>
<?php }
if($kartdata->tip_amount > 0){ ?>
<table style='width: 100%; border-collapse: collapse; margin-top: 10px; border-bottom: 1px solid #000;'>

<tr>
<td style='padding-bottom: 10px;'><p style='font-size: 18px; margin: 0;'>Tip</p></td>
<td style='padding-bottom: 10px; font-size: 18px; margin: 0; text-align: right;'>
<p style='font-size: 18px; margin: 0;'>+$<?php echo number_format($kartdata->tip_amount, 2); ?></p>
</td>
</tr>

</table>
<?php } ?>


<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
<tr>
<td></td>
<td style='text-align: right;'><p style='font-size: 20px; margin: 0; color: #000;'>Order Total: <span style='font-weight: bold;'>$<?php echo number_format($kartdata->agent_total, 2); ?></span></p></td>
</tr>
</table>
<?php } ?>
</div>

</body>
</html>