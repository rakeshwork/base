
<div class="row">
    
    <div class="col-md-6">
        
        <h4>Address</h4>
        
        <div class="form-group">
            <label for="address_line1_">Address line 1</label>
            <input type="text" id="address_line1_" name="address_line1_"
                   class="form-control"
                   value="<?php echo set_value('address_line1_') ? set_value('address_line1_') : ''?>"/>
        </div>
        
        <div class="form-group">
            <label for="address_line2_">Address line 2</label>
            <input type="text" id="address_line2_" name="address_line2_"
                   value="<?php echo set_value('address_line2_') ? set_value('address_line2_') : ''?>"
                   class="form-control"/>
        </div>
        
        
        <?php
        //var_dump($address_form_settings__bIsDistrictEditable);
        
        if( $address_form_settings__bShowDistrict ):?>
            
            <?php if( $address_form_settings__bIsDistrictSelectable ):?>
                
                <div class="form-group">
                    <label for="address_district_">District</label>
                    <?php //echo $aDistricts[$address_form_settings__sDefaultDistrict]?>
                    <?php echo form_dropdown('address_district_', $aDistricts, $address_form_settings__sDefaultDistrict, 'id="address_district_" class="form-control"')?>
                </div>
            <?php else:?>
                <input type="hidden" name="address_district_" value="<?php echo $address_form_settings__sDefaultDistrict;?>"/>
            <?php endif;?>
            
        <?php endif;?>
        
        <?php /* State */ ?>
        <?php if( $address_form_settings__bShowState ):?>
            <div class="form-group">
                <label for="address_state_">State</label>
                <?php echo $aStates[$address_form_settings__sDefaultState]?>
            </div>
        <?php endif;?>
        <?php if( $address_form_settings__bIsStateSelectable ):?>
                <?php // form drop down here?>
            <?php else:?>
                <input type="hidden" name="address_state_" value="<?php echo $address_form_settings__sDefaultState;?>"/>
        <?php endif;?>
            
            
        <?php /* Country */ ?>
        <?php if( $address_form_settings__bShowCountry ):?>
            <div class="form-group">
                <label for="address_country_">Country</label>
                <?php echo $aCountries[$address_form_settings__sDefaultCountry]?>
            </div>    
        <?php endif;?>
        <?php if( $address_form_settings__bIsCountrySelectable ):?>
            <?php // form drop down here?>
        <?php else:?>
            <input type="hidden" name="address_country_" value="<?php echo $address_form_settings__sDefaultCountry;?>"/>
        <?php endif;?>
        
        
        
        <?php /*?>
        <div class="form-group">
            <label for="address_city_">City</label>
            <?php $iDefault = set_value('address_city_') ? set_value('address_city_') : 11;?>
            <?php echo form_dropdown('address_city_', $aCities, $iDefault, 'id="address_city_" class="form-control"');?>
        </div>
        <?php */?>
        
        <div class="form-group">
            <label for="address_pincode_">Pincode</label>
            <input type="text" id="address_pincode_" name="address_pincode_"
                   value="<?php echo set_value('address_pincode_') ? set_value('address_pincode_') : ''?>" class="form-control"/>
        </div>
        
        
        
    </div>
    
    <div class="col-md-6">
        
        <h4>Contact Numbers</h4>
        
        <div class="form-group">
            <label>Give atleast one contact number</label>
        </div>
        
        <div class="form-group">
            <label for="address_mobile1_">Mobile 1</label>
            <input type="text" id="address_mobile1_" name="address_mobile1_"
                       value="<?php echo set_value('address_mobile1_') ? set_value('address_mobile1_') : ''?>" class="form-control"/>
                
        </div>
        
        <div class="form-group">
            <label for="address_mobile2_">Mobile 2</label>
            <input type="text" id="address_mobile2_" name="address_mobile2_"
                           value="<?php echo set_value('address_mobile2_') ? set_value('address_mobile2_') : ''?>" class="form-control"/>
        </div>
        
        <div class="form-group">
            <label for="address_landline1_">Land line 1</label>
            <input type="text" id="address_landline1_" name="address_landline1_"
                   value="<?php echo set_value('address_landline1_') ? set_value('address_landline1_') : ''?>" class="form-control"/>
        </div>
        
        <div class="form-group">
            <label for="address_landline2_">Land line 2</label>
            <input type="text" id="address_landline2_" name="address_landline2_"
                   value="<?php echo set_value('address_landline2_') ? set_value('address_landline2_') : ''?>" class="form-control"/>
        </div>
        
    </div>
    
</div>
