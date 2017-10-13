$(document).ready(function(){
$("#contactUsForm").validate({
	rules: {
		fname: {required:true},
		email: {required:true, email:true},
		captcha: {required:true},
		message: {required:true},
		contact_us_purpose:{
			required:true,
			min:1,
			}
	},
	messages: {
		contact_us_purpose:{min:"This field is required"}
	},
	submitHandler: function(form) {
		
		var form_temp = form;
		$('#captcha_container').block({
									message: '<?php echo $waiting_gif_text;?>',
									css:{width:'60%'} });
		
		$.ajax({
			type:'post',
			url:'<?php echo $base_url;?>ajax/check_valid_captcha',
			data:{"captcha":form.captcha.value},
			success: function(data){
				$('#captcha_container').unblock();
				if( data.success == 1 ){
					
					clearMessages();
					$('#validated_captcha_code').val(data.page);
					
					//alert( form.validated_captcha_code.value );
					$('#submitting').show();
					form.submit();
				} else {
					
					$('#captcha').val('');
					$('#captcha').focus();
					refreshCaptcha();
					clearMessages();
					$('#c').html(data.error);
					return false;
				}				
			},
			dataType:"json"
		});	
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
		//alert( label.parent().last().html() );
		//alert( label.parent().html() );
	},
	errorPlacement: function(error, element) {
		if (element.attr("name") == "message")
			error.insertAfter("#error_placement_comment");
		else if (element.attr("name") == "tac")
			error.insertAfter("#error_placement_tac");
		else
			error.insertAfter(element);
	}

});
});



<?php /*
	The "refresh cpatcha" image is made visible by JS.
	so a user will see this option only if JS is enabled
	
	extra files for upload are also diabled and made invisible by js
	
	Again added here for ease
	TO DO : move to a contact_us.js file outside /validation folder
	*/?>
$('#captcha_refresh').show();



$('.hide_files input[type="file"]').attr('disabled', 'disabled');
$('.hide_files').hide();