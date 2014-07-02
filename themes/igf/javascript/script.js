jQuery.noConflict();

(function($) {
$(document).ready(function() {

	$('#Form_SearchForm_action_results').on('click', function(e){
		ga('send', 'event', 'GeneralSearch', 'Submit', 'Search Term: ' + $('#Form_SearchForm_Search').val());
	});

	$('#Form_SearchForm').submit(function(e){
		ga('send', 'event', 'GeneralSearch', 'Submit', 'Search Term: ' + $('#Form_SearchForm_Search').val());
		if($.trim($('#Form_SearchForm_Search').val() ) == ''){
			e.preventDefault();
		}
	});



	//Get the form and action for ease of use
	var form = $('#Form_QuestionnaireForm');
	var action = form.find('.action');
	//Hide all the text area fields
	form.find('.field.textarea').hide();

	//Logic to hide/show the right fields
	action.on('click', function(e){
		e.preventDefault();
		option = $('input[name=Purpose]:checked').val();
		if(option != 0){
			if(form.find('.optionset').css('display') != 'none'){
				form.find('.optionset').hide();
				form.find('#'+option).show();
				if(option != 'Topic'){
					action.val('Done');
				}
			} else {
				if(form.find('#Topic').css('display') != 'none'){
					action.val('Done');
					if(form.find('#Topic').find('textarea').val().length > 0){
						form.find('#Topic').hide();
						form.find('#Research').show();
					}
				} else {
					form.submit();
				}
			}
		} else {
			form.submit();
		}
	});


	//Ajax form submit and GA tracking
	form.submit(function(e){
		e.preventDefault();

		ga('send', 
			'event', 
			'QuestionnaireSubmission', 
			'Submit', 
			'SecurityID: ' + $('#Form_QuestionnaireForm_SecurityID').val() + ', Purpose: ' + $('input[name=Purpose]:checked').val()
			);

		var url = $(this).attr('action');
		var data = $(this).serialize();

		$.post(url, data);

		action.val('Thank You');
		action.addClass('finished');
		setTimeout(function(){
			$('#modal').modal('hide');
		}, 1500);
	});


	//make pop up happen
	var timer = $('body').data('session');
	var cookie = $('body').data('questionnaire');
	if(!cookie){
		if(timer >= 120){
			makeModal();
		} else {
			var timeLeft = 1000*(120 - timer);
			setTimeout(function(){
				makeModal();
			}, timeLeft);
		}
	}

	function makeModal(){
		var modal = $('#modal');
		modal.modal();
		url = $('body').data('url') + 'setFormCookie'
		$.post(url);
	}




});
}(jQuery));
