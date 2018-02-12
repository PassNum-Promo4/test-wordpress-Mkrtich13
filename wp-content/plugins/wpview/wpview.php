<?php
/*
 * Plugin Name: Display Custom Fields - wpView
 * Description: An easy tool-kit for displaying custom fields with different views on front-end. Displays custom fields of all popular custom field creator plugins.
 * Version: 1.3.0
 * Author: gVectors Team (T.Antonyan, T.Muradyan, M.Aleksanyan, Y.Shahinyan, H.Nikolyan)
 * Contributors: A. Chakhoyan, G. Zakaryan, H. Martirosyan
 * Author URI: http://gvectors.com/
 * Plugin URI: http://gvectors.com/
 * Text Domain: wpview
 * Domain Path: /languages/
 */

define('WPVIEW_VIEWS', 'wpview_options_views');
define('WPVIEW_COLORS', 'wpview_options_colors');

include_once 'includes/autoload.php';

class wpView {

    private $init;
    private $views;
    private $plugins;
    private $options;

    public function __construct() {
        add_action('plugins_loaded', array(&$this, 'pluginsLoaded'), 111);
    }

    public function pluginsLoaded() {
        $this->init = new \wpView\Init();
        $this->options = new \wpView\options\Options(WPVIEW_VIEWS, WPVIEW_COLORS);
        $this->initPlugins();
        $fields = array('Text' => array(), 'Textarea' => array(), 'Number' => array(), 'Email' => array(), 'Phone' => array(), 'Currency' => array(), 'WYSIWYG' => array(), 'Date' => array(), 'Time' => array(), 'Color' => array(), 'Select' => array(), 'Checkbox' => array(), 'True/False' => array(), 'Checkboxes' => array(), 'Radio' => array(), 'Hyperlink' => array(), 'Embed' => array(), 'User' => array(), 'Relation' => array(), 'Page Link' => array(), 'Post Object' => array(), 'Image' => array(), 'Audio' => array(), 'Video' => array(), 'File' => array(), 'Category' => array(), 'Post Tag' => array(), 'Google Map' => array());
        if ($this->plugins) {
            $this->initViews();
            add_filter('the_content', array(&$this, 'drawContent'), 999);
            add_action('wp_enqueue_scripts', array(&$this, 'stylesAndScripts'));
            add_action('wp_head', array(&$this, 'dynamicCSS'));
            $options = get_option(WPVIEW_VIEWS);
            add_shortcode('wpview', array(&$this, 'wpviewShortcode'));
            add_action('init', array(&$this, 'wpview_buttons'), 999);
            add_action('admin_footer', array(&$this, 'wpview_dialog'), 999);
        }
        $this->initAdminViews($fields);
        $this->options->views = $fields;
    }

