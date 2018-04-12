<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alephsf.com
 * @since             1.0.8
 * @package           Headless
 *
 * @wordpress-plugin
 * Plugin Name:       Headless
 * Plugin URI:        https://alephsf.com
 * Description:       This plugin allows WordPress to serve as a headless API, essentially removing the theme system.
 * Version:           1.0.8
 * Author:            Matt Glaser
 * Author URI:        https://alephsf.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       headless
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.8' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-headless-activator.php
 */
function activate_headless() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-headless-activator.php';
	Headless_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-headless-deactivator.php
 */
function deactivate_headless() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-headless-deactivator.php';
	Headless_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_headless' );
register_deactivation_hook( __FILE__, 'deactivate_headless' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-headless.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_headless() {
	if( defined('HEADLESS_FRONTEND_URL') ){
		$plugin = new Headless();
		$plugin->run();
	} else {
		add_action('admin_notices', 'headless_no_frontend_url_constant');
	}
}
run_headless();

function headless_no_frontend_url_constant() {
  printf(
    '<div class="error"><p>%s</p></div>',
    __('Headless could not be instantiated because the HEADLESS_FRONTEND_URL constant is not set.')
  );
}
