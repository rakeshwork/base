/* get a new captcha and replace the existing image */

function refreshCaptcha(){
	//alert('test');
	
	$('#<?php echo $captcha_container_id;?>').block({ message: '<?php echo $waiting_gif_text;?>', css:{width:'60%'} });
	
	$.ajax({
		  type: 'POST',
		  url: "<?php echo $base_url;?>ajax/refresh_captcha",
		  success: function (data){
			//console.log( data.captcha );
			
			$('#captcha_image_id').replaceWith( data.captcha );
			$('#<?php echo $captcha_container_id;?>').unblock();
		  },
		  dataType: "json"
	});	
}


$(document).ready(function (){

/*to be moved to the captcha.js file later on*/
$('#captcha_refresh').click(function (){
//$('#captcha_refresh').livequery('click', function(event) { 

	//alert('test');
	refreshCaptcha();
});

});
