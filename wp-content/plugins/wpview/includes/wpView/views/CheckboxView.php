<?php

namespace wpView\views;

class CheckboxView extends View {

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
                    $structure .= '<tr class="wpview_checkbox_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    if (!is_array($single_data['value'])) {
                        $structure .= esc_html($single_data['value']);
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            if ($k !== 0) {
                                $structure .= ', ';
                            }
                            $structure .= esc_html($value);
                        }
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'with_icons') {
                $structure .= '<div class="wpview_checkbox_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class=" wpview_checkbox">';
                    $structure .= '<span class="wpview_field_label wpview_checked">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</span>';
                    if (!is_array($single_data['value'])) {
                        $structure .= esc_html($single_data['value']);
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            if ($k !== 0) {
                                $structure .= ', ';
                            }
                            $structure .= esc_html($value);
                        }
                    }
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_checkbox_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>';
                    if (!is_array($single_data['value'])) {
                        $structure .= esc_html($single_data['value']);
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            if ($k !== 0) {
                                $structure .= ', ';
                            }
                            $structure .= esc_html($value);
                        }
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            } else if ($view === 'with_ul') {
                $structure .= '<div class="wpview_checkbox_view">';
                $structure .= '<ul class="wpview_ul">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    if (!is_array($single_data['value'])) {
                        $structure .= esc_html($single_data['value']);
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            if ($k !== 0) {
                                $structure .= ', ';
                            }
                            $structure .= esc_html($value);
                        }
                    }
                    $structure .= '</li>';
                }
                $structure .= '</ul>';
                $structure .= '</div>';
            } else if ($view === 'with_ol') {
                $structure .= '<div class="wpview_checkbox_view">';
                $structure .= '<ol class="wpview_ol">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    if (!is_array($single_data['value'])) {
                        $structure .= esc_html($single_data['value']);
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            if ($k !== 0) {
                                $structure .= ', ';
                            }
                            $structure .= esc_html($value);
                        }
                    }
                    $structure .= '</li>';
                }
                $structure .= '</ol>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
