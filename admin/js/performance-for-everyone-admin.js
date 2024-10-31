(function ($) {
    'use strict';

    $(document).ready(function () {

        const apiKey = 'AIzaSyBzBv6Yok4km4-iWwxWNqmBftFkUHScI5s';
        $('#measure-performance').on('click', function () {
            const urlToMeasure = $('#site-url').val();
            if (urlToMeasure) {
                performanceData.measurePerformance(apiKey, urlToMeasure);
            } else {
                $('#performance-results').html('<div class="alert alert-danger">Please enter a valid URL to measure.</div>');
            }
        });

    });
})(jQuery);


