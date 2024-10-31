<?php

class PerformanceForEveryoneImages {
	function __construct() {
		add_filter( 'the_content', array( $this, 'wppfep_apply_lazy_loading' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'wppfep_apply_lazy_loading' ) );
		add_filter( 'widget_text', array( $this, 'wppfep_apply_lazy_loading' ) );
		add_filter( 'wp_get_attachment_image', array( $this, 'wppfep_apply_lazy_loading' ) );
		add_action( 'wp_enqueue_scripts', [ $this, 'wppfep_enqueue_lazyload_script' ] );
		add_action( 'wp_head', [ $this, 'wppfep_preload_images' ] );
	}

	public function wppfep_enqueue_lazyload_script() {
		$options      = get_option( 'performance_for_everyone_images_options' );
		$lazy_loading = $options['lazy_loading'] ?? 'disabled';
		if ( $lazy_loading === 'enabled' ) {
			wp_enqueue_script(
				'wppfep-lazyload-js',
				plugin_dir_url( __FILE__ ) . '../../public/js/lazyload.js',
				array(),
				time(),
				true
			);
		}
	}

	public function wppfep_apply_lazy_loading( $content ) {
		$options      = get_option( 'performance_for_everyone_images_options' );
		$lazy_loading = $options['lazy_loading'] ?? 'disabled';

		if ( $lazy_loading === 'enabled' ) {
			$content = preg_replace( '/<img(?!.*?loading=["\']lazy["\'])(.*?)>/', '<img loading="lazy" $1>', $content );
		}

		return $content;
	}

	public function wppfep_preload_images() {
		$options        = get_option( 'performance_for_everyone_images_options' );
		$preload_images = $options['image_preload'] ?? 'disabled';

		if ( $preload_images === 'enabled' ) {
			global $posts;

			$image_urls = [];
			if ( is_array( $posts ) ) {
				foreach ( $posts as $post ) {
					if ( preg_match_all( '/<img.*?src=["\'](.*?)["\']/', $post->post_content, $matches ) ) {
						$image_urls = array_merge( $image_urls, $matches[1] );
					}
				}
			}

			foreach ( $image_urls as $image_url ) {
				echo '<link rel="preload" href="' . esc_url( $image_url ) . '" as="image" />';
			}
		}
	}

}
