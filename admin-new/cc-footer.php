<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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
</body>
</html>