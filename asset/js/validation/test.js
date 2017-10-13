
$(document).ready(function(){
  var sContainerHTML = $("#container").html();
  //console.log( sContainerHTML );

  $(sContainerHTML).find('div').addClass("test_class");
  console.log(sContainerHTML);

  var count = 2;
  var replacement_text = 'name="test$1'+ count +'"';
  //console.log(replacement_text);
  var regular_expression = /name="(.+)"/g;
  var replaced = sContainerHTML.replace('name=\"(.+)\"'g, '$1 $2');

  console.log(replaced);

});
