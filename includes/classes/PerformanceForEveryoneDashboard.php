<?php

class PerformanceForEveryoneDashboard {
	function __construct() {
	}

	public function wppfep_system_requirements() {
		ob_start(); // Start output buffering

		// PHP Version Check
		$php_version = PHP_VERSION;
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">PHP Version: <span class="text-gray-800">' . esc_html( $php_version ) . '</span>';

		if ( version_compare( $php_version, '7.4', '<' ) ) {
			echo ' <span class="text-red-600">(Outdated, upgrade recommended)</span>';
			echo '<p class="text-sm mt-1">Your PHP version is outdated. The recommended version is 7.4 or higher. Using an outdated version can cause compatibility issues and expose your site to security vulnerabilities. Please contact your hosting provider to upgrade your PHP version.</p>';
		} else {
			echo ' <span class="text-green-600">(Up-to-date)</span>';
		}
		echo '</p>';
		echo '</div>';

		// WordPress Version Check
		$wp_version = get_bloginfo( 'version' );
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">WordPress Version: <span class="text-gray-800">' . esc_html( $wp_version ) . '</span>';
		if ( version_compare( $wp_version, $this->wppfep_get_latest_wordpress_version(), '<' ) ) {
			echo ' <span class="text-red-600">(Outdated, upgrade recommended)</span>';
			echo '<p class="text-sm mt-1">Your WordPress version is outdated. The recommended version is 6.0 or higher. Updating WordPress ensures you have the latest features, improvements, and security patches. Backup your site and update it via the WordPress admin dashboard.</p>';
		} else {
			echo ' <span class="text-green-600">(Up-to-date)</span>';
		}
		echo '</p>';
		echo '<span class="text-sm m-0 text-gray-600">Latest Wordpress version:' . $this->wppfep_get_latest_wordpress_version() . '</span>';
		echo '</div>';

		// Database Version Check
		global $wpdb;
		$db_version = $wpdb->db_version();
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">Database Version: <span class="text-gray-800">' . esc_html( $db_version ) . '</span>';
		if ( version_compare( $db_version, '5.7', '<' ) ) {
			echo ' <span class="text-red-600">(Outdated, upgrade recommended)</span>';
			echo '<p class="text-sm mt-1">Your database version is outdated. The recommended version is MySQL 5.7 or higher. An outdated database version can slow down your site and cause compatibility issues. Please contact your hosting provider to upgrade the database version.</p>';
		} else {
			echo ' <span class="text-green-600">(Up-to-date)</span>';
		}
		echo '</p>';
		echo '</div>';

		// Server Info
		$server_software = $_SERVER['SERVER_SOFTWARE'];
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">Server Software: <span class="text-gray-800">' . esc_html( $server_software ) . '</span></p>';
		echo '<p class="text-sm mt-1">Your server software determines how your site is hosted. Common servers include Apache, Nginx, and LiteSpeed. Ensure that your server is configured for optimal WordPress performance.</p>';
		echo '</div>';

		// Collect the output and return it
		$output = ob_get_clean();

		return $output;
	}

