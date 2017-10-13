<?php showMessage();?>

<?php /*?>
<div class="row">
	<div class="col-md-3">
		Total of <?php echo $iTotal;?> Enumerator
	</div>
	<div class="col-md-9">
		<div class="row">
			<form class="form-inline" role="form">
			<div class="form-group">
				<label>Gender :</label>
				<?php echo form_dropdown('gender', $aGenders, $iGender,
										 'class="user_filter form-control" id="f_gender"');?>
			</div>
			<div class="form-group">
				<label>Account Status :</label>
				<?php echo form_dropdown('status', $aUserStatusesFlipped, $iStatus,
										 'class="user_filter form-control"  id="f_status"');?>
			</div>
			<div class="form-group">
				<label>User Role :</label>
				<?php echo form_dropdown('status', $aAllUserRoles, $iUserRole,
										 'class="user_filter form-control"  id="f_role"');?>
			</div>
			</form>
		</div>
	</div>
</div>
<?php */?>


<?php if($aData):?>

<hr>
<div class="row text-center">
	<?php echo $sPagination;?>
</div>

<table class="table table-condensed">

    <thead>
		<tr>
			<th>SI</th>
			<th>Name and Picture</th>
			<th>Type</th>
			<th>Details</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
    </thead>

    <tbody>
	<?php foreach($aData AS $iKey=>$oItem):?>
    <tr>

	    <td>
			<?php echo $iKey + $iOffset + 1;?>
		</td>
		<td>

			<div>
				<!--<a href="<?php echo $c_base_url;?>profile/view/<?php echo $oItem->account_no;?>" target="_blank">-->
					<?php echo getCurrentProfilePic($oItem->oProfilePicture, 'small'); ?>
				<!--</a>-->
			</div>
			<h4><?php echo $oItem->full_name;?></h4>


		</td>

	    <td>
			<?php echo $aUserTypesTitle[$oItem->type];?>
		</td>
		<td>
			<div>
				username : <?php echo $oItem->username;?>
				<?php /*echo $oItem->email_id ? ' | ' . $oItem->email_id : '';*/?>
			</div>
			<div>Acc No: <?php echo $oItem->account_no;?></div>
			<div>gender : <?php echo $aGenders[$oItem->gender];?></div>
		</td>
		<td>
			<?php echo $aUserStatusesTitles[$oItem->status];?>
		</td>
		<td>
			<div class="action" title="Edit">
				<a class="linkable" href="<?php echo $c_base_url;?>user/edit/<?php echo $oItem->account_no;?>">Edit User </a>
			</div>
			<?php

			/*

            <div class="action" title="Edit">
				<a class="linkable" href="<?php echo $c_base_url;?>profile/edit/<?php echo $oItem->account_no;?>">Edit Profile</a>
			</div>
			<div class="action" title="Edit">
				<a class="linkable" href="<?php echo $c_base_url;?>user/support_profile/<?php echo $oItem->account_no;?>">Edit Support Profile</a>
			</div>
            <?php if( is_null( $oItem->address_uid ) ):?>
			<div>
				<a href="<?php echo $c_base_url;?>address/create/user/<?php echo $oItem->account_no;?>">Create Address</a>
			</div>
            <?php endif;?>
            <?php if( $oItem->address_uid ):?>
			<div>
				<a href="<?php echo $c_base_url;?>address/edit/<?php echo $oItem->address_uid;?>/user/<?php echo $oItem->account_no;?>">Edit Address</a>
			</div>
            <?php endif;?>
			<hr class="small">

			<?php if( $oItem->mobile_verification_status != 1 ):?>
			<div>
				<a href="<?php echo $c_base_url;?>mobile_number/mobile_number_verification_history?account_no=<?php echo $oItem->account_no;?>">Mobile number verification history</a>
			</div>
			<?php endif;?>

			<hr class="small">

			<div>
				<a href="<?php echo $c_base_url;?>history/election_id/<?php echo $oItem->account_no;?>">Election Id history</a>
			</div>
			<div>
				<a href="<?php echo $c_base_url;?>history/mobile_number/<?php echo $oItem->account_no;?>">Mobile number history</a>
			</div>

			<hr class="small">

			<div class="action" title="Logout User">
				<a href="javascript:void(0);" class="close_account linkable" id="<?php echo $oItem->id;?>">Close Account</a>
			</div>

			<div class="action" title="Close Account">
				<a href="javascript:void(0);" class="logout_user linkable" id="<?php echo $oItem->id;?>">Logout User</a>
			</div>

			<div class="action delete" title="Delete">
				<a href="javascript:void(0);" class="perm_delete linkable" id="<?php echo $oItem->id;?>">Permanent Delete</a>
			</div>
			*/
			?>
		</td>

    </tr>
	<?php endforeach;?>
    </tbody>
</table>
<div class="row text-center">
	<?php echo $sPagination;?>
</div>

<?php else:?>
<div class="row">
	<div class="col-md-12 text-center m-t-20">
		There are no users
	</div>
</div>
<?php endif;?>
