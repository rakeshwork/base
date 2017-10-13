<?php showMessage();?>

<?php echo form_open_multipart('account/change_password', array('id' => 'recoveryForm'))?>

<div class="row">
	<div class="col-md-6 col-md-offset-4">
		
		<h3>What did you forget</h3>
		
		<div class="form-group">
			<div class="radio">
				<label for="username">
					<input type="radio" value="1" name="forgot" id="username"/>
					I forgot my Username
				</label>
			</div>
		</div>
		
		<div class="form-group">
			<div class="radio">
				<label for="password">
					<input type="radio" value="2" name="forgot" id="password"/>
					I forgot my Password
				</label>
			</div>
		</div>
	
	</div>
	
</div>

<div class="row">
	<div class="col-md-6 col-md-offset-4">
		<div id="forgot_form_container" class="col-md-12 clearfix">&nbsp;<br/><br/><br/><br/><br/></div>
	</div>	
</div>	
	

<?php echo form_close(); ?>
