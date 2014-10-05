<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WordPress_Settings_Framework
 *
 * @author Chris
 */
class WordPress_Settings_Framework_Include implements Plugin_Builder_Include {
	
	/**
	 * The method run when the user has selected this include.
	 *
	 * @since    1.0.0
	 * @var      Plugin_Builder_Settings    $settings    The settings for the plugin being built.
	 */
	public function process_include( $settings ) {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$cache_file = PLUGIN_BUILDER_DIR . 'cache/wp-settings-framework/wp-settings-framework.php';
		$plugin_file = $settings->build_path . 'includes/wp-settings-framework.php';
		
		$this->setup_plugin( $cache_file, $plugin_file, $settings );
		
		return $wp_filesystem->exists( $plugin_file );
	}
	
	/**
	 * Sets up the plugin being built by copying the needed files and creating the settings folder.
	 *
	 * @since    1.0.0
	 */
	private function setup_plugin( $cache_file, $plugin_file, $settings ) {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		// copy the cached file to the build folder
		$wp_filesystem->copy( $cache_file, $plugin_file );
		
		// create the settings folder
		$wp_filesystem->mkdir( $settings->build_path . 'settings' );
		
		// create an empty settings file
		$wp_filesystem->put_contents( $settings->build_path . 'settings/settings.php', '// enter your settings here, see https://github.com/gilbitron/WordPress-Settings-Framework for more information' );
		
	}
	
	/**
	 * Returns any code to be injected into the top of the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	public function get_dependencies_code() {
		
		return "

		/**
		 * WordPress Settings Framework
		 *
		 * The WordPress Settings Framework aims to take the pain out of creating settings pages for your WordPress plugins by effectively creating a wrapper around the WordPress settings API and making it super simple to create and maintain settings pages.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-settings-framework.php';
		
";
		
	}
	
	/**
	 * Returns the unique slug for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_slug(){
		return 'wp-settings-framework';
	}
	
	/**
	 * Returns the translated title for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_title() {
		return __( 'WordPress Settings Framework', 'plugin-builder' );
	}
	
	/**
	 * Returns the translated description (HTML allowed) for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_description() {
		return __( '<p>The WordPress Settings Framework aims to take the pain out of creating settings pages for your WordPress plugins by effectively creating a wrapper around the WordPress settings API and making it super simple to create and maintain settings pages.</p>', 'plugin-builder' );
	}
	
	/**
	 * Returns the URL giving more information on this include.
	 *
	 * @since    1.0.0
	 */
	public function get_info_url() {
		return 'https://github.com/gilbitron/WordPress-Settings-Framework';
	}
	
	/**
	 * Adds this instance as an include.
	 *
	 * @since    1.0.0
	 */
	public function add_include( $includes ) {
		$includes[] = $this;
		return $includes;
	}
	
}
$wordpress_settings_framework_include = new WordPress_Settings_Framework_Include();
add_filter( 'plugin_builder_includes', array( $wordpress_settings_framework_include, 'add_include' ) );
