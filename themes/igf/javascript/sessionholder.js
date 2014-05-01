jQuery.noConflict();

(function($) {
$(document).ready(function() {

	var speakerArr = [];

	url = $('#Form_FilterForm').data('url') +'getSpeakers';
	$.get(url, function(data){
		if(data != false){
			for(var i in data){
			    speakerArr.push(data [i]);
			}
		}
	}, "json");

	$('.typeahead').typeahead({
		source: speakerArr
	});


//filter form---------

if($('#Form_FilterForm').css('display') == 'none'){	
	$('#filter-form').find('.arrow').html('&#9660');
}

$('#filter-form').on('click', function(){
	if($('#Form_FilterForm').css('display') == 'none'){	
		$('#Form_FilterForm').show();
		$('#filter-form').find('.arrow').html('&#9650');
	} else {
		$('#Form_FilterForm').hide();
		$('#filter-form').find('.arrow').html('&#9660');
	}
})

$('#tag-head').on('click', function(){
	if($('#tag-list').css('display') == 'none'){	
		$('#tag-list').show();
		$('#tag-head').find('.arrow').html('&#9650');
	} else {
		$('#tag-list').hide();
		$('#tag-head').find('.arrow').html('&#9660');
	}
})
$('.more').show();
$('#next').show();
$('#prev').show();

//load counts-------------------
var s_count = $('#counts').data('sessions');
var m_count = $('#counts').data('meetings');
$('#s-count').html(s_count);
$('#m-count').html(m_count);


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$('#Form_FilterForm_action_doSearch').on('click', function(){
	var string = '';
	if($('#Topic').find('input:checked').length > 0){
		string += 'Topics: ';
		$('#Topic').find('input:checked').each(function(){
			string += $(this).siblings('label').html() + ', ';
		});
		string += ' - ';
	}
	if($('#Meeting').find('option:selected').val() != ''){
		string += 'Meeting: ';
		string += $('#Meeting').find('option:selected').html() + ' - ';
	}
	if($('#Day').find('option:selected').val()  != ''){
		string += 'Day: ';
		string += $('#Day').find('option:selected').html() + ' - ';
	}
	if($('#Type').find('option:selected').val()  != ''){
		string += 'Type: ';
		string += $('#Type').find('option:selected').html() + ' - ';
	}
	if($('#Speaker').find('input').val()  != ''){
		string += 'Speaker: ';
		string += $('#Speaker').find('input').val() + ' - ';
	}
	if($('#Sort').find('input:checked').length > 0){
		string += 'Sort: ';
		string += $('#Sort').find('input:checked').val();
	}
	
	ga('send', 'event', 'SessionFilter', 'Submit', string);

});

	

	
	


	
});
}(jQuery));
