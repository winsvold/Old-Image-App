var fullscreen = 0;
var frame = 0;
var texture = 0;
var size =1;
var vignetting = 0;
var selectedpic;
var movepic = 0;
var mouseX;
var mouseY;
var zoompanflag = 0;
var zpglobalspeed = 1;

var textures = ($('#textures').children('div').children('img'));

function stackimages(){
	$('div.container').masonry({
		columnWidth: 20,
		itemSelector: 'div.image'
	});
};

$("#frameswitch").find("button").click(function(){
	
	if(frame>=2){
		frame=0;
		$(this).html('No frame');
	}
	else{
		frame++;
		$(this).html('Frame '+frame);
		$('#frame').children('img').attr("src","frame"+frame+".png");

	}
	if(fullscreen){
		toggle_fullscreen(selectedpic);
		toggle_fullscreen(selectedpic);
	}
});

$("#textureswitch").find("button").click(function(){
	toggle_texture();
});

function toggle_texture(){
	if(texture!=textures.length){
		texture++;
		$('.background').css('background-image','url('+textures[texture-1].src+')');
	}
	else{
		texture=0;
		$('.background').css('background-image','');
	}
};

$("#sizeswitch").find("button").click(function(){
	
	
	if(size>=1){
		size=0.2;
	}
	else{
		size+=0.2;
	}
	$(this).html('Size ' + parseInt(100*size) + '%');

	if(fullscreen){
		toggle_fullscreen(selectedpic);
		toggle_fullscreen(selectedpic);
	}
});

$("#vignettingswitch").find("button").click(function(){
	
	if(!vignetting){
		vignetting=1;
		$(this).html('Vignetting on');
		$('#vignetting').css('opacity','1');
		$('#vignetting').css('z-index','110');
	}
	else{
		vignetting=0;
		$(this).html('Vignetting off');
		$('#vignetting').css('opacity','0');
		$('#vignetting').css('z-index','0');
	}

});


$( ".image" ).find("img").click(function() {
	ev = $(this).parent();
	if(zoompanflag){
		zoompanflag = 0;
		$('.background').css("backgroundColor", '');
		$(selectedpic).stop(true, true);
		$(selectedpic).css("height",'');
		$(selectedpic).css("opacity",'');
		toggle_fullscreen(selectedpic);
		toggle_fullscreen(selectedpic);
	} else {
		toggle_fullscreen(ev);
	}
});

$('#frame').click(function(e){
	if(!movepic){
		movepic=1;
		mouseX = e.pageX;
		mouseY = e.pageY;
	} else
		movepic=0;	
});



window.setInterval(function(){
	if(!fullscreen)
		stackimages();
}, 500);


function toggle_fullscreen(ev){
	
	if( $(ev).css("position")!="fixed"){
		
		selectedpic = ev;
		var winW = $( window ).width();
		var winH = $( window ).height();
		var picW = $( ev ).find('img').width();
		var picH = $( ev ).find('img').height();

		var Wratio = winW / picW;
		var Hratio = winH / picH;
		var scale = 0.98;
		if(frame)
		scale = 0.58;
		if(Wratio > Hratio){
		var ratio = Hratio *scale;
		} else {
		var ratio = Wratio * (scale-0.02);
		}
		var height = picH*ratio*size;
		var width = picW*ratio*size;

		var left = (winW - width )/2;
		var top = (winH - height )/2;

		$(ev).css("width",width);
		$(ev).css("position","fixed");
		$(ev).css("z-index","100");
		$(ev).css("top",top);
		$(ev).css("left",left);
		$(ev).css("margin","0");
		if(frame)
			$(ev).children('img').css('border', 'none');
		else{
			$(ev).children('img').css({'border-color': 'black','border-width':'2px'});
		}
		if(frame){
			if(Wratio > Hratio){
				var ratio = Hratio *0.98;
			} else {
				var ratio = Wratio * (0.98-0.02);
			}
			var height = picH*ratio*(1-0.03*picH/picW)*size;
			var width = picW*ratio*(1-0.03*picW/picH)*size;
			var left = (winW - width )/2 ;
			var top = (winH - height )/2;
			
			$('#frame').css("width",width);
			$('#frame').css("height",height);
			$('#frame').css("position","fixed");
			$('#frame').css("z-index","80");
			$('#frame').css("top",top);
			$('#frame').css("left",left);
			$('#frame').css("margin","0");
			$('#frame').css("opacity","1");
			
		} 
		
		fullscreen = 1;
		$(".background").css("z-index","50");

	} else {
		$(ev).css("width","");	  
		$(ev).css("position","absolute");
		$(ev).css("z-index","auto");	
		$(ev).css("margin","5px");
		$(ev).children('img').css('border', '');

		$('#frame').css("z-index","0");
		$('#frame').css("opacity","0");
		

		fullscreen = 0; 

		$(".background").css("z-index","0");

	}
	
};

$( "#zoompan" ).click(function() {
	if(!zoompanflag){
		zoompanflag = 1;
		$('.background').css("backgroundColor", 'black');
		zoompanvalues();
	}else{
		zoompanflag = 0;
		$('.background').css("backgroundColor", '');
		$(selectedpic).stop(true, true);
		$(selectedpic).css("height",'');
		$(selectedpic).css("opacity",'');
		toggle_fullscreen(selectedpic);
		toggle_fullscreen(selectedpic);
	}
});

$(".zpset").click(function(){
	var zpsetting = $(this).val();
	var picname = $(this).parent().parent().parent().find("img").attr('src');
	picname = picname.split('\\');
	picname = picname[picname.length - 1]
	
	$.ajax({
	  method: "POST",
	  url: "zpset.php",
	  data: { set: zpsetting, picname: picname }
	})
	  .done(function( msg ) {
		//console.log( msg );
	  });
});

