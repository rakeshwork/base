<?php showMessage();?>
<h3>Add Section</h3>



<?php echo form_open_multipart('sitemaps/add_section/')?>


<div class="row">
<div class="col-md-8">

	<div class="form-group">
		
		<label for="title">Title<span class="req">*</span></label>
		<input type="text" name="title" class="form-control"
			   value="<?php echo set_value('title') ? set_value('title') : '';?>"/>
	</div>
	<div class="form-group">
		<input type="submit" name="create" value="Create" class="btn btn-default"/>
		<?php echo backButton('', 'Back')?>
	</div>
</div>
</div>



<?php echo form_close(); ?>