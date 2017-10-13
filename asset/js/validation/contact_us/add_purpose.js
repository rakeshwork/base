$(document).ready(function(){

$("#contactpurposeCreateForm").validate({
	rules: {
		title: {required:true},
		email: {required:true, email:true},
		reciever_name: {required:true},
		success_message: {required:true},
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
	},
	errorPlacement: function(error, element) {
			error.insertAfter(element);
	}

});
});
