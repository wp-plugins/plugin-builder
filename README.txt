=== Plugin Builder ===
Contributors: MrWiblog
Donate link: http://www.stillbreathing.co.uk/wordpress/plugin-builder/
Tags: plugin, development, boilerplate
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gets started building a plugin using the WordPress Plugin Boilerplate in seconds, not hours. Speed up your development.

== Description ==

This is a plugin for WordPress plugin developers. If you don't understand what `PHP`, `HTML`, `CSS`, `add_action` and `apply_filters` are then this plugin is not for you!

The [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) is a fantastic tool for standardising WordPress plugins, and encouraging developers to use best practices. But manual editing of every file to enter the name of the plugin, the author name and all the other metadata is a bit of a chore. This plugin automates that process, giving you a simple form to enter your metadata, then when you hit the 'Build' button your new plugin is created with all the correct metadata.

But there's more. You can also define Custom Post Types and even other custom classes which will have .php files created automatically, and all the relevant code to include those files in your plugin. Once your plugin is built you can, of course, edit the files in any way you want to add extra methods.

Plugin Builder will even create a manager class for your Custom Post Types if you want, as a place to store methods related to your CPTs - such as a get_all() method, for example.

And that's not all. Some developers like to include extra items in their plugins, like utility classes or frameworks such as the [WordPress Settings Framework](https://github.com/gilbitron/WordPress-Settings-Framework). Plugin Builder automates that process, too, by downloading and including the files you choose in your new plugin.

Plugin Builder comes with a range of these additional includes you can choose from, but if there are other items you want to include you can make those part of the build process really easily (by extending a simple interface and using a filter).

So, let's look at the traditional way of using the WordPress Plugin Boilerplate to create a new plugin:

1. Download the Boilerplate and extract it
1. Rename all the files using your plugin name (e.g. 'class-plugin-name.php' to 'class-my-plugin.php')
1. Go through all the files and replace the metadata with your plugin details (name, slug, author, URIs etc)
1. Create your CPT class and the registration code,
1. Add the code to include the CPT file and register the CPT with WordPress
1. Create a custom class
1. Add the code to include your custom class file
1. Download your favourite utility files to your plugin folder
1. Add the code to include your utility files in your plugin

Or, using Plugin Builder:

1. Enter the details of your plugin (name and description - the slug and class name are automatically created)
1. Enter the details of your Custom Post Type (name and description, whether you want a manager class creating)
1. Enter the details of your custom class (name and description)
1. Check the box next to any utility files you want to be included
1. Press 'Build'

My guess is Plugin Builder will save you 2-4 hours work, and make your plugins much more standard in their architecture.

