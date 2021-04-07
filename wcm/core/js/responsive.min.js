$( document ).ready(function() {
	$( "#bgLeftMenu" ).click(function() {
		hideLeftMenu();
	});
	$( "#menu_response" ).click(function() {
		showLeftMenu();
	});

});

function hideLeftMenu() {
	$( "#fadeLeftMenu" ).removeClass("show");
	//$( "#fadeLeftMenu" ).fadeOut( "fast", function() {
		$( "#fadeLeftMenu" ).addClass("hide");
	//});
}

function showLeftMenu() {
	$( "#fadeLeftMenu" ).removeClass("hide");
	//$( "#fadeLeftMenu" ).fadeIn( "fast", function() {
		$( "#fadeLeftMenu" ).addClass("show");
	//});
}