<?php

/**
 * Stores the settings for a Custom Post Type to be created in the new plugin
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * Stores the settings for a Custom Post Type to be created in the new plugin
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
class Plugin_Builder_CPT_Settings {
	
	var $name;
	var $slug;
	var $register_method;
	var $create_manager;
	var $path;
	var $manager_path;
	
	/**
	 * Gets the full class name for this Custom Post Type
	 *
	 * @since    1.0.0
	 */
	public function get_class_name( $settings ) {
		return $settings->plugin_package_name . '_' . camelify( $this->name ) . '_CPT';
	}

}
