$(document).ready(function() {
	<?php foreach($aSettings AS $aTabs):?>
		$("#<?php echo $aTabs['tab_id']?>").tabs(<?php echo $sTabOptions;?>);
	<?php endforeach;?>
});