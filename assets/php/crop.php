<?php	
	include('../php/convert-location.php');
	$x1 = $_REQUEST['x1'];
	$y1 = $_REQUEST['y1'];
	$w = $_REQUEST['w'];
	$h = $_REQUEST['h'];
	$crop = $w.'x'.$h.$x1.$y1;
	$IMAGEDIR = "../temp/".$_REQUEST['design_id']."/";
	$im = 'CimageeditOriginal0'.$_REQUEST['ext'];
	$im2 = 'Cimageedit0'.$_REQUEST['ext'];
	$CMD = $CONVERT.' '.$IMAGEDIR.$im.' -crop '.$crop.' +repage '.$IMAGEDIR.$im2; //save thumbnail as png
	exec($CMD);
	$CMD = $CONVERT.' '.$IMAGEDIR.$im2.' '.$IMAGEDIR.$im;
	exec($CMD);
?>