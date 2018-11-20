<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="<?php echo ROOT_URL; ?>/admin-new/js/js.cookie.js"></script>
<script>
$(function(){
$("#admin_agent_profile_update").click(function(){
if($("#admin_agent_profile_form input[name=pass]").val() != $("#admin_agent_profile_form input[name=confirm_pass]").val()){
alert('Password and Confirm Password not matching');
}
else $("#admin_agent_profile_form").submit();
});
});
</script>
<script>

	    $(function(){
		
		setInterval(function(){
		    
		    $.getJSON( "<?php echo ROOT_URL; ?>/api/index.php?r=users/checkadminuserlastactivetime", { device_token: "<?php echo $device_token; ?>", key: "<?php echo API_KEY; ?>", api_token: "<?php echo $finalusertoken; ?>", t1: "<?php echo $mw_admin_auth_arr[2]; ?>", t2: "<?php echo $mw_admin_auth_arr[3]; ?>", user_type: 'admin', user_id: "<?php echo $mw_admin_auth_arr[4]; ?>"}, function(data){
if((data.result == 'true') && (data.response == 'logout user')){
    Cookies.remove('mw_admin_auth', { path: '/', domain: '.devmobilewash.com' });
window.location.href = "<?php echo ROOT_URL; ?>/admin-new/login.php";
}

});
		    
		    }, 600000); // 10 mins interval
	    });
	</script>
</body>
</html>