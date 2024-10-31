(function ($) {
    'use strict';

    function fetchPageSpeedData(strategy, url, apiKey, callback) {
        $.ajax({
            url: 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed',
            type: 'GET',
            data: {
                key: apiKey,
                url: url,
                strategy: strategy
            }
        }).done(function (data) {
            var performanceScore = data.lighthouseResult.categories.performance.score * 100;
            var webVitalsAssessment = performanceScore >= 90 ? 'succeeded' : 'failed';
            var failedAudits = [];

            $.each(data.lighthouseResult.audits, function (index, audit) {
                if (audit.score < 0.9) {
                    failedAudits.push(audit.title + ': ' + audit.displayValue);
                }
            });

            callback(performanceScore, webVitalsAssessment, failedAudits, data);
        }).fail(function (error) {
            $('#performance-results').html('<div class="alert alert-danger">An error occurred while fetching the performance data.</div>');
            utils.logError('fetchPageSpeedData', error);
        });
    }

    function savePerformanceData(url, data, callback) {
        $.post(ajaxurl, {
            action: 'save_performance_data',
            url: url,
            mobile_score: data.mobileScore,
            desktop_score: data.desktopScore,
            mobile_assessment: data.mobileAssessment,
            desktop_assessment: data.desktopAssessment,
            mobile_failed_audits: data.mobileFailedAudits.join(', '),
            desktop_failed_audits: data.desktopFailedAudits.join(', ')
        }).done(function () {
            $('#performance-results').html('');
            $('#performance-results').append('<div class="alert alert-success">Performance data saved successfully!</div>');
            callback();
        }).fail(function (error) {
            $('#performance-results').html('');
            $('#performance-results').append('<div class="alert alert-danger">An error occurred while saving the performance data.</div>');
            utils.logError('savePerformanceData', error);
        });
    }

    function displaySavedResults() {
        $.post(ajaxurl, { action: 'display_saved_results' })
            .done(function (response) {
                $('#saved-results').html(response.data);
            })
            .fail(function (error) {
                $('#saved-results').append('<div class="alert alert-danger">An error occurred while loading saved results.</div>');
                utils.logError('displaySavedResults', error);
            });
    }

    function renderResults(score, strategy, data) {
        let resultsHtml = '<h2>Performance Results (' + strategy.charAt(0).toUpperCase() + strategy.slice(1) + ')</h2>';
        resultsHtml += '<p><strong>Performance Score:</strong> ' + score + '</p>';
        resultsHtml += '<h3>Good Results</h3>';
        resultsHtml += '<ul class="list-group mb-3">';

        Object.values(data.lighthouseResult.audits).forEach(audit => {
            if (audit.score >= 0.9) {
                resultsHtml += '<li class="list-group-item list-group-item-success">' + audit.title + ': ' + audit.displayValue + '</li>';
            }
        });

        resultsHtml += '</ul>';
        resultsHtml += '<h3>Bad Results</h3>';
        resultsHtml += '<ul class="list-group">';

        Object.values(data.lighthouseResult.audits).forEach(audit => {
            if (audit.score < 0.9) {
                resultsHtml += '<li class="list-group-item list-group-item-danger">' + audit.title + ': ' + audit.displayValue + '</li>';
            }
        });

        resultsHtml += '</ul>';
    }

    function measurePerformance(apiKey, urlToMeasure) {
        $('#performance-results').html('<div class="alert alert-info">Loading...</div>');
        $('#measure-performance').prop('disabled', true);

        function checkTestLimit(callback) {
            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ action: 'check_test_limit', url: urlToMeasure })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        callback();
                    } else {
                        $('#performance-results').html('');
                        $('#performance-results').html('<div class="alert alert-danger">' + data.data + '</div>');
                        $('#measure-performance').prop('disabled', false);
                    }
                })
                .catch(error => {
                    utils.logError('checkTestLimit', error);
                    $('#performance-results').html('');
                    $('#performance-results').html('<div class="alert alert-danger">An error occurred while checking the test limit.</div>');
                    $('#measure-performance').prop('disabled', false);
                });
        }

        checkTestLimit(function () {
            fetchPageSpeedData('mobile', urlToMeasure, apiKey, function (mobileScore, mobileAssessment, mobileFailedAudits, mobileData) {
                renderResults(mobileScore, 'mobile', mobileData);

                fetchPageSpeedData('desktop', urlToMeasure, apiKey, function (desktopScore, desktopAssessment, desktopFailedAudits, desktopData) {
                    renderResults(desktopScore, 'desktop', desktopData);

                    savePerformanceData(urlToMeasure, {
                        mobileScore: mobileScore,
                        desktopScore: desktopScore,
                        mobileAssessment: mobileAssessment,
                        desktopAssessment: desktopAssessment,
                        mobileFailedAudits: mobileFailedAudits,
                        desktopFailedAudits: desktopFailedAudits
                    }, function () {
                        $('#performance-results').html('');
                        displaySavedResults();
                        $('#measure-performance').prop('disabled', false);
                    });
                });
            });
        });
    }

    window.performanceData = {
        fetchPageSpeedData: fetchPageSpeedData,
        savePerformanceData: savePerformanceData,
        displaySavedResults: displaySavedResults,
        renderResults: renderResults,
        measurePerformance: measurePerformance
    };

})(jQuery);
