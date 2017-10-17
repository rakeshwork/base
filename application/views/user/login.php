
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<?php showMessage();?>
		<h3>Login</h3>
		<?php echo form_open_multipart('user/login', array('id' => 'loginForm'))?>
		<div class="form-group">
			<label>Username</label>
			<input type="text" name="username" id="username" size="30" class="form-control"
				   value="<?php echo set_value('username') ? set_value('username') : '';?>"/>
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="password" id="password" name="password" size="30" class="form-control"/>
		</div>

		<div class="form-group">
			<div class="col-md-6">
				<a href="<?php echo c('base_url').'account/password_recovery';?>" >Forgot Password</a>
			</div>
			<div class="col-md-6">
				<input type="submit" name="login" class="btn btn-default pull-right" id="submit_btn btn-default" value="Login"/>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php /* ?>
<script type="text/javascript">


</script>
<?php */ ?>
