<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wppfe_performance">
	<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-2">
		<div class="bg-gray-100 text-black-50 p-6 rounded-lg">
			<h2 class="text-info">Images Optimization Settings</h2>
			<hr>
			<form id="js-settings-form" method="post" action="options.php">
				<?php
				settings_fields( 'performance_for_everyone_images_options' );
				do_settings_sections( 'performance_for_everyone_images' );
				submit_button();
				?>
			</form>
		</div>
		<div class="bg-gray-100 text-black-50 p-6 rounded-lg">
			<h2 class="text-info">We will answer to your questions</h2>
			<hr>
			<div class="wppfe-faq-section">
<!--				<button class="wppfe-accordion bg-info">How Does Lazy Loading Work?</button>-->
<!--				<div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">-->
<!--                    <p class="text-gray-600 leading-relaxed">-->
<!--                        Lazy loading is a technique that defers the loading of images, videos, or other media until they are needed.-->
<!--                        Instead of loading all media files when the page initially loads, lazy loading only fetches and displays-->
<!--                        media when the user scrolls near them, improving page speed and reducing the load on the server.-->
<!--                    </p>-->
<!--                    <h4 class="text-xl font-bold text-gray-800">Benefits of Lazy Loading:</h4>-->
<!--                    <ul class="list-disc pl-6">-->
<!--                        <li><strong>Improved Performance:</strong> Only the necessary content is loaded initially, which reduces page load times.</li>-->
<!--                        <li><strong>Bandwidth Savings:</strong> Media is loaded only if the user scrolls to it, saving bandwidth, especially for mobile users.</li>-->
<!--                        <li><strong>SEO Benefits:</strong> Faster-loading sites are favored by search engines like Google, which can help with rankings.</li>-->
<!--                    </ul>-->
<!--				</div>-->
                <button class="wppfe-accordion bg-info mt-2">What is Image Preloading and when to use it?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <p class="text-gray-600 leading-relaxed">
                        Preloading images is a performance optimization technique where specific images are loaded early during the page load process, even before they are needed. This ensures that when the user scrolls or navigates to the part of the page where the images are located, they appear instantly, without delay.
                    </p>
                    <h4 class="text-xl font-bold text-gray-800">When to Enable Image Preloading:</h4>
                    <ul class="list-disc pl-6">
                        <li><strong>Above-the-Fold Content:</strong> If you have important images that appear at the top of the page, preloading ensures that they are immediately available as soon as the page is displayed, preventing any loading delay.</li>
                        <li><strong>Critical User Experience Elements:</strong> If images are key to the user experience (e.g., banners, product images, or hero images), preloading them can make the site feel more responsive and improve the overall user experience.</li>
                        <li><strong>Improving Perceived Performance:</strong> Preloading helps improve the perceived speed of your website by ensuring important visual elements are ready as soon as they are needed, making the site appear faster.</li>
                    </ul>
                    <p class="mt-2 text-sm text-gray-500">
                        Enable image preloading when you want to prioritize specific images for faster loading, particularly for above-the-fold content or crucial images that contribute to the user's first impression of your site.
                    </p>
                </div>


            </div>
		</div>
	</div>
</div>


