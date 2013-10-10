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
}(jQuery));

