$(document).ready(function(){
	
	$("#forgotUsernameForm").validate({
		rules: {
			 email: {required:true, email: true},
			 captcha: {required:true}
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("form_label_success");
			
		},
		submitHandler: function(form) {

			$('#forgot_form_container').block({ message: '<?php echo $waiting_gif_text;?>', css:{width:'60%'} });
			
			$.post(
				"<?php echo $base_url;?>" + "account/forgot/username",
				{
					'email' :  $(form).find('input[name="email"]').val(),
					'captcha' :  $(form).find('input[name="captcha"]').val()
				},
				function(data){
					
					$('#forgot_form_container').unblock();
					
					if(data.error != ''){
						
						//replace with the new form and show the error message
						$('#forgot_form_container').html(data.page);
						$('.forgot_form').before(data.error);
						
					} else {
						clearMessages();
						$('.forgot_form').before(data.success);
						$('.forgot_form').html('');
						
						//make the radio buttons active
						$('input[name="forgot"]').removeAttr('disabled');
						$('input[name="forgot"]').removeAttr('checked');
					}
				},
				"json"
			);
			
			return false;
		}
	});
	
});