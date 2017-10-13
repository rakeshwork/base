$(document).ready(function() {
tinyMCE.init({
        // General options
        mode : "textareas"
});

});



/*$(document).ready(function() {
	$('textarea.tinymce').tinymce({
		
		mode : "specific_textareas",
		editor_selector : "tinymce",
			
		// Location of TinyMCE script
		script_url : '<?php echo $tinymce_script_url;?>',

		// General options
		theme : "advanced",
		plugins : "autolink,lists,preview",
		
        formats : {
                bold : {inline : 'span', 'classes' : 'b', exact : true},
				italic : {inline : 'span', 'classes' : 'i'},
                underline : {inline : 'span', 'classes' : 'u', exact : true},

		},
		//content_css : "<?php echo $css_url, 'main.css';?>",


		
		// Theme options


		<?php echo $tinymce_button_collection_1;?>

		theme_advanced_toolbar_location : "<?php echo $tinymce_theme_advanced_toolbar_location;?>",
		theme_advanced_toolbar_align : "<?php echo $tinymce_theme_advanced_toolbar_align;?>",
		theme_advanced_statusbar_location : "<?php echo $tinymce_theme_advanced_statusbar_location;?>",
		theme_advanced_resizing : <?php echo $tinymce_theme_advanced_resizing;?>,
		theme_advanced_resizing_use_cookie : false
	});
});*/
