
<?php showMessage();?>

<?php echo form_open_multipart('contact_purpose/edit_purpose/'.$oPurpose->id, array('id' => 'contactpurposeCreateForm'))?>


<div class="row">
<div class="col-md-8">

	<div class="form-group">
		<label for="title">Title</label>
		<input type="text" name="title"
			   value="<?php echo set_value('title') ? set_value('title') : $oPurpose->title;?>"
			   class="form-control"
			 />
	</div>

	<div class="form-group">
		<label for="description">Description</label>

		<textarea name="description" class="form-control"><?php echo set_value('description') ? set_value('description') : $oPurpose->description;?></textarea>
	</div>

	<div class="form-group">
		<label for="target_email">Target Email</label>
		<input type="text" name="target_email" class="form-control" value="<?php echo set_value('target_email') ? set_value('target_email') : $oPurpose->target_email;?>"/>
	</div>

	<div class="form-group">
		<label for="reciever_name">Receiever Name</label>
		<input type="text" name="reciever_name" class="form-control" value="<?php echo set_value('reciever_name') ? set_value('reciever_name') : $oPurpose->reciever_name;?>"/>


	</div>


	<div class="form-group">
		<label for="status">Status</label>
				<?php $iDefault = set_value('status') ? set_value('status') : $oPurpose->status;?>
				<?php echo form_dropdown('status', $aPurposeStatus, $iDefault, ' class="form-control"');?>
	</div>

	<div class="form-group">
		<label for="success_message">Success Message</label>
		<textarea name="success_message" class="form-control"><?php echo set_value('success_message') ? set_value('success_message') : $oPurpose->success_message;?></textarea>
	</div>

	<div class="form-group">
		<input type="submit" name="update" value="Update" class="btn btn-primary"/>
		<?php echo backButton('', 'Back');?>
	</div>

</div>
</div>
<?php echo form_close(); ?>
