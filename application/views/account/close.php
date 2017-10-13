<h3><?php echo @$page_heading;?></h3>
<?php showMessage();?>
<div class="fc acc_close_form">
<?php echo form_open_multipart('account/close', array('id' => 'changePasswordForm'));?>
<?php required_title();?>
		
	<?php
	//NOT A GOOD WAY OF IMPLEMENTING. THINK OF ANOTHER WAY
	if($oCurrUser->online_via != $online_via['facebook']):?>
	<div class="fro" align="center">
		<label for="password">Password<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="password" value="" name="current_password"/>
		</div>
	</div>
	<?php endif;?>
	
	<div class="fro" align="center">
		<label for="captcha">Captcha<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<?php echo $sCaptcha;?>
		</div>
	</div>
	
		
	<div class="fro" align="center">
		<label>&nbsp;</label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" value="Close Account"/>
		</div>
	</div>
	
</div>
<?php echo form_close(); ?>