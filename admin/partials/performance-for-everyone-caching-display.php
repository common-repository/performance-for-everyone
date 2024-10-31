<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wppfe_performance">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-2 grid-reset-height">
        <div>
            <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
                <h2 class="text-info">Caching Settings</h2>
                <hr>
                <span class="red-color">Note: When caching pages, products, and posts, ensure that forms, AJAX requests, and other dynamic functionalities continue to work properly after caching.</span>
                <p class="mt-3">Enabling caching can significantly improve your website's performance. In many cases, page load times can be reduced by up to 50%, resulting in a faster and more responsive user experience.</p>
                <p>After you will cache the pages, go and check page performance in <strong>Speed Test</strong> tab.</p>
                <form method="post" action="options.php" class="form-horizontal">
		            <?php
		            settings_fields( 'performance_for_everyone_caching_options' );
		            do_settings_sections( 'performance_for_everyone_caching' );
		            ?>
                </form>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 mt-6 gap-6">
                <div class="bg-gray-100 text-gray-700 p-6 rounded-lg transition-transform duration-200 ">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-xl text-info ">Cached Pages</h4>
                    </div>
                    <div id="cached-pages-list" class="mt-4 text-sm text-gray-600">
                    </div>
                </div>

                <div class="bg-gray-100 text-gray-700 p-6 rounded-lg transition-transform duration-200 ">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-xl text-info ">Cached Posts</h4>
                    </div>
                    <div id="cached-posts-list" class="mt-4 text-sm text-gray-600">
                    </div>
                </div>

                <div class="bg-gray-100 text-gray-700 p-6 rounded-lg transition-transform duration-200 ">
                    <div class="flex items-center space-x-3">
                        <h4 class="text-xl text-info ">Cached Products</h4>
                    </div>
                    <div id="cached-products-list" class="mt-4 text-sm text-gray-600">
                    </div>
                </div>
            </div>

        </div>
        <div>
            <div class="bg-gray-100 text-black-50 p-6 rounded-lg ">
                <h2>We will answer to your questions</h2>
                <hr>
                <div class="wppfe-faq-section">
                    <button class="wppfe-accordion bg-info ">How page/post/product caching works?</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <h4 class="text-xl font-bold text-gray-800">1. Reduced File Size:</h4>
                        <p class="text-gray-600 leading-relaxed">Gzip compresses your website's files (HTML, CSS, JS) before sending them to the browser. This
                            significantly reduces file sizes, leading to faster download times for your users.</p>

                        <h4 class="text-xl font-bold text-gray-800">Reducing Server Load</h4>
                        <p class="text-gray-600 leading-relaxed">Without Caching: Every time a user requests a page, WordPress dynamically generates the page by querying the database and processing PHP code. This can be resource-intensive, especially for high-traffic sites.
                            With Caching: When page caching is enabled, the first time a page is requested, WordPress generates the page and saves the HTML output in a cache (a transient in this case). Subsequent requests for the same page are served from the cache, bypassing the database queries and PHP processing.
                        </p>
                        <h4 class="text-xl font-bold text-gray-800">Faster Page Load Times:</h4>
                        <p class="text-gray-600 leading-relaxed">Without Caching: Each page request involves multiple database queries and PHP execution, which can slow down the page load time, especially under heavy traffic.
                            With Caching: Serving the page from the cache is much faster since it involves simply retrieving the pre-generated HTML, leading to quicker page load times for users.
                        </p>
                        <h4 class="text-xl font-bold text-gray-800">Improved User Experience:</h4>
                        <p class="text-gray-600 leading-relaxed">Without Caching: Users might experience slower page loads, which can lead to higher bounce rates and lower user satisfaction.
                            With Caching: Faster page loads improve the overall user experience, leading to higher engagement and lower bounce rates.
                        </p>
                        <h4 class="text-xl font-bold text-gray-800">Scalability:</h4>
                        <p class="text-gray-600 leading-relaxed">Without Caching: As the number of concurrent users increases, the server load increases exponentially due to repeated database queries and PHP processing for each user.
                            With Caching: The server can handle more concurrent users since cached pages require significantly fewer resources to serve.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>