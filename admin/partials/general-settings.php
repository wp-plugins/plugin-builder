<?php

/**
 * The form for entering the general settings for the plugin being built
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>

<h3><?php _e( 'General Settings', 'plugin-builder' ) ?></h3>

<div id="titlediv">
	<p id="titlewrap">
		<input type="text" name="plugin_name" size="30" value="<?php echo $settings->plugin_name ?>" id="title" autocomplete="off" data-cip-id="title" placeholder="<?php _e( 'Plugin name', 'plugin-builder' ) ?>" value="<?php echo $settings->plugin_name ?>">
	</p>
</div>

<p>
    <label for="plugin_slug" class="inline"><?php _e( 'Slug', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_slug" name="plugin_slug" value="<?php echo $settings->plugin_slug ?>" class="autoslug medium" />
</p>
<p class="howto label-indent">
	<?php _e( 'Must be lowercase, alpha-numeric, with dashes instead of spaces.', 'plugin-builder' ) ?>
</p>

<p>
    <label for="plugin_package_name" class="inline"><?php _e( 'Package name', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_package_name" name="plugin_package_name" value="<?php echo $settings->plugin_package_name ?>" class="autocamel medium" />
</p>
<p class="howto label-indent">
	<?php _e( 'Must be Pascal case, alpha-numeric, with underscores instead of spaces.', 'plugin-builder' ) ?>
</p>

<p>
    <label for="plugin_uri" class="inline"><?php _e( 'URI', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_uri" name="plugin_uri" value="<?php echo $settings->plugin_uri ?>" />
</p>

<p>
    <label for="plugin_description" class="inline"><?php _e( 'Description', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_description" name="plugin_description" value="<?php echo $settings->description ?>" />
</p>

<p>
    <label for="plugin_version" class="inline"><?php _e( 'Version', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_version" name="plugin_version" class="medium" value="<?php echo $settings->version ?>" />
</p>

<p>
    <label for="plugin_author" class="inline"><?php _e( 'Author', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_author" name="plugin_author" value="<?php echo $settings->author ?>" />
</p>

<p>
    <label for="plugin_author_email" class="inline"><?php _e( 'Author email', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_author_email" name="plugin_author_email" value="<?php echo $settings->author_email ?>" />
</p>

<p>
    <label for="plugin_author_uri" class="inline"><?php _e( 'Author URI', 'plugin-builder' ) ?></label>
    <input type="text" id="plugin_author_uri" name="plugin_author_uri" value="<?php echo $settings->author_uri ?>" />
</p>

<p>
	<label for="renew_cached_includes" class="inline"><?php _e( 'Renew cached includes', 'plugin-builder' ) ?></label>
	<input type="checkbox" id="renew_cached_includes" name="renew_cached_includes" value="1" class="cb" <?php echo $settings->renew_cached_includes ? ' checked="checked"' : '' ?> />
</p>
<p class="howto label-indent">
	<?php _e( 'Get the latest version of includes, renewing the cached versions.', 'plugin-builder' ) ?>
</p>

<h4><a href="#advanced" id="plugin_advanced_settings" class="toggler button"><?php _e( 'Advanced settings', 'plugin-register' ) ?></a></h4>

<div id="advanced" class="hide-if-js">
    
    <p>
		<label for="plugin_license" class="inline"><?php _e( 'License', 'plugin-builder' ) ?></label>
		<input type="text" id="plugin_license" name="plugin_license" class="medium" value="<?php echo $settings->license ?>" />
    </p>

    <p>
		<label for="plugin_license_uri" class="inline"><?php _e( 'License URI', 'plugin-builder' ) ?></label>
		<input type="text" id="plugin_license_uri" name="plugin_license_uri" value="<?php echo $settings->license_uri ?>" />
    </p>
	<p class="howto label-indent">
		<?php _e( 'Not sure about licenses? <a href="http://choosealicense.com/">This site may help you</a>.', 'plugin-builder' ) ?>
	</p>
    
    <p>
		<label for="plugin_text_domain" class="inline"><?php _e( 'Text Domain', 'plugin-builder' ) ?></label>
		<input type="text" id="plugin_text_domain" name="plugin_text_domain" value="<?php echo $settings->text_domain ?>" class="autoslug medium" />
    </p>
    
    <p>
		<label for="plugin_domain_path" class="inline"><?php _e( 'Domain Path', 'plugin-builder' ) ?></label>
		<input type="hidden" id="plugin_domain_path" name="plugin_domain_path" class="medium" value="<?php echo $settings->domain_path ?>" />
		<?php echo $settings->domain_path ?>
    </p>
    
</div>