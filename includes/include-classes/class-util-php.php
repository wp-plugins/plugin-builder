<?php
/**
 * The class defining the methods for the util.php include.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes/include-classes
 */

/**
 * The class defining the methods for the util.php include.
 *
 * @since      1.0.0
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes/include-classes
 * @author     Chris Taylor
 */
class Util_Php_Include implements Plugin_Builder_Include {
	
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
		
		$cache_file = PLUGIN_BUILDER_DIR . 'cache/util-php/util.php';
		$plugin_file = $settings->build_path . 'includes/util.php';
		
		$wp_filesystem->copy( $cache_file, $plugin_file );
		
		return $wp_filesystem->exists( $plugin_file );
	}
	
	/**
	 * Returns any code to be injected into the top of the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	public function get_dependencies_code() {
		
		return "

		/**
		 * utils.php
		 *
		 * util.php is a collection of useful functions and snippets that you need or could use every day, designed to avoid conflicts with existing projects.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/util.php';
		
";
		
	}
	
	/**
	 * Returns the unique slug for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_slug(){
		return 'util.php';
	}
	
	/**
	 * Returns the translated title for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_title() {
		return __( 'util.php', 'plugin-builder' );
	}
	
	/**
	 * Returns the translated description (HTML allowed) for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_description() {
		return __( '<p>util.php is a collection of useful functions and snippets that you need or could use every day, designed to avoid conflicts with existing projects.</p>', 'plugin-builder' );
	}
	
	/**
	 * Returns the URL giving more information on this include.
	 *
	 * @since    1.0.0
	 */
	public function get_info_url() {
		return 'http://brandonwamboldt.github.io/utilphp/';
	}
	
	/**
	 * Adds this instance as an include.
	 *
	 * @since    1.0.0
	 * @var      array    $includes    The array of includes.
	 */
	public function add_include( $includes ) {
		$includes[] = $this;
		return $includes;
	}
	
}
$util_php_include = new Util_Php_Include();
add_filter( 'plugin_builder_includes', array( $util_php_include, 'add_include' ) );