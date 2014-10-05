<?php
/**
 * The class defining the methods for the Plugin Register include.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes/include-classes
 */

/**
 * The class defining the methods for the Plugin Register include.
 *
 * @since      1.0.0
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes/include-classes
 * @author     Chris Taylor
 */
class Plugin_Register_Include implements Plugin_Builder_Include {
	
	/**
	 * The method run when the user has selected this include.
	 *
	 * @since    1.0.0
	 * @var      Plugin_Builder_Settings    $settings    The settings for the plugin being built.
	 */
	public function process_include( $settings ) {
		
		return true;
		
	}
	
	/**
	 * Returns any code to be injected into the top of the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	public function get_dependencies_code() {
		
		return '';
		
	}
	
	/**
	 * Returns the unique slug for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_slug(){
		return 'plugin-register';
	}
	
	/**
	 * Returns the translated title for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_title() {
		return __( 'Plugin Register', 'plugin-builder' );
	}
	
	/**
	 * Returns the translated description (HTML allowed) for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_description() {
		return __( '<p>Keep a register of when and where your plugins are activated.</p>', 'plugin-builder' );
	}
	
	/**
	 * Returns the URL giving more information on this include.
	 *
	 * @since    1.0.0
	 */
	public function get_info_url() {
		return 'https://wordpress.org/plugins/plugin-register/';
	}
	
	/**
	 * The method that will be called when the add_includes filter runs.
	 *
	 * @since    1.0.0
	 */
	public function add_include( $includes ) {
		return $includes;
	}
	
}