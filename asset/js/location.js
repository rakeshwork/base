function getSubLocations(id, type, update_id) {
	
	var update_id = update_id ? update_id : type;
	
	$.post(
	base_url + 'location/get_sub_locations/' + type + '/' + id,
	{},
	function (data){
		
		if( update_id ){
			
			$('#'+update_id).html(data.page);
			$('#'+update_id).removeAttr('disabled');
		}

		
	},
	'json'
	);
}