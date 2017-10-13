$(document).ready(function(){
	$("#registrationForm").validate({
		rules: {
			 username: {
			 	required:true,
			 	remote: '<?php echo $base_url;?>user/checkavailability/username/' + $('input[name="username"]').val()
			 },
			 email_id: {
			   required: true,
			   email: true,
			   remote: '<?php echo $base_url;?>user/checkavailability/email_id/' + $('input[name="email_id"]').val()
			 },
			 password:{
			 	required:true,
			 	minlength:<?php echo $password_min_length;?>
			 },
		    password_again: {
		    	required:true,
		    	equalTo: "#password"
		    },
		    first_name: {required:true},
		    last_name: {required:true},
		    gender:{min:1,required:true},
		    dob:{required:true}
		},
		messages: {
			gender:{min:"This field is required"}
		},
		success: function(span) {
			// set &nbsp; as text for IE
			span.html("&nbsp;").addClass("form_label_success");

		},
		errorPlacement: function(error, element) {
				error.insertAfter(element);

		}

	});
});
