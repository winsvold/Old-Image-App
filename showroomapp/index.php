<!DOCTYPE html>
<html>
<head>
<title>Test</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>

<div id="buttoncontainer">

		<div id="frameswitch"><button type="button">No Frame</button></div>
		<div id="textureswitch"><button type="button">Texture</button></div>
		<div id="sizeswitch"><button type="button">Size 100%</button></div>
		<div id="vignettingswitch"><button type="button">Vignetting off</button></div>
		<div id="zoompan"><button type="button">Zoompan</button></div>
		<div id="zppos"><button type="button">ZPsettings</button></div>
		<div id="zpspeed"><button type="button">ZPspeed 100%</button></div>

</div>

<div class="background"></div>
<div id="vignetting"><img src="vignetting.png"></div>

<div class="container">

	<div id="frame"><img src=""></div>

	<div class="bumper"></div>
	<?php //Find all pictures and display them in code

	$url = explode('\\',__FILE__);

	$path=$url[0];
	for($i=1;$i<count($url)-1;$i++){
		$path .= '\\'.$url[$i];
	}

	$dir    = $path.'\\img';
	$files = scandir($dir);

	//Load zoompan settings for images
	$myfile = fopen("zpset.txt", "r") or die("Unable to open file!");
	$contents = fread($myfile,filesize("zpset.txt"));
	fclose($myfile);
	$contents = explode("\n", $contents);
	foreach($contents as $entry){
		$zpdata[] = explode(",",$entry);
	}
	
	
	for($i=0;$i<count($files);$i++){
		$file = $files[$i];
		$components = explode('.',$file);
		if($components[count($components)-1] =='jpg'){
			$components = explode('\\',$file);
			
			
			$match = false;
			$checked = array_fill (0,8,'');
			foreach($zpdata as $entry){
				if($entry[0]==$components[count($components)-1]){
					$match = true;
					if($entry[1]=='tl')
						$checked[0] = 'checked';
					if($entry[1]=='tr')
						$checked[1] = 'checked';
					if($entry[1]=='bl')
						$checked[2] = 'checked';
					if($entry[1]=='br')
						$checked[3] = 'checked';
					if($entry[1]=='rnd')
						$checked[4] = 'checked';
					if($entry[2]=='fast')
						$checked[5] = 'checked';
					if($entry[2]=='normal')
						$checked[6] = 'checked';
					if($entry[2]=='slow')
						$checked[7] = 'checked';
				}
					
			}
			if(!($checked[0] || $checked[1] || $checked[2] || $checked[3] || $checked[4])){
				$checked[4] = "checked";
			}
			if(!($checked[5] || $checked[6] || $checked[7])){
				$checked[6] = "checked";
			}
			
			$file = 'img\\'.$components[count($components)-1];
			
						echo '
			<div class="image">
				<img src="'.$file.'"><br>
				<div class="zpposform" id="zppos '.($i-1).'">
					<form>
						<input class="zpset" type="radio" name="startpos'.($i-1).'" value="tl" '.$checked[0].'> TL
						<input class="zpset" type="radio" name="startpos'.($i-1).'" value="tr" '.$checked[1].'> TR<br>
						<input class="zpset" type="radio" name="startpos'.($i-1).'" value="bl" '.$checked[2].'> BL
						<input class="zpset" type="radio" name="startpos'.($i-1).'" value="br" '.$checked[3].'> BR
						<input class="zpset" type="radio" name="startpos'.($i-1).'" value="rnd" '.$checked[4].'> AUTO <hr>
						<input class="zpset" type="radio" name="speed'.($i-1).'" value="fast" '.$checked[5].'> FST
						<input class="zpset" type="radio" name="speed'.($i-1).'" value="normal" '.$checked[6].'> NRM 
						<input class="zpset" type="radio" name="speed'.($i-1).'" value="slow" '.$checked[7].'> SLW
					</form>
				</div>
			</div>'."\n\n";
		}
	}

	?>
	<div class="bumper"></div>

	
</div>

<div id=textures>
	<?php //A div that lists the available textures

	$dir    = $path.'\\textures';
	$files = scandir($dir);

	for($i=0;$i<count($files);$i++){
		$file = $files[$i];
		$components = explode('.',$file);
		if($components[count($components)-1] =='jpg'){
			$components = explode('\\',$file);

			$file = 'textures\\'.$components[count($components)-1];
			echo '<div class="textures" id="texture'.$i.'"><img src="'.$file.'"></div>'."\n\n";
		}
	}

	?>
</div>

<script src="js/jquery.js"></script>
<script src="js/masonry.js"></script>
<script src="js/myscript.js"></script>
</body>

</html>