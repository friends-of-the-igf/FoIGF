jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm').submit(function(e){
		if($.trim($('#Form_SearchForm').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
