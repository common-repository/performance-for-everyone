(function ($) {
    'use strict';

    function handleCacheAction(buttonId, action, successMessage) {
        const buttonElement = document.querySelector(buttonId);
        if (!buttonElement) {
            console.error(`Element with selector "${buttonId}" not found.`);
            return; // Exit the function if the element is not found
        }

        buttonElement.addEventListener('click', function (e) {
            e.preventDefault();
            const button = this;
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ action: action })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        utils.showNotification(successMessage, 'success');
                        cacheDisplay.displayAllCachedItems();
                    } else {
                        throw new Error(data.data || 'An error occurred while processing.');
                    }
                })
                .catch(error => {
                    utils.showNotification(error.message, 'error');
                    utils.logError('handleCacheAction', error);
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
        });
    }

    function handleDeleteCacheItem() {
        document.body.addEventListener('click', function (event) {
            const deleteButton = event.target.closest('.delete-cache');

            if (deleteButton) {
                const itemId = deleteButton.getAttribute('data-id');
                const itemType = deleteButton.getAttribute('data-type');

                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'wppfep_delete_cache_item',
                        id: itemId,
                        type: itemType,
                        nonce: wppfep.nonce
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            utils.showNotification('Cache item deleted successfully.', 'success');
                            location.reload();
                        } else {
                            throw new Error(data.data || 'An error occurred.');
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                        utils.logError('handleDeleteCacheItem', error);
                    });
            }
        });
    }

    window.cacheActions = {
        handleCacheAction: handleCacheAction,
        handleDeleteCacheItem: handleDeleteCacheItem,
    };

    $(document).ready(function () {
        cacheActions.handleCacheAction('#cache-pages-button', 'cache_all_pages', 'Pages cached successfully!');
        cacheActions.handleCacheAction('#clear-pages-cache-button', 'clear_pages_cache', 'Pages cache cleared successfully!');
        cacheActions.handleCacheAction('#cache-posts-button', 'cache_all_posts', 'Posts cached successfully!');
        cacheActions.handleCacheAction('#clear-posts-cache-button', 'clear_posts_cache', 'Posts cache cleared successfully!');
        cacheActions.handleCacheAction('#clear-all-caches-button', 'clear_all_caches', 'All caches cleared successfully!');

        const products_cache_create_button = document.getElementById('cache-products-button');
        const products_cache_delete_button = document.getElementById('clear-products-cache-button');

        if(products_cache_create_button && products_cache_delete_button){
            cacheActions.handleCacheAction('#cache-products-button', 'cache_all_products', 'Products cached successfully!');
            cacheActions.handleCacheAction('#clear-products-cache-button', 'clear_products_cache', 'Products cache cleared successfully!');
        }

        cacheActions.handleDeleteCacheItem();
    });

})(jQuery);
