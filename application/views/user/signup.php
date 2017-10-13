<h1>Signup</h1>
<?php showMessage();?>
<?php required_title();?>
<?php echo form_open_multipart('user/signup', array('id' => 'signupForm'))?>

<div class="fc signup_form">

	<div class="fro" align="center">
		<label for="username">Username<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<div class="l">
				<input type="text" name="username" value="<?php echo set_value('username') ? set_value('username') : '';?>"/>
			</div>
			
			<?php /*
			<div class="l">
				<input type="button" title="Check Availability" name="check_availability" value="Check Availability" onclick="javascript:checkAvailability('username');"/>
			</div>
			<div class="l dn" id="username_result"></div>
			*/?>
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
		<label for="email_id">Email Id<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">

			
			<div class="l">
				<input type="text" id="email_id" name="email_id" value="<?php echo set_value('email_id') ? set_value('email_id') : '';?>"/>
			</div>
			<div class="l dn" id="email_id_result"></div>
			
		</div>
	</div>
	
	<div class="fro" align="center">
		<label for="first_name">First Name<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="first_name" value="<?php echo set_value('first_name') ? set_value('first_name') : '';?>"/>
		</div>
	</div>

	<div class="fro" align="center">
		<label for="middle_name">Middle Name</label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="middle_name" value="<?php echo set_value('middle_name') ? set_value('middle_name') : '';?>"/>
		</div>
	</div>

	<div class="fro" align="center">
		<label for="last_name">Last Name<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="last_name" value="<?php echo set_value('last_name') ? set_value('last_name') : '';?>"/>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label for="gender">Gender<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<?php $iDefault = set_value('gender') ? set_value('gender') : 0?>
			<?php echo form_dropdown('gender', $aGenders, 0);?>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label for="dob">Date of birth<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<div><input type="text" name="dob" readonly="true" value="<?php echo set_value('dob')? set_value('dob') : ''?>" id="datepicker"/></div>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>&nbsp;</label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" name="signup" value="Signup"/>
			<?php echo backButton('home', 'Cancel');?>
		</div>
	</div>
	
</div>
<?php echo form_close(); ?>
