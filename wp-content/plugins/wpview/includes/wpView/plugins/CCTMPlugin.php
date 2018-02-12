<?php

namespace wpView\plugins;

class CCTMPlugin extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    public function getFields($postId, $fieldType = 'all', $fieldTitle = '') {
        global $wpdb;
        $options = get_option(WPVIEW_VIEWS);
        $custom_fields = array();
        $in_array = array();
        $option = get_option('cctm_data');
        foreach ($option['custom_field_defs'] as $field) {
            if ($field['type'] != 'relationmeta' || $field['type'] != 'directory')
                $fields[] = array(
                    'title' => $field['label'],
                    'meta_key' => $field['name'],
                    'type' => $field['type']);
            $sql = "SELECT `meta_value` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = '{$field['name']}' AND post_id = $postId AND `meta_value` <> '' ";
            if ($values = $wpdb->get_results($sql)) {
                foreach ($values as $val) {
                    $multiselect = '';
                    if ($field['type'] == 'colorselector') {
                        $field['type'] = 'color';
                        $val->meta_value = trim($val->meta_value, '[""]');
                    } else if ($field['type'] == 'dropdown' || $field['type'] === 'multiselect') {
                        if ($field['type'] === 'multiselect') {
                            $multiselect = $val->meta_value;
                        }
                        $field['type'] = 'select';
                    } else if ($field['type'] == 'media') {
                        if (strstr(get_post_mime_type(intval($val->meta_value)), 'image')) {
                            $field['type'] = 'image';
                        } else if (strstr(get_post_mime_type(intval($val->meta_value)), 'audio')) {
                            $field['type'] = 'audio';
                        } else if (strstr(get_post_mime_type(intval($val->meta_value)), 'video')) {
                            $field['type'] = 'video';
                        }
                    }
                    if ($fieldType == 'all' || $fieldType == $field['type'] && ($fieldTitle === '' || $fieldTitle === $field['label'])) {
                        if ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$field['type']]['group']) && $options['views_for_all'][$field['type']]['group'] === 'yes') {
                            if (isset($in_array[$field['type']])) {
                                $i = $in_array[$field['type']];
                            } else {
                                $i = count($custom_fields);
                                $in_array[$field['type']] = $i;
                            }
                        } else {
                            $i = count($custom_fields);
                        }
                        if (isset($custom_fields[$i][$field['type']])) {
                            $j = count($custom_fields[$i][$field['type']]);
                        } else {
                            $j = 0;
                        }
                        $custom_fields[$i][$field['type']][$j]['title'] = $field['label'];
                        if ($multiselect !== '') {
                            $multiselect = trim($multiselect, '[]');
                            if (preg_match_all('#"([^"]+)"#is', $multiselect, $matches)) {
                                $custom_fields[$i][$field['type']][$j]['value'] = $matches[1];
                            }
                        } else {
                            $custom_fields[$i][$field['type']][$j]['value'] = $val->meta_value;
                        }
                    }
                }
            }
        }
        return $custom_fields;
    }

    protected function initFieldTypes() {
        $fields['Text'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Textarea'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['WYSIWYG'] = array('with_table' => __('Table', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Date'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'left_border' => __('Left Border Box', 'wpview'));
        $fields['Color'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'only_color' => __('Color Box', 'wpview'), 'with_code' => __('Color Box with Code', 'wpview'));
        $fields['Select'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Checkbox'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Hyperlink'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'hyperlink_icon' => __('Hyperlink Icon', 'wpview'));
        $fields['User'] = array('with_table' => __('Table', 'wpview'), 'with_avatar' => __('With Avatar', 'wpview'), 'without_avatar' => __('Without Avatar', 'wpview'));
        $fields['Relation'] = array('with_table' => __('Table', 'wpview'), 'with_thumbnail' => __('With Thumbnail', 'wpview'), 'without_thumbnail' => __('Without Thumbnail', 'wpview'));
        $fields['Image'] = array('with_table' => __('Table', 'wpview'), 'image_thumbnail' => __('Image Thumbnail', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Audio'] = array('with_table' => __('Table', 'wpview'), 'audio_tag' => __('HTML5 Audio Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Video'] = array('with_table' => __('Table', 'wpview'), 'video_tag' => __('HTML5 Video Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['File'] = array('with_table' => __('Table', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $this->setFieldTypes($fields);
    }

}
