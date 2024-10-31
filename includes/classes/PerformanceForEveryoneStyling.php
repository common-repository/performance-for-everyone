<?php

class PerformanceForEveryoneStyling {
	function __construct() {
	}

	public function list_css_files() {
		global $wp_styles;
		$css_files = [];

		if (!empty($wp_styles->registered)) {
			foreach ($wp_styles->registered as $handle => $style) {
				$src = $style->src;

				// Get the full URL
				$full_url = esc_url($src);
				$file_path = ABSPATH . str_replace(site_url('/'), '', $full_url);

				// Get the file size if the file exists
				if (file_exists($file_path)) {
					$size = filesize($file_path);
					$css_files[$handle] = [
						'url' => $full_url,
						'size' => $size,
						'file_path' => $file_path,
					];
				}
			}
		}

		uasort($css_files, function ($a, $b) {
			return $b['size'] - $a['size'];
		});

		return $css_files;
	}

}
