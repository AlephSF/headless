<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alephsf.com
 * @since      1.0.3
 *
 * @package    Headless
 * @subpackage Headless/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Headless
 * @subpackage Headless/includes
 * @author     Matt Glaser <matt@alephsf.com>
 */
class Headless {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Headless_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.4.0';
		}
		$this->plugin_name = 'headless';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_redirect_hooks();
		$this->define_api_hooks();
		$this->define_shortcode_hooks();
		$this->define_post_preview_hooks();
		$this->define_gutenberg_hooks();
		// $this->define_admin_hooks();
		// $this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Headless_Loader. Orchestrates the hooks of the plugin.
	 * - Headless_i18n. Defines internationalization functionality.
	 * - Headless_Admin. Defines all hooks for the admin area.
	 * - Headless_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-i18n.php';

		/**
		 * The class responsible for redirecting stuff and rewriting permalinks to the front end
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-redirects.php';

		/**
		 * The class responsible for extending and modifying REST API responses
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-rest-api.php';

		/**
		 * The class responsible for handling shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-shortcodes.php';

		/**
		 * The class responsible for handling post previews
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-post-previews.php';

		/**
		 * The class responsible for handling Gutenberg data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-headless-gutenberg.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-headless-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-headless-public.php';

		$this->loader = new Headless_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Headless_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Headless_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain', 20 );

	}


	/**
	 * Register all of the hooks related to redirects
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_redirect_hooks() {

		$redirects = new Headless_Redirects();

		$this->loader->add_filter( 'post_link', $redirects, 'change_permalink');
		$this->loader->add_filter( 'post_type_link', $redirects, 'change_permalink');
		$this->loader->add_filter( 'page_link', $redirects, 'change_permalink');
		$this->loader->add_filter( 'author_link', $redirects, 'change_permalink');
		$this->loader->add_filter( 'option_home', $redirects, 'change_sitemap_index_url', 10, 1 );
		$this->loader->add_filter( 'wpseo_sitemap_url', $redirects, 'change_yoast_sitemap_url', 10, 2 );
		$this->loader->add_action('template_redirect', $redirects, 'redirect_check');
		$this->loader->add_action('wp_login', $redirects, 'set_logged_in_cookie');

	}

	/**
	 * Register all of the hooks related to the JSON API
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_api_hooks() {

		$rest_api = new Headless_Rest_Api();
		$this->loader->add_action( 'rest_api_init', $rest_api, 'add_seo_data', 90);
		$this->loader->add_filter( 'headless_seo_post_types', $rest_api, 'custom_seo_post_types');
		$this->loader->add_action( 'rest_api_init', $rest_api, 'add_block_data', 20);
	}

	/**
	 * Register all of the hooks related to redirects
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcode_hooks() {
		remove_filter('the_content', 'wptexturize');
		$shortcodes = new Headless_Shortcodes();
		$this->loader->add_filter( 'the_content', $shortcodes, 'whitelist_shortcodes' );

	}

	/**
	 * Register all of the hooks related to redirects
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_post_preview_hooks() {

		$post_preview = new Headless_Post_Previews();
		$this->loader->add_filter( 'preview_post_link', $post_preview, 'frontend_preview_link', 10, 2 );
		$this->loader->add_filter( 'preview_page_link', $post_preview, 'frontend_preview_link', 10, 2 );
		$this->loader->add_filter( 'page_template', $post_preview, 'preview_page_template', 10, 1 );
		$this->loader->add_filter( 'rest_prepare_revision', $post_preview, 'add_acf_to_revision', 10, 2 );

	}

	/**
	 * Register all of the hooks related to Gutenberg
	 *
	 * @since    1.4.0
	 * @access   private
	 */
	private function define_gutenberg_hooks() {

		$gutes = new Headless_Gutenberg();
		$this->loader->add_action('post_updated', $gutes, 'save_block_data', 10, 3);

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	// private function define_admin_hooks() {
	//
	// 	$plugin_admin = new Headless_Admin( $this->get_plugin_name(), $this->get_version() );
	//
	// 	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
	// 	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	//
	// }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	// private function define_public_hooks() {
	//
	// 	$plugin_public = new Headless_Public( $this->get_plugin_name(), $this->get_version() );
	//
	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	//
	// }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Headless_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
