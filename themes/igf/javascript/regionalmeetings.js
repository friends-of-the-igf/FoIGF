jQuery.noConflict();

(function($) {
$(document).ready(function() {
	



	

	function loadRegion(id){
		url = $('#Regional').data('url') +'getMeetingsData';
		$('html').css('cursor', 'wait');
		$.post(url, {id:id}, function(data){
			$('#Regional-Meetings').html(data);
			$('html').css('cursor', 'default');
		});
	}

	$('area').on('click', function(e){
		e.preventDefault();
		$('area').removeClass('active');
		$('.country').fadeOut('fast');

		$(this).addClass('active');
		var id = $(this).data('id');
		loadRegion(id);
		var region = $(this).attr('continent');
		$('.country').fadeOut('fast');
		var highlight = $('img[id*=' + region + ']');
		highlight.fadeIn('fast');
	});

	$('area').hover(function() {
			alert('nasty');
		var region = $(this).attr('continent');
		var highlight = $('img[id*=' + region + ']');
		highlight.fadeIn('fast');
		
		},
		function(){
			alert($(this).hasClass('active'));
			if($(this).hasClass('active') == false){
			var region = $(this).attr('continent');
			var highlight = $('img[id*=' + region + ']');
				
				highlight.fadeOut('fast');
		
		}
		
		
	});

	



	


	

	
});
}(jQuery));
