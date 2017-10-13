
var base_url = '<?php echo $base_url;?>';
var dont_confirm_leave = 1; //set dont_confirm_leave to 1 when you want the user to be able to leave withou confirmation

var question_groups = <?php echo $question_groups;?>;

/**
 * This function will append row number to questions name field
 * @return {[type]} [description]
 */
function template_append_row_num_to_name_field(question_form_body, row_num) {

  return question_form_body.replace(/{row_number}/g, row_num);
}

$(document).ready(function() {


  if (storageAvailable('localStorage')) {

		var temporary_survey_number = null;
		var temporary_survey_current_question = 0;
		var temporary_survey_last_processed_question = 0;
		var temporary_survey_is_last_question = false;
    var current_temporary_survey_status = null;
    var total_number_of_questions = $('#current_temporary_survey_total_num_questions').val();

    localStorage.setItem('total_number_of_questions', total_number_of_questions)

		if($('#current_temporary_survey_number').val()) {

			temporary_survey_number = $('#current_temporary_survey_number').val();
			temporary_survey_current_question = $('#current_temporary_survey_next_question').val();
      //temporary_survey_current_question_uname = $('#current_temporary_survey_current_question_uname').val();
			temporary_survey_last_procesed_question = $('#current_temporary_survey_last_procesed_question').val();
			temporary_survey_is_last_question = $('#current_temporary_survey_is_last_question').val() == 1 ? true : false;

      current_temporary_survey_status = $('#current_temporary_survey_status').val();

			// not to be used in production
			displayTemporarySurveyNumber(temporary_survey_number);

		}




    //var temporary_survey_number = localStorage.getItem('temporary_survey_number');

    if( temporary_survey_number == null) {

      // get the survey information
      $.ajax({
        url: base_url + "survey/current_survey",
        type:"GET",
        success:function (data) {

          // store survey information locally.
          localStorage.setItem('temporary_survey_number', data.temporary_survey_number);
          localStorage.setItem('current_question', data.current_question);
          localStorage.setItem('last_question', 'false');


					// not to be used in production
					displayTemporarySurveyNumber(data.temporary_survey_number);


					if(data.temporary_survey_number != 0) {
						//contact the server for first question
	        	fetchNextQuestion();
					} else {
						alert("Error: No Survey found");
					}

        },
        dataType : "json"
      });

    } else {

      // store survey information locally.
			localStorage.setItem('temporary_survey_number', temporary_survey_number);
			localStorage.setItem('current_question', temporary_survey_last_procesed_question); // TO Do : fetchNextQuestion() should use "last_processed_question" instead of current_question. for more clarity in code
			localStorage.setItem('last_question', temporary_survey_is_last_question);

      localStorage.setItem('current_temporary_survey_status', current_temporary_survey_status);

      switch(current_temporary_survey_status) {

        case '1':
          //contact the server for next question
          fetchNextQuestion(getNextQuestionNumber('forward'), true);
          break;

        case '2':
          surveyCompleteRoutines();
          break;
      }

		}

  }
  else {
  	alert("Please use a modern browser to access this page");
  }



});


function displayTemporarySurveyNumber(temporary_survey_number) {

	$('#survey_no_display_counter').html(temporary_survey_number);
}

function showOverlay(){
	$('#overlay').show();
}
function hideOverlay(){
	$('#overlay').hide();
}

function defaultFor(arg, val) {
	return typeof arg !== 'undefined' ? arg : val;
}

function defaultForInteger(arg, val) {
	return typeof arg !== NaN ? arg : val;
}


function fetchNextQuestion(question_id, specific) {

  //clear the containers
  $('#question_container').html('');
  $('#error_container').html('');


  var temporary_survey_number = localStorage.getItem('temporary_survey_number');
  var current_question = localStorage.getItem('current_question');

	var next_question_id = (parseInt(current_question) + 1);

	// see if a question id is specified. else, we default to the next question WRT current_question.
	next_question_id = defaultFor(question_id, next_question_id);


  //contact the server for next question
  $.ajax({
    url:base_url + "question/get/" + temporary_survey_number + "/" + next_question_id + ((specific == true) ? '?specific=true':''),
    type:"GET",
    success:function (data) {

      localStorage.setItem('current_question', next_question_id);


      localStorage.setItem('last_question', data.last_question);
      localStorage.setItem('answer_type', data.answer_type);
			localStorage.setItem('question_type', data.question_type);

      localStorage.setItem('question_form_body', ( typeof data.question_form_body !== "undefined" ) ? data.question_form_body : '');



			// append the question to the viewing area.
			appendQuestion(data);

      // repopulate the question if required
      if (specific) {
        repopulateQuestion(data);
      }


      //remove overlay
		  $('#survey_container').removeClass('animated fadeInLeft');
		  $('#survey_container').addClass('animated fadeInLeft');
			$('#survey_container').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){$('#survey_container').removeClass('animated fadeInLeft');});
		  hideOverlay();
    },
    dataType : "json"
  });
}



/**
 *
 * Show message once the survey has been completed.
 *
 * @param  {[type]} survey_id [description]
 * @return {[type]}           [description]
 */
function showSurveyCompleteView(data) {

  // remove all buttons etc
  $('#survey_container').html('');
  hideOverlay();

  $('#question-group-id,#question-status').text('');
  // show link to access the survey result page. => view page of an individual survey!
  $('#survey_container').html(
    $(
      '<div style="text-align:center;">' +
        '<h4>'+ data.success_msg +'</h4>' +
        '<a href="'+base_url+'survey_result/view/'+data.survey_id+'" style="align:center" class="btn btn-primary">View the data collected' +
        '</a>' +
      '</div>'
      )
  );
}


function surveyCompleteRoutines() {

  //just let the server know that the survey was completed.

  $.ajax({
    url: base_url + "survey/complete",
    type:"GET",
    success:function (data) {

      if(data.error == '') {

        showSurveyCompleteView(data);
	      dont_confirm_leave = 1;

      } else {

        alert("There was some problem completing the survey.");
      }

    },
    dataType : "json"
  });

}



function storageAvailable(type) {
	try {
		var storage = window[type],
			x = '__storage_test__';
		storage.setItem(x, x);
		storage.removeItem(x);
		return true;
	}
	catch(e) {
		return false;
	}
}

/**
 * get the next question number based on "current_question"
 *
 * if current question is not withhing range, then current question is returned.
 * @return {[type]} [description]
 */
function getNextQuestionNumber(direction) {

  var next_question_no = current_question = parseInt(localStorage.getItem('current_question'));
  var total_number_of_questions = parseInt(localStorage.getItem('total_number_of_questions'));

  switch(direction) {
    case 'forward':
      next_question_no =  current_question + 1;
      break;
    case 'backward':
      next_question_no =  current_question - 1;
      break;
  }

  if( ! (next_question_no >= 1) || ! (next_question_no <= total_number_of_questions) ) {
    next_question_no = current_question;
  }

  return next_question_no;
}
