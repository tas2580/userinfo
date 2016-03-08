(function($) {
	'use strict';

	/* Set div to mouse */
	$(document).mousemove( function(e) {
		var docX, docY, pos;
		if(e) {
			if(typeof(e.pageX) === 'number') { docX = e.pageX; docY = e.pageY;}
			else {docX = e.clientX; docY = e.clientY;}
		} else {
			docY += $(window).scrollTop();
			docX += $(window).scrollLeft();
		}
		if (docX > $(window).width() - 400) {
			pos = (docX - 350);
		} else {
			pos = (docX - 15);
		}

		$('#popup').css({'top':(docY+35),'left':pos});
	});

	var data = new Array();
	var show_popup = false;
	$('a.username-coloured, a.username').mouseover(
		function() {
			var id = getURLParameter(($(this).attr('href')), 'u');
			var url = userinfo_url.replace('USERID', id);

			if(typeof data[id] === 'undefined') {
				$.get(url, function( responseText ) {
					data[id] = responseText;
					show_popup = true;
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
				$('#popup').show();
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