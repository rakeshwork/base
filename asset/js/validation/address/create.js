$(document).ready(function(){
	$("#contactInfoForm").validate({
		rules: {
			city: {
			 	required:true,
			 	min:1
			},
			 pincode: {
			 	required:true
			}
			
		},
		messages: {
			city:{min:"This field is required"}
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