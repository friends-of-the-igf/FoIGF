jQuery.noConflict();

(function($) {
$(document).ready(function() {

	var speakerArr = [];

	url = window.location.href.split('?')[0] + '/getSpeakers';
	$.get(url, function(data){
		for(var i in data){
		    speakerArr.push(data [i]);
		}
	}, "json");

	$('.typeahead').typeahead({
		source: speakerArr
	});

	

	
	


	
});
}(jQuery));
