$(document).ready(function(){
	<?php //fancy box Cancel button when in side an iframe?>
	$(".fncybx_cncl_btn_if").on('click', function (event){
		parent.$.fancybox.close();
		
		<?php /*stuff to do after a fancy box has been closed*/?>
		fncyboxPostCancel($(this));
	});
});

