
<div class="row">
<?php
$i = 1;
foreach($aSiteMapData AS $aItem): ?>
	
	<?php if($aItem['aLinks']):?>	
		<div class="col-md-3 thumbnail">
			<h4><?php echo $aItem['section_title'];?></h4>
			<ul class="list-unstyled">
			<?php foreach($aItem['aLinks'] AS $aLinks):?>
			
				<li>
					<i class="glyphicon glyphicon-angle-right"></i><a href="<?php echo $aLinks['url']?>" title="<?php echo $aLinks['link_title']?>">
						<?php echo $aLinks['link_title']?>
					</a>
				</li>
				
				<?php if(@$aLinks['aChildren']):?>
					<ul class="list-unstyled">
					<?php foreach($aLinks['aChildren'] AS $aChildren):?>
						<li class=" m-l-15">
							<i class="glyphicon glyphicon-double-angle-right"></i><a href="<?php echo $aChildren['url']?>" title="<?php echo $aChildren['link_title']?>">
								<?php echo $aChildren['link_title']?>
							</a>
						</li>
					<?php endforeach;?>
					</ul>
				<?php endif;?>
				
			<?php endforeach;?>
			</ul>
		</div>
	<?php endif;?>
	
<?php

++ $i;
if($i == 4){?>
	</div>
	<div class="row">
<?php }
endforeach;?>