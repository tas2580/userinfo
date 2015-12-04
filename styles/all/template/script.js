(function($) {
	'use strict';

	
	/* Set div to mouse */
	$(document).mousemove( function(e) {
		$('#popup').css({'top':e.pageY+20,'left':e.pageX+10});
	});

	$( "a.username" ).mouseover(display_userinfo);
	$( "a.username" ).mouseout(hide_userinfo);
	$( "a.username-coloured" ).mouseover(display_userinfo);
	$( "a.username-coloured" ).mouseout(hide_userinfo);



	/* Hide the info popup */
	function hide_userinfo(){
		show_popup = false;
		$( "#popup" ).hide();
	}
})(jQuery);

function getURLParameter(url, name) {
	return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}