<?php
require_once('api/protected/config/constant.php');
ini_set("date.timezone", "America/Los_Angeles");


/* --- washing kart call --- */

$handle = curl_init(ROOT_URL."/api/index.php?r=site/allwashes");
$data = array('key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$allwashes = json_decode($result);

/* --- washing kart call end --- */


?>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>

</head>
<body>
<a href="#" class="start">start</a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>


    $(function(){

        $(".start").click(function(){
            var th = $(this);

               var allwashes = new Array();
    <?php foreach($allwashes->all_washes as $key => $val){

        ?>
        allwashes.push('<?php echo $val->id; ?>');
    <?php } ?>
    //console.log(allcars.length);
     $.each(allwashes, function( index, item ) {
         $.post( "<?php echo ROOT_URL; ?>/api/index.php?r=site/createsingleorderpricinghistory", {wash_request_id: item, key: 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4'}, function( data ) {
 console.log(data);

});
        //console.log(item);

     });
     //console.log(count);

     return false;
        });



    });
</script>
</body>
</html>