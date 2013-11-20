jQuery.noConflict();

(function($) {
$(document).ready(function() {

	var speakerArr = [];

	url = $('#Form_FilterForm').data('url') +'getSpeakers';
	$.get(url, function(data){
		for(var i in data){
		    speakerArr.push(data [i]);
		}
	}, "json");

	$('.typeahead').typeahead({
		source: speakerArr
	});


//filter form---------

if($('#Form_FilterForm').css('display') == 'none'){	
	$('#filter-form').find('.arrow').html('&#9660');
}

$('#filter-form').on('click', function(){
	if($('#Form_FilterForm').css('display') == 'none'){	
		$('#Form_FilterForm').show();
		$('#filter-form').find('.arrow').html('&#9650');
	} else {
		$('#Form_FilterForm').hide();
		$('#filter-form').find('.arrow').html('&#9660');
	}
})

$('#tag-head').on('click', function(){
	if($('#tag-list').css('display') == 'none'){	
		$('#tag-list').show();
		$('#tag-head').find('.arrow').html('&#9650');
	} else {
		$('#tag-list').hide();
		$('#tag-head').find('.arrow').html('&#9660');
	}
})
$('.more').show();
$('#next').show();
$('#prev').show();


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

	

	
	


	
});
}(jQuery));
