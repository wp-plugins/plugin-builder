<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form action="plugins.php?page=plugin-builder&amp;view=build" method="post" class="plugin-builder">

	<?php wp_nonce_field( 'plugin_builder', 'plugin_builder_nonce' ); ?>
	
	<h2>
		<input type="submit" id="build_button" name="build" class="button-primary" value="<?php _e( 'Build', 'plugin-builder' ) ?>" />
		<?php _e( 'Build a plugin', 'plugin-builder' ) ?>
	</h2>
	
	<?php $plugin_builder->render_messages() ?>

	<?php $plugin_builder->render_tab_menu() ?>

	<?php $plugin_builder->render_tabs() ?>

</form>