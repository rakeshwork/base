$(document).ready(function() {
	$(".fancybox").fancybox({
        //width       : '50%',
		fitToView	: false,
		autoSize	: true,
		closeClick	: false,
	});
    
    
    $('#writeToMeForm').submit(function ( event ){
        
        $.ajax({
            type: "POST",
            url: "<?php echo $base_url, 'user/write_to_me_submit'?>",
            data: $("#writeToMeForm").serialize(), // serializes the form's elements.
            success: function(data)
            {
                //close the pop up, and show the success message in the page.
                
            }
       });
    });
    
});