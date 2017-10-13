<?php showMessage();?>
<h3><?php echo @$page_heading;?></h3>




<table class="table borderless">
	<tr>
		<td class="col-xs-3">
            
            
			
		</td>
        
		<td class="col-xs-3">
            
            Tag Status : <?php echo form_dropdown('status', $aTagStatusTitle_dropDown, $iStatus, 'class="form-control"  id="f_status"');?>
			
		</td>
        
		<td class="col-xs-3">
            &nbsp;
            <a href="#" id="search" class="btn btn-primary">Search</a>
			
		</td>
    </tr>
</table>

<div class="row">
	<div class="col-md-12">
		
		
	</div>
</div>


<div class="row">
	<div class="col-md-3">
		Total of <?php echo $iTotal;?> Tags
	</div>
	<div class="col-md-9 pull-right">
		
	</div>
</div>



<?php if($aTags):?>
<table class="table table-condensed">

    <thead>
		<tr>
			<th>SI</th>
			<th>Tag</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
    </thead>
	
    <tbody>
	<?php foreach($aTags AS $iKey => $oItem):?>
    <tr>

	    <td>
			<?php echo $iKey + $iOffset + 1;?>
		</td>
		<td>
			<div>
					<a href="<?php echo $c_base_url, 'tag/view/', $oItem->id;?>"><?php echo $oItem->title;?></a>
			</div>
			
		</td>
		
		<td>
			<?php echo $aTagStatusTitle[$oItem->status];?>
		</td>
		<td>
			<div>
				<a href="<?php echo c('base_url');?>tag/edit/<?php echo $oItem->id;?>">Edit</a>
			</div>
			<div>
				<a href="#" data-tag-id="<?php echo $oItem->id?>" class="delete_tag">Delete</a>
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
<div class="col-md-10 pull-center">There are no tags</div>
<?php endif;?>

