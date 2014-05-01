jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm').on('click', function(){
		ga('send', 'event', 'GeneralSearch', 'Submit', $('#Form_SearchForm_Search').val());
	});

	$('#Form_SearchForm').submit(function(e){
		
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
