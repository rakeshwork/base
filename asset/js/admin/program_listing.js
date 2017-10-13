$(document).ready(function(){

$('.perm_delete').click(function(){
	
	if(confirm("Program will be completely deleted from the system.\nDo you want to continue?")){
		
		//var p = $(this).parent().parent().parent().attr('class').split(" ");
		//var c=p[2];
		//$('.' + c).block({ message: '<?php echo $waiting_gif_text;?>' });
		
		$(this).parent().parent().parent().remove();
		
		$.post(
		"<?php echo $base_url;?>" + "program/delete_program/" + $(this).attr('id'),
		{},
		function (data){
			if(data.error == ''){
				//$('.'+data.c).unblock();
				//alert('The program has been permanently delete from the system');
				window.location.reload();
			} else {
				if (data.error_type == <?php echo $error_types['not_logged_in']?>){
					window.location = "<?php echo $base_url.'user/login';?>";
				} else {
					$('.grid_container').insertBefore()
				}
			}
		},
		"json"
		);		
	}

});


$('.status_filter').change(function(){
	gotoPage("program/listing/" + $('#f_status').val())
});

});

