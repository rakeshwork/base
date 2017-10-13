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
	
	if(confirm("Event will be completely deleted from the system.\nDo you want to continue?")){
		
		//var p = $(this).parent().parent().parent().attr('class').split(" ");
		//var c=p[2];
		//$('.' + c).block({ message: '<img class="l" src="<?php echo $base_url;?>images/<?php echo $waiting_img;?>' + '"><div class="waiting_gif_text l">PLease Wait</span>' });
		
		$(this).parent().parent().parent().remove();
		
		$.post(
		"<?php echo $base_url;?>" + "event/perm_delete/" + $(this).attr('id'),
		{},
		function (data){
			if(data.error == ''){
				//$('.'+data.c).unblock();
				//alert('The event has been permanently delete from the system');
				window.location.reload();
			} else {
				if (data.error == <?php echo $error_types['not_logged_in']?>){
					window.location = "<?php echo $base_url.'user/login';?>";
				}
			}
		},
		"json"
		);		
	}

});


$('.event_filter').change(function(){
	gotoPage("event/listing/" + $('#f_status').val())
});

});

