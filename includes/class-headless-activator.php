<?php

/**
 * Fired during plugin activation
 *
 * @link       https://alephsf.com
 * @since      1.0.0
 *
 * @package    Headless
 * @subpackage Headless/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Headless
 * @subpackage Headless/includes
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if( get_page_by_path('headless-post-preview') ){
      return;
    }
    // Gather post data.
    $preview_page = array(
        'post_title'    => 'Headless Post Preview',
        'post_name' => 'headless-post-preview',
        'post_type'  => 'page',
        'post_status'   => 'draft'
    );
    // Insert the post into the database.
    wp_insert_post( $preview_page );
	}

}
