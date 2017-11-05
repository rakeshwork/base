
<?php showMessage();?>

<?php echo form_open_multipart('department/edit/'.$oDepartment->id.'/', array('id' =>'createDepartment'))?>

<div class="form-group">
<div class="col-md-8">

	<div class="form-group">
		<label for="title">Department unique name : <?php echo $oDepartment->name;?></label>
	</div>

	<div class="form-group">
		<label for="title">Title</label>
		<input type="text" class="form-control" name="title" value="<?php echo htmlentities( set_value('title')?set_value('title'):$oDepartment->title );?>" >
	</div>


	<div class="form-group">
		<label for="status">Status</label>
		<?php $iValue = set_value('status') ? set_value('status') : $oDepartment->status;?>
		<?php echo form_dropdown('status', $aDepartmentStatusTitles, $iValue, 'class="form-control"');?>
	</div>

	<div class="form-group">
		<label for="website_url">Website URL</label>
		<input type="text" class="form-control" name="website_url" value='<?php echo htmlentities( set_value('website_url')?set_value('website_url'):$oDepartment->website_url );?>' >
	</div>


	<input type="submit" name="create" value="Update" class="btn btn-primary"/>
	<?php echo backButton('', 'Back');?>


</div>
</div>
<?php echo form_close(); ?>
