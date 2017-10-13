<div class="row">
    <div class="col-md-12">
        <p>
            <i class="glyphicon glyphicon-map-marker"></i> 
            <?php echo   $oSingleAddressItem->address_details->address_line1;?>
            <?php if($oSingleAddressItem->address_details->address_line2):?>
            
            <?php echo ', ', $oSingleAddressItem->address_details->address_line2;?>
            <?php endif;?>
            
            <?php /*?>
            <?php if($aPlaces[$oSingleAddressItem->address_details->place]):?>
            <?php echo ', ', $aPlaces[$oSingleAddressItem->address_details->place];?>
            <?php endif;?>
            <?php */?>
            
            <?php /*?>
            <?php if($aDistricts[$oSingleAddressItem->address_details->district]):?>
            <?php echo ', ', $aDistricts[$oSingleAddressItem->address_details->district];?>
            <?php endif;?>
            <?php */?>
            
            <?php if($oSingleAddressItem->address_details->pincode):?>
            <?php echo ' - ', $oSingleAddressItem->address_details->pincode;?>
            <?php endif;?>
        </p>
        
        <?php $aPhoneDetails = (array)$oSingleAddressItem->phone_details;?>
        <?php if( ! empty($aPhoneDetails) ):?>
        
        <p>
            
            <i class="fa fa-mobile-phone"></i> 
            <?php echo $oSingleAddressItem->phone_details->mobile1 ; ?>
            <?php if($oSingleAddressItem->phone_details->mobile2):?>
            <?php echo ', ', $oSingleAddressItem->phone_details->mobile2;?>
            <?php endif;?>
            
            <?php if($oSingleAddressItem->phone_details->landline1 || $oSingleAddressItem->phone_details->landline2):?>
             | <i class="fa fa-phone-square"></i> 
            <?php endif;?>
            
            <?php echo $oSingleAddressItem->phone_details->landline1;?>
            <?php if($oSingleAddressItem->phone_details->landline2):?>
            <?php echo ', ', $oSingleAddressItem->phone_details->landline2;?>
            <?php endif;?>
        </p>
        <?php endif;?>
    </div>
</div>