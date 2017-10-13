$(document).ready(function(){
	$('.status').change(function(){
		var lang = $(this).attr('lang');
		var val = $(this).val();
		var id = $(this).attr('id');
		$(this).attr('disabled', 'disabled');
		
		
		$.post(
		"<?php echo $base_url, 'user/set'?>/" + lang + "/status/" + val + "/" + id ,
		{},
		function(data){
			$("#"+data.id).removeAttr('disabled')
		},
		'json'
		);
	});
	


$('.perm_delete').click(function(){
	
	if(confirm("user will be completely deleted from the system.\nDo you want to continue?")){
		
		//var p = $(this).parent().parent().parent().attr('class').split(" ");
		//var c=p[2];
		//$('.' + c).block({ message: '<img class="l" src="<?php echo $base_url;?>images/<?php echo $waiting_img;?>' + '"><div class="waiting_gif_text l">PLease Wait</span>' });
		
		$.post(
		"<?php echo $base_url;?>" + "user/perm_delete/" + $(this).attr('id'),
		{},
		function (data){
			if(data.error == ''){
				$('.'+data.c).unblock();
				alert('The user has been permanently delete from the system');
				window.location.reload();
			} else {
				if (data.error_type == <?php echo $error_types['not_logged_in']?>){
					window.location = "<?php echo $base_url.'user/login';?>";
				}
			}
		},
		"json"
		);		
	}

});
$('.close_account, .logout_user').click(function(){
	
	var confirmation_message = '';
	var action = '';
	if( $(this).hasClass('close_account') ){
		confirmation_message = "All data related to this user will be deleted. But user will not be deleted. \nThis step cannot be revoked";
		action = 'close_account';
	} else if ( $(this).hasClass('logout_user') ){
		confirmation_message = "Are you sure?";
		action = 'logout';
	}
	
	if(confirm(confirmation_message)){
		
		//var p = $(this).parent().parent().parent().attr('class').split(" ");
		//var c=p[2];
		//$('.' + c).block({ message: '<?php echo $waiting_gif_text;?>' });
		
		$(this).parent().parent().parent().remove();
		
		$.post(
		"<?php echo $base_url;?>" + "user/take_action/" + action + "/" + $(this).attr('id')
		+ '?current_url=' + encodeURIComponent(window.location),
		{},
		function (data){
			if(data.error == ''){
				//$('.'+data.c).unblock();
				//alert(data.success);
				window.location.reload();
			} else {
				if (data.error_type == <?php echo $error_types['not_logged_in']?>){
					window.location = "<?php echo $base_url.'user/login';?>";
				}
			}
		},
		"json"
		);		
	}

});


$('.user_filter').change(function(){
	//gotoPage( "user/listing/" + $('#f_status').val() + "/" + $('#f_gender').val() + "/" + $('#f_role').val() )
	gotoPage( "user/listing/" + $('#f_status').val() + "/" + $('#f_gender').val() );
});

});

