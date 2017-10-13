<?php showMessage();?>
<h3><?php echo @$page_heading;?></h3>

<input type="hidden" name="url_list" id="url_list" value="<?php echo base_url().$this->uri->uri_string;?>" />



<?php if($sitepage_details):?>
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
	<?php foreach($sitepage_details AS $iKey=>$oItem):?>
    <tr>

	    <td>
			<?php echo $iKey + $iOffset + 1;?>
		</td>
		<td>
			<?php echo $oItem->name;?>
		</td>
		<td><?php echo $oItem->title;?>
		</td>
		<td>
			<div><a href="<?php echo $c_base_url, 'page/edit/', $oItem->id;?>">Edit</a></div>
		</td>
		
    </tr>
	<?php endforeach;?>
    </tbody>
</table>
<div class="row">
	<?php echo $sPagination;?>
</div>

<?php else:?>
<div class="col-md-10 pull-center">There are no Site pages</div>
<?php endif;?>