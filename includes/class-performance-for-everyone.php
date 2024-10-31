<?php


class Performance_For_Everyone {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Performance_For_Everyone_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PERFORMANCE_FOR_EVERYONE_VERSION' ) ) {
			$this->version = PERFORMANCE_FOR_EVERYONE_VERSION;
		} else {
			$this->version = '1.3.7';
		}
		$this->plugin_name = 'performance-for-everyone';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-performance-for-everyone-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-performance-for-everyone-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-performance-for-everyone-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-performance-for-everyone-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneCache.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneDatabase.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneGeneral.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneJavascript.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneStyling.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneDashboard.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/PerformanceForEveryoneImages.php';

		$this->loader = new Performance_For_Everyone_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Performance_For_Everyone_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Performance_For_Everyone_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Performance_For_Everyone_Admin( $this->get_plugin_name(), $this->get_version() );

		// Enqueue scripts and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Admin menu and settings
		$this->loader->add_action('admin_menu', $plugin_admin, 'wppfep_add_plugin_admin_menu');
		$this->loader->add_action('admin_init', $plugin_admin, 'wppfep_register_settings');
		$this->loader->add_filter('plugin_action_links_' . plugin_basename(__FILE__), $plugin_admin, 'wppfep_add_settings_link');

		// Performance data actions
		$this->loader->add_action('wp_ajax_save_performance_data', $plugin_admin, 'wppfe_save_performance_data');
		$this->loader->add_action('wp_ajax_save_performance_data', $plugin_admin, 'wppfe_save_performance_data');

		$this->loader->add_action('wp_ajax_check_test_limit', $plugin_admin, 'wppfe_check_test_limit');
		$this->loader->add_action('wp_ajax_nopriv_check_test_limit', $plugin_admin, 'wppfe_check_test_limit');

		$this->loader->add_action('wp_ajax_display_saved_results', $plugin_admin, 'wppfep_display_saved_results');
		$this->loader->add_action('wp_ajax_check_db_performance', $plugin_admin, 'wppfep_check_db_performance');

		// Scheduled events
		$this->loader->add_action('wppfep_cleanup_event', $plugin_admin, 'wppfep_cleanup_event_handler');
		$this->loader->add_action('wppfep_optimize_event', $plugin_admin, 'wppfep_optimize_event_handler');

		// Database actions
		$this->loader->add_action('wp_ajax_wppfep_db_cleanup', $plugin_admin, 'wppfep_db_cleanup_ajax');
		$this->loader->add_action('wp_ajax_wppfep_optimize_db_tables', $plugin_admin, 'wppfep_optimize_db_tables_ajax');
		$this->loader->add_action('wp_ajax_wppfep_cleanup_unused_data', $plugin_admin, 'wppfep_cleanup_unused_data_ajax');
		$this->loader->add_action('wp_ajax_wppfep_optimize_autoloaded_data', $plugin_admin, 'wppfep_optimize_autoloaded_data_ajax');
		$this->loader->add_action('wp_ajax_backup_database', $plugin_admin, 'wppfep_backup_database');
		$this->loader->add_action('wp_ajax_wppfep_delete_backup', $plugin_admin, 'wppfep_delete_backups');
		$this->loader->add_action('wp_ajax_list_backups', $plugin_admin, 'wppfep_list_backups');

		// Caching actions for pages, posts, and products
		$this->loader->add_action('wp_ajax_wppfep_list_cached_pages', $plugin_admin, 'wppfep_list_cached_pages');
		$this->loader->add_action('wp_ajax_wppfep_list_cached_posts', $plugin_admin, 'wppfep_list_cached_posts'); // Added hook for listing cached posts
		$this->loader->add_action('wp_ajax_wppfep_list_cached_products', $plugin_admin, 'wppfep_list_cached_products'); // Added hook for listing cached products

		$this->loader->add_action('wp_ajax_cache_all_pages', $plugin_admin, 'wppfep_cache_all_pages');
		$this->loader->add_action('wp_ajax_cache_all_posts', $plugin_admin, 'wppfep_cache_all_posts'); // Added hook for caching all posts
		$this->loader->add_action('wp_ajax_cache_all_products', $plugin_admin, 'wppfep_cache_all_products'); // Added hook for caching all products

		$this->loader->add_action('wp_ajax_clear_pages_cache', $plugin_admin, 'wppfep_clear_pages_cache'); // Added hook for clearing pages cache
		$this->loader->add_action('wp_ajax_clear_posts_cache', $plugin_admin, 'wppfep_clear_posts_cache'); // Added hook for clearing posts cache
		$this->loader->add_action('wp_ajax_clear_products_cache', $plugin_admin, 'wppfep_clear_products_cache'); // Added hook for clearing products cache
		$this->loader->add_action('wp_ajax_wppfep_update_plugin', $plugin_admin, 'wppfep_update_plugin_ajax'); // Added hook for clearing products cache
		$this->loader->add_action('wp_ajax_wppfep_check_plugin_update_status', $plugin_admin, 'wppfep_check_plugin_update_status'); // Added hook for clearing products cache
		$this->loader->add_action('admin_notices', $plugin_admin, 'wppfetfeperformance_admin_notices');
		$this->loader->add_action('wp_ajax_wppfep_delete_cache_item', $plugin_admin, 'wppfep_delete_cache_item'); // Added hook for clearing products cache
		$this->loader->add_action('wp_ajax_wppfepfe_dismiss_notice', $plugin_admin, 'wppfepfe_dismiss_notice');
		$this->loader->add_action('wp_ajax_clear_all_caches', $plugin_admin, 'wppfep_clear_all_caches__premium');

		$this->loader->add_action('init', $plugin_admin, 'wppfep_general_initialisation');
		$this->loader->add_filter('script_loader_tag', $plugin_admin, 'wppfep_defer_scripts', 10, 2);

		$this->loader->add_action('wp_ajax_delete_db_record', $plugin_admin, 'wppfe_delete_db_record');

		$this->loader->add_action('template_redirect', $plugin_admin, 'wppfep_serve_cached_page');
		$this->loader->add_action('elementor/core/files/clear_cache', $plugin_admin, 'wppfep_invalidate_cached_pages');

		$this->loader->add_action('wp_ajax_clear_cache', $plugin_admin, 'wppfep_clear_cache');
		$this->loader->add_action('wp_ajax_delete_performance_result', $plugin_admin, 'wppfep_delete_performance_result');



	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Performance_For_Everyone_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'wppfep_deregister_selected_styles_on_homepage', 100);

		$this->loader->add_action('wp_footer', $plugin_public, 'collect_all_enqueued_styles', 9999);
		$this->loader->add_action('wp_head', $plugin_public, 'collect_all_enqueued_styles', 9999);

		$this->loader->add_action('init', $plugin_public, 'wppfep_general_initialisation');

		$this->loader->add_filter('the_content', $plugin_public, 'wppfep_apply_lazy_loading');
		$this->loader->add_filter('widget_text', $plugin_public, 'wppfep_apply_lazy_loading');
		$this->loader->add_filter('post_thumbnail_html', $plugin_public, 'wppfep_apply_lazy_loading');
		$this->loader->add_filter('wp_get_attachment_image', $plugin_public, 'wppfep_apply_lazy_loading');

		$this->loader->add_filter('wp_get_attachment_image_attributes', $plugin_public, 'wppfep_add_lazy_loading_to_images');
		$this->loader->add_filter('img_caption_shortcode', $plugin_public, 'wppfep_apply_lazy_loading');

	}

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
	 * @return    Performance_For_Everyone_Loader    Orchestrates the hooks of the plugin.
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
