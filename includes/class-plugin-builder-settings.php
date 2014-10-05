<?php

/**
 * Stores the settings for a plugin being built with the Plugin Builder
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 */

/**
 * Stores the settings for a plugin being built with the Plugin Builder
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/includes
 * @author     Chris Taylor
 */
class Plugin_Builder_Settings {
	
	var $build_path;
	var $post_id = 0;
	var $plugin_name;
	var $plugin_slug;
	var $plugin_package_name;
	var $plugin_uri;
	var $description;
	var $version;
	var $author;
	var $author_email;
	var $author_uri;
	var $license;
	var $license_uri;
	var $text_domain;
	var $domain_path;
	var $includes;
	var $custom_post_types;
	var $classes;
	var $renew_cached_includes;
	
	var $errors = array();

	/**
	 * Sets this instance to have the default settings.
	 *
	 * @since    1.0.0
	 */
	public function set_defaults() {
		
		global $current_user;
	    
		$this->plugin_name = '';
		$this->plugin_slug = '';
		$this->plugin_package_name  = '';
		$this->plugin_uri = $current_user->user_url;
		$this->description = '';
		$this->version = '1.0.0';
		$this->author = $current_user->display_name;
		$this->author_email = $current_user->user_email;
		$this->author_uri = $current_user->user_url;
		$this->license = 'GPL-2.0+';
		$this->license_uri = 'http://www.gnu.org/licenses/gpl-2.0.txt';
		$this->text_domain = '';
		$this->domain_path = '/languages';
		$this->selected_includes = array();
		$this->custom_post_types = array();
		$this->classes = array();
		$this->renew_cached_includes = false;
	    
	}
	
	/**
	 * Sets this instance properties from the given form array.
	 *
	 * @since    1.0.0
	 */
	public function set_from_form( $form ) {
		
		// get the general details
		$this->plugin_name = trim( $form['plugin_name'] );
		$this->plugin_slug = trim( $form['plugin_slug'] );
		$this->plugin_package_name  = trim( $form['plugin_package_name'] );
		$this->plugin_uri = trim( $form['plugin_uri'] );
		$this->description = trim( $form['plugin_description'] );
		$this->version = trim( $form['plugin_version'] );
		$this->author = trim( $form['plugin_author'] );
		$this->author_email = trim( $form['plugin_author_email'] );
		$this->author_uri = trim( $form['plugin_author_uri'] );
		$this->license = trim( $form['plugin_license'] );
		$this->license_uri = trim( $form['plugin_license_uri'] );
		$this->text_domain = trim( $form['plugin_text_domain'] );
		$this->domain_path = trim( $form['plugin_domain_path'] );
		$this->renew_cached_includes = isset( $form['renew_cached_includes'] ) && '1' == $form['renew_cached_includes'];
		
		// set the build path; this is the folder at which the plugin will be created
		$this->build_path = str_replace( '\\', '/', WP_PLUGIN_DIR ) . '/' . $this->plugin_slug . '/';
		
		// get the collections of additional objects to create
		$this->includes = $this->get_includes( $form );
		$this->custom_post_types = $this->get_custom_post_types( $form );
		$this->classes = $this->get_classes( $form );
		
		// run the validation function to set any errors
		$this->is_valid();
		
	}
	
	/**
	 * Returns the properties of this settings object as an HTML string.
	 *
	 * @since    1.0.0
	 */
	public function to_html() {
		
		$html = "
			<p>" . __( 'Plugin URI:', 'plugin-builder' ) . "<a href=\"{$this->plugin_uri}\">{$this->plugin_uri}</a></p>
		";
			
		if ( '' != trim( $this->description ) ) {
			$html .= "
			<p>{$this->description}</p>
			";
		}
		
		$html .= "
			<p>" . __( 'Version:', 'plugin-builder' ) . " {$this->version}</p>
			<p>" . __( 'Author:', 'plugin-builder' );
			
		if ( '' == trim( $this->author_uri ) ) {
			$html .= " {$this->author}";
		} else {
			$html .= " <a href=\"{$this->author_uri}\">{$this->author}</a>";
		}
		
		if ( '' == trim( $this->author_email ) ) {
			$html .= " <a href=\"mailto:{$this->author_email}\">{$this->author_email}</a>";
		}
		$html .= "</p>
			<p>" . __( 'License:', 'plugin-builder' ) . " <a href=\"{$this->license_uri}\">{$this->license}</a></p>
			";
			
		if ( 0 < count( $this->custom_post_types ) ) {
			$html .= "
			<h4>" . __( 'Custom Post Types', 'plugin-builder' ) . "</h4>
			<ol>
			";
			foreach( $this->custom_post_types as $cpt ) {
				$html .= "
				<li>{$cpt->name} ({$cpt->slug})</li>	
				";
			}
			$html .= "
			</ol>
			";
		}
		
		if ( 0 < count( $this->classes ) ) {
			$html .= "
			<h4>" . __( 'Classes', 'plugin-builder' ) . "</h4>
			<ol>
			";
			foreach( $this->classes as $classe ) {
				$html .= "
				<li>{$classe->name}</li>	
				";
			}
			$html .= "
			</ol>
			";
		}
		
		if ( 0 < count( $this->includes ) ) {
			$html .= "
			<h4>" . __( 'Includes', 'plugin-builder' ) . "</h4>
			<ol>
			";
			foreach( $this->includes as $include ) {
				$html .= "
				<li>$include</li>	
				";
			}
			$html .= "
			</ol>
			";
		}
		
		return $html;
		
	}
	
