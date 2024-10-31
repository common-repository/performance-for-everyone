<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wppfe.com
 * @since      1.0.0
 *
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 * @author     wppfe <info@wppfe.com>
 */
class Performance_For_Everyone_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_performance_results';

		$wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS %s", $table_name));
	}

}
