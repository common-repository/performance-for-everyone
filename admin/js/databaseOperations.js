(function ($) {
    'use strict';


    function handleButtonAction(buttonId, action, successMessage) {
        $(buttonId).on('click', function (e) {
            e.preventDefault();
            var $button = $(this);
            var originalHtml = $button.html();
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            $.post(ajaxurl, {action: action})
                .done(function () {
                    utils.showNotification(successMessage, 'success');
                })
                .fail(function (error) {
                    utils.showNotification('An error occurred while performing the action.', 'error');
                    utils.logError('handleButtonAction', error);
                })
                .always(function () {
                    $button.prop('disabled', false).html(originalHtml);
                });
        });
    }

    function setupDatabaseBackup() {
        $('#backup-db-button').on('click', function () {
            var $button = $(this);
            var originalHtml = $button.html();
            $button.prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i> Backing Up...');

            $.post(ajaxurl, {action: 'backup_database'})
                .done(function (response) {
                    if (response.success) {
                        utils.showNotification('Database backup completed.', 'success');
                        displayBackupList();
                    } else {
                        utils.showNotification(response.data, 'error');
                    }
                })
                .fail(function (error) {
                    utils.showNotification('An error occurred while backing up the database.', 'error');
                    utils.logError('setupDatabaseBackup', error);
                })
                .always(function () {
                    $button.prop('disabled', false).html(originalHtml);
                });
        });
    }

    function displayBackupList() {
        $.post(ajaxurl, {action: 'list_backups'})
            .done(function (response) {
                if (response.success) {
                    var backupsHtml = '';
                    $.each(response.data.backups, function (index, backup) {
                        backupsHtml +=
                            '<div class="py-2 position-relative d-inline-block">' +
                            '<a href="' + backup.download_link + '" download="' + backup.file_name + '">' + backup.file_name + '</a>' +
                            ' <button class="delete-backup" data-file="' + backup.file_name + '" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
                             '</div>';
                    });
                    $('#backup-list').html(backupsHtml);
                    $('#backup-db-button').show();
                    $('#backup-limit-message').hide();

                    // Attach event listener for delete buttons
                    $('.delete-backup').on('click', function (e) {
                        e.preventDefault();
                        var fileName = $(this).data('file');
                        deleteBackup(fileName);
                    });
                } else {
                    $('#backup-list').html('<div class="alert alert-danger">Error loading backups: ' + response.data + '</div>');
                }
            })
            .fail(function (error) {
                $('#backup-list').html('<div class="alert alert-danger">An error occurred while loading backups.</div>');
                utils.logError('displayBackupList', error);
            });
    }

    function deleteBackup(fileName) {
        if (!confirm('Are you sure you want to delete this backup?')) {
            return;
        }

        $.post(ajaxurl, {action: 'wppfep_delete_backup', file: fileName})
            .done(function (response) {
                if (response.success) {
                    utils.showNotification('Backup deleted successfully.', 'success');
                    displayBackupList(); // Refresh the list after deletion
                } else {
                    utils.showNotification('Error deleting backup: ' + response.data, 'error');
                }
            })
            .fail(function (error) {
                utils.showNotification('An error occurred while deleting the backup.', 'error');
                utils.logError('deleteBackup', error);
            });
    }

    function setupCheckDBPerformance() {
        $('#check-db-performance').on('click', function () {
            $('#db-performance-results').html('<div class="alert alert-info">Loading...</div>');

            $.post(ajaxurl, {action: 'check_db_performance'})
                .done(function (response) {
                    $('#db-performance-results').html(response.data);
                })
                .fail(function (error) {
                    $('#db-performance-results').html('<div class="alert alert-danger">An error occurred while checking the database performance.</div>');
                    utils.logError('setupCheckDBPerformance', error);
                });
        });
    }

    function setupDatabaseActions() {
        handleButtonAction('#db-cleanup-button', 'wppfep_db_cleanup', 'Database cleanup completed.');
        handleButtonAction('#optimize-db-tables-button', 'wppfep_optimize_db_tables', 'Database tables optimized.');
        handleButtonAction('#cleanup-unused-data-button', 'wppfep_cleanup_unused_data', 'Unused data cleaned up.');
        handleButtonAction('#optimize-autoloaded-data-button', 'wppfep_optimize_autoloaded_data', 'Autoloaded data optimized.');
    }

    window.databaseOperations = {
        handleButtonAction: handleButtonAction,
        setupDatabaseBackup: setupDatabaseBackup,
        displayBackupList: displayBackupList,
        setupCheckDBPerformance: setupCheckDBPerformance,
        setupDatabaseActions: setupDatabaseActions // Added setupDatabaseActions to the exposed functions
    };


    $(document).ready(function () {

        databaseOperations.setupDatabaseActions();
        databaseOperations.setupDatabaseBackup();
        databaseOperations.setupCheckDBPerformance();
        databaseOperations.displayBackupList();


        $('.wppfe-db-delete-record').click(function() {
            var recordId = $(this).data('id');
            var confirmed = confirm('Are you sure you want to delete this record?');

            if (confirmed) {
                var row = $(this).closest('tr'); // Get the table row to remove later

                $.ajax({
                    url: ajaxurl, // WordPress AJAX handler
                    type: 'POST',
                    data: {
                        action: 'delete_db_record',
                        record_id: recordId,
                    },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Failed to delete record.');
                        }
                    }
                });
            }
        });

    });

})(jQuery);