<?php if($sInfoMessage):?>
	<div class="info"><?php echo $sInfoMessage;?></div>
<?php endif;?>

<?php echo $this->lang->line('common_account_activation_success');?>
<script type="text/javascript">
	autoRedirect('<?php echo base_url();?>');
</script>