<?php

namespace wpView\views;

class UserView extends View {

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
            if ($view === 'all_together_in_table') {
                foreach ($data['value'] as $key => $single_data) {
                    $get_user = get_userdata($single_data['value']);
                    $get_user_link = get_author_posts_url($single_data['value']);
                    $structure .= '<tr class="wpview_user_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    $structure .= '<a href="' . esc_url($get_user_link) . '" title="' . esc_attr($get_user->display_name) . '">';
                    $structure .= esc_html($get_user->display_name);
                    $structure .= '</a>';
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'with_avatar') {
                $structure .= '<div class="wpview_left wpview_user_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $get_user = get_userdata($single_data['value']);
                    $get_avatar = get_avatar_url($single_data['value'], array('size' => 50));
                    $get_user_link = get_author_posts_url($single_data['value']);
                    $structure .= '<div class="wpview_user wpview_column">';
                    $structure .= '<div class="wpview_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . $single_data['title'] . ':</strong> ';
                    }
                    $structure .= '</div>';
                    $structure .= '<a href="' . esc_url($get_user_link) . '" title="' . esc_attr($get_user->display_name) . '">';
                    $structure .= '<img src="' . esc_url($get_avatar) . '" />';
                    $structure .= '<span>' . esc_html($get_user->display_name) . '</span>';
                    $structure .= '</a>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'without_avatar') {
                $structure .= '<div class="wpview_left wpview_user_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $get_user = get_userdata($single_data['value']);
                    $get_user_link = get_author_posts_url($single_data['value']);
                    $structure .= '<div class="wpview_user wpview_user_without_avatar">';
                    $structure .= '<div class="wpview_field_label user_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':&nbsp;</strong>';
                    }
                    $structure .= '</div>';
                    $structure .= '<a href="' . esc_url($get_user_link) . '" title="' . esc_attr($get_user->display_name) . '">';
                    $structure .= esc_html($get_user->display_name);
                    $structure .= '</a>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_user_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    $get_user = get_userdata($single_data['value']);
                    $get_user_link = get_author_posts_url($single_data['value']);
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>';
                    $structure .= '<a href="' . esc_url($get_user_link) . '" title="' . esc_attr($get_user->display_name) . '">';
                    $structure .= esc_html($get_user->display_name);
                    $structure .= '</a>';
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
