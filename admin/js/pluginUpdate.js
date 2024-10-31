(function ($) {
    'use strict';

    function setupPluginUpdate() {
        $('body').on('click', '.update-plugin', function (e) {
            e.preventDefault();

            var $button = $(this);
            var pluginSlug = $button.data('plugin');

            $button.text('Updating...').prop('disabled', true);

            $.post(ajaxurl, {
                action: 'wppfep_update_plugin',
                plugin: pluginSlug,
                nonce: wppfep.nonce
            }).done(function (response) {
                if (response.success) {
                    checkPluginUpdateStatus(pluginSlug, $button);
                } else {
                    alert('Failed to update the plugin: ' + response.data.message);
                    $button.text('Update Now').prop('disabled', false);
                }
            }).fail(function (error) {
                alert('An error occurred: ' + error.responseText);
                utils.logError('setupPluginUpdate', error);
                $button.text('Update Now').prop('disabled', false);
            });
        });


    }

    window.pluginUpdate = {
        setupPluginUpdate: setupPluginUpdate
    };
})(jQuery);
