jQuery.noConflict();

(function($) {
$(document).ready(function() {
	$('.list').hide();
	$('.topic-list').hide();


	$('.switch').on('click', function(){

		var list = $(this).parents('.ses:first').find('.list');
	
		if(list.css('display') == 'none'){
			$('.list').hide();
			list.show();
			$('.arrow').find('a').html('&#9660');
			$(this).parent().find('.arrow').find('a').html('&#9650');
		} else if(list.css('display') == 'block') {
			$('.list').hide();
			list.hide();
			
			$(this).parent().find('.arrow').find('a').html('&#9660');
		}

	})

	$('.topic-switch').on('click', function(){

		var list = $(this).parents('.topics:first').find('.topic-list');
	
		if(list.css('display') == 'none'){
			$('.topic-list').hide();
			list.show();
			$('.topic-arrow').find('a').html('&#9660');
			$(this).parent().find('.topic-arrow').find('a').html('&#9650');
		} else if(list.css('display') == 'block') {
			$('.topic-list').hide();
			list.hide();
			$(this).parent().find('.topic-arrow').find('a').html('&#9660');
			// $(this).parent().find('a.topic-switch').find('a.topic-switch').html('&#9660');
		}

	})

	


});
}(jQuery));
