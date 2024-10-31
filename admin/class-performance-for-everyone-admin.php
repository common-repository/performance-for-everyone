<?php

class Performance_For_Everyone_Admin {


	private $plugin_name;
	private $version;

	private $wppfepfe_caching;
	private $wppfepfe_database;
	private $wppfepfe_general;
	private $wppfepfe_javascript;
	private $wppfepfe_css;
	private $wppfepfe_dashboard;
	private $wppfepfe_images;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->wppfepfe_caching    = new PerformanceForEveryoneCache();
		$this->wppfepfe_database   = new PerformanceForEveryoneDatabase();
		$this->wppfepfe_general    = new PerformanceForEveryoneGeneral();
		$this->wppfepfe_javascript = new PerformanceForEveryoneJavascript();
		$this->wppfepfe_css        = new PerformanceForEveryoneStyling();
		$this->wppfepfe_dashboard  = new PerformanceForEveryoneDashboard();
		$this->wppfepfe_images     = new PerformanceForEveryoneImages();
	}

	public function enqueue_styles() {
		$screen = get_current_screen();
		if ( $this->is_plugin_page( $screen ) ) {
			wp_enqueue_style( 'tailwind',
				plugin_dir_url( __FILE__ ) . 'css/integrations/tailwind.min.css',
				array(),
				null );
			wp_enqueue_style( 'bootstrap-css',
				plugin_dir_url( __FILE__ ) . 'css/integrations/bootstrap.min.css',
				array(),
				null );
			wp_enqueue_style( 'font-awesome',
				plugin_dir_url( __FILE__ ) . 'css/integrations/font-awesome/css/all.min.css',
				array(),
				null );
			wp_enqueue_style( $this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/performance-for-everyone-admin.css',
				array(),
				time(),
				'all' );
			wp_enqueue_style( $this->plugin_name . '-faq-accordion-css',
				plugin_dir_url( __FILE__ ) . 'css/faq-accordion.css',
				array(),
				time(),
				'all' );
		}
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( $this->is_plugin_page( $screen ) ) {
			wp_enqueue_script( 'bootstrap',
				plugin_dir_url( __FILE__ ) . 'js/integrations/bootstrap.min.js',
				array( 'jquery' ),
				null,
				true );
			wp_enqueue_script( 'chartjs',
				plugin_dir_url( __FILE__ ) . 'js/integrations/chart.min.js',
				array( 'jquery' ),
				null,
				true );
			wp_enqueue_script( 'font-awesome-js',
				plugin_dir_url( __FILE__ ) . 'css/integrations/font-awesome/js/all.min.js',
				array( 'jquery' ),
				null,
				true );
			wp_enqueue_script( 'sweetalert',
				plugin_dir_url( __FILE__ ) . 'js/integrations/sweetalert.js',
				array( 'jquery' ),
				null,
				true );
			wp_enqueue_script( $this->plugin_name . '-main',
				plugin_dir_url( __FILE__ ) . 'js/performance-for-everyone-admin.js',
				array( 'jquery' ),
				time(),
				false );
			wp_localize_script( $this->plugin_name . '-main', 'wppfep', array(
				'nonce' => wp_create_nonce( 'wppfep_nonce' ) // Create and pass the nonce
			) );

			wp_enqueue_script( $this->plugin_name . '-utilities',
				plugin_dir_url( __FILE__ ) . 'js/utilities.js',
				array( 'jquery' ),
				time(),
				false );


			if ( $this->is_plugin_page( $screen, 'performance_page_caching-settings' ) ) {
				wp_enqueue_script( $this->plugin_name . '-cache_display',
					plugin_dir_url( __FILE__ ) . 'js/cacheDisplay.js',
					array( 'jquery' ),
					time(),
					false );
				wp_enqueue_script( $this->plugin_name . '-cache_actions',
					plugin_dir_url( __FILE__ ) . 'js/cacheActions.js',
					array( 'jquery' ),
					time(),
					false );
			}

			if ( $this->is_plugin_page( $screen, 'performance_page_performance-dashboard' ) ) {
				wp_enqueue_script( $this->plugin_name . '-performance',
					plugin_dir_url( __FILE__ ) . 'js/performanceData.js',
					array( 'jquery' ),
					time(),
					false );
			}

			if ( $this->is_plugin_page( $screen, 'performance_page_database-optimization' ) ) {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( $this->plugin_name . '-dboperations',
					plugin_dir_url( __FILE__ ) . 'js/databaseOperations.js',
					array( 'jquery' ),
					time(),
					false );
			}

			if ( $this->is_plugin_page( $screen, 'performance_page_performance-dashboard' ) ) {
				wp_enqueue_script( $this->plugin_name . '-plugin-update',
					plugin_dir_url( __FILE__ ) . 'js/pluginUpdate.js',
					array( 'jquery' ),
					time(),
					false );

				wp_enqueue_script( 'wppfep-delete-performance-script',
					plugin_dir_url( __FILE__ ) . 'js/deletePerformanceResult.js',
					[ 'jquery' ],
					null,
					true );
				wp_localize_script( 'wppfep-delete-performance-script', 'wppfep_ajax', [
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'delete_result_nonce' ),
				] );
			}


			wp_enqueue_script( $this->plugin_name . '-faq-accordion',
				plugin_dir_url( __FILE__ ) . 'js/faq-accordion.js',
				array( 'jquery' ),
				time(),
				false );
		}
	}

	private function is_plugin_page( $screen, $page_slug = '' ) {

        $plugin_pages = array(
			'toplevel_page_' . $this->plugin_name,
			'performance_page_performance-dashboard',
			'performance_page_caching-settings',
			'performance_page_database-optimization',
			'performance_page_general-optimization',
			'performance_page_javascript-optimization',
			'performance_page_styling-optimization',
			'performance_page_images-optimization',
            'performance_page_wppfe-help'
		);

		if ( $page_slug ) {
			return $screen->id === $page_slug;
		}

		return in_array( $screen->id, $plugin_pages );
	}

	public function wppfep_add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->plugin_name . '">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function wppfep_update_plugin_ajax() {
		if ( ! check_ajax_referer( 'wppfep_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => 'Nonce verification failed.' ] );
		}

		// Get the plugin slug
		$plugin_slug = isset( $_POST['plugin'] ) ? sanitize_text_field( $_POST['plugin'] ) : '';

		if ( empty( $plugin_slug ) ) {
			wp_send_json_error( [ 'message' => 'Invalid plugin specified.' ] );
		}

		// Include the necessary files for plugin updates
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Perform the plugin update
		$upgrader = new Plugin_Upgrader();
		$result   = $upgrader->upgrade( $plugin_slug );

		// Check if the result is a WP_Error or if it explicitly returns false
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => 'Plugin update failed: ' . $result->get_error_message() ] );
		} elseif ( $result === false ) {
			// Check if the plugin was updated despite the false response
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug, false, false );
			if ( ! empty( $plugin_data['Version'] ) ) {
				wp_send_json_success( [ 'message' => 'Plugin updated successfully to version ' . $plugin_data['Version'] . '.' ] );
			} else {
				wp_send_json_error( [ 'message' => 'Plugin update failed for an unknown reason.' ] );
			}
		} else {
			// Assume success if the result is not an error or false
			wp_send_json_success( [ 'message' => 'Plugin updated successfully.' ] );
		}
	}

	public function wppfep_check_plugin_update_status() {
		if ( ! check_ajax_referer( 'wppfep_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => 'Nonce verification failed.' ] );
		}

		$plugin_slug = isset( $_POST['plugin'] ) ? sanitize_text_field( $_POST['plugin'] ) : '';

		if ( empty( $plugin_slug ) ) {
			wp_send_json_error( [ 'message' => 'Invalid plugin specified.' ] );
		}

		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug, false, false );
		if ( ! empty( $plugin_data['Version'] ) ) {
			wp_send_json_success( [ 'message' => 'Plugin updated successfully to version ' . $plugin_data['Version'] . '.' ] );
		} else {
			wp_send_json_error( [ 'message' => 'Plugin update status could not be confirmed.' ] );
		}
	}


	public function wppfep_add_plugin_admin_menu() {
		$icon_url = plugin_dir_url( __FILE__ ) . 'images/clock.svg';
		add_menu_page(
			'Performance For Everyone Settings',
			'Performance',
			'manage_options',
			$this->plugin_name,
			array( $this, 'wppfep_display_plugin_admin_dashboard' ),
			'dashicons-clock',
			6
		);

		add_submenu_page(
			$this->plugin_name,
			'Database Optimization',
			'Database Optimization',
			'manage_options',
			'database-optimization',
			array( $this, 'wppfep_display_database_optimization_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Caching Settings',
			'Caching Settings',
			'manage_options',
			'caching-settings',
			array( $this, 'wppfep_display_caching_settings_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'General',
			'General Settings',
			'manage_options',
			'general-optimization',
			array( $this, 'wppfep_display_general_settings_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Speed Test',
			'Speed Test',
			'manage_options',
			'performance-dashboard',
			array( $this, 'wppfep_display_performance_dashboard_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Javascript Optimization',
			'JavaScript (JS)',
			'manage_options',
			'javascript-optimization',
			array( $this, 'wppfep_display_js_optimization_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Images Settings',
			'Images Settings',
			'manage_options',
			'images-optimization',
			array( $this, 'wppfep_display_images_optimization_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Help',
			'Help',
			'manage_options',
			'wppfe-help',
			array( $this, 'wppfep_display_wppfe_help' )
		);

//		add_submenu_page(
//			$this->plugin_name,
//			'Stylingh Optimization',
//			'Styling (CSS)',
//			'manage_options',
//			'styling-optimization',
//			array( $this, 'wppfep_display_css_optimization_page' )
//		);
	}

	public function wppfep_display_database_optimization_page() {
		include_once 'partials/performance-for-everyone-database-optimization.php';
	}

	public function wppfep_display_js_optimization_page() {
		include_once 'partials/performance-for-everyone-js-optimization.php';
	}

	public function wppfep_display_wppfe_help() {
		?>
        <section class="grid grid-cols-5 gap-6 p-2 mb-6 grid-reset-height">
            <div class="col-span-3 col-start-2">
                <div class="bg-blue-50 text-black p-6 rounded-lg">
                    <h2 class="text-3xl font-bold m-0 mb-2">Support / Inquiries / Offers / Help</h2>
                    <p class="text-base text-gray-700 mb-3">
                        We will try to reply asap for you!
                    </p>
                    <div class="wrap">
                        <form method="post" action="">
			                <?php
			                wp_nonce_field( 'wppfep_support_form', 'wppfep_support_nonce' ); ?>
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="wppfep_name">Your Name</label></th>
                                    <td><input type="text" id="wppfep_name" name="wppfep_name" class="regular-text" required/></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="wppfep_email">Your Email</label></th>
                                    <td><input type="email" id="wppfep_email" name="wppfep_email" class="regular-text" required/>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="wppfep_message">Message</label></th>
                                    <td><textarea id="wppfep_message" name="wppfep_message" rows="5" class="large-text"
                                                  required></textarea></td>
                                </tr>
                            </table>
                            <p class="submit">
                                <input type="submit" name="wppfep_submit_support" class="button button-primary"
                                       value="Send Support Request"/>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
		<?php
    }

	public function wppfep_display_images_optimization_page() {
		include_once 'partials/performance-for-everyone-images-optimization.php';
	}

	public function wppfep_display_css_optimization_page() {
		include_once 'partials/performance-for-everyone-css-optimization.php';
	}

	public function wppfep_display_plugin_admin_dashboard() {
		$systemInformation = $this->wppfepfe_dashboard->wppfep_system_requirements();
		$performanceCheck  = $this->wppfepfe_dashboard->wppfep_system_performance_check();
		$databaseCheck     = $this->wppfepfe_dashboard->wppfep_database_optimization_check();
		$pluginsSecurity   = $this->wppfepfe_dashboard->wppfep_get_plugins_needing_update();
		include_once 'partials/performance-for-everyone-admin-display.php';
	}

	public function wppfep_display_caching_settings_page() {
		include_once 'partials/performance-for-everyone-caching-display.php';
	}

	public function wppfep_display_general_settings_page() {
		include_once 'partials/performance-for-everyone-general-display.php';
	}

	public function wppfep_page_caching_callback() {
		?>
        <br>
        <h2 class="text-info">Page Caching Options</h2>
        <hr>
        <div class="mt-4">
            <button id="clear-all-caches-button"
                    class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Clear All Caches
            </button>
        </div>
        <br>
        <div class="grid grid-cols-2 gap-4 mb-4 grid-reset-height">
            <button id="cache-pages-button"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Cache Pages
            </button>
            <button id="clear-pages-cache-button"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Clear Pages Cache
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4 grid-reset-height">
            <button id="cache-posts-button"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Cache Posts
            </button>
            <button id="clear-posts-cache-button"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Clear Posts Cache
            </button>
        </div>
		<?php
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
            <div class="mt-4 grid grid-cols-2 gap-4 grid-reset-height">
                <button id="cache-products-button"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Cache Products
                </button>
                <button id="clear-products-cache-button"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    Clear Products Cache
                </button>
            </div>
		<?php
		endif;
	}

	public function wppfep_display_performance_dashboard_page() {
		include_once 'partials/performance-for-everyone-performance-dashboard-display.php';

		echo '<div class="performance-results">';
		echo '<div class="bg-gray-100 text-black-50 p-6 rounded-lg">';
		echo '<h4 class="text-info">Performance Results</h4>';
		echo '<hr/>';
		echo '<div id="saved-results">';
		echo $this->get_saved_results_html();
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	public function wppfep_register_settings() {
		register_setting( 'performance_for_everyone_options', 'performance_for_everyone_options' );
		add_settings_section(
			'performance_for_everyone_settings_section',
			'General Settings',
			null,
			'performance_for_everyone'
		);
		add_settings_field(
			'example_setting',
			'Example Setting',
			array( $this, 'wppfep_example_setting_callback' ),
			'performance_for_everyone',
			'performance_for_everyone_settings_section'
		);

		register_setting( 'performance_for_everyone_caching_options', 'performance_for_everyone_caching_options' );

		add_settings_section( 'performance_for_everyone_caching_section',
			'',
			null,
			'performance_for_everyone_caching' );

		add_settings_field(
			'page_caching',
			'', // Pass an empty string to disable the title
			array( $this, 'wppfep_page_caching_callback' ),
			'performance_for_everyone_caching',
			'performance_for_everyone_caching_section',
			[ 'class' => 'wppfe-page-caching' ]
		);

		add_settings_section( 'performance_for_everyone_db_section', '', null, 'performance_for_everyone_db' );
		add_settings_field(
			'db_cleanup',
			'Database Cleanup',
			array( $this, 'wppfep_db_cleanup_callback' ),
			'performance_for_everyone_db',
			'performance_for_everyone_db_section'
		);
		add_settings_field(
			'optimize_db_tables',
			'Optimize Database Tables',
			array( $this, 'wppfep_optimize_db_tables_callback' ),
			'performance_for_everyone_db',
			'performance_for_everyone_db_section'
		);
		add_settings_field(
			'cleanup_unused_data',
			'Clean Up Unused Data',
			array( $this, 'wppfep_cleanup_unused_data_callback' ),
			'performance_for_everyone_db',
			'performance_for_everyone_db_section'
		);
		add_settings_field(
			'optimize_autoloaded_data',
			'Optimize Autoloaded Data',
			array( $this, 'wppfep_optimize_autoloaded_data_callback' ),
			'performance_for_everyone_db',
			'performance_for_everyone_db_section'
		);

		register_setting( 'performance_for_everyone_general_options', 'performance_for_everyone_general_options' );
		add_settings_section( 'performance_for_everyone_general_section',
			'',
			null,
			'performance_for_everyone_general' );
		add_settings_field(
			'gzip_compression',
			'GZIP Compression',
			array( $this, 'wppfep_gzip_compression_callback' ),
			'performance_for_everyone_general',
			'performance_for_everyone_general_section'
		);
		add_settings_field(
			'wp_emojies',
			'Default Emojies 😊',
			array( $this, 'wppfep_emojies_callback' ),
			'performance_for_everyone_general',
			'performance_for_everyone_general_section'
		);
		add_settings_field(
			'disable_wp_cron',
			'WordPress Cron',
			array( $this, 'wppfep_disable_cron_callback' ),
			'performance_for_everyone_general',
			'performance_for_everyone_general_section'
		);
		register_setting( 'performance_for_everyone_javascript_options',
			'performance_for_everyone_javascript_options' );
		add_settings_section( 'performance_for_everyone_javascript_section',
			'',
			null,
			'performance_for_everyone_javascript' );
		add_settings_field(
			'jquery_migrate',
			'jQuery Migrate',
			array( $this, 'wppfep_jquery_migrate_callback' ),
			'performance_for_everyone_javascript',
			'performance_for_everyone_javascript_section'
		);
		add_settings_field(
			'embeds',
			'Remove Embeds',
			array( $this, 'wppfep_remove_embeds_callback' ),
			'performance_for_everyone_javascript',
			'performance_for_everyone_javascript_section'
		);
		add_settings_field(
			'defer_javascript',
			'Defer JavaScript',
			array( $this, 'wppfep_defer_javascript_callback' ),
			'performance_for_everyone_javascript',
			'performance_for_everyone_javascript_section'
		);


		register_setting( 'performance_for_everyone_images_options',
			'performance_for_everyone_images_options' );
		add_settings_section( 'performance_for_everyone_images_section',
			'',
			null,
			'performance_for_everyone_images' );
//		add_settings_field(
//			'images_lazy_loading',
//			'Lazy Loading Images',
//			array( $this, 'wppfep_lazy_loading_callback' ),
//			'performance_for_everyone_images',
//			'performance_for_everyone_images_section'
//		);
		add_settings_field(
			'image_preload',
			'Preload Images',
			array( $this, 'wppfep_image_preload_callback' ),
			'performance_for_everyone_images',
			'performance_for_everyone_images_section'
		);

		register_setting( 'performance_for_everyone_styles_options', 'performance_for_everyone_deregistered_styles' );

		add_settings_section( 'performance_for_everyone_styles_section',
			'',
			null,
			'performance_for_everyone_styles' );

		add_settings_field(
			'deregister_styles',
			'Select CSS Files to Deregister on Homepage',
			array( $this, 'wppfep_deregister_styles_callback' ),
			'performance_for_everyone_styles',
			'performance_for_everyone_styles_section'
		);

		add_settings_field(
			'enable_lazy_loading',
			'Enable Lazy Loading',
			array( $this, 'wppfep_enable_lazy_loading_callback' ),
			'performance_for_everyone_styles',
			'performance_for_everyone_styles_section'
		);

        $this->wppfep_handle_support_form();
	}

	function wppfep_handle_support_form() {
		if ( isset( $_POST['wppfep_submit_support'] ) && check_admin_referer( 'wppfep_support_form', 'wppfep_support_nonce' ) ) {

			$name    = sanitize_text_field( $_POST['wppfep_name'] );
			$email   = sanitize_email( $_POST['wppfep_email'] );
			$message = sanitize_textarea_field( $_POST['wppfep_message'] );

			$to      = 'info@wppfe.com';
			$subject = 'Support Request from ' . $name;
			$body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
			$headers = array('Content-Type: text/plain; charset=UTF-8');

			$mail_sent = wp_mail( $to, $subject, $body, $headers );

			if ( $mail_sent ) {
				add_action( 'admin_notices', function() {
					echo '<div class="notice notice-success is-dismissible"><p>Support request sent successfully!</p></div>';
				});
			} else {
				add_action( 'admin_notices', function() {
					echo '<div class="notice notice-error is-dismissible"><p>Failed to send support request. Please try again.</p></div>';
				});
			}
		}
	}

	public function wppfep_db_cleanup_callback() {
		?>
        <div class="">
            <button id="db-cleanup-button"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Optimize
            </button>
            <p class="text-gray-600 mt-2">Regularly clean up the database by removing unnecessary data like post
                revisions, spam comments, and transient options.</p>
        </div>
		<?php
	}

	public function wppfep_cleanup_unused_data_callback() {
		?>
        <div class="">
            <button id="cleanup-unused-data-button"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Optimize
            </button>
            <p class="text-gray-600 mt-2">Regularly clean up orphaned post metadata, term relationships, and other
                unused data to keep the database efficient.</p>
        </div>
		<?php
	}

	public function wppfep_optimize_autoloaded_data_callback() {
		?>
        <div class="">
            <button id="optimize-autoloaded-data-button"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Optimize
            </button>
            <p class="text-gray-600 mt-2">Review and clean up the wp_options table to ensure that only essential data is
                autoloaded.</p>
        </div>
		<?php
	}

	public function wppfep_optimize_db_tables_callback() {
		?>
        <div class="">
            <button id="optimize-db-tables-button"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Optimize
            </button>
            <p class="text-gray-600 mt-2">Run optimization queries to defragment database tables.</p>
        </div>
		<?php
	}


	public function wppfep_gzip_compression_callback() {
		$options     = get_option( 'performance_for_everyone_general_options' );
		$gzip_status = $options['gzip_compression'] ?? '';
		?>
        <div class="mb-4">
            <select id="gzip_compression" name="performance_for_everyone_general_options[gzip_compression]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="disabled" <?php
				selected( $gzip_status, 'disabled' ); ?>>Disabled
                </option>
                <option value="enabled" <?php
				selected( $gzip_status, 'enabled' ); ?>>Enabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Enabling Gzip compression significantly reduces the size of your
                website's files, leading to faster load times and improved user experience (less data sending to user).
                It also decreases bandwidth usage, which can lower hosting costs and enhance your site's SEO
                performance.</p>
        </div>
		<?php
	}

	public function wppfep_emojies_callback() {
		$options = get_option( 'performance_for_everyone_general_options' );
		$emojies = $options['wp_emojies'] ?? '';
		?>
        <div class="mb-4">
            <select id="wp_emojies" name="performance_for_everyone_general_options[wp_emojies]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="enabled" <?php
				selected( $emojies, 'enabled' ); ?>>Enabled
                </option>
                <option value="disabled" <?php
				selected( $emojies, 'disabled' ); ?>>Disabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Disabling the emojis library in WordPress reduces unnecessary HTTP
                requests and script loading, which helps improve page load times and overall site performance. This is
                particularly beneficial for sites that do not heavily rely on emojis, as it eliminates extra code that
                would otherwise be redundant.</p>
        </div>
		<?php
	}

	public function wppfep_disable_cron_callback() {
		$options      = get_option( 'performance_for_everyone_general_options' );
		$disable_cron = $options['disable_wp_cron'] ?? '';

		?>
        <div class="mb-4">
            <select id="disable_wp_cron" name="performance_for_everyone_general_options[disable_wp_cron]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="enabled" <?php
				selected( $disable_cron, 'enabled' ); ?>>Enable
                </option>
                <option value="disabled" <?php
				selected( $disable_cron, 'disabled' ); ?>>Disable
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Disabling WordPress's built-in cron system can enhance website
                performance. The default WordPress cron runs on every page load, which can increase server load. This
                option is particularly useful for high-traffic websites where server resources are critical.</p>
        </div>
		<?php
	}

	public function wppfep_defer_javascript_callback() {
		$options          = get_option( 'performance_for_everyone_javascript_options' );
		$defer_javascript = $options['defer_javascript'] ?? '';
		?>
        <div class="mb-4">
            <select name="performance_for_everyone_javascript_options[defer_javascript]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="enabled" <?php
				selected( $defer_javascript, 'enabled' ); ?>>Enabled
                </option>
                <option value="disabled" <?php
				selected( $defer_javascript, 'disabled' ); ?>>Disabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Enabling this option will add the "defer" attribute to all JavaScript
                files, which can help improve page load times by loading JavaScript files after the HTML document has
                been parsed.</p>
        </div>
		<?php
	}


	public function wppfep_jquery_migrate_callback() {
		$options        = get_option( 'performance_for_everyone_javascript_options' );
		$jquery_migrate = $options['jquery_migrate'] ?? '';
		?>
        <div class="mb-4">
            <select name="performance_for_everyone_javascript_options[jquery_migrate]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="enabled" <?php
				selected( $jquery_migrate, 'enabled' ); ?>>Enabled
                </option>
                <option value="disabled" <?php
				selected( $jquery_migrate, 'disabled' ); ?>>Disabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">The jQuery Migrate script is included to provide backward
                compatibility for older jQuery code. If your theme and plugins are up-to-date and don’t require legacy
                jQuery support, you can remove it.</p>
        </div>
		<?php
	}

	public function wppfep_remove_embeds_callback() {
		$options = get_option( 'performance_for_everyone_javascript_options' );
		$embeds  = $options['embeds'] ?? '';
		?>
        <div class="mb-4">
            <select name="performance_for_everyone_javascript_options[embeds]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="disabled" <?php
				selected( $embeds, 'disabled' ); ?>>Disabled
                </option>
                <option value="enabled" <?php
				selected( $embeds, 'enabled' ); ?>>Enabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">The WordPress embeds script (wp-embed.js) is used to embed content
                from other sites (like YouTube videos) in posts. If you don’t need this functionality, you can remove it
                to save resources.</p>
        </div>
		<?php
	}


	public function wppfep_example_setting_callback() {
		$options = get_option( 'performance_for_everyone_options' );
		?>
        <input type="text" name="performance_for_everyone_options[example_setting]" value="<?php
		echo isset( $options['example_setting'] ) ? esc_attr( $options['example_setting'] ) : ''; ?>">
		<?php
	}


	//----------------------
	//---------- IMAGES
	//----------------------

	public function wppfep_lazy_loading_callback() {
		$options     = get_option( 'performance_for_everyone_images_options' );
		$lazyLoading = $options['lazy_loading'] ?? '';
		?>
        <div class="mb-4">
            <select name="performance_for_everyone_images_options[lazy_loading]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="disabled" <?php
				selected( $lazyLoading, 'disabled' ); ?>>Disabled
                </option>
                <option value="enabled" <?php
				selected( $lazyLoading, 'enabled' ); ?>>Enabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Enabling lazy loading defers loading of images until they are in view,
                improving page load times.
            </p>
        </div>
		<?php
	}

	public function wppfep_image_preload_callback() {
		$options      = get_option( 'performance_for_everyone_images_options' );
		$imagePreload = $options['image_preload'] ?? '';
		?>
        <div class="mb-4">
            <select name="performance_for_everyone_images_options[image_preload]"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="disabled" <?php
				selected( $imagePreload, 'disabled' ); ?>>Disabled
                </option>
                <option value="enabled" <?php
				selected( $imagePreload, 'enabled' ); ?>>Enabled
                </option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Enabling image preloading helps improve perceived performance by
                loading images in advance, ensuring they are available immediately when required.</p>
        </div>
		<?php
	}


	//----------------------
	//---------- Database
	//----------------------

	public function wppfe_delete_db_record() {
		$this->wppfepfe_database->wppfe_delete_db_record( $_POST['record_id'] );
	}

	public function wppfep_db_cleanup_ajax() {
		$this->wppfepfe_database->wppfep_cleanup_database();
		wp_send_json_success( 'Database cleanup completed.' );
	}

	public function wppfep_optimize_db_tables_ajax() {
		$this->wppfepfe_database->wppfep_optimize_database_tables();
		wp_send_json_success( 'Database tables optimized.' );
	}

	public function wppfep_cleanup_unused_data_ajax() {
		$this->wppfepfe_database->wppfep_cleanup_unused_data();
		wp_send_json_success( 'Unused data cleaned up.' );
	}

	public function wppfep_optimize_autoloaded_data_ajax() {
		$this->wppfepfe_database->wppfep_optimize_autoloaded_data();
		wp_send_json_success( 'Autoloaded data optimized.' );
	}

	function wppfep_backup_database() {
		return $this->wppfepfe_database->wppfep_backup_database();
	}

	function wppfep_delete_backups() {
		return $this->wppfepfe_database->wppfep_delete_backup();
	}

	function wppfep_list_backups() {
		return $this->wppfepfe_database->wppfep_list_backups();
	}

	public function wppfep_display_database_performance_results() {
		$html = $this->wppfepfe_database->get_db_performance_results_html();
		echo wp_kses_post( $html );
	}

	public function wppfep_check_db_performance() {
		return $this->wppfepfe_database->wppfep_check_db_performance();
	}

	public function wppfe_save_performance_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_performance_results';

		$urlUnslashed = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		$url          = wp_unslash( $urlUnslashed );

// Use wp_unslash() for other fields before sanitizing
		$mobile_score          = isset( $_POST['mobile_score'] ) ? intval( $_POST['mobile_score'] ) : null;
		$desktop_score         = isset( $_POST['desktop_score'] ) ? intval( $_POST['desktop_score'] ) : null;
		$mobile_assessment     = isset( $_POST['mobile_assessment'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile_assessment'] ) ) : null;
		$desktop_assessment    = isset( $_POST['desktop_assessment'] ) ? sanitize_text_field( wp_unslash( $_POST['desktop_assessment'] ) ) : null;
		$mobile_failed_audits  = isset( $_POST['mobile_failed_audits'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mobile_failed_audits'] ) ) : null;
		$desktop_failed_audits = isset( $_POST['desktop_failed_audits'] ) ? sanitize_textarea_field( wp_unslash( $_POST['desktop_failed_audits'] ) ) : null;

		$wpdb->insert(
			$table_name,
			array(
				'url'                   => $url,
				'mobile_score'          => $mobile_score,
				'desktop_score'         => $desktop_score,
				'mobile_assessment'     => $mobile_assessment,
				'desktop_assessment'    => $desktop_assessment,
				'mobile_failed_audits'  => $mobile_failed_audits,
				'desktop_failed_audits' => $desktop_failed_audits
			)
		);

		wp_send_json_success();
	}

	function wppfe_check_test_limit() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_performance_results';

		wp_send_json_success();
	}

	public function wppfep_display_saved_results() {
		$html = $this->get_saved_results_html();
		wp_send_json_success( $html );
	}

	private function get_saved_results_html() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_performance_results';
		$results    = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY checked_at DESC limit 10" );

		ob_start();
		echo '<table class="table table-bordered">';
		echo '<thead><tr><th>URL</th><th>Mobile Score</th><th>Desktop Score</th><th>Mobile Assessment</th><th>Desktop Assessment</th><th>Checked At</th><th>Action</th></tr></thead>';
		echo '<tbody>';

		foreach ( $results as $index => $result ) {
			$mobile_color             = $this->get_score_color( esc_attr( $result->mobile_score ) );
			$desktop_color            = $this->get_score_color( esc_attr( $result->desktop_score ) );
			$mobile_assessment_color  = $this->get_assessment_color( esc_attr( $result->mobile_assessment ) );
			$desktop_assessment_color = $this->get_assessment_color( esc_attr( $result->desktop_assessment ) );

			echo '<tr>';
			echo '<td>' . esc_html( $result->url ) . '</td>';
			echo '<td style="color: ' . esc_attr( $mobile_color ) . ';">' . esc_html( $result->mobile_score ) . '</td>';
			echo '<td style="color: ' . esc_attr( $desktop_color ) . ';">' . esc_html( $result->desktop_score ) . '</td>';
			echo '<td style="color: ' . esc_attr( $mobile_assessment_color ) . '; cursor: pointer;" onclick="toggleDetails(\'mobile\', ' . esc_attr( $index ) . ');">' . esc_html( $result->mobile_assessment ) . ' <i id="mobile-arrow-' . esc_attr( $index ) . '" class="fas fa-chevron-down"></i></td>';
			echo '<td style="color: ' . esc_attr( $desktop_assessment_color ) . '; cursor: pointer;" onclick="toggleDetails(\'desktop\', ' . esc_attr( $index ) . ');">' . esc_html( $result->desktop_assessment ) . ' <i id="desktop-arrow-' . esc_attr( $index ) . '" class="fas fa-chevron-down"></i></td>';
			echo '<td>' . esc_html( $result->checked_at ) . '</td>';
			echo '<td><button class="delete-result" data-result-id="' . esc_attr( $result->id ) . '"><i class="fas fa-trash-alt" style="color:red;"></i></button></td>';
			echo '</tr>';
			echo '<tr class="details" id="mobile-details-' . esc_attr( $index ) . '" style="display: none;"><td colspan="7">' . $this->format_failed_audits( $result->mobile_failed_audits ) . '</td></tr>';
			echo '<tr class="details" id="desktop-details-' . esc_attr( $index ) . '" style="display: none;"><td colspan="7">' . $this->format_failed_audits( $result->desktop_failed_audits ) . '</td></tr>';
		}

		echo '</tbody></table>';

		return ob_get_clean();
	}


	public function wppfep_delete_performance_result() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'delete_result_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! isset( $_POST['result_id'] ) ) {
			wp_send_json_error( 'No result ID provided' );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'wppfep_performance_results';
		$result_id  = (int) $_POST['result_id'];

		$deleted = $wpdb->delete( $table_name, [ 'id' => $result_id ], [ '%d' ] );

		if ( $deleted ) {
			wp_send_json_success( 'Result deleted successfully' );
		} else {
			wp_send_json_error( 'Failed to delete the result' );
		}
	}

	private function format_failed_audits( $failed_audits ) {
		if ( empty( $failed_audits ) ) {
			return '';
		}

		$audits           = explode( ', ', $failed_audits );
		$formatted_audits = '<ol>';
		foreach ( $audits as $audit ) {
			if ( stripos( $audit, 'undefined' ) === false ) {
				// Use regex to properly format numbers, considering thousands separators
				$audit            = preg_replace_callback( '/\d+(\.\d+)?(,\d{3})*( KiB| ms| s)?/',
					function ( $matches ) {
						return '<strong>' . $matches[0] . '</strong>';
					},
					$audit );
				$formatted_audits .= '<li>' . $audit . '</li>';
			}
		}
		$formatted_audits .= '</ol>';

		return $formatted_audits;
	}

	private function get_score_color( $score ) {
		if ( $score >= 90 ) {
			return 'green';
		} elseif ( $score >= 60 ) {
			return '#efa353';
		} else {
			return 'red';
		}
	}

	private function get_assessment_color( $assessment ) {
		return $assessment === 'succeeded' ? 'green' : 'red';
	}

	//----------------------
	//---------- CACHE
	//----------------------

	public function wppfep_list_cached_pages() {
		return $this->wppfepfe_caching->wppfep_list_cached_pages();
	}

	public function wppfep_list_cached_posts() {
		return $this->wppfepfe_caching->wppfep_list_cached_posts();
	}

	public function wppfep_delete_cache_item() {
		return $this->wppfepfe_caching->wppfep_delete_cache_item();
	}

	public function wppfep_list_cached_products() {
		return $this->wppfepfe_caching->wppfep_list_cached_products();
	}

	function wppfep_serve_cached_page() {
		$this->wppfepfe_caching->wppfep_serve_cached_page();
	}

	function wppfep_cache_all_pages() {
		return $this->wppfepfe_caching->wppfep_cache_all_pages();
	}

	function wppfep_cache_all_posts() {
		return $this->wppfepfe_caching->wppfep_cache_all_posts();
	}

	function wppfep_cache_all_products() {
		return $this->wppfepfe_caching->wppfep_cache_all_products();
	}

	function wppfep_clear_cache() {
		return $this->wppfepfe_caching->wppfep_clear_cache();
	}

	function wppfep_clear_pages_cache() {
		return $this->wppfepfe_caching->wppfep_clear_pages_cache();
	}

	function wppfep_clear_posts_cache() {
		return $this->wppfepfe_caching->wppfep_clear_posts_cache();
	}

	function wppfep_clear_products_cache() {
		return $this->wppfepfe_caching->wppfep_clear_products_cache();
	}

	public function wppfep_clear_all_caches__premium() {
		return $this->wppfepfe_caching->wppfep_clear_all_caches__premium();
	}

	public function wppfep_invalidate_cached_pages() {
		$this->wppfepfe_caching->wppfep_invalidate_cached_pages();
	}

	//----------------------
	//----------
	//----------------------


	//----------------------
	//----------
	//----------------------

	function log_debug_message( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( print_r( $message, true ) );
		}
	}

	// INIT
	function wppfep_general_initialisation() {
	}

	public function wppfep_defer_scripts( $tag, $handle ) {
		return $this->wppfepfe_javascript->defer_scripts( $tag, $handle );
	}

	//----------------------
	//----------
	//----------------------

	public function wppfep_deregister_styles_callback() {
		$enqueued_styles = get_option( 'performance_for_everyone_deregistered_styles_list', [] );
		$selected_styles = get_option( 'performance_for_everyone_deregistered_styles',
			[] )['deregistered_styles'] ?? [];

		// Arrays to hold styles grouped by plugin or theme
		$grouped_styles = [];

		// Group styles by plugin or theme name
		foreach ( $enqueued_styles as $handle => $src ) {
			$group = 'Others';

			// Check if the style is from a plugin
			if ( preg_match( '/\/plugins\/([^\/]*)\//', $src, $matches ) ) {
				$group = ucfirst( $matches[1] );
			} // Check if the style is from a theme
            elseif ( preg_match( '/\/themes\/([^\/]*)\//', $src, $matches ) ) {
				$group = ucfirst( $matches[1] );
			}

			// Organize styles by whether they are selected or not
			if ( ! isset( $grouped_styles[ $group ] ) ) {
				$grouped_styles[ $group ] = [ 'selected' => [], 'unselected' => [] ];
			}

			if ( in_array( $handle, $selected_styles ) ) {
				$grouped_styles[ $group ]['selected'][ $handle ] = $src;
			} else {
				$grouped_styles[ $group ]['unselected'][ $handle ] = $src;
			}
		}

		// Display styles grouped by plugin or theme
		echo '<div class="wppfep-styles-list">';
		foreach ( $grouped_styles as $group_name => $styles ) {
			$group_id = sanitize_title( $group_name ); // Create a unique ID for each group
			echo '<h6 class="wppfep-group-toggle" data-group="' . esc_attr( $group_id ) . '">' . esc_html( $group_name ) . ' <span class="toggle-icon">[+]</span></h6>';

			echo '<div id="' . esc_attr( $group_id ) . '" class="wppfep-group-content" style="display: none;">';

			// Display selected styles
			if ( ! empty( $styles['selected'] ) ) {
				echo '<h6>Selected Styles</h6>';
				echo '<table class="wp-list-table widefat fixed striped">';
				echo '<thead><tr><th>Handle</th><th>Source</th><th>Action</th></tr></thead>';
				echo '<tbody>';
				foreach ( $styles['selected'] as $handle => $src ) {
					echo '<tr>';
					echo '<td>' . esc_html( $handle ) . '</td>';
					echo '<td>' . esc_html( $src ) . '</td>';
					echo '<td><label><input type="checkbox" name="performance_for_everyone_deregistered_styles[deregistered_styles][]" value="' . esc_attr( $handle ) . '" checked> Deregister</label></td>';
					echo '</tr>';
				}
				echo '</tbody></table>';
			}

			// Display unselected styles
			if ( ! empty( $styles['unselected'] ) ) {
				echo '<h6>Available Styles</h6>';
				echo '<table class="wp-list-table widefat fixed striped">';
				echo '<thead><tr><th>Handle</th><th>Source</th><th>Action</th></tr></thead>';
				echo '<tbody>';
				foreach ( $styles['unselected'] as $handle => $src ) {
					echo '<tr>';
					echo '<td>' . esc_html( $handle ) . '</td>';
					echo '<td>' . esc_html( $src ) . '</td>';
					echo '<td><label><input type="checkbox" name="performance_for_everyone_deregistered_styles[deregistered_styles][]" value="' . esc_attr( $handle ) . '"> Deregister</label></td>';
					echo '</tr>';
				}
				echo '</tbody></table>';
			}

			echo '</div>'; // Close the group content div
			echo '<hr/>';
		}
		echo '</div>';

		if ( empty( $enqueued_styles ) ) {
			echo '<p>No stylesheets found. Ensure your theme and plugins are properly enqueuing styles.</p>';
		}

		echo '<p class="description">Select the stylesheets you want to deregister on the homepage.</p>';
	}

	function wppfep_enable_lazy_loading_callback() {
//		$options = get_option( 'performance_for_everyone_deregistered_styles', [] );
//		$checked = isset( $options['enable_lazy_loading'] ) ? 'checked' : '';
//		echo '<label>';
//		echo '<input type="checkbox" name="performance_for_everyone_deregistered_styles[enable_lazy_loading]" value="1" ' . $checked . '> Enable Lazy Loading for Images';
//		echo '</label>';
	}


	function wppfetfeperformance_admin_notices() {
		if ( get_user_meta( get_current_user_id(), '4days_wppfepfe_notice_dismissed', true ) ) {
			return;
		}

		if ( $this->wpte_performance_has_five_minutes_passed() ) {
			?>
            <div class="notice notice-info is-dismissible" id="wppfetfe-notice">
                <p>Fantastic! You've been using <i>Performance for Everyone</i> for over 4 days now. May we ask you to
                    give it a <strong>5-star</strong> rating on Wordpress.org?</p>
                <hr>
                <a href="https://wordpress.org/support/plugin/performance-for-everyone/reviews/#postform"
                   target="_blank">Ok, you deserved it</a>&nbsp;|&nbsp;
                <a href="#" id="already-did-btn">I already did</a>&nbsp;|&nbsp;
                <a href="#" id="not-good-enough-btn">No, not good enough</a>
                <br><br>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    // Dismiss notice functionality
                    $('#wppfetfe-notice').on('click', '.notice-dismiss', function () {
                        $.post(ajaxurl, {
                            action: 'wppfepfe_dismiss_notice'
                        });
                    });

                    // "I already did" button functionality
                    $('#already-did-btn').on('click', function (e) {
                        e.preventDefault();
                        $.post(ajaxurl, {
                            action: 'wppfepfe_dismiss_notice'
                        }, function () {
                            $('#wppfetfe-notice').fadeOut(); // Dismiss the notice after the action
                        });
                    });

                    // "No, not good enough" button functionality
                    $('#not-good-enough-btn').on('click', function (e) {
                        e.preventDefault();
                        $.post(ajaxurl, {
                            action: 'wppfepfe_dismiss_notice'
                        }, function () {
                            $('#wppfetfe-notice').fadeOut(); // Dismiss the notice after the action
                        });
                    });
                });
            </script>
			<?php
		}
	}

	function wpte_performance_has_five_minutes_passed() {
		$activation_time         = get_option( 'wppfe_performancefe_activation_time' );
		$current_time            = current_time( 'mysql' );
		$time_difference         = strtotime( $current_time ) - strtotime( $activation_time );
		$five_minutes_in_seconds = 4 * 86400;

		return $time_difference >= $five_minutes_in_seconds;
	}

	function wppfepfe_dismiss_notice() {
		update_user_meta( get_current_user_id(), '4days_wppfepfe_notice_dismissed', true );
		wp_send_json_success();
	}

}
