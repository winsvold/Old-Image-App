var showgallery = false;
var frameswitch = false;
var deleteswitch = false;
var galleryimagepopup = false;
var framecount = 0;
var movepic = false;
var mousepos = {
	x : 0,
	y : 0,
};
var selectedframe = 'none';
var selectedpicture = {};
var images = [];

//TURN FRAME MAKER ON / OFF
$('#frameswitch').find('button').click(function(){
	if(!frameswitch){
		if(deleteswitch){
			deleteswitch = false;
			$('#deleteswitch').find('button').html('Delete Frames');
		}
		$(this).html('Render frame');
		frameswitch = true;
		framecount++;
	} else {
		$(this).html('Make frame');
		frameswitch = false;
		makeframe();
	}
	$('#framemaker').toggle();
});



//SHOW GALERY AND SELECT PICTURE
function toggleGallery(bool){
	showgallery = bool;
	if (showgallery){
		$('#gallery').css('opacity','1');
		$('#gallery').css('z-index','10');
	}
	else{
		$('#gallery').css('opacity','');
		$('#gallery').css('z-index','');
	}
}

$('#framemaker').find('button').click(function(){
	toggleGallery(!showgallery);	
});

$('.gallerypicture').click(function(){
	selectedpicture.path = this.src;
	var img = '<img src='+selectedpicture.path+' />';
	$(img).appendTo('#galleryimagepopup').click(function (){
		$('#galleryimagepopup').css('z-index','');
		$(this).remove();
		toggleGallery(false);
	});
	$('#galleryimagepopup').css('z-index','11');
});

//TURN DELETE FRAME MODE ON / OFF
$('#deleteswitch').find('button').click(function(){
	if(!deleteswitch && !frameswitch){
		$(this).html('Turn Off Delete Mode');
		deleteswitch = true;
		selectedframe = '';
	} else if(frameswitch){
		alert('Turn off Frame Maker to delete frames');
	} else {
		$(this).html('Delete Frames');
		deleteswitch = false;

	}
});

//ADMINISTER SELECTED IMAGE
//FIND IMAGES IN GALLERY AND PUT THEM IN ARRAY images[]
function indexpictures(){
	images = $('#gallery').find('img');
	selectedpicture.index = 0;
	selectedpicture.path = images[selectedpicture.index].src;
	
};
indexpictures();

//USE ARROW KEYS TO SHUFFLE THROUGH PICTURES
$(document).keydown(function(e) {
    var newpic = false;
	switch(e.which) {
        case 37: // left
		selectedpicture.index--;
		if(selectedpicture.index < 0)
			selectedpicture.index = images.length - 1;
		newpic = true;
		
        break;

        case 38: // up
        break;

        case 39: // right
		selectedpicture.index++;
		if(selectedpicture.index > images.length - 1)
			selectedpicture.index = 0;
		newpic = true;
		
		break;

        case 40: // down
        break;

        default: return; // exit this handler for other keys
    }
	selectedpicture.path = images[selectedpicture.index].src;
	
	if(newpic){
		var img = '<img src='+selectedpicture.path+' />';
		$(img).appendTo('#singleimagepopup').delay(500).fadeOut(500, function(){
			$(this).remove();
		});
		/*$('#singleimagepopup').fadeIn(0).delay(500).fadeOut(500, function(){
			$('#singleimagepopup img')[0].remove();
		});*/
	}
	
    e.preventDefault(); // prevent the default action (scroll / move caret)
});



//MAKE FRAME FUNCTION
function makeframe(){
	var dim = getdimensions();
	
	
	var phprequest = 'frame/makeframe.php?height='+dim.height+'&width='+dim.width+'&thickness='+dim.thickness+'&passepartout='+dim.passepartout+'&scndpassepartout='+dim.scndpassepartout+'&shadow='+dim.shadow+'&red='+dim.red+'&green='+dim.green+'&blue='+dim.blue+'&shadowpath='+dim.shadowpath+'&passepartoutpath='+dim.passepartoutpath+'&framepath='+dim.framepath+'&globalshadowpath='+dim.globalshadowpath+'&savetag='+dim.savetag+'&picpath='+selectedpicture.path+'';

	//Create wrapper div
	$('<div height="'+(+dim.height+ +dim.shadow*2)+'" width="'+(+dim.width+ +dim.shadow*2)+'" class="divframe" id="divframe'+framecount+'" ></div>').appendTo( '#frames' );
	//Request frame
	$('<img class="frame" id="frame'+framecount+'" src="'+phprequest+'">').load(function() {
		
		//Add frame to picture
		$(this).appendTo( '#divframe'+framecount );
	
		//Add picture to frame
		var top = +dim.shadow+ +dim.thickness+ +dim.passepartout + +dim.scndpassepartout;
		var left = +dim.shadow+ +dim.thickness+ +dim.passepartout + +dim.scndpassepartout;
		var height = +dim.height - (+dim.passepartout + +dim.thickness + +dim.scndpassepartout)*2 +1; 
		var width = +dim.width - (+dim.passepartout + +dim.thickness + +dim.scndpassepartout)*2 +1;
		
		$('<img class="framedpicture" height="'+(height)+'" width="'+(width)+'" src="'+selectedpicture.path+'" />').load(function(){
			$(this).appendTo( '#divframe'+framecount );
			$('#divframe'+framecount).find('img.framedpicture').css({ 'top': top, 'left': left });
		});
		
	});
	
	
	// Make new frame movable
	$("#frames").on("click", 'img#frame'+framecount, function(e){
		if(!movepic){
			movepic=true;
			mousepos.x = e.pageX;
			mousepos.y = e.pageY;
			selectedframe = $(this).attr('id');

		} else {
			movepic=false;
		}
	});
	
	
};


