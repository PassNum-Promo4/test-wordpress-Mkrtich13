<?php

namespace wpView\views;

class ColorView extends View {

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
                    $structure .= '<tr class="wpview_color_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td><span style="background: ' . esc_attr($single_data['value']) . ';"></span></td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'with_code') {
                $structure .= '<div class="wpview_left wpview_color_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class="wpview_color">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ':</strong> ';
                    }
                    $structure .= '<span style="background: ' . esc_attr($single_data['value']) . ';"></span>';
                    $structure .= '<i>' . esc_html($single_data['value']) . '</i>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'only_color') {
                $structure .= '<div class="wpview_left wpview_color_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class="wpview_color">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ':</strong> ';
                    }
                    $structure .= '<span style="background: ' . esc_attr($single_data['value']) . ';"></span>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_color_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<tr class="wpview_color_tr">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td><span style="background: ' . esc_attr($single_data['value']) . ';"></span></td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            } else if ($view === 'with_ul') {
                $structure .= '<div class="wpview_color_view">';
                $structure .= '<ul class="wpview_ul">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li class="wpview_color_li">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    $structure .= '<span style="background: ' . esc_attr($single_data['value']) . ';"></span>';
                    $structure .= '</li>';
                }
                $structure .= '</ul>';
                $structure .= '</div>';
            } else if ($view === 'with_ol') {
                $structure .= '<div class="wpview_color_view">';
                $structure .= '<ol class="wpview_ol">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li class="wpview_color_li">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    $structure .= '<span style="background: ' . esc_attr($single_data['value']) . ';"></span>';
                    $structure .= '</li>';
                }
                $structure .= '</ol>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
