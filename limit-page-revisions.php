<?php
/*
Plugin Name: Limit Page Revisions
Description: Allows you to limit the number of page revisions.
Version: 1.0
Author: zoynul
Author URI: https://code.zoynul.com
Tags: page revisions, revision limit, customization
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Copyright (c) 2023 zoynul

Limit Page Revisions is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License

Limit Page Revisions is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Limit Page Revisions. If not, see <https://www.gnu.org/licenses/>.
*/


// Add a settings page to the WordPress admin menu
function limit_page_revisions_menu() {
    add_options_page(
        'Limit Page Revisions Settings',
        'Limit Page Revisions',
        'manage_options',
        'limit_page_revisions_settings',
        'limit_page_revisions_settings_page'
    );
}
add_action('admin_menu', 'limit_page_revisions_menu');

// Callback function for the settings page
function limit_page_revisions_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings when the form is submitted
    if (isset($_POST['limit_page_revisions_submit'])) {
        $revision_limit = intval($_POST['revision_limit']);
        update_option('limit_page_revisions_option', $revision_limit);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    // Retrieve the current revision limit setting
    $current_limit = get_option('limit_page_revisions_option', 3);

    // Display the settings form
    ?>
    <div class="wrap">
        <h2>Limit Page Revisions Settings</h2>
        <form method="post" action="">
            <label for="revision_limit">Enter the number of page / post revisions to keep:</label>
            <input type="number" id="revision_limit" name="revision_limit" value="<?php echo esc_attr($current_limit); ?>" min="1" />
            <p class="description">Set the maximum number of revisions to keep for each page. (Minimum: 1)</p>
            <input type="submit" name="limit_page_revisions_submit" class="button-primary" value="Save Changes" />
        </form>
    </div>
    <?php
}

// Hook to limit the number of page revisions
function limit_page_revisions($num, $post) {
    $revision_limit = get_option('limit_page_revisions_option', 3);
    return intval($revision_limit);
}
add_filter('wp_revisions_to_keep', 'limit_page_revisions', 10, 2);
