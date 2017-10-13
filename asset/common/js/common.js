function gotoPage(uri) {
	window.location = "<?php echo $base_url;?>" + uri;  
}
 
<?php /* Clear any success/failure messages */?>
function clearMessages() {
	
	$('.error_msg').remove();
	$('.success_msg').remove();
	$('.info_msg').remove();
}
<?php /* highlight any success/failure messages in the page*/?>
function highlightMessages(){
	//alert('test');
	//$('.success_message').fadeOut('slow');$('.success_message').fadeIn('slow');
	
}

function resizeIframe(obj) {
  obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}



<?php 
/**
 * Used when admin section, when the admin clicks on the edit/ delete button in the grid of contents
 */
?>
function takeAction(action, destination_uri, text){

	
	if(action == 'edit'){
		
		gotoPage( destination_uri );
	} else if (action == 'delete'){
		if ( confirm(text) ){
			gotoPage( destination_uri );
		}
	}
	return false;
}


function bs_hide(selector){
	
	$( selector ).addClass('hide');
}

function bs_show(selector){
	$( selector ).removeClass('hide');
}