	/**
	 * Gets the selected includes from the given form array.
	 *
	 * @since    1.0.0
	 */
	private function get_includes( $form ) {
		
		$includes = array();
		
		if( ! is_posted_array( 'includes', 0, $form ) ) {
			return $includes;
		}


		foreach( $form['includes'] as $include ) {
			$includes[] = $include;
		}
		
		return $includes;
		
	}
	
	/**
	 * Gets the Custom Post Type settings from the given form array.
	 *
	 * @since    1.0.0
	 */
	private function get_custom_post_types( $form ) {
		
		$custom_post_types = array();
		
		if( ! is_posted_array( 'cpt_name', 0, $form ) 
			|| ! is_posted_array( 'cpt_description', 0, $form ) 
			|| ! is_posted_array( 'cpt_slug', 0, $form ) 
			|| ! is_posted_array( 'cpt_register_method', 0, $form )
		) {
			return $custom_post_types;
		}
			
		for( $i = 0; $i < count( $form['cpt_name'] ); $i++ ) {

			if ( '' == trim( $form['cpt_name'][$i] ) ) {
				continue;
			}

			if ( isset( $form['remove_cpt'] ) && strpos( $form['remove_cpt'], '[' . $i . ']' ) ) {
				continue;
			}

			$cpt = new Plugin_Builder_CPT_Settings();
			$cpt->name = trim( $form['cpt_name'][$i] );
			$cpt->slug = trim( $form['cpt_slug'][$i] );
			if ( '' == trim( $cpt->slug ) ) {
				$cpt->slug = sanitize_title( $cpt->name );
			}
			$cpt->description = trim( $form['cpt_description'][$i] );
			$cpt->create_manager = false;
			if ( isset( $form['cpt_create_manager'] ) && isset( $form['cpt_create_manager'][$i] ) && '1' == $form['cpt_create_manager'][$i] ) {
				$cpt->create_manager = true;
			}
			$cpt->register_method = $form['cpt_register_method'][$i];				
			$custom_post_types[] = $cpt;

		}
		
		return $custom_post_types;
		
	}
	
	/**
	 * Gets the class settings from the given form array.
	 *
	 * @since    1.0.0
	 */
	private function get_classes( $form ) {
		
		$classes = array();
		
		if( ! is_posted_array( 'class_name', 0, $form ) 
			|| ! is_posted_array( 'class_description', 0, $form ) 
		) {
			return $classes;
		}
			
		for( $i = 0; $i < count( $form['class_name'] ); $i++ ) {

			if ( '' == trim( $form['class_name'][$i] ) ) {
				continue;
			}

			if ( isset( $form['remove_class'] ) && strpos( $form['remove_class'], '[' . $i . ']' ) ) {
				continue;
			}

			$class = new Plugin_Builder_Class_Settings();
			$class->name = trim( $form['class_name'][$i] );
			$class->description = trim( $form['class_description'][$i] );
			$classes[] = $class;

		}
		
		return $classes;
		
	}
	
	/**
	 * Returns a value indicating if this settings object has valid values.
	 *
	 * @since    1.0.0
	 */
	public function is_valid() {
		
		$valid = true;
		$this->errors = array();
		
		if ( '' == trim( $this->plugin_name ) ) {
			$this->errors[] = __( 'You must enter a plugin name', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->plugin_slug ) ) {
			$this->errors[] = __( 'You must enter a valid plugin slug', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->plugin_package_name ) ) {
			$this->errors[] = __( 'You must enter a valid package name', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->plugin_uri ) || ! filter_var( $this->plugin_uri, FILTER_VALIDATE_URL ) ) {
			$this->errors[] = __( 'You must enter a valid plugin URI', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->author ) ) {
			$this->errors[] = __( 'You must enter the author name', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' != trim( $this->author_uri ) && ! filter_var( $this->author_uri, FILTER_VALIDATE_URL ) ) {
			$this->errors[] = __( 'You must enter a valid author URI', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' != trim( $this->author_email ) && ! filter_var( $this->author_email, FILTER_VALIDATE_EMAIL ) ) {
			$this->errors[] = __( 'You must enter a valid author email address', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->text_domain ) ) {
			$this->errors[] = __( 'You must enter a valid text domain', 'plugin-builder' );
			$valid = false;
		}
		
		if ( '' == trim( $this->domain_path ) ) {
			$this->errors[] = __( 'You must enter a valid domain path', 'plugin-builder' );
			$valid = false;
		}
		
		return $valid;
		
	}
}
