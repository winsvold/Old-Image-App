<?php

//Make into dynamic script

$dimensions = [
			'frameheight' 			=> $_GET['height'],
			'framewidth' 			=> $_GET['width'],
			'framethickness'	 	=> $_GET['thickness'],
			'passepartout' 			=> $_GET['passepartout'],
			'scndpassepartout' 		=> $_GET['scndpassepartout'],
			'shadow' 				=> round($_GET['shadow']/2),
			'pshadow' 				=> round($_GET['shadow']/6),
			'globalshadow'			=> $_GET['shadow'],
			'picpath' 				=> $_GET['picpath'],
			'savetag' 				=> $_GET['savetag'],
			];
$papercolor = [
			'red' => $_GET['red'],
			'green' => $_GET['green'],
			'blue' => $_GET['blue'],
];

$shadowpath = $_GET['shadowpath'];
$globalshadowpath = $_GET['globalshadowpath'];
$passepartoutpath = $_GET['passepartoutpath'];	
$framepath = $_GET['framepath'];	


// localhost/createimg/frame/makeframe.php?height=500&width=800&thickness=20&passepartout=80&scndpassepartout=10&shadow=20&red=255&green=255&blue=255&shadowpath=frameshadow.png&passepartoutpath=passepartout/passepartout.png&framepath=frame/frame.png&globalshadowpath=globalshadow.png
// ?height=500&width=900&thickness=20&passepartout=80&shadow=20&red=255&green=255&blue=255&shadowpath=frameshadow.png&passepartoutpath=passepartout.png&framepath=frame.png&globalshadowpath=globalshadow.png
// frame/makeframe.php?height=600&width=507&thickness=20&passepartout=80&scndpassepartout=15&shadow=20&red=255&green=255&blue=255&shadowpath=frameshadow.png&passepartoutpath=passepartout/passepartout.png&framepath=frame/frame.png&globalshadowpath=globalshadow.png&savetag=false&picpath=http://localhost/createimg/img/001-130128-Italia-119.jpg
/*
//dimensions of picture and frame
$dimensions = [
			'frameheight' 			=> 500,
			'framewidth' 			=> 900,
			'framethickness'	 	=> 20,
			'passepartout' 			=> 80,
			'shadow' 				=> 10,
			'pshadow' 				=> 3,
			'globalshadow'			=> 20,
			];
//papercolor
$papercolor = [
			'red' => 255,
			'green' => 255,
			'blue' => 255,
];

$shadowpath = 'frameshadow.png';
$passepartoutpath = 'passepartout.png';	
$framepath = 'frame.png';	
$globalshadowpath = 'globalshadow.png';
*/



//MAKE FRAME
$draft = imagecreatetruecolor( $dimensions['framewidth'], $dimensions['frameheight'] ); //Create a black and white draft to use as base for mask. Black will become transparent and white opaque
imagesavealpha( $draft, true );
imagefill( $draft, 0, 0, imagecolorallocatealpha( $draft, $papercolor['red'], $papercolor['green'], $papercolor['blue'], 0 ) );

//PASSEPARTOUTSHADOW
$pshadow = imagecreatefrompng( $shadowpath );
$offset = $dimensions['framethickness'] + $dimensions['passepartout'];
//right shadow
$pshadowresized = resizeimg($pshadow, $dimensions['pshadow'] , $dimensions['frameheight'] - 2*$offset );

