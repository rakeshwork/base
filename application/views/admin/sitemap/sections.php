<?php showMessage();?>
<h3>Sitemap Sections</h3>
<?php if($aSitemaps):?>

<table class="table borderless">
	

<tr>
	<th class="col-md-1">
		SI
	</th>
	
	<th class="col-md-8">
		title / Desc
	</th>
	
	<th class="col-md-3">
		Actions
	</th>
	
</tr>


<?php foreach($aSitemaps AS $iKey=>$oItem):?>

	<tr>
		<td>
			<?php echo $iKey + 1;?>
		</td>	
		<td>
			<?php echo $oItem->title;?>
		</td>	
		<td>
			<div class="action" title="Edit">
				<a href="javascript:void(0);" class="edit" onclick="javascript:takeAction('edit', 'sitemaps/edit_section/<?php echo $oItem->id;?>', '');">Edit</a>
			</div>
			<div class="action delete" title="Delete">
				<a href="javascript:void(0);" class="delete" onclick="javascript:takeAction('delete', 'sitemaps/delete_section/<?php echo $oItem->id;?>', 'Are you sure you want to delete this sitemap section?');">Delete</a>
			</div>
		</td>	
	</tr>
	
<?php endforeach;?>

</table>


<?php else:?>

	<div class="row">
		<div class="col-md-12">
			No Sections added yet
		</div>
	</div>

<?php endif;?>

