<?php

namespace wpView\views;

class TextView extends View {

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
                    $structure .= '<tr class="wpview_text_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>' . esc_html($single_data['value']) . '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'left_border') {
                $structure .= '<div class="wpview_column wpview_text_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class="wpview_text_left_border">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ':</strong> ';
                    }
                    $structure .= esc_html($single_data['value']);
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'quote_box') {
                $structure .= '<div class="wpview_column wpview_text_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class="wpview_textarea_quote_box">';
                    $structure .= '<div>';
                    $structure .= '<div class="wpview_text_quote_box_title">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ':</strong> ';
                    }
                    $structure .= '</div>';
                    $structure .= esc_html($single_data['value']);
                    $structure .= '</div>';
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_text_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>' . esc_html($single_data['value']) . '</td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            } else if ($view === 'with_ul') {
                $structure .= '<div class="wpview_text_view">';
                $structure .= '<ul class="wpview_ul">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    $structure .= esc_html($single_data['value']);
                    $structure .= '</li>';
                }
                $structure .= '</ul>';
                $structure .= '</div>';
            } else if ($view === 'with_ol') {
                $structure .= '<div class="wpview_text_view">';
                $structure .= '<ol class="wpview_ol">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    $structure .= esc_html($single_data['value']);
                    $structure .= '</li>';
                }
                $structure .= '</ol>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
