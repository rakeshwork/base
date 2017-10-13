$(document).ready(function(){

	$('.delete').click(function(event){
		
		if(confirm("Deleting a Gift item will permanently deleted it from the system.\n"
                   + "\n and it will be removed from the Gift request(if any) which currently uses it"
                   + "\nDo you want to continue?")){
			
			//alert('collection_point/delete/' +  $(this).attr('id'));
			
			gotoPage( 'gift_item/delete/' +  $(this).attr('id') );
		}
        
        event.preventDetafault();
	
	});
    
    
    $('#search').click(submit_task_search_form);

	function submit_task_search_form(event) {
		
		
		var sUri = 	'gift_item/listing?' + 
					'&order_by=' + $('#order_by').val() + 
					'&direction=' + $('#direction').val() +
					'&status=' + $('#f_status').val()
                    ;
		
		//console.log( sUri );
		gotoPage( sUri );
		
		event.stopPropagation();
		event.preventDefault();
		
	}

});

