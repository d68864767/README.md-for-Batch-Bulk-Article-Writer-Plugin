<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add language support page
add_action('admin_menu', 'bbw_add_language_support_page');

function bbw_add_language_support_page() {
    add_submenu_page(
        'batch-bulk-article-writer',
        'Language Support',
        'Language Support',
        'manage_options',
        'bbw-language-support',
        'bbw_language_support_page'
    );
}

function bbw_language_support_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add error/update messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error('bbw_messages', 'bbw_message', __('Language Settings Saved', 'batch-bulk-article-writer'), 'updated');
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
add_action('admin_init', 'bbw_language_settings_init');

function bbw_language_settings_init() {
    // Register a new setting for "bbw" page
    register_setting('bbw', 'bbw_language_options');

    // Add a new section to the "bbw" page
    add_settings_section(
        'bbw_language_section',
        __('Batch Bulk Article Writer Language Settings', 'batch-bulk-article-writer'),
        'bbw_language_section_callback',
        'bbw'
    );

    // Add fields to the "bbw_language_section" section
    add_settings_field(
        'bbw_field_language',
        __('Language', 'batch-bulk-article-writer'),
        'bbw_field_language_cb',
        'bbw',
        'bbw_language_section',
        [
            'label_for' => 'bbw_field_language',
            'class' => 'bbw_row',
            'bbw_custom_data' => 'custom',
        ]
    );
}

function bbw_language_section_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Adjust the language settings for the Batch Bulk Article Writer plugin.', 'batch-bulk-article-writer'); ?></p>
    <?php
}

function bbw_field_language_cb($args) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('bbw_language_options');
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['bbw_custom_data']); ?>" name="bbw_language_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="english" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'english', false)) : (''); ?>>
            <?php esc_html_e('English', 'batch-bulk-article-writer'); ?>
        </option>
        <option value="spanish" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'spanish', false)) : (''); ?>>
            <?php esc_html_e('Spanish', 'batch-bulk-article-writer'); ?>
        </option>
        <!-- Add more languages as needed -->
    </select>
    <p class="description">
        <?php esc_html_e('Select the language for the generated articles.', 'batch-bulk-article-writer'); ?>
    </p>
    <?php
}
?>
