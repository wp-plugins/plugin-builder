<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link              http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since             1.0.0
 * @package           Plugin_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Builder
 * Plugin URI:        http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * Description:       Build a plugin from the WordPress Plugin Boilerplate.
 * Version:           1.0.0
 * Author:            Chris Taylor
 * Author URI:        http://www.stillbreathing.co.uk/wordpress/plugin-builder/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-builder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-builder-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-builder-deactivator.php';

/** This action is documented in includes/class-plugin-builder-activator.php */
register_activation_hook( __FILE__, array( 'Plugin_Builder_Activator', 'activate' ) );

/** This action is documented in includes/class-plugin-builder-deactivator.php */
register_activation_hook( __FILE__, array( 'Plugin_Builder_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-builder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Plugin_Builder() {

	if ( ! defined( 'PLUGIN_BUILDER_DIR' ) ) {
		define( 'PLUGIN_BUILDER_DIR', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );
	}

	if ( ! defined( 'PLUGIN_BUILDER_URL' ) ) {
		define( 'PLUGIN_BUILDER_URL', plugin_dir_url( __FILE__ ) );
	}

	$plugin_builder = new Plugin_Builder();
	$plugin_builder->run();

}
run_Plugin_Builder();
