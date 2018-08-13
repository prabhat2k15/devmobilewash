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
$per_car_wash_points_arr = explode(",", $kartdata->per_car_wash_points);
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
background: #262626;
margin: 0;
padding: 0;
color: #fff;
}

.clear{
    clear: both;
}

.header{
background: #006bd0;
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
/*border-bottom: 1px solid #6D6D6D;*/
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

.content .points-holder{
margin-top: 10px;
}

.content .points-holder img{
margin-right: 2px;
}

.content .points-holder img.last{
margin-right: 0;
}

.content .addi-details{
background: #fff;
padding: 20px;
color: #000;
}

.content .addi-details h3{
margin-top: 0;
text-transform: uppercase;
font-size: 16px;
}

.content .addi-details .col{
float: left;
}

.content .addi-details .collast{
float: right;
}

.content .addi-details .col h4{
text-transform: uppercase;
font-size: 16px;
font-weight: normal;
margin-top: 5px;
margin-bottom: 10px;
}

.content .addi-details .col p{
font-weight: bold;
font-size: 17px;
text-transform: uppercase;
margin: 0;
}

</style>
</head>
<body>
<div class="header">
<h1>Order # 000<?php echo $order_id; ?></h1>
</div>
<div class="content">
<?php if($kartdata->status == 5 || $kartdata->status == 6): ?>
<?php if($kartdata->status == 5): ?>
<h2 style="padding: 0 20px; margin-bottom: 0;">This order is canceled by client</h2>
<?php endif; ?>
<?php if($kartdata->status == 6): ?>
<?php if($kartdata->washer_late_cancel == 1): ?>
<h2 style="padding: 0 20px; margin-bottom: 0;">There was an error communicating with the network. Please contact support (888) 209-5585</h2>
<?php else: ?>
<h2 style="padding: 0 20px; margin-bottom: 0;">This order is canceled by agent</h2>
<?php endif; ?>
<?php endif; ?>
<table class="car-details">
 <?php foreach($kartdata->vehicles as $ind=>$vehicle): ?>
<tr>
<td>
<table class="inline-table">
<tr>
<td>
<p style="font-size: 20px;"><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p>
<?php if(($per_car_wash_points_arr[$ind]) == 1): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 2): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 3): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 4): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 5): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/blue-bubble2.png" /></p>
<?php endif; ?>
</td>
<td class="rightalign" style="vertical-align: top;">
<p class="price">+$0.00</p>
</td>
</tr>
<tr>
<td><p style="color: #ccc;"><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td class="rightalign"></td>
</tr>
<?php if($vehicle->extclaybar_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Clay Bar & Paste Wax</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->waterspotremove_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Water Spot Removal</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->upholstery_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Upholstery Conditioning</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->exthandwax_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Hand Wax (Liquid form)</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->extplasticdressing_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Dressing of all Exterior Plastics</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->floormat_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Floor Mat Cleaning</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<tr>
<td><p style="color: #ccc;">Service Fee</p></td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php if($vehicle->pet_hair_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Light Pet Hair Removal</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->lifted_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Lifted Truck</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>
<?php if(($vehicle->fifth_wash_discount == 0) && (count($kartdata->vehicles) > 1)): ?>
<tr>
<td>
<p style="color: #ccc;">Bundle Discount</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>

<?php if($vehicle->fifth_wash_discount > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Fifth Wash Discount</p>
</td>
<td class="rightalign"><p>-</p></td>
</tr>
<?php endif; ?>

</table>

</td>
</tr>

<?php endforeach; ?>
</table>
<?php if($kartdata->coupon_discount > 0): ?>
<table class="discount-details">
<tr>
<td><p>Promo Discount</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">-</p>
</td>
</tr>
</table>
<?php endif; ?>


<table class="discount-details">
<tr>
<td><p style="font-size: 20px;">Cancel Fee</p></td>
<td class="rightalign">
<p class="price">$<?php if($kartdata->cancel_fee > 0) {if($kartdata->washer_cancel_fee > 0) {echo number_format($kartdata->cancel_fee/2, 2);} else{echo number_format($kartdata->cancel_fee, 2);} } else{echo '0.00';} ?></p>
</td>
</tr>
</table>
<table class="total">
<tr>
<td><p style="font-size: 20px;">Total Price:</p></td>
<td class="rightalign"><p class="total-price">$<?php if($kartdata->cancel_fee > 0) {if($kartdata->washer_cancel_fee > 0) {echo number_format($kartdata->cancel_fee/2, 2);} else{echo number_format($kartdata->cancel_fee, 2);} } else{echo '0.00';} ?></p></td>
</tr>
</table>
<?php else: ?>
<table class="car-details">
 <?php foreach($kartdata->vehicles as $ind=>$vehicle): ?>
<tr>
<td>
<table class="inline-table">
<tr>
<td>
<p style="font-size: 20px;"><?php echo $vehicle->brand_name." ".$vehicle->model_name; ?></p>
<?php if(($per_car_wash_points_arr[$ind]) == 1): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 2): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 3): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 4): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/gray-bubble.png" /></p>
<?php elseif($per_car_wash_points_arr[$ind] == 5): ?>
<p class="points-holder"><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/blue-bubble2.png" /></p>
<?php endif; ?>
</td>
<td class="rightalign" style="vertical-align: top;">
<p class="price">+$<?php 
if($vehicle->vehicle_washing_package == 'Premium') echo number_format($vehicle->vehicle_washing_price*.25, 2); 
else echo number_format($vehicle->vehicle_washing_price*.20, 2);
?></p>
</td>
</tr>
<tr>
<td><p style="color: #ccc;"><?php echo $vehicle->vehicle_washing_package; ?> Package</p></td>
<td class="rightalign"></td>
</tr>
<?php if($vehicle->extclaybar_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Clay Bar & Paste Wax</p>
</td>
<td class="rightalign"><p>+$<?php 
echo number_format($vehicle->extclaybar_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->waterspotremove_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Water Spot Removal</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->waterspotremove_vehicle_fee *.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->exthandwax_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Full Exterior Hand Wax (Liquid form)</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->exthandwax_vehicle_fee *.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->upholstery_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Upholstery Conditioning</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->upholstery_vehicle_fee *.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->extplasticdressing_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Dressing of all Exterior Plastics</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->extplasticdressing_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->floormat_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Floor Mat Cleaning</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->floormat_vehicle_fee *.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<tr>
<td><p style="color: #ccc;">Service Fee</p></td>
<td class="rightalign"><p>+$1.00</p></td>
</tr>
<?php if($vehicle->pet_hair_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Light Pet Hair Removal</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->pet_hair_fee*.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->lifted_vehicle_fee > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Lifted Truck</p>
</td>
<td class="rightalign"><p>+$<?php echo number_format($vehicle->lifted_vehicle_fee*.20, 2); ?></p></td>
</tr>
<?php endif; ?>
<?php if($vehicle->bundle_discount > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Bundle Discount</p>
</td>
<td class="rightalign"><p>-$<?php echo number_format($vehicle->bundle_discount*.20, 2); ?></p></td>
</tr>
<?php endif; ?>

<?php if($vehicle->fifth_wash_discount > 0): ?>
<tr>
<td>
<p style="color: #ccc;">Fifth Wash Discount</p>
</td>
<td class="rightalign"><p>-$<?php
if(count($kartdata->vehicles) > 1) echo number_format($vehicle->fifth_wash_discount-.80, 2); 
else echo number_format($vehicle->fifth_wash_discount, 2); ?></p></td>
</tr>
<?php endif; ?>

</table>

</td>
</tr>

<?php endforeach; ?>
</table>

<?php if($kartdata->wash_now_fee > 0): ?>
<table class="discount-details">
<tr>
<td><p>Wash Now Fee</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">+$<?php echo number_format($kartdata->wash_now_fee*.25, 2); ?></p>
</td>
</tr>
</table>
<?php endif; ?>

<?php if($kartdata->wash_later_fee > 0): ?>
<table class="discount-details">
<tr>
<td><p>Surge Fee</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">+$<?php echo number_format($kartdata->wash_later_fee*.25, 2); ?></p>
</td>
</tr>
</table>
<?php endif; ?>


<?php if($kartdata->coupon_discount > 0): ?>
<table class="discount-details">
<tr>
<td><p>Promo Discount</p></td>
<td class="rightalign" style="vertical-align: top; min-width: 90px;">
<p class="price">-$<?php if(count($kartdata->vehicles) > 1) echo number_format($kartdata->coupon_discount-.80, 2);
else echo number_format($kartdata->coupon_discount, 2); ?></p>
</td>
</tr>
</table>
<?php endif; ?>

<table class="total">
<tr>
<td><p style="font-size: 20px;">Total Price:</p></td>
<td class="rightalign"><p class="total-price">$<?php echo $kartdata->company_total; ?></p></td>
</tr>
</table>
<div class="addi-details">
<?php if($kartdata->transaction_id && $kartdata->card_no): ?>
   <h3>Payment Method</h3>
   <p style="font-size: 14px;"><span style="display: block; float: left;"><img style="width: 24px; vertical-align: bottom; margin-right: 5px;" src="<?php echo $kartdata->card_img; ?>" /> <?php echo $kartdata->card_no; ?> <span style="margin-left: 5px;">(exp. <?php echo $kartdata->card_exp_mo."/".$kartdata->card_exp_yr; ?>)</span></span><span style="display: block; float: right;"><?php echo $kartdata->cardholder_name; ?></span>
 <div class="clear"></div>
</p>
<?php endif; ?>
   <div class="col">
<?php if($kartdata->is_scheduled): ?>        
<h4>Schedule Date</h4>
        <p><?php echo date('F d-Y', strtotime($kartdata->schedule_date)); ?></p>
<?php else: ?>
<h4>Order Date</h4>
        <p><?php echo date('F d-Y', strtotime($kartdata->order_date)); ?></p>
<?php endif; ?>
   </div>
   <div class="col collast">
<?php if($kartdata->is_scheduled): ?>   
        <h4>Schedule Time</h4>
        <p><?php echo $kartdata->schedule_time; ?></p>
<?php else: ?>
  <h4>Order Time</h4>
        <p><?php echo date('h:i A', strtotime($kartdata->order_date)); ?></p>
<?php endif; ?>
   </div>
   <div class="clear"></div>
</div>
<?php endif; ?>
</div>

</body>
</html>