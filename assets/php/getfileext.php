<?php
switch ($filetype) {
	case 'image/jpeg':
	$fileext = '.jpg';
	break;
	case 'image/jpg':
	$fileext = '.jpg';
	break;
	case 'image/pjpeg':
	$fileext = '.jpg';
	case 'image/pjpg':
	$fileext = '.jpg';
	break;
	case 'image/gif':
	$fileext = '.gif';
	break;
	case 'image/png':
	$fileext = '.png';
	break;
	case 'image/x-png':
	$fileext = '.png';
	break;
	case 'image/bmp':
	$fileext = '.bmp';
	break;
	case 'image/x-bmp':
	$fileext = '.bmp';
	break;
	default:
	$fileext = '.jpg';
	break;
}
?>
