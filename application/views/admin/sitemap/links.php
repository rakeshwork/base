<?php showMessage();?>

<h3>Sitemap Links</h3>

<div class="row">
	<div class="col-md-4">
		<label>Sort by section : </label>
		<?php echo form_dropdown('section', $aSectionDropDown, $iSection,
									'onChange="gotoPage(\'sitemaps/links/\' + this.value)" class="form-control"');?>
	</div>
</div>

<?php if($aLinks):?>
<table class="table borderless">
	
	<tr>
		<th>Si</th>
		<th>Link</th>
		<th>Section</th>
		<th>Parent Link</th>
		<th>Actions</th>
	</tr>


	<?php foreach($aLinks AS $iKey=>$oItem):?>
	<tr>
	
		<td><?php echo $iKey + $iOffset + 1;?></td>
		<td>
			<div><?php echo $oItem->title;?></div>
			<div><?php echo $oItem->url;?></div>
		</td>
		<td>
			<div><?php echo $oItem->section_title;?></div>
		</td>
		<td>
			<div><?php echo $oItem->parent_title ? $oItem->parent_title : 'None';?></div>
		</td>
		<td>
			<div class="action" title="Edit">
				<a href="javascript:void(0);" class="edit" onclick="javascript:takeAction('edit', 'sitemaps/edit_link/<?php echo $oItem->id;?>', '');">Edit</a>
			</div>
			<div class="action delete" title="Delete">
				<a href="javascript:void(0);" class="delete" onclick="javascript:takeAction('delete', 'sitemaps/delete_link/<?php echo $oItem->id;?>', 'Are you sure you want to delete this sitemap section?');">Delete</a>
			</div>
		</td>
	</tr>
	<?php endforeach;?>
	<?php echo $sPagination;?>
	
</table>
<?php else:?>
	No links added yet
<?php endif;?>