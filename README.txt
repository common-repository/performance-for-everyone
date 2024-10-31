=== Website Performance for Everyone - Cache, Speed, DB Backups & optimizations and more  ===
Contributors: wppforeveryone
Donate link: https://wppfe.com/
Tags: performance, cache, database, seo, speed
Requires at least: 6.0
Tested up to: 6.6.2
Requires PHP: 7.4
Stable tag: 1.3.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

🛠️ Speed up your WordPress site effortlessly! Optimize database, JS, images, test speed, cache pages/products, and more for top performance!

== Description ==

🚀 Performance for Everyone is the ultimate plugin for optimizing your WordPress website's speed and performance. It's built to empower website owners, developers, and agencies to boost their site's speed by cleaning up databases, optimizing JavaScript, managing caching, and much more.

### ⚡ Key Features:
 1. **Cache Optimization:**
    - Cache pages, posts, and products for faster loading.
    - Minifies HTML automatically on cache
    - If WooCommerce is activated, you can also cache product pages.
    - Easily clear the cache manually for better performance.
    - Delete all cached objects in one click or separately by post type
    - Delete individual page/post/product cache if needed

 2. **Database Settings:**
    - Clean up your database by removing unnecessary data such as post revisions, spam comments, transient options, and orphaned metadata.
    - Run optimization queries to defragment database tables, ensuring efficient data management.
    - Review and clean the `wp_options` table to ensure only essential data is autoloaded.
    - Regularly clean up term relationships and other unused data for a more streamlined database.
    - Backup your database before performing cleanups and monitor database performance, including size and optimization results before and after cleanup.

 3. **JavaScript Settings:**
    - Disable jQuery Migrate and embedded scripts for a leaner codebase.
    - Add 'defer' to JS scripts to prioritize content loading.

 5. **General Settings:**
    - Enable Gzip compression to shrink file sizes sent from your server.
    - Disable default emoji scripts for a cleaner, faster site.

 7. **Speed Test:**
    - Measure your website’s loading speed for both mobile and desktop.
    - Get insights and identify issues that may impact your site's performance.
    - Access a performance dashboard with real-time data, including page load times and optimization suggestions.
    - Delete results to keep DB optimized;

 8. **Images:**
    - Preload images for better performance.


### 🌟 Upcoming Features ⏳:
 - Automatic DB backup every X time.
 - Convert images to webp format
 - Cache pages after pages/posts/products saved
 - Scheduled Database Cleanups
 - Database Health Automatic Monitoring
 - WooCommerce-Specific Optimizations
 - Browser Caching Enhancements
 - Priority Support
 - Image CDN Integration
 - Advanced Page Preloading
 - Keeping Database backups in Cloud Service

== Third-Party Services ==
This plugin uses the following third-party services:

1. **Google PageSpeed Insights API**
   - **Purpose**: This plugin relies on the Google PageSpeed Insights API to measure the performance of your website. When you click the "Measure My Website Performance" button, the plugin sends your website URL to the Google PageSpeed Insights service to retrieve performance data.
   - **Data Sent**: Your website URL is sent to the Google PageSpeed Insights service.
   - **Service Link**: [Google PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/)
   - **Privacy Policy**: [Google Privacy Policy](https://policies.google.com/privacy)
   - **Terms of Use**: [Google PageSpeed API Terms of Use](https://developers.google.com/speed/docs/insights/terms)

== Installation ==

🚀 To install and start optimizing your website:

1. Download the plugin from the WordPress plugin directory or upload the `performance-for-everyone` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to Performance to configure the plugin to suit your optimization needs.
4. Start optimizing your website by enabling caching, cleaning up the database, and running performance tests.

== Frequently Asked Questions ==

= ❓ How do I cache my website’s pages and posts to improve WordPress performance? =
Go to Performance > Cache Settings and enable the options to cache pages, posts, and products. Caching reduces loading times, which significantly boosts your WordPress performance. You can also set cache expiration times and clear the cache manually for maximum flexibility.

= 🔄 How do I clean up my database to enhance WordPress speed? =
Navigate to Performance > Database Cleaner. Here, you can choose which data to remove, such as old revisions, spam comments, or expired transient data. By optimizing your database, you can improve both WordPress speed and performance.

= 📝 Can I measure my website's loading time to assess WordPress performance? =
Yes! The plugin includes a real-time performance dashboard that measures your website’s loading speed on both mobile and desktop. It provides insights and recommendations to improve WordPress speed based on this data.

= 📱 Is the plugin optimized for mobile WordPress performance? =
Absolutely! Performance for Everyone optimizes mobile loading times through features like lazy loading and responsive design improvements, ensuring excellent WordPress speed across all devices.

= ⚙️ How does the plugin help optimize JavaScript and CSS for better WordPress performance? =
The plugin can minify and combine JavaScript and CSS files, which decreases file sizes and the number of requests, improving both WordPress speed and overall site performance.

= 🌐 Can I enable Gzip compression to enhance WordPress speed? =
Yes! You can enable Gzip compression under Performance > Gzip Settings. Gzip reduces the file sizes sent to users, which significantly improves WordPress speed by reducing load times.


== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png
5. screenshot-5.png
6. screenshot-6.png

== Changelog ==

= 1.3.7 =
* Fix deactivation hook

= 1.3.6 =
* Fix notification part

= 1.3.5 =
* Update requirements for the wordpress version

= 1.3.4 =
* Upgrade fix elementor cache re-validating issues we had

= 1.3.3 =
* Added functionality to send inquiries to wppfe.
* Added functionality to fix caching pages on elemntor regeneration of css files.

= 1.3.2 =
* Added functionality to clear test speed results.
* Added functionality to clear DB performance test results.

= 1.3.0 =
* Added functionality to clear all cached objects at once.

= 1.2.7 =
* Added posibility to delete performance(speed test) results

= 1.2.6 =
* fix HTML minifier for caching posts.

= 1.2.5 =
* Make Dashboard fix for the height

= 1.2.4 =
* Make availability to delete DB backups

= 1.2.3 =
* Fix short description requirements

= 1.2.2 =
* Fix Defer scripts issue when logged as admin and trying to edit/create posts

= 1.2.1 =
* Fix FAQ in readme

= 1.2.0 =
* Initial fixed plugin and ready to go live:)

= 1.0.0 =
* Initial release with caching, database cleaning, JS/CSS optimization, Gzip compression, and performance measurement.

== Upgrade Notice ==

= 1.3.7 =
Upgrade this because when deactivating our plugin it get error and not deactivating :(

= 1.3.4 =
Upgrade fix elementor cache re-validating issues we had

= 1.3.0 =
Upgrade to have a button to clear all cached pages/posts/products in one click.

= 1.2.2 =
Please upgrade plugin to have fully working posts/pages etc. when logged in as admin.

= 1.0.0 =
First release – start optimizing your site with advanced caching, database cleaning, and JavaScript and CSS optimization.

== Additional Information ==

- 📋 License: GPLv2 or later.
- 💬 Support: If you need assistance, feel free to reach out via [WPPFE.com](https://wppfe.com/contacts).
