<table class="admin_sub_heading" width="100%">
	<tr>
		<th>
			<?php showMessage('790px');?>
		</th>
	</tr>
</table>
<table width="100%" class="common_listing_table" id="list_table">
	<tr>
		<td width="10%">&nbsp;</td>
		<td align="left" width="20%"> <b>Name</b>
		</td>
		<td align="left" width="70%"><?php print($site_page->name);?></td>
	</tr>
	<tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><b>Title</b></td>
		<td align="left"><?php print($site_page->title);?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top" align="left"> <b>Content</b>
		</td>
		<td valign="top" align="left">  <?php print($site_page->content);?>
		</td>
	</tr>  
	<tr>
		<td colspan="2" align="center"> <input type="button" name="btn btn-default_back" onclick="javascript:gotoPage('sitepage');" value="Back"></td>
	</tr>
</table>