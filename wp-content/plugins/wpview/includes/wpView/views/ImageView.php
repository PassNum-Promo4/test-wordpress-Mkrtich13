<?php

namespace wpView\views;

class ImageView extends View {

    private static $instance;

    protected function __construct() {
        parent::__construct();
    }

    static public function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function draw() {
        $data = $this->getData();
        $view = $this->getView();
        $structure = '';
        if ($data) {
            $options = get_option(WPVIEW_VIEWS);
            global $wpdb;
            if ($view === 'all_together_in_table') {
                foreach ($data['value'] as $key => $single_data) {
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_name = $wpdb->get_var("SELECT `post_title`  FROM `" . $wpdb->prefix . "posts` WHERE `ID` = " . $single_data['value']);
                    } else {
                        $get_file_name = basename($single_data['value']);
                    }
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_url = wp_get_attachment_image($single_data['value'], array(80, 80), false, array('alt' => esc_attr($get_file_name), 'title' => esc_attr($get_file_name)));
                    } else {
                        $get_file_url = $single_data['value'];
                    }
                    $structure .= '<tr class="wpview_image_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    if (!strstr($single_data['value'], 'http')) {
                        $structure .= $get_file_url;
                    } else {
                        $structure .= '<img width="80" src="' . esc_url($get_file_url) . '" alt="' . esc_attr($get_file_name) . '" title="' . esc_attr($get_file_name) . '" />';
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'image_thumbnail') {
                $structure .= '<div class="wpview_image_view">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_name = $wpdb->get_var("SELECT `post_title`  FROM `" . $wpdb->prefix . "posts` WHERE `ID` = " . $single_data['value']);
                    } else {
                        $get_file_name = basename($single_data['value']);
                    }
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_url = wp_get_attachment_image($single_data['value'], array(80, 80), false, array('alt' => esc_attr($get_file_name), 'title' => esc_attr($get_file_name)));
                    } else {
                        $get_file_url = $single_data['value'];
                    }
                    $structure .= '<div class="wpview_image_image_thumbnail">';
                    $structure .= '<div class="wpview_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</div>';
                    if (!strstr($single_data['value'], 'http')) {
                        $structure .= $get_file_url;
                    } else {
                        $structure .= '<img width="80" src="' . esc_url($get_file_url) . '" alt="' . esc_attr($get_file_name) . '" title="' . esc_attr($get_file_name) . '" />';
                    }
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'only_link') {
                $structure .= '<div class="wpview_image_view">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_url = wp_get_attachment_url($single_data['value']);
                    } else {
                        $get_file_url = $single_data['value'];
                    }
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_name = $wpdb->get_var("SELECT `post_title`  FROM `" . $wpdb->prefix . "posts` WHERE `ID` = " . $single_data['value']);
                    } else {
                        $get_file_name = basename($single_data['value']);
                    }
                    $structure .= '<div class="wpview_image_only_link">';
                    $structure .= '<span class="wpview_field_label image_link_to_download_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</span>';
                    $structure .= '<a href="' . esc_url($get_file_url) . '" title="' . esc_attr($get_file_name) . '" download>';
                    $structure .= esc_html($get_file_name);
                    $structure .= '</a>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_image_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_name = $wpdb->get_var("SELECT `post_title`  FROM `" . $wpdb->prefix . "posts` WHERE `ID` = " . $single_data['value']);
                    } else {
                        $get_file_name = basename($single_data['value']);
                    }
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_url = wp_get_attachment_image($single_data['value'], array(80, 80), false, array('alt' => esc_attr($get_file_name), 'title' => esc_attr($get_file_name)));
                    } else {
                        $get_file_url = $single_data['value'];
                    }
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>';
                    if (!strstr($single_data['value'], 'http')) {
                        $structure .= $get_file_url;
                    } else {
                        $structure .= '<img width="80" src="' . esc_url($get_file_url) . '" alt="' . esc_attr($get_file_name) . '" title="' . esc_attr($get_file_name) . '" />';
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
