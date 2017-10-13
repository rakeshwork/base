$(document).ready(function(){

	$('.perm_delete').click(function(event){

		event.preventDefault();
		
		if( confirm("Are you sure you want to continue? This action cannot be undon.") ) {

			gotoPage('contact_purpose/delete_purpose/' + $(this).attr('data-id'));
		}

	});

});
