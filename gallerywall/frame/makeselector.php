<?php
	
	$url = explode('\\',__FILE__);

	$path=$url[0];
	for($i=1;$i<count($url)-1;$i++){
		$path .= '\\'.$url[$i];
	}
	
	$dir['frames']    = $path.'\\frame';
	$frames = scandir($dir['frames']);
	
	$dir['passepartouts']    = $path.'\\passepartout';
	$passepartouts = scandir($dir['passepartouts']);
	array_shift($frames);
	array_shift($frames);
	array_shift($passepartouts);
	array_shift($passepartouts);
	
	echo '	Frame:<br> <select name="framechoiche" id="framechoice">';
	for($x=0;$x < count($frames);$x++)
			echo '<option value="frame/'.$frames[$x].'">'.explode('.',$frames[$x])[0].'</option>';
	echo	'</select><br>';
	
	echo "\n".'	Passepartout:<br> <select name="passepartoutchoiche" id="passepartoutchoiche">';
	for($x=0;$x < count($frames);$x++)
			echo '<option value="passepartout/'.$passepartouts[$x].'">'.explode('.',$passepartouts[$x])[0].'</option>';
	echo	'</select><br>';
	
	
?>