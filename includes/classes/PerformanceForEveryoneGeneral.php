<?php

class PerformanceForEveryoneGeneral {
	function __construct() {

		add_action('update_option_performance_for_everyone_general_options', [$this, 'wppfep_handle_general_options_update'], 10, 2);
	}

	public function wppfep_handle_general_options_update($old_value, $new_value){

		if ($new_value['disable_wp_cron'] === 'disabled' || $new_value['disable_wp_cron'] === 'enabled') {
			$this->wppfe_disable_wp_cron();
		}

		if($new_value['gzip_compression'] === 'disabled' || $new_value['gzip_compression'] === 'enabled'){
			$this->handle_gzip_compression();
		}

	}

	public function handle_gzip_compression() {
		$options     = get_option( 'performance_for_everyone_general_options' );
		$gzip_status = $options['gzip_compression'] ?? 'disabled';

		if ( $gzip_status === 'enabled' ) {
			if ( ! ini_get( 'zlib.output_compression' ) ) {
				ini_set( 'zlib.output_compression', 'On' );
				ini_set( 'zlib.output_compression_level', '6' );
			}
			$this->wppfe_gzip_compression_htaccess('add');
		} else {
			if ( ini_get( 'zlib.output_compression' ) ) {
				ini_set( 'zlib.output_compression', 'Off' );
			}
			$this->wppfe_gzip_compression_htaccess('remove');
		}
	}

	public function remove_emojicons() {
		$options         = get_option( 'performance_for_everyone_general_options' );
		$emojies_enabled = $options['wp_emojies'] ?? 'disabled';
		if ( $emojies_enabled === 'disabled' ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );

			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );

			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

			add_filter( 'tiny_mce_plugins', function ( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				}

				return array();
			} );

			add_filter( 'emoji_svg_url', '__return_false' );
		}
	}

	private function wppfe_gzip_compression_htaccess($action) {
		$htaccess_file = ABSPATH . '.htaccess';

		$gzip_rules = "# --- Performance For Everyone WPPFE gzip ---
<IfModule mod_deflate.c>
# Compress HTML, CSS, JavaScript, Text, XML, and fonts
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
AddOutputFilterByType DEFLATE application/x-font
AddOutputFilterByType DEFLATE application/x-font-opentype
AddOutputFilterByType DEFLATE application/x-font-otf
AddOutputFilterByType DEFLATE application/x-font-truetype
AddOutputFilterByType DEFLATE application/x-font-ttf
AddOutputFilterByType DEFLATE application/x-javascript
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE font/opentype
AddOutputFilterByType DEFLATE font/otf
AddOutputFilterByType DEFLATE font/ttf
AddOutputFilterByType DEFLATE image/svg+xml
AddOutputFilterByType DEFLATE image/x-icon
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/javascript
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/xml
</IfModule>
# --- Performance For Everyone WPPFE gzip END ---";

		if (is_writable($htaccess_file)) {
			// Read the current content of .htaccess
			$htaccess_content = file_get_contents($htaccess_file);

			// Define the start and end markers
			$start_marker = "# --- Performance For Everyone WPPFE gzip ---";
			$end_marker = "# --- Performance For Everyone WPPFE gzip END ---";

			if ($action === 'add') {
				// Add the GZIP rules if they are not already in the file
				if (strpos($htaccess_content, $start_marker) === false) {
					// Append the GZIP rules to the .htaccess file
					$htaccess_content .= "\n" . $gzip_rules;
				} else {
					// If the block exists, update it
					$htaccess_content = preg_replace(
						"/".preg_quote($start_marker, '/').".*?".preg_quote($end_marker, '/')."/s",
						$gzip_rules,
						$htaccess_content
					);
				}
			} elseif ($action === 'remove') {
				// Remove the GZIP rules from the .htaccess file
				if (strpos($htaccess_content, $start_marker) !== false && strpos($htaccess_content, $end_marker) !== false) {
					$htaccess_content = preg_replace(
						"/".preg_quote($start_marker, '/').".*?".preg_quote($end_marker, '/')."/s",
						'',
						$htaccess_content
					);
				}
			}

			if (file_put_contents($htaccess_file, $htaccess_content)) {

			} else {
				error_log('Failed to write changes to .htaccess.');
			}
		} else {
			error_log('.htaccess file is not writable. Please check file permissions.');
		}
	}

	private function wppfe_disable_wp_cron() {
		$options = get_option('performance_for_everyone_general_options');
		$disable_cron = $options['disable_wp_cron'] ?? 'disabled';

		$wp_config_path = ABSPATH . 'wp-config.php';

		// Ensure wp-config.php is writable
		if (is_writable($wp_config_path)) {
			// Read the current content of wp-config.php
			$wp_config_content = file_get_contents($wp_config_path);

			// Define the markers for your plugin's custom code
			$start_marker = "// --- Performance For Everyone WPPFE ---";
			$end_marker = "// --- Performance For Everyone WPPFE END ---";

			// New line to define DISABLE_WP_CRON
			$new_cron_line = $disable_cron === 'enabled'
				? "define('DISABLE_WP_CRON', false);"
				: "define('DISABLE_WP_CRON', true);";

			// Construct the new block with markers
			$new_code_block =  $start_marker . "\n" . $new_cron_line . "\n" . $end_marker;

			// Check if the block already exists in wp-config.php
			if (strpos($wp_config_content, $start_marker) !== false && strpos($wp_config_content, $end_marker) !== false) {
				// Replace the existing block
				$wp_config_content = preg_replace(
					"/".preg_quote($start_marker, '/').".*?".preg_quote($end_marker, '/')."/s",
					$new_code_block,
					$wp_config_content
				);
			} else {
				// Insert the block before the "/* That's all, stop editing!" line
				if (strpos($wp_config_content, "/* That's all, stop editing!") !== false) {
					$wp_config_content = str_replace(
						"/* That's all, stop editing!",
						$new_code_block . "/* That's all, stop editing!",
						$wp_config_content
					);
				} else {
					// If the marker isn't found, append the block to the end
					$wp_config_content .= $new_code_block;
				}
			}

			// Write the updated content back to wp-config.php
			if (file_put_contents($wp_config_path, $wp_config_content)) {
				error_log('Successfully updated wp-config.php for WP Cron settings.');
			} else {
				error_log('Failed to write changes to wp-config.php.');
			}
		} else {
			error_log('wp-config.php is not writable. Please check file permissions.');
		}
	}

}
