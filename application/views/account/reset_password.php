<h3><?php echo $page_heading;?></h3>
<?php showMessage();?>

<div class="row">
<div class="col-md-4 col-md-offset-4">
<?php echo form_open_multipart('account/reset_password/'.$sToken.'/'.$sTokenUniqueIdentification, array('id' => 'newPasswordForm'))?>

	<div class="form-group">
		<label for="title">New Password</label>
		<input type="password" class="form-control" id="password" value="" name="password"/>
	</div>

	<div class="form-group">
		<label for="title">Confirm new password</label>
		<input type="password" class="form-control" value="" name="password_again"/>
	</div>

	<div class="form-group">
			<input type="submit" name="reset_password" value="Reset Password" class="btn btn-default"/>
			<?php echo backButton('', 'Back');?>
	</div>

<?php echo form_close(); ?>
</div>
</div>
