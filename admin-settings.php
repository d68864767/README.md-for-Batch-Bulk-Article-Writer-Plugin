<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add settings page
add_action('admin_menu', 'bbw_add_settings_page');

function bbw_add_settings_page() {
    add_submenu_page(
        'batch-bulk-article-writer',
        'Settings',
        'Settings',
        'manage_options',
        'bbw-settings',
        'bbw_settings_page'
    );
}

function bbw_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add error/update messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error('bbw_messages', 'bbw_message', __('Settings Saved', 'batch-bulk-article-writer'), 'updated');
    }

    // Show error/update messages
    settings_errors('bbw_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // Output security fields for the registered setting "bbw"
            settings_fields('bbw');
            // Output setting sections and their fields
            do_settings_sections('bbw');
            // Output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register settings, sections and fields
add_action('admin_init', 'bbw_settings_init');

function bbw_settings_init() {
    // Register a new setting for "bbw" page
    register_setting('bbw', 'bbw_options');

    // Add a new section to the "bbw" page
    add_settings_section(
        'bbw_section',
        __('Batch Bulk Article Writer Settings', 'batch-bulk-article-writer'),
        'bbw_section_callback',
        'bbw'
    );

    // Add fields to the "bbw_section" section
    add_settings_field(
        'bbw_field_preset',
        __('Preset', 'batch-bulk-article-writer'),
        'bbw_field_preset_cb',
        'bbw',
        'bbw_section',
        [
            'label_for' => 'bbw_field_preset',
            'class' => 'bbw_row',
            'bbw_custom_data' => 'custom',
        ]
    );
}

function bbw_section_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Adjust the settings for the Batch Bulk Article Writer plugin.', 'batch-bulk-article-writer'); ?></p>
    <?php
}

function bbw_field_preset_cb($args) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('bbw_options');
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['bbw_custom_data']); ?>" name="bbw_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="option1" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'option1', false)) : (''); ?>>
            <?php esc_html_e('Option 1', 'batch-bulk-article-writer'); ?>
        </option>
        <option value="option2" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'option2', false)) : (''); ?>>
            <?php esc_html_e('Option 2', 'batch-bulk-article-writer'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('Choose your preferred preset.', 'batch-bulk-article-writer'); ?>
    </p>
    <?php
}
?>
