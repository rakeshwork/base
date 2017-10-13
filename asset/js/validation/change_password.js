$(document).ready(function(){
$("#changePasswordForm").validate({
	rules: {
		 current_password: {required:true},
		 new_password: {required:true, minlength:<?php echo $password_min_length;?>},
		 password_again: {required:true, minlength:<?php echo $password_min_length;?>, equalTo: "#new_password"}
	},
	messages: {
		password_again:"Should be same as New Password"
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
		
	}
});
});