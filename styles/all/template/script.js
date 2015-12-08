(function($) {
	'use strict';

	/* Set div to mouse */
	$(document).mousemove( function(e) {
		$('#popup').css({'top':e.pageY+20,'left':e.pageX+10});
	});

	$("a.username-coloured, a.username").mouseover(display_userinfo);
	$("a.username-coloured, a.username").mouseout(hide_userinfo);

	/* Display the info popup */
	function display_userinfo(){
		show_popup = true;
		var id = getURLParameter(($(this).attr('href')), 'u');
		var url = userinfo_url.replace('USERID', id);

		$.get(url, function( responseText ) {
			var data = eval (responseText);
			$.each(data[0], function(index, value){
				$('#ajax_'+index).html(value);
			});

			if (show_popup) {
				$( "#popup" ).show();
			}
		});
	}

	/* Hide the info popup */
	function hide_userinfo() {
		show_popup = false;
		$( "#popup" ).hide();
		$( "#popup" ).find('span').each(function(){
			$(this).html('');
		});
	}
})(jQuery);

function getURLParameter(url, name) {
	return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}