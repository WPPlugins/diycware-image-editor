<?php
class diycDupProduct {
	public $newid;
	public $original_prod_name = 'test';
	public function __construct() {
		global $wpdb;
		$imgext = $_POST['ext'];
		$post = $this->diyc_get_product_to_duplicate( $_POST['id'] );
		$this->original_prod_name = $post->post_title;
		$this->newid = $this->diyc_duplicate_product($post);
		update_post_meta($this->newid, '_visibility', 'hidden');
		update_post_meta($this->newid, '_diycie_enabled', 'false');
		$thumbnail_id = $this->diyc_create_image($this->newid);
		update_post_meta($thumbnail_id,'_wp_attached_file','diycware-image-editor-saved/'.$_POST['design_id'].'/Cimageedit0'.$imgext);
		update_post_meta($this->newid,'_thumbnail_id',$thumbnail_id);
	}
	public function diyc_duplicate_product($post,$parent=0,$post_status='') {
		global $wpdb;
		$new_post_author 	= wp_get_current_user();
		$new_post_date 		= current_time('mysql');
		$new_post_date_gmt 	= get_gmt_from_date($new_post_date);

		if ( $parent > 0 ) {
			$post_parent		= $parent;
			$post_status 		= $post_status ? $post_status : 'publish';
			$suffix 			= '';
			$post_content    		= str_replace("'", "''", $post->post_content);
			$post_content_filtered 	= str_replace("'", "''", $post->post_content_filtered);
			//$post_title      		= str_replace($this->original_prod_name,$this->original_prod_name.' '.$_POST['design_id'], $post->post_title);
			$post_title      		= $this->original_prod_name.' '.$_POST['design_id'];
			$post_name       		= str_replace("'", "''", $post->post_name);
			$post_excerpt    		= str_replace("'", "''", $post->post_excerpt);
		} else {
			
			$post_parent		= $post->post_parent;
			$post_status 		= $post_status ? $post_status : 'publish';
			$suffix 			= ' ' . __( '(Copy)', 'woocommerce' );
			$post_content = '<h3>Image Adjustments</h3>';
			$num_adjustments = 0;
			if ($_POST['contrast'] != 0) {
				$post_content = $post_content.'Contrast at '.$_POST['contrast'].'.</br/>';
				$num_adjustments++;
			}
			if ($_POST['brightness'] != 100) {
				$post_content = $post_content.'Brightness at '.$_POST['brightness'].'.</br/>';
				$num_adjustments++;
			}
			if ($_POST['sepia'] != 'off') {
				$post_content = $post_content.'Sepia is on and set to '.$_POST['sepia'].'.</br/>';
				$num_adjustments++;
			}
			if ($_POST['crop'] != 'none') {
				$post_content = $post_content.'Image has been cropped.</br/>';
				$num_adjustments++;
			}
			if ($num_adjustments == 0){
				$post_content = $post_content.'No image adjustments.';
			}
			$post_content_filtered = $post_content;
			$post_title = $this->original_prod_name.' '.$_POST['design_id'];
			$post_name = strtolower($this->original_prod_name);
			$post_name = str_replace(' ','-',$post_name).'-'.$_POST['design_id'];
			$post_excerpt = 'Uploaded Image:'.$_POST['name'];
		}
		$new_post_type 			= $post->post_type;
		$comment_status  		= str_replace("'", "''", $post->comment_status);
		$ping_status     		= str_replace("'", "''", $post->ping_status);

		$wpdb->query(
				"INSERT INTO $wpdb->posts
				(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
				VALUES
				('$new_post_author->ID', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', '$post_status', '$new_post_type', '$comment_status', '$ping_status', '$post->post_password', '$post_name', '$post->to_ping', '$post->pinged', '$new_post_date', '$new_post_date_gmt', '$post_parent', '$post->menu_order', '$post->post_mime_type')");

		$new_post_id = $wpdb->insert_id;

		$this->diyc_duplicate_post_taxonomies( $post->ID, $new_post_id, $post->post_type );

		$this->diyc_duplicate_post_meta( $post->ID, $new_post_id );

		if ( $children_products =& get_children( 'post_parent='.$post->ID.'&post_type=product_variation' ) ) {

			if ( $children_products )
				foreach ( $children_products as $child )
					$this->diyc_duplicate_product( $this->diyc_get_product_to_duplicate( $child->ID ), $new_post_id, $child->post_status );
		}

		return $new_post_id;
	}
	public function diyc_get_product_to_duplicate($id) {
			global $wpdb;

			$id = absint( $id );
			if ( ! $id ) {
				return false;
			}
			$post = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE ID=$id" );

			if ( isset( $post->post_type ) && $post->post_type == "revision" ) {
				$id   = $post->post_parent;
				$post = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE ID=$id" );
			}
			return $post[0];
	}
	public function diyc_duplicate_post_taxonomies( $id, $new_id, $post_type ) {
		global $wpdb;
		$taxonomies = get_object_taxonomies($post_type); //array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($id, $taxonomy);
			$post_terms_count = sizeof( $post_terms );
			for ($i=0; $i<$post_terms_count; $i++) {
				wp_set_object_terms($new_id, $post_terms[$i]->slug, $taxonomy, true);
			}
		}
	}
	public function diyc_duplicate_post_meta( $id, $new_id ) {
		global $wpdb;
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$id");

		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
	}
	public function diyc_create_image($parent) {
		global $wpdb;
		$new_post_author = wp_get_current_user();
		$new_post_date = current_time('mysql');
		$new_post_date_gmt = get_gmt_from_date($new_post_date);
		$parent	= $parent;
		$status = 'inherit';
		$title = $_POST['design_id'].'-image';
		$name = $_POST['design_id'].'-image';
		$post_type = 'attachment';
		$comment_status = 'open';
		$ping_status = 'open';
		$mime_type = 'image/'.str_replace('.','',$imgext);

		$wpdb->query(
				"INSERT INTO $wpdb->posts
				(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
				VALUES
				('$new_post_author->ID', '$new_post_date', '$new_post_date_gmt', '$content', '$content_filtered', '$title', '$excerpt', '$status', '$post_type', '$comment_status', '$ping_status', '$post_password', '$name', '$to_ping', '$pinged', '$new_post_date', '$new_post_date_gmt', '$parent', '$menu_order', '$mime_type')");

		return $wpdb->insert_id;
	}
}
?>