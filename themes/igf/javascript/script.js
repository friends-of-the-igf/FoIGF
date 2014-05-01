jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm').on('click', function(e){
		e.preventDefault();
		ga('send', 'event', 'GeneralSearch', 'Submit', 'Search Term: ' + $('#Form_SearchForm_Search').val());
		ga('send', 'event', 'OtherCategory', 'OtherAction', 'HAHAHAH');
	});

	$('#Form_SearchForm').submit(function(e){
		
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});

	
});
}(jQuery));
