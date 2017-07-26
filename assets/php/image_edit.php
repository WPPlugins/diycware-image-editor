<?php	
	include('../php/convert-location.php');
	$brightness = $_REQUEST['brightness'];
	$contrast = $_REQUEST['contrast']."%";
	$im_edit = '-modulate '.$brightness.' -level '.$contrast;
	$IMAGEDIR = "../temp/".$_REQUEST['design_id']."/";
	$im = 'CimageeditOriginal0'.$_REQUEST['ext'];
	$im2 = 'Cimageedit0'.$_REQUEST['ext'];
	$CMD = $CONVERT.' '.$IMAGEDIR.$im.' '.$im_edit.' '.$IMAGEDIR.$im2; //save thumbnail as png
	exec($CMD);
	echo ($IMAGEDIR.$im);
?>