(function($) {
	'use strict';

	/* Set div to mouse */
	$(document).mousemove( function(e) {
		var docX, docY, posX, posY;
		var popup_height = $('#popup').height();

		docX = e.clientX + $(window).scrollLeft();
		docY = e.clientY + $(window).scrollTop();

		if (docX > $(window).width() - 400) {
			posX = (docX - 350);
		} else {
			posX = docX;
		}
		if (docY > ($(window).height() + $(window).scrollTop()) - popup_height - 40) {
			posY = (docY - (popup_height + 20));
		} else {
			posY = (docY + 30);
		}
		$('#popup').css({'top':posY,'left':posX});
	});

	var data = new Array();
	var show_popup = false;
	$('a.username-coloured, a.username').mouseover(
		function() {
			var id = getURLParameter(($(this).attr('href')), 'u');
			var url = userinfo_url.replace('USERID', id);
			show_popup = true;
			if(typeof data[id] === 'undefined') {
				$.get(url, function( responseText ) {
					data[id] = responseText;
					$.each(data[id], function(index, value){
						$('#ajax_'+index).html(value);
					});
					if (show_popup) {
						$( '#popup').show();
					}
				});
			} else {
				$.each(data[id], function(index, value){
					$('#ajax_'+index).html(value);
				});
				if (show_popup) {
					$( '#popup').show();
				}
			}
		}
	);
	$('a.username-coloured, a.username').mouseout(
		function(){
			show_popup = false;
			$('#popup').hide();
			$('#popup').find('span').each(function(){
				$(this).html('');
			});
		}
	);
})(jQuery);

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}