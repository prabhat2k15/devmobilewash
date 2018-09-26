<?php
require_once('api/protected/config/constant.php');
ini_set("date.timezone", "America/Los_Angeles");



/* --- washing kart call --- */

$handle = curl_init(ROOT_URL."/api/index.php?r=washing/washingkart");
$data = array('wash_request_id' => $_GET['orderid'], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$kartdata = json_decode($result);

/* --- washing kart call end --- */
$order_id = $kartdata->org_id;

?>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<style>
body{
font-family: 'Lato', sans-serif;
background: #2c2c2c;
margin: 0;
padding: 0;
color: #fff;
}

.header{
background: #0270D1;
    padding: 15px;
box-sizing: border-box;
}

.header h1{
margin: 0;
font-weight: 500;
text-align: center;
   font-size: 30px;
}

.content{

}

.content table{
width: 100%;
border-collapse: collapse;
}

.content table .rightalign{
text-align: right;
}

.content table td{
padding: 20px;
}

.content table td p{
margin: 0;
}

.content .car-details td{
border-bottom: 1px solid #6D6D6D;
}

.content .car-details tr:last-child td,
.content .discount-details tr:last-child td{
border-bottom: 0;
}

.content .discount-details{
  border-bottom: 1px solid #6D6D6D;
}

.content .discount-details tr{
background: #171717;

}

.content .discount-details td{
/*border-bottom: 1px solid #6D6D6D; */
}

.content .total{
background: #ddd;
color: #000;
font-weight: 700;
}


.content .discount-details td{
padding: 15px 20px;
}

.content .discount-details td p{
font-size: 18px;
}

.content .total-price{
font-size: 34px;
}

.content .total td{
padding: 10px 20px;
}

.content .back-btn{
display: block;
    width: 100%;
    padding: 20px;
    background: #006bd0;
    color: #fff;
    text-align: center;
    text-decoration: none;
    font-size: 24px;
    box-sizing: border-box;
display: none;
}

.content .inline-table td{
padding: 0;
border: 0;
padding: 3px 0;
}


.content .inline-table td p{
font-size: 18px;
}


.content .price{
font-size: 24px !important;
}

.content .order-date{
    margin: 0;
    font-weight: 500;
    text-align: center;
    font-size: 20px;
    border-bottom: 1px solid #6D6D6D;
    padding: 20px 0;
}

@media screen and (max-width: 320px) {
  .content .order-date{
      font-size: 20px;
  }

}

</style>
</head>
<body>
<div class="header">
<h1>Order # 000<?php echo $order_id; ?></h1>
</div>
<div class="content">

<h2 class="order-date"><?php echo date('M d, Y', strtotime($kartdata->order_date)); ?> @ <?php echo date('h:i A', strtotime($kartdata->order_date)); ?></h2>

<?php if($kartdata->status == 5): ?>
<h2 style="padding: 0 20px;">This order is canceled by client</h2>
<table class="discount-details">
<tr>
<td><p style="font-size: 20px;">Cancel Fee</p></td>
<td class="rightalign">
<p class="price">$<?php if($kartdata->washer_cancel_fee > 0) {echo number_format($kartdata->washer_cancel_fee, 2);} else{echo '0.00';} ?></p>
</td>
</tr>
</table>
<table class="total">
<tr>
<td><p style="font-size: 20px;">Total Price:</p></td>
<td class="rightalign"><p class="total-price">$<?php if($kartdata->washer_cancel_fee > 0) {echo number_format($kartdata->washer_cancel_fee, 2);} else{echo '0.00';} ?></p></td>
</tr>
</table>
<?php elseif($kartdata->status == 6): ?>
<h2 style="padding: 0 20px;">This order is canceled by agent</h2>
<?php else: ?>
<table class="car-details">
 <?php foreach($kartdata->vehicles as $ind=>$vehicle): ?>
<tr>
<td>
<table class="inline-table">
<tr>
<td><p style="font-size: 20px;"><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p></td>
<td class="rightalign">
<p class="price">+$<?php echo $vehicle->vehicle_washing_price_agent; ?></p>
</td>
</tr>
<tr>
<td><p style="color: #ccc;"><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td class="rightalign"></td>
</tr>
<?php if($vehicle->surge_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Surge Charge</p>
</td>
<td class="rightalign"><p>+$<?php $surge_fee = $vehicle->surge_vehicle_fee*.8; echo number_format(round($surge_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->extclaybar_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Clay Bar & Paste Wax</p>
</td>
<td class="rightalign"><p>+$<?php $extclaybar_fee = $vehicle->extclaybar_vehicle_fee*.8; echo number_format(round($extclaybar_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->waterspotremove_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Water Spot Removal</p>
</td>
<td class="rightalign"><p>+$<?php $waterspotremove_fee = $vehicle->waterspotremove_vehicle_fee*.8; echo number_format(round($waterspotremove_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->upholstery_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Upholstery Conditioning</p>
</td>
<td class="rightalign"><p>+$<?php $upholstery_fee = $vehicle->upholstery_vehicle_fee*.8; echo number_format(round($upholstery_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->exthandwax_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Hand Wax (Liquid form)</p>
</td>
<td class="rightalign"><p>+$<?php $exthandwax_fee = $vehicle->exthandwax_vehicle_fee*.8; echo number_format(round($exthandwax_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->floormat_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Floor Mat Cleaning</p>
</td>
<td class="rightalign"><p>+$<?php $floormat_fee = $vehicle->floormat_vehicle_fee*.8; echo number_format(round($floormat_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->pet_hair_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Extra Cleaning Fee</p>
</td>
<td class="rightalign"><p>+$<?php $pet_fee = $vehicle->pet_hair_fee*.8; echo number_format(round($pet_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->lifted_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Lifted Truck</p>
</td>
<td class="rightalign"><p>+$<?php $lift_fee = $vehicle->lifted_vehicle_fee*.8; echo number_format(round($lift_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->extplasticdressing_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Dressing of all Exterior Plastics</p>
</td>
<td class="rightalign"><p>+$<?php $extplasticdressing_fee = $vehicle->extplasticdressing_vehicle_fee*.8; echo number_format(round($extplasticdressing_fee, 2), 2); ?></p></td>
</tr>
<?php endif; ?>


<?php if($vehicle->bundle_discount_agent > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Bundle Discount</p>
</td>
<td class="rightalign"><p>-$<?php echo number_format($vehicle->bundle_discount_agent, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->vehicle_inspect_image): ?>
<tr>
<td colspan="2"><img style="width: 100%; max-width: 300px; margin-top: 15px; display: block; margin-left: auto; margin-right: auto;" src="<?php echo $vehicle->vehicle_inspect_image; ?>" />
</td>
</tr>
<?php endif; ?>
</table>

</td>
</tr>

<?php endforeach; ?>

</table>
<?php if($kartdata->transaction_fee > 0): ?>
<table class="discount-details">

<tr>
<td><p>Transaction Fee</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">-$<?php echo $kartdata->transaction_fee; ?></p>
</td>
</tr>

</table>
<?php endif; ?>
<?php if($kartdata->wash_now_fee > 0): ?>
<table class="discount-details">

<tr>
<td><p>Wash Now Fee</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">+$<?php echo number_format(round($kartdata->wash_now_fee*.75, 2), 2); ?></p>
</td>
</tr>

</table>
<?php endif; ?>
<?php if($kartdata->wash_later_fee > 0): ?>
<table class="discount-details">

<tr>
<td><p>Surge Fee</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">+$<?php echo number_format(round($kartdata->wash_later_fee*.75, 2), 2); ?></p>
</td>
</tr>

</table>
<?php endif; ?>
<?php if($kartdata->tip_amount > 0): ?>
<table class="discount-details">

<tr>
<td><p>Tip</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">+$<?php echo number_format($kartdata->tip_amount, 2); ?></p>
</td>
</tr>

</table>
<?php endif; ?>

<table class="total">
<tr>
<td><p style="font-size: 20px;">Total Price:</p></td>
<td class="rightalign"><p class="total-price">$<?php echo $kartdata->agent_total; ?></p></td>
</tr>
</table>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back-btn">Go Back</a>
<?php endif; ?>
</div>

</body>
</html>