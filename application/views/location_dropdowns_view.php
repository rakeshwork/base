<?php for( $i=1; $i < $iTypeNo; ++$i):?>

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
				p($iDefault);
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

<?php endfor;?>