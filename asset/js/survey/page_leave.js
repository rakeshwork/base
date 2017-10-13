

/*window.onbeforeunload = function() {
  if(check_reaload){
      return "Data will be lost if you leave the page, are you sure?";
  }else{
    return "";
  }
};*/


var leave_message = 'You sure you want to leave?'
function goodbye(e)
{


  if(dont_confirm_leave!==1)
  {
    if(!e) e = window.event;
    //e.cancelBubble is supported by IE - this will kill the bubbling process.
    e.cancelBubble = true;
    e.returnValue = leave_message;
    //e.stopPropagation works in Firefox.
    if (e.stopPropagation)
    {
      e.stopPropagation();
      e.preventDefault();
    }

    //return works for Chrome and Safari
    return leave_message;
  }
}
window.onbeforeunload=goodbye;
