$(document).ready(function(){
$( "#datepicker" ).datepicker(
	{ 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange:'<?php echo $minYear;?>:<?php echo $maxYear;?>',
		minDate: '<?php echo $minDate;?>',
		maxDate: '<?php echo $maxDate;?>'
	}
);
});