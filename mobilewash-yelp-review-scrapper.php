<?php 
//phpinfo();
include_once('simple-dom/simple_html_dom.php');
function dlPage($href) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $str = curl_exec($curl);
    curl_close($curl);

    // Create a DOM object
    $dom = new simple_html_dom();
    // Load HTML from a string
    $dom->load($str);

    return $dom;
    }

$all_reviews = array();
$url = 'https://www.yelp.com/biz/mobilewash-los-angeles?sort_by=date_desc';
$data = dlPage($url);
$ind = 0;
foreach($data->find('.feed .ylist > li') as $element){
    $review = '';
    $username = '';
    $userpic = '';
    $userlocation = '';
    $reviewid = '';
    $rating = '';
    $reviewdate = '';
    $review = $element->find('.review-content p[lang=en]', 0)->plaintext;
    $username = $element->find('.review-sidebar .user-name a', 0)->plaintext;
    $userpic = $element->find('.review-sidebar .media-avatar a img', 0)->src;
    $userlocation = $element->find('.review-sidebar .user-location b', 0)->plaintext;
    $reviewid = $element->find('.review', 0)->attr['data-review-id'];
    $rating = $element->find('.review-content .rating-large', 0)->attr['title'];
    $rating = str_replace(" star rating", "", $rating);
    $reviewdate = $element->find('.review-content .rating-qualifier', 0)->plaintext;
    $reviewdate = str_replace(" Updated review", "", $reviewdate);
    $reviewdate = trim($reviewdate);
    
    if($review) {
        $all_reviews[$ind]->reviewid = $reviewid;
        $all_reviews[$ind]->review = $review;
    $all_reviews[$ind]->username = $username;
    $all_reviews[$ind]->userpic = $userpic;
    $all_reviews[$ind]->userlocation = $userlocation;
    $all_reviews[$ind]->rating = $rating;
    $all_reviews[$ind]->reviewdate = date('Y-m-d', strtotime($reviewdate));
    
        
    }
   $ind++;
} 

$all_reviews = array_values($all_reviews);

$data = array('reviews'=> json_encode($all_reviews), 'key' => 'Tva4hwH9KvqEQHTz5nHZTLhAV7Bv68AAtBeAHMA4');

            $handle = curl_init("https://www.getmobilewash.com/api/index.php?r=site/addyelpreviews");
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($handle);
            curl_close($handle);
            $jsondata = json_decode($result);
            //print_r($jsondata);

//echo json_encode($all_reviews);

?>