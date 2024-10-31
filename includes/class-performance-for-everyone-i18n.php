<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wppfe.com
 * @since      1.0.0
 *
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 * @author     wppfe <info@wppfe.com>
 */
class Performance_For_Everyone_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'performance-for-everyone',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
