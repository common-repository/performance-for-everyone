<?php

class PerformanceForEveryoneCache {
	function __construct() {
	}

	public function wppfep_list_cached_pages() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir    = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/pages/';
		$cached_pages = array();

		if ( file_exists( $cache_dir ) ) {
			$files = scandir( $cache_dir );
			foreach ( $files as $file ) {
				if ( $file !== '.' && $file !== '..' ) {
					$page_id = str_replace( '.html', '', $file );
					$page    = get_post( $page_id );
					if ( $page ) {
						$cached_pages[] = array(
							'id'    => $page->ID,
							'title' => $page->post_title,
							'url'   => get_permalink( $page_id )
						);
					}
				}
			}
		}

		ob_start();
		if ( ! empty( $cached_pages ) ) {
			echo '<ul>';
			foreach ( $cached_pages as $cached_page ) {
				echo '<li>';
				echo '<a href="' . esc_url( $cached_page['url'] ) . '">' . esc_html( $cached_page['title'] ) . '</a>';
				echo ' <span class="delete-cache" data-id="' . esc_attr( $cached_page['id'] ) . '" data-type="page">';
				echo '<i class="fas fa-times"></i>';
				echo '</span>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="notice notice-warning"><p>No cached pages found.</p></div>';
		}
		$output = ob_get_clean();

		return wp_send_json_success( $output );
	}

	public function wppfep_delete_cache_item() {
		if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'wppfep_nonce', 'nonce' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$id   = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( ! $id || ! in_array( $type, array( 'page', 'post', 'product' ), true ) ) {
			wp_send_json_error( 'Invalid request' );
		}

		$cache_dir  = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/' . $type . 's/';
		$cache_file = $cache_dir . $id . '.html';

		if ( file_exists( $cache_file ) ) {
			wp_delete_file( $cache_file );

			if ( ! file_exists( $cache_file ) ) {
				wp_send_json_success( 'Cache item deleted successfully.' );
			} else {
				wp_send_json_error( 'Failed to delete cache item.' );
			}
		} else {
			wp_send_json_error( 'Cache file not found.' );
		}
	}


//	----
//	---- PAGES
//	----

	function wppfep_serve_cached_page() {
		if ( is_page() || is_single() ) {
			global $post;
			$cache_dir = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/';

			if ( is_page() ) {
				$cache_file = $cache_dir . 'pages/' . $post->ID . '.html';
			}
			if ( is_single() ) {
				$cache_file = $cache_dir . 'posts/' . $post->ID . '.html';
			}
			if ( class_exists( 'WooCommerce' ) ) {
				if ( is_product() ) {
					$cache_file = $cache_dir . 'products/' . get_the_ID() . '.html';
				}
			}

			global $wp_filesystem;
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();

			if ( $wp_filesystem->exists( $cache_file ) ) {
				$file_contents = $wp_filesystem->get_contents( $cache_file );
				if ( $file_contents !== false ) {
					echo $file_contents;
					exit;
				} else {
					echo 'Error: Could not read cached file.';
					exit;
				}
			}
		}
	}

	function wppfep_cache_all_pages( $isForPurge = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			if ( $isForPurge ) {
				return false;
			}
			wp_send_json_error( 'Unauthorized user' );
		}

		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			if ( $isForPurge ) {
				return false;
			}
			wp_send_json_error( 'Failed to initialize WP_Filesystem.' );
		}

		$upload_dir = wp_upload_dir();
		$cache_dir  = trailingslashit( $upload_dir['basedir'] ) . 'cached-pages-wppfe/pages/';

		if ( ! $wp_filesystem->is_dir( $cache_dir ) ) {
			if ( ! $wp_filesystem->mkdir( $cache_dir, FS_CHMOD_DIR ) ) {
				if ( ! mkdir( $cache_dir, 0755, true ) ) {
					wp_send_json_error( 'Failed to create cache directory using both WP_Filesystem and native mkdir. Please check folder permissions.' );
				}
			}
		}

		// Set directory permissions to ensure it is writable
		$wp_filesystem->chmod( $cache_dir, FS_CHMOD_DIR );

		$pages = get_pages();

		foreach ( $pages as $page ) {
			$response = wp_remote_get( get_permalink( $page->ID ) );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'Failed to fetch page content for ID: ' . $page->ID );
				continue; // Skip this page if there was an error
			}

			$html = wp_remote_retrieve_body( $response );
			$html = $this->wppfep_minify_html( $html );

			$html .= "\n<!-- Cached by PerformanceForEveryone -->";

			$cache_file = trailingslashit( $cache_dir ) . $page->ID . '.html';
			if ( ! $wp_filesystem->put_contents( $cache_file, $html, FS_CHMOD_FILE ) ) {
				wp_send_json_error( 'Failed to cache page ID: ' . $page->ID );
			}
		}

		if ( $isForPurge ) {
			return true;
		}
		wp_send_json_success( 'Pages cached successfully' );
	}

	public function wppfep_clear_pages_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/pages/';
		if ( file_exists( $cache_dir ) ) {
			$files = glob( $cache_dir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					wp_delete_file( $file );
				}
			}
		}

		return wp_send_json_success( 'Pages cache cleared successfully' );
	}

