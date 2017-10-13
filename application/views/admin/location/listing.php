<?php showMessage();?>
<h3><?php echo @$page_heading;?></h3>


<div class="grid_container l users">
	<div class="cell l">
		Total of <?php echo $iTotal, ' ', $hierarchy_title[$iType];?>
	</div>
	<div class="cell r">

		<?php //p($aData);?>

		<?php //if($iCountryId):?>
			Country : <?php echo form_dropdown('country', $aCountrys, $iCountryId, 'class="country_filter"  id="country"');?></span>
		<?php //endif;?>
		<?php if($iStateId):?>
			State : <?php echo form_dropdown('state', $aStates, $iStateId, 'class="state_filter"  id="state"');?></span>
		<?php endif;?>
		<?php if($iDistrictId):?>
			District : <?php echo form_dropdown('district', $aDistricts, $iDistrictId, 'class="district_filter"  id="district"');?></span>
		<?php endif;?>

	</div>
</div>

<?php if($aData):?>

<div class="grid_container l events">


<div class="grid_header_cont l">
	<?php
	$width1 = '25px';
	$width2 = '565px';
	$width3 = '120px';
	$width4 = '105px';
	?>
	<div class="cell l" style="width:<?php echo $width1;?>">Si</div>
	<div class="cell l" style="width:<?php echo $width2;?>">Details</div>
	<div class="cell l" style="width:<?php echo $width3;?>">Status</div>
	<div class="cell l" style="width:<?php echo $width4;?>">Actions</div>
</div>


<div class="grid_content_cont l">
	<?php
	$iHierarchyCount = count($hierarchy);
	foreach($aData AS $iKey=>$oItem):

	$item_id = $sType.'_id';
	$item_name = $sType.'_name';


	$sUrl = '';

	if($sType == 'country') {
		$sUrl .= 'location/listing/' . $oItem->$item_id;
	} elseif ($sType == 'state') {
		$sUrl .= 'location/listing/' . $iCountryId . '/' . $oItem->$item_id;
	} elseif ($sType == 'district') {
		$sUrl .= 'location/listing/' . $iCountryId . '/' . $iStateId . '/' . $oItem->$item_id;
	}
	$sUrl = $sUrl ? $c_base_url . $sUrl : '';
	?>
	<div class="grid_row l <?php echo 'row'.$iKey;?>">
		<div class="cell l" style="width:<?php echo $width1;?>"><?php echo $iKey + $iOffset + 1;?></div>
		<div class="cell l event_details" style="width:<?php echo $width2;?>">
			<div><h5>
				<?php if($sUrl):?>
				<a href="<?php echo $sUrl;?>"><?php echo $oItem->$item_name;?></a>
				<?php else:?>
				<?php echo $oItem->$item_name;?>
				<?php endif;?>

			</h5></div>
		</div>
		<div class="cell l" style="width:<?php echo $width3;?>">
			<div>&nbsp;</div>
		</div>
		<div class="cell r" style="width:<?php echo $width4;?>">
		<?php if($iType < $iHierarchyCount):?>
			<div class="action" title="Add <?php echo $hierarchy_title[$iType+1];?>">
				<a href="<?php echo $c_base_url, 'location/edit/', $sType, '/', $oItem->$item_id;?>">Edit</a>
			</div>
			<div class="action" title="Edit">
				<a href="<?php echo $c_base_url, 'location/add/', $hierarchy_flipped[$iType+1], '/', $oItem->$item_id;?>">
				Add <?php echo $hierarchy_title[$iType+1];?></a>
			</div>
		<?php endif;?>
		</div>
	</div>
	<?php //p('test ');exit;?>

	<?php endforeach;?>
	<?php //p('test 2');exit;?>
	<?php echo $sPagination;?>

</div>

<?php else:?>
<div class="fw tac m-t-10 l">There are no <?php echo $hierarchy_title[$iType];?> ..
<a href="<?php echo $c_base_url, 'location/add/', $hierarchy_flipped[$iType], '/', $this->uri->segment(($iType + 1));?>">Add one</a></div>
<?php

//p($iType + 1);
endif;?>
