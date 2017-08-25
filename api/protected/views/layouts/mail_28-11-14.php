<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<table cellspacing="0" cellpadding="10" style="color:#666; font:13px Arial; line-height:1.4em; width:80%; margin:0 10%; background:none repeat scroll 0 0 #fff; border:1px solid #0E4D6E;">
	<tbody>
		<tr>
            <td style="color:#0E4D6E;font-size:22px;border-bottom: 2px solid #0E4D6E;">
				<?php //echo CHtml::encode(Yii::app()->name); 
				$imgurl = $_SERVER['SERVER_NAME'].'/images/logo.png';
				$imgurl = 'http://dev1.trigma.us/h2o/images/logo.png';
				?>
				<img src="<?php echo $imgurl;?>" alt='Happiness 2 Others' height='125px'>
            </td>
		</tr>
		<tr>
            <td style="color:#777;font-size:16px;padding-top:5px;">
            	<?php if(isset($data['description'])) echo $data['description'];  ?>
            </td>
		</tr>
		<tr>
            <td>
				<?php echo $content ?>
            </td>
		</tr>
	</tbody>
</table>
</body>
</html>