$( "#zppos" ).click(function() {
	$('.zpposform').toggle();
});

$( "#zpspeed" ).click(function() {
	if(zpglobalspeed > 1.3)
		zpglobalspeed = 0.6;
	else
		zpglobalspeed += 0.2;
	$(this).find('button').html('ZPspeed '  + parseInt(zpglobalspeed*100) + '%');
	
});

function zoompanvalues(){
		
		var winW = $( window ).width();
		var winH = $( window ).height();
		var picW = $( selectedpic ).find('img').width();
		var picH = $( selectedpic ).find('img').height();

		var Wratio = winW / picW;
		var Hratio = winH / picH;
		var scale = 0.98;
		var longside = '';
		if(picW < picH){
			longside = 'height';
			var scale = 1;
			if(picH<winH)
				scale = winH/picH;
			var height = picH*winW*1.1*scale/picW;
			var width = winW*1.1*scale;
		} else {
			longside = 'width';
			var scale = 1;
			if(picW<winW)
				scale = winW/picW;
			var height = winH*1.1*scale;
			var width = picW*winH*1.1*scale/picH;
		}
		
		var radiogroup = $(selectedpic).find(".zpposform").find("form").find("input");
		for (var i = 0; i < radiogroup.length; i++) {
			var button = radiogroup[i];
			if (button.checked && !path) {
				var path=button.value;
			}
			if (button.checked && path) {
				var picspeed=button.value;
			}
		}
		
		if(path=='rnd'){
			rndnr = 4*Math.random();
			if(rndnr < 1)
				path='tl';
			else if(rndnr < 2)
				path='tr';
			else if(rndnr < 3)
				path='bl';
			else
				path='br';
		}
		
		switch(path){
			case 'tl':
				var animateleft = - width + winW;
				var animatetop = - height + winH;
				var left = 0;
				var top = 0;
			break;

			case 'tr':
				var animateleft = 0;
				var animatetop = - height + winH;
				var left = - width + winW;
				var top = 0;
			break;

			case 'bl':
				var animateleft = - width + winW;
				var animatetop = 0;
				var left = 0;
				var top = - height + winH;
			break;

			case 'br':
				var animateleft = 0;
				var animatetop = 0;
				var left = - width + winW;
				var top = - height + winH;
			break;
		}
		
		switch(picspeed){
			case 'slow':
				var localspeed = 1.3;
			break;
			case 'normal':
				var localspeed = 1.0;
			break;
			case 'fast':
				var localspeed = 0.7;
			break;
		}

		$(selectedpic).css("width",width);
		$(selectedpic).css("height",height);
		$(selectedpic).css("position","fixed");
		$(selectedpic).css("z-index","100");
		$(selectedpic).css("top",top);
		$(selectedpic).css("left",left);
		$(selectedpic).css("margin","0");
		$(selectedpic).children('img').css({'border-color': 'black','border-width':'2px'});
		
		if(longside=='width'){
			var time = 30000 * localspeed * zpglobalspeed * width/(winW*1.5 + height);
		} else {
			var time = 25000 * localspeed * zpglobalspeed * height/(winH*1.5 + width);
		}
		
		console.log((parseInt(time/100)/10) + 'sek');
		
	zoompan(animateleft,animatetop,time);

	
};

function zoompan(left,top,time){
	$(selectedpic).animate({
		opacity: 1,
	},{ duration: 1000, queue: false });
	
	$( selectedpic ).animate({
		top: top,
		left: left,
	  }, { duration: time, queue: false });
	
	$(selectedpic).delay(time-1500).animate({
		opacity:0,
	}, 1500, function() {
		$( selectedpic ).css("height",'');
		$( selectedpic ).css("opacity",'');
		toggle_fullscreen(selectedpic);
		selectedpic = $(selectedpic).next('div');
		if($(selectedpic).attr('class')=="bumper"){
			do{
			selectedpic = $(selectedpic).prev('div');
			}while($(selectedpic).attr('class')!='bumper');
			selectedpic = $(selectedpic).next('div');
		}
		toggle_fullscreen(selectedpic);
		$(selectedpic).css("opacity", 0);
		if(zoompanflag)
			zoompanvalues();
	} );
};

$(document).keydown(function(e) {
    switch(e.which) {
        case 37: // left
		toggle_fullscreen(selectedpic);
		selectedpic = $(selectedpic).prev('div');
		if($(selectedpic).attr('class')=="bumper")
			selectedpic = $(selectedpic).next('div');
		toggle_fullscreen(selectedpic);
		
        break;

        case 38: // up
        break;

        case 39: // right
        toggle_fullscreen(selectedpic);
		selectedpic = $(selectedpic).next('div');
		if($(selectedpic).attr('class')=="bumper")
			selectedpic = $(selectedpic).prev('div');
		toggle_fullscreen(selectedpic);
		
		break;

        case 40: // down
        break;

        default: return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
});

$(document).ready(function(){
  var $moveframe = $('#frame');
  
  
  $(document).mousemove(function(e){
	  var $movepic = $(selectedpic);
	  if(movepic){
		  var topdif = mouseY - e.pageY;
		  
		  var leftdif = mouseX - e.pageX;
		  mouseY = e.pageY;
		  mouseX = e.pageX;
		  var prevtop = parseInt($moveframe.css('top'));
		  var prevleft = parseInt($moveframe.css('left'));
		  $moveframe.css({'top': (prevtop - topdif),'left': (prevleft - leftdif)});
		  var prevtop = parseInt($movepic.css('top'));
		  var prevleft = parseInt($movepic.css('left'));
		  $movepic.css({'top': (prevtop - topdif),'left': (prevleft - leftdif)});

	  }
  });
});

