<h3><?php echo @$page_heading; ?></h3>

<?php showMessage();?>

<?php echo form_open_multipart('location/add/'.$sType.'/'.$iParentId)?>
<div class="fc create_location_form">

<?php echo $sLocationsDropDowns;?>


<?php
/**
 *
 * - to delete later on if there are no errors
 *
 *
for( $i=1; $i < $iTypeNo; ++$i):?>

	<div class="fro" align="center">
		<label for=""><?php echo $hierarchy_title[$i]?></label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<?php
				$sAttributes = 'id="' . $hierarchy_flipped[$i] . '"';
				
				$aData = @$aParents['aParent' . $i] ? $aParents['aParent' . $i] : array('------------------------');
				
				if($i != ($iTypeNo-1)) {
					$sAttributes .= ' onchange="javascript:getSubLocations(this.value, \'' . $hierarchy_flipped[$i+1] . '\', \'' . $hierarchy_flipped[$i+1] . '\')"';						
				}
				
				$iDefault = set_value($hierarchy_flipped[$i]) ? set_value($hierarchy_flipped[$i]) : 0;
				if(!$iDefault) {
					
					$iDefault = $aParentHistory[$hierarchy_flipped[$i]]? $aParentHistory[$hierarchy_flipped[$i]] : $aPreviousData[$hierarchy_flipped[$i]];
				}
				
				if(!$iDefault && $i != 1){
					$sAttributes .= ' disabled="disabled"';
				}
			?>
			
			<?php echo form_dropdown($hierarchy_flipped[$i],
									 $aData,
									 $iDefault,
									 $sAttributes
									 );?>
		</div>
	</div>

<?php endfor;
*/
?>
	
	<div class="fro" align="center">
		<label for="new_item"><?php echo $hierarchy_title[$iTypeNo]?> Name</label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<div>
				<input type="text" name="new_item" value="<?php echo set_value('new_item') ? set_value('new_item') : '';?>"/>
			</div>
			<div class=helptxt-r>for multiple entries, enter as comma separated values</div>
		</div>
	</div>
	
	<div class="fro" align="center">
		<label>&nbsp;</label>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" name="add" value="Add"/>
			<?php echo backButton('', 'Back');?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>