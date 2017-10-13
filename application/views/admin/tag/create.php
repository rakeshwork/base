

<div class="col-md-12">
    <h3><?php echo $page_heading?></h3>
    <?php required_title();?>
    <?php showMessage();?>
</div>


<?php echo form_open_multipart('tag/create', array('id' => 'tagCreateForm'))?>

<div class="col-md-7">

	<div class="form-group">
		
		<label for="title">Tag Title<span class="req">*</span></label>
		<input type="text" name="title" class="form-control" value="<?php echo set_value('title') ? set_value('title') : '';?>"/>
		
	</div>
	
    
	<div class="form-group">	
		<input type="submit" name="create" value="Create" class="btn btn-default"/>
		<?php echo backButton('', 'Back');?>
	</div>
	
</div>