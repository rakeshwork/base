$(document).ready(function(){

	$('.delete').click(function(){
		
		if(confirm("Collection point will be permanently deleted from the system.\nDo you want to continue?")){
			
			//alert('collection_point/delete/' +  $(this).attr('id'));
			
			gotoPage( 'collection_point/delete/' +  $(this).attr('id') );
		}
	
	});

});

