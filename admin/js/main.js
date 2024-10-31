(function ($) {
    'use strict';

    function initialize() {
        cacheDisplay.displayCachedPages();
        cacheActions.handleCacheAction('#cache-pages-button', 'cache_all_pages', 'Pages Cached Successfully!');
        databaseOperations.handleButtonAction('#db-cleanup-button', 'wppfep_db_cleanup', 'Database cleanup completed.');
    }

    $(document).ready(function () {
        initialize();
    });
})(jQuery);
