<?php
	$newpic["name"] = $_POST["picname"];
	$newpic["set"] = $_POST["set"];
	$myfile = fopen("zpset.txt", "r") or die("Unable to open file!");
	$contents = fread($myfile,filesize("zpset.txt"));
	$contents = explode("\n", $contents);
	foreach($contents as $entry){
		$data[] = explode(",",$entry);
	}
	$match = false;
	for($i=0;$i<count($data);$i++){
		if($data[$i][0]==$newpic["name"]){
			if($newpic["set"]=='slow' || $newpic["set"]=='normal' || $newpic["set"]=='fast')
				$data[$i][2]=$newpic["set"];
			else
				$data[$i][1]=$newpic["set"];
			$match = true;
		}
	}
	if(!$match){
		$data[count($data)][0]=$newpic["name"];
		if($newpic["set"]=='slow' || $newpic["set"]=='normal' || $newpic["set"]=='fast')
			$data[count($data)-1][2]=$newpic["set"];
		else
			$data[count($data)-1][1]=$newpic["set"];
	}
	
	fclose($myfile);
	$myfile = fopen("zpset.txt", "w") or die("Unable to open file!");
	foreach($data as $entry){
		if($entry[0]!=''){
			$txt = $entry[0].','.$entry[1].','.$entry[2]."\n";
			fwrite($myfile,$txt);
		}
	}
	fclose($myfile);
	
?>