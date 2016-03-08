(function($) {
	'use strict';

	/* Set div to mouse */
	$(document).mousemove( function(e) {
		var docX, docY, posX, posY;
		if(e) {
			if(typeof(e.pageX) === 'number') { docX = e.pageX; docY = e.pageY;}
			else {docX = e.clientX; docY = e.clientY;}
		} else {
			docY += $(window).scrollTop();
			docX += $(window).scrollLeft();
		}
		if (docX > $(window).width() - 400) {
			posX = (docX - 350);
		} else {
			posX = (docX - 15);
		}
		if (docY > $(window).height() - 155) {
			posY = (docY - 145);
		} else {
			posY = (docY + 35);
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