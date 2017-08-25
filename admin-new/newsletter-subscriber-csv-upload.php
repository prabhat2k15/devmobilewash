<?php
 if (!empty($_POST) && isset($_POST['csv-submit'])) {

            $error = array();

            if (isset($_FILES['csv']['tmp_name']) && is_uploaded_file($_FILES['csv']['tmp_name'])) {
                $extention = strtolower(strrchr($_FILES['csv']['name'],"."));
                if ($extention != '.csv') {
                 header("Location: http://www.devmobilewash.com/admin-new/newsletter-subscribers.php?action=csv-error1");
die();
                }
                else{
                 $file = fopen($_FILES['csv']['tmp_name'],
                        "r");
                if (count(fgetcsv($file)) < 1) {
//
                   header("Location: http://www.devmobilewash.com/admin-new/newsletter-subscribers.php?action=csv-error2");
die();
                } else {

                    $i = 1;
                    while (!feof($file)) {
                        $temp = array();
                        $fl = true;
                        $row = fgetcsv($file);

                        if (!$row) {
                            break;
                        }

 $url = 'http://www.devmobilewash.com/api/index.php?r=site/addnewslettersubscriber';

    $handle = curl_init($url);
        $data = array('name' => $row[0], 'email' => $row[1], 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($handle);
curl_close($handle);
$jsondata = json_decode($result);

                      // echo $row[0]." ".$row[1]."<br>";
                   $i++;
                }

              header("Location: http://www.devmobilewash.com/admin-new/newsletter-subscribers.php?action=csv-success");
die();
            }
        }
    }
}
?>