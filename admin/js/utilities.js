(function ($) {
    'use strict';

    function showNotification(message, type) {
        Swal.fire({
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function logError(context, error) {
        console.error('Error in ' + context + ':', error);
    }

    window.toggleDetails = function (type, index) {
        var detailsRow = $('#' + type + '-details-' + index);
        var arrowIcon = $('#' + type + '-arrow-' + index);

        if (detailsRow.is(':visible')) {
            detailsRow.hide();
            arrowIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            detailsRow.show();
            arrowIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
    };

    window.utils = {
        showNotification: showNotification,
        logError: logError
    };
})(jQuery);
