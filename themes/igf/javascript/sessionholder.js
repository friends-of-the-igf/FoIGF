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

$('#filter-form').on('click', function(){
	if($('#Form_FilterForm').css('display') == 'none'){	
		$('#Form_FilterForm').show();
	} else {
		$('#Form_FilterForm').hide();
	}
})

$('#tag-head').on('click', function(){
	if($('#tag-list').css('display') == 'none'){	
		$('#tag-list').show();
	} else {
		$('#tag-list').hide();
	}
})


//pagination----------
var page = 1;
$('.more').show();
$('#first-next').on('click', function(){
	url = $('#Form_FilterForm').data('url') +'test';
	$.post(url, {test:'123'}, function(data){alert(data)});
})

	// var index = 1;

	// if(index == 1){
	// 	$('#prev').hide();
	// 	$('#next').hide();
	// 	$('#last-prev').hide();

	// }

	// if($('.paged').length > 1){
	// 	$('.more').show();
	// } else {
	// 	$('.more').hide();
	// }

	// $('.paged').each(function(){
	// 	if($(this).data('page') != index){
	// 		$(this).hide();
	// 	}
	// })

	// $('#first-next').on('click', function(){
	// 	index++;
	// 	showIndex();
	// 	checkIndex();
		
	// });

	// $('#next').on('click', function(){
	// 	index++;
	// 	showIndex();
	// 	checkIndex();	
	// });

	// $('#prev').on('click', function(){
	// 	index--;
	// 	showIndex();
	// 	checkIndex();
	// });

	// $('#last-prev').on('click', function(){
	// 	index--;
	// 	showIndex();
	// 	checkIndex();
	// });

	// function showIndex(){
	
	// 	$('.paged').each(function(){
	// 		if($(this).data('page') != index){
	// 			$(this).hide();
	// 		} else {
	// 			$(this).show();
	// 		}
	// 	});
	// 	window.scrollTo(0,0);
	// }

	// function checkIndex(){
	// 	if(index == 1){
	// 		$('#prev').hide();
	// 		$('#next').hide();
	// 		$('#last-prev').hide();
	// 		$('#first-next').show();
	// 	} else if(index > 1 && index != $('.paged').length){
	// 		$('#prev').show();
	// 		$('#next').show();
	// 		$('#last-prev').hide();
	// 		$('#first-next').hide();
	// 	} else if (index == $('.paged').length){
	// 		$('#prev').hide();
	// 		$('#next').hide();
	// 		$('#last-prev').show();
	// 		$('#first-next').hide();
	// 	}
	// }
	

	
	


	
});
}(jQuery));
