<h3><?php echo @$page_heading;?></h3>

<?php showMessage();?>
<?php echo form_open_multipart('account/change_password', array('id' => 'changePasswordForm'))?>
<div class="form-group">
	<div class="col-md-12">

		<div class="col-md-4 col-md-offset-4">

			<div class="form-group">
				<label for="current_password">Current Password</label>
				<input type="password" class="form-control" value="" id="current_password" name="current_password"/>

			</div>

			<div class="form-group">
				<label for="new_password">New Password</label>
				<input type="password" class="form-control" value="" id="new_password" name="new_password"/>
			</div>

			<div class="form-group">
				<label for="new_password_confirm">Confirm New Password</label>
				<input type="password" class="form-control" value="" id="password_again" name="password_again"/>
			</div>

			<div class="form-group">
				
				<input type="submit" value="Change Password" class="btn btn-default"/>
				<?php echo backButton('', 'Back');?>
			</div>

		</div>
	</div>
</div>
<?php echo form_close();?>
