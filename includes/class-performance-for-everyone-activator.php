<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wppfe.com
 * @since      1.0.0
 *
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/includes
 * @author     wppfe <info@wppfe.com>
 */
class Performance_For_Everyone_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'wppfep_performance_results';
		$table_name_db   = $wpdb->prefix . 'wppfep_db_measurements';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        url varchar(255) NOT NULL,
        mobile_score int(3) NOT NULL,
        desktop_score int(3) NOT NULL,
        mobile_assessment varchar(20) NOT NULL,
        desktop_assessment varchar(20) NOT NULL,
        mobile_failed_audits text NOT NULL,
        desktop_failed_audits text NOT NULL,
        checked_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        INDEX url_idx (url),
        INDEX checked_at_idx (checked_at)
    ) $charset_collate;";

		$sql2 = "CREATE TABLE $table_name_db (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        checked_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        db_size float NOT NULL,
        num_posts int NOT NULL,
        num_comments int NOT NULL,
        num_users int NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		dbDelta( $sql2 );
	}

}
