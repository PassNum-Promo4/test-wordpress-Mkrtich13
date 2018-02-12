<?php

namespace wpView\plugins;

class CFSPlugin extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    public function getFields($postId, $fieldType = 'all', $fieldTitle = '') {
        global $wpdb;
        $options = get_option(WPVIEW_VIEWS);
        $get_cfs_post_id = $wpdb->get_results("SELECT `ID` FROM `" . $wpdb->prefix . "posts` WHERE `post_type` = 'cfs' ORDER BY `ID`", ARRAY_A);
        $custom_fields = array();
        $in_array = array();
        if (!empty($get_cfs_post_id)) {
            $cfs_values = $wpdb->get_results("SELECT `field_id`, `meta_id` FROM `" . $wpdb->prefix . "cfs_values` WHERE `post_id` = " . $postId, ARRAY_A);
            foreach ($get_cfs_post_id as $cfs_post_id) {
                $cfs_fields = $wpdb->get_results("SELECT `meta_value` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = 'cfs_fields' AND `post_id` = " . $cfs_post_id['ID'], ARRAY_A);
                if ($cfs_fields) {
                    foreach ($cfs_fields as $cfs_field) {
                        $fields = maybe_unserialize($cfs_field['meta_value']);
                        foreach ($fields as $field) {
                            foreach ($cfs_values as $cfs_value) {
                                if ($field['id'] == $cfs_value['field_id']) {
                                    if ($fieldType == 'image' || $fieldType == 'audio' || $fieldType == 'video' || $fieldType == 'file') {
                                        $type = 'file';
                                    } else if ($fieldType == 'relation') {
                                        $type = 'relationship';
                                    } else {
                                        $type = $fieldType;
                                    }
                                    if ($fieldType === 'all' || $field['type'] === $type && ($fieldTitle === '' || $fieldTitle === $field['label'])) {
                                        if ($field['name']) {
                                            $value = $wpdb->get_var("SELECT `meta_value` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_id` = " . $cfs_value['meta_id'] . " AND `post_id` = " . $postId);
                                            if (!is_null($value) && $value) {
                                                if ($field['type'] === 'file') {
                                                    if (strstr(get_post_mime_type(intval($value)), 'image')) {
                                                        $field['type'] = 'image';
                                                    } else if (strstr(get_post_mime_type(intval($value)), 'audio')) {
                                                        $field['type'] = 'audio';
                                                    } else if (strstr(get_post_mime_type(intval($value)), 'video')) {
                                                        $field['type'] = 'video';
                                                    }
                                                } else if ($field['type'] === 'relationship') {
                                                    $field['type'] = 'relation';
                                                }
                                                if ($fieldType === 'all' || $field['type'] === $fieldType) {
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
                                                    if ($field['type'] == 'true_false' && $value != 0 && $field['options']['message'] != '') {
                                                        $custom_fields[$i][$field['type']][$j]['value'] = $field['options']['message'];
                                                    } else if ($field['type'] !== 'true_false') {
                                                        $custom_fields[$i][$field['type']][$j]['value'] = $value;
                                                    }
                                                    $custom_fields[$i][$field['type']][$j]['title'] = $field['label'];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
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
        $fields['True/False'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
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
