<div class="row">&nbsp;</div>
<div class="row">

	<div class="col-xs-3" style="border-right:1px dashed #CCC;">
		<?php echo getCurrentProfilePic($oUser->oProfilePicture, 'small');?>
	</div>

	<div class="col-md-9">
		<div class="row">

			<div class="col-md-12">

				<h2><?php echo $oUser->full_name;?></h2>

				<div><?php echo $oUser->about_me;?></div>

			</div>

		</div>
	</div>

</div>
