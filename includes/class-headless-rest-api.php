<?php

/**
 * Extends the WordPress REST API with helpful features
 *
 * @link       https://alephsf.com
 * @since      1.0.0
 *
 * @package    Headless
 * @subpackage Headless/admin
 */

/**
 * Extends the WordPress REST API with helpful features
 *
 *
 * @package    Headless
 * @subpackage Headless/admin
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless_Rest_Api {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->yoast = null;
	}

	public function add_seo_data() {

      register_rest_field(
          array(
						'post',
						'page'
					),
          'seoData',
          array(
              'get_callback' => array( $this, 'get_seo_data' )
          )
      );
  }

	public function remove_more_excerpt($more) {
		return '';
  }

	public function get_seo_data( $object, $field_name, $request ){

		if( class_exists('WPSEO_Frontend') ){
			$this->yoast = WPSEO_Frontend::get_instance();
		}

		$post = get_post( $object['id'] );
		$meta = get_post_meta( $object['id'] );
		$description = $this->get_seo_description( $post, $meta);
		$yoast_social = get_option('wpseo_social');
		return array(
			'title' => $this->get_seo_title( $post, $meta ),
			'description' => $description,
			'twitterHandle' => is_array($yoast_social) ? $yoast_social['twitter_site'] : null,
			'siteName' => get_bloginfo('name'),
			'fbAdmins' => is_array($yoast_social) && array_key_exists('fb_admins', $yoast_social) ? $yoast_social['fb_admins'] : null,
			'fbAppId' => is_array($yoast_social) && array_key_exists('fbadminapp', $yoast_social) ? $yoast_social['fbadminapp'] : null
		);
	}

	public function get_seo_title( $post, $meta ){
		if( $this->yoast ){
			$title = $this->yoast->get_content_title($post);
		} else {
			$title = get_the_title( $post ) . ' - ' . get_bloginfo('name');
		}
		return $title;
	}


	public function get_seo_description( $post, $meta ){
		if( is_array($meta) && key_exists('_yoast_wpseo_metadesc', $meta) ){
			$desc = $meta['_yoast_wpseo_metadesc'][0];
		} else {
			add_filter( 'excerpt_more', array($this, 'remove_more_excerpt'));
			$desc = get_the_excerpt($post->ID);
			remove_filter( 'excerpt_more', array($this, 'remove_more_excerpt'));
		}
		return $desc;
	}


}
