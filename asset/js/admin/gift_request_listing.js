$(document).ready(function(){

	$('.delete').click(function(event){
		
		if(confirm("Gift Request will be permanently deleted from the system.\nDo you want to continue?")){
			
			//alert('collection_point/delete/' +  $(this).attr('id'));
			
			gotoPage( 'gift_request/delete/' +  $(this).attr('id') );
		}
        
        event.preventDetafault();
	
	});
    
    
    $('#search').click(submit_task_search_form);

	function submit_task_search_form(event) {
		
		
		var sUri = 	'gift_request/listing?' + 
					'&order_by=' + $('#order_by').val() + 
					'&direction=' + $('#direction').val() +
					'&status=' + $('#f_status').val() +
                    '&public_uid=' + encodeURI($('#f_public_uid').val()) +
                    '&organization=' + $('#f_organization').val()                    
                    ;
		
		//console.log( sUri );
		gotoPage( sUri );
		
		event.stopPropagation();
		event.preventDefault();
		
	}

});

