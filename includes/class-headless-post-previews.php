<?php

/**
 * The post preview hacking part of the plugin
 *
 * @link       https://alephsf.com
 * @since      1.0.2
 *
 * @package    Headless
 * @subpackage Headless/admin
 */

class Headless_Post_Previews {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.2
	 */
	public function __construct() {

	}

	public function preview_page_template( $page_template ){
    if ( is_page( 'headless-post-preview' ) ) {
      $page_template = dirname( __FILE__ ) . '/templates/headless-post-preview.php';
    }
    return $page_template;
	}

	public function frontend_preview_link($preview_link, $post) {
	  $revisions = wp_get_post_revisions( $post->ID );
	  if( !empty($revisions) ) {
	    $latest = current($revisions);
	    $preview_id = $latest->ID;
	  } else {
	    $preview_id = $post->ID;
	  }
	  $query_params = parse_url($preview_link, PHP_URL_QUERY);
	  $query_arr = $this->parse_query($query_params);
	  return home_url('/') . 'headless-post-preview?ptype=' . $post->post_type . '&preview_id=' . $preview_id . '&_thumbnail_id=' . $query_arr['_thumbnail_id'] . '&pformat=' . $query_arr['post_format'] . '&post_id=' . $post->ID;
	}

	public function parse_query($var) {
	  /**
	   *  Use this function to parse out the query array element from
	   *  the output of parse_url().
	   */
	  $var  = html_entity_decode($var);
	  $var  = explode('&', $var);
	  $arr  = array();
	  foreach($var as $val){
	    $x          = explode('=', $val);
	    $arr[$x[0]] = $x[1];
	  }
	  unset($val, $x, $var);
	  return $arr;
	}

	public function add_acf_to_revision( $response, $post ) {
	  $data = $response->get_data();

	  $data['acf'] = get_fields( $post->ID );

	  return rest_ensure_response( $data );
	}

	public function get_preview_json() {
		$json_url = home_url('/') . 'wordpress/wp-json/wp/v2/posts/' . $_GET['post_id'] . '/revisions/' . $_GET['preview_id'];
		$response = wp_remote_get( $json_url );
		// $body = json_decode( wp_remote_retrieve_body( $response ) );
		var_dump($response);
		// print_r($body);
		die();
	}
}
