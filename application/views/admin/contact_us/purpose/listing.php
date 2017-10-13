<?php showMessage();?>


<?php if($aData):?>
<table class="table table-condensed">

    <thead>
		<tr>
			<th>SI</th>
			<th>Title</th>
			<th>Details</th>
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
			<?php echo $oItem->title;?>
		</td>
		<td>
			<div><span class="h6 b">Reciever Name : </span><?php echo $oItem->reciever_name;?></div>
			<div><span class="h6 b">Target Email : </span><?php echo $oItem->target_email;?></div>
			<div><span class="h6 b">Description : </span><?php echo $oItem->description;?></div>
			<div><span class="h6 b">Success Message : </span><?php echo $oItem->success_message;?></div>
		</td>
		<td>
			<div>
				<a href="<?php echo c('base_url');?>contact_purpose/edit_purpose/<?php echo $oItem->id;?>">Edit</a>
			</div>
			<div>
                <a href="#" class="perm_delete" data-id="<?php echo $oItem->id;?>">Delete</a>

			</div>
		</td>

    </tr>
	<?php endforeach;?>
    </tbody>
</table>
<div class="row">
	<?php echo $sPagination;?>
</div>

<?php else:?>
<div class="col-md-10 pull-center">There are no puposes</div>
<?php endif;?>
