<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wppfe.com
 * @since             1.0.0
 * @package           Performance_For_Everyone
 *
 * @wordpress-plugin
 * Plugin Name:       Performance For Everyone
 * Plugin URI:        https://wppfe.com
 * Description:       🚀 Performance for Everyone is the ultimate plugin for optimizing your WordPress website's speed and performance. It's built to empower website owners, developers, and agencies to boost their site's speed by cleaning up databases, optimizing JavaScript, managing caching, managing images and much more.
 * Version:           1.3.7
 * Author:            wppfe
 * Author URI:        https://wppfe.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       performance-for-everyone
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
define( 'PERFORMANCE_FOR_EVERYONE_VERSION', '1.3.7' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-performance-for-everyone-activator.php
 */
function wppfepe_activate_performance_for_everyone() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-performance-for-everyone-activator.php';
	$current_time = current_time('mysql');
	update_option('wppfe_performancefe_activation_time', $current_time);
	Performance_For_Everyone_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-performance-for-everyone-deactivator.php
 */
function wppfepe_deactivate_performance_for_everyone() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-performance-for-everyone-deactivator.php';
	delete_option('wppfe_performancefe_activation_time');
	Performance_For_Everyone_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wppfepe_activate_performance_for_everyone' );
register_deactivation_hook( __FILE__, 'wppfepe_deactivate_performance_for_everyone' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-performance-for-everyone.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wppfepe_run_performance_for_everyone() {

	$plugin = new Performance_For_Everyone();
	$plugin->run();

}
wppfepe_run_performance_for_everyone();
