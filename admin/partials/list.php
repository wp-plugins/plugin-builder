<?php

/**
 * Shows the list of plugin settings stored as pb-plugins Custom Post Types.
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>
	
<div class="plugin-builder">

<h2>
	<?php _e( 'Plugin Builder', 'plugin-builder' ) ?>
	<a href="plugins.php?page=plugin-builder&amp;view=build" class="button-primary"><?php _e( 'Build a plugin', 'plugin-builder' ) ?></a>
</h2>
	
<?php $plugin_builder->render_list() ?>

</div>