<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add template manager page
add_action('admin_menu', 'bbw_add_template_manager_page');

function bbw_add_template_manager_page() {
    add_submenu_page(
        'batch-bulk-article-writer',
        'Template Manager',
        'Template Manager',
        'manage_options',
        'bbw-template-manager',
        'bbw_template_manager_page'
    );
}

function bbw_template_manager_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add error/update messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error('bbw_messages', 'bbw_message', __('Template Settings Saved', 'batch-bulk-article-writer'), 'updated');
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
            submit_button('Save Template Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register settings, sections and fields
add_action('admin_init', 'bbw_template_manager_init');

function bbw_template_manager_init() {
    // Register a new setting for "bbw" page
    register_setting('bbw', 'bbw_template_options');

    // Add a new section to the "bbw" page
    add_settings_section(
        'bbw_template_section',
        __('Batch Bulk Article Writer Template Settings', 'batch-bulk-article-writer'),
        'bbw_template_section_callback',
        'bbw'
    );

    // Add fields to the "bbw_template_section" section
    add_settings_field(
        'bbw_field_template',
        __('Template', 'batch-bulk-article-writer'),
        'bbw_field_template_cb',
        'bbw',
        'bbw_template_section',
        [
            'label_for' => 'bbw_field_template',
            'class' => 'bbw_row',
            'bbw_custom_data' => 'custom',
        ]
    );
}

function bbw_template_section_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Adjust the template settings for the Batch Bulk Article Writer plugin.', 'batch-bulk-article-writer'); ?></p>
    <?php
}

function bbw_field_template_cb($args) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('bbw_template_options');
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['bbw_custom_data']); ?>" name="bbw_template_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="template1" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'template1', false)) : (''); ?>>
            <?php esc_html_e('Template 1', 'batch-bulk-article-writer'); ?>
        </option>
        <option value="template2" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'template2', false)) : (''); ?>>
            <?php esc_html_e('Template 2', 'batch-bulk-article-writer'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('Choose your preferred template.', 'batch-bulk-article-writer'); ?>
    </p>
    <?php
}
?>
