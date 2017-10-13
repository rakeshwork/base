<div class="row">
	<div class="col-md-8">
<h1><?php echo $page_heading;?></h1>
<?php showMessage();?>
<?php //	required_title();?>


<?php echo form_open_multipart('address/edit/' . $iAddressUid . '/' . $sEntityName . '/' . $sEntityValue, array('id' => 'contactInfoForm', 'class' => 'form'))?>

	
	
	<div class="row">
		<div class="col-md-12">
			<?php /*?>
			
			Details of the entity whoes address is being managed
			<?php */?>
		</div>
	</div>
	
	
	<div class="form-group">
		<label for="address_line1">Address line 1</label>
		<input type="text" id="address_line1" name="address_line1"
			   class="form-control"
			   value="<?php echo set_value('address_line1') ? set_value('address_line1') : $oAddress->address_details->address_line1;?>"/>
	</div>
	
	<div class="form-group">
		<label for="address_line2">Address line 2</label>
		<input type="text" id="address_line2" name="address_line2"
			   value="<?php echo set_value('address_line2') ? set_value('address_line2') : $oAddress->address_details->address_line2;?>"
			   class="form-control"/>
	</div>
	
	<div class="form-group">
		<label for="city">City</label>
		<?php $iDefault = set_value('city') ? set_value('city') : $oAddress->address_details->city;?>
		<?php echo form_dropdown('city', $aCities, $iDefault, 'id="city" class="form-control"');?>
	</div>
	
	<div class="form-group">
		<label for="pincode">Pincode</label>
		<input type="text" id="pincode" name="pincode"
			   value="<?php echo set_value('pincode') ? set_value('pincode') : $oAddress->address_details->pincode;?>" class="form-control"/>
	</div>
	
	<div class="form-group">
		<label>Give atleast one contact number</label>
	</div>
	
	<div class="form-group">
		<label for="mobile1">Mobile 1</label>
		<input type="text" id="mobile1" name="mobile1"
				   value="<?php echo set_value('mobile1') ? set_value('mobile1') : $oAddress->phone_details->mobile1;?>" class="form-control"/>
			
	</div>
	
	<div class="form-group">
		<label for="mobile2">Mobile 2</label>
		<input type="text" id="mobile2" name="mobile2"
					   value="<?php echo set_value('mobile2') ? set_value('mobile2') : $oAddress->phone_details->mobile2;?>" class="form-control"/>
	</div>

	<div class="form-group">
		<label for="landline1">Land line 1</label>
		<input type="text" id="landline1" name="landline1"
			   value="<?php echo set_value('landline1') ? set_value('landline1') : $oAddress->phone_details->landline1;?>" class="form-control"/>
	</div>

	<div class="form-group">
		<label for="landline2">Land line 2</label>
		<input type="text" id="landline2" name="landline2"
			   value="<?php echo set_value('landline2') ? set_value('landline2') : $oAddress->phone_details->landline2;?>" class="form-control"/>
	</div>

	
	<div class="form-group">
		<label>&nbsp;</label>
		<input type="submit" name="save" value="Save" class="btn btn-default"/>
		<?php echo backButton('', 'Back');?>
	</div>
	

<?php echo form_close(); ?>
</div>

</div>