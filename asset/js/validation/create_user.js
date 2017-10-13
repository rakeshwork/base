$(document).ready(function(){
	$("#userCreateForm").validate({
		rules: {
			 username: {
			 	required:true,
			 	remote: '<?php echo $base_url;?>user/checkavailability/username/'
			 },/*
			 email_id: {
			   required: true,
			   email: true,
			   remote: '<?php echo $base_url;?>user/checkavailability/email_id/' + $('input[name="email_id"]').val()
			 },*/
		    first_name: {
		    	required:true
		    },/*
		    gender:{
		    	required:true
		    },*/

		},
		success: function(span) {
			// set &nbsp; as text for IE
			span.html("&nbsp;").addClass("form_label_success");
			
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "gender")
				error.insertAfter("#male_label");
			else
				error.insertAfter(element);
		
		}
		
	});
});