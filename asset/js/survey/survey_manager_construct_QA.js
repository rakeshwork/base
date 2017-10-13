


function appendQuestion(data) {

  var answer_html = '';


  // clear any control buttons activated during the last question
  $('#add_button_cnt').html('');


  // construct the question body

	if(data.question_type == 1) { // 1=> single question

		answer_html = constructAnswerFormParts(data);

	} else if(data.question_type == 2) {



    if(data.question_form_body) {
      var row_number = 1; // if we are appending a question, then naturally, the row number will be one.
      answer_html += template_append_row_num_to_name_field(data.question_form_body, row_number);
    } else {
      var wrapping = 'table';

      switch(wrapping) {
      	case 'table':
      			answer_html = '<table>';

      				answer_html += '<tr>';
      				$.each(data.questions, function (index, question_data){

      					answer_html += '<th class="text-center">' + question_data.title + '</th>';

      				});
      				answer_html += '</tr>';


      				answer_html += '<tr>';
      				$.each(data.questions, function (index, question_data){

      					answer_html += '<td class="text-center">';
      					answer_html += constructAnswerFormParts(question_data, false);
      					answer_html += '</td>';
      				});
      				answer_html += '</tr>';

      			answer_html += '</table>';
      			break;


      }
    }

	}


  if(data.is_multipliable == true) {

    // add the addition button
    $('#add_button_cnt').html('<a href="#" class="btn btn-primary" id="add_question_set"> + </a>');

    // store the form body locally
    localStorage.setItem('question_form_body', data.question_form_body);

    // set the row number
    localStorage.setItem('row_num', 1);

    // make the add button clickable.
    $('#add_question_set').on('click', function(event){

      event.preventDefault();
      event.stopPropagation();

      addNewRowToFamilyTemplate();



    });

  }



	// --- Handle the display of controls (buttons etc)

  //$('#previous_btn').prop('disabled', false);
  if(data.question_no == data.question_count){
  	$('#next_btn').text('Save & Complete Survey');
  }else if(data.question_no == 1){
	  //$('#previous_btn').prop('disabled', true);
  }else{
	  $('#next_btn').text('Next Question');
  }



	// update question number to the display
	$('#question-status').text(data.question_no+' of '+data.question_count);

	// update the group information of the question
  $('#question-group-id').text(question_groups[data.group_id].title);

	// place question in its container

    $('#question_container').html(
      $(
        '<h3>'+
        data.title +
        '</h3>'+
        '<div class="answer_block">'+
        	answer_html +
        '</div>'
      )
    );
}

function constructAnswerFormParts(data, bProvideWrapper){



	var answer_html = '';
  bProvideWrapper = defaultFor(bProvideWrapper, true);

	var q_uid_class_name = 'q_uid_' + data.uid;


	switch(data.answer_type) {

		case '1':

			answer_html += bProvideWrapper ? '<div class="form-group">' : '';
			answer_html += '<span class="'+ q_uid_class_name +'">'
      answer_html += '<input type="text" name="single_value_text" '
      answer_html += ' class="form-control" '
        +' '+ data.ui_validation +' '
        +'/>';
      answer_html += '</span>';

			answer_html += bProvideWrapper ? '</div>' : '';
			break;

		case '2':

			answer_html += bProvideWrapper ? '<div class="radio">' : '';

			answer_html +=
			'<span class="'+ q_uid_class_name +'">';

			$.each(data.answer_options, function (index, answer_option){
				// name="'+ data.field_name +'"
				answer_html += bProvideWrapper ? '<label class="radio-inline">' : '';
				answer_html += '<input type="radio" name="single_value_radio" '
          +' value="'+ answer_option.value +'" '
          +' '+ data.ui_validation +' '
          +'/> ';
        answer_html +=  answer_option.title;

				answer_html += bProvideWrapper ? '</label>' : '';
			});

			answer_html += '</span>';

			answer_html += bProvideWrapper ? '</div>' : '';


			break;

		case '3':

			answer_html += bProvideWrapper ? '<div class="radio">' : '';

			answer_html += '<span class="'+ q_uid_class_name +'">';

			$.each(data.answer_options, function (index, answer_option){
				answer_html +=
				'<label class="checkbox-inline">' +
					'<input type="checkbox" name="multi_value_checkbox" value="'+ answer_option.value +'"/> ' +
					((answer_option.title == undefined) ? '' : answer_option.title)  +
				'</label>';
			});

			answer_html += '</span>';

			answer_html += bProvideWrapper ? '</div>' : '';
			break;

		case '4':

			answer_html += bProvideWrapper ? '<div class="form-group">' : '';
			answer_html +=
			'<span class="'+ q_uid_class_name +'">'+
			'<textarea name="single_value_textarea" class="form-control" rows="4"></textarea>' +
			'</span>';
			answer_html += bProvideWrapper ? '</div>' : '';
			break;

		case '5':

			answer_html += bProvideWrapper ? '<div class="form-group">' : '';
			answer_html +=
			'<span class="'+ q_uid_class_name +'">'+
			'<select name="single_value_select" class="form-control">';


			   //attach the non-selection option first

        if(data.answer_non_selection_option) {
           answer_html +=
           '<option value="'+ data.answer_non_selection_option.value +'">' +
            ((data.answer_non_selection_option.title == undefined) ? '' : data.answer_non_selection_option.title) +
           '</option>';
        }

				$.each(data.answer_options, function (index, answer_option){

					answer_html +=
					'<option value="'+ answer_option.value +'">' +
					 ((answer_option.title == undefined) ? '' : answer_option.title) +
					'</option>';
				});
			answer_html += '</select>' + '</span>';
			answer_html += bProvideWrapper ? '</div>' : '';
			break;

	}

	return answer_html;
}


