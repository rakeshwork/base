$(document).ready(function(){

	<?php //Form Validation?>	
	<?php //popup box?>	
	$("a#profile_pic_popup").fancybox({
		width:500,
		autoDimensions:true,
		hideOnOverlayClick:false,
		showCloseButton:false,
		enableEscapeButton:false
	});
	
	

	
});

<?php //clear all preview containers?>
function clearPreviewContainers(){
			
		$('#preview_cont_upload').html('&nbsp;');
		$('#preview_cont_url').html('&nbsp;');
}