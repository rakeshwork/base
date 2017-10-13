
<?php
/**
 * Stuff to do when the cancel button of a fancybox is clicked.
 * for various contexts.
 */
?>
function fncyboxPostCancel(elem){
	
	<?php /*
	
	ppc - profile pic popup
	
	*/ ?>
	
	var classArray = elem.attr('class').split(' ');
	if($.inArray('ppc', classArray) > 0){
		
		$.get(
		
			'<?php echo $base_url, "profile/profile_pic_cancel", "/", isset($iAccountNo) ? $iAccountNo : 0 ;?>'
		);
	}
	
}