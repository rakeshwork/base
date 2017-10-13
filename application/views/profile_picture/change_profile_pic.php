
<?php showMessage();?>

<div class="row">

	<div class="col-md-3" style="border-right:1px dotted #CCC;">
		<div id="preview_cont_upload">
			<?php echo getCurrentProfilePic($oProfilePicture, 'small');?>
		</div>
	</div>

	<div class="col-md-9">

		<?php echo form_open_multipart('profile_picture/change_profile_pic/'.$iAccountNo, 'id = "profilePicUpload"')?>

			<div class="form-group">
				<label for="">Upload Picture</label>
				<input type="file" name="profile_pic">
			</div>

			<div class="form-group">
				<label class="col-md-3">&nbsp;</label>
				<div  class="col-md-9">
					<input type="submit" name="Upload" class="btn btn-primary"/>
					<?php echo backButton('profile/edit/'.$oProfilePicture->account_no, 'Cancel');?>
				</div>
			</div>
		</form>

	</div>
</div>
