<?php
/**
 * @package DIYCWare Image Editor
 * @version 2.2
 * @author Orzel Enterprises Inc.
 * @license http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link http://www.diycware.com/diyc-image-editor
 */
/*
Plugin Name: DIYCWare Image Editor
Plugin URI: http://www.diycware.com/diyc-image-editor
Description: Image Editor tool for your Woocommerce store. 
Author: Orzel Enterprises Inc.
Version: 2.2
Author URI: http://www.diycware.com
*/

/* Activation */


function diyc_create_image_dir() {
	$upload_dir = wp_upload_dir();
	if (!file_exists($upload_dir['basedir'].'/diycware-image-editor-saved')) {
		mkdir($upload_dir['basedir'].'/diycware-image-editor-saved');
	}
	update_option('diycie_bootstrap_theme','false');
}
register_activation_hook( __FILE__, 'diyc_create_image_dir' );

/* Constants */

define( 'DIYC_PLUGIN_FILE', __FILE__ );

/* If Admin Page */

if (is_admin()) {
	if (file_exists(dirname(__FILE__).'includes/class-diycie-admin-pro.php')) {
		include_once('includes/class-diycie-admin-pro.php');
	}
	else {
		include_once('includes/class-diycie-admin.php');
	}
}

/* Else Product Pages */

else {
	if (file_exists(dirname(__FILE__).'includes/class-diycie-load-editor-pro.php')) {
		include_once('includes/class-diycie-load-editor-pro.php');
	}
	else {
		include_once('includes/class-diycie-load-editor.php');
	}
	new diycLoadIE();
	
}
include_once('includes/class-diycie-dup-product.php');
add_action( 'wp_ajax_diycie_action_callback', 'diycie_action_callback' );
add_action( 'wp_ajax_nopriv_diycie_action_callback', 'diycie_action_callback' );
function diycie_action_callback() {
	check_ajax_referer( 'create-product-nonce', 'nonce' );
	$diyc_dir = dirname(__FILE__);
	require(dirname(__FILE__).'/assets/php/saveimage.php');
	$newproduct = new diycDupProduct();
	$returnstring = $newproduct->original_prod_name;
	echo $returnstring;
	die();
}
//}
/*END OF FILE*/
?>