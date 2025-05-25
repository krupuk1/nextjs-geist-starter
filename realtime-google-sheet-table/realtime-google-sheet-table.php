<?php
/*
 * Plugin Name: Realtime Google Sheet Table
 * Description: Display table from Google Sheet with realtime updates
 * Version: 1.0.0
 * Author: Haris Iwan
 * Text Domain: realtime-google-sheet-table
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('RGST_VERSION', '1.0.0');
define('RGST_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RGST_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include admin settings
if (is_admin()) {
    require_once RGST_PLUGIN_DIR . 'admin/admin-settings.php';
}

// Enqueue scripts and styles
function rgst_enqueue_scripts() {
    wp_enqueue_style('rgst-style', RGST_PLUGIN_URL . 'assets/css/style.css', array(), RGST_VERSION);
    wp_enqueue_script('rgst-script', RGST_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), RGST_VERSION, true);
    
    wp_localize_script('rgst-script', 'rgstAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rgst_nonce'),
        'refresh_interval' => get_option('rgst_refresh_interval', 60) * 1000
    ));
}
add_action('wp_enqueue_scripts', 'rgst_enqueue_scripts');

// Register shortcode
function rgst_table_shortcode($atts) {
    $atts = shortcode_atts(array(
        'class' => '',
    ), $atts);

    ob_start();
    ?>
    <div id="rgst-table-container" class="<?php echo esc_attr($atts['class']); ?>">
        <div class="rgst-loading">Loading...</div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('google_sheet_table', 'rgst_table_shortcode');

// AJAX handler for fetching table data
function rgst_get_table_data() {
    check_ajax_referer('rgst_nonce', 'nonce');

    $api_key = get_option('rgst_api_key');
    $spreadsheet_id = get_option('rgst_spreadsheet_id');
    $range = get_option('rgst_sheet_range', 'Sheet1!A1:Z1000');

    if (empty($api_key) || empty($spreadsheet_id)) {
        wp_send_json_error('API key or Spreadsheet ID not configured');
    }

    $url = sprintf(
        'https://sheets.googleapis.com/v4/spreadsheets/%s/values/%s?key=%s',
        urlencode($spreadsheet_id),
        urlencode($range),
        urlencode($api_key)
    );

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!isset($data['values'])) {
        wp_send_json_error('No data found');
    }

    $html = '<table class="rgst-table">';
    
    // Headers
    $html .= '<thead><tr>';
    foreach ($data['values'][0] as $header) {
        $html .= '<th>' . esc_html($header) . '</th>';
    }
    $html .= '</tr></thead>';
    
    // Body
    $html .= '<tbody>';
    for ($i = 1; $i < count($data['values']); $i++) {
        $html .= '<tr>';
        foreach ($data['values'][$i] as $cell) {
            $html .= '<td>' . esc_html($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    
    $html .= '</table>';

    wp_send_json_success($html);
}
add_action('wp_ajax_rgst_get_table_data', 'rgst_get_table_data');
add_action('wp_ajax_nopriv_rgst_get_table_data', 'rgst_get_table_data');
