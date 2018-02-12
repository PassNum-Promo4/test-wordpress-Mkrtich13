<?php

namespace wpView\plugins;

class TTPlugin extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    public function getFields($postId, $fieldType = 'all', $fieldTitle = '') {
        global $wpdb;
        $options = get_option(WPVIEW_VIEWS);
        $array = array();
        $custom_fields = array();
        $opt = get_option('wpcf-fields');
        if ($opt) {
            $post_ids = $wpdb->get_results("SELECT `meta_value` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = '_wp_types_group_fields' ORDER BY `post_id`", ARRAY_A);
            foreach ($post_ids as $meta_values) {
                foreach (explode(",", $meta_values['meta_value']) as $name) {
                    foreach ($opt as $value) {
                        if ($value['slug'] == $name) {
                            $meta_key = $value['meta_key'];
                            $post_ids = $wpdb->get_results("SELECT `meta_value` FROM `" . $wpdb->prefix . "postmeta` WHERE `post_id` = '$postId' AND `meta_key` = '$meta_key'", ARRAY_A);
                            foreach ($post_ids as $val) {
                                if ($value['type'] == "colorpicker") {
                                    $value['type'] = "color";
                                } elseif ($value['type'] == "url") {
                                    $value['type'] = "hyperlink";
                                } elseif ($value['type'] == "textfield") {
                                    $value['type'] = "text";
                                } elseif ($value['type'] == "numeric") {
                                    $value['type'] = "number";
                                } elseif ($value['type'] == "selectbox") {
                                    $value['type'] = "select";
                                }
                                if ($fieldType == 'all' || $fieldType == $value['type'] && ($fieldTitle === '' || $fieldTitle === $value['name'])) {
                                    if ($meta_key) {
                                        if ($options['wpview_choose_views'] === 'display_apart' && isset($options['views_for_all'][$value['type']]['group']) && $options['views_for_all'][$value['type']]['group'] === 'yes') {
                                            if (isset($array[$value['type']])) {
                                                $i = $array[$value['type']];
                                            } else {
                                                $i = count($custom_fields);
                                                $array[$value['type']] = $i;
                                            }
                                        } else {
                                            $i = count($custom_fields);
                                        }
                                        if (isset($custom_fields[$i][$value['type']])) {
                                            $j = count($custom_fields[$i][$value['type']]);
                                        } else {
                                            $j = 0;
                                        }
                                        if ($value['type'] == 'select') {
                                            $custom_fields[$i][$value['type']][$j]['title'] = $value['name'];
                                            $selectBoxValue = $val["meta_value"];
                                            foreach ($value['data']['options'] as $select) {
                                                if ($select != "no-default" && $select['value'] == $selectBoxValue) {
                                                    $custom_fields[$i][$value['type']][$j]['value'] = $select['title'];
                                                }
                                            }
                                        } elseif ($value['type'] == 'checkboxes' || $value['type'] == 'radio') {
                                            $custom_fields[$i][$value['type']][$j]['title'] = $value['name'];
                                            $checkboxesValue = unserialize($val["meta_value"]);
                                            if (is_array($checkboxesValue)) {
                                                foreach ($checkboxesValue as $k => $v) {
                                                    if ($v[0] == $value['data']['options'][$k]['set_value']) {
                                                        $custom_fields[$i][$value['type']][$j]['value'][] = $value['data']['options'][$k]['title'];
                                                    }
                                                }
                                            } else {
                                                foreach ($value['data']['options'] as $k => $v) {
                                                    if ($k !== 'default') {
                                                        $custom_fields[$i][$value['type']][$j]['value'] = $value['data']['options'][$k]['title'];
                                                    }
                                                }
                                            }
                                        } else if ($value['type'] == 'checkbox') {
                                            if ($val["meta_value"]) {
                                                $custom_fields[$i][$value['type']][$j]['title'] = $value['name'];
                                                $custom_fields[$i][$value['type']][$j]['value'] = $val["meta_value"];
                                            }
                                        } else {
                                            $custom_fields[$i][$value['type']][$j]['title'] = $value['name'];
                                            if ($value['type'] == "date") {
                                                $custom_fields[$i][$value['type']][$j]['value'] = date("F j, Y", intval($val["meta_value"]));
                                            } else {
                                                $custom_fields[$i][$value['type']][$j]['value'] = $val["meta_value"];
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
        $fields['Number'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Email'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['Phone'] = array('left_border' => __('Left Border Box', 'wpview'), 'quote_box' => __('Quote Box', 'wpview'), 'with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'));
        $fields['WYSIWYG'] = array('with_table' => __('Table', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Date'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'left_border' => __('Left Border Box', 'wpview'));
        $fields['Color'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'only_color' => __('Color Box', 'wpview'), 'with_code' => __('Color Box with Code', 'wpview'));
        $fields['Select'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'bordered_box' => __('Bordered Box', 'wpview'));
        $fields['Checkbox'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Checkboxes'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Radio'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'with_icons' => __('With checkmark icon', 'wpview'));
        $fields['Hyperlink'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'hyperlink_icon' => __('Hyperlink Icon', 'wpview'));
        $fields['Embed'] = array('with_table' => __('Table', 'wpview'), 'with_ul' => __('Unordered List', 'wpview'), 'with_ol' => __('Ordered List', 'wpview'), 'hyperlink_icon' => __('Hyperlink Icon', 'wpview'));
        $fields['Image'] = array('with_table' => __('Table', 'wpview'), 'image_thumbnail' => __('Image Thumbnail', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Audio'] = array('with_table' => __('Table', 'wpview'), 'audio_tag' => __('HTML5 Audio Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['Video'] = array('with_table' => __('Table', 'wpview'), 'video_tag' => __('HTML5 Video Player', 'wpview'), 'wordpress_default' => __('WordPress Player', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $fields['File'] = array('with_table' => __('Table', 'wpview'), 'only_link' => __('Download Link', 'wpview'));
        $this->setFieldTypes($fields);
    }

}