// repopulate a question with the selected data.
function repopulateQuestion(data) {

	if(data.template) {
		repopulateTemplateQuestion(data);
	} else {
		repopulateIndividualQuestion(data);
	}
}

function repopulateTemplateQuestion(data) {

  switch(data.question_no) {

    case '1':
      if(data.populated_answer) {
        var num_rows = data.populated_answer.length;
        $.each(data.populated_answer, function (index, row) {

          row_number = index + 1;
          $.each(row, function (field_name, value) {

            $('#current_form [name="'+ field_name + row_number +'"]').val(value);
          });

          //create the next row
          if(index < (num_rows -1)) {
            addNewRowToFamilyTemplate();
          }

        });
      }

      break;

    case '2':

      $.each(data.populated_answer, function (index, value) {
        $('#current_form input[name="'+ index +'"]').val(value);
      });
      break;
  }

}

function repopulateIndividualQuestion(data) {

//console.log('repopulate ind : ' + data.form_field);
	switch(data.form_field) {


		case '1':
			$('#current_form input[name="single_value_text"]').val(data.populated_answer);
			break;

		case '2':
			$('#current_form input[name="single_value_radio"][value="'+ data.populated_answer +'"]').removeAttr('null');
			$('#current_form input[name="single_value_radio"][value="'+ data.populated_answer +'"]').attr('checked', 'checked');
			break;

		case '3':
			if(data.populated_answer && data.populated_answer.length > 0) {

					$.each(data.populated_answer, function (index, value) {
						$('#current_form input[name="multi_value_checkbox"][value="'+ value +'"]').attr('checked', 'checked');
					});
			}
			break;

		case '4':
			$('#current_form textarea[name="single_value_textarea"]').html(data.populated_answer);
			break;

		case '5':
    console.log(data.populated_answer);
			$('#current_form select[name="single_value_select"]').val(data.populated_answer);
			break;
	}
}



function addNewRowToFamilyTemplate() {

  var row_num = parseInt(localStorage.getItem('row_num'));

  var question_form_body = $(localStorage.getItem('question_form_body'));

  if( row_num % 2 != 0 ) {
    question_form_body.removeClass('odd-row');
  }

  // place the updated counter
  row_num++;
  $(question_form_body).find('.counter').html(row_num);


  // access the form HTML
  var question_form_body_html = question_form_body.prop('outerHTML');

  //append the row number to all names of the form.
  question_form_body_html = template_append_row_num_to_name_field(question_form_body_html, row_num)


  $('#question_container .answer_block').append(question_form_body_html);

  localStorage.setItem('row_num', row_num );
}
