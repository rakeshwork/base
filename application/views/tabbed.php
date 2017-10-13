<?php if($aContents):?>
<div id="<?php echo $aSettings['tab_id']?>" class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs">
    
		<?php // Tab titles
		$i=0; // index starts at 0
		foreach($aContents AS $sTabTitle => $aContent):?>
		<?php 
			$sActive = '';
			if($aSettings['current_tab'] == $i) {
				
				$sActive = 'active';
			}
		?>
		
		<?php /*?>
			<li><a href="#<?php echo @$aContent['fragment_id'] ? $aContent['fragment_id'] : $i;?>"><?php echo $aContent['title'];?></a></li>	
		<?php */?>
			<li class="<?php echo $sActive;?>"><a data-toggle="tab" href="#tab<?php echo isset($aContent['fragment_id']) ? $aContent['fragment_id'] : $i;?>"><?php echo $aContent['title'];?></a></li>
			
			<?php 
			$i++;
		endforeach;?>

    </ul>

    <div class="tab-content">
	<?php // Tab Contents
	$i=0;
	foreach($aContents AS $sTabTitle => $aContent):?>

		<?php /*
			
			<div id="<?php echo @$aContent['fragment_id'] ? $aContent['fragment_id'] : $i;?>">
		
				<?php echo $aContent['content'];?>
				<div class="c"></div>
			</div>
		*/?>
		<?php
		$sActive = '';
		if($aSettings['current_tab'] == $i) {
			
			$sActive = 'in active';
		}
		
		//p($i);
		//p($aSettings['current_tab']);
		?>
            <div id="tab<?php echo isset($aContent['fragment_id']) ? $aContent['fragment_id'] : $i;?>" class="tab-pane fade <?php echo $sActive;?>">
                <p><?php echo $aContent['content'];?></p>
            </div>
		
		
		<?php 
		$i++;
	endforeach;?>
	</div>
	</div>
</div>
<?php endif;?>