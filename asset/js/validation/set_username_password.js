$(document).ready(function(){
	$("#usernamePasswordForm").validate({
		rules: {
			 username: {
			 	required:true,
			 	remote: '<?php echo $base_url;?>user/checkavailability/username/' + $('input[name="username"]').val()
			 },
			 password:{
			 	required:true,
			 	minlength:6
			 },
		    password_again: {
		    	required:true,
		    	equalTo: "#password"
		    },
		},
		success: function(span) {
			// set &nbsp; as text for IE
			span.html("&nbsp;").addClass("form_label_success");
			
		}		
	});
});