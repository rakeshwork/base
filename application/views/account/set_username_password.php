<h3><?php echo $page_heading;?></h3>
<?php showMessage();?>
<?php required_title();?>
<?php echo form_open_multipart('account/set_username_password', array('id' => 'usernamePasswordForm'))?>

<div class="fc signup_form">

	<div class="fro" align="center">
		<label for="username">Username<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<div class="l">
				<input type="text" name="username" value="<?php echo set_value('username') ? set_value('username') : '';?>"/>
			</div>
			
		</div>
	</div>
	
	<div class="fro" align="center">
		<label for="password">Password<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="password" id="password" name="password" value=""/>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label for="password_again">Repeat Password<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="password" id="password_again" name="password_again" value=""/>
		</div>
	</div>
	
	
	<div class="fro" align="center">
		<div class="fl ">&nbsp;</div>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" name="Create" value="Create"/>
			<?php echo backButton('account/overview', 'Back');?>
		</div>
	</div>
	
</div>
<?php echo form_close(); ?>