    public function wpview_dialog() {
        $screen = get_current_screen();
        if ($screen->parent_base == 'edit') {
            $types = array();
            $views = array();
            $options = maybe_unserialize(get_option(WPVIEW_VIEWS));
            global $post;
            $custom_fields = array();
            foreach ($this->plugins as $key => $values) {
                foreach ($values->getFieldTypes() as $k => $val) {
                    $field_type = strtolower(str_replace(array('/', ' '), '_', $k));
                    if (!in_array($k, $types)) {
                        $types[] = $k;
                    }
                    $views[$field_type] = $val;
                }
                foreach ($values->getFields($post->ID) as $value) {
                    foreach ($value as $k => $val) {
                        $field_type = strtolower(str_replace(array('/', ' '), '_', $k));
                        foreach ($val as $v) {
                            $i = count($custom_fields);
                            $custom_fields[$i]['type'] = $k;
                            $custom_fields[$i]['title'] = $v['title'];
                        }
                    }
                }
            }
            ?>
            <div id="wpview_dialog" style="display: none;">
                <div class="wpview_shortcode_fields_group">
                    <label>
                        <input type="radio" name="wpview_shortcode" title="All in one Table" data-id="all" class="wpview_field_types" />All in one Table
                    </label>
                </div>
                <fieldset class="wpview_field_parent">
                    <legend style="margin-left: 10px;">
                        <strong><?php _e('Insert Custom Field (individual display mode)', 'wpview') ?></strong>
                    </legend>
                    <div>
                        <?php
                        if ($custom_fields) {
                            foreach ($custom_fields as $field) {
                                ?>
                                <div class="wpview_shortcode_fields_group">
                                    <label>
                                        <input type="radio" name="wpview_shortcode" title="<?php echo $field['title']; ?>" data-id="<?php echo $field['type']; ?>" class="wpview_field_types" /><?php echo $field['title'] . ' (' . $field['type'] . ')'; ?>
                                    </label>
                                    <select>
                                        <?php
                                        foreach ($views[$field['type']] as $k => $val) {
                                            ?>
                                            <option value="<?php echo $k; ?>" <?php echo (isset($options['views_for_all'][$field['type']]['view']) && $options['views_for_all'][$field['type']]['view'] === $k) ? 'selected="selected"' : ''; ?>><?php echo $val; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <h4 style="padding: 5px 15px; color: #FF5521; font-weight: 400;">There is no custom fields</h4>
                            <?php
                        }
                        ?>
                    </div>
                </fieldset>
                <fieldset class="wpview_field_parent">
                    <legend style="margin-left: 10px;">
                        <strong><?php _e('Insert All Custom Fields with Certain Type (grouped display mode)', 'wpview') ?></strong>
                    </legend>
                    <div>
                        <?php
                        if ($types) {
                            foreach ($types as $type) {
                                $field_type = strtolower(str_replace(array('/', ' '), '_', $type));
                                ?>
                                <div class="wpview_shortcode_fields_group">
                                    <label>
                                        <input type="radio" name="wpview_shortcode" title="" data-id="<?php echo $field_type; ?>" class="wpview_field_types" /><?php echo $type; ?>
                                    </label>
                                    <select>
                                        <?php
                                        foreach ($views[$field_type] as $k => $val) {
                                            ?>
                                            <option value="<?php echo $k; ?>" <?php echo (isset($options['views_for_all'][$field_type]['view']) && $options['views_for_all'][$field_type]['view'] === $k) ? 'selected="selected"' : ''; ?>><?php echo $val; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <h4 style="padding: 5px 15px; color: #FF5521; font-weight: 400;">There is no custom fields</h4>
                            <?php
                        }
                        ?>
                    </div>
                </fieldset>
                <div class="put-shortcode-parent">
                    <button type="button" class="button button-primary button-large put-shortcode">Put</button>
                </div>
            </div>
            <?php
        }
    }

    public function wpview_buttons() {
        add_filter('mce_buttons', array(&$this, 'wpview_mce_buttons'), 0);
        add_filter('mce_external_plugins', array(&$this, 'wpview_mce_external_plugins'), 0);
    }

    public function wpview_mce_buttons($buttons) {
        array_push($buttons, '|', 'wpView');
        return $buttons;
    }

    public function wpview_mce_external_plugins($plugin_array) {
        $plugin_array['wpView'] = plugins_url('wpview/assets/js/tinymce.js');
        return $plugin_array;
    }

    private function initPlugins() {
        foreach ($this->init->getPlugins() as $key => $val) {
            if (isset($_POST['wpview_reset_views']) && wp_verify_nonce($_POST['wpview_nonce_field_reset_views'], 'wpview_reset_views')) {
                if (class_exists($key)) {
                    $this->plugins[$key] = new $val;
                }
            } else if (isset($_POST['wpview_set_none_views']) && wp_verify_nonce($_POST['wpview_nonce_field_set_none_views'], 'wpview_set_none_views')) {
                $options = get_option(WPVIEW_VIEWS);
                if (isset($options['wpview_plugin_names'][$key])) {
                    $this->plugins[$key] = new $val;
                }
            } else if (isset($_POST['wpview_plugin_names'])) {
                if (isset($_POST['wpview_plugin_names'][$key]) && class_exists($key)) {
                    $this->plugins[$key] = new $val;
                }
            } else if (empty($_POST)) {
                $options = get_option(WPVIEW_VIEWS);
                if (isset($options['wpview_plugin_names'][$key])) {
                    $this->plugins[$key] = new $val;
                }
            }
        }
    }

    private function initViews() {
        foreach ($this->init->getViews() as $key => $val) {
            foreach ($val as $k => $v) {
                $view = $v::getInstance();
                if (isset($this->plugins[$key])) {
                    if (isset($this->views[$k]) && !in_array($view, $this->views[$k])) {
                        $this->views[$k][] = $view;
                    } else if (!isset($this->views[$k])) {
                        $this->views[$k][] = $view;
                    }
                }
            }
        }
    }

    private function initAdminViews(&$fields) {
        $fields['show_title'] = false;
        foreach ($this->init->getPlugins() as $name => $plugin) {
            if (isset($this->plugins[$name]) || class_exists($name)) {
                $plugins = new $plugin;
                $values = $plugins->getFieldTypes();
                foreach ($values as $key => $value) {
                    foreach ($value as $k => $val) {
                        if (!isset($fields[$key][$k])) {
                            $fields[$key][$k] = $val;
                        }
                    }
                    if (isset($fields[$key]['logo'])) {
                        $i = count($fields[$key]['logo']);
                    } else {
                        $i = 0;
                    }
                    $fields[$key]['logo'][$i]['url'] = plugins_url('wpview/assets/images/' . $name . '.png');
                    if (isset($this->plugins[$name])) {
                        $fields[$key]['logo'][$i]['class'] = 'wpview_display';
                    } else if (class_exists($name)) {
                        $fields[$key]['logo'][$i]['class'] = 'wpview_hidden';
                    }
                    $fields[$key]['logo'][$i]['id'] = 'wpview_plugin_' . $name;
                    if (isset($this->plugins[$name]) && (!isset($fields[$key]['class']) || $fields[$key]['class'] == "wpview_hidden")) {
                        $fields[$key]['class'] = 'wpview_display';
                        $fields['show_title'] = true;
                    } else if (class_exists($name) && !isset($fields[$key]['class'])) {
                        $fields[$key]['class'] = 'wpview_hidden';
                    }
                }
            }
        }
    }

    public function stylesAndScripts() {
        wp_register_style('wpview_style', plugins_url('wpview/assets/css/style.css'));
        wp_enqueue_style('wpview_style');
    }

    public function wpviewShortcode($attr) {
        $this->views = apply_filters('wpview_init_views', $this->views);
        $structure = '';
        if (is_singular()) {
            if (isset($attr['type']) && $attr['type']) {
                if ($attr['type'] !== 'all') {
                    $view = isset($attr['view']) ? $attr['view'] : '';
                    $title = isset($attr['title']) ? $attr['title'] : '';
                } else {
                    $title = '';
                    $view = '';
                }
                global $post;
                $custom_fields = array();
                $inArray = array();
                $options = maybe_unserialize(get_option(WPVIEW_VIEWS));
                foreach ($this->plugins as $values) {
                    foreach ($values->getFields($post->ID, $attr['type'], $title) as $value) {
                        foreach ($value as $k => $val) {
                            if ($view || (isset($options['views_for_all'][$k]['view']) && $options['views_for_all'][$k]['view'] !== 'none')) {
                                foreach ($val as $v) {
                                    if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                        if (!isset($inArray[$k])) {
                                            $i = count($custom_fields);
                                        } else {
                                            $i = $inArray[$k];
                                        }
                                    } else {
                                        $i = count($custom_fields);
                                    }
                                    if (!isset($inArray[$k])) {
                                        $inArray[$k] = $i;
                                    }
                                    $custom_fields[$i]['view'] = $view ? $view : $options['views_for_all'][$k]['view'];
                                    $custom_fields[$i]['type'] = $k;
                                    $custom_fields[$i]['value'][] = $v;
                                }
                            }
                        }
                    }
                }
                if ($custom_fields) {
                    foreach ($custom_fields as $val) {
                        if (isset($this->views[$val['type']])) {
                            for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                                $this->views[$val['type']][$i]->setView($val['view']);
                                $this->views[$val['type']][$i]->setData($val);
                                $structure .= $this->views[$val['type']][$i]->draw();
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($attr['type']) && $attr['type']) {
                if ($attr['type'] !== 'all') {
                    $title = isset($attr['title']) ? $attr['title'] : '';
                } else {
                    $title = '';
                }
                global $post;
                $custom_fields = array();
                $inArray = array();
                $options = maybe_unserialize(get_option(WPVIEW_VIEWS));
                foreach ($this->plugins as $values) {
                    foreach ($values->getFields($post->ID, $attr['type'], $title) as $value) {
                        foreach ($value as $k => $val) {
                            if (isset($options['views_for_all'][$k]['view']) && $options['views_for_all'][$k]['view'] !== 'none' && isset($options['views_for_all'][$k]['show_in_excerpt']) && $options['views_for_all'][$k]['show_in_excerpt'] === 'yes') {
                                foreach ($val as $v) {
                                    if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                        if (!isset($inArray[$k])) {
                                            $i = count($custom_fields);
                                        } else {
                                            $i = $inArray[$k];
                                        }
                                    } else {
                                        $i = count($custom_fields);
                                    }
                                    if (!isset($inArray[$k])) {
                                        $inArray[$k] = $i;
                                    }
                                    $custom_fields[$i]['type'] = $k;
                                    $custom_fields[$i]['value'][] = $v;
                                }
                            }
                        }
                    }
                }
                if ($custom_fields) {
                    foreach ($custom_fields as $val) {
                        if (isset($this->views[$val['type']])) {
                            for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                                $this->views[$val['type']][$i]->setView($options['views_for_all'][$val['type']]['view']);
                                $this->views[$val['type']][$i]->setData($val);
                                $structure .= $this->views[$val['type']][$i]->draw();
                            }
                        }
                    }
                }
            }
        }
        return $structure;
    }

    public function drawContent($content) {
        $this->views = apply_filters('wpview_init_views', $this->views);
        $beforeContent = array();
        $inBeforeContent = array();
        $afterContent = array();
        $inAfterContent = array();
        $structureBeforeContent = '';
        $structureAfterContent = '';
        if (is_singular()) {
            global $post;
            $options = maybe_unserialize(get_option(WPVIEW_VIEWS));
            foreach ($this->plugins as $values) {
                foreach ($values->getFields($post->ID) as $value) {
                    foreach ($value as $k => $val) {
                        if ($options['wpview_choose_views'] === 'display_together_before_content' || ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$k]['view']) && isset($options['views_for_all'][$k]['position']) && $options['views_for_all'][$k]['view'] !== 'none' && $options['views_for_all'][$k]['position'] === 'before_content')) {
                            foreach ($val as $v) {
                                if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                    if (!isset($inBeforeContent[$k])) {
                                        $i = count($beforeContent);
                                    } else {
                                        $i = $inBeforeContent[$k];
                                    }
                                } else {
                                    $i = count($beforeContent);
                                }
                                if (!isset($inBeforeContent[$k])) {
                                    $inBeforeContent[$k] = $i;
                                }
                                $beforeContent[$i]['type'] = $k;
                                $beforeContent[$i]['value'][] = $v;
                            }
                        } else if ($options['wpview_choose_views'] === 'display_together' || (isset($options['views_for_all'][$k]['view']) && isset($options['views_for_all'][$k]['position']) && $options['views_for_all'][$k]['view'] !== 'none' && $options['views_for_all'][$k]['position'] === 'after_content')) {
                            foreach ($val as $v) {
                                if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                    if (!isset($inAfterContent[$k])) {
                                        $i = count($afterContent);
                                    } else {
                                        $i = $inAfterContent[$k];
                                    }
                                } else {
                                    $i = count($afterContent);
                                }
                                if (!isset($inAfterContent[$k])) {
                                    $inAfterContent[$k] = $i;
                                }
                                $afterContent[$i]['type'] = $k;
                                $afterContent[$i]['value'][] = $v;
                            }
                        }
                    }
                }
            }
            if ($beforeContent) {
                if ($options['wpview_choose_views'] === 'display_together_before_content') {
                    $structureBeforeContent .= '<table class="wpview_all_together_in_table">';
                    foreach ($beforeContent as $val) {
                        for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                            $this->views[$val['type']][$i]->setView('all_together_in_table');
                            $this->views[$val['type']][$i]->setData($val);
                            $structureBeforeContent .= $this->views[$val['type']][$i]->draw();
                        }
                    }
                    $structureBeforeContent .= '</table>';
                } else {
                    foreach ($beforeContent as $val) {
                        if (isset($this->views[$val['type']])) {
                            for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                                $this->views[$val['type']][$i]->setView($options['views_for_all'][$val['type']]['view']);
                                $this->views[$val['type']][$i]->setData($val);
                                $structureBeforeContent .= $this->views[$val['type']][$i]->draw();
                            }
                        }
                    }
                }
            }
            if ($afterContent) {
                if ($options['wpview_choose_views'] === 'display_together') {
                    $structureAfterContent .= '<table class="wpview_all_together_in_table">';
                    foreach ($afterContent as $val) {
                        for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                            $this->views[$val['type']][$i]->setView('all_together_in_table');
                            $this->views[$val['type']][$i]->setData($val);
                            $structureAfterContent .= $this->views[$val['type']][$i]->draw();
                        }
                    }
                    $structureAfterContent .= '</table>';
                } else {
                    foreach ($afterContent as $val) {
                        if (isset($this->views[$val['type']])) {
                            for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                                $this->views[$val['type']][$i]->setView($options['views_for_all'][$val['type']]['view']);
                                $this->views[$val['type']][$i]->setData($val);
                                $structureAfterContent .= $this->views[$val['type']][$i]->draw();
                            }
                        }
                    }
                }
            }
        } else {
            global $post;
            $options = maybe_unserialize(get_option(WPVIEW_VIEWS));
            foreach ($this->plugins as $values) {
                foreach ($values->getFields($post->ID) as $value) {
                    foreach ($value as $k => $val) {
                        if ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$k]['view']) && isset($options['views_for_all'][$k]['position']) && $options['views_for_all'][$k]['view'] !== 'none' && $options['views_for_all'][$k]['position'] === 'before_content' && isset($options['views_for_all'][$k]['show_in_excerpt']) && $options['views_for_all'][$k]['show_in_excerpt'] === 'yes') {
                            foreach ($val as $v) {
                                if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                    if (!isset($inBeforeContent[$k])) {
                                        $i = count($beforeContent);
                                    } else {
                                        $i = $inBeforeContent[$k];
                                    }
                                } else {
                                    $i = count($beforeContent);
                                }
                                if (!isset($inBeforeContent[$k])) {
                                    $inBeforeContent[$k] = $i;
                                }
                                $beforeContent[$i]['type'] = $k;
                                $beforeContent[$i]['value'][] = $v;
                            }
                        } else if (isset($options['views_for_all'][$k]['view']) && isset($options['views_for_all'][$k]['position']) && $options['views_for_all'][$k]['view'] !== 'none' && $options['views_for_all'][$k]['position'] === 'after_content' && isset($options['views_for_all'][$k]['show_in_excerpt']) && $options['views_for_all'][$k]['show_in_excerpt'] === 'yes') {
                            foreach ($val as $v) {
                                if (isset($options['views_for_all'][$k]['group']) && $options['views_for_all'][$k]['group'] === 'yes') {
                                    if (!isset($inAfterContent[$k])) {
                                        $i = count($afterContent);
                                    } else {
                                        $i = $inAfterContent[$k];
                                    }
                                } else {
                                    $i = count($afterContent);
                                }
                                if (!isset($inAfterContent[$k])) {
                                    $inAfterContent[$k] = $i;
                                }
                                $afterContent[$i]['type'] = $k;
                                $afterContent[$i]['value'][] = $v;
                            }
                        }
                    }
                }
            }
            if ($beforeContent) {
                foreach ($beforeContent as $val) {
                    if (isset($this->views[$val['type']])) {
                        for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                            $this->views[$val['type']][$i]->setView($options['views_for_all'][$val['type']]['view']);
                            $this->views[$val['type']][$i]->setData($val);
                            $structureBeforeContent .= $this->views[$val['type']][$i]->draw();
                        }
                    }
                }
            }
            if ($afterContent) {
                foreach ($afterContent as $val) {
                    if (isset($this->views[$val['type']])) {
                        for ($i = 0; $i < count($this->views[$val['type']]); $i++) {
                            $this->views[$val['type']][$i]->setView($options['views_for_all'][$val['type']]['view']);
                            $this->views[$val['type']][$i]->setData($val);
                            $structureAfterContent .= $this->views[$val['type']][$i]->draw();
                        }
                    }
                }
            }
        }
        return $structureBeforeContent . $content . $structureAfterContent;
    }

    public function dynamicCSS() {
        ?>
        <style>
            .wpview_all_together_in_table td { border: 2px solid <?php echo $this->options->optionsColors['wpview_all_together_border_color']; ?>!important; }
            .wpview_all_together_in_table td:nth-child(odd) strong { color: <?php echo $this->options->optionsColors['wpview_all_together_title_color']; ?>; }
            .wpview_all_together_in_table td:nth-child(odd) { background-color: <?php echo $this->options->optionsColors['wpview_all_together_left_bg_color']; ?>; }
            .wpview_all_together_in_table td:nth-child(even) { background-color: <?php echo $this->options->optionsColors['wpview_all_together_right_bg_color']; ?>; }
            .wpview_text_left_border { color: <?php echo $this->options->optionsColors['wpview_text_text_color']; ?>; border-color: <?php echo $this->options->optionsColors['wpview_text_border_color']; ?>; border-left-color: <?php echo $this->options->optionsColors['wpview_text_left_border']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_text_bg_color']; ?>; }
            .wpview_text_left_border strong { color: <?php echo $this->options->optionsColors['wpview_text_title_color']; ?>; }
            .wpview_textarea_quote_box { color: <?php echo $this->options->optionsColors['wpview_textarea_text_color']; ?>; background: <?php echo $this->options->optionsColors['wpview_textarea_bg_color']; ?>; box-shadow: 0 3px 5px 0 <?php echo $this->options->optionsColors['wpview_textarea_shadow_color']; ?>; }
            .wpview_textarea_quote_box strong { color: <?php echo $this->options->optionsColors['wpview_textarea_title_color']; ?>; }
            .wpview_textarea_quote_box strong:before, .wpview_textarea_quote_box strong:before { color: <?php echo $this->options->optionsColors['wpview_textarea_text_color']; ?>; }
            .wpview_text_view table { color: <?php echo $this->options->optionsColors['wpview_text_table_text_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_text_table_bg_color']; ?>; }
            .wpview_text_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_text_table_border_color']; ?>; }
            .wpview_text_view table strong { color: <?php echo $this->options->optionsColors['wpview_text_table_title_color']; ?>; }
            .wpview_text_view ul { color: <?php echo $this->options->optionsColors['wpview_text_ul_text_color']; ?>; }
            .wpview_text_view ul strong { color: <?php echo $this->options->optionsColors['wpview_text_ul_title_color']; ?>; }
            .wpview_text_view ol { color: <?php echo $this->options->optionsColors['wpview_text_ol_text_color']; ?>; }
            .wpview_text_view ol strong { color: <?php echo $this->options->optionsColors['wpview_text_ol_title_color']; ?>; }
            .wpview_date { color: <?php echo $this->options->optionsColors['wpview_date_text_color']; ?>; border-color: <?php echo $this->options->optionsColors['wpview_date_border_color']; ?>; border-left-color: <?php echo $this->options->optionsColors['wpview_date_left_border']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_date_bg_color']; ?>; }
            .wpview_date strong { color: <?php echo $this->options->optionsColors['wpview_date_title_color']; ?>; }
            .wpview_date_view table { color: <?php echo $this->options->optionsColors['wpview_date_table_text_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_date_table_bg_color']; ?>; }
            .wpview_date_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_date_table_border_color']; ?>; }
            .wpview_date_view table strong { color: <?php echo $this->options->optionsColors['wpview_date_table_title_color']; ?>; }
            .wpview_date_view ul { color: <?php echo $this->options->optionsColors['wpview_date_ul_text_color']; ?>; }
            .wpview_date_view ul strong { color: <?php echo $this->options->optionsColors['wpview_date_ul_title_color']; ?>; }
            .wpview_date_view ol { color: <?php echo $this->options->optionsColors['wpview_date_ol_text_color']; ?>; }
            .wpview_date_view ol strong { color: <?php echo $this->options->optionsColors['wpview_date_ol_title_color']; ?>; }
            .wpview_wysiwyg { background-color: <?php echo $this->options->optionsColors['wpview_wysiwyg_bg_color']; ?>; border: 1px solid <?php echo $this->options->optionsColors['wpview_wysiwyg_border_color']; ?>; }
            .wpview_wysiwyg h3 { color: <?php echo $this->options->optionsColors['wpview_wysiwyg_title_color']; ?>; padding:5px 0px; line-height:1.1em; margin: 0px 0px 15px 0px; }
            .wpview_wysiwyg_view table { background-color: <?php echo $this->options->optionsColors['wpview_wysiwyg_table_bg_color']; ?>; }
            .wpview_wysiwyg_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_wysiwyg_table_border_color']; ?>; }
            .wpview_wysiwyg_view table strong { color: <?php echo $this->options->optionsColors['wpview_wysiwyg_table_title_color']; ?>; }
            .wpview_color { color: <?php echo $this->options->optionsColors['wpview_color_text_color']; ?>; }
            .wpview_color strong { color: <?php echo $this->options->optionsColors['wpview_color_title_color']; ?>; }
            .wpview_color_view table { color: <?php echo $this->options->optionsColors['wpview_color_table_text_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_color_table_bg_color']; ?>; }
            .wpview_color_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_color_table_border_color']; ?>; }
            .wpview_color_view table strong { color: <?php echo $this->options->optionsColors['wpview_color_table_title_color']; ?>; }
            .wpview_color_view ul { color: <?php echo $this->options->optionsColors['wpview_color_ul_text_color']; ?>; }
            .wpview_color_view ul strong { color: <?php echo $this->options->optionsColors['wpview_color_ul_title_color']; ?>; }
            .wpview_color_view ol { color: <?php echo $this->options->optionsColors['wpview_color_ol_text_color']; ?>; }
            .wpview_color_view ol strong { color: <?php echo $this->options->optionsColors['wpview_color_ol_title_color']; ?>; }
            .wpview_select { color: <?php echo $this->options->optionsColors['wpview_select_text_color']; ?>; border: 1px dotted <?php echo $this->options->optionsColors['wpview_select_border_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_select_bg_color']; ?>; }
            .wpview_select strong { color: <?php echo $this->options->optionsColors['wpview_select_title_color']; ?>; }
            .wpview_select_view table { color: <?php echo $this->options->optionsColors['wpview_select_table_text_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_select_table_bg_color']; ?>; }
            .wpview_select_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_select_table_border_color']; ?>; }
            .wpview_select_view table strong { color: <?php echo $this->options->optionsColors['wpview_select_table_title_color']; ?>; }
            .wpview_select_view ul { color: <?php echo $this->options->optionsColors['wpview_select_ul_text_color']; ?>; }
            .wpview_select_view ul strong { color: <?php echo $this->options->optionsColors['wpview_select_ul_title_color']; ?>; }
            .wpview_select_view ol { color: <?php echo $this->options->optionsColors['wpview_select_ol_text_color']; ?>; }
            .wpview_select_view ol strong { color: <?php echo $this->options->optionsColors['wpview_select_ol_title_color']; ?>; }
            .wpview_checkbox { color: <?php echo $this->options->optionsColors['wpview_checkbox_text_color']; ?>; background: <?php echo $this->options->optionsColors['wpview_checkbox_bg_color']; ?>; box-shadow: 0 5px 5px 0 <?php echo $this->options->optionsColors['wpview_checkbox_shadow_color']; ?>; }
            .wpview_checkbox strong { color: <?php echo $this->options->optionsColors['wpview_checkbox_title_color']; ?>; }
            .wpview_checkbox_view table { color: <?php echo $this->options->optionsColors['wpview_checkbox_table_text_color']; ?>; background-color: <?php echo $this->options->optionsColors['wpview_checkbox_table_bg_color']; ?>; }
            .wpview_checkbox_view table td {border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_checkbox_table_border_color']; ?>; }
            .wpview_checkbox_view table strong { color: <?php echo $this->options->optionsColors['wpview_checkbox_table_title_color']; ?>; }
            .wpview_checkbox_view ul { color: <?php echo $this->options->optionsColors['wpview_checkbox_ul_text_color']; ?>; }
            .wpview_checkbox_view ul strong { color: <?php echo $this->options->optionsColors['wpview_checkbox_ul_title_color']; ?>; }
            .wpview_checkbox_view ol { color: <?php echo $this->options->optionsColors['wpview_checkbox_ol_text_color']; ?>; }
            .wpview_checkbox_view ol strong { color: <?php echo $this->options->optionsColors['wpview_checkbox_ol_title_color']; ?>; }
            .wpview_hyperlink a { color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; }
            .hyperlink_field_label:before { color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; }
            .wpview_hyperlink strong { color: <?php echo $this->options->optionsColors['wpview_hyperlink_title_color']; ?>; }
            .wpview_hyperlink a:active { color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; }
            .wpview_hyperlink a:visited { color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color']; ?>; }
            .wpview_hyperlink a:hover { color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_text_color_hover']; ?> !important; }
            .wpview_hyperlink_view table { background-color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_bg_color']; ?>; }
            .wpview_hyperlink_view table a { color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?>; }
            .wpview_hyperlink_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_hyperlink_table_border_color']; ?>; }
            .wpview_hyperlink_view table strong { color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_title_color']; ?>; }
            .wpview_hyperlink_view table a:active { color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?> !important; }
            .wpview_hyperlink_view table a:visited { color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color']; ?> !important; }
            .wpview_hyperlink_view table a:hover { color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_table_text_color_hover']; ?> !important; }
            .wpview_hyperlink_view ul { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?>; }
            .wpview_hyperlink_view ul a { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?>; }
            .wpview_hyperlink_view ul strong { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_title_color']; ?>; }
            .wpview_hyperlink_view ul a:active { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?> !important; }
            .wpview_hyperlink_view ul a:visited { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color']; ?> !important; }
            .wpview_hyperlink_view ul a:hover { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ul_text_color_hover']; ?> !important; }
            .wpview_hyperlink_view ol { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?>; }
            .wpview_hyperlink_view ol a { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?>; }
            .wpview_hyperlink_view ol strong { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_title_color']; ?>; }
            .wpview_hyperlink_view ol a:active { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?> !important; }
            .wpview_hyperlink_view ol a:visited { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color']; ?> !important; }
            .wpview_hyperlink_view ol a:hover { color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_hyperlink_ol_text_color_hover']; ?> !important; }
            .wpview_user a { color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; }
            .wpview_user strong { color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_title_color']; ?>; }
            .wpview_user a:active { color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; }
            .wpview_user a:visited { color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color']; ?>; }
            .wpview_user a:hover { color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_with_avatar_text_color_hover']; ?> !important; }
            .wpview_user_without_avatar a { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; }
            .user_field_label:before { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; }
            .wpview_user_without_avatar strong { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_title_color']; ?>; }
            .wpview_user_without_avatar a:active { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; }
            .wpview_user_without_avatar a:visited { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color']; ?>; }
            .wpview_user_without_avatar a:hover { color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_without_avatar_text_color_hover']; ?> !important; }
            .wpview_user_view table { background-color: <?php echo $this->options->optionsColors['wpview_user_table_bg_color']; ?>; }
            .wpview_user_view table a { color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?>; }
            .wpview_user_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_user_table_border_color']; ?>; }
            .wpview_user_view table strong { color: <?php echo $this->options->optionsColors['wpview_user_table_title_color']; ?>; }
            .wpview_user_view table a:active { color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?> !important; }
            .wpview_user_view table a:visited { color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_table_text_color']; ?> !important; }
            .wpview_user_view table a:hover { color: <?php echo $this->options->optionsColors['wpview_user_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_user_table_text_color_hover']; ?> !important; }
            .wpview_relation_with_thumbnail a, .wpview_relation_with_thumbnail span { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?> !important; }
            .wpview_relation_with_thumbnail_title a { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?> !important; }
            .wpview_relation_view strong { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_title_color']; ?>; }
            .wpview_relation_with_thumbnail a:active, .wpview_relation_with_thumbnail span:active { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; }
            .wpview_relation_with_thumbnail_title a:active { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; }
            .wpview_relation_with_thumbnail a:visited, .wpview_relation_with_thumbnail span:visited { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; }
            .wpview_relation_with_thumbnail_title a:visited { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color']; ?>; }
            .wpview_relation_with_thumbnail a:hover, .wpview_relation_with_thumbnail span:hover { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color_hover']; ?> !important; }
            .wpview_relation_with_thumbnail_title a:hover { color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_with_thumbnail_text_color_hover']; ?> !important; }
            .wpview_relation_without_thumbnail a, .wpview_relation_without_thumbnail span { color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?> !important; }
            .relation_without_thumb strong { color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_title_color']; ?>; }
            .wpview_relation_without_thumbnail a:active { color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?>; }
            .wpview_relation_without_thumbnail a:visited { color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color']; ?>; }
            .wpview_relation_without_thumbnail a:hover { color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_without_thumbnail_text_color_hover']; ?> !important; }
            .wpview_relation_view table { background-color: <?php echo $this->options->optionsColors['wpview_relation_table_bg_color']; ?>; }
            .wpview_relation_view table a, .wpview_relation_view table span { color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?>; }
            .wpview_relation_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_relation_table_border_color']; ?>; }
            .wpview_relation_view table strong { color: <?php echo $this->options->optionsColors['wpview_relation_table_title_color']; ?>; }
            .wpview_relation_view table a:active, .wpview_relation_view table span:active { color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?> !important; }
            .wpview_relation_view table a:visited, .wpview_relation_view table span:visited { color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color']; ?> !important; }
            .wpview_relation_view table a:hover, .wpview_relation_view table span:hover { color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_relation_table_text_color_hover']; ?> !important; }
            .wpview_image_image_thumbnail strong { color: <?php echo $this->options->optionsColors['wpview_image_image_thumbnail_title_color']; ?>; }
            .wpview_image_only_link a { color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?> !important; }
            .image_link_to_download_field_label:before { color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?> !important; }
            .wpview_image_only_link strong { color: <?php echo $this->options->optionsColors['wpview_image_download_link_title_color']; ?> !important; }
            .wpview_image_only_link a:active { color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?>; }
            .wpview_image_only_link a:visited { color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color']; ?>; }
            .wpview_image_only_link a:hover { color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_image_download_link_text_color_hover']; ?> !important; }
            .wpview_image_view table { background-color: <?php echo $this->options->optionsColors['wpview_image_table_bg_color']; ?>; }
            .wpview_image_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_image_table_border_color']; ?>; }
            .wpview_image_view table strong { color: <?php echo $this->options->optionsColors['wpview_image_table_title_color']; ?>; }
            .wpview_audio_player strong, .wpview_wp_default strong { color: <?php echo $this->options->optionsColors['wpview_audio_player_title_color']; ?> !important; }
            .wpview_audio_only_link a { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?> !important; }
            .audio_link_to_download_field_label:before { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?> !important; }
            .wpview_audio_only_link strong { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_title_color']; ?> !important; }
            .wpview_audio_only_link a:active { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?>; }
            .wpview_audio_only_link a:visited { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color']; ?>; }
            .wpview_audio_only_link a:hover { color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_audio_download_link_text_color_hover']; ?> !important; }
            .wpview_audio_view table { background-color: <?php echo $this->options->optionsColors['wpview_audio_table_bg_color']; ?>; }
            .wpview_audio_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_audio_table_border_color']; ?>; }
            .wpview_audio_view table strong { color: <?php echo $this->options->optionsColors['wpview_audio_table_title_color']; ?>; }
            .wpview_video_wrap strong { color: <?php echo $this->options->optionsColors['wpview_video_player_title_color']; ?> !important; }
            .wpview_video_only_link a { color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?> !important; }
            .video_link_to_download_field_label:before { color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?> !important; }
            .wpview_video_only_link strong { color: <?php echo $this->options->optionsColors['wpview_video_download_link_title_color']; ?> !important; }
            .wpview_video_only_link a:active { color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?>; }
            .wpview_video_only_link a:visited { color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color']; ?>; }
            .wpview_video_only_link a:hover { color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_video_download_link_text_color_hover']; ?> !important; }
            .wpview_video_view table { background-color: <?php echo $this->options->optionsColors['wpview_video_table_bg_color']; ?>; }
            .wpview_video_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_video_table_border_color']; ?>; }
            .wpview_video_view table strong { color: <?php echo $this->options->optionsColors['wpview_video_table_title_color']; ?>; }
            .wpview_file a { color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?> !important; }
            .file_link_to_download_field_label:before { color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?> !important; }
            .wpview_file strong { color: <?php echo $this->options->optionsColors['wpview_file_title_color']; ?> !important; }
            .wpview_file a:active { color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?>; }
            .wpview_file a:visited { color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_text_color']; ?>; }
            .wpview_file a:hover { color: <?php echo $this->options->optionsColors['wpview_file_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_text_color_hover']; ?> !important; }
            .wpview_file_view table { background-color: <?php echo $this->options->optionsColors['wpview_file_table_bg_color']; ?>; }
            .wpview_file_view table a { color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?>; }
            .wpview_file_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_file_table_border_color']; ?>; }
            .wpview_file_view table strong { color: <?php echo $this->options->optionsColors['wpview_file_table_title_color']; ?>; }
            .wpview_file_view table a:active { color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?> !important; }
            .wpview_file_view table a:visited { color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_table_text_color']; ?> !important; }
            .wpview_file_view table a:hover { color: <?php echo $this->options->optionsColors['wpview_file_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_file_table_text_color_hover']; ?> !important; }
            .wpview_category_parent a { color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; }
            .wpview_category:before { color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; }
            .wpview_category_parent strong { color: <?php echo $this->options->optionsColors['wpview_category_title_color']; ?>; }
            .wpview_category_parent a:active { color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; }
            .wpview_category_parent a:visited { color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_text_color']; ?>; }
            .wpview_category_parent a:hover { color: <?php echo $this->options->optionsColors['wpview_category_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_text_color_hover']; ?> !important; }
            .wpview_category_view table { background-color: <?php echo $this->options->optionsColors['wpview_category_table_bg_color']; ?>; }
            .wpview_category_view table a { color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?>; }
            .wpview_category_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_category_table_border_color']; ?>; }
            .wpview_category_view table strong { color: <?php echo $this->options->optionsColors['wpview_category_table_title_color']; ?>; }
            .wpview_category_view table a:active { color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?> !important; }
            .wpview_category_view table a:visited { color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_table_text_color']; ?> !important; }
            .wpview_category_view table a:hover { color: <?php echo $this->options->optionsColors['wpview_category_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_table_text_color_hover']; ?> !important; }
            .wpview_category_view ul { color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?>; }
            .wpview_category_view ul a { color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?>; }
            .wpview_category_view ul strong { color: <?php echo $this->options->optionsColors['wpview_category_ul_title_color']; ?>; }
            .wpview_category_view ul a:active { color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?> !important; }
            .wpview_category_view ul a:visited { color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color']; ?> !important; }
            .wpview_category_view ul a:hover { color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ul_text_color_hover']; ?> !important; }
            .wpview_category_view ol { color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?>; }
            .wpview_category_view ol a { color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?>; }
            .wpview_category_view ol strong { color: <?php echo $this->options->optionsColors['wpview_category_ol_title_color']; ?>; }
            .wpview_category_view ol a:active { color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?> !important; }
            .wpview_category_view ol a:visited { color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color']; ?> !important; }
            .wpview_category_view ol a:hover { color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_category_ol_text_color_hover']; ?> !important; }
            .wpview_tag_parent a { color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; }
            .wpview_tag:before { color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; }
            .wpview_tag_parent strong { color: <?php echo $this->options->optionsColors['wpview_tag_title_color']; ?>; }
            .wpview_tag_parent a:active { color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; }
            .wpview_tag_parent a:visited { color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_text_color']; ?>; }
            .wpview_tag_parent a:hover { color: <?php echo $this->options->optionsColors['wpview_tag_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_text_color_hover']; ?> !important; }
            .wpview_tag_view table { background-color: <?php echo $this->options->optionsColors['wpview_tag_table_bg_color']; ?>; }
            .wpview_tag_view table a { color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?>; }
            .wpview_tag_view table td { border:none; border-bottom: 1px solid <?php echo $this->options->optionsColors['wpview_tag_table_border_color']; ?>; }
            .wpview_tag_view table strong { color: <?php echo $this->options->optionsColors['wpview_tag_table_title_color']; ?>; }
            .wpview_tag_view table a:active { color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?> !important; }
            .wpview_tag_view table a:visited { color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color']; ?> !important; }
            .wpview_tag_view table a:hover { color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_table_text_color_hover']; ?> !important; }
            .wpview_tag_view ul { color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?>; }
            .wpview_tag_view ul a { color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?>; }
            .wpview_tag_view ul strong { color: <?php echo $this->options->optionsColors['wpview_tag_ul_title_color']; ?>; }
            .wpview_tag_view ul a:active { color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?> !important; }
            .wpview_tag_view ul a:visited { color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color']; ?> !important; }
            .wpview_tag_view ul a:hover { color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ul_text_color_hover']; ?> !important; }
            .wpview_tag_view ol { color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?>; }
            .wpview_tag_view ol a { color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?>; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?>; }
            .wpview_tag_view ol strong { color: <?php echo $this->options->optionsColors['wpview_tag_ol_title_color']; ?>; }
            .wpview_tag_view ol a:active { color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?> !important; }
            .wpview_tag_view ol a:visited { color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color']; ?> !important; }
            .wpview_tag_view ol a:hover { color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color_hover']; ?> !important; border-bottom-color: <?php echo $this->options->optionsColors['wpview_tag_ol_text_color_hover']; ?> !important; }
            .wpview_map_view strong { color: <?php echo $this->options->optionsColors['wpview_google_map_title_color']; ?>; }
            <?php do_action('wpview_dynamic_css'); ?>
        </style>
        <?php
    }

}

new wpView();
