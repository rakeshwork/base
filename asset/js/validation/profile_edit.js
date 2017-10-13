$(document).ready(function(){
	
<?php /*profile information form*/?>

var profileEdit = $("#profileEdit");
profileEdit.validate({
	rules: {
		first_name: {required:true},
		gender: {required:true}
		
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
		
	},
	errorPlacement: function(error, element) {
		if (element.attr("name") == "gender")
			error.insertAfter("#male_label");
		else
			error.insertAfter(element);
	
	}
});

$('.district_select').change(function() {
	
	var id = $(this).attr('id');
	var district_id = $(this).val();
	var url = '<?php echo $base_url;?>' + 'address/get_ward/' + district_id;
	
	
	var num = $(this).attr('data-num');
	
	if( district_id !=0 ) {
	
		$.get( url, function (data) {
			
			ward_data = data.page;
			
			if( data.success == 1 ) {
				
				var ward_id = '#ward_' + num;
				
				$(ward_id).empty();
				
				$.each(ward_data, function (key, value) {
					
					$(ward_id).append(new Option(value, key));	
				});	
			}
		});	
	}
	
	
	
	
});

});

