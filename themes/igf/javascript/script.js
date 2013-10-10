jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#CustomSearchForm_CustomSearchForm').submit(function(e){
		if($.trim($('#CustomSearchForm_CustomSearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
