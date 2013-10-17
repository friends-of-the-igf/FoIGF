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
// $('.clear').on('click', function(){
// 	$(':input','#Form_FilterForm')
// 	 .not(':button, :submit, :reset, :hidden')
// 	 .val('')
// 	 .removeAttr('checked')
// 	 .removeAttr('selected');
// });


//pagination----------
// var page = 0;
// var filter = $('#sessions-paged').data('filter');
// var pageCount = $('#sessions-paged').data('pages');

// var pages = pageCount;
// if(pageCount > 12){
// 	pages = 12;
// }


// for(var i = 0; i < pageCount; i++){
// 	if(i == pageCount-1){
// 		$('.pages').append('<li><a class="page last" data-page="'+i+'">'+(i+1)+'</div>');
// 	} else {
// 		$('.pages').append('<li><a class="page" data-page="'+i+'">'+(i+1)+'</div>');
// 	}
// }

// $('.page').each(function(){
// 	if($(this).data('page') == page){
// 		$(this).addClass('active')
// 	} else{
// 		$(this).removeClass('active')
// 	}
// })



// if(pageCount > 1){
// 	$('.more').show();
// } else {
// 	$('.more').hide();
// }

// if(page == 0){
// 	$('#prev').hide();
// }



// $('#next').on('click', function(){
// 	page++;
// 	url = $('#Form_FilterForm').data('url') +'changePage';
// 	$('html').css('cursor', 'wait');
// 	$.post(url, {pager:page, filter:filter }, function(data){

// 		$('#sessions-paged').html(data);

// 		$('html').css('cursor', 'default');

// 		window.scrollTo(0,0);

// 		$('#prev').show();
	
// 		if(page == pageCount){
// 			$('#next').hide();
// 		}
// 		$('.page').each(function(){
// 			if($(this).data('page') == page){
// 				$(this).addClass('active')
// 			} else{
// 				$(this).removeClass('active')
// 			}
// 		})
// 	});	

// });

// $('#prev').on('click', function(){
// 	page--;
// 	url = $('#Form_FilterForm').data('url') +'changePage';
// 	$('html').css('cursor', 'wait');
// 	$.post(url, {pager:page, filter:filter}, function(data){
// 		$('#sessions-paged').html(data);
// 		$('html').css('cursor', 'default');
// 		window.scrollTo(0,0);

// 		$('#next').show();
		
// 		if(page == 0){
// 			$('#prev').hide();
// 		}
// 		$('.page').each(function(){
// 			if($(this).data('page') == page){
// 				$(this).addClass('active')
// 			} else{
// 				$(this).removeClass('active')
// 			}
// 		})
// 	});	
// });

// $('.page').on('click', function(){
// 	oldPage = page;
// 	page = $(this).data('page');
// 	url = $('#Form_FilterForm').data('url') +'changePage';
// 	$('html').css('cursor', 'wait');
// 	$.post(url, {pager:page, filter:filter}, function(data){

// 		$('#sessions-paged').html(data);

// 		$('html').css('cursor', 'default');

// 		window.scrollTo(0,0);
	
// 		if(page == pageCount-1){
// 			$('#next').hide();
// 		} else {
// 			$('#next').show();
// 		}
		
// 		if(page == 0){
// 			$('#prev').hide();
// 		} else {
// 			$('#prev').show();
// 		}

// 		$('.page').each(function(){
// 			if($(this).data('page') == page){
// 				$(this).addClass('active')
// 			} else{
// 				$(this).removeClass('active')
// 			}
// 		})

// 	});	
// });


// var currentTopic = getParameterByName('topic');
// if(currentTopic == ''){
// 	$('#all').css('color', '#D53F55');
// }
// $('.topic').each(function(){
// 	if($(this).data('id') == currentTopic){
// 		$(this).css('color', '#D53F55');
// 	}
// })



function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

	

	
	


	
});
}(jQuery));
