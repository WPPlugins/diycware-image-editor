<?php
	$shopcode = $_REQUEST['sc'];
	$newtime = time();
	settype($newtime,"string");
	$randnum = mt_rand(10000,99999);
	settype($randnum,"string");
	$design_id =$shopcode.$newtime.$randnum;
	mkdir('../temp/'.$design_id);
	echo $design_id;
?>