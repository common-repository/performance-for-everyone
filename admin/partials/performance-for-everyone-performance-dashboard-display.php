<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 grid-reset-height">
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
            <h2 class="text-info">Performance Dashboard</h2>
            <small><strong>Note:</strong> This plugin uses the Google PageSpeed Insights API to measure website
                performance. Your website URL is sent to Google for analysis. You can review Google\'s <a
                        href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and <a
                        href="https://developers.google.com/speed/docs/insights/terms" target="_blank">Terms of Use</a>
                for more information.</small>
            <h5 class="mt-3">Click the button below to measure your website's performance using Google PageSpeed Insights.</h5>
            <hr>
            <label for="site-url">
                Site URL: <strong><?php
					echo get_site_url(); ?></strong>
                <input type="hidden" disabled id="site-url" value="<?php
				echo get_site_url(); ?>"/>
                <br> <br>
                <button id="measure-performance" class="btn btn-primary">Measure My Website Performance</button>
                <div id="performance-results">
                    <div id="performance-details"></div>
                </div>
            </label>
        </div>
        <div class="bg-gray-100 text-black-50 p-6 rounded-lg wppfe-faq-section">
            <h2 class="text-info">FAQ</h2>
            <hr>
            <button class="wppfe-accordion bg-info">Why is it important to assess both Mobile and Desktop performance?
            </button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-xl font-bold text-gray-800">1. Different User Experiences:</h4>
                <p class="text-gray-600 leading-relaxed">Mobile and desktop users often experience websites differently
                    due to screen size, network conditions, and
                    device capabilities. Assessing both ensures your website performs well across all platforms,
                    offering the best user experience regardless of the device used.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Mobile Users are Increasing:</h4>
                <p class="text-gray-600 leading-relaxed">A significant portion of internet traffic comes from mobile
                    devices. Focusing on mobile assessments ensures
                    that your website caters to this growing user base, avoiding slow load times or usability issues
                    that could lead to higher bounce rates.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Google Prioritizes Mobile-First Indexing:</h4>
                <p class="text-gray-600 leading-relaxed">Google now uses mobile-first indexing, meaning it primarily
                    uses the mobile version of your site for ranking and indexing. Ensuring that your mobile performance
                    is optimized can improve your SEO rankings, visibility, and overall user engagement.</p>

                <h4 class="text-xl font-bold text-gray-800">4. Desktop Still Matters:</h4>
                <p class="text-gray-600 leading-relaxed">While mobile is growing, many users, especially in certain
                    industries, still prefer desktop. A fast, optimized desktop experience is essential for keeping
                    those users engaged and happy, particularly for tasks that might be easier to perform on larger
                    screens, such as complex forms or in-depth browsing.</p>
            </div>

            <button class="wppfe-accordion bg-info mt-1">What do performance measurements and suggestions in Google
                PageSpeed Insights mean?
            </button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">

                <h4 class="text-xl font-bold text-gray-800">1. Avoid serving legacy JavaScript to modern browsers:</h4>
                <p class="text-gray-600 leading-relaxed">Modern browsers can handle more efficient JavaScript formats,
                    but legacy JavaScript is often loaded to support
                    older browsers. By avoiding this, you can reduce the size of JavaScript files served to modern
                    browsers, potentially saving 11 KiB and speeding up
                    your website for most users.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Reduce unused JavaScript:</h4>
                <p class="text-gray-600 leading-relaxed">Unused JavaScript is code that is downloaded but not executed
                    by the browser. Reducing this can significantly
                    improve load times and reduce resource consumption, potentially saving 275 KiB. It’s important to
                    remove unnecessary scripts or load them conditionally
                    when needed.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Properly size images:</h4>
                <p class="text-gray-600 leading-relaxed">Large images can slow down your website, especially on mobile
                    devices. By ensuring images are sized correctly for
                    the display they’re being used on, you can save 30 KiB, leading to faster load times and better
                    performance on both mobile and desktop devices.</p>

                <h4 class="text-xl font-bold text-gray-800">4. Time to Interactive (TTI):</h4>
                <p class="text-gray-600 leading-relaxed">Time to Interactive measures how long it takes for the website
                    to become fully interactive. At 2.6 seconds, your
                    site is close to the ideal target, but improvements can be made. Reducing the time to interactive
                    creates a smoother, faster experience for users.</p>

                <h4 class="text-xl font-bold text-gray-800">5. Total Blocking Time (TBT):</h4>
                <p class="text-gray-600 leading-relaxed">Total Blocking Time measures how long users have to wait before
                    they can interact with the page after it starts
                    loading. A TBT of 390 ms is decent, but reducing it further can enhance responsiveness, especially
                    on slower devices. This can be achieved by optimizing
                    JavaScript execution and reducing long tasks.</p>

                <h4 class="text-xl font-bold text-gray-800">6. Serve static assets with an efficient cache policy:</h4>
                <p class="text-gray-600 leading-relaxed">Caching static assets (such as images, CSS, and JavaScript
                    files) ensures that repeat visitors don’t need to
                    re-download the same resources. By setting up an efficient caching policy for the 4 identified
                    resources, you can improve load times for returning visitors.</p>

                <h4 class="text-xl font-bold text-gray-800">7. Max Potential First Input Delay (FID):</h4>
                <p class="text-gray-600 leading-relaxed">First Input Delay measures the time from when a user first
                    interacts with your site (e.g., clicking a button)
                    to when the browser responds. A potential FID of 220 ms is within an acceptable range, but further
                    reducing it can enhance your site's responsiveness,
                    leading to a better user experience.</p>

                <h4 class="text-xl font-bold text-gray-800">8. Avoid an excessive DOM size:</h4>
                <p class="text-gray-600 leading-relaxed">An excessive DOM size refers to having too many HTML elements
                    on the page (891 in this case). A large DOM can
                    slow down rendering and interactivity, especially on mobile devices. Reducing the number of elements
                    can improve performance and make the site easier
                    to maintain.</p>
            </div>

            <button class="wppfe-accordion bg-info mt-1">What is the Mobile Score, and how much should it be?</button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-xl font-bold text-gray-800">1. Mobile Score Explained:</h4>
                <p class="text-gray-600 leading-relaxed">The mobile score in Google PageSpeed Insights reflects how well
                    your website performs on mobile devices. It measures
                    various aspects of your site’s speed, responsiveness, and user experience when accessed from
                    smartphones or tablets. Key metrics include load time, interactivity,
                    and visual stability on mobile.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Ideal Mobile Score:</h4>
                <p class="text-gray-600 leading-relaxed">A mobile score of 90 or above is considered "Good," meaning
                    your website is optimized for mobile devices.
                    Scores between 50 and 89 are considered "Needs Improvement," and below 50 is "Poor." Aim for a score
                    above 90 to ensure your website is fast and provides
                    a smooth experience for mobile users.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Why the Mobile Score Matters:</h4>
                <p class="text-gray-600 leading-relaxed">With the increasing number of mobile users, having a high
                    mobile score is essential for keeping users engaged and
                    reducing bounce rates. Additionally, Google uses mobile performance as part of its ranking criteria,
                    meaning a good mobile score can improve your visibility
                    in search results.</p>
            </div>

            <button class="wppfe-accordion bg-info mt-1">What is the Desktop Score, and how much should it be?</button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-xl font-bold text-gray-800">1. Desktop Score Explained:</h4>
                <p class="text-gray-600 leading-relaxed">The desktop score in Google PageSpeed Insights evaluates how
                    well your website performs on traditional desktop computers and laptops. It focuses on metrics like
                    load time, interactivity, and visual stability but under desktop-specific conditions, where users
                    generally have better hardware and faster internet connections.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Ideal Desktop Score:</h4>
                <p class="text-gray-600 leading-relaxed">Similar to the mobile score, an ideal desktop score is 90 or
                    above. Scores between 50 and 89 indicate "Needs Improvement," and below 50 is "Poor." A desktop
                    score above 90 ensures fast loading and a smooth experience for desktop users.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Why the Desktop Score Matters:</h4>
                <p class="text-gray-600 leading-relaxed">While mobile usage is growing, desktop users are still
                    significant, especially for tasks like e-commerce shopping,
                    filling out detailed forms, or research. A high desktop score ensures these users enjoy fast
                    performance and reduces the likelihood of frustration
                    or abandonment due to slow load times.</p>
            </div>

            <button class="wppfe-accordion bg-info mt-1">Why should I measure my website's performance?</button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-xl font-bold text-gray-800">1. User Experience:</h4>
                <p class="text-gray-600 leading-relaxed">Website performance plays a crucial role in delivering a smooth
                    and satisfying experience for your visitors.
                    A slow website can frustrate users, causing them to leave before engaging with your content.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Search Engine Optimization (SEO):</h4>
                <p class="text-gray-600 leading-relaxed">Google uses website speed as a ranking factor. A faster website
                    will not only keep visitors happy but can
                    also improve your search engine rankings, bringing more traffic to your site.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Conversion Rates:</h4>
                <p class="text-gray-600 leading-relaxed">Faster websites tend to have better conversion rates, as
                    visitors are more likely to complete actions like
                    making a purchase or filling out a form when the website responds quickly.</p>
            </div>

            <button class="wppfe-accordion bg-info mt-1">Why is page speed important for my website?</button>
            <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-xl font-bold text-gray-800">1. First Impressions Matter:</h4>
                <p class="text-gray-600 leading-relaxed">A fast-loading website creates a good first impression for new
                    visitors. Pages that load slowly can lead to
                    higher bounce rates, meaning users leave without exploring your content.</p>

                <h4 class="text-xl font-bold text-gray-800">2. Mobile Experience:</h4>
                <p class="text-gray-600 leading-relaxed">With an increasing number of users browsing on mobile devices,
                    page speed is especially important. Mobile
                    networks can be slower, so a website that is optimized for speed will perform better across all
                    devices.</p>

                <h4 class="text-xl font-bold text-gray-800">3. Reduced Server Costs:</h4>
                <p class="text-gray-600 leading-relaxed">By optimizing your website's speed, you reduce the amount of
                    bandwidth and resources your server needs to
                    handle, leading to potential cost savings on hosting.</p>
            </div>

        </div>
    </div>
</div>