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
	$('.country').hover(
		function(){
			var country = $(this).attr('id');
		
			$(this).find('img').attr('src', 'themes/igf/images/map/hover/'+country+'.png');
		},
		function(){
			var country = $(this).attr('id');
			if(!$(this).hasClass('active')){
				$(this).find('img').attr('src', 'themes/igf/images/map/default/'+country+'.png');
			}
		});

	$('.country').on('click',
		function(){
			var country = $(this).attr('id');
			$('.country').each(function(i){
				if($(this).attr('id') != country){
					$(this).find('img').attr('src', 'themes/igf/images/map/default/'+$(this).attr('id')+'.png');
				}
			})
			$(this).find('img').attr('src', 'themes/igf/images/map/hover/'+country+'.png');
			$(this).addClass('active');
		});




	//africa
	// $('#africa').on('hover', function(){
	// 	$(this).find('img').attr('src', 'themes/igf/images/map/hover/africa.png');
	// },
	// function(){
	// 	$(this).find('img').attr('src', 'themes/igf/images/map/default/africa.png');
	// });

	// $('#africa').on('click', function(){
	// 	$(this).find('img').attr('src', 'themes/igf/images/map/hover/africa.png');
	// });
	


	

	
});
}(jQuery));
