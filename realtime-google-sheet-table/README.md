# Realtime Google Sheet Table WordPress Plugin

A WordPress plugin that displays data from Google Sheets in a beautiful, responsive table format with true real-time synchronization. When data is added or modified in the Google Sheet, the table automatically updates without reloading the page.

## Features

- True real-time synchronization with Google Sheets
- Automatic updates when new data is added
- Responsive and modern table design
- Smooth animations for new data
- Easy to use shortcode
- Modern and clean interface
- Error handling and loading states

## Installation

1. Download and extract the plugin files
2. Upload the `realtime-google-sheet-table` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings > Google Sheet Table to configure the plugin

## Configuration

### Google Sheets API Setup

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google Sheets API for your project
4. Create credentials (API key) for the Sheets API
5. Copy your API key

### Plugin Settings

1. Navigate to Settings > Google Sheet Table in your WordPress admin
2. Enter your Google API Key
3. Enter your Spreadsheet ID (found in the Google Sheet URL)
4. Set the Sheet Range (e.g., Sheet1!A1:Z1000)

### Spreadsheet Setup

1. Make sure your Google Sheet is publicly accessible or shared with appropriate permissions
2. The first row of your selected range will be used as the table headers
3. Data should be properly formatted in your sheet

## Usage

Use the shortcode `[google_sheet_table]` in any post or page where you want to display the table.

Example:
```
[google_sheet_table]
```

## How It Works

The plugin implements true real-time synchronization by:
1. Initially loading the complete table data
2. Continuously checking for new rows in the background
3. Automatically appending new data with smooth animations
4. No page reloads or table refreshes required

## Troubleshooting

If the table isn't displaying or updating:

1. Check if your Google API key is correct
2. Verify that the Spreadsheet ID is correct
3. Ensure the sheet range is valid
4. Check if your Google Sheet is publicly accessible
5. Look for error messages in the browser console
6. Verify that your Google Sheets API is enabled in the Google Cloud Console
7. Check your browser's network tab to ensure API requests are working

## Support

For support or feature requests, please create an issue in the plugin's repository.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Created by [Your Name]
