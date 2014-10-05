<?php

/**
 * Provides methods to help manage the CPT_Name Custom Post Type
 * 
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Provides methods to help manage the CPT_Name Custom Post Type
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class CPT_Name_Manager {

	/**
	 * Gets all the plugins stored as a CPT_Name Custom Post Type.
	 *
	 * @since    1.0.0
	 */
	public function get_all() {

		$args = array (
			'post_type'              => '{slug}',
			'post_status'            => 'publish',
			'pagination'             => false,
			'order'                  => 'DESC',
			'orderby'                => 'title',
			'cache_results'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
			'posts_per_page'         => -1
		);
		
		$query = new WP_Query( $args );
		
		if ( false == $query->have_posts() ) {
			return array();
		}
		
		return $query->posts;
	
	}

}
