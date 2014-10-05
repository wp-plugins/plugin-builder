<?php

/**
 * This interface which all include classes must implement.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * This interface which all include classes must implement.
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
interface  Plugin_Builder_Include {

	/**
	 * Called during the build process.
	 *
	 * @since    1.0.0
	 * @var      Plugin_Builder_Settings    $settings    The settings for the plugin being built.
	 */
	public function process_include( $settings );
	
	/**
	 * Returns any code to be injected into the top of the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	public function get_dependencies_code();
	
	/**
	 * Returns the unique slug for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_slug();
	
	/**
	 * Returns the translated title for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_title();
	
	/**
	 * Returns the translated description (HTML allowed) for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_description();
	
	/**
	 * Returns the URL giving more information on this include.
	 *
	 * @since    1.0.0
	 */
	public function get_info_url();
	
	/**
	 * The method that will be called when the add_includes filter runs.
	 *
	 * @since    1.0.0
	 */
	public function add_include( $includes );

}