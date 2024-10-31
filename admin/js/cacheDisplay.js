(function ($) {
    'use strict';

    function displayCachedItems(action, targetElement, errorMessage) {
        $.post(ajaxurl, {action: action}, function (response) {
            $(targetElement).html(response.data);
        }).fail(function () {
            $(targetElement).html('<div class="alert alert-danger">' + errorMessage + '</div>');
            utils.logError('displayCachedItems', errorMessage);
        });
    }

    function displayCachedPages() {
        displayCachedItems('wppfep_list_cached_pages', '#cached-pages-list', 'An error occurred while loading cached pages.');
    }

    function displayCachedPosts() {
        displayCachedItems('wppfep_list_cached_posts', '#cached-posts-list', 'An error occurred while loading cached posts.');
    }

    function displayCachedProducts() {
        displayCachedItems('wppfep_list_cached_products', '#cached-products-list', 'An error occurred while loading cached products.');
    }

    function displayAllCachedItems() {
        displayCachedPages();
        displayCachedPosts();
        displayCachedProducts();
    }

    window.cacheDisplay = {
        displayCachedPages: displayCachedPages,
        displayCachedPosts: displayCachedPosts,
        displayCachedProducts: displayCachedProducts,
        displayAllCachedItems: displayAllCachedItems
    };

    $(document).ready(function () {
        cacheDisplay.displayAllCachedItems();
    });
})(jQuery);
