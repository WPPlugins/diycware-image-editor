<?php	
	include('../php/convert-location.php');
	$IMAGEDIR = "../temp/".$_REQUEST['design_id']."/";
	$im = 'CimageeditBackup0'.$_REQUEST['ext'];
	$im2 = 'Cimageedit0'.$_REQUEST['ext'];
	$im3 = 'CimageeditOriginal0'.$_REQUEST['ext'];
	$CMD = $CONVERT.' '.$IMAGEDIR.$im.' '.$IMAGEDIR.$im2;
	exec($CMD);
	$CMD = $CONVERT.' '.$IMAGEDIR.$im.' '.$IMAGEDIR.$im3;
	exec($CMD);
?>