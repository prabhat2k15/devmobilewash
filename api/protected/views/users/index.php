<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="<?php echo Yii::app()->getBaseUrl(true).'/js/jquery.validate.min.js'; ?>"></script>
<style type="text/css">
	.error {
		color:#f00;
	}
</style>
<center>
	<form id="form" action="" method="post">
	<table>
		<tr><td colspan=2><h1>Reset password</h1><hr></td></tr>
		<tr>
			<td style="text-align:right" >New Password </td><td><input type="password" name="newpassword" id="newpassword" /></td>
		</tr>	
		<tr>
			<td style="text-align:right" >Confirm Password </td>
			<td><input type="password" name="cnfpassword" id="cnfpassword" />
			<input type="hidden" name="id" value="<?php echo $id; ?>" /> </td>
		</tr>	
		<tr>
			<td colspan=2 style="text-align:center;color:#F40000;">
				<?php echo $message ?><br/>
			</td>
		</tr>					
		<tr>	
			<td></td>
			<td><input type="submit" name="submit" value="Submit" /></td>
		</tr>	
	</table>	
	</form>
</center>
<script type="text/javascript">
$(document).ready(function(){
$("#form").validate({
                rules: {
                    newpassword: "required", 
					cnfpassword: {
						required: true,
                        equalTo: "#newpassword"
					}					
                },
                messages: {
                    newpassword: {required:"Please enter your Password"},                   
                    cnfpassword: {required:"Please enter your Confirm Password",
								  equalTo: "Please enter the same password as above"	
								},                   
                    //equalTo: "Please enter same password",                   
				}
});	
});
</script>
