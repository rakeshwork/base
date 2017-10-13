<?php 
/*the captcha part of a form is made into a view, so that any changes need to be made only in a single file.*/
?>
<div class="captcha_image p-t-10 clearfix" title="get a new captcha image">
	
	<?php echo $image;?>
	<div id="captcha_refresh" class="captcha_refresh r">&nbsp;</div>
</div>
<div class="help-block">Enter the characters as seen in the image above</div>
<div class="help-block">The letters are NOT case sensitive.</div>
<input type="text" value="" class="form-control" name="captcha"/>