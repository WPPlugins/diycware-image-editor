<?php	
	include('../php/convert-location.php');
	if ( $_FILES['diycFileImg']['name'] != "" ) {
		$ind = $_REQUEST['diycFileImgID2'];
		//$ind = 0;
		$im = $_FILES['diycFileImg']['tmp_name'];
		$myW = $_REQUEST['width'];
		$myH = $_REQUEST['height'];
		$filetype = $_FILES['diycFileImg']['type'];
		$newFile = 'Cimage';
		$newFileThumb = 'Cimagethumb'.$ind;
		$newFileEdit = 'Cimageedit'.$ind;
		$newFileEdit1 = 'Cimageedit'.$ind.'-0';
		$newFileEdit2 = 'CimageeditOriginal'.$ind;
		$newFileEdit3 = 'CimageeditBackup'.$ind;
		$IMAGEDIR = "../temp/".$_REQUEST['diycFileImgID1']."/";
		if ($filetype== "image/x-png" || $filetype== "image/png" || $filetype=="image/jpeg" || $filetype=="image/jpg" || $filetype=="image/pjpeg" || $filetype=="image/pjpg" || $filetype=="image/gif" || $filetype=="image/bmp" || $filetype=="image/x-bmp") {
			include('getfileext.php');
			$CMD = $IDENTIFY.' -format "%w" '.$im;
			$image_width = exec($CMD);
			$CMD = $IDENTIFY.' -format "%h" '.$im;
			$image_height = exec($CMD);
			$CMD = $IDENTIFY.' -format "%x" '.$im;
			$x_resolution = exec($CMD);
			$CMD = $IDENTIFY.' -format "%y" '.$im;
			$y_resolution = exec($CMD);
			$aspect = $image_width/$image_height;
			if ($aspect >= 1) {
				//if ($width > $myW) {
					$width = $myW;
					$height = intval($width/$aspect);
				//}
			}
			if ($aspect < 1) {
				//if ($height > $myH) {
					$height = $myH;
					$width = intval($height*$aspect);
				//}
			}
			$CMD = $CONVERT.' '.$im.' '.$IMAGEDIR.$newFile.'-'.$ind.$fileext; //save original file
			exec($CMD);
			$CMD = $CONVERT.' '.$im.' -thumbnail 50 '.$IMAGEDIR.$newFileThumb.$fileext; //save thumbnail as png
			exec($CMD);
			$CMD = $CONVERT.' '.$im.' -thumbnail '.$width.' '.$IMAGEDIR.$newFileEdit.$fileext; //save editable image as png
			exec($CMD);
			$CMD = $CONVERT.' '.$im.' -thumbnail '.$width.' '.$IMAGEDIR.$newFileEdit2.$fileext; //save editable image as png
			exec($CMD);
			$CMD = $CONVERT.' '.$im.' -thumbnail '.$width.' '.$IMAGEDIR.$newFileEdit3.$fileext; //save editable image as png
			exec($CMD);
		//	$CMD = $CONVERT.' '.$im.' -thumbnail '.$width.' '.$IMAGEDIR.$newFileEdit1.$fileext; //save editable image as png
		//	exec($CMD);
			//$im = file_get_contents($IMAGEDIR.$newFileThumb.'.png');
			//echo $im;
			//$img = '<img src="./images/'.$newFileThumb.'.png" id="Gimg'.$ind.'" name="Gimg'.$ind.'" />';
			//echo $_REQUEST['diycFileImgID1'];
			echo $fileext.':'.$image_width.':'.$image_height.':'.$x_resolution.':'.$y_resolution;
		}
		else {
			echo 'error:filetype';
		}
	}
	else {
		echo 'error:nofile';
	}
?>