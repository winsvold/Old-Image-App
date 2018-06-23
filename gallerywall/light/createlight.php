<?php

// Load mask
$masktemplate = imagecreatefrompng( 'mask.png' );

// Get cordinates from GET request
$get = explode('|' , $_GET["lights"]);
$windim['width'] = explode(',' , $get[0])[0];
$windim['height'] = explode(',' , $get[0])[1];
$tempcordinates = explode(',' , $get[1]);

// Put cordinates into array
$cordinates = array();
for($x = 0; $x < count($tempcordinates)/4 ; $x++ ){
	$temp = [
		"left" => $tempcordinates[$x*4],
		"top" => $tempcordinates[$x*4 + 1],
		"width" => $tempcordinates[$x*4 + 2],
		"height" => $tempcordinates[$x*4 + 3],
	];
	array_push($cordinates, $temp);
}

$basealpha = 30; // General opacity, 0 -127
$resolution = 0.05; // 1 = full resolution 0 = no resolution (a reduction from 1 to 0.5 cuts processingtime by a factor of 3-4)
$mask = imagealphamask( $windim, $masktemplate, $cordinates, $basealpha, $resolution );

// Output
if(1){
	header( "Content-type: image/png");
	imagepng( $mask );
}



function imagealphamask( $windim, $mask, $cordinates, $basealpha, $resolution ) {
	// Get sizes and set up new picture
	$xSize = round( $windim['width']  * $resolution);
	$ySize = round( $windim['height']  * $resolution);
	for($x=0; $x < count($cordinates); $x++){
		$cordinates[$x]['left'] = round($cordinates[$x]['left'] * $resolution);
		$cordinates[$x]['top'] = round($cordinates[$x]['top'] * $resolution);
		$cordinates[$x]['width'] = round($cordinates[$x]['width'] * $resolution);
		$cordinates[$x]['height'] = round($cordinates[$x]['height'] * $resolution);	
	}
	
	$draft = imagecreatetruecolor( $xSize, $ySize ); //Create a black and white draft to use as base for mask. Black will become transparent and white opaque
	imagesavealpha( $draft, true );
	imagefill( $draft, 0, 0, imagecolorallocatealpha( $draft, 255, 255, 255, 0 ) );
	
	$maskcount = count($cordinates);
	
	for($u = 0; $u < $maskcount; $u++ ){
		// Resize mask
		$maskwidth = $cordinates[$u]['width'];
		$maskheight = $cordinates[$u]['height'];
		$maskposx = $cordinates[$u]['left'] - $maskwidth/2;
		$maskposy = $cordinates[$u]['top'] - $maskheight/2;
				
		$tempmask = imagecreatetruecolor( $maskwidth, $maskheight );
		imagecopyresampled( $tempmask, $mask, 0, 0, 0, 0, $maskwidth, $maskheight, imagesx( $mask ), imagesy( $mask ) );
		
		// Perform pixel-based alpha map application
		for( $x = 0; $x < $maskwidth; $x++ ) {
			for( $y = 0; $y < $maskheight; $y++ ) {
				$color = imagecolorsforindex( $tempmask, imagecolorat( $tempmask, $x, $y ) );
				
				//Calculate aplha value where black is transparent and white is opaque.
				$blacklevel = (($color['red']+$color['blue']+$color['green']) / 3 );
				
				//Allocate alpha values
				if($x + $maskposx < $xSize && $x + $maskposx >= 0 && $y + $maskposy < $ySize && $y + $maskposy >= 0){ //Check that pixel is not outside picture
					$draftcolor = imagecolorsforindex( $draft, imagecolorat( $draft, $x + $maskposx, $y + $maskposy ) );
					$draftblacklevel = (($draftcolor['red']+$draftcolor['blue']+$draftcolor['green']) / 3 );
					$blacklevel = 255 - sqrt(pow(255 - $draftblacklevel, 2) + pow(255 - $blacklevel,2) ); //use squareroot to smooth transitions between lights
					
					if ($blacklevel > 255)
						$blacklevel = 255;
					else if ($blacklevel < 0)
						$blacklevel = 0;
					imagesetpixel( $draft, $x + $maskposx, $y + $maskposy, imagecolorallocatealpha( $draft, $blacklevel, $blacklevel, $blacklevel, 0 ) );
				}
			}
		}
	}

	//Create full size alpha mask
	$finalmask = imagecreatetruecolor( $xSize, $ySize ); 
	imagesavealpha( $finalmask, true );
	imagefill( $finalmask, 0, 0, imagecolorallocatealpha( $finalmask, 0, 0, 0, 127 ) );
	// Perform pixel-based alpha map application
	for( $x = 0; $x < $xSize; $x++ ) {
		for( $y = 0; $y < $ySize; $y++ ) {
			$color = imagecolorsforindex( $draft, imagecolorat( $draft, $x, $y ) );
			
			//Calculate aplha value where black is transparent and white is opaque. //Incorporate $basealpha here: 
			$alpha = 127.5 - (($color['red']+$color['blue']+$color['green']) / 6)*((127.5-$basealpha)/127.5) ;
			
			//Allocate alpha values
			imagesetpixel( $finalmask, $x, $y, imagecolorallocatealpha( $finalmask, 0, 0, 0, $alpha ) );
		}
	}
	

	// Copy back to original picture
	return $finalmask;
	imagedestroy($newPicture);
	imagedestroy($mask);
	imagedestroy($draft);
	imagedestroy($finalmask);
}

?>