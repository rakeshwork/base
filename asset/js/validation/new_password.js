  $(document).ready(function(){
	$("#newPasswordForm").validate({
		rules: {
			 password: {required:true, minlength:<?php echo $password_min_length;?>},
			 captcha: {required:true},
			 password_again: {required:true, minlength:<?php echo $password_min_length;?>, equalTo: "#password"}
		},
		messages: {
			password_again:{equalTo:"Should be same as New Password"}
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("form_label_success");
			
		}
	});
  });