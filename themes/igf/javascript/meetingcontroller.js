jQuery.noConflict();

(function($) {
$(document).ready(function() {
	$('#listZero').hide();
	$('#listOne').hide();
	$('#listTwo').hide();
	$('#listThree').hide();
	$('#listFour').hide();

	$('#dayZero').on('click', function(){
		$('#listZero').toggle(0, function(){
			changeArrow()
		});
		$('#listOne').hide();
		$('#listTwo').hide();
		$('#listThree').hide();
		$('#listFour').hide();
		
	});
	$('#dayOne').on('click', function(){
		$('#listZero').hide();
		$('#listOne').toggle(0, function(){
			changeArrow()
		});
		$('#listTwo').hide();
		$('#listThree').hide();
		$('#listFour').hide();

	});
	$('#dayTwo').on('click', function(){
		$('#listZero').hide();
		$('#listOne').hide();
		$('#listTwo').toggle(0, function(){
			changeArrow()
		});
		$('#listThree').hide();
		$('#listFour').hide();
		
	});
	$('#dayThree').on('click', function(){
		$('#listZero').hide();
		$('#listOne').hide();
		$('#listTwo').hide();
		$('#listThree').toggle(0, function(){
			changeArrow()
		});
		$('#listFour').hide();

	});
	$('#dayFour').on('click', function(){

		$('#listZero').hide();
		$('#listOne').hide();
		$('#listTwo').hide();
		$('#listThree').hide();
		$('#listFour').toggle(0, function(){
			changeArrow()
		});
	
	});

	function changeArrow(){
		if($('#listZero').css('display') == 'none'){
			$('.arrow').find('#dayZero').html('&#9650');
		} else if($('#listZero').css('display') == 'block') {
			$('.arrow').find('#dayZero').html('&#9660');
		}
		if($('#listOne').css('display') == 'none'){
			$('.arrow').find('#dayOne').html('&#9650');
		} else if($('#listOne').css('display') == 'block') {
			$('.arrow').find('#dayOne').html('&#9660');
		}
		if($('#listTwo').css('display') == 'none'){
			$('.arrow').find('#dayTwo').html('&#9650');
		} else if($('#listTwo').css('display') == 'block') {
			$('.arrow').find('#dayTwo').html('&#9660');
		}
		if($('#listThree').css('display') == 'none'){
			$('.arrow').find('#dayThree').html('&#9650');
		} else if($('#listThree').css('display') == 'block') {
			$('.arrow').find('#dayThree').html('&#9660');
		}
		if($('#listFour').css('display') == 'none'){
			$('.arrow').find('#dayFour').html('&#9650');
		} else if($('#listFour').css('display') == 'block') {
			$('.arrow').find('#dayFour').html('&#9660');
		}
	}

	


});
}(jQuery));
