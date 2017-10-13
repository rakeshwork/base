<h3>Add Link</h3>
<?php echo form_open_multipart('sitemaps/add_link/')?>
<?php showMessage();?>
<div class="fc sitemap_form">

	<div class="fro" align="center">
		<label>Title<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="title" value="<?php echo set_value('title') ? set_value('title') : '';?>"/>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>URL<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="url" value="<?php echo set_value('url') ? set_value('url') : '';?>"/>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>Section<span class="req">*</span></label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<?php echo form_dropdown('section', $aSectionDropDown, set_value('section') ? set_value('section') : 0, 'onchange=getLinkParents(this.value)');?>
			
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>Parent</label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<?php echo form_dropdown('parent', array(0=>'Select Section'), set_value('parent') ? set_value('parent') : 0, 'id="parent" disabled');?>
		</div>
	</div>
		
	<div class="fro" align="center">
		<label>
			Change Frequency<span class="req">*</span>
			<div class="helptxt-l">For Search Engine Optimization</div>
		</label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<?php echo form_dropdown('change_frequency', $aFrequency, set_value('section') ? set_value('section') : 4);?>
		</div>
	</div>
			
	<div class="fro" align="center">
		<label>
			Priority<span class="req">*</span>
			<div class="helptxt-l">For Search Engine Optimization</div>
		</label>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="priority" value="<?php echo set_value('priority') ? set_value('priority') : '0.5';?>"/>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>&nbsp;</label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" name="save" value="Create Link"/>
			<?php echo backButton('', 'Back')?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>