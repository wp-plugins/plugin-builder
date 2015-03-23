<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin
 * @author     Chris Taylor
 */
class Plugin_Builder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The tabs to show on the admin page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $tabs    The tabs to show.
	 */
	private $tabs = array();
	
	/**
	 * The includes to show on the includes page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $includes    The includes to show.
	 */
	private $includes = array();
	
	/**
	 * The settings for a plugin submitted from the form.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Plugin_Builder_Settings    $submitted_settings    The submitted settings.
	 */
	private $submitted_settings;
	
	/**
	 * A variable indicating if a plugin has been built correctly.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool    $plugin_built    Whether a plugin has been built correctly.
	 */
	private $plugin_built = false;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;
		$this->setup_tabs();
		
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->name, PLUGIN_BUILDER_URL . 'admin/css/plugin-builder-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->name, PLUGIN_BUILDER_URL . 'admin/js/plugin-builder-admin.js', array( 'jquery' ), $this->version, FALSE );

	}
	
	/**
	 * Adds pages to the admin menu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
	
		add_submenu_page(
			'plugins.php', 
			__( 'Builder', 'plugin_builder' ), 
			__( 'Builder', 'plugin_builder' ), 
			'edit_posts', 
			'plugin-builder', 
			array( $this, 'render_page' ) 
		);
	
	}
	
	/**
	 * Handles form submissions.
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		
		if ( ! isset( $_POST['plugin_builder_nonce'] ) || ! wp_verify_nonce( $_POST['plugin_builder_nonce'], 'plugin_builder' ) ) {
			return;
	    }
		
		$this->submitted_settings = new Plugin_Builder_Settings();
		$this->submitted_settings->set_from_form( $_POST );
		
		if ( isset( $_POST['build'] ) && $this->submitted_settings->is_valid() ) {
			
			$manager = new Plugin_Builder_Plugin_CPT_Manager();
			$manager->save_plugin_settings( $this->submitted_settings );
			
			$builder = new Plugin_Builder_Processor( $this, $this->submitted_settings );
			$this->plugin_built = $builder->build();
			
		}
		
	}
	
	/**
	 * Renders any messages
	 *
	 * @since    1.0.0
	 */
	public function render_messages() {
		
		if ( $this->plugin_built ) {
			echo '<div class="updated">';
			echo '<p>' . __( 'Your plugin has been built. <a href="plugins.php">Go to the plugins screen to activate it</a>, or you can make some changes below and rebuild.', 'plugin-builder' ) . '</p>';
			echo '</div>';
			return;
		}
		
		if ( ! isset( $this->submitted_settings ) ) {
			return;
		}
		
		if ( $this->submitted_settings->is_valid() ) {
			return;
		}
		
		echo '<div class="error">';
		echo '<ul>';
		foreach( $this->submitted_settings->errors as $error ) {
			echo "<li>$error</li>";
		}
		echo '</ul>';
		echo '</div>';
		
	}
	
	/**
	 * Renders the list of plugin settings saved as pb-plugin Custom Post Types.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_list() {
		
		$manager = new Plugin_Builder_Plugin_CPT_Manager();
		$plugins = $manager->get_all();
		
		if ( 0 == count( $plugins ) ) {
			echo '<p>You\'ve not built any plugins yet, why not <a href="plugins.php?page=plugin-builder&view=build">build one now</a></p>';
			return;
		}
		
		echo '
	<table class="wp-list-table widefat plugins">
		<thead>
			<tr>
				<th scope="col">' . __( 'Plugin name', 'plugin-builder' ) . '</th>
				<th scope="col">' . __( 'Date', 'plugin-builder' ) . '</th>
				<th scope="col">' . __( 'Builder', 'plugin-builder' ) . '</th>
				<th scope="col">' . __( 'Details', 'plugin-builder' ) . '</th>
			</tr>
		</thead>';
		
		foreach( $plugins as $plugin ) {
			
			$author = get_userdata( $plugin->post_author );
			
			echo "
				<tr>
					<th scope=\"row\">
						<a href=\"plugins.php?page=plugin-builder&view=build&amp;plugin={$plugin->post_name}\">{$plugin->post_title}</a>
					</th>
					<td>
						{$plugin->post_date}
					</td>
					<td>
						{$author->display_name}
					</td>
					<td>
						<a href=\"#plugin-{$plugin->post_name}\" class=\"button toggler\">" . __( 'Details', 'plugin-builder' ) . "</a>
					</td>
				</tr>
				<tr class=\"hide-if-js\" id=\"plugin-{$plugin->post_name}\">
					<td colspan=\"4\">
						{$plugin->post_content}
					</td>
				</div>
			";
			
		}
		
	}
	
	/**
	 * Includes the file at the given path.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function render_partial( $path ) {

		// put the instance of this class into a variable that can be used in the partial
		$plugin_builder = $this;
		$settings = $this->get_settings();
		require_once( $path );

	}
	
	/**
	 * Renders the admin page.
	 *
	 * @since    1.0.0
	 */
	public function render_page() {
		
		if( isset( $_GET['view'] ) && 'build' == $_GET['view'] ) {
			$this->render_builder_page();
			return;
		}
		
		$this->render_list_page();
	}
	
	/**
	 * Renders the list page.
	 *
	 * @since    1.0.0
	 */
	private function render_list_page() {
	
		$path = apply_filters( 'plugin-builder-path', PLUGIN_BUILDER_DIR . 'admin/partials/list.php' );
		$this->render_partial( $path );
	
	}
	
	/**
	 * Renders the plugin builder page.
	 *
	 * @since    1.0.0
	 */
	private function render_builder_page() {
	
		$path = apply_filters( 'plugin-builder-path', PLUGIN_BUILDER_DIR . 'admin/partials/builder.php' );
		$this->render_partial( $path );
	
	}
	
	/**
	 * Sets up the tabs for the page.
	 *
	 * @since    1.0.0
	 */
	private function setup_tabs() {
		
		$general_settings_tab = $this->create_tab(
			'general-settings',
			PLUGIN_BUILDER_DIR . 'admin/partials/general-settings.php',
			__( 'General settings', 'plugin-builder' )
		);
		
		if ( ! is_string( $general_settings_tab ) ) {
			$this->tabs[] = $general_settings_tab;
		} else {
			wp_die( $general_settings_tab );
		}
		
		$cpt_tab = $this->create_tab(
			'cpt',
			PLUGIN_BUILDER_DIR . 'admin/partials/custom-post-types.php',
			__( 'Custom Post Types', 'plugin-builder' )
		);
		
		if ( ! is_string( $cpt_tab ) ) {
			$this->tabs[] = $cpt_tab;
		} else {
			wp_die( $cpt_tab );
		}
		
		$classes_tab = $this->create_tab(
			'classes',
			PLUGIN_BUILDER_DIR . 'admin/partials/classes.php',
			__( 'Classes', 'plugin-builder' )
		);
		
		if ( ! is_string( $classes_tab ) ) {
			$this->tabs[] = $classes_tab;
		} else {
			wp_die( $classes_tab );
		}
		
		$includes_tab = $this->create_tab(
			'includes',
			PLUGIN_BUILDER_DIR . 'admin/partials/includes.php',
			__( 'Includes', 'plugin-builder' )
		);
		
		if ( ! is_string( $includes_tab ) ) {
			$this->tabs[] = $includes_tab;
		} else {
			wp_die( $includes_tab );
		}
		
		$this->tabs = apply_filters( 'plugin-register-tabs', $this->tabs );
		
	}
	
	/**
	 * Creates a tab that can be added to the admin page.
	 *
	 * @since    1.0.0
	 * @access private
	 * @var string $slug The slug for the tab; must be unique
	 * @var string $path The path on disk to the partial to display
	 * @var string $title The translated title to show for the tab
	 * @return mixed The populated tab object, or a string describing the error encountered
	 */
	public function create_tab( $slug, $path, $title ) {
	    
	    $slug = trim( $slug );
	    $path = trim( $path );
	    $title = trim( $title );
	    
	    if ( strlen( $slug ) == 0 ) {
			return "No slug was given";
	    }
	    
	    if ( strlen( $path ) == 0 ) {
			return "No path was given";
	    }
	    
	    if ( strlen( $title ) == 0 ) {
			return "No title was given";
	    }
	    
	    if ( ! file_exists( $path ) ) {
			return "The file at the given path ($path) does not exist";
	    }
	    
	    $tab = new stdClass();
	    $tab->slug = strtolower( $slug );
	    $tab->path = $path;
	    $tab->title = $title;
	    
	    return $tab;
	    
	}
	
	/**
	 * Renders the tab menu for the page.
	 *
	 * @since    1.0.0
	 */
	public function render_tab_menu(){
		
		echo '
		    <ul class="plugin-register-tabs">
		    ';

		$item_classes = 'button button-primary';
		foreach ( $this->tabs as $tab ) {
			echo '
			    <li><a href="#' . $tab->slug . '" class="plugin-register-tablink plugin-register-tablink-' . $tab->slug . ' button ' . $item_classes . '">' . $tab->title . '</a></li>
			    ';
			$item_classes = '';
		}

		echo '
		    </ul>
		    ';
		
	}
	
	/**
	 * Includes the partials for each tab.
	 *
	 * @since  1.0.0
	 */
	public function render_tabs() {

		foreach ( $this->tabs as $tab ) {
			
			$this->current_tab = $tab;

			echo '
				<div id="' . $tab->slug . '" class="plugin-register-tab plugin-register-tab-' . $tab->slug . '">
				';

			$this->render_partial( $tab->path );

			echo '
				</div>
				';
			
			$this->current_tab = null;
			
		}
	}
	
	/**
	 * Gets the registered includes.
	 *
	 * @since  1.0.0
	 */
	public function get_includes() {
		
		$settings = $this->get_settings();
		
		$includes = apply_filters( 'plugin_builder_includes', $this->includes );
		
		foreach( $includes as $include ) {
			
			if( ! ( $include instanceof Plugin_Builder_Include ) ) {
				wp_die( 'An include class does not implement the Plugin_Builder_Include interface.' );
			}
			
			// set the included includes as included
			$include->included = false;
			
			if ( !isset( $settings->includes ) ) {
				continue;
			}
			
			if( in_array( $include->get_slug(), $settings->includes ) ) {
				$include->included = true;
			}
		}
		
		return $includes;
		
	}
	
	/**
	 * Creates a tab that can be added to the admin page.
	 *
	 * @since    1.0.0
	 * @access private
	 * @var string $slug The slug for the include; must be unique
	 * @var string $title The translated title to show for the include
	 * @var string $info_url The URL for further information about the include
	 * @var string $include_url The URL for the include file itself
	 * @var string $description The translated description of the include
	 * @return mixed The populated include object, or a string describing the error encountered
	 */
	public function create_include( $slug, $title, $info_url, $include_url, $description = '' ) {
		
		$slug = trim( $slug );
	    $info_url = trim( $info_url );
		$include_url = trim( $include_url );
	    $title = trim( $title );
	    
	    if ( strlen( $slug ) == 0 ) {
			return "No slug was given";
	    }
	    
	    if ( strlen( $info_url ) == 0 ) {
			return "No info URL was given";
	    }
		
		if ( strlen( $include_url ) == 0 ) {
			return "No include URL was given";
	    }
	    
	    if ( strlen( $title ) == 0 ) {
			return "No title was given";
	    }
	    
	    $include = new stdClass();
	    $include->slug = strtolower( $slug );
		$include->info_url = $info_url;
	    $include->include_url = $include_url;
		$include->description = $description;
	    $include->title = $title;
	    
	    return $include;
		
	}
	
	/**
	 * Gets the default settings object, or the settings for the plugin specified in an querystring key.
	 *
	 * @since  1.0.0
	 */
	public function get_settings() {
		
		if ( isset( $this->submitted_settings ) ) {
			return $this->submitted_settings;
		}
		
		if( isset( $_GET['plugin'] ) && '' != trim( $_GET['plugin'] ) ) {
			return $this->get_plugin_settings( trim( $_GET['plugin'] ) );
		}
		
		return $this->get_default_settings();
	}
	
	/**
	 * Gets the default settings object.
	 *
	 * @since  1.0.0
	 */
	private function get_default_settings() {
		
		$settings = new Plugin_Builder_Settings();
		$settings->set_defaults();
		
		$settings = apply_filters( 'plugin_builder_default_settings', $settings );
		
		return $settings;
	    
	}
	
	/**
	 * Gets the settings object for the plugin with the given name.
	 *
	 * @since  1.0.0
	 */
	private function get_plugin_settings( $plugin ) {
		
		$manager = new Plugin_Builder_Plugin_CPT_Manager();
		$settings = $manager->get_plugin_settings( $plugin );
		
		if ( null == $settings ) {
			return $this->get_default_settings();
		}
		
		return $settings;
	}

}
