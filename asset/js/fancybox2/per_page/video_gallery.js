$(document).ready(function(){

	//$(".fancybox").fancybox();
	$(".fancybox").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers:  {
			 title : {
				 type : 'inside'
			 }
		},
        beforeLoad: function() {
            this.title = $(this.element).attr('caption');
        }
	});
	
	
});
