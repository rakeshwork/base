

function is_data_valid() {

  //return true;

  var bIsValid = false;

  // custom method - we need at least one person as head of family
  jQuery.validator.addMethod("is-head-of-family", function(value, element) {

    var headOfHousePresent = false;

    $('#current_form').find('select[data-rule-is-head-of-family="true"]').each(function (index, element) {

      if( parseInt( $(element).val() ) != 0 ) {

        headOfHousePresent = true;

        return false; // we are breaking out of the .each();
      }

    });

    // If validation is ok, then clear error messages of all other select elements
    if(headOfHousePresent) {

      $('#current_form').find('select[data-rule-is-head-of-family="true"]').each(function (index, element) {
        $(element).next().remove();
      });
    }

    return headOfHousePresent;

  }, "Specify atleast one family member as head of family.");




  // Initialize validation
  $('#current_form').validate();

  // do the validation
  if( $('#current_form').valid() ) {
    bIsValid = true;
  }

  console.log(bIsValid);

  return bIsValid;
}
