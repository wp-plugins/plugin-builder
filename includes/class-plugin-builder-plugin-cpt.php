<?php

/**
 * The pb-plugin Custom Post Type, which stores to settings for built plugins in the database.
 * 
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * The pb-plugin Custom Post Type, which stores to settings for built plugins in the database.
 *
 * @since      1.0.0
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
class Plugin_Builder_Plugin_CPT {

	/**
	 * Register the VoucherPress Template Custom Post Type with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function register() {

		$labels = array(
			'name'                => _x( 'Plugins', 'Post Type General Name', 'plugin-builder' ),
			'singular_name'       => _x( 'Plugin', 'Post Type Singular Name', 'plugin-builder' ),
			'menu_name'           => __( 'Plugin', 'plugin-builder' ),
			'parent_item_colon'   => __( 'Parent Item:', 'plugin-builder' ),
			'all_items'           => __( 'Plugins', 'plugin-builder' ),
			'view_item'           => __( 'View Item', 'plugin-builder' ),
			'add_new_item'        => __( 'Add New Plugin', 'plugin-builder' ),
			'add_new'             => __( 'Add New', 'plugin-builder' ),
			'edit_item'           => __( 'Edit Item', 'plugin-builder' ),
			'update_item'         => __( 'Update Item', 'plugin-builder' ),
			'search_items'        => __( 'Search Plugins', 'plugin-builder' ),
			'not_found'           => __( 'Not found', 'plugin-builder' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'plugin-builder' ),
		);
		$args = array(
			'label'               => __( 'pb-plugin', 'plugin-builder' ),
			'description'         => __( 'Plugin', 'plugin-builder' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 20,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'pb-plugin', $args );
	
	}

}
