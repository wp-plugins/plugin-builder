<?php

/**
 * The form for adding Custom Post Types to the plugin being built
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>

<h3><?php _e( 'Custom Post Types', 'plugin-builder' ) ?></h3>

<?php 
$i = 0;
foreach( $settings->custom_post_types as $cpt ) {	
?>

<div class="cpt_details repeater_item">
    
    <p>
		<label for="cpt_name_<?php echo $i ?>" class="inline"><?php _e( 'CPT name', 'plugin-builder' ) ?></label>
		<input type="text" id="cpt_name_<?php echo $i ?>" name="cpt_name[]" class="medium" value="<?php echo $cpt->name ?>" />
		<a href="#cpt_<?php echo $i ?>" class="button toggler"><?php _e( 'Toggle details', 'plugin-builder' ) ?></a>
    </p>
	
	<div class="hide-if-js" id="cpt_<?php echo $i ?>">
	
		<p>
			<label for="cpt_slug_<?php echo $i ?>" class="inline"><?php _e( 'Post type', 'plugin-builder' ) ?></label>
			<input type="text" id="cpt_slug_<?php echo $i ?>" name="cpt_slug[]" class="medium" value="<?php echo $cpt->slug ?>" />
		</p>

		<p>
			<label for="cpt_create_manager_<?php echo $i ?>" class="inline"><?php _e( 'Create manager class', 'plugin-builder' ) ?></label>
			<input type="checkbox" id="cpt_create_manager_<?php echo $i ?>" name="cpt_create_manager[]" value="1" class="cb" <?php echo $cpt->create_manager ? ' checked="checked"' : '' ?> />
		</p>

		<p>
			<label for="cpt_description_<?php echo $i ?>" class="inline"><?php _e( 'Description', 'plugin-builder' ) ?></label>
			<input type="text" id="cpt_description_<?php echo $i ?>" name="cpt_description[]" value="<?php echo $cpt->description ?>" />
		</p>

		<p>
			<label for="cpt_register_method_<?php echo $i ?>" class="inline"><?php _e( 'Registration method', 'plugin-builder' ) ?></label>
			<textarea id="cpt_register_method_<?php echo $i ?>" name="cpt_register_method[]"><?php echo $cpt->register_method ?></textarea>
		</p>

		<p>
			<input type="submit" class="button" name="remove_cpt"  value="<?php _e( 'Remove', 'plugin-builder' ) ?> [<?php echo $i ?>]" />
		</p>
		
	</div>
    
</div>

<?php
$i++;
}
?>

<h4><?php _e( 'Add a Custom Post Type', 'plugin-builder' ) ?></h4>

<p><?php _e( 'Enter the details for a Custom Post Type you want to include in your plugin, optionally entering the full method to register the CPT (we recommend <a href="http://generatewp.com/post-type/">Generate WP</a>).', 'plugin-builder' ) ?></p>

<div class="cpt_details">
    
    <p>
		<label for="cpt_name" class="inline"><?php _e( 'CPT name', 'plugin-builder' ) ?></label>
		<input type="text" id="cpt_name" name="cpt_name[]" class="medium" />
    </p>
	<p class="howto label-indent">
		<?php _e( 'Enter the human-readable Custom Post Type name, it will be converted to a PHP-compatible class name automatically.', 'plugin-builder' ) ?>
	</p>
	
	<p>
		<label for="cpt_slug" class="inline"><?php _e( 'Post type', 'plugin-builder' ) ?></label>
		<input type="text" id="cpt_slug" name="cpt_slug[]" class="medium" class="autoslug" />
    </p>
	<p class="howto label-indent">
		<?php _e( 'There is a limit of 20 characters for Custom Post Type identifiers.', 'plugin-builder' ) ?>
	</p>
	
	<p>
		<label for="cpt_create_manager" class="inline"><?php _e( 'Create manager class', 'plugin-builder' ) ?></label>
		<input type="checkbox" id="cpt_create_manager" name="cpt_create_manager[]" value="1" class="cb" />
	</p>
	<p class="howto label-indent">
		<?php _e( 'A manager class is where you can put methods to halp manage your Custom Post Type, for instance a \'get_all()\' method.', 'plugin-builder' ) ?>
	</p>

    <p>
		<label for="cpt_description" class="inline"><?php _e( 'Description', 'plugin-builder' ) ?></label>
		<input type="text" id="cpt_description" name="cpt_description[]" />
    </p>
    
    <p>
		<label for="cpt_register_method" class="inline"><?php _e( 'Registration method', 'plugin-builder' ) ?></label>
		<textarea id="cpt_register_method" name="cpt_register_method[]"></textarea>
    </p>
	<p class="howto label-indent">
		<?php _e( 'Enter the output from the <a href="http://generatewp.com/post-type/">Generate WP Custom Post Type form</a>, or your own registration method, or leave blank for the default method.', 'plugin-builder' ) ?>
	</p>
    
    <p>
		<input type="submit" class="button" value="<?php _e( 'Add', 'plugin-builder' ) ?>" />
    </p>
    
</div>