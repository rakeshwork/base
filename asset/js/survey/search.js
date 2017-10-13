
var base_url = '<?php echo $base_url;?>';

$(document).ready(function(){

  $( "#searchForm" ).on( "submit", function( event ) {
    event.preventDefault();

    var search_url = base_url + 'search/do_search?' + $( this ).serialize()

    $.ajax({
      url:search_url,
      type:"GET",
      success:function (data) {

        $('#search_message').html('');
        $('#search_message').append(data.message);

        $('#result_container').html('');


        if(data.testing == true) {
          $('#query_cnt').html(data.query);
        }

        if(data.result) {

          $('#result_container').append('<table class="table"></table>');
          $('#result_container table').append(
            $(
              '<tr>' +
              '<th>SI</th>' +
              '<th>Name</th>' +
              '<th>Ward Id</th>'+
              '</tr>'
            )
          );
          $.each(data.result, function (index, item) {


/*
            $('#result_container').append(
              $(
                '<div class="row">' +
                  '<div class="col-md-3">' +
                    '<b>Name :</b>' + item.user_name +
                  '</div>' +
                  '<div class="col-md-3">' +
                    '<b>Ward  :</b>' + item.ward_id +
                  '</div>' +
                  '<div class="col-md-6">' +
                  '</div>' +
                '</div>'
              )
            );
            */


            $('#result_container table').append(
              $(
                '<tr>' +
                  '<td>' +
                  (index + 1) +
                  '</td>' +
                  '<td>' +
                    '<a href="'+ base_url +'survey_result/view/'+ item.survey_id +'" target="_blank">' +
                      item.user_name +
                    '</a>' +
                  '</td>' +
                  '<td>' +
                    item.ward_id +
                  '</td>' +

                '</tr>'
              )
            );

          });
        }

        $('html, body').animate({scrollTop: $('#result_container').offset().top}, 700);
        //$('').animate({scrollTop: '0px'}, 300);


      },
      dataType : "json"
    });

  });




});
