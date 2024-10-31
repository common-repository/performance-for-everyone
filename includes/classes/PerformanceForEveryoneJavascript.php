<?php

class PerformanceForEveryoneJavascript {
	function __construct() {

		add_action('update_option_performance_for_everyone_javascript_options', [$this, 'wppfep_handle_javascript_options_update'], 10, 2);

		add_action('wp_head', [$this, 'preload_fonts']);
		add_action('wp_head', [$this, 'add_analytics_tags']);
	}

	public function wppfep_handle_javascript_options_update($oldValue, $newValue){
		if ($newValue['jquery_migrate'] === 'disabled') {
			$this->remove_jqmigrate();
			$this->remove_jquery_migrate();
		}
		if ($newValue['embeds'] === 'disabled') {
			$this->remove_embeds();
		}
	}

	public function remove_jqmigrate() {
		$options = get_option( 'performance_for_everyone_javascript_options' );
		$status  = $options['jquery_migrate'] ?? 'disabled';
		if ( $status === 'disabled' ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'remove_jquery_migrate' ], 100 );
		}
	}

	public function remove_jquery_migrate() {
		if ( ! is_admin() ) {
			global $wp_scripts;
			if ( isset( $wp_scripts->registered['jquery'] ) ) {
				$wp_scripts->registered['jquery']->deps = array_diff( $wp_scripts->registered['jquery']->deps, [ 'jquery-migrate' ] );
			}
		}
	}

	public function remove_embeds() {
		$options = get_option( 'performance_for_everyone_javascript_options' );
		$status  = $options['embeds'] ?? 'disabled';
		if ( $status === 'disabled' ) {
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			remove_action( 'wp_head', 'rest_output_link_wp_head' );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			remove_action( 'wp_enqueue_scripts', 'wp-embed' );
		}
	}

	public function defer_scripts( $tag, $handle ) {

		if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
			return $tag;
		}

		$options = get_option( 'performance_for_everyone_javascript_options' );

		if ( isset( $options['defer_javascript'] ) && $options['defer_javascript'] === 'enabled' ) {

			if ( strpos( $tag, ' defer="defer"' ) === false ) {
				$tag = str_replace( ' src', ' defer="defer" src', $tag );
			}
		}

		return $tag;
	}


}
