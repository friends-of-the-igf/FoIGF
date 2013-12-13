jQuery.noConflict();

(function($) {
$(document).ready(function() {

	var tagsArr = [];

	url = $('#Form_TagForm').data('url') +'getTags';
	$.get(url, function(data){
		for(var i in data){
		    tagsArr.push(data [i]);
		}
	}, "json");

	$('.typeahead').typeahead({
		source: tagsArr
	});

	});

	var current =  $('.vid-nav ul .current').data('lang');
	$('.video-wrap').each(function(i){
		
		if($(this).hasClass(current)){
			$(this).show();
		} else {
			$(this).hide();
		}
	});
	$('.vid-nav ul li').on('click', function(){
		$('.vid-nav ul li').each(function(i){
			$(this).removeClass('current');
		})
		$(this).addClass('current');
		var current =  $(this).data('lang');
		$('.video-wrap').each(function(i){
			if($(this).hasClass(current)){
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});

}(jQuery));

