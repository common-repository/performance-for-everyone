<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wppfe_performance">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-2">
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
            <h2 class="text-info">General Optimisation Settings</h2>
            <hr>
            <form method="post" action="options.php" class="form-horizontal">
				<?php
				settings_fields( 'performance_for_everyone_general_options' );
				do_settings_sections( 'performance_for_everyone_general' );
				submit_button( );
				?>
            </form>
        </div>
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
            <h2 class="text-info">We will answer to your questions</h2>
            <hr>
            <div class="wppfe-faq-section">
                <button class="wppfe-accordion bg-info ">Why should Gzip compression be enabled in WordPress?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <h4 class="text-xl font-bold text-gray-800">1. Reduced File Size:</h4>
                    <p class="text-gray-600 leading-relaxed">Gzip compresses your website's files (HTML, CSS, JS) before sending them to the browser. This
                        significantly reduces file sizes, leading to faster download times for your users.</p>

                    <h4 class="text-xl font-bold text-gray-800">2. Faster Page Load Times:</h4>
                    <p class="text-gray-600 leading-relaxed">Since the files are smaller with Gzip enabled, they load faster. Faster page load times lead to a
                        better user experience and can improve your search engine ranking.</p>

                    <h4 class="text-xl font-bold text-gray-800">3. Improved Bandwidth Usage:</h4>
                    <p class="text-gray-600 leading-relaxed">Gzip minimizes the amount of data transmitted between your server and users' browsers. This
                        reduction in bandwidth usage is especially beneficial for users with slow or limited internet
                        connections.</p>

                    <h4 class="text-xl font-bold text-gray-800">4. Improved SEO and Performance:</h4>
                    <p class="text-gray-600 leading-relaxed">Search engines like Google consider page speed as a ranking factor. Enabling Gzip helps improve
                        performance, which in turn can have a positive impact on your SEO rankings.</p>
                </div>
                <button class="wppfe-accordion mt-1 bg-info">Why should default emojis be disabled in WordPress?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <h4 class="text-xl font-bold text-gray-800">1. Improved Page Speed:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        WordPress adds extra JavaScript and CSS files to handle emoji rendering on your website, even if you're not using emojis.
                        Disabling this feature reduces unnecessary scripts, improving your page's loading speed.
                    </p>

                    <h4 class="text-xl font-bold text-gray-800">2. Reduced HTTP Requests:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Disabling the default emoji feature reduces the number of HTTP requests made by your site, which leads to faster page loads,
                        especially on mobile devices and for users on slower networks.
                    </p>

                    <h4 class="text-xl font-bold text-gray-800">3. Better Performance on Older Browsers:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        WordPress includes emoji scripts for backward compatibility with older browsers that don't support emojis natively. However,
                        most modern browsers support emojis without these additional scripts, so disabling them improves performance for modern users.
                    </p>

                    <h4 class="text-xl font-bold text-gray-800">4. Cleaner Code and Fewer Conflicts:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        By turning off WordPress emojis, you reduce unnecessary JavaScript that might cause conflicts with other plugins or your custom
                        scripts. It also ensures a cleaner codebase, making your site more maintainable.
                    </p>
                </div>
                <button class="wppfe-accordion mt-1 bg-info">Why should WordPress Cron be disabled, and how to set up a real server cron job?</button>
                <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                    <h4 class="text-xl font-bold text-gray-800">1. Reduced Server Load:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        WordPress's default cron system, <code>wp-cron.php</code>, is triggered on every page load. On high-traffic websites, this can lead to increased server load, slowing down performance. Disabling the WordPress cron system helps reduce unnecessary resource usage.
                    </p>

                    <h4 class="text-xl font-bold text-gray-800">2. Better Scheduling Control:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        By using a real server cron job, you gain more control over when tasks run. This allows you to set a fixed interval (e.g., every 5 minutes) for scheduled tasks, making the process more predictable and efficient.
                    </p>

                    <h4 class="text-xl font-bold text-gray-800">3. How to Set Up a Real Cron Job on the Server:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        To set up a real cron job, follow these steps:
                    </p>
                    <ol class="list-decimal list-inside text-gray-600 leading-relaxed">
                        <li><strong>Log in to Your Server:</strong> Use SSH to log in to your server. Most hosting providers give access to SSH through cPanel or similar control panels.</li>
                        <li><strong>Open the Crontab Editor:</strong> Run the command <code>crontab -e</code> to open the cron job editor.</li>
                        <li><strong>Add a Cron Job:</strong> Insert the following line to set up a cron job that runs every 5 minutes:
                            <pre class="bg-gray-100 p-4 rounded"><code>*/5 * * * * wget -q -O - https://yourwebsite.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1</code></pre>
                            Replace <code>https://yourwebsite.com</code> with your actual website URL. This command will silently call the WordPress cron file every 5 minutes.
                        </li>
                        <li><strong>Save the Crontab File:</strong> Save and exit the crontab editor. Your real cron job is now set up and will trigger WordPress scheduled tasks at fixed intervals.</li>
                    </ol>

                    <h4 class="text-xl font-bold text-gray-800">4. Improved Performance:</h4>
                    <p class="text-gray-600 leading-relaxed">
                        By switching to a real server cron job, you prevent WordPress from triggering cron on every page load, resulting in a more efficient use of server resources and faster page loading times.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>