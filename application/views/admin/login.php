<div class="login_cnt l">
<table class="login_cnt_table" align="center">
<tr><td>
<?php echo form_open('admin/login')?>
<div class="fc admin_login">
	<div class="form_row" align="center">
		<?php echo showMessage();?>
	</div>
	<div class="form_row" align="center">
		<div class="fl ">Username</div>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="text" name="username" value="<?php echo set_value('username')?set_value('username'):'';?>" id="username"/>
		</div>
	</div>
	<div class="form_row" align="center">
		<div class="fl ">Password</div>
		<div>&nbsp;:&nbsp;</div>
		<div class="fr ">
			<input type="password" name="password"/>
		</div>
	</div>
	<div class="form_row" align="center">
		<div class="fl ">&nbsp;</div>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div class="fr ">
			<input type="submit" name="login" value="Login"/>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
</td></tr>
</table>

<script type="text/javascript">
$(document).ready(function (){
	$('#username').focus();
	
});
</script>
</div>