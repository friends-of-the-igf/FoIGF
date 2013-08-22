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


	var index = 1;

	if(index == 1){
		$('#prev').hide();
		$('#next').hide();
		$('#last-prev').hide();

	}

	if($('.paged').length > 1){
		$('.more').show();
	} else {
		$('.more').hide();
	}

	$('.paged').each(function(){
		if($(this).data('page') != index){
			$(this).hide();
		}
	})

	$('#first-next').on('click', function(){
		index++;
		showIndex();
		checkIndex();
		
	});

	$('#next').on('click', function(){
		index++;
		showIndex();
		checkIndex();	
	});

	$('#prev').on('click', function(){
		index--;
		showIndex();
		checkIndex();
	});

	$('#last-prev').on('click', function(){
		index--;
		showIndex();
		checkIndex();
	});

	function showIndex(){
	
		$('.paged').each(function(){
			if($(this).data('page') != index){
				$(this).hide();
			} else {
				$(this).show();
			}
		});
		window.scrollTo(0,0);
	}

	function checkIndex(){
		if(index == 1){
			$('#prev').hide();
			$('#next').hide();
			$('#last-prev').hide();
			$('#first-next').show();
		} else if(index > 1 && index != $('.paged').length){
			$('#prev').show();
			$('#next').show();
			$('#last-prev').hide();
			$('#first-next').hide();
		} else if (index == $('.paged').length){
			$('#prev').hide();
			$('#next').hide();
			$('#last-prev').show();
			$('#first-next').hide();
		}
	}
	

	
	


	
});
}(jQuery));
