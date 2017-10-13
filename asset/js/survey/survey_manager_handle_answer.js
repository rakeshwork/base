

function handleCurrentAnswer(direction) {


  var bWasSuccessful = false;

  if($('#question_container').html() != '') {

    var answer_type = localStorage.getItem('answer_type');
		var question_type = localStorage.getItem('question_type');
    var current_question = localStorage.getItem('current_question');



    var oDataObject = new Object();

		if( question_type == 2 ) { //question type = group

			switch(current_question) {

				case "1" :
        $('#question_container .answer_block .repeating-row').each(function(index, elem){

          oDataObject_New = {};

          // all form inputs which are of type "text"
          $(elem).find('input[type="text"]').each(function(index, elem){

            oDataObject_New[$(elem).attr('name')] = $(elem).val();
          });

          // all form inputs which are select
          $(elem).find('select').each(function(index, elem){

            oDataObject_New[$(elem).attr('name')] = $(elem).val();
          });


          oDataObject[index] = oDataObject_New;
        });


          break;

				case "2" :

        // all form inputs which are of type "text"
        $('#question_container .answer_block').find('input[type="text"]').each(function(index, elem){

          oDataObject[$(elem).attr('name')] = $(elem).val();
        });

					break;
			}

		} else { //question type = individual

			switch(answer_type) {

        case "1":
          var input = $('#question_container .answer_block input[name="single_value_text"]').val();

          oDataObject.single_value_text = input;
          break;

        case "2":
          var input = $('#question_container .answer_block input[name="single_value_radio"]:checked').val();

          oDataObject.single_value_radio = input;
          break;

        case "3":
          oDataObject.multi_value_checkbox = $('#question_container .answer_block input[name="multi_value_checkbox"]:checked').map(function () {
            return $(this).val();
          }).get();
        case "4":
          var input = $('#question_container .answer_block textarea[name="single_value_textarea"]').val();

          oDataObject.single_value_textarea = input;
          break;

				case "5":
					var input = $('#question_container .answer_block select[name="single_value_select"]').val();

					oDataObject.single_value_select = input;
					break;
      }
		}



    // see if this data is valid
    if( is_data_valid() ) {

      // show over lay
      showOverlay();

      //Submit data back to server
      $.ajax({
        url: base_url + "survey/accept_answer/" + current_question,
        data: oDataObject,
        method:"POST",
        success:function (data) {

          if(data.error == '') {

            var last_question = localStorage.getItem('last_question');

            if(last_question == "true") {

              surveyCompleteRoutines();

            } else {

              dont_confirm_leave = 0; // this is no longer needed as the server will remember the last question answered for a given survey.

              var next_question_number = getNextQuestionNumber('forward');
              var specific = true; // this is required so that previous populated value is also fetched . if available.
                                  // required in the context of next and previous button. and the cases it gives.
              fetchNextQuestion(next_question_number, specific);
            }

          } else {
            // display the error
            displayServerError(data.error);
            hideOverlay();
          }

        },
        error:function (data) {

          alert('Action could not be completed. Please try again.');
          hideOverlay();
        },
        dataType : "json"
      });
    }

  }

}