for( $x = 0; $x < $dimensions['pshadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['frameheight'] - 2* $offset; $y++ ) {
		$color = imagecolorat( $pshadowresized, $x, $y );
		//ifstatement to cut upper corner
		if($y >= $x) 					
			imagesetpixel( $draft, $dimensions['framewidth'] - $offset - $x, $offset + 1 + $y, $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}

//top shadow
$pshadowresized = resizeimg($pshadow, $dimensions['pshadow'] , $dimensions['framewidth'] -2*$offset +1);
for( $x = 0; $x < $dimensions['pshadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['framewidth'] + 1 - 2* $offset; $y++ ) {
		$color = imagecolorat( $pshadowresized, $x, $y );
		//ifstatement to cut upper corner
		if($y <= $dimensions['framewidth'] - 2* $offset - $x) 					
			imagesetpixel( $draft, $y + $offset, $x + $offset, $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}

imagedestroy($pshadow);
imagedestroy($pshadowresized);


//PASSEPARTOUT
if($dimensions['passepartout']){
	$passepartout = imagecreatefrompng( $passepartoutpath );
	$offset = $dimensions['framethickness'];
	//right and left passepartout
	for( $x = 0; $x < $dimensions['passepartout']; $x++ ) {
		for( $y = 0; $y < $dimensions['frameheight'] - 2* $offset; $y++ ) {
			$color = imagecolorat( $passepartout, $x, $y );
			if($y >= $x && $y <= ($dimensions['frameheight'] - $x - 2*$offset)){ 					//ifstatement to cut corners
				imagesetpixel( $draft, $x + $offset , $offset + $y, $color );	 							//left frame element
				imagesetpixel( $draft, $dimensions['framewidth'] - $x - $offset, $offset + $y, $color );	//right frame element
			}	}
	}

	//top and bottom passepartout
	for( $x = 0; $x < $dimensions['passepartout']; $x++ ) {
		for( $y = 0; $y < $dimensions['framewidth'] - 2* $offset; $y++ ) {
			$color = imagecolorat( $passepartout, $x, $y );
			if($y >= $x && $y <= ($dimensions['framewidth'] - $x - 2*$offset)){ 					//ifstatement to cut corners
				imagesetpixel( $draft, $offset + $y, $x + $offset , $color );	 							//left frame element
				imagesetpixel( $draft, $offset + $y, $dimensions['frameheight'] - $x - $offset, $color );	//right frame element
			}	}
	}
	imagedestroy($passepartout);
}


//FRAMESHADOW
$frameshadow = imagecreatefrompng( $shadowpath );
//right shadow
$frameshadowresized = resizeimg($frameshadow, $dimensions['shadow'] , $dimensions['frameheight']  );

for( $x = 0; $x < $dimensions['shadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['frameheight'] - 2* $dimensions['framethickness']; $y++ ) {
		$color = imagecolorat( $frameshadowresized, $x, $y );
		//ifstatement to cut upper corner
		if($y >= $x) 					
			imagesetpixel( $draft, $dimensions['framewidth'] - $dimensions['framethickness'] - $x, $dimensions['framethickness'] + 1 + $y, $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}

//top shadow
$frameshadowresized = resizeimg($frameshadow, $dimensions['shadow'] , $dimensions['framewidth'] );
for( $x = 0; $x < $dimensions['shadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['framewidth'] +1 - 2* $dimensions['framethickness']; $y++ ) {
		$color = imagecolorat( $frameshadowresized, $x, $y );
		//ifstatement to cut upper corner
		if($y <= $dimensions['framewidth'] - 2* $dimensions['framethickness'] - $x) 					
			imagesetpixel( $draft, $y + $dimensions['framethickness'], $x + $dimensions['framethickness'], $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}
imagedestroy($frameshadow);
imagedestroy($frameshadowresized);

//FRAME
$frameelement = imagecreatefrompng( $framepath );
//make left and right frame element
$frameelementresized = resizeimg($frameelement, $dimensions['framethickness'], $dimensions['frameheight']);
for( $x = 0; $x < $dimensions['framethickness']; $x++ ) {
	for( $y = 0; $y < $dimensions['frameheight']; $y++ ) {
		$color = imagecolorat( $frameelementresized, $x, $y );
		if($y >= $x && $y <= ($dimensions['frameheight'] - $x)){ 					//ifstatement to cut corners
			imagesetpixel( $draft, $x, $y, $color );	 							//left frame element
			imagesetpixel( $draft, $dimensions['framewidth'] - $x, $y, $color );	//right frame element
		}
	}
}

//make top and bottom frame element
$frameelementresized = resizeimg($frameelement, $dimensions['framethickness'], $dimensions['framewidth']);
for( $x = 0; $x < $dimensions['framethickness']; $x++ ) {
	for( $y = 0; $y < $dimensions['framewidth']; $y++ ) {
		$color = imagecolorat( $frameelementresized, $x, $y );
		if($y >= $x && $y <= ($dimensions['framewidth'] - $x)){ 					//ifstatement to cut corners
			imagesetpixel( $draft, $y, $x, $color );								//left frame element
			imagesetpixel( $draft, $y, $dimensions['frameheight'] - $x, $color );	//right frame element
		}
	}
}
imagedestroy($frameelement);
imagedestroy($frameelementresized);

//FRAME FINISHED

//ADD GLOBAL SHADOW
$framedpicture = imagecreatetruecolor( $dimensions['framewidth'] + 2 * $dimensions['globalshadow'], $dimensions['frameheight'] + 2*$dimensions['globalshadow'] ); //Create a black and white draft to use as base for mask. Black will become transparent and white opaque
imagesavealpha( $framedpicture, true );
imagefill( $framedpicture, 0, 0, imagecolorallocatealpha( $framedpicture, 0, 0, 0, 127 ) );
$globalshadow = imagecreatefrompng( $globalshadowpath );
//left shadow
$frameshadowresized = resizeimg($globalshadow, $dimensions['globalshadow'] , $dimensions['frameheight'] + $dimensions['globalshadow']  );
for( $x = 0; $x < $dimensions['globalshadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['frameheight'] + $dimensions['globalshadow']; $y++ ) {
		$color = imagecolorat( $frameshadowresized, $x, $y );
		//ifstatement to cut lower corner
		if($y <= $dimensions['frameheight'] + $dimensions['globalshadow'] - $x) 					
			imagesetpixel( $framedpicture, $x + round($dimensions['globalshadow'] * 0.4), $dimensions['globalshadow'] - 1 + $y, $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}
//bottom shadow
$frameshadowresized = resizeimg($globalshadow, $dimensions['globalshadow'] , $dimensions['framewidth']  );
for( $x = 0; $x < $dimensions['globalshadow']; $x++ ) {
	for( $y = 0; $y < $dimensions['framewidth']; $y++ ) {
		$color = imagecolorat( $frameshadowresized, $x, $y );
		//ifstatement to cut lower right corner
		if($y <= $dimensions['framewidth'] - $x) 					
			imagesetpixel( $framedpicture, round(0.4 * $dimensions['globalshadow']) + $dimensions['framewidth'] + 1 - $y, 2*$dimensions['globalshadow'] + $dimensions['frameheight'] - 1 - $x, $color );	 							//right frameshadowelement (had to add + 1 to y-cordinate, don't know why)
	}
}


//MERGE FRAME WITH SHADOW
imagecopymerge ( $framedpicture , $draft , $dimensions['globalshadow'] , $dimensions['globalshadow'] , 0 , 0 , $dimensions['framewidth'] , $dimensions['frameheight'] , 100 );





if(1){
	header('Content-type: image/png');
	imagepng($framedpicture);

}

//SAVE PIC AND FRAME
if($dimensions['savetag']!='false' && $dimensions['savetag']!=''){
	$combinedpicture = $framedpicture;	
	
	$picpath = str_replace(' ', '%20', $dimensions['picpath']);
	$picture = imagecreatefromjpeg( $picpath );
	$picwidth = $dimensions['framewidth'] - 2*($dimensions['framethickness'] + $dimensions['passepartout'] + $dimensions['scndpassepartout']);
	$picheight = $dimensions['frameheight'] - 2*($dimensions['framethickness'] + $dimensions['passepartout'] + $dimensions['scndpassepartout']);
	$picture = resizeimg($picture, $picwidth +1, $picheight +1 );
	
	$picoffset = $dimensions['globalshadow'] + $dimensions['framethickness'] + $dimensions['passepartout'] + $dimensions['scndpassepartout'];
	imagecopymerge ( $combinedpicture , $picture , $picoffset , $picoffset , 0 , 0 , $picwidth +1, $picheight +1, 100 );
	
	$filename = explode('/',$dimensions['picpath']);
	$filename = explode('.',$filename[count($filename)-1]) [0];
	$newpath = 'framedpics/' . $filename . '_' .$dimensions['savetag'] . '.png';
	if (!file_exists('framedpics/')) {
		mkdir('framedpics/', 0777, true);
	}

	imagepng($combinedpicture, $newpath);
}



function resizeimg( $orgpic, $newwidth, $newheight){
	$orgdim['width'] = imagesx ($orgpic);
	$orgdim['height'] = imagesy ($orgpic); 
	$resized = imagecreatetruecolor( $newwidth, $newheight );
	imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
    imagefilledrectangle($resized, 0, 0, $newwidth, $newheight, $transparent);
	
	imagecopyresampled ( $resized , $orgpic , 0 , 0 , 0 , 0 , $newwidth , $newheight , $orgdim['width'] , $orgdim['height'] );
	
	return $resized;
	imagedestroy($resized);
	imagedestroy($orgpic);
}

?>