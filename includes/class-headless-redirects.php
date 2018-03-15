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
class Headless_Redirects {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

	}

	/**
	 * Change permalink to front end urls
	 *
	 * @since    1.0.0
	 * @param str $permalink The old Permalink
	 * @return str $permalink The new Permalink
	 */

	 public function change_permalink( $permalink ){
	     if( defined('HEADLESS_FRONTEND_URL') ) {
	       $url = parse_url($permalink);
	       $permalink = HEADLESS_FRONTEND_URL . $url['path'];
	     }
	     return $permalink;
	 }

}
