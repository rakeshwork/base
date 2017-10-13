
<?php showMessage();?>
<h3>Edit Section</h3>

<?php echo form_open_multipart('sitemaps/edit_section/'.$oItem->id)?>
<div class="row">
<div class="col-md-8">

	<div class="form-group">
		
		<label for="title">Title<span class="req">*</span></label>
		<input type="text" name="title" class="form-control"
			   value="<?php echo set_value('title') ? set_value('title') : $oItem->title;?>"/>
	</div>
	<div class="form-group">
		
		<input type="submit" name="save" value="Save Changes" class="btn btn-default"/>
		<?php echo backButton('', 'Back')?>
	</div>
</div>
</div>
<?php echo form_close(); ?>


