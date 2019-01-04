<?php

/**
 * The Gutenberg-specific functionality of the plugin.
 *
 * @link       https://alephsf.com
 * @since      1.4.0
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
class Headless_Gutenberg {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.4.0
	 */
	public function __construct() {

	}

	/**
	 * Hook into the save post hook, parse Gutenberg blocks, save as post meta
	 *
	 * @param int $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 * @since    1.4.0
	 */
	public function save_block_data($post_id, $post, $update){
		// Support for legacy gutenberg_parse_blocks
		if( function_exists( 'gutenberg_parse_blocks' ) || function_exists( 'parse_blocks' ) ){
			$block_data = $this->parse_blocks_as_array( $post->post_content );
			update_post_meta( $post_id, '_headless_block_data', $block_data );
		}
	}

	/**
	 * Parse blocks into an array
	 *
	 * @param string $raw_content Post content with Gutenberg comments in it for parsing.
	 * @return array $block_data
	 * @since    1.4.0
	 */
	private function parse_blocks_as_array($raw_content){
		$block_data = [];
		$raw_blocks = function_exists( 'gutenberg_parse_blocks' ) ? gutenberg_parse_blocks($raw_content) : parse_blocks($raw_content);
		foreach ($raw_blocks as $raw_block) {
			if( array_key_exists('blockName', $raw_block) ){
				array_push($block_data, $raw_block);
			}
		}
		return $block_data;
	}


}
