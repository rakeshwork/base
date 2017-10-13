
<?php showMessage();?>


<?php echo form_open_multipart('contact_purpose/add_purpose', array('id' => 'contactpurposeCreateForm'))?>









<div class="row">
<div class="col-md-8">

	<div class="row">
		<label for="title">Title</label>
		<input type="text" name="title" class="form-control" value="<?php echo set_value('title') ? set_value('title') : '';?>"/>
	</div>

	<div class="row">
		<label for="description">Description</label>
		<textarea class="form-control" name="description"><?php echo set_value('description') ? set_value('description') : '';?></textarea>
	</div>

	<div class="row">
		<label for="email">Email</label>
		<input type="text" class="form-control" name="email" value="<?php echo set_value('email') ? set_value('email') : '';?>"/>

	</div>

	<div class="row">
		<label for="reciever_name">Receiever Name</label>
		<input type="text" class="form-control" name="reciever_name" value="<?php echo set_value('reciever_name') ? set_value('reciever_name') : '';?>"/>

	</div>

<!--
	<div class="row">
		<label for="email_template_id">Email Template</label>
			<?php $iDefault = set_value('email_template_id') ? set_value('email_template_id') : 0;?>
			<?php echo form_dropdown('email_template_id', $aEmailTemplates, $iDefault, ' class="form-control"');?>
	</div> -->

	<div class="row">
		<label for="success_message">Success Message</label>
		<textarea  class="form-control" name="success_message"><?php echo set_value('success_message') ? set_value('success_message') : '';?></textarea>
	</div>


	<div class="row">&nbsp;</div>
	<div class="row">
		<input type="submit" name="update" value="Create" class="btn btn-default btn btn-default-primary"/>
		<?php echo backButton('', 'Back');?>
	</div>

</div>
</div>
<?php echo form_close(); ?>
