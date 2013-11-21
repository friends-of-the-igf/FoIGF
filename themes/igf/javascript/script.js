jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm').submit(function(e){
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
