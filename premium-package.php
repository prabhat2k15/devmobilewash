<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<style>

body{

    font-family: 'Lato', sans-serif;
margin: 0;
padding: 0;
    background: #fff;
}

.premium-pop{
    position: relative;
}

.premium-pop .popular-ribbon{
    position: absolute;
    right: 0;
    top: 0;
    width: 90px;
}

.premium-pop .heading {
    padding: 15px 20px;
    min-height: 95px;
    /* padding: 20px; */
}

.premium-pop .heading img {
        float: none;
    text-align: center;
    margin: 0;
    display: block;
    margin: 0 auto;
}

.premium-pop .heading h3 {
    display: block !important;
     margin: 0;
    margin-top: 10px;
    text-align: center;
    font-weight: 400;
    font-size: 34px;

}

.premium-pop .pack-desc {
    color: #fff;
    background: #4d8eff;
    padding: 20px;
    margin-bottom: 0;
    font-size: 18px;
    margin-top: 0;
}

.premium-pop .popup-content {
    padding: 5px 20px;
    font-size: 16px;
    font-weight: 400;
}

.premium-pop .popup-content ul {
    list-style: none;
    margin: 0;
    padding: 0;
    margin: 10px 0 20px 0;
}

.premium-pop .popup-content ul li {
    display: block;
    background: url(images/blue_check.png) no-repeat 0 4px;
    padding-left: 30px;
    font-size: 18px;
    margin-bottom: 12px;
    font-weight: 400;
    background-size: 15px;
}

</style>
</head>
<body>
<div class="premium-pop">
    <?php if(!$_GET['hidebanner']): ?>
<img src="images/best-deal-ribbon.png" class="popular-ribbon">
    <?php endif; ?>
         <div class="heading">
<img src="images/premium-icon-large.png" alt="" width="111">
<h3 style="display: inline-block;">Premium Detail</h3>
</div>
<p class="pack-desc">Our Premium Detail Package goes above and beyond to satisfy your highest level of expectations and also the most affordable in the industry brought directly to your doorstep in minutes.</p>
         <div class="popup-content">

            <ul>
              <li>Complete exterior hand wash</li>

<li>Wipe down all door jambs &amp; trunk seals</li>

<li>Vacuum seats, carpets, &amp; floor mats</li>

<li>Clean windows inside &amp; out</li>

<li>Rim cleaning &amp; tire dressing</li>

<li style="color: #076fe1;">Thorough wipe down of interior, including dashboard, door panels, center console, pillars &amp; trim pieces</li>

<li style="color: #076fe1;">Dressing of all exterior plastics</li>

<li style="color: #076fe1;">Light stain removal of interior (excluding carpet)</li>

<li style="color: #076fe1;">Leather cleaning &amp; conditioning</li>

<li style="color: #076fe1;">Full exterior hand wax (Liquid form)</li>

            </ul>

         </div>
         </div>
</body>
</html>