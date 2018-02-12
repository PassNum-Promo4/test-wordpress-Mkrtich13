<?php

namespace wpView\plugins;

class ACFPlugin extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    public function getFields($postId, $fieldType = 'all', $fieldTitle = '') {
        global $wpdb;
        $fieldTitleSql = $fieldTitle != '' ? " AND a.`meta_key` = '$fieldTitle'" : '';
        $sql = "SELECT a.`meta_key` AS label, a.`meta_value` AS cust_val, b.`meta_value` AS field_name FROM `{$wpdb->prefix}postmeta` a, `{$wpdb->prefix}postmeta` b WHERE b.`meta_key` = CONCAT('_', a.`meta_key`) AND a.`meta_value` <> '' AND a.post_id = b.`post_id` AND a.post_id = $postId $fieldTitleSql";
        $acf_values = $wpdb->get_results($sql, ARRAY_A);
        $custom_fields = array();
        if (count($acf_values)) {
            $map_id = 0;
            $options = get_option(WPVIEW_VIEWS);
            $in_array = array();

            foreach ($acf_values as $value) {
                if ('null' != $value['cust_val'] && '' != trim($value['cust_val'])) {
                    $sql = "SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE meta_key = '{$value['field_name']}'";
                    $field_param = $wpdb->get_var($sql);
                    if ($field_param) {
                        $field_param = unserialize($field_param);
                        $sql = "SELECT post_id FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = '{$field_param['key']}'";
                        $group_id = $wpdb->get_var($sql);
                        if ($group_id) {

                            switch ($field_param['type']) {
                                case 'file':
                                    if (strstr(get_post_mime_type(intval($value['cust_val'])), 'audio')) {
                                        $field_param['type'] = 'audio';
                                    } elseif (strstr(get_post_mime_type(intval($value['cust_val'])), 'video')) {
                                        $field_param['type'] = 'video';
                                    }
                                    break;
                                case 'relationship':
                                    $field_param['type'] = 'relation';
                                    break;
                                case 'date_picker':
                                    $field_param['type'] = 'date';
                                    break;
                                case 'color_picker':
                                    $field_param['type'] = 'color';
                                    break;
                                case 'taxonomy':
                                    $field_param['type'] = $field_param['taxonomy'];
                                    break;
                            }
                            if (($fieldType == 'all' || $fieldType == $field_param['type']) && $field_param['type'] != 'password') {
                                $i = $j = $group_id . '.' . $field_param['order_no'];
                                if ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$field_param['type']]['group']) && $options['views_for_all'][$field_param['type']]['group'] === 'yes') {
                                    if (key_exists($field_param['type'], $in_array)) {
                                        if (floatval($in_array[$field_param['type']]) > floatval($i)) {
                                            $custom_fields[$i][$field_param['type']] = $custom_fields[$in_array[$field_param['type']]][$field_param['type']];
                                            unset($custom_fields[$in_array[$field_param['type']]]);
                                            $in_array[$field_param['type']] = $i;
                                        } else {
                                            $i = $in_array[$field_param['type']];
                                        }
                                    } else {
                                        $in_array[$field_param['type']] = $i;
                                    }
                                }
                                $custom_fields[$i][$field_param['type']][$j]['title'] = $field_param['label'];
                                switch ($field_param['type']) {
                                    case 'google_map':
                                        $cust_val = unserialize($value['cust_val']);
                                        $field_param['zoom'] = $field_param['zoom'] ? $field_param['zoom'] : '14';
                                        $custom_fields[$i][$field_param['type']][$j]['value'] = array('id' => $map_id, 'post_id' => $postId, 'field_id' => $postId . '-' . $j, 'lat' => $cust_val['lat'], 'lng' => $cust_val['lng'], 'zoom' => $field_param['zoom']);
                                        $map_id++;
                                        break;
                                    case 'date':
                                        $cust_val = preg_replace('#(\d{4})(\d{2})(\d{2})#is', '$1-$2-$3', $value['cust_val']);
                                        $custom_fields[$i][$field_param['type']][$j]['value'] = $cust_val;
                                        break;
                                    case 'true_false':
                                        $custom_fields[$i][$field_param['type']][$j]['value'] = $field_param['label'];
                                        break;
                                    default:
                                        $cust_val = maybe_unserialize($value['cust_val']);
                                        $custom_fields[$i][$field_param['type']][$j]['value'] = $cust_val;
                                }
                                if (count($custom_fields[$i][$field_param['type']]) > 1) {
                                    ksort($custom_fields[$i][$field_param['type']]);
                                }
                            }
                        }
                    }
                }
            }
        }
        ksort($custom_fields);
        return array_values($custom_fields);
    }

    protected function initFieldTypes() {
        $fields['Text'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Textarea'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Number'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Email'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['WYSIWYG'] = array('with_table' => __('Table', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Date'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'left_border' => __('Left Border Box', 'wpview'));
        $fields['Color'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'only_color' => __('Color Box', 'wpview'), 'with_code' => __('Color Box with Code', 'wpview'));
        $fields['Select'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Checkbox'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['True/False'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Hyperlink'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'hyperlink_icon' => __('Hyperlink Icon', 'wpview'));
        $fields['User'] = array('with_table' => __('Table', 'wpview'), 'with_avatar' => __('With Avatar', 'wpview'), 'without_avatar' => __('Without Avatar', 'wpview'));
        $fields['Relation'] = array('with_table' => __('Table', 'wpview'), 'with_thumbnail' => __('With Thumbnail', 'wpview'), 'without_thumbnail' => __('Without Thumbnail', 'wpview'));
        $fields['Page Link'] = array('with_table' => __('Table', 'wpview'), 'with_thumbnail' => __('With Thumbnail', 'wpview'), 'without_thumbnail' => __('Without Thumbnail', 'wpview'));
        $fields['Post Object'] = array('with_table' => __('Table', 'wpview'), 'with_thumbnail' => __('With Thumbnail', 'wpview'), 'without_thumbnail' => __('Without Thumbnail', 'wpview'));
        $fields['Image'] = array('with_table' => __('Table', 'wpview'), 'image_thumbnail' => __('Image Thumbnail', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Audio'] = array('with_table' => __('Table', 'wpview'), 'audio_tag' => __('HTML5 Audio Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Video'] = array('with_table' => __('Table', 'wpview'), 'video_tag' => __('HTML5 Video Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['File'] = array('with_table' => __('Table', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Category'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_separator' => __('Comma separated', 'wpview'), 'without_separator' => __('Without Separator', 'wpview'));
        $fields['Post Tag'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_separator' => __('Comma separated', 'wpview'), 'without_separator' => __('Without Separator', 'wpview'));
        $fields['Google Map'] = array('google_map' => __('Google Map', 'wpview'));
        $this->setFieldTypes($fields);
    }

}
