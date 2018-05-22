<?php

/**
 * The cache-management part of the plugin
 *
 * @link       https://alephsf.com
 * @since      1.2.0
 *
 * @package    Headless
 * @subpackage Headless/admin
 */

/**
 *
 * @package    Headless
 * @subpackage Headless/includes
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless_Cache {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {

	}

	/**
	 * Constructs a URL for the front end to clear
	 *
	 * @since    1.2.0
	 * @param str $frontend_url The URL to clear
	 */
	 public function clear_on_save( $post_id ){
		 $frontend_url = get_the_permalink( $post_id );
		 $this->bypass_cache($frontend_url);
	 }

	/**
	 * Sends an HTTP Request to a URL featuring a param that should clear any caches
	 * in the pipeline
	 *
	 * @since    1.2.0
	 * @param str $frontend_url The URL to clear
	 */
	 protected function bypass_cache( $frontend_url ){
		 $headers = array(
			  'authority' => HEADLESS_FRONTEND_URL,
				'pragma' => 'no-cache',
				'cache-control' => 'no-cache',
				'upgrade-insecure-requests' => '1',
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36',
				'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
				'accept-encoding' => 'gzip, deflate, br',
				'accept-language' => 'en-US,en;q=0.9',
				'X-Cache-Bypass-Please' => 'true'
		 );

		 if( defined('HEADLESS_FRONTEND_AUTH_USER') && defined('HEADLESS_FRONTEND_AUTH_PASS') ){
			 $headers['Authorization'] = 'Basic ' . base64_encode( HEADLESS_FRONTEND_AUTH_USER . ':' . HEADLESS_FRONTEND_AUTH_PASS );
		 }

		 $args = array(
		 	'headers' => $headers,
			'sslverify'   => WP_ENV !== 'development',
			'timeout'     => 10
		 );

		 $response = wp_remote_get( $frontend_url, $args );
		 error_log(print_r($response, true));
	 }

	/**
	* Clears a cached NGINX route if header is passed in
	*
	* @since    1.2.3
	*/

	public function purge_nginx_cache_path(){
		if( array_key_exists('HTTP_X_CACHE_BYPASS_PLEASE', $_SERVER) && $_SERVER['HTTP_X_CACHE_BYPASS_PLEASE'] ){
			// Construct the NGINX fastcgi cache key
			// httpGETucb-vision-wp.local/wp-json/wp/v2/pages/9
			$cache_key = $_SERVER['REQUEST_SCHEME'] . $_SERVER['REQUEST_METHOD'] . $_SERVER['HTTP_X_FORWARDED_HOST'] . $_SERVER['REQUEST_URI'];
			$hash = md5($cache_key);
			$cache_path = '/nginx/cache/fcgi/' . $hash[-1] . '/' . $hash[-3] . $hash[-2] . '/' . $hash;
			if( file_exists($cache_path) ){
				unlink($cache_path);
				error_log('Cache cleared for route at: ' . $cache_key);
			}
		}
	}

}
