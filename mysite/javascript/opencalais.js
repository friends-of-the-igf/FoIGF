jQuery.noConflict();

(function($) {
$(document).ready(function() {


	$('input[name="action_extractTags"]').live('click', function(e){
		e.preventDefault();
		$('.loading').show();
		var base = $(this).data('base');
		url = base + 'OpenCalaisController/suggestTags'
		id = $(this).data('id');
		$.post(url, {id:id}, function(result){
			$('.loading').hide();
			$('#table-holder').html(result);
			$('.add-tag').bind('click', function(e){
				e.preventDefault();
				$(this).parent().find('.loader').show();
				url = base + 'OpenCalaisController/addTag';
				var tag = $(this).data('tag');
				$.post(url, {tag:tag, id:id}, function(result){
					$('.add-tag[data-tag="'+tag+'"]').parent().find('.loader').hide();
					$('.add-tag[data-tag="'+tag+'"]').parent().html('<i>Added</i>');					
				});
			});
		});
	});




});
}(jQuery));