//MOVE OR DELETE FRAMES
$(document).ready(function(){
    
  $(document).mousemove(function(e){
	var moveframe = $('#'+selectedframe);
	var movediv = $('#div'+moveframe.attr('id'));
	
	if( deleteswitch ){
		movediv.remove();
	} else if( movepic ){
			
		var topdif = mousepos.y - e.pageY;
		var leftdif = mousepos.x - e.pageX;
		mousepos.y = e.pageY;
		mousepos.x = e.pageX;
		
		var prevtop = parseInt(movediv.css('top'));
		var prevleft = parseInt(movediv.css('left'));
		movediv.css({'top': (prevtop - topdif),'left': (prevleft - leftdif)});

	}
	  
  });
});


//DELETE FRAMES
$(document).ready(function(){
    
  $(document).mousemove(function(e){

	if( movepic ){
		
		var moveframe = $('#'+selectedframe);
		var movediv = $('#div'+moveframe.attr('id'));
		
		var topdif = mousepos.y - e.pageY;
		var leftdif = mousepos.x - e.pageX;
		mousepos.y = e.pageY;
		mousepos.x = e.pageX;
		
		var prevtop = parseInt(movediv.css('top'));
		var prevleft = parseInt(movediv.css('left'));
		movediv.css({'top': (prevtop - topdif),'left': (prevleft - leftdif)});

	}
	  
  });
});

//ADJUST VALUES WITH MOUSEWHEEL
var mouseoverinput = '';
$('input').mouseover(function(){
	mouseoverinput = this;
});

$(window).on('DOMMouseScroll mousewheel', function (e) {

	if(frameswitch && !showgallery){
		if (mouseoverinput.value >= 1000)
			var difference = 50;
		else if (mouseoverinput.value >= 200)
			var difference = 20;
		else if (mouseoverinput.value >= 30)
			var difference = 5;
		else
			var difference = 1;
		
		if(e.originalEvent.detail > 0 || e.originalEvent.wheelDelta < 0) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
		//scroll down
		mouseoverinput.value = parseInt(mouseoverinput.value) - difference;
		} else {
		//scroll up
		mouseoverinput.value = parseInt(mouseoverinput.value) + difference;
		}

		//prevent page fom scrolling
		return false;
	}
});

//CALCULATE WIDTH OF FRAME (using height and selected image)
setInterval(function() { 
	var inputs = getdimensions();
	
	var image = new Image();
	image.src = selectedpicture.path;
	image.onload = function(){
		var height = +image.height;
		var width = +image.width;
		var ratio = width/height;
		var newwidth = parseInt((+inputs.height - (+inputs.passepartout + +inputs.scndpassepartout + +inputs.thickness)*2) * ratio + (+inputs.passepartout + +inputs.scndpassepartout + +inputs.thickness)*2);
		$('#framemaker').find('input[name=width]').val(newwidth); 
	};
	
	
}, 200);

//GET INPUTS FROM FRAME MAKER FORM
function getdimensions(){
	var dimensions = {
		height : 			$('#framemaker').find('input[name=height]').val(),
		width : 			$('#framemaker').find('input[name=width]').val(),
		thickness : 		$('#framemaker').find('input[name=thickness]').val(),
		passepartout : 		$('#framemaker').find('input[name=passepartout]').val(),
		scndpassepartout : 	$('#framemaker').find('input[name=2ndpassepartout]').val(),
		shadow : 			$('#framemaker').find('input[name=shadow]').val(),
		red : 				$('#framemaker').find('input[name=red]').val(),
		green : 			$('#framemaker').find('input[name=green]').val(),
		blue : 				$('#framemaker').find('input[name=blue]').val(),
		framepath :			$('#framemaker').find('select[name=framechoiche]').val(),
		passepartoutpath :	$('#framemaker').find('select[name=passepartoutchoiche]').val(),
		savetag :			$('#framemaker').find('input[name=savetag]').val(),
		shadowpath :		'frameshadow.png',
		globalshadowpath :	'globalshadow.png',
	};

	return dimensions;	
};