	public function wppfep_system_performance_check() {
		ob_start(); // Start output buffering

		// Memory Limit
		$memory_limit = ini_get( 'memory_limit' );
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">Memory Limit: <span class="text-gray-800">' . esc_html( $memory_limit ) . '</span>';
		if ( intval( $memory_limit ) < 128 ) {
			echo ' <span class="text-red-600">(Low, increase recommended)</span>';
			echo '<p class="text-sm mt-1">The recommended memory limit for WordPress is 128M or higher. A low memory limit can lead to memory exhaustion errors. To increase it, add <code>define(\'WP_MEMORY_LIMIT\', \'256M\');</code> to your <code>wp-config.php</code> file, or contact your hosting provider for assistance.</p>';
		} else {
			echo ' <span class="text-green-600">(Sufficient)</span>';
		}
		echo '</p>';
		echo '</div>';

		// File Permissions Check
		$wp_content_dir      = WP_CONTENT_DIR;
		$uploads_dir         = wp_upload_dir()['basedir'];
		$wp_content_writable = is_writable( $wp_content_dir );
		$uploads_writable    = is_writable( $uploads_dir );

		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">File Permissions:</p>';
		echo '<ul class="list-disc pl-5">';
		echo '<li>wp-content directory: ' . ( $wp_content_writable ? '<span class="text-green-600">Writable</span>' : '<span class="text-red-600">Not Writable</span>' );
		if ( ! $wp_content_writable ) {
			echo '<p class="text-sm mt-1">The <code>wp-content</code> directory needs to be writable for WordPress to function correctly. Update the permissions using an FTP client or contact your hosting provider.</p>';
		}
		echo '</li>';
		echo '<li>uploads directory: ' . ( $uploads_writable ? '<span class="text-green-600">Writable</span>' : '<span class="text-red-600">Not Writable</span>' );
		if ( ! $uploads_writable ) {
			echo '<p class="text-sm mt-1">The <code>uploads</code> directory needs to be writable for media uploads. Update the permissions using an FTP client or contact your hosting provider.</p>';
		}
		echo '</li>';
		echo '</ul>';
		echo '</div>';

		// SSL Status Check
		$is_https = is_ssl();
		echo '<div class="mb-4">';
		echo '<p class="text-lg font-semibold m-0">SSL Status: ';
		echo $is_https ? '<span class="text-green-600">Enabled</span>' : '<span class="text-red-600">Not Enabled</span>';
		if ( ! $is_https ) {
			echo '<p class="text-sm mt-1">Your website is not using HTTPS. It is recommended to use an SSL certificate to secure data transmission and improve SEO. Please install an SSL certificate and configure WordPress to use HTTPS.</p>';
		}
		echo '</p>';
		echo '</div>';

		// PHP Opcache Status
		$opcache_enabled = ini_get( 'opcache.enable' );
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">PHP Opcache: ';
		echo( $opcache_enabled ? '<span class="text-green-600">Enabled</span>' : '<span class="text-red-600">Not Enabled</span>' );
		if ( ! $opcache_enabled ) {
			echo '<p class="text-sm mt-1">PHP Opcache is not enabled. Enabling Opcache can greatly improve the performance of your website. Contact your hosting provider to enable PHP Opcache in the server settings.</p>';
		}
		echo '</p>';
		echo '</div>';

		// WP Cron Status Check
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">WordPress Cron Status: ';
		if ( ! defined( 'DISABLE_WP_CRON' ) || DISABLE_WP_CRON === false ) {
			echo '<span class="text-red-600">Enabled</span>';
			echo '<p class="text-sm mt-1">The WordPress cron is currently enabled. For better performance, it is recommended to disable the WordPress cron. You can disable it through our <a href="' . esc_url( admin_url( 'admin.php?page=general-optimization' ) ) . '" class="text-blue-600 underline">General Settings</a> and save the changes.</p>';
		} else {
			echo '<span class="text-green-600">Disabled</span>';
		}
		echo '</p>';
		echo '</div>';

		// GZIP Compression Check
		$gzip_enabled = ini_get( 'zlib.output_compression' ) || $this->check_htaccess_for_gzip();
		echo '<div class="mb-1">';
		echo '<p class="text-lg font-semibold m-0">GZIP Compression: ';
		echo $gzip_enabled ? '<span class="text-green-600">Enabled</span>' : '<span class="text-red-600">Not Enabled</span>';
		if ( ! $gzip_enabled ) {
			echo '<p class="text-sm mt-1">GZIP compression is not enabled. Enabling GZIP can reduce file sizes and improve load times. You can enable it in <a href="admin.php?page=general-optimization">general settings</a> tab.</p>';
		}
		echo '</p>';
		echo '</div>';

		// Collect the output and return it
		$output = ob_get_clean();

		return $output;
	}

