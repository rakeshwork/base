$(document).ready(function(){
$("#changePasswordForm").validate({
	rules: {
		 current_password: {required:true},
		 captcha: {required:true},
		 new_password: {required:true, minlength:<?php echo $password_min_length;?>},
		 new_password_confirm: {required:true, minlength:<?php echo $password_min_length;?>, equalTo: "#new_password"}
	},
	messages: {
		new_password_confirm:"Should be same as New Password"
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
		
	}, 
//	submitHandler: function(form) {
//		
////		if( !confirm("Are you sure you want tot delete this account? \n This action cannot be undone!!") ){
////			return false;
////		}
//	}

});
});