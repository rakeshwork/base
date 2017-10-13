
<h3>Edit Link</h3>
<?php echo form_open_multipart('sitemaps/edit_link/'.$oItem->id)?>
<?php showMessage();?>
<div class="row">
<div class="col-md-8">


	<div class="form-group">
		<label>Title<span class="req">*</span></label>
		
		
		<input type="text" class="form-control" name="title" value="<?php echo set_value('title') ? set_value('title') : $oItem->title;?>"/>
		
	</div>
	
	<div class="form-group">
		<label>Url<span class="req">*</span></label>
		
		<input type="text" class="form-control" name="url" value="<?php echo set_value('url') ? set_value('url') : $oItem->url;?>"/>
		
	</div>
	
	<div class="form-group">
		<label>Section<span class="req">*</span></label>
		
		<?php echo form_dropdown('section', $aSectionDropDown, set_value('section') ? set_value('section') : $oItem->section_id, 'onchange=getLinkParents(this.value) class="form-control"');?>
			
		
	</div>
		
	<div class="form-group">
		<label>Parent<span class="req">*</span></label>
		
		<?php echo form_dropdown('parent', $aLinkParentDropDown, set_value('parent') ? set_value('parent') : $oItem->parent, 'id="parent" class="form-control"');?>
		
	</div>
		
	<div class="form-group">
		<label>Change Frequency<span class="req">*</span>
			<div class="helptxt-l">For Search Engine Optimization</div>
		</label>
		
			<?php echo form_dropdown('change_frequency', $aFrequency, set_value('section') ? set_value('section') : $oItem->change_frequency, ' class="form-control"');?>
		
	</div>
			
	<div class="form-group">
		<label>
			Priority<span class="req">*</span>
			<div class="helptxt-l">For Search Engine Optimization</div>
		</label>
		
		<input type="text" class="form-control" name="priority" value="<?php echo set_value('priority') ? set_value('priority') : $oItem->priority;?>"/>
		
	</div>
		
	<div class="form-group">
		<label>&nbsp;</label>
		
		<input type="submit" name="save" value="Save Changes" class="btn btn-default"/>
		<?php echo backButton('', 'Back')?>
		
	</div>
</div>
</div>
<?php echo form_close(); ?>