<h3><?php echo $page_heading;?></h3>
<?php showMessage();?>

<div class="row">
<div class="col-md-4 col-md-offset-4">
<?php echo form_open_multipart('account/recovery_process/password_recovery/'.$sToken.'/'.$sTokenUniqueIdentification, array('id' => 'newPasswordForm'))?>

<div></div>
	
	<div class="row">
		<label for="title">New Password</label>
		<input type="password" class="col-md-12" id="password" value="" name="password"/>
	</div>
	<div class="row">
		<label for="title">Confirm new password</label>
		<input type="password" class="col-md-12" value="" name="password_again"/>
	</div>
	
	<div class="row">
		
		<?php echo $sCaptcha;?>
	</div>
	
	<div class="row m-t-15">
			<input type="submit" name="reset_password" value="Reset Password" class="btn btn-default btn btn-default-primary"/>
			<?php echo backButton('', 'Back');?>
	</div>
	

<?php echo form_close(); ?>
</div>
</div>