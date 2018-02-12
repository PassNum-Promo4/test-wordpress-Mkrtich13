<?php

namespace wpView\options;

class Options {

    public $pluginNames;
    public $optionsViews;
    public $optionsColors;
    public $views;
    private $optionNameViews;
    private $optionNameColors;

    public function __construct($optionNameViews, $optionNameColors) {
        $this->optionNameViews = $optionNameViews;
        $this->optionNameColors = $optionNameColors;
        $this->initPluginNames();
        $this->addOptions();
        $this->initOptionsViews(get_option($this->optionNameViews));
        $this->initOptionsColors(get_option($this->optionNameColors));
        add_action('admin_menu', array(&$this, 'adminPage'));
        add_action('admin_enqueue_scripts', array(&$this, 'adminStyle'));
    }

    public function adminPage() {
        add_menu_page('wpView', 'wpView', 'administrator', 'wpview', array(&$this, 'adminPageContent'), 'dashicons-visibility');
        add_submenu_page('wpview', __('Field Settings', 'wpview'), __('Field Settings', 'wpview'), 'administrator', 'wpview', array(&$this, 'adminPageContent'));
        add_submenu_page('wpview', __('Colors & Styles', 'wpview'), __('Colors & Styles', 'wpview'), 'administrator', 'wpview_colors_and_styles', array(&$this, 'adminSubmenuPageContent'));
    }

    private function addOptions() {
        add_option($this->optionNameViews, $this->defaultValuesViews());
        add_option($this->optionNameColors, $this->defaultValuesColors());
    }

    private function initPluginNames() {
        $this->pluginNames = array('Custom_Field_Suite' => 'Custom Field Suite', 'acf' => 'Advanced Custom Fields', 'Toolset_Autoloader' => 'Toolset Types', 'CCTM' => 'Custom Content Type Manager', 'Wordpress_Creation_Kit' => 'WordPress Creation Kit');
    }

    public function adminStyle() {
        wp_register_style('wpview-index-css', plugins_url('wpview/assets/colorpicker/css/index.css'));
        wp_enqueue_style('wpview-index-css');
        wp_register_style('wpview-compatibility-css', plugins_url('wpview/assets/colorpicker/css/compatibility.css'));
        wp_enqueue_style('wpview-compatibility-css');
        wp_register_script('wpview-colors-js', plugins_url('wpview/assets/colorpicker/js/colors.js'), array('jquery'));
        wp_enqueue_script('wpview-colors-js');
        wp_register_script('wpview-colorpicker-js', plugins_url('wpview/assets/colorpicker/js/jqColorPicker.js'), array('jquery'));
        wp_enqueue_script('wpview-colorpicker-js');
        wp_register_script('wpview-index-js', plugins_url('wpview/assets/colorpicker/js/index.js'), array('jquery'));
        wp_enqueue_script('wpview-index-js');
        wp_register_script('wpview-admin-script', plugins_url('wpview/assets/js/admin_script.js'), array('jquery'));
        wp_enqueue_script('wpview-admin-script');
        wp_localize_script('wpview-admin-script', 'wpview', array('url' => plugins_url('wpview'), 'dialogTitle' => __('wpView Shortcodes', 'wpview')));
        wp_register_style('wpview-admin-style', plugins_url('wpview/assets/css/admin_style.css'));
        wp_enqueue_style('wpview-admin-style');
    }

