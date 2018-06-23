<!DOCTYPE html>
<html>
<head>
	<title>Image displayer</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>



<div id="buttoncontainer">

		<div id="lightswitch"><button type="button">Make light</button></div>
		<div id="frameswitch"><button type="button">Make Frame</button></div>
		<div id="framemaker">
			<button type="button">Select Picture</button>
			Height:
			<input type="number" name="height" value="600" min="1" max="3000"><br>
			Width:
			<input type="number" name="width" value="400" min="1" max="3000" disabled><br>
			Thickness:
			<input type="number" name="thickness" value="20" min="1" max="200"><br>
			Passepartout:
			<input type="number" name="passepartout" value="80" min="0" max="150"><br>
			2nd passepartout:
			<input type="number" name="2ndpassepartout" value="15" min="0" max="100"><br>
			Shadow:
			<input type="number" name="shadow" value="20" min="3" max="100"><br>
			Red:
			<input type="number" name="red" value="255" min="0" max="255"><br>
			Green:
			<input type="number" name="green" value="255" min="0" max="255"><br>
			Blue:
			<input type="number" name="blue" value="255" min="0" max="255"><br>
			<?php require('frame/makeselector.php'); ?>
			SaveTag:
			<input type="text" name="savetag" value="false"><br>
		</div>
		<div id="deleteswitch"><button type="button">Delete Frame</button></div>
		<div id="backgroundswitch"><button type="button">Backgrounds</button></div>

</div>

<div id="backgrounds">
	<?php
	$url = explode('\\',__FILE__);

	$path=$url[0];
	for($i=1;$i<count($url)-1;$i++){
		$path .= '\\'.$url[$i];
	}

	$dir    = $path.'\\backgrounds';
	$files = scandir($dir);
	
	for($x=0;$x < count($files);$x++){

		$components = explode('.',$files[$x]);
		if($components[count($components)-1] == 'jpg'){
			
			echo '
			<img class="backgroundpicture" src="backgrounds/'.$files[$x].'"></img>';
			
			
		}
	}
	
	?>
	
</div>

<div id="gallery">
	<?php
	$url = explode('\\',__FILE__);

	$path=$url[0];
	for($i=1;$i<count($url)-1;$i++){
		$path .= '\\'.$url[$i];
	}

	$dir    = $path.'\\img';
	$files = scandir($dir);
	
	for($x=0;$x < count($files);$x++){

		$components = explode('.',$files[$x]);
		if($components[count($components)-1] == 'jpg'){
			
			echo '
			<img class="gallerypicture" src="img/'.$files[$x].'"></img>';
			
			
		}
	}
	
	?>
	
</div>



<div id="workshop">
	<div id="frames"></div>
	<img id="maskplotter" src="light/maskplotter.png" />
	<div class="pic" id="mask"></div>
</div>


<div id="singleimagepopup"></div>
<div id="galleryimagepopup"></div>

<script src="js/jquery.js"></script>
<script src="js/createlight.js"></script>
<script src="js/createframe.js"></script>
<script src="js/div.js"></script>
<script src="js/masonry.js"></script>

</body>
</html>