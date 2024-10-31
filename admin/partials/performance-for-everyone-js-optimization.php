<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wppfe_performance">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-2">
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
            <h2 class="text-info">Javascript Optimization Settings</h2>
            <hr>
            <form id="js-settings-form" method="post" action="options.php">
				<?php
				settings_fields( 'performance_for_everyone_javascript_options' );
				do_settings_sections( 'performance_for_everyone_javascript' );
				submit_button();
				?>
            </form>
        </div>
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
            <h2 class="text-info">We will answer to your questions</h2>
            <hr>
            <div class="wppfe-faq-section">
                <button class="wppfe-accordion bg-info">Why Optimize Your JavaScript in WordPress?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <p class="text-gray-600 leading-relaxed">JavaScript plays a significant role in your website's
                        performance. However, unoptimized JavaScript can slow down page load times and affect user
                        experience. By optimizing your site's JavaScript, you can reduce load times and improve overall
                        website performance, leading to a better user experience and improved SEO rankings.</p>
                </div>

                <button class="wppfe-accordion bg-info mt-2">What Can Be Optimized?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <h4 class="text-xl font-bold text-gray-800">Disable jQuery Migrate:</h4>
                    <p class="text-gray-600 leading-relaxed">WordPress includes a file called jQuery Migrate to ensure
                        compatibility with older versions of jQuery. However, if your site and plugins are using
                        up-to-date versions of jQuery, this file is unnecessary and can slow down your site. Disabling
                        jQuery Migrate can improve loading speeds.</p>

                    <h4 class="text-xl font-bold text-gray-800">Remove Embeds:</h4>
                    <p class="text-gray-600 leading-relaxed">WordPress automatically includes embed scripts to allow you
                        to embed content from other sites, such as YouTube videos or tweets. If your site doesn't need
                        this functionality, removing the embed scripts can reduce unnecessary HTTP requests and improve
                        performance.</p>

                    <h4 class="text-xl font-bold text-gray-800">Defer JavaScript:</h4>
                    <p class="text-gray-600 leading-relaxed">Deferring JavaScript ensures that your scripts are loaded
                        only after the rest of the page has loaded, speeding up the initial page load time and improving
                        your site's perceived performance. This is especially helpful for large or complex scripts that
                        aren't required immediately.</p>
                </div>

                <button class="wppfe-accordion bg-info mt-2">How Often Should JavaScript Optimization Be Done?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <p class="text-gray-600 leading-relaxed">Once you optimize your JavaScript, it's generally a
                        one-time process. However, it's important to monitor your site for any updates to themes or
                        plugins that might reintroduce jQuery Migrate or other unnecessary scripts. We recommend
                        periodically reviewing your site's performance to ensure continued optimization.</p>
                </div>

                <button class="wppfe-accordion bg-info mt-2">Benefits of JavaScript Optimization</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <p class="text-gray-600 leading-relaxed">Optimizing JavaScript can have several benefits,
                        including:</p>
                    <ul class="list-disc pl-6">
                        <li><strong>Improved Page Load Speed:</strong> Removing unnecessary scripts and deferring
                            non-essential JavaScript speeds up your site, improving load times and user experience.
                        </li>
                        <li><strong>Better SEO:</strong> Faster sites tend to rank higher in search engine results,
                            especially after Google's emphasis on site speed as a ranking factor.
                        </li>
                        <li><strong>Reduced HTTP Requests:</strong> By removing embeds and unnecessary JavaScript, you
                            reduce the number of requests made by your site, improving performance.
                        </li>
                    </ul>
                </div>

            </div>


        </div>
    </div>
</div>


