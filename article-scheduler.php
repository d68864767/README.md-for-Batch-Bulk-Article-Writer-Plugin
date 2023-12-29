<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add scheduling page
add_action('admin_menu', 'bbw_add_scheduling_page');

function bbw_add_scheduling_page() {
    add_submenu_page(
        'batch-bulk-article-writer',
        'Article Scheduling',
        'Article Scheduling',
        'manage_options',
        'bbw-scheduling',
        'bbw_scheduling_page'
    );
}

function bbw_scheduling_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add error/update messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error('bbw_messages', 'bbw_message', __('Scheduling Settings Saved', 'batch-bulk-article-writer'), 'updated');
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
add_action('admin_init', 'bbw_scheduling_settings_init');

function bbw_scheduling_settings_init() {
    // Register a new setting for "bbw" page
    register_setting('bbw', 'bbw_scheduling_options');

    // Add a new section to the "bbw" page
    add_settings_section(
        'bbw_scheduling_section',
        __('Batch Bulk Article Writer Scheduling Settings', 'batch-bulk-article-writer'),
        'bbw_scheduling_section_callback',
        'bbw'
    );

    // Add fields to the "bbw_scheduling_section" section
    add_settings_field(
        'bbw_field_schedule',
        __('Schedule', 'batch-bulk-article-writer'),
        'bbw_field_schedule_cb',
        'bbw',
        'bbw_scheduling_section',
        [
            'label_for' => 'bbw_field_schedule',
            'class' => 'bbw_row',
            'bbw_custom_data' => 'custom',
        ]
    );
}

function bbw_scheduling_section_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Adjust the scheduling settings for the Batch Bulk Article Writer plugin.', 'batch-bulk-article-writer'); ?></p>
    <?php
}

function bbw_field_schedule_cb($args) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('bbw_scheduling_options');
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['bbw_custom_data']); ?>" name="bbw_scheduling_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="immediately" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'immediately', false)) : (''); ?>>
            <?php esc_html_e('Publish Immediately', 'batch-bulk-article-writer'); ?>
        </option>
        <option value="schedule" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'schedule', false)) : (''); ?>>
            <?php esc_html_e('Schedule for Later', 'batch-bulk-article-writer'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('Choose whether to publish the articles immediately or schedule them for later.', 'batch-bulk-article-writer'); ?>
    </p>
    <?php
}
?>
