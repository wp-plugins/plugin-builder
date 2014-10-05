<?php

/**
 * The form for adding additional classes to the plugin being built
 *
 * @link       http://www.stillbreathing.co.uk/wordpress/plugin-builder
 * @since      1.0.0
 *
 * @package    Plugin_Builder
 * @subpackage Plugin_Builder/admin/partials
 */
?>

<h3><?php _e( 'Classes', 'plugin-builder' ) ?></h3>

<?php
$i = 0;
foreach( $settings->classes as $class ) {
?>

<div class="class_details repeater_item">
    
    <p>
		<label for="class_name_<?php echo $i ?>" class="inline"><?php _e( 'Class name', 'plugin-builder' ) ?></label>
		<input type="text" id="class_name_<?php echo $i ?>" name="class_name[]" class="medium" value="<?php echo $class->name ?>" />
		<a href="#class_<?php echo $i ?>" class="button toggler"><?php _e( 'Toggle details', 'plugin-builder' ) ?></a>
    </p>
	
	<div class="hide-if-js" id="class_<?php echo $i ?>">
    
		<p>
			<label for="class_description_<?php echo $i ?>" class="inline"><?php _e( 'Description', 'plugin-builder' ) ?></label>
			<input type="text" id="class_description_<?php echo $i ?>" name="class_description[]" value="<?php echo $class->description ?>" />
		</p>

		<p>
			<input type="submit" class="button" name="remove_class" value="<?php _e( 'Remove', 'plugin-builder' ) ?> [<?php echo $i ?>]" />
		</p>
	
	</div>
    
</div>

<?php 
$i++;
}
?>

<h4><?php _e( 'Add a custom PHP class', 'plugin-builder' ) ?></h4>

<div class="class_details">
    
    <p>
		<label for="class_name" class="inline"><?php _e( 'Class name', 'plugin-builder' ) ?></label>
		<input type="text" id="class_name" name="class_name[]" class="medium" />
    </p>
	<p class="howto label-indent">
		<?php _e( 'Enter the human-readable class name, it will be converted to a PHP-compatible class name automatically.', 'plugin-builder' ) ?>
	</p>
    
    <p>
		<label for="class_description" class="inline"><?php _e( 'Description', 'plugin-builder' ) ?></label>
		<input type="text" id="class_description" name="class_description[]" />
    </p>
    
    <p>
		<input type="submit" class="button" value="<?php _e( 'Add', 'plugin-builder' ) ?>" />
    </p>
    
</div>