//	----
//	---- POSTS
//	----

	public function wppfep_cache_all_posts( $ifForPurge = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			if ( $isForPurge ) {
				return false;
			}
			wp_send_json_error( 'Unauthorized user' );
		}

		global $wp_filesystem;

		// Initialize the WP_Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			if ( $isForPurge ) {
				return false;
			}
			wp_send_json_error( 'Failed to initialize WP_Filesystem.' );
		}

		$upload_dir = wp_upload_dir();
		$cache_dir  = trailingslashit( $upload_dir['basedir'] ) . 'cached-pages-wppfe/posts/';

		if ( ! $wp_filesystem->is_dir( $cache_dir ) ) {
			if ( ! $wp_filesystem->mkdir( $cache_dir, FS_CHMOD_DIR ) ) {
				// Fallback: Attempt to create the directory with PHP's native mkdir
				if ( ! mkdir( $cache_dir, 0755, true ) ) {
					wp_send_json_error( 'Failed to create cache directory using both WP_Filesystem and native mkdir. Please check folder permissions.' );
				}
			}
		}

		// Set directory permissions to ensure it is writable
		$wp_filesystem->chmod( $cache_dir, FS_CHMOD_DIR );

		$posts = get_posts( array( 'post_type' => 'post', 'numberposts' => - 1 ) );

		foreach ( $posts as $post ) {
			$response = wp_remote_get( get_permalink( $post->ID ) );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'Failed to fetch post content for ID: ' . $post->ID );
				continue;
			}

			$html = wp_remote_retrieve_body( $response );
			$html = $this->wppfep_minify_html( $html );

			$html .= "\n<!-- Cached by PerformanceForEveryone -->";

			$cache_file = trailingslashit( $cache_dir ) . $post->ID . '.html';
			if ( ! $wp_filesystem->put_contents( $cache_file, $html, FS_CHMOD_FILE ) ) {
				wp_send_json_error( 'Failed to cache post ID: ' . $post->ID );
			}
		}
		if ( $isForPurge ) {
			return true;
		}

		return wp_send_json_success( 'Posts cached successfully' );
	}

	public function wppfep_clear_posts_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/posts/';
		if ( file_exists( $cache_dir ) ) {
			$files = glob( $cache_dir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					wp_delete_file( $file );
				}
			}
		}

		return wp_send_json_success( 'Posts cache cleared successfully' );
	}

	public function wppfep_list_cached_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir    = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/posts/';
		$cached_posts = array();

		if ( file_exists( $cache_dir ) ) {
			$files = scandir( $cache_dir );
			foreach ( $files as $file ) {
				if ( $file !== '.' && $file !== '..' ) {
					$post_id = str_replace( '.html', '', $file );
					$post    = get_post( $post_id );
					if ( $post ) {
						$cached_posts[] = array(
							'id'    => $post->ID,
							'title' => $post->post_title,
							'url'   => get_permalink( $post_id )
						);
					}
				}
			}
		}

		ob_start();
		if ( ! empty( $cached_posts ) ) {
			echo '<ul>';
			foreach ( $cached_posts as $cached_post ) {
				echo '<li>';
				echo '<a href="' . esc_url( $cached_post['url'] ) . '">' . esc_html( $cached_post['title'] ) . '</a>';
				echo ' <span class="delete-cache" data-id="' . esc_attr( $cached_post['id'] ) . '" data-type="post">';
				echo '<i class="fas fa-times"></i>';
				echo '</span>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="notice notice-warning"><p>No cached posts found.</p></div>';
		}
		$output = ob_get_clean();

		return wp_send_json_success( $output );
	}

