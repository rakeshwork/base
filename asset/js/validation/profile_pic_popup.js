$(document).ready(function(){
	
profilePicPopupUrl = $("#profilePicPopupUrl");
profilePicPopupUrl.validate({
	rules: {
		url: {required:true}
		
	},
	success: function(label) {
		// set &nbsp; as text for IE
		label.html("&nbsp;").addClass("form_label_success");
		
	},
	submitHandler: function(form) {
		
		<?php //show loading div?>
		$('#profilePicPopupUrl').block({ message: '<?php echo $waiting_gif_text;?>' });
		
		$.post(
		base_url + 'profile/upload_profile_image/url/<?php echo $account_no;?>',
		{
			url:$(form).find('input[name="url"]').val()
		},
		function (data){

			$('#profilePicPopupUrl').unblock(); 
			highlightMessages();
			
			if(data.error == ''){
				
				if(data.page != ''){
					<?php //show preview of image?>
					$('#preview_cont_url').html( data.page);
					$('#profile_pic_new_image').html(data.page);
					$('.profile_pic_popup_ok').show();
				} else {
					alert('there was some problem. Please Try later');
					$.fancybox.close();
				}
				
			} else {
				
				if(data.error_type == <?php echo $error_types['not_logged_in'];?>){
					
					gotoPage('user/login');
				} else {
					$('#profilePicPopupUrl').before(data.error);
				}
			}
		},
		"json"
		);
		return false;
   }
});




});

