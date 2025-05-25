<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add menu item
function rgst_add_admin_menu() {
    add_options_page(
        'Google Sheet Table Settings',
        'Google Sheet Table',
        'manage_options',
        'realtime-google-sheet-table',
        'rgst_settings_page'
    );
}
add_action('admin_menu', 'rgst_add_admin_menu');

// Register settings
function rgst_register_settings() {
    register_setting('rgst_settings', 'rgst_api_key');
    register_setting('rgst_settings', 'rgst_spreadsheet_id');
    register_setting('rgst_settings', 'rgst_sheet_range');
    register_setting('rgst_settings', 'rgst_refresh_interval', array(
        'type' => 'integer',
        'default' => 60
    ));
}
add_action('admin_init', 'rgst_register_settings');

// Settings page HTML
function rgst_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form action="options.php" method="post">
            <?php
            settings_fields('rgst_settings');
            do_settings_sections('rgst_settings');
            ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="rgst_api_key">Google API Key</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="rgst_api_key" 
                               name="rgst_api_key" 
                               value="<?php echo esc_attr(get_option('rgst_api_key')); ?>" 
                               class="regular-text">
                        <p class="description">Enter your Google Sheets API key</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="rgst_spreadsheet_id">Spreadsheet ID</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="rgst_spreadsheet_id" 
                               name="rgst_spreadsheet_id" 
                               value="<?php echo esc_attr(get_option('rgst_spreadsheet_id')); ?>" 
                               class="regular-text">
                        <p class="description">Enter the ID of your Google Spreadsheet</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="rgst_sheet_range">Sheet Range</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="rgst_sheet_range" 
                               name="rgst_sheet_range" 
                               value="<?php echo esc_attr(get_option('rgst_sheet_range', 'Sheet1!A1:Z1000')); ?>" 
                               class="regular-text">
                        <p class="description">Enter the sheet range (e.g., Sheet1!A1:Z1000)</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="rgst_refresh_interval">Refresh Interval</label>
                    </th>
                    <td>
                        <input type="number" 
                               id="rgst_refresh_interval" 
                               name="rgst_refresh_interval" 
                               value="<?php echo esc_attr(get_option('rgst_refresh_interval', 60)); ?>" 
                               min="10" 
                               class="small-text">
                        <p class="description">Enter the refresh interval in seconds (minimum 10)</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
        
        <div class="rgst-usage">
            <h2>Usage</h2>
            <p>Use the shortcode <code>[google_sheet_table]</code> to display the table in your posts or pages.</p>
            <p>Make sure you have:</p>
            <ol>
                <li>Created a project in Google Cloud Console</li>
                <li>Enabled Google Sheets API</li>
                <li>Created an API key</li>
                <li>Made your Google Sheet publicly accessible or shared with appropriate permissions</li>
            </ol>
        </div>
    </div>
    <?php
}
