(function($) {
	'use strict';

	var show_popup = true;
	/* Set div to mouse */
	$(document).mousemove( function(e) {
		$('#popup').css({'top':e.pageY+20,'left':e.pageX+10});
	});

	$( "a.username-coloured" ).mouseover(function() {
		show_popup = true;
		var id = getURLParameter(($(this).attr('href')), 'u');
		var url = userinfo_url.replace('USERID', id);

		$.get(url, function( responseText ) {
			var data = eval (responseText);
			$('#ajax_username').html(data[0].username);
			$('#ajax_registert').html(data[0].regdate);
			$('#ajax_posts').html(data[0].posts);
			$('#ajax_last_visit').html(data[0].lastvisit);
			$('#ajax_avatar').html(data[0].avatar);
			$('#ajax_rank').html(data[0].rank);
			if (show_popup)
			{
				$( "#popup" ).show();
			}
		});

	});

	$( "a.username-coloured" ).mouseout(function() {
		show_popup = false;
		$( "#popup" ).hide();
	});
})(jQuery);

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}