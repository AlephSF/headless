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


	 /**
 	 * Check if request should be redirected
 	 *
 	 * @since    1.0.0
	 *
 	 */
	 public function redirect_check () {
		if( is_page('headless-post-preview') ){
			return;
		}
 		if( is_single() || is_front_page() || is_page() || is_tax() || is_tag() || is_category() || is_author() || is_date() ){ // date archives
 			$this->redirect_to_frontend();
 		}
 	}


	/**
	* Redirect request to same path on front end domain
	*
	* @since    1.0.0
	*
	*/
	protected function redirect_to_frontend(){
		$old_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$new_link = $this->change_permalink( $old_link );
		exit( wp_redirect( $new_link ) );
	}


	public function change_yoast_sitemap_url( $output, $url ) {
		if( defined('HEADLESS_FRONTEND_URL') ) {
			$output = str_replace(get_bloginfo('url'), HEADLESS_FRONTEND_URL, $output);
		}
		return $output;
	}

	public function change_sitemap_index_url( $config_wp_home ) {
		if( defined('HEADLESS_FRONTEND_URL') && !is_admin() && strpos( $_SERVER['REQUEST_URI'], 'sitemap' ) ) {
	    $config_wp_home = HEADLESS_FRONTEND_URL . '/sitemaps';
		}
		return $config_wp_home;
	}

	function set_logged_in_cookie() {
			setcookie('headless_logged_in', true, time()+60*60*24*14, '/', $_SERVER['HTTP_HOST'], 1);
	}



}
