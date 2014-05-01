jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm').submit(function(e){
		ga('send', 'event', 'button', 'click', 'QR_Win_A_Grand', 'Clicked_through_to_register');
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