//	----
//	---- PRODUCTS
//	----

	public function wppfep_cache_all_products() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		global $wp_filesystem;

		// Initialize the WP_Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( site_url() );

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( 'Failed to initialize WP_Filesystem.' );
		}

		// Define cache directory
		$upload_dir = wp_upload_dir();
		$cache_dir  = trailingslashit( $upload_dir['basedir'] ) . 'cached-pages-wppfe/products/';

		// Attempt to create the directory using WP_Filesystem
		if ( ! $wp_filesystem->is_dir( $cache_dir ) ) {
			if ( ! $wp_filesystem->mkdir( $cache_dir, FS_CHMOD_DIR ) ) {
				// Fallback: Attempt to create the directory with PHP's native mkdir
				if ( ! mkdir( $cache_dir, 0755, true ) ) {
					wp_send_json_error( 'Failed to create cache directory using both WP_Filesystem and native mkdir. Please check folder permissions.' );
				}
			}
		}

		// Set directory permissions to ensure it is writable
		$wp_filesystem->chmod( $cache_dir, FS_CHMOD_DIR );

		$products = wc_get_products( array( 'limit' => - 1 ) );

		foreach ( $products as $product ) {
			$response = wp_remote_get( get_permalink( $product->get_id() ) );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'Failed to fetch product content for ID: ' . $product->get_id() );
				continue;
			}

			$html = wp_remote_retrieve_body( $response );
			$html .= "\n<!-- Cached by PerformanceForEveryone -->";

			$cache_file = trailingslashit( $cache_dir ) . $product->get_id() . '.html';
			if ( ! $wp_filesystem->put_contents( $cache_file, $html, FS_CHMOD_FILE ) ) {
				wp_send_json_error( 'Failed to cache product ID: ' . $product->get_id() );
			}
		}

		return wp_send_json_success( 'Products cached successfully' );
	}

	public function wppfep_clear_products_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/products/';
		if ( file_exists( $cache_dir ) ) {
			$files = glob( $cache_dir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					wp_delete_file( $file );
				}
			}
		}

		return wp_send_json_success( 'Products cache cleared successfully' );
	}

	public function wppfep_list_cached_products() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dir       = wp_upload_dir()['basedir'] . '/cached-pages-wppfe/products/';
		$cached_products = array();

		if ( file_exists( $cache_dir ) ) {
			$files = scandir( $cache_dir );
			foreach ( $files as $file ) {
				if ( $file !== '.' && $file !== '..' ) {
					$product_id = str_replace( '.html', '', $file );
					$product    = wc_get_product( $product_id );
					if ( $product ) {
						$cached_products[] = array(
							'id'    => $product_id,
							'title' => $product->get_name(),
							'url'   => get_permalink( $product_id )
						);
					}
				}
			}
		}

		ob_start();
		if ( ! empty( $cached_products ) ) {
			echo '<ul>';
			foreach ( $cached_products as $cached_product ) {
				echo '<li>';
				echo '<a href="' . esc_url( $cached_product['url'] ) . '">' . esc_html( $cached_product['title'] ) . '</a>';
				echo ' <span class="delete-cache" data-id="' . esc_attr( $cached_product['id'] ) . '" data-type="product">';
				echo '<i class="fas fa-times"></i>';
				echo '</span>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="notice notice-warning"><p>No cached products found.</p></div>';
		}
		$output = ob_get_clean();

		return wp_send_json_success( $output );
	}


	public function wppfep_clear_all_caches__premium() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized user' );
		}

		$cache_dirs = [
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/pages/',
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/posts/',
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/products/',
		];

		foreach ( $cache_dirs as $cache_dir ) {
			if ( file_exists( $cache_dir ) ) {
				$files = glob( $cache_dir . '*' );
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						wp_delete_file( $file );
					}
				}
			}
		}

		return wp_send_json_success( 'All caches cleared successfully' );
	}

	private function wppfep_minify_html( $html ) {
		$html = preg_replace( '/<!--(?!\[if).*?-->/', '', $html );
		$html = preg_replace( '/>\s+</', '><', $html );
		$html = preg_replace( '/\s+/', ' ', $html );
		$html = preg_replace( '/\s*=\s*/', '=', $html );

		return trim( $html );
	}

	public function wppfep_invalidate_cached_pages($is_ajax = false) {
		// Check user capability only for AJAX calls
		if ($is_ajax && ! current_user_can('manage_options')) {
			wp_send_json_error('Unauthorized user');
		}

		global $wp_filesystem;

		if (!function_exists('WP_Filesystem')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials(site_url());

		if (!WP_Filesystem($creds)) {
			if ($is_ajax) {
				wp_send_json_error('Failed to initialize WP_Filesystem.');
			} else {
				return; // Exit if not able to initialize filesystem on non-AJAX calls
			}
		}

		$cache_dirs = [
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/pages/',
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/posts/',
			wp_upload_dir()['basedir'] . '/cached-pages-wppfe/products/',
		];

		foreach ($cache_dirs as $cache_dir) {
			if (file_exists($cache_dir)) {
				$files = glob($cache_dir . '*');
				foreach ($files as $file) {
					if (is_file($file)) {
						wp_delete_file($file);
					}
				}
			}
		}

		if ($is_ajax) {
			$this->wppfep_cache_all_pages(true);
			$this->wppfep_cache_all_posts(true);
			if (class_exists('WooCommerce')) {
				$this->wppfep_cache_all_products();
			}
			wp_send_json_success('Cache purged and recached successfully.');
		}
	}

}
