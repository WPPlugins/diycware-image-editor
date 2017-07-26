<?php
	class diycLoadIE {
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this,'diycie_load_js') );
			add_action('loop_start',array($this,'diycie_page_load_top'));
			add_action('loop_end',array($this,'diycie_page_load_bottom'));
			add_action('woocommerce_before_single_product',array($this,'diycie_load_before'));
			add_action('woocommerce_after_main_content',array($this,'diycie_load_after'));
		}
		public function init() {
			add_action('loop_start',array($this,'diycie_page_load_top'));
			add_action('loop_end',array($this,'diycie_page_load_bottom'));
			add_action('woocommerce_before_single_product',array($this,'diycie_load_before'));
			add_action('woocommerce_after_main_content',array($this,'diycie_load_after'));
		}
		public function diycie_page_load_top() {
			global $post;
			$enabled = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($enabled == 'true' && !is_shop() && $post->post_type != 'product') {
				include_once('diyc-header.php');
			}
		}
		public function diycie_page_load_bottom() {
			global $post;
			$enabled = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($enabled == 'true' && !is_shop() && $post->post_type != 'product') {
				include_once('diyc-tool.php');
			}
		}
		public function diycie_load_before() {
			global $post;
			$enabled = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($enabled == 'true' && !is_shop()) {
				include_once('diyc-header.php');
			}
		}
		public function diycie_load_after() {
			global $post;
			$enabled = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($enabled == 'true' && !is_shop()) {
				include_once('diyc-tool.php');
			}
		}
		public function diycie_load_js() {
			global $post;
			global $wpdb;
			$enabled = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($enabled == 'true' && !is_shop()) {
				if ($post->post_type == 'product') {
					$ID = $post->ID;
					$disable_atc = get_post_meta( $post->ID, '_diycie_disable_addtocart', true );
					$which_product = get_the_title($post->ID);
				}
				else {
					$which_product = get_post_meta( $post->ID, '_diycie_which_product', true );
					$prod = get_page_by_title($which_product,OBJECT,'product');
					$ID = $prod->ID;
				}
				//$nocache2 = "nocache".rand();
				$nocache2 = "v2.2";
				$upload_dir = wp_upload_dir();
				wp_enqueue_style('diyc-css_jq',plugins_url('assets/css/jq-ui-theme/jquery-ui-1.10.4.custom.min.css',DIYC_PLUGIN_FILE),false,$nocache2);
				wp_enqueue_style('diyc-css',plugins_url('assets/css/diyc.css',DIYC_PLUGIN_FILE),false,$nocache2);
				wp_enqueue_script('diyc_jscript_gui',plugins_url('assets/js/diycGUI.js',DIYC_PLUGIN_FILE),array('jquery','jquery-form','jquery-ui-core','jquery-ui-spinner','jquery-ui-tooltip','jquery-ui-accordion','jquery-ui-button','jquery-ui-dialog','jquery-ui-resizable','jquery-ui-accordion','jquery-ui-draggable','jquery-ui-menu','jquery-ui-progressbar'),$nocache2);
				$params = array(
					'srv'=>plugins_url(),
					'postID'=>$ID,
					'imageDir'=>$upload_dir['basedir'],
					'imageURL'=>$upload_dir['baseurl'],
					'ajaxURL' => admin_url( 'admin-ajax.php' ),
					'themeBootstrap'=>get_option('diycie_bootstrap_theme'),
					'siteURL'=>site_url(),
					'originalProduct'=>$which_product,
					'disableATC'=>$disable_atc);
				wp_localize_script( 'diyc_jscript_gui', 'diycIEParams', $params );
			}
			if (is_cart()) {
				//$nocache = "nocache=".rand();
				$nocache = "v2.2";
				wp_enqueue_script('diyc_jscript_cart',plugins_url('assets/js/diycCart.js',DIYC_PLUGIN_FILE),array('jquery'),$nocache);
				$params = array('srv'=>plugins_url(),'siteURL'=>site_url());
				wp_localize_script( 'diyc_jscript_cart', 'diycIEParams', $params );
			}
		}
	}
?>
