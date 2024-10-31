<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wppfe.com
 * @since      1.0.0
 *
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Performance_For_Everyone
 * @subpackage Performance_For_Everyone/public
 * @author     wppfe <info@wppfe.com>
 */
class Performance_For_Everyone_Public {

	private $plugin_name;
	private $version;
	private $wppfepfe_javascript;
	private $wppfepfe_general;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->wppfepfe_javascript = new PerformanceForEveryoneJavascript();
		$this->wppfepfe_general = new PerformanceForEveryoneGeneral();
		$this->version     = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/performance-for-everyone-public.css',
			array(),
			$this->version,
			'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/performance-for-everyone-public.js',
			array( 'jquery' ),
			$this->version,
			false );
	}

	function wppfep_deregister_selected_styles_on_homepage() {
		if ( is_front_page() ) {
			$selected_styles = get_option( 'performance_for_everyone_deregistered_styles',
				[] )['deregistered_styles'] ?? [];

			if ( ! empty( $selected_styles ) ) {
				foreach ( $selected_styles as $handle ) {
					if ( wp_style_is( $handle, 'enqueued' ) ) {
						wp_dequeue_style( $handle );
						wp_deregister_style( $handle );
					}
				}
			}
		}
	}

	function wppfep_general_initialisation(){
		$this->wppfepfe_javascript->remove_jqmigrate();
		$this->wppfepfe_general->remove_emojicons();
		$this->wppfepfe_javascript->remove_embeds();
	}

	function collect_all_enqueued_styles() {
		global $wp_styles;
		$enqueued_styles = get_option( 'performance_for_everyone_deregistered_styles_list', [] );

		// Collect all registered styles
		if ( isset( $wp_styles->registered ) ) {
			foreach ( $wp_styles->registered as $handle => $style ) {
				if ( ! empty( $style->src ) && ! isset( $enqueued_styles[ $handle ] ) ) {
					// Filter styles by their source path
					$src = $style->src;

					// Include only styles from the themes, plugins, or WordPress core (excluding admin)
					if ( ( strpos( $src, '/wp-content/themes/' ) !== false ||
					       strpos( $src, '/wp-content/plugins/' ) !== false ||
					       strpos( $src, '/wp-includes/css/' ) !== false ) &&
					     strpos( $src, '/wp-admin/' ) === false ) {
						$enqueued_styles[ $handle ] = $src;
					}
				}
			}
		}

		// Store the updated list
		update_option( 'performance_for_everyone_deregistered_styles_list', $enqueued_styles );
	}

	function wppfep_apply_lazy_loading( $content ) {
		$options = get_option( 'performance_for_everyone_styles_section', [] );

		if ( isset( $options['enable_lazy_loading'] ) && $options['enable_lazy_loading'] ) {
			$content = preg_replace( '/<img(.*?)src=/', '<img$1loading="lazy" src=', $content );
		}

		return $content;
	}

	function wppfep_add_lazy_loading_to_images( $attributes ) {
		$options = get_option( 'performance_for_everyone_deregistered_styles', [] );

		if ( isset( $options['enable_lazy_loading'] ) && $options['enable_lazy_loading'] ) {
			$attributes['loading'] = 'lazy';
		}

		return $attributes;
	}



}
