<?php

/**
 * Contains global functions
 *
 * * @link       http://voucherpress.com
 * @since      2.0.0
 *
 * @package    VoucherPress
 * @subpackage VoucherPress/includes
 */

/**
 * Contains global functions
 *
 * @package    Plugin Builder
 * @subpackage Plugin_Builder/includes
 * @author     Yorkshire Twist <support@yorkshiretwist.com>
 */

if ( ! function_exists( 'array_insert' ) ) {
	/**
	 * Inserts the given item at the given position in the given array
	 * 
	 * @since 2.0
	 * @access public
	 * @var array $array The array into which to insert the item
	 * @var mixed $item The item to insert
	 * @var int $position The position (zero-based) into which to insert the item
	 * @return array The modified array, unmodified if there was an error
	 */
	function array_insert( $array, $item, $position ) {

		if ( ! is_array( $array ) ) {
			throw new Exception( '$array is not an array' );
		}

		if ( $position < 0 ) {
			throw new Exception( '$position must be zero or more' );
		}

		if ( count( $array ) < $position ) {
			$array[] = $item;
			return $array;
		}

		return array_slice( $array, 0, $position, true ) +
			$item +
			array_slice( $array, 3, count( $array ) - $position, true);

	}
}

if ( ! function_exists( 'is_posted_array' ) ) {
	
	function is_posted_array( $key, $min_count = 0, $form = null ) {
		
		if ( null == $form ) {
			$form = $_POST;
		}
		
		if ( isset( $form[$key] ) && is_array( $form[$key] ) && $min_count < count( $form[$key] ) ) {
			return true;
		}
		return false;
		
	}
	
}

if ( ! function_exists( 'rmdir_recursive' ) ) {
	
	function rmdir_recursive( $root_path ) {
		
		$rdi = new RecursiveDirectoryIterator($root_path, FilesystemIterator::SKIP_DOTS);
		foreach( new RecursiveIteratorIterator( $rdi, RecursiveIteratorIterator::CHILD_FIRST ) as $item ) {
			
			$item->isDir() ? rmdir( $item->getPathname() ) : unlink( $item->getPathname() );
			
		}
		rmdir( $root_path );
		
	}
	
}

if ( ! function_exists( 'copy_recursive' ) ) {
	
	function copy_recursive( $source_path, $destination_path ) {

		$rdi = new RecursiveDirectoryIterator( $source_path, RecursiveDirectoryIterator::SKIP_DOTS );
		foreach ( $iterator = new RecursiveIteratorIterator( $rdi, RecursiveIteratorIterator::SELF_FIRST ) as $item ) {
			
		if ( $item->isDir() ) {
			
			mkdir( $destination_path . DIRECTORY_SEPARATOR . $iterator->getSubPathName() );
		
		} else {
			
			copy( $item, $destination_path . DIRECTORY_SEPARATOR . $iterator->getSubPathName() );
		
		}
		
		}
	}
	
}

if ( ! function_exists( 'camelify' ) ) {
	
	function camelify( $string ) {
		
		$output = preg_replace( "/[^A-Za-z0-9 ]/", '', $string );
		$output = ucwords( $string );
		$output = preg_replace( '/[\s]+/', '_', $output );
		return $output;
		
	}
	
}