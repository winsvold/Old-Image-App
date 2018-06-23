var lightswitch = false;
var lights = new Array();
var buttonid = '#lightswitch'; 
var maskid = '#mask';
var maskplotterid = '#maskplotter';
var buttoncontainerid = '#buttoncontainer';

// Turn on create light function
$( buttonid ).find("button").click(function(){
	if(!lightswitch){
		lights.length = 0;
		lightswitch = true;
		$(this).html('Render Light');
		$( maskid ).children('img').remove();
		$( maskplotterid ).css('display','inline');
	}
	else { // render image
		lightswitch = false;
		$(this).html('Make Light');
		$( maskplotterid ).css('display','none');
		// Request mask from php script
		var wwidth = $(window).width();
		var wheight = $(window).height();
		var tempstring = wwidth + ',' + wheight + '|';
		tempstring = tempstring + lights;

		$('<img src="light/createlight.php?lights=' + tempstring + '">').load(function() {
		  $(this).height( $(window).height() ).width( $(window).width() ).appendTo( maskid );
		});
	}
});


// Register position of clicks when making lights
$(document).ready(function(e) {
    $(window).click(function(e) {
        if(lightswitch && $( buttoncontainerid ).css("opacity") == 0 ){	
			var width = $( maskplotterid ).css('width').replace(/[^-\d\.]/g, '');
			var height = $( maskplotterid ).css('height').replace(/[^-\d\.]/g, '');
			lights.push ( e.pageX, e.pageY, width, height ); // x pos, y pos, width, height
		}
    });
});

// Resize mask
$(window).on('DOMMouseScroll mousewheel', function (e) {
	if(lightswitch){
		var plottersize = parseInt($( maskplotterid ).css( 'height' ).replace(/[^-\d\.]/g, ''));
		var top = parseInt($( maskplotterid ).css('top'));
		var left= parseInt($( maskplotterid ).css('left'));

		if(e.originalEvent.detail > 0 || e.originalEvent.wheelDelta < 0) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
		//scroll down
		plottersize -= 50;
		top += 25;
		left += 25;
		} else {
		//scroll up
		plottersize += 50;
		top -= 25;
		left -= 25;
		}
		$( maskplotterid ).css( 'height', plottersize + 'px');
		$( maskplotterid ).css('left', left + 'px');
		$( maskplotterid ).css('top', top + 'px');
		//prevent page fom scrolling
		return false;
	}
});


// Lay maskplotter png over mouselocation
$( window ).mousemove(function( e ) {
	if(lightswitch){
		var width = $( maskplotterid ).css('width').replace(/[^-\d\.]/g, '');
		var height = $( maskplotterid ).css('height').replace(/[^-\d\.]/g, '');
		$( maskplotterid ).css('top', e.pageY - height/2 + 'px');
		$( maskplotterid ).css('left', e.pageX - width/2 + 'px');		

	}	
});