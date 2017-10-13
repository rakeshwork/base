function getLinkParents(section_id){

$.get(
	base_url + 'sitemaps/get_link_parents/' + section_id,
	{},
	function (data){
		$('#parent').replaceWith(data.page);
	},
	'json'
);

}