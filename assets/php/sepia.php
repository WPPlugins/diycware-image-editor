<?php	
	include('../php/convert-location.php');
	$sepia = $_REQUEST['sepia']."%";
	$IMAGEDIR = "../temp/".$_REQUEST['design_id']."/";
	$im = 'CimageeditOriginal0'.$_REQUEST['ext'];
	$im2 = 'Cimageedit0'.$_REQUEST['ext'];
	if ($_REQUEST['sepia'] == 'off') {
		$CMD = $CONVERT.' '.$IMAGEDIR.$im.' '.$IMAGEDIR.$im2; //save thumbnail as png
		exec($CMD);
	}
	else {
		$CMD = $CONVERT.' '.$IMAGEDIR.$im.' -sepia-tone '.$sepia.' '.$IMAGEDIR.$im2; //save thumbnail as png
		exec($CMD);
	}
	echo ($IMAGEDIR.$im);
?>