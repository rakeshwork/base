<div class="row">
    <div class="col-md-12">

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
