jQuery(document).ready(function($) {
    $('.delete-result').on('click', function() {
        if (confirm('Are you sure you want to delete this result?')) {
            var resultId = $(this).data('result-id');
            var row = $(this).closest('tr');

            $.ajax({
                url: wppfep_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_performance_result',
                    result_id: resultId,
                    nonce: wppfep_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        row.fadeOut('slow', function() {
                            row.remove();
                        });
                    } else {
                        alert('Failed to delete the result: ' + response.data);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the result.');
                }
            });
        }
    });
});
