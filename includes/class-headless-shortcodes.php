<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alephsf.com
 * @since      1.0.0
 *
 * @package    Headless
 * @subpackage Headless/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Headless
 * @subpackage Headless/admin
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless_Shortcodes {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Whitelist specific shortcodes
	 *
	 * @since    1.0.0
	 *
	 */
	function whitelist_shortcodes( $content) {
		if( ! defined('HEADLESS_SHORTCODE_WHITELIST') ){
			return $content;
		}

	  global $shortcode_tags;
	  // Store original copy of registered tags.
	  $_shortcode_tags = $shortcode_tags;
	  // Remove any tags not in whitelist.
	  foreach ( $shortcode_tags as $tag => $function ) {
	    if (!in_array($tag, HEADLESS_SHORTCODE_WHITELIST)) {
	      unset( $shortcode_tags[ $tag ] );
	    }
	  }
	  // Apply shortcode.
	  $content = shortcode_unautop( $content );

	  // Restore tags.
	  $shortcode_tags = $_shortcode_tags;
	  return $content;
	}
}
