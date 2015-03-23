<?php

/**
 * The class that performs the processing to build a plugin from a given settings object.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * The class that performs the processing to build a plugin from a given settings object.
 *
 * @since      1.0.0
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
class Plugin_Builder_Processor {
	
	/**
	 * The settings object containing the settings with which to build the plugin.
	 *
	 * @since    1.0.0
	 */
	var $settings;
	
	/**
	 * The instance of the admin class being used to build this plugin.
	 *
	 * @since    1.0.0
	 */
	var $admin;
	
	/**
	 * Constructs an instance of this class with the given settings object.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $admin, $settings ) {
		
		if ( ! $settings->is_valid() ) {
			wp_die( __( 'The given settings object is not valid', 'plugin-builder' ) );
		}
		
		$this->admin = $admin;
		$this->settings = $settings;
	}
	
	/**
	 * Build the plugin defined in the settings object.
	 *
	 * @since    1.0.0
	 */
	public function build() {
		
		if ( ! isset( $this->settings ) ) {
			wp_die( __( 'There is no settings object from which to build this plugin', 'plugin-builder' ) );
		}
		
		// try to get credentials
		$creds = $this->get_credentials();
		if ( false === $creds ) {
			return false;
		}
		
		// now we have some credentials, try to get the wp_filesystem running
		if ( ! WP_Filesystem( $creds ) ) {
			
			// our credentials were no good, ask the user for them again
			$this->get_credentials();
			return false;
			
		}
		
		$folder_built = $this->create_build_folder();
		if ( false === $folder_built ) {
			$this->rollback();
			return false;
		}
		
		$boilerplate_copied = $this->copy_boilerplate();
		if ( false === $boilerplate_copied ) {
			$this->rollback();
			return false;
		}
		
		$classes_created = $this->create_classes();
		if ( false === $classes_created ) {
			$this->rollback();
			return false;
		}
		
		$cpts_created = $this->create_cpts();
		if ( false === $cpts_created ) {
			$this->rollback();
			return false;
		}
		
		$files_renamed = $this->rename_files();
		if ( false === $files_renamed ) {
			$this->rollback();
			return false;
		}
		
		$strings_replaced = $this->do_string_replacements();
		if ( false === $strings_replaced ) {
			$this->rollback();
			return false;
		}
		
		// we process includes after doing string replacements as we don't want to overwrite other
		// peoples file metadata
		$includes_processed = $this->process_includes();
		if ( false === $includes_processed ) {
			$this->rollback();
			return false;
		}
		
		$dependencies_added = $this->add_dependencies();
		if ( false === $dependencies_added ) {
			$this->rollback();
			return false;
		}
		
		$cpts_registered = $this->register_cpts();
		if ( false === $cpts_registered ) {
			$this->rollback();
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Rolls back the current build, deleting the build folder.
	 *
	 * @since    1.0.0
	 */
	private function rollback() {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return;
		}
		
		//TODO: should we do this, or just leave things as they are?
		if ( file_exists( $this->settings->build_path ) ) {
			$wp_filesystem->rmdir( $this->settings->build_path, true );
		}
		
	}
	
	/**
	 * Returns the array of form fields that need to be re-sent if the user is asked for credentials.
	 *
	 * @since    1.0.0
	 */
	private function get_form_fields() {
		
		$form_fields = array (
			'plugin_name',
			'plugin_slug',
			'plugin_package_name',
			'plugin_uri',
			'plugin_description',
			'plugin_version',
			'plugin_author',
			'plugin_author_email',
			'plugin_author_uri',
			'plugin_license',
			'plugin_license_uri',
			'plugin_text_domain',
			'plugin_domain_path',
			'renew_cached_includes',
			'cpt_name[]',
			'cpt_description[]',
			'cpt_slug[]',
			'cpt_register_method[]',
			'remove_cpt',
			'cpt_create_manager[]',
			'class_name[]',
			'class_description[]',
			'remove_class',
			'includes[]'
		);
		
		return apply_filters( 'plugin_builder_form_fields', $form_fields );
		
	}
	
	/**
	 * Attempts to get the credentials for access to the filesystem, returning false if a credentials form is to be displayed.
	 *
	 * @since    1.0.0
	 */
	private function get_credentials() {
		
		$url = wp_nonce_url( 'plugins.php?page=plugin-builder&view=build' );
		$form_fields = $this->get_form_fields();
		$method = '';
		$creds = request_filesystem_credentials( $url, $method, false, false, $form_fields );
		
		return $creds;
	}
	
	/**
	 * Creates the build folder, overwriting any existing folder.
	 *
	 * @since    1.0.0
	 */
	private function create_build_folder() {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return;
		}
		
		if( $wp_filesystem->exists( $this->settings->build_path ) ) {
			$wp_filesystem->rmdir( $this->settings->build_path, true );
		}
		
		$wp_filesystem->mkdir( $this->settings->build_path, 0777, true );
		
	}
	
	/**
	 * Copied the extracted boilerplate to the build folder.
	 *
	 * @since    1.0.0
	 */
	private function copy_boilerplate() {
		
		$source_dir = PLUGIN_BUILDER_DIR . 'cache/wordpress-plugin-boilerplate/plugin-name/trunk';
		$target_dir = $this->settings->build_path;
		
		return $this->copy_recursive( $source_dir, $target_dir );
	}
	
	/**
	 * Copies the given source path recursively to the given target path.
	 *
	 * @since    1.0.0
	 */
	private function copy_recursive( $source, $target ) {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$source = rtrim( $source, '\\' );
		$source = rtrim( $source, '/' );
		$target = rtrim( $target, '\\' );
		$target = rtrim( $target, '/' );
		
		if ( ! $wp_filesystem->exists( $target ) ) {
			$wp_filesystem->mkdir( $target );
		}
		
		$rdi = new RecursiveDirectoryIterator( $source, FilesystemIterator::SKIP_DOTS);
		foreach( $iterator = new RecursiveIteratorIterator( $rdi, RecursiveIteratorIterator::SELF_FIRST ) as $item ) {

				$source_file = str_replace( '\\', '/', $source . '/' . $iterator->getSubPathName() );
				$target_file = str_replace( '\\', '/', $target . '/' . $iterator->getSubPathName() );
				
				if ($item->isDir()) {
					mkdir( $target_file );
				} else {
					copy( $source_file, $target_file );
				}
		 }
		 
		 return true;
		
	}
	
	/**
	 * Creates the classes defined in the settings object.
	 *
	 * @since    1.0.0
	 */
	private function create_classes() {
		
		if( ! isset( $this->settings->classes ) || 0 == count( $this->settings->classes ) ) {
			return true;
		}
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$source_file = PLUGIN_BUILDER_DIR . 'cache/plugin-builder/class-plugin-name-class-name.php';
		
		foreach( $this->settings->classes as $class ) {
		
			$class->path = 'includes/class-' . $this->settings->plugin_slug . '-' . sanitize_title( $class->name ) . '.php';
			$class_path = $this->settings->build_path . $class->path;
			
			copy( $source_file, $class_path );
			
			$contents = $wp_filesystem->get_contents( $class_path );
			
			$contents = str_replace( 'Class_Name', $this->settings->plugin_package_name . '_' . camelify( $class->  name ), $contents );
			$contents = str_replace( '{description}', $class->description, $contents );
			
			$wp_filesystem->put_contents( $class_path, $contents );
		
		}
		
	}
	
	/**
	 * Creates the Custom Post Types defined in the settings object.
	 *
	 * @since    1.0.0
	 */
	private function create_cpts() {
		
		if( ! isset( $this->settings->custom_post_types ) || 0 == count( $this->settings->custom_post_types ) ) {
			return true;
		}
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$source_file = PLUGIN_BUILDER_DIR . 'cache/plugin-builder/class-plugin-name-cpt-name.php';
		$manager_source_file = PLUGIN_BUILDER_DIR . 'cache/plugin-builder/class-plugin-name-cpt-name-manager.php';
		
		foreach( $this->settings->custom_post_types as $cpt ) {
		
			// set up the paths
			$cpt->path = 'includes/class-' . $this->settings->plugin_slug . '-' . $cpt->slug . '-cpt.php';
			$cpt_path = $this->settings->build_path . $cpt->path;
			
			// copy the CPT class template
			$wp_filesystem->copy( $source_file, $cpt_path );
			
			// get the contents of the new CPT class
			$contents = $wp_filesystem->get_contents( $cpt_path );
			
			// replace some variables
			$name = $cpt->get_class_name( $this->settings );
			$contents = str_replace( 'CPT_Name', $name, $contents );
			$contents = str_replace( '{description}', $cpt->description, $contents );
			
			// add the register method code
			$register_method = $this->get_default_cpt_register_method( $name, $cpt->slug );
			if ( '' != trim( $cpt->register_method ) ) {
				$register_method = $cpt->register_method;
			}
			$contents = str_replace( '//{register_method}', $register_method, $contents );
			
			// save the updated CPT class
			$wp_filesystem->put_contents( $cpt_path, $contents );
		
			// if we need to create the manager class
			if ( $cpt->create_manager ) {
				
				// set up the paths
				$cpt->manager_path = 'includes/class-' . $this->settings->plugin_slug . '-' . $cpt->slug . '-cpt-manager.php';
				$manager_path = $this->settings->build_path . $cpt->manager_path;
				
				// copy the manager class template
				$wp_filesystem->copy( $manager_source_file, $manager_path );

				// get the contents of the new manager class
				$contents = $wp_filesystem->get_contents( $manager_path );
				
				// replace some variables
				$name = $cpt->get_class_name( $this->settings );
				$contents = str_replace( 'CPT_Name', $name, $contents );
				$contents = str_replace( '{slug}', $cpt->slug, $contents );
				
				// save the updated manager class
				$wp_filesystem->put_contents( $manager_path, $contents );
				
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Renames files in the build folder to have the slug of the plugin instead of 'plugin-name'.
	 *
	 * @since    1.0.0
	 */
	private function rename_files() {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$rdi = new RecursiveDirectoryIterator( $this->settings->build_path, FilesystemIterator::SKIP_DOTS);
		foreach( new RecursiveIteratorIterator( $rdi, RecursiveIteratorIterator::CHILD_FIRST ) as $item ) {
			
			$filename = $item->getPathname();
			
			if( $item->isFile() && false !== stripos( $filename, 'plugin-name' ) ) {
				$wp_filesystem->move( $filename, str_ireplace( 'plugin-name', $this->settings->plugin_slug, $filename ) );
			}
		}
		
		return true;
	}
	
	/**
	 * Processes the selected includes.
	 *
	 * @since    1.0.0
	 */
	private function process_includes() {

		if( ! isset( $this->settings->includes ) || 0 == count( $this->settings->includes ) ) {
			return true;
		}
		
		$include_classes = $this->admin->get_includes();
		
		foreach( $include_classes as $include ) {
			
			if( ! in_array( $include->get_slug(), $this->settings->includes ) ) {
				continue;
			}
			
			$result = $include->process_include( $this->settings );

			if ( '1' != $result ) {
				wp_die( "Error processing the include '" . $include->get_slug() . "': " . $result );
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Performs string replacements in files for the plugin name, slug etc.
	 *
	 * @since    1.0.0
	 */
	private function do_string_replacements() {
		
		$rdi = new RecursiveDirectoryIterator( $this->settings->build_path, FilesystemIterator::SKIP_DOTS );
		foreach( new RecursiveIteratorIterator( $rdi, RecursiveIteratorIterator::CHILD_FIRST ) as $item ) {
			
			$filename = $item->getPathname();
			
			if( $item->isFile() ) {
				$this->do_string_replacements_in_file( $filename );
			}
		}	
	}
	
	/**
	 * Performs string replacements in the given file.
	 *
	 * @since    1.0.0
	 */
	private function do_string_replacements_in_file( $file ) {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$contents = $wp_filesystem->get_contents( $file );
		
		// names, slugs and version
		$contents = str_replace( 'Plugin_Name', $this->settings->plugin_package_name, $contents );
		$contents = str_replace( 'WordPress Plugin Boilerplate', $this->settings->plugin_name, $contents );
		$contents = str_replace( '1.0.0', $this->settings->version, $contents );
		$contents = str_replace( 'plugin-name', $this->settings->plugin_slug, $contents );
		$contents = str_replace( 'plugin_name', $this->settings->plugin_package_name, $contents );
		
		// the property 'plugin_name' in the main class gets changed incorrectly, so set it back
		$contents = str_replace( '$this->' . $this->settings->plugin_package_name, '$this->plugin_name', $contents );
		
		// the method 'get_plugin_name()' in the main class gets changed incorrectly, so set it back
		$contents = str_replace( 'get_' . $this->settings->plugin_package_name . '()', 'get_plugin_name()', $contents );
		
		// meta information
		$contents = preg_replace( "/[ ]*(.*Plugin URI:.*)[\r\n]*/i", " * Plugin URI:        " . $this->settings->plugin_uri . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*Author:.*)[\r\n]*/i", " * Author:            " . $this->settings->author . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*Author URI:.*)[\r\n]*/i", " * Author URI:        " . $this->settings->author_uri . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*Description:.*)[\r\n]*/i", " * Description:        " . $this->settings->description . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*License:.*)[\r\n]*/i", " * License:            " . $this->settings->license . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*License URI:.*)[\r\n]*/i", " * License URI:        " . $this->settings->license_uri . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*Text Domain:.*)[\r\n]*/i", " * Text Domain:            " . $this->settings->text_domain . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*Domain Path:.*)[\r\n]*/i", " * Domain Path:            " . $this->settings->domain_path . "\n", $contents );
		$contents = preg_replace( "/[ ]*(.*@link.*)[\r\n]*/i", " * @link:       " . $this->settings->plugin_uri . "\n", $contents );
		
		if ( '' == $this->settings->author_email ) {
			$contents = preg_replace( "/[ ]*(.*@author.*)[\r\n]*/i", " * @author:       " . $this->settings->author . "\n", $contents );
		} else {
			$contents = preg_replace( "/[ ]*(.*@author.*)[\r\n]*/i", " * @author:       " . $this->settings->author . " <" . $this->settings->author_email . ">\n", $contents );
		}
		
		//echo "<h2>$file</h2>";
		//echo "<textarea style='width:100%;height:10em'>$contents</textarea>";
		
		$wp_filesystem->put_contents( $file, $contents );
		
	}
	
	/**
	 * Adds the custom classes and Custom Post Types to the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	private function add_dependencies() {
		
		if ( 0 == count( $this->settings->classes ) 
			&& 0 == count( $this->settings->custom_post_types ) 
			&& 0 == count( $this->settings->includes ) ) {
			return;
		}
		
		$dependencies = '';
		
		// custom classes
		foreach( $this->settings->classes as $class ) {
			
			$dependencies .= "
				
		/**
		 * Custom class: {$class->name}
		 *
		 * {$class->description}
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '{$class->path}';
";
			
		}
		
		// Custom Post Types
		foreach( $this->settings->custom_post_types as $cpt ) {
			
			$cptname = $cpt->get_class_name( $this->settings );
			
			$dependencies .= "
				
		/**
		 * Custom Post Type: $cptname
		 *
		 * {$cpt->description}
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '{$cpt->path}';
";
			
		if ( true == $cpt->create_manager ) {
				$dependencies .= "		require_once plugin_dir_path( dirname( __FILE__ ) ) . '{$cpt->manager_path}';
			
";
			}
		
		}
		
		$dependencies .= "
";
		
		// add the dependencies code to the method
		
		$this->add_code_to_method( 
			$this->settings->build_path . 'includes/class-' . $this->settings->plugin_slug . '.php',
			'load_dependencies',
			$dependencies
		);
		
		// get the dependencies code for each include and add it to the method
		
		$include_dependencies = '';
		$include_classes = $this->admin->get_includes();
		
		foreach( $include_classes as $include ) {
			
			if( ! in_array( $include->get_slug(), $this->settings->includes ) ) {
				continue;
			}
			
			$include_dependencies .= $include->get_dependencies_code();
		}
		
		$this->add_code_to_method( 
			$this->settings->build_path . 'includes/class-' . $this->settings->plugin_slug . '.php',
			'load_dependencies',
			$include_dependencies
		);
		
	}
	
	/**
	 * Adds the calls to the register method for each Custom Post Type.
	 *
	 * @since    1.0.0
	 */
	private function register_cpts() {
		
		if ( 0 == count( $this->settings->custom_post_types ) ) {
			return;
		}
		
		foreach( $this->settings->custom_post_types as $cpt ) {
		
			$name = $name = $cpt->get_class_name( $this->settings );
			$name_lowered = strtolower( $name );
			
			$code = "
		\${$name_lowered} = new $name();
		\${$name_lowered}->register();

";
			
			$this->add_code_to_method( 
				$this->settings->build_path . 'includes/class-' . $this->settings->plugin_slug . '-loader.php',
				'__construct',
				$code
			);

		}
		
	}
	
	/**
	 * Adds the given code string into the given method in the given file then saves the file.
	 *
	 * @since    1.0.0
	 */
	private function add_code_to_method( $file, $method_name, $code ) {
		
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}
		
		$contents = $wp_filesystem->get_contents( $file );
		$contents = preg_replace( "/$method_name\(\).*\{.*[\r\n]*/i", $method_name . "() {\n" . $code, $contents );
		$wp_filesystem->put_contents( $file, $contents );
		
		return true;
		
	}
	
	/**
	 * Gets the default registration code for the Custom Post Type with the given name.
	 *
	 * @since    1.0.0
	 */
	private function get_default_cpt_register_method( $name, $slug ) {
		
		$code = "
		/**
		* Register this Custom Post Type.
		*
		* @since    1.0.0
		*/
	   public function register() {
		
			\$labels = array(
				'name'                => _x( '{$name}s', 'Post Type General Name', 'plugin-name' ),
				'singular_name'       => _x( '$name', 'Post Type Singular Name', 'plugin-name' ),
				'menu_name'           => __( '$name', 'plugin-name' ),
				'parent_item_colon'   => __( 'Parent Item:', 'plugin-name' ),
				'all_items'           => __( '$name', 'plugin-name' ),
				'view_item'           => __( 'View Item', 'plugin-name' ),
				'add_new_item'        => __( 'Create New ' . $name, 'plugin-name' ),
				'add_new'             => __( 'Create ' . $name, 'plugin-name' ),
				'edit_item'           => __( 'Edit Item', 'plugin-name' ),
				'update_item'         => __( 'Update Item', 'plugin-name' ),
				'search_items'        => sprintf( __( 'Search %s', 'plugin-name' ), '{$name}s' ),
				'not_found'           => __( 'Not found', 'plugin-name' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'plugin-name' ),
			);
			\$args = array(
				'label'               => __( '$slug', 'plugin-name' ),
				'description'         => __( '$name', 'plugin-name' ),
				'labels'              => \$labels,
				'supports'            => array( 'title', 'editor', 'excerpt', 'author', ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 20,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( '$slug', \$args );
		
		}
		";
		
		return $code;
		
	}
	
}
