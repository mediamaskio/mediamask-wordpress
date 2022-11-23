<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Mediamask
 *
 * @wordpress-plugin
 * Plugin Name:       Mediamask
 * Plugin URI:        https://mediamask.io/
 * Description:       Generate automated Open Graph images on your Wordpress Website.
 * Version:           1.0.0
 * Author:            Mediamask
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mediamask
 * Domain Path:       /languages
 */

require_once(dirname(__FILE__) . '/vendor/autoload.php');

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MEDIAMASK_VERSION', '1.0.15' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mediamask-activator.php
 */
function activate_mediamask() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mediamask-activator.php';
	Mediamask_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mediamask-deactivator.php
 */
function deactivate_mediamask() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mediamask-deactivator.php';
	Mediamask_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mediamask' );
register_deactivation_hook( __FILE__, 'deactivate_mediamask' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mediamask.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mediamask() {

	$plugin = new Mediamask();
	$plugin->run();

}
run_mediamask();
