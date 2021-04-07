var newWindow;
function openPopup(popup_url,popup_width,popup_height,var_name){
	var windowHeight = popup_height;
	var windowWidth = popup_width;
	var windowName = var_name;
	var windowUri = popup_url;

	var centerWidth = (window.screen.width - windowWidth) / 2;
	var centerHeight = (window.screen.height - windowHeight) / 2;

	if ( !newWindow || newWindow.closed ){
		try{
			var dimension = "";
			if(windowHeight && windowWidth){
				dimension = ',width=' + windowWidth +
				',height=' + windowHeight +
				',left=' + centerWidth +
				',top=' + centerHeight;
			}

			newWindow = window.open(windowUri, windowName,'addressbar=0,menubar=0,location=0,status=0,scrollbars=yes,resizable=yes'+dimension);
				//newWindow.location.href; // throws exception if after reload
				//newWindow.location = windowUri;
		}catch(e){};
	}else if ( newWindow && ! newWindow.closed ){
		newWindow.location.replace(windowUri);
		newWindow.focus();
	}
}