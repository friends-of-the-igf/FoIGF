jQuery.noConflict();

(function($) {
$(document).ready(function() {
	



	$('.region').on('click', function(){
		var id = $(this).data('id');
		loadRegion(id);
	})



	function loadRegion(id){
		url = $('#Regional').data('url') +'getMeetingsData';
		$('html').css('cursor', 'wait');
		$.post(url, {id:id}, function(data){
			$('#Regional-Meetings').html(data);
			$('html').css('cursor', 'default');
		});
	}			
	
});
}(jQuery));
