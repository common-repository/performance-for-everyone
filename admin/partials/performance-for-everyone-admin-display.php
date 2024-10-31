<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap wppfe_performance">
    <section class="grid grid-cols-5 gap-6 p-2 mb-6 grid-reset-height">
        <div class="col-span-3 col-start-2">
            <div class="bg-blue-50 text-black p-6 rounded-lg">
                <h2 class="text-3xl font-bold m-0 mb-2">Welcome to Performance for Everyone!</h2>
                <p class="text-base text-gray-700 mb-3">
                    Thank you for using our plugin! We're dedicated to making Performance for Everyone the best
                    WordPress
                    performance optimization tool available. Our goal is to help you achieve faster, smoother websites
                    while
                    providing a seamless user experience.
                </p>
                <p class="text-base text-gray-700 mb-3">
                    We have exciting plans for future updates, including more detailed performance analytics, one-click
                    optimization features, and much more! Your support helps us make these improvements. If you enjoy
                    using our plugin, we would really appreciate a <strong>5-star</strong> rating on the
                    <a href="https://wordpress.org/support/plugin/performance-for-everyone/reviews/#postform"
                       target="_blank">WordPress plugin directory</a>.
                </p>
                <p class="text-base text-gray-700 mb-3">
                    Got questions, feedback, or need assistance? We're here to help! Feel free to contact us anytime
                    through our
                    <a href="https://wppfe.com/contact" class="text-blue-600 underline">Contact Form</a> on wppfe.com.
                    Together,
                    let's make WordPress websites faster and more efficient for everyone!
                </p>
                <p class="text-base text-gray-700">
                    Thank you for being part of our journey!
                </p>
            </div>
        </div>
    </section>
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 p-2 grid-reset-height">
        <div>
            <div class="bg-gray-100 text-black p-6 rounded-lg">
                <h2 class="text-2xl font-bold m-0 mb-1">System Requirements</h2>
                <p class="text-sm text-gray-500">This section provides a summary of the current system requirements,
                    including PHP version, WordPress version, database version, and server environment. Keeping these
                    components updated is essential for security, compatibility, and optimal performance of your
                    WordPress website.</p>
                <hr class="mb-3 mt-1">
				<?php
				echo $systemInformation; ?>
            </div>

            <div class="bg-gray-100 text-black p-6 mt-4 rounded-lg">
                <h2 class="text-2xl font-bold m-0 mb-1">Database Check</h2>
                <p class="text-sm text-gray-500">Database health is crucial for website performance. This section checks
                    for issues like database overhead, unused space, excessive post revisions, and autoloaded data.
                    Regular database optimization helps speed up your website and prevents potential data
                    corruption.</p>
                <hr class="mb-3 mt-1">
				<?php
				echo $databaseCheck; ?>
            </div>
        </div>

        <div>
            <div class="bg-gray-100 text-black p-6 rounded-lg">
                <h2 class="text-2xl font-bold m-0 mb-1">Performance Checks</h2>
                <p class="text-sm text-gray-500">Performance checks provide insights into your site's resource usage,
                    memory limits, cache status, and other performance metrics. Keeping these in check ensures your site
                    runs smoothly, providing a better user experience and improved SEO rankings.</p>
                <hr class="mb-3 mt-1">
				<?php
				echo $performanceCheck; ?>
            </div>

            <div class="bg-gray-100 text-black p-6 mt-4 rounded-lg">
                <h2 class="text-2xl font-bold m-0 mb-1">Outdated Plugins</h2>
                <p class="text-sm text-gray-500">Outdated plugins can pose security risks and may cause compatibility
                    issues. This section identifies plugins that require updates and categorizes them based on their
                    importance. Major updates are critical and should be addressed immediately, while minor and patch
                    updates focus on new features, enhancements, or bug fixes.</p>
                <hr class="mb-3 mt-1">
                <div class="mb-4">
                    <p><i class="fas fa-exclamation-circle text-red-600"></i> <strong>Major Update:</strong> Critical
                        changes and new features. It's highly recommended to update immediately.</p>
                    <p><i class="fas fa-exclamation-triangle text-yellow-600"></i> <strong>Minor Update:</strong>
                        Includes new features or enhancements. Updating is recommended but not as urgent.</p>
                    <p><i class="fas fa-wrench text-blue-600"></i> <strong>Patch Update:</strong> Bug fixes and
                        improvements. Update at your convenience.</p>
                </div>
                <hr>
				<?php
				echo $pluginsSecurity; ?>
            </div>
        </div>
    </section>
</div>
