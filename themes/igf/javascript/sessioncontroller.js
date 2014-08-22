jQuery.noConflict();

(function($) {
$(document).ready(function() {

	/* Tab Navs */
	var current =  $('.vid-nav ul .current').data('lang');
	$('.video-wrap').each(function(i){
		
		if($(this).hasClass(current)){
			$(this).show();
		} else {
			$(this).hide();
		}
	});
	$('.tran').each(function(i){
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
		$('.tran').each(function(i){
			if($(this).hasClass(current)){
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});

	if(!$('.vid-nav').length){
	
		$('.tran').first().show();
	}

	$('#Form_TagForm').on('submit', function(e){
		e.preventDefault();

		data = $(this).serialize();	
		url = $(this).attr('action');

		$('#Form_TagForm_action_submitTag').html('<img class="loader" src="themes/igf/images/ajax-loader-white.gif">');

		$.post(url, data, function(result){
		 	obj = JSON.parse(result);
		 	if(obj.Status == 'Failure'){
		 		alert(obj.Content);
		 	} else if(obj.Status == 'Pending') {
		 		$('#Form_TagForm_Tag').val('');
		 		$('#Form_TagForm_action_submitTag').html('<i class="fa fa-check"></i>');
		 		$('#Form_TagForm_action_submitTag').addClass('green');
		 		$('#Form_TagForm_action_submitTag').attr("disabled", true);
		 		alert(obj.Content);
		 	} else {
		 		$('.tag-list').find('div').prepend(obj.Content);
		 	}
		})

	});

	

	/* Tag Stuff */
	//Approve/Deny a tag
	$('li.pending-tag').find('a').on('click', function(e){
		e.preventDefault();
		var id = $(this).parent().data('id');
		var url = $(this).attr('href');

		$(this).parent().find('.loader').show();

		$.get(url, null, function(result){
			$('.loader').hide();
			obj = JSON.parse(result);
			if(obj.Status == 'Denied'){
		 		$('.pending-tag[data-id="'+obj.ID+'"]').remove();
		 	} else {
		 		$('.tag-list').find('div').before(obj.Content);
		 		$('.pending-tag[data-id="'+obj.ID+'"]').remove();
		 	}
		});
	});

	$('li.tag').find('.rate').find('a').on('click', function(e){
		e.preventDefault();
		var id = $(this).parent().data('id');
		var url = $(this).attr('href');

		$(this).parent().find('.loader').show();

		$.get(url, null, function(result){
			obj = JSON.parse(result);
			$('.loader').hide();
			if(obj.Status == 'Failure'){
				alert(obj.Content);
		 	} else {
		 		$('#rating_'+obj.ID).html(obj.Rating);
		 	}
		});
	});

});

}(jQuery));

