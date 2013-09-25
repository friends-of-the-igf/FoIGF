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

	$('#dayZeroIcon').on('click', function(){
		$('#listZero').toggle(0, function(){
			changeArrow()
		});
		$('#listOne').hide();
		$('#listTwo').hide();
		$('#listThree').hide();
		$('#listFour').hide();
		
	});
	$('#dayOneIcon').on('click', function(){
		$('#listZero').hide();
		$('#listOne').toggle(0, function(){
			changeArrow()
		});
		$('#listTwo').hide();
		$('#listThree').hide();
		$('#listFour').hide();

	});
	$('#dayTwoIcon').on('click', function(){
		$('#listZero').hide();
		$('#listOne').hide();
		$('#listTwo').toggle(0, function(){
			changeArrow()
		});
		$('#listThree').hide();
		$('#listFour').hide();
		
	});
	$('#dayThreeIcon').on('click', function(){
		$('#listZero').hide();
		$('#listOne').hide();
		$('#listTwo').hide();
		$('#listThree').toggle(0, function(){
			changeArrow()
		});
		$('#listFour').hide();

	});
	$('#dayFourIcon').on('click', function(){

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
			$('.arrow').find('#dayZeroIcon').html('&#9660');
		} else if($('#listZero').css('display') == 'block') {
			$('.arrow').find('#dayZeroIcon').html('&#9650');
		}
		if($('#listOne').css('display') == 'none'){
			$('.arrow').find('#dayOneIcon').html('&#9660');
		} else if($('#listOne').css('display') == 'block') {
			$('.arrow').find('#dayOneIcon').html('&#9650');
		}
		if($('#listTwo').css('display') == 'none'){
			$('.arrow').find('#dayTwoIcon').html('&#9660');
		} else if($('#listTwo').css('display') == 'block') {
			$('.arrow').find('#dayTwoIcon').html('&#9650');
		}
		if($('#listThree').css('display') == 'none'){
			$('.arrow').find('#dayThreeIcon').html('&#9660');
		} else if($('#listThree').css('display') == 'block') {
			$('.arrow').find('#dayThreeIcon').html('&#9650');
		}
		if($('#listFour').css('display') == 'none'){
			$('.arrow').find('#dayFourIcon').html('&#9660');
		} else if($('#listFour').css('display') == 'block') {
			$('.arrow').find('#dayFourIcon').html('&#9650');
		}
	}


});
}(jQuery));
