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

	// $('area').hover(function() {
	
		
	// 	var region = $(this).attr('continent');
	// 	var highlight = $('img[id*=' + region + ']');
	// 	highlight.fadeIn('fast');
		
	// 	},
	// 	function(){
	// 		if($(this).hasClass('active') == false){
	// 		$(this).mouseout(function() {
	// 			highlight.fadeOut('fast');
	// 		});
	// 	}
	// 	//console.log(highlight);
		
	// });

	



	


	

	
});
}(jQuery));
