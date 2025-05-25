jQuery(document).ready(function($) {
    let lastRowCount = 0;
    let isInitialLoad = true;

    // Function to fetch and update table data
    function checkForNewData() {
        const container = $('#rgst-table-container');
        
        // Show loading state only on initial load
        if (isInitialLoad) {
            container.html('<div class="rgst-loading">Loading...</div>');
        }
        
        // Make AJAX request
        $.ajax({
            url: rgstAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'rgst_get_table_data',
                nonce: rgstAjax.nonce,
                last_row: lastRowCount
            },
            success: function(response) {
                if (response.success) {
                    if (isInitialLoad) {
                        // Initial load - replace entire container
                        container.html(response.data.html);
                        isInitialLoad = false;
                    } else if (response.data.new_rows && response.data.new_rows.length > 0) {
                        // Append new rows with animation
                        const tbody = container.find('table.rgst-table tbody');
                        response.data.new_rows.forEach(function(row) {
                            const tr = $('<tr>').css('display', 'none');
                            row.forEach(function(cell) {
                                tr.append($('<td>').text(cell));
                            });
                            tbody.append(tr);
                            tr.fadeIn('slow');
                        });
                    }
                    
                    // Update row count
                    lastRowCount = response.data.total_rows;
                } else {
                    if (isInitialLoad) {
                        container.html('<div class="rgst-error">' + 
                            (response.data || 'Error loading table data') + '</div>');
                    }
                    console.error('RGST Error:', response.data);
                }
            },
            error: function(xhr, status, error) {
                if (isInitialLoad) {
                    container.html('<div class="rgst-error">Failed to load table data. ' + 
                        'Please try again later.</div>');
                }
                console.error('RGST Ajax Error:', error);
            }
        });
    }

    // Initial load
    checkForNewData();

    // Set up polling for new data every 2 seconds
    setInterval(checkForNewData, 2000);

    // Add refresh button (optional)
    $(document).on('click', '.rgst-refresh-btn', function(e) {
        e.preventDefault();
        checkForNewData();
    });
});
