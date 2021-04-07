function PadLeft(txtValue, totalDigits,padTxt) {
	txtValue = txtValue.toString();
	var pd = '';
	if (totalDigits > txtValue.length) {
		for (i=0; i < (totalDigits - txtValue.length); i++) pd += padTxt.toString();
	}
	return pd + txtValue.toString() ;
}

function redirect(url){
	window.location.assign(url);
}
function disableDraggingFor(element) {
  // this works for FireFox and WebKit in future according to http://help.dottoro.com/lhqsqbtn.php
  element.draggable = false;
  // this works for older web layout engines
  element.onmousedown = function(event) {
    event.preventDefault();
    return false;
  };
}