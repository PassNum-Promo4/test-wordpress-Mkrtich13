<?php

namespace wpView\plugins;

class WCKPlugin extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    public function getFields($postId, $fieldType = 'all', $fieldTitle = '') {
        global $wpdb;
        $options = get_option(WPVIEW_VIEWS);
        $post_custom_fields = array();
        $in_array = array();
        $query = "SELECT post_id, meta_value FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key = 'wck_cfc_args'";
        if ($results = $wpdb->get_results($query, ARRAY_A)) {
            foreach ($results as $result) {
                extract($result);
                $meta_name = unserialize($result['meta_value']);
                $meta_name = $meta_name[0]['meta-name'];
                $select_post_values = "SELECT meta_value FROM `wp_postmeta` WHERE meta_key = '" . $meta_name . "' AND post_id = " . $postId;
                if ($post_meta_values = $wpdb->get_results($select_post_values, ARRAY_A)) {
                    foreach ($post_meta_values as $post_meta_value) {
                        $field_values = unserialize($post_meta_value['meta_value']);
                        foreach ($field_values as $field_value) {
                            foreach ($field_value as $key => $value) {
                                $select_field_types = "SELECT meta_value FROM `wp_postmeta` WHERE meta_key = 'wck_cfc_fields' and post_id = " . $post_id;
                                if ($post_fields = $wpdb->get_results($select_field_types, ARRAY_A)) {
                                    foreach ($post_fields as $post_field) {
                                        $post_field = unserialize($post_field['meta_value']);
                                        foreach ($post_field as $k => $v) {
                                            if ($v['field-title'] != 'heading') {
                                                if ($key == rawurldecode(sanitize_title_with_dashes(remove_accents($v['field-title'])))) {
                                                    if ($v['field-type'] == 'colorpicker') {
                                                        $v['field-type'] = 'color';
                                                    } elseif ($v['field-type'] === 'upload') {
                                                        if (strstr(get_post_mime_type(intval($value)), 'image')) {
                                                            $v['field-type'] = 'image';
                                                        } else if (strstr(get_post_mime_type(intval($value)), 'audio')) {
                                                            $v['field-type'] = 'audio';
                                                        } else if (strstr(get_post_mime_type(intval($value)), 'video')) {
                                                            $v['field-type'] = 'video';
                                                        } else {
                                                            $v['field-type'] = 'file';
                                                        }
                                                    } elseif ($v['field-type'] === 'wysiwyg editor') {
                                                        $v['field-type'] = 'wysiwyg';
                                                    } elseif ($v['field-type'] === 'currency select') {
                                                        $v['field-type'] = 'currency';
                                                    } elseif ($v['field-type'] == 'timepicker') {
                                                        $v['field-type'] = 'time';
                                                    }
                                                    if ($fieldType === 'all' || $v['field-type'] === $fieldType && ($fieldTitle === '' || $fieldTitle === $v['field-title'])) {
                                                        if ($value !== '' && !is_null($value)) {
                                                            if ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$v['field-type']]['group']) && $options['views_for_all'][$v['field-type']]['group'] === 'yes') {
                                                                if (isset($in_array[$v['field-type']])) {
                                                                    $i = $in_array[$v['field-type']];
                                                                } else {
                                                                    $i = count($post_custom_fields);
                                                                    $in_array[$v['field-type']] = $i;
                                                                }
                                                            } else {
                                                                $i = count($post_custom_fields);
                                                            }
                                                            if (isset($post_custom_fields[$i][$v['field-type']])) {
                                                                $j = count($post_custom_fields[$i][$v['field-type']]);
                                                            } else {
                                                                $j = 0;
                                                            }
                                                            $post_custom_fields[$i][$v['field-type']][$j]['title'] = $v['field-title'];
                                                            $post_custom_fields[$i][$v['field-type']][$j]['value'] = $value;
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
            }
        }
        return $post_custom_fields;
    }

    protected function initFieldTypes() {
        $fields['Text'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Textarea'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Number'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Phone'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Currency'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['WYSIWYG'] = array('with_table' => __('Table', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Time'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'left_border' => __('Left Border Box', 'wpview'));
        $fields['Color'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'only_color' => __('Color Box', 'wpview'), 'with_code' => __('Color Box with Code', 'wpview'));
        $fields['Select'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Checkbox'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Radio'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Image'] = array('with_table' => __('Table', 'wpview'), 'image_thumbnail' => __('Image Thumbnail', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Audio'] = array('with_table' => __('Table', 'wpview'), 'audio_tag' => __('HTML5 Audio Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Video'] = array('with_table' => __('Table', 'wpview'), 'video_tag' => __('HTML5 Video Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['File'] = array('with_table' => __('Table', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $this->setFieldTypes($fields);
    }

}
