<?php
	class diycAdmin {
		public function __construct() {
			add_action( 'admin_menu', array( $this,'diycie_admin_load' ));
			add_action( 'load-post.php', array($this,'setup_meta_boxes') );
			add_action( 'load-post-new.php', array($this,'setup_meta_boxes') );
			add_action( 'woocommerce_product_options_general_product_data', array($this,'diycid_add_customfields') );
			add_action( 'woocommerce_process_product_meta', array($this,'diycie_update_product_meta') );
		}
		public function diycie_admin_load() {
			add_menu_page('DIYCWare IE', 'DIYCWare IE', 'manage_options', 'diycie-admin-page',array($this,'diycware_menu_page_callback'),9);
			
		}
		public function diycware_menu_page_callback() {
			global $wpdb, $post;
			if (isset($_POST['diyc_update_theme'])) {
				if (isset($_POST['diyc_cb_bootstrap_theme'])) {
					update_option('diycie_bootstrap_theme','true');
					$cb_status = 'checked="checked"';
				}
				else {
					update_option('diycie_bootstrap_theme','false');
					$cb_status = '';
				}
			}
			else {
				if (get_option('diycie_bootstrap_theme') == 'true') {
					$cb_status = 'checked="checked"';
				}
				else {
					$cb_status = '';
				}
			}
			echo '<h2>Settings</h2>';
			echo '<hr>';
			echo '<h2>Bootstrap Theme</h2>';
			echo '<p>If you are using a theme that is based on <i>bootstrap</i> (such as customizr), then you will need to enable this option.<br />';
			echo '<form name="diycie_enable_form" action="admin.php?page=diycie-admin-page" method="post" >';
			echo '<input type="checkbox" name="diyc_cb_bootstrap_theme" '.$cb_status.'  />';
			echo '<input type="hidden" id="diyc_update_theme" name="diyc_update_theme" value="yes" /><br />';
			echo '<input type="submit" value="Apply" /></form>';
			echo '<hr>';
		}
		public function setup_meta_boxes() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}
		public function add_meta_box( $post_type ) {
           $post_types = array('page');     
            if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'diycie_meta_box',
					'DIYCWare Image Editor',
					array( $this, 'render_meta_box_content' ),
					$post_type,
					'advanced',
					'high'
				);
            }
		}
		public function render_meta_box_content( $post ) {
			wp_nonce_field( 'diycie_inner_custom_box', 'diycie_inner_custom_box_nonce' );
			$cbvalue = get_post_meta( $post->ID, '_diycie_enabled', true );
			$tbvalue = get_post_meta( $post->ID, '_diycie_which_product', true );
			if ($cbvalue == 'true') {
				$cb_status = 'checked="checked"';
			}
			else {
				$cb_status = '';
			}
			echo '<label for="diycie_page_enable_cb">';
			echo 'Enable DIYCWare Image Editor for this Page?';
			echo '</label> ';
			echo '<input type="checkbox" id="diycie_page_enable_cb" name="diycie_page_enable_cb" '.$cb_status.'  /><br />';
			echo '<label for="diycie_page_enable_cb">';
			echo 'Which product to copy?';
			echo '</label> ';
			echo '<input type="text" id="diycie_which_product_tb" name="diycie_which_product_tb"';
            echo ' value="' . esc_attr( $tbvalue ) . '" size="25" />';
		}
		public function save( $post_id ) {
			if ( ! isset( $_POST['diycie_inner_custom_box_nonce'] ) ){
				return $post_id;
			}
			$nonce = $_POST['diycie_inner_custom_box_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'diycie_inner_custom_box' ) ){
				return $post_id;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
				return $post_id;
			}
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ){
					return $post_id;
				}
			} 
			else {
				if ( ! current_user_can( 'edit_post', $post_id ) ){
					return $post_id;
				}
			}
			$which_product = sanitize_text_field( $_POST['diycie_which_product_tb'] );
			update_post_meta( $post_id, '_diycie_which_product', $which_product );
			if (isset($_POST['diycie_page_enable_cb'])) {
				update_post_meta($post_id,'_diycie_enabled','true');
			}
			else {
				update_post_meta($post_id,'_diycie_enabled','false');
			}
		}
		public function diycid_add_customfields() {
			 global $woocommerce, $post;
			 $key_value = get_post_meta( $post->ID, '_diycie_enabled', true );
			if ($key_value == 'true') {
				$cb_status = get_post_meta( $post->ID, '_diycie_enabled', true );
			}
			else {
				$cb_status = '';
			}

			$key_value2 = get_post_meta( $post->ID, '_diycie_disable_addtocart', true );
			if ($key_value2 == 'true') {
				$cb_status2 = get_post_meta( $post->ID, '_diycie_disable_addtocart', true );
			}
			else {
				$cb_status2 = '';
			}
			echo '<div class="options_group">';
			woocommerce_wp_checkbox(
				array(
				'id' => '_diycie_enabled',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label' => __('Enable DIYCWare', 'woocommerce' ),
				'description' => __( 'Check here to enable DIYCWare Image Editor for this product.', 'woocommerce' ),
				'cbvalue'=>$cb_status,
				)
			);
			echo '<br />';
			woocommerce_wp_checkbox(
				array(
				'id' => '_diycie_disable_addtocart',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label' => __('Disable Add to Cart', 'woocommerce' ),
				'description' => __( 'Check here to disable the add-to-cart button for the blank product.', 'woocommerce' ),
				'cbvalue'=>$cb_status2,
				)
			);
			echo '</div>';
		}
		public function diycie_update_product_meta() {
			global $woocommerce, $post;
			$woocommerce_checkbox = isset( $_POST['_diycie_enabled'] ) ? 'true' : 'false';
			update_post_meta( $post->ID, '_diycie_enabled', $woocommerce_checkbox );
			$woocommerce_checkbox = isset( $_POST['_diycie_disable_addtocart'] ) ? 'true' : 'false';
			update_post_meta( $post->ID, '_diycie_disable_addtocart', $woocommerce_checkbox );
		}
	}
	new diycAdmin();
?>