When your plugin is built the settings for it will be saved so you can rebuild your plugin at any time (this will overwrite any changes you've made manually) or make a few changes and create a new plugin.

== Installation ==

I recommend you install this only on your development environment, so you can open your new plugins and continue your development. There's no point installing it on a live site!

1. Upload the `plugin-builder` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Or install direct from the Plugin Repository using the Plugins system in WordPress.

== Frequently Asked Questions ==

= Why did you create Plugin Builder? =

I'm a big fan of the WordPress Plugin Boilderplate, but the tedious manual work required to get a plugin started annoyed me. I wanted to develop plugins in a standard way more quickly.

= Is Plugin Builder an official part of WordPress Plugin Boilerplate? =

No. It's built on top of the Boilerplate, but it's not part of that project.

= Why not just get the latest version of the Boilerplate from Github? Why have it in the Plugin Builder plugin itself? =

Because loading executable code for inclusion in a WordPress installation goes against the [WordPress Plugin Guidelines](https://wordpress.org/plugins/about/guidelines/). Having the files for the Boilerplate in Plugin Builder means they are diff-able, and therefore can be checked by the WordPress team. The Plugin Repository is their place, after all, and needs to be kept secure (as do people's WordPress sites who use this plugin).

= Where's the LEGO image from? =

It's [from Flickr](https://www.flickr.com/photos/eepaul/7396791752/in/photostream/). The photo was taken by [Paul Wilkinson](https://www.flickr.com/photos/eepaul/) and shared with a Creative Commons Attribution licence. Thanks, Paul. 

== Screenshots ==

1. The list of built plugins. You can open the plugin build settings form, make changes and rebuild, or you can build with a different slug (which makes a new plugin).
2. The main settings form; the advanced section is hidden by default.
3. The form to create Custom Post Types. You can add as many CPTs as you want.
4. The form to create custom classes. You can add as many custom classes as you want.
5. The form to add optional includes.

== Changelog ==

= 1.0 =
* The first release

== Upgrade Notice ==

There's nothing to upgrade from yet!

== Optional includes ==

The optional includes available in Plugin Builder are:

= WordPress Settings Framework =

The [WordPress Settings Framework](https://github.com/gilbitron/WordPress-Settings-Framework) aims to take the pain out of creating settings pages for your WordPress plugins by effectively creating a wrapper around the WordPress settings API and making it super simple to create and maintain settings pages.

= util.php =

[util.php](http://brandonwamboldt.github.io/utilphp/) is a collection of useful functions and snippets that you need or could use every day, designed to avoid conflicts with existing projects.

Expect more includes to be bundled with future versions of Plugin Builder. If you have an idea for an include you want and you think it may be useful for other developers let me know. Or, add your own include (see the next section for details).

= Adding your own includes =

Adding your own includes to Plugin Builder is really easy. There's an interface named Plugin_Builder_Include which you need to extend, it has a few methods that need to be implemented. Then you add a call to a method in your include class for the plugin_builder_includes filter. Here's a simple example:

`
class My_Include implements Plugin_Builder_Include {
	
	/**
	 * The method run when the user has selected this include.
	 *
	 * @since    1.0.0
	 * @var      Plugin_Builder_Settings    $settings    The settings for the plugin being built.
	 */
	public function process_include( $settings ) {
		
		// This is where you would do the work for your include; downloading files and saving them locally,
		// creating folders, adding settings etc.
		
		// Returns 'true' if your processing succeeds, and a string detailing the error if it fails.
		
		// We ALWAYS use the WordPress Filesystem API for file operations, see this for details: http://codex.wordpress.org/Filesystem_API
		global $wp_filesystem;
		if ( ! isset( $wp_filesystem ) || null == $wp_filesystem ) {
			return false;
		}

		// get the file we want to include in the new plugin from a local folder
		// Note: it's tempting to get your files from somewhere on the Internet (such as Github or your own site) but
		// this would be against the WordPress Plugin Guidelines: https://wordpress.org/plugins/about/guidelines/
		// Also you can't include your files in a Zip or other archive, they have to be diff-able (i.e. text-based) files.
		$cache_file = PLUGIN_BUILDER_DIR . 'cache/my-include.php';
		
		// copy the file to the build folder
		$wp_filesystem->copy( $cache_file, $settings->build_path . 'includes/my-include.php' );
		
		return true;
		
	}
	
	/**
	 * Returns any code to be injected into the top of the load_dependencies() method.
	 *
	 * @since    1.0.0
	 */
	public function get_dependencies_code() {
		
		// Be careful here: you're writing PHP code as a string to be injected into a .php file, so it must be valid.
		
		return "
		/**
		 * My Include
		 *
		 * My Include is an example include for the Plugin Builder plugin.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/my-include.php';
		";
		
	}
	
	/**
	 * Returns the unique slug for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_slug(){
	
		return 'my-include';
		
	}
	
	/**
	 * Returns the translated title for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_title() {
	
		return __( 'My Include' );
		
	}
	
	/**
	 * Returns the translated description (HTML allowed) for this include.
	 *
	 * @since    1.0.0
	 */
	public function get_description() {
	
		return __( '<p>My Include is an example include for the Plugin Builder plugin.</p>' );
		
	}
	
	/**
	 * Returns the URL giving more information on this include.
	 *
	 * @since    1.0.0
	 */
	public function get_info_url() {
	
		return 'https://some-website.com/my-include/';
		
	}
	
	/**
	 * The method that will be called when the add_includes filter runs.
	 *
	 * @since    1.0.0
	 * @var      array    $includes    The array of includes.
	 */
	public function add_includes( $includes ) {
	
		return $includes;
		
	}
	
}

// add this include to Plugin Builder using the plugin_builder_includes filter
$my_include = new My_Include();
add_filter( 'plugin_builder_includes', array( $my_include, 'add_include' ) );
`