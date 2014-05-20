jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('.area-nav li a').on('click', function(e){
		e.preventDefault();
		$('.area-nav li a').removeClass('active');
		$(this).addClass('active');
		$('.area').removeClass('active');
		$('.area[data-area="'+$(this).data('area')+'"]').addClass('active');
	});

	$('.type-nav li a').on('click', function(e){
		e.preventDefault();
		$(this).parent().parent().find('li a').removeClass('active');
		$(this).addClass('active');
		$(this).parent().parent().parent().find('.type').removeClass('active');
		$(this).parent().parent().parent().find('.type[data-type="'+$(this).data('type')+'"]').addClass('active');
	});


	$('.tab-content').on('click', ' th a', function(e){
		e.preventDefault();
		var sortField = $(this).data('sort');
		var area = $('.area-nav li a.active').data('area');
		var type = $('.type-nav li a.active').data('type');
		$('.ajax-loading').addClass('active');
		if($(this).hasClass('ASC')){
			var dir = 'DESC';
		} else {
			var dir = 'ASC';
		}
		var url = $('.tab-content').data('url')+'sortColumn';
		$.post(url, {field:sortField, dir:dir, type:type, area:area }, function(result){
			$('.ajax-loading').removeClass('active');
			var parent = $('.area[data-area="'+area+'"]').find('.type[data-type="'+type+'"]');
			parent.html(result);
			parent.find('th a[data-sort="'+sortField+'"]').addClass(dir);
		});
	});


});
}(jQuery));
