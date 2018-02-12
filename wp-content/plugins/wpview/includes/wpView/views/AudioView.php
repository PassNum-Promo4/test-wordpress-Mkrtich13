<?php

namespace wpView\views;

class AudioView extends View {

    private static $instance;

    protected function __construct() {
        parent::__construct();
        add_action('wp_enqueue_scripts', array($this, 'audioScript'), 19);
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
                        $get_file_url = wp_get_attachment_url($single_data['value']);
                    } else {
                        $get_file_url = $single_data['value'];
                    }
                    if (!strstr($single_data['value'], 'http')) {
                        $get_file_name = $wpdb->get_var("SELECT `post_title`  FROM `" . $wpdb->prefix . "posts` WHERE `ID` = " . $single_data['value']);
                    } else {
                        $get_file_name = basename($single_data['value']);
                    }
                    $structure .= '<tr class="wpview_audio_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    $structure .= '<audio class="wpview_audio_audio_tag" controls title="' . esc_attr($get_file_name) . '">';
                    $structure .= '<source src="' . esc_url($get_file_url) . '">';
                    $structure .= 'Your browser does not support the audio tag.';
                    $structure .= '</audio>';
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'audio_tag') {
                $structure .= '<div class="wpview_left wpview_audio_view">';
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
                    $structure .= '<div class="wpview_audio_wrap wpview_audio_player">';
                    $structure .= '<div class = "wpview_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</div>';
                    $structure .= '<audio class="wpview_audio_audio_tag" controls title="' . esc_attr($get_file_name) . '">';
                    $structure .= '<source src="' . esc_url($get_file_url) . '">';
                    $structure .= 'Your browser does not support the audio tag.';
                    $structure .= '</audio>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'wordpress_default') {
                $structure .= '<div class="wpview_left wpview_audio_view">';
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
                    $attr = array('src' => esc_url($get_file_url));
                    $structure .= '<div class="wpview_audio_wrap wpview_wp_default">';
                    $structure .= '<div class="wpview_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</div>';
                    $structure .= '<div class="wpview_wp_audio_shortcode">';
                    $structure .= wp_audio_shortcode($attr);
                    $structure .= '</div>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'only_link') {
                $structure .= '<div class="wpview_audio_view">';
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
                    $structure .= '<div class="wpview_audio_only_link">';
                    $structure .= '<span class="wpview_field_label audio_link_to_download_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':</strong> ';
                    }
                    $structure .= '</span>';
                    $structure .= '<a href="' . esc_url($get_file_url) . '" title="' . esc_attr($get_file_name) . '" download>';
                    $structure .= esc_html($get_file_name);
                    $structure .= '</a>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_audio_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
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
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>';
                    $structure .= '<audio class="wpview_audio_audio_tag" controls title="' . esc_attr($get_file_name) . '">';
                    $structure .= '<source src="' . esc_url($get_file_url) . '">';
                    $structure .= 'Your browser does not support the audio tag.';
                    $structure .= '</audio>';
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

    public function audioScript() {
        $options = get_option(WPVIEW_VIEWS);
        if (isset($options['views_for_all']['audio']['view']) && $options['views_for_all']['audio']['view'] !== 'none') {
            wp_register_script('wpview_audio_video', plugins_url('wpview/assets/js/audio_video.js'), array('jquery'), null, true);
            wp_enqueue_script('wpview_audio_video');
        }
    }

}
