$(document).ready(function(){
	
	<?php /*Update the profile pic with the new image*/?>
	$('.profile_pic_popup_ok').click(function () {
		
		clearPreviewContainers();
		
		//var new_image = $('#profile_pic_new_image').html();
		
		switch( $(this).attr('name') ) {
			
			case 'none':
				$('#profile_pic_cont').html(function (){
					return $('#preview_cont_default').html();
				});
				$.post('<?php echo $base_url;?>' + 'profile/set_profile_pic/none/' + '<?php echo $account_no;?>' + '/' + '<?php echo $profile_default_pic_name;?>');
				break;
			
			case 'facebook':
				
				$('#profile_pic_cont').html(function (){
					
					//alert($('#preview_cont_facebook').html());
					
					return $('#preview_cont_facebook').html();
				});
				$.post('<?php echo $base_url;?>' + 'profile/set_profile_pic/facebook/' + '<?php echo $account_no;?>' );
				break;
			
			case 'upload':
				
				$('.profile_pic_popup_ok').data('account_no');
				$.post('<?php echo $base_url;?>' + 'profile/set_profile_pic/upload/<?php echo $account_no;?>/' + $(this).data('file_name'));	
				break;
			
			case 'url':
				
				$.post('<?php echo $base_url;?>' + 'profile/set_profile_pic/url/' + '<?php echo $account_no;?>');	
				break;
		}
		
		
		gotoPage('profile/edit/<?php echo $account_no;?>');
		
	});
	
});