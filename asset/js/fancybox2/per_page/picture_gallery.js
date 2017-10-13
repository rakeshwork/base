$(document).ready(function(){

	//$(".fancybox").fancybox();
	$(".fancybox").fancybox({
		prevEffect		: 'none',
		nextEffect		: 'none',
		closeBtn		: false,
		helpers		: {
			title	: { type : 'inside' },
			buttons	: {}
		},
        beforeLoad: function() {
            this.title = $(this.element).attr('caption');
        }
	});
	
});
