
<?php showMessage();?>

<div class="row">
<div class="col-md-8">
<?php echo form_open_multipart('user/create', array('id' => 'userCreateForm'))?>
<div></div>


	<div class="form-group">
		<label for="salutation">Salutation</label>
		<?php $iDefault = set_value('salutation') ? set_value('salutation') : NULL ?>
		<?php echo form_dropdown('salutation', $aUserSalutationTitles, $iDefault, 'class="form-control"');?>
	</div>

	<div class="form-group">
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name') ? set_value('first_name') : '';?>"/>
	</div>

	<div class="form-group">
		<label for="middle_name">Middle Name</label>
		<input type="text" name="middle_name" class="form-control" value="<?php echo set_value('middle_name') ? set_value('middle_name') : '';?>"/>
	</div>

	<div class="form-group">
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" class="form-control" value="<?php echo set_value('last_name') ? set_value('last_name') : '';?>"/>
	</div>


	<div class="form-group">
		<label>Gender</label>
		<?php $iDefault = set_value('gender') ? set_value('gender') : NULL ?>
		<?php echo form_dropdown('gender', $aGenderTitles, $iDefault, 'class="form-control"');?>

	</div>

	<div class="form-group">
		<label for="email_id">Email Id</label>
		<input type="text" id="email_id" class="form-control" name="email_id" value="<?php echo set_value('email_id') ? set_value('email_id') : '';?>"/>
		<div class="l dn" id="email_id_result"></div>
	</div>


	<div class="form-group">
		<label for="user_type">User Type</label>
		<?php $iDefault = set_value('user_type') ? set_value('user_type') : 0;?>
		<?php echo form_dropdown('user_type', $aUserTypesTitle, $iDefault, ' class="form-control"');?>
	</div>


	<?php if($aAllRoles):?>

	<div class="checkbox">

		<label for="">User Roles</label>
		<?php foreach($aAllRoles AS $sRoleName => $aItem) :?>
				<label>
					<input type="checkbox" name="user_roles[]" value="<?php echo $aItem['id'];?>"
							<?php echo set_checkbox('user_roles', $aItem['id']); ?>
					/>
					<?php echo $aItem['title']?>
				</label>
		<?php endforeach;?>

	</div>
	<?php endif;?>



	<div class="form-group">
			<input type="submit" name="create" value="Create User" class="btn btn-default"/>
			<?php echo backButton('', 'Back');?>
	</div>


<?php echo form_close(); ?>
</div>
</div>
