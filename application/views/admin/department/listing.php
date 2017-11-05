<?php showMessage();?>



<?php if($aDepartments):?>
<table class="table table-condensed">

    <thead>
		<tr>
			<th>SI</th>
			<th>Name</th>
			<th>Title</th>
			<th>Actions</th>
		</tr>
    </thead>

    <tbody>
	<?php foreach($aDepartments AS $iKey=>$oItem):?>
    <tr>

	    <td>
			<?php echo $iKey + 1;?>
		</td>
		<td>
			<?php echo $oItem->name;?>
		</td>
		<td><?php echo $oItem->title;?>
		</td>
		<td>
			<div><a href="<?php echo $c_base_url, 'department/edit/', $oItem->id;?>">Edit</a></div>
		</td>

    </tr>
	<?php endforeach;?>
    </tbody>
</table>
<div class="row">
	<?php //echo $sPagination;?>
</div>

<?php else:?>
<div class="col-md-10 pull-center">There is no data</div>
<?php endif;?>
