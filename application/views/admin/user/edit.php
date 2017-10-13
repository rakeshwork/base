
<?php showMessage();?>

<?php echo form_open('user/edit/' . $iEditedAccountNo, 'id = "userEdit"')?>


<div class="col-md-8">

	<div class="form-group">
		<h4><?php echo $oUser->full_name;?></h4>
	</div>

	<div class="form-group">
		<label for="email_id">Email Id</label>
		<input type="text" class="form-control" name="email_id" value="<?php echo set_value('email_id')? set_value('email_id') : $oUser->email_id;?>"/>
	</div>

	<div class="form-group">
		<label for="status">Status</label>
		<?php echo form_dropdown('status', $aUserStatusesTitles, $oUser->status, 'class="form-control"');?>
	</div>

	<?php if($aAllRoles): ?>

	<div class="form-group">
		<label for="user_roles">User Roles</label>
		<div class="checkbox">

			<?php foreach($aAllRoles AS $sRoleName => $aItem) :?>
			<?php $bSet = in_array($aItem['id'], $aExistingRoles) ? true : false;?>
					<label class="checkbox">
						<input type="checkbox" name="user_roles[]" value="<?php echo $aItem['id'];?>"
						<?php echo set_checkbox('user_roles[]', $aItem['id'], $bSet); ?> />
						<?php echo $aItem['title']?>
					</label>
			<?php endforeach;?>
		</div>
	</div>
	<?php endif; ?>

	<div class="form-group">
			<input type="submit" name="update" value="Update" class="btn btn-default btn btn-default-primary"/>
			<?php echo backButton('', 'Back');?>
	</div>

</div>
<?php echo form_close(); ?>
