jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm_action_results').on('click', function(e){
		ga('send', 'event', 'GeneralSearch', 'Submit', 'Search Term: ' + $('#Form_SearchForm_Search').val());
	});

	$('#Form_SearchForm').submit(function(e){
		
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

});
}(jQuery));
