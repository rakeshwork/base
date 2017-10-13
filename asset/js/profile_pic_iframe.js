$(document).ready(function(){
	
	<?php /*Update the profile pic with the new image*/?>
	$('.profile_pic_popup_ok').click(function () {
		
		clearPreviewContainers();
		
		var new_image = $('#profile_pic_new_image').html();
		
 		if( $(this).attr('name') == 'none' ) {
			
 			
			$('#profile_pic_cont').html(function (){
				return $('#preview_cont_default').html();
			});
			$.post(base_url + 'profile/set_profile_pic/none/' + '<?php echo $account_no;?>' + '/' + '<?php echo $profile_default_pic_name;?>');
			
		} else if( $(this).attr('name') == 'facebook' ) {
			
			
			$('#profile_pic_cont').html(function (){
				
				//alert($('#preview_cont_facebook').html());
				
				return $('#preview_cont_facebook').html();
			});
			$.post(base_url + 'profile/set_profile_pic/facebook/' + '<?php echo $account_no;?>' );
			
		} else if( new_image != '') {
			
			
			$('#profile_pic_cont').html(new_image);

			//alert('post REPLACE');
			if( $(this).attr('name') == 'upload' ){
				
				$('.profile_pic_popup_ok').data('account_no');
				$.post(base_url + 'profile/set_profile_pic/upload/' + $(this).data('account_no')  + '/' + $(this).data('file_name'));
			} else {
				
				$.post(base_url + 'profile/set_profile_pic/url/' + '<?php echo $account_no;?>');
			}
		}
		
		
		parent.$.fancybox.close();
	});
	
});