    private function defaultValuesViews() {
        $options['wpview_plugin_names'] = array();
        foreach ($this->pluginNames as $key => $plugin) {
            if (class_exists($key)) {
                $options['wpview_plugin_names'][$key] = $plugin;
            }
        }
        $options['wpview_choose_views'] = 'display_together';
        $options['views_for_all'] = array(
            'text' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'textarea' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'phone' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'number' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'email' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'category' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'post_tag' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'wysiwyg' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'hyperlink' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'embed' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'date' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'time' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'color' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'checkboxes' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'checkbox' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'true_false' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'select' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'relation' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'page_link' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'post_object' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'user' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'image' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'audio' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'video' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'file' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'radio' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'currency' => array('view' => 'with_table', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no'),
            'google_map' => array('view' => 'google_map', 'position' => 'after_content', 'group' => 'yes', 'show_titles' => 'yes', 'show_in_excerpt' => 'no')
        );
        return $options;
    }

    private function noneValues() {
        $dboptions = get_option(WPVIEW_VIEWS);
        $options['wpview_plugin_names'] = array();
        foreach ($this->pluginNames as $key => $plugin) {
            if (isset($dboptions['wpview_plugin_names'][$key])) {
                $options['wpview_plugin_names'][$key] = $plugin;
            }
        }
        $options['wpview_choose_views'] = 'display_apart';
        $options['views_for_all'] = array(
            'text' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'textarea' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'phone' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'number' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'email' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'category' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'post_tag' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'wysiwyg' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'hyperlink' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'embed' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'date' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'time' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'color' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'checkboxes' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'checkbox' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'true_false' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'select' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'relation' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'page_link' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'post_object' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'user' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'image' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'audio' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'video' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'file' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'google_map' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'radio' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no'),
            'currency' => array('view' => 'none', 'position' => 'after_content', 'group' => 'no', 'show_titles' => 'no', 'show_in_excerpt' => 'no')
        );
        return $options;
    }

    private function initOptionsViews($serialize_options) {
        $options = maybe_unserialize($serialize_options);
        $default = $this->defaultValuesViews();
        $this->optionsViews['wpview_plugin_names'] = array();
        foreach ($this->pluginNames as $key => $value) {
            if (isset($options['wpview_plugin_names'][$key]) && class_exists($key)) {
                $this->optionsViews['wpview_plugin_names'][$key] = $value;
            } else {
                unset($this->optionsViews['wpview_plugin_names'][$key]);
            }
        }
        if ($options['wpview_choose_views'] === 'display_together' || $options['wpview_choose_views'] === 'display_together_before_content') {
            $this->optionsViews['wpview_choose_views'] = $options['wpview_choose_views'];
            foreach ($default['views_for_all'] as $key => $value) {
                $this->optionsViews['views_for_all'][$key]['view'] = $value['view'];
                $this->optionsViews['views_for_all'][$key]['position'] = $value['position'];
                $this->optionsViews['views_for_all'][$key]['group'] = $value['group'];
                $this->optionsViews['views_for_all'][$key]['show_titles'] = $value['show_titles'];
                $this->optionsViews['views_for_all'][$key]['show_in_excerpt'] = $value['show_in_excerpt'];
            }
        } else {
            $this->optionsViews['wpview_choose_views'] = $options['wpview_choose_views'];
            foreach ($default['views_for_all'] as $key => $value) {
                if (isset($options['wpview_' . $key . '_view'])) {
                    $this->optionsViews['views_for_all'][$key]['view'] = esc_sql($options['wpview_' . $key . '_view']);
                } else if (isset($options['views_for_all'][$key]['view'])) {
                    $this->optionsViews['views_for_all'][$key]['view'] = esc_sql($options['views_for_all'][$key]['view']);
                } else {
                    $this->optionsViews['views_for_all'][$key]['view'] = $value['view'];
                }
                if (isset($options['wpview_' . $key . '_position'])) {
                    $this->optionsViews['views_for_all'][$key]['position'] = esc_sql($options['wpview_' . $key . '_position']);
                } else if (isset($options['views_for_all'][$key]['position'])) {
                    $this->optionsViews['views_for_all'][$key]['position'] = esc_sql($options['views_for_all'][$key]['position']);
                } else {
                    $this->optionsViews['views_for_all'][$key]['position'] = $value['position'];
                }
                if (isset($options['wpview_' . $key . '_group'])) {
                    $this->optionsViews['views_for_all'][$key]['group'] = esc_sql($options['wpview_' . $key . '_group']);
                } else if (isset($options['views_for_all'][$key]['group'])) {
                    $this->optionsViews['views_for_all'][$key]['group'] = esc_sql($options['views_for_all'][$key]['group']);
                } else {
                    $this->optionsViews['views_for_all'][$key]['group'] = $value['group'];
                }
                if (isset($options['wpview_' . $key . '_show_titles'])) {
                    $this->optionsViews['views_for_all'][$key]['show_titles'] = esc_sql($options['wpview_' . $key . '_show_titles']);
                } else if (isset($options['views_for_all'][$key]['show_titles'])) {
                    $this->optionsViews['views_for_all'][$key]['show_titles'] = esc_sql($options['views_for_all'][$key]['show_titles']);
                } else {
                    $this->optionsViews['views_for_all'][$key]['show_titles'] = $value['show_titles'];
                }
                if (isset($options['wpview_' . $key . '_show_in_excerpt'])) {
                    $this->optionsViews['views_for_all'][$key]['show_in_excerpt'] = esc_sql($options['wpview_' . $key . '_show_in_excerpt']);
                } else if (isset($options['views_for_all'][$key]['show_in_excerpt'])) {
                    $this->optionsViews['views_for_all'][$key]['show_in_excerpt'] = esc_sql($options['views_for_all'][$key]['show_in_excerpt']);
                } else {
                    $this->optionsViews['views_for_all'][$key]['show_in_excerpt'] = $value['show_in_excerpt'];
                }
            }
        }
        update_option($this->optionNameViews, $this->optionsViews);
    }

    private function fieldsForViews($views) {
        ?>
        <div class="wpview_choose_views">
            <h3><?php _e('Choose Views', 'wpview'); ?></h3>
            <div class="wpview_choose_views_label_wrap">
                <label>
                    <input type="radio" value="display_together_before_content" name="wpview_choose_views" <?php echo $this->optionsViews['wpview_choose_views'] === 'display_together_before_content' ? ' checked="checked" ' : ""; ?> />
                    <img src="<?php echo plugins_url('wpview/assets/images/wpview-table.png') ?>" />
                    <span><?php _e('All in one Table Before Content', 'wpview'); ?></span>
                </label>
                <label>
                    <input type="radio" value="display_together" name="wpview_choose_views" <?php echo $this->optionsViews['wpview_choose_views'] === 'display_together' ? ' checked="checked" ' : ""; ?> />
                    <img src="<?php echo plugins_url('wpview/assets/images/wpview-table.png') ?>" />
                    <span><?php _e('All in one Table After Content', 'wpview'); ?></span>
                </label>
                <label>
                    <input type="radio" value="display_apart" name="wpview_choose_views" <?php echo $this->optionsViews['wpview_choose_views'] === 'display_apart' ? ' checked="checked" ' : ""; ?> />
                    <img src="<?php echo plugins_url('wpview/assets/images/wpview-fields.png') ?>" />
                    <span><?php _e('Custom Separate View', 'wpview'); ?></span>
                </label>
            </div>
        </div>
        <?php
        if ($views) {
            ?>
            <table>
                <tr>
                    <td>
                        <div id="wpview_display_all_apart" class="<?php echo ($this->optionsViews['wpview_choose_views'] === 'display_together' || $this->optionsViews['wpview_choose_views'] === 'display_together_before_content') ? 'wpview_hidden' : ''; ?>">
                            <h3 class="wpview_settings <?php echo!$views['show_title'] ? 'wpview_hidden' : ''; ?>"><?php _e('Custom Field - View Settings', 'wpview'); ?></h3>
                            <div class="views_for_all">
                                <?php
                                foreach ($views as $field_type => $view_array) {
                                    if (!empty($view_array) && $field_type !== 'show_title') {
                                        ?>
                                        <fieldset class="for_all <?php echo $view_array['class']; ?>">
                                            <legend>
                                                <?php
                                                echo '&nbsp;' . $field_type . '&nbsp;';
                                                foreach ($view_array['logo'] as $logo) {
                                                    ?>
                                                    <img id="fielset_logo_<?php echo $logo['id']; ?>" data-show="<?php echo $logo['class'] === 'wpview_display' ? 'true' : 'false'; ?>" class="plugin_logo <?php echo $logo['class']; ?>" src="<?php echo $logo['url']; ?>" />
                                                    <?php
                                                }
                                                ?>
                                            </legend>
                                            <?php
                                            $type = strtolower(str_replace(array('/', ' '), '_', $field_type));
                                            ?>
                                            <div class="wpview_labels_wrap">
                                                <label class="fieldset_label_view">
                                                    <strong><?php _e('View', 'wpview'); ?></strong>
                                                    <select id="<?php echo 'wpview_' . $type . '_view'; ?>" name="<?php echo 'wpview_' . $type . '_view'; ?>">
                                                        <option value="none"><?php _e('None', 'wpview'); ?></option>
                                                        <?php
                                                        foreach ($view_array as $view_key => $view) {
                                                            if ($view_key !== 'logo' && $view_key !== 'class') {
                                                                ?>
                                                                <option value="<?php echo $view_key; ?>"<?php echo $this->optionsViews['views_for_all'][$type]['view'] === $view_key ? ' selected="selected" class="wpview_selected" ' : ""; ?>><?php echo $view; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        do_action('wpview_new_option', $this->optionsViews, $type);
                                                        ?>
                                                    </select>
                                                </label>
                                                <label class="fieldset_label_position">
                                                    <strong><?php _e('Position', 'wpview'); ?></strong>
                                                    <select id="<?php echo 'wpview_' . $type . '_position'; ?>" name="<?php echo 'wpview_' . $type . '_position'; ?>">
                                                        <option value="after_content" <?php echo $this->optionsViews['views_for_all'][$type]['position'] === 'after_content' ? ' selected="selected" ' : ""; ?>><?php _e('After Content', 'wpview'); ?></option>
                                                        <option value="before_content" <?php echo $this->optionsViews['views_for_all'][$type]['position'] === 'before_content' ? ' selected="selected" ' : ""; ?>><?php _e('Before Content', 'wpview'); ?></option>
                                                        <option value="shortcode" <?php echo $this->optionsViews['views_for_all'][$type]['position'] === 'shortcode' ? ' selected="selected" ' : ""; ?>><?php _e('Use Shortcode', 'wpview'); ?></option>
                                                    </select>
                                                </label>
                                                <div class="wpview_checkbox_group">
                                                    <div class="wpview_group_inputs wpview_radio_box">
                                                        <div class="wpv-field-desc">
                                                            <strong><?php _e('Group', 'wpview'); ?></strong>
                                                            <span><?php _e('Enable this option if you want to group (sort together) the same type of custom fields on front-end view.', 'wpview'); ?></span>
                                                        </div>
                                                        <label>
                                                            <input type="radio" id="group_<?php echo $type; ?>" value="yes" name="<?php echo 'wpview_' . $type . '_group'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['group'] === 'yes' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Enable', 'wpview'); ?></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" value="no" name="<?php echo 'wpview_' . $type . '_group'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['group'] === 'no' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Disable', 'wpview'); ?></span>
                                                        </label>
                                                    </div>
                                                    <div class="wpview_show_inputs wpview_radio_box">
                                                        <div class="wpv-field-desc">
                                                            <strong><?php _e('Show Titles', 'wpview'); ?></strong>
                                                            <span><?php _e('This option hides/shows custom field title/label. If this option is disabled you\'ll only see the custom field value on front-end.', 'wpview'); ?></span>
                                                        </div>
                                                        <label>
                                                            <input type="radio" id="group_<?php echo $type; ?>" value="yes" name="<?php echo 'wpview_' . $type . '_show_titles'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['show_titles'] === 'yes' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Enable', 'wpview'); ?></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" value="no" name="<?php echo 'wpview_' . $type . '_show_titles'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['show_titles'] === 'no' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Disable', 'wpview'); ?></span>
                                                        </label>
                                                    </div>
                                                    <div class="wpview_show_inputs wpview_radio_box">
                                                        <div class="wpv-field-desc">
                                                            <strong><?php _e('Show in Excerpt', 'wpview'); ?></strong>
                                                            <span><?php _e('This option hides/shows custom fields in post excerpt (post list, archive, category page).', 'wpview'); ?></span>
                                                        </div>
                                                        <label>
                                                            <input type="radio" id="group_<?php echo $type; ?>" value="yes" name="<?php echo 'wpview_' . $type . '_show_in_excerpt'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['show_in_excerpt'] === 'yes' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Enable', 'wpview'); ?></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" value="no" name="<?php echo 'wpview_' . $type . '_show_in_excerpt'; ?>" <?php echo $this->optionsViews['views_for_all'][$type]['show_in_excerpt'] === 'no' ? ' checked="checked" ' : ""; ?> />
                                                            <span><?php _e('Disable', 'wpview'); ?></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <?php
        }
    }

    public function adminPageContent() {
        if (isset($_POST['wpview_reset_views']) && wp_verify_nonce($_POST['wpview_nonce_field_reset_views'], 'wpview_reset_views')) {
            $this->initOptionsViews($this->defaultValuesViews());
        } else if (isset($_POST['wpview_save_views'])) {
            $this->initOptionsViews($_POST);
        } else if (isset($_POST['wpview_set_none_views']) && wp_verify_nonce($_POST['wpview_nonce_field_set_none_views'], 'wpview_set_none_views')) {
            $this->initOptionsViews($this->noneValues());
        }
        ?>
        <div class="wrap">
            <h1><?php _e('wpView Settings', 'wpview'); ?></h1>
            <div id="wpview_admin_page">
                <form method="POST" id="wpview_view_settings">
                    <div id="wpview_plugins">
                        <fieldset>
                            <legend><?php _e('Enable Custom Field Plugins', 'wpview'); ?></legend>
                            <div><?php _e('wpView is designed to use with popular custom field plugins. If you have one of the mentioned plugins please click on the according icon and activate for certain custom field plugin. After activated please set according views for custom fields in settings box below.', 'wpview'); ?></div>
                            <div id="wpview_plugin_names">
                                <?php
                                foreach ($this->pluginNames as $key => $value) {
                                    $class = ' class="wpview_checkbox" ';
                                    $checkbox = '<label><input type="checkbox" value="' . esc_attr($value) . '" name="wpview_plugin_names[' . esc_attr($key) . ']"';
                                    if (isset($this->optionsViews['wpview_plugin_names'][$key]) && class_exists($key)) {
                                        $checkbox .= ' checked="checked" ';
                                        $class = ' class="wpview_display_checkbox" ';
                                    } else if (!isset($this->optionsViews['wpview_plugin_names'][$key]) && class_exists($key)) {
                                        $class = ' class="wpview_checkbox wpview_hidden_checkbox" ';
                                    } else {
                                        $class = ' class="wpview_checkbox wpview_not_installed_checkbox" ';
                                    }
                                    $checkbox .= '/><img id="wpview_plugin_' . $key . '" ' . $class . ' src="' . plugins_url('wpview/assets/images/' . $key . '.png') . '" title="' . esc_attr($value) . (!class_exists($key) ? __(' - Not Installed', 'wpview') : '') . '" /></label>';
                                    echo $checkbox;
                                }
                                ?>
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    if ($this->views) {
                        $this->fieldsForViews($this->views);
                    }
                    ?>
                    <div class="wpview_admin_button_group">
                        <button type="submit" class="wpview_buttons" name="wpview_set_none_views" id="wpview_set_none_views"><?php _e('Disable All Views', 'wpview'); ?></button>
                        <button type="submit" class="wpview_buttons" name="wpview_reset_views" id="wpview_reset_views"><?php _e('Reset Options', 'wpview'); ?></button>
                        <button type="submit" class="wpview_buttons" name="wpview_save_views" id="wpview_save_views"><?php _e('Save Changes', 'wpview'); ?></button>
                    </div>
                    <?php
                    wp_nonce_field('wpview_reset_views', 'wpview_nonce_field_reset_views');
                    wp_nonce_field('wpview_set_none_views', 'wpview_nonce_field_set_none_views');
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    private function initOptionsColors($serialize_options) {
        $options = maybe_unserialize($serialize_options);
        $default = $this->defaultValuesColors();
        foreach ($default as $key => $value) {
            $this->optionsColors[$key] = isset($options[$key]) ? esc_sql($options[$key]) : $value;
        }
        update_option($this->optionNameColors, $this->optionsColors);
    }

    private function defaultValuesColors() {
        $options = array(
            'wpview_all_together_title_color' => '#333333',
            'wpview_all_together_left_bg_color' => '#EEEEEE',
            'wpview_all_together_right_bg_color' => '#F8F8F8',
            'wpview_all_together_border_color' => '#FFFFFF',
            'wpview_text_text_color' => '#333333',
            'wpview_text_title_color' => '#333333',
            'wpview_text_bg_color' => '#FFFFFF',
            'wpview_text_left_border' => '#2277BB',
            'wpview_text_border_color' => '#CCCCCC',
            'wpview_textarea_text_color' => '#333333',
            'wpview_textarea_title_color' => '#333333',
            'wpview_textarea_bg_color' => '#EEEEEE',
            'wpview_textarea_shadow_color' => 'rgba(0, 0, 0, 0.1)',
            'wpview_text_table_text_color' => '#333333',
            'wpview_text_table_title_color' => '#333333',
            'wpview_text_table_bg_color' => '#FFFFFF',
            'wpview_text_table_border_color' => '#EBEBEB',
            'wpview_text_ul_text_color' => '#333333',
            'wpview_text_ul_title_color' => '#333333',
            'wpview_text_ol_text_color' => '#333333',
            'wpview_text_ol_title_color' => '#333333',
            'wpview_date_text_color' => '#333333',
            'wpview_date_title_color' => '#333333',
            'wpview_date_left_border' => '#20C529',
            'wpview_date_bg_color' => '#FFFFFF',
            'wpview_date_border_color' => '#CCCCCC',
            'wpview_date_table_text_color' => '#333333',
            'wpview_date_table_title_color' => '#333333',
            'wpview_date_table_bg_color' => '#FFFFFF',
            'wpview_date_table_border_color' => '#EBEBEB',
            'wpview_date_ul_text_color' => '#333333',
            'wpview_date_ul_title_color' => '#333333',
            'wpview_date_ol_text_color' => '#333333',
            'wpview_date_ol_title_color' => '#333333',
            'wpview_wysiwyg_title_color' => '#333333',
            'wpview_wysiwyg_border_color' => '#CCCCCC',
            'wpview_wysiwyg_bg_color' => '#FFFFFF',
            'wpview_wysiwyg_table_title_color' => '#333333',
            'wpview_wysiwyg_table_bg_color' => '#FFFFFF',
            'wpview_wysiwyg_table_border_color' => '#EBEBEB',
            'wpview_color_text_color' => '#333333',
            'wpview_color_title_color' => '#333333',
            'wpview_color_table_text_color' => '#333333',
            'wpview_color_table_title_color' => '#333333',
            'wpview_color_table_bg_color' => '#FFFFFF',
            'wpview_color_table_border_color' => '#EBEBEB',
            'wpview_color_ul_text_color' => '#333333',
            'wpview_color_ul_title_color' => '#333333',
            'wpview_color_ol_text_color' => '#333333',
            'wpview_color_ol_title_color' => '#333333',
            'wpview_select_text_color' => '#333333',
            'wpview_select_title_color' => '#333333',
            'wpview_select_bg_color' => '#FFFFFF',
            'wpview_select_border_color' => '#CCCCCC',
            'wpview_select_table_text_color' => '#333333',
            'wpview_select_table_title_color' => '#333333',
            'wpview_select_table_bg_color' => '#FFFFFF',
            'wpview_select_table_border_color' => '#EBEBEB',
            'wpview_select_ul_text_color' => '#333333',
            'wpview_select_ul_title_color' => '#333333',
            'wpview_select_ol_text_color' => '#333333',
            'wpview_select_ol_title_color' => '#333333',
            'wpview_checkbox_text_color' => '#333333',
            'wpview_checkbox_title_color' => '#333333',
            'wpview_checkbox_bg_color' => '#EEEEEE',
            'wpview_checkbox_shadow_color' => 'rgba(0, 0, 0, 0.1)',
            'wpview_checkbox_table_text_color' => '#333333',
            'wpview_checkbox_table_title_color' => '#333333',
            'wpview_checkbox_table_bg_color' => '#FFFFFF',
            'wpview_checkbox_table_border_color' => '#EBEBEB',
            'wpview_checkbox_ul_text_color' => '#333333',
            'wpview_checkbox_ul_title_color' => '#333333',
            'wpview_checkbox_ol_text_color' => '#333333',
            'wpview_checkbox_ol_title_color' => '#333333',
            'wpview_hyperlink_text_color' => '#333333',
            'wpview_hyperlink_title_color' => '#333333',
            'wpview_hyperlink_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_hyperlink_table_text_color' => '#333333',
            'wpview_hyperlink_table_title_color' => '#333333',
            'wpview_hyperlink_table_bg_color' => '#FFFFFF',
            'wpview_hyperlink_table_border_color' => '#EBEBEB',
            'wpview_hyperlink_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_hyperlink_ul_text_color' => '#333333',
            'wpview_hyperlink_ul_title_color' => '#333333',
            'wpview_hyperlink_ul_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_hyperlink_ol_text_color' => '#333333',
            'wpview_hyperlink_ol_title_color' => '#333333',
            'wpview_hyperlink_ol_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_user_with_avatar_text_color' => '#333333',
            'wpview_user_with_avatar_title_color' => '#333333',
            'wpview_user_with_avatar_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_user_without_avatar_text_color' => '#333333',
            'wpview_user_without_avatar_title_color' => '#333333',
            'wpview_user_without_avatar_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_user_table_text_color' => '#333333',
            'wpview_user_table_title_color' => '#333333',
            'wpview_user_table_bg_color' => '#FFFFFF',
            'wpview_user_table_border_color' => '#EBEBEB',
            'wpview_user_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_relation_with_thumbnail_text_color' => 'rgba(131, 135, 131, 0.68)',
            'wpview_relation_with_thumbnail_title_color' => '#333333',
            'wpview_relation_with_thumbnail_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_relation_without_thumbnail_text_color' => '#333333',
            'wpview_relation_without_thumbnail_title_color' => '#333333',
            'wpview_relation_without_thumbnail_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_relation_table_text_color' => 'rgba(131, 135, 131, 0.68)',
            'wpview_relation_table_title_color' => '#333333',
            'wpview_relation_table_bg_color' => '#FFFFFF',
            'wpview_relation_table_border_color' => '#EBEBEB',
            'wpview_relation_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_image_image_thumbnail_title_color' => '#333333',
            'wpview_image_download_link_text_color' => '#333333',
            'wpview_image_download_link_title_color' => '#333333',
            'wpview_image_download_link_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_image_table_title_color' => '#333333',
            'wpview_image_table_bg_color' => '#FFFFFF',
            'wpview_image_table_border_color' => '#EBEBEB',
            'wpview_audio_player_title_color' => '#333333',
            'wpview_audio_download_link_text_color' => '#333333',
            'wpview_audio_download_link_title_color' => '#333333',
            'wpview_audio_download_link_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_audio_table_title_color' => '#333333',
            'wpview_audio_table_bg_color' => '#FFFFFF',
            'wpview_audio_table_border_color' => '#EBEBEB',
            'wpview_video_player_title_color' => '#333333',
            'wpview_video_download_link_text_color' => '#333333',
            'wpview_video_download_link_title_color' => '#333333',
            'wpview_video_download_link_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_video_table_title_color' => '#333333',
            'wpview_video_table_bg_color' => '#FFFFFF',
            'wpview_video_table_border_color' => '#EBEBEB',
            'wpview_file_text_color' => '#333333',
            'wpview_file_title_color' => '#333333',
            'wpview_file_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_file_table_text_color' => '#333333',
            'wpview_file_table_title_color' => '#333333',
            'wpview_file_table_bg_color' => '#FFFFFF',
            'wpview_file_table_border_color' => '#EBEBEB',
            'wpview_file_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_category_text_color' => '#333333',
            'wpview_category_title_color' => '#333333',
            'wpview_category_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_category_table_text_color' => '#333333',
            'wpview_category_table_title_color' => '#333333',
            'wpview_category_table_bg_color' => '#FFFFFF',
            'wpview_category_table_border_color' => '#EBEBEB',
            'wpview_category_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_category_ul_text_color' => '#333333',
            'wpview_category_ul_title_color' => '#333333',
            'wpview_category_ul_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_category_ol_text_color' => '#333333',
            'wpview_category_ol_title_color' => '#333333',
            'wpview_category_ol_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_tag_text_color' => '#333333',
            'wpview_tag_title_color' => '#333333',
            'wpview_tag_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_tag_table_text_color' => '#333333',
            'wpview_tag_table_title_color' => '#333333',
            'wpview_tag_table_bg_color' => '#FFFFFF',
            'wpview_tag_table_border_color' => '#EBEBEB',
            'wpview_tag_table_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_tag_ul_text_color' => '#333333',
            'wpview_tag_ul_title_color' => '#333333',
            'wpview_tag_ul_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_tag_ol_text_color' => '#333333',
            'wpview_tag_ol_title_color' => '#333333',
            'wpview_tag_ol_text_color_hover' => 'rgba(51, 51, 51, 0.7)',
            'wpview_google_map_title_color' => '#333333'
        );
        return $options;
    }

    public function adminSubmenuPageContent() {
        $default = $this->defaultValuesColors();
        if (isset($_POST['wpview_reset_colors']) && wp_verify_nonce($_POST['wpview_nonce_field_colors'], 'wpview_reset_colors')) {
            $this->initOptionsColors($this->defaultValuesColors());
        } else if (isset($_POST['wpview_save_colors'])) {
            $this->initOptionsColors($_POST);
        }
        ?>
        <div class="wrap">
            <h1 class="wpview_colors_title"><?php _e('wpView Colors & Styles', 'wpview'); ?></h1>
            <div id="wpview_admin_page_colors_and_styles">
                <form method="POST" id="wpview_color_settings">
                    <div id="wpview_colors_and_styles">
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('All Together In Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for all custom field types', 'wpview'); ?>
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_all_together_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_all_together_title_color" name="wpview_all_together_title_color" value="<?php echo isset($this->optionsColors['wpview_all_together_title_color']) ? $this->optionsColors['wpview_all_together_title_color'] : $default['wpview_all_together_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_all_together_left_bg_color">
                                    <span><?php _e('Left Column Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_all_together_left_bg_color" name="wpview_all_together_left_bg_color" value="<?php echo isset($this->optionsColors['wpview_all_together_left_bg_color']) ? $this->optionsColors['wpview_all_together_left_bg_color'] : $default['wpview_all_together_left_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_all_together_right_bg_color">
                                    <span><?php _e('Right Column Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_all_together_right_bg_color" name="wpview_all_together_right_bg_color" value="<?php echo isset($this->optionsColors['wpview_all_together_right_bg_color']) ? $this->optionsColors['wpview_all_together_right_bg_color'] : $default['wpview_all_together_right_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_all_together_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_all_together_border_color" name="wpview_all_together_border_color" value="<?php echo isset($this->optionsColors['wpview_all_together_border_color']) ? $this->optionsColors['wpview_all_together_border_color'] : $default['wpview_all_together_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Left Border Box', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Text, Textarea, Number, etc...
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_text_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_text_color" name="wpview_text_text_color" value="<?php echo isset($this->optionsColors['wpview_text_text_color']) ? $this->optionsColors['wpview_text_text_color'] : $default['wpview_text_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_title_color" name="wpview_text_title_color" value="<?php echo isset($this->optionsColors['wpview_text_title_color']) ? $this->optionsColors['wpview_text_title_color'] : $default['wpview_text_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_bg_color" name="wpview_text_bg_color" value="<?php echo isset($this->optionsColors['wpview_text_bg_color']) ? $this->optionsColors['wpview_text_bg_color'] : $default['wpview_text_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_left_border">
                                    <span><?php _e('Left Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_left_border" name="wpview_text_left_border" value="<?php echo isset($this->optionsColors['wpview_text_left_border']) ? $this->optionsColors['wpview_text_left_border'] : $default['wpview_text_left_border']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_border_color" name="wpview_text_border_color" value="<?php echo isset($this->optionsColors['wpview_text_border_color']) ? $this->optionsColors['wpview_text_border_color'] : $default['wpview_text_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Quote Box', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Text, Textarea, Number, etc...
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_textarea_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_textarea_text_color" name="wpview_textarea_text_color" value="<?php echo isset($this->optionsColors['wpview_textarea_text_color']) ? $this->optionsColors['wpview_textarea_text_color'] : $default['wpview_textarea_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_textarea_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_textarea_title_color" name="wpview_textarea_title_color" value="<?php echo isset($this->optionsColors['wpview_textarea_title_color']) ? $this->optionsColors['wpview_textarea_title_color'] : $default['wpview_textarea_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_textarea_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_textarea_bg_color" name="wpview_textarea_bg_color" value="<?php echo isset($this->optionsColors['wpview_textarea_bg_color']) ? $this->optionsColors['wpview_textarea_bg_color'] : $default['wpview_textarea_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_textarea_shadow_color">
                                    <span><?php _e('Shadow Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_textarea_shadow_color" name="wpview_textarea_shadow_color" value="<?php echo isset($this->optionsColors['wpview_textarea_shadow_color']) ? $this->optionsColors['wpview_textarea_shadow_color'] : $default['wpview_textarea_shadow_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Text, Textarea, Number, etc...
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_text_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_table_text_color" name="wpview_text_table_text_color" value="<?php echo isset($this->optionsColors['wpview_text_table_text_color']) ? $this->optionsColors['wpview_text_table_text_color'] : $default['wpview_text_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_table_title_color" name="wpview_text_table_title_color" value="<?php echo isset($this->optionsColors['wpview_text_table_title_color']) ? $this->optionsColors['wpview_text_table_title_color'] : $default['wpview_text_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_table_bg_color" name="wpview_text_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_text_table_bg_color']) ? $this->optionsColors['wpview_text_table_bg_color'] : $default['wpview_text_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_table_border_color" name="wpview_text_table_border_color" value="<?php echo isset($this->optionsColors['wpview_text_table_border_color']) ? $this->optionsColors['wpview_text_table_border_color'] : $default['wpview_text_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Text, Textarea, Number, etc...
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_text_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_ul_text_color" name="wpview_text_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_text_ul_text_color']) ? $this->optionsColors['wpview_text_ul_text_color'] : $default['wpview_text_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_ul_title_color" name="wpview_text_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_text_ul_title_color']) ? $this->optionsColors['wpview_text_ul_title_color'] : $default['wpview_text_ul_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Text, Textarea, Number, etc...
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_text_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_ol_text_color" name="wpview_text_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_text_ol_text_color']) ? $this->optionsColors['wpview_text_ol_text_color'] : $default['wpview_text_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_text_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_text_ol_title_color" name="wpview_text_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_text_ol_title_color']) ? $this->optionsColors['wpview_text_ol_title_color'] : $default['wpview_text_ol_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Left Border Box', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Date, Time
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_date_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_text_color" name="wpview_date_text_color" value="<?php echo isset($this->optionsColors['wpview_date_text_color']) ? $this->optionsColors['wpview_date_text_color'] : $default['wpview_date_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_title_color" name="wpview_date_title_color" value="<?php echo isset($this->optionsColors['wpview_date_title_color']) ? $this->optionsColors['wpview_date_title_color'] : $default['wpview_date_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_left_border">
                                    <span><?php _e('Left Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_left_border" name="wpview_date_left_border" value="<?php echo isset($this->optionsColors['wpview_date_left_border']) ? $this->optionsColors['wpview_date_left_border'] : $default['wpview_date_left_border']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_bg_color" name="wpview_date_bg_color" value="<?php echo isset($this->optionsColors['wpview_date_bg_color']) ? $this->optionsColors['wpview_date_bg_color'] : $default['wpview_date_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_border_color" name="wpview_date_border_color" value="<?php echo isset($this->optionsColors['wpview_date_border_color']) ? $this->optionsColors['wpview_date_border_color'] : $default['wpview_date_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Date, Time
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_date_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_table_text_color" name="wpview_date_table_text_color" value="<?php echo isset($this->optionsColors['wpview_date_table_text_color']) ? $this->optionsColors['wpview_date_table_text_color'] : $default['wpview_date_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_table_title_color" name="wpview_date_table_title_color" value="<?php echo isset($this->optionsColors['wpview_date_table_title_color']) ? $this->optionsColors['wpview_date_table_title_color'] : $default['wpview_date_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_table_bg_color" name="wpview_date_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_date_table_bg_color']) ? $this->optionsColors['wpview_date_table_bg_color'] : $default['wpview_date_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_table_border_color" name="wpview_date_table_border_color" value="<?php echo isset($this->optionsColors['wpview_date_table_border_color']) ? $this->optionsColors['wpview_date_table_border_color'] : $default['wpview_date_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Date, Time
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_date_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_ul_text_color" name="wpview_date_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_date_ul_text_color']) ? $this->optionsColors['wpview_date_ul_text_color'] : $default['wpview_date_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_ul_title_color" name="wpview_date_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_date_ul_title_color']) ? $this->optionsColors['wpview_date_ul_title_color'] : $default['wpview_date_ul_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Date, Time
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_date_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_ol_text_color" name="wpview_date_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_date_ol_text_color']) ? $this->optionsColors['wpview_date_ol_text_color'] : $default['wpview_date_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_date_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_date_ol_title_color" name="wpview_date_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_date_ol_title_color']) ? $this->optionsColors['wpview_date_ol_title_color'] : $default['wpview_date_ol_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Bordered Box', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: WYSIWYG (Rich Editor)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_title_color" name="wpview_wysiwyg_title_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_title_color']) ? $this->optionsColors['wpview_wysiwyg_title_color'] : $default['wpview_wysiwyg_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_bg_color" name="wpview_wysiwyg_bg_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_bg_color']) ? $this->optionsColors['wpview_wysiwyg_bg_color'] : $default['wpview_wysiwyg_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_border_color" name="wpview_wysiwyg_border_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_border_color']) ? $this->optionsColors['wpview_wysiwyg_border_color'] : $default['wpview_wysiwyg_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: WYSIWYG (Rich Editor)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_table_title_color" name="wpview_wysiwyg_table_title_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_table_title_color']) ? $this->optionsColors['wpview_wysiwyg_table_title_color'] : $default['wpview_wysiwyg_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_table_bg_color" name="wpview_wysiwyg_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_table_bg_color']) ? $this->optionsColors['wpview_wysiwyg_table_bg_color'] : $default['wpview_wysiwyg_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_wysiwyg_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_wysiwyg_table_border_color" name="wpview_wysiwyg_table_border_color" value="<?php echo isset($this->optionsColors['wpview_wysiwyg_table_border_color']) ? $this->optionsColors['wpview_wysiwyg_table_border_color'] : $default['wpview_wysiwyg_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Color Box', 'wpview') . ', ' . __('Color Box with Code', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Color
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_color_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_text_color" name="wpview_color_text_color" value="<?php echo isset($this->optionsColors['wpview_color_text_color']) ? $this->optionsColors['wpview_color_text_color'] : $default['wpview_color_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_title_color" name="wpview_color_title_color" value="<?php echo isset($this->optionsColors['wpview_color_title_color']) ? $this->optionsColors['wpview_color_title_color'] : $default['wpview_color_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Color
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_color_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_table_text_color" name="wpview_color_table_text_color" value="<?php echo isset($this->optionsColors['wpview_color_table_text_color']) ? $this->optionsColors['wpview_color_table_text_color'] : $default['wpview_color_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_table_title_color" name="wpview_color_table_title_color" value="<?php echo isset($this->optionsColors['wpview_color_table_title_color']) ? $this->optionsColors['wpview_color_table_title_color'] : $default['wpview_color_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_table_bg_color" name="wpview_color_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_color_table_bg_color']) ? $this->optionsColors['wpview_color_table_bg_color'] : $default['wpview_color_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_table_border_color" name="wpview_color_table_border_color" value="<?php echo isset($this->optionsColors['wpview_color_table_border_color']) ? $this->optionsColors['wpview_color_table_border_color'] : $default['wpview_color_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Color
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_color_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_ul_text_color" name="wpview_color_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_color_ul_text_color']) ? $this->optionsColors['wpview_color_ul_text_color'] : $default['wpview_color_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_ul_title_color" name="wpview_color_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_color_ul_title_color']) ? $this->optionsColors['wpview_color_ul_title_color'] : $default['wpview_color_ul_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Color
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_color_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_ol_text_color" name="wpview_color_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_color_ol_text_color']) ? $this->optionsColors['wpview_color_ol_text_color'] : $default['wpview_color_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_color_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_color_ol_title_color" name="wpview_color_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_color_ol_title_color']) ? $this->optionsColors['wpview_color_ol_title_color'] : $default['wpview_color_ol_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Bordered Box', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Select (Dropdown)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_select_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_text_color" name="wpview_select_text_color" value="<?php echo isset($this->optionsColors['wpview_select_text_color']) ? $this->optionsColors['wpview_select_text_color'] : $default['wpview_select_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_title_color" name="wpview_select_title_color" value="<?php echo isset($this->optionsColors['wpview_select_title_color']) ? $this->optionsColors['wpview_select_title_color'] : $default['wpview_select_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_bg_color" name="wpview_select_bg_color" value="<?php echo isset($this->optionsColors['wpview_select_bg_color']) ? $this->optionsColors['wpview_select_bg_color'] : $default['wpview_select_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_border_color" name="wpview_select_border_color" value="<?php echo isset($this->optionsColors['wpview_select_border_color']) ? $this->optionsColors['wpview_select_border_color'] : $default['wpview_select_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Select (Dropdown)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_select_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_table_text_color" name="wpview_select_table_text_color" value="<?php echo isset($this->optionsColors['wpview_select_table_text_color']) ? $this->optionsColors['wpview_select_table_text_color'] : $default['wpview_select_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_table_title_color" name="wpview_select_table_title_color" value="<?php echo isset($this->optionsColors['wpview_select_table_title_color']) ? $this->optionsColors['wpview_select_table_title_color'] : $default['wpview_select_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_table_bg_color" name="wpview_select_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_select_table_bg_color']) ? $this->optionsColors['wpview_select_table_bg_color'] : $default['wpview_select_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_table_border_color" name="wpview_select_table_border_color" value="<?php echo isset($this->optionsColors['wpview_select_table_border_color']) ? $this->optionsColors['wpview_select_table_border_color'] : $default['wpview_select_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Select (Dropdown)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_select_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_ul_text_color" name="wpview_select_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_select_ul_text_color']) ? $this->optionsColors['wpview_select_ul_text_color'] : $default['wpview_select_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_ul_title_color" name="wpview_select_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_select_ul_title_color']) ? $this->optionsColors['wpview_select_ul_title_color'] : $default['wpview_select_ul_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Select (Dropdown)
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_select_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_ol_text_color" name="wpview_select_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_select_ol_text_color']) ? $this->optionsColors['wpview_select_ol_text_color'] : $default['wpview_select_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_select_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_select_ol_title_color" name="wpview_select_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_select_ol_title_color']) ? $this->optionsColors['wpview_select_ol_title_color'] : $default['wpview_select_ol_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('With checkmark icon', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Checkbox, True/False, Radio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_text_color" name="wpview_checkbox_text_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_text_color']) ? $this->optionsColors['wpview_checkbox_text_color'] : $default['wpview_checkbox_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_title_color" name="wpview_checkbox_title_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_title_color']) ? $this->optionsColors['wpview_checkbox_title_color'] : $default['wpview_checkbox_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_bg_color" name="wpview_checkbox_bg_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_bg_color']) ? $this->optionsColors['wpview_checkbox_bg_color'] : $default['wpview_checkbox_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_shadow_color">
                                    <span><?php _e('Shadow Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_shadow_color" name="wpview_checkbox_shadow_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_shadow_color']) ? $this->optionsColors['wpview_checkbox_shadow_color'] : $default['wpview_checkbox_shadow_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Checkbox, True/False, Radio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_table_text_color" name="wpview_checkbox_table_text_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_table_text_color']) ? $this->optionsColors['wpview_checkbox_table_text_color'] : $default['wpview_checkbox_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_table_title_color" name="wpview_checkbox_table_title_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_table_title_color']) ? $this->optionsColors['wpview_checkbox_table_title_color'] : $default['wpview_checkbox_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_table_bg_color" name="wpview_checkbox_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_table_bg_color']) ? $this->optionsColors['wpview_checkbox_table_bg_color'] : $default['wpview_checkbox_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_table_border_color" name="wpview_checkbox_table_border_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_table_border_color']) ? $this->optionsColors['wpview_checkbox_table_border_color'] : $default['wpview_checkbox_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Checkbox, True/False, Radio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_ul_text_color" name="wpview_checkbox_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_ul_text_color']) ? $this->optionsColors['wpview_checkbox_ul_text_color'] : $default['wpview_checkbox_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_ul_title_color" name="wpview_checkbox_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_ul_title_color']) ? $this->optionsColors['wpview_checkbox_ul_title_color'] : $default['wpview_checkbox_ul_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Checkbox, True/False, Radio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_ol_text_color" name="wpview_checkbox_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_ol_text_color']) ? $this->optionsColors['wpview_checkbox_ol_text_color'] : $default['wpview_checkbox_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_checkbox_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_checkbox_ol_title_color" name="wpview_checkbox_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_checkbox_ol_title_color']) ? $this->optionsColors['wpview_checkbox_ol_title_color'] : $default['wpview_checkbox_ol_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Hyperlink Icon', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Hyperlink, Embed
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_text_color" name="wpview_hyperlink_text_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_text_color']) ? $this->optionsColors['wpview_hyperlink_text_color'] : $default['wpview_hyperlink_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_title_color" name="wpview_hyperlink_title_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_title_color']) ? $this->optionsColors['wpview_hyperlink_title_color'] : $default['wpview_hyperlink_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_text_color_hover" name="wpview_hyperlink_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_hyperlink_text_color_hover']) ? $this->optionsColors['wpview_hyperlink_text_color_hover'] : $default['wpview_hyperlink_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Hyperlink, Embed
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_table_text_color" name="wpview_hyperlink_table_text_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_table_text_color']) ? $this->optionsColors['wpview_hyperlink_table_text_color'] : $default['wpview_hyperlink_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_table_title_color" name="wpview_hyperlink_table_title_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_table_title_color']) ? $this->optionsColors['wpview_hyperlink_table_title_color'] : $default['wpview_hyperlink_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_table_bg_color" name="wpview_hyperlink_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_table_bg_color']) ? $this->optionsColors['wpview_hyperlink_table_bg_color'] : $default['wpview_hyperlink_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_table_border_color" name="wpview_hyperlink_table_border_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_table_border_color']) ? $this->optionsColors['wpview_hyperlink_table_border_color'] : $default['wpview_hyperlink_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_table_text_color_hover" name="wpview_hyperlink_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_hyperlink_table_text_color_hover']) ? $this->optionsColors['wpview_hyperlink_table_text_color_hover'] : $default['wpview_hyperlink_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Hyperlink, Embed
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ul_text_color" name="wpview_hyperlink_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ul_text_color']) ? $this->optionsColors['wpview_hyperlink_ul_text_color'] : $default['wpview_hyperlink_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ul_title_color" name="wpview_hyperlink_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ul_title_color']) ? $this->optionsColors['wpview_hyperlink_ul_title_color'] : $default['wpview_hyperlink_ul_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ul_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ul_text_color_hover" name="wpview_hyperlink_ul_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ul_text_color_hover']) ? $this->optionsColors['wpview_hyperlink_ul_text_color_hover'] : $default['wpview_hyperlink_ul_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Hyperlink, Embed
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ol_text_color" name="wpview_hyperlink_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ol_text_color']) ? $this->optionsColors['wpview_hyperlink_ol_text_color'] : $default['wpview_hyperlink_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ol_title_color" name="wpview_hyperlink_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ol_title_color']) ? $this->optionsColors['wpview_hyperlink_ol_title_color'] : $default['wpview_hyperlink_ol_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_hyperlink_ol_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_hyperlink_ol_text_color_hover" name="wpview_hyperlink_ol_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_hyperlink_ol_text_color_hover']) ? $this->optionsColors['wpview_hyperlink_ol_text_color_hover'] : $default['wpview_hyperlink_ol_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('With Avatar', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: User
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">	
                            <div class="wpview_color_option">
                                <label for="wpview_user_with_avatar_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_with_avatar_text_color" name="wpview_user_with_avatar_text_color" value="<?php echo isset($this->optionsColors['wpview_user_with_avatar_text_color']) ? $this->optionsColors['wpview_user_with_avatar_text_color'] : $default['wpview_user_with_avatar_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_with_avatar_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_with_avatar_title_color" name="wpview_user_with_avatar_title_color" value="<?php echo isset($this->optionsColors['wpview_user_with_avatar_title_color']) ? $this->optionsColors['wpview_user_with_avatar_title_color'] : $default['wpview_user_with_avatar_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_with_avatar_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_with_avatar_text_color_hover" name="wpview_user_with_avatar_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_user_with_avatar_text_color_hover']) ? $this->optionsColors['wpview_user_with_avatar_text_color_hover'] : $default['wpview_user_with_avatar_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Without Avatar', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: User
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">	
                            <div class="wpview_color_option">
                                <label for="wpview_user_without_avatar_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_without_avatar_text_color" name="wpview_user_without_avatar_text_color" value="<?php echo isset($this->optionsColors['wpview_user_without_avatar_text_color']) ? $this->optionsColors['wpview_user_without_avatar_text_color'] : $default['wpview_user_without_avatar_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_without_avatar_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_without_avatar_title_color" name="wpview_user_without_avatar_title_color" value="<?php echo isset($this->optionsColors['wpview_user_without_avatar_title_color']) ? $this->optionsColors['wpview_user_without_avatar_title_color'] : $default['wpview_user_without_avatar_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_without_avatar_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_without_avatar_text_color_hover" name="wpview_user_without_avatar_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_user_without_avatar_text_color_hover']) ? $this->optionsColors['wpview_user_without_avatar_text_color_hover'] : $default['wpview_user_without_avatar_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: User
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_user_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_table_text_color" name="wpview_user_table_text_color" value="<?php echo isset($this->optionsColors['wpview_user_table_text_color']) ? $this->optionsColors['wpview_user_table_text_color'] : $default['wpview_user_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_table_title_color" name="wpview_user_table_title_color" value="<?php echo isset($this->optionsColors['wpview_user_table_title_color']) ? $this->optionsColors['wpview_user_table_title_color'] : $default['wpview_user_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_table_bg_color" name="wpview_user_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_user_table_bg_color']) ? $this->optionsColors['wpview_user_table_bg_color'] : $default['wpview_user_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_table_border_color" name="wpview_user_table_border_color" value="<?php echo isset($this->optionsColors['wpview_user_table_border_color']) ? $this->optionsColors['wpview_user_table_border_color'] : $default['wpview_user_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_user_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_user_table_text_color_hover" name="wpview_user_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_user_table_text_color_hover']) ? $this->optionsColors['wpview_user_table_text_color_hover'] : $default['wpview_user_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('With Thumbnail', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Relation, Page Link, Post Object
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_relation_with_thumbnail_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_with_thumbnail_text_color" name="wpview_relation_with_thumbnail_text_color" value="<?php echo isset($this->optionsColors['wpview_relation_with_thumbnail_text_color']) ? $this->optionsColors['wpview_relation_with_thumbnail_text_color'] : $default['wpview_relation_with_thumbnail_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_with_thumbnail_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_with_thumbnail_title_color" name="wpview_relation_with_thumbnail_title_color" value="<?php echo isset($this->optionsColors['wpview_relation_with_thumbnail_title_color']) ? $this->optionsColors['wpview_relation_with_thumbnail_title_color'] : $default['wpview_relation_with_thumbnail_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_with_thumbnail_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_with_thumbnail_text_color_hover" name="wpview_relation_with_thumbnail_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_relation_with_thumbnail_text_color_hover']) ? $this->optionsColors['wpview_relation_with_thumbnail_text_color_hover'] : $default['wpview_relation_with_thumbnail_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Without Thumbnail', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Relation, Page Link, Post Object
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_relation_without_thumbnail_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_without_thumbnail_text_color" name="wpview_relation_without_thumbnail_text_color" value="<?php echo isset($this->optionsColors['wpview_relation_without_thumbnail_text_color']) ? $this->optionsColors['wpview_relation_without_thumbnail_text_color'] : $default['wpview_relation_without_thumbnail_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_without_thumbnail_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_without_thumbnail_title_color" name="wpview_relation_without_thumbnail_title_color" value="<?php echo isset($this->optionsColors['wpview_relation_without_thumbnail_title_color']) ? $this->optionsColors['wpview_relation_without_thumbnail_title_color'] : $default['wpview_relation_without_thumbnail_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_without_thumbnail_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_without_thumbnail_text_color_hover" name="wpview_relation_without_thumbnail_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_relation_without_thumbnail_text_color_hover']) ? $this->optionsColors['wpview_relation_without_thumbnail_text_color_hover'] : $default['wpview_relation_without_thumbnail_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Relation, Page Link, Post Object
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_relation_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_table_text_color" name="wpview_relation_table_text_color" value="<?php echo isset($this->optionsColors['wpview_relation_table_text_color']) ? $this->optionsColors['wpview_relation_table_text_color'] : $default['wpview_relation_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_table_title_color" name="wpview_relation_table_title_color" value="<?php echo isset($this->optionsColors['wpview_relation_table_title_color']) ? $this->optionsColors['wpview_relation_table_title_color'] : $default['wpview_relation_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_table_bg_color" name="wpview_relation_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_relation_table_bg_color']) ? $this->optionsColors['wpview_relation_table_bg_color'] : $default['wpview_relation_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_table_border_color" name="wpview_relation_table_border_color" value="<?php echo isset($this->optionsColors['wpview_relation_table_border_color']) ? $this->optionsColors['wpview_relation_table_border_color'] : $default['wpview_relation_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_relation_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_relation_table_text_color_hover" name="wpview_relation_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_relation_table_text_color_hover']) ? $this->optionsColors['wpview_relation_table_text_color_hover'] : $default['wpview_relation_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Image Thumbnail', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Image
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_image_image_thumbnail_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_image_thumbnail_title_color" name="wpview_image_image_thumbnail_title_color" value="<?php echo isset($this->optionsColors['wpview_image_image_thumbnail_title_color']) ? $this->optionsColors['wpview_image_image_thumbnail_title_color'] : $default['wpview_image_image_thumbnail_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Download Link', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Image
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_image_download_link_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_download_link_text_color" name="wpview_image_download_link_text_color" value="<?php echo isset($this->optionsColors['wpview_image_download_link_text_color']) ? $this->optionsColors['wpview_image_download_link_text_color'] : $default['wpview_image_download_link_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_image_download_link_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_download_link_title_color" name="wpview_image_download_link_title_color" value="<?php echo isset($this->optionsColors['wpview_image_download_link_title_color']) ? $this->optionsColors['wpview_image_download_link_title_color'] : $default['wpview_image_download_link_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_image_download_link_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_download_link_text_color_hover" name="wpview_image_download_link_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_image_download_link_text_color_hover']) ? $this->optionsColors['wpview_image_download_link_text_color_hover'] : $default['wpview_image_download_link_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Image
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_image_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_table_title_color" name="wpview_image_table_title_color" value="<?php echo isset($this->optionsColors['wpview_image_table_title_color']) ? $this->optionsColors['wpview_image_table_title_color'] : $default['wpview_image_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_image_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_table_bg_color" name="wpview_image_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_image_table_bg_color']) ? $this->optionsColors['wpview_image_table_bg_color'] : $default['wpview_image_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_image_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_image_table_border_color" name="wpview_image_table_border_color" value="<?php echo isset($this->optionsColors['wpview_image_table_border_color']) ? $this->optionsColors['wpview_image_table_border_color'] : $default['wpview_image_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('HTML5 Audio Player', 'wpview') . ', ' . __('WordPress Player', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Audio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_audio_player_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_player_title_color" name="wpview_audio_player_title_color" value="<?php echo isset($this->optionsColors['wpview_audio_player_title_color']) ? $this->optionsColors['wpview_audio_player_title_color'] : $default['wpview_audio_player_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Download Link', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Audio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_audio_download_link_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_download_link_text_color" name="wpview_audio_download_link_text_color" value="<?php echo isset($this->optionsColors['wpview_audio_download_link_text_color']) ? $this->optionsColors['wpview_audio_download_link_text_color'] : $default['wpview_audio_download_link_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_audio_download_link_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_download_link_title_color" name="wpview_audio_download_link_title_color" value="<?php echo isset($this->optionsColors['wpview_audio_download_link_title_color']) ? $this->optionsColors['wpview_audio_download_link_title_color'] : $default['wpview_audio_download_link_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_audio_download_link_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_download_link_text_color_hover" name="wpview_audio_download_link_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_audio_download_link_text_color_hover']) ? $this->optionsColors['wpview_audio_download_link_text_color_hover'] : $default['wpview_audio_download_link_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Audio
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_audio_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_table_title_color" name="wpview_audio_table_title_color" value="<?php echo isset($this->optionsColors['wpview_audio_table_title_color']) ? $this->optionsColors['wpview_audio_table_title_color'] : $default['wpview_audio_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_audio_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_table_bg_color" name="wpview_audio_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_audio_table_bg_color']) ? $this->optionsColors['wpview_audio_table_bg_color'] : $default['wpview_audio_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_audio_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_audio_table_border_color" name="wpview_audio_table_border_color" value="<?php echo isset($this->optionsColors['wpview_audio_table_border_color']) ? $this->optionsColors['wpview_audio_table_border_color'] : $default['wpview_audio_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('HTML5 Video Player', 'wpview') . ', ' . __('WordPress Player', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Video
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_video_player_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_player_title_color" name="wpview_video_player_title_color" value="<?php echo isset($this->optionsColors['wpview_video_player_title_color']) ? $this->optionsColors['wpview_video_player_title_color'] : $default['wpview_video_player_title_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Download Link', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Video
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_video_download_link_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_download_link_text_color" name="wpview_video_download_link_text_color" value="<?php echo isset($this->optionsColors['wpview_video_download_link_text_color']) ? $this->optionsColors['wpview_video_download_link_text_color'] : $default['wpview_video_download_link_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_video_download_link_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_download_link_title_color" name="wpview_video_download_link_title_color" value="<?php echo isset($this->optionsColors['wpview_video_download_link_title_color']) ? $this->optionsColors['wpview_video_download_link_title_color'] : $default['wpview_video_download_link_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_video_download_link_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_download_link_text_color_hover" name="wpview_video_download_link_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_video_download_link_text_color_hover']) ? $this->optionsColors['wpview_video_download_link_text_color_hover'] : $default['wpview_video_download_link_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Video
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_video_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_table_title_color" name="wpview_video_table_title_color" value="<?php echo isset($this->optionsColors['wpview_video_table_title_color']) ? $this->optionsColors['wpview_video_table_title_color'] : $default['wpview_video_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_video_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_table_bg_color" name="wpview_video_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_video_table_bg_color']) ? $this->optionsColors['wpview_video_table_bg_color'] : $default['wpview_video_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_video_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_video_table_border_color" name="wpview_video_table_border_color" value="<?php echo isset($this->optionsColors['wpview_video_table_border_color']) ? $this->optionsColors['wpview_video_table_border_color'] : $default['wpview_video_table_border_color']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Download Link', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: File
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_file_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_text_color" name="wpview_file_text_color" value="<?php echo isset($this->optionsColors['wpview_file_text_color']) ? $this->optionsColors['wpview_file_text_color'] : $default['wpview_file_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_title_color" name="wpview_file_title_color" value="<?php echo isset($this->optionsColors['wpview_file_title_color']) ? $this->optionsColors['wpview_file_title_color'] : $default['wpview_file_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_text_color_hover" name="wpview_file_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_file_text_color_hover']) ? $this->optionsColors['wpview_file_text_color_hover'] : $default['wpview_file_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: File
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_file_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_table_text_color" name="wpview_file_table_text_color" value="<?php echo isset($this->optionsColors['wpview_file_table_text_color']) ? $this->optionsColors['wpview_file_table_text_color'] : $default['wpview_file_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_table_title_color" name="wpview_file_table_title_color" value="<?php echo isset($this->optionsColors['wpview_file_table_title_color']) ? $this->optionsColors['wpview_file_table_title_color'] : $default['wpview_file_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_table_bg_color" name="wpview_file_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_file_table_bg_color']) ? $this->optionsColors['wpview_file_table_bg_color'] : $default['wpview_file_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_table_border_color" name="wpview_file_table_border_color" value="<?php echo isset($this->optionsColors['wpview_file_table_border_color']) ? $this->optionsColors['wpview_file_table_border_color'] : $default['wpview_file_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_file_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_file_table_text_color_hover" name="wpview_file_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_file_table_text_color_hover']) ? $this->optionsColors['wpview_file_table_text_color_hover'] : $default['wpview_file_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Comma separated', 'wpview') . ', ' . __('Without Separator', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Category
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_category_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_text_color" name="wpview_category_text_color" value="<?php echo isset($this->optionsColors['wpview_category_text_color']) ? $this->optionsColors['wpview_category_text_color'] : $default['wpview_category_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_title_color" name="wpview_category_title_color" value="<?php echo isset($this->optionsColors['wpview_category_title_color']) ? $this->optionsColors['wpview_category_title_color'] : $default['wpview_category_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_text_color_hover" name="wpview_category_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_category_text_color_hover']) ? $this->optionsColors['wpview_category_text_color_hover'] : $default['wpview_category_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Category
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_category_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_table_text_color" name="wpview_category_table_text_color" value="<?php echo isset($this->optionsColors['wpview_category_table_text_color']) ? $this->optionsColors['wpview_category_table_text_color'] : $default['wpview_category_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_table_title_color" name="wpview_category_table_title_color" value="<?php echo isset($this->optionsColors['wpview_category_table_title_color']) ? $this->optionsColors['wpview_category_table_title_color'] : $default['wpview_category_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_table_bg_color" name="wpview_category_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_category_table_bg_color']) ? $this->optionsColors['wpview_category_table_bg_color'] : $default['wpview_category_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_table_border_color" name="wpview_category_table_border_color" value="<?php echo isset($this->optionsColors['wpview_category_table_border_color']) ? $this->optionsColors['wpview_category_table_border_color'] : $default['wpview_category_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_table_text_color_hover" name="wpview_category_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_category_table_text_color_hover']) ? $this->optionsColors['wpview_category_table_text_color_hover'] : $default['wpview_category_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Category
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_category_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ul_text_color" name="wpview_category_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_category_ul_text_color']) ? $this->optionsColors['wpview_category_ul_text_color'] : $default['wpview_category_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ul_title_color" name="wpview_category_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_category_ul_title_color']) ? $this->optionsColors['wpview_category_ul_title_color'] : $default['wpview_category_ul_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_ul_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ul_text_color_hover" name="wpview_category_ul_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_category_ul_text_color_hover']) ? $this->optionsColors['wpview_category_ul_text_color_hover'] : $default['wpview_category_ul_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Category
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_category_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ol_text_color" name="wpview_category_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_category_ol_text_color']) ? $this->optionsColors['wpview_category_ol_text_color'] : $default['wpview_category_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ol_title_color" name="wpview_category_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_category_ol_title_color']) ? $this->optionsColors['wpview_category_ol_title_color'] : $default['wpview_category_ol_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_category_ol_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_category_ol_text_color_hover" name="wpview_category_ol_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_category_ol_text_color_hover']) ? $this->optionsColors['wpview_category_ol_text_color_hover'] : $default['wpview_category_ol_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Comma separated', 'wpview') . ', ' . __('Without Separator', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Tag
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_tag_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_text_color" name="wpview_tag_text_color" value="<?php echo isset($this->optionsColors['wpview_tag_text_color']) ? $this->optionsColors['wpview_tag_text_color'] : $default['wpview_tag_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_title_color" name="wpview_tag_title_color" value="<?php echo isset($this->optionsColors['wpview_tag_title_color']) ? $this->optionsColors['wpview_tag_title_color'] : $default['wpview_tag_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_text_color_hover" name="wpview_tag_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_tag_text_color_hover']) ? $this->optionsColors['wpview_tag_text_color_hover'] : $default['wpview_tag_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Table', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Tag
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_tag_table_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_table_text_color" name="wpview_tag_table_text_color" value="<?php echo isset($this->optionsColors['wpview_tag_table_text_color']) ? $this->optionsColors['wpview_tag_table_text_color'] : $default['wpview_tag_table_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_table_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_table_title_color" name="wpview_tag_table_title_color" value="<?php echo isset($this->optionsColors['wpview_tag_table_title_color']) ? $this->optionsColors['wpview_tag_table_title_color'] : $default['wpview_tag_table_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_table_bg_color">
                                    <span><?php _e('Background Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_table_bg_color" name="wpview_tag_table_bg_color" value="<?php echo isset($this->optionsColors['wpview_tag_table_bg_color']) ? $this->optionsColors['wpview_tag_table_bg_color'] : $default['wpview_tag_table_bg_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_table_border_color">
                                    <span><?php _e('Border Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_table_border_color" name="wpview_tag_table_border_color" value="<?php echo isset($this->optionsColors['wpview_tag_table_border_color']) ? $this->optionsColors['wpview_tag_table_border_color'] : $default['wpview_tag_table_border_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_table_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_table_text_color_hover" name="wpview_tag_table_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_tag_table_text_color_hover']) ? $this->optionsColors['wpview_tag_table_text_color_hover'] : $default['wpview_tag_table_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Unordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Tag
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ul_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ul_text_color" name="wpview_tag_ul_text_color" value="<?php echo isset($this->optionsColors['wpview_tag_ul_text_color']) ? $this->optionsColors['wpview_tag_ul_text_color'] : $default['wpview_tag_ul_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ul_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ul_title_color" name="wpview_tag_ul_title_color" value="<?php echo isset($this->optionsColors['wpview_tag_ul_title_color']) ? $this->optionsColors['wpview_tag_ul_title_color'] : $default['wpview_tag_ul_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ul_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ul_text_color_hover" name="wpview_tag_ul_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_tag_ul_text_color_hover']) ? $this->optionsColors['wpview_tag_ul_text_color_hover'] : $default['wpview_tag_ul_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php echo __('View', 'wpview') . ' - ' . __('Ordered List', 'wpview'); ?>
                            <span>
                                <?php _e('Available for custom field types', 'wpview'); ?>: Tag
                            </span>
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ol_text_color">
                                    <span><?php _e('Text Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ol_text_color" name="wpview_tag_ol_text_color" value="<?php echo isset($this->optionsColors['wpview_tag_ol_text_color']) ? $this->optionsColors['wpview_tag_ol_text_color'] : $default['wpview_tag_ol_text_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ol_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ol_title_color" name="wpview_tag_ol_title_color" value="<?php echo isset($this->optionsColors['wpview_tag_ol_title_color']) ? $this->optionsColors['wpview_tag_ol_title_color'] : $default['wpview_tag_ol_title_color']; ?>" />
                            </div>
                            <div class="wpview_color_option">
                                <label for="wpview_tag_ol_text_color_hover">
                                    <span><?php echo __('Text Hover Color', 'wpview') . ' (' . __('Only link', 'wpview') . ') '; ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_tag_ol_text_color_hover" name="wpview_tag_ol_text_color_hover" value="<?php echo isset($this->optionsColors['wpview_tag_ol_text_color_hover']) ? $this->optionsColors['wpview_tag_ol_text_color_hover'] : $default['wpview_tag_ol_text_color_hover']; ?>" />
                            </div>
                        </div>
                        <h3>
                            <?php _e('View', 'wpview'); ?> - Google Map
                        </h3>
                        <div class="wpview_colors_group wpview_hidden">
                            <div class="wpview_color_option">
                                <label for="wpview_google_map_title_color">
                                    <span><?php _e('Title Color', 'wpview'); ?></span>
                                </label>
                                <input type="text" class="wpview_color_picker" id="wpview_google_map_title_color" name="wpview_google_map_title_color" value="<?php echo isset($this->optionsColors['wpview_google_map_title_color']) ? $this->optionsColors['wpview_google_map_title_color'] : $default['wpview_google_map_title_color']; ?>" />
                            </div>
                        </div>
                    </div>   
                    <div class="wpview_admin_button_group">             
                        <button type="submit" class="wpview_buttons" name="wpview_reset_colors" id="wpview_reset_colors"><?php _e('Reset Options', 'wpview'); ?></button>
                        <button type="submit" class="wpview_buttons" name="wpview_save_colors" id="wpview_save_colors"><?php _e('Save Changes', 'wpview'); ?></button>
                    </div>
                    <?php wp_nonce_field('wpview_reset_colors', 'wpview_nonce_field_colors'); ?>
                </form>
            </div>
        </div>
        <?php
    }

}
