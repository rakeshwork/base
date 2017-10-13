$( function() {
  $( "#sortable" ).sortable({
    placeholder: "ui-state-highlight"
  });
  $( "#sortable" ).disableSelection();
} );


$(document).ready(function(){

  $('#update_order').click(function (event){

    var sorted_Question_Uids = {};

    $('#sortable li').each(function(index, element){


      sorted_Question_Uids[index] = $(element).attr('data-uid');
    });


    //console.log(JSON.stringify(sorted_Question_Uids));

    var base_url = '<?php echo $base_url;?>';
    var update_url = base_url + 'question/update_order';

    $.ajax({
      url : update_url,
      type:"POST",
      data: {"sorted_questions" : sorted_Question_Uids},
      success:function (data) {

        if(data.error){
          alert(data.error);
        } else {
          alert('new order has been updated');
        }

      },
      dataType : "json"
    });


    event.preventDefault();
    event.stopPropagation();

  });

});
