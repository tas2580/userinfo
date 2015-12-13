(function($) {
	'use strict';

	/* Set div to mouse */
	$(document).mousemove( function(e) {
		$('#popup').css({'top':e.pageY+20,'left':e.pageX+10});
	});
	var timeout;
	$("a.username-coloured, a.username").hover(
		function() {
			var id = getURLParameter(($(this).attr('href')), 'u');
			var url = userinfo_url.replace('USERID', id);
			timeout = setTimeout(function(){
				$.get(url, function( responseText ) {
					var data = responseText;
					$.each(data, function(index, value){
						$('#ajax_'+index).html(value);
					});
					$( "#popup" ).show();
				});
			}, 500);
		},
		function(){
			clearTimeout(timeout);
			$( "#popup" ).hide();
			$( "#popup" ).find('span').each(function(){
				$(this).html('');
			});
		}
	);
})(jQuery);

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}