<h3 class="bodyHeading"><?php echo @$page_heading?></h3>
<?php showMessage();?>
<div class="row">
	<div class="col-md-12">

		<ol>
			<li>
				<a class="" href="<?php echo c('base_url').'profile/edit/', $oUser->account_no;?>" title="Edit Profile">
					Edit Profile
				</a>
			</li>
			<li>
				<a class="" href="<?php echo c('base_url').'account/change_password'?>" title="Change Password">
					Change Password
				</a>
			</li>
		</ol>

	</div>
</div>
