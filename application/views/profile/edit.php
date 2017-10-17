<h3><?php echo $page_heading;?></h3>
<?php showMessage();?>

<?php echo form_open('profile/edit/'.$iAccountNo, 'id = "profileEdit"')?>

<div class="row">
	<div class="col-md-3">

		<div id="profile_pic_cont">
			<?php echo getCurrentProfilePic($oProfilePicture, 'small');?>
		</div>
		<div>
			<a href="<?php echo c('base_url'), 'profile_picture/change_profile_pic/', $iAccountNo;?>">Change Picture</a>
		</div>
	</div>

<div class="col-md-9">

	<div class="form-group">
		<div class="row">
			<div class="col-md-4">
				<label for="first_name">First Name</label>
				<input type="text" class="form-control" name="first_name" value="<?php echo set_value('first_name')? set_value('first_name') : $oUser->first_name;?>"/>
			</div>
			<div class="col-md-4">
				<label for="middle_name">Middle Name</label>
				<input type="text" class="form-control" name="middle_name" value="<?php echo set_value('middle_name')? set_value('middle_name') : $oUser->middle_name;?>"/>
			</div>
			<div class="col-md-4">
				<label for="last_name">Last Name</label>
				<input type="text" class="form-control" name="last_name" value="<?php echo set_value('last_name')? set_value('last_name') : $oUser->last_name;?>"/>
			</div>
		</div>


	</div>

<?php /*?>
	<div class="form-group">
		<label for="title">Title</label>
		<input type="text" class="form-control" name="title" value="<?php echo set_value('title')? set_value('title') : $oUser->title;?>"/>
	</div>

<?php */?>



<?php /*?>
	<div class="form-group">
		<div class="row">
			<div class="col-md-4">
				<label for="facebook_url">Facebook Url</label>
				<input type="text" class="form-control" name="facebook_url" value="<?php echo set_value('facebook_url')? set_value('facebook_url') : $oUser->facebook_url;?>"/>
			</div>
			<div class="col-md-4">
				<label for="twitter_url">Twitter Url</label>
				<input type="text" class="form-control" name="twitter_url" value="<?php echo set_value('twitter_url')? set_value('twitter_url') : $oUser->twitter_url;?>"/>
			</div>
			<div class="col-md-4">
				<label for="blog_url">Blog Url</label>
				<input type="text" class="form-control" name="blog_url" value="<?php echo set_value('blog_url')? set_value('blog_url') : $oUser->blog_url;?>"/>
			</div>
		</div>
	</div>
<?php */?>


	<div class="row">

		<div class="col-md-6">
			<div class="radio">
				<?php foreach($aGenders AS $iValue => $sName):?>
				<label for="gender<?php echo $iValue;?>">
					<input type="radio" name="gender" id="gender<?php echo $iValue;?>"
						value="<?php echo $iValue;?>"
						<?php echo set_radio(	'gender', $iValue,
												($oUser->gender == $iValue ? true : false)
											); ?>
					/> <?php echo $aGenderTitles[$iValue]?>
				</label>
				<?php endforeach;?>
			</div>
		</div>

		<div class="col-md-6">
			<label for="dob">Date of birth</label>
			<?php $sDefault = $oUser->birthday? date('Y-m-d', strtotime($oUser->birthday)) : '';?>
			<input type="text" name="dob" class="form-control" value="<?php echo set_value('dob')? set_value('dob') : $sDefault;?>" id="datepicker"/>
			<div class="help-block">YYYY-MM-DD format</div>
		</div>
	</div>


	<div class="form-group">
		<label for="about_me">About me Excerpt</label>
		<textarea name="about_me_excerpt" class="form-control" rows="5"><?php echo set_value('about_me_excerpt')? set_value('about_me_excerpt') : $oUser->about_me_excerpt;?></textarea>
	</div>
	<div class="form-group">
		<label for="about_me">About me</label>
		<textarea name="about_me" class="form-control text-editor" rows="7"><?php echo set_value('about_me')? set_value('about_me') : $oUser->about_me;?></textarea>
	</div>

	<?php /*?>
	<div class="form-group">
		<label for="about_me">About me</label>
		<textarea name="about_me" class="form-control text-editor" rows="15"><?php echo set_value('about_me')? set_value('about_me') : $oUser->about_me;?></textarea>
	</div>
    <?php */?>

	<div class="form-group">&nbsp;</div>
	<div class="form-group">
			<input type="submit" name="update" value="Update" class="btn btn-default btn btn-default-primary"/>
			<?php echo backButton('', 'Back');?>
	</div>


</div>
</div>
<?php echo form_close(); ?>



<div  class="dn">
	<div class="dn">
		<div id="profile_pic_new_image"></div>
	</div>
</div>
