jQuery.noConflict();

(function($) {
$(document).ready(function() {

	if($('#meetingResults').find('li').length == 0){
		$('#noMeeting').html('<p>Sorry, your search query did not return any meetings.</p>')
	}

	if($('#sessionResults').find('li').length == 0){
		$('#noSessions').html('<p>Sorry, your search query did not return any sessions.</p>')
	}
	
});
}(jQuery));
