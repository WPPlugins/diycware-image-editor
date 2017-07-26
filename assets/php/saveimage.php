<?php
	include($diyc_dir.'/assets/php/convert-location.php');
	$design_id = $_POST['design_id'];
	$brightness = $_REQUEST['brightness'];
	$contrast = $_REQUEST['contrast'];
	$sepia = $_REQUEST['sepia'];
	$crop = $_REQUEST['crop'];
	$fileext = $_REQUEST['ext'];
	$srcDir = $diyc_dir.'/assets/temp/'.$design_id;
	mkdir($diyc_dir.'/assets/saveddesigns/'.$design_id); //saveddesigns in plugin, a temp location
	$wpdir = $_REQUEST['dir']; 
	mkdir($wpdir.'/'.$design_id); //diyc-image-editor-save location, final saved location
	$destDir = $diyc_dir.'/assets/saveddesigns/'.$design_id;
	if ($handle = opendir($srcDir)) {
		while (false !== ($file = readdir($handle))) {
	    	if (is_file($srcDir . '/' . $file)) {
	        	rename($srcDir . '/' . $file, $destDir . '/' . $file);
	    	}
	    }
	    closedir($handle);
	}
	rmdir($srcDir);
	$IMAGEDIR = $diyc_dir.'/assets/saveddesigns/'.$design_id.'/';
	$CMD = $CONVERT.' '.$IMAGEDIR.'Cimageedit0'.$fileext.' -thumbnail 100 '.$IMAGEDIR.$design_id.'-cart-thumb'.$fileext; //save thumbnail for the cart
	exec($CMD);
	if ($crop != 'none') {
		$CMD = $IDENTIFY.' -format "%w" ../saveddesigns/'.$design_id.'/Cimage-0'.$fileext;
		$w_original = exec($CMD);
		$CMD = $IDENTIFY.' -format "%w" ../saveddesigns/'.$design_id.'/CimageeditBackup0'.$fileext;
		$w_new = exec($CMD);
		$scaleW = $w_original/$w_new;
		$CMD = $IDENTIFY.' -format "%h" ../saveddesigns/'.$design_id.'/Cimage-0'.$fileext;
		$h_original = exec($CMD);
		$CMD = $IDENTIFY.' -format "%h" ../saveddesigns/'.$design_id.'/CimageeditBackup0'.$fileext;
		$h_new = exec($CMD);
		$scaleH = $h_original/$h_new;
		$crop = $crop.':'.$scaleW.':'.$scaleH;
	}
	$xml = '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
	$xml = $xml.'<design name="'.$_REQUEST['name'].'">'."\n".'<brightness>'.$brightness.'</brightness>'."\n";
	$xml = $xml.'<contrast>'.$contrast.'</contrast>'."\n";
	$xml = $xml.'<sepia>'.$sepia.'</sepia>'."\n";
	$xml = $xml.'<crop>'.$crop.'</crop>'."\n";
	$xml = $xml.'</design>'."\n";
	file_put_contents($diyc_dir.'/assets/saveddesigns/'.$design_id.'/'.$design_id.'.xml', $xml);
	/*create final saved image location */
	$srcDir = $destDir;
	$destDir = $wpdir.'/'.$design_id;
	if ($handle = opendir($srcDir)) {
		while (false !== ($file = readdir($handle))) {
	    	if (is_file($srcDir . '/' . $file)) {
	        	copy($srcDir . '/' . $file, $destDir . '/' . $file);
	    	}
	    }
	    closedir($handle);
	}
?>

