
$(document).ready(function(){


	//header - actions menu
	$(".header_actions_menu .fancybox").fancybox({
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
