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
var page = 0;
var filter = $('#sessions-paged').data('filter');
var pageCount = $('#sessions-paged').data('pages');

var pages = pageCount;
if(pageCount > 12){
	pages = 12;
}


for(var i = 0; i < pageCount; i++){
	if(i == pageCount-1){
		$('.pages').append('<li><a class="page last" data-page="'+i+'">'+(i+1)+'</div>');
	} else {
		$('.pages').append('<li><a class="page" data-page="'+i+'">'+(i+1)+'</div>');
	}
}

$('.page').each(function(){
	if($(this).data('page') == page){
		$(this).addClass('active')
	} else{
		$(this).removeClass('active')
	}
})



if(pageCount > 1){
	$('.more').show();
} else {
	$('.more').hide();
}

if(page == 0){
	$('#prev').hide();
}



$('#next').on('click', function(){
	page++;
	url = $('#Form_FilterForm').data('url') +'changePage';
	$('html').css('cursor', 'wait');
	$.post(url, {pager:page, filter:filter }, function(data){

		$('#sessions-paged').html(data);

		$('html').css('cursor', 'default');

		window.scrollTo(0,0);

		$('#prev').show();
	
		if(page == pageCount){
			$('#next').hide();
		}
		$('.page').each(function(){
			if($(this).data('page') == page){
				$(this).addClass('active')
			} else{
				$(this).removeClass('active')
			}
		})
	});	

});

$('#prev').on('click', function(){
	page--;
	url = $('#Form_FilterForm').data('url') +'changePage';
	$('html').css('cursor', 'wait');
	$.post(url, {pager:page, filter:filter}, function(data){
		$('#sessions-paged').html(data);
		$('html').css('cursor', 'default');
		window.scrollTo(0,0);

		$('#next').show();
		
		if(page == 0){
			$('#prev').hide();
		}
		$('.page').each(function(){
			if($(this).data('page') == page){
				$(this).addClass('active')
			} else{
				$(this).removeClass('active')
			}
		})
	});	
});

$('.page').on('click', function(){
	oldPage = page;
	page = $(this).data('page');
	url = $('#Form_FilterForm').data('url') +'changePage';
	$('html').css('cursor', 'wait');
	$.post(url, {pager:page, filter:filter}, function(data){

		$('#sessions-paged').html(data);

		$('html').css('cursor', 'default');

		window.scrollTo(0,0);
	
		if(page == pageCount-1){
			$('#next').hide();
		} else {
			$('#next').show();
		}
		
		if(page == 0){
			$('#prev').hide();
		} else {
			$('#prev').show();
		}

		$('.page').each(function(){
			if($(this).data('page') == page){
				$(this).addClass('active')
			} else{
				$(this).removeClass('active')
			}
		})




		// if(pageCount > 12){
		// 	if(page != pageCount){
		// 		if(page >= 5){
		// 			$('.page').each(function(){
		// 				newIndex = $(this).data('page')+1;
		// 				$(this).data('page') = newIndex;
		// 				$(this).html(newIndex+1);
		// 			})
		// 		}
		// 	}
		// }
	});	
});




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
