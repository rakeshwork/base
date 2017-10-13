
<?php showMessage();?>

<div class="row">
<div class="col-md-8">
<?php echo form_open_multipart('developer/create_rag_pickers', array('id' => 'campaignCreateForm'))?>
<div></div>
	
            rag category numbr : <input type="text" name="rag_category" value="" />
	
			<textarea name="description" class="col-md-12 tinymce"><?php echo set_value('description') ? set_value('description') : '';?></textarea>
	
	
			<input type="submit" name="create" value="Create Campaign" class="btn btn-default btn btn-default-primary"/>
	

<?php echo form_close(); ?>
</div>
</div>

