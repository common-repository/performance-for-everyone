<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap wppfe_performance">
    <div class="grid grid-cols-2 gap-4 grid-reset-height">
        <div>
            <div>
                <div class="bg-gray-100 text-black-50 p-6 rounded-lg">
                    <h2 class="text-info">Database Optimization Settings</h2>
                    <hr>
                    <form id="db-settings-form" method="post" action="options.php">
						<?php
						settings_fields( 'performance_for_everyone_db_options' );
						do_settings_sections( 'performance_for_everyone_db' );
						?>
                    </form>
                </div>
            </div>
            <div>
                <div class="bg-gray-100 text-black-50 p-6 mt-4 rounded-lg">
                    <h2 class="text-info">Database Performance results</h2>
                    <br>
                    <button id="check-db-performance" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Check Database Performance</button>
                    <div id="db-performance-results" class="mt-4">
						<?php
						$this->wppfep_display_database_performance_results();
						?>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-gray-100 text-black-50 p-6 rounded-lg ">
                <h2 class="text-info">We will answer to your questions</h2>
                <hr>
                <div class="wppfe-faq-section">
                    <button class="wppfe-accordion bg-info">Why Clean Up Your Database?</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <p class="text-gray-600 leading-relaxed">Over time, your website's database can accumulate a lot
                            of unnecessary data that slows down your site and takes up space. Regularly cleaning your
                            database helps keep your site running smoothly and efficiently.</p>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">What Gets Cleaned Up?</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <h4 class="text-xl font-bold text-gray-800">Post Revisions:</h4>
                        <p class="text-gray-600 leading-relaxed">Whenever you edit a post or page, WordPress saves each
                            version of your changes. These saved versions are called post revisions. While revisions can
                            be helpful, they can pile up and take up a lot of space in your database.</p>

                        <h4 class="text-xl font-bold text-gray-800">Spam Comments:</h4>
                        <p class="text-gray-600 leading-relaxed">Spam comments are unsolicited and irrelevant messages
                            posted in your comment section. They can clutter your database and affect your website's
                            performance.</p>

                        <h4 class="text-xl font-bold text-gray-800">Transient Options:</h4>
                        <p class="text-gray-600 leading-relaxed">Transient options are temporary pieces of data stored
                            in your database to speed up your site. These are useful for caching but can become outdated
                            and take up unnecessary space if not cleaned up.</p>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">How We Clean Up Your Database?</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <h4 class="text-xl font-bold text-gray-800">Identify and Remove Post Revisions:</h4>
                        <p class="text-gray-600 leading-relaxed">We look for all the saved versions of posts and pages
                            that are no longer needed and delete them. This helps reduce the size of your database and
                            improve performance.</p>

                        <h4 class="text-xl font-bold text-gray-800">Delete Spam Comments:</h4>
                        <p class="text-gray-600 leading-relaxed">We scan through the comments section of your site to
                            identify and delete spam comments. This makes your database cleaner and your website
                            faster.</p>

                        <h4 class="text-xl font-bold text-gray-800">Clear Transient Options:</h4>
                        <p class="text-gray-600 leading-relaxed">We identify and remove expired transient options. This
                            frees up space and ensures that your site isn't bogged down by outdated cached data.</p>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">How Often Should This Be Done?</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <p class="text-gray-600 leading-relaxed">We recommend scheduling a database cleanup at least
                            once a month. However, if your site gets a lot of traffic or you make frequent updates, you
                            might benefit from more frequent cleanups.</p>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">Optimize Database Tables</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <p class="text-gray-600 leading-relaxed">Running optimization queries defragments your database
                            tables, which helps improve the efficiency and speed of your database. When you enable
                            database table optimization, our system will periodically run these queries to ensure your
                            tables are optimized and performing at their best.</p>

                        <h4 class="text-xl font-bold text-gray-800">Benefits of Database Table Optimization:</h4>
                        <ul>
                            <li><strong>Improved Performance:</strong> Defragmented tables can process queries faster,
                                leading to quicker load times for your website.
                            </li>
                            <li><strong>Space Efficiency:</strong> Optimization can free up space by reorganizing the
                                storage of data, making your database more efficient.
                            </li>
                            <li><strong>Reduced Latency:</strong> Regular optimization reduces delays in data retrieval,
                                enhancing the overall user experience on your site.
                            </li>
                        </ul>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">Cleaning Up Unused Data</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <p class="text-gray-600 leading-relaxed">Over time, databases can accumulate orphaned metadata,
                            old drafts, and other unused data. Regularly cleaning up orphaned post metadata, term
                            relationships, and other unused data keeps the database efficient.</p>
                    </div>

                    <button class="wppfe-accordion mt-1 bg-info">Optimize Autoloaded Data</button>
                    <div class="wppfe-panel bg-white shadow-md rounded-lg p-6 space-y-6">
                        <p class="text-gray-600 leading-relaxed">Autoloaded data is loaded on every page load. Having
                            too much data set to autoload can slow down your site. Review and clean up the <code>wp_options</code>
                            table to ensure that only essential data is autoloaded.</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-100 text-black-50 p-6 mt-4 rounded-lg">
                <h2 class="text-info">Database Backups</h2>
                <hr>
                <p>Before taking any actions, please backup your database.</p>
                <button id="backup-db-button" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Backup Database</button>
                <div id="backup-limit-message" class="alert alert-danger" style="display:none;">Only 5 backups are
                    allowed.
                </div>
                <div id="backup-results" class="mt-3"></div>
                <h2 class="text-info">Available Backups</h2>
                <div id="backup-list"></div>
            </div>

        </div>
    </div>
</div>

