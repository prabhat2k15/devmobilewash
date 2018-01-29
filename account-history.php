<?php
ini_set("date.timezone", "America/Los_Angeles");

$site_config = file_get_contents("api/protected/site_config.json");
$site_config_json = json_decode($site_config, true);

$client_id = $_GET['id'];
$page_no = 1;
if($_GET['page_no']) $page_no = $_GET['page_no'];

/* --- client account history call --- */

$handle = curl_init($site_config_json['root_url']."/api/index.php?r=customers/accounthistory");
$data = array('customer_id' => $client_id, 'page' => $page_no, 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);
//var_dump($jsondata);
$history_response = $jsondata->response;
$history_result_code = $jsondata->result;
$all_wash_requests = $jsondata->wash_requests;
$total_pages = $jsondata->total_pages;
$total_entries = $jsondata->total_entries;
//echo count($all_wash_requests);

/* --- client account history call end --- */


?>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<style>
body{
font-family: 'Lato', sans-serif;
background: #141414;
margin: 0;
padding: 0;
color: #fff;
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

.content .account-history{
width: 100%;
border-collapse: collapse;
}

.content .account-history .rightalign{
text-align: right;
}

.content .account-history td{
padding: 20px;
}

.content .account-history td p{
margin: 0 !important;
}

.content .account-history tr:nth-child(odd){
background: #000;
}

.content .account-history tr:last-child td{
border-bottom: 0;
}

.content .account-history td img{
margin-right: 2px;
}

.content .account-history td img.last{
margin-right: 0;
}

.content .account-history tr{
cursor: pointer;
}

.content .account-history td a{
text-decoration: none;
color: #fff;
}

.pagination{
text-align: center;
margin: 20px 0;
padding: 0 15px;
}

.pagination ul{
list-style: none;
margin: 0;
padding: 0;
}

.pagination ul li{
display: inline-block;
margin-bottom: 3px;
}

.pagination ul li.active{
background: #fff;
    text-decoration: none;
    padding: 10px 20px;
color: #000;
}

.pagination ul li a{
display: inline-block;
    text-align: center;
    color: #fff;
    background: #000;
    text-decoration: none;
    padding: 10px 20px;
}

.content .mobile-date{
display: none;
}

.content .view-btn{
background: #888 url(images/app-history-eye.png) no-repeat center 6px;
    display: block;
    text-align: center;
    padding-top: 31px;
    padding: 3px 0;
    padding-top: 16px;
    margin-top: 10px;
    width: 60px;
    font-size: 10px;
    margin-left: auto;
    /* margin-right: auto; */
    background-size: 18px;
}

.content .single-item{
width: 100%;
}

.content .single-item tr{
background: none !important;
}

.content .single-item td{
padding: 0;
padding-bottom: 5px !important;
}

</style>
</head>
<body><!--
<div class="header">
<h1>Account History</h1>
</div>-->
<div class="content">
<?php if(count($all_wash_requests)): ?>
<table class="account-history">
<?php foreach($all_wash_requests as $wrequest): ?>
<tr data-id="<?php echo $wrequest->id; ?>">
<td>
<table class="single-item">
<tr>
<td><p style="margin-bottom: 0; margin-top: 0; font-size: 20px;">#000<?php echo $wrequest->id; ?></p></td>
<td class="rightalign">
<?php if(trim($wrequest->agent_name)): ?>
<p style="font-size: 20px; margin-top: 0;">
<?php
$agent_name = explode(" ", trim($wrequest->agent_name));
if(count($agent_name > 1)) echo $agent_name[0]." ".strtoupper(substr($agent_name[1], 0, 1)).". #".$wrequest->agent_id;
else echo $agent_name[0]." #".$wrequest->agent_id;
?>
</p>
<?php else: ?>
<p style="font-size: 20px; margin-top: 0;">N/A</p>
<?php endif; ?>
</td>
</tr>
<tr>
<td>
 <p class="date" style="margin-bottom: 0; font-size: 16px;"><?php echo date('M d, Y', strtotime($wrequest->date)); ?> @ <?php echo date('h:i a', strtotime($wrequest->date)); ?></p>
</td>
<td class="rightalign">
 <?php if($wrequest->status == 5 || $wrequest->status == 6): ?>
<?php if($wrequest->cancel_fee): ?>
<p style="font-size: 26px; margin: 0;"><strong>$<?php echo number_format($wrequest->cancel_fee, 2); ?></strong></p>
<?php else: ?>
<p style="font-size: 26px; margin: 0;"><strong>$0.00</strong></p>
<?php endif; ?>
<?php else: ?>
<p style="font-size: 26px; margin: 0;"><strong>$<?php echo $wrequest->total; ?></strong></p>
<?php endif; ?>
</td>
</tr>
<tr>
<td>
<p style="min-width: 100px; margin-top: 20px;">
<?php if(($wrequest->customer_wash_points) == 1): ?>
<img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" />
<?php elseif($wrequest->customer_wash_points == 2): ?>
<img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" />
<?php elseif($wrequest->customer_wash_points == 3): ?>
<img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/gray-bubble.png" /><img class="last" src="images/gray-bubble.png" />
<?php elseif($wrequest->customer_wash_points == 4): ?>
<img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/gray-bubble.png" />
<?php elseif($wrequest->customer_wash_points == 5): ?>
<img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img src="images/blue-bubble2.png" /><img class="last" src="images/blue-bubble2.png" />
<?php endif; ?>
</p>
</td>
<td class="rightalign">
 <a class="view-btn" href="<?php echo $site_config_json['root_url']; ?>/receipt-view.php?orderid=<?php echo $wrequest->id; ?>">View</a>
</td>
</tr>
</table>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php if($total_pages > 1): ?>
<div class="pagination">
<ul>
<?php if($page_no != 1): ?>
<li><a href="account-history.php?id=<?php echo $client_id; ?>&page_no=1">1</a></li>
<li>...</li>
<?php endif; ?>
<?php if($page_no-1 > 0): ?>
<li><a href="account-history.php?id=<?php echo $client_id; ?>&page_no=<?php echo $page_no-1; ?>">&laquo;</a></li>
<?php endif; ?>

<li class="active"><?php echo $page_no; ?></li>
<?php if($page_no+1 <= $total_pages): ?>
<li><a href="account-history.php?id=<?php echo $client_id; ?>&page_no=<?php echo $page_no+1; ?>">&raquo;</a></li>
<?php endif; ?>
<?php if($page_no != $total_pages): ?>
<li>...</li>
<li><a href="account-history.php?id=<?php echo $client_id; ?>&page_no=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
<?php endif; ?>
</ul>
</div>
<?php endif; ?>
<?php else: ?>
<h2 style="padding: 20px; margin: 0;">No history found</h2>
<?php endif; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
$(function(){
$(".account-history tr").click(function(){
var orderid = $(this).data('id');
window.location.href="<?php echo $site_config_json['root_url']; ?>/receipt-view.php?orderid="+orderid;
});
});
</script>

</body>
</html>