	public function wppfep_database_optimization_check() {
		global $wpdb;
		ob_start();

		// Post Revisions Check
		$post_revisions = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'" );
		echo '<div class="mb-4">';
		echo '<p class="text-lg font-semibold m-0">Post Revisions: <span class="text-gray-800">' . esc_html( $post_revisions ) . '</span>';
		if ( $post_revisions > 100 ) {
			echo ' <span class="text-red-600">(High, consider reducing)</span>';
			echo '<p class="text-sm mt-1">You have a high number of post revisions (' . esc_html( $post_revisions ) . '). Revisions can slow down your database. Consider limiting revisions by adding <code>define(\'WP_POST_REVISIONS\', 5);</code> to your <code>wp-config.php</code> file, or use a plugin to clean them up.</p>';
		} else {
			echo ' <span class="text-green-600">(Within normal range)</span>';
		}
		echo '</p>';
		echo '</div>';

		// Autoloaded Data Check in wp_options
		$autoload_size = $wpdb->get_var( "SELECT SUM(LENGTH(option_value)) FROM $wpdb->options WHERE autoload = 'yes'" );
		echo '<div class="mb-4">';
		echo '<p class="text-lg font-semibold m-0">Autoloaded Data: <span class="text-gray-800">' . size_format( $autoload_size ) . '</span>';
		if ( $autoload_size > 800000 ) { // Approximately 800KB
			echo ' <span class="text-red-600">(Large, optimization recommended)</span>';
			echo '<p class="text-sm mt-1">Your autoloaded data size is large (' . size_format( $autoload_size ) . '). Excessive autoloaded data can slow down page loads. Consider removing unnecessary autoloaded options from the <code>wp_options</code> table. Use a plugin like <a href="https://wordpress.org/plugins/wp-optimize/" class="text-blue-600">WP-Optimize</a> for optimization.</p>';
		} else {
			echo ' <span class="text-green-600">(Optimal)</span>';
		}
		echo '</p>';
		echo '</div>';

		// Collect the output and return it
		$output = ob_get_clean();

		return $output;
	}

	private function wppfep_get_latest_wordpress_version() {
		// WordPress API URL for latest version info
		$api_url = 'https://api.wordpress.org/core/version-check/1.7/';

		// Fetch the API response
		$response = wp_remote_get( $api_url );

		// Check for errors
		if ( is_wp_error( $response ) ) {
			return 'Unable to retrieve WordPress version. Please try again later.';
		}

		// Decode the JSON response
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		// Get the latest version from the response
		if ( ! empty( $data['offers'][0]['version'] ) ) {
			return $data['offers'][0]['version'];
		} else {
			return 'Unable to determine the latest WordPress version.';
		}
	}

	public function wppfep_get_plugins_needing_update() {
		$update_plugins = get_site_transient( 'update_plugins' );

		if ( empty( $update_plugins->response ) ) {
			return '<p>All plugins are up-to-date.</p>';
		}

		$output = '<ul class="list-disc pl-5">';

		// Loop through the plugins that need updates
		foreach ( $update_plugins->response as $plugin_slug => $plugin_data ) {
			$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;

			// Get the current version of the plugin
			if ( file_exists( $plugin_path ) ) {
				$plugin_info     = get_plugin_data( $plugin_path );
				$current_version = $plugin_info['Version'];
			} else {
				$current_version = 'Unknown';
			}

			$plugin_name    = $plugin_data->slug;
			$latest_version = $plugin_data->new_version;

			// Determine the type of update (major, minor, patch)
			$update_type_icon = '';
			if ( version_compare( $current_version, $latest_version, '<' ) ) {
				$current_version_parts = explode( '.', $current_version );
				$latest_version_parts  = explode( '.', $latest_version );

				if ( $current_version_parts[0] !== $latest_version_parts[0] ) {
					// Major update
					$update_type_icon = '<i class="fas fa-exclamation-circle text-red-600" title="Major Update: Critical changes and new features."></i>';
				} elseif ( $current_version_parts[1] !== $latest_version_parts[1] ) {
					// Minor update
					$update_type_icon = '<i class="fas fa-exclamation-triangle text-yellow-600" title="Minor Update: New features or enhancements."></i>';
				} else {
					// Patch update
					$update_type_icon = '<i class="fas fa-wrench text-blue-600" title="Patch Update: Bug fixes and improvements."></i>';
				}
			}

			// Generate the list item with version details
			$output .= '<li>';
			$output .= $update_type_icon . ' '; // Include the icon
			$output .= esc_html( $plugin_name ) . ' - ';
			$output .= '<span style="color: red;">Current: ' . esc_html( $current_version ) . '</span>, ';
			$output .= '<span style="color: green;">Latest: ' . esc_html( $latest_version ) . '</span>';
//			$output .= ' <a href="#" class="text-blue-600 update-plugin" data-plugin="' . esc_attr($plugin_slug) . '">Update Now</a>';
			$output .= '</li>';
		}

		$output .= '</ul>';

		return $output;
	}

	private function check_htaccess_for_gzip() {
		$htaccess_file = ABSPATH . '.htaccess';
		if (file_exists($htaccess_file)) {
			$htaccess_content = file_get_contents($htaccess_file);
			if (strpos($htaccess_content, 'mod_deflate') !== false) {
				return true;
			}
		}
		return false;
	}


}
