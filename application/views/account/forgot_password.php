<h4><?php echo $page_heading;?></h4>

<?php showMessage();?>

<div class="row">
<div class="col-md-12">
<?php echo form_open_multipart('account/forgot/password', array('id' => 'forgotPasswordForm'))?>

<div></div>
	
	<div class="form-group">
		<label for="username">Username</label>
		<input type="text" value="" name="username" class="form-control"/>
	</div>
	
	<div class="form-group">
		
		<?php echo $sCaptcha;?>
		
	</div>
	<div class="form-group">
			<input type="submit" class="btn btn-default" value="Start Password Recovery"/>
			<input type="button" class="btn btn-primary" value="Cancel" id="passRecCancel"/>
			
	</div>
	

<?php echo form_close(); ?>
</div>
</div>


<?php echo load_files('js', true, array('captcha.js','misc/forgot_loaded.js', 'validation/forgot_password.js'));?>