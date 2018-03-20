<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://alephsf.com
 * @since      1.0.0
 *
 * @package    Headless
 * @subpackage Headless/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Headless
 * @subpackage Headless/includes
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$page = get_page_by_path('headless-post-preview');
	  if( $page ){
	    wp_delete_post( $page->ID, true );
	  } else {
	    return;
	  }
	}

}
