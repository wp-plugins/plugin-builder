<?php

/**
 * Manages the pb-plugin Custom Post Types.
 * 
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * Manages the pb-plugin Custom Post Types.
 *
 * @since      1.0.0
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
class Plugin_Builder_Plugin_CPT_Manager {

	/**
	 * Gets all the plugins stored as a pb-plugin Custom Post Type.
	 *
	 * @since    1.0.0
	 */
	public function get_all() {

		$args = array (
			'post_type'              => 'pb-plugin',
			'post_status'            => 'publish',
			'pagination'             => false,
			'order'                  => 'DESC',
			'orderby'                => 'title',
			'cache_results'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
			'posts_per_page'         => -1
		);
		
		$query = new WP_Query( $args );
		
		if ( false == $query->have_posts() ) {
			return array();
		}
		
		return $query->posts;
	
	}
	
	/**
	 * Saves the given settings as a pb-plugin Custom Post.
	 *
	 * @since    1.0.0
	 */
	public function save_plugin_settings( $settings ) {
		
		$post = array(
				'post_content'   => $settings->to_html(),
				'post_name'      => $settings->plugin_slug,
				'post_title'     => $settings->plugin_name,
				'post_status'    => 'publish',
				'post_type'      => 'pb-plugin',
				'ping_status'    => 'closed',
				'comment_status' => 'closed'
			);
		
		$existing_settings = $this->get_plugin_settings( $settings->plugin_slug );
		if ( null != $existing_settings ) {
			$post['ID'] = $existing_settings->post_id;
		}
		
		$id = wp_insert_post( $post );
		
		if ( 0 == $id ) {
			return;
		}
		
		update_post_meta( $id, 'plugin_settings', $settings );
	}
	
	/**
	 * Gets the settings object for the specified plugin, or null if no plugin with the given name can be found.
	 *
	 * @since  1.0.0
	 */
	public function get_plugin_settings( $plugin ) {
	    
		$args = array(
			'name' => $plugin,
			'post_type' => 'pb-plugin',
			'post_status' => 'publish',
			'posts_per_page' => 1
		);
		$plugins = get_posts( $args );
		
		if ( 0 == count( $plugins ) ) {
			return null;
		}

		$settings =get_post_meta( $plugins[0]->ID, 'plugin_settings', true );
		$settings->post_id = $plugins[0]->ID;
		return $settings;
		
	}

}
