jQuery(document).ready(function($) {
    // Function to fetch and update table data
    function updateTableData() {
        const container = $('#rgst-table-container');
        
        // Show loading state
        container.html('<div class="rgst-loading">Loading...</div>');
        
        // Make AJAX request
        $.ajax({
            url: rgstAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'rgst_get_table_data',
                nonce: rgstAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    container.html(response.data);
                } else {
                    container.html('<div class="rgst-error">' + 
                        (response.data || 'Error loading table data') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                container.html('<div class="rgst-error">Failed to load table data. ' + 
                    'Please try again later.</div>');
                console.error('RGST Ajax Error:', error);
            }
        });
    }

    // Initial load
    updateTableData();

    // Set up periodic refresh
    if (rgstAjax.refresh_interval > 0) {
        setInterval(updateTableData, rgstAjax.refresh_interval);
    }

    // Add refresh button (optional)
    $(document).on('click', '.rgst-refresh-btn', function(e) {
        e.preventDefault();
        updateTableData();
    });
});
