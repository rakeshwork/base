<h4><?php echo $page_heading;?></h4>

<?php showMessage();?>

<div class="row">
<div class="col-md-4 col-md-offset-4">
<?php echo form_open('account/password_recovery', array('id' => 'passwordRecoveryForm'))?>


	<div class="form-group">
		<label for="email_id">Enter the Email ID used during registration</label>
		<input type="text" value="" name="email_id" class="form-control"/>
	</div>

	<div class="form-group">
			<input type="submit" class="btn btn-default" value="Start Password Recovery"/>
			<input type="button" class="btn btn-primary" value="Cancel" id="passRecCancel"/>

	</div>

<?php echo form_close(); ?>
</div>
</div>
