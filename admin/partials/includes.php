<?php

/**
 * PThe form for choosing the includes to add to the plugin being built
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>

<h3><?php _e( 'Includes', 'plugin-builder' ) ?></h3>

<p><?php _e( 'Chose what you want to include in your plugin.', 'plugin-builder' ) ?></p>

<?php foreach( $plugin_builder->get_includes() as $include ) { ?>

<div class="include-container">
	
	<h4>
		<input class="cb" type="checkbox" name="includes[]" <?php echo $include->included ? 'checked="checked"' : '' ?> id="include_<?php echo $include->get_slug() ?>" value="<?php echo $include->get_slug() ?>" />
		<label for="include_<?php echo $include->get_slug() ?>"><?php echo $include->get_title() ?></label>
		<a href="<?php echo $include->get_info_url() ?>" class="button"><?php _e( 'More info', 'plugin-builder' ) ?></a>
	</h4>

	<?php if( '' != $include->get_description() ) {
		echo $include->get_description();
	} ?>
	
</div>

<?php } ?>
