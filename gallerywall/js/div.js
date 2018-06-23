var showbackgrounds = false;
var selectedbackgroundpath = '';
// MASONRY

$(document).ready(function(){
	$('div#gallery').masonry({
		columnWidth: 20,
		itemSelector: 'img.gallerypicture'
	});
	$('div#backgrounds').masonry({
		columnWidth: 20,
		itemSelector: 'img.backgroundpicture'
	});
});

// CHOOSE BACKGROUND

function toggleBackgroundPool(bool){
	showbackgrounds = bool;
	//$('#backgrounds').toggle();

	if (showbackgrounds){
		$('#backgrounds').css('opacity','1');
		$('#backgrounds').css('z-index','10');
	}
	else{
		$('#backgrounds').css('opacity','');
		$('#backgrounds').css('z-index','');
	}
	
}

$('#backgroundswitch').find('button').click(function(){
	toggleBackgroundPool(!showbackgrounds);	
});

$('.backgroundpicture').click(function(){
	selectedbackgroundpath = this.src;
	toggleBackgroundPool(false);
	$('body').css('background-image','url('+ selectedbackgroundpath +')');
});